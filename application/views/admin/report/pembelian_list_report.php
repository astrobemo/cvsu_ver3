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
	font-size: 0.95em;

}

#general_table tr td{
	color:#000;
	font-size: 0.95em;
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
												<td class='padding-rl-5'> : </td>
												<td>
													<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
													s/d
													<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
												</td>
											</tr>
											<tr>
												<td>Toko</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='toko_id'>
														<?foreach ($this->toko_list_aktif as $row) { ?>
															<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Lokasi</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='gudang_id'>
														<option <?=($gudang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->gudang_list_aktif as $row) { ?>
															<option <?=($gudang_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>											
											<tr>
												<td>Supplier</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='supplier_id' id="supplier_id_select" style='width:200px;'>
														<option <?=($supplier_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->supplier_list_aktif as $row) { ?>
															<option <?=($supplier_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Barang</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='barang_id' id='barang_id_select' style='width:200px;'>
														<option <?=($barang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->barang_list_aktif as $row) { ?>
															<option <?=($barang_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama_jual;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Warna</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='warna_id' id="warna_id_select" style="width:200px" >
														<option <?=($warna_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->warna_list_aktif as $row) { ?>
															<option <?=($warna_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->warna_beli;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td colspan='3' class='text-center'>
													<button class='btn btn-xs default' style='width:100%; margin-top:10px;'><i class='fa fa-search'></i> Cari</button>
												</td>
											</tr>
										</table>
										
									</form>
								</td>
								<td class='text-right'>
									<form action='<?=base_url();?>report/pembelian_list_export_excel' method='get'>
										<div hidden>
											<input name='tanggal_start' value='<?=$tanggal_start;?>' >
											<input name='tanggal_end' value='<?=$tanggal_end;?>' >
											<input name='toko_id' value='<?=$toko_id;?>' >
											<input name='gudang_id' value='<?=$gudang_id;?>' >
											<input name='barang_id' value='<?=$barang_id;?>' >
											<input name='warna_id' value='<?=$warna_id;?>' >
											<input name='supplier_id' value='<?=$supplier_id;?>' >
										</div>
										<button class='btn green'><i class='fa fa-download'></i> Excel</button>
									</form>
								</td>
							</tr>
						</table>
									
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered " id="general_table">
							<thead>
								<tr style='background:#eee' >
									<th scope="col" style='width:70px !important;'>
										No Faktur
									</th>
									<th scope="col" style='width:100px !important;'>
										Tanggal<br/> Pembelian
									</th>
									<th scope="col">
										Sat. Kecil
									</th>
									<th scope="col">
										Sat. Besar
									</th>
									<th scope="col" style='width:200px !important;'>
										Nama Barang
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col" class='status_column'>
										Diskon
									</th>
									<th scope="col">
										Nama <br/> Supplier
									</th>
									<th scope="col">
										Lokasi
									</th>
									<th scope="col">
										Jatuh Tempo
									</th>
									<th scope="col">
										Keterangan
									</th>
									
								</tr>
							</thead>
							<tbody>
								<?
								$idx = 0; $g_total = 0; $yard_total = 0; $roll_total = 0; 
								foreach ($pembelian_list as $row) { 

										$subtotal_yard = 0;
										$subtotal_roll = 0;
										$subtotal = 0;

										$qty = explode('??', $row->qty);
										$roll = explode('??', $row->jumlah_roll);
										$pengali_type = explode('??', $row->pengali_type);
										$harga_beli = explode('??', $row->harga_beli);
										foreach ($qty as $key => $value) {
											$yard_total += $value;
											$roll_total += $roll[$key];
										}
									?>
									<tr class='text-center' >
										<td>
											<a href="<?=base_url().is_setting_link('transaction/pembelian_list_detail')?>/<?=$row->id;?>" target='_blank'><?=$row->no_faktur;?></a>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?//=str_replace('??', '<br/>', str_replace(',00', '', number_format($row->qty,'2',',','.')));?>
											<?foreach ($qty as $key => $value) {
												if ($value == '') {
													$value = 0;
												}
												$subtotal_yard += $value;
												echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
											};?>
											<hr style='margin:5px 0' />
											<b><?=str_replace(',00', '', number_format($subtotal_yard,'2',',','.')).'<br/>';?></b>
										</td>
										<td>
											<?=str_replace('??', '<br/>', $row->jumlah_roll);?>
											<?foreach ($roll as $key => $value) {
												$subtotal_roll += $value;
											}?>
											<hr style='margin:5px 0' />
											<b><?=str_replace(',00', '', number_format($subtotal_roll,'2',',','.')).'<br/>';?></b>

										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<span class='nama'><?=str_replace('??', '<br/>', $row->nama_barang);?></span> 
											<hr style='margin:5px 0' />
											Total :<b> <?=count($roll)?> Item</b>
										</td>
										<td>
											<?if ($row->harga_beli != '') {
												foreach ($harga_beli as $key => $value) {
													echo number_format($value,'0',',','.')."<br/>";
												}
											}?>
										</td>
										<td>
											
											<?foreach ($harga_beli as $key => $value) {
												$subtotal += ($pengali_type[$key] == 1 ? $qty[$key] : $roll[$key]) * $value;
												echo number_format(($pengali_type[$key] == 1 ? $qty[$key] : $roll[$key]) * $value,'0','.',',').'<br/>';
												$g_total += ($pengali_type[$key] == 1 ? $qty[$key] : $roll[$key]) * $value;
											}?>
											<hr style='margin:5px 0' />
											<b> <?=str_replace(',00', '', number_format($subtotal,'0',',','.')).'<br/>';?></b>
										</td>
										<td class='status_column'>
											<?if ($row->diskon != 0) {
												echo $row->diskon;
											};?> 
										</td>
										<td>
											<?=$row->nama_supplier;?> 
										</td>
										<td>
											<?=$row->nama_gudang;?>
										</td>
										<td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td>
										<td>
											<?if ($row->keterangan < 0) { ?>
												<span style='color:red'>belum lunas</span>
											<?}else if ($row->keterangan >= 0){
												$pembayaran_hutang_id = explode(',', $row->pembayaran_hutang_id);
												$tanggal_bayar = explode(',', $row->tanggal_bayar);
												foreach ($pembayaran_hutang_id as $key => $value) { 
													if (isset($tanggal_bayar[$key])) {?>
														<a target='_blank' href="<?=base_url().is_setting_link('finance/hutang_payment_form');?>?id=<?=$value;?>" style='color:blue'>
															<i class='fa fa-search'></i> <?=is_reverse_date($tanggal_bayar[$key]);?>
														</a>
													<?}?>
												<?}
												?>
											<?}?> 
										</td>
									</tr>
								<? $idx++;} ?>
							</tbody>
						</table>
						<hr/>
						<table class='table'>
							<tr>
								<th class='text-center'>TRANSAKSI</th>
								<th class='text-center'>NILAI</th>
							</tr>
							<tr style='font-size:1.2em;font-weight:bold;'>
								<td class='text-center'><?=$idx;?></td>
								<td class='text-center'><b><?=number_format($g_total,'0',',','.');?></b> </td>
							</tr>
						</table>
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
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {
	
	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	$("#barang_id_select, #warna_id_select, #supplier_id_select").select2();

});
</script>
