<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style>
/* #newAssemblyTable{
	width:auto;
}

#newAssemblyTable tr td,
#newAssemblyTable tr th{
	padding:2px 5px;
	border:1px solid #ddd;
	position:relative;
} */

#itemListContainer{
	width:650px;
	max-height:400px;
	position:absolute;
	padding:10px;
	top:30px;
	left:0;
	background:#fff;
}

#searchItemList{
	width:290px;
	border:1px solid #ddd;
	margin-bottom:10px;
	padding:2px 5px;;
}

#stockContainer{
	width:300px;
	height:250px;
	margin-left:15px;
	overflow:auto;
	display:inline-block;
}

#stockItem{
	margin:auto;
}

#itemList{
	width:300px;
	height:250px;
	padding:0;
	overflow:auto;
	display:inline-block;
}

#itemList li{
	padding:0px 2px;
}

#itemList li.selected{
	background:#eee;
}

#itemList li:hover{
	background:#eee;
	cursor:pointer;
	word-break: break-all;
}

#tblFilter tr td{
	padding:2px 5px;
}

#stockItem tr td{
	padding:2px 8px;
	cursor:pointer;
}

#stockItem tr:hover{
	background:#ddd;
}

#itemListToko{
	width:250px;
	border:1px solid #ddd;
	border-radius:3px;
	margin:5px;
	font-size:1.1em;
}


#form-add-barang{
	min-height:200px;
}

#addListTable{
	/* position:absolute; */
	/* max-width:520px; */
}

.stok-add-container{
	padding:10px 10px 20px 10px;
	height:350px;
	background:lightblue;
	border-radius:3px;
	padding-top:20px;
	overflow-y:auto;

}

.div-form-container{
	top:0;
	left:0;
	width:100%;
}



/* #stok-add-container div{
	margin:auto;
	width:300px;
	min-height:200px;
	text-align:center;
} */

/* #stok-add-container{
	display: flex;
	align-items: center;
	justify-content: center;
	min-height:300px;
	background:rgba(200,200,200,0.3);
}

#stok-add-container div{
	width:200px;
	min-height:100px;
	text-align:center;
} */

#stok-add-container table{
	margin:auto;
	font-size:13px;
}

#stok-add-container table tr th,
#stok-add-container table tr td{
	padding:3px;
	border:1px solid #ccc;
}

.blur{
	filter:blur(10px);
}

#qty-table-stok tr td{
	/* cursor:pointer; */
}

#form-rekap-barang{
	margin:auto;
}

#form-rekap-barang tr td,
#form-rekap-barang tr th{
	text-align:center;
	vertical-align:middle;
	padding:0px 10px;
}

#form-rekap-barang tr td{
	padding:5px;
	width:80px;
}

