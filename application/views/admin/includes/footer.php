		</div>
	<!-- END CONTENT -->
</div>

<style type="text/css">
.notifikasi-akunting{
	position: fixed;
	right: 0;
	top: 126px;
	padding: 20px;
	background: orange;
}

.notifikasi-akunting hr{
	padding: 2px 0 ;
	margin: 2px 0;
}

.isTesting{
	position:fixed;
	bottom:50px;
	right:20px;
	font-size:2em;
	border: 2px solid red;
	padding:10px;
}

</style>

<div class="modal fade bs-modal-lg" id="portlet-config-note-order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="<?=base_url('admin/note_order_insert')?>" class="form-horizontal" id="form_add_note_order" method="post">
					<div class="form-group">
	                    <label class="control-label col-md-3">Tanggal Order<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<div class="input-group date form_datetime">
								<input name="id" hidden>
								<input type="text" size="16" name="tanggal_note_order" class="form-control">
								<span class="input-group-btn">
								<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Tanggal Target
	                    </label>
	                    <div class="col-md-6">
							<input autocomplete="off" name="tanggal_target" class="form-control date-picker">
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Tipe Customer<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<div class="radio-list">
		                    	<label class="radio-inline">
								<input type='radio' name="tipe_customer" checked class="form-control note-type-customer" value='1'>Customer</label>
								<label class="radio-inline">
								<input type='radio' name="tipe_customer" class="form-control note-type-customer" value='2'>Non Customer</label>
							</div>
	                    </div>
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Customer<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<div class='note-customer'>
		                    	<select name='customer_id' class='form-control' id="customer_id_select_global">
		                    		<option value=''>Pilih</option>
		                    		<?foreach ($this->customer_list_aktif as $row) {?>
			                    		<option value='<?=$row->id;?>'><?=$row->nama;?></option>
		                    		<?}?>
		                    	</select>
	                    	</div>
	                    	<div class='note-non-customer' hidden>
		                    	<input class='form-control' name='nama_customer' placeholder='nama'>
	                    	</div>
	                    	<input class='form-control' name='contact_info' placeholder='kontak'>
	                    </div>				                    
	                </div>

	                <div id='detail-on-order'>
	                	<div class="form-group">
		                    <label class="control-label col-md-3">Tipe Barang<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
								<div class="radio-list">
			                    	<label class="radio-inline">
									<input type='radio' checked class='form-control' name='tipe_barang' value="1">Terdaftar</label>
									<label class="radio-inline">
									<input type='radio' class='form-control' name='tipe_barang' value="2">Belum Terdaftar</label>
								</div>								
		                    </div>				                    
		                </div>

		                <div class="form-group" id='barang_terdaftar'>
		                    <label class="control-label col-md-3">Nama Barang<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<select class="form-control" name="barang_id" id='barang_id_select_global'>
		                    		<option value='0'>Pilih</option>
		                    		<?foreach ($this->barang_list_aktif as $row) {?>
		                    			<option value='<?=$row->id;?>'><?=$row->nama_jual;?></option>
		                    		<?}?>
		                    	</select>
		                    </div>				                    
		                </div>

		                <div class="form-group" hidden id='barang_tidak_terdaftar'>
		                    <label class="control-label col-md-3">Nama Barang<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input type="text" class="form-control" name="nama_barang"/>
		                    </div>				                    
		                </div>

		                <div class="form-group">
		                    <label class="control-label col-md-3">Warna<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<select class="form-control" name="warna_id" id='warna_id_select_global'>
		                    		<option value='0'>Pilih</option>
		                    		<option value='-1'>Lain-lain</option>
		                    		<?foreach ($this->warna_list_aktif as $row) {?>
		                    			<option value='<?=$row->id;?>'><?=$row->warna_jual;?></option>
		                    		<?}?>
		                    	</select>
		                    </div>				                    
		                </div>

		                <div class="form-group" hidden id='warna_tidak_terdaftar'>
		                    <label class="control-label col-md-3">Nama Warna<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input type="text" class="form-control" name="nama_warna"/>
		                    </div>				                    
		                </div>


		                <div class="form-group">
		                    <label class="control-label col-md-3">QTY
		                    </label>
		                    <div class="col-md-6">
		                    	<input autocomplete='off' type="text" class="form-control " name="qty"/>
		                    </div>				                    
		                </div>

		                <div class="form-group">
		                    <label class="control-label col-md-3">Roll
		                    </label>
		                    <div class="col-md-6">
		                    	<input autocomplete='off' type="text" class="form-control " name="roll"/>
		                    </div>				                    
		                </div>

		                <div class="form-group">
		                    <label class="control-label col-md-3">Harga
		                    </label>
		                    <div class="col-md-6">
		                    	<input autocomplete='off' type="text" class="form-control amount_number " name="harga"/>
		                    </div>				                    
		                </div>
	                </div>
	               
	                <input hidden name='link' value="https://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn blue btn-note-order-save">Save</button>
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg" id="portlet-config-note-order-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="<?=base_url('admin/note_order_detail_insert')?>" class="form-horizontal" id="form_add_note_order_detail" method="post">
					            
	                <div class="form-group">
	                    <label class="control-label col-md-3">Tipe Barang<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
							<input name="note_order_detail_id" hidden>
							<input name="note_order_id" hidden>
							<div class="radio-list">
		                    	<label class="radio-inline">
								<input type='radio' checked class='form-control' name='tipe_barang' value="1">Terdaftar</label>
								<label class="radio-inline">
								<input type='radio' class='form-control' name='tipe_barang' value="2">Belum Terdaftar</label>
							</div>								
	                    </div>				                    
	                </div>

	                <div class="form-group" id='barang_terdaftar'>
	                    <label class="control-label col-md-3">Nama Barang<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<select class="form-control" name="barang_id" id='barang_id_select_global'>
	                    		<option value='0'>Pilih</option>
	                    		<?foreach ($this->barang_list_aktif as $row) {?>
	                    			<option value='<?=$row->id;?>'><?=$row->nama_jual;?></option>
	                    		<?}?>
	                    	</select>
	                    </div>				                    
	                </div>

	                <div class="form-group" hidden id='barang_tidak_terdaftar'>
	                    <label class="control-label col-md-3">Nama Barang<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<input type="text" class="form-control" name="nama_barang"/>
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Warna<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<select class="form-control" name="warna_id" id='warna_id_select_global'>
	                    		<option value='0'>Pilih</option>
	                    		<option value='-1'>Lain-lain</option>
	                    		<?foreach ($this->warna_list_aktif as $row) {?>
	                    			<option value='<?=$row->id;?>'><?=$row->warna_jual;?></option>
	                    		<?}?>
	                    	</select>
	                    </div>				                    
	                </div>

	                <div class="form-group" hidden id='warna_tidak_terdaftar'>
	                    <label class="control-label col-md-3">Nama Warna<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<input type="text" class="form-control" name="nama_warna"/>
	                    </div>				                    
	                </div>


	                <div class="form-group">
	                    <label class="control-label col-md-3">QTY
	                    </label>
	                    <div class="col-md-6">
	                    	<input autocomplete='off' type="text" class="form-control " name="qty"/>
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Roll
	                    </label>
	                    <div class="col-md-6">
	                    	<input autocomplete='off' type="text" class="form-control " name="roll"/>
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Harga
	                    </label>
	                    <div class="col-md-6">
	                    	<input autocomplete='off' type="text" class="form-control amount_number " name="harga"/>
	                    </div>				                    
	                </div>

	               
	                <input hidden name='link' value="https://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn blue btn-note-order-detail-save">Save</button>
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg" id="portlet-config-reminder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<table class='table' id='reminder_table'>
					<thead>
						<tr>
							<th>Customer</th>
							<th>Barang</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?foreach (get_note_order_reminder() as $row) { ?>
							<td><?=$row->nama_customer;?> - <?=$row->contact_info;?></td>
							<td>( <?=$row->roll;?> roll/<?=is_qty_general($row->qty);?> m ) <?=$row->nama_barang?> <?=$row->nama_warna;?></td>
							<td>
								<span class='reminder_id' hidden><?=$row->reminder_id;?></span>
								<button class='btn btn-xs btn-remove-reminder'><i class='fa fa-times'></i> </button>
							</td>
						<?}?>
					</tbody>
				</table>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade" id="portlet-config-cek-harga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<span style="font-size:1.5em">Cek Harga Barang</span>
				<hr/>
				<form class="form-horizontal" id="form_cek_harga_barang" method="post">
					<div class="form-group">
	                    <label class="control-label col-md-3">Nama Barang<span class="required">
	                    * </span>
	                    </label>
	                    <div class="col-md-6">
	                    	<select class="form-control" name="barang_id" id='barang_id_select_global_2'>
	                    		<?foreach ($this->barang_list_aktif as $row) {?>
	                    			<option value='<?=$row->id;?>'><?=$row->nama_jual;?></option>
	                    		<?}?>
	                    	</select>
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Customer</label>
	                    <div class="col-md-6">
	                    	<select name='customer_id' class='form-control' id='customer_id_select_global_2'>
	                    		<option value='0'>Pembelian Terakhir</option>
	                    		<?foreach ($this->customer_list_aktif as $row) {?>
		                    		<option value='<?=$row->id;?>'><?=$row->nama;?></option>
	                    		<?}?>
	                    	</select>
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Banyak Data</label>
	                    <div class="col-md-6">
	                    	<input class='form-control' name='limit' value='3'>
	                    </div>				                    
	                </div>
				</form>
				<hr/>
				<div style='text-align:right'>
					<button type="button" class="btn blue btn-cari-harga">CARI</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>

				</div>
				
				<hr/>
				<table class='table table-border' id='hasil_cek_harga_barang'>
					<thead>
						<tr>
							<th>Tanggal</th>
							<th>Harga</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>


			</div>
			<!-- <div class="modal-footer">
			</div> -->
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade" id="portlet-config-akunting-notification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<span style="font-size:1.5em">Notifikasi Pembayaran U/ Akunting</span>
				<hr/>
				<form action="<?=base_url('admin/notifikasi_akunting_insert');?>" class="form-horizontal" id="form_add_notif" method="post">
					
	                <div class="form-group">
	                    <label class="control-label col-md-3">Customer</label>
	                    <div class="col-md-6">
	                    	<select name='customer_id' class='form-control' id='customer_id_select_global_3'>
	                    		<?foreach ($this->customer_list_aktif as $row) {?>
		                    		<option value='<?=$row->id;?>'><?=$row->nama;?></option>
	                    		<?}?>
	                    	</select>

	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Jumlah</label>
	                    <div class="col-md-6">
	                    	<input class='form-control amount-number' autocomplete='off' name='amount'>
	                    </div>				                    
	                </div>

	                <div class="form-group">
	                    <label class="control-label col-md-3">Keterangan</label>
	                    <div class="col-md-6">
	                    	<textarea class='form-control' rows='10' name='keterangan'></textarea>
	                    </div>				                    
	                </div>

	                <input hidden name='link' value="https://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn blue btn-send-notif">SAVE</button>
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?if (is_posisi_id() == 6) { ?>
	<div class='notifikasi-akunting' <?=(count(get_notifikasi_akunting()) == 0 ? 'hidden' :'' );?> >
		<?foreach (get_notifikasi_akunting() as $row) { ?>
			<div>
				<b>Notif</b><br/>
				Customer : <?=$row->nama_customer;?><br/>
				Jumlah : <?=number_format($row->amount,'0',',','.');?><br/>
				Keterangan : <hr/><?=str_replace("\n", "<br/>", $row->keterangan);?><hr/>
				<table width='100%'>
					<tr>
						<td>
							<span class='notifikasi_akunting_id' hidden><?=$row->id;?></span>
						</td>
						<td>
							<button class='btn btn-xs default btn-dismiss-notif' style='float:right'>dismiss <i class='fa fa-times'></i></button>
						</td>
					</tr>
				</table>
			</div>
		<?}?>
	</div>
<?}?>

