<?php   
/*
Plugin Name: 00 stacktech crm
Plugin URI: http://www.etongapp.com
Description: stacktech crm
Version: 1.0
Author: Stacktech
Author URI: http://www.etongapp.com
License: GPL
*/
define( 'STACKTECH_CRM_VIEW_PATH', plugin_dir_path(__FILE__) . 'view/' );

class StacktechCrm {
	public $crm_db,$model;
	public $model_cus,$model_rec,$model_mem,$model_hol,$model_mail,$model_sms;
	public $customer_area;

	function __construct(){
		
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	        spl_autoload_register( 'StacktechCrm::autoloadClass', true, true);
	    } else {
	        spl_autoload_register( 'StacktechCrm::autoloadClass' );
	    }

		$this->crm_db        = new Stacktech_Crm_Db;
		$this->model_cus     = 'customer';
		$this->model_rec     = 'record';
		$this->model_hol     = 'holiday';
		$this->model_mem     = 'member';
		$this->model_mail    = 'mail';
		$this->model_sms     = 'sms';
		$this->customer_area = 1;

		date_default_timezone_set('PRC');
	}

	function autoloadClass($classname){
        $filename = plugin_dir_path( __FILE__ ).'class/class_'.strtolower($classname).'.php';
	    if (is_readable($filename)) {
	        require_once $filename;
	    }
    }

    function load_required_files() {
    	wp_enqueue_script( 'crm-bootstrap-min-script', 'http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js' );
		wp_enqueue_script( 'crm-main-script', plugin_dir_url( __FILE__ ) . 'js/crm_main.js' );
		wp_enqueue_script( 'crm-pnotify-script', 'http://cdn.bootcss.com/pnotify/3.0.0/pnotify.min.js' );
		wp_enqueue_script( 'crm-sweet-script', 'https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js' );
		wp_enqueue_script( 'crm-jquery-ui-script', 'https://cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.js' );
		wp_enqueue_script( 'crm-jquery-ui-add-script', 'https://cdn.bootcss.com/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js');



		wp_enqueue_style( 'crm-bootstrap-min-style', 'http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css' );//bug 样式错位
		wp_enqueue_style( 'crm-pnotify-min-style', 'https://cdn.bootcss.com/pnotify/3.0.0/pnotify.min.css' );
		wp_enqueue_style( 'crm-pnotify-brighttheme-style', 'https://cdn.bootcss.com/pnotify/3.0.0/pnotify.brighttheme.min.css' );
		wp_enqueue_style( 'crm-font-awesome-style', 'https://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css' );
		wp_enqueue_style( 'crm-sweet-style', 'https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css' );
		wp_enqueue_style( 'crm-jquery-ui-style', 'https://cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.css' );
		wp_enqueue_style( 'crm-jquery-ui-add-style', 'https://cdn.bootcss.com/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.css' );
		wp_enqueue_style( 'crm-main-style', plugin_dir_url( __FILE__ ) . 'css/crm_main.css' );

    }

    //控制面板页面
    function crm_setting(){

		$this->load_required_files();

    	$action = '';
    	if( isset( $_REQUEST['action'] ) )
    		$action = $_REQUEST['action'];
    	else
    		$action = 'drop_box';

    	//加载导航条视图
    	$this->load_view( 'setting', 'nav', array( 'action' => $action ) );

    	//加载模块视图
    	switch ($action) {
    		case 'drop_box':
    			$this->load_view( 'setting', 'dropbox' );
    			break;
    		
    		case 'sort_box':
    			$this->load_view( 'setting', 'sortbox' );
    			break;
    		default:
    			# code...
    			break;
    	}

    }

    //加载各个视图
    function load_view( $label, $box, $data = array() ){
    	$filename = $label . '_' . $box . '_view.php';

    	require_once( STACKTECH_CRM_VIEW_PATH . $filename );
    }

    //插件激活时,创建相应表,初始化数据
	function plugin_activation(){
		$plugin = new StacktechCrm;
		$plugin->crm_db->prepare_table();
	}

	//模块改变时,加载相应视图
	function crm_load_options_ajax(){
		$model = $_REQUEST['model'];
		$box = $_REQUEST['box'];

		$plugin = new StacktechCrm;

		switch ($box) {
			case 'dropbox':
				$plugin->get_form_dropbox_options( $model );
				break;

			case 'sortbox':
				$plugin->get_form_sortbox_options( $model );
				break;
			
			default:
				# code...
				break;
		}
		
		exit();
	}

	//下拉框的模块改变时,加载相应视图
	function get_form_dropbox_options( $model ){
		$data = $this->crm_db->get_form_option_by_form_type( $model, 'select' );
		$data['model'] = $model;
		$this->load_view( 'setting', 'dropbox_options', $data );

	}

	//页面布局的模块改变时,加载相应视图
	function get_form_sortbox_options( $model ){
		$areas = $this->crm_db->get_model_areas( $model );

		foreach ($areas as $key => $area) {
			$areas[$key]['area_options'] = $this->crm_db->get_form_option( $model, $area['area_id'] );
		}
		
		$areas['model'] = $model;
		//echo '<pre>';print_r($areas);echo '</pre>';exit();
		$this->load_view( 'setting', 'sortbox_options', $areas );
		
		//exit();
	}

	//更新下拉框内容
	function crm_option_save_ajax(){
		$plugin = new StacktechCrm;

		if( !isset($_POST['box']) || empty($_POST['box']) )
			return;

		$data = array();

		foreach ($_POST['data'] as $item) {
			$data[$item['name']] = $item['value'];
		}

		switch ($_POST['box']) {
			case 'dropbox':
				$options = explode( "\r\n", $data['option_value'] );

				$option_values = array();
				foreach ($options as $key => $value) {
					if( empty($value) )
						continue;
		    		$option_values[] = $value;
				}
				
				//error_log(var_export($option_values,true));
				$plugin->crm_db->update_option_values_by_option_id( $data['option_id'], $option_values );

				echo json_encode($option_values);
				break;

			case 'sortbox':
				$option['is_required'] = $data['is_required'];
				$option['title']       = $data['title'];
				$option['sort']        = $data['sort'];
				$option['is_hidden']   = $data['is_hidden'];
				
				$where['option_id']    = $data['option_id'];
				$where['model']        = $data['model'];

				$plugin->crm_db->update_form_option( $option, $where );

				echo json_encode($option);
				break;
			
			default:
				# code...
				break;
		}
		
		exit();
	}

	function crm_customer(){
		$this->load_required_files();

		$this->model = $this->model_cus;
		$data['categories'] = $this->crm_db->get_all_categories( $this->model );
		//echo '<pre>';print_r($categories);echo '</pre>';
		$this->load_view( 'customer', 'main', $data );
	}

	function get_list_content_by_category( $cate_id = 0 ) {

		switch ( $this->model ) {
			case $this->model_cus:
				$customer_list = new Crm_Customer_List( $cate_id );
				$customer_list->prepare_items();
				$customer_list->display();

				break;
			
			case $this->model_mem:
				$customer_list = new Crm_Member_List( $cate_id );
				$customer_list->prepare_items();
				$customer_list->display();

				break;

			case $this->model_rec:
				$record_list = new Crm_Record_List( $cate_id );
				$record_list->prepare_items();
				$record_list->display();

				break;

			case $this->model_hol:
				$holiday_list = new Crm_Holiday_List( $cate_id );
				$holiday_list->prepare_items();
				$holiday_list->display();

				break;

			case $this->model_mail:
				$mail_list = new Crm_Mail_List( $cate_id );
				$mail_list->prepare_items();
				$mail_list->display();

				break;

			case $this->model_sms:
				$sms_list = new Crm_SMS_List( $cate_id );
				$sms_list->prepare_items();
				$sms_list->display();

				break;

			default:
				# code...
				break;
		}
		//echo '<pre>';print_r($customers);echo '</pre>';
	}

	function get_site_admins() {
		$users = get_users( array( 'role' => 'administrator' ) );
		$site_admins = array();

		foreach ($users as $key => $user) {
			$site_admins[$key]['user_id']      = $user->ID;
			$site_admins[$key]['user_name']    = $user->data->display_name;
			
			$site_admins[$key]['option_key']   = $user->ID;
			$site_admins[$key]['option_value'] = $user->data->display_name;
		}
		return $site_admins;
	}

	function get_site_users() {
		$users = get_users();

		$site_users = array();

		foreach ($users as $key => $user) {
			$site_users[$key]['user_id']      = $user->ID;
			$site_users[$key]['user_name']    = $user->data->display_name;
			
			$site_users[$key]['option_key']   = $user->ID;
			$site_users[$key]['option_value'] = $user->data->display_name;
		}
		return $site_users;
	}

	function crm_edit_model() {
		$this->load_required_files();

		if( !isset( $_REQUEST['model'] ) )
			return;
		else
			$this->model = $_REQUEST['model'];

		$pid = '';
		if( isset( $_REQUEST['pid'] ) && $_REQUEST['pid'] > 0 )
			$pid = $_REQUEST['pid'];

		//保存填入信息
		if( count( $_POST ) > 0 ){
			switch ( $this->model ) {
				case $this->model_cus:
					$pid = $this->customer_save_info();
					//wp_redirect( admin_url('admin.php?page=crm_customer') ); 
					//exit;
					break;

				case $this->model_mem:
					$pid = $this->member_save_info();

					break;

				case $this->model_rec:
					$pid = $this->record_save_info();

					break;

				case $this->model_hol:
					$pid = $this->holiday_save_info();

					break;
				
				default:
					# code...
					break;
			}
		}

		$areas = $this->crm_db->get_model_areas( $this->model );

		foreach ($areas as $key => $area) {
			$areas[$key]['area_options'] = $this->get_area_options( $area['area_id'], $pid );
		}

		//得到各个模块资料数据
		if( !empty( $pid ) ) {
			switch ( $this->model ) {
				case $this->model_cus:
					$customer = $this->crm_db->get_crm_customer_info_by_id( $pid );
					$areas['customer'] = $customer;
					$members = $this->crm_db->get_crm_members_by_customer_id( $pid );
					$areas['members'] = $members;

					break;

				case $this->model_mem:
					$member = $this->crm_db->get_crm_member_by_member_id( $pid );
					$areas['member'] = $member;

					break;

				case $this->model_rec:
					$record = $this->crm_db->get_crm_record_by_record_id( $pid );
					$areas['record'] = $record;

					break;

				case $this->model_hol:
					$holiday = $this->crm_db->get_crm_holiday_by_holiday_id( $pid );
					$areas['holiday'] = $holiday;

					break;
				
				default:
					# code...
					break;
			}
		}
		//echo '<pre>';print_r($areas);echo '</pre>';exit();
		switch ( $this->model ) {
			case $this->model_cus:
				$areas['return_url'] = admin_url( 'admin.php?page=crm_customer' );
				break;

			case $this->model_mem:
				$areas['return_url'] = admin_url( 'admin.php?page=crm_member' );
				break;

			case $this->model_rec:
				$areas['return_url'] = admin_url( 'admin.php?page=crm_record' );
				break;

			case $this->model_hol:
				$areas['return_url'] = admin_url( 'admin.php?page=crm_holiday' );
				break;
			
			default:
				# code...
				break;
		}
		$areas['pid'] = $pid;
		//echo '<pre>';print_r($areas);echo '</pre>';exit();
		$this->load_view( $this->model, 'edit', $areas );
	}

	function customer_save_info() {
		//echo '<pre>';print_r($_POST);echo '</pre>';exit();
		global $current_user;

		$pid = $_POST['pid'];

		$options_arr = array( 'customer_name', 'customer_status', 'user_id', 'vip', 'tradedwish', 'fever', 'customer_from', 'customer_type', 'next_contact_time', 'site_url', 'country', 'province', 'city', 'bill_code', 'area', 'address', 'remark' );

		foreach ($options_arr as $option) {
			$data[$option] = $_POST[$option];
		}
		$data['creater'] = $current_user->ID;

		if( !empty( $pid ) ) {
			$this->crm_db->update_crm_customer_info( $data, $pid );
		}else{
			$pid = $this->crm_db->add_crm_customer_info( $data );
		}

		$member_count = count( $_POST['member_id'] );

		for ($i=0; $i < $member_count; $i++) { 
			$options_arr = array( 'member_name', 'user_id', 'sex', 'career', 'phone', 'email', 'telephone', 'fax', 'qq', 'msn', 'wangwang', 'weibo' );

			$member['customer_id'] = $pid;
			$member['creater']     = $current_user->ID;
			$member['member_id']   = $_POST['member_id'][$i];

			foreach ($options_arr as $option) {
				$member[$option] = $_POST[$option.'_mem'][$i];
			}

			if( empty( $member['member_id'] ) )
				$this->crm_db->add_crm_member_info( $member );
			else
				$this->crm_db->update_crm_member_info( $member, $member['member_id'] );
		}

		return $pid;
	}

	function member_save_info() {
		global $current_user;

		$pid = $_POST['pid'];

		$options_arr = array( 'member_name', 'customer_id', 'user_id', 'sex', 'career', 'phone', 'email', 'telephone', 'fax', 'qq', 'msn', 'wangwang', 'weibo', 'remark' );

		foreach ($options_arr as $option) {
			$data[$option] = $_POST[$option];
		}
		$data['creater'] = $current_user->ID;

		if( !empty( $pid ) ) {
			$this->crm_db->update_crm_member_info( $data, $pid );
			$member_id = $pid;
		}else{
			$member_id = $this->crm_db->add_crm_member_info( $data );
		}

		return $member_id;
	}

	function record_save_info() {
		global $current_user;

		$pid = $_POST['pid'];

		$options_arr = array( 'topic', 'customer_id', 'member_id', 'note_type', 'user_id', 'next_contact_time', 'customer_status', 'pre_notice', 'content' );

		foreach ($options_arr as $option) {
			$data[$option] = $_POST[$option];
		}
		$data['creater'] = $current_user->ID;

		if( !empty( $pid ) ) {
			$this->crm_db->update_crm_record_info( $data, $pid );
			$record_id = $pid;
		}else{
			$record_id = $this->crm_db->add_crm_record_info( $data );
		}

		return $record_id;
	}

	function holiday_save_info() {
		global $current_user;

		$pid = $_POST['pid'];

		//holiday_date need modify
		$options_arr = array( 'topic', 'user_id', 'customer_id', 'member_id', 'holiday_type', 'calendar_type', 'holiday_date', 'remark' );

		foreach ($options_arr as $option) {
			$data[$option] = $_POST[$option];
		}
		$data['creater'] = $current_user->ID;

		if( !empty( $pid ) ) {
			$this->crm_db->update_crm_holiday_info( $data, $pid );
			$holiday_id = $pid;
		}else{
			$holiday_id = $this->crm_db->add_crm_holiday_info( $data );
		}

		return $holiday_id;
	}

	function get_area_options( $area_id, $pid = '' ) {
		global $current_user;

		$options = $this->crm_db->get_form_option( $this->model, $area_id );

		foreach ($options as $key => $item) {
			if( $item['form_type'] == 'select' ) {
				$options[$key]['option_values'] = $this->crm_db->get_form_option_values_by_id( $item['option_id'] );
			}

			if( $item['form_type'] == 'date' ) {
				$options[$key]['default_value'] = date( 'Y-m-d' );
			}

			if( $item['form_type'] == 'special' ) {
				switch ( $item['special_key'] ) {
					case $this->crm_db->key_creater:
						$options[$key]['disabled']      = 1;
						$options[$key]['default_value'] = $current_user->ID;
						$options[$key]['form_type']     = 'select';
						$options[$key]['option_values'] = $this->get_site_admins();
												
						break;

					case $this->crm_db->key_charger:
						$options[$key]['default_value'] = $current_user->ID;
						$options[$key]['form_type']     = 'select';
						$options[$key]['option_values'] = $this->get_site_users();

						break;

					case $this->crm_db->key_customer:
						$options[$key]['default_value'] = 0;
						$options[$key]['form_type']     = 'select';
						$options[$key]['option_values'] = $this->crm_db->get_crm_customers_name();
						$empty_customer = array( 'customer_id' => '','customer_name' => '' );
						array_unshift( $options[$key]['option_values'], $empty_customer );

						break;

					case $this->crm_db->key_country:
						$options[$key]['default_value'] = 0;
						$options[$key]['form_type']     = 'select';
						$options[$key]['option_values'] = array(
							'0' => array(
								'option_key' => '0',
								'option_value' => '中国'
								)
							);

						break;

					case $this->crm_db->key_province:
						$options[$key]['default_value'] = 0;
						$options[$key]['form_type']     = 'select';
						$options[$key]['option_values'] = $this->crm_db->get_crm_provinces();

						break;

					case $this->crm_db->key_city:
						if( !empty( $pid ) ) {
							$customer_info = $this->crm_db->get_crm_customer_info_by_id( $pid );
							$province = $customer_info['province'];
							$city     = $customer_info['city'];
							$citys    = $this->crm_db->get_data_by_parent( $province );
						}else{
							$city = 0;
							$citys = $this->crm_db->get_data_by_parent( '110000' );
						}

						$options[$key]['default_value'] = $city;
						$options[$key]['form_type']     = 'select';
						$options[$key]['option_values'] = $citys;

						break;

					case $this->crm_db->key_area:
						if( !empty( $pid ) ) {
							$customer_info = $this->crm_db->get_crm_customer_info_by_id( $pid );
							$city  = $customer_info['city'];
							$area  = $customer_info['area'];
							$areas = $this->crm_db->get_data_by_parent( $city );
						}else{
							$area = 0;
							$areas = $this->crm_db->get_data_by_parent( '110100' );
						}

						$options[$key]['default_value'] = $area;
						$options[$key]['form_type']     = 'select';
						$options[$key]['option_values'] = $areas;

						break;

					default:
						# code...
						break;
				}
			}
		}

		return $options;
	}

	function get_member_list( $customer_id, $member_id = 0 ) {

		if( empty( $customer_id ) )
			return '';

		$members = $this->crm_db->get_crm_members_by_customer_id( $customer_id );
		//echo '<pre>';print_r($members);echo '</pre>';return;
		$content = '<select name="member_id">';

		foreach ($members as $member) {
			$select = ( $member['member_id'] == $member_id ) ? ' selected="selected"' : '';
			$content.= '<option value="' . $member['member_id'] . '" ' . $select . '>' . $member['member_name'] . '</option>';
		}
		$content.= '</select>';

		return $content;
	}

	function crm_customer_form_options_ajax() {
		$plugin = new StacktechCrm;
		$plugin->model = $plugin->model_cus;

		$options = $plugin->get_area_options( $plugin->customer_area );

		//echo '<pre>';print_r($options);echo '</pre>';
		$plugin->load_view( $plugin->model, 'member_form', $options );
		exit();
		//set cache...
	}

	function crm_customer_remove_member_ajax() {
		$plugin = new StacktechCrm;
		$plugin->model = $plugin->model_cus;

		$member_id = $_POST['mem_id'];

		$member['is_delete'] = 1;
		$plugin->crm_db->update_crm_member_info( $member, $member_id );

		exit();
	}

	function crm_remove_model_item_ajax() {
		if( !isset( $_POST['model'] ) || empty( $_POST['model'] ) )
			return;

		$plugin = new StacktechCrm;
		$plugin->model = $_POST['model'];
		$pid = $_POST['pid'];

		switch ( $plugin->model ) {
			case $plugin->model_cus:
				$plugin->crm_db->delete_crm_customer( $pid );
				$plugin->crm_db->delete_crm_member_by_customer( $pid );

				break;

			case $plugin->model_mem:
				$plugin->crm_db->delete_crm_member( $pid );

				break;

			case $plugin->model_rec:
				$plugin->crm_db->delete_crm_record( $pid );

				break;

			case $plugin->model_hol:
				$plugin->crm_db->delete_crm_holiday( $pid );

				break;

			case $plugin->model_mail:
				$plugin->crm_db->delete_crm_mail( $pid );

				break;

			case $plugin->model_sms:
				$plugin->crm_db->delete_crm_sms( $pid );

				break;
			
			default:
				# code...
				break;
		}
		exit();
	}

	function crm_get_members_list_ajax() {
		$plugin = new StacktechCrm;
		$customer_id = $_POST['customer_id'];

		echo $plugin->get_member_list( $customer_id );

		exit();
	}

	function crm_get_country_address_ajax() {
		$plugin = new StacktechCrm;
		$type = $_POST['type'];
		$current_val = $_POST['current_val'];

		$response = array();

		if( $type == 'province' ) {
			$citys = $plugin->crm_db->get_data_by_parent( $current_val );
			if( count($citys) > 0 ){
				$areas = $plugin->crm_db->get_data_by_parent( $citys[0]['code'] );
			}else{
				$areas = array();
			}

			$city_html = '<select name="city" id="crm_city">';
			//error_log(var_export($citys,true));
			foreach ($citys as $c) {
			 	$city_html.= '<option value="' . $c['code'] . '">' . $c['name'] . '</option>';
			} 
			$city_html.= '</select>';

			$area_html = '<select name="area" id="crm_area">';
			foreach ($areas as $a) {
			 	$area_html.= '<option value="' . $a['code'] . '">' . $a['name'] . '</option>';
			} 
			$area_html.= '</select>';

			$response['city_html'] = $city_html;
			$response['area_html'] = $area_html;
		}elseif ( $type == 'city' ) {
			$areas = $plugin->crm_db->get_data_by_parent( $current_val );

			$area_html = '<select name="area" id="crm_area">';
			foreach ($areas as $a) {
			 	$area_html.= '<option value="' . $a['code'] . '">' . $a['name'] . '</option>';
			} 
			$area_html.= '</select>';

			$response['area_html'] = $area_html;
		}

		echo json_encode($response);
		exit();
	}

	function crm_selected_action_ajax() {
		$plugin = new StacktechCrm;

		$type    = $_POST['model'];
		$selected = substr( $_POST['selected'], 0, -1 );
		$exec   = $_POST['exec'];

		$response = array();
		switch ( $exec ) {
			case 'mail':
				$response['url'] = admin_url( 'admin.php?page=crm_send&model=mail&type=' . $type . '&selected=' . $selected );
				break;
			
			default:
				# code...
				break;
		}

		echo json_encode( $response );
		exit();
	}

	function crm_get_viewer_content_ajax() {
		$plugin = new StacktechCrm;

		$viewer_id = $_POST['viewer_id'];

		$viewer = $plugin->crm_db->get_send_viewer_by_id( $viewer_id );

		echo json_encode( $viewer );
		exit();
	}

	function crm_viewer_save_ajax() {
		$plugin = new StacktechCrm;

		$data['model']          = $_POST['model'];
		$data['viewer_id']      = $_POST['viewer_id'];
		$data['viewer_name']    = $_POST['viewer_name'];
		$data['viewer_content'] = stripslashes($_POST['viewer_content']);

		$viewer_id = $plugin->crm_db->update_send_viewer( $data );

		$response['viewer_id']      = $viewer_id;
		$response['viewer_name']    = $_POST['viewer_name'];
		$response['viewer_content'] = stripslashes($_POST['viewer_content']);
		//echo '<pre>';print_r($data);echo '</pre>';
		echo json_encode( $response );
		exit();
	}

	function crm_member() {
		$this->load_required_files();

		$this->model = $this->model_mem;
		$data['categories'] = $this->crm_db->get_all_categories( $this->model );
		//echo '<pre>';print_r($categories);echo '</pre>';
		$this->load_view( 'member', 'main', $data );
	}

	function crm_record() {
		$this->load_required_files();

		$this->model = $this->model_rec;
		$data['categories'] = $this->crm_db->get_all_categories( $this->model );
		//echo '<pre>';print_r($categories);echo '</pre>';
		$this->load_view( 'record', 'main', $data );
	}

	function crm_holiday() {
		$this->load_required_files();

		$this->model = $this->model_hol;
		$data['categories'] = $this->crm_db->get_all_categories( $this->model );
		//echo '<pre>';print_r($categories);echo '</pre>';
		$this->load_view( 'holiday', 'main', $data );
	}

	function crm_mail() {
		$this->load_required_files();

		$this->model = $this->model_mail;
		$data['categories'] = $this->crm_db->get_all_categories( $this->model );


		$this->load_view( 'mail', 'main', $data );
	}

	function crm_sms() {
		$this->load_required_files();

		$this->model = $this->model_sms;
		$data['categories'] = $this->crm_db->get_all_categories( $this->model );
		//echo '<pre>';print_r($categories);echo '</pre>';
		$this->load_view( 'sms', 'main', $data );
	}

	function crm_send() {
		global $current_user;

		if( !isset( $_REQUEST['model'] ) )
			return;
		else
			$this->model = $_REQUEST['model'];

		if( count( $_POST ) > 0 ) {
			switch ( $this->model ) {
				case $this->model_mail:
					$this->send_mail_save();
					wp_redirect( admin_url('admin.php?page=crm_mail') ); 
					exit;

					break;
				
				case $this->model_sms:
					$this->send_sms_save();
					wp_redirect( admin_url('admin.php?page=crm_sms') ); 
					exit;

					break;

				default:
					# code...
					break;
			}
		}

		$this->load_required_files();
		
		$data['categories'] = $this->crm_db->get_all_categories( $this->model );

		if( isset( $_GET['type'] ) && isset( $_GET['selected'] ) ) {
			$data['mail_addresses'] = $this->crm_db->get_email_addresses_by_type( $_GET['type'], $_GET['selected'] );
		}

		switch ( $this->model ) {
			case $this->model_mail:
				$data['user_id']    = $current_user->ID;
				$data['user_email'] = $current_user->data->user_email;
				$data['user_name']  = $current_user->data->display_name;

				$data['viewers'] = $this->crm_db->get_all_viewers( $this->model );

				break;
			
			default:
				# code...
				break;
		}

		$this->load_view( $this->model, 'edit', $data );
	}

	function send_mail_save() {
		global $current_user;

		if( empty( $_POST['mail_account'] ) )
			return;

		$mail_group = time();

		$accounts_arr = explode( "\n", $_POST['mail_account'] );

		foreach ($accounts_arr as $account) {
			$account = trim( $account );
			if( empty( $account ) )
				continue;

			$tmp_arr = explode( '(', $account );
			$data['recipient_email'] = $tmp_arr[0];

			$tmp_arr2 = explode( ')', $tmp_arr[1] );
			$data['recipient_name'] = $tmp_arr2[0];

			$data['mail_group']   = $mail_group;
			$data['mail_topic']   = $_POST['mail_topic'];
			$data['mail_content'] = stripslashes( $_POST['send_content'] );
			$data['mail_time']    = date( 'Y-m-d H:i:s' );
			$data['user_id']      = $current_user->ID;
			$data['user_email']   = $current_user->data->user_email;

			wp_mail( $data['recipient_email'], $data['mail_topic'], $data['mail_content'], array( 'From:'. $data['user_email'] ) );

			$this->crm_db->add_crm_mail_info( $data );
		}
	}

	function send_mail( $email, $topic, $content ) {
		//load php file to send
	}

	function send_sms_save() {
		global $current_user;

		if( empty( $_POST['sms_account'] ) )
			return;

		$sms_group = time();

		$accounts_arr = explode( "\n", $_POST['sms_account'] );
		$sms_public_time = date( 'Y-m-d H:i:s', strtotime( $_POST['sms_public_time'] ) );

		foreach ($accounts_arr as $account) {
			$account = trim( $account );
			if( empty( $account ) )
				continue;

			$tmp_arr = explode( '(', $account );
			$data['recipient_phone'] = $tmp_arr[0];

			$tmp_arr2 = explode( ')', $tmp_arr[1] );
			$data['recipient_name'] = $tmp_arr2[0];

			$data['sms_group']       = $sms_group;
			$data['sms_public_time'] = $sms_public_time;
			$data['sms_content']     = $_POST['sms_content'];
			$data['sms_time']        = date( 'Y-m-d H:i:s' );
			$data['user_id']         = $current_user->ID;
			
			$this->crm_db->add_crm_sms_info( $data );
		}
	}

	function crm_main() {
		global $current_user;
		$this->load_required_files();
		
		$record_week = $this->crm_db->get_week_record( $current_user->ID );
		foreach ( $record_week as $key => $record ) {
			$customer = $this->crm_db->get_crm_customer_info_by_id( $record['customer_id'] );
			$record_week[$key]['customer_name'] = $customer['customer_name'];

			$member = $this->crm_db->get_crm_member_by_member_id( $record['member_id'] );
			$record_week[$key]['member_name'] = $member['member_name'];
		}

		$data['record_week'] = $record_week;
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load_view( 'crm', 'main', $data );
	}

	function create_cate() {
		$this->load_required_files();

		switch ( $_REQUEST['model'] ) {
			case 'customer':
				$this->load_view( 'customer', 'edit_cate', $data );
				break;
			
			default:
				# code...
				break;
		}
		
	}

}

