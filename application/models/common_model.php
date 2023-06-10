<?php

class Common_Model extends CI_Model {

	function db_free_query_superadmin($query){
		$query = $this->db->query("$query");
		return $query;
	}

	function db_select($table){
		$query = $this->db->query("SELECT * 
			FROM $table");
		return $query->result();
	}

	function db_select_cond($table, $selector, $selector_value, $cond){
		$query = $this->db->query("SELECT *
			FROM $table
			WHERE $selector = '$selector_value'
			$cond ");
		return $query->result();
	}

	function db_select_num_rows($table){
		$query = $this->db->query("SELECT * 
			FROM $table");
		return $query->num_rows();
	}

	function db_select_array($table, $selector, $array, $order){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where_in($selector,$array);
		$this->db->order_by($order);
		$query = $this->db->get();
		return $query->result();
	}

	function db_insert($table,$data){
		$this->db->insert($table,$data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function db_insert_batch($table, $data){
		$query = $this->db->insert_batch($table,$data);
	}

	function db_update($table,$data,$column,$selector){
		$this->db->where($column, $selector);
		$this->db->update($table, $data);
		return $this->db->last_query();
	}

	function db_update_batch($table, $data, $param)
	{
		$this->db->update_batch($table, $data, $param);
		return $this->db->last_query();
	}

	function db_update_multiple_cond($table,$data,$array){
		$this->db->where($array);
		$this->db->update($table, $data);
	}

	function db_delete($table,$column,$selector){
		$this->db->where($column, $selector);
		$this->db->delete($table);
	}

	function db_delete_batch($table, $column, $array)
	{
		$this->db->where_in($column, $array);
		$this->db->delete($table);
		return $this->db->last_query();
	}

	function get_data_barang($barang_id){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_satuan, t3.nama as nama_packaging
			FROM (
				SELECT *
				FROM nd_barang
				WHERE id = $barang_id)t1
			LEFT JOIN nd_satuan t2
			ON t1.satuan_id = t2.id
			LEFT JOIN nd_satuan t3
			ON t1.packaging_id = t3.id
			");

		return $query->result();
	}

	function get_next_faktur($no_faktur, $tanggal){
		$query = $this->db->query("SELECT id, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur
			FROM nd_penjualan
			WHERE no_faktur > $no_faktur
			AND tanggal >= '$tanggal'
			AND status_aktif = 1
			AND no_faktur != ''
			ORDER BY no_faktur asc
			LIMIT 1

		");
		return $query->result();
	}

	function get_latest_so($tanggal, $barang_id, $warna_id, $gudang_id)
	{
		$query = $this->db->query("SELECT t1.*, t2.tanggal
			FROM (
				SELECT *
				FROM nd_stok_opname_detail
				WHERE barang_id = $barang_id
				AND warna_id = $warna_id
				AND gudang_id = $gudang_id
			) t1
			LEFT JOIN (
				SELECT *
				FROM nd_stok_opname
				WHERE tanggal <= '$tanggal'
				AND status_aktif = 1
			) t2
			ON t1.stok_opname_id = t2.id
			WHERE t2.id is not null
			ORDER BY tanggal DESC LIMIT 1


		");
		return $query->result();
	}

	function get_latest_so_eceran($tanggal, $barang_id, $warna_id, $gudang_id)
	{
		$query = $this->db->query("SELECT t1.*, t2.tanggal
			FROM (
				SELECT *
				FROM nd_stok_opname_eceran
				WHERE barang_id = $barang_id
				AND warna_id = $warna_id
				AND gudang_id = $gudang_id
			) t1
			LEFT JOIN (
				SELECT *
				FROM nd_stok_opname
				WHERE tanggal <= '$tanggal'
				AND status_aktif = 1
			) t2
			ON t1.stok_opname_id = t2.id
			WHERE t2.id is not null
			ORDER BY tanggal DESC LIMIT 1


		");
		return $query->result();
	}

	function get_latest_so_before($tanggal, $barang_id, $supplier_id, $warna_id, $gudang_id)
	{
		$query = $this->db->query("SELECT t1.*, t2.tanggal
			FROM (
				SELECT *
				FROM nd_stok_opname_detail
				WHERE barang_id = $barang_id
				AND warna_id = $warna_id
				AND supplier_id = $supplier_id
				AND gudang_id = $gudang_id
			) t1
			LEFT JOIN (
				SELECT *
				FROM nd_stok_opname
				WHERE tanggal < '$tanggal'
				AND status_aktif = 1
			) t2
			ON t1.stok_opname_id = t2.id
			WHERE t2.id is not null
			ORDER BY tanggal DESC LIMIT 1
		");
		return $query->result();
	}

	function get_latest_so_eceran_before($tanggal, $barang_id, $warna_id, $supplier_id,  $gudang_id)
	{
		$query = $this->db->query("SELECT t1.*, t2.tanggal
			FROM (
				SELECT *
				FROM nd_stok_opname_eceran
				WHERE barang_id = $barang_id
				AND warna_id = $warna_id
				AND gudang_id = $gudang_id
				AND supplier_id = $supplier_id
			) t1
			LEFT JOIN (
				SELECT *
				FROM nd_stok_opname
				WHERE tanggal < '$tanggal'
				AND status_aktif = 1
			) t2
			ON t1.stok_opname_id = t2.id
			WHERE t2.id is not null
			ORDER BY tanggal DESC LIMIT 1


		");
		return $query->result();
	}

//===================================================================

	function get_user_list(){
		$query = $this->db->query("SELECT nd_user.id, username, time_start, time_end, posisi_id, nd_posisi.name as posisi_name, nd_user.status_aktif
			FROM nd_user
			LEFT JOIN nd_posisi
			ON nd_user.posisi_id = nd_posisi.id
			");

		return $query->result();
	}

	function get_barang_list(){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_satuan
			FROM nd_barang as tbl_a
			LEFT JOIN nd_satuan as tbl_b
			ON tbl_a.satuan_id = tbl_b.id
			ORDER By tbl_a.nama
			");

		return $query->result();
	}

	function get_barang_list_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		// $this->db->_protect_identifiers = false;

		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, tbl_a.nama as nama, nama_jual, tbl_b.nama as nama_satuan, 
				tbl_c.nama as nama_packaging, harga_jual, harga_beli, 
				concat(if(pengali_harga_jual=1,tbl_b.nama, tbl_c.nama),'??',
				if(pengali_harga_beli=1,tbl_b.nama, tbl_c.nama)) as pengali_harga, 
				nd_toko.nama as nama_toko, 
				concat_ws('??',tbl_a.id, satuan_id, packaging_id, pengali_harga_jual, pengali_harga_beli, toko_id, ifnull(harga_ecer,0), subitem_status, eceran_mix_status ) as status_barang
				FROM nd_barang as tbl_a
				LEFT JOIN nd_satuan as tbl_b
				ON tbl_a.satuan_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_a.packaging_id = tbl_c.id
				LEFT JOIN nd_toko 
				ON tbl_a.toko_id = nd_toko.id
				) A
			$sWhere
            $sOrder
            $sLimit
			", false);
            // $sOrder

		return $query;
	}

	function get_barang_list_aktif(){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_satuan, tbl_c.nama as nama_packaging
				FROM (
					SELECT *
					FROM nd_barang
					where status_aktif = 1
					) as tbl_a
				LEFT JOIN nd_satuan as tbl_b
				ON tbl_a.satuan_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_a.packaging_id = tbl_c.id
				ORDER BY tbl_a.nama asc
			", false);

		return $query->result();
	}

	function get_customer_list_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT nama, alias, alamat, kota, telepon1, telepon2,npwp, status_aktif,tempo_kredit, 
				concat_ws('-?-',ifnull(kode_pos,''),ifnull(email,''),ifnull(npwp,''),status_aktif,id,ifnull(nik,'') ) as other_data
				FROM nd_customer
				) A
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

//=======================================customer profile======================================

	function get_customer_profile_pembelian_barang_terbanyak($customer_id, $year){
		$query = $this->db->query("SELECT concat_ws(' ',tbl_c.nama,tbl_d.warna_beli ) as barang, sum(qty) as qty 
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE YEAR(tanggal) = '$year'
				AND customer_id = $customer_id
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT qty, penjualan_id, barang_id, warna_id 
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
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

	function get_customer_profile_pembelanjaan_tahun($customer_id,$date_start, $date_end){
		$query = $this->db->query("SELECT MONTHNAME(tanggal) as tanggal, sum(amount)/1000 as amount
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				AND customer_id = $customer_id
				and tanggal >= '$date_start'
				AND tanggal <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(harga_jual*qty) as amount, penjualan_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				group by penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			group by MONTH(tanggal)
			");

		return $query->result();

	}

	function get_customer_profile_piutang($customer_id){
		$query = $this->db->query("SELECT tbl_a.status_aktif, ifnull(tbl_c.nama,'no name') as nama_customer, sum((ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) - ifnull(total_bayar,0) - ifnull(bayar_piutang,0) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
				FROM (
					SELECT *, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
					FROM nd_penjualan 
					WHERE status_aktif = 1
					AND customer_id = $customer_id
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
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
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_piutang_detail
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN (
					SELECT SUM(amount) as bayar_piutang, penjualan_id
					FROM nd_pembayaran_piutang_detail t1
					LEFT JOIN (
						SELECT * 
						FROM nd_penjualan
						WHERE status_aktif = 1
					) t2
					ON t1.pembayaran_piutang_id = t2.id
					WHERE t2.id is not null
					GROUP BY penjualan_id
				) as tbl_f
				ON tbl_a.id = tbl_f.penjualan_id
				WHERE ifnull(total_bayar,0) + ifnull(bayar_piutang,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim < 0
				group by customer_id
			", false);

		return $query->result();
	}

	function get_data_penjualan($customer_id){
		$query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, tbl_e.text as penjualan_type_id, ifnull(g_total,0) as g_total , ifnull(diskon,0) as diskon, ifnull(ongkos_kirim,0) as ongkos_kirim, if(penjualan_type_id = 3,if(nama_keterangan = '','no_name', nama_keterangan), tbl_c.nama) as nama_customer, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as keterangan, concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status,tbl_e.text as tipe_penjualan, tbl_a.id as penjualan_id
				FROM (
					SELECT *, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
					FROM nd_penjualan 
					WHERE customer_id = $customer_id
					ORDER BY tanggal desc
					LIMIT 0,30
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
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
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN nd_penjualan_type tbl_e
				ON tbl_a.penjualan_type_id = tbl_e.id
			", false);

		return $query->result();
	}

	function get_dp_by_customer($customer_id){
		$query = $this->db->query("SELECT tbl_a.id, nama, status_aktif , ifnull(dp_masuk,0) - ifnull(dp_keluar,0) as saldo
			FROM (
				SELECT *
				FROM nd_customer
				WHERE id = $customer_id
				) as tbl_a
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_masuk, customer_id
				FROM nd_dp_masuk
				group by customer_id
				) as tbl_b
			ON tbl_a.id = tbl_b.customer_id
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_keluar, customer_id
				FROM (
					SELECT amount, penjualan_id 
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id = 1
					) as nd_pembayaran_penjualan
				LEFT JOIN nd_penjualan
				ON nd_pembayaran_penjualan.penjualan_id = nd_penjualan.id
				group by customer_id
				) as tbl_c
			ON tbl_c.customer_id = tbl_a.id
		");
		return $query->result();
	}

	function get_penjualan_report($cond, $customer_id, $limit){
		$query = $this->db->query("SELECT tbl_a.id, no_faktur as nf, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, harga_jual, total, diskon, ongkos_kirim, if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, (ifnull(total_bayar,0) + ifnull(bayar_piutang,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, tbl_a.id as data , jatuh_tempo, pembayaran_type_id, data_bayar
				FROM (
					SELECT *, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
					FROM nd_penjualan 
					WHERE status_aktif = 1
					AND customer_id = $customer_id
					ORDER BY tanggal desc
					$limit
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
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN (
					SELECT SUM(amount) as bayar_piutang, penjualan_id
					FROM nd_pembayaran_piutang_detail t1
					LEFT JOIN (
						SELECT * 
						FROM nd_penjualan
						WHERE status_aktif = 1
					) t2
					ON t1.pembayaran_piutang_id = t2.id
					WHERE t2.id is not null
					GROUP BY penjualan_id
				) as tbl_f
				ON tbl_a.id = tbl_f.penjualan_id
				$cond
				ORDER BY nf asc

			", false);

		return $query->result();
	}


//=======================================cek harga barang===========================================

	function cek_harga_penjualan_barang($barang_id, $cond, $limit){
		$query = $this->db->query("SELECT t1.*, DATE_FORMAT(tanggal,'%d-%b-%Y') as tanggal
			FROM (
				SELECT harga_jual, barang_id, penjualan_id
				FROM nd_penjualan_detail
				WHERE barang_id = $barang_id
				)t1
			LEFT JOIN (
				SELECT *
				FROM nd_penjualan
				$cond
				) t2
			ON t1.penjualan_id = t2.id
			WHERE t2.id is not null
			GROUP BY penjualan_id
			ORDER BY tanggal desc
			LIMIT $limit
		");
		return $query->result();
	}

//=======================================note_order===========================================

	function get_note_order(){
		$query = $this->db->query("SELECT a.id, tipe_customer, customer_id, group_concat(matched SEPARATOR '??') as matched, group_concat(done_by) as done_by, group_concat(a.status) as status, if(a.tipe_customer = 1, d.nama, nama_customer) as nama_customer, contact_info ,done_time, group_concat(tipe_barang) as tipe_barang, group_concat(barang_id) as barang_id, group_concat(warna_id) as warna_id, group_concat(if(tipe_barang =1 ,b.nama_jual,a.nama_barang) SEPARATOR '??') as nama_barang, group_concat(if(warna_id = -1,nama_warna, warna_jual)) as nama_warna, group_concat(qty) as qty, group_concat(roll) as roll, group_concat(harga) as harga, tanggal_note_order, tanggal_target , group_concat(note_order_detail_id) as note_order_detail_id
			FROM (
				SELECT t1.*, if(t2.barang_id is not null, 1, 0) matched
				FROM (
					SELECT a.*, barang_id, warna_id, tipe_barang, qty, roll, harga, nama_barang, nama_warna, b.id as note_order_detail_id, done_by, status, done_time
					FROM (
						SELECT *
						FROM nd_note_order_detail
						WHERE status = 0
						) b
					LEFT JOIN (
						SELECT * 
						FROM nd_note_order
					)a 
					ON b.note_order_id = a.id
				) t1
				LEFT JOIN (
					SELECT barang_id, warna_id, MAX(tanggal) as tanggal, t1.id
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					GROUP BY barang_id, warna_id, tanggal
					ORDER BY t2.tanggal desc
				) t2
				ON t1.barang_id = t2.barang_id
				AND t1.warna_id = t2.warna_id
				AND t1.tanggal_note_order <= t2.tanggal
			) a
			LEFT JOIN nd_barang b
			ON a.barang_id = b.id
			LEFT JOIN nd_warna c
			ON a.warna_id = c.id
			LEFT JOIN nd_customer d
			ON a.customer_id = d.id
			GROUP BY a.id

		");
		return $query;
	}

	function get_note_order_reminder(){
		$reminder = date('Y-m-d H:i');
		$user_id = is_user_id();
		$query = $this->db->query("SELECT z.reminder, z.id as reminder_id, a.id,tipe_customer, customer_id, matched, a.done_by,a.status, if(a.tipe_customer = 1, d.nama, nama_customer) as nama_customer, contact_info ,done_time, tipe_barang, barang_id, warna_id, if(tipe_barang =1 ,b.nama_jual,a.nama_barang) as nama_barang, if(warna_id = -1,nama_warna, warna_jual) as nama_warna, qty, harga, tanggal_note_order, tanggal_target , roll
			FROM (
				SELECT *
				FROM nd_reminder
				WHERE reminder <= '$reminder'
				AND status_on = 1
				AND user_id = $user_id
				) z
			LEFT JOIN (
				SELECT t1.*, if(t2.barang_id is not null, 1, 0) matched
				FROM (
					SELECT a.*, barang_id, warna_id, tipe_barang, qty, roll, harga, nama_barang, nama_warna, done_by, done_time, status
					FROM nd_note_order a
					LEFT JOIN (
						SELECT *
						FROM nd_note_order_detail
						WHERE done_time >= DATE_SUB(NOW(), INTERVAL 1 DAY)
						OR status = 0
						) b
					ON b.note_order_id = a.id
					) t1
				LEFT JOIN (
					SELECT barang_id, warna_id, tanggal
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
				) t2
				ON t1.barang_id = t2.barang_id
				AND t1.warna_id = t2.warna_id
				AND t1.tanggal_note_order <= t2.tanggal
				group by t1.id
			) a
			ON z.note_order_id = a.id
			LEFT JOIN nd_barang b
			ON a.barang_id = b.id
			LEFT JOIN nd_warna c
			ON a.warna_id = c.id
			LEFT JOIN nd_customer d
			ON a.customer_id = d.id

		");
		return $query;
		// return $this->db->last_query();
	}

	function get_note_order_target(){
		$query = $this->db->query("SELECT a.id,tipe_customer, customer_id, done_by, status, if(a.tipe_customer = 1, d.nama, nama_customer) as nama_customer ,done_time, tipe_barang, barang_id, warna_id, nama_barang, nama_warna, qty, harga, tanggal_note_order, tanggal_target 
			FROM (
				SELECT group_concat(tipe_barang) as tipe_barang, group_concat(barang_id) as barang_id, group_concat(warna_id) as warna_id ,group_concat(if(tipe_barang =1 ,t2.nama_jual,t1.nama_barang)) as nama_barang ,group_concat(warna_jual) as nama_warna, group_concat(qty) as qty , group_concat(harga) as harga, note_order_id, group_concat(done_by) as done_by, group_concat(done_time) as done_time, group_concat(status) as status
				FROM (
					SELECT *
					FROM nd_note_order_detail
					WHERE status != -1
					AND status != 1
					) t1
				LEFT JOIN nd_barang t2
				ON t1.barang_id = t2.id
				LEFT JOIN nd_warna t3
				ON t1.warna_id = t3.id
				GROUP BY note_order_id
			) b
			LEFT JOIN (
				SELECT *
				FROM nd_note_order
				WHERE tanggal_target is not null 
			) a 
			ON a.id = b.note_order_id
			LEFT JOIN nd_customer d
			ON a.customer_id = d.id
			ORDER BY tanggal_target asc
			LIMIT 10

		");
		return $query;
	}

	function get_note_order_pending(){
		$query = $this->db->query("SELECT a.id,tipe_customer, customer_id,done_by,status, if(a.tipe_customer = 1, d.nama, nama_customer) as nama_customer, done_time, tipe_barang, barang_id, warna_id, nama_barang, nama_warna, qty, harga, tanggal_note_order, tanggal_target 
			FROM (
				SELECT group_concat(tipe_barang) as tipe_barang, group_concat(barang_id) as barang_id, group_concat(warna_id) as warna_id ,group_concat(if(tipe_barang =1 ,t2.nama_jual,t1.nama_barang)) as nama_barang ,group_concat(warna_jual) as nama_warna, group_concat(qty) as qty , group_concat(harga) as harga, note_order_id, group_concat(done_by) as done_by, group_concat(done_time) as done_time, group_concat(status) as status
				FROM (
					SELECT *
					FROM nd_note_order_detail
					WHERE status = 0
					) t1
				LEFT JOIN nd_barang t2
				ON t1.barang_id = t2.id
				LEFT JOIN nd_warna t3
				ON t1.warna_id = t3.id
				GROUP BY note_order_id
			) b
			LEFT JOIN (
				SELECT *
				FROM nd_note_order
			) a 
			ON a.id = b.note_order_id
			LEFT JOIN nd_customer d
			ON a.customer_id = d.id
		");
		return $query;
	}

//=======================================================================================

	function get_notifikasi_akunting(){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_customer
			FROM nd_notifikasi_akunting t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id
			WHERE read_by is null
		");

		return $query;
		
	}

	function get_piutang_warn(){
		$today = date('Y-m-d');
		$query = $this->db->query("SELECT customer_id, t2.nama as nama_customer, t3.nama as nama_toko, sum(sisa_piutang) as sisa_piutang, MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id, sum(counter_invoice) as counter_invoice
			FROM (
				(
					SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, sum(1) as counter_invoice
					FROM (
						SELECT sum(if(pengali_harga = 1,qty, jumlah_roll) * t1.harga_jual) as g_total, penjualan_id 
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
					LEFT JOIN (
						SELECT *, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur_lengkap, if(jatuh_tempo = tanggal, DATE_ADD(jatuh_tempo, INTERVAL 60 DAY), jatuh_tempo ) as new_jatuh_tempo
						FROM nd_penjualan 
						WHERE status_aktif = 1
						AND penjualan_type_id != 3
						AND no_faktur != ''
						ORDER BY tanggal desc
						)as tbl_a
					ON tbl_b.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT penjualan_id, sum(amount) as total_bayar
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_detail
							WHERE data_status = 1
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							) b
						ON a.pembayaran_piutang_id = b.id
						WHERE b.id is not null
						GROUP BY penjualan_id
						) as tbl_d
					ON tbl_d.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT sum(amount) as amount_bayar, penjualan_id
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id != 5
						GROUP BY penjualan_id
					) tbl_g
					ON tbl_a.id = tbl_g.penjualan_id
					WHERE ifnull(total_bayar,0) + ifnull(amount_bayar,0) - ifnull(g_total,0) - ifnull(diskon,0) - ifnull(ongkos_kirim,0)  < 0
					AND new_jatuh_tempo <= '$today'
					AND tbl_a.id is not null
					group by customer_id, toko_id

				)UNION(
					SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, 1 as toko_id, sum(1) as counter_invoice
					FROM nd_piutang_awal a
					LEFT JOIN (
						SELECT penjualan_id, sum(amount) as total_bayar
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_detail
							WHERE data_status = 2
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							) b
						ON a.pembayaran_piutang_id = b.id
						WHERE b.id is not null
						GROUP BY penjualan_id
						) b
					ON b.penjualan_id = a.id
					GROUP BY customer_id, toko_id
				)
			) t1
			LEFT JOIN nd_customer as t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_toko t3
			ON t1.toko_id = t3.id
			WHERE sisa_piutang > 0
			GROUP BY customer_id
			ORDER BY t2.nama asc", false);


		return $query;
	}

	function get_hutang_warn(){
		$today = date('Y-m-d');
		$query = $this->db->query("SELECT supplier_id, t2.nama as nama_supplier, t3.nama as nama_toko, sum(sisa_hutang) as sisa_hutang, MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id, sum(counter_invoice) as counter_invoice
			FROM (
				(
					SELECT tbl_a.status_aktif,sum(if(ifnull(g_total,0) - ifnull(diskon,0)- ifnull(total_bayar,0) - ifnull(amount_bayar,0) < 0,0,ifnull(g_total,0) - ifnull(diskon,0)- ifnull(total_bayar,0) - ifnull(amount_bayar,0)) ) as sisa_hutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, supplier_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, sum(1) as counter_invoice
					FROM (
						SELECT sum(if(pengali_harga_beli = 1,t2.qty, t2.jumlah_roll) * t1.harga_beli) as g_total, pembelian_id
						FROM nd_pembelian_detail t1
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
							FROM nd_pembelian_qty_detail
							group by pembelian_detail_id
							) t2
						ON t2.pembelian_detail_id = t1.id
						LEFT JOIN nd_barang t3
						ON t1.barang_id = t3.id
						GROUP BY pembelian_id
						) as tbl_b
					LEFT JOIN (
						SELECT *, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur_lengkap, if(jatuh_tempo = tanggal, DATE_ADD(jatuh_tempo, INTERVAL 60 DAY), jatuh_tempo ) as new_jatuh_tempo
						FROM nd_pembelian 
						WHERE status_aktif = 1
						ORDER BY tanggal desc
						)as tbl_a
					ON tbl_b.pembelian_id = tbl_a.id
					LEFT JOIN (
						SELECT pembelian_id, sum(amount) as total_bayar
						FROM (
							SELECT *
							FROM nd_pembayaran_hutang_detail
							WHERE data_status = 1
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_hutang
							WHERE status_aktif = 1
							) b
						ON a.pembayaran_hutang_id = b.id
						WHERE b.id is not null
						GROUP BY pembelian_id
						) as tbl_d
					ON tbl_d.pembelian_id = tbl_a.id
					LEFT JOIN (
						SELECT sum(amount) as amount_bayar, pembelian_id
						FROM nd_pembayaran_pembelian
						WHERE pembayaran_type_id != 5
						GROUP BY pembelian_id
					) tbl_g
					ON tbl_a.id = tbl_g.pembelian_id
					WHERE ifnull(total_bayar,0) + ifnull(amount_bayar,0) - ifnull(g_total,0) - ifnull(diskon,0)  < 0
					AND new_jatuh_tempo <= '$today'
					AND tbl_a.id is not null
					group by supplier_id, toko_id

				)UNION(
					SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_hutang, concat_ws('??',id,no_faktur) as data, 1, supplier_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, 1 as toko_id, sum(1) as counter_invoice
					FROM nd_hutang_awal a
					LEFT JOIN (
						SELECT pembelian_id, sum(amount) as total_bayar
						FROM (
							SELECT *
							FROM nd_pembayaran_hutang_detail
							WHERE data_status = 2
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_hutang
							WHERE status_aktif = 1
							) b
						ON a.pembayaran_hutang_id = b.id
						WHERE b.id is not null
						GROUP BY pembelian_id
						) b
					ON b.pembelian_id = a.id
					GROUP BY supplier_id, toko_id
				)
			) t1
			LEFT JOIN nd_supplier as t2
			ON t1.supplier_id = t2.id
			LEFT JOIN nd_toko t3
			ON t1.toko_id = t3.id
			WHERE sisa_hutang > 0
			GROUP BY supplier_id
			ORDER BY t2.nama asc", false);


		return $query;
	}

//=======================================================================================

	function get_barang_eceran_mix_list(){
		$query = $this->db->query("SELECT *
			FROM nd_barang
			WHERE eceran_mix_status = 1
		");

		return $query->result();
		
	}

}
