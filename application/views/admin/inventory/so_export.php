<?
    $data_perg = array();
    foreach ($this->gudang_list_aktif as $row) {
        $data_perg[$row->id] = array();
    }
    foreach ($data_get as $row){
        array_push($data_perg[$row->gudang_id], $row);
    }
?>
<? /*foreach ($this->gudang_list_aktif as $list) {
			$idx = 1;?>
			<h3><?=$list->nama;?></h3>
			<table>
                <thead>
                    <tr>
                        <th rowspan=''>NO</th>
                        <th rowspan=''>Tanggal<br/>SO</th>
                        <th rowspan=''>Toko</th>
                        <th rowspan=''>Gudang</th>
                        <th rowspan=''>Nama Beli</th>
                        <th rowspan=''>Nama Jual</th>
                        <th rowspan=''>Harga Eceran</th>
                        <th rowspan=''>Harga Jual</th>
                        <th rowspan=''>Harga Beli</th>
                        <th rowspan=''>Nama <br/>Keterangan</th>
                        <th rowspan=''>Supplier</th>
                        <th rowspan=''>Qty Besar</th>
                        <th rowspan=''>Satuan Besar</th>
                        <th rowspan=''>Qty Kecil</th>
                        <th rowspan=''>Satuan Kecil</th>
                        <th rowspan=''>Qty Eceran</th>
                        <th rowspan=''>Satuan Eceran</th>
                        <th rowspan=''>Rincian Qty Kecil</th>
                    </tr>
                </thead>
                <tbody>

                    <?$no=0; $tgl = is_reverse_date($tanggal);
                    foreach ($data_perg[$list->id] as $row) {
                        unset($qty_list);
                        $no++;
                        
                        ?>
                                <tr>
                                    <!-- no -->
                                    <td><?=$no;?></td>
                                    <!-- Tanggal -->
                                    <td><?=is_reverse_date($tanggal)?></td>
                                    <td><?=$row->nama_toko;?></td>
                                    <td><?=$row->nama_gudang;?></td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_barang;?></td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_barang_jual;?></td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->harga_ecer;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->harga_jual;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->harga_beli;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_warna_jual;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=($row->supplier_id != 0 ? $row->nama_supplier : '-');?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->roll;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_satuan_besar;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->qty;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_satuan_kecil;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?//=$row->qty_eceran;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_satuan_eceran;?> </td>
                                    
                                </tr>
                            <?
                        
                        $idx++;
                    }?>
                </tbody>
			</table>
		<?}*/?>

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

$row_no=1;
$coll = "F";
$sheet_idx=0;

foreach ($this->gudang_list_aktif as $list) {

    $objPHPExcel->createSheet($sheet_idx);
    $objPHPExcel->setActiveSheetIndex($sheet_idx);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setTitle($list->nama);

    //a
    //<th rowspan=''>NO</th>
    // <th rowspan=''>Tanggal<br/>SO</th>
    // <th rowspan=''>Toko</th>
    // <th rowspan=''>Gudang</th>
    // <th rowspan=''>Nama Beli</th>
    // <th rowspan=''>Nama Jual</th>
    // <th rowspan=''>Harga Eceran</th>
    // <th rowspan=''>Harga Jual</th>
    // <th rowspan=''>Harga Beli</th>
    // <th rowspan=''>Nama <br/>Keterangan</th>
    // <th rowspan=''>Supplier</th>
    // <th rowspan=''>Qty Besar</th>
    // <th rowspan=''>Satuan Besar</th>
    // <th rowspan=''>Qty Kecil</th>
    // <th rowspan=''>Satuan Kecil</th>
    // <th rowspan=''>Qty Eceran</th>
    // <th rowspan=''>Satuan Eceran</th>
    // <th rowspan=''>Rincian Qty Kecil</th>

    $sheet
    ->setCellValue('A4', 'NO')
    ->setCellValue('B4', 'TANGGAL SO')
    ->setCellValue('C4', 'TOKO')
    ->setCellValue('D4', 'GUDANG')
    ->setCellValue('E4', 'NAMA_BELI')
    ->setCellValue('F4', 'NAMA_JUAL')
    ->setCellValue('G4', 'HARGA_BELI')
    ->setCellValue('H4', 'HARGA_JUAL')
    ->setCellValue('I4', 'HARGA_ECER')
    ->setCellValue('J4', 'KETERANGAN_BRG')
    ->setCellValue('K4', 'SUPPLIER')
    ->setCellValue('L4', 'QTY_BESAR')
    ->setCellValue('M4', 'SATUAN_BESAR')
    ->setCellValue('N4', 'QTY_KECIL')
    ->setCellValue('O4', 'SATUAN_KECIL')
    ->setCellValue('P4', 'QTY_ECERAN')
    ->setCellValue('Q4', 'SATUAN_ECERAN')
    ->setCellValue('R4', 'RINCIAN_QTY')
    ;


    $objPHPExcel->getActiveSheet()->setCellValue("A1","GUDANG ($list->nama)");
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    // $coll++;

    $row_no = 5;
    $idx = 1;

    foreach ($data_perg[$list->id] as $row) {
        $coll = 'A';
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
        $coll++;
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
        $coll++;
        
        // toko
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_toko);
        $coll++;
        // gudang
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_gudang);
        $coll++;
        // nama beli
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang);
        $coll++;
        // nama jual
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang_jual);
        $coll++;
        // harga beli
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->harga_beli);
        $coll++;
        // harga jual
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->harga_jual);
        $coll++;
        // harga ecer
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->harga_ecer);
        $coll++;
        // ket
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_warna_jual);
        $coll++;
        // supplier
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_supplier);
        $coll++;
        // qty
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->roll);
        $coll++;
        // sat besar
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan_besar);
        $coll++;
        // qty kecil
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->qty);
        $coll++;
        // sat kecil
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan_kecil);
        $coll++;
        // qty ecer
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,'');
        $coll++;
        // sat ecer
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan_eceran);
        $coll++;
        // qty rinci

        if (is_posisi_id()==1) {
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->warna_id);
            $coll++;
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->barang_id);
            $coll++;
        }

        // $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_warna_jual);
        // $coll++;

        $row_no++;
        $idx++;
    }

    //A
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
    //B
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //C
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
    //D
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //E
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    //F
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    //G
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //H
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //I
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //J
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    $objPHPExcel->getActiveSheet()->getStyle($coll)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //K
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    //L
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //M
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    //N
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //O
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);

    $sheet_idx++;
}


