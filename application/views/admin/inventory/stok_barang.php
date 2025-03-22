<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


<?$warna_bg = ['#dfe1f5', '#e1fac5','#faeded','#eee']?>
<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

.highlight{
	background-color: yellow !important;
	cursor: pointer;
}

<?$idx=0;foreach ($this->gudang_list_aktif as $row) {?>
	.gudang-<?=$row->id?>{
		background-color: <?=$warna_bg[$idx];?>;
	}
<?$idx++;}?>

.warning-qty td{
	border-top: 1px solid red !important;
	border-bottom: 1px solid red !important;
}

.warning-qty td:nth-child(1){
	border-left: 1px solid red !important;
}

.warning-qty td:nth-last-child{
	border-right: 1px solid red !important;
}

.qty-info{
	background-color: lightpink; 
	padding:8px 10px; 
	margin-top:10px; 
	display:none
}

.satuan-col:hover .qty-info{
	display: block;
}

.btn-add-flag, .btn-toggle-view{
	display: none;
}

.col-add-flag:hover .btn-add-flag,
.col-add-flag:hover .btn-toggle-view{
	display: block;
}

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

.actually-hidden{
	background-color: #ddd;
	color:blue;
}

.actually-hidden:hover{
	background-color: #ccc;
}

#buttonFilter{
	display: none;
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
							<!-- <select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a> -->
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<h4>
											<b>Tanggal Stok: </b>
											<input name='tanggal' readonly onchange="showButtonFilter()" class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'> 
											<button class='btn btn-xs red' id='buttonFilter'><i class='fa fa-search'></i></button>
											<br>
											
											<b>Toko: </b>
											<label	hidden>
											<input type="radio" name="toko_id" 
														id="toko-filter-join" 
														value="join" 
														<?=($toko_id == "join" ? 'checked' : "")?> 
														onchange="showButtonFilter()">JOIN</label>
											<label>
													<input type="radio" name="toko_id" 
														id="toko-filter-all" 
														value="all" 
														<?=($toko_id == "all" ? 'checked' : "")?> 
														onchange="showButtonFilter()">All Toko</label>
														<label>
													
											<?foreach ($this->toko_list_aktif as $row) {?>
												<label>
													<input type="radio" name="toko_id" 
														id="toko-filter-<?=$row->id?>" 
														value="<?=$row->id?>" 
														<?=($toko_id == $row->id ? 'checked' : "")?>
														onchange="showButtonFilter()"><?=$row->nama;?> Only</label>
											<?}?>
										</h4>
									</form>
								</td>
								<td class='text-right'>
									<button class="btn btn-md btn-danger" id="btn-filter-warning" onclick="filterStokLow()">Filter Stok Low </button>
									<form hidden action='<?=base_url();?>inventory/stok_barang_excel' method='get'>
										<input name='tanggal' value='<?=$tanggal;?>' hidden>
										<button class='btn green'><i class='fa fa-download'></i> Excel</button>
									</form>

									<?if ($toko_id != 'all' && $toko_id != 'join' && $toko_id != '') {?>
										<?foreach ($this->toko_list_aktif as $row) {
											if($row->id == $toko_id){?>
												<form action='<?=base_url();?>inventory/stok_barang_excel_pertoko' method='get'>
													<input name='tanggal' value='<?=$tanggal;?>' hidden>
													<input name='toko_id' value='<?=$row->id;?>' hidden>
													<button class='btn green'><i class='fa fa-download'></i> Excel <?=$row->nama;?></button>
												</form>
											<?}?>
										<?}?>
									<?}?>

									

									<?if (is_posisi_id() == 1) {?>
										<form hidden action='<?=base_url();?>inventory/stok_barang_detail_excel' method='get'>
											<input name='tanggal' value='<?=$tanggal;?>' hidden>
											<button class='btn green'><i class='fa fa-download'></i> Excel2</button>
										</form>
									<?}?>
								</td>
							</tr>
						</table>
						<hr/>

						<table>
							<tr>
								<th colspan="3">
									<b style="font-size: 1.1em;">Filter : </b>
								</th>
							</tr>
							<tr>
								<td>Gudang</td>
								<td> : </td>
								<td > <?foreach ($this->gudang_list_aktif as $row) {?>
									<label style="margin-right: 10px;" onclick="toggleGudang('<?=$row->id?>','<?=$row->isVisible?>')">
										<input type="checkbox" name="gudang_filter" <?=($row->isVisible == 1 ? "checked" : "")?> ><?=$row->nama?></label>
								<?}?> </td>
							</tr>
							<tr>
								<td>Barang</td>
								<td> : </td>
								<td>
									<label onclick="filterShown('All')">
										<input type="radio" <?=($is_filter_sku == "All" ? "checked" : "")?> >Semua</label> 
									<label onclick="filterShown('Some')">
										<input type="radio" <?=($is_filter_sku == "Some" ? "checked" : "")?> >Hide Sebagian</label> 
									<?if (is_posisi_id()==1) {?>
										
									<label onclick="filterShown('NoSKU')">
										<input type="radio" <?=($is_filter_sku == "Some" ? "checked" : "")?> >No SKU</label> 
									<?}?>	
								</td>
							</tr>
						</table>

						<hr/>
						<h3>Stok Barang <?=strtoupper($toko_id)?></h3>
						<hr/>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<!-- <th scope="col" rowspan='2'>
										Nama Beli
									</th> -->
									<th scope="col" rowspan='2'>
										Nama Jual
									</th>
									<th scope="col" rowspan='2'>
										<i class="fa fa-flag"></i>
									</th>
									<?/* if (is_posisi_id() == 1) {?>
										<th scope="col" rowspan='2'>
											Status
										</th>
									<?} */?>
									<th scope="col"  rowspan='2'>
										Satuan
									</th>
									<?foreach ($this->gudang_list_aktif as $row) {
										if ($row->isVisible == 1) {?>
											<th colspan='3'><?=$row->nama;?></th>
										<?}?>
									<?}?>
									<th colspan='3'>TOTAL</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) {
										if ($row->isVisible == 1) {?>
										<th>Sat. Kecil</th>
										<th>Sat. Besar</th>
										<th>Eceran</th>
										<!-- <th><i class='fa fa-list'></i></th> -->

									<?}}?>
									<th>Sat. Kecil</th>
									<th>Sat. Besar</th>
									<th>Eceran</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_barang_eceran as $row) {
									$stok_eceran[$row->gudang_id][$row->barang_id][$row->warna_id] = $row->qty_stok;
								}?>
								
								<?foreach ($stok_barang_warning as $row) {
									// print_r($row->qty_warning);
									// echo "<br/>";
									$stok_warning[$row->nama_satuan][$row->barang_id][$row->warna_id] = $row->qty_warning;
								}?>
								<?$baris = 0;
								foreach ($stok_barang as $row) { 
										$baris++;

										$subtotal_qty = 0;
										$subtotal_roll = 0;
										$subtotal_eceran = 0;
										$is_warning=false;
										foreach ($this->gudang_list_aktif as $isi) {
											$qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = (!isset($qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id]) ? $qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = 0 : $qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id]);
											$roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = (!isset($roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id]) ? $roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = 0 : $roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id]);
											$nama_qty = 'gudang_'.$isi->id.'_qty';
											$nama_roll = 'gudang_'.$isi->id.'_roll';
											
											$subtotal_qty += $row->$nama_qty;
											$subtotal_roll += $row->$nama_roll;
											$qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] += $row->$nama_qty;
											$roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] += $row->$nama_roll;
											$stok_e = '';
											$sub_warning = $subtotal_qty;
											if(isset($stok_eceran[$isi->id][$row->barang_id][$row->warna_id])){
												$subtotal_eceran += $stok_eceran[$isi->id][$row->barang_id][$row->warna_id];
												$stok_e = $stok_eceran[$isi->id][$row->barang_id][$row->warna_id];
												$sub_warning += $stok_e;
												$stok_e = str_replace(",00","",number_format($stok_e,'2',',','.'));
											}
										}
										
										$flag = '';
										$flag_info = "";
										if (isset($stok_warning[$row->nama_satuan][$row->barang_id][$row->warna_id]) ||  isset($stok_warning[$row->nama_packaging][$row->barang_id][$row->warna_id])) {
											$flag = 'fa fa-flag';

											$qty_warning = (float)(isset($stok_warning[$row->nama_satuan][$row->barang_id][$row->warna_id]) ? $stok_warning[$row->nama_satuan][$row->barang_id][$row->warna_id] : $stok_warning[$row->nama_packaging][$row->barang_id][$row->warna_id] );
											$flag_info = "Aktif jika <".$qty_warning;
											if (isset($stok_warning[$row->nama_satuan][$row->barang_id][$row->warna_id])) {
												if ($qty_warning >= $sub_warning && $row->nama_satuan != 'none') {
													$is_warning = true;
												}
												$flag_info .= ' '.$row->nama_satuan;
											}else{
												if ($qty_warning >= $subtotal_roll && $row->nama_packaging != 'none') {
													$is_warning = true;
												}
												$flag_info .= ' '.$row->nama_packaging;

											}
										}
											
										?>
										<tr class="<?=($is_warning ? 'warning-qty' : '');?> <?=($row->sku_id !== '' ? 'has-sku' : 'no-sku') ?> " >
											
											<td style='text-align:left'>
													<?=$row->nama_barang_jual;?> <?=$row->nama_warna_jual;?>
												<?if ($toko_id != 'join') {?>
													<?//=$row->barang_id?> <?//=$row->warna_id;?> 
													<?//=$row->sku_id?> 
													<?=$row->nama_toko;?>
												<?}?>
												<span hidden><?=($is_warning != '' ? 'has-warning' : '');?></span>
											</td>
											<td class="<?=($flag == '' ? "col-add-flag" : '')?>">
												<i color class="<?=$flag?>"></i>
												<?if($flag =='' && is_master_admin()){?>
													<a href="#portlet-config" data-toggle="modal" onclick="newFlag('<?=$row->sku_id;?>','<?=$row->nama_satuan?>','<?=$row->nama_packaging?>')" class="btn btn-xs green btn-add-flag"> 
														<i class="fa fa-plus"></i> 
													</a>
												<?}?>
												<?if ($row->sku_id != ''){
													$brg = $row->nama_barang_jual.' '.$row->nama_warna_jual;
													if(is_master_admin() && $row->isShown == 1) {?>
														<button class="btn-xs btn yellow-gold btn-toggle-view" onclick="hideSku('<?=$row->sku_id?>','<?=$brg?>')"><i class="fa fa-eye"></i></button>
													<?}else{?>
														<button class="btn-xs btn red btn-toggle-view" onclick="showSku('<?=$row->sku_id?>','<?=$brg?>')"><i class="fa fa-eye-slash"></i></button>
													<?}
												}?>
											</td>
											<?/* if (is_posisi_id() == 1) {?>
												<td>
													<?if ($row->status_barang == 0) { ?>
														<span style='color:red'>Tidak Aktif</span> 
													<? }else{?>
														Aktif
													<?} ?>
												</td>
											<?} */?>
											<td class='satuan-col'>
												<?if (isset($stok_warning[$row->nama_satuan][$row->barang_id][$row->warna_id])) {?>
													<span style="background-color: lightpink;" >
														<?=$row->nama_satuan;?>
													</span>

												<?}else{
													echo $row->nama_satuan;
												}?>
												/
												<?if (isset($stok_warning[$row->nama_packaging][$row->barang_id][$row->warna_id])) {?>
													<span style="background-color: lightpink;">
														<?=$row->nama_packaging;?>
													</span>
												<?}else{
													echo $row->nama_packaging;
												}?>

												<div class="qty-info">
													<?if ($flag_info != '') {?>
														<?=$flag_info;?>
													<?}?>
												</div>
											</td>
											<?
											$subtotal_qty = 0;
											$subtotal_roll = 0;
											$subtotal_eceran = 0;
											foreach ($this->gudang_list_aktif as $isi) {
												if ($isi->isVisible == 1) {
													$qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = (!isset($qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id]) ? $qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = 0 : $qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id]);
													$roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = (!isset($roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id]) ? $roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] = 0 : $roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id]);
													$nama_qty = 'gudang_'.$isi->id.'_qty';
													$nama_roll = 'gudang_'.$isi->id.'_roll';
													
													$subtotal_qty += $row->$nama_qty;
													$subtotal_roll += $row->$nama_roll;
													$qty_gudang[$row->satuan_id.$row->packaging_id][$isi->id] += $row->$nama_qty;
													$roll_gudang[$row->satuan_id.$row->packaging_id][$isi->id] += $row->$nama_roll;
													$stok_e = '';
													if(isset($stok_eceran[$isi->id][$row->barang_id][$row->warna_id])){
														$subtotal_eceran += $stok_eceran[$isi->id][$row->barang_id][$row->warna_id];
														$stok_e = $stok_eceran[$isi->id][$row->barang_id][$row->warna_id];
														$stok_e = str_replace(",00","",number_format($stok_e,'2',',','.'));
													}
													
													$t_id = "";

													if($toko_id != 'join'){
														$t_id = (isset($row->toko_id) ? $row->toko_id : 0);
													}
													?>
													<td onclick="openKartuStok('<?=$isi->id?>','<?=$row->barang_id?>', '<?=$row->warna_id;?>', '<?=$t_id;?>' )" class="col-<?=$baris?>-<?=$isi->id?> gudang-<?=$isi->id?>" onmouseover="addHighlight('col-<?=$baris?>-<?=$isi->id?>')" onmouseleave="removeHighlight('col-<?=$baris?>-<?=$isi->id?>')" ><?=(float)$row->$nama_qty?></td>
													<td onclick="openKartuStok('<?=$isi->id?>','<?=$row->barang_id?>', '<?=$row->warna_id;?>', '<?=$t_id;?>'  )" class="col-<?=$baris?>-<?=$isi->id?> gudang-<?=$isi->id?>" onmouseover="addHighlight('col-<?=$baris?>-<?=$isi->id?>')" onmouseleave="removeHighlight('col-<?=$baris?>-<?=$isi->id?>')" >
														<?if($row->warna_id != 888){?>
															<?=number_format($row->$nama_roll,'0',',','.');?>
														<?}?>
													</td>
													<td onclick="openKartuStok('<?=$isi->id?>','<?=$row->barang_id?>', '<?=$row->warna_id;?>', '<?=$t_id;?>')" class="col-<?=$baris?>-<?=$isi->id?> gudang-<?=$isi->id?>" onmouseover="addHighlight('col-<?=$baris?>-<?=$isi->id?>')" onmouseleave="removeHighlight('col-<?=$baris?>-<?=$isi->id?>')" ><?=$stok_e;?></td>
													<!-- <td>									
														<a href="<?=base_url().is_setting_link('inventory/kartu_stok').'/'.$isi->id.'/'.$row->barang_id.'/'.$row->warna_id;?>" class='btn btn-xs yellow-gold' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>
													</td> -->
												<?
												}
												}?>

											<td>
												<b><?=(float)$subtotal_qty;?></b> 
											</td>
											<td>
												<?if($row->warna_id != 888){?>
													<b><?=number_format($subtotal_roll,'0',',','.');?></b>											
												<?}?>
											</td>
											<td>
												<b><?=number_format($subtotal_eceran,'0',',','.');?></b>											
											</td>
										</tr>
									<?
								} ?>
								
							</tbody>
						</table>
						<hr/>
						<?if (is_posisi_id() <= 3) { ?>
							<table class='table' style='font-size:1.5em;'>
								<thead>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th colspan='2' class='text-center' ><?=$row->nama;?></th>
										<?}?>
										<th colspan='2' class='text-center'>TOTAL</th>
									</tr>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th class='text-center'>Qty</th>
											<th class='text-center'>Roll</th>
										<?}?>
										<th class='text-center'>Qty</th>
										<th class='text-center'>Roll</th>
									</tr>
								</thead>
								<tbody>
									<?
									foreach ($qty_gudang as $key => $value) {
										// echo($key).'==';
										// print_r($value);echo'<br/>';
									}
									foreach ($this->satuan_list_aktif as $row) {
										foreach ($this->satuan_list_aktif as $row2) {
											$total_qty = 0;
											$total_roll = 0;
											if (isset($qty_gudang[$row->id.$row2->id])) {?>
												<tr>
													<?foreach ($this->gudang_list_aktif as $col) {?>
														<?if (isset($qty_gudang[$row->id.$row2->id][$col->id])) {
															$total_qty += $qty_gudang[$row->id.$row2->id][$col->id];
															$total_roll += $roll_gudang[$row->id.$row2->id][$col->id];
															$qty = $qty_gudang[$row->id.$row2->id][$col->id];
															$roll = $roll_gudang[$row->id.$row2->id][$col->id];
														}else{
															$qty = 0;
															$roll = 0;
														}?>
														<td class='text-center'>
															<?=$qty;?> <?=$row->nama;?>
														</td>
														<td class='text-center'>
															<?=$roll;?> <?=$row2->nama;?>
														</td>
													<?}?>
													<td class='text-center'><b> <?=$total_qty?> <?=$row->nama;?></b></td>
													<td class='text-center'><b><?=$total_roll?> <?=$row2->nama;?></b></td>
												</tr>
											<?}
										}
									}?>
								</tbody>
							</table>
						<?}?>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form id="form-add-barang" class="form-horizontal"  action="" method="POST">
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
									<input type="text" name="sku_id" id="sku_id_new" hidden>
									<select disabled id="barang_id_new" class="form-control">
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
						<button type="button" class="btn default" data-dismiss="modal" id="btnWarningClose">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
