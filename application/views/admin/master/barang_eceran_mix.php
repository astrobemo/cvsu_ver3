<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/barang_eceran_mix_list_update')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Add Mix Baru </h3>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" id="barang_id_select" class="form-control">
										<option value="">Pilih</option>
										<?foreach ($this->barang_list_aktif as $row) {?>
											<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
										<?}?>
									</select>
			                    </div>				                    
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Stok Eceran di Mix<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<label class='checkbox-inline'>
										<input type="checkbox" checked class="form-control" value='1' onchange="eceranMixChange()" id='eceran-mix-edit' name="eceran_mix_status" />yes</label>
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
						<form action="<?=base_url('master/barang_eceran_mix_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Add Mix Baru </h3>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input hidden name="barang_id">
			                    	<input type="text" name="nama_barang" class="form-control">
			                    </div>				                    
			                </div>
			                <hr/>

							<div class="form-group">
			                    <label class="control-label col-md-3">Stok Eceran di Mix<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<label class='checkbox-inline'>
										<input type="checkbox" checked class="form-control" value='1' onchange="eceranMixChangeEdit()" id='subitem-option' name="eceran_mix_status" />yes</label>
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

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="tabbable tabs-left" style='margin-bottom:5px'>
						<ul class="nav nav-tabs" style='padding:0px; margin:10px 0px;'>
							<li>
								<a href="<?=base_url().is_setting_link('master/barang_list')?>">
									DAFTAR BARANG
								</a> 
							</li>
							<li class='active'>
								<a >
									DAFTAR BARANG ECERAN (MIX)
								</a> 
							</li>
						</ul>
					</div>
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Nama
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($barang_list as $row) { ?>
									<tr>
										<td>
											<span class='nama_jual'><?=$row->nama_jual;?></span> 
										</td>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
											<a class="btn-xs btn red btn-remove"><i class="fa fa-times"></i> </a>
										</td>
									</tr>
								<? }?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {
	//  Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	

	// TableAdvanced.init();
	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=barang_id]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});

	
});


function tokoChange(tipe){
	if (tipe == 1) {
		let toko_id = $('#toko-id').val();
		$("#portlet-config .modal-body").css('background-color',colorToko[toko_id]);
	}else{
		let toko_id = $('#toko-id-edit').val();
		$("#portlet-config-edit .modal-body").css('background-color',colorToko[toko_id]);
		
	}
}

</script>
