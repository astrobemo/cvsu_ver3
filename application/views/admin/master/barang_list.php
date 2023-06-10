<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/barang_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block' style="background:lightblue; padding-left:10px;"> Barang Baru </h3>
							<div class="form-group" hidden>
			                    <label class="control-label col-md-3">Toko<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="toko_id" class='form-control' id="toko-id" onchange="tokoChange('1')">
										<?foreach ($this->toko_list_aktif as $row) {?>
											<option value="<?=$row->id;?>"><?=$row->nama;?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" autocomplete='off'  class="form-control input1 check-double" name="nama"/>
			                    	<span class='check-double-info'></span>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" autocomplete='off'  class="form-control check-double" name="nama_jual"/>
			                    	<span class='check-double-info'></span>
			                    </div>				                    
			                </div>
			                <hr/>

							<div class="form-group subitem-field" style="background:lightgreen">
			                    <label class="control-label col-md-3">Punya Subitem<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<label class='checkbox-inline'>
										<input type="checkbox" checked class="form-control" value='1' onchange="subitemChange('1')" id='subitem-check' name="subitem_status" value="1" />yes</label>
								</div>				                    
			                </div>

							<div class="form-group eceran-field">
			                    <label class="control-label col-md-3">Stok Eceran di Mix<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<label class='checkbox-inline'>
										<input type="checkbox" class="form-control" value='1' onchange="eceranMixChange('1')" id='eceran-mix' name="eceran_mix_status" value="1" />yes</label>
								</div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan(Kecil)<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class="form-control " name="satuan_id">
			                    		<?foreach ($satuan_list as $row) { 
			                    			if ($row->id == 1) {
			                    				$initial_ukuran = $row->nama;
			                    			}
			                    			?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<small>Umumnya menunjukkan ukuran panjang cth: yar, meter, dan dapat bernilai <b>koma</b></small>
			                    </div>
			                </div>

			                <div class="form-group" id='satuan-besar-add'>
			                    <label class="control-label col-md-3">Satuan(Besar)<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class="form-control " name="packaging_id">
			                    		<?foreach ($satuan_list as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<small>Umumnya menunjukkan packaging dan berupa angka <b>bulat</b> cth: roll, pcs, pak</small>
			                    </div>				                    
			                </div>

			                <hr/>

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Harga Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control amount_number_comma " value='0' name="harga_beli"/>
			                    </div>				                    
			                </div>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control amount_number_comma" name="harga_jual"/>
			                    </div>				                    
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Harga Ecer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control amount_number_comma" name="harga_ecer"/>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Pengali Harga Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class="radio-list">
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_beli" value="1" checked> Satuan Kecil </label>
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_beli" value="2"> Satuan Besar </label>
									</div>
									Harga Beli x <b><span class='pengali-harga-beli-keterangan'><?=$initial_ukuran?></span></b>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Pengali Harga Jual <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class="radio-list">
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_jual" value="1" checked> Satuan Kecil </label>
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_jual" value="2"> Satuan Besar </label>
									</div>
									Harga Jual x <b><span class='pengali-harga-keterangan'><?=$initial_ukuran?></span></b>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Status<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control' name='status_aktif'>
			                    		<option value='0'>Tidak Aktif</option>
			                    		<option value='1' selected>Aktif</option>
			                    	</select>
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
						<form action="<?=base_url('master/barang_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block' style="background:lightpink; padding-left:10px;"> Edit Barang </h3>
							<div class="form-group"  hidden>
			                    <label class="control-label col-md-3">Toko<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="toko_id" class='form-control' id="toko-id-edit" onchange="tokoChange('2')">
										<?foreach ($this->toko_list_aktif as $row) {?>
											<option value="<?=$row->id;?>"><?=$row->nama;?></option>
										<?}?>
									</select>
			                    </div>
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input hidden name="barang_id"/>
			                    	<input type="text" class="form-control input1" name="nama"/>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control" name="nama_jual"/>
			                    </div>				                    
			                </div>							
			                <hr/>

							<div class="form-group subitem-field">
			                    <label class="control-label col-md-3">Punya Subitem<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<label class='checkbox-inline'>
										<input type="checkbox" class="form-control" value='1' onchange="subitemChange('2')" id='subitem-check-edit' name="subitem_status" />yes</label>
								</div>				                    
			                </div>

							<div class="form-group eceran-field">
			                    <label class="control-label col-md-3">Stok Eceran di Mix<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<label class='checkbox-inline'>
										<input type="checkbox" class="form-control" value='1' onchange="eceranMixChange('2')" id='eceran-mix-edit' name="eceran_mix_status" />yes</label>
								</div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan(Kecil)<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class="form-control " name="satuan_id">
			                    		<?foreach ($satuan_list as $row) { 
			                    			if ($row->id == 1) {
			                    				$initial_ukuran = $row->nama;
			                    			}
			                    			?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<small>Umumnya menunjukkan ukuran panjang cth: yar, meter</small>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan(Besar)<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class="form-control " name="packaging_id">
			                    		<?foreach ($satuan_list as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<small>Umumnya menunjukkan packaging dan berupa angka bulat() cth: roll, pcs, pak</small>
			                    </div>				                    
			                </div>

			                <hr/>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control amount_number_comma " name="harga_beli"/>
			                    </div>				                    
			                </div>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control amount_number_comma" name="harga_jual"/>
			                    </div>				                    
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Harga Ecer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control amount_number_comma" name="harga_ecer"/>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Beli Per<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class="radio-list">
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_beli" value="1" checked> Satuan Kecil </label>
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_beli" value="2"> Satuan Besar </label>
									</div>
									Harga Beli x <b><span class='pengali-harga-beli-keterangan'><?=$initial_ukuran?></span></b>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual Per<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class="radio-list">
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_jual" value="1" checked> Satuan Kecil </label>
										<label class="radio-inline">
										<input type="radio" name="pengali_harga_jual" value="2"> Satuan Besar </label>
									</div>
									Harga Jual x <b><span class='pengali-harga-keterangan'><?=$initial_ukuran?></span></b>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Status<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control' name='status_aktif'>
			                    		<option value='0'>Tidak Aktif</option>
			                    		<option value='1' selected>Aktif</option>
			                    	</select>
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
					<div class="tabbable tabs-left" style='margin-bottom:5px'>
						<ul class="nav nav-tabs" style='padding:0px; margin:10px 0px;'>
							<li class='active'>
								<a>
									DAFTAR BARANG
								</a> 
							</li>
							<li>
								<a href="<?=base_url().is_setting_link('master/barang_eceran_mix_list')?>">
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
										Status Aktif
									</th>
									<th scope="col">
										Nama
									</th>
									<th scope="col">
										Nama Jual
									</th>
									<th scope="col">
										Sat. Kecil
									</th>
									<th scope="col">
										Sat. Besar
									</th>
									<th scope="col">
										Harga Jual
									</th>
									<th scope="col">
										Harga Beli
									</th>
									<th scope="col" class='status_column'>
										Keterangan
									</th>
									<th scope="col">
										Toko
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?/*foreach ($barang_list as $row) { ?>
									<tr>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='nama'><?=$row->nama;?></span> 
										</td>
										<td>
											<span class='nama_jual'><?=$row->nama_jual;?></span> 
										</td>
										<td>
											<span class='satuan' hidden><?=$row->satuan_id;?></span>
											<?=$row->nama_satuan;?> 
										</td>
										<td>
											<span class='harga_jual'><?=number_format($row->harga_jual,'0','.','.');?></span> 
										</td>
										<td>
											<span class='harga_beli'><?=number_format($row->harga_beli,'0','.','.');?></span> 
										</td>
										<td>
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

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {
	//  Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	oTable = $('#general_table').DataTable();
	oTable.state.clear();
	oTable.destroy();

	// TableAdvanced.init();
	

	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            let data_get = $('td:eq(9)', nRow).text().split('??');
            let data_harga = $('td:eq(7)', nRow).text().split('??');
            let id = "<span class='id' hidden>"+data_get[0]+"</span>";
            let satuan_id = data_get[1];
            let packaging_id = data_get[2];
            let pengali_harga = data_get[3];
            let pengali_harga_beli = data_get[4];
            let toko_id = data_get[5];
            // let color_code = data_get[6];
            let harga_ecer = data_get[6];
            let subitem_status = data_get[7];
            let eceran_mix_status = data_get[8];
            let status_aktif = $('td:eq(0)', nRow).text();
            let btn_status = '';
            let p_jual = data_harga[0];
            let p_beli = data_harga[1];
            // alert(status_aktif);
            if (status_aktif == 1 ) {
            	btn_status = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
			}else{
            	btn_status = "<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>";
            };
            let pengali = "<span class='pengali_harga_jual' hidden>"+pengali_harga+"</span>"
            let pengali_beli = "<span class='pengali_harga_beli' hidden>"+pengali_harga_beli+"</span>"
            let satuan = "<span class='satuan_id' hidden>"+satuan_id+"</span>"
            let toko = "<span class='toko_id' hidden>"+toko_id+"</span>"
            let packaging = "<span class='packaging_id' hidden>"+packaging_id+"</span>";
            let status = "<span class='status_aktif' hidden>"+status_aktif+"</span>";
            let subitemStatus = "<span class='subitem-status' hidden>"+subitem_status+"</span>";
            let eceranStatus = "<span class='eceran-mix-status' hidden>"+eceran_mix_status+"</span>";
			let ecer = '';
			if(harga_ecer !=0){
            	ecer = `<span class='harga_ecer' style='background:lightblue'>${change_number_comma(harga_ecer)}</span>`;
			}
           	let action = id+status+pengali+pengali_beli+
           		satuan+packaging+toko+subitemStatus+eceranStatus+
           		"<a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>"+
           		btn_status;
            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html('<span class="nama">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(2)', nRow).html('<span class="nama_jual">'+$('td:eq(2)', nRow).text()+'</span>');
            $('td:eq(5)', nRow).html(`<span class="harga_jual"> ${change_number_comma($('td:eq(5)', nRow).text())} </span> 
						/<b>${p_jual}</b>
						<br/> ${ecer}`);
            $('td:eq(6)', nRow).html('<span class="harga_beli">'+change_number_comma($('td:eq(6)', nRow).text())+'</span> /<b>'+p_beli+'</b>');
            $('td:eq(7)', nRow).addClass('status_column');
            $('td:eq(7)', nRow).html('');
            $('td:eq(9)', nRow).html(action);
            // $(nRow).addClass('status_aktif_'+status_aktif);
            
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"paging": true,
		"sAjaxSource": baseurl + "master/data_barang"
	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

	$('#status_aktif_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});
	
   	$('#general_table').on('click', '.btn-edit', function(){
		var form = '#form_edit_data';
		
		let ini = $(this).closest('tr');
   		$(`${form} [name=barang_id]`).val(ini.find('.id').html());
   		$(`${form} [name=satuan_id]`).val(ini.find('.satuan_id').html());
   		$(`${form} [name=packaging_id]`).val(ini.find('.packaging_id').html());
		$(`${form} [name=satuan_id]`).change();

   		$(`${form} [name=nama]`).val(ini.find('.nama').html());
   		$(`${form} [name=nama_jual]`).val(ini.find('.nama_jual').html());
   		$(`${form} [name=harga_beli]`).val(ini.find('.harga_beli').html());
   		$(`${form} [name=harga_jual]`).val(ini.find('.harga_jual').html());
   		$(`${form} [name=harga_ecer]`).val(ini.find('.harga_ecer').html());
   		$(`${form} [name=toko_id]`).val(ini.find('.toko_id').html());
   		$(`${form} [name=pengali_harga_jual]`).prop('checked', false);

   		$.uniform.update($("#form_edit_data [name=pengali_harga]"));
   		var pengali_harga = ini.find('.pengali_harga_jual').html();
   		var pengali_harga_beli = ini.find('.pengali_harga_beli').html();
		const subitemStatus = ini.find(".subitem-status").html();
		const eceranStatus = ini.find(".eceran-mix-status").html();
		tokoChange(2);

   		$("#form_edit_data [name=pengali_harga_jual][value='"+pengali_harga+"']").prop('checked', true);
   		$("#form_edit_data [name=pengali_harga_beli][value='"+pengali_harga_beli+"']").prop('checked', true);
   		
   		$("#subitem-check-edit").prop('checked', parseInt(subitemStatus));
   		$("#eceran-mix-edit").prop('checked', parseInt(eceranStatus));

		console.log($("#eceran-mix-edit").is(':checked'), parseInt(eceranStatus) == false);

		subitemChange('2');
		eceranMixChange('2');

   		$('#form_edit_data [name=satuan_id]').change();
   		$('#form_edit_data [name=packaging_id]').change();


   		$.uniform.update($("#form_edit_data [name=pengali_harga_jual], #form_edit_data [name=pengali_harga_beli], #subitem-check-edit, #eceran-mix-edit"));
   		
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=barang';
   		window.location.replace(baseurl+"master/ubah_status_aktif?data_sent="+data+'&link=barang_list');
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

   	$("[name=satuan_id]").change(function(){
   		var form = '#'+$(this).closest('form').attr('id');
   		var pengali_harga = $(form+" input[name=pengali_harga_jual]:checked").val();
   		var pengali_harga_beli = $(form+" input[name=pengali_harga_beli]:checked").val();
   		var nama_satuan = $(this).find(":selected").text();
   		if (pengali_harga == 1) {
	   		$(form+" .pengali-harga-keterangan").html(nama_satuan);
   		};
   		if (pengali_harga_beli == 1) {
	   		$(form+" .pengali-harga-beli-keterangan").html(nama_satuan);
   		};
   	});

   	$("[name=packaging_id]").change(function(){
   		var form = '#'+$(this).closest('form').attr('id');
   		var pengali_harga = $(form+" input[name=pengali_harga]:checked").val();
   		var pengali_harga_beli = $(form+" input[name=pengali_harga_beli]:checked").val();
   		var nama_packaging = $(this).find(":selected").text();
   		if (pengali_harga == 2) {
	   		$(form+" .pengali-harga-keterangan").html(nama_packaging);
   		};
   		if (pengali_harga_beli == 2) {
	   		$(form+" .pengali-harga-beli-keterangan").html(nama_packaging);
   		};
   	});

   	$("[name=pengali_harga_jual]").change(function(){
   		var form = '#'+$(this).closest('form').attr('id');
   		var pengali_harga = $(form+" input[name=pengali_harga_jual]:checked").val();
   		var nama_satuan = $(form+" [name=satuan_id]").find(":selected").text();
   		var nama_packaging = $(form+" [name=packaging_id]").find(":selected").text();
   		var keterangan = ((pengali_harga == 1) ? nama_satuan : nama_packaging);
   		$(form+" .pengali-harga-keterangan").html(keterangan);
   		// alert(nama_satuan);
   	});;

   	$("[name=pengali_harga_beli]").change(function(){
   		var form = '#'+$(this).closest('form').attr('id');
   		var pengali_harga = $(form+" input[name=pengali_harga_beli]:checked").val();
   		var nama_satuan = $(form+" [name=satuan_id]").find(":selected").text();
   		var nama_packaging = $(form+" [name=packaging_id]").find(":selected").text();
   		var keterangan = ((pengali_harga == 1) ? nama_satuan : nama_packaging);
   		$(form+" .pengali-harga-beli-keterangan").html(keterangan);
   		// alert(nama_satuan);
   	});

   	$(document).on('input','.check-double', function(){
   		var result = '';
   		if ($(this).val() != '') {
   			result = check_double_data($(this), 'barang');
   		}else{
            $(this).closest('div').find(".check-double-info").html('');
   		}
   	});
});

function tokoChange(tipe){
	if (tipe == 1) {
		let toko_id = $('#toko-id').val();
		// $("#portlet-config .modal-body").css('background-color',colorToko[toko_id]);
	}else{
		let toko_id = $('#toko-id-edit').val();
		// $("#portlet-config-edit .modal-body").css('background-color',colorToko[toko_id]);
	}
}

function subitemChange(tipe){
	let form;
	let check;
	let checkDiv;
	if (tipe==1) {
		form = document.querySelector("#form_add_data")
		checkDiv = form.querySelector(".subitem-field");
		check = document.querySelector("#subitem-check");
	}else{
		form = document.querySelector("#form_edit_data")
		checkDiv = form.querySelector(".subitem-field");
		check = document.querySelector("#subitem-check-edit");
	}

	if (check.checked) {
		checkDiv.style.backgroundColor = 'lightgreen'
	}else{
		checkDiv.style.backgroundColor = 'transparent'
	}
	
}

function eceranMixChange(tipe){
	let form;
	let check;
	let checkDiv;
	if (tipe==1) {
		form = document.querySelector("#form_add_data")
		checkDiv = form.querySelector(".eceran-field");
		check = document.querySelector("#eceran-mix");
	}else{
		form = document.querySelector("#form_edit_data")
		checkDiv = form.querySelector(".eceran-field");
		check = document.querySelector("#eceran-mix-edit");
	}

	if (check.checked) {
		checkDiv.style.backgroundColor = 'yellow'
	}else{
		checkDiv.style.backgroundColor = 'transparent'
	}
	
}

</script>