var oTable = null;
var showStokButton = false;

jQuery(document).ready(function() {
	//Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	// TableAdvanced.init();

	oTable = $("#general_table").DataTable({
		// "ordering":false,
		"orderClasses": false
	});

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	// $("#general_table").DataTable({
 //   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
 //            var status = $('td:eq(6)', nRow).text().split('??');
 //            var id = status[0];
 //            var satuan_id = status[1];
 //            var status_aktif = $('td:eq(0)', nRow).text();
 //            if (status_aktif == 1 ) {
 //            	var btn_status = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
 //            }else{
 //            	var btn_status = "<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>";
 //            };
 //           	var action = "<span class='id' hidden='hidden'>"+id+"</span><span class='satuan' hidden='hidden'>"+satuan_id+"</span><span class='status_aktif' hidden='hidden'>"+status_aktif+"</span><a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>"+btn_status;
            
 //            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
 //            $('td:eq(0)', nRow).addClass('status_column');
 //            $('td:eq(1)', nRow).html('<span class="nama">'+$('td:eq(1)', nRow).text()+'</span>');
 //            $('td:eq(2)', nRow).html('<span class="nama_jual">'+$('td:eq(2)', nRow).text()+'</span>');
 //            $('td:eq(4)', nRow).html('<span class="harga_jual">'+change_number_format($('td:eq(4)', nRow).text())+'</span>');
 //            $('td:eq(5)', nRow).html('<span class="harga_beli">'+change_number_format($('td:eq(5)', nRow).text())+'</span>');
 //            $('td:eq(6)', nRow).html(action);
 //            // $(nRow).addClass('status_aktif_'+status_aktif);
            
 //        },
 //        "bStateSave" :true,
	// 	"bProcessing": true,
	// 	"bServerSide": true,
	// 	"sAjaxSource": baseurl + "master/data_barang"
	// });

	// var oTable;
 //    oTable = $('#general_table').dataTable();
 //    oTable.fnFilter( 1, 0 );

	// $('#status_aktif_select').change(function(){
	// 	oTable.fnFilter( $(this).val(), 0 ); 
	// });
	
	
   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=barang_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
   		$('#form_edit_data [name=nama_jual]').val($(this).closest('tr').find('.nama_jual').html());
   		$('#form_edit_data [name=harga_beli]').val($(this).closest('tr').find('.harga_beli').html());
   		$('#form_edit_data [name=harga_jual]').val($(this).closest('tr').find('.harga_jual').html());
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=barang';
   		window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=barang_list');
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

	$('#qty_alert_new').change(function(){
		const sat = $('#nama_satuan_new').val();
		const val = $(this).val();const warn = val > 0 ? `Stok &le; ${val} ${sat}` : "<span class='text-mute'>No alert</span>";
		$("#ex-alert-new").html(warn);
	});
	
	$('#qty_warning_new').change(function(){
		const sat = $('#nama_satuan_new').val();
		const val = $(this).val();
		const warn = val > 0 ? `Stok &le; ${val} ${sat}` : "<span class='text-mute'>No warning</span>";
		$("#ex-warning-new").html(warn);
	});
});

