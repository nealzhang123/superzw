<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Exam_List_Table extends WP_List_Table {
	public $data, $hos_type;
	public $plugin;
    public $total, $total_count, $total_items;
    public $column_header_define, $column_parameters;

	function __construct( $data ){
		$this->plugin = new HospitalFourTrack;
        $this->data = $data;
        if( !empty( $_REQUEST['hos_type'] ) )
            $this->hos_type = $_REQUEST['hos_type'];
        else
            $this->hos_type = $data['hos_type'];
	}

	function get_userinfos() {
		global $wpdb;

		$table = $wpdb->prefix . 'hos_ry_exam_info';
        if( 1 == $this->hos_type ) {
            $table_basic = $wpdb->prefix . 'hos_in_birth_basic_info';
            $sql = 'SELECT ry.*,tb.name,tb.bname,tb.dedate FROM ' . $table . ' AS ry LEFT JOIN ' . $table_basic . ' AS tb ON tb.no2 = ry.no2 WHERE tb.dedate > "1971-01-01"';
        }else{
            $table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
            $sql = 'SELECT ry.*,tb.name,tb.bname,tb.cname,tb.dedate FROM ' . $table . ' AS ry LEFT JOIN ' . $table_basic . ' AS tb ON tb.no1 = ry.no1 WHERE tb.dedate > "1971-01-01"';
        }

        $sql .= ' AND ry.hos_type = ' . $this->hos_type;

        if( !$this->data['from_key'] ) {
            if( !empty( $this->data['min_exam_date'] ) ) {
                $sql .= ' AND tb.dedate >= "' . $this->data['min_exam_date'] . '"'; 
            }

            if( !empty( $this->data['max_exam_date'] ) ) {
                $sql .= ' AND tb.dedate <= "' . $this->data['max_exam_date'] . '"'; 
            }

            if( $this->data['hos_exam_status'] == '1' ) {
                $sql .= ' AND ry.meu_ry > 0';
            }elseif( $this->data['hos_exam_status'] == '2' ){
                $sql .= ' AND ry.meu_ry < 1';
            }

            if( $this->data['hos_exam_meu'] > 0 ) {
                $sql .= ' AND ry.meu_ry = ' . $this->data['hos_exam_meu'];
            }

            switch ( $this->data['hos_exam_blood_type'] ) {
                case '1'://血清 cserum_ry
                    $sql .= ' AND ry.cserum_ry > 0';

                    break;

                case '2'://血浆 cplasma_ry
                    $sql .= ' AND ry.cplasma_ry > 0';

                    break;

                case '3'://血细胞 cbcell_ry
                    $sql .= ' AND ry.cbcell_ry > 0';

                    break;
                
                default:
                    # code...
                    break;
            }

            //hos_exam_blood_type还不确定
            if( $this->data['hos_exam_bloodqu'] != '-1' ) {
                $sql .= ' AND ry.bloodqu_ry = ' . $this->data['hos_exam_bloodqu'];
            }

            if( $this->data['hos_exam_brtr'] != '-1' ) {
                $sql .= ' AND ry.brtr_ry = ' . $this->data['hos_exam_brtr'];
            }

            if( $this->data['hos_exam_altr'] != '-1' ) {
                $sql .= ' AND ry.altr_ry = ' . $this->data['hos_exam_altr'];
            }
        }else{
            if( 1 == $this->hos_type ) {
                $sql .= ' AND (ry.no2 like "%' . $this->data['key_word'] . '%"';
            }else{
                $sql .= ' AND (ry.no1 like "%' . $this->data['key_word'] . '%"';
            }
            $sql .= ' OR tb.bname like "%' . $this->data['key_word'] . '%"';
            $sql .= ' OR ry.name_ry like "%' . $this->data['key_word'] . '%"';
            $sql .= ' OR tb.name like "%' . $this->data['key_word'] . '%")';     
        }

        $sql2 = $sql . ' ORDER BY tb.dedate ASC, tb.id ASC';

        $result = $wpdb->get_results( $sql2, 'ARRAY_A' );
        $results['count'] = count($result);
        $this->total_items = $result;

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

        $sql .= ' ORDER BY tb.dedate ASC, tb.id ASC';
        $sql .= ' LIMIT ' . $start . ',' . $page_per_num;

        $results['current_items'] = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $results;
	}

	function prepare_items() {

        $per_page = 10;

        $results = $this->get_userinfos();
        $this->items = $results['current_items'];
        $this->total['total_count'] = $this->total_count = $results['count'];

        $this->total['cserum_count'] = $this->total['cplasma_count'] = $this->total['brtr_count'] = $this->total['age_count1'] = $this->total['age_count2'] = 0;

        foreach ( $this->total_items as $key => $item ) {
            if( $item['cserum_ry'] > 0 )
                $this->total['cserum_count']++;

            if( $item['cplasma_ry'] > 0 && $item['cbcell_ry'] > 0 )
                $this->total['cplasma_count']++;

            if( $item['brtr_ry'] > 0 )
                $this->total['brtr_count']++;

            $detime = strtotime( $item['dedate'] );
            $current_time = time();
            if( $current_time - $detime >= 3*365*24*3600 )
                $this->total['age_count1']++;

            if( $current_time - $detime >= 3.5*365*24*3600 )
                $this->total['age_count2']++;
        }
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
        if( in_array( $column_name, array('cserum_ry', 'cplasma_ry', 'cbcell_ry' ) ) ) {
            if( $item[ $column_name ] % 1 == $item[ $column_name ] ){
                return (int)$item[ $column_name ];
            }else{
                return $item[ $column_name ];
            }
    
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

        if( $this->hos_type == 1 )
            $columns['no2']         = '队列编号';
        else
            $columns['no1']         = '队列编号';
        $columns['bname']           = '儿童姓名';
        $columns['name']            = '母亲姓名';
        $columns['dedate']          = '出生日期';
        $columns['hos_exam_status'] = '入园体检状态';
        $columns['name_ry']         = '入园儿童姓名';
        $columns['datet_ry']        = '入园体检日期';
        $columns['cage_ry']         = '入园体检年龄';
        $columns['meu_ry']          = '体检单位';
        $columns['cserum_ry']       = '血清(mL)';
        $columns['cplasma_ry']      = '血浆(mL)';
        $columns['cbcell_ry']       = '血细胞(mL)';
        $columns['bloodqu_ry']      = '血样质量';
        $columns['brtr_ry']         = '血常规结果';
        $columns['altr_ry']         = 'ALT结果';
        $columns['note_ry']         = '备注';

        if( $this->hos_type == 1 ){
            $this->column_parameters = array(
                'no2',
                'bname',
                'name',
                'dedate',
                'hos_exam_status',
                'name_ry',
                'datet_ry',
                'cage_ry',
                'meu_ry',
                'cserum_ry',
                'cplasma_ry',
                'cbcell_ry',
                'bloodqu_ry',
                'brtr_ry',
                'altr_ry',
                'note_ry'
            );
        }else{
            $this->column_parameters = array(
                'no1',
                'bname',
                'name',
                'dedate',
                'hos_exam_status',
                'name_ry',
                'datet_ry',
                'cage_ry',
                'meu_ry',
                'cserum_ry',
                'cplasma_ry',
                'cbcell_ry',
                'bloodqu_ry',
                'brtr_ry',
                'altr_ry',
                'note_ry'
            );
        }

        return $columns;
    }

    function column_meu_ry( $item ) {
        return $this->plugin->meu_arr[$item['meu_ry']];
    } 

    function column_bloodqu_ry( $item ) {
        return $this->plugin->bloodqu_arr[$item['bloodqu_ry']];
    } 

    function column_dedate( $item ) {
    	return $this->plugin->translate_date( $item['dedate'] );
    }

    function column_datet_ry( $item ) {
        return $this->plugin->translate_date( $item['datet_ry'] );
    }

    function column_brtr_ry( $item ) {
        return $this->plugin->get_check_icon( $item['brtr_ry'] );
    }

    function column_altr_ry( $item ) {
        return $this->plugin->get_check_icon( $item['altr_ry'] );
    }

    function column_hos_exam_status( $item ) {
        if( $item['meu_ry'] > 0 )
            return $this->plugin->track_status_arr[1];
        else
            return '';
    } 

    function column_cage_ry( $item ) {
        if( $item['meu_ry'] < 1)
            return '';

        $data1 = $item['datet_ry'];
        $data2 = $item['dedate'];

        $result = $this->plugin->diffDate( $data2, $data1 );
        $re = '';
        if( $result[0] > 0 )
            $re .= $result[0] . 'Y';

        if( $result[1] > 0 )
            $re .= $result[1] . 'M';

        if( $result[2] > 0 )
            $re .= $result[2] . 'D';

        return $re;
    }
    
}
?>