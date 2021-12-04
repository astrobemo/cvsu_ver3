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
							</table>
						 
							
							
						
						</form>
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
										Jumlah <br/>
										Yard/KG
									</th>
									<th scope="col">
										Jumlah <br/> Roll
									</th>
									<th scope="col" style='min-width:300px !important'>
										Nama Barang
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total
									</th>
									<!-- <th scope="col">
										Diskon
									</th>
									<th scope="col">
										Ongkir
									</th> -->
									<th scope="col">
										Nama <br/> Customer
									</th>
									<th scope="col">
										HPP
									</th>
									<th scope="col">
										Keuntungan
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$idx = 0; $g_total = 0; $g_total_hpp = 0;
								$yard_total = 0; $roll_total = 0; $g_total_untung = 0;
								foreach ($penjualan_list as $row) { ?>
									<?
										$qty = ''; $jumlah_roll = ''; $nama_barang = ''; $harga_jual = '';
										if ($row->qty != '') {
											$qty = explode('??', $row->qty);
											$jumlah_roll = explode('??', $row->jumlah_roll);
											$nama_barang = explode('??', $row->nama_barang);
											$harga_jual = explode('??', $row->harga_jual);
											$hpp = explode('??', $row->hpp);
											$untung = array();
										}
										$yard_total += $row->qty;
										$roll_total += $row->jumlah_roll;
									?>
									<tr class='text-center' >
										<td>
											<?=$row->no_faktur;?>
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
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
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
										</td>
										<td>
											
											<?
											$subtotal = 0; 
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													echo number_format($qty[$key] * $value,'0',',','.').'<br/>';
													$subtotal +=$qty[$key] * $value; 
													$g_total += $qty[$key] * $value;

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
										</td>
										<!-- <td>
											<?if ($row->diskon != 0) {
												echo $row->diskon;
											};?> 
										</td>
										<td>
											<?if ($row->ongkos_kirim != 0) {
												echo $row->ongkos_kirim;
											};?> 
										</td> -->
										<td>
											<?=$row->nama_customer;?> 
										</td>
										<td>
											<?
											$j = 1; $idx = 1; $total_hpp = 0;
											if ($harga_jual != '') {
												foreach ($harga_jual as $key => $value) {
													$total_hpp += $qty[$key] * $hpp[$key];
													$untung[$key] = ($qty[$key] * $value) - ($qty[$key] * $hpp[$key]);
													echo number_format($qty[$key] * $hpp[$key],'0',',','.').'<br/>';
													
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

											$g_total_hpp += $total_hpp;
											?>
											<b><?=number_format($total_hpp,'0',',','.');?></b>
										</td>
										<td>
											<?
											$j = 1; $idx = 1; $total_untung = 0;
											if ($harga_jual != '') {
												foreach ($untung as $key => $value) {
													$total_untung += $value;
													echo number_format($value,'0',',','.').'<br/>';
													
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
											$g_total_untung += $total_untung;

											?>
											<b><?=number_format($total_untung,'0',',','.')?></b>
										</td>
									</tr>
									<!-- <tr style='font-weight:bold; text-align:center'>
										
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class='text-right'>subtotal</td>
										<td>
											Rp<?=number_format($subtotal,'0',',','.')?>
										</td>
										<td></td>
										<td></td>
									</tr> -->
								<? $idx++;} ?>
								<tr style='font-size:1.2em;font-weight:bold'>
									<td class='text-center'>TOTAL</td>
									<td class='text-center'><?=$idx;?></td>
									<td class='text-center'><?=number_format($yard_total,'2',',','.');?></td>
									<td class='text-center'><?=number_format($roll_total,'0',',','.');?></td>
									<td class='text-center'></td>
									<td class='text-right'><b>TOTAL :</b></td>
									<td class='text-center'><b><?=number_format($g_total,'0',',','.');?></b> </td>
									<td></td>
									<td><b><?=number_format($g_total_hpp,'0',',','.');?></b></td>
									<td><b><?=number_format($g_total_untung,'0',',','.');?></b></td>
									<!-- <td></td>
									<td></td> -->
								</tr>
							</tbody>
						</table>
					</div>

					<?/*<form action='<?=base_url();?>report/penjualan_list_export_excel' method='get'>
						<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden='hidden'>
						<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden='hidden'>
						<input name='tipe_search' value='<?=$tipe_search;?>' hidden='hidden'>
						<input name='customer_id' value='<?=$customer_id;?>' hidden='hidden'>

						<button <?=($status_excel==0 || $idx ==0 ? "disabled" : "");?> class='btn green'><i class='fa fa-download'></i> Excel</button>
					</form>*/?>
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
