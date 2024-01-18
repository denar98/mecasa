<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

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
	 * @see https://codeigniter.com/client_guide/general/urls.html
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
	}

	public function index()
	{
    $data['clients'] = $this->db->get("clients")->result();

		$this->load->view('template/head.html');
		$this->load->view('client/index.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllClient()
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
            0=>'client_name',
            1=>'client_phone',
            2=>'client_address',
            3=>'client_interest',
            4=>'client_class',
            5=>'client_status',
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
        $clients = $this->db->get('clients');
        $data = array();
        foreach($clients->result() as $rows)
        {

            $data[]= array(
                $rows->client_name,
                $rows->client_phone,
                $rows->client_address,
                $rows->client_interest,
                $rows->client_class,
                $rows->client_status,
                '<a href="#" class="btn btn-warning mr-1" onclick="getClient('.$rows->client_id.')" data-toggle="modal" data-target="#updateModal" title="Edit"><i class="fa fa-pencil"></i></a>
                 <a href="'.base_url().'Client/deleteAction/'.$rows->client_id.'" class="btn btn-danger mr-1" title="Hapus"><i class="fa fa-trash"></i></a>'
            );
        }
        $total_clients = $this->totalClients();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_clients,
            "recordsFiltered" => $total_clients,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalClients()
    {
        $query = $this->db->select('COUNT(*) as num')
                 ->from('clients')
                 ->get();
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function addAction()
    {
      $client_name = $this->input->post('client_name');
      $client_phone = $this->input->post('client_phone');
      $client_address = $this->input->post('client_address');
      $client_interest = $this->input->post('client_interest');
      $client_class = $this->input->post('client_class');
      // $client_status = $this->input->post('client_status');

      $data = array(
        'client_name' => $client_name,
        'client_phone' => $client_phone,
        'client_address' => $client_address,
        'client_interest' => $client_interest,
        'client_class' => $client_class,
        // 'client_status' => $client_status,
      );
      $add = $this->crud_model->createData('clients',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Client');
      }

    }

    public function updateAction()
    {
      $client_id = $this->input->post('client_id');
      $client_name = $this->input->post('client_name');
      $client_phone = $this->input->post('client_phone');
      $client_address = $this->input->post('client_address');
      $client_interest = $this->input->post('client_interest');
      $client_class = $this->input->post('client_class');
      // $client_status = $this->input->post('client_status');

      $data = array(
        'client_name' => $client_name,
        'client_phone' => $client_phone,
        'client_address' => $client_address,
        'client_interest' => $client_interest,
        'client_class' => $client_class,
        // 'client_status' => $client_status,
      );
      $where="client_id=".$client_id;
      $update = $this->crud_model->updateData('clients',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('Client');
      }
    }

    public function deleteAction($client_id)
    {
      $where="client_id=".$client_id;
      $delete = $this->crud_model->deleteData('clients',$where);
      if($delete){
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('Client');

      }
    }
    public function updateStatusAction()
    {
      $client_id = $this->input->post('client_id');
      $client_status = $this->input->post('client_status');

      $data = array(
        'client_status' => $client_status,
      );
      $where="client_id=".$client_id;
      $update = $this->crud_model->updateData('clients',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data status Has Been Updated !");
        redirect('Client');
      }
    }

    public function getClient()
    {
      $client_id = $this->input->post('client_id');
      $where = "client_id=".$client_id;
      $client = $this->crud_model->readData('*','clients',$where)->row();
      echo json_encode($client);

    }
}
