<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

.qty-table-input tr td input{
	width: 100%;
	width:100px;
	padding:2px 8px;
}

#stok-eceran{
	padding:10px;
	margin:auto;
	margin-bottom:30px;
	text-align:center;
}

#table-eceran{
	margin:auto;
}


#table-eceran tr td, #table-eceran tr th{
	text-align:center;
	padding:2px 8px;
	font-size:1.1em;
	border:1px solid #ddd;
	min-width:50px;
	height:50px;
}

#table-eceran tr:nth-child(2n) td:nth-child(2n){
	background: #f9f9f9;
}

#table-eceran tr:nth-child(2n+1) td:nth-child(2n+1) {
	background: #f9f9f9;
}

#qty-total, #roll-total{
	font-size:1.2em;
	font-weight:bold;
}

#qty-total-info, #roll-total-info{
	font-size:0.9em;
	color:#333;
}

.qty-eceran-data{
	position: absolute;
	top: 100%;
	padding: 5px;
	z-index: 99;
	font-size:12px;
	background-color: white;
	max-height:200px;
	overflow:auto;
}

.qty-eceran-data tbody tr:first-child td{
	font-size:14px !important;
}

.qty-eceran-data table tr th,
.qty-eceran-data table tr td{
	padding:2px !important;
	min-width:20px !important;
	height:20px !important;
}

.qty-eceran-aktif{
	background:lightpink !important;
}

#kartu-stok-eceran{
	display: none;
}

#kartu-stok-copy{
	display: none;
}

</style>

