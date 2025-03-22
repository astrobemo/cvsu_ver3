<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


<style type="text/css">
#tbl-data input[type="text"], #tbl-data select{
	height: 25px;
	width: 50%;
	padding: 0 5px;
}

#qty-table input, #qty-table-edit input, .qty-eceran{
	width: 65px;
	padding: 5px;
}

#qty-table-stok tbody, #qty-table-stok-edit tbody{
	border: 1px solid #ddd;
}

#qty-table-stok tr td, 
#qty-table-stok-edit tr td, 
#qty-table-eceran tr td, 
#qty-table-eceran-edit tr td,
#qty-table-eceran-edit tr th
{
	padding: 2px 5px;
	border: 1px solid #ddd;
}

#qty-table .nama_supplier{
	font-size:0.8em;
}

.stok-info{
	font-size: 1.5em;
	/*position: absolute;*/
	right: 50px;
	top: 30px;
}

.yard-info, .yard-info-edit{
	font-size: 1.5em;
}

.no-faktur-lengkap{
	font-size: 2.5em;
	font-weight: bold;
}

.no-faktur-sub{
	font-size: 1.2em;
	font-weight: bold;
}

.input-no-border{
	border: none;
}

.subtotal-data{
	font-size: 1.2em;
}

.textarea{
	resize:none;
}

#bayar-data tr td{
	font-size: 1.5em;
	font-weight: bold;
	padding: 0 10px 0 10px;
}

#bayar-data tr td input{
	padding: 0 5px 0 5px;
	border: 1px solid #ddd;
}

.eceran-form{
	padding-bottom:5px;
}

.eceran-active{
	background:yellow;
}

.row-stok:hover{
	background:lightblue;
}

