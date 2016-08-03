<?php 
/**
*产前队列孕产前随访
**/
include_once(plugin_dir_path(__FILE__) .'class/class_hospital_db_extend.php');
include_once(plugin_dir_path(__FILE__) .'class/class_hospital_pre_birth_childbirth_status_list_table.php');
include_once(plugin_dir_path(__FILE__) .'class/class_hospital_pre_birth_pregnant_list_table.php');
include_once(plugin_dir_path(__FILE__) .'class/class_hospital_pre_birth_pregnant_middle_list_table.php');
include_once(plugin_dir_path(__FILE__) .'class/class_hospital_pre_birth_pregnant_b_list_table.php');
include_once(plugin_dir_path(__FILE__) .'class/class_hospital_pre_birth_health_manage_list_table.php');
class HospitalPreBirthPregnantTrack extends HospitalTrack{
	public $hos_pregnant_db, $is_mobile;
	public $hos_type;
	public $page_arr;
	public $childbirth_status_arr;
	public $user_name;

	function __construct(){
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	        spl_autoload_register( 'HospitalTrack::autoloadClass', true, true);
	    } else {
	        spl_autoload_register( 'HospitalTrack::autoloadClass' );
	    }
	    date_default_timezone_set('PRC');
		if( wp_is_mobile() )
			$this->is_mobile = true;

		$this->hos_pregnant_db = new Hospital_Db_Pregnant;
		$this->hos_type = 2;
		$this->pregnant_middle_fut2rem_arr =array(
			0 => '同意',
			1 => '失联',
		);
		$this->page_arr = array(
			0 => 10,
			1 => 20,
			2 => 50,
			3 => 100,
			);
		$this->pregnant_arr = array(
			'serumt1' => '早期血清',
			'plasma_bcellt1' => '早期血浆血细胞',
			'urinet1' => '早期尿',
			'pablood' => '父亲血',
			'serumt2' => '中期血清',
			'urinet2' => '中期尿',
			'urinet3_ent1' => '晚期尿-Ent1',

		);
		$this->pregnant_fut2rem_arr = array(
			0 => '同意',
			1 => '失联',
		);
		$this->childbirth_status_arr = array(
			'serumt3' 			=> '晚期血清',
			'plasma_bcellt3' 	=> '晚期血浆血细胞',
			'cbser' 			=> '脐血清',
			'cbpla_bcl' 		=> '脐血浆血细胞',
			'placent' 			=> '胎盘',
			'ubcord' 			=> '脐带',
			'nmtube' 			=> '胎粪',
			'urinet3_ent2' => '晚期尿-Ent2',
		);
		$this->pregnant_midlle_option_arr = array(
			'serumt2' => '中期血清',
			'urinet2' => '中期尿',
		);

