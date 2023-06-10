<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	private $data = [];

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}elseif (is_user_time() == false) {
			redirect('home');
		}
		$this->data['username'] = is_username();
		$this->load->model('admin_model','',true);
		
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		
		// date_default_timezone_set("Asia/Jakarta");		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 order by nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

	}

	function index()
	{
		$this->dashboard();
		// echo 'admin';
	}

	function dashboard()
	{
		if (is_posisi_id() < 3) {
			$content = 'admin/dashboard';
		}else{
			$content = 'admin/dashboard_staff';
		}
		$data = array(
			'content' => $content,
			'breadcrumb_title' => 'Dashboard',
			'breadcrumb_small' => 'dashboard',
			'nama_menu' => 'menu_dashboard',
			'nama_submenu' => '',
			'common_data'=> $this->data );
		// $data['notifikasi_akunting'] = $this->admin_model->get_notifikasi_akunting_report();
		// $data['notifikasi_faktur_kosong'] = $this->admin_model->get_notifikasi_faktur_kosong();
		// $data['recap_pembelian_bulanan'] = $this->admin_model->recap_pembelian_bulanan(date('m'), date('Y'));
		// $data['recap_penjualan_bulanan'] = $this->admin_model->recap_penjualan_bulanan(date('m'), date('Y'));

		$data['notifikasi_akunting'] = array();
		$data['notifikasi_faktur_kosong'] = array();
		$data['recap_pembelian_bulanan'] = array();
		$data['recap_penjualan_bulanan'] = array();
		// echo 'tet';
		$this->load->view('admin/template',$data);
	}

	function setting_link($string){
		return rtrim(base64_encode($string),'=');
	}

	function check_double_data(){
		$nama_data = 'nd_'.$this->input->post('nama_data');
		$field = $this->input->post('field');
		$value = $this->input->post('value');

		$check = $this->common_model->db_free_query_superadmin("SELECT * FROM $nama_data WHERE $field LIKE '%$value%' AND status_aktif = 1");
		$idx = 0;
		$return_array= array();
		foreach ($check->result() as $row) {
			$return_array[$idx] = $row->$field;
			$idx++;
		}

		echo implode('<b>,</b> ', $return_array);
	}


//====================================cek barang harga===========================================
	
	function cek_harga_barang(){

		$barang_id = $this->input->post('barang_id');
		$customer_id = $this->input->post('customer_id');
		$limit = ($this->input->post('limit') == '' ? '3': $this->input->post('limit') ) ;

		$cond = ( $customer_id != 0 ? 'WHERE customer_id = '.$customer_id : '' );

		$get = $this->common_model->cek_harga_penjualan_barang($barang_id, $cond, $limit);
		echo json_encode($get);
		
	}


//====================================nota order===========================================

	function note_order_insert(){
		$ini = $this->input;
		$link = $ini->post('link');
		$id = $this->input->post('id');
		$tanggal_target = $ini->post('tanggal_target');
		if ($tanggal_target == '') {
			$tanggal_target = null;
		}else{
			$tanggal_target = is_date_formatter($ini->post('tanggal_target'));
		}

		$data = array(
			'tanggal_note_order' => is_datetime_formatter($ini->post('tanggal_note_order')),
			'tanggal_target' => $tanggal_target,
			'tipe_customer' => $ini->post('tipe_customer'),
			'customer_id' => ($ini->post('customer_id') == '' ? null : $ini->post('customer_id')),
			'nama_customer' => ($ini->post('nama_customer') == '' ? null : $ini->post('nama_customer')),
			'contact_info' => ($ini->post('contact_info') == '' ? null : $ini->post('contact_info'))
			 );

		if ($id == '') {
			$result_id = $this->common_model->db_insert("nd_note_order", $data);

			$data_detail = array(
				'note_order_id' => $result_id,
				'tipe_barang' => $ini->post('tipe_barang'),
				'barang_id' => $ini->post('barang_id'),
				'nama_barang' => $ini->post('nama_barang'),
				'warna_id' => $ini->post('warna_id'),
				'nama_warna' => $ini->post('nama_warna'),
				'roll' => $ini->post('roll'),
				'qty' => str_replace('.', '', $ini->post('qty')),
				'harga' => str_replace('.', '', $ini->post('harga')),
				 );

			$this->common_model->db_insert('nd_note_order_detail',$data_detail);

		}else{
			$this->common_model->db_update("nd_note_order", $data,'id', $id);
		}


		
		redirect($link);
	}

	function note_order_detail_insert(){
		$ini = $this->input;
		$link = $ini->post('link');
		$id = $this->input->post('note_order_detail_id');
		
		$data = array(
			'note_order_id' => $ini->post('note_order_id'),
			'tipe_barang' => $ini->post('tipe_barang'),
			'barang_id' => $ini->post('barang_id'),
			'nama_barang' => $ini->post('nama_barang'),
			'warna_id' => $ini->post('warna_id'),
			'nama_warna' => $ini->post('nama_warna'),
			'roll' => $ini->post('roll'),
			'qty' => str_replace('.', '', $ini->post('qty')),
			'harga' => str_replace('.', '', $ini->post('harga')),
			);

		// print_r($data);
		if ($id == '') {
			$result_id = $this->common_model->db_insert("nd_note_order_detail", $data);
		}else{
			$this->common_model->db_update("nd_note_order_detail", $data,'id', $id);
		}
		
		redirect($link);
	}

	function note_order_status_update(){
		$id = $this->input->get('id');
		$status = $this->input->get('status');
		if ($status == 0) {
			$done_by = null;
			$done_time = null;
		}else{	
			$done_by = is_user_id();
			$done_time = date("Y-m-d H:i:s");
		}

		$data = array(
			'status' => $status,
			'done_by' => $done_by,
			'done_time' => $done_time
			);
		// print_r($data);
		$this->common_model->db_update('nd_note_order_detail',$data,'id',$id);
		redirect('admin/dashboard');

	}

	function set_reminder(){

		$reminder = $this->input->get('reminder');

		if ($reminder != '') {
			$data = array(
				'note_order_id' => $this->input->get('note_order_id') ,
				'reminder' => is_datetime_formatter($reminder),
				'user_id' => is_user_id()
				);

			$this->common_model->db_insert('nd_reminder', $data);
		}

		redirect('admin/dashboard');
	}

	function reminder_remove(){
		$reminder_id = $this->input->post('reminder_id');
		$data = array(
			'status_on' => 0 );
		$this->common_model->db_update('nd_reminder', $data, 'id', $reminder_id);
		echo 'OK';
	}

	function note_order_item_remove(){
		$note_order_detail_id = $this->input->post('note_order_detail_id');
		$this->common_model->db_delete('note_order_detail','id',$note_order_detail_id);
		echo "OK";

	}


