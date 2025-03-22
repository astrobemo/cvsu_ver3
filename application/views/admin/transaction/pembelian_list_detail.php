<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#qty-table-edit input{
	width: 60px;
	padding-left: 5px
}

#qty-table input{
	width: 70px;
	padding-left: 5px
}

#qty-table .nama_satuan, #qty-table .nama_packaging, #qty-table-edit .nama_satuan, #qty-table-edit .nama_packaging{
	text-align: center;
}

.yard-info{
	font-size: 1.5em;
}
</style>

<div class="page-content">
	<div class='container'>

		<?
			$pembelian_id = '';
			$supplier_id = '';
			$nama_supplier = '';
			$gudang_id = '';
			$nama_gudang = '';
			$no_faktur = '';
			$no_surat_jalan = '';
			$ockh = '';
			$tanggal = '';
			$tanggal_sj = '';
			$ori_tanggal = '';
			$toko_id = '';
			$nama_toko = '';
			
			$jatuh_tempo = '';
			$ori_jatuh_tempo = '';
			$diskon = 0;
			$status = 0;
			$status_aktif = 0;
			$keterangan = '';

			$po_pembelian_batch_id = '';
			$po_number = '';
			$status = 0;
			$toko_id = 1;

			foreach ($pembelian_data as $row) {
				$pembelian_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$gudang_id = $row->gudang_id;
				$nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;
				$no_surat_jalan = $row->no_surat_jalan;

				$tanggal = is_reverse_date($row->tanggal);
				$tanggal_sj = is_reverse_date($row->tanggal_sj);
				$ori_tanggal = $row->tanggal;
				$toko_id = $row->toko_id;
				$nama_toko = $row->nama_toko;
				
				$jatuh_tempo = is_reverse_date($row->jatuh_tempo);
				$ori_jatuh_tempo = $row->jatuh_tempo;

				$diskon = $row->diskon;
				$status = $row->status;
				$status_aktif = $row->status_aktif;
				$keterangan = $row->keterangan;

				$po_pembelian_batch_id = $row->po_pembelian_batch_id;
				// $po_number = $row->po_number;
				$ockh = $row->ockh;
				$status = $row->status;
				$toko_id = $row->toko_id;

			}

			$readonly = ''; $disabled = '';
			if (is_posisi_id() == 6) {
				$readonly = 'readonly';
				$disabled = 'disabled';
			}

		?>

		<div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='pembelian_id' value='<?=$pembelian_id;?>' hidden>
									<input name='pin' type='password' id="pin_user" class="form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-request-open">OPEN</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

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
					                <select name="toko_id" class='form-control' id='toko-id' onchange="tokoChange('1')">
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
						<form action="<?=base_url('transaction/pembelian_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit Data</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" name="pembelian_id" value='<?=$pembelian_id;?>' hidden/>
			                    	<select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?if ($supplier_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
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
			                    			<option <?if ($gudang_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
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
			                    <label class="control-label col-md-3">No Faktur
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur" value="<?=$no_faktur;?>"/>
			                    	<div class='note-change-faktur note note-info' hidden>
					                	Nota berubah, jangan lupa cek tanggal di atas
					                </div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Surat Jalan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=$tanggal_sj;?>" name="tanggal_sj"/>
			                    </div>
			                </div> 	

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Surat Jalan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_surat_jalan" value="<?=$no_surat_jalan?>"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">PO Pembelian
			                    </label>
			                    <div class="col-md-6">
			                		<select name='po_pembelian_batch_id' class='form-control' id="po_list">
			                			<option value=''>Non PO</option>

			                		</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="keterangan" value="<?=$keterangan?>"/>
			                		<!--<img src="<?=base_url()?>image/loading.gif">-->
			                    </div>
			                </div>

			                <!-- <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="ockh" value="<?=$ockh?>"/>
			                    </div>
			                </div>   --> 


			                <div class="form-group">
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control' id='toko-id-edit' onchange="tokoChange('2')">
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
						<form action="<?=base_url('transaction/pembelian_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
	                    	<input name='pembelian_detail_id' hidden>
	                    	<input name='po_pembelian_batch_id' value="<?=$po_pembelian_batch_id;?>" hidden>
	                    	<input name='pembelian_id' value='<?=$pembelian_id;?>' hidden>

	                    	<?if ($po_pembelian_batch_id != '') {?>
	                    		<div class="form-group">
				                    <label class="control-label col-md-3">PO Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($barang_list as $row) { ?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>"><?=$row->nama;?> <?=$row->nama_warna;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' hidden>
				                    		<?foreach ($barang_list as $row) { ?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>">satuan??<?=$row->harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>	
	                    	<?}else{?>
								<div class="form-group">
				                    <label class="control-label col-md-3">Kode Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->barang_list_aktif as $row) {?>
													<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' hidden>
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->nama_packaging;?>??<?=$row->harga_beli;?>??<?=$row->pengali_harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>	                
				                
				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
		                    			<select name="warna_id" class='form-control' id='warna_id_select'>
			                				<option value=''>Pilihan..</option>
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div> 
	                    	<?}?>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input type="text" class='form-control amount_number_comma' name="harga_beli"/>
		                			<small style="font-size:0.9em">Format Numbering cth : <b>10,000.50</b></small>
		                			<input name='rekap_qty' hidden>
			                    </div>
			                </div>

                    	    <div class="form-group">
			                    <label class="control-label col-md-3">Pengali<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="pengali_type" class='form-control input1' id='pengali_type_select'>
			                    	</select>
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-add-qty">Add Qty</button>
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
						<form action="<?=base_url().trim(base64_encode('transaction/pembelian_list_detail'));?>" class="form-horizontal" id="form_search_faktur" method="post">
							<h3 class='block'> Cari Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='pembelian_id' id="search_no_faktur" class="form-control select2">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-search-faktur">GO!</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-qty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-body">
						<table width='100%'>
							<tr>
								<td>
									<table id='qty-table'>
										<tr>
											<th class='nama_satuan'>Yard</th>
											<th class='nama_packaging'>Roll</th>
											<th style='text-align:center'>Subtotal</th>
											<th></th>
										</tr>
										<tr>
											<td><input name='qty' class='input1'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
									</table>
								</td>
								<td style='vertical-align:top'>
									<div class='yard-info'>
										TOTAL QTY: <span class='yard_total' >0</span> <span class='satuan-total'></span> <br/>
										TOTAL ROLL: <span class='jumlah_roll_total' >0</span> 
									</div>
								</td>
							</tr>
						</table>
									
						

					</div>

					<div class="modal-footer">
						<button type="button" disabled class="btn blue btn-active btn-trigger btn-brg-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-detail-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_detail_update')?>" class="form-horizontal" id="form_edit_barang" method="post">
							<h3 class='block'> Edit Barang</h3>
							
	                    	<input name='pembelian_detail_id' hidden>
	                    	<input name='po_pembelian_batch_id' value="<?=$po_pembelian_batch_id;?>" hidden>
	                    	<input name='pembelian_id' value='<?=$pembelian_id;?>' hidden>

	                    	<?if ($po_pembelian_batch_id != '') {?>
	                    		<div class="form-group">
				                    <label class="control-label col-md-3">PO Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($barang_list as $row) { ?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>"><?=$row->nama;?> <?=$row->nama_warna;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' hidden>
				                    		<?foreach ($barang_list as $row) { ?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>">satuan??<?=$row->harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>	
	                    	<?}else{?>
								<div class="form-group">
				                    <label class="control-label col-md-3">Kode Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->barang_list_aktif as $row) {?>
												<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' hidden>
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->nama_packaging;?>??<?=$row->harga_beli;?><?=$row->pengali_type?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>			                
				                
				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
		                    			<select name="warna_id" class='form-control' id='warna_id_select'>
			                				<option value=''>Pilihan..</option>
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div> 
	                    	<?}?>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input type="text" class='form-control amount_number_comma' name="harga_beli"/>
		                			<small style="font-size:0.9em">Format Numbering cth : <b>10,000.50</b></small>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Pengali<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="pengali_type" class='form-control' id='pengali_type_select_edit'>
			                    	</select>
			                    </div>
			                </div>
							<input name='rekap_qty' hidden>

						</form>
					</div>

					<div class="modal-footer">
						<a href='#portlet-config-qty-edit' data-toggle='modal' class="btn blue btn-active btn-trigger btn-edit-form-qty">Edit Qty</a>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-qty-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<div class='col-md-6 col-xs-12' style='height:400px;overflow:auto'>
							<table id='qty-table-edit'>
								<thead>
									<tr>
										<th>No</th>
										<th class='nama_satuan'>Yard</th>
										<th class='nama_packaging'>Roll</th>
										<th style='text-align:center'>Subtotal</th>
										<th></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<div class='yard-info' style='float:right;position:relative; font-size:2em'>
							TOTAL : <span class='yard_total' >0</span> <span class='satuan-total-edit'></span> <br/>
							TOTAL ROLL : <span class='jumlah_roll_total' >0</span>
						</div> 
						<span class='total_roll' hidden></span>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-brg-edit-save">Save</button>
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
							<?if (is_posisi_id() != 6) { ?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Pembelian Baru </a>
							<?}?>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<table>
							<?if ($pembelian_id != '') { ?>
								<tr>
									<td colspan='3'>
										<?if ($status == 0) { ?>
											<button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin'><i class='fa fa-key'></i> request open</button>
										<?}elseif (is_posisi_id() != 6 && $status != -1) { ?>
											<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
										<?}?>
									</td>
								</tr>
							<?}?>
							<tr>
					    		<td>No Faktur</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?=$no_faktur;?>
					    		</td>
					    	</tr>
					    	<tr>
					    		<td>Surat Jalan</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?=$no_surat_jalan;?>
					    		</td>
					    	</tr>
					    	<tr>
					    		<td>PO Number</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td  class='td-isi-bold'>
					    			<?=$po_number;?></td>
					    	</tr>
					    	<tr>
					    		<td>Tanggal</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'><?=$tanggal;?></td>
					    	</tr>
					    	<tr>
					    		<td>Toko</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?=$nama_toko;?>
					    		</td>
					    	</tr>
					    	<tr>
						    	<td>Gudang</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?=$nama_gudang;?>
					            </td>
				            </tr>
					    	<tr>
					    		<td>Supplier</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?=$nama_supplier;?>
					    		</td>
					    	</tr>
					    	<tr>
								<td>Catatan</td>
								<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'><?=$keterangan;?></td>
							</tr>

					    	
					    </table>

					    <hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Nama Barang
										<?if ($pembelian_id != '' && is_posisi_id() !=6 && $status == 1) { ?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Sat. Kecil
									</th>
									<th scope="col">
										Sat. Besar
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total Harga
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$i =1; $g_total = 0;
								$qty_total = 0; $roll_total = 0;
								foreach ($pembelian_detail as $row) { ?>
									<tr>
										<td>
											<?=$i;?> 
										</td>
										<td>
											<span class='nama_jual'><?=$row->nama_barang;?> <?=$row->nama_warna;?></span> 
										</td>
										<td>
											<b><?=(float)$row->qty;?></b>  <?=$row->nama_satuan?>
										</td>
										<td>
											<b><?=$row->jumlah_roll;?></b> <?=$row->nama_packaging?>
										</td>
										<td>
											<span class='harga_beli'><?=number_format($row->harga_beli,'2','.',',');?></span> 
										</td>
										<td>
											<?$subtotal = ($row->pengali_type == 1 ? $row->qty : $row->jumlah_roll) * $row->harga_beli;
											$g_total += $subtotal;
											$qty_total += $row->qty;
											$roll_total += $row->jumlah_roll;
											?>
											<span <?=$readonly;?> class='subtotal'><?=number_format($subtotal,'0','.',',');?></span> 
										</td>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='barang_id' hidden><?=$row->barang_id;?></span>
											<span class='warna_id' hidden><?=$row->warna_id;?></span>
											<span class='data_qty' hidden><?=$row->data_qty;?></span>
											<span class='pengali_type' hidden><?=$row->pengali_type;?></span>

											<?if(is_posisi_id() != 6 && $status==1){?>
												<a href='#portlet-config-detail-edit' data-toggle='modal' class="btn-xs btn green btn-detail-edit btn-qty-edit"><i class="fa fa-edit"></i> </a>
												<a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
											<?} ?>
											<!-- <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a> -->
										</td>
									</tr>
								<? $i++;} ?>
							</tbody>
						</table>

						<hr/>
							<p class='btn-detail-toggle' style='cursor:pointer'><b>Detail <i class='fa fa-caret-down'></i></b></p>
						
							<table id='general-detail-table' class='table table-bordered'>
								<thead>
									<tr>
										<th>Barang</th>
										<th>Keterangan</th>
										<th>Qty</th>
										<th>Total</th>
										<th>Detail</th>
									</tr>
								</thead>
								<?foreach ($pembelian_detail as $row) {?>
									<tr>
										<td><?=$row->nama_barang?></td>
										<td><?=$row->nama_warna?></td>
										<td><?=$row->jumlah_roll?></td>
										<td><?=(float)$row->qty;?></td>
										<td><?
											$data_qty = explode('--', $row->data_qty);
											$coll = 1;
											foreach ($data_qty as $key => $value) {
												$detail_qty = explode('??', $value);
												for ($i=1; $i <= $detail_qty[1] ; $i++) { 
													echo "<p style='display:inline-flex; width:50px; '>".(float)$detail_qty[0]."</p>";
													$coll++;
													if ($coll == 11) {
														echo "<hr style='margin:2px' />";
														$coll = 1;
													}
												}
											}
										?></td>
									</tr>
								<?}?>
							</table>
						<hr/>

						<table style='width:100%' id='rekap-table'>
							<tr>
								<td>
									<table>
										<tr>
											<td>Subtotal</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><span class='total'><?=number_format($g_total,'0','.',',');?></span> </td>
										</tr>
										<tr>
											<td>Diskon</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			Rp <input <?=$readonly;?> <?if ($pembelian_id =='') {?>readonly<?}?> name='diskon' class='amount_number_comma padding-rl-5 diskon' style='width:109px;' value="<?=number_format($diskon,'0','.',',');?>"> /
								    			<?$g_total = ($g_total == 0 ? 1 : $g_total);?>
								    			<input <?=$readonly;?> <?if ($pembelian_id =='') {?>readonly<?}?> name='diskon_persen' class='padding-rl-5 diskon-persen' style='width:50px;' value="<?=number_format($diskon/$g_total*100,'2','.','');?>"> %
								    		</td>
										</tr>
										<tr>
											<td>Jatuh Tempo</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			
								    			<?$style='';
								    			$diff = strtotime($ori_jatuh_tempo) - strtotime($ori_tanggal);
								    			$diff = $diff/(60*60*24);
								    			// echo $diff;
								    			if ($diff < 0) {
								    				$style = 'color:red';
								    			}?>
								    			<input name='jatuh_tempo'  <?=$readonly;?> <?if ($pembelian_id =='') {?>readonly<?}?> class="<?if ($pembelian_id !='' && is_posisi_id() != 6 ) {?>date-picker<?}?> padding-rl-5 jatuh_tempo" style='<?=$style;?>' value='<?=$jatuh_tempo;?>'></td>
										</tr>
									</table>
								</td>
								<td style='vertical-align:top;font-size:4em;' class='text-right'>
									<b>Rp <span class='g_total' style=''><?=number_format($g_total - $diskon,'0','.',',');?></span></b>
								</td>
							</tr>

						</table>
						<hr/>
						<div>
							<?if ($status == 1) {?>
								<a href="<?=base_url('transaction/pembelian_list_close')?>?id=<?=$pembelian_id?>" class='btn btn-lg yellow-gold hidden-print'><i class='fa fa-lock'></i> LOCK </a>
							<?}?>
			                <a class='btn btn-lg blue hidden-print'><i class='fa fa-print'></i> Print </a>

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
<script src="<?php echo base_url('assets_noondev/js/form-pembelian.js'); ?>" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {

	FormNewPembelian.init();
	FormEditPembelian.init();
	FormNewPembelianDetail.init();

	$('#barang_id_select,#barang_id_select_edit,#warna_id_select, #po_list').select2({
        allowClear: true
    });

	$("#portlet-config .modal-body").css('background-color',colorToko[1]);

    <?if ($pembelian_id != '' && is_posisi_id() != 6) { ?>
    	var map = {220: false};
		$(document).keydown(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[220]) {
		            $('#portlet-config-detail').modal('toggle');
		            setTimeout(function(){
			    		$('#barang_id_select').select2("open");
			    		// $('#form_add_barang .input1 .select2-choice').click();
			    	},700);
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
    <?}?>

	$(".btn-add-qty-row").click(function(){
		var baris = `<tr><td><input name='qty'></td>
						<td><input name='jumlah_roll'></td>
						<td><input name='subtotal' tabindex='-1'></td>
						<td></td>
					</tr><tr><td><input name='qty'></td>
						<td><input name='jumlah_roll'></td>
						<td><input name='subtotal' tabindex='-1'></td>
						<td></td>
					</tr>`;
		$('#qty-table').append(baris);
	});

    $("#form_edit_data [name=no_faktur]").change(function(){
    	$('#form_edit_data .note-change-faktur').show();
    });

    $('#general_table').on('click', '.btn-detail-edit', function(){
    	var ini = $(this).closest('tr');
    	var form = $('#form_edit_barang');
    	var barang_id = ini.find('.barang_id').html();
    	$("#barang_id_select_edit").val(barang_id).change();
    	form.find("[name=data_barang]").val(barang_id);

    	form.find("[name=pembelian_detail_id]").val(ini.find('.id').html());
    	form.find("[name=warna_id]").val(ini.find('.warna_id').html());
    	form.find("[name=warna_id]").change();
   		var data = $("#form_edit_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
   		let pengali_type = ini.find(".pengali_type").html();
   		let option = `<option value='1' ${(pengali_type == 1 ? 'selected' : '')} >${data[0]}</option>
   					<option value='2' ${(pengali_type == 2 ? 'selected' : '')} >${data[1]}</option>`;
    	// form.find("[name=qty]").val(ini.find('[name=qty]').val());
    	// form.find("[name=jumlah_roll]").val(ini.find('[name=jumlah_roll]').val());
    	form.find("[name=harga_beli]").val(ini.find('.harga_beli').html());
    	form.find('[name=rekap_qty]').val(ini.find('.data_qty').html());

    	$('#qty-table-edit .nama_satuan').html(data[0]);
    	$('.yard-info .satuan-total-edit').html(data[0]);
		$('#qty-table-edit .nama_packaging').html(data[1]);
		$("#pengali_type_select_edit").empty().append(option);

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
   		let pengali_type = data[3];
   		// alert(pengali_type);
   		let option = `<option value='1' ${(pengali_type == 1 ? 'selected' : '')} >${data[0]}</option>
   					<option value='2' ${(pengali_type == 2 ? 'selected' : '')} >${data[1]}</option>`;
   		// alert(data);
		$('#form_add_barang [name=harga_beli]').val(change_number_comma(data[2]));
		$('#form_add_barang .satuan_unit').html(data[0]+'/kg');
		$('#form_add_barang [name=satuan]').val(data[0]);
		$('#form_add_barang [name=packaging]').val(data[1]);
		$('#qty-table .nama_satuan').html(data[0]);
		$('.yard-info .satuan-total').html(data[0]);
		$('#qty-table .nama_packaging').html(data[1]);
		$("#pengali_type_select").empty().append(option);
    });

    $('#barang_id_select_edit').change(function(){
    	var barang_id = $('#barang_id_select_edit').val();
   		var data = $("#form_edit_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
   		let pengali_type = data[3];
   		let option = `<option value='1'>${data[0]}</option>
   					<option value='2'>${data[1]}</option>`;
   		// alert(data);
		$("#pengali_type_select").empty().append(option);
    });


    $('#general_table').on('change','.qty, .jumlah_roll,.harga_beli', function(){
    	var ini = $(this).closest('tr');
    	var data = {};
    	data['column'] = $(this).attr('name');
    	data['id'] =  ini.find('.id').html();
    	data['value'] = $(this).val();
    	var url = 'transaction/pembelian_detail_update';
    	// update_table(ini);
    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				var qty = ini.find('.qty').val();
				var harga_beli = reset_number_comma(ini.find('.harga_beli').val());

				ini.find('.subtotal').html(change_number_comma(qty*harga_beli));
				update_table();
			};
   		});
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

    <?if ($pembelian_id != '') { ?>
    	$(document).on('change','.diskon, .diskon-persen , .keterangan', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	if ($(this).attr('name') != 'diskon_persen') {
		    	data['column'] = $(this).attr('name');
		    	data['value'] = $(this).val();
		    	if ($(this).attr('name') == 'diskon') {
		    		var diskon =  $(".diskon").val();
		    		if(diskon == ''){diskon=0;data['value']=0;}
		    			<?if (is_posisi_id() == 1 ) {?>
							// alert(diskon);
						<?}?>
		    		var total =  reset_number_comma($("#rekap-table .total").html());
		    		var diskon_persen = diskon/total*100;
		    		$(".diskon-persen").val(parseFloat(diskon_persen.toFixed(2)));
		    	};
	    	}else if ($(this).attr('name') == 'diskon_persen') {
	    		var total =  reset_number_comma($("#rekap-table .total").html());
	    		var diskon_persen = $('.diskon-persen').val();
	    		var diskon =  diskon_persen * total / 100;
	    		$(".diskon").val(change_number_comma(diskon.toFixed(0)) );

		    	data['column'] = 'diskon';
		    	data['value'] = diskon.toFixed(0);
	    	}
	    	data['pembelian_id'] =  "<?=$pembelian_id;?>";
	    	var url = 'transaction/pembelian_data_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					update_table(ini);
				};
	   		});
	    });

	    $(document).on('change','.jatuh_tempo', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['ori_tanggal'] = "<?=$ori_tanggal;?>";
	    	data['pembelian_id'] =  "<?=$pembelian_id;?>";
	    	data['jatuh_tempo'] = $(this).val();
	    	var url = 'transaction/pembelian_jatuh_tempo_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// alert(data_respond);
				if (data_respond == 'OK') {
					$('.jatuh_tempo').css('color','black');
				}else{
					$('.jatuh_tempo').css('color','red');
				}
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
			var url = "transaction/get_search_no_faktur";
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
    	var id = $("#form_search_faktur [name=pembelian_id]").val();
    	var action = $("#form_search_faktur").attr('action');
    	if (id != '') {
    		window.location.replace(action+'/'+id);
    	};
    });


//=====================================qty pembelian edit=======================

	$("#qty-table").on('change','[name=qty],[name=jumlah_roll]',function(){
    	data_result = table_qty_update('#qty-table').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	if (total > 0) {
		    $('.btn-brg-save').attr('disabled',false);
    	}else{
    		$('.btn-brg-save').attr('disabled',true);
    	}


    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	$('#form_add_barang [name=rekap_qty]').val(rekap);
    });

	$("#qty-table").on('change','[name=subtotal]',function(){
		let ini = $(this);
		subtotal_on_change(ini)

		data_result = table_qty_update('#qty-table').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	if (total > 0) {
		    $('.btn-brg-save').attr('disabled',false);
    	}else{
    		$('.btn-brg-save').attr('disabled',true);
    	}

    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	$('#form_add_data [name=rekap_qty]').val(rekap);
	});

	$('.btn-brg-save').click(function(){
    	var ini = $(this);
    	var yard = reset_number_comma($('.yard_total').html());
    	if( yard > 0){
    		$('#form_add_barang').submit();
            btn_disabled_load(ini);

    	}
    });

//============================pembelian qty edit=============================

	$('.btn-qty-edit').click(function(){
		$('#qty-table-edit tbody').html('');
		var data_qty = $(this).closest('tr').find('.data_qty').html();
		$('#form-qty-update [name=rekap_qty]').val(data_qty);
		$('#form-qty-update [name=id]').val($(this).closest('tr').find('.id').html());

		var data_break  = data_qty.split('--');
		
		var i = 0; var total = 0; var idx = 1;
		if (data_qty !='') {
			$.each(data_break, function(k,v){
				console.log('break:'+k+','+v);
				var qty = v.split('??');
				if (qty[1] == null) {
					qty[1] = 0;
				};
				total += qty[0]*qty[1]; 
				if (i == 0 ) {
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='"+qty_float_number(qty[0])+"' class='input1'></td>"+
						"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
						"<td> = <input name='subtotal' value='"+qty[0]*qty[1]+"'></td>"+
						"<td hidden><input name='id' value='"+qty[2]+"'></td>"+
						"<td><button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button></td>"+
						"</tr>";
					idx++;
					$('#qty-table-edit tbody').append(baris);

				}else{
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='"+qty_float_number(qty[0])+"' ></td>"+
						"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
						"<td> = <input name='subtotal' value='"+qty[0]*qty[1]+"'></td>"+
						"<td hidden> = <input name='id' value='"+qty[2]+"'></td>"+
						"<td></td>"+
						"</tr>";
					idx++;

					$('#qty-table-edit tbody').append(baris);
				}

				i++;
			});
		};

		for (var i = 0; i < 5; i++) {
			var baris = "<tr>"+
					"<td>"+idx+"</td>"+
					"<td><input name='qty' value='' class='input1'></td>"+
					"<td><input name='jumlah_roll' value=''></td>"+
					"<td> = <input name='subtotal' value=''></td>"+
					"<td></td>"+
					"</tr>";
			idx++;
			$('#qty-table-edit tbody').append(baris);	
		};

		data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total_qty = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		if (total_qty > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
		$('#portlet-config-qty-edit .yard_total').html(total_qty.toFixed(2));

		$('#form_edit_barang [name=rekap_qty]').val(rekap);
	});
	
	$("#qty-table-edit").on('change',"[name=qty],[name=jumlah_roll]",function(){
		// alert('ok');
    	data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total_qty = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		if (total_qty > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
		$('#portlet-config-qty-edit .yard_total').html(total_qty.toFixed(2));

		$('#form_edit_barang [name=rekap_qty]').val(rekap);
    });

    $('#qty-table-edit').on('change','[name=subtotal]', function(){

    	let ini = $(this);

    	subtotal_on_change(ini);

    	data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total_qty = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		if (total_qty > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
		$('#portlet-config-qty-edit .yard_total').html(total_qty.toFixed(2));

		$('#form_edit_barang [name=rekap_qty]').val(rekap);
    });


    $('.btn-brg-edit-save').click(function(){
    	$('#form_edit_barang').submit();
    });

//========================================btn-detail============================

	$(".btn-detail-toggle").click(function(){
		$('#general-detail-table').toggle('slow');
	});

//============================open request=========================

	$('.btn-pin').click(function(){
    	setTimeout(function(){
	    	$('#pin_user').focus();    		
    	},700);
    });

    $('.btn-request-open').click(function(){
    	cek_pin();
    });

    $('#pin_user').keypress(function (e) {
        if (e.which == 13) {
        	cek_pin();
        }
    });


});

function cek_pin(){
	// alert('test');
	var data = {};
	data['pin'] = $('#pin_user').val();
	var url = 'transaction/cek_pin';
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		if (data_respond == "OK") {
			$('#form-request-open').submit();
		}else{
			alert("PIN Invalid");
		}
	}); 
}


//===============================outide on ready =============

function subtotal_on_change(pointer){
	var ini = pointer.closest('tr');
	let subtotal = pointer.val();
	let qty = ini.find('[name=qty]').val();
	let jumlah_roll = ini.find('[name=jumlah_roll]').val();
	if (qty != '' || jumlah_roll != '') {
		// alert('test');
		if (qty != '') {
			jumlah_roll = subtotal / qty;
			ini.find('[name=jumlah_roll]').val(jumlah_roll.toFixed(2));
		}else{
			qty = subtotal / jumlah_roll;
			ini.find('[name=qty]').val(qty.toFixed(3));
		}
	};
}

function update_table(){
	// alert('t');
	subtotal = 0;

	$('.subtotal').each(function(){
		subtotal+= parseFloat(reset_number_comma($(this).html()));
		// alert(subtotal);
	});

	$('.total').html(change_number_comma(subtotal));
	// alert(change_number_comma(subtotal));
	var diskon = reset_number_comma($('.diskon').val());
	if (diskon == '') {diskon=0;};
	<?if (is_posisi_id() == 1) {?>
		// alert(subtotal +'-'+diskon);
	<?}?>
	var g_total = subtotal - parseInt(diskon);
	$('.g_total').html(change_number_comma(g_total));

}

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

function table_qty_update(table){
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;
	$(table+" [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		var id = ini.find('[name=id]').val();
		if (typeof id === 'undefined') {
			id = '0';
		};
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
			rekap[idx] = qty+'??'+roll+'??'+id;
		}else if(id != 0){
			rekap[idx] = qty+'??'+roll+'??'+id;
			// alert(id);
		}
		idx++; 
		// alert(total_roll);
		total += subtotal;
		ini.find('[name=subtotal]').val(qty*roll);

	});

	rekap_str = rekap.join('--');
	// console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

function update_qty_edit(){
    var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
	$("#qty-table-edit [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		var id = ini.find('[name=id]').val();
		if (typeof id === 'undefined') {
			id = '0';
		};
		if (qty != '' && roll == '') {
			roll = 1;
			ini.find('[name=jumlah_roll]').val(roll)
		}else if(roll == 0){
			// alert('test');
			if (qty == '') {
				qty = 0;
				roll = 0;
			}
		}else if(qty == '' && roll == ''){
			roll = 0;
			qty = 0;
		}

		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		// alert(qty+'*'+roll);
    		total_roll += parseInt(roll);
		};

		if (qty != '' && roll != '' && id != '') {
			rekap[idx] = qty+'??'+roll+'??'+id;
		}else if(id != 0){
			rekap[idx] = qty+'??'+roll+'??'+id;
			// alert(id);
		}
		idx++;  
		total += subtotal;

	});
}

function tokoChange(tipe){
	if (tipe == 1) {
		let toko_id = $('#toko-id').val();
		$("#portlet-config .modal-body").css('background-color',colorToko[toko_id]);
	}else{
		let toko_id = $('#toko-id-edit').val();
		$("#portlet-config-edit .modal-body").css('background-color',colorToko[toko_id]);
		
	}
}

</script>