<div class="page-content">
	<div class='container'>

		<?
			$nama_satuan = '';
			$nama_packaging = '';
			foreach ($barang_data as $row) {
				$nama_satuan = $row->nama_satuan;
				$nama_packaging = $row->nama_packaging;
			}
		?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/penyesuaian_stok_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Penyesuaian Barang</h3>

							<div class="form-group">
								<label class="control-label col-md-4">Toko<span class="required">
								* </span>
								</label>
								<div class="col-md-6">
									<input type="text" class="form-control" value="<?=$nama_toko?>" disabled >
									<input type="text" name="toko_id" value="<?=$toko_id?>" <?=(is_posisi_id()==1 ? "" : "hidden" )?> >
								</div>
							</div>

							
							<div class="form-group">
			                    <label class="control-label col-md-4">Tipe<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <div class='radio-list'>
					                	<label class="radio-inline">
					                		<input type='radio' class='form-control' checked name='tipe_transaksi' value='1'> Barang Masuk
					                	</label>
					                	<label class="radio-inline">
					                		<input type='radio' class='form-control' name='tipe_transaksi' value='2'> Barang Keluar
					                	</label>
					                </div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='barang_id' value='<?=$barang_id?>' hidden='hidden'>
			                    	<input name='warna_id' value='<?=$warna_id?>' hidden='hidden'>
			                    	<input name='gudang_id' value='<?=$gudang_id;?>' hidden='hidden'>
					                <input name="tanggal" type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Qty
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name="qty"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jumlah Roll
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name="jumlah_roll"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name="keterangan"/>
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
						<form action="<?=base_url('inventory/penyesuaian_stok_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Penyesuaian Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-4">Tipe<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <div class='radio-list'>
					                	<label class="radio-inline">
					                		<input type='radio' class='form-control' name='tipe_transaksi' value='1'> Barang Masuk
					                	</label>
					                	<label class="radio-inline">
					                		<input type='radio' class='form-control' name='tipe_transaksi' value='2'> Barang Keluar
					                	</label>
					                </div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='barang_id' value='<?=$barang_id?>' hidden='hidden'>
			                    	<input name='warna_id' value='<?=$warna_id?>' hidden='hidden'>
			                    	<input name='gudang_id' value='<?=$gudang_id;?>' hidden='hidden'>
			                    	<input name='penyesuaian_stok_id' hidden='hidden'>
				                	<input name="tanggal" type="text" readonly class="form-control date-picker" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Qty
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name="qty"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jumlah Roll
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name="jumlah_roll"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name="keterangan"/>
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

		<div class="modal fade" id="portlet-config-eceran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-body">
						<h3 class='block'> Mutasi ke eceran</h3>
						<form action="<?=base_url('inventory/mutasi_stok_eceran_insert')?>" class="form-horizontal" id="form_add_eceran" method="post">
							<div class="form-group">
								<label class="control-label col-md-4">Tanggal<span class="required">
								* </span>
								</label>
								<div class="col-md-6">
									<input name='tipe' value='1' hidden>
									<input name='barang_id' value='<?=$barang_id?>' hidden>
									<input name='warna_id' value='<?=$warna_id?>' hidden>
									<input name='gudang_id' value='<?=$gudang_id;?>' hidden>
									<input name='rekap_qty' hidden>
									<input name="tanggal" type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4">Toko
								</label>
								<div class="col-md-6">
									<select name="toko_id" id="toko-id-select" class='form-control'>
										<?foreach ($this->toko_list_aktif as $row) {?>
											<option value="<?=$row->id?>"><?=$row->nama;?></option>
										<?}?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4">Keterangan
								</label>
								<div class="col-md-6">
									<input type="text" class='form-control' name="keterangan"/>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4">QTY
								</label>
								<div class="col-md-6">
									<table class='qty-table-input' id='qty-table'>
										<thead>
											<tr>
												<th><?=$nama_satuan;?></th>
												<th><?=$nama_packaging;?></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input class='qty'  tabindex='1'></td>
												<td hidden><input class='roll' ></td>
												<td><button class='btn btn-xs blue btn-add-qty' style="margin-left:10px;" tabindex='-1' onclick="addQtyTableRow()"><i class="fa fa-plus"></i></button></td>
											</tr>
											<?for ($i=0; $i < 5 ; $i++) { ?>
												<tr>
													<td><input class='qty'  tabindex="<?=($i+2);?>" ></td>
													<td hidden><input class='roll'></td>
												</tr>
											<?}?>
										</tbody>
										<tfoot>
											<tr>
												<td class='total-qty-eceran'></td>
												<td hidden class='total-roll-eceran'></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div> 
						</form>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-save-eceran" onclick="submitEceran()">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-eceran-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
					<h3 class='block'> Mutasi ke eceran Edit</h3>
						<form action="<?=base_url('inventory/mutasi_stok_eceran_update')?>" class="form-horizontal" id="form_edit_eceran" method="post">
							
							<div class="form-group">
									<label class="control-label col-md-4">Tanggal<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<input name='tipe' value='1' hidden>
										<input name='barang_id' value='<?=$barang_id?>' hidden>
										<input name='warna_id' value='<?=$warna_id?>' hidden>
										<input name='gudang_id' value='<?=$gudang_id;?>' hidden>
										<input name='rekap_qty' hidden>
										<input name='id' hidden>
										<input name="tanggal" type="text" readonly class="form-control date-picker" />
									</div>
								</div>

								<div class="form-group">
								<label class="control-label col-md-4">Toko
								</label>
								<div class="col-md-6">
									<select name="toko_id" id="toko-id-select-edit" class='form-control'>
										<?foreach ($this->toko_list_aktif as $row) {?>
											<option value="<?=$row->id?>"><?=$row->nama;?></option>
										<?}?>
									</select>
								</div>
							</div>

								<div class="form-group">
									<label class="control-label col-md-4">Keterangan
									</label>
									<div class="col-md-6">
										<input type="text" class='form-control' name="keterangan"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4">QTY
									</label>
									<div class="col-md-6">
										<table class='qty-table-input' id='qty-table-edit'>
											<thead>
												<tr>
													<th><?=$nama_satuan;?></th>
													<th hidden><?=$nama_packaging;?></th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
											<tfoot>
												<tr>
													<td class='total-qty-eceran'></td>
													<td hidden class='total-roll-eceran'></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div> 
							
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-eceran-save" onclick="submitEceranEdit()">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<form id="form_remove_data" action="<?=base_url('inventory/penyesuaian_stok_remove')?>" method='post'>
			<input name='barang_id' value='<?=$barang_id?>' hidden='hidden'>
        	<input name='warna_id' value='<?=$warna_id?>' hidden='hidden'>
        	<input name='gudang_id' value='<?=$gudang_id;?>' hidden='hidden'>
        	<input name='penyesuaian_stok_id' hidden='hidden'>
		</form>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<?/*if (is_posisi_id() < 3) { ?>
							<div class="actions">
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add hidden-print">
								<i class="fa fa-plus"></i> Tambah </a>
								<!-- <a href="#portlet-config-eceran" data-toggle='modal' class="btn btn-default btn-sm hidden-print">
								<i class="fa fa-plus"></i> Eceran </a> -->
							</div>
						<?}*/?>
					</div>
					<div class="portlet-body">
						<h1><?=$nama_toko;?></h1>
						<form action='' method='get'>
							<table>
								<tr>
									<td>Lokasi</td>
									<td class='padding-rl-5'> : </td>
									<td><b><?=$nama_gudang;?></b></td>
								</tr>
								<tr>
									<td>Nama/Warna</td>
									<td class='padding-rl-5'> : </td>
									<td><b><?=$nama_beli.' '.$warna_beli.' || '.$nama_jual.' '.$warna_jual;?></b></td>
								</tr>
								<tr>
									<td>Tanggal Stok</td>
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
							</table>
							
						</form>

						<hr/>
						<button class='btn green btn-kartu'>Kartu Stok</button>
						<button class='btn yellow-gold btn-kartu-eceran'>Kartu Stok Eceran</button>
						<button class='btn blue btn-detail'>List Stok</button>
						<hr/>
						<?
							$qty = 0;
							$roll = 0;

							?>

						<div id='kartu-stok'>
							<h3>
								Kartu Stok
								<button class="btn btn-xs green" onclick="copyTableAsHTML()">Copy Table</button>
							</h3>
							<!-- <button onclick="copyTableAsText()">Copy as Plain Text</button>
							<button onclick="copyTableAsCSV()">Copy as CSV</button> -->

							<table class="table table-striped table-bordered table-hover" id="general_table">
								<thead>
									<tr>
										<th scope="col" rowspan='2'>
											Tanggal
										</th>
										<th scope="col" rowspan='2'>
											Keterangan
										</th>
										<th scope="col" colspan='2'>
											Masuk
										</th>
										<th scope="col" colspan='2'>
											Keluar
										</th>
										<th colspan='2'>
											Saldo
										</th>
									</tr>
									<tr>
										<th scope="col">
											<?=$nama_satuan?>
										</th>
										<th scope="col">
											<?=$nama_packaging?>
										</th>
										<th scope="col">
											<?=$nama_satuan?>
										</th>
										<th scope="col">
											<?=$nama_packaging?>
										</th>
										<th scope="col">
											<?=$nama_satuan?>
										</th>
										<th scope="col">
											<?=$nama_packaging?>
										</th>

									</tr>
								</thead>
								<tbody>
									<?
									$total_in = 0;
									$total_out = 0;
									// print_r($stok_awal);
									foreach ($stok_awal as $row) { ?>
										<tr>
											<td>
												<b>Stok Awal</b>
											</td>
											<td></td>
											<td>
												<?=($row->qty_masuk == 0 ?  '-' :  $row->qty_masuk );?>
												<?$qty += $row->qty_masuk;?>
												<?$total_in += $row->qty_masuk;?>
											</td>
											<td>
												<?=($row->jumlah_roll_masuk == 0 ? '-' : $row->jumlah_roll_masuk ) ;?>
												<?$roll += $row->jumlah_roll_masuk;?>

											</td>

											<td>
												<?=($row->qty_keluar == 0 ? '-' : $row->qty_keluar );?>
												<?$qty -= $row->qty_keluar;?>
												<?$total_out += $row->qty_keluar;?>


											</td>
											<td>
												<?=($row->jumlah_roll_keluar == 0 ? '-' : $row->jumlah_roll_keluar ) ;?>
												<?$roll-= $row->jumlah_roll_keluar;?>

											</td>
											<td <? if ($qty < 0): echo "style='color:red'"; endif ?>>
												<b><?=number_format($qty,'2',',','.');?></b> 
											</td>
											<td <? if ($roll < 0): echo "style='color:red'"; endif ?>>
												<b><?=number_format($roll,'0',',','.');?></b> 
											</td>
										</tr>
									<?}?>
									<?foreach ($stok_barang as $row) { ?>
										<tr style="<?=($row->tipe == 'z1' ? 'background:#B71C1C; color:white' : ($row->tipe == 'so' ? 'background:#B71C1C; color:white' : ''))?>" >
											<td>
												<?=date('d F Y', strtotime($row->tanggal));?>
												<span class='tanggal' hidden><?=is_reverse_date($row->tanggal)?></span>
											</td>
											<td>
												<?if ($row->tipe == 'a1' || $row->tipe == 'a2' || $row->tipe == 'a3') {
													if (is_posisi_id() < 3) {
														if ($row->tipe == 'a1') { ?>
															<a terget='_blank' style="color:red" href="<?=base_url().is_setting_link('transaction/pembelian_list_detail').'/'.$row->trx_id;?>"><?=($row->no_faktur !='' ? $row->no_faktur : 'pembelian');?></a>
														<?}elseif ($row->tipe == 'a2') { ?>
															<a terget='_blank' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail').'?id='.$row->trx_id;?>"><?=($row->no_faktur !='' ? $row->no_faktur : 'penjualan');?></a>
														<?}elseif ($row->tipe == 'a3') { ?>
															<a terget='_blank' href="<?=base_url().is_setting_link('transaction/retur_jual_detail').'?id='.$row->trx_id;?>"><?=($row->no_faktur !='' ? $row->no_faktur : '???');?></a>
														<?}
													}else{
														echo $row->no_faktur;
													}
												}else if ($row->tipe == '1' || $row->tipe == '2' ) {
													$user = explode('??', $row->no_faktur);
													?>
													<!-- <?=$row->no_faktur;?> -->
													Penyesuaian (<small class='keterangan'><?=$user[3]?></small>) oleh: <b><?=$user[0];?></b> 
													<span class='user_id' hidden='hidden'><?=$user[1];?></span>
													<span class='penyesuaian_stok_id' hidden='hidden'><?=$user[2];?></span>
													<span class='tipe' hidden='hidden'><?=$row->tipe;?></span>
													<?
														if (is_posisi_id() < 3) { ?>
															<span class='tipe' hidden><?=$row->tipe?></span>
															<a href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs blue btn-edit'><i class='fa fa-edit'></i></a>
															<a class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></a>
														<? }?>
													
												<?}elseif ($row->tipe == 'b1') {
													echo "mutasi barang dari ".$row->no_faktur." ke ".$nama_gudang;
												}elseif ($row->tipe == 'b2') {
													echo "mutasi barang dari ".$nama_gudang." ke ".$row->no_faktur."";
												}elseif ($row->tipe == 'ask') {
													echo $row->no_faktur;
												}elseif ($row->tipe == 'asm') {
													echo $row->no_faktur;
												}else if($row->tipe == 0 && $row->tipe != 'b1' && $row->tipe != 'b2' && $row->tipe != 'z1' && $row->tipe != 'ecer1' && $row->tipe != 'so'){
													echo "<b>Mutasi Stok Awal</b>";
												}elseif ($row->tipe == 'z1') {
													echo "<b>Stok Opname</b>";
												}elseif ($row->tipe == 'ecer1') {
													echo "<b>Ubah ke stok eceran</b>";
													/* if (is_posisi_id() < 3) { ?>
														<span class='tipe' hidden><?=$row->tipe?></span>
														<span class='id' hidden ><?=$row->trx_id?></span>
														<span class='toko_id' hidden ><?=$row->toko_id?></span>
														<span class='qty-data' hidden><?=$row->qty_data?></span>
														<a href="#portlet-config-eceran-edit" data-toggle='modal' class='btn btn-xs blue btn-eceran-edit'><i class='fa fa-edit'></i></a>
														<button class='btn btn-xs red btn-eceran-remove' onclick="removeEceran('<?=$row->trx_id;?>')"><i class='fa fa-times'></i></button>
													<? } */
												}elseif ($row->tipe == 'so') {?>
													STOK OPNAME	
												<?}?>
											</td>
											<td>
												<?if ($row->tipe != 'z1' && $row->tipe != 1 && $row->tipe != 'so') {?>
													<?=($row->qty_masuk == 0 ?  '-' : "<span class='qty'>".$row->qty_masuk."</span>" );?>
													<?$qty += $row->qty_masuk;?>
													<?$total_in += $row->qty_masuk;?>
												<?}else if($row->tipe == 1){?>
													<?=($row->qty_masuk == 0 ?  '-' : "<span class='qty'>".$row->qty_masuk."</span>" );?>
													<?$qty += ($row->qty_masuk*$row->jumlah_roll_masuk);?>
													<?$total_in += ($row->qty_masuk*$row->jumlah_roll_masuk);
												}else{
													$qty_so = $row->qty_masuk - $qty;
													$qty = $row->qty_masuk;
													$total_in += $qty_so;
													echo "<span class='qty'>".$qty_so."</span>";

												}?>
											</td>
											<td>
												<?if ($row->tipe != 'z1' && $row->tipe != 'so') {?>
													<?=($row->jumlah_roll_masuk == 0 ? '-' : "<span class='jumlah_roll'>".$row->jumlah_roll_masuk."</span>" ) ;?>
													<?$roll += $row->jumlah_roll_masuk;?>
												<?}else{
													$roll_so = $row->jumlah_roll_masuk - $roll;
													$roll += $roll_so;
													echo "<span class='jumlah_roll'>".$roll_so."</span>";
												}?>
											</td>

											<td>
												<?	echo ($row->qty_keluar == 0 ? '-' : "<span class='qty'>".$row->qty_keluar."</span>" ); 
													$qty -= $row->qty_keluar;
													if ($row->tipe==2) {
														$total_out += ($row->qty_keluar*$row->jumlah_roll_keluar);
													}else{
														$total_out += $row->qty_keluar;
													}
												?>
												
											</td>
											<td>
												<?=($row->jumlah_roll_keluar == 0 && $row->qty_keluar == 0 ? '-' : "<span class='jumlah_roll'>".$row->jumlah_roll_keluar."</span>" ) ;?>
												<?$roll-= $row->jumlah_roll_keluar;?>
											</td>
											<td <? if ($qty < 0): echo "style='color:red'"; endif ?>>
												<?if ($row->tipe != 'z1' && $row->tipe != 'so') {?>
													<b><?=number_format($qty,'2',',','.');?></b> 
												<?}else if ($row->tipe == 'so') {?>
														<b><?=number_format($row->qty_masuk,'2',',','.');?></b> 
												<?}else{?>
													<b><?=number_format($row->qty_masuk,'2',',','.');?></b> 
												<?}?>
											</td>
											<td <? if ($roll < 0): echo "style='color:red'"; endif ?>>
												<?if ($row->tipe != 'z1' && $row->tipe != 'so') {?>
													<b><?=number_format($roll,'2',',','.');?></b> 
												<?}else if ($row->tipe == 'so') {?>
													<b><?=number_format($row->jumlah_roll_masuk,'2',',','.');?></b> 
												<?}else{?>
												<b><?=number_format($row->jumlah_roll_masuk,'2',',','.');?></b> 
												<?}?>
											</td>
										</tr>
									<? } ?>
									<tr>
										<td></td>
										<td></td>
										<td><?=$total_in;?></td>
										<td></td>
										<td><?=$total_out;?></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>

								</tbody>
							</table>
						</div>

						<?
							$qty = 0;
							$roll = 0;

							?>

						

						<div id='kartu-stok-eceran'>
							<h3>Kartu Stok Eceran <?=$tanggal_start ?> - <?=$tanggal_end?> </h3>
							<table class="table table-striped table-bordered table-hover" id="general_table_eceran">
								<thead>
									<tr>
										<th scope="col" rowspan='2'>
											Tanggal
										</th>
										<th scope="col" rowspan='2'>
											Keterangan
										</th>
										<th scope="col" class="text-center">
											Masuk (<?=$nama_satuan?>)
										</th>
										<th scope="col" class="text-center">
											Keluar (<?=$nama_satuan?>)
										</th>
									</tr>
								</thead>
								<tbody>
									<?
									$total_in_ecer = 0 ;
									$total_out_ecer = 0 ;
									foreach ($kartu_stok_eceran as $row) { 
										$total_in_ecer += $row->qty_in;
										$total_out_ecer += $row->qty_out;
										?>
										<tr>
											<td>
												<?=date('d F Y', strtotime($row->tanggal));?>
											</td>
											<td>
												<?=$row->keterangan?>
											</td>
											<td class="text-center">
												<?=($row->qty_in == 0 ? "" : (float)$row->qty_in)?>
											</td>
											<td class="text-center">
												<?=($row->qty_out == 0 ? "" : (float)$row->qty_out)?>
											</td>
										</tr>
									<? } ?>
									<tr>
										<td></td>
										<td></td>
										<td class="text-center"><?=$total_in_ecer;?></td>
										<td class="text-center"><?=$total_out_ecer;?></td>
									</tr>

								</tbody>
							</table>
						</div>

						<div id='kartu-stok-eceran-detail' <?=(is_posisi_id() != 1 ? 'hidden' : '')?> >
							<h3>Kartu Stok Eceran Detail <?=$tanggal_start ?> - <?=$tanggal_end?> </h3>
							<table class="table table-striped table-bordered table-hover" id="general_table_eceran_detail">
								<thead>
									<tr>
										<th scope="col" rowspan='2'>
											Tanggal
										</th>
										<th scope="col" rowspan='2'>
											Keterangan
										</th>
										<th scope="col" class="text-center">
											Masuk (<?=$nama_satuan?>)
										</th>
										<th scope="col" class="text-center">
											Keluar (<?=$nama_satuan?>)
										</th>
										<th scope="col" class="text-center">
											Saldo
										</th>
									</tr>
								</thead>
								<tbody>
									<?
									foreach ($kartu_stok_eceran_detail as $row) {
										print_r($row); ?>
										<tr>
											<td></td>
											<td></td>
											<td><?=(float)$row->qty_masuk?></td>
											<td><?=(float)$row->qty_jual?></td>
											<td></td>
										</tr>
									<? } ?>
									

								</tbody>
							</table>
						</div>
						
						<div id='detail-stok' hidden>
							<h4>Filter:</h4>
							<?foreach ($stok_detail as $row) {
								if (!isset($btn_toko[$row->toko_id])) {
									$btn_toko[$row->toko_id] = true;
									?>
										<button class='btn blue btn-md default' id='btnToko<?=$row->toko_id?>' data-status='1' onclick="filterToko('<?=$row->toko_id?>')"><?=$row->nama_toko;?> <span class='view-stat'><i class='fa fa-eye-slash'></i></span></button>
									<?
								}
							}

							foreach ($stok_detail as $row) {
								if (!isset($btn_supplier[$row->supplier_id])) {
									$btn_supplier[$row->supplier_id] = true;
									?>
										<button class='btn green btn-md default' id='btnSupplier<?=$row->supplier_id?>' data-status='1'  onclick="filterSupplier('<?=$row->supplier_id?>')" ><?=$row->nama_supplier;?> <span class='view-stat'><i class='fa fa-eye-slash'></i></span></button>
									<?
								}
							}
							
							?>

							<table class='table' id='table-list'>
								<thead>
									<tr>
										<th><?=$nama_satuan?></th>
										<th><?=$nama_packaging?></th>
										<th>Subtotal</th>
										<th>Supplier</th>
										<th>Toko</th>
									</tr>

								</thead>
								<tbody>
									<?
									$qty_total = 0; $roll_total = 0;
									foreach ($stok_detail as $row) {
										if ($row->jumlah_roll != 0) {
											$qty_total += $row->qty * $row->jumlah_roll;
											$roll_total += $row->jumlah_roll;
											?>
											<tr class='showed toko-<?=$row->toko_id?> supplier-<?=$row->supplier_id?>'>
												<td class='qty-detail'><?=str_replace('.000', '', $row->qty);?></td>
												<td class='roll-detail'><?=$row->jumlah_roll;?></td>
												<td class='qty-subtotal'><?=str_replace('.000', '', $row->qty*$row->jumlah_roll );?></td>
												<td><?=$row->nama_supplier;?></td>
												<td><?=$row->nama_toko;?></td>
											</tr>
										<?}?>
									<?}?>
								</tbody>
								<tfoot>
									<tr>
										<td>
										</td>
										<td>
											<b id='roll-total'><?=$roll_total;?></b><br/>
											<span id='roll-total-info'>of <?=$roll_total;?></span>
										</td>
										
										<td>
											<b id='qty-total'><?=$qty_total;?></b><br/>
											<span id='qty-total-info'> of <?=$qty_total;?></span>
										</td>
										<td></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
						</div>
						
						<hr/>
						<div id="stok-eceran">
							<h1>STOK ECERAN</h1>
							<table id='table-eceran'>
							<?
								$stok_eceran_satuan = [];
								$total_stok_eceran = 0;
								$stok_eceran_data = [];
								foreach ($stok_barang_eceran as $row) {
									$total_stok_eceran += $row->qty;
									array_push($stok_eceran_data, array(
										'tanggal' => $row->tanggal,
										'qty' => $row->qty,
										'qty_in' => $row->qty_in,
										'id' => $row->stok_eceran_qty_id,
										'qty_out' => explode(",", $row->qty_out),
										'tanggal_jual' => explode(",", $row->tanggal_jual),
										'nama_customer' => explode("??", $row->nama_customer)
									));
								}?>
							<tr>
								<th colspan='5'>TOTAL</th>
								<th colspan='5'><?=(float)$total_stok_eceran;?></th>
							</tr>
							<?
								$baris = ceil(count($stok_eceran_data)/10);
								for ($i=0; $i < $baris ; $i++) { 
									echo '<tr>';
									for ($j=0; $j < 10 ; $j++) { 
										$dt = '';
										if (isset($stok_eceran_data[($i*10) + $j])) {
											$dt = $stok_eceran_data[($i*10) + $j];
											echo "<td class='qty-eceran' id='eceran-".$dt['id']."'  style='position:relative' onclick='showEceranDetail(".$dt['id'].")'>";
										}else{
											echo "<td>";
										}
											if (is_array($dt) > 0) {
												echo (float)$dt['qty'];
												$sld = $dt['qty_in'];?>
												<div class='qty-eceran-data' hidden>
													<table>
														<thead>
															<tr>
																<th></th>
																<th>Tgl</th>
																<th>In</th>
																<th>Out</th>
																<th>Sisa</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>1</td>
																<td><?=is_reverse_date($dt['tanggal'])?></td>
																<td><?=(float)$dt['qty_in']?></td>
																<td></td>
																<td><?=(float)$sld?></td>
															</tr>
															<?foreach ($dt['tanggal_jual'] as $key => $value) {
																if ($dt['qty_out'][$key] != '') {
																	$sld -= $dt['qty_out'][$key];?>
																	<tr>
																		<td>
																			<?=$key+2?>
																		</td>
																		<td>
																			<?=is_reverse_date($value)?><br/>
																			<!-- <small><?=$dt['nama_customer'][$key]?></small> -->
																		</td>
																		<td></td>
																		<td>
																			<?=(float)$dt['qty_out'][$key]?>
																		</td>
																		<td><?=(float)$sld?></td>
																	</tr>
																<?}
															}?>
														</tbody>
													</table>
												</div>
											<?}
										echo '</td>';
									}
									echo '</tr>';
								}
							?>
							</table>
						</div>
							
						<div>
		                	<a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a>
		                	<button onclick='window.print()' class="btn blue hidden-print"><i class='fa fa-print'></i> Print</button>
						</div>
						
						<div id="kartu-stok-copy">
							
							<table id="general_table_copy">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th colspan='7'><span><?=$tanggal_start.' - '.$tanggal_end;?></span></th>
									</tr>
									<tr>
										<th>Barang</th>
										<th colspan='7'><span><?=$nama_jual.' '.$warna_jual;?></span></th>
									</tr>
									<tr>
										<th>Lokasi</th>
										<th colspan="7"><?=$nama_gudang;?></th>
									</tr>
									<tr>
										<th colspan="8"></th>
									</tr>
									<tr>
										<th scope="col" rowspan='2'>
											Tanggal
										</th>
										<th scope="col" rowspan='2'>
											Keterangan
										</th>
										<th scope="col" colspan='2'>
											Masuk
										</th>
										<th scope="col" colspan='2'>
											Keluar
										</th>
										<th colspan='2'>
											Saldo
										</th>
									</tr>
									<tr>
										<th scope="col">
											<?=$nama_satuan?>
										</th>
										<th scope="col">
											<?=$nama_packaging?>
										</th>
										<th scope="col">
											<?=$nama_satuan?>
										</th>
										<th scope="col">
											<?=$nama_packaging?>
										</th>
										<th scope="col">
											<?=$nama_satuan?>
										</th>
										<th scope="col">
											<?=$nama_packaging?>
										</th>
	
									</tr>
								</thead>
								<tbody>
									<?
									$total_in = 0;
									$total_out = 0;
									// print_r($stok_awal);
									foreach ($stok_awal as $row) { ?>
										<tr>
											<td>
												<b>Stok Awal</b>
											</td>
											<td></td>
											<td>
												<?=($row->qty_masuk == 0 ?  '-' :  $row->qty_masuk );?>
												<?$qty += $row->qty_masuk;?>
												<?$total_in += $row->qty_masuk;?>
											</td>
											<td>
												<?=($row->jumlah_roll_masuk == 0 ? '-' : $row->jumlah_roll_masuk ) ;?>
												<?$roll += $row->jumlah_roll_masuk;?>
	
											</td>
	
											<td>
												<?=($row->qty_keluar == 0 ? '-' : $row->qty_keluar );?>
												<?$qty -= $row->qty_keluar;?>
												<?$total_out += $row->qty_keluar;?>
	
	
											</td>
											<td>
												<?=($row->jumlah_roll_keluar == 0 ? '-' : $row->jumlah_roll_keluar ) ;?>
												<?$roll-= $row->jumlah_roll_keluar;?>
	
											</td>
											<td <? if ($qty < 0): echo "style='color:red'"; endif ?>>
												<b><?=number_format($qty,'2',',','.');?></b> 
											</td>
											<td <? if ($roll < 0): echo "style='color:red'"; endif ?>>
												<b><?=number_format($roll,'0',',','.');?></b> 
											</td>
										</tr>
									<?}?>
									<?foreach ($stok_barang as $row) { ?>
										<tr >
											<td>
												<?=date('d F Y', strtotime($row->tanggal));?>
												<span class='tanggal' hidden><?=is_reverse_date($row->tanggal)?></span>
											</td>
											<td>
												<?if ($row->tipe == 'a1' || $row->tipe == 'a2' || $row->tipe == 'a3') {
													if (is_posisi_id() < 3) {
														if ($row->tipe == 'a1') { ?>
															<?=($row->no_faktur !='' ? $row->no_faktur : '???');?>
														<?}elseif ($row->tipe == 'a2') { ?>
															<?=($row->no_faktur !='' ? $row->no_faktur : '???');?>
														<?}elseif ($row->tipe == 'a3') { ?>
															<?=($row->no_faktur !='' ? $row->no_faktur : '???');?>
														<?}
													}else{
														echo $row->no_faktur;
													}
												}else if ($row->tipe == '1' || $row->tipe == '2' ) {
													$user = explode('??', $row->no_faktur);
													?>
													<!-- <?=$row->no_faktur;?> -->
													Penyesuaian (<small class='keterangan'><?=$user[3]?></small>) oleh: <b><?=$user[0];?></b> 
													<span class='user_id' hidden='hidden'><?=$user[1];?></span>
													<span class='penyesuaian_stok_id' hidden='hidden'><?=$user[2];?></span>
													<span class='tipe' hidden='hidden'><?=$row->tipe;?></span>
													
												<?}elseif ($row->tipe == 'b1') {
													echo "mutasi barang dari ".$row->no_faktur." ke ".$nama_gudang;
												}elseif ($row->tipe == 'b2') {
													echo "mutasi barang dari ".$nama_gudang." ke ".$row->no_faktur."";
												}elseif ($row->tipe == 'ask') {
													echo $row->no_faktur;
												}elseif ($row->tipe == 'asm') {
													echo $row->no_faktur;
												}else if($row->tipe == 0 && $row->tipe != 'b1' && $row->tipe != 'b2' && $row->tipe != 'z1' && $row->tipe != 'ecer1' && $row->tipe != 'so'){
													echo "Mutasi Stok Awal";
												}elseif ($row->tipe == 'z1') {
													echo "Stok Opname";
												}elseif ($row->tipe == 'ecer1') {
													echo "Ubah ke stok eceran";
												}elseif ($row->tipe == 'so') {?>
													STOK OPNAME	
												<?}?>
											</td>
											<td>
												<?if ($row->tipe != 'z1' && $row->tipe != 1 && $row->tipe != 'so') {?>
													<?=($row->qty_masuk == 0 ?  '-' : "<span class='qty'>".$row->qty_masuk."</span>" );?>
													<?$qty += $row->qty_masuk;?>
													<?$total_in += $row->qty_masuk;?>
												<?}else if($row->tipe == 1){?>
													<?=($row->qty_masuk == 0 ?  '-' : "<span class='qty'>".$row->qty_masuk."</span>" );?>
													<?$qty += ($row->qty_masuk*$row->jumlah_roll_masuk);?>
													<?$total_in += ($row->qty_masuk*$row->jumlah_roll_masuk);
												}else{
													$qty_so = $row->qty_masuk - $qty;
													$qty = $row->qty_masuk;
													$total_in += $qty_so;
													echo "<span class='qty'>".$qty_so."</span>";
	
												}?>
											</td>
											<td>
												<?if ($row->tipe != 'z1' && $row->tipe != 'so') {?>
													<?=($row->jumlah_roll_masuk == 0 ? '-' : "<span class='jumlah_roll'>".$row->jumlah_roll_masuk."</span>" ) ;?>
													<?$roll += $row->jumlah_roll_masuk;?>
												<?}else{
													$roll_so = $row->jumlah_roll_masuk - $roll;
													$roll += $roll_so;
													echo "<span class='jumlah_roll'>".$roll_so."</span>";
												}?>
											</td>
	
											<td>
												<?	echo ($row->qty_keluar == 0 ? '-' : "<span class='qty'>".$row->qty_keluar."</span>" ); 
													$qty -= $row->qty_keluar;
													if ($row->tipe==2) {
														$total_out += ($row->qty_keluar*$row->jumlah_roll_keluar);
													}else{
														$total_out += $row->qty_keluar;
													}
												?>
												
											</td>
											<td>
												<?=($row->jumlah_roll_keluar == 0 && $row->qty_keluar == 0 ? '-' : "<span class='jumlah_roll'>".$row->jumlah_roll_keluar."</span>" ) ;?>
												<?$roll-= $row->jumlah_roll_keluar;?>
											</td>
											<td <? if ($qty < 0): echo "style='color:red'"; endif ?>>
												<?if ($row->tipe != 'z1' && $row->tipe != 'so') {?>
													<b><?=number_format($qty,'2',',','.');?></b> 
												<?}else if ($row->tipe == 'so') {?>
														<b><?=number_format($row->qty_masuk,'2',',','.');?></b> 
												<?}else{?>
													<b><?=number_format($row->qty_masuk,'2',',','.');?></b> 
												<?}?>
											</td>
											<td <? if ($roll < 0): echo "style='color:red'"; endif ?>>
												<?if ($row->tipe != 'z1' && $row->tipe != 'so') {?>
													<b><?=number_format($roll,'2',',','.');?></b> 
												<?}else if ($row->tipe == 'so') {?>
													<b><?=number_format($row->jumlah_roll_masuk,'2',',','.');?></b> 
												<?}else{?>
												<b><?=number_format($row->jumlah_roll_masuk,'2',',','.');?></b> 
												<?}?>
											</td>
										</tr>
									<? } ?>
									<tr>
										<td></td>
										<td></td>
										<td><?=$total_in;?></td>
										<td></td>
										<td><?=$total_out;?></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
	
								</tbody>
							</table>
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

