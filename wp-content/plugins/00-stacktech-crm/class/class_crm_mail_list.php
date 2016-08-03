<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Crm_Mail_List extends WP_List_Table {
    public $cate_id;

	function __construct( $cate_id ){
        $this->cate_id = $cate_id;

	}

	function get_mail_count(){
        global $wpdb;

        if( empty( $this->cate_id ) ) {
            $mail_table = $wpdb->prefix . 'crm_mail_info';
            $sql = "SELECT count(*) FROM " . $mail_table . " WHERE is_delete=0";

            $count = $wpdb->get_var( $sql );
        }else{
            $category_table = $wpdb->prefix . 'crm_category';
            $sql = "SELECT cate_condition FROM " . $category_table . " WHERE cate_id=" . $this->cate_id;
            $condition = $wpdb->get_val( $sql );

            //分类视图后续。。。
        }

        return $count;
	}

	function get_mail_infos( $per_page = 10 ,$page = 1 ) {
		global $wpdb;

        if( empty( $this->cate_id ) ) {
            $mail_table = $wpdb->prefix . 'crm_mail_info';
            $sql = "SELECT * FROM " . $mail_table . " WHERE is_delete=0";

            $mail = $wpdb->get_results( $sql, 'ARRAY_A' );
        }else{
            $category_table = $wpdb->prefix . 'crm_category';
            $sql = "SELECT cate_condition FROM " . $category_table . " WHERE cate_id=" . $this->cate_id;
            $condition = $wpdb->get_val( $sql );

            //分类视图后续。。。
        }

        $mail_groups = array();
        $new_mails = array();
        $i = 0;
        foreach ( $mail as $item ) {
            if( in_array( $item['mail_group'], $mail_groups ) ) {
                $new_mails[$i-1]['recipient_email_new'][] = $item['recipient_email'];
                $new_mails[$i-1]['recipient_name_new'][] = $item['recipient_name'];
            }else{
                $mail_groups[] = $item['mail_group'];
                $new_mails[$i] = $item;
                $new_mails[$i]['recipient_email_new'][] = $item['recipient_email'];
                $new_mails[$i]['recipient_name_new'][] = $item['recipient_name'];
                $i++;
            }
        }
        //echo '<pre>';print_r($new_mails);echo '</pre>';

        return $new_mails;
	}

    function prepare_items() {

        $per_page = 10;
        $total_count = $this->get_mail_count();
        // $page = isset($_GET['paged']) ? $_GET['paged'] : '1';
        // $page = (int) $page;
        // if ($page <= 0)
        //     $page = 1;

        // if ($page > ceil($total_count / $per_page))
        //     $page = ceil($total_count / $per_page);

        $this->items = $this->get_mail_infos();
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

    // function column_mail_name( $item ) {
    //     echo '<a href="'.admin_url( 'admin.php?page=crm_edit_model&model=mail&pid='.$item['mail_id'] ).'" target="_blank">'.$item['mail_name'].'</i></a>';
    // }

    function get_hidden_columns(){
        return array();
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function get_columns() {

        $columns = array();

        $columns['cb']              = '<input type="checkbox" />';
        $columns['mail_topic']      = '主题';
        $columns['user_id']         = '发件人';
        $columns['user_email']      = '发件人邮件';
        $columns['recipient_email'] = '收件人邮件';
        $columns['recipient_name']  = '收件人';
        $columns['mail_content']    = '邮件内容';
        //$columns['mail_status']     = '发送状态';
        $columns['mail_time']       = '发送时间';
        $columns['tools']           = '删除';
    	
        return $columns;
    }

    function column_tools( $item ) {
        echo '<a class="list_item_delete" data-pid="'.$item['mail_group'].'"><i class="fa fa-trash-o"></i></a>';
    }

    function column_cb( $item ) {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']);
    }

    function column_user_id( $item ) {
        $user = get_userdata( $item['user_id'] );
        echo $user->display_name;
    }

    function column_recipient_email( $item ) {
        $recipient_email_new = $item['recipient_email_new'];

        echo '<ul class="list-group">';
        foreach ($recipient_email_new as $key => $email) {
            echo '<li class="crm-li-item">' . $email . '</li>';
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

    // function column_customer_id( $item ) {
    //     global $wpdb;

    //     $table = $wpdb->prefix . 'crm_customer_info';

    //     $sql = "SELECT customer_name FROM " . $table . " WHERE customer_id=" . $item['customer_id'];
    //     $customer_name = $wpdb->get_var( $sql );

    //     echo $customer_name;
    // }

}
?>