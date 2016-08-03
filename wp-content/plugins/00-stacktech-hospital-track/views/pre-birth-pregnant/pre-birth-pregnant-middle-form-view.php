
<input type="hidden" name="hos_pregnant_id" id="hos_pregnant_middle_id" value="<?php echo $data['item']['id']?>"/>
<input type="hidden" name="user_name" id="user_name" value="<?php echo $this->user_name; ?>" />
<table class="table table-bordered table-hover table-consdened">
  <tr>
    <td><b>队列编号1 : </b></td>
    <td><input name="no1" id="hos_pregnant_no1" type="text" readonly="readonly" value="<?php echo $data['item']['no1']; ?>"/></td>
  </tr>
  <tr>
    <td><b>母亲姓名 : </b></td>
    <td><input name="name" id="hos_pregnant_name" type="text" readonly="readonly" value="<?php echo $data['item']['name']; ?>"/></td>
  </tr>
  <?php $lmp_time =date('Y-m-d' ,strtotime($data['item']['lmp'])); ?>
  <tr>
    <td><b>母亲电话 : </b></td>
    <td><input name="pphone" type="text" id="hos_pregnant_pphone" value="<?php echo $data['item']['pphone']; ?>" /></td>
  </tr>
  <tr>
    <td><b>父亲电话 : </b></td>
    <td><input name="hphone" type="text" id="hos_pregnant_hphone" value="<?php echo $data['item']['hphone']; ?>" /></td>
  </tr>


	<tr>(温馨提示：1表示有，0表示没有)</tr>
	<?php 
		foreach( $data['pregnant_midlle_option_arr'] as $key => $option ){
	?>
		<tr>
			<td><b><?php echo $this->pregnant_midlle_option_arr[$option]; ?> : </b></td>
			<td>
				<label class="radio-inline">
					<input type="radio" value="0" class="hos_pregnant_middle_<?php echo $option; ?>" name="<?php echo $option; ?>" <?php if(0 == $data['item'][$option]) echo 'checked="checked"';?> />0
				</label>
				<label class="radio-inline">
					<input type="radio" value="1" class="hos_pregnant_middle_<?php echo $option; ?>" name="<?php echo $option; ?>" <?php if(1 == $data['item'][$option]) echo 'checked="checked"';?> />1
				</label>
			</td>
		<tr>
	<?php
		}
	?>
  <!-- <tr>
   	<td><b>早期血清 : </b></td>
   	<td>
   		
   		<input type="radio" value="0" name="hos_pregnant_serumt1" class="hos_pregnant_serumt1"/>0
   	</td>
  </tr>
  <tr>
    <td><b>早期血浆血细胞 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_pregnant_plasma_bcellt1" class="hos_pregnant_plasma_bcellt1"checked="checked" />1
      <input type="radio" value="0" name="hos_pregnant_plasma_bcellt1" class="hos_pregnant_plasma_bcellt1"/>0
    </td>
  </tr>
  <tr>
    <td><b>早期尿 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_pregnant_urinet1" class="hos_pregnant_urinet1"checked="checked" />1
      <input type="radio" value="0" name="hos_pregnant_urinet1" class="hos_pregnant_urinet1"/>0
    </td>
  </tr> 
  <tr>
    <td><b>父亲血 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_pregnant_pablood" class="hos_pregnant_pablood"checked="checked" />1
      <input type="radio" value="0" name="hos_pregnant_pablood" class="hos_pregnant_pablood"/>0
    </td>
  </tr> 
  <tr>
    <td><b>中期血清 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_pregnant_serumt2" class="hos_pregnant_serumt2"checked="checked" />1
      <input type="radio" value="0" name="hos_pregnant_serumt2" class="hos_pregnant_serumt2"/>0
    </td>
  </tr> 
  <tr>
    <td><b>中期尿 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_pregnant_urinet2" class="hos_pregnant_urinet2"checked="checked" />1
      <input type="radio" value="0" name="hos_pregnant_urinet2" class="hos_pregnant_urinet2"/>0
    </td>
  </tr> 
  <tr>
    <td><b>晚期尿-Ent1 : </b></td>
    <td>
      <input type="radio" value="1" name="hos_pregnant_urinet3_ent1" class="hos_pregnant_urinet3_ent1"checked="checked" />1
      <input type="radio" value="0" name="hos_pregnant_urinet3_ent1" class="hos_pregnant_urinet3_ent1"/>0
    </td>
  </tr> 
 -->
  <tr>
    <td><b>孕中期电话备注 : </b></td>
    <td>
    	<select name="fut2rem" id="hos_pregnant_fut2rem">
	    	<option  value=""> 请选择备注情况</option>

    		<?php foreach($this->pregnant_middle_fut2rem_arr as $item ){
    				if( $item == $data['item']['fut2rem']){
    		?>
    			<option  selected="selected"  value="<?php echo $item?>"><?php echo $item?> </option>
    		<?php }else{?>
				<option  value="<?php echo $item?>"><?php echo $item?> </option>
    		<?php }
    			}
    		?>
   	 	</select>
    </td>
  </tr>
  
</table>