.habis{
	cursor:no-drop;
	color:#666;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$penjualan_id = '';
			$customer_id = '';
			$nama_customer = '';
			$gudang_id = '';
			$nama_gudang = '';
			$no_faktur = '';
			$tanggal = date('d/m/Y');
			$tanggal_print = '';
			$ori_tanggal = date('Y-m-d');
			$po_number = '';

			$jatuh_tempo = date('d/m/Y', strtotime("+60 days") );
			$ori_jatuh_tempo = '';
			$status = -99;

			$revisi = 0;

			$diskon = 0;
			$ongkos_kirim = 0;
			$nama_keterangan = '';
			$alamat_keterangan = '';
			$kota = '';
			$keterangan = '';
			$penjualan_type_id = 3;
			$tipe_penjualan = '';
			$customer_id = '';
			$no_faktur_lengkap = '';
			$no_surat_jalan = '';
			$fp_status = 1;

			$g_total = 0;
			$readonly = '';
			$disabled = '';
			$disabled_status = '';
			$alamat_customer = '';
			$npwp_customer = '';

			$ppn_berlaku = 11;
			$ppn_pengali = $ppn_berlaku/100;
			$ppn_pembagi = 1+$ppn_pengali;

			foreach ($penjualan_data as $row) {
				$tipe_penjualan = $row->tipe_penjualan;
				$penjualan_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_keterangan;
				$alamat_customer = $row->alamat_keterangan;
				$npwp_customer = $row->npwp_customer;
				$gudang_id = $row->gudang_id;
				$nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;
				$penjualan_type_id = $row->penjualan_type_id; 
				$po_number = $row->po_number;
				$fp_status = $row->fp_status;
				
				$tanggal_print = date('d F Y', strtotime($row->tanggal));

				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status_cek = 0;
				if ($penjualan_type_id == 2) {
					$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
					if ($row->jatuh_tempo == $row->tanggal) {
						$status_cek = 1;
					}
				}
				$get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
				// print_r($get_jt);
				$jatuh_tempo = is_reverse_date($get_jt);
				$ori_jatuh_tempo = $row->jatuh_tempo;
				$status = $row->status;
				
				$diskon = $row->diskon;
				$ongkos_kirim = $row->ongkos_kirim;
				$status_aktif = $row->status_aktif;
				$nama_keterangan = $row->nama_keterangan;
				$alamat_keterangan = $row->alamat_keterangan;
				$kota = $row->kota;
				$keterangan = $row->keterangan;
				$customer_id = $row->customer_id;
				$no_faktur_lengkap = $row->no_faktur_lengkap;
				$revisi = $row->revisi - 1;
				$no_surat_jalan = $row->no_surat_jalan;
				$ppn_berlaku = get_ppn_berlaku($ori_tanggal);
				$ppn_pengali = $ppn_berlaku/100;
				$ppn_pembagi = 1+$ppn_pengali;
			}

			$nama_bank = '';
			$no_rek_bank = '';
			$tanggal_giro = '';
			$jatuh_tempo_giro = '';
			$no_akun = '';

			foreach ($data_giro as $row) {
				$nama_bank = $row->nama_bank;
				$no_rek_bank = $row->no_rek_bank;
				$tanggal_giro =is_reverse_date($row->tanggal_giro) ;
				$jatuh_tempo_giro = is_reverse_date($row->jatuh_tempo);
				$no_akun = $row->no_akun;
			}

			if ($status != 1) {
				if ( is_posisi_id() != 1 ) {
					$readonly = 'readonly';
				}
			}

			if ($penjualan_id == '') {
				$disabled = 'disabled';
			}

			if ($status != 0) {
				$disabled_status = 'disabled';
			}

			$lock_ = '';
			$read_ = '';
			if (is_posisi_id() == 6) {
				$disabled = 'disabled';
				$readonly = 'readonly';
			}

			$ary_filter = array("\n","\r", "<br>");
			$alamat_keterangan = str_replace($ary_filter," ",$alamat_keterangan);

			$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,46);
		   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
			$last_1 = substr($alamat1, -1,1);
			$last_2 = substr($alamat2, 0,1);

			$positions = array();
			$pos = -1;
			while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
				$positions[] = $pos;
			}

			$max = 47;
			if ($last_1 != '' && $last_2 != '') {
				$posisi =array_filter(array_reverse($positions),
					function($value) use ($max) {
						return $value <= $max;
					});

				$posisi = array_values($posisi);

				$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
			   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
			}

			$keterangan1 = substr(strtoupper(trim($keterangan)), 0,47);
		   	$keterangan2 = substr(strtoupper(trim($keterangan)), 47);
			$last_ket1 = substr($keterangan1, -1,1);
			$last_ket2 = substr($keterangan2, 0,1);

			$positions = array();
			$pos = -1;
			while (($pos = strpos(trim($keterangan)," ", $pos+1 )) !== false) {
				$positions[] = $pos;
			}

			$max = 47;
			if ($last_ket1 != '' && $last_ket2 != '') {
				$posisi_ket =array_filter(array_reverse($positions),
					function($value) use ($max) {
						return $value <= $max;
					});

				$posisi_ket = array_values($posisi_ket);

				$keterangan1 = substr(strtoupper(trim($keterangan)), 0,$posisi_ket[0]);
			   	$keterangan2 = substr(strtoupper(trim($keterangan)), $posisi_ket[0]);
			}
		?>

		<?include_once 'penjualan_list_modal.php';?>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<?if (is_posisi_id() != 6) { ?>
								<a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail');?>" target='_blank' class="btn btn-default btn-sm">
								<i class="fa fa-files-o"></i> Tab Kosong Baru </a>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Penjualan Baru </a>
							<?}?>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<table style='width:100%'>
							<tr>
								<td>
									<table>
										<tr>
											<?if ($penjualan_id != '') { ?>
												<tr>
													<td colspan='3'>
														<?if ($status == 0) { ?>
															<button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin'><i class='fa fa-key'></i> request open</button>
														<?}elseif ($status != -1) { ?>
															<?if (is_posisi_id() != 6 ) { ?>
																<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
															<?}?>
														<?}?>

													</td>
												</tr>
											<?}?>
								    		<td>Status</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($status == -1) { ?>
								    				<span style='color:red'><b>BATAL</b></span>
								    			<?}elseif ($status == 1) {?>
								    				<span style='color:green'><b>OPEN</b></span>
								    			<?}elseif ($status == 0) {?>
								    				<span style='color:orange'><b>LOCKED</b></span>
								    			<?}?>
								    		</td>
								    	</tr>
										<tr>
								    		<td>Tipe</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$tipe_penjualan;?>
								    		</td>
								    	</tr>
								    	<tr>
									    	<!-- po_section -->
								    		<td>PO/Ket</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$po_number;?>
								    		</td>
								    	</tr>
								    	<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
								    	</tr>
								    	<tr  <?=($penjualan_type_id != 2 ? 'hidden' : '' )?> >
								    		<td>Jatuh Tempo</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?
								    				// $dt = strtotime(' +60 days', strtotime($tanggal) );
													// echo $get_jt = ($jatuh_tempo == '' ? date('Y-m-d', $dt) : $row->jatuh_tempo);
								    			?>
								    			<?=$jatuh_tempo;?></td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>Customer</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($penjualan_type_id == 3) { ?>
								    				<?=$nama_keterangan;?> / <span class='alamat_keterangan'><?=$alamat_keterangan;?></span>
								    			<?} else{
								    				echo $nama_customer;
								    			}?>
								    		</td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>NPWP</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$npwp_customer;?>
								    		</td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>Alamat</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$alamat_customer;?>
								    		</td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>Catatan</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$keterangan?>
								    		</td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>FP</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($fp_status == 1) { ?>
								    				<i class='fa fa-check'></i>
								    			<?} else{
								    				echo '';
								    			}?>
								    		</td>
								    	</tr>
								    </table>
								</td>
								<td class='text-right'>
									<div class='note note-info' style="margin-bottom:0px">
										<?if (is_posisi_id() == 1) {?>
											<!-- <h1>TERSDT</h1> -->
										<?}?>
										<span class='no-faktur-lengkap'> <?=$no_faktur_lengkap;?></span><br>
										<?=($no_faktur_lengkap != '' ? 'revisi : <b>'.$revisi.'</b>' : '' );?>
									</div>
									<?foreach ($penjualan_invoice as $row) {?>
										<div class='no-faktur-sub' style="background:<?=$is_toko[$row->toko_id]['color']?>; border-left: 5px solid #ccc; width: 100%; padding:5px 8px; margin-bottom:0px"><?=$row->no_faktur_lengkap?></div>
									<?}?>
									
									
								</td>
							</tr>
						</table>

					    <hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Nos
									</th>
									<th scope="col">
										Nama Barang
										<?if ($penjualan_id !='' && $status == 1 && is_posisi_id() != 6 ) {?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}else if (is_posisi_id()==1) {?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Gudang
									</th>
									<th scope="col">
										Jml
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Jumlah
									</th>
									<th scope="col">
										PPN
									</th>
									<th scope="col" >
										Subtotal
									</th>
									<th scope="col" >
										Diskon
									</th>
									<th scope="col" >
										Total
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$idx =1; $barang_id = ''; $gudang_id_last = ''; $toko_id_last = '';
								$harga_jual = 0; $qty_total = 0; $roll_total = 0;
								$g_total_blmppn = 0; $ppn_total = 0; $diskon_total = 0; 
								$g_total_after = 0;
								foreach ($penjualan_detail as $row) { ?>
									 
									<tr id='id_<?=$row->id;?>' class='item-<?=$row->toko_id?>' style="background-color:<?=$row->color_code;?>">
										<td>
											<?=$idx;?> 
										</td>
										<td>
											<span class='nama_jual'><?=$row->nama_barang;?> <?=$row->nama_warna;?></span> 
											<?$barang_id=$row->barang_id;?>
										</td>
										<td>
											<?=$row->nama_gudang;?>
										</td>
										<td style="<?=($row->pengali_harga == 1 ? 'background:#ddd' : ''); ?>">
											<!-- <input name='qty' class='free-input-sm qty' value="<?=$row->qty;?>">  -->
											<span class='qty' style="<?=($row->pengali_harga == 1 ? 'color:blue;font-weight:bold' : '')?>"><?=(float)$row->qty;?></span> 
											<span class='nama_satuan'><?=$row->nama_satuan;?></span>  
										</td>
										<td style="<?=($row->pengali_harga == 2 ? 'background:#ddd' : ''); ?>">
											<!-- <input name='jumlah_roll' class='free-input-sm jumlah_roll' value="<?=$row->jumlah_roll;?>"> -->
											<span class='jumlah_roll' style="<?=($row->pengali_harga == 2 ? 'color:blue;font-weight:bold' : '')?>"><?=$row->jumlah_roll;?></span> 
											<span class='nama_packaging'><?=$row->nama_packaging;?></span>
										</td>
										<td>
											<span class='harga_jual' hidden><?=$row->harga_jual;?></span> 
											<?if ($row->use_ppn == 1) {?>
												<span style="color:blue;font-weight:bold"><?=number_format($row->harga_jual/$ppn_pembagi,'2','.',',');?></span>
											<?}else{?>
												<span style="color:blue;font-weight:bold"><?=number_format($row->harga_jual,'2','.',',');?></span>
											<?}?>
										</td>
										<td>
											<?
											$total_after = 0;
												if ($row->is_eceran) {
													$subtotal = ( $row->is_eceran == 1 ? $row->qty : $row->jumlah_roll ) * $row->harga_jual;
												}else{
													$subtotal = ( $row->pengali_harga == 1 ? $row->qty : $row->jumlah_roll ) * $row->harga_jual;
												}
												$g_total += $subtotal;
												$harga_jual = (float)$row->harga_jual;
												$qty_total += $row->qty;
												$roll_total += $row->jumlah_roll;
												if ($row->use_ppn == 1) {
													$harga_raw = (float)$subtotal/$ppn_pembagi;
													$ppn = $subtotal - ($harga_raw);
													$ppn_total += $ppn;
												}else{
													$harga_raw = (float)$subtotal;
													$ppn = '';;
												}
												$g_total_blmppn += $harga_raw;
												$diskon_total += $row->subdiskon;
												$total_after += $subtotal - $row->subdiskon;
												$g_total_after += $total_after;
											?>
											<span style="color:blue;font-weight:bold" ><?=number_format($harga_raw,'2','.',',');?></span> 
										</td>
										<td>
											<?if ($row->use_ppn == 1) {?>
												<span class='PPN' style="color:blue;font-weight:bold" ><?=number_format($ppn,'2','.',',');?></span> 
											<?}?>
										</td>
										<td>
											<?=number_format($subtotal,'0','.',',');?> 
											<span class='subtotal' hidden><?=$subtotal;?> </span> 
										</td>
										<td>
											<?=number_format($row->subdiskon,'0','.',',');?> 
											<span class='diskon' hidden><?=$row->subdiskon;?> </span> 
										</td>
										<td>
											<?=number_format($total_after,'0','.',',');?>
											<span class='s_total' hidden><?=$total_after;?></span> 
										</td>
										<td class='hidden-print'>
											<?
											$gudang_id_last=$row->gudang_id;
											$toko_id_last=$row->toko_id;
											$hidden = (is_posisi_id()!=1 ? 'hidden' : 'hidden');
											?>
											<?if ($status == 1 || is_posisi_id() == 1) { ?>
												<?if (is_posisi_id() != 6) { ?>
													<span class='toko_id' <?=$hidden;?>><?=$row->toko_id;?></span>
													<span class='use_ppn' <?=$hidden;?>><?=$row->use_ppn;?></span>
													<span class='gudang_id' <?=$hidden;?>><?=$row->gudang_id;?></span>
													<span class='barang_id' <?=$hidden;?>><?=$row->barang_id;?></span>
													<span class='warna_id' <?=$hidden;?>><?=$row->warna_id;?></span>
													<span class='data_qty' <?=$hidden;?>><?=$row->data_qty;?></span>
													<span class='data_supplier' <?=$hidden;?>><?=$row->data_supplier;?></span>
													<span class='is_eceran' <?=$hidden;?>><?=$row->is_eceran;?></span>
													<span class='is_eceran_mix' <?=$hidden;?>><?=$row->is_eceran_mix;?></span>
													<span class='id' <?=$hidden;?>><?=$row->id;?></span>
													<a href='#portlet-config-qty-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
													<a class="btn-xs btn red btn-detail-remove"><i class="fa fa-times"></i> </a>
												<?}?>
											<?}?>
										</td>
									</tr>
								<?
								$idx++; 
								} ?>

								<tr class='subtotal-data' >
									<td colspan='5' class='text-right'>
									</td>
									<td class='text-left'><b>TOTAL<?//=str_replace('.00', '',$qty_total);?></b></td>
									<td class='text-left'><b><?=number_format($g_total_blmppn,'2','.',',');?></b></td>
									<td class='text-left'><b><?=number_format($ppn_total,'2','.',',');?></b></td>
									<td class='text-left'><b><?=number_format($g_total,'2','.',',');?></b></td>
									<td>
										<?=number_format($diskon,'0','.',',')?> / <?=number_format($diskon/($g_total == 0 ? 1 : $g_total) * 100,'2','.',',')?>
										<?/* if ($status == 1 ) {?>
												<b>Rp. </b><input <?=($status != 1 ? 'readonly' : '');?> class='diskon  text-center' name='diskon' style='width:120px'  value="<?=number_format($diskon,'0','.',',')?>"> /
												<input <?=($status != 1 ? 'readonly' : '');?> class='diskon-persen text-center' name='diskon_persen' style='width:60px' value="<?=number_format($diskon/($g_total == 0 ? 1 : $g_total) * 100,'2','.',',')?>"> %
											<?}else{?>
												Rp.<?=number_format($diskon,'0','.',',')?> /<?=number_format($diskon/($g_total == 0 ? 1 : $g_total) * 100,'2','.',',')?> %
											<?} */?>
									</td>
									<td class='text-left'><b><?=number_format($g_total_after,'2','.',',');?></b></td>
									<td class='hidden-print'></td>
								</tr>
								<tr class='subtotal-data' hidden>
									<td colspan='5' class='text-right'><b></b></td>
									<td class='text-right'><b>SUBTOTAL<?//=str_replace('.00', '',$qty_total);?></b></td>
									<td class='text-center' colspan='3'><b id='subtotal-all'><?=number_format($g_total,'2','.',',');?></b></td>
									<td class='hidden-print'></td>
								</tr>
								<!-- <tr class='subtotal-data'>
									<td colspan='6' class='text-right'><b>DISKON</b></td>
									<td colspan='2' class='text-center'>
										<?if ($status == 1 ) {?>
											<b>Rp. </b><input <?=($status != 1 ? 'readonly' : '');?> class='diskon  text-center' name='diskon' style='width:120px'  value="<?=number_format($diskon,'0','.',',')?>"> /
											<input <?=($status != 1 ? 'readonly' : '');?> class='diskon-persen text-center' name='diskon_persen' style='width:60px' value="<?=number_format($diskon/($g_total == 0 ? 1 : $g_total) * 100,'2','.',',')?>"> %
										<?}else{?>
											Rp.<?=number_format($diskon,'0','.',',')?> /<?=number_format($diskon/($g_total == 0 ? 1 : $g_total) * 100,'2','.',',')?> %
										<?}?>

									</td>
									<td class='hidden-print'></td>
								</tr> -->
								<tr class='subtotal-data' hidden>
									<td colspan='6' class='text-right'><b>GRAND TOTAL</b></td>
									<td colspan='2' class='text-center'><b class='total'><?=number_format($g_total - $diskon,'0','.',',');?> </b> </td>
									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>
						<hr/>
							<p class='btn-detail-toggle' style='cursor:pointer'>
								<b>Detail <i class='fa fa-caret-down'></i></b>
								<?foreach ($is_toko as $key => $value) {
									if ($value['item'] > 0) {?>
										<button class='btn btn-xs' style="background:<?=$value['color']?>; border: 1px solid #ccc; width: 250px; padding:5px 8px"><?=$value['nama'];?> : <?=$value['item']?> item</button>
									<?}?>
								<?}?>
							</p>
						
							<table id='general-detail-table' class='table table-bordered' hidden>
								<thead>
									<tr>
										<th>Barang</th>
										<th>Toko</th>
										<th>Keterangan</th>
										<th>Qty</th>
										<th>Total</th>
										<th>Detail</th>
									</tr>
								</thead>
								<?
								if (is_posisi_id() == 1) {
									//print_r($penjualan_detail);
									# code...
								}
								foreach ($penjualan_detail as $row) {?>
									<tr>
										<td><?=$row->nama_barang?></td>
										<td><?=$is_toko[$row->toko_id]['nama']?></td>
										<td><?=$row->nama_warna?></td>
										<td><?=$row->jumlah_roll?></td>
										<td><?=str_replace('.0000', '',$row->qty)?></td>
										<td><?
											$data_qty = explode('=?=', $row->data_qty);
											$coll = 1;
											foreach ($data_qty as $key => $value) {
												$detail_qty = explode('??', $value);
												for ($i=1; $i <= $detail_qty[1] ; $i++) { 
													echo "<p style='display:inline-flex; width:50px; '>".str_replace('.0000', '', $detail_qty[0])."</p>";
													$coll++;
													if ($coll == 11) {
														echo "<hr style='margin:2px' />";
														$coll = 1;
													}
												}
											}
										?></td>
									</tr>
								<?}?>
							</table>
						<hr/>

						<table style='width:100%'>
							<tr>
								<td>
									<table id='bayar-data'>
										<?
										$bayar_total = 0;
										foreach ($pembayaran_type as $row) { 
											$bayar = null; 
											if (isset($pembayaran_penjualan[$row->id])) {
												$bayar = $pembayaran_penjualan[$row->id];
												if ($row->id == 1) {
													$bayar = $dp_bayar;
													$bayar_total += $dp_bayar;
												}else{
													$keterangan = $pembayaran_keterangan[$row->id];
													$bayar_total += $bayar;
												}
											}

											$stat = ''; $style = '';
											if ($status == 0) {
												$stat = 'readonly';
												$style = 'background:#ddd; border:1px solid #ddd';
											}

											if ($row->id == 1 || $status != 1) {
												if ( $customer_id == '' || $customer_id == 0 || $status != 1) {
													if (is_posisi_id() != 1) {
														$stat = 'readonly';
														$style = 'background:#ddd; border:1px solid #ddd';
													}
												}
											}
											?>
											<?if ($row->id == 1) { ?>
												<tr <?=($penjualan_type_id == 3 ? "hidden" : '');?>>
													<td><?=$row->nama;?><span class='saldo_awal' hidden><?=$saldo_awal;?></span></td>
													<td>
														<a <?=($status == 1 ? "href='#portlet-config-dp' data-toggle='modal'" : '' );?> >
															<input readonly <?=$stat;?> style='<?=$style;?>' value="<?=number_format($bayar,'0','.',',');?>" >
														</a>
														<!--<a data-toggle="popover" style='color:black' data-trigger='focus' data-html="true" data-content="Saldo : <?=number_format($saldo_awal,'0','.',',');?>">
														</a>-->
														<span class='dp_copy' hidden><?=$bayar?></span>
													</td>
												</tr>
											<?}elseif ($row->id == 4) { ?>
												<tr>
													<td><?=$row->nama;?></td>
													<td>
														<input <?=$stat;?> style='<?=$style;?>' class=' bayar-input' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0','.',',');?>">
														<?if ($penjualan_id != '') { ?>
															<a data-toggle="popover" style='color:black' data-trigger='click' data-html="true" data-content="<input <?=$stat;?> style='<?=$style;?>' class='keterangan_bayar' name='keterangan_<?=$row->id;?>' value='<?=$keterangan;?>'>">
																<i class='fa fa-edit'></i>
															</a>
														<?}?>
													</td>
												</tr>
											<?}elseif ($row->id == 5) { ?>
												<tr>
													<td><?=$row->nama;?></td>
													<td>
														<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Hanya untuk tipe kredit pelanggan">
															<input <?=$stat;?> id='bayar_<?=$row->id;?>'  class=' bayar-input bayar-kredit' value="<?=number_format($bayar,'0','.',',');?>">
														</a>
													</td>
												</tr>
											<?}elseif ($row->id == 6) { ?>
												<tr hidden>
													<td><?=$row->nama;?></td>
													<td>
														<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Nama Bank : <b><?=$nama_bank?></b><br/>No Rek : <b><?=$no_rek_bank?></b><br/>No Akun : <b><?=$no_akun?></b><br/>Nama Bank : <b><?=$nama_bank?></b><br/>Tanggal Giro : <b><?=$tanggal_giro?></b><br/>Jatuh Tempo : <b><?=$jatuh_tempo_giro?></b><br/>">
															<input <?=$stat;?> style='<?=$style;?>' class=' bayar-giro' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0','.',',');?>">
														</a>
														<?if ($penjualan_id != '' && is_posisi_id() != 6 && $status != 0) { ?>
															<a data-toggle="modal" href='#portlet-config-giro' style='color:black' style='<?=$style;?>' >
																<i class='fa fa-edit'></i>
															</a>
														<?}?>
													</td>
												</tr>
											<?}else{?>
												<tr>
													<td><?=$row->nama;?></td>
													<td><input <?=$stat;?> style='<?=$style;?>' class=' bayar-input' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0','.',',');?>"></td>
												</tr>
											<?}?>

										<?}?>
									</table>
								</td>
								<?$g_total = $g_total_after?>
								<td style='vertical-align:top;font-size:2.5em;' class='text-right'>
									<table style='float:right;'>
										<tr style='border:2px solid #c9ddfc'>
											<td class='padding-rl-25' style='background:#c9ddfc'>BAYAR</td>
											<td class='padding-rl-10'>
												<b>Rp <span class='total_bayar' style=''><?=number_format($bayar_total,'0','.',',');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ffd7b5'>
											<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
											<td class='text-right padding-rl-10'> 
												<b>Rp <span class='g_total' style=''><?=number_format($g_total - $diskon,'0','.',',');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ceffb4'>
											<td class='padding-rl-25' style='background:#ceffb4'>KEMBALI</td>
											<td class='padding-rl-10'>
												<?
												$kembali_style = '';
												$kembali = $bayar_total - ($g_total - $diskon + $ongkos_kirim);
												if ($kembali < 0 ) {
													$kembali_style = 'color:red';
												}
												?>
												<b>Rp <span class='kembali' style='<?=$kembali_style;?>'><?=number_format($kembali,'0','.',',');?></span></b>
											</td>
										</tr>
									</table>
								</td>
							</tr>

						</table>
						<hr/>
						<?if ($penjualan_id != '' && $status == 0) {?>
							<label>
								<input type='checkbox' id="view-ppn" checked /> Munculkan PPN di nota
							</label>
							<hr/>
						<?}?>
						<div>
							<button type='button'<?if ($idx == 1) { echo 'disabled'; }?> <?=$disabled;?> <?if ($status != 1) {?> disabled <?}?> class='btn btn-lg red hidden-print btn-close'><i class='fa fa-lock'></i> LOCK </button>
			                
			                <button <?=($status != 0 ? 'disabled' : '')?>  type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-faktur-print print-ppn"><i class='fa fa-print'></i> Faktur</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-print-kombi print-ppn"><i class='fa fa-print'></i> Faktur + Detail</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg green btn-surat-jalan print-ppn"><i class='fa fa-print'></i>Surat Jalan</button>

                            <button <?=($status != 0 ? 'disabled' : '')?>  type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-faktur-print-2 print-noppn" style='display:none' ><i class='fa fa-print'></i> Faktur <i class='fa fa-eye-slash'></i> PPN</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-print-kombi-2 print-noppn" style='display:none' ><i class='fa fa-print'></i> Faktur + Detail <i class='fa fa-eye-slash'></i> PPN</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg green btn-surat-jalan-2 print-noppn" style='display:none' ><i class='fa fa-print'></i>Surat Jalan <i class='fa fa-eye-slash'></i> PPN</button>


				            <a <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg yellow-gold btn-print hidden-print'>Faktur PDF <i class='fa fa-download'></i>  </a>

                            <?if (is_posisi_id() == 1) {?>
	                            <!-- <button type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg green btn-surat-jalan-noharga print-ppn"><i class='fa fa-print'></i>SJ No Harga</button> -->
	                            <!-- <button type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-print-`">TEST</button> -->
                            <?}?>
                            <?if ($penjualan_id != '') {
                            	$next_id = '';
                            	foreach ($next_nota as $row) {
	                            	$next_id = $row->id;
	                            	$no_faktur_next = $row->no_faktur;
	                            }
	                            if ($next_id != '') {?>
		                            <a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail');?>?id=<?=$next_id;?>" class="btn btn-lg btn-default" style='float:right'><?=$no_faktur_next;?> <i class='fa fa-angle-double-right'></i></a>
	                            <?}?>
                            <?}?>
						
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets_noondev/js/form-penjualan.js'); ?>" type="text/javascript"></script>


<script>

var table_stok_page = 1;
var use_ppn = 1;
var ppn_berlaku = "<?=get_ppn_berlaku($ori_tanggal);?>";
var ppn_persen = ppn_berlaku/100;
var ppn_pembagi = 1+parseFloat(ppn_persen);
var isBarangMix = false;
var barangSku = <?=json_encode($this->barang_sku_aktif)?>;
var keteranganList = <?=json_encode($this->warna_list_aktif)?>;

console.log('toko',<?=json_encode($is_toko)?>);
/*

1. qty-table itu table qty yang diambil BUKAN table stok
2. qty-table-stok itu table qty stok 
 */

/* 
Rekap QTY consist of
1. qty
2. penjualan_qty_detail_id
3. jumlah_roll
4. supplier_id
 */

 </script>
 <script>
jQuery(document).ready(function() {	

//===========================change toko================================
//===========================general=================================
			eceranFilter();

		<?if($penjualan_id != '' && $status==0){?>

			$("#view-ppn").change(function(){
				if ($('#view-ppn').is(":checked")) {
					$('.print-noppn').hide();
					$('.print-ppn').show();
				}else{
					$('.print-ppn').hide();
					$('.print-noppn').show();
				}
			});

			webprint = new WebPrint(true, {
		        relayHost: "127.0.0.1",
		        relayPort: "8080",
		        readyCallback: function(){
		            
		        }
		    });

			$('.btn-print-action').click(function(){
				var selected = $('#printer-name').val();
				var printer_name = $("#printer-name [value='"+selected+"']").text();
				printer_name = $.trim(printer_name);
				var action = $('[name=print_target]').val();
				// alert(action);	
				if (action == 1 ) {
					print_faktur(printer_name);
				}else if(action == 2){
					print_detail(printer_name);
				}else if(action == 3){
					print_kombinasi(printer_name);
				}else if(action == 4){
					print_surat_jalan(printer_name);
				}else if(action == 5){
					print_surat_jalan(printer_name);
					// print_surat_jalan_noharga(printer_name);
				}else if(action == 6){
					print_test(printer_name);
				}else if (action == '1a' ) {
					print_faktur_2(printer_name);
					// alert('test');
				}else if(action == '2a'){
					print_detail_2(printer_name);
				}else if(action == '3a'){
					print_kombinasi_2(printer_name);
				}
				// alert(printer_name);
			});

			$('.btn-faktur-print').click(function(){
				$('[name=print_target]').val('1');
			});

			$('.btn-print-detail').click(function(){
				$('[name=print_target]').val('2');
				// print_detail();
			});

			$('.btn-print-kombi').click(function(){
				$('[name=print_target]').val('3');
				// print_detail();
			});

			$('.btn-surat-jalan').click(function(){
				$('[name=print_target]').val('4');
				// print_detail();
			});

			$('.btn-surat-jalan-noharga').click(function(){
				$('[name=print_target]').val('5');
				// print_detail();
			});

			$('.btn-print-test').click(function(){
				$('[name=print_target]').val('6');
				// print_detail();
			});

			$('.btn-faktur-print-2').click(function(){
				$('[name=print_target]').val('1a');
			});

			$('.btn-print-kombi-2').click(function(){
				$('[name=print_target]').val('3a');
				// print_detail();
			});

			$('.btn-surat-jalan-2').click(function(){
				$('[name=print_target]').val('4a');
				// print_detail();
			});
		<?}?>

		FormNewPenjualanDetail.init();

		var form_group = {};
		var idx_gen = 0;
		var print_idx = 1;
	   	var penjualan_type_id = '<?=$penjualan_type_id;?>';


		$('[data-toggle="popover"]').popover();


	    $('#warna_id_select_edit, #barang_id_select,#barang_id_select_edit, #customer_id_select, #customer_id_select2').select2({
	        placeholder: "Pilih...",
	        allowClear: true
	    });

	    $('#customer_id_select, #customer_id_select_edit').select2({
	        allowClear: true
	    });

	    <?if ($penjualan_id != '') { ?>
			$('.btn-print').click(function(){
		    	// window.print();
		    });
		<?}?>

	    $("#search_no_faktur").select2({
	        placeholder: "Select...",
	        allowClear: true,
	        minimumInputLength: 1,
	        query: function (query) {
	            var data = {
	                results: []
	            }, i, j, s;
	            var data_st = {};
				var url = "transaction/get_search_no_faktur_jual";
				data_st['no_faktur'] = query.term;
				
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					// console.log(data_respond);
					$.each(JSON.parse(data_respond),function(k,v){
						data.results.push({
		                    id: v.id,
		                    text: v.no_faktur
		                });
					});
		            query.callback(data);
		   		});
	        }
	    });

	    $('.btn-search-faktur').click(function(){
	    	var id = $("#form_search_faktur [name=penjualan_id]").val();
	    	if (id != '') {
	    		$('#form_search_faktur').submit();
	    	};
	    });

	    $('.btn-pin').click(function(){
	    	setTimeout(function(){
		    	$('#pin_user').focus();    		
	    	},700);
	    });

	    $('.btn-request-open').click(function(){
	    	cek_pin();
	    });

	    $('#pin_user').keypress(function (e) {
	        if (e.which == 13) {
	        	cek_pin();
	        }
	    });

//====================================penjualan type=============================


		$('#form_edit_data [name=penjualan_type_id]').change(function(){
			if ($(this).val() == 1) {
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
	   			penjualan_type_id = 1;
	   			// $('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('.edit-alamat-keterangan').hide();
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',false);

			};

			if ($(this).val() == 2) {
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
	   			// $('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('.edit-alamat-keterangan').hide();
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',true);
			};

			if ($(this).val() == 3) {
				$('#form_edit_data .po_section').hide();
				penjualan_type_id = 3;
	   			$('#customer_id_select_edit').val('');
	   			$('#edit-nama-keterangan').show();
	   			$('.edit-alamat-keterangan').show();
	   			$('#edit-select-customer').hide();
	   			$('#fp_status_edit').prop('checked',false);
			};

			$.uniform.update($('#fp_status_edit'));
		});

		$('#customer_id_select').change(function(){
			if (penjualan_type_id == 1 || penjualan_type_id == 2) {
				if ($(this).val() == '') {
					var customer_id = $(this).val('');
					notific8('ruby', 'Customer harus dipilih');
		   			$('#customer_id_select').select2("open");
				}else{
					var customer_id = $(this).val();
				}
			};
		});

		$('#form_add_data [name=penjualan_type_id]').change(function(){
			if ($(this).val() == 1) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$('#add-nama-keterangan').hide();
	   			$('.add-alamat-keterangan').hide();
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',false);
			};

			if ($(this).val() == 2) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$('#add-nama-keterangan').hide();
	   			$('.add-alamat-keterangan').hide();
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',true);
	   			// alert($('#fp_status_add').is(':checked'));

			};

			if ($(this).val() == 3) {
				$('#form_add_data .po_section').hide();
				// $('#form_add_data .customer_section').hide();
	   			$('#customer_id_select').val('');
	   			$('#add-nama-keterangan').show();
	   			$('.add-alamat-keterangan').show();
	   			$('#add-select-customer').hide();
	   			$('#fp_status_add').prop('checked',false);
			};

			$.uniform.update($('#fp_status_add'));

		});

//====================================get harga jual barang====================================

		$('#eceran-cek').change(function(){
			eceranFilter();
		});

	    $('#barang_id_select').change(function(){
	    	var barang_id = $('#barang_id_select').val();
	   		var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
			const keteranganSelected = getKeterangan(barangSku, barang_id);
			
			$('#warna_id_select').empty();
			const dropdown = document.querySelector(`#warna_id_select`);
			let mEcer;
			keteranganList.forEach(function(item) {
				if (keteranganSelected.includes(item.id) && item.id != 888) {
					const option = document.createElement('option');
					option.value = item.id;
					option.text = item.warna_jual;
					dropdown.appendChild(option);
				}else if(item.id == 888){
					mEcer = item;
				}
			});

			if (mEcer) {
				const option = document.createElement('option');
				option.value = mEcer.id;
				option.text = mEcer.warna_jual;
				dropdown.appendChild(option);
			}
			
			if (penjualan_type_id == 3) {
				$('#form_add_barang [name=harga_jual]').val((data[2]));
				harga_jual = data[2];
				if (harga_jual != 0 ) {
					harga_jual_add_change($('#form_add_barang').find(".harga_jual_add"));
				}else{
					$('#form_add_barang').find('.harga_jual_add_noppn').val(0);
				}
			}else{
				var data_st = {};
				data_st['barang_id'] = $('#form_add_barang [name=barang_id]').val();
				data_st['customer_id'] =  "<?=$customer_id;?>";
				var url = "transaction/get_latest_harga";

				let harga_jual = 0;
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond > 0) {
						$('#form_add_barang [name=harga_jual]').val((data_respond));
					}else if(data[2] > 0){
						$('#form_add_barang [name=harga_jual]').val((data[2]));
						<?if (is_posisi_id() == 1) {?>
							// alert(data);
						<?}?>
						// harga_jual = data[2];
					}else{
						$('#form_add_barang [name=harga_jual]').val(0);
					}

					// alert("respond="+data_respond);
					if ($('#form_add_barang').find(".harga_jual_add").val() != 0) {
						harga_jual_add_change($('#form_add_barang').find(".harga_jual_add"));
					}else{
						$('#form_add_barang').find('.harga_jual_add_noppn').val(0);
					}
				});
			}

			$('#eceran-mix').val(data[5]);
			isBarangMix = (data[5] == 1 ? true : false);

			$('#form_add_barang [name=satuan]').val(data[0]);
			$('#nama-satuan-keterangan').html('per '+data[0]);
			$('#form_add_barang [name=pengali_harga]').val(data[3]);
			$('#warna_id_select').select2('open');
			$('#qty-table-eceran').closest('td').find('.nama_satuan').html(data[0]);

			$('#qty-table').closest('td').find('.nama_satuan').html(data[0]);
			$('#qty-table').closest('td').find('.nama_packaging').html(data[1]);

			$('#qty-table-stok').closest('td').find('.nama_satuan').html(data[0]);
			$('#qty-table-stok').closest('td').find('.nama_packaging').html(data[1])

	    }); 

		$(".harga_jual_add").change(function(){
			// alert('test');
			harga_jual_add_change($(this));
		});

		$(".harga_jual_add_noppn").change(function(){
			harga_jual_add_noppn_change($(this));

		});

	    $('#warna_id_select').change(function(){
	    	$('#form_add_barang [name=harga_jual]').focus();
			const w = $('#warna_id_select').val();
			if (w == 888 && isBarangMix == true) {
				// alert(w);	
				$("#eceran-cek").prop('checked',true);
				$.uniform.update($('#eceran-cek'));
				// $("#eceran_cek")
			}
			eceranFilter()
	    });

	    $('.btn-cek-harga').click(function(){
	    	var data = {};
	    	data['barang_id'] = $('#form_add_barang [name=barang_id]').val();
	    	var penjualan_type_id = parseInt("<?=$penjualan_type_id;?>");
	    	var customer_id = '';
	    	if (penjualan_type_id != 3) {
	    		customer_id = "<?=$customer_id;?>";
	    	};
	    	data['customer_id'] =  customer_id;
	    	var url = 'transaction/cek_history_harga';
	    	if (data['barang_id'] != '') {
	    		var tbl = '<table>';
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		console.log(data_respond)
		    		var isi_tbl = '';
					$.each(JSON.parse(data_respond),function(i,v){
						// alert(i +" == "+v);
						isi_tbl += "<tr>"+
							"<td>"+date_formatter(v.tanggal)+"</td>"+
							"<td> : </td>"+
							"<td>"+currency.rupiah(v.harga_jual)+"</td>"+
							"</tr>";
					});

					if (isi_tbl !='') {
						tbl += isi_tbl + "</table>";
				    	$('#data-harga').html(tbl);			
					}else{
				    	$('#data-harga').html("no data");
					};

		   		});
	    	}else{
	    		$('#data-harga').html("no data");
	    	}
	    	
	    });


