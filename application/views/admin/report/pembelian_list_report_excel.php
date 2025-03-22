<?

$styleArray = array(
	'font'=>array(
		'bold'=>true,
		'size'=>12,
		)
	);

$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");


$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' LAPORAN PEMBELIAN '.$nama_toko.$nama_supplier)
->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
->setCellValue('A4', 'No')
->setCellValue('B4', 'No Faktur')
->setCellValue('C4', 'Tanggal')
->setCellValue('D4', 'Qty')
->setCellValue('E4', 'Jumlah Roll')
->setCellValue('F4', 'Nama Barang')
->setCellValue('G4', 'Nama Jual')
->setCellValue('H4', 'Harga Beli')
->setCellValue('I4', 'SubTotal')
->setCellValue('J4', 'Diskon')
->setCellValue('K4', 'Total')
->setCellValue('L4', 'Supplier')
->setCellValue('M4', 'Lokasi')
->setCellValue('N4', 'Jatuh Tempo')
->setCellValue('O4', 'Keterangan')
;

$objPHPExcel->getActiveSheet()->getStyle('A1:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:N4')->applyFromArray($styleArray);


// print_r($pembelian_list);
$idx = 1; $row_no = 5; $g_total = 0;
$yard_total = 0;
$roll_total = 0;
foreach ($pembelian_list as $row) {
	$total = array();

	$qty = explode('??', $row->qty);
	$harga_beli = explode('??', $row->harga_beli);
	$jumlah_roll = explode('??', $row->jumlah_roll);
	$nama_barang = explode('??', $row->nama_barang);
	$nama_jual = explode('??', $row->nama_jual);
	$pengali_type = explode('??', $row->pengali_type);
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

	$tanggal = date('d-m-Y',strtotime($row->tanggal));
	$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
	$coll++;

	$coll_start = $coll;
	$row_start = $row_no;
	$subtotal = 0;


	foreach ($harga_beli as $key => $value) {
		$coll = $coll_start;
		$yard_total += $qty[$key];
		$roll_total += $jumlah_roll[$key];
		// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace("??","\n",$row->qty));
		$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);				
		// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
		$coll++;

		// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->jumlah_roll));
		$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
		// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
		$coll++;

		$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
		$coll++;

		$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_jual[$key]);				
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
		$coll++;

		// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->harga_beli));
		$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_beli[$key]);				
		// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
		$coll++;

		$p = $pengali_type[$key] == 1 ? $qty[$key] : $jumlah_roll[$key];
		$total = $p * $harga_beli[$key];
		$subtotal += $total;
		if(is_posisi_id() == 1) {
			// echo $key.'**';
			// echo $p."--".$harga_beli[$key]."--".$row->diskon."<hr/>";
		}
		$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $p * $harga_beli[$key]);
		// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
		$coll++;

		if ($key != $count -1) {
			$row_no++;
		}
	}

	$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->diskon);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
	$coll++;

	$subtotal = $subtotal - $row->diskon;
	$g_total += $total;

	$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $subtotal);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
	$coll++;

	$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_supplier);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
	$coll++;

	$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_gudang);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
	$coll++;

	$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, date('d-m-Y', strtotime($row->jatuh_tempo)));
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

	$idx++;
	$last_row = $row_no;
	$row_no++;
	
}

$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_total);
$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_total);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "=SUM( I5:I".$last_row.')');
$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, "=SUM( J5:J".$last_row.')');
$objPHPExcel->getActiveSheet()->setCellValue('K'.$row_no, "=SUM( K5:K".$last_row.')');
// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':K'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':K'.$row_no)->applyFromArray($styleArray);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Laporan_Pembelian_".str_replace(' ', '_', $nama_toko)."_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>