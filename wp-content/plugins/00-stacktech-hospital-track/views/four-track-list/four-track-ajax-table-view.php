<table class="table table-bordered">
	<thead>
		<tr>
			<?php foreach ($data[1] as $title) { ?>
				<th><?php echo $title; ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data as $key => $item) { 
			if( 1 == $key )
				continue;
		?>
			<tr>
				<?php foreach ( $item as $val ) { ?>
					<td><?php echo $val; ?></td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>