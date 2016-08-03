<div class="container-fluid">
	<input type="hidden" name="hos_action" id="hos_action" value="<?php echo $data['hos_action']; ?>">
	<input type="hidden" name="model" id="model" value="<?php echo $data['model']; ?>">
	<div>
		<h1><?php echo $data['title']; ?><div class="pull-right">
	    <a class="btn btn-primary" style="margin-top:2px;" href="<?php echo admin_url( 'admin.php?page=pre_birth_manage' ); ?>"><i class="fa fa-arrow-left"></i> 返回 </a>
	  </div>
	</div>

	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <ul class="nav nav-pills">
	      <li role="presentation" <?php echo ( $this->tel_book == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=edit&hos_action=' . $this->tel_book ); ?>">现场随访预约录入</a></li>

	      <li role="presentation" <?php echo ( $this->track_result == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=edit&hos_action=' . $this->track_result ); ?>">现场随访结果录入</a></li>
	      
	      <li role="presentation" <?php echo ( $this->tel_list == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=edit&hos_action=' . $this->tel_list ); ?>">电话随访录入</a></li>
	    </ul>
	  </div><!-- /.container-fluid -->
	</nav>
</div>