<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table tr td{
	color:#000;
}

.batal{
	background: #ccc;
}

#stokList, #selectedList,
#stokListEdit, #selectedListEdit{
	height:150px;
	overflow:auto;
}

#stokList table tr td,
#selectedList table tr td,
#stokListEdit table tr td,
#selectedListEdit table tr td{
	border:1px solid #ddd;
	font-size:14px;
	padding:2px 5px;
}

#stokList table tr:hover td,
#selectedList table tr:hover td,
#stokListEdit table tr:hover td,
#selectedListEdit table tr:hover td{
	background:#eee;
	cursor:pointer;
}

#stokList .taken,
#stokListEdit .taken{
	text-decoration:line-through;
}

#table-stok-recap tr th,
#table-stok-recap tr td,
#table-selected-recap tr th,
#table-selected-recap tr td,
#table-stok-recap-edit tr th,
#table-stok-recap-edit tr td,
#table-selected-recap-edit tr th,
#table-selected-recap-edit tr td{
	border:1px solid #ddd;
	padding:2px 5px;
	font-size:14px;
	text-align:center
}

.eceran{
	display: none;
}

#qty-table-eceran-edit tbody tr td{
	border:1px solid #ddd;
	text-align: center;
}

#qty-table-eceran-edit tr th{
	width: 50px;
	padding: 0 10px;
}

#qty-table-eceran-edit tbody tr:hover{
	cursor:pointer
}

#qty-table-eceran-edit .active td{
	background:yellow;
}

#qty-table-eceran-edit input{
	border:none;
	width:50px;
	text-align: center;
	background-color: #eee;
}



</style>

