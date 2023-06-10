<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<style>
	#general_table tr td, #general_table tr th{
		/* padding:5px 20px 5px 5px;
		border:1px solid #ddd; */
	}

	#cover-load{
		position: fixed;
		top: 0;
		left: 0;
		width:100vw;
		height:100vh;
		background-color:rgba(0,0,0,0.4);
        z-index: 999;
	}

    .form-div{
        display:none;
        text-align:left;
        padding:10px;
        padding-left:20px;
        background:lightblue;
        margin-top:10px;
        border-radius:5px;
    }

    .form-div table tr td{
        padding:5px 10px 5px 0px;
    }


</style>

<div class="page-content">
	<div class='container'>
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">DATA</span>
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
                        <div id='btn-list'>
                            <button class='btn btn-md' onclick="showFormToko('0')" >TOKO <span class="badge badge-danger" id='badge-toko'></span> </button>
                            <button class='btn btn-md' onclick="showFormGudang('1')" >GUDANG <span class="badge badge-danger" id='badge-gudang'></span> </button>
                            <button class='btn btn-md' onclick="showFormBarang('2')">BARANG <span class="badge badge-danger" id='badge-barang' ></span></button>
                            <button class='btn btn-md' onclick="showFormKeterangan('3')" >KETERANGAN BARANG <span class="badge badge-danger" id='badge-keterangan'></span></button>
                            <button class='btn btn-md' onclick="showFormSupplier('4')" >SUPPLIER <span class="badge badge-danger" id='badge-supplier'></span> </button>
                            <button class='btn btn-md' onclick="showFormSatuan('5')" >SATUAN <span class="badge badge-danger" id='badge-satuan'></span> </button>
                        </div>
                        <div id='toko-form'><form class='form-div'>1</form></div>
                        <div id='gudang-form'><form class='form-div'>2</form></div>
                        <div id='barang-form'>
                            <form id='formBarang' class='form-div' action="<?=base_url()?>stok/stok_opname/insert_file_barang" method='post'>
                            </form>
                        </div>
                        <div id='keterangan-form'><form class='form-div'>4</form></div>
                        <div id='supplier-form'><form class='form-div'>5</form></div>
                        <div id='satuan-form'><form class='form-div'>6</form></div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">HISTORY</span>
							<!-- <span class="caption-helper hide">weekly stats...</span> -->
						</div>
						<div class="actions">
						</div>
					</div>
					<div class="portlet-body">
						<?foreach ($data_file as $row) {?>
                            <p>
                                File : <b><?=$row->nama_file;?></b><br>
                                Uploaded : <b><?=date("d F Y H:i:s", strtotime($row->created_at));?></b>
                            </p>     
                        <?}?>
						<table class='table' id='general_table'>
                            <thead>
                                <tr>
                                    <th>IDX</th>
                                    <th>Tanggal</th>
                                    <th>Toko</th>
                                    <th>Gudang</th>
                                    <th>Nama Beli</th>
                                    <th>Nama Jual</th>
                                    <th>Keterangan</th>
                                    <th>Supplier</th>
                                    <th>Qty Besar</th>
                                    <th></th>
                                    <th>Qty Kecil</th>
                                    <th></th>
                                    <th>Eceran</th>
                                    <th></th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?$idx = 0;
                                foreach ($data_so as $row) { ?>
                                    <tr>
                                        <td><?=$row->id;?></td>
                                        <td><?=is_reverse_date($row->tanggal);?></td>
                                        <td><?=$row->toko;?></td>
                                        <td><?=$row->gudang?></td>
                                        <td><?=$row->nama_beli?></td>
                                        <td><?=$row->nama_jual?></td>
                                        <td><?=$row->keterangan?></td>
                                        <td><?=$row->supplier?></td>
                                        <td><?=$row->qty_besar?></td>
                                        <td><?=$row->nama_satuan_besar?></td>
                                        <td><?=$row->qty_kecil?></td>
                                        <td><?=$row->nama_satuan_kecil?></td>
                                        <td><?=$row->qty_eceran?></td>
                                        <td><?=$row->nama_satuan_eceran?></td>
                                        <td><?=$row->catatan?></td>
                                    </tr>	
                                <?$idx++;}?>
                            </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<div id='cover-load' class='text-center'>
	<div style='position:relative; top:20%; color:white; font-size:2em'>checking <span id='data-info'></span>....</div>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>

