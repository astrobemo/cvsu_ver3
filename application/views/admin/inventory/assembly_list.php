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
	/* background:lightblue; */
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

.stok-add-container table{
	margin:auto;
	font-size:13px;
}

.stok-add-container table tr th,
.stok-add-container table tr td{
	padding:3px;
	border:1px solid #ccc;
}

.blur{
	filter:blur(10px);
}

#qty-table-stok tr td{
	/* cursor:pointer; */
}

.form-rekap-barang{
	margin:auto;
}

.form-rekap-barang tr td,
.form-rekap-barang tr th{
	text-align:center;
	vertical-align:middle;
	padding:0px 10px;
}

.form-rekap-barang tr td{
	padding:5px;
	width:80px;
}

.inactive{
	display:none;
}

#qty-table-hasil input{
	width:50px; 
	height:30px;
	background:rgba(255,255,255,0.3);
	padding:5px;
	border:none;
	text-align:center;
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
										<div class="stok-add-container" style="background:#ddd" id="form-add-barang-container">
											<h2 class='text-center'>FORM</h2>
											<form id="form-add-barang" action="<?=base_url()?>inventory/assembly_list_insert" method="POST">
												<input readonly value="<?=date('Y-m-d')?>" name="" id="newTanggal" class='form-control date-picker'>
												<select name="toko_id"  id="toko_id_new" class="form-control">
													<?foreach ($this->toko_list_aktif as $row) {?>
														<option value="<?=$row->id?>"><?=$row->nama;?></option>
													<?}?>
												</select>
												<select name="gudang_id" id="gudang_id_new" class="form-control">
													<?foreach ($this->gudang_list_aktif as $row) {?>
														<option <?=($row->status_default == 1 ? "selected" : ""); $isChecked = true;?> value="<?=$row->id?>"><?=$row->nama;?></option>
													<?}?>
												</select>
												<label style='margin-top:5px;' onClick="setEqual('equal_new')">
													<input type="checkbox" checked value='1' name="equal_status" id="equal_new" >QTY Sumber = QTY Hasil</label>
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
										<div class="stok-add-container" style="background:lightblue"  id="stok-add-container">
											<h2 class='text-center'>SUMBER</h2>
											<div>
												<table id="qty-table-stok">
													<thead>
														<tr>
															<th colspan='4' style="background:#ddd" class='text-center' id="sumber-nama"></th>
														</tr>
														<tr>
															<th>SUPPLIER</th>
															<th id="satuan-sumber">QTY</th>
															<th id="packaging-sumber">DATA</th>
															<th></th>
														</tr>
													</thead>
													<tbody></tbody>
													<tfoot>
														<tr>
															<th>STOK</th>
															<th id="sumber-qty"  style='font-weight:bold;font-size:1.2em; text-align:center' ></th>
															<th id="sumber-roll"  style='font-weight:bold;font-size:1.2em; text-align:center' ></th>
														</tr>
														<tr>
															<th>AMBIL</th>
															<th id="sumber-qty-ambil"  style='font-weight:bold;font-size:1.2em; text-align:center' ></th>
															<th id="sumber-roll-ambil"  style='font-weight:bold;font-size:1.2em; text-align:center' ></th>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
										<div id="stok-add-get">

										</div>
									</div>
									<div class="col-xs-4">
										<div class="stok-add-container" style="background:rgba(0,225,0,0.3)" >
											<h2 class='text-center'>HASIL</h2>
											<div>
												<table id="qty-table-hasil">
													<thead>
														<tr>
															<th colspan='4' style="background:#ddd" class='text-center' id="hasil-nama"></th>
														</tr>
														<tr>
															<th></th>
															<th id="satuan-hasil">QTY</th>
															<th id="packaging-hasil">DATA</th>
															<th></th>
														</tr>
													</thead>
													<tbody></tbody>
													<tfoot>
														<tr id="inputHasilRow" class='inactive text-center' style="background:#ddd;">
															<td id='newRowIndex'>1</td>
															<td style="padding:0px;" onChange="rekapHasilRow()"><input type="text" class="inputNewQty"></td>
															<td style="padding:0px;" onChange="rekapHasilRow()"><input type="text" class="inputNewRoll"></td>
															<td><button class="btn-xs btn green" onClick="addNewHasilRow()"><i class="fa fa-plus"></i></button></td>
														</tr>
														<tr>
															<th>TOTAL<br/>HASIL</th>
															<th id="hasil-qty"  style='font-weight:bold;font-size:1.2em; text-align:center' ></th>
															<th id="hasil-roll"  style='font-weight:bold;font-size:1.2em; text-align:center' ></th>
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
							<a href="#portlet-config-split" data-toggle='modal' id="btnShowForm" class="btn green btn-sm hidden-print" onClick="newAssembly()" >
							<i class="fa fa-plus"></i> Assembly </a>
						</div>
					</div>
					<div class="portlet-body">
						<form action='' method='get'>
							<table>
								<tr>
									<td>Lokasi</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select name='gudang_id' id='gudang-id' style='width:205px;'>
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
											<select name='barang_id'  id='barang-id' style='width:205px;'>
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
											<select name='warna_id'  id='warna-id' style='width:205px;'>
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
										<input name='tanggal_start' readonly class='date-picker text-center'  style='width:90px;' value='<?=$tanggal_start;?>'>
										s/d
										<input name='tanggal_end' readonly class='date-picker text-center'  style='width:90px;' value='<?=$tanggal_end;?>'>
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td style="padding-top:10px;"><button class='btn btn-block btn-xs default' onclick="getDataList()">Filter Data <i class='fa fa-search'></i></button></td>
								</tr>
							</table>
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
						<table class="table table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2' onclick="sortTable('tanggal','0')">
										Tanggal
									</th>
                                    <th scope="col" rowspan='2' onclick="sortTable('username','1')">
										User
									</th>
									<th scope="col" rowspan='2' onclick="sortTable('toko','1')">
										Toko
									</th>
                                    <th scope="col" rowspan='2' onclick="sortTable('nama_gudang','2')">
										Gudang
									</th>
									<th scope="col" class='text-center' colspan='2' onclick="sortTable('nama_barang_sumber','3')" style='border-bottom:1px solid #ddd'>
										Barang Sumber
									</th>
									<th scope="col" class='text-center' colspan='2' onclick="sortTable('nama_barang_hasil','3')" style='border-bottom:1px solid #ddd'>
										Barang Hasil
									</th>
									<th scope="col" rowspan='2' style='border-left:1px solid #ddd'>
                                        
									</th>
								</tr>
								<tr>
									<th scope="col" onclick="sortTable('qty', '4')" class='text-center'>
										Nama
									</th>
									<th scope="col" class='text-center'>
										QTY
									</th>
									<th scope="col" class='text-center' style='cursor:no-drop'>
										Nama
									</th>
									<th scope="col" onclick="sortTable('qty', '4')" class='text-center'>
										QTY
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($assembly_list as $row) {?>
									<tr id='baris-<?=$row->id;?>'>
										<td><?=is_reverse_date($row->tanggal)?></td>
										<td><?=$row->username?></td>
										<td><?=$row->nama_toko?></td>
										<td><?=$row->nama_gudang?></td>
										<td><?=$row->nama_barang_sumber?> <?=$row->nama_warna_sumber;?></td>
										<td><?=$row->qty_sumber?></td>
										<td><?=$row->nama_barang_hasil?> <?=$row->nama_warna_hasil;?></td>
										<td><?=$row->qty_hasil?></td>
										<td>
											<button class="btn btn-xs green" onclick="editData('<?=$row->id?>')"><i class="fa fa-edit"></i></button>
											<?if (is_posisi_id() <= 3 || is_posisi_id() == 9) {?>
												<button class="btn btn-xs red" onclick="deleteData('<?=$row->id?>')"><i class="fa fa-times"></i></button>
											<?}?>
										</td>
									</tr>
								<?}?>
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
const rekapDiv = document.querySelector(`.form-rekap-barang`);
const satuanSumber = document.querySelector("#satuan-sumber");
const packagingSumber = document.querySelector("#packaging-sumber");
const satuanHasil = document.querySelector("#satuan-hasil");
const packagingHasil = document.querySelector("#packaging-hasil");
const rowInput = document.querySelector('#inputHasilRow');
var counter = 0;

