<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">

#form-get input, #form-get select{
	border:none; border-bottom:1px solid #ddd; width:100px;
}

#form-get select{
	border:none; border-bottom:1px solid #ddd; width:150px;
}

.block-info-bayar{
	display: block;
	background: #eee;
	padding: 10px;
	font-size: 1.2em;
	font-weight: bold;
	text-align: right;
}

.block-info-bayar:hover{
	text-decoration: none;
	font-weight: bold;
}

.info-bayar-div{
	border-bottom:1px solid #ccc;
	border-top:1px solid #ccc;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$pembayaran_hutang_id = '';
			$pembayaran_type_id = 1;
			
			$keterangan = '';
			$tanggal_giro = '';
			$jatuh_tempo = '';
			$tanggal_transfer = '';
			$nama_bank = '';
			$no_rek_bank = '';
			$no_giro = '';
			$nama_penerima = '';
			$toko_id = 1;
			$pembulatan = 0;

			$g_total = 0;
			$readonly = '';
			$disabled = '';

			$potongan_hutang = 0;

			foreach ($pembayaran_hutang_data as $row) {
				$pembayaran_hutang_id = $row->id;
				$pembulatan = $row->pembulatan;
				$potongan_hutang = $row->potongan_hutang;
				// $pembayaran_type_id = $row->pembayaran_type_id;
				
				// $keterangan = $row->keterangan;
				// $tanggal_giro = $row->tanggal_giro;
				// $jatuh_tempo = $row->jatuh_tempo;
				// $tanggal_transfer = is_reverse_date($row->tanggal_transfer);

				// $nama_penerima = $row->nama_penerima;
				// $nama_bank = $row->nama_bank;
				// $no_rek_bank = $row->no_rek_bank;
				$toko_id = $row->toko_id;
				$supplier_id = $row->supplier_id;
			}

			// if (is_posisi_id() == 6 ) {
			// 	$readonly = 'readonly';
			// 	$disabled = 'disabled';
			// }
			// if ($status != 1 ) {
			// 	$readonly = 'readonly';
			// }

			// if ($penjualan_id == '') {
			// 	$disabled = 'disabled';
			// }
		?>


		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-body">
						<form action='' id='form-get' method='get'>
							<table id='tbl-form-get' width='50%'>
								<?if ($pembayaran_hutang_id == '') { ?>
									<tr>
										<td>Tanggal: </td>
										<td class='padding-rl-5'> : </td>
										<td>
											<b>
												<input name='tanggal_start' class='date-picker' value='<?=$tanggal_start;?>'>
												s/d
												<input name='tanggal_end' class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											</b>
										</td>
									</tr>
								<?}else{?>
									<tr>
										<td>Tanggal: </td>
										<td class='padding-rl-5'> : </td>
										<td style='border-bottom:1px solid #ddd'>
											<b>
												<?=is_reverse_date($tanggal_start);?>
												s/d
												<?=is_reverse_date($tanggal_end);?>

											</b>
										</td>
									</tr>
								<?}?>
								<tr>
									<td>Toko </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select <?if ($pembayaran_hutang_id != '') {?> disabled <?}?> name='toko_id' id="toko_id_select" style='width:100%;'>
												<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->toko_list_aktif as $row) { ?>
													<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
								<tr>
									<td> Supplier </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select name='supplier_id'  <?if ($pembayaran_hutang_id != '') {?> disabled <?}?> id="supplier_id_select" style='width:100%;'>
												<option <?=($supplier_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->supplier_list_aktif as $row) { ?>
													<option <?=($supplier_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
							</table>
						</form>
						<?if ($pembayaran_hutang_id == '') {?>
							<button <?if ($toko_id == '' && $supplier_id == '') { ?> disabled <?}?> class='btn btn-xs default btn-form-get'><i class='fa fa-search'></i> Cari</button>
						<?}?>

						<!-- table-striped table-bordered  -->
						<hr/>
						<?if ($pembayaran_hutang_id == '') { ?>
							<form method="post" action="<?=base_url()?>finance/pembayaran_hutang_insert" id='form-bayar'>
						<?}?>
							<table class="table table-hover table-striped" id="general_table">
								<thead>
									<tr>
										<th scope="col">
											No
										</th>
										<th scope="col">
											No Faktur
										</th>
										<th scope="col">
											Tanggal Beli
										</th>
										<th scope="col">
											Jatuh Tempo
										</th>
										<th scope="col">
											Total Roll
										</th>
										<th scope="col">
											Total Hutang
										</th>
										<th scope="col">
											Bayar
										</th>
										<th scope="col" style='width:150px'>
											Sisa
										</th>
										<th scope="col" class='hidden-print'>
											Action
										</th>
									</tr>
								</thead>
								<tbody>
									<?
									$i =1; $total_hutang = 0; $total_roll = 0;
									foreach ($pembayaran_hutang_awal as $row) { ?>
										<tr>
											<td>
												<?=$i;?>
											</td>
											<td>
												<?=$row->no_faktur;?>
											</td>
											<td>
												<?=is_reverse_date($row->tanggal);?>
											</td>
											<td>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td>
												<span style="padding-left:25px"><?=$row->jumlah_roll;?></span> 
												<?$total_roll += $row->jumlah_roll; ?>
											</td>
											<td>
												<?$total_hutang += $row->sisa_hutang;?>
												<?//=$row->sisa_hutang;?>
												<span class='hutang'><?=number_format($row->sisa_hutang,'0',',','.');?></span>
											</td>
											<td>
												<? if ($pembayaran_hutang_id != '') { $amount = $row->amount;}else{$amount = 0;}?>
												<input <?=$readonly;?> name='hutang_<?=$row->pembelian_id;?>' class='amount_number bayar-hutang' value="<?=number_format($amount,'0',',','.');?>">
											</td>
											<td>
												<?$sisa = $row->sisa_hutang - $amount;?>
												<span <?=$readonly;?> class='sisa-hutang'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_hutang_id != '') { ?>
													<span class='pembayaran_hutang_detail_id' hidden><?=$row->id;?></span>
												<?}else{ ?>
													<input name='hawal_<?=$row->pembelian_id;?>' hidden value="<?=$row->pembelian_id;?>"> 
													<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->pembelian_id?>' class='lunas-check' >
													lunas </label>
												<?}?>
											</td>
										</tr>
									<?
									$i++; 
									} ?>

									<?
									foreach ($pembayaran_hutang_detail as $row) { ?>
										<tr>
											<td>
												<?=$i;?>
											</td>
											<td>
												<a href="<?=base_url().is_setting_link('transaction/pembelian_list_detail')?>/<?=$row->pembelian_id?>" target="_blank">
												<?=$row->no_faktur;?>
												</a>
											</td>
											<td>
												<?=is_reverse_date($row->tanggal);?>
											</td>
											<td>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td>
												<span style="padding-left:25px"><?=$row->jumlah_roll;?></span> 
												<?$total_roll += $row->jumlah_roll; ?>
											</td>
											<td>
												<?$total_hutang += $row->sisa_hutang;?>
												<?//=$row->sisa_hutang;?>
												<span class='hutang'><?=number_format($row->sisa_hutang,'0',',','.');?></span>
											</td>
											<td>
												<? if ($pembayaran_hutang_id != '') { $amount = $row->amount;}else{$amount = 0;}?>
												<input <?=$readonly;?> name='bayar_<?=$row->pembelian_id;?>' class='amount_number bayar-hutang' value="<?=number_format($amount,'0',',','.');?>">
											</td>
											<td>
												<?$sisa = $row->sisa_hutang - $amount;?>
												<span <?=$readonly;?> class='sisa-hutang'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_hutang_id != '') { ?>
													<span class='pembayaran_hutang_detail_id' hidden><?=$row->id;?></span>
												<?}else{?>
													<input name='data_status_<?=$row->pembelian_id;?>' hidden value='<?=$row->data_status;?>'>
													<input name='pembelian_id_<?=$row->pembelian_id;?>' hidden value="<?=$row->pembelian_id;?>"> 
													<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->pembelian_id?>' class='lunas-check' >
													lunas </label>
												<?}?>
											</td>
										</tr>
									<?
									$i++; 
									} ?>

									<tr style='font-size:1.2em; border-top:2px solid #171717; border-bottom:2px solid #171717;'>
										<td></td>
										<td></td>
										<td></td>
										<td>POTONGAN</td>
										<td></td>
										<td></td>
										<td><b><input name='potongan_hutang' id='potongan-hutang' class='amount_number' value='<?=number_format($potongan_hutang,'0',',','.')?>'></b></td>
										<td></td>
										<td>
										</td>

									</tr>

									<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc;'>
										<td></td>
										<td></td>
										<td></td>
										<td>TOTAL</td>
										<td><b><?=number_format($total_roll,'0',',','.');?></b></td>
										<td><b><span class='total_hutang'><?=number_format($total_hutang,'0',',','.');?></span></b> </td>
										<td><b><span class='total_bayar'></span></b></td>
										<td><b><span class='total_sisa_hutang'><?=number_format($total_hutang,'0',',','.');?></span></b></td>
										<td>
											<?if ($pembayaran_hutang_id =='') {?>
												<label>
												<input type='checkbox' name='check_all' id='check_all'> lunas all </label>| 
											<?}?>
											<!-- <button class='btn btn-sm red btn-reset-form'>reset all</button> -->
										</td>

									</tr>
								</tbody>
							</table>
							<hr/>
							<?if ($pembayaran_hutang_id != '') { ?>
								<form method="post" action="<?=base_url()?>finance/pembayaran_hutang_insert" id='form-bayar'>
							<?}?>
								<input name='supplier_id' value="<?=$supplier_id;?>" hidden>
								<input name='toko_id' value="<?=$toko_id;?>" hidden>
								<input name='pembayaran_hutang_id' value="<?=$pembayaran_hutang_id;?>" hidden>
							</form>

							<table width='100%'>
								<tr>
									<td style='vertical-align:top;'  width='45%'>
										
										<?
										$idx = 1; $total_bayar_hutang = 0;
										if ($pembayaran_hutang_id != '') { ?>
										<div class='list-group'>
											<?$title = '-';
											foreach ($pembayaran_hutang_nilai as $row) { 
												if ($row->pembayaran_type_id == 1) { $title = 'TRANSFER'; }
													elseif ($row->pembayaran_type_id == 2) { $title = 'GIRO'; }
														elseif ($row->pembayaran_type_id == 3) { $title = 'CASH'; }
												?>
												<div class='info-bayar-div'>
													<a class='block-info-bayar'> 
														<span style='font-size:0.8em;color:#aaa; position:absolute;left:50px;'><?=$idx;?></span> 
														<?=$title;?> : 
														<span style='color:black'> <?=number_format($row->amount,'0',',','.');?></span></a>
													<form method="post" action="<?=base_url()?>finance/pembayaran_hutang_nilai_update" id='form-bayar-nilai-update-<?=$row->id;?>'>
													
														<table class='bayar-info' width='100%;' style='margin-bottom:5px;' hidden >
															<tr>
																<td>Jenis Pembayaran</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 1) { echo 'checked'; }?> name='pembayaran_type_id' value="1">Transfer</label>
																	<label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 2) { echo 'checked'; }?> name='pembayaran_type_id' value="2">Giro</label>
																	<label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 3) { echo 'checked'; }?> name='pembayaran_type_id' value="3">Cash</label>
																	<!-- <label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4">EDC</label> -->
																</td>
															</tr>
															<tr class='tanggal-transfer'>
																<td>Tanggal <span class='status-terima' <?if ($row->pembayaran_type_id == 3 || $row->pembayaran_type_id == 2 || $row->pembayaran_type_id == 4) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($row->pembayaran_type_id == 1 ) {?> hidden <?}?> >Transfer</span></td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='tanggal_transfer' class='date-picker' value='<?=is_reverse_date($row->tanggal_transfer);?>'>
																	<?$tanggal_transfer = is_reverse_date($row->tanggal_transfer);?>
																</td>
															</tr>
															<tr class='nama-bank' <?if ($row->pembayaran_type_id == 3) {?> hidden <?}?> >
																<td>Nama Bank</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='nama_bank' value="<?=$row->nama_bank;?>">
																</td>
															</tr>
															<tr class='no-rek-bank' <?if ($row->pembayaran_type_id == 3) {?> hidden <?}?> >
																<td>No Rekening Bank</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='no_rek_bank' value="<?=$row->no_rek_bank;?>">
																</td>
															</tr>
															<tr class='no-giro' <?if ($row->pembayaran_type_id != 2) {?> hidden <?}?> >
																<td>No GIRO</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='no_giro' value="<?=$row->no_giro;?>">
																</td>
															</tr>
															<!-- <tr class='tanggal-giro' <?if ($row->pembayaran_type_id == 3 || $row->pembayaran_type_id == 1) {?> hidden <?}?> >
																<td>Tanggal GIRO</td>
																<td class='padding-rl-5'> : </td>
																	<input name='tanggal_giro' class='date-picker' value='<?=is_reverse_date($row->tanggal_giro);?>'>
																</td>
															</tr> -->
															<tr class='jatuh-tempo'  <?if ($row->pembayaran_type_id != 2) {?> hidden <?}?>   >
																<td>Jatuh Tempo</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='jatuh_tempo' class='date-picker' value='<?=is_reverse_date($row->jatuh_tempo);?>'>
																</td>
															</tr>
															
															<tr class='nama-penerima' <?if ($row->pembayaran_type_id == 1 || $row->pembayaran_type_id == 2) {?> hidden <?}?> >
																<td>Nama Penerima </td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='nama_penerima' value="<?=$row->nama_penerima;?>">
																</td>
															</tr>
															<tr>
																<td>Nilai</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<?$total_bayar_hutang += $row->amount;?>
																	<input name='amount' class='amount_number' value="<?=number_format($row->amount,'0',',','.');?>">
																</td>
															</tr>
															<tr>
																<td>Keterangan</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name="keterangan" value="<?=$row->keterangan;?>">
																</td>
															</tr>
															<tr>
																<td></td>
																<td></td>
																<td class='text-right'>
																	<input name='pembayaran_hutang_id' value="<?=$pembayaran_hutang_id;?>" hidden>
																	<input name='pembayaran_hutang_nilai_id' value='<?=$row->id;?>' hidden>
																	<button style='margin:5px;' id='remove-<?=$row->id;?>' class='btn btn-xs red btn-active btn-remove-nilai'>HAPUS</button>
																	<button style='margin:5px;' id='update-<?=$row->id;?>' class='btn btn-xs green btn-active btn-update-nilai'>SIMPAN</button>
																</td>
															</tr>
														</table>
													</form>
												</div>
											<?
											$idx++;
											}?>
											<div class='info-bayar-div' id='bayar-section'>
												<a class='block-info-bayar'><i class='fa fa-plus'></i> Tambah Bayar</a>
												<form method="post" action="<?=base_url()?>finance/pembayaran_hutang_nilai_insert" id='form-bayar-nilai'>
													<table class='bayar-info' width='100%;' style='margin-bottom:5px;' hidden>
														<tr>
															<td>Jenis Pembayaran</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 1 || $pembayaran_type_id =='') { echo 'checked'; }?> name='pembayaran_type_id' value="1">Transfer</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 2) { echo 'checked'; }?> name='pembayaran_type_id' value="2">Giro</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 3) { echo 'checked'; }?> name='pembayaran_type_id' value="3">Cash</label>
																<!-- <label>
																<input type='radio' <?if ($pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4">EDC</label> -->
															</td>
														</tr>
														<tr class='tanggal-transfer'>
															<td>Tanggal <span class='status-terima' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 2 || $pembayaran_type_id == 4) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >Transfer</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='tanggal_transfer' class='date-picker' value='<?=date('d/m/Y');?>'>
															</td>
														</tr>
														<tr class='nama-bank' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
															<td>Nama Bank</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<?
																$bank_list = "<table>";
																foreach ($bank_history as $row) {
																	$bank_list .= "<tr class='bank-history' style='cursor:pointer'>";
																	$bank_list .= "<td><span class='nama-bank-history'>".$row->nama_bank."</span></td>";
																	$bank_list .= "<td> : </td>";
																	$bank_list .= "<td><span class='no-rek-history'>".$row->no_rek_bank."</span></td>";
																	$bank_list .= "</tr>";
																}
																$bank_list .= "</table>";

																?>
																<a data-toggle="popover" data-trigger='click' data-html="true" data-content="<?=$bank_list;?>">
																	<input name='nama_bank' value="<?=$nama_bank;?>">
																</a>
															</td>
														</tr>
														<tr class='no-rek-bank' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
															<td><span class='nrk-bank'>No Rekening Bank</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_rek_bank' value="<?=$no_rek_bank;?>">
															</td>
														</tr>
														<tr class='no-giro' <?if ($pembayaran_type_id != 2) {?> hidden <?}?> >
															<td>No GIRO</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_giro'>
															</td>
														</tr>
														<tr class='tanggal-giro' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 1) {?> hidden <?}?> >
															<td>Tanggal GIRO</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='tanggal_giro' class='date-picker' >
															</td>
														</tr>
														<tr class='jatuh-tempo' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 1) {?> hidden <?}?> >
															<td>Jatuh Tempo</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='jatuh_tempo' class='date-picker' value='<?=$jatuh_tempo;?>'>
															</td>
														</tr>
														<tr>
															<td>Nilai</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='amount' class='amount_number'>
															</td>
														</tr>
														
														<tr class='nama-penerima' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >
															<td>Nama Penerima </td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='nama_penerima' value="<?=$nama_penerima;?>">
															</td>
														</tr>
														<tr>
															<td>Keterangan</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name="keterangan" value="<?=$keterangan;?>">
															</td>
														</tr>
														<tr>
															<td></td>
															<td></td>
															<td class='text-right'>
																<input name='pembayaran_hutang_id' value="<?=$pembayaran_hutang_id;?>" hidden>
																<button style='margin:5px;' class='btn btn-xs green btn-active btn-save-nilai'>SIMPAN</button>
															</td>
														</tr>
													</table>

												</form>
												

											</div>
												
										</div>
										<?}else{?>
											<i>
												<div class='alert alert-info'>
													<b>NOTES : </b><br/>
													1. <b>Pilih daftar nota</b> yang akan dibayar terlebih dahulu kemudian <b> klik SIMPAN</b><br>
													2. <b>Pilihan pembayaran</b> akan muncul setelah daftar nota tersimpan
												</div>
											</i> 
										<?}?>
									</td>
									<td style='vertical-align:top;font-size:2.5em;'  width='55%' class='text-right'>
										<table style='float:right;'>
											<tr style='border:2px solid #c9ddfc'>
												<td class='padding-rl-25' style='background:#c9ddfc'>BAYAR</td>
												<td class='padding-rl-10'>
													<b>Rp <span class='total_nilai_bayar' style=''><?=number_format($total_bayar_hutang,'0',',','.');?></span></b>
												</td>
											</tr>
											<tr style='border:2px solid #ffd7b5'>
												<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
												<td class='text-right padding-rl-10'> 
													<b>Rp <span class='total_bayar' style=''></span></b>
												</td>
											</tr>
											<tr style='border:2px solid #ffd700'>
												<td class='padding-rl-25' style='background:#ffd700'>PEMBULATAN</td>
												<td class='text-right padding-rl-10'> 
													<b style='float:left'>Rp</b><b> <input <?=($pembayaran_hutang_id == '' ? 'readonly' :'');?> name='pembulatan' value="<?=$pembulatan;?>" style='width:110px;border:none;text-align:right'></b>
												</td>
											</tr>
											<tr style='border:2px solid #ceffb4'>
												<td class='padding-rl-25' style='background:#ceffb4'>SELISIH</td>
												<td class='padding-rl-10'>
													<?
														$sisa_bayar = ($total_bayar_hutang + $pembulatan) - $total_hutang ;
														if ($sisa_bayar < 0) {
															$kembali_style = "color:red";
														}else{
															$kembali_style = "color:black";
														}
													?>
													<b>Rp <span class='selisih' style='<?=$kembali_style;?>'><?=number_format($sisa_bayar,'0',',','.'); ?></span></b>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

						

						<hr/>
						<div>
							<?//if (is_posisi_id() != 6 ) { ?>
								<?if ($pembayaran_hutang_id == '') { ?>
									<button title='double click ini untuk save' type='button' class='btn btn-lg green hidden-print btn-save-bayar'><i class='fa fa-save'></i> Simpan </button>
								<?}?>
							<?//}?>
			                <a <?=$disabled;?> class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Print </a>
			                <a href="<?=base_url().is_setting_link('finance/hutang_payment_form');?>" class='btn btn-lg default hidden-print'> <i class='fa fa-plus'></i> Pembayaran Baru </a>
						</div>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>


