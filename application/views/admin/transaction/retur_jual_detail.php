<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#tbl-data input[type="text"], #tbl-data select{
	height: 25px;
	width: 50%;
	padding: 0 5px;
}

#qty-table input{
	width: 80px;
	padding: 5px;
}

#stok-info{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
}

.yard-info{
	font-size: 1.5em;
}

.no-faktur-lengkap{
	font-size: 2.5em;
	font-weight: bold;
}

.input-no-border{
	border: none;
}

.subtotal-data{
	font-size: 1.2em;
}

#bayar-data tr td{
	font-size: 1.5em;
	font-weight: bold;
	padding: 0 10px 0 10px;
}

#bayar-data tr td input{
	padding: 0 5px 0 5px;
	border: 1px solid #ddd;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$retur_jual_id = '';
			$customer_id = '';
			$nama_customer = '';
			$gudang_id = '';
			$nama_gudang = '';
			$tanggal = date('d/m/Y');
			$ori_tanggal = '';
			$no_faktur_lengkap = '';

			// $keterangan = '';
			$retur_type_id = '';
			$status = '-';
			$g_total = 0;
			$readonly = '';
			$disabled = '';

			foreach ($retur_data as $row) {
				$retur_jual_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_customer;
				$retur_type_id = $row->retur_type_id; 
				$no_faktur_lengkap = $row->no_faktur_lengkap;
				
				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status = $row->status;
				$status_aktif = $row->status_aktif;
				
				// $keterangan = $row->keterangan;
				
			}

			if ($status != 1) {
				if ( is_posisi_id() != 1 ) {
					$readonly = 'readonly';
				}
			}

			if ($retur_jual_id == '') {
				$disabled = 'disabled';
			}
		?>


		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/retur_jual_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Retur Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control input1' name='retur_type_id' id='retur_type_add'>
		                    			<option <??> value='1'>Customer</option>
		                    			<option <??> value='2'>Non Customer</option>
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

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='add-select-customer'  >
			                    		<select name="customer_id" class='form-control' id='customer_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                    	</div>
			                    	<div id='add-input-customer' hidden='hidden'>
			                    		<input name='nama_keterangan' class='form-control'>
			                    	</div>
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
						<form action="<?=base_url('transaction/retur_jual_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Retur Jual Edit</h3>
							
							
			                <div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' hidden='hidden'>
			                    	<select class='form-control input1' name='retur_type_id' id='retur_type_edit'>
		                    			<option <??> value='1'>Customer</option>
		                    			<option <??> value='2'>Non Customer</option>
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

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='edit-select-customer'>
			                    		<select name="customer_id" class='form-control' id='customer_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                    	</div>
			                    	<div id='edit-input-customer' hidden='hidden'>
			                    		<input name='nama_keterangan' class='form-control'>
			                    	</div>
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
		
		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/retur_jual_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='retur_jual_id' value='<?=$retur_jual_id;?>' hidden='hidden'>
	                    			<select name="gudang_id" class='form-control'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?if ($row->status_default == 1) {echo "selected";}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id_source" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?$i=0;foreach ($retur_barang as $row) { ?>
			                    			<option value="<?=$row->id?>" data-index="<?=$i?>"><?=$row->nama_jual;?> <?=$row->warna_jual;?> - Rp <?=number_format($row->harga_jual,"0",",",".");?></option>
			                    		<? $i++;} ?>
			                    	</select>
									<input type="text" name='barang_id' id="barang_id_add" hidden>
									<input type="text" name='warna_id' id="warna_id_add" hidden>
			                    </div>
			                </div>
							

							<!-- <div class="form-group">
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
			                    	<select name='data_barang' hidden='hidden'>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
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

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control' name="satuan"/>
			                    </div>
			                </div>   -->

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='amount_number form-control' id="hargaJualAdd" name="harga_jual"/>
			                    	<small id="hargaAddNotes"></small>
									<input name='rekap_qty' hidden='hidden'>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-qty">Add Qty</button>
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
						<table id='qty-table'>
							<tr>
								<th>Yard</td>
								<th>Roll</td>
								<th></th>
							</tr>
							<tr>
								<td><input name='qty' class='input1'></td>
								<td><input name='jumlah_roll'></td>
								<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
							</tr>
							<?for ($i=0; $i < 9; $i++) { ?>
								<tr>
									<td><input name='qty'></td>
									<td><input name='jumlah_roll'></td>
									<td></td>
								</tr>
								
							<? }?>
						</table>
						<div class='yard-info'>
							TOTAL QTY: <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL: <span class='jumlah_roll_total' >0</span> 
						</div>


					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-brg-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
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
						<table id='qty-table-edit'>
							<thead>
								<tr>
									<th>Yard</td>
									<th>Roll</td>
									<th></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
						<span class='total_roll' hidden='hidden'></span>
						<div class='yard-info'>TOTAL : <span class='yard_total' >0</span> yard </div>
						<form hidden='hidden' action="<?=base_url()?>transaction/retur_jual_qty_update" id='form-qty-update' method="post">
							<input name='id'>
							<input name='rekap_qty'>
						</form>
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
							
							<!-- <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='id' id="search_no_faktur" class="form-control select2">
			                    </div>
			                </div>	 -->
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
						<form action="<?=base_url('transaction/retur_jual_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Cari Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='retur_jual_id' value='<?=$retur_jual_id;?>' hidden='hidden'>
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

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> retur Baru </a>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<div style='position:absolute; color:red; font-size:4em; margin:-10px 35%'><b>RETUR</b></div>
						<table style='width:100%'>
							<tr>
								<td>
									<table>
										<tr>
											<?if ($retur_jual_id != '') { ?>
												<tr>
													<td colspan='3'>
														<?if ($status != 1) { ?>
															<button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin'><i class='fa fa-key'></i> request open</button>
														<?}else{ ?>
															<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
														<?}?>

													</td>
												</tr>
											<?}?>
								    		<td>Status</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($status == -1) { ?>
								    				<span style='color:red'><b>BATAL</b></span>
								    			<?}elseif ($status == 1) {?>
								    				<span style='color:green'><b>OPEN</b></span>
								    			<?}elseif ($status == 0 && $status != '-') {?>
								    				<span style='color:orange'><b>LOCKED</b></span>
								    			<?}else{}?>
								    		</td>
								    	</tr>
										<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>Customer</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($retur_type_id == 3) { ?>
								    				<?=$nama_keterangan;?>
								    			<?} else{
								    				echo $nama_customer;
								    			}?>
								    		</td>
								    	</tr>
								    </table>
								</td>
								<td class='text-right'>
									<span class='no-faktur-lengkap'> <?=$no_faktur_lengkap;?></span>
								</td>
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
										<?if ($retur_jual_id !='' && $status == 1) {?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Satuan
									</th>
									<th scope="col">
										Jml Yard/KG
									</th>
									<th scope="col">
										Jml Roll
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total Harga
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$i =1; 
								foreach ($retur_detail as $row) { ?>
									<tr>
										<td>
											<?=$i;?> 
										</td>
										<td>
											<span class='nama_jual'><?=$row->nama_barang;?> <?=$row->nama_warna;?></span> 
										</td>
										<td>
											<?=$row->nama_satuan;?>
										</td>
										<td>
											<!-- <input name='qty' class='free-input-sm qty' value="<?=$row->qty;?>">  -->
											<span class='qty'><?=$row->qty;?></span>
										</td>
										<td>
											<!-- <input name='jumlah_roll' class='free-input-sm jumlah_roll' value="<?=$row->jumlah_roll;?>"> -->
											<span class='jumlah_roll'><?=$row->jumlah_roll;?></span> 
										</td>
										<td>
											<input name='harga' <?=$readonly;?> class='free-input-sm amount_number harga' value="<?=number_format($row->harga,'0','.','.');?>"> 
										</td>
										<td>
											<?$subtotal = $row->qty * $row->harga;
											$g_total += $subtotal;
											?>
											<span class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td>
										<td class='hidden-print'>
											<?if ($status == 1 || is_posisi_id() == 1) { ?>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<span class='data_qty' hidden='hidden'><?=$row->data_qty;?></span>
												<a href='#portlet-config-qty-edit' data-toggle='modal' class="btn-xs btn green btn-qty-edit"><i class="fa fa-edit"></i> </a>
												<a class="btn-xs btn red btn-detail-remove"><i class="fa fa-times"></i> </a>
											<?}?>
										</td>
									</tr>
								<?
								$i++; 
								} ?>

								<tr class='subtotal-data'>
									<td colspan='6' class='text-right'><b>TOTAL</b></td>
									<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>

						<hr/>
						<div>
							<button type='button' <?=$disabled;?> <?if ($status != 1) {?> disabled <?}?> class='btn btn-lg red hidden-print btn-close'><i class='fa fa-lock'></i> LOCK </button>
			                <a <?=$disabled;?> href="<?=base_url();?>transaction/retur_jual_print?retur_jual_id=<?=$retur_jual_id;?>" target='_blank' class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Print </a>
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

<script src="<?php echo base_url('assets_noondev/js/form-retur.js'); ?>" type="text/javascript"></script>


<script>

let hargaSource;
const barangSource = <?=json_encode($retur_barang);?>;
const hargaAdd = document.querySelector("#hargaJualAdd");
const hargaAddNotes = document.querySelector("#hargaAddNotes");
const barangIdAdd = document.querySelector("#barang_id_add");
const warnaIdAdd = document.querySelector("#warna_id_add");

jQuery(document).ready(function() {

	FormNewReturDetail.init();

	var form_group = {};
	var idx_gen = 0;
   	var retur_type_id = '<?=$retur_type_id;?>';


	$('[data-toggle="popover"]').popover();


    $('#warna_id_select, #barang_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('#customer_id_select, #customer_id_select_edit').select2({
        allowClear: true
    });

    <?if ($retur_jual_id != '') { ?>
		$('.btn-print').click(function(){
	    	// window.print();
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
			var url = "transaction/get_search_no_faktur_jual";
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
    	var id = $("#form_search_faktur [name=retur_id]").val();
    	if (id != '') {
    		$('#form_search_faktur').submit();
    	};
    });

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

    <?if ($retur_jual_id != '') {?>
    	$('.btn-close').click(function(){
    		var id = "<?=$retur_jual_id;?>";
	    	window.location.replace(baseurl+'transaction/retur_jual_list_close?id='+id);
	    });
    <?}?>

//====================================retur type=============================
	
	$("#retur_type_add").change(function(){
		if ($(this).val() == 1) {
			$('#add-select-customer').show();
			$('#add-input-customer').hide();
		}else{
			$('#add-select-customer').hide();
			$('#add-input-customer').show();
		};
	});


	$("#retur_type_edit").change(function(){
		if ($(this).val() == 1) {
			$('#edit-select-customer').show();
			$('#edit-input-customer').hide();
		}else{
			$('#edit-select-customer').hide();
			$('#edit-input-customer').show();
		};
	});

	$('#customer_id_select').change(function(){
		if (retur_type_id == 1 || retur_type_id == 2) {
			if ($(this).val() == '') {
				var customer_id = $(this).val('');

				notific8('ruby', 'Customer harus dipilih');
	   			$('#customer_id_select').select2("open");
			}else{
				var customer_id = $(this).val();
			}
		};
	});

	$('#form_add_data [name=retur_type_id]').change(function(){
		if ($(this).val() == 1) {
			$('#form_add_data .po_section').hide();
			$('#form_add_data .customer_section').show();
   			// $('#customer_id_select').select2("open");
   			$('#add-nama-keterangan').hide();
   			$('#add-select-customer').show();
		};

		if ($(this).val() == 2) {
			$('#form_add_data .po_section').show();
			$('#form_add_data .customer_section').show();
   			// $('#customer_id_select').select2("open");
   			$('#add-nama-keterangan').hide();
   			$('#add-select-customer').show();
		};

		if ($(this).val() == 3) {
			$('#form_add_data .po_section').hide();
			// $('#form_add_data .customer_section').hide();
   			$('#customer_id_select').val('');
   			$('#add-nama-keterangan').show();
   			$('#add-select-customer').hide();
		};

	});

//====================================get harga jual barang====================================

    $('#barang_id_select').change(function(){
    	var barangIdSource = $('#barang_id_select');
		const barangIndex = barangIdSource.find(":selected").attr('data-index');
		const sel = barangSource[barangIndex];
		const barang_id = sel.barang_id;
		const warna_id = sel.warna_id;
		const harga_jual = sel.harga_jual;
		const qty = sel.subqty;
		const roll = sel.subroll;
		hargaSource = parseFloat(sel.harga_jual);
		hargaAdd.value = hargaSource;
		barangIdAdd.value = barang_id;
		warnaIdAdd.value = warna_id;

   		// var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
   		// if (retur_type_id == 3) {
		// 	$('#form_add_barang [name=harga]').val(change_number_format(data[1]));		
   		// }else{
	    // 	var data_st = {};
   		// 	data_st['barang_id'] = $('#form_add_barang [name=barang_id]').val();
	    // 	data_st['customer_id'] =  "<?=$customer_id;?>";
	    // 	var url = "transaction/get_latest_harga";

   		// 	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
   		// 		// alert(data_respond);
   		// 		if (data_respond > 0) {
		// 			$('#form_add_barang [name=harga]').val(change_number_format(data_respond));
   		// 		}else{
		// 			$('#form_add_barang [name=harga]').val(change_number_format(data[1]));
   		// 		}
	   	// 	});
   		// }
   		// // alert(data);
		// $('#form_add_barang [name=satuan]').val(data[0]);
		// $('#warna_id_select').select2('open');
    });

	$("#hargaJualAdd").change(function(){
		const selisih = parseFloat(hargaAdd.value) - parseFloat(hargaSource);
		console.log(selisih,hargaSource,  parseFloat(hargaAdd.value)+ '-'+ parseFloat(hargaSource));
		if (selisih < 0) {
			hargaAddNotes.innerHTML = (`<span style="color:red"> Harga retur lebih rendah</span>`);
		}else{
			hargaAddNotes.innerHTML = ("");
		}
	})

    $('.btn-cek-harga').click(function(){
    	var data = {};
    	data['barang_id'] = $('#form_add_barang [name=barang_id]').val();
    	data['customer_id'] =  $('#customer_id_select').val();
    	var url = 'transaction/cek_history_harga';
    	if (data['barang_id'] != '') {
    		var tbl = '<table>';
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		var isi_tbl = '';
				$.each(JSON.parse(data_respond),function(i,v){
					isi_tbl += "<tr>"+
						"<td>"+date_formatter(v.tanggal)+"</td>"+
						"<td>"+date_formatter(v.harga)+"</td>"+
						"</tr>";
				});

				if (isi_tbl !='') {
					tbl += isi_tbl + "</table>";
			    	$('#data-harga').html(tbl);			
				}else{
			    	$('#data-harga').html("no data");
				};

	   		});
    	}else{
    		$('#data-harga').html("no data");
    	}
    	
    });

//====================================modal barang=============================
	

	<?if ($status == 1) {?>
		var map = {220: false};
		$(document).keydown(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[220]) {
		            $('#portlet-config-detail').modal('toggle');
		           	setTimeout(function(){
			    		$('#barang_id_select').select2("open");
			    	},700);
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
	<?};?>

	$('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	setTimeout(function(){
    		$('#barang_id_select').select2("open");
    		// $('#form_add_barang .input1 .select2-choice').click();
    	},700);
    });

