<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


<div class="page-content">
	<div class='container'>

		<?
			$po_pembelian_id = '';
			$supplier_id = '';
			$nama_supplier = '';
			$po_number = '';
			$sales_contract = '';
			$tanggal = '';
			$ori_tanggal = '';
			$toko_id = '';
			$nama_toko = '';
			
			$po_status = 0;
			$status_aktif = 0;
			$catatan = '';

			foreach ($po_pembelian_data as $row) {
				$po_pembelian_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$po_number = $row->po_number;
				$sales_contract = $row->sales_contract;
				
				$status_aktif = $row->status_aktif;
				$catatan = $row->catatan;
			}

			foreach ($po_pembelian_data_batch as $row) {
				$tanggal = is_reverse_date($row->tanggal);
				$batch = $row->batch;
			}


			$readonly = ''; $disabled = '';
			if (is_posisi_id() == 6) {
				$readonly = 'readonly';
				$disabled = 'disabled';
			}
		?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_batch_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'>+ PO WARNA </h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">PO MASTER<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" disabled class="form-control" value="<?=$po_number?>"/>
			                    </div>
			                </div> 	

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select disabled class='form-control' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?=($row->id==1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
					                <input hidden value="<?=$po_pembelian_id;?>" name="po_pembelian_id"/>
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
		
		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_warna_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Data Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='po_pembelian_id' value='<?=$po_pembelian_id;?>' hidden>
			                    	<input name='po_pembelian_detail_warna_id' hidden>
			                    	<input name='batch_id' value='<?=$batch_id;?>' hidden>
			                    	<select name="po_pembelian_detail_id" class='form-control input1' id='barang_id_select'>
		                				<?foreach ($data_barang_po as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_barang;?> | Kuota : <?=number_format($row->sisa_kuota,'0',',','.');?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="warna_id" class='form-control' id='warna_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty <span class='satuan_unit'></span> <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control amount-number" name="qty"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH <span class='satuan_unit'>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="OCKH"/>
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save-brg">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-faktur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url().is_setting_link('transaction/po_pembelian_detail');?>" class="form-horizontal" id="form_search_po" method="post">
							<h3 class='block'> Cari PO</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PO Number<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='po_pembelian_id' id="search_po_number" class="form-control select2">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-search-po">GO!</button>
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
					<div class="portlet-title hidden-print">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<?if (is_posisi_id() != 6) { ?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i>PO Batch Baru </a>
							<?}?>
							<!--<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari PO </a>-->
						</div>
					</div>
					<div class="portlet-body">

						<div style='font-size:2em;text-align:right' class='hidden-print'>
							BATCH : 
							<?foreach ($po_pembelian_data_batch as $row) {
								if ($batch_id == $row->id) {?>
									<span style="padding:5px; background:black; color:white"><?=$row->batch?></span>
								<?}else{?>
									<a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$row->id;?>"><?=$row->batch;?></a>
								<?}?>
							<?}?>
						</div>
						<hr/>
						<div id="po-pembelian-header" style='margin-bottom:15px'>
							<div class='text-center'>
								<?foreach ($toko_data as $row) {?>
									<span style='font-size:1.5em'> <b><?=strtoupper($row->nama);?></b></span><br/>
									<span style='font-size:1.2em'> <?=$row->alamat;?><br/>
										<?=$row->telepon?>
										<?=($row->fax != '' ? '/'.$row->fax : '');?><br/>
										<?=($row->kota != '' ? $row->kota.$row->kode_pos : '');?><br/>
									</span>
								<?}?>
							</div>
							<hr/>
							<h1 class='text-center'>PURCHASE ORDER <span style="color:blue">(DETAIL WARNA)</span></h1>
							<div class='text-center' style='font-size:1.2em'><b>PO : <?=$po_number;?></b></div>
							<table width='100%'>
								<tr>
									<td class='text-left' width="65%" style='vertical-align:top'>
										<span style='font-size:2em'><b>PO : <?=$po_number;?> / <?=$batch_id;?></b></span><br/>
										Tanggal : <?=$tanggal?> <br/>
										Sales Contract : <b><?=$sales_contract;?></b>
									</td>
									<td>
										Kepada Yth, <br/>
										<?foreach ($supplier_data as $row) {?>
											<?=$row->nama;?><br/>
											<?=($row->alamat != '' ? $row->alamat.'<br/>' : '');?>
											<?=($row->telepon != '' ? $row->telepon.'<br/>' : '');?>
											<?=($row->kota != '' ? $row->kota.'<br/>' : '');?>
										<?}?>
									</td>								
								</tr>
							</table>
						</div>

						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" width='200px'>
										Nama Barang
									</th>
									<th scope="col">
										Warna
										<?if ($batch_id != '') { ?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add hidden-print">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Jml Yard/KG
									</th>
									<th scope="col" class='hidden-print'  width='200px' hidden>
										OCKH
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$g_total = 0;
								if ($batch_id != '') {
									foreach ($data_barang_po as $row2) {
										$i =1;
										$merge_baris = count($po_pembelian_data_warna[$row2->id]);
										foreach ($po_pembelian_data_warna[$row2->id] as $row) { 
											$s_total[$row->id] = 0;?>
											<tr>
												<?if ($i == 1) {?>
													<td rowspan='<?=$merge_baris;?>'>
														<?=$row->nama_barang;?> 
													</td>
												<?}?>
												<td>
													<span class='nama_beli'><?=$row->nama_warna?></span> 
												</td>
												<td>
													<span class='free-input-sm qty' ><?=str_replace(',00', '', number_format($row->qty,'2',',','.'));?> </span>
													<?=$row->nama_satuan;?>
												</td>
												<td class="hidden-print" hidden>
													<input name="OCKH" value="<?=$row->OCKH?>" width='100px' class='OCKH'>
												</td>
												<td class='hidden-print'>
													<span class='po_pembelian_detail_id' hidden><?=$row->po_pembelian_detail_id;?></span>
													<span class='warna_id' hidden><?=$row->warna_id;?></span>
													<span class='id' hidden><?=$row->id;?></span>
													<a href='#portlet-config-detail' data-toggle='modal' class="btn-xs btn green btn-detail-edit"><i class="fa fa-edit"></i> </a>
													<a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
												</td>
											</tr>
										<? $i++;
										} 
									}
								}?>
							</tbody>
						</table>

						<hr/>
						<div>
			                <a class='btn btn-lg blue hidden-print' onclick='window.print()'><i class='fa fa-print'></i> Print </a>
			                <?if ($po_status == 1) { ?>
				                <a <?=($i == 1 ? 'disabled' : '' );?> class="btn btn-lg yellow-gold btn-active <?=($i != 1 ? 'btn-finish' : '');?> hidden-print"><i class='fa fa-print'></i> Finish </a>
			                <?}elseif ($po_status == 0 && is_posisi_id() <= 3 ) {?>
				                <a href="<?=base_url()?>transaction/po_pembelian_open?id=<?=$po_pembelian_id;?>" class='btn btn-lg yellow-gold btn-edit-po hidden-print'><i class='fa fa-print'></i> Edit </a>
			                <?}?>
			                <a href="javascript:window.open('','_self').close();" class="btn btn-lg default button-previous hidden-print">Close</a>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {

	
	$('#barang_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('#warna_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    <?if ($po_pembelian_id != '' && is_posisi_id() != 6) { ?>
    	var map = {220: false};
		$(document).keydown(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[220]) {
		            $('#portlet-config-detail').modal('toggle');
		            setTimeout(function(){
		            	$('#barang_id_select').select2("open");
		            },600);
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
    <?}?>

    //=============form add data========================
    $(".btn-save").click(function(){
    	if( $("#form_add_data [name=tanggal]").val() != ''){
    		$("#form_add_data").submit();
    		btn_disabled_load($(".btn-save"));
    	}else{
    		alert("Tanggal tidak boleh kosong");
    	}
    });

    $('.btn-save-brg').click(function(){
    	if ($('#form_add_barang [name=po_pembelian_detail_id]').val() != '' && $('#form_add_barang [name=qty]').val() != '' ) {
    		$('#form_add_barang').submit();
    		btn_disabled_load($(".btn-save-brg"));
    	};
    });

    $('#general_table').on('click', '.btn-detail-edit', function(){
    	const ini = $(this).closest('tr');
    	let form = $('#form_add_barang');

    	form.find("[name=po_pembelian_detail_warna_id]").val(ini.find('.id').html());
    	form.find("[name=po_pembelian_detail_id]").val(ini.find('.po_pembelian_detail_id').html());
    	form.find("[name=po_pembelian_detail_id]").change();
    	form.find("[name=warna_id]").val(ini.find('.warna_id').html());
    	form.find("[name=warna_id]").change();
    	form.find("[name=qty]").val(ini.find('.qty').html());
    	
    });

    $('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	$("#form_add_barang [name=po_pembelian_detail_warna_id]").val("");
    	$("#form_add_barang [name=qty]").val("");
    	setTimeout(function(){
    		$('#barang_id_select').select2("open");
    		// $('#form_add_barang .input1 .select2-choice').click();
    	},700);
    });

    $('#barang_id_select').change(function(){
    	var barang_id = $('#barang_id_select').val();
  //  		var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
  //  		// alert(data);
		// $('#form_add_barang [name=harga]').val(change_number_format(data[1]));
		// $('#form_add_barang .satuan_unit').html(data[0]+'/kg');
		// $('#form_add_barang [name=satuan]').val(data[0]);
    });


    $('#general_table').on('click','.btn-detail-remove', function(){
	    let ini = $(this).closest('tr');
    	let url = baseurl+'transaction/pembelian_detail_warna_remove';
	    bootbox.confirm("Mau menghapus item ini ? ", function(respond){
	    	if (respond) {
	    		let id = ini.find('.id').html();
	    		let batch_id = "<?=$batch_id?>";
	    		let po_pembelian_id = "<?=$po_pembelian_id?>";
	    		window.location.replace(url+"?id="+id+"&batch_id="+batch_id+"&po_pembelian_id="+po_pembelian_id);
	    	};
	    });
    });

    <?if ($po_pembelian_id != '') { ?>
    	$(document).on('change','.OCKH', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['id'] =  ini.find(".id").html();
	    	data['OCKH'] = $(this).val();
	    	var url = 'transaction/po_pembelian_OCKH_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					notific8("lime", "OCKH updated");
				}else{
					alert("error");
				}
	   		});
	    });

    <?}?>

    $('.btn-search-faktur').click(function(){
    	var id = $("#form_search_po [name=po_pembelian_id]").val();
    	var action = $("#form_search_po").attr('action');
    	if (id != '') {
    		window.location.replace(action+'/'+id);
    	};
    });

    $('.btn-finish').click(function(){
    	btn_disabled_load($(this));
    	let po_pembelian_id = "<?=$po_pembelian_id?>";
    	window.location.replace(baseurl+"transaction/po_pembelian_finish?id="+po_pembelian_id)
    });

    $("#general_table").on('click','.show-warna', function () {
    	const ini = $(this).closest('tr');
    	let data_id = $(this).attr("id").split('-');
    	let id = data_id[1];
    	// alert($('#data-warna-'+id).html());
    	$('.data-warna-'+id).toggle("slow");
    })

});

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		subtotal+= reset_number_format($(this).html());
	});

	$('.total').html(change_number_format(subtotal));
	var diskon = reset_number_format($('.diskon').val());
	var g_total = subtotal - parseInt(diskon);
	$('.g_total').html(change_number_format(g_total));

}
</script>
