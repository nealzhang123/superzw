<?php   
/*
Plugin Name: 00 stacktech hospital track
Plugin URI: http://www.etongapp.com
Description: stacktech hospital track
Version: 1.0
Author: Stacktech
Author URI: http://www.etongapp.com
License: GPL
*/
define( 'Hospital_Track_VIEW_PATH', plugin_dir_path(__FILE__) . 'views/' );

//该类结构,以大空白分隔,函数群体依次为
//公共函数,产前队列列表函数,产前队列录入函数,仪表盘widget,ajax函数,底部为插件类的函数,js,css文件,钩子
class HospitalTrack {
	public $hos_db,$is_mobile;
	public $region0,$region1,$region2,$region3;
	public $tel_book,$track_result,$track_list,$tel_list;
	public $model_m1,$model_m6,$model_y1,$model_y2;
	public $page_arr,$telre_arr;
	public $user_name;
	public $track_arr,$telquesre_arr,$track_status_content;

	function __construct() {

		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	        spl_autoload_register( 'HospitalTrack::autoloadClass', true, true);
	    } else {
	        spl_autoload_register( 'HospitalTrack::autoloadClass' );
	    }

	    if( wp_is_mobile() )
	    	$this->is_mobile = true;

	    date_default_timezone_set('PRC');

	    $this->hos_db = new Hospital_Db;

		$this->region0 = 0;//1个月的随访
		$this->region1 = 1;//6个月
		$this->region2 = 2;//1岁
		$this->region3 = 3;//2岁

		$this->tel_book     = 'tel_book';
		$this->track_result = 'track_result';
		$this->track_list   = 'track_list';
		$this->tel_list     = 'tel_list';

		$this->model_m1 = 'm1';
		$this->model_m6 = 'm6';
		$this->model_y1 = 'y1';
		$this->model_y2 = 'y2';

		$this->model_pregnant_three = 'pregnant_three';
		$this->model_pregnant_middle = 'pregnant_middle';
		$this->model_childbirth_status = 'childbirth_status';
		$this->model_pregnant_b = 'pregnant_b';  
		$this->model_health_manage = 'health_manage'; 

		$this->page_arr = array(
			0 => 10,
			1 => 20,
			2 => 50,
			3 => 100,
			);

		$this->telre_arr = array(
			0 => '无备注',
			1 => '参加',
			2 => '不在武汉',
			3 => '社区体检',
			4 => '失联(停机、空号、错号)',
			5 => '拒绝',
			6 => '市妇幼自费已做',
			7 => '宝宝夭折',
			100 => '自定义'
			);

		$this->track_arr = array(
			'selfques' => '自填问卷',
			'epds'     => '抑郁量表',
			'phyexa'   => '体格检查',
			'brmilkr'  => '母乳结果',
			'brmilks'  => '母乳样本',
			'icterus'  => '黄疸',
			'urine'    => '尿液',
			'fec'      => '粪便',
			'bp'       => '母亲血压',
			'baily'    => '贝利',
			'rbt'      => '血常规',
			'vision'   => '视力',
			'bpb'      => '血铅'
			);

		//随访状态	根据随访信息的录入显示颜色 1.完成现场随访 2.完成电话随访 3.电话随访失联 4.电话随访拒绝	 5.宝宝夭折 6.未到随访时间 7.到随访时间人还没来  8.未处理 
		$this->track_status_arr = array(
			1 => '<img src="'.esc_url( plugins_url( 'images/green.png', __FILE__ ) ).'">',
			2 => '<img src="'.esc_url( plugins_url( 'images/blue.png', __FILE__ ) ).'">',
			3 => '<img src="'.esc_url( plugins_url( 'images/grey.png', __FILE__ ) ).'">',
			4 => '<img src="'.esc_url( plugins_url( 'images/purple.png', __FILE__ ) ).'">',
			5 => '<img src="'.esc_url( plugins_url( 'images/black.png', __FILE__ ) ).'">',
			6 => '<img src="'.esc_url( plugins_url( 'images/white.png', __FILE__ ) ).'">',
			7 => '<img src="'.esc_url( plugins_url( 'images/yellow.png', __FILE__ ) ).'">',
			8 => '<img src="'.esc_url( plugins_url( 'images/red.png', __FILE__ ) ).'">',
			);

		$this->telquesre_arr = array(
			3 => '失联',
			4 => '拒绝',
			5 => '宝宝夭折'
			);

		$this->track_status_content = array(
			1 => '完成现场随访',
			2 => '完成电话随访',
			3 => '电话随访失联',
			4 => '电话随访拒绝',
			5 => '宝宝夭折',
			6 => '未到随访时间',
			7 => '到随访时间还没来',
			8 => '逾期未处理',
			);
		
		//
		//完成现场随访值为1,完成电话随访值为2,电话随访失联值为3，电话随访拒绝值为4
		//其他值为０，状态通过当前时间与应该随访时间判断是否　未到随访时间，到随访时间还没来，未处理

