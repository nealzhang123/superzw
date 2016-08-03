<?php
switch ( $data->model) {
	case $this->model_y3:
		$pre = '_3y';
		# code...
		break;

	case $this->model_y5:
		$pre = '_5y';
		# code...
		break;
	
	default:
		# code...
		break;
}
//echo '<pre>';print_r($data);echo '</pre>';
?>
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
					case 'operate':
						$content = '<a class="in_track_item_edit" data-id="'.$item_id.'" data-no1="'.$item['no1'].'" data-no2="'.$item['no2'].'"><i class="fa fa-pencil-square-o"></i></a>';

						break;

					case 'status':
						$content = $data->get_track_status($item);
						
						break;

					case 'district':
						$content = $this->meu_arr[$item[$key]];
						
						break;

					case 'dedate':
						$content = $this->translate_date( $item[$key] );
						
						break;

					case 'pqudate':case 'wppsidate':case 'exdate':case 'enrolldate':
						$content = $this->translate_date( $item[$key.$pre] );
						
						break;

					case 'cage':
						$content = $data->column_cage( $item );
						
						break;
						
					case 'plasma':case 'bcell':case 'churine':case 'chfaeces':case 'phmeas':case 'pexam':case 'vision':case 'hearing':case 'oral':case 'blroutine':case 'bllead':case 'hemoglobin':case 'trelements':case 'chmeigg':case 'hbeag':case 'mvigg':case 'look':case 'stradip':case 'bmd':case 'paquestion':case 'tequestion':case 'si':case 'ptq':case 'psy':case 'chealthhandbook':case 'vacertifi':case 'cbcl':case 'abc':case 'phquestion':case 'trs':case 'asq':case 'wppsi':case 'btype':
						$content = $this->get_check_icon( $item[$key.$pre] );

						break;

					case 'notetel':
						$content = $this->note_tel_arr[$item[$key.$pre]];

						break;

					case 'notekd':
						$content = $this->note_local_arr[$item[$key.$pre]];

						break;

					case 'completertel':case 'completerkd':case 'kindergarten':case 'class':case 'completerwpp':case 'notewpp':
						$content = $item[$key.$pre];

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