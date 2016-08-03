<?php   

  
class HospitalTrack {
	//为简化命名.region 0对应25天,1对应5个半月,2对应11个半月,3对应23个半月,4对应45天,5对应7个月,6对应13个月,7对应25个月
	public $step1 = FALSE;
	public $step2 = FALSE;
	public $step3 = FALSE;
	public $error = '';
	public $filename = '';
	public $track_info_filename = '';
	public $delimiter = ',';
	public $mapped = array();
	public $form_fields = array();
	public $form_id = 0;
	public $table_no = 0;
	public $is_mobile = false;

	public $ent1_form_id = 0;
	public $non_ent1_form_id = 0;
	public $blood_form_id = 0;
	public $item_infos = array();
	static $check_type = 4;
    static $check_count1 = 5;
    static $check_count2 = 7;

	function __construct(){
		$this->filename = dirname(__FILE__).'/myfile.csv';
		$this->track_info_filename = dirname(__FILE__).'/track_info.xlsx';
		// if( !is_plugin_active('ninja-forms/ninja-forms.php') ){
		// 	$this->error = __('plugin error,please contact stacktech');
		// }NF_Upgrade_Submissions
		if( !class_exists('NF_Upgrade_Submissions') ){
			$this->error = __('插件报错,请联系万锦新科公司.');
		}

		$this->ent1_form_id = get_option( 'ent1_form_id' );
		$this->non_ent1_form_id = get_option( 'non_ent1_form_id' );
		$this->blood_form_id = get_option( 'blood_form_id' );
		if( 'ent1_import' == $_GET['page'] ){
			$this->form_id = $this->ent1_form_id;
		}
			
		if( 'nonent1_import' == $_GET['page'] ){
			$this->form_id = $this->non_ent1_form_id;
		}

		if( 'three_blood_import' == $_GET['page'] ){
			$this->form_id = $this->blood_form_id;
		}
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	        spl_autoload_register( 'HospitalTrack::autoloadClass', true, true);
	    } else {
	        spl_autoload_register( 'HospitalTrack::autoloadClass' );
	    }

	    if( wp_is_mobile() )
	    	$this->is_mobile = true;

	    $this->item_infos[0][0]['title'] = __('问卷');
		$this->item_infos[0][1]['title'] = __('体格检查');
		$this->item_infos[0][2]['title'] = __('母乳');
		$this->item_infos[0][3]['title'] = __('黄疸指数');
		$this->item_infos[0][4]['title'] = __('粪便');
		//$this->item_infos[0][5]['title'] = __('30天随访调查备注');
		$this->item_infos[0]['count'] = self::$check_count1;

		$this->item_infos[1][0]['title'] = __('问卷');
		$this->item_infos[1][1]['title'] = __('体格检查');
		$this->item_infos[1][2]['title'] = __('母乳');
		$this->item_infos[1][3]['title'] = __('贝利量表');
		$this->item_infos[1][4]['title'] = __('粪便');
		//$this->item_infos[1][5]['title'] = __('6个月随访调查备注');
		$this->item_infos[1]['count'] = self::$check_count1;

		$this->item_infos[2][0]['title'] = __('问卷');
		$this->item_infos[2][1]['title'] = __('体格检查');
		$this->item_infos[2][2]['title'] = __('母乳');
		$this->item_infos[2][3]['title'] = __('贝利量表');
		$this->item_infos[2][4]['title'] = __('气质量表');
		$this->item_infos[2][5]['title'] = __('血铅');
		$this->item_infos[2][6]['title'] = __('粪便');
		//$this->item_infos[2][7]['title'] = __('1年随访调查备注');
		$this->item_infos[2]['count'] = self::$check_count2;

		$this->item_infos[3][0]['title'] = __('问卷');
		$this->item_infos[3][1]['title'] = __('体格检查');
		$this->item_infos[3][2]['title'] = __('贝利量表');
		$this->item_infos[3][3]['title'] = __('气质量表');
		$this->item_infos[3][4]['title'] = __('粪便');
		//$this->item_infos[3][5]['title'] = __('2年随访调查备注');
		$this->item_infos[3]['count'] = self::$check_count1;
	}

	function autoloadClass($classname){
        $filename = plugin_dir_path( __FILE__ ).'class/class_'.strtolower($classname).'.php';
	    if (is_readable($filename)) {
	        require_once $filename;
	    }
    }

    public function __get($name){ 
		return $this->$name; 
	}

	function admin_init(){
		if( !isset($_GET['page']) ){
			wp_redirect( admin_url() );
			return;
		}

		if($this->form_id){
			$this->import();
		}else{
			wp_redirect( admin_url() );
			return;
		}
	}

	//上传文件的界面和处理数据
	function import(){
		$this->form_fields = $this->get_form_info($this->form_id);
		//echo '<pre>';print_r($this->form_fields);echo '</pre>';return;
		$this->HandlePages();
		if($this->step1){ 
?>
		<div class="wrap">
			<h2>
				上传文件
			</h2><br/>
			<?php if($this->error !== '') :  ?>
			 <div class="error">
			    <?php echo $this->error; ?>
			 </div>
			 <?php endif; ?>
			<form class="add:the-list: validate" method="post" enctype="multipart/form-data">
			<input name="_csv_import_files_next" type="hidden" value="next" />

			<!-- File input -->
			<p>
				<label for="csv_import">
					选择文件:
				</label><br/>
				<input name="csv_import" id="csv_import" type="file" value=""/>
			</p>
			
			<p class="submit">
				<input type="submit" class="button" name="submit" value="下一步" />
			</p>
		 </form>
		</div>
		<?php }elseif($this->step2){ ?>
		<div class="wrap">
			<h2>
				<?php _e('上传文件');?>
			</h2>
			<?php if($this->error !== ''){ ?>
			 <div class="error">
			    <?php echo $this->error; ?>
			 </div>
			 <?php } ?>
			<h3><?php _e('第二步 对齐列表');?></h3><br />
			<?php 
			$c = 0;
		        $e = '';
		        $l = 9999999;
		        $d = $this->delimiter;

	 			ini_set("auto_detect_line_endings", true);
				$res = $this->fopen_utf8($this->filename);
				if($res == 0) return;
				$headers = array();
				$rows = array();
		        while ($keys = fgetcsv($res, $l, $d)) {
						$str = implode("",$keys);
						$str = trim($str);
						if( mb_strlen($str) === 0 )
							continue;
						// if($c == 6)
						// 	break;
						if ($c == 0){
		                	$headers = $keys;
		           		} else {				  
		                	array_push($rows, $keys);
		                }
		            $c++;
		        }
		        fclose($res);
				ini_set("auto_detect_line_endings", false);
			?>
			<form class="add:the-list: validate" method="post" enctype="multipart/form-data">
			<input name="_csv_import_files_next1" type="hidden" value="next2" />
			<input name="_csv_import_files_name" type="hidden" value="<?php echo basename($this->filename); ?>" />
			<!-- Type -->
			<p>
				<div id="formatdiv" class="postbox" style="max-width:600px;">
					<h3 class="hndle" style="cursor:auto;padding:10px;">
						<span>
							<?php _e('对齐上传文件列与数据库列');?>
						</span>
					</h3>
					<div class="inside">
						<div style="background-color:#FFFFE0;border: 1px solid #E6DB55;padding:10px;"><b><?php _e('注意:');?></b><?php _e('左边导入的数据格式与右边数据库格式一致');?>
						</div>
						<div id="post-formats-select">
						<?php 
						$number_of_fields = count($headers);
						for ($i=0; $i < $number_of_fields; $i++){
							$string = $headers[$i];
							 
							if(strlen($string)>30){
							 	$string = mb_substr($string,0,30);
							}
						?>
							<p><div style="width:250px;float:left;"><b><?php echo $string;?></b></div>
							<select name="field<?php echo $i;?>">
							<?php 
								$fields_count = count($this->form_fields);
								foreach ($this->form_fields as $key => $form_field) {
									if( $key+1 == $fields_count )
										break;
									if( $key == 0 ){
										$option = __('选择数据列');
										$field_value = 0;
									}else{
										$option = $form_field['data']['label'];
										$field_value = $form_field['id'];
									}
									$is_check = ( $i+1 == $key ) ? 1:0;
							?>
								<option value="<?php echo $field_value;?>" <?php selected($is_check,1);?>><?php echo $option;?></option>
							<?php }?>
							</select></p>
						<?php } ?>
						<input type="hidden" name="number_of_fields" value="<?php echo $number_of_fields;?>" />
						</div>
					</div>
				</div>
			</p>
			<div style="background-color:#FFFFE0;border: 1px solid #E6DB55;padding:10px;"><?php _e('点击下一步以后,需要一定时间导入数据,请耐心等候.');?></div>
			<p class="submit">
				<input type="submit" class="button" name="submitback" value="返回" />&nbsp;&nbsp;&nbsp;
				<input type="submit" class="button" name="submit" value="下一步 >" />
			</p>
			<p><?php _e('数据预览只显示前30个字符');?></p>
			<div style="overflow:auto;/*width:800px;*/">
			<?php 
				$c = 0;
				echo '<table class="widefat" >';
				$number_of_fields = 0;
				$number_of_fields = count($headers);
				echo '<thead>
					    <tr>';
				for ($i=0; $i < $number_of_fields; $i++)
		        {
					 $string = $headers[$i];
					 
					 if(mb_strlen($string)>30)
					 {
					 	$string = substr($string,0,30);
					 }
					 $string = str_replace(" ","&nbsp;",$string);
					 echo "<th><b>".$string."</b></th>";
		        }
					echo '</tr>
						</thead>';
					echo '<tbody>';
					foreach($rows as $row)
					{
			    		$number_of_fields = count($row);
						echo '<tr>';
						
				        for ($i=0; $i < $number_of_fields; $i++)
				        {
							 $string = $row[$i];
							 
							 if(strlen($string)>30)
							 {
							 	$string = substr($string,0,30);
							 }
							 $string = str_replace(" ","&nbsp;",$string);
							 echo "<td>".$string."</td>";
				        }
						 
						echo '</tr>';
						$c++;
					}
					echo '</tbody></table>';
			
			?>
			</div>
			<p class="submit">
				<input type="submit" class="button" name="submitback" value="返回" />&nbsp;&nbsp;&nbsp;
				<input type="submit" class="button" name="submit" value="下一步 >" />
			</p>
			</form>
		</div>
		<?php }else{ ?>
			<div class="wrap">
			<h1>报告</h1>
			<br/>
			<?php 
			$time_start = microtime(true);
			$tz = get_option('timezone_string');
			if ($tz && function_exists('date_default_timezone_set')) {
			    date_default_timezone_set($tz);
			}
			$c = 0;
		    $d = $this->delimiter;
		    $l = 999999;
			$skipped = 0;
		    $imported = 0;
			ini_set("auto_detect_line_endings", true);
			$res = $this->fopen_utf8($this->filename);
			if($res == 0) 
				return;

			if( $this->form_id !== $this->blood_form_id){
				$min_date = 0;
				$max_date = 0;
				$min_post_id = 0;
				$max_post_id = 0;
				$table_arg = array(
					'post_title' => 'hospital_table',
					'post_type' => 'hos_tab',
					'post_status' => 'publish'
					);
				$this->table_no = wp_insert_post($table_arg);
			}

		    while ($keys = fgetcsv($res, $l, $d)) {
				if($c==0){
					$str = implode("",$keys);
					trim($str);
					if(mb_strlen($str) === 0)
				       continue;
				}else{
		        	$number_of_fields = count($keys);
					$data = array();
					foreach($this->mapped as $item => $value )
					{
						$data[$value] = $keys[$item];
					}
				
					global $wpdb;
					// $plugin = new HospitalTrack;
					if( $this->form_id == $this->blood_form_id){
						$field_ent1_id = $this->get_field_id_by_name('入口1',$this->ent1_form_id);
						$field_meta_key = '_field_'.$field_ent1_id;
						$field_entr_id = $this->get_field_id_by_name('入口１',$this->blood_form_id);
						$user_pid = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}postmeta where meta_value =%s and meta_key =%s",$data[$field_entr_id],$field_meta_key));
						if( $user_pid ){
							$field_t1_id = $this->get_field_id_by_name('T1',$this->blood_form_id);
								update_post_meta($user_pid,'SerumT1',$data[$field_t1_id]);
		
							$field_t2_id = $this->get_field_id_by_name('T2',$this->blood_form_id);
								update_post_meta($user_pid,'SerumT2',$data[$field_t2_id]);
					
							$field_t3_id = $this->get_field_id_by_name('T3',$this->blood_form_id);
								update_post_meta($user_pid,'SerumT3',$data[$field_t3_id]);

							$field_total_id = $this->get_field_id_by_name('T1+T2+T3',$this->blood_form_id);
								update_post_meta($user_pid,'T1+T2+T3',$data[$field_total_id]);
						}
					}else{
						$result = $this->insert_form_submission($data);
					
						update_post_meta( $result['post_id'],'hos_tab_no',$this->table_no );
						$hos_table_name = $result['hos_table_name'];

						$tmp_date = strtotime($result['date']);
						if($c == 1){
							$min_date = $tmp_date;
							$min_post_id = $result['post_id'];
						}
						$max_date = $tmp_date;
						$max_post_id = $result['post_id'];
					}
					$imported++;
		        }
		        $c++;
			}

			if( $this->form_id !== $this->blood_form_id){
				update_post_meta( $min_post_id,'hospital_table_min_date',date('Y-m-d',$min_date) );
				update_post_meta( $max_post_id,'hospital_table_max_date',date('Y-m-d',$max_date) );
				if( !empty($hos_table_name) ){
					update_post_meta( $this->table_no,'hos_table_name',$hos_table_name );
					wp_update_post( array(
						'ID' => $this->table_no,
						'post_title' => $hos_table_name
						));
				}
				update_post_meta( $this->table_no,'hospital_table_min_date',date('Y-m-d',$min_date) );
				update_post_meta( $this->table_no,'hospital_table_max_date',date('Y-m-d',$max_date) );
				update_post_meta( $this->table_no,'hos_form_id',$this->form_id );
			}

		    fclose($res);
			ini_set("auto_detect_line_endings", true);
			$exec_time = microtime(true) - $time_start;
			echo '<div class="updated">';
			echo sprintf(" 成功 <b>上传</b> - <b>%d</b> 行数据<br><br>", $imported);
			echo sprintf("上传时间 <b>%.2f</b> 秒.", $exec_time);
			echo '</div>';
			echo '<a href="'.admin_url().'" class="button">'.__('返回仪表盘').'</a>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.admin_url('admin.php?page='.$_GET['page']).'" class="button">'.__('继续上传').'</a>';
			echo '</div>';

			if (file_exists($this->filename)){
				@unlink($this->filename);
			}
		} 
	}

	function track_info_import(){
		if( isset($_POST['submit']) ){
			if(empty($_FILES['track_import']['tmp_name'])){
				$this->error = "No file uploaded";
			}else{
				$file_name_arr = explode('.', $_FILES['track_import']['name']);
				$pos = count($file_name_arr)-1;
				$file_ext = $file_name_arr[$pos];

				if( !in_array( $file_ext, array('xls','xlsx','csv') ) ){
		            $this->error = __('请上传正确的excle文件.例如xls,xlsv,csv');
				}
				move_uploaded_file($_FILES['track_import']['tmp_name'], $this->track_info_filename);
				
				if ( !file_exists($this->track_info_filename) || !is_readable($this->track_info_filename) ) {
		            $this->error = "Can not open/read uploaded file.";
				}
			}

			if( empty($this->error) ){
				require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');

				$PHPExcel = PHPExcel_IOFactory::load($this->track_info_filename); // 载入excel文件
				$sheetCount = $PHPExcel->getSheetCount();
				global $wpdb,$table_prefix;

				$table_meta = $table_prefix . 'postmeta';
				$this->ent1_form_id = get_option( 'ent1_form_id' );
				$field_id = $this->get_field_id_by_name( '入口1',$this->ent1_form_id );
				$field_name = '_field_'.$field_id;

				for ($i=0; $i < $sheetCount; $i++) { 
					$sheet = $PHPExcel->getSheet($i); // 读取第一個工作表
					$highestRow = $sheet->getHighestRow(); // 取得总行数

					//确实表中内容B1是no1
					$verify_name = $sheet->getCell('B1')->getValue();
					if( 'no1' != strtolower($verify_name) )
						continue;
					
					/** 循环读取每个单元格的数据 */
					$track_data = array();
					$track_data_key = array();

					for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
						if( 1 == $row ){
							for ($column = 'A'; $column != 'SV'; $column++) {//列数是以A列开始
								$value = $sheet->getCell($column.$row)->getValue();
								if( empty($value) )
						        	break;
						        $track_data_key[$column] = strtolower($value);
						    }
						}else{
							for ($column = 'A'; $column != 'SV'; $column++) {//列数是以A列开始
								$value = $sheet->getCell($column.$row)->getFormattedValue();
								if( empty($track_data_key[$column]) )
						        	break;
						        $track_data[$row][$track_data_key[$column]] = $value;
						    }
						}
					}
					//echo '<pre>';print_r($track_data);echo '</pre>';
					//
					switch ($i) {
						case 0:
							$track_items = array( '1mques','1mphyexam','1mbmilk','1mici','1mmeco' );
							break;
						case 1:
							$track_items = array( '6mques','6mphyexam','6mbmilk','6mbailey','6mmeco' );
							break;
						case 2:
							$track_items = array( '1yques','1yphyexam','1ybmilk','1ybailey','1yritq','1ybpb','1ymeco' );
							break;
						case 3:
							$track_items = array( '2yques','2yphyexam','2ybailey','2ybpb','2ymeco' );
							break;
						
						default:
							# code...
							break;
					}
					$track_items_name = 'hos_check'.$i.'_item';

					foreach ($track_data as $track_info) {
				        $sql = $wpdb->prepare("SELECT post_id FROM " . $table_meta . " WHERE meta_key = %s AND meta_value = %s" ,$field_name ,$track_info['no1'] );
						$post_id = $wpdb->get_var($sql);

						if( $post_id>0 ){
							$sql = $wpdb->prepare("DELETE FROM " . $table_meta . " WHERE meta_key LIKE %s AND post_id = %d" ,$track_items_name.'%' ,$post_id );
							$wpdb->query($sql);

							foreach ($track_items as $key => $item) {
								if( !array_key_exists($item, $track_info) ){
									$this->error .= $item . ',';
									continue;
								}

								if( 1 == $track_info[$item] ){
									$option_meta = $track_items_name.$key;
									update_post_meta( $post_id ,$option_meta ,1 );
								}
							}
						}
					}
				}
				if (file_exists($this->track_info_filename)){
					@unlink($this->track_info_filename);
				}

				if( !empty($this->error) ){
					$this->error = substr( $this->error, 0 , strlen($this->error)-1 );
					$this->error = '以下参数因为缺失而未导入到数据库中:'.$this->error;
				}
			}
		}
