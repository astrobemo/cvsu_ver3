<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>

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

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<!-- <form action="<?=base_url('retur_beli/retur_beli_list_insert')?>" class="form-horizontal" id="form_add_data" method="post"> -->
						<form action="<?=base_url('retur_beli/pembelian_list_retur')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Retur Beli Baru</h3>	                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

							<div class="form-group supplier_section">
			                    <label class="control-label col-md-3">Invoice Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='add-select-supplier'>
			                    		<select name="pembelian_id" class='form-control' id="pembelian_id_select">
			                				<option value=''>Pilih</option>
			                				<?foreach ($pembelian_list as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=($row->no_faktur != '' ?  $row->no_faktur : ($row->no_surat_jalan != '' ? $row->no_surat_jalan :  ($row->nama_supplier .' - '.is_reverse_date($row->tanggal)) ));?></option>
				                    		<? } ?>
				                    	</select>
										<small>tanggal invoice >= <?=is_reverse_date($max_tanggal);?></small>
			                    	</div>
			                    </div>
			                </div>
			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('retur_beli/retur_beli_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Retur Beli Edit</h3>
							
							
			                <div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' hidden='hidden'>
			                    	<select class='form-control input1' name='retur_type_id' id='retur_type_edit'>
		                    			<option <??> value='1'>Pelanggan</option>
		                    			<option <??> value='2'>Non Pelanggan</option>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

			                <div class="form-group supplier_section">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='edit-select-supplier'>
			                    		<select name="supplier_id" class='form-control'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->supplier_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                    	</div>
			                    	<div id='edit-input-supplier'>
			                    		<input name='nama_keterangan' class='form-control'>
			                    	</div>
			                    </div>
			                </div>

			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="" selected>All</option>
								<option value="0">Aktif</option>
								<!-- <option value="0">Tidak Aktif</option> -->
								<option value="-1">Batal</option>

							</select>
							<a href='#portlet-config' data-toggle='modal' class='btn btn-sm btn-default'><i class='fa fa-plus'></i> Tambah</a>

						</div>
					</div>
					<div class="portlet-body">
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Roll
									</th>
									<th scope="col">
										Nama Barang
									</th>
									<th scope="col">
										Gudang
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col" class='text-center'>
										Total
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										Actions
									</th>
									
								</tr>
							</thead>
							<tbody>
								<?foreach ($retur_list as $row) { ?>
									<tr>
										<?
											$qty = explode(',', $row->qty);
											$jumlah_roll = explode(',', $row->jumlah_roll);
											$nama_barang = explode(',', $row->nama_barang);
											$nama_warna = explode(',', $row->nama_warna);
											$nama_gudang = explode(',', $row->nama_gudang);
											$harga = explode(',', $row->harga);

										?>
										<td  class='status_column'>
											<?=$row->status_aktif?>
										</td>
										<td >
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td >
											<?=$row->no_faktur_lengkap;?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo (float)$row->qty.'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo $jumlah_roll[$key].'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo $nama_barang[$key].' '.$nama_warna[$key].'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo $nama_gudang[$key].'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo number_format($harga[$key],'0',',','.').'<br>';
											}?>
										</td>
										<td  class='text-center'>
											<?foreach ($qty as $key => $value) {
												echo number_format($harga[$key] * $qty[$key],'0',',','.').'<br>';
											}?>
										</td>
										<td >
											<?=$row->nama_supplier;?>
										</td>
										<td >
											<a class='btn btn-xs green' target='_blank' href="<?=base_url().is_setting_link('retur_beli/retur_beli_detail');?>?id=<?=$row->id;?>"><i class='fa fa-edit'></i></a>
											<?if ($row->status_aktif == 1 && is_posisi_id() <= 3) {?>
												<button class="btn btn-xs red" onclick="statRetur('<?=$row->id;?>','1')"><i class="fa fa-times"></i></button>
											<?}else if(is_posisi_id() <= 3){?>
												<button class="btn btn-xs blue" onclick="statRetur('<?=$row->id;?>','2')"><i class="fa fa-play"></i></button>
											<?}?>
										</td>
										
									</tr>
								<?}?>
									
							</tbody>
						</table>
						<!-- <button class='btn blue btn-test'>test</button> -->
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
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {
	$('#pembelian_id_select').select2({
	        allowClear: true
	});
	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( '', 9 );

	$('#status_select').change(function(){
		oTable.fnFilter( $(this).val(), 9 ); 
	});

	$("#retur_type_add").change(function(){
		if ($(this).val() == 1) {
			$('#add-select-supplier').show();
			$('#add-input-supplier').hide();
		}else{
			$('#add-select-supplier').hide();
			$('#add-input-supplier').show();
		};
	});

	// $("#retur_type_edit").change(function(){
	// 	if ($(this).val() == 1) {
	// 		$('#form_edit_data .supplier_section').show();
	// 	}else{
	// 		$('#form_edit_data .supplier_section').hide();
	// 		$('#form_edit_data [name=supplier_id]').val('');
	// 	};
	// });


	$('.supplier-input, .gudang-input').click(function(){
		$('#form_add_data .supplier-input').removeClass('supplier-input');
	})

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});


	<?if (is_posisi_id() < 3) {?>
		$('#general_table').on('click','.btn-batal',function(){
			var faktur = $(this).closest('tr').find('.no_faktur').html();
			var id = $(this).closest('tr').find('.id').html();
			bootbox.confirm("Yakin membatalkan retur ? ", function(respond){
				if (respond) {
					window.location.replace(baseurl+'retur_beli/retur_pembelian_batal?id='+id);
				};
			});
		});
	<?};?>

	$('.btn-save').click(function(){
		var tanggal = $('#form_add_data [name=tanggal]').val();
		var retur_type_id = $('#form_add_data [name=retur_type_id]').val();
		var supplier_id = $('#form_add_data [name=supplier_id]').val();

		if (tanggal != '') {
			if (retur_type_id == 1) {
				if (supplier_id != '') {
					$('#form_add_data').submit();
				}else{
					notific8("ruby", "Mohon isi supplier data");
				};
			}else{
				$('#form_add_data [name=supplier_id]').val('');
				$('#form_add_data').submit();
			};
		}else{
			notific8("ruby", "Mohon isi tanggal");
		};
		
	});
});

<?if (is_posisi_id() <= 3) {?>
	function statRetur(id, stat){
		const tipe= (stat == 1 ? 'membatalkan' : 'mengaktifkan');
		const status = (stat == 1 ? 0 : 1);
		bootbox.confirm(`Yakin ${tipe} retur ini ? `, function(respond){
			if (respond) {
				updateStatRetur(id, status)
			}
		})
	}
	
	async function updateStatRetur(id, status){
		const response = await fetch(baseurl+"retur_beli/retur_beli_list_batal",{
			method:"POST",
			headers:{
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			body:`id=${id}&status=${status}`
		});
		isSuccess = await response.json();
		if(isSuccess){
			window.location.reload();
		}
	}
<?}?>

</script>
