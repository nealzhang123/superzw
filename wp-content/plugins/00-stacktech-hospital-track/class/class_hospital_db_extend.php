<?php  
/**
*产前队列孕产前随访数据表
**/
class Hospital_Db_Pregnant {
	function create_tables(){
		global $wpdb;
		require_once(ABSPATH."wp-admin/includes/upgrade.php");

		//=====================================================
		//==================出生队列============================
		//=====================================================

		//孕三期 数据 + 孕中期数据
		$hos_pre_birth_three_pregnant_info = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
		$sql = "CREATE TABLE $hos_pre_birth_three_pregnant_info(
			id int(10) not null AUTO_INCREMENT,
			-- no1 varchar(20) not null ,
			-- name varchar(50),
			-- lmp datetime,
			-- tel1 varchar(30),
			-- tel2 varchar(30),
			serumt1 smallint,
			plasma_bcellt1 smallint,
			urinet1 smallint,
			pablood smallint,
			serumt2 smallint,
			urinet2 smallint,
			urinet3_ent1 smallint,
			fut2 varchar(20),
			fut2rem varchar(20),
			fut2er varchar(30),
			PRIMARY KEY (id)
		)";
		dbDelta($sql);
		
		//分娩状态数据
		$hos_pre_birth_childbirth_info = $wpdb->prefix . 'hos_pre_birth_childbirth_info';
		$sql = "CREATE TABLE $hos_pre_birth_childbirth_info(
			id int(10) not null AUTO_INCREMENT,
			-- no1 varchar(20) not null,
			-- name varchar(50),
			-- lmp datetime,
			-- dedate datetime,
			-- m_id varchar(50),
			-- pphone varchar(30),
			-- hphone varchar(30),
			-- dephone varchar(30),
			-- no2 varchar(20) not null,
			serumt3 smallint,
			plasma_bcellt3 smallint,
			cbser smallint,
			cbpla_bcl smallint,
			placent smallint,
			ubcord smallint,
			nmtube smallint,
			urinet3_ent2 smallint,
			PRIMARY KEY (id)
		)";
		dbDelta($sql);

		//孕期B超检查数据
		$hos_pre_birth_b_pregnant_info = $wpdb->prefix . 'hos_pre_birth_b_pregnant_info';
		$sql = "CREATE TABLE $hos_pre_birth_b_pregnant_info(
			id int(10) not null AUTO_INCREMENT,
			-- no2 varchar(20) not null,
			no1 varchar(20) not null,
			-- lmp datetime,
			ult_checkdate datetime,
			crl varchar(6) default '',
			PRIMARY KEY (id)
		)";
		dbDelta($sql);

		//产前队列健康管理
		$hos_pre_birth_health_manage_info = $wpdb->prefix . 'hos_pre_birth_health_manage_info';
		$sql = "CREATE TABLE $hos_pre_birth_health_manage_info(
			id int(10) not null auto_increment,
			var59 varchar(10),
			bw_p90gs varchar(15),
			bw_p10gs varchar(15),
			lbw smallint,
			macro smallint,
			preterm smallint,
			sga smallint,
			lga smallint,
			neobd_001 smallint,
			matpih smallint,
			matgdm smallint,
			primary key (id) 
		)";
		dbDelta($sql);
	}
	
	// function get_pre_birth_pregnant_id_by_no1($no1){
	// 	global $wpdb;
	// 	$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info'; 
	// 	//$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
	// 	$sql = 'SELECT id FROM ' . $table_basic . ' WHERE no1="' . esc_attr($no1) . '"';
	// 	$id = $wpdb->get_var($sql);
	// 	return $id;
	// }

	// function add_pre_birth_pregnant_info($data){
	// 	global $wpdb;
	// 	$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
	// 	$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info'; 
	// 	$wpdb->insert($table_basic, $data_basic);
	// 	$wpdb->insert($table, $data);
	// 	return $wpdb->insert_id;
	// }

	function update_pre_birth_pregnant_info($data, $id){
		// error_log(var_export($data,true));
		// error_log($id);
		global $wpdb;
		$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
		$sql = 'SELECT * FROM ' . $table . ' WHERE id = "' . $id .'"';
		$result = $wpdb->get_row($sql);
		if(empty($result)){
			$data['id'] = $id;
			$wpdb->insert($table,$data);
		}else{
			
			$where = array('id' => $id);
			$wpdb->update($table,$data,$where);
		}
		
	}

	// function get_pre_birth_childbirth_id_by_no($no1, $no2){
	// 	global $wpdb;
	// 	$table =$wpdb->prefix . 'hos_pre_birth_childbirth_info';
	// 	$sql = 'SELECT id FROM ' . $table . ' WHERE no1="' . esc_attr($no1) . '" AND no2= "' . esc_attr($no2) . '"';
	// 	$id = $wpdb->get_var($sql);
	// 	return $id;
	// }

	// function add_pre_birth_childbirth_info($data){
	// 	global $wpdb;
	// 	$table = $wpdb->prefix . 'hos_pre_birth_childbirth_info';
	// 	$wpdb->insert($table, $data);
	// 	return $wpdb->insert_id;
	// }

	function update_pre_birth_childbirth_info($data, $id){
		global $wpdb;
		$table = $wpdb->prefix . 'hos_pre_birth_childbirth_info';
		$sql = 'SELECT * FROM ' . $table . ' WHERE id = "' . $id .'"';
		$result = $wpdb->get_row($sql);
		if(empty($result)){
			$data['id'] = $id;
			$wpdb->insert($table,$data);
		}else{
			
			$where = array('id' => $id);
			$wpdb->update($table,$data,$where);
		}
	
	}


	// function get_pre_birth_b_pregnant_id($no1, $ult_checkdate){
	// 	global $wpdb;
	// 	$table =$wpdb->prefix . 'hos_pre_birth_b_pregnant_info';
	// 	$sql = 'SELECT id FROM ' . $table . ' WHERE no1="' . esc_attr($no1) . '" AND ult_checkdate="' . $ult_checkdate .'"';
	// 	$id = $wpdb->get_var($sql);
	// 	return $id;
	// }

	// function add_pre_birth_b_pregnant_info($data){
	// 	global $wpdb;
	// 	$table = $wpdb->prefix . 'hos_pre_birth_b_pregnant_info';
	// 	$wpdb->insert($table, $data);
	// 	return $wpdb->insert_id;
	// }

	function update_pre_birth_b_pregnant_info($data, $no1){
		global $wpdb;
		$ult_checkdate = $data['ult_checkdate'];
		$table = $wpdb->prefix . 'hos_pre_birth_b_pregnant_info';
		$id = 'SELECT id FROM ' . $table . ' WHERE no1 = "' . $no1 .'"AND ult_checkdate="' . $ult_checkdate .'"';
	
		$result = $wpdb->get_var($id);

		if(empty($result)){
			// $data['no1'] = $no1;
			$wpdb->insert($table,$data);
		}else{
			
			$where = array('id' => $result);
			$wpdb->update($table,$data,$where);
		}
	}


	function get_pregnant_info_by_id($id){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
		$sql = 'SELECT * FROM '.$table_basic . ' basic inner join ' . $table . ' pregnant WHERE basic.id =  pregnant.id and pregnant.id = ' . $id ;
		$result = $wpdb->get_row($sql, ARRAY_A);
		return $result;
	}

	// function update_pre_birth_pregnant_info( $data, $where){
	// 	global $wpdb;
	// 	$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
	// 	$wpdb->update($table,$data,$where);
	// }
	function get_childbirth_info_by_id($id){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_childbirth_info';
		$sql = 'SELECT * FROM ' . $table_basic . ' basic inner join ' . $table . ' childbirth WHERE basic.id = childbirth.id  and childbirth.id = ' . $id ; 
		$result = $wpdb->get_row($sql, ARRAY_A);
		return $result;
	}

	function update_pre_birth_health_info( $data, $id){
		global $wpdb;
		$table = $wpdb->prefix . 'hos_pre_birth_health_manage_info';
		$sql = 'SELECT * FROM ' . $table . ' WHERE id = "' . $id .'"';
		$result = $wpdb->get_row($sql);
		if(empty($result)){
			$data['id'] = $id;
			$wpdb->insert($table,$data);
		}else{
			
			$where = array('id' => $id);
			$wpdb->update($table,$data,$where);
		}
	
	}

	function get_health_info_by_id($id){	
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_health_manage_info';
		$sql = 'SELECT * FROM ' . $table_basic . ' basic inner join '. $table . ' health on basic.id = health.id and  health.id = ' . $id ;
		$result = $wpdb->get_row($sql, ARRAY_A );
		return $result;
	}
}