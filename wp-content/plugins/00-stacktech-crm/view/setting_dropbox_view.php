<div class="container-fluid">
  <div class="row">
  	<div class="col-md-1">当前模块</div>
  	<div class="col-md-1">
		<select id="setting_model" box="dropbox">
		  <option value="<?php echo $this->model_cus; ?>">客户</option>
		  <option value="<?php echo $this->model_rec; ?>">联系记录</option>
		  <option value="<?php echo $this->model_hol; ?>">纪念日</option>
		  <option value="<?php echo $this->model_mem; ?>">联系人</option>
		</select>
  	</div>
  </div>
</div>

<div id="setting_dropbox">
	<?php $this->get_form_dropbox_options( $this->model_cus );?>
</div>