//====================================qty manage=============================    

    $(".btn-add-qty-row").click(function(){
    	var baris = "<tr><td><input name='qty'></td>"+
								"<td><input name='jumlah_roll'></td>"+
								"<td></td></tr>";
    	$('#qty-table').append(baris);
    });
	
    $("#qty-table input").change(function(){
    	var total = 0; var idx = 0; var rekap = [];
    	var total_roll = 0;
    	var total_stok = $('.stok-qty').html();
    	var jumlah_roll_stok = $('.stok-roll').html();
    	$("#qty-table [name=qty]").each(function(){
    		var ini = $(this).closest('tr');
    		var qty = $(this).val();
    		var roll = ini.find('[name=jumlah_roll]').val();
    		if (qty != '' && roll == '') {
    			roll = 1;
    		}else if(qty == '' && roll == ''){
    			roll = 0;
    		};
    		var subtotal = qty*roll;
    		total_roll += parseInt(roll);
    		if (qty != '' && roll != '') {
    			rekap[idx] = qty+'??'+roll;
    		};
    		idx++; 
    		// alert(total_roll);
    		total += subtotal;
    	});


    	$('.yard_total').html(total);
    	$('.jumlah_roll_total').html(total_roll);
    	$('[name=rekap_qty]').val(rekap.join('--'));
    });