const satuans = [];
const barangMix = [];
var tempRekap = [];
const satuanBarang = [];
var stokList = [];
var totalStokTemp = 0;
var totalStokRollTemp = 0;
let assemblyList = <?=json_encode($assembly_list);?>

// console.log(assemblyList);

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
		id:'',
		tanggal:`${today.getFullYear()}-${parseInt(today.getMonth())+1}-${today.getDate()}`,
		toko_id:tokoNew.val(),
		gudang_id:gudangNew.val(),
		barang_id_sumber:brgNewSumber.val().split('-')[0],
		warna_id_sumber:brgNewSumber.val().split('-')[1],
		barang_id_hasil:brgNewHasil.val(),
		warna_id_hasil:warnaNewHasil.val(),
		nama_sumber:'',
		satuan_sumber:'',
		satuan_id_sumber:'',
		nama_hasil:'',
		satuan_hasil:'',
		satuan_id_hasil:'',
		equal_status:1,
		qty_sumber: 0,
		jumlah_roll_sumber: 0,
		qty_hasil: 0,
		jumlah_roll_hasil: 0,
		rekap_hasil:{},
		rekap_sumber:{}
	};

	const tempItemEdit = {};

function initTempData(){
	brgNewSumber.val("").change();
	brgNewHasil.val("").change();
	warnaNewHasil.val("").change();
	
	tempItem.barang_id_sumber="";
	tempItem.warna_id_sumber="";
	tempItem.barang_id_hasil="";
	tempItem.warna_id_hasil="";
	tempItem.nama_sumber='';
	tempItem.satuan_sumber='';
	tempItem.satuan_id_sumber='';
	tempItem.nama_hasil='';
	tempItem.satuan_hasil="";
	tempItem.satuan_id_hasil="";
	tempItem.qty_sumber= 0;
	tempItem.jumlah_roll_sumber= 0;
	tempItem.qty_hasil= 0;
	tempItem.jumlah_roll_hasil= 0;
	tempItem.rekap_hasil={};
	tempItem.rekap_sumber={};

	document.querySelector('#sumber-qty').innerHTML = '0';
	document.querySelector('#sumber-roll').innerHTML = '0';
	document.querySelector('#sumber-qty-ambil').innerHTML = '0';
	document.querySelector('#sumber-roll-ambil').innerHTML = '0';
	document.querySelector('#hasil-qty').innerHTML = '0';
	document.querySelector('#hasil-roll').innerHTML = '0';

}

