<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_opname extends CI_Controller {

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

//===================================stok opname===============================================

	function table_stok_opname(){
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_roll ";
		}
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list($select, date('Y-m-d'));
		$data['content'] = 'admin/inventory/stok_opname';
		$this->load->view('admin/template_no_sidebar',$data);
		// $this->load->view('admin/inventory/stok_opname',$data);
	}



//================================penyesuaian stok ==================================================

	function penyesuaian_stok_insert(){
		$ini = $this->input;
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$data = array(
			'keterangan' => $ini->post('keterangan'),
			'barang_id' => $ini->post('barang_id'),
			'warna_id' => $ini->post('warna_id'),
			'gudang_id' => $ini->post('gudang_id'),
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'tipe_transaksi' => $ini->post('tipe_transaksi'),
			'qty' => $ini->post('qty'),
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'user_id' => is_user_id() );

		$this->common_model->db_insert('nd_penyesuaian_stok', $data);
		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);
	}

	function penyesuaian_stok_update(){
		$ini = $this->input;
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$id = $ini->post('penyesuaian_stok_id');
		$data = array(
			'keterangan' => $ini->post('keterangan'),
			'tipe_transaksi' => $ini->post('tipe_transaksi'),
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'qty' => $ini->post('qty'),
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'user_id' => is_user_id() );

		$this->common_model->db_update('nd_penyesuaian_stok', $data,'id', $id);
		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);
	}

	function penyesuaian_stok_remove(){
		$ini = $this->input;
		$id = $this->input->post('penyesuaian_stok_id');
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$this->common_model->db_delete('nd_penyesuaian_stok','id',$id);
		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);
	}