//====================================modal barang=============================
	
		var barang_id = "<?=$barang_id;?>";
		var gudang_id_last = "<?=$gudang_id_last;?>";
		var toko_id_last = "<?=$toko_id_last;?>";
		var idx = "<?=$idx;?>";
		var harga_jual = "<?=number_format($harga_jual,'0','.',',');?>";


		<?if ($status == 1 && is_posisi_id() != 6) {?>
			var map = {220: false};
			$(document).keydown(function(e) {
			    if (e.keyCode in map) {
			        map[e.keyCode] = true;
			        if (map[220]) {
			        	// alert(idx);
			            $('#portlet-config-detail').modal('toggle');
			            if (idx == 1) {
			            	setTimeout(function(){
					    		$('#barang_id_select').select2("open");
					    	},700);
			            }else{
			            	cek_last_input(gudang_id_last,barang_id, harga_jual, toko_id_last);
			            }
			        }
			    }
			}).keyup(function(e) {
			    if (e.keyCode in map) {
			        map[e.keyCode] = false;
			    }
			});
		<?};?>

		$('.btn-brg-add').click(function(){
	    	// var select2 = $(this).data('select2');
	    	// alert(harga_jual);
		    if (idx == '1') {
	        	setTimeout(function(){
		    		$('#barang_id_select').select2("open");
		    	},700);
	        }else{
	        	cek_last_input(gudang_id_last,barang_id, harga_jual, toko_id_last);
	        }
	    });