function tokoBG(){
	const toko_id = tokoNew.val();;
	let bg = '';
	tokoList.forEach(toko => {
		if (toko.id == toko_id) {
			bg = colo
		}
	});
}

jQuery(document).ready(function(){
	$('#barang_id_new_sumber, #barang_id_new_hasil, #warna_id_new_hasil').select2();

	addTanggal.change(function(){
		tempItem.tanggal = addTanggal.val().split('/').reverse().join('-');
	});

	tokoNew.change(function(){
		tempItem.toko_id=tokoNew.val();
		if (tempItem.id =='') {
			getStok();
		}
	});

	gudangNew.change(function(){
		tempItem.gudang_id=gudangNew.val();
		if (tempItem.id =='') {
			getStok();
		}
	});

	brgNewSumber.change(function(){
		if (brgNewSumber.val() != '' && brgNewSumber.val() != null) {
			
			const barang = brgNewSumber.select2('data');
			const brgData = barang.id.split('-');
			totalStokTemp = 0;


			if (tempItem.id == '') {
				tempItem.barang_id_sumber=brgData[0];
				tempItem.warna_id_sumber=brgData[1];
				tempItem.rekap_sumber={};

				getStok();
				showRekap();

			}
			tempItem.nama_sumber=barang.text;
			tempItem.satuan_id_sumber=satuanBarang[`s-${brgData[0]}`][0];
			tempItem.satuan_sumber=satuanBarang[`s-${brgData[0]}`][1];

			const barangIdHasil = brgNewHasil.val();

			if (brgData[0] != barangIdHasil) {
				bootbox.confirm({
					message: "Jenis barang Hasil sama dengan barang sumber ?",
					buttons: {
					confirm: {
					label: 'Ya',
					className: 'btn-primary'
					},
					cancel: {
					label: 'Tidak',
					className: 'btn-default'
					}
					},
					callback: function(respond){
					if (respond) {
							brgNewHasil.val(brgData[0]).change();
						}
					}
				})
			}

			if(brgNewHasil.val() == ''){
				checkMixStatus();
			}

			
			
		}
	});

	brgNewHasil.change(function(){
		const barang = brgNewHasil.select2('data');
		const warna = warnaNewHasil.select2('data');

		if (tempItem.id == '') {
			tempItem.barang_id_hasil=barang.id;
			if (barang.id !='' && warna.id != '') {
				tempItem.satuan_id_hasil=satuanBarang[`s-${barang.id}`][0];
				tempItem.satuan_hasil=satuanBarang[`s-${barang.id}`][1];
			}else{
				tempItem.nama_hasil = '';
			}

			tempItem.rekap_hasil={};
			setStokShow();
			showRekap();
		}

		tempItem.nama_hasil = barang.text+' '+warna.text;

	});
	
	warnaNewHasil.change(function(){
		const barang = brgNewHasil.select2('data');
		const warna = warnaNewHasil.select2('data');

		if (tempItem.id == '') {
			tempItem.warna_id_hasil=warna.id;
			if (barang.id !='' && warna.id != '') {
				tempItem.nama_hasil = barang.text+' '+warna.text;
			}else{
				tempItem.nama_hasil = '';
			}
			setStokShow();
			showRekap();
		}
	});

});

