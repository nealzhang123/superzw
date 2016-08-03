<?php 
$current_month = date('m');
if( $current_month < 3 )
  $year_show = (int)(date('Y'));
else
  $year_show = (int)(date('Y')) + 1;

?>
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
		  &nbsp;&nbsp;
		  <input type="radio" name="extend_type" class="extend_type" value="1" checked />&nbsp;<?php echo $year_show;?>年待在园体检的儿童名单&nbsp;
		  <input type="radio" name="extend_type" class="extend_type" value="2" />&nbsp;<?php echo $year_show;?>年在园体检的待定儿童名单&nbsp;
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

  <div class="modal fade" id="modal_hos_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" aria-hidden="true">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"></h4>
        </div>
        <div class="modal-body">
          <form id="track_form">
          </form>
        </div>
        <div class="modal-footer">
          <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
          <button class="btn btn-small btn-success pull-right" id="four_track_edit_save">保存</button>
        </div>
      </div>
    </div>
  </div>
</div>