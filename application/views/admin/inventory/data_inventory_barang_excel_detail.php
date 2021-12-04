<?

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

$styleArraySplitter = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'dddddd')
        )
    );

$styleArrayBG = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'eeeeee')
        )
    );

$styleArrayBG0 = array(
        'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'e6faea')
            )
        );

$styleArrayBG1 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'bfffc6')
        )
    );

$styleArrayBG2 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ffffc7')
        )
    );

$styleArrayBG3 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ffe5e3')
        )
    );

$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle("DATA BARANG");

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'NO')
->setCellValue('B1', 'INDEX_BARANG')
->setCellValue('C1', 'NAMA_BARANG')
->setCellValue('D1', 'INDEX_KETERANGAN')
->setCellValue('E1', 'KETERANGAN')
;

$row_no=1;
$coll = "F";
foreach ($this->gudang_list_aktif as $row) {
    if ($row->id == $gudang_id) {
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"INDEX ($row->nama)");
        $coll++;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"SAT.KECIL ($row->nama)");
        $coll++;
        $coll++;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"SAT.BESAR ($row->nama)");
        $coll++;
        $coll++;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"COUNT QTY ($row->nama)");
        $coll++;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"DETAIL (isi menyamping)");
        $coll++;
    }
    
}

$row_no = 2;
$idx = 1;

foreach ($data_barang as $row) {
    $coll = 'A';

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(5);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->barang_id);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang_jual);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->warna_id);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_warna_jual);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
    $coll++;

    foreach ($this->gudang_list_aktif as $row2) {
        if ($row2->id ==  $gudang_id) {
            # code...
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row2->id);
            $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
            $coll++;
    
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"");
            $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
            $coll++;
    
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan);
            $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
            $coll++;
    
            $col_roll = $coll;
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"");
            $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
            $coll++;
    
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_packaging);
            $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
            $coll++;
        }
    }

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=IF(".$col_roll.$row_no." >0,SUM(OFFSET(L".$row_no.",0,0,1,".$col_roll.$row_no.")),0)");
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;


    $row_no++;
    $idx++;
}




$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=DataBarangInventory.xls");
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>