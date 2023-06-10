<?php echo link_tag('assets_noondev/css/google-font-open-sans-400-300-600-700.css'); ?>
<?php echo link_tag('assets/global/plugins/font-awesome/css/font-awesome.min.css'); ?>
<?php echo link_tag('assets/global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>


<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style>
    .item-list, header{
        display:flex;
        width:100%;
    }

    .item-list:nth-child(2n){
        background-color:#eee;
    }

    .item-list div{
        padding-top:5px;
        padding-bottom:10px;
        font-size:14px;
        /* flex:1; */
    }

    .col-barang{
        flex:3;
        text-align:left
    }

    .col-gudang{
        flex:1.5;
    }

    .col-qbesar{
        flex:1;
    }

    .col-qkecil{
        flex:1;
    }

    .col-harga{
        flex:1.5;
    }

    .col-total{
        flex:2;
    }

    .col-ppn{
        flex:1;
    }

    .col-btn{
        text-align:center;
        flex:1.5;
    }

</style>

<?
foreach ($penjualan_data as $row) {
    $tipe_penjualan = $row->tipe_penjualan;
    $penjualan_id = $row->id;
    $customer_id = $row->customer_id;
    $nama_customer = $row->nama_keterangan;
    $alamat_customer = $row->alamat_keterangan;
    $npwp_customer = $row->npwp_customer;
    $gudang_id = $row->gudang_id;
    $nama_gudang = $row->nama_gudang;
    $no_faktur = $row->no_faktur;
    $penjualan_type_id = $row->penjualan_type_id; 
    $po_number = $row->po_number;
    $fp_status = $row->fp_status;
    
    $tanggal_print = date('d F Y', strtotime($row->tanggal));

    $tanggal = is_reverse_date($row->tanggal);
    $ori_tanggal = $row->tanggal;
    $status_cek = 0;
    if ($penjualan_type_id == 2) {
        $dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
        if ($row->jatuh_tempo == $row->tanggal) {
            $status_cek = 1;
        }
    }
    $get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
    // print_r($get_jt);
    $jatuh_tempo = is_reverse_date($get_jt);
    $ori_jatuh_tempo = $row->jatuh_tempo;
    $status = $row->status;
    
    $diskon = $row->diskon;
    $ongkos_kirim = $row->ongkos_kirim;
    $status_aktif = $row->status_aktif;
    $nama_keterangan = $row->nama_keterangan;
    $alamat_keterangan = $row->alamat_keterangan;
    $kota = $row->kota;
    $keterangan = $row->keterangan;
    $customer_id = $row->customer_id;
    $no_faktur_lengkap = $row->no_faktur_lengkap;
    $revisi = $row->revisi - 1;
    $no_surat_jalan = $row->no_surat_jalan;
}

$nama_bank = '';
$no_rek_bank = '';
$tanggal_giro = '';
$jatuh_tempo_giro = '';
$no_akun = '';

foreach ($data_giro as $row) {
    $nama_bank = $row->nama_bank;
    $no_rek_bank = $row->no_rek_bank;
    $tanggal_giro =is_reverse_date($row->tanggal_giro) ;
    $jatuh_tempo_giro = is_reverse_date($row->jatuh_tempo);
    $no_akun = $row->no_akun;
}

if ($status != 1) {
    if ( is_posisi_id() != 1 ) {
        $readonly = 'readonly';
    }
}

if ($penjualan_id == '') {
    $disabled = 'disabled';
}

if ($status != 0) {
    $disabled_status = 'disabled';
}

$lock_ = '';
$read_ = '';
if (is_posisi_id() == 6) {
    $disabled = 'disabled';
    $readonly = 'readonly';
}

$ary_filter = array("\n","\r", "<br>");
$alamat_keterangan = str_replace($ary_filter," ",$alamat_keterangan);

$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,46);
   $alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
$last_1 = substr($alamat1, -1,1);
$last_2 = substr($alamat2, 0,1);

$positions = array();
$pos = -1;
while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
    $positions[] = $pos;
}

