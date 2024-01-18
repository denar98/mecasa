<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studies extends CI_Controller {

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
    $this->load->model('custom_model');
	}

	public function index()
	{
    $data['studies'] = $this->db->get("studies")->result();
    $data['teachers'] = $this->db->get("teachers")->result();
    $data['pics'] = $this->db->get("pics")->result();

		$this->load->view('template/head.html');
		$this->load->view('studies/index.html',$data);
		$this->load->view('template/foot.html');
	}
	public function detail($class_id)
	{
    $data['row'] = $this->custom_model->getDetailDataClasses($class_id);
    $data['student_rows'] = $this->custom_model->getClassStudent($class_id);
    $data['courses'] = $this->db->get("courses")->result();
    $data['teachers'] = $this->db->get("teachers")->result();
		$this->load->view('template/head.html');
		$this->load->view('studies/detail.html',$data);
		$this->load->view('template/foot.html');
	}

  public function fetchClasses()
  {
    $output = '';
    $data = $this->custom_model->getDataClasses($this->input->post('limit'), $this->input->post('start'));
    if($data->num_rows() > 0)
    {
     foreach($data->result() as $row)
     {
      $last_course = substr($row->last_course,-2,2);
      $last_stage = substr($row->last_course,0,3);

      if($last_stage=="A 1"){
        if($last_course == "M1"){
          $progress = "20%";
        }
        if($last_course == "M2"){
          $progress = "40%";
        }
        if($last_course == "M3"){
          $progress = "60%";
        }
        if($last_course == "M4"){
          $progress = "80%";
        }
        if($last_course == "M5"){
          $progress = "100%";
        }
      }
      else if($last_stage=="A 2"){
        if($last_course == "M1"){
          $progress = "25%";
        }
        if($last_course == "M2"){
          $progress = "50%";
        }
        if($last_course == "M3"){
          $progress = "75%";
        }
        if($last_course == "M4"){
          $progress = "100%";
        }
      }
      else if($last_stage=="A 3"){
        if($last_course == "M1"){
          $progress = "30%";
        }
        if($last_course == "M2"){
          $progress = "60%";
        }
        if($last_course == "M3"){
          $progress = "100%";
        }
      }
      $base_url = base_url()."Studies/detail/";
      $url="'$base_url$row->class_id'";
      $output .= '
      <div class="row">
        <div class="col-xl-12 col-lg-12 mt-2">
          <div class="project-box" id="'.$row->class_id.'" style="cursor:pointer;" onclick="window.location='.$url.'">
            <span class="badge badge-primary">'.$row->last_course.'</span>
            <div class="row">
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Kode Kelas</h6>
                  <p>'.$row->class_code.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Nama Guru</h6>
                  <p>'.$row->teacher_name.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Nama PJ</h6>
                  <p>'.$row->pic_name.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Tempat</h6>
                  <p>'.$row->class_address.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Waktu</h6>
                  <p>'.$row->class_day.' | '.$row->class_hour.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Pertemuan Terakhir</h6>
                  <p>'.$row->last_studies.'</p>
                </div>
              </div>
            </div>
            <div class="project-status mt-2">
              <div class="progress" style="height: 5px">
                <div class="progress-bar-animated bg-success progress-bar-striped" role="progressbar" style="width: '.$progress.'" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>';
     }
    }
    echo $output;
  }
  public function fetchClassesFilter()
  {
    $output = '';
    $data = $this->custom_model->getDataClassesFilter($this->input->post('limit'), $this->input->post('start'), $this->input->post('keyword'));
    if($data->num_rows() > 0)
    {
     foreach($data->result() as $row)
     {
      $last_course = substr($row->last_course,-2,2);
      $last_stage = substr($row->last_course,0,3);

      if($last_stage=="A 1"){
        if($last_course == "M1"){
          $progress = "20%";
        }
        if($last_course == "M2"){
          $progress = "40%";
        }
        if($last_course == "M3"){
          $progress = "60%";
        }
        if($last_course == "M4"){
          $progress = "80%";
        }
        if($last_course == "M5"){
          $progress = "100%";
        }
      }
      else if($last_stage=="A 2"){
        if($last_course == "M1"){
          $progress = "25%";
        }
        if($last_course == "M2"){
          $progress = "50%";
        }
        if($last_course == "M3"){
          $progress = "75%";
        }
        if($last_course == "M4"){
          $progress = "100%";
        }
      }
      else if($last_stage=="A 3"){
        if($last_course == "M1"){
          $progress = "30%";
        }
        if($last_course == "M2"){
          $progress = "60%";
        }
        if($last_course == "M3"){
          $progress = "100%";
        }
      }
      $base_url = base_url()."Studies/detail/";
      $url="'$base_url$row->class_id'";
      $output .= '
      <div class="row">
        <div class="col-xl-12 col-lg-12 mt-2">
          <div class="project-box" id="'.$row->class_id.'" style="cursor:pointer;" onclick="window.location='.$url.'">
            <span class="badge badge-primary">'.$row->last_course.'</span>
            <div class="row">
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Kode Kelas</h6>
                  <p>'.$row->class_code.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Nama Guru</h6>
                  <p>'.$row->teacher_name.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Nama PJ</h6>
                  <p>'.$row->pic_name.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Tempat</h6>
                  <p>'.$row->class_address.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Waktu</h6>
                  <p>'.$row->class_day.' | '.$row->class_hour.'</p>
                </div>
              </div>
              <div class="media col-md-2 mb-1">
                <div class="media-body">
                  <h6 class="mb-0 mt-3">Pertemuan Terakhir</h6>
                  <p>'.$row->last_studies.'</p>
                </div>
              </div>
            </div>
            <div class="project-status mt-2">
              <div class="progress" style="height: 5px">
                <div class="progress-bar-animated bg-success progress-bar-striped" role="progressbar" style="width: '.$progress.'" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>';
     }
    }
    echo $output;
  }

  public function fetchStudies($class_id)
  {
    $output = '';
    $data = $this->custom_model->getDataStudies($this->input->post('limit'), $this->input->post('start'),$class_id);
    if($data->num_rows() > 0)
    {
     foreach($data->result() as $row)
     {
        $last_course = substr($row->last_course,-2,2);
        $last_stage = substr($row->last_course,0,3);
      }
      $base_url = base_url()."Studies/detail/";
      $url="'$base_url$row->class_id'";
      $student_rows = $this->custom_model->getDataStudentStudies($row->class_id,$row->study_date)->result();
      $output .= '
      <div class="project-box mt-2" id="'.$row->class_id.'" >
        <!-- <span class="badge badge-primary">'.$row->last_course.'</span> -->
        <div class="row">
          <div class="col-md-12 mb-1">
            <div class="media-body">
              <h5 class="mt-2"><span class="icon-calendar mr-1"></span> '. tanggal($row->study_date).'  <span class="mr-2 ml-2">|</span> <span class="icon-user mr-1 "></span> '.$row->teacher_name.'</h5>
              <span class="mb-0 mt-0 badge badge-primary last-course-badge">'.$row->last_course_study.'</span>
              <h6 class="mt-3 text-info">Siswa Yang Hadir</h6>
              <ul class="list-group list-group-flush mt-1 ">
              ';

                foreach ($student_rows as $student_row) {
                  $output .='
                  <li class="list-group-item list-group-item-action pl-1" data-toggle="collapse" data-target="#gradehistory'.$student_row->student_id.'_'.$student_row->study_date.'">
                    <i class="icon-user"></i>
                    <h7 class="mt-2">'.$student_row->student_name.' <span class="badge-grade"> <i class="fa fa-user"></i>  Guru : <b>'.$student_row->student_grade_teacher.'</b> </span> <span class="badge-grade"> <i class="fa fa-user"></i>  Walas : <b>'.$student_row->student_grade_pic.'</b> &nbsp;</span></h7>
                  </li>
                ';
              }
              $output .='
              </ul>
            </div>
          </div>
        </div>
      </div>';

    }
    echo $output;
  }


  public function addStudentAction()
  {
    $class_id = $this->input->post('class_id');
    $student_nik = $this->input->post('student_nik');

    $data = array(
      'class_id' => $class_id,
    );

    $where="student_nik=".$student_nik;
    $update = $this->crud_model->updateData('students',$data,$where);
    if($update){
      $this->session->set_flashdata("success", "Your Data Has Been Added !");
      redirect('Studies/detail/'.$class_id);
    }
  }
  public function addStudiesAction()
  {
    $course_id = $this->input->post('course_id');
    $class_id = $this->input->post('class_id');
    $teacher_id = $this->input->post('teacher_id');
    $study_date = strtotime($this->input->post('study_date'));
    $study_date = Date("Y-m-d", $study_date);
    $last_course = $this->input->post('last_course');
    $student_id_array = $this->input->post('student_id');
		for($i=0;$i<count($student_id_array);$i++){
			$student_id = $student_id_array[$i];
      $student_grade_teacher = $this->input->post('student_grade_teacher'.$student_id);
      $student_grade_pic = $this->input->post('student_grade_pic'.$student_id);

      $data = array(
        'course_id' => $course_id,
        'class_id' => $class_id,
        'study_date' => $study_date,
        'student_id' => $student_id,
        'teacher_id' => $teacher_id,
        'student_grade_teacher' => $student_grade_teacher,
        'student_grade_pic' => $student_grade_pic,
        'last_course' => $last_course,
      );

      $data_update = array(
        'last_course' => $last_course,
      );
      $where="class_id=".$class_id;

      $add = $this->crud_model->createData('studies',$data);
      $update = $this->crud_model->updateData('classes',$data_update,$where);
		}
    if($add && $update){
      $this->session->set_flashdata("success", "Your Data Has Been Added !");
      redirect('Studies/detail/'.$class_id);
    }


  }

  public function removeStudent($student_nik,$class_id)
  {

    $data = array(
      'class_id' => 999,
    );

    $where="student_nik=".$student_nik;
    $update = $this->crud_model->updateData('students',$data,$where);
    if($update){
      $this->session->set_flashdata("success", "Your Data Has Been Removed !");
      redirect('Studies/detail/'.$class_id);
    }
  }

  public function getAvarageStudentGrade()
  {
      $student_id = $this->input->post('student_id');
      $query = $this->db->select("AVG(student_grade_pic) as student_grade_pic_avg, AVG(student_grade_teacher) as student_grade_teacher_avg")->where('student_id',$student_id)->get("studies");
      $result = $query->row();
      $avarage = ($result->student_grade_pic_avg + $result->student_grade_teacher_avg) / 2;
      // if(isset($result)) return $result->num;
      echo round($avarage);
  }
  public function getAvarageAbsent()
  {
      $class_id = $this->input->post('class_id');
      $student_id = $this->input->post('student_id');
      $class_query = $this->db->select("COUNT(*) as study_total")->where('class_id',$class_id)->group_by('date(study_date)')->get("studies")->num_rows();
      $student_query = $this->db->select("COUNT(*) as student_study_total")->where('class_id',$class_id)->where('student_id',$student_id)->group_by('study_date')->get("studies")->num_rows();
      $avarage = ($student_query * 100) / $class_query;
      // if(isset($result)) return $result->num;
      echo round($avarage);
  }
  public function getTotalStudies()
  {
      $class_id = $this->input->post('class_id');
      $class_query = $this->db->select("COUNT(*) as study_total")->where('class_id',$class_id)->group_by('date(study_date)')->get("studies")->num_rows();
      echo $class_query;
  }

}
