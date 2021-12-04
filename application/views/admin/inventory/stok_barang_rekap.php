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
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<!-- <select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a> -->
						</div>
					</div>
					<div class="portlet-body">
						<form action='' method='get'>
						<h4><b>Tanggal Stok: </b><input name='tanggal' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'> <button class='btn btn-xs default'><i class='fa fa-search'></i></button></h4>
						</form>
						<hr/>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<!-- <th scope="col" rowspan='2'>
										Nama Beli
									</th> -->
									<th scope="col" rowspan='2'>
										Nama Jual
									</th>
									<th scope="col" rowspan='2'>
										Status
									</th>
									<!-- <th scope="col">
										Satuan
									</th> -->
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th colspan='2'><?=$row->nama;?></th>
									<?}?>
									<th colspan='2'>TOTAL</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th style='text-align:right'>Yard/Kg</th>
										<th style='text-align:right'>Jumlah Roll</th>
									<?}?>
									<th style='text-align:right'>Yard/Kg</th>
									<th style='text-align:right'>Jumlah Roll</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_barang as $row) { ?>
									<tr>
										<!-- <td>
											<span class='barang_id' hidden="hidden"><?=$row->barang_id;?></span>
											<?//=$row->nama_barang;?> <?=$row->nama_warna;?>
											<?//=$row->barang_id;?><?//=$row->warna_id;?>
										</td> -->
										<td style='text-align:left'>
											<a target='_blank' href="<?=base_url().is_setting_link('inventory/stok_barang_by_barang');?>?barang_id=<?=$row->barang_id?>&tanggal=<?=is_date_formatter($tanggal);?>">
												<?=$row->nama_barang_jual;?>
											</a>
										</td>
										<td>
											<?if ($row->status_barang == 0) { ?>
												<span style='color:red'>Tidak Aktif</span> 
											<? }else{?>
												Aktif
											<?} ?>
										</td>
										<?
										$subtotal_qty = 0;
										$subtotal_roll = 0;
										foreach ($this->gudang_list_aktif as $isi) { ?>
											<?
											$qty = $isi->nama.'_qty';
											$roll = $isi->nama.'_roll';
											$subtotal_qty += $row->$qty;
											$subtotal_roll += $row->$roll;
											?>
											<td style='text-align:right'><?=number_format($row->$qty,'2',',','.');?> <?=$row->nama_satuan;?></td>
											<td style='text-align:right'><?=number_format($row->$roll,'0',',','.');?></td>
										<?}?>

										<td style='text-align:right'>
											<b><?=number_format($subtotal_qty,'2',',','.');?></b> 
										</td>
										<td style='text-align:right'>
											<b><?=number_format($subtotal_roll,'0',',','.');?></b>											
										</td>
									</tr>
								<? } ?>

							</tbody>
						</table>

						<form action='<?=base_url();?>inventory/stok_barang_excel' method='get'>
							<input name='tanggal' value='<?=$tanggal;?>' hidden>
							<button class='btn green'><i class='fa fa-download'></i> Excel</button>
						</form>
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

<script>
jQuery(document).ready(function() {
	//Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").DataTable({
		"ordering":false,
		"orderClasses": false
	});

	
});
</script>
