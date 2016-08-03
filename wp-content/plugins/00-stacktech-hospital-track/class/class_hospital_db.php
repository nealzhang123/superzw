<?php
/**
* 
*/
class Hospital_Db {
	public $model_m1,$model_m6,$model_y1,$model_y2;
	public $model_y3,$model_y5;

	function __construct() {
		$this->model_m1 = 'm1';
		$this->model_m6 = 'm6';
		$this->model_y1 = 'y1';
		$this->model_y2 = 'y2';

		$this->model_y3 = 'y3';
		$this->model_y5 = 'y5';
	}

	function init_tables() {
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');


        //=====================================================
		//==================产前队列============================
		//=====================================================
		//
		//产前队列的基本信息表
        $hos_pre_birth_basic_info = $wpdb->prefix . 'hos_pre_birth_basic_info';
        
        $sql = "CREATE TABLE $hos_pre_birth_basic_info  (
            id int(11) NOT NULL AUTO_INCREMENT,
            no1 varchar(30),
            no2 varchar(30),
            name varchar(50),
            m_id varchar(40),
            lmp datetime,
            bname varchar(50),
            hname varchar(50),
            cname varchar(50),
            dedate datetime,
            pphone varchar(30),
            hphone varchar(30),
            dephone varchar(30),
            m1_tab_no varchar(40),
            sys_tab_no int(11),
            m1_fpstatus smallint,
            m6_fpstatus smallint,
            y1_fpstatus smallint,
            y2_fpstatus smallint,
            m_id2 datetime,
            update_date datetime,
            hbid varchar(30),
            ppid varchar(30),
            regist varchar(10),
            build datetime,
            patid varchar(30),
            patid2 varchar(30),
            id_deinfo varchar(40),
            id2_deinfo datetime,
            ad varchar(20),
            hbid1 varchar(20),
            ppid1 varchar(20),
            tel varchar(30),
            count2 varchar(50),
            rem_mzin varchar(50),
            fetus varchar(50),
            pid varchar(30),
            cid varchar(30),
            var6 varchar(30),
            var7 varchar(30),
            var8 varchar(30),
            var9 varchar(30),
            var10 varchar(30),
            var11 varchar(30),
            var12 varchar(30),
            var13 varchar(30),
            var14_a varchar(30),
            var14_b varchar(30),
            var15 varchar(30),
            var16 varchar(30),
            var17 varchar(30),
            var18 varchar(30),
            var19 varchar(30),
            var20 varchar(30),
            var21 varchar(30),
            var22 varchar(30),
            var23 varchar(30),
            var24 varchar(30),
            var25 varchar(30),
            var27 datetime,
            var28 varchar(30),
            var29 varchar(30),
            var30 varchar(30),
            var31 varchar(30),
            var32 varchar(30),
            var33 varchar(30),
            var34 varchar(30),
            var35 varchar(30),
            var36 varchar(30),
            var37 varchar(30),
            var38 varchar(30),
            var39 varchar(30),
            var40 varchar(30),
            var41 varchar(30),
            var42 varchar(30),
            var43 varchar(30),
            var44 varchar(30),
            var45 varchar(30),
            var46 varchar(30),
            var46_a varchar(30),
            var47 varchar(30),
            var48 varchar(30),
            var49 varchar(30),
            var50 varchar(30),
            var51 varchar(30),
            var52 varchar(30),
            var53 varchar(30),
            var54 varchar(30),
            var55 varchar(30),
            var56 varchar(30),
            var57 varchar(30),
            var58 varchar(30),
            var59 varchar(30),
            var60 varchar(30),
            var61 varchar(30),
            var62 varchar(30),
            var63 varchar(30),
            var64 varchar(30),
            var65 varchar(30),
            var65_h varchar(30),
            var65_m varchar(30),
            var65_min varchar(30),
            var66 varchar(30),
            var66_h varchar(30),
            var66_m varchar(30),
            var66_min varchar(30),
            var67 varchar(30),
            var67_h varchar(30),
            var67_m varchar(30),
            var67_min varchar(30),
            var68 varchar(30),
            var68_h varchar(30),
            var68_m varchar(30),
            var68_min varchar(30),
            var69 varchar(30),
            var70 varchar(30),
            var71 varchar(30),
            var72 varchar(30),
            var73 varchar(30),
            var74 varchar(30),
            var75 varchar(30),
            var76 varchar(30),
            var77 varchar(30),
            remark text,
            PRIMARY KEY  (id)
        	)";