$max = 47;
if ($last_1 != '' && $last_2 != '') {
    $posisi =array_filter(array_reverse($positions),
        function($value) use ($max) {
            return $value <= $max;
        });

    $posisi = array_values($posisi);

    $alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
       $alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
}

$keterangan1 = substr(strtoupper(trim($keterangan)), 0,47);
   $keterangan2 = substr(strtoupper(trim($keterangan)), 47);
$last_ket1 = substr($keterangan1, -1,1);
$last_ket2 = substr($keterangan2, 0,1);

$positions = array();
$pos = -1;
while (($pos = strpos(trim($keterangan)," ", $pos+1 )) !== false) {
    $positions[] = $pos;
}

$max = 47;
if ($last_ket1 != '' && $last_ket2 != '') {
    $posisi_ket =array_filter(array_reverse($positions),
        function($value) use ($max) {
            return $value <= $max;
        });

    $posisi_ket = array_values($posisi_ket);

    $keterangan1 = substr(strtoupper(trim($keterangan)), 0,$posisi_ket[0]);
       $keterangan2 = substr(strtoupper(trim($keterangan)), $posisi_ket[0]);
}
?>

<div class="container">
    <div class="row mb-3 mt-5">
        <div class="col-12 col-md-12 text-right">
            <?if (is_posisi_id() != 6) { ?>
                <a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail');?>" target='_blank' class="btn btn-light btn-sm">
                <i class="fa fa-files-o"></i> Tab Kosong Baru </a>
                <a href="#portlet-config" data-toggle='modal' class="btn btn-light btn-sm btn-form-add">
                <i class="fa fa-plus"></i> Penjualan Baru </a>
            <?}?>
            <a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-light btn-sm btn-form-add">
            <i class="fa fa-search"></i> Cari Faktur </a>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-2 pt-4 px-4" style="background:lightblue; color:white; border-top-left-radius:5px">
            <h1>test</h1>
        </div>
        <div class="col-12 col-md-10 pt-4" style="background:#B8F1B0; border-top-right-radius:10px; display:flex">
            <h1 style='flex:1'>content</h1>
            <div style='flex:1; text-align:right'>
                <button class="btn btn-sm btn-success" style='height:40px; width:40px;border-radius:50%'><i class='fa fa-plus'></i></button>
            </div>
        </div>

        
        <div class="col-12 col-md-2" style="background:lightblue">
        </div>
        <div class="col-12 col-md-10 pt-2" style="background:#eff">

            <header class="py-2 px-2">
                <div class="col-barang">Barang</div>
                <div class="col-gudang">Gudang</div>
                <div class="col-qbesar">Q.Besar</div>
                <div class="col-qkecil">Q.Kecil</div>
                <div class="col-harga">Harga</div>
                <div class="col-total">Total</div>
                <div class="col-ppn">PPN</div>
                <div class="col-btn"></div>
            </header>

            <div class="item-list px-2">
                <div class="col-barang">ADRIANO F,GREEN</div>
                <div class="col-gudang">Gudang O</div>
                <div class="col-qbesar">112 Meter</div>
                <div class="col-qkecil">2 Roll</div>
                <div class="col-harga">31,818.18</div>
                <div class="col-total">3,563,636.36</div>
                <div class="col-ppn">356,363.64</div>
                <div class="col-btn">
                    <button class="btn btn-sm btn-outline-primary"><i class='fa fa-edit'></i></button>
                    <button class="btn btn-sm btn-outline-danger"><i class='fa fa-times'></i></button>
                </div>
            </div>
                
            <div class="item-list px-2">
                <div class="col-barang">ADRIANO F,GREEN</div>
                <div class="col-gudang">Gudang O</div>
                <div class="col-qbesar">112 Meter</div>
                <div class="col-qkecil">2 Roll</div>
                <div class="col-harga">31,818.18</div>
                <div class="col-total">3,563,636.36</div>
                <div class="col-ppn">356,363.64</div>
                <div class="col-btn">
                    <button class="btn btn-sm btn-outline-primary"><i class='fa fa-edit'></i></button>
                    <button class="btn btn-sm btn-outline-danger"><i class='fa fa-times'></i></button>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>




<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script>
</script>