?>
		<div class="wrap">
			<h2>
				上传文件
			</h2><br/>
			<?php if($this->error !== ''){ ?>
				<div class="error">
				<?php echo $this->error; ?>
				</div>
			<?php }elseif( isset($_POST['submit']) ){ ?>
				 <div class="updated">
				    上传成功
				 </div>
			<?php } ?>
			<form class="add:the-list: validate" method="post" enctype="multipart/form-data">
			<!-- File input -->
			<p>
				<label for="track_import">
					选择文件:
				</label><br/>
				<input name="track_import" id="track_import" type="file" value=""/>
			</p>
			
			<p class="submit">
				<input type="submit" class="button" name="submit" value="上传" />
			</p>
		 </form>
		</div>
<?php
	}

	function checkIsPost($postvar,$postval){
		if(!isset($_POST[$postvar]))
		   return FALSE;
		if(!$_POST[$postvar] == $postval)
		   return FALSE;
		return TRUE;
	}

	//处理上传文件的执行顺序
	function HandlePages(){
		if( $this->checkIsPost('_csv_import_files_next','next') ){
			if(empty($_FILES['csv_import']['tmp_name'])){
				$this->error = "No file uploaded";
				$this->step1 = TRUE;
				return;
			}else{
				$file_name_arr = explode('.', $_FILES['csv_import']['name']);
				$pos = count($file_name_arr)-1;
				$file_ext = $file_name_arr[$pos];

				if( !in_array( $file_ext, array('xls','xlsx','csv') ) ){
		            $this->error = __('请上传正确的excle文件.例如xls,xlsv,csv');
					$this->step1 = TRUE;
					return;
				}

				if( 'csv' != $file_ext ){
					$filename = dirname(__FILE__).'/'.$_FILES['csv_import']['name'];
					move_uploaded_file($_FILES['csv_import']['tmp_name'], $filename);
					$this->convertXLStoCSV($filename,$this->filename);
					unlink($filename);
				}else{
					move_uploaded_file($_FILES['csv_import']['tmp_name'], $this->filename);
				}
				
				if ( !file_exists($this->filename) || !is_readable($this->filename) ) {
		            $this->error = "Can not open/read uploaded file.";
					$this->step1 = TRUE;
					return;
				}
				$this->step2 = TRUE;
			}
		}elseif( $this->checkIsPost('_csv_import_files_next1','next2') ){
			if($this->checkIsPost('submitback','返回')){ 
			   $this->step1 = TRUE;
			   return;
			}
			$columns = $_POST['number_of_fields'];
			$alloptions = array();
			for($i = 0; $i < $columns; $i++){
				$val = '';
			    if( $this->GetPost("field$i",$val) ){
					if( !in_array($val,$alloptions) || 0 == $_POST["field$i"] ){
						if( 0 == $_POST["field$i"])
							continue;
						$alloptions[] = $val;
					}else{
						$this->error = "Post field(s) mapped more than once !";
						$this->step2 = TRUE;
						return;
					}
				    $this->mapped[$i]= $val;
				}
			}
			$this->step3 = TRUE;
			
		}else{
			//default
			$this->step1 = TRUE;
		}
		
	}

	//读取上传文件的内容
	function fopen_utf8($filename){
		if (!file_exists($filename) || !is_readable($filename)) 
			return 0;
	    $encoding='';
	    $handle = fopen($filename, 'r');
	    $bom = fread($handle, 2);
	    rewind($handle);

	    if($bom === chr(0xff).chr(0xfe)  || $bom === chr(0xfe).chr(0xff)){
	            // UTF16 Byte Order Mark present
	        $encoding = 'UTF-16';
	    }
	//        $encoding = mb_detect_encoding($file_sample , 'UTF-8, UTF-7, ASCII, EUC-JP,SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP');
		$bytes = fread($handle, 3);
		if ($bytes != pack('CCC', 0xef, 0xbb, 0xbf)) {
		 	rewind($handle);
		}
		if($encoding != ''){
		 	stream_filter_append($handle, 'convert.iconv.'.$encoding.'/UTF-8');
		}
	    return ($handle);
	}

	//得到通过ninja form创建的表的结构
	function get_form_info($form_id){
		global $wpdb,$table_prefix;
		$ninja_form_table = $table_prefix . 'ninja_forms_fields';
		
		$result = $wpdb->get_results( "SELECT * FROM $ninja_form_table WHERE form_id = '$form_id' order by `order` ASC" ,ARRAY_A );
		foreach ($result as $key => $value) {
			$result[$key]['data'] = unserialize($value['data']);
		}
		return $result;
	}

	function GetPost($postvar,&$postval){
		if(!isset($_POST[$postvar]))
	        return FALSE;
		$postval = $_POST[$postvar];
		if($postval == '')		
		   return FALSE;
		return TRUE;
	}

	//调用ninja form中的函数,实现上传文件的数据导入
	function insert_form_submission($data){
		global $wpdb,$table_prefix;
		//echo '<pre>';print_r($data);echo '</pre>';

		$sub_obj = new NF_Upgrade_Submissions();
		$form_id = $this->form_id;
		$last_sub = nf_get_object_meta_value($this->form_id,'last_sub');
		$next_sub = $last_sub+1;

		$pre_arr = array(
			'form_id' => $form_id,
			'action' => 'import',
			'seq_num' => $next_sub
			);

		$date_updated = '';
		$hos_table_name = '';
		$tz = get_option( 'timezone_string' );

		foreach ($data as $key => $value) {
			$pre_arr['data'][$key]['field_id'] = $key;
			$pre_arr['data'][$key]['user_value'] = $value;
			$field_label = isset( Ninja_Forms()->form( $form_id )->fields[ $key ]['data']['label'] ) ? Ninja_Forms()->form( $form_id )->fields[ $key ]['data']['label'] : '';
			if( '分娩日期' == $field_label ){
				$string_gmt = date_create( $value, new DateTimeZone( $tz ) );
				if ( ! $string_gmt )
					return 0;
				$string_gmt->setTimezone( new DateTimeZone( 'PRC' ) );
				$date_updated = $string_gmt->format( 'Y-m-d' );
				$date_updated2 = $date_updated . ' 12:00:00';
				$pre_arr['data'][$key]['user_value'] = $date_updated;
			}
			if( '表号' == $field_label){
				$hos_table_name = $value;
			}
		}

		if( !empty($date_updated) ){
			$pre_arr['date_updated'] = $date_updated2;
		}
		// echo '<pre>';print_r($pre_arr);echo '</pre>';
		//echo '<pre>';print_r($next_sub);echo '</pre>';return;
		$post_id = $sub_obj->convert( $pre_arr ,$next_sub );
		
		$return_arr = array(
			'post_id' => $post_id,
			'date' => $date_updated2,
			'hos_table_name' => $hos_table_name
			);
		return $return_arr;
	}

	//将上传的非CVS格式的文件,转化成CSV格式
	function convertXLStoCSV($infile,$outfile){
		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');

		$fileType = PHPExcel_IOFactory::identify($infile);
	    $objReader = PHPExcel_IOFactory::createReader($fileType);
	 
	    $objReader->setReadDataOnly(true);   
	    $objPHPExcel = $objReader->load($infile);    
	 
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
	    $objWriter->save($outfile);

	}

	function tel_list(){
		if( isset($_GET['action']) && $_GET['action'] == 'print' ){
			$export = $this->tel_export();
			$this->download($export);
			exit();
		}
		$html = '';
		$region = isset($_GET['region']) ? $_GET['region'] : '';
		$form_id = isset($_GET['form_id']) ? $_GET['form_id'] : '';
		$table_no = isset($_GET['table_no']) ? $_GET['table_no'] : '';
		$table_name = get_post_meta( $table_no,'hos_table_name',true );
		$error = 0;
		$is_tel = 1;
		$region2 = 0;
		$data_type = 'tel';
		if( $region >= self::$check_type ){
			$is_tel = 0;
			$region = $region-self::$check_type;
			$data_type = 'track';
		}
		switch ($region) {
			case 0:
				$title = __('25天电话通知');
				break;
			case 1:
				$title = __('5个半月电话通知');
				break;
			case 2:
				$title = __('11个半月电话通知');
				break;
			case 3:
				$title = __('23个半月电话通知');
				break;
			case 4:
				$title = __('45天电话随访');
				break;
			case 5:
				$title = __('7个月电话随访');
				break;
			case 6:
				$title = __('13个月电话随访');
				break;
			case 7:
				$title = __('25个月电话随访');
				break;
			default:
				break;
		}
		if( !empty($_GET['table_no']) && '-1' != $_GET['region'] )
			$title .= '—'.$table_name;
		if( '-1' == $_GET['region'] )
			$error = 1;

		$url = admin_url( 'admin.php?page=tel_list&action=print&region='.$region.'&form_id='.$form_id.'&table_no='.$table_no );
		$html.= '<div style="font-size:20px;text-align:center;padding-top:5px;">'.$title.'</div>';
		$html.= '<div style="float:right;padding-top:5px;"><a class="button" style="" href="'.admin_url().'">'.__('回到仪表盘').'</a>&nbsp;&nbsp;';
		
		if( $is_tel ){
			$is_ignore = get_post_meta( $table_no,'ignore_tel_'.$region,true );
		}else{
			$is_ignore = get_post_meta( $table_no,'ignore_track_'.$region,true );
		}

		if( $is_ignore ){
			$ignore_title = __('重新提醒');
		}else{
			$ignore_title = __('不再提醒');
		}
		if( !$error && !empty($_GET['table_no']) && current_user_can('manage_options') ){
			$html.= '<a class="button list_ignore" data-region="'.$region.'" data-table_no="'.$table_no.'" data-ignore="'.$is_ignore.'" data-type="'.$data_type.'">'.$ignore_title.'</a>&nbsp;&nbsp;';
			$html.= '<a class="button" href="'.$url.'">'.__('导出表单').'</a>&nbsp;&nbsp;';
		}
		$html.= '</div>';
		echo $html;
		if( $is_tel ){
			$this->render_tel_list();
		}else{
			$this->render_tel_track_list();
		}
		
	}

	function email_and_phone(){
			if(isset($_POST['hos_phone_number']) ) {
				$hos_phone_number  = json_encode($_POST['hos_phone_number']);
				$hos_phone_status = update_option('hos_phone_number',$hos_phone_number);
			}
			if(isset($_POST['hos_manager_email']) ) {
				$hos_manager_email = json_encode($_POST['hos_manager_email']);
				$hos_email_status = update_option('hos_manager_email',$hos_manager_email);
			}
		?>
		<div> 
			<?php 
				if( $hos_phone_status ){
					?>
					<div class="updated">
						<p>
						电话号码已保存
						<span id="phone_dashicons" style="float:right;" class="dashicons dashicons-dismiss"></span>
						</p>
					</div>	
					<?php
				}
				if( $hos_email_status ){
					?>
					<div class="updated">
						<p>
						邮箱已保存
						<span id="email_dashicons" style="float:right;" class="dashicons dashicons-dismiss"></span>
						</p>
					</div>	
					<?php
				}
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('#phone_dashicons').mouseover(function(){
			 			jQuery("#phone_dashicons").css("cursor","pointer");
			 			jQuery("#phone_dashicons").css("color","red");
					});

					jQuery('#phone_dashicons').mouseout(function(){
			 			jQuery("#phone_dashicons").css("cursor","default");
			 			jQuery("#phone_dashicons").css("color","#444444");
					});
					jQuery('#email_dashicons').mouseover(function(){
			 			jQuery("#email_dashicons").css("cursor","pointer");
			 			jQuery("#email_dashicons").css("color","red");
					});

					jQuery('#email_dashicons').mouseout(function(){
			 			jQuery("#email_dashicons").css("cursor","default");
			 			jQuery("#email_dashicons").css("color","#444444");
					});


					jQuery('#phone_dashicons').click(function(){
						jQuery(this).parent().parent().hide();
					});
					jQuery('#email_dashicons').click(function(){
						jQuery(this).parent().parent().hide();
					});
				});
			</script>
			<h2> 预警手机邮件设置</h2>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
				<p>
					<label>请输入手机(一行一个):</label>
					</br>
					<textarea rows="5" name="hos_phone_number"><?php echo trim(json_decode(get_option('hos_phone_number'))); ?></textarea>
				</p>
				<p>
					</br>
					<label>请输入邮箱(一行一个):</label>
					</br>
					<textarea rows="5" name="hos_manager_email"><?php echo json_decode(get_option('hos_manager_email')); ?></textarea>
					</br>
				</p>
				<input type="submit" value="保存"  class="button-primary"/>  
			</form>
		</div>
		<?php
	}

	//列表需要提醒的表
	function render_tel_list(){

		$table = new Hospital_Track_Tel_List();
		$table->prepare_items();
		$extra_field_name = array('入口1','母亲姓名','分娩日期');
		$extra_field_arr = array();

		foreach ($extra_field_name as $name) {
			$key1 = array_search($name,$table->table_head);
			$extra_field_arr[] = array(
					'name' => $name,
					'key1' => $table->key_arr[$key1]
				); 
		}
		//echo '<pre>';print_r($extra_field_arr);echo '</pre>';
		//echo '<pre>';print_r($table->items);echo '</pre>';

		if( $this->is_mobile ){
			$mobile_html = '<div class="tablenav top">';
			$mobile_html.= '<table class="widefat fixed striped">';
			$mobile_html.= '<thead><tr>';
			foreach ($extra_field_arr as $field) {
				$field_name = $field['name'];
				$extra_style = '';
				if( '入口1' == $field_name )
					$extra_style = ' style="width:20%;"';
				if( '分娩日期' == $field_name )
					$extra_style = ' style="width:34%;"';

				$mobile_html.= '<th scope="col"'.$extra_style.'>'.$field_name.'</th>';
			}
			$mobile_html.= '<th scope="col" style="width:22%;">'.__('操作').'</th>';
			$mobile_html.= '</tr></thead>';
			$mobile_html.= '<tbody id="the-list">';
			foreach ($table->items as $key => $item) {
				$mobile_html.='<tr>';
				foreach ($extra_field_arr as $field) {
					$mobile_html.= '<td>'.$item[$field['key1']].'</td>';
				}
				$mobile_html.= '<td>'.$item['action'].'</td>';
				$mobile_html.='</tr>';
			}
			$mobile_html.= '</tbody></table></div>';
			echo $mobile_html;

		}else{
			$table->display();
		}
		//echo '<pre>';print_r($table);echo '</pre>';

		$check_tels_title = '';
		$tels_titles = array(
				__('打第一遍电话'),
				__('是否来'),
				__('打第二遍电话'),
				__('是否来'),
				__('护士打电话'),
				__('是否来'),
			);
		$tel_results = array(
				__('选择通话情况'),
				__('同意'),
				__('拒绝'),
				__('空号'),
				__('停机'),
				__('关机'),
				__('未接'),
				__('未通'),
				__('挂断'),
			);
		$region = $_GET['region'];

		switch ($region) {
			case 0:
				$check_tels_title = __('30天电话调查');
				break;
			case 1:
				$check_tels_title = __('6个月电话调查');
				break;
			case 2:
				$check_tels_title = __('1年电话调查');
				break;
			case 3:
				$check_tels_title = __('2年电话调查');
				break;
			default:
				# code...
				break;
		}

		foreach ($table->items as $key => $item) {

			$html_tel = '<div id="dialog_tel_'.$key.'" class="dialog" style="display:none;" key="'.$key.'" act="tel" data-title="'.$check_tels_title.'">  
			        <form id="hos_dialog_tel_'.$key.'">
			            <table class="hos-dialog-table">
						<tbody>';
			// $tel_option = "hos_tel".$region;
			// $tel_value = get_post_meta( $item['post_id'],$tel_option,true );
			foreach ($extra_field_arr as $key1 => $extra) {
				$html_tel.= '<tr class="hos-dialog-item">
						<td class="hos-dialog-left">'.$extra['name'].'</td>';
				$html_tel.= '<td class="hos-dialog-right">'.$item[$extra['key1']].'</td></tr>';
			}

			foreach ($tels_titles as $k => $tel_title) {
				$item_option = "hos_tel".$region."_item".$k;
				$item_tel_value = get_post_meta( $item['post_id'],$item_option,true );
				if( $k%2 != 0 ){
					$item_tel_value = !empty($item_tel_value) ? 1 : 0;
				}else{
					$item_tel_value = !empty($item_tel_value) ? $item_tel_value : 0;
				}
				
				$html_tel.= '<tr class="hos-dialog-item">
					<td class="hos-dialog-left">'.$tel_title.'</td>';
				if( $k%2 != 0 ){
					$html_tel.= '<td>
						<div class="hos-dialog-right">
						<input type="radio" name="hos_tel'.$region.'_item'.$k.'" id="hos_tel'.$region.'_item'.$k.'_l" value="0"'.checked( $item_tel_value ,0,false ).' /><label for="hos_tel'.$region.'_item'.$k.'_l">'.__('否').'</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="hos_tel'.$region.'_item'.$k.'" id="hos_tel'.$region.'_item'.$k.'_r" value="1" '.checked( $item_tel_value ,1,false ).' /><label for="hos_tel'.$region.'_item'.$k.'_r">'.__('是').'</label>
						</div>
					</td>';
				}else{
					$html_tel.= '<td>
						<div class="hos-dialog-right">
						<select name="hos_tel'.$region.'_item'.$k.'">';
							foreach ($tel_results as $m => $tel_result) {
								$html_tel.= '<option value="'.$m.'" '.selected( $item_tel_value,$m,false ).'>'.$tel_result.'</option>';
							}
					$html_tel.='</select>
						</div>
					</td>';
				}
					
				$html_tel.= '</tr>';
			}
			$html_tel.= '</table>
					<input type="hidden" name="post_id" value="'.$item['post_id'].'">
					<input type="hidden" name="region" value="'.$region.'">
			        </form>  
			    	</div> ';
			//echo $html_info;
			echo $html_tel;
		}
	}

	//列表需要提醒的表
	function render_tel_track_list(){

		$table = new Hospital_Track_Tel_List();
		$table->prepare_items();
		$extra_field_name = array('入口1','母亲姓名','分娩日期');
		$extra_field_arr = array();

		foreach ($extra_field_name as $name) {
			$key1 = array_search($name,$table->table_head);
			$extra_field_arr[] = array(
					'name' => $name,
					'key1' => $table->key_arr[$key1]
				); 
		}
		//echo '<pre>';print_r($extra_field_arr);echo '</pre>';
		//echo '<pre>';print_r($table->items);echo '</pre>';

		if( $this->is_mobile ){
			$mobile_html = '<div class="tablenav top">';
			$mobile_html.= '<table class="widefat fixed striped">';
			$mobile_html.= '<thead><tr>';
			foreach ($extra_field_arr as $field) {
				$field_name = $field['name'];
				$extra_style = '';
				if( '入口1' == $field_name )
					$extra_style = ' style="width:20%;"';
				if( '分娩日期' == $field_name )
					$extra_style = ' style="width:33%;"';

				$mobile_html.= '<th scope="col"'.$extra_style.'>'.$field_name.'</th>';
			}
			$mobile_html.= '<th scope="col" style="width:22%;">'.__('备注').'</th>';
			$mobile_html.= '</tr></thead>';
			$mobile_html.= '<tbody id="the-list">';
			foreach ($table->items as $key => $item) {
				$mobile_html.='<tr>';
				foreach ($extra_field_arr as $field) {
					$mobile_html.= '<td>'.$item[$field['key1']].'</td>';
				}
				$option_temp = 'tel_remark'.$_GET['region'];
				$value_temp = get_post_meta( $item['post_id'],$option_temp,true );

				$mobile_html.= '<td>'.$item['remark'].'</td>';
				$mobile_html.='</tr>';
				$mobile_html.='<tr id="remark_tr'.$item['post_id'].'" class="hos_remark_tr">';
				$mobile_html.= '<td colspan="4"><textarea id="mobile_remark'.$item['post_id'].'">'.$value_temp.'</textarea><br /><a class="button remark_button" data-id="'.$item['post_id'].'" data-region="'.$_GET['region'].'">'.__('保存').'</a></td>';
				$mobile_html.='</tr>';
			}
			$mobile_html.= '</tbody></table></div>';
			echo $mobile_html;

		}else{
			$table->display();
		}
		//echo '<pre>';print_r($table);echo '</pre>';
	}

	function track_list(){

		$html = '';
		$region = isset($_GET['region']) ? $_GET['region'] : '';
		$form_id = isset($_GET['form_id']) ? $_GET['form_id'] : '';
		switch ($region) {
			case 0:
				$title = __('30天随访情况');
				break;
			case 1:
				$title = __('6个月随访情况');
				break;
			case 2:
				$title = __('1岁随访情况');
				break;
			case 3:
				$title = __('2岁随访情况');
				break;
			default:
				$title = __('30天随访情况');
				break;
		}

		$html.= '<div style="font-size:20px;text-align:center;padding-top:5px;">'.$title.'</div>';
		$html.= '<div style="float:right;padding-top:5px;"><a class="button" style="" href="'.admin_url().'">'.__('回到仪表盘').'</a>&nbsp;&nbsp;';
		
		echo $html;
		$this->render_track_list();
	}

	function render_track_list(){

		$field_id = $this->get_field_id_by_name('分娩日期',$this->ent1_form_id);
		$table = new Hospital_Track_Info_List();
		$table->prepare_items();
		$table->items = $this->array_sort( $table->items, $field_id, 'ASC' );

		$region = isset($_GET['region']) ? $_GET['region'] : '';

		$extra_field_name = array('入口1','母亲姓名','分娩日期');
		$extra_field_arr = array();
		foreach ($extra_field_name as $name) {
			$key1 = array_search($name,$table->table_head);
			$extra_field_arr[] = array(
					'name' => $name,
					'key1' => $table->key_arr[$key1]
				); 
		}

		if( $this->is_mobile ){
			$mobile_html = '<div class="tablenav top">';
			$mobile_html.= '<table class="widefat fixed striped">';
			$mobile_html.= '<thead><tr>';

			foreach ($extra_field_arr as $field) {
				$field_name = $field['name'];
				$extra_style = '';
				if( '入口1' == $field_name )
					$extra_style = ' style="width:20%;"';
				if( '分娩日期' == $field_name )
					$extra_style = ' style="width:34%;"';

				$mobile_html.= '<th scope="col"'.$extra_style.'>'.$field_name.'</th>';
			}
			$mobile_html.= '<th scope="col" style="width:22%;">'.__('操作').'</th>';
			$mobile_html.= '</tr></thead>';
			$mobile_html.= '<tbody id="the-list">';

			foreach ($table->items as $key => $item) {
				$mobile_html.='<tr>';
				foreach ($extra_field_arr as $field) {
					$mobile_html.= '<td>'.$item[$field['key1']].'</td>';
				}
				$edit_html = '<a class="button hos_track" hkey='.$key.'>'.__('随访').'</a>&nbsp;&nbsp;';
				$mobile_html.= '<td>'.$edit_html.'</td>';
				$mobile_html.='</tr>';
			}
			$mobile_html.= '</tbody></table></div>';
			echo $mobile_html;

			$button_name = array();
			for ($i=0; $i < self::$check_type; $i++) { 
				switch ($i) {
					case 0:
						$button_name[$i] = __('30天');
						break;
					case 1:
						$button_name[$i] = __('6个月');
						break;
					case 2:
						$button_name[$i] = __('1岁');
						break;
					case 3:
						$button_name[$i] = __('2岁');
						break;
					default:
						# code...
						break;
				}
			}

			foreach ($table->items as $key => $item) {
				$html_info = '<div id="dialog_info_'.$key.'" class="dialog" style="display:none;" key="'.$key.'" act="info" data-title="'.__('随访情况').'">';
				$html_info.= '<div>';
				for ($i=0; $i < self::$check_type; $i++) {
					$button_active = ''; 
					if( $i == $region )
						$button_active = ' style="border-color:#23282d;"';
					$html_info.= '<a class="button track_b track_button'.$key.'"'.$button_active.' data-region="'.$i.'" data-key="'.$key.'">'.$button_name[$i].'</a>&nbsp;&nbsp;';
				}
				$html_info.= '</div>';
				$html_info.= '<form id="hos_dialog_info_'.$key.'">
				            <table class="hos-dialog-table">
							<tbody>';

				foreach ($extra_field_arr as $key1 => $extra) {
					$html_info.= '<tr class="hos-dialog-item">
							<td class="hos-dialog-left">'.$extra['name'].'</td>';
					$html_info.= '<td class="hos-dialog-right">'.$item[$extra['key1']].'</td></tr>';
				}

				for ($i=0; $i < self::$check_type; $i++) {
					$info_active = ' style="display:none"';
					if( $i == $region )
						$info_active = '';
					for ($j=0; $j < $this->item_infos[$i]['count']; $j++) {
						$item_option = 'hos_check'.$i.'_item'.$j;
						$item_value = get_post_meta( $item['post_id'],$item_option,true );
						$item_label_id = $item_option . '_' . $key;
						$item_tr_group = ' hos_check_tr'.$key.'_group'.$i;

						$html_info.= '<tr class="hos-dialog-item hos_check'.$key.$item_tr_group.'"'.$info_active.'><td class="hos-dialog-left"><label for="'.$item_label_id.'">'.$this->item_infos[$i][$j]['title'].'</label></td>';
						$html_info.= '<td><input type="checkbox" id="'.$item_label_id.'" '.checked( $item_value,1,false ).' name="'.$item_option.'" /></td>';
						$html_info.= '</tr>';
					}
				}
				                
				$html_info.= '</table>
						<input type="hidden" name="post_id" value="'.$item['post_id'].'">
						<input type="hidden" name="region" value="'.$region.'">
				        </form>  
				    	</div> ';
				echo $html_info;
			}

		}else{
			//echo '<pre>';print_r($table);echo '</pre>';
			$html_info = '<div>';
			$html_info.= '<table class="widefat fixed" id="hos_table1">';
			$html_info.= '<thead><tr>';

			$m = 0;
			for ($m=0; $m < 3; $m++) { 
				$html_info.= '<th scope="col" style="text-align:center;" rowspan="2">'.$table->table_head[$m].'</th>';
			}
			switch ($region) {
				case 0:
					$html_info.= '<th colspan="'.self::$check_count1.'" style="text-align:center;">'.__('30天').'</th>';
					break;
				case 1:
					$html_info.= '<th colspan="'.self::$check_count1.'" style="text-align:center;">'.__('6个月').'</th>';
					break;
				case 2:
					$html_info.= '<th colspan="'.self::$check_count2.'" style="text-align:center;">'.__('1岁').'</th>';
					break;
				case 3:
					$html_info.= '<th colspan="'.self::$check_count1.'" style="text-align:center;">'.__('2岁').'</th>';
					break;
				
				default:
					# code...
					break;
			}
			$html_info.= '</tr>';

			$html_info.= '<tr>';
			for ($m=3; $m < count($table->table_head); $m++) { 
				$html_info.= '<th>'.$table->table_head[$m].'</th>';
			}
			$html_info.= '</tr>';
			$html_info.= '</thead>';

			$html_info.= '<tbody id="the-list">';
			foreach ($table->items as $single_items) {
				$html_info.= '<tr>';
				foreach ($single_items as $key => $value) {
					$post_id = $single_items['post_id'];
					if( 'post_id' != $key ){
						if( strpos( $key,'hos_check' ) === false ){
							$html_info.= '<td>'.$value.'</td>';
						}else{
							$html_info.= '<td style="text-align:center;"><input type="checkbox" class="track_check" data-option="'.$key.'" data-id="'.$post_id.'" '.checked( $value,1,false ).' /></td>';
						}
					}
				}
				$html_info.= '</tr>';
			}

			$html_info.= '</tbody>';
			$html_info.= '</table></div>';
			echo $html_info;

		}
	}

	function array_sort( $array, $on, $order='ASC' ){
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case 'ASC':
                    asort($sortable_array);
                break;
                case 'DESC':
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array; 
    }

    function tel_export(){

    	$export_arr = array();
		$table = new Hospital_Track_Tel_List();
		$table->prepare_items();
		
		array_pop($table->table_head);
		if( $_GET['region'] < self::$check_type ){
			$is_tel = 1;
		}else{
			$is_tel = 0;
		}
		if( $is_tel ){
			$extra_head = array( __('打第一遍电话'),__('是否来'),__('打第二遍电话'),__('是否来'),__('护士打电话'),__('是否来') );
		}else{
			$extra_head = array( __('备注') );
		}
		
		switch ($_GET['region']) {
			case 0:
				$title = __('25天电话通知');
				break;
			case 1:
				$title = __('5个半月电话通知');
				break;
			case 2:
				$title = __('11个半月电话通知');
				break;
			case 3:
				$title = __('23个半月电话通知');
				break;
			case 4:
				$title = __('45天电话随访');
				break;
			case 5:
				$title = __('7个月电话随访');
				break;
			case 6:
				$title = __('13个月电话随访');
				break;
			case 7:
				$title = __('25个月电话随访');
				break;
			default:
				break;
		}

		$table_name = get_post_meta( $table->table_no,'hos_table_name',true );
		$export_arr['file_name'] = $title.'-'.$table_name;

		$tel_results = array(
				'',
				__('同意'),
				__('拒绝'),
				__('空号'),
				__('停机'),
				__('关机'),
				__('未接'),
				__('未通'),
				__('挂断'),
			);

		$export_arr['head'] = array_merge( $table->table_head,$extra_head );

		foreach ($table->items as $key => $item) {
			if($is_tel){
				for ($i=0; $i < count($extra_head); $i++) { 
					$option_meta = 'hos_tel'.$region.'_item'.$i;
					$option_value = get_post_meta( $item['post_id'],$option_meta,true );
					if( $i%2 == 0 ){
						$item[] = $tel_results[$option_value];
					}else{
						$item[] = empty($option_value) ? __('否') : __('是'); 
					}
				}
				unset($item['action']);
			}else{
				$option_meta = 'tel_remark'.$_GET['region'];
				$item[] = get_post_meta( $item['post_id'],$option_meta,true );
				unset($item['remark']);
			}
			
			unset($item['post_id']);
			$export_arr['items'][] = $item;
		}
		return $export_arr;
    }

    function mb_unserialize($serial_str) {
	    $out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
	    return unserialize($out);
	}

	function download($export){
		ob_end_clean();

		require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');

		$PHPExcel = new PHPExcel();
		$PHPExcel->setActiveSheetIndex(0);
		$PHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);

		for ($i=0,$j='A'; $i < count($export['head']); $i++,$j++) {//列数是以A列开始
			$pos = $i+1;
			$PHPExcel->getActiveSheet()->setCellValue($j.'1', $export['head'][$i]);
			$PHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
	    }

	    $m=2;
	    foreach ($export['items'] as $item) {
	    	$n='A';
	    	foreach ($item as $key => $value) {
	    		$PHPExcel->getActiveSheet()->setCellValue($n.$m, $value);
	    		$n++;
	    	}
	    	$m++;
	    }

	    $objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Content-type:text/octect-stream;charset=utf-8");
		header("Content-Disposition:attachment;filename=" . $export['file_name'] .'.xls');
		$objWriter->save('php://output');
		exit();
	}

	//得到所有的分类
	function getAllTableId($form_id = ''){
		global $wpdb,$table_prefix;

		$table_post = $table_prefix . 'posts';
		$table_meta = $table_prefix . 'postmeta';
		$sql = $wpdb->prepare( "SELECT p.ID as post_id FROM " . $table_post . " AS p LEFT JOIN ".$table_meta." AS m ON p.ID = m.post_id WHERE p.post_type='hos_tab' AND m.meta_key='hos_form_id' AND m.meta_value=%d order by p.post_date DESC" ,$form_id );
		$results = $wpdb->get_results($sql,'ARRAY_A');

		return $results;
	}

	function load_widgets() {
		if( current_user_can('manage_options') ){
			wp_add_dashboard_widget( __('30天表单提醒'), __('30天表单提醒'), array( $this,'widget_fi' ) );
		    wp_add_dashboard_widget( __('6个月表单提醒'), __('6个月表单提醒'), array( $this,'widget_se' ) );
		    wp_add_dashboard_widget( __('1年表单提醒'), __('1年表单提醒'), array( $this,'widget_th' ) );
		    wp_add_dashboard_widget( __('2年表单提醒'), __('2年表单提醒'), array( $this,'widget_fo' ) );
		}

		if( current_user_can('manage_categories') ){
			wp_add_dashboard_widget( __('查询表单内容'), __('查询表单内容'), array( $this,'widget_search' ) );
		}
	}

	function widget_fi(){
		$this->render_widget_content(0);
	}

	function widget_se(){
		$this->render_widget_content(1);
	}

	function widget_th(){
		$this->render_widget_content(2);
	}

	function widget_fo(){
		$this->render_widget_content(3);
	}

	function widget_search(){
		$html = '<p"><select name="region" id="region">';
		$html.= '<option value="-1">'.__('选择查询类型').'</option>';
		$select_val = array(
				'0' => __('25天电话'),
				'4' => __('45天随访'),
				'1' => __('5个半月电话'),
				'5' => __('7个月随访'),
				'2' => __('11个半月电话'),
				'6' => __('13个月随访'),
				'3' => __('23个半月电话'),
				'7' => __('25个月随访'),
			);
		
		foreach ($select_val as $i => $value) {
			$selected = '';
			if( $i == 0 )
				$selected = ' selected';
			$html.= '<option value="'.$i.'"'.$selected.'>'.$select_val[$i].'</option>';
		}

		$html.='</select>&nbsp;&nbsp;&nbsp;&nbsp;';

		$table_arr = $this->getAllTableId($this->ent1_form_id);
		$html.= '<select name="table_no" id="table_no">';
		$html.= '<option value="0">'.__('表号').'</option>';

		foreach ($table_arr as $table) {
			$table_name = get_post_meta( $table['post_id'],'hos_table_name',true );
			$html.= '<option value="'.$table['post_id'].'">'.$table_name.'</option>';
		}

		$html.='</select>&nbsp;&nbsp;&nbsp;&nbsp;';
		$url = admin_url('admin.php?form_id='.$this->ent1_form_id);
		$html.= '<a href="'.$url.'" class="button dashicons-before dashicons-media-spreadsheet
" id="hos_search"></a></p>';

		$html.= '<p>'.__('随访情况调查:').'<br />';
		$info_url = admin_url('admin.php?page=track_list&form_id='.$this->ent1_form_id);
		for ($i=0; $i < self::$check_type; $i++) {
			switch ($i) {
				case 0:
					$name = __('30天');
					break;
				case 1:
					$name = __('6个月');
					break;
				case 2:
					$name = __('1岁');
					break;
				case 3:
					$name = __('2岁');
					break;
				default:
					break;
			}
			$html.= '<a href="'.$info_url.'&region='.$i.'" class="button">'.$name.'</a>&nbsp;&nbsp;';
		}
		
		$html.= '</p>';

		echo $html;
	}

	//widget内容
	function render_widget_content($region){

		$tel_title = array();
		$track_title = array();
		$max_count1 = 5;
		$max_count2 = 5;
		date_default_timezone_set('PRC');
		switch ($region) {
			case 0:
				$time1 = date( 'Y-m-d',strtotime('-25 day') );
				$time2 = date( 'Y-m-d',strtotime('-45 day') );
				$tel_title[$region] = __('25天电话');
				$track_title[$region] = __('45天随访');
				$max_count1 = 1;
				break;
			case 1:
				$time1 = strtotime('-5 month');
                $time1 = date( "Y-m-d", strtotime( '-15 day',$time1 ) );
                $time2 = date( "Y-m-d", strtotime( '-7 month' ) );
				$tel_title[$region] = __('5个半月电话');
				$track_title[$region] = __('7个月随访');
				break;
			case 2:
				$time1 = strtotime('-11 month');
                $time1 = date( "Y-m-d", strtotime( '-15 day',$time1 ) );
                $time2 = date( "Y-m-d", strtotime( '-13 month' ) );
				$tel_title[$region] = __('11个半月电话');
				$track_title[$region] = __('13个月随访');
				break;
			case 3:
				$time1 = strtotime('-23 month');
                $time1 = date( "Y-m-d", strtotime( '-15 day',$time1 ) );
                $time2 = date( "Y-m-d", strtotime( '-25 month' ) );
				$tel_title[$region] = __('23个半月电话');
				$track_title[$region] = __('25个月随访');
				break;
			
			default:
				break;
		}
		
		//echo $time1.'<br />';echo $time2.'<br />';
		$results = $this->get_min_date_tables($time1);
		$results2 = $this->get_max_date_tables($time2);
		$html = '<div><h4 id="hos_tel_message'.$region.'" style="color:green;"></h4><table style="width:90%;">';
		$html.= '<thead><tr>';
		$html.= '<th>'.$tel_title[$region].'</th>';
		$html.= '</tr></thead><tbody>';
		
		if( count($results)>0 ){
			$i=0;
			foreach ($results as $value) {
				$hid_class = '';
				if( $i >= $max_count1 ){
					$hid_class = 'hos_widget_hid';
				}
				$html .= '<tr class="'.$hid_class.'"><td>';
				$table_no = $value['post_id'];
				$is_ignore = get_post_meta( $table_no,'ignore_tel_'.$region,true );
				if( 1 == $is_ignore)
					continue;
				$table_name = get_post_meta( $table_no,'hos_table_name',true );
				$url = admin_url('admin.php?page=tel_list&region='.$region.'&form_id='.$this->ent1_form_id.'&table_no='.$table_no);
				$html.= '<a href="'.$url.'" style="float:left;valign:middle;">'.$table_name.'</a>';
				$html.= '&nbsp;&nbsp;&nbsp;&nbsp;<a class="table_ignore button" tab_no="'.$table_no.'" style="float:right;" region="'.$region.'" data-type="tel">'.__('不再提醒').'</a><br />';
				$html .= '</td></tr>';
				$i++;
			}
		}else{
			$html .= '<tr><td>'.__('没有任何相关表').'</td></tr>';
		}
		$html.= '</tbody></table><hr>';

		$html.= '<h4 id="hos_track_message'.$region.'" style="color:green;"></h4><table style="width:90%;">';
		$html.= '<thead><tr>';
		$html.= '<th>'.$track_title[$region].'</th>';
		$html.= '</tr></thead><tbody>';
		
		if( count($results2)>0 ){
			$i=0;
			$region2 = $region+4;
			foreach ($results2 as $value) {
				$hid_class = '';
				if( $i >= $max_count2 ){
					$hid_class = 'hos_widget_hid';
				}
				$html .= '<tr class="'.$hid_class.'"><td>';
				$table_no = $value['post_id'];
				$is_ignore = get_post_meta( $table_no,'ignore_track_'.$region,true );
				if( 1 == $is_ignore)
					continue;
				$table_name = get_post_meta( $table_no,'hos_table_name',true );
				$url = admin_url('admin.php?page=tel_list&region='.$region2.'&form_id='.$this->ent1_form_id.'&table_no='.$table_no);
				$html.= '<a href="'.$url.'" style="float:left;">'.$table_name.'</a>';
				$html.= '&nbsp;&nbsp;&nbsp;&nbsp;<a class="table_ignore button" tab_no="'.$table_no.'" style="float:right;" region="'.$region.'" data-type="track">'.__('不再提醒').'</a><br />';
				$html .= '</td></tr>';
				$i++;
			}
		}else{
			$html .= '<tr><td>'.__('没有任何相关表').'</td></tr>';
		}
		$html.= '</tbody></table></div>';
		echo $html;
	}

	//根据上传表中的最小日期得到相关的表
	function get_min_date_tables( $date ){
		global $wpdb,$table_prefix;

		$form_id = $this->ent1_form_id;
		$post_table = $table_prefix . 'posts';
		$postmeta_table = $table_prefix . 'postmeta';

		$sql = "SELECT * FROM $postmeta_table WHERE meta_key = 'hospital_table_min_date' AND meta_value <= '" . $date . "'";
		$sql .= " AND post_id IN (SELECT m.post_id FROM $post_table AS p LEFT JOIN $postmeta_table AS m ON p.ID=m.post_id WHERE p.post_type = 'hos_tab' AND m.meta_key='hos_form_id' AND m.meta_value=".$form_id.")";

		$result = $wpdb->get_results( $sql ,ARRAY_A );
		return $result;
	}

	//根据上传表中的最大日期得到相关的表
	function get_max_date_tables( $date ){
		global $wpdb,$table_prefix;

		$form_id = $this->ent1_form_id;
		$post_table = $table_prefix . 'posts';
		$postmeta_table = $table_prefix . 'postmeta';

		$sql = "SELECT * FROM $postmeta_table WHERE meta_key = 'hospital_table_max_date' AND meta_value <= '" . $date . "'";
		$sql .= " AND post_id IN (SELECT m.post_id FROM $post_table AS p LEFT JOIN $postmeta_table AS m ON p.ID=m.post_id WHERE p.post_type = 'hos_tab' AND m.meta_key='hos_form_id' AND m.meta_value=".$form_id.")";

		$result = $wpdb->get_results( $sql ,ARRAY_A );
		return $result;
	}

	function hospital_track_ignore_ajax(){
		$table_no = $_POST['tab_no'];
		$region = $_POST['region'];
		$ignore = empty($_POST['ignore']) ? 1 : 0;
		$type = $_POST['type'];

		update_post_meta( $table_no,'ignore_'.$type.'_'.$region,$ignore );
		echo $ignore;
		exit();
	}

	function hospital_track_info_save(){
		$temp_arr = array();
		foreach ($_POST['data'] as $key => $value) {
			if( 'post_id' == $value['name']){
				$post_id = $value['value'];
			}elseif( 'region' == $value['name']){
				$region = $value['value'];
			}else{
				$temp_arr[$value['name']] = $value['value'];
			}
		}
		//update_post_meta( $post_id,'hos_check'.$region,1 );
		for ($i=0; $i < self::$check_type; $i++) {
			$count = self::$check_count1;
			if( 2 == $i )
				$count = self::$check_count2;
			for ($j=0; $j < $count; $j++) {
				$key = 'hos_check'.$i.'_item'.$j;
				if( array_key_exists($key, $temp_arr) && 'on' == $temp_arr[$key] )
					update_post_meta( $post_id,$key,1 );
				else
					update_post_meta( $post_id,$key,0 );
			}
		}

		return;
	}

	function hospital_single_option_save(){
		$post_id = $_POST['post_id'];
		$meta_key = $_POST['meta_key'];
		$meta_value = $_POST['meta_value'];

		update_post_meta( $post_id,$meta_key,$meta_value );
	}

	function hospital_track_tel_save(){
		$temp_arr = array();
		foreach ($_POST['data'] as $key => $value) {
			if( 'post_id' == $value['name']){
				$post_id = $value['value'];
			}elseif( 'region' == $value['name']){
				$region = $value['value'];
			}else{
				$temp_arr[$value['name']] = $value['value'];
			}
		}
		update_post_meta( $post_id,'hos_tel'.$region,1 );

		foreach ($temp_arr as $name => $value) {
			update_post_meta( $post_id,$name,$value );
		}
		return;
	}

	function hos_stat(){

		$html1 = '<div class="container">';
		$html1 .= '<div class="col-md-4">test1</div>';
		$html1 .= '<div class="col-md-4">test2</div>';
		$html1 .= '<div class="col-md-4">test3</div>';
		$html1 .= '</div>';
		//echo $html1;

		$html = '<br /><br /><form id="stat_form">';
		$option_title = array( __('30天:'),__('6个月:'),__('1岁:'),__('2岁:') );
		
		if( $this->is_mobile ){
			$html.= '<p>'.__('开始日期:').'&nbsp;&nbsp;<input type="date" class="start_date" name="start_date[]" size="10" /></p>';
			$html.= '<p>'.__('结束日期:').'&nbsp;&nbsp;<input type="date" class="end_date" name="end_date[]" size="10" /></p>';

			$html.= '<p id="mobile_date">'.__('截止满').'&nbsp;&nbsp;<input type="text" id="date_limit" name="date_limit" size="4" value="" />&nbsp;&nbsp;'.__('天的人').'&nbsp;&nbsp;&nbsp;&nbsp;<a class="button stat_button">'.__('查询').'</a>&nbsp;&nbsp;<a class="button add_data_button" data-type="mobile">'.__('添加查询').'</a></p>';

			$html.= '<p>'.__('符合下列条件(可选):').'</p>';

			for ($i=0; $i < self::$check_type; $i++) { 
				$html .= '<p><div class="hos_mobile_span" style="width:15%;display:-webkit-inline-box;float:left;">'.$option_title[$i].'</div>';
				$count = self::$check_count1;
				if( 2==$i){
					$count = self::$check_count2;
				}

				$html .= '<div style="width:85%;display:-webkit-inline-box;">';
				for ($j=0; $j < $count; $j++) { 
					$item_name = 'hos_check'.$i.'_item'.$j;
					$html .= '<span class="hos_mobile_span">'.$this->item_infos[$i][$j]['title'].'&nbsp;<input type="checkbox" name="'.$item_name.'" class="stat_option stat_checkbox_'.$i.'" data-type="'.$i.'" /></span>';
				}
				$html .= '<input type="hidden" name="has_option'.$i.'" id="has_option'.$i.'" value="0">';
				$html .= '</div></p>';
			}
		}else{
			$html.= '<p>'.__('开始日期:').'&nbsp;&nbsp;<input type="text" class="start_date" name="start_date[]" size="10">';
			$html.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('结束日期:').'&nbsp;&nbsp;<input type="text" class="end_date" name="end_date[]" size="10">&nbsp;&nbsp;';

			$html.= __('截止满').'&nbsp;&nbsp;<input type="text" id="date_limit" name="date_limit" size="4" value="" />&nbsp;&nbsp;'.__('天的人').'&nbsp;&nbsp;&nbsp;&nbsp;<a class="button stat_button">'.__('查询').'</a>&nbsp;&nbsp;<a class="button add_data_button" data-type="pc">'.__('添加查询').'</a></p>';
			
			$html .= '<table class="hos_stat_table"><tr><th colspan="8">'.__('符合下列条件(可选):').'</th></tr>';
			
			for ($i=0; $i < self::$check_type; $i++) { 
				$html .= '<tr><td>'.$option_title[$i].'</td>';
				$count = self::$check_count1;
				if( 2==$i){
					$count = self::$check_count2;
				}

				for ($j=0; $j < $count; $j++) { 
					$item_name = 'hos_check'.$i.'_item'.$j;
					$html .= '<td style="text-align:right;">'.$this->item_infos[$i][$j]['title'].'&nbsp;<input type="checkbox" name="'.$item_name.'" class="stat_option stat_checkbox_'.$i.'" data-type="'.$i.'" /></td>';
				}
				$html .= '</tr>';
				$html .= '<input type="hidden" name="has_option'.$i.'" id="has_option'.$i.'" value="0">';
			}
			$html .= '</table>';
		}


		$html .= '</form>';
		$html .= '<p id="stat_result"></p>';
		echo $html;
		
	}

	function check_track_options( $post_ids_arr,$type,$has_checked = 0,$options = array() ){

		global $wpdb,$table_prefix;

		if( 0 == count($post_ids_arr) )
			return array();

		$post_ids = implode( ',', $post_ids_arr );
		$postmeta_table = $table_prefix . 'postmeta';
		$check_items_count = self::$check_count1;
		if( 2 == $type )
			$check_items_count = self::$check_count2;

		//特定的条件搜索
		if( !empty($has_checked) ){
			foreach ($post_ids_arr as $key => $post_id) {
				$is_checked = true;
				for ($j=0; $j < $check_items_count; $j++) { 
					$option_meta = 'hos_check'.$type.'_item'.$j;

					if( !in_array($option_meta, $options) ){
						continue;
					}else{
						$option_value = get_post_meta( $post_id,$option_meta,true );
						if( empty($option_value) ){
							$is_checked = false;
							break;
						}
					}
				}
				if(!$is_checked)
					unset($post_ids_arr[$key]);
			}
			$result = $post_ids_arr;
		}else{//无条件搜索
			$meta_key = 'hos_check'.$type.'%';

			$sql = "SELECT distinct(post_id) FROM $postmeta_table where post_id in ( ".$post_ids." ) and meta_key like '".$meta_key."' and meta_value=1";
			$result = $wpdb->get_results( $sql ,ARRAY_A );
			$result = self::fill_array_with_field( $result ,'post_id' );
		}

		return $result;
	}

	function check_blood_options( $post_ids_arr ){

		global $wpdb,$table_prefix;

		if( 0 == count($post_ids_arr) )
			return array();

		$post_ids = implode( ',', $post_ids_arr );
		$postmeta_table = $table_prefix . 'postmeta';

		$meta_key = 'T1+T2+T3';

		$sql = "SELECT distinct(post_id) FROM $postmeta_table where post_id in ( ".$post_ids." ) and meta_key like '".$meta_key."' and meta_value=1";
		$result = $wpdb->get_results( $sql ,ARRAY_A );
		$result = self::fill_array_with_field( $result ,'post_id' );

		return $result;
	}

	function fill_array_with_field( $arr,$field){
		$temp_arr = array();
		if( count($arr) == 0 )
			return array();

		foreach ($arr as $key => $value) {
			$temp_arr[] = $value[$field];
		}
		return $temp_arr;
	}

	function get_stat_result_by_date_array( $date_arr,$from,$list_no = 0,$select_day = 0,$check_blood = false,$check_options = array() ){
		//echo '<pre>';print_r($check_options);echo '<pre>';
		date_default_timezone_set('PRC');
		$field_id = $this->get_field_id_by_name('分娩日期',$this->ent1_form_id);
		$is_limit_type = false;
		if( $select_day > 0 ){
			$is_limit_type = true;
		}

		if( $select_day < 30 ){
			$track_type = -1;
		}elseif( $select_day < 180 ){
			$track_type = 0;
		}elseif( $select_day < 365 ){
			$track_type = 1;
		}elseif( $select_day < 365*2 ){
			$track_type = 2;
		}else{
			$track_type = 2;
		}

		$stat_results = array();
		foreach ($date_arr as $round => $date_item) {
			$start_date = ( !empty($date_item['start_date']) ) ? $date_item['start_date'] : date('2014-04-24');
			$end_date = ( !empty($date_item['end_date']) ) ? $date_item['end_date'] : date('Y-m-d');
			$stat_results[$round]['start_date'] = $start_date;
			$stat_results[$round]['end_date'] = $end_date;
			
			if( 'init' == $from ){
				switch ($list_no) {
					case 1:
						for ($i=0; $i < 3; $i++) {
							switch ($i) {
								case 0:
									$start = strtotime( '-45 day',strtotime($start_date) );
									$end = strtotime( '-45 day',strtotime($end_date) );
									break;
								case 1:
									$start = strtotime( '-7 month',strtotime($start_date) );
									$end = strtotime( '-7 month',strtotime($end_date) );
									break;
								case 2:
									$start = strtotime( '-11 month',strtotime($start_date) );
									$end = strtotime( '-11 month',strtotime($end_date) );
									break;
								// case 3:
								// 	$start = strtotime( '-2 year',strtotime($start_date) );
								// 	$end = strtotime( '-2 year',strtotime($end_date) );
								// 	break;
								
								default:
									break;
							}

							$start = date('Y-m-d', $start );
							$end = date('Y-m-d', $end );

							$expect_items_arr = $this->get_users_by_date( $start,$end,$field_id );

							$stat_results[$round]['items'][$i]['expect_posts'] = $expect_items_arr;
							$stat_results[$round]['items'][$i]['expect_count'] = count($expect_items_arr);
							$stat_results[$round]['items'][$i]['actual_posts'] = $this->check_track_options( $expect_items_arr,$i,$check_options[$i]['has_checked'],$check_options[$i]['options'] );
							$stat_results[$round]['items'][$i]['actual_count'] = count($stat_results[$round]['items'][$i]['actual_posts']);
						}
						break;

					case '2':case '3':
						//30天随访
						$start = strtotime( '-45 day',strtotime($start_date) );
						$end = strtotime( '-45 day',strtotime($end_date) );
						$start = date('Y-m-d', $start );
						$end = date('Y-m-d', $end );
						$expect_items_arr = $this->get_users_by_date( $start,$end,$field_id );
						$stat_results[$round]['items'][0]['expect_posts'] = $expect_items_arr;
						if( $check_blood ){
							$stat_results[$round]['items'][0]['expect_posts'] = $this->check_blood_options($stat_results[$round]['items'][0]['expect_posts']);
						}
						$stat_results[$round]['items'][0]['expect_count'] = count($stat_results[$round]['items'][0]['expect_posts']);
						$stat_results[$round]['items'][0]['actual_posts'] = $this->check_track_options( $expect_items_arr,0 );

						if( $check_blood ){
							$stat_results[$round]['items'][0]['actual_posts'] = $this->check_blood_options($stat_results[$round]['items'][0]['actual_posts']);
						}
						$stat_results[$round]['items'][0]['actual_count'] = count($stat_results[$round]['items'][0]['actual_posts']);

						//6个月随访
						$start = strtotime( '-7 month',strtotime($start_date) );
						$end = strtotime( '-7 month',strtotime($end_date) );
						$start = date('Y-m-d', $start );
						$end = date('Y-m-d', $end );
						$expect_items_arr = $this->get_users_by_date( $start,$end,$field_id );
						$stat_results[$round]['items'][1]['expect_posts'] = $expect_items_arr;
						if( $check_blood ){
							$stat_results[$round]['items'][1]['expect_posts'] = $this->check_blood_options($stat_results[$round]['items'][1]['expect_posts']);
						}
						$stat_results[$round]['items'][1]['expect_count'] = count($stat_results[$round]['items'][1]['expect_posts']);

						$stat_results[$round]['items'][1]['actual_posts'] = $this->check_track_options( $expect_items_arr,1 );
						if( $check_blood ){
							$stat_results[$round]['items'][1]['actual_posts'] = $this->check_blood_options($stat_results[$round]['items'][1]['actual_posts']);
						}
						$stat_results[$round]['items'][1]['actual_count'] = count($stat_results[$round]['items'][1]['actual_posts']);

						//1岁随访
						$start = strtotime( '-11 month',strtotime($start_date) );
						$end = strtotime( '-11 month',strtotime($end_date) );
						$start = date('Y-m-d', $start );
						$end = date('Y-m-d', $end );
						$expect_items_arr = $this->get_users_by_date( $start,$end,$field_id );
						$stat_results[$round]['items'][2]['expect_posts'] = $expect_items_arr;
						if( $check_blood ){
							$stat_results[$round]['items'][2]['expect_posts'] = $this->check_blood_options($stat_results[$round]['items'][2]['expect_posts']);
						}
						$stat_results[$round]['items'][2]['expect_count'] = count($stat_results[$round]['items'][2]['expect_posts']);

						$stat_results[$round]['items'][2]['actual_posts'] = $this->check_track_options( $expect_items_arr,2 );
						if( $check_blood ){
							$stat_results[$round]['items'][2]['actual_posts'] = $this->check_blood_options($stat_results[$round]['items'][2]['actual_posts']);
						}
						$stat_results[$round]['items'][2]['actual_count'] = count($stat_results[$round]['items'][2]['actual_posts']);

						//6个月的人中,做了30天随访的
						$stat_results[$round]['items'][3]['expect_posts'] = $stat_results[$round]['items'][1]['expect_posts'];
						$stat_results[$round]['items'][3]['expect_count'] = $stat_results[$round]['items'][1]['expect_count'];
						$stat_results[$round]['items'][3]['actual_posts'] = $this->check_track_options( $stat_results[$round]['items'][1]['actual_posts'],0 );
						if( $check_blood ){
							$stat_results[$round]['items'][3]['actual_posts'] = $this->check_blood_options($stat_results[$round]['items'][3]['actual_posts']);
						}
						$stat_results[$round]['items'][3]['actual_count'] = count($stat_results[$round]['items'][3]['actual_posts']);

						//1岁的人中,做了6个月随访的
						$stat_results[$round]['items'][4]['expect_posts'] = $stat_results[$round]['items'][2]['expect_posts'];
						$stat_results[$round]['items'][4]['expect_count'] = $stat_results[$round]['items'][2]['expect_count'];
						$stat_results[$round]['items'][4]['actual_posts'] = $this->check_track_options( $stat_results[$round]['items'][2]['actual_posts'],1 );
						if( $check_blood ){
							$stat_results[$round]['items'][4]['actual_posts'] = $this->check_blood_options($stat_results[$round]['items'][4]['actual_posts']);
						}
						$stat_results[$round]['items'][4]['actual_count'] = count($stat_results[$round]['items'][4]['actual_posts']);

						//1岁的人中,做了30天和6个月随访的
						$stat_results[$round]['items'][5]['expect_posts'] = $stat_results[$round]['items'][2]['expect_posts'];
						$stat_results[$round]['items'][5]['expect_count'] = $stat_results[$round]['items'][2]['expect_count'];
						$stat_results[$round]['items'][5]['actual_posts'] = $this->check_track_options( $stat_results[$round]['items'][4]['actual_posts'],0 );
						if( $check_blood ){
							$stat_results[$round]['items'][5]['actual_posts'] = $this->check_blood_options($stat_results[$round]['items'][5]['actual_posts']);
						}
						$stat_results[$round]['items'][5]['actual_count'] = count($stat_results[$round]['items'][5]['actual_posts']);
						break;

					default:
						# code...
						break;
				}
			}elseif( 'search' == $from ){
				if( !$is_limit_type ){
					for ($i=0; $i < 3; $i++) {
						switch ($i) {
							case 0:
								$start = strtotime( '-30 day',strtotime($start_date) );
								$end = strtotime( '-30 day',strtotime($end_date) );
								break;
							case 1:
								$start = strtotime( '-6 month',strtotime($start_date) );
								$end = strtotime( '-6 month',strtotime($end_date) );
								break;
							case 2:
								$start = strtotime( '-1 year',strtotime($start_date) );
								$end = strtotime( '-1 year',strtotime($end_date) );
								break;
							// case 3:
							// 	$start = strtotime( '-2 year',strtotime($start_date) );
							// 	$end = strtotime( '-2 year',strtotime($end_date) );
							// 	break;
							
							default:
								break;
						}

						$start = date('Y-m-d', $start );
						$end = date('Y-m-d', $end );

						$expect_items_arr = $this->get_users_by_date( $start,$end,$field_id );

						$stat_results[$round]['items'][$i]['expect_posts'] = $expect_items_arr;
						$stat_results[$round]['items'][$i]['expect_count'] = count($expect_items_arr);
						$stat_results[$round]['items'][$i]['actual_posts'] = $this->check_track_options( $expect_items_arr,$i,$check_options[$i]['has_checked'],$check_options[$i]['options'] );
						$stat_results[$round]['items'][$i]['actual_count'] = count($stat_results[$round]['items'][$i]['actual_posts']);
					}
				}else{
					//统计列表中带截止日期的
					if( -1 == $track_type){
						break;
					}

					$start = strtotime( '-'.$select_day.' day',strtotime($start_date) );
					$end = strtotime( '-'.$select_day.' day',strtotime($end_date) );
					$start = date('Y-m-d', $start );
					$end = date('Y-m-d', $end );

					$expect_items_arr = $this->get_users_by_date( $start,$end,$field_id );

					switch ($track_type) {
						case 0:
							//30天随访
							$stat_results[$round]['items'][0]['expect_posts'] = $expect_items_arr;
							$stat_results[$round]['items'][0]['expect_count'] = count($expect_items_arr);
							$stat_results[$round]['items'][0]['actual_posts'] = $this->check_track_options( $expect_items_arr,0,$check_options[0]['has_checked'],$check_options[0]['options'] );
							$stat_results[$round]['items'][0]['actual_count'] = count($stat_results[$round]['items'][0]['actual_posts']);
							break;
						case 1:
							//6个月随访
							$stat_results[$round]['items'][1]['expect_posts'] = $stat_results[$round]['items'][0]['expect_posts'] = $expect_items_arr;
							$stat_results[$round]['items'][0]['expect_count'] = $stat_results[$round]['items'][1]['expect_count'] = count($expect_items_arr);

							$stat_results[$round]['items'][0]['actual_posts'] = $this->check_track_options( $expect_items_arr,1,$check_options[1]['has_checked'],$check_options[1]['options'] );
							$stat_results[$round]['items'][0]['actual_count'] = count($stat_results[$round]['items'][0]['actual_posts']);

							//6个月的人中,做了30天随访的
							$stat_results[$round]['items'][1]['actual_posts'] = $this->check_track_options( $stat_results[$round]['items'][0]['actual_posts'],0,$check_options[0]['has_checked'],$check_options[0]['options'] );
							$stat_results[$round]['items'][1]['actual_count'] = count($stat_results[$round]['items'][1]['actual_posts']);
							break;
						case 2:
							$stat_results[$round]['items'][2]['expect_posts'] = $stat_results[$round]['items'][1]['expect_posts'] = $stat_results[$round]['items'][0]['expect_posts'] = $expect_items_arr;
							$stat_results[$round]['items'][0]['expect_count'] = $stat_results[$round]['items'][1]['expect_count'] = $stat_results[$round]['items'][2]['expect_count'] = count($expect_items_arr);

							$stat_results[$round]['items'][0]['actual_posts'] = $this->check_track_options( $expect_items_arr,2,$check_options[2]['has_checked'],$check_options[2]['options'] );
							$stat_results[$round]['items'][0]['actual_count'] = count($stat_results[$round]['items'][0]['actual_posts']);

							//1岁的人中,做了6个月随访的
							$stat_results[$round]['items'][1]['actual_posts'] = $this->check_track_options( $stat_results[$round]['items'][0]['actual_posts'],1,$check_options[1]['has_checked'],$check_options[1]['options'] );
							$stat_results[$round]['items'][1]['actual_count'] = count($stat_results[$round]['items'][1]['actual_posts']);

							//1岁的人中,做了6个月随访的,并做了30天随访的
							$stat_results[$round]['items'][2]['actual_posts'] = $this->check_track_options( $stat_results[$round]['items'][1]['actual_posts'],0,$check_options[0]['has_checked'],$check_options[0]['options'] );
							$stat_results[$round]['items'][2]['actual_count'] = count($stat_results[$round]['items'][2]['actual_posts']);
							break;
						case 3:
							# code...
							break;
						
						default:
							# code...
							break;
					}
				}
				
			}
		}

		return $stat_results;
	}

	function hospital_stat_result(){
		$temp_arr = array();
		date_default_timezone_set('PRC');
		$plugin = new HospitalTrack;
		$field_id = $plugin->get_field_id_by_name('分娩日期',$plugin->ent1_form_id);

		$date_arr = array();
		$i=$j=0;
		foreach ($_POST['data'] as $key => $value) {
			if( strpos( $value['name'], 'start_date' ) !== false ){
				$date_arr[$i]['start_date'] = $value['value'];
				$i++;
			}elseif( strpos( $value['name'], 'end_date' ) !== false ){
				$date_arr[$j]['end_date'] = $value['value'];
				$j++;
			}
			else{
				$temp_arr[$value['name']] = $value['value'];
			}
		}
		
		//echo '<pre>haha';print_r($temp_arr);echo '</pre>';exit();
		$date_limit = (int)$temp_arr['date_limit'];
		$columns_count = 2;

		if( $temp_arr['date_limit'] < 30 ){

		}elseif( $temp_arr['date_limit'] < 180 ){
			$columns_count = 0;
		}elseif( $temp_arr['date_limit'] < 365 ){
			$columns_count = 1;
		}elseif( $temp_arr['date_limit'] < 365*2 ){
			$columns_count = 2;
		}else{

		}

		for ($i=0; $i < self::$check_type; $i++) { 
			switch ($i) {
				case 0:case 1:case 3:
					$check_options[$i]['options'] = array();
					$check_options[$i]['has_checked'] = 0;
					for ($j=0; $j < self::$check_count1; $j++) { 
						$option_meta = 'hos_check'.$i.'_item'.$j;
						if( array_key_exists($option_meta, $temp_arr) ){
							$check_options[$i]['options'][] = $option_meta;
							$check_options[$i]['has_checked'] = 1;
						}
					}
					break;
				case 2:
					$check_options[$i]['options'] = array();
					$check_options[$i]['has_checked'] = 0;
					for ($j=0; $j < self::$check_count2; $j++) { 
						$option_meta = 'hos_check'.$i.'_item'.$j;
						if( array_key_exists($option_meta, $temp_arr) ){
							$check_options[$i]['options'][] = $option_meta;
							$check_options[$i]['has_checked'] = 1;
						}
					}
					break;
				default:
					# code...
					break;
			}
		}

		$stat_results = $plugin->get_stat_result_by_date_array( $date_arr,'search',0,$date_limit,false,$check_options );
		if( empty($stat_results) ){
			$html = __('没有任何符合的数据');
			echo $html;exit();
		}

		$is_limit_type = false;
		if( $date_limit > 0 ){
			$is_limit_type = true;
		}
		//echo '<pre>';print_r($stat_results);echo '</pre>';
		//}
		if( !$plugin->is_mobile ){
			$html = '<table class="hos_result_table"><tr>';
			if(!$is_limit_type){
				$table_title = __('时段');
			}else{
				$table_title = __('时段(满') . $date_limit .__('天)');
			}
			$html.= '<th rowspan="2">'.$table_title.'</th>';
			if(!$is_limit_type){
				$html.= '<th colspan="3">'.__('30天随访').'</th>';
				$html.= '<th colspan="3">'.__('6个月随访').'</th>';
				$html.= '<th colspan="3">'.__('1岁随访').'</th>';
			}else{
				switch ($columns_count) {
					case 0:
						$html.= '<th colspan="3">'.__('30天随访').'</th>';
						break;
					case 1:
						$html.= '<th colspan="3">'.__('6个月随访').'</th>';
						$html.= '<th colspan="3">'.__('30天+6个月').'</th>';
						break;
					case 2:
						$html.= '<th colspan="3">'.__('1岁随访').'</th>';
						$html.= '<th colspan="3">'.__('6个月+1岁').'</th>';
						$html.= '<th colspan="3">'.__('30天+6个月+1岁').'</th>';
						break;
					
					default:
						# code...
						break;
				}
			}
			$html.= '</tr><tr>';
			for ($i=0; $i <= $columns_count; $i++) { 
				$html.= '<td>'.__('应到人数').'</td>';
				$html.= '<td>'.__('实到人数').'</td>';
				$html.= '<td>'.__('随访率').'</td>';
			}
			$html.= '</tr>';

			foreach ($stat_results as $key => $result) {
				$html.= '<tr>';
				$html.= '<td>'.$result['start_date'].'—'.$result['end_date'].'</td>';

				foreach ($result['items'] as $key2 => $item) {
					if( 0 == $item['expect_count'] ){
						$rate = __('无');
					}else{
						$rate = round( $item['actual_count']*100/$item['expect_count'] ,2 );
						$rate .= '%'; 
					}

					$html.= '<td>'.$item['expect_count'].'</td>';
					$html.= '<td>'.$item['actual_count'].'</td>';
					$html.= '<td>'.$rate.'</td>';
				}
				$html.= '</tr>';
			}
			$html.= '</table>';
		}else{
			$html = '<div style="width:46%;display:-webkit-inline-box;"><table class="hos_result_table" style="width:100%;">';
			if(!$is_limit_type){
				$table_title = __('时段');
			}else{
				$table_title = __('时段(满') . $date_limit .__('天)');
			}
			$html.= '<tr><th height="55px;">'.$table_title.'</th></tr>';

			foreach ($stat_results as $key => $result) {
				$html.= '<tr>';
				$html.= '<td>'.date( 'y/m/d',strtotime($result['start_date']) ).'-'.date( 'y/m/d',strtotime($result['end_date']) ).'</td>';
				$html.= '</tr>';
			}
			$html.= '</table></div>';

			$html.= '<div class="swiper-container swiper-container-search"><div class="swiper-wrapper">';

			if(!$is_limit_type){
				$title_arr = array( __('30天随访'),__('6个月随访'),__('1岁随访') );
			}else{
				switch ($columns_count) {
					case 0:
						$title_arr = array( __('30天随访') );
						break;
					case 1:
						$title_arr = array( __('6个月随访'),__('30天+6个月') );
						break;
					case 2:
						$title_arr = array( __('1岁随访'),__('6个月+1岁'),__('30天+6个月+1岁') );
						break;
					
					default:
						# code...
						break;
				}
			}

			for ($j=0; $j < count($title_arr); $j++) { 
				$html.= '<div class="swiper-slide"><table class="hos_result_table" style="width:100%;"><tr>';
				$html.= '<th colspan="3">'.$title_arr[$j].'</th>';
				$html.= '</tr><tr>';
				$html.= '<td>'.__('应到').'</td>';
				$html.= '<td>'.__('实到').'</td>';
				$html.= '<td>'.__('随访率').'</td>';
				$html.= '</tr>';

				foreach ($stat_results as $key => $result) {
					$html.= '<tr>';
					$k = 0;

					foreach ($result['items'] as $key2 => $item) {
						if( $k == 1 )
							break; 
						if( 0 == $item['expect_count'] ){
							$rate = __('无');
						}else{
							$rate = round( $item['actual_count']*100/$item['expect_count'] ,2 );
							$rate .= '%'; 
						}

						$html.= '<td>'.$item['expect_count'].'</td>';
						$html.= '<td>'.$item['actual_count'].'</td>';
						$html.= '<td>'.$rate.'</td>';
						unset($stat_results[$key]['items'][$key2]);
						$k++;
					}
					$html.= '</tr>';
				}
				$html.= '</table></div>';
			}

			$html.= '</div>';
			$html.= '<div class="swiper-button-next swiper-button-next-search"></div><div class="swiper-button-prev swiper-button-prev-search"></div>';
			$html.= '</div>';
		}
		

		echo $html;exit();

	}

	function get_field_id_by_name($name, $form_id){
		$form_fields = $this->get_form_info( $form_id);
		foreach ($form_fields as $key => $value) {
			if( $name == $value['data']['label'] ){
				return $value['id'];
			}
		}
	}

	function get_users_by_date( $start_date,$end_date,$field_id ){
		global $wpdb,$table_prefix;

		$field_key = '_field_'.$field_id;
		$form_id = $this->ent1_form_id;
		$post_table = $table_prefix . 'posts';
		$postmeta_table = $table_prefix . 'postmeta';

		$sql = "SELECT post_id FROM $postmeta_table WHERE meta_key = '".$field_key."' AND meta_value between '" . $start_date . "' AND '".$end_date."'";
		$sql .= " AND post_id IN (SELECT post_id FROM $postmeta_table WHERE meta_key = '_form_id' AND meta_value=".$form_id.")";

		$result = $wpdb->get_results( $sql ,ARRAY_A );
		$result = self::fill_array_with_field( $result ,'post_id' );

		return $result;
	}

	function final_stat_results(){
		date_default_timezone_set('PRC');
		$start = strtotime('2014-11-03');
		$end = time();

		$date_arr = array();
		$i = 0;
		while( $start <= $end ) {

			$start1 = date('Y-m-01', $start);
			$start2 = strtotime( '+1 month',strtotime($start1) );
			$end1  = date('Y-m-t', $start );
			if( 0 == $i){
				$date_arr[$i]['start_date'] = date('Y-m-d', $start);
			}else{
				$date_arr[$i]['start_date'] = $start1;
			}
			
			if( strtotime($end1) > $end){
				$date_arr[$i]['end_date'] = date('Y-m-d', $end);
			}else{
				$date_arr[$i]['end_date'] = $end1;
			}
			$start = $start2;
			$i++;
		}
		//echo '<pre>';print_r($date_arr);echo '</pre>';
		$stat_results1 = $this->get_stat_result_by_date_array( $date_arr,'init',1 );
		//echo '<pre>';print_r($stat_results1);echo '</pre>';exit();

		$start = strtotime('2014-11-03');
		$end = time();
		$end_ini = strtotime('2015-02-28');

		$date_arr = array();
		$i = 0;
		while( $end_ini <= $end ) {

			$end1 = date('Y-m-01', $end_ini);
			$end2 = strtotime( '+1 month',strtotime($end1) );

			$date_arr[$i]['start_date'] = date('Y-m-d', $start);

			if( strtotime( date( 'Y-m-t', strtotime($end1) ) ) > $end ){
				$date_arr[$i]['end_date'] = date('Y-m-d', $end);
			}else{
				$date_arr[$i]['end_date'] = date( 'Y-m-t', strtotime($end1) );
			}
			$end_ini = $end2;
			$i++;
		}

		$stat_results2 = $this->get_stat_result_by_date_array( $date_arr,'init',2 );
		$stat_results3 = $this->get_stat_result_by_date_array( $date_arr,'init',3,0,true );

		if( isset($_GET['action']) ){

			ob_end_clean();
			require_once( plugin_dir_path( __FILE__ ) .'class/PHPExcel.php');

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);

			$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', __('时段'));
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			switch ($_GET['action']) {
				case 'export1':
					$objPHPExcel->getActiveSheet()->setCellValue('A1', __('时段'));
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->mergeCells('B1:D1')->mergeCells('E1:G1')->mergeCells('H1:J1');
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$objPHPExcel->getActiveSheet()->setCellValue('B1', __('30天随访'));
					$objPHPExcel->getActiveSheet()->setCellValue('E1', __('6个月随访'));
					$objPHPExcel->getActiveSheet()->setCellValue('H1', __('1岁随访'));

					$tmp_arr = array( 'B2','C2','D2','E2','F2','G2','H2','I2','J2' );

					foreach ($tmp_arr as $key => $value) {
						if( $key%3 == 0 )
							$objPHPExcel->getActiveSheet()->setCellValue($value, __('应到人数'));
						if( $key%3 == 1 )
							$objPHPExcel->getActiveSheet()->setCellValue($value, __('实到人数'));
						if( $key%3 == 2 )
							$objPHPExcel->getActiveSheet()->setCellValue($value, __('随访率'));
						$objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}

					$file_name = __('随访率统计');
					$stat_results = $stat_results1;
					break;
				case 'export2':case 'export3':
					if( $_GET['action'] == 'export3' )
						$objPHPExcel->getActiveSheet()->setCellValue('A1', __('时段(有三期血)'));
					
					$objPHPExcel->getActiveSheet()->mergeCells('B1:C1')->mergeCells('D1:E1')->mergeCells('F1:G1')->mergeCells('H1:I1')->mergeCells('J1:K1')->mergeCells('L1:M1');
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$objPHPExcel->getActiveSheet()->setCellValue('B1', __('30天随访'));
					$objPHPExcel->getActiveSheet()->setCellValue('D1', __('6个月随访'));
					$objPHPExcel->getActiveSheet()->setCellValue('F1', __('1岁随访'));
					$objPHPExcel->getActiveSheet()->setCellValue('H1', __('30天+6个月'));
					$objPHPExcel->getActiveSheet()->setCellValue('J1', __('6个月+1岁'));
					$objPHPExcel->getActiveSheet()->setCellValue('L1', __('30天+6个月+1岁'));

					$tmp_arr = array( 'B2','C2','D2','E2','F2','G2','H2','I2','J2','K2','L2','M2' );

					foreach ($tmp_arr as $key => $value) {
						if( $key%2 == 0 )
							$objPHPExcel->getActiveSheet()->setCellValue($value, __('应到人数'));
						if( $key%2 == 1 )
							$objPHPExcel->getActiveSheet()->setCellValue($value, __('实到人数'));
						
						$objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}

					$file_name = __('随访统计');
					$stat_results = $stat_results2;
					if( $_GET['action'] == 'export3' ){
						$file_name = __('随访统计(有三期血)');
						$stat_results = $stat_results3;
					}
					break;
				
				default:
					//# code...
					break;
			}

			$start_num = 3;
			foreach ($stat_results as $key => $result) {
				$start_str = 'A';
				if( $_GET['action'] == 'export1' ){
					$date_area = $result['start_date'].'—'.$result['end_date'];
				}else{
					$date_area = __('截至').$result['end_date'];
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue( $start_str.$start_num, $date_area )->getStyle($start_str.$start_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				foreach ($result['items'] as $item) {
					if( 0 == $item['expect_count'] ){
						$rate = __('无');
					}else{
						$rate = round( $item['actual_count']*100/$item['expect_count'] ,2 );
						$rate .= '%'; 
					}
					$objPHPExcel->getActiveSheet()->setCellValue( ++$start_str.$start_num, $item['expect_count'] )->getStyle($start_str.$start_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->setCellValue( ++$start_str.$start_num, $item['actual_count'] )->getStyle($start_str.$start_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);;
					if( $_GET['action'] == 'export1' )
						$objPHPExcel->getActiveSheet()->setCellValue( ++$start_str.$start_num, $rate )->getStyle($start_str.$start_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				}

				$start_num++;
			}

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			header("Content-type:text/octect-stream;charset=utf-8");
			header('Content-Disposition:attachment;filename="'.$file_name.'.xls"');
			$objWriter->save('php://output');
			exit();
		}

		$url1 = admin_url( 'admin.php?page=hospital_track&action=export1' );
		$url2 = admin_url( 'admin.php?page=hospital_track&action=export2' );
		$url3 = admin_url( 'admin.php?page=hospital_track&action=export3' );
		if( !$this->is_mobile ){
			//echo '<pre>';print_r($date_arr);echo '</pre>';
			$html = '<br /><br /><div><a class="button" href="'.$url1.'">'.__('导出表单').'</a><br /><br />';
			$html.= '<table class="hos_result_table"><tr>';
			$html.= '<th rowspan="2">'.__('时段').'</th>';
			$html.= '<th colspan="3">'.__('30天随访').'</th>';
			$html.= '<th colspan="3">'.__('6个月随访').'</th>';
			$html.= '<th colspan="3">'.__('1岁随访').'</th>';

			$html.= '</tr><tr>';
			for ($i=0; $i < 3; $i++) { 
				$html.= '<td>'.__('应到人数').'</td>';
				$html.= '<td>'.__('实到人数').'</td>';
				$html.= '<td>'.__('随访率').'</td>';
			}
			$html.= '</tr>';

			foreach ($stat_results1 as $key => $result) {
				$html.= '<tr>';
				$html.= '<td>'.$result['start_date'].'—'.$result['end_date'].'</td>';
				
				foreach ($result['items'] as $key2 => $item) {
					if( 0 == $item['expect_count'] ){
						$rate = __('无');
					}else{
						$rate = round( $item['actual_count']*100/$item['expect_count'] ,2 );
						$rate .= '%'; 
					}

					$html.= '<td>'.$item['expect_count'].'</td>';
					$html.= '<td>'.$item['actual_count'].'</td>';
					$html.= '<td>'.$rate.'</td>';
				}
				$html.= '</tr>';
			}
			$html.= '</table></div>';
			echo $html;
			
			$html = '<br /><br /><div><a class="button" href="'.$url2.'">'.__('导出表单').'</a><br /><br />';
			$html.= '<table class="hos_result_table"><tr>';
			$html.= '<th rowspan="2">'.__('时段').'</th>';
			$html.= '<th colspan="3">'.__('30天随访').'</th>';
			$html.= '<th colspan="3">'.__('6个月随访').'</th>';
			$html.= '<th colspan="3">'.__('1岁随访').'</th>';
			$html.= '<th colspan="3">'.__('30天+6个月').'</th>';
			$html.= '<th colspan="3">'.__('6个月+1岁').'</th>';
			$html.= '<th colspan="3">'.__('30天+6个月+1岁').'</th>';

			$html.= '</tr><tr>';
			for ($i=0; $i < 6; $i++) { 
				$html.= '<td>'.__('应到人数').'</td>';
				$html.= '<td>'.__('实到人数').'</td>';
				$html.= '<td>'.__('随访率').'</td>';
			}
			$html.= '</tr>';

			foreach ($stat_results2 as $key => $result) {
				$html.= '<tr>';
				$html.= '<td>'.__('截至').$result['end_date'].'</td>';
				
				foreach ($result['items'] as $key2 => $item) {
					if( 0 == $item['expect_count'] ){
						$rate = __('无');
					}else{
						$rate = round( $item['actual_count']*100/$item['expect_count'] ,2 );
						$rate .= '%'; 
					}

					$html.= '<td>'.$item['expect_count'].'</td>';
					$html.= '<td>'.$item['actual_count'].'</td>';
					$html.= '<td>'.$rate.'</td>';
				}
				$html.= '</tr>';
			}
			$html.= '</table></div>';
			echo $html;

			$html = '<br /><br /><div><a class="button" href="'.$url3.'">'.__('导出表单').'</a><br /><br />';
			$html.= '<table class="hos_result_table"><tr>';
			$html.= '<th rowspan="2">'.__('时段(有三期血)').'</th>';
			$html.= '<th colspan="3">'.__('30天随访').'</th>';
			$html.= '<th colspan="3">'.__('6个月随访').'</th>';
			$html.= '<th colspan="3">'.__('1岁随访').'</th>';
			$html.= '<th colspan="3">'.__('30天+6个月').'</th>';
			$html.= '<th colspan="3">'.__('6个月+1岁').'</th>';
			$html.= '<th colspan="3">'.__('30天+6个月+1岁').'</th>';

			$html.= '</tr><tr>';
			for ($i=0; $i < 6; $i++) { 
				$html.= '<td>'.__('应到人数').'</td>';
				$html.= '<td>'.__('实到人数').'</td>';
				$html.= '<td>'.__('随访率').'</td>';
			}
			$html.= '</tr>';

			foreach ($stat_results3 as $key => $result) {

				$html.= '<tr>';
				$html.= '<td>'.__('截至').$result['end_date'].'</td>';
				
				foreach ($result['items'] as $key2 => $item) {
					if( 0 == $item['expect_count'] ){
						$rate = __('无');
					}else{
						$rate = round( $item['actual_count']*100/$item['expect_count'] ,2 );
						$rate .= '%'; 
					}

					$html.= '<td>'.$item['expect_count'].'</td>';
					$html.= '<td>'.$item['actual_count'].'</td>';
					$html.= '<td>'.$rate.'</td>';
				}
				$html.= '</tr>';
			}
			$html.= '</table></div>';
			echo $html;
		}else{
			for ($i=1; $i < 4; $i++) { 
				$html = '<br /><br /><div style="width:45%;display:-webkit-inline-box;"><table class="hos_result_table" style="width:100%;">';
				$html.= '<tr><th height="53px;">'.__('时段').'</th></tr>';

				switch ($i) {
					case 1:
						$stat_results = $stat_results1;
						break;
					case 2:
						$stat_results = $stat_results2;
						break;
					case 3:
						$stat_results = $stat_results3;
						break;
					
					default:
						# code...
						break;
				}

				foreach ($stat_results as $key => $result) {
					$html.= '<tr>';
					if( 1 == $i ){
						$html.= '<td>'.date( 'y/m/d',strtotime($result['start_date']) ).'-'.date( 'y/m/d',strtotime($result['end_date']) ).'</td>';
					}else{
						$html.= '<td>'.__('截至').date( 'y/m/d',strtotime($result['end_date']) ).'</td>';
					}
					
					$html.= '</tr>';
				}
				$html.= '</table></div>';

				$html.= '<div class="swiper-container swiper-container'.$i.'"><div class="swiper-wrapper">';
				if( 1 == $i ){
					$title_arr = array( __('30天随访'),__('6个月随访'),__('1岁随访') );
				}else{
					$title_arr = array( __('30天随访'),__('6个月随访'),__('1岁随访'),__('30天+6个月'),__('6个月+1岁'),__('30天+6个月+1岁') );
				}

				for ($j=0; $j < count($title_arr); $j++) { 
					$html.= '<div class="swiper-slide"><table class="hos_result_table" style="width:100%;"><tr>';
					$html.= '<th colspan="3">'.$title_arr[$j].'</th>';
					$html.= '</tr><tr>';
					$html.= '<td>'.__('应到').'</td>';
					$html.= '<td>'.__('实到').'</td>';
					$html.= '<td>'.__('随访率').'</td>';
					$html.= '</tr>';

					foreach ($stat_results as $key => $result) {
						$html.= '<tr>';
						$k = 0;

						foreach ($result['items'] as $key2 => $item) {
							if( $k == 1 )
								break; 
							if( 0 == $item['expect_count'] ){
								$rate = __('无');
							}else{
								$rate = round( $item['actual_count']*100/$item['expect_count'] ,2 );
								$rate .= '%'; 
							}

							$html.= '<td>'.$item['expect_count'].'</td>';
							$html.= '<td>'.$item['actual_count'].'</td>';
							$html.= '<td>'.$rate.'</td>';
							unset($stat_results[$key]['items'][$key2]);
							$k++;
						}
						$html.= '</tr>';
					}
					$html.= '</table></div>';
				}
				$html.= '</div>';
				$html.= '<div class="swiper-button-next swiper-button-next'.$i.'"></div><div class="swiper-button-prev swiper-button-prev'.$i.'"></div>';
				$html.= '</div>';

				$html.= '<div class="clear"></div>';
				echo $html;
			}
		}
		
	}

	function init(){

		$labels = array(
	        'name'               => __( 'Tables' ),
	        'singular_name'      => __( 'Table' ),
	        'menu_name'          => __( 'Tables' ),
	        'name_admin_bar'     => __( 'Table' ),
	        'add_new'            => __( 'Add New' ),
	        'add_new_item'       => __( 'Add New Table' ),
	        'new_item'           => __( 'New Table' ),
	        'edit_item'          => __( 'Edit Table' ),
	        'view_item'          => __( 'View Table' ),
	        'all_items'          => __( 'All Table' ),
	        'search_items'       => __( 'Search Table' ),
	        'parent_item_colon'  => __( 'Parent Table:' ),
	        'not_found'          => __( 'No books found.' ),
	        'not_found_in_trash' => __( 'No books found in Trash.' )
	    );
	 
	    $args = array(
	        'labels'             => $labels,
	        'public'             => true,
	        'show_ui'            => true,
	        'show_in_menu'       => true,
	        'query_var'          => true,
	        'rewrite'            => array( 'slug' => 'hos_tab' ),
	        'capability_type'    => 'post',
	        'has_archive'        => true,
	        'hierarchical'       => false,
	        'menu_position'      => null,
	        'supports'           => array( 'title', 'editor' )
	    );

	    register_post_type( 'hos_tab', $args );
	}

	function hos_test(){
		global $wpdb,$table_prefix;

		$field_key = '_field_'.$field_id;
		$form_id = $this->ent1_form_id;
		$post_table = $table_prefix . 'posts';
		$postmeta_table = $table_prefix . 'postmeta';

		$message = '';

		$result = $wpdb->get_results( $sql ,ARRAY_A );
		$result = self::fill_array_with_field( $result ,'post_id' );

		if( isset($_POST['action']) ){
			if ( $_POST['action'] == 'delete' && $_POST['table_no'] > 0 ) {
				$table_no = $_POST['table_no'];

				$sql = "DELETE FROM $postmeta_table WHERE post_id = '".$table_no."'";
				$wpdb->query($sql);

				$sql = "DELETE FROM $post_table WHERE ID = '".$table_no."'";
				$wpdb->query($sql);

				// $sql = "DELETE FROM $postmeta_table WHERE post_id in (SELECT post_id FROM $postmeta_table where meta_key = 'hos_tab_no' and meta_value='$table_no')";
				// $wpdb->query($sql);

				$sql = "DELETE FROM $post_table WHERE ID in (SELECT post_id FROM $postmeta_table where meta_key = 'hos_tab_no' and meta_value='$table_no')";
				$wpdb->query($sql);

				$message = '完成删除';
			}

			if ( $_POST['action'] == 'insert' ){
				$sql = $wpdb->prepare("SELECT p.ID as post_id FROM " . $post_table . " AS p LEFT JOIN ".$postmeta_table." AS m ON p.ID = m.post_id WHERE p.post_type='nf_sub' AND m.meta_key='_form_id' AND m.meta_value=%d" ,$form_id );

				$result = $wpdb->get_results( $sql, 'ARRAY_A' );
				$result = self::fill_array_with_field( $result ,'post_id' );

				foreach ($result as $post_id) {
					for ($i=0; $i < 3; $i++) { 
						for ($j=0; $j < 6; $j++) { 
							$option_meta = 'hos_tel'.$i.'_item'.$j;
							if( $j%2 == 0 ){
								$option_value = rand(1,8);
							}else{
								$option_value = rand(0,1);
							}
							update_post_meta( $post_id,$option_meta,$option_value );
						}
					}
					
					for ($i=0; $i < 4; $i++) { 
						$count = 5;
						if( 2 == $i ){
							$count = 7;
						}
						for ($j=0; $j < $count; $j++) { 
							$option_meta2 = 'hos_check'.$i.'_item'.$j;
							$option_value2 = rand(0,1);
							update_post_meta( $post_id,$option_meta2,$option_value2 );
						}
					}

					update_post_meta( $post_id,'SerumT1',rand(0,1) );
					update_post_meta( $post_id,'SerumT2',rand(0,1) );
					update_post_meta( $post_id,'SerumT3',rand(0,1) );
					update_post_meta( $post_id,'T1+T2+T3',rand(0,1) );
		
				}
				$message = '完成随机数据的插入';
			}
		}

		$table_arr = $this->getAllTableId($this->ent1_form_id);
		$html = '<br /><br /><br /><p class="updated">'.$message.'</p><br />';

		$html.= '<div><form method="post">';
		$html.= '<select name="table_no" id="table_no">';
		$html.= '<option value="0">'.__('表号').'</option>';

		foreach ($table_arr as $table) {
			$table_name = get_post_meta( $table['post_id'],'hos_table_name',true );
			$html.= '<option value="'.$table['post_id'].'">'.$table_name.'</option>';
		}

		$html.= '</select>&nbsp;&nbsp;&nbsp;&nbsp;';
		$html.= '<input type="hidden" value="delete" name="action" />';
		$html.= '<input type="submit" value="'.__('删除该表').'" />';
		$html.= '</form></div>';

		$html.= '<br /><br /><br /><div><form method="post">';
		$html.= '<input type="hidden" value="insert" name="action" />';
		$html.= '<input type="submit" value="'.__('随机插入数据').'" />';
		$html.= '</form></div>';

		echo $html;
	}

	function ent1_muti_import(){
		$action = 'ent1';
		$this->muti_import($action);
	}
	function nonent1_muti_import(){
		$action = 'nonent1';
		$this->muti_import($action);
	}
	function three_blood_muti_import(){
		$action = 'three_blood';
		$this->muti_import($action);
	}

	function get_field_options($form_id){
		$form_fields = $this->get_form_info($form_id);
        $field_names = array();

        $i=0;
        foreach ($form_fields as $key => $form_field){
            if( $i == 0 ){
                $i++;
                continue;
            }
            if( $i == count($form_fields)-1 )
                break;

            $field_names[$form_field['id']] = trim($form_field['data']['label']);
            //$file->error.=$form_field['data']['label'];
            $i++;
        }
        return $field_names; 
	}

	function muti_import($action){

		wp_enqueue_script( 'jquery-ui-widget-script', plugin_dir_url( __FILE__ ) . 'js/jquery.ui.widget.js' );
		wp_enqueue_script( 'blueimp-mix-script', plugin_dir_url( __FILE__ ) . 'js/blueimp.mix.js' );
		wp_enqueue_script( 'hospital-upload-main-script', plugin_dir_url( __FILE__ ) . 'js/hos_upload_main.js' );
		wp_enqueue_script( 'jquery-fileupload-mix-script', plugin_dir_url( __FILE__ ) . 'js/jquery.fileupload.mix.js' );
		wp_enqueue_script( 'jquery-bootstrap-min-script', 'http://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js' );

		wp_enqueue_style( 'bootstrap-min-css', 'http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css' );
		wp_enqueue_style( 'jquery-fileupload-mix-css', plugin_dir_url( __FILE__ ) . 'css/jquery.fileupload.mix.css' );

		switch ($action) {
			case 'ent1':
				$head = __('Ent1数据导入');
				$ajax_action = 'hos-non1-muti-upload-ajax';
				$view_file = 'ent1-muti-upload-example.php';
				$form_id = $this->ent1_form_id;
				break;
			case 'nonent1':
				$head = __('NonEnt1数据导入');
				$ajax_action = 'hos-nonent1-muti-upload-ajax';
				$view_file = 'nonent1-muti-upload-example.php';
				$form_id = $this->non_ent1_form_id;
				break;
			case 'three_blood':
				$head = __('三期血数据导入');
				$ajax_action = 'hos-three-blood-muti-upload-ajax';
				$view_file = 'three-blood-muti-upload-example.php';
				$form_id = $this->blood_form_id;
				break;
			
			default:
				# code...
				break;
		}

		$field_names = $this->get_field_options($form_id);
		ob_start();
		include_once( plugin_dir_path( __FILE__ ) . 'views/' . $view_file );
		$view = ob_get_contents();
		ob_end_clean();
?>
    <div class="container">
	    <h1><?php echo $head; ?></h1>
	    <br>
	    <blockquote class="muti_upload">
	        <p>在上传文件前,请按照对应的例表排列成相对应的格式,支持xlsx,xls,csv文件格式的上传.<br>
	        可以通过点击上传,完成多个文件的上传,或者将需要上传的文件拖拽到现所在区域.<br>
	        </p>
	    </blockquote>
	    <br>
	    <!-- The file upload form used as target for the file upload widget -->
	    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
	        <div class="row fileupload-buttonbar">
	            <div class="col-lg-7">
	                <!-- The fileinput-button span is used to style the file input field as button -->
	                <span class="btn btn-success fileinput-button">
	                    <i class="glyphicon glyphicon-plus"></i>
	                    <span>上传文件...</span>
	                    <input type="file" name="files[]" multiple>
	                </span>
	                <button type="submit" class="btn btn-primary start">
	                    <i class="glyphicon glyphicon-upload"></i>
	                    <span>开始上传</span>
	                </button>
	                <button type="reset" class="btn btn-warning cancel">
	                    <i class="glyphicon glyphicon-ban-circle"></i>
	                    <span>取消上传</span>
	                </button>
	                <!-- The global file processing state -->
	                <span class="fileupload-process"></span>
	            </div>
	            <div class="col-lg-5 fileupload-progress fade">
	                <!-- The global progress bar -->
	                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
	                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
	                </div>
	                <!-- The extended global progress state -->
	                <div class="progress-extended">&nbsp;</div>
	            </div>
	        </div>
	        <!-- The table listing the files available for upload/download -->
	        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
	        <input type="hidden" name="action" id="hos_muti_upload" value="<?php echo $ajax_action; ?>" />
	        <input type="hidden" name="style" id="hos_muti_style" value="<?php echo $action; ?>" />
	    </form>
	    <br>
	    <div class="panel panel-default">
	        <div class="panel-heading">
	            <h3 class="panel-title">文件内容格式示例</h3>
	        </div>
	        <div class="panel-body">
	            <?php echo $view; ?>
	        </div>
	    </div>
	</div>

	<!-- The template to display files available for upload -->
	<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-upload fade">
	        <td>
	            <p class="name">{%=file.name%}</p>
	            <strong class="error text-danger"></strong>
	        </td>
	        <td>
	            <p class="size">上传中...</p>
	            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
	        </td>
	        <td>
	            {% if (!i && !o.options.autoUpload) { %}
	                <button class="btn btn-primary start" disabled>
	                    <i class="glyphicon glyphicon-upload"></i>
	                    <span>开始</span>
	                </button>
	            {% } %}
	            {% if (!i) { %}
	                <button class="btn btn-warning cancel">
	                    <i class="glyphicon glyphicon-ban-circle"></i>
	                    <span>取消</span>
	                </button>
	            {% } %}
	        </td>
	    </tr>
	{% } %}
	</script>
	<!-- The template to display files available for download -->
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-download fade">
	        <td>
	            <p class="name">
	                {% if (file.url) { %}
	                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
	                {% } else { %}
	                    <span>{%=file.name%}</span>
	                {% } %}
	            </p>
	            {% if (file.error) { %}
	                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
	            {% } %}
	        </td>
	        <td>
	            <span class="size">{%=o.formatFileSize(file.size)%}</span>
	        </td>
	        <td>
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
	        </td>
	    </tr>
	{% } %}
	</script>
<?php		
	}

	function hospital_muti_upload(){
		$plugin = new HospitalTrack;

		$upload_handler = new Hospital_Upload_Handler();
		exit();
	}
}