		$current_user = wp_get_current_user();
		$this->user_name = $current_user->display_name;
	}

	//自动加载类
	function autoloadClass( $classname ) {
        $filename = plugin_dir_path( __FILE__ ) . 'class/class_' . strtolower( $classname ) . '.php';
	    if ( is_readable( $filename ) ) {
	        require_once $filename;
	    }
    }

    //插件激活时候，建立相关数据表
    function plugin_activation() {
    	$plugin = new HospitalTrack;
    	$plugin->hos_db->init_tables();
    	$pregnant = new HospitalPreBirthPregnantTrack;
    	$pregnant->hos_pregnant_db->create_tables();
    }

    //加载各个视图
    function load_view( $folder, $view, $data = array() ){
    	if( !empty( $folder ) )
    		$filename = $folder . '/' . $view . '-view.php';
    	else
    		$filename = $view . '-view.php';

    	require( Hospital_Track_VIEW_PATH . $filename );
    }

    function get_system_table_id( $dedate) {
    	//从2014年4月21日，星期一开始计算吧，起始为10000
    	$start = strtotime( '2014-04-21' );
    	$end = strtotime( $dedate );
    	$per = 3600*24*14;//每两周
    	$table_id = round( ( $end-$start) / $per ) + 10000;

    	return $table_id;
    }

    function get_system_date_by_id( $sys_tab_id ) {
    	$per = 3600*24*14;//每两周
    	$time = strtotime( '2014-04-21' ) + ( $sys_tab_id - 10000 ) * $per;
    	$date = date( 'Y-m-d', $time );

    	return $date;
    }

    function diffDate($date1, $date2) { 
        if (strtotime($date1) > strtotime($date2)) { 
            return array();
        }
        list($y1, $m1, $d1) = explode('-', $date1); 
        list($y2, $m2, $d2) = explode('-', $date2); 
        $y = $m = $d = $_m = 0; 
        $math = ($y2 - $y1) * 12 + $m2 - $m1; 
        $y = floor($math / 12); 
        $m = intval($math % 12); 
        $d = (mktime(0, 0, 0, $m2, $d2, $y2) - mktime(0, 0, 0, $m2, $d1, $y2)) / 86400; 
        if ($d < 0) { 
	        $m -= 1; 
	        $d += date('j', mktime(0, 0, 0, $m2, 0, $y2)); 
        } 
        $m < 0 && $y -= 1; 

        return array($y, $m, $d); 
    }

    function translate_date( $date ) {
    	if( empty( $date ) )
    		return '';

    	if( date( 'Y', strtotime( $date ) ) <= '1970' )
    		return '';

    	return  date( 'Y-m-d', strtotime( $date ) );
    }

    function get_check_icon( $val ) {
    	if( $val == 1 ){
    		return '<i class="fa fa-check" aria-hidden="true" style="color:green;"></i>';
    	}else{
    		return '<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>';
    	}
    }

    function get_hos_status_content_view() {
?>
    	<table class="table">
    	<tr>
    		<th>完成现场随访&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/green.png', __FILE__ ) ) ?>" /></th>
    		<th>完成电话随访&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/blue.png', __FILE__ ) ) ?>" /></th>
    		<th>电话随访失联&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/grey.png', __FILE__ ) ) ?>" /></th>
    		<th>电话随访拒绝&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/purple.png', __FILE__ ) ) ?>" /></th>
    		<th>宝宝夭折&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/black.png', __FILE__ ) ) ?>" /></th>
    		<th>未到随访时间&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/white.png', __FILE__ ) ) ?>" /></th>
    		<th>到随访时间人还没来&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/yellow.png', __FILE__ ) ) ?>" /></th>
    		<th>逾时未处理&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/red.png', __FILE__ ) ) ?>" /></th>
    	</tr>
    	</table>
<?php
    }

    function hos_get_two_year_track_status( $fpstatus, $model, $dedate ) {
    	$checked_arr = array( 1,2,3,4,5 );//现场随访,电话随访,问卷失联,问卷拒绝,宝宝夭折

    	if( in_array( $fpstatus, $checked_arr ) )
    		return $fpstatus;

    	switch ( $model ) {
            case $this->model_m1:
                $start = strtotime( '+25 day', strtotime( $dedate ) );
                $end = strtotime( '+15 day', strtotime( $dedate ) );
                $end = strtotime( '+2 month', $end );

                break;

            case $this->model_m6:
                $start = strtotime( '+5 month', strtotime( $dedate ) );
                $end = strtotime( '+8 month', strtotime( $dedate ) );

                break;

            case $this->model_y1:
                $start = strtotime( '+11 month', strtotime( $dedate ) );
                $end = strtotime( '+14 month', strtotime( $dedate ) );

                break;

            case $this->model_y2:
                $start = strtotime( '+23 month', strtotime( $dedate ) );
                $end = strtotime( '+27 month', strtotime( $dedate ) );

                break;
            
            default:
                # code...
                break;
        }

        $current_time = time();

        if( $current_time < $start )
        	$fpstatus = 6;
        else if( $current_time > $end )
        	$fpstatus = 8;
        else
        	$fpstatus = 7;

        return $fpstatus;
    }


    //上传模块
	function muti_import() {
		$pregnant_plugin = new HospitalPreBirthPregnantTrack;
		if( !isset( $_REQUEST['hos_action'] ) ){
			$this->load_view( 'muti-upload', 'muti-upload-main' );
			return;
		}

		$hos_action = $_REQUEST['hos_action'];
		$hos_type = $_REQUEST['hos_type'];
		$model = $_REQUEST['model'];

		switch ( $hos_type ) {
			case 1://出生队列
				switch ( $hos_action ) {
					case 'basic_info':
						$head = __('出生队列分娩信息导入');
						$view = 'in-birth-basic-example';

						break;
					
					default:
						# code...
						break;
				}

				break;

			case 2://产前队列
				if( isset( $_REQUEST['model'] ) )
					$model = $_REQUEST['model'];
				else
					$model = $this->model_m1;

				switch ( $hos_action ) {
					case 'basic_info':
						$head = __('产前队列分娩信息导入');
						$view = 'pre-birth-basic-example';

						break;

					case "three_pregnant_info":
						if( isset( $_REQUEST['model'] ) )
							$model = $_REQUEST['model'];
						else
							$model = $this->model_pregnant_three;
						switch ( $model ){
							case $this->model_pregnant_three:
								$head = __('产前队列孕三期数据导入');

								break;

							case $this->model_pregnant_middle:
								$head = __('产前队列孕中期数据导入');

								break;

							case $this->model_childbirth_status:
								$head = __('产前队分娩状态数据导入');

								break;

							case $this->model_pregnant_b:
								$head = __('产前队列孕期超声检查数据导入');

								break;

							case $this->model_health_manage:
								$head = __('产前队列健康管理数据导入');

								break;

							default :
								#code ......
								break;
						}
						
						$view = 'pre-birth-three-pregnant-info';
						break;
						
					case $this->track_result:
						if( isset( $_REQUEST['model'] ) )
							$model = $_REQUEST['model'];
						else
							$model = $this->model_m1;
						switch ( $model ) {
							case $this->model_m1:
								$head = __('产前队列1月现场随访导入');

								break;

							case $this->model_m6:
								$head = __('产前队列6月现场随访导入');
								
								break;

							case $this->model_y1:
								$head = __('产前队列1岁现场随访导入');
								
								break;

							case $this->model_y2:
								$head = __('产前队列2岁现场随访导入');
								
								break;
							
							default:
								# code...
								break;
						}

						$view = 'pre-birth-track-result-example';

						break;
					
					default:
						# code...
						break;
				}

				break;
			
			default:
				return;				
				break;
		}

		$data = array();

		$data['head']        = $head;
		$data['ajax_action'] = 'hos-muti-upload-ajax';
		$data['view']        = $view;
		$data['hos_action']  = $hos_action;
		$data['hos_type']    = $hos_type;
		$data['model']       = $model;

		$main_view = 'muti-upload';
		$this->load_view( 'muti-upload', $main_view, $data );
	}



	//产前队列列表模块
	//主界面
	function pre_birth_manage() {
		$pregnant_plugin = new HospitalPreBirthPregnantTrack;
		$four_track = new HospitalFourTrack;

		$group = $_REQUEST['group'];
		$model = $_REQUEST['model'];

		if( in_array( $model, array( $four_track->model_ex, $four_track->model_y3, $four_track->model_y5 ) ) ){
			$four_track->four_track_manage();
			
			return;
		}

		if( !isset( $_REQUEST['group'] ) ){
			$this->load_view( 'pre-birth-list', 'pre-birth-main' );

			return;
		}

		if( !isset( $_REQUEST['hos_action'] ) )
			$hos_action = $this->tel_book;
		else
			$hos_action = $_REQUEST['hos_action'];

		if( isset( $_REQUEST['model'] ) )
			$data['model'] = $_REQUEST['model'];
		else
			$data['model'] = $this->model_m1;

		switch ( $group ) {
			case 'list':
				$group_folder = 'pre-birth-list';
				$group_nav_view = 'pre-birth-list-nav';

				switch ( $model ) {
					case $this->model_m1:
						$title = '产前队列——1月随访';
						$data['pre_title'] = '1月';
						
						break;
					case $this->model_m6:
						$title = '产前队列——6月随访';
						$data['pre_title'] = '6月';

						break;
					case $this->model_y1:
						$title = '产前队列——1岁随访';
						$data['pre_title'] = '1岁';

						break;
					case $this->model_y2:
						$title = '产前队列——2岁随访';
						$data['pre_title'] = '2岁';

						break;
					
					default:
						# code...
						break;
				}

				break;
			
			case 'pregnant':
				$group_folder =  'pre-birth-pregnant';
				$group_nav_view = 'pre-birth-pregnant-nav';

				$title = '产前队列——孕三期收样情况和孕中期电话情况';

				break;

			case 'childbirth_status':
				$group_folder =  'pre-birth-childbirth';
				$group_nav_view = 'pre-birth-childbirth-status-nav';

				$title = '产前队列——分娩状态';

				break;

			case 'pregnant_middle':
				$group_folder =  'pre-birth-pregnant';
				$group_nav_view = 'pre-birth-pregnant-middle-nav';

				$title = '产前队列——孕中期电话随访情况';

				break;

			case 'pregnant_b':
				$group_folder = 'pre-birth-pregnant';
				$group_nav_view = 'pre-birth-pregnant-b-nav';

				$title = '产前队列——孕期超声检查状态情况';

				break;

			case 'health_manage':
				$group_folder = 'pre-birth-health';
				$group_nav_view = 'pre-birth-health-manage-nav';

				$title = '产前队列——健康管理';
				break;

			case 'edit':
				$group_folder = 'pre-birth-edit';
				$group_nav_view = 'pre-birth-edit-nav';

				$title = '产前队列——现场随访';

				break;

			default:
				# code...
				break;
		}

		$data['model']          = $model;
		$data['group']          = $group;
		$data['hos_action']     = $hos_action;
		$data['title']          = $title;
		$data['group_folder']   = $group_folder;
		$data['group_nav_view'] = $group_nav_view;

		$this->load_view( $group_folder, $group_nav_view, $data );

		switch ( $group ) {
			case 'list':
				$this->load_pre_birth_list_view( $data );

				break;
			
			case 'pregnant':
				$pregnant_plugin->load_pre_birth_pregnant_list_view($data);

				break;
				
			case 'childbirth_status':
				$pregnant_plugin->load_pre_birth_childbirth_status_list_view($data);

			break;

			case 'pregnant_middle':
				$pregnant_plugin->load_pre_birth_pregnant_middle_list_view($data);

			break;

			case 'pregnant_b':
				$pregnant_plugin->load_pre_birth_pregnant_b_list_view($data);

			break;

			case 'health_manage':
				$pregnant_plugin->load_pre_birth_health_manage_list_view($data);

			break;

			case 'edit':
				$this->load_pre_birth_edit_view( $data );

				break;

			default:
				# code...
				break;
		}

	}

	//产前队列各个分类的界面
	function load_pre_birth_list_view( $pass ) {
		$data = array();

		switch ( $pass['model'] ) {
			case $this->model_m1:
				$title = '1月';
				$data['tables'] = $this->hos_db->get_all_table_info(0);
				
				break;
			case $this->model_m6:
				$title = '6月';
				$data['tables'] = $this->hos_db->get_all_table_info(1);

				break;
			case $this->model_y1:
				$title = '1岁';
				$data['tables'] = $this->hos_db->get_all_table_info(1);

				break;
			case $this->model_y2:
				$title = '2岁';
				$data['tables'] = $this->hos_db->get_all_table_info(1);

				break;
			
			default:
				# code...
				break;
		}

		$data['model']  = $pass['model'];
		$data['hos_action'] = $pass['hos_action'];

		if( isset( $_REQUEST['tab_no'] ) )
			$data['tab_no'] = $_REQUEST['tab_no'];

		switch ( $pass['hos_action'] ) {
			case $this->tel_book:
				$view = 'pre-birth-tel-book';
				$list = new Hospital_Pre_Birth_Tel_Book_Table( $pass['model'] );

				break;

			case $this->track_result:
				$view = 'pre-birth-track-result';
				$list = new Hospital_Pre_Birth_Track_Result_Table( $pass['model'] );

				break;

			case $this->track_list:
				$data['table_name'] = $title . '现场随访表';
				$view = 'pre-birth-track-list';
				$list = new Hospital_Pre_Birth_Track_List_Table( $pass['model'] );

				break;

			case $this->tel_list:
				$data['table_name'] = $title . '电话随访表';
				$view = 'pre-birth-tel-list';
				$list = new Hospital_Pre_Birth_Tel_List_Table( $pass['model'] );

				break;
			
			default:
				# code...
				break;
		}

		$list->prepare_items();
		$data['list'] = $list;

		$this->load_view( $pass['group_folder'], $view, $data );
	}

	//产前队列列表显示视图
	function get_pre_birth_list_content_view( $list ) {
		$list->display();
	}

	function get_table_nav_data( $list ) {
		$data['total_count'] = $list->total_count;
		$data['current_page'] = 1;
		if( isset( $_REQUEST['current_page'] ) )
			$data['current_page'] = $_REQUEST['current_page'];

		$data['page_per_num'] = 0;
		if( isset( $_REQUEST['page_per_num'] ) )
			$data['page_per_num'] = $_REQUEST['page_per_num'];
		$data['total_page'] = ceil( $data['total_count'] / $this->page_arr[$data['page_per_num']] );

		return $data;
	}

	function get_table_nav_top_view( $list ) {
		$data = $this->get_table_nav_data( $list );

		$this->load_view( '', 'table-nav-top', $data );
	}

	function get_table_nav_bottom_view( $list ) {
		$data = $this->get_table_nav_data( $list );
		$this->load_view( '', 'table-nav-bottom', $data );
	}





	//产前队列各个录入的界面
	function load_pre_birth_edit_view( $pass ) {
		$data = array();

		$data['hos_action'] = $pass['hos_action'];

		if( isset( $_REQUEST['model'] ) )
			$data['model'] = $_REQUEST['model'];
		else
			$data['model'] = $this->model_m1;

		switch ( $pass['hos_action'] ) {
			case $this->tel_book:
				$data['title'] = '现场随访';
				$view = 'pre-birth-edit-tel-book';
				$list = new Hospital_Pre_Birth_Edit_Tel_Book_Table( $data['model'] );

				break;

			case $this->track_result:
				$data['title'] = '现场随访表';
				$view = 'pre-birth-edit-track-result';
				$list = new Hospital_Pre_Birth_Edit_Track_Result_Table( $data['model'] );
				break;

			case $this->tel_list:
				$data['title'] = '电话随访表';
				$view = 'pre-birth-edit-tel-list';
				$list = new Hospital_Pre_Birth_Edit_Tel_List_Table( $data['model'] );

				break;
			
			default:
				# code...
				break;
		}

		$list->prepare_items();
		$data['list'] = $list;

		$this->load_view( 'pre-birth-edit', $view, $data );
	}

	function get_pre_birth_edit_select_tables( $model, $hos_action ) {
		$data['tab_no'] = 0;
		if( isset( $_REQUEST['tab_no'] ) )
			$data['tab_no'] = $_REQUEST['tab_no'];

		switch ( $model ) {
			case $this->model_m1:
				$title = '1月';
				$data['tables'] = $this->hos_db->get_all_table_info(0);
				
				break;
			case $this->model_m6:
				$title = '6月';
				$data['tables'] = $this->hos_db->get_all_table_info(1);

				break;
			case $this->model_y1:
				$title = '1岁';
				$data['tables'] = $this->hos_db->get_all_table_info(1);

				break;
			case $this->model_y2:
				$title = '2岁';
				$data['tables'] = $this->hos_db->get_all_table_info(1);

				break;
			
			default:
				# code...
				break;
		}

		$this->load_view( 'pre-birth-edit', 'pre-birth-edit-select-table', $data );
	}

	function get_pre_birth_edit_table_view( $list ) {
		$list->display();
	}





    //dashboard widget
    function load_widgets() {
		if( current_user_can('manage_options') ){
			wp_add_dashboard_widget( __('30天表单提醒'), __('30天表单提醒'), array( $this,'widget_pre_1m' ) );
		    wp_add_dashboard_widget( __('6个月表单提醒'), __('6个月表单提醒'), array( $this,'widget_pre_6m' ) );
		    wp_add_dashboard_widget( __('1岁表单提醒'), __('1岁表单提醒'), array( $this,'widget_pre_1y' ) );
		    wp_add_dashboard_widget( __('2岁表单提醒'), __('2岁表单提醒'), array( $this,'widget_pre_2y' ) );
		}

		// if( current_user_can('manage_categories') ){
		// 	wp_add_dashboard_widget( __('查询表单内容'), __('查询表单内容'), array( $this,'widget_search' ) );
		// }
	}

	function widget_pre_1m() {
		$this->render_widget_content( $this->region0 );
	}

	function widget_pre_6m() {
		$this->render_widget_content( $this->region1 );
	}

	function widget_pre_1y() {
		$this->render_widget_content( $this->region2 );
	}

	function widget_pre_2y() {
		$this->render_widget_content( $this->region3 );
	}

	function render_widget_content( $region ) {
		$tel_title = '';
		$track_title = '';
		$max_count1 = 5;
		$max_count2 = 5;
		$tab_type = 1;

		switch ( $region ) {
			case 0:
				$time1       = date( 'Y-m-d',strtotime('-25 day') );
				$time2       = date( 'Y-m-d',strtotime('-45 day') );
				$tel_title   = __('25天电话预约');
				$track_title = __('45天电话随访');
				$max_count1  = 1;
				$tab_type    = 0;
				$model = $this->model_m1;

				break;
			case 1:
				$time1       = strtotime('-5 month');
				$time1       = date( "Y-m-d", strtotime( '-15 day',$time1 ) );
				$time2       = date( "Y-m-d", strtotime( '-7 month' ) );
				$tel_title   = __('5个半月电话预约');
				$track_title = __('7个月电话随访');
				$model = $this->model_m6;

				break;
			case 2:
				$time1       = strtotime('-11 month');
				$time1       = date( "Y-m-d", strtotime( '-15 day',$time1 ) );
				$time2       = date( "Y-m-d", strtotime( '-13 month' ) );
				$tel_title   = __('11个半月电话预约');
				$track_title = __('13个月电话随访');
				$model = $this->model_y1;

				break;
			case 3:
				$time1       = strtotime('-23 month');
				$time1       = date( "Y-m-d", strtotime( '-15 day',$time1 ) );
				$time2       = date( "Y-m-d", strtotime( '-25 month' ) );
				$tel_title   = __('23个半月电话预约');
				$track_title = __('26个月电话随访');
				$model = $this->model_y2;

				break;
			
			default:
				break;
		}

		$tel_results = $this->hos_db->get_related_tables_by_time( $time1, $tab_type );
		$tel_ignore_key = 'ignore_tel_' . $region;

		foreach ( $tel_results as $key => $tel) {
			if( !empty( $tel[$tel_ignore_key] ) ) {
				unset( $tel_results[$key] );
				continue;
			}

			if( $tab_type ) 
				$tel_results[$key]['tab_name'] = '表' . $tel['tab_no'];
			else
				$tel_results[$key]['tab_name'] = $tel['tab_no'];
		}

		$track_results = $this->hos_db->get_related_tables_by_time( $time2, $tab_type );
		$track_ignore_key = 'ignore_track_' . $region;

		foreach ( $track_results as $key => $track) {
			if( !empty( $track[$track_ignore_key] ) ) {
				unset( $track_results[$key] );
				continue;
			}

			if( $tab_type ) 
				$track_results[$key]['tab_name'] = '表' . $track['tab_no'];
			else
				$track_results[$key]['tab_name'] = $track['tab_no'];
		}

		$data['region']        = $region;
		$data['tel_title']     = $tel_title;
		$data['track_title']   = $track_title;
		$data['max_count1']    = $max_count1;
		$data['max_count2']    = $max_count2;
		$data['tab_type']      = $tab_type;
		$data['tel_results']   = $tel_results;
		$data['track_results'] = $track_results;
		$data['model']		   = $model;

		//echo '<pre>';print_r($data);echo '</pre>';//exit();
		$this->load_view( '', 'widget-list', $data );
	}





	//kind of ajax
	function hospital_muti_upload_ajax() {
		$plugin = new HospitalTrack;

		$upload_handler = new Hospital_Upload_Handler();
		exit();
	}

	function hospital_track_ignore_ajax() {
		//echo '<pre>';print_r($_POST);echo '</pre>';exit();
		$plugin = new HospitalTrack;

		if( !isset( $_POST ) )
			exit();

		$ignore = empty( $_POST['ignore'] ) ? 1 : 0;

		$ignore_key = 'ignore_' . $_POST['type'] . '_' . $_POST['region'];
		$data[$ignore_key] = $ignore;
		$where = array(
			'tab_id' => $_POST['tab_id']
			);

		$plugin->hos_db->update_pre_birth_table_status( $data, $where );
		exit();
	}

	function hospital_track_search_ajax() {
		$plugin = new HospitalTrack;

		$response = array();

		$data['model']  = $_POST['model'];
		$data['hos_action'] = $_POST['hos_action'];

		switch ( $data['hos_action'] ) {
			case $plugin->tel_book:
				$list = new Hospital_Pre_Birth_Tel_Book_Table( $data['model'] );

				break;

			case $plugin->track_result:
				$list = new Hospital_Pre_Birth_Track_Result_Table( $data['model'] );

				break;

			case $plugin->track_list:
				$list = new Hospital_Pre_Birth_Track_List_Table( $data['model'] );

				break;

			case $plugin->tel_list:
				$list = new Hospital_Pre_Birth_Tel_List_Table( $data['model'] );

				break;
			
			default:
				# code...
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

	//产前队列随访结果点击上传文件ajax
	function hospital_pre_birth_track_result_import_ajax() {
		$plugin = new HospitalTrack;

		$response = array();
		$response['error'] = '';

		$model = $_POST['model'];
		$hos_action = $_POST['hos_action'];

		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');
	
        $need_columns = array( 'no1' );

        switch ( $model ) {
            case $plugin->model_m1:
                $allow_columns = array(
                    'no1', 'm1_fpdate', 'm1_selfques', 'm1_epds', 'm1_phyexa', 'm1_icterus', 'm1_brmilks', 'm1_brmilkr', 'm1_urine', 'm1_fec', 'm1_bp', 'm1_telques', 'm1_telquesre', 'm1_telname', 'm1_telre1', 'm1_telre2', 'm1_telre3'
                    );
                $pre = 'm1_';

                break;

            case $plugin->model_m6:
                $allow_columns = array(
                    'no1', 'm6_fpdate', 'm6_selfques', 'm6_epds', 'm6_phyexa', 'm6_baily', 'm6_brmilks', 'm6_brmilkr', 'm6_urine', 'm6_fec', 'm6_rbt', 'm6_bp', 'm6_telques', 'm6_telquesre', 'm6_telname', 'm6_telre1', 'm6_telre2', 'm6_telre3'
                    );

                break;

            case $plugin->model_y1:
                $allow_columns = array(
                    'no1', 'y1_fpdate', 'cname', 'y1_selfques', 'y1_vision', 'y1_phyexa', 'y1_baily', 'y1_brmilks', 'y1_brmilkr', 'y1_urine', 'y1_fec', 'y1_rbt', 'y1_bpb', 'y1_bp', 'y1_telques', 'y1_telquesre', 'y1_telname', 'y1_telre1', 'y1_telre2', 'y1_telre3'
                    );

                break;

            case $plugin->model_y2:
                $allow_columns = array(
                    'no1', 'y2_fpdate', 'y2_selfques', 'y2_vision', 'y2_phyexa', 'y2_baily', 'y2_urine', 'y2_fec', 'y2_rbt', 'y2_bpb', 'y2_bp', 'y2_telques', 'y2_telquesre', 'y2_telname', 'y2_telre1', 'y2_telre2', 'y2_telre3'
                    );

                break;
            
            default:
                # code...
                break;
        }
		
		$file_ext = explode( '.', $_FILES['track_import']['name'] );
        $file_ext = $file_ext[ count( $file_ext ) - 1 ];

        if( $file_ext == 'xlsx' || $file_ext == 'xls' ){
            $PHPExcel = PHPExcel_IOFactory::load( $_FILES['track_import']['tmp_name'] );
        }else if( $file_ext == 'csv' ){
            //$PHPExcel = PHPExcel_IOFactory::load($uploaded_file);
            $objReader = PHPExcel_IOFactory::createReader('CSV')
                ->setDelimiter(',')
                ->setEnclosure('"')
                ->setSheetIndex(0);
            $PHPExcel = $objReader->load( $_FILES['track_import']['tmp_name'] );
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
		//echo '<pre>';print_r($form_infos);echo '</pre>';exit();
		// error_log(var_export($form_infos,true));exit();

        switch ( $model ) {
            case $plugin->model_m1:
                foreach ( $form_infos as $item ) {
                    $item_id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                    if( $item_id < 1 )
                        continue;

                    $basic_data = array();
                    $status_data = array();

                    $judge_arr = array(
                    	'm1_selfques', 'm1_epds', 'm1_phyexa', 'm1_icterus', 'm1_brmilks', 'm1_brmilkr', 'm1_urine', 'm1_fec', 'm1_bp', 'm1_telques'
                    );

                    foreach ( $judge_arr as $judge ) {
                    	if( !in_array( $judge, $upload_field_names ) )
                    		continue;

                        if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                            $status_data[ $judge ] = $item[ $judge ] = 1;
                        else
                            $status_data[ $judge ] = $item[ $judge ] = 0;
                    }

                    $telres_arr = array(
                    	'm1_telre1', 'm1_telre2', 'm1_telre3'
                    );

                    foreach ( $telres_arr as $telre ) {
                    	if( !in_array( $telre, $upload_field_names ) )
                    		continue;
                        
                        $status_data[ $telre ] = array_search( $item[ $telre ], $plugin->telre_arr );
                    }

                    $status_data['m1_telquesre'] = $item['m1_telquesre'] = array_search( $item['m1_telquesre'], $plugin->telquesre_arr );
                    $status_data['id'] = $item_id;
                    $status_data['m1_fpdate'] = $item['m1_fpdate'];

                    switch ( $hos_action ) {
                    	case $plugin->track_result:
                    		$check_arr = array(
			                    'm1_fpdate', 'm1_selfques', 'm1_epds', 'm1_phyexa', 'm1_icterus', 'm1_brmilks', 'm1_brmilkr', 'm1_urine', 'm1_fec', 'm1_bp'
			                );

			                $status = 0;
                    		foreach ( $check_arr as $check ) {
		                        if( $item[ $check ] ) {
		                            $status = 1;
		                            break;
		                        }
		                    }
		                    if( $status == 1 ){
		                    	$status_data['m1_fp'] = $basic_data['m1_fpstatus'] = 1;
		                    }else{
		                    	if( $item['m1_telques'] )
		                            $status_data['m1_fp'] = $basic_data['m1_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['m1_telquesre'] )
		                                $status_data['m1_fp'] = $basic_data['m1_fpstatus'] = $item['m1_telquesre'];//失联,拒绝,宝宝夭折
		                        }
		                    }

                    		break;

                    	case $plugin->tel_list:
                    		$user_info = $plugin->hos_db->get_pre_birth_info_by_id( $item_id );
                    		$status = $user_info['m1_telques'];

                    		if( $status != 1 ){
                    			if( $item['m1_telques'] )
		                            $status_data['m1_fp'] = $basic_data['m1_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['m1_telquesre'] )
		                                $status_data['m1_fp'] = $basic_data['m1_fpstatus'] = $item['m1_telquesre'];//失联,拒绝,宝宝夭折
		                        }
                    		}

                    		break;
                    	
                    	default:
                    		# code...
                    		break;
                    }

                    if( count( $basic_data ) > 0 ) 
                    	$plugin->hos_db->update_pre_birth_basic_info( $basic_data, array( 'id' => $item_id ) );

                    //随访表中的数据更新
                    $plugin->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                }

                break;

            case $plugin->model_m6:
                foreach ( $form_infos as $item ) {
                    $item_id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                    if( $item_id < 1 )
                        continue;

                    $basic_data = array();
                    $status_data = array();

                    $judge_arr = array(
                    	'm6_selfques', 'm6_epds', 'm6_phyexa', 'm6_baily', 'm6_brmilks', 'm6_brmilkr', 'm6_urine', 'm6_fec', 'm6_rbt', 'm6_bp', 'm6_telques'
                    );

                    foreach ( $judge_arr as $judge ) {
                    	if( !in_array( $judge, $upload_field_names ) )
                    		continue;

                        if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                            $status_data[ $judge ] = $item[ $judge ] = 1;
                        else
                            $status_data[ $judge ] = $item[ $judge ] = 0;
                    }

                    $telres_arr = array(
                    	'm6_telre1', 'm6_telre2', 'm6_telre3'
                    );

                    foreach ( $telres_arr as $telre ) {
                    	if( !in_array( $telre, $upload_field_names ) )
                    		continue;

                        $status_data[ $telre ] = array_search( $item[ $telre ] , $plugin->telre_arr );
                    }

                    $status_data['m6_telquesre'] = $item['m6_telquesre'] = array_search( $item['m6_telquesre'], $plugin->telquesre_arr );
                    $status_data['id'] = $item_id;
                    $status_data['m6_fpdate'] = $item['m6_fpdate'];

                    switch ( $hos_action ) {
                    	case $plugin->track_result:
                    		$check_arr = array(
		                    	'm6_fpdate', 'm6_selfques', 'm6_epds', 'm6_phyexa', 'm6_baily', 'm6_brmilks', 'm6_brmilkr', 'm6_urine', 'm6_fec', 'm6_rbt', 'm6_bp'
		                    );

			                $status = 0;
                    		foreach ( $check_arr as $check ) {
		                        if( $item[ $check ] ) {
		                            $status = 1;
		                            break;
		                        }
		                    }
		                    if( $status == 1 ){
		                    	$status_data['m6_fp'] = $basic_data['m6_fpstatus'] = 1;
		                    }else{
		                    	if( $item['m6_telques'] )
		                            $status_data['m6_fp'] = $basic_data['m6_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['m6_telquesre'] )
		                                $status_data['m6_fp'] = $basic_data['m6_fpstatus'] = $item['m6_telquesre'];//失联,拒绝,宝宝夭折
		                        }
		                    }

                    		break;

                    	case $plugin->tel_list:
                    		$user_info = $plugin->hos_db->get_pre_birth_info_by_id( $item_id );
                    		$status = $user_info['m6_telques'];

                    		if( $status != 1 ){
                    			if( $item['m6_telques'] )
		                            $status_data['m6_fp'] = $basic_data['m6_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['m6_telquesre'] )
		                                $status_data['m6_fp'] = $basic_data['m6_fpstatus'] = $item['m6_telquesre'];//失联,拒绝,宝宝夭折
		                        }
                    		}

                    		break;
                    	
                    	default:
                    		# code...
                    		break;
                    }
                    //echo '<pre>';print_r($basic_data);echo '</pre>';
                    //echo '<pre>';print_r($status_data);echo '</pre>';exit();

                    if( count( $basic_data ) > 0 ) 
                    	$plugin->hos_db->update_pre_birth_basic_info( $basic_data, array( 'id' => $item_id ) );

                    //随访表中的数据更新
                    $plugin->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                }

                break;

            case $plugin->model_y1:
            	foreach ( $form_infos as $item ) {
                    $item_id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                    if( $item_id < 1 )
                        continue;

                    $basic_data = array();
                    $status_data = array();

                    $judge_arr = array(
                    	'y1_selfques', 'y1_vision', 'y1_phyexa', 'y1_baily', 'y1_brmilks', 'y1_brmilkr', 'y1_urine', 'y1_fec', 'y1_rbt', 'y1_bpb', 'y1_bp', 'y1_telques'
                    );

                    foreach ( $judge_arr as $judge ) {
                    	if( !in_array( $judge, $upload_field_names ) )
                    		continue;

                        if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                            $status_data[ $judge ] = $item[ $judge ] = 1;
                        else
                            $status_data[ $judge ] = $item[ $judge ] = 0;
                    }

                    $telres_arr = array(
                    	'y1_telre1', 'y1_telre2', 'y1_telre3'
                    );

                    foreach ( $telres_arr as $telre ) {
                    	if( !in_array( $telre, $upload_field_names ) )
                    		continue;
                        
                        $status_data[ $telre ] = array_search( $item[ $telre ], $plugin->telre_arr );
                    }

                    $status_data['y1_telquesre'] = $item['y1_telquesre'] = array_search( $item['y1_telquesre'], $plugin->telquesre_arr );
                    $status_data['id'] = $item_id;
                    $status_data['y1_fpdate'] = $item['y1_fpdate'];

                    switch ( $hos_action ) {
                    	case $plugin->track_result:
                    		$check_arr = array(
		                    	'y1_fpdate', 'y1_selfques', 'y1_vision', 'y1_phyexa', 'y1_baily', 'y1_brmilks', 'y1_brmilkr', 'y1_urine', 'y1_fec', 'y1_rbt', 'y1_bpb', 'y1_bp'
		                    );

			                $status = 0;
                    		foreach ( $check_arr as $check ) {
		                        if( $item[ $check ] ) {
		                            $status = 1;
		                            break;
		                        }
		                    }
		                    if( $status == 1 ){
		                    	$status_data['y1_fp'] = $basic_data['y1_fpstatus'] = 1;
		                    }else{
		                    	if( $item['y1_telques'] )
		                            $status_data['y1_fp'] = $basic_data['y1_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['y1_telquesre'] )
		                                $status_data['y1_fp'] = $basic_data['y1_fpstatus'] = $item['y1_telquesre'];//失联,拒绝,宝宝夭折
		                        }
		                    }

                    		break;

                    	case $plugin->tel_list:
                    		$user_info = $plugin->hos_db->get_pre_birth_info_by_id( $item_id );
                    		$status = $user_info['y1_telques'];

                    		if( $status != 1 ){
                    			if( $item['y1_telques'] )
		                            $status_data['y1_fp'] = $basic_data['y1_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['y1_telquesre'] )
		                                $status_data['y1_fp'] = $basic_data['y1_fpstatus'] = $item['y1_telquesre'];//失联,拒绝,宝宝夭折
		                        }
                    		}

                    		break;
                    	
                    	default:
                    		# code...
                    		break;
                    }

                    if( count( $basic_data ) > 0 ) 
                    	$plugin->hos_db->update_pre_birth_basic_info( $basic_data, array( 'id' => $item_id ) );

                    //随访表中的数据更新
                    $plugin->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                }

                break;

            case $plugin->model_y2:
            	foreach ( $form_infos as $item ) {
                    $item_id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                    if( $item_id < 1 )
                        continue;

                    $basic_data = array();
                    $status_data = array();

                    $judge_arr = array(
                    	'y2_selfques', 'y2_vision', 'y2_phyexa', 'y2_baily', 'y2_urine', 'y2_fec', 'y2_rbt', 'y2_bpb', 'y2_bp', 'y2_telques'
                    );

                    foreach ( $judge_arr as $judge ) {
                    	if( !in_array( $judge, $upload_field_names ) )
                    		continue;

                        if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                            $status_data[ $judge ] = $item[ $judge ] = 1;
                        else
                            $status_data[ $judge ] = $item[ $judge ] = 0;
                    }

                    $telres_arr = array(
                    	'y2_telre1', 'y2_telre2', 'y2_telre3'
                    );

                    foreach ( $telres_arr as $telre ) {
                    	if( !in_array( $telre, $upload_field_names ) )
                    		continue;
                        
                        $status_data[ $telre ] = array_search( $item[ $telre ], $plugin->telre_arr );
                    }

                    $status_data['y2_telquesre'] = $item['y2_telquesre'] = array_search( $item['y2_telquesre'], $plugin->telquesre_arr );
                    $status_data['id'] = $item_id;
                    $status_data['y2_fpdate'] = $item['y2_fpdate'];

                    switch ( $hos_action ) {
                    	case $plugin->track_result:
                    		$check_arr = array(
		                    	'y2_fpdate', 'y2_selfques', 'y2_vision', 'y2_phyexa', 'y2_baily', 'y2_urine', 'y2_fec', 'y2_rbt', 'y2_bpb', 'y2_bp'
		                    );

			                $status = 0;
                    		foreach ( $check_arr as $check ) {
		                        if( $item[ $check ] ) {
		                            $status = 1;
		                            break;
		                        }
		                    }
		                    if( $status == 1 ){
		                    	$status_data['y2_fp'] = $basic_data['y2_fpstatus'] = 1;
		                    }else{
		                    	if( $item['y2_telques'] )
		                            $status_data['y2_fp'] = $basic_data['y2_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['y2_telquesre'] )
		                                $status_data['y2_fp'] = $basic_data['y2_fpstatus'] = $item['y2_telquesre'];//失联,拒绝,宝宝夭折
		                        }
		                    }

                    		break;

                    	case $plugin->tel_list:
                    		$user_info = $plugin->hos_db->get_pre_birth_info_by_id( $item_id );
                    		$status = $user_info['y2_telques'];

                    		if( $status != 1 ){
                    			if( $item['y2_telques'] )
		                            $status_data['y2_fp'] = $basic_data['y2_fpstatus'] = 2;//参加电话随访
		                        else{
		                            if( $item['y2_telquesre'] )
		                                $status_data['y2_fp'] = $basic_data['y2_fpstatus'] = $item['y2_telquesre'];//失联,拒绝,宝宝夭折
		                        }
                    		}

                    		break;
                    	
                    	default:
                    		# code...
                    		break;
                    }

                    if( count( $basic_data ) > 0 ) 
                    	$plugin->hos_db->update_pre_birth_basic_info( $basic_data, array( 'id' => $item_id ) );

                    //随访表中的数据更新
                    $plugin->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                }

                break;
            
            default:
                # code...
                break;
        }

        echo json_encode( $response );
        exit();
	}

    function hospital_pre_birth_edit_list_ajax() {
    	$plugin = new HospitalTrack;

		$response = array();

		$data['model']  = $_POST['model'];
		$data['hos_action'] = $_POST['hos_action'];

		switch ( $data['hos_action'] ) {
			case $plugin->tel_book:
				$list = new Hospital_Pre_Birth_Edit_Tel_Book_Table( $data['model'] );

				break;

			case $plugin->track_result:
				$list = new Hospital_Pre_Birth_Edit_Track_Result_Table( $data['model'] );

				break;

			case $plugin->tel_list:
				$list = new Hospital_Pre_Birth_Edit_Tel_List_Table( $data['model'] );

				break;
			
			default:
				# code...
				break;
		}
		$list->prepare_items();
		//echo '<pre>';print_r($list);echo '</pre>';exit();

		if( $data['hos_action'] == $plugin->tel_book || $data['hos_action'] == $plugin->tel_list ){
			ob_start();
			$plugin->get_pre_birth_edit_select_tables( $data['model'], $data['hos_action'] );
			$content = ob_get_contents();
			ob_end_clean();

			$response['hos_table_list'] = $content;
		}

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
		//echo '<pre>';print_r($response);echo '</pre>';exit();
		//
		echo json_encode( $response );
		exit();
    }

    function hospital_pre_birth_edit_save_ajax() {
    	$plugin = new HospitalTrack;
    	$hos_action = $_POST['hos_action'];
    	$model = $_POST['model'];
    	$id = $_POST['id'];
    	$where = array( 'id' => $id );
    	$response = array();

    	switch ( $hos_action ) {
    		case $plugin->tel_book:
				$basic_data['pphone'] = $_POST['pphone'];
		    	$basic_data['hphone'] = $_POST['hphone'];
		    	$patient_data['id'] = $id;

		    	switch ( $model ) {
		    		case $plugin->model_m1:
						$patient_data['m1_telre1']  = $_POST['telre1'];
						$patient_data['m1_telre2']  = $_POST['telre2'];
						$patient_data['m1_telre3']  = $_POST['telre3'];
						$patient_data['m1_telname'] = $_POST['telname'];

						if( $_POST['telre1'] == 100 ) 
							$patient_data['m1_telre1_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre1_remark'] );

						if( $_POST['telre2'] == 100 ) 
							$patient_data['m1_telre2_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre2_remark'] );

						if( $_POST['telre3'] == 100 ) 
							$patient_data['m1_telre3_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre3_remark'] );

		    			break;

		    		case $plugin->model_m6:
						$patient_data['m6_telre1']  = $_POST['telre1'];
						$patient_data['m6_telre2']  = $_POST['telre2'];
						$patient_data['m6_telre3']  = $_POST['telre3'];
						$patient_data['m6_telname'] = $_POST['telname'];

						if( $_POST['telre1'] == 100 ) 
							$patient_data['m6_telre1_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre1_remark'] );

						if( $_POST['telre2'] == 100 ) 
							$patient_data['m6_telre2_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre2_remark'] );

						if( $_POST['telre3'] == 100 ) 
							$patient_data['m6_telre3_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre3_remark'] );

		    			break;

		    		case $plugin->model_y1:
						$patient_data['y1_telre1']  = $_POST['telre1'];
						$patient_data['y1_telre2']  = $_POST['telre2'];
						$patient_data['y1_telre3']  = $_POST['telre3'];
						$patient_data['y1_telname'] = $_POST['telname'];

						if( $_POST['telre1'] == 100 ) 
							$patient_data['y1_telre1_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre1_remark'] );

						if( $_POST['telre2'] == 100 ) 
							$patient_data['y1_telre2_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre2_remark'] );

						if( $_POST['telre3'] == 100 ) 
							$patient_data['y1_telre3_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre3_remark'] );

		    			break;

		    		case $plugin->model_y2:
						$patient_data['y2_telre1']  = $_POST['telre1'];
						$patient_data['y2_telre2']  = $_POST['telre2'];
						$patient_data['y2_telre3']  = $_POST['telre3'];
						$patient_data['y2_telname'] = $_POST['telname'];

						if( $_POST['telre1'] == 100 ) 
							$patient_data['y2_telre1_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre1_remark'] );

						if( $_POST['telre2'] == 100 ) 
							$patient_data['y2_telre2_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre2_remark'] );

						if( $_POST['telre3'] == 100 ) 
							$patient_data['y2_telre3_extend'] = $plugin->hos_db->get_remark_id_by_content( $_POST['telre3_remark'] );

		    			break;
		    		
		    		default:
		    			# code...
		    			break;
		    	}

		    	$plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );
		    	$plugin->hos_db->update_pre_birth_patient_status( $patient_data, $id, $model );

				break;

			case $plugin->track_result:
				foreach ( $_POST['form_data'] as $item) {
					$data[$item['name']] = $item['value'];
				}

				$patient_data['id'] = $id;

				switch ( $model ) {
		    		case $plugin->model_m1:
		    			$pre = 'm1_';
						$options = array( 'selfques', 'epds', 'phyexa', 'icterus', 'brmilkr', 'brmilks', 'urine', 'fec', 'bp' );

						$patient_data['m1_fpdate'] = $data['hos_edit_date'];

		    			break;

		    		case $plugin->model_m6:
		    			$pre = 'm6_';
		    			$options = array( 'selfques', 'epds', 'phyexa', 'baily', 'brmilkr', 'brmilks', 'urine', 'fec', 'rbt', 'bp' );

		    			$patient_data['m6_fpdate'] = $data['hos_edit_date'];

		    			break;

		    		case $plugin->model_y1:
		    			$pre = 'y1_';
		    			$options = array( 'selfques', 'vision', 'phyexa', 'baily', 'brmilkr', 'brmilks', 'rbt', 'bpb', 'urine', 'fec', 'bp' );

		    			$patient_data['y1_fpdate'] = $data['hos_edit_date'];
						$basic_data['cname'] = $data['hos_edit_cname'];

		    			break;

		    		case $plugin->model_y2:
		    			$pre = 'y2_';
		    			$options = array( 'selfques', 'vision', 'phyexa', 'baily', 'bpb', 'urine', 'fec', 'rbt', 'bp' );

		    			$patient_data['y2_fpdate'] = $data['hos_edit_date'];

		    			break;
		    		
		    		default:
		    			# code...
		    			break;
		    	}

		    	$basic_data[$pre . 'fpstatus'] = 0;
				$patient_data[$pre . 'fp']     = 0;

				foreach ( $options as $option) {
					if( $data['hos_edit_' . $option] ) {
						$basic_data[$pre . 'fpstatus'] = 1;
						$patient_data[$pre . 'fp']     = 1;
					}

					$patient_data[$pre . $option] = $data['hos_edit_' . $option];
				}

				$plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );
		    	$plugin->hos_db->update_pre_birth_patient_status( $patient_data, $id, $model );

				break;

			case $plugin->tel_list:
				$basic_data['pphone'] = $_POST['pphone'];
		    	$basic_data['hphone'] = $_POST['hphone'];
		    	$patient_data['id'] = $id;

		    	switch ( $model ) {
		    		case $plugin->model_m1:
						$patient_data['m1_telques']  = $_POST['telques'];
						if( $_POST['telques'] != 1 ){
							$patient_data['m1_telquesre']  = $_POST['telquesre'];
							$basic_data['m1_fpstatus'] = $_POST['telquesre'];
						}else{
							$basic_data['m1_fpstatus'] = 2;
						}

		    			break;

		    		case $plugin->model_m6:
						$patient_data['m6_telques']  = $_POST['telques'];
						if( $_POST['telques'] != 1 ){
							$patient_data['m6_telquesre']  = $_POST['telquesre'];
							$basic_data['m6_fpstatus'] = $_POST['telquesre'];
						}else{
							$basic_data['m6_fpstatus'] = 2;
						}

		    			break;

		    		case $plugin->model_y1:
						$patient_data['y1_telques']  = $_POST['telques'];
						if( $_POST['telques'] != 1 ){
							$patient_data['y1_telquesre']  = $_POST['telquesre'];
							$basic_data['y1_fpstatus'] = $_POST['telquesre'];
						}else{
							$basic_data['y1_fpstatus'] = 2;
						}

		    			break;

		    		case $plugin->model_y2:
						$patient_data['y2_telques']  = $_POST['telques'];
						if( $_POST['telques'] != 1 ){
							$patient_data['y2_telquesre']  = $_POST['telquesre'];
							$basic_data['y2_fpstatus'] = $_POST['telquesre'];
						}else{
							$basic_data['y2_fpstatus'] = 2;
						}

		    			break;
		    		
		    		default:
		    			# code...
		    			break;
		    	}

		    	$plugin->hos_db->update_pre_birth_basic_info( $basic_data, $where );
		    	$plugin->hos_db->update_pre_birth_patient_status( $patient_data, $id, $model );

				break;
			
			default:
				# code...
				break;
    	}

    	echo json_encode( $response );
		exit();
    }

    function hospital_pre_birth_get_track_result_ajax() {
    	$plugin = new HospitalTrack;

    	$id = $_POST['id'];
    	$model = $_POST['model'];

    	$result = $plugin->hos_db->get_patient_info_status_by_id( $id, $model );
    	$form_content = $plugin->get_pre_birth_track_form( $model, $result );

    	echo json_encode( $form_content );
		exit();
    }

    function get_pre_birth_track_form( $model, $item ) {
    	$data = array();
    	$fpdate = '';
    	switch ( $model ) {
    		case $this->model_m1:
    			$track_options_arr = array( 'selfques', 'epds', 'phyexa', 'icterus', 'brmilkr', 'brmilks', 'urine', 'fec', 'bp' );
    			$title = '1月随访时间';
    			$fpdate = $item['m1_fpdate'];
    			$pre = 'm1_';

    			break;

    		case $this->model_m6:
    			$track_options_arr = array( 'selfques', 'epds', 'phyexa', 'baily', 'brmilkr', 'brmilks', 'urine', 'fec', 'rbt', 'bp' );
    			$title = '6月随访时间';
    			$fpdate = $item['m6_fpdate'];
    			$pre = 'm6_';
    			break;

    		case $this->model_y1:
    			$track_options_arr = array( 'selfques', 'vision', 'phyexa', 'baily', 'brmilkr', 'brmilks', 'rbt', 'bpb', 'urine', 'fec', 'bp' );
    			$title = '1岁随访时间';
    			$fpdate = $item['y1_fpdate'];
    			$pre = 'y1_';
    			break;

    		case $this->model_y2:
    			$track_options_arr = array( 'selfques', 'vision', 'phyexa', 'baily', 'bpb', 'urine', 'fec', 'rbt', 'bp' );
    			$title = '2岁随访时间';
    			$fpdate = $item['y2_fpdate'];
    			$pre = 'y2_';
    			break;
    		
    		default:
    			# code...
    			break;
    	}

    	if( empty( $fpdate ) )
    		$fpdate = date( 'Y-m-d' );

		$data['track_options_arr'] = $track_options_arr;
		$data['model']             = $model;
		$data['title']             = $title;
		$data['item']              = $item;
		$data['fpdate']            = $fpdate;
		$data['pre']               = $pre;

		ob_start();
		$this->load_view( 'pre-birth-edit', 'pre-birth-edit-track-form', $data );
		$track_form_html = ob_get_contents();
		ob_end_clean();

    	return $track_form_html;
    }

    function track_export_file() {
    	foreach ($_REQUEST as $key => $value) {
    		$_REQUEST[$key] = esc_attr( $value );
    	}

		$response = array();

		$model = $_REQUEST['model'];
		$hos_action = $_REQUEST['hos_action'];
		$group = $_REQUEST['group'];
		$file_foot = '';

		switch ( $model ) {
			case $this->model_m1:
				$pre = 'm1_';
				$pre_title = '产前队列-1月';

				break;

			case $this->model_m6:
				$pre = 'm6_';
				$pre_title = '产前队列-6月';

				break;

			case $this->model_y1:
				$pre = 'y1_';
				$pre_title = '产前队列-1岁';

				break;

			case $this->model_y2:
				$pre = 'y2_';
				$pre_title = '产前队列-2岁';

				break;
			
			default:
				# code...
				break;
		}

		switch ( $hos_action ) {
			case $this->tel_book:
				$list = new Hospital_Pre_Birth_Tel_Book_Table( $model );
				$ex_title = '随访预约';

				break;

			case $this->track_result:
				$list = new Hospital_Pre_Birth_Track_Result_Table( $model );
				$ex_title = '随访结果';
				$file_foot = '武汉' . $pre_title . '现场随访结果';

				break;

			case $this->track_list:
				$list = new Hospital_Pre_Birth_Track_List_Table( $model );
				$ex_title = '现场随访名单';
				$file_foot = '武汉' . $pre_title . '现场随访名单';
				if( isset( $_REQUEST['tab_no'] ) && !empty( $_REQUEST['tab_no'] ) )
					$file_foot .= $_REQUEST['tab_no'];

				break;

			case $this->tel_list:
				$list = new Hospital_Pre_Birth_Tel_List_Table( $model );
				$ex_title = '电话随访名单';
				$file_foot = '武汉' . $pre_title . '电话随访名单';
				if( isset( $_REQUEST['tab_no'] ) && !empty( $_REQUEST['tab_no'] ) )
					$file_foot .= $_REQUEST['tab_no'];

				break;
			
			default:
				# code...
				break;
		}

		switch ( $group ){
			case 'pregnant_middle':
			//$pre = 'y2_';
			$pre_title = '产前队列-孕中期';
			$list = new Hospital_Pre_Birth_Pregnant_Midlle_List_Table();
			$ex_title = '电话随访状态名单';
			$file_foot = '武汉' . $pre_title . '电话随访状态名单';

			break;

			case 'pregnant_b' :
				$pre_title = '产前队列-孕期';
				$list = new Hospital_Pre_Birth_Pregnant_B_List_Table();
				$ex_title = '超声检查状态';
				$file_foot = '武汉' . $pre_title . '超声检查状态';
				
				

			break;
			
			default:
			#code ......
			break;
		}

		$list->prepare_items();
	
		// echo '<pre>';print_r($list->total_items);echo '</pre>';
		// error_log(var_export($list->column_header_define,true));
		// echo '<pre>';print_r($list->column_header_define);echo '</pre>';
		// exit();
		$new_items = array();
		if( count( $list->total_items ) > 0 ){
			foreach ( $list->total_items as $key1 => $item ) {
				foreach ( $list->column_header_define as $key2 => $value ) {
					
					if( $key2 == 'baby_mon' ){
						$new_items[$key1][$key2] = $list->column_baby_mon( $item );

						continue;
					}

					if( $key2 == 'fpstatus' ) {
						$fpstatus = $item[$pre.'fpstatus'];
						$dedate = $item['dedate'];
						$status_id = $this->hos_get_two_year_track_status( $fpstatus, $model, $dedate );
						$new_items[$key1][$key2] = $this->track_status_content[$status_id];

						continue;
					}

					if( array_key_exists( $key2, $this->track_arr ) || $key2 == 'telques' ) {
						$new_items[$key1][$key2] = ( 1 == $item[$pre.$key2] ) ? 1 : 0;

						continue;
					}

					if( $key2 == 'telquesre' ) {
						$new_items[$key1][$key2] = $this->telquesre_arr[ $item[$pre.$key2] ];

						continue;
					}

					if( $key2 == 'fpdate' ) {
						$new_items[$key1][$key2] = $this->translate_date( $item[$pre.$key2] );

						continue;
					}

					if( $key2 == 'dedate' ) {
						$new_items[$key1][$key2] = $this->translate_date( $item[$key2] );
			
						continue;
					}

					if( $key2 == 'first_date' ) {
						$new_items[$key1][$key2] = $list->column_first_date( $item );
						continue;
					}

					if( $key2 == 'second_date' ) {
						$new_items[$key1][$key2] = $list->column_second_date( $item );
						continue;
					}

					if( $key2 == 'last_date' ) {
						$new_items[$key1][$key2] = $list->column_last_date( $item );
						continue;
					}

					if( $key2 == 'telre1' ) {
						$new_items[$key1][$key2] = $list->column_telre1( $item );
						continue;
					}

					if( $key2 == 'telre2' ) {
						$new_items[$key1][$key2] = $list->column_telre2( $item );
						continue;
					}

					if( $key2 == 'telre3' ) {
						$new_items[$key1][$key2] = $list->column_telre3( $item );
						continue;
					}
					if( 'tool' == $key2){
						continue;
					}

					if( 'date27_0' == $key2){
						
						$new_items[$key1][$key2] = date("Y-m-d",strtotime( $item['lmp'])+16328600  );
						continue;
					}

					$new_items[$key1][$key2] = $item[$key2];
				}
			}
		}
		// echo '<pre>';print_r($list->column_header_define);echo '</pre>';
		// echo '<pre>';print_r($new_items);echo '</pre>';
		//  exit();
		ob_end_clean();

		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');
		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel/IOFactory.php');

		$PHPExcel = new PHPExcel();
		$PHPExcel->setActiveSheetIndex(0);
		$PHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
		$PHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter( '&L&B' . $file_foot . '&R第 &P 页 共 &N 页' );

		$tpo = 'A';
		foreach ( $list->column_header_define as $key => $title ) {
			$PHPExcel->getActiveSheet()->setCellValue($tpo.'1', $title );
			$PHPExcel->getActiveSheet()->getColumnDimension($tpo)->setAutoSize(true);
			$tpo++;
		}

		$ppo = 'A';
		foreach ( $list->column_parameters as $key => $title ) {
			$PHPExcel->getActiveSheet()->setCellValue($ppo.'2', $title );
			$PHPExcel->getActiveSheet()->getColumnDimension($ppo)->setAutoSize(true);
			$ppo++;
		}

	    $m=3;
	    foreach ( $new_items as $item ) {
	    	$n='A';
	    	foreach ( $item as $key => $value ) {
	    		$PHPExcel->getActiveSheet()->setCellValue($n.$m, $value );
	    		$n++;
	    	}
	    	$m++;
	    }
	    $filename = $pre_title . $ex_title;

		header('Content-Type: application/download;charset=utf-8'); 
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"'); 
		header('Cache-Control: max-age=0'); 
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
		$objWriter->save('php://output');

		exit();
    }

    function final_stat_results() {
    	echo '<br /><br />';
    	echo '暂时无内容,根据医院需求而定,预计做随访率统计页面';
    	//phpinfo();
    }

    function user_manage() {
    	echo '<br /><br />';
    	echo '暂时无内容,预备做人员和权限管理页面';
    }


	function is_arrays($array){
		$s = 1;
		foreach ($array as  $value) {
			if(is_array($value)){
				$s = 2;
			}
		}
		return $s;
	}

	function email_and_phone(){
			if(isset($_POST['hos_phone_number']) ) {
				$hos_phone_number  = json_encode($_POST['hos_phone_number']);
				$hos_phone_status = update_option('hos_phone_number',$hos_phone_number);
			}
			if(isset($_POST['hos_manager_email']) ) {
				$hos_manager_email = json_encode($_POST['hos_manager_email']);
				$hos_email_status = update_option('hos_manager_email',$hos_manager_email);
			}
		?>
		<div> 
			<?php 
				if( $hos_phone_status ){
					?>
					<div class="updated">
						<p>
						电话号码已保存
						<span id="phone_dashicons" style="float:right;" class="dashicons dashicons-dismiss"></span>
						</p>
					</div>	
					<?php
				}
				if( $hos_email_status ){
					?>
					<div class="updated">
						<p>
						邮箱已保存
						<span id="email_dashicons" style="float:right;" class="dashicons dashicons-dismiss"></span>
						</p>
					</div>	
					<?php
				}
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('#phone_dashicons').mouseover(function(){
			 			jQuery("#phone_dashicons").css("cursor","pointer");
			 			jQuery("#phone_dashicons").css("color","red");
					});

					jQuery('#phone_dashicons').mouseout(function(){
			 			jQuery("#phone_dashicons").css("cursor","default");
			 			jQuery("#phone_dashicons").css("color","#444444");
					});
					jQuery('#email_dashicons').mouseover(function(){
			 			jQuery("#email_dashicons").css("cursor","pointer");
			 			jQuery("#email_dashicons").css("color","red");
					});

					jQuery('#email_dashicons').mouseout(function(){
			 			jQuery("#email_dashicons").css("cursor","default");
			 			jQuery("#email_dashicons").css("color","#444444");
					});


					jQuery('#phone_dashicons').click(function(){
						jQuery(this).parent().parent().hide();
					});
					jQuery('#email_dashicons').click(function(){
						jQuery(this).parent().parent().hide();
					});
				});
			</script>
			<h2> 预警手机邮件设置</h2>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
				<p>
					<label>请输入手机(一行一个):</label>
					</br>
					<textarea rows="5" name="hos_phone_number"><?php echo trim(json_decode(get_option('hos_phone_number'))); ?></textarea>
				</p>
				<p>
					</br>
					<label>请输入邮箱(一行一个):</label>
					</br>
					<textarea rows="5" name="hos_manager_email"><?php echo json_decode(get_option('hos_manager_email')); ?></textarea>
					</br>
				</p>
				<input type="submit" value="保存"  class="button-primary"/>  
			</form>
		</div>
		<?php
	}

}

function stacktech_init_hospital_track() {  
	$plugin = new HospitalTrack;
	$four_track = new HospitalFourTrack;

    add_menu_page( __('医院随访管理'), __('医院随访管理'), "manage_options", 'hospital_track', array( $plugin, 'final_stat_results' ), 'dashicons-welcome-view-site' );
    add_submenu_page( 'hospital_track', __('统计列表'), __('统计列表'), 'manage_options', 'hospital_track' ,array( $plugin, 'hos_stat' ) );
    
    add_submenu_page( 'hospital_track', __('产前队列管理'), __('产前队列管理'), "manage_options", 'pre_birth_manage', array( $plugin, 'pre_birth_manage' ) );
    add_submenu_page( 'hospital_track', __('出生队列管理'), __('出生队列管理'), "manage_options", 'four_track_manage', array( $four_track, 'four_track_manage' ) );
    add_submenu_page( 'hospital_track', __('文件导入'), __('文件导入'), "manage_options", 'muti_import', array( $plugin, 'muti_import' ) );
    add_submenu_page( 'hospital_track', __('预警手机邮件设置'), __('预警手机邮件设置'), 'manage_options', 'email_phone', array( $plugin, 'email_and_phone' ) );
    
    //add_submenu_page( 'hospital_track', __('数据调试'), __('数据调试'), 'manage_network', 'hos_test' ,array( $plugin, 'hos_test' ) );
    add_action( 'wp_dashboard_setup', array( $plugin,'load_widgets' ) );
    add_submenu_page( null, null, null, 'manage_options', 'track_export_file', array( $plugin, 'track_export_file' ) );
    add_submenu_page( null, null, null, 'manage_options', 'four_track_export', array( $four_track, 'four_track_export' ) );

    //add_submenu_page( 'hospital_track', __('成员管理'), __('成员管理'), "manage_options", 'user_manage', array( $plugin, 'user_manage' ) );

  //   if( !current_user_can('manage_options') ){
		// remove_menu_page('hospital_track');
  //   }

}

function stacktech_hospital_load_scripts( $hook ) {
	$page_arr = array( 'hospital_track', 'pre_birth_manage', 'muti_import','four_track_manage' );
	if( !in_array( $_GET['page'], $page_arr ) && 'index.php' != $hook ) 
		return;

	wp_enqueue_script( 'hospital-jquery-script', 'https://cdn.bootcss.com/jquery/2.2.1/jquery.min.js' );
    wp_enqueue_script( 'hospital-track-ui-script', 'https://cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.js' );
    wp_enqueue_script( 'hospital-swiper-min-script', 'https://cdn.bootcss.com/Swiper/3.3.1/js/swiper.jquery.min.js' );
    wp_enqueue_script( 'jquery-ui-widget-script', 'https://cdn.bootcss.com/blueimp-file-upload/9.10.4/vendor/jquery.ui.widget.js' );
	wp_enqueue_script( 'blueimp-mix-script', plugin_dir_url( __FILE__ ) . 'js/blueimp.mix.js' );
	wp_enqueue_script( 'hospital-upload-main-script', plugin_dir_url( __FILE__ ) . 'js/hos_upload_main.js' );
	wp_enqueue_script( 'jquery-fileupload-mix-script', plugin_dir_url( __FILE__ ) . 'js/jquery.fileupload.mix.js' );
	wp_enqueue_script( 'jquery-bootstrap-min-script', 'http://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js' );
	wp_enqueue_script( 'hospital-track-script', plugin_dir_url( __FILE__ ) . 'js/hospital_track.js' );
	wp_enqueue_script( 'hospital-pnotify-script', 'http://cdn.bootcss.com/pnotify/3.0.0/pnotify.min.js' );
	wp_enqueue_script( 'hospital-sweet-script', 'https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js' );
	wp_enqueue_script( 'hospital-jquery-form-script', 'https://cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js' );

	//sometimes for my bad network
	// wp_enqueue_script( 'hospital-jquery-script', plugin_dir_url( __FILE__ ) . 'js/jquery.min.js' );
 //    wp_enqueue_script( 'hospital-track-ui-script', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js' );
 //    wp_enqueue_script( 'hospital-swiper-min-script', plugin_dir_url( __FILE__ ) . 'js/swiper.jquery.min.js' );
 //    wp_enqueue_script( 'jquery-ui-widget-script', plugin_dir_url( __FILE__ ) . 'js/jquery.ui.widget.js' );
	// wp_enqueue_script( 'blueimp-mix-script', plugin_dir_url( __FILE__ ) . 'js/blueimp.mix.js' );
	// wp_enqueue_script( 'hospital-upload-main-script', plugin_dir_url( __FILE__ ) . 'js/hos_upload_main.js' );
	// wp_enqueue_script( 'jquery-fileupload-mix-script', plugin_dir_url( __FILE__ ) . 'js/jquery.fileupload.mix.js' );
	// wp_enqueue_script( 'jquery-bootstrap-min-script', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js' );
	// wp_enqueue_script( 'hospital-track-script', plugin_dir_url( __FILE__ ) . 'js/hospital_track.js' );
	// wp_enqueue_script( 'hospital-pnotify-script', plugin_dir_url( __FILE__ ) . 'js/pnotify.min.js' );
	// wp_enqueue_script( 'hospital-sweet-script', plugin_dir_url( __FILE__ ) . 'js/sweetalert.min.js' );
	// wp_enqueue_script( 'hospital-jquery-form-script', plugin_dir_url( __FILE__ ) . 'js/jquery.form.min.js' );

	

    wp_enqueue_style( 'hospital-track-ui-css', 'https://cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.css' );
    wp_enqueue_style( 'hospital-swiper-min-css', 'https://cdn.bootcss.com/Swiper/3.3.0/css/swiper.min.css' );
    wp_enqueue_style( 'bootstrap-min-css', 'http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.css' );
	wp_enqueue_style( 'jquery-fileupload-mix-css', plugin_dir_url( __FILE__ ) . 'css/jquery.fileupload.mix.css' );
	wp_enqueue_style( 'hospital-track-css', plugin_dir_url( __FILE__ ) . 'css/hospital-track.css' );
	wp_enqueue_style( 'hospital-pnotify-min-style', 'https://cdn.bootcss.com/pnotify/3.0.0/pnotify.min.css' );
	wp_enqueue_style( 'hospital-pnotify-brighttheme-style', 'https://cdn.bootcss.com/pnotify/3.0.0/pnotify.brighttheme.min.css' );
	wp_enqueue_style( 'hospital-sweet-style', 'https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css' );
	wp_enqueue_style( 'crm-font-awesome-style', 'https://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css' );

	// wp_enqueue_style( 'hospital-track-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css' );
 //    wp_enqueue_style( 'hospital-swiper-min-css', plugin_dir_url( __FILE__ ) . 'css/swiper.min.css' );
    //wp_enqueue_style( 'bootstrap-min-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css' );
	// wp_enqueue_style( 'jquery-fileupload-mix-css', plugin_dir_url( __FILE__ ) . 'css/jquery.fileupload.mix.css' );
	// wp_enqueue_style( 'hospital-track-css', plugin_dir_url( __FILE__ ) . 'css/hospital-track.css' );
	// wp_enqueue_style( 'hospital-pnotify-min-style', plugin_dir_url( __FILE__ ) . 'css/pnotify.min.css' );
	// wp_enqueue_style( 'hospital-pnotify-brighttheme-style', plugin_dir_url( __FILE__ ) . 'css/pnotify.brighttheme.min.css' );
	// wp_enqueue_style( 'hospital-sweet-style', plugin_dir_url( __FILE__ ) . 'css/sweetalert.min.css' );
	// wp_enqueue_style( 'hospital-font-awesome-style', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css' );
}

