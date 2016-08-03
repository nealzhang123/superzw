<?php 
	switch( $data['model']){
		case $this->model_pregnant_three:
			$title_arr = array('队列编号1', '母亲姓名', '末次月经', '母亲电话', '父亲电话', '早期血清', '早期血浆血细胞', '早期尿', '父亲血', '中期血清', '中期尿', '晚期尿-Ent1');
			$part_title_arr = array('no1', 'name', 'lmp', 'tel1', 'tel2', 'serumt1', 'plasma_bcellt1', 'urinet1', 'pablood',  'serumt2', 'urinet2', 'urinet3_ent1');
			$message_arr = array('HP10002', '张丽姿', '2013/7/11', '11位数', '11位数', 1, 1, 1, 1, 0, 1, 0);

			break;

		case $this->model_pregnant_middle:
			$title_arr = array('队列编号1', '母亲姓名', '孕中期母亲电话', '孕中期父亲电话', '孕中期电话', '孕中期电话备注', '孕中期电话人');
			$part_title_arr = array('no1', 'name', 'tel1t2', 'tel2t2', 'fut2', 'fut2rem', 'fut2er');
			$message_arr = array('HP10002', '张丽姿', '11位数', '11位数', '完成', '同意', '胡杏');

			break;

		case $this->model_childbirth_status:
			$title_arr = array('队列编号1', '母亲姓名', '末次月经', '分娩日期', '身份证号', '分娩时母亲电话', '分娩时父亲电话', '备用电话', '队列编号2', '晚期血清', '晚期血浆血细胞', '脐血清', '脐血浆血细胞', '胎盘', '脐带', '胎粪', '晚期尿-Ent2');
			$part_title_arr = array('no1', 'name', 'lmp', 'dedate', 'm_id', 'pphone', 'hphone', 'dephone', 'no2',  'serumt3', 'plasma_bcellt3', 'cbser', 'cbpla_bcl', 'placent', 'ubcord', 'nmtube', 'urinet3_ent2');
			$message_arr = array('HP10002', '张丽姿', '2013/7/11', '2014/3/28', '18位数', '11位数', '11位数', '11位数', 'HP83082', 1, 1, 1, 1, 1, 0, 0, 1);
			break;

		case $this->model_pregnant_b:
			$title_arr = array('入口一队列号', '入口二队列号', '末次月经日期', '超声检查日期', '头臀长');
			$part_title_arr = array('no2', 'no1', 'lmp', 'ult_checkdate', 'crl');
			$message_arr = array(
				array('HP74001', 'HP10001', '2014/1/1', '2014/4/1', '6.5'),
				array('HP74001', 'HP10001', '2014/1/1', '2014/5/1', ''),
				array('HP74001', 'HP10001', '2014/1/1', '2014/8/1', ''),
				array('HP74005', 'HP10002', '2014/1/5', '2014/6/6', ''),
				array('HP74005', 'HP10002', '2014/1/5', '2014/7/1', ''),
			);

			break;

		case $this->model_health_manage:
			$title_arr = array('队列编号1', '母亲姓名', '出生体重', '出生体重百分位数(90%)', '出生体重百分位数(10%)', '低出生体重', '巨大儿', '早产', '小于胎龄儿', '大于胎龄儿', '新生儿畸形', '妊娠期高血压', '妊娠期糖尿病');
			$part_title_arr = array('no1', 'name', 'var59', 'bw_p90gs', 'bw_p10gs', 'lbw', 'macro', 'preterm',  'sga', 'lga', 'neobd_001', 'matpih', 'matgdm');
			$message_arr = array('HP10002', '张丽姿', '3140', '3385.123041', '2605.832289',  0, 0, 1, 1, 1, 0, 0, 0);
			break;

		default :
			#code ......
			break; 
	}
?>
<div style="overflow:auto;">
	<table class="table table-bordered hos_table1" >
		<tr>
			<?php 
				foreach( $title_arr as $title ){
					?>
					<th><?php echo $title;?></th>
					<?php
				}
			?>
		</tr>
		<tr>
			<?php 
				foreach( $part_title_arr as $title ){
					?>
					<td><?php echo $title;?></td>
					<?php
				}
			?>
		</tr>
		<?php 
			if(1 == $this->is_arrays($message_arr) ){
				?>
				<tr>
					<?php 
						foreach( $message_arr as $title ){
							?>
							<td><?php echo $title;?></td>
							<?php
						}
					?>
				</tr>
				<?php
			}
			if(2 == $this->is_arrays($message_arr) ){
				foreach( $message_arr as $titles ){
					?>
					<tr>
						<?php 
							foreach( $titles as $title ){
								?>
								<td><?php echo $title;?></td>
								<?php
							}
						?>
					</tr>
					<?php
				}
			}
		?>
		
	</table>
<div>