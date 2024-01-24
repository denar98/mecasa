<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

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
	 * @see https://codeigniter.com/quotation_guide/general/urls.html
	 */

 	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('login_status')!='logged'){
			$this->session->set_flashdata("error", 'Please Login Before You Access This Page');
			redirect('Login');
		}
    $this->load->model('datatable_model');
    $this->load->model('crud_model');
    $this->load->model('custom_model');
	}

public function downloadQuotation($quotation_id)
    {
      $data['quotation'] = $this->custom_model->getQuotationById($quotation_id)->row(); 
      $data['quotation_details'] = $this->custom_model->getQuotationDetails($quotation_id)->result();
      $data['users'] = $this->db->get("users")->result();
  
      $data['clients'] = $this->db->get("clients")->result();

        // panggil library yang kita buat sebelumnya yang bernama pdfgenerator
        $this->load->library('pdfgenerator');
        
        // title dari pdf
        $this->data['title_pdf'] = 'Laporan Penjualan Toko Kita';
        
        // filename dari pdf ketika didownload
        $file_pdf = 'laporan_penjualan_toko_kita';
        // setting paper
        $paper = 'A4';
        //orientasi paper potrait / landscape
        $orientation = "portrait";
        
		    $html = $this->load->view('quotation/detail.html',$data);	    
        
        // run dompdf
        $this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
  }


	public function index()
	{
    $data['quotations'] = $this->db->get("quotations")->result();
    $data['clients'] = $this->db->get("clients")->result();
    $data['users'] = $this->db->get("users")->result();

		$this->load->view('template/head.html');
		$this->load->view('quotation/index.html',$data);
		$this->load->view('template/foot.html');
    
	}
	public function createQuotation()
	{
    $data['quotations'] = $this->db->get("quotations")->result();
    $data['clients'] = $this->db->get("clients")->result();

		$this->load->view('template/head.html');
		$this->load->view('quotation/create.html',$data);
		$this->load->view('template/foot.html');
	}
	public function editQuotation($quotation_id)
	{
    $data['quotation'] = $this->db->where('quotation_id',$quotation_id)->get('quotations')->row();
    $data['quotation_details'] = $this->custom_model->getQuotationDetails($quotation_id)->result();
    $data['users'] = $this->db->get("users")->result();

    $data['clients'] = $this->db->get("clients")->result();

		$this->load->view('template/head.html');
		$this->load->view('quotation/edit.html',$data);
		$this->load->view('template/foot.html');
	}
	
	public function detail($quotation_id)
	{
    $data['quotation'] = $this->custom_model->getQuotationById($quotation_id)->row(); 
    $data['quotation_details'] = $this->custom_model->getQuotationDetails($quotation_id)->result();
    $data['users'] = $this->db->get("users")->result();

    $data['clients'] = $this->db->get("clients")->result();

		$this->load->view('template/head.html');
		$this->load->view('quotation/detail.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllQuotation()
    {
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }
        $valid_columns = array(
            0=>'quotation_number',
            1=>'client_name',
            2=>'project_type',
            3=>'quotation_nominal',
            4=>'quotation_discount',
            4=>'quotation_cashback',
            5=>'quotation_total',
            6=>'quotation_sent_date',
            7=>'quotation_status',
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }

        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }
        }
        $this->db->limit($length,$start);
        $quotations = $this->custom_model->getQuotation();
        $data = array();
        foreach($quotations->result() as $rows)
        {

            $data[]= array(
                "<a href='".base_url()."Quotation/detail/".$rows->quotation_id."'>".$rows->quotation_number."</a>",
                $rows->client_name,
                $rows->project_type,
                "Rp ".number_format($rows->quotation_nominal),
                "Rp ".number_format($rows->quotation_discount),
                $rows->quotation_cashback."%",
                "Rp ".number_format($rows->quotation_total),
                date_format(date_create($rows->quotation_sent_date),"d M Y"),
                $rows->quotation_status,
                '<a href="'.base_url().'Quotation/editQuotation/'.$rows->quotation_id.'" class="btn btn-warning mr-1 mt-1" title="Edit" style="padding-left:15px; padding-right:15px;"><i class="fa fa-pencil" style="margin-right:0px !important;"></i></a>
                 <a href="'.base_url().'Quotation/deleteAction/'.$rows->quotation_id.'" class="btn btn-danger mr-1 mt-1" title="Hapus" style="padding-left:15px; padding-right:15px;"><i class="fa fa-trash" style="margin-right:0px !important;"></i></a>'
            );
        }
        $total_quotations = $this->totalQuotations();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_quotations,
            "recordsFiltered" => $total_quotations,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalQuotations()
    {
        $query = $this->db->select('COUNT(*) as num')
                 ->from('quotations')
                 ->get();
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function addAction()
    {
      // $quotation_number = $this->input->post('quotation_number');
      $client_id = $this->input->post('client_id');
      $project_name = $this->input->post('project_name');
      $project_type = $this->input->post('project_type');
      $quotation_nominal = str_replace( ',', '', $this->input->post('quotation_nominal'));
      $quotation_discount = str_replace( ',', '', $this->input->post('quotation_discount'));
      $quotation_cashback = str_replace( ',', '', $this->input->post('quotation_cashback'));
      $quotation_total = $quotation_nominal - $quotation_discount;
      $quotation_sent_date = Date('Y-m-d');
      $quotation_status = "Waiting";
      // $quotation_number = 'Q-'.Date('Y').'0001';

      $quotation_row = $this->db->limit(1)->order_by('quotation_id','desc')->get('quotations')->row();
      $quotation_id=1;
      if($quotation_row != ''){
        $quotation_id = $quotation_row->quotation_id + 1;
      }
      
      $quotation_number_rows = $this->db->query("SELECT MAX(RIGHT(quotation_number,4)) AS quotation_number_max FROM quotations WHERE YEAR(quotation_sent_date)= YEAR(CURDATE())");
      $quotation_number = "";
      if($quotation_number_rows->num_rows()>0){
          foreach($quotation_number_rows->result() as $quotation_number_row){
              $tmp = ((int)$quotation_number_row->quotation_number_max)+1;
              $quotation_number = sprintf("%04s", $tmp);
          }
      }else{
          $quotation_number = "0001";
      }

      $quotation_number = 'Q-'.Date('Y').$quotation_number;

        $quotation_data = array(
        'quotation_number' => $quotation_number,
        'client_id' => $client_id,
        'project_type' => $project_type,
        'project_name' => $project_name,
        'quotation_nominal' => $quotation_nominal,
        'quotation_discount' => $quotation_discount,
        'quotation_cashback' => $quotation_cashback,
        'quotation_total' => $quotation_total,
        'quotation_sent_date' => $quotation_sent_date,
        'quotation_status' => $quotation_status,
      );

      $quotation_item = $this->input->post('quotation_item');
      $quotation_size = $this->input->post('quotation_size'); 
      $quotation_price = str_replace( ',', '', $this->input->post('quotation_price')); 

      for($i=0;$i<sizeof($quotation_item);$i++)
      {
        $quotation_detail_data[$i] = array ('quotation_id' =>$quotation_id, 'quotation_item' => $quotation_item[$i], 'quotation_size' => $quotation_size[$i], 'quotation_price' => $quotation_price[$i]);
      }
      // $dataSet is an array of array

      $add = $this->crud_model->createData('quotations',$quotation_data);
      if($add){
        $this->db->insert_batch('quotation_details', $quotation_detail_data);
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Quotation');
      }

    }

    public function updateAction()
    {

      $quotation_id = $this->input->post('quotation_id');
      $quotation_number = $this->input->post('quotation_number');
      $client_id = $this->input->post('client_id');
      $project_type = $this->input->post('project_type');
      $project_name = $this->input->post('project_name');
      $quotation_nominal = str_replace( ',', '', $this->input->post('quotation_nominal'));
      $quotation_discount = str_replace( ',', '', $this->input->post('quotation_discount'));
      $quotation_cashback = str_replace( ',', '', $this->input->post('quotation_cashback'));
      $quotation_total = $quotation_nominal - $quotation_discount;
      $quotation_sent_date = Date('Y-m-d');
      $quotation_status = $this->input->post('quotation_status');
      $quotation_row = $this->db->limit(1)->order_by('quotation_id','desc')->get('quotations')->row();

      $user_id = $this->input->post('user_id');
      $profit_estimation = str_replace( ',', '', $this->input->post('profit_estimation')); 
      $start_date = $this->input->post('start_date');
      $deadline = $this->input->post('deadline');

      $project_detail_status = "Progress";
      $project_detail_percentage = "0";
      $project_id="1";
      $project_row = $this->db->limit(1)->order_by('project_id','desc')->get('projects')->row();
  
      if($project_row->project_id !=0 || $project_row->project_id != ''){
        $project_id = $project_row->project_id + 1;
      }

      $quotation_data = array(
        'quotation_number' => $quotation_number,
        'client_id' => $client_id,
        'project_id' => $project_id,
        'project_type' => $project_type,
        'project_name' => $project_name,
        'quotation_nominal' => $quotation_nominal,
        'quotation_discount' => $quotation_discount,
        'quotation_cashback' => $quotation_cashback,
        'quotation_total' => $quotation_total,
        'quotation_sent_date' => $quotation_sent_date,
        'quotation_status' => $quotation_status,
      );

      $quotation_item = $this->input->post('quotation_item');
      $quotation_size = $this->input->post('quotation_size'); 
      $quotation_price = str_replace( ',', '', $this->input->post('quotation_price')); 

      for($i=0;$i<sizeof($quotation_item);$i++)
      {
        $quotation_detail_data[$i] = array ('quotation_id' =>$quotation_id, 'quotation_item' => $quotation_item[$i], 'quotation_size' => $quotation_size[$i], 'quotation_price' => $quotation_price[$i]);
      }
      
      // $dataSet is an array of array
      $where="quotation_id=".$quotation_id;
      $update = $this->crud_model->updateData('quotations',$quotation_data,$where);
      if($update){
        if($quotation_status=='Deal'){
          
          $data_project = array(
            'project_name' => $project_name,
            'client_id' => $client_id,
            'project_date' => $quotation_sent_date,
          );

          $data_detail_project = array(
            'project_id' => $project_id,
            'project_detail_type' => $project_type,
            'project_detail_nominal' => $quotation_total,
            'profit_estimation' => $profit_estimation,
            'start_date' => $start_date,
            'deadline' => $deadline,
            'user_id' => $user_id,
            // 'brief' => $brief,
            'project_detail_percentage' => $project_detail_percentage,
            'project_detail_status' => $project_detail_status,
          );
          $add_project = $this->crud_model->createData('projects',$data_project);
          $add_project_details = $this->crud_model->createData('project_details',$data_detail_project);
          }

          $this->crud_model->deleteData('quotation_details',$where);
          $this->db->insert_batch('quotation_details', $quotation_detail_data);
          $this->session->set_flashdata("success", "Your Data Has Been Updated !");
          redirect('Quotation');
  
        }
        
    }


     
    

    public function deleteAction($quotation_id)
    {
      $where="quotation_id=".$quotation_id;
      $delete = $this->crud_model->deleteData('quotations',$where);
      if($delete){
        $this->crud_model->deleteData('quotation_details',$where);
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('Quotation');

      }
    }
    public function updateStatusAction()
    {
      $quotation_id = $this->input->post('quotation_id');
      $quotation_status = $this->input->post('quotation_status');

      $data = array(
        'quotation_status' => $quotation_status,
      );
      $where="quotation_id=".$quotation_id;
      $update = $this->crud_model->updateData('quotations',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data status Has Been Updated !");
        redirect('Quotation');
      }
    }

    public function getQuotation()
    {
      $quotation_id = $this->input->post('quotation_id');
      $where = "quotation_id=".$quotation_id;
      $quotation = $this->crud_model->readData('*','quotations',$where)->row();
      echo json_encode($quotation);

    }
}
