<?php 
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Childbirth_Status_List_Table extends WP_List_Table{
	public $plugin;
	public $pregnant_plugin;
	public $total_count;

	function  __construct(){
		$this->plugin = new HospitalTrack;
		$this->pregnant_plugin = new HospitalPreBirthPregnantTrack;
	}

	function prepare_items(){
		$this->total_count = $this->get_userinfos_count();

		$this->items = $this->get_userinfo();
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_colums();
		$this->_column_headers = array($columns, $hidden, $sortable);
	}

	function get_userinfos_count(){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_childbirth_info';
		$sql = 'SELECT count(*) FROM ' . $table_basic .' ORDER BY id ASC';
		$count = $wpdb->get_var($sql);
		return $count;
	}

	function get_userinfo(){
		global $wpdb;
		$table 			= $wpdb->prefix . 'hos_pre_birth_childbirth_info';
		$table_basic 	= $wpdb->prefix . 'hos_pre_birth_basic_info';
		$sql = 'SELECT basic.id, basic.no1, basic.name, basic.lmp, basic.dedate, basic.no2, childbirth.serumt3, childbirth.plasma_bcellt3,childbirth.cbser,childbirth.cbpla_bcl,childbirth.placent, childbirth.ubcord, childbirth.nmtube, childbirth.urinet3_ent2 FROM ' . $table_basic .' basic inner join ' . $table . '  childbirth WHERE basic.id = childbirth.id ORDER BY childbirth.id ASC';
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
		$sql .=' LIMIT '. $start . ',' . $page_per_num;
		
		$results = $wpdb->get_results($sql,'ARRAY_A');
		return $results;
	}

	function get_columns(){
		$columns['no1']				= '队列编号1';
		$columns['status']			= "状态";
		$columns['name']			= '母亲姓名';
		$columns['lmp']				= '末次月经';
		$columns['gawdelive']		= '分娩孕周';
		$columns['dedate']			= '分娩日期';
		$columns['no2']				= '队列编号2	';
		$columns['serumt3']			= '晚期血清';
		$columns['plasma_bcellt3']	= '晚期血浆血细胞';
		$columns['cbser']			= '脐血清';
		$columns['cbpla_bcl']		= '脐血浆血细胞';
		$columns['placent']			= '胎盘';
		$columns['ubcord']			= '脐带';
		$columns['nmtube']			= '胎粪';
		$columns['urinet3_ent2']	= '晚期尿-Ent2';
		$columns['tool']			= '操作';
		return $columns;
	}

	function get_hidden_columns(){
		return array('id');
	}

	function column_default( $item, $column_name ){
		if( in_array($column_name ,array('serumt3', 'plasma_bcellt3', 'cbser', 'cbpla_bcl', 'placent', 'ubcord', 'nmtube', 'urinet3_ent2'))){
			if( 1 == $item[$column_name]){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></div>';
			}
		}else{
			echo '<div id="'.$column_name.'_'.$item['id'].'">'.$item[$column_name].'</div>';
		}
    }

    function no_items() {
        echo __('没有相关数据');
    }
    function column_lmp($item){
    	// $lmp_time = date('Y-m-d',strtotime($item['lmp']));
    	// echo '<div id=" lmp ' . $item['id'] . '"> '. $lmp_time .'</div>'; 
    	// error_log($item['lmp']);
    	if(!empty($item['lmp']) && ($item['lmp'] != '0000-00-00 00:00:00') ){
			$lmp_time = date('Y-m-d',strtotime($item['lmp']));
    		echo '<div id=" lmp ' . $item['id'] . '"> '. $lmp_time .'</div>'; 
		}else{
			echo '<div id=" lmp ' . $item['id'] . '"> </div>'; 
		}

    }

	function column_dedate($item){
		if(!empty($item['dedate']) && ($item['dedate'] != '0000-00-00 00:00:00')){
			$dedate_time = date('Y-m-d',strtotime($item['dedate']));
    		echo '<div id=" dedate ' . $item['id'] . '"> '. $dedate_time .'</div>'; 
		}else{
			echo '<div id=" dedate ' . $item['id'] . '"> </div>'; 
		}
    	

    }

	function column_status($item){
		if( !empty( $item['dedate'] )  && '0000-00-00 00:00:00' != $item['dedate']){

    		$days = abs((strtotime($item['dedate'])-strtotime($item['lmp']))/86400);
    	}else{
    		$days = abs((strtotime(date('Y-m-d 12:00:00'))-strtotime($item['lmp']))/86400);
    	}
		//$days = abs((strtotime($item['dedate'])-strtotime($item['lmp']))/86400);
		$week = floor($days/7);
		$day = $days%7;
		if( !empty($item['dedate']) && ($item['dedate'] != '0000-00-00 00:00:00') ){
			echo '<div id="status_'.$item['id'].'"> ' . $this->plugin->track_status_arr['1'] . ' </div>';
		}else{
			if( $week >= 45){
				echo '<div id=" status_' . $item['id'] . '">'.$this->plugin->track_status_arr['5'].'</div>';
			}else{
				echo '<div id=" status_' . $item['id'] . '"></div>';
			}
		}


	}
    function column_gawdelive( $item ){
  
    	// $days=abs((strtotime(date("Y-m-d 12:00:00"))-strtotime($item['lmp']))/86400);
    	if( !empty( $item['dedate'] )  && '0000-00-00 00:00:00' != $item['dedate']){

    		$days = abs((strtotime($item['dedate'])-strtotime($item['lmp']))/86400);
    	}else{
    		$days = abs((strtotime(date('Y-m-d 12:00:00'))-strtotime($item['lmp']))/86400);
    	}
    	
		$week = floor($days/7);
		$day = $days%7;
		echo '<div id=" gawdelive_'.$item['id'].'">'.$week.'+'.$day.'</div>';
	}

    function column_tool( $item ) {
        echo '<a class="pre_childbirth_status_item_edit" data-id="' . $item['id'] . '" m1_tab_no="0" sys_tab_no="0"><i class="fa fa-pencil-square-o"></i>编辑</a>';
    }
}