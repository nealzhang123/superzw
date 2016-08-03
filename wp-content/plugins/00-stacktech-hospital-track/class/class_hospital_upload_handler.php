<?php
/*
 * jQuery File Upload Plugin PHP Class 8.1.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

class Hospital_Upload_Handler
{

    protected $options;

    // PHP File Upload error message codes:
    // http://php.net/manual/en/features.file-upload.errors.php
    protected $error_messages = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => '文件超过1M,无法上传',
        'min_file_size' => '文件大小不足,确认文件是否正确',
        'accept_file_types' => '文件类型不符合',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'abort' => '文件上传出错',
    );

    function __construct($options = null, $initialize = true, $error_messages = null) {
        $this->options = array(
            'script_url' => $this->get_full_url().'/',
            'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/files/',
            'upload_url' => $this->get_full_url().'/files/',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ),
            'access_control_allow_headers' => array(
                'Content-Type',
                'Content-Range',
                'Content-Disposition'
            ),
            // Enable to provide file downloads via GET requests to the PHP script:
            //     1. Set to 1 to download files via readfile method through PHP
            //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
            //     3. Set to 3 to send a X-Accel-Redirect header for nginx
            // If set to 2 or 3, adjust the upload_url option to the base path of
            // the redirect parameter, e.g. '/files/'.
            'download_via_php' => false,
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.(xls(x)?|csv)$/i',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => 50000000,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Defines which files are handled as image files:
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Use exif_imagetype on all files to correct file extensions:
            'correct_image_extensions' => false,
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            // set to 2 to use the ImageMagick convert binary directly:
            'image_library' => 1,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
            'imagick_resource_limits' => array(
                imagick::RESOURCETYPE_MAP => 32,
                imagick::RESOURCETYPE_MEMORY => 32
            ),
            */
            // Command or path for to the ImageMagick convert binary:
            'convert_bin' => 'convert',
            // Uncomment the following to add parameters in front of each
            // ImageMagick convert call (the limit constraints seem only
            // to have an effect if put in front):
            /*
            'convert_params' => '-limit memory 32MiB -limit map 32MiB',
            */
            // Command or path for to the ImageMagick identify binary:
            'identify_bin' => 'identify',
            'image_versions' => array(
                // The empty image version key defines options for the original image:
                '' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ),
                // Uncomment the following to create medium sized images:
                /*
                'medium' => array(
                    'max_width' => 800,
                    'max_height' => 600
                ),
                */
                'thumbnail' => array(
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    //'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
                    //'upload_url' => $this->get_full_url().'/thumb/',
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    //'crop' => true,
                    'max_width' => 80,
                    'max_height' => 80
                )
            )
        );
        if ($options) {
            $this->options = $options + $this->options;
        }
        if ($error_messages) {
            $this->error_messages = $error_messages + $this->error_messages;
        }
        if ($initialize) {
            $this->initialize();
        }
    }

    protected function initialize() {
        switch ($this->get_server_var('REQUEST_METHOD')) {
            case 'GET':
                break;
            case 'PATCH':
            case 'PUT':
            case 'POST':
                $this->post();
                break;
            default:
                $this->header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    protected function validate($file, $error, $index) {
        if ($error) {
            $file->error = $this->get_error_message($error);
            return false;
        }
        
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = $this->get_error_message('accept_file_types');
            return false;
        }
        
        if ($this->options['max_file_size'] && $file->size > $this->options['max_file_size']
            ) {
            $file->error = $this->get_error_message('max_file_size');
            return false;
        }
        
        return true;
    }

    protected function get_error_message($error) {
        return array_key_exists($error, $this->error_messages) ?
            $this->error_messages[$error] : $error;
    }

    protected function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0 ||
            !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    //上传文件的处理函数
    protected function handle_file_upload( $uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null ) {
        $file = new \stdClass();
        $file->name = $name;
        $file->size = $size;
        $file->type = $type;

        if ( $this->validate( $file, $error, $index ) ){
            require_once( 'PHPExcel.php');
            $track = new HospitalTrack;
            $trackpregnant = new HospitalPreBirthPregnantTrack;
            $hos_action = $_REQUEST['hos_action'];
            $hos_type = $_REQUEST['hos_type'];
            $model = $_REQUEST['model'];

            switch ( $hos_type ) {
                case 1://出生队列
                    $need_columns = array('no2');

                    switch ( $hos_action ) {
                        case 'basic_info':
                            $allow_columns = array( 'no2', 'name', 'pid', 'm_id', 'id2', 'lmp', 'dedate', 'fetus', 'gender', 'delive', 'ga', 'gad', 'gaw', 'gawg', 'pphone', 'hphone', 'dephone', 'serumt3', 'plasmat3', 'bcellt3', 'cbser', 'cbpla', 'cbbcl', 'placent', 'urinet3', 'urinet3_bag', 'urinet3_dao', 'bname', 'hname', 'var7', 'var9', 'var12', 'var41', 'var42', 'var43', 'var44', 'var45', 'var50', 'var51', 'var52', 'var53', 'var54', 'var55', 'var56', 'var58', 'var59', 'var60', 'var78', 'var61', 'var62', 'var64', 'var65', 'var65_h', 'var65_m', 'var65_min', 'var66', 'var66_h', 'var66_m', 'var66_min', 'var67', 'var67_h', 'var67_m', 'var67_min', 'var68', 'var68_h', 'var68_m', 'var68_min', 'var69', 'var70', 'var71', 'var74', 'var75', 'var76', 'var76_a', 'bw_p90gs', 'bw_p10gs', 'lbw', 'macro', 'preterm', 'sga', 'lga', 'neobd_001', 'matpih_001', 'matpih_002', 'matpih_003', 'matpih_004', 'matpih_005', 'matpih_006', 'matpih_007', 'matpih_008', 'matpih_009', 'matpih_010', 'matgdm_001', 'matgdm_002', 'matgdm_003', 'matgdm_004' );

                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    break;

                case 2://产前队列
                    $need_columns = array('no1');

                    switch ( $hos_action ) {
                        case 'basic_info':
                            $need_columns = array( 'no1' );
                            
                            $allow_columns = array(
                                'no1', 'no2', 'name', 'lmp', 'dedate', 'm_id', 'pphone', 'hphone', 'dephone', 'remark',  'm1_tab_no', 'm_id2', 'update_date', 'HBID', 'ppid' ,'regist' ,'build', 'patid', 'patid2', 'id_deinfo', 'id2_deinfo', 'ad', 'hbid1', 'ppid1', 'tel', 'count2', 'fetus', 'pid', 'cid', 'r6', 'r7', 'r8', 'r9', 'r10', 'r11', 'r12', 'r13', 'r14_a', 'r14_b', 'r15', 'r16', 'r17', 'r18', 'r19', 'r20', 'r21', 'r22', 'r23', 'r24', 'r25', 'r27', 'r28', 'r29', 'r30', 'r31', 'r32', 'r33', 'r34', 'r35', 'r36', 'r37', 'r38', 'r39', 'r40', 'r41', 'r42', 'r43', 'r44', 'r45', 'r46', 'r46_a', 'r47', 'r48', 'r49', 'r50', 'r51', 'r52', 'r53', 'r54', 'r55', 'r56', 'r57', 'r58', 'r59', 'r60', 'r61', 'r62', 'r63', 'r64', 'r65', 'r65_h', 'r65_m', 'r65_min', 'r66', 'r66_h', 'r66_m', 'r66_min', 'r67', 'r67_h', 'r67_m', 'r67_min', 'r68', 'r68_h', 'r68_m', 'r68_min', 'r69', 'r70', 'r71', 'r72', 'r73', 'r74', 'r75', 'r76', 'r77'
                                );

                            break;

                        case 'three_pregnant_info':

                           
                            switch($model){
                                case $track->model_pregnant_three:

                                    $need_columns = array('no1' );
                                    $allow_columns = array('no1', 'name', 'lmp', 'tel1', 'tel2', 'serumt1', 'plasma_bcellt1', 'urinet1', 'pablood', 'serumt2','urinet2', 'urinet3_ent1'
                                        );

                                     break;

                                case $track->model_pregnant_middle:
                                    $need_columns = array('no1' );
                                    $allow_columns = array('no1', 'name', 'tel1t2', 'tel2t2', 'fut2', 'fut2rem', 'fut2er'
                                        );

                                     break;

                                case $track->model_childbirth_status:
                                    $need_columns = array('no1' ,'no2');
                                    $allow_columns = array('no1', 'name', 'lmp', 'dedate', 'm_id', 'pphone', 'hphone', 'dephone', 'no2', 'serumt3', 'plasma_bcellt3', 'cbser', 'cbpla_bcl', 'placent', 'ubcord', 'nmtube', 'urinet3_ent2');
                                    break;

                                case $track->model_pregnant_b:
                                    $need_columns = array('no1' ,'no2');
                                    $allow_columns = array('no2', 'no1', 'lmp', 'ult_checkdate', 'crl');
                                    break;

                                case $track->model_health_manage:
                                    $need_columns = array('no1' );
                                    $allow_columns = array('no1', 'name', 'var59', 'bw_p90gs', 'bw_p10gs', 'lbw', 'macro', 'preterm',  'sga', 'lga', 'neobd_001', 'matpih', 'matgdm');
                                    break;

                                default:
                                    #code .....
                                    break;
                            }
                            break;


                        case $track->track_result:
                            switch ( $model ) {
                                case $track->model_m1:
                                    $allow_columns = array(
                                        'no1', 'm1_fpdate', 'm1_selfques', 'm1_epds', 'm1_phyexa', 'm1_icterus', 'm1_brmilks', 'm1_brmilkr', 'm1_fec', 'm1_bp', 'm1_telques', 'm1_telquesre'
                                        );

                                    break;

                                case $track->model_m6:
                                    $allow_columns = array(
                                        'no1', 'm6_fpdate', 'm6_selfques', 'm6_epds', 'm6_phyexa', 'm6_baily', 'm6_brmilks', 'm6_brmilkr', 'm6_fec', 'm6_rbt', 'm6_bp', 'm6_telques', 'm6_telquesre'
                                        );

                                    break;

                                case $track->model_y1:
                                    $allow_columns = array(
                                        'no1', 'y1_fpdate', 'cname', 'y1_selfques', 'y1_vision', 'y1_phyexa', 'y1_baily', 'y1_brmilks', 'y1_brmilkr', 'y1_fec', 'y1_rbt', 'y1_bpb', 'y1_bp', 'y1_telques', 'y1_telquesre'
                                        );

                                    break;

                                case $track->model_y2:
                                    $allow_columns = array(
                                        'no1', 'y2_fpdate', 'y2_selfques', 'y2_vision', 'y2_phyexa', 'y2_baily', 'y2_fec', 'y2_rbt', 'y2_bpb', 'y2_bp', 'y2_telques', 'y2_telquesre'
                                        );

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

                    break;
                
                default:
                    return;             
                    break;
            }

            //error_log(var_export($field_names,true));
            $file_ext = explode( '.', $name );
            $file_ext = $file_ext[count( $file_ext )-1];

            if( $file_ext == 'xlsx' || $file_ext == 'xls' ){
                $PHPExcel = PHPExcel_IOFactory::load( $uploaded_file );
            }else if( $file_ext == 'csv' ){
                //$PHPExcel = PHPExcel_IOFactory::load($uploaded_file);
                $objReader = PHPExcel_IOFactory::createReader('CSV')
                    ->setDelimiter(',')
                    ->setEnclosure('"')
                    ->setSheetIndex(0);
                $PHPExcel = $objReader->load( $uploaded_file );
            }
            
            $sheet = $PHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            //$verify_name = $sheet->getCell('A2')->getValue();

            $upload_field_names = array();

            for ( $column = 'A'; $column != 'SV'; $column++ ) {//列数是以A列开始
                $value = $sheet->getCell($column.'2')->getFormattedValue();

                if( !in_array( $value, $allow_columns ) )
                    continue;

                if( empty( $value ) )
                    break;

                $upload_field_names[$column] = trim( $value );
            }
            //echo '<pre>';print_r($upload_field_names);echo '</pre>';exit();
            foreach ( $need_columns as $meta ) {
                if( !in_array( $meta, $upload_field_names ) ) {
                    if( empty( $file->error ) ) {
                        $file->error = '文件未上传,缺失该列:'. $meta;
                    }else {
                        $file->error.= ','. $meta;
                    }
                }
            }

            if( !empty( $file->error ) ) {
                return $file;
            }

            $form_infos = array();

            $j = 0;
            for ( $row = 3; $row <= $highestRow; $row++ ) {
                $first_value = $sheet->getCell('A'.$row)->getFormattedValue();
                if( empty( $first_value ) )
                    break;

                foreach ( $upload_field_names as $column => $column_name ) {
                    $val = $sheet->getCell($column.$row)->getFormattedValue();
                    switch ( $column_name ) {
                        case 'pid':case 'm_id':
                            $form_infos[$j][$column_name] = number_format( $val,0,'','' );
                            break;

                        case 'm_id2': case 'update_date': case 'build':case 'id2_deinfo': case 'var27':
                           
                           $form_infos[$j][$column_name] = date( 'Y-m-d 12:00:00', strtotime( $val ) );
                            break;

                       case 'id2':case 'lmp':case 'ult_checkdate':
                            // error_log($form_infos[$j][$column_name] );
                            if( $hos_type == 2 && $hos_action == 'basic_info' )
                                $form_infos[$j]['sys_tab_no'] = $track->get_system_table_id( $val );

                            $form_infos[$j][$column_name] = date( 'Y-m-d 12:00:00', strtotime( $val ) );

                            break;

                        case 'dedate':
                            if( empty( $val ) ){
                                $form_infos[$j][$column_name] = '';
                            }else{
                                $form_infos[$j][$column_name] = date( 'Y-m-d 12:00:00', strtotime( $val ) );
                                if( $hos_type == 2 && $hos_action == 'basic_info' )
                                    $form_infos[$j]['sys_tab_no'] = $track->get_system_table_id( $val );
                            }

                            break;

                        default:
                            $form_infos[$j][$column_name] = $val;
                            break;
                    }
                }
                $j++;
            }
            if( count( $form_infos ) < 1 ) {
                $file->error = '未找到有效的导入数据.';
                return $file;
            }

            switch ( $hos_type ) {
                case 1://出生队列
                    switch ( $hos_action ) {
                        case 'basic_info':
                            foreach ( $form_infos as $item ) {
                                $valid = 1;
                                foreach ($need_columns as $need ) {
                                    if( empty( $item[$need] ) )
                                        $valid = 0;
                                }

                                if( !$valid )
                                    continue;

                                $item_id = $track->hos_db->get_in_birth_id_by_no2( $item['no2'] );

                                if( empty( $item_id ) ) {
                                    $track->hos_db->add_in_birth_basic_info( $item );
                                }else{
                                    $where = array( 'id' => $item_id );
                                    $track->hos_db->update_in_birth_basic_info( $item, $where );
                                }
                            }

                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    break;

                case 2://产前队列
                    switch ( $hos_action ) {
                        case 'basic_info':
                            $sys_tab_array = array();
                            //echo '<pre>';print_r($form_infos);echo '</pre>';exit();
                            //echo '<pre>';print_r($upload_field_names);echo '</pre>';exit();
                            //error_log(var_export($form_infos,true));
                            $has_m1_no = false;
                            if( in_array( 'm1_tab_no', $upload_field_names ) )
                                $has_m1_no = true;
                            
                            $m1_tab_arr = array();

                            foreach ( $form_infos as $item ) {
                                $valid = 1;
                                foreach ( $need_columns as $need ) {
                                    if( empty( $item[$need] ) )
                                        $valid = 0;
                                }

                                if( !$valid )
                                    continue;

                                if( !in_array( $item['sys_tab_no'], $sys_tab_array ) ) 
                                    $sys_tab_array[] = $item['sys_tab_no'];

                                $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
                                if( empty( $item_id ) ) {
                                    $track->hos_db->add_pre_birth_basic_info( $item );
                                }else{
                                    $where = array( 'id' => $item_id );
                                    $track->hos_db->update_pre_birth_basic_info( $item, $where );
                                }

                                //上传带有m1_tab_no的表时,记住开始时间和表名
                                if( $has_m1_no ){
                                    if( array_key_exists( $item['m1_tab_no'], $m1_tab_arr ) ) {
                                        $init_time = strtotime( $m1_tab_arr[ $item['m1_tab_no'] ] );
                                        $item_time = strtotime( $item['dedate'] );

                                        if( $init_time > $item_time ){
                                            $m1_tab_arr[ $item['m1_tab_no'] ] = $item_time;
                                        }
                                    }else{
                                       $m1_tab_arr[ $item['m1_tab_no'] ] = $item['dedate'];
                                    }
                                }
                            }

                            if( $has_m1_no && !empty( $m1_tab_arr ) ) {
                                foreach ( $m1_tab_arr as $m1_tab_no => $m1_tab_time ) {
                                    $tab_id = $track->hos_db->get_pre_birth_table_status( $m1_tab_no, 0 );

                                    if( is_null( $tab_id ) ){
                                        $m1_tab_data = array(
                                            'tab_no' => $m1_tab_no,
                                            'tab_type' => 0,
                                            'start_time' => $m1_tab_time
                                            );

                                        $track->hos_db->add_pre_birth_table_status( $m1_tab_data );
                                    }
                                }
                            }

                            foreach ( $sys_tab_array as $sys_tab) {
                                $tab_id = $track->hos_db->get_pre_birth_table_status( $m1_tab_no, 1 );

                                if( is_null( $tab_id ) ){
                                    $sys_tab_data = array(
                                        'tab_no' => $sys_tab,
                                        'tab_type' => 1,
                                        'start_time' => $track->get_system_date_by_id( $sys_tab )
                                    );

                                    $track->hos_db->add_pre_birth_table_status( $sys_tab_data );
                                }
                            }

                            break;

                        case 'three_pregnant_info':
                            switch($model){
                                case $track->model_pregnant_three:
                                    
                                    foreach( $form_infos as $item){
                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
                                        // $item_id = $trackpregnant->hos_pregnant_db->get_pre_birth_pregnant_id_by_no1($item['no1']);
                                        $basic_data['no1']      = $item['no1'];
                                        $basic_data['name']     = $item['name'];
                                        $basic_data['lmp']      = $item['lmp'];
                                        $basic_data['pphone']   = $item['tel1'];
                                        $basic_data['hphone']   = $item['tel2'];
                                        if($item_id < 1){
                                           
                                            $item_id = $track->hos_db->add_pre_birth_basic_info( $basic_data );
                                            // error_log($item_id);
                                        }else{
                                            
                                            $where = array( 'id' => $item_id );
                                            $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        }
                                        
                                        $pregnant_three_data['serumt1']         = $item['serumt1'];
                                        $pregnant_three_data['plasma_bcellt1']  = $item['plasma_bcellt1'];
                                        $pregnant_three_data['urinet1']         = $item['urinet1'];
                                        $pregnant_three_data['pablood']         = $item['pablood'];
                                        $pregnant_three_data['serumt2']         = $item['serumt2'];
                                        $pregnant_three_data['urinet2']         = $item['urinet2'];
                                        $pregnant_three_data['urinet3_ent1']    = $item['urinet3_ent1']; 
                                        $trackpregnant->hos_pregnant_db->update_pre_birth_pregnant_info($pregnant_three_data, $item_id);
                                    }

                                    break;

                                case $track->model_pregnant_middle:

                                    foreach( $form_infos as $item){

                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
                                        // $item_id = $trackpregnant->hos_pregnant_db->get_pre_birth_pregnant_id_by_no1($item['no1']);
                                        $basic_data['no1']      = $item['no1'];
                                        $basic_data['name']     = $item['name'];
                                        $basic_data['pphone']   = $item['tel1t2'];
                                        $basic_data['hphone']   = $item['tel2t2'];
                                        if($item_id < 1){
                                            
                                            $item_id = $track->hos_db->add_pre_birth_basic_info( $basic_data );
                                            // error_log($item_id);
                                        }else{
                                            
                                            $where = array( 'id' => $item_id );
                                            $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        }
                                        
                                        $pregnant_middle_data['fut2']          = $item['fut2'];
                                        $pregnant_middle_data['fut2rem']       = $item['fut2rem'];
                                        $pregnant_middle_data['fut2er']        = $item['fut2er'];
                                       
                                        $trackpregnant->hos_pregnant_db->update_pre_birth_pregnant_info($pregnant_middle_data, $item_id);
  
                                    }

                                    break;

                                case $track->model_childbirth_status:

                                    foreach( $form_infos as $item){

                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
                                        // $item_id = $trackpregnant->hos_pregnant_db->get_pre_birth_pregnant_id_by_no1($item['no1']);
                                        $basic_data['no1']      = $item['no1'];
                                        $basic_data['no2']      = $item['no2'];
                                        $basic_data['name']     = $item['name'];
                                        
                                        $basic_data['lmp']      = $item['lmp'];
                                        $basic_data['dedate']   = $item['dedate'];
                                        $basic_data['m_id']     = $item['m_id'];
                                        $basic_data['pphone']   = $item['pphone'];
                                        $basic_data['hphone']   = $item['hphone'];
                                        $basic_data['dephone']  = $item['dephone'];
                                        if($item_id < 1){
                                            

                                            $item_id = $track->hos_db->add_pre_birth_basic_info( $basic_data );
                                            // error_log($item_id);
                                        }else{
                                            
                                            $where = array( 'id' => $item_id );
                                            $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        }
                                        
                                        $pregnant_middle_data['serumt3']          = $item['serumt3'];
                                        $pregnant_middle_data['plasma_bcellt3']   = $item['plasma_bcellt3'];
                                        $pregnant_middle_data['cbser']            = $item['cbser'];
                                        $pregnant_middle_data['cbpla_bcl']        = $item['cbpla_bcl'];
                                        $pregnant_middle_data['placent']          = $item['placent'];
                                        $pregnant_middle_data['ubcord']           = $item['ubcord'];
                                        $pregnant_middle_data['nmtube']           = $item['nmtube'];
                                        $pregnant_middle_data['urinet3_ent2']     = $item['urinet3_ent2'];

                                       
                                        $trackpregnant->hos_pregnant_db->update_pre_birth_childbirth_info($pregnant_middle_data, $item_id);

                                    }

                                    break;

                                case $track->model_pregnant_b:
                                    foreach( $form_infos as $item){

                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
                                        // $item_id = $trackpregnant->hos_pregnant_db->get_pre_birth_pregnant_id_by_no1($item['no1']);
                                        $basic_data['no1']      = $item['no1'];
                                        $basic_data['no2']      = $item['no2'];
                                        $basic_data['lmp']      = $item['lmp'];
                                        if($item_id < 1){
                                            
                                            $item_id = $track->hos_db->add_pre_birth_basic_info( $basic_data );
                                            // error_log($item_id);
                                        }else{
                                            
                                            $where = array( 'id' => $item_id );
                                            $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        }
                                        $pregnant_middle_data['no1']            = $item['no1'];
                                        $pregnant_middle_data['ult_checkdate']  = $item['ult_checkdate'];
                                        $pregnant_middle_data['crl']            = $item['crl'];
                                       
                                        $trackpregnant->hos_pregnant_db->update_pre_birth_b_pregnant_info($pregnant_middle_data, $item['no1']); 
                                        // $item_id = $trackpregnant->hos_pregnant_db->get_pre_birth_b_pregnant_id($item['no1'], $item['ult_checkdate']);
                                        // if(empty($item_id)){
                                        //     $trackpregnant->hos_pregnant_db->add_pre_birth_b_pregnant_info($item);
                                        // }else{
                                        //     $where = array( 'id' => $item_id);
                                        //     $trackpregnant->hos_pregnant_db->update_pre_birth_b_pregnant_info($item, $where);
                                        // }
                                    }
                                    break;

                                case $track->model_health_manage:

                                    foreach( $form_infos as $item){
                                         $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );
                                        $basic_data['no1']      = $item['no1'];
                        
                                        $basic_data['name']     = $item['name'];
                                        if($item_id < 1){
                                            
                                            $item_id = $track->hos_db->add_pre_birth_basic_info( $basic_data );
                                            // error_log($item_id);
                                        }else{
                                            
                                            $where = array( 'id' => $item_id );
                                            $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        }
                            
                                        $health_manage_data['var59'] = $item['var59'] ;
                                        $health_manage_data['bw_p90gs'] = $item['bw_p90gs'] ;
                                        $health_manage_data['bw_p10gs'] = $item['bw_p10gs'] ;
                                        $health_manage_data['lbw'] = $item['lbw'] ;
                                        $health_manage_data['macro'] = $item['macro'] ;
                                        $health_manage_data['preterm'] = $item['preterm'] ;
                                        $health_manage_data['sga'] = $item['sga'] ;
                                        $health_manage_data['lga'] = $item['lga'] ;
                                        $health_manage_data['neobd_001'] = $item['neobd_001'] ;
                                        $health_manage_data['matpih'] = $item['matpih'] ;
                                        $health_manage_data['matgdm'] = $item['matgdm'] ;
                                       
                                        $trackpregnant->hos_pregnant_db->update_pre_birth_health_info($health_manage_data, $item_id); 

                                     }

                                    break;
                                default:
                                    #code .....
                                    break;
                            }

                            break;

                        case $track->track_result:
                            switch ( $model ) {
                                case $track->model_m1:
                                    foreach ( $form_infos as $item ) {
                                        $valid = 1;
                                        foreach ( $need_columns as $need ) {
                                            if( empty( $item[$need] ) )
                                                $valid = 0;
                                        }

                                        if( !$valid )
                                            continue;

                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                                        if( $item_id < 1 )
                                            continue;

                                        $basic_data = array();
                                        $status_data = array();

                                        $judge_arr = array(
                                        'm1_selfques', 'm1_epds', 'm1_phyexa', 'm1_icterus', 'm1_brmilks', 'm1_brmilkr', 'm1_fec', 'm1_bp', 'm1_telques'
                                        );

                                        foreach ( $judge_arr as $judge) {
                                            if( !in_array( $judge, $upload_field_names ) )
                                                continue;
                                            if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                                                $status_data[ $judge ] = $item[ $judge ] = 1;
                                            else
                                                $status_data[ $judge ] = $item[ $judge ] = 0;
                                        }

                                        $status_data['m1_telquesre'] = $item['m1_telquesre'] = array_search( $item['m1_telquesre'], $track->telquesre_arr );
                                        $status_data['id'] = $item_id;
                                        $status_data['m1_fpdate'] = $item['m1_fpdate'];

                                        $check_arr = array(
                                        'm1_fpdate', 'm1_selfques', 'm1_epds', 'm1_phyexa', 'm1_icterus', 'm1_brmilks', 'm1_brmilkr', 'm1_fec', 'm1_bp'
                                        );

                                        $status = 0;
                                        //只要有其中一项,代表有现场随访
                                        foreach ( $check_arr as $check ) {
                                            if( $item[ $check ] ) {
                                                $status = 1;
                                                break;
                                            }
                                        }

                                        if( !$status ) {
                                            if( $item['m1_telques'] )
                                                $status = 2;//参加电话随访
                                            else{
                                                if( $item['m1_telquesre'] )
                                                    $status = $item['m1_telquesre'];//失联,拒绝,宝宝夭折
                                            }
                                        }

                                        $basic_data['m1_fpstatus'] = $status_data['m1_fp'] = $status;
                                        $where = array( 'id' => $item_id );
                                        // echo '<pre>';print_r($basic_data);echo '</pre>';
                                        // echo '<pre>';print_r($where);echo '</pre>';
                                        // echo '<pre>';print_r($status_data);echo '</pre>';
                                        // exit();
                                        //基本表中的状态更新
                                        $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        //随访表中的数据更新
                                        $track->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                                    }

                                    break;

                                case $track->model_m6:
                                    foreach ( $form_infos as $item ) {
                                        $valid = 1;
                                        foreach ( $need_columns as $need ) {
                                            if( empty( $item[$need] ) )
                                                $valid = 0;
                                        }

                                        if( !$valid )
                                            continue;

                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                                        if( $item_id < 1 )
                                            continue;

                                        $basic_data = array();
                                        $status_data = array();

                                        $judge_arr = array(
                                        'm6_selfques', 'm6_epds', 'm6_phyexa', 'm6_baily', 'm6_brmilks', 'm6_brmilkr', 'm6_fec', 'm6_rbt', 'm6_bp', 'm6_telques'
                                        );

                                        foreach ( $judge_arr as $judge) {
                                            if( !in_array( $judge, $upload_field_names ) )
                                                continue;
                                            if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                                                $status_data[ $judge ] = $item[ $judge ] = 1;
                                            else
                                                $status_data[ $judge ] = $item[ $judge ] = 0;
                                        }

                                        $status_data['m6_telquesre'] = $item['m6_telquesre'] = array_search( $item['m6_telquesre'], $track->telquesre_arr );
                                        $status_data['id'] = $item_id;
                                        $status_data['m6_fpdate'] = $item['m6_fpdate'];

                                        $check_arr =  array(
                                        'm6_fpdate', 'm6_selfques', 'm6_epds', 'm6_phyexa', 'm6_baily', 'm6_brmilks', 'm6_brmilkr', 'm6_fec', 'm6_rbt', 'm6_bp'
                                        );

                                        $status = 0;
                                        //只要有其中一项,代表有现场随访
                                        foreach ( $check_arr as $check ) {
                                            if( $item[ $check ] ) {
                                                $status = 1;
                                                break;
                                            }
                                        }

                                        if( !$status ) {
                                            if( $item['m6_telques'] )
                                                $status = 2;//参加电话随访
                                            else{
                                                if( $item['m6_telquesre'] )
                                                    $status = $item['m6_telquesre'];//失联,拒绝,宝宝夭折
                                            }
                                        }

                                        $basic_data['m6_fpstatus'] = $status_data['m6_fp'] = $status;
                                        $where = array( 'id' => $item_id );
                     
                                        //基本表中的状态更新
                                        $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        //随访表中的数据更新
                                        $track->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                                    }

                                    break;

                                case $track->model_y1:
                                    foreach ( $form_infos as $item ) {
                                        $valid = 1;
                                        foreach ( $need_columns as $need ) {
                                            if( empty( $item[$need] ) )
                                                $valid = 0;
                                        }

                                        if( !$valid )
                                            continue;

                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                                        if( $item_id < 1 )
                                            continue;

                                        $basic_data = array();
                                        $status_data = array();

                                        $judge_arr = array(
                                        'y1_selfques', 'y1_vision', 'y1_phyexa', 'y1_baily', 'y1_brmilks', 'y1_brmilkr', 'y1_fec', 'y1_rbt', 'y1_bpb', 'y1_bp', 'y1_telques'
                                        );

                                        foreach ( $judge_arr as $judge) {
                                            if( !in_array( $judge, $upload_field_names ) )
                                                continue;
                                            if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                                                $status_data[ $judge ] = $item[ $judge ] = 1;
                                            else
                                                $status_data[ $judge ] = $item[ $judge ] = 0;
                                        }

                                        $status_data['y1_telquesre'] = $item['y1_telquesre'] = array_search( $item['y1_telquesre'], $track->telquesre_arr );
                                        $status_data['id'] = $item_id;
                                        $status_data['y1_fpdate'] = $item['y1_fpdate'];
                                    
                                        $check_arr = array(
                                        'y1_fpdate', 'y1_selfques', 'y1_vision', 'y1_phyexa', 'y1_baily', 'y1_brmilks', 'y1_brmilkr', 'y1_fec', 'y1_rbt', 'y1_bpb', 'y1_bp'
                                        );

                                        $status = 0;
                                        //只要有其中一项,代表有现场随访
                                        foreach ( $check_arr as $check ) {
                                            if( $item[ $check ] ) {
                                                $status = 1;
                                                break;
                                            }
                                        }

                                        if( !$status ) {
                                            if( $item['y1_telques'] )
                                                $status = 2;//参加电话随访
                                            else{
                                                if( $item['y1_telquesre'] )
                                                    $status = $item['y1_telquesre'];//失联,拒绝,宝宝夭折
                                            }
                                        }

                                        $basic_data['y1_fpstatus'] = $status_data['y1_fp'] = $status;
                                        $basic_data['cname'] = $item['cname'];
                                        $where = array( 'id' => $item_id );
                     
                                        //基本表中的状态更新
                                        $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        //随访表中的数据更新
                                        $track->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                                    }

                                    break;

                                case $track->model_y2:
                                    foreach ( $form_infos as $item ) {
                                        $valid = 1;
                                        foreach ( $need_columns as $need ) {
                                            if( empty( $item[$need] ) )
                                                $valid = 0;
                                        }

                                        if( !$valid )
                                            continue;

                                        $item_id = $track->hos_db->get_pre_birth_id_by_no1( $item['no1'] );

                                        if( $item_id < 1 )
                                            continue;

                                        $basic_data = array();
                                        $status_data = array();

                                        $judge_arr = array(
                                        'y2_selfques', 'y2_vision', 'y2_phyexa', 'y2_baily', 'y2_fec', 'y2_rbt', 'y2_bpb', 'y2_bp', 'y2_telques'
                                        );

                                        foreach ( $judge_arr as $judge) {
                                            if( !in_array( $judge, $upload_field_names ) )
                                                continue;
                                            if( $item[ $judge ] == '是' || $item[ $judge ] == 1 )
                                                $status_data[ $judge ] = $item[ $judge ] = 1;
                                            else
                                                $status_data[ $judge ] = $item[ $judge ] = 0;
                                        }

                                        $status_data['y2_telquesre'] = $item['y2_telquesre'] = array_search( $item['y2_telquesre'], $track->telquesre_arr );
                                        $status_data['id'] = $item_id;
                                        $status_data['y2_fpdate'] = $item['y2_fpdate'];
                                    
                                        $check_arr = array(
                                        'y2_fpdate', 'y2_selfques', 'y2_vision', 'y2_phyexa', 'y2_baily', 'y2_fec', 'y2_rbt', 'y2_bpb', 'y2_bp'
                                        );

                                        $status = 0;
                                        //只要有其中一项,代表有现场随访
                                        foreach ( $check_arr as $check ) {
                                            if( $item[ $check ] ) {
                                                $status = 1;
                                                break;
                                            }
                                        }

                                        if( !$status ) {
                                            if( $item['y2_telques'] )
                                                $status = 2;//参加电话随访
                                            else{
                                                if( $item['y2_telquesre'] )
                                                    $status = $item['y2_telquesre'];//失联,拒绝,宝宝夭折
                                            }
                                        }

                                        $basic_data['y2_fpstatus'] = $status_data['y2_fp'] = $status;
                                        $where = array( 'id' => $item_id );
                     
                                        //基本表中的状态更新
                                        $track->hos_db->update_pre_birth_basic_info( $basic_data, $where );
                                        //随访表中的数据更新
                                        $track->hos_db->update_pre_birth_patient_status( $status_data, $item_id, $model );
                                    }

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

                    break;
                
                default:
                    break;
            }

            @unlink( $uploaded_file );
        }
        return $file;
    }

    protected function body($str) {
        echo $str;
    }
    
    protected function header($str) {
        header($str);
    }

    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }

    protected function generate_response($content, $print_response = true) {
        if ($print_response) {
            $json = json_encode($content);
            $redirect = isset($_REQUEST['redirect']) ?
                stripslashes($_REQUEST['redirect']) : null;
            if ($redirect) {
                $this->header('Location: '.sprintf($redirect, rawurlencode($json)));
                return;
            }
            $this->head();
            if ($this->get_server_var('HTTP_CONTENT_RANGE')) {
                $files = isset($content[$this->options['param_name']]) ?
                    $content[$this->options['param_name']] : null;
                if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
                    $this->header('Range: 0-'.(
                        $this->fix_integer_overflow(intval($files[0]->size)) - 1
                    ));
                }
            }
            $this->body($json);
        }
        return $content;
    }

    

    protected function send_content_type_header() {
        $this->header('Vary: Accept');
        if (strpos($this->get_server_var('HTTP_ACCEPT'), 'application/json') !== false) {
            $this->header('Content-type: application/json');
        } else {
            $this->header('Content-type: text/plain');
        }
    }

    protected function send_access_control_headers() {
        $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
        $this->header('Access-Control-Allow-Credentials: '
            .($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
        $this->header('Access-Control-Allow-Methods: '
            .implode(', ', $this->options['access_control_allow_methods']));
        $this->header('Access-Control-Allow-Headers: '
            .implode(', ', $this->options['access_control_allow_headers']));
    }

    public function head() {
        $this->header('Pragma: no-cache');
        $this->header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->header('Content-Disposition: inline; filename="files.json"');
        // Prevent Internet Explorer from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if ($this->options['access_control_allow_origin']) {
            $this->send_access_control_headers();
        }
        $this->send_content_type_header();
    }

    public function get($print_response = true) {
        
    }

    public function post($print_response = true) {
        
        $upload = isset($_FILES[$this->options['param_name']]) ?
            $_FILES[$this->options['param_name']] : null;
        // Parse the Content-Disposition header, if available:
        $file_name = $this->get_server_var('HTTP_CONTENT_DISPOSITION') ?
            rawurldecode(preg_replace(
                '/(^[^"]+")|("$)/',
                '',
                $this->get_server_var('HTTP_CONTENT_DISPOSITION')
            )) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range = $this->get_server_var('HTTP_CONTENT_RANGE') ?
            preg_split('/[^0-9]+/', $this->get_server_var('HTTP_CONTENT_RANGE')) : null;
        $size =  $content_range ? $content_range[3] : null;
        $files = array();
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    $file_name ? $file_name : $upload['name'][$index],
                    $size ? $size : $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range
                );
            }
        } else {
            // param_name is a single object identifier like "file",
            // $_FILES is a one-dimensional array:
            $files[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                $file_name ? $file_name : (isset($upload['name']) ?
                        $upload['name'] : null),
                $size ? $size : (isset($upload['size']) ?
                        $upload['size'] : $this->get_server_var('CONTENT_LENGTH')),
                isset($upload['type']) ?
                        $upload['type'] : $this->get_server_var('CONTENT_TYPE'),
                isset($upload['error']) ? $upload['error'] : null,
                null,
                $content_range
            );
        }
        return $this->generate_response(
            array($this->options['param_name'] => $files),
            $print_response
        );
    }

}
?>