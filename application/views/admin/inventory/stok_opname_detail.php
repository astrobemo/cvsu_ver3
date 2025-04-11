<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

.nav-tabs li.active a{
	font-weight: bold !important;
}

.table-head tr th, .table-foot tr th{
	padding: 5px;
	border:1px solid #ddd ;
	font-size: 1.2em;
	text-align: center;
}

.table-body tr td{
	/*padding: 5px;*/
	height: 31px;
	border:1px solid #ddd ;
	font-size: 1.1em;
}

.table-body input{
	border: none;
	width: 100%;
	height: 100%;
	/*padding-left: 5px;*/
	text-align: center;
}

#rekap-table tr td, #rekap-table tr th{
	padding: 5px 10px;
	font-size: 1.3em;
}

.eceran, .eceran a{
	background:#ffe6e6 !important;
}

</style>

<div class="page-content">
	<div class='container'>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							
							<a target="_blank" href="<?=base_url().('inventory/data_barang_excel_so');?>" class="btn green btn-md">
							<i class="fa fa-download"></i> Daftar Barang Untuk SO </a>
						</div>
					</div>
					<div class="portlet-body">
						<div class='row'>
							<div class='col-xs-12'>
								<table width='100%'>
									<tr>
										<td width='40%'>
											<table width='100%' id='tbl-data-barang'>
												<tr>
													<td>Tanggal<span class="required"></td>
													<td> : </td>
													<td><input readonly name='tanggal' class='form-control' value="<?=is_reverse_date($tanggal_so);?>" ></td>
													<td></td>
												</tr>
												<tr>
													<td>Toko</td>
													<td> : </td>
													<td>
														<select id='toko_id_select' class='form-control' name='toko_id_filter' >
															<option value=''>Pilih</option>
															<?foreach ($this->toko_list_aktif as $row) { ?>
																<option value='<?=$row->id?>'><?=$row->nama;?></option>
															<?}?>
														</select>
													</td>
													<td></td>
												</tr>
												<tr>
													<td>Supplier</td>
													<td> : </td>
													<td>
														<select id='supplier_id_select' class='form-control' name='supplier_id_filter' >
															<option value=''>Pilih</option>
															<?foreach ($this->supplier_list_aktif as $row) { ?>
																<option value='<?=$row->id?>'><?=$row->nama;?></option>
															<?}?>
														</select>
													</td>
													<td></td>
												</tr>
												<tr>
													<td>Barang</td>
													<td> : </td>
													<td>
														<select id='barang_id_select' class='form-control' name='barang_id_filter' >
															<option value=''>Pilih</option>
															<?foreach ($this->barang_list_aktif as $row) { ?>
																<option value='<?=$row->id?>'><?=$row->nama_jual;?></option>
															<?}?>
														</select>
														<select id='barang_id_copy' hidden>
															<option value=''></option>
															<?foreach ($this->barang_list_aktif as $row) { ?>
																<option value='<?=$row->id?>'><?=$row->nama_satuan;?>??<?=$row->nama_packaging;?></option>
															<?}?>
														</select>
													</td>
													<td></td>
												</tr>
												<tr>
													<td>Keterangan</td>
													<td> : </td>
													<td>
														<select id='warna_id_select' class='form-control' name='warna_id_filter' >
															<option value=''>Pilih</option>
															<?foreach ($this->warna_list_aktif as $row) {?>
																<option value='<?=$row->id?>'><?=$row->warna_jual;?></option>
															<?}?>
														</select>
													</td>
													<td hidden><button class='btn btn-md green form-control search-opname'><i class='fa fa-search'></i></button></td>
												</tr>
											</table>
										</td>
										<td>
											<?if (is_posisi_id()==1) {?>
												TOKO ID : <span id='toko-id-selected'></span><br/> 
												Barang ID : <span id='barang-id-selected'></span><br/> 
												Warna ID : <span id='warna-id-selected'></span><br/>
												Supplier ID : <span id='supplier-id-selected'></span>
											<?}?>
											<h1 style='float:right;font-weight:bold' id='nama-barang'></h1>
										</td>
									</tr>
								</table>
							</div>
							<div class='col-xs-12'>
								<hr/>
								<div class="tabbable tabbable-custom">
									<ul class="nav nav-tabs">
										<?$idx = 0;foreach ($this->gudang_list_aktif as $row) {?>
											<li class="<?=($idx == 0 ? 'active' : '');?>">
												<a href="#tab_1_<?=$idx+1;?>" data-toggle="tab">
												<?=$row->nama;?> <span class='event-gudang<?=$row->id;?>' hidden style='color:red'>*</span> </a>
											</li>
										<?$idx++;}?>
										<?foreach ($this->gudang_list_aktif as $row) {?>
											<li class='eceran'>
												<a href="#tabecer_1_<?=$idx+1;?>" data-toggle="tab">
												<?=str_replace('Gudang', 'Eceran', $row->nama);?> <span class='event-gudangecer<?=$row->id;?>' hidden style='color:red'>*</span> </a>
											</li>
										<?$idx++;}?>
									</ul>

									<div class="tab-content col-md-6">
										<?$idx = 0;foreach ($this->gudang_list_aktif as $row) {?>
											<div class="tab-pane <?=($idx == 0 ? 'active' : '');?>" id="tab_1_<?=$idx+1;?>">
												<div class="tbl-container" id='gudang<?=$row->id?>'>
													<table class='table-head' >
														<thead>
															<th style='width:50px' onclick="sortTable(0)" >No</th>
															<th style='width:100px' onclick="sortTable(1)" ><span class='nama-satuan-kecil'></span></th>
															<th style='width:80px' onclick="sortTable(2)" ><span class='nama-satuan-besar'></span></th>
															<th style='width:100px' onclick="sortTable(3)" >Total</th>
															<th style='width:100px; border:none; vertical-align:top' >
																<?$hidden = (is_posisi_id()!=1 ? 'hidden' : '');?>
																<form class='form-stok-detail' style='position:absolute; margin-left:20px' method='post' hidden >
																	<input name='stok_opname_id' value="<?=$stok_opname_id?>" <?=$hidden?> > <br/>
																	<input name='gudang_id' value="<?=$row->id?>" <?=$hidden?> ><br/>
																	<input name='toko_id' <?=$hidden?> ><br/>
																	<input name='barang_id' <?=$hidden?> ><br/>
																	<input name='warna_id' <?=$hidden?> ><br/>
																	<input name='rekap_qty' <?=$hidden?> ><br/>
																	<small class='event-gudang<?=$row->id;?>' hidden></small>
																</form>
																<button style='display:none' type='button' class='btn btn-lg green btn-save-detail event-gudang<?=$row->id;?>' id='btn-save-gudang<?=$row->id;?>'>SAVE</button>
																<span class='loading' hidden>load...</span>
																<span class='done' hidden>Updated <i class='fa fa-check' style='color:blue' ></i></span>
															</th>
															<!-- <th style='width:100px'></th> -->
														</thead>
													</table>
													<div style="height:235px; overflow:auto; width:450px" >
														<table class='table-body' id="table-body">
															<tbody>
																<?for ($i=1; $i < 51 ; $i++) { ?>
																	<tr>
																		<td style='width:50px' class='text-center'><?=$i?></td>
																		<td style='width:100px' class='text-center'><input disabled name='qty' tabindex="<?=($i*2)-1?>" class='qty'></td>
																		<td style='width:80px' class='text-center'><input disabled name='roll' tabindex="<?=($i*2)?>" class='jumlah-roll'></td>
																		<td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
																		<!-- <td style='width:100px'></td> -->
																	</tr>
																<?}?>
															</tbody>
														</table>
													</div>
													<table class='table-foot'>
														<tfoot>
															<tr>
																<th style='width:150px' class='text-center'>Total</th>
																<th style='width:80px' class='jumlah_roll_total'></th>
																<th style='width:100px' class='yard_total'></th>
															</tr>
															<!-- <th style='width:100px'></th> -->
														</tfoot>
													</table>


												</div>
											</div>	
										<?$idx++;}?>
										<!-- bagian eceran -->
										<?foreach ($this->gudang_list_aktif as $row) {?>
											<div class="tab-pane" id="tabecer_1_<?=$idx+1;?>">
												<div class="tbl-container" id='gudangecer<?=$row->id?>'>
													<table class='table-head' >
														<thead>
															<th style='width:50px' onclick="sortTable(0)" >No</th>
															<th style='width:100px' onclick="sortTable(1)" ><span class='nama-satuan-kecil'></span></th>
															<th style='width:80px' onclick="sortTable(2)" hidden><span class='nama-satuan-besar'></span></th>
															<th style='width:100px' onclick="sortTable(3)" hidden >Total</th>
															<th style='width:100px; border:none; vertical-align:top' >
																<?$hidden = (is_posisi_id()!=1 ? 'hidden' : '');?>
																<form class='form-stok-detail' style='position:absolute; margin-left:20px' method='post' hidden >
																	<input name='stok_opname_id' value="<?=$stok_opname_id?>" <?=$hidden?> > <br/>
																	<input name='gudang_id' value="<?=$row->id?>" <?=$hidden?> ><br/>
																	<input name='toko_id' <?=$hidden?> ><br/>
																	<input name='barang_id' <?=$hidden?> ><br/>
																	<input name='warna_id' <?=$hidden?> ><br/>
																	<input name='rekap_qty' <?=$hidden?> ><br/>
																	<small class='event-gudang<?=$row->id;?>' hidden></small>
																</form>
																<button style='display:none' type='button' class='btn btn-lg blue btn-save-ecer event-gudangecer<?=$row->id;?>' id='btn-save-ecer-gudang<?=$row->id;?>'>SAVE</button>
																<span class='loading' hidden>load...</span>
																<span class='done' hidden>Updated <i class='fa fa-check' style='color:blue' ></i></span>
															</th>
															<!-- <th style='width:100px'></th> -->
														</thead>
													</table>
													<div style="height:235px; overflow:auto; width:450px" >
														<table class='table-body'  id="table-body">
															<tbody>
																<?for ($i=1; $i < 51 ; $i++) { ?>
																	<tr>
																		<td style='width:50px' class='text-center'><?=$i?></td>
																		<td style='width:100px' class='text-center'><input disabled name='qty' tabindex="<?=($i*2)-1?>" class='qty'></td>
																		<td hidden style='width:80px' class='text-center'><input disabled name='roll' tabindex="<?=($i*2)?>" class='jumlah-roll'></td>
																		<td hidden style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
																		<!-- <td style='width:100px'></td> -->
																	</tr>
																<?}?>
															</tbody>
														</table>
													</div>
													<table class='table-foot'>
														<tfoot>
															<tr>
																<th style='width:150px' class='text-center'>Total</th>
																<th style='width:100px' class='yard_total'></th>
															</tr>
															<!-- <th style='width:100px'></th> -->
														</tfoot>
													</table>


												</div>
											</div>	
										<?$idx++;}?>
									</div>
									<div class='col-md-6'>
										<table id='rekap-table'>
											<tr>
												<th></th>
												<th></th>
												<th>QTY</th>
												<th>ROLL</th>
												<th style='font-size:0.9em'>QTY <br/>before</th>
												<th style='font-size:0.9em'>ROLL <br/>before</th>
											</tr>
											<?$idx = 0;foreach ($this->gudang_list_aktif as $row) {?>
												<tr>
													<td><?=$row->nama;?> </td>
													<td>:</td>
													<td><span class='rekap-yard-gudang<?=$row->id;?>'></span> </td>
													<td><span class='rekap-roll-gudang<?=$row->id;?>'></span> </td>
													<td><span class='rekap-yard-before<?=$row->id;?>'></span> </td>
													<td><span class='rekap-roll-before<?=$row->id;?>'></span> </td>
												</tr>
											<?$idx++;}?>
											<tr style="background:#ddd">
												<td colspan='6'></td>
											</tr>
											<?$idx = 0;foreach ($this->gudang_list_aktif as $row) {?>
												<tr>
													<td><?=str_replace('Gudang', 'Eceran', $row->nama);?> </td>
													<td>:</td>
													<td><span class='rekap-yard-eceran<?=$row->id;?>'></span> </td>
													<td><span class='rekap-roll-eceran<?=$row->id;?>'></span> </td>
													<td><span class='rekap-yard-eceranbefore<?=$row->id;?>'></span> </td>
													<td><span class='rekap-roll-eceranbefore<?=$row->id;?>'></span> </td>
												</tr>
											<?$idx++;}?>
										</table>
									</div>
								</div>
								<hr/>
								<div style='text-align:right'>
									<?
									if(is_posisi_id()==1){
										if ($status_aktif == 0 ) {?>
											<button type='button' class='btn btn-lg red hidden-print btn-close' id='btn-lock-so' onclick="lockSO(<?=$id;?>)"><i class='fa fa-lock'></i> LOCK </button>
										<?}else{?>
											<h2><i class='fa fa-locked'></i> LOCKED</h2>
										<?}
									} ?>
								</div>
							</div>
						</div>
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