function newAssembly(){
	getStok();
	setEqual('equal_new');

	addTanggal.val("<?=date('d/m/Y')?>");
	tempItem.tanggal = addTanggal.val().split('/').reverse().join('-');
	tempItem.id = '';
	// tempItem.toko_id = list.toko_id;
	// tempItem.gudang_id = list.gudang_id;
	// tempItem.barang_id_sumber = list.barang_id_sumber;
	// tempItem.warna_id_sumber = list.warna_id_sumber;
	// tempItem.barang_id_hasil = list.barang_id_hasil;
	// tempItem.warna_id_hasil = list.warna_id_hasil;
	// tempItem.nama_sumber='';
	// tempItem.satuan_sumber='';
	// tempItem.satuan_id_sumber='';
	// tempItem.nama_hasil='';
	// tempItem.satuan_hasil='';
	// tempItem.satuan_id_hasil='';
	// tempItem.equal_status=list.equal_status;
	// tempItem.qty_sumber= list.qty_sumber;
	// tempItem.jumlah_roll_sumber= list.jumlah_roll_sumber;
	// tempItem.qty_hasil= list.qty_hasil;
	// tempItem.jumlah_roll_hasil= list.jumlah_roll_hasil;

	tokoNew.val(tempItem.toko_id).change();
	gudangNew.val(tempItem.gudang_id).change();
	brgNewSumber.val(tempItem.barang_id_sumber+'-'+tempItem.warna_id_sumber).change();
	brgNewHasil.val(tempItem.barang_id_hasil).change();

	warnaNewHasil.val(tempItem.warna_id_hasil).change();

	brgNewSumber.prop('disabled',false);
	brgNewHasil.prop('disabled',false);
	warnaNewHasil.prop('disabled',false);
	tokoNew.prop('disabled',false);
	gudangNew.prop('disabled',false);

}

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
	$(`#qty-table-stok tbody`).html(`<td colspan='3' class='text-center'>cek stok <i class='fa fa-spin fa-cog'></i></td>`);
	document.querySelector("#inputHasilRow .inputNewQty").value='';
	document.querySelector("#inputHasilRow .inputNewRoll").value='';

	// const brgData= brgNewSumber.val().split('-');

	// const barang_id = brgData[0];
	// const warna_id = brgData[1];
	// const toko_id = tokoNew.val();
	// const gudang_id = gudangNew.val();
	// const tanggal = addTanggal.val();

	// const brgData = tempItem.barang_id

	const barang_id = tempItem.barang_id_sumber;
	const warna_id = tempItem.warna_id_sumber;
	const toko_id = tempItem.toko_id;
	const gudang_id = tempItem.gudang_id;
	const tanggal = tempItem.tanggal;

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
			let showBody = "";

			setTimeout(() => {
				$("#qty-table-stok tbody").html('');
				$.each(JSON.parse(data_respond), function(k,v){
					tempRekap = [];
					if(k==0){
						stokList = v;
						setStokShow();
					}
				});
			}, 500);
		});
		
	}else{
		setTimeout(() => {
			$(`#qty-table-stok tbody`).html(`<td colspan='3' class='text-center'>Mohon isi barang sumber</td>`);
			document.querySelector('#sumber-qty').innerHTML = '0';
			document.querySelector('#sumber-roll').innerHTML = '0';
			document.querySelector('#sumber-qty-ambil').innerHTML = '0';
			document.querySelector('#sumber-roll-ambil').innerHTML = '0';
		}, 1000);

	};
}

function setStokShow(){
	$(`#qty-table-stok tbody`).html("");
	if (tempItem.id == '') {
		tempItem.rekap_sumber={};
		tempItem.rekap_hasil={};
	}
	let showList = '';
	totalStokTemp = 0;
	totalStokRollTemp = 0;
	let rowIndex=0;
	stokList.forEach((stok, index) => {
		if (stok.jumlah_roll > 0 && stok.qty) {
			totalStokTemp += stok.qty * stok.jumlah_roll;
			totalStokRollTemp += parseInt(stok.jumlah_roll);
			let qIdx = stok.qty.toString().replace(".00","");
			qIdx = qIdx.replace(".","_");
			let aRoll = 0;
			if (typeof tempItem.rekap_sumber[`q-${qIdx}`] !== 'undefined') {
				aRoll = tempItem.rekap_sumber[`q-${qIdx}`].jumlah_roll;
			}
			showList += `<tr id='id-${qIdx}'>
				<td>
					<span class='nama_supplier'>${stok.nama_supplier}</span>
					<span class='supplier_id' hidden>${stok.supplier_id}</span>
				</td>
				<td class='text-center qty'>${stok.qty}</td>
				<td class='text-center roll'>${stok.jumlah_roll - aRoll}</td>
				<td>
					<button class='btn btn-xs red' id='btn-unget-${rowIndex}' onClick="unAmbilStok('${rowIndex}')">-</button>
					<button class='btn btn-xs green' id='btn-get-${rowIndex}' onClick="ambilStok('${rowIndex}')">+</button>
				</td>
			</tr>`;
			rowIndex++;
		}
	});

	if (totalStokTemp == 0) {
		showList = `<tr><td>No Stok</td></tr>`;
	}

	document.querySelector('#sumber-qty').innerHTML = totalStokTemp;
	document.querySelector('#sumber-roll').innerHTML = totalStokRollTemp;
	
	$(`#qty-table-stok tbody`).html(showList);
	if (tempItem.id == '') {
		document.querySelector('#hasil-qty').innerHTML = '0';
		document.querySelector('#hasil-roll').innerHTML = '0';
		$(`#qty-table-hasil tbody`).html("");
	}

	

}