function showButtonFilter(){
	$("#buttonFilter").show();
}

function addHighlight(className) {
	$("."+className).addClass('highlight');
}

function removeHighlight(className) {
	$("."+className).removeClass('highlight');
}

function openKartuStok(gudang_id,barang_id, warna_id, toko_id){
	// .'/'.$isi->id.'/'.$row->barang_id.'/'.$row->warna_id;?>"
	<?if(is_posisi_id() == 1 && $toko_id != 'join'){?>
		const url = "<?=base_url().is_setting_link('inventory/kartu_stok_pertoko');?>"+`/${gudang_id}/${barang_id}/${warna_id}/${toko_id}`;
		window.open(url, "_blank");
	<?}else{?>
		const url = "<?=base_url().is_setting_link('inventory/kartu_stok_pertoko');?>"+`/${gudang_id}/${barang_id}/${warna_id}/${toko_id}`;
		window.open(url, "_blank");
	<?}?>
}

function filterStokLow(){
	showStokButton = !showStokButton;
	if (showStokButton) {
		$("#btn-filter-warning").html("Show All Stok").removeClass('btn-danger');
		oTable.search('has-warning').draw();
		return;
	}
	$("#btn-filter-warning").html("Filter Stok Low").addClass("btn-danger");
	oTable.search("").draw();
	return
}

