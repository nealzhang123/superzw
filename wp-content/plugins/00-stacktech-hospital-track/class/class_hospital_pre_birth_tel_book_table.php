<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Tel_Book_Table extends WP_List_Table {
	public $model;
	public $plugin;
    public $total_count;
    public $telre_arr;

	function __construct( $model ){
		$this->model = $model;
		$this->plugin = new HospitalTrack;

        $this->telre_arr = array(
            0 => '无备注',
            1 => '参加',
            2 => '不在武汉',
            3 => '社区体检',
            4 => '失联(停机、空号、错号)',
            5 => '拒绝',
            6 => '市妇幼自费已做',
            7 => '宝宝夭折'
            );
	}

	function get_userinfos_count() {
		global $wpdb;

		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';

		switch ( $this->model) {
    		case $this->plugin->model_m1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_month_status';

    			break;

    		case $this->plugin->model_m6:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_six_month_status';

    			break;

    		case $this->plugin->model_y1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_year_status';

    			break;

    		case $this->plugin->model_y2:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_two_year_status';

    			break;
    		
    		default:
    			return 0;
    			break;
    	}
		
        $sql = 'SELECT count(*) FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_status . ' AS ps ON tb.id = ps.id WHERE tb.dedate > "1971-01-01"';

        if( isset( $_POST['min_mon_date'] ) && is_numeric( $_POST['min_mon_date'] ) ) {
            $min_date = time()-3600*24*30.5*$_POST['min_mon_date'];
            $sql .= ' AND tb.dedate<="' . date( 'Y-m-d', $min_date ) . '"';
        }

        if( isset( $_POST['max_mon_date'] ) && is_numeric( $_POST['max_mon_date'] ) ) {
            $max_date = time()-3600*24*30.5*$_POST['max_mon_date'];
            $sql .= ' AND tb.dedate>="' . date( 'Y-m-d', $max_date ) . '"';
        }

		$count = $wpdb->get_var( $sql );

		return $count;
	}

	function get_userinfos() {
		global $wpdb;

		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';

		switch ( $this->model) {
    		case $this->plugin->model_m1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_month_status';

    			break;

    		case $this->plugin->model_m6:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_six_month_status';

    			break;

    		case $this->plugin->model_y1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_year_status';

    			break;

    		case $this->plugin->model_y2:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_two_year_status';

    			break;
    		
    		default:
    			return array();
    			break;
    	}
		
		$sql = 'SELECT * FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_status . ' AS ps ON tb.id = ps.id WHERE tb.dedate > "1971-01-01"';

        if( isset( $_POST['min_mon_date'] ) && is_numeric( $_POST['min_mon_date'] ) ) {
            $min_date = ceil( 30.5*$_POST['min_mon_date'] );
            $min_date = time()-3600*24*$min_date;
            $sql .= ' AND tb.dedate<="' . date( 'Y-m-d', $min_date ) . '"';
        }

        if( isset( $_POST['max_mon_date'] ) && is_numeric( $_POST['max_mon_date'] ) ) {
            $max_date = time()-3600*24*30.5*$_POST['max_mon_date'];
            $sql .= ' AND tb.dedate>="' . date( 'Y-m-d', $max_date ) . '"';
        }

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

        $sql .= ' ORDER BY tb.dedate ASC,tb.no1 ASC';
        $sql .= ' LIMIT ' . $start . ',' . $page_per_num;

		$results = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $results;
	}

	function prepare_items() {

        $per_page = 10;

        $this->total_count = $this->get_userinfos_count();

        $this->items = $this->get_userinfos();
        // $this->set_pagination_args( [
        //     'total_items' => $total_count,
        //     'per_page' => $per_page,
        // ] );
        
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        //echo '<pre>';print_r($this->items);echo '</pre>';
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
    	switch ( $this->model) {
    		case $this->plugin->model_m1:
    			$model_name = '1月';

    			break;

    		case $this->plugin->model_m6:
    			$model_name = '6月';

    			break;

    		case $this->plugin->model_y1:
    			$model_name = '1岁';

    			break;

    		case $this->plugin->model_y2:
    			$model_name = '2岁';

    			break;
    		
    		default:
    			# code...
    			break;
    	}

        $columns['no1']      = '队列编号';
        $columns['name']     = '母亲姓名';
        $columns['baby_mon'] = '今日月龄';
        $columns['fpstatus'] = $model_name . '随访状态';
        $columns['telname']  = $model_name . '电话人';
        $columns['telre1']   = $model_name . '第一遍电话备注';
        $columns['telre2']   = $model_name . '第二遍电话备注';
        $columns['telre3']   = $model_name . '护士打电话备注';

        return $columns;
    }

    function column_baby_mon( $item ) {
    	$dedate = $item['dedate'];

    	$baby_mon = floor( ( time() - strtotime( $dedate ) ) * 10 / ( 3600 * 24 * 30.5 ) );
        $baby_mon = $baby_mon / 10;

    	echo $baby_mon;
    }

    function column_telre1( $item ) {
        switch ( $this->model) {
            case $this->plugin->model_m1:
                $telre1_value = $item['m1_telre1'];
                $telre1_value_extend = $item['m1_telre1_extend'];

                break;

            case $this->plugin->model_m6:
                $telre1_value = $item['m6_telre1'];
                $telre1_value_extend = $item['m6_telre1_extend'];

                break;

            case $this->plugin->model_y1:
                $telre1_value = $item['y1_telre1'];
                $telre1_value_extend = $item['y1_telre1_extend'];

                break;

            case $this->plugin->model_y2:
                $telre1_value = $item['y2_telre1'];
                $telre1_value_extend = $item['y2_telre1_extend'];

                break;
            
            default:
                # code...
                break;
        }

        if( $telre1_value == 100 ) {
            $telre1_content = $this->plugin->hos_db->get_remark_content_by_id( $telre1_value_extend );
        }else{
            $telre1_content = $this->telre_arr[$telre1_value];
        }

        return $telre1_content; 
    }

    function column_telre2( $item ) {
        switch ( $this->model) {
            case $this->plugin->model_m1:
                $telre2_value = $item['m1_telre2'];
                $telre2_value_extend = $item['m1_telre2_extend'];

                break;

            case $this->plugin->model_m6:
                $telre2_value = $item['m6_telre2'];
                $telre2_value_extend = $item['m6_telre2_extend'];

                break;

            case $this->plugin->model_y1:
                $telre2_value = $item['y1_telre2'];
                $telre2_value_extend = $item['y1_telre2_extend'];

                break;

            case $this->plugin->model_y2:
                $telre2_value = $item['y2_telre2'];
                $telre2_value_extend = $item['y2_telre2_extend'];

                break;
            
            default:
                # code...
                break;
        }

        if( $telre2_value == 100 ) {
            $telre2_content = $this->plugin->hos_db->get_remark_content_by_id( $telre2_value_extend );
        }else{
            $telre2_content = $this->telre_arr[$telre2_value];
        }

        return $telre2_content;
    }

    function column_telre3( $item ) {
        switch ( $this->model) {
            case $this->plugin->model_m1:
                $telre3_value = $item['m1_telre3'];
                $telre3_value_extend = $item['m1_telre3_extend'];

                break;

            case $this->plugin->model_m6:
                $telre3_value = $item['m6_telre3'];
                $telre3_value_extend = $item['m6_telre3_extend'];

                break;

            case $this->plugin->model_y1:
                $telre3_value = $item['y1_telre3'];
                $telre3_value_extend = $item['y1_telre3_extend'];

                break;

            case $this->plugin->model_y2:
                $telre3_value = $item['y2_telre3'];
                $telre3_value_extend = $item['y2_telre3_extend'];

                break;
            
            default:
                # code...
                break;
        }

        if( $telre3_value == 100 ) {
            $telre3_content = $this->plugin->hos_db->get_remark_content_by_id( $telre3_value_extend );
        }else{
            $telre3_content = $this->telre_arr[$telre3_value];
        }

        return $telre3_content;
    }

    function column_telname( $item ) {
        switch ( $this->model) {
            case $this->plugin->model_m1:
                $telname = $item['m1_telname'];

                break;

            case $this->plugin->model_m6:
                $telname = $item['m6_telname'];

                break;

            case $this->plugin->model_y1:
                $telname = $item['y1_telname'];

                break;

            case $this->plugin->model_y2:
                $telname = $item['y2_telname'];

                break;
            
            default:
                # code...
                break;
        }

        return $telname;
    }

    function column_fpstatus( $item ) {
        $model = $this->model;
        $dedate = $item['dedate'];

        switch ( $this->model ) {
            case $this->plugin->model_m1:
                $fpstatus = $item['m1_fpstatus'];

                break;

            case $this->plugin->model_m6:
                $fpstatus = $item['m6_fpstatus'];

                break;

            case $this->plugin->model_y1:
                $fpstatus = $item['y1_fpstatus'];

                break;

            case $this->plugin->model_y2:
                $fpstatus = $item['y2_fpstatus'];

                break;
            
            default:
                # code...
                break;
        }
        $status_id = $this->plugin->hos_get_two_year_track_status( $fpstatus, $model, $dedate );

        return $this->plugin->track_status_arr[$status_id];
    }
}

?>