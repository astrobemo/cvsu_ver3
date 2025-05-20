<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller {

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
		$this->load->model('report_model','rpt_model',true);
		$this->load->model('admin_model','admin_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 ORDER BY nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 ORDER BY warna_jual asc');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

	}


//=====================================penjualan report==================================================

	function penjualan_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date('Y-m-d');
		$tanggal_end = date('Y-m-d');
		$status_excel = '0';
		$tipe_search = 1;
		$customer_id = 0;
		$supplier_id = 0;
		$toko_id = 0;
		$barang_id = 0;
		$warna_id = 0;
		$gudang_id = 0;
		$penjualan_type_id = "";

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tipe_search = $this->input->get('tipe_search');
			$customer_id = $this->input->get('customer_id');
			$supplier_id = $this->input->get('supplier_id');


			
			$penjualan_type_id = $this->input->get('penjualan_type_id');
			if ($tipe_search > 6 || $tipe_search < 1) {
				$tipe_search = 1;
			}
			$status_excel = '1';
			$toko_id = $this->input->get('toko_id');
			$barang_id = $this->input->get('barang_id');
			$warna_id = $this->input->get('warna_id');
			$gudang_id = $this->input->get('gudang_id');
		}

		$cond = '';
		$customer_cond = "";
		$cond_toko = "";
		$supplier_cond = "";
		$cond_barang_warna = '';
		$penjualan_type_cond = "";
		if ($tipe_search == 2) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 3) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) < 0";
		}elseif ($tipe_search == 5) {
			$customer_cond = " AND  fp_status = 1";
		}elseif ($tipe_search == 6) {
			$customer_cond = " AND  fp_status != 1";
		}

		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
		}
		
		if ($supplier_id != null && $supplier_id != 0) {
			$supplier_cond = 'WHERE supplier_id = '.$supplier_id;
		}

		if ($toko_id != 0) {
			$cond_toko = "WHERE toko_id =".$toko_id;
		}

		if ($barang_id != 0) {
			$cond_barang_warna = "WHERE barang_id =".$barang_id;
		}

		if ($penjualan_type_id != '') {
			$penjualan_type_cond = " AND penjualan_type_id = $penjualan_type_id";
		}

		if ($warna_id != 0) {
			if ($barang_id != 0) {
				$cond_barang_warna .= "AND warna_id =".$warna_id;
			}else{
				$cond_barang_warna = "WHERE warna_id =".$warna_id;
			}
		}


		$data = array(
			'content' =>'admin/report/penjualan_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Penjualan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'tipe_search' => $tipe_search,
			'customer_id' => $customer_id,
			'supplier_id' => $supplier_id,
			'toko_id' => $toko_id,
			'barang_id' => $barang_id,
			'penjualan_type' => $this->common_model->db_select('nd_penjualan_type'),
			'warna_id' => $warna_id,
			'gudang_id' => $gudang_id,
			'tipe_bayar' => $this->common_model->db_select('nd_pembayaran_type'),
			'penjualan_type_id' => $penjualan_type_id,
			'penjualan_list' => $this->rpt_model->get_penjualan_report($tanggal_start, $tanggal_end, $cond,$customer_cond, $cond_toko, $cond_barang_warna, $supplier_cond, $penjualan_type_cond)
		);

		// echo $tanggal_start.'<hr/>'. $tanggal_end.'<hr/>'. $cond.'<hr/>'.$customer_cond.'<hr/>'. $cond_toko.'<hr/>'. $cond_barang_warna.'<hr/>'. $cond_supplier;

		$this->load->view('admin/template',$data);
	}

	function penjualan_list_export_excel(){

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$tipe_search = $this->input->get('tipe_search');
		$customer_id = $this->input->get('customer_id');

		$toko_id = $this->input->get('toko_id');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');
		$gudang_id = $this->input->get('gudang_id');

		
		$cond = '';
		$customer_cond = "";
		$nama_customer = "";
		$cond_toko = "";
		$cond_barang_warna = '';
		$supplier_cond = '';
		$penjualan_type_cond = '';
		
		if ($tipe_search == 2) {
			$cond = "WHERE pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 3) {
			$cond = "WHERE pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " WHERE  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) < 0";
		}elseif ($tipe_search == 5) {
			$customer_cond = " AND  fp_status = 1";
		}elseif ($tipe_search == 6) {
			$customer_cond = " AND  fp_status != 1";
		}
		
		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;

			$get = $this->common_model->db_select("nd_customer where id=".$customer_id);
			foreach ($get as $row) {
				$nama_customer = "Customer : ".$row->nama;
			}
		}

		if ($toko_id != 0) {
			$cond_toko = "WHERE toko_id =".$toko_id;
		}

		if ($barang_id != 0) {
			$cond_barang_warna = "WHERE barang_id =".$barang_id;
		}

		if ($warna_id != 0) {
			if ($barang_id != 0) {
				$cond_barang_warna .= "AND warna_id =".$warna_id;
			}else{
				$cond_barang_warna = "WHERE warna_id =".$warna_id;
			}
		}
		
		//'penjualan_list' => $this->rpt_model->get_penjualan_report($tanggal_start, $tanggal_end, $cond,$customer_cond, $cond_toko, $cond_barang_warna, $supplier_cond, $penjualan_type_cond)

		$penjualan_list = $this->rpt_model->get_penjualan_report($tanggal_start, $tanggal_end, $cond,$customer_cond, $cond_toko, $cond_barang_warna, $supplier_cond, $penjualan_type_cond);
		$tipe_bayar = $this->common_model->db_select("nd_pembayaran_type");

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

		$objPHPExcel->getActiveSheet()->mergeCells("A1:M1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:M2");

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' LAPORAN PENJUALAN '.$nama_customer)
		->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'No Faktur')
		->setCellValue('C4', 'No Faktur')
		->setCellValue('D4', 'Tanggal')
		->setCellValue('E4', 'Qty')
		->setCellValue('F4', 'Jumlah Roll')
		->setCellValue('G4', 'Nama Barang')
		->setCellValue('H4', 'Nama Jual')
		->setCellValue('I4', 'Harga Jual')
		->setCellValue('J4', 'Total')
		// ->setCellValue('J4', 'Diskon')
		// ->setCellValue('J4', 'Ongkos Kirim')
		->setCellValue('K4', 'Nama Customer')
		->setCellValue('L4', 'Keterangan')
		;

		$coll_now = "L";
		foreach ($tipe_bayar as $row2) {
			$objPHPExcel->getActiveSheet()->setCellValue($coll_now."4",$row2->nama);
			$coll_now++;
		}

		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->applyFromArray($styleArray);


		$idx = 1; $row_no = 5; $g_total = 0;
		$yard_total = 0;
		$roll_total = 0;
		foreach ($penjualan_list as $row) {
			$total = array();

			$qty = explode('??', $row->qty);
			$harga_jual = explode('??', $row->harga_jual);
			$jumlah_roll = explode('??', $row->jumlah_roll);
			$nama_barang = explode('??', $row->nama_barang);
			$nama_jual = explode('??', $row->nama_jual);
			$pengali_harga = explode('??', $row->pengali_harga);
			$count = count($qty);
			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur_pertoko);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;
			

			$tanggal = date('d-m-Y',strtotime($row->tanggal));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;

			foreach ($harga_jual as $key => $value) {
				$coll = $coll_start;
				$yard_total += $qty[$key];
				$roll_total += $jumlah_roll[$key];
				
				$col_qty = $coll;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				if ($row->pengali_harga == 1) {
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->applyFromArray($styleArray);
				}
				
				$coll++;

				$col_roll = $coll;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
				if ($row->pengali_harga == 2) {
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->applyFromArray($styleArray);
				}
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->nama_barang));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->nama_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				$col_harga = $coll;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_jual[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$col_sub = ($pengali_harga[$key] == 1 ? $col_qty : $col_roll);
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$col_sub.$row_no." * ".$col_harga.$row_no);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				if ($key != $count -1) {
					$row_no++;
				}
				$sub_total += ($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key]) * $harga_jual[$key];
				$g_total += ($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key]) * $harga_jual[$key];
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_customer);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$status = '';
			if ($row->keterangan < 0) {
				$status = 'belum lunas';
			}else if ($row->keterangan >= 0){
				$status = 'lunas';
			} 

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $status);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
			$data_bayar = explode(',', $row->data_bayar);
			$bayar = array_combine($pembayaran_type_id, $data_bayar);

			foreach ($tipe_bayar as $row2) {
				if (isset($bayar[$row2->id])) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $bayar[$row2->id]);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				}
				$coll++;
			}

			$idx++;
			$last_row = $row_no;
			$row_no++;

			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "SUBTOTAL");
			$objPHPExcel->getActiveSheet()->getStyle("I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $sub_total);
			$objPHPExcel->getActiveSheet()->getStyle("J".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
			$row_no++;

			if ($row->diskon != '' && $row->diskon != 0) {
				$g_total = $g_total - $row->diskon;
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "DISKON");
				$objPHPExcel->getActiveSheet()->getStyle("I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $row->diskon);
				$objPHPExcel->getActiveSheet()->getStyle("J".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
				$row_no++;
			}


			$row_no++;			
			$row_no++;			
			
		}

		//=======================================================================================		

		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_total);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_total);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
		// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':I'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':I'.$row_no)->applyFromArray($styleArray);

		
		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Laporan_Penjualan_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	

