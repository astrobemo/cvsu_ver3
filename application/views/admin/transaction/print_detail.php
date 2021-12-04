<script>

function print_detail(printer_name){
	

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
	<?
		$baris25 = "'".sprintf('%-18.14s %-31.30s %-15.15s %-15.15s ', 'Tanda Terima', '', 'Checker', 'Hormat Kami')."'";
   	?>

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
	   	<?="'".sprintf('%-35.35s', 'NPWP : '.$npwp)."'";?>+'\x09'+
	   	<?='"'.sprintf('%56.54s', 'Yth, '.strtoupper($nama_keterangan) ).'"';?> + 
	   	
	   	//4
	   	'\x0A'+
	   	<?="'".sprintf('%-31.31s', strtoupper($alamat_toko))."'";?>+'\x09'+
	   	<?="'".sprintf('%64.45s', 'NPWP : '.$npwp_customer )."'";?> + 

	   	//5
	   	'\x0A'+
	   	<?="'".sprintf('%-31.31s', "" )."'";?>+'\x09'+
	   	<?="'".sprintf('%64.50s',$alamat1 )."'";?> + 

	   	//6
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
   		<?="'".sprintf('%-64.64s', 'NOTE : '.$keterangan1 )."'";?>+'\x09'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf('%45.45s', $alamat2.' '.strtoupper($kota))."'";?> + 

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
	   	

	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-15.15s', 'Spec ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-15.15s', 'Warna ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-2.2s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'Roll ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-9.9s', 'Total ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-1.1s %-40.40s','', 'Detail ')."'";?>+
		'\x0A'+

	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ '\x0A'+
		

	   	//==============================================================================
	   	<?


	$i = 1; $baris_print = 0;
	foreach ($data_penjualan_detail_group as $row) {

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);
		
		$data_all = explode('=??=', $row->data_all);

		


		foreach ($nama_warna as $key => $value) {
			$total_roll = 0;
			$total = 0;

			$qty_c = array();

			$qty_detail = explode(' ', $data_qty[$key]);
			$roll_detail = explode(',', $roll_qty[$key]);
			$qty_roll_data = explode('??', $data_all[$key]);
			
			$j = 0; 
			foreach ($qty_detail as $key2 => $value2) {
				$total_roll += $roll_detail[$key2];

				if ($roll_detail[$key2] == 0) {
					$roll_detail[$key2] = 1;
				}
				
				for ($l=0; $l < $roll_detail[$key2] ; $l++) { 
					$total += $qty_detail[$key2];
					$qty_c[$j] = number_format($qty_detail[$key2],'2','.',',');
					$j++;
				}
			}

			// print_r($qty_detail);echo '<br/>';
			// print_r($roll_detail);echo '<hr/>';
			asort($qty_c);

			$jml_angka = count($qty_c);
			// print_r($qty_c);
			$qty_c = array_values($qty_c);
			$jml_angka;
			$baris = ceil($jml_angka/10);
			for ($m=0; $m < $baris ; $m++) { 
				if ($m == 0) {
					$nama_barang = $row->nama_barang;
					$nama_warna_print = $value;
				}else{
					$nama_barang = '';
					$nama_warna_print = '';
				}


			}?>

			<?for ($m=0; $m < $baris ; $m++) { 
				if ($m == 0) {
					$nama_barang = $row->nama_barang;
					$nama_warna_print = $value;
					$qty_total = is_qty_general($total);
					$roll_total = $total_roll;

					$total_show = number_format($total,2,',','.');
					$total_roll_show = $total_roll;
				}else{
					$nama_barang = '';
					$nama_warna_print = '';
					$total_show = '';
					$total_roll_show = '';
				}
				?>
				'\x1B' + '\x21' + '\x04'+ // em mode on
			   	<?="'".sprintf('%-15.15s', $nama_barang)."'";?>+ '\x09'+
			   	<?="'".sprintf('%-15.15s', $nama_warna_print)."'";?>+ '\x09'+
			   	<?="'".sprintf('%-2.2s', '|')."'";?>+
			   	<?="'".sprintf('%3.3s', $total_roll_show)."'";?>+ '\x09'+
			   	<?="'".sprintf('%9.9s', $total_show)."'";?>+ '\x09'+
			   	<?="'".sprintf('%-1.1s', '|')."'";?>+

			   		<?for ($n=0; $n < 10; $n++) { 
			   			$k = 10 * $m + $n;
			   			?>
						<?="'".sprintf('%6.6s', (isset($qty_c[$k]) ? is_qty_general($qty_c[$k]) : '' ) )."'";?>+ '\x09'+
			   		<?}?>

			   	'\x0A'+
			<?
			$baris_print++;
			if ($baris_print == 15) {?>
				'\x0A'+
				'\x0A'+
				'\x1B' + '\x21' + '\x01'+
				<?=$baris25;?>+'\x0A'+
			   	'\x1B' + '\x21' + '\x04'+ // em mode on
				'\x0A'+
				'\x0A'+
				'\x0A'+

			<?};

			if ( ($baris_print - 15 ) % 30 == 0 && $baris_print > 15) {?>
				'\x0A'+
				'\x0A'+
				'\x0A'+
			<?};
			}			
		}

		?>
		'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ '\x0A'+
	   	<?
			$baris_print++;

			if ($baris_print == 15) {?>
				'\x0A'+
				'\x0A'+
				'\x1B' + '\x21' + '\x01'+
				<?=$baris25;?>+'\x0A'+
			   	'\x1B' + '\x21' + '\x04'+ // em mode on
				'\x0A'+
				'\x0A'+
				'\x0A'+

			<?};
			if ( ($baris_print - 15 ) % 30 == 0 && $baris_print > 15) {?>
				'\x0A'+
				'\x0A'+
				'\x0A'+
			<?};
	   	?>
		
	<?}?>

	   	//==============================================================================
	   	<?foreach ($penjualan_detail as $row) {
	   		$total += ($row->pengali_harga == 1 ? $row->qty : $row->jumlah_roll ) *$row->harga_jual;
	   	}?>

	   	//23
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-74.74s', 'Terima Kasih telah berbelanja CV. Setia Usaha Nusantara,')."'";?>+'\x09'+
	   	
	   	<?="'".sprintf('%8.8s', 'TOTAL' )."'";?>+'\x09'+
		<?="'".sprintf('%36.36s', number_format($total,'2',',','.'))."'";?> + 

	   	//==============================================================================

	   	//24
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%66.66s', 'Barang yang sudah dibeli/potong tidak dapat ditukar/dikembalikan ')."'";?>+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf('%18.18s', '')."'";?>+

	   	//25
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-74.74s', 'Pembayaran via transfer ke BCA Bandung')."'";?>+'\x09'+
	   	<?="'".sprintf('%8.8s', '' )."'";?>+'\x09'+
	   	
		<?="'".sprintf('%36.36s', '')."'";?> + 

	   	//==============================================================================
	   	//26
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-65.65s', 'No Rekening : 7841 75 3333 a.n Setia Usaha Nusantara CV.')."'";?>+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf('%19.19s', '')."'";?>+
	   	<?="'".sprintf("%'-31s",'')."'";?>+ 
	   
	   	<?if ($baris_print < 25) {?>
	   		'\x0A'+
			'\x0A'+
			'\x0A'+
	   		'\x1B' + '\x21' + '\x01'+ // em mode on
		   	<?
				echo "'".sprintf('%-1.0s %-12.12s %-5.4s %-12.12s %-8.8s %-15.15s ', '','Tanda Terima', '', '', 'Checker','','Hormat Kami')."'";
			?>+
	   	<?};?>
	   	'\x0A'+
		'\x0A'+
		'\x0A'+

	   	/*
	   	<?
			echo "'".sprintf('%-15.12s %-4.4s %-31.30s %-12.11s %-12.11s ', 'TOTAL ROLL', $total_roll, '', 'Total*', number_format($total,'0',',','.'))."'";?> + '\x0A',<?
	   	?>		   	
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ '\x0A',
	  	'\x0A',
		*/
	   	'\x1B' + '\x69',          // cut paper
	   	'\x01' + '\x04' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
	                                                // **for legacy drawer cable CD-005A.  Research before using.
	                                                // see also http://keyhut.com/popopen4.htm
    ];
	console.log(data);

	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });

	// console.log(printer_name);

    webprint.printRaw(data, printer_name);
	
}

</script>