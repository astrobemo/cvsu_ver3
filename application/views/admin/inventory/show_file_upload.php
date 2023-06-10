<?=link_tag('assets/global/plugins/dropzone/css/dropzone.css'); ?>
<style>
	#tableFiles tr td, #tableFiles tr th{
		padding:5px 20px 5px 5px;
		border:1px solid #ddd;
	}

	#cover-load{
		position: fixed;
		top: 0;
		left: 0;
		width:100vw;
		height:100vh;
		background-color:rgba(0,0,0,0.4);
		/* display: none; */
        z-index: 100;
	}

    .kolom-name{
        width:150px;
        background:lightblue;
        text-align:center;
        padding:5px;
        margin:5px 0;
        cursor:pointer;
        border-radius:5px;
        z-index: 99;
        /* border:1px solid blue; */
    }

    .kolom-name-disabled{
        width:150px;
        background:#ddd !important;
        text-align:center;
        padding:5px;
        margin:5px 0;
        cursor:no-drop;
        border-radius:5px;
        z-index: 99;
    }

    #table-database tr td, #table-database tr th{
        /* padding:15px 10px; */
        border:1px solid #ddd;
        width:200px;
        height:50px;
        padding-left:10px;
        vertical-align:middle;
    }

    .kolom-sumber{
        
    }

    #keterangan-kolom{
        padding:10px;
        background:#eee;
    }

    .target-kolom{
        display:none;
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
							<span class="caption-subject theme-font bold uppercase">MATCHING DATA</span>
							<!-- <span class="caption-helper hide">weekly stats...</span> -->
						</div>
						<div class="actions">
						</div>
					</div>
					<div class="portlet-body">
                        <div class='row'>
                            <div class="col-xs-12">
                                <label for="isFirstHeader">
                                    <input type="checkbox" name="isFirstHeader" id="isFirstHeader" onchange="showNamaIndex()">Baris Pertama adalah header </label>
                                    <hr/>
                            </div>
                            <div class="col-xs-3">
                                <div id='keterangan-kolom'>
                                    <?foreach ($data_pertama as $key => $value) {?>
                                        <div class='kolom-name' data-index="<?=$key;?>" id="header-<?=$key?>"><span class="index-base">kolom-<?=$key+1?></span><span class="name-base" hidden><?=($value==''? "kolom-".$key :$value);?></span></div>
                                    <?}?>
                                </div>
                            </div>
                            <div class="col-xs-6 ">
                                <div>
                                    <table id='table-database'>
                                        <form action="<?=base_url()?>stok/stok_opname/insert_file_mutasi_new" method="post" id="form-header">

                                            <thead>

                                                <tr style='background:lightpink'>
                                                    <th>JUMLAH BARIS</th>
                                                    <th><input type="text" name='jumlah_baris' id='jumlahBaris' value="<?=count($file_show);?>"></th>
                                                    
                                                </tr>

                                                <tr>
                                                    <th>Kolom Data</th>
                                                    <th>Kolom File</th>
                                                </tr>
                                            </thead>
                                            <input hidden type="text" name="nama_file" value="<?=$nama_file;?>" >
                                            <input hidden type='text' name="is_baris_header" id="isBarisHeader">
                                            <tbody>
                                                <tr>
                                                    <td>Tanggal SO</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="tanggal" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Toko</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="toko" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>    
                                                    <td>Gudang</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="gudang" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>    
                                                    <td>Nama Beli</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="nama_beli" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Nama Jual</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="nama_jual" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Nama Keterangan</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="nama_keterangan" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Harga Beli</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="harga_beli" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Harga Jual</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="harga_jual" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Harga Eceran</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="harga_eceran" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>    
                                                    <td>Nama Supplier</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="nama_supplier" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>    
                                                    <td>QTY BESAR</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="qty_besar" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>    
                                                    <td>Nama Satuan Besar</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="nama_satuan_besar" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>    
                                                    <td>QTY KECIL</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="qty_kecil" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>    
                                                    <td>Nama Satuan Kecil</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="nama_satuan_kecil" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>QTY ECERAN</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="qty_eceran" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Nama Satuan Eceran</td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="nama_satuan_eceran" class='target-kolom' id=""></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Rincian Qty Kecil<br/>
                                                    </td>
                                                    <td class='kolom-sumber kol-db'><input type="text" name="rincian_qty_kecil" class='target-kolom' onchange="changeRincianQty()" id="rincianQtyKecil"></td>
                                                </tr>

                                            </tbody>
                                        </form>
                                    </table>
                                </div>
                                <div>
                                    <hr>
                                    <button class='btn red' onclick="resetPage()">RESET ALL</button>
                                    <button class='btn green' onclick="submitPage()">SUBMIT</button>
                                </div>
                            </div>
                        </div>

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
						<?$dir = "./uploads";
						$files = scandir($dir); ?>
                        <div style='overflow:auto'>

                            <table id='tableFiles'>
                                <tr>
                                    <th>Baris</th>
                                
                                <?
                                $idx = 0;
                                $no = 1;
                                foreach ($data_pertama as $key => $value) {?>
                                    <th>kolom-<?=$key+1;?></th>
                                <?}?>
                                </tr>
                                <?
                                foreach ($file_show as $key => $value) {?>
                                    <tr>
                                        <td>
                                            <?=$no;?>
                                        </td>
                                        <?foreach ($value as $k => $v) {?>
                                            <td><?=$v;?></td>
                                        <?}?>
                                    </tr>
                                <?$no++;}
                                ?>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<div id='cover-load' class='text-center'>
	<h1 style='position:relative; top:20%; color:white;'>processing....</h1>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/dropzone/dropzone.js'); ?>"></script>

<script>

var header_kolom_dragged = '';
var id_dragged = '';
var index_dragged = '';
var ori_top = '';
var ori_left = '';
var ori_pos = '';
var ket_pos;
var jml_kol = "<?=count($data_pertama);?>";

jQuery(document).ready(function() {

    ket_pos = $("#keterangan-kolom").offset();
    draggableInitialize();
    droppableInitialize();

    $("#keterangan-kolom").droppable({
        accept:'.kolom-name',
		drop:function(event, ui){
            emptyKolomTarget(index_dragged);
            ori_pos = '';
            index_dragged = '';
            id_dragged = '';
        }
    });
    
   
});

function emptyKolomTarget(idx){
    $(".target-kolom").each(function(){
        if($(this).val() == idx){
            $(this).val('');
            $(this).closest('td').css('background','white');
            $(this).change();
            // $(this).closest('.kol-db').removeClass('ui-droppable-disabled');
            $('#table-database .kolom-sumber').droppable('destroy');
            droppableInitialize();
            // console.log($(this).attr('name'));
        }
    });
}

function removeCellBG(idx){
    $(".target-kolom").each(function(){
        if($(this).val() == idx){
            $(this).closest('td').css('background','white');
            $(this).change();
            // $(this).closest('.kol-db').removeClass('ui-droppable-disabled');
            $('#table-database .kolom-sumber').droppable('destroy');
            droppableInitialize();
            // console.log($(this).attr('name'));
        }
    });
}


function addColorCellBG(idx){
    $(".target-kolom").each(function(){
        if($(this).val() == idx){
            setTimeout(() => {
                $(this).closest('td').css('background','lightblue');
            }, 500);
        }
    });
}

function draggableInitialize(){
    showLoad(500);
    setTimeout(() => {
        $('.kolom-name').draggable({
            start: function(){
                $(this).closest('td').css('background','lightblue')
                id_dragged = $(this).attr('id');
                index_dragged = $(this).attr('data-index');
                removeCellBG(index_dragged);
                // ori_pos = $(this).position();
                ori_pos = $(this).data('uiDraggable').originalPosition;
                ori_top = $(this).css('left');
                ori_left = $(this).css('top');
    
            },
            stop: function() {
               
            },
            revert:function(is_valid_drop){
                if (!is_valid_drop) {
                    addColorCellBG(index_dragged);
                    index_dragged = '';
                    return "invalid";
                }
            },
        });
    }, 500);
}

function droppableInitialize(){
    $('#table-database .kolom-sumber').droppable({
        accept:'.kolom-name',
		drop:function(event, ui){
            const $this = $(this);
            const indexIsi = $(this).find('.target-kolom').val();
            const isFilled = indexIsi.length;
            if(!isFilled){
                emptyKolomTarget(index_dragged);
                $(this).droppable('disable');
                $(this).find('.target-kolom').val(index_dragged);
                $(this).find('.target-kolom').change();
                $(this).closest('td').css('background','lightblue');
            }
            else if(indexIsi != index_dragged){

                // $(`#${id_dragged}`).css({
                //     'left':ori_pos.left,
                //     'top': ori_pos.top,
                // });
                $(`#${id_dragged}`).animate(ori_pos,200,"linear");
                addColorCellBG(index_dragged);
                ori_pos = '';
                
                index_dragged = '';
                id_dragged = '';
                alert("Sudah terisi");
                return false;
                // event.revert = true;
            }
            ori_pos = '';
            index_dragged = '';
            id_dragged = '';
            ui.draggable.position({
            my: "center",
            at: "center",
            of: $this,
            using: function(pos) {
                $(this).animate(pos, 200, "linear");
            }
            });
		}
	});
}

function changeRincianQty(){
    const idx_col = $("#rincianQtyKecil").val();
    console.log(idx_col);
    if (idx_col != '') {
        for (let m = parseInt(idx_col)+1; m < jml_kol; m++) {
            $(`#header-${m}`).draggable("disable");
            $(`#header-${m}`).addClass("kolom-name-disabled");
        }
    }else{
        $(".kolom-name").removeClass("kolom-name-disabled");
        $(".kolom-name").draggable("destroy");
        draggableInitialize();
    }
}

function showLoad(lama){
    $("#cover-load").show();
    setTimeout(() => {
        $("#cover-load").hide();
    }, lama);
}

function showNamaIndex(){
    // alert($('#isFirstHeader').is(":checked"));
    if ($('#isFirstHeader').is(":checked")) {
        $("#isBarisHeader").val(1);
        $("#keterangan-kolom").find(".index-base").hide();
        $("#keterangan-kolom").find(".name-base").show();
    }else{
        $("#isBarisHeader").val(0);
        $("#keterangan-kolom").find(".index-base").show();
        $("#keterangan-kolom").find(".name-base").hide();
    }
}

function resetPage(){
    bootbox.confirm("Yakin reset ?", function(respond){
        if (respond) {
            window.location.reload();
        }
    });
}

function submitPage() {
    if ($("#jumlahBaris").val() != '') {
        bootbox.confirm("Submit File ? Mohon cek ulang sebelum upload, <b>Mohon Pastikan Jumlah Baris Benar</b>", function(respond){
            if (respond) {
                $("#cover-load").show();
                $("#form-header").submit();
            }
        })
    }else{
        bootbox.confirm("Jumlah Baris tidak boleh kosong");
    }
}

</script>
