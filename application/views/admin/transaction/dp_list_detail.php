<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/dp_masuk_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> DP Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='customer_id' value='<?=$customer_id;?>' hidden>
			                    	<input readonly class='form-control' name='nama_customer' value="<?=$nama_customer;?>">
			                    </div>
			                </div>	

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jenis Pembayaran<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name='pembayaran_type_id' class='form-control' id="pembayaran_type_id" >
	                    				<?foreach ($pembayaran_type_list as $row) { ?>
	                    					<option value='<?=$row->id;?>'><?=$row->nama?></option>
	                    				<?}?>
	                    			</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal DP<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal' value="<?=date('d/m/Y');?>">
			                    </div>
			                </div>


			                <div class="form-group type_2">
			                    <label class="control-label col-md-4">Nama Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_6">
			                    <label class="control-label col-md-4">No Rek Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_rek_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">No Giro<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_giro' class='form-control' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Nilai<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='amount' class='form-control amount-number' >
			                    </div>
			                </div>

			                <div class="form-group type_6 type_4 type_3">
			                    <label class="control-label col-md-4">Penerima<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_penerima' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='keterangan' class='form-control'>
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
						<form action="<?=base_url('transaction/dp_masuk_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> DP Edit</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='dp_masuk_id' value='<?=$dp_masuk_id;?>' hidden>
			                    	<input name='customer_id' value='<?=$customer_id;?>' hidden>
			                    	<input readonly class='form-control' name='nama_customer' value="<?=$nama_customer;?>">
			                    </div>
			                </div>	

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jenis Pembayaran<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name='pembayaran_type_id' class='form-control' id="pembayaran_type_id_edit" >
	                    				<?foreach ($pembayaran_type_list as $row) { ?>
	                    					<option value='<?=$row->id;?>'><?=$row->nama?></option>
	                    				<?}?>
	                    			</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal DP<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal' value="<?=date('d/m/Y');?>">
			                    </div>
			                </div>


			                <div class="form-group type_2">
			                    <label class="control-label col-md-4">Nama Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_6">
			                    <label class="control-label col-md-4">No Rek Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_rek_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">No Giro<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_giro' class='form-control' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Nilai<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='amount' class='form-control amount-number' >
			                    </div>
			                </div>

			                <div class="form-group type_6 type_4 type_3 ">
			                    <label class="control-label col-md-4">Penerima<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_penerima' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='keterangan' class='form-control'>
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

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<?if ($dp_masuk_id_group == '') {?>
							<form>
								<table>
									<tr>
										<td>Tanggal</td>
										<td>
											<div class="input-group input-large date-picker input-daterange">
												<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="from" value='<?=is_reverse_date($from); ?>'>
												<span class="input-group-addon">
												s/d </span>
												<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="to" value='<?=is_reverse_date($to); ?>'>
											</div>
										</td>
										<td>
											<button class='btn btn-sm green'><i class='fa fa-search'></i></button>
										</td>
									</tr>
								</table>
							</form>
						<?}else{?>
							<a style='width:100%' class='btn btn-default green' href="<?=base_url().is_setting_link('transaction/dp_list_detail')?>/<?=$customer_id;?>"><i class='fa fa-arrow-left' style='color:white'></i> Perlihatkan Semua Transaksi DP</a>
						<?}?>
						<hr/>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Deskripsi
									</th>
									<th scope="col">
										DP Masuk
									</th>
									<th scope="col">
										DP Keluar
									</th>
									<th scope="col">
										Saldo
									</th>
									<th scope="col">
										No Transaksi DP
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<tr <?=($dp_masuk_id_group != '' ? 'hidden' : '')?> >
									<td>
										
									</td>
									<td>
										<b>Saldo Awal</b>
									</td>
									<td></td>
									<td>
									</td>
									<td>
										<span style='font-size:1.2em'><b><?=number_format($saldo_awal,'0',',','.');?></b></span> 
									</td>
									<td>
									</td>
									<td>
									</td>
								</tr>
								<?
								$saldo_akhir = $saldo_awal;
								foreach ($dp_list_detail as $row) { ?>
									<tr>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<span class='tanggal'><?=is_reverse_date($row->tanggal);?></span>
										</td>
										<td><?if ($row->type == 'i') {
												$data_bayar = explode('??', $row->pembayaran_data);
												$nama_penerima = $data_bayar[0];
												$nama_bank = $data_bayar[1];
												$no_rek_bank = $data_bayar[2];
												$no_giro = $data_bayar[3];
												$jatuh_tempo = is_reverse_date($data_bayar[4]);
												$dp_masuk_id_grouping = $row->id;
												?>
												<?=$row->bayar_dp;?> : 
												<?
												$type_2 = '';
												$type_3 = '';
												$type_4 = '';
												$type_6 = '';
												${'type_'.$row->pembayaran_type_id} = 'hidden';
												?>
												<ul>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_6;?> >Penerima :<b><span class='nama_penerima' ><?=$nama_penerima?></span></b></li>
													<li <?=$type_2;?> >Bank : <b><span class='nama_bank'><?=$nama_bank?></span></b></li>
													<li <?=$type_2;?> <?=$type_6;?> >No Rek : <b><span class='no_rek_bank'><?=$no_rek_bank?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?>>Jatuh Tempo : <b><span class='jatuh_tempo' ><?=$jatuh_tempo;?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?> >No Giro : <b><span class='no_giro' ><?=$no_giro;?></span></b></li>
													<li>Keterangan : <b><span class='keterangan'><?=$row->keterangan;?></span></li></b>
												</ul>
											<?}else if($row->type == 'pj'){?>
												Transaksi Penjualan : <a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail').'?id='.$row->id;?>" target='_blank'><?=($row->pembayaran_data != '' ? $row->pembayaran_data : 'no faktur ?') ;?></a>
											<?}else if($row->type == 'pp'){?>
												<a href="<?=base_url().is_setting_link('finance/piutang_payment_form').'?id='.$row->id;?>" target='_blank'><?=($row->pembayaran_data != '' ? $row->pembayaran_data : '') ;?></a>
											<?}?>
										</td>
										<td>
											<?if ($row->dp_masuk == null || $row->dp_masuk == 0) {
												echo '-';
											}else{ ?>
												<span class='amount'><?=number_format($row->dp_masuk,'0',',','.');?></span>
											<?}?>
											
										</td>
										<td>
											<?if ($row->dp_keluar == null || $row->dp_keluar == 0) {
												echo '-';
											}else{ ?>
												<span class='amount'><?=number_format($row->dp_keluar,'0',',','.');?></span>
											<?}?>
										</td>
										<td>
											<?$saldo_akhir += $row->dp_masuk - $row->dp_keluar;?>
											<?=number_format($saldo_akhir,'0',',','.');?>
										</td>
										<td>
											<?
												if ($row->type != 'i') {
													$data_bayar = explode('??', $row->keterangan);
													$nama_penerima = $data_bayar[0];
													$nama_bank = $data_bayar[1];
													$no_rek_bank = $data_bayar[2];
													$no_giro = $data_bayar[3];
													$jatuh_tempo = is_reverse_date($data_bayar[4]);
													$tipe_bayar = $data_bayar[5];
													$keterangan_bayar_type_id = $data_bayar[6];
													$amount_dp = $data_bayar[7];
													$tanggal_dp = is_reverse_date($data_bayar[8]);
													$dp_masuk_id_grouping = $data_bayar[9];
												}
											?>
											<a href="<?=base_url().is_setting_link('transaction/dp_list_detail');?>/<?=$customer_id?>?dp_masuk_id=<?=$dp_masuk_id_grouping?>" class='no_faktur_lengkap' title='click untuk grouping'><?=$row->no_faktur_lengkap;?></a>
											<?if ($row->type != 'i') {

												?>
												( <?=$tipe_bayar;?> )
												<?
												$type_2 = '';
												$type_3 = '';
												$type_4 = '';
												$type_6 = '';
												${'type_'.$keterangan_bayar_type_id} = 'hidden';
												?>
												<div hidden>
													<ul>
														<li <?=$type_3;?> <?=$type_4;?> <?=$type_6;?> >Penerima :<b><span class='nama_penerima' ><?=$nama_penerima?></span></b></li>
														<li <?=$type_2;?> >Bank : <b><?=$nama_bank?></b></li>
														<li <?=$type_2;?> <?=$type_6;?> >No Rek : <b><?=$no_rek_bank?></b></li>
														<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?>>Jatuh Tempo : <b><?=$jatuh_tempo;?></b></li>
														<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?> >No Giro : <b><?=$no_giro;?></b></li>
														<li >Jumlah : <b><?=number_format($amount_dp,'0',',','.');?></b></li>
														<li >Tanggal : <b><?=$tanggal_dp;?></b></li>
													</ul>
												</div>
											<?}?>
										</td>
										<td>
											<span class='bayar_dp_id' hidden><?=$row->bayar_dp_id;?></span>
											<span class='pembayaran_type_id' hidden><?=$row->pembayaran_type_id?></span>
											<span class='penerima' hidden><?=$row->penerima?></span>
											<?if ($row->type == 'i') { ?>
												<a href="#portlet-config-edit" data-toggle='modal' class="btn-xs btn green btn-edit" ><i class="fa fa-edit"></i> </a>
												<a href="<?=base_url();?>transaction/dp_print?id=<?=$row->id;?>" class="btn-xs btn blue btn-print" target='blank'><i class="fa fa-print"></i> </a>
											<?}?>
										</td>
									</tr>
								<? } ?>

							</tbody>
						</table>
					</div>
					<div>
	                  	<a href="<?=base_url().is_setting_link('transaction/dp_list');?>" class="btn btn-lg default button-previous">Back</a>
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

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	$('[data-toggle="popover"]').popover();
	$('.type_2').hide();


	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=amount]').val() != '' ) {
			$('#form_add_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

	$('.btn-edit-save').click(function(){
		if ($('#form_edit_data [name=tanggal]').val() != '' && $('#form_edit_data [name=amount]').val() != '' ) {
			$('#form_edit_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

	$('#general_table').on('click','.btn-edit', function(){
		var ini = $(this).closest('tr');

		$('#form_edit_data [name=dp_masuk_id]').val(ini.find('.id').html());
		$('#form_edit_data [name=pembayaran_type_id]').val(ini.find('.pembayaran_type_id').html());
		$("#pembayaran_type_id_edit").change();
		$('#form_edit_data [name=tanggal]').val(ini.find('.tanggal').html());
		$('#form_edit_data [name=nama_penerima]').val(ini.find('.nama_penerima').html());
		$('#form_edit_data [name=nama_bank]').val(ini.find('.nama_bank').html());
		$('#form_edit_data [name=no_rek_bank]').val(ini.find('.no_rek_bank').html());
		$('#form_edit_data [name=no_giro]').val(ini.find('.no_giro').html());
		$('#form_edit_data [name=jatuh_tempo]').val(ini.find('.jatuh_tempo').html());
		$('#form_edit_data [name=amount]').val(ini.find('.amount').html());
		$('#form_edit_data [name=keterangan]').val(ini.find('.keterangan').html());

	});

	$("#pembayaran_type_id").change(function(){
		let id = $(this).val();
		$('#form_add_data .form-group').show();
		$('#form_add_data .type_'+id).hide();
	});

	$("#pembayaran_type_id_edit").change(function(){
		let id = $(this).val();
		$('#form_edit_data .form-group').show();
		$('#form_edit_data .type_'+id).hide();
	});

});
</script>