</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade bs-modal-lg" id="portlet-config-split" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">							
							<div class="div-form-container">
								<div class="col-xs-12" style='position:relative;'>
									<h2 class='text-center'>
										<i class=" fa fa-plus"></i> ASSEMBLY BARU
									</h2>
									<hr>
									<div class="col-xs-4">
										<div class="stok-add-container" id="form-add-barang-container">
											<h2 class='text-center'>FORM</h2>
											<form id="form-add-barang" action="<?=base_url()?>inventory/assembly_list_insert" method="POST">
												<input type="date" value="<?=date('Y-m-d')?>" name="" id="newTanggal" class='form-control'>
												<select name="toko_id"  id="toko_id_new" class="form-control">
													<?$isChecked = false;
													foreach ($this->toko_list_aktif as $row) {?>
														<option  <?=($isChecked ? "" : "selected"); $isChecked = true;?> value="<?=$row->id?>"><?=$row->nama;?></option>
													<?}?>
												</select>
												<select name="gudang_id" id="gudang_id_new" class="form-control">
													<?$isChecked = false;
													foreach ($this->gudang_list_aktif as $row) {?>
														<option <?=($isChecked ? "" : "selected"); $isChecked = true;?> value="<?=$row->id?>"><?=$row->nama;?></option>
													<?}?>
												</select>
												<label style='margin-top:5px;' onClick="setEqual('equal_new')">
													<input type="checkbox" checked value='1' name="equal_status" id="equal_new" >Sumber = Hasil</label>
												<hr style="margin:5px 0" />
												<select name="barang_id_sumber" id="barang_id_new_sumber" class="form-control" >
													<option value="">Barang Sumber</option>
													<?foreach ($stok_barang as $row) {?>
														<option value="<?=$row->barang_id?>-<?=$row->warna_id?>"><?=$row->nama_barang_jual?> <?=$row->nama_warna_jual;?></option>
													<?}?>
												</select>
												<hr style="margin:5px 0"/>
												<select name="barang_id_hasil" id="barang_id_new_hasil" class="form-control">
													<option value="">Barang Hasil</option>
													<?foreach ($this->barang_list_aktif as $row) {?>
														<option value="<?=$row->id?>"><?=$row->nama_jual?></option>
													<?}?>
												</select>
												<select name="warna_id_hasil" id="warna_id_new_hasil" class="form-control">
													<option value="">Keterangan Hasil</option>
													<?foreach ($this->warna_list_aktif as $row) {?>
														<option value="<?=$row->id?>"><?=$row->warna_jual;?></option>
													<?}?>
												</select>
												<hr style="margin:5px 0"/>
											</form>
											<!-- <button style="margin-top:10px" class="btn btn-block default" onClick="getStok()"><i class='fa fa-plus'></i> QTY</button> -->
										</div>
									</div>
									
									<div class="col-xs-4">
										<div class="stok-add-container" id="stok-add-container">
											<h2 class='text-center'>STOK</h2>
											<div>
												<table>
													<tr>
														<th>QTY</th>
														<th>DATA</th>
														<th>SUPPLIER</th>
														<th></th>
													</tr>
												</table>
											</div>
										</div>
										<div id="stok-add-get">

										</div>
									</div>
									<div class="col-xs-4">
										<div class="stok-add-container" >
											<h2 class='text-center'>REKAP</h2>
											<table id="form-rekap-barang">
												<tr>
													<th>SUMBER</th>
													<th rowspan='4'>
														<i class="fa fa-caret-right"></i>
													</th>
													<th>HASIL</th>
												</tr>
												<tr>
													<td id="sumber-nama"  style='height:60px; background:lightpink'></td>
													<td id="hasil-nama" style='height:60px; background:lightgreen'></td>
												</tr>
												<tr>
													<td id="sumber-qty"  style='font-weight:bold;font-size:1.2em; height:25px; background:#ddd' ></td>
													<td id="hasil-qty"  style='font-weight:bold;font-size:1.2em; height:25px; background:#ddd' ></td>
												</tr>
												<tr>
													<td id="sumber-satuan"  style='font-size:0.8em; height:10px; background:#ccc' ></td>
													<td id="hasil-satuan"  style='font-size:0.8em; height:10px; background:#ccc' ></td>
												</tr>
											</table>
											<!-- <button style="margin-top:10px; "  class='btn btn-block green'>SAVE</button> -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="main-container">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-active blue" id="btnAssemblySave" onClick="submitNewAssembly()" disabled>Save</button>
						<button type="button" class="btn default" data-dismiss="modal" id="btnSplitClose">Close</button>
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
							<a href="#portlet-config-split" data-toggle='modal' id="btnShowForm" class="btn green btn-sm hidden-print" >
							<i class="fa fa-plus"></i> Assembly </a>
						</div>
					</div>
					<div class="portlet-body">
                        <table>
                            <tr>
                                <td>Lokasi</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
                                    <b>
                                        <select name='gudang_id' id='gudang-id' style='width:200px;'>
                                        <option <?=($gudang_id == ''  ? 'selected' : "")?>  value="" >Pilih</option>
                                        <?foreach ($this->gudang_list_aktif as $row) {?>
                                            <option <?=($gudang_id == $row->id ? 'selected' :"")?> value="<?=$row->id;?>" ><?=$row->nama;?></option>
                                        <?}?>
                                        </select>
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
                                    <b>
                                        <select name='barang_id'  id='barang-id' style='width:200px;'>
                                            <option <?=($barang_id == ''  ? 'selected' : "")?>  value="" >Pilih</option>
                                            <?foreach ($this->barang_list_aktif as $row) {?>
                                                <option <?=($barang_id == $row->id ? 'selected' : "")?>  value="<?=$row->id;?>" ><?=$row->nama_jual;?><?=(is_posisi_id()==1 ? $barang_id.'-'.$row->id : '')?></option>
                                            <?}?>
                                        </select>
                                    </b>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Warna</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
                                    <b>
                                        <select name='warna_id'  id='warna-id' style='width:200px;'>
                                            <option <?=($warna_id == ''  ? 'selected' : "")?>  value="" >Pilih</option>
                                            <?foreach ($this->warna_list_aktif as $row) {?>
                                                <option <?=($warna_id == $row->id ? 'selected' :"")?>  value="<?=$row->id;?>" ><?=$row->warna_jual;?></option>
                                            <?}?>
                                        </select>
                                    </b>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Split</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
									<input name='tanggal_start' readonly class='date-picker'  style='width:200px;' value='<?=$tanggal_start;?>'>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="padding-top:10px;"><button class='btn btn-block btn-xs default' onclick="getDataList()">Filter Data <i class='fa fa-search'></i></button></td>
                            </tr>
                        </table>
						<form action='' method='get'>
							
						</form>
						<hr/>
						<?
							$qty = 0;
							$roll = 0;
							?>
                        <div style="margin-bottom:15px;text-align:right; position:relative">
                            <div class="keterangan-jumlah-baris" style='position:absolute; bottom:0px;'>
                                Menampilkan <b style="font-size:14px;" class='countFilteredRow'>0</b> dari <b style="font-size:14px;" class='countAllRow'>0</b> baris
                            </div>
                            <div class="search-div" >
                                SEARCH : <input type="search"  class='searchInTable' style="font-size:14px;" placeholder="cari...">
                            </div>
                        </div>
						<table class="table table-striped" id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2' onclick="sortTable('tanggal','0')">
										Tanggal
									</th>
                                    <th scope="col" rowspan='2' onclick="sortTable('username','1')">
										User
									</th>
                                    <th scope="col" rowspan='2' onclick="sortTable('nama_gudang','2')">
										Gudang
									</th>
									<th scope="col" rowspan='2' onclick="sortTable('nama_barang','3')">
										Barang
									</th>
									<th scope="col" colspan='2' onclick="sortTable('qty', '4')" class='text-center'>
										Ori
									</th>
									<th scope="col" colspan='2' class='text-center' style='cursor:no-drop'>
										Hasil
									</th>
									<th scope="col" colspan='2'>
                                        
									</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
                            <tfoot>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
