<div class="container-fluid">
  <div>
  	<div class="pull-left">
      <button class="btn btn-primary btn-sm exec_export" data-href="<?php echo admin_url('admin.php?page=track_export_file'); ?>"><i class="glyphicon glyphicon-download"></i>&nbsp;导出</button>
  	</div>
    <div class="pull-right" id="hos_table_nav_top">
  	  <?php $this->get_table_nav_top_view( $data['list'] ); ?>
  	</div>
  </div>
	<div class="list_content">
		<div id="hos_table_content">
		<?php $data['list']->display(); ?>
		</div>
	</div>
	<div class="tablenav bottom tablenav-pages" id="hos_table_nav_bottom">
    <?php $this->get_table_nav_bottom_view( $data['list'] ); ?>
  </div>

  
</div>

<div class="modal fade" id="modal_hos_pregnant_middle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" aria-hidden="true">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">孕中期电话情况</h4>
        </div>
        <div class="modal-body">
          <form id="pregnant_middle_viewer_form">
            
          </form>
        </div>
        <div class="modal-footer">
          <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
          <button class="btn btn-small btn-success pull-right" id="hos_pregnant_middle_save">保存</button>
        </div>
      </div>
    </div>
  </div>