//=================================input stok opname=================================

	function stok_opname(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/inventory/stok_opname',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Stok Opname',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			);

		$data['stok_opname_list'] = $this->common_model->db_select('nd_stok_opname');
		$this->load->view('admin/template',$data);
	}

	function stok_opname_insert(){
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'user_id' => is_user_id(), );
		$result_id = $this->common_model->db_insert('nd_stok_opname',$data);
		redirect(is_setting_link('inventory/stok_opname_detail').'?id='.$result_id);
	}

	function stok_opname_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');
		$cond_barang = '';
		$barang_id_filter = '';
		$status_aktif = 1;

		if ($this->input->get('barang_id_filter')) {
			$cond_barang = "AND t1.barang_id =".$this->input->get('barang_id_filter');
			$barang_id_filter = $this->input->get('barang_id_filter');
		}
		$stok_opname_data = $this->common_model->db_select('nd_stok_opname where id='.$id);
		foreach ($stok_opname_data as $row) {
			$tanggal_so = $row->tanggal;
			$status_aktif = $row->status_aktif;
		}
		$data = array(
			'content' =>'admin/inventory/stok_opname_detail',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Daftar Barang Stok Opname',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_opname_data' => $stok_opname_data,
			'stok_opname_id' => $id,
			'barang_id_filter' => $barang_id_filter,
			'tanggal_so' => $tanggal_so,
			'status_aktif' => $status_aktif,
			'id' => $id
		);	

		if ($this->session->flashdata('data_double')) {
			$data["data_double"] = "benar";
		}else{
			$data["data_double"] = "tidak";
		}
		$select = '';
		$select_before = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty,0),0))  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll, 0 )  )  as gudang_".$row->id."_roll ";
			$select_before .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_beforeqty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_beforeroll ";
		}

		$tanggal_awal = '2018-01-01';
		$get_stok_opname_before = $this->common_model->db_select("nd_stok_opname where tanggal <'".$tanggal_so."' ORDER BY tanggal desc LIMIT 1");
		foreach ($get_stok_opname_before as $row) {
			$tanggal_awal = $row->tanggal;
		}

		$data['nama_stok_barang'] = $this->inv_model->get_nama_stok_barang();
		$data['stok_opname_detail'] = $this->inv_model->get_stok_opname_detail($id, $select, $select_before, $tanggal_awal, $tanggal_so, $cond_barang);
		$this->load->view('admin/template',$data);
	}

	function stok_opname_lock(){
		$id = $this->input->post('id');
		$data = array(
			'status_aktif' => 1,
			'locked_by' => is_user_id(),
			'locked_date' => date('Y-m-d H:i:s') );
		$this->common_model->db_update("nd_stok_opname",$data,'id', $id);
		echo $id;
	}

	function get_data_stok_opname_detail(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$tanggal = $this->input->post('tanggal');

		$result['result'] = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id");
		$result['resultEcer'] = $this->common_model->db_select("nd_stok_opname_eceran where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id");
		$data = array();
		$dataEcer = array();
		foreach ($this->gudang_list_aktif as $row) {
			$get_stok_opname = $this->common_model->get_latest_so_before($tanggal, $barang_id, $warna_id, $row->id);
			$tanggal_awal = '2018-01-01';
			$stok_opname_id_before = 0;
			foreach ($get_stok_opname as $row2) {
				$tanggal_awal = $row2->tanggal;
				$stok_opname_id_before = $row2->stok_opname_id;
			}
			// echo $row->id,' ',$tanggal_awal,' ', $stok_opname_id_before.'<br/>';
			array_push($data, $this->inv_model->cek_barang_qty($row->id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id_before, $tanggal));

			$get_stok_opname = $this->common_model->get_latest_so_eceran_before($tanggal, $barang_id, $warna_id, $row->id);
			$tanggal_awal = '2018-01-01';
			$stok_opname_id_before = 0;
			foreach ($get_stok_opname as $row2) {
				$tanggal_awal = $row2->tanggal;
				$stok_opname_id_before = $row2->stok_opname_id;
			}
			// echo $row->id.','. $barang_id.','.$warna_id.','. $tanggal_awal.','. $stok_opname_id_before."\n";
			array_push($dataEcer, $this->inv_model->cek_total_barang_qty_eceran($row->id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id_before, $tanggal));
			// print_r($this->inv_model->cek_barang_qty_eceran($row->id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id));
		}
		$result['data'] = $data;
		$result['dataEcer'] = $dataEcer;
		echo json_encode($result);
	}

	function update_stok_opname_tanggal(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')) );

		$this->common_model->db_update('nd_stok_opname',$data,'id',$stok_opname_id);
		echo "OK";

	}

	function update_stok_opname_barang(){
		$ini = $this->input;
		$stok_opname_id = $ini->post('stok_opname_id');
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$data_qty = str_replace(',', '?', $this->input->post('qty'));
		$data_qty = str_replace('.', '', $data_qty);
		$qty = str_replace('?', '.', $data_qty);

		$data = array(
			'gudang_id' => $gudang_id ,
			'warna_id' => $warna_id,
			'barang_id' => $barang_id,
			'qty' => $qty,
			'stok_opname_id' => $stok_opname_id,
			'jumlah_roll' => $this->input->post('jumlah_roll')
			);

		$id = '';
		$getData = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id = ".$stok_opname_id." AND barang_id = ".$barang_id." AND warna_id =".$warna_id." AND gudang_id=".$gudang_id);
		foreach ($getData as $row) {
			$id = $row->id;
		}

		if ($id == '') {
			$this->common_model->db_insert('nd_stok_opname_detail', $data);
		}else{
			$this->common_model->db_update('nd_stok_opname_detail', $data,'id',$id);
		}

		echo "OK";
	}

	function stok_opname_detail_insert(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$gudang_id = $this->input->post('gudang_id');
		$rekap = explode('--', $this->input->post('rekap_qty'));
		foreach ($rekap as $key => $value) {
			$dt = explode('??', $value);
			if (isset($dt[2]) && $dt[2] == 0) {
				if ($dt[0] != '' && $dt[0] != null) {
					$data[$key] = array(
						'stok_opname_id' => $stok_opname_id ,
						'barang_id' => $barang_id,
						'warna_id' => $warna_id,
						'gudang_id' => $gudang_id,
						'qty' => $dt[0],
						'jumlah_roll' => $dt[1]
						);
				}
			}else if(isset($dt[2]) && $dt[2] != 0){
				$data_update = array(
					'stok_opname_id' => $stok_opname_id ,
					'barang_id' => $barang_id,
					'warna_id' => $warna_id,
					'gudang_id' => $gudang_id,
					'qty' => $dt[0],
					'jumlah_roll' => $dt[1]
					);

				if($dt[0] != 0 && $dt[0] != ''){
					$this->common_model->db_update('nd_stok_opname_detail',$data_update,'id', $dt[2]);
				}else{
					$this->common_model->db_delete('nd_stok_opname_detail','id', $dt[2]);
				}
			}
		}

		if (isset($data)) {
			$this->common_model->db_insert_batch('nd_stok_opname_detail', $data);
		}

		$result = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id and gudang_id=$gudang_id");

		// print_r($data);
		echo json_encode($result);
	}

	function stok_opname_detail_ecer(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$gudang_id = $this->input->post('gudang_id');
		$rekap = explode('--', $this->input->post('rekap_qty'));
		foreach ($rekap as $key => $value) {
			$dt = explode('??', $value);
			if (isset($dt[2]) && $dt[2] == 0) {
				if ($dt[0] != '' && $dt[0] != null) {
					$data[$key] = array(
						'stok_opname_id' => $stok_opname_id ,
						'barang_id' => $barang_id,
						'warna_id' => $warna_id,
						'gudang_id' => $gudang_id,
						'qty' => $dt[0],
						'jumlah_roll' => $dt[1]
						);
				}
			}else if(isset($dt[2]) && $dt[2] != 0){
				$data_update = array(
					'stok_opname_id' => $stok_opname_id ,
					'barang_id' => $barang_id,
					'warna_id' => $warna_id,
					'gudang_id' => $gudang_id,
					'qty' => $dt[0],
					'jumlah_roll' => $dt[1]
					);

				if($dt[0] != 0 && $dt[0] != ''){
					$this->common_model->db_update('nd_stok_opname_eceran',$data_update,'id', $dt[2]);
				}else{
					$this->common_model->db_delete('nd_stok_opname_eceran','id', $dt[2]);
				}
			}
		}

		if (isset($data)) {
			$this->common_model->db_insert_batch('nd_stok_opname_eceran', $data);
		}

		$result = $this->common_model->db_select("nd_stok_opname_eceran where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id and gudang_id=$gudang_id");

		// print_r($data);
		echo json_encode($result);
	}