<script>
const stok_opname_id = "<?=$stok_opname_id?>";

var gudang_id = [];
count_gudang = 0;
	<?foreach ($this->gudang_list_aktif as $row) {?>
		gudang_id.push(<?=$row->id;?>);
		// window["count_gudang<?=$row->id?>"] = 0;
	<?}?>

var toko_id_now = '';
var barang_id_now = '';
var warna_id_now = '';
var barang_id_select = '';		
var supplier_id_select = '';		
var warna_id_select = '';
var isSaved = true;

const status_aktif = <?=$status_aktif;?>


jQuery(document).ready(function() {
	//Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	// TableAdvanced.init();

	if (status_aktif == 1) {
		<?//if (is_posisi_id() > 3) {?>
			// $('.table-body').find('.qty, .jumlah-roll').prop('disabled',true);
		<?//};?>
	};


	$('#barang_id_select, #warna_id_select, #supplier_id_select').select2();

	
	var map = {13: false};
    $(".table-body").on('keydown','input',function(e) {
      if (e.keyCode in map) {
        map[e.keyCode] = true;
        if (map[13]) {
          	var tabindex = $(this).attr('tabindex');
          	tabindex++;
          	// console.log(tabindex);
	        $('.table-body').find('input[tabindex='+tabindex+']').focus();
          // document.forms[idx].elements[2].focus();
        }
      }            
    }).keyup(function(e) {
        if (e.keyCode in map) {
            map[e.keyCode] = false;
        }
    });

	/*Untuk table : 
	- sort
	- automatic show table
	- automatic add row
	- automatic delete row
	*/

	//==============================change qty-table==============================================

	$(".table-body").on('change','.qty, .jumlah-roll',function(){
		var tbl_body = $(this).closest('.table-body');
		// alert(tbl_body.html());
    	data_result = table_qty_update(tbl_body).split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	$(this).closest('.tbl-container').find('[name=rekap_qty]').val(rekap);
    	
    	if (total == 0) {
			$('.btn-save').attr('disabled',true);
		}else{
			$('.btn-save').attr('disabled',false);
		}

		isSaved = false;

		var id = $(this).closest('.tbl-container').attr('id');
		var foot = $(this).closest('.tbl-container').find('.table-foot');
		$(this).closest('.tbl-container').find('.done').hide();
		
    	foot.find('.yard_total').html(parseFloat(total));
    	foot.find('.jumlah_roll_total').html(total_roll);

    	$(".event-"+id).show();
    	count_gudang++;
    	// window['count_'+id] += 1;
    	// alert(window['count_'+id]);
    });

    //==============================================================================

    $('.table-head th').click(function(){
	    /*var table = $('.table-body');
	    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
	    this.asc = !this.asc
	    if (!this.asc){rows = rows.reverse()}
	    for (var i = 0; i < rows.length; i++){table.append(rows[i])}*/
	});

	$("#toko_id_select, #barang_id_select, #warna_id_select, #supplier_id_select" ).change(function(){
		if($('#barang_id_select').val() != '' && $("#warna_id_select").val() != '' && $("#supplier_id_select").val() != '' && $("#toko_id_select").val() != '' ){
			var totalan = 0;
			totalan = count_gudang;
			

				// alert('*');

			// alert(totalan);

			// alert(count_gudang);
			if (totalan == 0) {
				// count_gudang = 0;
				searchAndDrawTable();
			}else{
				toko_id_now = $("#toko-id-selected").html();
				barang_id_now = $("#barang-id-selected").html();
				warna_id_now = $("#warna-id-selected").html();
				supplier_id_now = $("#supplier-id-selected").html();
				
				toko_id_select = $('#toko_id_select').val();		
				barang_id_select = $('#barang_id_select').val();		
				warna_id_select = $('#warna_id_select').val();
				supplier_id_select = $('#supplier_id_select').val();
				

				if (toko_id_now != toko_id_select || barang_id_now != barang_id_select || warna_id_now != warna_id_select || supplier_id_now != supplier_id_select) {
					bootbox.confirm("Table belum di save, yakin ubah nama barang ? ", function(respond){
						if(respond){
							count_gudang = 0;
							searchAndDrawTable();
						}else{
							$("#toko_id_select").val(toko_id_now);
							$("#barang_id_select").val(barang_id_now);
							$("#warna_id_select").val(warna_id_now);
							$("#supplier_id_select").val(supplier_id_now);

							$('#toko_id_select').change();
							$('#barang_id_select').change();
							$('#warna_id_select').change();
							$('#supplier_id_select').change();
						}
					});
					
				};
			}
		}

	});

	$(".search-opname").click(function(){
		var totalan = 0;
		totalan = count_gudang;
		// alert(totalan);

		// alert(count_gudang);
		if (totalan == 0) {
			// count_gudang = 0;
			searchAndDrawTable();
		}else{
			bootbox.confirm("Table belum di save, yakin ubah nama barang ? ", function(respond){
				if(respond){
					count_gudang = 0;
					searchAndDrawTable();
				}else{
					toko_id_now = $("#toko-id-selected").html();
					barang_id_now = $("#barang-id-selected").html();
					warna_id_now = $("#warna-id-selected").html();
					supplier_id_now = $("#supplier-id-selected").html();
					$("#toko_id_select").val(toko_id_now);
					$("#barang_id_select").val(barang_id_now);
					$("#warna_id_select").val(warna_id_now);
					$("#supplier_id_select").val(supplier_id_now);

					$('#toko_id_select').change();
					$('#barang_id_select').change();
					$('#warna_id_select').change();
					$('#supplier_id_select').change();

				}
			});
		}
	});

	$(".btn-save-detail").click(function(){
		$(this).hide();
		var parent = $(this).closest('.tbl-container');
		parent.find('.qty, .jumlah-roll').prop('disabled',true);
		parent.find('.loading').show();
		var id = parent.attr('id');
		var form = parent.find('.form-stok-detail');
		// var data_respond = ajax_form('inventory/stok_opname_detail_insert',form);
		var gudang_id = form.find('[name=gudang_id]').val();
		var baris_gudang = '';
		var idx = 1;
		ajax_form('inventory/stok_opname_detail_insert',form).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			$.each(JSON.parse(data_respond),function(k,v){
				baris_gudang += `<tr>
					<td style='width:50px' class='text-center'>${idx}</td>
					<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' value="${parseFloat(v.qty)}" tabindex="${(idx*2)-1}" class='qty'></td>
					<td style='width:80px' class='text-center'><input name='roll' autocomplete='off' value="${v.jumlah_roll}" tabindex="${idx*2}" class='jumlah-roll'></td>
					<td style='width:100px; padding-left:5px;' class='subtotal text-center'>${parseFloat(v.qty*v.jumlah_roll)}</td>
					<td hidden><span class='id'>${v.id}</span></td>
				</tr>`;
				idx++;
			});
			
			for (var i = idx; i < (idx+100); i++) {
				baris_gudang += `<tr>
					<td style='width:50px' class='text-center'>${i}</td>
					<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' tabindex="${(i*2)-1}" class='qty'></td>
					<td style='width:80px' class='text-center'><input name='roll' autocomplete='off' tabindex="${i*2}" class='jumlah-roll'></td>
					<td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
					<td hidden></td>
				</tr>`;
			};
			$('#gudang'+gudang_id).find('.table-body').html(baris_gudang);
			tbl_body = $('#gudang'+gudang_id).find('.table-body');
	    	data_result = table_qty_update(tbl_body).split('=*=');
	    	let rekap = data_result[2];
			$('#gudang'+gudang_id).find('[name=rekap_qty]').val(rekap);
			$('#gudang'+gudang_id).find('.yard_total').html(data_result[0]);
			$('#gudang'+gudang_id).find('.jumlah_roll_total').html(data_result[1]);
			$('.event-gudang'+gudang_id).hide();


			$('.rekap-yard-gudang'+gudang_id).html(data_result[0]);
			$('.rekap-roll-gudang'+gudang_id).html(data_result[1]);
			parent.find('.loading').hide();
			parent.find('.done').show();
			count_gudang=0;
			isSaved = true;
			// window["count_gudang"+v] = 0;

		});

		
	});

	$(".btn-save-ecer").click(function(){
		$(this).hide();
		var parent = $(this).closest('.tbl-container');
		parent.find('.qty, .jumlah-roll').prop('disabled',true);
		parent.find('.loading').show();
		var id = parent.attr('id');
		var form = parent.find('.form-stok-detail');
		var gudang_id = form.find('[name=gudang_id]').val();
		var baris_gudang = '';
		var idx = 1;
		ajax_form('inventory/stok_opname_detail_ecer',form).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			$.each(JSON.parse(data_respond),function(k,v){
				baris_gudang += `<tr>
					<td style='width:50px' class='text-center'>${idx}</td>
					<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' value="${parseFloat(v.qty)}" tabindex="${(idx*2)-1}" class='qty'></td>
					<td hidden><span class='id'>${v.id}</span></td>
				</tr>`;
				idx++;
			});
			// <td style='width:100px; padding-left:5px;' class='subtotal text-center'>${parseFloat(v.qty*v.jumlah_roll)}</td>

			
			for (var i = idx; i < (idx+100); i++) {
				baris_gudang += `<tr>
					<td style='width:50px' class='text-center'>${i}</td>
					<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' tabindex="${(i*2)-1}" class='qty'></td>
					<td hidden></td>
					</tr>`;
				};
				// <td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
			$('#gudangecer'+gudang_id).find('.table-body').html(baris_gudang);
			tbl_body = $('#gudangecer'+gudang_id).find('.table-body');
	    	data_result = table_qty_update(tbl_body).split('=*=');
	    	let rekap = data_result[2];
			$('#gudangecer'+gudang_id).find('[name=rekap_qty]').val(rekap);
			$('#gudangecer'+gudang_id).find('.yard_total').html(data_result[0]);
			$('#gudangecer'+gudang_id).find('.jumlah_roll_total').html('-');
			$('.event-gudangecer'+gudang_id).hide();

			$('.rekap-yard-eceran'+gudang_id).html(data_result[0]);
			$('.rekap-roll-eceran'+gudang_id).html(data_result[1]);
			parent.find('.loading').hide();
			parent.find('.done').show();
			count_gudang=0;
			isSaved = true;
			// window["count_gudang"+v] = 0;

		});

		
	});

});

