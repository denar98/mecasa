<?php

class Custom_model extends CI_Model
{

  public function getAllClientMeeting()
  {

    $query = $this->db->select('clients.*,client_meetings.*')
             ->from('clients')
             ->join("client_meetings","clients.client_id = client_meetings.client_id")
             ->get();
    return $query;

  }
  public function getTodayClientMeeting()
  {

    $query = $this->db->select('clients.*,client_meetings.*')
             ->from('clients')
             ->join("client_meetings","clients.client_id = client_meetings.client_id")
             ->where("meeting_date", Date("Y-m-d"))
            //  ->limit(4, 0)
             ->order_by('meeting_time', 'ASC')
             ->get();
    return $query;

  }
  public function getAllClientMeetingById($client_meeting_id)
  {

    $query = $this->db->select('clients.*,client_meetings.*')
             ->from('clients')
             ->join("client_meetings","clients.client_id = client_meetings.client_id")
             ->where("client_meeting_id",$client_meeting_id)
             ->get();
    return $query;

  }
  public function getDataClasses($limit, $start)
  {

    $query = $this->db->select('classes.*,teachers.*,pics.*')
             ->from('classes')
             ->join("teachers","classes.teacher_id = teachers.teacher_id")
             ->join("pics","classes.pic_id = pics.pic_id")
             // ->order_by('order_id', 'DESC')
             ->limit($limit, $start)
             ->get();
    return $query;

  }
  public function getDataProjects($limit, $start, $keyword, $project_status, $project_type,$client_id)
  {

    // $array = array('projects.project_name' => $keyword, 'clients.client_name' => $keyword, 'project_details.start_date' => $keyword, 'project_details.deadline' => $keyword);

    $where = "projects.project_name LIKE '%".$keyword."%' OR clients.client_name LIKE '%".$keyword."%' OR project_details.start_date LIKE '%".$keyword."%' OR project_details.deadline LIKE '%".$keyword."%'";

    $query = $this->db->select('projects.*,project_details.*,clients.*')
             ->from('projects')
             ->join("project_details","projects.project_id = project_details.project_id")
             ->join("clients","projects.client_id = clients.client_id");
             // ->order_by('order_id', 'DESC')
             if( $project_type != 'All' ){
              $this->db->where('project_details.project_detail_type',$project_type);
             }
             if($project_status != 'All' ){
              $this->db->where('project_details.project_detail_status',$project_status);
             }
             if($client_id != 'All' ){
              $this->db->where('projects.client_id',$client_id);
             }
             if($keyword != ''){
              $this->db->where($where);
              // $this->db->like('projects.project_name',$keyword);
              // $this->db->or_like('clients.client_name',$keyword);
              // $this->db->or_like('project_details.start_date',$keyword);
              // $this->db->or_like('project_details.deadline',$keyword);
            }
            $this->db->order_by('project_date', 'desc');
            $this->db->limit($limit, $start);
            $query = $this->db->get();

            return $query;

  }
  public function getDataMecasaHistoryFinancial($limit, $start)
  {

    $query = $this->db->select('mecasa_financials.*')
    ->from('mecasa_financials')
    ->order_by('mecasa_financials.mecasa_financial_id','asc')
    ->limit($limit, $start);
    $query = $this->db->get();

   return $query;


  }
  public function getAllOnProgressProject()
  {

    // $array = array('projects.project_name' => $keyword, 'clients.client_name' => $keyword, 'project_details.start_date' => $keyword, 'project_details.deadline' => $keyword);


    $query = $this->db->select('projects.*,project_details.*,clients.*')
             ->from('projects')
             ->join("project_details","projects.project_id = project_details.project_id")
             ->join("clients","projects.client_id = clients.client_id");
            //  ->where('projects.client_id',);
            $this->db->limit(5, 0);
            $this->db->order_by('projects.projecT_id', 'desc');
            $query = $this->db->get();

            return $query;

  }
  public function getTotalOnProgressProject($project_detail_type)
  {

    // $array = array('projects.project_name' => $keyword, 'clients.client_name' => $keyword, 'project_details.start_date' => $keyword, 'project_details.deadline' => $keyword);


    $query = $this->db->select('COUNT(projects.project_id) as num')
             ->from('projects')
             ->join("project_details","projects.project_id = project_details.project_id")
             ->join("clients","projects.client_id = clients.client_id")
             ->where('project_detail_type',$project_detail_type)
             ->where('project_detail_status !="Done" ');
            $query = $this->db->get();

            return $query;

  }
  