//==========================================================

	function upload_file()
	{
		// echo base_url()."uploads/";
		if(!empty($_FILES)){

			$fileName = $_FILES['file']['name'];
			
			$config['upload_path'] = './uploads/';	
			$config['allowed_types'] = 'csv';
			$config['file_name'] = $fileName;
			// echo $fileName;
			$this->load->library('upload',$config);
			//$this->upload->initialize($config);
			if(!$this->upload->do_upload('file')){
				$error = array('eror' => $this->upload->display_errors());
				print_r($error);
			}else{
				$data = array('upload_data' => $this->upload->data() );
				// redirect('stok/stok_opname/insert_file_mutasi?nama='.$data['upload_data']['file_name']);
				redirect('stok/stok_opname/show_file?nama='.$data['upload_data']['file_name']);
			}

		}else{
			echo 'kosoong ngagorolong';
		}
	}

	function show_file(){
		
		// $fileName = "Laporan_Faktur_Pajak_CV._PELITA_LESTARI_110122_.csv";
		$fileName = $this->input->get('nama');
		$target_file = "./uploads/".$fileName;

		$data = array(
			'content' =>'admin/inventory/show_file_upload',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Upload Stok Opname',
			'nama_file' => $fileName,
			'common_data'=> $this->data,);

			$data_file = array();
			$data_pertama = array();
			// $file = fopen($target_file,"r");
			// print_r(fgetcsv($file,5000, ";"));
	
			$row = 1;
			$isTable = false;
			if (($handle = fopen($target_file, "r")) !== FALSE) {
				$idx = 0;
				$idx_detail = 1;
				$gudangList = [];
				$data_ins = array();
				$detail_ins = array();
				while (($data_so = fgetcsv($handle, 5000, ";",",")) !== FALSE) {
					if ($idx  >= 0) {
						# code...

						if ($idx==0) {
							$data_pertama = $data_so;
						}
						$num = count($data_so);
						// $tgl = date('Y-m-d', strtotime($data_so[1]));
						// $row++;
						$data_file[$idx] = $data_so;
						
						// $data_so_ins[$idx] = array(
						// 	'id' => $idx,
						// 	'tanggal' => $tgl,
						// 	'toko' => $data_so[2],
						// 	'gudang' => $data_so[3],
						// 	'nama_toko' => $data_so[4],
						// 	'barang' => $data_so[5],
						// 	'keterangan' => $data_so[6],
						// 	'supplier' => $data_so[7],
						// 	'qty_besar' => $data_so[8],
						// 	'nama_satuan_besar' => $data_so[9],
						// 	'qty_kecil' => $data_so[10],
						// 	'nama_satuan_kecil' => $data_so[11],
						// 	'qty_eceran' => $data_so[12],
						// 	'nama_satuan_eceran' => $data_so[13],
						// 	'catatan' => $data_so[14]
						// );
						
						// for ($m=15; $m < $num ; $m++) { 
						// 	if ($data_so[$m] != '') {
						// 		$detail_ins[$idx_detail] = array(
						// 			'id' => $idx_detail,
						// 			'so_upload_id' => $idx,
						// 			'qty' => $data_so[$m]
						// 		);
						// 		$idx_detail++;
						// 	}
						// }
					}
					$idx++;
				}
				fclose($handle);
			}
			// print_r($data_file);
		$data['file_show'] = $data_file;
		$data['data_pertama'] = $data_pertama;
		$this->load->view('admin/template',$data);

		
	}
	
	function insert_file_mutasi(){

		// $fileName = "mutasi_perdesember2020.csv";

		/* CREATE TABLE nd_so_upload(
			id int AUTO_INCREMENT PRIMARY KEY,
			tanggal date,
			toko varchar(100) DEFAULT NULL,
			gudang varchar(100) DEFAULT NULL,
			nama_toko varchar(100) DEFAULT NULL,
			barang varchar(100) DEFAULT NULL,
			keterangan varchar(100) DEFAULT NULL,
			supplier varchar(100) DEFAULT NULL,
			qty_besar decimal(15,2) DEFAULT NULL,
			nama_satuan_besar varchar(20) DEFAULT NULL,
			qty_kecil decimal(15,2) DEFAULT NULL,
			nama_satuan_kecil varchar(20) DEFAULT NULL,
			qty_eceran decimal(10,2) DEFAULT NULL,
			nama_satuan_eceran varchar(20) DEFAULT NULL,
			catatan varchar(200)  DEFAULT NULL
		) 

		CREATE TABLE nd_so_upload_rincian_qty( 
			id int AUTO_INCREMENT PRIMARY KEY, 
			so_upload_id int, 
			qty decimal(10,2) 
		)
		*/

		// print_r($this->input->post());
		$fileName = $this->input->post('nama_file');
		$is_baris_header = $this->input->post('is_baris_header');

		$idx_tanggal = $this->input->post('tanggal');
		$idx_toko = $this->input->post('toko');
		$idx_gudang = $this->input->post('gudang');
		$idx_nama_beli = $this->input->post('nama_beli');
		$idx_nama_jual = $this->input->post('nama_jual');
		$idx_keterangan = $this->input->post('nama_keterangan');
		
		$idx_harga_beli = $this->input->post('harga_beli');
		$idx_harga_jual = $this->input->post('harga_jual');
		$idx_harga_eceran = $this->input->post('harga_eceran');

		$idx_supplier = $this->input->post('nama_supplier');

		$idx_qty_besar = $this->input->post('qty_besar');
		$idx_sat_besar = $this->input->post('nama_satuan_besar');

		$idx_qty_kecil = $this->input->post('qty_kecil');
		$idx_sat_kecil = $this->input->post('nama_satuan_kecil');

		$idx_qty_ecer = $this->input->post('qty_eceran');
		$idx_sat_ecer = $this->input->post('nama_satuan_eceran');

		$idx_qty_rincian = $this->input->post('rincian_qty_kecil');
		$jumlah_baris = $this->input->post('jumlah_baris');
		
		// kosong table upload
		$this->common_model->db_free_query_superadmin("TRUNCATE TABLE nd_so_upload");
		$this->common_model->db_free_query_superadmin("TRUNCATE TABLE nd_so_upload_rincian_qty");
		// $fileName = "Laporan_Faktur_Pajak_CV._PELITA_LESTARI_110122_.csv";
		// if ($this->input->get('nama') != '') {
		// 	$fileName = $this->input->get('nama');
		// }

		// create history upload
		$data_new = array(
			'user_id' => is_user_id() ,
			'nama_file' => $fileName
		);


		$this->common_model->db_insert("nd_so_upload_file_history", $data_new);
		// echo '<hr/>';
		$target_file = "./uploads/".$fileName;
		// echo $target_file;
		
		// $file = fopen($target_file,"r");
		// print_r(fgetcsv($file,5000, ";"));

		$row = 1;
		$isTable = false;
		$dt_list = array();
		$start_baris = -1;
		if ($is_baris_header) {
			$start_baris = 0;
		}

		if (($handle = fopen($target_file, "r")) !== FALSE) {
			
			$idx = 0;
			$id_generate = 1;
			$idx_detail = 1;
			$gudangList = [];
			$data_ins = array();
			$detail_ins = array();
			while (($data = fgetcsv($handle, 5000, ";",",")) !== FALSE) {
				if ($idx  > $start_baris && $idx < $jumlah_baris  ) {
					# code...
					$num = count($data);
					// echo "<p> $num fields in line $row: <br /></p>\n";
					$tgl = date('Y-m-d', strtotime($data[1]));
					$row++;
					
					$data_ins[$idx] = array(
						'id' => $id_generate,
						'tanggal' => $tgl,
						'toko' => ($idx_toko != '' ? $data[$idx_toko] : ''),
						'gudang' => ($idx_gudang != '' ? $data[$idx_gudang] : ''),
						'nama_beli' => ($idx_nama_beli != '' ? $data[$idx_nama_beli] : ''),
						'nama_jual' => ($idx_nama_jual != '' ? $data[$idx_nama_jual] : ''),
						'keterangan' => ($idx_keterangan != '' ? $data[$idx_keterangan] : ''),
						'supplier' => ($idx_supplier != '' ? $data[$idx_supplier] : ''),
						'qty_besar' => ($idx_qty_besar != '' ? $data[$idx_qty_besar] : ''),
						'nama_satuan_besar' => ($idx_sat_besar != '' ? $data[$idx_sat_besar] : ''),
						'qty_kecil' => ($idx_qty_kecil != '' ? $data[$idx_qty_kecil] : ''),
						'nama_satuan_kecil' => ($idx_sat_kecil != '' ? $data[$idx_sat_kecil] : ''),
						'qty_eceran' => ($idx_qty_ecer != '' ? $data[$idx_qty_ecer] : ''),
						'nama_satuan_eceran' => ($idx_sat_ecer != '' ? $data[$idx_sat_ecer] : '')
					);
					
					if ($idx_qty_rincian != '') {
						for ($m=$idx_qty_rincian; $m < $num ; $m++) { 
							if ($data[$m] != '') {
								$detail_ins[$idx_detail] = array(
									'id' => $idx_detail,
									'so_upload_id' => $idx,
									'qty' => $data[$m]
								);
								$idx_detail++;
							}
						}
					}
					$id_generate++;
				}
				$idx++;
			}

			// print_r($data_ins);

			$this->common_model->db_insert_batch("nd_so_upload", $data_ins);
			$this->common_model->db_insert_batch("nd_so_upload_rincian_qty", $detail_ins);
			fclose($handle);
		}

		redirect('stok/stok_opname/show_file_uploaded');
	}

	function insert_file_mutasi_new(){

		// print_r($this->input->post());

		$fileName = $this->input->post('nama_file');
		$is_baris_header = $this->input->post('is_baris_header');

		$idx_tanggal = $this->input->post('tanggal');
		$idx_toko = $this->input->post('toko');
		$idx_gudang = $this->input->post('gudang');
		$idx_nama_beli = $this->input->post('nama_beli');
		$idx_nama_jual = $this->input->post('nama_jual');
		$idx_keterangan = $this->input->post('nama_keterangan');
		
		$idx_harga_beli = $this->input->post('harga_beli');
		$idx_harga_jual = $this->input->post('harga_jual');
		$idx_harga_eceran = $this->input->post('harga_eceran');

		$idx_supplier = $this->input->post('nama_supplier');

		$idx_qty_besar = $this->input->post('qty_besar');
		$idx_sat_besar = $this->input->post('nama_satuan_besar');

		$idx_qty_kecil = $this->input->post('qty_kecil');
		$idx_sat_kecil = $this->input->post('nama_satuan_kecil');

		$idx_qty_ecer = $this->input->post('qty_eceran');
		$idx_sat_ecer = $this->input->post('nama_satuan_eceran');

		$idx_qty_rincian = $this->input->post('rincian_qty_kecil');
		$jumlah_baris = $this->input->post('jumlah_baris');
		
		// kosong table upload
		$this->common_model->db_free_query_superadmin("TRUNCATE TABLE nd_so_upload");
		$this->common_model->db_free_query_superadmin("TRUNCATE TABLE nd_so_upload_rincian_qty");
		// $fileName = "Laporan_Faktur_Pajak_CV._PELITA_LESTARI_110122_.csv";
		// if ($this->input->get('nama') != '') {
		// 	$fileName = $this->input->get('nama');
		// }

		// create history upload
		$data_new = array(
			'user_id' => is_user_id() ,
			'nama_file' => $fileName
		);


		$this->common_model->db_insert("nd_so_upload_file_history", $data_new);
		// echo '<hr/>';
		$target_file = "./uploads/".$fileName;
		// echo $target_file;
		
		// $file = fopen($target_file,"r");
		// print_r(fgetcsv($file,5000, ";"));

		$row = 1;
		$isTable = false;
		$dt_list = array();
		$start_baris = -1;
		if ($is_baris_header) {
			$start_baris = 0;
		}

		if (($handle = fopen($target_file, "r")) !== FALSE) {
			
			$idx = 0;
			$id_generate = 1;
			$idx_detail = 1;
			$gudangList = [];
			$data_ins = array();
			$detail_ins = array();
			while (($data = fgetcsv($handle, 5000, ";",",")) !== FALSE) {
				if ($idx  > $start_baris && $idx < $jumlah_baris  ) {
					# code...
					$num = count($data);
					// echo "<p> $num fields in line $row: <br /></p>\n";
					$tgl = date('Y-m-d', strtotime($data[1]));
					$row++;
					
					$data_ins[$idx] = array(
						'id' => $id_generate,
						'tanggal' => $tgl,
						'toko' => ($idx_toko != '' ? $data[$idx_toko] : ''),
						'gudang' => ($idx_gudang != '' ? $data[$idx_gudang] : ''),
						'nama_beli' => ($idx_nama_beli != '' ? $data[$idx_nama_beli] : ''),
						'nama_jual' => ($idx_nama_jual != '' ? $data[$idx_nama_jual] : ''),
						'keterangan' => ($idx_keterangan != '' ? $data[$idx_keterangan] : ''),
						'supplier' => ($idx_supplier != '' ? $data[$idx_supplier] : ''),
						'qty_besar' => ($idx_qty_besar != '' ? $data[$idx_qty_besar] : ''),
						'nama_satuan_besar' => ($idx_sat_besar != '' ? $data[$idx_sat_besar] : ''),
						'qty_kecil' => ($idx_qty_kecil != '' ? $data[$idx_qty_kecil] : ''),
						'nama_satuan_kecil' => ($idx_sat_kecil != '' ? $data[$idx_sat_kecil] : ''),
						'qty_eceran' => ($idx_qty_ecer != '' ? $data[$idx_qty_ecer] : ''),
						'nama_satuan_eceran' => ($idx_sat_ecer != '' ? $data[$idx_sat_ecer] : '')
					);
					
					if ($idx_qty_rincian != '') {
						for ($m=$idx_qty_rincian; $m < $num ; $m++) { 
							if ($data[$m] != '') {
								$detail_ins[$idx_detail] = array(
									'id' => $idx_detail,
									'so_upload_id' => $idx,
									'qty' => $data[$m]
								);
								$idx_detail++;
							}
						}
					}
					$id_generate++;
				}
				$idx++;
			}

			// print_r($data_ins);


			$this->common_model->db_insert_batch("nd_so_upload", $data_ins);

			if (count($detail_ins) > 0) {
				$this->common_model->db_insert_batch("nd_so_upload_rincian_qty", $detail_ins);
			}
			fclose($handle);
		}

		redirect('stok/stok_opname/show_file_uploaded');
	}

	function show_file_uploaded()
	{

		$data = array(
			'content' =>'admin/inventory/stok_opname_upload_result',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Data Upload',
			'common_data'=> $this->data,
		);

		
		$data['data_file'] = $this->common_model->db_select("nd_so_upload_file_history order by id desc LIMIT 1");
		$data['data_so'] = $this->common_model->db_select("nd_so_upload");
		$this->load->view('admin/template',$data);
	}

	function cek_toko()
	{
		$get = $this->so_model->so_get_new_toko();
		if ($this->input->post('tipe') == 1) {
			echo json_encode( $get->result() );
		}else{
			echo $get->num_rows();
		}
		
	}

	function cek_gudang()
	{
		$get = $this->so_model->so_get_new_gudang();
		if ($this->input->post('tipe') == 1) {
			echo json_encode( $get->result() );
		}else{
			echo $get->num_rows();
		}
		
	}

	function cek_barang()
	{
		$get = $this->so_model->so_get_new_barang();
		if ($this->input->post('tipe') == 1) {
			echo json_encode( $get->result() );
		}else{
			echo $get->num_rows();
		}
	}

	function cek_keterangan()
	{
		$get = $this->so_model->so_get_new_keterangan();
		if ($this->input->post('tipe') == 1) {
			echo json_encode( $get->result() );
		}else{
			echo $get->num_rows();
		}
		
	}

	function cek_supplier()
	{
		$get = $this->so_model->so_get_new_supplier();
		if ($this->input->post('tipe') == 1) {
			echo json_encode( $get->result() );
		}else{
			echo $get->num_rows();
		}
		
	}

	function cek_satuan()
	{
		$get = $this->so_model->so_get_new_satuan();
		if ($this->input->post('tipe') == 1) {
			echo json_encode( $get->result() );
		}else{
			echo $get->num_rows();
		}
		
	}

