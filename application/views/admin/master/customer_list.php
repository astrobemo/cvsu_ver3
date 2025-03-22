<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<div class="page-content">
	<div class='container'>
		
		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/customer_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3> Tambah </h3>
							<hr/>
							<table style="width:90%">
								<tr>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">Nama<span class="required">
						                    * </span>
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control input1" name="nama"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Alias
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="alias"/>
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
						                    <label class="control-label col-md-4">Kota
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kota"/>
						                    </div>				                    
						                </div>

						                
						                <div class="form-group">
						                    <label class="control-label col-md-4">Email
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="email"/>
						                    </div>				                    
						                </div>
									</td>
									<td>
										
										<div class="form-group">
						                    <label class="control-label col-md-4">NPWP
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control mask_npwp" name="npwp"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">NIK
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control mask_nik" name="nik" maxlength='19'/>
						                    </div>				                    
						                </div>

										<div class="form-group">
						                    <label class="control-label col-md-4">Telepon1
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon1"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Telepon2
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon2"/>
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
						                    <label class="control-label col-md-4">Tempo Kredit (hari)
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="tempo_kredit"/>
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
						<form action="<?=base_url('master/customer_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
							<hr/>
							<table style="width:90%">
								<tr>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">Nama<span class="required">
						                    * </span>
						                    </label>
						                    <div class="col-md-8">
						                    	<input hidden name="customer_id"/>
						                    	<input type="text" class="form-control input1" name="nama"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Alias
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="alias"/>
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
						                    <label class="control-label col-md-4">Kota
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kota"/>
						                    </div>				                    
						                </div>

						                
						                <div class="form-group">
						                    <label class="control-label col-md-4">Email
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="email"/>
						                    </div>				                    
						                </div>
									</td>
									<td>
										
										<div class="form-group">
						                    <label class="control-label col-md-4">NPWP
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control mask_npwp_edit" name="npwp"/>
						                    </div>                 
						                </div>

						                 <div class="form-group">
						                    <label class="control-label col-md-4">NIK
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control mask_nik_edit" name="nik" maxlength='19'/>
						                    </div>				                    
						                </div>


										<div class="form-group">
						                    <label class="control-label col-md-4">Telepon1
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon1"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Telepon2
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon2"/>
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
						                    <label class="control-label col-md-4">Tempo Kredit (hari)
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="tempo_kredit"/>
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
							<select class='btn btn-sm btn-default' name='status_aktif_select' id="status_aktif_select">
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<div id='main-table'>
							<table class="table table-striped table-bordered table-hover" id="general_table">
								<thead>
									<tr>
										<th scope="col" class='status_column'>
											Status Aktif
										</th>
										<th scope="col">
											Nama
										</th>
										<th scope="col">
											Alias
										</th>
										<th scope="col">
											Alamat
										</th>
										<th scope="col">
											Kota
										</th>
										<th scope="col">
											Telepon1
										</th>
										<th scope="col">
											NPWP / NIK
										</th>
										<th scope="col">
											Tempo Kredit
										</th>
										<th scope="col" style="min-width:150px !important">
											Actions
										</th>
									</tr>
								</thead>
								<tbody>
									<?/*foreach ($customer_list as $row) { ?>
										<tr>
											<td>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<span class='nama'><?=$row->nama;?></span>
											</td>
											<td>
												<span class='alamat'><?=$row->alamat;?></span> 
											</td>
											<td>
												<span class='kota'><?=$row->kota;?></span> 
											</td>
											<td>
												<span class='telepon1'><?=$row->telepon1;?></span>
											</td>
											<td>
												<span class='telepon2'><?=$row->telepon2;?></span>
											</td>
											<td>
												<span hidden class='kode_pos'><?=$row->kode_pos;?></span> 
												<span hidden class='email'><?=$row->email;?></span> 
												<span hidden class='npwp'><?=$row->npwp;?></span>
												<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
												<a class="btn-xs btn red btn-remove"><i class="fa fa-times"></i> </a>
											</td>
										</tr>
									<? } */?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>


