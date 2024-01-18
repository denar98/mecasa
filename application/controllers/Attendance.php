<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {

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
    $this->load->model('datatable_model');
    $this->load->model('crud_model');
    date_default_timezone_set('Asia/Jakarta');
  }

	public function index()
	{
    $data['attendances'] = $this->db->get("attendances")->result();

		$this->load->view('template/head.html');
		$this->load->view('attendance/index.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllAttendance()
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
            0=>'date',
            1=>'user_fullname',
            2=>'check_in',
            3=>'check_out',
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
        $users = $this->db->select('users.user_fullname,attendances.*')
                 ->from('attendances')
                 ->join("users","users.user_id = attendances.user_id")
                 ->order_by("date","desc")
                 ->get();
        $data = array();
        foreach($users->result() as $rows)
        {

            $data[]= array(
                date_format(date_create($rows->date),"d M Y"),
                $rows->user_fullname,
                $rows->check_in,
                $rows->check_out,
                
            );
        }
        $total_attendances = $this->totalAttendance();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_attendances,
            "recordsFiltered" => $total_attendances,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalAttendance()
    {
        $query = $this->db->select('COUNT(*) as num')
                 ->from('attendances')
                 ->join("users","users.user_id = attendances.user_id")
                 ->get();
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function checkInAction()
    {

      $user_id = $this->input->post('user_id');
      $check_in = $this->input->post('check_in');
      $check_in_hours = $this->input->post('check_in_hours');
      $check_in_time = $check_in.' '.$check_in_hours;
      $data = array(
        'user_id' => $user_id,
        'date' => $check_in,
        'check_in' => $check_in_time,
      );
      $url = $this->uri->segment(1, 0);
      $add = $this->crud_model->createData('attendances',$data);
      if($add){
        $this->session->set_flashdata("success", "Absen Masuk Berhasil !");

        redirect($url);
      }

    }
    public function checkOutAction()
    {

      $user_id = $this->input->post('user_id');
      $check_out = $this->input->post('check_out');
      $check_out_hours = $this->input->post('check_out_hours');
      $check_out_time = $check_out.' '.$check_out_hours;
      $attendance = $this->db->where('user_id', $user_id)->where('date', $check_out)->get('attendances')->row();
      $url = $this->uri->segment(1, 0);

      $data = array(
        'user_id' => $user_id,
        'check_out' => $check_out_time,
      );

      $add = $this->crud_model->updateData('attendances',$data,'attendance_id ='.$attendance->attendance_id);
      if($add){
        $this->session->set_flashdata("success", "Absen Pulang Berhasil !");

        redirect($url);
      }


      

    }

    public function getAttendanceToday()
    {
      $user_id = $this->input->post('user_id');
      $date = Date('Y-m-d');
      $attendance = $this->db->where('user_id', $user_id)->where('date', $date)->get('attendances')->row();
      echo json_encode($attendance);

    }
}
