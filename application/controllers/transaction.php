<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction extends CI_Controller {

	private $data = [];
	

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}
		$this->data['username'] = is_username();
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());

    //     $this->load->database('mysqli');

		$this->load->model('transaction_model','tr_model',true);
		$this->load->model('finance_model','fi_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 ORDER BY nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 ORDER BY warna_jual asc');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');
		$this->barang_sku_aktif = $this->common_model->db_select('nd_barang_sku where status_aktif = 1');

		$this->mysqli_conn = $this->db->conn_id;
	}

	function index(){
		redirect('admin');
	}

	function setting_link($string){
		return rtrim(base64_encode($string),'=');
	}

	function cek_pin(){
		$pin = $this->input->post('pin');
		$baris = $this->common_model->db_select_num_rows("nd_user where posisi_id < 3 and status_aktif = 1 and PIN is not null AND PIN ='".$pin."' limit 1");
		if ($baris == 1) {
			echo 'OK';
		}else{
			print_r($baris);
		}
	}

	function register_new_sku($barang_id, $warna_id, $is_eceran){

		$get_sku = $this->common_model->db_select("nd_barang_sku where
		 barang_id = $barang_id and warna_id = $warna_id and isEceran=$is_eceran");

		$sku_id = 0;
		foreach ($get_sku as $row) {
			$sku_id = $row->id;
		}

		if($sku_id == 0){

			$barang_data = $this->common_model->db_select("nd_barang WHERE id='$barang_id'");
			$warna_data = $this->common_model->db_select("nd_warna WHERE id='$warna_id'");
			$nama_barang = '';
			$satuan_id = '';
			$packaging_id = '';
			$nama_satuan = '';
			$nama_packaging = '';
	
			foreach ($barang_data as $row) {
				$nama_barang = $row->nama_jual.' ';
				$satuan_id = $row->satuan_id;
				$packaging_id = $row->packaging_id;
	
			}
			foreach ($warna_data as $row) {
				$nama_barang .= $row->warna_jual;
			}
	
			foreach ($this->satuan_list_aktif as $row) {
				if ($row->id == $satuan_id) {
					$nama_satuan = $row->nama;
				}
	
				if ($row->id == $packaging_id) {
					$nama_packaging = $row->nama;
				}
			}
			
			$nSku = array(
				'barang_id' => $barang_id,
				'warna_id' => $warna_id, 
				'nama_barang' => $nama_barang.($is_eceran == 1 ? ' .' : ''),
				'nama_satuan' => $nama_satuan, 
				'nama_packaging' => $nama_packaging, 
				'user_id' => is_user_id(),
				'status_aktif'=> 1
			);

			try {
				$this->common_model->db_insert("nd_barang_sku", $nSku);	
			} catch (Exception $th) {
				//throw $th;
			}
	
		}

	}  

//===================================ajax_check=============================================

	function check_faktur_pembelian(){
		$no_faktur = $this->input->post('no_faktur');
		$result = $this->common_model->db_select_num_rows("nd_pembelian where no_faktur='".$no_faktur."'");
		echo $result;
	}

	function check_new_faktur_pembelian(){
		$no_faktur = $this->input->post('no_faktur');
        $id = $this->input->post('pembelian_id');

        echo 'true';
	}


    function check_new_surat_jalan(){
        $no_surat_jalan = trim($this->input->post('no_surat_jalan'));
        $id = $this->input->post('pembelian_id');

        $result = $this->common_model->db_select_num_rows("nd_pembelian where no_surat_jalan='".$no_surat_jalan."' AND status_aktif = 1");
        if ($result == 0) {
            echo 'true';
        }else{
            echo 'false';
        }

    }

    function check_edit_surat_jalan(){
        $no_surat_jalan = trim($this->input->post('no_surat_jalan'));
        $id = $this->input->post('pembelian_id');
        $result = $this->common_model->db_select_num_rows("nd_pembelian where no_surat_jalan='".$no_surat_jalan."' AND id !=".$id);
        if ($result == 0) {
            echo 'true';
        }else{
            echo 'false';
        }

    }


	function check_edit_faktur_pembelian(){
		$no_faktur = $this->input->post('no_faktur');
		$pembelian_id = $this->input->post('pembelian_id');
		$result = $this->common_model->db_select_num_rows("nd_pembelian where no_faktur='".$no_faktur."' AND id !=$pembelian_id limit 1");
		if ($result == 0) {
			echo 'true';
		}else{
			echo 'false';
		}
	}


//===================================po pembelian=============================================
    function po_pembelian_list(){
        $menu = is_get_url($this->uri->segment(1)) ;

        $data = array(
            'content' =>'admin/transaction/po_pembelian_list',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar PO Pembelian',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );


        $data['user_id'] = is_user_id();
        $data['po_pembelian_list'] = $this->common_model->db_select('nd_po_pembelian order by tanggal desc'); 
        $this->load->view('admin/template',$data);
    }

    function data_po_pembelian(){

        // $session_data = $this->session->userdata('do_filter');
        
        $aColumns = array('status_aktif', 'po_number','tanggal', 'supplier','keterangan', 'status_data');
        
        $sIndexColumn = "id";
        
        // paging
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".$this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] ).", ".
                $this->mysqli_conn->real_escape_string( $_GET['iDisplayLength'] );
        }
        $numbering = $this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] );
        $page = 1;
        
        // ordering
        if ( isset( $_GET['iSortCol_0'] ) ){
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                        ".$this->mysqli_conn->real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ){
                $sOrder = "";
            }
        }

        // filtering
        $sWhere = "";
        if ( $_GET['sSearch'] != "" ){
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        // individual column filtering
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
                if ( $sWhere == "" ){
                    $sWhere = "WHERE ";
                }
                else{
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

        $rResult = $this->tr_model->get_po_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_po_pembelian');
        $Filternya = $this->tr_model->get_po_pembelian_list_ajax($aColumns, $sWhere , $sOrder, '');
        $iFilteredTotal = $Filternya->num_rows();
        // $iTotal = $rResultTotal;
        // $iFilteredTotal = $iTotal;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResultTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ($rResult->result_array() as $aRow){
            $y = 0;
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $row[] = $aRow[ $aColumns[$i] ];
            }
            $y++;
            $page++;
            $output['aaData'][] = $row;
        }
        
        echo json_encode( $output );
    }

    function po_pembelian_list_insert(){
        $ini = $this->input;
        $data = array(
            'tanggal' => is_date_formatter($ini->post('tanggal')) ,
            'toko_id' => $ini->post('toko_id'),
            'supplier_id' => $ini->post('supplier_id'),
            'catatan' => ($ini->post('catatan') == '' ? null : $ini->post('catatan')) ,
            'sales_contract' => ($ini->post('sales_contract') == '' ? null : $ini->post('sales_contract') ),
            'user_id' => is_posisi_id(),
            'po_status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'status_aktif' => 1
            );
        // print_r($data);

        $result_id = $this->common_model->db_insert('nd_po_pembelian', $data);
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$result_id);
    }

    function po_pembelian_finish(){
        $id = $this->input->get('id');
        $po_data = $this->common_model->db_select('nd_po_pembelian');
        foreach($po_data as $row) {
            $tahun = date('Y', strtotime($row->tanggal));
            $po_number = $row->po_number;
        }

        if (!isset($po_number)) {
            $po_number = 1;
            $get = $this->common_model->db_select("nd_po_pembelian where po_number is not null AND YEAR(tanggal)='".$tahun."' order by po_number desc limit 1");
            foreach ($get as $row) {
                $po_number = $row->po_number + 1;
            }
        }

        $data = array('po_number' => $po_number,'po_status' => 0);    
        $this->common_model->db_update('nd_po_pembelian', $data,'id',$id);
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$id);

    }

    function po_pembelian_detail(){
        
        $menu = is_get_url($this->uri->segment(1)) ;

        $id = $this->input->get('id');
        $data = array(
            'content' =>'admin/transaction/po_pembelian_detail',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'PO Pembelian',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );

        if ($id != '') {
            $data['po_pembelian_data'] = $this->tr_model->get_data_po_pembelian($id);
            $data['po_pembelian_detail'] = $this->tr_model->get_data_po_pembelian_detail($id);
            foreach ($data['po_pembelian_detail'] as $row) {
                $data['po_pembelian_warna'][$row->id] = $this->tr_model->get_data_po_pembelian_warna($row->id);
            }
            foreach ($data['po_pembelian_data']  as $row) {
                $toko_id = $row->toko_id;
                $supplier_id = $row->supplier_id;
            }
            $data['toko_data'] = $this->common_model->db_select('nd_toko where id='.$toko_id);
            $data['supplier_data'] = $this->common_model->db_select('nd_supplier where id='.$supplier_id);

        }else{
            $data['po_pembelian_data'] = array();
            $data['po_pembelian_detail'] = array();
            $data['po_pembelian_warna'] = array();
            $data['toko_data'] = array();
            $data['supplier_data'] = array();
        }
        $this->load->view('admin/template',$data);
    }

    function po_pembelian_sales_contract_update(){
        $po_pembelian_id = $this->input->post('po_pembelian_id');
        $data = array(
            'sales_contract' => $this->input->post('sales_contract') );
        $this->common_model->db_update('nd_po_pembelian', $data,'id', $po_pembelian_id);
        echo "OK";
    }

    function po_pembelian_list_detail_insert(){
        $ini = $this->input;
        $po_pembelian_detail_id = $this->input->post('po_pembelian_detail_id');
        $data = array(
            'po_pembelian_id' => $ini->post('po_pembelian_id'),
            'barang_id' => $ini->post('barang_id') ,
            'harga' => str_replace('.', '', $ini->post('harga')),
            'qty' => str_replace('.', '', $ini->post('qty')),
            );

        if ($po_pembelian_detail_id == '') {
            $this->common_model->db_insert('nd_po_pembelian_detail', $data);
        }else{
            $this->common_model->db_update('nd_po_pembelian_detail', $data,'id', $po_pembelian_detail_id);
        }
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$ini->post('po_pembelian_id'));
        
    }
    
    function po_pembelian_detail_warna(){
        $id = $this->input->get('id');
        
        $menu = is_get_url($this->uri->segment(1)) ;

        $id = $this->input->get('po_pembelian_id');
        $po_pembelian_detail_id = $this->input->get('po_pembelian_detail_id');
        $data = array(
            'content' =>'admin/transaction/po_pembelian_detail_warna',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'PO Detail Warna',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );

        $data['po_pembelian_data'] = $this->tr_model->get_data_po_pembelian($id);
        $data['po_pembelian_data_detail'] = $this->tr_model->get_data_po_pembelian_detail_info($po_pembelian_detail_id);
        $data['po_pembelian_data_warna'] = $this->tr_model->get_data_po_pembelian_detail_warna($po_pembelian_detail_id);
        
        $this->load->view('admin/template_no_sidebar',$data);

    }

    function po_pembelian_detail_warna_batch(){
        $id = $this->input->get('id');
        $menu = is_get_url($this->uri->segment(1)) ;
        $po_pembelian_detail_id = $this->input->get('po_pembelian_detail_id');
        $batch_id = $this->input->get('batch_id');

        $data = array(
            'content' =>'admin/transaction/po_pembelian_detail_warna_batch',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'PO Detail Warna',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );

        $data['po_pembelian_data'] = $this->tr_model->get_data_po_pembelian($id);
        
        foreach ($data['po_pembelian_data'] as $row) {
            $toko_id = $row->toko_id;
            $supplier_id = $row->supplier_id;
        }

        $data['toko_data'] = $this->common_model->db_select('nd_toko where id='.$toko_id);
        $data['supplier_data'] = $this->common_model->db_select('nd_supplier where id='.$supplier_id);
        $data['data_barang_po'] = $this->tr_model->get_data_barang_po($id);

        $data['po_pembelian_data_batch'] = $this->common_model->db_select("nd_po_pembelian_batch where po_pembelian_id=".$id);
        if ($batch_id == '') {
            foreach ($data['po_pembelian_data_batch'] as $row) {
                $batch_id = $row->id;
            }
        }
        $data['po_pembelian_data_warna'] = array();
        if ($batch_id != '') {
            foreach ($data['data_barang_po'] as $row) {
                $data['po_pembelian_data_warna'][$row->id] = $this->tr_model->get_data_po_pembelian_detail_batch($batch_id, $row->id);
            }
        }

        $data['batch_id'] = $batch_id;

        
        $this->load->view('admin/template_no_sidebar',$data);

    }

    function po_pembelian_batch_insert()
    {
        $po_pembelian_id = $this->input->post('po_pembelian_id');
        $batch = 1;
        $get_batch = $this->common_model->db_select("nd_po_pembelian_batch where po_pembelian_id=".$po_pembelian_id." ORDER BY batch desc limit 1");
        foreach ($get_batch as $row) {
            $batch = $row->batch + 1;
        }
        $data = array(
            'po_pembelian_id' => $po_pembelian_id ,
            'tanggal' => is_date_formatter($this->input->post('tanggal')),
            'batch' => $batch );

        $result_id = $this->common_model->db_insert('nd_po_pembelian_batch', $data);
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id."&batch_id=".$result_id);
    }

    function po_pembelian_warna_insert(){
        $ini = $this->input;
        $po_pembelian_id = $this->input->post('po_pembelian_id');
        $po_pembelian_detail_warna_id = $this->input->post('po_pembelian_detail_warna_id');
        $batch_id = $ini->post('batch_id');

        $data = array(
            'po_pembelian_detail_id' => $ini->post('po_pembelian_detail_id'),
            'po_pembelian_batch_id' => $batch_id,
            'warna_id' => $ini->post('warna_id'),
            'qty' => str_replace('.', '', $ini->post('qty')),
            'OCKH' => $ini->post('OCKH'),
        );

        if ($po_pembelian_detail_warna_id == '') {
            $this->common_model->db_insert('nd_po_pembelian_warna', $data);
        }else{
            $this->common_model->db_update('nd_po_pembelian_warna', $data,'id', $po_pembelian_detail_warna_id);
        }
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$batch_id);
    }

    function po_pembelian_OCKH_update(){
        $id = $this->input->post("id");
        $data = array(
            'OCKH' => $this->input->post("OCKH") );
        $this->common_model->db_update('nd_po_pembelian_warna',$data,'id',$id);
        echo "OK";
    }

    function pembelian_detail_warna_remove(){
        $id = $this->input->get('id');
        $po_pembelian_id = $this->input->get('po_pembelian_id');
        $batch_id = $this->input->get('batch_id');
        $this->common_model->db_delete('nd_po_pembelian_warna','id',$id);
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$batch_id);

    }

    function po_ockh_update(){
        $id = $this->input->post('id');
        $data = array(
            'OCKH' => $this->input->post('OCKH') );
        $this->common_model->db_update('nd_po_pembelian_warna', $data,'id', $id);
        echo "OK"; 
    }

    function po_pembelian_open(){
        $id = $this->input->get('id');
        $data = array('po_status' => 1 );
        $this->common_model->db_update('nd_po_pembelian',$data,'id',$id);
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$id);
    }

