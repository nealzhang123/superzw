<?php 
//echo '<pre>';print_r($data['item']);echo '</pre>';exit();

switch ( $data['model'] ) {
	case $this->model_y3:
		$pre = '_3y';
		$options = array(
			'kindergarten'    => '幼儿园名称',
			'enrolldate'      => '入园日期',
			'class'           => '班级',
			'exdate'          => '体检日期',
			'phmeas'          => '体格测量和评价',
			'pexam'           => '体格检查',
			'vision'          => '视力',
			'hearing'         => '听力',
			'oral'            => '口腔检查及防龋',
			'btype'           => '血型',
			'blroutine'       => '血常规',
			'bllead'          => '血铅',
			'hemoglobin'      => '血红蛋白',
			'trelements'      => '微量元素',
			'chmeigg'         => '水痘带状疱疹病毒IgG抗体',
			'hbeag'           => '乙肝表面抗体或两对半',
			'mvigg'           => '麻疹病毒IgG抗体',
			'look'            => '注视性质检查',
			'stradip'         => '斜视度与复视',
			'bmd'             => '骨密度',
			'si'              => '感统',
			'ptq'             => '气质',
			'plasma'          => '血浆',
			'bcell'           => '血细胞',
			'churine'         => '尿液',
			'chfaeces'        => '粪便',
			'paquestion'      => '家长问卷',
			'tequestion'      => '教师问卷',
			'trs'             => '教师多动问卷',
			'asq'             => '多动简明问卷',
			'chealthhandbook' => '保健手册',
			'vacertifi'       => '预防接种本',
			'cbcl'            => 'cbcl量表',
			'abc'             => 'ABC量表',
			'completerkd'     => '幼儿园完成人',
			'notekd'          => '备注-幼儿园',
			'phquestion'      => '电话随访问卷',
			'pqudate'         => '电话问卷日期',
			'notetel'         => '备注电话',
			'completertel'    => '电话完成人',
			);

		$radio_arr = array(
			'phmeas', 'pexam', 'vision', 'hearing', 'oral', 'btype', 'blroutine', 'bllead', 'hemoglobin', 'trelements', 'chmeigg', 'hbeag', 'mvigg', 'look', 'stradip', 'bmd', 'si', 'ptq', 'plasma', 'bcell', 'churine', 'chfaeces', 'paquestion', 'tequestion', 'trs', 'asq', 'chealthhandbook', 'vacertifi', 'cbcl', 'abc', 'phquestion'
			);

		$text_arr = array(
			'kindergarten', 'completerkd', 'completertel', 'class'
			);

		$date_arr = array(
			'enrolldate', 'pqudate', 'exdate'
			);

		break;

	case $this->model_y5:
		$pre = '_5y';
		$options = array(
			'kindergarten'    => '幼儿园名称',
			'enrolldate'      => '入园日期',
			'class'           => '班级',
			'exdate'          => '体检日期',
			'phmeas'          => '体格测量和评价',
			'pexam'           => '体格检查',
			'vision'          => '视力',
			'hearing'         => '听力',
			'oral'            => '口腔检查及防龋',
			'btype'           => '血型',
			'blroutine'       => '血常规',
			'bllead'          => '血铅',
			'hemoglobin'      => '血红蛋白',
			'trelements'      => '微量元素',
			'chmeigg'         => '水痘带状疱疹病毒IgG抗体',
			'hbeag'           => '乙肝表面抗体或两对半',
			'mvigg'           => '麻疹病毒IgG抗体',
			'look'            => '注视性质检查',
			'stradip'         => '斜视度与复视',
			'bmd'             => '骨密度',
			'si'              => '感统',
			'ptq'             => '气质',
			'plasma'          => '血浆',
			'bcell'           => '血细胞',
			'churine'         => '尿液',
			'chfaeces'        => '粪便',
			'paquestion'      => '家长问卷',
			'tequestion'      => '教师问卷',
			'trs'             => '教师多动问卷',
			'asq'             => '多动简明问卷',
			'chealthhandbook' => '保健手册',
			'vacertifi'       => '预防接种本',
			'cbcl'            => 'cbcl量表',
			'abc'             => 'ABC量表',
			'completerkd'     => '幼儿园完成人',
			'notekd'          => '备注-幼儿园',
			'phquestion'      => '电话随访问卷',
			'pqudate'         => '电话问卷日期',
			'notetel'         => '备注电话',
			'completertel'    => '电话完成人',
			'wppsi'           => '韦氏测试',
			'wppsidate'       => '韦氏测试日期',
			'completerwpp'    => '韦氏完成人',
			'notewpp'         => '备注韦氏'
			);

		$radio_arr = array(
			'phmeas', 'pexam', 'vision', 'hearing', 'oral', 'btype', 'blroutine', 'bllead', 'hemoglobin', 'trelements', 'chmeigg', 'hbeag', 'mvigg', 'look', 'stradip', 'bmd', 'si', 'ptq', 'plasma', 'bcell', 'churine', 'chfaeces', 'paquestion', 'tequestion', 'trs', 'asq', 'chealthhandbook', 'vacertifi', 'cbcl', 'abc', 'phquestion', 'wppsi'
			);

		$text_arr = array(
			'completerkd', 'completertel', 'completerwpp', 'notewpp', 'kindergarten','class'
			);

		$date_arr = array(
			'pqudate', 'wppsidate', 'enrolldate', 'exdate'
			);

		break;
	
	default:
		# code...
		break;
}