function searchAndDrawTable(){

	$('#table-body input').attr('disabled', true);

	const data_st = {};
	const toko_id = $("#toko_id_select").val();
	const barang_id = $("#barang_id_select").val();
	const warna_id = $("#warna_id_select").val();
	const supplier_id = $("#supplier_id_select").val();

	const satuan_data = document.querySelector(`#barang_id_copy [value='${barang_id}']`).text.split("??");
	const nama_satuan_kecil = satuan_data[0];
	const nama_satuan_besar = satuan_data[1];


	const nama_barang = $("#barang_id_select [value='"+barang_id+"']").text();
	const nama_warna = $("#warna_id_select [value='"+warna_id+"']").text();
	const nama_lengkap = nama_barang+' '+nama_warna;
	$("#nama-barang").html(nama_lengkap);

	
	$('#toko-id-selected').html(toko_id);
	$('#barang-id-selected').html(barang_id);
	$('#warna-id-selected').html(warna_id);
	$('#supplier-id-selected').html(supplier_id);

	$(".nama-satuan-kecil").html(nama_satuan_kecil);
	$(".nama-satuan-besar").html(nama_satuan_besar);
	
	const url = "inventory/get_data_stok_opname_detail";
	data_st['stok_opname_id'] = stok_opname_id;
	data_st['toko_id'] = toko_id;
	data_st['barang_id'] = barang_id;
	data_st['warna_id'] = warna_id;
	data_st['supplier_id'] = supplier_id;
	data_st['tanggal'] = "<?=$tanggal_so?>";
	// console.log(data_st);
	const baris_gudang = [];
	const idx_gudang = [];
	const baris_gudangecer = [];
	const idx_gudangecer = [];
	$.each(gudang_id, function(k,v){
		baris_gudang[v] = '';
		idx_gudang[v] = 1;
	
		baris_gudangecer[v] = '';
		idx_gudangecer[v] = 1;
	});
	
	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		let res = JSON.parse(data_respond)
		$.each(res.result,function(k,v){
			baris_gudang[v.gudang_id] += `<tr>
				<td style='width:50px' class='text-center'>${idx_gudang[v.gudang_id]}</td>
				<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' value="${parseFloat(v.qty)}" tabindex="${(idx_gudang[v.gudang_id]*2)-1}" class='qty'></td>
				<td style='width:80px' class='text-center'><input name='roll' autocomplete='off' value="${v.jumlah_roll}" tabindex="${idx_gudang[v.gudang_id]*2}" class='jumlah-roll'></td>
				<td style='width:100px; padding-left:5px;' class='subtotal text-center'>${parseFloat(v.qty*v.jumlah_roll)}</td>
				<td hidden><span class='id'>${v.id}</span></td>
			</tr>`;
			idx_gudang[v.gudang_id]++;
		});

		$.each(res.resultEcer,function(k,v){
			baris_gudangecer[v.gudang_id] += `<tr>
				<td style='width:50px' class='text-center'>${idx_gudangecer[v.gudang_id]}</td>
				<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' value="${parseFloat(v.qty)}" tabindex="${(idx_gudangecer[v.gudang_id]*2)-1}" class='qty'></td>
				<td hidden style='width:100px; padding-left:5px;' class='subtotal text-center'>${parseFloat(v.qty*v.jumlah_roll)}</td>
				<td hidden><span class='id'>${v.id}</span></td>
			</tr>`;
			idx_gudangecer[v.gudang_id]++;
		});

		$.each(gudang_id, function(k,v){			
			for (var i = idx_gudang[v]; i < (idx_gudang[v]+100); i++) {
				baris_gudang[v] += `<tr>
					<td style='width:50px' class='text-center'>${i}</td>
					<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' tabindex="${(i*2)-1}" class='qty'></td>
					<td style='width:80px' class='text-center'><input name='roll' autocomplete='off' tabindex="${i*2}" class='jumlah-roll'></td>
					<td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
					<td hidden></td>
				</tr>`;
			};

			$('#gudang'+v).find('.table-body').html(baris_gudang[v]);
			tbl_body = $('#gudang'+v).find('.table-body');
	    	data_result = table_qty_update(tbl_body).split('=*=');
	    	let rekap = data_result[2];
			$('#gudang'+v).find('[name=rekap_qty]').val(rekap);
			$('#gudang'+v).find('.yard_total').html(data_result[0]);
			$('#gudang'+v).find('.jumlah_roll_total').html(data_result[1]);

			$('.rekap-yard-gudang'+v).html(data_result[0]);
			$('.rekap-roll-gudang'+v).html(data_result[1]);

			for (var i = idx_gudangecer[v]; i < (idx_gudangecer[v]+100); i++) {
				baris_gudangecer[v] += `<tr>
					<td style='width:50px' class='text-center'>${i}</td>
					<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' tabindex="${(i*2)-1}" class='qty'></td>
					<td hidden style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
					<td hidden></td>
				</tr>`;
			};

			$('#gudangecer'+v).find('.table-body').html(baris_gudangecer[v]);
			tbl_body = $('#gudangecer'+v).find('.table-body');
	    	data_result = table_qty_update(tbl_body).split('=*=');
	    	let rekapecer = data_result[2];
			$('#gudangecer'+v).find('[name=rekap_qty]').val(rekapecer);
			$('#gudangecer'+v).find('.yard_total').html(data_result[0]);
			$('#gudangecer'+v).find('.jumlah_roll_total').html('-');

			$('.rekap-yard-eceran'+v).html(data_result[0]);
			$('.rekap-roll-eceran'+v).html('-');
			
			
			$(".loading").hide();
			$(".done").hide();
			$('.event-gudangecer'+v).hide();
			$('.event-gudang'+v).hide();
			
		});



		$(".form-stok-detail [name=barang_id]").val(barang_id);
		$(".form-stok-detail [name=warna_id]").val(warna_id);
		$(".form-stok-detail [name=toko_id]").val(toko_id);

		if (status_aktif == 1) {
			<?//if (is_posisi_id() > 3) {?>
			// $('.table-body').find('.qty, .jumlah-roll').prop('disabled',true);
			<?//}?>
		};

		//===============================================
		for (let j = 0; j < res.data.length; j++) {
			let dt = res.data[j][0];
			console.log(dt);
			if (dt) {
				$(`.rekap-yard-before${dt.gudang_id}`).html(dt.qty);
				$(`.rekap-roll-before${dt.gudang_id}`).html(dt.jumlah_roll);
			}
			
		}

		//===============================================
		for (let j = 0; j < res.dataEcer.length; j++) {
			let dt = res.dataEcer[j][0];
			if (dt) {
				$(`.rekap-yard-eceranbefore${dt.gudang_id}`).html(Math.round(dt.qty*100)/100);
				$(`.rekap-roll-eceranbefore${dt.gudang_id}`).html('-');
			}
		}
	});

}

