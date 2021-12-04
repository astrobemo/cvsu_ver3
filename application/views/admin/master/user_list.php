<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css'); ?>"/>
<div class="page-content">
	<div class='container'>
		
		<div id="ajax-modal" class="modal fade" tabindex="-1">
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/user_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Username<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control input1" name="username"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Password<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="password" class="form-control" name="password"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Posisi<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name="posisi_id">
				                    		<?foreach ($posisi_list as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->name;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Jam Kerja<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<div class="input-group">
											<input name='time_start' type="text" class="form-control timepicker timepicker-24" value='07:30'>
											<span class="input-group-addon">
											to </span>
											<input name='time_end' type="text" class="form-control timepicker timepicker-24" value='17:30'>
											<!-- <input type="text" class="form-control timepicker timepicker-24"> -->
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
						<form action="<?=base_url('master/user_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Username<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input hidden='hidden' type="text" name="user_id"/>
				                    	<input type="text" class="form-control" name="username"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Password<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="password" class="form-control" name="password"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Posisi<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name="posisi_id">
				                    		<?foreach ($posisi_list as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->name;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Jam Kerja<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<div class="input-group">
											<input name='time_start' type="text" class="form-control timepicker timepicker-24">
											<span class="input-group-addon">
											to </span>
											<input name='time_end' type="text" class="form-control timepicker timepicker-24">
											<!-- <input type="text" class="form-control timepicker timepicker-24"> -->
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
									<th scope="col">
										Username
									</th>
									<th scope="col">
										Posisi
									</th>
									<th scope="col">
										Jam Mulai
									</th>
									<th scope="col">
										Jam Selesai
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($user_list as $row) { 
									if ($row->id != 1) {?>
										<tr class='status_aktif_<?=$row->status_aktif;?>'>
											<td>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<span class='username'><?=$row->username;?></span> 
											</td>
											<td>
												<span class='posisi_id' hidden="hidden"><?=$row->posisi_id;?></span>
												<?=$row->posisi_name;?>
											</td>
											<td>
												<span class='time_start'><?=date('H:i', strtotime($row->time_start));?></span> 
											</td>
											<td>
												<span class='time_end'><?=date('H:i',strtotime($row->time_end));?></span> 
											</td>
											<td>
												<span class='status_aktif' hidden='hidden'><?=$row->status_aktif;?></span>
												<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
												<?
													if ($row->status_aktif == 1 ) { ?>
										            	<a href="<?=base_url('master/user_list_status_update')?>?id=<?=$row->id;?>&status_aktif=0" class='btn-xs btn red btn-status'><i class='fa fa-times'></i> </a>
										            <?}else{?>
										            	<a href="<?=base_url('master/user_list_status_update')?>?id=<?=$row->id;?>&status_aktif=1"  class='btn-xs btn blue btn-status'><i class='fa fa-play'></i> </a>
										            <?}
												?>
											</td>
										</tr>
									<?}?>
								<? } ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/nd_user_manage.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'); ?>"  type="text/javascript"></script></script>
<script src="<?php echo base_url('assets/admin/pages/scripts/components-pickers.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script>
jQuery(document).ready(function() {       
   
   	FormAddUser.init();
   	FormEditUser.init();
   	ComponentsPickers.init();

	TableAdvanced.init();
   	

   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=user_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=posisi_id]').val($(this).closest('tr').find('.posisi_id').html());
   		$('#form_edit_data [name=username]').val($(this).closest('tr').find('.username').html());
   		$('#form_edit_data [name=time_start]').val($(this).closest('tr').find('.time_start').html());
   		$('#form_edit_data [name=time_end]').val($(this).closest('tr').find('.time_end').html());
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=user';
   		window.location.replace("<?=base_url();?> master/ubah_status_aktif?data_sent="+data+'&link=user_list');
   	});

   	// $('.btn-edit-save').click(function(){
   	// 	if( $('#form_edit_data [name=username]').val() != '' && $('#form_edit_data [name=password]').val() != '' ){
   	// 		$('#form_edit_data').submit();
   	// 	}
   	// });
});
</script>
