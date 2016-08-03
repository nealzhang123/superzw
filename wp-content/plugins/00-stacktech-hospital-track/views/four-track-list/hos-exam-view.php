<?php 
$page = ( $data['hos_type'] == 1 ) ? 'four_track_manage' : 'pre_birth_manage'; 
?>
<div class="container-fluid">
	<input type="hidden" name="hos_type" id="hos_type" value="<?php echo $data['hos_type']; ?>" />	
	<input type="hidden" name="model" id="model" value="<?php echo $data['model']; ?>" />	
	<input type="hidden" name="hos_action" id="hos_action" value="<?php echo $data['hos_action']; ?>" />	
	<div>
		<h1><?php echo $data['title']; ?><div class="pull-right">
	    <a class="btn btn-primary" style="margin-top:2px;" href="<?php echo admin_url( 'admin.php?page=' . $page ); ?>"><i class="fa fa-arrow-left"></i> 返回 </a>
	  </div></h1>
	</div>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <ul class="nav nav-pills">
	      <li role="presentation" <?php echo ( $this->ex_stat == $data['hos_action'] ) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url( 'admin.php?page=' . $page . '&hos_type='.$data['hos_type'] . '&model='.$data['model'] . '&hos_action=' . $this->ex_stat ); ?>">入园人数统计</a></li>

	      <li role="presentation" <?php echo ( $this->ex_import == $data['hos_action'] ) ? 'class="active sydr"' : 'sydr'; ?>><a href="<?php echo admin_url( 'admin.php?page=' . $page . '&hos_type='.$data['hos_type'] . '&model='.$data['model'] . '&hos_action=' . $this->ex_import ); ?>">入园收样导入</a></li>

	      <li role="presentation" id="ex_search_div" <?php echo ( $this->ex_stat != $data['hos_action'] ) ? 'style="display:none;"' : ''; ?>><input type='text' name="ex_search_text" id="ex_search_text" maxlength="10" size="10"/>&nbsp;&nbsp;<button class="btn btn-primary btn-sm ex_search_button"><i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;查询</button></li>
	      
	      <li id="ex_export_div" <?php echo ( $this->ex_import != $data['hos_action'] ) ? 'style="display:none;"' : ''; ?>>
			<input name="ex_import" id="ex_import" style="display:none;" type="file" />
	      	<button class="btn btn-primary btn-sm ex_import_div" id="ex_import_submit"><i class="glyphicon glyphicon-upload"></i>&nbsp;导入</button>
	      </li>
	    </ul>
	  </div><!-- /.container-fluid -->
	</nav>
</div>