		dbDelta( $sql );


		//产前队列的1个月随访记录
		$hos_pre_birth_one_month_status = $wpdb->prefix . 'hos_pre_birth_one_month_status';

		$sql = "CREATE TABLE $hos_pre_birth_one_month_status  (
            id int(11) NOT NULL,
            m1_fp smallint,
			m1_fpdate datetime,
			m1_selfques smallint,
			m1_epds smallint,
			m1_phyexa smallint,
			m1_icterus smallint,
			m1_brmilks smallint,
			m1_brmilkr smallint,
			m1_urine smallint,
			m1_fec smallint,
			m1_bp smallint,
			m1_telname varchar(50),
			m1_telre1 smallint,
			m1_telre1_extend int(11),
			m1_telre2 smallint,
			m1_telre2_extend int(11),
			m1_telre3 smallint,
			m1_telre3_extend int(11),
			m1_telques smallint,
			m1_telquesre smallint,
			m1_is_delay smallint,
			PRIMARY KEY  (id)
        	)";

		dbDelta( $sql );


		//产前队列的6个月随访记录
		$hos_pre_birth_six_month_status = $wpdb->prefix . 'hos_pre_birth_six_month_status';

		$sql = "CREATE TABLE $hos_pre_birth_six_month_status  (
            id int(11) NOT NULL,
            m6_fp smallint,
			m6_fpdate datetime,
			m6_selfques smallint,
			m6_epds smallint,
			m6_phyexa smallint,
			m6_baily smallint,
			m6_rbt smallint,
			m6_brmilks smallint,
			m6_brmilkr smallint,
			m6_urine smallint,
			m6_bp smallint,
			m6_fec smallint,
			m6_telname varchar(50),
			m6_telre1 smallint,
			m6_telre1_extend int(11),
			m6_telre2 smallint,
			m6_telre2_extend int(11),
			m6_telre3 smallint,
			m6_telre3_extend int(11),
			m6_telques smallint,
			m6_telquesre smallint,
			m6_is_delay smallint,
			PRIMARY KEY  (id)
        	)";

		dbDelta( $sql );


		//产前队列的1岁随访记录
		$hos_pre_birth_one_year_status = $wpdb->prefix . 'hos_pre_birth_one_year_status';

		$sql = "CREATE TABLE $hos_pre_birth_one_year_status  (
            id int(11) NOT NULL,
            y1_fp smallint,
			y1_fpdate datetime,
			y1_telques smallint,
			y1_phyexa smallint,
			y1_baily smallint,
			y1_bpb smallint,
			y1_rbt smallint,
			y1_brmilks smallint,
			y1_brmilkr smallint,
			y1_urine smallint,
			y1_fec smallint,
			y1_vision smallint,
			y1_bp smallint,
			y1_telname varchar(50),
			y1_telre1 smallint,
			y1_telre1_extend int(11),
			y1_telre2 smallint,
			y1_telre2_extend int(11),
			y1_telre3 smallint,
			y1_telre3_extend int(11),
			y1_selfques smallint,
			y1_telquesre smallint,
			y1_is_delay smallint,
			PRIMARY KEY  (id)
        	)";

		dbDelta( $sql );


		//产前队列的2岁随访记录
		$hos_pre_birth_two_year_status = $wpdb->prefix . 'hos_pre_birth_two_year_status';

		$sql = "CREATE TABLE $hos_pre_birth_two_year_status  (
            id int(11) NOT NULL,
            y2_fp smallint,
			y2_fpdate datetime,
            y2_selfques smallint,
			y2_phyexa smallint,
			y2_baily smallint,
			y2_bpb smallint,
			y2_rbt smallint,
			y2_urine smallint,
			y2_fec smallint,
			y2_vision smallint,
			y2_bp smallint,
			y2_telname varchar(50),
			y2_telre1 smallint,
			y2_telre1_extend int(11),
			y2_telre2 smallint,
			y2_telre2_extend int(11),
			y2_telre3 smallint,
			y2_telre3_extend int(11),
			y2_telques smallint,
			y2_telquesre smallint,
			y2_is_delay smallint,
			PRIMARY KEY  (id)
        	)";

		dbDelta( $sql );


		//产前队列的相关表状态
		$hos_pre_birth_table_status = $wpdb->prefix . 'hos_pre_birth_table_status';

		//tab_no 上传时候的表名(tab_type为0)，或者是系统自动生成的表号(tab_type为1)
		//start_time 是整个表中分娩时间最早的时间
		//ignore_track_1,2,3,4分别对应该表,1月，6月，1岁，2岁的现场随访表在仪表盘是否不再提醒。 
		//ignore_tel_1,2,3,4分别对应该表,1月，6月，1岁，2岁的电话随访表在仪表盘是否不再提醒。 
		$sql = "CREATE TABLE $hos_pre_birth_table_status  (
            tab_id int(11) NOT NULL AUTO_INCREMENT,
            tab_no varchar(20),
            tab_type smallint,
            start_time datetime,
            ignore_track_0 smallint,
            ignore_track_1 smallint,
            ignore_track_2 smallint,
            ignore_track_3 smallint,
            ignore_tel_0 smallint,
            ignore_tel_1 smallint,
            ignore_tel_2 smallint,
            ignore_tel_3 smallint,
			PRIMARY KEY  (tab_id)
        	)";

		dbDelta( $sql );


		//自定义备注表
		$hos_presonal_define_remark = $wpdb->prefix . 'hos_presonal_define_remark';
		
		$sql = "CREATE TABLE $hos_presonal_define_remark  (
            remark_id int(11) NOT NULL AUTO_INCREMENT,
            remark_content varchar(255),
			PRIMARY KEY  (remark_id)
        	)";

		dbDelta( $sql );




		//=====================================================
		//==================出生队列============================
		//=====================================================
		//
		//出生队列的基本信息表(分娩信息表)
        $hos_in_birth_basic_info = $wpdb->prefix . 'hos_in_birth_basic_info';

        $sql = "CREATE TABLE $hos_in_birth_basic_info  (
        	id int(11) NOT NULL AUTO_INCREMENT,
        	no2 varchar(30),
			name varchar(50),
			pid varchar(30),
			m_id varchar(30),
			id2 datetime,
			lmp datetime,
			dedate datetime,
			fetus varchar(30),
			gender varchar(30),
			delive varchar(30),
			ga varchar(30),
			gad int(8),
			gaw decimal(10,2),
			gawg int(8),
			pphone varchar(30),
			hphone varchar(30),
			dephone varchar(30),
			serumt3 int(11),
			plasmat3 int(11),
			bcellt3 int(11),
			cbser int(11),
			cbpla int(11),
			cbbcl int(11),
			placent int(11),
			urinet3 int(11),
			urinet3_bag int(11),
			urinet3_dao varchar(50),
			bname varchar(50),
			hname varchar(50),
			var7 int(11),
			var9 varchar(50),
			var12 int(11),
			var41 varchar(50),
			var42 varchar(50),
			var43 varchar(50),
			var44 varchar(50),
			var45 int(11),
			var50 int(11),
			var51 int(11),
			var52 int(11),
			var53 int(11),
			var54 int(11),
			var55 int(11),
			var56 varchar(50),
			var58 varchar(50),
			var59 int(11),
			var60 int(11),
			var78 int(11),
			var61 int(11),
			var62 int(11),
			var64 varchar(50),
			var65 varchar(50),
			var65_h int(11),
			var65_m int(11),
			var65_min int(11),
			var66 varchar(50),
			var66_h int(11),
			var66_m int(11),
			var66_min int(11),
			var67 varchar(50),
			var67_h int(11),
			var67_m int(11),
			var67_min int(11),
			var68 varchar(50),
			var68_h int(11),
			var68_m int(11),
			var68_min int(11),
			var69 int(11),
			var70 int(11),
			var71 int(11),
			var74 int(11),
			var75 int(11),
			var76 varchar(50),
			var76_a varchar(50),
			bw_p90gs decimal(10,6),
			bw_p10gs decimal(10,6),
			lbw int(11),
			macro int(11),
			preterm int(11),
			sga int(11),
			lga int(11),
			neobd_001 int(11),
			matpih_001 int(11),
			matpih_002 int(11),
			matpih_003 int(11),
			matpih_004 int(11),
			matpih_005 int(11),
			matpih_006 int(11),
			matpih_007 int(11),
			matpih_008 int(11),
			matpih_009 int(11),
			matpih_010 int(11),
			matgdm_001 int(11),
			matgdm_002 int(11),
			matgdm_003 int(11),
			matgdm_004 int(11),
			PRIMARY KEY  (id)
        	)";

		dbDelta( $sql );

		//出生队列入院体检表
		$hos_ry_exam_info = $wpdb->prefix . 'hos_ry_exam_info';

		//hos_type 出生队列为1,产前队列为2
		$sql = "CREATE TABLE $hos_ry_exam_info  (
            id int(11) NOT NULL AUTO_INCREMENT,
            no1 varchar(30),
            no2 varchar(30),
            name_ry varchar(50),
            datet_ry datetime,
            meu_ry smallint,
            cserum_ry decimal(10,1),
            cplasma_ry decimal(10,1),
            cbcell_ry decimal(10,1),
            bloodqu_ry smallint,
            brtr_ry smallint,
            altr_ry smallint,
            note_ry text,
            hos_type smallint,
            PRIMARY KEY  (id)
		)";

		dbDelta( $sql );


		//3-4岁随访表
		$hos_3y_track_info = $wpdb->prefix . 'hos_3y_track_info';

		//hos_type 出生队列为1,产前队列为2
		$sql = "CREATE TABLE $hos_3y_track_info  (
            id int(11) NOT NULL AUTO_INCREMENT,
            no1 varchar(30),
            no2 varchar(30),
            hos_type smallint,
            status_3y smallint,
			district smallint,
			phmeas_3y smallint,
			pexam_3y smallint,
			hearing_3y smallint,
			vision_3y smallint,
			oral_3y smallint,
			btype_3y smallint,
			blroutine_3y smallint,
			bllead_3y smallint,
			hemoglobin_3y smallint,
			trelements_3y smallint,
			chmeigg_3y smallint,
			hbeag_3y smallint,
			mvigg_3y smallint,
			look_3y smallint,
			stradip_3y smallint,
			bmd_3y smallint,
			si_3y smallint,
			ptq_3y smallint,
			plasma_3y smallint,
			bcell_3y smallint,
			churine_3y smallint,
			chfaeces_3y smallint,
			paquestion_3y smallint,
			tequestion_3y smallint,
			trs_3y smallint,
			asq_3y smallint,
			chealthhandbook_3y smallint,
			vacertifi_3y smallint,
			cbcl_3y smallint,
			abc_3y smallint,
			completerkd_3y varchar(50),
			notekd_3y varchar(255),
			phquestion_3y smallint,
			notetel_3y varchar(255),
			completertel_3y varchar(50),
			kindergarten_3y varchar(255),
			enrolldate_3y datetime,
			class_3y varchar(255),
			exdate_3y datetime,
			pqudate_3y datetime,
            PRIMARY KEY  (id)
		)";

		dbDelta( $sql );

		//5-6岁随访表
		$hos_5y_track_info = $wpdb->prefix . 'hos_5y_track_info';

		//hos_type 出生队列为1,产前队列为2
		$sql = "CREATE TABLE $hos_5y_track_info  (
			id int(11) NOT NULL AUTO_INCREMENT,
            no1 varchar(30),
            no2 varchar(30),
            hos_type smallint,
            status_5y smallint,
			district smallint,
			phmeas_5y smallint,
			pexam_5y smallint,
			hearing_5y smallint,
			vision_5y smallint,
			oral_5y smallint,
			btype_5y smallint,
			blroutine_5y smallint,
			bllead_5y smallint,
			hemoglobin_5y smallint,
			trelements_5y smallint,
			chmeigg_5y smallint,
			hbeag_5y smallint,
			mvigg_5y smallint,
			look_5y smallint,
			stradip_5y smallint,
			bmd_5y smallint,
			si_5y smallint,
			ptq_5y smallint,
			plasma_5y smallint,
			bcell_5y smallint,
			churine_5y smallint,
			chfaeces_5y smallint,
			paquestion_5y smallint,
			tequestion_5y smallint,
			trs_5y smallint,
			asq_5y smallint,
			chealthhandbook_5y smallint,
			vacertifi_5y smallint,
			cbcl_5y smallint,
			abc_5y smallint,
			completerkd_5y varchar(50),
			notekd_5y varchar(255),
			phquestion_5y smallint,
			notetel_5y varchar(255),
			completertel_5y varchar(50),
			kindergarten_5y varchar(255),
			enrolldate_5y datetime,
			class_5y varchar(255),
			exdate_5y datetime,
			pqudate_5y datetime,
			wppsidate_5y datetime,
			wppsi_5y smallint,
			completerwpp_5y varchar(50),
			notewpp_5y varchar(50),
			PRIMARY KEY  (id)
		)";

		dbDelta( $sql );
		
	}


	//for table hos_pre_birth_basic_info
	function get_pre_birth_id_by_no1( $no1 ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_basic_info';

        $sql = 'SELECT id FROM ' . $table . ' WHERE no1="' . esc_attr( $no1 ) . '"';

        $id = $wpdb->get_var( $sql );

        return $id;
	}

	function get_pre_birth_id_by_no2( $no2 ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_basic_info';

        $sql = 'SELECT id FROM ' . $table . ' WHERE no2="' . esc_attr( $no2 ) . '"';

        $id = $wpdb->get_var( $sql );

        return $id;
	}

	function add_pre_birth_basic_info( $data ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_basic_info';

        $wpdb->insert( $table, $data );

        return $wpdb->insert_id;
	}

	function update_pre_birth_basic_info( $data, $where ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_basic_info';
        
        $wpdb->update( $table, $data, $where );

        return;
	}

	function get_pre_birth_info_by_id( $id ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_basic_info';

        $sql = 'SELECT * FROM ' . $table . ' WHERE id=' . $id;

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $result;
	}

	function get_pre_birth_info_by_no1( $no1 ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_basic_info';

        $sql = 'SELECT * FROM ' . $table . ' WHERE no1="' . $no1 . '"';

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $result;
	}



	//for table hos_pre_birth_table_status
	function add_pre_birth_table_status( $data ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_table_status';

        $sql = 'SELECT tab_id FROM ' . $table . ' WHERE tab_no="' . $data['tab_no'] . '"';

        $tab_id = $wpdb->get_var( $sql );

        if( empty( $tab_id ) )
        	$wpdb->insert( $table, $data );

        return;
	}

	function get_pre_birth_table_status( $tab_no, $tab_type ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_table_status';

        $sql = 'SELECT tab_id FROM ' . $table . ' WHERE tab_no="' . $tab_no . '" AND tab_type=' . $tab_type;

        $tab_id = $wpdb->get_var( $sql );

        return $tab_id;
	}

	function get_related_tables_by_time( $time, $tab_type ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_table_status';

		$sql = 'SELECT * FROM ' . $table . ' WHERE tab_type="' . $tab_type . '" AND start_time<="' . $time . '" ORDER BY start_time';

		$results = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $results;
	}

	function update_pre_birth_table_status( $data, $where ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_table_status';

        $wpdb->update( $table, $data, $where );

        return;
	} 

	function get_all_table_info( $tab_type ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_pre_birth_table_status';

		$sql = 'SELECT tab_id,tab_no FROM ' . $table . ' WHERE tab_type="' . $tab_type . '" ORDER BY start_time';

		$results = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $results;
	}




	//for patient status table
	function update_pre_birth_patient_status( $data, $id, $model ) {
		global $wpdb;

		switch ( $model ) {
			case $this->model_m1:
				$table = $wpdb->prefix . 'hos_pre_birth_one_month_status';

				break;

			case $this->model_m6:
				$table = $wpdb->prefix . 'hos_pre_birth_six_month_status';

				break;

			case $this->model_y1:
				$table = $wpdb->prefix . 'hos_pre_birth_one_year_status';

				break;

			case $this->model_y2:
				$table = $wpdb->prefix . 'hos_pre_birth_two_year_status';

				break;
			
			default:
				# code...
				break;
		}

		$sql = 'SELECT * FROM ' . $table . ' WHERE id=' . $id;
		$row = $wpdb->get_row( $sql, 'ARRAY_A' );

		if( !empty( $row ) ) {
			$wpdb->update( $table, $data, array( 'id' => $id ) );
		}else{
			$wpdb->insert( $table, $data );
		}
 
        return;
	}

	function get_patient_info_status_by_id( $id, $model ) {
		global $wpdb;

		switch ( $model ) {
			case $this->model_m1:
				$patient_status = $wpdb->prefix . 'hos_pre_birth_one_month_status';

				break;

			case $this->model_m6:
				$patient_status = $wpdb->prefix . 'hos_pre_birth_six_month_status';

				break;

			case $this->model_y1:
				$patient_status = $wpdb->prefix . 'hos_pre_birth_one_year_status';

				break;

			case $this->model_y2:
				$patient_status = $wpdb->prefix . 'hos_pre_birth_two_year_status';

				break;
			
			default:
				# code...
				break;
		}

		$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';

		$sql = 'SELECT *,tb.id AS id FROM ' . $table_basic . ' AS tb LEFT JOIN ' . $patient_status . ' AS ps ON tb.id = ps.id where tb.id=' . $id;

		$result = $wpdb->get_row( $sql, 'ARRAY_A' );

		return $result;
	}	


	//入园体检表
	//
	function get_ry_exam_id_by_no2( $no2 ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_ry_exam_info';

        $sql = 'SELECT id FROM ' . $table . ' WHERE no2="' . $no2 . '"';

        $id = $wpdb->get_var( $sql );

        return $id;
	}

	function get_ry_exam_id_by_no1( $no1 ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_ry_exam_info';

        $sql = 'SELECT id FROM ' . $table . ' WHERE no1="' . $no1 . '"';

        $id = $wpdb->get_var( $sql );

        return $id;
	}

	function add_ry_exam_info( $data ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_ry_exam_info';

        $wpdb->insert( $table, $data );

        return $wpdb->insert_id;
	}

	function update_ry_exam_info( $data, $where ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_ry_exam_info';

        $wpdb->update( $table, $data, $where );

        return;
	}


	//3-4,5-6岁随访表
	//
	function get_four_track_info_by_no2( $no2, $model ) {
		global $wpdb;

		switch ( $model ) {
			case $this->model_y3:
				$table = $wpdb->prefix . 'hos_3y_track_info';

				break;

			case $this->model_y5:
				$table = $wpdb->prefix . 'hos_5y_track_info';
				
				break;
			
			default:
				return;
				break;
		}

        $sql = 'SELECT * FROM ' . $table . ' WHERE no2="' . $no2 . '"';

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );
        return $result;
	}

	function get_four_track_info_by_no1( $no1, $model ) {
		global $wpdb;

		switch ( $model ) {
			case $this->model_y3:
				$table = $wpdb->prefix . 'hos_3y_track_info';

				break;

			case $this->model_y5:
				$table = $wpdb->prefix . 'hos_5y_track_info';
				
				break;
			
			default:
				return;
				break;
		}

        $sql = 'SELECT * FROM ' . $table . ' WHERE no1="' . $no1 . '"';

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );
        return $result;
	}

	function add_four_track_info( $data, $model ) {
		global $wpdb;

        switch ( $model ) {
			case $this->model_y3:
				$table = $wpdb->prefix . 'hos_3y_track_info';

				break;

			case $this->model_y5:
				$table = $wpdb->prefix . 'hos_5y_track_info';
				
				break;
			
			default:
				return;
				break;
		}

        $wpdb->insert( $table, $data );

        return $wpdb->insert_id;
	}

	function update_four_track_info( $data, $where, $model ) {
		global $wpdb;


        switch ( $model ) {
			case $this->model_y3:
				$table = $wpdb->prefix . 'hos_3y_track_info';

				break;

			case $this->model_y5:
				$table = $wpdb->prefix . 'hos_5y_track_info';
				
				break;
			
			default:
				return;
				break;
		}

        $wpdb->update( $table, $data, $where );

        return;
	}


	//自定义现场备注表
	function get_remark_id_by_content( $content ) {
		global $wpdb;

		$content = esc_attr( $content );

		$table = $wpdb->prefix . 'hos_presonal_define_remark';

        $sql = 'SELECT remark_id FROM ' . $table . ' WHERE remark_content="' . $content . '"';

        $id = $wpdb->get_var( $sql );

        if( $id > 0 )
        	return $id;
        else{
        	$wpdb->insert( $table, array( 'remark_content' => $content ) );
        	return $wpdb->insert_id;
        }
	}

	function get_remark_content_by_id( $id ) {
		global $wpdb;

		$table = $wpdb->prefix . 'hos_presonal_define_remark';

        $sql = 'SELECT remark_content FROM ' . $table . ' WHERE remark_id=' . $id;

        $remark_content = $wpdb->get_var( $sql );

        return $remark_content;
	}



	//for table hos_in_birth_basic_info
	function get_in_birth_id_by_no2( $no2 ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_in_birth_basic_info';

        $sql = 'SELECT id FROM ' . $table . ' WHERE no2="' . esc_attr( $no2 ) . '"';

        $id = $wpdb->get_var( $sql );

        return $id;
	}

	function get_in_birth_info_by_no2( $no2 ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_in_birth_basic_info';

        $sql = 'SELECT * FROM ' . $table . ' WHERE no2="' . esc_attr( $no2 ) . '"';

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $result;
	}

	function add_in_birth_basic_info( $data ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_in_birth_basic_info';

        $wpdb->insert( $table, $data );

        return $wpdb->insert_id;
	}

	function update_in_birth_basic_info( $data, $where ) {
		global $wpdb;

        $table = $wpdb->prefix . 'hos_in_birth_basic_info';
        
        $wpdb->update( $table, $data, $where );

        return;
	}



	//for hos_?y_track_info
	function get_four_track_info_by_info_id( $id, $model, $hos_type ) {
		global $wpdb;

		if( $model == $this->model_y3 ) {
			$table_track_info = $wpdb->prefix . 'hos_3y_track_info';
		}elseif( $model == $this->model_y5 ) {
			$table_track_info = $wpdb->prefix . 'hos_5y_track_info';
		}

		if( $hos_type == 2 ) {
			$table_basic = $wpdb->prefix . 'hos_pre_birth_basic_info';

			$sql = 'SELECT ti.*,tb.bname,tb.name,tb.hname,tb.dedate,tb.pphone,tb.hphone FROM ' . $table_track_info . ' AS ti LEFT JOIN ' . $table_basic . ' AS tb ON tb.no1 = ti.no1 WHERE ti.hos_type="' . $hos_type . '" AND ti.id=' . $id;
		}elseif( $hos_type == 1 ) {
			$table_basic = $wpdb->prefix . 'hos_in_birth_basic_info';

			$sql = 'SELECT ti.*,tb.bname,tb.name,tb.hname,tb.dedate,tb.pphone,tb.hphone FROM ' . $table_track_info . ' AS ti LEFT JOIN ' . $table_basic . ' AS tb ON tb.no2 = ti.no2 WHERE ti.hos_type="' . $hos_type . '" AND ti.id=' . $id;
		}

		$result = $wpdb->get_row( $sql, 'ARRAY_A' );

		return $result;
	}
}
?>