function ambilStok(index){

	if (brgNewHasil.val() !== '' && warnaNewHasil !== '') {
		
		const row = document.querySelectorAll("#qty-table-stok tbody tr")[index];
		const roll = row.querySelector(".roll");
		const rolVal = parseFloat(roll.innerHTML.toString().trim());
		const qtyVal = parseFloat(row.querySelector(".qty").innerHTML.toString().trim());
		const supplier_id = parseFloat(row.querySelector(".supplier_id").innerHTML.toString().trim());
		let qIdx = qtyVal.toString().replace(".00","");
		qIdx = qIdx.replace(".","_")
	
		if (rolVal > 0 && rolVal != '' && typeof rolVal !== 'undefined') {
			if (typeof tempItem.rekap_sumber[`q-${qIdx}`] === 'undefined') {
				tempItem.rekap_sumber[`q-${qIdx}`] = {
					qty:qtyVal,
					jumlah_roll:1,
					supplier_id:supplier_id
				};
			}else{
				tempItem.rekap_sumber[`q-${qIdx}`].jumlah_roll++;
			}
			const nRoll = rolVal- 1;
			roll.innerHTML = nRoll;
	
			if (tempItem.equal_status == 1) {
				tempItem.rekap_hasil[`q-${qIdx}`] = JSON.parse(JSON.stringify(tempItem.rekap_sumber[`q-${qIdx}`]));
			}
			showRekap();
		}else{
			// console.log('rv',rolVal, typeof rolVal);
		}
	}else{
		bootbox.alert("Mohon isi barang dan keterangan hasil")
	}

}

function unAmbilStok(index){
	
	const row = document.querySelectorAll("#qty-table-stok tbody tr")[index];
	const roll = row.querySelector(".roll");

	const qtyVal = parseFloat(row.querySelector(".qty").innerHTML.toString().trim());
	let qIdx = qtyVal.toString().replace(".00","");
	const qIdSumber = qIdx;
	qIdx = qIdx.replace(".","_")
	let rolVal = parseFloat(roll.innerHTML.toString().trim());
	if (typeof tempItem.rekap_sumber[`q-${qIdx}`] !== 'undefined') {
		if (tempItem.rekap_sumber[`q-${qIdx}`].jumlah_roll > 0) {
			tempItem.rekap_sumber[`q-${qIdx}`].jumlah_roll -= 1;
			rolVal += 1;
			roll.innerHTML = rolVal;
		}
	}

	if (typeof tempItem.rekap_sumber[`q-${qIdx}`] !== 'undefined' && tempItem.rekap_sumber[`q-${qIdx}`].jumlah_roll == 0) {
		delete tempItem.rekap_sumber[`q-${qIdx}`];
		delete tempItem.rekap_hasil[`q-${qIdx}`];
	}else if (tempItem.equal_status == 1) {
		tempItem.rekap_hasil[`q-${qIdx}`] =  JSON.parse(JSON.stringify(tempItem.rekap_sumber[`q-${qIdx}`]));
	}

	const rowSumber = document.querySelector(`#id-${qIdSumber}`);
	
	showRekap();
}

