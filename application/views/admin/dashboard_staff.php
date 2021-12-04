'<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>

		<div class="page-content">
			<div class='container'>
				<div class="row margin-top-10">
					<div class="col-md-12">
						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Dashboard</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
									<!-- <div class="btn-group btn-group-devided" data-toggle="buttons">
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="option1">Today</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Week</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Month</label>
									</div> -->
								</div>
							</div>
							<div class="portlet-body">

								<div class='note note-info'>
									<b>INFO : </b> <br/>
									1. Menu <b><i class='fa fa-shopping-cart'></i>Transaksi<i class='fa fa-arrow-right'></i>Laporan Penerimaan</b> kini berada di Menu <b> <i class='icon-graph'></i>Laporan<i class='fa fa-arrow-right'></i>Laporan Penerimaan</b>
								</div>

								<?if (is_posisi_id() != 6 ) { ?>
									<hr/>
									<h4>Catatan Pesanan</h4>
									<table class='table' id='note_order_table'>
										<thead>
											<tr>
												<th>No</th>
												<th>Tanggal Input</th>
												<th>Tanggal Target</th>
												<th>Customer</th>
												<th>Status - Barang - Qty - Harga</th>
												<!-- <th>Status</th> -->
												<?//if (is_posisi_id() < 4) { ?>
													<th>Action</th>
												<?//}?>
											</tr>
										</thead>
										<tbody>
											<?
										$idx = 1;
										foreach (get_note_order() as $row) {?>
											<tr <?/*style="<?=($row->matched == 1 ? 'border:2px solid red' : '');?>" */?>>
												<td>
													<?=$idx;?>
													
												</td>
												<td><span class='tanggal_note_order'><?=is_reverse_datetime2($row->tanggal_note_order);?></span> </td>
												<td><span class='tanggal_target'><?=is_reverse_date($row->tanggal_target);?></span></td>
												<td>
													<span class='tipe_customer' hidden><?=$row->tipe_customer?></span>
													<span class='customer_id' hidden><?=$row->customer_id;?></span>
													<span class='nama_customer'><?=$row->nama_customer;?></span> - 
													<span class='contact_info'><?=$row->contact_info;?></span>
												</td>
												<td>
													<?	
														$status = $row->status;
														$nama_barang = explode('??', $row->nama_barang);
														$status = explode(',', $row->status);
														$barang_id = explode(',', $row->barang_id);
														$warna_id = explode(',', $row->warna_id);
														$tipe_barang = explode(',', $row->tipe_barang);
														$nama_warna = explode(',', $row->nama_warna);
														$qty = explode(',', $row->qty);
														$roll = explode(',', $row->roll);
														$harga = explode(',', $row->harga);
														$note_order_detail_id = explode(',', $row->note_order_detail_id);
														$matched = explode('??', $row->matched);
														$done_by = explode(',', $row->done_by);
														$done_time = explode(',', $row->done_time);
													?>
													
													<table>
														<?
														if ($row->nama_barang != '') {
															foreach ($nama_barang as $key => $value) {?>
																<tr style="<?=($status[$key] == 1 ? 'text-decoration:line-through' : '' );?>">
																	<td style='width:100px'>
																		<?if ($matched[$key] == 1) { ?>
																			<span style='color:red'><i class='fa fa-flag'></i></span>
																		<?}?>
																		<span class='tipe_barang' hidden><?=$tipe_barang[$key];?></span>
																		<span class='barang_id' hidden><?=$barang_id[$key];?></span>
																		<span class='note_order_detail_id' hidden><?=$note_order_detail_id[$key];?></span>
																		<span class='note_order_id' hidden><?=$row->id;?></span>
																		<?=($row->tipe_barang==1 ? 'terdaftar' : 'tidak terdaftar');?></td>
																	<td style='width:150px'><span class='nama_barang'><?=$value;?></span></td>
																	<td style='width:100px'><span class='warna_id' hidden><?=$warna_id[$key];?></span> <span class='nama_warna'><?=$nama_warna[$key];?></span> </td>
																	<td style='width:50px'><span class='qty'><?=is_qty_general($qty[$key]);?></span></td>
																	<td style='width:50px'><span class='roll'><?=$roll[$key];?></span></td>
																	<td style="width:50px">
																		<span class='harga'><?=number_format($harga[$key],'0',',','.');?></span>
																	</td>
																	<td style="width:50px;" <?=($status == 1 ? 'hidden' : '' ); ?> >
																		<i class='fa fa-edit btn-edit-note' style='cursor:pointer; color:green'></i>
																		<i class='fa fa-times btn-remove-item-note' style='cursor:pointer; color:red'></i>
																	</td>
																	<td>
																		<span class='status' hidden><?=$status[$key];?></span>
																		<?if ($status[$key] == 1) { ?>
																			<button class='btn btn-xs blue check_note_order'><i class='fa fa-check'></i> completed <br/> by <?=is_get_username($done_by[$key]);?> <br/><?=is_reverse_datetime($done_time[$key]);?></button>
																		<?}elseif($status[$key] == -1){?>
																			<button class='btn btn-xs red check_note_order'><i class='fa fa-times'></i> cancel by <?=is_get_username($done_by[$key]);?> <?=is_reverse_datetime($done_time[$key]);?></button>
																		<?}else{?>
																			<button style='display:none' class='btn btn-xs default btn-reminder'> <i class='fa fa-plus'></i> <i class='fa fa-clock-o'></i></button>
																			<button class='btn btn-xs default check_note_order'> completed</button>
																		<?}?>
																		
																	</td>
																</tr>
															<?}
														}
														?>
													</table>
												</td>
												<?//if (is_posisi_id() < 4) { ?>
													<td>
														<span class='id' hidden><?=$row->id;?></span>
														<form hidden action="<?=base_url('admin/set_reminder');?>" hidden class='form-reminder'>
															<input name='note_order_id' value="<?=$row->id;?>" hidden>
															<input name='reminder' class='form_datetime'> <button><i class='fa fa-check'></i></button>
														</form>
														<button class='btn btn-xs blue btn-add'> <i class='fa fa-plus'></i></button>
														<button class='btn btn-xs green btn-edit'> <i class='fa fa-edit'></i></button>
													</td>
												<?//}?>
											</tr>
										<?$idx++;}?>
										</tbody>
									</table>
								<?}?>

							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>


		<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
