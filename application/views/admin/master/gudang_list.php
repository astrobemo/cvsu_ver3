<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/gudang_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Nama<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control input1" name="nama"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Lokasi
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="lokasi"/>
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
						<form action="<?=base_url('master/gudang_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Nama<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name="gudang_list_id" hidden='hidden'/>
				                    	<input type="text" class="form-control input1" name="nama"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Lokasi
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="lokasi"/>
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
									<th scope="col" class='status_column'>
										Status
									</th>
									<th scope="col">
										Nama
									</th>
									<th scope="col">
										Lokasi
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($gudang_list as $row) { ?>
									<tr class='status_aktif_<?=$row->status_aktif;?>'>
										<td class='status_column'>
											<span class='status_aktif' hidden='hidden'><?=$row->status_aktif;?></span>
										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<span class='nama'><?=$row->nama;?></span> 
										</td>
										<td>
											<span class='lokasi'><?=$row->lokasi;?></span> 
										</td>
										<td>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i></a>
											<?if ($row->status_aktif == 1) { ?>
												<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>
											<? }else{?>
												<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>
											<?}?>
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

<script>
jQuery(document).ready(function() {       
   
   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=gudang_list_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
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

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=gudang';
   		window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=gudang_list');
   	});
});
</script>
