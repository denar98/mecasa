<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/course_guide/general/urls.html
	 */

 	public function __construct()
	{
		parent::__construct();
    error_reporting(0);
    if($this->session->userdata('login_status')!='logged'){
			$this->session->set_flashdata("error", 'Please Login Before You Access This Page');
			redirect('Login');
		}
    $this->load->model('datatable_model');
    $this->load->model('crud_model');
    $this->load->model('custom_model');
    $this->load->library('upload');
    date_default_timezone_set('Asia/Jakarta');

	}
   
	public function index()
	{
    $data['projects'] = $this->db->get("projects")->result();
    $data['clients'] = $this->db->get("clients")->result();

		$this->load->view('template/head.html');
		$this->load->view('project/index.html',$data);
		$this->load->view('template/foot.html');
	}
	public function create()
	{
    $data['projects'] = $this->db->get("projects")->result();
    $data['clients'] = $this->db->get("clients")->result();
    $data['users'] = $this->db->get("users")->result();

		$this->load->view('template/head.html');
		$this->load->view('project/create.html',$data);
		$this->load->view('template/foot.html');
	}
	public function createProgress($project_id,$project_detail_id)
	{
    $data['project_id'] = $project_id; 
    $data['project_detail_id'] = $project_detail_id; 
		$this->load->view('template/head.html');
		$this->load->view('project/create_progress.html',$data);
		$this->load->view('template/foot.html');
	}
	public function editProgress($project_id,$project_detail_id,$progress_history_id)
	{
    $data['project_id'] = $project_id; 
    $data['project_detail_id'] = $project_detail_id; 
    $data['progress_history_id'] = $progress_history_id; 

    $where = "progress_history_id=".$progress_history_id;
    $data['progress_history'] = $this->crud_model->readData('*','progress_histories',$where)->row();


		$this->load->view('template/head.html');
		$this->load->view('project/edit_progress.html',$data);
		$this->load->view('template/foot.html');
	}
	public function edit($project_id)
	{
    $data['project'] =  $this->custom_model->getDetailDataProject($project_id)->row();
    $data['clients'] = $this->db->get("clients")->result();
    $data['users'] = $this->db->get("users")->result();
    $data['quotations'] = $this->db->get("quotations")->result();

		$this->load->view('template/head.html');
		$this->load->view('project/edit.html',$data);
		$this->load->view('template/foot.html');
	}
	public function detail($project_id)
	{
    $data['project'] = $this->custom_model->getDetailDataProject($project_id)->row();
    $data['attachments'] = $this->custom_model->getAttachmentProject($project_id)->result();
    $data['financials'] = $this->custom_model->getFinancialProject($project_id)->result();
    $data['financial_keluar_total'] = $this->custom_model->getFinancialProjectTotal($project_id,'Uang Keluar')->row();
    $data['financial_masuk_total'] = $this->custom_model->getFinancialProjectTotal($project_id,'Uang Masuk')->row();
		$this->load->view('template/head.html');
		$this->load->view('project/detail.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getProjectTable()
  {
    $project_detail_status = $this->input->post("project_status");
    $project_detail_type = $this->input->post("project_type");
    if($project_detail_status==''){
      $project_detail_status = null;
    }
    if($project_detail_type==''){
      $project_detail_type = 'All';
    }

    $draw = intval($this->input->post("draw"));
    $start = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));
    $order = $this->input->post("order");
    $search = $this->input->post("search");
    $search = $search['value'];
    $col = 0;
    $dir = "desc";
    if (!empty($order)) {
      foreach ($order as $o) {
        $col = $o['column'];
        $dir = $o['dir'];
      }
    }

    if ($dir != "asc" && $dir != "desc") {
      $dir = "desc";
    }
    $valid_columns = array(
      0 => 'project_name',
      1 => 'start_date',
      2 => 'deadline',
      3 => 'project_detail_nominal',
      6 => 'project_detail_status',
    );
    if (!isset($valid_columns[$col])) {
      $order = null;
    } else {
      $order = $valid_columns[$col];
    }
    if ($order != null) {
      $this->db->order_by($order, $dir);
    }

    $x=0;
		foreach ($valid_columns as $sterm) // loop kolom 
    {
        if (!empty($search)) // jika datatable mengirim POST untuk search
        {
            if ($x === 0) // looping pertama
            {
                $this->db->group_start();
                $this->db->like($sterm, $search);
            } else {
                $this->db->or_like($sterm, $search);
            }
            if (count($valid_columns) - 1 == $x) //looping terakhir
                $this->db->group_end();
        }
        $x++;
    }
		

    $this->db->limit($length, $start);
    
    $project = $this->custom_model->getDataProjectsTable($project_detail_status,$project_detail_type);
    $data = array();
    $i = 1;
    foreach ($project->result() as $rows) {
      $financial_project = $this->custom_model->getInFinancialProject($rows->project_id)->row();
      $financial_keluar_total = $this->custom_model->getFinancialProjectTotal($rows->project_id,'Uang Keluar')->row();
      $financial_masuk_total = $this->custom_model->getFinancialProjectTotal($rows->project_id,'Uang Masuk')->row();

      // $base_url = base_url()."Project/detail/";
      // $url="'$base_url$rows->project_id'";
      $data[] = array(
        // $i,
        '<a href="'.base_url().'Project/detail/'.$rows->project_id.'" data-toggle="tooltip" data-placement="bottom" title="'.$rows->client_name.'">'.$rows->project_name.'</a>',
        // $rows->client_name,
        date_format(date_create($rows->start_date),"d M Y") ,
        date_format(date_create($rows->deadline),"d M Y"),
        "Rp ".number_format($rows->project_detail_nominal),
        // number_format($rows->profit_estimation),
        // number_format($financial_project->uang_masuk),
        "Rp ".number_format($rows->project_detail_nominal-$financial_project->uang_masuk),
        "Rp ".number_format($financial_masuk_total->total - $financial_keluar_total->total),
        $rows->project_detail_status
      );

      $i++;
    }
    // $total_project = $this->totalProject();
    $query = $this->custom_model->getDataProjectsTableRow($project_detail_status,$project_detail_type);
    
    $result = $query->row();
    if (isset($result)){
      $total_project = $result->num;
    }else{
      $total_project = 0;
    }

    $output = array(
      "draw" => $draw,
      "recordsTotal" => $total_project,
      "recordsFiltered" => $total_project,
      "data" => $data
    );
    echo json_encode($output);
    exit();
  }

  public function fetchProject()
  {
    $output = '';

    
    $keyword = $this->input->post('keyword');
    $order_status = $this->input->post('order_status');
    $project_type = $this->input->post('project_type');

    // if($keyword != 'null'){
      $data = $this->custom_model->getDataProjects($this->input->post('limit'), $this->input->post('start'),$keyword,$order_status,$project_type);
    // }
    // else if($keyword == 'null'){
    //   $data = $this->custom_model->getDataProjects($this->input->post('limit'), $this->input->post('start'),'null');

    // }

    if($data->num_rows() > 0)
    {
     foreach($data->result() as $row)
     {


      if($row->project_detail_type=="Design"){
        $project_type_image = 'project_type-01.png';
      }
      else{
        $project_type_image = 'project_type-02.png';
      }


      if($row->assign_to==null){
        $assign = "Not Assign";
        $btn_status = '';
      }else{
        $assign = $row->assign_to;
        $btn_status = 'disabled="disabled"';
      }

      $rem = strtotime($row->deadline) - time();
      $days_remaining = floor($rem / 86400);
      $hours_remaining  = floor(($rem % 86400) / 3600);
      $due_in = $days_remaining.'d, '.$hours_remaining.'h'; 
      $due_color = "black"; 
      if($days_remaining<=0 && $hours_remaining<=0){
        $due_in = "Late";
        $due_color = "danger";
      }

      $financial_project = $this->custom_model->getInFinancialProject($row->project_id)->row();
      $financial_keluar_total = $this->custom_model->getFinancialProjectTotal($row->project_id,'Uang Keluar')->row();
      $financial_masuk_total = $this->custom_model->getFinancialProjectTotal($row->project_id,'Uang Masuk')->row();

      // date_format(date_create($row->order_deadline),"d M Y")
      // echo "There are $days_remaining days and $hours_remaining hours left";

      $project_id = "'$row->project_id'";
      $output .= '
      
      <div class="row">
        <div class="col-xl-12 col-lg-12 mt-1 mb-2">
          <!-- <div class="project-box" style="padding:5px 15px !important;"><span class="badge badge-'.$badge_color.'">'.$row->order_status.'</span> -->
            <div class="row">
              <div class="media col-md-3  mt-2 mb-1">
              <img class="img mr-4 rounded" src="'.base_url().'assets/images/'.$project_type_image.'" style="width:55px;" alt="" data-original-title="" title="">
              <div class="media-body pt-2">
                  <a href="'.base_url().'Project/detail/'.$row->project_id.'"><h6>'.$row->project_name.'</h6></a>
                  <h7 class="mb-0 mt-2">'.$row->project_date.'</h7>
                </div>
              </div>
              <div class="media col-md-2 mt-2 mb-1">
                <div class="media-body pt-2">
                  <h6 class="text-'.$due_color.'">'.$due_in.'</h6>
                  <h7 class="mb-0 mt-2">Deadline</h7>
                </div>
              </div>
              <div class="media col-md-2 mt-2 mb-1">
                <div class="media-body pt-2">
                  <h6>'.number_format($row->project_detail_nominal).'</h6>
                  <h7 class="mb-0 mt-2">Nominal</h7>
                </div>
              </div>
              <div class="media col-md-2 mt-2 mb-1">
                <div class="media-body pt-2">
                  <h6>'.number_format($row->project_detail_nominal-$financial_project->uang_masuk).'</h6>
                  <h7 class="mb-0 mt-2">Sisa</h7>
                </div>
              </div>
              <div class="media col-md-2 mt-2 mb-1">
                <div class="media-body pt-2">
                  <h6>'.number_format($financial_masuk_total->total - $financial_keluar_total->total).'</h6>
                  <h7 class="mb-0 mt-2">Saldo</h7>
                </div>
              </div>

              <div class="media col-md-1 mt-2 mb-1">
                <div class="media-body pt-2">
                  <button class="btn btn-'.$service_color.'" style="padding: 1px 30px; display:block; width: 95px; cursor:context-menu;">'.$service_package_name.'</button>
                  <button class="btn btn-secondary mt-1 btn-not-assign" data-assign="'.$assign.'" '.$btn_status.'   onclick="assignOrder('.$project_id.')" style="padding: 1px 10px; display:block;">'.$assign.'</button>
                </div>
              </div>
            </div>
            <div class="project-status mt-0">
            <!--<div class="progress" style="height: 5px">
                <div class="progress-bar-animated '.$progress_color.' progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              </div> -->
            </div>
          </div>
        </div>
        <hr>
      </div>';
     }
    }
    echo $output;
  }
  public function fetchProjectFilter()
  {
    $output = '';

    
    $keyword = $this->input->post('keyword');
    $project_status = $this->input->post('project_status');
    $project_type = $this->input->post('project_type');
    $client_id = $this->input->post('client_id');

    // if($keyword != 'null'){
      $data = $this->custom_model->getDataProjects($this->input->post('limit'), $this->input->post('start'),$keyword,$project_status,$project_type,$client_id);
    // }
    // else if($keyword == 'null'){
    //   $data = $this->custom_model->getDataProjects($this->input->post('limit'), $this->input->post('start'),'null');

    // }

    if($data->num_rows() > 0)
    {
     foreach($data->result() as $row)
     {

      $financial_project = $this->custom_model->getInFinancialProject($row->project_id)->row();

      $progress = $row->project_detail_percentage;
      $progress = $row->project_detail_percentage."%";
     
      $base_url = base_url()."Project/detail/";
      $url="'$base_url$row->project_id'";
      if($row->project_detail_type=="Design"){
        $badge_color = 'primary';
      }
      else if($row->project_detail_type=="Produksi"){
        $badge_color = 'secondary';
      }
      else{
        $badge_color = 'warning';
      }
      $financial_keluar_total = $this->custom_model->getFinancialProjectTotal($row->project_id,'Uang Keluar')->row();
      $financial_masuk_total = $this->custom_model->getFinancialProjectTotal($row->project_id,'Uang Masuk')->row();
      $output .= '
      <div class="row">
        <div class="col-xl-12 col-lg-12 mt-2">
          <div class="project-box" id="'.$row->project_id.'" style="cursor:pointer;" onclick="window.location='.$url.'">
          
            <span class="badge badge-'.$badge_color.'">Project '.$row->project_detail_type.' </span>
            <div class="row">
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Nama Project</h6>
                  <p>'.$row->project_name.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Nama Klien</h6>
                  <p>'.$row->client_name.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Tanggal Start</h6>
                  <p>'.date_format(date_create($row->start_date),"d M Y").'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Deadline</h6>
                  <p>'.date_format(date_create($row->deadline),"d M Y").'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Project Nominal</h6>
                  <p>'.number_format($row->project_detail_nominal).'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Estimasi Profit</h6>
                  <p>'.number_format($row->profit_estimation).'</p>
                </div>
              </div>
            </div>
            <div class="project-status mt-2 text-'.$badge_color.'">
              <div class="media mb-0">
                <span>'.$row->project_detail_status.'</span>
                <span class="ml-1 mr-1">|</span>
                <p>Progress : '.$row->project_detail_percentage.'% </p>
                <div class="media-body text-right">
                <p class=" ml-2 text-right"> 
                <span class="text-success"> Masuk : Rp. '.number_format($financial_project->uang_masuk).'  </span>
                <span class="ml-2 mr-0"> | </span>
                <span class="ml-2 text-danger"> Sisa :  Rp. '.number_format($row->project_detail_nominal-$financial_project->uang_masuk).'</span>
                <span class="ml-2 mr-0"> | </span>
                <span class="ml-2 text-info"> Saldo :  Rp. '.number_format($financial_masuk_total->total - $financial_keluar_total->total).'</span>
                </p>
                
                </div>
              </div>
              <div class="progress" style="height: 7px">
                <div class="progress-bar-animated bg-'.$badge_color.' progress-bar-striped" role="progressbar" style="width: '.$progress.'" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
           
    
            </div>
          </div>
        </div>
      </div>';
     }
    }
    echo $output;
  }

  public function fetchProgress($project_detail_id)
  {
    $output = '';
    $data = $this->custom_model->getDataProgress($this->input->post('limit'), $this->input->post('start'),$project_detail_id);
    $i=5;
    if($data->num_rows() > 0)
    {
     foreach($data->result() as $row)
      {
        $i++;

        $output .= '
        

          <div class="media" style="align-items:start; padding-bottom:10px; margin-top:0px;">
            <div class="activity-dot-secondary" style="margin-top:18px;"></div>
            <div class="media-body"> <h2 class="mb-0">
                <h2>
                  <button class="btn btn-link btn-block text-left" style="padding-left:0px; padding-top:15px;" type="button" data-toggle="collapse" data-target="#collapse'.$i.'" aria-expanded="false" aria-controls="collapse'.$i.'">
                    <span style="color:#000000;">Updated By '.$row->user_fullname.'</span> 
                    <span class="ml-2">'.$row->progress_type.'</span> 
                    <span class="ml-2" style="font-style:italic; ">'.date_format(date_create($row->progress_time),"d M, H:i").'</span>
                  </button>
                </h2>
                <div class="collapse" id="collapse'.$i.'">
                  <hr>
                  <span class="mb-0 mt-0 text-secondary text-left pull-left" style="top:10px;"><h6>'.$row->progress_type.'</h6></span>
                  <span class="mb-0 mt-0 text-secondary text-right" style="top:10px;"><h6>Progress : '.$row->progress_percentage.'%</h6></span>
                  <div class="progress  mb-1" style="height: 7px">
                    <div class="progress-bar-animated bg-secondary progress-bar-striped" role="progressbar" style="width: '.$row->progress_percentage.'%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <a href="'.base_url().'Project/editProgress/'.$row->project_id.'/'.$row->project_detail_id.'/'.$row->progress_history_id.'"><span class="mb-0 mt-0 text-primary text-right pull-right" style="cursor:pointer;">Edit Progress</span></a>

                  <p>'.$row->progress_note.'</p>
                  </div>
            </div>
          </div>
          ';

      }
    }

    echo $output;
  }

  public function addAction()
  {
    $project_name = $this->input->post('project_name');
    $client_id = $this->input->post('client_id');
    $user_id = $this->input->post('user_id');
    $project_detail_type = $this->input->post('project_detail_type');
    $project_detail_nominal = str_replace( ',', '', $this->input->post('project_detail_nominal'));
    $profit_estimation = str_replace( ',', '', $this->input->post('profit_estimation')); 
    $project_date = $this->input->post('project_date');
    // $start_date = $this->input->post('start_date');
    $deadline = $this->input->post('deadline');
    // $brief = $this->input->post('brief');
    // $attachment_name = $this->input->post('attachment_name');
    $project_detail_status = "Progress";
    $project_detail_percentage = "0";
    $project_id="1";
    $project_detail_id="1";
    $project_row = $this->db->limit(1)->order_by('project_id','desc')->get('projects')->row();
    $project_detail_row = $this->db->limit(1)->order_by('project_detail_id','desc')->get('project_details')->row();
    $client_row = $this->db->where('client_id',$client_id)->get('clients')->row();

    if($project_row->project_id !=0 || $project_row->project_id != ''){
      $project_id = $project_row->project_id + 1;
    }

    if($project_detail_row->project_detail_id !=0 || $project_detail_row->project_detail_id != ''){
      $project_detail_id = $project_detail_row->project_detail_id + 1;
    }

    $data_project = array(
      'project_name' => $project_name,
      'client_id' => $client_id,
      'project_date' => $project_date,
    );

    $data_detail_project = array(
      'project_id' => $project_id,
      'project_detail_type' => $project_detail_type,
      'project_detail_nominal' => $project_detail_nominal,
      'profit_estimation' => $profit_estimation,
      'start_date' => $project_date,
      'deadline' => $deadline,
      'user_id' => $user_id,
      'brief' => $brief,
      'project_detail_percentage' => $project_detail_percentage,
      'project_detail_status' => $project_detail_status,
    );


    $floor_plan_attachments = [];
   
    $floor_plan_count = count($_FILES['floor_plan_files']['name']);
  
    for($i=0;$i<$floor_plan_count;$i++){
  
      if(!empty($_FILES['floor_plan_files']['name'][$i])){
  
        if (!is_dir('assets/requirement/'.$client_row->client_name.'/attachments')) {
          mkdir('assets/requirement/'.$client_row->client_name.'/attachments', 0777, TRUE);
        }
        $path = './assets/requirement/'.$client_row->client_name.'/attachments';
        $_FILES['file']['name'] = $_FILES['floor_plan_files']['name'][$i];
        $_FILES['file']['type'] = $_FILES['floor_plan_files']['type'][$i];
        $_FILES['file']['tmp_name'] = $_FILES['floor_plan_files']['tmp_name'][$i];
        $_FILES['file']['error'] = $_FILES['floor_plan_files']['error'][$i];
        $_FILES['file']['size'] = $_FILES['floor_plan_files']['size'][$i];

        $config['upload_path'] = $path; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd|zip|rar';
        $config['overwrite'] = FALSE;
        $config['file_name'] = $_FILES['floor_plan_files']['name'][$i];

        
        $this->upload->initialize($config);

        if($this->upload->do_upload('file')){
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];
 
          $floor_plan_attachments['totalFiles'][] = $filename;

          $data_attachments = array(
            'project_detail_id' => $project_detail_id,
            'attachment_name' => $filename,
            'project_detail_attachment_type' => 'Floor Plan',
            'project_detail_attachment_ext' =>$uploadData['file_ext'],
            'project_detail_attachment_width' => $uploadData['image_width'],
            'project_detail_attachment_height' => $uploadData['image_height'],
        );
          $this->crud_model->createData('project_detail_attachments',$data_attachments);
      
        }
      }

    }

    $reference_files_attachments = [];
   
    $reference_files_count = count($_FILES['reference_files']['name']);
  
    for($i=0;$i<$reference_files_count;$i++){
  
      if(!empty($_FILES['reference_files']['name'][$i])){
  
        if (!is_dir('assets/requirement/'.$client_row->client_name.'/attachments')) {
          mkdir('assets/requirement/'.$client_row->client_name.'/attachments', 0777, TRUE);
        }
        $path = './assets/requirement/'.$client_row->client_name.'/attachments';
        $_FILES['file']['name'] = $_FILES['reference_files']['name'][$i];
        $_FILES['file']['type'] = $_FILES['reference_files']['type'][$i];
        $_FILES['file']['tmp_name'] = $_FILES['reference_files']['tmp_name'][$i];
        $_FILES['file']['error'] = $_FILES['reference_files']['error'][$i];
        $_FILES['file']['size'] = $_FILES['reference_files']['size'][$i];

        $config['upload_path'] = $path; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd|zip|rar';
        $config['overwrite'] = FALSE;
        $config['file_name'] = $_FILES['reference_files']['name'][$i];

        
        $this->upload->initialize($config);

        if($this->upload->do_upload('file')){
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];
 
          $reference_files_attachments['totalFiles'][] = $filename;

          $reference_files_data_attachments = array(
            'project_detail_id' => $project_detail_id,
            'attachment_name' => $filename,
            'project_detail_attachment_type' => 'Reference',
            'project_detail_attachment_ext' =>$uploadData['file_ext'],
            'project_detail_attachment_width' => $uploadData['image_width'],
            'project_detail_attachment_height' => $uploadData['image_height'],
        );
          $this->crud_model->createData('project_detail_attachments',$reference_files_data_attachments);
      
        }
      }

    }

    $existing_photo_attachments = [];
   
    $existing_photo_count = count($_FILES['existing_photo_files']['name']);
  
    for($i=0;$i<$existing_photo_count;$i++){
  
      if(!empty($_FILES['existing_photo_files']['name'][$i])){
  
        if (!is_dir('assets/requirement/'.$client_row->client_name.'/attachments')) {
          mkdir('assets/requirement/'.$client_row->client_name.'/attachments', 0777, TRUE);
        }
        $path = './assets/requirement/'.$client_row->client_name.'/attachments';
        $_FILES['file']['name'] = $_FILES['existing_photo_files']['name'][$i];
        $_FILES['file']['type'] = $_FILES['existing_photo_files']['type'][$i];
        $_FILES['file']['tmp_name'] = $_FILES['existing_photo_files']['tmp_name'][$i];
        $_FILES['file']['error'] = $_FILES['existing_photo_files']['error'][$i];
        $_FILES['file']['size'] = $_FILES['existing_photo_files']['size'][$i];

        $config['upload_path'] = $path; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd|zip|rar';
        $config['overwrite'] = FALSE;
        $config['file_name'] = $_FILES['existing_photo_files']['name'][$i];

        
        $this->upload->initialize($config);

        if($this->upload->do_upload('file')){
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];
 
          $existing_photo_attachments['totalFiles'][] = $filename;

          $existing_photo_files_data_attachments = array(
            'project_detail_id' => $project_detail_id,
            'attachment_name' => $filename,
            'project_detail_attachment_type' => 'Existing Photo',
            'project_detail_attachment_ext' =>$uploadData['file_ext'],
            'project_detail_attachment_width' => $uploadData['image_width'],
            'project_detail_attachment_height' => $uploadData['image_height'],
        );
          $this->crud_model->createData('project_detail_attachments',$existing_photo_files_data_attachments);
      
        }
      }

    }
    
    $add_project = $this->crud_model->createData('projects',$data_project);
  
    if($add_project){
      $add_project = $this->crud_model->createData('project_details',$data_detail_project);
      $this->session->set_flashdata("success", "Your Data Has Been Added !");
      redirect('Project/');
    }


  }
  public function updateAction()
  {
    $project_id = $this->input->post('project_id');
    $quotation_number = $this->input->post('quotation_number');
    $project_detail_id = $this->input->post('project_detail_id');
    $project_name = $this->input->post('project_name');
    $client_id = $this->input->post('client_id');
    $user_id = $this->input->post('user_id');
    $project_detail_type = $this->input->post('project_detail_type');
    $project_detail_nominal = str_replace( ',', '', $this->input->post('project_detail_nominal'));
    $profit_estimation = str_replace( ',', '', $this->input->post('profit_estimation')); 
    $project_date = $this->input->post('project_date');
    $start_date = $this->input->post('start_date');
    $deadline = $this->input->post('deadline');
    // $brief = $this->input->post('brief');
    // $attachment_name = $this->input->post('attachment_name');
    // $project_detail_status = "New";
    $project_detail_percentage = "0";


    $data_project = array(
      'project_name' => $project_name,
      'client_id' => $client_id,
      'project_date' => $project_date,
    );
    $data_quotation = array(
      'project_id' => $project_id,
      'quotation_status' => 'Deal',
    );

    $data_detail_project = array(
      'project_detail_type' => $project_detail_type,
      'project_detail_nominal' => $project_detail_nominal,
      'profit_estimation' => $profit_estimation,
      'start_date' => $start_date,
      'deadline' => $deadline,
      'user_id' => $user_id,
      // 'brief' => $brief,
    );


    // $attachments = [];
   
    // $count = count($_FILES['files']['name']);
  
    // for($i=0;$i<$count;$i++){
  
    //   if(!empty($_FILES['files']['name'][$i])){
  
    //     $_FILES['file']['name'] = $_FILES['files']['name'][$i];
    //     $_FILES['file']['type'] = $_FILES['files']['type'][$i];
    //     $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
    //     $_FILES['file']['error'] = $_FILES['files']['error'][$i];
    //     $_FILES['file']['size'] = $_FILES['files']['size'][$i];

    //     $config['upload_path'] = 'assets/attachments'; 
    //     $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd';
    //     $config['file_name'] = $_FILES['files']['name'][$i];
        
    //     $this->upload->initialize($config);

    //     if($this->upload->do_upload('file')){
    //       $uploadData = $this->upload->data();
    //       $filename = $uploadData['file_name'];
 
    //       $attachments['totalFiles'][] = $filename;

    //       $data_attachments = array(
    //         'project_detail_id' => $project_detail_id,
    //         'attachment_name' => $filename,
    //       );
    //       $add_attachments = $this->crud_model->createData('project_detail_attachments',$data_attachments);
      
    //     }
    //   }

    // }

    $where_project="project_id=".$project_id;
    $update_project = $this->crud_model->updateData('projects',$data_project,$where_project);
    
  
    if($update_project){
      $where_detail_project="project_detail_id=".$project_detail_id;
      $update_detail_project = $this->crud_model->updateData('project_details',$data_detail_project,$where_detail_project);
      $where_quotation="quotation_number='".$quotation_number."'";
      $update_quotations = $this->crud_model->updateData('quotations',$data_quotation,$where_quotation);
      $this->session->set_flashdata("success", "Your Data Has Been Updated !");
      redirect('Project/');
    }


  }
  public function changeStatusAction()
  {
    $project_id = $this->input->post('project_id');
    $project_detail_id = $this->input->post('project_detail_id');
    $project_detail_status = $this->input->post('project_detail_status');


    $data_detail_project = array(
      'project_detail_status' => $project_detail_status,
    );

    $where_detail_project="project_detail_id=".$project_detail_id;
    $update_detail_project = $this->crud_model->updateData('project_details',$data_detail_project,$where_detail_project);
    
  
    if($update_detail_project){
      $this->session->set_flashdata("success", "Your Status Order Has Been Updated !");
      redirect('Project/detail/'.$project_id);
    }


  }
  public function addProgressAction()
  {
    $project_id = $this->input->post('project_id');
    $project_detail_id = $this->input->post('project_detail_id');
    $progress_type = $this->input->post('progress_type');
    $progress_note = $this->input->post('progress_note');
    $project_detail_percentage = $this->input->post('project_detail_percentage');
    $user_id = $this->session->userdata('user_id');
    $progress_time = Date("Y-m-d H:i:s");

    if($progress_type == "Preview Awal"){
      $project_detail_status = "Preview Awal";
    }
    else if($progress_type == "Revisi"){
      $project_detail_status = "Progress";
    }
    else if($progress_type == "Final"){
      $project_detail_status = "Done";
    }
    $data_progress_history = array(
      'project_detail_id' => $project_detail_id,
      'progress_type' => $progress_type,
      'progress_note' => $progress_note,
      'progress_time' => $progress_time,
      'progress_percentage' => $project_detail_percentage,
      'user_id' => $user_id,
    );

    $data_project_detail = array(
      'project_detail_status' => $project_detail_status,
      'project_detail_percentage' => $project_detail_percentage,
    );

   
    
    $add_progress_history = $this->crud_model->createData('progress_histories',$data_progress_history);
  
    if($add_progress_history){
      $where="project_detail_id=".$project_detail_id;
      $update_project_detail = $this->crud_model->updateData('project_details',$data_project_detail,$where);
      $this->session->set_flashdata("success", "Your Data Has Been Added !");
      redirect('Project/detail/'.$project_id);
    }


  }
  public function updateProgressAction()
  {
    $project_id = $this->input->post('project_id');
    $project_detail_id = $this->input->post('project_detail_id');
    $progress_history_id = $this->input->post('progress_history_id');
    $progress_type = $this->input->post('progress_type');
    $progress_note = $this->input->post('progress_note');
    $project_detail_percentage = $this->input->post('project_detail_percentage');

    if($progress_type == "Preview Awal"){
      $project_detail_status = "Preview Awal";
    }
    else if($progress_type == "Revisi"){
      $project_detail_status = "Progress";
    }
    else if($progress_type == "Final"){
      $project_detail_status = "Done";
    }
    $data_progress_history = array(
      'progress_type' => $progress_type,
      'progress_note' => $progress_note,
      'progress_percentage' => $project_detail_percentage,
    );

    $data_project_detail = array(
      'project_detail_status' => $project_detail_status,
      'project_detail_percentage' => $project_detail_percentage,
    );

   
    $where_history = "progress_history_id=".$progress_history_id;
    $update_progress_history = $this->crud_model->updateData('progress_histories',$data_progress_history,$where_history);
  
    if($update_progress_history){
      $where="project_detail_id=".$project_detail_id;
      $update_project_detail = $this->crud_model->updateData('project_details',$data_project_detail,$where);
      $this->session->set_flashdata("success", "Your Data Has Been Updateded !");
      redirect('Project/detail/'.$project_id);
    }


  }
  public function addHistoryAction()
  {
    $project_id = $this->input->post('project_id');
    $project_detail_id = $this->input->post('project_detail_id');
    $financial_note = $this->input->post('financial_note');
    $financial_date = $this->input->post('financial_date').' '.Date('H:i:s');
    $financial_ops_type = $this->input->post('financial_ops_type');
    $financial_type = $this->input->post('financial_type');
    $financial_in_type = $this->input->post('financial_in_type');
    $financial_nominal =  str_replace( ',', '', $this->input->post('financial_nominal'));
    $financial_pic = $this->input->post('financial_pic');

      if(!empty($_FILES['attachments']['name'])){
  
        $config['upload_path'] = 'assets/attachments'; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd';
        $config['file_name'] = $_FILES['attachments']['name'];
        $this->load->library('upload', $config);

        $this->upload->initialize($config);
        if(!$this->upload->do_upload('attachments')){
          $data['error'] = $this->upload->display_errors(); 
          print_r($data['error']);
        }
        else{
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];
        }
      }
      else{
        $filename = "null";
      }

      $financial_row = $this->custom_model->getSaldoFinancialProject($project_id)->row();
      if($financial_row == null || $financial_row == ''){
        $financial_saldo = $financial_nominal;
      }else{
        if($financial_type == 'Uang Masuk'){
          $financial_saldo = $financial_row->financial_saldo + $financial_nominal ;
        }else{
          $financial_saldo = $financial_row->financial_saldo - $financial_nominal ;
        }
      }
      $data_financial_history = array(
        'project_detail_id' => $project_detail_id,
        'financial_note' => $financial_note,
        'financial_date' => $financial_date,
        'financial_ops_type' => $financial_ops_type,
        'financial_type' => $financial_type,
        'financial_nominal' => $financial_nominal,
        'financial_pic' => $financial_pic,
        'attachment_name' => $filename,
        'financial_saldo' => $financial_saldo,
      );
      
      $add_financial_project = $this->crud_model->createData('project_financials',$data_financial_history);
    
      if($add_financial_project){

        if($financial_ops_type == 'Operasional Mecasa'){

          $financial_mecasa_row = $this->custom_model->getSaldoFinancialMecasa()->row();

          if($financial_mecasa_row == null || $financial_mecasa_row == ''){
            $financial_saldo = $financial_nominal;
            if($financial_type == 'Uang Masuk'){
              $financial_type_mecasa = 'Uang Keluar';
              $financial_note_mecasa =  $financial_note.' Untuk Project: '.$financial_row->project_name;
            }else{
              $financial_type_mecasa = 'Uang Masuk';
              $financial_note_mecasa =  $financial_note.' Dari Project: '.$financial_row->project_name;
            }
            
          }else{
            if($financial_type == 'Uang Masuk'){
              $financial_saldo = $financial_mecasa_row->financial_saldo - $financial_nominal ;
              $financial_type_mecasa = 'Uang Keluar';
              $financial_note_mecasa =  $financial_note.' Untuk Project: '.$financial_row->project_name;
            }else{
              $financial_saldo = $financial_mecasa_row->financial_saldo + $financial_nominal ;
              $financial_type_mecasa = 'Uang Masuk';
              $financial_note_mecasa =  $financial_note.' Dari Project: '.$financial_row->project_name;
            }
          }
  

          $data_financial_mecasa = array(
            'project_financials_id' => $financial_row->project_financials_id+1,
            'financial_note' => $financial_note_mecasa,
            'financial_date' => $financial_date,
            'financial_type' => $financial_type_mecasa,
            'financial_out_type' => $financial_in_type,
            'financial_nominal' => $financial_nominal,
            'financial_pic' => $financial_pic,
            'financial_saldo' => $financial_saldo,
          );
          $add_financial_mecasa = $this->crud_model->createData('mecasa_financials',$data_financial_mecasa);
        }

        
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Project/detail/'.$project_id);
      }

  }
  public function updateHistoryAction()
  {
    $mecasa_financial_id = $this->input->post('mecasa_financial_id');
    $project_financials_id = $this->input->post('project_financials_id');
    $project_id = $this->input->post('project_id');
    $project_detail_id = $this->input->post('project_detail_id');
    $financial_note = $this->input->post('financial_note');
    $financial_date = $this->input->post('financial_date').' '.Date('H:i:s');
    $financial_ops_type = $this->input->post('financial_ops_type');
    $financial_type = $this->input->post('financial_type');
    $financial_type = $this->input->post('financial_type');
    $financial_in_type = $this->input->post('financial_in_type');
    $financial_nominal =  str_replace( ',', '', $this->input->post('financial_nominal'));
    $financial_pic = $this->input->post('financial_pic');

      if(!empty($_FILES['attachments']['name'])){
  
        $config['upload_path'] = 'assets/attachments'; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd';
        $config['file_name'] = $_FILES['attachments']['name'];
        $this->load->library('upload', $config);

        $this->upload->initialize($config);
        if(!$this->upload->do_upload('attachments')){
          $data['error'] = $this->upload->display_errors(); 
          print_r($data['error']);
        }
        else{
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];
        }
      }
      else{
        $filename = "null";
      }

      $financial_row = $this->custom_model->getSaldoFinancialProject($project_id)->row();
      $financial_by_id_row = $this->custom_model->getSaldoFinancialProjectById($project_financials_id)->row();

      if($financial_row == null || $financial_row == ''){
        $financial_saldo = $financial_nominal;
      }else{
        if($financial_type == 'Uang Masuk'){
          $financial_saldo = ($financial_row->financial_saldo - $financial_by_id_row->financial_nominal ) + $financial_nominal ;
        }else{
          $financial_saldo = ($financial_row->financial_saldo + $financial_by_id_row->financial_nominal) - $financial_nominal ;
        }
      }
      if(!empty($_FILES['attachments']['name'])){
        $data_financial_history = array(
          'project_detail_id' => $project_detail_id,
          'financial_note' => $financial_note,
          'financial_date' => $financial_date,
          'financial_ops_type' => $financial_ops_type,
          'financial_type' => $financial_type,
          'financial_nominal' => $financial_nominal,
          'financial_pic' => $financial_pic,
          'attachment_name' => $filename,
          'financial_saldo' => $financial_saldo,
        );
      }
      else{
        $data_financial_history = array(
          'project_detail_id' => $project_detail_id,
          'financial_note' => $financial_note,
          'financial_date' => $financial_date,
          'financial_ops_type' => $financial_ops_type,
          'financial_type' => $financial_type,
          'financial_nominal' => $financial_nominal,
          'financial_pic' => $financial_pic,
          'financial_saldo' => $financial_saldo,
        );
      }

      
      $update_financial_project = $this->crud_model->updateData('project_financials',$data_financial_history,'project_financials_id='.$project_financials_id);
    
      if($update_financial_project){

        if($financial_ops_type == 'Operasional Mecasa'){

          $financial_mecasa_row = $this->custom_model->getSaldoFinancialMecasaById($mecasa_financial_id)->row();

          if($financial_mecasa_row == null || $financial_mecasa_row == ''){
            $financial_saldo = $financial_nominal;
            if($financial_type == 'Uang Masuk'){
              $financial_type_mecasa = 'Uang Keluar';
              $financial_note_mecasa =  $financial_note.' Untuk Project: '.$financial_row->project_name;
            }else{
              $financial_type_mecasa = 'Uang Masuk';
              $financial_note_mecasa =  $financial_note.' Dari Project: '.$financial_row->project_name;
            }
            
          }else{
            if($financial_type == 'Uang Masuk'){
              $financial_saldo = ($financial_mecasa_row->financial_saldo + $financial_mecasa_row->financial_nominal) - $financial_nominal ;
              $financial_type_mecasa = 'Uang Keluar';
              $financial_note_mecasa =  $financial_note.' Untuk Project: '.$financial_row->project_name;
            }else{
              $financial_saldo = ($financial_mecasa_row->financial_saldo - $financial_mecasa_row->financial_nominal) + $financial_nominal ;
              $financial_type_mecasa = 'Uang Masuk';
              $financial_note_mecasa =  $financial_note.' Dari Project: '.$financial_row->project_name;
            }
          }
  

          $data_financial_mecasa = array(
            'financial_note' => $financial_note_mecasa,
            'financial_date' => $financial_date,
            'financial_type' => $financial_type_mecasa,
            'financial_out_type' => $financial_in_type,
            'financial_nominal' => $financial_nominal,
            'financial_pic' => $financial_pic,
            'financial_saldo' => $financial_saldo,
          );
          $add_financial_mecasa = $this->crud_model->updateData('mecasa_financials',$data_financial_mecasa,'mecasa_financial_id ='.$mecasa_financial_id);
        }

        
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('Project/detail/'.$project_id);
      }

  }

  function uploadImageSummernote(){
    if(isset($_FILES["image"]["name"])){
      $config['upload_path'] = 'assets/requirement_attachments/';
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $this->upload->initialize($config);
      if(!$this->upload->do_upload('image')){
        $this->upload->display_errors();
        return FALSE;
      }else{
        $data = $this->upload->data();

        $config['image_library']='gd2';
        $config['source_image']='assets/requirement_attachments/'.$data['file_name'];
        $config['create_thumb']= FALSE;
        $config['maintain_ratio']= TRUE;
        $config['quality']= '60%';
        $config['width']= 800;
        $config['height']= 800;
        $config['new_image']= 'assets/requirement_attachments/'.$data['file_name'];
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        echo base_url().'assets/requirement_attachments/'.$data['file_name'];
        }
    }
  }
    
  //Delete image summernote
  function deleteImageSummernote(){
    $src = $this->input->post('src');
    $file_name = str_replace(base_url(), '', $src);
    if(unlink($file_name))
    {
      echo 'File Delete Successfully';
    }
  }

  public function getHistoryProgressById()
  {
    $progress_history_id = $this->input->post('progress_history_id');
    $where = "progress_history_id=".$progress_history_id;
    $progress_history = $this->crud_model->readData('*','progress_histories',$where)->row();
    echo json_encode($progress_history);


  }
  public function getHistoryFinancialById()
  {
    $project_financials_id = $this->input->post('project_financials_id');
    $where = "project_financials_id=".$project_financials_id;
    $financial_history = $this->custom_model->getHistoryFinancialById($project_financials_id)->row();
    echo json_encode($financial_history);

  }
  public function getQuotationByProjectId()
  {
    $project_id = $this->input->post('project_id');
    $where = "project_id=".$project_id;
    $quotation = $this->custom_model->getQuotationByProjectId($project_id)->row();
    echo json_encode($quotation);

  }

  public function deleteHistoryFinancialAction($project_id,$project_financials_id)
  {
    $where="project_financials_id=".$project_financials_id;
    $delete = $this->crud_model->deleteData('project_financials',$where);
    if($delete){
      $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
      redirect('Project/detail/'.$project_id);

    }
  }
}
