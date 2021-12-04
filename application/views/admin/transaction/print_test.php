<script>

function print_test(printer_name){
	
	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	//1
	   	
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	//2
	   	'\x0A'+
	   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+'\x09'+
	   	<?="'".sprintf('%56.48s', 'Kepada Yth,')."'";?> + 

	   	//3
	   	'\x0A'+
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+'\x09'+
	   	<?="'".sprintf('%56.54s', strtoupper($nama_keterangan) )."'";?> + 

	   	//4
	   	'\x0A'+
	   	<?="'".sprintf('%-31.31s', 'TELP:'.$telepon)."'";?>+'\x09'+
	   	<?="'".sprintf('%64.45s', $alamat1)."'";?> + 

	   	//5
	   	'\x0A'+
	   	<?="'".sprintf('%-31.31s', ($fax != '' ? 'FAX:'.$fax : '') )."'";?>+'\x09'+
	   	<?="'".sprintf('%64.45s',$alamat2 )."'";?> + 

	   	//6
	   	'\x0A'+
   		<?="'".sprintf('%-30.30s', 'NPWP : '.$npwp)."'";?>+'\x09'+
	   	<?="'".sprintf('%64.54s', strtoupper($kota))."'";?> + 

	   	//7
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ '\x0A'+
	   	
	   	//8
	   	<?="'".sprintf('%-45.45s', ($po_number != '' ? "PO/Ket : ".$po_number : '') )."'";?>+'\x09'+
	   	<?="'".sprintf('%48.48s', 'INVOICE NO : '.$no_faktur_lengkap)."'";?> + 

	   	//==============================================================================
	   	//9
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ 

	   	//10
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%-2.2s', 'NO ')."'";?>+'\x09'+
	   	<?="'".sprintf('%-54.54s', 'Nama Barang ')."'";?>+'\x09'+
	   	<?="'".sprintf('%8.8s', 'QTY ')."'";?>+' '+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+'\x09'+
	   	<?="'".sprintf('%8.8s', 'QTY ')."'";?>+' '+
	   	<?="'".sprintf('%-4.3s', '')."'";?>+'\x09'+
	   	<?="'".sprintf('%-17.17s', 'Harga ')."'";?>+'\x09'+
	   	<?="'".sprintf('%-12.12s', 'PPN ')."'";?>+ 

	   	//11
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}96s", '')."'";?>+ 

	   	
	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A',
	   	
	   	// cut paper
	   	
    ];

	console.log(data);

	webprint.printRaw(data, printer_name);
	
}

</script>