  public function getTotalIncome($project_detail_type)
  {

    $query = $this->db->query("SELECT 
    SUM(IF(month = 'Jan', project_detail_nominal, 0)) AS 'Jan',
    SUM(IF(month = 'Feb', project_detail_nominal, 0)) AS 'Feb',
    SUM(IF(month = 'Mar', project_detail_nominal, 0)) AS 'Mar',
    SUM(IF(month = 'Apr', project_detail_nominal, 0)) AS 'Apr',
    SUM(IF(month = 'May', project_detail_nominal, 0)) AS 'May',
    SUM(IF(month = 'Jun', project_detail_nominal, 0)) AS 'Jun',
    SUM(IF(month = 'Jul', project_detail_nominal, 0)) AS 'Jul',
    SUM(IF(month = 'Aug', project_detail_nominal, 0)) AS 'Aug',
    SUM(IF(month = 'Sep', project_detail_nominal, 0)) AS 'Sep',
    SUM(IF(month = 'Oct', project_detail_nominal, 0)) AS 'Oct',
    SUM(IF(month = 'Nov', project_detail_nominal, 0)) AS 'Nov',
    SUM(IF(month = 'Dec', project_detail_nominal, 0)) AS 'Dec'
    FROM
    (SELECT MIN(DATE_FORMAT(project_date, '%b')) AS month, SUM(project_detail_nominal) as project_detail_nominal
     FROM projects join project_details on projects.project_id = project_details.project_id where project_detail_type = '$project_detail_type'
     group by YEAR(project_date), MONTH(project_date) order by  YEAR(project_date), MONTH(project_date)) as sale");

    return $query;

  }
  
  public function getTotalIncomeAll()
  {

    $query = $this->db->query("SELECT 
    SUM(IF(month = 'Jan', project_detail_nominal, 0)) AS 'Jan',
    SUM(IF(month = 'Feb', project_detail_nominal, 0)) AS 'Feb',
    SUM(IF(month = 'Mar', project_detail_nominal, 0)) AS 'Mar',
    SUM(IF(month = 'Apr', project_detail_nominal, 0)) AS 'Apr',
    SUM(IF(month = 'May', project_detail_nominal, 0)) AS 'May',
    SUM(IF(month = 'Jun', project_detail_nominal, 0)) AS 'Jun',
    SUM(IF(month = 'Jul', project_detail_nominal, 0)) AS 'Jul',
    SUM(IF(month = 'Aug', project_detail_nominal, 0)) AS 'Aug',
    SUM(IF(month = 'Sep', project_detail_nominal, 0)) AS 'Sep',
    SUM(IF(month = 'Oct', project_detail_nominal, 0)) AS 'Oct',
    SUM(IF(month = 'Nov', project_detail_nominal, 0)) AS 'Nov',
    SUM(IF(month = 'Dec', project_detail_nominal, 0)) AS 'Dec'
    FROM
    (SELECT MIN(DATE_FORMAT(project_date, '%b')) AS month, SUM(project_detail_nominal) as project_detail_nominal
     FROM projects join project_details on projects.project_id = project_details.project_id 
     group by YEAR(project_date), MONTH(project_date) order by  YEAR(project_date), MONTH(project_date)) as sale");

    return $query;

  }
  
  public function getTotalIncomeWhole()
  {

    $this->db->select('SUM(project_detail_nominal) as total')
    ->from('projects')
    ->join("project_details","projects.project_id = project_details.project_id");
    $query = $this->db->get();

    return $query;

  }
  public function getTotalProfitWhole()
  {

    $this->db->select('SUM(profit_estimation) as total')
    ->from('projects')
    ->join("project_details","projects.project_id = project_details.project_id");
    $query = $this->db->get();

    return $query;

  }

  public function getTotalUangMasuk()
  {

    $this->db->select('SUM(financial_nominal) as total')
    ->from('project_financials')
    ->where('financial_ops_type','Operasional Project')
    ->where('financial_type','Uang Masuk');
    $query = $this->db->get();

    return $query;

  }
  public function getTotalUangKeluar()
  {

    $this->db->select('SUM(financial_nominal) as total')
    ->from('project_financials')
    ->where('financial_ops_type','Operasional Project')
    ->where('financial_type','Uang Keluar');
    $query = $this->db->get();

    return $query;

  }
  
  public function getTotalProfit($project_detail_type)
  {

    $query = $this->db->query("SELECT 
    SUM(IF(month = 'Jan', profit_estimation, 0)) AS 'Jan',
    SUM(IF(month = 'Feb', profit_estimation, 0)) AS 'Feb',
    SUM(IF(month = 'Mar', profit_estimation, 0)) AS 'Mar',
    SUM(IF(month = 'Apr', profit_estimation, 0)) AS 'Apr',
    SUM(IF(month = 'May', profit_estimation, 0)) AS 'May',
    SUM(IF(month = 'Jun', profit_estimation, 0)) AS 'Jun',
    SUM(IF(month = 'Jul', profit_estimation, 0)) AS 'Jul',
    SUM(IF(month = 'Aug', profit_estimation, 0)) AS 'Aug',
    SUM(IF(month = 'Sep', profit_estimation, 0)) AS 'Sep',
    SUM(IF(month = 'Oct', profit_estimation, 0)) AS 'Oct',
    SUM(IF(month = 'Nov', profit_estimation, 0)) AS 'Nov',
    SUM(IF(month = 'Dec', profit_estimation, 0)) AS 'Dec'
    FROM
    (SELECT MIN(DATE_FORMAT(project_date, '%b')) AS month, SUM(profit_estimation) as profit_estimation
     FROM projects join project_details on projects.project_id = project_details.project_id where project_detail_type = '$project_detail_type'
     group by YEAR(project_date), MONTH(project_date) order by  YEAR(project_date), MONTH(project_date)) as sale");

    return $query;

  }
  
  public function getTotalProfitAll()
  {

    $query = $this->db->query("SELECT 
    SUM(IF(month = 'Jan', profit_estimation, 0)) AS 'Jan',
    SUM(IF(month = 'Feb', profit_estimation, 0)) AS 'Feb',
    SUM(IF(month = 'Mar', profit_estimation, 0)) AS 'Mar',
    SUM(IF(month = 'Apr', profit_estimation, 0)) AS 'Apr',
    SUM(IF(month = 'May', profit_estimation, 0)) AS 'May',
    SUM(IF(month = 'Jun', profit_estimation, 0)) AS 'Jun',
    SUM(IF(month = 'Jul', profit_estimation, 0)) AS 'Jul',
    SUM(IF(month = 'Aug', profit_estimation, 0)) AS 'Aug',
    SUM(IF(month = 'Sep', profit_estimation, 0)) AS 'Sep',
    SUM(IF(month = 'Oct', profit_estimation, 0)) AS 'Oct',
    SUM(IF(month = 'Nov', profit_estimation, 0)) AS 'Nov',
    SUM(IF(month = 'Dec', profit_estimation, 0)) AS 'Dec'
    FROM
    (SELECT MIN(DATE_FORMAT(project_date, '%b')) AS month, SUM(profit_estimation) as profit_estimation
     FROM projects join project_details on projects.project_id = project_details.project_id 
     group by YEAR(project_date), MONTH(project_date) order by  YEAR(project_date), MONTH(project_date)) as sale");

    return $query;

  }
  
  public function getProjectMasuk($project_detail_type)
  {

    $query = $this->db->query("SELECT 
    SUM(IF(month = 'Jan', project_masuk, 0)) AS 'Jan',
    SUM(IF(month = 'Feb', project_masuk, 0)) AS 'Feb',
    SUM(IF(month = 'Mar', project_masuk, 0)) AS 'Mar',
    SUM(IF(month = 'Apr', project_masuk, 0)) AS 'Apr',
    SUM(IF(month = 'May', project_masuk, 0)) AS 'May',
    SUM(IF(month = 'Jun', project_masuk, 0)) AS 'Jun',
    SUM(IF(month = 'Jul', project_masuk, 0)) AS 'Jul',
    SUM(IF(month = 'Aug', project_masuk, 0)) AS 'Aug',
    SUM(IF(month = 'Sep', project_masuk, 0)) AS 'Sep',
    SUM(IF(month = 'Oct', project_masuk, 0)) AS 'Oct',
    SUM(IF(month = 'Nov', project_masuk, 0)) AS 'Nov',
    SUM(IF(month = 'Dec', project_masuk, 0)) AS 'Dec'
    FROM
    (SELECT MIN(DATE_FORMAT(project_date, '%b')) AS month, COUNT(projects.project_id) as project_masuk
     FROM projects join project_details on projects.project_id = project_details.project_id where project_detail_type = '$project_detail_type'
     group by YEAR(project_date), MONTH(project_date) order by  YEAR(project_date), MONTH(project_date)) as sale");

    return $query;

  }
  public function getProjectMasukAll()
  {

    $query = $this->db->query("SELECT 
    SUM(IF(month = 'Jan', project_masuk, 0)) AS 'Jan',
    SUM(IF(month = 'Feb', project_masuk, 0)) AS 'Feb',
    SUM(IF(month = 'Mar', project_masuk, 0)) AS 'Mar',
    SUM(IF(month = 'Apr', project_masuk, 0)) AS 'Apr',
    SUM(IF(month = 'May', project_masuk, 0)) AS 'May',
    SUM(IF(month = 'Jun', project_masuk, 0)) AS 'Jun',
    SUM(IF(month = 'Jul', project_masuk, 0)) AS 'Jul',
    SUM(IF(month = 'Aug', project_masuk, 0)) AS 'Aug',
    SUM(IF(month = 'Sep', project_masuk, 0)) AS 'Sep',
    SUM(IF(month = 'Oct', project_masuk, 0)) AS 'Oct',
    SUM(IF(month = 'Nov', project_masuk, 0)) AS 'Nov',
    SUM(IF(month = 'Dec', project_masuk, 0)) AS 'Dec'
    FROM
    (SELECT MIN(DATE_FORMAT(project_date, '%b')) AS month, COUNT(projects.project_id) as project_masuk
     FROM projects join project_details on projects.project_id = project_details.project_id
     group by YEAR(project_date), MONTH(project_date) order by  YEAR(project_date), MONTH(project_date)) as sale");

    return $query;

  }
  
  public function getDetailDataProject($project_id)
  {

    $query = $this->db->select('projects.*,project_details.*,clients.*,users.*')
             ->from('projects')
             ->join("project_details","projects.project_id = project_details.project_id")
             ->join("clients","projects.client_id = clients.client_id")
             ->join("users","project_details.user_id = users.user_id")
             ->where('projects.project_id',$project_id);
            
            $query = $this->db->get();

            return $query;

  }
  public function getAttachmentProject($project_id)
  {

    $query = $this->db->select('project_detail_attachments.*,project_details.*,projects.*')
             ->from('project_detail_attachments')
             ->join("project_details","project_detail_attachments.project_detail_id = project_details.project_detail_id")
             ->join("projects","projects.project_id = project_details.project_id")
             ->where('projects.project_id',$project_id);
            
            $query = $this->db->get();

            return $query;

  }
  public function getSaldoFinancialProject($project_id)
  {

    $query = $this->db->select('project_financials.*,project_details.*,projects.*')
             ->from('project_financials')
             ->join("project_details","project_financials.project_detail_id = project_details.project_detail_id")
             ->join("projects","projects.project_id = project_details.project_id")
             ->where('projects.project_id',$project_id)
             ->order_by('project_financials.project_financials_id','desc')
             ->limit(1);
            $query = $this->db->get();

            return $query;

  }
  public function getSaldoFinancialProjectById($project_financials_id)
  {

    $query = $this->db->select('project_financials.*,project_details.*,projects.*')
             ->from('project_financials')
             ->join("project_details","project_financials.project_detail_id = project_details.project_detail_id")
             ->join("projects","projects.project_id = project_details.project_id")
             ->where('project_financials.project_financials_id',$project_financials_id)
             ->order_by('project_financials.project_financials_id','desc')
             ->limit(1);
            $query = $this->db->get();

            return $query;

  }
  public function getHistoryFinancialById($project_financials_id)
  {

    $query = $this->db->select('project_financials.*,mecasa_financials.mecasa_financial_id')
             ->from('project_financials')
             ->join("mecasa_financials","project_financials.project_financials_id = mecasa_financials.project_financials_id","left")
             ->where('project_financials.project_financials_id',$project_financials_id)
             ->order_by('project_financials.project_financials_id','desc')
             ->limit(1);
            $query = $this->db->get();

            return $query;

  }
  public function getHistoryMecasaFinancialById($mecasa_financial_id)
  {

    $query = $this->db->select('mecasa_financials.*')
             ->from('mecasa_financials')
             ->where('mecasa_financial_id',$mecasa_financial_id)
             ->limit(1);
            $query = $this->db->get();

            return $query;

  }
  public function getSaldoFinancialMecasa()
  {

    $query = $this->db->select('mecasa_financials.*')
             ->from('mecasa_financials')
             ->order_by('mecasa_financials.mecasa_financial_id','asc')
             ->limit(1);
            $query = $this->db->get();

            return $query;

  }
  public function getSaldoFinancialMecasaById($mecasa_financial_id)
  {

    $query = $this->db->select('mecasa_financials.*')
             ->from('mecasa_financials')
             ->order_by('mecasa_financials.mecasa_financial_id','asc')
             ->where('mecasa_financials.mecasa_financial_id',$mecasa_financial_id)
             ->limit(1);
            $query = $this->db->get();

            return $query;

  }
  public function getFinancialMecasa()
  {

    $query = $this->db->select('mecasa_financials.*')
             ->from('mecasa_financials')
             ->order_by('mecasa_financials.mecasa_financial_id','asc');
            $query = $this->db->get();

            return $query;

  }
  public function getInFinancialProject($project_id)
  {

    $query = $this->db->select('SUM(project_financials.financial_nominal) as uang_masuk, project_financials.*,project_details.*,projects.*')
             ->from('project_financials')
             ->join("project_details","project_financials.project_detail_id = project_details.project_detail_id")
             ->join("projects","projects.project_id = project_details.project_id")
             ->where('projects.project_id',$project_id)
             ->where('project_financials.financial_ops_type','Operasional Project')
             ->where('project_financials.financial_type','Uang Masuk');

            $query = $this->db->get();

            return $query;

  }
  public function getFinancialProject($project_id)
  {

    $query = $this->db->select('project_financials.*,project_details.*,projects.*')
             ->from('project_financials')
             ->join("project_details","project_financials.project_detail_id = project_details.project_detail_id")
             ->join("projects","projects.project_id = project_details.project_id")
             ->where('projects.project_id',$project_id);

            $query = $this->db->get();

            return $query;

  }
  public function getFinancialProjectTotal($project_id,$financial_type)
  {

    $query = $this->db->select('SUM(project_financials.financial_nominal) as total')
             ->from('project_financials')
             ->join("project_details","project_financials.project_detail_id = project_details.project_detail_id")
             ->join("projects","projects.project_id = project_details.project_id")
             ->where('projects.project_id',$project_id)
             ->where('project_financials.financial_type',$financial_type);
             
            $query = $this->db->get();

            return $query;
  }
  public function getFinancialMecasaTotal($financial_type)
  {

    $query = $this->db->select('SUM(mecasa_financials.financial_nominal) as total')
             ->from('mecasa_financials')
             ->where('mecasa_financials.financial_type',$financial_type);
            $query = $this->db->get();

            return $query;
  }

  public function getDataClassesFilter($limit, $start, $keyword)
  {
    $where = "teachers.teacher_name LIKE '%".$keyword."%' OR classes.class_code LIKE '%".$keyword."%' OR pics.pic_name LIKE '%".$keyword."%' OR classes.class_address LIKE '%".$keyword."%'";

    $this->db->select('classes.*,teachers.*,pics.*')
             ->from('classes')
             ->join("teachers","classes.teacher_id = teachers.teacher_id")
             ->join("pics","classes.pic_id = pics.pic_id");

             if($keyword!='null'){
              $this->db->where($where);
             }

             $this->db->limit($limit, $start);
      $query = $this->db->get();
    return $query;

  }

  public function getDataProgress($limit, $start,$project_detail_id)
  {

    $query = $this->db->select('progress_histories.*,projects.*,project_details.*,users.*')
             ->from('progress_histories')
             ->join("project_details","progress_histories.project_detail_id = project_details.project_detail_id")
             ->join("projects","projects.project_id = project_details.project_id")
             ->join("users","progress_histories.user_id = users.user_id")
             ->where('progress_histories.project_detail_id', $project_detail_id)
             ->limit($limit, $start)
            //  ->group_by('progress_time')
             ->order_by('progress_time','desc')
             ->get();
    return $query;

  }
  public function getDataStudentStudies($class_id, $study_date)
  {

    $query = $this->db->select('studies.*,classes.*,students.*')
             ->from('studies')
             ->join("students","students.student_id = studies.student_id")
             ->join("classes","classes.class_id = students.class_id")
             ->where('classes.class_id', $class_id)
             ->where('studies.study_date', $study_date)
             ->get();
    return $query;

  }
  public function getDetailDataClasses($class_id)
  {

    $query = $this->db->select('classes.*,teachers.*,pics.*')
             ->from('classes')
             ->join("teachers","classes.teacher_id = teachers.teacher_id")
             ->join("pics","classes.pic_id = pics.pic_id")
             ->where('class_id', $class_id)
             ->get()->row();
    return $query;

  }
  public function getClassStudent($class_id)
  {

    $query = $this->db->select('*')
             ->from('students')
             ->where('class_id', $class_id)
             ->get()->result();
    return $query;

  }
  public function getDataProgressOrder()
  {
    $this->db->select('clients.client_name,orders.*');
    $this->db->from('clients');
    $this->db->join('orders', 'clients.client_id = orders.client_id');
    $this->db->where('orders.status', 1);
    // $this->db->order_by('order_id', 'DESC');
    return $this->db->get();
  }



}