//===================================pembelian=============================================
	function pembelian_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/transaction/pembelian_list_slim',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Pembelian',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['pembelian_list'] = $this->common_model->db_select('nd_pembelian order by tanggal desc'); 
		$this->load->view('admin/template',$data);
	}

	function data_pembelian(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif','toko', 'no_faktur','tanggal', 'jumlah','jumlah_roll','nama_barang','gudang', 'harga_beli', 'total','supplier','keterangan', 'status_data');
        
        $sIndexColumn = "id";
        
        // paging
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".$this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] ).", ".
                $this->mysqli_conn->real_escape_string( $_GET['iDisplayLength'] );
        }
        $numbering = $this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] );
        $page = 1;
        
        // ordering
        if ( isset( $_GET['iSortCol_0'] ) ){
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                        ".$this->mysqli_conn->real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ){
                $sOrder = "";
            }
        }

        // filtering
        $sWhere = "";
        if ( $_GET['sSearch'] != "" ){
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        // individual column filtering
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
                if ( $sWhere == "" ){
                    $sWhere = "WHERE ";
                }
                else{
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

        $rResult = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_pembelian');
        $Filternya = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, '');
        $iFilteredTotal = $Filternya->num_rows();
        // $iTotal = $rResultTotal;
        // $iFilteredTotal = $iTotal;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResultTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ($rResult->result_array() as $aRow){
        	$y = 0;
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            	$row[] = $aRow[ $aColumns[$i] ];
            }
            $y++;
            $page++;
            $output['aaData'][] = $row;
        }
        
        echo json_encode( $output );
	}

	function data_pembelian_slim(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif','toko', 'no_faktur','no_surat_jalan','tanggal', 'gudang', 'total','supplier','keterangan', 'status_data');
        
        $sIndexColumn = "id";
        
        // paging
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".$this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] ).", ".
                $this->mysqli_conn->real_escape_string( $_GET['iDisplayLength'] );
        }
        $numbering = $this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] );
        $page = 1;
        
        // ordering
        if ( isset( $_GET['iSortCol_0'] ) ){
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                        ".$this->mysqli_conn->real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ){
                $sOrder = "";
            }
        }

        // filtering
        $sWhere = "";
        if ( $_GET['sSearch'] != "" ){
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        // individual column filtering
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
                if ( $sWhere == "" ){
                    $sWhere = "WHERE ";
                }
                else{
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

        $status_aktif = 1;
        if ($this->input->get('status_aktif')) {
            $status_aktif = $this->input->get('status_aktif');
        }
        
        if ( $sWhere == "" ){
            $sWhere = "WHERE status_aktif = ".$status_aktif;
        }
        else{
            $sWhere .= " AND status_aktif = ".$status_aktif;
        }
        $rResult = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_pembelian');
        $Filternya = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere , $sOrder, '');
        $iFilteredTotal = $Filternya->num_rows();
        // $iTotal = $rResultTotal;
        // $iFilteredTotal = $iTotal;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResultTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ($rResult->result_array() as $aRow){
        	$y = 0;
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            	$row[] = $aRow[ $aColumns[$i] ];
            }
            $y++;
            $page++;
            $output['aaData'][] = $row;
        }


        // echo $aColumns, $sWhere, $sOrder, $sLimit;
        echo json_encode( $output );
	}

    function get_po_pembelian_by_supplier(){
        $supplier_id = $this->input->post('supplier_id');
        $get_po_list = $this->tr_model->get_po_pembelian_by_supplier($supplier_id);
        echo json_encode($get_po_list) ;
    }

	function pembelian_list_insert(){
		$ini = $this->input;
		$no_nota = 1;
		$tanggal = is_date_formatter($ini->post('tanggal'));
        $tanggal_sj = ($ini->post('tanggal_sj') !='' ? is_date_formatter($ini->post('tanggal_sj')) : null);
		$year = date('Y', strtotime($tanggal));
		$data_last = $this->common_model->db_select("nd_pembelian WHERE YEAR(tanggal) ='$year' order by no_nota desc limit 1");
		foreach ($data_last as $row) {
			$no_nota = $row->no_nota + 1;
		}
		$data_pembelian = array(
			'no_nota' => $no_nota,
            'no_faktur' => $ini->post('no_faktur') ,
			'no_surat_jalan' => $ini->post('no_surat_jalan') ,
			'ockh' => $ini->post('ockh'),
            'tanggal'=>$tanggal,
			'tanggal_sj'=>$tanggal_sj,
			'gudang_id'=>$ini->post('gudang_id'),
			'toko_id'=>$ini->post('toko_id'),
			'supplier_id' => $ini->post('supplier_id'),
            // 'po_pembelian_batch_id' => $ini->post('po_pembelian_batch_id'),
			'diskon' => ($ini->post('diskon') != '' ? str_replace('.', '', $ini->post('diskon')) : 0 ),
			'jatuh_tempo' => is_date_formatter($ini->post('tanggal')),
			'keterangan' => ($ini->post('keterangan') == '' ? null : $ini->post('keterangan')),
			'user_id' => is_user_id(),
            'status_aktif' => 2
			 );

        // print_r($this->input->post());
		$result_id = $this->common_model->db_insert('nd_pembelian',$data_pembelian);
		redirect(trim(base64_encode('transaction/pembelian_list_detail'),'=').'/'.$result_id);

	}

	function pembelian_list_update(){

		$ini = $this->input;
		$pembelian_id = $ini->post('pembelian_id');
        $tanggal_sj = ($ini->post('tanggal_sj') !='' ? is_date_formatter($ini->post('tanggal_sj')) : null);
		$data = array(
			'no_faktur' => $ini->post('no_faktur') ,
            'no_surat_jalan' => $ini->post('no_surat_jalan') ,
            'ockh' => $ini->post('ockh'),
			'gudang_id'=>$ini->post('gudang_id'),
			'tanggal'=>is_date_formatter($ini->post('tanggal')),
            'tanggal_sj'=>$tanggal_sj,
            'gudang_id'=>$ini->post('gudang_id'),
            'toko_id'=>$ini->post('toko_id'),
            'supplier_id' => $ini->post('supplier_id'),
            'diskon' => ($ini->post('diskon') != '' ? str_replace('.', '', $ini->post('diskon')) : 0 ),
            'jatuh_tempo' => is_date_formatter($ini->post('tanggal')),
            'keterangan' => ($ini->post('keterangan') == '' ? null : $ini->post('keterangan')),
            // 'po_pembelian_batch_id' => $ini->post('po_pembelian_batch_id'),
			'user_id' => is_user_id());

		$this->common_model->db_update('nd_pembelian',$data,'id', $pembelian_id);
		redirect(trim(base64_encode('transaction/pembelian_list_detail'),'=').'/'.$pembelian_id);

	}

    function pembelian_release(){
        $id = $this->input->post('id');
        $data = array(
            'status_aktif' => 1,
            'released_by' => is_user_id(),
            'released_date' => date('Y-m-d H:i:s') );
        $this->common_model->db_update('nd_pembelian', $data, 'id', $id);
        echo "OK";
    }

	function pembelian_list_batal(){
		$id = $this->input->get('id');
		// echo $id;
		$data = array(
			'status_aktif' => -1,
			'cancelled_by' => is_user_id(),
			'cancelled_date' => date('Y-m-d H:i:s') );
		$this->common_model->db_update('nd_pembelian',$data,'id',$id);
		redirect($this->setting_link('transaction/pembelian_list'));
	}

    function pembelian_list_close(){
        $id = $this->input->get('id');
        // echo $id;
        $data = array(
            'status' => 0,
            'closed_by' => is_user_id(),
            'closed_date' => date('Y-m-d H:i:s') );
        $this->common_model->db_update('nd_pembelian',$data,'id',$id);
        redirect(is_setting_link('transaction/pembelian_list_detail').'/'.$id);
    }

    function pembelian_request_open(){
        $pembelian_id = $this->input->post('pembelian_id');
        $data = array(
            'status' => 1 );
        $this->common_model->db_update('nd_pembelian',$data,'id',$pembelian_id);
        redirect($this->setting_link('transaction/pembelian_list_detail').'/'.$pembelian_id);

    }


	function pembelian_list_undo_batal(){
		$id = $this->input->get('id');
		// echo $id;
		$data = array(
			'status_aktif' => 1,
			);
		$this->common_model->db_update('nd_pembelian',$data,'id',$id);
		redirect($this->setting_link('transaction/pembelian_list'));
	}

	function pembelian_list_edit(){
		$id = $this->input->get('id');
		$data['pembelian_list'] = $this->tr_model->data_pembelian_list($id);
		$this->load->view('admin/transaction/pembelian_list_edit',$data);	
	}

	function pembelian_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$id = $this->uri->segment(2);
		$data = array(
			'content' =>'admin/transaction/pembelian_list_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Pembelian',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );

		if ($id != '') {
			$data['pembelian_data'] = $this->tr_model->get_data_pembelian($id);
			$data['pembelian_detail'] = $this->tr_model->get_data_pembelian_detail($id);
            $po_pembelian_batch_id = '';
            $OCKH = '';
            foreach ($data['pembelian_data'] as $row) {
                // $po_pembelian_batch_id = $row->po_pembelian_batch_id;
                $OCKH = $row->ockh;
            }

            // if ($po_pembelian_batch_id != '') {
            //     $barang_list = $this->tr_model->get_pembelian_barang_by_po($po_pembelian_batch_id, $OCKH);
            // }else {
                $barang_list = $this->barang_list_aktif;
            // }

            $data['barang_list'] = $barang_list;

        }else{
            $data['pembelian_data'] = array();
            $data['pembelian_detail'] = array();
            $data['barang_list'] = array();
		}
		$this->load->view('admin/template',$data);
	}

	function get_search_no_faktur(){
		$no_faktur = $this->input->post('no_faktur');
		$result = $this->common_model->db_select("nd_pembelian where no_faktur LIKE '%$no_faktur%' ");
		echo json_encode($result);
	}

	function pembelian_list_detail_insert(){
		$ini = $this->input;
		$pembelian_id = $ini->post('pembelian_id');
        $pembelian_detail_id = $ini->post('pembelian_detail_id');
		$po_pembelian_batch_id = $ini->post('po_pembelian_batch_id');
        $rekap_qty = explode('--', $this->input->post('rekap_qty'));

        $harga_beli = str_replace(',', '', $ini->post('harga_beli'));
        // $harga_beli = str_replace('', '.', $harga_beli);


        if ($po_pembelian_batch_id != '') {
            $data_barang = $this->input->post('barang_id');
            $data_barang = explode('??', $data_barang);
            $barang_id = $data_barang[0];
            $warna_id = $data_barang[1];
        }else{
            $barang_id = $this->input->post('barang_id');
            $warna_id = $this->input->post('warna_id');
        }
		$data = array(
			'pembelian_id' => $pembelian_id ,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id ,
			'harga_beli' => $harga_beli,
            'pengali_type' => $this->input->post('pengali_type')
			);

        // print_r($this->input->post());
        // echo '<hr/>';

		if ($pembelian_detail_id == '') {
			$result_id = $this->common_model->db_insert('nd_pembelian_detail',$data);
            $pembelian_detail_id = $result_id;

			//cek_sku_id
			$this->register_new_sku($barang_id, $warna_id, 0);
            foreach ($rekap_qty as $key => $value) {
                $qty_data = explode('??', $value);
				if ($qty_data[0] != 0 && $qty_data[1] != 0) {
					$data_detail[$key] = array(
						'pembelian_detail_id' => $pembelian_detail_id ,
						'qty' => $qty_data[0],
						'jumlah_roll' => $qty_data[1] 
					);
				}
            }

            $this->common_model->db_insert_batch('nd_pembelian_qty_detail', $data_detail);
		}else{

			$this->register_new_sku($barang_id, $warna_id,0  );
			$this->common_model->db_update('nd_pembelian_detail',$data,'id',$pembelian_detail_id);
		}

        // print_r($data_detail);

		redirect($this->setting_link('transaction/pembelian_list_detail').'/'.$pembelian_id);
			
	}

	function pembelian_detail_update(){
		$id = $this->input->post('id');
		$data = array(
			$this->input->post('column') => $this->input->post('value') );
		$this->common_model->db_update('nd_pembelian_detail', $data,'id',$id);
		echo 'OK';

	}

	function pembelian_detail_remove(){
		$id = $this->input->post('id');
		$this->common_model->db_delete('nd_pembelian_detail', 'id',$id);
		echo 'OK';
	}



	function pembelian_data_update(){
		$id = $this->input->post('pembelian_id');
		$data = array(
			$this->input->post('column') => $this->input->post('value') );
		$this->common_model->db_update('nd_pembelian', $data,'id',$id);
		echo 'OK';
	}

	function pembelian_jatuh_tempo_update(){
		$id = $this->input->post('pembelian_id');
		$ori_tanggal = strtotime($this->input->post('ori_tanggal'));
		$ori_jatuh_tempo = strtotime(is_date_formatter($this->input->post('jatuh_tempo')));
		
		$data = array(
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) );

		$this->common_model->db_update('nd_pembelian', $data,'id',$id);


		$diff = $ori_jatuh_tempo - $ori_tanggal;
		$diff = $diff/(60*60*24);
		if ($diff >= 0) {
			echo 'OK';
		}elseif ($diff < 0) {
			echo 'FALSE';
		}else{
			echo 'ERROR';
		};
	}

	function data_pembelian_list_edit(){
		$id = $this->input->post('id');

		$result = $this->tr_model->data_pembelian_list($id);
		// print_r($result);
		$baris = '';
		foreach ($result as $row) {
			$baris .= "<tr>";
			$baris .= "<td> <input name='barang_id[]' hidden='hidden' value='".$row->barang_id."'>".$row->nama_barang."<input name='warna_id' hidden='hidden' value='".$row->warna_id."'>".$row->warna_beli."</td>";
    		$baris .= "<td> <input name='satuan_id[]' hidden='hidden' value='".$row->satuan_id."'>".$row->nama_satuan."</td>";
			// $baris .= "<td> <input name='gudang_id' hidden='hidden' value='".$row->gudang_id."'>".$row->nama_gudang."</td>";
			$baris .= "<td> <input name='qty[]' value='".$row->qty."'></td>";
			$baris .= "<td> <input name='jumlah_roll[]' value='".$row->jumlah_roll."'></td>";
			$baris .= "<td> <input name='harga_beli[]' class='amount_number' value='".is_rupiah_format($row->harga_beli)."'></td>";
			$baris .= "<td> <span class='total'>".is_rupiah_format($row->qty * $row->harga_beli)."</span></td>";
			$baris .= "<td> <button type='button' class='btn btn-xs red btn-remove-list'><i class='fa fa-times'></i></button></td>";
			$baris .= "</tr>";
		}

		echo $baris;
	}

	function testing_print2(){
		$var = 'testing print yess';
		echo $var;
	}

	function testing_print(){
		$this->load->view('admin/transaction/testing_print');
	}

	function testing_pdf()
	{ 
		//load mPDF library
		$this->load->library('m_pdf');
		//load mPDF library

		//now pass the data//
		$this->data['title']="MY PDF TITLE 1.";
		$this->data['description']="";
		// $this->data['description']=$this->official_copies;
		//now pass the data //

			
		$html=$this->load->view('admin/transaction/testing_pdf',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
	 	 
		//this the the PDF filename that user will get to download
		$pdfFilePath ="mypdfName-".time()."-download.pdf";

			
		//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();
		//generate the PDF!
		$pdf->WriteHTML($html,2);
		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		$pdf->Output($pdfFilePath, "D");
			 
			 	
	}

    function pembelian_list_detail_update(){
        $ini = $this->input;
        $pembelian_id = $ini->post('pembelian_id');
        $pembelian_detail_id = $ini->post('pembelian_detail_id');
        $po_pembelian_batch_id = $ini->post('po_pembelian_batch_id');
        $rekap_qty = explode('--', $this->input->post('rekap_qty'));

        $harga_beli = str_replace(',', '', $ini->post('harga_beli'));
        // $harga_beli = str_replace(',', '.', $harga_beli);


        if ($po_pembelian_batch_id != '') {
            $data_barang = $this->input->post('barang_id');
            $data_barang = explode('??', $data_barang);
            $barang_id = $data_barang[0];
            $warna_id = $data_barang[1];
        }else{
            $barang_id = $this->input->post('barang_id');
            $warna_id = $this->input->post('warna_id');
        }
        $data = array(
            'pembelian_id' => $pembelian_id ,
            'barang_id' => $barang_id,
            'warna_id' => $warna_id ,
            'harga_beli' => $harga_beli,
            'pengali_type' => $this->input->post('pengali_type')
            );

        $this->common_model->db_update('nd_pembelian_detail',$data,'id',$pembelian_detail_id);
        // echo '<hr/>';

        foreach ($rekap_qty as $key => $value) {
            $qty_data = explode('??', $value);
            $pembelian_qty_detail_id = $qty_data[2];
            $data_detail[$key] = array(
                'pembelian_detail_id' => $pembelian_detail_id ,
                'qty' => $qty_data[0],
                'jumlah_roll' => $qty_data[1] );
            if ($pembelian_qty_detail_id != 0) {
                if ($qty_data[0] == 0) {
                    $this->common_model->db_delete('nd_pembelian_qty_detail','id',$pembelian_qty_detail_id);                
                }else{
                    $this->common_model->db_update('nd_pembelian_qty_detail',$data_detail[$key],'id',$pembelian_qty_detail_id);
                }
            }else{
                $this->common_model->db_insert('nd_pembelian_qty_detail',$data_detail[$key]);
            }
        }
        
        redirect($this->setting_link('transaction/pembelian_list_detail').'/'.$pembelian_id);
            
    }
	

	function pembelian_print(){

		$pembelian_id = $this->input->get('pembelian_id');
		$nama_supplier = '';
		$telepon_supplier = '';
		$nama_gudang = '';
		$no_faktur = '';
		$ockh = '';
		$tanggal = '';
		$nama_toko = '';
		$jatuh_tempo = '';
		$no_nota = '';
		
		$data_pembelian = $this->tr_model->get_data_pembelian($pembelian_id);
		$data_pembelian_detail = $this->tr_model->get_data_pembelian_detail($pembelian_id);

		foreach ($data_pembelian as $row) {
			$nama_supplier = $row->nama_supplier;
			$telepon_supplier = $row->telepon_supplier;
			$no_faktur = $row->no_faktur;
			$tanggal_nota = date('dmy', strtotime($row->tanggal));
			$no_nota = 'FPB'.$tanggal_nota.'-'.$row->no_nota_p;
			$tanggal = is_reverse_date($row->tanggal);
			$jatuh_tempo = is_reverse_date($row->jatuh_tempo);
		}

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$pdf = new FPDF( 'L', 'mm', array(225 ,135 ) );
		
		$pdf->AddPage();
		$pdf->SetMargins(15,0,10);
		$pdf->SetTextColor( 0,0,0 );

		$font_name = 'Arial';
		
		$pdf->SetFont( $font_name, '', 9 );
		//1x3
		$pdf->Cell( 0, 3, 'From Seller :', 0, 1, 'R' );
		$pdf->Cell( 0, 3, $nama_supplier, 0, 1, 'R' );
		$pdf->Cell( 0, 3, ',telp : '.$telepon_supplier, 0, 1, 'R' );
		$pdf->Ln();

		$pdf->Cell( 0, 0, '', 1, 1, 'R' );

		$pdf->SetFont( $font_name, '', 11 );

		//1x5
		$pdf->Cell( 0, 5, strtoupper('Invoice No '.$no_faktur.' / Barang Jadi'), 0, 1, 'C' );
		$pdf->Ln();
		$pdf->Cell( 0, 5, strtoupper('Tanggal Pembelian : '.$tanggal), 0, 1, 'L' );
		$pdf->Cell( 0, 5, strtoupper('Supplier Invoice Number : '.$no_faktur), 0, 1, 'L' );
		$pdf->Ln();

		//1x8
		$pdf->Cell( 10, 8, strtoupper('No'), 1, 0, 'C' );
		$pdf->Cell( 50, 8, strtoupper('Nama Barang'), 1, 0, 'C' );
		$pdf->Cell( 20, 8, strtoupper('Satuan'), 1, 0, 'C' );
		$pdf->Cell( 25, 8, strtoupper('Jumlah'), 1, 0, 'C' );
		$pdf->Cell( 20, 8, strtoupper('Roll'), 1, 0, 'C' );
		$pdf->Cell( 35, 8, strtoupper('Harga/Yard'), 1, 0, 'C' );
		$pdf->Cell( 40, 8, strtoupper('Total'), 1, 1, 'C' );

		$baris = 16;
		$i = 1; $g_total = 0;
		foreach ($data_pembelian_detail as $row) {
			//1x7
			$pdf->Cell( 10, 7, $i, 1, 0, 'C' );
			$pdf->Cell( 50, 7, strtoupper($row->nama_barang), 1, 0, 'C' );
			$pdf->Cell( 20, 7, strtoupper($row->nama_satuan), 1, 0, 'C' );
			$pdf->Cell( 25, 7, $row->qty, 1, 0, 'C' );
			$pdf->Cell( 20, 7, $row->jumlah_roll, 1, 0, 'C' );
			$pdf->Cell( 35, 7, 'Rp'.number_format($row->harga_beli,'2',',','.'), 1, 0, 'C' );
			$pdf->Cell( 40, 7, 'Rp'.number_format($row->harga_beli*$row->qty,'2',',','.'), 1, 1, 'R' );
			$g_total += $row->harga_beli*$row->qty; 
			$i++;
			$baris += 7;
		}
		// $pdf->Cell( 10, 5, '1', 1, 0, 'C' );
		// $pdf->Cell( 40, 5, '9992 WR/CIRE Jade', 1, 0, 'C' );
		// $pdf->Cell( 20, 5, 'yard', 1, 0, 'C' );
		// $pdf->Cell( 20, 5, '2229', 1, 0, 'C' );
		// $pdf->Cell( 20, 5, '24', 1, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Rp10.500,00', 1, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Rp23.404,500,00', 1, 1, 'R' );

		// $pdf->Cell( 110, 5, '', 0, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Subtotal', 1, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Rp23.404,500,00', 1, 1, 'R' );

		//1x7
		$pdf->Cell( 125, 7, '', 0, 0, 'C' );
		$pdf->Cell( 35, 7, 'Total', 1, 0, 'C' );
		$pdf->Cell( 40, 7, 'Rp'.number_format($g_total,'2',',','.'), 1, 1, 'R' );

		// $pdf->Ln(10);

		$pdf->SetFont( $font_name, '', 9 );
		//Sisain 28mm
		//1x4
		$pdf->Cell( 0, 4, is_number_write($g_total), 0, 1, 'L' );
		$pdf->Cell( 0, 4, "Jatuh Tempo : ".$jatuh_tempo, 0, 1, 'L' );


		$pdf->Ln(5);

		$baris += 26;

		//===========================================================
		$sisa = 80 - $baris - 24;
		if ( $sisa > 0) {
			$pdf->Ln($sisa);
		}
		//1x4
		$pdf->Cell( 25, 4, 'Kepala Gudang', 1, 0, 'C' );
		$pdf->Cell( 25, 4, 'Pengirim', 1, 0, 'C' );
		$pdf->Cell( 25, 4, 'Penerima', 1, 1, 'C' );

		//1x20
		$pdf->Cell( 25, 20, '', 1, 0, 'C' );
		$pdf->Cell( 25, 20, '', 1, 0, 'C' );
		$pdf->Cell( 25, 20, '', 1, 0, 'C' );

		//=============================================================

		$pdf->SetAutoPageBreak(false);
		// $pdf->AddPage();

		$pdf->Output( 'testing', "I" );
		// echo $sisa;
		
	}


//===================================penjualan=============================================
	function penjualan_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

        $status_aktif = 1;
        // if ($this->input->get('status_aktif')) {
        //     $status_aktif = $this->input->get('status_aktif');
        // }

		$tanggal_start = date('Y-m-d', strtotime('-3 months'));
		$tanggal_end = date('Y-m-d');

		$data = array(
			'content' =>'admin/transaction/penjualan_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Penjualan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
            'status_aktif' => $status_aktif,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		// $data['penjualan_list'] = $this->common_model->db_select('nd_penjualan order by tanggal desc'); 
		$data['penjualan_list'] = array();
		if (is_posisi_id() != 1) {
			$this->load->view('admin/template',$data);
			# code...
		}else{
			// $data['content'] = 'admin/transaction/penjualan_list_test';
			$this->load->view('admin/template',$data);

		}
	}

	function data_penjualan(){

        $aColumns = array('status_aktif', 'nf', 'no_faktur','tanggal','penjualan_type_id','g_total','diskon', 'ongkos_kirim','nama_customer','keterangan', 'data', 'status');
        
        $sIndexColumn = "id";
        
        // paging
        $sLimit = "";

		$tanggal_start = date('Y-m-d', strtotime('-3 months'));
		$tanggal_end = date('Y-m-d');

		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}


        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".$this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] ).", ".
                $this->mysqli_conn->real_escape_string( $_GET['iDisplayLength'] );
        }
        $numbering = $this->mysqli_conn->real_escape_string( $_GET['iDisplayStart'] );
        $page = 1;
        
        // ordering
        if ( isset( $_GET['iSortCol_0'] ) ){
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                        ".$this->mysqli_conn->real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ){
                $sOrder = "";
            }
        }

        // filtering
        $sWhere = "";
        if ( $_GET['sSearch'] != "" ){
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        // individual column filtering
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
                if ( $sWhere == "" ){
                    $sWhere = "WHERE ";
                }
                else{
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".$this->mysqli_conn->real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

        $cond_limit_user = '';
        // if (is_posisi_id() > 3) {
        //     $cond_limit_user =" AND tanggal = '".date('Y-m-d')."'";
        // }

        if ($this->input->get('status_aktif')) {
            $status_aktif = $this->input->get('status_aktif');
            if ($status_aktif != 2) {
                if ( $sWhere == "" ){
                    $sWhere = "WHERE status_aktif =".$status_aktif.$cond_limit_user;
                }
                else{
                    $sWhere .= " AND status_aktif =".$status_aktif.$cond_limit_user;
                }
            }else{
                if ( $sWhere == "" ){
                    $sWhere = "WHERE no_faktur is null AND status_aktif = 1";
                }
                else{
                    $sWhere .= " AND no_faktur is null AND status_aktif = 1";
                }
            }
        }
        $rResult = $this->tr_model->get_penjualan_list_ajax($aColumns, $sWhere, $sOrder, $sLimit, $tanggal_start, $tanggal_end);        
        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_penjualan ');
        $Filternya = $this->tr_model->get_penjualan_list_ajax($aColumns, $sWhere, $sOrder, '', $tanggal_start, $tanggal_end);
        $iFilteredTotal = $Filternya->num_rows();
        // $iTotal = $rResultTotal;
        // $iFilteredTotal = $iTotal;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResultTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
			"range" => $this->input->get('tanggal_start').' s/d '.$this->input->get('tanggal_end'),
			"range2" => $tanggal_start.' s/d '.$tanggal_end,
        );
        
        foreach ($rResult->result_array() as $aRow){
            $y = 0;
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $row[] = $aRow[ $aColumns[$i] ];
            }
            $y++;
            $page++;
            $output['aaData'][] = $row;
        }
        
        echo json_encode( $output );
    }

	function penjualan_list_insert(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tahun = date('Y', strtotime($tanggal));
		$no_faktur = 1;
		$ppn = 10;
        $ini = $this->input;
		$penjualan_type_id = $ini->post('penjualan_type_id');
        $customer_id = ($penjualan_type_id != 3 ? $ini->post('customer_id') : 0) ;
        $data_get = $this->common_model->db_select("nd_penjualan where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
		foreach ($data_get as $row) {
			$no_faktur = $row->no_faktur + 1;
		}
		
		$ppn_get = $this->common_model->db_select("nd_ppn WHERE tanggal = ( SELECT max(tanggal) FROM nd_ppn WHERE tanggal <= '$tanggal' ) ");
		foreach ($ppn_get as $row) {
			$ppn = $row->ppn;
		}

        $jt = is_date_formatter($ini->post('jatuh_tempo'));

        if ($penjualan_type_id == 2) {
    		$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
    		$jt = date('Y-m-d', $dt);
        }

        // echo $jt;
        $data = array(
			// 'toko_id' => 1,
			'penjualan_type_id' => $ini->post('penjualan_type_id') ,
			'tanggal' => $tanggal,
			'ppn' => $ppn,
			// 'no_faktur' => $no_faktur,
			'customer_id' => $customer_id,
			'closed_by' => 0,
			'po_number' => $ini->post('po_number'),
			'jatuh_tempo' => $jt,
			'user_id' => is_user_id(), 
			'nama_keterangan' => $ini->post('nama_keterangan') ,
			'alamat_keterangan' => $ini->post('alamat_keterangan'),
            'keterangan' => ($ini->post('keterangan') == '' ? null : $ini->post('keterangan')),
			'fp_status' => $ini->post('fp_status')
			);

		// print_r($data);

		$result_id = $this->common_model->db_insert('nd_penjualan',$data);
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$result_id);

	}

	function penjualan_list_update(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));

        $ini = $this->input;
		$id = $ini->post('id');
        $customer_id = $ini->post('customer_id');
        $alamat = $ini->post('alamat_keterangan');
        $cek = $this->common_model->db_select("nd_penjualan where id=".$id);
        $penjualan_type_id = $ini->post('penjualan_type_id');
        foreach ($cek as $row) {
            if($customer_id != $row->customer_id && $penjualan_type_id != 3){
                $get_new_address = $this->common_model->db_select('nd_customer where id='.$customer_id);
                foreach ($get_new_address as $row) {
                    $alamat = $row->alamat;
                }
            }

        }

        if ($penjualan_type_id == 3) {
            $customer_id = null;
        }
		$ppn = 10;
		$ppn_get = $this->common_model->db_select("nd_ppn WHERE tanggal = ( SELECT max(tanggal) FROM nd_ppn WHERE tanggal <= '$tanggal' ) ");
		foreach ($ppn_get as $row) {
			$ppn = $row->ppn;
		}
        $data = array(
			'penjualan_type_id' => $ini->post('penjualan_type_id') ,
			'tanggal' => $tanggal,
			'ppn' => $ppn,
			'customer_id' => $customer_id ,
			'po_number' => $ini->post('po_number'),
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')),
			'nama_keterangan' => $ini->post('nama_keterangan'),
			'alamat_keterangan' => $alamat,
            'keterangan' => ($ini->post('keterangan') == '' ? null : $ini->post('keterangan')),
			'fp_status' => $ini->post('fp_status'),
			'user_id' => is_user_id() );

		// print_r($data);

		$result_id = $this->common_model->db_update('nd_penjualan',$data,'id',$id);
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$id);

	}

    function add_testing_data(){
        for ($i=57; $i <= 66 ; $i++) { 
            $data[$i] = array('penjualan_detail_id' => $i ,
                        'qty' => 50+$i,
                        'jumlah_roll' => 1 );
        }

        $this->common_model->db_insert_batch('nd_penjualan_qty_detail', $data);
        echo 'inserted';
    }
	
