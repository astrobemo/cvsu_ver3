<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retur_Beli extends CI_Controller {

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

		$this->load->model('retur_beli_model','rtr_model',true);
		$this->load->model('finance_model','fi_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 ORDER BY warna_beli asc');
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

	function retur_beli_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/retur_beli/retur_beli_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Retur Beli',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['retur_list'] = $this->rtr_model->get_retur_list();
		
		$today = date('Y-m-d');
		$threema = strtotime("-12 months", strtotime($today));
		$max_tanggal = date("Y-m-d",$threema);

		$data['pembelian_list'] = $this->rtr_model->get_pembelian($max_tanggal);
		$data['max_tanggal'] = $max_tanggal;
		$this->load->view('admin/template',$data);
	}

	function pembelian_list_retur(){
		$id = $this->input->post('pembelian_id');
		$data_beli = $this->common_model->db_select('nd_pembelian where id='.$id);
		foreach ($data_beli as $row) {
			$supplier_id = $row->supplier_id;
			$toko_id = $row->toko_id;
			$faktur_beli = ($row->no_faktur != '' ? $row->no_faktur : ($row->no_surat_jalan != '' ? $row->no_surat_jalan : ''));
			if ($faktur_beli == '') {
				$d = $this->common_model->db_select('nd_supplier where id='.$supplier_id);
				foreach ($d as $row2) {
					$nama_supplier = $row2->nama;
				}
				$faktur_beli = $nama_supplier.' - '.is_reverse_date($row->tanggal);
			}
		}

		$tanggal = is_date_formatter($this->input->post('tanggal'));
		// $tahun = date('Y', strtotime($tanggal));

		foreach ($data_beli as $row) {
			$data = array(
				'pembelian_id' => $id,
				'no_faktur_beli' => $faktur_beli,
				'toko_id' => $toko_id,
				'tanggal' => date('Y-m-d'),
				'no_faktur' => null,
				'supplier_id' => $supplier_id,
				'user_id' => is_user_id(),
				);

		}
		$result_id = $this->common_model->db_insert('nd_retur_beli', $data);

		// $data_beli_detail = $this->common_model->db_select('nd_pembelian_detail where pembelian_id='.$id);
		// foreach ($data_beli_detail as $row) {
		// 	$data_detail = array(
		// 		'retur_beli_id' => $result_id ,
		// 		'gudang_id' => $row->gudang_id,
		// 		'barang_id' => $row->barang_id,
		// 		'warna_id' => $row->warna_id ,
		// 		'harga' => $row->harga_beli
		// 		);

		// 	$result_detail_id = $this->common_model->db_insert('nd_retur_beli_detail', $data_detail);

		// 	$data_beli_qty = $this->common_model->db_select('nd_pembelian_qty_detail where pembelian_detail_id='.$row->id );

		// 	foreach ($data_beli_qty as $row) {
		// 		$data_qty = array(
		// 			'retur_beli_detail_id' => $result_detail_id,
		// 			'qty' => $row->qty,
		// 			'jumlah_roll' => $row->jumlah_roll );

		// 		$this->common_model->db_insert('nd_retur_beli_qty', $data_qty);
		// 	}
		// }

		redirect($this->setting_link('retur_beli/retur_beli_detail').'/?id='.$result_id);

	}

	function retur_beli_list_insert(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tahun = date('Y', strtotime($tanggal));
		$no_faktur = 1;
		$data_get = $this->common_model->db_select("nd_retur_beli where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
		foreach ($data_get as $row) {
			$no_faktur = $row->no_faktur + 1;
		}

		$data = array(
			'retur_type_id' => $this->input->post('retur_type_id') ,
			'tanggal' => $tanggal,
			'no_faktur' => $no_faktur,
			'supplier_id' => $this->input->post('supplier_id') ,
			'nama_keterangan' => $this->input->post('nama_keterangan') ,
			'user_id' => is_user_id(),
			);

		// print_r($data);

		$result_id = $this->common_model->db_insert('nd_retur_beli',$data);
		redirect($this->setting_link('retur_beli/retur_beli_detail').'/?id='.$result_id);

	}

	function retur_beli_list_update(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$id = $this->input->post('id');
		$data = array(
			'tanggal' => $tanggal,
			'user_id' => is_user_id(),
			);

		// print_r($data);

		$this->common_model->db_update('nd_retur_beli',$data,'id',$id);
		redirect($this->setting_link('retur_beli/retur_beli_detail').'/?id='.$id);

	}

	function retur_beli_request_open(){
		// print_r($this->input->post());
		$retur_id = $this->input->post('retur_beli_id');
		$data = array(
			'status' => 1 );
		$this->common_model->db_update('nd_retur_beli',$data,'id',$retur_id);
		redirect(is_setting_link('retur_beli/retur_beli_detail').'?id='.$retur_id);
	}

	function retur_beli_print(){

		$retur_beli_id = $this->input->get('retur_beli_id');
		$nama_supplier = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_retur'] = $this->rtr_model->get_retur_data($retur_beli_id);
		$data['data_retur_detail'] = $this->rtr_model->get_retur_beli_detail($retur_beli_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/retur_beli/retur_beli_print',$data);
		
	}

	function retur_beli_list_close()
	{
		$id = $this->input->get('id');

		
		$get = $this->common_model->db_select("nd_retur_beli where id=".$id);
		foreach ($get as $row) {
			$no_faktur = $row->no_faktur;
			$tanggal = $row->tanggal;
		}

		$tahun = date('Y', strtotime($tanggal));

		if ($no_faktur == '') {
			$data_get = $this->common_model->db_select("nd_retur_beli where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
			foreach ($data_get as $row) {
				$no_faktur = $row->no_faktur + 1;
			}
            if ($no_faktur == '') {
                $no_faktur = 1;
            }
			// concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0'))
			$no_faktur_lengkap = $tahun.'/CVSUN/RETURBELI/'.str_pad($no_faktur,4,"0",STR_PAD_LEFT);

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

		$this->common_model->db_update('nd_retur_beli',$data,'id',$id);
		redirect(is_setting_link('retur_beli/retur_beli_detail').'/?id='.$id);
	}

	function retur_beli_list_batal(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$data = array(
			'status_aktif' => $status
		);

		$this->common_model->db_update("nd_retur_beli",$data,'id', $id);
		echo json_encode(1);
		
	}

//============================================detail=======================================================


	function retur_beli_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');

		$data = array(
			'content' =>'admin/retur_beli/retur_beli_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Retur Beli',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );

		$today = date('Y-m-d');
		$threema = strtotime("-12 months", strtotime($today));
		$max_tanggal = date("Y-m-d",$threema);

		$data['pembelian_list'] = $this->rtr_model->get_pembelian($max_tanggal);
		$data['max_tanggal'] = $max_tanggal;			
			
		$data['retur_data'] = array();
		$data['retur_detail'] = array(); 
		$data['retur_barang'] = array(); 
		if ($id != '') {
			$data['retur_data'] = $this->rtr_model->get_retur_data($id);
			$data['retur_detail'] = $this->rtr_model->get_retur_detail($id); 
			$data['retur_barang'] = $this->rtr_model->get_retur_barang($id); 
		}
		$this->load->view('admin/template',$data);
	}

	function retur_beli_list_detail_insert(){

		$retur_beli_id = $this->input->post('retur_beli_id');

		$subqty = 0;
		$subroll = 0;
		$rekap = explode('--', $this->input->post('rekap_qty'));

		foreach ($rekap as $key => $value) {
			$qty = explode('??', $value);
			$subqty += $qty[0] * $qty[1]; 
			$subroll += $qty[1]; 
		}

		$data = array(
			'retur_beli_id' => $retur_beli_id ,
			'pengali_type' => $this->input->post('pengali_type'),
			'gudang_id' => $this->input->post('gudang_id'),
			'barang_id' => $this->input->post('barang_id'),
			'warna_id' => $this->input->post('warna_id') ,
			'subqty' => $subqty,
			'subroll' => $subroll,
			'harga' => str_replace('.', '', $this->input->post('harga')),
			'keterangan' => $this->input->post('keterangan')
			);
		$result_id = $this->common_model->db_insert('nd_retur_beli_detail',$data);


		foreach ($rekap as $key => $value) {
			$qty = explode('??', $value);
			$data_qty[$key] = array(
				'retur_beli_detail_id' => $result_id,
				'qty' => $qty[0],
				'jumlah_roll' => $qty[1] ); 
		}

		$this->common_model->db_insert_batch('nd_retur_beli_qty',$data_qty);

		// $this->rtr_model->retur_detail_insert($data, $rekap);

		// print_r($data);

		// $result_id = $this->common_model->db_insert('nd_retur_beli',$data);
		redirect($this->setting_link('retur_beli/retur_beli_detail').'/?id='.$retur_beli_id);
	}

	function retur_beli_qty_update(){
		$retur_beli_detail_id = $this->input->post('retur_beli_detail_id');
		$qty = $this->input->post('rekap_qty');

		$qty_list = explode('--', $qty);
		foreach ($qty_list as $key => $value) {
			$qty = explode('??', $value);
			$data_qty[$key] = array(
				'retur_beli_detail_id' => $retur_beli_detail_id,
				'qty' => $qty[0] ,
				'jumlah_roll' => $qty[1] );
		}

		$this->common_model->db_delete('nd_retur_beli_qty','retur_beli_detail_id',$retur_beli_detail_id);
		$this->common_model->db_insert_batch('nd_retur_beli_qty',$data_qty);
		// print_r($this->input->post());
		echo 'OK';
	}

	function retur_list_detail_remove(){
		$id= $this->input->post("id");
		$user_id = is_user_id();

		$this->common_model->db_delete("nd_retur_beli_detail",'id', $id);
		$this->common_model->db_delete("nd_retur_beli_qty",'retur_beli_detail_id', $id);

		// $this->common_model->db_custom_query("CALL sp_retur_beli_detail_remove($id, $user_id)");
		echo "OK";

	}

}