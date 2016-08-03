<div class="accordion-inline">
	<input type="hidden" name="member_id[]" value="" />
	<button class="btn btn-small btn-danger delete_member" style="margin-top:2px;" type="submit" member-id="">删除</button>
	<table class="table table-bordered table-hover table-consdened">
		<tbody>
			<tr>
			<?php 
				foreach ($data as $key => $option) { 
					$option['form_name'] = $option['form_name']."_mem[]";
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
				<?php if( $key%2 != 0 && $key != count( $area['area_options'] ) ) { ?>
					</tr>
					<tr>
				<?php	}?>
			<?php	} //end of foreach ($area['area_options'] as $key => $option) ?>
			</tr>
		</tbody>
	</table>
	<div role="separator" class="divider"></div>
</div>