<div class="container-fluid">
	<div class="pre_birth_edit_title">
    <h4>
      随访状态录入 : 
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_track_result_edit_label" value="<?php echo $this->model_m1; ?>" <?php checked( $this->model_m1, $data['model'] ) ?> />1月随访
      </label>
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_track_result_edit_label" value="<?php echo $this->model_m6; ?>" <?php checked( $this->model_m6, $data['model'] ) ?> />6月随访
      </label>
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_track_result_edit_label" value="<?php echo $this->model_y1; ?>" <?php checked( $this->model_y1, $data['model'] ) ?> />1岁随访
      </label>
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_track_result_edit_label" value="<?php echo $this->model_y2; ?>" <?php checked( $this->model_y2, $data['model'] ) ?> />2岁随访
      </label>
    </h4>

    <h4>
      入口一队列号 : 
      <input type="text" id="edit_track_no1" value="" size="8" maxlength="8" />
      &nbsp;(输入关键词搜索,例如hpxxx)
    </h4>
  </div>

  <div class="pull-right" id="hos_table_nav_top">
    <?php $this->get_table_nav_top_view( $data['list'] ); ?>
  </div>

  <div class="list_content">
    <div id="hos_table_content">
    <?php $this->get_pre_birth_edit_table_view( $data['list'] ); ?>
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
          <h4 class="modal-title" id="myModalLabel">现场随访结果录入</h4>
        </div>
        <div class="modal-body">
          <form id="track_form">
          </form>
        </div>
        <div class="modal-footer">
          <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
          <button class="btn btn-small btn-success pull-right" id="track_result_save">保存</button>
        </div>
      </div>
    </div>
  </div>
</div>