const addBarangList = document.querySelector("#addBarangList");
const addBarangTable = document.querySelector('#newAssemblyTable');
const addTanggal = $("#newTanggal");
const barangList = <?=json_encode($this->barang_list_aktif);?>;
const gudangList = <?=json_encode($this->gudang_list_aktif)?>;
const satuanList = <?=json_encode($this->satuan_list_aktif)?>;
const tokoList = <?=json_encode($this->toko_list_aktif)?>;
const stok_url = "stok/stok_general/get_qty_stock_by_barang_detail";
const brgNewSumber = $('#barang_id_new_sumber'); 
const brgNewHasil = $('#barang_id_new_hasil'); 
const warnaNewHasil = $("#warna_id_new_hasil");
const tokoNew = $('#toko_id_new');
const gudangNew = $('#gudang_id_new');
const btnAssembly = document.querySelector(`#btnAssemblySave`);
const rekapDiv = document.querySelector(`#form-rekap-barang`);
var counter = 0;

const satuans = [];
const barangMix = [];
var tempRekap = [];
const satuanBarang = [];

//assembly new list
const asl= [];
satuanList.forEach((satuan, index) => {
	satuans[`s-${satuan.id}`] = satuan.nama;
});
barangList.forEach((item, index) => {
	satuanBarang[`s-${item.id}`] = [item.satuan_id, satuans[`s-${item.satuan_id}`]];
	if (item.eceran_mix_status==1) {
		barangMix[`m-${item.id}`] = true;
	}else{
		barangMix[`m-${item.id}`] = false;
	}
});

