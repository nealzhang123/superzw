<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Tel_List_Table extends WP_List_Table {
	public $model;
	public $plugin, $pre;
    public $total_count,$total_items;
    public $column_header_define, $column_parameters;

	function __construct( $model ){
		$this->model = $model;
		$this->plugin = new HospitalTrack;

        switch ( $this->model) {
            case $this->plugin->model_m1:
                $this->pre = 'm1_';

                break;

            case $this->plugin->model_m6:
                $this->pre = 'm6_';

                break;

            case $this->plugin->model_y1:
                $this->pre = 'y1_';

                break;

            case $this->plugin->model_y2:
                $this->pre = 'y2_';

                break;
            
            default:
                # code...
                break;
        }
	}

	function get_userinfos() {
		global $wpdb;

		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
        $tab_no = '';

		switch ( $this->model) {
    		case $this->plugin->model_m1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_month_status';
                if( isset( $_REQUEST['tab_no'] ) ) {
                    $tab_no = $_REQUEST['tab_no'];
                    $type = 0;
                }
                $status_key = 'm1_fpstatus';

    			break;

    		case $this->plugin->model_m6:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_six_month_status';
                if( isset( $_REQUEST['tab_no'] ) ) {
                    $tab_no = $_REQUEST['tab_no'];
                    $type = 1;
                }
                $status_key = 'm6_fpstatus';

    			break;

    		case $this->plugin->model_y1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_year_status';
                if( isset( $_REQUEST['tab_no'] ) ) {
                    $tab_no = $_REQUEST['tab_no'];
                    $type = 1;
                }
                $status_key = 'y1_fpstatus';

    			break;

    		case $this->plugin->model_y2:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_two_year_status';
                if( isset( $_REQUEST['tab_no'] ) ) {
                    $tab_no = $_REQUEST['tab_no'];
                    $type = 1;
                }
                $status_key = 'y2_fpstatus';

    			break;
    		
    		default:
    			return array();
    			break;
    	}
		
		$sql = 'SELECT * FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_status . ' AS ps ON tb.id = ps.id WHERE tb.dedate > "1971-01-01"';
        if( !empty( $tab_no ) ) {
            if( !$type ) {
                $sql .= ' AND tb.m1_tab_no="' . $tab_no . '"'; 
            }else{
                $sql .= ' AND tb.sys_tab_no="' . $tab_no . '"';
            }
        }
        $sql .= ' AND (tb.' . $status_key . '<>1 OR tb.' . $status_key . ' is null)';

        $sql2 = $sql . ' ORDER BY tb.dedate ASC,tb.no1 ASC';
        $this->total_items = $wpdb->get_results( $sql2, 'ARRAY_A' );

        $current_page = 0;
        if( isset( $_REQUEST['current_page'] ) && is_numeric( $_REQUEST['current_page'] ) ) {
            $current_page = $_REQUEST['current_page']-1;
        }

        $page_per_num = 0;
        if( isset( $_REQUEST['page_per_num'] ) && is_numeric( $_REQUEST['page_per_num'] ) ) {
            $page_per_num = $_REQUEST['page_per_num'];
        }

        $page_per_num = $this->plugin->page_arr[$page_per_num];
        $start = $current_page * $page_per_num;

        $sql .= ' ORDER BY tb.dedate ASC,tb.no1 ASC';
        $sql .= ' LIMIT ' . $start . ',' . $page_per_num;

		$results = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $results;
	}

	function prepare_items() {

        $this->items = $this->get_userinfos();
        $this->total_count = count( $this->total_items );

        // $this->set_pagination_args( [
        //     'total_items' => $total_count,
        //     'per_page' => $per_page,
        // ] );
        
        $this->column_header_define = $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        //echo '<pre>';print_r($this->items);echo '</pre>';exit();
        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    function column_default( $item, $column_name ){
    	return $item[ $column_name ];
    }

    function get_hidden_columns(){
        return array('id');
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function get_columns() {

        $columns['no1']       = '队列编号';
        $columns['name']      = '母亲姓名';
        $columns['dedate']    = '分娩日期';

        switch ( $this->model) {
            case $this->plugin->model_m1:
                $columns['last_date']   = '45天日期';

                break;

            case $this->plugin->model_m6:
                $columns['last_date']   = '7个月日期';

                break;

            case $this->plugin->model_y1:
                $columns['last_date']   = '13个月日期';

                break;

            case $this->plugin->model_y2:
                $columns['last_date']   = '26个月日期';

                break;
            
            default:
                # code...
                break;
        }

        $columns['pphone']    = '母亲手机';
        $columns['hphone']    = '父亲手机';
        $columns['dephone']   = '备用电话';
        $columns['telquesre'] = '电话随访备注';

        $this->column_parameters = array(
            'no1',
            'name',
            'dedate',
            'last_date',
            'pphone',
            'hphone',
            'dephone',
            $this->pre . 'telquesre'
            );

        return $columns;
    }

    function column_dedate( $item ) {
    	return date( 'Y-m-d', strtotime( $item['dedate'] ) );
    }
    
    function column_last_date( $item ) {
        switch ( $this->model) {
            case $this->plugin->model_m1:
                $date = date( 'Y-m-d', strtotime( '+45 day', strtotime( $item['dedate'] ) ) );

                break;

            case $this->plugin->model_m6:
                $date = date( 'Y-m-d', strtotime( '+7 month', strtotime( $item['dedate'] ) ) );

                break;

            case $this->plugin->model_y1:
                $date = date( 'Y-m-d', strtotime( '+13 month', strtotime( $item['dedate'] ) ) );

                break;

            case $this->plugin->model_y2:
                $date = date( 'Y-m-d', strtotime( '+26 month', strtotime( $item['dedate'] ) ) );

                break;
            
            default:
                # code...
                break;
        }

        return $date;
    }

    function column_telquesre( $item ) {
        $telquesre = $item[$this->pre . 'telquesre'];

        return $this->plugin->telquesre_arr[$telquesre];
    }
}

?>