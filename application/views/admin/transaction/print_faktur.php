<script>

function print_faktur(printer_name){
	

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	//1
	   	<?="'".sprintf('%-18.18s','FAKTUR PENJUALAN')."'";?>+'\x09'+
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%48.27s', $no_faktur_lengkap )."'";?> + 
	   	
	   	//2
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	'\x0A'+
	   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+'\x09'+
	   	<?="'".sprintf('%56.48s', 'BANDUNG, '.$tanggal_print)."'";?> + 

	   	//3
	   	'\x0A'+
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+'\x09'+
	   	<?='"'.sprintf('%56.54s', 'Yth, '.strtoupper($nama_keterangan) ).'"';?> + 

	   	//4
	   	'\x0A'+
	   	<?="'".sprintf('%-31.31s', '')."'";?>+'\x09'+
	   	<?="'".sprintf('%64.45s', 'NPWP : '.$npwp_customer )."'";?> + 

	   	

	   	//5
	   	'\x0A'+
	   	<?="'".sprintf('%-31.31s', "" )."'";?>+'\x09'+
	   	<?='"'.sprintf('%64.50s',$alamat1 ).'"';?> + 

	   	//6
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
   		<?="'".sprintf('%-64.64s', 'NOTE : '.$keterangan1 )."'";?>+'\x09'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?='"'.sprintf('%45.45s', $alamat2.' '.strtoupper($kota)).'"';?> + 

	   	//7
	   	'\x0A'+
	   	//8
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-64.64s', $keterangan2 )."'";?>+'\x09'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf('%31.31s', '' )."'";?> + 
	   	
	   	//==============================================================================
	   	//9
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ 

	   	//10
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-4.2s', 'NO ')."'";?>+
	   	<?="'".sprintf('%-35.35s', 'Nama Barang')."'";?>+'\x09'+
	   	<?="'".sprintf('%8.8s', 'QTY')."'";?>+' '+
	   	<?="'".sprintf('%6.3s', '')."'";?>+
	   	<?="'".sprintf('%8.8s', 'QTY')."'";?>+' '+
	   	<?="'".sprintf('%-6.3s', '')."'";?>+'\x09'+
	   	<?="'".sprintf('%-13.13s', 'Hrg Pokok')."'";?>+'\x09'+
	   	<?="'".sprintf('%-14.14s', 'Jumlah ')."'";?>+'\x09'+
	   	<?="'".sprintf('%-11.11s', 'Diskon ')."'";?>+'\x09'+
	   	<?="'".sprintf('%-13.13s', 'PPN ')."'";?>+ 

	   	//11
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ 

	   	//==============================================================================
	   	//12
	   	'\x0A'+
	   	<?
	   	$total = 0; $total_roll = 0; $idx = 1;
	   	$harga_raw_total = 0; $ppn_total = 0;
		$ppn_pembagi = 1+($ppn_berlaku/100);
		$diskon_total = 0;
		
	   	foreach ($penjualan_detail as $row) {
	   		$total += ($row->pengali_harga == 1 ? $row->qty : $row->jumlah_roll ) *$row->harga_jual - $row->subdiskon;
	   		$total_roll += $row->jumlah_roll;?>
	   			'\x1B' + '\x21' + '\x04'+ // em mode on
	   			<?="'".sprintf('%-4.2s', $idx)."'";?>+
	   			<?="'".sprintf('%-35.35s', $row->nama_barang.' '.$row->nama_warna)."'";?>+'\x09'+
			   	<?="'".sprintf('%8.8s', (float)$row->qty )."'";?>+' '+
			   	<?="'".sprintf('%6.3s', $row->nama_satuan )."'";?>+
			   	<?="'".sprintf('%8.8s', (float)$row->jumlah_roll)."'";?>+' '+
			   	<?="'".sprintf('%-6.3s', $row->nama_packaging )."'";?>+'\x09'+
			   	
			   	<?
				$diskon_total += $row->subdiskon;
			   	$harga_total = ($row->pengali_harga == 1 ? $row->qty : $row->jumlah_roll ) *$row->harga_jual - $row->subdiskon;
			   	$harga_raw = $harga_total/$ppn_pembagi;
				$harga_raw_satuan = $row->harga_jual/$ppn_pembagi;
			   	$harga_raw_total += $harga_raw;
			   	$ppn = $harga_total - $harga_raw;
			   	$ppn_total += $ppn;
			   	?>
			   	<?="'".sprintf('%-13.13s', number_format($harga_raw_satuan,'2',',','.'))."'";?>+'\x09'+
			   	<?="'".sprintf('%-14.14s', number_format($harga_raw,'2',',','.'))."'";?>+'\x09'+
			   	<?="'".sprintf('%-11.11s', number_format($row->subdiskon,'2',',','.'))."'";?>+
			   	<?="'".sprintf('%-13.13s', number_format($ppn,'2',',','.'))."'";?>+
			   	'\x0A'+

	   	<?$idx++;}?>
	   	<?
	   	if ($idx > 11) {
	   		$page = 'page 2';
	   		for ($i = $idx; $i <= 18; $i++) {?>
		   		'\x0A'+
		   	<?}
	   	}else{
	   		$page = 'page 1';
	   		for ($i = $idx; $i <= 10; $i++) {?>
		   		'\x0A'+
		   	<?}	
	   	}
	   	;?>

	   	//22
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ 

	   	'\x0A'+
	   	<?if ($idx > 11) {?>
	   		'\x1B' + '\x21' + '\x01'+ // em mode on
			<?echo "'".sprintf('%-1.0s %-12.12s %-5.4s %-12.12s %-5.5s %-15.15s ', '','', '', '','','')."'";?>+
		   	'\x1B' + '\x21' + '\x04'+ // em mode on
		   	'\x09'+
		   	<?="'".sprintf('%30.18s','page 1')."'";?>+

		   	<?for ($i = 0; $i < 3; $i++) {?>
		   		'\x0A'+
		   	<?}
	   	}?>


	   	//23
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-64.64s', 'Terima kasih telah berbelanja di CV. Setia Usaha Nusantara,')."'";?>+'\x09'+
	   	<?="'".sprintf('%8.8s', 'Subtotal' )."'";?>+'\x09'+
	   	
		<?="'".sprintf('%-6.6s', '')."'";?>+ '\x09'+
		<?="'".sprintf('%-14.14s', number_format($harga_raw_total,'2',',','.'))."'";?>+ '\x09'+
		<?="'".sprintf('%-11.11s', number_format($diskon_total,'2',',','.'))."'";?>+ '\x09'+
	   	<?="'".sprintf('%-13.13s', number_format($ppn_total,'2',',','.'))."'";?>+ 

	   	//==============================================================================


	   	//24
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?if ($diskon != 0) {?>
	   	   	<?="'".sprintf('%-74.74s', 'Barang yang sudah dibeli/dipotong tidak dapat ditukar/dikembalikan')."'";?>+'\x09'+
	   		<?="'".sprintf('%8.8s', 'Diskon' )."'";?>+'\x09'+
			<?="'".sprintf('%36.36s', number_format($diskon,'2',',','.'))."'";?> + 
	   	
	   	<?}else{?>
		   	<?="'".sprintf('%66.66s', 'Barang yang sudah dibeli/dipotong tidak dapat ditukar/dikembalikan.')."'";?>+
		   	'\x1B' + '\x21' + '\x01'+ // em mode on
		   	<?="'".sprintf('%18.18s', '')."'";?>+
		   	<?="'".sprintf("%'-31s",'')."'";?> + 
   		<?}?>

	   	//25
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-74.74s', 'Pembayaran via transfer ke BCA Bandung')."'";?>+'\x09'+
	   	<?="'".sprintf('%8.8s', 'TOTAL' )."'";?>+'\x09'+
	   	
		<?="'".sprintf('%36.36s', number_format($total - $diskon,'2',',','.'))."'";?> + 

	   	//==============================================================================
	   	//26
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-65.65s', 'No Rekening : 7841 75 3333 a.n Setia Usaha Nusantara CV.')."'";?>+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf('%19.19s', '')."'";?>+
	   	<?="'".sprintf("%'-31s",'')."'";?>+ 

	   	//==============================================================================
	   	//27-30
	   	'\x0A'+
	   	<?$idx_bayar = 0;
	   	foreach ($data_pembayaran as $row) {
	   		if ($row->pembayaran_type_id == 1) {?>
			   	'\x1B' + '\x21' + '\x04'+ // em mode on
			   	'\x1B' + '\x21' + '\x04'+ // em mode on
			   	<?="'".sprintf('%-74.74s', '')."'";?>+'\x09'+
			   	<?="'".sprintf('%8.8s', $row->nama_bayar )."'";?>+'\x09'+
			   	
				<?="'".sprintf('%36.36s', number_format($row->amount,'2',',','.'))."'";?> + 
			   	'\x0A'+
	   		<?}?>
	   	<?}
	   	if (count($data_pembayaran != 0)) {
		   	for ($idx_bayar=0; $idx_bayar < 1 ; $idx_bayar++) { ?>
		   		'\x0A'+
		   	<?}
	   	}
	   	?>

	   	//31
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
		<?echo "'".sprintf('%-1.0s %-12.12s %-5.4s %-12.12s %-5.5s %-15.15s ', '','Tanda Terima', '', 'Checker','','Hormat Kami')."'";?>+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	'\x09'+
	   	<?="'".sprintf('%30.18s', $page)."'";?>+

	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A',
	   	
	   	// cut paper
	   	
    ];
	console.log(data);
	console.log("<?=$idx?>");

    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}


</script>