<script>
jQuery(document).ready(function() {

	// FormNewPenjualanDetail.init();

	<?if ($pembayaran_hutang_id == '') {?>
		$("#supplier_id_select").select2();
	<?}?>
	var form_group = {};
	var idx_gen = 0;

	update_total_bayar();

	$('.btn-reset-form, .btn-reset-bayar').click(function(e){
		e.preventDefault();
	});

	$('[data-toggle="popover"]').popover();

	$("#toko_id_select").change(function(){
		if ($(this).val() != 0 && $("#supplier_id_select").val() != 0) {
			$('.btn-form-get').prop("disabled",false);
		}else{
			$('.btn-form-get').prop("disabled",true);
		};
	});

	$("#supplier_id_select").change(function(){
		// alert($(this).val());
		if ($(this).val() != 0 && $("#toko_id_select").val() != 0) {
			$('.btn-form-get').prop('disabled',false);
		}else{
			$('.btn-form-get').prop('disabled',true);
		};
	});

	$('.btn-form-get').click(function(){
		var date_start = $('[name=tanggal_start]').val();
		var date = date_start.split('/');
		var start = date[1]+'/'+date[0]+'/'+date[2];

		var date_end = $('[name=tanggal_end]').val();
		var date = date_end.split('/');
		var end = date[1]+'/'+date[0]+'/'+date[2];;

		var start = new Date(start);
		var end = new Date(end);
		// alert(start);

		// end - start returns difference in milliseconds 
		var diff = new Date(end - start);

		// get days
		var days = diff/1000/60/60/24;
		// alert(days);
		if (days < 0) {
			alert("Tanggal awal lebih besar dari tanggal akhir ")
			$('[nama=tanggal_start]').val(date_end);
		}else{
			/*if (days >= 15) {
				bootbox.confirm("Memanggil data lebih dari 15 hari <br/> "+
					"( tergantung dari banyaknya data ) mungkin membuat halaman menjadi lama dimuat <br/> atau bahkan error. "+
					"Anda ingin melanjutkan ? ", function(respond){
						if (respond) {	
							$('#form-get').submit();				
						};
					});
			}else{*/
				$('#form-get').submit();				
			// };
		}
	});

	$('#general_table').on('change','.lunas-check', function(){
		var ini = $(this).closest('tr');
		if($(this).is(':checked')){
			check_bayar(ini);
		}else{
			undo_bayar(ini);
		}
		update_total_bayar();
	});

	$('#general_table').on('change','.bayar-hutang', function(){
		var ini = $(this).closest('tr');
		var hutang = reset_number_format(ini.find('.hutang').html());
		var bayar = reset_number_format($(this).val());
		var sisa_hutang = hutang - bayar;
		// alert(hutang+' - '+bayar);
		ini.find('.sisa-hutang').html(change_number_format(sisa_hutang));

		if (sisa_hutang != 0) {
			ini.find('.lunas-check').prop('checked',false);
		}else{
			ini.find('.lunas-check').prop('checked',true);
		};
		$.uniform.update('.lunas-check');


		<?if ($pembayaran_hutang_id != '') { ?>
			var data = {};
			data['id'] = ini.find('.pembayaran_hutang_detail_id').html();
			data['amount'] = reset_number_format(bayar);
			var url = 'finance/update_bayar_hutang_detail';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// alert(data_respond);
				if (data_respond == 'OK') {
					
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		<?};?>
		update_total_bayar();
	});

	$('#check_all').change(function(){
		if($(this).is(':checked')){
			$("#general_table .lunas-check").each(function(){
				$(this).prop('checked',true);
				var ini = $(this).closest('tr');
				check_bayar(ini);
			});
		}else{
			$("#general_table .lunas-check").each(function(){
				$(this).prop('checked',false);
				var ini = $(this).closest('tr');
				undo_bayar(ini);
			});
		}
		$.uniform.update('.lunas-check');
		update_total_bayar();
	});
    
    $('.btn-save-bayar').dblclick(function(){
    	// $('#form-bayar').submit();
    	var ini = $(this);
    	var bayar_id = $('[name=pembayaran_type_id]:checked').val();
    	var total_bayar = reset_number_format($('.total_bayar').html());
    	// alert(total_bayar);

    	if (total_bayar != 0) {
	    	$('#form-bayar').submit();
	    	$(this).attr('disabled',true);    		
    	}else{
    		alert("Total Nota Tidak bisa 0");
    	};

    	// if (bayar_id == 1) {
	    	
	    // 	if ($('[name=tanggal_transfer]').val() != '' && $('[name=no_rek_bank]').val() != '' && $('[name=nama_bank]').val() != '') {
	    // 		if (total_bayar <= 0) {
	    // 			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    // 		}else{
	    // 			$('#form-bayar').submit();
	    // 			btn_disabled_load(ini);
	    // 			// $(this).prop('disabled',true);
	    // 		};
	    // 	}else{
	    // 		bootbox.alert("Mohon lengkapi data pembayaran");
	    // 	}
	    	
    	// }else if ( bayar_id == 2) {
    		
    	// 	if ($('[name=tanggal_transfer]').val() != '' && $('[name=no_rek_bank]').val() != '' && $('[name=nama_bank]').val() != '' && $('[name=jatuh_tempo]').val() != ''  ) {
	    // 		if (total_bayar <= 0) {
	    // 			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    // 		}else{
	    // 			$('#form-bayar').submit();
	    // 			btn_disabled_load(ini);
	    // 		};
	    // 	}else{
	    // 		bootbox.alert("Mohon lengkapi data pembayaran");
	    // 	}

    	// }else if (bayar_id ==  3) {
    	// 	if ($('[name=tanggal_transfer]').val() != '' ) {
	    // 		if (total_bayar <= 0) {
	    // 			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    // 		}else{
	    // 			$('#form-bayar').submit();
	    // 			btn_disabled_load(ini);
	    // 		};
	    // 	}else{
	    // 		bootbox.alert("Mohon lengkapi data pembayaran");
	    // 	}
    	// };
    });
	
	$("#potongan-hutang").change(function(){
		update_total_bayar();
	});

	<?if ($pembayaran_hutang_id != '') { ?>
		$("[name=pembulatan]").change(function(){
			var data = {};
			data['id'] = "<?=$pembayaran_hutang_id?>";
			data['pembulatan'] = $(this).val();
			var url = 'finance/update_pembulatan_hutang';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {				
					update_total_bayar();
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		});

		$("#potongan-hutang").change(function(){
			var data = {};
			data['id'] = "<?=$pembayaran_hutang_id?>";
			data['potongan_hutang'] = $(this).val();
			var url = 'finance/update_potongan_hutang';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {				
					update_total_bayar();
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		});
	<?}?>

    $('.bayar-info').on('change', '[name=pembayaran_type_id]', function(){
    	var ini = $('.bayar-info');

    	if ($(this).val() == 1) {
	    	ini.find('.nama-penerima').hide();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.no-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.status-terima').hide();

	    	ini.find('.status-transfer').show();
	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();
	    	
    	}else if ($(this).val() == 2) {
    		ini.find('.no-giro').show();
	    	ini.find('.no-acc-giro').show();
	    	// ini.find('.tanggal-giro').show();
	    	ini.find('.jatuh-tempo').show();
	    	ini.find('.status-terima').show();

	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();
	    	ini.find('.no-giro').show();

	    	ini.find('.status-transfer').hide();
	    	// ini.find('.nm-bank').hide();
	    	// ini.find('.nrk-bank').hide();
	    	ini.find('.nama-penerima').hide();

    	}else if ($(this).val() == 3) {
    		ini.find('.nama-penerima').show();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();
	    	ini.find('.status-terima').show();
	    	ini.find('.no-giro').hide();

	    	ini.find('.status-transfer').hide();
    	}else if ($(this).val() == 4) {
    		ini.find('.nama-penerima').hide();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();
	    	ini.find('.status-terima').show();
	    	ini.find('.no-giro').hide();

	    	ini.find('.status-transfer').hide();
    	};;
    });

	$(document).on('click', '.block-info-bayar', function(){
		// alert($(this).next(".bayar-info").html());
		var ini = $(this).closest('div').find(".bayar-info");
		ini.toggle();
	});

	$(".btn-save-nilai").click(function(e){
		// 
		e.preventDefault();
		var ini = $(this);
    	var form = $('#form-bayar-nilai');
    	var ini = $(this);
    	form_bayar_nilai(form, ini);
    	
	});

	$(document).on('click', '.btn-update-nilai', function(e){
		e.preventDefault();
		var ini = $(this);
		var data_id = $(this).attr('id').split('-');
		var id = data_id[1];
		var form = $("#form-bayar-nilai-update-"+id);
    	
    	form_bayar_nilai(form, ini);
	});

	$(document).on('click','.btn-remove-nilai', function(e){
		e.preventDefault();
		var ini = $(this);
		var data_id = $(this).attr('id').split('-');
		var id = data_id[1];
		var pembayaran_hutang_id = "<?=$pembayaran_hutang_id?>";
		bootbox.confirm("Yakin menghapus data pembayaran ini ?", function(respond){
			if (respond) {
				window.location.replace(baseurl+"finance/pembayaran_hutang_nilai_delete?id="+id+"&pembayaran_hutang_id="+pembayaran_hutang_id);
			};
		});
	});

//=============================================bank history click==========================

	$(document).on('click','.bank-history', function(){
		var nama_bank = $(this).find('.nama-bank-history').html();
		var rek_bank = $(this).find('.no-rek-history').html();

		var form = '#form-bayar-nilai';
		$(form+" [name=nama_bank]").val(nama_bank);
		$(form+" [name=no_rek_bank]").val(rek_bank);
		$('[data-toggle="popover"]').popover('hide');


	});

});

function check_bayar(ini){
    $('.bayar-hutang').number(true,0);
	var bayar = reset_number_format(ini.find('.hutang').html());
	// alert(bayar);
	ini.find('.bayar-hutang').val(bayar);
	ini.find('.sisa-hutang').html(0);
}

function undo_bayar(ini){
	var bayar = ini.find('.hutang').html();
	ini.find('.bayar-hutang').val(0);
	ini.find('.sisa-hutang').html(addCommas(bayar));
}

function update_total_bayar(){
	var total_bayar = 0; var total_hutang = 0;
	var pembulatan = $("[name=pembulatan]").val();
	var total_nilai_bayar = reset_number_format($('.total_nilai_bayar').html());
	if (pembulatan == '') {pembulatan = 0;};
	$('#general_table .bayar-hutang').each(function(){
		total_bayar += reset_number_format($(this).val());
	});

	$('#general_table .sisa-hutang').each(function(){
		total_hutang += reset_number_format($(this).html());
		// alert($(this).val());
	});

	var potongan_hutang = reset_number_format($('#potongan-hutang').val());
	var total_bayar = total_bayar - parseInt(potongan_hutang);
	// alert(potongan_hutang);

	var selisih = total_nilai_bayar - total_bayar + parseInt(pembulatan);

	<?if ($supplier_id != '') {?>
		// notific8("teal", "TOTAL : "+change_number_format(total_bayar));
	<?}?>

	$('.total_bayar').html(change_number_format(total_bayar));
	$('.total_sisa_hutang').html(change_number_format(total_hutang));
	$('.selisih').html(change_number_format(selisih));

}

function form_bayar_nilai(form, ini){

	var bayar_id = form.find('[name=pembayaran_type_id]:checked').val();
	var total_bayar = reset_number_format($('.total_bayar').html());
	var tanggal_transfer = form.find('[name=tanggal_transfer]').val();
	var no_rek_bank = form.find('[name=no_rek_bank]').val();
	var nama_bank = form.find('[name=nama_bank]').val();
	var tanggal_giro = form.find('[name=tanggal_giro]').val();
	var jatuh_tempo = form.find('[name=jatuh_tempo]').val();
	// alert(total_bayar);

	// $('#form-bayar').submit();
	if (bayar_id == 1) {
    	
    	if (tanggal_transfer != '' && no_rek_bank != '' && nama_bank != '') {
    		if (total_bayar <= 0) {
    			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    		}else{
	    			// $('#form-bayar-nilai').submit();
	    			form.submit();
	    			btn_disabled_load(ini);
	    			// $(this).prop('disabled',true);
	    		};
	    	}else{
	    		bootbox.alert("Mohon lengkapi data pembayaran");
	    	}
	    	
    	}else if ( bayar_id == 2) {
    		
    		if (tanggal_transfer != '' && no_rek_bank != '' && nama_bank != '' && jatuh_tempo != ''  ) {
	    		if (total_bayar <= 0) {
	    			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    		}else{
	    			form.submit();
	    			btn_disabled_load(ini);
	    		};
	    	}else{
	    		bootbox.alert("Mohon lengkapi data pembayaran");
	    	}

    	}else if (bayar_id ==  3) {
    		if ( tanggal_transfer != '' ) {
	    		if (total_bayar <= 0) {
	    			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    		}else{
	    			// $('#form-bayar-nilai').submit();
	    			form.submit();
	    			btn_disabled_load(ini);
	    		};
	    	}else{
	    		bootbox.alert("Mohon lengkapi data pembayaran");
	    	}
    	};
}


</script>
