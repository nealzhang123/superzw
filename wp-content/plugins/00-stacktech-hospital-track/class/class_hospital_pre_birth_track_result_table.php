<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Track_Result_Table extends WP_List_Table {
	public $model;
	public $plugin, $pre;
    public $total_count, $total_items;
    public $telquesre_arr;
    public $column_header_define, $column_parameters;

	function __construct( $model ){
		$this->model = $model;
		$this->plugin = new HospitalTrack;
        
        $this->telquesre_arr = $this->plugin->telquesre_arr;

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
        $fpdate_name = '';

		switch ( $this->model) {
    		case $this->plugin->model_m1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_month_status';
                $fpdate_name = 'm1_fpdate';

    			break;

    		case $this->plugin->model_m6:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_six_month_status';
                $fpdate_name = 'm6_fpdate';

    			break;

    		case $this->plugin->model_y1:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_one_year_status';
                $fpdate_name = 'y1_fpdate';

    			break;

    		case $this->plugin->model_y2:
    			$patient_status = $wpdb->prefix . 'hos_pre_birth_two_year_status';
                $fpdate_name = 'y2_fpdate';

    			break;
    		
    		default:
    			return array();
    			break;
    	}
		
		$sql = 'SELECT * FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_status . ' AS ps ON tb.id = ps.id where tb.dedate > "1971-01-01"';

		if( isset( $_REQUEST['min_mon_date'] ) && is_numeric( $_REQUEST['min_mon_date'] ) ) {
            $min_date = ceil( 30.5*$_REQUEST['min_mon_date'] );
            $min_date = time()-3600*24*$min_date;
            $sql .= ' AND tb.dedate<="' . date( 'Y-m-d', $min_date ) . '"';
        }

        if( isset( $_REQUEST['max_mon_date'] ) && is_numeric( $_REQUEST['max_mon_date'] ) ) {
            $max_date = time()-3600*24*30.5*$_REQUEST['max_mon_date'];
            $sql .= ' AND tb.dedate>="' . date( 'Y-m-d', $max_date ) . '"';
        }

        if( isset( $_REQUEST['min_track_date'] ) && !empty( $_REQUEST['min_track_date'] ) ) {
            $min_date = strtotime( $_REQUEST['min_track_date'] );
            $sql .= ' AND ps.' . $fpdate_name . '>="' . date( 'Y-m-d', $min_date ) . '"';
        }

        if( isset( $_REQUEST['max_track_date'] ) && !empty( $_REQUEST['max_track_date'] ) ) {
            $max_date = strtotime( $_REQUEST['max_track_date'] );
            $sql .= ' AND ps.' . $fpdate_name . '<="' . date( 'Y-m-d', $max_date ) . '"';
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
        //$count = $this->vfu_wb->count($search);
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
        

        $columns_arr = array_keys( $this->plugin->track_arr );
        $columns_arr[] = 'telques';

        if( in_array( $column_name, $columns_arr ) ) {
            return $this->plugin->get_check_icon( $item[ $this->pre . $column_name ] );
        }else if( $column_name == 'fpdate' ){
            return $this->plugin->translate_date( $item[ $this->pre . $column_name ] );
        }else if( $column_name == 'telquesre' ){
            return $this->telquesre_arr[ $item[ $this->pre . $column_name ] ];
        }else{
            return $item[ $column_name ];
        }
    }

    function get_hidden_columns(){
        return array('id');
    }

    function no_items() {
        return __('没有相关数据');
    }

    function get_columns() {

        $columns['no1']       = '队列编号';
        $columns['name']      = '母亲姓名';
        $columns['baby_mon']  = '今日月龄';

    	switch ( $this->model ) {
    		case $this->plugin->model_m1:
    			$model_name = '1月';

                $columns['fpstatus']  = $model_name . '随访状态';
                $columns['fpdate']    = $model_name . '随访时间';
                $columns['selfques']  = $model_name . '自填问卷';
                $columns['epds']      = $model_name . '抑郁量表';
                $columns['phyexa']    = $model_name . '体格检查';
                $columns['icterus']   = $model_name . '黄疸指数';
                $columns['brmilks']   = $model_name . '母乳结果';
                $columns['brmilkr']   = $model_name . '母乳样本';
                $columns['urine']     = $model_name . '尿液';
                $columns['fec']       = $model_name . '粪便';
                $columns['bp']        = $model_name . '母亲血压';
                $columns['telques']   = $model_name . '电话问卷';
                $columns['telquesre'] = $model_name . '电话随访备注';

                $this->column_parameters = array(
                    'no1',
                    'name',
                    'baby_mon',
                    $this->pre . 'fpstatus',
                    $this->pre . 'fpdate',
                    $this->pre . 'selfques',
                    $this->pre . 'epds',
                    $this->pre . 'phyexa',
                    $this->pre . 'icterus',
                    $this->pre . 'brmilks',
                    $this->pre . 'brmilkr',
                    $this->pre . 'urine',
                    $this->pre . 'fec',
                    $this->pre . 'bp',
                    $this->pre . 'telques',
                    $this->pre . 'telquesre'
                    );

    			break;

    		case $this->plugin->model_m6:
    			$model_name = '6月';

                $columns['fpstatus']  = $model_name . '随访状态';
                $columns['fpdate']    = $model_name . '随访时间';
                $columns['selfques']  = $model_name . '自填问卷';
                $columns['epds']      = $model_name . '抑郁量表';
                $columns['phyexa']    = $model_name . '体格检查';
                $columns['baily']     = $model_name . '贝利';
                $columns['brmilks']   = $model_name . '母乳结果';
                $columns['brmilkr']   = $model_name . '母乳样本';
                $columns['urine']     = $model_name . '尿液';
                $columns['fec']       = $model_name . '粪便';
                $columns['rbt']       = $model_name . '血常规';
                $columns['bp']        = $model_name . '母亲血压';
                $columns['telques']   = $model_name . '电话问卷';
                $columns['telquesre'] = $model_name . '电话随访备注';

                $this->column_parameters = array(
                    'no1',
                    'name',
                    'baby_mon',
                    $this->pre . 'fpstatus',
                    $this->pre . 'fpdate',
                    $this->pre . 'selfques',
                    $this->pre . 'epds',
                    $this->pre . 'phyexa',
                    $this->pre . 'baily',
                    $this->pre . 'brmilks',
                    $this->pre . 'brmilkr',
                    $this->pre . 'urine',
                    $this->pre . 'fec',
                    $this->pre . 'rbt',
                    $this->pre . 'bp',
                    $this->pre . 'telques',
                    $this->pre . 'telquesre'
                    );
       
    			break;

    		case $this->plugin->model_y1:
    			$model_name = '1岁';

                $columns['fpstatus']  = $model_name . '随访状态';
                $columns['fpdate']    = $model_name . '随访时间';
                $columns['cname']     = '宝宝姓名';
                $columns['selfques']  = $model_name . '自填问卷';
                $columns['vision']    = $model_name . '视力';
                $columns['phyexa']    = $model_name . '体格检查';
                $columns['baily']     = $model_name . '贝利';
                $columns['brmilks']   = $model_name . '母乳结果';
                $columns['brmilkr']   = $model_name . '母乳样本';
                $columns['urine']     = $model_name . '尿液';
                $columns['fec']       = $model_name . '粪便';
                $columns['rbt']       = $model_name . '血常规';
                $columns['bpb']       = $model_name . '血铅';
                $columns['bp']        = $model_name . '母亲血压';
                $columns['telques']   = $model_name . '电话问卷';
                $columns['telquesre'] = $model_name . '电话随访备注';

                $this->column_parameters = array(
                    'no1',
                    'name',
                    'baby_mon',
                    $this->pre . 'fpstatus',
                    $this->pre . 'fpdate',
                    $this->pre . 'selfques',
                    $this->pre . 'vision',
                    $this->pre . 'phyexa',
                    $this->pre . 'baily',
                    $this->pre . 'brmilks',
                    $this->pre . 'brmilkr',
                    $this->pre . 'urine',
                    $this->pre . 'fec',
                    $this->pre . 'rbt',
                    $this->pre . 'bpb',
                    $this->pre . 'bp',
                    $this->pre . 'telques',
                    $this->pre . 'telquesre'
                    );

    			break;

    		case $this->plugin->model_y2:
    			$model_name = '2岁';

                $columns['fpstatus']  = $model_name . '随访状态';
                $columns['fpdate']    = $model_name . '随访时间';
                $columns['selfques']  = $model_name . '自填问卷';
                $columns['vision']    = $model_name . '视力';
                $columns['phyexa']    = $model_name . '体格检查';
                $columns['baily']     = $model_name . '贝利';
                $columns['urine']     = $model_name . '尿液';
                $columns['fec']       = $model_name . '粪便';
                $columns['rbt']       = $model_name . '血常规';
                $columns['bpb']       = $model_name . '血铅';
                $columns['bp']        = $model_name . '母亲血压';
                $columns['telques']   = $model_name . '电话问卷';
                $columns['telquesre'] = $model_name . '电话随访备注';

                $this->column_parameters = array(
                    'no1',
                    'name',
                    'baby_mon',
                    $this->pre . 'fpstatus',
                    $this->pre . 'fpdate',
                    $this->pre . 'selfques',
                    $this->pre . 'vision',
                    $this->pre . 'phyexa',
                    $this->pre . 'baily',
                    $this->pre . 'urine',
                    $this->pre . 'fec',
                    $this->pre . 'rbt',
                    $this->pre . 'bpb',
                    $this->pre . 'bp',
                    $this->pre . 'telques',
                    $this->pre . 'telquesre'
                    );

    			break;
    		
    		default:
    			# code...
    			break;
    	}

        return $columns;
    }

    function column_baby_mon( $item ) {
    	$dedate = $item['dedate'];

        $baby_mon = floor( ( time() - strtotime( $dedate ) ) * 10 / ( 3600 * 24 * 30.5 ) );
        $baby_mon = $baby_mon / 10;

    	return $baby_mon;
    }

    function column_fpstatus( $item ) {
        $model = $this->model;
        $dedate = $item['dedate'];

        $status_id = $this->plugin->hos_get_two_year_track_status( $item[$this->pre . 'fpstatus'], $model, $dedate );

        return $this->plugin->track_status_arr[$status_id];
    }

}
?>