//=====================================pembelian report==================================================

	function pembelian_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$status_excel = '1';
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');
			$barang_id = $this->input->get('barang_id');
			$warna_id = $this->input->get('warna_id');
			$gudang_id = $this->input->get('gudang_id');
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$toko_id = 1;
			$supplier_id = 0;
			$barang_id = 0;
			$warna_id = 0;
			$gudang_id = 0;
		}

		$data = array(
			'content' =>'admin/report/pembelian_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Pembelian',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'toko_id' => $toko_id,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'supplier_id' => $supplier_id,
			'gudang_id' => $gudang_id
			);

		$cond = '';
		if ($toko_id != 0) {
			$cond .= " AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
		}

		$cond .= ($gudang_id != 0 ? ' AND gudang_id = '.$gudang_id : '');
		$cond_barang = ($barang_id != 0 ? " AND barang_id = ".$barang_id : '');
		$cond_warna = ($warna_id != 0 ? " AND warna_id = ".$warna_id : '');

		$data['pembelian_list'] = $this->rpt_model->get_pembelian_report($tanggal_start, $tanggal_end, $cond, $cond_barang, $cond_warna);
		$this->load->view('admin/template',$data);
		if(is_posisi_id()==1){
			$this->output->enable_profiler(TRUE);
		}
	}

	function pembelian_list_export_excel(){

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		$gudang_id = $this->input->get('gudang_id');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');
		$supplier_id = $this->input->get('supplier_id');
		$nama_supplier = '';
		if ($toko_id == 0) {
			$nama_toko = "SEMUA TOKO";
		}else{
			$result = $this->common_model->db_select('nd_toko WHERE id='.$toko_id);
			foreach ($result as $row) {
				$nama_toko = $row->nama;
			}
		}

		$cond = '';
		if ($toko_id != 0) {
			$cond = " AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
			$get = $this->common_model->db_select('nd_supplier where id='.$supplier_id);
			foreach ($get as $row) {
				$nama_supplier = " ke supplier ".$row->nama;
			}
		}

		$cond .= ($gudang_id != 0 ? ' AND gudang_id = '.$gudang_id : '');
		$cond_barang = ($barang_id != 0 ? " AND barang_id = ".$barang_id : '');
		$cond_warna = ($warna_id != 0 ? " AND warna_id = ".$warna_id : '');

		$pembelian_list = $this->rpt_model->get_pembelian_report_excel($tanggal_start, $tanggal_end, $cond, $cond_barang, $cond_warna);
		// $pembelian_list = $this->rpt_model->get_pembelian_report($tanggal_start, $tanggal_end, $cond, $cond_barang, $cond_warna);

		
		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$data['objPHPExcel'] = new PHPExcel();
		$data['tanggal_start'] = $tanggal_start;
		$data['tanggal_end'] = $tanggal_end;

		$data['toko_id'] = $toko_id;
		$data['gudang_id'] = $gudang_id;
		$data['barang_id'] = $barang_id;
		$data['warna_id'] = $warna_id;
		$data['supplier_id'] = $supplier_id;
		$data['nama_supplier'] = $nama_supplier;
		$data['pembelian_list'] = $pembelian_list;
		$data['nama_toko'] = $nama_toko;

		$this->load->view('admin/report/pembelian_list_report_excel',$data);

	}


