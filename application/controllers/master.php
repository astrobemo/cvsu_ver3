<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

	private $data = [];

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}elseif (is_user_time() == false) {
			redirect('home');
		}
		$this->data['username'] = is_username();
		
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

		$this->load->model('stok/stok_general_model','sg_model',true);
		$this->barang_sku_aktif = $this->common_model->get_sku_barang_aktif();
		$this->mysqli_conn = $this->db->conn_id;
		
		date_default_timezone_set("Asia/Jakarta");		

	}

	function index(){
		redirect('admin/dashboard');
	}

	function ubah_status_aktif(){
		$data_get = explode('=?=', $this->input->get('data_sent'));
		$data = array('status_aktif' => $data_get[0] );
		$this->common_model->db_update('nd_'.$data_get[2],$data,'id',$data_get[1]);
		$link = $this->input->get('link');
		redirect(is_setting_link('master/'.$link));
	}

//===================================ajax_check=============================================

	function check_user(){
		$username = $this->input->post('username');
		$id = '';
		$result = $this->common_model->db_select_cond('nd_user','username', $username,'');
		foreach ($result as $row) {
			$id = $row->id;
		}
		if ($id != '') {
			echo 'false';
		}else{
			echo 'true';
		}
	}

	function check_user_edit(){
		$username = $this->input->post('username');
		$id = $this->input->post('user_id');
		
		$result = $this->common_model->db_select_cond('nd_user','username', $username,'');
		$check = '';
		foreach ($result as $row) {
			$check = $row->id;
		}
		if ($check != '') {
			// echo $id;
			if ($check == $id ) {
				echo 'true';
			}else{
				echo 'false';
			}
		}else{
			echo 'true';
		}
	}

//================================user list================================================

	function user_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/user_list' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'daftar user',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['posisi_list'] = $this->common_model->db_select('nd_posisi where id != 1'); 
		$data['user_list'] = $this->common_model->get_user_list();
		$this->load->view('admin/template',$data);
	}


	function user_list_insert(){
		$data = array(
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'posisi_id' => $this->input->post('posisi_id'),
			'time_start' => $this->input->post('time_start'),
			'time_end' => $this->input->post('time_end'),
			'status_aktif' => 1
			);
		$this->common_model->db_insert('nd_user',$data);
		redirect(trim(base64_encode('master/user_list'),'='));
	}

	function user_list_update(){

		$id = $this->input->post('user_id');

		if ($this->input->post('password') == '') {
			$data = array(
				'username' => $this->input->post('username'),
				'posisi_id' => $this->input->post('posisi_id'),
				'time_start' => $this->input->post('time_start'),
				'time_end' => $this->input->post('time_end')
				);
		}else{
			$data = array(
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'posisi_id' => $this->input->post('posisi_id'),
				'time_start' => $this->input->post('time_start'),
				'time_end' => $this->input->post('time_end')
				);
		}

		$this->common_model->db_update('nd_user',$data,'id', $id);
		redirect(trim(base64_encode('master/user_list'),'='));
	}

	function user_list_status_update(){
		$id = $this->input->get('id');
		$data = array(
			'status_aktif' => $this->input->get('status_aktif') );

		$this->common_model->db_update('nd_user', $data,'id',$id);
		redirect(trim(base64_encode('master/user_list'),'='));
		
	}

//================================satuan list================================================

	function satuan_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/satuan_list' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Satuan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['satuan_list'] = $this->common_model->db_select('nd_satuan'); 
		$this->load->view('admin/template',$data);
	}


	function satuan_list_insert(){
		$data = array(
			'nama' => $this->input->post('nama')
			);
		$this->common_model->db_insert('nd_satuan',$data);
		redirect(trim(base64_encode('master/satuan_list'),'='));
	}

	function satuan_list_update(){

		$id = $this->input->post('satuan_id');

		$data = array(
			'nama' => $this->input->post('nama')
			);

		$this->common_model->db_update('nd_satuan',$data,'id', $id);
		redirect(trim(base64_encode('master/satuan_list'),'='));
	}

