<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>



<style type="text/css">


</style>
<div class="page-content">
	<div class='container'>

				
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Pembelian Baru</h3>
							
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
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select style='font-weight:bold' class='form-control gudang-input' name="gudang_id">
			                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?=($row->id==2 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
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
			                    <label class="control-label col-md-3">No Faktur
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Surat Jalan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal_sj"/>
			                    </div>
			                </div> 	

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Surat Jalan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_surat_jalan"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">PO Pembelian
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="po_pembelian_batch_id"/>
			                		<!--<img src="<?=base_url()?>image/loading.gif">-->
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="keterangan"/>
			                		<!--<img src="<?=base_url()?>image/loading.gif">-->
			                    </div>
			                </div>

			                <!-- <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="ockh"/>
			                    </div>
			                </div> -->


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
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
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
								<option value="1" selected>Aktif</option>
								<option value="2">Belum Release</option>
								<?if (is_posisi_id() < 4) {?>
									<option value="-1">Batal</option>
								<?}?>

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
										Toko
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Surat Jalan
									</th>
									<th scope="col">
										Tanggal Pembelian
									</th>
									<!-- <th scope="col">
										Satuan
									</th> -->
									<!-- <th scope="col">
										Yard/KG
									</th>
									<th scope="col">
										Jml Roll
									</th>
									<th scope="col">
										Nama Barang
									</th> -->
									<th scope="col">
										Gudang
									</th>
									<!-- <th scope="col">
										Harga
									</th> -->
									<th scope="col">
										Total
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
<script src="<?php echo base_url('assets_noondev/js/form-pembelian.js'); ?>" type="text/javascript"></script>



<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/ui-extended-modals.js'); ?>"></script>

<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>


<script>
jQuery(document).ready(function() {
	
	FormNewPembelian.init();
	// ModalsPembelianEdit.init();
	$('.barang-id, .warna-id, #po_list').select2({
        allowClear: true
    });

	// oTable = $('#general_table').dataTable();
	// oTable.state.clear();
	// oTable.destroy();

	var oTable = $("#general_table").dataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var status_data = $('td:eq(9)', nRow).text().split('??');
            var status_aktif = $('td:eq(0)', nRow).text();
            var id = status_data[0];
            var toko_id = status_data[1];            
            var gudang_id = status_data[2];
            var supplier_id = status_data[3];
            var tanggal = date_formatter($('td:eq(4)', nRow).html());
            var total_data = $('td:eq(6)', nRow).text().split('??');
           	var total = 0;

            if ($('td:eq(6)', nRow).text() != '') {
	            $.each(total_data, function(i,v){
	            	total += parseInt(v);
	            });
            }else{
            	total = 0;
            }
            
        	var total = change_number_comma(total.toString().replace('.00',''));
            var url = "<?=base_url().rtrim(base64_encode('transaction/pembelian_list_detail'),'=');?>/"+id;
            // var url_print = "<?=base_url();?>transaction/pembelian_print?pembelian_id="+id;
            //var button_view = '<a href="'+url_print+'" class="btn-xs btn blue btn-print" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-print"></i> </a>';
            let button_view = '';
            var button_edit = "<a href='"+url+"' target='_blank' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
           	var button_remove = '';


           	var posisi_id = "<?=is_posisi_id();?>"
           	if (posisi_id != 6) {
           		var status_aktif = $('td:eq(0)', nRow).text();
           		if (status_aktif != -1) {
		           	button_remove = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
           		}else{
		           	button_remove = "<a class='btn-xs btn blue btn-activate'><i class='fa fa-play'></i> </a>";
           		}
           	};

           	var status_ket = $('td:eq(8)', nRow).text();
           	let status = '';
           	if (status_aktif == 2) {
           		status = "<span style='color:gray' class='release-stat'>belum release</span>"
           	}else{
	           	if (status_ket < 0) {
	           		status = "<span style='color:red' class='release-stat'>belum lunas</span>";
	           	}else{
	           		status = "<span style='color:blue'>lunas</span>";
	           	}
           	}
           	if (status_aktif == 0) {
           		status = "BATAL";
           	};

           	let button_release = '';
           	<?//if (is_posisi_id() <= 3) {?>
           		if (status_aktif == 2) {
           			button_release = "<a data-toggle='tooltip' data-placement='left' title='Double click untuk release' class='btn-xs btn purple btn-release'><i class='fa fa-check'></i> </a>";
           		};
       		<?//};?>
           	
           	var action = "<span class='id' hidden='hidden'>"+id+"</span><span class='toko_id' hidden='hidden'>"+toko_id+"</span><span class='gudang_id' hidden='hidden'>"+gudang_id+"</span><span class='supplier_id' hidden='hidden'>"+supplier_id+"</span>"+button_release+button_edit+button_remove + button_view;

            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html('<span class="toko_id">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(2)', nRow).html('<span class="no_faktur">'+$('td:eq(2)', nRow).text()+'</span>');
            $('td:eq(4)', nRow).html(tanggal);
            
            $('td:eq(6)', nRow).html('<span class="total">'+total+'</span>');
            if (total == 0 || total == '') {
	            $('td:eq(6)', nRow).addClass('caution');
            };
            
            $('td:eq(9)', nRow).html(action);
            $('td:eq(8)', nRow).html(status);


            
        },
        "bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": baseurl + "transaction/data_pembelian_slim?status_aktif = 1",
		"order":[[4, 'desc']]

	});

	$('#status_select').change(function(){
		// alert($(this).val());
		var status_aktif = $(this).val();
		oTable.fnReloadAjax(baseurl + "transaction/data_pembelian_slim?status_aktif="+status_aktif);
	});

	

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$('#general_table').on('click','.btn-remove', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin <b style='color:red'>MEMBATALKAN</b> Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/pembelian_list_batal?id='+id);


			};
		});
	}) ;  

	$('#general_table').on('click','.btn-activate', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin <b style='color:blue'>MENGAKTIVASI</b> kembali Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/pembelian_list_undo_batal?id='+id);


			};
		});
	}) ;  

	$(".supplier-input").change(function(){
		get_po_list($(this));
	});

	$(".btn-form-add").click(function(){
		get_po_list($(".supplier-input"));
	});

	$("#general_table").on('dblclick','.btn-release', function(){
		let ini = $(this).closest('tr');
		let data = {};
		data['id'] = ini.find('.id').html();
		let url = 'transaction/pembelian_release';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				notific8("lime", 'released');
				ini.find('.release-stat').css('color','red');
				ini.find('.release-stat').html('belum lunas');
				ini.find('.btn-release').hide();
				ini.find('td:eq(0)').html(1);
			}else{
				notific8("ruby", 'error, mohon refresh halaman');
			}
		});
	});

});

function get_po_list(ini){
	let data = {};
	data['supplier_id'] = ini.val();
	let url = 'transaction/get_po_pembelian_by_supplier';
	$('#po_list').empty().trigger('change');
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		// $('#po_list').select2("val","");
		var newOpt = new Option("Non PO", "", true, false);
		$("#po_list").append(newOpt).trigger('change');

		$.each(JSON.parse(data_respond), function(i,v){
			console.log(data_respond);
			var newOpt = new Option(v.po_number, v.id, false, false);
			$("#po_list").append(newOpt).trigger('change');
			// $('#po_list').select2('data',{value:v.id, text:v.tanggal});
			// $("#po_list").append($('<option>',{
			// 	value: v.id,
			// 	text: v.tanggal+'/'+v.po_number
			// }));
		})
	});
}
</script>
