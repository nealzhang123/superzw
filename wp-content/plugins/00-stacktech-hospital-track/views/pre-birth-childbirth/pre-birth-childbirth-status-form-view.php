
<input type="hidden" name="hos_childbirth_status_id" id="hos_childbirth_status_id" value="<?php echo $data['item']['id']?>"/>
<input type="hidden" name="user_name" id="user_name" value="<?php echo $this->user_name; ?>" />
<table class="table table-bordered table-hover table-consdened">
  <tr>
    <td><b>队列编号1 : </b></td>
    <td><input name="no1" id="hos_childbirth_status_no1" type="text" readonly="readonly" value="<?php echo $data['item']['no1']; ?>"/></td>
  </tr>
  <tr>
    <td><b>母亲姓名 : </b></td>
    <td><input name="name" id="hos_childbirth_status_name" type="text" readonly="readonly" value="<?php echo $data['item']['name']; ?>"/></td>
  </tr>
  <?php $lmp_time =date('Y-m-d' ,strtotime($data['item']['lmp'])); ?>
  <tr>
    <td><b>末次月经 : </b></td>
    <td><input type="text" name="lmp" class="hos_childbirth_status_time" id="hos_childbirth_status_lmp" value="<?php echo $lmp_time; ?>"></td>
  </tr>
  <?php $dedate_time =date('Y-m-d' ,strtotime($data['item']['dedate'])); ?>
  <tr>
    <td><b>分娩日期 : </b></td>
    <td><input name="dedate" type="text" class="hos_childbirth_status_time" id="hos_childbirth_status_dedate" value="<?php echo $dedate_time; ?>" /></td>
  </tr>
 <tr>
    <td><b>队列编号2 : </b></td>
    <td><input name="no2" id="hos_childbirth_status_no2" type="text" readonly="readonly" value="<?php echo $data['item']['no2']; ?>"/></td>
  </tr>

	<tr>(温馨提示：1表示有，0表示没有)</tr>
	<?php 
		foreach( $data['childbirth_options_arr'] as $key => $option ){
	?>
		<tr>
			<td><b><?php echo $this->childbirth_status_arr[$option]; ?> : </b></td>
			<td>
				<label class="radio-inline">
					<input type="radio" value="0" class="hos_childbirth_status_<?php echo $option; ?>" name="<?php echo $option; ?>" <?php if(0 == $data['item'][$option]) echo 'checked="checked"';?> />0
				</label>
				<label class="radio-inline">
					<input type="radio" value="1" class="hos_childbirth_status_<?php echo $option; ?>" name="<?php echo $option; ?>" <?php if(1 == $data['item'][$option]) echo 'checked="checked"';?> />1
				</label>
			</td>
		<tr>
	<?php
		}
	?>
  <!-- <tr>
   	<td><b>早期血清 : </b></td>
   	<td>
   		
   		<input type="radio" value="0" name="hos_childbirth_status_serumt1" class="hos_childbirth_status_serumt1"/>0
   	</td>
  </tr>
  <tr>
    <td><b>早期血浆血细胞 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_childbirth_status_plasma_bcellt1" class="hos_childbirth_status_plasma_bcellt1"checked="checked" />1
      <input type="radio" value="0" name="hos_childbirth_status_plasma_bcellt1" class="hos_childbirth_status_plasma_bcellt1"/>0
    </td>
  </tr>
  <tr>
    <td><b>早期尿 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_childbirth_status_urinet1" class="hos_childbirth_status_urinet1"checked="checked" />1
      <input type="radio" value="0" name="hos_childbirth_status_urinet1" class="hos_childbirth_status_urinet1"/>0
    </td>
  </tr> 
  <tr>
    <td><b>父亲血 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_childbirth_status_pablood" class="hos_childbirth_status_pablood"checked="checked" />1
      <input type="radio" value="0" name="hos_childbirth_status_pablood" class="hos_childbirth_status_pablood"/>0
    </td>
  </tr> 
  <tr>
    <td><b>中期血清 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_childbirth_status_serumt2" class="hos_childbirth_status_serumt2"checked="checked" />1
      <input type="radio" value="0" name="hos_childbirth_status_serumt2" class="hos_childbirth_status_serumt2"/>0
    </td>
  </tr> 
  <tr>
    <td><b>中期尿 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_childbirth_status_urinet2" class="hos_childbirth_status_urinet2"checked="checked" />1
      <input type="radio" value="0" name="hos_childbirth_status_urinet2" class="hos_childbirth_status_urinet2"/>0
    </td>
  </tr> 
  <tr>
    <td><b>晚期尿-Ent1 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_childbirth_status_urinet3_ent1" class="hos_childbirth_status_urinet3_ent1"checked="checked" />1
      <input type="radio" value="0" name="hos_childbirth_status_urinet3_ent1" class="hos_childbirth_status_urinet3_ent1"/>0
    </td>
  </tr> 
 -->
  
</table>