const today = new Date();
const tempItem = {
		tanggal:`${today.getFullYear()}-${parseInt(today.getMonth())+1}-${today.getDate()}`,
		toko_id:tokoNew.val(),
		gudang_id:gudangNew.val(),
		barang_id_sumber:'',
		warna_id_sumber:'',
		barang_id_hasil:'',
		warna_id_hasil:'',
		nama_sumber:'',
		satuan_sumber:'',
		satuan_id_sumber:'',
		nama_hasil:'',
		satuan_hasil:'',
		satuan_id_hasil:'',
		equal_status:1,
		total_sumber: 0,
		total_roll_sumber: 0,
		total_hasil: 0,
		total_roll_hasil: 0,
		rekap:{}
	};

jQuery(document).ready(function(){
	$('#barang_id_new_sumber, #barang_id_new_hasil, #warna_id_new_hasil').select2();
	tokoNew.change(function(){
		tempItem.toko_id=tokoNew.val();
		getStok();
	});

	gudangNew.change(function(){
		tempItem.gudang_id=gudangNew.val();
		getStok();
	});

	brgNewSumber.change(function(){
		const barang = brgNewSumber.select2('data');
		const brgData = barang.id.split('-');
		
		const namaBarang = $()
		tempItem.barang_id_sumber=brgData[0];
		tempItem.warna_id_sumber=brgData[1];
		tempItem.nama_sumber=barang.text;

		tempItem.satuan_id_sumber=satuanBarang[`s-${brgData[0]}`][0];
		tempItem.satuan_sumber=satuanBarang[`s-${brgData[0]}`][1];
		if(brgNewHasil.val() == ''){
			checkMixStatus();
		}
		getStok();
		showRekap();
	});

	brgNewHasil.change(function(){
		const barang = brgNewHasil.select2('data');
		const warna = warnaNewHasil.select2('data');
		tempItem.barang_id_hasil=barang.id;

		tempItem.satuan_id_hasil=satuanBarang[`s-${barang.id}`][0];
		tempItem.satuan_hasil=satuanBarang[`s-${barang.id}`][1];
		if (barang.id !='' && warna.id != '') {
			console.log(warna);
			tempItem.nama_hasil = barang.text+' '+warna.text;
		}else{
			tempItem.nama_hasil = '';
		}
		showRekap();
	});
	
	warnaNewHasil.change(function(){
		const barang = brgNewHasil.select2('data');
		const warna = warnaNewHasil.select2('data');
		tempItem.warna_id_hasil=warna.id;
		if (barang.id !='' && warna.id != '') {
			tempItem.nama_hasil = barang.text+' '+warna.text;
		}else{
			tempItem.nama_hasil = '';
		}
		showRekap();
	});

});

function checkMixStatus(){
	const brgData = brgNewSumber.val().split('-');
	const brg_id = brgData[0];
	console.log(brg_id);
	if(barangMix[`m-${brg_id}`]){
		brgNewHasil.val(brg_id);
		brgNewHasil.change();
		warnaNewHasil.val(888);
		warnaNewHasil.change();
	}	
}

