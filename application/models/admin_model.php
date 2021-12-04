<?php

class Admin_Model extends CI_Model {

//=============================recap dashboard==================
	function recap_pembelian_bulanan($tanggal_start, $tanggal_end)
	{
		$query = $this->db->query("SELECT sum(amount) as amount 
			FROM (
				SELECT *
				FROM nd_pembelian
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT (t1.harga_beli*if(pengali_harga_beli = 1,qty,jumlah_roll)) as amount, pembelian_id
				FROM nd_pembelian_detail t1
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
					FROM nd_pembelian_qty_detail
					GROUP BY pembelian_detail_id
					) t3
				ON t3.pembelian_detail_id = t1.id
				LEFT JOIN nd_barang t2
				ON t1.barang_id = t2.id
				) as tbl_b
			ON tbl_a.id = tbl_b.pembelian_id
			");
		return $query->result();
	}

	function recap_penjualan_bulanan($tanggal_start, $tanggal_end)
	{
		$query = $this->db->query("SELECT sum(amount) as amount 
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT (t1.harga_jual*if(pengali_harga = 1,qty,jumlah_roll)) as amount, penjualan_id
				FROM nd_penjualan_detail t1
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					)t2
				ON t1.id = t2.penjualan_detail_id
				LEFT JOIN nd_barang
				ON t1.barang_id = nd_barang.id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			");
		return $query->result();
	}

	function get_list_penjualan_by_date($date_start, $date_end){
		$query = $this->db->query("SELECT tanggal, sum(amount) as amount
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				and DATE(tanggal) >= '$date_start'
				AND DATE(tanggal) <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(harga_jual*qty) as amount, penjualan_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty *if(jumlah_roll = 0,1,jumlah_roll) ) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				group by penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			group by DATE(tanggal)
			");
		return $query->result();
	}

	function get_list_penjualan_tahunan($date_start, $date_end){
		$query = $this->db->query("SELECT MONTHNAME(tanggal) as tanggal, sum(amount)/1000 as amount
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				and tanggal >= '$date_start'
				AND tanggal <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(t1.harga_jual * if(pengali_harga = 1,qty,jumlah_roll) ) as amount, penjualan_id
				FROM nd_penjualan_detail t1
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) t2
				ON t1.id = t2.penjualan_detail_id
				LEFT JOIN nd_barang 
				ON t1.barang_id = nd_barang.id
				group by penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			group by MONTH(tanggal)
			");
		return $query->result();
	}

//===========================================best seller===========================================

	function get_barang_jual_terbanyak($year)
	{
		$query = $this->db->query("SELECT concat_ws(' ',tbl_c.nama,tbl_d.warna_beli ) as barang, sum(qty) as qty 
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE YEAR(tanggal) = '$year'
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT qty, penjualan_id, barang_id, warna_id 
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			LEFT JOIN nd_barang tbl_c
			ON tbl_b.barang_id = tbl_c.id
			LEFT JOIN nd_warna tbl_d
			ON tbl_b.warna_id = tbl_d.id
			WHERE barang_id is not null
			group by barang_id
			order by qty desc
			limit 10
			");
		return $query->result();
	}

	function get_barang_jual_warna_terbanyak($year)
	{
		$query = $this->db->query("SELECT tbl_d.warna_beli as barang, sum(qty) as qty 
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE YEAR(tanggal) = '$year'
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT qty, penjualan_id, barang_id, warna_id 
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			LEFT JOIN nd_barang tbl_c
			ON tbl_b.barang_id = tbl_c.id
			LEFT JOIN nd_warna tbl_d
			ON tbl_b.warna_id = tbl_d.id
			WHERE barang_id is not null
			group by warna_id
			order by qty desc
			limit 10
			");
		return $query->result();
	}

//===========================================best buyer===========================================

	function get_customer_beli_terbanyak($year)
	{
		$query = $this->db->query("SELECT tbl_c.nama as nama_customer, sum(amount) as amount 
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE YEAR(tanggal) = '$year'
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT sum(t1.harga_jual*if(pengali_harga = 1,qty,jumlah_roll)) as amount, penjualan_id
				FROM nd_penjualan_detail t1
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty,sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as t2
				ON t1.id = t2.penjualan_detail_id
				LEFT JOIN nd_barang 
				ON t1.barang_id = nd_barang.id
				GROUP BY penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			LEFT JOIN nd_customer as tbl_c
			ON tbl_a.customer_id = tbl_c.id
			WHERE customer_id != 0
			AND customer_id is not null
			group by customer_id
			order by amount desc
			limit 10
			");
		return $query->result();
	}

	function get_customer_beli_terbanyak_pie($year)
	{
		$query = $this->db->query("SELECT *
			FROM(
			(
				SELECT tbl_c.nama as nama_customer, sum(amount) as amount 
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE YEAR(tanggal) = '$year'
					AND status_aktif = 1
					) as tbl_a
				LEFT JOIN (
					SELECT sum(t1.harga_jual*if(pengali_harga = 1,qty,jumlah_roll)) as amount, penjualan_id
					FROM nd_penjualan_detail t1
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP BY penjualan_detail_id
						) t2
					ON t1.id = t2.penjualan_detail_id
					LEFT JOIN nd_barang 
					ON t1.barang_id = nd_barang.id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_a.id = tbl_b.penjualan_id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				WHERE customer_id != 0
				AND customer_id is not null
				group by customer_id
				order by amount desc
				limit 10

				)UNION(
				SELECT 'other' as nama_customer, sum(amount)
				FROM(
					SELECT tbl_c.nama as nama_customer, sum(amount) as amount 
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT sum(qty*harga_jual) as amount, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_customer as tbl_c
					ON tbl_a.customer_id = tbl_c.id
					WHERE customer_id != 0
					AND customer_id is not null
					group by customer_id
					order by amount desc
					limit 10, 10000000
					) tbl_a
				)
			) A
			");
		return $query->result();
	}

//==============================================================================

	function get_notifikasi_akunting_report(){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_customer, username
			FROM nd_notifikasi_akunting t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_user t3
			ON t1.read_by = t3.id
			");
		return $query->result();
	}


//============================faktur kosong=====================================

	function get_notifikasi_faktur_kosong(){
		$today = date('Y-m-d');
		$query = $this->db->query("SELECT *
			FROM (
				SELECT count_data, sum(1) as count_data_penjualan
				FROM (
					SELECT sum(1) as count_data, penjualan_id
					FROM nd_penjualan_detail
					GROUP BY penjualan_id
					) t2
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan
					WHERE tanggal < '$today'
					AND no_faktur is null
					AND status_aktif = 1
					) t1
				ON t2.penjualan_id = t1.id
				WHERE t1.id is not null
			)result
			WHERE count_data is not null
			");
		return $query->result();
	}

}

?>