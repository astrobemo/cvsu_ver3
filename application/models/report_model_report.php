<?php

class Report_Model extends CI_Model {
	
	function get_penjualan_report($from, $to, $cond, $customer_cond, $cond_toko, $cond_barang_warna, $cond_supplier){
		$query = $this->db->query("SELECT tbl_a.id, no_faktur as nf, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, 
			nama_barang, harga_jual, total, diskon, ongkos_kirim, if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer,
			(ifnull(total_bayar,0) + ifnull(total_lunas,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, tbl_a.id as data , 
			jatuh_tempo, pembayaran_type_id, data_bayar, pengali_harga, pembayaran_piutang_id, ifnull(npwp, '00.000.000.0-000.000') as npwp, nama_jual
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					$customer_cond
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_barang, 
					group_concat(t1.harga_jual SEPARATOR '??') as harga_jual, 
					group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, 
					group_concat((qty *t1.harga_jual) SEPARATOR '??') as total, sum(if(pengali_harga=1,qty,jumlah_roll) *t1.harga_jual) as g_total, 
					penjualan_id, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual, 
					group_concat(pengali_harga SEPARATOR '??') as pengali_harga
					FROM (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						$cond_supplier
						group by penjualan_detail_id, supplier_id
						) as nd_penjualan_qty_detail
					LEFT JOIN(
						SELECT *
						FROM nd_penjualan_detail
						$cond_barang_warna
						$cond_toko
						)t1
					ON nd_penjualan_qty_detail.penjualan_detail_id = t1.id
					LEFT JOIN nd_barang
					ON t1.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON t1.warna_id = nd_warna.id
					WHERE nd_barang.id is not null
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(if(pembayaran_type_id=5,0,amount)) as total_bayar, group_concat(amount) as data_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_lunas, group_concat(amount) as data_lunas, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id 
					FROM nd_pembayaran_piutang_detail t1
					LEFT JOIN nd_pembayaran_piutang t2
					ON t1.pembayaran_piutang_id = t2.id
					WHERE t2.status_aktif != 0
					AND data_status = 1
					GROUP BY penjualan_id
					) tbl_e
				ON tbl_e.penjualan_id = tbl_a.id
				WHERE tbl_b.nama_barang is not null
				$cond
				ORDER BY tanggal, nf asc

			", false);

		return $query->result();
	}

	function get_penjualan_report_excel($from, $to, $cond, $customer_cond){
		$query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, nama_jual, harga_jual, total, diskon, ongkos_kirim,  if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, data_bayar, pembayaran_type_id
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					$customer_cond
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nama,warna_beli) SEPARATOR '??') as nama_barang, group_concat(nd_penjualan_detail.harga_jual SEPARATOR '??') as harga_jual, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_penjualan_detail.harga_jual) SEPARATOR '??') as total, sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual
					FROM nd_penjualan_detail
					LEFT JOIN nd_barang
					ON nd_penjualan_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_penjualan_detail.warna_id = nd_warna.id
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar, group_concat(amount) as data_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				$cond
				ORDER BY no_faktur
			", false);

		return $query->result();
	}

	function get_penjualan_laba_report($from, $to, $cond, $customer_cond){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, harga_jual, total, diskon, ongkos_kirim, if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, tbl_a.id as data , jatuh_tempo, hpp
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					AND no_faktur != ''
					$customer_cond
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nama,warna_jual) SEPARATOR '??') as nama_barang, group_concat(nd_penjualan_detail.harga_jual SEPARATOR '??') as harga_jual, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_penjualan_detail.harga_jual) SEPARATOR '??') as total, sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual, group_concat(hpp SEPARATOR '??') as hpp
					FROM nd_penjualan_detail
					LEFT JOIN nd_barang
					ON nd_penjualan_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_penjualan_detail.warna_id = nd_warna.id
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					LEFT JOIN (
						SELECT sum(qty_beli) as qty_beli, sum(total_beli) as total_beli, barang_id, warna_id, TRUNCATE(sum(total_beli)/sum(qty_beli),2) as hpp
						FROM (
							(
								SELECT sum(qty) as qty_beli, sum(qty*harga_beli) as total_beli, barang_id, warna_id
								FROM (
									SELECT *
									FROM nd_pembelian
									WHERE tanggal <= '$to'
									AND status_aktif = 1
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
					) tbl_hpp
					ON nd_penjualan_detail.barang_id = tbl_hpp.barang_id
					AND nd_penjualan_detail.warna_id = tbl_hpp.warna_id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(if(pembayaran_type_id=5,0,amount)) as total_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				$cond
				ORDER BY no_faktur

			", false);

		return $query->result();
	}

	

//==================================pembelian list==========================================

	function get_pembelian_report($from, $to, $cond, $cond_barang, $cond_warna){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, if(no_faktur='',no_surat_jalan, no_faktur) as no_faktur, tanggal, qty, jumlah_roll, nama_barang, nama_jual, harga_beli, total, diskon, ifnull(tbl_c.nama,'no name') as nama_supplier, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0))) as keterangan2, tbl_a.id as data , jatuh_tempo, tbl_e.nama as nama_gudang, tbl_d.pembayaran_hutang_id, tanggal_bayar, pengali_type, sisa_hutang_bayar as keterangan
				FROM (
					SELECT group_concat(concat_ws(' ',nama,warna_beli) SEPARATOR '??') as nama_barang, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual,  group_concat(nd_pembelian_detail.harga_beli SEPARATOR '??') as harga_beli, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat(total SEPARATOR '??') as total, sum(if(pengali_type = 1,qty,jumlah_roll) *nd_pembelian_detail.harga_beli) as g_total, pembelian_id, group_concat(pengali_type SEPARATOR '??') as pengali_type
					FROM (
						SELECT t_a.id, if(pengali_type = 1,t_b.qty, t_b.jumlah_roll)*t_a.harga_beli as total, t_a.harga_beli, pembelian_id, t_b.qty, t_b.jumlah_roll, barang_id, warna_id, pengali_type
						FROM nd_pembelian_detail t_a
						LEFT JOIN (
							SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_pembelian_qty_detail
							GROUP BY pembelian_detail_id
						) t_b
						ON t_a.id = t_b.pembelian_detail_id
						LEFT JOIN nd_barang
						ON t_a.barang_id = nd_barang.id
						WHERE t_b.qty != 0
						$cond_barang
						$cond_warna
						) as nd_pembelian_detail
					LEFT JOIN nd_barang
					ON nd_pembelian_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_pembelian_detail.warna_id = nd_warna.id
					GROUP BY pembelian_id
					) as tbl_b
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian 
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as tbl_a
				ON tbl_b.pembelian_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_c
				ON tbl_a.supplier_id = tbl_c.id
				LEFT JOIN (
					SELECT pembelian_id, sum(t1.amount) as total_bayar, group_concat(t1.pembayaran_hutang_id) as pembayaran_hutang_id, group_concat(tanggal_transfer) as tanggal_bayar
					FROM nd_pembayaran_hutang_detail t1
					LEFT JOIN (
						SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
						) t2
					ON t1.pembayaran_hutang_id = t2.pembayaran_hutang_id
					GROUP BY pembelian_id
					) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				LEFT JOIN nd_gudang tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				LEFT JOIN (
					SELECT amount_bill - ifnull(amount_paid, 0) - ifnull(potongan_hutang,0) as sisa_hutang_bayar, tA.pembayaran_hutang_id
					FROM (
						SELECT sum(amount) as amount_bill, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_detail
						GROUP BY pembayaran_hutang_id
					)tA
					LEFT JOIN (
						SELECT sum(amount) as amount_paid, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
					)tB
					ON tA.pembayaran_hutang_id = tB.pembayaran_hutang_id
					LEFT JOIN nd_pembayaran_hutang tC
					ON tA.pembayaran_hutang_id = tC.id
				) tbl_e
				ON tbl_d.pembayaran_hutang_id = tbl_e.pembayaran_hutang_id
				WHERE tbl_a.id is not null
				ORDER BY tanggal asc, no_faktur asc
			", false);

		return $query->result();
	}

	function get_pembelian_report_excel($from, $to, $cond, $cond_barang, $cond_warna){
		$query = $this->db->query("SELECT no_faktur, tanggal, qty, jumlah_roll, nama_barang, nama_jual, harga_beli, total, diskon, ifnull(tbl_c.nama,'no name') as nama_supplier, 
		(ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0))) as keterangan, tbl_a.id as data , jatuh_tempo, tbl_e.nama as nama_gudang
				FROM (
					SELECT group_concat(concat_ws(' ',nama,warna_beli) SEPARATOR '??') as nama_barang, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual,  group_concat(nd_pembelian_detail.harga_beli SEPARATOR '??') as harga_beli, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat(total SEPARATOR '??') as total, sum(if(pengali_type = 1,qty,jumlah_roll) *nd_pembelian_detail.harga_beli) as g_total, pembelian_id, group_concat(pengali_type SEPARATOR '??') as pengali_type
					FROM (
						SELECT t_a.id, if(pengali_type = 1,t_b.qty, t_b.jumlah_roll)*t_a.harga_beli as total, t_a.harga_beli, pembelian_id, t_b.qty, t_b.jumlah_roll, barang_id, warna_id, pengali_type
						FROM nd_pembelian_detail t_a
						LEFT JOIN (
							SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_pembelian_qty_detail
							GROUP BY pembelian_detail_id
						) t_b
						ON t_a.id = t_b.pembelian_detail_id
						LEFT JOIN nd_barang
						ON t_a.barang_id = nd_barang.id
						WHERE t_b.qty != 0
						$cond_barang
						$cond_warna
						) as nd_pembelian_detail
					LEFT JOIN nd_barang
					ON nd_pembelian_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_pembelian_detail.warna_id = nd_warna.id
					GROUP BY pembelian_id
					) as tbl_b
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian 
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as tbl_a
				ON tbl_b.pembelian_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_c
				ON tbl_a.supplier_id = tbl_c.id
				LEFT JOIN (
					SELECT pembelian_id, sum(t1.amount) as total_bayar, group_concat(t1.pembayaran_hutang_id) as pembayaran_hutang_id, group_concat(tanggal_transfer) as tanggal_bayar
					FROM nd_pembayaran_hutang_detail t1
					LEFT JOIN (
						SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
						) t2
					ON t1.pembayaran_hutang_id = t2.pembayaran_hutang_id
					GROUP BY pembelian_id
					) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				LEFT JOIN nd_gudang tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				LEFT JOIN (
					SELECT amount_bill - ifnull(amount_paid, 0) - ifnull(potongan_hutang,0) as sisa_hutang_bayar, tA.pembayaran_hutang_id
					FROM (
						SELECT sum(amount) as amount_bill, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_detail
						GROUP BY pembayaran_hutang_id
					)tA
					LEFT JOIN (
						SELECT sum(amount) as amount_paid, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
					)tB
					ON tA.pembayaran_hutang_id = tB.pembayaran_hutang_id
					LEFT JOIN nd_pembayaran_hutang tC
					ON tA.pembayaran_hutang_id = tC.id
				) tbl_e
				ON tbl_d.pembayaran_hutang_id = tbl_e.pembayaran_hutang_id
				WHERE tbl_a.id is not null
				ORDER BY tanggal asc, no_faktur asc
			", false);

		return $query->result();
	}


