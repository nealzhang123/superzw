<div class="container-fluid"> 
  <div id="category" class="row">
    <ul style="margin-bottom:5px;" class="nav nav-pills">
      <li style="padding-left:0px;padding-right:5px;" class="nav-header">
        <i class="fa fa-list-ul fa-3x"></i> 
      </li>
      <?php foreach ($data['categories'] as $key => $category) { ?>
        <li class="category_item<?php echo (0==$key)? ' active':''; ?>"><a><?php echo $category['cate_name'] ?></a></li>  
      <?php } ?>
      <li>
        <a style="padding:2px;"><i class="fa fa-plus-square fa-2x"></i></a>
      </li> 
      <li>
        <a style="padding:2px;"><i class="fa fa-pencil-square fa-2x"></i></a>
      </li>
      <li> 
        <a style="padding:2px;"><i class="fa fa-times-circle fa-2x"></i></a>
      </li>
    </ul>
  </div>
  <div>
    <form method="POST" ENCTYPE="multipart/form-data">
      <input type="hidden" value="<?php echo $this->model; ?>" name="model" id="model" />
      <div class="col-md-3">
        <p>
        1.选择分组，添加该分组所有客户至接收人Email。<br>
        2.如果没有你需要的分组，可自行创建新分组。<br>
        3.群发前，请先到<b>控制面板</b>-><b>相关设置</b>中设置SMTP服务器。<br>
        4.邮件为空的用户,不显示。<br>
        5.编辑邮件模板时候，可使用{name}代表收件人姓名，{email}代表收件人邮箱。<br />
        </p>
      </div>

      <div class="col-md-3">
        <p align="center">
          <b>&nbsp;收件人Email<br></b>
          <font color="#808080">每行一个邮件</font>
        </p>
        <p align="center">
          <textarea class="form-control" name="mail_account" rows="8" id="mail_account"><?php
          if( count( $data['mail_addresses'] ) > 0 ){
            foreach ($data['mail_addresses'] as $member) {
              if( !empty( $member['email'] ) )
                echo $member['email'] . '(' . $member['member_name'] .')' ."\n";
            }
          }
          ?></textarea>
        </p>
      </div>

      <div class="col-md-6">
        <p align="center">
          <b>&nbsp;邮件信息</font> <br></b>
        </p>
        <p>
          <b>发件人: &nbsp;
          <font color=red><?php echo $data['user_name'];?> &nbsp;( <?php echo $data['user_email'];?> )</font></b>
        </p> 
        <b>邮件主题:</b><input name="mail_topic" type="text" id="mail_topic" value="">
        <b>选择模板:</b>
        <select name="send_editor_view" id="send_editor_view">
          <option value="0">未选择模板</option>
          <?php foreach ($data['viewers'] as $viewer) { ?>
            <option value="<?php echo $viewer['viewer_id']; ?>"><?php echo $viewer['viewer_name']; ?></option>
          <?php } ?>
        </select>
        <a title="编辑模板" class="btn btn-small btn-primary edit_send_view" value="">编辑</a>
        <a title="新增模版" class="btn btn-small btn-primary add_send_view" value="" >新增</a>
        
        <p align="left">
          <b> 邮件内容:</b><br />
          <?php wp_editor( '', 'send_content' );?>
        </p>
        <button type="submit" id="send_save" class="btn btn-primary">群发</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modal_send_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" aria-hidden="true">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">编辑模板</h4>
      </div>
      <div class="modal-body">
        <div>
          编辑邮件模板时候，可使用{name}代表收件人姓名，{email}代表收件人邮箱
        </div>
        <div>
          <form id="viewer_form">
            <input type="hidden" name="viewer_id" id="viewer_id" value="0">
            <input type="hidden" name="model" value="<?php echo $this->model; ?>">
            <b>模板名称 : </b><input name="viewer_name" type="text" id="viewer_name" value=""><br />
            <?php wp_editor( '', 'viewer_content',array( 'editor_height'=>'400' ) );?>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
        <button class="btn btn-small btn-success pull-right" id="send_viewer_save">保存</button>
      </div>
    </div>
  </div>
</div>