<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

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
		$this->load->model('crud_model');
		$this->load->model('custom_model');
		}

	public function keuangan()
	{
		$data['mecasa_financials'] = $this->custom_model->getFinancialMecasa()->result();
		$data['total_income'] = $this->custom_model->getTotalIncomeWhole()->row();
		$data['total_profit'] = $this->custom_model->getTotalProfitWhole()->row();
		$data['total_uang_masuk'] = $this->custom_model->getTotalUangMasuk()->row();
		$data['total_uang_keluar'] = $this->custom_model->getTotalUangKeluar()->row();
		$data['mecasa_keluar_total'] = $this->custom_model->getFinancialMecasaTotal('Uang Keluar')->row();
		$data['mecasa_masuk_total'] = $this->custom_model->getFinancialMecasaTotal('Uang Masuk')->row();
		$data['total_income_design'] = $this->custom_model->getTotalIncome('Design')->result();
		$data['total_income_produksi']  = $this->custom_model->getTotalIncome('Produksi')->result();
		$data['total_income_all']  = $this->custom_model->getTotalIncomeAll()->result();
		$data['total_profit_design'] = $this->custom_model->getTotalProfit('Design')->result();
		$data['total_profit_produksi']  = $this->custom_model->getTotalProfit('Produksi')->result();
		$data['total_profit_all']  = $this->custom_model->getTotalProfitAll()->result();

		$this->load->view('template/head.html');
		$this->load->view('laporan/keuangan.php',$data);
		$this->load->view('template/foot.html');
	}
	public function project()
	{
		$this->load->view('template/head.html');
		$this->load->view('laporan/project.php');
		$this->load->view('template/foot.html');
	}
	public function addHistoryAction()
  {
    $financial_note = $this->input->post('financial_note');
    $financial_date = $this->input->post('financial_date');
    $financial_type = $this->input->post('financial_type');
    $financial_nominal =  str_replace( ',', '', $this->input->post('financial_nominal'));
    $financial_pic = $this->input->post('financial_pic');

      
	$financial_mecasa_row = $this->custom_model->getSaldoFinancialMecasa()->row();

	if($financial_mecasa_row == null || $financial_mecasa_row == ''){
	$financial_saldo = $financial_nominal;
	}else{
		if($financial_type == 'Uang Masuk'){
			$financial_saldo = $financial_mecasa_row->financial_saldo - $financial_nominal ;
		}else{
			$financial_saldo = $financial_mecasa_row->financial_saldo + $financial_nominal ;
		}
	}


	$data_financial_mecasa = array(
	'financial_note' => $financial_note,
	'financial_date' => $financial_date,
	'financial_type' => $financial_type,
	'financial_nominal' => $financial_nominal,
	'financial_pic' => $financial_pic,
	'financial_saldo' => $financial_saldo,
	);
	$add_financial_mecasa = $this->crud_model->createData('mecasa_financials',$data_financial_mecasa);
	if($add_financial_mecasa){
		$this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Laporan/keuangan/');
	}
    

  }
	public function updateHistoryAction()
  {
    $mecasa_financial_id = $this->input->post('mecasa_financial_id');
    $financial_note = $this->input->post('financial_note');
    $financial_date = $this->input->post('financial_date');
    $financial_type = $this->input->post('financial_type');
    $financial_nominal =  str_replace( ',', '', $this->input->post('financial_nominal'));
    $financial_pic = $this->input->post('financial_pic');

      
	$financial_mecasa_row = $this->custom_model->getSaldoFinancialMecasa()->row();

	if($financial_mecasa_row == null || $financial_mecasa_row == ''){
	$financial_saldo = $financial_nominal;
	}else{
		if($financial_type == 'Uang Masuk'){
			$financial_saldo = $financial_mecasa_row->financial_saldo - $financial_nominal ;
		}else{
			$financial_saldo = $financial_mecasa_row->financial_saldo + $financial_nominal ;
		}
	}


	$data_financial_mecasa = array(
	'financial_note' => $financial_note,
	'financial_date' => $financial_date,
	'financial_type' => $financial_type,
	'financial_nominal' => $financial_nominal,
	'financial_pic' => $financial_pic,
	'financial_saldo' => $financial_saldo,
	);
	$update_financial_mecasa = $this->crud_model->updateData('mecasa_financials',$data_financial_mecasa,'mecasa_financial_id='.$mecasa_financial_id);
	if($update_financial_mecasa){
		$this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('Laporan/keuangan/');
	}
    

  }

  public function fetchHistoryFinancial()
  {
    $output = '';

    
 
    // if($keyword != 'null'){
      $data = $this->custom_model->getDataMecasaHistoryFinancial($this->input->post('limit'), $this->input->post('start'));
    // }
    // else if($keyword == 'null'){
    //   $data = $this->custom_model->getDataProjects($this->input->post('limit'), $this->input->post('start'),'null');

    // }

    if($data->num_rows() > 0)
    {
     foreach($data->result() as $financial)
     {
		if($financial->financial_type == 'Uang Keluar'){
			$btn_color = 'secondary';
		}
		else{
			$btn_color = 'success';
		}

		$output .= '
			<div class="activity-dot-'.$btn_color.'" style="margin-top:18px;"></div>
			<li class="list-group-item" style="margin-top:-31px; margin-left:10px;">
				<a  data-toggle="modal" data-target="#editHistoryModal" style="cursor: pointer;" onclick="updateHistoryFinancial('.$financial->mecasa_financial_id.')"><p style="font-weight: bold; margin-bottom:0px;"> Rp. '.number_format($financial->financial_nominal).' - <span style="font-weight: 300;">'.$financial->financial_type.'</span>  </p></a>
				<p style="margin-top:0px;">'.date_format(date_create($financial->financial_date),"d M Y") .' | '.$financial->financial_note.'  | '.$financial->financial_pic.'</p>
			</li>
		';
     }
    }
    echo $output;
  }
  public function getHistoryFinancialById()
  {
    $mecasa_financial_id = $this->input->post('mecasa_financial_id');
    $where = "mecasa_financial_id=".$mecasa_financial_id;
    $financial_history = $this->custom_model->getHistoryMecasaFinancialById($mecasa_financial_id)->row();
    echo json_encode($financial_history);

  }
  public function deleteHistoryFinancialAction($mecasa_financial_id)
  {
    $where="mecasa_financial_id=".$mecasa_financial_id;
    $delete = $this->crud_model->deleteData('mecasa_financials',$where);
    if($delete){
      $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
      redirect('Laporan/keuangan/');

    }
  }
}