var qty_total_ori = "<?=$qty_total?>"
var roll_total_ori = "<?=$roll_total?>";

jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout

	document.addEventListener("click",function(e){
		if(!e.target.classList.contains('qty-eceran')){
			$(".qty-eceran-data").hide();
		}
	})

	$('.view-stat, #qty-total-info, #roll-total-info').hide();
	
	
   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=penyesuaian_stok_id]').val($(this).closest('tr').find('.penyesuaian_stok_id').html());
   		$('#form_edit_data [name=tanggal]').val(date_formatter($(this).closest('tr').find('.tanggal').html()));
   		$('#form_edit_data [name=qty]').val($(this).closest('tr').find('.qty').html());
   		$('#form_edit_data [name=jumlah_roll]').val($(this).closest('tr').find('.jumlah_roll').html());
   		$('#form_edit_data [name=keterangan]').val($(this).closest('tr').find('.keterangan').html());
   		var tipe = $(this).closest('tr').find('.tipe').html();
   		// alert(tipe);
   		$('#form_edit_data [name=tipe_transaksi][value='+tipe+']').prop('checked',true);
   		$.uniform.update($('#form_edit_data [name=tipe_transaksi]'));

   	});


   	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=tanggal]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=tanggal]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$('#general_table').on('click','.btn-remove', function(){
   		$('#form_remove_data [name=penyesuaian_stok_id]').val($(this).closest('tr').find('.penyesuaian_stok_id').html());
   		bootbox.confirm("Hapus penyesuaian stok ini ? ", function(respond){
   			if (respond) {
   				$('#form_remove_data').submit();
   			};
   		});

   	});

   	$('.btn-kartu').click(function(){
   		$('#kartu-stok').show();
   		$('#kartu-stok-eceran').hide();
   		$('#detail-stok').hide();
   	});

	$('.btn-kartu-eceran').click(function(){
   		$('#kartu-stok').hide();
   		$('#kartu-stok-eceran').show();
   		$('#detail-stok').hide();
   	});


   	$('.btn-detail').click(function(){
   		$('#detail-stok').show();
   		$('#kartu-stok-eceran').hide();
   		$('#kartu-stok').hide();
   	});

	$('.btn-add-qty').click(function(e){
		e.preventDefault();
		$("#qty-table tbody").append(`<tr>
			<td><input class='qty'></td>
			<td hidden><input class='roll'></td>
		</tr>`);
	})

	$('#form_edit_eceran').on('click','.btn-add-qty-edit', function(e){
		e.preventDefault();
		$("#qty-table-edit tbody").append(`<tr>
			<td><input class='qty'></td>
			<td hidden><input class='roll'></td>
		</tr>`);
	})

	$('.btn-eceran-edit').click(function() {
		const ini = $(this).closest('tr');
		const form = $('#form_edit_eceran');
		let qty_data= ini.find('.qty-data').html();
		form.find('[name=id]').val(ini.find('.id').html());
		form.find('[name=toko_id]').val(ini.find('.toko_id').html());
		form.find('[name=tanggal]').val(ini.find('.tanggal').html());
		form.find('[name=rekap_qty]').val(qty_data);
		$("#qty-table-edit tbody").html('');
		let baris = qty_data.split('??').length;

		qty_data.split('??').forEach((item,index) => {
			let q = item.split(',');
			if(index == 0){
				$("#qty-table-edit tbody").append(`<tr>
						<td><input class='qty'  tabindex='1' value='${parseFloat(q[0])}'></td>
						<td hidden><input class='roll' value='${q[1]}'><input class='qty_detail_id' value="${q[2]}"></td>
						<td><button class='btn btn-xs blue btn-add-qty-edit' style="margin-left:10px;" tabindex='-1' onclick="addQtyTableRowEdit()"><i class="fa fa-plus"></i></button></td>
					</tr>`);
			}else{
				$("#qty-table-edit tbody").append(`<tr>
						<td><input class='qty' tabindex='${parseFloat(index)+1}' value='${parseFloat(q[0])}'></td>
						<td hidden><input class='roll' value='${q[1]}'></td>
					</tr>`);
			}
		});

		for (let i = baris; i < parseFloat(baris) + 2; i++) {
			$("#qty-table-edit tbody").append(`<tr>
					<td><input class='qty'  tabindex='${parseFloat(i)+1}'></td>
					<td hidden><input class='roll' }'></td>
				</tr>`);
		}
		
	})
	
	$('.btn-eceran-remove').click(function() {
		
	})
});


