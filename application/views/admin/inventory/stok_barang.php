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
						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<h4><b>Tanggal Stok: </b><input name='tanggal' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'> <button class='btn btn-xs default'><i class='fa fa-search'></i></button></h4>
									</form>
								</td>
								<td class='text-right'>
									<form action='<?=base_url();?>inventory/stok_barang_excel' method='get'>
										<input name='tanggal' value='<?=$tanggal;?>' hidden>
										<button class='btn green'><i class='fa fa-download'></i> Excel</button>
									</form>

									<?if (is_posisi_id() == 1) {?>
										<form action='<?=base_url();?>inventory/stok_barang_detail_excel' method='get'>
											<input name='tanggal' value='<?=$tanggal;?>' hidden>
											<button class='btn green'><i class='fa fa-download'></i> Excel2</button>
										</form>
									<?}?>
								</td>
							</tr>
						</table>
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
									<?if (is_posisi_id() == 1) {?>
										<th scope="col" rowspan='2'>
											Status
										</th>
									<?}?>
									<th scope="col"  rowspan='2'>
										Satuan
									</th>
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th colspan='4'><?=$row->nama;?></th>
									<?}?>
									<th colspan='3'>TOTAL</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) {
										?>
										<th>Sat. Kecil</th>
										<th>Sat. Besar</th>
										<th>Eceran</th>
										<th><i class='fa fa-list'></i></th>

									<?}?>
									<th>Sat. Kecil</th>
									<th>Sat. Besar</th>
									<th>Eceran</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_barang_eceran as $row) {
									$stok_eceran[$row->gudang_id][$row->barang_id][$row->warna_id] = $row->qty_stok;
								}?>
								<?foreach ($stok_barang as $row) { ?>
									<tr>
										
										<td style='text-align:left'>
												<?=$row->nama_barang_jual;?> <?=$row->nama_warna_jual;?>
											<?if (is_posisi_id() == 1) {?>
												<?=$row->barang_id?> <?=$row->warna_id;?>
											<?}?>
										</td>
										<?if (is_posisi_id() == 1) {?>
											<td>
												<?if ($row->status_barang == 0) { ?>
													<span style='color:red'>Tidak Aktif</span> 
												<? }else{?>
													Aktif
												<?} ?>
											</td>
										<?}?>
										<td><?=$row->nama_satuan;?> /  <?=$row->nama_packaging;?></td>
										<?
										$subtotal_qty = 0;
										$subtotal_roll = 0;
										$subtotal_eceran = 0;
										foreach ($this->gudang_list_aktif as $isi) {
											$qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = (!isset($qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id]) ? $qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = 0 : $qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id]);
											$roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = (!isset($roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id]) ? $roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = 0 : $roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id]);
											$nama_qty = 'gudang_'.$isi->id.'_qty';
											$nama_roll = 'gudang_'.$isi->id.'_roll';
											
											$subtotal_qty += $row->$nama_qty;
											$subtotal_roll += $row->$nama_roll;
											$qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] += $row->$nama_qty;
											$roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] += $row->$nama_roll;
											$stok_e = '';
											if(isset($stok_eceran[$isi->id][$row->barang_id][$row->warna_id])){
												$subtotal_eceran += $stok_eceran[$isi->id][$row->barang_id][$row->warna_id];
												$stok_e = $stok_eceran[$isi->id][$row->barang_id][$row->warna_id];
												$stok_e = str_replace(",00","",number_format($stok_e,'2',',','.'));
											}
											?>
											<td><?=(float)$row->$nama_qty?></td>
											<td><?=number_format($row->$nama_roll,'0',',','.');?></td>
											<td><?=$stok_e;?></td>
											<td>									
												<a href="<?=base_url().is_setting_link('inventory/kartu_stok').'/'.$isi->id.'/'.$row->barang_id.'/'.$row->warna_id;?>" class='btn btn-xs yellow-gold' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>
											</td>
										<?}?>

										<td>
											<b><?=(float)$subtotal_qty;?></b> 
										</td>
										<td>
											<b><?=number_format($subtotal_roll,'0',',','.');?></b>											
										</td>
										<td>
											<b><?=number_format($subtotal_eceran,'0',',','.');?></b>											
										</td>
									</tr>
								<? } ?>
								
							</tbody>
						</table>
						<hr/>
						<?if (is_posisi_id() <= 3) { ?>
							<table class='table' style='font-size:1.5em;'>
								<thead>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th colspan='2' class='text-center' ><?=$row->nama;?></th>
										<?}?>
										<th colspan='2' class='text-center'>TOTAL</th>
									</tr>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th class='text-center'>Qty</th>
											<th class='text-center'>Roll</th>
										<?}?>
										<th class='text-center'>Qty</th>
										<th class='text-center'>Roll</th>
									</tr>
								</thead>
								<tbody>
									<?
									foreach ($qty_gudang as $key => $value) {
										// echo($key).'==';
										// print_r($value);echo'<br/>';
									}
									foreach ($this->satuan_list_aktif as $row) {
										foreach ($this->satuan_list_aktif as $row2) {
											$total_qty = 0;
											$total_roll = 0;
											if (isset($qty_gudang[$row->id.$row2->id])) {?>
												<tr>
													<?foreach ($this->gudang_list_aktif as $col) {?>
														<?if (isset($qty_gudang[$row->id.$row2->id][$col->id])) {
															$total_qty += $qty_gudang[$row->id.$row2->id][$col->id];
															$total_roll += $roll_gudang[$row->id.$row2->id][$col->id];
															$qty = $qty_gudang[$row->id.$row2->id][$col->id];
															$roll = $roll_gudang[$row->id.$row2->id][$col->id];
														}else{
															$qty = 0;
															$roll = 0;
														}?>
														<td class='text-center'>
															<?=$qty;?> <?=$row->nama;?>
														</td>
														<td class='text-center'>
															<?=$roll;?> <?=$row2->nama;?>
														</td>
													<?}?>
													<td class='text-center'><b> <?=$total_qty?> <?=$row->nama;?></b></td>
													<td class='text-center'><b><?=$total_roll?> <?=$row2->nama;?></b></td>
												</tr>
											<?}
										}
									}?>
								</tbody>
							</table>
						<?}?>
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
		// "ordering":false,
		"orderClasses": false
	});

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	// $("#general_table").DataTable({
 //   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
 //            var status = $('td:eq(6)', nRow).text().split('??');
 //            var id = status[0];
 //            var satuan_id = status[1];
 //            var status_aktif = $('td:eq(0)', nRow).text();
 //            if (status_aktif == 1 ) {
 //            	var btn_status = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
 //            }else{
 //            	var btn_status = "<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>";
 //            };
 //           	var action = "<span class='id' hidden='hidden'>"+id+"</span><span class='satuan' hidden='hidden'>"+satuan_id+"</span><span class='status_aktif' hidden='hidden'>"+status_aktif+"</span><a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>"+btn_status;
            
 //            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
 //            $('td:eq(0)', nRow).addClass('status_column');
 //            $('td:eq(1)', nRow).html('<span class="nama">'+$('td:eq(1)', nRow).text()+'</span>');
 //            $('td:eq(2)', nRow).html('<span class="nama_jual">'+$('td:eq(2)', nRow).text()+'</span>');
 //            $('td:eq(4)', nRow).html('<span class="harga_jual">'+change_number_format($('td:eq(4)', nRow).text())+'</span>');
 //            $('td:eq(5)', nRow).html('<span class="harga_beli">'+change_number_format($('td:eq(5)', nRow).text())+'</span>');
 //            $('td:eq(6)', nRow).html(action);
 //            // $(nRow).addClass('status_aktif_'+status_aktif);
            
 //        },
 //        "bStateSave" :true,
	// 	"bProcessing": true,
	// 	"bServerSide": true,
	// 	"sAjaxSource": baseurl + "master/data_barang"
	// });

	// var oTable;
 //    oTable = $('#general_table').dataTable();
 //    oTable.fnFilter( 1, 0 );

	// $('#status_aktif_select').change(function(){
	// 	oTable.fnFilter( $(this).val(), 0 ); 
	// });
	
   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=barang_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
   		$('#form_edit_data [name=nama_jual]').val($(this).closest('tr').find('.nama_jual').html());
   		$('#form_edit_data [name=harga_beli]').val($(this).closest('tr').find('.harga_beli').html());
   		$('#form_edit_data [name=harga_jual]').val($(this).closest('tr').find('.harga_jual').html());
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=barang';
   		window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=barang_list');
   	});

   	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=nama]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});
});
</script>
