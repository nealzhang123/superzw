<h1>控制面板</h1>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav nav-pills">
      <li role="presentation" <?php echo ( 'drop_box' == $data['action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=crm_setting&action=drop_box'); ?>">下拉框选项</a></li>
      <li role="presentation" <?php echo ( 'sort_box' == $data['action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url('admin.php?page=crm_setting&action=sort_box'); ?>">页面布局</a></li>
    </ul>
  </div><!-- /.container-fluid -->
</nav>