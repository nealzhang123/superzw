<?php 
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Pregnant_Midlle_List_Table extends WP_List_Table{
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
		$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
		//$sql = 'SELECT count(*) FROM ' . $table_basic .' ORDER BY id ASC';
		$sql = 'SELECT count(*) FROM ' . $table_basic . ' basic inner join ' .$table . ' pregnant_middle WHERE basic.id = pregnant_middle.id ';
		$sql .= '  and floor((unix_timestamp(date_format(date_sub(curdate(),interval WEEKDAY(curdate()) day),"%Y-%m-%d 12:00:00")) - unix_timestamp( date_format(basic.lmp,"%Y-%m-%d 12:00:00")))/604800)>=27 and ( pregnant_middle.serumt2 = 0 or pregnant_middle.urinet2 = 0) ';
		$sql .= ' order by pregnant_middle.id ASC';
		// $sql1 = 'SELECT * FROM ' . $table_basic . ' basic inner join ' .$table . ' pregnant_middle WHERE basic.id = pregnant_middle.id  order by pregnant_middle.id ASC';
		$count = $wpdb->get_var($sql);
		// $results = $wpdb->get_results($sql1,ARRAY_A);
		// foreach ($results as $key => $item) {
		// 	$days=abs((strtotime(date("Y-m-d 12:00:00"))-strtotime($item['lmp']))/86400);
		// 	$week = floor($days/7);
		// 	$day = $days%7;
		// 	if( !($week >= 27 && ( (0 == $item['serumt2']) || (0 == $item['urinet2'])  ) ) ){
		// 		unset($results[$key]);
		// 	}
		// }
		//error_log(var_export($results, true));
		return $count;
		//return count($results);
	}

	function get_userinfo(){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';

 		//SELECT * FROM gkmysql.stacktech_hos_pre_birth_basic_info basic inner join gkmysql.	stacktech_hos_pre_birth_three_pregnant_info pregnant_middle WHERE basic.id = pregnant_middle.id  and (floor(abs((unix_timestamp(date_format(now(), "%Y-%m-%d 12:00:00"))-unix_timestamp( basic.lmp ))/86400) ) >= 27) and ( pregnant_middle.serumt2 = 0 or pregnant_middle.urinet2 = 0) order by pregnant_middle.id ASC


		//$sql1 = 'SELECT *, basic.no1, basic.name, basic.lmp, basic.pphone, basic.hphone FROM ' .$table_basic . ' basic inner join '. $table .' pregnant  WHERE basic.id =  pregnant.id ORDER BY pregnant.id ASC';
		//$sql = 'SELECT count(*) FROM ' . $table_basic .' ORDER BY id ASC';

		$sql = 'SELECT basic.id, basic.no1, basic.name, basic.lmp, basic.pphone, basic.hphone, pregnant_middle.serumt2, pregnant_middle.urinet2, pregnant_middle.fut2rem  FROM ' . $table_basic . ' basic inner join ' .$table . ' pregnant_middle WHERE basic.id = pregnant_middle.id ';
		$sql .= '  and floor((unix_timestamp(date_format(date_sub(curdate(),interval WEEKDAY(curdate()) day),"%Y-%m-%d 12:00:00")) - unix_timestamp( date_format(basic.lmp,"%Y-%m-%d 12:00:00")))/604800)>=27 and ( pregnant_middle.serumt2 = 0 or pregnant_middle.urinet2 = 0) ';
		$sql1 = $sql. ' order by pregnant_middle.id ASC ';
		$this->total_items = $wpdb->get_results($sql1, ARRAY_A);
		$sql .= ' order by pregnant_middle.id ASC';
		//error_log($sql1);
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
		$sql .=' LIMIT '. $start . ',' . $page_per_num;
		
		$results = $wpdb->get_results($sql,'ARRAY_A');
		return $results;
	}

	function get_columns(){
		$columns['no1']				= '队列编号1';
		$columns['name']			= '母亲姓名';
		$columns['pphone']			= '母亲电话';
		$columns['hphone']			= '父亲电话';
		$columns['date27_0']		= '孕中检日期27+0';
		$columns['serumt2']			= '中期血清';
		$columns['urinet2']			= '中期尿';
		$columns['fut2rem']			= '孕中期电话备注';
		$columns['tool']			= '操作';

		$this->column_parameters = array(
			'no1',
			'name',
			'tel1',
			'tel2',
			'date27+0',
			'serumt2',
			'urinet2',
			'fut2rem',

		);
		return $columns;
	}

	function get_hidden_columns(){
		return array('id');
	}

	function column_date27_0( $item ){
		//error_log(var_export($item,true));
		$date = date("Y-m-d",strtotime( $item['lmp'])+16328600  );
		echo  '<div id="date27_0_'.$item['id'].'">'. $date .'</div>';

	}

	function column_default( $item, $column_name ){
		if( 'serumt2' == $column_name ){

			if( 1 == $item['serumt2'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}elseif( 'urinet2' == $column_name ){

			if( 1 == $item['urinet2'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}else{

			echo '<div id="'.$column_name.'_'.$item['id'].'">'.$item[$column_name].'</div>';
		}
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function column_tool( $item ) {
        echo '<a class="pre_pregnant_middle_item_edit" data-id="' . $item['id'] . '" m1_tab_no="0" sys_tab_no="0"><i class="fa fa-pencil-square-o"></i>编辑</a>';
    }
}