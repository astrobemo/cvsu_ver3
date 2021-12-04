<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">

#form-get select{
	border:none; border-bottom:1px solid #ddd; width:150px;
}


#giro_table .add_item{
	background: #ffb6c1;
}


</style>

<div class="page-content">
	<div class='container'>
		<?
			$giro_setor_id = '';
			$keterangan = '';
			
			$tanggal_setor = date('d/m/Y');
			
			$g_total = 0;
			$readonly = '';
			$disabled = '';

			foreach ($giro_data as $row) {
				$giro_setor_id = $row->id;
				$keterangan = $row->keterangan;
				$tanggal_setor = is_reverse_date($row->tanggal);
			}

			if (is_posisi_id() == 6 ) {
				$readonly = 'readonly';
				$disabled = 'disabled';
			}
			// if ($status != 1 ) {
			// 	$readonly = 'readonly';
			// }

			// if ($penjualan_id == '') {
			// 	$disabled = 'disabled';
			// }
		?>
		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<table class="table table-hover table-striped" id="giro_table">
							<thead>
								<tr>
									<th scope="col">
										ID
									</th>
									<th scope="col">
										Customer
									</th>
									<th scope="col">
										Tanggal Bayar
									</th>
									<th scope="col">
										Nama Bank
									</th>
									<th scope="col">
										No Giro
									</th>
									<th scope="col">
										TGL JT
									</th>
									<th scope="col">
										Nominal
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$i =1; $total_nominal = 0;
								foreach ($giro_list_detail as $row) { ?>
									<tr>
										<td>
											<?=$i;?>
											<?if ($giro_setor_id != '') {
												echo $row->id;
											}?>
										</td>
										<td>
											<?=$row->nama_customer;?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal_transfer);?>
										</td>
										<td>
											<?=$row->nama_bank?>
										</td>
										<td>
											<?=$row->no_giro;?>
										</td>
										<td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td>
										<td>
											<?if ($giro_setor_id != '') {
												$total_nominal += $row->amount;
											}?>
											<span class='amount' hidden><?=$row->amount;?></span> <?=number_format($row->amount,'0',',','.');?>
										</td>
										<td class='hidden-print'>
											<?if ($giro_setor_id != '') { ?>
												<span class='giro_setor_detail_id' hidden><?=$row->id;?></span>
											<?}else{?>
												<label><input type='checkbox' name='status_<?=$row->pembayaran_piutang_nilai_id?>' class='lunas-check' >
												pilih </label>
											<?}?>
											<input name='bayar_<?=$row->pembayaran_piutang_nilai_id;?>' hidden value="<?=$row->pembayaran_piutang_nilai_id;?>"> 
										</td>
									</tr>
								<?
								$i++; 
								} ?>
								<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc;'>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>TOTAL</td>
									<td><b><span class='total_setor'><?=number_format($total_nominal,'0',',','.');?></span></b></td>
									<td>
										<?if ($giro_setor_id == '') { ?>
											<label>
											<input type='checkbox' name='check_all' id='check_all'> pilih semua </label> 
										<?}?>
										<!-- <button class='btn btn-sm red btn-reset-form'>reset all</button> -->
									</td>

								</tr>
							</tbody>
						</table>

						<form action="<?=base_url('transaction/penjualan_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Penjualan Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control input1' name='giro_list_detail_id' id="search_giro" multiple>
			                    		<?foreach ($giro_list_detail as $row) { ?>
			                    			<option value='<?=$row->id;?>'><?=$row->no_giro?> | <?=is_reverse_date($row->jatuh_tempo)?> | <?=$row->nama_bank;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>			                

			                
						</form>
					</div>

					<div class="modal-footer">
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
						<form action='' id='form-get' method='get'>
							<table id='tbl-form-get' width='50%'>
								<?if ($giro_setor_id == '') { ?>
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
											<select <?if ($giro_setor_id != '') {?> disabled <?}?> name='toko_id' id="toko_id_select" style='width:100%;'>
												<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->toko_list_aktif as $row) { ?>
													<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
							</table>
						</form>
						<?if ($giro_setor_id == '') {?>
							<button <?if ($toko_id == '' && $supplier_id == '') { ?> disabled <?}?> class='btn btn-xs default btn-form-get'><i class='fa fa-search'></i> Cari</button>
						<?}?>

						<!-- table-striped table-bordered  -->
						<hr/>
						<?if ($giro_setor_id == '') { ?>
							<a href="#portlet-config" data-toggle='modal' class='btn btn-md blue btn-add'>+ GIRO</a>
							<form method="post" action="<?=base_url()?>finance/giro_setor_insert" id='form-bayar'>
						<?}?>
							<table class="table table-hover table-striped" id="general_table">
								<thead>
									<tr>
										<th scope="col">
											ID
										</th>
										<th scope="col">
											Customer
										</th>
										<th scope="col">
											Tanggal Bayar
										</th>
										<th scope="col">
											Nama Bank
										</th>
										<th scope="col">
											No Giro
										</th>
										<th scope="col">
											TGL JT
										</th>
										<th scope="col">
											Nominal
										</th>
										<th scope="col" class='hidden-print'>
											Action
										</th>
									</tr>
								</thead>
								<tbody>
								<?
								$i =1; $total_nominal = 0;
								if ($giro_setor_id != '') {
									foreach ($giro_list_detail as $row) { ?>
									<tr>
										<td>
											<?if ($giro_setor_id != '') {
												echo $row->id;
											}?>
										</td>
										<td>
											<?=$row->nama_customer;?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal_transfer);?>
										</td>
										<td>
											<?=$row->nama_bank?>
										</td>
										<td>
											<?=$row->no_giro;?>
										</td>
										<td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td>
										<td>
											<?if ($giro_setor_id != '') {
												$total_nominal += $row->amount;
											}?>
											<span class='amount' hidden><?=$row->amount;?></span> <?=number_format($row->amount,'0',',','.');?>
										</td>
										<td class='hidden-print'>
											<?if ($giro_setor_id != '') { ?>
												<span class='giro_setor_detail_id' hidden><?=$row->id;?></span>
											<?}else{?>
												<label><input type='checkbox' name='status_<?=$row->pembayaran_piutang_nilai_id?>' class='lunas-check' >
												pilih </label>
											<?}?>
											<input name='bayar_<?=$row->pembayaran_piutang_nilai_id;?>' hidden value="<?=$row->pembayaran_piutang_nilai_id;?>"> 
										</td>
									</tr>
								<?
									$i++; 
								}
								
								} ?>
								<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc;'>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>TOTAL</td>
									<td><b><span class='total_setor'><?=number_format($total_nominal,'0',',','.');?></span></b></td>
									<td>
										<?if ($giro_setor_id == '') { ?>
											<label>
											<input type='checkbox' name='check_all' id='check_all'> pilih semua </label> 
										<?}?>
										<!-- <button class='btn btn-sm red btn-reset-form'>reset all</button> -->
									</td>

								</tr>
							</tbody>
							</table>
							<?if ($giro_setor_id != '') { ?>
								<form method="post" action="<?=base_url()?>finance/pembayaran_hutang_insert" id='form-bayar'>
							<?}?>
								<input name='toko_id' value="<?=$toko_id;?>" hidden>

								<input name='giro_setor_id' value="<?=$giro_setor_id;?>" hidden>

							<table>
								<tr>
									<td style='vertical-align:top;'>
										
										<?
										$idx = 1; $total_bayar_hutang = 0;?>
										<div class='list-group'>
											<?$title = '-';
											?>
											<div class='info-bayar-div' id='bayar-section'>
												<table class='bayar-info' width='100%;' style='margin-bottom:5px;'>
													<tr class='tanggal-transfer'>
														<td>Tanggal  Setor</td>
														<td class='padding-rl-5'> : </td>
														<td>
															<input name='tanggal' class='date-picker' value='<?=$tanggal_setor;?>'>
														</td>
													</tr>
													<tr>
														<td>Keterangan</td>
														<td class='padding-rl-5'> : </td>
														<td>
															<input name="keterangan" value="<?=$keterangan;?>">
														</td>
													</tr>
												</table>
												

											</div>
												
										</div>
									</td>
								</tr>
							</table>

						</form>
						

						<hr/>
						<div>
							<?if (is_posisi_id() != 6 ) { ?>
								
							<?}?>
							<button type='button' class='btn btn-lg green hidden-print btn-save-bayar'><i class='fa fa-save'></i> Simpan </button>
			                <a <?=$disabled;?> class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Print </a>
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
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script>
jQuery(document).ready(function() {

	// FormNewPenjualanDetail.init();

	$("#search_giro").select2();
	
	$('#giro_table').dataTable({
        "bStateSave" :true,
    });

   
	var form_group = {};
	var idx_gen = 0;

	<?if ($giro_setor_id == '') { ?>
		update_total_bayar();
	<?}?>

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
			if (days >= 15) {
				bootbox.confirm("Memanggil data lebih dari 15 hari <br/> "+
					"( tergantung dari banyaknya data ) mungkin membuat halaman menjadi lama dimuat <br/> atau bahkan error. "+
					"Anda ingin melanjutkan ? ", function(respond){
						if (respond) {	
							$('#form-get').submit();				
						};
					});
			}else{
				$('#form-get').submit();				
			};
		}
	});

	/*$('#general_table').on('change','.lunas-check', function(){
		var ini = $(this).closest('tr');
		update_total_bayar();
	});*/

	$('#giro_table').on('change','.lunas-check', function(){
		var ini = $(this).closest('tr').html();
		$('#general_table tbody').append("<tr>"+ini+"</tr>");
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


		<?if ($giro_setor_id != '') { ?>
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
			});
		}else{
			$("#general_table .lunas-check").each(function(){
				$(this).prop('checked',false);
				var ini = $(this).closest('tr');
			});
		}
		$.uniform.update('.lunas-check');
		update_total_bayar();
	});
    
    $('.btn-save-bayar').click(function(){
    	// $('#form-bayar').submit();
    	var ini = $(this);
    	var bayar_id = $('[name=pembayaran_type_id]:checked').val();
    	var total_setor = reset_number_format($('.total_setor').html());
    	// alert(total_bayar);

    	$('#form-bayar').submit();    		
    	// if (total_setor != 0 && $('[name=tanggal]').val() != '') {
    	// }else{
    	// 	alert("Total Nota Tidak bisa 0/ Data Tidak lengkap");
    	// };

    });

	<?if ($giro_setor_id != '') { ?>
		$("[name=pembulatan]").change(function(){
			var data = {};
			data['id'] = "<?=$giro_setor_id?>";
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
	<?}?>

   

	$(document).on('click','.btn-remove-nilai', function(e){
		e.preventDefault();
		var ini = $(this);
		var data_id = $(this).attr('id').split('-');
		var id = data_id[1];
		var giro_setor_id = "<?=$giro_setor_id?>";
		bootbox.confirm("Yakin menghapus data pembayaran ini ?", function(respond){
			if (respond) {
				window.location.replace(baseurl+"finance/pembayaran_hutang_nilai_delete?id="+id+"&giro_setor_id="+giro_setor_id);
			};
		});
	});
});

function update_total_bayar(){
	var total_setor = 0;
	$("#general_table .lunas-check").each(function(){
		if ($(this).is(':checked')) {
			var amount = $(this).closest('tr').find('.amount').html();
			total_setor += parseInt(amount);
		};
	});
	$('.total_setor').html(change_number_format(total_setor));

}



</script>
