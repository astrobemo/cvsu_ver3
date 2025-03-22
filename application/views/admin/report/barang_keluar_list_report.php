<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


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
									<td>Customer</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='customer_id'>
											<option <?=($customer_id == '' ? "selected" : "");?> value="">Pilih..</option>
											<?foreach ($this->customer_list_aktif as $row) { ?>
												<option <?=($customer_id == $row->id ? "selected" : "");?>  value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Toko</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='toko_id'>
											<?foreach ($this->toko_list_aktif as $row) { ?>
												<option <?=($toko_id == $row->id ? "selected" : "");?>   value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Barang</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='barang_id' id='barang_select'>
											<option value='0'>Semua</option>
											<?foreach ($this->barang_list_aktif as $row) { ?>
												<option <?=($barang_id == $row->id ? "selected" : "");?>   value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Periode</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
											s/d
											<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
										</b>
									</td>
								</tr>

							</table>
							
							
						</form>
						<hr/>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Nama
									</th>
									<th scope="col">
										Sat.Kecil
									</th>
									<th scope="col">
										Sat.Besar
									</th>
									<!-- <th scope="col">
										Frequency
									</th> -->
									<th scope="col">
										Transaksi
									</th>
									<!-- <th scope="col">
										HPP
									</th> -->
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$idx = 1; $qty_total = 0; $roll_total = 0;
								foreach ($barang_list as $row) { 
									$qty_total += $row->qty;
									$roll_total += $row->jumlah_roll;
									?>
									<tr>
										<td><?=$idx?></td>
										<td>
											<span class='nama'><?=$row->nama_beli.' '.$row->nama_warna;?></span> 
										</td>
										<td>
											<span class='harga_beli'><?=(float)number_format($row->qty,'2','.','.');?></span> 
											<?=$row->nama_satuan;?>
										</td>
										<td>
											<?=$row->jumlah_roll;?>
											<?=$row->nama_packaging;?>
										</td>
										<!-- <td>
											<?
												$d1 = strtotime(is_date_formatter($tanggal_start));
												$d2 = strtotime(is_date_formatter($tanggal_end));
												$diff = $d2-$d1;
												$date_diff = round($diff / (60 * 60 * 24)) + 1;

												echo number_format($row->qty/$date_diff,'2',',','.').'<br>';
											?>
										</td> -->
										<td>
											<?=$row->count;?>
										</td>
										<!-- <td>
											<?=number_format($row->harga_rata,'2',',','.');?>
										</td> -->
										<td>
											<a href="<?=base_url().is_setting_link('report/barang_keluar_list_detail_report');?>?toko_id=<?=$toko_id;?>&customer_id=<?=$customer_id?>&tanggal_start=<?=$tanggal_start;?>&tanggal_end=<?=$tanggal_end;?>&barang_id=<?=$row->barang_id;?>&warna_id=<?=$row->warna_id;?>" class='btn btn-xs yellow-gold' target="_blank"><i class='fa fa-search'></i></a>
										</td>
									</tr>
								<? 
								$idx++;
								} ?>

								<!-- <tr style='font-size:1.1em; font-weight:bold;'>
									<td></td>
									<td class='text-right'>TOTAL</td>
									<td><?=number_format($qty_total,'2',',','.');?></td>
									<td><?=number_format($roll_total,'0',',','.')?></td>
									<td></td>
									<td></td>
									<td></td>
								</tr> -->

							</tbody>
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

	$('#barang_select').select2();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
});
</script>
