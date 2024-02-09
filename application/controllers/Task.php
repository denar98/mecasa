<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

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
		error_reporting(0);

		$this->load->model('datatable_model');
		$this->load->model('crud_model');
		$this->load->model('task_model');
		$this->load->model('custom_model');
	}

	public function index()
	{
    $data['page'] = 'task';
		$data['module'] = 'order';
		// $data['employees'] = $this->db->get("employees")->result();
		// $data['employees'] = $this->task_model->getEmployeeDesigner()->result();
		// $data['projects'] = $this->custom_model->getDataProjectsAll()->result();
		$data['tags'] = $this->db->get("tags")->result();

		$this->load->view('template/head.html',$data);
		$this->load->view('task/index.html',$data);
		$this->load->view('template/foot.html');
	}
	public function history()
	{
    $data['page'] = 'history-task';
		$data['module'] = 'order';
		// $data['employees'] = $this->db->get("employees")->result();
		$data['employees'] = $this->task_model->getEmployeeDesigner()->result();

		$this->load->view('template/head.html',$data);
		$this->load->view('task/index.html',$data);
		$this->load->view('template/foot.html');
	}
	public function fetchHistoryTask()
	{

		$output = '';
		$employee_id = $this->input->post('employee_id');
	
		$data = $this->task_model->getDataHistoryTask($this->input->post('limit'), $this->input->post('start'),$employee_id);

		if($data->num_rows() > 0)
		{
		$i = 0;
		 foreach($data->result() as $row)
		 {
	
			$i++;
			if($row->tag_name == 'Urgent'){
			  $tag_color = "danger";
			}
			else{
			  $tag_color = "normal";
			}
			if($row->service_package_name== 'Basic'){
			  $service_package_color = "primary";
			}
			else if($row->service_package_name== 'Standard'){
			  $service_package_color = "warning";
			}
			else{
			  $service_package_color = "info";
			}
		  $output .= '
		  <tr> 
			<td style="width:5%; min-width:50px;">'.$i.'</td>
			<td id="task-row">
				<h7 class="task_title_0">'.$row->task_date.'</h7>
			</td>
			<td id="task-row">
				<h7 class="task_title_0">'.$row->client_name.'</h7>
			</td>
			<td id="task-row">
				<h7 class="task_title_0">'.$row->service_name.'</h7>
			</td>
			<td>
				<h7 class="task_title_0">'.$row->order_number.'</h7>
			</td>
			<td class="text-center">
				<h7 class="task_title_0">'.$row->task_type.'</h7>
			</td>
			<td class="text-center">
				<h7 class="task_title_0">'.$row->task_estimation_hour.'h, '.$row->task_estimation_minute.'m </h7>
			</td>
			
			</tr>';
			
		  
		 }
		}
		echo $output;	
	}

	public function deliveryAction()
    {
      $task_id = $this->input->post('task_id');
      $date = Date('Y-m-d H:i:s');
  	  
	  $detail_task = $this->task_model->getDetailTask($task_id)->row();
      $order_id = $detail_task->order_id;
	  $task_delivery = $this->db->limit(1)->order_by('task_delivery_id','desc')->get('task_deliveries')->row();
	  $task_delivery_last_count = $this->db->where('order_id',$order_id)->limit(1)->order_by('task_delivery_count','desc')->get('task_deliveries')->row();
	  $task_delivery_id =  $this->uuid->v4();
	  if($detail_task->task_type == 'Revision'){
		$task_delivery_final_count = $task_delivery_last_count->task_delivery_count + 1;
		$data_task_delivery = array(
			'task_delivery_id'=>$task_delivery_id,
			'task_id'=>$task_id,
			'order_id'=>$order_id,
			'task_delivery_date' => Date('Y-m-d H:i:s'),
			'task_delivery_type' => $detail_task->task_type,
			'task_delivery_count' => $task_delivery_final_count
		  );
	   }
	   else{
		$data_task_delivery = array(
			'task_delivery_id'=>$task_delivery_id,
			'task_id'=>$task_id,
			'order_id'=>$order_id,
			'task_delivery_date' => Date('Y-m-d H:i:s'),
			'task_delivery_type' => $detail_task->task_type,
		  );

	   }

      $data_task = array(
        'task_finish' => Date('Y-m-d H:i:s'),
        'task_status' => 'Checking',
      );
      $data_order = array(
        'order_status' => 'Done',
      );
	  $where_task = "task_id='".$task_id."'";
	  $where_order = "order_id='".$detail_task->order_id."'";

      $preview_files_attachments = [];
      $preview_files_count = count($_FILES['preview_files']['name']);
	  
      for($i=0;$i<$preview_files_count;$i++){
    
        if(!empty($_FILES['preview_files']['name'][$i])){
    

          // $date = str_replace( ':', '', $date);
          if (!is_dir('assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/preview')) {
              mkdir('./assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/preview', 0777, TRUE);
          }
		  $path = './assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/preview';
          $_FILES['file']['name'] = $_FILES['preview_files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['preview_files']['type'][$i];
          $_FILES['file']['file_ext'] = $_FILES['preview_files']['file_ext'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['preview_files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['preview_files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['preview_files']['size'][$i];
		  $_FILES['file']['image_width'] = $_FILES['preview_files']['image_width'][$i];
		  $_FILES['file']['image_height'] = $_FILES['preview_files']['image_height'][$i];

          $config['upload_path'] = $path; 
          $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd|zip|rar';
		  $config['overwrite'] = FALSE;
          $config['file_name'] = $_FILES['preview_files']['name'][$i];
          
          $this->upload->initialize($config);
  
          if($this->upload->do_upload('file')){
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];
   
            $preview_files_attachments['totalFiles'][] = $filename;
			$task_delivery_attachments_id  =  $this->uuid->v4();

            $preview_files_attachments_data = array(
              'task_delivery_attachments_id ' => $task_delivery_attachments_id ,
              'task_id' => $task_id,
              'task_delivery_id' => $task_delivery_id,
              'task_delivery_attachment_type' => 'Preview',
              'task_delivery_attachment_name' => $filename,
              'task_delivery_attachment_ext' => $uploadData['file_ext'],
              'task_delivery_attachment_width' => $uploadData['image_width'],
              'task_delivery_attachment_height' => $uploadData['image_height'],
            );
            $add_preview_files_attachments = $this->crud_model->createData('task_delivery_attachments',$preview_files_attachments_data);
        
          }
        }
  
      }

      $source_files_attachments = [];
      $source_files_count = count($_FILES['source_files']['name']);
	  
      for($i=0;$i<$source_files_count;$i++){
    
        if(!empty($_FILES['source_files']['name'][$i])){
    

          // $date = str_replace( ':', '', $date);
          if (!is_dir('assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/source')) {
			mkdir('./assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/source', 0777, TRUE);
  		  }
		  $path = './assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/source';
		  $_FILES['file']['name'] = $_FILES['source_files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['source_files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['source_files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['source_files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['source_files']['size'][$i];
  
          $config['upload_path'] = $path; 
          $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd|zip|rar';
		  $config['overwrite'] = FALSE;
          $config['file_name'] = $_FILES['source_files']['name'][$i];
          
          $this->upload->initialize($config);
  
          if($this->upload->do_upload('file')){
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];
   
            $source_files_attachments['totalFiles'][] = $filename;
			$task_delivery_attachments_id  =  $this->uuid->v4();

            $source_files_attachments_data = array(
  			  'task_delivery_attachments_id ' => $task_delivery_attachments_id ,
			  'task_id' => $task_id,
              'task_delivery_id' => $task_delivery_id,
              'task_delivery_attachment_type' => 'Source',
              'task_delivery_attachment_name' => $filename,
			  'task_delivery_attachment_ext' => $uploadData['file_ext'],
              'task_delivery_attachment_width' => $uploadData['image_width'],
              'task_delivery_attachment_height' => $uploadData['image_height'],

            );
            $add_source_files_attachments = $this->crud_model->createData('task_delivery_attachments',$source_files_attachments_data);
        
          }
        }
  
      }

      $proven_files_attachments = [];
      $proven_files_count = count($_FILES['proven_files']['name']);
	  
      for($i=0;$i<$proven_files_count;$i++){
    
        if(!empty($_FILES['proven_files']['name'][$i])){
    

          // $date = str_replace( ':', '', $date);
          if (!is_dir('assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/proven')) {
			mkdir('./assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/proven', 0777, TRUE);
  		  }
		  $path = './assets/attachments/'.$detail_task->service_name.'/'.$detail_task->client_name.'/'.$detail_task->order_number.'/proven';
		  $_FILES['file']['name'] = $_FILES['proven_files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['proven_files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['proven_files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['proven_files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['proven_files']['size'][$i];
  
          $config['upload_path'] = $path; 
          $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd|zip|rar';
		  $config['overwrite'] = FALSE;
          $config['file_name'] = $_FILES['proven_files']['name'][$i];
          
          $this->upload->initialize($config);
  
          if($this->upload->do_upload('file')){
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];
   
            $proven_files_attachments['totalFiles'][] = $filename;
			$task_delivery_attachments_id  =  $this->uuid->v4();

            $proven_files_attachments_data = array(
  			  'task_delivery_attachments_id ' => $task_delivery_attachments_id ,
              'task_id' => $task_id,
              'task_delivery_id' => $task_delivery_id,
              'task_delivery_attachment_type' => 'Proven',
              'task_delivery_attachment_name' => $filename,
			  'task_delivery_attachment_ext' => $uploadData['file_ext'],
              'task_delivery_attachment_width' => $uploadData['image_width'],
              'task_delivery_attachment_height' => $uploadData['image_height'],

            );
            $add_source_files_attachments = $this->crud_model->createData('task_delivery_attachments',$proven_files_attachments_data);
        
          }
        }
  
      }

	  
      $add_task_delivery = $this->crud_model->createData('task_deliveries',$data_task_delivery);
	  $update_task = $this->crud_model->updateData('tasks',$data_task,$where_task);
	  $update_order = $this->crud_model->updateData('orders',$data_order,$where_order);
	  $this->session->set_flashdata("success", "Your Task Has Been Delivered !");
	  redirect('Task/');	
	
	
	  


    }

	public function assignNewTaskAction()
    {
      
      $project_id = $this->input->post('project_id');
      $task_type = $this->input->post('task_type');
      $project_row = $this->db->where('project_id',$project_id)->get('projects')->row();
      $assign_type = $this->input->post('assign_type');
      $task_id = $this->uuid->v4();
      $user_id = $this->input->post('user_id');
      $task_date = Date('Y-m-d');
      $task_estimation_hour = $this->input->post('task_estimation_hour');
      $task_estimation_minute = $this->input->post('task_estimation_minute');
      $task_start = $this->input->post('task_start');
      $task_brief = $this->input->post('brief');
      $tag_id = $this->input->post('tag_id');

      $task_row = $this->db->limit(1)->order_by('task_id','desc')->get('tasks')->row();
      $client_exist_row = $this->db->where('client_id',$project_row->client_id)->get('clients')->row();

      $user_row = $this->db->where('user_id',$user_id)->get('users')->row();
      $task_revision_count_row = $this->db->where('project_id',$project_id)->order_by('task_revision_count','desc')->get('tasks')->row();
      $task_new_count_row = $this->db->where('project_id',$project_id)->order_by('task_new_count','desc')->get('tasks')->row();

      if($task_revision_count_row){
        $task_revision_count = $task_revision_count_row->task_revision_count + 1;
      }
      else{
        $task_revision_count = 1;
      }

      if($task_new_count_row ){
        $task_new_count = $task_new_count_row->task_new_count + 1;
      }
      else{
        $task_new_count = 1;
      }


      if($task_type == 'New'){
        $task_revision_count = "";
        $project_detail_status = "On Progress";
      }
      else{
        $task_new_count = "";
        $project_detail_status = "In Revision";
      }

      $data = array(
        'task_id' => $task_id,
        'project_id' => $project_id,
        'task_type' => $task_type,
        'user_id' => $user_id,
        'task_date' => $task_date,
        'task_estimation_hour' => $task_estimation_hour,
        'task_estimation_minute' => $task_estimation_minute,
        'task_start' => $task_start,
        'task_brief' => $task_brief,
        'tag_id' => $tag_id,
        'task_status' => 'Open',
        'task_new_count' => $task_new_count,
        'task_revision_count' => $task_revision_count,
      );

      $data_project = array(
        // 'assign_to' => $employee_row->employee_name,
        'project_detail_status' => $project_detail_status
      );

      $attachments = [];
   
      $count = count($_FILES['files']['name']);
    
      for($i=0;$i<$count;$i++){
    
        if(!empty($_FILES['files']['name'][$i])){
    
 			if (!is_dir('assets/requirement/'.$client_exist_row->client_name.'/attachments')) {
				mkdir('assets/requirement/'.$client_exist_row->client_name.'/attachments', 0777, TRUE);
			}
          $path = './assets/requirement/'.$client_exist_row->client_name.'/attachments';
          $_FILES['file']['name'] = $_FILES['files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['files']['size'][$i];
          $_FILES['file']['file_ext'] = $_FILES['files']['file_ext'][$i];

          $config['upload_path'] = $path; 
          $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|doc|xlsx|ai|psd|zip|rar';
          $config['overwrite'] = FALSE;
          $config['file_name'] = $_FILES['files']['name'][$i];
          
          $this->upload->initialize($config);
  
          if($this->upload->do_upload('file')){
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];

            $attachments['totalFiles'][] = $filename;
            $task_attachment_id =  $this->uuid->v4();

            $data_attachments = array(
              'task_attachment_id' => $task_attachment_id,
              'task_id' => $task_id,
              'task_attachment_name' => $filename,
              'task_attachment_ext' =>$uploadData['file_ext'],
              'task_attachment_width' => $uploadData['image_width'],
              'task_attachment_height' => $uploadData['image_height'],
            );
            $add_attachments = $this->crud_model->createData('task_attachments',$data_attachments);
        
          }
        }
      }

      $task_todays = $this->task_model->getTaskByUser($user_id)->result();
      $estimate_hour = 0;
      $estimate_minute = 0;
      foreach ($task_todays as $task_today) {
        $estimate_hour += $task_today->task_estimation_hour;
        $estimate_minute += $task_today->task_estimation_minute;
      } 

      $current_estimate = ($estimate_hour*60) + $estimate_minute;
      $new_estimate = ($task_estimation_hour*60) + $task_estimation_minute;
      $total_estimate = $new_estimate + $current_estimate;
      $overtime_time = ($new_estimate + $current_estimate) - 420;
      if(Date('D') =='Sun'){
        $overtime_type = 'Weekend';
      }else{
        $overtime_type = 'Weekday';
      }

      if($total_estimate > 420){
        $overtime_id =  $this->uuid->v4();

        $data_overtime = array(
          'overtime_id' => $overtime_id,
          'user_id' => $user_id,
          'date' => Date('Y-m-d'),
          'overtime_type' => $overtime_type,
          'overtime_time' => $overtime_time,
        );
        $overtimes = $this->db->where("user_id = '".$user_id."' and date = '".Date('Y-m-d')."'")->get('overtimes');
     
        if($overtimes->num_rows()>0){
            $where_overtime = "user_id='".$user_id."' AND date='".Date('Y-m-d')."'";
          $this->crud_model->updateData('overtimes',$data_overtime,$where_overtime);          
        }else{
          $this->crud_model->createData('overtimes',$data_overtime);
        }
      }
      $add = $this->crud_model->createData('tasks',$data);
    

    
        $where = "project_id='".$project_id."'";
        $update = $this->crud_model->updateData('project_details',$data_project,$where);
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        if($this->input->post('source_assign') == "fromDetailPage"){
          redirect('Project/detail/'.$project_id);
        }else if($this->input->post('source_assign') == "fromTaskPage"){
          redirect('Task');
        }else{
          redirect('Project/');
        }
      

    }

}
