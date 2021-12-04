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
				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$toko_id = $row->toko_id;
				$nama_toko = $row->nama_toko;
				
				$po_status = $row->po_status;
				$status_aktif = $row->status_aktif;
				$catatan = $row->catatan;
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
						<form action="<?=base_url('transaction/po_pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'>PO Pembelian Baru</h3>
							
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
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="OCKH"/>
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
		
		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit Data</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Sales Contract
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="sales_contract" value="<?=$sales_contract?>"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=$tanggal;?>" name="tanggal"/>
			                    </div>
			                </div> 	

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" name="po_pembelian_id" value='<?=$po_pembelian_id;?>' hidden/>
			                    	<select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?if ($supplier_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>			                

			                
			                <div class="form-group">
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option <?if ($toko_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select> 
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/po_pembelian_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Data Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='po_pembelian_detail_id' hidden>
			                    	<input name='po_pembelian_id' value='<?=$po_pembelian_id;?>' hidden>
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<select name='data_barang' hidden>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" class="form-control amount-number" value="" name="harga"/>
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

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Jumlah Roll
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="jumlah_roll"/>
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
								<i class="fa fa-plus"></i>PO Pembelian Baru </a>
							<?}?>
							<!--<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari PO </a>-->
						</div>
					</div>
					<div class="portlet-body">

						<div id="po-pembelian-header" style='margin-bottom:15px'>
							<div class='text-center'>
								<?foreach ($toko_data as $row) {?>
									<span style='font-size:1.5em'> <b><?=strtoupper($nama_toko);?></b></span><br/>
									<span style='font-size:1.2em'> <?=$row->alamat;?><br/>
										<?=$row->telepon?>
										<?=($row->fax != '' ? '/'.$row->fax : '');?><br/>
										<?=($row->kota != '' ? $row->kota.$row->kode_pos : '');?><br/>
									</span>
								<?}?>
							</div>
							<hr/>
							<h1 class='text-center'>PURCHASE ORDER</h1>
							<table width='100%'>
								<tr>
									<td class='text-left' width="65%" style='vertical-align:top'>
										<span style='font-size:2em'><b> PO: <?=$po_number;?></b></span><br/>
										Tanggal : <?=$tanggal?> <br/>
										<div class='hidden-print'>
											Sales Contract : <input name='sales_contract' value="<?=$sales_contract;?>">
										</div>
									</td>
									<td>
										<?if (is_posisi_id() != 6 && $po_status == 1) { ?>
											<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs hidden-print'><i class='fa fa-edit'></i> edit</button><br/>
										<?}?>
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

						<?if (count($po_pembelian_detail) > 0 ) {?>
							<div style="margin:10px 0">
								<a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id;?>" class='btn default' style="width:100%"><i class='fa fa-arrow-right'></i> PO DETAIL WARNA</a>
							</div>
						<?}?>

						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Nama Barang
										<?if ($po_pembelian_id != '' && is_posisi_id() !=6 &&  $po_status == 1) { ?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add hidden-print">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Jml Yard/KG
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$i =1; $g_total = 0;
								foreach ($po_pembelian_detail as $row) { 
									$s_total[$row->id] = 0;?>
									<tr>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<?=$i;?> 
										</td>
										<td>
											<span class='nama_beli'><?=$row->nama_barang;?></span> 
											<?if(count($po_pembelian_warna[$row->id]) > 0) { ?>
												<a id="show-<?=$row->id;?>" class='show-warna hidden-print' style='font-size:0.9em;'><i><i class='fa fa-arrow-down'></i> warna</i></a>
											<?}?>
										</td>
										<td>
											<input name='qty' <?=$readonly;?> class='free-input-sm qty' value="<?=str_replace(',00', '', number_format($row->qty,'2',',','.'));?>"> 
											<?=$row->nama_satuan;?>
										</td>
										<td>
											<input name='harga' <?=$readonly;?> class='free-input-sm amount_number harga' value="<?=number_format($row->harga,'0',',','.');?>"> 
										</td>
										<td>
											<?
												$subtotal = $row->qty * $row->harga;
												$g_total += $subtotal;
											?>
											<span <?=$readonly;?> class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td>
										<td class='hidden-print'>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='barang_id' hidden><?=$row->barang_id;?></span>
											<?if(is_posisi_id() != 6){?>
												<?if ( $po_status == 1) {?>
													<a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
													<a href='#portlet-config-detail' data-toggle='modal' class="btn-xs btn green btn-detail-edit"><i class="fa fa-edit"></i> </a>
												<?}?>
											<?} ?>
											<a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna');?>?po_pembelian_id=<?=$po_pembelian_id?>&po_pembelian_detail_id=<?=$row->id;?>&view_type=1" class="btn-xs btn yellow-gold" onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class="fa fa-search"></i> </a>

										</td>
									</tr>
									<?foreach ($po_pembelian_warna[$row->id] as $row2) {?>
										<tr class='hidden-print data-warna-<?=$row->id;?>' style="border:none; background:#ccd; display:none">
											<td></td>
											<td>
												<span class='nama_warna'><?=$row2->nama_warna;?></span> <i><small>(batch <?=$row2->batch;?>)</small></i>
											</td>
											<td class='text-right'>
												<span class='qty-warna'> <?=str_replace(',00', '', number_format($row2->qty,'2',',','.'));?></span>
												<?=$row->nama_satuan;
													$s_total[$row->id] += $row2->qty;
												?>
											</td>
											<td colspan='3'>
												<span class='po_pembelian_warna_id' hidden="hidden"><?=$row2->id;?></span>
												<span class='warna_id' hidden><?=$row2->barang_id;?></span>
											</td>
										</tr>
									<?}
									if ($s_total[$row->id] != 0) {?>
										<tr class='hidden-print data-warna-<?=$row->id;?>' style="border:none; background:#ccd;  display:none">
											<td></td>
											<td></td>
											<td class="text-right" style=""><b style='font-size:1.2em; border-top:1px solid black; padding-left:20px'>SISA KUOTA : <?=number_format($row->qty - $s_total[$row->id],'0',',','.')?> <?=$row->nama_satuan;?></b></td>
											<td colspan='3'></td>
										</tr>
									<?}
									?>
								<? $i++;} ?>
							</tbody>
						</table>

						<hr/>
						<div>
			                <a class='btn btn-lg blue hidden-print' onclick='window.print()'><i class='fa fa-print'></i> Print </a>
			                <?if ($po_status == 1) { ?>
				                <a <?=($i == 1 ? 'disabled' : '' );?> class="btn btn-lg yellow-gold btn-active <?=($i != 1 ? 'btn-finish' : '');?> hidden-print"><i class='fa fa-print'></i> Finish </a>
			                <?}elseif ($po_status == 0 && is_posisi_id() <= 3 ) {?>
				                <a href="<?=base_url()?>transaction/po_pembelian_open?id=<?=$po_pembelian_id;?>" class='btn btn-lg yellow-gold btn-edit-po hidden-print' ><i class='fa fa-print'></i> Edit </a>
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

    $('.btn-save-brg').click(function(){
    	if ($('#form_add_barang [name=barang_id]').val() != '') {
    		$('#form_add_barang').submit();
    		btn_disabled_load($(this));
    	};
    });

    $('#general_table').on('click', '.btn-detail-edit', function(){
    	var ini = $(this).closest('tr');
    	var form = $('#form_add_barang');

    	form.find("[name=po_pembelian_detail_id]").val(ini.find('.id').html());
    	form.find("[name=barang_id]").val(ini.find('.barang_id').html());
    	form.find("[name=barang_id]").change();
    	form.find("[name=qty]").val(ini.find('[name=qty]').val());
    	form.find("[name=harga]").val(ini.find('[name=harga]').val());


    });

    $('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	setTimeout(function(){
    		$('#barang_id_select').select2("open");
    		// $('#form_add_barang .input1 .select2-choice').click();
    	},700);
    });

    $('#barang_id_select').change(function(){
    	var barang_id = $('#barang_id_select').val();
   		var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
   		// alert(data);
		$('#form_add_barang [name=harga]').val(change_number_format(data[1]));
		$('#form_add_barang .satuan_unit').html(data[0]+'/kg');
		$('#form_add_barang [name=satuan]').val(data[0]);
    });


    $('#general_table').on('click','.btn-detail-remove', function(){
	    var ini = $(this).closest('tr');
	    bootbox.confirm("Mau menghapus item ini ? ", function(respond){
	    	if (respond) {
	    		var data = {};
		    	data['id'] =  ini.find('.id').html();
		    	var url = 'transaction/pembelian_detail_remove';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						ini.remove();
						update_table();
					};
		   		});
	    	};
	    });
    });

    <?if ($po_pembelian_id != '') { ?>
    	$(document).on('change','[name=sales_contract]', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['po_pembelian_id'] =  "<?=$po_pembelian_id;?>";
	    	data['sales_contract'] = $(this).val();
	    	var url = 'transaction/po_pembelian_sales_contract_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					notific8("lime", "Sales Contract updated");
				};
	   		});
	    });

    <?}?>

    $("#search_no_faktur").select2({
        placeholder: "Select...",
        allowClear: true,
        minimumInputLength: 1,
        query: function (query) {
            var data = {
                results: []
            }, i, j, s;
            var data_st = {};
			var url = "transaction/get_search_po_number";
			data_st['no_faktur'] = query.term;
			
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// console.log(data_respond);
				$.each(JSON.parse(data_respond),function(k,v){
					data.results.push({
	                    id: v.id,
	                    text: v.no_faktur
	                });
				});
	            query.callback(data);
	   		});
        }
    });

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