//==========================penjualan_detail=================================

	function penjualan_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		// $id = $this->uri->segment(2);
		$id = $this->input->get('id');
		$is_toko = [];
		


		$data = array(
			'content' =>'admin/transaction/penjualan_list_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Formulir Penjualan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'is_toko' => $is_toko,
			'data_isi'=> $this->data,
			'penjualan_type' => $this->common_model->db_select('nd_penjualan_type'),
			'pembayaran_type' => $this->common_model->db_select('nd_pembayaran_type'),
			'printer_list' => $this->common_model->db_select('nd_printer_list')
			);

		$data['dp_list_detail'] = array(); 
		$data['penjualan_data'] = array();
		$data['penjualan_detail'] = array();
		$data['total_jual'] = 0;
		$data['pembayaran_penjualan'] = array();
		$data['saldo_awal'] = 0;
		$data['data_giro'] = array();
		$data['penjualan_print'] = array();
		$data['data_toko'] = array();
		$data['penjualan_invoice'] = [];


		if ($id != '') {
			$penjualan_data = $this->tr_model->get_data_penjualan($id);
			$data['penjualan_invoice'] = $this->common_model->db_select("nd_penjualan_invoice where penjualan_id = $id");
			$data['penjualan_data'] = $penjualan_data;
			foreach ($penjualan_data as $row) {
				$penjualan_type_id = $row->penjualan_type_id;
				$customer_id = $row->customer_id;
				$toko_id = $row->toko_id;
                $penjualan_id = $row->id;
                $tanggal = $row->tanggal;
                $no_faktur = $row->no_faktur_raw;
			}
			$data['penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($id);
			$total_jual = 0;
			foreach($this->toko_list_aktif as $row){
				$is_toko[$row->id] = array(
					"color"=> $row->color_code,
					"nama" => $row->nama,
					"item" => 0
				);
			}
			foreach ($data['penjualan_detail'] as $row) {
				$is_toko[$row->toko_id]["item"]++;
				$total_jual += $row->qty * $row->harga_jual;
			}
			$data['total_jual'] = $total_jual;
			$result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$id);
			foreach ($result as $row) {
				$data['pembayaran_penjualan'][$row->pembayaran_type_id] = $row->amount; 
				$data['pembayaran_keterangan'][$row->pembayaran_type_id] = $row->keterangan;
			}

			$data['data_giro'] = $this->common_model->db_select('nd_pembayaran_penjualan_giro where penjualan_id='.$id);

			$data['saldo_awal'] = 0;
			if ($penjualan_type_id != 3) {
				$result = $this->tr_model->get_dp_awal($customer_id, date('Y-m-d'));
				foreach ($result as $row) {
					$data['saldo_awal'] = $row->saldo;
				}
			}

            if ($customer_id != '') {
                $data['dp_list_detail'] = $this->tr_model->get_dp_berlaku($customer_id, $id); 
            }else{
                $data['dp_list_detail'] = array(); 
            }
			$data['penjualan_print'] = $this->tr_model->get_data_penjualan_detail_group($id);
			$data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($id);
			$data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($id);
			$data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);

            $data['next_nota'] = array();
            if ($no_faktur != '') {
                $data['next_nota']  = $this->common_model->get_next_faktur($no_faktur, $tanggal);
            }
			$data['is_toko'] = $is_toko;

		}

		$tipe = '';
		if ($this->input->get("tipe") != '') {
			$tipe = $this->input->get("tipe");
		}
		if (is_posisi_id() == 1) {
			if ($tipe == '') {
				$data['content'] = 'admin/transaction/penjualan_list_detail_2';
			}else{
				$data['content'] = 'admin/transaction/penjualan_pos';
			}
		}

		if ($tipe == '') {
			$data['content'] = 'admin/transaction/penjualan_list_detail_2';
			$this->load->view('admin/template',$data);
		}else{
			$data['content'] = 'admin/transaction/penjualan_pos';
			// $this->load->view('admin/template_no_sidebar',$data);
			$this->load->view('admin/transaction/penjualan_pos',$data);
		}
		
	}

	function cek_history_harga(){
		$customer_id = $this->input->post('customer_id');
		$cond = '';
		if ($customer_id != '') {
			$cond = ' AND customer_id ='.$customer_id;
		}
		$barang_id = $this->input->post('barang_id');

		$result = $this->tr_model->cek_harga_jual($barang_id,$cond);
		echo json_encode($result);

	}

	function get_latest_harga(){
		$customer_id = $this->input->post('customer_id');
		$barang_id = $this->input->post('barang_id');
		$cond = ' AND customer_id ='.$customer_id;

		$harga = 0;
		$result = $this->tr_model->get_lastest_harga($barang_id, $cond);
		foreach ($result as $row) {
			$harga = $row->harga_jual;
		}
		echo $harga;
	}

	function get_latest_harga_non_customer(){
		$customer_id = $this->input->post('customer_id');
		$barang_id = $this->input->post('barang_id');
		$cond = ' AND customer_id ='.$customer_id;

		$harga = 0;
		$result = $this->tr_model->get_lastest_harga_non_customer($barang_id, $cond);
		foreach ($result as $row) {
			$harga = $row->harga_jual;
			$id = $row->id;
		}
		echo $harga;
	}

	function penjualan_list_detail_insert(){
		// print_r($this->input->post());
		$ini = $this->input;
		$penjualan_id =  $ini->post('penjualan_id');
		$is_eceran = $this->input->post('is_eceran');
		$is_eceran = ($is_eceran == 'on' ? 1 : 0);
		$is_eceran_mix = $ini->post('is_eceran_mix');

		$use_ppn = $ini->post('use_ppn');
		$barang_id = $ini->post('barang_id');
		$warna_id = $ini->post('warna_id');
		$gudang_id = $ini->post('gudang_id');
		$toko_id = $ini->post('toko_id');
		$subdiskon = $ini->post('subdiskon');

		$subqty = $ini->post('subqty');
		$subroll = $ini->post('subroll');
		
		$subtotal_nilai = str_replace(".","", $ini->post('subtotal_nilai'));
		$harga_jual_noppn = str_replace(",","", $ini->post('harga_jual_noppn'));
		$ppn_berlaku = $ini->post('ppn_berlaku');
		$subtotal_ppn = 0;
		if ($use_ppn == 1) {
			$subtotal_ppn = $harga_jual_noppn * $subtqty;
		}else{
			$harga_jual_noppn = 0;
			$subtotal_ppn = 0;
		}
		$data = array(
			'penjualan_id' => $penjualan_id,
			'gudang_id' => $gudang_id,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
            'pengali_harga' => $this->input->post('pengali_harga'),
			'is_eceran' => $is_eceran,
			'is_eceran_mix' => $is_eceran_mix,
			'toko_id' => $toko_id,
			'use_ppn' => $use_ppn,
			'ppn_berlaku' => $ppn_berlaku,
			'subqty' => $subqty,
			'subroll' => $subroll,
			'subtotal_nilai' => $subtotal_nilai,
			'subtotal_ppn' => $subtotal_ppn,
			'subdiskon' => ($subdiskon == '' ? 0 : str_replace(',', '', $subdiskon)),
			'harga_dpp' => $harga_jual_noppn,
			'harga_jual' => str_replace(',', '', $ini->post('harga_jual')) );

			// if (is_posisi_id()==1) {
			// 	print_r($data);
			// 	print_r($ini->post());
			// 	# code...
			// }
		$result_id = $this->common_model->db_insert('nd_penjualan_detail', $data);
		$i = 0;
		$qty_list = explode('--', $ini->post('rekap_qty'));
		$new_mutasi_id = '';
		

		foreach ($qty_list as $key => $value) {
			$qty = explode('??', $value);
			if (!$is_eceran) {
				$data_qty[$i] = array(
					'penjualan_detail_id' => $result_id,
					'qty' => $qty[0],
					'jumlah_roll' => $qty[1],
					'supplier_id' => $qty[3]
				);
			}else{
				//index 1 stok_qty_eceran_id klo 0 ya berarti baru mutasi
				if($qty[1] != 0){
					$data_qty[$i] = array(
						'penjualan_detail_id' => $result_id,
						'qty' => $qty[0],
						'jumlah_roll' => 1,
						'stok_eceran_qty_id' => $qty[1],
						'supplier_id' => $qty[3],
						'eceran_source' => $qty[4]
					);
				}else{
					if ($new_mutasi_id  == '') {
						$data_new = array(
							'tanggal' => date('Y-m-d'),
							'toko_id' => $toko_id ,
							'barang_id' => $barang_id ,
							'warna_id' => $warna_id ,
							'gudang_id' => $gudang_id ,
							'tipe' => 2,
							'user_id' => is_user_id(),
						);
				
						$new_mutasi_id = $this->common_model->db_insert('nd_mutasi_stok_eceran', $data_new);
					}

					$data_new_mutasi = array(
						'mutasi_stok_eceran_id' => $new_mutasi_id ,
						'qty' => $qty[2],
						'jumlah_roll' => 1,
						'supplier_id' => $qty[3]
					);

					$new_id = $this->common_model->db_insert("nd_mutasi_stok_eceran_qty",$data_new_mutasi);

					
					$data_qty[$i] = array(
						'penjualan_detail_id' => $result_id,
						'qty' => $qty[0],
						'jumlah_roll' => 1,
						'supplier_id' => $qty[3],
						'stok_eceran_qty_id' => $new_id,
						'eceran_source' => 1
					);

				}
			}
			$i++;
		}

		if (isset($data_qty)) {
			$this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_qty);
		}
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);
	}

	function penjualan_list_detail_remove(){
		$id = $this->input->post('id');
		$this->common_model->db_delete('nd_penjualan_detail','id',$id);
		$this->common_model->db_delete('nd_penjualan_qty_detail','penjualan_detail_id',$id);
		echo 'OK';

	}

	function update_penjualan_detail_harga(){
		$id = $this->input->post('id');
		$harga_jual = $this->input->post('harga_jual');
		$harga_jual = str_replace('.', '', $harga_jual);
		$data = array(
			'harga_jual' => $this->input->post('harga_jual') );
		$this->common_model->db_update('nd_penjualan_detail',$data,'id',$id);
		// print_r($this->input->post());
		echo 'OK';

	}

	function penjualan_qty_detail_update(){

		// print_r($this->input->post());
		$ini= $this->input;
		
        $penjualan_detail_id = $ini->post('penjualan_list_detail_id');
		$penjualan_id = $ini->post('penjualan_id');
		$rekap_qty = $ini->post('rekap_qty');
		$is_eceran = $ini->post('is_eceran');
		$qty_list = explode('--', $rekap_qty);
		$use_ppn = $ini->post('use_ppn');


        $harga_jual = $ini->post('harga_jual');
        $harga_jual = str_replace('.', '', $harga_jual);
        $harga_jual = str_replace(',', '.', $harga_jual);
		
        $subdiskon = $ini->post('subdiskon');
        $subdiskon = str_replace('.', '', $subdiskon);
        $subdiskon = str_replace(',', '.', $subdiskon);

		$subqty = $ini->post('subqty');
		$subroll = $ini->post('subroll');

		$subtotal_nilai = str_replace(".","", $ini->post('subtotal_nilai'));
		$subtotal_nilai = str_replace(",",".", $ini->post('subtotal_nilai'));
		
		$harga_jual_noppn = str_replace(".","", $ini->post('harga_jual_noppn'));
		$harga_jual_noppn = str_replace(",",".", $ini->post('harga_jual_noppn'));

		if ($use_ppn == 1) {
			$subtotal_ppn = $harga_jual_noppn * $subtqty;
		}else{
			$harga_jual_noppn = 0;
			$subtotal_ppn = 0;
		}

        $dt_harga = array(
            'harga_jual' => $harga_jual,
			'subqty' => $subqty,
			'subroll' => $subroll,
			'subtotal_nilai' => $subtotal_nilai,
			'subtotal_ppn' => $subtotal_ppn,
			'subdiskon' => ($subdiskon == '' ? 0 : str_replace(',', '', $subdiskon)),
			'harga_dpp' => $harga_jual_noppn

		);

		if (is_posisi_id() == 1) {
			# code...
			// print_r($dt_harga);
		}

		

        $this->common_model->db_update('nd_penjualan_detail',$dt_harga,'id', $penjualan_detail_id);
		$i = 0;
		$data_qty = array();
		foreach ($qty_list as $key => $value) {
			$qty = explode('??', $value);
			$urai = explode('??', $value);
			unset($data_up);
			if (!$is_eceran) {
				$penjualan_qty_detail_id = $urai[2];
				$data = array(
					'penjualan_detail_id' => $penjualan_detail_id,
					'qty' => $urai[0] ,
					'jumlah_roll' => $urai[1],
					'supplier_id' => $urai[3],
				);
				
				if ($penjualan_qty_detail_id != 0) {
					if ($urai[0] == 0) {
						$this->common_model->db_delete('nd_penjualan_qty_detail', 'id', $penjualan_qty_detail_id);
					}else{
						$this->common_model->db_update('nd_penjualan_qty_detail', $data,'id', $penjualan_qty_detail_id);
					}
				}else{
					$this->common_model->db_insert('nd_penjualan_qty_detail', $data);
				}
			}else{
				//index 1 stok_qty_eceran_id klo 0 ya berarti baru mutasi
				if($qty[1] != 0){
					if($qty[0] != 0){
						$data_up = array(
							'penjualan_detail_id' => $penjualan_detail_id,
							'qty' => $qty[0],
							'jumlah_roll' => 1,
							'stok_eceran_qty_id' => $qty[1],
							'eceran_source' => $qty[3]
						);
					}

					if ($qty[0] == 0 && $qty[4] != 0) {
						// echo '1';
						$this->common_model->db_delete("nd_penjualan_qty_detail", "id", $qty[4]);
					}else if($qty[4] != 0 ){
						// echo '<br/>2';
						$this->common_model->db_update("nd_penjualan_qty_detail", $data_up, "id", $qty[4]);
					}elseif($qty[0] != 0){
						// echo '<br/>3';
						// $data_qty[$i] = $data_up;
						array_push($data_qty, $data_up);
					}
					
				}else{
					if ($new_mutasi_id  == '') {
						$new_mutasi_id = $this->common_model->db_insert('nd_mutasi_stok_eceran', $data_new);
					}

					$data_new_mutasi = array(
						'mutasi_stok_eceran_id' => $new_mutasi_id,
						'qty' => $qty[2],
						'jumlah_roll' => 1
					);

					$new_id = $this->common_model->db_insert("nd_mutasi_stok_eceran_qty",$data_new_mutasi);

					
					$data_up = array(
						'penjualan_detail_id' => $result_id,
						'qty' => $qty[0],
						'jumlah_roll' => 1,
						'stok_eceran_qty_id' => $new_id,
						'eceran_source' => 1
					);

					array_push($data_qty, $data_up);


				}
			}
			$i++;
		}


		if (count($data_qty)) {
			$this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_qty);
		}

        redirect($this->setting_link('transaction/penjualan_list_detail').'?id='.$penjualan_id);        

		// $this->common_model->db_delete('nd_penjualan_qty_detail','penjualan_detail_id',$penjualan_detail_id);
		// $this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_qty);
		// // print_r($this->input->post());
		// echo 'OK';
	}

	function pembayaran_penjualan_update(){
		$penjualan_id = $this->input->post('penjualan_id');
		$pembayaran_type_id = $this->input->post('pembayaran_type_id');
		$data = array(
			'penjualan_id' => $penjualan_id,
			'pembayaran_type_id' => $pembayaran_type_id,
			'amount' => ($this->input->post('amount') != '' ? str_replace('.', '', $this->input->post('amount')) : 0) );
		
		$result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$penjualan_id." AND pembayaran_type_id=".$pembayaran_type_id);
		$id = '';
		foreach ($result as $row) {
			$id = $row->id;
		}
		if ($id == '') {
			$this->common_model->db_insert('nd_pembayaran_penjualan', $data);
		}else{
			$this->common_model->db_update('nd_pembayaran_penjualan', $data,'id', $id);
		}

		echo 'OK';
	}

    function pembayaran_penjualan_dp_update(){
        $penjualan_id = $this->input->post('penjualan_id');
        $post = (array)$this->input->post();
        $idx = 0;
        foreach ($post as $key => $value) {
            if (strpos($key, 'amount_') !== false) {
                // echo $key.'-->'.$value.'<br/>';
                $data_get = explode('_', $key);
                $dp_masuk_id[$idx] = $data_get[1];
                $isi[$idx] = str_replace('.', '', $value);
                $idx++;
            }            
        }

        foreach ($dp_masuk_id as $key => $value) {
            $data = array(
                'penjualan_id' => $penjualan_id ,
                'pembayaran_type_id' => 1,
                'dp_masuk_id' => $dp_masuk_id[$key],
                'amount' => $isi[$key],
                );

            $id = '';
            $get_id = $this->common_model->db_select("nd_pembayaran_penjualan where penjualan_id =".$penjualan_id." AND pembayaran_type_id = 1 AND dp_masuk_id =".$value);
            foreach ($get_id as $row) {
                $id = $row->id;
            }
            if ($id == '') {
                if ($isi[$key] != 0) {
                    $this->common_model->db_insert('nd_pembayaran_penjualan', $data);
                }
            }else{
                $this->common_model->db_update('nd_pembayaran_penjualan',$data, 'id', $id);
            }

        }

        redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);
    }

	function penjualan_data_update(){
		$penjualan_id = $this->input->post('penjualan_id');
		$data = array(
			$this->input->post('column') => $this->input->post('value') );
		$this->common_model->db_update('nd_penjualan', $data,'id', $penjualan_id);
		echo 'OK';
	}

	function penjualan_list_batal(){
		$id = $this->input->get('id');
		$data = array(
			'status_aktif' => -1,
			'closed_by' => is_user_id(),
			'closed_date' => date('Y-m-d H:i:s') );
		
		// print_r($id);
		$this->common_model->db_update('nd_penjualan',$data,'id',$id);
		redirect($this->setting_link('transaction/penjualan_list'));
	}

	function penjualan_list_undo_batal(){
		$id = $this->input->get('id');
		echo $id;
		$data = array(
			'status_aktif' => 1,
			'closed_by' => is_user_id(),
			'closed_date' => date('Y-m-d H:i:s') );
		$this->common_model->db_update('nd_penjualan',$data,'id',$id);
		redirect($this->setting_link('transaction/penjualan_list'));
	}

	function get_search_no_faktur_jual(){
		$no_faktur = $this->input->post('no_faktur');
		$result = $this->tr_model->search_faktur_jual($no_faktur);
		echo json_encode($result);
	}

	function penjualan_list_close()
	{
		$id = $this->input->get('id');
		
		// total toko adalah total item tiap toko (cth 1,1 artinya toko 1 ada 1 item dan toko 2 ada 1 item)
		$total_toko = explode("," ,$this->input->get('total_toko'));
		// list toko adalah list id toko yang ada dikaitkan dengan total toko
		$list_toko = explode("," ,$this->input->get('list_toko'));
		$no_faktur = 1;
		$tanggal = $this->input->get('tanggal');
        $tahun = date('Y', strtotime($tanggal));
		$bulan = date('m', strtotime($tanggal));
		$revisi = 1;


		$get = $this->common_model->db_select("nd_penjualan where id=".$id);
		foreach ($get as $row) {
			$no_faktur = $row->no_faktur;
			$revisi = $row->revisi+1;
		}

		$getInvoice = $this->common_model->db_select("nd_penjualan_invoice where penjualan_id=".$id);
		$listNoInvoice = [];
		foreach ($getInvoice as $row) {
			$listNoInvoice[$row->toko_id] = array(
				"no" => $row->no_faktur,
				"id" => $row->id
			);
		}

		$kode_toko = [];
		foreach ($this->toko_list_aktif as $row) {
			$kode_toko[$row->id] = $row->kode_toko;
		}

		$get_romani = $this->common_model->db_select("romani where id='".(int)$bulan."'");
		foreach ($get_romani as $row) {
			$romani = $row->romani;
		}

		/* echo $no_faktur;
		echo '<br/> total toko';
		print_r($total_toko);
		echo '<br/> invoice';
		print_r($listNoInvoice);
		echo '<br/>';

		foreach ($total_toko as $key => $value) {
			$toko_id_cek = $list_toko[$key];
			echo $toko_id_cek.'--'.$key.'-->'.$value.'<br/>';
			// kalo ada item di toko tersebut 

			if ($value > 0) {
				if(!isset($listNoInvoice[$toko_id_cek])){
					echo 'insert<br/>';
				}else{
					echo 'update<br/>';
				}
			}else if(isset($listNoInvoice[$toko_id_cek])){
				echo 'update2';
			}
		} */


		if ($no_faktur == '') {

			if ($tanggal <= '2024-08-31') {
				$data_get = $this->common_model->db_select("nd_penjualan where YEAR(tanggal)='".$tahun."' AND tanggal <= '2024-08-31' order by no_faktur desc limit 1 ");
				foreach ($data_get as $row) {
					$no_faktur = $row->no_faktur + 1;
				}
				if ($no_faktur == '') {
					$no_faktur = 1;
				}
	
				$no_faktur_lengkap = $tahun.'/CVSUN/INV/'.str_pad($no_faktur,4,"0",STR_PAD_LEFT);
			}else{
				$no_faktur = 1;
				$data_get = $this->common_model->db_select("nd_penjualan where YEAR(tanggal)='".$tahun."' AND MONTH(tanggal)='".$bulan."' order by no_faktur desc limit 1 ");
				foreach ($data_get as $row) {
					$no_faktur = $row->no_faktur + 1;
				}
				
				$no_faktur_lengkap = $tahun.'/'.$romani.'/'.str_pad($no_faktur,4,"0",STR_PAD_LEFT);
			}

			// echo $id;
			$data = array(
				'closed_by' => is_user_id() ,
				'no_faktur' => $no_faktur,
				'no_faktur_lengkap' => $no_faktur_lengkap,
				'closed_date' => date('Y-m-d H:i:s'),
				'revisi' => $revisi,
				'status' => 0 );
			// print_r($data);
		}else{
			$data = array(
				'closed_by' => is_user_id() ,
				'closed_date' => date('Y-m-d H:i:s'),
				'revisi' => $revisi,
				'status' => 0 );
		}

		$data_new_invoice = [];
		foreach ($total_toko as $key => $value) {
			$toko_id_cek = $list_toko[$key];
			if ($value > 0) {
				if(!isset($listNoInvoice[$toko_id_cek])){
					$get_latest_invoice = $this->common_model->db_select("nd_penjualan_invoice where toko_id = ".$toko_id_cek." and YEAR(tanggal)='".$tahun."' and MONTH(tanggal)='".$bulan."' and status_aktif = 1 order by no_faktur desc limit 1");
					$no_faktur_toko = 1;
					foreach ($get_latest_invoice as $row) {
						$no_faktur_toko = $row->no_faktur + 1;
					}

					$no_faktur_lengkap_toko = "INV/".$tahun."/".$romani."/".$kode_toko[$toko_id_cek]."/".str_pad($no_faktur_toko,5,"0",STR_PAD_LEFT);

					array_push($data_new_invoice, array(
						'penjualan_id' => $id,
						'toko_id' => $toko_id_cek,
						'no_faktur' => $no_faktur_toko,
						'no_faktur_lengkap' => $no_faktur_lengkap_toko,
						'tanggal' => $tanggal
					));
				}else{
					$nData = array(
						'tanggal' => $tanggal 
					);
					$this->common_model->db_update('nd_penjualan_invoice',$nData,'id',$listNoInvoice[$toko_id_cek]['id']);		
				}
			}else if(isset($listNoInvoice[$toko_id_cek])){
				$nData = array(
						'tanggal' => $tanggal ,
						'status_aktif' => -1
				);
				$this->common_model->db_update('nd_penjualan_invoice',$nData,'id',$listNoInvoice[$toko_id_cek]['id']);
			}
		}

		// print_r($data_new_invoice);

		if (count($data_new_invoice) > 0) {
			$this->common_model->db_insert_batch('nd_penjualan_invoice', $data_new_invoice);
		}
		$this->common_model->db_update('nd_penjualan',$data,'id',$id);
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$id); 
	}

	function penjualan_request_open(){
		$penjualan_id = $this->input->post('penjualan_id');
		$data = array(
			'status' => 1 );
		$this->common_model->db_update('nd_penjualan',$data,'id',$penjualan_id);
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);

	}

	function get_qty_stock_by_barang(){
		$gudang_id = $this->input->post('gudang_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
		$toko_id = $this->input->post('toko_id');
        // $get_stok_opname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' ORDER BY tanggal desc LIMIT 1");
        $get_stok_opname = $this->common_model->get_latest_so($tanggal, $barang_id, $warna_id, $gudang_id, $toko_id);
		$tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        foreach ($get_stok_opname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }
		$result = $this->tr_model->get_qty_stok_by_barang($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $toko_id);
		// $result = $this->tr_model->get_qty_stok_by_barang_2($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id);
		
        // print_r($this->input->post());
		echo json_encode($result->result());
		
		// print_r($result);
	}

    function get_qty_stock_by_barang_detail(){
        $gudang_id = $this->input->post('gudang_id');
        $barang_id = $this->input->post('barang_id');
        $warna_id = $this->input->post('warna_id');
		$toko_id = $this->input->post('toko_id');
        $isEceran = $this->input->post('is_eceran');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        // $get_stok_opname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' ORDER BY tanggal desc LIMIT 1");
        $get_stok_opname = $this->common_model->get_latest_so($tanggal, $barang_id, $warna_id, $gudang_id, $toko_id);
		$tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        foreach ($get_stok_opname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->stok_opname_id;
        }

		

		// echo $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id;
        $getStok = $this->tr_model->get_qty_stok_by_barang_detail($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id);
		$get = $getStok['result'];
		$getQuery = $getStok['query'];
        $result[0] = $get->result();

		$res = array(
			'data' => $tanggal, $barang_id, $warna_id, $gudang_id,
			'tanggal_awal' => $tanggal_awal,
			'stok_opname_id' => $stok_opname_id
		);

		$detail_id = $this->input->post('penjualan_list_detail_id');
		$detail_id = ($detail_id=='' ? 0 : $detail_id);
        $get_stok_opname = $this->common_model->get_latest_so_eceran($tanggal, $barang_id, $warna_id, $gudang_id, $toko_id);

		$tanggal_awal_ecer = '2018-01-01';
        $stok_opname_id_ecer = 0;
		foreach ($get_stok_opname as $row) {
            $tanggal_awal_ecer = $row->tanggal;
            $stok_opname_id_ecer = $row->stok_opname_id;
        }

		
		if($isEceran){
			$result[1] = $this->tr_model->get_qty_stok_by_barang_detail_eceran($gudang_id, $barang_id,$warna_id, $tanggal_awal_ecer, $stok_opname_id_ecer, $detail_id);
			$result[1] = $result[1]->result();
		}

		$result[2] = array(
			'tgl'=>$tanggal_awal,
			'sid' =>$stok_opname_id
		);

		$result[3] = array(
			'res'=>$res,
			'var' => $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $detail_id, $tanggal
		);

		$result[4] = array(
			'tgl'=>$tanggal_awal_ecer,
			'sid' =>$stok_opname_id_ecer
		);

		// $result[4] = $getQuery;

		
		
		echo json_encode($result);
		// if (is_posisi_id() == 1) {
		// 	echo $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id;
		// }
        
        // print_r($result);
    }

	function pembayaran_transfer_update(){
		$penjualan_id = $this->input->post('penjualan_id');
		$pembayaran_type_id = 4;

		$cond = array(
			'penjualan_id' => $penjualan_id ,
			'pembayaran_type_id' => 4 );
		$data = array(
			'penjualan_id' => $penjualan_id ,
			'pembayaran_type_id' => 4,
			'keterangan' => $this->input->post('keterangan') );

		$result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$penjualan_id." AND pembayaran_type_id=".$pembayaran_type_id);
		$id = '';
		foreach ($result as $row) {
			$id = $row->id;
		}
		if ($id == '') {
			$this->common_model->db_insert('nd_pembayaran_penjualan', $data);
		}else{
			$this->common_model->db_update('nd_pembayaran_penjualan', $data,'id', $id);
		}

		echo 'OK';

	}

	function penjualan_detail_giro(){
		$ini = $this->input;
		$penjualan_id = $ini->post('penjualan_id');
		$data = array(
			'penjualan_id' => $penjualan_id ,
			'nama_bank' => $ini->post('nama_bank') ,
			'no_rek_bank' => $ini->post('no_rek_bank') ,
			'no_akun' => $ini->post('no_akun') ,
			'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')) ,
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')) );
		$result = $this->common_model->db_select('nd_pembayaran_penjualan_giro where penjualan_id = '.$penjualan_id);
		$id = '';
		foreach ($result_id as $row) {
			$id = $row->id;
		}
		if ($id == '') {
			$this->common_model->db_insert('nd_pembayaran_penjualan_giro', $data);
		}else{
			$this->common_model->db_update('nd_pembayaran_penjualan_giro', $data,'id', $id);
		}
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);

	}

