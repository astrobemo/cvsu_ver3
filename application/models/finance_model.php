<?php

class Finance_Model extends CI_Model {

//====================================hutang awal======================================
	
	function get_hutang_awal(){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.*
				FROM nd_supplier as tbl_a
				LEFT JOIN (
					SELECT supplier_id, sum(1) as jumlah_nota, sum(ifnull(amount,0)) as amount
					FROM nd_hutang_awal
					GROUP BY supplier_id
					) as tbl_b
				ON tbl_b.supplier_id = tbl_a.id
			", false);

		return $query->result();
	}

//====================================hutang======================================

	function get_hutang_list($tanggal){
		$query = $this->db->query("SELECT a.nama as nama_supplier, b.*
			FROM nd_supplier a 
			LEFT JOIN (
				SELECT status_aktif, supplier_id, sum(total_beli), total_bayar, 
				if(sisa_hutang <= 1 AND sisa_hutang >= -1,0, sisa_hutang) as sisa_hutang, toko_id, tanggal_start, tanggal_end
				FROM (
					(
						SELECT tbl_a.status_aktif, supplier_id, sum(total) as total_beli, 
						sum(ifnull(total_bayar,0)) as total_bayar, sum(ifnull(total,0)) - sum(ifnull(total_bayar,0))  - sum(ifnull(diskon,0)) as sisa_hutang, tanggal, toko_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
						FROM (
							SELECT t1.*, total, pembelian_id
							FROM (
								SELECT *
								FROM nd_pembelian
								WHERE status_aktif = 1
								AND tanggal <= '$tanggal'
								)t1
								LEFT JOIN (
									SELECT t_a.id, sum(if(pengali_type = 1,t_b.qty, t_b.jumlah_roll)*t_a.harga_beli) as total, 
									t_a.harga_beli, pembelian_id
									FROM nd_pembelian_detail t_a
									LEFT JOIN (
										SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
										FROM nd_pembelian_qty_detail
										GROUP BY pembelian_detail_id
									) t_b
									ON t_a.id = t_b.pembelian_detail_id
									LEFT JOIN nd_barang
									ON t_a.barang_id = nd_barang.id
									group by pembelian_id
								) t2
								ON t1.id = t2.pembelian_id
							) as tbl_a
						LEFT JOIN nd_supplier as tbl_c
						ON tbl_c.id = tbl_a.supplier_id
						LEFT JOIN (
							SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
							FROM nd_pembayaran_hutang_detail
							WHERE data_status = 1
							GROUP BY pembelian_id
							) tbl_d
						ON tbl_a.id = tbl_d.pembelian_id
						GROUP BY supplier_id
					)UNION(
						SELECT 1, supplier_id, sum(ifnull(amount,0)), 0 , sum(ifnull(amount,0)), tanggal, toko_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
						FROM nd_hutang_awal
						GROUP BY supplier_id	
					)
				) t1
				GROUP BY supplier_id
			) b
			ON b.supplier_id = a.id
			", false);

		return $query->result();
	}

	function get_hutang_list_detail($supplier_id){
		$query = $this->db->query("SELECT t1.*, t2.nama as supplier
			FROM (
				(
					SELECT tanggal, no_faktur, supplier_id, total as total_beli, total_bayar, ifnull(total_bayar,0) - ifnull(total,0) as sisa_hutang
					FROM (
						SELECT *
						FROM nd_pembelian
						WHERE supplier_id = $supplier_id
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN nd_toko as tbl_b
					ON tbl_a.toko_id = tbl_b.id
					LEFT JOIN (
						SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_beli) as total, harga_beli, pembelian_id, barang_id, satuan_id
						FROM nd_pembelian_detail
						group by pembelian_id
						) as tbl_c
					ON tbl_c.pembelian_id = tbl_a.id
					LEFT JOIN nd_gudang as tbl_d
					ON tbl_a.gudang_id = tbl_d.id
					LEFT JOIN (
						SELECT sum(amount) as total_bayar, pembelian_id
						FROM nd_pembayaran_hutang_detail
						GROUP BY pembelian_id
						) tbl_f
					ON tbl_a.id = tbl_f.pembelian_id
					WHERE ifnull(total_bayar,0) - ifnull(total,0) < 0
				)UNION(
					SELECT tanggal, concat(no_faktur,' (hutang awal)'), supplier_id, amount, 0, ifnull(amount,0) - 0 as sisa_hutang
					FROM nd_hutang_awal
					WHERE supplier_id = $supplier_id
				)
			) t1
			LEFT JOIN nd_supplier as t2
			ON t2.id = t1.supplier_id
			", false);

		return $query->result();
	}

	function get_hutang_list_by_date($from, $to, $toko_id, $supplier_id){
		$query = $this->db->query("SELECT tbl_a.status_aktif, if(no_faktur = '',no_surat_jalan, no_faktur) as no_faktur, 
		supplier_id, total as total_beli, total_bayar, ifnull(total,0) - ifnull(total_bayar,0) - ifnull(diskon,0) as sisa_hutang, 
		tbl_a.id as pembelian_id, jatuh_tempo, jumlah_roll, tbl_a.tanggal
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND supplier_id = $supplier_id
					AND toko_id = $toko_id
					AND status_aktif = 1
					) as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT t_a.id, sum(if(pengali_type = 1,t_b.qty, t_b.jumlah_roll)*t_a.harga_beli) as total, t_a.harga_beli, pembelian_id, t_b.qty, t_b.jumlah_roll
					FROM nd_pembelian_detail t_a
					LEFT JOIN (
						SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
						FROM nd_pembelian_qty_detail
						GROUP BY pembelian_detail_id
					) t_b
					ON t_a.id = t_b.pembelian_detail_id
					LEFT JOIN nd_barang
					ON t_a.barang_id = nd_barang.id
					group by pembelian_id
					) as tbl_c
				ON tbl_c.pembelian_id = tbl_a.id
				LEFT JOIN nd_gudang as tbl_d
				ON tbl_a.gudang_id = tbl_d.id
				LEFT JOIN nd_supplier as tbl_e
				ON tbl_e.id = tbl_a.supplier_id
				LEFT JOIN (
					SELECT sum(amount) as total_bayar, pembelian_id
					FROM nd_pembayaran_hutang_detail
					GROUP BY pembelian_id
					) tbl_f
				ON tbl_a.id = tbl_f.pembelian_id
				WHERE ifnull(total,0) - ifnull(total_bayar,0) - ifnull(diskon,0) > 1
				ORDER by no_faktur asc, tanggal asc
			", false);

		return $query->result();
	}

	function get_retur_beli_belum_lunas($supplier_id, $toko_id){
		$query = $this->db->query("SELECT tbl_a.status_aktif, 
		(no_faktur_lengkap) as no_faktur, 
		supplier_id, total as total_beli, total_bayar, 
		ifnull(total,0) - ifnull(total_bayar,0) as sisa_hutang, 
		tbl_a.id as pembelian_id, jumlah_roll, tbl_a.tanggal
				FROM (
					SELECT *
					FROM nd_retur_beli
					WHERE supplier_id = $supplier_id
					AND toko_id = $toko_id
					AND status_aktif = 1
					) as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT t_a.id, sum(if(pengali_type = 1, subqty, subroll)*t_a.harga) as total, 
					t_a.harga, retur_beli_id, sum(subroll) as jumlah_roll
					FROM nd_retur_beli_detail t_a
					LEFT JOIN nd_barang
					ON t_a.barang_id = nd_barang.id
					group by retur_beli_id
					) as tbl_c
				ON tbl_c.retur_beli_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_e
				ON tbl_e.id = tbl_a.supplier_id
				LEFT JOIN (
					SELECT sum(amount) as total_bayar, pembelian_id
					FROM nd_pembayaran_hutang_detail
					WHERE data_status=3
					GROUP BY pembelian_id
					) tbl_f
				ON tbl_a.id = tbl_f.pembelian_id
				WHERE ifnull(total,0) - ifnull(total_bayar,0) > 1
				ORDER by no_faktur asc, tanggal asc
			", false);

		return $query->result();
	}

	function get_retur_beli_detail($pembayaran_hutang_id){
		$query = $this->db->query("SELECT tA.status_aktif, 
		(no_faktur_lengkap) as no_faktur, 
		supplier_id, total as total_beli, amount, 
		ifnull(total,0) as sisa_hutang, 
		tA.id as pembelian_id, jumlah_roll, tA.tanggal
				FROM (
					SELECT amount, pembelian_id
					FROM nd_pembayaran_hutang_detail
					WHERE data_status=3
					AND pembayaran_hutang_id = $pembayaran_hutang_id
				) t0
				LEFT JOIN nd_retur_beli tA
				ON t0.pembelian_id = tA.id
				LEFT JOIN nd_toko as tB
				ON tA.toko_id = tB.id
				LEFT JOIN (
					SELECT t_a.id, sum(if(pengali_type = 1, subqty, subroll)*t_a.harga) as total, 
					t_a.harga, retur_beli_id, sum(subroll) as jumlah_roll
					FROM nd_retur_beli_detail t_a
					LEFT JOIN nd_barang
					ON t_a.barang_id = nd_barang.id
					group by retur_beli_id
				) as tC
				ON tC.retur_beli_id = tA.id
				ORDER by no_faktur asc, tanggal asc
			", false);

		return $query->result();
	}

	function get_hutang_awal_by_date($from, $to, $toko_id, $supplier_id){
		$query = $this->db->query("SELECT 1, concat(no_faktur,' (hutang awal)') as no_faktur, supplier_id, amount as total_beli,0 as total_bayar , ifnull(amount,0) - 0 as sisa_hutang, id as pembelian_id, jatuh_tempo, jumlah_roll, 2 as data_status
				FROM nd_hutang_awal
				WHERE supplier_id = $supplier_id
				AND tanggal >= '$from'
				AND tanggal <= '$to'
				AND toko_id = $toko_id
				");
		return $query->result();

	}

//========================================hutang payment============================

	function get_bank_bayar_history(){
		$query = $this->db->query("SELECT nama_bank, no_rek_bank
				FROM nd_pembayaran_hutang_nilai
				WHERE nama_bank is not null
				AND nama_bank != ''
				GROUP BY nama_bank, no_rek_bank
					");

		return $query->result();
	}

	function get_bank_default(){
		$query = $this->db->query("SELECT nama_bank, no_rek_bank, nama_rek
				FROM nd_bank_akun
				WHERE status_default = 1
					");

		return $query->result();
	}

	function get_pembayaran_hutang($tanggal_start, $tanggal_end, $cond){
		$query = $this->db->query("SELECT a.id, b.nama as nama_supplier, c.nama as nama_toko, supplier_id, toko_id, pembulatan
				FROM (
					SELECT id, supplier_id, toko_id, pembulatan
					FROM (
						(
							SELECT id, supplier_id, toko_id, pembulatan
							FROM nd_pembayaran_hutang
							$cond
							AND DATE(created) >= '$tanggal_start'
							AND DATE(created) <= '$tanggal_end'
						)UNION(
							SELECT tbl_b.id, supplier_id, toko_id, 0
							FROM (
								SELECT *
								FROM nd_pembayaran_hutang_nilai
								WHERE tanggal_transfer >= '$tanggal_start'
								AND tanggal_transfer <= '$tanggal_end'
								
								)tbl_a
							LEFT JOIN nd_pembayaran_hutang tbl_b
							ON tbl_a.pembayaran_hutang_id = tbl_b.id
							$cond
							GROUP BY pembayaran_hutang_id, toko_id, supplier_id
						)
					) a
					GROUP BY id,supplier_id,toko_id
				)a
				LEFT JOIN nd_supplier b
				ON a.supplier_id = b.id
				LEFT JOIN nd_toko c
				ON a.toko_id = c.id
			", false);

		return $query->result();
	}

	function get_pembayaran_hutang_data($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_supplier, tbl_d.nama as nama_toko
			FROM (
				SELECT *
				FROM nd_pembayaran_hutang
				WHERE id = $id
				) tbl_a
			LEFT JOIN nd_supplier tbl_c
			ON tbl_a.supplier_id = tbl_c.id
			LEFT JOIN nd_toko tbl_d
			ON tbl_a.toko_id = tbl_d.id
			", false);

		return $query->result();
	}

	function get_pembayaran_hutang_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, if(no_faktur = '',no_surat_jalan, no_faktur) as no_faktur, tbl_c.total - ifnull(diskon,0) - ifnull(tbl_d.amount_bayar,0) as sisa_hutang, jatuh_tempo, tanggal, jumlah_roll, tbl_b.tanggal
			FROM (
				SELECT *
				FROM nd_pembayaran_hutang_detail
				WHERE pembayaran_hutang_id = $id
				AND data_status = 1
				) tbl_a
			LEFT JOIN nd_pembelian tbl_b
			ON tbl_a.pembelian_id = tbl_b.id
			LEFT JOIN (
				SELECT t_a.id, sum(if(pengali_type = 1,t_b.qty, t_b.jumlah_roll)*t_a.harga_beli) as total, t_a.harga_beli, pembelian_id, t_b.jumlah_roll, t_b.qty
				FROM nd_pembelian_detail t_a
				LEFT JOIN (
					SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
					FROM nd_pembelian_qty_detail
					GROUP BY pembelian_detail_id
				) t_b
				ON t_a.id = t_b.pembelian_detail_id
				LEFT JOIN nd_barang
				ON t_a.barang_id = nd_barang.id
				group by pembelian_id
				) tbl_c
			ON tbl_a.pembelian_id = tbl_c.pembelian_id
			LEFT JOIN (
				SELECT pembelian_id, sum(amount) as amount_bayar
				FROM nd_pembayaran_hutang_detail t_a
				LEFT JOIN nd_pembayaran_hutang t_b
				ON t_a.pembayaran_hutang_id = t_b.id
				WHERE pembayaran_hutang_id != $id
				AND data_status = 1
				AND t_b.status_aktif = 1
				GROUP BY pembelian_id
				) tbl_d
			ON tbl_c.pembelian_id = tbl_d.pembelian_id
			", false);

		return $query->result();
	}

	function get_pembayaran_hutang_awal_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, tbl_b.amount as sisa_hutang, jatuh_tempo, tanggal, jumlah_roll
			FROM (
				SELECT *
				FROM nd_pembayaran_hutang_detail
				WHERE pembayaran_hutang_id = $id
				AND data_status = 2
				) tbl_a
			LEFT JOIN nd_hutang_awal tbl_b
			ON tbl_a.pembelian_id = tbl_b.id
			
			", false);

		return $query->result();
	}

	function get_periode_pembelian($pembayaran_hutang_id){
		$query = $this->db->query("SELECT MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
			FROM nd_pembelian
			WHERE id in (
				SELECT pembelian_id
				FROM nd_pembayaran_hutang_detail
				WHERE pembayaran_hutang_id = $pembayaran_hutang_id
				)
			", false);

		return $query->result();
	}

	function get_pembayaran_hutang_unbalance(){
		$query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) - amount as balance, bayar, pembulatan, amount
				FROM (
					SELECT a.id, b.nama as nama_supplier, c.nama as nama_toko, supplier_id, toko_id, pembulatan
					FROM nd_pembayaran_hutang a
					LEFT JOIN nd_supplier b
					ON a.supplier_id = b.id
					LEFT JOIN nd_toko c
					ON a.toko_id = c.id
					) tbl_a
				LEFT JOIN (
					SELECT pembayaran_hutang_id, sum(amount) as amount
					FROM nd_pembayaran_hutang_detail
					GROUP BY pembayaran_hutang_id
					) tbl_b
				ON tbl_a.id = tbl_b.pembayaran_hutang_id
				LEFT JOIN (
					SELECT sum(amount) as bayar, pembayaran_hutang_id
					FROM nd_pembayaran_hutang_nilai
					GROUP BY pembayaran_hutang_id
					) tbl_c
				ON tbl_a.id = tbl_c.pembayaran_hutang_id
				WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount != 0

			", false);

		return $query->result();
	}

//====================================piutang awal======================================
	
	function get_piutang_awal(){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.*
				FROM nd_customer as tbl_a
				LEFT JOIN (
					SELECT customer_id, sum(1) as jumlah_nota, sum(ifnull(amount,0)) as amount
					FROM nd_piutang_awal
					GROUP BY customer_id
					) as tbl_b
				ON tbl_b.customer_id = tbl_a.id
			", false);

		return $query->result();
	}


//==============================piutang=========================================================

	function get_piutang_list(){
		$query = $this->db->query("SELECT tbl_a.status_aktif, ifnull(tbl_c.nama,'no name') as nama_customer, sum((ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim - ifnull(total_bayar,0) ) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, total_bayar, g_total, pembayaran_piutang_id
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE status_aktif = 1
					AND customer_id != 0
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) - ifnull(total_bayar,0) as g_total, nd_penjualan_detail.penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					LEFT JOIN (
						SELECT sum(amount) as total_bayar, penjualan_id
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id != 5
						GROUP by penjualan_id
					) nd_pembayaran_penjualan
					ON nd_penjualan_detail.penjualan_id = nd_pembayaran_penjualan.penjualan_id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id
					FROM nd_pembayaran_piutang_detail
					WHERE data_status = 1
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				group by customer_id
			", false);

		return $query->result();
	}

	/* function get_piutang_list_all_legacy($tanggal){
		
		$query = $this->db->query("SELECT customer_id, t2.nama as nama_customer, t3.nama as nama_toko, 
			sum(sisa_piutang) as sisa_piutang, 
			MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id,
			sum(sisa_kontra) as sisa_kontra, group_concat(sisa_kontra_data) as sisa_kontra_data, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id
			FROM (
				(
					SELECT t1.customer_id, toko_id, tanggal_start, tanggal_end, sisa - ifnull(total_bayar,0) as sisa_piutang, 0 as sisa_kontra, null as pembayaran_piutang_id, null as sisa_kontra_data
					FROM (
						SELECT sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(if(amount_bayar > g_total, 
						g_total, amount_bayar),0)) as sisa, 
						concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, 
						if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, 
						tbl_a.status_aktif, toko_id
						FROM (
							SELECT *
							FROM nd_penjualan 
							WHERE status_aktif = 1
							AND penjualan_type_id != 3
							AND no_faktur != ''
							AND tanggal <='$tanggal'
							ORDER BY tanggal desc
							)as tbl_a
						LEFT JOIN (
							SELECT sum(if(pengali_harga = 1,qty,jumlah_roll) *t1.harga_jual) as g_total, penjualan_id, t1.toko_id
							FROM nd_penjualan_detail t1
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								group by penjualan_detail_id
								) t2
							ON t2.penjualan_detail_id = t1.id
							LEFT JOIN nd_barang t3
							ON t1.barang_id = t3.id
							GROUP BY penjualan_id
							) as tbl_b
						ON tbl_b.penjualan_id = tbl_a.id
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, penjualan_id
							FROM nd_pembayaran_penjualan
							WHERE pembayaran_type_id != 5
							GROUP BY penjualan_id
						) tbl_g
						ON tbl_a.id = tbl_g.penjualan_id
						WHERE ifnull(g_total,0) + ongkos_kirim - ifnull(diskon,0) - ifnull(if(amount_bayar > g_total , g_total, amount_bayar),0) > 0
						GROUP BY customer_id
					)t1
					LEFT JOIN (
						SELECT sum(amount) as total_bayar, customer_id
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_detail
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							AND tanggal <='$tanggal'
							) b
						ON a.pembayaran_piutang_id = b.id
						WHERE b.id is not null
						GROUP BY customer_id
						) t2
					ON t1.customer_id = t2.customer_id
				)UNION(
					SELECT customer_id, toko_id, min(tanggal_start) as tanggal_start, max(tanggal_end) , 0, sum(sisa_kontra) as sisa_kontra, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id, group_concat(sisa_kontra) as sisa_kontra_data
					FROM (
						SELECT amount, amount_bayar, (amount - ifnull(amount_bayar,0)) as sisa_kontra, customer_id , toko_id, tanggal_start, tanggal_end, t1.pembayaran_piutang_id
						FROM (
							SELECT sum(amount) as amount, pembayaran_piutang_id
							FROM nd_pembayaran_piutang_detail
							GROUP BY pembayaran_piutang_id
						) t1
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, pembayaran_piutang_id, min(tanggal_transfer) as tanggal_start, max(tanggal_transfer) as tanggal_end
							FROM nd_pembayaran_piutang_nilai
							WHERE tanggal_transfer <= '$tanggal'
							GROUP BY pembayaran_piutang_id
						) t2
						ON t1.pembayaran_piutang_id = t2.pembayaran_piutang_id
						LEFT JOIN nd_pembayaran_piutang t3
						ON t1.pembayaran_piutang_id = t3.id
						WHERE t3.id is not null
					)res
					WHERE sisa_kontra > 0
					GROUP BY customer_id
					)
				) t1
				LEFT JOIN nd_customer as t2
				ON t1.customer_id = t2.id
				LEFT JOIN nd_toko t3
				ON t1.toko_id = t3.id
				WHERE sisa_piutang != 0
				OR sisa_kontra != 0
				GROUP BY customer_id
				ORDER BY t2.nama asc", false);

		return $query->result();
	} */

	function get_piutang_list_all($tanggal_start, $tanggal_end){
		
		$query = $this->db->query("SELECT customer_id, t2.nama as nama_customer, t3.nama as nama_toko, 
			sum(sisa_piutang) as sisa_piutang, 
			MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id,
			sum(sisa_kontra) as sisa_kontra, group_concat(sisa_kontra_data) as sisa_kontra_data, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id
			FROM (
				(
					SELECT t1.customer_id, toko_id, tanggal_start, tanggal_end, sisa - ifnull(total_bayar,0) as sisa_piutang, 0 as sisa_kontra, null as pembayaran_piutang_id, null as sisa_kontra_data
					FROM (
						SELECT sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(if(amount_bayar > g_total, 
						g_total, amount_bayar),0)) as sisa, 
						concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, 
						if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, 
						tbl_a.status_aktif, toko_id
						FROM (
							SELECT *
							FROM nd_penjualan 
							WHERE status_aktif = 1
							AND penjualan_type_id != 3
							AND no_faktur != ''
							AND tanggal >='$tanggal_start'
							AND tanggal <='$tanggal_end'
							ORDER BY tanggal desc
							)as tbl_a
						LEFT JOIN (
							SELECT sum(if(pengali_harga = 1,qty,jumlah_roll) *t1.harga_jual) as g_total, penjualan_id, t1.toko_id
							FROM nd_penjualan_detail t1
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								group by penjualan_detail_id
								) t2
							ON t2.penjualan_detail_id = t1.id
							LEFT JOIN nd_barang t3
							ON t1.barang_id = t3.id
							GROUP BY penjualan_id
							) as tbl_b
						ON tbl_b.penjualan_id = tbl_a.id
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, penjualan_id
							FROM nd_pembayaran_penjualan
							WHERE pembayaran_type_id != 5
							GROUP BY penjualan_id
						) tbl_g
						ON tbl_a.id = tbl_g.penjualan_id
						WHERE ifnull(g_total,0) + ongkos_kirim - ifnull(diskon,0) - ifnull(if(amount_bayar > g_total , g_total, amount_bayar),0) > 0
						GROUP BY customer_id
					)t1
					LEFT JOIN (
						SELECT sum(amount) as total_bayar, customer_id
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_detail
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							AND tanggal >='$tanggal_start'
							AND tanggal <='$tanggal_end'
							) b
						ON a.pembayaran_piutang_id = b.id
						WHERE b.id is not null
						GROUP BY customer_id
						) t2
					ON t1.customer_id = t2.customer_id
				)UNION(
					SELECT customer_id, toko_id, min(tanggal_start) as tanggal_start, max(tanggal_end) , 0, sum(sisa_kontra) as sisa_kontra, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id, group_concat(sisa_kontra) as sisa_kontra_data
					FROM (
						SELECT amount, amount_bayar, (amount - ifnull(amount_bayar,0)) as sisa_kontra, customer_id , toko_id, tanggal_start, tanggal_end, t1.pembayaran_piutang_id
						FROM (
							SELECT sum(amount) as amount, pembayaran_piutang_id
							FROM nd_pembayaran_piutang_detail
							GROUP BY pembayaran_piutang_id
						) t1
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, pembayaran_piutang_id, min(tanggal_transfer) as tanggal_start, max(tanggal_transfer) as tanggal_end
							FROM nd_pembayaran_piutang_nilai
							WHERE tanggal_transfer <= '$tanggal_end'
							AND tanggal_transfer >='$tanggal_start'
							GROUP BY pembayaran_piutang_id
						) t2
						ON t1.pembayaran_piutang_id = t2.pembayaran_piutang_id
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							AND tanggal >='$tanggal_start'
							AND tanggal <='$tanggal_end'
							) t3
						ON t1.pembayaran_piutang_id = t3.id
						WHERE t3.id is not null
					)res
					WHERE sisa_kontra > 0
					GROUP BY customer_id
					)
				) t1
				LEFT JOIN nd_customer as t2
				ON t1.customer_id = t2.id
				LEFT JOIN nd_toko t3
				ON t1.toko_id = t3.id
				WHERE sisa_piutang != 0
				OR sisa_kontra != 0
				GROUP BY customer_id
				ORDER BY t2.nama asc", false);

