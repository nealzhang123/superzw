<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Track_List_Table extends WP_List_Table {
	public $model;
	public $plugin, $pre;
    public $total_count,$total_items;
    public $telre_arr;
    public $column_header_define, $column_parameters;

	function __construct( $model ){
		$this->model = $model;
		$this->plugin = new HospitalTrack;
        $this->telre_arr = $this->plugin->telre_arr;

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

    			break;

    		case $this->plugin->model_m6:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_six_month_status';
                if( isset( $_REQUEST['tab_no'] ) ) {
                    $tab_no = $_REQUEST['tab_no'];
                    $type = 1;
                }

    			break;

    		case $this->plugin->model_y1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_year_status';
                if( isset( $_REQUEST['tab_no'] ) ) {
                    $tab_no = $_REQUEST['tab_no'];
                    $type = 1;
                }

    			break;

    		case $this->plugin->model_y2:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_two_year_status';
                if( isset( $_REQUEST['tab_no'] ) ) {
                    $tab_no = $_REQUEST['tab_no'];
                    $type = 1;
                }

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

        $per_page = 10;

        $this->items = $this->get_userinfos();

        $this->total_count = count( $this->total_items );
        // $this->set_pagination_args( [
        //     'total_items' => $total_count,
        //     'per_page' => $per_page,
        // ] );
        
        $this->column_header_define = $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        
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

        $columns['no1']         = '队列编号';
        $columns['name']        = '母亲姓名';
        $columns['dedate']      = '分娩日期';

        switch ( $this->model) {
            case $this->plugin->model_m1:
                $columns['first_date']  = '打第一遍电话(25天)';
                $columns['second_date'] = '打第二遍电话(35天)';
                $columns['last_date']   = '截止日期(45天)';

                break;

            case $this->plugin->model_m6:
                $columns['first_date']  = '打第一遍电话(5个半月)';
                $columns['second_date'] = '打第二遍电话(6个半月)';
                $columns['last_date']   = '截止日期(7个月)';

                break;

            case $this->plugin->model_y1:
                $columns['first_date']  = '打第一遍电话(11个半月)';
                $columns['second_date'] = '打第二遍电话(12个半月)';
                $columns['last_date']   = '截止日期(13个月)';

                break;

            case $this->plugin->model_y2:
                $columns['first_date']  = '打第一遍电话(23个半月)';
                $columns['second_date'] = '打第二遍电话(24个半月)';
                $columns['last_date']   = '截止日期(26个月)';

                break;
            
            default:
                # code...
                break;
        }

        $columns['pphone']  = '母亲手机';
        $columns['hphone']  = '父亲手机';
        $columns['dephone'] = '备用电话';
        $columns['telre1']  = '第一遍打电话备注';
        $columns['telre2']  = '第二遍打电话备注';
        $columns['telre3']  = '护士打电话备注';

        $this->column_parameters = array(
            'no1',
            'name',
            'dedate',
            'first_date',
            'second_date',
            'last_date',
            'pphone',
            'hphone',
            'dephone',
            $this->pre . 'telre1',
            $this->pre . 'telre2',
            $this->pre . 'telre3'
            );

        return $columns;
    }

    function column_dedate( $item ) {
    	return date( 'Y-m-d', strtotime( $item['dedate'] ) );
    }

    function column_first_date( $item ) {
        switch ( $this->model) {
            case $this->plugin->model_m1:
                $date = date( 'Y-m-d', strtotime( '+25 day', strtotime( $item['dedate'] ) ) );

                break;

            case $this->plugin->model_m6:
                $date = strtotime( '+5 month', strtotime( $item['dedate'] ) );
                $date = date( 'Y-m-d', strtotime( '+15 day', $date ) );

                break;

            case $this->plugin->model_y1:
                $date = strtotime( '+11 month', strtotime( $item['dedate'] ) );
                $date = date( 'Y-m-d', strtotime( '+15 day', $date ) );

                break;

            case $this->plugin->model_y2:
                $date = strtotime( '+23 month', strtotime( $item['dedate'] ) );
                $date = date( 'Y-m-d', strtotime( '+15 day', $date ) );

                break;
            
            default:
                # code...
                break;
        }

        return $date;
    }

    function column_second_date( $item ) {
        switch ( $this->model) {
            case $this->plugin->model_m1:
                $date = date( 'Y-m-d', strtotime( '+35 day', strtotime( $item['dedate'] ) ) );

                break;

            case $this->plugin->model_m6:
                $date = strtotime( '+6 month', strtotime( $item['dedate'] ) );
                $date = date( 'Y-m-d', strtotime( '+15 day', $date ) );

                break;

            case $this->plugin->model_y1:
                $date = strtotime( '+1 year', strtotime( $item['dedate'] ) );
                $date = date( 'Y-m-d', strtotime( '+15 day', $date ) );

                break;

            case $this->plugin->model_y2:
                $date = strtotime( '+2 year', strtotime( $item['dedate'] ) );
                $date = date( 'Y-m-d', strtotime( '+15 day', $date ) );

                break;
            
            default:
                # code...
                break;
        }

        return $date;
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

    function column_telre1( $item ) {
        $telre1_value = $item['telre1'];
        $telre1_value_extend = $item[$this->pre . 'telre1_extend'];

        if( $telre1_value == 100 ) {
            $telre1_content = $this->plugin->hos_db->get_remark_content_by_id( $telre1_value_extend );
        }else{
            $telre1_content = $this->telre_arr[$telre1_value];
        }

        return $telre1_content; 
    }

    function column_telre2( $item ) {
        $telre2_value = $item[$this->pre . 'telre2'];
        $telre2_value_extend = $item[$this->pre . 'telre2_extend'];

        if( $telre2_value == 100 ) {
            $telre2_content = $this->plugin->hos_db->get_remark_content_by_id( $telre2_value_extend );
        }else{
            $telre2_content = $this->telre_arr[$telre2_value];
        }

        return $telre2_content;
    }

    function column_telre3( $item ) {
        $telre3_value = $item[$this->pre . 'telre3'];
        $telre3_value_extend = $item[$this->pre . 'telre3_extend'];

        if( $telre3_value == 100 ) {
            $telre3_content = $this->plugin->hos_db->get_remark_content_by_id( $telre3_value_extend );
        }else{
            $telre3_content = $this->telre_arr[$telre3_value];
        }

        return $telre3_content;
    }
}

?>