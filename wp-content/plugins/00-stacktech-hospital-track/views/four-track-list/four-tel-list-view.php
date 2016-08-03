<div class="container-fluid">
	<div>
		<div class="pull-left">
		  <input type="hidden" value="<?php echo $data['model']; ?>" name="model" id="model">
		  <input type="hidden" value="<?php echo $data['hos_action']; ?>" name="hos_action" id="hos_action">
		  <input type="hidden" value="<?php echo $data['hos_type']; ?>" name="hos_type" id="hos_type">
		  <input name="four_list_import" id="four_list_import" style="display:none;" type="file" />
		  <button class="btn btn-primary btn-sm" id="four_list_import_submit"><i class="glyphicon glyphicon-upload"></i>&nbsp;导入</button>
		  &nbsp;&nbsp;
		  <button class="btn btn-primary btn-sm four_track_export" data-href="<?php echo admin_url('admin.php?page=four_track_export'); ?>"><i class="glyphicon glyphicon-download"></i>&nbsp;导出</button>
	  </div>
	  <div class="pull-right" id="hos_table_nav_top">
  		<?php $this->get_table_nav_top_view( $data['list'] ); ?>
  	</div>
  </div>

  <div class="list_content">
		<div id="hos_table_content">
  		<?php $this->get_four_list_content_view( $data['list'] ); ?>
  	</div>
  </div>
  
  <div class="tablenav bottom" id="hos_table_nav_bottom">
  	<?php $this->get_table_nav_bottom_view( $data['list'] ); ?>
  </div>
</div>