//================================printer list================================================

	function printer_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/printer_list' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar printer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['printer_list'] = $this->common_model->db_select('nd_printer_list'); 
		$this->load->view('admin/template',$data);
	}


	function printer_list_insert(){
		$data = array(
			'nama' => $this->input->post('nama')
			);
		$this->common_model->db_insert('nd_printer_list',$data);
		redirect(trim(base64_encode('master/printer_list'),'='));
	}

	function printer_list_update(){

		$id = $this->input->post('printer_id');

		$data = array(
			'nama' => $this->input->post('nama')
			);

		$this->common_model->db_update('nd_printer_list',$data,'id', $id);
		redirect(trim(base64_encode('master/printer_list'),'='));
	}

//================================barang list================================================

	function barang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/barang_list' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'daftar barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['satuan_list'] = $this->common_model->db_select('nd_satuan');
		$data['barang_list'] = $this->common_model->get_barang_list(); 
		$this->load->view('admin/template',$data);
	}



	function data_barang(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif','nama', 'nama_jual','nama_satuan', 'nama_packaging' ,'harga_jual', 'harga_beli', 'pengali_harga','nama_toko', 'status_barang');
        
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

        $rResult = $this->common_model->get_barang_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);        
        
        // $iFilteredTotal = 5;
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_barang');
        $Filternya = $this->common_model->get_barang_list_ajax($aColumns, $sWhere, $sOrder, '');
        $iFilteredTotal = $Filternya->num_rows();
        
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


	function barang_list_insert(){
		$harga_jual = ( $this->input->post('harga_jual') !='' ? $this->input->post('harga_jual') : 0);
		$harga_beli = ( $this->input->post('harga_beli') !='' ? $this->input->post('harga_beli') : 0);
		$harga_ecer = ( $this->input->post('harga_ecer') !='' ? $this->input->post('harga_ecer') : 0);
		$harga_jual = str_replace(',', '', $harga_jual);
		$harga_beli = str_replace(',', '', $harga_beli);
		$harga_ecer = str_replace(',', '', $harga_ecer);

		$data = array(
			'nama' => $this->input->post('nama'),
			'nama_jual' => $this->input->post('nama_jual'),
			'harga_jual' => $harga_jual,
			'harga_beli' => $harga_beli,
			'harga_ecer' => $harga_ecer,
			'subitem_status' => $this->input->post('subitem_status'),
			'eceran_mix_status' => $this->input->post('eceran_mix_status'),
			'toko_id' => $this->input->post('toko_id') ,
			'satuan_id' => $this->input->post('satuan_id'),
			'packaging_id' => $this->input->post('packaging_id'),
			'pengali_harga_jual' => $this->input->post('pengali_harga_jual'),
			'pengali_harga_beli' => $this->input->post('pengali_harga_beli'),
			'status_aktif' => $this->input->post('status_aktif')
			);
		// print_r($data);
		$this->common_model->db_insert('nd_barang',$data);
		redirect(is_setting_link('master/barang_list'));
	}

	function barang_list_update(){

		$id = $this->input->post('barang_id');

		$harga_jual = ( $this->input->post('harga_jual') !='' ? $this->input->post('harga_jual') : 0);
		$harga_beli = ( $this->input->post('harga_beli') !='' ? $this->input->post('harga_beli') : 0);
		$harga_ecer = ( $this->input->post('harga_ecer') !='' ? $this->input->post('harga_ecer') : 0);
		$harga_jual = str_replace(',', '', $harga_jual);
		$harga_beli = str_replace(',', '', $harga_beli);
		$harga_ecer = str_replace(',', '', $harga_ecer);
		$nama_jual = $this->input->post('nama_jual');

		$data = array(
			'nama' => $this->input->post('nama'),
			'nama_jual' => $nama_jual,
			'harga_jual' => $harga_jual,
			'harga_beli' => $harga_beli,
			'harga_ecer' => $harga_ecer,
			'toko_id' => $this->input->post('toko_id'),
			'subitem_status' => $this->input->post('subitem_status'),
			'eceran_mix_status' => $this->input->post('eceran_mix_status'),
			'satuan_id' => $this->input->post('satuan_id'),
			'packaging_id' => $this->input->post('packaging_id'),
			'pengali_harga_jual' => $this->input->post('pengali_harga_jual'),
			'pengali_harga_beli' => $this->input->post('pengali_harga_beli'),
			'status_aktif' => $this->input->post('status_aktif')
			);

			// print_r($this->input->post());
			// print_r($data);

			$nama_satuan = "";
			$nama_packaging = "";
		
			foreach ($satuan_list_aktif as $row) {
				if ($row->id == $data['satuan_id']) {
					$nama_satuan = $row->nama;
				}

				if ($row->id == $data['packaging_id']) {
					$nama_packaging = $row->nama;
				}
			}

		$this->common_model->db_update('nd_barang',$data,'id', $id);
		$this->update_sku_barang($id, $nama_jual, $nama_satuan, $nama_packaging);
		redirect(is_setting_link('master/barang_list'));
	}

	function update_sku_barang($barang_id, $nama_jual, $nama_satuan, $nama_packaging){
		$ket = $this->common_model->db_select("nd_barang_sku WHERE barang_id=$barang_id");
		$warna_list = [];
		$id_list = [];
		foreach ($ket as $row) {
			array_push($warna_list,$row->warna_id);
			$id_list[$row->warna_id] = $row->id;
		}

		if (count($warna_list) > 0) {
			$get_warna = $this->common_model->db_select("nd_warna where id in (".implode(",",$warna_list).")");

			$new_data = array();
			foreach ($get_warna as $row) {
				array_push($new_data,array(
					"id"=>$id_list[$row->id],
					"nama_barang"=>$nama_jual." ".$row->warna_jual,
					"nama_satuan"=>$nama_satuan,
					"nama_packaging"=>$nama_packaging
				));
			}

			$this->common_model->db_update_batch("nd_barang_sku", $new_data,"id");
		}
	}

	function barang_sku_insert(){
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$nama_barang = "";
		$nama_satuan = "";
		$nama_packaging = "";

		$sku_id = "";
		$get_barang_sku = $this->common_model->db_select("nd_barang_sku WHERE barang_id='$barang_id' AND warna_id='$warna_id'");
		foreach ($get_barang_sku as $row) {
			$sku_id = $row->id;
		}
		if ($sku_id == "") {
			# code...
			$get_data_barang = $this->common_model->db_select("nd_barang WHERE id='$barang_id'");
			foreach ($get_data_barang as $row) {
				$nama_barang = $row->nama_jual;
				$satuan_id = $row->satuan_id;
				$packaging_id = $row->packaging_id;
			}
	
			$get_data_warna = $this->common_model->db_select("nd_warna WHERE id='$warna_id'");
			foreach ($get_data_warna as $row) {
				$nama_warna = $row->warna_jual;
			}
	
			$get_nama_satuan = $this->common_model->db_select("nd_satuan WHERE id='$satuan_id'");
			foreach ($get_nama_satuan as $row) {
				$nama_satuan = $row->nama;
			}
			$get_nama_packaging = $this->common_model->db_select("nd_satuan WHERE id='$packaging_id'");
			foreach ($get_nama_packaging as $row) {
				$nama_packaging = $row->nama;
			}
			$nSku = array(
				'barang_id' => $barang_id,
				'warna_id' => $warna_id, 
				'nama_barang' => $nama_barang.' '.$nama_warna, 
				'nama_satuan' => $nama_satuan, 
				'nama_packaging' => $nama_packaging, 
				'user_id' => is_user_id(),
				'status_aktif'=> 1
			);
	
			$this->common_model->db_insert('nd_barang_sku',$nSku);
		}
		redirect(is_setting_link('master/barang_list'));
	}

