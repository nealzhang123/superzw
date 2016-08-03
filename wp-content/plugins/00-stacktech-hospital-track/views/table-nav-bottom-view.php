<div class="tablenav buttom tablenav-pages">
	<!-- <div class="tablenav-pages">
			<button class="btn btn-primary btn-sm" id="first_page">首页</button>
			<button class="btn btn-primary btn-sm" id="prev_page">上一页</button>
			<span class="paging-input">共<?php echo $data['total_page']; ?>页</span>
			<span class="displaying-num"><?php echo $data['total_count']; ?>条记录</span>
			<button class="btn btn-primary btn-sm" id="next_page">下一页</button>
			<button class="btn btn-primary btn-sm" id="last_page">尾页</button>
			<span class="paging-input">第<input class="current-page" id="current_page" type="text" name="paged" value="<?php echo $data['current_page']; ?>" size="1" aria-describedby="table-paging">页</span>
			<button class="btn btn-primary btn-sm" id="search_list">go</button>
	</div> -->
		<span class="displaying-colume-count">
		每页显示: 
		<select id="page_per_num">
			<option value="0" <?php selected( $data['page_per_num'], 0 );?>>10</option>
			<option value="1" <?php selected( $data['page_per_num'], 1 );?>>20</option>
			<option value="2" <?php selected( $data['page_per_num'], 2 );?>>50</option>
			<option value="3" <?php selected( $data['page_per_num'], 3 );?>>100</option>
		</select>
		个&nbsp;&nbsp;
	</span>
		<input type="hidden" id="group" value="<?php echo $_REQUEST['group']; ?>" />
		<input type="hidden" id="total_page" value="<?php echo $data['total_page']; ?>" />
		<button class="btn<?php echo ( $data['current_page'] == 1 ) ? '' : ' btn-primary'; ?> btn-sm" id="first_page">首页</button>
		<button class="btn<?php echo ( $data['current_page'] == 1 ) ? '' : ' btn-primary'; ?> btn-sm" id="prev_page">上一页</button>
		<span class="paging-input">共<?php echo $data['total_page']; ?>页</span>
		<span class="displaying-num"><?php echo $data['total_count']; ?>条记录</span>
		<button class="btn<?php echo ( $data['current_page'] == $data['total_page'] ) ? '' : ' btn-primary'; ?> btn-sm" id="next_page">下一页</button>
		<button class="btn<?php echo ( $data['current_page'] == $data['total_page'] ) ? '' : ' btn-primary'; ?> btn-sm" id="last_page">尾页</button>
		<span class="paging-input">第<input class="current-page" id="current_page" type="text" name="paged" value="<?php echo $data['current_page']; ?>" size="1" aria-describedby="table-paging">页</span>
		<button class="btn btn-primary btn-sm" id="search_list">go</button>
</div>