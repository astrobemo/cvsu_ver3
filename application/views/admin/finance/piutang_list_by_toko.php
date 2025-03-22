<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<h3>Daftar Kontra Bon</h3>
						<div>
							<ol id='kontra-list'></ol>
						</div>
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
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<form>
							Transaksi : <input readonly name='tanggal_start' class='date-picker' style='max-width:150px;' value="<?=is_reverse_date($tanggal_start)?>">
							s/d <input readonly name='tanggal_end' class='date-picker' style='max-width:150px;' value="<?=is_reverse_date($tanggal_end)?>">
							<button class='btn btn-xs btn-default'><i class='fa fa-search'></i></button>
						</form>
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col" rowspan="2">
										Nama Customer
									</th>
									<?foreach ($this->toko_list_aktif as $row) {
										${'total_'.$row->id} = 0;
										${'kontra_'.$row->id} = 0;
										?>
										<th scope="col" colspan="2">
											<?=$row->nama;?>
										</th>
									<?}?>
									<th scope="col" rowspan="2">
										Actions
									</th>
								</tr>
								<tr>									
									<?foreach ($this->toko_list_aktif as $row) {?>
										<th scope="col">
											Piutang
										</th>
										<th scope="col" style="border-right:1px solid #ddd">
											Kontra
										</th>
									<?}?>
									
								</tr>
							</thead>
							<tbody>
								<?
								foreach ($piutang_list as $row) { ?>
									<tr>
										<td>
											<?=$row->nama_customer;?>
										</td>
										<?foreach ($this->toko_list_aktif as $row2) {
											$sisa_piutang = $row->{'sisa_piutang_'.$row2->id};
											${'total_'.$row2->id} += $sisa_piutang;
											$sisa_kontra = $row->{'sisa_kontra_'.$row2->id};
											${'kontra_'.$row2->id} += $sisa_kontra;
											${'tanggal_start_'.$row2->id} = $row->{'tanggal_start_'.$row2->id};
											${'tanggal_end_'.$row2->id} = $row->{'tanggal_end_'.$row2->id};
											?>
											
											<td>
												<?=number_format($sisa_piutang,'0',',','.');?>
											</td>
											<td>
												<?=number_format($sisa_kontra,'0',',','.');?>
											</td>
											
										<?}?>
										<td>
											<!-- <a href="<?=base_url().rtrim(base64_encode('finance/piutang_list_detail'),'=').'/?customer_id='.$row->customer_id;?>" class="btn btn-xs blue"><i class='fa fa-search'></i></a> -->
											<?foreach ($this->toko_list_aktif as $row2) {
												if (${ 'total_'.$row2->id} != 0) {?>
													<a href="<?=base_url().rtrim(base64_encode('finance/piutang_payment_form'),'=').'/?customer_id='.$row->customer_id;?>&toko_id=<?=$row2->id;?>&tanggal_start=<?=${"tanggal_start_".$row2->id};?>&tanggal_end=<?=${"tanggal_end_".$row2->id};?>" class="btn btn-xs blue"> Pelunasan <?=$row2->nama;?></a>
												<?}?>
											<?}?>
											<!-- <a href="#portlet-config" data-toggle="modal" class="btn btn-xs green btn-show-kontra"> Kontra Bon</a> -->
											<span hidden class='pembayaran_piutang_id'><?=$row->pembayaran_piutang_id;?></span>
											<span hidden class='sisa_kontra_data'><?=$row->sisa_kontra_data;?></span>
											
										</td>
									</tr>
								<?}?>
							</tbody>
							<tfoot>
								<tr>
									<th>TOTAL</th>
									<?foreach ($this->toko_list_aktif as $row) {?>
										<th>
											<?=number_format(${'total_'.$row->id},'0',',','.');?>
										</th>
										<th>
											<?=number_format(${'kontra_'.$row2->id},'0',',','.');?>
										</th>
									<?}?>
									<th></th>
								</tr>
							</tfoot>
						</table>
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

	TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=amount]').val() != '' ) {
			$('#form_add_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

	$(document).on('click','.btn-show-kontra', function(){
		$("#kontra-list").html('');
		var pemb_piutang_id = $(this).closest('tr').find('.pembayaran_piutang_id').html().split(',');
		var kontra_data = $(this).closest('tr').find('.sisa_kontra_data').html().split(',');
		for (let i = 0; i < pemb_piutang_id.length; i++) {
			$('#kontra-list').append(`<li>
				<a href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=${pemb_piutang_id[i]}" class='btn btn-xs default'>Kontra - ${change_number_format(kontra_data[i])}</a>
			</li>`);
		}
	});

});
</script>
