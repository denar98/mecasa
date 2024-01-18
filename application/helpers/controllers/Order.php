<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

 	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('login_status')!='logged'){
			$this->session->set_flashdata("error", 'Please Login Before You Access This Page');
			redirect('Login');
		}
    $this->load->helper(array('url','html','form'));
    $this->load->model('datatable_model');
    $this->load->model('crud_model');
    $this->load->model('order_model');
	}

	public function index()
	{
    // $this->scroll_pagination_model->fetch_data($this->input->post('limit'), $this->input->post('start'));
    // $data['orders_progress'] = $this->order_model->getDataProgressOrder()->result();
    // $data['orders_revision'] = $this->order_model->getDataRevisionOrder()->result();
    // $data['orders_delivered'] = $this->order_model->getDataDeliveredOrder()->result();
    // $data['orders_completed'] = $this->order_model->getDataCompletedOrder()->result();
		$this->load->view('template/head.html');
		$this->load->view('order/order.html');
		$this->load->view('template/foot.html');
	}

  public function fetchOrders()
  {
    $output = '';
    $data = $this->order_model->getDataOrder($this->input->post('limit'), $this->input->post('start'));
    if($data->num_rows() > 0)
    {
     foreach($data->result() as $row)
     {
       if($row->order_status==0){
         $order_status = "On Progress" ;
       }
       else if($row->order_status==1){
         $order_status = "Delivered" ;
       }
       else if($row->order_status==2){
         $order_status = "Revision" ;
       }
       else if($row->order_status==3){
         $order_status = "Done" ;
       }
      $output .= '
      <div class="row">
        <div class="col-xl-12 col-lg-12 mt-3">
          <div class="project-box"><span class="badge badge-primary">'.$order_status.'</span>
            <div class="row">
              <div class="media col-md-4 mb-1">
                <img class="img-50 mr-3 rounded-circle" src="<?=base_url()?>assets/images/user/3.jpg" alt="" data-original-title="" title="">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">'.$row->client_name.'</h6>
                </div>
              </div>
              <div class="media col-md-4 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-2">Price</h6>
                  <h7>$ '.$row->order_total.'</h7>
                </div>
              </div>
              <div class="media col-md-4 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-2">Status</h6>
                  <h7>'.$order_status.'</h7>
                </div>
              </div>
            </div>
            <div class="project-status mt-0">
              <div class="media mb-1">
                <div class="media-body text-right"><a href="#" class="">View Detail</a></div>
              </div>
              <div class="progress" style="height: 5px">
                <div class="progress-bar-animated bg-warning progress-bar-striped" role="progressbar" style="width: 25%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>';
     }
    }
    echo $output;
  }

	public function createOrder()
	{
    $data['clients'] = $this->db->get("clients")->result();
		$this->load->view('template/head.html');
		$this->load->view('order/create_order.html',$data);
		$this->load->view('template/foot.html');
	}
	public function createOrderProject($order_id)
	{
    $data['order_id'] = $order_id;
    $select_package = "services.service_name,service_packages.service_package_id,service_packages.service_package_name";
    $where_package = "services.service_id = service_packages.service_id";
    $join_package = "'service_packages', 'services.service_id=service_packages.service_id'";
    $data['packages'] = $this->order_model->getAllService()->result();
		$this->load->view('template/head.html');
		$this->load->view('order/create_order_project.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllOrder()
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
            0=>'orders_id',
            1=>'orders_name',
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
        $orderss = $this->db->get("orderss");
        $data = array();
        foreach($orderss->result() as $rows)
        {

            $data[]= array(
                $rows->orders_id,
                $rows->orders_name,
                '<a href="#" class="btn btn-warning mr-1" onclick="getOrder('.$rows->orders_id.')" data-toggle="modal" data-target="#updateModal"><i class="fa fa-pencil"></i></a>
                 <a href="'.base_url().'Order/deleteAction/'.$rows->orders_id.'" class="btn btn-danger mr-1"><i class="fa fa-trash"></i></a>'
            );
        }
        $total_orderss = $this->totalOrders();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_orderss,
            "recordsFiltered" => $total_orderss,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalOrders()
    {
        $query = $this->db->select("COUNT(*) as num")->get("orderss");
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function addAction()
    {
      $client_id = $this->input->post('client_id');
      $order_total = $this->input->post('order_total');
      $order_date = $this->input->post('order_date');
      $order_due_date = $this->input->post('order_due_date');
      $order_status = 0;
      $user_id = $this->session->userdata('user_id');

      $data = array(
        'client_id' => $client_id,
        'user_id' => $user_id,
        'order_total' => $order_total,
        'order_date' => Date("Y-m-d H:i:s",$order_date),
        'order_due_date' => Date("Y-m-d H:i:s",$order_due_date),
        'order_status' => $order_status
      );
      $add = $this->crud_model->createData('orders',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Order/createOrderProject/'.$add);
      }

    }
    public function addOrderProjectAction()
    {
      $order_id = $this->input->post('order_id');
      $service_package_id = $this->input->post('service_package_id');
      $project_brief = $this->input->post('project_brief');
      $project_notes = $this->input->post('project_notes');
      $project_status = 0;

      $data = array(
        'order_id' => $order_id,
        'service_package_id' => $service_package_id,
        'project_brief' => $project_brief,
        'project_notes' => $project_notes,
        'project_status' => $project_status
      );
      $add = $this->crud_model->createData('projects',$data);
      $this->session->set_flashdata("success", "Your Data Has Been Added !");
      redirect('Order/createOrderProject/'.$order_id);

    }

    public function updateAction()
    {
      $orders_id = $this->input->post('orders_id');
      $orders_name = $this->input->post('orders_name');

      $data = array(
        'orders_name' => $orders_name,
      );
      $where="orders_id=".$orders_id;
      $update = $this->crud_model->updateData('orderss',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('Order');
      }
    }

    public function deleteAction($orders_id)
    {
      $where="orders_id=".$orders_id;
      $delete = $this->crud_model->deleteData('orderss',$where);
      if($delete){
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('Order');

      }
    }

    public function getOrder()
    {
      $orders_id = $this->input->post('orders_id');
      $where = "orders_id=".$orders_id;
      $orders = $this->crud_model->readData('*','orderss',$where)->row();
      echo json_encode($orders);

    }

    function projectAttachment(){
        if(!empty($_FILES)){
            // File upload configuration
            echo "test";
            $uploadPath = './uploads/project_attachment/';
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = '*';

            // Load and initialize upload library
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            // Upload file to the server
            if($this->upload->do_upload('userfile')){
                $fileData = $this->upload->data();
                $this->db->order_by('project_id', 'DESC');
                $this->db->limit('1');
                $project_last_id_row = $this->db->get('projects')->row();
                if($project_last_id_row!=null){
                  $project_last_id = $project_last_id_row;
                }
                else{
                  $project_last_id = 1;
                }
                $token=$this->input->post('token_foto');
                $uploadData['project_id'] = $project_last_id;
                $uploadData['token'] = $token;
                $uploadData['file_name'] = $fileData['file_name'];
                $uploadData['uploaded_date'] = date("Y-m-d H:i:s");

                // Insert files info into the database
                $insert = $this->crud_model->createData('project_attachments',$uploadData);
                echo"uploaded";
            }
            else {
              $error = array('error' => $this->upload->display_errors());
              print_r($error);
              // $this->load->view('display', $error);
            }
        }
    }

    //Untuk menghapus foto
	function removeProjectAttachment(){

		//Ambil token foto
		$token=$this->input->post('token');

		$foto=$this->db->get_where('project_attachments',array('token'=>$token));


		if($foto->num_rows()>0){
			$hasil=$foto->row();
			$nama_foto=$hasil->file_name;
			if(file_exists($file='./uploads/project_attachment/'.$nama_foto)){
				unlink($file);
        echo "deleted";
			}else{
        echo "nope";
      }
			$this->db->delete('project_attachments',array('token'=>$token));

		}


		echo "{}";
	}
}
