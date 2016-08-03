<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Crm_sms_List extends WP_List_Table {
    public $cate_id;

	function __construct( $cate_id ){
        $this->cate_id = $cate_id;

	}

	function get_sms_count(){
        global $wpdb;

        if( empty( $this->cate_id ) ) {
            $sms_table = $wpdb->prefix . 'crm_sms_info';
            $sql = "SELECT count(*) FROM " . $sms_table . " WHERE is_delete=0";

            $count = $wpdb->get_var( $sql );
        }else{
            $category_table = $wpdb->prefix . 'crm_category';
            $sql = "SELECT cate_condition FROM " . $category_table . " WHERE cate_id=" . $this->cate_id;
            $condition = $wpdb->get_val( $sql );

            //分类视图后续。。。
        }

        return $count;
	}

	function get_sms_infos( $per_page = 10 ,$page = 1 ) {
		global $wpdb;

        if( empty( $this->cate_id ) ) {
            $sms_table = $wpdb->prefix . 'crm_sms_info';
            $sql = "SELECT * FROM " . $sms_table . " WHERE is_delete=0";

            $sms = $wpdb->get_results( $sql, 'ARRAY_A' );
        }else{
            $category_table = $wpdb->prefix . 'crm_category';
            $sql = "SELECT cate_condition FROM " . $category_table . " WHERE cate_id=" . $this->cate_id;
            $condition = $wpdb->get_val( $sql );

            //分类视图后续。。。
        }

        $sms_groups = array();
        $new_smses = array();
        $i = 0;
        foreach ( $sms as $item ) {
            if( in_array( $item['sms_group'], $sms_groups ) ) {
                $new_smses[$i-1]['recipient_phone_new'][] = $item['recipient_phone'];
                $new_smses[$i-1]['recipient_name_new'][] = $item['recipient_name'];
            }else{
                $sms_groups[] = $item['sms_group'];
                $new_smses[$i] = $item;
                $new_smses[$i]['recipient_phone_new'][] = $item['recipient_phone'];
                $new_smses[$i]['recipient_name_new'][] = $item['recipient_name'];
                $i++;
            }
        }
        //echo '<pre>';print_r($new_smses);echo '</pre>';

        return $new_smses;
	}

    function prepare_items() {

        $per_page = 10;
        $total_count = $this->get_sms_count();
        // $page = isset($_GET['paged']) ? $_GET['paged'] : '1';
        // $page = (int) $page;
        // if ($page <= 0)
        //     $page = 1;

        // if ($page > ceil($total_count / $per_page))
        //     $page = ceil($total_count / $per_page);

        $this->items = $this->get_sms_infos();
        // $this->set_pagination_args( [
        //     'total_items' => $total_count,
        //     'per_page' => $per_page,
        // ] );
        
        $columns = $this->get_columns();
        //echo '<pre>';print_r($columns);echo '</pre>';
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        //echo '<pre>';print_r($this->items);echo '</pre>';
        $this->_column_headers = array($columns, $hidden, $sortable);
    }


    function column_default( $item, $column_name ){
        return $item[$column_name];
    }


    function get_hidden_columns(){
        return array();
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function get_columns() {

        $columns = array();

        $columns['cb']              = '<input type="checkbox" />';
        $columns['sms_content']     = '内容';
        $columns['user_id']         = '发件人';
        $columns['recipient_phone'] = '收件人电话';
        $columns['recipient_name']  = '收件人';
        $columns['sms_content']     = '短信内容';
        //$columns['sms_status']      = '发送状态';
        $columns['sms_public_time'] = '发送时间';
        $columns['tools']           = '删除';
    	
        return $columns;
    }

    function column_tools( $item ) {
        echo '<a class="list_item_delete" data-pid="'.$item['sms_group'].'"><i class="fa fa-trash-o"></i></a>';
    }

    function column_cb( $item ) {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']);
    }

    function column_user_id( $item ) {
        $user = get_userdata( $item['user_id'] );
        echo $user->display_name;
    }
    
    function column_recipient_phone( $item ) {
        $recipient_phone_new = $item['recipient_phone_new'];

        echo '<ul class="list-group">';
        foreach ($recipient_phone_new as $key => $phone) {
            echo '<li class="crm-li-item">' . $phone . '</li>';
        }
        echo '</ul>';
    }

    function column_recipient_name( $item ) {
        $recipient_name_new = $item['recipient_name_new'];

        echo '<ul class="list-group">';
        foreach ($recipient_name_new as $key => $name) {
            echo '<li class="crm-li-item">' . $name . '</li>';
        }
        echo '</ul>';
    }

}
?>