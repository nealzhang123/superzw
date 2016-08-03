<?php 
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Health_Manage_List_Table extends WP_List_Table{
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
		$table = $wpdb->prefix . 'hos_pre_birth_health_manage_info';
		$sql = 'SELECT  count(*) FROM '. $table . ' order by id ASC ';
		$count = $wpdb->get_var($sql);
		return $count;
	}

	function get_userinfo(){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_health_manage_info';
		$sql = 'SELECT  health_manage.id, basic.no1, basic.name, health_manage.var59, health_manage.bw_p90gs, health_manage.bw_p10gs, health_manage.lbw, health_manage.macro, health_manage.preterm, health_manage.sga, health_manage.lga, health_manage.neobd_001, health_manage.matpih, health_manage.matgdm FROM '. $table . ' as health_manage inner join ' . $table_basic . ' as basic on basic.id = health_manage.id order by id ASC ';
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
		$columns['no1']					= '队列编号1';
		$columns['name']				= '母亲姓名';
		$columns['var59']				= '出生体重';
		$columns['bw_p90gs']			= '出生体重百分位数(90%)';
		$columns['bw_p10gs']			= '出生体重百分位数(10%)';
		$columns['lbw']					= '低出生体重';
		$columns['macro']				= '巨大儿';
		$columns['preterm']				= '早产';
		$columns['sga']					= '小于胎龄儿';
		$columns['lga']					= '大于胎龄儿';
		$columns['neobd_001']			= '新生儿畸形';
		$columns['matpih']				= '妊娠期高血压';
		$columns['matgdm']				= '妊娠期糖尿病';
		$columns['tool']				= '编辑';
		$this->column_parameters = array(
			'no1',
			'name',
			'var59',
			'bw_p90gs',
			'bw_p10gs',
			'lbw',
			'macro',
			'preterm',
			'sga',
			'lga',
			'neobd_001',
			'matpih',
			'matgdm'
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

    function column_tool($item){
    	 echo '<a class="pre_health_manage_item_edit" data-id="' . $item['id'] . '" m1_tab_no="0" sys_tab_no="0"><i class="fa fa-pencil-square-o"></i>编辑</a>';
    }

}