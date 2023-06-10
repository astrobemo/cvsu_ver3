<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {

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
		redirect('admin/dashboard');
	}

//======================================stok barang============================================

	function stok_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal) );

		$tanggal_awal = '2019-01-01';

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
		// echo $select.'<br>';
		// echo $tanggal_awal;
		// $data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");
		// $data['stok_barang'] = $this->inv_model->get_stok_barang_list($select, $tanggal, $tanggal_awal);
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_2($select_update, $tanggal, $tanggal_awal);
		$data['stok_barang_eceran'] = $this->inv_model->get_stok_barang_eceran_list($tanggal);
		// echo $data['stok_barang'];

		if (is_posisi_id()==1) {
			# code...
			// print_r($data['stok_barang_eceran']);
		}else{
		}
		$this->load->view('admin/template',$data);
	}

	function stok_barang_rekap(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang_rekap',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal) );


		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_roll ";
		}
		// echo $select.'<br>';
		// $data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_rekap($select, $tanggal); 
		// echo $data['stok_barang'];
		$this->load->view('admin/template',$data);
	}

	function data_barang_excel(){
		$tanggal_awal = '2019-01-01';
		$tanggal = date('Y-m-d');

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
		
		$data['data_barang'] = $this->inv_model->get_stok_barang_list_2($select_update, $tanggal, $tanggal_awal);
		
		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		$this->load->view('admin/inventory/data_inventory_barang_excel', $data);


	}

	function data_barang_excel_so(){
		$tanggal_awal = '2019-01-01';
		$tanggal = date('Y-m-d');
		$gudang_id = 1;

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
		
		$data['data_barang'] = $this->inv_model->get_stok_barang_list_2($select_update, $tanggal, $tanggal_awal);
		$data['gudang_id'] = $gudang_id;
		
		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		$this->load->view('admin/inventory/data_inventory_barang_excel_detail', $data);


	}

	
	function stok_barang_excel(){

		$tanggal = is_date_formatter($this->input->get('tanggal'));
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_roll ";
		}
		
		$stok_barang = $this->inv_model->get_stok_barang_list($select, $tanggal);
		$gudang_list = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");

		
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

		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		

		$coll = 'D'; $coll_next = 'E';
		foreach ($this->gudang_list_aktif as $row) {
			$objPHPExcel->getActiveSheet()->mergeCells($coll."4:".$coll_next."4");
			$objPHPExcel->getActiveSheet()->setCellValue($coll.'4',$row->nama);
			$objPHPExcel->getActiveSheet()->setCellValue($coll.'5','Yard/Kg');
			$objPHPExcel->getActiveSheet()->setCellValue($coll_next.'5','Jumlah Roll');
			$objPHPExcel->getActiveSheet()->getStyle($coll."4:".$coll_next."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$coll++;
			$coll_next++;
			$coll++;
			$coll_next++;
		}

		
		$objPHPExcel->getActiveSheet()->mergeCells($coll."4:".$coll_next."4");
		$objPHPExcel->getActiveSheet()->setCellValue($coll.'4',"TOTAL");
		$objPHPExcel->getActiveSheet()->getStyle($coll."4:".$coll_next."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue($coll.'5','Yard/Kg');
		$objPHPExcel->getActiveSheet()->setCellValue($coll_next.'5','Jumlah Roll');
		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:".$coll_next."1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:".$coll_next."2");

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' STOK BARANG ')
		->setCellValue('A2', ' Tanggal '.is_reverse_date($tanggal))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Beli')
		->setCellValue('C4', 'Nama Jual')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($stok_barang as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang.' '.$row->nama_warna);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang_jual.' '.$row->nama_warna_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$subtotal_qty = 0;
			$subtotal_roll = 0;
			foreach ($gudang_list as $isi) { 

				$qty = $isi->nama.'_qty';
				$roll = $isi->nama.'_roll';
				$subtotal_qty += $row->$qty;
				$subtotal_roll += $row->$roll;
				
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, number_format($row->$qty,'2','.',''));
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->$roll);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_qty);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_roll);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$row_no++;
			$idx++;

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();	


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Stok_Barang_".date("dmY",strtotime($tanggal)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function stok_barang_detail_excel(){
		$tanggal = is_date_formatter($this->input->get('tanggal'));
		if ($tanggal == '') {
			$tanggal = date('Y-m-d');
		}
		$tanggal_awal = '2019-01-01';
		$data_get = $this->inv_model->get_stok_barang_all_detail($tanggal, $tanggal_awal);
		// print_r($data_get);
		$data['tanggal'] = $tanggal;
		$data['data_get'] = $data_get;

		$this->load->view('admin/inventory/so_testing',$data);



	}

	function kartu_stok(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$gudang_id = $this->uri->segment(2);
		$barang_id = $this->uri->segment(3);
		$warna_id = $this->uri->segment(4);
		$tanggal_start = date("Y-m-1");
		$tanggal_end = date("Y-m-t");

		$barang = $this->common_model->get_data_barang($barang_id);
		foreach ($barang as $row) {
			$nama_beli = $row->nama;
			$nama_jual = $row->nama_jual;
		}

		$warna = $this->common_model->db_select('nd_warna where id='.$warna_id);
		foreach ($warna as $row) {
			$warna_beli = $row->warna_beli;
			$warna_jual = $row->warna_jual;
		}

		$gudang = $this->common_model->db_select('nd_gudang where id='.$gudang_id);
		foreach ($gudang as $row) {
			$nama_gudang = $row->nama;
		}

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			if ($this->input->get('tanggal_end') != '') {
				$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			}else{
				$tanggal_start = date("Y-m-1");
				$tanggal_end = date("Y-m-t");

			}
		}

		$data = array(
			'content' =>'admin/inventory/kartu_stok',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Kartu Stok',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'gudang_id' => $gudang_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'nama_gudang' => $nama_gudang,
			'nama_beli' => $nama_beli,
			'nama_jual' => $nama_jual,
			'warna_beli' => $warna_beli,
			'warna_jual' => $warna_jual,
			'barang_data' => $barang );

		
		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->common_model->get_latest_so($tanggal_start, $barang_id, $warna_id, $gudang_id);
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->stok_opname_id;
		}

		$data['stok_barang'] = $this->inv_model->get_stok_barang_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id); 
		$data['stok_awal'] = $this->inv_model->get_stok_barang_satuan_awal($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_awal, $stok_opname_id);

		$tanggal_awal_eceran = '2018-01-01';
		$getOpname = $this->common_model->get_latest_so_eceran($tanggal_start, $barang_id, $warna_id, $gudang_id);
		foreach ($getOpname as $row) {
			$tanggal_awal_eceran = $row->tanggal;
			$stok_opname_id = $row->stok_opname_id;
		}
		// $data['stok_detail'] = $this->inv_model->get_stok_barang_detail($gudang_id, $barang_id, $warna_id, '2019-01-01', $tanggal_end, $tanggal_awal, $stok_opname_id); 
		// $data['stok_detail'] = $this->inv_model->get_stok_barang_detail_2($gudang_id, $barang_id, $warna_id, '2019-01-01', $tanggal_end, $tanggal_awal, $stok_opname_id); 
		$data['stok_detail'] = $this->sg_model->get_stok_barang_detail_2($gudang_id, $barang_id, $warna_id, '2019-01-01', $tanggal_end, $tanggal_awal, $stok_opname_id); 
	

		// $data['stok_detail'] = $this->sg_model->get_stok_barang_detail_eceran($gudang_id, $barang_id, $warna_id, '2019-01-01', $tanggal_end, $tanggal_awal, $stok_opname_id); 
		$data['stok_barang_eceran'] = $this->inv_model->get_stok_barang_eceran_list_detail($gudang_id, $barang_id, $warna_id, $tanggal_end, $tanggal_awal_eceran, $stok_opname_id);


		// $data['stok_barang'] = array();
		// $data['stok_awal'] = array();

		// echo $data['stok_barang'];
		// echo $gudang_id.",". $barang_id.",". $warna_id.",". $tanggal_start.",". $tanggal_end.",". $tanggal_awal.",". $stok_opname_id;

		// print_r($data['stok_barang_eceran']);
		if (is_posisi_id()==1) {
			# code...
			// print_r($data['stok_barang_eceran']);
			// echo $gudang_id.'<br/>'. $barang_id.'<br/>'. $warna_id.'<br/>'.$tanggal_start.'<br/>'. $tanggal_end.'<br/>'. $tanggal_awal.'<br/>'. $stok_opname_id;
			$this->load->view('admin/template_no_sidebar',$data);

		}else{
			$this->load->view('admin/template_no_sidebar',$data);
		}
	}

	function stok_barang_by_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$barang_id = '';$cond_barang = '';
		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$cond_barang = 'WHERE barang_id ='.$barang_id;
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang_by_barang',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal),
			'barang_id' => $barang_id );


		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_roll ";
		}
		
		$data['gudang_list'] = $this->gudang_list_aktif;
		$data['stok_barang'] = array();
		if ($barang_id != '') {
			$data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang($select, $tanggal, $cond_barang); 
		}
		// echo $data['stok_barang'];
		$this->load->view('admin/template',$data);
	}