//===================generate eceran=======================================================

    $objPHPExcel->createSheet($sheet_idx);
    $objPHPExcel->setActiveSheetIndex($sheet_idx);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setTitle("eceran");

    //a
    //<th rowspan=''>NO</th>
    // <th rowspan=''>Tanggal<br/>SO</th>
    // <th rowspan=''>Toko</th>
    // <th rowspan=''>Gudang</th>
    // <th rowspan=''>Nama Beli</th>
    // <th rowspan=''>Nama Jual</th>
    // <th rowspan=''>Harga Eceran</th>
    // <th rowspan=''>Harga Jual</th>
    // <th rowspan=''>Harga Beli</th>
    // <th rowspan=''>Nama <br/>Keterangan</th>
    // <th rowspan=''>Supplier</th>
    // <th rowspan=''>Qty Besar</th>
    // <th rowspan=''>Satuan Besar</th>
    // <th rowspan=''>Qty Kecil</th>
    // <th rowspan=''>Satuan Kecil</th>
    // <th rowspan=''>Qty Eceran</th>
    // <th rowspan=''>Satuan Eceran</th>
    // <th rowspan=''>Rincian Qty Kecil</th>

    $sheet
    ->setCellValue('A4', 'NO')
    ->setCellValue('B4', 'TANGGAL SO')
    ->setCellValue('C4', 'TOKO')
    ->setCellValue('D4', 'GUDANG')
    ->setCellValue('E4', 'NAMA_BELI')
    ->setCellValue('F4', 'NAMA_JUAL')
    ->setCellValue('G4', 'HARGA_BELI')
    ->setCellValue('H4', 'HARGA_JUAL')
    ->setCellValue('I4', 'HARGA_ECER')
    ->setCellValue('J4', 'KETERANGAN_BRG')
    ->setCellValue('K4', 'SUPPLIER')
    ->setCellValue('L4', 'QTY_BESAR')
    ->setCellValue('M4', 'SATUAN_BESAR')
    ->setCellValue('N4', 'QTY_KECIL')
    ->setCellValue('O4', 'SATUAN_KECIL')
    ->setCellValue('P4', 'QTY_ECERAN')
    ->setCellValue('Q4', 'SATUAN_ECERAN')
    ->setCellValue('R4', 'RINCIAN_QTY')
    ;


    $objPHPExcel->getActiveSheet()->setCellValue("A1","ECERAN");
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    // $coll++;

    $row_no = 5;
    $idx = 1;

    foreach ($stok_barang_eceran as $row) {

        $qty_stok = explode(",", $row->qty_stok_data);
        $coll = 'A';
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
        $coll++;
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
        $coll++;
        
        // toko
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_toko);
        $coll++;
        // gudang
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_gudang);
        $coll++;
        // nama beli
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang);
        $coll++;
        // nama jual
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang_jual);
        $coll++;
        // harga beli
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->harga_beli);
        $coll++;
        // harga jual
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->harga_jual);
        $coll++;
        // harga ecer
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->harga_ecer);
        $coll++;
        // ket
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_warna_jual);
        $coll++;
        // supplier
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_supplier);
        $coll++;
        // qty
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,'');
        $coll++;
        // sat besar
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan_besar);
        $coll++;
        // qty kecil
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,'');
        $coll++;
        // sat kecil
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan_kecil);
        $coll++;
        // qty ecer
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->qty_stok);
        $coll++;
        // sat ecer
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan_eceran);
        $coll++;
        // qty rinci
        for ($x=0; $x < count($qty_stok) ; $x++) { 
            # code...
            if ($qty_stok[$x] !=0) {
                $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$qty_stok[$x]);
                $coll++;
            }
        }
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_satuan_eceran);
        $coll++;

        if (is_posisi_id()==1) {
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->warna_id);
            $coll++;
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->barang_id);
            $coll++;
        }

        // $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_warna_jual);
        // $coll++;

        $row_no++;
        $idx++;
    }

    //A
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
    //B
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //C
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
    //D
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //E
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    //F
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    //G
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //H
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //I
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //J
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    $objPHPExcel->getActiveSheet()->getStyle($coll)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //K
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(25);
    //L
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //M
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    //N
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    //O
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);


    $sheet_idx++;





$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=DataBarangSO_$tanggal.xls");
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>