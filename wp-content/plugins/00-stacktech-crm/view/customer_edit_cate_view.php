<div class="container-fluid">
	<div style="margin-left:0px;margin-right:10px;">
	  <ul class="breadcrumb" style="margin-bottom:10px;">
	    <li>
	      <a class="hdrLink" href="<?php echo admin_url('admin.php?page=crm_customer');?>">客户</a>
	    </li>
	    <li class="active">新增视图 </li>
	  </ul>
	</div>

	<div style="margin-left:0px;margin-right:10px;">
		<table class="table table-bordered table-condensed table-striped"> 
			<tbody>
        <tr>  
          <td><b>基本信息</b></td>
        </tr>
        <tr>  
          <td>
            <table class="table table-bordered table-hover"> 
              <tbody>
                <tr>
                  <td style="background-color:#fff;" width="10%">
                    <font color="red">*</font>视图名称</td>
                  <td style="background-color:#fff;" width="20%">
                    <input type="text" name="cate_name" value="">
                  </td> 
                  <td style="background-color:#fff;" width="10%">
                    是否为关键视图
                  </td>
									<td style="background-color:#fff">
										<input type="checkbox" name="is_pivotal" value="0">
									</td>
									<td style="background-color:#fff;" width="10%">
                    设为默认视图
                  </td>
									<td style="background-color:#fff">
										<input type="checkbox" name="is_default" value="0">
									</td>
                </tr>
              </tbody>  
            </table>
          </td>
        </tr>
        <tr>

        <tr>  
          <td><b>选择列表中显示字段</b></td>
        </tr>
        <tr>  
          <td>
          <table class="table table-bordered table-hover table-condensed"> 
            <tbody>
			        <tr>
                <td>
                  #1 <select name="column1" id="row1" onchange="checkDuplicate(this);">
                    <option value="">无</option>
                    </select>
                </td>
              </tr>
              <tr>
            </tbody>
          </table>
          </td>
        </tr>

        <tr>  
          <td><b>设置过滤条件</b></td>
        </tr>
        <tr>  
          <td>
          	<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
              <div class="tab-pane active" id="tab1">
                <div class="well" style="margin-bottom:10px;">
                  <b>设置查询条件以过滤视图列表：</b><br>
                  1)当时间段为自定义时，开始日期和结束日期将为指定的日期，例如2010-10-10。<br>
                  2)当时间段为非自定义时，开始日期和结束日期将为动态的日期，例如选择本周时，开始日期和结束日期将分别为本周的周一和周末，而不是固定的日期。
                </div>
                <table class="table table-bordered table-hover table-condensed"> 
                  <tbody>
                    <tr>
                      <td colspan="2">
                          <b>根据时间类型字段设置过滤条件</b>
                      </td>
                    </tr>
                    <tr>
                      <td>
                         选择查询字段 :
                      </td>
                      <td>
                        <select name="date_filter_field" class="select">
                          <option value="last_sms_date">客户 - 最新发送短信日期</option>
                          <option value="last_email_date">客户 - 最新发送邮件日期</option>
                          <option value="last_record_time">客户 - 最新联系时间</option>
                          <option value="next_record_time">客户 - 下次联系日期</option>
                          <option value="customer_create_time">客户 - 创建时间</option>
                          <option value="customer_modify_time">客户 - 修改时间</option>
                      	</select>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        选择时间段 :
                      </td>
                     	<td>
                        <select name="stdDateFilter" class="select" onchange="showDateRange(this.options[this.selectedIndex].value )">
                          <option value="custom">自定义</option>
                          <option value="prevfy">上财年</option>
                          <option value="thisfy">本财年</option>
                          <option value="nextfy">下财年</option>
                          <option value="prevfq">上季度</option>
                          <option value="thisfq">本季度</option>
                          <option value="nextfq">下季度</option>
                          <option value="yesterday">昨天</option>
                          <option value="today">今天</option>
                          <option value="tomorrow">明天</option>
                          <option value="lastweek">上星期</option>
                          <option value="thisweek">本星期</option>
                          <option value="nextweek">下星期</option>
                          <option value="lastmonth">上月</option>
                          <option value="thismonth">本月</option>
                          <option value="nextmonth">下月</option>
                          <option value="before3days">3天前</option>
                          <option value="before7days">7天前</option>
                          <option value="before15days">15天前</option>
                          <option value="before30days">30天前</option>
                          <option value="before60days">60天前</option>
                          <option value="before100days">100天前</option>
                          <option value="before180days">180天前</option>
                          <option value="after3days">3天后</option>
                          <option value="after7days">7天后</option>
                          <option value="after15days">15天后</option>
                          <option value="after30days">30天后</option>
                          <option value="after60days">60天后</option>
                          <option value="after100days">100天后</option>
                          <option value="after180days">180天后</option>
                          <option value="last3days">前3天</option>
                          <option value="last7days">前7天</option>
                          <option value="last15days">前15天</option>
                          <option value="last30days">前30天</option>
                          <option value="last60days">前60天</option>
                          <option value="last90days">前90天</option>
                          <option value="last180days">前180天</option>
                          <option value="next3days">后3天</option>
                          <option value="next7days">后7天</option>
                          <option value="next15days">后15天</option>
                          <option value="next30days">后30天</option>
                          <option value="next60days">后60天</option>
                          <option value="next90days">后90天</option>
                          <option value="next180days">后180天</option>
                      	</select>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        开始日期 :
                      </td>
                      <td>
											  <div class="input-append date" data-date-format="yyyy-mm-dd" data-date="" id="jscal_field_date_start">
												<input type="text" name="startdate" value="" style="width:180px">
												<span class="add-on">
													<i class="cus-date"></i>
												</span>
											  </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        结束日期 :
                      </td>
                      <td>
											  <div class="input-append date" data-date-format="yyyy-mm-dd" data-date="" id="jscal_field_date_end">
												<input type="text" name="enddate" value="" style="width:180px">
												<span class="add-on">
													<i class="cus-date"></i> <!-- change by ligangze 2013-08-20-->
												</span>
											  </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td style="text-align:center">
						<button title="取消 [Alt+X]" accesskey="X" class="btn btn-primary btn-small" name="button2" onclick="goback()" type="button">
						<i class="icon-arrow-left icon-white"></i> 取消
					   </button>
					   &nbsp;&nbsp; &nbsp;&nbsp;
		               <button title="保存 [Alt+S]" accesskey="S" class="btn btn-success btn-small" name="button2" type="submit" onclick="return checkDuplicate();">
						<i class="icon-ok icon-white"></i> 保存
					   </button>
          </td>
        </tr>
      </tbody>
    </table>
	</div>
</div>