		$this->health_manage_arr = array(
			'lbw'				=> '低出生体重',
			'macro'				=> '巨大儿',
			'preterm'			=> '早产',
			'sga'				=> '小于胎龄儿',
			'lga'				=> '大于胎龄儿',
			'neobd_001'			=> '新生儿畸形',
			'matpih'			=> '妊娠期高血压',
			'matgdm'			=> '妊娠期糖尿病',
		);
		$current_user = wp_get_current_user();
		$this->user_name = $current_user->display_name;
	}

	function load_pre_birth_pregnant_list_view($pass){
		
		$view = 'pre-birth-pregnant-list';
		$list = new Hospital_Pre_Birth_Pregnant_List_Table();
		$list->prepare_items();
		$data['list'] = $list;
		$this->load_view('pre-birth-pregnant', $view, $data);
	}


	function hospital_pregnant_ajax(){
		$group = $_POST['group'];
		$plugin = new HospitalTrack;
		$pregnantplugins = new HospitalPreBirthPregnantTrack;
		switch($group){
			case 'pregnant':
				$list = new Hospital_Pre_Birth_Pregnant_List_Table();
			break;

			case 'childbirth_status':
				$list = new Hospital_Pre_Birth_Childbirth_Status_List_Table();
			break;

			case 'pregnant_middle':
				$list = new Hospital_Pre_Birth_Pregnant_Midlle_List_Table();
			break;

			case 'health_manage':
				$list = new Hospital_Pre_Birth_Health_Manage_List_Table();
			break;
			default:
			#code
			break;
		}
		
		$list->prepare_items();
		ob_start();
		$plugin->get_table_nav_top_view( $list );
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_nav_top'] = $content;

		ob_start();
		$list->display();
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_content'] = $content;

		ob_start();
		$plugin->get_table_nav_bottom_view( $list );
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_nav_bottom'] = $content;

		echo json_encode( $response );
		exit();

	}

	function hospital_pre_birth_pregnant_edit_ajax(){
		$plugin = new HospitalTrack;
		$pregnant_plugin = new HospitalPreBirthPregnantTrack;

		$id = $_POST['id'];
		$result = $pregnant_plugin->hos_pregnant_db->get_pregnant_info_by_id($id);
		$form_content = $pregnant_plugin->get_pre_birth_pregnant_form($result);
		echo json_encode($form_content);
		exit();
	}

	function get_pre_birth_pregnant_form($item){
		$pregnant_options_arr = array('serumt1', 'plasma_bcellt1', 'urinet1', 'pablood', 'serumt2', 'urinet2', 'urinet3_ent1');
		$data['pregnant_options_arr'] = $pregnant_options_arr;
		$data['item'] = $item ;
		// error_log(var_export($item,true));
		ob_start();
		$this->load_view( 'pre-birth-pregnant', 'pre-birth-pregnant-form' ,$data );
		$pregnant_html = ob_get_contents();
		ob_end_clean();
		return $pregnant_html;
	}
	
	function hospital_pre_birth_pregnant_save_ajax(){
		$plugin = new HospitalTrack;
		$pregnant_plugin = new HospitalPreBirthPregnantTrack;
		$response = '';
		$id = $_POST['id'];
		//$pregnant_data['name'] = $_POST['name'];

		$lmp = $_POST['lmp'] ;
		$tel1 = $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		$serumt1 = $_POST['serumt1'];
		$plasma_bcellt1 = $_POST['plasma_bcellt1'];
		$urinet1 = $_POST['urinet1'];
		$pablood = $_POST['pablood'];
		$serumt2 = $_POST['serumt2'];
		$urinet2 = $_POST['urinet2'];
		$urinet3_ent1 = $_POST['urinet3_ent1'];
		$fut2 = $_POST['fut2'];
		$fut2rem = $_POST['fut2rem'];
		$fut2er = $_POST['fut2er'];

		$where = array('id' => $id);
		$basic_data['no1'] 					= $_POST['no1'];
		// $pregnant_data['no1'] = $_POST['hos_pregnant_status']
		$basic_data['name'] 				= $_POST['name'];

		$basic_data['lmp'] 					= date( 'Y-m-d 12:00:00', strtotime( $lmp ) );
		$basic_data['pphone'] 				= $tel1;
		$basic_data['hphone'] 				= $tel2;
		$pregnant_data['serumt1']			= $serumt1;
		$pregnant_data['plasma_bcellt1'] 	= $plasma_bcellt1;
		$pregnant_data['urinet1'] 			= $urinet1;
		$pregnant_data['pablood'] 			= $pablood;
		$pregnant_data['serumt2'] 			= $serumt2;
		$pregnant_data['urinet2']		 	= $urinet2;
		$pregnant_data['urinet3_ent1'] 		= $urinet3_ent1;
		$pregnant_data['fut2'] 				= $fut2;
		$pregnant_data['fut2rem'] 			= $fut2rem;
		$pregnant_data['fut2er'] 			= $fut2er;
		
        $plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );
      
        $pregnant_plugin->hos_pregnant_db->update_pre_birth_pregnant_info($pregnant_data, $id);
		echo json_decode($response);
		exit();
	}

	function load_pre_birth_childbirth_status_list_view($pass){
		$view = 'pre-birth-childbirth-status-list';
		$list = new Hospital_Pre_Birth_Childbirth_Status_List_Table();
		$list->prepare_items();
		$data['list'] = $list;
		$this->load_view('pre-birth-childbirth', $view, $data);
	}

	function hospital_pre_birth_childbirth_status_edit_ajax(){
		$plugin = new HospitalTrack;
		$pregnantplugin = new HospitalPreBirthPregnantTrack;
		$id = $_POST['id'];
		$result = $pregnantplugin->hos_pregnant_db->get_childbirth_info_by_id($id);
		$form_content = $pregnantplugin->get_pre_birth_childbirth_status_form($result);
		echo json_encode($form_content);
		exit();
	}

	function get_pre_birth_childbirth_status_form($item){
		$childbirth_option_arr = array('serumt3', 'plasma_bcellt3', 'cbser' ,'cbpla_bcl', 'placent', 'ubcord', 'nmtube', 'urinet3_ent2');
		$data['childbirth_options_arr'] = $childbirth_option_arr;
		$data['item'] = $item;
		ob_start();
		$this->load_view('pre-birth-childbirth', 'pre-birth-childbirth-status-form', $data);
		$childbirth_html = ob_get_contents();
		ob_end_clean();
		return $childbirth_html;
	}

	function hospital_pre_birth_childbirth_status_save_ajax(){
		$plugin = new HospitalTrack;
		$pregnant_plugin = new HospitalPreBirthPregnantTrack;
		$response = '';
		$id = $_POST['id'];
		$where = array('id' => $id);
		// error_log(var_export($_POST['form_data'],true));
		foreach( $_POST['form_data'] as $item ){
			$childbirth_data[$item['name']] = $item['value'];
		}
		
		$basic_data['no1'] = $childbirth_data['no1'] ;
		$basic_data['name'] = $childbirth_data['name'] ;
		if( empty($childbirth_data['lmp'])){
			$basic_data['lmp'] = '' ;
		}else{
			$basic_data['lmp'] = date('Y-m-d 12:00:00',strtotime($childbirth_data['lmp'])) ;
		}
		if( empty($childbirth_data['dedate'])){
			$basic_data['dedate'] = '' ;
		}else{
			$basic_data['dedate'] = date('Y-m-d 12:00:00',strtotime($childbirth_data['dedate'])) ;
		}
		//$basic_data['lmp'] = date('Y-m-d 12:00:00',strtotime($childbirth_data['lmp'])) ;
		
		$basic_data['no2'] = $childbirth_data['no2'] ;


		$childbirth_status_data['serumt3'] = $childbirth_data['serumt3'] ;
		$childbirth_status_data['plasma_bcellt3'] = $childbirth_data['plasma_bcellt3'] ;
		$childbirth_status_data['cbser'] = $childbirth_data['cbser'] ;
		$childbirth_status_data['cbpla_bcl'] = $childbirth_data['cbpla_bcl'] ;
		$childbirth_status_data['placent'] = $childbirth_data['placent'] ;
		$childbirth_status_data['ubcord'] = $childbirth_data['ubcord'] ;
		$childbirth_status_data['nmtube'] = $childbirth_data['nmtube'] ;
		$childbirth_status_data['urinet3_ent2'] = $childbirth_data['urinet3_ent2'] ;

 		$plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );

		$pregnant_plugin->hos_pregnant_db->update_pre_birth_childbirth_info($childbirth_status_data, $id);

		echo json_decode($response);
		exit();
	}

	function load_pre_birth_pregnant_middle_list_view($pass){
		$view = 'pre-birth-pregnant-middle-list';
		$list = new Hospital_Pre_Birth_Pregnant_Midlle_List_Table();
		$list->prepare_items();
		$data['list'] = $list;
		$this->load_view('pre-birth-pregnant', $view, $data);
	}

	function hospital_pre_birth_pregnant_middle_edit_ajax(){
		$plugin = new HospitalTrack;
		$pregnantplugin = new HospitalPreBirthPregnantTrack;
		$id = $_POST['id'];
		$result = $pregnantplugin->hos_pregnant_db->get_pregnant_info_by_id($id);
		$form_content = $pregnantplugin->get_pre_birth_pregnant_middle_form($result);
		echo json_encode($form_content);
		exit();
	}

	function get_pre_birth_pregnant_middle_form($item){
		$pregnant_midlle_option_arr = array('serumt2', 'urinet2');
		$data['pregnant_midlle_option_arr'] = $pregnant_midlle_option_arr;
		$data['item'] = $item;
		ob_start();
		$this->load_view('pre-birth-pregnant', 'pre-birth-pregnant-middle-form', $data);
		$pregnant_middle_html = ob_get_contents();
		ob_end_clean();
		return $pregnant_middle_html;
	}

	function hospital_pre_birth_pregnant_middle_save_ajax(){
		$plugin = new HospitalTrack;
		$pregnant_plugin = new HospitalPreBirthPregnantTrack;
		$response = '';
		$id = $_POST['id'];
		$where = array('id' => $id);
		// error_log(var_export($_POST['form_data'],true));
		foreach( $_POST['form_data'] as $item ){
			$pregnant_data[$item['name']] = $item['value'];
		}
		
		$basic_data['no1'] = $pregnant_data['no1'] ;
		$basic_data['name'] = $pregnant_data['name'] ;
		
		$basic_data['pphone'] = $pregnant_data['pphone'] ;
		$basic_data['hphone'] = $pregnant_data['hphone'] ;


		$pregnant_middle_data['serumt2'] = $pregnant_data['serumt2'] ;
		$pregnant_middle_data['urinet2'] = $pregnant_data['urinet2'] ;
		$pregnant_middle_data['fut2rem'] = $pregnant_data['fut2rem'] ;

 		$plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );

		$pregnant_plugin->hos_pregnant_db->update_pre_birth_pregnant_info($pregnant_middle_data, $id);

		echo json_decode($response);
		exit();
	}

	function load_pre_birth_pregnant_b_list_view($pass){
		$view = 'pre-birth-pregnant-b-list';
		$list = new Hospital_Pre_Birth_Pregnant_B_List_Table();
		$list->prepare_items();
		$data['list'] = $list;
		$this->load_view('pre-birth-pregnant', $view, $data );
	}

	function get_table_content_view($data){
		$view = 'pre-birth-pregnant-b-table';
		$this->load_view('pre-birth-pregnant',$view,$data);
	}

	function hospital_pregnant_b_ajax(){
		
		$plugin = new HospitalTrack;
		$pregnantplugin = new HospitalPreBirthPregnantTrack;
		$list = new Hospital_Pre_Birth_Pregnant_B_List_Table();
		$list->prepare_items();
		ob_start();
		$plugin->get_table_nav_top_view($list);
		$content = ob_get_contents();
		ob_end_clean();
		$response['hos_table_nav_top'] = $content;

		ob_start();
		$pregnantplugin->get_table_content_view($list);
		$content = ob_get_contents();
		ob_end_clean();
		$response['hos_table_content'] = $content;

		ob_start();
		$plugin->get_table_nav_bottom_view($list);
		$content = ob_get_contents();
		ob_end_clean();
		$response['hos_table_nav_bottom'] = $content;

		echo json_encode($response);
		exit();
	}

	function load_pre_birth_health_manage_list_view($pass){
		$list = new Hospital_Pre_Birth_Health_Manage_List_Table();
		$list->prepare_items();
		$data['list'] = $list;
		$view = 'pre-birth-health-manage-list';
		$this->load_view('pre-birth-health', $view, $data);
	}


	function hospital_pre_birth_health_manage_import_ajax(){
		$plugin = new HospitalTrack;
		$pregnantplugin = new HospitalPreBirthPregnantTrack;
		$response = array();
		$response['error'] = '';
		require_once(plugin_dir_path(__FILE__).'/class/PHPExcel.php');
		// error_log(var_export($_FILES,true));
		$need_columns = array('no1');
		$allow_columns = array('no1', 'name', 'ult_16wk', 'var59', 'bw_p90gs', 'bw_p10gs', 'lbw', 'macro', 'preterm', 'sga', 'lga', 'neobd_001', 'matpih', 'matgdm');
		$file_ext = explode( '.', $_FILES['health_manage_import']['name'] );
        $file_ext = $file_ext[ count( $file_ext ) - 1 ];

        if( $file_ext == 'xlsx' || $file_ext == 'xls' ){
            $PHPExcel = PHPExcel_IOFactory::load( $_FILES['health_manage_import']['tmp_name'] );
        }else if( $file_ext == 'csv' ){
            //$PHPExcel = PHPExcel_IOFactory::load($uploaded_file);
            $objReader = PHPExcel_IOFactory::createReader('CSV')
                ->setDelimiter(',')
                ->setEnclosure('"')
                ->setSheetIndex(0);
            $PHPExcel = $objReader->load( $_FILES['health_manage_import']['tmp_name'] );
        }

        $sheet = $PHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		//$verify_name = $sheet->getCell('A2')->getValue();

		$upload_field_names = array();

		for ( $column = 'A'; $column != 'SV'; $column++ ) {//列数是以A列开始
		    $value = $sheet->getCell($column.'2')->getFormattedValue();

		    if( !in_array( $value, $allow_columns ) )
		        continue;

		    if( empty( $value ) )
		        break;

		    $upload_field_names[$column] = trim( $value );
		}
		//echo '<pre>';print_r($upload_field_names);echo '</pre>';exit();
		foreach ( $need_columns as $meta ) {
		    if( !in_array( $meta, $upload_field_names ) ) {
		        if( empty( $response['error'] ) ) {
		            $response['error'] = '文件未上传,缺失该列:'. $meta;
		        }else {
		            $response['error'].= ','. $meta;
		        }
		    }
		}

		if( !empty( $response['error'] ) ) {
		    echo json_encode( $response );
		    exit();
		}

		$form_infos = array();

		$j = 0;
		for ( $row = 3; $row <= $highestRow; $row++ ) {
		    $first_value = $sheet->getCell('A'.$row)->getFormattedValue();
		    if( empty( $first_value ) )
		        break;

		    foreach ( $upload_field_names as $column => $column_name ) {
		        $form_infos[$j][$column_name] = $sheet->getCell($column.$row)->getFormattedValue();
		    }
		    $j++;
		}
		if( count( $form_infos ) < 1 ) {
		    $response['error'] = '未找到有效的导入数据.';
		    echo json_encode( $response );
		    exit();
		}

		foreach( $form_infos as $item ){
			$item_id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

			$basic_data['name'] = $item['name'];

			if( $item_id < 1){
			 	$basic_data['no1'] = $item['no1'];
			 	 $item_id = $plugin->hos_db->add_pre_birth_basic_info( $basic_data );
			}else{
			 	$where = array( 'id' => $item_id );
            	$plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );
			}

			$health_data['var59'] =$item['var59'];
			$health_data['bw_p90gs'] =$item['bw_p90gs'];
			$health_data['bw_p10gs'] =$item['bw_p10gs'];
			$health_data['lbw'] =$item['lbw'];
			$health_data['macro'] =$item['macro'];
			$health_data['preterm'] =$item['preterm'];
			$health_data['sga'] =$item['sga'];
			$health_data['lga'] =$item['lga'];
			$health_data['neobd_001'] =$item['meobd_001'];
			$health_data['matpih'] =$item['matpih'];
			$health_data['matgdm'] =$item['matgdm'];
			$pregnantplugin->hos_pregnant_db->update_pre_birth_health_info($health_data,$item_id);

		}
		echo json_encode( $response );
        exit();
	}

	function hospital_pre_birth_health_manage_edit_ajax(){
		$plugin = new HospitalTrack;
		$pregnantplugin = new HospitalPreBirthPregnantTrack;
		$id = $_POST['id'];
		$result = $pregnantplugin->hos_pregnant_db->get_health_info_by_id($id);
		$form_content = $pregnantplugin->get_pre_birth_health_manage_form($result);
		echo json_encode($form_content);
		exit();
	}

	function get_pre_birth_health_manage_form($item){
		$health_manage_option_arr = array('lbw', 'macro', 'preterm', 'sga', 'lga', 'neobd_001', 'matpih', 'matgdm');
		$data['health_manage_option_arr'] = $health_manage_option_arr;
		$data['item'] = $item;
		ob_start();
		$this->load_view('pre-birth-health', 'pre-birth-health-manage-form', $data);
		$health_manage_html = ob_get_contents();
		ob_end_clean();
		return $health_manage_html;
	}

	function hospital_pre_birth_health_manage_save_ajax(){

		$plugin = new HospitalTrack;
		$pregnant_plugin = new HospitalPreBirthPregnantTrack;
		$response = '';
		$id = $_POST['id'];
		$where = array('id' => $id);
		// error_log(var_export($_POST['form_data'],true));
		foreach( $_POST['form_data'] as $item ){
			$health_data[$item['name']] = $item['value'];
		}
		
		$basic_data['no1'] = $health_data['no1'] ;
		$basic_data['name'] = $health_data['name'] ;
	

		$health_manage_data['var59'] = $health_data['var59'] ;
		$health_manage_data['bw_p90gs'] = $health_data['bw_p90gs'] ;
		$health_manage_data['bw_p10gs'] = $health_data['bw_p10gs'] ;
		$health_manage_data['lbw'] = $health_data['lbw'] ;
		$health_manage_data['macro'] = $health_data['macro'] ;
		$health_manage_data['preterm'] = $health_data['preterm'] ;
		$health_manage_data['sga'] = $health_data['sga'] ;
		$health_manage_data['lga'] = $health_data['lga'] ;
		$health_manage_data['neobd_001'] = $health_data['neobd_001'] ;
		$health_manage_data['matpih'] = $health_data['matpih'] ;
		$health_manage_data['matgdm'] = $health_data['matgdm'] ;

 		$plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );

		$pregnant_plugin->hos_pregnant_db->update_pre_birth_health_info($health_manage_data, $id);

		echo json_decode($response);
		exit();
	}
}
add_action('wp_ajax_hospital-pregnant-ajax',array('HospitalPreBirthPregnantTrack','hospital_pregnant_ajax'));
add_action ('wp_ajax_hospital-pre-birth-pregnant-edit-ajax', array( 'HospitalPreBirthPregnantTrack', 'hospital_pre_birth_pregnant_edit_ajax'));
add_action ('wp_ajax_hospital-pre-birth-pregnant-save-ajax', array( 'HospitalPreBirthPregnantTrack', 'hospital_pre_birth_pregnant_save_ajax'));

