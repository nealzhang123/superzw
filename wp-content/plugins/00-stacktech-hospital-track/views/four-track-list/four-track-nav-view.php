<?php 
$page = ( $data['hos_type'] == 1 ) ? 'four_track_manage' : 'pre_birth_manage'; 
?>
<div class="container-fluid">
	<div>
		<h1><?php echo $data['title']; ?><div class="pull-right">
	    <a class="btn btn-primary" style="margin-top:2px;" href="<?php echo admin_url( 'admin.php?page=' . $page ); ?>"><i class="fa fa-arrow-left"></i> 返回 </a>
	  </div></h1>
		
	</div>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <ul class="nav nav-pills">
	      <li role="presentation" <?php echo ( $this->track_result == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=' . $page . '&hos_type='.$data['hos_type'] . '&model='.$data['model'] . '&hos_action=' . $this->track_result ); ?>">在园随访状态</a></li>

	      <li role="presentation" <?php echo ( $this->track_import == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=' . $page . '&hos_type='.$data['hos_type'] . '&model='.$data['model'] . '&hos_action=' . $this->track_import ); ?>">随访结果录入</a></li>

	      <li role="presentation" <?php echo ( $this->track_list == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=' . $page . '&hos_type='.$data['hos_type'] . '&model='.$data['model'] . '&hos_action=' . $this->track_list ); ?>">现场随访名单</a></li>
	      
	      <li role="presentation" <?php echo ( $this->tel_list == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=' . $page . '&hos_type='.$data['hos_type'] . '&model='.$data['model'] . '&hos_action=' . $this->tel_list ); ?>">电话随访名单</a></li>
	    </ul>
	  </div><!-- /.container-fluid -->
	</nav>
</div>