?>
<input type="hidden" name="item_id" id="item_id" value="<?php echo $data['item']['id']; ?>" />
<input type="hidden" name="hos_type" id="hos_type" value="<?php echo $data['hos_type']; ?>" />
<input type="hidden" name="no1" id="no1" value="<?php echo $data['item']['no1']; ?>" />
<input type="hidden" name="no2" id="no2" value="<?php echo $data['item']['no2']; ?>" />
<input type="hidden" name="model" id="model" value="<?php echo $data['model']; ?>" />
<div class="table-responsive">
<table class="table table-bordered">
  <tbody>
    <tr>
    	<?php if( $data['hos_type'] == 1 ){ ?>
    		<td><b>队列编号no2 : </b></td>
      	<td><?php echo $data['item']['no2']; ?></td>
    	<?php	}else{ ?>
    		<td><b>队列编号no1 : </b></td>
      	<td><?php echo $data['item']['no1']; ?></td>
    	<?php	} ?>
      <td><b>母亲姓名 : </b></td>
      <td><?php echo $data['item']['name']; ?></td>
    </tr>

    <tr>
    	<td><b>区 : </b></td>
      <td>
      	<select name="district">
					<option value="-1">选择区</option>
					<?php foreach ( $this->meu_arr as $key => $meu ) { ?>
						<option value="<?php echo $key; ?>" <?php selected( $key, $data['item']['district'] ); ?>><?php echo $meu; ?></option>
					<?php } ?>
				</select>
      </td>
      <td><b>儿童姓名 : </b></td>
      <td><?php echo $data['item']['bname']; ?></td>
    </tr>

    <tr>
      <?php 
      $i = 1;
      foreach ( $options as $key => $option ) { 
      ?>
      	<td><b><?php echo $option; ?></b></td>
        <td>
        	<?php if( in_array( $key, $radio_arr ) ) { ?>
        		<input type="radio" name="<?php echo $key;?>" value="1" <?php if( 1 == $data['item'][$key.$pre] ) echo 'checked="checked"'; ?> />&nbsp;&nbsp;是&nbsp;&nbsp;
        		<input type="radio" name="<?php echo $key; ?>" value="0" <?php if( 1 != $data['item'][$key.$pre] ) echo 'checked="checked"'; ?> />&nbsp;&nbsp;否&nbsp;	&nbsp;
        	<?php	}elseif( in_array( $key, $text_arr ) ) { ?>
        		<input type="text" name="<?php echo $key; ?>" value="<?php echo $data['item'][$key.$pre]; ?>" />
        	<?php }elseif( $key == 'notetel' ) { ?>
        		<select name="<?php echo $key; ?>">
        			<option value="-1">无备注</option>
        			<?php foreach ( $this->note_tel_arr as $key1 => $val ) { ?>
                <option value="<?php echo $key1; ?>" <?php if( $key1 == $data['item'][$key.$pre] ) echo 'selected'; ?>><?php echo $val; ?></option>
              <?php }?> 
        		</select>
        	<?php }elseif( $key == 'notekd' ) { ?>
        		<select name="<?php echo $key; ?>">
        			<option value="-1">无备注</option>
        			<?php foreach ( $this->note_local_arr as $key2 => $val ) { ?>
                <option value="<?php echo $key2; ?>" <?php if( $key2 == $data['item'][$key.$pre] ) echo 'selected'; ?>><?php echo $val; ?></option>
              <?php }?> 
        		</select>
        	<?php }elseif( in_array( $key, $date_arr ) ) { ?>
        		<input class="datepicker hos_input" name="<?php echo $key; ?>" type="text" value="<?php echo $this->translate_date( $data['item'][$key.$pre] ); ?>">
        	<?php	} ?>
        </td>
        <?php if( $i%2 == 0 ) { ?>
          </tr>
          <tr>
        <?php }?>
      <?php $i++; } //end of foreach ?>
    </tr>
  </tbody>
</table>
</div>