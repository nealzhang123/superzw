<div class="container-fluid">
	<div class="pre_birth_edit_title">
    <h4>
      电话随访录入 : 
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_pre_birth_edit_label" value="<?php echo $this->model_m1; ?>" <?php checked( $this->model_m1, $data['model'] ) ?> />1月
      </label>
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_pre_birth_edit_label" value="<?php echo $this->model_m6; ?>" <?php checked( $this->model_m6, $data['model'] ) ?> />6月
      </label>
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_pre_birth_edit_label" value="<?php echo $this->model_y1; ?>" <?php checked( $this->model_y1, $data['model'] ) ?> />1岁
      </label>
      <label class="radio-inline">
        <input type="radio" name="model" class="hos_pre_birth_edit_label" value="<?php echo $this->model_y2; ?>" <?php checked( $this->model_y2, $data['model'] ) ?> />2岁
      </label>
    </h4>
  </div>
	
  <div id="table_list">
    <?php $this->get_pre_birth_edit_select_tables( $data['model'], $data['hos_action'] ); ?>
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
          <h4 class="modal-title" id="myModalLabel">电话随访录入</h4>
        </div>
        <div class="modal-body">
          <form id="tel_list_form">
            <input type="hidden" name="hos_edit_id" id="hos_edit_id" />
            <table class="table table-bordered table-hover table-consdened">
              <tr>
                <td><b>队列编号 : </b></td>
                <td><span name="hos_edit_no1" id="hos_edit_no1"></span></td>
              </tr>

              <tr>
                <td><b>母亲姓名 : </b></td>
                <td><span name="hos_edit_name" id="hos_edit_name"></span></td>
              </tr>

              <tr>
                <td><b>母亲手机 : </b></td>
                <td><input name="hos_edit_pphone" type="text" id="hos_edit_pphone" value="" /></td>
              </tr>

              <tr>
                <td><b>父亲手机 : </b></td>
                <td><input name="hos_edit_hphone" type="text" id="hos_edit_hphone" value="" /></td>
              </tr>

              <tr>
                <td><b>电话问卷 : </b></td>
                <td>
                  <label class="radio-inline">
                    <input type="radio" name="hos_edit_telques" id="hos_edit_telques" value="1" />是
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="hos_edit_telques" id="hos_edit_telques" value="0" />否
                  </label>
                </td>
              </tr>

              <tr id="hos_edit_telquesre_html">
                <td><b>电话随访备注 : </b></td>
                <td>
                  <label class="radio-inline">
                    <input type="radio" name="hos_edit_telquesre" id="hos_edit_telquesre" value="3" />失联
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="hos_edit_telquesre" id="hos_edit_telquesre" value="4" />拒绝
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="hos_edit_telquesre" id="hos_edit_telquesre" value="5" />宝宝夭折
                  </label>
                </td>
              </tr>
            </table>
          </form>
        </div>
        <div class="modal-footer">
          <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
          <button class="btn btn-small btn-success pull-right" id="tel_list_save">保存</button>
        </div>
      </div>
    </div>
  </div>
</div>