function stacktech_crm_init(){  
	$plugin = new StacktechCrm;

    add_menu_page( __('客户管理'), __('客户管理'), "manage_options", 'crm_main', array($plugin, 'crm_main'),'dashicons-id' );

    add_submenu_page( 'crm_main', __('主界面'), __('主界面'), 'manage_options', 'crm_main' ,array($plugin, 'crm_main') );
    add_submenu_page( 'crm_main', __('客户管理'), __('客户管理'), 'manage_options', 'crm_customer' ,array($plugin, 'crm_customer') );
    add_submenu_page( 'crm_main', __('联系人管理'), __('联系人管理'), 'manage_options', 'crm_member' ,array($plugin, 'crm_member') );
    add_submenu_page( 'crm_main', __('联系记录'), __('联系记录'), 'manage_options', 'crm_record' ,array($plugin, 'crm_record') );
    add_submenu_page( 'crm_main', __('纪念日管理'), __('纪念日管理'), 'manage_options', 'crm_holiday' ,array($plugin, 'crm_holiday') );
    add_submenu_page( 'crm_main', __('邮件管理'), __('邮件管理'), 'manage_options', 'crm_mail' ,array($plugin, 'crm_mail') );
    add_submenu_page( 'crm_main', __('短信管理'), __('短信管理'), 'manage_options', 'crm_sms' ,array($plugin, 'crm_sms') );
    add_submenu_page( 'crm_main', __('控制面板'), __('控制面板'), 'manage_options', 'crm_setting' ,array($plugin, 'crm_setting') );

    add_submenu_page( null, __('编辑'), __('编辑'), 'manage_options', 'crm_edit_model' ,array($plugin, 'crm_edit_model') );
    add_submenu_page( null, __('编辑邮件'), __('编辑邮件'), 'manage_options', 'crm_send' ,array($plugin, 'crm_send') );
    add_submenu_page( null, __('编辑视图'), __('编辑视图'), 'manage_options', 'create_cate' ,array($plugin, 'create_cate') );
    
}

