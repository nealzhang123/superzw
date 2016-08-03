<?php 
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Pregnant_B_List_Table extends WP_List_Table{
	public $plugin;
	public $pregnant_plugin;
	public $total_count;
	public $total_items;
	PUBLIC $column_header_define;
	public $column_parameters;
	
	function  __construct(){
		$this->plugin = new HospitalTrack;
		$this->pregnant_plugin = new HospitalPreBirthPregnantTrack;

	}

	function prepare_items(){
		$this->total_count = $this->get_userinfos_count();

		$this->items = $this->get_userinfo();
		// error_log(var_export($this->items,true));
		$this->column_header_define = $columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_colums();
		$this->_column_headers = array($columns, $hidden, $sortable);
	}

	function get_userinfos_count(){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_b_pregnant_info';
		// $sql = 'SELECT count(distinct no1) FROM ' . $table .' ORDER BY id ASC';
		// $count = $wpdb->get_var($sql);
		$sql = 'SELECT  pregnnat_b.id, pregnnat_b.no1, basic.lmp, pregnnat_b.ult_checkdate, pregnnat_b.crl FROM '. $table . ' as pregnnat_b inner join ' . $table_basic . ' as basic on basic.no1 = pregnnat_b.no1 order by id ASC ';
		$res = $wpdb->get_results($sql, ARRAY_A);
		//error_log(var_export($res,true));
		$sql1 = 'SELECT distinct(no1) FROM ' . $table . ' order by id ASC ';
		$results = $wpdb->get_results( $sql1 , ARRAY_A);
		foreach ($results as $key1 => $value) {
			$results[$key1]['ult_12wk'] = 0;
			$results[$key1]['ult_16wk'] = 0;
			$results[$key1]['ult_24wk'] = 0;
			$results[$key1]['ult_32wk'] = 0;
			$results[$key1]['ult_37wk'] = 0;			
		}


		foreach ($res as $key => $value) {
			foreach ($results as $key1 => $result) {

				if($result['no1'] == $value['no1']){
					$ult_week = round((strtotime($value['ult_checkdate']) - strtotime($value['lmp']))/604800, 2);

					//error_log($ult_week );
					if( $results[$key1]['ult_12wk'] != 1 ){
						//error_log(var_export($result,true));
						if( 10.00 <= $ult_week && $ult_week <15.00 && !empty($value['crl']) ) {
							$results[$key1]['ult_12wk'] = 1;	
						}else{
							$results[$key1]['ult_12wk'] = 0;
						}
					}
					
					if($results[$key1]['ult_16wk'] != 1){
						if( 15.00 <= $ult_week && $ult_week <19.00 ) {
							$results[$key1]['ult_16wk'] = 1;
						}else{
							$results[$key1]['ult_16wk'] = 0;
						}
					}

					if($results[$key1]['ult_24wk'] != 1){
						if( 23.00 <= $ult_week && $ult_week <26.00 ) {
							$results[$key1]['ult_24wk'] = 1;
						}else{
							$results[$key1]['ult_24wk'] = 0;
						}
					}

					if($results[$key1]['ult_32wk'] != 1){
						if( 30.00 <= $ult_week && $ult_week <34.00 ) {
							$results[$key1]['ult_32wk'] = 1;
						}else{
							$results[$key1]['ult_32wk'] = 0;
						}
					}

					if($results[$key1]['ult_37wk'] != 1){
						if( 36.00 <= $ult_week  ) {
							$results[$key1]['ult_37wk'] = 1;
						}else{
							$results[$key1]['ult_37wk'] = 0;
						}
					}
				}
					
			}

		}
		$flag_no1 = 0;
		$flag_ult_12wk = 0;
		$flag_ult_16wk = 0;
		$flag_ult_24wk = 0;
		$flag_ult_32wk = 0;
		$flag_ult_37wk = 0;
		// error_log(var_export($_REQUEST,true));
		//error_log(isset($_POST['pregnant_b_ult_12wk']));
		if( isset($_REQUEST['pregnant_b_no1']) && strlen($_REQUEST['pregnant_b_no1']) == 1){
	 		$pregnant_b_no1 = $_REQUEST['pregnant_b_no1'];
			$flag_no1 = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_12wk']) && strlen($_REQUEST['pregnant_b_ult_12wk']) == 1){
			$pregnant_b_ult_12wk = $_REQUEST['pregnant_b_ult_12wk'];
			$flag_ult_12wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_16wk']) && strlen($_REQUEST['pregnant_b_ult_16wk']) == 1){
			$pregnant_b_ult_16wk = $_REQUEST['pregnant_b_ult_16wk'];
			$flag_ult_16wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_24wk']) && strlen($_REQUEST['pregnant_b_ult_24wk']) == 1){
			$pregnant_b_ult_24wk = $_REQUEST['pregnant_b_ult_24wk'];
			$flag_ult_24wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_32wk']) && strlen($_REQUEST['pregnant_b_ult_32wk']) == 1){
			$pregnant_b_ult_32wk = $_REQUEST['pregnant_b_ult_32wk'];
			$flag_ult_32wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_37wk']) && strlen($_REQUEST['pregnant_b_ult_37wk']) == 1){
			$pregnant_b_ult_37wk = $_REQUEST['pregnant_b_ult_37wk'];
			$flag_ult_37wk = 1;
		}

		foreach ($results as $key => $value) {
			if(1 == $flag_no1 ){
				if( strpos($value['no1'], $pregnant_b_no1) == false ){
					unset($results[$key]);
					continue;
				}
				
			}
			if(1 == $flag_ult_12wk ){
				if( $pregnant_b_ult_12wk != $value['ult_12wk']){

					unset($results[$key]);
					continue;
				}
				
			}
			if(1 == $flag_ult_16wk ){
				if( $pregnant_b_ult_16wk != $value['ult_16wk']){
					unset($results[$key]);
				}
			}
			if(1 == $flag_ult_24wk ){
				if( $pregnant_b_ult_24wk != $value['ult_24wk']){
					unset($results[$key]);
				}
			}
			if(1 == $flag_ult_32wk ){
				if( $pregnant_b_ult_32wk != $value['ult_32wk']){
					unset($results[$key]);
				}
			}
			if(1 == $flag_ult_37wk ){
				if( $pregnant_b_ult_37wk != $value['ult_37wk']){
					unset($results[$key]);
				}
			}

		}
		// error_log(var_export($results,true));
		$count  = count($results);
		
		return $count;
	}

	function get_userinfo(){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_b_pregnant_info';

 		
		$sql = 'SELECT  pregnnat_b.id, pregnnat_b.no1, basic.lmp, pregnnat_b.ult_checkdate, pregnnat_b.crl FROM '. $table . ' as pregnnat_b inner join ' . $table_basic . ' as basic on basic.no1 = pregnnat_b.no1 order by id ASC ';
		$res = $wpdb->get_results($sql, ARRAY_A);
		//error_log(var_export($res,true));
		$sql1 = 'SELECT distinct(no1) FROM ' . $table . ' order by id ASC ';
		$results = $wpdb->get_results( $sql1 , ARRAY_A);
		foreach ($results as $key1 => $value) {
			$results[$key1]['ult_12wk'] = 0;
			$results[$key1]['ult_16wk'] = 0;
			$results[$key1]['ult_24wk'] = 0;
			$results[$key1]['ult_32wk'] = 0;
			$results[$key1]['ult_37wk'] = 0;			
		}


		foreach ($res as $key => $value) {
			foreach ($results as $key1 => $result) {

				if($result['no1'] == $value['no1']){
					// error_log($value['ult_checkdate'] );
					// error_log($value['lmp'] );
					$ult_week = round((strtotime($value['ult_checkdate']) - strtotime($value['lmp']))/604800, 2);
					// error_log($ult_week );
					if( $results[$key1]['ult_12wk'] != 1 ){
						//error_log(var_export($result,true));
						if( 10.00 <= $ult_week && $ult_week <15.00 && !empty($value['crl']) ) {
							$results[$key1]['ult_12wk'] = 1;	
						}else{
							$results[$key1]['ult_12wk'] = 0;
						}
					}
					
					if($results[$key1]['ult_16wk'] != 1){
						if( 15.00 <= $ult_week && $ult_week <19.00 ) {
							$results[$key1]['ult_16wk'] = 1;
						}else{
							$results[$key1]['ult_16wk'] = 0;
						}
					}

					if($results[$key1]['ult_24wk'] != 1){
						if( 23.00 <= $ult_week && $ult_week <26.00 ) {
							$results[$key1]['ult_24wk'] = 1;
						}else{
							$results[$key1]['ult_24wk'] = 0;
						}
					}

					if($results[$key1]['ult_32wk'] != 1){
						if( 30.00 <= $ult_week && $ult_week <34.00 ) {
							$results[$key1]['ult_32wk'] = 1;
						}else{
							$results[$key1]['ult_32wk'] = 0;
						}
					}

					if($results[$key1]['ult_37wk'] != 1){
						if( 36.00 <= $ult_week  ) {
							$results[$key1]['ult_37wk'] = 1;
						}else{
							$results[$key1]['ult_37wk'] = 0;
						}
					}
				}
					
			}

		}
		$flag_no1 = 0;
		$flag_ult_12wk = 0;
		$flag_ult_16wk = 0;
		$flag_ult_24wk = 0;
		$flag_ult_32wk = 0;
		$flag_ult_37wk = 0;
		// error_log(var_export($_REQUEST,true));
		if( isset($_REQUEST['pregnant_b_no1']) && strlen($_REQUEST['pregnant_b_no1']) != 0){
	 		$pregnant_b_no1 = $_REQUEST['pregnant_b_no1'];
			$flag_no1 = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_12wk']) && strlen($_REQUEST['pregnant_b_ult_12wk']) == 1){
			$pregnant_b_ult_12wk = $_REQUEST['pregnant_b_ult_12wk'];
			$flag_ult_12wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_16wk']) && strlen($_REQUEST['pregnant_b_ult_16wk']) == 1){
			$pregnant_b_ult_16wk = $_REQUEST['pregnant_b_ult_16wk'];
			$flag_ult_16wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_24wk']) && strlen($_REQUEST['pregnant_b_ult_24wk']) == 1){
			$pregnant_b_ult_24wk = $_REQUEST['pregnant_b_ult_24wk'];
			$flag_ult_24wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_32wk']) && strlen($_REQUEST['pregnant_b_ult_32wk']) == 1){
			$pregnant_b_ult_32wk = $_REQUEST['pregnant_b_ult_32wk'];
			$flag_ult_32wk = 1;
		}
		if( isset($_REQUEST['pregnant_b_ult_37wk']) && strlen($_REQUEST['pregnant_b_ult_37wk']) == 1){
			$pregnant_b_ult_37wk = $_REQUEST['pregnant_b_ult_37wk'];
			$flag_ult_37wk = 1;
		}
		// error_log( $pregnant_b_no1);
		foreach ($results as $key => $value) {
			if(1 == $flag_no1 ){
				//if( $pregnant_b_no1 != $value['no1']){
				// error_log($pregnant_b_no1);
				// error_log($value['no1']);
				if( strpos($value['no1'], $pregnant_b_no1) == false){
					unset($results[$key]);
					continue;
				}
				
			}
			if(1 == $flag_ult_12wk ){
				if( $pregnant_b_ult_12wk != $value['ult_12wk']){
					unset($results[$key]);
					continue;
				}
				
			}
			if(1 == $flag_ult_16wk ){
				if( $pregnant_b_ult_16wk != $value['ult_16wk']){
					unset($results[$key]);
				}
			}
			if(1 == $flag_ult_24wk ){
				if( $pregnant_b_ult_24wk != $value['ult_24wk']){
					unset($results[$key]);
				}
			}
			if(1 == $flag_ult_32wk ){
				if( $pregnant_b_ult_32wk != $value['ult_32wk']){
					unset($results[$key]);
				}
			}
			if(1 == $flag_ult_37wk ){
				if( $pregnant_b_ult_37wk != $value['ult_37wk']){
					unset($results[$key]);
				}
			}

		}
		//error_log(var_export($results,true));
		$results_export = $results;
		$this->total_items = $results_export;
		
		$current_page = 0;
		if( isset( $_POST['current_page'] ) && is_numeric( $_POST['current_page'] ) ) {
            $current_page = $_POST['current_page']-1;
        }
       
        $page_per_num = 0;
        if( isset( $_POST['page_per_num'] ) && is_numeric( $_POST['page_per_num'] ) ) {
            $page_per_num = $_POST['page_per_num'];
        }
		$page_per_num = $this->plugin->page_arr[$page_per_num];

		$start = $current_page * $page_per_num;
		$results = array_slice( $results, $start, $page_per_num);
		
		// $sql .=' LIMIT '. $start . ',' . $page_per_num;
		
		// $results = $wpdb->get_results($sql,'ARRAY_A');
		//error_log(var_export($results,true));
		return $results;
	}

	function get_columns(){

		$columns['no1']				= '队列编号' ;
		$columns['ult_12wk']			= '头臀长B超_12周 ' ;
		$columns['ult_16wk']			= '唐筛B超_18周' ;
		$columns['ult_24wk']			= '大排畸B超_24周' ;
		$columns['ult_32wk']			= '小排畸B超_32周' ;
		$columns['ult_37wk']			= '临产B超_37周' ;
		$this->column_parameters = array(
			'no1',
			'ult_12wk',
			'ult_16wk',
			'ult_24wk',
			'ult_32wk',
			'ult_37wk',
		);
		return $columns;
	}

	function get_hidden_columns(){
		return array('id');
	}

	function column_default( $item, $column_name ){
		
		echo '<div id="'.$column_name.'_'.$item['id'].'">'.$item[$column_name].'</div>';
		
    }

    function no_items() {
        echo __('没有相关数据');
    }

}