//====================================qty edit manage=============================    

	$('.btn-qty-edit').click(function(){
		$('#qty-table-edit tbody').html('');
		var data_qty = $(this).closest('tr').find('.data_qty').html();
		$('#form-qty-update [name=rekap_qty]').val(data_qty);
		$('#form-qty-update [name=id]').val($(this).closest('tr').find('.id').html());
		var data_break  = data_qty.split('--');
		
		var i = 0; var total = 0;
		$.each(data_break, function(k,v){
			var qty = v.split('??');
			total += qty[0]*qty[1]; 
			if (i == 0 ) {
				var baris = "<tr>"+
					"<td><input name='qty' value='"+qty[0]+"' class='input1'></td>"+
					"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
					"<td><button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button></td>"+
					"</tr>";
				$('#qty-table-edit tbody').append(baris);
			}else{
				var baris = "<tr>"+
					"<td><input name='qty' value='"+qty[0]+"' ></td>"+
					"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
					"<td></td>"+
					"</tr>";
				$('#qty-table-edit tbody').append(baris);
			}

			i++;
		});

		for (var i = 0; i < 5; i++) {
			var baris = "<tr>"+
						"<td><input name='qty' value='' class='input1'></td>"+
						"<td><input name='jumlah_roll' value=''></td>"+
						"<td></td>"+
						"</tr>";

			$('#qty-table-edit tbody').append(baris);
		};

		update_qty_edit();


	});


	$("#qty-table-edit").on('change',"input",function(){
    	update_qty_edit();
    	
    });

	$(".btn-brg-edit-save").click(function(){
		var data = {};
		var id = $('#form-qty-update [name=id]').val();
		data['retur_jual_detail_id'] = id;
		data['rekap_qty'] = $('#form-qty-update [name=rekap_qty]').val();
		var url = 'transaction/retur_jual_qty_update';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {				
				$('#portlet-config-qty-edit').modal('toggle');
				
				var qty = $('#portlet-config-qty-edit .yard_total').html();
				var total_roll = $('#portlet-config-qty-edit .total_roll').html();

				var ini = $('#general_table').find(".id:contains("+id+")").closest('tr');
				var harga = ini.find('[name=harga]').val();
				var subtotal = qty * reset_number_format(harga);
				// alert(ini.html());
				ini.find('.subtotal').html(change_number_format(subtotal));
				ini.find('.jumlah_roll').html(total_roll);
				ini.find('.qty').html(qty);
				ini.find('.data_qty').html(data['rekap_qty']);
				update_table();
			}else{
				bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
					if(respond){
						window.location.reload();
					}
				});
			};
   		});	

	});

