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

.header-letter{
	display: none;
}

@media print{
	a[href]:after{content:none}

	.header-letter{
		display: block;
	}
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$pembayaran_piutang_id = '';
			$pembayaran_type_id = 1;
			
			$keterangan = '';
			$tanggal_giro = '';
			$jatuh_tempo = '';
			$tanggal = date('d/m/Y');
			$tanggal_transfer = '';
			$nama_bank = '';
			$no_rek_bank = '';
			$no_giro = '';
			$nama_penerima = '';
			$total_jual = 0;
			$pembulatan = 0;

			$g_total = 0;
			$readonly = '';
			$disabled = '';

			foreach ($pembayaran_piutang_data as $row) {
				$tanggal = is_reverse_date($row->tanggal);
				$pembayaran_piutang_id = $row->id;
				$pembulatan = $row->pembulatan;
				
				$toko_id = $row->toko_id;
				$customer_id = $row->customer_id;
			}

			foreach ($toko_data as $row) {
				$nama_toko = $row->nama;
				$alamat_toko = $row->alamat;
				$telepon_toko = $row->telepon;
				$kota_toko = $row->kota;
			}

			$nama_rek_default = '';
			$nama_bank_default = '';
			$no_rek_bank_default = '';

			// foreach ($bank_default as $row) {
			// 	$nama_rek_default = $row->nama_rek;
			// 	$nama_bank_default = $row->nama_bank;
			// 	$no_rek_bank_default = $row->no_rek_bank;
			// }

			// if (is_posisi_id() == 6 ) {
			// 	$readonly = 'readonly';
			// 	$disabled = 'disabled';
			// }

			// if ($penjualan_id == '') {
			// 	$disabled = 'disabled';
			// }
		?>

		<div class="modal fade bd-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<b style='font-size:2em'>DAFTAR DP</b><hr/>
						<form id='form-dp' action="<?=base_url('finance/pembayaran_piutang_dp_update');?>" method="POST">

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
											Saldo
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
										<input name="tanggal_transfer" value="<?=$tanggal_transfer;?>" hidden>

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
													<span class='amount'><?=number_format($row->amount,'0',',','.');?></span>
												</td>
												<td>
													<?$dp_bayar += $row->amount_bayar;?>
													<input name="amount_<?=$row->id;?>" class='amount-bayar amount-number' value='<?=number_format($row->amount_bayar,'0',',','.');?>' <?=($row->amount_bayar == 0 ? 'readonly' : '');?> style="width:80px">
												</td>
												<td>
													<input name="pembayaran_piutang_id" value="<?=$pembayaran_piutang_id;?>" hidden >
													<span class='id' hidden="hidden"><?=$row->id;?></span>
													<input type="checkbox" class='dp-check' <?=($row->amount_bayar != 0 ? 'checked' : '');?> >
												</td>
											</tr>
										<? } ?>
										<tr>
											<td colspan='3'></td>
											<td><b>TOTAL</b></td>
											<td><span class='dp-total' style='font-size:1.3em'><?=number_format($dp_bayar,'0',',','.');?></span></td>
											<td></td>
										</tr>

								</tbody>
							</table>
						</form>
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

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-body">
						<div class='text-center' class='header-letter'>
							<b><span style='font-size:1.2em'><?=$nama_toko?></span></b><br/>
							<?=($alamat_toko != '' ? $alamat_toko.'<br/>' : '')?>
							<?=($telepon_toko != '' ? $telepon_toko.'<br/>' : '')?>
							<?=($kota_toko != '' ? $kota_toko.'<br/>' : '')?>
							<hr/>
						</div>
						<span class='header-letter'><b>Kontra Bon</b></span>
						<form action='' id='form-get' method='get'>
							<table id='tbl-form-get'>
								<tr>
									<td>Tanggal Kontra</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<input name='tanggal' id='tanggal-kontra-show' class='date-picker' value='<?=$tanggal;?>'>
										</b>
									</td>
								</tr>
								<?if ($pembayaran_piutang_id == '') { ?>
									<tr>
										<td>Tanggal Faktur </td>
										<td class='padding-rl-5'> : </td>
										<td>
											<b>
												<input name='tanggal_start' class='date-picker' value='<?=$tanggal_start;?>'>
												s/d
												<input name='tanggal_end' class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											</b>
										</td>
									</tr>

									<tr>
										<td>Status </td>
										<td class='padding-rl-5'> : </td>
										<td>
											<b>
												<select name='status_jt' style="<?=($status_jt == 1 ? 'color:red' : ''); ?>" >
													<option <?=($status_jt == 0 ? 'selected' : '' );?> value="0" >Semua</option>
													<option <?=($status_jt == 1 ? 'selected' : '' );?> value="1" >Jatuh Tempo</option>
												</select>
											</b>
										</td>
									</tr>									
								<?}?>
								<tr class='hidden-print'>
									<td>Toko </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select <?if ($pembayaran_piutang_id != '') {?> disabled <?}?> name='toko_id' id="toko_id_select">
												<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->toko_list_aktif as $row) { ?>
													<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
								<tr>
									<td> Customer </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select name='customer_id'  <?if ($pembayaran_piutang_id != '') {?> disabled <?}?> id="customer_id_select"  style='width:250px;'>
												<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->customer_list_aktif as $row) { ?>
													<option <?=($customer_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
							</table>
						</form>
						<?if ($pembayaran_piutang_id == '') {?>
							<button <?if ($toko_id == '' && $customer_id == '') { ?> disabled <?}?> class='btn btn-xs default btn-form-get'><i class='fa fa-search'></i> Cari</button>
						<?}?>

					    <hr/>
						<!-- table-striped table-bordered  -->
						<?if ($pembayaran_piutang_id == '') { ?>
							<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_insert" id='form-bayar'>
							<input hidden name='tanggal' id='tanggal_kontra' value='<?=date('d/m/Y')?>'>
						<?}?>

							<?$total_piutang = 0; $i =1; ?>
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
											Tanggal
										</th>
										<th scope="col">
											Total Faktur
										</th>
										<th scope="col">
											Total piutang
										</th>
										<th scope="col">
											Jatuh Tempo
										</th>
										<th scope="col" class='hidden-print'>
											Bayar
										</th>
										<th scope="col" style='width:150px' class='hidden-print'>
											Sisa
										</th>
										<th scope="col" class='hidden-print' class='hidden-print'>
											Action
										</th>
									</tr>
								</thead>
								<tbody>

									<?
									$i =1; $total_piutang = 0;
									foreach ($pembayaran_piutang_awal_detail as $row) { ?>
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
												<?=number_format($row->total_jual,'0',',','.');?>
											</td>
											<td>
												<?$total_piutang += $row->sisa_piutang;?>
												<?//=$row->sisa_piutang;?>
												<span class='piutang'><?=number_format($row->sisa_piutang,'0',',','.');?></span>
											</td>
											<td>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td class='hidden-print'>
												<input <?=$readonly;?> name='piutang_<?=$row->penjualan_id;?>' class='amount-number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
											</td>
											<td class='hidden-print'>
												<?$sisa = $row->sisa_piutang - $row->amount;?>
												<span class='sisa-piutang amount-number'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_piutang_id != '') { ?>
													<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
												<?}?>
												<input name='penjualan_id_<?=$row->penjualan_id;?>' hidden value="<?=$row->penjualan_id;?>"> 
												<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount-number' >
												lunas </label>
											</td>
										</tr>
									<?
									$i++; 
									} 

									foreach ($pembayaran_piutang_detail as $row) { ?>
										<tr>
											<td>
												<?=$i;?>
											</td>
											<td>
												<a target='_blank' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$row->penjualan_id;?>">
													<?=$row->no_faktur;?>
												</a>
											</td>
											<td>
												<?=is_reverse_date($row->tanggal);?>
											</td>
											<td>
												<?=number_format($row->total_jual,'0',',','.');?>
											</td>
											<td>
												<?$total_piutang += $row->sisa_piutang;?>
												<?//=$row->sisa_piutang;?>
												<span class='piutang'><?=number_format($row->sisa_piutang,'0',',','.');?></span>
											</td>
											<td>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td class='hidden-print'>
												<?//=$row->penjualan_id;?>
												<input <?=$readonly;?> name='bayar_<?=$row->penjualan_id;?>' class='amount-number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
											</td>
											<td class='hidden-print'>
												<?$sisa = $row->sisa_piutang - $row->amount;?>
												<span class='sisa-piutang amount-number'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_piutang_id != '') { ?>
													<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
												<?}?>
												<input name='penjualan_id_<?=$row->penjualan_id;?>' hidden value="<?=$row->penjualan_id;?>"> 
												<?if ($pembayaran_piutang_id == '') {?>
													<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount-number' >
													lunas </label>
												<?}?>
											</td>
										</tr>
									<?
									$i++; 
									} ?>
									<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc;'>
										<td></td>
										<td></td>
										<td></td>
										<td>TOTAL</td>
										<td><b><span class='total_piutang'><?=number_format($total_piutang,'0',',','.');?></span></b> </td>
										<td></td>
										<td class='hidden-print'><b><span class='total_bayar amount-number'></span></b></td>
										<td class='hidden-print'><b><span class='total_sisa_piutang'><?=number_format($total_piutang,'0',',','.');?></span></b></td>
										<td class='hidden-print'>
											<?if ($pembayaran_piutang_id == '') { ?>
												<label>
												<input type='checkbox' name='check_all' id='check_all'> lunas all </label>
											<?}?>
										</td>

									</tr>
								</tbody>
							</table>
							<hr class='hidden-print'/>
							<?if ($pembayaran_piutang_id != '') { ?>
								<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_insert" id='form-bayar' target="_blank">
							<?}?>
									<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
									<input name='customer_id' value="<?=$customer_id;?>" hidden>
									<input name='toko_id' value="<?=$toko_id;?>" hidden>

								</form>

							<table width='100%' class='hidden-print'>
								<tr>
									<td style='vertical-align:top;'  width='45%'>
										
										<?
										$idx = 1; $total_bayar_piutang = 0;
										if ($pembayaran_piutang_id != '') { ?>
										<div class='list-group'>
											<?$title = '-';
											foreach ($pembayaran_piutang_nilai as $row) { 
												$pernah_bayar = true;
												if ($row->pembayaran_type_id == 1) { $title = 'TRANSFER'; }
													elseif ($row->pembayaran_type_id == 2) { $title = 'GIRO'; }
														elseif ($row->pembayaran_type_id == 3) { $title = 'CASH'; }
															elseif ($row->pembayaran_type_id == 4) { $title = 'EDC'; }
																elseif ($row->pembayaran_type_id == 5) { $title = 'DP'; }
												?>
												<div class='info-bayar-div'>
													<a class='block-info-bayar'> 
														<span style='font-size:0.8em;color:#aaa; position:absolute;left:50px;'><?=$idx;?></span> 
														<?=$title;?> : 
														<span style='color:black'> <?=number_format($row->amount,'0',',','.');?></span>
													</a>
													<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_nilai_update" id='form-bayar-nilai-update-<?=$row->id;?>'>
													
														<table class='bayar-info' width='100%;' style='margin-bottom:5px;' hidden >
															<tr>
																<td>Jenis Pembayaran</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<?if ($row->pembayaran_type_id != 5) {?>
																		<label>
																		<input type='radio' <?if ($row->pembayaran_type_id == 1) { echo 'checked'; }?> name='pembayaran_type_id' value="1">Transfer</label>
																		<label>
																		<input type='radio' <?if ($row->pembayaran_type_id == 2) { echo 'checked'; }?> name='pembayaran_type_id' value="2">Giro</label>
																		<label>
																		<input type='radio' <?if ($row->pembayaran_type_id == 3) { echo 'checked'; }?> name='pembayaran_type_id' value="3">Cash</label>
																		<label>
																		<input type='radio' <?if ($row->pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4">EDC</label>
																		<label>
																		<input type='radio' <?if ($row->pembayaran_type_id == 5) { echo 'checked'; }?> name='pembayaran_type_id' value="5">DP</label>
																	<?}else{?>

																		<label>
																		<input type='radio' <?if ($row->pembayaran_type_id == 5) { echo 'checked'; }?> name='pembayaran_type_id' value="5">DP</label>
																	<?}?>
																</td>
															</tr>
															<tr class='tanggal-transfer'>
																<td>Tanggal <span class='status-terima' <?if ($row->pembayaran_type_id == 1) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($row->pembayaran_type_id != 1) {?> hidden <?}?> >Transfer</span></td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='tanggal_transfer' class='date-picker' value='<?=is_reverse_date($row->tanggal_transfer);?>'>
																	<?$tanggal_transfer = is_reverse_date($row->tanggal_transfer);?>
																</td>
															</tr>
															<tr class='nama-bank no-dp' <?=($row->pembayaran_type_id == 3 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?> >
																<td>Nama Bank</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='nama_bank' value="<?=$row->nama_bank;?>">
																</td>
															</tr>
															<tr class='no-rek-bank no-dp' <?=($row->pembayaran_type_id == 3 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?> >
																<td>No Rekening Bank</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='no_rek_bank' value="<?=$row->no_rek_bank;?>">
																</td>
															</tr>
															<tr class='jatuh-tempo no-dp' <?=($row->pembayaran_type_id != 2 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?>   >
																<td>Jatuh Tempo</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='jatuh_tempo' class='date-picker' value='<?=is_reverse_date($row->jatuh_tempo);?>'>
																</td>
															</tr>
															<tr class='no-giro no-dp' <?=($row->pembayaran_type_id != 2 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?> >
																<td>No GIRO</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='no_giro' value='<?=$row->no_giro;?>'>
																</td>
															</tr>
															<tr class='nama-penerima no-dp' <?=($row->pembayaran_type_id == 1 || $row->pembayaran_type_id == 2 || $row->pembayaran_type_id == 5 ? 'hidden' : '' ); ?> >
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
																	<?$total_bayar_piutang += $row->amount;?>
																	<?if ($row->pembayaran_type_id == 5) {?>
																		<a class='btn-dp-show'>
																			<input readonly value="<?=number_format($row->amount,'0',',','.');?>">
																		</a>
																	<?}else{?>
																		<input name='amount' class='amount-number' value="<?=number_format($row->amount,'0',',','.');?>">
																	<?}?>
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
																	<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
																	<input name='pembayaran_piutang_nilai_id' value='<?=$row->id;?>' hidden>
																	<button style='margin:5px;' id='remove-<?=$row->id;?>' class='btn btn-xs red btn-active btn-remove-nilai'>HAPUS</button>
																	<?if ($row->pembayaran_type_id != 5) {?>
																		<button style='margin:5px;' id='update-<?=$row->id;?>' class='btn btn-xs green btn-active btn-update-nilai'>SIMPAN</button>
																	<?}?>
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
												<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_nilai_insert" id='form-bayar-nilai'>
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
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4">EDC</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 5) { echo 'checked'; }?> name='pembayaran_type_id' value="5">DP</label>
															</td>
														</tr>
														<tr class='tanggal-transfer'>
															<td>Tanggal <span class='status-terima' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 2 || $pembayaran_type_id == 4) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >Transfer</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input autocomplete='off' <?=(isset($pernah_bayar) ? 'hidden' : '');?> name='tanggal_transfer' class='date-picker' value='<?=$tanggal_transfer;?>'>
																<?=$tanggal_transfer;?>
															</td>
														</tr>
														<tr class='nama-bank no-dp' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
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
														<tr class='no-rek-bank no-dp' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
															<td><span class='nrk-bank'>No Rekening Bank</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_rek_bank' value="<?=$no_rek_bank;?>">
															</td>
														</tr>
														<tr class='no-giro no-dp' <?if ($pembayaran_type_id != 2) {?> hidden <?}?> >
															<td>No GIRO</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_giro' value="<?=$no_giro;?>">
															</td>
														</tr>
														<tr class='jatuh-tempo no-dp' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 1) {?> hidden <?}?> >
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
																<input name='amount' class='amount-number isian-nilai'>
																<div class='dp-btn' hidden>
																	<a class='btn btn-default btn-dp-show'>Pilih DP</a>
																</div>
															</td>
														</tr>
														
														<tr class='nama-penerima no-dp' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >
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
																<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
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
													<b>Rp <span class='total_nilai_bayar' style=''><?=number_format($total_bayar_piutang,'0',',','.');?></span></b>
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
													<b style='float:left'>Rp</b> <b><input <?=($pembayaran_piutang_id == '' ? 'readonly' :'');?> name='pembulatan' value="<?=$pembulatan;?>" style='width:140px;border:none;text-align:right'></b>
												</td>
											</tr>
											<tr style='border:2px solid #ceffb4'>
												<td class='padding-rl-25' style='background:#ceffb4'>SELISIH</td>
												<td class='padding-rl-10'>
													<?
														$sisa_bayar = ($total_bayar_piutang + $pembulatan) - $total_piutang ;

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
							<?if ($pembayaran_piutang_id == '') { ?>
								<button title='double click ini untuk save' type='button' <?=($customer_id == '' ? 'disabled' : '');?> class='btn btn-lg green hidden-print btn-save-bayar'><i class='fa fa-save'></i> Simpan </button>
							<?}?>
			                <a <?=$disabled;?> class='btn btn-lg blue btn-print hidden-print' onclick='window.print()'><i class='fa fa-print'></i> Print </a>
			                <a href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?customer_id=<?=$customer_id?>&toko_id=<?=$toko_id?>&tanggal_start=<?=date('Y-01-01')?>&tanggal_end=<?=date('Y-12-t')?>" class='btn btn-lg yellow-gold hidden-print'><i class='fa fa-plus'></i> Baru </a>
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

<script src="<?php echo base_url('assets_noondev/js/form-penjualan.js'); ?>" type="text/javascript"></script>


<script>
jQuery(document).ready(function() {

	// FormNewPenjualanDetail.init();

	var form_group = {};
	var idx_gen = 0;
	var nama_rek_default = "<?=$nama_rek_default?>";
	var nama_bank_default = "<?=$nama_bank_default?>";
	var no_rek_bank_default = "<?=$no_rek_bank_default?>";

	update_total_bayar();

	$("#tanggal-kontra-show").change(function(){
		$('#tanggal_kontra').val($(this).val());
	});

	<?if ($pembayaran_piutang_id != '') {?>
		var idx_tanggal = 1;
		$("#tanggal-kontra-show").change(function(){
			if($(this).val() !='' && idx_tanggal == 1){
				var data = {};
				data['pembayaran_piutang_id'] = "<?=$pembayaran_piutang_id?>" ;
				data['tanggal'] = $(this).val();
				var url = 'finance/update_tanggal_kontra_bon';
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						notific8("lime", "Tanggal Updated");	
					}else{
						alert("error mohon refresh dengan menekan F5");
					}
		   		});
			}
			idx_tanggal++;
	   		// console.log(idx_tanggal);
	   		if (idx_tanggal == 4) { idx_tanggal = 1;};
		});
	<?}?>

	$('.btn-reset-form, .btn-reset-bayar').click(function(e){
		e.preventDefault();
	});

	$('#customer_id_select').select2();

	$('[data-toggle="popover"]').popover();

	$("#toko_id_select").change(function(){
		if ($(this).val() != 0 && $("#customer_id_select").val() != 0) {
			$('.btn-form-get').prop("disabled",false);
		}else{
			$('.btn-form-get').prop("disabled",true);
		};
	});

	$("#customer_id_select").change(function(){
		// alert($(this).val());
		if ($(this).val() != 0 && $("#toko_id_select").val() != 0) {
			$('.btn-form-get').prop('disabled',false);
		}else{
			$('.btn-form-get').prop('disabled',true);
		};
	});

	$('.btn-form-get').click(function(){
		$('#form-get').submit();
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

	$('#general_table').on('change','.bayar-piutang', function(){


		var ini = $(this).closest('tr');
		var piutang = reset_number_format(ini.find('.piutang').html());
		var bayar = $(this).val();
		if (bayar == '') {
			bayar = 0;
		};
		var sisa_piutang = piutang - bayar;
		// alert(piutang +'-'+ bayar);
		if (sisa_piutang == '' || sisa_piutang == 0) {
			sisa_piutang = 0;
		}else{
			sisa_piutang = change_number_format(sisa_piutang);
		}
		ini.find('.sisa-piutang').html(sisa_piutang);

		if (sisa_piutang != 0) {
			ini.find('.lunas-check').prop('checked',false);
		}else{
			ini.find('.lunas-check').prop('checked',true);
		};
		$.uniform.update('.lunas-check');


		<?if ($pembayaran_piutang_id != '') { ?>
			var data = {};
			data['id'] = ini.find('.pembayaran_piutang_detail_id').html();
			data['amount'] = reset_number_format(bayar);
			var url = 'finance/update_bayar_piutang_detail';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
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

    });

    $(document).on('click', '.block-info-bayar', function(){
		// alert($(this).next(".bayar-info").html());
		var ini = $(this).closest('div').find(".bayar-info");
		ini.toggle();
	});

//========================================bank default===========================

	$('#form_bayar_nilai').on('change','[name=pembayaran_type_id]', function(){
    	if ($(this).val() == 1) {
			ini.find('.nama-bank').val(nama_bank_default);
	    	ini.find('.no-rek-bank').val(no_rek_bank_default);
	    }else{
	    	ini.find('.nama-bank').val('');
	    	ini.find('.no-rek-bank').val('');
	    }
	});


    $('.bayar-info').on('change', '[name=pembayaran_type_id]', function(){
    	var ini = $('.bayar-info');

    	if ($(this).val() == 1) {
	    	ini.find('.nama-penerima').hide();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.no-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.status-terima').hide();
	    	ini.find('.dp-btn').hide();

	    	ini.find('.status-transfer').show();
	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();
	    	ini.find('.isian-nilai').show();
	    	
    	}else if ($(this).val() == 2) {
    		ini.find('.no-giro').show();
	    	ini.find('.no-acc-giro').show();
	    	ini.find('.tanggal-giro').show();
	    	ini.find('.jatuh-tempo').show();
	    	ini.find('.status-terima').show();

	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();
	    	ini.find('.isian-nilai').show();

	    	ini.find('.status-transfer').hide();
	    	ini.find('.nama-penerima').hide();
	    	ini.find('.dp-btn').hide();

    	}else if ($(this).val() == 3) {
    		ini.find('.nama-penerima').show();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();
	    	ini.find('.status-terima').show();
	    	ini.find('.isian-nilai').show();

	    	ini.find('.no-giro').hide();
	    	ini.find('.dp-btn').hide();

	    	ini.find('.status-transfer').hide();
    	}else if ($(this).val() == 4) {
    		ini.find('.nama-penerima').hide();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();

	    	ini.find('.status-terima').show();
	    	ini.find('.isian-nilai').show();

	    	ini.find('.no-giro').hide();
	    	ini.find('.dp-btn').hide();


	    	ini.find('.status-transfer').hide();
    	}else if ($(this).val() == 5) {
    		ini.find('.no-dp').hide();
	    	ini.find('.dp-btn').show();
	    	ini.find('.isian-nilai').hide();
    	};

    	if($(this).val() == 5){
    		$('.btn-save-nilai').hide();
    	}else{
    		$('.btn-save-nilai').show();	
    	}
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
		var pembayaran_piutang_id = "<?=$pembayaran_piutang_id?>";
		bootbox.confirm("Yakin menghapus data pembayaran ini ?", function(respond){
			if (respond) {
				window.location.replace(baseurl+"finance/pembayaran_piutang_nilai_delete?id="+id+"&pembayaran_piutang_id="+pembayaran_piutang_id);
			};
		});
	});

	<?if ($pembayaran_piutang_id == '') { 
		$today = date('d/m/Y');
		?>
	    $('[name=pembayaran_type_id]').change(function(){
	    	if ($(this).val() == 1) {
	    		$('#tgl-transfer').val("");
	    	}else{
	    		var tgl = "<?=$today;?>";
	    		$('#tgl-transfer').val(tgl);
	    		// alert($('[name=tanggal_transfer]').val());
	    	}

	    });

	<?};?>
//=============================================bank history click==========================

	$(document).on('click','.bank-history', function(){
		var nama_bank = $(this).find('.nama-bank-history').html();
		var rek_bank = $(this).find('.no-rek-history').html();

		var form = '#form-bayar-nilai';
		$(form+" [name=nama_bank]").val(nama_bank);
		$(form+" [name=no_rek_bank]").val(rek_bank);
		$('[data-toggle="popover"]').popover('hide');


	});

	<?if ($pembayaran_piutang_id != '') { ?>
		$("[name=pembulatan]").change(function(){
			var data = {};
			data['id'] = "<?=$pembayaran_piutang_id?>";
			data['pembulatan'] = $(this).val();
			var url = 'finance/update_pembulatan_piutang';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {				
					update_total_bayar();
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		});
	<?}?>

//=====================================bayar dp=========================================

		$('.btn-dp-show').click(function(){
			let ini = $(this).closest('.bayar-info');
			let tanggal = ini.find('[name=tanggal_transfer]').val();
			// alert(tanggal);
			if (tanggal != '') {
				$("#form-dp [name=tanggal_transfer]").val(tanggal);
				$("#form-dp .testing").html(tanggal);
				// alert($('#form-dp [name=tanggal]').val());
				$('#portlet-config').modal('toggle');
			}else{
				alert("isi tanggal terlebih dahulu");
			}
		});

		$('#dp_list_table').on('change','.dp-check', function(){
			let ini = $(this).closest('tr');
			// alert($(this).is(':checked'));
			if($(this).is(':checked')){
				let dp_nilai = reset_number_format(ini.find('.amount').html());
				ini.find('.amount-bayar').prop('readonly',false);
				ini.find('.amount-bayar').val(dp_nilai);
			}else{
				ini.find('.amount-bayar').prop('readonly',true);
				ini.find('.amount-bayar').val(0);
			}
			dp_table_update();
		});

		$('#dp_list_table').on('change','.amount-bayar', function(){
			let ini = $(this).closest('tr');
			let amount_dp = reset_number_format(ini.find('.amount').html());
			if ($(this).val() > amount_dp) {
				$(this).val(amount_dp);
				alert("dana dp tidak cukup");
			}else{
				dp_table_update();
			}
		});
		
		$('.btn-save-dp').click(function(){
			$('#form-dp').submit();
		});

});

function check_bayar(ini){
	var bayar = reset_number_format(ini.find('.piutang').html());
	ini.find('.bayar-piutang').val(bayar);
	ini.find('.sisa-piutang').html(0);
}

function undo_bayar(ini){
	var bayar = ini.find('.piutang').html();
	ini.find('.bayar-piutang').val(0);
	ini.find('.sisa-piutang').html(bayar);
}

function update_total_bayar(){
	var total_bayar = 0; var total_piutang = 0;
	var pembulatan = $("[name=pembulatan]").val();
	var total_nilai_bayar = reset_number_format($('.total_nilai_bayar').html());

	$('#general_table .bayar-piutang').each(function(){
		var bayar = reset_number_format($(this).val());
		if (bayar == '') {
			bayar = 0;
		};

		total_bayar += parseInt(bayar);
	});

	$('#general_table .sisa-piutang').each(function(){
		var sisa = reset_number_format($(this).html());
		if (sisa == '') {
			sisa = 0;
		};
		total_piutang += parseInt(sisa);
		// alert($(this).html());
	});
	
	var selisih = total_nilai_bayar - total_bayar + parseInt(pembulatan);
	// alert(selisih);

	$('.total_bayar').html(change_number_format(total_bayar));
	$('.total_sisa_piutang').html(change_number_format(total_piutang));
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
    	
    	if (tanggal_transfer != '') {
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
    	}else if (bayar_id ==  4) {
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
