<?php

class Stok_general_model extends CI_Model {

    function get_qty_stok_by_barang_detail($toko_id, $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_a.toko_id, tbl_a.supplier_id as supplier_id, qty, 
            sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll,
            tbl_e.nama as nama_supplier, tbl_d.nama as nama_toko, tbl_f.nama as nama_packaging, tbl_g.nama as nama_satuan
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, 
                        sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'a' as tipe, t2.id as id, 
                        toko_id, supplier_id
				        FROM (
				        	SELECT a.id, pembelian_id, barang_id, warna_id, b.qty, b.jumlah_roll
				        	FROM (
					        	SELECT *
					        	FROM nd_pembelian_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
				        		)a
							LEFT JOIN nd_pembelian_qty_detail b
							ON b.pembelian_detail_id = a.id
				        	) t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
                            WHERE ( 
                                tanggal_sj >= '$tanggal_awal'
					        	OR tanggal >= '$tanggal_awal' 
                                )  
				        	AND status_aktif = 1
				        	AND gudang_id = $gudang_id
				        	) t2
				        ON t1.pembelian_id = t2.id
				        WHERE t2.id is not null
			        	AND gudang_id = $gudang_id
				        GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        0, sum(jumlah_roll) as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'j' as tipe, t2.id as id, 
                        toko_id, supplier_id
				        FROM (
				        	SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id, 
                            toko_id, supplier_id
				        	FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
				        		)a
							LEFT JOIN nd_penjualan_qty_detail b
							ON b.penjualan_detail_id = a.id
							WHERE gudang_id = $gudang_id
							AND stok_eceran_qty_id is null
				        	) t1
				        LEFT JOIN (
				        	SELECT id, tanggal, no_faktur
				        	FROM nd_penjualan
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	) t2
				        ON t1.penjualan_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps0' as tipe, id, 
                        toko_id, supplier_id
			        	FROM (
							SELECT id, tanggal, barang_id, warna_id, gudang_id, keterangan, 
                            toko_id, supplier_id
							FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 0
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							) a
						LEFT JOIN (
							SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat(qty,'??', jumlah_roll,'??', id) SEPARATOR '--') as data_qty, penyesuaian_stok_id
							FROM nd_penyesuaian_stok_qty
							GROUP BY qty, penyesuaian_stok_id
							) t1
						ON a.id = t1.penyesuaian_stok_id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps1' as tipe, id, 
                        toko_id, supplier_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
				        AND gudang_id = $gudang_id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,
                        0, sum(jumlah_roll), 
                        tanggal, keterangan, 'ps2' as tipe, id, 
                        toko_id, supplier_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 2
				        AND gudang_id = $gudang_id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,
                        0, sum(jumlah_roll),  
                        tanggal, keterangan, 'ec1' as tipe, t1.id, 
                        toko_id, t2.supplier_id
			        	FROM (
							SELECT *
                            FROM nd_mutasi_stok_eceran
                            WHERE tanggal >= '$tanggal_awal'
                            AND barang_id = $barang_id
                            AND warna_id = $warna_id
                            AND gudang_id = $gudang_id
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT qty, sum(jumlah_roll) as jumlah_roll, mutasi_stok_eceran_id, supplier_id
                            FROM nd_mutasi_stok_eceran_qty
                            GROUP BY qty, mutasi_stok_eceran_id, supplier_id
                        )t2
                        ON t2.mutasi_stok_eceran_id = t1.id
                        GROUP BY qty, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, 
                        sum(jumlah_roll), 0,
						tanggal, 'so', 'so1', t1.id, 
                        toko_id, supplier_id
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id, supplier_id
                            FROM nd_stok_opname_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND stok_opname_id = $stok_opname_id
							GROUP BY qty, barang_id, warna_id, gudang_id, stok_opname_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						(qty), 0, sum(jumlah_roll),
						tanggal, concat('Diambil untuk <b>',nama_barang,'</b>'), 'ask',
						t1.id, toko_id, supplier_id
				        FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_awal'
								AND gudang_id = $gudang_id 
								AND barang_id_sumber = $barang_id
								AND warna_id_sumber = $warna_id
							)tA
							LEFT JOIN nd_barang as b2
							ON tA.barang_id_hasil = b2.id
							LEFT JOIN nd_warna as w2
							ON tA.warna_id_hasil = w2.id

                        ) t1
                        LEFT JOIN nd_assembly_detail_sumber t2
                        ON t2.assembly_id = t1.id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						(qty), sum(jumlah_roll), 0, 
						tanggal, concat('Diambil dari <b>',nama_barang,'</b>'), 'asm',
						t1.id, toko_id, supplier_id
				        FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_awal'
								AND gudang_id = $gudang_id 
								AND barang_id_hasil = $barang_id
								AND warna_id_hasil = $warna_id
							)tA
							LEFT JOIN nd_barang as b1
							ON tA.barang_id_sumber = b1.id
							LEFT JOIN nd_warna as w1
							ON tA.warna_id_sumber = w1.id
                        ) t1
                        LEFT JOIN nd_assembly_detail_sumber t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY qty, toko_id, supplier_id
                        
				    )UNION(
                        SELECT barang_id, warna_id, gudang_id_before as gudang_id, t2.qty ,
                        0, sum(t2.jumlah_roll),  
                        tanggal, '', 'mb1' as tipe, t1.id, 
                        toko_id, t2.supplier_id
			        	FROM (
							SELECT *
                            FROM nd_mutasi_barang
                            WHERE tanggal >= '$tanggal_awal'
                            AND barang_id = $barang_id
                            AND warna_id = $warna_id
                            AND gudang_id_before = $gudang_id
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT qty, sum(jumlah_roll) as jumlah_roll, mutasi_barang_id, supplier_id
                            FROM nd_mutasi_barang_detail
                            GROUP BY qty, mutasi_barang_id, supplier_id
                        )t2
                        ON t2.mutasi_barang_id = t1.id
                        GROUP BY qty, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id_before as gudang_id, t2.qty ,
                        sum(t2.jumlah_roll), 0,
                        tanggal, '', 'mb1' as tipe, t1.id, 
                        toko_id, t2.supplier_id
			        	FROM (
							SELECT *
                            FROM nd_mutasi_barang
                            WHERE tanggal >= '$tanggal_awal'
                            AND barang_id = $barang_id
                            AND warna_id = $warna_id
                            AND gudang_id_after = $gudang_id
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT qty, sum(jumlah_roll) as jumlah_roll, mutasi_barang_id, supplier_id
                            FROM nd_mutasi_barang_detail
                            GROUP BY qty, mutasi_barang_id, supplier_id
                        )t2
                        ON t2.mutasi_barang_id = t1.id
                        GROUP BY qty, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll) as jumlah_roll_masuk, 0,  
                        tanggal, no_faktur, 'rj' as tipe, t2.id as id, 
                        toko_id, supplier_id
				        FROM (
				        	SELECT a.id, retur_jual_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id, 
                            toko_id, supplier_id
				        	FROM (
					        	SELECT *
					        	FROM nd_retur_jual_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
                                AND gudang_id = $gudang_id
				        		)a
							LEFT JOIN nd_retur_jual_qty b
							ON b.retur_jual_detail_id = a.id
				        	) t1
				        LEFT JOIN (
				        	SELECT id, tanggal, no_faktur
				        	FROM nd_retur_jual
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	) t2
				        ON t1.retur_jual_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY qty, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, qty , 
                        0, sum(jumlah_roll) as jumlah_roll_masuk,   
                        tanggal, no_faktur, 'rj' as tipe, t2.id as id, 
                        toko_id, supplier_id
				        FROM (
				        	SELECT a.id, retur_beli_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id
				        	FROM (
					        	SELECT *
					        	FROM nd_retur_beli_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
                                AND gudang_id = $gudang_id
				        		)a
							LEFT JOIN nd_retur_beli_qty b
							ON b.retur_beli_detail_id = a.id
				        	) t1
				        LEFT JOIN (
				        	SELECT id, tanggal, no_faktur, toko_id, supplier_id
				        	FROM nd_retur_beli
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	) t2
				        ON t1.retur_beli_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY qty, toko_id, supplier_id
                    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
                LEFT JOIN nd_toko tbl_d
                ON tbl_a.toko_id = tbl_d.id
                LEFT JOIN nd_supplier tbl_e
                ON tbl_a.supplier_id = tbl_e.id
                LEFT JOIN nd_satuan tbl_f
                ON tbl_b.packaging_id = tbl_f.id
                LEFT JOIN nd_satuan tbl_g
                ON tbl_b.satuan_id = tbl_g.id
				Where tbl_a.toko_id = $toko_id
                AND barang_id is not null
                AND qty is not null
				GROUP BY qty, toko_id, supplier_id
				ORDER BY qty asc
            ");
		
		return $query;
		// return $this->db->last_query();
	}

    function get_barang_header($id){
        $query = $this->db->query("SELECT tbl_b.*, tbl_f.nama as nama_packaging, tbl_g.nama as nama_satuan
				FROM (
                    SELECT *
                    FROM nd_barang
                    WHERE id='$id'
                    ) tbl_b
                LEFT JOIN nd_satuan tbl_f
                ON tbl_b.packaging_id = tbl_f.id
                LEFT JOIN nd_satuan tbl_g
                ON tbl_b.satuan_id = tbl_g.id
				");
		
		return $query->result();
    }
    
    function get_stok_barang_detail($barang_id, $warna_id, $tanggal_end, $tanggal_awal){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT tbl_g.nama as nama_gudang, tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, 
        tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual,
        harga_ecer, harga_jual, harga_beli, tbl_h.nama as nama_supplier, tbl_i.nama as nama_toko,
        tbl_e.nama as nama_satuan_besar, tbl_d.nama as nama_satuan_kecil, tbl_f.nama as nama_satuan_eceran,
        tbl_a.barang_id, tbl_a.warna_id, tbl_b.status_aktif as status_barang, 
        tbl_d.nama as nama_satuan, satuan_id, tbl_e.nama as nama_packaging, packaging_id,
        tbl_a.toko_id, tbl_a.supplier_id, tbl_a.gudang_id
        $select
        FROM(
            (
                    SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 1 as tipe, toko_id, ifnull(supplier_id,0) as supplier_id
                    FROM (
                        SELECT t2.qty as qty, t2.jumlah_roll, id, barang_id, warna_id, pembelian_id
                        FROM nd_pembelian_detail t1
                        LEFT JOIN (
                            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll, 1)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
                            FROM nd_pembelian_qty_detail
                            GROUP BY pembelian_detail_id
                            ) t2
                        ON t2.pembelian_detail_id = t1.id
                        ORDER BY pembelian_id
                    ) nd_pembelian_detail
                    LEFT JOIN (
                        SELECT *
                        FROM nd_pembelian
                        WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal_end'
                        AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
                        AND status_aktif = 1
                        ) nd_pembelian
                    ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
                    WHERE nd_pembelian.id is not null
                    GROUP BY barang_id, warna_id, nd_pembelian.gudang_id, tanggal
            )UNION(
                SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 2, toko_id, ifnull(supplier_id,0)
                FROM nd_mutasi_barang
                WHERE tanggal <= '$tanggal_end'
                AND tanggal >= '$tanggal_awal'
                AND status_aktif = 1
                GROUP BY barang_id, warna_id, gudang_id_after, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 3, t1.toko_id, ifnull(supplier_id,0)
                FROM nd_penjualan_detail t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_penjualan
                    WHERE tanggal <= '$tanggal_end'
                    AND tanggal >= '$tanggal_awal'
                    AND status_aktif = 1
                    ) t2
                ON t1.penjualan_id = t2.id
                LEFT JOIN (
                    SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id, supplier_id
                    FROM nd_penjualan_qty_detail
                    GROUP BY penjualan_detail_id, supplier_id
                    ) t3
                ON t3.penjualan_detail_id = t1.id
                where t2.id is not null
                AND is_eceran = 0
                GROUP BY barang_id, warna_id, t1.gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 4, toko_id, ifnull(supplier_id,0)
                FROM nd_retur_jual_detail
                LEFT JOIN (
                    SELECT *
                    FROM nd_retur_jual
                    WHERE tanggal <= '$tanggal_end'
                    AND tanggal >= '$tanggal_awal'
                    AND status_aktif = 1
                    ) nd_retur_jual
                ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
                LEFT JOIN (
                    SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                    FROM nd_retur_jual_qty
                    GROUP BY retur_jual_detail_id
                    ) nd_penjualan_qty_detail
                ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
                WHERE nd_retur_jual.id is not null
                GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 5, toko_id, ifnull(supplier_id,0)
                    FROM (
                        SELECT barang_id, warna_id, keterangan, id, gudang_id, tanggal, toko_id, supplier_id
                        FROM nd_penyesuaian_stok
                        WHERE tipe_transaksi = 0
                        AND tanggal <= '$tanggal_end'
                        AND tanggal >= '$tanggal_awal'
                    )t1
                    LEFT JOIN (
                        SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
                        FROM nd_penyesuaian_stok_qty
                        GROUP BY penyesuaian_stok_id
                        ) t2
                    ON t2.penyesuaian_stok_id = t1.id
                    GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 6, toko_id, ifnull(supplier_id,0)
                FROM nd_penyesuaian_stok
                WHERE tanggal <= '$tanggal_end'
                AND tanggal >= '$tanggal_awal'
                AND tipe_transaksi = 1
                GROUP BY barang_id, warna_id, gudang_id, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 7, toko_id, ifnull(supplier_id,0)
                FROM nd_penyesuaian_stok
                WHERE tanggal <= '$tanggal_end'
                AND tanggal >= '$tanggal_awal'
                AND tipe_transaksi = 2
                GROUP BY barang_id, warna_id, gudang_id, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 8, toko_id, ifnull(supplier_id,0)
                FROM nd_mutasi_barang
                WHERE tanggal <= '$tanggal_end'	
                AND tanggal >= '$tanggal_awal'
                AND status_aktif = 1
                GROUP BY barang_id, warna_id, gudang_id_before, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, 
                sum(qty) as qty_masuk, sum(jumlah_roll), 0, 0,
                tanggal, 10, toko_id, ifnull(supplier_id,0)
                FROM (
                    SELECT id, barang_id, warna_id, gudang_id, group_concat(qty) as qty_data, 
                        sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id, supplier_id
                    FROM nd_stok_opname_detail
                       GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id, supplier_id
                ) t1
                LEFT JOIN nd_stok_opname t2
                ON t1.stok_opname_id = t2.id
                GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, 
                0, 0, sum(qty), sum(jumlah_roll),
                tanggal, 11, toko_id, ifnull(supplier_id,0)
                FROM (
                    SELECT *
                    FROM nd_mutasi_stok_eceran
                    WHERE tanggal <= '$tanggal_end'	
                    AND tanggal >= '$tanggal_awal'
                ) t1
                LEFT JOIN nd_mutasi_stok_eceran_qty t2
                ON t2.mutasi_stok_eceran_id = t1.id
                GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id, supplier_id
            )
        ) tbl_a
        LEFT JOIN (
            SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_stok, toko_id, supplier_id
            FROM (
                SELECT barang_id, warna_id, gudang_id, stok_opname_id, toko_id, ifnull(supplier_id,0) as supplier_id
                FROM nd_stok_opname_detail
                GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id, supplier_id
            )t1
            LEFT JOIN nd_stok_opname t2
            ON t1.stok_opname_id = t2.id
            GROUP BY barang_id, warna_id, gudang_id, toko_id, supplier_id
        ) t_stok
        ON tbl_a.barang_id = t_stok.barang_id
        AND tbl_a.warna_id = t_stok.warna_id
        AND tbl_a.gudang_id = t_stok.gudang_id
        AND tbl_a.toko_id = t_stok.toko_id
        AND tbl_a.supplier_id = t_stok.supplier_id
        LEFT JOIN nd_barang tbl_b
        ON tbl_a.barang_id = tbl_b.id
        LEFT JOIN nd_warna tbl_c
        ON tbl_a.warna_id = tbl_c.id
        LEFT JOIN nd_satuan tbl_d
        ON tbl_b.satuan_id = tbl_d.id
        LEFT JOIN nd_satuan tbl_e
        ON tbl_b.packaging_id = tbl_e.id
        LEFT JOIN nd_satuan tbl_f
        ON tbl_b.satuan_eceran_id = tbl_f.id
        LEFT JOIN nd_gudang tbl_g
        ON tbl_a.gudang_id = tbl_g.id
        LEFT JOIN nd_supplier tbl_h
        ON tbl_a.supplier_id = tbl_h.id
        LEFT JOIN nd_toko tbl_i
        ON tbl_a.toko_id = tbl_i.id
        Where tbl_a.barang_id is not null
        GROUP BY tbl_a.barang_id, tbl_a.warna_id, toko_id, supplier_id
        ORDER BY nama_jual, warna_jual

				");
		
			return $query->result();
		// return $this->db->last_query();
	}

    function get_qty_stok_by_barang_detail_eceran($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $penjualan_detail_id){
		$query = $this->db->query("SELECT tA.stok_eceran_qty_id, tA.qty - ifnull(tB.qty,0) as qty, tA.tipe, ifnull(tC.qty,0) as qty_jual, 
        ifnull(tC.id,0) as penjualan_qty_detail_id, tA.supplier_id, nd_supplier.nama as nama_supplier
				FROM (
					(
						SELECT barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 1 as tipe, gudang_id, t2.supplier_id
						FROM (
							SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal >= '$tanggal_awal'
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
							AND status_aktif = 1
						)t1
						LEFT JOIN nd_mutasi_stok_eceran_qty t2
						ON t2.mutasi_stok_eceran_id = t1.id
					)UNION(
						SELECT barang_id, warna_id, id as stok_eceran_qty_id, qty, 2 , gudang_id, supplier_id
						FROM nd_stok_opname_eceran
						WHERE barang_id = $barang_id
						AND warna_id = $warna_id
						AND gudang_id = $gudang_id
						AND stok_opname_id = $stok_opname_id
					)
				)tA
				LEFT JOIN (
					SELECT stok_eceran_qty_id, sum(qty) as qty, eceran_source
					FROM (
						SELECT *
						FROM nd_penjualan_qty_detail
						WHERE stok_eceran_qty_id is not null
					)t1
						LEFT JOIN nd_penjualan_detail t2
						ON t1.penjualan_detail_id=t2.id
						LEFT JOIN nd_penjualan t3
						ON t2.penjualan_id=t3.id
						WHERE status_aktif=1
						AND t2.id != $penjualan_detail_id
						GROUP BY stok_eceran_qty_id, eceran_source

				)tB
				ON tA.stok_eceran_qty_id = tB.stok_eceran_qty_id
				AND tA.tipe = tB.eceran_source
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan_qty_detail
					WHERE penjualan_detail_id = $penjualan_detail_id
				) tC
				ON tA.stok_eceran_qty_id = tC.stok_eceran_qty_id
				AND tA.tipe = tC.eceran_source
                LEFT JOIN nd_supplier
                ON tA.supplier_id = nd_supplier.id
				WHERE barang_id is not null
				AND warna_id is not null
				AND tA.qty - ifnull(tB.qty,0) > 0
				");
		
		return $query;
		// return $this->db->last_query();
	}

    function get_stok_barang_list_2($select, $tanggal_end, $tanggal_awal){
		$query = $this->db->query("SELECT tbl_g.nama as nama_gudang, tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, 
        tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual,
        harga_ecer, harga_jual, harga_beli, tbl_h.nama as nama_supplier, tbl_i.nama as nama_toko,
        tbl_e.nama as nama_satuan_besar, tbl_d.nama as nama_satuan_kecil, tbl_f.nama as nama_satuan_eceran,
        tbl_a.barang_id, tbl_a.warna_id, tbl_b.status_aktif as status_barang, 
        tbl_d.nama as nama_satuan, satuan_id, tbl_e.nama as nama_packaging, packaging_id,
        tbl_a.toko_id, tbl_a.supplier_id, tbl_a.gudang_id
        $select
        FROM(
            (
                    SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 1 as tipe, toko_id, ifnull(supplier_id,0) as supplier_id
                    FROM (
                        SELECT t2.qty as qty, t2.jumlah_roll, id, barang_id, warna_id, pembelian_id
                        FROM nd_pembelian_detail t1
                        LEFT JOIN (
                            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll, 1)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
                            FROM nd_pembelian_qty_detail
                            GROUP BY pembelian_detail_id
                            ) t2
                        ON t2.pembelian_detail_id = t1.id
                        ORDER BY pembelian_id
                    ) nd_pembelian_detail
                    LEFT JOIN (
                        SELECT *
                        FROM nd_pembelian
                        WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal_end'
                        AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
                        AND status_aktif = 1
                        ) nd_pembelian
                    ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
                    WHERE nd_pembelian.id is not null
                    GROUP BY barang_id, warna_id, nd_pembelian.gudang_id, tanggal
            )UNION(
                SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 2, toko_id, ifnull(supplier_id,0)
                FROM nd_mutasi_barang
                WHERE tanggal <= '$tanggal_end'
                AND tanggal >= '$tanggal_awal'
                AND status_aktif = 1
                GROUP BY barang_id, warna_id, gudang_id_after, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 3, t1.toko_id, ifnull(supplier_id,0)
                FROM nd_penjualan_detail t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_penjualan
                    WHERE tanggal <= '$tanggal_end'
                    AND tanggal >= '$tanggal_awal'
                    AND status_aktif = 1
                    ) t2
                ON t1.penjualan_id = t2.id
                LEFT JOIN (
                    SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id, supplier_id
                    FROM nd_penjualan_qty_detail
                    GROUP BY penjualan_detail_id, supplier_id
                    ) t3
                ON t3.penjualan_detail_id = t1.id
                where t2.id is not null
                AND is_eceran = 0
                GROUP BY barang_id, warna_id, t1.gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 4, toko_id, ifnull(supplier_id,0)
                FROM nd_retur_jual_detail
                LEFT JOIN (
                    SELECT *
                    FROM nd_retur_jual
                    WHERE tanggal <= '$tanggal_end'
                    AND tanggal >= '$tanggal_awal'
                    AND status_aktif = 1
                    ) nd_retur_jual
                ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
                LEFT JOIN (
                    SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                    FROM nd_retur_jual_qty
                    GROUP BY retur_jual_detail_id
                    ) nd_penjualan_qty_detail
                ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
                WHERE nd_retur_jual.id is not null
                GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 5, toko_id, ifnull(supplier_id,0)
                    FROM (
                        SELECT barang_id, warna_id, keterangan, id, gudang_id, tanggal, toko_id, supplier_id
                        FROM nd_penyesuaian_stok
                        WHERE tipe_transaksi = 0
                        AND tanggal <= '$tanggal_end'
                        AND tanggal >= '$tanggal_awal'
                    )t1
                    LEFT JOIN (
                        SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
                        FROM nd_penyesuaian_stok_qty
                        GROUP BY penyesuaian_stok_id
                        ) t2
                    ON t2.penyesuaian_stok_id = t1.id
                    GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 6, toko_id, ifnull(supplier_id,0)
                FROM nd_penyesuaian_stok
                WHERE tanggal <= '$tanggal_end'
                AND tanggal >= '$tanggal_awal'
                AND tipe_transaksi = 1
                GROUP BY barang_id, warna_id, gudang_id, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 7, toko_id, ifnull(supplier_id,0)
                FROM nd_penyesuaian_stok
                WHERE tanggal <= '$tanggal_end'
                AND tanggal >= '$tanggal_awal'
                AND tipe_transaksi = 2
                GROUP BY barang_id, warna_id, gudang_id, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 8, toko_id, ifnull(supplier_id,0)
                FROM nd_mutasi_barang
                WHERE tanggal <= '$tanggal_end'	
                AND tanggal >= '$tanggal_awal'
                AND status_aktif = 1
                GROUP BY barang_id, warna_id, gudang_id_before, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, 
                sum(qty) as qty_masuk, sum(jumlah_roll), 0, 0,
                tanggal, 10, toko_id, ifnull(supplier_id,0)
                FROM (
                    SELECT id, barang_id, warna_id, gudang_id, group_concat(qty) as qty_data, 
                        sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id, supplier_id
                    FROM nd_stok_opname_detail
                       GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id, supplier_id
                ) t1
                LEFT JOIN nd_stok_opname t2
                ON t1.stok_opname_id = t2.id
                GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id, supplier_id
            )UNION(
                SELECT barang_id, warna_id, gudang_id, 
                0, 0, sum(qty), sum(jumlah_roll),
                tanggal, 11, toko_id, ifnull(supplier_id,0)
                FROM (
                    SELECT *
                    FROM nd_mutasi_stok_eceran
                    WHERE tanggal <= '$tanggal_end'	
                    AND tanggal >= '$tanggal_awal'
                ) t1
                LEFT JOIN nd_mutasi_stok_eceran_qty t2
                ON t2.mutasi_stok_eceran_id = t1.id
                GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id, supplier_id
            )
        ) tbl_a
        LEFT JOIN (
            SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_stok, toko_id, supplier_id
            FROM (
                SELECT barang_id, warna_id, gudang_id, stok_opname_id, toko_id, ifnull(supplier_id,0) as supplier_id
                FROM nd_stok_opname_detail
                GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id, supplier_id
            )t1
            LEFT JOIN nd_stok_opname t2
            ON t1.stok_opname_id = t2.id
            GROUP BY barang_id, warna_id, gudang_id, toko_id, supplier_id
        ) t_stok
        ON tbl_a.barang_id = t_stok.barang_id
        AND tbl_a.warna_id = t_stok.warna_id
        AND tbl_a.gudang_id = t_stok.gudang_id
        AND tbl_a.toko_id = t_stok.toko_id
        AND tbl_a.supplier_id = t_stok.supplier_id
        LEFT JOIN nd_barang tbl_b
        ON tbl_a.barang_id = tbl_b.id
        LEFT JOIN nd_warna tbl_c
        ON tbl_a.warna_id = tbl_c.id
        LEFT JOIN nd_satuan tbl_d
        ON tbl_b.satuan_id = tbl_d.id
        LEFT JOIN nd_satuan tbl_e
        ON tbl_b.packaging_id = tbl_e.id
        LEFT JOIN nd_satuan tbl_f
        ON tbl_b.satuan_eceran_id = tbl_f.id
        LEFT JOIN nd_gudang tbl_g
        ON tbl_a.gudang_id = tbl_g.id
        LEFT JOIN nd_supplier tbl_h
        ON tbl_a.supplier_id = tbl_h.id
        LEFT JOIN nd_toko tbl_i
        ON tbl_a.toko_id = tbl_i.id
        Where tbl_a.barang_id is not null
        GROUP BY tbl_a.barang_id, tbl_a.warna_id, toko_id, supplier_id
        ORDER BY nama_jual, warna_jual");
		
		return $query->result();
		// return $this->db->last_query();
	}

    function get_stok_barang_eceran_list($tanggal){
		$query = $this->db->query("SELECT barang_id,warna_id, gudang_id,  sum(tA.qty - ifnull(tB.qty,0)) as qty_stok, group_concat(tA.qty - ifnull(tB.qty,0)) as qty_stok_data,
        tA.toko_id, tA.supplier_id, 
        t8.nama as nama_toko, t7.nama as nama_supplier, t6.nama as nama_gudang,
        t2.warna_jual as nama_warna_jual,
        t1.nama as nama_barang, t1.nama_jual as nama_barang_jual, 
        harga_beli, harga_jual, harga_ecer,
        t3.nama as nama_satuan_kecil, t4.nama as nama_satuan_besar, t5.nama as nama_satuan_eceran
				FROM (
				    	SELECT stok_eceran_qty_id, tX.barang_id, tX.warna_id, tX.gudang_id, if(tanggal >= ifnull(tanggal_so,'2018-01-01'),qty, 0 ) as qty, tX.toko_id, tX.supplier_id
			        	FROM (
							(
								SELECT t1.id, barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 1 as tipe, gudang_id, tanggal, toko_id, ifnull(supplier_id,0) as supplier_id
								FROM (
									SELECT *
									FROM nd_mutasi_stok_eceran
									WHERE tanggal <= '$tanggal'
									AND status_aktif = 1
								)t1
								LEFT JOIN nd_mutasi_stok_eceran_qty t2
								ON t2.mutasi_stok_eceran_id = t1.id
							)UNION(
								SELECT tB.id, barang_id, warna_id, tA.id as stok_eceran_qty_id, qty, 2 , gudang_id, tanggal, toko_id, ifnull(supplier_id,0)
								FROM nd_stok_opname_eceran tA
								LEFT JOIN (
									SELECT *
									FROM nd_stok_opname
									WHERE status_aktif = 1
									AND tanggal <= '$tanggal'
								)tB
								ON tA.stok_opname_id = tB.id
								WHERE tB.id is not null
							)
						)tX
						LEFT JOIN (
							SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_so, toko_id, ifnull(supplier_id,0) supplier_id
							FROM nd_stok_opname_eceran tA
							LEFT JOIN (
								SELECT *
								FROM nd_stok_opname
								WHERE tanggal <= '$tanggal'
							) tB
							ON tA.stok_opname_id = tB.id
							GROUP BY barang_id, warna_id, gudang_id, toko_id, supplier_id
						) tY
						ON tX.barang_id = tY.barang_id
						AND tX.warna_id = tY.warna_id
						AND tX.gudang_id = tY.gudang_id
						AND tX.toko_id = tY.toko_id
						AND tX.supplier_id = tY.supplier_id
					)tA
					LEFT JOIN (
						SELECT stok_eceran_qty_id, sum(qty) as qty, t2.toko_id, supplier_id
						FROM (
							SELECT *
							FROM nd_penjualan_qty_detail
							WHERE stok_eceran_qty_id is not null
							)t1
                        LEFT JOIN nd_penjualan_detail t2
                        ON t1.penjualan_detail_id=t2.id
                        LEFT JOIN nd_penjualan t3
                        ON t2.penjualan_id=t3.id
                        WHERE status_aktif=1
                        GROUP BY stok_eceran_qty_id, toko_id, supplier_id

					)tB
					ON tA.stok_eceran_qty_id = tB.stok_eceran_qty_id
                    AND tA.toko_id = tB.toko_id
                    AND tA.supplier_id = tB.supplier_id
                    LEFT JOIN nd_barang t1
                    ON tA.barang_id = t1.id
                    LEFT JOIN nd_warna t2
                    ON tA.warna_id = t2.id
                    LEFT JOIN nd_satuan t3
                    ON t1.satuan_id = t3.id
                    LEFT JOIN nd_satuan t4
                    ON t1.packaging_id = t4.id
                    LEFT JOIN nd_satuan t5
                    ON t1.satuan_eceran_id = t5.id
                    LEFT JOIN nd_gudang t6
                    ON tA.gudang_id = t6.id
                    LEFT JOIN nd_supplier t7
                    ON tA.supplier_id = t7.id
                    LEFT JOIN nd_toko t8
                    ON tA.toko_id = t8.id
                    WHERE tA.qty > 0
					GROUP BY barang_id, warna_id, gudang_id, tA.toko_id, tA.supplier_id
				");
		
		return $query->result();	
	}

    function get_stok_barang_detail_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
        $eceran_cond = "AND is_eceran = 0";
        if($warna_id == 888){
            $eceran_cond = "AND is_eceran = 1";
        }

		$query = $this->db->query("SELECT qty, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll,
        tbl_a.toko_id, supplier_id, tbl_d.nama as nama_toko, tbl_e.nama as nama_supplier
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, 
                        sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'a' as tipe, t2.id as id, toko_id, supplier_id
				        FROM (
				        	SELECT a.id, pembelian_id, barang_id, warna_id, b.qty, b.jumlah_roll
				        	FROM (
					        	SELECT *
					        	FROM nd_pembelian_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
				        		)a
							LEFT JOIN nd_pembelian_qty_detail b
							ON b.pembelian_detail_id = a.id
				        	) t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	AND tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND gudang_id = $gudang_id
				        	) t2
				        ON t1.pembelian_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY qty, tanggal, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 0, 
                        sum(jumlah_roll) as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'j' as tipe, t2.id as id, toko_id, supplier_id
				        FROM (
				        	SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id, toko_id, supplier_id
				        	FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
                                $eceran_cond
				        		)a
							LEFT JOIN nd_penjualan_qty_detail b
							ON b.penjualan_detail_id = a.id
							WHERE gudang_id = $gudang_id
				        	) t1
				        LEFT JOIN (
				        	SELECT id, tanggal, no_faktur
				        	FROM nd_penjualan
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	AND tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	) t2
				        ON t1.penjualan_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY qty, tanggal, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps0' as tipe, id, toko_id, supplier_id
			        	FROM (
							SELECT id, tanggal, barang_id, warna_id, gudang_id, keterangan, toko_id, supplier_id
							FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 0
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							) a
						LEFT JOIN (
							SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat(qty,'??', jumlah_roll,'??', id) SEPARATOR '--') as data_qty, penyesuaian_stok_id
							FROM nd_penyesuaian_stok_qty
							GROUP BY qty, penyesuaian_stok_id
							) t1
						ON a.id = t1.penyesuaian_stok_id
						GROUP BY qty, tanggal, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps1' as tipe, id, toko_id, supplier_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 1
						GROUP BY tanggal, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,
                        0, sum(jumlah_roll), 
                        tanggal, keterangan, 'ps2' as tipe, id, toko_id, supplier_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 2
						GROUP BY tanggal, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						qty as qty_masuk, sum(jumlah_roll), 0,
						tanggal,'so', 'so', t1.id, toko_id, supplier_id
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, qty, 
                            sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id, supplier_id
                            FROM nd_stok_opname_detail
                            WHERE barang_id = $barang_id
                            AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							AND stok_opname_id = $stok_opname_id
							GROUP BY qty, barang_id, warna_id, gudang_id, stok_opname_id, toko_id, supplier_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						GROUP BY qty, tanggal, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id,  
						(qty), 0, sum(jumlah_roll),
						tanggal, concat('asembly-out-',t1.id), 'ask',
						t1.id, toko_id, supplier_id
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal >= '$tanggal_awal'
                            AND tanggal >= '$tanggal_start'
                            AND tanggal <= '$tanggal_end'
                            AND barang_id_sumber = $barang_id
                            AND warna_id_sumber = $warna_id
                        ) t1
                        LEFT JOIN nd_assembly_detail_sumber t2
                        ON t2.assembly_id = t1.id
						GROUP BY qty, toko_id, supplier_id, gudang_id, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						(qty), sum(jumlah_roll),
						0, 
						tanggal, concat('asembly-in-',t1.id), 'asm',
						t1.id, toko_id, supplier_id
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal >= '$tanggal_awal'
                            AND tanggal >= '$tanggal_start'
                            AND tanggal <= '$tanggal_end'
                            AND barang_id_hasil = $barang_id
                            AND warna_id_hasil = $warna_id
                        ) t1
                        LEFT JOIN nd_assembly_detail_hasil t2
                        ON t2.assembly_id = t1.id
						GROUP BY qty, toko_id, supplier_id, gudang_id, tanggal
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
                LEFT JOIN nd_toko tbl_d
                ON tbl_a.toko_id = tbl_d.id
				LEFT JOIN nd_supplier tbl_e
                ON tbl_a.supplier_id = tbl_e.id
				Where barang_id is not null
				GROUP BY qty, toko_id, supplier_id
				ORDER BY qty asc
				");
		
		return $query->result();
	}



