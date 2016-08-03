<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Edit_Track_Result_Table extends WP_List_Table {
	public $model;
    public $plugin, $pre, $model_name;
    public $total_count, $total_items;

	function __construct( $model ){
		$this->model = $model;
		$this->plugin = new HospitalTrack;

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

		if( !isset( $_REQUEST['edit_track_no1'] ) || empty( $_REQUEST['edit_track_no1'] ) )
			return array();

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

        $sql .= ' AND tb.no1 LIKE %s';

        $sql2 = $sql . ' ORDER BY tb.dedate ASC,tb.no1 ASC';
        $this->total_items = $wpdb->get_results( $wpdb->prepare( $sql2, '%' . $_REQUEST['edit_track_no1'] . '%' ), 'ARRAY_A' );

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

		$results = $wpdb->get_results( $wpdb->prepare( $sql, '%' . $_REQUEST['edit_track_no1'] . '%' ), 'ARRAY_A' );

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

		$columns['no1']      = '队列编号';
		$columns['name']     = '姓名';
		$columns['dedate']   = '分娩日期';
		$columns['baby_mon'] = '今日月龄';
		$columns['tool']     = '操作';

        return $columns;
    }

    function column_tool( $item ) {
        return '<a class="pre_result_item_edit" data-id="' . $item['id'] . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';
    }

    function column_baby_mon( $item ) {
    	$dedate = $item['dedate'];

        $baby_mon = floor( ( time() - strtotime( $dedate ) ) * 10 / ( 3600 * 24 * 30.5 ) );
        $baby_mon = $baby_mon / 10;

    	return $baby_mon;
    }

    function column_dedate( $item ) {
    	return date( 'Y-m-d', strtotime( $item['dedate'] ) );
    }
}

?>