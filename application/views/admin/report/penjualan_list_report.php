<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>
<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	text-align: center;
	/*font-size: 0.95em;*/

}

#general_table tr td{
	color:#000;
	/*font-size: 0.8em;*/
	/*font-family: Arial;*/
	/*font-size: 12px;*/
}

#general_table{
	border-bottom: 2px solid #ddd;
}
</style>

<div class="page-content">
	<div class='container'>

		
		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<table>
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
														s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
													</b>
												</td>
												<td>
													<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
												</td>
											</tr>
											<tr>
												<td>Tipe </td>
												<td>: </td>
												<td>
													<b>
														<select name='tipe_search'>
															<option <?=($tipe_search == 1 ? "selected" : "");?> value='1'>Semua</option>
															<option <?=($tipe_search == 2 ? "selected" : "");?> value='2'>Lunas (Cash)</option>
															<option <?=($tipe_search == 3 ? "selected" : "");?> value='3' >Lunas (Kredit)</option>
															<option <?=($tipe_search == 4 ? "selected" : "");?> value='4'>Belum Lunas</option>
															<option <?=($tipe_search == 5 ? "selected" : "");?> value='5'>Faktur Pajak</option>
															<option <?=($tipe_search == 6 ? "selected" : "");?> value='6'>Non Faktur Pajak</option>
														</select>
													</b>
												</td>
												<td></td>
											</tr>
											<tr>
												<td>Toko</td>
												<td> : </td>
												<td>
													<b>
														<select name='toko_id' style='width:100%'>
															<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Semua</option>
															<?foreach ($this->toko_list_aktif as $row) { ?>
																<option <?=($toko_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?></option>
															<?}?>
														</select>
													</b>
												</td>
												<td></td>
											</tr>
											<tr>
												<td>Customer</td>
												<td> : </td>
												<td>
													<b>
														<select name='customer_id' id="select_customer" style='width:100%'>
															<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Semua</option>
															<?foreach ($this->customer_list_aktif as $row) { ?>
																<option <?=($customer_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?></option>
															<?}?>
														</select>
													</b>
												</td>
												<td></td>
											</tr>
											<tr>
												<td>Barang</td>
												<td> : </td>
												<td>
													<b>
														<select name='barang_id' style='width:100%'>
															<option <?=($barang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
															<?foreach ($this->barang_list_aktif as $row) { ?>
																<option <?=($barang_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama_jual;?></option>
															<?}?>
														</select>
													</b>
												</td>
												<td></td>
											</tr>
											<tr>
												<td>Warna</td>
												<td> : </td>
												<td>
													<b>
														<select name='warna_id' style='width:100%'>
															<option <?=($warna_id == 0 ? "selected" : "");?> value='0'>Semua</option>
															<?foreach ($this->warna_list_aktif as $row) { ?>
																<option <?=($warna_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->warna_jual;?></option>
															<?}?>
														</select>
													</b>
												</td>
												<td></td>
											</tr>
										</table>
									</form>
								</td>
								<td  class='text-right'>
									<form action='<?=base_url();?>report/penjualan_list_export_excel' method='get'>
										<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden>
										<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden>
										<input name='tipe_search' value='<?=$tipe_search;?>' hidden>
										<input name='customer_id' value='<?=$customer_id;?>' hidden>
										<input name='barang_id' value='<?=$barang_id;?>' hidden>
										<input name='warna_id' value='<?=$warna_id;?>' hidden>
										<input name='toko_id' value='<?=$toko_id;?>' hidden>

										<button <?=(count($penjualan_list) == 0 ? "disabled" : "");?> class='btn green'><i class='fa fa-download'></i> Excel</button>
									</form>
								</td>
							</tr>
						</table>
									
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-bordered table-hover table-striped " id="general_table">
							<thead>
								<tr style='background:#eee' >
									<th scope="col" style='width:90px !important;'>
										No Faktur
									</th>
									<th scope="col">
										Tanggal<br/> Penjualan
									</th>
									<th scope="col">
										Sat <br/>
										Kecil
									</th>
									<th scope="col">
										Sat <br/> Besar
									</th>
									<th scope="col" style='min-width:300px !important'>
										Nama Barang
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
									<th scope="col">
										Total
									</th>
									<th scope="col">
										Nama <br/> Customer
									</th>
									<th scope="col">
										Keterangan
									</th>
									<?foreach ($tipe_bayar as $row2) {
										if ($row2->id != 6) {

											$total_tipe_bayar[$row2->id] = 0;
											?>
											<th scope="col">
												<?=$row2->nama;?>
											</th>
									<?}}?>
									<!-- <th scope="col">
										Jatuh Tempo
									</th> -->
								</tr>
							</thead>
							<tbody>
								<?
								$idx_total = 0; $g_total = 0;
								$yard_total = 0; $roll_total = 0;
								foreach ($penjualan_list as $row) { ?>
									<?
										$qty = ''; $jumlah_roll = ''; $nama_barang = ''; $harga_jual = '';
										if ($row->qty != '') {
											$qty = explode('??', $row->qty);
											$jumlah_roll = explode('??', $row->jumlah_roll);
											$nama_barang = explode('??', $row->nama_barang);
											$harga_jual = explode('??', $row->harga_jual);
											$pengali_harga = explode('??', $row->pengali_harga);
										}
										$pembayaran_piutang_id = explode(',', $row->pembayaran_piutang_id);
										
									?>
									<tr class='text-center' >
										<td>
											<a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>/?id=<?=$row->id;?>" target='_blank' <?=($row->no_faktur == '' ? "class='btn btn-xs red'" : '' );?> ><?=($row->no_faktur == '' ? "<i class='fa fa-warning'></i><i class='fa fa-warning'></i>" : $row->no_faktur);?></a>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?
											if ($qty != '') {
												$baris = count($qty);
												$j = 1; $idx = 1;
												foreach ($qty as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
													$yard_total += $row->qty;
												}
											}
											?>
										</td>
										<td>
											<?
											if ($jumlah_roll != '') {
												$j = 1; $idx = 1;
												foreach ($jumlah_roll as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{										
														$j++;
													}
													$idx++;
													$roll_total += $row->jumlah_roll;
												}
											}

											?>
										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<?
											if ($nama_barang != '') {
												$j = 1; $idx = 1;
												foreach ($nama_barang as $key => $value) {
													echo "<span class='nama'>".$value."</span>".'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											?>
											<!-- <span class='nama'><?=str_replace('??', '<br/>', $row->nama_barang);?></span><br/> -->
											
										</td>
										<td>
											<?
											if ($harga_jual != '') {
													$j = 1; $idx = 1;
													foreach ($harga_jual as $key => $value) {
													echo str_replace(',00', '', number_format($value/1.1,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}?>
											<b>Subtotal:</b>
											<?if ($row->diskon != 0) {?>
												<b>Diskon</b>
												<hr style='padding:0px; margin:0px;' />
											<?}?>
										</td>
										<td>
											<?$subtotal = 0;
											if ($harga_jual != '') {
												$j = 1; $idx = 1; 
												foreach ($harga_jual as $key => $value) {
													echo str_replace(',00', '', number_format(($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key])*$value/1.1,'2',',','.')).'<br/>';
													$subtotal += ($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key])*$value/1.1;
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}?>
											<b><?=str_replace(',00', '', number_format($subtotal,'2',',','.'))?></b>
										</td>
										<td>
											<?$subtotal = 0;
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													$harga_now = ($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key]) * $value;
													$subtotal += $harga_now-($harga_now/1.1);
													echo str_replace(',00', '', number_format($harga_now-($harga_now/1.1),'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}?>
											<b><?=str_replace(',00', '', number_format($subtotal,'2',',','.'))?></b>
										</td>
										<td>
											
											<?
											$subtotal = 0; 
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													echo number_format(($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key]) * $value,'0',',','.').'<br/>';
													$subtotal +=($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key]) * $value; 
													$g_total += ($pengali_harga[$key] == 1 ? $qty[$key] : $jumlah_roll[$key]) * $value;

													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											
											?>
											<b><?=number_format($subtotal,'0',',','.')?></b>
											<?if ($row->diskon != 0) {
												$g_total -= $row->diskon;
												?>
												<b><?=number_format($row->diskon,'0',',','.')?></b>
												<hr style='padding:0px; margin:0px;' />
												<b><?=number_format($subtotal - $row->diskon,'0',',','.')?></b>
											<?}?>

										</td>
										<td>
											<?=$row->nama_customer;?><br/>
											<span style='background:#fcf8e3; padding:2px; border-left:2px solid #f2cf87 '><?=$row->npwp;?></span> 
										</td>
										<td>
											<?=(is_posisi_id()==1 ? $row->keterangan : '');?>
											<?if ($row->keterangan < 0) { ?>
												<span style='color:red'>belum lunas</span>
											<?}else if ($row->keterangan >= 0){?>
												<span style='color:green'>lunas</span>
											<?}?> 
											<?$idx = 1;
											echo "<br/>";
											foreach ($pembayaran_piutang_id as $key => $value) {
												if ($value != '') {?>
													<a target="_blank" href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$value;?>"><i class='fa fa-search'></i> pelunasan <?=$idx; $idx++;?></a>
													<br/>
												<?}
												?>
											<?}?>
										</td>
										<?
										unset($pembayaran_type_id); unset($data_bayar);
										$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
										$data_bayar = explode(',', $row->data_bayar);
										$bayar = array_combine($pembayaran_type_id, $data_bayar);

										foreach ($tipe_bayar as $row2) { 
											if ($row2->id != 6) {?>
												<td>
													<?if (isset($bayar[$row2->id])) {
														$total_tipe_bayar[$row2->id] += $bayar[$row2->id];
														echo number_format($bayar[$row2->id],'0',',','.');
													}?>
												</td>
											<?}?>
										<?}?>
										<!-- <td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td> -->
									</tr>
								<? $idx_total++;} ?>
							</tbody>
						</table>

						<hr/>

						<table class='table table-bordered table-hover table-striped'>
							<tr>
								<th>Transaksi</th>
								<th>Total</th>
								<?foreach ($tipe_bayar as $row2) { 
									if ($row2->id != 6) {?>
									<th>
										<?=$row2->nama;?>
									</td>
								<?}}?>

							</tr>
							<tr style='font-size:1.2em;font-weight:bold'>
								<td class='text-center'><?=$idx_total;?></td>
								<td class='text-center'><b><?=number_format($g_total,'0',',','.');?></b> </td>
								<?foreach ($tipe_bayar as $row2) { 
									if ($row2->id != 6) {?>
										<td>
											<?if (isset($total_tipe_bayar[$row2->id])) {
												echo number_format($total_tipe_bayar[$row2->id],'0',',','.');
											}?>
										</td>
									<?}?>
								<?}?>
								<!-- <td></td>
								<td></td>
								<td></td> -->
							</tr>

						</table>
					</div>

				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	$('#select_customer').select2({});

});
</script>
