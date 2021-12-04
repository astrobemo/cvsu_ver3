<?php

class Akunting_Model extends CI_Model {


//==============================ambil akun list===================================

	function get_akun_list($kode_akun_id)
	{
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as tipe_akun
			FROM (
				SELECT *
				FROM nd_kode_akun
				where kode_akun_id = $kode_akun_id
				) as tbl_a
			LEFT JOIN nd_tipe_akun_master as tbl_b
			ON tbl_a.tipe_akun_master_id = tbl_b.id ");
		return $query->result();
	}

}