function newFlag(sku_id, nama_satuan, nama_packaging){
	$("#sku_id_new").val(sku_id);
	$("#barang_id_new").val(sku_id).change();
	$("#btn-satuan").html(nama_satuan);
	$("#btn-packaging").html(nama_packaging);
}

function submitNewWarning(){

	const sku_id = $('#sku_id_new').val(); 	
	const nama_satuan = $('#nama_satuan_new').val();
	const qty_alert = $('#qty_alert_new').val();
	const qty_warning = $('#qty_warning_new').val();
	let valid = true;
	
	let satuan_notes = "Satuan belum dipilih<br/>";
	let qty_notes = "Stok warning harus diisi";
	let warning_notes = '';
	
	if (nama_satuan == '') {
		valid=false;
		warning_notes += satuan_notes;
	}

	if (qty_alert == '' && qty_warning == '' ) {
		valid=false;
		warning_notes += qty_notes;
	}

	if (valid) {
		const data = {};
		const url = "master/qty_warning_insert_ajax";
		data['sku_id'] = sku_id;
		data['toko_id'] = $("#toko_id_new").val();
		data['nama_satuan'] = nama_satuan;
		data['qty_alert'] = qty_alert;
		data['qty_warning'] = qty_warning;

		$("#btnWarningSave").prop('disabled',true);
		$("#btnWarningClose").hide();

		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			window.location.reload();
		});
	}else{
		$("#warning-new").show();
		$('#warning-note-new').html(warning_notes);
	}

}

