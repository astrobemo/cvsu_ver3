<div class="page-content">
	<div class='container'>
		
		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/supplier_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3> Tambah </h3>
							<hr/>
							<table style="width:90%">
								<tr>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">Nama
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control input1" name="nama"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Alamat
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="alamat"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Telepon
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Fax
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="fax"/>
						                    </div>				                    
						                </div>
									</td>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">Kota
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kota"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Kode Pos
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kode_pos"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Email
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="email"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Website
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="website"/>
						                    </div>				                    
						                </div>
									</td>
								</tr>
							</table>
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

		<div class="modal fade bs-modal-lg" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/supplier_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
							<hr/>
							<table style="width:90%">
								<tr>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">Nama
						                    </label>
						                    <div class="col-md-8">
						                    	<input hidden name="supplier_id"/>
						                    	<input type="text" class="form-control input1" name="nama"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Alamat
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="alamat"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Telepon
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Fax
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="fax"/>
						                    </div>				                    
						                </div>
									</td>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">Kota
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kota"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Kode Pos
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kode_pos"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Email
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="email"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Website
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="website"/>
						                    </div>				                    
						                </div>
									</td>
								</tr>
							</table>
				                
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
										Nama
									</th>
									<th scope="col">
										Alamat
									</th>
									<th scope="col">
										Kota
									</th>
									<th scope="col">
										Telepon
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($supplier_list as $row) { ?>
									<tr class='status_aktif_<?=$row->status_aktif;?>'>
										<td>
											<span class='nama'><?=$row->nama;?></span>
										</td>
										<td>
											<span class='alamat'><?=$row->alamat;?></span> 
										</td>
										<td>
											<span class='kota'><?=$row->kota;?></span> 
										</td>
										<td>
											<span class='telepon'><?=$row->telepon;?></span> 
										</td>
										<td>
											<span class='id' hidden ><?=$row->id;?></span>
											<span class='status_aktif' hidden><?=$row->status_aktif;?></span>
											<span hidden class='kode_pos'><?=$row->kode_pos;?></span> 
											<span hidden class='email'><?=$row->email;?></span> 
											<span hidden class='fax'><?=$row->fax;?></span>
											<span hidden class='website'><?=$row->website;?></span> 
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
   		$('#form_edit_data [name=supplier_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
   		$('#form_edit_data [name=alamat]').val($(this).closest('tr').find('.alamat').html());
   		$('#form_edit_data [name=telepon]').val($(this).closest('tr').find('.telepon').html());
   		$('#form_edit_data [name=kota]').val($(this).closest('tr').find('.kota').html());
   		$('#form_edit_data [name=kode_pos]').val($(this).closest('tr').find('.kode_pos').html());
   		$('#form_edit_data [name=fax]').val($(this).closest('tr').find('.fax').html());
   		$('#form_edit_data [name=email]').val($(this).closest('tr').find('.email').html());
   		$('#form_edit_data [name=website]').val($(this).closest('tr').find('.website').html());
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=supplier';
   		window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=supplier_list');
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
