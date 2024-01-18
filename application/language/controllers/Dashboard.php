<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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

	public function index()
	{
		$data['clients'] = $this->db->get("clients")->result();
		$data['meetings'] = $this->custom_model->getAllClientMeeting()->result();
		$data['today_meetings'] = $this->custom_model->getTodayClientMeeting()->result();
		$data['on_progress_project'] = $this->custom_model->getAllOnProgressProject()->result();
		$data['on_progress_project_design'] = $this->custom_model->getTotalOnProgressProject('Design')->row();
		$data['on_progress_project_produksi'] = $this->custom_model->getTotalOnProgressProject('Produksi')->row();
		$data['total_income_design'] = $this->custom_model->getTotalIncome('Design')->result();
		$data['total_income_produksi']  = $this->custom_model->getTotalIncome('Produksi')->result();
		$data['total_income_all']  = $this->custom_model->getTotalIncomeAll()->result();
		$data['project_masuk_design'] = $this->custom_model->getProjectMasuk('Design')->result();
		$data['project_masuk_produksi']  = $this->custom_model->getProjectMasuk('Produksi')->result();
		$data['project_masuk_all']  = $this->custom_model->getProjectMasukAll()->result();
		$data['total_profit_design'] = $this->custom_model->getTotalProfit('Design')->result();
		$data['total_profit_produksi']  = $this->custom_model->getTotalProfit('Produksi')->result();
		$data['total_profit_all']  = $this->custom_model->getTotalProfitAll()->result();

		$this->load->view('template/head.html');
		$this->load->view('dashboard/index.php',$data);
		$this->load->view('template/foot.html');
	}
}