//======================================laporan_harian===========================================

	function penerimaan_harian_penjualan(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date('Y-m-d');
		$tanggal_end = date('Y-m-d');

		if($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '' ){
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		$data = array(
			'content' =>'admin/report/penerimaan_harian_penjualan',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Penerimaan Harian Penjualan',
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );

		$data['penjualan_list'] = $this->rpt_model->get_penjualan_bayar_by_date($tanggal_start, $tanggal_end);
		$data['retur_list'] = $this->rpt_model->get_retur_jual_by_date($tanggal_start, $tanggal_end);
		$data['pembayaran_type'] = $this->common_model->db_select("nd_pembayaran_type");
		$this->load->view('admin/template',$data);

	}


//======================================laporan_gp===========================================

	function penjualan_laba_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tipe_search = $this->input->get('tipe_search');
			$customer_id = $this->input->get('customer_id');
			if ($tipe_search > 4 || $tipe_search < 1) {
				$tipe_search = 1;
			}
			$status_excel = '1';
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$tipe_search = 1;
			$customer_id = 0;
		}

		$cond = 'WHERE total is not null';
		if ($tipe_search == 2) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 3) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) < 0";
		}
		$customer_cond = "";
		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
		}


		$data = array(
			'content' =>'admin/report/penjualan_laba_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan GP',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'tipe_search' => $tipe_search,
			'customer_id' => $customer_id,
			'penjualan_list' => $this->rpt_model->get_penjualan_laba_report($tanggal_start, $tanggal_end, $cond,$customer_cond)
			);

		$this->load->view('admin/template',$data);
	}

