<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Four_Tel_List_Table extends WP_List_Table {
    public $model, $hos_type;
    public $plugin;
    public $total_count,$total_items;
    public $telre_arr;
    public $column_header_define, $column_parameters;
    public $pre;

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

    }

    function get_userinfos() {
        global $wpdb;

        switch ( $this->model) {
            case $this->plugin->model_y3:
                $patient_track = $wpdb->prefix . 'hos_3y_track_info';

                $max_date = time() - 4*365*24*60*60;//4年
                $max_date = date( 'Y-m-d', $max_date );

                break;

            case $this->plugin->model_y5:
                $patient_track = $wpdb->prefix . 'hos_5y_track_info';

                $max_date = time() - 6*365*24*60*60;//6年
                $max_date = date( 'Y-m-d', $max_date );

                break;
            
            default:
                return array();
                break;
        }

        if( $this->hos_type == 1 ) {
            $table_basic = $wpdb->prefix . 'hos_in_birth_basic_info';

            $sql = 'SELECT pt.*,tb.no2 as no2,tb.bname,tb.name,tb.hname,tb.dedate,tb.pphone,tb.hphone' . ' FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_track . ' AS pt ON tb.no2 = pt.no2 WHERE (pt.status' . $this->pre .'=0 OR pt.status' . $this->pre . ' is null)';
        }else{
            $table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';

            $sql = 'SELECT pt.*,tb.no1 as no1,tb.bname,tb.name,tb.cname,tb.hname,tb.dedate,tb.pphone,tb.hphone' . ' FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_track . ' AS pt ON tb.no1 = pt.no1 WHERE (pt.status' . $this->pre .'=0 OR pt.status' . $this->pre . ' is null)';
        }

        $sql .= ' AND tb.dedate < "' . $max_date . '" AND tb.dedate > "1971-01-01"';
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
        //echo '<pre>';print_r($this->items);echo '</pre>';
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
        if( in_array( $column_name, array( 'kindergarten', 'class', 'pquestionnote' ) ) ) {
            return $item[ $column_name . $this->pre ];
        }elseif( $column_name == 'pqudate' ){
            return $this->plugin->translate_date( $item[ $column_name . $this->pre ] );
        }elseif( $column_name == 'phquestion' ){
            return $this->plugin->get_check_icon( $item[ $column_name . $this->pre ] );
        }elseif( $column_name == 'notetel' ) {
            return $this->plugin->note_tel_arr[ $item[ $column_name . $this->pre ] ];
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

                break;

            case $this->plugin->model_y5:
                $pre_name = '5岁';

                break;
            
            default:
                # code...
                break;
        }

        if( $this->hos_type == 1 ) {
            $columns['no2']    = '队列号';
        }else{
            $columns['no1']    = '队列号';
        }
        
        $columns['bname']      = '儿童姓名';
        $columns['name']       = '母亲姓名';
        $columns['pphone']     = '母亲电话';
        $columns['hname']      = '父亲姓名';
        $columns['hphone']     = '父亲电话';
        $columns['pqudate']    = '电话问卷日期' . $pre_name;
        $columns['phquestion'] = '电话问卷' . $pre_name;
        $columns['notetel']    = '电话问卷' . $pre_name  . '备注';

        if( $this->hos_type == 1 ) {
            $this->column_parameters = array(
                'no2',
                'bname',
                'name',
                'pphone',
                'hname',
                'hphone',
                'pqudate' . $this->pre,
                'phquestion' . $this->pre,
                'notetel' . $this->pre,
            );
        }else{
            $this->column_parameters = array(
                'no1',
                'bname',
                'name',
                'pphone',
                'hname',
                'hphone',
                'pqudate' . $this->pre,
                'phquestion' . $this->pre,
                'notetel' . $this->pre,
            );
        }

        return $columns;
    }

}
?>