function stacktech_init_hospital_track() {  
	$plugin = new HospitalTrack;

	wp_enqueue_script( 'hospital-track-jQuery-script', plugin_dir_url( __FILE__ ) . 'js/jquery-1.11.3.min.js' );
    wp_enqueue_script( 'hospital-track-ui-script', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js' );
    wp_enqueue_script( 'hospital-track-script', plugin_dir_url( __FILE__ ) . 'js/hospital_track.js' );
    //wp_enqueue_script( 'hospital-bootstrap-min-script', 'http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js' );
    wp_enqueue_script( 'hospital-swiper-min-script', plugin_dir_url( __FILE__ ) . 'js/swiper.min.js' );

    wp_enqueue_style( 'hospital-track-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css' );
    wp_enqueue_style( 'hospital-track-css', plugin_dir_url( __FILE__ ) . 'css/hospital-track.css' );
    //wp_enqueue_style( 'hospital-bootcss-css', 'http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css' );
    wp_enqueue_style( 'hospital-swiper-min-css', plugin_dir_url( __FILE__ ) . 'css/swiper.min.css' );

    add_menu_page( __('医院随访管理'), __('医院随访管理'), "manage_options", 'hospital_track', array($plugin, 'final_stat_results'),'dashicons-welcome-view-site
' );
    add_submenu_page( 'hospital_track', __('统计列表'), __('统计列表'), 'manage_options', 'hos_stat' ,array($plugin, 'hos_stat') );
    add_submenu_page( 'hospital_track', __('Ent1导入'), __('Ent1导入'), "manage_options", 'ent1_import', array($plugin,'admin_init') );
    add_submenu_page( 'hospital_track', __('NonEnt1导入'), __('NonEnt1导入'), "manage_options", 'nonent1_import', array($plugin,'admin_init') );
    add_submenu_page( 'hospital_track', __('三期血导入'), __('三期血导入'), "manage_options", 'three_blood_import', array($plugin,'admin_init') );
    add_submenu_page( 'hospital_track', __('随访情况数据导入'), __('随访情况数据导入'), "manage_options", 'track_info_import', array($plugin,'track_info_import') );

    add_submenu_page( 'hospital_track', __('Ent1多文件导入'), __('Ent1多文件导入'), "manage_options", 'ent1_muti_import', array($plugin,'ent1_muti_import') );
    add_submenu_page( 'hospital_track', __('NonEnt1多文件导入'), __('NonEnt1多文件导入'), "manage_options", 'nonent1_muti_import', array($plugin,'nonent1_muti_import') );
    add_submenu_page( 'hospital_track', __('三期血多文件导入'), __('三期血多文件导入'), "manage_options", 'three_blood_muti_import', array($plugin,'three_blood_muti_import') );

    //add_submenu_page( 'hospital_track', __('医院数据表'), __('医院数据表'), 'manage_options', 'edit.php?post_type=nf_sub' );
    add_submenu_page( null, __('电话通知列表'), __('电话通知列表'), 'manage_categories', 'tel_list' ,array($plugin, 'tel_list') );
    add_submenu_page( null, __('随访调查列表'), __('随访调查列表'), 'manage_categories', 'track_list' ,array($plugin, 'track_list') );
    add_submenu_page( 'hospital_track', __('预警手机邮件设置'), __('预警手机邮件设置'), 'manage_options', 'email_phone' ,array($plugin, 'email_and_phone') );
    
    add_submenu_page( 'hospital_track', __('数据调试'), __('数据调试'), 'manage_network', 'hos_test' ,array($plugin, 'hos_test') );
    add_action( 'wp_dashboard_setup', array( $plugin,'load_widgets' ) );

    if( !current_user_can('manage_options') ){
		remove_menu_page('hospital_track');
    }

}

add_action('admin_menu', 'stacktech_init_hospital_track');
//add_action('init', array( 'HospitalTrack','init') );
add_action( 'wp_ajax_hospital-track-ignore-ajax', array( 'HospitalTrack','hospital_track_ignore_ajax') );

add_action( 'wp_ajax_hospital-track-info-ajax', array( 'HospitalTrack','hospital_track_info_save') );
add_action( 'wp_ajax_hospital-single-option-save-ajax', array( 'HospitalTrack','hospital_single_option_save') );

add_action( 'wp_ajax_hospital-track-tel-ajax', array( 'HospitalTrack','hospital_track_tel_save') );
add_action( 'wp_ajax_hospital-stat-result-ajax', array( 'HospitalTrack','hospital_stat_result') );
add_action( 'wp_ajax_hos-non1-muti-upload-ajax', array( 'HospitalTrack','hospital_muti_upload') );
add_action( 'wp_ajax_hos-nonent1-muti-upload-ajax', array( 'HospitalTrack','hospital_muti_upload') );
add_action( 'wp_ajax_hos-three-blood-muti-upload-ajax', array( 'HospitalTrack','hospital_muti_upload') );