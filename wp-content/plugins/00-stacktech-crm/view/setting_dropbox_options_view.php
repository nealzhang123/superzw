<?php //echo '<pre>';print_r($data);echo '</pre>'; ?>
<div class="container-fluid">
<input type="hidden" id="box" value="dropbox">
	<div class="row">
		<?php foreach ($data as $item) { 
			if( !is_array($item) )
				continue;
		?>
		<div class="col-xs-3">
			<div>
				<?php echo $item['title']; ?>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?php echo $item['option_id'];?>">
				  编辑
				</button>
				<div class="modal fade" id="modal<?php echo $item['option_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $item['option_id'];?>">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel<?php echo $item['option_id'];?>">编辑下拉框选项 - <?php echo $item['title']; ?></h4>
				      </div>
				      <div class="modal-body">
				        <div>
				        	每行输入一个选项，然后点击“保存”按钮进行保存。
				        </div>
				        <div>
				        	<form id="form_option_save<?php echo $item['option_id'];?>">
				        		<input type="hidden" name="option_id" value="<?php echo $item['option_id'];?>">
				        		<input type="hidden" name="model" value="<?php echo $data['model'];?>">
					        	<textarea rows="10" name="option_value"><?php foreach ($item['options'] as $option) {
				        			echo $option['option_value'] . "\n";
				        		}?></textarea>
			        		</form>
				        </div>
				      </div>
				      <div class="modal-footer">
	                    <button aria-hidden="true" data-dismiss="modal" class="btn pull-left btn-small btn-primary">取消</button>
	                    <button type="submit" class="btn btn-small btn-success pull-right option_save_button" option-id="<?php echo $item['option_id'];?>">保存</button>
	                  </div>
				    </div>
				  </div>
				</div>
			</div>
			<div id="option_list<?php echo $item['option_id'];?>" class="option_list">
				<?php foreach ($item['options'] as $option) { ?>
				<div class="option_item">
					<?php echo $option['option_value']; ?>
				</div>
				<?php }?>
			</div>
		</div>
		<?php }//end of foreach ($data as $item) ?>
	</div>
</div>