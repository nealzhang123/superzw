<div class="container-fluid">
  <div class="container-fluid">
    <div class="pull-left">
      <input type="hidden" value="<?php echo $data['model']; ?>" name="model" id="model">
      <input type="hidden" value="<?php echo $data['hos_action']; ?>" name="hos_action" id="hos_action">
      <input type="hidden" value="<?php echo $data['hos_type']; ?>" name="hos_type" id="hos_type">
      <button class="btn btn-primary btn-sm four_track_export" data-href="<?php echo admin_url('admin.php?page=four_track_export'); ?>"><i class="glyphicon glyphicon-download"></i>&nbsp;导出</button>
    </div>
    <div class="pull-right" id="hos_table_nav_top">
      <?php $this->get_table_nav_top_view( $data['list'] ); ?>
    </div>
  </div>

  <div class="tablenav top">
    <div class="alignleft actions bulkactions">
    </div>
    <br class="clear">
  </div>
  <div class="list_content">
    <div id="hos_table_content" style="overflow:auto;">
    <?php $this->get_four_track_table_view( $data['list'] ); ?>
    </div>
  </div>

  <div class="status_content" id="hos_status_content">
    <?php $this->get_hos_status_content_view(); ?>
  </div>
  
  <div class="tablenav bottom" id="hos_table_nav_bottom">
    <?php $this->get_table_nav_bottom_view( $data['list'] ); ?>
  </div>

  <div class="modal fade" id="modal_hos_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-target=".bs-example-modal-lg">
    <div class="modal-dialog modal-lg" role="document" aria-hidden="true">
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