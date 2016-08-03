<?php //echo '<pre>';print_r($data);echo '</pre>'; ?>
<div class="container-fluid"> 
  <div class="row">
    <form method="post">
      <input type="hidden" value="<?php echo $this->model; ?>" name="model" id="model" />
      <input type="hidden" value="<?php echo $data['pid']; ?>" name="pid" id="pid" />
      <div class="pull-left" style="margin-bottom:5px;">
        <a class="btn btn-small btn-primary edit_return_button" style="margin-top:2px;" href="<?php echo $data['return_url']; ?>"><i class="fa fa-arrow-left"></i> 返回 </a>
      </div>
     	<div class="pull-right" style="margin-bottom:5px;">
        <button class="btn btn-small btn-success" style="margin-top:2px;" type="submit"><i class="glyphicon glyphicon-ok"></i> 保存 </button>
      </div>
      <div class="clearfix"></div>
      <?php foreach ($data as $m => $area) { 
				if( !is_int($m) )
					continue;
			?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a href="#accordion<?php echo $area['id']; ?>" data-toggle="collapse" class="accordion-toggle"><?php echo $area['area_value']; ?></a>
					</div>
					<div id="accordion<?php echo $area['id']; ?>" class="accordion-body collapse in">
					<?php 
					if( count($area['area_options']) > 0 ) { 
					?>
							<div class="accordion-inline">
								<table class="table table-bordered table-hover table-consdened">
									<tbody>
										<tr>
										<?php 
										foreach ($area['area_options'] as $key => $option) { 
											if( isset( $data['holiday'][$option['form_name']] ) && !empty( $data['holiday'][$option['form_name']] )  )
												$option['default_value'] = $data['holiday'][$option['form_name']];

											$form_name = $option['form_name'];
											if( $option['special_key'] == 'customer' ){
										?>
												<td>
													<div style="padding: 2px;line-height: 28px;height: 28px;vertical-align: middle;"><?php echo $option['title']; ?></div>
													<hr />
													<div style="padding: 2px;line-height: 28px;height: 28px;vertical-align: middle;">联系人</div>
												</td>
												<td>
													<select name="<?php echo $form_name; ?>" <?php echo ( $option['disabled'] ) ? 'disabled' : ''; ?> id="record_custom_change">
													<?php foreach ($option['option_values'] as $selects) { ?>
														<option value="<?php echo $selects['option_key']; ?>"<?php echo ( $selects['option_key'] == $option['default_value'] ) ? ' selected' : ''; ?>><?php echo $selects['option_value']; ?></option>
													<?php } ?>	
													</select>
													<hr />
													<div id="members_list">
													<?php echo $this->get_member_list( $data['record']['customer_id'], $data['record']['member_id'] ); ?>
													</div>
												</td>
										<?php		
											}else{
										?>
											<td><?php echo $option['title'] ?></td>
											<td>
											<?php 
											switch ( $option['form_type'] ) {
												case 'select':
											?>
													<select name="<?php echo $form_name; ?>" <?php echo ( $option['disabled'] ) ? 'disabled' : ''; ?>>
													<?php foreach ($option['option_values'] as $selects) { ?>
														<option value="<?php echo $selects['option_key']; ?>"<?php echo ( $selects['option_key'] == $option['default_value'] ) ? ' selected' : ''; ?>><?php echo $selects['option_value']; ?></option>
													<?php } ?>	
													</select>
											<?php
													break;

												case 'date':
											?>
													<input data-format="yyyy-MM-dd" type="text" name="<?php echo $form_name; ?>" class='datepicker' value="<?php echo date( 'Y-m-d',strtotime($option['default_value'] ) );?>">
											<?php
													break;

												case 'textarea':
											?>
													<textarea class="form-control" rows="3" name="<?php echo $form_name; ?>"><?php echo $option['default_value']; ?></textarea>
											<?php
													break;

												default:
											?>
													<input type="<?php echo $option['form_type'];?>" name="<?php echo $form_name; ?>" value="<?php echo $option['default_value'];?>" />
											<?php
													break;
											}
											?>
											</td>
										<?php } ?>
											<?php if( $key%2 != 0 && $key != count( $area['area_options'] ) ) { ?>
												</tr>
												<tr>
											<?php	}?>
										<?php	} //end of foreach ($area['area_options'] as $key => $option) ?>
										</tr>
									</tbody>
								</table>
							</div>
					<?php }//end of if( count($area['area_options']) > 0 )  ?>
					</div>
				</div>
			<?php }//end of foreach ($data as $area) ?>
			<div class="pull-left" style="margin-bottom:5px;">
        <a class="btn btn-small btn-primary edit_return_button" style="margin-top:2px;" href="<?php echo $data['return_url']; ?>"><i class="fa fa-arrow-left"></i> 返回 </a>
      </div>
			<div class="pull-right">
        <button class="btn btn-small btn-success" style="margin-top:2px;"><i class="glyphicon glyphicon-ok"></i> 保存 </button>
      </div>
    </form>
  </div>
</div>