//====================================update harga=============================    
	
	$('#general_table').on('change','[name=harga]', function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['id'] = ini.find('.id').html();
		data['harga'] = $(this).val();
		var url = "transaction/update_retur_detail_harga";
		var qty = ini.find('.qty').html();
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				var subtotal = qty*data['harga'];
				ini.find('.subtotal').html(change_number_format(subtotal));
				update_table();
			}else{
				bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
					if(respond){
						window.location.reload();
					}
				});
			};
   		});		
	});

//====================================btn save=============================    


    $('.btn-brg-save').click(function(){
    	var yard = reset_number_format($('.yard_total').html());
    	if( yard > 0){
    		$('#form_add_barang').submit();
    	}

    });


    $('.btn-save').click(function(){
    	var retur_type_id = $('#form_add_data [name=retur_type_id]').val();
    	if ($('#form_add_data [name=tanggal]').val() != '') {
    		if (retur_type_id == 1 || retur_type_id == 2 ) {
    			if($('#form_add_data [name=customer_id]').val() != ''){
    				$('#form_add_data').submit();
    			}else{
    				notific8('ruby','Mohon pilih customer');
    			}
    		}else{
    			$('#form_add_data').submit();
    		};
    	}else{
    		alert("Mohon isi tanggal !");
    	};
    });

    $('.btn-edit-save').click(function(){
    	var retur_type_id = $('#form_edit_data [name=retur_type_id]').val();
    	if ($('#form_edit_data [name=tanggal]').val() != '') {
    		if (retur_type_id == 1 || retur_type_id == 2 ) {
    			if($('#form_edit_data [name=customer_id]').val() != ''){
    				$('#form_edit_data').submit();
    			}else{
    				notific8('ruby','Mohon pilih customer');
    			}
    		}else{
    			$('#form_edit_data').submit();
    		};
    	}else{
    		alert("Mohon isi tanggal !");
    	};
    });

