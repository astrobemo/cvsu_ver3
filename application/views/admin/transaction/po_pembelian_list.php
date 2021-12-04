<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>


<style type="text/css">


</style>
<div class="page-content">
	<div class='container'>
		
		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block' style='background:#eee; padding:5px'> PO Pembelian Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?=($row->id==1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
			                    </div>
			                </div> 	

			                <div class="form-group">
			                    <label class="control-label col-md-3">Sales Contract
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="sales_contract"/>
			                    </div>
			                </div>   


			                <div class="form-group">
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select> 
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="catatan"/>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active btn-trigger blue btn-save">Save</button>
						<button type="button" class="btn  btn-active default" data-dismiss="modal">Close</button>
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
							<select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="" selected>All</option>
								<option value="1">Aktif</option>
								<!-- <option value="0">Tidak Aktif</option> -->
								<option value="0">Batal</option>

							</select>

							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										No PO
									</th>
									<th scope="col">
										Tanggal PO
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col" >
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	
	$('.barang-id, .warna-id').select2({
        allowClear: true
    });

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var status = $('td:eq(5)', nRow).text().split('??');
            var id = status[0];
            var toko_id = status[1];            
            var gudang_id = status[2];
            var supplier_id = status[3];
            
            var url = "<?=base_url().is_setting_link('transaction/po_pembelian_detail');?>?id="+id;
            // var button_edit = "<a href='"+url+"' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
            var button_view = '<a href="'+url+'" class="btn-xs btn green btn-view" target="_blank"><i class="fa fa-edit"></i> </a>';
           	var button_remove = '';

           	var posisi_id = "<?=is_posisi_id();?>"
           	if (posisi_id != 6) {
           		var status_aktif = $('td:eq(0)', nRow).text();
           		if (status_aktif == 1) {
		           	button_remove = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
           		}else{
		           	button_remove = "<a class='btn-xs btn blue btn-activate'><i class='fa fa-play'></i> </a>";
           		}
           	};

           	
           	var action = "<span class='id' hidden>"+id+"</span><span class='toko_id' hidden>"+toko_id+"</span><span class='gudang_id' hidden>"+gudang_id+"</span><span class='supplier_id' hidden>"+supplier_id+"</span>"+button_remove + button_view;

            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html('<span class="po_number">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(2)', nRow).html('<span class="tanggal">'+$('td:eq(2)', nRow).text()+'</span>');
            $('td:eq(5)', nRow).html(action);
            
            
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": baseurl + "transaction/data_po_pembelian",
		"order":[[2, 'desc']]

	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( '', 0 );

	$('#status_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});

	

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$('#general_table').on('click','.btn-remove', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin <b style='color:red'>MEMBATALKAN</b> PO Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/po_pembelian_list_batal?id='+id);
			};
		});
	}) ;  

	$('#general_table').on('click','.btn-activate', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin <b style='color:blue'>MENGAKTIVASI</b> kembali PO Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/po_pembelian_list_undo_batal?id='+id);


			};
		});
	}) ;  


	$('.btn-save').click(function () {
        if ($('#form_add_data [name=tanggal]').val() != '')
        {
            $('#form_add_data').submit();
            btn_disabled_load(ini);
        }else{
        	alert("Tanggal harus diisi");
        }
    });




});
</script>
