<?php

class Inventory_Model extends CI_Model {

//=====================================================Stok & Kartu Stok======================================

	function get_stok_barang_list($select, $tanggal, $tanggal_awal){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan, satuan_id, tbl_e.nama as nama_packaging, packaging_id
				$select
				FROM(
					(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
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
					        	WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal'
					        	AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_pembelian
					        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
					        WHERE nd_pembelian.id is not null
					        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        FROM nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM nd_retur_jual_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal'
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
				        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        	FROM (
				        		SELECT barang_id, warna_id, keterangan, id, gudang_id
				        		FROM nd_penyesuaian_stok
					        	WHERE tipe_transaksi = 0
		                        AND tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
			        		)t1
							LEFT JOIN (
								SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
								FROM nd_penyesuaian_stok_qty
								GROUP BY penyesuaian_stok_id
								) t2
							ON t2.penyesuaian_stok_id = t1.id
	                        GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 2
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
						GROUP BY barang_id, warna_id, gudang_id_before
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				LEFT JOIN nd_satuan tbl_e
				ON tbl_b.packaging_id = tbl_e.id
				Where barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY nama_jual, warna_jual");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_list_2($select, $tanggal, $tanggal_awal, $condShown){

		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, 
				tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, 
				tbl_a.barang_id, tbl_a.warna_id, tbl_b.status_aktif as status_barang, 
				tbl_d.nama as nama_satuan, satuan_id, tbl_e.nama as nama_packaging, packaging_id,
				nd_barang_sku.id as sku_id, ifnull(isShown,1) as isShown, nd_barang_sku.id as sku_id
				$select
				FROM(
					(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, 
							sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
							CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
							tanggal, 1 as tipe
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
					        	WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal'
					        	AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_pembelian
					        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
					        WHERE nd_pembelian.id is not null
					        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id, tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 2
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 3
				        FROM (
							SELECT id, toko_id, penjualan_id, barang_id, warna_id, gudang_id, subqty, subroll, if(warna_id!=888,is_eceran,0) as is_eceran 
							FROM nd_penjualan_detail
						) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
						AND is_eceran = 0
				        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id, tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, 
						sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
						tanggal, 12
				        FROM nd_retur_jual_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal'
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
				        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id, tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id, 
						0 as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 
						tanggal, 13
				        FROM nd_retur_beli_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_beli
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_retur_beli
				        ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
				        LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) nd_penbelian_qty_detail
				        ON nd_penbelian_qty_detail.retur_beli_detail_id = nd_retur_beli_detail.id
				        WHERE nd_retur_beli.id is not null
				        GROUP BY barang_id, warna_id,nd_retur_beli_detail.gudang_id, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 5
				        	FROM (
				        		SELECT barang_id, warna_id, keterangan, id, gudang_id, tanggal
				        		FROM nd_penyesuaian_stok
					        	WHERE tipe_transaksi = 0
		                        AND tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
			        		)t1
							LEFT JOIN (
								SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
								FROM nd_penyesuaian_stok_qty
								GROUP BY penyesuaian_stok_id
								) t2
							ON t2.penyesuaian_stok_id = t1.id
	                        GROUP BY barang_id, warna_id, gudang_id, tanggal
				    )UNION(
				        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 6
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 7
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 2
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 8
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
						GROUP BY barang_id, warna_id, gudang_id_before, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						sum(qty) as qty_masuk, sum(jumlah_roll), 0, 0,
						tanggal, 10
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, group_concat(qty) as qty_data, 
								sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
                            FROM nd_stok_opname_detail
                           	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						WHERE status_aktif = 1
			        	AND tanggal <= '$tanggal'	
						GROUP BY barang_id, warna_id, gudang_id, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						0, 0, sum(qty), sum(jumlah_roll),
						tanggal, 11
				        FROM (
                            SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
							AND tipe != 3
                        ) t1
                        LEFT JOIN nd_mutasi_stok_eceran_qty t2
                        ON t2.mutasi_stok_eceran_id = t1.id
						GROUP BY barang_id, warna_id, gudang_id, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						0, 0, 
						sum(qty), sum(jumlah_roll),
						tanggal, 12
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_assembly_detail_sumber 
							GROUP BY assembly_id, supplier_id
							)t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						sum(qty), sum(jumlah_roll),
						0, 0, 
						tanggal, 13
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_assembly_detail_hasil 
							GROUP BY assembly_id, supplier_id
							)t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal
				    )
				) tbl_a
				LEFT JOIN (
					SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_stok
					FROM (
						SELECT barang_id, warna_id, gudang_id, stok_opname_id
						FROM nd_stok_opname_detail
						GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
					)t1
					LEFT JOIN nd_stok_opname t2
					ON t1.stok_opname_id = t2.id
					WHERE status_aktif = 1
					AND tanggal <= '$tanggal'	
					GROUP BY barang_id, warna_id, gudang_id
				) t_stok
				ON tbl_a.barang_id = t_stok.barang_id
				AND tbl_a.warna_id = t_stok.warna_id
				AND tbl_a.gudang_id = t_stok.gudang_id
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				LEFT JOIN nd_satuan tbl_e
				ON tbl_b.packaging_id = tbl_e.id
				LEFT JOIN nd_barang_sku 
				ON tbl_a.barang_id = nd_barang_sku.barang_id
				AND tbl_a.warna_id = nd_barang_sku.warna_id
				Where tbl_a.barang_id is not null
				AND isEceran=0
				$condShown
				GROUP BY tbl_a.barang_id, tbl_a.warna_id
				ORDER BY nama_jual, warna_jual");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_list_3($select, $tanggal, $tanggal_awal, $condShown){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual,  tbl_a.toko_id,
				tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, 
				tbl_a.barang_id, tbl_a.warna_id, tbl_b.status_aktif as status_barang, 
				tbl_d.nama as nama_satuan, satuan_id, tbl_e.nama as nama_packaging, packaging_id,
				nd_barang_sku.id as sku_id, ifnull(isShown,1) as isShown, nd_barang_sku.id as sku_id, tbl_f.nama as nama_toko
				$select
				FROM(
					(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, 
							sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
							CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
							tanggal, 1 as tipe, toko_id
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
					        	WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal'
					        	AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_pembelian
					        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
					        WHERE nd_pembelian.id is not null
					        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id, tanggal, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 2, toko_id
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 3, toko_id
				        FROM (
							SELECT id, toko_id, penjualan_id, barang_id, warna_id, gudang_id, subqty, subroll, if(warna_id!=888,is_eceran,0) as is_eceran 
							FROM nd_penjualan_detail
						) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
						AND is_eceran = 0
				        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id, tanggal, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, 
						sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
						tanggal, 12, toko_id
				        FROM nd_retur_jual_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal'
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
				        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id, 
						0 as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 
						tanggal, 13, toko_id
				        FROM nd_retur_beli_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_beli
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_retur_beli
				        ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
				        LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) nd_penbelian_qty_detail
				        ON nd_penbelian_qty_detail.retur_beli_detail_id = nd_retur_beli_detail.id
				        WHERE nd_retur_beli.id is not null
				        GROUP BY barang_id, warna_id,nd_retur_beli_detail.gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 5, toko_id
				        	FROM (
				        		SELECT barang_id, warna_id, keterangan, id, gudang_id, tanggal, toko_id
				        		FROM nd_penyesuaian_stok
					        	WHERE tipe_transaksi = 0
		                        AND tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
			        		)t1
							LEFT JOIN (
								SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
								FROM nd_penyesuaian_stok_qty
								GROUP BY penyesuaian_stok_id
								) t2
							ON t2.penyesuaian_stok_id = t1.id
	                        GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 6, toko_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	GROUP BY barang_id, warna_id, gudang_id, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 7, toko_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 2
						GROUP BY barang_id, warna_id, gudang_id, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 8, toko_id
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
						GROUP BY barang_id, warna_id, gudang_id_before, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						sum(qty) as qty_masuk, sum(jumlah_roll), 0, 0,
						tanggal, 10, toko_id
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, group_concat(qty) as qty_data, 
								sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id
                            FROM nd_stok_opname_detail
                           	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						WHERE status_aktif = 1
			        	AND tanggal <= '$tanggal'	
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						0, 0, sum(qty), sum(jumlah_roll),
						tanggal, 11, toko_id
				        FROM (
                            SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
							AND tipe != 3
                        ) t1
                        LEFT JOIN nd_mutasi_stok_eceran_qty t2
                        ON t2.mutasi_stok_eceran_id = t1.id
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						0, 0, 
						sum(qty), sum(jumlah_roll),
						tanggal, 12, toko_id
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_assembly_detail_sumber 
							GROUP BY assembly_id, supplier_id
							)t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						sum(qty), sum(jumlah_roll),
						0, 0, 
						tanggal, 13, toko_id
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_assembly_detail_hasil 
							GROUP BY assembly_id, supplier_id
							)t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )
				) tbl_a
				LEFT JOIN (
					SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_stok, toko_id
					FROM (
						SELECT barang_id, warna_id, gudang_id, stok_opname_id, toko_id
						FROM nd_stok_opname_detail
						GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id
					)t1
					LEFT JOIN nd_stok_opname t2
					ON t1.stok_opname_id = t2.id
					WHERE status_aktif = 1
					AND tanggal <= '$tanggal'	
					GROUP BY barang_id, warna_id, gudang_id, toko_id
				) t_stok
				ON tbl_a.barang_id = t_stok.barang_id
				AND tbl_a.warna_id = t_stok.warna_id
				AND tbl_a.gudang_id = t_stok.gudang_id
				AND tbl_a.toko_id = t_stok.toko_id
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				LEFT JOIN nd_satuan tbl_e
				ON tbl_b.packaging_id = tbl_e.id
				LEFT JOIN nd_toko tbl_f
				ON tbl_a.toko_id = tbl_f.id
				LEFT JOIN nd_barang_sku 
				ON tbl_a.barang_id = nd_barang_sku.barang_id
				AND tbl_a.warna_id = nd_barang_sku.warna_id
				Where tbl_a.barang_id is not null
				AND isEceran=0
				$condShown
				GROUP BY tbl_a.barang_id, tbl_a.warna_id, tbl_a.toko_id
				ORDER BY nama_jual, warna_jual");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_list_pertoko($select, $tanggal, $tanggal_awal, $condShown, $cond_toko){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual,  tbl_a.toko_id,
				tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, 
				tbl_a.barang_id, tbl_a.warna_id, tbl_b.status_aktif as status_barang, 
				tbl_d.nama as nama_satuan, satuan_id, tbl_e.nama as nama_packaging, packaging_id,
				nd_barang_sku.id as sku_id, ifnull(isShown,1) as isShown, nd_barang_sku.id as sku_id, tbl_f.nama as nama_toko
				$select
				FROM(
					(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, 
							sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
							CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
							tanggal, 1 as tipe, toko_id
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
					        	WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal'
					        	AND ifnull(tanggal_sj,tanggal) >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_pembelian
					        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
					        WHERE nd_pembelian.id is not null
					        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id, tanggal, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 2, toko_id
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 3, toko_id
				        FROM (
							SELECT id, toko_id, penjualan_id, barang_id, warna_id, gudang_id, subqty, subroll, if(warna_id!=888,is_eceran,0) as is_eceran 
							FROM nd_penjualan_detail
						) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
						AND is_eceran = 0
				        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id, tanggal, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, 
						sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
						tanggal, 12, toko_id
				        FROM nd_retur_jual_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal'
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
				        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id, 
						0 as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 
						tanggal, 13, toko_id
				        FROM nd_retur_beli_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_beli
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_retur_beli
				        ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
				        LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) nd_penbelian_qty_detail
				        ON nd_penbelian_qty_detail.retur_beli_detail_id = nd_retur_beli_detail.id
				        WHERE nd_retur_beli.id is not null
				        GROUP BY barang_id, warna_id,nd_retur_beli_detail.gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 5, toko_id
				        	FROM (
				        		SELECT barang_id, warna_id, keterangan, id, gudang_id, tanggal, toko_id
				        		FROM nd_penyesuaian_stok
					        	WHERE tipe_transaksi = 0
		                        AND tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
			        		)t1
							LEFT JOIN (
								SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
								FROM nd_penyesuaian_stok_qty
								GROUP BY penyesuaian_stok_id
								) t2
							ON t2.penyesuaian_stok_id = t1.id
	                        GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 
						CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 6, toko_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	GROUP BY barang_id, warna_id, gudang_id, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 7, toko_id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 2
						GROUP BY barang_id, warna_id, gudang_id, toko_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, 8, toko_id
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
						GROUP BY barang_id, warna_id, gudang_id_before, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						sum(qty) as qty_masuk, sum(jumlah_roll), 0, 0,
						tanggal, 10, toko_id
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, group_concat(qty) as qty_data, 
								sum(qty * if(jumlah_roll = 0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, toko_id
                            FROM nd_stok_opname_detail
                           	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						WHERE status_aktif = 1
			        	AND tanggal <= '$tanggal'	
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						0, 0, sum(qty), sum(jumlah_roll),
						tanggal, 11, toko_id
				        FROM (
                            SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
							AND tipe != 3
                        ) t1
                        LEFT JOIN nd_mutasi_stok_eceran_qty t2
                        ON t2.mutasi_stok_eceran_id = t1.id
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						0, 0, 
						sum(qty), sum(jumlah_roll),
						tanggal, 12, toko_id
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_assembly_detail_sumber 
							GROUP BY assembly_id, supplier_id
							)t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						sum(qty), sum(jumlah_roll),
						0, 0, 
						tanggal, 13, toko_id
				        FROM (
                            SELECT *
							FROM nd_assembly
							WHERE tanggal <= '$tanggal'	
							AND tanggal >= '$tanggal_awal'
                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_assembly_detail_hasil 
							GROUP BY assembly_id, supplier_id
							)t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal, toko_id
				    )
				) tbl_a
				LEFT JOIN (
					SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_stok, toko_id
					FROM (
						SELECT barang_id, warna_id, gudang_id, stok_opname_id, toko_id
						FROM nd_stok_opname_detail
						GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, toko_id
					)t1
					LEFT JOIN nd_stok_opname t2
					ON t1.stok_opname_id = t2.id
					WHERE status_aktif = 1
					AND tanggal <= '$tanggal'	
					GROUP BY barang_id, warna_id, gudang_id, toko_id
				) t_stok
				ON tbl_a.barang_id = t_stok.barang_id
				AND tbl_a.warna_id = t_stok.warna_id
				AND tbl_a.gudang_id = t_stok.gudang_id
				AND tbl_a.toko_id = t_stok.toko_id
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				LEFT JOIN nd_satuan tbl_e
				ON tbl_b.packaging_id = tbl_e.id
				LEFT JOIN nd_toko tbl_f
				ON tbl_a.toko_id = tbl_f.id
				LEFT JOIN nd_barang_sku 
				ON tbl_a.barang_id = nd_barang_sku.barang_id
				AND tbl_a.warna_id = nd_barang_sku.warna_id
				Where tbl_a.barang_id is not null
				AND isEceran=0
				$cond_toko
				$condShown
				GROUP BY tbl_a.barang_id, tbl_a.warna_id, tbl_a.toko_id
				ORDER BY nama_jual, warna_jual");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_list_ajax($aColumns, $sWhere, $sOrder, $sLimit, $cond, $select, $tanggal){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan
				$select
				FROM(
				(
			        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        FROM (
			        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
			        	FROM nd_pembelian_detail
			        	ORDER BY pembelian_id
			        ) nd_pembelian_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_pembelian
			        	WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal'
			        	AND status_aktif = 1
			        	) nd_pembelian
			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
			        WHERE nd_pembelian.id is not null
			        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id
			    )UNION(
			    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
		        	FROM nd_mutasi_barang
		        	WHERE tanggal <= '$tanggal'
		        	AND status_aktif = 1
			        GROUP BY barang_id, warna_id, gudang_id_after
			    )UNION(
			        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        FROM nd_penjualan_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_penjualan
			        	WHERE tanggal <= '$tanggal'
			        	AND status_aktif = 1
			        	) nd_penjualan
			        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			        LEFT JOIN (
			            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
			            FROM nd_penjualan_qty_detail
			            GROUP BY penjualan_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
			        where nd_penjualan.id is not null
			        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id
			    )UNION(
			    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        FROM nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE tanggal <= '$tanggal'
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
			        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id
			    )UNION(
			        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tipe_transaksi = 0
                        GROUP BY barang_id, warna_id, gudang_id
			    )UNION(
			        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
		        	FROM nd_penyesuaian_stok
		        	WHERE tanggal <= '$tanggal'
		        	AND tipe_transaksi = 1
		        	GROUP BY barang_id, warna_id, gudang_id
			    )UNION(
			        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
		        	FROM nd_penyesuaian_stok
		        	WHERE tanggal <= '$tanggal'
		        	AND tipe_transaksi = 2
					GROUP BY barang_id, warna_id, gudang_id
			    )UNION(
			    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
		        	FROM nd_mutasi_barang
		        	WHERE tanggal <= '$tanggal'
		        	AND status_aktif = 1
					GROUP BY barang_id, warna_id, gudang_id_before
			    )
			) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				Where barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY nama_jual, warna_jual
				) A			
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	function get_stok_barang_eceran_list($tanggal){
		$query = $this->db->query("SELECT barang_id,warna_id, gudang_id,  sum(tA.qty - ifnull(tB.qty,0) - ifnull(qty_mutasi,0) ) as qty_stok, toko_id
				FROM (
				    	SELECT stok_eceran_qty_id, tX.barang_id, tX.warna_id, tX.gudang_id, 
						if(tanggal >= ifnull(tanggal_so,'2018-01-01'),qty, 0 ) as qty, tipe, tX.toko_id
			        	FROM (
							(
								SELECT t1.id, toko_id, barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 1 as tipe, gudang_id, tanggal
								FROM (
									SELECT *
									FROM nd_mutasi_stok_eceran
									WHERE tanggal <= '$tanggal'
									AND status_aktif = 1
								)t1
								LEFT JOIN nd_mutasi_stok_eceran_qty t2
								ON t2.mutasi_stok_eceran_id = t1.id
							)UNION(
								SELECT tB.id, toko_id, barang_id, warna_id, tA.id as stok_eceran_qty_id, qty, 2 , gudang_id, tanggal
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
							SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_so, toko_id
							FROM nd_stok_opname_eceran tA
							LEFT JOIN (
								SELECT *
								FROM nd_stok_opname
								WHERE tanggal <= '$tanggal'
								AND status_aktif = 1
							) tB
							ON tA.stok_opname_id = tB.id
							WHERE tB.id is not null
							GROUP BY barang_id, warna_id, gudang_id
						) tY
						ON tX.barang_id = tY.barang_id
						AND tX.warna_id = tY.warna_id
						AND tX.gudang_id = tY.gudang_id
						AND tX.toko_id = tY.toko_id
					)tA
					LEFT JOIN (
						SELECT stok_eceran_qty_id, sum(qty) as qty, eceran_source, toko_id as toko_id_jual
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
							GROUP BY stok_eceran_qty_id, eceran_source

					)tB
					ON tA.stok_eceran_qty_id = tB.stok_eceran_qty_id
					AND tA.tipe = tB.eceran_source
					AND tA.toko_id = tB.toko_id_jual
					LEFT JOIN (
							SELECT sum(qty) as qty_mutasi, mutasi_stok_eceran_qty_source_id
							FROM nd_mutasi_stok_eceran_qty
							WHERE mutasi_stok_eceran_qty_source_id is not null
							GROUP BY mutasi_stok_eceran_qty_source_id
					)tC
					ON tA.stok_eceran_qty_id = tC.mutasi_stok_eceran_qty_source_id
                    WHERE tA.qty > 0
					GROUP BY barang_id, warna_id, gudang_id, toko_id
				");
		
		return $query->result();	
	}

	function get_stok_barang_eceran_detail($barang_id, $warna_id, $toko_id, $tanggal, $tanggal_awal){
		$query = $this->db->query("SELECT barang_id,warna_id, gudang_id, tA.stok_eceran_qty_id,  tA.qty as qty_masuk, 
		ifnull(tB.qty,0) as qty_jual , ifnull(qty_mutasi,0) as qty_mutasi , toko_id, toko_id_jual, ROUND((tA.qty - ifnull(tB.qty,0) - ifnull(qty_mutasi,0)),0) as sisa, qty_data_jual
			FROM (
				SELECT stok_eceran_qty_id, tX.barang_id, tX.warna_id, tX.gudang_id, 
				if(tanggal >= ifnull(tanggal_so,'$tanggal_awal'),qty, 0 ) as qty, tipe, tX.toko_id
				FROM (
					(
						SELECT t1.id, toko_id, barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 1 as tipe, gudang_id, tanggal
						FROM (
							SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal <= '$tanggal'
							AND status_aktif = 1
						)t1
						LEFT JOIN nd_mutasi_stok_eceran_qty t2
						ON t2.mutasi_stok_eceran_id = t1.id
					)UNION(
						SELECT tB.id, toko_id, barang_id, warna_id, tA.id as stok_eceran_qty_id, qty, 2 , gudang_id, tanggal
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
					SELECT barang_id, warna_id, gudang_id, max(tanggal) as tanggal_so, toko_id
					FROM nd_stok_opname_eceran tA
					LEFT JOIN (
						SELECT *
						FROM nd_stok_opname
						WHERE tanggal <= '$tanggal'
						AND status_aktif = 1
					) tB
					ON tA.stok_opname_id = tB.id
					WHERE tB.id is not null
					GROUP BY barang_id, warna_id, gudang_id
				) tY
				ON tX.barang_id = tY.barang_id
				AND tX.warna_id = tY.warna_id
				AND tX.gudang_id = tY.gudang_id
				AND tX.toko_id = tY.toko_id
			)tA
			LEFT JOIN (
				SELECT stok_eceran_qty_id, sum(qty) as qty, eceran_source, toko_id as toko_id_jual, group_concat(qty) as qty_data_jual
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
					GROUP BY stok_eceran_qty_id, eceran_source, toko_id_jual

			)tB
			ON tA.stok_eceran_qty_id = tB.stok_eceran_qty_id
			AND tA.tipe = tB.eceran_source
			AND tA.toko_id = tB.toko_id_jual
			LEFT JOIN (
					SELECT sum(qty) as qty_mutasi, mutasi_stok_eceran_qty_source_id
					FROM nd_mutasi_stok_eceran_qty
					WHERE mutasi_stok_eceran_qty_source_id is not null
					GROUP BY mutasi_stok_eceran_qty_source_id
			)tC
			ON tA.stok_eceran_qty_id = tC.mutasi_stok_eceran_qty_source_id
			WHERE tA.qty > 0
			AND barang_id = '$barang_id'
			AND warna_id = '$warna_id'
			AND toko_id = '$toko_id'
			");
			
			return $query->result();
	}

//===============================================================================================

	function get_stok_barang_all_detail($tanggal_end, $tanggal_awal){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT barang_id, warna_id, nama_barang, warna_jual, group_concat(qty ORDER BY qty asc SEPARATOR '??') as qty, group_concat(jumlah_roll ORDER BY qty asc SEPARATOR '??') as jumlah_roll,sum(jumlah_roll) as total_roll, gudang_id, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as total_qty
			FROM (
				SELECT nama as nama_barang, warna_beli, warna_jual, qty, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll, barang_id, warna_id, gudang_id
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, tanggal, no_faktur, 'a' as tipe, t2.id as id
				        FROM (
				        	SELECT a.id, pembelian_id, barang_id, warna_id, b.qty, b.jumlah_roll
				        	FROM (
					        	SELECT *
					        	FROM nd_pembelian_detail
				        		)a
							LEFT JOIN nd_pembelian_qty_detail b
							ON b.pembelian_detail_id = a.id
				        	) t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	AND tanggal <= '$tanggal_end'
				        	) t2
				        ON t1.pembelian_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY barang_id, warna_id, gudang_id,qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 0, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, no_faktur, 'j' as tipe, t2.id as id
				        FROM (
				        	SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id
				        	FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	)a
							LEFT JOIN nd_penjualan_qty_detail b
							ON b.penjualan_detail_id = a.id
							) t1
				        LEFT JOIN (
				        	SELECT id, tanggal, no_faktur
				        	FROM nd_penjualan
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	
				        	AND tanggal <= '$tanggal_end'
				        	) t2
				        ON t1.penjualan_id = t2.id
				        WHERE t2.id is not null
				        GROUP BY barang_id, warna_id, gudang_id,qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , sum(jumlah_roll), 0, tanggal, keterangan, 'ps0' as tipe, id
			        	FROM (
							SELECT id, tanggal, barang_id, warna_id, gudang_id, keterangan
							FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 0
				        	) a
						LEFT JOIN (
							SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat(qty,'??', jumlah_roll,'??', id) SEPARATOR '--') as data_qty, penyesuaian_stok_id
							FROM nd_penyesuaian_stok_qty
							GROUP BY qty, penyesuaian_stok_id
							) t1
						ON a.id = t1.penyesuaian_stok_id
				        GROUP BY barang_id, warna_id, gudang_id,qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , sum(jumlah_roll), 0, tanggal, keterangan, 'ps1' as tipe, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	
			        	AND tanggal <= '$tanggal_end'
		        		AND tipe_transaksi = 1
				        GROUP BY barang_id, warna_id, gudang_id,qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,0, sum(jumlah_roll),  tanggal, keterangan, 'ps2' as tipe, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	
			        	AND tanggal <= '$tanggal_end'
		        		AND tipe_transaksi = 2
				        GROUP BY barang_id, warna_id, gudang_id,qty
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				GROUP BY barang_id, warna_id, qty
			)result
			WHERE jumlah_roll != 0
			GROUP BY barang_id, warna_id, gudang_id
			ORDER BY nama_barang, warna_jual asc

				");
		
			return $query->result();
		// return $this->db->last_query();
	}


//===============================================================================================


	function get_stok_barang_list_by_barang($select, $tanggal, $cond_barang){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan
				$select
				FROM(
					(
				        SELECT barang_id, warna_id, tA.gudang_id, CAST(sum(qty) as DECIMAL(15,2)) as qty_masuk, 
						sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE ifnull(tanggal_sj,tanggal) <= '$tanggal'
				        	AND status_aktif = 1
				        	) tA
				        LEFT JOIN nd_pembelian_detail tB
				        ON tB.pembelian_id = tA.id
						LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
				            FROM nd_pembelian_qty_detail
				            GROUP BY pembelian_detail_id
				            ) tC
				        ON tC.pembelian_detail_id = tB.id
				        $cond_barang
				        GROUP BY barang_id, warna_id, tA.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_mutasi_barang
				        $cond_barang
				        GROUP BY barang_id, warna_id, gudang_id_after
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        LEFT JOIN nd_penjualan_detail
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        $cond_barang
				        GROUP BY barang_id, warna_id,nd_penjualan_detail.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_retur_jual
				        LEFT JOIN nd_retur_jual_detail
				        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
				        LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
				        $cond_barang
				        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
			        	) nd_penyesuaian_stok
				        $cond_barang
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal <= '$tanggal'
				        	AND tipe_transaksi = 1
			        	) nd_penyesuaian_stok
				        $cond_barang
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal <= '$tanggal'
				        	AND tipe_transaksi = 2
			        	) nd_penyesuaian_stok
				        $cond_barang
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_mutasi_barang
				        $cond_barang
						GROUP BY barang_id, warna_id, gudang_id_before
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				Where barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY nama_jual");
		
		return $query->result();
		// return $this->db->last_query();

	}

	function get_stok_barang_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal){
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
							AND is_eceran = 0
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

	function get_stok_barang_satuan_awal($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_awal, $stok_opname_id){
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
                        AND gudang_id = $gudang_id
                        AND tanggal < '$tanggal_start'
				        AND tanggal >= '$tanggal_awal'
						GROUP BY barang_id_sumber, warna_id_sumber, gudang_id
                    )UNION(
                        SELECT barang_id_hasil, warna_id_hasil, gudang_id, sum(qty_hasil), sum(jumlah_roll_hasil) , 0 , 0, tanggal, id , 12
				        FROM nd_assembly
                        WHERE barang_id_hasil =  $barang_id
                        AND warna_id_hasil = $warna_id
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

	function get_stok_barang_detail($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT qty, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, tanggal, no_faktur, 'a' as tipe, t2.id as id
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
				        GROUP BY qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 0, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, no_faktur, 'j' as tipe, t2.id as id
				        FROM (
				        	SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id
				        	FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
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
				        GROUP BY qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , sum(jumlah_roll), 0, tanggal, keterangan, 'ps0' as tipe, id
			        	FROM (
							SELECT id, tanggal, barang_id, warna_id, gudang_id, keterangan
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
						GROUP BY qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , sum(jumlah_roll), 0, tanggal, keterangan, 'ps1' as tipe, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 1
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,0, sum(jumlah_roll),  tanggal, keterangan, 'ps2' as tipe, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 2
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				GROUP BY qty
				ORDER BY qty asc
				");
		
		return $query->result();
	}

	function get_stok_barang_detail_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT qty, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, tanggal, no_faktur, 'a' as tipe, t2.id as id
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
				        GROUP BY qty, tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 0, sum(jumlah_roll) as jumlah_roll_keluar, tanggal, no_faktur, 'j' as tipe, t2.id as id
				        FROM (
				        	SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id
				        	FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
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
				        GROUP BY qty, tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , sum(jumlah_roll), 0, tanggal, keterangan, 'ps0' as tipe, id
			        	FROM (
							SELECT id, tanggal, barang_id, warna_id, gudang_id, keterangan
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
						GROUP BY qty, tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , sum(jumlah_roll), 0, tanggal, keterangan, 'ps1' as tipe, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 1
						GROUP BY tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,0, sum(jumlah_roll),  tanggal, keterangan, 'ps2' as tipe, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 2
						GROUP BY tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						qty as qty_masuk, sum(jumlah_roll), 0,
						tanggal,'so', 'so', t1.id
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
                            FROM nd_stok_opname_detail
                            WHERE barang_id = $barang_id
                            AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							GROUP BY qty, barang_id, warna_id, gudang_id, stok_opname_id
                        ) t1
                        LEFT JOIN (
							SELECT *
							FROM nd_stok_opname
							WHERE tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal_end'
							) t2
                        ON t1.stok_opname_id = t2.id
						WHERE t2.status_aktif = 1
						AND t2.id is not null
						GROUP BY qty, tanggal
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				GROUP BY qty
				ORDER BY qty asc
				");
		
		return $query->result();
	}

	function get_last_opname($barang_id, $warna_id, $gudang_id, $tanggal_start){
		$query = $this->db->query("SELECT tanggal, t2.id
			FROM (
				SELECT *
				FROM nd_stok_opname_detail
				WHERE barang_id = $barang_id
				AND warna_id = $warna_id
				AND gudang_id = $gudang_id
				AND qty != 0
				) t1
			LEFT JOIN (
				SELECT *
				FROM nd_stok_opname
				WHERE tanggal <= '$tanggal_start'
				AND status_aktif = 1
				) t2
			ON t1.stok_opname_id = t2.id
			WHERE t2.id is not null
			ORDER BY t2.tanggal desc
			LIMIT 1
			", false);

		return $query->result();
	}

//==========================================rekap=========================================================

	function get_stok_barang_list_rekap($select, $tanggal){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan
				$select
				FROM(
				(
				        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
				        	FROM nd_pembelian_detail
				        	ORDER BY pembelian_id
				        ) nd_pembelian_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_pembelian
				        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
				        WHERE nd_pembelian.id is not null
				        GROUP BY barang_id, nd_pembelian.gudang_id
			    )UNION(
			    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
		        	FROM nd_mutasi_barang
		        	WHERE tanggal <= '$tanggal'
		        	AND status_aktif = 1
			        GROUP BY barang_id,  gudang_id_after
			    )UNION(
			        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        FROM nd_penjualan_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_penjualan
			        	WHERE tanggal <= '$tanggal'
			        	AND status_aktif = 1
			        	) nd_penjualan
			        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			        LEFT JOIN (
			            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
			            FROM nd_penjualan_qty_detail
			            GROUP BY penjualan_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
			        where nd_penjualan.id is not null
			        GROUP BY barang_id, nd_penjualan_detail.gudang_id
			    )UNION(
			    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        FROM nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE tanggal <= '$tanggal'
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
			        GROUP BY barang_id, nd_retur_jual_detail.gudang_id
			    )UNION(
			        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tipe_transaksi = 0
                        GROUP BY barang_id, gudang_id
			    )UNION(
			        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
		        	FROM nd_penyesuaian_stok
		        	WHERE tanggal <= '$tanggal'
		        	AND tipe_transaksi = 1
		        	GROUP BY barang_id, gudang_id
			    )UNION(
			        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
		        	FROM nd_penyesuaian_stok
		        	WHERE tanggal <= '$tanggal'
		        	AND tipe_transaksi = 2
					GROUP BY barang_id, gudang_id
			    )UNION(
			    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
		        	FROM nd_mutasi_barang
		        	WHERE tanggal <= '$tanggal'
		        	AND status_aktif = 1
					GROUP BY barang_id, gudang_id_before
			    )
			) tbl_a
			LEFT JOIN nd_barang tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna tbl_c
			ON tbl_a.warna_id = tbl_c.id
			LEFT JOIN nd_satuan tbl_d
			ON tbl_b.satuan_id = tbl_d.id
			Where barang_id is not null
			GROUP BY barang_id
			ORDER BY nama_jual");
		
		return $query->result();
		// return $this->db->last_query();
	}


//=====================================================Stok + HPP======================================

	function get_stok_barang_list_hpp($select, $tanggal){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, tbl_a.barang_id, tbl_a.warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan,hpp
				$select
				FROM(
					(
				        SELECT barang_id, warna_id, nd_pembelian.gudang_id, CAST(sum(qty) as DECIMAL(15,2)) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_pembelian
				        LEFT JOIN nd_pembelian_detail
				        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
				        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_mutasi_barang
				        GROUP BY barang_id, warna_id, gudang_id_after
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        LEFT JOIN nd_penjualan_detail
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        GROUP BY barang_id, warna_id,nd_penjualan_detail.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_retur_jual
				        LEFT JOIN nd_retur_jual_detail
				        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
				        LEFT JOIN (
				            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
				        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
			        	) nd_penyesuaian_stok
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal <= '$tanggal'
				        	AND tipe_transaksi = 1
			        	) nd_penyesuaian_stok
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal <= '$tanggal'
				        	AND tipe_transaksi = 2
			        	) nd_penyesuaian_stok
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) nd_mutasi_barang
						GROUP BY barang_id, warna_id, gudang_id_before
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				LEFT JOIN (
					SELECT sum(qty_beli) as qty_beli, sum(total_beli) as total_beli, barang_id, warna_id, TRUNCATE(sum(total_beli)/sum(qty_beli),2) as hpp
					FROM (
						(
							SELECT sum(qty) as qty_beli, sum(qty*harga_beli) as total_beli, barang_id, warna_id
							FROM (
								SELECT *
								FROM nd_pembelian
								WHERE tanggal <= '$tanggal'
								) nd_pembelian
							LEFT JOIN nd_pembelian_detail
							ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
							WHERE barang_id is not null
							GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
						)UNION(
							SELECT sum(qty) as qty_beli, sum(qty*harga_stok_awal) as total_beli, stok_awal.barang_id, stok_awal.warna_id
							FROM (
								SELECT sum(qty) as qty, barang_id, warna_id
								FROM nd_penyesuaian_stok
								WHERE tipe_transaksi = 0
								GROUP BY barang_id, warna_id
								) stok_awal 
							LEFT JOIN nd_stok_awal_item_harga
							ON stok_awal.barang_id = nd_stok_awal_item_harga.barang_id
							GROUP BY barang_id, warna_id
						) 
					)a
					GROUP BY barang_id, warna_id
				) tbl_e
				ON tbl_a.barang_id = tbl_e.barang_id
				AND tbl_a.warna_id = tbl_e.warna_id
				Where tbl_a.barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY nama_jual");
		
		return $query->result();
		// return $this->db->last_query();

	}


//===================================================stok pertoko============================================= 

//=======================================================mutasi================================================


	// function cek_barang_qty($barang_id, $warna_id, $gudang_id){
	// 	$query = $this->db->query("SELECT sum(qty_masuk) - sum(qty_keluar) as qty, sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar) as jumlah_roll
	// 			FROM(
	// 				(
	// 			        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal
	// 			        FROM (
	// 			        	SELECT *
	// 			        	FROM nd_pembelian
	// 			        	WHERE gudang_id = $gudang_id
	// 			        	AND status_aktif = 1
	// 			        	) nd_pembelian
	// 			        LEFT JOIN (
	// 			        	SELECT *
	// 			        	FROM nd_pembelian_detail
	// 			        	WHERE barang_id = $barang_id
	// 			        	AND warna_id = $warna_id
	// 			        	) nd_pembelian_detail
	// 			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
				        
	// 			    )UNION(
	// 			    	SELECT barang_id,warna_id, gudang_id_after, sum(qty) as qty_mutasi_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal
	//         			FROM nd_mutasi_barang
	//         			WHERE gudang_id_after = $gudang_id
	//         			AND barang_id = $barang_id
	//         			AND warna_id = $warna_id
	//         			AND status_aktif = 1
	// 			    )UNION(
	// 			        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal
	// 			        FROM (
	// 			        	SELECT *
	// 			        	FROM nd_penjualan_detail 
	// 			        	WHERE gudang_id = $gudang_id 
	// 			        	AND barang_id = $barang_id
	// 			        	AND warna_id = $warna_id
	// 			        	) nd_penjualan_detail
	// 			        LEFT JOIN (
	// 			        	SELECT *
	// 			        	FROM nd_penjualan
	// 			        	WHERE status_aktif = 1
	// 			        	) nd_penjualan
	// 			        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
	// 			        LEFT JOIN (
	// 			            SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
	// 			            FROM nd_penjualan_qty_detail
	// 			            GROUP BY penjualan_detail_id
	// 			            ) nd_penjualan_qty_detail
	// 			        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
	// 			        WHERE nd_penjualan.id is not null
	// 			    )UNION(
	// 			    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal
	// 			        FROM (
	// 			        	SELECT *
	// 			        	FROM nd_retur_jual_detail 
	// 			        	WHERE gudang_id = $gudang_id 
	// 			        	AND barang_id = $barang_id
	// 			        	AND warna_id = $warna_id
	// 			        	) nd_retur_jual_detail
	// 			        LEFT JOIN (
	// 			        	SELECT *
	// 			        	FROM nd_retur_jual
	// 			        	where status_aktif = 1
	// 			        	) nd_retur_jual
	// 			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
	// 			        LEFT JOIN (
	// 			            SELECT sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
	// 			            FROM nd_retur_jual_qty
	// 			            GROUP BY retur_jual_detail_id
	// 			            ) nd_retur_jual_qty
	// 			        ON nd_retur_jual_qty.retur_jual_detail_id = nd_retur_jual_detail.id
	// 			        WHERE barang_id is not null 
	// 			        AND warna_id is not null
	// 			        AND nd_retur_jual.id is not null
	// 			    )UNION(
	// 			    	SELECT barang_id, warna_id, gudang_id_before,0 as qty_masuk, 0 as jumlah_roll_masuk, sum(qty), sum(jumlah_roll) as jumlah_roll_keluar, tanggal
	//         			FROM nd_mutasi_barang
	//         			WHERE gudang_id_before = $gudang_id
	//         			AND barang_id = $barang_id
	//         			AND warna_id = $warna_id
	//         			AND status_aktif = 1
 //        			)UNION(
	// 			        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,tanggal
	// 			        FROM (
	// 			        	SELECT *
	// 			        	FROM nd_penyesuaian_stok
	// 			        	WHERE tipe_transaksi = 0
	// 			        	AND barang_id = $barang_id
	// 	        			AND warna_id = $warna_id
	// 	        			AND gudang_id = $gudang_id
	// 		        	) nd_penyesuaian_stok
	// 					GROUP BY barang_id, warna_id, gudang_id
	// 			    )UNION(
	// 			        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,tanggal
	// 			        FROM (
	// 			        	SELECT *
	// 			        	FROM nd_penyesuaian_stok
	// 			        	WHERE tipe_transaksi = 1
	// 			        	AND barang_id = $barang_id
	// 	        			AND warna_id = $warna_id
	// 	        			AND gudang_id = $gudang_id
	// 		        	) nd_penyesuaian_stok
	// 					GROUP BY barang_id, warna_id, gudang_id
	// 			    )UNION(
	// 			        SELECT barang_id, warna_id, gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal
	// 			        FROM (
	// 			        	SELECT *
	// 			        	FROM nd_penyesuaian_stok
	// 			        	WHERE tipe_transaksi = 2
	// 			        	AND barang_id = $barang_id
	// 	        			AND warna_id = $warna_id
	// 	        			AND gudang_id = $gudang_id
	// 		        	) nd_penyesuaian_stok
	// 					GROUP BY barang_id, warna_id, gudang_id
	// 			    )
	// 		) tbl_a
	// 		LEFT JOIN nd_barang tbl_b
	// 		ON tbl_a.barang_id = tbl_b.id
	// 		LEFT JOIN nd_warna tbl_c
	// 		ON tbl_a.warna_id = tbl_c.id
	// 	");
	// 	return $query->result();
	// }

	function cek_barang_qty($gudang_id, $toko_id, $barang_id,$warna_id, $supplier_id, $tanggal_awal, $stok_opname_id, $tanggal){
		$query = $this->db->query("SELECT sum(ifnull(qty_masuk,0)) - sum(ifnull(qty_keluar,0)) as qty, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll, gudang_id, $toko_id
				FROM(
					(
				        SELECT barang_id, warna_id, t1.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal
				        FROM (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE gudang_id = $gudang_id
				        	AND tanggal >= '$tanggal_awal'
							AND supplier_id = $supplier_id
							AND toko_id = $toko_id
							AND tanggal <= '$tanggal'
				        	AND status_aktif = 1
				        	) t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	) t2
				        ON t2.pembelian_id = t1.id
						LEFT JOIN (
                            SELECT pembelian_detail_id, sum(qty * if(jumlah_roll != 0, jumlah_roll, 1)) as qty, sum(jumlah_roll) as jumlah_roll
                            FROM nd_pembelian_qty_detail
                            GROUP BY pembelian_detail_id
                            ) t3
                        ON t3.pembelian_detail_id = t2.id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(t2.qty) as qty_masuk, sum(t2.jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE gudang_id_after = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
							AND toko_id = $toko_id
				        	AND status_aktif = 1
				        	) t1
							LEFT JOIN nd_mutasi_barang_detail t2
							ON t1.id = t2.mutasi_barang_id
							WHERE supplier_id=$supplier_id
							GROUP BY mutasi_barang_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, 
						sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail 
				        	WHERE gudang_id = $gudang_id 
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
							AND toko_id = $toko_id
				        	) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
							WHERE supplier_id = $supplier_id
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        WHERE nd_penjualan.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal
				        FROM (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
				        	) nd_retur_jual
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual_detail
				        	WHERE gudang_id = $gudang_id
							AND toko_id = $toko_id
				        	AND barang_id = $barang_id
							AND supplier_id = $supplier_id
				        	AND warna_id = $warna_id
				        	) nd_retur_jual_detail
				        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) nd_retur_jual_qty
				        ON nd_retur_jual_qty.retur_jual_detail_id = nd_retur_jual_detail.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, 0 as qty_masuk, 0 as jumlah_roll_masuk, sum(t2.qty) as qty_keluar, sum(t2.jumlah_roll) as jumlah_roll_keluar, tanggal
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE gudang_id_before = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
							AND toko_id = $toko_id
				        	AND status_aktif = 1
				        	)  t1
							LEFT JOIN nd_mutasi_barang_detail t2
							ON t1.id = t2.mutasi_barang_id
							WHERE supplier_id=$supplier_id
							GROUP BY mutasi_barang_id, supplier_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,tanggal
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
				        	AND barang_id = $barang_id
		        			AND warna_id = $warna_id
							AND supplier_id = $supplier_id
		        			AND gudang_id = $gudang_id
				        	AND tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
							AND toko_id = $toko_id
			        	) nd_penyesuaian_stok
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,tanggal
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 1
				        	AND barang_id = $barang_id
		        			AND warna_id = $warna_id
							AND supplier_id = $supplier_id
		        			AND gudang_id = $gudang_id
				        	AND tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
							AND toko_id = $toko_id
			        	) nd_penyesuaian_stok
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, tanggal
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 2
				        	AND barang_id = $barang_id
		        			AND warna_id = $warna_id
							AND supplier_id = $supplier_id
		        			AND gudang_id = $gudang_id
				        	AND tanggal >= '$tanggal_awal'
							AND tanggal <= '$tanggal'
							AND toko_id = $toko_id
			        	) nd_penyesuaian_stok
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal
				    	FROM (
				        	SELECT *
				        	FROM nd_stok_opname_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
							AND supplier_id = $supplier_id
				        	AND gudang_id = $gudang_id
				        	AND stok_opname_id = $stok_opname_id
							AND toko_id = $toko_id
				        	) t1
						LEFT JOIN nd_stok_opname t2
						ON t1.stok_opname_id = t2.id
						LEFT JOIN nd_gudang
						ON t1.gudang_id = nd_gudang.id
						WHERE t2.status_aktif = 1
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				WHERE barang_id is not null
				AND warna_id is not null
		");
		return $query->result();
	}

	function cek_total_barang_qty_eceran($gudang_id, $toko_id, $barang_id,$warna_id, $supplier_id,  $tanggal_awal, $stok_opname_id, $tanggal){
		$query = $this->db->query("SELECT sum(ifnull(tA.qty,0)) - sum(ifnull(tB.qty,0)) as qty, tA.tipe, gudang_id
				FROM (
					(
						SELECT barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 1 as tipe, gudang_id
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
						SELECT barang_id, warna_id, id as stok_eceran_qty_id, qty, 2 , gudang_id
						FROM nd_stok_opname_eceran
						WHERE barang_id = $barang_id
						AND warna_id = $warna_id
						AND gudang_id = $gudang_id
						AND stok_opname_id = '$stok_opname_id'
						AND toko_id = $toko_id
					)
					)tA
					LEFT JOIN (
						SELECT stok_eceran_qty_id, sum(qty) as qty, eceran_source
						FROM (
							SELECT *
							FROM nd_penjualan_qty_detail
							WHERE stok_eceran_qty_id is not null
							AND supplier_id = $supplier_id
							)t1
							LEFT JOIN nd_penjualan_detail t2
							ON t1.penjualan_detail_id=t2.id
							LEFT JOIN nd_penjualan t3
							ON t2.penjualan_id=t3.id
							WHERE status_aktif=1
							AND t2.toko_id = $toko_id
							GROUP BY stok_eceran_qty_id

					)tB
					ON tA.stok_eceran_qty_id = tB.stok_eceran_qty_id
					AND tA.tipe = tB.eceran_source
					WHERE barang_id is not null
					AND warna_id is not null
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function cek_barang_qty_eceran($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $tanggal){
		$query = $this->db->query("SELECT tA.stok_eceran_qty_id, tA.qty - ifnull(tB.qty,0) as qty, tA.tipe, gudang_id
				FROM (
					(
						SELECT barang_id, warna_id, t2.id as stok_eceran_qty_id, qty, 1 as tipe, gudang_id
						FROM (
							SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal >= '$tanggal_awal'
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
							AND supplier_id = $supplier_id
							AND status_aktif = 1
						)t1
						LEFT JOIN nd_mutasi_stok_eceran_qty t2
						ON t2.mutasi_stok_eceran_id = t1.id
					)UNION(
						SELECT barang_id, warna_id, id as stok_eceran_qty_id, qty, 2 , gudang_id
						FROM nd_stok_opname_eceran
						WHERE barang_id = $barang_id
						AND warna_id = $warna_id
						AND supplier_id = $supplier_id
						AND gudang_id = $gudang_id
						AND stok_opname_id = '$stok_opname_id'
					)
					)tA
					LEFT JOIN (
						SELECT stok_eceran_qty_id, sum(qty) as qty, eceran_source
						FROM (
							SELECT *
							FROM nd_penjualan_qty_detail
							WHERE stok_eceran_qty_id is not null
							AND supplier_id = $supplier_id
							)t1
							LEFT JOIN nd_penjualan_detail t2
							ON t1.penjualan_detail_id=t2.id
							LEFT JOIN nd_penjualan t3
							ON t2.penjualan_id=t3.id
							WHERE status_aktif=1
							GROUP BY stok_eceran_qty_id

					)tB
					ON tA.stok_eceran_qty_id = tB.stok_eceran_qty_id
					AND tA.tipe = tB.eceran_source
					WHERE barang_id is not null
					AND warna_id is not null
				");
		
		return $query->result();
		// return $this->db->last_query();
	}


	function get_mutasi_list_detail($tanggal){
		$query = $this->db->query("SELECT tbl_a.id, tbl_b.nama as nama_gudang_before, tbl_c.nama as nama_gudang_after,  tbl_d.nama as nama_barang, tbl_e.warna_beli as nama_warna 
			FROM (
				SELECT *
				FROM nd_mutasi_barang
				WHERE tanggal = '$tanggal'
				) tbl_a
			LEFT JOIN nd_gudang as tbl_b
			ON tbl_a.gudang_id_before = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id_after = tbl_c.id
			LEFT JOIN nd_barang as tbl_d
			ON tbl_a.barang_id = tbl_d.id
			LEFT JOIN nd_warna as tbl_e
			ON tbl_a.warna_id = tbl_e.id
		");
		return $query->result();
	}

	function get_mutasi_barang_ajax($aColumns, $sWhere/*, $sOrder*/, $sLimit, $cond){
		$query = $this->db->query("SELECT *
			FROM (
				-- @row := @row + 1 as idx, 
				SELECT a.status_aktif, tanggal, concat_ws(' ',e.nama_jual, f.warna_jual)  as nama_barang , c.nama as gudang_before, 
				d.nama as gudang_after, qty, jumlah_roll, concat_ws('??',a.id,gudang_id_before, gudang_id_after, barang_id, warna_id, ifnull(qty_data,'-'), sku_id, a.toko_id) as data
				FROM (
					SELECT * 
					FROM nd_mutasi_barang
					$cond
					) a
				LEFT JOIN nd_gudang c
				ON a.gudang_id_before = c.id
				LEFT JOIN nd_gudang d
				ON a.gudang_id_after = d.id 
				LEFT JOIN nd_barang e
				ON a.barang_id = e.id
				LEFT JOIN nd_warna f
				ON a.warna_id = f.id
				LEFT JOIN (
					SELECT group_concat(concat(t1.id,'|',qty,'|',supplier_id,'|', nd_supplier.nama) SEPARATOR '==') as qty_data, mutasi_barang_id
					FROM nd_mutasi_barang_detail t1
					LEFT JOIN nd_supplier
					ON t1.supplier_id = nd_supplier.id
					GROUP BY mutasi_barang_id
				)a2
				ON a.id = a2.mutasi_barang_id
				order by a.id desc
				) A			
			$sWhere
            $sLimit
			", false);

		return $query;
	}

	function get_mutasi_barang($cond){
		$query = $this->db->query("SELECT a.status_aktif, tanggal, concat_ws(' ',e.nama_jual, f.warna_jual)  as nama_barang , c.nama as gudang_before, d.nama as gudang_after, qty, jumlah_roll
				FROM (
					SELECT * 
					-- , (SELECT @row := 0)
					FROM nd_mutasi_barang
					$cond
					) a
				LEFT JOIN nd_gudang c
				ON a.gudang_id_before = c.id
				LEFT JOIN nd_gudang d
				ON a.gudang_id_after = d.id 
				LEFT JOIN nd_barang e
				ON a.barang_id = e.id
				LEFT JOIN nd_warna f
				ON a.warna_id = f.id
				order by tanggal desc
				
			", false);

		return $query->result();
		// return $this->db->last_query();
	}

//==================================mutasi stok awal==================

	function get_stok_awal(){
		$query = $this->db->query("SELECT a.*,t1.*, e.nama_jual as nama_barang, f.warna_jual as nama_warna, g.nama as nama_satuan, h.nama as nama_gudang, g2.nama as nama_packaging
				FROM (
					SELECT id, tanggal, barang_id, warna_id, gudang_id
					FROM nd_penyesuaian_stok
					WHERE tipe_transaksi = 0
					) a
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat(qty,'??', jumlah_roll,'??', id) SEPARATOR '--') as data_qty, penyesuaian_stok_id
					FROM nd_penyesuaian_stok_qty
					GROUP BY penyesuaian_stok_id
					) t1
				ON a.id = t1.penyesuaian_stok_id
				LEFT JOIN nd_barang e
				ON a.barang_id = e.id
				LEFT JOIN nd_warna f
				ON a.warna_id = f.id
				LEFT JOIN nd_satuan g
				ON e.satuan_id = g.id
				LEFT JOIN nd_satuan g2
				ON e.packaging_id = g2.id
				LEFT JOIN nd_gudang h
				ON a.gudang_id = h.id
				-- order by tanggal desc
				ORDER BY h.nama, e.nama_jual, f.warna_jual
				
			", false);

		return $query->result();
	}

	function get_harga_stok_awal(){
		$query = $this->db->query("SELECT a.*, b.nama_jual as nama_barang, c.warna_jual as nama_warna, d.nama as nama_satuan
				FROM (
					SELECT * 
					FROM nd_stok_awal_item_harga
					) a
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan d
				ON b.satuan_id = d.id
				order by b.nama asc, c.warna_beli asc
				
			", false);

		return $query->result();

	}


//==================================mutasi persediaan barang==================

	function mutasi_persediaan_barang($tanggal,$tanggal_end, $gudang_id){
		
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, sum(qty_jual) as qty_jual, sum(jumlah_roll_jual) as jumlah_roll_jual, sum(qty_mutasi_masuk) as qty_mutasi_masuk, sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur ,hpp, hpp_beli, hpp_jual, t3.nama as nama_barang, nama_jual, warna_jual
			FROM (
				(
					SELECT barang_id, warna_id , sum(ifnull(qty_masuk,0)) - sum(ifnull(qty_keluar,0)) as qty_stock, sum(ifnull(jumlah_roll_masuk,0)) -sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stock, 0.00 as qty_beli, 0 as jumlah_roll_beli, 0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 0.00 as qty_jual, 0 as jumlah_roll_jual, 0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 0 as jumlah_roll_retur, 0.00 as qty_retur
					FROM (
						(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        FROM (
					        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail
					        	ORDER BY pembelian_id
					        ) nd_pembelian_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pembelian
					        	WHERE tanggal < '$tanggal'
					        	AND gudang_id = $gudang_id
					        	AND status_aktif = 1
					        	) nd_pembelian
					        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
					        WHERE nd_pembelian.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        	FROM nd_mutasi_barang
				        	WHERE tanggal < '$tanggal'
				        	AND status_aktif = 1
				        	AND gudang_id_after = $gudang_id
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE gudang_id = $gudang_id
					        ) nd_penjualan_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE tanggal < '$tanggal'
					        	AND status_aktif = 1
					        	) nd_penjualan
					        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					            FROM nd_penjualan_qty_detail
					            GROUP BY penjualan_detail_id
					            ) nd_penjualan_qty_detail
					        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					        where nd_penjualan.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        FROM (
					        	SELECT *
					        	FROM nd_retur_jual_detail
					        	WHERE gudang_id = $gudang_id
					        ) nd_retur_jual_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_jual
					        	WHERE tanggal < '$tanggal'
					        	AND status_aktif = 1
					        	) nd_retur_jual
					        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					            FROM nd_retur_jual_qty
					            GROUP BY retur_jual_detail_id
					            ) nd_penjualan_qty_detail
					        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
					        WHERE nd_retur_jual.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tipe_transaksi = 0
					        	AND gudang_id = $gudang_id
		                        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal < '$tanggal'
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 1
				        	GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal < '$tanggal'
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 2
							GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        	FROM nd_mutasi_barang
				        	WHERE tanggal < '$tanggal'
				        	AND gudang_id_before = $gudang_id
				        	AND status_aktif = 1
							GROUP BY barang_id, warna_id
					    )
					) a
					GROUP BY barang_id, warna_id
				)UNION (
					SELECT barang_id, warna_id, 0.00, 0, sum(qty) as qty_beli, sum(jumlah_roll) as jumlah_roll_beli, 0.00 , 0, 0.00, 0 , 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
			        FROM (
			        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
			        	FROM nd_pembelian_detail
			        	ORDER BY pembelian_id
			        ) nd_pembelian_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_pembelian
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND gudang_id = $gudang_id
			        	AND status_aktif = 1
			        	) nd_pembelian
			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
			        WHERE nd_pembelian.id is not null
			        GROUP BY barang_id, warna_id
				)UNION(
			        SELECT barang_id, warna_id, 0.00, 0, 0.00, 0, 0.00,0, sum(qty), sum(jumlah_roll), 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail
				        	WHERE gudang_id = $gudang_id
				        ) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal >= '$tanggal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION (
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , sum(qty) , sum(jumlah_roll),0.00 , 0, 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_before = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION (
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, sum(qty) , sum(jumlah_roll),0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_after = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, 0.00 , 0, sum(qty_masuk) - sum(qty_keluar), sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar), 0 as jumlah_roll_retur, 0.00 as qty_retur
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id
							    )
							)a
				        GROUP BY barang_id, warna_id
				)UNION(
			    	SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, 0.00 , 0,0.00 ,0, jumlah_roll as jumlah_roll_retur, qty as qty_retur
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        	WHERE gudang_id = $gudang_id
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id, warna_id
			    )
			)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, TRUNCATE(sum(total_beli)/sum(qty_beli),2) as hpp
				FROM (
					(
						SELECT sum(qty) as qty_beli, sum(qty*harga_beli) as total_beli, barang_id, warna_id
						FROM (
							SELECT *
							FROM nd_pembelian
							WHERE tanggal < '$tanggal'
							) nd_pembelian
						LEFT JOIN nd_pembelian_detail
						ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
						WHERE barang_id is not null
						GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
					)UNION(
						SELECT sum(qty) as qty_beli, sum(qty*harga_stok_awal) as total_beli, stok_awal.barang_id, stok_awal.warna_id
						FROM (
							SELECT sum(qty) as qty, barang_id, warna_id
							FROM nd_penyesuaian_stok
							WHERE tipe_transaksi = 0
							GROUP BY barang_id, warna_id
							) stok_awal 
						LEFT JOIN nd_stok_awal_item_harga
						ON stok_awal.barang_id = nd_stok_awal_item_harga.barang_id
						GROUP BY barang_id, warna_id
					) 
				)a
				GROUP BY barang_id, warna_id
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_beli)/sum(qty) as hpp_beli 
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE tanggal >= '$tanggal'
					AND tanggal <= '$tanggal_end'
					) nd_pembelian
				LEFT JOIN nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				WHERE barang_id is not null
				GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
			)t5
			ON t1.barang_id = t5.barang_id
			AND t1.warna_id = t5.warna_id 
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_jual) as hpp_jual
				FROM (
		        	SELECT *
		        	FROM nd_penjualan_detail
		        	WHERE gudang_id = $gudang_id
		        ) nd_penjualan_detail
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_penjualan
		        	WHERE tanggal >= '$tanggal'
		        	AND tanggal <= '$tanggal_end'
		        	AND status_aktif = 1
		        	) nd_penjualan
		        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
		        LEFT JOIN (
		            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
		            FROM nd_penjualan_qty_detail
		            GROUP BY penjualan_detail_id
		            ) nd_penjualan_qty_detail
		        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
		        where nd_penjualan.id is not null
		        GROUP BY barang_id, warna_id
			)t6
			ON t1.barang_id = t6.barang_id
			AND t1.warna_id = t6.warna_id 
			LEFT JOIN nd_barang as t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_warna as t4
			ON t1.warna_id = t4.id
			GROUP BY barang_id, warna_id

			", false);

		return $query->result();
	}

	function get_stok_opname_detail($stok_opname_id, $select, $select_before, $tanggal_awal, $tanggal, $cond_barang){
		$query = $this->db->query("SELECT t3.*, t2.*, t2.id as stok_opname_detail_id, t1.* 
			FROM (
				SELECT b.nama as nama_barang, b.nama_jual as nama_barang_jual, c.warna_beli as nama_warna, c.warna_jual as nama_warna_jual, b.status_aktif as status_barang, e.nama as nama_satuan,  barang_id, warna_id 
				FROM (
					(
						SELECT barang_id, warna_id
						FROM nd_pembelian_detail
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_penjualan_detail
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_penyesuaian_stok
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_mutasi_barang
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_stok_opname_detail
					)
				)a
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan e
				ON b.satuan_id = e.id
			) t1
			LEFT JOIN(
				SELECT id, barang_id, warna_id $select, gudang_id
				FROM nd_stok_opname_detail
				WHERE stok_opname_id = $stok_opname_id
				GROUP BY barang_id, warna_id
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT barang_id, warna_id $select_before
				FROM(
					(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        FROM (
					        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail t1
                                LEFT JOIN (
                                    SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll, 1)) as qty, sum(jumlah_roll) as jumlah_roll,  pembelian_detail_id
                                    FROM nd_pembelian_qty_detail
					        		GROUP BY pembelian_detail_id
					        		 
                                ) t2
                                ON t1.id = t2.pembelian_detail_id
					        ) nd_pembelian_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pembelian
					        	WHERE tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_pembelian
					        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
					        WHERE nd_pembelian.id is not null
					        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        FROM nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        FROM nd_retur_jual_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal'
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
				        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
	                        AND tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
	                        GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 2
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
						GROUP BY barang_id, warna_id, gudang_id_before
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_stok_opname_detail
			        	WHERE stok_opname_id != $stok_opname_id
			        	GROUP BY barang_id, warna_id, gudang_id
				    )
				) tbl_a
				GROUP by barang_id, warna_id
			) t3
			ON t1.barang_id = t3.barang_id
			AND t1.warna_id = t3.warna_id
			WHERE t1.barang_id != 0
			$cond_barang
			ORDER BY nama_barang_jual, nama_warna_jual
			", false);

		return $query->result();
	}


	function get_nama_stok_barang(){
		$query = $this->db->query("SELECT b.nama as nama_barang, b.nama_jual as nama_barang_jual, b.status_aktif as status_barang, barang_id 
				FROM (
					(
						SELECT barang_id
						FROM nd_pembelian_detail
					)UNION(
						SELECT barang_id
						FROM nd_penjualan_detail
					)UNION(
						SELECT barang_id
						FROM nd_penyesuaian_stok
					)UNION(
						SELECT barang_id
						FROM nd_mutasi_barang
					)
					-- UNION(
					-- 	SELECT barang_id
					-- 	FROM nd_stok_opname_detail
					-- )
				)a
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
			", false);

		return $query->result();
	}

//=======================================================================

	function get_data_eceran_jual_detail($id_detail){
		$query = $this->db->query("SELECT t1.id, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur, if(customer_id=0, nama_keterangan, t4.nama) as nama_customer, tanggal
			FROM (
				SELECT *
				FROM nd_penjualan_qty_detail
				WHERE stok_eceran_qty_id IN ($id_detail)
				)t1
			LEFT JOIN nd_penjualan_detail t2
			ON t1.penjualan_detail_id = t2.id
			LEFT JOIN nd_penjualan t3
			ON t2.penjualan_id = t3.id
			LEFT JOIN nd_customer t4
			ON t3.customer_id = t4.id
			WHERE t3.status_aktif = 1 
			", false);

		return $query->result();
	}

	function get_stok_barang_eceran_list_detail($gudang_id, $barang_id,$warna_id, $tanggal, $tanggal_awal, $stok_opname_id){
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

	function kartu_stok_eceran($gudang_id, $barang_id,$warna_id, $tanggal_end, $tanggal_start, $stok_opname_id){
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

//==========================================================================================================================================

	function get_assembly_list($tanggal_end, $tanggal_start, $cond_barang, $cond_gudang){
		$query = $this->db->query("SELECT t1.*, CONCAT('[', GROUP_CONCAT(rekap_sumber),']') as rekap_sumber, 
		CONCAT('[', GROUP_CONCAT(rekap_hasil),']') as rekap_hasil, 
		username, nd_toko.nama as nama_toko, nd_gudang.nama as nama_gudang,
			b1.nama_jual as nama_barang_sumber, b2.nama_jual as nama_barang_hasil,
			w1.warna_jual as nama_warna_sumber, w2.warna_jual as nama_warna_hasil,
			nd_toko.nama as nama_toko
			FROM (
				SELECT *
				FROM nd_assembly
				WHERE tanggal >='$tanggal_start'
				AND tanggal <='$tanggal_end'
				$cond_gudang
				)t1
				LEFT JOIN(
					SELECT assembly_id,
					CONCAT('{',
						'\"qty\":', qty,
						',\"jumlah_roll\":', jumlah_roll,
						',\"supplier_id\":', supplier_id,
						',\"nama_supplier\":', '\"',nd_supplier.nama,'\"',
						'}'
					) as rekap_sumber
					FROM nd_assembly_detail_sumber tX
					LEFT JOIN nd_supplier 
					ON tX.supplier_id = nd_supplier.id
					$cond_barang
				)t2
				ON t1.id=t2.assembly_id
				LEFT JOIN(
					SELECT assembly_id, 
					CONCAT('{',
						'\"qty\":', qty,
						',\"jumlah_roll\":', jumlah_roll,
						',\"supplier_id\":', supplier_id,
						',\"nama_supplier\":', '\"',nd_supplier.nama,'\"',
						'}'
					)as rekap_hasil
					FROM nd_assembly_detail_hasil tX
					LEFT JOIN nd_supplier 
					ON tX.supplier_id = nd_supplier.id
					$cond_barang
				)t3
				ON t1.id=t3.assembly_id
				LEFT JOIN nd_user
				ON t1.user_id=nd_user.id
				LEFT JOIN nd_gudang
				ON t1.gudang_id=nd_gudang.id
				LEFT JOIN nd_toko
				ON t1.toko_id=nd_toko.id
				LEFT JOIN nd_barang b1 
				ON t1.barang_id_sumber = b1.id
				LEFT JOIN nd_barang b2
				ON t1.barang_id_hasil = b2.id
				LEFT JOIN nd_warna w1 
				ON t1.warna_id_sumber = w1.id
				LEFT JOIN nd_warna w2
				ON t1.warna_id_hasil = w2.id
				WHERE t2.assembly_id is not null
				OR t3.assembly_id is not null
				GROUP BY t1.id
				");
		
		return $query->result();
	}

//============================================================================================================================

	function get_mutasi_barang_eceran($tanggal_start, $tanggal_end, $cond){
		$query = $this->db->query("SELECT t1.*, t2.*, nama_jual as nama_barang, warna_jual as nama_keterangan, 
		tD.nama as nama_gudang, tE.nama as nama_gudang_sumber
			FROM (
				SELECT *
				FROM nd_mutasi_stok_eceran
				WHERE tanggal >='$tanggal_start'
				AND tanggal <='$tanggal_end'
				AND tipe = 3
				$cond
			)t1
			LEFT JOIN (
				SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, mutasi_stok_eceran_id,
				group_concat(concat(mutasi_stok_eceran_qty_source_id,',',qty,',',tA.supplier_id) SEPARATOR '??' ) as qty_data,
				tB.gudang_id as gudang_id_source
				FROM nd_mutasi_stok_eceran_qty tA
				LEFT JOIN nd_mutasi_stok_eceran tB
				ON tA.mutasi_stok_eceran_qty_source_id = tB.id
				GROUP BY mutasi_stok_eceran_id
			)t2
			ON t2.mutasi_stok_eceran_id = t1.id
			LEFT JOIN nd_barang tB
			ON t1.barang_id = tB.id
			LEFT JOIN nd_warna tC 
			ON t1.warna_id = tC.id
			LEFT JOIN nd_gudang tD 
			ON t1.gudang_id = tD.id
			LEFT JOIN nd_gudang tE 
			ON t2.gudang_id_source = tE.id
			
		");
		
		return $query->result();
	}

}