add_action('wp_ajax_hospital-pre-birth-childbirth-status-edit-ajax', array('HospitalPreBirthPregnantTrack', 'hospital_pre_birth_childbirth_status_edit_ajax'));
add_action('wp_ajax_hospital-pre-birth-childbirth-status-save-ajax', array('HospitalPreBirthPregnantTrack', 'hospital_pre_birth_childbirth_status_save_ajax') );

add_action('wp_ajax_hospital-pre-birth-pregnant-middle-edit-ajax', array('HospitalPreBirthPregnantTrack', 'hospital_pre_birth_pregnant_middle_edit_ajax') );
add_action('wp_ajax_hospital-pre-birth-pregnant-middle-save-ajax', array('HospitalPreBirthPregnantTrack', 'hospital_pre_birth_pregnant_middle_save_ajax') );

add_action('wp_ajax_hospital-pregnant-b-ajax', array('HospitalPreBirthPregnantTrack', 'hospital_pregnant_b_ajax'));
 

add_action('wp_ajax_hospital-pre-birth-health-manage-import-ajax', array('HospitalPreBirthPregnantTrack','hospital_pre_birth_health_manage_import_ajax'));
add_action('wp_ajax_hospital-pre-birth-health-manage-edit-ajax', array('HospitalPreBirthPregnantTrack','hospital_pre_birth_health_manage_edit_ajax') );
add_action('wp_ajax_hospital-pre-birth-health-manage-save-ajax', array('HospitalPreBirthPregnantTrack', 'hospital_pre_birth_health_manage_save_ajax') );