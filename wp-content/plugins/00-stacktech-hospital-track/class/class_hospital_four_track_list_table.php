<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Four_Track_List_Table extends WP_List_Table {
	public $model, $hos_type;
	public $plugin;
    public $total_count,$total_items;
    public $telre_arr;
    public $column_header_define, $column_parameters;
    public $pre;
    public $extend_type;

	function __construct( $model, $hos_type ){
        $this->model = $model;
		$this->hos_type = $hos_type;
		$this->plugin = new HospitalFourTrack;

		switch ( $this->model) {
    		case $this->plugin->model_y3:
    			$this->pre = '_3y';

    			break;

    		case $this->plugin->model_y5:
    			$this->pre = '_5y';

    			break;
    		
    		default:
    			$this->pre = '_3y';

    			break;
    	}

        $this->extend_type = 0;
        if( isset( $_REQUEST['extend_type'] ) )
            $this->extend_type = $_REQUEST['extend_type'];

	}

	function get_userinfos() {
		global $wpdb;

        switch ( $this->model) {
            case $this->plugin->model_y3:
                $patient_track = $wpdb->prefix . 'hos_3y_track_info';

                //eg:2016年1月--2月底显示截止2015年12月31日的,3月开始到年底显示截止
                $current_month = date('m');
                $current_year = date('Y');
                if( $current_month < 3 ) {
                    $last_year = $current_year - 1;
                    $max_date = strtotime( date( $last_year . '-12-31' ) ) - (2*365+8*30.5)*24*60*60;//2年8个月
                    $max_date = date( 'Y-m-d', $max_date );
                }else{
                    $max_date = strtotime( date('Y-12-31') ) - (2*365+8*30.5)*24*60*60;//2年8个月
                    $max_date = date( 'Y-m-d', $max_date );
                }

                $min_date = time() - 4*365*24*60*60;//4年
                $min_date = date( 'Y-m-d', $min_date );

                break;

            case $this->plugin->model_y5:
                $patient_track = $wpdb->prefix . 'hos_5y_track_info';

                //eg:2016年1月--2月底显示截止2015年12月31日的,3月开始到年底显示截止
                $current_month = date('m');
                $current_year = date('Y');
                if( $current_month < 3 ) {
                    $last_year = $current_year - 1;
                    $max_date = strtotime( date( $last_year . '-12-31' ) ) - (4*365+8*30.5)*24*60*60;//2年8个月
                    $max_date = date( 'Y-m-d', $max_date );
                }else{
                    $max_date = strtotime( date('Y-12-31') ) - (4*365+8*30.5)*24*60*60;//2年8个月
                    $max_date = date( 'Y-m-d', $max_date );
                }

                $min_date = time() - 6*365*24*60*60;//4年
                $min_date = date( 'Y-m-d', $min_date );

                break;
            
            default:
                return array();
                break;
        }

        if( $this->hos_type == 1 ){
            $table_basic = $wpdb->prefix . 'hos_in_birth_basic_info';

            $sql = 'SELECT pt.*,tb.no2 as no2,tb.bname,tb.name,tb.hname,tb.dedate,tb.pphone,tb.hphone FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_track . ' AS pt ON tb.no2 = pt.no2 WHERE (pt.status' . $this->pre .'=0 OR pt.status' . $this->pre . ' is null)';
        }else{
            $table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';

            $sql = 'SELECT pt.*,tb.no1 as no1,tb.bname,tb.cname,tb.name,tb.hname,tb.dedate,tb.pphone,tb.hphone FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_track . ' AS pt ON tb.no1 = pt.no1 WHERE (pt.status' . $this->pre .'=0 OR pt.status' . $this->pre . ' is null)';
        }

		$sql .= ' AND tb.dedate > "' . $min_date . '" AND tb.dedate < "' . $max_date . '"';
        if( $this->extend_type == 2 ){
            $sql .= ' AND (pt.kindergarten' . $this->pre .' = "" OR pt.kindergarten' . $this->pre . ' is null)';
        }
		//error_log($sql);

        $sql2 = $sql . ' ORDER BY tb.dedate ASC,tb.id ASC';
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

        $sql .= ' ORDER BY tb.dedate ASC,tb.id ASC';
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
    	if( in_array( $column_name, array( 'kindergarten', 'class' ) ) ) {
    		return $item[ $column_name . $this->pre ];
    	}elseif( in_array( $column_name, array( 'exdate', 'enrolldate' ) ) ){
    		return $this->plugin->translate_date( $item[ $column_name . $this->pre ] );
    	}elseif( $column_name == 'district' ) {
    		return $this->plugin->meu_arr[ $item[ $column_name ] ];
    	}else{
    		return $item[ $column_name ];
    	}
    }

    function get_hidden_columns(){
        return array('id');
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function get_columns() {
    	switch ( $this->model ) {
            case $this->plugin->model_y3:
            	$pre_name = '3岁';

                if( $this->hos_type == 1 ){
                    $columns['no2']      = '队列号';
                }else{
                    $columns['no1']      = '队列号';
                }
                
                $columns['district']     = '区';
                $columns['kindergarten'] = '幼儿园名称' . $pre_name;
                $columns['enrolldate']   = '入园日期' . $pre_name;
                $columns['class']        = '班级' . $pre_name;
                $columns['exdate']       = '体检日期' . $pre_name;
                $columns['bname']        = '儿童姓名';
                $columns['name']         = '母亲姓名';
                $columns['hname']        = '父亲姓名';
                $columns['dedate']       = '出生日期';
                $columns['cage']         = '儿童年龄';
                $columns['pphone']       = '母亲电话';
                $columns['hphone']       = '父亲电话';
                $columns['editor']       = '操作';

                if( $this->hos_type == 1 ){
                    $this->column_parameters = array(
                        'no2',
                        'district',
                        'kindergarten' . $this->pre,
                        'enrolldate' . $this->pre,
                        'class' . $this->pre,
                        'exdate' . $this->pre,
                        'bname',
                        'name',
                        'hname',
                        'dedate',
                        'cage',
                        'pphone',
                        'hphone'
                    );
                }else{
                    $this->column_parameters = array(
                        'no1',
                        'district',
                        'kindergarten' . $this->pre,
                        'enrolldate' . $this->pre,
                        'class' . $this->pre,
                        'exdate' . $this->pre,
                        'bname',
                        'name',
                        'hname',
                        'dedate',
                        'cage',
                        'pphone',
                        'hphone'
                    );
                }

                break;

            case $this->plugin->model_y5:
                $pre_name = '5岁';

                if( $this->hos_type == 1 ){
                    $columns['no2']      = '队列号';
                }else{
                    $columns['no1']      = '队列号';
                }
                $columns['district']     = '区';
                $columns['kindergarten'] = '幼儿园名称' . $pre_name;
                $columns['enrolldate']   = '入园日期' . $pre_name;
                $columns['class']        = '班级' . $pre_name;
                $columns['exdate']       = '体检日期' . $pre_name;
                $columns['bname']        = '儿童姓名';
                $columns['name']         = '母亲姓名';
                $columns['cage']         = '儿童年龄';
                $columns['pphone']       = '母亲电话';
                $columns['hphone']       = '父亲电话';
                $columns['dephone']      = '备用电话';
                $columns['editor']       = '操作';

                if( $this->hos_type == 1 ){
                    $this->column_parameters = array(
                        'no2',
                        'district',
                        'kindergarten' . $this->pre,
                        'enrolldate' . $this->pre,
                        'class' . $this->pre,
                        'exdate' . $this->pre,
                        'bname',
                        'name',
                        'cage',
                        'pphone',
                        'hphone',
                        'dephone',
                    );
                }else{
                    $this->column_parameters = array(
                        'no1',
                        'district',
                        'kindergarten' . $this->pre,
                        'enrolldate' . $this->pre,
                        'class' . $this->pre,
                        'exdate' . $this->pre,
                        'bname',
                        'name',
                        'cage',
                        'pphone',
                        'hphone',
                        'dephone',
                    );
                }

                break;
            
            default:
                # code...
                break;
        }

        return $columns;
    }

    function column_dedate( $item ) {
    	return $this->plugin->translate_date( $item['dedate'] );
    }

    function column_cage( $item ) {
        $date = $this->plugin->diffDate( $item['dedate'] , date( 'Y-m-d' ) )  ;
        $content = '';

        if( $date[0] > 0 )
            $content .= $date[0] . 'Y';

        if( $date[1] > 0 )
            $content .= $date[1] . 'M';

        if( $date[2] > 0 )
            $content .= $date[2] . 'D';

        return $content;
    }

    function column_editor( $item ) {
        return '<a class="four_track_item_edit" data-id="' . $item['id'] . '" data-no2="' . $item['no2'] . '" data-no1="' . $item['no1'] . '"><i class="fa fa-pencil-square-o"></i></a>';
    }
}

?>