//=======================================notifikasi akunting=================================

	function notifikasi_akunting_insert(){
		
		$link = $this->input->post('link');
		$data = array(
			'customer_id' => $this->input->post('customer_id') ,
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'keterangan' => $this->input->post('keterangan'),
			'created' => date('Y-m-d H:i:s')
			 );

		// print_r($data);
		$this->common_model->db_insert("nd_notifikasi_akunting", $data);
		redirect($link);

	}

	function dismiss_notifikasi_akunting(){
		$id = $this->input->post('notifikasi_akunting_id');
		$data = array(
			'read_by' => is_user_id() ,
			'read_time' => date('Y-m-d H:i:s')
			 );

		$this->common_model->db_update('nd_notifikasi_akunting',$data,'id',$id);
		echo 'OK';
	}

//======================================dashboard===========================================


	function get_penjualan_bulan(){

		$recap_list = $this->admin_model->get_list_penjualan_by_date(date('Y-m-01'), date('Y-m-t'));
		echo json_encode($recap_list);
	}

	function get_penjualan_tahun(){

		$recap_list = $this->admin_model->get_list_penjualan_tahunan(date('Y-01-01'), date('Y-12-31'));

		echo json_encode($recap_list);
	}

	function get_barang_jual_terbanyak(){
		$recap_list = $this->admin_model->get_barang_jual_terbanyak(date('Y'));

		echo json_encode($recap_list);
	}

	function get_customer_beli_terbanyak(){
		$recap_list = $this->admin_model->get_customer_beli_terbanyak(date('Y'));

		echo json_encode($recap_list);
	}


	function get_barang_jual_warna_terbanyak(){
		$recap_list = $this->admin_model->get_barang_jual_warna_terbanyak(date('Y'));

		echo json_encode($recap_list);
	}

//====================================================================

	function setting_change_password()
	{
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/setting/change_password' ,
			'breadcrumb_title' => 'Setting',
			'breadcrumb_small' => 'ubah password',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1], 
			'common_data'=> $this->data );
		if ($this->session->flashdata('update_password')) {
			$data['n8_message'] = 'Update password berhasil !!';
		}else{
			$data['n8_message'] = '';
		}

		$this->load->view('admin/template',$data);
	}

	function update_password(){
		
		$data = array('password' => md5($this->input->post('password')) );
		$this->common_model->db_update('nd_user',$data,'id', is_user_id());
		$this->session->set_flashdata('update_password','Sukses');
		redirect(is_setting_link('admin/setting_change_password'));
	}

//==============================PIN======================================


	function change_pin()
	{
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/setting/change_pin' ,
			'breadcrumb_title' => 'Setting',
			'breadcrumb_small' => 'ubah PIN',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1], 
			'common_data'=> $this->data );
		if ($this->session->flashdata('change_code')) {
			$data['n8_message'] = 'Update password berhasil !!';
		}else{
			$data['n8_message'] = '';
		}

		$this->load->view('admin/template',$data);
	}

	function update_pin()
	{
		// $session_data = $this->session->userdata('logged_in');
		$user_id = is_user_id();
		$data = array(
			'PIN'=>$this->input->post('PIN'));
		
		$this->common_model->db_update('nd_user', $data,'id',$user_id);
		$session_array = array(
			'success'=>'OK');
		$this->session->set_flashdata('change_code',$session_array);
		redirect(is_setting_link('admin/change_pin'));
	}

	//========================================================

	function uploadCSVTest()
	{
		echo base_url().'assets_noondev/csvDataBarangInventory.csv';
		$file = fopen(base_url().'assets_noondev/csv/DataBarangInventory.csv',"r");

		print_r(fgetcsv($file));
		fclose($file);

		// while(! feof($file))
		// {
		// print_r(fgetcsv($file));
		// }

		// fclose($file);
	}

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */