<style>
	.use_ppn{
		background: lightpink;
	}
</style>
<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/toko_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Nama<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control input1" name="nama"/>
				                    </div>				                    
				                </div>
								
								<div class="form-group use_ppn" id='ppn_add' >
				                    <label class="control-label col-md-3">PPN
				                    </label>
				                    <div class="col-md-6">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input type="checkbox" checked value="1" class="form-control" name="use_ppn" onchange="checkPPN('ppn_add')" /></label>
										</div>		
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Alamat
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="alamat"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Telepon
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="telepon"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">FAX
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="fax"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Kota
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="kota"/>
				                    </div>				                    
				                </div>
				                
				                <div class="form-group">
				                    <label class="control-label col-md-3">Kode pos
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="kode_pos"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">NPWP
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="NPWP"/>
				                    </div>				                    
				                </div>
								
								<div class="form-group">
				                    <label class="control-label col-md-3">Warna
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="color" class="form-control" name="color_code"/>
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
						<form action="<?=base_url('master/toko_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
							

				                <div class="form-group">
				                    <label class="control-label col-md-3">Nama<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name="toko_list_id" hidden='hidden'/>
				                    	<input type="text" class="form-control input1" name="nama"/>
				                    </div>				                    
				                </div>

								<div class="form-group" id='ppn_edit'>
				                    <label class="control-label col-md-3">PPN
				                    </label>
				                    <div class="col-md-6">
										<div class="checkbox-list">
											<label class="checkbox-inline">
											<input type="checkbox" checked value="1" class="form-control" name="use_ppn" onchange="checkPPN('ppn_edit')" /></label>
										</div>				                    
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Alamat
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="alamat"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Telepon
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="telepon"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">FAX
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="fax"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Kota
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="kota"/>
				                    </div>				                    
				                </div>
				                
				                <div class="form-group">
				                    <label class="control-label col-md-3">Kode pos
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="kode_pos"/>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">NPWP
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="NPWP"/>
				                    </div>				                    
				                </div>

								<div class="form-group">
				                    <label class="control-label col-md-3">Warna
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="color" class="form-control" name="color_code"/>
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
										PPN
									</th>
									<th scope="col">
										Alamat
									</th>
									<th scope="col">
										Telepon
									</th>
									<th scope="col">
										Fax
									</th>
									<th scope="col">
										Kota
									</th>
									<th scope="col">
										Kode POS
									</th>
									<th scope="col">
										NPWP
									</th>
									<th scope="col">
										Warna
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($toko_list as $row) { ?>
									<tr>
										<td>
											<span class='nama'><?=$row->nama;?></span> 
										</td>
										<td>
											<span class='ppn'><?=($row->use_ppn ? "<i class='fa fa-check'></i>" : "");?></span> 
										</td>
										<td>
											<span class='alamat'><?=$row->alamat;?></span> 
										</td>
										<td>
											<span class='telepon'><?=$row->telepon;?></span> 
										</td>
										<td>
											<span class='fax'><?=$row->fax;?></span> 
										</td>
										<td>
											<span class='kota'><?=$row->kota;?></span>
										</td>
										<td>
											<span class='kode_pos'><?=$row->kode_pos;?></span>
										</td>
										<td>
											<span class='NPWP'><?=$row->NPWP;?></span>
										</td>
										<td style="background-color:<?=$row->color_code;?>">
											<span class='color_code'><?=$row->color_code;?></span>
										</td>
										<td>
											<span class='use_ppn' hidden="hidden"><?=$row->use_ppn;?></span>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> Edit</a>
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
		let ini = $(this).closest('tr');
   		$('#form_edit_data [name=toko_list_id]').val(ini.find('.id').html());
   		$('#form_edit_data [name=nama]').val(ini.find('.nama').html());
   		$('#form_edit_data [name=use_ppn]').val(ini.find('.use_ppn').html());
   		$('#form_edit_data [name=alamat]').val(ini.find('.alamat').html());
   		$('#form_edit_data [name=telepon]').val(ini.find('.telepon').html());
   		$('#form_edit_data [name=fax]').val(ini.find('.fax').html());
   		$('#form_edit_data [name=kota]').val(ini.find('.kota').html());
   		$('#form_edit_data [name=kode_pos]').val(ini.find('.kode_pos').html());
   		$('#form_edit_data [name=NPWP]').val(ini.find('.NPWP').html());
   		$('#form_edit_data [name=color_code]').val(ini.find('.color_code').html());

		const ppn_row = $('#form_edit_data [name=use_ppn]').closest('.form-group'); 
		if (ini.find('.use_ppn').html()) {
			ppn_row.addClass('use_ppn')
		}else{
			ppn_row.removeClass('use_ppn')
		}

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

function checkPPN(el){
	const ini = $(`#${el}`);
	const isChecked = ini.find('input').is(':checked');
	if (isChecked) {
		ini.addClass('use_ppn');
	}else{
		ini.removeClass('use_ppn');
	}
}

</script>