//==========================================penerimaan list================================
	function get_penjualan_bayar_by_date($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT tbl_a.tanggal, no_faktur_lengkap as no_faktur, group_concat(pembayaran_type_id) as pembayaran_type_id, group_concat(amount) as bayar , g_total as amount, if (penjualan_type_id = 3, nama_keterangan, tbl_c.nama) as nama_customer, tbl_b.keterangan as keterangan_transfer
			FROM (
				SELECT nd_penjualan.*, g_total
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					) as nd_penjualan
					LEFT JOIN (
						SELECT sum(if(pengali_harga=1,qty,jumlah_roll) *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							group by penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
						GROUP BY penjualan_id
					) nd_penjualan_detail
					ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
				) tbl_a
			LEFT JOIN nd_pembayaran_penjualan tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			LEFT JOIN nd_customer as tbl_c
			ON tbl_c.id = tbl_a.customer_id
			GROUP BY penjualan_id
			ORDER BY no_faktur asc
		");
		return $query->result();
	}

	function get_retur_jual_by_date($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT tanggal, no_faktur_lengkap as no_faktur, sum( harga * qty ) as amount
			FROM (
				SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
				FROM nd_retur_jual
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
			) tbl_a
			LEFT JOIN (
				SELECT (qty * jumlah_roll) as qty, retur_jual_id, harga
				FROM nd_retur_jual_detail
				LEFT JOIN nd_retur_jual_qty
				ON nd_retur_jual_detail.id = nd_retur_jual_qty.retur_jual_detail_id
			) tbl_b
			ON tbl_a.id = tbl_b.retur_jual_id
			GROUP BY retur_jual_id
		");
		return $query->result();
	}