//======================================stok barang + HPP============================================

	function stok_barang_hpp(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang_hpp',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang + HPP',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal) );


		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_roll ";
		}
		// echo $select.'<br>';
		$data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_hpp($select, $tanggal); 
		// echo $data['stok_barang'];
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

//=====================================mutasi barang=============================================

	function mutasi_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-d", strtotime("-7days"));
		$tanggal_end = date("Y-m-d");

		$cond = '';
		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		}

		$cond = " WHERE tanggal >='".$tanggal_start."' AND tanggal <= '".$tanggal_end."'";


		$barang_id = 0;
		if ($this->input->get('barang_id')) {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' barang_id = '.$this->input->get('barang_id');
			$barang_id = $this->input->get('barang_id');
		}

		$warna_id = 0;
		if ($this->input->get('warna_id')) {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' WARNA_id = '.$this->input->get('warna_id');
			$warna_id = $this->input->get('warna_id');
		}

		$barang_id_latest = '';
		$gudang_before_latest = '';
		if ($this->session->flashdata('mutasi_barang')) {
			$data_latest = $this->session->flashdata('mutasi_barang');
			$data_latest = explode('??', $data_latest);
			$barang_id_latest = $data_latest[0];
			$gudang_before_latest = $data_latest[1];
		}

		$data = array(
			'content' =>'admin/inventory/mutasi_barang_list',
			'breadcrumb_title' => 'Inventory',
			'breadcrumb_small' => 'Mutasi Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'cond' => $cond,
			'barang_id_latest' => $barang_id_latest,
			'gudang_before_latest' => $gudang_before_latest
			);


		// $data['mutasi_barang_list'] = $this->inv_model->mutasi_barang_list(); 
		$this->load->view('admin/template',$data);
	}

	function mutasi_barang_insert(){
		$ini = $this->input;
		$data = array(
			'gudang_id_before' => $ini->post('gudang_id_before') ,
			'gudang_id_after' => $ini->post('gudang_id_after') ,
			'tanggal' => is_date_formatter($ini->post('tanggal')) ,
			'barang_id' => $ini->post('barang_id') ,
			'warna_id' => $ini->post('warna_id') ,
			'qty' => $ini->post('qty') ,
			'jumlah_roll' => $ini->post('jumlah_roll') );
		$this->common_model->db_insert('nd_mutasi_barang',$data);


		$this->session->set_flashdata('mutasi_barang', $ini->post('barang_id').'??'.$ini->post('gudang_id_before'));

		redirect(is_setting_link('inventory/mutasi_barang'));

	}

	function mutasi_barang_update(){
		$ini = $this->input;
		$id = $this->input->post('mutasi_barang_id');
		$data = array(
			'gudang_id_before' => $ini->post('gudang_id_before') ,
			'gudang_id_after' => $ini->post('gudang_id_after') ,
			'tanggal' => is_date_formatter($ini->post('tanggal')) ,
			'barang_id' => $ini->post('barang_id') ,
			'warna_id' => $ini->post('warna_id') ,
			'qty' => $ini->post('qty') ,
			'jumlah_roll' => $ini->post('jumlah_roll') );
		$this->common_model->db_update('nd_mutasi_barang',$data,'id',$id);
		redirect(is_setting_link('inventory/mutasi_barang'));

	}


	function data_mutasi(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif','tanggal','nama_barang','gudang_before','gudang_after','qty','jumlah_roll', 'data');
        
        $sIndexColumn = "idx";
        
        // paging
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
                mysql_real_escape_string( $_GET['iDisplayLength'] );
        }
        $numbering = mysql_real_escape_string( $_GET['iDisplayStart'] );
        $page = 1;
        
        // ordering
        if ( isset( $_GET['iSortCol_0'] ) ){
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                        ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ){
                $sOrder = "";
            }
        }

        // filtering
        $sWhere = "";
        if ( $_GET['sSearch'] != "" ){
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        // individual column filtering
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
                if ( $sWhere == "" ){
                    $sWhere = "WHERE ";
                }
                else{
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

		$cond = $this->input->get('cond');

        $rResult = $this->inv_model->get_mutasi_barang_ajax($aColumns, $sWhere/*, $sOrder*/, $sLimit, $cond);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_pembelian group by tanggal');
        $Filternya = $this->inv_model->get_mutasi_barang_ajax($aColumns, $sWhere /*, $sOrder*/, '',$cond);
        $iFilteredTotal = $Filternya->num_rows();
        // $iTotal = $rResultTotal;
        // $iFilteredTotal = $iTotal;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResultTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ($rResult->result_array() as $aRow){
        	$y = 0;
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            	$row[] = $aRow[ $aColumns[$i] ];
            }
            $y++;
            $page++;
            $output['aaData'][] = $row;
        }
        
        echo json_encode( $output );
	}

	function mutasi_barang_batal(){
		$id = $this->uri->segment(3);
		$status_aktif = $this->uri->segment(4);
		if ($status_aktif == 0) {
			$status_aktif_update = 1;
		}else if ($status_aktif == 1) {
			$status_aktif_update = 0;
		}
		$data = array(
			'status_aktif' => $status_aktif_update );

		// print_r($data);
		// echo $id;
		$this->common_model->db_update('nd_mutasi_barang', $data,'id', $id);
		redirect(is_setting_link('inventory/mutasi_barang'));
	}

	function mutasi_barang_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal = $this->uri->segment(2);
		$tanggal = is_date_formatter($tanggal);

		$data = array(
			'content' =>'admin/transaction/mutasi_barang_detail',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Mutasi Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal)
			);

		if ($tanggal == '') {
			$data['mutasi_barang_list'] = array();
		}else{
			$data['mutasi_barang_list'] = $this->inv_model->get_mutasi_list_detail($tanggal);
		}
		$this->load->view('admin/template',$data);
	}

	function cek_barang_qty(){
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$gudang_id = $this->input->post('gudang_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$get_stok_opname = $this->common_model->get_latest_so($tanggal, $barang_id, $warna_id, $gudang_id);
        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        foreach ($get_stok_opname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->stok_opname_id;
        }

		$data = $this->inv_model->cek_barang_qty($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id);
		echo json_encode($data);

	}

	function cek_barang_qty_eceran(){
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$gudang_id = $this->input->post('gudang_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$get_stok_opname = $this->common_model->get_latest_so($tanggal, $barang_id, $warna_id, $gudang_id);
        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        foreach ($get_stok_opname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->stok_opname_id;
        }

		$data = $this->inv_model->cek_barang_qty_eceran($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id);
		echo json_encode($data);

	}

	function mutasi_barang_remove(){
		$id = $this->uri->segment(3);
		// echo $id;

		$data = array(
			'status_aktif' => 0  );
		$this->common_model->db_update('nd_mutasi_barang',$data,'id',$id);
		redirect(is_setting_link('inventory/mutasi_barang'));
		
	}

	function mutasi_barang_excel(){
		
		$tanggal_end = $this->input->get('tanggal_end');
		$tanggal_start = $this->input->get('tanggal_start');
		
		$cond = " WHERE status_aktif = 1 AND tanggal >='".$tanggal_start."' AND tanggal <= '".$tanggal_end."'";


		$barang_id = 0;
		if ($this->input->get('barang_id') != '0' ) {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' barang_id = '.$this->input->get('barang_id');
			$barang_id = $this->input->get('barang_id');
		}

		$warna_id = 0;
		if ($this->input->get('warna_id') != '0') {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' WARNA_id = '.$this->input->get('warna_id');
			$warna_id = $this->input->get('warna_id');
		}

		$nama_barang = 'Semua';
		$get = $this->common_model->db_select("nd_barang where id=".$barang_id);
		foreach ($get as $row) {
			$nama_barang = $row->nama_barang;
		}

		$nama_warna = 'Semua';
		$get = $this->common_model->db_select("nd_warna where id=".$warna_id);
		foreach ($get as $row) {
			$nama_warna = $row->warna_jual;
		}

		$mutasi_barang_list = $this->inv_model->get_mutasi_barang($cond); 
		
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

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Tanggal '.is_reverse_date($tanggal_start).' s/d '.is_reverse_date($tanggal_end));
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Mutasi Barang : '.$nama_barang." ".$nama_warna);

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Tanggal')
		->setCellValue('C4', 'Barang')
		->setCellValue('D4', 'Lokasi Sebelum')
		->setCellValue('E4', 'Lokasi Setelah')
		->setCellValue('F4', 'Qty')
		->setCellValue('G4', 'Jumlah Roll')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_barang_list as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,is_reverse_date($row->tanggal));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->gudang_before);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->gudang_after);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,str_replace('.00', '', $row->qty));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->jumlah_roll);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$row_no++;
			$idx++;

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_barang.xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//=====================================mutasi persediaan barang=============================================

	function mutasi_persediaan_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-01"); 
		$tanggal_end = date("Y-m-t"); 
		$toko_id = 1;
		$gudang_id = 1;

		if ($this->input->get('tanggal_start')) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
		}else{
			$tanggal_start = date('Y-m-01');
			$tanggal_end = date('Y-m-t');
		}

		if ($this->input->get('gudang_id') && $this->input->get('gudang_id') != '') {
			$gudang_id = $this->input->get('gudang_id');
		}

		$data = array(
			'content' =>'admin/inventory/mutasi_persediaan_barang',
			'breadcrumb_title' => 'Inventory',
			'breadcrumb_small' => 'Mutasi Persediaan Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'gudang_id' => $gudang_id
			);


		$data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_barang($tanggal_start,$tanggal_end, $gudang_id); 
		$this->load->view('admin/template',$data);
	}

	function mutasi_persediaan_barang_excel(){
		
		$tanggal = $this->input->get('tanggal_start');
		$tanggal_end = $this->input->get('tanggal_end');
		$toko_id = $this->input->get('toko_id');
		$gudang_id = $this->input->get('gudang_id');
		$tanggal_print = date('d F Y',strtotime($tanggal) );
		$tanggal_print_end = date('d F Y',strtotime($tanggal_end) );
		// echo $tanggal_print;
		
		$mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang($tanggal,$tanggal_end, $gudang_id); 
		
	
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

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Mutasi Persediaan Barang ');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Tanggal : '.$tanggal_print.' sd '.$tanggal_print_end);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		
		$objPHPExcel->getActiveSheet()->mergeCells("D4:F4");
		$objPHPExcel->getActiveSheet()->mergeCells("G4:I4");
		$objPHPExcel->getActiveSheet()->mergeCells("J4:L4");
		$objPHPExcel->getActiveSheet()->mergeCells("M4:O4");
		$objPHPExcel->getActiveSheet()->mergeCells("P4:R4");
		$objPHPExcel->getActiveSheet()->mergeCells("S4:U4");
		$objPHPExcel->getActiveSheet()->mergeCells("V4:X4");
		$objPHPExcel->getActiveSheet()->mergeCells("Y4:Z4");

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Barang')
		->setCellValue('C4', 'Harga Satuan')
		->setCellValue('D4', 'Stok PER ('.strtoupper(date('01 M Y', strtotime($tanggal))).')' )
		->setCellValue('G4', 'Pembelian')
		->setCellValue('J4', 'Penjualan')
		->setCellValue('M4', 'Mutasi Masuk')
		->setCellValue('P4', 'Mutasi Keluar')
		->setCellValue('S4', 'Penyesuaian')
		->setCellValue('V4', 'RETUR')
		->setCellValue('Y4', 'Sakdo Akhir')

		->setCellValue('D5', 'Yard')
		->setCellValue('E5', 'Roll')
		->setCellValue('F5', 'Nilai')
		
		->setCellValue('G5', 'Yard')
		->setCellValue('H5', 'Roll')
		->setCellValue('I5', 'Nilai')

		->setCellValue('J5', 'Yard')
		->setCellValue('K5', 'Roll')
		->setCellValue('L5', 'Nilai')

		->setCellValue('M5', 'Yard')
		->setCellValue('N5', 'Roll')
		->setCellValue('O5', 'Nilai')

		->setCellValue('P5', 'Yard')
		->setCellValue('Q5', 'Roll')
		->setCellValue('R5', 'Nilai')

		->setCellValue('S5', 'Yard')
		->setCellValue('T5', 'Roll')
		->setCellValue('U5', 'Nilai')

		->setCellValue('V5', 'Yard')
		->setCellValue('W5', 'Roll')
		->setCellValue('X5', 'Nilai')

		->setCellValue('Y5', 'Yard')
		->setCellValue('Z5', 'Roll')
		//->setCellValue('X5', 'Roll')
		//->setCellValue('Y5', 'Roll')

		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_barang_list as $row) {
			$coll = 'A';

			$total_qty = 0;
			$total_roll = 0;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_jual.' '.$row->warna_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;


			$coll_hpp = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->hpp);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stock);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$coll_hpp.$row_no.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			$total_nilai =($row->hpp * $row->qty_stock) + ($row->hpp_beli * $row->qty_beli);
			$total_qty_stock = $row->qty_stock + $row->qty_beli;
			if ($total_qty_stock == 0) {
			 	$total_qty_stock = 1;
			}
			$hpp_all = $total_nilai / $total_qty_stock;

			$hpp_beli = $row->hpp_beli;
			if ($row->hpp_beli == '') {
				$hpp_beli = 0;
			}

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_beli.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// echo $row->hpp_beli.'*'.$coll_qty.$row_no.'<br/>';
			$coll++;


			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_mutasi_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_mutasi_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_mutasi);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_mutasi);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stock + $row->qty_beli - $row->qty_jual + $row->qty_mutasi_masuk - $row->qty_mutasi + $row->qty_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual + $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi + $row->jumlah_roll_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			/*$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->barang_id);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->warna_id);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			$coll++;*/
			

			$coll++;
			$row_no++;
			$idx++;			

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_persediaan_barang ".$tanggal_print.".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//=================================input mutasi barang baru=================================

	function mutasi_stok_awal(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/inventory/stok_barang_awal',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Mutasi Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_awal_list' => $this->inv_model->get_stok_awal()
			);

		if ($this->session->flashdata('data_double')) {
			$data["data_double"] = "benar";
		}else{
			$data["data_double"] = "tidak";
		}
		// $data['stok_barang'] = $this->inv_model->get_stok_awal();
		$this->load->view('admin/template',$data);
	}

	function mutasi_stok_awal_insert(){
		$ini = $this->input;
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$post_id = $ini->post('id');
		$result = $this->common_model->db_select("nd_penyesuaian_stok where gudang_id=".$gudang_id." AND barang_id=".$barang_id." AND warna_id =".$warna_id." AND tipe_transaksi = 0 limit 1 ");
		$double = false;

		$qty = explode('--', $this->input->post('rekap_qty'));

		$data = array(
			'barang_id' => $ini->post('barang_id'),
			'warna_id' => $ini->post('warna_id'),
			'toko_id' => $ini->post('toko_id'),
			'gudang_id' => $ini->post('gudang_id'),
			'tanggal' => '2019-01-31',
			'tipe_transaksi' => 0,
			'qty' => 0,
			'jumlah_roll' => 0,
			'user_id' => is_user_id() );

		if ($post_id != '') {
			$this->common_model->db_update('nd_penyesuaian_stok', $data, 'id',$post_id);
		}else{
			$id = '';
			foreach ($result as $row) {
				$id = $row->id;
			}
			

			if ($id == '') {
				$post_id = $this->common_model->db_insert('nd_penyesuaian_stok', $data);
			}else{
				$data = array(
					'Error' => "Error");
				$this->session->set_flashdata("data_double",$data);
				$double = true;
			}
		}

		if ($double == false) {
			foreach ($qty as $key => $value) {
				$dt = explode('??', $value);
				$data_detail = array(
					'penyesuaian_stok_id' => $post_id ,
					'qty' => $dt[0],
					'jumlah_roll' => $dt[1] );
				if ($dt[2] != 0) {
					if ($dt[0] == 0 || $dt[1] == 0 ) {
						$this->common_model->db_delete('nd_penyesuaian_stok_qty','id',$dt[2]);
					}else{
						$this->common_model->db_update('nd_penyesuaian_stok_qty',$data_detail,'id',$dt[2]);
					}
				}else{
					$this->common_model->db_insert('nd_penyesuaian_stok_qty',$data_detail);
				}
			}
		}

		redirect(is_setting_link('inventory/mutasi_stok_awal'));
	}

	function mutasi_stok_awal_update(){
		$ini = $this->input;
		$id = $this->input->post('id');
		$data = array(
			'qty' => $ini->post('qty'),
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'user_id' => is_user_id() );

		$this->common_model->db_update('nd_penyesuaian_stok', $data,'id',$id);
		echo 'OK';
	}

	function mutasi_stok_awal_delete(){
		$id = $this->input->get('id');
		// echo $id;
		$this->common_model->db_delete('nd_penyesuaian_stok', 'id',$id);
		redirect(is_setting_link('inventory/mutasi_stok_awal'));

	}

