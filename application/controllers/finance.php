<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends CI_Controller {

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
		$this->load->model('finance_model','fi_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 ORDER BY nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');
	}

	function index(){
		redirect('admin/dashboard');
	}

//============================hutang awal section=================================================

	function hutang_awal(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/finance/hutang_awal',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Hutang Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['hutang_list'] = $this->fi_model->get_hutang_awal(); 
		$this->load->view('admin/template',$data);
	}

	function hutang_awal_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$supplier_id = $this->input->get('supplier_id');

		$data = array(
			'content' =>'admin/finance/hutang_awal_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Hutang Awal Detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'supplier_id' => $supplier_id,
			'supplier_data' => $this->common_model->db_select("nd_supplier where id=".$supplier_id)
			 );


		$data['user_id'] = is_user_id();
		$data['hutang_list_detail'] = $this->common_model->db_select(" nd_hutang_awal where supplier_id=".$supplier_id); 
		$this->load->view('admin/template',$data);
	}

	function hutang_awal_insert(){

		$supplier_id = $this->input->post('supplier_id');

		$data = array(
			'supplier_id' => $supplier_id,
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'no_faktur' => $this->input->post('no_faktur'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id() ,
			 );

		$this->common_model->db_insert('nd_hutang_awal',$data);
		redirect(is_setting_link('finance/hutang_awal_detail').'?supplier_id='.$supplier_id);
	}

	function hutang_awal_update(){

		$supplier_id = $this->input->post('supplier_id');
		$id = $this->input->post('id');

		$data = array(
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'no_faktur' => $this->input->post('no_faktur'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id()
			 );

		$this->common_model->db_update('nd_hutang_awal',$data,'id',$id);
		redirect(is_setting_link('finance/hutang_awal_detail').'?supplier_id='.$supplier_id);
	}

//============================hutang section=================================================

	function hutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal = date('Y-m-d');
		if ($this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}
		$data = array(
			'content' =>'admin/finance/hutang_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => $tanggal,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['hutang_list'] = $this->fi_model->get_hutang_list($tanggal);
		$this->load->view('admin/template',$data);
	}

	function hutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$supplier_id = $this->input->get('supplier_id');

		$data = array(
			'content' =>'admin/finance/hutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Hutang Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'supplier_id' => $supplier_id,
			'supplier_data' => $this->common_model->db_select('nd_supplier where id='.$supplier_id) );

		$data['hutang_list_detail'] = $this->fi_model->get_hutang_list_detail($supplier_id); 
		$this->load->view('admin/template',$data);
	}

	function hutang_payment(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date("Y-m-d");
		$tanggal_end = date("Y-m-d");
		$supplier_id = '';
		$toko_id = '1';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		if ($this->input->get('supplier_id') && $this->input->get('supplier_id') != '') {
			$supplier_id = $this->input->get('supplier_id');
		}

		if ($this->input->get('toko_id') && $this->input->get('toko_id') != '') {
			$toko_id = $this->input->get('toko_id');
		}

		$cond = "WHERE toko_id = ".$toko_id." ";
		if ($supplier_id != '') {
			$cond .= "AND supplier_id = ".$supplier_id;
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_hutang',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Pembayaran Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'supplier_id' => $supplier_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end) );


		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$data['pembayaran_hutang_list'] = $this->fi_model->get_pembayaran_hutang($tanggal_start, $tanggal_end, $cond);
			foreach ($data['pembayaran_hutang_list'] as $row) {

				$periode = $this->fi_model->get_periode_pembelian($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_hutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_awal_detail($row->id);
				$data['pembayaran_hutang_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_detail($row->id);
				$data['pembayaran_hutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_hutang_nilai WHERE pembayaran_hutang_id=".$row->id);

			}
		}else{
			$data['pembayaran_hutang_list'] = $this->fi_model->get_pembayaran_hutang_unbalance();
			foreach ($data['pembayaran_hutang_list'] as $row) {

				$periode = $this->fi_model->get_periode_pembelian($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_hutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_awal_detail($row->id);
				$data['pembayaran_hutang_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_detail($row->id);
				$data['pembayaran_hutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_hutang_nilai WHERE pembayaran_hutang_id=".$row->id);
			}

			$data['status_view'] = 0;
		}

		// print_r($data['pembayaran_hutang_list']);
		$this->load->view('admin/template',$data);
	}

	function hutang_payment_form(){
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('tanggal_start')) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');

		}else{
			$tanggal_start = date("Y-m-01"); 
			$tanggal_end = date("Y-m-t");
			$toko_id = '';
			$supplier_id = '';
		}

		$pembayaran_hutang_id = '';
		if ($this->input->get('id')) {
			$pembayaran_hutang_id = $this->input->get('id');
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_hutang_form',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Formulir Pembayaran Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'supplier_id' => $supplier_id );


		if ($pembayaran_hutang_id != '') {
			$data['pembayaran_hutang_data'] = $this->fi_model->get_pembayaran_hutang_data($pembayaran_hutang_id);
			$periode = $this->fi_model->get_periode_pembelian($pembayaran_hutang_id);
			foreach ($periode as $row) {
				$data['tanggal_start'] = $row->tanggal_start;
				$data['tanggal_end'] = $row->tanggal_end;
			}

			$data['bank_history'] = $this->fi_model->get_bank_bayar_history();
			$data['bank_default'] = $this->fi_model->get_bank_default();
			$data['pembayaran_hutang_awal'] = $this->fi_model->get_pembayaran_hutang_awal_detail($pembayaran_hutang_id); 
			$data['pembayaran_hutang_detail'] = $this->fi_model->get_pembayaran_hutang_detail($pembayaran_hutang_id); 
			$data['pembayaran_hutang_nilai'] = $this->common_model->db_select("nd_pembayaran_hutang_nilai where pembayaran_hutang_id=".$pembayaran_hutang_id);
			$data['retur_beli'] = $this->fi_model->get_retur_beli_detail($pembayaran_hutang_id);
		
		}elseif ($toko_id != '' && $supplier_id != '') {
			$data['pembayaran_hutang_data'] = array();
			$data['pembayaran_hutang_awal'] = $this->fi_model->get_hutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $supplier_id); 
			$data['pembayaran_hutang_detail'] = $this->fi_model->get_hutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $supplier_id); 
			$data['retur_beli'] = $this->fi_model->get_retur_beli_belum_lunas($supplier_id, $toko_id);
			$data['pembayaran_hutang_nilai'] = array();
			$data['bank_history'] = array();
			$data['bank_default'] = array();
		}else{
			$data['pembayaran_hutang_data'] = array();
			$data['pembayaran_hutang_awal'] = array();
			$data['pembayaran_hutang_detail'] = array(); 
			$data['pembayaran_hutang_nilai'] = array();
			$data['bank_history'] = array();
			$data['bank_default'] = array();
			$data['retur_beli'] = array();

		}
		$this->load->view('admin/template',$data);
	}

	function pembayaran_hutang_insert(){
		$ini = $this->input;
		$pembayaran_hutang_id = $this->input->post('pembayaran_hutang_id');
		// echo $pembayaran_hutang_id;
		

		if ($pembayaran_hutang_id == '') {

			$data = array(
			'supplier_id' => $ini->post('supplier_id'),
			'toko_id' => $ini->post('toko_id'),
			'potongan_hutang' => str_replace('.', '', $ini->post('potongan_hutang')),
			'pembulatan' => 0,
			'status_aktif' => 1,
			// 'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
			// 'nama_bank' => $ini->post('nama_bank'),
			// 'no_rek_bank' => $ini->post('no_rek_bank'),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			// 'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')),
			// 'nama_penerima' => $ini->post('nama_penerima'),
			// 'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

			$result_id = $this->common_model->db_insert('nd_pembayaran_hutang',$data);

			$post = (array)$this->input->post();
			$idx = 0;
			foreach ($post as $key => $value) {
				if (strpos($key, 'bayar_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$pembelian_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'hutang_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$hutang_awal_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'retur_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$retur_beli_id[$idx] = $data_get[1];
						$idx++;
					}
				}
			}

			$idx = 0;
			if (isset($pembelian_id)) {
				foreach ($pembelian_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_hutang_id' => $result_id,
						'pembelian_id' => $value ,
						'amount' => str_replace('.', '', $post['bayar_'.$value]),
						'data_status' => 1
						 );
					$idx++;
				}
			}

			if (isset($retur_beli_id)) {
				foreach ($retur_beli_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_hutang_id' => $result_id,
						'pembelian_id' => $value ,
						'amount' => str_replace('.', '', $post['retur_'.$value]),
						'data_status' => 3
						 );
					$idx++;
				}
			}

			if (isset($hutang_awal_id)) {
				foreach ($hutang_awal_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_hutang_id' => $result_id,
						'pembelian_id' => $value ,
						'amount' => str_replace('.', '', $post['hutang_'.$value]),
						'data_status' => 2
						 );
					$idx++;
				}
			}


			$this->common_model->db_insert_batch('nd_pembayaran_hutang_detail',$data_detail);

			$pembayaran_hutang_id = $result_id;	
		}else{

			$data = array(
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
			'nama_bank' => $ini->post('nama_bank'),
			'no_rek_bank' => $ini->post('no_rek_bank'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')),
			'nama_penerima' => $ini->post('nama_penerima'),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

			$this->common_model->db_update('nd_pembayaran_hutang',$data,'id',$pembayaran_hutang_id);	
		}
		
		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id);

	}

	function update_bayar_hutang_detail(){
		$id = $this->input->post('id');
		$data = array(
			'amount' => $this->input->post('amount') );
		$this->common_model->db_update('nd_pembayaran_hutang_detail',$data,'id',$id);
		echo "OK";
	}

	function pembayaran_hutang_nilai_insert(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_hutang_id = $ini->post('pembayaran_hutang_id');
		$data = array(
			'pembayaran_hutang_id' =>  $pembayaran_hutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			// 'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan') 
			);

		// print_r($data);
		$this->common_model->db_insert('nd_pembayaran_hutang_nilai', $data);

		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id.'#bayar-section');


	}

	function pembayaran_hutang_nilai_delete(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_hutang_id = $ini->get('pembayaran_hutang_id');
		$id = $ini->get('id');

		// print_r($data);
		$this->common_model->db_delete('nd_pembayaran_hutang_nilai', 'id',$id);

		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id.'#bayar-section');

	}

	function pembayaran_hutang_nilai_update(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_hutang_id = $ini->post('pembayaran_hutang_id');
		$id = $ini->post('pembayaran_hutang_nilai_id');
		$data = array(
			'pembayaran_hutang_id' =>  $pembayaran_hutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			// 'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan') );

		// print_r($data);
		$this->common_model->db_update("nd_pembayaran_hutang_nilai", $data,'id', $id);
		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id);
	}

	function update_pembulatan_hutang(){
		$id = $this->input->post('id');
		$data = array(
			'pembulatan' => $this->input->post('pembulatan') );
		$this->common_model->db_update("nd_pembayaran_hutang", $data, "id", $id);
		echo "OK";

	}

	function update_potongan_hutang(){
		$id = $this->input->post('id');
		$data = array(
			'potongan_hutang' => str_replace('.', '', $this->input->post('potongan_hutang') )
			);
		$this->common_model->db_update("nd_pembayaran_hutang", $data, "id", $id);
		echo "OK";

	}


//============================piutang awal section=================================================

	function piutang_awal(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/finance/piutang_awal',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'piutang Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['piutang_list'] = $this->fi_model->get_piutang_awal(); 
		$this->load->view('admin/template',$data);
	}

	function piutang_awal_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->input->get('customer_id');

		$data = array(
			'content' =>'admin/finance/piutang_awal_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'piutang Awal Detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'customer_data' => $this->common_model->db_select("nd_customer where id=".$customer_id)
			 );


		$data['user_id'] = is_user_id();
		$data['piutang_list_detail'] = $this->common_model->db_select(" nd_piutang_awal where customer_id=".$customer_id); 
		$this->load->view('admin/template',$data);
	}

	function piutang_awal_insert(){

		$customer_id = $this->input->post('customer_id');

		$data = array(
			'customer_id' => $customer_id,
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'jumlah_roll' => $this->input->post('jumlah_roll'),
			'no_faktur' => $this->input->post('no_faktur'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id() ,
			 );

		$this->common_model->db_insert('nd_piutang_awal',$data);
		redirect(is_setting_link('finance/piutang_awal_detail').'?customer_id='.$customer_id);
	}

	function piutang_awal_update(){

		$customer_id = $this->input->post('customer_id');
		$id = $this->input->post('id');

		$data = array(
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'no_faktur' => $this->input->post('no_faktur'),
			'jumlah_roll' => $this->input->post('jumlah_roll'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id()
			 );

		$this->common_model->db_update('nd_piutang_awal',$data,'id',$id);
		redirect(is_setting_link('finance/piutang_awal_detail').'?customer_id='.$customer_id);
	}


//============================piutang section=================================================

	function piutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date('2024-09-01');
		$tanggal_end = date('Y-m-d');
		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		$data = array(
			'content' =>'admin/finance/piutang_list_by_toko',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => $tanggal_start,
			'tanggal_end' => $tanggal_end,
			'data_isi'=> $this->data );

		// $data['piutang_list'] = $this->fi_model->get_piutang_list_all($tanggal_start, $tanggal_end); 
		$select_toko = "";

		foreach ($this->toko_list_aktif as $row) {
			$select_toko .= "sum(if(toko_id = ".$row->id.", sisa_piutang, 0)) as sisa_piutang_".$row->id.", 
			MAX(if(toko_id = ".$row->id.", tanggal_start, '')) as tanggal_start_".$row->id.", 
			MAX(if(toko_id = ".$row->id.", tanggal_end, '')) as tanggal_end_".$row->id.",
			sum(if(toko_id = ".$row->id.", sisa_kontra, 0)) as sisa_kontra_".$row->id.",
			group_concat(if(toko_id = ".$row->id.", sisa_kontra_data, '')) as sisa_kontra_data_".$row->id.",
			group_concat(if(toko_id = ".$row->id.", pembayaran_piutang_id, '')) as pembayaran_piutang_id_".$row->id.",";
		}
		$data['piutang_list'] = $this->fi_model->get_piutang_list_all_by_toko($tanggal_start, $tanggal_end, $select_toko); 

		
		$this->load->view('admin/template',$data);
		if (is_posisi_id()==1) {
            $this->output->enable_profiler(TRUE);
			
		}
	}

	function piutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->input->get('customer_id');

		$data = array(
			'content' =>'admin/finance/piutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Piutang Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id );

		$data['piutang_list_detail'] = $this->fi_model->get_piutang_list_detail($customer_id); 
		$this->load->view('admin/template',$data);
	}

	function piutang_payment(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date("Y-m-d");
		$tanggal_end = date("Y-m-d");
		$customer_id = '';
		$toko_id = '1';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		if ($this->input->get('customer_id') && $this->input->get('customer_id') != '') {
			$customer_id = $this->input->get('customer_id');
		}

		if ($this->input->get('toko_id') && $this->input->get('toko_id') != '') {
			$toko_id = $this->input->get('toko_id');
		}

		$cond = "WHERE toko_id = ".$toko_id." ";
		if ($customer_id != '') {
			$cond .= "AND customer_id = ".$customer_id;
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_piutang',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end) );


		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$data['pembayaran_piutang_list'] = $this->fi_model->get_pembayaran_piutang($tanggal_start, $tanggal_end, $cond);
			// echo $data['pembayaran_piutang_list'];
			foreach ($data['pembayaran_piutang_list'] as $row) {

				$periode = $this->fi_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_piutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_piutang_awal_detail($row->id);
				$data['pembayaran_piutang_detail'][$row->id] = $this->fi_model->get_pembayaran_piutang_detail($row->id);
				$data['retur_jual'][$row->id] = $this->fi_model->get_pembayaran_retur_jual_detail($row->id);
				$data['pembayaran_piutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_piutang_nilai WHERE pembayaran_piutang_id=".$row->id);

			}
		}else{
			$data['pembayaran_piutang_list'] = $this->fi_model->get_pembayaran_piutang_unbalance();
			foreach ($data['pembayaran_piutang_list'] as $row) {

				$periode = $this->fi_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_piutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_piutang_awal_detail($row->id);
				$data['pembayaran_piutang_detail'][$row->id] = $this->fi_model->get_pembayaran_piutang_detail($row->id);				
				$data['retur_jual'][$row->id] = $this->fi_model->get_pembayaran_retur_jual_detail($row->id);
				$data['pembayaran_piutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_piutang_nilai WHERE pembayaran_piutang_id=".$row->id);
			}

			$data['status_view'] = 0;
		}

		$this->load->view('admin/template',$data);
	}

	// function piutang_payment(){
	// 	$menu = is_get_url($this->uri->segment(1)) ;

	// 	$data = array(
	// 		'content' =>'admin/finance/pembayaran_piutang',
	// 		'breadcrumb_title' => 'Finance',
	// 		'breadcrumb_small' => 'Daftar Pembayaran Piutang',
	// 		'nama_menu' => $menu[0],
	// 		'nama_submenu' => $menu[1],
	// 		'common_data'=> $this->data,
	// 		'data_isi'=> $this->data );


	// 	$data['pembayaran_piutang_list'] = $this->fi_model->get_pembayaran_piutang(); 
	// 	$this->load->view('admin/template',$data);
	// }

	function piutang_payment_form(){
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('tanggal_start')) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');
			$status_jt = $this->input->get('status_jt');

		}else{
			$tanggal_start = date("Y-m-01"); 
			$tanggal_end = date("Y-m-t");
			$toko_id = 1;
			$customer_id = '';
			$status_jt = 0;
		}

		$pembayaran_piutang_id = '';
		if ($this->input->get('id')) {
			$pembayaran_piutang_id = $this->input->get('id');
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_piutang_form',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Formulir Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'customer_id' => $customer_id,
			'status_jt' => $status_jt );


		$data['toko_data'] = $this->common_model->db_select('nd_toko where id='.$toko_id);

		if ($pembayaran_piutang_id != '') {
			$data['pembayaran_piutang_data'] = $this->fi_model->get_pembayaran_piutang_data($pembayaran_piutang_id);
			$toko_id = $data['pembayaran_piutang_data'][0]->toko_id;
			$periode = $this->fi_model->get_periode_penjualan($pembayaran_piutang_id);
			foreach ($periode as $row) {
				$data['tanggal_start'] = $row->tanggal_start;
				$data['tanggal_end'] = $row->tanggal_end;
			}

			foreach ($data['pembayaran_piutang_data'] as $row) {
				$customer_id = $row->customer_id;
			}
			
			$data['pembayaran_piutang_awal_detail'] = $this->fi_model->get_pembayaran_piutang_awal_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_detail'] = $this->fi_model->get_pembayaran_piutang_detail($pembayaran_piutang_id, $toko_id); 
			$data['retur_jual'] = $this->fi_model->get_pembayaran_retur_jual_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_nilai'] = $this->common_model->db_select("nd_pembayaran_piutang_nilai where pembayaran_piutang_id=".$pembayaran_piutang_id);
			$data['bank_history'] = $this->fi_model->get_customer_bank_bayar_history($customer_id);
            $data['dp_list_detail'] = $this->fi_model->get_dp_berlaku($customer_id, $pembayaran_piutang_id); 
			
		}elseif ($toko_id != '' && $customer_id != '') {
			
			$cond_jt = '';
			if ($status_jt == 1) {
				$cond_jt = "AND new_jatuh_tempo <= '".date('Y-m-d')."'";
			}

			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_awal_detail'] = $this->fi_model->get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id); 
			$data['pembayaran_piutang_detail'] = $this->fi_model->get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id, $cond_jt); 
			$data['pembayaran_piutang_nilai'] = array();

			$data['retur_jual'] = $this->fi_model->get_retur_jual($toko_id, $customer_id);
			$data['bank_history'] = array();
            $data['dp_list_detail'] = array();
			

		}else{
			$data['pembayaran_piutang_awal_detail'] = array(); 
			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_detail'] = array(); 
			$data['bank_history'] = array();
            $data['dp_list_detail'] = array();
			$data['retur_jual'] = array();
		}

		$this->load->view('admin/template',$data);

		if (is_posisi_id()==1) {
			$this->output->enable_profiler(TRUE);
		}
	}

	function pembayaran_piutang_insert(){
		$ini = $this->input;
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
		// echo $pembayaran_piutang_id;
		

		if ($pembayaran_piutang_id == '') {

			$data = array(
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'customer_id' => $ini->post('customer_id'),
			'toko_id' => $ini->post('toko_id'),
			'pembulatan' => 0,
			'user_id' => is_user_id() );

			$result_id = $this->common_model->db_insert('nd_pembayaran_piutang',$data);

			$post = (array)$this->input->post();
			$idx = 0;
			foreach ($post as $key => $value) {
				if (strpos($key, 'bayar_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$penjualan_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'piutang_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$piutang_awal_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'retur_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$retur_jual_id[$idx] = $data_get[1];
						$idx++;
					}
				}
				
			}

			//===========================

			// print_r($penjualan_id);
			$idx = 0;

			if (isset($penjualan_id)) {
				foreach ($penjualan_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['bayar_'.$value]),
						'data_status' => 1
						);
					$idx++;
				}
			}

			if (isset($retur_jual_id)) {
				foreach ($retur_jual_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['retur_'.$value]),
						'data_status' => 3
						);
					$idx++;
				}
			}

			if (isset($piutang_awal_id)) {
				foreach ($piutang_awal_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['piutang_'.$value]),
						'data_status' => 2
						 );
					$idx++;
				}
			}

			
			$this->common_model->db_insert_batch('nd_pembayaran_piutang_detail',$data_detail);	
			$pembayaran_piutang_id = $result_id;	
		}else{

			$data = array(
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
			'nama_bank' => $ini->post('nama_bank'),
			'no_rek_bank' => $ini->post('no_rek_bank'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')),
			'nama_penerima' => $ini->post('nama_penerima'),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

			$this->common_model->db_update('nd_pembayaran_piutang',$data,'id',$pembayaran_piutang_id);
					
		}
		
		redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id);

	}

	function update_tanggal_kontra_bon(){
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')) );
		$this->common_model->db_update('nd_pembayaran_piutang', $data,'id',$pembayaran_piutang_id);
		echo 'OK';
	}

	function pembayaran_piutang_dp_update(){
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
		$tanggal_transfer = is_date_formatter($this->input->post('tanggal_transfer'));
		$post = (array)$this->input->post();
        $idx = 0;
        foreach ($post as $key => $value) {
            if (strpos($key, 'amount_') !== false) {
                // echo $key.'-->'.$value.'<br/>';
                $data_get = explode('_', $key);
                if ($value != '' && $value != 0) {
                    $dp_masuk_id[$idx] = $data_get[1];
                    $isi[$idx] = str_replace('.', '', $value);
                    $idx++;
                }
            }
            
        }


        foreach ($dp_masuk_id as $key => $value) {
            $data = array(
            	'tanggal_transfer' => $tanggal_transfer,
                'pembayaran_piutang_id' => $pembayaran_piutang_id ,
                'pembayaran_type_id' => 5,
                'dp_masuk_id' => $dp_masuk_id[$key],
                'amount' => $isi[$key],
                );

            $id = '';
            $get_id = $this->common_model->db_select("nd_pembayaran_piutang_nilai where pembayaran_piutang_id =".$pembayaran_piutang_id." AND pembayaran_type_id = 5 AND dp_masuk_id =".$value);
            foreach ($get_id as $row) {
                $id = $row->id;
            }

            // echo $id;
            // print_r($data);
            if ($id == '') {
                if ($isi[$key] != 0) {
                    $this->common_model->db_insert('nd_pembayaran_piutang_nilai', $data);
                }
            }else{
                $this->common_model->db_update('nd_pembayaran_piutang_nilai',$data, 'id', $id);
            }

        }

        redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id);

	}

	function update_bayar_piutang_detail(){
		$id = $this->input->post('id');
		$data = array(
			'amount' => $this->input->post('amount') );
		$this->common_model->db_update('nd_pembayaran_piutang_detail',$data,'id',$id);
		echo "OK";
	}

	function pembayaran_piutang_nilai_insert(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan') );

		// print_r($data);
		$this->common_model->db_insert('nd_pembayaran_piutang_nilai', $data);

		redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id.'#bayar-section');

	}

	function pembayaran_piutang_nilai_update(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$id = $ini->post('pembayaran_piutang_nilai_id');
		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan')
				);

		// print_r($data);
		$this->common_model->db_update("nd_pembayaran_piutang_nilai", $data,'id', $id);
		redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id);
	}

	function pembayaran_piutang_nilai_delete()
	{
		$id = $this->input->get('id');
		$this->common_model->db_delete('nd_pembayaran_piutang_nilai','id',$id);
		$pembayaran_piutang_id = $this->input->get('pembayaran_piutang_id');
		redirect(is_setting_link('finance/piutang_payment_form').'?id='.$pembayaran_piutang_id);
	}

	function update_pembulatan_piutang(){
		$id = $this->input->post('id');
		$data = array(
			'pembulatan' => $this->input->post('pembulatan') );
		$this->common_model->db_update("nd_pembayaran_piutang", $data, "id", $id);
		echo "OK";

	}

	function update_pembayaran_nilai_by_mutasi(){
		$pembayaran_piutang_nilai_id = $this->input->post('id');
		$data = array(
			'amount' => str_replace(',', '', $this->input->post('amount')) );
		$this->common_model->db_update('nd_pembayaran_piutang_nilai',$data,'id',$pembayaran_piutang_nilai_id);
		echo 'OK';
	}