<div class="page-content">
	<div class='container'>


		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class="col-xs-6 " style="border-right:1px solid #ddd">
							<form action="<?=base_url('inventory/mutasi_barang_insert')?>" class="form-horizontal" id="form_add_data" method="post">
								<h3 class='block'> Mutasi Barang</h3>
	
								<div class="form-group">
									<label class="control-label col-md-4">Toko<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select class='input1 form-control' style='font-weight:bold' name="toko_id" id="toko_id_select">
											<?foreach ($this->toko_list_aktif as $row) { ?>
												<option <?=($row->id == 1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
											<? } ?>
										</select>
									</div>
								</div>		
								
								<div class="form-group">
									<label class="control-label col-md-4">Lokasi Sebelum<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select class='input1 form-control' style='font-weight:bold' name="gudang_id_before">
											<?foreach ($this->gudang_list_aktif as $row) { ?>
												<option <?=($row->id == 1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
											<? } ?>
										</select>
									</div>
								</div>			                
	
								<div class="form-group">
									<label class="control-label col-md-4">Lokasi Setelah<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select style='font-weight:bold' class='form-control' name="gudang_id_after">
											<?foreach ($this->gudang_list_aktif as $row) { ?>
												<option <?=($row->id == 2 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
											<? } ?>
										</select>
									</div>
								</div> 
	
								<div class="form-group">
									<label class="control-label col-md-4">Tanggal<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
									</div>
								</div> 	
	
								<div class="form-group">
									<label class="control-label col-md-4">Barang<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select class='form-control' name="sku_id" id='sku_id_select'>
											<option value=''>Pilih</option>
											<?foreach ($this->barang_sku_aktif as $row) { ?>
												<option value="<?=$row->id?>"><?=$row->nama_barang;?></option>
											<? } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4">Eceran<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<label>
											<input type="checkbox" name="isEceran" onchange="toggleEceran()" id="eceranToggler" value="1" class="form-control"> Ya</label>
									</div>
								</div>
	
								<div class="form-group" hidden>
									<label class="control-label col-md-4">Qty
									</label>
									<div class="col-md-6">
										<input readonly type="text" class='form-control' id="qtyNew" name="qty"/>
										STOK : <b><span id='data-qty-add'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-qty' title="Qty" data-html="true">
										</a>-->
									</div>
								</div> 
	
								<div class="form-group" hidden>
									<label class="control-label col-md-4">Jumlah Roll
									</label>
									<div class="col-md-6">
										<input readonly type="text" class='form-control'  id="rollNew" name="jumlah_roll"/>
										STOK : <b><span id='data-roll-add'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-roll' title="Jumlah Roll" data-html="true">
										</a>-->
									</div>
								</div>

								<input name='rekap_qty' id="rekapQtyNew" <?=(is_posisi_id()!=1 ? 'hidden' : '');?> >
							</form>
						</div>
						<div class="col-xs-3 non-eceran" style="min-height:100%;border-right:1px solid #ddd">
							<h3 class='block'> Stok Barang</h3>
							<div id="stokList">

							</div>
							<hr style="margin:5px 0 10px 0;"/>
							<table id="table-stok-recap">
								<thead>
									<tr>
										<th>STOK</th>
										<th>QTY</th>
									</tr>
								<thead>
								<tbody>
									<tr>
										<td><span id='nama-stok-satuan'></span></td>
										<td id="qty-stok-satuan"></td>
									</tr>
									<tr>
										<td><span id='nama-stok-packaging'></span></td>
										<td id="qty-stok-packaging"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-3 non-eceran">
							<h3 class='block'> Selected Barang</h3>
							<div id="selectedList">

							</div>
							<hr style="margin:5px 0 10px 0;"/>
							<table id="table-selected-recap">
								<thead>
									<tr>
										<th>AMBIL</th>
										<th>QTY</th>
									</tr>
								<thead>
								<tbody>
									<tr>
										<td><span id='nama-selected-satuan'></span></td>
										<td id="qty-selected-satuan"></td>
									</tr>
									<tr>
										<td><span id='nama-selected-packaging'></span></td>
										<td id="qty-selected-packaging"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-3 eceran">
							<h3 class='block'> STOK</h3>
							<div id="stokEceran">
								<table id='qty-table-eceran-edit'>
									<thead>
										<tr>
											<th class='text-center'>Qty</th>
											<th class='text-center'>Ambil</th>
											<th class='text-center'>Sisa</th>
											<th class='text-center'>Suppl</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot style='font-size:1.2em'>
										<tr>
											<th style='width:45px'>TOTAL</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button disabled type="button" class="btn blue btn-active btn-save non-eceran">Save</button>
						<button disabled type="button" class="btn yellow-gold btn-active btn-save-eceran eceran">Save</button>
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
						<div class="col-xs-6 " style="border-right:1px solid #ddd">
							<form action="<?=base_url('inventory/mutasi_barang_update')?>" class="form-horizontal" id="form_edit_data" method="post">
								<h3 class='block'> Mutasi Barang</h3>
	
								<div class="form-group">
									<label class="control-label col-md-4">Toko<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select disabled class='input1 form-control' style='font-weight:bold' name="toko_id" id="toko_id_select_edit">
											<?foreach ($this->toko_list_aktif as $row) { ?>
												<option <?=($row->id == 1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
											<? } ?>
										</select>
									</div>
								</div>		
								
								<div class="form-group">
									<label class="control-label col-md-4">Lokasi Sebelum<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select disabled class='input1 form-control' style='font-weight:bold' name="gudang_id_before">
											<?foreach ($this->gudang_list_aktif as $row) { ?>
												<option <?=($row->id == 1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
											<? } ?>
										</select>
									</div>
								</div>			                
	
								<div class="form-group">
									<label class="control-label col-md-4">Lokasi Setelah<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select disabled style='font-weight:bold' class='form-control' name="gudang_id_after">
											<?foreach ($this->gudang_list_aktif as $row) { ?>
												<option <?=($row->id == 2 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
											<? } ?>
										</select>
									</div>
								</div> 
	
								<div class="form-group">
									<label class="control-label col-md-4">Tanggal<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<input disabled type="text" readonly class="form-control" value="<?=date('d/m/Y');?>" name="tanggal"/>
									</div>
								</div> 	
	
								<div class="form-group">
									<label class="control-label col-md-4">Barang<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select disabled class='form-control' name="sku_id" id='sku_id_select_edit'>
											<option value=''>Pilih</option>
											<?foreach ($this->barang_sku_aktif as $row) { ?>
												<option value="<?=$row->id?>"><?=$row->nama_barang;?></option>
											<? } ?>
										</select>
									</div>
								</div>
	
								<div class="form-group" hidden>
									<label class="control-label col-md-4">Qty
									</label>
									<div class="col-md-6">
										<input readonly type="text" class='form-control' id="qtyEdit" name="qty"/>
										STOK : <b><span id='data-qty-add'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-qty' title="Qty" data-html="true">
										</a>-->
									</div>
								</div> 
	
								<div class="form-group" hidden>
									<label class="control-label col-md-4">Jumlah Roll
									</label>
									<div class="col-md-6">
										<input readonly type="text" class='form-control'  id="rollEdit" name="jumlah_roll"/>
										STOK : <b><span id='data-roll-add'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-roll' title="Jumlah Roll" data-html="true">
										</a>-->
									</div>
								</div>

								<input name='mutasi_barang_id' <?=(is_posisi_id()!=1 ? 'hidden' : '');?> >
								<input name='rekap_qty' id="rekapQtyEdit" <?=(is_posisi_id()!=1 ? 'hidden' : '');?> >
							</form>
						</div>
						<div class="col-xs-3" style="min-height:100%;border-right:1px solid #ddd">
							<h3 class='block'> Stok Barang</h3>
							<div id="stokListEdit">

							</div>
							<hr style="margin:5px 0 10px 0;"/>
							<table id="table-stok-recap">
								<thead>
									<tr>
										<th>STOK</th>
										<th>QTY</th>
									</tr>
								<thead>
								<tbody>
									<tr>
										<td><span id='nama-stok-satuan-edit'></span></td>
										<td id="qty-stok-satuan-edit"></td>
									</tr>
									<tr>
										<td><span id='nama-stok-packaging-edit'></span></td>
										<td id="qty-stok-packaging-edit"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-3">
							<h3 class='block'> Selected Barang</h3>
							<div id="selectedListEdit">

							</div>
							<hr style="margin:5px 0 10px 0;"/>
							<table id="table-selected-recap-edit">
								<thead>
									<tr>
										<th>AMBIL</th>
										<th>QTY</th>
									</tr>
								<thead>
								<tbody>
									<tr>
										<td><span id='nama-selected-satuan-edit'></span></td>
										<td id="qty-selected-satuan-edit"></td>
									</tr>
									<tr>
										<td><span id='nama-selected-packaging-edit'></span></td>
										<td id="qty-selected-packaging-edit"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="modal-footer">
						<button disabled type="button" class="btn blue btn-active btn-edit-save">Save</button>
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
							<select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<?if (is_posisi_id() != 6) { ?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Mutasi Barang Baru </a>
							<?}?>
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<table>
											<tr>
												<td>
													<table>
														<tr>
															<td>Periode</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<b>
																	<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
																	s/d
																	<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
																	<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
																</b>
															</td>
														</tr>
														<tr>
															<td>Barang</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<b>
																	<select name='barang_id' id='barang_select' style="width:100%;">
																		<option <?=($barang_id == 0 ? 'selected' : '');?> value='0'>Semua</option>
																		<?foreach ($this->barang_list_aktif as $row) { ?>
																			<option <?=($barang_id == $row->id ? 'selected' : '');?> value='<?=$row->id?>'><?=$row->nama_jual;?></option>
																		<?}?>
																	</select>
																</b>
															</td>
														</tr>
														<tr>
															<td>Warna</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<b>
																	<select name='warna_id' id='warna_select' style="width:100%;">
																		<option <?=($warna_id == 0 ? 'selected' : '');?> value='0'>Semua</option>
																		<?foreach ($this->warna_list_aktif as $row) { ?>
																			<option <?=($warna_id == $row->id ? 'selected' : '');?> value='<?=$row->id?>'><?=$row->warna_beli;?></option>
																		<?}?>
																	</select>
																</b>
															</td>
														</tr>
													</table>
												</td>
												<td>
													<?if (date('Y-m-d') < '2018-03-07' && $cond == '') { ?>

														<div id='info-section' class='alert alert-info' style='position:absolute; margin:10px; top:75px; '>
																<i style='font-weight:bold' class='fa fa-arrow-left'></i> User dapat memilih hanya nama barang saja atau nama warna saja <br/>
																Tanggal <i>default</i> yang dipilih adalah periode 1 minggu
														</div>
													<?}?>
												</td>
											</tr>
										</table>
										
									</form>
								</td>
								<td class='text-right'>
									<a href="<?=base_url().'inventory/mutasi_barang_excel?tanggal_start='.is_date_formatter($tanggal_start).'&tanggal_end='.is_date_formatter($tanggal_end).'&barang_id='.$barang_id.'&warna_id='.$warna_id;?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
								</td>
							</tr>
									
						</table>

						<div class="tabbable tabs-left" style='margin-bottom:5px'>
                            <ul class="nav nav-tabs" style='padding:0px; margin:10px 0px;'>
                                <li id="mutasi1" class='active navi-tab' onclick="setViewTab(1)">
                                    <a href="#" class="" >
										MUTASI BARANG
                                    </a> 
                                </li>
                                <li id="mutasi2" class="navi-tab" onclick="setViewTab(2)">
                                    <a href="#">
                                        MUTASI ECERAN
                                    </a> 
                                </li>
                            </ul>
                        </div>
						<hr/>
						<!-- table-striped table-bordered  -->
						<div id="viewTab1">
							<table class="table table-hover table-bordered" id="general_table">
								<thead>
									<tr>
										<th scope="col" class='status_column'>
											Status Aktif
										</th>
										<th scope="col" style='width:150px;'>
											Tanggal
										</th>
										<th scope="col" style='width:150px;'>
											Nama
										</th>
										<th scope="col">
											Lokasi Sebelum
										</th>
										<th scope="col">
											Lokasi Setelah
										</th>
										<th scope="col">
											Qty
										</th>
										<th scope="col">
											Jumlah Roll
										</th>
										<th scope="col">
											Action 
										</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div id="viewTab2">
							<table class="table table-hover table-bordered" id="tableEceran">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Barang</th>
										<th>Gudang Awal</th>
										<th>Gudang Akhir</th>
										<th>QTY</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody></tbody>

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script>

const qtyDetailNew = [];
let qtyStokNew=0;
let rollStokNew=0;

let qtySelectedNew = 0;
let rollSelectedNew = 0;


let qtyDetailEdit = [];
let qtyStokEdit=0;
let rollStokedit=0;

let qtySelectedEdit = 0;
let rollSelectedEdit = 0;

jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	$('[data-toggle="popover"]').popover();

	setTimeout(function(){
		$('#info-section').toggle('slow');
	},7000);


	$('#sku_id_select, #warna_id_select,#sku_id_select2, #warna_id_select2, #barang_select, #warna_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    var qty_global = 0;
    var jumlah_roll_global = 0;

	var idx = 1;
	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            
            var status_aktif = $('td:eq(0)', nRow).text();
            var data = $('td:eq(7)', nRow).text().split('??');
            var id = data[0];
            var gudang_id_before = data[1];
            var gudang_id_after = data[2];
            var barang_id = data[3];
            var warna_id = data[4];
            var sku_id = data[6];
            var toko_id = data[7];
			
            const qty_data = data[5].split("==");
			let qty_detail = [];
			qty_data.forEach((brs,idx) => {
				const x = brs.split('|');
				if (x[0] != '-') {
					qty_detail.push({
						detail_id:x[0],
						qty:parseFloat(x[1]),
						supplier_id:x[2],
						supplier_name:x[3],
						stok_index:idx
					});
				}
			});
            
			let btn_status = "";
            <?if (is_posisi_id() <= 2 || is_posisi_id() == 9 ) { ?>
            	var btn_edit = "<a href='#portlet-config-edit' data-toggle='modal' class='btn btn-xs blue btn-edit'><i class='fa fa-edit'></i></a>";
            	if (status_aktif == 1) {
	            	btn_status =`<button onclick="mutasi_batal('${id}',${status_aktif})" class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></button>`;	
            	}else{
	            	// var btn_status ="<a href='"+baseurl+"inventory/mutasi_barang_batal/"+id+"/"+status_aktif+"' class='btn btn-xs blue btn-remove'><i class='fa fa-play'></i></a>";
            	};
            <?}else{?>
            	var btn_edit = "";
        	<?}?>

            var action = `<span class='id' hidden>${id}</span>
            			<span class='gudang_id_before' hidden>${gudang_id_before}</span>
            			<span class='gudang_id_after' hidden>${gudang_id_after}</span>
            			<span class='barang_id' hidden>${barang_id}</span>
            			<span class='warna_id' hidden>${warna_id}</span>
            			<span class='sku_id' hidden>${sku_id}</span>
            			<span class='toko_id' hidden>${toko_id}</span>
						<span class='rekap_qty' hidden>${JSON.stringify(qty_detail)}</span>
						${btn_edit} 
						${btn_status}`;


			$('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html("<span class='tanggal' hidden>"+$('td:eq(1)', nRow).text()+"</span>"+date_formatter_month_name($('td:eq(1)', nRow).text()));
            $('td:eq(5)', nRow).html("<span class='qty'>"+$('td:eq(5)', nRow).text().replace('.00','')+"</span>");
            $('td:eq(6)', nRow).html("<span class='jumlah_roll'>"+$('td:eq(6)', nRow).text()+"</span>");
            $('td:eq(7)', nRow).html(action);
            // $('td:eq(2)', nRow).html(btn_view);


        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"ordering":false,
		"sAjaxSource": baseurl + `inventory/data_mutasi?cond=`+"<?=$cond?>"
	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

	$('#status_aktif_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});
	

//========================================add data=================================================

	$('#form_add_data [name=gudang_id_before]').change(function(){
		var gudang_before = $(this).val();
		var gudang_after = $('#form_add_data [name=gudang_id_after]').val();
		if (gudang_before ==  gudang_after) {
			if (gudang_before > 1) {
				gudang_after = 1;
			}else{
				gudang_after = 2;
			};
			$('#form_add_data [name=gudang_id_after]').val(gudang_after);
		}
	});

	$('#form_add_data [name=gudang_id_after]').change(function(){
		var gudang_after = $(this).val();
		var gudang_before = $('#form_add_data [name=gudang_id_before]').val();
		if (gudang_before ==  gudang_after) {
			if (gudang_after > 1) {
				gudang_before = 1;
			}else{
				gudang_before = 2;
			};
			$('#form_add_data [name=gudang_id_before]').val(gudang_before);
		}
	});

	$('#sku_id_select, #toko_id_select, #form_add_data [name=gudang_id_before],#form_add_data [name=gudang_id_after], #eceranToggler').change(function(){
		$('#data-qty').attr('data-content','');
		$('#data-roll').attr('data-content','');



		if ($('#sku_id_select').val() != '' ) {

			if ($('#form_add_data [name=barang_id]').val() != '' && $('#form_add_data [name=warna_id]').val() != '') {
				$('#form_add_data [name=qty]').attr('placeholder','loading...');
				$('#form_add_data [name=jumlah_roll]').attr('placeholder','loading...');

				if ($("#eceranToggler").is(':checked')) {
					let table_eceran = '';
					let qty_eceran = 0;

					var data = {};
					data['toko_id'] = $('#form_add_data [name=toko_id]').val();
					data['sku_id'] = $('#sku_id_select').val();
					data['gudang_id'] = $('#form_add_data [name=gudang_id_before]').val();
					data['tanggal'] = $('#form_add_data [name=tanggal]').val();
					var url = 'inventory/get_stok_eceran';
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						const v = JSON.parse(data_respond);
						for (let i = 0; i < v.length; i++) {
							if (v[i].qty > 0) {
								qty_eceran += parseFloat(v[i].qty);
								table_eceran += `<tr style="font-size:1.2em;" data-id="${v[i].stok_eceran_qty_id}" data-qty="${parseFloat(v[i].qty)}" data-supplier="${v[i].supplier_id}">
									<td style='padding:2px 10px'>${parseFloat(v[i].qty)}</td>
									<td style='padding:2px 10px'><input class="qty-input-ecer" onkeyup="ambilEceran('${i}')" ></td>
									<td class='eceran-sisa' style='padding:2px 10px'></td>
									<td >
										<span class='nama_supplier'> ${(v[i].nama_supplier != null ? v[i].nama_supplier : 'none') }</span>
										<span hidden class='supplier_id'>${v[i].supplier_id}</span> 
									</td>
									<td hidden><span class='penjualan_qty_detail_id'>${v[i].penjualan_qty_detail_id}</span></td>
									</tr>`;
							}
						}

						if (qty_eceran == 0) {
							table_eceran = "no stok";
						}
						$("#qty-table-eceran-edit tbody").html(table_eceran);
						// $("#stok-eceran-edit").find(".stok-qty-eceran").text(parseFloat(qty_eceran));
					});

				}else{
					var data = {};
					data['toko_id'] = $('#form_add_data [name=toko_id]').val();
					data['sku_id'] = $('#sku_id_select').val();
					data['gudang_id'] = $('#form_add_data [name=gudang_id_before]').val();
					data['tanggal'] = $('#form_add_data [name=tanggal]').val();
					var url = 'inventory/cek_barang_qty';
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						// alert(data_respond)
						
						let tbl_body = ``;
						const qtyList = [];
						const supplierList = [];
						const rollList = [];
						let totalQty = 0;
						let totalRoll = 0;
						let barang_data = {};
						let nama_satuan = '';
						let nama_packaging = '';
	
						$.each(JSON.parse(data_respond),function(kx,vx){
							if (kx==0) {
								for (let y = 0; y < vx.length; y++) {
									if (vx[y].jumlah_roll > 0) {
										totalRoll+=parseFloat(vx[y].jumlah_roll);
										for (let x = 0; x < vx[y].jumlah_roll; x++) {
											// console.log(totalQty, vx[y].qty);
											qtyList.push(vx[y].qty);
											supplierList.push([vx[y].supplier_id,vx[y].nama_supplier]);
											totalQty+=parseFloat(vx[y].qty);
											
										}
									}
									
								}
								
							}else if(kx==2){
								barang_data=vx.barang_data[0];
								nama_satuan = barang_data.nama_satuan;
								nama_packaging = barang_data.nama_packaging;
							}
						});
	
						if (totalQty > 0) {
							for (let x = 0; x < qtyList.length; x++) {
								tbl_body += `<tr data-taken="1">
									<td class='qty-info'>${qtyList[x]}</td>
									<td class='supplier_id' hidden>${supplierList[x][0]}</td>
									<td class='supplier-info'>${supplierList[x][1]}</td>
								</tr>`;
							}
						} else {
							
						}
	
						const res = `<table>${tbl_body}</table>`;
						$("#nama-stok-satuan").html(nama_satuan);
						$("#nama-stok-packaging").html(nama_packaging);
						
						$("#nama-selected-satuan").html(nama_satuan);
						$("#nama-selected-packaging").html(nama_packaging);
	
						$("#qty-stok-satuan").html(totalQty);
						$("#qty-stok-packaging").html(totalRoll);
						qtyStokNew = totalQty;
						rollStokNew = totalRoll;
	
	
						$("#stokList").html(res);
					   });
				}

			};
		}else{
			$('#form_add_data [name=qty]').attr('readonly',true);
			$('#form_add_data [name=jumlah_roll]').attr('readonly',true);
		}
	});

	$('#form_add_data [name=qty]').change(function(){
		var qty = parseInt($(this).val());
		var jumlah_roll = parseInt($('#form_add_data [name=jumlah_roll]').val());
		if (qty > qty_global) {
			notific8('ruby', "Kuantiti melebihi stok");
		}else{
			if (jumlah_roll <= jumlah_roll_global) {
				$('.btn-save').attr('disabled',false);
			}else{
				$('.btn-save').attr('disabled',true);
			}
		}
	});

	$('#form_add_data').on('input','[name=jumlah_roll]',function(){
		var jumlah_roll = parseInt($(this).val());
		var qty = parseInt($('#form_add_data [name=qty]').val());
		if (jumlah_roll > jumlah_roll_global) {
			notific8('ruby', "Jumlah Roll melebihi stok");
		}else{
			if (qty <= qty_global) {
				$('.btn-save').attr('disabled',false);
			}else{
				$('.btn-save').attr('disabled',true);
			}
		}
	});

	$('.btn-save').click(function(){
		$('#form_add_data').submit();
		btn_disabled_load($(this));
		// if($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=qty]').val() != '' && $('#form_add_data [name=qty]').val() != 0 && $('#form_add_data [name=jumlah_roll]').val() != '' && $('#form_add_data [name=jumlah_roll]').val() != 0){
		// }else{
		// 	bootbox.alert("Mohon isi tanggal & jumlah ");
		// }
	});

	$('.btn-save-eceran').click(function(){
		$('#form_add_data').submit();
		btn_disabled_load($(this));
	});

//========================================edit data=================================================

	$('#general_table').on('click','.btn-edit',function(){

		$('#data-qty-edit').attr('data-content','');
		$('#data-roll-edit').attr('data-content','');

		var ini = $(this).closest('tr');
		var form = $('#form_edit_data');

		form.find('[name=mutasi_barang_id]').val(ini.find('.id').html()).change();
		form.find('[name=toko_id]').val(ini.find('.toko_id').html());
		form.find('[name=gudang_id_before]').val(ini.find('.gudang_id_before').html());
		form.find('[name=gudang_id_after]').val(ini.find('.gudang_id_after').html());
		const rekapQty = ini.find('.rekap_qty').html();
		form.find('#rekapQtyEdit').val(rekapQty);
		
		var sku_id = ini.find('.sku_id').html();
		$("#sku_id_select_edit").val(sku_id).trigger('change');
		
		var qty_now = ini.find('.qty').html();
		var jml_roll_now = ini.find('.jumlah_roll').html();
		qtySelectedEdit =qty_now;
		rollSelectedEdit = jml_roll_now;
		form.find('[name=qty]').val(ini.find('.qty').html());
		form.find('[name=jumlah_roll]').val(ini.find('.jumlah_roll').html());

		$('#form_edit_data [name=qty]').attr('placeholder','loading...');
		$('#form_edit_data [name=jumlah_roll]').attr('placeholder','loading...');

		var data = {};
		data['toko_id'] = $('#form_edit_data [name=toko_id]').val();
		data['sku_id'] = $('#sku_id_select_edit').val();
		data['gudang_id'] = $('#form_edit_data [name=gudang_id_before]').val();
		data['tanggal'] = $('#form_edit_data [name=tanggal]').val();
		var url = 'inventory/cek_barang_qty';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			
			let tbl_body = ``;
			const qtyList = [];
			const supplierList = [];
			const rollList = [];
			let totalQty = 0;
			let totalRoll = 0;
			let barang_data = {};
			let nama_satuan = '';
			let nama_packaging = '';

			$.each(JSON.parse(data_respond),function(kx,vx){
				if (kx==0) {
					for (let y = 0; y < vx.length; y++) {
						if (vx[y].jumlah_roll > 0) {
							totalRoll+=parseFloat(vx[y].jumlah_roll);
							for (let x = 0; x < vx[y].jumlah_roll; x++) {
								// console.log(totalQty, vx[y].qty);
								qtyList.push(vx[y].qty);
								supplierList.push([vx[y].supplier_id,vx[y].nama_supplier]);
								totalQty+=parseFloat(vx[y].qty);
								
							}
						}
						
					}
					
				}else if(kx==2){
					barang_data=vx.barang_data[0];
					nama_satuan = barang_data.nama_satuan;
					nama_packaging = barang_data.nama_packaging;
				}
			});
			
			const selected = JSON.parse(rekapQty);
			qtyDetailEdit=JSON.parse(rekapQty);
			selected.forEach(list => {
				tbl_body += `<tr data-taken="0" class="taken" style="background-color:lightyellow">
						<td class='qty-info'>${list.qty}</td>
						<td class='supplier_id' hidden>${list.supplier_id}</td>
						<td class='supplier-info'>${list.supplier_name}</td>
						<td class='id_detail' hidden>${list.detail_id}</td>
					</tr>`
			});
			if (totalQty > 0) {
				for (let x = 0; x < qtyList.length; x++) {
					tbl_body += `<tr data-taken="1">
						<td class='qty-info'>${qtyList[x]}</td>
						<td class='supplier_id' hidden>${supplierList[x][0]}</td>
						<td class='supplier-info'>${supplierList[x][1]}</td>
						<td class='id_detail' hidden>0</td>
					</tr>`;
				}
			} else {
				
			}

			const res = `<table>${tbl_body}</table>`;
			$("#nama-stok-satuan-edit").html(nama_satuan);
			$("#nama-stok-packaging-edit").html(nama_packaging);
			
			$("#nama-selected-satuan-edit").html(nama_satuan);
			$("#nama-selected-packaging-edit").html(nama_packaging);

			$("#qty-stok-satuan-edit").html(totalQty);
			$("#qty-stok-packaging-edit").html(totalRoll);
			qtyStokEdit = totalQty;
			rollStokEdit = totalRoll;

			$("#stokListEdit").html(res);
			drawSelectedEdit();
   		});
		
	});

	$('#form_edit_data [name=qty]').change(function(){
		// alert(jumlah_roll_global);
		var qty = parseInt($(this).val());
		var jumlah_roll = parseInt($('#form_edit_data [name=jumlah_roll]').val());
		if (qty > qty_global) {
			notific8('ruby', "Kuantiti melebihi stok");
			$('.btn-edit-save').attr('disabled',true);
		}else{
			if (jumlah_roll <= jumlah_roll_global) {
				$('.btn-edit-save').attr('disabled',false);
			}else{
				$('.btn-edit-save').attr('disabled',true);
			}
		}
	});

	$('#form_edit_data [name=jumlah_roll]').change(function(){
		var jumlah_roll = parseInt($(this).val());
		var qty = parseInt($('#form_edit_data [name=qty]').val());
		if (jumlah_roll > jumlah_roll_global) {
			notific8('ruby', "Jumlah Roll melebihi stok");
			$('.btn-edit-save').attr('disabled',true);
		}else{
			if (qty <= qty_global) {
				$('.btn-edit-save').attr('disabled',false);
			}else{
				$('.btn-edit-save').attr('disabled',true);
			}
		}
	});

	$('.btn-edit-save').click(function(){
		$('#form_edit_data').submit();
		// if($('#form_edit_data [name=tanggal]').val() != '' && $('#form_edit_data [name=qty]').val() != '' && $('#form_edit_data [name=qty]').val() != 0 && $('#form_edit_data [name=jumlah_roll]').val() != '' && $('#form_edit_data [name=jumlah_roll]').val() != 0){
		// }else{
		// 	bootbox.alert("Mohon isi tanggal & jumlah ");
		// }
	});

//========================================set data=================================================
	<?if ($barang_id_latest != '') {?>
		// alert("<?=$barang_id_latest?>");
		$("#portlet-config").modal('toggle');
		$('#barang_id_select').select2('val','<?=$barang_id_latest;?>');
		var gudang_before = "<?=$gudang_before_latest?>";
		$('#form_add_data [name=gudang_id_before]').val(gudang_before);
		if (gudang_before > 1) {
			gudang_after = 1;
		}else{
			gudang_after = 2;
		};
		$('#form_add_data [name=gudang_id_after]').val(gudang_after);

	<?};?>

//========================================set qty=================================================

		$("#stokList").on("click", "tr", function(){
			const ini = $(this);
			const taken_status = parseFloat(ini.attr("data-taken"));
			const index = ini.index();
			
			const q = ini.find(".qty-info").html();
			const sN = ini.find(".supplier-info").html();
			const sId = ini.find(".supplier_id").html();

			if (taken_status) {
				ini.addClass('taken');
				ini.attr("data-taken","0");
				qtyStokNew -= q;
				rollStokNew -= 1;

				qtySelectedNew += parseFloat(q);
				rollSelectedNew += parseFloat(1);
				
				qtyDetailNew.push({
					qty:q,
					supplier_name:sN,
					supplier_id:sId,
					stok_index:index
				});
			}else{
				ini.removeClass('taken');
				ini.attr("data-taken","1");

				qtyStokNew += parseFloat(q);
				rollStokNew += parseFloat(1);

				qtySelectedNew -= parseFloat(q);
				rollSelectedNew -= parseFloat(1);

				let indexSelected = null;
				qtyDetailNew.forEach((item, idx) => {
					if (item.stok_index == index) {
						indexSelected = idx;
					}
				});

				if (indexSelected !== null) {
					qtyDetailNew.splice(indexSelected,1);
				}
				
			}

			drawSelected();

		})

		$("#selectedList").on("click", "tr", function(){
			const ini = $(this);
			const index = ini.index();

			const sel = qtyDetailNew[index];

			qtyStokNew += parseFloat(sel.qty);
			rollStokNew += parseFloat(1);

			qtySelectedNew -= parseFloat(sel.qty);
			qtyDetailNew.splice(index,1);

			const dt = $("#stokList tr")[sel.stok_index];	
			
			dt.setAttribute('data-taken','1');
			dt.classList.remove('taken');
			
			rollSelectedNew -= parseFloat(1);
			drawSelected();

		})

//========================================set qty=================================================

		$("#stokListEdit").on("click", "tr", function(){
			const ini = $(this);
			const taken_status = parseFloat(ini.attr("data-taken"));
			const index = ini.index();
			
			const q = ini.find(".qty-info").html();
			const sN = ini.find(".supplier-info").html();
			const sId = ini.find(".supplier_id").html();
			const dId = ini.find(".id_detail").html();
			let getIdx = qtyDetailEdit.length;
			if (dId != 0) {
				qtyDetailEdit.forEach((detail,idx) => {
					console.log(detail.detail_id, dId);
					if (detail.detail_id==dId) {
						getIdx = idx;
					}
				});
			}

			if (taken_status) {
				ini.addClass('taken');
				ini.attr("data-taken","0");
				qtyStokEdit -= q;
				rollStokEdit -= 1;

				qtySelectedEdit += parseFloat(q);
				rollSelectedEdit += parseFloat(1);
				if (dId == 0) {
					qtyDetailEdit[getIdx] = {};
				}

				console.log(qtyDetailEdit[getIdx]);
				
				qtyDetailEdit[getIdx].detail_id=dId;
				qtyDetailEdit[getIdx].qty=q;
				qtyDetailEdit[getIdx].supplier_name=sN;
				qtyDetailEdit[getIdx].supplier_id=sId;
				qtyDetailEdit[getIdx].stok_index=index;
			}else{
				ini.removeClass('taken');
				ini.attr("data-taken","1");

				qtyStokEdit += parseFloat(q);
				rollStokEdit += parseFloat(1);

				qtySelectedEdit -= parseFloat(q);
				rollSelectedEdit -= parseFloat(1);

				let indexSelected = null;
				qtyDetailEdit.forEach((item, idx) => {
					if (item.stok_index == index) {
						indexSelected = idx;
					}
				});

				if (dId != 0) {
					qtyDetailEdit[getIdx].detail_id=dId;
					qtyDetailEdit[getIdx].qty=null;
					qtyDetailEdit[getIdx].supplier_name=null;
					qtyDetailEdit[getIdx].supplier_id=null;
					qtyDetailEdit[getIdx].stok_index=null;
				}else if(indexSelected !== null) {
					qtyDetailEdit.splice(indexSelected,1);
				}
				
			}

			drawSelectedEdit();

		})

		$("#selectedListEdit").on("click", "tr", function(){
			const ini = $(this);
			const index = ini.index();
			const sel = qtyDetailEdit[index];

			qtyStokEdit += parseFloat(sel.qty);
			rollStokEdit += parseFloat(1);


			qtySelectedEdit -= parseFloat(sel.qty);
			const dt = $("#stokListEdit tr")[sel.stok_index];

			if (sel.detail_id != 0) {
				sel.qty=null;
				sel.supplier_name=null;
				sel.supplier_id=null;
				sel.stok_index=null;
			}else if(indexSelected !== null) {
				qtyDetailEdit.splice(indexSelected,1);
			}

			
			dt.setAttribute('data-taken','1');
			dt.classList.remove('taken');
			
			rollSelectedEdit -= parseFloat(1);
			drawSelectedEdit();

		});

		get_mutasi_barang_eceran()

});

function drawSelected(){
	let tbody = '';
	$(".btn-save").prop('disabled',true);


	$("#rekapQtyNew").val(JSON.stringify(qtyDetailNew))
	qtyDetailNew.forEach(items => {
		tbody+= `<tr>
			<td>${items.qty}</td>
			<td hidden>${items.supplier_id}</td>
			<td>${items.supplier_name}</td>
			</tr>
		`
	});
	$("#selectedList").html(`<table>${tbody}</table>`);
	$("#qty-stok-satuan").html(qtyStokNew);
	$("#qty-stok-packaging").html(rollStokNew);

	
	$("#qty-selected-satuan").html(qtySelectedNew);
	$("#qty-selected-packaging").html(rollSelectedNew);

	$("#qtyNew").val(qtySelectedNew);
	$("#rollNew").val(rollSelectedNew);
	if (qtySelectedNew > 0) {
		$(".btn-save").prop('disabled',false);
	}
}

function drawSelectedEdit(){
	let tbody = '';
	$(".btn-edit-save").prop('disabled',true);


	$("#rekapQtyEdit").val(JSON.stringify(qtyDetailEdit))
	qtyDetailEdit.forEach(items => {
		if (items.stok_index !== null) {
			tbody+= `<tr>
				<td>${items.qty}</td>
				<td hidden>${items.supplier_id}</td>
				<td>${items.supplier_name}</td>
				</tr>
			`;
		}
	});
	$("#selectedListEdit").html(`<table>${tbody}</table>`);
	$("#qty-stok-satuan-edit").html(qtyStokEdit);
	$("#qty-stok-packaging-edit").html(rollStokEdit);

	
	$("#qty-selected-satuan-edit").html(qtySelectedEdit);
	$("#qty-selected-packaging-edit").html(rollSelectedEdit);

	$("#qtyEdit").val(qtySelectedEdit);
	$("#rollEdit").val(rollSelectedEdit);
	if (qtySelectedEdit > 0) {
		$(".btn-edit-save").prop('disabled',false);
	}
}

function mutasi_batal(id, status_aktif){
	const text = (status_aktif == 1 ? "Mau membatalkan mutasi barang ini ?" : "" )
	bootbox.confirm(text,function(respond){
		if (respond) {
			window.location.replace(baseurl+"inventory/mutasi_barang_batal/"+id+"/"+status_aktif);
		}
	})
}

//=====================================================================================

async function get_mutasi_barang_eceran(){
	const tanggal_start = "<?=is_date_formatter($tanggal_start)?>";
	const tanggal_end = "<?=is_date_formatter($tanggal_end)?>";
	const barang_id = "<?=$barang_id?>";
	const warna_id = "<?=$warna_id?>";
	const cond = ((barang_id != '' ? `&barang_id=${barang_id}` : '')) + ((warna_id != '' ? `&warna_id=${warna_id}` : '')); ;
	const response = await fetch(baseurl+`inventory/get_mutasi_barang_eceran?tanggal_start=${tanggal_start}&tanggal_end=${tanggal_end}${cond}`, {
      method: "GET",
    });

    const result = await response.json();
	const rows = [];
	result.map((res, index)=>{
		const t = res.tanggal.split('-').reverse().join('/');
		rows.push(`
			<tr>
				<td>${t}</td>
				<td>${res.nama_barang} ${res.nama_keterangan}</td>
				<td>${res.nama_gudang_sumber}</td>
				<td>${res.nama_gudang}</td>
				<td>${parseFloat(res.qty)}</td>
				<td>
					<button class="btn btn-xs btn-small red"><i class="fa fa-times" onclick="removeMutasiEceran('${res.id}','${t}', '${res.nama_barang} ${res.nama_keterangan}')"></i></button>
				</td>
			</tr>
		`)
	});

	console.log(rows.join(''));
	$("#tableEceran tbody").html(rows.join(''))
}

function toggleEceran(){
	const isEceran = $("#eceranToggler").is(':checked');
	if (isEceran) {
		$(".non-eceran").hide();
		$(".eceran").show();
	}else{
		$(".non-eceran").show();
		$(".eceran").hide();
	}
}

function ambilEceran(index){
	const ini = $('#qty-table-eceran-edit tbody').find('tr').eq(index);
	const q = ini.attr('data-qty');
	const qty = $(document).find('.qty-input-ecer').val();
	const sisa = q - qty;

	if (qty.length == 0 || qty == 0) {
		ini.removeClass("active");
	}else if(sisa < 0){
		bootbox.alert("quantity ambil terlalu besar");
		$(ini).find('.qty-input-ecer').val('').change().focus()
	}else{
		if(!ini.hasClass("active")){
			ini.addClass("active");
		}
	}


	setRekapEceran();
}

function setRekapEceran(){
	let totalAmbil = 0;
	const rekap = [];
	$('#qty-table-eceran-edit tbody tr').each((i,v)=>{
		const ini = v;
		const q = $(ini).attr('data-qty');
		const id = $(ini).attr('data-id');
		const sId = $(ini).attr('data-supplier');
		const qty = $(ini).find('.qty-input-ecer').val();
		const sisa = q - qty;

		if (qty.length > 0 && qty > 0) {
			totalAmbil += parseFloat(q);
			rekap.push({
				id:id,
				qty:parseFloat(qty),
				supplier_id: sId
			});
		}
	});

	$("#rekapQtyNew").val(JSON.stringify(rekap));
	if (totalAmbil > 0) {
		$(".btn-save-eceran").prop('disabled',false);
	}else {
		$(".btn-save-eceran").prop('disabled',true);
	}
}

function setViewTab(index){
	$(".navi-tab").removeClass("active");
	$(`#mutasi${index}`).addClass("active");
	if (index == 1) {
		$(`#viewTab2`).hide();
	}else {
		$(`#viewTab1`).hide();
	}
	$(`#viewTab${index}`).show();
}

function removeMutasiEceran(index, tanggal, nama_barang){
	bootbox.confirm(`Yakin menghapus mutasi eceran <b>${nama_barang}</b> ini ?`, function(respond){
		if (respond) {
			removMutasiEceranData(index);
		}
	});
}

async function removMutasiEceranData(index){
	const response = await fetch(baseurl+`inventory/remove_mutasi_eceran`, {
      method: "POST",
	  body: `id=${index}`,
	  headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
	  }
    });

	const result = await response.text();
	console.log(result, typeof result);
	if (result == "OK") {
		window.location.reload();
	}
	
}

</script>
