<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_general extends CI_Controller {

	private $data = [];

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}
		$this->data['username'] = is_username();
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		$this->load->model('inventory_model','inv_model',true);
		$this->load->model('stok/stok_opname_model','so_model',true);
		$this->load->model('stok/stok_general_model','sg_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		
		if (strpos($_SERVER['SERVER_NAME'], 'gracetdj') !== false ) {
			$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1  ORDER BY id asc');
		}else{
			$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1  ORDER BY id desc');
		}


		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 order by warna_jual asc');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

		// $this->output->enable_profiler(TRUE);

	}

	function index(){
		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/inventory/stok_opname_upload',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Upload Stok Opname',
			'common_data'=> $this->data,);

		
		$data['fileHistory'] = $this->common_model->db_select("nd_so_upload_file_history order by created_at desc");
		$this->load->view('admin/template',$data);
	}

//===================================get stok===============================================

function get_qty_stock_by_barang_detail(){
	$toko_id = $this->input->post('toko_id');
	$gudang_id = $this->input->post('gudang_id');
	$barang_id = $this->input->post('barang_id');
	$warna_id = $this->input->post('warna_id');
	$isEceran = $this->input->post('is_eceran');
	$tanggal = is_date_formatter($this->input->post('tanggal'));
	// $get_stok_opname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' ORDER BY tanggal desc LIMIT 1");
	$get_stok_opname = $this->common_model->get_latest_so($tanggal, $barang_id, $warna_id, $gudang_id);
	$tanggal_awal = '2018-01-01';
	$stok_opname_id = 0;
	foreach ($get_stok_opname as $row) {
		$tanggal_awal = $row->tanggal;
		$stok_opname_id = $row->stok_opname_id;
	}

	// echo $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id;
	$get = $this->sg_model->get_qty_stok_by_barang_detail($toko_id, $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id);
	$result[0] = $get->result();

	$res = array(
		'data' => $tanggal, $barang_id, $warna_id, $gudang_id,
		'tanggal_awal' => $tanggal_awal,
		'stok_opname_id' => $stok_opname_id
	);

	$tanggal_awal = '2018-01-01';
	$detail_id = $this->input->post('penjualan_list_detail_id');
	$detail_id = ($detail_id=='' ? 0 : $detail_id);
	$get_stok_opname = $this->common_model->get_latest_so_eceran($tanggal, $barang_id, $warna_id, $gudang_id);

	foreach ($get_stok_opname as $row) {
		$tanggal_awal = $row->tanggal;
		$stok_opname_id = $row->stok_opname_id;
	}
	
	if($isEceran){
		$result[1] = $this->sg_model->get_qty_stok_by_barang_detail_eceran($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $detail_id);
		$result[1] = $result[1]->result();
	}

	$result[2] = array(
		'toko' => $toko_id,
		'gudang_id' => $gudang_id,
		'barang' => $barang_id,
		'warna' => $warna_id,
		'tanggal_awal' => $tanggal_awal,
		'stok_opname_id' => $stok_opname_id
	);

	$dt = $this->sg_model->get_barang_header($barang_id);

	$result[3] = array(
		'res'=>$res,
		'var' => $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $detail_id, $tanggal,
		'barang_head' => $dt
	);
	
	// echo $tanggal_awal;

	// echo $stok_opname_id;
	echo json_encode($result);
	// echo $tanggal.'/'.$barang_id.'/'.$warna_id.'/'.$gudang_id;
	
	// print_r($result);
}



}