function unAmbilHasil(index){
	
	const row = document.querySelectorAll("#qty-table-hasil tbody tr")[index];
	console.log(row);
	const roll = row.querySelector(".roll");

	const qtyVal = parseFloat(row.querySelector(".qty").innerHTML.toString().trim());

	let qIdx = qtyVal.toString().replace(".00","");
	qIdx = qIdx.replace(".","_")
	let rolVal = parseFloat(roll.innerHTML.toString().trim());
	if (typeof tempItem.rekap_hasil[`q-${qIdx}`] !== 'undefined') {
		if (tempItem.rekap_hasil[`q-${qIdx}`].jumlah_roll > 0) {
			tempItem.rekap_hasil[`q-${qIdx}`].jumlah_roll -= 1;
			rolVal -= 1;
			roll.innerHTML = rolVal;

			if(document.querySelector(`#id-${qIdx}`) === null ){

				const qtyHasil = document.querySelector('#hasil-qty').innerHTML;
				const rollHasil = document.querySelector('#hasil-roll').innerHTML
				totalStokTemp += parseFloat(qtyHasil);
				totalStokRollTemp += parseFloat(rollHasil);
				const nSumber = JSON.stringify(tempItem.rekap_hasil[`q-${qIdx}`]);

				for (let _i = 0; _i < stokList.length; _i++) {
					if (stokList[_i].qty == qIdx) {
						stokList[_i].jumlah_roll = 1;
					}
				}

				let addRow = '';
				
				const text = $(`#qty-table-stok tbody`).html();
				addRow = text;
				const rowCount = document.querySelectorAll(`#qty-table-stok tbody tr`).length;
				console.log(rowCount);
				let rowIndex = rowCount;
				if (text.toString().includes('No Stok')) {
					rowIndex = 0;
					addRow = '';
				};
				
				let sId = tempItem.rekap_hasil[`q-${qIdx}`].supplier_id;
				let sName = tempItem.rekap_hasil[`q-${qIdx}`].nama_supplier;
				let sQty = tempItem.rekap_hasil[`q-${qIdx}`].qty;
				addRow += `<tr id='id-${qIdx}'>
					<td>
						<span class='nama_supplier'>${sName}</span>
						<span class='supplier_id' hidden>${sId}</span>
					</td>
					<td class='text-center qty'>${sQty}</td>
					<td class='text-center roll'>${0}</td>
					<td>
						<button class='btn btn-xs red' id='btn-unget-${rowIndex}' onClick="unAmbilStok('${rowIndex}')">-</button>
						<button class='btn btn-xs green' id='btn-get-${rowIndex}' onClick="ambilStok('${rowIndex}')">+</button>
					</td>
				</tr>`;

				
				$(`#qty-table-stok tbody`).html(addRow);
				// setStokShow();
			}
			const rowStok = document.querySelector(`#id-${qIdx}`);
			const rollStok = rowStok.querySelector(".roll");
			const rollVal = parseInt(rollStok.innerHTML) + 1;
			rollStok.innerHTML = rollVal;
		}
	}

	if (tempItem.rekap_hasil[`q-${qIdx}`].jumlah_roll == 0) {
		delete tempItem.rekap_hasil[`q-${qIdx}`];
		delete tempItem.rekap_sumber[`q-${qIdx}`];
	}else{
		tempItem.rekap_sumber[`q-${qIdx}`] =  JSON.parse(JSON.stringify(tempItem.rekap_hasil[`q-${qIdx}`]));
	}
	
	showRekap();
}

function showRekap(){
	btnAssembly.disabled = true
	let total = 0;
	let totalRoll = 0;
	
	let total_hasil = 0;
	let totalRoll_hasil = 0;
	
	document.querySelector('#sumber-nama').innerHTML = tempItem.nama_sumber;
	document.querySelector('#hasil-nama').innerHTML = tempItem.nama_hasil;

	
	// document.querySelector('#sumber-satuan').innerHTML = tempItem.satuan_sumber;
	// document.querySelector('#hasil-satuan').innerHTML = tempItem.satuan_hasil;
	for(const list in tempItem.rekap_sumber){
		// console.log("list",list, tempItem.rekap_sumber);
		totalRoll += parseFloat(tempItem.rekap_sumber[list].jumlah_roll);
		total += (parseFloat(tempItem.rekap_sumber[list].qty) * tempItem.rekap_sumber[list].jumlah_roll);
	}

	let tbody = '';
	let index = 0;

	if (tempItem.equal_status == 1) {
		for(const list in tempItem.rekap_hasil){
			if (typeof tempItem.rekap_hasil[list] !== 'undefined') {
				totalRoll_hasil += parseFloat(tempItem.rekap_hasil[list].jumlah_roll);
				total_hasil += (parseFloat(tempItem.rekap_hasil[list].qty) * tempItem.rekap_hasil[list].jumlah_roll);
				tbody += `<tr class='id_hasil-${index}'>
					<td></td>
					<td class='text-center qty'>${tempItem.rekap_hasil[list].qty}</td>
					<td class='text-center roll'>${tempItem.rekap_hasil[list].jumlah_roll}</td>
					<td>
						<button class='btn btn-xs red' id='btn-hasil_unget-${index}' onClick="unAmbilHasil('${index}')">-</button>
					</td>
				</tr>`;
				index++;
			}
		}
	
		$(`#qty-table-hasil tbody`).html(tbody);
		document.querySelector('#hasil-qty').innerHTML = total_hasil;
		document.querySelector('#hasil-roll').innerHTML = totalRoll_hasil;

		tempItem.qty_hasil = total;	
		tempItem.jumlah_roll_hasil = totalRoll;

	}

	
	document.querySelector('#sumber-qty-ambil').innerHTML = total;
	document.querySelector('#sumber-roll-ambil').innerHTML = totalRoll;
	const sisa = totalStokTemp - total;
	const sisaRoll = totalStokRollTemp - totalRoll;
	document.querySelector('#sumber-qty').innerHTML = (sisa < 0 ? 0 : sisa);
	document.querySelector('#sumber-roll').innerHTML = (sisaRoll < 0 ? 0 : sisaRoll)
	tempItem.qty_sumber = total;	
	tempItem.jumlah_roll_sumber = totalRoll;	

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
	// if (box.checked == false) {
	// 	bootbox.alert("Fitur No Equal belum tersedia");
	// 	setTimeout(() => {
	// 		box.checked = true;
	// 		$(`#${id}`).uniform.update();
	// 	}, 500);	
	// }
	if (box.checked == false) {
		if (tempItem.id == '') {
			tempItem.rekap_hasil = {};
		}
		inputHasilRow.classList.remove('inactive');
		document.querySelector("#qty-table-hasil tbody").innerHTML = '';
		document.querySelector("#newRowIndex").innerHTML = '1';
		document.querySelector("#inputHasilRow .inputNewQty").value='';
		document.querySelector("#inputHasilRow .inputNewRoll").value='';
	}else{
		inputHasilRow.classList.add('inactive');
		if (tempItem.id == '') {
			tempItem.rekap_hasil =  JSON.parse(JSON.stringify(tempItem.rekap_sumber));
		}
	}
	tempItem.equal_status = (box.checked ? 1 : 0);
	if (tempItem.id == '') {
		showRekap();
	}


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
		if (data=='OK') {
			dialog.find('.bootbox-body').html(`<p class="text-center mb-0">Sukses</p>`);
			setTimeout(() => {
				dialog.modal('hide');
				window.location.reload();
			}, 1000);
		}
	});

	$("#portlet-config-split").modal('toggle');
}



