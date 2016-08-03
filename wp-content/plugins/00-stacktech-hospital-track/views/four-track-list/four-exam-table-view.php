<?php
//echo '<pre>';print_r($data);echo '</pre>';exit();
?>
<div id="hos_four_exam_content" style="overflow:auto;">
<table class="table table-bordered hos_table1">
	<thead>
		<tr>
		<?php foreach ($data->column_header_define as $key => $title) { ?>
			<th><?php echo $title; ?></th>
		<?php } ?>
		</tr>
	</thead>

	<tbody>
		<!-- <tr>
		<?php foreach ($data->column_header_define as $key => $title) { ?>
			<th><?php echo $key; ?></th>
		<?php } ?>
		</tr> -->
		<?php foreach ($data->items as $item) { 
			$item_id = $item['id'];
		?>
			<tr>
			<?php foreach ($data->column_header_define as $key => $title) { 
				switch ( $key ) {
					case 'hos_exam_status':
						$content = $data->column_hos_exam_status($item);
						
						break;

					case 'dedate':case 'datet_ry':
						$content = $this->translate_date( $item[$key] );
						
						break;

					case 'bloodqu_ry':
						$content = $data->column_bloodqu_ry($item);
						
						break;

					case 'meu_ry':
						$content = $data->column_meu_ry($item);
						
						break;

					case 'cage_ry':
						$content = $data->column_cage_ry($item);
						
						break;

					case 'brtr_ry':case 'altr_ry':
						$content = $this->get_check_icon( $item[$key.$pre] );

						break;

					default:
						$content = $item[$key];

						break;
				}
			?>
				<td id="<?php echo $key.$item_id ?>"><?php echo $content; ?></td>
			<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>
</div>