//====================================update harga=============================    
	
		$('#general_table').on('change','[name=harga_jual]', function(){
			var ini = $(this).closest('tr');
			var data = {};
			data['id'] = ini.find('.id').html();
			data['harga_jual'] = $(this).val();
			var url = "transaction/update_penjualan_detail_harga";
			var qty = ini.find('.qty').html();
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					var subtotal = qty*data['harga_jual'];
					ini.find('.subtotal').html(currency.rupiah(subtotal));
					update_table();
				}else{
					bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
						if(respond){
							window.location.reload();
						}
					});
				};
	   		});		
		});

//====================================btn save=============================    


	    $('.btn-brg-save').click(function(){

			let isEceran = $('#eceran-cek').is(':checked');

	    	var ini = $(this);

			if(!isEceran){
				var yard = reset_number_comma($('.yard_total').html());
				if( yard > 0){
					$('#form_add_barang').submit();
					btn_disabled_load(ini);
				}
			}else{
				let totalAmbil = $("#qty-table-eceran .total-ambil").html();
				if(parseFloat(totalAmbil) >0 ){
					$('#form_add_barang').submit();
					btn_disabled_load(ini);
				}

			}
	    });


	    $('.btn-save').click(function(){
	    	var ini = $(this);
	    	var penjualan_type_id = $('#form_add_data [name=penjualan_type_id]').val();
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_add_data [name=customer_id]').val() != ''){
	    				$('#form_add_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
	    			$('#form_add_data').removeAttr('target');
	    			$('#form_add_data').submit();
	    			btn_disabled_load($(this));
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	    var idx_submit = 1;
	    $('.btn-save-tab').click(function(){
	    	let ini = $(this);
	    	let penjualan_type_id = $('#form_add_data [name=penjualan_type_id]').val();
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_add_data [name=customer_id]').val() != ''){
	    				$('#form_add_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
					idx++;
	    			$('#form_add_data').attr('target','_blank');
	    			$('#portlet-config').modal('toggle');
	    			$('#form_add_data [name=nama_keterangan]').val('');
	    			btn_disabled_load($('.btn-save-tab'));
	    			setTimeout(function(){
	    				if (idx_submit == 2) {
			    			$('#form_add_data').submit();
	    				}else{
	    					idx_submit = 2;
	    				};
		    			$(".btn-active").prop('disabled',false);
					    $('.btn-save-tab').html("Save & New Tab");
					    // alert(idx_submit);
	    			},2000);
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	    $('.btn-edit-save').click(function(){
	    	var penjualan_type_id = $('#form_edit_data [name=penjualan_type_id]').val();
	    	if ($('#form_edit_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_edit_data [name=customer_id]').val() != ''){
	    				$('#form_edit_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
	    			$('#form_edit_data').submit();
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

//====================================bayar==========================================
		var saldo_awal ='<?=$saldo_awal;?>';
		<?if ($penjualan_id != '') {?>

			$('.bayar-input').dblclick(function(){
				var id_data = $(this).attr('id').split('_');
				var penjualan_type_id = "<?=$penjualan_type_id?>";
				var ini = $(this);

				if ($(this).val() == 0 || $(this).val() == '' ) {
					var g_total = reset_number_comma($('.g_total').html());
					var total_bayar = reset_number_comma($('.total_bayar').html());
					var sisa = parseInt(g_total) - parseInt(total_bayar);

					if (sisa > 0) {
						if ($(this).hasClass('bayar-kredit') && penjualan_type_id != 2) {

						}else{
							$(this).val(change_number_format(sisa));
							var data = {};
							data['pembayaran_type_id'] = id_data[1];
							data['penjualan_id'] = '<?=$penjualan_id?>';
							data['amount'] = ini.val();
							var url = 'transaction/pembayaran_penjualan_update';
							update_db_bayar(url, data);
						};
					};
					
				};
			});

			var bayar = true;
			$('#bayar-data tr td').on('change','input', function(){
				var id_data = $(this).attr('id').split('_');
				if (id_data[1] == 1) {
					var s_awal = reset_number_comma(saldo_awal);
					var isi = $(this).val();
					var dp_initial = reset_number_comma($('.dp_copy').html());
					var sisa = parseInt(s_awal) + dp_initial - reset_number_comma(isi);
					// alert(s_awal+'+'+dp_initial+'+'+isi);
					if (sisa >= 0) {
						// alert('true');
						bayar = true;
					}else{
						$(this).val(0);
						bayar == false;
						alert('Saldo Tidak Cukup');
					};
				};

				if (bayar) {
					var data = {};
					data['pembayaran_type_id'] = id_data[1];
					data['penjualan_id'] = '<?=$penjualan_id?>';
					data['amount'] = $(this).val();
					var penjualan_type_id = "<?=$penjualan_type_id?>";
					if (data['pembayaran_type_id'] == 5 && penjualan_type_id != 2 ) {
						data['amount'] = 0;
						$(this).val(0);
						alert("Tipe bukan kredit pelanggan");
					}
					var url = 'transaction/pembayaran_penjualan_update';
					update_db_bayar(url, data);

					
				};
			});
		<?};?>

		$(document).on('change', '.keterangan_bayar',function(){
			var data = {};
	    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
	    	data['keterangan'] = $(this).val();
	    	var url = 'transaction/pembayaran_transfer_update';
	    	
	    	// alert(data['keterangan']);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					// update_table();
				}else{
					bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
						if(respond){
							window.location.reload();
						}
					});
				};
	   		});
		});

//===================================change===========================================

	    <?if ($penjualan_id != '') { ?>
	    	$(document).on('change','.diskon, .diskon-persen, .ongkos_kirim, .keterangan ', function(){
	    		var value = $(this).val();
	    		var name = $(this).attr('name');
	    		if ($(this).attr('name') != 'keterangan' && $(this).attr('name') == 'diskon_persen') {
	    			value = reset_number_comma(value);
	    		};

	    		if ($(this).attr('name') == 'diskon_persen') {
	    			value = value.toString().replace(',','.');
	    			var diskon = reset_number_comma($('#subtotal-all').html()) * value/100;
	    			$('.diskon').val(diskon);
	    			name = 'diskon';
	    			value = diskon;
	    		};

	    		if ($(this).attr('name') == 'diskon') {
	    			value = reset_number_comma(value);
	    			// alert(value);
	    			value = value.toString().replace(',','.');
	    			// alert(value);
	    			var diskon = value / reset_number_comma($('#subtotal-all').html()) * 100;
	    			diskon = diskon.toFixed(2)
	    			diskon = diskon.toString().replace('.',',');
	    			$('.diskon-persen').val(diskon);
	    		};

		    	var ini = $(this).closest('tr');
		    	var data = {};
		    	data['column'] = name
		    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
		    	data['value'] = value;
		    	var url = 'transaction/penjualan_data_update';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		console.log(data_respond);
					if (data_respond == 'OK') {
						update_table();
					}else{
						bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
							if(respond){
								window.location.reload();
							}
						});
					};
		   		});
		    });
	    <?}?>

	    <?if ($penjualan_id != '') {?>	
	    	$('.btn-close').click(function(){
				let toko_list = [];
				let total_toko = [];
				<?foreach($this->toko_list_aktif as $row){?>
					toko_list.push(<?=$row->id?>);
				<?}?>
				<?foreach ($is_toko as $key => $value) {?>
					total_toko.push(<?=$value['item']?>);
				<?}?>
	    		var kembali = reset_number_comma($('.kembali').html());
	    		var g_total = reset_number_comma($('.g_total').html());
	    		var tanggal = "<?=$ori_tanggal;?>";
	    		var id = "<?=$penjualan_id;?>";
	    		if (g_total <= 0) {
	    			bootbox.alert("Error! Total tidak boleh 0");
	    		}else {
	    			if (kembali >= 0 ) {
	    				window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal+"&list_toko="+toko_list.join(',')+"&total_toko="+total_toko.join(','));
	    			}else{
	    				bootbox.alert('Kembali tidak boleh minus');
	    			}
	    		}
		    });
	    <?}?>

//=====================================remove barang=========================================
		$('#general_table').on('click','.btn-detail-remove', function(){
			var ini = $(this).closest('tr');
			bootbox.confirm("Yakin mengahpus item ini?", function(respond){
				if (respond) {
					var data = {};
					data['id'] = ini.find('.id').html();
					var url = 'transaction/penjualan_list_detail_remove';
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						if (data_respond == "OK") {
							ini.remove();
							window.location.reload();
							// update_table();
						}else{
							alert("Error");
						}
					}); 
				};
			});
		}) ;  

//=====================================bayar giro=========================================
		$(".btn-save-giro").click(function(){
			if ($('#form-data-giro [name=nama_bank]').val() != '' && $('#form-data-giro [name=no_rek_bank]').val() != '' && $('#form-data-giro [name=tanggal_giro]').val() != '' && $('#form-data-giro [name=jatuh_tempo]').val() != '' && $('#form-data-giro [name=no_akun]').val() != '' ) {
				$('#form-data-giro').submit();
			}else{
				alert("mohon lengkapi data giro")
			};
		});

//=====================================bayar dp=========================================

		$('#dp_list_table').on('change','.dp-check', function(){
			let ini = $(this).closest('tr');
			// alert($(this).is(':checked'));
			if($(this).is(':checked')){
				let dp_nilai = reset_number_comma(ini.find('.amount').html());
				ini.find('.amount-bayar').prop('readonly',false);
				ini.find('.amount-bayar').val(dp_nilai);
			}else{
				ini.find('.amount-bayar').prop('readonly',true);
				ini.find('.amount-bayar').val(0);
			}
			dp_table_update();
		});

		$('#dp_list_table').on('change','.amount-bayar', function(){
			let ini = $(this).closest('tr');
			dp_table_update();
		});
		
		$('.btn-save-dp').click(function(){
			$('#form-dp').submit();
		});



//======================================qty add manage====================================

		// nambah baris table kalo kurang
	    $(".btn-add-qty-row").click(function(){
	    	var baris = `<tr><td><input name='qty'></td>
							<td><input name='jumlah_roll'></td>
							<td><span class='nama_supplier'></span></td>
							<td hidden>
								<input name='supplier_id' value=''>
								<span class='qty-get'></span>
								<span class='roll-get'></span>
							</td>
						</tr>`;
	    	$('#qty-table').append(baris);
	    });

		function setQtyAdd(){
			data_result = table_qty_update('#qty-table').split('=*=');
			// console.log('ds', data_result);
	    	let total = parseFloat(data_result[0]);
	    	let total_roll = parseFloat(data_result[1]);
	    	let rekap = data_result[2];
	    	if (total > 0) {
				$("#qty-add-dismiss").attr('disabled',false);
			    $('.btn-brg-save').attr('disabled',false);
	    	}else{
				$("#qty-add-dismiss").attr('disabled',true);
	    		$('.btn-brg-save').attr('disabled',true);
	    	}

	    	$('.yard_total').html(total.toFixed(2));
	    	$('.jumlah_roll_total').html(total_roll);
	    	$('#form_add_barang [name=rekap_qty]').val(rekap);
			$('#qty-add').val(total);
			$('#subqty-add').val(total);
			$('#subroll-add').val(total_roll);
			
			table_stok_update('#stok-info-add');
			setDiskonAdd();
		}
		
	    $("#qty-table").on('change','[name=qty]',function(){
	    	let ini = $(this);
			// subtotal_on_change(ini);
			
			change_qty_update(ini,'#qty-table-stok');
			setQtyAdd();

	    });

		$("#qty-table").on('change','[name=jumlah_roll]',function(){
	    	let ini = $(this).closest('tr');
			
			change_roll_update($(this), '#qty-table-stok');
			setQtyAdd();

	    });

		
	    $('#qty-table-stok').on('click','tr', function(){
	    	var ini = $(this);
			let isEceran = $('#eceran-cek').is(':checked');
			
			if(!isEceran){
				change_click_stok(ini, '#qty-table');
				setQtyAdd();

			}else{

			}	    	

	    });

	    $('#qty-table').on('input', 'input', function(){
	    	var qty = $(this).val();
	    	if ($(this).val() != '') {
	    		qty = qty.replace(',','.');
	    		$(this).val(qty);
		    	var class_qty = qty.replace('.','');
		    	$('#qty-table-stok tbody tr').hide();
		    	$('[class*=main]')
		    	$('#qty-table-stok tbody [class*='+class_qty+']').closest('tr').not('.habis').show();
	    	};

	    });

	    $('#qty-table').on('focusin','input', function(){
	    	$('#qty-table-stok tbody tr').hide();
	    	$('#qty-table-stok tbody tr').not('.habis').show();
	    });