//==============================daftar giro=================================================

	function giro_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-31');

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') ) {
			if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
				$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
				$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			}
		}

		$data = array(
			'content' =>'admin/finance/giro_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Giro Masuk',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end) );

		$data['giro_setor_list'] = $this->fi_model->get_daftar_giro($tanggal_start, $tanggal_end); 
		$this->load->view('admin/template',$data);
	}

	function giro_setor_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-31');
		$cond = '';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') ) {
			if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
				$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
				$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
				$cond  = "WHERE tanggal >= '".$tanggal_start."' AND tanggal <=".$tanggal_end."'";
			}
		}

		$data = array(
			'content' =>'admin/finance/giro_setor_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Giro',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			// 'tanggal_start' => is_reverse_date($tanggal_start),
			// 'tanggal_end' => is_reverse_date($tanggal_end)
			 );

		$data['giro_setor_list'] = $this->common_model->db_select("nd_giro_setor ".$cond); 
		$this->load->view('admin/template',$data);
	}

	function giro_setor_list_form(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-31');
		$toko_id = 1;

		$cond2 = 'WHERE toko_id = 1';
		$cond = '';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') ) {
			if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
				$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
				$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
				$toko_id = $this->input->get('toko_id');
				$cond  = "AND jatuh_tempo >= '".$tanggal_start."' AND jatuh_tempo <='".$tanggal_end."'";
			}
		}

		$data = array(
			'content' =>'admin/finance/giro_setor_list_form',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Giro',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id
			 );

		if ($this->input->get('id') && $this->input->get('id') != '') {
			$giro_setor_id = $this->input->get('id');
			$data['giro_data'] = $this->common_model->db_select('nd_giro_setor where id='.$giro_setor_id);
			$data['giro_list_detail'] = $this->fi_model->get_daftar_giro_setor($giro_setor_id);
		}else{
			$data['giro_data'] = array();
			$data['giro_list_detail'] = $this->fi_model->get_daftar_giro_mentah($cond, $cond2);
		}

		$this->load->view('admin/template',$data);
	}

	function giro_setor_insert(){
		$ini = $this->input;
		$giro_setor_id = $this->input->post('giro_setor_id');
		
		if ($giro_setor_id == '') {

			$data = array(
			'toko_id' => $ini->post('toko_id'),
			'keterangan' => $ini->post('keterangan'),
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'user_id' => is_user_id() );

			// print_r($data);

			$result_id = $this->common_model->db_insert('nd_giro_setor',$data);

			$post = (array)$this->input->post();
			$idx = 0;
			foreach ($post as $key => $value) {
				if (strpos($key, 'bayar_') !== false) {
					echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($post['bayar_'.$data_get[1]] != '' && $post['bayar_'.$data_get[1]] != 0) {
						$setor_id[$idx] = $data_get[1];
						$idx++;
					}
				}
				
			}

			$numerator = 1;
			$get_latest_num = $this->common_model->db_select("nd_giro_setor_detail order by numerator desc limit 1");
			foreach ($get_latest_num as $row) {
				$numerator = $row->numerator + 1;
			}

			foreach ($setor_id as $key => $value) {
				$data_detail[$key] = array(
					'numerator'=> $numerator,
					'giro_setor_id' => $result_id,
					'pembayaran_piutang_nilai_id' => $value
					);
				$numerator++;
			}

			// print_r($data_detail);
			
			$this->common_model->db_insert_batch('nd_giro_setor_detail',$data_detail);	
			$giro_setor_id = $result_id;	
		}
		
		redirect(is_setting_link('finance/giro_setor_list_form').'/?id='.$giro_setor_id);
	}

	// function update_setor_giro(){
	// 	$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
	// 	$data = array(
	// 		'tanggal_setor' => is_date_formatter($this->input->post('tanggal_setor'))
	// 		);

	// 	$this->common_model->db_update('nd_pembayaran_piutang',$data,'id',$pembayaran_piutang_id);
	// 	echo 'OK';
		
	// }

