<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table tr td{
	color:#000;
}

.batal{
	background: #ccc;
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
						<div class="tools">
							<a href="" class="fullscreen">
							</a>
						</div>
					</div>
					<div class="portlet-body">

						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<table>
											<tr>
												<td>
													<table>
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

														<tr>
															<td>Gudang</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<b>
																	<select class='form-control' name='gudang_id'>
																		<?foreach ($this->gudang_list_aktif as $row) { 
																			if ($gudang_id == $row->id) {
																				$nama_gudang = $row->nama;
																			}
																			?>
																			<option <?=($gudang_id == $row->id ? 'selected' : '');?> value='<?=$row->id?>'><?=$row->nama;?></option>
																		<?}?>
																	</select>
																</b>
															</td>
														</tr>
													</table>
												</td>
												<td>
												</td>
											</tr>
										</table>
																	
									</form>
								</td>
								<td class='text-right'>
									<a href="<?=base_url().'inventory/mutasi_persediaan_barang_excel?tanggal_start='.is_date_formatter($tanggal_start).'&tanggal_end='.is_date_formatter($tanggal_end).'&toko_id='.$toko_id.'&gudang_id='.$gudang_id;?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
								</td>
							</tr>
						</table>

									
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" style='width:150px;' rowspan='2' class='text-center'>
										Nama
									</th>
									<th scope="col" rowspan='2' class='text-center'>
										Harga Sat.
									</th>
									<th scope="col" colspan='3' style='border-bottom:1px solid #ccc'>
										STOK PER
										( <?=strtoupper(date('d M Y', strtotime(is_date_formatter($tanggal_start))));?> )
									</th>
									<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
										PEMBELIAN
									</th>
									<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
										PENJUALAN
									</th>
									<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
										MUTASI masuk <?=$nama_gudang;?>
									</th>
									<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
										MUTASI keluar <?=$nama_gudang;?>
									</th>
									<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
										Penyesuaian <?=$nama_gudang;?>
									</th>
									<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
										RETUR
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										SALDO AKHIR
									</th>
								</tr>
								
								<tr>
									<th>Yard</th>
									<th>Roll</th>
									<th class='text-center'>Nilai</th>
									<th>Yard</th>
									<th>Roll</th>
									<th class='text-center'>Nilai</th>
									<th>Yard</th>
									<th>Roll</th>
									<th class='text-center'>Nilai</th>
									<th>Yard</th>
									<th>Roll</th>
									<th class='text-center'>Nilai</th>
									<th>Yard</th>
									<th>Roll</th>
									<th class='text-center'>Nilai</th>
									<th>Yard</th>
									<th>Roll</th>
									<th class='text-center'>Nilai</th>
									<th>Yard</th>
									<th>Roll</th>
									<th class='text-center'>Nilai</th>
									<th>Yard</th>
									<th>Roll</th>
								</tr>
							</thead>
							<tbody>
								<?
								$qty_stock = 0;
								$roll_stock = 0;
								$qty_beli = 0;
								$roll_beli = 0;
								$qty_jual = 0;
								$roll_jual = 0;
								$qty_mutasi_masuk = 0;
								$roll_mutasi_masuk = 0;
								$qty_mutasi_keluar = 0;
								$roll_mutasi_keluar = 0;
								$qty_penyesuaian = 0;
								$roll_penyesuaian = 0;
								$qty_retur = 0;
								$roll_retur = 0;

								foreach ($mutasi_barang_list as $row) { 
									$total_qty = 0;
									$total_roll = 0;

									?>
									<tr>
										<td><?=$row->nama_jual;?> <?=$row->warna_jual;?></td>
										<td class='text-center'><?=number_format($row->hpp,'0',',','.');?></td>
										
										<?
											$qty_stock += $row->qty_stock;
											$roll_stock += $row->jumlah_roll_stock;
											$qty_beli += $row->qty_beli;
											$roll_beli += $row->jumlah_roll_beli;
											$qty_jual += $row->qty_jual;
											$roll_jual += $row->jumlah_roll_jual;
											$qty_mutasi_masuk += $row->qty_mutasi_masuk;
											$roll_mutasi_masuk += $row->jumlah_roll_mutasi_masuk;
											$qty_mutasi_keluar += $row->qty_mutasi;
											$roll_mutasi_keluar += $row->jumlah_roll_mutasi;
											$qty_penyesuaian += $row->qty_penyesuaian;
											$roll_penyesuaian += $row->jumlah_roll_penyesuaian;
											$qty_retur += $row->qty_retur;
											$roll_retur += $row->jumlah_roll_retur;
										?>

										<td><?=number_format($row->qty_stock,'0',',','.');?></td>
										<td><?=number_format($row->jumlah_roll_stock,'0',',','.')?></td>
										<td class='text-center'><?=number_format($row->qty_stock * $row->hpp,'0',',','.')?></td>
										
										<td><?=number_format($row->qty_beli,'0',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_beli,'0',',','.')?></td>
										<td class='text-center'><?=number_format($row->qty_beli * $row->hpp_beli,'0',',','.')?></td>

										<?
										$total_nilai =($row->hpp * $row->qty_stock) + ($row->hpp_beli * $row->qty_beli);
										$total_qty_stock = $row->qty_stock + $row->qty_beli;
										if ($total_qty_stock == 0) {
										 	$total_qty_stock = 1;
										}
										$hpp_all = $total_nilai / $total_qty_stock;?>
										<td><?=number_format($row->qty_jual,'0',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_jual,'0',',','.')?></td>
										<td class='text-center'><?=number_format($row->qty_jual * $hpp_all,'0',',','.')?></td>
										
										<td><?=number_format($row->qty_mutasi_masuk,'0',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_mutasi_masuk,'0',',','.')?></td>
										<td class='text-center'><?=number_format($row->qty_mutasi_masuk * $hpp_all,'0',',','.')?></td>

										<td><?=number_format($row->qty_mutasi,'0',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_mutasi,'0',',','.')?></td>
										<td class='text-center'><?=number_format($row->qty_mutasi * $hpp_all,'0',',','.')?></td>

										<td><?=number_format($row->qty_penyesuaian,'0',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_penyesuaian,'0',',','.')?></td>
										<td class='text-center'><?=number_format($row->qty_penyesuaian * $hpp_all,'0',',','.')?></td>

										<td><?=number_format($row->qty_retur,'0',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_retur,'0',',','.')?></td>
										<td class='text-center'><?=number_format($row->qty_retur * $row->hpp,'0',',','.')?></td>

										<td><?=number_format($row->qty_stock + $row->qty_beli - $row->qty_jual + $row->qty_mutasi_masuk - $row->qty_mutasi+ $row->qty_retur ,'0',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual + $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi + $row->jumlah_roll_retur,'0',',','.')?></td>
										
									</tr>	
								<?}?>
							</tbody>
						</table>
						<hr>
						<table class='table'>
							<tr>
								<th colspan='2'>STOK</th>
								<th colspan='2'>PEMBELIAN</th>
								<th colspan='2'>PENJUALAN</th>
								<th colspan='2'>MUTASI IN</th>
								<th colspan='2'>MUTASI OUT</th>
								<th colspan='2'>PENYESUAIAN</th>
								<th colspan='2'>RETUR</th>
								<th colspan='2'>AKHIR</th>
							</tr>
							<tr>
								<th>Yard</th>
								<th>Roll</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Yard</th>
								<th>Roll</th>
							</tr>
							<tr>
								<td><?=number_format($qty_stock,'2',',','.');?></td>
								<td><?=number_format($roll_stock,'2',',','.');?></td>
								<td><?=number_format($qty_beli,'2',',','.');?></td>
								<td><?=number_format($roll_beli,'2',',','.');?></td>
								<td><?=number_format($qty_jual,'2',',','.');?></td>
								<td><?=number_format($roll_jual ,'2',',','.');?></td>
								<td><?=number_format($qty_mutasi_masuk,'2',',','.');?></td>
								<td><?=number_format($roll_mutasi_masuk,'2',',','.');?></td>
								<td><?=number_format($qty_mutasi_keluar,'2',',','.');?></td>
								<td><?=number_format($roll_mutasi_keluar,'2',',','.');?></td>
								<td><?=number_format($qty_penyesuaian,'2',',','.');?></td>
								<td><?=number_format($roll_penyesuaian,'2',',','.');?></td>
								<td><?=number_format($qty_retur,'2',',','.');?></td>
								<td><?=number_format($roll_retur,'2',',','.');?></td>
								<td><?=''?></td>
								<td><?=''?></td>
								
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>


<script>
jQuery(document).ready(function() {
	

	$("#general_table").DataTable({
		"ordering":false
	});

	setTimeout(function(){
		$('#info-section').toggle('slow');
	},7000);


	$('#barang_id_select, #warna_id_select,#barang_id_select2, #warna_id_select2, #barang_select, #warna_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('.date-picker-month').datepicker({
        autoclose : true,
        format: "MM yyyy"
	});

});
</script>