function submitEceran(){
	btn_disabled_load($(".btn-save-eceran"));
	let rekapQty = "";
	let qtyList = [];
	let totalQty = 0;
	let totalRoll = 0;
	$("#qty-table tbody tr").each(function(){
		let q = $(this).find('.qty').val();
		let r = $(this).find('.roll').val();
		if (q != '') {
			if (r=='') {
				r=1;
			}
			qtyList.push(q+','+r);
			totalQty += parseFloat(q*r);
			totalRoll += parseInt(r);
		}
	})

	$('#qty-table').find(".total-qty-eceran").val(totalQty);
	$('#qty-table').find(".total-roll-eceran").val(totalRoll);

	if($('#form_add_eceran [name=tanggal]').val() != '' && parseFloat(totalQty) > 0 ){
		$("#form_add_eceran [name=rekap_qty]").val(qtyList.join('??'));
		$('#form_add_eceran').submit();
	}else{
		alert("Mohon cek tanggal dan qty, jika masih error, mohon hubungi admin system");
	}
}

function submitEceranEdit(){
	btn_disabled_load($(".btn-save-edit-eceran"));
	let rekapQty = "";
	let qtyList = [];
	let totalQty = 0;
	let totalRoll = 0;
	$("#qty-table-edit tbody tr").each(function(){
		let q = $(this).find('.qty').val();
		let r = $(this).find('.roll').val();
		let id = $(this).find('.qty_detail_id').val();
		if (typeof id === 'undefined') {
			id = 0;
		}
		if (q != '') {
			if (r=='') {
				r=1;
			}
			qtyList.push(q+','+r+','+id);
			totalQty += parseFloat(q*r);
			totalRoll += parseInt(r);
		}
	})
	
	$('#qty-table-edit').find(".total-qty-eceran").val(totalQty);
	$('#qty-table-edit').find(".total-roll-eceran").val(totalRoll);

	if($('#form_edit_eceran [name=tanggal]').val() != '' && parseFloat(totalQty) > 0 ){
		$("#form_edit_eceran [name=rekap_qty]").val(qtyList.join('??'));
		// console.log(qtyList.join('??'));
		$('#form_edit_eceran').submit();
	}else{
		alert("Mohon cek tanggal dan qty, jika masih error, mohon hubungi system admin");
	}
	
}

