<div class="container-fluid">
	<div>
		<div class="pull-left">
		  <input type="hidden" value="<?php echo $data['model']; ?>" name="model" id="model">
		  <input type="hidden" value="<?php echo $data['hos_action']; ?>" name="hos_action" id="hos_action">
		  <?php echo $data['table_name']; ?> : <select name="tab_no" id="tab_no">
		  	<option value="0">全部</option>
		  	<?php 
		  	foreach ( $data['tables'] as $table ) {
		  	?>
		  		<option value="<?php echo $table['tab_no']; ?>" <?php selected( $data['tab_no'], $table['tab_no'] ); ?>><?php echo $table['tab_no']; ?></option>
		  	<?php } ?>
		  </select>
		  &nbsp;&nbsp;
		  <input name="track_import" id="track_import" style="display:none;" type="file" />
		  <button class="btn btn-primary btn-sm track_result_import"><i class="glyphicon glyphicon-upload"></i>&nbsp;导入</button>
		  &nbsp;&nbsp;
		  <button class="btn btn-primary btn-sm exec_export" data-href="<?php echo admin_url('admin.php?page=track_export_file'); ?>"><i class="glyphicon glyphicon-download"></i>&nbsp;导出</button>
	  </div>
	  <div class="pull-right" id="hos_table_nav_top">
	  	<?php $this->get_table_nav_top_view( $data['list'] ); ?>
	  </div>
  </div>
  
  <div class="list_content">
		<div id="hos_table_content">
  	<?php $this->get_pre_birth_list_content_view( $data['list'] ); ?>
  	</div>
  </div>

	<div class="tablenav bottom" id="hos_table_nav_bottom">
		<?php $this->get_table_nav_bottom_view( $data['list'] ); ?>
	</div>
</div>