<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/warna_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan Beli<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" autocomplete='off' class="form-control input1 check-double" name="warna_beli"/>
				                    	<span class='check-double-info'></span>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan Jual<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" autocomplete='off' class="form-control check-double" name="warna_jual"/>
				                    	<span class='check-double-info'></span>
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
						<form action="<?=base_url('master/warna_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan Beli<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input hidden='hidden' type="text" name="warna_id"/>
				                    	<input type="text" class="form-control" name="warna_beli"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan Jual<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="warna_jual"/>
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
							<select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th class='status_column'>
										Status
									</th>
									<th scope="col">
										Keterangan Beli
									</th>
									<th scope="col">
										Keterangan Jual
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($warna_list as $row) { ?>
									<tr>
										<td class="status_column">
											<?=$row->status_aktif?>
										</td>
										<td>
											<span class='warna_beli'><?=$row->warna_beli;?></span> 
										</td>
										<td>
											<span class='warna_jual'><?=$row->warna_jual;?></span> 
										</td>										
										<td>
											<span class='id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> ><?=$row->id;?></span>
											<span class='status_aktif' hidden='hidden'><?=$row->status_aktif;?></span>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
											<?
												if ($row->status_aktif == 1 ) { ?>
									            	<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>
									            <?}else{?>
									            	<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>
									            <?}
											?>
										</td>
									</tr>
								<? } ?>

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
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script>
jQuery(document).ready(function() {       
   	
   	TableAdvanced.init();

   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=warna_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=warna_beli]').val($(this).closest('tr').find('.warna_beli').html());
   		$('#form_edit_data [name=warna_jual]').val($(this).closest('tr').find('.warna_jual').html());
   	});

   	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=nama]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=warna';
   		window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=warna_list');
   	});

   	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

	$('#status_aktif_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$(document).on('input','.check-double', function(){
   		var result = '';
   		if ($(this).val() != '') {
   			result = check_double_data($(this), 'warna');
   		}else{
            $(this).closest('div').find(".check-double-info").html('');
   		}
   	});
});
</script>
