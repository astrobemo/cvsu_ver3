<?php

class Retur_Beli_Model extends CI_Model {

	// public function __construct() {
    //     parent::__construct();
    //     $this->load->database('mysqli');
    // }

	function get_pembelian($max_tanggal){
		$query = $this->db->query("SELECT t1.*, nd_supplier.nama as nama_supplier
			FROM nd_pembelian t1
			LEFT JOIN nd_supplier
			ON t1.supplier_id =nd_supplier.id
			WHERE t1.tanggal >= '$max_tanggal'
		");
		return $query->result();
	}

	// function retur_detail_insert($data_detail, $rekap){
	// 	$this->db->trans_start();
	// 	$result_id = $this->db->insert("nd_retur_beli_detail", $data_detail);

	// 	foreach ($rekap as $key => $value) {
	// 		$qty = explode('??', $value);
	// 		$data_qty[$key] = array(
	// 			'retur_beli_detail_id' => $result_id,
	// 			'qty' => $qty[0],
	// 			'jumlah_roll' => $qty[1] ); 
	// 	}
		
	// 	$this->db->insert_batch("nd_retur_beli_qty", $data_qty);
	// 	$this->db->trans_complete();
	// }


//==========================================retur barang================================

	function get_retur_list(){
		$query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_supplier, 
		username, created_date, group_concat(ifnull(harga,0)) as harga, 
		group_concat(qty) as qty, group_concat(jumlah_roll) as jumlah_roll, nama_barang, 
		group_concat(nama_gudang) as nama_gudang, group_concat(nama_barang) as nama_barang,
		group_concat(warna_beli) as nama_warna, group_concat(nama_gudang) as nama_gudang
			FROM (
				SELECT *
				FROM nd_retur_beli
			) tbl_a
			LEFT JOIN (
				SELECT nd_retur_beli_detail.*, qty, jumlah_roll, retur_beli_detail_id, nd_barang.nama as nama_barang, nd_gudang.nama as nama_gudang, warna_beli
				FROM nd_retur_beli_detail
				LEFT JOIN (
					SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
					FROM nd_retur_beli_qty
					GROUP BY retur_beli_detail_id 
					) nd_retur_beli_qty
				ON nd_retur_beli_detail.id = nd_retur_beli_qty.retur_beli_detail_id
				LEFT JOIN nd_barang
				ON nd_retur_beli_detail.barang_id = nd_barang.id
				LEFT JOIN nd_gudang
				ON nd_retur_beli_detail.gudang_id = nd_gudang.id
				LEFT JOIN nd_warna
				ON nd_retur_beli_detail.warna_id = nd_warna.id
			) tbl_b
			ON tbl_a.id = tbl_b.retur_beli_id
			LEFT JOIN nd_supplier tbl_c
			ON tbl_a.supplier_id = tbl_c.id
			LEFT JOIN nd_user tbl_d
			ON tbl_a.user_id = tbl_d.id
			GROUP BY tbl_a.id
		");
		return $query->result();
	}

	function get_retur_data($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_supplier, 
		username, created_date, tbl_c.alamat as alamat
			FROM (
				SELECT *
				FROM nd_retur_beli
				WHERE id = $id
			) tbl_a
			LEFT JOIN (
				SELECT *
				FROM nd_retur_beli_qty
				) tbl_b
			ON tbl_a.id = tbl_b.retur_beli_detail_id
			LEFT JOIN nd_supplier tbl_c
			ON tbl_a.supplier_id = tbl_c.id
			LEFT JOIN nd_user tbl_d
			ON tbl_a.user_id = tbl_d.id
		");
		return $query->result();
	}

	function get_retur_barang($id){
		$query = $this->db->query("SELECT tB.*, tC.nama as nama_barang, warna_beli, 
		tE.nama as nama_satuan, 
		tF.nama as nama_packaging, qty_data
			FROM (
				SELECT *
				FROM nd_retur_beli 
				WHERE id = $id
			) tA
			LEFT JOIN nd_pembelian_detail tB
			ON tA.pembelian_id = tB.pembelian_id
			LEFT JOIN (
				SELECT pembelian_detail_id, group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
				FROM nd_pembelian_qty_detail
				GROUP BY pembelian_detail_id
				) tB1
			ON tB.id = tB1.pembelian_detail_id
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
		$query = $this->db->query("SELECT tbl_a.*, jumlah_roll, tbl_c.nama as nama_barang, tbl_d.warna_beli as nama_warna, 
		tbl_e.nama as nama_satuan, data_qty, qty, jumlah_roll, tbl_f.nama as nama_gudang
			FROM (
				SELECT *
				FROM nd_retur_beli_detail 
				WHERE retur_beli_id = $id
			) tbl_a
			LEFT JOIN (
				SELECT retur_beli_detail_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, 
				group_concat(concat_ws('??',qty, jumlah_roll) SEPARATOR '--') as data_qty 
				FROM nd_retur_beli_qty
				GROUP BY retur_beli_detail_id
				) tbl_b
			ON tbl_a.id = tbl_b.retur_beli_detail_id
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

	function get_retur_beli_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, 
		tbl_d.warna_beli as nama_warna, tbl_e.qty as qty, tbl_e.jumlah_roll as jumlah_roll, data_qty
			FROM (
				SELECT *
				FROM nd_retur_beli_detail
				WHERE retur_beli_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama as nama_barang, nd_satuan.nama as nama_satuan 
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
				SELECT sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_qty
				FROM nd_retur_beli_qty
				group by retur_beli_detail_id
				) as tbl_e
			ON tbl_e.retur_beli_detail_id = tbl_a.id
			", false);

		return $query->result();
	}


}