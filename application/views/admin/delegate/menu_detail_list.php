<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<!-- <div class="modal-dialog"> -->
				<div class="modal-content">
					<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div> -->
					<div class="modal-body">
						<form action="<?=base_url('delegate/menu_detail_insert')?>" class="form-horizontal" id="form_detail_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Controller<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input hidden='hidden' name="menu_id" value='<?=$menu_id?>'>
				                    	<select class="form-control" name="controller">
				                    		<?foreach ($controller_list as $row) { ?>
				                    			<option value='<?=$row->name;?>'><?=$row->name;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Link<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="page_link"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Text<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="text"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Urutan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="urutan"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Level<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='level'>
				                    		<option selected value='1'>1</option>
				                    		<option value='2'>2</option>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Parent<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='parent_id'>
				                    		<option value=''>Pilih</option>
				                    		<?foreach ($menu_list_parent as $row) { ?>
				                    			<option value="<?=$row->id;?>"><?=$row->text;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Status<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='status_aktif'>
				                    		<option value='0'>Tidak Aktif</option>
				                    		<option selected value='1'>Aktif</option>
				                    	</select>
				                    </div>
				                    
				                </div>			
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-detail-menu">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			<!-- </div> -->
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<!-- <div class="modal-dialog"> -->
				<div class="modal-content">
					<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div> -->
					<div class="modal-body">
						<form action="<?=base_url('delegate/menu_detail_update')?>" class="form-horizontal" id="form_detail_edit_data" method="post">
							<h3 class='block'> Edit </h3>
				                <!-- <div class="form-group">
				                    <label class="control-label col-md-3">Menu<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name="menu_id">
				                    		<?foreach ($menu_list as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->text;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div> -->

				                <div class="form-group">
				                    <label class="control-label col-md-3">Controller<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name="menu_id" value="<?=$menu_id;?>"/>
				                    	<input name="menu_detail_id" hidden='hidden'/>
				                    	<select class="form-control" name="controller">
				                    		<?foreach ($controller_list as $row) { ?>
				                    			<option value='<?=$row->name;?>'><?=$row->name;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Link<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="page_link"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Text<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="text"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Urutan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="urutan"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Level<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='level'>
				                    		<option value='1'>1</option>
				                    		<option value='2'>2</option>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Parent<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='parent_id'>
				                    		<option value=''>Pilih</option>
				                    		<?foreach ($menu_list_parent as $row) { ?>
				                    			<option value="<?=$row->id;?>"><?=$row->text;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Status<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='status_aktif'>
				                    		<option value='0'>Tidak Aktif</option>
				                    		<option value='1'>Aktif</option>
				                    	</select>
				                    </div>
				                    
				                </div>			
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-detail-menu">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			<!-- </div> -->
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
							<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										ID
									</th>
									<th scope="col">
										Controller
									</th>
									<th scope="col">
										Link
									</th>
									<th scope="col">
										Text
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col">
										Urutan
									</th>
									<th scope="col">
										Level
									</th>
									<th scope="col">
										Parent ID
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($menu_list_detail as $row) { ?>
									<tr>
										<td>
											<?=$row->id;?>
										</td>
										<td>
											<span hidden='hidden' class='menu_detail_id'><?=$row->id;?></span>
											<span class='controller'><?=$row->controller;?></span>
										</td>
										<td>
											<span class='page_link'><?=$row->page_link;?></span> 
										</td>
										<td>
											<span class='text'><?=$row->text;?></span> 
										</td>
										<td>
											<?if ($row->status_aktif == 1) { ?>
												<span class='label label-primary'>aktif</span>
											<?}else { ?>
												<span class='label label-danger'>tidak aktif</span>
											<?}?>
											<input hidden='hidden' name='status_aktif' value='<?=$row->status_aktif;?>'>
										</td>
										<td>
											<span class='urutan'><?=$row->urutan;?></span> 
										</td>
										<td>
											<span class='level'><?=$row->level;?></span> 
										</td>
										<td>
											<span class='level'><?=$row->parent_id;?></span> 
										</td>
										<td>
											<a href='#portlet-config-edit-detail' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> Edit</a>
										</td>
									</tr>	
									
								<? } ?>

							</tbody>
							<!-- <input name='menu_id' style='display:none' value="<?=$menu_id;?>"> -->
						</table>
					</div>
				</div>
			</div>
		</div>

<script>
jQuery(document).ready(function() {       
   
   	$('.btn-edit').click(function(){
   		var ini = $(this).closest('tr');
   		$('#form_detail_edit_data [name=menu_detail_id]').val(ini.find('.menu_detail_id').html());
   		$('#form_detail_edit_data [name=controller]').val(ini.find('.controller').html());
   		$('#form_detail_edit_data [name=page_link]').val(ini.find('.page_link').html());
   		$('#form_detail_edit_data [name=text]').val(ini.find('.text').html());
   		$('#form_detail_edit_data [name=urutan]').val(ini.find('.urutan').html());
   		$('#form_detail_edit_data [name=level]').val(ini.find('.parent_id').html());
   		$('#form_detail_edit_data [name=status_aktif]').val(ini.find('[name=status_aktif]').val());
   			
   	});

   	$('.btn-add-detail-menu').click(function(){
   		form = $('#form_detail_add_data');
   		var url = form.attr("action");
   		//ga bisa untuk multidimensional array
	    var formData = $(form).serializeArray();
	    // $.post(url, formData).done(function (data) {
	    //     // alert(data);
	    //     // location.reload();
	    //     // $('#ajax-modal').removeData('bs.modal');
	    // });
   		$('#form_detail_add_data').submit();
   	});

   	$('.btn-edit-detail-menu').click(function(){
   		$('#form_detail_edit_data').submit();
   	});
});
</script>
