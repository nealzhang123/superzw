<div class="container-fluid btn-primary-group">
	<div class="row">
		<h2>出生队列</h2>
		<div class="col-xs-3 fadeInLeft">
			<a class="btn btn-primary button" href="<?php echo admin_url('admin.php?page=muti_import&hos_type=1&hos_action=basic_info'); ?>" >
				<img class="hos_main_href" src="<?php echo esc_url( plugins_url( 'images/tubiao_09.png', plugin_dir_path( plugin_dir_path(__FILE__) ) ) ) ; ?>"></img>
				分娩信息
			</a>
		</div>
	</div>

	<div class="row">
		<h2>产前队列</h2>
		<div class="col-xs-3 fadeInLeft">
			<a class="btn btn-primary button" href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=basic_info'); ?>" >
				<img class="hos_main_href" src="<?php echo esc_url( plugins_url( 'images/tubiao_05.png', plugin_dir_path( plugin_dir_path(__FILE__) ) ) ) ; ?>"></img>
				分娩信息
			</a>
		</div>

		<!-- <div class="col-xs-3">
			<a class="btn btn-primary button" href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=three_pregnant_info'); ?>" >
				<img class="hos_main_href" src="<?php echo esc_url( plugins_url( 'images/tubiao_05.png', plugin_dir_path( plugin_dir_path(__FILE__) ) ) ) ; ?>"></img>
				孕产前随访
			</a>
		</div> -->

		<div class="col-xs-3">
			<a class="btn btn-primary button" href="<?php echo admin_url('admin.php?page=muti_import&hos_type=2&hos_action=track_result'); ?>" >
				<img class="hos_main_href" src="<?php echo esc_url( plugins_url( 'images/tubiao_05.png', plugin_dir_path( plugin_dir_path(__FILE__) ) ) ) ; ?>"></img>
				0-2岁现场随访
			</a>
		</div>

		
		

	</div>
</div>