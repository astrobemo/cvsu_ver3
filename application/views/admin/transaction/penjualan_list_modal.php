<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Penjualan Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control input1' name='penjualan_type_id'>
			                    		<?foreach ($penjualan_type as $row) { ?>
			                    			<option <?if ($row->id == 3) {echo 'selected';}?> value='<?=$row->id;?>'><?=$row->text;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 
			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">PO/Ket
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='po_number' maxlength='38' class='form-control'>
			                    </div>
			                </div> 

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='add-select-customer'  hidden>
			                    		<select name="customer_id" class='form-control' id='customer_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                    	</div>
			                    	<div id='add-nama-keterangan'>
				                    	<input name='nama_keterangan' class='form-control'>
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">Alamat
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='alamat_keterangan' class='form-control'>
			                    </div>
			                </div>

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='keterangan' maxlength='80' class='form-control'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">FP Status
			                    </label>
			                    <div class="col-md-6">
			                    	<label>
			                    	<input type='checkbox' name='fp_status' class='form-control' id='fp_status_add' value='1'>Ya</label>
			                    </div>
			                </div>
			                
						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger btn-save" title='Save & Buka di Tab Ini'>Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/penjualan_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Penjualan Edit</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$penjualan_id;?>' hidden>
			                    	<select class='form-control input1' name='penjualan_type_id'>
			                    		<?foreach ($penjualan_type as $row) { ?>
			                    			<option <?if ($penjualan_type_id == $row->id) {echo 'selected';}?> value='<?=$row->id;?>'><?=$row->text;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' value="<?=$tanggal;?>" >
			                    </div>
			                </div> 

			                <div class="form-group"  <?=($penjualan_type_id != 2 ? 'hidden' : '' )?> >
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' value="<?=$jatuh_tempo;?>" >
			                    </div>
			                </div> 

			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">PO/Ket
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='po_number' maxlength='38' class='form-control' value='<?=$po_number?>'>
			                    </div>
			                </div> 

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='edit-select-customer'  <?if ($penjualan_type_id == 3) { ?> hidden <?}?> >
			                    		<select name="customer_id" class='form-control' id='customer_id_select2'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { ?>
				                    			<option <?if ($customer_id == $row->id) {?>selected<?}?> value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                    	</div>
			                    	<div id='edit-nama-keterangan' <?if ($penjualan_type_id != 3) { ?> hidden <?}?> >
				                    	<input name='nama_keterangan' class='form-control' value="<?=$nama_keterangan;?>">
			                    	</div>
			                    </div>
			                </div> 

			                <div class="form-group edit-alamat-keterangan">
			                    <label class="control-label col-md-3">Alamat
			                    </label>
			                    <div class="col-md-6">
			                    	<textarea name='alamat_keterangan' maxlength='90' rows='4' class='form-control'><?=$alamat_keterangan;?></textarea>
			                    	<!-- <div>
				                    	<input name='alamat_keterangan' class='form-control' value="">
			                    	</div> -->
			                    </div>
			                </div>

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='keterangan' maxlength='90' class='form-control' value="<?=$keterangan;?>">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">FP Status
			                    </label>
			                    <div class="col-md-6">
			                    	<label>
			                    	<input type='checkbox' <?=($fp_status == 1 ? 'checked' : '');?> name='fp_status' class='form-control' id='fp_status_edit' value='1'>Ya</label>
			                    </div>
			                </div>
			                

			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
		<div class="modal fade bs-modal-lg" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<span class='barang_id_before' hidden></span>
			                    	<span class='warna_id_before' hidden></span>
			                    	<span class='gudang_id_before' hidden></span>
			                    	<span class='eceran_before' hidden></span>
			                    	<input name="pengali_harga" class='pengali_harga' hidden>
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
									<input id='eceran-mix' name="is_eceran_mix" hidden >
			                    	<input name='tanggal' value='<?=$tanggal;?>' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudang_id_select'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?if ($row->status_default == 1) {echo "selected";}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">TOKO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="toko_id" class='form-control ' id='toko_id_select' onchange="tokoChange('1')">
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama	;?></option>
			                    		<? } ?>
			                    	</select>
									<select id='toko_id_copy' hidden>
		                				<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->use_ppn	;?></option>
			                    		<? } ?>
			                    	</select>
									
			                    </div>
			                </div>	

							<div class="form-group">
			                    <label class="control-label col-md-3">PPN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<div class="checkbox-list">
									<label class='checkbox-inline'>
										<input disabled type="checkbox" value='1' id='ppn-cek' >Yes</label>
										<input name='use_ppn' id='ppn-value' <?=(is_posisi_id() != 1 ? 'hidden' : "")?> >
										<input name='ppn_berlaku' value="<?=$ppn_berlaku;?>" <?=(is_posisi_id() != 1 ? 'hidden' : "")?> >

									</div>
			                    </div>
			                </div>	

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<select name='data_barang' hidden>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->nama_packaging;?>??<?=$row->harga_jual;?>??<?=$row->pengali_harga_jual?>??<?=$row->harga_ecer?>??<?=$row->eceran_mix_status?></option>
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
			                    			<option value="<?=$row->id?>"><?=$row->warna_jual;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>

							<div class="form-group eceran-form ">
								<label class="control-label col-md-3">Eceran</label>
								<div class="col-md-6">
								<div class="checkbox-list">
									<label class='checkbox-inline'>
										<input type="checkbox" name='is_eceran' id='eceran-cek' >Yes</label>
									</div>
								</div>
							</div>

			                <div class="form-group" id='harga-dpp-group'>
			                    <label class="control-label col-md-3">Harga DPP<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="text" style='margin-bottom:0px;' class='form-control harga_jual_add_noppn amount_number_comma' id='harga_jual_add_noppn' name="harga_jual_noppn"/>
									<input hidden name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' : '' )?> >
									<label id='nama-satuan-keterangan' class='form-control' style='padding:0px 10px; height:20px; background:#eee; color:red; border:none'></label>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-3">
									<div class='input-group'>
										<input type="text" class='form-control harga_jual_add' id='harga_jual_add' name="harga_jual"/>
										<!-- <span hidden class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga amount_number_comma' data-trigger='click' title="History Pembelian Customer" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span> -->
									</div>
								</div>
								<div class='col-md-1'>
									<label class="control-label col-md-12">x</label>
								</div>
								<div class='col-md-2'>
									<input disabled type="text" class='form-control' id='qty-add'/>
									<input name='subqty' id='subqty-add' hidden/>
									<input name='subroll' id='subroll-add' hidden />
								</div>
			                </div>

							<div class="form-group">

			                    <label class="control-label col-md-3">SUBTOTAL<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input disabled type="text" class='form-control' id='subtotal-add-text' />
									<input id='subtotal-add' hidden name='subtotal_nilai' />
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Diskon<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name='subdiskon' id='subdiskon-add' name="subdiskon" oninput="setDiskonAdd()" />
									<!-- <input id='subdiskon-add' hidden /> -->
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">TOTAL<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input disabled type="text" class='form-control' id='subtotal-grand' />
			                    </div>
			                </div>
							<input name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' : '' )?>>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-qty" >Add Qty</button>
						<button disabled type="button" class="btn blue btn-active btn-trigger btn-brg-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-qty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class='note note-danger'>gunakan titik untuk decimal</div>

						<table width='100%'>
							<tr>
								<td width='50%' style='vertical-align:top' class='table-qty'>
									<span style='font-size:1.2em'>AMBIL</span>
									<table id='qty-table'>
										<thead>
											<tr>
												<th class='nama_satuan'>Yard</th>
												<th class='nama_packaging'>Roll</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
													<td><input name='qty' value='' class='input1'></td>
													<td><input name='jumlah_roll' value=''></td>
													<td><span class='nama_supplier'></span></td>
													<td hidden>
														<input name='supplier_id' value=''>
														<span class='qty-get'></span>
														<span class='roll-get'></span>
													</td>
													<td>
														<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button>
													</td>
												</tr>
											<?for($i = 0 ; $i < 9 ; $i++){?>
												<tr>
													<td><input name='qty' value=''></td>
													<td><input name='jumlah_roll' value=''></td>
													<td><span class='nama_supplier'></span></td>
													<td hidden>
														<input name='supplier_id' value=''>
														<span class='qty-get'></span>
														<span class='roll-get'></span>
													</td>
												</tr>
											<?} ?>
										</tbody>
									</table>

									<div class='yard-info'>
										TOTAL QTY: <span class='yard_total' >0</span> <span class='nama_satuan'>yard</span> <br/>
										TOTAL ROLL: <span class='jumlah_roll_total' >0</span> <span class='nama_packaging'>yard</span>
									</div>
								</td>
								<td style='vertical-align:top'>
									<table width='100%'>
										<tr>
											<td class='add-eceran' style='vertical-align:top'>
												<span style='font-size:1.2em'>STOK ECERAN</span>
												<div style="overflow:auto;">
													<table id='qty-table-eceran'>
														<thead>
															<tr>
																<th class='nama_satuan'  style='width:45px'></th>
																<th class='text-center'>AMBIL</th>
																<th class='text-center'>SISA</th>
															</tr>
														</thead>
														<tbody></tbody>
														<tfoot style='font-size:1.2em'>
															<tr>
																<th style='width:45px'>TOTAL</th>
																<th class='text-center total-ambil'></th>
																<th class='text-center'></th>
															</tr>
														</tfoot>
													</table>
													Info : <br/>
													- Ketika klik <button>add to eceran</button> <br/>Maka stok besar akan dirubah ke eceran <br/>
													- Mohon klik tombol <button class='btn btn-xs red'><i class='fa fa-times'></i></button> jika ingin membatalkan 

												</div>
												
											</td>
											<td>
												<span style='font-size:1.2em'>STOK BESAR</span>
												<div id='btn-stok-div'></div>
												<div style='height:340px; overflow:auto; padding-left:5px'>
													<table id='qty-table-stok'>
														<thead>
															<tr>
																<th class='nama_satuan'  style='width:45px'></th>
																<th class='nama_packaging' style='width:45px'></th>
															</tr>
														</thead>
														<tbody></tbody>
														
													</table>

												</div>
											</td>
										</tr>
									</table>
									
									<div class='stok-info add-eceran' id='stok-eceran-add' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK Eceran : <span class='stok-qty-eceran'>0</span>
									</div>

									<div class='stok-info' id='stok-info-add' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
									</div>

								</td>
							</tr>
						</table>

						
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger" data-dismiss="modal" id="qty-add-dismiss">Edit Diskon</button>
						<button disabled type="button" class="btn blue btn-active btn-trigger btn-brg-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-qty-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<?//GA BISA JADI EDIT BIASA KARENA TAKUT QTY NYA NGACO?>
						<form id="form_qty_edit" method='post' action="<?=base_url()?>transaction/penjualan_qty_detail_update">
	                    	<table>
	                    		<tr id="harga-dpp-group-edit">
	                    			<td>HARGA DPP</td>
	                    			<td class='padding-rl-5'></td>
	                    			<td>
										<input id="harga_jual_edit_noppn" name='harga_jual_noppn' class='form-control harga_jual_edit_noppn' >
									</td>
	                    		</tr>
	                    		<tr>
	                    			<td>HARGA JUAL</td>
	                    			<td class='padding-rl-5'></td>
	                    			<td><input id='harga_jual_edit' name='harga_jual' class='form-control amount_number_comma harga_jual_edit' ></td>
	                    		</tr>
	                    	</table>
							<input name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' : '' )?> >
	                    	<input name='penjualan_list_detail_id' hidden >
	                    	<input name='penjualan_id' hidden >
	                    	<input id='barang-id-edit' hidden >
	                    	<input id='warna-id-edit' hidden >
	                    	<input id='eceran-mix-edit' hidden >
	                    	<input name='penjualan_id' hidden >
	                    	<input name='is_eceran' id='eceran-cek-edit' style='background:#ddd' <?=(is_posisi_id() != 1 ? 'hidden' : "")?> >
							<input name='use_ppn' id='ppn-value-edit' <?=(is_posisi_id() != 1 ? 'hidden' : "")?> >
							<input name='subqty' id='subqty-edit' hidden/>
							<input name='subroll' id='subroll-edit' hidden />
							<input id='subtotal-edit' hidden name='subtotal_nilai' />
						</form>
						<hr/>
						<div class='note note-danger'>gunakan titik untuk decimal</div>
						<table width='100%'>
							<tr>
								<td class='table-qty-edit' width='50%' style='vertical-align:top'>
									<span style='font-size:1.2em'>AMBIL</span>
									<table id='qty-table-edit'>
										<thead>
											<tr>
												<th class='nama_satuan'></th>
												<th class='nama_packaging'></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
													<td><input name='qty' value='' class='input1'></td>
													<td><input name='jumlah_roll' value=''></td>
													<td><span class='nama_supplier'></span></td>
													<td hidden>
														<input name='supplier_id' value=''>
														<span class='qty-get'></span>
														<span class='roll-get'></span>
													</td>
													<td hidden><input name='penjualan_qty_detail_id' class='penjualan_qty_detail_id'></td>
													<td>
														<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row-edit'><i class='fa fa-plus'></i></button>
													</td>
												</tr>
											<?for($i = 0 ; $i < 6 ; $i++){?>
												<tr>
													<td><input name='qty' value=''></td>
													<td><input name='jumlah_roll' value=''></td>
													<td><span class='nama_supplier'></span></td>
													<td hidden>
														<input name='supplier_id' value=''>
														<span class='qty-get'></span>
														<span class='roll-get'></span>
													</td>
													<td hidden><input name='penjualan_qty_detail_id' class='penjualan_qty_detail_id'></td>
												</tr>
											<?} ?>
										</tbody>
									</table>

									<div class='yard-info-edit'>
										TOTAL QTY: <span class='yard_total' >0</span> <span class='nama_satuan'>yard</span> <br/>
										TOTAL ROLL: <span class='jumlah_roll_total' >0</span> <span class='nama_packaging'>roll</span>
									</div>
								</td>
								<td class='table-qty-edit' style='vertical-align:top'>
									<span style='font-size:1.2em'>STOK</span>
									<div id="btn-stok-div-edit"></div>
									<div style='height:240px; overflow:auto'>
										<table id='qty-table-stok-edit'>
											<thead>
												<tr>
													<th class='nama_satuan'  style='width:45px'></th>
													<th class='nama_packaging' style='width:45px'></th>
												</tr>
											</thead>
											<tbody></tbody>
											
										</table>
									</div>
									
									<div class='stok-info' id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
									</div>
								</td>
								<td class='edit-eceran-col'>
									<table width='100%'>
										<tr>
											<td class='edit-eceran' style="vertical-align:top" >
												<span style='font-size:1.2em'>STOK ECERAN</span>
												<div style="overflow:auto;">
													<table id='qty-table-eceran-edit'>
														<thead>
															<tr>
																<th class='text-center'>STOK</th>
																<th class='text-center'>AMBIL</th>
																<th class='text-center'>SISA</th>
															</tr>
														</thead>
														<tbody></tbody>
														<tfoot style='font-size:1.2em'>
															<tr>
																<th style='width:45px'>TOTAL</th>
																<th class='text-center total-ambil'></th>
																<th class='text-center'></th>
															</tr>
														</tfoot>
													</table>
												</div>
												
											</td>
											<td>
												<span style='font-size:1.2em'>STOK BESAR</span>
												<div style='min-height:200px; overflow:auto; padding-left:5px'>
													<table id='qty-table-stok-edit'>
														<thead>
															<tr>
																<th class='nama_satuan'  style='width:45px'></th>
																<th class='nama_packaging' style='width:45px'></th>
															</tr>
														</thead>
														<tbody></tbody>
														
													</table>

												</div>
											</td>
										</tr>
									</table>
									
									<div class='stok-info edit-eceran' id='stok-eceran-add' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK Eceran : <span class='stok-qty-eceran'>0</span>
									</div>

									<div class='stok-info' id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
									</div>
								</td>
							</tr>
						</table>
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

		<div class="modal fade" id="portlet-config-faktur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="" class="form-horizontal" id="form_search_faktur" method="get">
							<h3 class='block'> Cari Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='id' id="search_no_faktur" class="form-control select2">
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

		<div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
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

		<div class="modal fade" id="portlet-config-print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<h3 class='block'> Penjualan Baru</h3>
						
						<div class="form-group">
		                    <label class="control-label col-md-3">Type<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input name='print_target' hidden>
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) { ?>
		                    			<option value='<?=$row->id;?>'><?=$row->nama;?></option>
		                    		<?}?>
		                    	</select>
		                    </div>
		                </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-print-action" data-dismiss="modal">Print</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bd-modal-lg" id="portlet-config-dp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<b style='font-size:2em'>DAFTAR DP</b><hr/>
						<table class="table table-striped table-bordered table-hover" id='dp_list_table'>
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Deskripsi
									</th>
									<th scope="col">
										No Transaksi DP
									</th>
									<th scope="col">
										Nilai
									</th>
									<th scope="col">
										Dibayar
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<form id='form-dp' action="<?=base_url('transaction/pembayaran_penjualan_dp_update');?>" method="POST">
									<?
									$dp_bayar = 0;
									foreach ($dp_list_detail as $row) { ?>
										<tr>
											<td>
												<?=is_reverse_date($row->tanggal);?>
											</td>
											<td>
												<?=$row->bayar_dp;?> : 
												<?
												$type_2 = '';
												$type_3 = '';
												$type_4 = '';
												$type_6 = '';
												${'type_'.$row->pembayaran_type_id} = 'hidden';
												?>
												<ul>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_6;?> >Penerima :<b><span class='nama_penerima' ><?=$row->nama_penerima?></span></b></li>
													<li <?=$type_2;?> >Bank : <b><span class='nama_bank'><?=$row->nama_bank?></span></b></li>
													<li <?=$type_2;?> <?=$type_6;?> >No Rek : <b><span class='no_rek_bank'><?=$row->no_rek_bank?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?>>Jatuh Tempo : <b><span class='jatuh_tempo' ><?=is_reverse_date($row->jatuh_tempo);?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?> >No Giro : <b><span class='no_giro' ><?=$row->no_giro;?></span></b></li>
													<li>Keterangan : <b><span class='keterangan'><?=$row->keterangan;?></span></li></b>

												</ul>
											</td>
											<td>
												<span class='no_faktur_lengkap'><?=$row->no_faktur_lengkap;?></span>
											</td>
											<td>
												<span class='amount'><?=number_format($row->amount,'0','.',',');?></span>
											</td>
											<td>
												<?$dp_bayar += $row->amount_bayar;?>
												<input name="amount_<?=$row->id;?>" class='amount-bayar amount_number_comma' value='<?=number_format($row->amount_bayar,'0','.',',');?>' <?=($row->amount_bayar == 0 ? 'readonly' : '');?> style="width:80px">
											</td>
											<td>
												<input name="penjualan_id" value="<?=$penjualan_id;?>" hidden>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<input type="checkbox" class='dp-check' <?=($row->amount_bayar != 0 ? 'checked' : '');?> >
											</td>
										</tr>
									<? } ?>
									<tr>
										<td colspan='3'></td>
										<td><b>TOTAL</b></td>
										<td><span class='dp-total' style='font-size:1.3em'><?=number_format($dp_bayar,'0','.',',');?></span></td>
										<td></td>
									</tr>
								</form>

							</tbody>
						</table>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active green btn-save-dp" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<script>

		</script>