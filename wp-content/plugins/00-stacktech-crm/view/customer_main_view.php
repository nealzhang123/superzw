<div class="container-fluid"> 
  <div class="row">
    <form method="post" style="margin-bottom:5px;" class="form-search pull-left">
      <input type="hidden" value="<?php echo $this->model; ?>" name="model" id="model">
      <input type="text" name="search_text" value="" class="input-large search-query">
      <button class="btn btn-small exec_search" type="submit"><i class="glyphicon glyphicon-search"></i>&nbsp;搜索</button>
      <button class="btn btn-small cancle_search" type="button"><i class="icon-remove-sign"></i>&nbsp;取消查找</button>
      <button class="btn btn-small exact_search" type="button"><i class="icon-share-alt"></i>&nbsp;高级搜索</button>
    </form>
  </div>

  <div id="category" class="row">
    <ul style="margin-bottom:5px;" class="nav nav-pills">
      <li style="padding-left:0px;padding-right:5px;" class="nav-header">
        <i class="fa fa-list-ul fa-3x"></i> 
      </li>
      <?php foreach ($data['categories'] as $key => $category) { ?>
        <li class="category_item<?php echo (0==$key)? ' active':''; ?>"><a><?php echo $category['cate_name'] ?></a></li>  
      <?php } ?>
      <li>
        <a style="padding:2px;" href="<?php echo admin_url('admin.php?page=create_cate&model=customer');?>"><i class="fa fa-plus-square fa-2x"></i></a>
      </li> 
      <li>
        <a style="padding:2px;"><i class="fa fa-pencil-square fa-2x"></i></a>
      </li>
      <li> 
        <a style="padding:2px;"><i class="fa fa-times-circle fa-2x"></i></a>
      </li>
    </ul>
  </div>

  <div id="tool_laber" style="margin-top:2px;padding-top:5px;margin-bottom:5px;border-top:2px solid #0088CC;">
    <div style="margin-bottom:5px;" class="pull-left">
      <a class="btn btn-small btn-primary" href="<?php echo admin_url('admin.php?page=crm_edit_model&model='.$this->model); ?>"><i class="glyphicon glyphicon-plus"></i> 新增</a>
      <button class="btn btn-small btn-danger"><i class="glyphicon glyphicon-trash"></i> 删除</button>
      <div class="btn-group">
        <a href="#" data-toggle="dropdown" class="btn btn-small btn-inverse dropdown-toggle"><i class="glyphicon glyphicon-edit"></i> 批量操作<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a>批量修改</a>
            </li>
          <li>
            <a>修改负责人</a>
          </li>
        </ul>
      </div>
      <button class="btn btn-small btn-success"><i class="glyphicon glyphicon-upload"></i> 导入</button>
      <button class="btn btn-small btn-success"><i class="glyphicon glyphicon-download"></i> 导出</button>
      <button class="btn btn-small btn-default send_select_mail"><i class="glyphicon glyphicon-envelope"></i> 发送邮件</button>
      <button class="btn btn-small btn-default"><i class="glyphicon glyphicon-comment"></i> 发送短信</button>
    </div>
  </div>
  <div id="list_results">
    <?php $this->get_list_content_by_category(); ?>
  </div>
</div>