async function showSku(skuId, nama_barang){
	const response = await fetch(baseurl+"inventory/toggle_sku_barang", {
      method: "POST",
	  body:`sku_id=${skuId}&isShown=1`,
	  headers: {
		'Content-Type': 'application/x-www-form-urlencoded',
	},
    });

	const result = await response.json();
	if (result=="OK") {
		notific8("lime", `Barang ${nama_barang} di show, refresh untuk melihat perubahan`);
	}
}
async function hideSku(skuId, nama_barang){
	const response = await fetch(baseurl+"inventory/toggle_sku_barang", {
      method: "POST",
	  body:`sku_id=${skuId}&isShown=0`,
	  headers: {
		'Content-Type': 'application/x-www-form-urlencoded',
		},
    });

	const result = await response.json();
	if (result=="OK") {
		notific8("lime", `Barang  ${nama_barang} di hide, refresh untuk melihat perubahan`);
	}
}

function filterShown(action){
	const link = "<?=base_url().is_setting_link('inventory/stok_barang')?>"
	window.location.replace(link+"?is_filter_sku="+action);
}

async function toggleGudang(gudangId, isVisible){
	const response = await fetch(baseurl+"master/toggle_gudang_visibility", {
      method: "POST",
	  body:`gudang_id=${gudangId}&isVisible=${(isVisible == 1 ? 0 : 1)}`,
	  headers: {
		'Content-Type': 'application/x-www-form-urlencoded',
		},
    });

	const result = await response.json();
	if (result=="OK") {
		window.location.reload();
	}
}

</script>
