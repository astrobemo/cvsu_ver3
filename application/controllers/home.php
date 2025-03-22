<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() 
	{
		parent:: __construct();
		$this->load->helper(array('html_helper', 'url_helper'));
		$this->load->library(array('form_validation','session'));
		$this->load->library('form_validation');
		$this->load->model('home_model','',true);

	}

	function index()
	{
		redirect('home/login_soft');
		// echo 'test';
	}

	function login_soft()
	{
		$this->form_validation->set_rules('username','username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password','password', 'trim|required|xss_clean|callback_check_database');
		if($this->form_validation->run() == FALSE){
			$this->load->view('login');
		}else{
			$session_data = $this->session->userdata('usaha_logged_in');
			redirect('admin');
		}
	}

	function check_database($password){
		 //Field validation succeeded.  Validate against database
		$username = $this->input->post('username');

		//query the database
   		$result = $this->home_model->check_database($username, $password);

   		if($result){

   			$tanggal = date('Y-m-d');
   			foreach ($result as $row) {
   				$posisi_id = $row->posisi_id;
   			}

   			if ($posisi_id >= 3) {
	   			$day_status = $this->common_model->db_select_num_rows("nd_close_day where tanggal_start <='".$tanggal."' AND tanggal_end >='".$tanggal."'");
	   			if ($day_status > 0) {
	   				$this->form_validation->set_message('check_database','TOKO Saat Ditutup Untuk Libur Lebaran');
		   			return false;
	   			}
   			}

   			$this->session->unset_userdata('usaha_logged_in');
   			$session_array = array();
   			foreach ($result as $row) {
   				// $user_type = $row->type;
   				$session_array = array(
   					'username'=>$username,
   					'user_id'=>$row->id,
   					'posisi_id'=>$row->posisi_id,
   					'time_start' => $row->time_start,
   					'time_end' => $row->time_end
   					);
   				$this->session->set_userdata('usaha_logged_in',$session_array);
   			}

   			$data = array(
				'time' => time() );
			$this->session->set_userdata('user_session',$data);
   			return true;
   		}else{
   			$this->form_validation->set_message('check_database','Invalid username or password');
   			return false;
   		}
	}

	function logout(){
		$this->session->unset_userdata('usaha_logged_in');
		redirect('home');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */