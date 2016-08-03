<?php
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Four_Track_Result_Table extends WP_List_Table {
	public $model, $hos_type;
    public $plugin;
    public $total_count,$total_items;
    public $telquesre_arr;
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

        $this->telquesre_arr = $this->plugin->telquesre_arr;
	}

	function get_userinfos() {
		global $wpdb;

        switch ( $this->model) {
            case $this->plugin->model_y3:
                $track_info = $wpdb->prefix . 'hos_3y_track_info';

                break;

            case $this->plugin->model_y5:
                $track_info = $wpdb->prefix . 'hos_5y_track_info';

                break;

            default:
                return array();
                break;
        }

        if( $this->hos_type == 1 ){
            $table_basic = $wpdb->prefix . 'hos_in_birth_basic_info';

            $sql = 'SELECT ti.*,tb.bname as bname,tb.name as name,tb.dedate as dedate,tb.no2 as no2 FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $track_info . ' AS ti ON tb.no2 = ti.no2 WHERE tb.dedate > "1971-01-01"';
        }else{
            $table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';

            $sql = 'SELECT ti.*,tb.bname as bname,tb.cname as cname,tb.name as name,tb.dedate as dedate,tb.no1 as no1 FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $track_info . ' AS ti ON tb.no1 = ti.no1 WHERE tb.dedate > "1971-01-01"';
        }

        $sql2 = $sql . ' ORDER BY tb.dedate ASC,ti.id ASC';
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

        //echo '<pre>';print_r($results);echo '</pre>';

		return $results;
	}

	function prepare_items() {

        $this->items = $this->get_userinfos();
        $this->total_count = count( $this->total_items );

        $this->column_header_define = $this->get_columns();

    }

    function get_columns() {

        $columns['operate']  = '操作';
        if( $this->hos_type == 1 ){
            $columns['no2']  = '队列编号no2';
        }else{
            $columns['no1']  = '队列编号no1';
        }
        
        $columns['status']   = '状态';
        $columns['district'] = '区或区编号';
        $columns['bname']    = '儿童姓名';
        $columns['name']     = '母亲姓名';
        $columns['dedate']   = '出生日期';
        $columns['cage']     = '儿童年龄';

        switch ( $this->model ) {
            case $this->plugin->model_y3:
                $model_name = '3岁';

                $columns['phmeas']          = '体格测量和评价' . $model_name;
                $columns['pexam']           = '体格检查' . $model_name;
                $columns['vision']          = '视力' . $model_name;
                $columns['hearing']         = '听力' . $model_name;
                $columns['oral']            = '口腔检查及防龋' . $model_name;
                $columns['btype']           = '血型';
                $columns['blroutine']       = '血常规' . $model_name;
                $columns['bllead']          = '血铅' . $model_name;
                $columns['hemoglobin']      = '血红蛋白' . $model_name;
                $columns['trelements']      = '微量元素' . $model_name;
                $columns['chmeigg']         = '水痘带状疱疹病毒IgG抗体' . $model_name;
                $columns['hbeag']           = '乙肝表面抗体或两对半' . $model_name;
                $columns['mvigg']           = '麻疹病毒IgG抗体' . $model_name;
                $columns['look']            = '注视性质检查' . $model_name;
                $columns['stradip']         = '斜视度与复视' . $model_name;
                $columns['bmd']             = '骨密度' . $model_name;
                $columns['si']              = '感统' . $model_name;
                $columns['ptq']             = '气质' . $model_name;
                $columns['plasma']          = '血浆' . $model_name;
                $columns['bcell']           = '血细胞' . $model_name;
                $columns['churine']         = '尿液' . $model_name;
                $columns['chfaeces']        = '粪便' . $model_name;
                $columns['paquestion']      = '家长问卷' . $model_name;
                $columns['tequestion']      = '教师问卷' . $model_name;
                $columns['trs']             = '教师多动问卷' . $model_name;
                $columns['asq']             = '多动简明问卷' . $model_name;
                $columns['chealthhandbook'] = '保健手册' . $model_name;
                $columns['vacertifi']       = '预防接种本' . $model_name;
                $columns['cbcl']            = 'cbcl量表' . $model_name;
                $columns['abc']             = 'ABC量表' . $model_name;
                $columns['kindergarten']    = '幼儿园名称' . $model_name;
                $columns['enrolldate']      = '入园日期' . $model_name;
                $columns['class']           = '班级' . $model_name;
                $columns['exdate']          = '体检日期' . $model_name;
                $columns['completerkd']     = '幼儿园完成人' . $model_name;
                $columns['notekd']          = '备注-幼儿园' . $model_name;
                $columns['phquestion']      = '电话随访问卷' . $model_name;
                $columns['pqudate']         = '电话问卷日期' . $model_name;
                $columns['notetel']         = '备注电话' . $model_name;
                $columns['completertel']    = '电话完成人' . $model_name;

                if( $this->hos_type == 1 ){
                    $array1 = array( 'no2');
                }else{
                    $array1 = array( 'no1');
                }
                
                $array2 = array(
                    'status' . $this->pre,
                    'district',
                    'bname',
                    'name',
                    'dedate',
                    'cage',
                    'phmeas' . $this->pre,
                    'pexam' . $this->pre,
                    'vision' . $this->pre,
                    'hearing' . $this->pre,
                    'oral' . $this->pre,
                    'btype',
                    'blroutine' . $this->pre,
                    'bllead' . $this->pre,
                    'hemoglobin' . $this->pre,
                    'trelements' . $this->pre,
                    'chmeigg' . $this->pre,
                    'hbeag' . $this->pre,
                    'mvigg' . $this->pre,
                    'look' . $this->pre,
                    'stradip' . $this->pre,
                    'bmd' . $this->pre,
                    'si' . $this->pre,
                    'ptq' . $this->pre,
                    'plasma' . $this->pre,
                    'bcell' . $this->pre,
                    'churine' . $this->pre,
                    'chfaeces' . $this->pre,
                    'paquestion' . $this->pre,
                    'tequestion' . $this->pre,
                    'trs' . $this->pre,
                    'asq' . $this->pre,
                    'chealthhandbook' . $this->pre,
                    'vacertifi' . $this->pre,
                    'cbcl' . $this->pre,
                    'abc' . $this->pre,
                    'kindergarten' . $this->pre,
                    'enrolldate' . $this->pre,
                    'class' . $this->pre,
                    'exdate' . $this->pre,
                    'completerkd' . $this->pre,
                    'notekd' . $this->pre,
                    'phquestion' . $this->pre,
                    'pqudate' . $this->pre,
                    'notetel' . $this->pre,
                    'completertel' . $this->pre,
                );
 
                $this->column_parameters = array_merge( $array1, $array2 );

                break;

            case $this->plugin->model_y5:
                $model_name = '5岁';

                $columns['phmeas']          = '体格测量和评价' . $model_name;
                $columns['pexam']           = '体格检查' . $model_name;
                $columns['vision']          = '视力' . $model_name;
                $columns['hearing']         = '听力' . $model_name;
                $columns['oral']            = '口腔检查及防龋' . $model_name;
                $columns['btype']           = '血型';
                $columns['blroutine']       = '血常规' . $model_name;
                $columns['bllead']          = '血铅' . $model_name;
                $columns['hemoglobin']      = '血红蛋白' . $model_name;
                $columns['trelements']      = '微量元素' . $model_name;
                $columns['chmeigg']         = '水痘带状疱疹病毒IgG抗体' . $model_name;
                $columns['hbeag']           = '乙肝表面抗体或两对半' . $model_name;
                $columns['mvigg']           = '麻疹病毒IgG抗体' . $model_name;
                $columns['look']            = '注视性质检查' . $model_name;
                $columns['stradip']         = '斜视度与复视' . $model_name;
                $columns['bmd']             = '骨密度' . $model_name;
                $columns['si']              = '感统' . $model_name;
                $columns['ptq']             = '气质' . $model_name;
                $columns['plasma']          = '血浆' . $model_name;
                $columns['bcell']           = '血细胞' . $model_name;
                $columns['churine']         = '尿液' . $model_name;
                $columns['chfaeces']        = '粪便' . $model_name;
                $columns['paquestion']      = '家长问卷' . $model_name;
                $columns['tequestion']      = '教师问卷' . $model_name;
                $columns['trs']             = '教师多动问卷' . $model_name;
                $columns['asq']             = '多动简明问卷' . $model_name;
                $columns['chealthhandbook'] = '保健手册' . $model_name;
                $columns['vacertifi']       = '预防接种本' . $model_name;
                $columns['cbcl']            = 'cbcl量表' . $model_name;
                $columns['abc']             = 'ABC量表' . $model_name;
                $columns['kindergarten']    = '幼儿园名称' . $model_name;
                $columns['enrolldate']      = '入园日期' . $model_name;
                $columns['class']           = '班级' . $model_name;
                $columns['exdate']          = '体检日期' . $model_name;
                $columns['completerkd']     = '幼儿园完成人' . $model_name;
                $columns['notekd']          = '备注-幼儿园' . $model_name;
                $columns['phquestion']      = '电话随访问卷' . $model_name;
                $columns['pqudate']         = '电话问卷日期' . $model_name;
                $columns['notetel']         = '备注电话' . $model_name;
                $columns['completertel']    = '电话完成人' . $model_name;
                $columns['wppsidate']       = '韦氏测试日期' . $model_name;
                $columns['wppsi']           = '韦氏测试' . $model_name;
                $columns['completerwpp']    = '韦氏完成人' . $model_name;
                $columns['notewpp']         = '备注韦氏';

                if( $this->hos_type == 1 ){
                    $array1 = array( 'no2');
                }else{
                    $array1 = array( 'no1');
                }
                
                $array2 = array(
                    'status' . $this->pre,
                    'district',
                    'bname',
                    'name',
                    'dedate',
                    'cage',
                    'phmeas' . $this->pre,
                    'pexam' . $this->pre,
                    'vision' . $this->pre,
                    'hearing' . $this->pre,
                    'oral' . $this->pre,
                    'btype',
                    'blroutine' . $this->pre,
                    'bllead' . $this->pre,
                    'hemoglobin' . $this->pre,
                    'trelements' . $this->pre,
                    'chmeigg' . $this->pre,
                    'hbeag' . $this->pre,
                    'mvigg' . $this->pre,
                    'look' . $this->pre,
                    'stradip' . $this->pre,
                    'bmd' . $this->pre,
                    'si' . $this->pre,
                    'ptq' . $this->pre,
                    'plasma' . $this->pre,
                    'bcell' . $this->pre,
                    'churine' . $this->pre,
                    'chfaeces' . $this->pre,
                    'paquestion' . $this->pre,
                    'tequestion' . $this->pre,
                    'trs' . $this->pre,
                    'asq' . $this->pre,
                    'chealthhandbook' . $this->pre,
                    'vacertifi' . $this->pre,
                    'cbcl' . $this->pre,
                    'abc' . $this->pre,
                    'kindergarten' . $this->pre,
                    'enrolldate' . $this->pre,
                    'class' . $this->pre,
                    'exdate' . $this->pre,
                    'completerkd' . $this->pre,
                    'notekd' . $this->pre,
                    'phquestion' . $this->pre,
                    'pqudate' . $this->pre,
                    'notetel' . $this->pre,
                    'completertel' . $this->pre,
                    'wppsidate' . $this->pre,
                    'wppsi' . $this->pre,
                    'completerwpp' . $this->pre,
                    'notewpp' . $this->pre,
                );
 
                $this->column_parameters = array_merge( $array1, $array2 );

                break;
            
            default:
                # code...
                break;
        }

        return $columns;
    }

    function get_track_status( $item ) {
        if( $item['status'.$this->pre] > 0 ){
            return $this->plugin->track_status_arr[$item['status'.$this->pre]];
        }else{
            $dedate = $item['dedate'];
            $now_date = date('Y-m-d');
            $diff = $this->plugin->diffDate( $dedate, $now_date );

            switch ( $this->model) {
                case $this->plugin->model_y3:
                    if( $diff[0] >4 || ( $diff[0] == 4 && $diff[1] >= 6 ) ){
                        $status = 8;
                    }elseif( $diff[0] < 2 || ( $diff[0] == 2 && $diff[1] < 8 ) ) {
                        $status = 6;
                    }else{
                        $status = 7;
                    }

                    break;

                case $this->plugin->model_y5:
                    $this->pre = '_5y';

                    if( $diff[0] >6 || ( $diff[0] == 6 && $diff[1] >= 6 ) ){
                        $status = 8;
                    }elseif( $diff[0] < 4 || ( $diff[0] == 4 && $diff[1] < 8 ) ) {
                        $status = 6;
                    }else{
                        $status = 7;
                    }

                    break;
                
                default:
                    $this->pre = '_3y';

                    break;
            }
            return $this->plugin->track_status_arr[$status];
        }
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

}

?>