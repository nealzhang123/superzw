<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Edit_Tel_List_Table extends WP_List_Table {
	public $model;
    public $plugin, $pre, $model_name;
    public $total_count, $total_items;
    public $telques_arr,$telquesre_arr;

	function __construct( $model ){
		$this->model = $model;
		$this->plugin = new HospitalTrack;

        $this->telques_arr = $this->plugin->telre_arr;
        $this->telquesre_arr = $this->plugin->telquesre_arr;

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

		$sql = 'SELECT *,tb.id as id FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_status . ' AS ps ON tb.id = ps.id WHERE tb.dedate > "1971-01-01"';

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
        return '<div id="'.$column_name.'_'.$item['id'].'">'.$item[$column_name].'</div>';
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
        $columns['pphone']    = '母亲手机';
        $columns['hphone']    = '父亲手机';
        $columns['telques']   = '电话问卷';
        $columns['telquesre'] = '电话随访备注';
        $columns['tool']      = '操作';

        return $columns;
    }

    function column_tool( $item ) {
        return '<a class="pre_tel_item_edit" data-id="' . $item['id'] . '" m1_tab_no="' . $item['m1_tab_no'] . '" sys_tab_no="' . $item['sys_tab_no'] . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';
    }

    function column_telques( $item ) {
        $telques_value = $item[$this->pre . 'telques'];

        return '<div id="telques_' . $item['id'] . '" data-val="' . $telques_value . '">' . $this->plugin->get_check_icon( $telques_value ) . '</div>';
    }

    function column_telquesre( $item ) {
        $telquesre_value = $item[$this->pre . 'telquesre'];

        return '<div id="telquesre_' . $item['id'] . '" data-val="' . $telquesre_value . '">' . $this->telquesre_arr[$telquesre_value] . '</div>';
    }

}
?>