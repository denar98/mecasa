<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientMeeting extends CI_Controller {

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
    $this->load->model('custom_model');
	}

	public function index()
	{
    $data['clients'] = $this->db->get("clients")->result();
    $data['meetings'] = $this->custom_model->getAllClientMeeting()->result();

		$this->load->view('template/head.html');
		$this->load->view('client_meeting/index.html',$data);
		$this->load->view('template/foot.html');
	}
	public function calendar()
	{
    $data['clients'] = $this->db->get("clients")->result();
    $data['meetings'] = $this->custom_model->getAllClientMeeting()->result();

		$this->load->view('template/head.html');
		$this->load->view('client_meeting/calendar.php',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllClientMeeting()
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
            4=>'client_status',
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
      $client_id = $this->input->post('client_id');
      $meeting_date = $this->input->post('meeting_date');
      $meeting_time = $this->input->post('meeting_time');
      $meeting_location = $this->input->post('meeting_location');
      $meeting_type = $this->input->post('meeting_type');
      $meeting_status = "Belum Selesai";

      $data = array(
        'client_id' => $client_id,
        'meeting_date' => $meeting_date,
        'meeting_time' => $meeting_time,
        'meeting_location' => $meeting_location,
        'meeting_type' => $meeting_type,
        'meeting_status' => $meeting_status,
      );
      $add = $this->crud_model->createData('client_meetings',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('ClientMeeting');
      }

    }
    public function addActionCalendar()
    {
      $client_id = $this->input->post('client_id');
      $meeting_date = $this->input->post('meeting_date');
      $meeting_time = $this->input->post('meeting_time');
      $meeting_location = $this->input->post('meeting_location');
      $meeting_type = $this->input->post('meeting_type');
      $meeting_status = "Belum Selesai";

      $data = array(
        'client_id' => $client_id,
        'meeting_date' => $meeting_date,
        'meeting_time' => $meeting_time,
        'meeting_location' => $meeting_location,
        'meeting_type' => $meeting_type,
        'meeting_status' => $meeting_status,
      );
      $add = $this->crud_model->createData('client_meetings',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('ClientMeeting/calendar');
      }

    }

    public function updateAction()
    {
      $client_meeting_id = $this->input->post('client_meeting_id');
      $client_id = $this->input->post('client_id');
      $meeting_date = $this->input->post('meeting_date');
      $meeting_time = $this->input->post('meeting_time');
      $meeting_location = $this->input->post('meeting_location');
      $meeting_type = $this->input->post('meeting_type');
      $meeting_status =  $this->input->post('meeting_status');

      $data = array(
        'client_id' => $client_id,
        'meeting_date' => $meeting_date,
        'meeting_time' => $meeting_time,
        'meeting_location' => $meeting_location,
        'meeting_type' => $meeting_type,
        'meeting_status' => $meeting_status,
      );

      $where="client_meeting_id=".$client_meeting_id;
      $update = $this->crud_model->updateData('client_meetings',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('ClientMeeting');
      }
    }

    public function deleteAction($client_meeting_id)
    {
      $where="client_meeting_id=".$client_meeting_id;
      $delete = $this->crud_model->deleteData('client_meetings',$where);
      if($delete){
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('ClientMeeting');

      }
    }

    public function getClientMeeting()
    {
      $client_meeting_id = $this->input->post('client_meeting_id');
      $client_meeting = $this->custom_model->getAllClientMeetingById($client_meeting_id)->row();
      echo json_encode($client_meeting);

    }

    public function getAllClientMeetingJson()
    {
      $client_meeting_id = $this->input->post('client_meeting_id');
      $client_meeting = $this->custom_model->getAllClientMeeting();
      // echo json_encode($client_meeting);

      $eventsArr = array(); 
      foreach($client_meeting->result() as $client_meeting){
        $title = $client_meeting->meeting_type." dengan ". $client_meeting->client_name;
        $start = $client_meeting->meeting_date."  ". $client_meeting->meeting_time;
        array_push($eventsArr, "{title:'".$client_meeting->meeting_type." dengan ". $client_meeting->client_name."',start:'".$start."'}");   
      }

    //   while($row = $result->fetch_assoc()){ 
    //     array_push($eventsArr, $row); 
    //   } 

    // if($result->num_rows > 0){ 
    //     while($row = $result->fetch_assoc()){ 
    //         array_push($eventsArr, $row); 
    //     } 
    // } 
    
    // Render event data in JSON format 
    echo json_encode($eventsArr);

    }
}