//==========================================================================================

	function stok_awal_harga(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/inventory/stok_barang_awal_harga',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Harga Stok Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_awal_list' => $this->inv_model->get_harga_stok_awal()
			);

		$this->load->view('admin/template',$data);
	}

	function harga_stok_awal_update(){
		$ini = $this->input;
		$id = $this->input->post('id');
		$data = array(
			'harga_stok_awal' => str_replace('.', '', $ini->post('harga_stok_awal')),
			'user_id' => is_user_id() );

		$this->common_model->db_update('nd_stok_awal_item_harga', $data,'id',$id);
		echo 'OK';
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
			'user_id' => is_user_id(), 
			'status_aktif' => 1
		);
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
		$supplier_id = $this->input->post('supplier_id');
		$tanggal = $this->input->post('tanggal');

		$result['result'] = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id and supplier_id = $supplier_id");
		$result['resultEcer'] = $this->common_model->db_select("nd_stok_opname_eceran where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id and supplier_id = $supplier_id");
		$data = array();
		$dataEcer = array();
		foreach ($this->gudang_list_aktif as $row) {
			$get_stok_opname = $this->common_model->get_latest_so_before($tanggal, $barang_id, $supplier_id, $warna_id, $row->id);
			$tanggal_awal = '2018-01-01';
			$stok_opname_id_before = 0;
			foreach ($get_stok_opname as $row2) {
				$tanggal_awal = $row2->tanggal;
				$stok_opname_id_before = $row2->stok_opname_id;
			}
			// echo $row->id,' ',$tanggal_awal,' ', $stok_opname_id_before.'<br/>';
			array_push($data, $this->inv_model->cek_barang_qty($row->id, $barang_id,$warna_id, $supplier_id, $tanggal_awal, $stok_opname_id_before, $tanggal));

			$get_stok_opname = $this->common_model->get_latest_so_eceran_before($tanggal, $barang_id, $warna_id, $supplier_id, $row->id);
			$tanggal_awal = '2018-01-01';
			$stok_opname_id_before = 0;
			foreach ($get_stok_opname as $row2) {
				$tanggal_awal = $row2->tanggal;
				$stok_opname_id_before = $row2->stok_opname_id;
			}
			// echo $row->id.','. $barang_id.','.$warna_id.','. $tanggal_awal.','. $stok_opname_id_before."\n";
			array_push($dataEcer, $this->inv_model->cek_total_barang_qty_eceran($row->id, $barang_id,$warna_id, $supplier_id, $tanggal_awal, $stok_opname_id_before, $tanggal));
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
		$supplier_id = $this->input->post('supplier_id');
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
						'supplier_id' => $supplier_id,
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
					'supplier_id' => $supplier_id,
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
		$supplier_id = $this->input->post('supplier_id');
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
					'supplier_id' => $supplier_id,
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
					'supplier_id' => $supplier_id,
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

//=========================================eceran======================================

	function mutasi_stok_eceran_insert(){
		$keterangan = $this->input->post('keterangan');
		$keterangan = ($keterangan == '' ? null : $keterangan);
		$barang_id = $this->input->post('barang_id') ;
		$warna_id = $this->input->post('warna_id') ;
		$gudang_id = $this->input->post('gudang_id');
		$toko_id = $this->input->post('toko_id');
			
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')),
			'barang_id' => $barang_id ,
			'warna_id' => $warna_id ,
			'gudang_id' => $gudang_id ,
			'toko_id' => $toko_id ,
			'keterangan' => $keterangan,
			'tipe' => 1,
			'user_id' => is_user_id(),
		);

		$result_id = $this->common_model->db_insert('nd_mutasi_stok_eceran', $data);

		$data_detail = array();
		foreach (explode("??",$this->input->post('rekap_qty')) as $key => $value) {
			$q = explode(",", $value);
			array_push($data_detail,array(
				'mutasi_stok_eceran_id' => $result_id ,
				'qty' => $q[0],
				'jumlah_roll' => $q[1]
			));
		}

		if (count($data_detail) > 0) {
			$this->common_model->db_insert_batch("nd_mutasi_stok_eceran_qty", $data_detail);
		}

		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);

	}

	function mutasi_stok_eceran_update(){
		$keterangan = $this->input->post('keterangan');
		$keterangan = ($keterangan == '' ? null : $keterangan);
		$barang_id = $this->input->post('barang_id') ;
		$warna_id = $this->input->post('warna_id') ;
		$gudang_id = $this->input->post('gudang_id');
		$toko_id = $this->input->post('toko_id');
		$id = $this->input->post('id');
			
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')),
			'barang_id' => $barang_id ,
			'warna_id' => $warna_id ,
			'gudang_id' => $gudang_id ,
			'toko_id' => $toko_id ,
			'keterangan' => $keterangan,
			'tipe' => 1,
			'user_id' => is_user_id(),
		);

		$this->common_model->db_update('nd_mutasi_stok_eceran', $data,'id', $id);
		// $this->common_model->db_delete("nd_mutasi_stok_eceran_qty",'mutasi_stok_eceran_id',$id);

		$data_detail = array();
		foreach (explode("??",$this->input->post('rekap_qty')) as $key => $value) {
			$q = explode(",", $value);
			$id_before = $q[2];
			if($id_before == 0){
				array_push($data_detail,array(
					'mutasi_stok_eceran_id' => $id ,
					'qty' => $q[0],
					'jumlah_roll' => $q[1]
				));
			}else{
				$data_update = array(
					'qty' => $q[0] ,
				);
				if($q[0] == 0){
					$get = $this->inv_model->get_data_eceran_jual_detail($id_before);
					$qty_id_jual = '';
					foreach($get as $row){
						$qty_id_jual = $row->id;
					}

					if($qty_id_jual == ''){
						$this->common_model->db_delete('nd_mutasi_stok_eceran_qty','id', $id_before);
					}
				}else{
					$this->common_model->db_update("nd_mutasi_stok_eceran_qty",$data_update,'id', $id_before);
				}
			}
		}

		if (count($data_detail) > 0) {
			$this->common_model->db_insert_batch("nd_mutasi_stok_eceran_qty", $data_detail);
		}

		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);

	}

	function remove_mutasi_eceran(){
		$id = $this->input->post('id');
		$get_detail = $this->common_model->db_select("nd_mutasi_stok_eceran_qty WHERE mutasi_stok_eceran_id=".$id);
		$id_detail = [];
		foreach($get_detail as $row){
			array_push($id_detail,$row->id);
		}

		$qty_id = '';
		$no_faktur = '';
		if (count($id_detail) > 0) {
			$get = $this->inv_model->get_data_eceran_jual_detail(implode(",",$id_detail));
			foreach($get as $row){
				$qty_id = $row->id;
				$no_faktur = $row->no_faktur;
				$nama_customer = $row->nama_customer;
				$tanggal = is_reverse_date($row->tanggal);
			}
		}

		if ($qty_id == '') {
			$this->common_model->db_delete('nd_mutasi_stok_eceran', 'id',$id);
			$this->common_model->db_delete('nd_mutasi_stok_eceran_qty','mutasi_stok_eceran_id',$id);
			echo "OK";
		}else{
			if ($no_faktur == '') {
				$no_faktur = $tanggal.' a.n '.$nama_customer;
			}
			echo "Tidak bisa hapus, terdaftar di penjualan ".$no_faktur;
		}

	}

