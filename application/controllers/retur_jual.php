<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retur_Jual extends CI_Controller {

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

    //     $this->load->database('mysqli');

		$this->load->model('retur_model','rtr_model',true);
		$this->load->model('finance_model','fi_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 ORDER BY nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 ORDER BY warna_jual asc');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

		$this->mysqli_conn = $this->db->conn_id;
	}

	function index(){
		redirect('admin');
	}

	function setting_link($string){
		return rtrim(base64_encode($string),'=');
	}

	function cek_pin(){
		$pin = $this->input->post('pin');
		$baris = $this->common_model->db_select_num_rows("nd_user where posisi_id < 3 and status_aktif = 1 and PIN is not null AND PIN ='".$pin."' limit 1");
		if ($baris == 1) {
			echo 'OK';
		}else{
			print_r($baris);
		}
	}

//===================================Retur=============================================

	function retur_jual_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/retur_jual/retur_jual_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Retur Jual',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['retur_list'] = $this->rtr_model->get_retur_list(); 
		
		$today = date('Y-m-d');
		$threema = strtotime("-6 months", strtotime($today));
		$max_tanggal = date("Y-m-d",$threema);

		// $data['penjualan_list'] = $this->rtr_model->get_penjualan_list($max_tanggal);
		$data['penjualan_list'] = array();
		$data['max_tanggal'] = $max_tanggal;
		$this->load->view('admin/template',$data);
	}

	function get_penjualan_list(){
		
		$today = date('Y-m-d');
		$threema = strtotime("-11 months", strtotime($today));
		$max_tanggal = date("Y-m-d",$threema);

		$result[0] = $max_tanggal;
		$result[1] = $this->rtr_model->get_penjualan_list($max_tanggal);
		echo json_encode($result);

		// echo json_encode($this->rtr_model->get_penjualan_list_for_retur($max_tanggal));
	}

	function get_penjualan_list_retur(){
		
		$today = date('Y-m-d');
		$threema = strtotime("-11 months", strtotime($today));
		$max_tanggal = date("Y-m-d",$threema);

		// $result[0] = $max_tanggal;
		// $result[1] = $this->rtr_model->get_penjualan_list($max_tanggal);
		// echo json_encode($result);

		echo json_encode($this->rtr_model->get_penjualan_list_for_retur($max_tanggal));
	}

	function penjualan_list_retur(){
		$id = $this->input->post('penjualan_id');
		$data_jual = $this->common_model->db_select('nd_penjualan where id='.$id);

		$tanggal = is_date_formatter($this->input->post('tanggal'));
		// $tahun = date('Y', strtotime($tanggal));
		// $no_faktur = 1;
		// $data_get = $this->common_model->db_select("nd_retur_jual where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
		// foreach ($data_get as $row) {
		// 	$no_faktur = $row->no_faktur + 1;
		// }

		foreach ($data_jual as $row) {
			$data = array(
				'retur_type_id' => $row->penjualan_type_id,
				'penjualan_id' => $id,
				'no_faktur_jual' => $row->no_faktur_lengkap,
				'tanggal' => date('Y-m-d'),
				'no_faktur' => null,
				'customer_id' => $row->customer_id,
				'nama_keterangan' => $row->nama_keterangan,
				'user_id' => is_user_id(),
				);

		}
		$result_id = $this->common_model->db_insert('nd_retur_jual', $data);

		redirect($this->setting_link('retur_jual/retur_jual_detail').'/?id='.$result_id);

	}

	function retur_jual_list_insert(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tahun = date('Y', strtotime($tanggal));
		$no_faktur = 1;
		$data_get = $this->common_model->db_select("nd_retur_jual where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
		foreach ($data_get as $row) {
			$no_faktur = $row->no_faktur + 1;
		}

		$data = array(
			'retur_type_id' => $this->input->post('retur_type_id') ,
			'tanggal' => $tanggal,
			'no_faktur' => $no_faktur,
			'customer_id' => $this->input->post('customer_id') ,
			'nama_keterangan' => $this->input->post('nama_keterangan') ,
			'user_id' => is_user_id(),
			);

		// print_r($data);

		// $result_id = $this->common_model->db_insert('nd_retur_jual',$data);
		redirect($this->setting_link('retur_jual/retur_jual_detail').'/?id='.$result_id);

	}

	function retur_jual_list_update(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$id = $this->input->post('id');
		// $customer_id = $this->input->post('customer_id');
		// $retur_type_id = $this->input->post('retur_type_id');
		// $customer_id = ($retur_type_id == 2 ? null : $customer_id);
		// $nama_keterangan = ($retur_type_id == 2 ? $this->input->post('nama_keterangan') : null);
		$data = array(
			'tanggal' => $tanggal,
			'user_id' => is_user_id(),
			);

		// print_r($data);

		$this->common_model->db_update('nd_retur_jual',$data,'id',$id);
		redirect($this->setting_link('retur_jual/retur_jual_detail').'/?id='.$id);

	}

	function retur_jual_request_open(){
		// print_r($this->input->post());
		$retur_id = $this->input->post('retur_jual_id');
		$data = array(
			'status' => 1 );
		$this->common_model->db_update('nd_retur_jual',$data,'id',$retur_id);
		redirect(is_setting_link('retur_jual/retur_jual_detail').'?id='.$retur_id);
	}

	function retur_jual_print(){

		$retur_jual_id = $this->input->get('retur_jual_id');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_retur'] = $this->rtr_model->get_retur_data($retur_jual_id);
		$data['data_retur_detail'] = $this->rtr_model->get_retur_jual_detail($retur_jual_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/retur_jual/retur_jual_print',$data);
		
	}

	function retur_jual_list_close()
	{
		$id = $this->input->get('id');

		
		$get = $this->common_model->db_select("nd_retur_jual where id=".$id);
		foreach ($get as $row) {
			$no_faktur = $row->no_faktur;
			$tanggal = $row->tanggal;
		}

		$tahun = date('Y', strtotime($tanggal));

		if ($no_faktur == '') {
			$data_get = $this->common_model->db_select("nd_retur_jual where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
			foreach ($data_get as $row) {
				$no_faktur = $row->no_faktur + 1;
			}
            if ($no_faktur == '') {
                $no_faktur = 1;
            }
			// concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0'))
			$no_faktur_lengkap = $tahun.'/CVSUN/RETURJUAL/'.str_pad($no_faktur,4,"0",STR_PAD_LEFT);

			// echo $id;
			$data = array(
				'closed_by' => is_user_id() ,
				'closed_date' => date('Y-m-d H:i:s'),
				'no_faktur' => $no_faktur,
				'no_faktur_lengkap' => $no_faktur_lengkap,
				'status' => 0 );
			// print_r($data);
		}else{
			$data = array(
				'closed_by' => is_user_id() ,
				'closed_date' => date('Y-m-d H:i:s'),
				'status' => 0  );
		}

		$this->common_model->db_update('nd_retur_jual',$data,'id',$id);
		redirect(is_setting_link('retur_jual/retur_jual_detail').'/?id='.$id);
	}

	function retur_jual_list_batal(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$data = array(
			'status_aktif' => $status
		);

		$this->common_model->db_update("nd_retur_jual",$data,'id', $id);
		echo json_encode(1);
		
	}

//============================================detail=======================================================


	function retur_jual_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');

		$data = array(
			'content' =>'admin/retur_jual/retur_jual_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Retur Jual',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );
			
		$data['retur_data'] = array();
		$data['retur_detail'] = array(); 
		$data['retur_barang'] = array(); 
		$today = date('Y-m-d');
		$threema = strtotime("-3 months", strtotime($today));
		$max_tanggal = date("Y-m-d",$threema);
		$data['max_tanggal'] = $max_tanggal;
		$data['penjualan_list'] = $this->rtr_model->get_penjualan_list($max_tanggal);
		// $data['penjualan_list'] = array();

		if ($id != '') {
			$data['retur_data'] = $this->rtr_model->get_retur_data($id);
			$data['retur_detail'] = $this->rtr_model->get_retur_detail($id); 
			$data['retur_barang'] = $this->rtr_model->get_retur_barang($id);
		}
		$this->load->view('admin/template',$data);
	}

	function retur_jual_list_detail_insert(){

		$retur_jual_id = $this->input->post('retur_jual_id');

		$rekap = explode('--', $this->input->post('rekap_qty'));
		$subqty = 0;
		$subroll = 0;
		foreach ($rekap as $key => $value) {
			$qty = explode('??', $value);
			$subqty += ($qty[0] * $qty[1]);
			$subroll += $qty[1];
		}

		$data = array(
			'retur_jual_id' => $retur_jual_id ,
			'toko_id'=> $this->input->post('toko_id'),
			'supplier_id'=> 0 ,
			'gudang_id' => $this->input->post('gudang_id'),
			'barang_id' => $this->input->post('barang_id'),
			'warna_id' => $this->input->post('warna_id') ,
			'harga' => str_replace('.', '', $this->input->post('harga')),
			'subqty' => $subqty,
			'subroll' => $subroll,
			'keterangan' => $this->input->post('keterangan')
			);
		// $result_id = $this->common_model->db_insert('nd_retur_jual_detail',$data);


		// $this->common_model->db_insert_batch('nd_retur_jual_qty',$data_qty);

		$result = $this->rtr_model->retur_detail_insert($data, $rekap);
		// print_r($result);

		// $result_id = $this->common_model->db_insert('nd_retur_jual',$data);
				
		redirect($this->setting_link('retur_jual/retur_jual_detail').'/?id='.$retur_jual_id);
	}

	function retur_jual_qty_update(){
		$retur_jual_detail_id = $this->input->post('retur_jual_detail_id');
		$qty = $this->input->post('rekap_qty');

		$qty_list = explode('--', $qty);
		foreach ($qty_list as $key => $value) {
			$qty = explode('??', $value);
			$data_qty[$key] = array(
				'retur_jual_detail_id' => $retur_jual_detail_id,
				'qty' => $qty[0] ,
				'jumlah_roll' => $qty[1] );
		}

		$this->common_model->db_delete('nd_retur_jual_qty','retur_jual_detail_id',$retur_jual_detail_id);
		$this->common_model->db_insert_batch('nd_retur_jual_qty',$data_qty);
		// print_r($this->input->post());
		echo 'OK';
	}

	function retur_list_detail_remove(){
		$id= $this->input->post("id");
		$user_id = is_user_id();

		$this->rtr_model->retur_jual_detail_remove($id);
		// $this->common_model->db_custom_query("CALL sp_retur_jual_detail_remove($id, $user_id)");
		echo "OK";

	}
	
	function retur_jual_bayar_update(){

		// print_r($this->input->post());
		$id = $this->input->post('id');
		$tipe_bayar = $this->input->post('tipe_bayar');
		$notes_bayar = $this->input->post('notes_bayar');

		$data = array(
			'tipe_bayar' => $tipe_bayar,
			'notes_bayar' => $notes_bayar
		);

		$this->common_model->db_update("nd_retur_jual",$data,'id', $id);
		echo json_encode($tipe_bayar);

	}


	function retur_jual_potongan_update(){
		// print_r($this->input->post());
		$id = $this->input->post('id');
		$potongan_harga = $this->input->post('potongan_harga');
		$potongan_harga = str_replace(".","",$potongan_harga);

		$data = array(
			'potongan_harga' => $potongan_harga,
		);

		$this->common_model->db_update("nd_retur_jual",$data,'id', $id);
		echo json_encode('1');
	}

	
}