//=====================================qty edit=========================================
	
	// nambah baris table edit kalo kurang
	$(".btn-add-qty-row-edit").click(function(){
		var baris = `<tr><td><input name='qty'></td>
						<td><input name='jumlah_roll'></td>
						<td><span class='nama_supplier'></span></td>
						<td hidden>
							<input name='supplier_id' value=''>
							<span class='qty-get'></span>
							<span class='roll-get'></span>
						</td>
					</tr>`;
		$('#qty-table-edit').append(baris);
	});

	$('#general_table').on('click','.btn-edit', function () {
		let ini = $(this).closest('tr');
		let form = '#form_qty_edit';
		let table_qty = $("#qty-table-edit");
		let table_stok = $("#qty-table-stok-edit");
		const data_qty = ini.find('.data_qty').html().split('=?=');
		let data_supplier = ini.find('.data_supplier').html().split('=?=');

		let toko_id = ini.find('.toko_id').html();
		let gudang_id = ini.find('.gudang_id').html();
        let warna_id = ini.find('.warna_id').html();
        let barang_id = ini.find('.barang_id').html();
        var isEceran = ini.find('.is_eceran').html();
        const use_ppn = ini.find('.use_ppn').html();
		const isEceranMix = ini.find(".is_eceran_mix").html();
		// alert(isEceranMix)

		$("#barang-id-edit").val(barang_id);
		$("#warna-id-edit").val(warna_id);
		$("#eceran-mix-edit").val(isEceranMix);

		let harga_jual = ini.find('.harga_jual').html();
		
		let subqty = ini.find('.qty').html();
		let subroll = ini.find('.jumlah_roll').html();

		let subtotal = ini.find('.subtotal').html();
		let diskon = ini.find('.diskon').html();
		let harga_noppn = harga_jual/ppn_pembagi;

		table_qty.find('[name=qty]').each(function(){
			var itu = $(this).closest('tr');
			itu.find('[name=qty]').val('');
			itu.find('[name=jumlah_roll]').val('');
			itu.find('[name=penjualan_type_id]').val('');
			itu.find('.qty-get').html('');
			itu.find('roll-get').val('');
		});

		table_qty.closest('td').find('.nama_satuan').html(ini.find('.nama_satuan').html());
		table_qty.closest('td').find('.nama_packaging').html(ini.find('.nama_packaging').html());

		table_stok.closest('td').find('.nama_satuan').html(ini.find('.nama_satuan').html());
		table_stok.closest('td').find('.nama_packaging').html(ini.find('.nama_packaging').html());

		diskon = (diskon.length == 0 ? 0 : diskon);

		const tnp = harga_jual/ppn_pembagi;
		console.log(tnp.toFixed(2));
		$("#harga_jual_edit_noppn").val((tnp.toFixed(2)));
		$("#harga_jual_edit").val(currency.rupiah(harga_jual));

		$("#subtotal-edit-text").val(currency.rupiah(subtotal));
		$("#subtotal-edit").val(subtotal);
		$("#subdiskon-edit").val(currency.rupiah(diskon));
		$("#subtotal-grand-edit").val(currency.rupiah(subtotal-diskon));
		$("#subqty-edit").val(subqty);
		$("#subroll-edit").val(subroll);
		$("#ppn-value-edit").val(use_ppn);
		
		// console.log('stot', $("#subqty-edit").val(),subroll);

		harga_jual_add_change($(form+" [name=harga_jual]"));

		if (use_ppn != 1) {
			$("#harga-dpp-group-edit").hide('fast')
		}else {
			$("#harga-dpp-group-edit").show('slow')
		}

		var penjualan_list_detail_id = ini.find('.id').html();
		$(form+' [name=penjualan_list_detail_id]').val(penjualan_list_detail_id);
		$(form+' [name=penjualan_id]').val("<?=$penjualan_id;?>");
		$(form+' [name=rekap_qty]').val(ini.find('.data_qty').html());
		$("#eceran-cek-edit").val(isEceran);


		$.each(data_qty,function(i,v){
			var urai = v.split('??');
    		var qty_get = parseFloat(urai[0]);
    		var roll_get = urai[1];
    		var supplier_id = urai[3];
    		var penjualan_qty_detail_id = urai[2];
    		var nama_supplier = data_supplier[i];

    		$('#qty-table-edit tbody tr').each(function(){
	    		var qty = $(this).find('[name=qty]').val();
	    		var jumlah_roll = $(this).find('[name=jumlah_roll]').val();
	    		if (jumlah_roll == '' && qty == '') {
	    			$(this).find('[name=qty]').val(qty_get);
	    			$(this).find('[name=jumlah_roll]').val(roll_get);
	    			$(this).find('[name=supplier_id]').val(supplier_id);
	    			$(this).find('.nama_supplier').html(nama_supplier);
	    			$(this).find('.qty-get').html(qty_get);
	    			$(this).find('.roll-get').html(roll_get);
	    			$(this).find('.penjualan_qty_detail_id').val(penjualan_qty_detail_id);
	    			return false;
	    		};
	    	});
    	});

		let data = {};
        

        data['toko_id'] = toko_id;
        data['gudang_id'] = gudang_id;
        data['barang_id'] = barang_id;
        data['warna_id'] = warna_id;
        data['is_eceran'] = isEceran;
		data['penjualan_list_detail_id'] = penjualan_list_detail_id;
		data['tanggal'] = "<?=$tanggal;?>";

		// alert(isEceran);

        // var url = "transaction/get_qty_stock_by_barang_detail";
        var url = "stok/stok_general/get_qty_stock_by_barang_detail";
        ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
            var qty = 0;
            var jumlah_roll = 0;
            let table_stok = '';
            let table_stok_array = [];
			let qty_eceran = 0;
			let btn_supplier = '';
            let idx = 1;
            let qty_row = 0;
            let qty_stok = 0;
            let status = '';
            let total_page = 1;
            let tombol_page = '';
			var supplier_id = '';
            $("#qty-table-stok-edit tbody").html('');
			var tp = 0;
			if (parseInt(isEceran) ) {
				tp = 1;
			}
			
			eceranEditFilter(tp);
            $.each(JSON.parse(data_respond), function(k,v){
				
				if (k==0) {

					for (let i = 0; i < v.length; i++) {
						// console.log(v[i]);
						supplier_id = v[i].supplier_id;
						if (typeof table_stok_array[supplier_id] === 'undefined') {
							table_stok_array[supplier_id] = [];
							const nama_supplier = (supplier_id != 0 ? v[i].nama_supplier : 'none');
							btn_supplier += `<button style='margin:0px 10px 5px 0px' class="btn btn-xs default btn-show-stok-edit" id='btn-edit-supplier-${supplier_id}' onclick="showStokSupplierEdit('${supplier_id}')">${nama_supplier}</button>`
						}
						qty += parseFloat(v[i].qty);
						jumlah_roll += parseFloat(v[i].jumlah_roll);
						status = ((v[i].jumlah_roll <= 0) ? 'habis' : '');
						page_idx = parseInt(idx/10) +  parseInt((idx % 10 != 0 ) ? 1 : 0);
						var class_qty = parseFloat(v[i].qty);
						class_qty = class_qty.toString().replace('.','');
						let btnEcer = '';
						// console.log(isEceran==true && parseFloat(v[i].jumlah_roll) > 0);
						if(isEceran == true && parseFloat(v[i].jumlah_roll) > 0){
							btnEcer = `<button onclick="mutasiToEceranEdit('${class_qty}','${v[i].qty}')">add to eceran</button>`;
							// $("#eceran-cek-edit").val('1')
						}else{
							// $("#eceran-cek-edit").val(0)
						}

						console.log('1',v[i].nama_supplier);

						table_stok += `<tr data-supplier='${supplier_id}' class='row-stok row-${v[i].qty} page-${v[i].qty} baris-table ${status} '>
							<td class='idx-${class_qty}'><span class='qty-stok'>${parseFloat(v[i].qty)}</span></td>
							<td><span class='roll-stok'>${parseFloat(v[i].jumlah_roll)}</span> </td>
							<td>
								<span class='nama_supplier'> ${(v[i].nama_supplier != null ? v[i].nama_supplier : 'none') }</span>
								<span class='supplier_id' hidden>${supplier_id}</span> 
							</td>
							<td>${btnEcer}</td>
						</tr>`;
						
						qty_stok += parseFloat(v[i].qty*v[i].jumlah_roll);
						qty_row = idx;
						idx++;
						
					}
				}else{
					if (isEceran && k == 1) {

						let table_eceran = '';
						let totalAmbil = 0;
						if (warna_id == 888 && isEceranMix == true) {
							const button = `<button class='btn-xs btn green' onclick="addRowMix('#qty-table-eceran-edit')"><i class='fa fa-plus' ></i></button>`;
							data_qty.forEach((dt,index) => {
								const c = dt.split("??");
								totalAmbil += parseFloat(c[0]);
								table_eceran += (`<tr data-supplier='0' class=''>
									<td><input class='qty-row text-center' onChange="eceranMixEdit()" style='width:80px' value="${parseFloat(c[0])}"></td>
									<td>
										<span class='nama_supplier'> none</span>
										<span class='supplier_id' hidden>0</span> 
										<span class='qty_id' hidden>${c[4]}</span> 
									</td>
									<td>${index == 0 ? button : ''}</td>
								</tr>`);
							});
							$("#qty-table-eceran-edit .total-ambil").html(totalAmbil);
						}else{
							for (let i = 0; i < v.length; i++) {
								qty_eceran += parseFloat(v[i].qty);
								table_eceran += `<tr style="font-size:1.2em;">
									<td class='eceran-stok' style='padding:2px 10px'>${parseFloat(v[i].qty)}</td>
									<td><input class='text-center eceran-qty' style='width:55px; border:none' onchange="ambilEceranEdit()" value="${parseFloat(v[i].qty_jual)}"></td>
									<td class='eceran-sisa' style='padding:2px 10px'>${v[i].qty - v[i].qty_jual}</td>
									<td hidden><span class='stok_eceran_qty_tipe'>${v[i].tipe}</span> </td>
									<td hidden><span class='stok_eceran_qty_id'>${v[i].stok_eceran_qty_id}</span></td>
									<td >
										<span class='nama_supplier'> ${(v[i].nama_supplier != null ? v[i].nama_supplier : 'none') }</span>
										<span hidden class='supplier_id'>${v[i].supplier_id}</span> 
									</td>
									<td hidden><span class='penjualan_qty_detail_id'>${v[i].penjualan_qty_detail_id}</span></td>
									</tr>`;
							}
							$("#stok-eceran-edit").find(".stok-qty-eceran").text(parseFloat(qty_eceran));
						}
						$("#qty-table-eceran-edit tbody").html(table_eceran);

					}
				}
            });

			if (parseInt(isEceran)) {
				ambilEceranEdit();
			}
			$('#btn-stok-div-edit').html(btn_supplier);

			// console.log(table_stok);
            // $("#qty-table-edit tbody").html(table_stok);
            $("#qty-table-stok-edit tbody").html(table_stok);

            $('#stok-info-edit').find('.stok-qty').html(qty_stok);
            $('#stok-info-edit').find('.stok-roll').html(jumlah_roll);
            $('#qty-table-edit input').val();
            $('#qty-table-stok-edit .habis').hide();

            data_result = table_qty_update('#qty-table-edit').split('=*=');
			// console.log(data_result);
	    	let total = parseFloat(data_result[0]);
	    	let total_roll = parseFloat(data_result[1]);
	    	let rekap = data_result[2];
	    	if (total > 0) {
			    $('.btn-brg-save').attr('disabled',false);
	    	}else{
	    		$('.btn-brg-save').attr('disabled',true);
	    	}

	    	total = total.toFixed(2);
	    	total = total.replace('.00','');
	    	$('.yard_total').html(total);
	    	$('.jumlah_roll_total').html(total_roll);
			if (!isEceran) {
				$('#form_qty_edit [name=rekap_qty]').val(rekap);
			}
            
        });
	});

	function setQtyEdit(){

		data_result = table_qty_update('#qty-table-edit').split('=*=');
		// console.log('ds', data_result);
		let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	if (total > 0) {
		    $('.btn-brg-save').attr('disabled',false);
    	}else{
    		$('.btn-brg-save').attr('disabled',true);
    	}

		
    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	$('#form_qty_edit [name=rekap_qty]').val(rekap);
		$('#subqty-edit').val(total);
		$('#subroll-edit').val(total_roll);
		
		table_stok_update('#stok-info-edit');
		setDiskonEdit();
	}

    $("#qty-table-edit").on('change','[name=qty]',function(){
    	let ini = $(this);
		// subtotal_on_change(ini);
		
		change_qty_update(ini,'#qty-table-stok-edit');
		setQtyEdit();

    });

    $("#qty-table-edit").on('change','[name=jumlah_roll]',function(){
    	let ini = $(this).closest('tr');
		change_roll_update($(this), '#qty-table-stok-edit');
		setQtyEdit();

    });

    $('.btn-brg-edit-save').click(function(){
    	$('#form_qty_edit').submit();
    });

    $('#qty-table-stok-edit').on('click','tr', function(){
    	var ini = $(this);
    	
    	change_click_stok(ini, '#qty-table-edit');
		setQtyEdit();
    	
    	// data_result = table_qty_update('#qty-table-edit').split('=*=');
    	// let total = parseFloat(data_result[0]);
    	// let total_roll = parseFloat(data_result[1]);
    	// let rekap = data_result[2];
    	// if (total > 0) {
		//     $('.btn-brg-save').attr('disabled',false);
    	// }else{
    	// 	$('.btn-brg-save').attr('disabled',true);
    	// }

    	// $('.yard_total').html(total.toFixed(2));
    	// $('.jumlah_roll_total').html(total_roll);
    	// $('#form_qty_edit [name=rekap_qty]').val(rekap);
    	// table_stok_update('#stok-info-add');
    	

    });

//========================================btn-detail============================

		$(".btn-detail-toggle").click(function(){
			$('#general-detail-table').toggle('slow');
		});