//======================================general_report===========================================
	function penjualan_general_report()
	{
		
		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-t');
		$tahun = date('Y');

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			if ($this->input->get('tahun') != '') {
				$tahun = $this->input->get('tahun');
			}
		}


		$bulan = date('d M y', strtotime($tanggal_start)).' s/d '.date('d M y', strtotime($tanggal_end));

		$data = array(
			'content' => 'admin/report/penjualan_general_report',
			'breadcrumb_title' => 'General Report Penjualan',
			'breadcrumb_small' => 'statistik & laporan',
			'nama_menu' => 'menu_report',
			'nama_submenu' => '',
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'ket_tgl' => $bulan,
			'tahun' => $tahun,
			'common_data'=> $this->data );


		$data['recap_pembelian_bulanan'] = $this->admin_model->recap_pembelian_bulanan($tanggal_start, $tanggal_end);
		$data['recap_penjualan_bulanan'] = $this->admin_model->recap_penjualan_bulanan($tanggal_start, $tanggal_end);
		$this->load->view('admin/template',$data);
	}


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

//=====================================barang masuk report==================================================	

	function barang_masuk_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$status_excel = '1';
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');
			$barang_id = $this->input->get('barang_id');
			$warna_id = $this->input->get('warna_id');
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$toko_id = 1;
			$supplier_id = 0;
			$barang_id = 0;
		}

		$data = array(
			'content' =>'admin/report/barang_masuk_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Barang Masuk',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'toko_id' => $toko_id,
			'supplier_id' => $supplier_id,
			'barang_id' => $barang_id
			);

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
		}

		$cond2 = '';
		if ($barang_id != 0) {
			$cond2 = " WHERE barang_id = $barang_id";
		}

		$data['barang_list'] = $this->rpt_model->get_barang_masuk_report($tanggal_start, $tanggal_end, $cond, $cond2);
		$this->load->view('admin/template',$data);
	}

	function barang_masuk_list_detail_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		$supplier_id = $this->input->get('supplier_id');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');

		$get = $this->common_model->db_select('nd_barang where id='.$barang_id);
		foreach ($get as $row) {
			$nama_barang = $row->nama;
		}
		$get = $this->common_model->db_select('nd_warna where id='.$warna_id);
		foreach ($get as $row) {
			$nama_warna = $row->warna_beli;
		}
		$data = array(
			'content' =>'admin/report/barang_masuk_list_detail_report' ,
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'barang masuk detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'nama_barang' => $nama_barang,
			'nama_warna' => $nama_warna,
			'supplier_list_aktif'=>$this->supplier_list_aktif );

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
		}

		$cond2 = " WHERE barang_id = $barang_id AND warna_id = $warna_id";

		$data['barang_list'] = $this->rpt_model->get_barang_masuk_detail_report($tanggal_start, $tanggal_end, $cond, $cond2);
		$this->load->view('admin/template_no_sidebar',$data);
		
	}
