<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Social_Accounts_List extends WP_List_Table {
    public $plugin;

    function get_accounts_info() {
        global $wpdb;

        $this->plugin = new CMSocial;
        $results = $this->plugin->so_db->get_social_accounts();

        return $results;
    }

    function prepare_items() {

        // $per_page = 10;
        $this->items = $this->get_accounts_info();
        // echo '<pre>';print_r($this->items);echo '</pre>';
        //$this->count = count( $this->items );
        // $this->set_pagination_args( [
        //     'total_items' => $total_count,
        //     'per_page' => $per_page,
        // ] );
        
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    function get_hidden_columns(){
        return array();
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function get_columns() {
        $columns = array(
            'id'             => 'ID',
            'so_type'        => '賬號類型',
            'account_name'   => '賬號姓名',
            'account_email'  => '賬號郵箱',
            'account_status' => '賬號狀態',
            'verify_time'    => '更新日期',
            'expire_time'    => '過期日期',
        );

        return $columns;
    }

    function column_default( $item, $column_name ) {
        return $item[ $column_name ];
    }

    function column_account_status( $item ) {
        if( $item['so_type'] == 1 ) {
            if( strtotime( $item['verify_time'] ) < strtotime( $item['expire_time'] ) ) {
                return '正常';
            }else {
                return '失效';
            }
        }else{
            return '';
        }
    }

    function column_so_type( $item ) {
        return $this->plugin->account_types[ $item['so_type'] ];
    }

    function column_verify_time( $item ) {
        return $this->plugin->date_format( $item['verify_time'] );
    }

    function column_expire_time( $item ) {
        if( $item['so_type'] == 1 ) {
            return $this->plugin->date_format( $item['expire_time'] );
        }else{
            return '';
        }
    }
}