//================================eceran==========================================

	$('#qty-table-eceran').on('click','.remove-stok-to-eceran', function(){
		let ini = $(this).closest('tr');
		let numberQty = ini.find('.eceran-stok').html();
		class_qty = numberQty.toString().replace('.','');
		
		let point = $("#qty-table-stok").find(`.idx-${class_qty}`).closest('tr');
		let roll = point.find(".roll-stok").html();
		roll = parseFloat(roll) + 1;
		point.find(".roll-stok").html(roll);
		point.removeClass('habis');
		ini.remove();

		let sec = $('#stok-eceran-add').find('.stok-qty-eceran').html();
		let seb = $('#stok-info-add').find('.stok-qty').html();
		let reb = $('#stok-info-add').find('.stok-roll').html();

		sec = parseFloat(sec) - parseFloat(numberQty);
		seb = parseFloat(seb) + parseFloat(numberQty);
		reb++;

		$('#stok-eceran-add').find('.stok-qty-eceran').html(sec);
		$('#stok-info-add').find('.stok-qty').html(seb);
		$('#stok-info-add').find('.stok-roll').html(reb);
	})

	$('#qty-table-eceran-edit').on('click','.remove-stok-to-eceran-edit', function(){
		let ini = $(this).closest('tr');
		let numberQty = ini.find('.eceran-stok').html();
		class_qty = numberQty.toString().replace('.','');

		let point = $("#qty-table-stok-edit").find(`.idx-${class_qty}`).closest('tr');
		let roll = point.find(".roll-stok").html();
		roll = parseFloat(roll) + 1;
		point.find(".roll-stok").html(roll);
		point.removeClass('habis');
		ini.remove();

		let sec = $('#stok-eceran-edit').find('.stok-qty-eceran').html();
		let seb = $('#stok-info-edit').find('.stok-qty').html();
		let reb = $('#stok-info-edit').find('.stok-roll').html();

		sec = parseFloat(sec) - parseFloat(numberQty);
		seb = parseFloat(seb) + parseFloat(numberQty);
		reb++;

		$('#stok-eceran-edit').find('.stok-qty-eceran').html(sec);
		$('#stok-info-edit').find('.stok-qty').html(seb);
		$('#stok-info-edit').find('.stok-roll').html(reb);
	})
	
});
</script>

<!-- script buat penambahan barang -->
<script>
	function setDiskonAdd(){

		const total = $('#qty-add').val();
		const hrg = $('#harga_jual_add').val();
		let diskon = $('#subdiskon-add').val();
		let subtotal = hrg*total;
		$('#subtotal-add').val(subtotal.toFixed(0));
		$('#subtotal-add-text').val(currency.rupiah(subtotal));

		subtotal = subtotal - diskon;
		$('#subtotal-grand').val(currency.rupiah(subtotal));
	}

	function harga_jual_add_change(ini){
		let harga = reset_number_comma(ini.val())/ppn_pembagi;
		harga = harga.toFixed(2);
		// alert(harga);
		var form = '#'+ini.closest('form').attr('id');
		$(form).find('.harga_jual_add_noppn').val(currency.rupiah(harga));
		setDiskonAdd();
	}

	function harga_jual_add_noppn_change(ini){
		let harga = reset_number_comma(ini.val())*ppn_pembagi;
		var form = '#'+ini.closest('form').attr('id');
		$(form).find('.harga_jual_add').val(currency.rupiah(harga));
		setDiskonAdd();
	}

	//=========ajax untuk get qty=======================================================================================
	function get_qty(){
		var data = {};
		var toko_id = $('#form_add_barang [name=toko_id]').val();
		var gudang_id = $('#form_add_barang [name=gudang_id]').val();
		var barang_id = $('#form_add_barang [name=barang_id]').val();
		var warna_id = $('#form_add_barang [name=warna_id]').val();
		let isEceran = $('#eceran-cek').is(':checked');

		data['toko_id'] = toko_id;
		data['gudang_id'] = gudang_id;
		data['barang_id'] = barang_id;
		data['warna_id'] = warna_id;
		data['is_eceran'] = isEceran;
		data['tanggal'] = $('#form_add_barang [name=tanggal]').val();

		var toko_before = $('#form_add_barang .toko_id_before').html();
		var barang_before = $('#form_add_barang .barang_id_before').html();
		var warna_before = $('#form_add_barang .warna_id_before').html();
		var gudang_before = $('#form_add_barang .gudang_id_before').html();
		var eceran_before = $('#form_add_barang .eceran_before').html();

		// console.log(barang_id+'='+barang_before);

		
		const eceranBeforeTrue = (eceran_before === 'true');
		if (warna_id != 888 || isBarangMix == false) {
			if (barang_id != barang_before || warna_id != warna_before || gudang_id != gudang_before || eceranBeforeTrue != isEceran || toko_id != toko_before) {
				var url = "stok/stok_general/get_qty_stock_by_barang_detail";
				
				//alert('test');
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					// alert(data_respond);
					var qty = 0;
					var jumlah_roll = 0;
					let table_stok = '';
					let table_stok_array = [];
					let btn_supplier = '';
					let table_eceran = '';
					let idx = 1;
					let qty_row = 0;
					let supplier_id = '';
					let qty_stok = 0;
					let qty_eceran = 0;
					let status = '';
					let total_page = 1;
					let tombol_page = '';
					$("#qty-table-stok tbody").html('');
					$.each(JSON.parse(data_respond), function(k,v){
						if(k==0){
							for (let i = 0; i < v.length; i++) {
								
								supplier_id = v[i].supplier_id;
								if (typeof table_stok_array[supplier_id] === 'undefined') {
									table_stok_array[supplier_id] = [];
									const nama_supplier = (supplier_id != 0 ? v[i].nama_supplier : 'not assigned');
									btn_supplier += `<button style='margin:0px 10px 5px 0px' class="btn btn-xs default btn-show-stok" id='btn-supplier-${supplier_id}' onclick="showStokSupplier('${supplier_id}')">${nama_supplier}</button>`
								}
								qty += parseFloat(v[i].qty);
								jumlah_roll += parseFloat(v[i].jumlah_roll);
								status = ((v[i].jumlah_roll <= 0) ? 'habis' : '');
								page_idx = parseInt(idx/10) +  parseInt((idx % 10 != 0 ) ? 1 : 0);
								var class_qty = parseFloat(v[i].qty);
								class_qty = class_qty.toString().replace('.','');
								let btnEcer = '';
								if(isEceran && parseFloat(v[i].jumlah_roll) > 0){
									btnEcer = `<button onclick="mutasiToEceran('${class_qty}','${v[i].qty}','${supplier_id}')">add to eceran</button>`;
								}
	
								console.log('3',v[i].nama_supplier);
								const content = `<tr data-supplier='${supplier_id}' class='row-stok row-${v[i].qty} page-${v[i].qty} baris-table ${status} '>
									<td class='idx-${class_qty}' ><span class='qty-stok' >${parseFloat(v[i].qty)}</span></td>
									<td><span class='roll-stok'>${parseFloat(v[i].jumlah_roll)}</span> </td>
									<td>
										<span class='nama_supplier'> ${v[i].nama_supplier}</span>
										<span class='supplier_id' hidden>${supplier_id}</span> 
									</td>
									<td>${btnEcer}</td>
								</tr>`;
	
	
								table_stok_array[supplier_id] += content; 
								table_stok += content;
								qty_stok += parseFloat(v[i].qty*v[i].jumlah_roll);
								qty_row = idx;
								idx++;
								
							}
						}else if(k==1){
							if(isEceran){
								console.log('asd',v, v.length);
								for (let i = 0; i < v.length; i++) {
									qty_eceran += parseFloat(v[i].qty);
								console.log('4',v[i].nama_supplier);
									table_eceran += `<tr >
										<td class='eceran-stok' style='padding:2px 10px'>${parseFloat(v[i].qty)}</td>
										<td><input class='text-center eceran-qty' style='width:55px; border:none' onchange="ambilEceran()"></td>
										<td class='eceran-sisa' style='padding:2px 10px'></td>
										<td hidden><span class='stok_eceran_qty_tipe'>${v[i].tipe}</span> </td>
										<td hidden><span class='stok_eceran_qty_id'>${v[i].stok_eceran_qty_id}</span></td>
										<td >
											<span class='nama_supplier'> ${(typeof v[i].nama_supplier !== 'undefined' ? v[i].nama_supplier : 'none' )}</span>
											<span hidden class='supplier_id'>${v[i].supplier_id}</span> 
										</td>
										</tr>`;
								}
								$("#qty-table-eceran tbody").html(table_eceran);
								$("#stok-eceran-add").find(".stok-qty-eceran").text(parseFloat(qty_eceran));
							}else{
								$(".add-eceran").hide();
							}
						}
						// alert(v.qty);
					});
					
	
					$('#btn-stok-div').html(btn_supplier);
					// total_page = ((qty_row <= 10) ? 1 : parseInt(qty_row/10));
					// total_page = ((qty_row % 10 != 0 ) ? total_page + 1 : total_page);
					// for (var i = 1; i <= total_page; i++) {
					//     tombol_page += "<a class='btn btn-xs default btn-page-qty-stok' style='padding:1px 5px' >"+i+"</a>";
					// };
					// $('#qty-table-stok_page').html(tombol_page);
					$("#qty-table-stok tbody").html(table_stok);
					$('#qty-table-stok .habis').hide();
	
	
					$('#stok-info-add').find('.stok-qty').html(qty_stok);
					$('#stok-info-add').find('.stok-roll').html(jumlah_roll);
					$('#qty-table input').val();
					// alert(data_respond);
					// console.log(data_respond);
	
					$('#form_add_barang .toko_id_before').html(toko_id);
					$('#form_add_barang .barang_id_before').html(barang_id);
					$('#form_add_barang .warna_id_before').html(warna_id);
					$('#form_add_barang .gudang_id_before').html(gudang_id);
					$('#form_add_barang .eceran_before').html(isEceran);
				});
				
			}
		}else{
			$("#qty-table-eceran tbody").html(`<tr data-supplier='0' class=''>
					<td><input class='qty-row text-center' onChange="eceranMix()" style='width:80px'></td>
					<td>
						<span class='nama_supplier'> none</span>
						<span class='supplier_id' hidden>0</span> 
					</td>
					<td><button class='btn-xs btn green' onclick="addRowMix('#qty-table-eceran')"><i class='fa fa-plus' ></i></button></td>
				</tr>`)
		}
		// var url = "transaction/get_qty_stock_by_barang";
	}
</script>