add_action( 'admin_menu', 'stacktech_init_hospital_track' );

register_activation_hook( __FILE__, array( 'HospitalTrack', 'plugin_activation' ) );

add_action( 'wp_ajax_hos-muti-upload-ajax', array( 'HospitalTrack','hospital_muti_upload_ajax' ) );

add_action( 'wp_ajax_hospital-track-ignore-ajax', array( 'HospitalTrack','hospital_track_ignore_ajax' ) );
add_action( 'wp_ajax_hospital-track-search-ajax', array( 'HospitalTrack','hospital_track_search_ajax' ) );
add_action( 'wp_ajax_hospital-pre-birth-edit-list-ajax', array( 'HospitalTrack','hospital_pre_birth_edit_list_ajax' ) );
add_action( 'wp_ajax_hospital-pre-birth-edit-save-ajax', array( 'HospitalTrack','hospital_pre_birth_edit_save_ajax' ) );
add_action( 'wp_ajax_hospital-pre-birth-get-track-result-ajax', array( 'HospitalTrack','hospital_pre_birth_get_track_result_ajax' ) );
add_action( 'wp_ajax_hospital-pre-birth-track-result-import-ajax', array( 'HospitalTrack','hospital_pre_birth_track_result_import_ajax' ) );




add_action('admin_enqueue_scripts', 'stacktech_hospital_load_scripts');

include_once( plugin_dir_path( __FILE__ ) . 'hospital_four_track.php' );
include_once( plugin_dir_path( __FILE__ ) . 'hospital_pre_birth_pregnant_track.php' );
?>