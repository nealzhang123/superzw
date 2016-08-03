
<input type="hidden" name="hos_health_manage_id" id="hos_health_manage_id" value="<?php echo $data['item']['id']?>"/>
<input type="hidden" name="user_name" id="user_name" value="<?php echo $this->user_name; ?>" />
<table class="table table-bordered table-hover table-consdened">
  <tr>
    <td><b>队列编号1 : </b></td>
    <td><input name="no1" id="hos_health_manage_no1" type="text" readonly="readonly" value="<?php echo $data['item']['no1']; ?>"/></td>
  </tr>
  <tr>
    <td><b>母亲姓名 : </b></td>
    <td><input name="name" id="hos_health_manage_name" type="text" readonly="readonly" value="<?php echo $data['item']['name']; ?>"/></td>
  </tr>
  <tr>
    <td><b>出生体重 : </b></td>
    <td><input name="var59" id="hos_health_manage_var59" type="text"  value="<?php echo $data['item']['var59']; ?>"/></td>
  </tr>

 <tr>
    <td><b>出生体重百分位数(90%) : </b></td>
    <td><input name="bw_p90gs" id="hos_health_manage_bw_p90gs" type="text"  value="<?php echo $data['item']['bw_p90gs']; ?>"/></td>
  </tr>

   <tr>
    <td><b>出生体重百分位数(10%) : </b></td>
    <td><input name="bw_p10gs" id="hos_health_manage_bw_p10gs" type="text"  value="<?php echo $data['item']['bw_p10gs']; ?>"/></td>
  </tr>
	<tr>(温馨提示：1表示有，0表示没有)</tr>
	<?php 
		foreach( $data['health_manage_option_arr'] as $key => $option ){
	?>
		<tr>
			<td><b><?php echo $this->health_manage_arr[$option]; ?> : </b></td>
			<td>
				<label class="radio-inline">
					<input type="radio" value="0" class="hos_health_manage_<?php echo $option; ?>" name="<?php echo $option; ?>" <?php if(0 == $data['item'][$option]) echo 'checked="checked"';?> />0
				</label>
				<label class="radio-inline">
					<input type="radio" value="1" class="hos_health_manage_<?php echo $option; ?>" name="<?php echo $option; ?>" <?php if(1 == $data['item'][$option]) echo 'checked="checked"';?> />1
				</label>
			</td>
		<tr>
	<?php
		}
	?>
</table>