<!-- script buat update dan hitung qty table barang -->
<script>

	function table_qty_update(table){
		var total = 0; 
		var idx = 0; 
		var rekap = [];
		var total_roll = 0;
		// console.log($(table).html());
		$(table+" [name=qty]").each(function(){
			var ini = $(this).closest('tr');
			var qty = $(this).val();
			var roll = ini.find('[name=jumlah_roll]').val();
			var supplier_id = ini.find('[name=supplier_id]').val();
			// console.log(qty, roll, supplier_id);
			var id = ini.find('.penjualan_qty_detail_id').val();
			if ($(this).val() != '' || id != '' ) {
				if (typeof id === 'undefined' || id == '' ) {
					id = '0';
				};

				roll = (roll == '' ? 0 : roll);
				var subtotal = parseFloat(qty*roll);
				total_roll += parseFloat(roll);
				console.log('trol',total_roll, roll);

				
				if (qty != '' && roll != '' && id != '') {
					rekap[idx] = qty+'??'+roll+'??'+id+'??'+supplier_id;
				}else if(id != 0){
					rekap[idx] = 0+'??'+0+'??'+id+'??'+supplier_id;
				}
				
				idx++; 
				total += subtotal;
				ini.find('[name=subtotal]').val(qty*roll);
			};

		});

		rekap_str = rekap.join('--');
		// console.log(total+'=*='+total_roll+'=*='+rekap_str);

		return total+'=*='+total_roll+'=*='+rekap_str;
	}

	function table_stok_update(table_stok_id){
		var total= 0 ;
		var total_roll = 0;
		$(table_stok_id+".qty-stok").each(function(){
			let ini = $(this).closest('tr');
			var qty = parseFloat($(this).html());
			var jumlah_roll = parseFloat(ini.find('.roll-stok').html());
			total += (qty*jumlah_roll);
			total_roll += jumlah_roll;
		});

		$(table_stok_id).find('.stok-qty').html(total);
		$(table_stok_id).find('.stok-roll').html(total_roll);

	}

	function change_qty_update(pointer, table_stok_id){
		let ini = pointer.closest('tr');
		let qty = pointer.val();
		
		let jumlah_roll = ini.find('[name=jumlah_roll]').val();
		let supplier_id = ini.find('.supplier_id').html();
		let toko_id = ini.find('[name=toko_id]').val();
		let penjualan_qty_detail_id = ini.find('.penjualan_qty_detail_id').val();
		if (jumlah_roll == '') {
			jumlah_roll = 1; 
			ini.find('[name=jumlah_roll]').val(1);
		}else if(qty == ''){jumlah_roll = '';};
		if (typeof penjualan_qty_detail_id === 'undefined' || penjualan_qty_detail_id == '' ) {ini.find('.penjualan_qty_detail_id').val(0);};

		var qty_before = ini.find('.qty-get').html();
		var roll_before = ini.find('.roll-get').html();

		if (qty_before != '' && roll_before != '' ) {
			$(table_stok_id+" .qty-stok").filter(function(){
				const s_id = $(this).closest('tr').find('.supplier_id').html();
				if ($(this).text() == qty_before && supplier_id == s_id) {
					var baris_before = $(this).closest('tr');
					var roll_stok = baris_before.find('.roll-stok').html();
					var roll_now = parseFloat(roll_before) + parseFloat(roll_stok);
					baris_before.find('.roll-stok').html(roll_now);
					baris_before.removeClass('habis');
					$(table_stok_id+' tbody tr').not('.habis').show();

				};
			});
		};
		
		var result = filter_stok($(table_stok_id+' .qty-stok'), qty, supplier_id, toko_id);
		if (result) {
			ini.find('[name=jumlah_roll]').val(1);
			ini.find('.qty-get').html(qty);
			ini.find('.roll-get').html(1);

		}else{
			if (qty != '') {
				ini.find('[name=qty]').val(qty_before);
				ini.find('[name=jumlah_roll]').val(roll_before);
			}else{
				ini.find('[name=jumlah_roll]').val('');
				ini.find('[name=supplier_id]').val('');
				ini.find('[name=toko_id]').val('');
				ini.find('.nama_supplier').html('');
				ini.find('.qty-get').html('');
				ini.find('.roll-get').html('');
			}
		}
	}

	function change_roll_update(pointer_ini, table_stok_id){
		
		var ini = pointer_ini.closest('tr');
		let supplier_id = ini.find('.supplier_id').html();
		var roll_now = pointer_ini.val();
		var qty = ini.find('[name=qty]').val();
		var roll_before = ini.find('.roll-get').html();

		// console.log(table_stok_id);
		$(table_stok_id+" .qty-stok").filter(function(){
			const s_id = $(this).closest('tr').find('.supplier_id').html();
			if ($(this).text() == qty && s_id == supplier_id) {
				var pointer = $(this).closest('tr');
				roll_stok = pointer.find('.roll-stok').html();
				var roll_max = parseFloat(roll_before) + parseFloat(roll_stok);
				console.log("=================");
				console.log('stok:'+roll_stok);
				console.log('max:'+roll_max);
				console.log('now:'+roll_now);

				if (roll_now == '') {roll_now = 1; ini.find('[name=jumlah_roll]').val(roll_now) };
				if (roll_now > roll_max) {
					roll_now = roll_max;
					ini.find("[name=jumlah_roll]").val(roll_now);
					notific8("ruby","Sisa Stok "+roll_max+" Roll")
					var roll_sisa = 0;
				}else{
					var roll_sisa = parseFloat(roll_max) - parseFloat(roll_now);
				}

				// console.log('sisa',roll_sisa);
				if (roll_sisa == 0) {
					pointer.addClass('habis');
				}else{
					pointer.removeClass('habis');
				}
				pointer.find('.roll-stok').html(roll_sisa);
				ini.find('.roll-get').html(roll_now);
			};
		});
	}

	function change_click_stok(pointer, table_id, table_stok_id){
		var ini = pointer;
		var qty_get = ini.find('.qty-stok').html();
		var roll_get = 1;
		var roll_sisa = ini.find('.roll-stok').html() - 1;
		console.log(roll_sisa);
		var toko_id = ini.find('.toko_id').html();
		let supplier_id = ini.find('.supplier_id').html();
		var nama_supplier = ini.find('.nama_supplier').html();
		
		var compare = false;
		if (roll_sisa >= 0) {
			
			$(table_id+" .qty-get").filter(function(){
				const s_id = $(this).closest('tr').find('[name=supplier_id]').val();
				if ($(this).text() == qty_get && s_id == supplier_id) {
					// console.log(s_id+'=='+ supplier_id);
					var baris_get = $(this).closest('tr');
					var jumlah_roll = parseFloat(baris_get.find('[name=jumlah_roll]').val());
					jumlah_roll += parseFloat(roll_get);
					baris_get.find('.roll-get').html(jumlah_roll);
					baris_get.find('[name=jumlah_roll]').val(jumlah_roll);
					compare = true;
					return true;
				};
			});
		
			if (!compare) {
		
				$(table_id+' tbody tr').each(function(){
					var qty = $(this).find('[name=qty]').val();
					var jumlah_roll = $(this).find('[name=jumlah_roll]').val();
					if (jumlah_roll == '' && qty == '') {
						$(this).find('[name=qty]').val(qty_get);
						$(this).find('[name=jumlah_roll]').val(roll_get);
						$(this).find('.nama_supplier').html(nama_supplier);
						$(this).find('[name=supplier_id]').val(supplier_id);
						$(this).find('.qty-get').html(qty_get);
						$(this).find('.roll-get').html(roll_get);
						return false;
					};
				});
			};
		
			ini.find('.roll-stok').html(roll_sisa);
			if (roll_sisa == 0) {
				ini.addClass('habis');
				ini.hide();
			}
		}

	}

	function change_click_stok_all(pointer, table_id, table_stok_id){
		var ini = pointer;
		var qty_get = ini.find('.qty-stok').html();
		var roll_get = ini.find('.roll-stok').html();
		var toko_id = ini.find('.toko_id').html();
		var supplier_id = ini.find('.supplier_id').html();
		var nama_supplier = ini.find('.nama_supplier').html();
		
		var compare = false;

		$(table_id+" .qty-get").filter(function(){
			if ($(this).text() == qty_get) {
				var baris_get = $(this).closest('tr');
				var jumlah_roll = parseFloat(baris_get.find('[name=jumlah_roll]').val());
				jumlah_roll += parseFloat(roll_get);
				baris_get.find('.roll-get').html(jumlah_roll);
				baris_get.find('[name=jumlah_roll]').val(jumlah_roll);
				compare = true;
				return true;
			};
		});

		if (!compare) {

			$(table_id+' tbody tr').each(function(){
				var qty = $(this).find('[name=qty]').val();
				var jumlah_roll = $(this).find('[name=jumlah_roll]').val();
				if (jumlah_roll == '' && qty == '') {
					$(this).find('[name=qty]').val(qty_get);
					$(this).find('[name=jumlah_roll]').val(roll_get);
					$(this).find('.qty-get').html(qty_get);
					$(this).find('.roll-get').html(roll_get);
					return false;
				};
			});
		};

		ini.find('.roll-stok').html(roll_get);
		ini.addClass('habis');
		ini.hide();
	}
</script>


<!-- script buat edit barang -->
<script>

	function setHargaEdit(tipe) {
		if (tipe==1) {
			const m = $(`#harga_jual_edit_noppn`).val().replaceAll(".","").replace(",",".");
			const t = m*ppn_pembagi;
			$("#harga_jual_edit").val(currency.rupiah(t));	
		}else{
			const m = $(`#harga_jual_edit`).val().replaceAll(".","").replace(",",".");
			const t = m/ppn_pembagi;
			$("#harga_jual_edit_noppn").val(currency.rupiah(t));
		}

		setDiskonEdit();
	}

	function setDiskonEdit(){

		const total = $('#subqty-edit').val();
		const hrg = $('#harga_jual_edit').val().toString().replaceAll(".","");
		let diskon = $('#subdiskon-edit').val().toString().replaceAll(".","");
		
		let subtotal = hrg*total;
		console.log(subtotal);
		$('#subtotal-edit').val(subtotal);
		$('#subtotal-edit-text').val(currency.rupiah(subtotal));
		diskon = diskon.toString().replaceAll(",","");
		subtotal = subtotal - diskon;
		$('#subtotal-grand-edit').val(currency.rupiah(subtotal));
	}

	function dp_table_update(){
		let total_dp = 0;
		$('#dp_list_table .amount-bayar').each(function(){
			total_dp += parseFloat(reset_number_comma($(this).val()));
		});

		$('.dp-total').html(currency.rupiah(total_dp));
	}

	function eceranEditFilter(tipe){
		var penjualan_type_id = '<?=$penjualan_type_id;?>';

		// console.log($('#eceran-cek').is(':checked'));
		// $('.eceran-form').addClass("eceran-active");
		// alert(tipe);
		if (tipe) {
			$(".table-qty-edit").hide();
			$(".edit-eceran-col").show();
		}else{
			$(".table-qty-edit").show();
			$(".edit-eceran-col").hide();	
		}
	}


	function update_qty_edit(){
		var total = 0; var idx = 0; var rekap = [];
		var total_roll = 0;
		$("#qty-table-edit [name=qty]").each(function(){
			var ini = $(this).closest('tr');
			var qty = $(this).val();
			var roll = ini.find('[name=jumlah_roll]').val();
			if (qty != '' && roll == '') {
				roll = 1;
			}else if(roll == 0){
				// alert('test');
				if (qty == '') {
					qty = 0;
				};
			}else if(qty == '' && roll == ''){
				roll = 0;
				qty = 0;
			}

			if (roll == 0) {
				var subtotal = parseFloat(qty);
				total_roll += 0;
			}else{
				var subtotal = parseFloat(qty*roll);
				// alert(qty+'*'+roll);
				total_roll += parseInt(roll);
				console.log(subtotal);
			};

			if (qty != '' && roll != '') {
				rekap[idx] = qty+'??'+roll;
			};
			idx++;  
			total += subtotal;

		});

		if (total > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
		$('#portlet-config-qty-edit .yard_total').html(total.toFixed(2));

		$('#form-qty-update [name=rekap_qty]').val(rekap.join('--'));

	}

	function ambilEceranEdit(){
		let totalAmbil = 0;
		let dataQty = [];
		const isMix = $("#eceran-mix-edit").val();
		if ($("#warna-id-edit").val() != '888' || isMix == false) {
			$('#qty-table-eceran-edit .eceran-qty').each(function(){
				let ini = $(this).closest('tr');
				let stok = ini.find('.eceran-stok').text();
				let supplier_id = ini.find('.supplier_id').html();
				let id = ini.find('.stok_eceran_qty_id').html();
				let tipe = ini.find('.stok_eceran_qty_tipe').html();
				let qty_detail_id = ini.find('.penjualan_qty_detail_id').html();
				
				let ambil = $(this).val();
				if(ambil != ''){
					totalAmbil += parseFloat(ambil);
					let sisa = stok - ambil;
					ini.find('.eceran-sisa').html(sisa);
					dataQty.push(ambil+'??'+id+'??'+stok+'??'+supplier_id+'??'+tipe+'??'+qty_detail_id);
				}
			});
	
			// console.log('daki',dataQty);
	
			$('#form_qty_edit [name=rekap_qty]').val(dataQty.join('--'));
			$("#qty-table-eceran-edit .total-ambil").html(totalAmbil);
			$('#qty-edit').val(totalAmbil);
			$('#subqty-edit').val(totalAmbil);
			$('#subroll-edit').val(0);
			if(totalAmbil > 0){
				$(".btn-brg-edit-save").prop('disabled',false);
			}
			
		}
	}
</script>


<!-- script buat cek pin akses level -->
<script>
	function cek_pin(){
		// alert('test');
		var data = {};
		data['pin'] = $('#pin_user').val();
		var url = 'transaction/cek_pin';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == "OK") {
				$('#form-request-open').submit();
			}else{
				alert("PIN Invalid");
			}
		}); 
	}
</script>

<!-- script buat data pembayaran -->
<script>
	function update_db_bayar(url,data){
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				update_bayar();
				if (data['pembayaran_type_id'] == 6 ) {
					$("#portlet-config-giro").modal('toggle');
				};
			}else{
				bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
					if(respond){
						window.location.reload();
					}
				});
			};
		});
	}


	function update_bayar(){
		var bayar = 0;
		var g_total = reset_number_comma($('.g_total').html()) ;
		$('#bayar-data tr td input').each(function(){
			if ($(this).attr('class') != 'keterangan_bayar') {
				const number = $(this).val().replace(/\./g, '');
				// alert(reset_number_comma($(this).val()));
				bayar += parseFloat(reset_number_comma(number));
			};
		});
		// alert(currency.rupiah(bayar));

		var kembali = bayar - g_total ;
		// alert(currency.rupiah(bayar));
		$('.total_bayar').html(currency.rupiah(bayar) );
		$('.kembali').html(currency.rupiah(kembali));

		if (kembali < 0) {
			$('.kembali').css('color','red');
		}else{
			$('.kembali').css('color','#333');
		}

	}

</script>

