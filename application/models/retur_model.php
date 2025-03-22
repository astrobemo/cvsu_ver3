<?php

class Retur_Model extends CI_Model {

	// public function __construct() {
    //     parent::__construct();
    //     $this->load->database('mysqli');
    // }

	function retur_detail_insert($data_detail, $rekap){
		try {
			$this->db->trans_start();
			$this->db->insert("nd_retur_jual_detail", $data_detail);
			$result_id = $this->db->insert_id();
	
			foreach ($rekap as $key => $value) {
				$qty = explode('??', $value);
				$data_qty[$key] = array(
					'retur_jual_detail_id' => $result_id,
					'qty' => $qty[0],
					'jumlah_roll' => $qty[1] ); 
			}
			
			$this->db->insert_batch("nd_retur_jual_qty", $data_qty);
			$this->db->trans_complete();

			return $this->db->trans_status();
		} catch (Exception $e) {
			//throw $th;
			echo 'Error: ' . $e->getMessage();
		}
	}

	function get_penjualan_list($max_tanggal){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_customer 
			FROM nd_penjualan t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id 
			WHERE tanggal >='$max_tanggal' 
			AND no_faktur_lengkap is not null
			");

		return $query->result();
			
	}

	function get_penjualan_list_for_retur($max_tanggal){
		$query = $this->db->query("SELECT t1.id, concat(no_faktur_lengkap, ' - ', if(t1.customer_id != 0, t2.nama, nama_keterangan)) as text
			FROM nd_penjualan t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id 
			WHERE tanggal >='$max_tanggal' 
			AND no_faktur_lengkap is not null
			");

		return $query->result();
			
	}


//==========================================retur barang================================

	function get_retur_list(){
		$query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_customer, nama_keterangan, 
		username, created_date, no_faktur_lengkap, group_concat(ifnull(harga,0)) as harga, 
		group_concat(qty) as qty, group_concat(jumlah_roll) as jumlah_roll, nama_barang, 
		group_concat(nama_gudang) as nama_gudang, group_concat(nama_barang) as nama_barang,
		group_concat(warna_jual) as nama_warna, group_concat(nama_gudang) as nama_gudang
			FROM nd_retur_jual tbl_a
			LEFT JOIN (
				SELECT nd_retur_jual_detail.*, qty, jumlah_roll, retur_jual_detail_id, nd_barang.nama_jual as nama_barang, nd_gudang.nama as nama_gudang, warna_jual
				FROM nd_retur_jual_detail
				LEFT JOIN (
					SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					FROM nd_retur_jual_qty
					GROUP BY retur_jual_detail_id 
					) nd_retur_jual_qty
				ON nd_retur_jual_detail.id = nd_retur_jual_qty.retur_jual_detail_id
				LEFT JOIN nd_barang
				ON nd_retur_jual_detail.barang_id = nd_barang.id
				LEFT JOIN nd_gudang
				ON nd_retur_jual_detail.gudang_id = nd_gudang.id
				LEFT JOIN nd_warna
				ON nd_retur_jual_detail.warna_id = nd_warna.id
			) tbl_b
			ON tbl_a.id = tbl_b.retur_jual_id
			LEFT JOIN nd_customer tbl_c
			ON tbl_a.customer_id = tbl_c.id
			LEFT JOIN nd_user tbl_d
			ON tbl_a.user_id = tbl_d.id
			GROUP BY tbl_a.id
		");
		return $query->result();
	}

	function get_retur_data($id){
		$query = $this->db->query("SELECT tbl_a.*, nama_keterangan,
		tbl_c.nama as nama_customer, 
		username, created_date, no_faktur_lengkap, 
		if(tbl_a.retur_type_id = 1, tbl_c.alamat, '-') as alamat
			FROM (
				SELECT *
				FROM nd_retur_jual
				WHERE id = $id
			) tbl_a
			LEFT JOIN (
				SELECT *
				FROM nd_retur_jual_qty
				) tbl_b
			ON tbl_a.id = tbl_b.retur_jual_detail_id
			LEFT JOIN nd_customer tbl_c
			ON tbl_a.customer_id = tbl_c.id
			LEFT JOIN nd_user tbl_d
			ON tbl_a.user_id = tbl_d.id
		");
		return $query->result();
	}

	function get_retur_barang($id){
		$query = $this->db->query("SELECT tB.*, nama_jual, warna_jual, tE.nama as nama_satuan, 
		tF.nama as nama_packaging, qty_data
			FROM (
				SELECT *
				FROM nd_retur_jual 
				WHERE id = $id
			) tA
			LEFT JOIN nd_penjualan_detail tB
			ON tA.penjualan_id = tB.penjualan_id
			LEFT JOIN (
				SELECT penjualan_detail_id, group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
				FROM nd_penjualan_qty_detail
				GROUP BY penjualan_detail_id
				) tB1
			ON tB.id = tB1.penjualan_detail_id
			LEFT JOIN nd_barang tC
			ON tB.barang_id = tC.id
			LEFT JOIN nd_warna tD
			ON tB.warna_id = tD.id
			LEFT JOIN nd_satuan tE
			ON tC.satuan_id = tE.id
			LEFT JOIN nd_satuan tF
			ON tC.packaging_id = tF.id
		");
		return $query->result();
	}

	function get_retur_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, jumlah_roll, 
		tbl_c.nama_jual as nama_barang, tbl_d.warna_jual as nama_warna, 
		tbl_e.nama as nama_satuan, data_qty, qty, jumlah_roll, tbl_f.nama as nama_gudang
			FROM (
				SELECT *
				FROM nd_retur_jual_detail 
				WHERE retur_jual_id = $id
			) tbl_a
			LEFT JOIN (
				SELECT retur_jual_detail_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, 
				group_concat(concat_ws('??',qty, jumlah_roll) SEPARATOR '--') as data_qty 
				FROM nd_retur_jual_qty
				GROUP BY retur_jual_detail_id
				) tbl_b
			ON tbl_a.id = tbl_b.retur_jual_detail_id
			LEFT JOIN nd_barang tbl_c
			ON tbl_a.barang_id = tbl_c.id
			LEFT JOIN nd_warna tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN nd_satuan tbl_e
			ON tbl_c.satuan_id = tbl_e.id
			LEFT JOIN nd_gudang tbl_f
			ON tbl_a.gudang_id = tbl_f.id
		");
		return $query->result();
	}

	function get_retur_jual_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, 
		tbl_d.warna_jual as nama_warna, tbl_e.qty as qty, tbl_e.jumlah_roll as jumlah_roll, data_qty
			FROM (
				SELECT *
				FROM nd_retur_jual_detail
				WHERE retur_jual_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_qty
				FROM nd_retur_jual_qty
				group by retur_jual_detail_id
				) as tbl_e
			ON tbl_e.retur_jual_detail_id = tbl_a.id
			", false);

		return $query->result();
	}


	function retur_jual_detail_remove($id){
		$this->db->query("DELETE FROM nd_retur_jual_detail WHERE id = $id");

		/* ALTER TABLE nd_retur_jual_qty; 
		ALTER TABLE nd_retur_jual_qty ADD CONSTRAINT fk_retur_qty_detail 
		FOREIGN KEY (`retur_jual_detail_id`) REFERENCES nd_retur_jual_detail(`id`) ON DELETE CASCADE ON UPDATE NO ACTION; */
	}

}