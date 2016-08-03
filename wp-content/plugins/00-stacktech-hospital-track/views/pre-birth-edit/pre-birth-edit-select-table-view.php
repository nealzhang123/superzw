<div>
	选择表 : <select name="tab_no_edit" id="tab_no_edit">
    <option value="0">全部</option>
    <?php 
    foreach ( $data['tables'] as $table ) {
    ?>  
      <option value="<?php echo $table['tab_no']; ?>" <?php selected( $data['tab_no'], $table['tab_no'] ); ?>><?php echo $table['tab_no']; ?></option>
    <?php } ?>
  </select>
</div>