function addNewHasilRow(){
	const tBody = document.querySelector(`#qty-table-hasil tbody`);
	const index = document.querySelectorAll(`#qty-table-hasil tbody tr`).length;
	const qty = document.querySelector("#inputHasilRow .inputNewQty");
	const roll = document.querySelector("#inputHasilRow .inputNewRoll");

	if (qty.value.trim() !== '') {
		const nRow = document.createElement("tr");
		const cells = `<td class='text-center'>${index + 1}</td><td style="padding:0px;" onChange="rekapHasilRow()"><input type="text" class="inputNewQty" value="${qty.value}"></td>
						<td style="padding:0px;" onChange="rekapHasilRow()"><input type="text" class="inputNewRoll" value="${(roll.value.trim() != '' ? roll.value : '1')}"></td>
						<td><button class='btn btn-xs red' onClick="deleteRow('${index}')"><i class='fa fa-times'></i></button></td>`;
		nRow.innerHTML = cells;
		tBody.append(nRow);
		qty.value="";
		roll.value="";
		document.querySelector("#newRowIndex").innerHTML = parseInt(index) + 2;
	}
}

function deleteRow(index){
	const table=  document.querySelector(`#qty-table-hasil tbody`);
	// const row = document.querySelectorAll(`#qty-table-hasil tbody tr`)[index];
	table.deleteRow(index);
	rekapHasilRow();

}

function rekapHasilRow(){
	const allQty = document.querySelectorAll(".inputNewQty");
	const allRow = document.querySelectorAll(".inputNewRoll");

	let tQty = 0;
	let tRoll = 0;

	tempItem.rekap_hasil={};

	allQty.forEach((qty,index) => {

		let qIdx = qty.value.toString().replace(".00","");
		const r = allRow[index].value;
		
		if (qty.value.length > 0) {
			if (typeof tempItem.rekap_hasil[`q-${qty.value}`] === 'undefined') {
				tempItem.rekap_hasil[`q-${qIdx}`] = {
					qty:qty.value,
					jumlah_roll:r,
					supplier_id:0
				};
			}else{
				tempItem.rekap_hasil[`q-${qIdx}`].jumlah_roll++;
			}
			
			tQty += parseFloat(qty.value*r);
			tRoll += (r != '' ? parseInt(r) : (qty.value.length > 0 ? 1 : 0));
			qty.parentElement.parentElement.querySelector("td:first-child").innerHTML = index+1;
		}

	});

	tempItem.qty_hasil = tQty;
	tempItem.jumlah_roll_hasil = tRoll;
	document.querySelector("#hasil-qty").innerHTML=tQty;
	document.querySelector("#hasil-roll").innerHTML=tRoll;

}

//=============================================================================================

