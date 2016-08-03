<div class="container-fluid">
	<div class="row">
		<div class="col-xs-4 fadeInLeft" data-wow-duration="1s" data-wow-delay=".6s" >
			<div id="four_search_div">
				<form id="four_search_form">
					<div class="date-section">
						出生日期 :   
					  <input type="text" name="min_exam_date" id="min_exam_date" maxlength="10" size="10" class="datepicker hos_input">
		  			至
		  			<input type="text" name="max_exam_date" id="max_exam_date" maxlength="10" size="10" class="datepicker hos_input">
					</div>

					<div>
						<table class="table table-bordered four_exam_form">
							<tr>
								<td>入园体检 : <select id="hos_exam_status" name="hos_exam_status">
										<option value="-1">全部</option>
										<option value="1">是</option>
										<option value="2">否</option>
                  </select>
                </td>
                <td>体检单位 : <select id="hos_exam_meu" name="hos_exam_meu">
									<option value="-1">全部</option>
									<?php foreach ( $this->meu_arr as $key => $meu ) { ?>
										<option value="<?php echo $key; ?>"><?php echo $meu; ?></option>
									<?php } ?>
									</select>
								</td>
								<td class="search_td">
									<a class="btn btn-primary btn-sm exam_search"><i class="glyphicon glyphicon-search"></i>&nbsp;查询</a>
								</td>
							</tr>

							<tr>
								<td>血样类型 : <select id="hos_exam_blood_type" name="hos_exam_blood_type">
										<option value="-1">全部</option>
										<option value="1">血清</option>
										<option value="2">血浆</option>
										<option value="3">血细胞</option>
                  </select>
								</td>
								<td>血样质量 : <select id="hos_exam_bloodqu" name="hos_exam_bloodqu">
										<option value="-1">全部</option>
										<option value="1">正常</option>
										<option value="2">溶血</option>
										<option value="3">其他异常</option>
                  </select>
								</td>
								<td class="export_td">
								<a class="btn btn-primary btn-sm exam_export" data-href="<?php echo admin_url('admin.php?page=four_track_export'); ?>"><i class="glyphicon glyphicon-download"></i>&nbsp;导出</a>
								</td>
							</tr>
	
							<tr>
								<td>血常规结果 : <select id="hos_exam_brtr" name="hos_exam_brtr">
										<option value="-1">全部</option>
										<option value="1">有</option>
										<option value="0">无</option>
                  </select>
                </td>
								<td>ALT结果 : <select id="hos_exam_altr" name="hos_exam_altr">
										<option value="-1">全部</option>
										<option value="1">有</option>
										<option value="0">无</option>
                  </select>
								</td>
								<td>共&nbsp;<span id="result_count"></span>&nbsp;人</td>
							</tr>
						</table>
					</div>

					<div class="number_people">
						<table class="table table-bordered form-table">
							<tbody>
								<tr>
									<th scope="row">入园体检总人数</th>
									<td><span id="total_count"></span></td>
								</tr>
								<tr>
									<th scope="row">收集入园血清人数</th>
									<td><span id="cserum_count"></span></td>
								</tr>
								<tr>
									<th scope="row">收集入园血浆血细胞人数</th>
									<td><span id="cplasma_count"></span></td>
								</tr>
								<tr>
									<th scope="row">有血检结果人数</th>
									<td><span id="brtr_count"></span></td>
								</tr>
								<tr>
									<th scope="row">队列中年满3周岁人数</th>
									<td><span id="age_count1"></span></td>
								</tr>
								<tr>
									<th scope="row">队列中年满3.5周岁人数</th>
									<td><span id="age_count2"></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>

		<div class="col-xs-8">
			<div id="hos_exam_stat_top" class="pull-right">
			</div>
			<div id="hos_exam_stat_content">
			</div>
			<div id="hos_exam_stat_bottom" class="tablenav bottom tablenav-pages">
			</div>
		</div>
	</div>
</div>