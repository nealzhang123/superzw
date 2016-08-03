<div class="container-fluid">
	
  <div>
    <div class="pull-left" >
      <input name="health_manage_import" id="health_manage_import" style="display:none;" type="file" />
      <button class="btn btn-primary btn-sm health_manage_result_import"><i class="glyphicon glyphicon-upload"></i>&nbsp;导入</button>
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

<div class="modal fade" id="modal_hos_health_manage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" aria-hidden="true">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">孕三期收样情况和孕中期电话情况</h4>
        </div>
        <div class="modal-body">
          <form id="health_manage_viewer_form">
            
          </form>
        </div>
        <div class="modal-footer">
          <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
          <button class="btn btn-small btn-success pull-right" id="hos_health_manage_save">保存</button>
        </div>
      </div>
    </div>
  </div>
</div>