//=======================================print========================================


	function test_print(){

		echo "'\x1B' '\x45' '\x0D', ".// bold on
		"'\x1B' '\x61' '\x30', ".// left align
		"FA. CHEMICAL '\x0A',".
		   	"TAMIM NO. 53 BANDUNG  '\x0A'";
	}

	function penjualan_print(){

		$penjualan_id = $this->input->get('penjualan_id');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		$toko_id = 1;
		
		$data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
		foreach ($data['data_penjualan'] as $row) {
			$toko_id = $row->toko_id;
			$tanggal = $row->tanggal;
		}
		$data['ppn_berlaku'] = get_ppn_berlaku($tanggal);
		
		$data['toko_data'] = $this->common_model->db_select('nd_toko where id='.$toko_id);
		$data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($penjualan_id);
        // $data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
		$data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($penjualan_id);
		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/penjualan_print_2',$data);
		
		
	}

	function penjualan_print_langsung(){

		$this->load->library('blade');

		$this->blade->set('foo', 'bar')
				->set('an_array', array(1, 2, 3, 4))
				->append('an_array', 5)
				->set_data(array('more' => 'data', 'other' => 'data'))
				->render('test', array('message' => 'Hello World!'));

		

		// $this->load->view('admin/transaction/penjualan_print_2',$data);
		
		
	}

	function penjualan_detail_print(){

		$penjualan_id = $this->input->get('penjualan_id');
		
		
		$data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
		$data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
		
		$data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
		$data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($penjualan_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/penjualan_detail_print_3',$data);
		
	}

	function penjualan_print_kombinasi(){

		$penjualan_id = $this->input->get('penjualan_id');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
		$data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
		$data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($penjualan_id);
		$data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
		// $data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($penjualan_id);
		$data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($penjualan_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/penjualan_kombinasi_print_4',$data);
		
	}

	function penjualan_sj_print(){

		$penjualan_id = $this->input->get('penjualan_id');
		$data['harga_status'] = $this->input->get('harga');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
		// $data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($penjualan_id);
		$data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
		$data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
		$data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($penjualan_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/penjualan_sj_print',$data);
		
	}

//===================================Dp=============================================
	function dp_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/transaction/dp_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Uang Muka (DP)',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['dp_list'] = $this->tr_model->get_dp_list(); 
		$this->load->view('admin/template',$data);
	}

	function dp_list_detail(){

		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->uri->segment(2);

		if ($this->input->get('from')) {
			$from = is_date_formatter($this->input->get('from'));
			$to = is_date_formatter($this->input->get('to'));
		}else{
			$from = date("Y-m-01"); 
			$to = date("Y-m-t");
		}

        $dp_masuk_id_group = '';
        if ($this->input->get('dp_masuk_id')) {
            $dp_masuk_id_group = $this->input->get('dp_masuk_id');
        }

		$data = array(
			'content' =>'admin/transaction/dp_list_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Kartu DP Customer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'from' => $from,
			'to' => $to,
			'customer_id' => $customer_id,
            'dp_masuk_id_group' => $dp_masuk_id_group 
            );

		// $data['bayar_dp_list'] = $this->common_model->db_select('nd_bayar_dp'); 
        if ($customer_id != '') {
    		$get = $this->common_model->db_select('nd_customer where id='.$customer_id);
            # code...
        }
		foreach ($get as $row) {
			$data['nama_customer'] = $row->nama;
		}
		$data['pembayaran_type_list'] = $this->common_model->db_select('nd_pembayaran_type where id != 1 AND id != 5');
		if ($dp_masuk_id_group != '') {
            $data['saldo_awal'] = 0;
            $data['dp_list_detail'] = $this->tr_model->get_dp_detail_by_dp($customer_id, $dp_masuk_id_group); 
        }else{
            $data['dp_list_detail'] = $this->tr_model->get_dp_detail($customer_id,$from, $to); 
    		$result = $this->tr_model->get_dp_awal($customer_id, $from);
    		$data['saldo_awal'] = 0;
    		foreach ($result as $row) {
    			$data['saldo_awal'] = $row->saldo;
    		}
            
        }

		$this->load->view('admin/template',$data);
	}

	function dp_masuk_insert(){
        $ini = $this->input;
		$customer_id = $ini->post('customer_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$year = date('Y', strtotime($tanggal));
		$data_get = $this->common_model->db_select("nd_dp_masuk where YEAR(tanggal) ='".$year."' order by no_dp desc limit 1");
		$no_dp = 1;
		foreach ($data_get as $row) {
			$no_dp  = $row->no_dp + 1;
		}
		$data = array(
			'no_dp' => $no_dp,
			'customer_id' => $customer_id ,
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
            'tanggal' => is_date_formatter($this->input->post('tanggal')),
            'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
            'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
            'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
            'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
            'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
            'amount' => str_replace('.', '', $ini->post('amount')),
            'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id(),
            'created' => date('Y-m-d H:i:s') );

		// print_r($data);
		$this->common_model->db_insert('nd_dp_masuk', $data);
		redirect(is_setting_link('transaction/dp_list_detail').'/'.$customer_id);
	}

	function dp_masuk_update(){
        $ini = $this->input;

		$dp_masuk_id = $ini->post('dp_masuk_id');
		$customer_id = $ini->post('customer_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$data = array(
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
            'tanggal' => is_date_formatter($this->input->post('tanggal')),
            'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
            'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
            'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
            'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
            'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
            'amount' => str_replace('.', '', $ini->post('amount')),
            'keterangan' => $ini->post('keterangan'),
            'user_id' => is_user_id(),
			 );

		// print_r($data);
		$this->common_model->db_update('nd_dp_masuk', $data,'id', $dp_masuk_id);
		redirect(is_setting_link('transaction/dp_list_detail').'/'.$customer_id);
	}

	function dp_print(){

		$dp_id = $this->input->get('id');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_dp'] = $this->tr_model->get_data_dp($dp_id);
		
		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/dp_print',$data);
		
		
	}

//===================================History Pembelian=============================================

	function pembelian_input_history(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('from')) {
			$from = is_date_formatter($this->input->get('from'));
			$to = is_date_formatter($this->input->get('to'));
		}else{
			$from = date("Y-m-d"); 
			$to = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/transaction/pembelian_input_history',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Pembelian Input History',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'from' => $from,
			'to' => $to );


		$data['history'] = $this->tr_model->get_pembelian_history($from, $to); 
		
		$this->load->view('admin/template',$data);
	}

	function penjualan_input_history(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('from')) {
			$from = is_date_formatter($this->input->get('from'));
			$to = is_date_formatter($this->input->get('to'));
		}else{
			$from = date("Y-m-d"); 
			$to = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/transaction/penjualan_input_history',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Penjualan Input History',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'from' => $from,
			'to' => $to );


		$data['history'] = $this->tr_model->get_penjualan_history($from, $to); 
		
		$this->load->view('admin/template',$data);
	}

//===================================Penerimaan Pembelian=============================================

	// function penerimaan_harian_penjualan(){
	// 	$menu = is_get_url($this->uri->segment(1)) ;
	// 	if($this->input->get('tanggal')){
	// 		$tanggal = is_date_formatter($this->input->get('tanggal'));
	// 	}else{
	// 		$tanggal = date('Y-m-d');
	// 	}

	// 	$data = array(
	// 		'content' =>'admin/transaction/penerimaan_harian_penjualan',
	// 		'breadcrumb_title' => 'Transaction',
	// 		'breadcrumb_small' => 'Penerimaan Harian Penjualan',
	// 		'tanggal' => $tanggal,
	// 		'nama_menu' => $menu[0],
	// 		'nama_submenu' => $menu[1],
	// 		'common_data'=> $this->data,
	// 		'data_isi'=> $this->data );

	// 	$data['penjualan_list'] = $this->tr_model->get_penjualan_bayar_by_date($tanggal);
	// 	$data['retur_list'] = $this->tr_model->get_retur_jual_by_date($tanggal);
	// 	$data['pembayaran_type'] = $this->common_model->db_select("nd_pembayaran_type");
	// 	$this->load->view('admin/template',$data);

	// }


//============================piutang temp section=================================================

	function piutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/transaction/piutang_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );

		$data['piutang_list'] = $this->tr_model->get_piutang_list_all(); 
		$this->load->view('admin/template',$data);
	}

	function piutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->input->get('customer_id');

		$data = array(
			'content' =>'admin/transaction/piutang_list_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Piutang Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id );

		$data['piutang_list_detail'] = $this->tr_model->get_piutang_list_detail($customer_id); 
		$this->load->view('admin/template',$data);
	}

	function piutang_payment(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date("Y-m-d");
		$tanggal_end = date("Y-m-d");
		$customer_id = '';
		$toko_id = '1';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		if ($this->input->get('customer_id') && $this->input->get('customer_id') != '') {
			$customer_id = $this->input->get('customer_id');
		}

		if ($this->input->get('toko_id') && $this->input->get('toko_id') != '') {
			$toko_id = $this->input->get('toko_id');
		}

		$cond = "WHERE toko_id = ".$toko_id." ";
		if ($customer_id != '') {
			$cond .= "AND customer_id = ".$customer_id;
		}

		$data = array(
			'content' =>'admin/transaction/pembayaran_piutang',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end) );


		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$data['pembayaran_piutang_list'] = $this->tr_model->get_pembayaran_piutang($tanggal_start, $tanggal_end, $cond);
			// echo $data['pembayaran_piutang_list'];
			foreach ($data['pembayaran_piutang_list'] as $row) {

				$periode = $this->tr_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_piutang_awal_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_awal_detail($row->id);
				$data['pembayaran_piutang_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_detail($row->id);
				$data['pembayaran_piutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_piutang_temp_nilai WHERE pembayaran_piutang_id=".$row->id);

			}
		}else{
			$data['pembayaran_piutang_list'] = $this->tr_model->get_pembayaran_piutang_unbalance();
			foreach ($data['pembayaran_piutang_list'] as $row) {

				$periode = $this->tr_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_piutang_awal_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_awal_detail($row->id);
				$data['pembayaran_piutang_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_detail($row->id);
				$data['pembayaran_piutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_piutang_temp_nilai WHERE pembayaran_piutang_id=".$row->id);
			}

			$data['status_view'] = 0;
		}

		$this->load->view('admin/template',$data);
	}

	function piutang_payment_form(){
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('tanggal_start')) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');

		}else{
			$tanggal_start = date("Y-m-01"); 
			$tanggal_end = date("Y-m-t");
			$toko_id = 1;
			$customer_id = '';
		}

		$pembayaran_piutang_id = '';
		if ($this->input->get('id')) {
			$pembayaran_piutang_id = $this->input->get('id');
		}

		$data = array(
			'content' =>'admin/transaction/pembayaran_piutang_form',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Formulir Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'customer_id' => $customer_id );


		if ($pembayaran_piutang_id != '') {
			$data['pembayaran_piutang_data'] = $this->tr_model->get_pembayaran_piutang_data($pembayaran_piutang_id);
			$periode = $this->tr_model->get_periode_penjualan($pembayaran_piutang_id);
			foreach ($periode as $row) {
				$data['tanggal_start'] = $row->tanggal_start;
				$data['tanggal_end'] = $row->tanggal_end;
			}

			foreach ($data['pembayaran_piutang_data'] as $row) {
				$customer_id = $row->customer_id;
			}
			
			$data['pembayaran_piutang_awal_detail'] = $this->tr_model->get_pembayaran_piutang_awal_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_detail'] = $this->tr_model->get_pembayaran_piutang_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_nilai'] = $this->common_model->db_select("nd_pembayaran_piutang_temp_nilai where pembayaran_piutang_id=".$pembayaran_piutang_id);
			$data['bank_history'] = $this->tr_model->get_customer_bank_bayar_history($customer_id);
			
		}elseif ($toko_id != '' && $customer_id != '') {
			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_awal_detail'] = $this->fi_model->get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id); 
			$data['pembayaran_piutang_detail'] = $this->fi_model->get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id); 
			$data['pembayaran_hutang_nilai'] = array();
			$data['bank_history'] = array();
			

		}else{
			$data['pembayaran_piutang_awal_detail'] = array(); 
			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_detail'] = array(); 
			$data['bank_history'] = array();
		}
		$data['printer_list'] = $this->common_model->db_select('nd_printer_list');

		$this->load->view('admin/template',$data);
	}

	function pembayaran_piutang_insert(){
		$ini = $this->input;
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
		// echo $pembayaran_piutang_id;
		

		if ($pembayaran_piutang_id == '') {

			$data = array(
			'customer_id' => $ini->post('customer_id'),
			'toko_id' => $ini->post('toko_id'),
			'user_id' => is_user_id() );

			$result_id = $this->common_model->db_insert('nd_pembayaran_piutang_temp',$data);

			$post = (array)$this->input->post();
			$idx = 0;
			foreach ($post as $key => $value) {
				if (strpos($key, 'bayar_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$penjualan_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'piutang_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$piutang_awal_id[$idx] = $data_get[1];
						$idx++;
					}
				}
				
			}

			//===========================

			// print_r($penjualan_id);
			$idx = 0;

			if (isset($penjualan_id)) {
				foreach ($penjualan_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['bayar_'.$value]),
						'data_status' => 1
						);
					$idx++;
				}
			}

			if (isset($piutang_awal_id)) {
				foreach ($piutang_awal_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['piutang_'.$value]),
						'data_status' => 2
						 );
					$idx++;
				}
			}

			
			$this->common_model->db_insert_batch('nd_pembayaran_piutang_temp_detail',$data_detail);	
			$pembayaran_piutang_id = $result_id;	
		}else{

			$data = array(
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
			'nama_bank' => $ini->post('nama_bank'),
			'no_rek_bank' => $ini->post('no_rek_bank'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')),
			'nama_penerima' => $ini->post('nama_penerima'),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

			$this->common_model->db_update('nd_pembayaran_piutang_temp',$data,'id',$pembayaran_piutang_id);
					
		}
		
		redirect(is_setting_link('transaction/piutang_payment_form').'/?id='.$pembayaran_piutang_id);

	}

	function update_bayar_piutang_detail(){
		$id = $this->input->post('id');
		$data = array(
			'amount' => $this->input->post('amount') );
		$this->common_model->db_update('nd_pembayaran_piutang_temp_detail',$data,'id',$id);
		echo "OK";
	}

	function pembayaran_piutang_nilai_insert(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !=''  && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan') );

		// print_r($data);
		$this->common_model->db_insert('nd_pembayaran_piutang_temp_nilai', $data);

		redirect(is_setting_link('transaction/piutang_payment_form').'/?id='.$pembayaran_piutang_id.'#bayar-section');

	}

	function pembayaran_piutang_nilai_update(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$id = $ini->post('pembayaran_piutang_nilai_id');
		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan') );

		// print_r($data);
		$this->common_model->db_update("nd_pembayaran_piutang_temp_nilai", $data,'id', $id);
		redirect(is_setting_link('transaction/piutang_payment_form').'/?id='.$pembayaran_piutang_id);
	}

	function update_pembulatan_piutang(){
		$id = $this->input->post('id');
		$data = array(
			'pembulatan' => $this->input->post('pembulatan') );
		$this->common_model->db_update("nd_pembayaran_piutang_temp", $data, "id", $id);
		echo "OK";

	}



}