<div class="container">
  <h1><?php echo $data['head']; ?><div class="pull-right">
      <a class="btn btn-primary" style="margin-top:2px;" href="<?php echo admin_url( 'admin.php?page=muti_import' ); ?>"><i class="fa fa-arrow-left"></i> 返回 </a>
    </div></h1>
  <?php 
  switch ( $data['hos_type'] ) {
    case 1://出生队列
      switch ( $data['hos_action'] ) {
        default:
          # code...
          break;
      }

      break;

    case 2://产前队列
      switch ( $data['hos_action'] ) {
        case 'track_result':
  ?>
  <nav class="navbar">  
    <ul class="nav nav-pills">       
      <li role="presentation" <?php echo ( $this->model_m1 == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=track_result&model='.$this->model_m1);?>">1月现场随访</a></li>
      <li role="presentation" <?php echo ( $this->model_m6 == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=track_result&model='.$this->model_m6);?>">6月现场随访</a></li>
      <li role="presentation" <?php echo ( $this->model_y1 == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=track_result&model='.$this->model_y1);?>">1岁现场随访</a></li>
      <li role="presentation" <?php echo ( $this->model_y2 == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=track_result&model='.$this->model_y2);?>">2岁现场随访</a></li>
    </ul>
  </nav>
  <?php
          break;
            case 'three_pregnant_info':
          ?>
              <nav class="navbar">  
                <ul class="nav nav-pills">       
                  <li role="presentation" <?php echo ( $this->model_pregnant_three == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=three_pregnant_info&model='.$this->model_pregnant_three);?>">孕三期数据</a></li>
                  <li role="presentation" <?php echo ( $this->model_pregnant_middle == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=three_pregnant_info&model='.$this->model_pregnant_middle);?>">孕中期数据</a></li>
                  <li role="presentation" <?php echo ( $this->model_childbirth_status == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=three_pregnant_info&model='.$this->model_childbirth_status);?>">分娩状态数据</a></li>
                  <li role="presentation" <?php echo ( $this->model_pregnant_b == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=three_pregnant_info&model='.$this->model_pregnant_b);?>">孕期超声检查数据</a></li>
                   <li role="presentation" <?php echo ( $this->model_health_manage == $data['model'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=three_pregnant_info&model='.$this->model_health_manage);?>">健康管理数据</a></li>
                </ul>
              </nav>
          <?php
            break;
        
        default:
          # code...
          break;
      }

      break;
    
    default:
      break;
  }
  ?>
  <br>
  <blockquote class="muti_upload">
    <p>在上传文件前,请按照对应的例表排列成相对应的格式,支持xlsx,xls,csv文件格式的上传.<br>
    可以通过点击上传,完成多个文件的上传,或者将需要上传的文件拖拽到现所在区域.<br>
    </p>
  </blockquote>
  <br>
  <!-- The file upload form used as target for the file upload widget -->
  <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
    <div class="row fileupload-buttonbar">
      <div class="col-lg-7">
        <!-- The fileinput-button span is used to style the file input field as button -->
        <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>上传文件...</span>
            <input type="file" name="files[]" multiple>
        </span>
        <button type="reset" class="btn btn-warning cancel">
            <i class="glyphicon glyphicon-ban-circle"></i>
            <span>删除记录</span>
        </button>
        <!-- The global file processing state -->
        <span class="fileupload-process"></span>
      </div>
      <div class="col-lg-5 fileupload-progress fade">
        <!-- The global progress bar -->
        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
        </div>
        <!-- The extended global progress state -->
        <div class="progress-extended">&nbsp;</div>
      </div>
    </div>
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    <input type="hidden" name="ajax_action" id="ajax_action" value="<?php echo $data['ajax_action']; ?>" />
    <input type="hidden" id="hos_type" value="<?php echo $data['hos_type']; ?>">
    <input type="hidden" id="hos_action" value="<?php echo $data['hos_action']; ?>">
    <input type="hidden" id="model" value="<?php echo $data['model']; ?>">
  </form>
  <br>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">文件内容格式示例,第二行参数请保持一致</h3>
    </div>
    <div class="panel-body">
      <?php $this->load_view( 'muti-upload', $data['view'], $data ) ?>
    </div>
  </div>
</div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-upload fade">
    <td>
      <p class="name">{%=file.name%}</p>
      <strong class="error text-danger"></strong>
    </td>
    <td>
      <p class="size">上传中...</p>
      <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
    </td>
    <td>
      {% if (!i && !o.options.autoUpload) { %}
        <button class="btn btn-primary start" disabled>
          <i class="glyphicon glyphicon-upload"></i>
          <span>开始</span>
        </button>
      {% } %}
      {% if (!i) { %}
        <button class="btn btn-warning cancel" style="display:none;">
          <i class="glyphicon glyphicon-ban-circle"></i>
          <span>取消</span>
        </button>
      {% } %}
    </td>
  </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-download fade">
    <td>
      <p class="name">
        {% if (file.url) { %}
          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
        {% } else { %}
          <span>{%=file.name%}</span>
        {% } %}
      </p>
      {% if (file.error) { %}
        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
      {% } %}
    </td>
    <td>
      <span class="size">{%=o.formatFileSize(file.size)%}</span>
    </td>
    <td>
      <div>
      {% if (!file.error) { %}
        <span class="text-success">完成上传</span>
      {% }else{ %}
        <span class="text-danger">上传失败</span>
      {% } %}
      <button class="btn btn-warning cancel" style="display:none;">
        <i class="glyphicon glyphicon-ban-circle"></i>
        <span>取消</span>
      </button>
      </div>
    </td>
  </tr>
{% } %}
</script>