/** 
============================================================================================================================================= 
**/

	function assembly_list(){
		$gudang_id = "";
		$barang_id = "";
		$warna_id = "";

		$tanggal_end = date("Y-m-d");
		$tanggal_start = date("Y-m-d", strtotime('-3days'));
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
		}

		if ($this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
		}

		if ($this->input->get('gudang_id') != '') {
			$gudang_id = $this->input->get('gudang_id');
		}

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		$data = array(
			'content' =>'admin/inventory/assembly_list',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Assembly',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'gudang_id' => $gudang_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			);	
		$tanggal_awal = '2019-01-01';

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
		
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_2($select_update, $tanggal_end, $tanggal_awal);
		$data['assembly_list'] = $this->inv_model->get_assembly_list($tanggal_end, $tanggal_start);
		$this->load->view('admin/template',$data);		
	}

	function assembly_list_insert(){
		$data = $this->input->post('data');
		$dt = json_decode($data);
		$rekap_sumber = $dt->rekap_sumber;
		$rekap_hasil = $dt->rekap_hasil;
		$assembly_id = $dt->id;

		$detail_sumber = [];
		$detail_hasil = [];

		$edit_sumber = [];
		$edit_hasil = [];
		$assembly_id=$dt->id;

		$header = array(
			'tanggal' => is_date_formatter($dt->tanggal) ,
			'gudang_id' => $dt->gudang_id , 
			'toko_id' => $dt->toko_id ,
			'equal_status' => $dt->equal_status ,
			'barang_id_sumber' => $dt->barang_id_sumber,
			'warna_id_sumber' => $dt->warna_id_sumber,
			'barang_id_hasil' => $dt->barang_id_hasil,
			'warna_id_hasil' => $dt->warna_id_hasil,
			'qty_sumber'=>$dt->qty_sumber,
			'jumlah_roll_sumber'=>$dt->jumlah_roll_sumber,
			'qty_hasil'=>$dt->qty_hasil,
			'jumlah_roll_hasil'=>$dt->jumlah_roll_hasil,
			'user_id'=>is_user_id()
		);

		if ($assembly_id == '') {
			$assembly_id = $this->common_model->db_insert("nd_assembly", $header);
		}else{
			$this->common_model->db_update("nd_assembly", $header,'id', $assembly_id);
		}

		$sumber_list = $this->common_model->db_select("nd_assembly_detail_sumber where assembly_id='$assembly_id'");
		$hasil_list = $this->common_model->db_select("nd_assembly_detail_hasil where assembly_id='$assembly_id'");
		$sumber_id = [];
		$hasil_id = [];
		$delete_hasil = [];

		foreach ($sumber_list as $row) {
			array_push($sumber_id, $row->id);
		}

		foreach ($hasil_list as $row) {
			array_push($hasil_id, $row->id);
		}

		foreach ($rekap_sumber as $key => $value) {
			if (count($sumber_id) > 0) {
				array_push($edit_sumber, array(
					'id' => $sumber_id[0],
					'assembly_id' => $assembly_id,
					'barang_id' => $dt->barang_id_sumber ,
					'warna_id' => $dt->warna_id_sumber ,
					'supplier_id' => $value->supplier_id ,
					'qty' => $value->qty ,
					'jumlah_roll' => $value->jumlah_roll,
				));
				array_shift($sumber_id);
			}else{
				array_push($detail_sumber, array(
					'assembly_id' => $assembly_id,
					'barang_id' => $dt->barang_id_sumber ,
					'warna_id' => $dt->warna_id_sumber ,
					'supplier_id' => $value->supplier_id ,
					'qty' => $value->qty ,
					'jumlah_roll' => $value->jumlah_roll,
				));
			}
		}

		if (count($edit_sumber) > 0) {
			$this->common_model->db_update_batch("nd_assembly_detail_sumber", $edit_sumber, "id");
		}

		if (count($detail_sumber) > 0) {
			$this->common_model->db_insert_batch("nd_assembly_detail_sumber", $detail_sumber);
		}

		if (count($sumber_id) > 0) {
			$this->common_model->db_delete_batch("nd_assembly_detail_sumber", "id", $sumber_id);
		}

		foreach ($rekap_hasil as $key => $value) {
			if (count($hasil_id) > 0) {
				if ($value->qty > 0) {
					array_push($edit_hasil, array(
						'id' => $hasil_id[0],
						'assembly_id' => $assembly_id,
						'barang_id' => $dt->barang_id_hasil ,
						'warna_id' => $dt->warna_id_hasil ,
						'supplier_id' => $value->supplier_id ,
						'qty' => $value->qty ,
						'jumlah_roll' => $value->jumlah_roll,
					));
					array_shift($hasil_id);
				}
			}else{
				array_push($detail_hasil, array(
					'assembly_id' => $assembly_id,
					'barang_id' => $dt->barang_id_hasil ,
					'warna_id' => $dt->warna_id_hasil ,
					'supplier_id' => $value->supplier_id ,
					'qty' => $value->qty ,
					'jumlah_roll' => $value->jumlah_roll,
				));
			}
		}

		if (count($edit_hasil) > 0) {
			$this->common_model->db_update_batch("nd_assembly_detail_hasil", $edit_hasil, "id");
		}

		if (count($detail_hasil) > 0) {
			$this->common_model->db_insert_batch("nd_assembly_detail_hasil", $detail_hasil);
		}

		if (count($hasil_id) > 0) {
			$this->common_model->db_delete_batch("nd_assembly_detail_hasil", "id", $hasil_id);
		}

		echo json_encode("OK");
		
	}
	
	function assembly_list_remove(){
		$id = $this->input->get('id');
		$this->common_model->db_delete("nd_assembly_detail_hasil","assembly_id",$id);		
		$this->common_model->db_delete("nd_assembly_detail_sumber","assembly_id",$id);		
		$this->common_model->db_delete("nd_assembly","id",$id);
		echo json_encode("OK");		
	}



/** 
============================================================================================================================================= 
**/

}