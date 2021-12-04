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
                        <th rowspan='2'>NO</th>
                        <th rowspan='2'>index<br/>barang</th>
                        <th rowspan='2'>index<br/>keterangan</th>
                        <th rowspan='2'>Nama</th>
                        <th rowspan='2'>Ket</th>
                        <th colspan='10'>Sat. Kecil</th>
                        <th rowspan='2'>Sat. Besar</th>
                        <th rowspan='2'>TOTAL</th>
                    </tr>
                    <tr> 
                    <?for ($i=1; $i <= 10 ; $i++) {?>
                        <th><?=$i?></th>
                    <?}?>
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
                                    <td class="first-row" rowspan='<?=$baris;?>'><?=$idx;?></td>
                                    <td class="first-row" rowspan='<?=$baris;?>'><?=$row->barang_id;?></td>
                                    <td class="first-row" rowspan='<?=$baris;?>'><?=$row->warna_id;?></td>
                                    <td class="first-row" rowspan='<?=$baris;?>'><?=$row->nama_barang;?></td>
                                    <td class="first-row" rowspan='<?=$baris;?>'><?=$row->warna_jual;?> </td>
                                        <?for ($i=0; $i < 10 ; $i++) {;?> 
                                            <td class="first-row"><?=(isset($qty_list[$i]) ? (float)$qty_list[$i] : '' );?></td>
                                        <?};?>
                                    <td class="first-row" rowspan='<?=$baris;?>'><?=$row->total_roll;?></td>
                                    <td class="first-row" rowspan='<?=$baris;?>'><?=(float)$row->total_qty;?></td>
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