register_activation_hook( __FILE__, array( 'StacktechCrm', 'plugin_activation' ) );
add_action('admin_menu', 'stacktech_crm_init');
add_action( 'wp_ajax_stacktech-crm-load-options-ajax', array('StacktechCrm','crm_load_options_ajax') );
add_action( 'wp_ajax_stacktech-crm-option-save-ajax', array('StacktechCrm','crm_option_save_ajax') );
add_action( 'wp_ajax_stacktech-crm-customer-form-options-ajax', array('StacktechCrm','crm_customer_form_options_ajax') );
add_action( 'wp_ajax_stacktech-crm-customer-remove-member-ajax', array('StacktechCrm','crm_customer_remove_member_ajax') );
add_action( 'wp_ajax_stacktech-crm-remove-model-item-ajax', array('StacktechCrm','crm_remove_model_item_ajax') );
add_action( 'wp_ajax_stacktech-crm-get-members-list-ajax', array('StacktechCrm','crm_get_members_list_ajax') );
add_action( 'wp_ajax_stacktech-crm-get-country-address-ajax', array('StacktechCrm','crm_get_country_address_ajax') );
add_action( 'wp_ajax_stacktech-crm-selected-action-ajax', array('StacktechCrm','crm_selected_action_ajax') );
add_action( 'wp_ajax_stacktech-crm-get-viewer-content-ajax', array('StacktechCrm','crm_get_viewer_content_ajax') );
add_action( 'wp_ajax_stacktech-crm-viewer-save-ajax', array('StacktechCrm','crm_viewer_save_ajax') );



?>