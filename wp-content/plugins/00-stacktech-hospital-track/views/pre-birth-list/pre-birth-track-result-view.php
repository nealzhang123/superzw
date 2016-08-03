<div class="container-fluid">
	<div>
		<div class="pull-left">
		  <input type="hidden" value="<?php echo $data['model']; ?>" name="model" id="model">
		  <input type="hidden" value="<?php echo $data['hos_action']; ?>" name="hos_action" id="hos_action">
		  月龄 : 大于  
		  <input type="text" name="min_mon_date" id="min_mon_date" maxlength="4" size="4" class="hos_input">
		  小于
		  <input type="text" name="max_mon_date" id="max_mon_date" maxlength="4" size="4" class="hos_input">
		  &nbsp;&nbsp;
		  随访时间 : 大于  
		  <input type="text" name="min_track_date" id="min_track_date" maxlength="10" size="10" class="datepicker hos_input">
		  小于
		  <input type="text" name="max_track_date" id="max_track_date" maxlength="10" size="10" class="datepicker hos_input">
		  &nbsp;&nbsp;
		  <button class="btn btn-primary btn-sm exec_search"><i class="glyphicon glyphicon-search"></i>&nbsp;筛选</button>
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

  <div class="status_content" id="hos_wp_status_content">
    <?php $this->get_hos_status_content_view(); ?>
  </div>

  <div class="tablenav bottom" id="hos_table_nav_bottom">
  	<?php $this->get_table_nav_bottom_view( $data['list'] ); ?>
  </div>
</div>