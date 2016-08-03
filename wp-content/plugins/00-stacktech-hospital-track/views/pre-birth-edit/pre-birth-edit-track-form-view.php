<input type="hidden" name="hos_edit_id" id="hos_edit_id" value="<?php echo $data['item']['id']; ?>" />
<table class="table table-bordered table-hover table-consdened">
  <tbody>
    <tr>
      <td colspan="2"><b>队列编号 : </b></td>
      <td colspan="2"><?php echo $data['item']['no1']; ?></td>
    </tr>

    <tr>
      <td colspan="2"><b>母亲姓名 : </b></td>
      <td colspan="2"><?php echo $data['item']['name']; ?></td>
    </tr>

    <tr>
      <td colspan="2"><b><?php echo $data['title']; ?> : </b></td>
      <td colspan="2"><input class="datepicker hos_input" name="hos_edit_date" type="text" id="hos_edit_date" value="<?php echo date( 'Y-m-d', strtotime( $data['fpdate'] ) ); ?>" /></td>
    </tr>

    <?php if( $this->model_y1 == $data['model'] ) { ?>
      <tr>
        <td colspan="2"><b>宝宝姓名 : </b></td>
        <td colspan="2"><input name="hos_edit_cname" type="text" id="hos_edit_cname" value="<?php echo $data['item']['cname']; ?>" /></td>
      </tr>
    <?php } ?>
    <tr>
      <?php 
      foreach ( $data['track_options_arr'] as $key => $option ) { 
        $option_key = $data['pre'].$option;
      ?>
        <td><b><?php echo $this->track_arr[$option]; ?></b></td>
        <td>
          <label class="radio-inline">
            <input type="radio" name="hos_edit_<?php echo $option; ?>" value="1" <?php if( 1 == $data['item'][$option_key] ) echo 'checked="checked"'; ?> />是
          </label>
          <label class="radio-inline">
            <input type="radio" name="hos_edit_<?php echo $option; ?>" value="0" <?php if( 1 != $data['item'][$option_key] ) echo 'checked="checked"'; ?> />否
          </label>
        </td>
        <?php if( $key%2 != 0 && $key != count( $area['area_options'] ) ) { ?>
          </tr>
          <tr>
        <?php }?>
      <?php } //end of foreach ?>
    </tr>
  </tbody>
</table>