//=====================================barang keluar report==================================================	

	function barang_keluar_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$status_excel = '1';
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');
			$barang_id = $this->input->get('barang_id');
			$warna_id = $this->input->get('warna_id');
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$toko_id = 1;
			$customer_id = 0;
			$barang_id = 0;
		}

		$data = array(
			'content' =>'admin/report/barang_keluar_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Barang keluar',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'toko_id' => $toko_id,
			'customer_id' => $customer_id,
			'barang_id' => $barang_id
			);

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($customer_id != 0) {
			$cond .= " AND customer_id = $customer_id";
		}

		$cond2 = '';
		if ($barang_id != 0) {
			$cond2 = " WHERE barang_id = $barang_id";
		}

		$data['barang_list'] = $this->rpt_model->get_barang_keluar_report($tanggal_start, $tanggal_end, $cond, $cond2);
		$this->load->view('admin/template',$data);
	}

	function barang_keluar_list_detail_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		$customer_id = $this->input->get('customer_id');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');

		$get = $this->common_model->db_select('nd_barang where id='.$barang_id);
		foreach ($get as $row) {
			$nama_barang = $row->nama;
		}
		$get = $this->common_model->db_select('nd_warna where id='.$warna_id);
		foreach ($get as $row) {
			$nama_warna = $row->warna_beli;
		}
		$data = array(
			'content' =>'admin/report/barang_keluar_list_detail_report' ,
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'barang keluar detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'nama_barang' => $nama_barang,
			'nama_warna' => $nama_warna,
			'customer_list_aktif'=>$this->customer_list_aktif );

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($customer_id != 0) {
			$cond .= " AND customer_id = $customer_id";
		}

		$cond2 = " WHERE barang_id = $barang_id AND warna_id = $warna_id";

		$data['barang_list'] = $this->rpt_model->get_barang_keluar_detail_report($tanggal_start, $tanggal_end, $cond, $cond2);
		$this->load->view('admin/template_no_sidebar',$data);
	}