//=====================================remove barang=========================================
	$('#general_table').on('click','.btn-detail-remove', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin mengahpus item ini?", function(respond){
			if (respond) {
				var data = {};
				data['id'] = ini.find('.id').html();
				var url = 'transaction/retur_list_detail_remove';
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == "OK") {
						ini.remove();
						update_table();
					}else{
						alert("Error");
					}
				}); 
			};
		});
	}) ;  
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



function update_qty_edit(){
    var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
	$("#qty-table-edit [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		if (qty != '' && roll == '') {
			roll = 1;
		};

		if (roll == '') {
			roll = 0;
		};
		var subtotal = qty*roll;
		if (qty != '' && roll != '') {
			rekap[idx] = qty+'??'+roll;
		};
		idx++; 
		// alert(roll);
		total_roll += parseInt(roll); 
		total += subtotal;
	});

	if (total > 0) {
		$('.btn-brg-edit-save').attr('disabled',false);
	}else{
		$('.btn-brg-edit-save').attr('disabled',true);
	}

	$('#portlet-config-qty-edit .total_roll').html(total_roll);
	$('#portlet-config-qty-edit .yard_total').html(total);

	$('#form-qty-update [name=rekap_qty]').val(rekap.join('--'));
}

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		subtotal+= reset_number_format($(this).html());
	});

	$('.total').html(change_number_format(subtotal));
	var diskon = reset_number_format($('.diskon').val());
	var ongkir = reset_number_format($('.ongkos_kirim').val());
	var g_total = subtotal - parseInt(diskon) + parseInt(ongkir);
	$('.g_total').html(change_number_format(g_total));
	update_bayar();
}
</script>