//====================================per toko=======================================

    function get_stok_barang_detail_2_pertoko($toko_id, $gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
        $this->db->simple_query('SET SESSION group_concat_max_len=15000');
        $eceran_cond = "AND is_eceran = 0";
        if($warna_id == 888){
            $eceran_cond = "AND is_eceran = 1";
        }

        $query = $this->db->query("SELECT qty, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll,
        tbl_a.toko_id, supplier_id, tbl_d.nama as nama_toko, tbl_e.nama as nama_supplier
                FROM(
                    (
                        SELECT barang_id, warna_id, t2.gudang_id, qty, 
                        sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'a' as tipe, t2.id as id, toko_id, supplier_id
                        FROM (
                            SELECT a.id, pembelian_id, barang_id, warna_id, b.qty, b.jumlah_roll
                            FROM (
                                SELECT *
                                FROM nd_pembelian_detail
                                WHERE barang_id = $barang_id
                                AND warna_id = $warna_id
                                )a
                            LEFT JOIN nd_pembelian_qty_detail b
                            ON b.pembelian_detail_id = a.id
                            ) t1
                        LEFT JOIN (
                            SELECT *
                            FROM nd_pembelian
                            WHERE status_aktif = 1
                            AND tanggal >= '$tanggal_awal'
                            AND tanggal >= '$tanggal_start'
                            AND tanggal <= '$tanggal_end'
                            AND gudang_id = $gudang_id
                            AND toko_id = $toko_id
                            ) t2
                        ON t1.pembelian_id = t2.id
                        WHERE t2.id is not null
                        GROUP BY qty, tanggal, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, qty , 0, 
                        sum(jumlah_roll) as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'j' as tipe, t2.id as id, toko_id, supplier_id
                        FROM (
                            SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id, toko_id, supplier_id
                            FROM (
                                SELECT *
                                FROM nd_penjualan_detail
                                WHERE barang_id = $barang_id
                                AND warna_id = $warna_id
                                AND toko_id = $toko_id
                                $eceran_cond
                                )a
                            LEFT JOIN nd_penjualan_qty_detail b
                            ON b.penjualan_detail_id = a.id
                            WHERE gudang_id = $gudang_id
                            ) t1
                        LEFT JOIN (
                            SELECT id, tanggal, no_faktur
                            FROM nd_penjualan
                            WHERE status_aktif = 1
                            AND tanggal >= '$tanggal_awal'
                            AND tanggal >= '$tanggal_start'
                            AND tanggal <= '$tanggal_end'
                            ) t2
                        ON t1.penjualan_id = t2.id
                        WHERE t2.id is not null
                        GROUP BY qty, tanggal, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps0' as tipe, id, toko_id, supplier_id
                        FROM (
                            SELECT id, tanggal, barang_id, warna_id, gudang_id, keterangan, toko_id, supplier_id
                            FROM nd_penyesuaian_stok
                            WHERE tanggal >= '$tanggal_awal'
                            AND tipe_transaksi = 0
                            AND barang_id = $barang_id
                            AND toko_id = $toko_id
                            AND warna_id = $warna_id
                            AND gudang_id = $gudang_id
                            ) a
                        LEFT JOIN (
                            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat(qty,'??', jumlah_roll,'??', id) SEPARATOR '--') as data_qty, penyesuaian_stok_id
                            FROM nd_penyesuaian_stok_qty
                            GROUP BY qty, penyesuaian_stok_id
                            ) t1
                        ON a.id = t1.penyesuaian_stok_id
                        GROUP BY qty, tanggal, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps1' as tipe, id, toko_id, supplier_id
                        FROM nd_penyesuaian_stok
                        WHERE tanggal >= '$tanggal_awal'
                        AND tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND toko_id = $toko_id
                        AND barang_id = $barang_id
                        AND warna_id = $warna_id
                        AND tipe_transaksi = 1
                        GROUP BY tanggal, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, qty ,
                        0, sum(jumlah_roll), 
                        tanggal, keterangan, 'ps2' as tipe, id, toko_id, supplier_id
                        FROM nd_penyesuaian_stok
                        WHERE tanggal >= '$tanggal_awal'
                        AND tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND toko_id = $toko_id
                        AND barang_id = $barang_id
                        AND warna_id = $warna_id
                        AND tipe_transaksi = 2
                        GROUP BY tanggal, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, 
                        qty as qty_masuk, sum(jumlah_roll), 0,
                        tanggal,'so', 'so', t1.id, toko_id, supplier_id
                        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, qty, 
                            sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id, supplier_id
                            FROM nd_stok_opname_detail
                            WHERE barang_id = $barang_id
                            AND warna_id = $warna_id
                            AND toko_id = $toko_id
                            AND gudang_id = $gudang_id
                            AND stok_opname_id = $stok_opname_id
                            GROUP BY qty, barang_id, warna_id, gudang_id, stok_opname_id, toko_id, supplier_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
                        GROUP BY qty, tanggal, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id,  
                        (qty), 0, sum(jumlah_roll),
                        tanggal, concat('asembly-out-',t1.id), 'ask',
                        t1.id, toko_id, supplier_id
                        FROM (
                            SELECT *
                            FROM nd_assembly
                            WHERE tanggal >= '$tanggal_awal'
                            AND toko_id = $toko_id
                            AND tanggal >= '$tanggal_start'
                            AND tanggal <= '$tanggal_end'
                            AND barang_id_sumber = $barang_id
                            AND warna_id_sumber = $warna_id
                        ) t1
                        LEFT JOIN nd_assembly_detail_sumber t2
                        ON t2.assembly_id = t1.id
                        GROUP BY qty, toko_id, supplier_id, gudang_id, tanggal
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id, 
                        (qty), sum(jumlah_roll),
                        0, 
                        tanggal, concat('asembly-in-',t1.id), 'asm',
                        t1.id, toko_id, supplier_id
                        FROM (
                            SELECT *
                            FROM nd_assembly
                            WHERE tanggal >= '$tanggal_awal'
                            AND tanggal >= '$tanggal_start'
                            AND tanggal <= '$tanggal_end'
                            AND toko_id = $toko_id
                            AND barang_id_hasil = $barang_id
                            AND warna_id_hasil = $warna_id
                        ) t1
                        LEFT JOIN nd_assembly_detail_hasil t2
                        ON t2.assembly_id = t1.id
                        GROUP BY qty, toko_id, supplier_id, gudang_id, tanggal
                    )
                ) tbl_a
                LEFT JOIN nd_barang tbl_b
                ON tbl_a.barang_id = tbl_b.id
                LEFT JOIN nd_warna tbl_c
                ON tbl_a.warna_id = tbl_c.id
                LEFT JOIN nd_toko tbl_d
                ON tbl_a.toko_id = tbl_d.id
                LEFT JOIN nd_supplier tbl_e
                ON tbl_a.supplier_id = tbl_e.id
                Where barang_id is not null
                GROUP BY qty, toko_id, supplier_id
                ORDER BY qty asc
                ");
        
        return $query->result();
    }

    function get_stok_barang_eceran_list_detail_pertoko($toko_id, $gudang_id, $barang_id,$warna_id, $tanggal, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT *
		FROM (SELECT tA.stok_eceran_qty_id, tA.qty - ifnull(tB.qty,0) -ifnull(qty_mutasi,0) as qty, 
			eceran_source, tanggal, tipe, tanggal_jual, nama_customer, tA.qty as qty_in, qty_out
				FROM (
					(
						SELECT barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 1 as tipe, gudang_id, tanggal
						FROM (
							SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
                            AND toko_id = $toko_id
							AND status_aktif = 1
						)t1
						LEFT JOIN nd_mutasi_stok_eceran_qty t2
						ON t2.mutasi_stok_eceran_id = t1.id
					)UNION(
						SELECT barang_id, warna_id, id as stok_eceran_qty_id, qty, 2 , gudang_id, tanggal
						FROM (
							SELECT barang_id, warna_id, id as stok_eceran_qty_id, qty, 2 , gudang_id,stok_opname_id
							FROM nd_stok_opname_eceran
							WHERE barang_id = $barang_id
							AND warna_id = $warna_id
                            AND toko_id = $toko_id
							AND gudang_id = $gudang_id
							AND stok_opname_id = $stok_opname_id
						)t1
						LEFT JOIN nd_stok_opname t2
						ON t1.stok_opname_id = t2.id
					)
					)tA
					LEFT JOIN (
						SELECT stok_eceran_qty_id, sum(qty) as qty, eceran_source, 
						group_concat(qty ORDER BY tanggal ASC) as qty_out, group_concat(tanggal ORDER BY tanggal ASC) as tanggal_jual, 
						group_concat(ifnull(t4.nama,'-') ORDER BY tanggal ASC SEPARATOR '??' ) as nama_customer
						FROM (
							SELECT *
							FROM nd_penjualan_qty_detail
							WHERE stok_eceran_qty_id is not null
							)t1
							LEFT JOIN nd_penjualan_detail t2
							ON t1.penjualan_detail_id=t2.id
							LEFT JOIN nd_penjualan t3
							ON t2.penjualan_id=t3.id
							LEFT JOIN nd_customer t4
							ON t3.customer_id=  t4.id
							WHERE t3.status_aktif=1
                            AND t2.toko_id = $toko_id
							GROUP BY barang_id, stok_eceran_qty_id, eceran_source

					)tB
					ON tA.stok_eceran_qty_id = tB.stok_eceran_qty_id
					AND tA.tipe = tB.eceran_source
					LEFT JOIN (
							SELECT sum(qty) as qty_mutasi, mutasi_stok_eceran_qty_source_id
							FROM nd_mutasi_stok_eceran_qty
							WHERE mutasi_stok_eceran_qty_source_id is not null
							GROUP BY mutasi_stok_eceran_qty_source_id
					)tC
					ON tA.stok_eceran_qty_id = tC.mutasi_stok_eceran_qty_source_id
					WHERE barang_id is not null
					AND warna_id is not null
					AND tA.qty - ifnull(tB.qty,0) > 0
					)res
					ORDER BY qty asc
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

    function kartu_stok_eceran_pertoko($toko_id, $gudang_id, $barang_id,$warna_id, $tanggal_end, $tanggal_start){
		$query = $this->db->query("SELECT tA.*, username
		FROM (
			(
				SELECT barang_id, warna_id, t2.id as stok_eceran_qty_id, qty as qty_in, 0 as qty_out, 1 as eceran_srouce, t1.id as trx_id, gudang_id, tanggal,
				'mutasi eceran' as keterangan, ' ' as no_faktur, '1' as tipe, user_id
				FROM (
					SELECT *
					FROM nd_mutasi_stok_eceran
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND barang_id = $barang_id
					AND warna_id = $warna_id
					AND toko_id = $toko_id
					AND gudang_id = $gudang_id
					AND status_aktif = 1
				)t1
				LEFT JOIN nd_mutasi_stok_eceran_qty t2
				ON t2.mutasi_stok_eceran_id = t1.id
			)UNION(
				SELECT barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 0 as qty_out, 2 as eceran_srouce, t2.id as trx_id, gudang_id, tanggal,
				'mutasi eceran', ' ' as no_faktur, '2' as tipe, user_id
				FROM (
					SELECT barang_id, warna_id, id as stok_eceran_qty_id, qty, 2 , gudang_id,stok_opname_id, id
					FROM nd_stok_opname_eceran
					WHERE barang_id = $barang_id
					AND warna_id = $warna_id
					AND toko_id = $toko_id
					AND gudang_id = $gudang_id
				)t1
				LEFT JOIN nd_stok_opname t2
				ON t1.stok_opname_id = t2.id
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
			)UNION(
				SELECT barang_id, warna_id, stok_eceran_qty_id, 0 as qty_in, qty as qty_out, eceran_source, penjualan_id, t2.gudang_id, tanggal, 
				no_faktur_lengkap, ifnull(t4.nama,'-') as nama_customer, '3' as tipe, user_id
				FROM (
					SELECT *
					FROM nd_penjualan_qty_detail
					WHERE stok_eceran_qty_id is not null
				)t1
				LEFT JOIN nd_penjualan_detail t2
				ON t1.penjualan_detail_id=t2.id
				LEFT JOIN nd_penjualan t3
				ON t2.penjualan_id=t3.id
				LEFT JOIN nd_customer t4
				ON t3.customer_id=  t4.id
				WHERE t3.status_aktif=1
				AND tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND barang_id = $barang_id
				AND warna_id = $warna_id
                AND t2.toko_id = $toko_id
				AND t2.gudang_id = $gudang_id
				AND no_faktur is not null
				AND no_faktur != ''
			)
		)tA
		LEFT JOIN nd_user
		ON tA.user_id = nd_user.id");
		
		return $query->result();
		// return $this->db->last_query();
	}

    function get_stok_barang_satuan_pertoko($toko_id, $gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang, tanggal, 
		tbl_c.warna_beli as nama_warna, barang_id, warna_id, qty_masuk, qty_keluar, 
		jumlah_roll_masuk, jumlah_roll_keluar, no_faktur, tipe, trx_id, qty_data, tbl_a.toko_id, supplier_id, tbl_d.nama as nama_supplier
				FROM(
					(
						SELECT barang_id, warna_id, toko_id, nd_pembelian.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 
						if(no_faktur !='' && no_faktur != null, no_faktur, no_surat_jalan) as no_faktur, 
						'a1' as tipe, nd_pembelian.id as trx_id, qty_data, supplier_id, nd_pembelian_detail.id as detail_id
						FROM (
							SELECT barang_id, warna_id,t1.id,pembelian_id, t2.qty, t2.jumlah_roll, qty_data
							FROM (
								SELECT pembelian_detail_id, sum(qty * if(jumlah_roll != 0, jumlah_roll, 1)) as qty, sum(jumlah_roll) as jumlah_roll, 
								group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
								FROM nd_pembelian_qty_detail
								GROUP BY pembelian_detail_id
								) t2
							LEFT JOIN (
								SELECT *
								FROM nd_pembelian_detail
								WHERE barang_id = $barang_id
								AND warna_id = $warna_id
								)t1 
							ON t2.pembelian_detail_id = t1.id
							) nd_pembelian_detail
						LEFT JOIN (
							SELECT *
							FROM nd_pembelian
							WHERE ifnull(tanggal_sj,tanggal) >= '$tanggal_start'
							AND ifnull(tanggal_sj,tanggal) <= '$tanggal_end'
							AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
							AND gudang_id = $gudang_id
							AND toko_id = $toko_id
							AND status_aktif = 1
							) nd_pembelian
						ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
						WHERE barang_id is not null 
						AND warna_id is not null
						AND nd_pembelian.id is not null
					)UNION(
						SELECT barang_id, warna_id, nd_penjualan_detail.toko_id, nd_penjualan_detail.gudang_id, 
						CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, 
						tanggal, no_faktur_lengkap, 'a2' as tipe, 
						nd_penjualan.id as trx_id, qty_data, supplier_id, nd_penjualan_detail.id as detail_id
						FROM (
							SELECT *
							FROM nd_penjualan_detail 
							WHERE gudang_id = $gudang_id 
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND toko_id = $toko_id
							AND (is_eceran = 0 OR warna_id=888)
							) nd_penjualan_detail
						LEFT JOIN (
							SELECT *
							FROM nd_penjualan
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							) nd_penjualan
						ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, 
							penjualan_detail_id, group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data, supplier_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id, supplier_id
							) nd_penjualan_qty_detail
						ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
						WHERE barang_id is not null 
						AND warna_id is not null
						AND nd_penjualan.id is not null
					)UNION(
						SELECT barang_id, warna_id,  toko_id,nd_retur_jual_detail.gudang_id, CAST(qty as DECIMAL(15,2)) as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
						tanggal, no_faktur_lengkap, 'r0' as tipe, nd_retur_jual.id as trx_id, qty_data,
						0 as supplier_id, nd_retur_jual_detail.id  as detail_id
						FROM (
							SELECT *
							FROM nd_retur_jual
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND status_aktif = 1
							) nd_retur_jual
						LEFT JOIN (
							SELECT *
							FROM nd_retur_jual_detail
							WHERE gudang_id = $gudang_id
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND toko_id = $toko_id
							) nd_retur_jual_detail
						ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, 
							group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
							FROM nd_retur_jual_qty
							GROUP BY retur_jual_detail_id
							) nd_retur_jual_qty
						ON nd_retur_jual_qty.retur_jual_detail_id = nd_retur_jual_detail.id
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id,  toko_id,nd_retur_beli_detail.gudang_id, 
						0 as qty_masuk, 0 as jumlah_roll_masuk, 
						CAST(qty as DECIMAL(15,2)) as qty_keluar, jumlah_roll as jumlah_roll_keluar, 
						tanggal, no_faktur_lengkap, 'r1' as tipe, nd_retur_beli.id as trx_id, qty_data,
						0 as supplier_id, nd_retur_beli_detail.id  as detail_id
						FROM (
							SELECT *
							FROM nd_retur_beli
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND toko_id = $toko_id
							AND status_aktif = 1
							) nd_retur_beli
						LEFT JOIN (
							SELECT *
							FROM nd_retur_beli_detail
							WHERE gudang_id = $gudang_id
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							) nd_retur_beli_detail
						ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
							FROM nd_retur_beli_qty
							GROUP BY retur_beli_detail_id
							) nd_retur_beli_qty
						ON nd_retur_beli_qty.retur_beli_detail_id = nd_retur_beli_detail.id
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id,  toko_id,gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
						tanggal, concat_ws('??', nd_user.username, user_id, nd_penyesuaian_stok.id  ), 0 as tipe, nd_penyesuaian_stok.id, concat(qty,',',jumlah_roll) as qty_data,
						supplier_id, nd_penyesuaian_stok.id as detail_id
						FROM (
							SELECT *
							FROM nd_penyesuaian_stok
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
							AND toko_id = $toko_id
							AND tipe_transaksi = 0
						) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id,  toko_id,gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 
						0 as jumlah_roll_keluar, tanggal, concat_ws('??', nd_user.username, user_id, nd_penyesuaian_stok.id, keterangan  ), 1 as tipe,nd_penyesuaian_stok.id, 
						concat(qty,',',jumlah_roll)  as qty_data, supplier_id, nd_penyesuaian_stok.id as detail_id
						FROM (
							SELECT *
							FROM nd_penyesuaian_stok
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
							AND toko_id = $toko_id
							AND tipe_transaksi = 1
						) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id, toko_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, tanggal, concat_ws('??', nd_user.username, user_id, 
						nd_penyesuaian_stok.id, keterangan ), 2 as tipe,nd_penyesuaian_stok.id, concat(qty,',',jumlah_roll) as qty_data, 
						supplier_id, nd_penyesuaian_stok.id as detail_id
						FROM (
							SELECT *
							FROM nd_penyesuaian_stok
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
							AND toko_id = $toko_id
							AND tipe_transaksi = 2
						) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id,  toko_id,gudang_id_after, qty2 as qty_masuk, jumlah_roll2 as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal ,nd_gudang.nama, 'b1' as tipe, t1.id, concat(qty,',',jumlah_roll)  as qty_data,
						supplier_id, t2.id as detail_id
						FROM (
							SELECT *
							FROM nd_mutasi_barang
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND gudang_id_after = $gudang_id
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND toko_id = $toko_id
							AND status_aktif = 1
							) t1
						LEFT JOIN nd_gudang
						ON t1.gudang_id_before = nd_gudang.id
						LEFT JOIN (
							SELECT sum(qty) as qty2, sum(jumlah_roll) as jumlah_roll2, mutasi_barang_id, supplier_id, id
							FROM nd_mutasi_barang_detail
							GROUP BY mutasi_barang_id
						) t2
						ON t1.id = t2.mutasi_barang_id 
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id, toko_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						qty2 as qty_keluar, jumlah_roll2 as jumlah_roll_keluar, tanggal,nd_gudang.nama, 'b2' as tipe, t1.id, concat(qty,',',jumlah_roll)  as qty_data,
						supplier_id, t2.id as detail_id
						FROM (
							SELECT *
							FROM nd_mutasi_barang
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND gudang_id_before = $gudang_id
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND toko_id = $toko_id
							AND status_aktif = 1
							) t1
						LEFT JOIN nd_gudang
						ON t1.gudang_id_after = nd_gudang.id
						LEFT JOIN (
							SELECT sum(qty) as qty2, sum(jumlah_roll) as jumlah_roll2, mutasi_barang_id, supplier_id, id
							FROM nd_mutasi_barang_detail
							GROUP BY mutasi_barang_id
						) t2
						ON t1.id = t2.mutasi_barang_id 
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id, toko_id, gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, 
						tanggal,nd_gudang.nama, 'ecer1' as tipe, t1.id, qty_data, supplier_id, t2.id as detail_id
						FROM (
							SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND gudang_id = $gudang_id
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND toko_id = $toko_id
							AND status_aktif = 1
							) t1
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, 
							mutasi_stok_eceran_id, group_concat(concat(qty,',',jumlah_roll,',',id) SEPARATOR '??') as qty_data, id
							FROM nd_mutasi_stok_eceran_qty
							GROUP by mutasi_stok_eceran_id
							) t2
						ON t2.mutasi_stok_eceran_id = t1.id
						LEFT JOIN nd_gudang
						ON t1.gudang_id = nd_gudang.id
						WHERE barang_id is not null 
						AND warna_id is not null
					)UNION(
						SELECT barang_id, warna_id, toko_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0, 0, tanggal,nd_gudang.nama, 'so' as tipe, 
						t1.id, qty_data, supplier_id, t2.id as detail_id
						FROM (
							SELECT id, barang_id, warna_id, gudang_id, group_concat(qty) as qty_data, toko_id, 
								sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, supplier_id
							FROM nd_stok_opname_detail
							WHERE barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
							AND toko_id = $toko_id
							GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, supplier_id
						) t1
						LEFT JOIN (
							SELECT *
							FROM nd_stok_opname
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND tanggal >= '$tanggal_awal'
							AND status_aktif = 1
						) t2
						ON t1.stok_opname_id = t2.id
						LEFT JOIN nd_gudang
						ON t1.gudang_id = nd_gudang.id
						WHERE t2.id is not null
						ORDER BY tanggal DESC
						
					)UNION(
						SELECT barang_id, warna_id, toko_id, gudang_id, 
						0, 0, 
						sum(qty), sum(jumlah_roll),
						tanggal, concat('Diambil untuk <b>',nama_barang,'</b>'), 'ask',
						t1.id, qty_data, supplier_id, t2.id as detail_id
						FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_start'
								AND tanggal <= '$tanggal_end'
								AND tanggal >= '$tanggal_awal'
								AND toko_id = $toko_id
								AND gudang_id = $gudang_id 
								AND barang_id_sumber = $barang_id
								AND warna_id_sumber = $warna_id
							)tA
							LEFT JOIN nd_barang as b2
							ON tA.barang_id_hasil = b2.id
							LEFT JOIN nd_warna as w2
							ON tA.warna_id_hasil = w2.id

						) t1
						LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id, id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll,
							group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
							FROM nd_assembly_detail_sumber 
							GROUP BY assembly_id, supplier_id
							)t2
						ON t2.assembly_id = t1.id
						GROUP BY barang_id, warna_id, gudang_id, tanggal
					)UNION(
						SELECT barang_id, warna_id, toko_id, gudang_id, 
						sum(qty), sum(jumlah_roll),
						0, 0, 
						tanggal, concat('Diambil dari <b>',nama_barang,'</b>'), 'asm',
						t1.id, qty_data, supplier_id, t2.id as detail_id
						FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_start'
								AND tanggal <= '$tanggal_end'
								AND tanggal >= '$tanggal_awal'
								AND toko_id = $toko_id
								AND gudang_id = $gudang_id 
								AND barang_id_hasil = $barang_id
								AND warna_id_hasil = $warna_id
							)tA
							LEFT JOIN nd_barang as b1
							ON tA.barang_id_sumber = b1.id
							LEFT JOIN nd_warna as w1
							ON tA.warna_id_sumber = w1.id
						) t1
						LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id, id, 
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll,
							group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
							FROM nd_assembly_detail_hasil 
							GROUP BY assembly_id
							)t2
						ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal
					)
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id				
				LEFT JOIN nd_supplier tbl_d
				ON tbl_a.supplier_id = tbl_d.id
				Where barang_id is not null
				ORDER BY tanggal, FIELD(tipe,'so') desc
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_satuan_awal_pertoko($toko_id, $gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang, tanggal, tbl_c.warna_beli as nama_warna, barang_id, warna_id, 
		sum(qty_masuk) as qty_masuk, sum(qty_keluar) qty_keluar, 
		sum(jumlah_roll_masuk) as jumlah_roll_masuk, sum(jumlah_roll_keluar) jumlah_roll_keluar
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, t1.id, 1 as tipe
				        FROM (
				        	SELECT barang_id, warna_id, id, pembelian_id, t_b.*
				        	FROM nd_pembelian_detail t_a
				        	LEFT JOIN (
								SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
								FROM nd_pembelian_qty_detail
								GROUP BY pembelian_detail_id
							) t_b
							ON t_a.id = t_b.pembelian_detail_id
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
			        	) t1
						LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE ifnull(tanggal_sj,tanggal) < '$tanggal_start'
				        	AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
				        	AND gudang_id = $gudang_id
				        	AND status_aktif = 1
							AND toko_id = $toko_id
				        	) t2
				        ON t1.pembelian_id = t2.id
				        WHERE t2.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, t1.id, 2
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND gudang_id_after = $gudang_id
							AND toko_id = $toko_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND status_aktif = 1
				        	) t1
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, nd_penjualan_detail.id , 3
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail 
				        	WHERE gudang_id = $gudang_id 
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
							AND toko_id = $toko_id
							AND (is_eceran = 0 OR warna_id=888)
				        	) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll, 1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        WHERE nd_penjualan.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, nd_retur_jual_detail.id , 4
				        FROM (
				        	SELECT *
				        	FROM nd_retur_jual_detail
				        	WHERE gudang_id = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
							AND toko_id = $toko_id
				        	) nd_retur_jual_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_retur_jual
				        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1)  ) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) nd_retur_jual_qty
				        ON nd_retur_jual_qty.retur_jual_detail_id = nd_retur_jual_detail.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND nd_retur_jual.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, 0 as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, t1.id , 5
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
							AND toko_id = $toko_id
				        	AND gudang_id_before = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND status_aktif = 1
				        	) t1
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,tanggal, t1.id , 6
			        	FROM (
			        		SELECT barang_id, warna_id, keterangan, id, gudang_id, tanggal
			        		FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
	                        AND tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND barang_id = $barang_id
		        			AND warna_id = $warna_id
							AND toko_id = $toko_id
		        			AND gudang_id = $gudang_id
		        		)t1
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
							FROM nd_penyesuaian_stok_qty
							GROUP BY penyesuaian_stok_id
							) t2
						ON t2.penyesuaian_stok_id = t1.id
                        GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,tanggal, t1.id , 7
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 1
							AND toko_id = $toko_id
				        	AND barang_id = $barang_id
		        			AND warna_id = $warna_id
		        			AND gudang_id = $gudang_id
			        	) t1
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, t1.id , 8
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 2
							AND toko_id = $toko_id
				        	AND barang_id = $barang_id
		        			AND warna_id = $warna_id
		        			AND gudang_id = $gudang_id
			        	) t1
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
						0 as qty_keluar, 0 as jumlah_roll_keluar, 
						tanggal, t1.id , 10
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, group_concat(qty) as qty_data, 
								sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
                            FROM nd_stok_opname_detail
                            WHERE barang_id = $barang_id
                            AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							AND stok_opname_id = $stok_opname_id
							AND toko_id = $toko_id
							GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						WHERE status_aktif = 1
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 0 , 0, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, t1.id , 8
				        FROM (
				        	SELECT *
				        	FROM nd_mutasi_stok_eceran
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
							AND toko_id = $toko_id
				        	AND barang_id = $barang_id
		        			AND warna_id = $warna_id
		        			AND gudang_id = $gudang_id
			        	) t1
						LEFT JOIN nd_mutasi_stok_eceran_qty t2
						ON t1.id = t2.mutasi_stok_eceran_id
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
                        SELECT barang_id_sumber, warna_id_sumber, gudang_id, 0 , 0, sum(qty_sumber), sum(jumlah_roll_sumber) , tanggal, id , 11
				        FROM nd_assembly
                        WHERE barang_id_sumber =  $barang_id
                        AND warna_id_sumber = $warna_id
						AND toko_id = $toko_id
                        AND gudang_id = $gudang_id
                        AND tanggal < '$tanggal_start'
				        AND tanggal >= '$tanggal_awal'
						GROUP BY barang_id_sumber, warna_id_sumber, gudang_id
                    )UNION(
                        SELECT barang_id_hasil, warna_id_hasil, gudang_id, sum(qty_hasil), sum(jumlah_roll_hasil) , 0 , 0, tanggal, id , 12
				        FROM nd_assembly
                        WHERE barang_id_hasil =  $barang_id
                        AND warna_id_hasil = $warna_id
						AND toko_id = $toko_id
                        AND gudang_id = $gudang_id
                        AND tanggal < '$tanggal_start'
				        AND tanggal >= '$tanggal_awal'
						GROUP BY barang_id_hasil, warna_id_hasil, gudang_id
                    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				");
		
		return $query->result();
		// return $this->db->last_query();

	}