//==================================pembelian list==========================================

	function get_barang_masuk_report($tanggal_start, $tanggal_end, $cond, $cond2){
		$query = $this->db->query("SELECT sum(e.qty * if(e.jumlah_roll = 0,1,e.jumlah_roll)) as qty, sum(e.jumlah_roll) as jumlah_roll, c.nama as nama_beli, d.warna_beli as nama_warna, sum(1) as count,barang_id, warna_id, sum(b.harga_beli*e.qty) / sum(e.qty) as harga_rata, satuan.nama as nama_satuan, packaging.nama as nama_packaging
				FROM (
					SELECT *
					FROM nd_pembelian 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as a
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian_detail
					$cond2
					) b
				LEFT JOIN nd_pembelian_qty_detail e
				ON b.id = e.pembelian_detail_id
				ON b.pembelian_id = a.id
				LEFT JOIN nd_barang c
				ON b.barang_id = c.id
				LEFT JOIN nd_warna d
				ON b.warna_id = d.id
				LEFT JOIN nd_satuan as satuan
				ON c.satuan_id = satuan.id
				LEFT JOIN nd_satuan as packaging
				ON c.packaging_id = packaging.id
				WHERE b.barang_id is not null
				AND b.warna_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY c.nama, d.warna_beli asc
			", false);

		return $query->result();
	}

	function get_barang_masuk_detail_report($tanggal_start, $tanggal_end, $cond, $cond2){
		$query = $this->db->query("SELECT tanggal, e.qty, e.jumlah_roll, c.nama as nama_beli, d.warna_beli as nama_warna, b.harga_beli, if(no_faktur = '',no_surat_jalan, no_faktur) as no_faktur, satuan.nama as nama_satuan, packaging.nama as nama_packaging
				FROM (
					SELECT *
					FROM nd_pembelian 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as a
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian_detail
					$cond2
					) b
				ON b.pembelian_id = a.id
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
					FROM nd_pembelian_qty_detail
					GROUP BY pembelian_detail_id
				) e
				ON b.id = e.pembelian_detail_id
				LEFT JOIN nd_barang c
				ON b.barang_id = c.id
				LEFT JOIN nd_warna d
				ON b.warna_id = d.id
				LEFT JOIN nd_satuan as satuan
				ON c.satuan_id = satuan.id
				LEFT JOIN nd_satuan as packaging
				ON c.packaging_id = packaging.id
				WHERE b.barang_id is not null
				AND b.warna_id is not null
			", false);

		return $query->result();
	}	

//================================buku laporan keluar=============================

	function get_barang_keluar_report($tanggal_start, $tanggal_end, $cond, $cond2){
		$query = $this->db->query("SELECT sum(e.qty * if(e.jumlah_roll = 0,1,e.jumlah_roll)) as qty, 
		sum(e.jumlah_roll) as jumlah_roll, c.nama as nama_beli, d.warna_beli as nama_warna, 
		sum(1) as count,barang_id, warna_id, sum(b.harga_jual*e.qty) / sum(e.qty) as harga_rata, 
		satuan.nama as nama_satuan, packaging.nama as nama_packaging
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as a
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan_detail
					$cond2
					) b
				LEFT JOIN nd_penjualan_qty_detail e
				ON b.id = e.penjualan_detail_id
				ON b.penjualan_id = a.id
				LEFT JOIN nd_barang c
				ON b.barang_id = c.id
				LEFT JOIN nd_warna d
				ON b.warna_id = d.id
				LEFT JOIN nd_satuan as satuan
				ON c.satuan_id = satuan.id
				LEFT JOIN nd_satuan as packaging
				ON c.packaging_id = packaging.id
				WHERE b.barang_id is not null
				AND b.warna_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY c.nama, d.warna_beli asc
			", false);

		return $query->result();
	}


	function get_barang_keluar_detail_report($tanggal_start, $tanggal_end, $cond, $cond2){
		$query = $this->db->query("SELECT tanggal, e.qty, e.jumlah_roll, c.nama as nama_beli, 
		d.warna_beli as nama_warna, b.harga_jual, no_faktur_lengkap as no_faktur, 
		satuan.nama as nama_satuan, packaging.nama as nama_packaging, a.id as penjualan_id
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as a
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan_detail
					$cond2
					) b
				ON b.penjualan_id = a.id
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
				) e
				ON b.id = e.penjualan_detail_id
				LEFT JOIN nd_barang c
				ON b.barang_id = c.id
				LEFT JOIN nd_warna d
				ON b.warna_id = d.id
				LEFT JOIN nd_satuan as satuan
				ON c.satuan_id = satuan.id
				LEFT JOIN nd_satuan as packaging
				ON c.packaging_id = packaging.id
				WHERE b.barang_id is not null
				AND b.warna_id is not null
			", false);

		return $query->result();
	}

