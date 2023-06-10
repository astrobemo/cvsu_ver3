<style type="text/css">
    table tr td, table tr th{
        border: 1px solid #999;
        padding:2px;
    }

    .first-row{
        border-top: 2px solid #333 !important;
    }
    
    table{
        border-collapse: collapse;
    }

    table thead th{
        position: -webkit-sticky;
        position: sticky;
        background: #fff;
        top: 50px;
        z-index: 99;
        border-bottom: 2px solid #ddd;
        background: #eee;

        -webkit-full-screen{
            top: 0px;
        }
    }

    table thead tr:first-child th{
        top: 50px;
    }
    table thead tr:nth-child(2) th{
        top: 65px;
    }
</style>
<? foreach ($this->gudang_list_aktif as $list) {
			$idx = 1;?>
			<h3><?=$list->nama;?></h3>
			<table>
                <thead>
                    <tr>
                        <th rowspan=''>NO</th>
                        <th rowspan=''>Tanggal<br/>SO</th>
                        <th rowspan=''>Toko</th>
                        <th rowspan=''>Gudang</th>
                        <th rowspan=''>Nama Beli</th>
                        <th rowspan=''>Nama Jual</th>
                        <th rowspan=''>Harga Eceran</th>
                        <th rowspan=''>Harga Jual</th>
                        <th rowspan=''>Harga Beli</th>
                        <th rowspan=''>Nama <br/>Keterangan</th>
                        <th rowspan=''>Supplier</th>
                        <th rowspan=''>Qty Besar</th>
                        <th rowspan=''>Satuan Besar</th>
                        <th rowspan=''>Qty Kecil</th>
                        <th rowspan=''>Satuan Kecil</th>
                        <th rowspan=''>Qty Eceran</th>
                        <th rowspan=''>Satuan Eceran</th>
                        <th rowspan=''>Rincian Qty Kecil</th>
                    </tr>
                </thead>
                <tbody>

                    <?foreach ($data_get as $row) {
                        unset($qty_list);
                        $qty_list=array();
                        $qty_idx=0;
                        $baris = (ceil($row->total_roll/10));
                        $qty = explode('??', $row->qty);
                        $roll = explode('??', $row->jumlah_roll);
                        foreach ($qty as $key => $value) {
                            for ($i=0; $i < $roll[$key] ; $i++) { 
                                $qty_list[$qty_idx] = $value;
                                $qty_idx++;
                            }
                        }
                        
                        for ($j=0; $j < $baris; $j++) { 
                            if ($j==0) {?>
                                <tr>
                                    <!-- no -->
                                    <td class="first-row" ><?=$idx;?></td>
                                    <!-- Tanggal -->
                                    <td><?=is_reverse_date($tanggal)?></td>
                                    <td><?=$row->nama_toko;?></td>
                                    <td><?=$row->nama_gudang;?></td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_barang;?></td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->warna_jual;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->harga_eceran;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->harga_jual;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->harga_beli;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_supplier;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->qty_besar;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_satuan_besar;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->qty_detail;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_satuan_detail;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->qty_eceran;?> </td>
                                    <td class="" rowspan='<?=$baris;?>'><?=$row->nama_satuan_eceran;?> </td>
                                    <?for ($i=0; $i < 10 ; $i++) {;?> 
                                        <td><?=(isset($qty_list[$i]) ? (float)$qty_list[$i] : '' );?></td>
                                    <?};?>
                                </tr>
                            <?}else{?>
                                <tr>
                                    <?for ($i=0; $i < 10 ; $i++) { 
                                        $idx_now = ($j*10) + $i;?>
                                        <td><?=(isset($qty_list[$idx_now]) ? (float)$qty_list[$idx_now] : '' )?></td>
                                    <?}?>
                                </tr>
                            <?}
                        }
                        $idx++;
                    }?>
                </tbody>
			</table>
		<?}?>