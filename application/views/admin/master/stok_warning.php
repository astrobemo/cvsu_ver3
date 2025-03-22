<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style>
	.label-satuan{
		background:#eee;
		padding:7px;
		text-align:center;
		cursor:pointer;
	}

	.selected{
		background:lightpink;
	}

	.ex-warning{
		background:lightpink;
		padding:10px;
		display:block;
		min-width:80px;
		text-align:center;
	}

	.ex-alert{
		display:block;
		background:lightyellow;
		padding:10px;
		min-width:80px;
		text-align:center;
	}

	#warning-new, #warning-edit{
		display:none;
	}


</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form id="form-add-barang" class="form-horizontal"  action="<?=base_url()?>master/qty_warning_insert" method="POST">
							<h3 class='block'> Stok Warning Baru</h3>
							<div class="form-group">
			                    <label class="control-label col-md-4">TOKO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" id="toko_id_new" class="form-control">
										<?foreach ($this->toko_list_aktif as $row) {?>
											<option value="<?=$row->id?>"><?=$row->nama;?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<select name="sku_id" id="barang_id_new" class="form-control">
										<option value="">Pilih</option>
										<?foreach ($this->barang_sku_aktif as $row) {?>
											<option value="<?=$row->id?>" data-satuan="<?=$row->nama_satuan?>" data-packaging="<?=$row->nama_packaging?>" ><?=$row->nama_barang;?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Satuan<span class="required">
			                    * </span>
			                    </label>
								<div class="col-xs-4">					                
									<div class="input-group">
										<input name='nama_satuan' id='nama_satuan_new' hidden>
										<span class="input-group-btn">
											<label class="col-xs-6 label-satuan" id="btn-satuan" style="border-right:1px solid #ccc" >...</label>
											<label class="col-xs-6 label-satuan" id="btn-packaging" >...</label>
										</span>
									</div>
			                    </div>
			                </div>

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Alert
			                    </label>
			                    <div class="col-xs-4">					                
									<input type="text" name="qty_alert" placeholder="qty alert" id="qty_alert_new" class='form-control text-center'>
			                    </div>
			                    <div class="col-xs-3">			
									<span class="example ex-alert" id='ex-alert-new'></span>
								</div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Warning
			                    </label>
			                    <div class="col-xs-4">					                
									<input type="text" name="qty_warning" placeholder="qty warning" id="qty_warning_new" class='form-control text-center'>
			                    </div>
			                    <div class="col-xs-3">		
									<span class="example ex-warning" id='ex-warning-new'></span>
								</div>
			                </div>

							<div class='row' id="warning-new">
								<div class="col-xs-2"></div>
			                    <div class="col-xs-8">					                
									<div class="note-danger note" id="warning-note-new"></div>
			                    </div>
			                </div>
							
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-active blue" id="btnWarningSave" onClick="submitNewWarning()">Save</button>
						<button type="button" class="btn default" data-dismiss="modal" id="btnSplitClose">Close</button>
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
						<form id="form-edit-barang" class="form-horizontal"  action="<?=base_url()?>master/qty_warning_update" method="POST">
							<h3 class='block'> Stok Warning Edit</h3>
							<div class="form-group">
			                    <label class="control-label col-md-4">TOKO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" id="toko_id_edit" class="form-control">
										<?foreach ($this->toko_list_aktif as $row) {?>
											<option value="<?=$row->id?>"><?=$row->nama;?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<select name="sku_id" id="barang_id_edit" class="form-control">
										<option value="">Pilih</option>
										<?foreach ($this->barang_sku_aktif as $row) {?>
											<option value="<?=$row->id?>" data-satuan="<?=$row->nama_satuan?>" data-packaging="<?=$row->nama_packaging?>" ><?=$row->nama_barang;?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Satuan<span class="required">
			                    * </span>
			                    </label>
								<div class="col-xs-4">					                
									<div class="input-group">
										<input name='nama_satuan' id='nama_satuan_edit' hidden>
										<span class="input-group-btn">
											<label class="col-xs-6 label-satuan" id="btn-satuan-edit" style="border-right:1px solid #ccc" >...</label>
											<label class="col-xs-6 label-satuan" id="btn-packaging-edit" >...</label>
										</span>
									</div>
			                    </div>
			                </div>

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Alert
			                    </label>
			                    <div class="col-xs-4">					                
									<input type="text" name="qty_alert" placeholder="qty alert" id="qty_alert_edit" class='form-control text-center'>
			                    </div>
			                    <div class="col-xs-3">
									<span class="example ex-alert" id='ex-alert-edit'></span>
								</div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Warning
			                    </label>
			                    <div class="col-xs-4">					                
									<input type="text" name="qty_warning" placeholder="qty warning" id="qty_warning_edit" class='form-control text-center'>
			                    </div>
			                    <div class="col-xs-3">		
									<span class="example ex-warning" id='ex-warning-edit'></span>
								</div>
			                </div>

							<div class='row' id="warning-edit">
								<div class="col-xs-2"></div>
			                    <div class="col-xs-8">					                
									<div class="note-danger note" id="warning-note-edit"></div>
			                    </div>
			                </div>
							<input name='id' id='id_edit' hidden>


						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-active blue" id="btnWarningEditSave" onClick="submitEditWarning()">Save</button>
						<button type="button" class="btn default" data-dismiss="modal" >Close</button>
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
									<th scope="col" >
										Nama Barang
									</th>
									<th scope="col" hidden >
										QTY Alert
									</th>
									<th scope="col"  >
										QTY Warning
									</th>
									<th scope="col"  >
										Satuan
									</th>
									<th scope="col"  >
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_warning_list as $row) { ?>
									<tr>
										<td>
											<?=$row->nama_barang?> 
										</td>
										<td hidden>
											<span class="qty_alert"><?=(float)$row->qty_alert?></span>
										</td>
										<td>
											<span class="qty_warning"><?=(float)$row->qty_warning?></span>
										</td>
										<td>
											<span class="nama_satuan"><?=$row->nama_satuan?></span>
										</td>
										<td>
											<span class="id" hidden><?=$row->id?></span>					
											<span class="toko_id" hidden><?=$row->toko_id?></span>					
											<span class="sku_id" hidden><?=$row->sku_id?></span>
											<span class="satuan_id" hidden><?=$row->satuan_id?></span>
											<a href="#portlet-config-edit" data-toggle="modal" class="btn btn-xs green btn-edit"><i class="fa fa-edit"></i></a>
											<button class="btn btn-xs red btn-remove" onclick="removeWarning('<?=$row->id?>','<?=$row->nama_barang;?>')"><i class="fa fa-times"></i></a>
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

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>

jQuery(document).ready(function() {

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	$('#barang_id_new, #barang_id_edit').select2();

	$("#barang_id_new").change(function(){
		if ($(this).val() != '') {
			const nama_satuan = $(`#barang_id_new option:selected`).attr('data-satuan');
			const nama_packaging = $(`#barang_id_new option:selected`).attr('data-packaging');
			
			$("#btn-satuan").text(nama_satuan);
			$("#btn-packaging").text(nama_packaging);
		}else{
			$('#nama_satuan').val('');
			$(".label-satuan").text('...');
		}
		$(".label-satuan").removeClass('selected');
		
	});

	$(".label-satuan").click(function(){
		$(".label-satuan").removeClass('selected');
		const isEdit = $(this).attr("id").includes("edit");	


		if($(this).text() !== '...'){
			$(this).addClass('selected');
			if (isEdit) {
				$("#nama_satuan_edit").val($(this).text());
				return;
			}
			$("#nama_satuan_new").val($(this).text())
		};
	})

	$(document).on("click",".btn-edit", function(){
		const ini = $(this).closest('tr');
		const id = ini.find(".id").text();
		const toko_id = ini.find(".toko_id").text();
		const sku_id = ini.find(".sku_id").text();
		const nama_satuan = ini.find(".nama_satuan").text();
		const qty_alert = ini.find(".qty_alert").text();
		const qty_warning = ini.find(".qty_warning").text();

		$('#id_edit').val(id);
		$('#toko_id_edit').val(toko_id);
		$('#nama_satuan_edit').val(nama_satuan);
		$('#barang_id_edit').val(sku_id).change();
		$('#qty_alert_edit').val(qty_alert).change();
		$('#qty_warning_edit').val(qty_warning).change();
		

	});

	$("#barang_id_edit").change(function(){
			$(".label-satuan").removeClass('selected');
		if ($(this).val() != '') {
			const nama_satuan = $(`#barang_id_edit option:selected`).attr('data-satuan');
			const nama_packaging = $(`#barang_id_edit option:selected`).attr('data-packaging');
			const nama_satuan_selected = $('#nama_satuan_edit').val();
			
			$("#btn-satuan-edit").text(nama_satuan);
			$("#btn-packaging-edit").text(nama_packaging);
			if (nama_satuan_selected == nama_satuan) {
				$("#btn-satuan-edit").addClass('selected');
			}else if(nama_satuan_selected == nama_packaging){
				$("#btn-packaging-edit").addClass('selected');
			}
		}else{
			$('#nama_satuan_edit').val('');
			$(".label-satuan").text('...');
		}
		
	});

	//==========================================================
	$('#qty_alert_edit').change(function(){
		const sat = $('#nama_satuan_edit').val();
		const val = $(this).val();
		const warn = val > 0 ? `Stok &le; ${val} ${sat}` : "<span class='text-mute'>No alert</span>";
		$("#ex-alert-edit").html(warn);
	});

	$('#qty_alert_new').change(function(){
		const sat = $('#nama_satuan_new').val();
		const val = $(this).val();const warn = val > 0 ? `Stok &le; ${val} ${sat}` : "<span class='text-mute'>No alert</span>";
		$("#ex-alert-new").html(warn);
	});

	$('#qty_warning_edit').change(function(){
		const sat = $('#nama_satuan_edit').val();
		const val = $(this).val();
		const warn = val > 0 ? `Stok &le; ${val} ${sat}` : "<span class='text-mute'>No warning</span>";
		$("#ex-warning-edit").html(warn);
	});

	$('#qty_warning_new').change(function(){
		const sat = $('#nama_satuan_new').val();
		const val = $(this).val();
		const warn = val > 0 ? `Stok &le; ${val} ${sat}` : "<span class='text-mute'>No warning</span>";
		$("#ex-warning-new").html(warn);
	});

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

});

function submitNewWarning(){
	const barang_id = $('#barang_id_new').val(); 	
	const nama_satuan = $('#nama_satuan_new').val();
	const qty_alert = $('#qty_alert_new').val();
	const qty_warning = $('#qty_warning_new').val();
	let valid = true;
	let barang_notes = "Barang belum diisi<br/>";
	let satuan_notes = "Satuan belum dipilih<br/>";
	let qty_notes = "Stok warning harus diisi";
	let warning_notes = '';
	if (barang_id == '') {
		valid=false;
		warning_notes += barang_notes;
	}

	if (nama_satuan == '') {
		valid=false;
		warning_notes += satuan_notes;
	}

	if (qty_alert == '' && qty_warning == '' ) {
		valid=false;
		warning_notes += qty_notes;
	}

	if (valid) {
		$("#btnWarningSave").prop('disabled',true);
		$("#form-add-barang").submit();
	}else{
		$("#warning-new").show();
		$('#warning-note-new').html(warning_notes);
	}
}

function submitEditWarning(){
	const barang_id = $('#barang_id_edit').val(); 	
	const nama_satuan = $('#nama_satuan_edit').val();

	const qty_alert = $('#qty_alert_edit').val();
	const qty_warning = $('#qty_warning_edit').val();
	let valid = true;
	let barang_notes = "Barang belum diisi<br/>";
	let satuan_notes = "Satuan belum dipilih<br/>";
	let qty_notes = "Stok warning / alert harus diisi salah satu";
	let warning_notes = '';
	if (barang_id == '') {
		valid=false;
		warning_notes += barang_notes;
	}

	if (nama_satuan == '') {
		valid=false;
		warning_notes += satuan_notes;
	}

	if (qty_alert == '' && qty_warning == '' ) {
		valid=false;
		warning_notes += qty_notes;
	}

	if (valid) {
		$("#btnWarningEditSave").prop('disabled',true);
		$("#form-edit-barang").submit();
	}else{
		$('#warning-note-edit').html(warning_notes);
		$("#warning-edit").show();

	}
}

function removeWarning(id, nama_barang){
	bootbox.confirm(`Apakah anda yakin akan menghapus stok warning ${nama_barang}?`,  function (result) {
		if (result) {
			remove(id);
		}
	});
}

async function remove(id){
	const url = "<?=base_url()?>master/qty_warning_delete";
	const data = {id:id};
	const res = await $.post(url,data);

	if (JSON.parse(res) == 'OK') {
		window.location.reload();
	}
}
</script>
