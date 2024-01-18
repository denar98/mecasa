<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

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
	 * @see https://codeigniter.com/quotation_guide/general/urls.html
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
    $data['quotations'] = $this->db->get("quotations")->result();

		$this->load->view('template/head.html');
		$this->load->view('quotation/index.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllQuotation()
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
            0=>'quotation_name',
            1=>'quotation_phone',
            2=>'quotation_address',
            3=>'quotation_interest',
            4=>'quotation_class',
            5=>'quotation_status',
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
        $quotations = $this->db->get('quotations');
        $data = array();
        foreach($quotations->result() as $rows)
        {

            $data[]= array(
                $rows->quotation_name,
                $rows->quotation_phone,
                $rows->quotation_address,
                $rows->quotation_interest,
                $rows->quotation_class,
                $rows->quotation_status,
                '<a href="#" class="btn btn-warning mr-1" onclick="getQuotation('.$rows->quotation_id.')" data-toggle="modal" data-target="#updateModal" title="Edit"><i class="fa fa-pencil"></i></a>
                 <a href="'.base_url().'Quotation/deleteAction/'.$rows->quotation_id.'" class="btn btn-danger mr-1" title="Hapus"><i class="fa fa-trash"></i></a>'
            );
        }
        $total_quotations = $this->totalQuotations();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_quotations,
            "recordsFiltered" => $total_quotations,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalQuotations()
    {
        $query = $this->db->select('COUNT(*) as num')
                 ->from('quotations')
                 ->get();
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function addAction()
    {
      $quotation_name = $this->input->post('quotation_name');
      $quotation_phone = $this->input->post('quotation_phone');
      $quotation_address = $this->input->post('quotation_address');
      $quotation_interest = $this->input->post('quotation_interest');
      $quotation_class = $this->input->post('quotation_class');
      // $quotation_status = $this->input->post('quotation_status');

      $data = array(
        'quotation_name' => $quotation_name,
        'quotation_phone' => $quotation_phone,
        'quotation_address' => $quotation_address,
        'quotation_interest' => $quotation_interest,
        'quotation_class' => $quotation_class,
        // 'quotation_status' => $quotation_status,
      );
      $add = $this->crud_model->createData('quotations',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Quotation');
      }

    }

    public function updateAction()
    {
      $quotation_id = $this->input->post('quotation_id');
      $quotation_name = $this->input->post('quotation_name');
      $quotation_phone = $this->input->post('quotation_phone');
      $quotation_address = $this->input->post('quotation_address');
      $quotation_interest = $this->input->post('quotation_interest');
      $quotation_class = $this->input->post('quotation_class');
      // $quotation_status = $this->input->post('quotation_status');

      $data = array(
        'quotation_name' => $quotation_name,
        'quotation_phone' => $quotation_phone,
        'quotation_address' => $quotation_address,
        'quotation_interest' => $quotation_interest,
        'quotation_class' => $quotation_class,
        // 'quotation_status' => $quotation_status,
      );
      $where="quotation_id=".$quotation_id;
      $update = $this->crud_model->updateData('quotations',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('Quotation');
      }
    }

    public function deleteAction($quotation_id)
    {
      $where="quotation_id=".$quotation_id;
      $delete = $this->crud_model->deleteData('quotations',$where);
      if($delete){
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('Quotation');

      }
    }
    public function updateStatusAction()
    {
      $quotation_id = $this->input->post('quotation_id');
      $quotation_status = $this->input->post('quotation_status');

      $data = array(
        'quotation_status' => $quotation_status,
      );
      $where="quotation_id=".$quotation_id;
      $update = $this->crud_model->updateData('quotations',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data status Has Been Updated !");
        redirect('Quotation');
      }
    }

    public function getQuotation()
    {
      $quotation_id = $this->input->post('quotation_id');
      $where = "quotation_id=".$quotation_id;
      $quotation = $this->crud_model->readData('*','quotations',$where)->row();
      echo json_encode($quotation);

    }
}
