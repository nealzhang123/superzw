<?php
/**
* 
*/
class HospitalFourTrack extends HospitalTrack {
	public $is_mobile, $page_arr, $track_status_arr;
	public $model_ex, $hos_db;
	public $ex_stat, $ex_import;
	public $meu_arr, $bloodqu_arr, $note_tel_arr ,$note_local_arr, $telre_arr, $track_status_content;
	public $track_result, $track_import, $track_list, $tel_list;
	public $hos_type,$user_name;

	function __construct() {

		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	        spl_autoload_register( 'HospitalTrack::autoloadClass', true, true);
	    } else {
	        spl_autoload_register( 'HospitalTrack::autoloadClass' );
	    }

	    if( wp_is_mobile() )
	    	$this->is_mobile = true;

	    date_default_timezone_set('PRC');

	    $this->hos_db = new Hospital_Db;

		$this->page_arr = array(
			0 => 10,
			1 => 20,
			2 => 50,
			3 => 100,
			);

		//随访状态	根据随访信息的录入显示颜色 1.完成现场随访 2.完成电话随访 3.电话随访失联 4.电话随访拒绝	 5.宝宝夭折 6.未到随访时间 7.到随访时间人还没来  8.未处理 9. 韦氏测试
		$this->track_status_arr = array(
			1 => '<img src="'.esc_url( plugins_url( 'images/green.png', __FILE__ ) ).'">',
			2 => '<img src="'.esc_url( plugins_url( 'images/blue.png', __FILE__ ) ).'">',
			3 => '<img src="'.esc_url( plugins_url( 'images/grey.png', __FILE__ ) ).'">',
			4 => '<img src="'.esc_url( plugins_url( 'images/purple.png', __FILE__ ) ).'">',
			5 => '<img src="'.esc_url( plugins_url( 'images/black.png', __FILE__ ) ).'">',
			6 => '<img src="'.esc_url( plugins_url( 'images/white.png', __FILE__ ) ).'">',
			7 => '<img src="'.esc_url( plugins_url( 'images/yellow.png', __FILE__ ) ).'">',
			8 => '<img src="'.esc_url( plugins_url( 'images/red.png', __FILE__ ) ).'">',
			9 => '<img src="'.esc_url( plugins_url( 'images/pink.png', __FILE__ ) ).'">',
			);
		
		$this->model_ex = 'exam';
		$this->model_y3 = 'y3';
		$this->model_y5 = 'y5';

		$this->track_result = 'track_result';
		$this->track_import = 'track_import';
		$this->track_list   = 'track_list';
		$this->tel_list     = 'tel_list';

		$this->ex_stat = 'exam_stat';
		$this->ex_import = 'exam_import';

		$this->meu_arr = array(
			1  => '江岸区',
			2  => '江汉区',
			3  => '硚口区',
			4  => '汉阳区',
			5  => '武昌区',
			6  => '洪山区',
			7  => '青山区',
			8  => '沌口开发区',
			9  => '东西湖区',
			10 => '汉南区',
			11 => '江夏区',
			12 => '蔡甸区',
			13 => '黄陂区',
			14 => '新洲区',
			16 => '东湖高新技术开发区',
			19 => '市妇幼',
			21 => '化工区',
		);

		$this->bloodqu_arr = array(
			1 => '正常',
			2 => '溶血',
			3 => '其他异常'
			);

		$this->note_tel_arr = array(
			3 => '失联',
			4 => '拒绝'
			);

		$this->note_local_arr = array(
			1 => '没有尿液',
			2 => '没有粪便',
			3 => '没有尿液和粪便',
			);

		$this->track_status_content = array(
			1 => '完成现场随访',
			2 => '完成电话随访',
			3 => '电话随访失联',
			4 => '电话随访拒绝',
			5 => '宝宝夭折',
			6 => '未到随访时间',
			7 => '到随访时间还没来',
			8 => '逾期未处理',
			9 => '完成韦氏测试'
			);

		//$this->hos_type = 1;//出生队列