var filter = [];
var b_url = "stok/stok_opname/";

filter[0] = {
    'url' : `${b_url}cek_toko`,
    'info' : 'cek toko',
    'badge' : 'toko'
}

filter[1] = {
    'url' : `${b_url}cek_gudang`,
    'info' : 'cek gudang',
    'badge' : 'gudang'
}

filter[2] = {
    'url' : `${b_url}cek_barang`,
    'info' : 'cek barang',
    'badge' : 'barang'
}

filter[3] = {
    'url' : `${b_url}cek_keterangan`,
    'info' : 'cek keterangan',
    'badge' : 'keterangan'
}

filter[4] = {
    'url' : `${b_url}cek_supplier`,
    'info' : 'cek supplier',
    'badge' : 'supplier'
}

filter[5] = {
    'url' : `${b_url}cek_satuan`,
    'info' : 'cek satuan',
    'badge' : 'satuan'
}

var cek_index = 0;


jQuery(document).ready(function() {
	// cek_harga_barang
	// getText();

	$('#general_table').dataTable();
    get_data();
    // $("#cover-load").hide();

    $(document).on("click", '#btnSaveBarang', function(){
        console.log($(this).attr());
        // bootbox.confirm("Barang yang tidak memiliki nama beli / jual akan otomatis diisi oleh nama beli/jual ?", function(respond){
        //     if (respond) {
        //         $("#formBarang").submit();
        //     }
        // })
    });


});

function showLoad(){
	$("#cover-load").show();
}

function get_data(){
    if (cek_index < filter.length) {
        console.log(filter[cek_index]);
        $("#data-info").html(filter[cek_index].info);
        var data = {};
        var url = filter[cek_index].url;
        ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
            // alert(data_respond);
            if (parseInt(data_respond)) {
                $(`#badge-${filter[cek_index].badge}`).html(data_respond);
            }else{
                $(`#badge-${filter[cek_index].badge}`).closest("button").attr("disabled",true);
            }
            setTimeout(() => {
                cek_index++;
                get_data();
            }, 200);
        });
    }else{
        $("#cover-load").hide();
    }
}

function showFormToko(idx) {
    $(".form-div").hide();
    $(".form-div").html('');
    $("#toko-form .form-div").html(`<span style='color:#ddd'>load...</span>`);
    $("#toko-form").find(".form-div").show();

    var data = {};
    data['tipe'] = 1;
    var url = filter[idx].url;
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        console.log(data_respond);
        let template = `<h3>TAMBAH TOKO BARU</h3><div><table>`;
        $.each(JSON.parse(data_respond),function(k,v){
            template += `<tr>
                <td>Nama TOKO </td> <td>:</td> <td><input readonly type='text' name='nama[]' value="${v.toko}"></td>`;
        });
        template += `<tr>
            <td colspan='3' style='padding:10px 0px 20px 0px'>
                <button class='btn btn-xs btn-block green'> SAVE</button>
            </td>
            </table></div>`;

        console.log(template);
        $("#toko-form").find('.form-div').html(template);
    });
}

function showFormGudang(idx) {
    $(".form-div").hide();
    $(".form-div").html('');
    $("#gudang-form .form-div").html(`<span style='color:#ddd'>load...</span>`);
    $("#gudang-form").find(".form-div").show();

    var data = {};
    data['tipe'] = 1;
    var url = filter[idx].url;
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        console.log(data_respond);
        let template = `<h3>TAMBAH GUDANG BARU</h3><div>
        <table>`;
        $.each(JSON.parse(data_respond),function(k,v){
            template += `<tr>
                <td>Nama Gudang </td> <td>:</td> <td><input readonly type='text' name='nama[]' value="${v.gudang}"></td>`;
        });
        template += `<tr>
            <td colspan='3' style='padding:10px 0px 20px 0px'>
                <button class='btn btn-xs btn-block green'> SAVE</button>
            </td>
            </table></div>`;

        console.log(template);
        $("#gudang-form").find('.form-div').html(template);
    });
}