<?if (count(get_note_order_reminder()) > 0) { ?>
	<a style='position:fixed; top:35%; right:0; width:100px; padding:10px 5px; background:#F3565D; color:white' href="#portlet-config-reminder" data-toggle='modal'>
		Anda memiliki <span id='reminder_count'><?=count(get_note_order_reminder()); ?> </span> reminder<br>
		click here !
	</a>

<?}?>

<div class='isTesting' hidden>
	FOR TESTING ONLY
</div>

<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->

<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
</body>
<!-- END BODY -->
</html>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/page_common.js'); ?>"></script>
<script>
	$(document).ready(function() {
	   	$("#<?=$nama_menu;?>").addClass("active");
	   	$("#<?=$nama_menu;?> .title").after('<span class="selected"></span>');
	   	$("#<?=$nama_submenu;?>").addClass("active");

	   	// $('.tanggal_target')

	   	$(".form_datetime").datetimepicker({
            autoclose: true,
            isRTL: Metronic.isRTL(),
            format: "dd/mm/yyyy - hh:ii",
            pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
        });

   		Metronic.init(); // init metronic core components
	    Layout.init(); // init layout

	    $('.btn-note-order').click(function(){
	    	var form = "#form_add_note_order";
	    	var currentdate = new Date(); 
	    	var date = two_digit(currentdate.getDate());
	    	var month = two_digit((currentdate.getMonth()+1));
	    	var hour = two_digit(currentdate.getHours());
	    	var min = two_digit(currentdate.getMinutes());
	    	// alert(currentdate.getDate());

		    var datetime = date + "/"
                + month  + "/" 
                + currentdate.getFullYear() + " - "  
                + hour + ":"  
                + min;

            $(form+" [name=tanggal_note_order]").val(datetime);

			$("#detail-on-order").show();

	    });

	    $('.btn-send-notif').click(function(){
	    	$('#form_add_notif').submit();
	    });

	    $("#barang_id_select_global, #barang_id_select_global_2, #warna_id_select_global, #customer_id_select_global, #customer_id_select_global_2, #customer_id_select_global_3").select2();
	    $("#form_add_note_order_detail [name=tipe_barang]").change(function(){
	    	var tipe_barang = $(this).val();
	    	if (tipe_barang == 1) {
	    		$('#barang_terdaftar').show();
	    		$('#barang_tidak_terdaftar').hide();
	    	}else if (tipe_barang == 2) {
	    		$('#barang_terdaftar').hide();
	    		$('#barang_tidak_terdaftar').show();
	    	};
	    });

	    $('.btn-note-order-save').click(function(){
	    	var form = "#form_add_note_order";
	    	$(form).submit();
	    	// $('#form_add_note_order').submit();
	    });

	    $('.btn-note-order-detail-save').click(function(){
	    	var form = "#form_add_note_order_detail";
	    	var tipe_barang = $(form+" [name=tipe_barang]:checked").val(); 
	    	var barang_id = $(form+" [name=barang_id]").val();
	    	var nama_barang = $(form+" [name=nama_barang]").val();
	    	var warna_id = $(form+" [name=warna_id]").val();
	    	var qty = $(form+" [name=qty]").val();
	    	var check = 0;

	    	$(form).submit();

	    	// $('#form_add_note_order').submit();
	    });

	    $('.note-type-customer').change(function(){
	    	// alert($(this).val());
	    	var tipe_customer = $(this).val();
	    	if (tipe_customer == 1) {
	    		$('.note-customer').show();
	    		$('.note-non-customer').hide();
	    	}else{
	    		$('.note-customer').hide();
	    		$('.note-non-customer').show();
	    	};
	    });

	    $('#form_add_note_order_detail [name=warna_id]').change(function(){
	    	if ($(this).val() == -1) {
	    		$('#warna_tidak_terdaftar').show();
	    	}else{
	    		$('#warna_tidak_terdaftar').hide();
	    	}
	    });

	    $('#reminder_table').on('click', '.btn-remove-reminder', function(){
	    	var data_st = {};
	    	var ini = $(this).closest('tr');
	    	data_st['reminder_id'] = ini.find('.reminder_id').html();
	    	var url = 'admin/reminder_remove';
	    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		var reminder_count = $('#reminder_count').html();
	    		reminder_count--;
				ini.remove();
				$('#reminder_count').html(reminder_count);
	   		});
	    });

	    $('.btn-cari-harga').click(function(){
	    	var form = $('#form_cek_harga_barang');
	    	var data_st = {};
	    	data_st['barang_id'] = form.find('[name=barang_id]').val();
	    	data_st['customer_id'] = form.find('[name=customer_id]').val();
	    	data_st['limit'] = form.find('[name=limit]').val();
	    	var url = 'admin/cek_harga_barang';
	    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		var data_show = "";
	    		$.each(JSON.parse(data_respond) , function(i,v){
	    			data_show += "<tr>";
	    			data_show += "<td>"+v.tanggal+"</td>";
	    			data_show += "<td>"+change_number_format(v.harga_jual)+"</td>";
	    			data_show += "</tr>";
	    		});

	    		$('#hasil_cek_harga_barang tbody').html(data_show);
	   		});
	    });

	    $('.btn-dismiss-notif').click(function(){
	    	var ini = $(this);
	    	var data_st = {};
	    	data_st['notifikasi_akunting_id'] = ini.closest('tr').find('.notifikasi_akunting_id').html();
	    	var url = 'admin/dismiss_notifikasi_akunting';
	    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		if(data_respond == 'OK'){
	    			ini.closest('div').remove();
			    	if($('.notifikasi-akunting div').length == 0){
			    		$('.notifikasi-akunting').toggle('slow');
			    	};
	    		}
	   		});
	    });
	});

	function two_digit(number){
		var pjg = number.toString().length;
		// alert(pjg);
		if (pjg == 1) {
			number = '0'+number;
		};
		return number;
	}

</script>


<div class="page-footer">
	<div class="page-footer-inner" style='text-align:center'>
		2017 &copy; hendry_lioenardi 
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>