function removeEceran(id) {

	bootbox.confirm("Hapus perubahan stok eceran ini ? ", function(respond){
		if(respond){
			var data_st = {};
			var url = "inventory/remove_mutasi_eceran";
			data_st['id'] = id;
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// console.log(data_respond);
				if(data_respond == 'OK'){
					window.location.reload()
				}else{
					alert(data_respond);
				}
			});

		}
	})
}

function filterToko(toko_id){
	const isAktif = $(`#btnToko${toko_id}`).attr('data-status');
	const setStat = Math.abs((isAktif - 1) * -1);

	if (isAktif == true) {
		$(`#btnToko${toko_id}`).removeClass('blue');
		$(`#btnToko${toko_id}`).find('.view-stat').show();
	}else{
		$(`#btnToko${toko_id}`).addClass('blue');
		$(`#btnToko${toko_id}`).find('.view-stat').hide();
	}
	$(`#btnToko${toko_id}`).attr('data-status',setStat);

	filterListStok(`toko-${toko_id}`, setStat);
}

function filterSupplier(supplier_id){
	const isAktif = $(`#btnSupplier${supplier_id}`).attr('data-status');
	const setStat = Math.abs((isAktif - 1) * -1);

	if (isAktif == true) {
		$(`#btnSupplier${supplier_id}`).removeClass('green');
		$(`#btnSupplier${supplier_id}`).find('.view-stat').show();
	}else{
		$(`#btnSupplier${supplier_id}`).addClass('green');
		$(`#btnSupplier${supplier_id}`).find('.view-stat').hide();
	}
	$(`#btnSupplier${supplier_id}`).attr('data-status',setStat);

	filterListStok(`supplier-${supplier_id}`, setStat)
}

