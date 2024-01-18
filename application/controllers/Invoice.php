<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

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
	 * @see https://codeigniter.com/invoice_guide/general/urls.html
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
    $data['invoices'] = $this->db->get("invoices")->result();

		$this->load->view('template/head.html');
		$this->load->view('invoice/index.html',$data);
		$this->load->view('template/foot.html');
	}

  public function getAllInvoice()
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
            0=>'invoice_name',
            1=>'invoice_phone',
            2=>'invoice_address',
            3=>'invoice_interest',
            4=>'invoice_class',
            5=>'invoice_status',
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
        $invoices = $this->db->get('invoices');
        $data = array();
        foreach($invoices->result() as $rows)
        {

            $data[]= array(
                $rows->invoice_name,
                $rows->invoice_phone,
                $rows->invoice_address,
                $rows->invoice_interest,
                $rows->invoice_class,
                $rows->invoice_status,
                '<a href="#" class="btn btn-warning mr-1" onclick="getInvoice('.$rows->invoice_id.')" data-toggle="modal" data-target="#updateModal" title="Edit"><i class="fa fa-pencil"></i></a>
                 <a href="'.base_url().'Invoice/deleteAction/'.$rows->invoice_id.'" class="btn btn-danger mr-1" title="Hapus"><i class="fa fa-trash"></i></a>'
            );
        }
        $total_invoices = $this->totalInvoices();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_invoices,
            "recordsFiltered" => $total_invoices,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function totalInvoices()
    {
        $query = $this->db->select('COUNT(*) as num')
                 ->from('invoices')
                 ->get();
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function addAction()
    {
      $invoice_name = $this->input->post('invoice_name');
      $invoice_phone = $this->input->post('invoice_phone');
      $invoice_address = $this->input->post('invoice_address');
      $invoice_interest = $this->input->post('invoice_interest');
      $invoice_class = $this->input->post('invoice_class');
      // $invoice_status = $this->input->post('invoice_status');

      $data = array(
        'invoice_name' => $invoice_name,
        'invoice_phone' => $invoice_phone,
        'invoice_address' => $invoice_address,
        'invoice_interest' => $invoice_interest,
        'invoice_class' => $invoice_class,
        // 'invoice_status' => $invoice_status,
      );
      $add = $this->crud_model->createData('invoices',$data);
      if($add){
        $this->session->set_flashdata("success", "Your Data Has Been Added !");
        redirect('Invoice');
      }

    }

    public function updateAction()
    {
      $invoice_id = $this->input->post('invoice_id');
      $invoice_name = $this->input->post('invoice_name');
      $invoice_phone = $this->input->post('invoice_phone');
      $invoice_address = $this->input->post('invoice_address');
      $invoice_interest = $this->input->post('invoice_interest');
      $invoice_class = $this->input->post('invoice_class');
      // $invoice_status = $this->input->post('invoice_status');

      $data = array(
        'invoice_name' => $invoice_name,
        'invoice_phone' => $invoice_phone,
        'invoice_address' => $invoice_address,
        'invoice_interest' => $invoice_interest,
        'invoice_class' => $invoice_class,
        // 'invoice_status' => $invoice_status,
      );
      $where="invoice_id=".$invoice_id;
      $update = $this->crud_model->updateData('invoices',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data Has Been Updated !");
        redirect('Invoice');
      }
    }

    public function deleteAction($invoice_id)
    {
      $where="invoice_id=".$invoice_id;
      $delete = $this->crud_model->deleteData('invoices',$where);
      if($delete){
        $this->session->set_flashdata("success", "Your Data Has Been Deleted !");
        redirect('Invoice');

      }
    }
    public function updateStatusAction()
    {
      $invoice_id = $this->input->post('invoice_id');
      $invoice_status = $this->input->post('invoice_status');

      $data = array(
        'invoice_status' => $invoice_status,
      );
      $where="invoice_id=".$invoice_id;
      $update = $this->crud_model->updateData('invoices',$data,$where);
      if($update){
        $this->session->set_flashdata("success", "Your Data status Has Been Updated !");
        redirect('Invoice');
      }
    }

    public function getInvoice()
    {
      $invoice_id = $this->input->post('invoice_id');
      $where = "invoice_id=".$invoice_id;
      $invoice = $this->crud_model->readData('*','invoices',$where)->row();
      echo json_encode($invoice);

    }
}
