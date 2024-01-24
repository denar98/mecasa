<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends CI_Controller {

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
		if($this->session->userdata('login_status')!='logged'){
			$this->session->set_flashdata("error", 'Please Login Before You Access This Page');
			redirect('Login');
		}
    $this->load->model('datatable_model');
    $this->load->model('crud_model');
	}

	public function index()
	{
    $data['classes'] = $this->db->get("classes")->result();
    $data['teachers'] = $this->db->get("teachers")->result();
    $data['pics'] = $this->db->get("pics")->result();

		$this->load->view('template/head.html');
		$this->load->view('classes/index.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllClasses()
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
            0=>'class_code',
            1=>'pic_name',
            2=>'teacher_name',
            3=>'class_address',
            4=>'class_day',
            5=>'class_hour',
            6=>'last_course',
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
        $classes = $this->db->select('classes.*,teachers.*,pics.*')
                 ->from('classes')
                 ->join("teachers","classes.teacher_id = teachers.teacher_id")
                 ->join("pics","classes.pic_id = pics.pic_id")
                 ->get();

        $data = array();
        foreach($classes->result() as $rows)
        {
            $data[]= array(
                $rows->class_code,
                $rows->pic_name,
                $rows->teacher_name,
                $rows->class_address,
                $rows->class_day,
                $rows->class_hour,
                $rows->last_course,
                '<a href="#" class="btn btn-warning mr-1 mt-1" title="Edit" style="padding-left:15px; padding-right:15px;" onclick="getClasses('.$rows->class_id.')" data-toggle="modal" data-target="#updateModal" title="Edit"><i class="fa fa-pencil"></i></a>
                 <a href="'.base_url().'Classes/deleteAction/'.$rows->class_id.'"class="btn btn-danger mr-1 mt-1" title="Edit" style="padding-left:15px; padding-right:15px;" title="Hapus"><i class="fa fa-trash"></i></a>'
            );
        }
        $total_classes = $this->totalClassess();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_classes,
            "recordsFiltered" => $total_classes,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalClassess()
    {
        $query = $this->db->select('COUNT(*) as num')
                ->from('classes')
                ->join("teachers","classes.teacher_id = teachers.teacher_id")
                ->join("pics","classes.pic_id = pics.pic_id")
                ->get();
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function addAction()
    {
      $pic_id = $this->input->post('pic_id');
      $teacher_id = $this->input->post('teacher_id');
      $class_code = $this->input->post('class_code');
      $class_address = $this->input->post('class_address');
      $class_day = $this->input->post('class_day');
      $class_hour = $this->input->post('class_hour');

      $data = array(
        'pic_id' => $pic_id,
        'teacher_id' => $teacher_id,
        'class_code' => $class_code,
        'class_address' => $class_address,
        'class_day' => $class_day,
        'class_hour' => $class_hour,
      );

      $add = $this->crud_model->createData('classes',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Classes');
      }

    }

    public function updateAction()
    {
      $class_id = $this->input->post('class_id');
      $pic_id = $this->input->post('pic_id');
      $teacher_id = $this->input->post('teacher_id');
      $class_code = $this->input->post('class_code');
      $class_address = $this->input->post('class_address');
      $class_day = $this->input->post('class_day');
      $class_hour = $this->input->post('class_hour');

      $data = array(
        'pic_id' => $pic_id,
        'teacher_id' => $teacher_id,
        'class_code' => $class_code,
        'class_address' => $class_address,
        'class_day' => $class_day,
        'class_hour' => $class_hour,
      );

      $where="class_id=".$class_id;
      $update = $this->crud_model->updateData('classes',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('Classes');
      }
    }

    public function deleteAction($class_id)
    {
      $where="class_id=".$class_id;
      $delete = $this->crud_model->deleteData('classes',$where);
      if($delete){
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('Classes');

      }
    }

    public function getClasses()
    {
      $class_id = $this->input->post('class_id');
      $where = "class_id=".$class_id;
      $course = $this->crud_model->readData('*','classes',$where)->row();
      echo json_encode($course);

    }
}