function table_qty_update(table){
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;
	
	$(table).find(".qty").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('.jumlah-roll').val();
		var id = ini.find('.id').html();
		if (typeof id === 'undefined') {
			id = '0';
		};
		
		if (typeof roll === 'undefined') {
			roll = '';
		}

		if (qty != '' && roll == '') {
			roll = 1;
		}else if(roll == 0){
			// alert('test');
			if (qty == '') {
				qty = 0;
			};
		}else if(qty == '' && roll == ''){
			roll = 0;
			qty = 0;
		};

		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		total_roll += parseFloat(roll);
		};

		// alert(subtotal);
		if (qty != '' && roll != '' && id != '') {
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
		}else if(id != 0){
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
			// alert(id);
		}
		idx++; 
		// alert(total_roll);
		total += subtotal;
		total = Math.round(total*100)/100;
		// console.log(Math.round(total,2));
		if (qty != '' && qty != 0 && roll != '') {
			ini.find('.subtotal').html(qty*roll);
		};

	});

	// $(table).find('.total-roll').html(total_roll);
	// $(table).find('.total-all').html(total);

	rekap_str = rekap.join('--');
	// console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("table-body");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 0; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
  	if (n == 1) {
  		x = rows[i].querySelector('.qty');
  		y = rows[i+1].querySelector('.qty');	
  	}else if (n == 2) {
  		x = rows[i].querySelector('.jumlah-roll');
  		y = rows[i+1].querySelector('.jumlah-roll');	
  	}else{
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
  	}
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
      	if (n == 1 || n == 2) {
      		if (Number(x.value) > Number(y.value) && y.value != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	}else{
	        if (Number(x.innerHTML) > Number(y.innerHTML) && x.innerHTML!= '' && y.innerHTML != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	};
      } else if (dir == "desc") {
      	if (n == 1 || n == 2) {
      		if (Number(x.value) < Number(y.value) && y.value != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	}else{
	        if (Number(x.innerHTML) < Number(y.innerHTML) && x.innerHTML!= '' && y.innerHTML != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	}
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function ajax_form(url,form){
    var hasil = "";
    return $.ajax({
        type:"POST",
        url:baseurl+url,
        data:$(form).serialize(),
        async:true,
        success: function(data)
        {
            hasil = data;
        }
    });
    return hasil;
}

function lockSO(id){
	let data_st = {};
	data_st['id'] = id;
	const url = 'inventory/stok_opname_lock';
	let text = '';
	if (isSaved == true) {
		text = "Yakin untuk lock stok opname ini ?";
	}else{
		text = `Table belum di save, yakin lock ?`;	
	};
	bootbox.confirm(text, function(respond){
		if (respond) {
			ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
				// console.log(textStatus, jqXHR);
				if (textStatus == 'success') {
					window.location.reload();
				};
			});
			
		};
	});
}
</script>
