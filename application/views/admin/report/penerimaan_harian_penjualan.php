<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
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
							<span class="caption-subject theme-font bold uppercase">
								<?=$breadcrumb_small;?>
								<b><?=$tanggal_start.' - '. $tanggal_end;?> </b>
							</span>
						</div>
					</div>
					<div class="portlet-body">
						<form action='' method='get' class='hidden-print'>
							<table>
								<tr>
									<td>Tanggal</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
											s/d
											<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
										</b>
								</tr>
							</table>
							<hr/>
						</form>
						<?
							$qty = 0;
							$roll = 0;
							?>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr> 
									<!-- <th scope="col">
										Tanggal
									</th> -->
									<th>No</th>
									<th scope="col" style='width:150px !important'>
										No Faktur
									</th>
									<th scope="col">
										Nama Customer
									</th>
									<th scope="col">
										Penjualan
									</th>
									<?foreach ($pembayaran_type as $row) { ?>
										<th scope="col">
											<?=$row->nama;?>
											<?${"count_".$row->id} = 0; ${"total_".$row->id} = 0;?>
										</th>
									<?}?>
								</tr>
							</thead>
							<tbody>
								<?
								$grand_total = 0; $idx = 0;
								foreach ($penjualan_list as $row) { ?>
									<tr>
										<!-- <td><?=is_reverse_date($row->tanggal);?></td> -->
										<td><?=($idx+1);?></td>
										<td><?=$row->no_faktur;?></td>
										<td><?=$row->nama_customer;?></td>
										<td><?=number_format($row->amount,'0',',','.');?></td>
										<?
										$idx++;
										$grand_total += $row->amount;
										unset($pembayaran_type_id);
										unset($bayar);
										unset($bayar_id);
										$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
										$bayar = explode(',', $row->bayar);
										foreach ($pembayaran_type_id as $key => $value) {
											$bayar_id[$value] = $bayar[$key];
										}
										?>
										<?foreach ($pembayaran_type as $row2) { 
											$ket = '';
											if ($row2->id == 4 && $row->keterangan_transfer !='') {
												$ket = "<br><span style='font-size:0.8em'><b>(".$row->keterangan_transfer.")</b></span>";
											}
											?>
											<td>
												<?if (isset($bayar_id[$row2->id]) && $bayar_id[$row2->id] != 0) { ?>
													<?=number_format($bayar_id[$row2->id],'0',',','.');?>
													<?${"count_".$row2->id}++;?>
													<?${"total_".$row2->id}+=$bayar_id[$row2->id];?>
												<?}?>
												<?=$ket;?>
											</td>
										<?}?>
									</tr>
								<?}?>

								<tr style='font-size:1.1em;font-weight:bold; border-top:2px solid black'>
									<td colspan = '3'>Total Transaksi</td>
									<td><?=$idx;?></td>
									<?foreach ($pembayaran_type as $row2) { ?>
										<td>
											<?=${"count_".$row2->id};?>
										</td>
									<?}?>
								</tr>
								<tr style='font-size:1.1em;font-weight:bold'>
									<td colspan = '2'>Total Nilai</td>
									<td><?=number_format($grand_total,'0',',','.');?></td>
									<?foreach ($pembayaran_type as $row2) { ?>
										<td>
											<?=number_format(${"total_".$row2->id},'0',',','.');?>
										</td>
									<?}?>
								</tr>

								<tr style=' border-top:2px solid black'>
									<!-- <th scope="col">
										Tanggal
									</th> -->
									<th scope="col" colspan='2'>
										Keterangan
									</th>
									<th scope="col">
										Penjualan
									</th>
									<?foreach ($pembayaran_type as $row) { ?>
										<th scope="col">
											<?=$row->nama;?>
											<?${"count_".$row->id} = 0; ${"total_".$row->id} = 0;?>
										</th>
									<?}?>
								</tr>
								
							</tbody>
						</table>

						<?if (count($retur_list) != 0) { ?>
						<hr/>
							<div style="font-size:2em;">Retur Penjualan </div>
							<table class="table table-striped table-bordered table-hover" id="general_table_2">
							<thead>
								<tr>
									<!-- <th scope="col">
										Tanggal
									</th> -->
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Penjualan
									</th>
									<th scope="col">
										Jumlah
									</th>
								</tr>
							</thead>
							<tbody>
								
								<?foreach ($retur_list as $row) { ?>
									<tr style='background:#eee'>
										<td><?=$row->no_faktur;?></td>
										<td><?=number_format($row->amount,'0',',','.');?></td>
										<td>
											<?if ($row2->id == 2) { ?>
												- <?=number_format($row->amount,'0',',','.');?>
											<?}?>
										</td>

									</tr>
								<?}?>
							</tbody>
						</table>
						<?} ;?>

					</div>
					<div>
						<button class='btn blue hidden-print' onclick="window.print()"><i class='fa fa-print'></i> Print</button>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	
});
</script>
