<input type="hidden" name="item_id" value="<?php echo $data['item_id']; ?>">
<input type="hidden" name="no1" id="no1" value="<?php echo $data['no1']; ?>">
<input type="hidden" name="no2" id="no2" value="<?php echo $data['no2']; ?>">
<table class="table table-bordered table-hover table-consdened">
  <tbody>
    <tr>
      <td><b>队列编号 : </b></td>
      <td><?php echo ( $data['hos_type'] == 1 ) ? $data['item']['no2'] : $data['item']['no1']; ?></td>
      <td><b>母亲姓名 : </b></td>
      <td><?php echo $data['item']['name']; ?></td>
    </tr>

    <tr>
      <td><b>儿童姓名 : </b></td>
      <td><?php echo $data['item']['bname']; ?></td>
      <td><b>出生日期 : </b></td>
      <td><?php echo $this->translate_date( $data['item']['dedate'] ); ?></td>
    </tr>

    <tr>
      <td><b>区 : </b></td>
      <td>
        <select name="district">
          <option value="">无</option>
          <?php foreach ( $this->meu_arr as $key => $meu ) { ?>
            <option value="<?php echo $key; ?>" <?php selected( $key, $data['item']['district'] ); ?>><?php echo $meu; ?></option>
          <?php } ?>
        </select>
      </td>
      <td><b>幼儿园名称 : </b></td>
      <td><input type="text" name="kindergarten" size="10" maxlength="10" value="<?php echo $data['item']['kindergarten'.$data['pre']]; ?>" /></td>
    </tr>

    <?php //if( $data['model'] == $this->model_y3 ){ ?>
      <tr>
        <td><b>入园日期 : </b></td>
        <td><input class="datepicker hos_input" name="enrolldate" type="text" size="10" maxlength="10" value="<?php echo $this->translate_date( $data['item']['enrolldate'.$data['pre']] ); ?>" /></td>
        <td><b>班级 : </b></td>
        <td><input type="text" name="class" size="10" maxlength="10" value="<?php echo $data['item']['class'.$data['pre']]; ?>" /></td>
      </tr>
    <?php //} ?>

    <tr>
      <td><b>体检日期 : </b></td>
      <td><input class="datepicker hos_input" name="exdate" type="text" size="10" maxlength="10" value="<?php echo $this->translate_date( $data['item']['exdate'.$data['pre']] ); ?>" /></td>
      <td><b>幼儿园完成人 : </b></td>
      <td><input type="text" name="completerkd" size="10" maxlength="10" value="<?php echo $data['item']['completerkd'.$data['pre']]; ?>" /></td>
    </tr>
  </tbody>
</table>