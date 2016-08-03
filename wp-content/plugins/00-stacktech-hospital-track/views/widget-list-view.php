<?php //error_log('test');echo '<pre>';print_r($data);echo '</pre>'; ?>
<div>
	<table style="width:90%;">
		<thead>
			<tr>
				<th><?php echo $data['tel_title']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
		if( count( $data['tel_results'] ) > 0 ) {
			$i = 0;
			foreach ( $data['tel_results'] as $tel ) {
				$hid_class = '';
				if( $i >= $data['max_count1'] ){
					$hid_class = 'hos_widget_hid';
				}
				switch (variable) {
					case 'value':
						# code...
						break;
					
					default:
						# code...
						break;
				}
				http://stacktech.com/wp-admin/admin.php?page=pre_birth_manage&model=y1&hos_action=track_list

				//$url = admin_url('admin.php?page=tel_list&region='.$region.'&form_id='.$this->ent1_form_id.'&table_no='.$table_no);
			?>
				<tr class="<?php echo $hid_class; ?>">
					<td><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=list&model=' . $data['model'] . '&hos_action=track_list&tab_no=' . $tel['tab_no'] ); ?>" style="float:left;valign:middle;"><?php echo $tel['tab_name']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="table_ignore button" tab_id="<?php echo $tel['tab_id']; ?>" style="float:right;" region="<?php echo $data['region']; ?>" data-type="tel">不再提醒</a><br />
					</td>
				</tr>
			<?php
				$i++;
			}
		}else{
		?>
			<tr><td>没有任何相关表</td></tr>
		<?php } ?>
		</tbody>
	</table>
	<hr>

	<table style="width:90%;">
		<thead>
			<tr>
				<th><?php echo $data['track_title']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
		if( count( $data['track_results'] ) > 0 ) {
			$i = 0;
			foreach ( $data['track_results'] as $track ) {
				$hid_class = '';
				if( $i >= $data['max_count2'] ){
					$hid_class = 'hos_widget_hid';
				}
				//$url = admin_url('admin.php?page=tel_list&region='.$region.'&form_id='.$this->ent1_form_id.'&table_no='.$table_no);
			?>
				<tr class="<?php echo $hid_class; ?>">
					<td><a href="<?php echo admin_url( 'admin.php?page=pre_birth_manage&group=list&model=' . $data['model'] . '&hos_action=tel_list&tab_no=' . $track['tab_no'] ); ?>" style="float:left;valign:middle;"><?php echo $track['tab_name']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="table_ignore button" tab_id="<?php echo $track['tab_id']; ?>" style="float:right;" region="<?php echo $data['region']; ?>" data-type="track">不再提醒</a><br />
					</td>
				</tr>
			<?php
				$i++;
			}
		}else{
		?>
			<tr><td>没有任何相关表</td></tr>
		<?php } ?>
		</tbody>
	</table>
</div>