//================================supplier list================================================

	function supplier_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/supplier_list' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Supplier',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['supplier_list'] = $this->common_model->db_select('nd_supplier'); 
		$this->load->view('admin/template',$data);
	}


	function supplier_list_insert(){
		$data = array(
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'telepon' => $this->input->post('telepon'),
			'kota' => $this->input->post('kota'),
			'fax' => $this->input->post('fax'),
			'kode_pos' => $this->input->post('kode_pos'),
			'email' => $this->input->post('email'),
			'website' => $this->input->post('website')
			);
		$this->common_model->db_insert('nd_supplier',$data);
		redirect(trim(base64_encode('master/supplier_list'),'='));
	}

	function supplier_list_update(){

		$id = $this->input->post('supplier_id');

		$data = array(
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'telepon' => $this->input->post('telepon'),
			'kota' => $this->input->post('kota'),
			'fax' => $this->input->post('fax'),
			'kode_pos' => $this->input->post('kode_pos'),
			'email' => $this->input->post('email'),
			'website' => $this->input->post('website')
			);

		$this->common_model->db_update('nd_supplier',$data,'id', $id);
		redirect(trim(base64_encode('master/supplier_list'),'='));
	}

//================================customer list================================================

	function customer_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/customer_list' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Customer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['customer_list'] = $this->common_model->db_select('nd_customer'); 
		$this->load->view('admin/template',$data);
	}

	function data_customer(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif','nama', 'alias', 'alamat','kota', 'telepon1', 'npwp', 'tempo_kredit', 'other_data');
        
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

        $rResult = $this->common_model->get_customer_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_customer');
        $Filternya = $this->common_model->get_customer_list_ajax($aColumns, $sWhere, $sOrder, '');
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


	function customer_list_insert(){
		$data = array(
			'nama' => $this->input->post('nama'),
			'alias' => $this->input->post('alias'),
			'alamat' => $this->input->post('alamat'),
			'kota' => $this->input->post('kota'),
			'npwp' => $this->input->post('npwp'),
			'nik' => str_replace(' ','',$this->input->post('nik')),
			'telepon1' => $this->input->post('telepon1'),
			'telepon2' => $this->input->post('telepon2'),
			'kode_pos' => $this->input->post('kode_pos'),
			'email' => $this->input->post('email'),
			'tempo_kredit' => ($this->input->post('tempo_kredit') != '' ? $this->input->post('tempo_kredit') : null )
			);
		$this->common_model->db_insert('nd_customer',$data);
		redirect(trim(base64_encode('master/customer_list'),'='));
	}

	function customer_list_update(){

		$id = $this->input->post('customer_id');

		$data = array(
			'nama' => $this->input->post('nama'),
			'alias' => $this->input->post('alias'),
			'alamat' => $this->input->post('alamat'),
			'kota' => $this->input->post('kota'),
			'npwp' => $this->input->post('npwp'),
			'nik' => str_replace(' ','',$this->input->post('nik')),
			'telepon1' => $this->input->post('telepon1'),
			'telepon2' => $this->input->post('telepon2'),
			'kode_pos' => $this->input->post('kode_pos'),
			'email' => $this->input->post('email'),
			'tempo_kredit' => ($this->input->post('tempo_kredit') != '' ? $this->input->post('tempo_kredit') : null )
			);

		$this->common_model->db_update('nd_customer',$data,'id', $id);
		redirect(trim(base64_encode('master/customer_list'),'='));
	}

	function customer_profile(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->uri->segment(2);
		$year = date('Y');

		$data = array(
			'content' =>'admin/master/customer_profile' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Customer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'customer_id' => $customer_id,
			'common_data'=> $this->data );

		$data['data_customer'] = $this->common_model->db_select('nd_customer where id='.$customer_id); 
		// $data['customer_profile_pembelian_barang'] = $this->common_model->get_customer_profile_pembelian_barang_terbanyak($customer_id, $year); 
		// $data['customer_profile_pembelanjaan'] = $this->common_model->get_customer_profile_pembelanjaan_tahun($customer_id, $year.'-01-01', $year.'-12-31'); 
		$data['customer_profile_hutang'] = $this->common_model->get_customer_profile_piutang($customer_id); 
		$data['customer_dp'] = $this->common_model->get_dp_by_customer($customer_id); 
		// $data['data_penjualan'] = $this->common_model->get_data_penjualan($customer_id);

		$limit = '';
		// $limit = "LIMIT ".(is_posisi_id() <= 5 ? '30' : '1');
		$data['data_penjualan'] = $this->common_model->get_penjualan_report('', $customer_id, $limit);

		$this->load->view('admin/template_no_sidebar',$data);
	}


	function get_penjualan_tahun(){
		$customer_id = $this->input->get('customer_id');
		$recap_list = $this->common_model->get_customer_profile_pembelanjaan_tahun($customer_id, date('Y-01-01'), date('Y-12-31'));

		echo json_encode($recap_list);
	}

	function get_barang_jual_terbanyak(){
		$customer_id = $this->input->get('customer_id');

		$recap_list = $this->common_model->get_customer_profile_pembelian_barang_terbanyak($customer_id, date('Y'));

		echo json_encode($recap_list);
	}



//================================warna list================================================

	function warna_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/warna_list' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Warna',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['warna_list'] = $this->common_model->db_select('nd_warna order by warna_beli asc'); 
		$this->load->view('admin/template',$data);
	}


	function warna_list_insert(){
		$data = array(
			'warna_beli' => $this->input->post('warna_beli'),
			'warna_jual' => $this->input->post('warna_jual')
			);
		$this->common_model->db_insert('nd_warna',$data);
		redirect(trim(base64_encode('master/warna_list'),'='));
	}

	function warna_list_update(){

		$id = $this->input->post('warna_id');

		$data = array(
			'warna_beli' => $this->input->post('warna_beli'),
			'warna_jual' => $this->input->post('warna_jual')
			);

		$this->common_model->db_update('nd_warna',$data,'id', $id);
		redirect(trim(base64_encode('master/warna_list'),'='));
	}

