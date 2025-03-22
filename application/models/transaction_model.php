<?php

class Transaction_Model extends CI_Model {

	// public function __construct() {
    //     parent::__construct();
    //     $this->load->database('mysqli');
    // }


//===============================po pembelian===========================================
	function get_po_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, tbl_b.nama as toko, po_number, DATE_FORMAT(tanggal,'%d/%m/%Y') as tanggal, qty as jumlah, jumlah_roll, nama_barang,  harga as harga, tbl_f.nama as supplier, concat_ws('??',tbl_a.id, toko_id, supplier_id) as status_data, '' as keterangan
				FROM nd_po_pembelian as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nd_barang.nama,warna_beli) SEPARATOR '??') as nama_barang,  group_concat(t1.harga SEPARATOR '??') as harga, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, po_pembelian_id 
					FROM (
						SELECT (a.qty) as qty, a.jumlah_roll, po_pembelian_id, a.barang_id, b.warna_id, ifnull(b.harga, a.harga) as harga
						FROM nd_po_pembelian_detail a
						LEFT JOIN nd_po_pembelian_warna b
						ON b.po_pembelian_detail_id = a.id
						) t1
					LEFT JOIN nd_barang
					ON t1.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON t1.warna_id = nd_warna.id
					LEFT JOIN nd_satuan
					ON nd_barang.satuan_id = nd_satuan.id
					GROUP BY po_pembelian_id
				) as tbl_c
				ON tbl_c.po_pembelian_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_f
				ON tbl_f.id = tbl_a.supplier_id
				) A
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	function get_data_po_pembelian($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_supplier, tbl_d.nama as nama_toko, tbl_b.telepon as telepon_supplier, concat(DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(po_number,4,'0')) as po_number
			FROM (
				SELECT *
				FROM nd_po_pembelian
				Where id = $id
			) as tbl_a
			LEFT JOIN nd_supplier as tbl_b
			ON tbl_a.supplier_id = tbl_b.id
			LEFT JOIN nd_toko as tbl_d
			ON tbl_a.toko_id = tbl_d.id
		");
		return $query->result();
	}

	function get_data_po_pembelian_detail($po_pembelian_id){
		$query = $this->db->query("SELECT t1.*, t2.*
			FROM (
				SELECT tbl_a.*, tbl_b.nama as nama_barang, tbl_c.nama as nama_satuan
				FROM (
					SELECT *
					FROM nd_po_pembelian_detail
					WHere po_pembelian_id = $po_pembelian_id
				) as tbl_a
				LEFT JOIN nd_barang as tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_b.satuan_id = tbl_c.id
			) t1
			LEFT JOIN  (
				SELECT group_concat(c.warna_beli SEPARATOR '??') as nama_warna, po_pembelian_detail_id, group_concat(warna_id) as warna_id, sum(qty) as qty_warna_total, group_concat(qty) as qty_warna
				FROM nd_po_pembelian_warna a
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				GROUP BY po_pembelian_detail_id
			) t2
			ON t2.po_pembelian_detail_id = t1.id
		");
		return $query->result();
	}

	function get_po_pembelian_by_supplier($supplier_id){
		$query = $this->db->query("SELECT a.id, concat(DATE_FORMAT(b.tanggal,'%d%m%y'),'-',LPAD(po_number,4,'0'),'/',a.batch) as po_number
			FROM nd_po_pembelian_batch a
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian
				WHERE supplier_id = $supplier_id
				) b
			ON a.po_pembelian_id = b.id
			where b.id is not null");
		return $query->result();
	}
	
	function get_data_po_pembelian_detail_info($po_pembelian_detail_id){
		
		$query = $this->db->query("SELECT t1.*, t2.*
			FROM (
				SELECT tbl_a.*, tbl_b.nama as nama_barang, tbl_c.nama as nama_satuan
				FROM (
					SELECT *
					FROM nd_po_pembelian_detail
					WHere id = $po_pembelian_detail_id
				) as tbl_a
				LEFT JOIN nd_barang as tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_b.satuan_id = tbl_c.id
			) t1
			LEFT JOIN  (
				SELECT group_concat(c.warna_beli SEPARATOR '??') as nama_warna, po_pembelian_detail_id, group_concat(warna_id) as warna_id, sum(qty) as qty_warna_total, group_concat(qty) as qty_warna
				FROM nd_po_pembelian_warna a
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				GROUP BY po_pembelian_detail_id
			) t2
			ON t2.po_pembelian_detail_id = t1.id
		");
		return $query->result();
	}

	function get_data_po_pembelian_detail_warna($po_pembelian_detail_id){
		$query = $this->db->query("SELECT b.nama as nama_barang_revisi , c.warna_beli as nama_warna, po_pembelian_detail_id, d.barang_id as barang_id_revisi, a.warna_id, a.qty as qty_warna, OCKH, a.id, qty_datang, f.tanggal, batch
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_detail_id = $po_pembelian_detail_id
				) a
			LEFT JOIN nd_po_pembelian_detail d
			ON a.po_pembelian_detail_id = d.id
			LEFT JOIN nd_po_pembelian_batch f
			ON a.po_pembelian_batch_id = f.id
			LEFT JOIN (
				SELECT sum(qty) as qty_datang, po_pembelian_batch_id, barang_id, warna_id
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				GROUP BY po_pembelian_batch_id, warna_id
				) e
			ON a.po_pembelian_batch_id = e.po_pembelian_batch_id
			AND d.barang_id = e.barang_id
			AND e.warna_id = a.warna_id
			LEFT JOIN nd_barang b
			ON d.barang_id = b.id
			LEFT JOIN nd_warna c
			ON a.warna_id = c.id
		");
		return $query->result();
	}

	function get_data_po_pembelian_warna($po_pembelian_detail_id){
		$query = $this->db->query("SELECT a.*, c.warna_beli as nama_warna, batch
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_detail_id = $po_pembelian_detail_id
				) a
			LEFT JOIN nd_po_pembelian_batch b
			ON a.po_pembelian_batch_id = b.id
			LEFT JOIN nd_warna c
			ON a.warna_id = c.id

			");

		return $query->result();
	}

	function get_data_po_pembelian_detail_batch($po_pembelian_batch_id, $po_pembelian_detail_id){
		$query = $this->db->query("SELECT c.nama as nama_barang , d.warna_beli as nama_warna, po_pembelian_detail_id, b.barang_id, a.warna_id, a.qty, OCKH, a.id, e.nama as nama_satuan
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_batch_id = $po_pembelian_batch_id
				AND po_pembelian_detail_id = $po_pembelian_detail_id
				) a
			LEFT JOIN nd_po_pembelian_detail b
			ON a.po_pembelian_detail_id = b.id
			LEFT JOIN nd_barang c
			ON b.barang_id = c.id
			LEFT JOIN nd_warna d
			ON a.warna_id = d.id
			LEFT JOIN nd_satuan e
			ON c.satuan_id = e.id
		");
		return $query->result();
	}

	function get_data_barang_po($po_pembelian_id){
		
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_barang, tbl_c.nama as nama_satuan, qty - ifnull(qty_order,0) as sisa_kuota
				FROM (
					SELECT *
					FROM nd_po_pembelian_detail
					Where po_pembelian_id = $po_pembelian_id
				) as tbl_a
				LEFT JOIN nd_barang as tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_b.satuan_id = tbl_c.id
				LEFT JOIN (
					SELECT sum(qty) as qty_order, po_pembelian_detail_id
					FROM nd_po_pembelian_warna
					GROUP BY po_pembelian_detail_id
					) tbl_d
				ON tbl_d.po_pembelian_detail_id = tbl_a.id
		");
		return $query->result();
	}

//===============================pembelian===========================================
	function get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT if(tbl_a.status_aktif=0,-1,tbl_a.status_aktif) as status_aktif, tbl_b.nama as toko, no_faktur,no_surat_jalan, tanggal, qty as jumlah, jumlah_roll, nama_barang, tbl_c.harga_beli, tbl_e.nama as gudang, 0 as harga, tbl_f.nama as supplier, concat_ws('??',tbl_a.id, toko_id, gudang_id, supplier_id) as status_data, ROUND(g_total,0) - diskon as total, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0))) as keterangan
				FROM nd_pembelian as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nd_barang.nama,warna_beli) SEPARATOR '??') as nama_barang,  group_concat(nd_pembelian_detail.harga_beli SEPARATOR '??') as harga_beli, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((if(pengali_type = 1,qty,jumlah_roll) *nd_pembelian_detail.harga_beli) SEPARATOR '??') as total, sum(if(pengali_type = 1,qty,jumlah_roll) *nd_pembelian_detail.harga_beli) as g_total, pembelian_id 
					FROM (
						SELECT pembelian_id, barang_id, warna_id, harga_beli, id, pengali_type
						FROM nd_pembelian_detail
						) as nd_pembelian_detail
					LEFT JOIN (
						SELECT pembelian_detail_id, sum(qty * if(jumlah_roll != 0, jumlah_roll, 1)) as qty, sum(jumlah_roll) as jumlah_roll
						FROM nd_pembelian_qty_detail
						GROUP BY pembelian_detail_id
						) t2
					ON t2.pembelian_detail_id = nd_pembelian_detail.id
					LEFT JOIN nd_barang
					ON nd_pembelian_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_pembelian_detail.warna_id = nd_warna.id
					LEFT JOIN nd_satuan
					ON nd_barang.satuan_id = nd_satuan.id
					GROUP BY pembelian_id
				) as tbl_c
				ON tbl_c.pembelian_id = tbl_a.id
				LEFT JOIN nd_gudang as tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				LEFT JOIN nd_supplier as tbl_f
				ON tbl_f.id = tbl_a.supplier_id
				LEFT JOIN (
					SELECT pembelian_id, sum(amount) as total_bayar
					FROM nd_pembayaran_hutang_detail
					GROUP BY pembelian_id
					) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				) A
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	function get_pembelian_list_ajax_slim($aColumns, $sWhere, $sOrder, $sLimit){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, tbl_b.nama as toko, no_faktur, tanggal, tbl_e.nama as gudang , tbl_f.nama as supplier, concat_ws('??',tbl_a.id, toko_id, gudang_id, supplier_id) as status_data, total
				FROM nd_pembelian as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*cast(harga_beli as decimal(15,2))) as total, harga_beli, pembelian_id, barang_id, satuan_id
					FROM nd_pembelian_detail
					group by pembelian_id
					) as tbl_c
				ON tbl_c.pembelian_id = tbl_a.id
				LEFT JOIN nd_barang as tbl_d
				ON tbl_c.barang_id = tbl_d.id
				LEFT JOIN nd_gudang as tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				LEFT JOIN nd_supplier as tbl_f
				ON tbl_f.id = tbl_a.supplier_id
				LEFT JOIN nd_satuan as tbl_g
				ON tbl_c.satuan_id = tbl_g.id

				) A
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	function data_pembelian_list($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_barang, warna_beli, tbl_d.nama as nama_satuan, tbl_e.nama as nama_gudang 
			FROM (
				SELECT *
				FROM nd_pembelian_barang_list
				WHere pembelian_id = $id
			) as tbl_a
			LEFT JOIN nd_barang as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna as tbl_c
			ON tbl_a.warna_id = tbl_c.id
			LEFT JOIN nd_satuan as tbl_d
			ON tbl_a.satuan_id = tbl_d.id
			LEFT JOIN nd_gudang as tbl_e
			ON tbl_a.gudang_id = tbl_e.id
		");
		return $query->result();
	}

	function get_data_pembelian($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_supplier, tbl_c.nama as nama_gudang, tbl_d.nama as nama_toko, tbl_b.telepon as telepon_supplier, LPAD(no_nota,4,'0') as no_nota_p, po_number
			FROM (
				SELECT *
				FROM nd_pembelian
				WHere id = $id
			) as tbl_a
			LEFT JOIN nd_supplier as tbl_b
			ON tbl_a.supplier_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_toko as tbl_d
			ON tbl_a.toko_id = tbl_d.id
			LEFT JOIN (
				SELECT t1.id, concat(DATE_FORMAT(t2.tanggal,'%d%m%y'),'-',LPAD(po_number,4,'0')) as po_number
				FROM nd_po_pembelian_batch t1
				LEFT JOIN nd_po_pembelian t2
				ON t1.po_pembelian_id = t2.id
				)tbl_e
			ON tbl_a.po_pembelian_batch_id = tbl_e.id
		");
		return $query->result();
	}

	function get_pembelian_barang_by_po($po_pembelian_batch_id, $OCKH){
		$cond = '';
		if ($OCKH != '') {
			$cond = "AND OCKH = ".$OCKH;
		}
		$query = $this->db->query("SELECT a.id, barang_id, warna_id, c.nama, d.warna_beli as nama_warna, harga_beli 
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_batch_id = $po_pembelian_batch_id
				$cond 
				) a
			LEFT JOIN nd_po_pembelian_detail b
			ON a.po_pembelian_detail_id = b.id
			LEFT JOIN nd_barang c
			ON b.barang_id = c.id
			LEFT JOIN nd_warna d
			ON a.warna_id = d.id
		");
		return $query->result();
	}