//=====================================buku laporan piutang==================================================	

	function buku_laporan_piutang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tipe_search = $this->input->get('tipe_search');
			$customer_id = $this->input->get('customer_id');
			if ($tipe_search > 6 || $tipe_search < 1) {
				$tipe_search = 1;
			}
			$status_excel = '1';
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$tipe_search = 1;
			$customer_id = 0;
		}

		$cond = '';
		$customer_cond = "";
		
		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
		}


		$data = array(
			'content' =>'admin/report/buku_laporan_piutang',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Buku Laporan Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'tipe_search' => $tipe_search,
			'customer_id' => $customer_id,
			// 'penjualan_list' => $this->rpt_model->buku_laporan_piutang($tanggal_start, $tanggal_end, $cond,$customer_cond)
			);

		$this->load->view('admin/template',$data);
	}


	function data_buku_laporan_piutang(){

		$aColumns = array('no_faktur','tanggal','qty','jumlah_roll','nama_barang', 'harga_jual','total','nama_customer','pembayaran_data','pelunasan_data','keterangan', 'penjualan_id');
        
        $sIndexColumn = "id";
        
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

        
        $tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$cond_date = "WHERE tanggal >= '$tanggal_start' AND tanggal <= '$tanggal_end'";

		$customer_id = $this->input->get('customer_id');
		$customer_cond = ($customer_id != '' ? "AND customer_id =".$customer_id : '');
			
        
        $rResult = $this->rpt_model->buku_laporan_piutang($aColumns, $sWhere, $sOrder, $sLimit, $cond_date, $customer_cond);
        
        // $iFilteredTotal = 5;
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_penjualan '.$cond_date.' '.$customer_cond);
        $Filternya = $this->rpt_model->buku_laporan_piutang($aColumns, $sWhere, $sOrder, '', $cond_date, $customer_cond);
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

//=====================================buku laporan penyesuaian==================================================

	function laporan_penyesuaian_stok(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$cond_gudang = '1';

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-t');

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			// $cond_gudang = $this->input->get('cond_gudang');
		}

		$data = array(
			'content' =>'admin/report/laporan_penyesuaian_stok',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Penyesuaian Stok Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'cond_gudang' => $cond_gudang 
			);


		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
		}
		if ($cond_gudang == 1) {
			$data['penyesuaian_stok_barang'] = $this->rpt_model->get_penyesuaian_stok($select, $tanggal_start, $tanggal_end); 
		}elseif ($cond_gudang = 2) {
			$data['penyesuaian_stok_barang'] = $this->rpt_model->get_penyesuaian_stok_by_gudang($select, $tanggal_start, $tanggal_end); 
		}
		$this->load->view('admin/template',$data);
	
	}

	function laporan_penyesuaian_stok_excel(){
		
		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$gudang_id = $this->input->get('gudang_id');
		if ($gudang_id != 0) {
			$cond_gudang = "AND gudang_id = ".$gudang_id;
		}
		
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
		}
		$penyesuaian_stok_barang = $this->rpt_model->get_penyesuaian_stok($select, $tanggal_start, $tanggal_end); 
		// print_r($penyesuaian_stok_barang);

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

		$objPHPExcel->getActiveSheet()->mergeCells("A1:M1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:M2");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:D4");
		$objPHPExcel->getActiveSheet()->mergeCells("E4:F4");
		$objPHPExcel->getActiveSheet()->mergeCells("G4:H4");

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' LAPORAN PENYESUAIAN STOK ')
		->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama')
		->setCellValue('C4', 'Masuk')
		->setCellValue('E4', 'Keluar')
		->setCellValue('G4', 'Total')

		->setCellValue('C5', 'Yard/Kg')
		->setCellValue('D5', 'Jumlah Roll')
		->setCellValue('E5', 'Yard/Kg')
		->setCellValue('F5', 'Jumlah Roll')
		->setCellValue('G5', 'Yard/Kg')
		->setCellValue('H5', 'Jumlah Roll')
		// ->setCellValue('J4', 'Diskon')
		// ->setCellValue('J4', 'Ongkos Kirim')
		;

		$coll_now = "H";
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->applyFromArray($styleArray);


		$idx = 1; $row_no = 6; $g_total = 0;
		$yard_total = 0;
		$roll_total = 0;
		foreach ($penyesuaian_stok_barang as $row) {
			$total = array();

			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang.' '.$row->nama_warna);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->qty_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->qty_keluar);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_keluar);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			//===============================TOTAL=========================================
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=C".$row_no." - E".$row_no);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=D".$row_no." - F".$row_no);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$row_no++;
			$idx++;	
			
		}

		//=======================================================================================		

		
		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Laporan_Penyesuaian_Stok_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

}