function editData(id){
	const dialog = bootbox.dialog({
		message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Checking...</p>`,
		closeButton: false
	});
	tempItem.id = id;
	assemblyList.forEach((list,index) => {
		if (id==list.id) {
			tempItem.tanggal = list.tanggal;
			tempItem.toko_id = list.toko_id;
			tempItem.gudang_id = list.gudang_id;
			tempItem.barang_id_sumber = list.barang_id_sumber;
			tempItem.warna_id_sumber = list.warna_id_sumber;
			tempItem.barang_id_hasil = list.barang_id_hasil;
			tempItem.warna_id_hasil = list.warna_id_hasil;
			tempItem.nama_sumber='';
			tempItem.satuan_sumber='';
			tempItem.satuan_id_sumber='';
			tempItem.nama_hasil='';
			tempItem.satuan_hasil='';
			tempItem.satuan_id_hasil='';
			tempItem.equal_status=list.equal_status;
			tempItem.qty_sumber= list.qty_sumber;
			tempItem.jumlah_roll_sumber= list.jumlah_roll_sumber;
			tempItem.qty_hasil= list.qty_hasil;
			tempItem.jumlah_roll_hasil= list.jumlah_roll_hasil;

			const tgl = list.tanggal.split('-').reverse().join('/');
			addTanggal.val(tgl);
			tokoNew.val(tempItem.toko_id).change();
			gudangNew.val(tempItem.gudang_id).change();
			brgNewSumber.val(tempItem.barang_id_sumber+'-'+tempItem.warna_id_sumber).change();
			brgNewHasil.val(tempItem.barang_id_hasil).change();
			warnaNewHasil.val(tempItem.warna_id_hasil).change();
			brgNewSumber.prop('disabled',true);
			brgNewHasil.prop('disabled',true);
			warnaNewHasil.prop('disabled',true);
			tokoNew.prop('disabled',true);
			gudangNew.prop('disabled',true);

			// addTanggal.val(`${tgl}`);
			// $("#newTanggal").val();
			// console.log(tgl);

			setTimeout(() => {
				dialog.modal('hide');
				console.log(typeof list.rekap_sumber);
				const rS = JSON.parse(list.rekap_sumber);
				const nRS = {};
				rS.forEach(item => {
					nRS[`q-${item.qty}`] = item;
				});
				tempItem.rekap_sumber = nRS;
	
				const rH = JSON.parse(list.rekap_hasil);
				const nRH = {};
				rH.forEach(item => {
					nRH[`q-${item.qty}`] = item;
				});
				tempItem.rekap_hasil = nRH;

				getStok();
				showRekap();
				
				if (list.equal_status == 1) {
					$('#equal_new').prop("checked", true);
				}else{
					$('#equal_new').prop("checked", false);
				}
				
				setEqual('equal_new');
				
				$(`#equal_new`).uniform.update();

				if (list.equal_status == 0) {

					const tBody = document.querySelector(`#qty-table-hasil tbody`);
					const index = document.querySelectorAll(`#qty-table-hasil tbody tr`).length;
					// const qty = document.querySelector("#inputHasilRow .inputNewQty");
					// const roll = document.querySelector("#inputHasilRow .inputNewRoll");

					for(const list in tempItem.rekap_hasil){
						if (typeof tempItem.rekap_hasil[list] !== 'undefined') {
							const nRow = document.createElement("tr");
							const qty = tempItem.rekap_hasil[list].qty;
							const roll = tempItem.rekap_hasil[list].jumlah_roll;
							const cells = `<td class='text-center'>${index + 1}</td><td style="padding:0px;" onChange="rekapHasilRow()"><input type="text" class="inputNewQty" value="${qty}"></td>
											<td style="padding:0px;" onChange="rekapHasilRow()"><input type="text" class="inputNewRoll" value="${roll}"></td>
											<td><button class='btn btn-xs red' onClick="deleteRow('${index}')"><i class='fa fa-times'></i></button></td>`;
							nRow.innerHTML = cells;
							tBody.append(nRow);
							qty.value="";
							roll.value="";
							document.querySelector("#newRowIndex").innerHTML = parseInt(index) + 2;
						}
					}

					rekapHasilRow();
				}
			}, 1000);

			// tempItem.rekap_sumber= nRH;
			// console.log('smbr',JSON.parse(list.rekap_sumber));
		}
	});

	
	
	$("#portlet-config-split").modal('toggle');
}

function deleteData(id){

	bootbox.confirm("Yakin hapus daftar ini dari assembly ? ", function(respond){
		if (respond) {
			const dialog = bootbox.dialog({
				message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Delete...</p>`,
				closeButton: false
			});

			fetch(baseurl+`inventory/assembly_list_remove?id=${id}`)
			.then((response) => response.json())
			.then((data) => {
				if (data=='OK') {
					dialog.find('.bootbox-body').html(`<p class="text-center mb-0">Data deleted</p>`);
					setTimeout(() => {
						dialog.modal('hide');
						$(`#baris-${id}`).remove();
					}, 1000);
				}
			});
		}
	})
}

</script>
