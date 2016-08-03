<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Edit_Tel_Book_Table extends WP_List_Table {
	public $model;
    public $plugin, $pre, $model_name;
    public $total_count, $total_items;
	public $telre_arr;

	function __construct( $model ){
		$this->model = $model;
		$this->plugin = new HospitalTrack;

        $this->telre_arr = $this->plugin->telre_arr;

        switch ( $this->model) {
            case $this->plugin->model_m1:
                $this->pre = 'm1_';
                $this->model_name = '1月';

                break;

            case $this->plugin->model_m6:
                $this->pre = 'm6_';
                $this->model_name = '6月';

                break;

            case $this->plugin->model_y1:
                $this->pre = 'y1_';
                $this->model_name = '1岁';

                break;

            case $this->plugin->model_y2:
                $this->pre = 'y2_';
                $this->model_name = '2岁';

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

		$sql = 'SELECT *,tb.id as id FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_status . ' AS ps ON tb.id = ps.id WHERE tb.dedate > "1971-01-01"';

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
        
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        //echo '<pre>';print_r($this->items);echo '</pre>';
        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    function column_default( $item, $column_name ){
        echo '<div id="'.$column_name.'_'.$item['id'].'">'.$item[$column_name].'</div>';
    }

    function get_hidden_columns(){
        return array('id');
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function get_columns() {

        $columns['no1']     = '队列编号';
        $columns['name']    = '母亲姓名';
        $columns['pphone']  = '母亲手机';
        $columns['hphone']  = '父亲手机';
        $columns['telre1']  = $this->model_name . '第一遍电话备注';
        $columns['telre2']  = $this->model_name . '第二遍电话备注';
        $columns['telre3']  = $this->model_name . '护士打电话备注';
        $columns['telname'] = '电话人';
        $columns['tool']    = '操作';

        return $columns;
    }

    function column_tool( $item ) {
        echo '<a class="pre_book_item_edit" data-id="' . $item['id'] . '" m1_tab_no="' . $item['m1_tab_no'] . '" sys_tab_no="' . $item['sys_tab_no'] . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';
    }

    function column_telre1( $item ) {

        $telre1_value = $item[$this->pre . 'telre1'];
        $telre1_value_extend = $item[$this->pre . 'telre1_extend'];

        if( $telre1_value == 100 ) {
            $telre1_content = $this->plugin->hos_db->get_remark_content_by_id( $telre1_value_extend );
        }else{
            $telre1_content = $this->telre_arr[$telre1_value];
        }

        return '<div id="telre1_' . $item['id'] . '" data-val="' . $telre1_value . '">' . $telre1_content . '</div>';
    }

    function column_telre2( $item ) {

        $telre2_value = $item[$this->pre . 'telre2'];
        $telre2_value_extend = $item[$this->pre . 'telre2_extend'];

        if( $telre2_value == 100 ) {
            $telre2_content = $this->plugin->hos_db->get_remark_content_by_id( $telre2_value_extend );
        }else{
            $telre2_content = $this->telre_arr[$telre2_value];
        }

        return '<div id="telre2_' . $item['id'] . '" data-val="' . $telre2_value . '">' . $telre2_content . '</div>';
    }

    function column_telre3( $item ) {
        $telre3_value = $item[$this->pre . 'telre3'];
        $telre3_value_extend = $item[$this->pre . 'telre3_extend'];

        if( $telre3_value == 100 ) {
            $telre3_content = $this->plugin->hos_db->get_remark_content_by_id( $telre3_value_extend );
        }else{
            $telre3_content = $this->telre_arr[$telre3_value];
        }

        return '<div id="telre3_' . $item['id'] . '" data-val="' . $telre3_value . '">' . $telre3_content . '</div>';
    }

    function column_telname( $item ) {
        $telname = $item[$this->pre . 'telname'];

        return '<div id="telname_' . $item['id'] . '">' . $telname . '</div>';
    }

}
?>