/**
* ===================================================================================
**/
    function get_stok_warning(){
        $query = $this->db->query("SELECT t1.*, nama_barang, barang_id, warna_id
            FROM nd_stok_warning t1
            LEFT JOIN nd_barang_sku 
            ON t1.sku_id = nd_barang_sku.id
			");
		
		return $query->result();	
    }

/**
* ===================================================================================
**/

    function get_qty_stok_by_barang_detail_2($toko_id, $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_a.toko_id, tbl_a.supplier_id as supplier_id, qty, 
            sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll,
            tbl_e.nama as nama_supplier, tbl_d.nama as nama_toko, tbl_f.nama as nama_packaging, tbl_g.nama as nama_satuan
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, 
                        sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'a' as tipe, t2.id as id, 
                        toko_id, supplier_id
				        FROM (
				        	SELECT a.id, pembelian_id, barang_id, warna_id, b.qty, b.jumlah_roll
				        	FROM (
					        	SELECT *
					        	FROM nd_pembelian_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
				        		)a
							LEFT JOIN nd_pembelian_qty_detail b
							ON b.pembelian_detail_id = a.id
				        	) t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
                            WHERE ( 
                                tanggal_sj >= '$tanggal_awal'
					        	OR tanggal >= '$tanggal_awal' 
                                )  
				        	AND status_aktif = 1
				        	AND gudang_id = $gudang_id
				        	) t2
				        ON t1.pembelian_id = t2.id
				        WHERE t2.id is not null
			        	AND gudang_id = $gudang_id
				        GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        0, sum(jumlah_roll) as jumlah_roll_keluar, 
                        tanggal, no_faktur, 'j' as tipe, t2.id as id, 
                        toko_id, supplier_id
				        FROM (
				        	SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id, 
                            toko_id, supplier_id
				        	FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
				        		)a
							LEFT JOIN nd_penjualan_qty_detail b
							ON b.penjualan_detail_id = a.id
							WHERE gudang_id = $gudang_id
							AND stok_eceran_qty_id is null
				        	) t1
				        LEFT JOIN (
				        	SELECT id, tanggal, no_faktur
				        	FROM nd_penjualan
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	) t2
				        ON t1.penjualan_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps0' as tipe, id, 
                        toko_id, supplier_id
			        	FROM (
							SELECT id, tanggal, barang_id, warna_id, gudang_id, keterangan, 
                            toko_id, supplier_id
							FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 0
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							) a
						LEFT JOIN (
							SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat(qty,'??', jumlah_roll,'??', id) SEPARATOR '--') as data_qty, penyesuaian_stok_id
							FROM nd_penyesuaian_stok_qty
							GROUP BY qty, penyesuaian_stok_id
							) t1
						ON a.id = t1.penyesuaian_stok_id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 
                        sum(jumlah_roll), 0, 
                        tanggal, keterangan, 'ps1' as tipe, id, 
                        toko_id, supplier_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
				        AND gudang_id = $gudang_id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,
                        0, sum(jumlah_roll), 
                        tanggal, keterangan, 'ps2' as tipe, id, 
                        toko_id, supplier_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 2
				        AND gudang_id = $gudang_id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,
                        0, sum(jumlah_roll),  
                        tanggal, keterangan, 'ec1' as tipe, t1.id, 
                        toko_id, t2.supplier_id
			        	FROM (
							SELECT *
                            FROM nd_mutasi_stok_eceran
                            WHERE tanggal >= '$tanggal_awal'
                            AND barang_id = $barang_id
                            AND warna_id = $warna_id
                            AND gudang_id = $gudang_id
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT qty, sum(jumlah_roll) as jumlah_roll, mutasi_stok_eceran_id, supplier_id
                            FROM nd_mutasi_stok_eceran_qty
                            GROUP BY qty, mutasi_stok_eceran_id, supplier_id
                        )t2
                        ON t2.mutasi_stok_eceran_id = t1.id
                        GROUP BY qty, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, 
                        sum(jumlah_roll), 0,
						tanggal, 'so', 'so1', t1.id, 
                        toko_id, supplier_id
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id, supplier_id
                            FROM nd_stok_opname_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND stok_opname_id = $stok_opname_id
							GROUP BY qty, barang_id, warna_id, gudang_id, stok_opname_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						(qty), 0, sum(jumlah_roll),
						tanggal, concat('Diambil untuk <b>',nama_barang,'</b>'), 'ask',
						t1.id, toko_id, supplier_id
				        FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_awal'
								AND gudang_id = $gudang_id 
								AND barang_id_sumber = $barang_id
								AND warna_id_sumber = $warna_id
							)tA
							LEFT JOIN nd_barang as b2
							ON tA.barang_id_hasil = b2.id
							LEFT JOIN nd_warna as w2
							ON tA.warna_id_hasil = w2.id

                        ) t1
                        LEFT JOIN nd_assembly_detail_sumber t2
                        ON t2.assembly_id = t1.id
						GROUP BY qty, toko_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						(qty), sum(jumlah_roll), 0, 
						tanggal, concat('Diambil dari <b>',nama_barang,'</b>'), 'asm',
						t1.id, toko_id, supplier_id
				        FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_awal'
								AND gudang_id = $gudang_id 
								AND barang_id_hasil = $barang_id
								AND warna_id_hasil = $warna_id
							)tA
							LEFT JOIN nd_barang as b1
							ON tA.barang_id_sumber = b1.id
							LEFT JOIN nd_warna as w1
							ON tA.warna_id_sumber = w1.id
                        ) t1
                        LEFT JOIN nd_assembly_detail_sumber t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY qty, toko_id, supplier_id
                        
				    )UNION(
                        SELECT barang_id, warna_id, gudang_id_before as gudang_id, t2.qty ,
                        0, sum(t2.jumlah_roll),  
                        tanggal, '', 'mb1' as tipe, t1.id, 
                        toko_id, t2.supplier_id
			        	FROM (
							SELECT *
                            FROM nd_mutasi_barang
                            WHERE tanggal >= '$tanggal_awal'
                            AND barang_id = $barang_id
                            AND warna_id = $warna_id
                            AND gudang_id_before = $gudang_id
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT qty, sum(jumlah_roll) as jumlah_roll, mutasi_barang_id, supplier_id
                            FROM nd_mutasi_barang_detail
                            GROUP BY qty, mutasi_barang_id, supplier_id
                        )t2
                        ON t2.mutasi_barang_id = t1.id
                        GROUP BY qty, toko_id, supplier_id
                    )UNION(
                        SELECT barang_id, warna_id, gudang_id_before as gudang_id, t2.qty ,
                        sum(t2.jumlah_roll), 0,
                        tanggal, '', 'mb1' as tipe, t1.id, 
                        toko_id, t2.supplier_id
			        	FROM (
							SELECT *
                            FROM nd_mutasi_barang
                            WHERE tanggal >= '$tanggal_awal'
                            AND barang_id = $barang_id
                            AND warna_id = $warna_id
                            AND gudang_id_after = $gudang_id
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT qty, sum(jumlah_roll) as jumlah_roll, mutasi_barang_id, supplier_id
                            FROM nd_mutasi_barang_detail
                            GROUP BY qty, mutasi_barang_id, supplier_id
                        )t2
                        ON t2.mutasi_barang_id = t1.id
                        GROUP BY qty, toko_id, supplier_id
                    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
                LEFT JOIN nd_toko tbl_d
                ON tbl_a.toko_id = tbl_d.id
                LEFT JOIN nd_supplier tbl_e
                ON tbl_a.supplier_id = tbl_e.id
                LEFT JOIN nd_satuan tbl_f
                ON tbl_b.packaging_id = tbl_f.id
                LEFT JOIN nd_satuan tbl_g
                ON tbl_b.satuan_id = tbl_g.id
				Where tbl_a.toko_id = $toko_id
                AND barang_id is not null
				GROUP BY qty, toko_id, supplier_id
				ORDER BY qty asc
				");
		
		return $query;
		// return $this->db->last_query();
	}


}