$(document).ready(function() {   
	
	
				$('#note_order_table').on('dblclick','.check_note_order', function(){
					var id = $(this).closest('tr').find('.note_order_detail_id').html();
					var status = 1;
					var done_by = $(this).closest('tr').find('.status').html();
					if (done_by == '1' || done_by == '-1') {
						status = 0;
					};
					// alert(status);
					window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
				});

				$('#note_order_table').on('dblclick','.btn-remove', function(){
					var id = $(this).closest('tr').find('.id').html();
					var status = -1;
					// alert(status);
					window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
				});

				// $('.btn-remove-nota-order').click( function(){
				// 	var id = $(this).closest('tr').find('.id').html();
				// 	var status = -1;
				// 	// alert(status);
				// 	window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
				// });

				// btn-remove-nota-order

				$("#note_order_table").on('click','.btn-edit-note', function(){
					var form = '#form_add_note_order_detail';
					var ini = $(this).closest('tr');
					$(form+" [name=id]").val(ini.find('.id').html());
					$(form+" [name=tanggal_note_order]").val(ini.find('.tanggal_note_order').html());
					$(form+" [name=tanggal_target]").val(ini.find('.tanggal_target').html());
					
					var tipe_customer = ini.find('.tipe_customer').html();
					$(form+" [name=tipe_customer][value="+tipe_customer+"]").prop("checked", true);
					$.uniform.update($(form+" [name=tipe_customer]"));
					if (tipe_customer == 1) {
			    		$('.note-customer').show();
			    		$('.note-non-customer').hide();
			    	}else{
			    		$('.note-customer').hide();
			    		$('.note-non-customer').show();
			    	};

					$(form+" [name=customer_id]").val(ini.find('.customer_id').html());
					$(form+" [name=nama_customer]").val(ini.find('.nama_customer').html());
					$(form+" [name=contact_info]").val(ini.find('.contact_info').html());
					
					var tipe_barang = ini.find('.tipe_barang').html();
					$(form+" [name=tipe_barang][value="+tipe_barang+"]").prop("checked", true);
					$.uniform.update($(form+" [name=tipe_barang]"));
					if (tipe_barang == 1) {
			    		$('#barang_terdaftar').show();
			    		$(form+' [name=nama_barang]').val('');
			    		$('#barang_tidak_terdaftar').hide();
			    	}else if (tipe_barang == 2) {
			    		$('#barang_terdaftar').hide();
						$(form+" [name=nama_barang]").val(ini.find('.nama_barang').html());
			    		$('#barang_tidak_terdaftar').show();
			    	};


					$(form+" [name=barang_id]").select2("val",ini.find('.barang_id').html());
					
					var warna_id = ini.find('.warna_id').html();
					if (warna_id == -1) {
						$('#warna_tidak_terdaftar').show();
						$(form+' [name=nama_warna]').val(ini.find('.nama_warna').html());
					}else{
						$('#warna_tidak_terdaftar').hide();
						$(form+' [name=nama_warna]').val('');
					}

					$(form+" [name=note_order_id]").val(ini.find('.note_order_id').html());
					$(form+" [name=warna_id]").select2("val",warna_id);
					$(form+" [name=note_order_detail_id]").val(ini.find('.note_order_detail_id').html());
					$(form+" [name=qty]").val(ini.find('.qty').html());
					$(form+" [name=roll]").val(ini.find('.roll').html());
					$(form+" [name=harga]").val(ini.find('.harga').html());
					$("#portlet-config-note-order-detail").modal('toggle');
				});
				
				$("#note_order_table").on('click','.btn-remove-item-note', function(){
					var form = '#form_add_note_order_detail';
					var ini = $(this).closest('tr');
					var note_order_detail_id = ini.find('.note_order_detail_id').html();
					bootbox.confirm("Hapus item?", function(respond){
						if (respond) {
							var data_st = {};
					    	data_st['note_order_detail_id'] = note_order_detail_id;
					    	var url = 'admin/note_order_item_remove';
					    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					    		if (data_respond == 'OK') {
						    		ini.remove();
					    		}else{
					    			alert("Error");
					    		};
					   		});
						};
					});



				});

				$("#note_order_table").on('click','.btn-edit', function(){
					var form = '#form_add_note_order';
					var ini = $(this).closest('tr');
					$(form+" [name=id]").val(ini.find('.id').html());
					$(form+" [name=tanggal_note_order]").val(ini.find('.tanggal_note_order').html());
					$(form+" [name=tanggal_target]").val(ini.find('.tanggal_target').html());
					
					var tipe_customer = ini.find('.tipe_customer').html();
					$(form+" [name=tipe_customer][value="+tipe_customer+"]").prop("checked", true);
					$.uniform.update($(form+" [name=tipe_customer]"));
					if (tipe_customer == 1) {
			    		$('.note-customer').show();
			    		$('.note-non-customer').hide();
			    	}else{
			    		$('.note-customer').hide();
			    		$('.note-non-customer').show();
			    	};

					$(form+" [name=customer_id]").val(ini.find('.customer_id').html());
					$(form+" [name=nama_customer]").val(ini.find('.nama_customer').html());
					$(form+" [name=contact_info]").val(ini.find('.contact_info').html());
					
					var tipe_barang = ini.find('.tipe_barang').html();
					$(form+" [name=tipe_barang][value="+tipe_barang+"]").prop("checked", true);
					$.uniform.update($(form+" [name=tipe_barang]"));
					if (tipe_barang == 1) {
			    		$('#barang_terdaftar').show();
			    		$('#barang_tidak_terdaftar').hide();
			    	}else if (tipe_barang == 2) {
			    		$('#barang_terdaftar').hide();
			    		$('#barang_tidak_terdaftar').show();
			    	};


					$(form+" [name=barang_id]").val(ini.find('.barang_id').html());
					$(form+" [name=warna_id]").val(ini.find('.warna_id').html()).trigger('change.select2');;
					$(form+" [name=nama_barang]").val(ini.find('.nama_barang').html());
					$(form+" [name=qty]").val(ini.find('.qty').html());
					$(form+" [name=harga]").val(ini.find('.harga').html());
					$("#detail-on-order").hide();
					$("#portlet-config-note-order").modal('toggle');
					
				});

				$("#note_order_table").on('click','.btn-add', function(){
					var id = $(this).closest('tr').find('.id').html();
					var form = $('#form_add_note_order_detail');
					form.find('[name=note_order_id]').val(id);
					// alert(form.html());
					$("#portlet-config-note-order-detail").modal('toggle');
				});

				$("#note_order_table").on('click', '.btn-reminder', function(){
					var ini = $(this).closest('tr');
					// $('.form-reminder').hide();
					ini.find('.form-reminder').toggle();
				});

    
});
</script>
<!-- END JAVASCRIPTS -->