function getStok(){
	$(`#stok-add-container div`).html(`<p class='text-center'>cek stok <i class='fa fa-spin fa-cog'></i></p>`);

	const brgData= brgNewSumber.val().split('-');
	const barang_id = brgData[0];
	const warna_id = brgData[1];
	const toko_id = tokoNew.val();
	const gudang_id = gudangNew.val();
	const tanggal = addTanggal.val();

	data={};
	
	data['toko_id'] = toko_id;
	data['gudang_id'] = gudang_id;
	data['barang_id'] = barang_id;
	data['warna_id'] = warna_id;
	data['is_eceran'] = 0;
	data['tanggal'] = tanggal;

	if (barang_id != '' && warna_id != '' && gudang_id != '') {
		var url = "stok/stok_general/get_qty_stock_by_barang_detail";

		
		//alert('test');
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			// alert(data_respond);
			var qty = 0;
			var jumlah_roll = 0;
			let table_stok = '';
			let table_stok_array = [];
			let btn_supplier = '';
			let table_eceran = '';
			let idx = 1;
			let qty_row = 0;
			let supplier_id = '';
			let qty_stok = 0;
			let qty_eceran = 0;
			let status = '';
			let total_page = 1;
			let tombol_page = '';
			let showHead = `<thead>
				<tr>
					<th>QTY</th>
					<th>DATA</th>
					<th>SUPPLIER</th>
				</tr>
				</thead>`;
			let showBody = "";
			let totalStok = '';

			setTimeout(() => {
				$("#qty-table-stok tbody").html('');
				$.each(JSON.parse(data_respond), function(k,v){
					tempRekap = [];
					if(k==0){
						const stokList = v;
						stokList.forEach((stok, index) => {
							if (stok.jumlah_roll > 0) {
								totalStok += stok.qty * stok.jumlah_roll;
								showBody += `<tr class='id-${index}'>
									<td class='text-center qty'>${stok.qty}</td>
									<td class='text-center roll'>${stok.jumlah_roll}</td>
									<td>
										<span class='nama_supplier'>${stok.nama_supplier}</span>
										<span class='supplier_id' hidden>${stok.supplier_id}</span>
									</td>
									<td>
										<button class='btn btn-xs red' id='btn-unget-${index}' onClick="unAmbilStok('${index}')">-</button>
										<button class='btn btn-xs green' id='btn-get-${index}' onClick="ambilStok('${index}')">+</button>
									</td>
								</tr>`;
							}
						});

						showList = showHead + `<tbody>${showBody}</tbody>`;
	
						if (totalStok == 0) {
							showList = `<tr><td>No Stok</td></tr>`;
						}
						
						$(`#stok-add-container div`).html(`<table id="qty-table-stok">${showList}</table>`);
	
						// for (let i = 0; i < v.length; i++) {
							
						// 	supplier_id = v[i].supplier_id;
						// 	if (typeof table_stok_array[supplier_id] === 'undefined') {
						// 		table_stok_array[supplier_id] = [];
						// 		const nama_supplier = (supplier_id != 0 ? v[i].nama_supplier : 'not assigned');
						// 		btn_supplier += `<button style='margin:0px 10px 5px 0px' class="btn btn-xs default btn-show-stok" id='btn-supplier-${supplier_id}' onclick="showStokSupplier('${supplier_id}')">${nama_supplier}</button>`
						// 	}
						// 	qty += parseFloat(v[i].qty);
						// 	jumlah_roll += parseFloat(v[i].jumlah_roll);
						// 	status = ((v[i].jumlah_roll <= 0) ? 'habis' : '');
						// 	page_idx = parseInt(idx/10) +  parseInt((idx % 10 != 0 ) ? 1 : 0);
						// 	var class_qty = parseFloat(v[i].qty);
						// 	class_qty = class_qty.toString().replace('.','');
						// 	let btnEcer = '';
						// 	if(isEceran && parseFloat(v[i].jumlah_roll) > 0){
						// 		btnEcer = `<button onclick="mutasiToEceran('${class_qty}','${v[i].qty}','${supplier_id}')">add to eceran</button>`;
						// 	}
	
						// 	console.log('3',v[i].nama_supplier);
						// 	const content = `<tr data-supplier='${supplier_id}' class='row-stok row-${v[i].qty} page-${v[i].qty} baris-table ${status} '>
						// 		<td class='idx-${class_qty}' ><span class='qty-stok' >${parseFloat(v[i].qty)}</span></td>
						// 		<td><span class='roll-stok'>${parseFloat(v[i].jumlah_roll)}</span> </td>
						// 		<td>
						// 			<span class='nama_supplier'> ${v[i].nama_supplier}</span>
						// 			<span class='supplier_id' hidden>${supplier_id}</span> 
						// 		</td>
						// 		<td>${btnEcer}</td>
						// 	</tr>`;
	
	
						// 	table_stok_array[supplier_id] += content; 
						// 	table_stok += content;
						// 	qty_stok += parseFloat(v[i].qty*v[i].jumlah_roll);
						// 	qty_row = idx;
						// 	idx++;
							
						// }
					}
				});
			}, 500);
		});
		
	}else{
		console.log(`barang:${barang_id}, warna:${warna_id},gudang:${gudang_id},toko:${toko_id}, tgl:${tanggal}`);
		setTimeout(() => {
			$(`#stok-add-container div`).html(`Mohon isi barang sumber`);
		}, 1000);

	};
}