		return $query->result();
	}

	function get_piutang_list_all_by_toko($tanggal_start, $tanggal_end, $select_toko){
		
		$query = $this->db->query("SELECT customer_id, t2.nama as nama_customer, 
			$select_toko group_concat(t3.nama) as nama_toko
			FROM (
				(
					SELECT t1.customer_id, t1.toko_id, tanggal_start, tanggal_end, sisa - ifnull(total_bayar,0) as sisa_piutang, 0 as sisa_kontra, 
					null as pembayaran_piutang_id, null as sisa_kontra_data
					FROM (
						SELECT sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(if(amount_bayar > g_total, 
						g_total, amount_bayar),0)) as sisa, 
						concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, 
						if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, 
						tbl_a.status_aktif, toko_id
						FROM (
							SELECT *
							FROM nd_penjualan 
							WHERE status_aktif = 1
							AND penjualan_type_id != 3
							AND no_faktur != ''
							AND tanggal >='$tanggal_start'
							AND tanggal <='$tanggal_end'	
							ORDER BY tanggal desc
							)as tbl_a
						LEFT JOIN (
							SELECT sum(if(pengali_harga = 1,qty,jumlah_roll) *t1.harga_jual) as g_total, penjualan_id, t1.toko_id
							FROM nd_penjualan_detail t1
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								group by penjualan_detail_id
								) t2
							ON t2.penjualan_detail_id = t1.id
							LEFT JOIN nd_barang t3
							ON t1.barang_id = t3.id
							GROUP BY penjualan_id, toko_id
							) as tbl_b
						ON tbl_b.penjualan_id = tbl_a.id
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, penjualan_id
							FROM nd_pembayaran_penjualan
							WHERE pembayaran_type_id != 5
							GROUP BY penjualan_id
						) tbl_g
						ON tbl_a.id = tbl_g.penjualan_id
						WHERE ifnull(g_total,0) + ongkos_kirim - ifnull(diskon,0) - ifnull(if(amount_bayar > g_total , g_total, amount_bayar),0) > 0
						GROUP BY customer_id, toko_id
					)t1
					LEFT JOIN (
						SELECT sum(amount) as total_bayar, customer_id, toko_id
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_detail
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							AND tanggal >='$tanggal_start'
							AND tanggal <='$tanggal_end'
							) b
						ON a.pembayaran_piutang_id = b.id
						WHERE b.id is not null
						GROUP BY customer_id, toko_id
						) t2
					ON t1.customer_id = t2.customer_id
					AND t1.toko_id = t2.toko_id
				)UNION(
					SELECT customer_id, toko_id, min(tanggal_start) as tanggal_start, max(tanggal_end) , 0, sum(sisa_kontra) as sisa_kontra, 
					group_concat(pembayaran_piutang_id) as pembayaran_piutang_id, group_concat(sisa_kontra) as sisa_kontra_data
					FROM (
						SELECT amount, amount_bayar, (amount - ifnull(amount_bayar,0)) as sisa_kontra, customer_id , toko_id, tanggal_start, tanggal_end, t1.pembayaran_piutang_id
						FROM (
							SELECT sum(amount) as amount, pembayaran_piutang_id
							FROM nd_pembayaran_piutang_detail
							GROUP BY pembayaran_piutang_id
						) t1
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, pembayaran_piutang_id, min(tanggal_transfer) as tanggal_start, max(tanggal_transfer) as tanggal_end
							FROM nd_pembayaran_piutang_nilai
							WHERE tanggal_transfer <= '$tanggal_end'
							AND tanggal_transfer >='$tanggal_start'
							GROUP BY pembayaran_piutang_id
						) t2
						ON t1.pembayaran_piutang_id = t2.pembayaran_piutang_id
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							AND tanggal >='$tanggal_start'
							AND tanggal <='$tanggal_end'
							) t3
						ON t1.pembayaran_piutang_id = t3.id
						WHERE t3.id is not null
					)res
					WHERE sisa_kontra > 0
					GROUP BY customer_id, toko_id
					)
				) t1
				LEFT JOIN nd_customer as t2
				ON t1.customer_id = t2.id
				LEFT JOIN nd_toko t3
				ON t1.toko_id = t3.id
				WHERE sisa_piutang != 0
				OR sisa_kontra != 0
				GROUP BY customer_id
				ORDER BY t2.nama asc", false);

		return $query->result();
	}

	function get_pembayaran_piutang_unbalance(){
		$query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) - amount as balance
				FROM (
					SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
					FROM nd_pembayaran_piutang a
					LEFT JOIN nd_customer b
					ON a.customer_id = b.id
					LEFT JOIN nd_toko c
					ON a.toko_id = c.id
					) tbl_a
				LEFT JOIN (
					SELECT pembayaran_piutang_id, sum(amount) as amount
					FROM nd_pembayaran_piutang_detail
					GROUP BY pembayaran_piutang_id
					) tbl_b
				ON tbl_a.id = tbl_b.pembayaran_piutang_id
				LEFT JOIN (
					SELECT sum(amount) as bayar, pembayaran_piutang_id
					FROM nd_pembayaran_piutang_nilai
					GROUP BY pembayaran_piutang_id
					) tbl_c
				ON tbl_a.id = tbl_c.pembayaran_piutang_id
				WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount != 0

			", false);

		return $query->result();
	}


	function get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id, $cond_jt){
		$query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tbl_e.nama as customer, customer_id, 
		total - ifnull(diskon,0) - ifnull(subdiskon,0) as total_jual, 0 as amount, amount_bayar, 
		ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) - ifnull(diskon,0) - ifnull(subdiskon,0)  as sisa_piutang, ifnull(subdiskon,0) as subdiskon,
		tbl_a.id as penjualan_id, new_jatuh_tempo as jatuh_tempo, tbl_a.tanggal, no_faktur_pertoko
				FROM (
					SELECT *, if(jatuh_tempo = tanggal, DATE_ADD(jatuh_tempo, INTERVAL 30 DAY), jatuh_tempo ) as new_jatuh_tempo
					FROM nd_penjualan
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND customer_id = $customer_id
					AND status_aktif = 1
					AND penjualan_type_id != 3
					AND no_faktur != ''
					) as tbl_a
				LEFT JOIN (
					SELECT t1.id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, t1.toko_id, sum(ifnull(subdiskon,0)) as subdiskon,
					sum(if(pengali_harga=1,qty,jumlah_roll)*t1.harga_jual) as total, t1.harga_jual, penjualan_id, barang_id, t3.satuan_id
					FROM nd_penjualan_detail t1
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP BY penjualan_detail_id
					) t2
					ON t1.id = t2.penjualan_detail_id
					LEFT JOIN nd_barang t3
					ON t1.barang_id = t3.id
					WHERE t1.toko_id = $toko_id
					group by penjualan_id
					) as tbl_c
				ON tbl_c.penjualan_id = tbl_a.id
				LEFT JOIN nd_gudang as tbl_d
				ON tbl_a.gudang_id = tbl_d.id
				LEFT JOIN nd_customer as tbl_e
				ON tbl_e.id = tbl_a.customer_id
				LEFT JOIN (
					SELECT sum(amount) as amount, penjualan_id
					FROM nd_pembayaran_piutang_detail
					WHERE data_status = 1
					GROUP BY penjualan_id
					) tbl_f
				ON tbl_a.id = tbl_f.penjualan_id
				LEFT JOIN (
					SELECT sum(amount) as amount_bayar, penjualan_id
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					GROUP BY penjualan_id
				) tbl_g
				ON tbl_a.id = tbl_g.penjualan_id
				LEFT JOIN (
					SELECT penjualan_id, no_faktur_lengkap as no_faktur_pertoko
					FROM nd_penjualan_invoice
					WHERE toko_id=$toko_id
				) tbl_h
				ON tbl_c.penjualan_id = tbl_h.penjualan_id
				WHERE ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) > 0
				$cond_jt
			", false);

		return $query->result();
	}

	function get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id){
		$query = $this->db->query("SELECT 1, no_faktur, nama as customer, customer_id, amount as total_jual, 0 as amount, ifnull(amount,0) - 0 as sisa_piutang, a.id as penjualan_id, jatuh_tempo
				FROM (
					SELECT *, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
					FROM nd_piutang_awal
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND customer_id = $customer_id
					-- AND toko_id = $toko_id
				) a
				LEFT JOIN nd_customer b
				ON a.customer_id = b.id
				LEFT JOIN (
					SELECT sum(amount) as bayar, penjualan_id
					FROM nd_pembayaran_piutang_detail
					WHERE data_status = 2
					GROUP BY penjualan_id
					) c
				ON a.id = c.penjualan_id
				WHERE ifnull(amount,0) - ifnull(bayar,0) > 0
					
			", false);

		return $query->result();
	}


//===============================piutang payment=============================

	function get_customer_bank_bayar_history($customer_id){
		$query = $this->db->query("SELECT nama_bank, no_rek_bank
				FROM nd_pembayaran_piutang_nilai t1
				LEFT JOIN nd_pembayaran_piutang t2
				ON t1.pembayaran_piutang_id = t2.id
				WHERE nama_bank is not null
				AND nama_bank != ''
				AND customer_id = $customer_id
				GROUP BY nama_bank, no_rek_bank, customer_id
				");

		return $query->result();
	}


	function get_pembayaran_piutang($tanggal_start, $tanggal_end, $cond){
		$query = $this->db->query("SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
				FROM (
					SELECT id, customer_id, toko_id, pembulatan
					FROM (
						(
							SELECT id, customer_id, toko_id, pembulatan
							FROM nd_pembayaran_piutang
							$cond
							AND DATE(created) >= '$tanggal_start'
							AND DATE(created) <= '$tanggal_end'
						)UNION(
							SELECT tbl_b.id, customer_id, toko_id, 0
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_nilai
								WHERE tanggal_transfer >= '$tanggal_start'
								AND tanggal_transfer <= '$tanggal_end'
								
								)tbl_a
							LEFT JOIN nd_pembayaran_piutang tbl_b
							ON tbl_a.pembayaran_piutang_id = tbl_b.id
							$cond
							GROUP BY pembayaran_piutang_id, toko_id, customer_id
						)
					) a
					GROUP BY id,customer_id,toko_id
				)a
				LEFT JOIN nd_customer b
				ON a.customer_id = b.id
				LEFT JOIN nd_toko c
				ON a.toko_id = c.id
			
			", false);

		return $query->result();
		// return $this->db->last_query();
	}

	function get_periode_penjualan($pembayaran_piutang_id){
		$query = $this->db->query("SELECT MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
			FROM nd_penjualan
			WHERE id in (
				SELECT penjualan_id
				FROM nd_pembayaran_piutang_detail
				WHERE pembayaran_piutang_id = $pembayaran_piutang_id
				)
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_data($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_customer, tbl_d.nama as nama_toko
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang
				WHERE id = $id
				) tbl_a
			LEFT JOIN nd_customer tbl_c
			ON tbl_a.customer_id = tbl_c.id
			LEFT JOIN nd_toko tbl_d
			ON tbl_a.toko_id = tbl_d.id
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_awal_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, ifnull(tbl_b.amount,0) - ifnull(total_bayar,0) as sisa_piutang, jatuh_tempo, no_faktur, tbl_b.amount as total_jual, tbl_b.tanggal
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_detail
				WHERE pembayaran_piutang_id = $id
				AND data_status = 2
				) tbl_a
			LEFT JOIN nd_piutang_awal tbl_b
			ON tbl_a.penjualan_id = tbl_b.id
			LEFT JOIN (
				SELECT sum(amount) as total_bayar, penjualan_id
				FROM nd_pembayaran_piutang_detail
				WHERE pembayaran_piutang_id != $id
				AND data_status = 2
				GROUP BY penjualan_id
				) tbl_c
			ON tbl_c.penjualan_id = tbl_b.id
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_detail($id, $toko_id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, ifnull(sisa_piutang,0) - ifnull(total_bayar,0) - ifnull(diskon,0)  as sisa_piutang, 
		jatuh_tempo, no_faktur_pertoko, subdiskon,
		concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur, 
		total_jual - ifnull(diskon,0)  as total_jual, tbl_b.tanggal
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_detail
				WHERE pembayaran_piutang_id = $id
				AND data_status = 1
				) tbl_a
			LEFT JOIN nd_penjualan tbl_b
			ON tbl_a.penjualan_id = tbl_b.id
			LEFT JOIN (
				SELECT sum((if(pengali_harga = 1,qty,jumlah_roll)*t1.harga_jual) - ifnull(subdiskon,0) ) - ifnull(amount_bayar,0) as sisa_piutang, 
				t1.penjualan_id, sum(if(pengali_harga = 1,qty,jumlah_roll)*t1.harga_jual) as total_jual, sum(ifnull(subdiskon,0)) as subdiskon
				FROM nd_penjualan_detail t1
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) t2
				ON t2.penjualan_detail_id = t1.id
				LEFT JOIN (
					SELECT sum(amount) as amount_bayar, penjualan_id
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					GROUP BY penjualan_id
					) t4
				ON t1.penjualan_id = t4.penjualan_id
				LEFT JOIN nd_barang t3
				ON t1.barang_id = t3.id
				WHERE t1.toko_id = $toko_id
				GROUP BY penjualan_id
				) tbl_c
			ON tbl_a.penjualan_id = tbl_c.penjualan_id
			LEFT JOIN (
				SELECT sum(amount) as total_bayar, penjualan_id
				FROM nd_pembayaran_piutang_detail
				WHERE pembayaran_piutang_id != $id
				AND data_status = 1
				GROUP BY penjualan_id
				) tbl_d
			ON tbl_c.penjualan_id = tbl_d.penjualan_id
			LEFT JOIN (
				SELECT penjualan_id, no_faktur_lengkap as no_faktur_pertoko
				FROM nd_penjualan_invoice
				WHERE toko_id=$toko_id
			) tbl_e
			ON tbl_a.penjualan_id = tbl_e.penjualan_id
			", false);

		return $query->result();
	}

	function get_dp_berlaku($customer_id, $pembayaran_piutang_id){
		$query = $this->db->query("SELECT a.*, c.nama as bayar_dp, b.amount as amount_bayar
				FROM (
					SELECT t1.id,amount - ifnull(amount_use,0) - ifnull(amount_bayar,0)  as amount, tanggal, keterangan, no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
					FROM (
						SELECT id,amount, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
						FROM nd_dp_masuk
						WHERE customer_id = $customer_id
					)t1
					LEFT JOIN (
						SELECT dp_masuk_id, sum(amount) as amount_use
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id = 1
						GROUP BY dp_masuk_id
					)t2
					ON t1.id = t2.dp_masuk_id
					LEFT JOIN (
						SELECT sum(amount) as amount_bayar, dp_masuk_id
						FROM nd_pembayaran_piutang_nilai
						WHERE pembayaran_piutang_id != $pembayaran_piutang_id
						GROUP BY dp_masuk_id
						) t3
					ON t1.id = t3.dp_masuk_id
					WHERE amount - ifnull(amount_use,0) - ifnull(amount_bayar,0) > 0
				) a
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_piutang_nilai
					WHERE pembayaran_piutang_id = $pembayaran_piutang_id
					AND pembayaran_type_id = 5
					) b
				ON a.id = b.dp_masuk_id
				LEFT JOIN nd_pembayaran_type c
				ON a.pembayaran_type_id = c.id
		");
		return $query->result();
	}


//===============================daftar giro=============================
	function get_daftar_giro($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT a.*, c.nama as nama_customer, amount
			FROM  (
				SELECT t1.*, tanggal_setor
				FROM (
					SELECT *
					FROM nd_pembayaran_piutang_nilai
					WHERE pembayaran_type_id = 2
					-- AND jatuh_tempo >= '$tanggal_start'
					-- AND jatuh_tempo <= '$tanggal_end'
					) t1
				LEFT JOIN (
					SELECT pembayaran_piutang_nilai_id, tanggal as tanggal_setor
					FROM nd_giro_setor_detail
					LEFT JOIN nd_giro_setor
					ON nd_giro_setor_detail.giro_setor_id = nd_giro_setor.id
					) t2
				ON t1.id = t2.pembayaran_piutang_nilai_id
				
				) a
			LEFT JOIN nd_pembayaran_piutang b
			ON a.pembayaran_piutang_id = b.id
			LEFT JOIN nd_customer c
			ON b.customer_id = c.id
			");

		return $query->result();
	}

	function get_daftar_giro_mentah($cond, $cond2){
		$query = $this->db->query("SELECT a.*, d.nama as nama_customer, amount, a.id as pembayaran_piutang_nilai_id
			FROM  (
				SELECT t1.*
				FROM (
					SELECT *
					FROM nd_pembayaran_piutang_nilai
					WHERE pembayaran_type_id = 2
					$cond
					) t1
				LEFT JOIN nd_pembayaran_piutang t2
				ON t1.pembayaran_piutang_id = t2.id
				$cond2
				) a
			LEFT JOIN nd_giro_setor_detail b
			ON a.id = b.pembayaran_piutang_nilai_id
			LEFT JOIN nd_pembayaran_piutang c 
			ON a.pembayaran_piutang_id = c.id
			LEFT JOIN nd_customer d
			ON c.customer_id = d.id
			WHERE b.id is null
			");

		return $query->result();
	}

	function get_daftar_giro_setor($giro_setor_id){
		$query = $this->db->query("SELECT a.*,b.*, d.nama as nama_customer, amount, a.id as id
			FROM  (
				SELECT *
				FROM nd_giro_setor_detail
				WHERE giro_setor_id = $giro_setor_id
				) a
			LEFT JOIN nd_pembayaran_piutang_nilai b
			ON b.id = a.pembayaran_piutang_nilai_id
			LEFT JOIN nd_pembayaran_piutang c
			ON b.pembayaran_piutang_id = c.id
			LEFT JOIN nd_customer d
			ON c.customer_id = d.id
			-- ORDER BY tanggal_transfer asc
			");

		return $query->result();
	}

//===============================daftar mutasi hutang=============================
	function get_mutasi_hutang($tanggal_start, $tanggal_end, $toko_id){
		// $tanggal = date('Y-m-01', strtotime($tanggal));
		// $tanggal_end = date('Y-m-t', strtotime($tanggal));
		$query = $this->db->query("SELECT amount, amount_bayar, a.nama as nama_supplier, a.id as supplier_id, amount_beli
			FROM nd_supplier a 
			LEFT JOIN (
				SELECT supplier_id, sum(amount) as amount, sum(amount_bayar) as amount_bayar
				FROM (
					(
						SELECT supplier_id, sum(amount_beli) as amount, 0 as amount_bayar
						FROM (
							SELECT *
							FROM nd_pembelian
							WHERE tanggal < '$tanggal_start'
							AND toko_id = $toko_id
							) nd_pembelian
						LEFT JOIN (
							SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
							FROM nd_pembelian_detail t1
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
								FROM nd_pembelian_qty_detail
								GROUP BY pembelian_detail_id
								) t2
							ON t2.pembelian_detail_id = t1.id
							GROUP BY pembelian_id
						) nd_pembelian_detail
						ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
						GROUP BY supplier_id
					) UNION (
						SELECT supplier_id,0, sum(amount)
						FROM (
							SELECT *
							FROM nd_pembayaran_hutang
							WHERE toko_id = $toko_id
						) nd_pembayaran_hutang
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_hutang_nilai 
							WHERE tanggal_transfer < '$tanggal_start'
							)nd_pembayaran_hutang_nilai
						ON nd_pembayaran_hutang.id = nd_pembayaran_hutang_nilai.pembayaran_hutang_id
						WHERE nd_pembayaran_hutang_nilai.id is not null
						GROUP BY supplier_id
					)UNION(
						SELECT supplier_id, sum(amount) as amount, 0 as amount_bayar
						FROM nd_hutang_awal
						WHERE tanggal < '$tanggal_start'
						AND toko_id = $toko_id
						GROUP BY supplier_id

					)
					) t1
				GROUP BY supplier_id
			)b
			ON b.supplier_id = a.id
			LEFT JOIN (
				SELECT supplier_id, sum(amount_beli) as amount_beli
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND toko_id = $toko_id
					) nd_pembelian
				LEFT JOIN (
					SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
					FROM nd_pembelian_detail t1
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
						FROM nd_pembelian_qty_detail
						GROUP BY pembelian_detail_id
						) t2
					ON t2.pembelian_detail_id = t1.id
					GROUP BY pembelian_id
				) nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				GROUP BY supplier_id
			) c
			ON a.id = c.supplier_id

			");

		return $query->result();
	}

	function get_mutasi_hutang_bayar($supplier_id, $toko_id, $tanggal_start,$tanggal_end){
		$tanggal_before = $tanggal_start;
		$tanggal_end = $tanggal_end;
		$query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id
			FROM (
				SELECT *  
				FROM nd_pembayaran_hutang_nilai
				WHERE tanggal_transfer >= '$tanggal_before'
				AND tanggal_transfer <= '$tanggal_end'
				) a
			LEFT JOIN nd_pembayaran_hutang b
			ON a.pembayaran_hutang_id = b.id
			WHERE supplier_id = $supplier_id
			AND toko_id = $toko_id
			GROUP BY pembayaran_type_id
			");

		return $query->result();
		// return $this->db->last_query();
	}

	function get_mutasi_hutang_list_detail($supplier_id, $toko_id, $tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT *
			FROM  (
			(
				SELECT nd_pembelian.tanggal, ifnull(if(no_faktur='',no_surat_jalan,''),no_surat_jalan) as no_faktur, amount_beli, 0 as amount_bayar, if(amount_beli - bayar = 0, 1, 2) as status_lunas
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE supplier_id = $supplier_id 
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND toko_id = $toko_id
					) nd_pembelian
				LEFT JOIN (
					SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
					FROM nd_pembelian_detail t_a
					LEFT JOIN (
						SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
						FROM nd_pembelian_qty_detail
						GROUP BY pembelian_detail_id
					) t_b
					ON t_a.id = t_b.pembelian_detail_id
					GROUP BY pembelian_id
				) nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				LEFT JOIN (
					SELECT SUM(amount) as bayar, pembelian_id
					FROM nd_pembayaran_hutang_detail
					GROUP BY pembelian_id
					) nd_pembayaran_hutang_detail
				ON nd_pembelian.id = nd_pembayaran_hutang_detail.pembelian_id
			) UNION (
				SELECT tanggal_transfer,no_faktur,0, amount, 0 as status_lunas
				FROM (
					SELECT *
					FROM nd_pembayaran_hutang_nilai 
					WHERE tanggal_transfer >= '$tanggal_start'
					AND tanggal_transfer <= '$tanggal_end'
					)nd_pembayaran_hutang_nilai
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_hutang
					WHERE supplier_id = $supplier_id
					AND toko_id = $toko_id
				) nd_pembayaran_hutang
				ON nd_pembayaran_hutang.id = nd_pembayaran_hutang_nilai.pembayaran_hutang_id
				LEFT JOIN (
					SELECT pembayaran_hutang_id, group_concat(no_faktur SEPARATOR', ') as no_faktur
					FROM nd_pembayaran_hutang_detail
					LEFT JOIN nd_pembelian
					ON nd_pembayaran_hutang_detail.pembelian_id = nd_pembelian.id 
					GROUP BY pembayaran_hutang_id
					) nd_pembayaran_hutang_detail
				ON nd_pembayaran_hutang_detail.pembayaran_hutang_id = nd_pembayaran_hutang.id
				WHERE nd_pembayaran_hutang.id is not null
			)UNION(
				SELECT tanggal, concat(no_faktur,' (hutang awal)'), amount, 0 as amount_bayar, 0 as status_lunas
				FROM nd_hutang_awal
				WHERE supplier_id = $supplier_id
				AND toko_id = $toko_id
				AND tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
					
			) 
		)a
		ORDER by tanggal, no_faktur asc
			");

		return $query->result();
	}

	function get_mutasi_hutang_detail_saldo_awal($supplier_id, $toko_id, $tanggal_start){
		$query = $this->db->query("SELECT sum(ifnull(amount_beli,0)) - sum(ifnull(amount_bayar,0)) as saldo_awal
			FROM  (
				(
					SELECT nd_pembelian.tanggal, ifnull(if(no_faktur='',no_surat_jalan,''),no_surat_jalan) as no_faktur, amount_beli, 0 as amount_bayar
					FROM (
						SELECT *
						FROM nd_pembelian
						WHERE supplier_id = $supplier_id 
						AND tanggal < '$tanggal_start'
						AND toko_id = $toko_id
						) nd_pembelian
					LEFT JOIN (
						SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
						FROM nd_pembelian_detail t_a
						LEFT JOIN (
							SELECT pembelian_detail_id, sum(qty* if(jumlah_roll= 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_pembelian_qty_detail
							GROUP BY pembelian_detail_id
						) t_b
						ON t_a.id = t_b.pembelian_detail_id
						GROUP BY pembelian_id
					) nd_pembelian_detail
					ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				) UNION (
					SELECT tanggal_transfer,nd_pembayaran_hutang.id,0, amount
					FROM (
						SELECT *
						FROM nd_pembayaran_hutang
						WHERE supplier_id = $supplier_id
						AND toko_id = $toko_id
					) nd_pembayaran_hutang
					LEFT JOIN (
						SELECT *
						FROM nd_pembayaran_hutang_nilai 
						WHERE tanggal_transfer < '$tanggal_start'
						)nd_pembayaran_hutang_nilai
					ON nd_pembayaran_hutang.id = nd_pembayaran_hutang_nilai.pembayaran_hutang_id
					WHERE nd_pembayaran_hutang_nilai.id is not null
				) UNION (
					SELECT tanggal, concat(no_faktur, '(hutang awal)') , amount, 0 as amount_bayar
					FROM nd_hutang_awal
					WHERE supplier_id = $supplier_id
					AND toko_id = $toko_id
					AND tanggal < '$tanggal_start'

				)
			)a
			");

		return $query->result();
	}


//===============================daftar mutasi piutang=============================

	function get_mutasi_piutang($tanggal_start,$tanggal_end, $toko_id){
		// $tanggal = date('Y-m-01', strtotime($tanggal));
		// $tanggal_end = date('Y-m-t', strtotime($tanggal));

		$query = $this->db->query("SELECT sum(amount) as amount, sum(amount_bayar) as amount_bayar, a.nama as nama_customer, a.id as customer_id, sum(penjualan) as penjualan
			FROM nd_customer a 
			LEFT JOIN (
				SELECT customer_id, sum(amount) as amount, sum(amount_bayar) as amount_bayar
				FROM (
					(
						SELECT customer_id, sum(ifnull(amount,0)) as amount, amount_bayar
						FROM (
							(
								SELECT customer_id, sum(ifnull(amount_jual,0) -ifnull(diskon,0) )  as amount, sum(if(pembayaran > amount_jual, amount_jual, pembayaran) ) as amount_bayar
								FROM (
									SELECT *
									FROM nd_penjualan
									WHERE tanggal < '$tanggal_start'
									AND toko_id = $toko_id
									AND penjualan_type_id != 3
									AND status_aktif = 1
									AND no_faktur != ''
									AND no_faktur is not null
									) nd_penjualan
								LEFT JOIN (
									SELECT sum(harga_jual * if(pengali_harga = 1, qty, jumlah_roll) ) as amount_jual, penjualan_id
									FROM nd_penjualan_detail t1
									LEFT JOIN (
										SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
										FROM nd_penjualan_qty_detail
										GROUP BY penjualan_detail_id
									) t2
									ON t2.penjualan_detail_id = t1.id
									GROUP BY penjualan_id
								) nd_penjualan_detail
								ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
								LEFT JOIN (
									SELECT sum(amount) as pembayaran, penjualan_id
									FROM nd_pembayaran_penjualan
									WHERE pembayaran_type_id != 5
									AND amount != 0
									GROUP BY penjualan_id
									) nd_pembayaran_penjualan
								ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
								GROUP BY customer_id
							)UNION (
								SELECT customer_id, sum(amount), 0
								FROM nd_piutang_awal
								WHERE tanggal < '$tanggal_start'
								AND toko_id = $toko_id
								GROUP BY customer_id
								
							)
						) t1
						GROUP BY customer_id
					) UNION (
						SELECT customer_id,0, sum(amount)
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE toko_id = $toko_id
						) nd_pembayaran_piutang
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang_nilai 
							WHERE tanggal_transfer < '$tanggal_start'
							)nd_pembayaran_piutang_nilai
						ON nd_pembayaran_piutang.id = nd_pembayaran_piutang_nilai.pembayaran_piutang_id
						WHERE nd_pembayaran_piutang_nilai.id is not null
						GROUP BY customer_id
					)UNION (
						SELECT customer_id, 0, sum(pembulatan)
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_nilai
							WHERE tanggal_transfer < '$tanggal_start'
							GROUP BY pembayaran_piutang_id
						) t1
						LEFT JOIN nd_pembayaran_piutang t2
						ON t1.pembayaran_piutang_id = t2.id
						GROUP BY customer_id
					)
				) result
				GROUP BY customer_id
			)b
			ON b.customer_id = a.id
			LEFT JOIN (
				SELECT customer_id, sum(ifnull(amount_jual,0) - ifnull(diskon,0)) as penjualan
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND toko_id = $toko_id
					AND penjualan_type_id != 3
					
					AND status_aktif = 1
					) nd_penjualan
				LEFT JOIN (
					SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
					FROM nd_penjualan_detail t1
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP BY penjualan_detail_id
					) t2
					ON t2.penjualan_detail_id = t1.id
					GROUP BY penjualan_id
				) nd_penjualan_detail
				ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
				GROUP BY customer_id
			) c
			ON c.customer_id = a.id
			GROUP BY customer_id
			ORDER BY nama asc

			");

		return $query->result();
	}

	function get_mutasi_piutang_bayar($customer_id, $toko_id, $tanggal_start, $tanggal_end){
		$tanggal_before = $tanggal_start;
		// $tanggal_end = date('Y-m-t', strtotime($tanggal));
		$query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id
			FROM (
				SELECT *  
				FROM nd_pembayaran_piutang_nilai
				WHERE tanggal_transfer >= '$tanggal_before'
				AND tanggal_transfer <= '$tanggal_end'
				) a
			LEFT JOIN nd_pembayaran_piutang b
			ON a.pembayaran_piutang_id = b.id
			WHERE customer_id = $customer_id
			AND toko_id = $toko_id
			GROUP BY pembayaran_type_id
			");

		return $query->result();
		// return $this->db->last_query();
	}

	function get_bayar_penjualan($customer_id, $toko_id, $tanggal_start, $tanggal_end){
		$tanggal_before = $tanggal_start;
		// $tanggal_end = date('Y-m-t', strtotime($tanggal));
		$query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id
			FROM (
				SELECT *  
				FROM nd_penjualan
				WHERE customer_id = $customer_id
				AND penjualan_type_id != 3
				AND toko_id = $toko_id
				AND tanggal >= '$tanggal_before'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
				) a
			LEFT JOIN nd_pembayaran_penjualan b
			ON a.id = b.penjualan_id
			GROUP BY pembayaran_type_id
			");

		return $query->result();
		// return $this->db->last_query();
	}

	function get_mutasi_piutang_list_detail($customer_id, $toko_id, $tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT *
			FROM  (
			(
			 	SELECT nd_penjualan.tanggal,nd_penjualan.id, no_faktur_lengkap as no_faktur, amount_jual - ifnull(diskon,0) as amount_jual , 0 as amount_bayar,'jual' as ket
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE customer_id = $customer_id 
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND toko_id = $toko_id
					AND penjualan_type_id != 3
					AND status_aktif = 1
					) nd_penjualan
				LEFT JOIN (
					SELECT sum(harga_jual * if(pengali_harga=1,qty, jumlah_roll)) as amount_jual, penjualan_id
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty* if(jumlah_roll = 0,1, jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP BY penjualan_detail_id
						) nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
				) nd_penjualan_detail
				ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
			)UNION(
				SELECT tanggal, nd_penjualan.id, no_faktur_lengkap,0, bayar, 'bayar jual'
				FROM (
					SELECT sum(amount) as bayar, penjualan_id
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					AND amount != 0
					GROUP BY penjualan_id
				) nd_pembayaran_penjualan
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan
					WHERE customer_id = $customer_id 
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND toko_id = $toko_id
					AND penjualan_type_id != 3
					AND status_aktif = 1
					) nd_penjualan
				ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
				WHERE nd_penjualan.id is not null
			) UNION (
				SELECT tanggal_transfer,nd_pembayaran_piutang.id,no_faktur,0, amount, 'bayar piutang'
				FROM (
					SELECT *
					FROM nd_pembayaran_piutang
					WHERE customer_id = $customer_id
					AND toko_id = $toko_id
					) nd_pembayaran_piutang
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_piutang_nilai
					WHERE tanggal_transfer >= '$tanggal_start'
					AND tanggal_transfer <= '$tanggal_end'
					) nd_pembayaran_piutang_nilai
				ON nd_pembayaran_piutang.id = nd_pembayaran_piutang_nilai.pembayaran_piutang_id
				LEFT JOIN (
					SELECT pembayaran_piutang_id, group_concat(no_faktur SEPARATOR', ') as no_faktur
					FROM (
						(
							SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap ORDER BY no_faktur SEPARATOR', ' ) as no_faktur
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_detail
								WHERE data_status = 1
							) nd_pembayaran_piutang_detail
							LEFT JOIN (
								SELECT *
								FROM nd_penjualan
								) nd_penjualan
							ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
							GROUP BY pembayaran_piutang_id
						)UNION(
							SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap SEPARATOR ' ,')
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_detail
								WHERE data_status = 2
							) nd_pembayaran_piutang_detail
							LEFT JOIN (
								SELECT *, concat(no_faktur,' (piutang awal)') as no_faktur_lengkap
								FROM nd_piutang_awal
								) nd_penjualan
							ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
							GROUP BY pembayaran_piutang_id
						)
					) a
					GROUP BY pembayaran_piutang_id
				) nd_pembayaran_piutang_detail
				ON nd_pembayaran_piutang_detail.pembayaran_piutang_id = nd_pembayaran_piutang.id
				WHERE nd_pembayaran_piutang_nilai.id is not null
			) UNION(
				SELECT tanggal,id, concat(no_faktur,' (piutang awal)'), amount, 0 as amount_bayar,'piutang awal'
				FROM nd_piutang_awal
				WHERE customer_id = $customer_id
				AND toko_id = $toko_id
				AND tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
					
			) UNION (
				SELECT tanggal_transfer, b.id, 'Pembulatan', 0, pembulatan, '' 
				FROM (
					SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_piutang_id
					FROM nd_pembayaran_piutang_nilai
					WHERE tanggal_transfer >= '$tanggal_start'
					AND tanggal_transfer <= '$tanggal_end'
					GROUP BY pembayaran_piutang_id
					) a
				LEFT JOIN nd_pembayaran_piutang b
				ON a.pembayaran_piutang_id = b.id
				WHERE customer_id = $customer_id
				AND toko_id = $toko_id
				AND pembulatan != 0
			)
		)a
		ORDER by tanggal, no_faktur asc
		");

		return $query->result();
		// return $this->db->last_query();

	}

	function get_mutasi_piutang_saldo_awal($customer_id, $toko_id, $tanggal){
		$query = $this->db->query("SELECT sum(ifnull(amount_beli,0)) - sum(ifnull(amount_bayar,0)) as saldo_awal
			FROM  (
				(
					SELECT nd_penjualan.tanggal, no_faktur_lengkap as no_faktur, amount_beli - ifnull(diskon,0) as amount_beli, pembayaran as amount_bayar
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE customer_id = $customer_id 
						AND tanggal < '$tanggal'
						AND toko_id = $toko_id
						AND status_aktif = 1
						AND penjualan_type_id !=3
					) nd_penjualan
					LEFT JOIN (
						SELECT sum(harga_jual * if(pengali_harga=1,qty, jumlah_roll)) as amount_beli, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
						) nd_penjualan_qty_detail
						ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
						GROUP BY penjualan_id
					) nd_penjualan_detail
					ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
					LEFT JOIN (
						SELECT sum(amount) as pembayaran, penjualan_id
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id != 5
						GROUP BY penjualan_id
						) nd_pembayaran_penjualan
					ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
				) UNION (
					SELECT tanggal_transfer,nd_pembayaran_piutang.id,0, amount_bayar
					FROM (
						SELECT *
						FROM nd_pembayaran_piutang
						WHERE customer_id = $customer_id
						AND toko_id = $toko_id
						) nd_pembayaran_piutang
					LEFT JOIN (
						SELECT sum(amount) as amount_bayar, pembayaran_piutang_id, tanggal_transfer
						FROM nd_pembayaran_piutang_nilai
						WHERE tanggal_transfer < '$tanggal'
						GROUP BY pembayaran_piutang_id
						) nd_pembayaran_piutang_nilai
					ON nd_pembayaran_piutang.id = nd_pembayaran_piutang_nilai.pembayaran_piutang_id
				)UNION(
					SELECT tanggal, no_faktur, amount, 0 as amount_bayar
					FROM nd_piutang_awal
					WHERE customer_id = $customer_id 
					AND tanggal < '$tanggal'
					AND toko_id = $toko_id
				)UNION (
					SELECT tanggal_transfer, b.id, 0, pembulatan 
					FROM (
						SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_piutang_id
						FROM nd_pembayaran_piutang_nilai
						WHERE tanggal_transfer < '$tanggal'
						GROUP BY pembayaran_piutang_id
						) a
					LEFT JOIN nd_pembayaran_piutang b
					ON a.pembayaran_piutang_id = b.id
					WHERE customer_id = $customer_id
					AND toko_id = $toko_id
				)
			)a
			");

		return $query->result();
	}

	function get_pembulatan_piutang($customer_id, $toko_id, $tanggal_start, $tanggal_end){
		$tanggal_before = $tanggal_start;
		// $tanggal_end = date('Y-m-t', strtotime($tanggal));
		$query = $this->db->query("SELECT sum(pembulatan) as pembulatan 
			FROM (
				SELECT *  
				FROM nd_pembayaran_piutang_nilai
				WHERE tanggal_transfer >= '$tanggal_before'
				AND tanggal_transfer <= '$tanggal_end'
				GROUP BY pembayaran_piutang_id
				) a
			LEFT JOIN nd_pembayaran_piutang b
			ON a.pembayaran_piutang_id = b.id
			WHERE customer_id = $customer_id
			AND toko_id = $toko_id
			");

		return $query->result();
		// return $this->db->last_query();
	}

//===========================================================================================

	function get_retur_jual($toko_id, $customer_id){
		$tanggal_awal = "2024-09-01";

		$query = $this->db->query("SELECT tA.* , tA.id as penjualan_id, 0 as amount, 
			ifnull(tB.total,0) - ifnull(tC.amount,0) as sisa_piutang
			FROM nd_retur_jual tA
			LEFT JOIN (
				SELECT (subqty * harga) as total, retur_jual_id
				FROM nd_retur_jual_detail
			) tB
			ON tA.id = tB.retur_jual_id
			LEFT JOIN (
				SELECT *
				FROM nd_pembayaran_piutang_detail
				WHERE data_status = 3
				) tC
			ON tA.id = tC.penjualan_id
			WHERE tA.tipe_bayar = 5
			AND ifnull(tB.total,0) - ifnull(tC.amount,0) > 0 
			AND tA.status_aktif = 1
			AND tA.tanggal >= '$tanggal_awal'
			");

		return $query->result();
	}

	function get_pembayaran_retur_jual_detail($pembayaran_piutang_id){
		$query = $this->db->query("SELECT t0.*, no_faktur_lengkap, (total - ifnull(potongan_harga,0)) as total, tanggal, (total - ifnull(potongan_harga,0)) as sisa_piutang
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_detail
				WHERE pembayaran_piutang_id = $pembayaran_piutang_id
				AND data_status = 3
			) t0
			LEFT JOIN nd_retur_jual tA
			ON t0.penjualan_id = tA.id
			LEFT JOIN (
				SELECT (subqty * harga) as total, retur_jual_id
				FROM nd_retur_jual_detail
			) tB
			ON tA.id = tB.retur_jual_id 
			");

		return $query->result();
	}

}