function showFormBarang(idx) {
    $(".form-div").hide();
    $(".form-div").html('');
    $("#barang-form .form-div").html(`<span style='color:#ddd'>load...</span>`);
    $("#barang-form").find(".form-div").show();

    var data = {};
    data['tipe'] = 1;
    var url = filter[idx].url;
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        console.log(data_respond);
        let template = `<h3>TAMBAH BARANG BARU</h3>
            <div  style="height:400px; overflow:auto">
            <table>
                <tr>
                    <td>Nama Beli</td>
                    <td>Nama Jual</td>
                    <td>Harga Beli</td>
                    <td>Harga Jual</td>
                </tr>`;
        $.each(JSON.parse(data_respond),function(k,v){
            
            let s = `value="${v.nama_beli}"`;
            let j = `value="${v.nama_jual}"`;
            if (v.nama_beli.indexOf('"') >= 0) {
                s=`value='${v.nama_beli}'`;
            }

            if (v.nama_jual.indexOf('"') >= 0) {
                j=`value='${v.nama_jual}'`;
            }

            template += `<tr>
                <td><input type='text' name='nama_beli[]' ${s}> </td> 
                <td><input type='text' name='nama_jual[]' ${j}></td> 
                <td><input type='text' name='harga_beli[]'></td>
                <td><input type='text' name='harga_jual[]'></td>`;
        });
        template += `
            </table>
            </div>
            <div style='height:100px; padding-top:20px'>
                <button class='btn btn-xs btn-block green' id='btnSaveBarang'> SAVE</button>
            </div>
            `;

        console.log(template);
        $("#formBarang").html(template);
    });
}

function showFormKeterangan(idx) {
    $(".form-div").hide();
    $(".form-div").html('');
    $("#keterangan-form .form-div").html(`<span style='color:#ddd'>load...</span>`);
    $("#keterangan-form").find(".form-div").show();

    var data = {};
    data['tipe'] = 1;
    var url = filter[idx].url;
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        console.log(data_respond);
        let template = `<h3>TAMBAH KETERANGAN BARU</h3>
            <div  style="height:400px; overflow:auto">
            <table>
                <tr>
                    <td>Keterangan Beli</td>
                    <td>Keterangan Jual</td>
                </tr>`;
        $.each(JSON.parse(data_respond),function(k,v){
            
            let s = `value="${v.keterangan}"`;
            if (v.keterangan.indexOf('"') >= 0) {
                s=`value='${v.keterangan}'`;
            }

            template += `<tr>
                <td><input type='text' name='warna_beli[]' value=""> </td> 
                <td><input type='text' name='warna_jual[]' ${s}></td> `;
        });
        template += `
            </table>
            </div>
            <div style='height:100px; padding-top:20px'>
                <button class='btn btn-xs btn-block green'> SAVE</button>
            </div>
            `;

        console.log(template);
        $("#keterangan-form").find('.form-div').html(template);
    });
}

function showFormSupplier(idx) {
    $(".form-div").hide();
    $(".form-div").html('');
    $("#supplier-form .form-div").html(`<span style='color:#ddd'>load...</span>`);
    $("#supplier-form").find(".form-div").show();

    var data = {};
    data['tipe'] = 1;
    var url = filter[idx].url;
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        console.log(data_respond);
        let template = `<h3>TAMBAH SUPPLIER BARU</h3><div style="height:400px; overflow:auto"><table>`;
        $.each(JSON.parse(data_respond),function(k,v){
            template += `<tr>
                <td>Nama Supplier </td> <td>:</td> <td><input readonly type='text' name='nama[]' value="${v.supplier}"></td>`;
        });
        template += `<tr>
            <td colspan='3' style='padding:10px 0px 20px 0px'>
                <button class='btn btn-xs btn-block green'> SAVE</button>
            </td>
            </table></div>`;

        console.log(template);
        $("#supplier-form").find('.form-div').html(template);
    });
}


</script>
