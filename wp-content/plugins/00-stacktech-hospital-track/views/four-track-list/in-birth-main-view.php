<?php 
$page = ( $data['hos_type'] == 1 ) ? 'four_track_manage' : 'pre_birth_manage'; 
?>
<div class="container-fluid btn-primary-group">
	<div class="row">
		<div class="col-xs-3 fadeInLeft" data-wow-duration="1s" data-wow-delay=".6s" >
			<a class="btn btn-primary button" href="<?php echo admin_url('admin.php?page=' . $page . '&hos_type=1&model=' . $this->model_ex); ?>" >
				<img class="hos_main_href" src="<?php echo esc_url( plugins_url( 'images/tubiao_03.png', plugin_dir_path( plugin_dir_path(__FILE__) ) ) ) ; ?>"></img>
				入园体检
			</a>
		</div>
		<div class="col-xs-3">
			<a class="btn btn-primary button" href="<?php echo admin_url('admin.php?page=' . $page . '&hos_type=1&model=y3'); ?>" >
				<img class="hos_main_href" src="<?php echo esc_url( plugins_url( 'images/tubiao_05.png', plugin_dir_path( plugin_dir_path(__FILE__) ) ) ) ; ?>"></img>
				3-4岁随访
			</a>
		</div>

		<div class="col-xs-3">
			<a class="btn btn-primary button" href="<?php echo admin_url('admin.php?page=' . $page . '&hos_type=1&model=y5'); ?>" >
				<img class="hos_main_href" src="<?php echo esc_url( plugins_url( 'images/tubiao_05.png', plugin_dir_path( plugin_dir_path(__FILE__) ) ) ) ; ?>"></img>
				5-6岁随访
			</a>
		</div>
	</div>
</div>