//================================toko list================================================

	function toko_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/toko_list',
			'breadcrumb_title' => 'Toko',
			'breadcrumb_small' => 'Daftar Toko',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['toko_list'] = $this->common_model->db_select('nd_toko'); 
		$this->load->view('admin/template',$data);
	}


	function toko_list_insert(){
		$use_ppn = $this->input->post('use_ppn');
		$use_ppn = ($use_ppn == '' ? 0 : $use_ppn);
		$data = array(
			'nama' => $this->input->post('nama'),
			'use_ppn' => $use_ppn,
			'alamat' => $this->input->post('alamat'),
			'telepon' => $this->input->post('telepon'),
			'fax' => $this->input->post('fax'),
			'kota' => $this->input->post('kota'),
			'kode_pos' => $this->input->post('kode_pos'),
			'NPWP' => $this->input->post('NPWP'),
			'color_code' => $this->input->post('color_code')
			);
		$this->common_model->db_insert('nd_toko',$data);
		redirect(trim(base64_encode('master/toko_list'),'='));
	}

	function toko_list_update(){

		$id = $this->input->post('toko_list_id');
		$use_ppn = $this->input->post('use_ppn');
		$use_ppn = ($use_ppn == '' ? 0 : $use_ppn);
		$data = array(
			'nama' => $this->input->post('nama'),
			'use_ppn' => $use_ppn,
			'alamat' => $this->input->post('alamat'),
			'telepon' => $this->input->post('telepon'),
			'fax' => $this->input->post('fax'),
			'kota' => $this->input->post('kota'),
			'kode_pos' => $this->input->post('kode_pos'),
			'NPWP' => $this->input->post('NPWP'),
			'color_code' => $this->input->post('color_code')
			);

		$this->common_model->db_update('nd_toko',$data,'id', $id);
		redirect(trim(base64_encode('master/toko_list'),'='));
	}

