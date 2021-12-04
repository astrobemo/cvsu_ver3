<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akunting extends CI_Controller {

	private $data = [];

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}elseif (is_user_time() == false || is_user_session() == false) {
			redirect('home');
		}
		$this->data['username'] = is_username();
		$this->load->model('akunting_model','ak_model',true);
		
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());

	}

	function index()
	{
		// $this->dashboard();
	}

	function setting_link($string){
		return rtrim(base64_encode($string),'=');
	}

//====================================================================

	function kode_akun_list()
	{
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/akunting/kode_akun_list' ,
			'breadcrumb_title' => 'Akunting',
			'breadcrumb_small' => 'Daftar Kode Akun',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1], 
			'common_data'=> $this->data );

		$data['tipe_akun_master'] = $this->common_model->db_select('nd_tipe_akun_master where status_aktif = 1');
		$data['kode_akun_list'] = $this->ak_model->get_akun_list("'' OR kode_akun_id is null OR sub_akun_status = 0 order BY tipe_akun_master_id,no_akun asc");
		
		foreach ($data['kode_akun_list'] as $row) {
			$data['kode_akun_list_sub'][$row->id] = $this->ak_model->get_akun_list($row->id.' AND sub_akun_status = 1');
		}

		$this->load->view('admin/template',$data);
	}

	function get_option_kode_akun(){
		$tipe_akun_master_id = $this->input->post('tipe_akun_master_id');
		$kode_akun_id = $this->input->post('kode_akun_id');
		if ($kode_akun_id == '') {
			$kode_akun_id = "''";
		}

		$result = $this->common_model->db_select("nd_kode_akun where tipe_akun_master_id =".$tipe_akun_master_id." and sub_akun_status = 0 AND id !=".$kode_akun_id);
        echo json_encode( $result );

	}

	function kode_akun_update(){
		
		$id = $this->input->post('id');

		if ($this->input->post('sub_akun_status') == 'on') {
			$sub_akun_status = 1;
		}else{
			$sub_akun_status = 0;
		}


		$data = array(
			'tipe_akun_master_id' => $this->input->post('tipe_akun_master_id'),
			'no_akun' => $this->input->post('no_akun'),
			'nama' => $this->input->post('nama'),
			'sub_akun_status' => $sub_akun_status,
			'kode_akun_id' => $this->input->post('kode_akun_id'),
			'opening_balance' => $this->input->post('opening_balance'),
			'tanggal' => $this->input->post('tanggal')
			);



		// print_r($data);

		if ($id == '') {
			$this->common_model->db_insert('nd_kode_akun',$data);
		}else{
			$this->common_model->db_update('nd_kode_akun',$data,'id',$id);
		}
		redirect($this->setting_link('akunting/kode_akun_list'));
	}

	function check_kode_akun_new(){
		$no_akun = $this->input->post('no_akun');
		$result = $this->common_model->db_select_num_rows("nd_kode_akun where no_akun ='".$no_akun."' limit 1");
		if ($result == 0) {
			echo 'true';
		}else{
			echo 'false';
		}

	}

	function check_kode_akun_update(){
		$no_akun = $this->input->post('no_akun');
		$id = $this->input->post('id');
		
		$result = $this->common_model->db_select_num_rows("nd_kode_akun where id !=".$id." AND no_akun ='".$no_akun."' limit 1");
		if ($result == 0) {
			echo 'true';
		}else{
			echo 'false';
		}

	}

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */