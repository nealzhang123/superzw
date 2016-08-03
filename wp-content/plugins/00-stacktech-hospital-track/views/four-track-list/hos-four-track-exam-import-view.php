<div class="container-fluid " id="hos_four_exam_content">
  <div>
    <h4>文件内容格式示例(第二行参数必须保持一致)</h4>
  </div>
  <div>
     <table class="table table-bordered">
			<thead>
				<tr>
					<?php if( $data['hos_type'] == 1 ){ ?>
            <th>队列号no2</th>
          <?php }else{ ?>
            <th>队列号no1</th>
          <?php } ?>
					<th>入园儿童姓名</th>
					<th>入园体检日期</th>
					<th>体检单位</th>
					<th>血清(ml)</th>
					<th>血浆(ml)</th>
					<th>血细胞(ml)</th>
					<th>血样质量</th>
					<th>血常规结果</th>
					<th>ALT结果</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php if( $data['hos_type'] == 1 ){ ?>
            <th>no2</th>
          <?php }else{ ?>
            <th>no1</th>
          <?php } ?>
					<th>name_ry</th>
					<th>datet_ry</th>
					<th>meu_ry</th>
					<th>cserum_ry</th>
					<th>cplasma_ry</th>
					<th>cbcell_ry</th>
					<th>bloodqu_ry</th>
					<th>brtr_ry</th>
					<th>altr_ry</th>
					<th>note_ry</th>
				</tr>
				<tr>
					<th>HP80695</th>
					<th>成演习</th>
					<th>2016-02-15</th>
					<th>江岸区</th>
					<th>0</th>
					<th>0.5</th>
					<th>0.5</th>
					<th>正常</th>
					<th>有</th>
					<th>有</th>
					<th>备注123</th>
				</tr>
			</tbody>
		</table>    
	</div>
</div>