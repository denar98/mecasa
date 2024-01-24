<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
	}

	public function index()
	{
    $data['users'] = $this->db->get("users")->result();

		$this->load->view('template/head.html');
		$this->load->view('user/index.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllUser()
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
            0=>'username',
            1=>'user_fullname',
            2=>'role',
            3=>'last_login',
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
        $users = $this->db->get('users');
        $data = array();
        foreach($users->result() as $rows)
        {

            $data[]= array(
                $rows->username,
                $rows->user_fullname,
                $rows->role,
                $rows->last_login,
                '<a href="#" class="btn btn-warning mr-1 mt-1" title="Edit" style="padding-left:15px; padding-right:15px;" onclick="getUser('.$rows->user_id.')" data-toggle="modal" data-target="#updateModal" title="Edit"><i class="fa fa-pencil"></i></a>
                 <a href="'.base_url().'User/deleteAction/'.$rows->user_id.'" class="btn btn-danger mr-1 mt-1" title="Edit" style="padding-left:15px; padding-right:15px;" title="Hapus"><i class="fa fa-trash"></i></a>'
            );
        }
        $total_users = $this->totalUsers();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_users,
            "recordsFiltered" => $total_users,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalUsers()
    {
        $query = $this->db->select('COUNT(*) as num')
                 ->from('users')
                 ->get();
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function addAction()
    {
      $user_fullname = $this->input->post('user_fullname');
      $username = $this->input->post('username');
      $password = md5($this->input->post('password'));
      $role = $this->input->post('role');

      $data = array(
        'user_fullname' => $user_fullname,
        'username' => $username,
        'password' => $password,
        'role' => $role,
      );
      $add = $this->crud_model->createData('users',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('User');
      }

    }

    public function updateAction()
    {
      $user_id = $this->input->post('user_id');
      $user_fullname = $this->input->post('user_fullname');
      $username = $this->input->post('username');
      $password = md5($this->input->post('password'));
      $role = $this->input->post('role');

      $data = array(
        'user_fullname' => $user_fullname,
        'username' => $username,
        'password' => $password,
        'role' => $role,
      );

      $where="user_id=".$user_id;
      $update = $this->crud_model->updateData('users',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('User');
      }
    }

    public function deleteAction($user_id)
    {
      $where="user_id=".$user_id;
      $delete = $this->crud_model->deleteData('users',$where);
      if($delete){
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('User');

      }
    }

    public function getUser()
    {
      $user_id = $this->input->post('user_id');
      $where = "user_id=".$user_id;
      $user = $this->crud_model->readData('*','users',$where)->row();
      echo json_encode($user);

    }
}