//================================buku laporan piutang=============================

	function buku_laporan_piutang($aColumns, $sWhere, $sOrder, $sLimit, $cond_date, $customer_cond){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.id, no_faktur as nf, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, harga_jual, total, diskon, ongkos_kirim, if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, ( (ifnull(total_bayar,0) + ifnull(total_lunas,0) ) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, tbl_a.id as data , jatuh_tempo, concat(DATE_FORMAT(tanggal,'%d-%b-%y'),'??',pembayaran_type_id,'??',data_bayar) as pembayaran_data, pelunasan_data, tbl_a.id as penjualan_id
					FROM (
						SELECT *
						FROM nd_penjualan 
						$cond_date
						AND status_aktif = 1
						$customer_cond
						ORDER BY tanggal desc
						)as tbl_a
					LEFT JOIN (
						SELECT group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_barang, group_concat(nd_penjualan_detail.harga_jual SEPARATOR '??') as harga_jual, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_penjualan_detail.harga_jual) SEPARATOR '??') as total, sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual
						FROM nd_penjualan_detail
						LEFT JOIN nd_barang
						ON nd_penjualan_detail.barang_id = nd_barang.id
						LEFT JOIN nd_warna
						ON nd_penjualan_detail.warna_id = nd_warna.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							group by penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_b.penjualan_id = tbl_a.id
					LEFT JOIN nd_customer as tbl_c
					ON tbl_a.customer_id = tbl_c.id
					LEFT JOIN (
						SELECT penjualan_id, sum(if(pembayaran_type_id=5,0,amount)) as total_bayar, group_concat(amount) as data_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id != 5
						GROUP BY penjualan_id
						) as tbl_d
					ON tbl_d.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT penjualan_id, sum(t1.amount) as total_lunas, group_concat(t2.id,'--',t1.amount,'--',tanggal_transfer,'--',pembayaran_type_id SEPARATOR '??') as pelunasan_data
						FROM nd_pembayaran_piutang_detail t1
						LEFT JOIN nd_pembayaran_piutang t2
						ON t1.pembayaran_piutang_id = t2.id
						LEFT JOIN (
							SELECT group_concat(pembayaran_type_id) as pembayaran_type_id, group_concat(DATE_FORMAT(tanggal_transfer,'%d-%b-%y') ) as tanggal_transfer, pembayaran_piutang_id
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_nilai
								GROUP BY pembayaran_type_id, pembayaran_piutang_id, tanggal_transfer
								)nd_pembayaran_piutang_nilai
							GROUP BY pembayaran_piutang_id
							) t3
						ON t3.pembayaran_piutang_id = t2.id
						WHERE t2.status_aktif != 0
						AND data_status = 1
						AND tanggal_transfer is not null
						GROUP BY penjualan_id
						) tbl_e
					ON tbl_e.penjualan_id = tbl_a.id
					ORDER BY nf asc
				) A
			$sWhere
            $sOrder
            $sLimit

				

			", false);

		return $query;
	}


