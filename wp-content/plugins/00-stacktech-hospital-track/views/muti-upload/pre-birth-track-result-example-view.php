<?php 
switch ( $data['model'] ) {
	case $this->model_m1:
		$title_arr = array(
			'产前队列no1编号', '1月随访时间','1月自填问卷', '1月抑郁量表', '1月体格检查', '1月黄疸指数', '1月母乳结果', '1月母乳样本', '1月尿液', '1月粪便', '1月母亲血压', '1月电话问卷', '1月电话随访备注'
			);
		$para_arr = array(
			'no1', 'm1_fpdate', 'm1_selfques', 'm1_epds', 'm1_phyexa', 'm1_icterus', 'm1_brmilks', 'm1_brmilkr', 'm1_urine', 'm1_fec', 'm1_bp', 'm1_telques', 'm1_telquesre'
			);
		$eg_arr = array(
			'HP13921', '2016-04-27','是', '是', '是', '是', '是', '是', '是', '是', '是', '是', ''
			);
		
		break;

	case $this->model_m6:
		$title_arr = array(
			'产前队列no1编号', '6月随访时间','6月自填问卷', '6月抑郁量表', '6月体格检查', '6月贝利', '6月母乳结果', '6月母乳样本', '6月尿液', '6月粪便', '6月血常规', '6月母亲血压', '6月电话问卷', '6月电话随访备注'
			);

		$para_arr = array(
			'no1', 'm6_fpdate', 'm6_selfques', 'm6_epds', 'm6_phyexa', 'm6_baily', 'm6_brmilks', 'm6_brmilkr', 'm6_urine', 'm6_fec', 'm6_rbt', 'm6_bp', 'm6_telques', 'm6_telquesre'
			);

		$eg_arr = array(
			'HP13921', '2016-04-27', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', ''
			);

		break;

	case $this->model_y1:
		$title_arr = array(
			'产前队列no1编号', '1岁随访时间', '宝宝姓名','1岁自填问卷', '1岁视力', '1岁体格检查', '1岁贝利', '1岁母乳结果', '1岁母乳样本', '1岁尿液', '1岁粪便', '1岁血常规', '1岁血铅', '1岁母亲血压', '1岁电话问卷', '1岁电话随访备注'
			);

		$para_arr = array(
			'no1', 'y1_fpdate', 'cname', 'y1_selfques', 'y1_vision', 'y1_phyexa', 'y1_baily', 'y1_brmilks', 'y1_brmilkr', 'y1_urine', 'y1_fec', 'y1_rbt', 'y1_bpb', 'y1_bp', 'y1_telques', 'y1_telquesre'
			);

		$eg_arr = array(
			'HP13921', '2016-04-27', '张三', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', ''
			);

		break;

	case $this->model_y2:
		$title_arr = array(
			'产前队列no1编号', '2岁随访时间','2岁自填问卷', '2岁视力', '2岁体格检查', '2岁贝利', '2岁尿液', '2岁粪便', '2岁血常规', '2岁血铅', '2岁母亲血压', '2岁电话问卷', '2岁电话随访备注'
			);

		$para_arr = array(
			'no1', 'y2_fpdate', 'y2_selfques', 'y2_vision', 'y2_phyexa', 'y2_baily', 'y2_urine', 'y2_fec', 'y2_rbt', 'y2_bpb', 'y2_bp', 'y2_telques', 'y2_telquesre'
			);

		$eg_arr = array(
			'HP13921', '2016-04-27', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', ''
			);

		break;
	
	default:
		# code...
		break;
}
?>
<div style="overflow:auto;">
<table class="table table-bordered hos_table1">
	<thead>
		<tr>
		<?php foreach ($title_arr as $title) { ?>
			<th><?php echo $title;?></th>
		<?php } ?>
		</tr>

		<tr>
		<?php foreach ($para_arr as $para) { ?>
			<th><?php echo $para;?></th>
		<?php } ?>
		</tr>
	</thead>
	<tbody>
		<tr>
		<?php foreach ($eg_arr as $eg) { ?>
			<td><?php echo $eg;?></td>
		<?php } ?>
		</tr>
	</tbody>
</table>