<!-- script buat eceran -->
<script>
	function ambilEceran(){
		let totalAmbil = 0;
		let dataQty = [];
		$('#qty-table-eceran .eceran-qty').each(function(){
			let ini = $(this).closest('tr');
			let stok = ini.find('.eceran-stok').text();
			let supplier_id = ini.find('.supplier_id').html();
			let id = ini.find('.stok_eceran_qty_id').html();
			let tipe = ini.find('.stok_eceran_qty_tipe').html();
			
			let ambil = $(this).val();
			if(ambil != ''){
				totalAmbil += parseFloat(ambil);
				let sisa = stok - ambil;
				ini.find('.eceran-sisa').html(sisa);
				dataQty.push(ambil+'??'+id+'??'+stok+'??'+supplier_id+'??'+tipe);
			}
		});

		$('#form_add_barang [name=rekap_qty]').val(dataQty.join('--'));
		$("#qty-table-eceran .total-ambil").html(totalAmbil);
		$('#qty-add').val(totalAmbil);
		$('#subqty-add').val(totalAmbil);
		$('#subroll-add').val(0);
		setDiskonAdd();
		if(totalAmbil > 0){
			$(".btn-brg-save").prop('disabled',false);
		}
	}

	function mutasiToEceran(idx, numberQty, supplier_id){
		// let ini = $('#qty-table-stok').find(`.idx-${idx}`).closest('tr');
		let ini = $('#qty-table-stok').find(`[data-supplier='${supplier_id}']`).find(`.idx-${idx}`).closest('tr');
		let roll_stok = ini.find('.roll-stok').html();
		const nama_supplier = ini.find('.nama_supplier').html();
		console.log('cek',ini.html());
		if(roll_stok > 0){
			roll_stok--;
			ini.find('.roll-stok').html(roll_stok);
			if(roll_stok == 0){
				ini.addClass('habis');
			}

			console.log('5',nama_supplier);
			let newBaris = `<tr >
				<td class='eceran-stok' style='padding:2px 10px'>${numberQty}</td>
				<td><input type='numberQty' class='text-center eceran-qty' style='width:55px; border:none' onchange="ambilEceran()"></td>
				<td class='eceran-sisa' style='padding:2px 10px'></td>
				<td hidden><span class='stok_eceran_qty_id'>0</span> </td>
				<td hidden><span class='stok_eceran_qty_tipe'>1</span> </td>
				<td >
					<span class='nama_supplier'>${nama_supplier}</span>
					<span hidden class='supplier_id'>${supplier_id}</span>
				</td>
				<td style='border:none; padding-left:5px'><button class='btn btn-xs red remove-stok-to-eceran'><i class='fa fa-times'></i></button></td>
				</tr>`;

			$('#qty-table-eceran tbody').prepend(newBaris);

			let sec = $('#stok-eceran-add').find('.stok-qty-eceran').html();
			let seb = $('#stok-info-add').find('.stok-qty').html();
			let reb = $('#stok-info-add').find('.stok-roll').html();

			sec = parseFloat(sec) + parseFloat(numberQty);
			seb = parseFloat(seb) - parseFloat(numberQty);
			reb--;

			$('#stok-eceran-add').find('.stok-qty-eceran').html(sec);
			$('#stok-info-add').find('.stok-qty').html(seb);
			$('#stok-info-add').find('.stok-roll').html(reb);

		}else{
			alert("no stok");
		}
	}

	function mutasiToEceranEdit(idx, numberQty){
		let ini = $('#qty-table-stok-edit').find(`.idx-${idx}`).closest('tr');
		let roll_stok = ini.find('.roll-stok').html();
		const supplier_id = ini.find('.supplier_id').html();
		const nama_supplier = ini.find('.nama_supplier').html();

		console.log(roll_stok);
		if(roll_stok > 0){
			roll_stok--;
			ini.find('.roll-stok').html(roll_stok);
			
			if(roll_stok == 0){
				ini.addClass('habis');
			}

			let newBaris = `<tr >
				<td class='eceran-stok' style='padding:2px 10px'>${numberQty}</td>
				<td><input type='numberQty' class='text-center eceran-qty' style='width:55px; border:none' onchange="ambilEceranEdit()"></td>
				<td class='eceran-sisa' style='padding:2px 10px'></td>
				<td hidden><span class='stok_eceran_qty_id'>0</span> </td>
				<td hidden><span class='stok_eceran_qty_tipe'>1</span> </td>
				<td hidden>
					<span class='supplier_id'>${supplier_id}</span> 
					<span class='supplier_id'>${nama_supplier}</span> 
				</td>
				<td style='border:none; padding-left:5px'><button class='btn btn-xs red remove-stok-to-eceran-edit'><i class='fa fa-times'></i></button></td>
				</tr>`;

			$('#qty-table-eceran-edit tbody').prepend(newBaris);

			let sec = $('#stok-eceran-edit').find('.stok-qty-eceran').html();
			let seb = $('#stok-info-edit').find('.stok-qty').html();
			let reb = $('#stok-info-edit').find('.stok-roll').html();

			sec = parseFloat(sec) + parseFloat(numberQty);
			seb = parseFloat(seb) - parseFloat(numberQty);
			reb--;

			$('#stok-eceran-edit').find('.stok-qty-eceran').html(sec);
			$('#stok-info-edit').find('.stok-qty').html(seb);
			$('#stok-info-edit').find('.stok-roll').html(reb);

		}else{
			alert("no stok");
		}
	}
</script>


<!-- script buat ambil data stok per supplier -->
<script>
	function showStokSupplier(supplier_id){
		$(`.btn-show-stok`).removeClass('green');
		$(`#btn-supplier-${supplier_id}`).addClass('green');
		$(`#qty-table-stok .row-stok`).hide();
		$(`#qty-table-stok [data-supplier=${supplier_id}]`).show();
	}

	function showStokSupplierEdit(supplier_id){
		$(`.btn-show-stok-edit`).removeClass('green');
		$(`#btn-edit-supplier-${supplier_id}`).addClass('green');
		$(`#qty-table-stok-edit .row-stok`).hide();
		$(`#qty-table-stok-edit [data-supplier=${supplier_id}]`).show();
		
	}
</script>

<!-- script buat lain-lain  -->

<script>

	function subtotal_on_change(pointer){
		var ini = pointer.closest('tr');
		let subtotal = pointer.val();
		let qty = ini.find('[name=qty]').val();
		let jumlah_roll = ini.find('[name=jumlah_roll]').val();
		if (qty != '' || jumlah_roll != '') {
			// alert('test');
			if (qty != '') {
				jumlah_roll = subtotal / qty;
				jumlah_roll = jumlah_roll.toFixed(2);
				ini.find('[name=jumlah_roll]').val(jumlah_roll.toString().replace('.00',''));
			}else{
				qty = subtotal / jumlah_roll;
				ini.find('[name=qty]').val(qty.toFixed(3));
			}
		};
	}

	function cek_last_input(gudang_id_last,barang_id, harga_jual, toko_id_last){
		setTimeout(function(){
			// $('#barang_id_select').select2("open");
			$('#gudang_id_select').val(gudang_id_last);
			$('#barang_id_select').val(barang_id);
			$('#toko_id_select').val(toko_id_last);
			$('#barang_id_select, #gudang_id_select').change();
			tokoChange(1);
			/*setTimeout(function(){
				$('.harga_jual_add').val(harga_jual);
				harga_jual_add_change(harga_jual);
			},700);*/

		},650);
	}

	function save_penjualan_baru(ini){
		ini.prop('disabled',true);
		// $('#form_add_data').submit();
		setTimeout(function(){
			ini.prop('disabled',false);
		},2000);
	}

	//============ecerab=====================================================================================

	function eceranFilter(){
		var barang_id = $('#barang_id_select').val();
		var warna_id = $('#warna_id_select').val();
		var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
		var penjualan_type_id = '<?=$penjualan_type_id;?>';

		// console.log($('#eceran-cek').is(':checked'));
		if (barang_id != '' && warna_id != '' ) {
			if($('#eceran-cek').is(':checked')){
				$('.eceran-form').addClass("eceran-active");
				$(".table-qty").hide();
				$(".add-eceran").show();
				$('#form_add_barang [name=harga_jual]').val((data[4]));
				harga_jual = data[4];
				if (harga_jual != 0 ) {
					harga_jual_add_change($('#form_add_barang').find(".harga_jual_add"));
				}else{
					$('#form_add_barang').find('.harga_jual_add_noppn').val(0);
				}
			}else{
				$('.eceran-form').removeClass("eceran-active");
				$(".table-qty").show();
				$(".add-eceran").hide();
				if (penjualan_type_id == 3) {
					$('#form_add_barang [name=harga_jual]').val((data[2]));
					harga_jual = data[2];
					if (harga_jual != 0 ) {
						harga_jual_add_change($('#form_add_barang').find(".harga_jual_add"));
					}else{
						$('#form_add_barang').find('.harga_jual_add_noppn').val(0);
					}
				}else{
					var data_st = {};
					data_st['barang_id'] = $('#form_add_barang [name=barang_id]').val();
					data_st['customer_id'] =  "<?=$customer_id;?>";
					var url = "transaction/get_latest_harga";

					let harga_jual = 0;
					ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						if (data_respond > 0) {
							$('#form_add_barang [name=harga_jual]').val((data_respond));
						}else if(data[2] > 0){
							$('#form_add_barang [name=harga_jual]').val((data[2]));
							<?if (is_posisi_id() == 1) {?>
								// alert(data);
							<?}?>
							// harga_jual = data[2];
						}else{
							$('#form_add_barang [name=harga_jual]').val(0);
						}

						// alert("respond="+data_respond);
						if ($('#form_add_barang').find(".harga_jual_add").val() != 0) {
							harga_jual_add_change($('#form_add_barang').find(".harga_jual_add"));
						}else{
							$('#form_add_barang').find('.harga_jual_add_noppn').val(0);
						}
					});
				}		
			}
			
		}
	}

	//=============filter stok====================================================================================

	function filter_stok(ini, qty, supplier_id){
		// console.log(ini);
		var result = false;
		var jumlah_roll = 0;
		ini.filter(function(index){
			const s_id = $(this).closest('tr').find('.supplier_id').html();
			if ($(this).text() === qty.toString() && s_id == supplier_id) {
				var pointer = $(this).closest('tr');
				jumlah_roll = pointer.find('.roll-stok').html();
				if (jumlah_roll > 0) {
					jumlah_roll -= 1;
					pointer.find('.roll-stok').html(jumlah_roll);
					if (jumlah_roll == 0) {
						pointer.addClass('habis');

					};
					result = true
				}else{result = true};
			};
		});
		return result;
	}

	function update_table(){
		subtotal = 0;
		$('.subtotal').each(function(){
			var sub = reset_number_comma($(this).html());
			subtotal += parseFloat(sub);
			// alert(subtotal);
		});

		var diskon = reset_number_comma($('.diskon').val());
		var ongkir = $('.ongkos_kirim').val();
		if (typeof ongkir === 'undefined') {ongkir = 0};
		// alert(ongkir);
		ongkir = reset_number_comma(ongkir);
		var g_total = subtotal - parseInt(diskon) + parseInt(ongkir);
		// alert(subtotal+ '-' +parseInt(diskon) +'+'+ parseInt(ongkir));
		$('.g_total').html(currency.rupiah(g_total));
		$('.total').html(currency.rupiah(g_total));
		update_bayar();
	}

	function tokoChange(tipe){
		console.log(colorToko);
		if (tipe == 1) {
			let toko_id = $('#toko_id_select').val();
			const use_ppn_toko = $(`#toko_id_copy option[value='${toko_id}']`).text();
			const val = (use_ppn_toko == 1 ? 1 : 0)
			$('#ppn-value').val(val); 
			$("#portlet-config-detail .modal-body").css('background-color',colorToko[toko_id]);
			ppnStatusChange(val)
		}else{
			let toko_id = $('#toko_id_select').val();
			$("#portlet-config-edit .modal-body").css('background-color',colorToko[toko_id]);	
		}

		$.uniform.update($('#ppn-cek'));
		// alert($('#ppn-cek').is(':checked'));
	}

	function ppnStatusChange(ppn_stat){

		if (parseInt(ppn_stat) == 1) {
			$('#ppn-cek').prop('checked',true);
			$('#harga-dpp-group').show('slow');
		}else{
			$('#ppn-cek').prop('checked', false);
			$('#harga-dpp-group').hide('fast');

		}
	}
	//==============================================eceran==========================================


</script>

<!-- script buat eceran mix -->
<script>

	function getKeterangan(barangList, barang_id){
		const filteredList = barangList.filter(item=>item.barang_id == barang_id)
		.map(item=>item.warna_id);
		return filteredList;
	}

	function addRowMix(table){
		$(`${table} tbody`).append(`<tr data-supplier='0' class=''>
			<td><input class='qty-row text-center' style='width:80px'></td>
			<td>
				<span class='nama_supplier'> none</span>
				<span class='supplier_id' hidden>0</span> 
				<span class='qty_id' hidden>0</span> 
			</td>
			<td></td>
		</tr>`);

		
	}
	

	function eceranMix(){
		/* 
			Rekap QTY consist of
			0. qty
			1. jumlah_roll
			2. penjualan_qty_detail_id
			3. supplier_id
			4. jangan dipake !!!
			======klo eceran======
			0. qty
			1. jumlah_roll
			2. penjualan_qty_detail_id
			3. eceran_source
			4. supplier_id
			

		*/

		let totalAmbil = 0;
		let total_roll = 0;
		let total_rekap = 0;
		const rkp = [];
		const rows = document.querySelectorAll("#qty-table-eceran .qty-row");
		rows.forEach((row,index) => {
			if (row.value.length > 0 ) {
				totalAmbil += parseFloat(row.value);
				total_roll++;
				rkp[index] = `${row.value}??1??0??2??0`;
			}
		});

		console.log(rkp);
		

		$('#form_add_barang [name=rekap_qty]').val(rkp.join('--'));
		$("#qty-table-eceran .total-ambil").html(totalAmbil);
		$('#qty-add').val(totalAmbil);
		$('#subqty-add').val(totalAmbil);
		$('#subroll-add').val(0);
		setDiskonAdd();
		if(totalAmbil > 0){
			$(".btn-brg-save").prop('disabled',false);
		}
	}

	function eceranMixEdit(){
		/* 
			Rekap QTY consist of
			0. qty
			1. jumlah_roll
			2. penjualan_qty_detail_id
			3. supplier_id
			4. jangan dipake !!!
			======klo eceran======
			0. qty
			1. jumlah_roll
			2. penjualan_qty_detail_id
			3. eceran_source
			4. supplier_id 
			

		*/

		let totalAmbil = 0;
		let total_roll = 0;
		let total_rekap = 0;
		const rkp = [];
		const rows = document.querySelectorAll("#qty-table-eceran-edit tbody tr");
		rows.forEach((row,index) => {
			const input = row.querySelector(".qty-row");
			const qty_id = row.querySelector(".qty_id").innerHTML;
			const qty = (input.value.length > 0 ? input.value : 0)
			totalAmbil += parseFloat(input.value);
			total_roll++;
			rkp[index] = `${qty}??1??0??2??${qty_id}`;
		});

		// console.log(rkp);		

		$('#form_qty_edit [name=rekap_qty]').val(rkp.join('--'));
		$("#qty-table-eceran-edit .total-ambil").html(totalAmbil);
		if(totalAmbil > 0){
			$(".btn-brg-edit-save").prop('disabled',false);
		}else{
			$(".btn-brg-edit-save").prop('disabled',true);
		}
		
		
	}

	
	
</script>
<?
$nama_toko = '';
$alamat_toko = '';
$telepon = '';
$fax = '';
$npwp = '';



if ($penjualan_id != '') {

	foreach ($data_toko as $row) {
		$nama_toko = trim($row->nama);
		$alamat_toko = trim($row->alamat.' '.$row->kota);
		$telepon = trim($row->telepon);
		$fax = trim($row->fax);
		$npwp = trim($row->NPWP);

	}

	$garis1 = "'-";
	$garis2 = "=";

	include_once 'print_faktur.php';
	// include_once 'print_detail.php';
	include_once 'print_faktur_detail.php';
	include_once 'print_surat_jalan.php';
	// include_once 'print_surat_jalan_noharga.php';
	// include_once 'print_test.php';

	include_once 'print_faktur_2.php';
	include_once 'print_faktur_detail_2.php';
	include_once 'print_surat_jalan_2.php';
}?>
