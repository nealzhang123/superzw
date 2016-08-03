<div class="container-fluid">
	<div>
		<h1><?php echo $data['title']; ?><div class="pull-right">
	    <a class="btn btn-primary" style="margin-top:2px;" href="<?php echo admin_url( 'admin.php?page=pre_birth_manage' ); ?>"><i class="fa fa-arrow-left"></i> 返回 </a>
	  </div></h1>
		
	</div>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <ul class="nav nav-pills">
	      <li role="presentation" <?php echo ( $this->tel_book == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=list&model='.$data['model'] . '&hos_action=' . $this->tel_book ); ?>"><?php echo $data['pre_title']; ?>随访预约</a></li>

	      <li role="presentation" <?php echo ( $this->track_result == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=list&model='.$data['model'] . '&hos_action=' . $this->track_result ); ?>"><?php echo $data['pre_title']; ?>随访结果</a></li>

	      <li role="presentation" <?php echo ( $this->track_list == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=list&model='.$data['model'] . '&hos_action=' . $this->track_list ); ?>"><?php echo $data['pre_title']; ?>现场随访名单</a></li>
	      
	      <li role="presentation" <?php echo ( $this->tel_list == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=list&model='.$data['model'] . '&hos_action=' . $this->tel_list ); ?>"><?php echo $data['pre_title']; ?>电话随访名单</a></li>
	    </ul>
	  </div><!-- /.container-fluid -->
	</nav>
</div>