function filterListStok(className, setStat) {
	let qtyTotal = 0;
	let rollTotal = 0;
	console.log(className, setStat);
	$(`.${className}`).each(function(){
		const haveShowed = $(this).hasClass("showed");
		if (setStat == true) {
			if (!haveShowed) {
				$(this).addClass('showed').show();
			}
		}else {
			if (haveShowed) {
				$(this).removeClass('showed').hide();
			}
		}
	});

	$(".showed").each(function(){
		qtyTotal += parseFloat($(this).find('.qty-subtotal').html());
		rollTotal += parseFloat($(this).find('.roll-detail').html());
	});

	if (qtyTotal != qty_total_ori) {
		$("#qty-total-info, #roll-total-info").show();
	}else{
		$("#qty-total-info, #roll-total-info").hide();
	}

	$("#qty-total").html(qtyTotal);
	$("#roll-total").html(rollTotal);
}

function showEceranDetail(ecId){
	$(".qty-eceran-data").hide();
	$(".qty-eceran").removeClass('qty-eceran-aktif');
	$(`#eceran-${ecId} .qty-eceran-data`).show();
	$(`#eceran-${ecId}`).addClass('qty-eceran-aktif');
}

</script>

<!-- Copy Paste -->
<script>
const table = document.querySelector("#kartu-stok-copy");
function copyTableAsHTML() {

	table.style.display = "block";

	table.querySelectorAll("a").forEach(link => {
		link.replaceWith(link.textContent);
	});
	
  const range = document.createRange();
  range.selectNode(table);
  
  const selection = window.getSelection();
  selection.removeAllRanges();
  selection.addRange(range);
  
  document.execCommand("copy");
  selection.removeAllRanges();

	table.style.display = "none";

  
  notific8("lime","Table berhasil do copy!");
}

function copyTableAsText() {
  let text = "";
  
  for (let row of table.rows) {
    let rowData = Array.from(row.cells).map(cell => cell.textContent).join("\t");
    text += rowData + "\n";
  }
  
  navigator.clipboard.writeText(text).then(() => {
    alert("Table copied as plain text!");
  }).catch(err => {
    console.error("Failed to copy table: ", err);
  });
}

function copyTableAsCSV() {
  let csv = "";
  
  for (let row of table.rows) {
    let rowData = Array.from(row.cells).map(cell => `"${cell.textContent}"`).join(",");
    csv += rowData + "\n";
  }
  
  navigator.clipboard.writeText(csv).then(() => {
    alert("Table copied as CSV!");
  }).catch(err => {
    console.error("Failed to copy table: ", err);
  });
}

</script>