//====================================generate file so=====================================

	function stok_barang_detail_excel(){
		$tanggal = is_date_formatter($this->input->get('tanggal'));
		if ($tanggal == '') {
			$tanggal = date('Y-m-d');
		}
		$tanggal_awal = '2019-01-01';
		// $data_get = $this->so_model->get_stok_barang_all_detail($tanggal, $tanggal_awal);

		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", ROUND(SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) ),3)  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_roll ";
		}

		$select_update = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select_update .= ", 
				ROUND(SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), 
					if(tbl_a.gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ),0 )) - 
				SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), 
					if(tbl_a.gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ),0 )),3)  as gudang_".$row->id."_qty , SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), if(tbl_a.gudang_id=".$row->id.", jumlah_roll_masuk, 0 ),0 )) - SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), if(tbl_a.gudang_id=".$row->id.", jumlah_roll_keluar, 0 ),0 ))  as gudang_".$row->id."_roll ";
		}

		$select = ", ROUND(SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), 
		ifnull(qty_masuk,0),0 )) - 
		SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), 
		ifnull(qty_keluar,0),0 )),3)  as qty , 
		SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), jumlah_roll_masuk,0 )) -
		SUM( if(tanggal >= ifnull(tanggal_stok,'$tanggal_awal'), jumlah_roll_keluar,0 ))  as roll ";

		$data['data_get'] = $this->so_model->get_stok_barang_list_2($select, $tanggal, $tanggal_awal);
		$data['stok_barang_eceran'] = $this->so_model->get_stok_barang_eceran_list($tanggal);
		
		// print_r($data_get);
		// $data['data_get'] = $data_get;
		$data['tanggal'] = $tanggal;
		// print_r($data['stok_barang_eceran']);
		// echo $tanggal, $tanggal_awal;
		// echo $select_update.'<hr/>'. $tanggal.'<hr/>'. $tanggal_awal;
		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");
		$this->load->view('admin/inventory/so_export',$data);
		// $this->output->enable_profiler(TRUE);




	}

//====================================insert barang=====================================


	function insert_file_barang()
	{
		// print_r($this->input->post());
		$nama_beli = $this->input->post('nama_beli');
		$nama_jual = $this->input->post('nama_jual');
		$harga_beli = $this->input->post('harga_beli');
		$harga_jual = $this->input->post('harga_jual');

		$data = array();
		foreach ($this->input->post() as $k => $v) {
			$data = array(
				'nama_beli' => $s->post('nama_beli')[$k] , );
		}

	}


}