		$current_user = wp_get_current_user();
		$this->user_name = $current_user->display_name;
	}

	function get_hos_status_content_view() {
?>
    	<table class="table">
    	<tr>
    		<th>完成现场随访&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/green.png', __FILE__ ) ) ?>" /></th>
    		<th>完成韦氏测试&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/pink.png', __FILE__ ) ) ?>" /></th>
    		<th>完成电话随访&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/blue.png', __FILE__ ) ) ?>" /></th>
    		<th>电话随访失联&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/grey.png', __FILE__ ) ) ?>" /></th>
    		<th>电话随访拒绝&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/purple.png', __FILE__ ) ) ?>" /></th>
    		<!-- <th>宝宝夭折&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/black.png', __FILE__ ) ) ?>" /></th> -->
    		<th>未到随访时间&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/white.png', __FILE__ ) ) ?>" /></th>
    		<th>到随访时间人还没来&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/yellow.png', __FILE__ ) ) ?>" /></th>
    		<th>逾时未处理&nbsp;&nbsp;&nbsp;<img src="<?php echo esc_url( plugins_url( 'images/red.png', __FILE__ ) ) ?>" /></th>
    	</tr>
    	</table>
<?php
    }
	
	//主界面
	function four_track_manage() {
		$this->hos_type = 1;

		if( !isset( $_REQUEST['hos_type'] ) ){
			$this->load_view( 'four-track-list', 'in-birth-main' );

			return;
		}else{
			$this->hos_type = $_REQUEST['hos_type'];
		}

		$model = $_REQUEST['model'];

		if( $this->hos_type == 1 ) {
			$type_name = '出生队列';
		}

		if( $this->hos_type == 2 ) {
			$type_name = '产前队列';
		}

		switch ( $model ) {
			case $this->model_ex:
				$title = $type_name . '——入园体检';
				$view_nav = 'hos-exam';

				if( !isset( $_REQUEST['hos_action'] ) )
					$hos_action = $this->ex_stat;
				else
					$hos_action = $_REQUEST['hos_action'];

				switch ( $hos_action ) {
					case $this->ex_stat;
						$view_content = 'hos-four-track-exam-stat';

						break;

					case $this->ex_import;
						$view_content = 'hos-four-track-exam-import';

						break;
					
					default:
						# code...
						break;
				}
				
				break;

			case $this->model_y3;
				$title = $type_name . '——3-4岁随访';
				$view_nav = 'four-track-nav';

				if( !isset( $_REQUEST['hos_action'] ) )
					$hos_action = $this->track_result;
				else
					$hos_action = $_REQUEST['hos_action'];

				switch ( $hos_action ) {
					case $this->track_result;
						$view_content = 'four-track-result';
						$list = new Hospital_Four_Track_Result_Table( $model, $this->hos_type );
						$list->prepare_items();
						$data['list'] = $list;
						//echo '<pre>';print_r($list);echo '</pre>';exit();

						break;

					case $this->track_import;
						$view_content = 'four-track-import';

						break;

					case $this->track_list;
						$view_content = 'four-track-list';
						$list = new Hospital_Four_Track_List_Table( $model, $this->hos_type );
						$list->prepare_items();
						$data['list'] = $list;

						break;

					case $this->tel_list;
						$view_content = 'four-tel-list';
						$list = new Hospital_Four_Tel_List_Table( $model, $this->hos_type );
						$list->prepare_items();
						$data['list'] = $list;

						break;
					
					default:
						# code...
						break;
				}

				break;

			case $this->model_y5;
				$title = $type_name . '——5-6岁随访';
				$view_nav = 'four-track-nav';

				if( !isset( $_REQUEST['hos_action'] ) )
					$hos_action = $this->track_result;
				else
					$hos_action = $_REQUEST['hos_action'];

				switch ( $hos_action ) {
					case $this->track_result;
						$view_content = 'four-track-result';
						$list = new Hospital_Four_Track_Result_Table( $model, $this->hos_type );
						$list->prepare_items();
						$data['list'] = $list;
						//echo '<pre>';print_r($list);echo '</pre>';exit();

						break;

					case $this->track_import;
						$view_content = 'four-track-import';

						break;

					case $this->track_list;
						$view_content = 'four-track-list';
						$list = new Hospital_Four_Track_List_Table( $model, $this->hos_type );
						$list->prepare_items();
						$data['list'] = $list;

						break;

					case $this->tel_list;
						$view_content = 'four-tel-list';
						$list = new Hospital_Four_Tel_List_Table( $model, $this->hos_type );
						$list->prepare_items();
						$data['list'] = $list;

						break;
					
					default:
						# code...
						break;
				}

				break;

			
			default:
				# code...
				break;
		}

		
		
		$data['model']      = $model;
		$data['hos_action'] = $hos_action;
		$data['title']      = $title;
		$data['hos_type']   = $this->hos_type;

		$this->load_view( 'four-track-list', $view_nav, $data );

		$this->load_view( 'four-track-list', $view_content, $data );

	}

	function get_four_track_table_view( $list ) {
		$this->load_view( 'four-track-list', 'four-track-table', $list );
		//echo '<pre>';print_r($list);echo '</pre>';
	}

	function get_four_exam_table_view( $list ) {
		$this->load_view( 'four-track-list', 'four-exam-table', $list );
		//echo '<pre>';print_r($list);echo '</pre>';
	}
	
	function hospital_exam_upload_ajax() {
		$response = array();
		$response['error'] = '';

		$plugin = new HospitalFourTrack;
		$hos_type = $_REQUEST['hos_type'];

		if( $hos_type == 1 ) {
			$allow_columns = array('no2','name_ry','datet_ry','meu_ry','cserum_ry','cplasma_ry','cbcell_ry','bloodqu_ry','brtr_ry','altr_ry','note_ry');
		}

		if( $hos_type == 2 ) {
			$allow_columns = array('no1','name_ry','datet_ry','meu_ry','cserum_ry','cplasma_ry','cbcell_ry','bloodqu_ry','brtr_ry','altr_ry','note_ry');
		}

		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');

        $file_ext = explode( '.', $_FILES['ex_import']['name'] );
        $file_ext = $file_ext[ count( $file_ext ) - 1 ];

        if( $file_ext == 'xlsx' || $file_ext == 'xls' ){
            $PHPExcel = PHPExcel_IOFactory::load( $_FILES['ex_import']['tmp_name'] );
        }else if( $file_ext == 'csv' ){
            //$PHPExcel = PHPExcel_IOFactory::load($uploaded_file);
            $objReader = PHPExcel_IOFactory::createReader('CSV')
                ->setDelimiter(',')
                ->setEnclosure('"')
                ->setSheetIndex(0);
            $PHPExcel = $objReader->load( $_FILES['ex_import']['tmp_name'] );
        }
        $sheet = $PHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        $upload_field_names = array();
        for ( $column = 'A'; $column != 'SV'; $column++ ) {//列数是以A列开始
            $value = $sheet->getCell($column.'2')->getFormattedValue();
            $value = trim($value);

            if( !in_array( $value, $allow_columns ) )
                continue;

            if( empty( $value ) )
                break;

            $upload_field_names[$column] = trim( $value );
        }
        foreach ( $allow_columns as $meta ) {
            if( !in_array( $meta, $upload_field_names ) ) {
                if( empty( $response['error'] ) ) {
                    $response['error'] = '文件未上传,缺失该列:'. $meta;
                }else {
                    $response['error'].= ','. $meta;
                }
            }
        }

        if( !empty( $response['error'] ) ){
        	echo json_encode( $response );
        	exit();
        }

        $m = 1;
        $origin_data = array();
        for ( $m = 1; $m <= $highestRow; $m++ ) { 
        	$first_value = $sheet->getCell('A'.$m)->getFormattedValue();

	        if( empty( $first_value ) )
	            break;

        	for ( $column = 'A',$n = 0; $n < count($upload_field_names); $column++,$n++ ) {
	        	$origin_data[$m][$column] = $sheet->getCell($column.$m)->getFormattedValue();
	        }
        }
        
        $replace_content = $plugin->get_origin_table_content( $origin_data );
        $response['replace_content'] = $replace_content;

        $form_infos = array();

        $j = 0;
        for ( $row = 3; $row <= $highestRow; $row++ ) {
            $first_value = $sheet->getCell('A'.$row)->getFormattedValue();
            if( empty( $first_value ) )
                break;

            foreach ( $upload_field_names as $column => $column_name ) {
                $val = $sheet->getCell($column.$row)->getFormattedValue();
                $val = trim($val);

                switch ( $column_name ) {
                    case 'datet_ry':
                    	if( !empty( $val ) )
                    		$form_infos[$j][$column_name] = date( 'Y-m-d 12:00:00', strtotime( $val ) );
                    	else
                    		$form_infos[$j][$column_name] = '';
                        
                        break;

                    case 'meu_ry':
                    	if( in_array( $val, $plugin->meu_arr ) ) 
                    		$form_infos[$j][$column_name] = array_search( $val, $plugin->meu_arr );
                    	else
                    		$form_infos[$j][$column_name] = '';

                    	break;

                    case 'brtr_ry':case 'altr_ry':
                        if( $val == '有' || $val == '1' ) 
                    		$form_infos[$j][$column_name] = 1;
                    	else
                    		$form_infos[$j][$column_name] = 0;
                        
                        break;

                    case 'bloodqu_ry':
                    	if( in_array( $val, $plugin->bloodqu_arr ) ) 
                    		$form_infos[$j][$column_name] = array_search( $val, $plugin->bloodqu_arr );
                    	else
                    		$form_infos[$j][$column_name] = '';

                    	break;

                    default:
                        $form_infos[$j][$column_name] = $val;
                        break;
                }
            }
            $j++;
        }
        //echo '<pre>';print_r($form_infos);echo '</pre>';exit();

        if( count( $form_infos ) > 0 ){
            foreach ( $form_infos as $key => $item) {
            	$item['hos_type'] = $_REQUEST['hos_type'];
            	$id = 0;
            	if( $item['hos_type'] == 1 ) {
            		$id = $plugin->hos_db->get_in_birth_id_by_no2( $item['no2'] );
            	}else{
            		$id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
            	}

            	//在基本信息中如果找不到数据,那么不对其进行操作
            	if( empty( $id ) )
            		continue;

            	if( $item['hos_type'] == 1 ) {
            		$item_id = $plugin->hos_db->get_ry_exam_id_by_no2( $item['no2'] );
            	}else{
            		$item_id = $plugin->hos_db->get_ry_exam_id_by_no1( $item['no1'] );
            	}

                if( empty( $item_id ) ) {
                    $plugin->hos_db->add_ry_exam_info( $item );
                }else{
                    $where = array( 'id' => $item_id );
                    $plugin->hos_db->update_ry_exam_info( $item, $where );
                }
            }
        }

        @unlink( $_FILES['ex_import']['tmp_name'] );

        echo json_encode( $response );
        exit();
	}

	function get_origin_table_content( $data ) {
		ob_start();
		$this->load_view( 'four-track-list', 'four-track-ajax-table', $data );
		$content = ob_get_contents();
		ob_end_clean();		

		return $content;
	}

	function hospital_four_track_upload_ajax() {
		$response = array();
		$response['error'] = '';

		$plugin = new HospitalFourTrack;
		$model = $_POST['model'];
		$import_action = $_POST['import_action'];
		$hos_type = $_POST['hos_type'];

		if( $hos_type == 1 ){
			$need_columns = array( 'no2' );
		}else{
			$need_columns = array( 'no1' );
		}
		

		switch ( $model ) {
    		case $plugin->model_y3:
    			$pre = '_3y';

    			switch ( $import_action ) {
    				case 'area_exam':
    					if( $hos_type == 1 ){
							$allow_columns = array( 'no2', 'district', 'phmeas' . $pre, 'pexam' . $pre, 'hearing' . $pre, 'vision' . $pre, 'oral' . $pre, 'btype', 'blroutine' . $pre, 'bllead' . $pre, 'hemoglobin' . $pre, 'trelements' . $pre, 'chmeigg' . $pre, 'hbeag' . $pre, 'mvigg' . $pre, 'look' . $pre, 'stradip' . $pre, 'bmd' . $pre, 'si' . $pre, 'ptq' . $pre );
						}else{
							$allow_columns = array( 'no1', 'district', 'phmeas' . $pre, 'pexam' . $pre, 'hearing' . $pre, 'vision' . $pre, 'oral' . $pre, 'btype', 'blroutine' . $pre, 'bllead' . $pre, 'hemoglobin' . $pre, 'trelements' . $pre, 'chmeigg' . $pre, 'hbeag' . $pre, 'mvigg' . $pre, 'look' . $pre, 'stradip' . $pre, 'bmd' . $pre, 'si' . $pre, 'ptq' . $pre );
						}

    					break;

    				case 'question_collect':
    					if( $hos_type == 1 ){
							$allow_columns = array( 'no2', 'plasma' . $pre, 'bcell' . $pre, 'churine' . $pre, 'chfaeces' . $pre, 'paquestion' . $pre, 'tequestion' . $pre, 'trs' . $pre, 'asq' . $pre, 'chealthhandbook' . $pre, 'vacertifi' . $pre, 'cbcl' . $pre, 'abc' . $pre, 'completerkd' . $pre, 'notekd' . $pre, 'phquestion' . $pre, 'pqudate' . $pre, 'notetel' . $pre, 'completertel' . $pre );
						}else{
							$allow_columns = array( 'no1', 'plasma' . $pre, 'bcell' . $pre, 'churine' . $pre, 'chfaeces' . $pre, 'paquestion' . $pre, 'tequestion' . $pre, 'trs' . $pre, 'asq' . $pre, 'chealthhandbook' . $pre, 'vacertifi' . $pre, 'cbcl' . $pre, 'abc' . $pre, 'completerkd' . $pre, 'notekd' . $pre, 'phquestion' . $pre, 'pqudate' . $pre, 'notetel' . $pre, 'completertel' . $pre );
						}

    					break;
    				
    				default:
    					break;
    			}
    			break;

    		case $plugin->model_y5:
    			$pre = '_5y';

    			switch ( $import_action ) {
    				case 'area_exam':
    					if( $hos_type == 1 ){
							$allow_columns = array( 'no2', 'district', 'phmeas' . $pre, 'pexam' . $pre, 'hearing' . $pre, 'vision' . $pre, 'oral' . $pre, 'btype', 'blroutine' . $pre, 'bllead' . $pre, 'hemoglobin' . $pre, 'trelements' . $pre, 'chmeigg' . $pre, 'hbeag' . $pre, 'mvigg' . $pre, 'look' . $pre, 'stradip' . $pre, 'bmd' . $pre, 'si' . $pre, 'ptq' . $pre );
						}else{
							$allow_columns = array( 'no1', 'district', 'phmeas' . $pre, 'pexam' . $pre, 'hearing' . $pre, 'vision' . $pre, 'oral' . $pre, 'btype', 'blroutine' . $pre, 'bllead' . $pre, 'hemoglobin' . $pre, 'trelements' . $pre, 'chmeigg' . $pre, 'hbeag' . $pre, 'mvigg' . $pre, 'look' . $pre, 'stradip' . $pre, 'bmd' . $pre, 'si' . $pre, 'ptq' . $pre );
						}

    					break;

    				case 'question_collect':
    					if( $hos_type == 1 ){
							$allow_columns = array( 'no2', 'plasma' . $pre, 'bcell' . $pre, 'churine' . $pre, 'chfaeces' . $pre, 'paquestion' . $pre, 'tequestion' . $pre, 'trs' . $pre, 'asq' . $pre, 'chealthhandbook' . $pre, 'vacertifi' . $pre, 'cbcl' . $pre, 'abc' . $pre, 'completerkd' . $pre, 'notekd' . $pre, 'phquestion' . $pre, 'pqudate' . $pre, 'notetel' . $pre, 'completertel' . $pre );
						}else{
							$allow_columns = array( 'no1', 'plasma' . $pre, 'bcell' . $pre, 'churine' . $pre, 'chfaeces' . $pre, 'paquestion' . $pre, 'tequestion' . $pre, 'trs' . $pre, 'asq' . $pre, 'chealthhandbook' . $pre, 'vacertifi' . $pre, 'cbcl' . $pre, 'abc' . $pre, 'completerkd' . $pre, 'notekd' . $pre, 'phquestion' . $pre, 'pqudate' . $pre, 'notetel' . $pre, 'completertel' . $pre );
						}

    					break;

    				case 'wpp_test':
    					if( $hos_type == 1 ){
							$allow_columns = array( 'no2', 'wppsidate' . $pre, 'wppsi' . $pre, 'completerwpp' . $pre, 'notewpp' . $pre );
						}else{
							$allow_columns = array( 'no1', 'wppsidate' . $pre, 'wppsi' . $pre, 'completerwpp' . $pre, 'notewpp' . $pre );
						}

    					break;
    				
    				default:
    					break;
    			}
    			break;
    		
    		default:
    			# code...
    			break;
    	}

		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');

        $file_ext = explode( '.', $_FILES['four_import']['name'] );
        $file_ext = $file_ext[ count( $file_ext ) - 1 ];

        if( $file_ext == 'xlsx' || $file_ext == 'xls' ){
            $PHPExcel = PHPExcel_IOFactory::load( $_FILES['four_import']['tmp_name'] );
        }else if( $file_ext == 'csv' ){
            //$PHPExcel = PHPExcel_IOFactory::load($uploaded_file);
            $objReader = PHPExcel_IOFactory::createReader('CSV')
                ->setDelimiter(',')
                ->setEnclosure('"')
                ->setSheetIndex(0);
            $PHPExcel = $objReader->load( $_FILES['four_import']['tmp_name'] );
        }
        $sheet = $PHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        $upload_field_names = array();
        for ( $column = 'A'; $column != 'SV'; $column++ ) {//列数是以A列开始
            $value = $sheet->getCell($column.'2')->getFormattedValue();
            $value = trim($value);

            if( !in_array( $value, $allow_columns ) )
                continue;

            if( empty( $value ) )
                break;

            $upload_field_names[$column] = trim( $value );
        }
        foreach ( $need_columns as $meta ) {
            if( !in_array( $meta, $upload_field_names ) ) {
                if( empty( $response['error'] ) ) {
                    $response['error'] = '文件未上传,缺失该列:'. $meta;
                }else {
                    $response['error'].= ','. $meta;
                }
            }
        }

        if( !empty( $response['error'] ) ){
        	echo json_encode( $response );
        	exit();
        }

        $m = 1;
        $origin_data = array();
        for ( $m = 1; $m <= $highestRow; $m++ ) { 
        	$first_value = $sheet->getCell('A'.$m)->getFormattedValue();

	        if( empty( $first_value ) )
	            break;

        	for ( $column = 'A',$n = 0; $n < count($upload_field_names); $column++,$n++ ) {
	        	$origin_data[$m][$column] = $sheet->getCell($column.$m)->getFormattedValue();
	        }
        }
        
        $replace_content = $plugin->get_origin_table_content( $origin_data );
        $response['replace_content'] = $replace_content;

        $form_infos = array();

        $j = 0;
        for ( $row = 3; $row <= $highestRow; $row++ ) {
            $first_value = $sheet->getCell('A'.$row)->getFormattedValue();
            if( empty( $first_value ) )
                break;

            foreach ( $upload_field_names as $column => $column_name ) {
                $val = $sheet->getCell($column.$row)->getFormattedValue();
                $val = trim($val);

                switch ( $column_name ) {
                	case 'district':
                    	if( in_array( $val, $plugin->meu_arr ) ) 
                    		$form_infos[$j][$column_name] = array_search( $val, $plugin->meu_arr );
                    	else
                    		$form_infos[$j][$column_name] = '';

                    	break;

                    case 'exdate' . $pre:case 'enrolldate' . $pre:case 'pqudate' . $pre:case 'wppsidate' . $pre:
                    	if( !empty( $val ) )
                    		$form_infos[$j][$column_name] = date( 'Y-m-d 12:00:00', strtotime( $val ) );
                    	else
                    		$form_infos[$j][$column_name] = '';
                        
                        break;

                    case 'notekd' . $pre:
                        if( in_array( $val, $plugin->note_local_arr ) ) 
                    		$form_infos[$j][$column_name] = array_search( $val, $plugin->note_local_arr );
                    	else
                    		$form_infos[$j][$column_name] = '';
                        
                        break;

                    case 'notetel' . $pre:
                        if( in_array( $val, $plugin->note_tel_arr ) ) 
                    		$form_infos[$j][$column_name] = array_search( $val, $plugin->note_tel_arr );
                    	else
                    		$form_infos[$j][$column_name] = '';
                        
                        break;

                    default:
                        $form_infos[$j][$column_name] = $val;
                        break;
                }
            }
            $j++;
        }


        if( count( $form_infos ) > 0 ){
            foreach ( $form_infos as $key => $item) {
            	$item['hos_type'] = $_REQUEST['hos_type'];
            	if( $hos_type == 1 ){
            		$basic_id = $plugin->hos_db->get_in_birth_id_by_no2( $item['no2'] );
            	}elseif( $hos_type == 2 ){
            		$basic_id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
            	}else{
            		continue;
            	}

            	if( empty( $basic_id ) )
            		continue;

            	if( $hos_type == 1 ){
            		$item_info = $plugin->hos_db->get_four_track_info_by_no2( $item['no2'], $model );
            	}elseif( $hos_type == 2 ){
            		$item_info = $plugin->hos_db->get_four_track_info_by_no1( $item['no1'], $model );
            	}
            	
            	$item_id = $item_info['id'];

            	$origin_status = $item_info['status' . $pre];

            	switch ( $import_action ) {
					case 'area_exam':
						if( $item['pexam' . $pre] )
							$item['status' . $pre] = 1;
						
						break;

					case 'question_collect':
						if( $origin_status == 1 || $origin_status == 9 ){

						}elseif( $item['phquestion' . $pre] > 0 ) {
							$item['status' . $pre] = 2;
						}elseif( !empty( $item['notetel_' . $pre] ) ) {
							if( $item['notetel' . $pre] == '失联' ){
								$item['status' . $pre] = 3;
							}elseif( $item['notetel' . $pre] == '拒绝' || $item['notetel' . $pre] == '退出' ) {
								$item['status' . $pre] = 4;
							}elseif( $item['notetel' . $pre] == '宝宝夭折' || $item['notetel' . $pre] == '夭折' ){
								$item['status' . $pre] = 5;
							}
						}

						break;

					case 'wpp_test':
						if( $item['wppsi' . $pre] ){
							$item['status' . $pre] = 9;
						}

						break;
					
					default:
						# code...
						break;
				}

                if( empty( $item_id ) ) {
                    $plugin->hos_db->add_four_track_info( $item, $model );
                }else{
                    $where = array( 'id' => $item_id );
                    $plugin->hos_db->update_four_track_info( $item, $where, $model );
                }
            }
        }

        @unlink( $_FILES['four_import']['tmp_name'] );

        echo json_encode( $response );
        exit();
	}

	function hospital_four_track_import_ajax() {
		$response = array();
		$response['error'] = '';

		$plugin = new HospitalFourTrack;
		$model = $_POST['model'];
		$hos_action = $_POST['hos_action'];
		$hos_type = $_POST['hos_type'];

		if( $hos_type == 1 ){
			$need_columns = array( 'no2' );
		}else{
			$need_columns = array( 'no1' );
		}

		switch ( $model ) {
    		case $plugin->model_y3:
    			$pre = '_3y';
    			# code...
    			break;

    		case $plugin->model_y5:
    			$pre = '_5y';
    			# code...
    			break;
    		
    		default:
    			# code...
    			break;
    	}

    	switch ( $hos_action ) {
    		case $plugin->track_list:
    			if( $hos_type == 1 ){
					$allow_columns = array( 'no2', 'kindergarten' . $pre, 'enrolldate' . $pre, 'class' . $pre, 'exdate' . $pre, 'district' );
				}else{
					$allow_columns = array( 'no1', 'kindergarten' . $pre, 'enrolldate' . $pre, 'class' . $pre, 'exdate' . $pre, 'district' );
				}

    			break;

    		case $plugin->tel_list:
    			if( $hos_type == 1 ){
    				$allow_columns = array( 'no2', 'pqudate' . $pre, 'phquestion' . $pre, 'notetel' . $pre );

    			}else{
    				$allow_columns = array( 'no1', 'pqudate' . $pre, 'phquestion' . $pre, 'notetel' . $pre );

    			}
    			
    			break;
    		
    		default:
    			# code...
    			break;
    	}

		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');

        $file_ext = explode( '.', $_FILES['four_list_import']['name'] );
        $file_ext = $file_ext[ count( $file_ext ) - 1 ];

        if( $file_ext == 'xlsx' || $file_ext == 'xls' ){
            $PHPExcel = PHPExcel_IOFactory::load( $_FILES['four_list_import']['tmp_name'] );
        }else if( $file_ext == 'csv' ){
            //$PHPExcel = PHPExcel_IOFactory::load($uploaded_file);
            $objReader = PHPExcel_IOFactory::createReader('CSV')
                ->setDelimiter(',')
                ->setEnclosure('"')
                ->setSheetIndex(0);
            $PHPExcel = $objReader->load( $_FILES['four_list_import']['tmp_name'] );
        }
        $sheet = $PHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        $upload_field_names = array();
        for ( $column = 'A'; $column != 'SV'; $column++ ) {//列数是以A列开始
            $value = $sheet->getCell($column.'2')->getFormattedValue();
            $value = trim($value);

            if( !in_array( $value, $allow_columns ) )
                continue;

            if( empty( $value ) )
                break;

            $upload_field_names[$column] = trim( $value );
        }
        foreach ( $need_columns as $meta ) {
            if( !in_array( $meta, $upload_field_names ) ) {
                if( empty( $response['error'] ) ) {
                    $response['error'] = '文件未上传,缺失该列:'. $meta;
                }else {
                    $response['error'].= ','. $meta;
                }
            }
        }

        if( !empty( $response['error'] ) ){
        	echo json_encode( $response );
        	exit();
        }

        $m = 1;
        $origin_data = array();
        for ( $m = 1; $m <= $highestRow; $m++ ) { 
        	$first_value = $sheet->getCell('A'.$m)->getFormattedValue();

	        if( empty( $first_value ) )
	            break;

        	for ( $column = 'A',$n = 0; $n < count($upload_field_names); $column++,$n++ ) {
	        	$origin_data[$m][$column] = $sheet->getCell($column.$m)->getFormattedValue();
	        }
        }

        $form_infos = array();
        //echo '<pre>';print_r($upload_field_names);echo '</pre>';exit();
        $j = 0;
        for ( $row = 3; $row <= $highestRow; $row++ ) {
            $first_value = $sheet->getCell('A'.$row)->getFormattedValue();
            if( empty( $first_value ) )
                break;

            foreach ( $upload_field_names as $column => $column_name ) {
                $val = $sheet->getCell($column.$row)->getFormattedValue();
                $val = trim($val);

                switch ( $column_name ) {
                	case 'district':
                    	if( in_array( $val, $plugin->meu_arr ) ) 
                    		$form_infos[$j][$column_name] = array_search( $val, $plugin->meu_arr );
                    	else
                    		$form_infos[$j][$column_name] = '';

                    	break;

                    case 'enrolldate' . $pre:case 'exdate' . $pre:case 'pqudate' . $pre:
                    	if( !empty( $val ) )
                    		$form_infos[$j][$column_name] = date( 'Y-m-d 12:00:00', strtotime( $val ) );
                    	else
                    		$form_infos[$j][$column_name] = '';
                        
                        break;

                    case 'phquestion' . $pre:
                    	if( $val == 1 || $val == '是' )
                    		$form_infos[$j][$column_name] = 1;
                    	else
                    		$form_infos[$j][$column_name] = 0;

                    	break;

                    case 'notetel' . $pre:
                        if( in_array( $val, $plugin->note_tel_arr ) ) 
                    		$form_infos[$j][$column_name] = array_search( $val, $plugin->note_tel_arr );
                    	else
                    		$form_infos[$j][$column_name] = '';
                        
                        break;

                    default:
                        $form_infos[$j][$column_name] = $val;
                        break;
                }
            }
            $j++;
        }
        //echo '<pre>';print_r($form_infos);echo '</pre>';exit();

        if( count( $form_infos ) > 0 ){
            foreach ( $form_infos as $key => $item) {
            	$item['hos_type'] = $_REQUEST['hos_type'];

            	if( $item['hos_type'] == 1 ) {
            		$item_id = $plugin->hos_db->get_in_birth_id_by_no2( $item['no2'] );
            	}else{
            		$item_id = $plugin->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
            	}

            	if( is_null( $item_id ) )
            		continue;
             
                if( $item['hos_type'] == 1 ) {
            		$track_info = $plugin->hos_db->get_four_track_info_by_no2( $item['no2'], $model );
            	}else{
            		$track_info = $plugin->hos_db->get_four_track_info_by_no1( $item['no1'], $model );
            	}

                $where = array( 'id' => $track_info['id'] );

                if( $hos_action == 'tel_list' && $track_info['status'.$pre] != 1 ) {
                	if( $item['phquestion'.$pre] )
                		$item['status'.$pre] = 2;
                	else
                		$item['status'.$pre] = $item['notetel'.$pre]; 
                }

                if( empty( $track_info ) ) {
                	$plugin->hos_db->add_four_track_info( $item, $model );
                }else{
                	$plugin->hos_db->update_four_track_info( $item, $where, $model );
                }

            }
        }

        @unlink( $_FILES['four_list_import']['tmp_name'] );

        echo json_encode( $response );
        exit();
	}

	function get_four_list_content_view( $list ) {
		//echo '<pre>';print_r($list);echo '</pre>';return;
		$list->display();
	}


	function hospital_exam_search_ajax() {
		$response = array();

		$hos_action = $_POST['hos_action'];

		$data['key_word'] = esc_attr( $_POST['key_word'] );

		$data['from_key'] = $_POST['from_key'];
		$data['hos_type'] = $_POST['hos_type'];

		foreach ( $_POST['form_data'] as $item ) {
			$data[$item['name']] = $item['value'];
		}

		$plugin = new HospitalFourTrack;
		$exam = new Hospital_Exam_List_Table( $data );

		$exam->prepare_items();

		ob_start();
		$plugin->get_table_nav_top_view( $exam );
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_nav_top'] = $content;

		ob_start();
		$plugin->get_four_exam_table_view( $exam );
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_content'] = $content;

		ob_start();
		$plugin->get_table_nav_bottom_view( $exam );
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_nav_bottom'] = $content;

		$response['total'] = $exam->total;

		echo json_encode( $response );
		exit();
	}

	function hospital_track_four_ajax() {
		$response = array();
		$plugin = new HospitalFourTrack;

		$hos_action = $_POST['hos_action'];
		$hos_type = $_POST['hos_type'];
		$model = $_POST['model'];

		switch ( $hos_action ) {
			case $plugin->track_result:
				$track = new Hospital_Four_Track_Result_Table( $model, $hos_type );

				break;

			case $plugin->track_list:
				$track = new Hospital_Four_Track_List_Table( $model, $hos_type );

				break;

			case $plugin->tel_list:
				$track = new Hospital_Four_Tel_List_Table( $model, $hos_type );

				break;
			
			default:
				# code...
				break;
		}

		$track->prepare_items();

		ob_start();
		$plugin->get_table_nav_top_view( $track );
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_nav_top'] = $content;

		ob_start();
		switch ( $hos_action ) {
			case $plugin->track_result:
				$plugin->get_four_track_table_view( $track );

				break;

			case $plugin->track_list:case $plugin->tel_list:
				$track->display();

				break;
			
			default:
				# code...
				break;
		}
		//$plugin->get_in_birth_track_table_view($track);
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_content'] = $content;

		ob_start();
		$plugin->get_table_nav_bottom_view( $track );
		$content = ob_get_contents();
		ob_end_clean();

		$response['hos_table_nav_bottom'] = $content;

		$response['total'] = $track->total;

		echo json_encode( $response );
		exit();
	}

	function hospital_four_track_edit_ajax() {
		$plugin = new HospitalFourTrack;

		$hos_action = $_POST['hos_action'];
		$hos_type = $_POST['hos_type'];
		$model = $_POST['model'];
		
		$item = $plugin->get_track_edit_table( $model, $hos_type, $hos_action );
		$response['title'] = $item['title'];
		$response['content'] = $item['html'];

		echo json_encode( $response );
		exit();
	}

	function get_track_edit_table( $model, $hos_type, $hos_action ) {
		switch ( $model ) {
			case $this->model_y3:
				$pre = '_3y';
				break;

			case $this->model_y5:
				$pre = '_5y';
				break;
			
			default:
				# code...
				break;
		}

		if( $hos_type == 1 ){
			$type_name = '出生队列';
		}else{
			$type_name = '产前队列';
		}

		switch ( $hos_action ) {
			case $this->track_result:
				$content_view = 'edit-four-track-result-form';
				switch ( $model ) {
					case $this->model_y3:
						$title = $type_name . '——3-4岁随访状态';
						break;

					case $this->model_y5:
						$title = $type_name . '——5-6岁随访状态';
						break;
					
					default:
						# code...
						break;
				}

				break;

			case $this->track_list:
				$content_view = 'edit-four-track-list-form';
				switch ( $model ) {
					case $this->model_y3:
						$title = $type_name . '——3-4岁现场随访名单';
						break;

					case $this->model_y5:
						$title = $type_name . '——5-6岁现场随访名单';
						break;
					
					default:
						# code...
						break;
				}

				break;

			// case $this->tel_list:
			// 	$content_view = 'in-edit-four-tel-list-form';
			// 	switch ( $model ) {
			// 		case $this->model_y3:
			// 			$title = $type_name . '——3-4岁电话随访名单';
			// 			break;

			// 		case $this->model_y5:
			// 			$title = $type_name . '——5-6岁电话随访名单';
			// 			break;
					
			// 		default:
			// 			# code...
			// 			break;
			// 	}

			// 	break;
			
			default:
				# code...
				break;
		}

		$item_id = $_POST['id'];
		$no2 = $_POST['no2'];
		$no1 = $_POST['no1'];

		if( !empty( $item_id ) ) {
			$item = $this->hos_db->get_four_track_info_by_info_id( $item_id, $model, $hos_type );
		}else{
			if( $hos_type == 1 ){
				$item = $this->hos_db->get_in_birth_info_by_no2( $no2 );
			}else{
				$item = $this->hos_db->get_pre_birth_info_by_no1( $no1 );
			}
			//else for 2
		}

		if( empty( $item['completerlocal'.$pre] ) ) {
			$item['completerlocal'.$pre] = $this->user_name;
		}

		if( empty( $item['completertel'.$pre] ) ) {
			$item['completertel'.$pre] = $this->user_name;
		}

		if( empty( $item['completerkd'.$pre] ) ) {
			$item['completerkd'.$pre] = $this->user_name;
		}

		if( empty( $item['completerwpp'.$pre] ) ) {
			$item['completerwpp'.$pre] = $this->user_name;
		}

		$data['model']    = $model;
		$data['hos_type'] = $hos_type;
		$data['item']     = $item;
		$data['pre']      = $pre;
		$data['item_id']  = $item_id;
		$data['no2']  	  = $no2;

		ob_start();
		$this->load_view( 'four-track-list', $content_view, $data );
		$content = ob_get_contents();
		ob_end_clean();

		$item['html'] = $content;
		$item['title'] = $title;

		return $item;
	}

	function four_track_export() {
    	foreach ($_REQUEST as $key => $value) {
    		$_REQUEST[$key] = esc_attr( $value );
    	}

		$response = array();

		$model = $_REQUEST['model'];
		$hos_action = $_REQUEST['hos_action'];
		$hos_type = $_REQUEST['hos_type'];
		$file_foot = '';

		if( $hos_type == 1 ){
			$type_name = '出生队列';
		}else{
			$type_name = '产前队列';
		}

		switch ( $model ) {
			case $this->model_y3:
				$pre = '_3y';
				$pre_title = '3-4岁';

				break;

			case $this->model_y5:
				$pre = '_5y';
				$pre_title = '5-6岁';

				break;

			case $this->model_ex:
				$pre = '_ry';

				break;
			
			default:
				# code...
				break;
		}

		switch ( $hos_action ) {
			case $this->ex_stat:
				$data['hos_exam_status']     = $_REQUEST['hos_exam_status'];
				$data['hos_exam_meu']        = $_REQUEST['hos_exam_meu'];
				$data['hos_exam_blood_type'] = $_REQUEST['hos_exam_blood_type'];
				$data['hos_exam_bloodqu']    = $_REQUEST['hos_exam_bloodqu'];
				$data['hos_exam_brtr']       = $_REQUEST['hos_exam_brtr'];
				$data['hos_exam_altr']       = $_REQUEST['hos_exam_altr'];
				$data['hos_type']       	 = $_REQUEST['hos_type'];

				$list = new Hospital_Exam_List_Table( $data );
				$filename = $type_name . '入园体检';
				$file_foot = '武汉' . $type_name . '入园体检';

				break;

			case $this->track_result:
				$list = new Hospital_Four_Track_Result_Table( $model, $hos_type );
				$filename = '出生队列' . $pre_title . '随访结果';
				$file_foot = '武汉' . $type_name . $pre_title . '随访结果';

				break;

			case $this->track_list:
				$list = new Hospital_Four_Track_List_Table( $model, $hos_type );
				$year = (int)(date('Y')) + 1;
				if( $_REQUEST['extend_type'] == 2 ){
					$ex_title = $year . '年在园体检的待定儿童名单';
				}else{
					$ex_title = $year . '年待在园体检的儿童名单';
				}
				$filename = $type_name . $pre_title . $ex_title;
				$file_foot = '武汉' . $type_name . $pre_title . $ex_title;

				break;

			case $this->tel_list:
				$list = new Hospital_Four_Tel_List_Table( $model, $hos_type );
				$filename = $type_name . $pre_title . '电话随访名单';
				$file_foot = '武汉' . $type_name . $pre_title . '电话随访名单';

				break;
			
			default:
				# code...
				break;
		}
		$list->prepare_items();
		unset( $list->column_header_define['editor'] );
		unset( $list->column_header_define['operate'] );
		//echo '<pre>';print_r($list);echo '</pre>';exit();

		$new_items = array();
		if( count( $list->total_items ) > 0 ){
			foreach ( $list->total_items as $key1 => $item ) {
				foreach ( $list->column_header_define as $key2 => $value ) {
					if( in_array( $key2, array( 'kindergarten', 'class' ) ) ) {
						$new_items[$key1][$key2] = $item[$key2.$pre];

						continue;
					}

					if( $key2 == 'district' || $key2 == 'meu_ry' ) {
						$new_items[$key1][$key2] = $this->meu_arr[ $item[$key2] ];
						continue;
					}

					if( $key2 == 'dedate' || $key2 == 'datet_ry' ) {
						$new_items[$key1][$key2] = $this->translate_date( $item[$key2] );

						continue;
					}

					if( $key2 == 'exdate' || $key2 == 'enrolldate' || $key2 == 'pqudate' || $key2 == 'wppsidate' ) {
						$new_items[$key1][$key2] = $this->translate_date( $item[$key2.$pre] );

						continue;
					}

					if( $key2 == 'cage' ) {
						$new_items[$key1][$key2] = $list->column_cage( $item );

						continue;
					}

					if( $key2 == 'cage_ry' ) {
						$new_items[$key1][$key2] = $list->column_cage_ry( $item );

						continue;
					}

					if( $key2 == 'phquestion' ) {
						$new_items[$key1][$key2] = ( $item[$key2.$pre] == 1 ) ? 1 : 0;

						continue;
					}

					if( $key2 == 'notetel' ) {
						$new_items[$key1][$key2] = $this->note_tel_arr[ $item[$key2.$pre] ];

						continue;
					}

					if( $key2 == 'notekd' ) {
						$new_items[$key1][$key2] = $this->note_local_arr[ $item[$key2.$pre] ];

						continue;
					}

					if( $key2 == 'bname' || $key2 == 'name' || $key2 == 'hname' || $key2 == 'no2' || $key2 == 'pphone' || $key2 == 'hphone' || $key2 == 'dephone' || $key2 == 'name_ry' || $key2 == 'cname' || $key2 == 'no1' ){
						$new_items[$key1][$key2] = $item[$key2];

						continue;
					}

					if( $key2 == 'completerkd' || $key2 == 'completertel' || $key2 == 'completerwpp' || $key2 == 'notewpp' ){
						$new_items[$key1][$key2] = $item[$key2.$pre];

						continue;
					}

					if( $key2 == 'status' ) {
						$status_div = $list->get_track_status( $item );
						$status = array_search( $status_div, $this->track_status_arr );
						$new_items[$key1][$key2] = $this->track_status_content[ $status ];

						continue;
					}

					if( $key2 == 'hos_exam_status' ) {
						$new_items[$key1][$key2] = ( $item['meu_ry'] > 0 ) ? '是' : '否';

						continue;
					}

					if( $key2 == 'cserum_ry' || $key2 == 'cplasma_ry' || $key2 == 'cbcell_ry' ) {
						if( $item[ $key2 ] % 1 == $item[ $key2 ] ){
			                $new_items[$key1][$key2] = (int)$item[ $key2 ];
			            }else{
			                $new_items[$key1][$key2] = $item[ $key2 ];
			            }

						continue;
					}

					if( $key2 == 'bloodqu_ry' ) {
						$new_items[$key1][$key2] = $this->bloodqu_arr[ $item[ $key2 ] ];

						continue;
					}

					if( $key2 == 'brtr_ry' || $key2 == 'altr_ry' || $key2 == 'note_ry' ) {
						$new_items[$key1][$key2] = $item[$key2];

						continue;
					}

					if( !empty( $item[$key2.$pre] ) )
						$new_items[$key1][$key2] = $item[$key2.$pre];
					else
						$new_items[$key1][$key2] = 0;
				}
			}
		}
		// echo '<pre>';print_r($list->column_header_define);echo '</pre>';
		// echo '<pre>';print_r($new_items);echo '</pre>';
		// exit();
		ob_end_clean();

		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');
		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel/IOFactory.php');

		$PHPExcel = new PHPExcel();
		$PHPExcel->setActiveSheetIndex(0);
		$PHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
		$PHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter( '&L&B' . $file_foot . '&R第 &P 页 共 &N 页' );

		$tpo = 'A';
		foreach ( $list->column_header_define as $key => $title ) {
			$PHPExcel->getActiveSheet()->setCellValue($tpo.'1', $title );
			$PHPExcel->getActiveSheet()->getColumnDimension($tpo)->setAutoSize(true);
			$tpo++;
		}

		$ppo = 'A';
		foreach ( $list->column_parameters as $key => $title ) {
			$PHPExcel->getActiveSheet()->setCellValue($ppo.'2', $title );
			$PHPExcel->getActiveSheet()->getColumnDimension($ppo)->setAutoSize(true);
			$ppo++;
		}

	    $m=3;
	    foreach ( $new_items as $item ) {
	    	$n='A';
	    	foreach ( $item as $key => $value ) {
	    		$PHPExcel->getActiveSheet()->setCellValue($n.$m, $value );
	    		$n++;
	    	}
	    	$m++;
	    }

		header('Content-Type: application/download;charset=utf-8'); 
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"'); 
		header('Cache-Control: max-age=0'); 
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
		$objWriter->save('php://output');

		exit();
	}

	function hospital_four_track_edit_save_ajax() {
		//echo '<pre>';print_r($_REQUEST);echo '</pre>';exit();

		$plugin     = new HospitalFourTrack;
		$hos_action = $_POST['hos_action'];
		$hos_type   = $_POST['hos_type'];
		$model      = $_POST['model'];

		switch ( $model ) {
			case $plugin->model_y3:
				$pre = '_3y';
				break;

			case $plugin->model_y5:
				$pre = '_5y';
				break;
			
			default:
				# code...
				break;
		}

		foreach ( $_POST['form_data'] as $item) {
			$data[$item['name']] = $item['value'];
		}

		switch ( $hos_action ) {
			case $plugin->track_list:
				$track_data = array();
				$track_data['hos_type'] = $hos_type;

				if( $hos_type == 1 ){
					$track_data['no2'] = $data['no2'];
				}else{
					$track_data['no1'] = $data['no1'];
				}

				$track_data['district'] = $data['district'];
				switch ( $model ) {
					case $plugin->model_y3:
						$data_arr = array( 'enrolldate', 'exdate', 'kindergarten', 'class', 'completerkd' );

						break;

					case $plugin->model_y5:
						$data_arr = array( 'enrolldate', 'exdate', 'kindergarten', 'class', 'completerkd' );

						break;
					
					default:
						# code...
						break;
				}

				foreach ($data_arr as $val) {
					$track_data[$val.$pre] = $data[$val];
				}

				if( $hos_type == 1 ){
					$item_info = $plugin->hos_db->get_four_track_info_by_no2( $data['no2'], $model );
				}else{
					$item_info = $plugin->hos_db->get_four_track_info_by_no1( $data['no1'], $model );
				}

				if( !empty( $item_info ) ) {
					$where = array( 'id' => $item_info['id'] );
					$plugin->hos_db->update_four_track_info( $track_data, $where, $model );
					
				}else{
					$plugin->hos_db->add_four_track_info( $track_data, $model );
				}

				if( $hos_type == 1 ){
					echo $data['no2'];
				}else{
					echo $data['no1'];
				}
				
				exit();
				
				break;

			case $plugin->track_result:
				$track_data = array();
				$track_data['hos_type'] = $hos_type;

				$track_data['district'] = $data['district'];

				switch ( $model ) {
					case $plugin->model_y3:
						$pre = '_3y';
						$data_arr = array(
							'kindergarten', 'enrolldate', 'class', 'exdate', 'phmeas', 'pexam', 'vision', 'hearing', 'oral', 'btype', 'blroutine', 'bllead', 'hemoglobin', 'trelements', 'chmeigg', 'hbeag', 'mvigg', 'look', 'stradip', 'bmd', 'si', 'ptq', 'plasma', 'bcell', 'churine', 'chfaeces', 'paquestion', 'tequestion', 'trs', 'asq', 'chealthhandbook', 'vacertifi', 'cbcl', 'abc', 'completerkd', 'notekd', 'phquestion', 'pqudate', 'notetel', 'completertel'
							);

						break;

					case $plugin->model_y5:
						$pre = '_5y';
						$data_arr = array(
							'kindergarten', 'enrolldate', 'class', 'exdate', 'phmeas', 'pexam', 'vision', 'hearing', 'oral', 'btype', 'blroutine', 'bllead', 'hemoglobin', 'trelements', 'chmeigg', 'hbeag', 'mvigg', 'look', 'stradip', 'bmd', 'si', 'ptq', 'plasma', 'bcell', 'churine', 'chfaeces', 'paquestion', 'tequestion', 'trs', 'asq', 'chealthhandbook', 'vacertifi', 'cbcl', 'abc', 'completerkd', 'notekd', 'phquestion', 'pqudate', 'notetel', 'completertel','wppsi','completerwpp','notewpp', 'wppsidate');

						break;
					
					default:
						# code...
						break;
				}

				foreach ($data_arr as $val) {
					$track_data[$val.$pre] = $data[$val];
				}

				if( $hos_type == 1 ){
					$track_data['no2'] = $data['no2'];
				}else{
					$track_data['no1'] = $data['no1'];
				}
				
				$status = 0;

				if( $data['wppsi'] ) {
					$status = 9;
				}elseif( $data['pexam'] ) {
					$status = 1;
				}elseif( $data['phquestion'] ) {
					$status = 2;
				}elseif( $data['notetel'] > 0 ) {
					$status = $data['notetel'];
				}

				$track_data['status'.$pre] = $status;

				if( $hos_type == 1 ){
					$item_info = $plugin->hos_db->get_four_track_info_by_no2( $data['no2'], $model );
				}else{
					$item_info = $plugin->hos_db->get_four_track_info_by_no1( $data['no1'], $model );
				}
				

				if( !empty( $item_info ) ) {
					$where = array( 'id' => $item_info['id'] );
					$plugin->hos_db->update_four_track_info( $track_data, $where, $model );
				}else{
					$plugin->hos_db->add_four_track_info( $track_data, $model );
				}

				if( $hos_type == 1 ){
					echo $data['no2'];
				}else{
					echo $data['no1'];
				}
				
				exit();
				
				break;
			
			default:
				# code...
				break;
		}
	}
}

add_action( 'wp_ajax_hospital-exam-upload-ajax', array( 'HospitalFourTrack','hospital_exam_upload_ajax' ) );
add_action( 'wp_ajax_hospital-exam-search-ajax', array( 'HospitalFourTrack','hospital_exam_search_ajax' ) );
add_action( 'wp_ajax_hospital-four-track-upload-ajax', array( 'HospitalFourTrack','hospital_four_track_upload_ajax' ) );
add_action( 'wp_ajax_hospital-track-four-ajax', array( 'HospitalFourTrack','hospital_track_four_ajax' ) );
add_action( 'wp_ajax_hospital-four-track-edit-ajax', array( 'HospitalFourTrack','hospital_four_track_edit_ajax' ) );
add_action( 'wp_ajax_hospital-four-track-import-ajax', array( 'HospitalFourTrack','hospital_four_track_import_ajax' ) );
add_action( 'wp_ajax_hospital-four-track-edit-save-ajax', array( 'HospitalFourTrack','hospital_four_track_edit_save_ajax' ) );

?>