function ambilStok(index){
	const row = document.querySelectorAll("#qty-table-stok tbody tr")[index];
	const roll = row.querySelector(".roll");
	const rolVal = parseFloat(roll.innerHTML.toString().trim());
	const qtyVal = parseFloat(row.querySelector(".qty").innerHTML.toString().trim());
	const supplier_id = parseFloat(row.querySelector(".supplier_id").innerHTML.toString().trim());

	if (rolVal > 0 && rolVal != '' && typeof rolVal !== 'undefined') {
		if (typeof tempItem.rekap[`q-${index}`] === 'undefined') {
			tempItem.rekap[`q-${index}`] = {
				qty:qtyVal,
				jumlah_roll:1,
				supplier_id:supplier_id
			};
		}else{
			tempItem.rekap[`q-${index}`].jumlah_roll++;
		}
		const nRoll = rolVal- 1;
		roll.innerHTML = nRoll;
		showRekap();
	}else{
		// console.log('rv',rolVal, typeof rolVal);
	}

}

function unAmbilStok(index){
	
	const row = document.querySelectorAll("#qty-table-stok tbody tr")[index];
	const roll = row.querySelector(".roll");
	let rolVal = parseFloat(roll.innerHTML.toString().trim());
	if (typeof tempItem.rekap[`q-${index}`] !== 'undefined') {
		if (tempItem.rekap[`q-${index}`].jumlah_roll > 0) {
			tempItem.rekap[`q-${index}`].jumlah_roll -= 1;
			rolVal += 1;
			roll.innerHTML = rolVal;
		}
	}	
	showRekap();

}

function showRekap(){
	btnAssembly.disabled = true
	let total = 0;
	let totalRoll = 0;
	
	document.querySelector('#sumber-nama').innerHTML = tempItem.nama_sumber;
	document.querySelector('#hasil-nama').innerHTML = tempItem.nama_hasil;

	
	document.querySelector('#sumber-satuan').innerHTML = tempItem.satuan_sumber;
	document.querySelector('#hasil-satuan').innerHTML = tempItem.satuan_hasil;
	for(const list in tempItem.rekap){
		totalRoll += parseFloat(tempItem.rekap[list].jumlah_roll);
		total += (parseFloat(tempItem.rekap[list].qty) * tempItem.rekap[list].jumlah_roll);
	}

	rekapDiv.querySelector('#sumber-qty').innerHTML = total;
	rekapDiv.querySelector('#hasil-qty').innerHTML = total;

	if (total > 0) {
		btnAssembly.disabled = false
	}
}

function closeAddForm(){
	$(".div-form-container").hide();
	$(".div-rekap-container").find(".blur").removeClass('blur');
}

function showAddForm(){
	$(".div-form-container").show();
	$("#addListTable").addClass('blur');	
}

function setEqual(id){
	const box = document.querySelector(`#${id}`);
	if (box.checked == false) {
		bootbox.alert("Fitur No Equal belum tersedia");
		setTimeout(() => {
			box.checked = true;
			$(`#${id}`).uniform.update();
		}, 500);	
	}
	tempItem.equal_status = (box.checked ? 1 : 0);
}

function submitNewAssembly(){
	const dialog = bootbox.dialog({
			message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Saving...</p>`,
			closeButton: false
		});
	fetch(baseurl+"inventory/assembly_list_insert",{
		method:"POST",
        headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `data=${JSON.stringify(tempItem)}`
	})
	.then((response) => response.json())
	.then((data) => {
		dialog.modal('hide');
		console.log(data)
	});
}

</script>
