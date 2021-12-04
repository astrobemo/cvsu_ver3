<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/stok_opname_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> SO Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Stok Opname<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly name='tanggal' value="<?=date('d/m/Y')?>" class='form-control date-picker' autocomplete="off">
			                    </div>
			                </div>
			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save" title='Save & Buka di Tab Ini'>Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
						<div class="tools">
							<a href="" class="fullscreen">
							</a>
						</div>

						<div class="actions hidden-print">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default"><i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">

						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th>
										Tanggal
									</th>
									<th>
										Status
									</th>
									<th>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_opname_list as $row) {?>
									<tr>
										<td><?=is_reverse_date($row->tanggal);?></td>
										<td><?=($row->status_aktif == 1 ? "<span style='color:blue'>aktif</span>" : 'belum aktif')?></td>
										<td>
											<a href="<?=base_url().is_setting_link('inventory/stok_opname_detail').'?id='.$row->id;?>" class='btn btn-xs yellow-gold'><i class='fa fa-search'></i></a>
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>


<script>
jQuery(document).ready(function() {
	

	$("#general_table").DataTable({
		"ordering":false
	});

	$(".btn-save").click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '') {
			$("#form_add_data").submit();
		}else{
			alert("Tanggal harus diisi");
			btn_disabled_load($(this));
		}
	});
	
});
</script>