<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/form-customer.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	FormAddCustomer.init();
	FormEditCustomer.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var other_data = $('td:eq(8)', nRow).text().split('-?-');
            var kode_pos = other_data[0];
            if (kode_pos == null) {kode_pos = '';}
            var email = other_data[1];
            if (email == null) {email = '';}
            var npwp = other_data[2];
            if (npwp == null) {npwp = '';}
            var nik = other_data[5];
            if (nik == null) {nik = '';}
            var status_aktif = $('td:eq(0)', nRow).text();
            var id = other_data[4];

            var btn_edit = "";
            var btn_status = "";
            <?if (is_master_admin()) {?>
            	btn_edit = "<a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
	            if (status_aktif == 1 ) {
	            	btn_status = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
	            	var text_aktif = 'Aktif';
	            }else{
	            	btn_status = "<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>";
	            	var text_aktif = 'Tidak Aktif';
	            };
        	<?};?>

             // 
            var url = "<?=base_url(is_setting_link('master/customer_profile'));?>/"+id;
            var btn_profile = '<a class="btn-xs btn blue" href="'+url+'" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-file-archive-o"></i></a>';
           	var action = "<span class='id' hidden>"+id+"</span>"+
           			"<span class='kode_pos' hidden>"+kode_pos+"</span>"+
           			"<span class='email' hidden>"+email+"</span>"+
           			"<span class='status_aktif' hidden>"+status_aktif+"</span>"+btn_edit+btn_status+btn_profile;
            
            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
            $('td:eq(0)', nRow).addClass('status_column');
            // $('td:eq(6)', nRow).addClass('status_column');

            $('td:eq(1)', nRow).html('<span class="nama">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(2)', nRow).html('<span class="alias">'+$('td:eq(2)', nRow).text()+'</span>');
            $('td:eq(3)', nRow).html('<span class="alamat">'+$('td:eq(3)', nRow).text()+'</span>');
            $('td:eq(4)', nRow).html('<span class="kota">'+$('td:eq(4)', nRow).text()+'</span>');
            $('td:eq(5)', nRow).html('<span class="telepon1">'+$('td:eq(5)', nRow).text()+'</span>');
            $('td:eq(6)', nRow).html(`<span class='npwp' >${npwp}</span>${(parseFloat(npwp) > 0 && parseFloat(nik) > 0 ? '/' : '' )}<span class='nik'>${nik}</span>`);
            $('td:eq(7)', nRow).html('<span class="tempo_kredit">'+$('td:eq(7)', nRow).text()+'</span>');

            $('td:eq(8)', nRow).html(action);
            // $(nRow).addClass('status_aktif_'+status_aktif);
            
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"ordering":true,
		"order": [[ 1, "asc" ]],
		"sAjaxSource": baseurl + "master/data_customer",
		"aoColumnDefs": [{ "bVisible": true, "aTargets": [1] }]

	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

	$('#status_aktif_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});
   
   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=customer_id]').val($(this).closest('tr').find('.id').html());
   		
   		$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
   		$('#form_edit_data [name=alias]').val($(this).closest('tr').find('.alias').html());
   		$('#form_edit_data [name=alamat]').val($(this).closest('tr').find('.alamat').html());
   		$('#form_edit_data [name=kota]').val($(this).closest('tr').find('.kota').html());
   		$('#form_edit_data [name=email]').val($(this).closest('tr').find('.email').html());

   		$('#form_edit_data [name=npwp]').val($(this).closest('tr').find('.npwp').html());
   		$('#form_edit_data [name=nik]').val($(this).closest('tr').find('.nik').html());
   		$('#form_edit_data [name=telepon1]').val($(this).closest('tr').find('.telepon1').html());
   		$('#form_edit_data [name=telepon2]').val($(this).closest('tr').find('.telepon2').html());
   		$('#form_edit_data [name=kode_pos]').val($(this).closest('tr').find('.kode_pos').html());
   		$('#form_edit_data [name=tempo_kredit]').val($(this).closest('tr').find('.tempo_kredit').html());
   		
   	});	

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=customer';
   		var nama = $(this).closest('tr').find('.nama').html();
   		bootbox.confirm("Yakin untuk menonaktifkan customer <b>"+nama+ "</b> ?", function(respond){
   			if (respond) {
		   		window.location.replace(baseurl+"master/ubah_status_aktif?data_sent="+data+'&link=customer_list');

   			};
   		});

   	});
});
</script>
