<?php //echo '<pre>';print_r($data);echo '</pre>';exit(); ?>
<div class="container-fluid accordion" id="layout_accordion">
<input type="hidden" id="box" value="sortbox">
	<?php foreach ($data as $item) { 
		if( !is_array($item) )
			continue;
	?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#accordion<?php echo $item['id']; ?>" data-toggle="collapse" class="accordion-toggle"><?php echo $item['area_value']; ?></a>
		</div>
		<div id="accordion<?php echo $item['id']; ?>" class="accordion-body collapse in">
			<?php if( count($item['area_options']) > 0 ) { ?>
			<div class="accordion-inline">
				<table class="table table-bordered table-hover table-consdened">
					<tbody>
						<tr>
							<?php foreach ($item['area_options'] as $key => $option) { ?>
							<td align="left">
								<span id="option_sort<?php echo $option['option_id'];?>">#<?php echo $option['sort']; ?></span>
								<?php if( $option['is_required'] ){ ?>
									<span id="option_is_required<?php echo $option['option_id'];?>">&nbsp;<font color="red">*</font></span>
								<?php }else{ ?>
									<span id="option_is_required<?php echo $option['option_id'];?>">&nbsp;</span>
								<?php } ?>							
								<span id="option_title<?php echo $option['option_id'];?>"><?php echo $option['title']; ?></span>
								<?php if( $option['is_hidden'] ){ ?>
									<span id="option_is_hidden<?php echo $option['option_id'];?>">&nbsp;(隐藏)</span>
								<?php }else{ ?>
									<span id="option_is_hidden<?php echo $option['option_id'];?>"></span>
								<?php } ?>
								&nbsp;<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal<?php echo $option['option_id'];?>">
								  编辑
								</button>
								<div class="modal fade" id="modal<?php echo $option['option_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $option['option_id'];?>">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel<?php echo $option['option_id'];?>">编辑页面布局 - <?php echo $option['title']; ?></h4>
								      </div>
								      <div class="modal-body">
							        	<form id="form_option_save<?php echo $option['option_id'];?>">
							        		<input type="hidden" name="option_id" value="<?php echo $option['option_id'];?>">
							        		<input type="hidden" name="model" value="<?php echo $data['model'];?>">
							        		<table class="table-consdened">
		                        <tbody>
			                        <tr>
			                            <td><strong>是否必填: </strong></td>
			                            <td><input type="checkbox" <?php checked( $option['is_required'], 1 ); ?> value="1" name="is_required"></td>
			                        </tr>
			                        <tr>
			                            <td><strong>字段标签: </strong></td>
			                            <td><input type="text" value="<?php echo $option['title']; ?>" name="title" size="20"></td>
			                        </tr>
			                        <tr>
			                            <td><strong>显示顺序: </strong></td>
			                            <td><input type="text" value="<?php echo $option['sort']; ?>" name="sort" size="20"></td>
			                        </tr>
			                        <tr>
			                            <td><strong>是否隐藏: </strong></td>
			                            <td><input type="checkbox" <?php checked( $option['is_hidden'], 1 ); ?> value="1" name="is_hidden"></td>
			                        </tr>
		                     		</tbody>
		                     	</table>
						        		</form>
								      </div>
								      <div class="modal-footer">
		                    <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
		                    <button type="submit" class="btn btn-small btn-success pull-right option_save_button" option-id="<?php echo $option['option_id'];?>">保存</button>
		                	</div>
								    </div>
								  </div>
								</div>
								<?php if( $key%2 != 0 && $key != count( $item['area_options'] ) ) { ?>
								</tr>
								<tr>
								<?php	}?>
							</td>
							<?php } ?>					        
						</tr>
					</tbody>
				</table>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php }?>
</div>