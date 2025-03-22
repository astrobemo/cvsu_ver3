<?

	$ppn_pembagi = 1.11;

	$ppn_pembagi = 1+($ppn_berlaku/100);


	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = $row->alamat_keterangan;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
		$npwp_customer = $row->npwp_customer;
		$keterangan = $row->keterangan;
		$diskon = $row->diskon;
	}



	$pdf = new FPDF( 'L', 'mm', array(220 ,140 ) );
	$pdf->cMargin = 0;
	$pdf->AddPage();
	$pdf->SetMargins(7,0,3);
	$pdf->SetTextColor( 0,0,0 );

	$pdf->AddFont('calibriL','','calibriL.php');
	$pdf->AddFont('calibri','','calibri.php');
	$pdf->AddFont('calibriLI','','calibriLI.php');

	$font_name = 'calibriL';
	$font_name_bold = 'calibri';
	$font_name_italic = 'calibriLI';

	// $font_name = 'Arial';
	// $font_name_bold = 'Arial';
	// $font_name_italic = 'Arial';

	foreach ($toko_data as $row) {
		$nama_toko = $row->nama;
		$alamat_toko = $row->alamat;
		$phone = $row->telepon;
		$fax = $row->fax;
		$npwp = $row->NPWP;
		$kota_toko = $row->kota;
	}


	$pdf->Ln(0);
	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Text( 85, 8, 'FAKTUR PENJUALAN');
	// $pdf->Cell( 10 );
	$pdf->Image(site_url().'image/logo.jpg',7,10,-500);
	$pdf->SetFont( $font_name, '', 11 );
	$pdf->Text( 25, 14, $nama_toko);
	$pdf->Text( 25, 18, $alamat_toko);
	$pdf->Text( 25, 22, 'Jawa Barat 40114');
	$pdf->Text( 25, 26, 'Indonesia');
	
	$pdf->Cell( 205, 4, "Bill To :", 0, 1, 'R' );
	$pdf->Cell( 205, 4, strtoupper($nama_customer), 0, 1, 'R' );
	$pdf->Cell( 205, 4, $npwp_customer, 0, 1, 'R' );
	$pdf->Cell( 205, 4, strtoupper($alamat), 0, 1, 'R' );

	$pdf->SetFont( $font_name, '', 12 );
	

	$pdf->Ln(12);
	// $pdf->Cell( 205, 1, '', 'T', 1, 'L' );
	$pdf->Cell( 30, 4, 'Invoice Number', 0, 0, 'L' );
	$pdf->Cell( 140, 4, ': '.$no_faktur_lengkap, 0, 1, 'L' );
	$pdf->Cell( 30, 4, 'Invoice Date : ', 0, 0, 'L' );
	$pdf->Cell( 170, 4, ': '.$tanggal, 0, 1, 'L' );
	if ($keterangan != '') {
		$pdf->Cell( 30, 4, 'Note : ', 0, 0, 'L' );
		$pdf->Cell( 170, 4, ': '.$keterangan, 0, 1, 'L' );
	}
	
	$pdf->Ln();
	$pdf->SetFont( $font_name_bold, '', 10.5 );


	$pdf->Cell( 1, 6, '', 'TLB', 0, 'L' );
	$pdf->Cell( 29, 6, 'Nama Barang', 'TB', 0, 'L' );
	$pdf->Cell( 1, 6, '', 'TLB', 0, 'L' );
	$pdf->Cell( 29, 6, 'Keterangan', 'TB', 0, 'L' );
	$pdf->Cell( 20, 6, 'Qty', 'TLB', 0, 'C' );
	$pdf->Cell( 10, 6, 'Sat', 'TLB', 0, 'C' );
	$pdf->Cell( 15, 6, 'Qty', 'TLB', 0, 'C' );
	$pdf->Cell( 10, 6, 'Sat', 'TLB', 0, 'C' );
	$pdf->Cell( 30, 6, 'Harga', 'TLB', 0, 'C' );
	$pdf->Cell( 30, 6, 'Jumlah', 'TLB', 0, 'C' );
	$pdf->Cell( 30, 6, 'PPN', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$pdf->SetFont( $font_name, '', 10.5 );
	$i = 1; $g_total = 0;$t_roll = 0;
	foreach ($data_penjualan_detail as $row) {
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 1, 5, '', 'LB', 0, 'L' );
		$pdf->Cell( 29, 5, $row->nama_barang, 'B', 0, 'L' );
		$pdf->Cell( 1, 5, '', 'LB', 0, 'L' );
		$pdf->Cell( 29, 5, $row->nama_warna, 'B', 0, 'L' );
		$pdf->Cell( 20, 5, is_qty_general($row->qty), 'LB', 0, 'C' );
		$pdf->Cell( 10, 5, substr($row->nama_satuan, 0,3), 'LB', 0, 'C' );
		$pdf->Cell( 15, 5, is_qty_general($row->jumlah_roll), 'LB', 0, 'C' );
		$pdf->Cell( 10, 5, substr($row->nama_packaging, 0,3), 'LB', 0, 'C' );
		$t_roll += $row->jumlah_roll;
		$subtotal = $row->harga_jual * ($row->pengali_harga == 1 ? $row->qty : $row->jumlah_roll);
		$dpp = ($row->harga_jual/$ppn_pembagi) * ($row->pengali_harga == 1 ? $row->qty : $row->jumlah_roll);

		$pdf->Cell( 30, 5, number_format($row->harga_jual/$ppn_pembagi,'2',',','.'), 'LB', 0, 'C' );
		$pdf->Cell( 29, 5, number_format($dpp,'2',',','.'), 'LB', 0, 'R' );
		$pdf->Cell( 1, 5, '','B', 0, 'R' );
		$pdf->Cell( 29, 5, number_format($subtotal - $dpp ,'2',',','.'), 'LB', 0, 'R' );
		$pdf->Cell( 1, 5, '','RB', 1, 'R' );
		$g_total += $subtotal; 
		$i++;
		
	}

	$pdf->SetFont( $font_name_bold, '', 11 );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$nowY = $pdf->getY();
	if ($nowY > 116) {
		$pdf->AddPage();
		$pdf->Ln(3);
		$nowY = 7;

	}else{
		$nowY += 4;
	}
	
	$pdf->Cell( 125, 5, '', 0, 0, 'C' );
	$pdf->Cell( 20, 5, 'Subtotal', 0, 0, 'L' );
	$pdf->Cell( 29, 5, number_format($g_total/$ppn_pembagi,'2',',','.'), 'LBT', 0, 'R' );
	$pdf->Cell( 1, 5, '','TB', 0, 'R' );
	$pdf->Cell( 29, 5, number_format($g_total - ($g_total/$ppn_pembagi),'2',',','.'), 'LBT', 0, 'R' );
	$pdf->Cell( 1, 5, '','TRB', 1, 'R' );

	$pdf->SetFont( $font_name_bold, '', 12 );
	if ($diskon != 0) {
		$pdf->Cell( 125, 5, '', 0, 0, 'C' );
		$pdf->Cell( 20, 5, 'DISKON', 0, 0, 'L' );
		$pdf->Cell( 60, 5, number_format($diskon,'2',',','.'), 'LBR', 1, 'C' );
	}

	$pdf->Cell( 125, 5, '', 0, 0, 'C' );
	$pdf->Cell( 20, 5, 'TOTAL', 0, 0, 'L' );
	$pdf->Cell( 60, 5, number_format($g_total,'2',',','.'), 'LBR', 1, 'C' );

	$pdf->SetFont( $font_name_bold, '', 10 );
	$pdf->Text( 7, $nowY, "Terima kasih telah berbelanja di CV. Setia Usaha Nusantara,");
	$pdf->Text( 7, $nowY += 4, "Barang yang sudah dibeli/dipotong tidak dapat ditukar/dikembalikan.");
	$pdf->Text( 7, $nowY += 4, "Pembayaran via transfer ke BCA Bandung");
	$pdf->Text( 7, $nowY += 4, "No Rekening : 7841 75 3333 a.n Setia Usaha Nusantara CV.");

	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 10, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 170, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>