//==============================mutasi hutang=================================================

	function mutasi_hutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-1"); 
		$tanggal_end = date("Y-m-t"); 
		$toko_id = 1;
		$supplier_id = '';

		if ($this->input->get('tanggal_start')) {
			// $tanggal = strto($this->input->get('tanggal'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		
			// echo $tanggal;
			$toko_id = $this->input->get('toko_id');
		}

		$data = array(
			'content' =>'admin/finance/mutasi_hutang_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Mutasi Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end)
			);


		$data['user_id'] = is_user_id();
		$data['mutasi_list'] = $this->fi_model->get_mutasi_hutang($tanggal_start, $tanggal_end, $toko_id);
		// echo $tanggal_start.' '.$tanggal_end;
		foreach ($data['mutasi_list'] as $row) {
			$data['bayar_list'][$row->supplier_id] = $this->fi_model->get_mutasi_hutang_bayar($row->supplier_id, $toko_id, $tanggal_start, $tanggal_end);
			// echo $data['bayar_list'][$row->supplier_id].'<br/>';
			// echo $row->supplier_id.'<br/>';
		}
		// $data['mutasi_list'] = $this->fi_model->get_mutasi_hutang_list($supplier_id, $tanggal_start, $tanggal_end); 
		$this->load->view('admin/template',$data);
	}

	function mutasi_hutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal = date("Y-m-01"); 
		$tanggal_end = date("Y-m-t");
		$toko_id = $this->input->get('toko_id');
		$supplier_id = $this->input->get('supplier_id');

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '' ) {
			$tanggal = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');
		}

		$supplier = $this->common_model->db_select('nd_supplier where id = '.$supplier_id);
		foreach ($supplier as $row) {
			$nama_supplier = $row->nama;
		}

		$data = array(
			'content' =>'admin/finance/mutasi_hutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Kartu Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'supplier_id' => $supplier_id,
			'nama_supplier' => $nama_supplier,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal),
			'tanggal_end' => is_reverse_date($tanggal_end)
			);

		$data['user_id'] = is_user_id();
		if ($supplier_id != '') {
			$data['saldo_awal_list'] = $this->fi_model->get_mutasi_hutang_detail_saldo_awal($supplier_id, $toko_id, $tanggal); 
			$data['mutasi_list'] = $this->fi_model->get_mutasi_hutang_list_detail($supplier_id, $toko_id, $tanggal, $tanggal_end); 
		}else{
			$data['saldo_awal_list'] = array();
			$data['mutasi_list'] = array();
		}
		// echo $tanggal.'<br>';
		// echo $tanggal_end.'<br>';
		
		// print_r($this->input->get());
		$this->load->view('admin/template_no_sidebar',$data);
	}

	function mutasi_hutang_excel(){
		
		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));

		$tanggal_print_start = date('d F Y',strtotime($this->input->get('tanggal_start') ));
		$tanggal_print_end = date('d F Y',strtotime($this->input->get('tanggal_end') ));

		$bulan = date('F Y',strtotime($this->input->get('tanggal_start') ));
		$toko_id = $this->input->get('toko_id');

		$mutasi_list = $this->fi_model->get_mutasi_hutang($tanggal_start, $tanggal_end, $toko_id);
		foreach ($mutasi_list as $row) {
			$bayar_list[$row->supplier_id] = $this->fi_model->get_mutasi_hutang_bayar($row->supplier_id, $toko_id, $tanggal_start, $tanggal_end);
		}
		
		$this->load->library('Excel/PHPExcel');
		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:F2");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Mutasi Hutang ');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Bulan : '.$bulan);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		$objPHPExcel->getActiveSheet()->mergeCells("D4:D5");
		$objPHPExcel->getActiveSheet()->mergeCells("E4:G4");
		$objPHPExcel->getActiveSheet()->mergeCells("H4:H5");
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Supplier')
		->setCellValue('C4', 'Saldo Awal')
		->setCellValue('D4', 'Pembelian')
		->setCellValue('E4', 'Pembayaran')
		->setCellValue('E5', 'Transfer')
		->setCellValue('F5', 'Giro Mundur')
		->setCellValue('G5', 'Cash')
		->setCellValue('H4', 'Saldo Akhir')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_list as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_supplier);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->amount - $row->amount_bayar);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->amount_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$bayar1 = 0;
			$bayar2 = 0;
			$bayar3 = 0;
			$total_bayar = 0;
			foreach ($bayar_list[$row->supplier_id] as $row2) {
				$total_bayar += $row2->bayar;
				if ($row2->pembayaran_type_id == 1) {
					$bayar1 = $row2->bayar;
				}

				if ($row2->pembayaran_type_id == 2) {
					$bayar2 = $row2->bayar;
				}
				if ($row2->pembayaran_type_id == 3) {
					$bayar3 = $row2->bayar;
				}
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bayar1);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bayar2);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bayar3);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->amount - $row->amount_bayar + $row->amount_beli - $total_bayar );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$row_no++;
			$idx++;

		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		//ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_hutang ".$bulan.".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//==============================mutasi piutang=================================================
	function mutasi_piutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-1"); 
		$tanggal_end = date("Y-m-t"); 
		$toko_id = 1;
		
		if ($this->input->get('tanggal_start')) {
			// $tanggal = strto($this->input->get('tanggal'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
		}

		$data = array(
			'content' =>'admin/finance/mutasi_piutang_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Mutasi Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end)
			);


		$data['user_id'] = is_user_id();
		$data['mutasi_list'] = $this->fi_model->get_mutasi_piutang($tanggal_start, $tanggal_end, $toko_id); 
		foreach ($data['mutasi_list'] as $row) {
			$data['bayar_list'][$row->customer_id] = $this->fi_model->get_mutasi_piutang_bayar($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
			$data['bayar_list_jual'][$row->customer_id] = $this->fi_model->get_bayar_penjualan($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
			$data['pembayaran_pembulatan'][$row->customer_id] = $this->fi_model->get_pembulatan_piutang($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
		}
		$data['pembayaran_type'] = $this->common_model->db_select('nd_pembayaran_type');
		$this->load->view('admin/template',$data);
	}

	function mutasi_piutang_excel(){
		
		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		
		$tanggal_print_start = date('d F Y',strtotime($this->input->get('tanggal_start') ));
		$tanggal_print_end = date('d F Y',strtotime($this->input->get('tanggal_end') ));
		$bulan = date('F Y',strtotime($this->input->get('tanggal_start') ));
		$toko_id = $this->input->get('toko_id');


		$mutasi_list = $this->fi_model->get_mutasi_piutang($tanggal_start, $tanggal_end, $toko_id); 
		foreach ($mutasi_list as $row) {
			$bayar_list[$row->customer_id] = $this->fi_model->get_mutasi_piutang_bayar($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
			$bayar_list_jual[$row->customer_id] = $this->fi_model->get_bayar_penjualan($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
			$pembayaran_pembulatan[$row->customer_id] = $this->fi_model->get_pembulatan_piutang($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
		
		}
		
		$pembayaran_type = $this->common_model->db_select('nd_pembayaran_type');
	
		$this->load->library('Excel/PHPExcel');
		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:F2");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Mutasi Piutang ');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'tanggal : '.$tanggal_print_start.' sd '.$tanggal_print_end);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		$objPHPExcel->getActiveSheet()->mergeCells("D4:D5");
		$objPHPExcel->getActiveSheet()->mergeCells("E4:J4");
		$objPHPExcel->getActiveSheet()->mergeCells("K4:K5");
		

		$kolom = count($pembayaran_type) - 1;
		

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Customer')
		->setCellValue('C4', 'Saldo Awal')
		->setCellValue('D4', 'Penjualan')
		->setCellValue('E4', 'Pembayaran')
		->setCellValue('E5', 'DP')
		->setCellValue('F5', 'Cash')
		->setCellValue('G5', 'Edc')
		->setCellValue('H5', 'Transfer')
		->setCellValue('I5', 'Giro')
		->setCellValue('J5', 'Pembulatan')
		->setCellValue('K4', 'Saldo Akhir')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_list as $row) {

			$total = 0; $count = 0;

			$coll = "A";

			$total+=$row->amount - $row->amount_bayar + $row->penjualan;

			foreach ($pembayaran_type as $row2) {
				${"bayar".$row2->id} = 0;
			}

			foreach ($bayar_list[$row->customer_id] as $row2) {
				if ($row2->pembayaran_type_id == 1) {
					$bayar2 += $row2->bayar;
					$count++;
				}

				if ($row2->pembayaran_type_id == 2) {
					$bayar6 += $row2->bayar;
					$count++;
				}
				if ($row2->pembayaran_type_id == 3) {
					$bayar4 += $row2->bayar;
					$count++;
				}
			}

			foreach ($bayar_list_jual[$row->customer_id] as $row2) {
				if ($row2->pembayaran_type_id == 1) {
					$bayar1 += $row2->bayar;
				}

				if ($row2->pembayaran_type_id == 2) {
					$bayar2 += $row2->bayar;
				}
				if ($row2->pembayaran_type_id == 3) {
					$bayar3 += $row2->bayar;
				}
				if ($row2->pembayaran_type_id == 4) {
					$bayar4 += $row2->bayar;
				}
				// if ($row2->pembayaran_type_id == 5) {
				// 	$bayar5 += $row2->bayar;
				// }
				if ($row2->pembayaran_type_id == 6) {
					$bayar6 += $row2->amount;
				}
			}

			$total_bayar = 0;
			foreach ($pembayaran_type as $row2) {
				$total_bayar += ${"bayar".$row2->id}; 
			}

			if ($row->amount - $row->amount_bayar > 0 || $row->penjualan != $total_bayar || $count > 0 ) { 
				
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_customer);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;


				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->amount - $row->amount_bayar);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->penjualan);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				foreach ($pembayaran_type as $row2) { 
					if ($row2->id != 5) { 
						$total -= ${"bayar".$row2->id};

						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ${"bayar".$row2->id});
						$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
						$coll++;
					}	
				}

				
				foreach ($pembayaran_pembulatan[$row->customer_id] as $row2) {
					$total -= $row2->pembulatan;

					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row2->pembulatan);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
					$coll++;

				}
				// if ($pembayaran_pembulatan[$row->customer_id])) {
				// }else{
				// }


				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$total );
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				$row_no++;
				$idx++;
				
			}
			

		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		//ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_piutang ".$tanggal_print_start.".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function mutasi_piutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-01"); 
		$tanggal_end = date("Y-m-t");
		$toko_id = '';
		$customer_id = '';

		if ($this->input->get('tanggal_start')) {
			// $tanggal = is_date_formatter($this->input->get('tanggal'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');

		}

		$data = array(
			'content' =>'admin/finance/mutasi_piutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Kartu Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'customer_id' => $customer_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end)
			);


		$data['user_id'] = is_user_id();
		if ($customer_id != '') {
			$data['saldo_awal_list'] = $this->fi_model->get_mutasi_piutang_saldo_awal($customer_id, $toko_id, $tanggal_start); 
			$data['mutasi_list'] = $this->fi_model->get_mutasi_piutang_list_detail($customer_id, $toko_id, $tanggal_start, $tanggal_end); 
		}else{
			$data['saldo_awal_list'] = array();
			$data['mutasi_list'] = array();
		}
		// echo $tanggal.'<br>';
		// echo $tanggal_end.'<br>';
		$this->load->view('admin/template_no_sidebar',$data);
	}

	function mutasi_piutang_list_detail_excel(){

		
		$tanggal_start = $this->input->get('tanggal_start');
		$tanggal_end = $this->input->get('tanggal_end');
		$toko_id = $this->input->get('toko_id');
		$customer_id = $this->input->get('customer_id');

		$customer_data = $this->common_model->db_select("nd_customer where id = ".$customer_id);
		foreach ($customer_data as $row) {
			$nama_customer = $row->nama;
		}
		$saldo_awal_list = $this->fi_model->get_mutasi_piutang_saldo_awal($customer_id, $toko_id, $tanggal_start); 
		$mutasi_list = $this->fi_model->get_mutasi_piutang_list_detail($customer_id, $toko_id, $tanggal_start, $tanggal_end); 
		
		
		$this->load->library('Excel/PHPExcel');
		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:F2");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Kartu Piutang '.$nama_customer);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'tanggal : '.date('d F Y', strtotime($tanggal_start)).' sd '.date('d F Y', strtotime($tanggal_end)));

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		$objPHPExcel->getActiveSheet()->mergeCells("D4:E4");
		$objPHPExcel->getActiveSheet()->mergeCells("F4:F5");
		
		

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Tanggal')
		->setCellValue('C4', 'Keterangan')
		->setCellValue('D4', 'Mutasi')
		->setCellValue('F4', 'Saldo')
		->setCellValue('D5', 'Total Bon')
		->setCellValue('E5', 'Pembayaran')
		;
	

		$row_no = 6;
		$idx = 1;
		$saldo = 0;
		foreach ($saldo_awal_list as $row) {

			$total = 0; $count = 0;
			$coll = "A";

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, is_reverse_date($tanggal_start) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "SALDO AWAL" );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$saldo += $row->saldo_awal;
			$objPHPExcel->getActiveSheet()->setCellValue("F".$row_no, $row->saldo_awal );
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
			$coll++;

			$row_no++;
			$idx++;
				
			

		}

		foreach ($mutasi_list as $row) {

			$total = 0; $count = 0;
			$coll = "A";

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, is_reverse_date($row->tanggal) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->no_faktur );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$coll++;

			$saldo += $row->amount_jual;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ($row->amount_jual == 0 ? '' : $row->amount_jual) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$saldo -= $row->amount_bayar;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ($row->amount_bayar == 0 ? '' : $row->amount_bayar) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $saldo );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$row_no++;
			$idx++;
				
			

		}


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		//ob_end_clean();

		$cek =array(',','.');
		$nama_customer = str_replace($cek, '', $nama_customer);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=kartu_piutang_".$nama_customer."_".date('dmY', strtotime($tanggal_start))."sd".date('dmY', strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}


}