//================================buku laporan penyesuaian stok=============================

	function get_penyesuaian_stok($select, $tanggal_start, $tanggal_end){

		$query = $this->db->query("SELECT t2.nama as nama_barang, t3.warna_beli as nama_warna, t4.nama as nama_satuan, sum(qty_masuk) as qty_masuk, sum(qty_keluar) as qty_keluar, sum(jumlah_roll_masuk) as jumlah_roll_masuk, sum(jumlah_roll_keluar) as jumlah_roll_keluar
			FROM (
				(
					select barang_id, gudang_id, warna_id, qty as qty_masuk, 0 as qty_keluar, jumlah_roll as jumlah_roll_masuk, 0 as jumlah_roll_keluar
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 1
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
				)UNION(
					select barang_id, gudang_id, warna_id, 0, qty, 0, jumlah_roll
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 2
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
				)
			) t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_warna t3
			ON t1.warna_id = t3.id
			LEFT JOIN nd_satuan t4
			ON t2.satuan_id = t4.id
			GROUP BY barang_id

			", false);

		return $query->result();
	}

	function get_penyesuaian_stok_by_gudang($select, $tanggal){

		$query = $this->db->query("SELECT t2.nama as nama_barang, t3.warna_beli as nama_warna, t4.nama as nama_satuan $select
			FROM (
				(
					select barang_id, gudang_id, warna_id, qty as qty_masuk, 0 as qty_keluar, jumlah_roll as jumlah_roll_masuk, 0 as jumlah_roll_keluar
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 1
					AND tanggal <= '$tanggal'
				)UNION(
					select barang_id, gudang_id, warna_id, 0, qty, 0, jumlah_roll
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 2
					AND tanggal <= '$tanggal'
				)
			) t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_warna t3
			ON t1.warna_id = t3.id
			LEFT JOIN nd_satuan t4
			ON t2.satuan_id = t4.id
			GROUP BY barang_id, gudang_id

			", false);

		return $query->result();
	}

}
