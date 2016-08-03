<?php 
include_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Hospital_Pre_Birth_Pregnant_List_Table extends WP_List_Table{
	public $plugin;
	public $total_count;

	function  __construct(){
		$this->plugin = new HospitalTrack;
	}

	function prepare_items(){
		$this->total_count = $this->get_userinfos_count();

		$this->items = $this->get_userinfo();
		// error_log(var_export($this->items,true));
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_colums();
		$this->_column_headers = array($columns, $hidden, $sortable);
	}

	function get_userinfos_count(){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';
		$sql = 'SELECT count(*) FROM ' . $table_basic .' ORDER BY id ASC';
		$count = $wpdb->get_var($sql);
		return $count;
	}

	function get_userinfo(){
		global $wpdb;
		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';
		$table = $wpdb->prefix . 'hos_pre_birth_three_pregnant_info';

		$sql = 'SELECT basic.id, basic.no1, basic.name, basic.lmp, basic.pphone, basic.hphone ,pregnant.serumt1, pregnant.plasma_bcellt1, pregnant.urinet1, pregnant.pablood, pregnant.serumt2, pregnant.urinet2, pregnant.urinet3_ent1, pregnant.fut2, pregnant.fut2rem, pregnant.fut2er FROM ' .$table_basic . ' basic inner join '. $table .' pregnant  WHERE basic.id =  pregnant.id ORDER BY pregnant.id ASC';
		// error_log($sql);
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
		$columns['gawt']			= '今日孕周';
		$columns['pphone']			= '母亲电话';
		$columns['hphone']			= '父亲电话';
		$columns['serumt1']			= '早期血清';
		$columns['plasma_bcellt1']	= '早期血浆血细胞';
		$columns['urinet1']			= '早期尿';
		$columns['pablood']			= '父亲血';
		$columns['serumt2']			= '中期血清';
		$columns['urinet2']			= '中期尿';
		$columns['urinet3_ent1']	= '晚期尿-Ent1';
		$columns['fut2']			= '孕中期电话';
		$columns['fut2rem']			= '孕中期电话备注';
		$columns['fut2er']			= '孕中期电话人';
		$columns['tool']			= '操作';
		return $columns;
	}

	function get_hidden_columns(){
		return array('id');
	}

	function column_default( $item, $column_name ){
		if( 'gawt' == $column_name ){

			$days=abs((strtotime(date("Y-m-d 12:00:00"))-strtotime($item['lmp']))/86400);
			$week = floor($days/7);
			$day = $days%7;
			echo '<div id="'.$column_name.'_'.$item['id'].'">'.$week.'+'.$day.'</div>';

		}elseif( 'status' == $column_name ){

			$days=abs((strtotime(date("Y-m-d 12:00:00"))-strtotime($item['lmp']))/86400);
			$week = floor($days/7);
			$day = $days%7;
			if( 1 == $item['serumt2']){
				echo '<div id="'.$column_name.'_'.$item['id'].'">'. $this->plugin->track_status_arr['1'] .'</div>';
			}elseif($week >= 27 && (0 == $item['serumt2'] || 0 == $item['urinet2'] ) ){
				echo '<div id="'.$column_name.'_'.$item['id'].'">'. $this->plugin->track_status_arr['7'] .'</div>';
			}elseif( '完成' == $item['fut2'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'">'. $this->plugin->track_status_arr['2'] .'</div>';
			}elseif( '失联' == $item['fut2rem'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'">'. $this->plugin->track_status_arr['3'] .'</div>';
			}

		}elseif( 'serumt1' == $column_name ){

			if( 1 == $item['serumt1'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}elseif( 'lmp' == $column_name ){
			$lmp_time =date('Y-m-d' ,strtotime($item[$column_name]));
			echo '<div id="'.$column_name.'_'.$item['id'].'">'.$lmp_time.'</div>';

		}elseif( 'plasma_bcellt1' == $column_name ){

			if( 1 == $item['plasma_bcellt1'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}elseif( 'urinet1' == $column_name ){

			if( 1 == $item['urinet1'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}elseif( 'pablood' == $column_name ){

			if( 1 == $item['pablood'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}elseif( 'serumt2' == $column_name ){

			if( 1 == $item['serumt2'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}elseif( 'urinet2' == $column_name ){

			if( 1 == $item['urinet2'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}elseif( 'urinet3_ent1' == $column_name ){

			if( 1 == $item['urinet3_ent1'] ){
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-check" aria-hidden="true"></i></div>';
			}else{
				echo '<div id="'.$column_name.'_'.$item['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
			}

		}else{

			echo '<div id="'.$column_name.'_'.$item['id'].'">'.$item[$column_name].'</div>';
		}
    }

    function no_items() {
        echo __('没有相关数据');
    }

    function column_tool( $item ) {
        echo '<a class="pre_pregnant_item_edit" data-id="' . $item['id'] . '" m1_tab_no="0" sys_tab_no="0"><i class="fa fa-pencil-square-o"></i>编辑</a>';
    }
}