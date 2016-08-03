<div class="container-fluid">
	<input type="hidden" name="hos_type" id="hos_type" value="<?php echo $data['hos_type']; ?>" />	
	<input type="hidden" name="model" id="model" value="<?php echo $data['model']; ?>" />	
	<input type="hidden" name="hos_action" id="hos_action" value="<?php echo $data['hos_action']; ?>" />
	<div>
		<input type="radio" name="import_action" value="area_exam" checked />&nbsp;&nbsp;区妇幼体检项目&nbsp;&nbsp;
		<input type="radio" name="import_action" value="question_collect" />&nbsp;&nbsp;问卷和样本收集情况&nbsp;&nbsp;
		<?php if( $data['model'] == $this->model_y5 ){ ?>
		<input type="radio" name="import_action" value="wpp_test" />&nbsp;&nbsp;韦氏测试&nbsp;&nbsp;
		<?php } ?>
	</div>
	<div id="track_import_div">
		<input name="four_import" id="four_import" style="display:none;" type="file">
	  <button class="btn btn-primary btn-sm four_import_div" id="four_import_submit"><i class="glyphicon glyphicon-upload"></i>&nbsp;导入</button>
	</div>
  <div>
    <h4>文件内容格式示例(第二行参数必须保持一致)</h4>
  </div>

  <?php if( $data['model'] == $this->model_y3 ){ ?>
    <div id="hos_four_track_content" style="overflow:auto;">
    	<table class="table table-bordered hos_table1">
    			<thead>
    				<tr>
              <?php if( $data['hos_type'] == 1 ){ ?>
                <th>队列号no2</th>
              <?php }else{ ?>
                <th>队列号no1</th>
              <?php } ?>
    					<th>区</th>
    					<th>体格测量和评价3岁</th>
    					<th>体格检查3岁</th>
    					<th>视力3岁</th>
    					<th>听力3岁</th>
    					<th>口腔检查及防龋3岁</th>
    					<th>血型</th>
    					<th>血常规3岁</th>
    					<th>血铅3岁</th>
    					<th>血红蛋白3岁</th>
    					<th>微量元素3岁</th>
    					<th>水痘带状疱疹病毒IgG抗体3岁</th>
    					<th>乙肝表面抗体或两对半3岁</th>
    					<th>麻疹病毒IgG抗体3岁</th>
    					<th>注视性质检查3岁</th>
    					<th>斜视度与复视3岁</th>
    					<th>骨密度3岁</th>
    					<th>感统3岁</th>
    					<th>气质3岁</th>
    				</tr>
    			</thead>

    			<tbody>
    				<tr>
    					<?php if( $data['hos_type'] == 1 ){ ?>
                <th>no2</th>
              <?php }else{ ?>
                <th>no1</th>
              <?php } ?>
    					<th>district</th>
    					<th>phmeas_3y</th>
    					<th>pexam_3y</th>
              <th>vision_3y</th>
    					<th>hearing_3y</th>
    					<th>oral_3y</th>
    					<th>btype_3y</th>
    					<th>blroutine_3y</th>
    					<th>bllead_3y</th>
    					<th>hemoglobin_3y</th>
    					<th>trelements_3y</th>
    					<th>chmeigg_3y</th>
    					<th>hbeag_3y</th>
    					<th>mvigg_3y</th>
    					<th>look_3y</th>
    					<th>stradip_3y</th>
    					<th>bmd_3y</th>
    					<th>si_3y</th>
    					<th>ptq_3y</th>
    				</tr>
    				<tr>
    					<th>hp70001</th>
    					<th>洪山区</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    					<th>1</th>
    				</tr>
    			</tbody>
    	</table> 
  	</div>
  	<div id="area_exam_table" class="hos_hidden">
  		<table class="table table-bordered hos_table1">
  			<thead>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>队列号no2</th>
            <?php }else{ ?>
              <th>队列号no1</th>
            <?php } ?>
            <th>区</th>
            <th>体格测量和评价3岁</th>
            <th>体格检查3岁</th>
            <th>视力3岁</th>
            <th>听力3岁</th>
            <th>口腔检查及防龋3岁</th>
            <th>血型</th>
            <th>血常规3岁</th>
            <th>血铅3岁</th>
            <th>血红蛋白3岁</th>
            <th>微量元素3岁</th>
            <th>水痘带状疱疹病毒IgG抗体3岁</th>
            <th>乙肝表面抗体或两对半3岁</th>
            <th>麻疹病毒IgG抗体3岁</th>
            <th>注视性质检查3岁</th>
            <th>斜视度与复视3岁</th>
            <th>骨密度3岁</th>
            <th>感统3岁</th>
            <th>气质3岁</th>
  				</tr>
  			</thead>

  			<tbody>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>no2</th>
            <?php }else{ ?>
              <th>no1</th>
            <?php } ?>
  					<th>district</th>
            <th>phmeas_3y</th>
            <th>pexam_3y</th>
            <th>vision_3y</th>
            <th>hearing_3y</th>
            <th>oral_3y</th>
            <th>btype_3y</th>
            <th>blroutine_3y</th>
            <th>bllead_3y</th>
            <th>hemoglobin_3y</th>
            <th>trelements_3y</th>
            <th>chmeigg_3y</th>
            <th>hbeag_3y</th>
            <th>mvigg_3y</th>
            <th>look_3y</th>
            <th>stradip_3y</th>
            <th>bmd_3y</th>
            <th>si_3y</th>
            <th>ptq_3y</th>
  				</tr>
  				<tr>
  					<th>hp70001</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
            <th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  				</tr>
  			</tbody>
  	</table>
  	</div>
  	<div id="question_collect_table" class="hos_hidden">
  		<table class="table table-bordered hos_table1" >
  			<thead>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>队列号no2</th>
            <?php }else{ ?>
              <th>队列号no1</th>
            <?php } ?>
  					<th>儿童血浆3岁</th>
  					<th>儿童血细胞3岁</th>
  					<th>儿童尿液3岁</th>
  					<th>儿童粪便3岁</th>
  					<th>家长问卷3岁</th>
            <th>教师问卷3岁</th>
            <th>教师多动问卷3岁</th>
  					<th>多动简明问卷3岁</th>
  					<th>保健手册3岁</th>
  					<th>预防接种本3岁</th>
  					<th>cbcl量表3岁</th>
  					<th>ABC量表3岁</th>
  					<th>幼儿园完成人3岁</th>
  					<th>备注-幼儿园3岁</th>
            <th>电话随访问卷3岁</th>
  					<th>电话问卷日期3岁</th>
  					<th>备注电话3岁</th>
  					<th>电话完成人3岁</th>
  				</tr>
  			</thead>

  			<tbody>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>no2</th>
            <?php }else{ ?>
              <th>no1</th>
            <?php } ?>
  					<th>plasma_3y</th>
  					<th>bcell_3y</th>
  					<th>churine_3y</th>
  					<th>chfaeces_3y</th>
  					<th>paquestion_3y</th>
            <th>tequestion_3y</th>
            <th>trs_3y</th>
  					<th>asq_3y</th>
  					<th>chealthhandbook_3y</th>
  					<th>vacertifi_3y</th>
  					<th>cbcl_3y</th>
  					<th>abc_3y</th>
  					<th>completerkd_3y</th>
  					<th>notekd_3y</th>
            <th>phquestion_3y</th>
  					<th>pqudate_3y</th>
  					<th>notetel_3y</th>
  					<th>completertel_3y</th>
  				</tr>
  				<tr>
  					<th>hp70001</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
            <th>1</th>
            <th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>张三</th>
  					<th></th>
            <th>1</th>
  					<th>1980-12-12</th>
  					<th></th>
  					<th>李四</th>
  				</tr>
  			</tbody>
  	</table>
  	</div>
  <?php	} ?>

  <?php if( $data['model'] == $this->model_y5 ){ ?>
    <div id="hos_four_track_content" style="overflow:auto;">
    	<table class="table table-bordered hos_table1">
    			<thead>
    				<tr>
    					<?php if( $data['hos_type'] == 1 ){ ?>
                <th>队列号no2</th>
              <?php }else{ ?>
                <th>队列号no1</th>
              <?php } ?>
              <th>区</th>
              <th>体格测量和评价5岁</th>
              <th>体格检查5岁</th>
              <th>视力5岁</th>
              <th>听力5岁</th>
              <th>口腔检查及防龋5岁</th>
              <th>血型</th>
              <th>血常规5岁</th>
              <th>血铅5岁</th>
              <th>血红蛋白5岁</th>
              <th>微量元素5岁</th>
              <th>水痘带状疱疹病毒IgG抗体5岁</th>
              <th>乙肝表面抗体或两对半5岁</th>
              <th>麻疹病毒IgG抗体5岁</th>
              <th>注视性质检查5岁</th>
              <th>斜视度与复视5岁</th>
              <th>骨密度5岁</th>
              <th>感统5岁</th>
              <th>气质5岁</th>
    				</tr>
    			</thead>

    			<tbody>
    				<tr>
    					<?php if( $data['hos_type'] == 1 ){ ?>
                <th>no2</th>
              <?php }else{ ?>
                <th>no1</th>
              <?php } ?>
              <th>district</th>
              <th>phmeas_5y</th>
              <th>pexam_5y</th>
              <th>vision_5y</th>
              <th>hearing_5y</th>
              <th>oral_5y</th>
              <th>btype_5y</th>
              <th>blroutine_5y</th>
              <th>bllead_5y</th>
              <th>hemoglobin_5y</th>
              <th>trelements_5y</th>
              <th>chmeigg_5y</th>
              <th>hbeag_5y</th>
              <th>mvigg_5y</th>
              <th>look_5y</th>
              <th>stradip_5y</th>
              <th>bmd_5y</th>
              <th>si_5y</th>
              <th>ptq_5y</th>
    				</tr>
    				<tr>
    					<th>hp70001</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
    				</tr>
    			</tbody>
    	</table> 
  	</div>
  	<div id="area_exam_table" class="hos_hidden">
  		<table class="table table-bordered hos_table1">
  			<thead>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>队列号no2</th>
            <?php }else{ ?>
              <th>队列号no1</th>
            <?php } ?>
  					<th>区</th>
            <th>体格测量和评价5岁</th>
            <th>体格检查5岁</th>
            <th>视力5岁</th>
            <th>听力5岁</th>
            <th>口腔检查及防龋5岁</th>
            <th>血型</th>
            <th>血常规5岁</th>
            <th>血铅5岁</th>
            <th>血红蛋白5岁</th>
            <th>微量元素5岁</th>
            <th>水痘带状疱疹病毒IgG抗体5岁</th>
            <th>乙肝表面抗体或两对半5岁</th>
            <th>麻疹病毒IgG抗体5岁</th>
            <th>注视性质检查5岁</th>
            <th>斜视度与复视5岁</th>
            <th>骨密度5岁</th>
            <th>感统5岁</th>
            <th>气质5岁</th>
  				</tr>
  			</thead>

  			<tbody>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>no2</th>
            <?php }else{ ?>
              <th>no1</th>
            <?php } ?>
  					<th>district</th>
            <th>phmeas_5y</th>
            <th>pexam_5y</th>
            <th>vision_5y</th>
            <th>hearing_5y</th>
            <th>oral_5y</th>
            <th>btype_5y</th>
            <th>blroutine_5y</th>
            <th>bllead_5y</th>
            <th>hemoglobin_5y</th>
            <th>trelements_5y</th>
            <th>chmeigg_5y</th>
            <th>hbeag_5y</th>
            <th>mvigg_5y</th>
            <th>look_5y</th>
            <th>stradip_5y</th>
            <th>bmd_5y</th>
            <th>si_5y</th>
            <th>ptq_5y</th>
  				</tr>
  				<tr>
  					<th>hp70001</th>
  					<th>洪山区</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  				</tr>
  			</tbody>
    	</table> 
  	</div>
  	<div id="question_collect_table" class="hos_hidden">
  		<table class="table table-bordered hos_table1" >
  			<thead>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>队列号no2</th>
            <?php }else{ ?>
              <th>队列号no1</th>
            <?php } ?>
            <th>儿童血浆5岁</th>
            <th>儿童血细胞5岁</th>
            <th>儿童尿液5岁</th>
            <th>儿童粪便5岁</th>
            <th>家长问卷5岁</th>
            <th>教师问卷5岁</th>
            <th>教师多动问卷5岁</th>
            <th>多动简明问卷5岁</th>
            <th>保健手册5岁</th>
            <th>预防接种本5岁</th>
            <th>cbcl量表5岁</th>
            <th>ABC量表5岁</th>
            <th>幼儿园完成人5岁</th>
            <th>备注-幼儿园5岁</th>
            <th>电话随访问卷5岁</th>
            <th>电话问卷日期5岁</th>
            <th>备注电话5岁</th>
            <th>电话完成人5岁</th>
  				</tr>
  			</thead>

  			<tbody>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>no2</th>
            <?php }else{ ?>
              <th>no1</th>
            <?php } ?>
  					<th>plasma_5y</th>
            <th>bcell_5y</th>
            <th>churine_5y</th>
            <th>chfaeces_5y</th>
            <th>paquestion_5y</th>
            <th>tequestion_5y</th>
            <th>trs_5y</th>
            <th>asq_5y</th>
            <th>chealthhandbook_5y</th>
            <th>vacertifi_5y</th>
            <th>cbcl_5y</th>
            <th>abc_5y</th>
            <th>completerkd_5y</th>
            <th>notekd_5y</th>
            <th>phquestion_5y</th>
            <th>pqudate_5y</th>
            <th>notetel_5y</th>
            <th>completertel_5y</th>
  				</tr>
  				<tr>
  					<th>hp70001</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
            <th>1</th>
  					<th>1</th>
  					<th>1</th>
  					<th>1</th>
            <th>张三</th>
  					<th></th>
  					<th>1</th>
  					<th>2016-01-01</th>
  					<th></th>
  					<th>张三</th>
  				</tr>
  			</tbody>
  		</table>
  	</div>
  	<div id="wpp_test_table" class="hos_hidden">
  		<table class="table table-bordered hos_table1" >
  			<thead>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>队列号no2</th>
            <?php }else{ ?>
              <th>队列号no1</th>
            <?php } ?>
  					<th>韦氏测试日期5岁</th>
  					<th>韦氏测试5岁</th>
  					<th>韦氏完成人5岁</th>
  					<th>备注-韦氏</th>
  				</tr>
  			</thead>

  			<tbody>
  				<tr>
  					<?php if( $data['hos_type'] == 1 ){ ?>
              <th>no2</th>
            <?php }else{ ?>
              <th>no1</th>
            <?php } ?>
  					<th>wppsidate_5y</th>
  					<th>wppsi_5y</th>
  					<th>completerwpp_5y</th>
  					<th>notewpp_5y</th>
  				</tr>
  				<tr>
  					<th>hp70001</th>
  					<th>2016-01-01</th>
  					<th>1</th>
  					<th>张三</th>
  					<th></th>
  				</tr>
  			</tbody>
  		</table>
  	</div>
  <?php	} ?>
</div>