//================================gudang list================================================

	function gudang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/gudang_list',
			'breadcrumb_title' => 'Gudang',
			'breadcrumb_small' => 'Daftar Gudang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['gudang_list'] = $this->common_model->db_select('nd_gudang'); 
		$this->load->view('admin/template',$data);
	}


	function gudang_list_insert(){
		$data = array(
			'nama' => $this->input->post('nama'),
			'lokasi' => $this->input->post('lokasi')
			);
		$this->common_model->db_insert('nd_gudang',$data);
		redirect(trim(base64_encode('master/gudang_list'),'='));
	}

	function gudang_list_update(){

		$id = $this->input->post('gudang_list_id');

		$data = array(
			'nama' => $this->input->post('nama'),
			'lokasi' => $this->input->post('lokasi')
			);

		$this->common_model->db_update('nd_gudang',$data,'id', $id);
		redirect(trim(base64_encode('master/gudang_list'),'='));
	}

	function toggle_gudang_visibility(){
		$gudang_id=$this->input->post('gudang_id');
		$data = array(
			'isVisible'=>$this->input->post("isVisible")
		);

		$this->common_model->db_update("nd_gudang",$data,"id",$gudang_id);
		echo json_encode("OK");

	}


//================================close program date list================================================

	function close_day_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/close_day_list',
			'breadcrumb_title' => 'Admin',
			'breadcrumb_small' => 'Daftar Hari Tutup Akses Program',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['close_day_list'] = $this->common_model->db_select('nd_close_day'); 
		$this->load->view('admin/template',$data);
	}


	function close_day_list_insert(){
		$data = array(
			'tanggal_start' => is_date_formatter($this->input->post('tanggal_start')),
			'tanggal_end' => is_date_formatter($this->input->post('tanggal_end')),
			'keterangan' => $this->input->post('keterangan'),
			'user_id' => is_user_id(),
			);
		$this->common_model->db_insert('nd_close_day',$data);
		redirect(trim(base64_encode('master/close_day_list'),'='));
	}

	function close_day_list_update(){

		$id = $this->input->post('close_day_id');

		$data = array(
			'tanggal_start' => is_date_formatter($this->input->post('tanggal_start')),
			'tanggal_end' => is_date_formatter($this->input->post('tanggal_end')),
			'keterangan' => $this->input->post('keterangan'),
			'user_id' => is_user_id(),
			);

		$this->common_model->db_update('nd_close_day',$data,'id', $id);
		redirect(trim(base64_encode('master/close_day_list'),'='));
	}