//=====================================pembelian detail=====================================================

	function get_data_pembelian_detail($pembelian_id){
		$query = $this->db->query("SELECT tbl_a.*,t2.*, tbl_b.nama as nama_barang, tbl_c.nama as nama_satuan, 
		tbl_c2.nama as nama_packaging, tbl_d.warna_beli as nama_warna, pengali_harga_beli, 
		tbl_b.toko_id as toko_id, color_code
			FROM (
				SELECT id, barang_id, warna_id, harga_beli, pengali_type
				FROM nd_pembelian_detail
				WHere pembelian_id = $pembelian_id
			) as tbl_a
			LEFT JOIN (
				SELECT sum(qty*if(jumlah_roll !=0, jumlah_roll,1)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat(qty,'??',jumlah_roll,'??',id) SEPARATOR '--') as data_qty, pembelian_detail_id
				FROM nd_pembelian_qty_detail
				GROUP BY pembelian_detail_id
				) t2
			ON tbl_a.id = t2.pembelian_detail_id
			LEFT JOIN nd_barang as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_satuan as tbl_c
			ON tbl_b.satuan_id = tbl_c.id
			LEFT JOIN nd_satuan as tbl_c2
			ON tbl_b.packaging_id = tbl_c2.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN nd_toko 
			ON tbl_b.toko_id = nd_toko.id
		");
		return $query->result();
	}

//==========================================penjualan================================

	function get_penjualan_list_ajax($aColumns, $sWhere, $sOrder, $sLimit, $tanggal_start, $tanggal_end){
		$cond = '';
		// if (is_posisi_id() > 3) {
		// 	$cond = " AND tanggal = '".date('Y-m-d')."' AND penjualan_type_id != 0";  
		// }else{
		// 	$cond = " AND penjualan_type_id != 0";  
		// }
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, concat(YEAR(tanggal), MONTH(tanggal),LPAD(no_faktur,5,'0')) as nf, no_faktur_lengkap as no_faktur, tanggal, 
				tbl_e.text as penjualan_type_id, ROUND(ifnull(g_total,0) - ifnull(diskon,0),2) as g_total , ifnull(diskon,0) as diskon, 
				ifnull(ongkos_kirim,0) as ongkos_kirim, if(penjualan_type_id = 3,if(nama_keterangan = '','no_name', nama_keterangan), tbl_c.nama) as nama_customer, 
				(ifnull(total_bayar,0) + ifnull(bayar_piutang,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as keterangan, 
				concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					)as tbl_a
				LEFT JOIN (
					SELECT sum(if(pengali_harga=1,qty, jumlah_roll) * t1.harga_jual) as g_total, penjualan_id 
					FROM nd_penjualan_detail t1
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) t2
					ON t2.penjualan_detail_id = t1.id
					LEFT JOIN nd_barang 
					ON t1.barang_id = nd_barang.id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					AND pembayaran_type_id != 6
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN nd_penjualan_type tbl_e
				ON tbl_a.penjualan_type_id = tbl_e.id
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

				) A			
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	function cek_harga_jual($barang_id,$cond){
		$query = $this->db->query("SELECT tanggal, harga_jual
			FROM nd_penjualan_detail 
			LEFT JOIN nd_penjualan
			ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			where barang_id = $barang_id
			$cond
			GROUP BY tanggal, harga_jual
			limit 10
			", false);

		return $query->result();
	}

	function get_data_penjualan($id){
		$query = $this->db->query("SELECT tbl_a.id, penjualan_type_id, revisi, tanggal, customer_id,gudang_id, 
		no_faktur_lengkap as no_faktur, no_surat_jalan, jatuh_tempo, diskon, tbl_a.status_aktif, ongkos_kirim, 
		tbl_a.keterangan, status , po_number, fp_status, 
		if(penjualan_type_id = 3,if(nama_keterangan = '','no_name', nama_keterangan), tbl_b.nama) as nama_keterangan, 
		if(penjualan_type_id = 3,'',if(kota = '','-',kota)) as kota , tbl_c.nama as nama_gudang, tbl_d.text as tipe_penjualan, 
		no_faktur_lengkap, ifnull(tbl_e.amount,0) as bayar_dp, no_surat_jalan,if(penjualan_type_id = 3,ifnull(alamat_keterangan,'-') , 
		if (alamat = '','-',alamat)) as alamat_keterangan, ifnull(tbl_b.npwp, '00.000.000.0-000.000') as npwp_customer, 
		no_faktur as no_faktur_raw, toko_id_legacy as toko_id
			FROM (
				SELECT *, concat('SJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_surat_jalan
				FROM nd_penjualan
				WHERE id = $id
				) as tbl_a
			LEFT JOIN nd_customer as tbl_b
			ON tbl_a.customer_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_penjualan_type as tbl_d
			ON tbl_a.penjualan_type_id = tbl_d.id
			LEFT JOIN (
				SELECT *
				FROM nd_pembayaran_penjualan
				WHERE pembayaran_type_id = 1
				) tbl_e
			ON tbl_a.id = tbl_e.penjualan_id
			LEFT JOIN (
				SELECT group_concat(DISTINCT toko_id) as toko_id, penjualan_id
				FROM nd_penjualan_detail
				GROUP BY penjualan_id
				) tbl_f
			ON tbl_a.id = tbl_f.penjualan_id
			", false);

		return $query->result();
	}

	function get_data_pembayaran($penjualan_id){
		$query = $this->db->query("SELECT a.*, b.nama as nama_bayar
			FROM (
				SELECT *
				FROM nd_pembayaran_penjualan
				WHERE penjualan_id = $penjualan_id
				) a
			LEFT JOIN nd_pembayaran_type b
			ON a.pembayaran_type_id = b.id
			", false);

		return $query->result();
	}

	function get_data_penjualan_detail($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT tbl_a.*, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, 
		tbl_d.warna_jual as nama_warna, tbl_e.qty as qty, tbl_e.jumlah_roll as jumlah_roll,  nama_supplier as data_supplier,
		if(is_eceran = 1,data_qty_eceran,data_qty) as data_qty, pengali_harga, nama_packaging, color_code
				FROM (
					SELECT *
					FROM nd_penjualan_detail
					WHERE penjualan_id = $id
					) as tbl_a
				LEFT JOIN (
					SELECT t0.id, t0.nama_jual as nama_barang, t1.nama as nama_satuan, pengali_harga_jual, t2.nama as nama_packaging
					FROM nd_barang t0
					LEFT JOIN nd_satuan t1
					ON t0.satuan_id = t1.id
					LEFT JOIN nd_satuan t2
					ON t0.packaging_id = t2.id
					) as tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_gudang as tbl_c
				ON tbl_a.gudang_id = tbl_c.id
				LEFT JOIN nd_warna as tbl_d
				ON tbl_a.warna_id = tbl_d.id
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, 
					sum(jumlah_roll) as jumlah_roll, penjualan_detail_id, 
					group_concat(concat_ws('??',qty,jumlah_roll,t1.id, supplier_id) SEPARATOR '=?=') as data_qty, 
					group_concat(t2.nama SEPARATOR '=?=') as nama_supplier, 
					group_concat(concat_ws('??',qty,ifnull(stok_eceran_qty_id,0),'0', ifnull(eceran_source,0), t1.id ) SEPARATOR '=?=') as data_qty_eceran
					FROM nd_penjualan_qty_detail t1
					LEFT JOIN nd_supplier t2
					ON t1.supplier_id = t2.id
					group by penjualan_detail_id
				) as tbl_e
				ON tbl_e.penjualan_detail_id = tbl_a.id
				LEFT JOIN nd_toko
				ON tbl_a.toko_id = nd_toko.id
			", false);

		return $query->result();
	}

	function get_data_penjualan_detail_group($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		
		$query = $this->db->query("SELECT tbl_a.*, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, 
		group_concat(tbl_d.warna_jual SEPARATOR '--') as nama_warna, 
		sum(tbl_e.qty) as qty, sum(tbl_e.jumlah_roll) as jumlah_roll, group_concat(data_qty SEPARATOR '--') as data_qty
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE penjualan_id = $id
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
				SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_qty
				FROM nd_penjualan_qty_detail
				group by penjualan_detail_id
				) as tbl_e
			ON tbl_e.penjualan_detail_id = tbl_a.id
			GROUP BY barang_id, harga_jual
			", false);

		return $query->result();
	}

	function get_data_penjualan_detail_by_barang($id){
		$query = $this->db->query("SELECT nama_barang, nama_satuan, group_concat(tbl_d.warna_jual SEPARATOR '??') as nama_warna, group_concat(tbl_e.qty SEPARATOR '??') as qty, group_concat(tbl_e.jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat(data_qty SEPARATOR '??') as data_qty, group_concat(roll_qty SEPARATOR '??') as roll_qty, group_concat(data_all SEPARATOR '=??=') as data_all
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE penjualan_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(jumlah_roll) as roll_qty, penjualan_detail_id,  group_concat(qty SEPARATOR ' ') as data_qty, group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_all
				FROM nd_penjualan_qty_detail
				group by penjualan_detail_id
				) as tbl_e
			ON tbl_e.penjualan_detail_id = tbl_a.id
			GROUP BY barang_id
			", false);

		return $query->result();
	}

	function get_lastest_harga($barang_id, $cond){
		$query = $this->db->query("SELECT harga_jual
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE barang_id = $barang_id
				) nd_penjualan_detail 
			LEFT JOIN nd_penjualan
			ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			$cond
			ORDER BY tanggal desc
			limit 1
			", false);

		return $query->result();
	}

	function get_lastest_harga_non_customer($barang_id){
		$query = $this->db->query("SELECT nd_penjualan.id, harga_jual
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE barang_id = $barang_id
				) nd_penjualan_detail 
			LEFT JOIN (
				SELECT *
				FROM nd_penjualan
				) nd_penjualan
			ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			where penjualan_type_id = 3
			ORDER BY tanggal desc
			limit 1
			", false);

		return $query->result();
	}

	function search_faktur_jual($no_faktur){
		$query = $this->db->query("SELECT id, no_faktur_lengkap as no_faktur
			FROM (
				SELECT id
				FROM nd_penjualan
				)as tbl_a
			WHERE no_faktur_lengkap LIKE '%$no_faktur%'
			", false);

		return $query->result();
	}

	/*function get_qty_stok_by_barang($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT ifnull(sum(qty_masuk) - sum(qty_keluar),0) as qty, ifnull(sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar),0) as jumlah_roll
				FROM(
					(
				        SELECT barang_id, warna_id, nd_pembelian.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, no_faktur, 'a' as tipe, nd_pembelian.id as id
				        FROM (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	AND gudang_id = $gudang_id
				        	) nd_pembelian
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	) nd_pembelian_detail
				        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, tanggal, no_faktur_lengkap, 'a' as tipe, nd_penjualan_detail.id
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail 
				        	WHERE gudang_id = $gudang_id 
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        WHERE nd_penjualan.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, concat('penyesuaian o/ ',nd_user.username, nd_penyesuaian_stok.id  ) , 1 as tipe, nd_penyesuaian_stok.id
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 0
			        	) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, concat('penyesuaian o/ ',nd_user.username, nd_penyesuaian_stok.id  ) , 1 as tipe,nd_penyesuaian_stok.id
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 1
			        	) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, tanggal, concat_ws('??', nd_user.username, user_id, nd_penyesuaian_stok.id  ) , 2 as tipe, nd_penyesuaian_stok.id
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 2
			        	) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal ,nd_gudang.nama, 'b1' as tipe, nd_mutasi_barang.id
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE status_aktif = 1 
				        	AND gudang_id_after = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_mutasi_barang
						LEFT JOIN nd_gudang
						ON nd_mutasi_barang.gudang_id_before = nd_gudang.id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, tanggal,nd_gudang.nama, 'b2' as tipe, nd_mutasi_barang.id
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE status_aktif = 1 
				        	AND gudang_id_before = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_mutasi_barang
						LEFT JOIN nd_gudang
						ON nd_mutasi_barang.gudang_id_after = nd_gudang.id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, '', 'a' as tipe, nd_retur_jual_detail.id
				        FROM (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
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
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) nd_retur_jual_qty
				        ON nd_retur_jual_qty.retur_jual_detail_id = nd_retur_jual_detail.id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal,nd_gudang.nama, 's1' as tipe, t1.id
				    	FROM (
				        	SELECT *
				        	FROM nd_stok_opname_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND stok_opname_id = '$stok_opname_id'
				        	) t1
						LEFT JOIN nd_stok_opname t2
						ON t1.stok_opname_id = t2.id
						LEFT JOIN nd_gudang
						ON t1.gudang_id = nd_gudang.id
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				ORDER BY tanggal asc
				");
		
		return $query;
		// return $this->db->last_query();
	}*/

	function get_qty_stok_by_barang_detail($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $toko_id){
		$query = $this->db->query("SELECT qty, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 
						tanggal, no_faktur, 'a' as tipe, t2.id as id
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
				        	FROM (
				        		SELECT *
				        		FROM nd_pembelian
					        	WHERE tanggal_sj >= '$tanggal_awal'
					        	OR tanggal >= '$tanggal_awal'
				        		) nd_pembelian 
				        	WHERE status_aktif = 1
				        	AND gudang_id = $gudang_id
							AND toko_id = $toko_id
				        	) t2
				        ON t1.pembelian_id = t2.id
				        WHERE t2.id is not null
			        	AND gudang_id = $gudang_id
				        GROUP BY qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , 0, sum(jumlah_roll) as jumlah_roll_keluar, 
						tanggal, no_faktur, 'j' as tipe, t2.id as id
				        FROM (
				        	SELECT a.id, penjualan_id, barang_id, warna_id, b.qty, b.jumlah_roll, gudang_id
				        	FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE barang_id = $barang_id
					        	AND warna_id = $warna_id
								AND toko_id = $toko_id
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
							AND toko_id = $toko_id
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
			        	AND tipe_transaksi = 1
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
				        AND gudang_id = $gudang_id
						AND toko_id = $toko_id
						GROUP BY qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,0, sum(jumlah_roll),  tanggal, keterangan, 'ps2' as tipe, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal >= '$tanggal_awal'
			        	AND barang_id = $barang_id
			        	AND warna_id = $warna_id
		        		AND tipe_transaksi = 2
				        AND gudang_id = $gudang_id
						AND toko_id = $toko_id
						GROUP BY qty
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty ,0, sum(jumlah_roll),  tanggal, keterangan, 'ec1' as tipe, t1.id
			        	FROM (
							SELECT *
							FROM nd_mutasi_stok_eceran
							WHERE tanggal >= '$tanggal_awal'
							AND barang_id = $barang_id
							AND warna_id = $warna_id
							AND gudang_id = $gudang_id
							AND toko_id = $toko_id
							AND status_aktif = 1
							)t1
							LEFT JOIN (
								SELECT qty, sum(jumlah_roll) as jumlah_roll, mutasi_stok_eceran_id
								FROM nd_mutasi_stok_eceran_qty
								GROUP BY qty, mutasi_stok_eceran_id
								)t2
								ON t2.mutasi_stok_eceran_id = t1.id
							GROUP BY qty
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						qty as qty_masuk, sum(jumlah_roll), 0,
						tanggal, 'so', 'so1', t1.id
				        FROM (
                            SELECT id, barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
                            FROM nd_stok_opname_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND stok_opname_id = $stok_opname_id
							AND toko_id = $toko_id
							GROUP BY qty, barang_id, warna_id, gudang_id, stok_opname_id
                        ) t1
                        LEFT JOIN nd_stok_opname t2
                        ON t1.stok_opname_id = t2.id
						GROUP BY qty
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						(qty), 0, sum(jumlah_roll),
						tanggal, concat('Diambil untuk <b>',nama_barang,'</b>'), 'ask',
						t1.id
				        FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_awal'
								AND gudang_id = $gudang_id 
								AND barang_id_sumber = $barang_id
								AND warna_id_sumber = $warna_id
								AND toko_id = $toko_id
							)tA
							LEFT JOIN nd_barang as b2
							ON tA.barang_id_hasil = b2.id
							LEFT JOIN nd_warna as w2
							ON tA.warna_id_hasil = w2.id

                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id, 
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll,
							group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
							FROM nd_assembly_detail_sumber 
							GROUP BY assembly_id, supplier_id
							)t2
                        ON t2.assembly_id = t1.id
						GROUP BY barang_id, warna_id, gudang_id, tanggal
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, 
						(qty), sum(jumlah_roll), 0, 
						tanggal, concat('Diambil dari <b>',nama_barang,'</b>'), 'asm',
						t1.id
				        FROM (
							SELECT tA.*, concat(nama_jual,' ', warna_jual) as nama_barang
							FROM (
								SELECT *
								FROM nd_assembly
								WHERE tanggal >= '$tanggal_awal'
								AND gudang_id = $gudang_id 
								AND barang_id_hasil = $barang_id
								AND warna_id_hasil = $warna_id
								AND toko_id = $toko_id

							)tA
							LEFT JOIN nd_barang as b1
							ON tA.barang_id_sumber = b1.id
							LEFT JOIN nd_warna as w1
							ON tA.warna_id_sumber = w1.id
                        ) t1
                        LEFT JOIN (
							SELECT assembly_id, barang_id, warna_id, supplier_id,
							sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll,
							group_concat(concat(qty,',',jumlah_roll) SEPARATOR '??') as qty_data
							FROM nd_assembly_detail_hasil 
							GROUP BY assembly_id
							)t2
                        ON t2.assembly_id = t1.id
						WHERE t2.assembly_id is not null
						GROUP BY barang_id, warna_id, gudang_id, tanggal
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, 
						qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
						0 as qty_keluar, 0 as jumlah_roll_keluar, 
						tanggal, '', 'a' as tipe, nd_retur_jual_detail.id
				        FROM (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal'
							AND toko_id = $toko_id
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
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) nd_retur_jual_qty
				        ON nd_retur_jual_qty.retur_jual_detail_id = nd_retur_jual_detail.id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, 
						0 as qty_masuk, 0 as jumlah_roll_masuk, 
						qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, 
						tanggal, '', 'a' as tipe, nd_retur_beli_detail.id
				        FROM (
				        	SELECT *
				        	FROM nd_retur_beli
				        	WHERE status_aktif = 1
				        	AND tanggal >= '$tanggal_awal' 
							AND toko_id = $toko_id
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
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) nd_retur_beli_qty
				        ON nd_retur_beli_qty.retur_beli_detail_id = nd_retur_beli_detail.id
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
		$result['result'] = $query;
		$result['query'] = $this->db->last_query();
		return $result;
		// return $this->db->last_query();
	}

	function get_qty_stok_by_barang_detail_eceran($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $penjualan_detail_id){
		$query = $this->db->query("SELECT tA.stok_eceran_qty_id, tA.supplier_id, 
		tA.qty - ifnull(tB.qty,0) - ifnull(qty_mutasi,0) as qty, tA.tipe, 
		ifnull(tC.qty,0) as qty_jual, 
		ifnull(tC.id,0) as penjualan_qty_detail_id
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
				LEFT JOIN (
						SELECT sum(qty) as qty_mutasi, mutasi_stok_eceran_qty_source_id
						FROM nd_mutasi_stok_eceran_qty
						WHERE mutasi_stok_eceran_qty_source_id is not null
						GROUP BY mutasi_stok_eceran_qty_source_id
				)tD
				ON tA.stok_eceran_qty_id = tD.mutasi_stok_eceran_qty_source_id
				WHERE barang_id is not null
				AND warna_id is not null
				AND tA.qty - ifnull(tB.qty,0) > 0
				");
		
		return $query;
		// return $this->db->last_query();
	}

//==========================================dp_list================================

	function get_dp_list(){
		$query = $this->db->query("SELECT tbl_a.id, nama, status_aktif , ifnull(dp_masuk,0) - ifnull(dp_keluar,0) - ifnull(dp_on_piutang,0) as saldo
			FROM (
				SELECT *
				FROM nd_customer
				WHERE status_aktif = 1
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
			LEFT JOIN (
				SELECT sum(amount) as dp_on_piutang, customer_id
				FROM (
					SELECT *
					FROM nd_pembayaran_piutang_nilai
					WHERE pembayaran_type_id = 5
					) t1
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_piutang
					WHERE status_aktif = 1
					) t2
				ON t1.pembayaran_piutang_id = t2.id
				WHERE t2.id is not null
				) tbl_d
			ON tbl_d.customer_id = tbl_a.id
		");
		return $query->result();
	}

	function get_dp_awal($customer_id, $from){
		$query = $this->db->query("SELECT ifnull(dp_masuk,0) - ifnull(dp_keluar,0) as saldo
			FROM (
				SELECT sum(ifnull(amount,0)) as dp_masuk, customer_id
				FROM nd_dp_masuk
				WHERE customer_id = $customer_id
				AND tanggal < '$from'
				group by customer_id
				) as tbl_a
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_keluar, customer_id
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE customer_id = $customer_id
					AND tanggal < '$from'
					) as nd_penjualan
				LEFT JOIN (
					SELECT amount, penjualan_id 
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id = 1
					) as nd_pembayaran_penjualan
				ON nd_pembayaran_penjualan.penjualan_id = nd_penjualan.id
				group by customer_id
				) as tbl_b
			ON tbl_b.customer_id = tbl_a.customer_id
		");
		return $query->result();
	}

	function get_dp_detail($customer_id, $from, $to){
		$query = $this->db->query("SELECT *
			FROM
			(
				(
					SELECT a.id, dp_masuk, dp_keluar, tanggal, a.keterangan,no_faktur_lengkap, pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'i' as type
					FROM (
						SELECT id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-')) as pembayaran_data, pembayaran_type_id
						FROM nd_dp_masuk
						WHERE customer_id = $customer_id
						AND tanggal >= '$from'
						AND tanggal <= '$to'
					) a
					LEFT JOIN nd_pembayaran_type b
					ON a.pembayaran_type_id = b.id
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t2.tanggal, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pj'
					FROM (
						SELECT *
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id = 1
						) t1
					LEFT JOIN (
						SELECT *
						FROM nd_penjualan
						WHERE status_aktif = 1
						AND tanggal >= '$from'
						AND tanggal <= '$to'
						AND customer_id = $customer_id
						) t2
					ON t1.penjualan_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t2.tanggal, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pp'
					FROM (
						SELECT amount, pembayaran_piutang_id, dp_masuk_id, 1 as pembayaran_type_id
						FROM nd_pembayaran_piutang_nilai
						WHERE pembayaran_type_id = 5
						AND tanggal_transfer >= '$from'
						AND tanggal_transfer <= '$to'
						) t1
					LEFT JOIN (
						SELECT *, 'Pelunasan Piutang' as pembayaran_data
						FROM nd_penjualan
						WHERE status_aktif = 1
						AND customer_id = $customer_id
						) t2
					ON t1.pembayaran_piutang_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				) 
			) A
			order by tanggal asc
		");
		return $query->result();
	}

	function get_dp_detail_by_dp($customer_id, $dp_masuk_id){
		$query = $this->db->query("SELECT *
			FROM
			(
				(
					SELECT a.id, dp_masuk, dp_keluar, tanggal, a.keterangan,no_faktur_lengkap, pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'i' as type
					FROM (
						SELECT id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-')) as pembayaran_data, pembayaran_type_id
						FROM nd_dp_masuk
						WHERE id = $dp_masuk_id
					) a
					LEFT JOIN nd_pembayaran_type b
					ON a.pembayaran_type_id = b.id
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t2.tanggal, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pj'
					FROM (
						SELECT *
						FROM nd_pembayaran_penjualan
						WHERE dp_masuk_id = $dp_masuk_id
						) t1
					LEFT JOIN (
						SELECT *
						FROM nd_penjualan
						WHERE status_aktif = 1
						AND customer_id = $customer_id
						) t2
					ON t1.penjualan_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t2.tanggal, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pp'
					FROM (
						SELECT amount, pembayaran_piutang_id, dp_masuk_id, 1 as pembayaran_type_id
						FROM nd_pembayaran_piutang_nilai
						WHERE pembayaran_type_id = 5
						AND dp_masuk_id = $dp_masuk_id
						) t1
					LEFT JOIN (
						SELECT *, 'Pelunasan Piutang' as pembayaran_data
						FROM nd_penjualan
						WHERE status_aktif = 1
						AND customer_id = $customer_id
						) t2
					ON t1.pembayaran_piutang_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				) 
			) A
			order by tanggal asc
		");
		return $query->result();
	}

	function get_dp_berlaku($customer_id, $penjualan_id){
		$query = $this->db->query("SELECT a.*, c.nama as bayar_dp, b.amount as amount_bayar
				FROM (
					SELECT t1.id,amount - ifnull(amount_use,0)  as amount, tanggal, keterangan, no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
					FROM (
						SELECT id,amount, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
						FROM nd_dp_masuk
						WHERE customer_id = $customer_id
					)t1
					LEFT JOIN (
						SELECT dp_masuk_id, sum(amount) as amount_use
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id = 1
						AND penjualan_id != $penjualan_id
						GROUP BY dp_masuk_id
					)t2
					ON t1.id = t2.dp_masuk_id
					WHERE amount - ifnull(amount_use,0) > 0
				) a
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_penjualan
					WHERE penjualan_id = $penjualan_id
					AND pembayaran_type_id = 1
					) b
				ON a.id = b.dp_masuk_id
				LEFT JOIN nd_pembayaran_type c
				ON a.pembayaran_type_id = c.id
		");
		return $query->result();
	}

	function get_data_dp($dp_id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_customer, no_faktur_lengkap as no_faktur, tbl_b.alamat, tbl_c.nama as bayar_dp
			FROM (
				SELECT *,concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap
				FROM nd_dp_masuk
				WHERE id = $dp_id
			) tbl_a
			LEFT JOIN nd_customer tbl_b 
			ON tbl_a.customer_id = tbl_b.id
			LEFT JOIN nd_pembayaran_type tbl_c
			ON tbl_a.pembayaran_type_id = tbl_c.id
		");
		return $query->result();
	}

//==========================================history list================================

	function get_pembelian_history($from, $to){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, tbl_b.nama as toko, no_faktur, tanggal, qty as jumlah, jumlah_roll, 
				tbl_f.nama as supplier, total, created, username, tbl_e.nama as gudang
				FROM ( 
					SELECT * FROM nd_pembelian 
					WHERE DATE(created) >= '$from'
					AND DATE(created) <= '$to'
					ORDER BY created desc 
				) as tbl_a 
				LEFT JOIN nd_toko as tbl_b 
				ON tbl_a.toko_id = tbl_b.id 
				LEFT JOIN ( 
					SELECT tA.id, sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, sum((qty*if(jumlah_roll = 0,1,jumlah_roll))*harga_beli) as total, harga_beli, pembelian_id 
					FROM nd_pembelian_detail tA
					LEFT JOIN nd_pembelian_qty_detail tB
					ON tB.pembelian_detail_id = tA.id 
					group by pembelian_id 
					) as tbl_c 
					ON tbl_c.pembelian_id = tbl_a.id 
				LEFT JOIN nd_gudang as tbl_e ON tbl_a.gudang_id = tbl_e.id 
				LEFT JOIN nd_supplier as tbl_f ON tbl_f.id = tbl_a.supplier_id 
				LEFT JOIN nd_user tbl_h ON tbl_a.user_id = tbl_h.id
		");
		return $query->result();
	}

	function get_penjualan_history($from, $to){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, ifnull(g_total,0) as g_total , ifnull(diskon,0) as diskon, ifnull(ongkos_kirim,0) as ongkos_kirim, if (penjualan_type_id = 3, concat(' ',nama_keterangan, ' (non-pelanggan) '), tbl_c.nama) as nama_customer, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as keterangan, concat_ws('??',tbl_a.id,no_faktur) as data, created , username
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE DATE(created) >= '$from'
					AND DATE(created) <= '$to'
					ORDER BY created desc
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
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
				LEFT JOIN nd_user tbl_e
				ON tbl_a.user_id = tbl_e.id
		");
		return $query->result();
	}


//==============================piutang=========================================================

	function get_piutang_list(){
		$query = $this->db->query("SELECT tbl_a.status_aktif, ifnull(tbl_c.nama,'no name') as nama_customer, sum(ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
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
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_piutang_temp_detail
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim < 0
				group by customer_id
			", false);

		return $query->result();
	}

	function get_piutang_list_all(){
		
		$query = $this->db->query(" SELECT customer_id, t2.nama as nama_customer, t3.nama as nama_toko, sum(sisa_piutang) as sisa_piutang, MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id
			FROM (
				(
					SELECT tbl_a.status_aktif, sum((ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id
					FROM (
						SELECT *
						FROM nd_penjualan 
						WHERE status_aktif = 1
						AND penjualan_type_id != 3
						AND no_faktur != ''
						ORDER BY tanggal desc
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
					LEFT JOIN (
						SELECT penjualan_id, sum(amount) as total_bayar
						FROM nd_pembayaran_piutang_temp_detail
						GROUP BY penjualan_id
						) as tbl_d
					ON tbl_d.penjualan_id = tbl_a.id
					WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim < 0
					group by customer_id, toko_id

				)UNION(
					SELECT 1, sum(ifnull(amount,0) - 0) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id
					FROM nd_piutang_awal
					GROUP BY customer_id, toko_id
				)
			) t1
			LEFT JOIN nd_customer as t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_toko t3
			ON t1.toko_id = t3.id
			GROUP BY customer_id
			ORDER BY t2.nama asc
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_unbalance(){
		$query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) - amount as balance
				FROM (
					SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
					FROM nd_pembayaran_piutang_temp a
					LEFT JOIN nd_customer b
					ON a.customer_id = b.id
					LEFT JOIN nd_toko c
					ON a.toko_id = c.id
					) tbl_a
				LEFT JOIN (
					SELECT pembayaran_piutang_id, sum(amount) as amount
					FROM nd_pembayaran_piutang_temp_detail
					GROUP BY pembayaran_piutang_id
					) tbl_b
				ON tbl_a.id = tbl_b.pembayaran_piutang_id
				LEFT JOIN (
					SELECT sum(amount) as bayar, pembayaran_piutang_id
					FROM nd_pembayaran_piutang_temp_nilai
					GROUP BY pembayaran_piutang_id
					) tbl_c
				ON tbl_a.id = tbl_c.pembayaran_piutang_id
				WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount != 0

			", false);

		return $query->result();
	}


	function get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id){
		$query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tbl_e.nama as customer, customer_id, total as total_jual, amount, ifnull(total,0) - ifnull(amount,0) as sisa_piutang, tbl_a.id as penjualan_id, jatuh_tempo
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND customer_id = $customer_id
					AND toko_id = $toko_id
					AND status_aktif = 1
					AND penjualan_type_id != 3
					AND no_faktur != ''
					) as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as total, harga_jual, penjualan_id, barang_id, satuan_id
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP BY penjualan_detail_id
					) nd_penjualan_qty_detail
					ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
					group by penjualan_id
					) as tbl_c
				ON tbl_c.penjualan_id = tbl_a.id
				LEFT JOIN nd_gudang as tbl_d
				ON tbl_a.gudang_id = tbl_d.id
				LEFT JOIN nd_customer as tbl_e
				ON tbl_e.id = tbl_a.customer_id
				LEFT JOIN (
					SELECT sum(amount) as amount, penjualan_id
					FROM nd_pembayaran_piutang_temp_detail
					WHERE data_status = 1
					GROUP BY penjualan_id
					) tbl_f
				ON tbl_a.id = tbl_f.penjualan_id
				WHERE ifnull(total,0) - ifnull(amount,0) > 0
			", false);

		return $query->result();
	}

	function get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id){
		$query = $this->db->query("SELECT 1, no_faktur, nama as customer, customer_id, amount as total_jual, 0 as amount, ifnull(amount,0) - 0 as sisa_piutang, a.id as penjualan_id, jatuh_tempo
				FROM (
					SELECT *
					FROM nd_piutang_awal
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND customer_id = $customer_id
					AND toko_id = $toko_id
				) a
				LEFT JOIN nd_customer b
				ON a.customer_id = b.id
				LEFT JOIN (
					SELECT sum(amount) as bayar, penjualan_id
					FROM nd_pembayaran_piutang_temp_detail
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
				FROM nd_pembayaran_piutang_temp_nilai t1
				LEFT JOIN nd_pembayaran_piutang_temp t2
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
							FROM nd_pembayaran_piutang_temp
							$cond
							AND DATE(created) >= '$tanggal_start'
							AND DATE(created) <= '$tanggal_end'
						)UNION(
							SELECT tbl_b.id, customer_id, toko_id, 0
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_temp_nilai
								WHERE tanggal_transfer >= '$tanggal_start'
								AND tanggal_transfer <= '$tanggal_end'
								
								)tbl_a
							LEFT JOIN nd_pembayaran_piutang_temp tbl_b
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
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id = $pembayaran_piutang_id
				)
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_data($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_customer, tbl_d.nama as nama_toko, tbl_c.alamat as alamat_customer, tbl_c.kota
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_temp
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
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id = $id
				AND data_status = 2
				) tbl_a
			LEFT JOIN nd_piutang_awal tbl_b
			ON tbl_a.penjualan_id = tbl_b.id
			LEFT JOIN (
				SELECT sum(amount) as total_bayar, penjualan_id
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id != $id
				AND data_status = 2
				GROUP BY penjualan_id
				) tbl_c
			ON tbl_c.penjualan_id = tbl_b.id
			", false);

		return $query->result();
	}

	

	function get_pembayaran_piutang_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, ifnull(sisa_piutang,0) - ifnull(total_bayar,0) as sisa_piutang, jatuh_tempo, concat(DATE_FORMAT(tanggal,'%Y'),'/CVSUN/INV/',LPAD(no_faktur,4,'0')) as no_faktur, total_jual, tbl_b.tanggal
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id = $id
				AND data_status = 1
				) tbl_a
			LEFT JOIN nd_penjualan tbl_b
			ON tbl_a.penjualan_id = tbl_b.id
			LEFT JOIN (
				SELECT sum(qty*harga_jual) as sisa_piutang, penjualan_id, sum(qty*harga_jual) as total_jual
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) nd_penjualan_qty_detail
				ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				GROUP BY penjualan_id
				) tbl_c
			ON tbl_a.penjualan_id = tbl_c.penjualan_id
			LEFT JOIN (
				SELECT sum(amount) as total_bayar, penjualan_id
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id != $id
				AND data_status = 1
				GROUP BY penjualan_id
				) tbl_d
			ON tbl_c.penjualan_id = tbl_d.penjualan_id
			", false);

		return $query->result();
	}



}