//================================barang list================================================

	function barang_eceran_mix_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/master/barang_eceran_mix' ,
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'daftar eceran mix',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['barang_list'] = $this->common_model->get_barang_eceran_mix_list(); 
		$this->load->view('admin/template',$data);
	}

	function barang_eceran_mix_list_update(){
		$barang_id = $this->input->post('barang_id');
		$data = array(
			'eceran_mix_status' => $this->input->post('eceran_mix_status')
			);
		$this->common_model->db_update('nd_barang',$data, 'id', $barang_id);
		redirect(is_setting_link('master/barang_eceran_mix_list'));
	}
/** 
============================================================================================================================================= 
**/

	function stok_warning(){
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' =>'admin/master/stok_warning',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Warning',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			);	

		$data['stok_warning_list'] = $this->sg_model->get_stok_warning();

		$this->load->view('admin/template',$data);		
	}

	function qty_warning_insert(){
		$qty_alert = $this->input->post('qty_alert');
		$qty_warning = $this->input->post('qty_warning');
		$data = array(
			'sku_id' => $this->input->post('sku_id'),
			'toko_id' => $this->input->post('toko_id'),
			'nama_satuan' => $this->input->post('nama_satuan'),
			'qty_alert' => ($qty_alert == '' ? 0 : $qty_alert),
			"qty_warning" => ($qty_warning == '' ? 0 : $qty_warning)
		);

		$this->common_model->db_insert("nd_stok_warning", $data);

		redirect(is_setting_link('master/stok_warning'));
	}

	function qty_warning_insert_ajax(){
		$qty_alert = $this->input->post('qty_alert');
		$qty_warning = $this->input->post('qty_warning');
		$data = array(
			'sku_id' => $this->input->post('sku_id'),
			'toko_id' => $this->input->post('toko_id'),
			'nama_satuan' => $this->input->post('nama_satuan'),
			'qty_alert' => ($qty_alert == '' ? 0 : $qty_alert),
			"qty_warning" => ($qty_warning == '' ? 0 : $qty_warning)
		);

		$this->common_model->db_insert("nd_stok_warning", $data);

		redirect(is_setting_link('master/stok_warning'));
	}

	function qty_warning_update(){

		$id = $this->input->post('id');
		$qty_alert = $this->input->post('qty_alert');
		$qty_warning = $this->input->post('qty_warning');
		$data = array(
			'sku_id' => $this->input->post('sku_id'),
			'toko_id' => $this->input->post('toko_id'),
			'nama_satuan' => $this->input->post('nama_satuan'),
			'qty_alert' => ($qty_alert == '' ? 0 : $qty_alert),
			"qty_warning" => ($qty_warning == '' ? 0 : $qty_warning)
		);

		$this->common_model->db_update("nd_stok_warning", $data,'id', $id);

		redirect(is_setting_link('master/stok_warning'));
	}


	function qty_warning_delete(){
		$id = $this->input->post('id');
		$this->common_model->db_delete("nd_stok_warning", "id", $id);
		echo json_encode("OK");
	}
	


}