<div class="container-fluid">
  <div class="col-md-4">
  	<div class="panel panel-default">
		  <!-- Default panel contents -->
		  <div class="panel-heading">
		  	<h4>
					7天内待联系客户(下次联系日期)
				</h4>
		  </div>
		  <!-- Table -->
		  <table class="table table-hover">
		    <thead>
	        <tr>
	          <th>客户</th>
	          <th>联系人</th>
	          <th>主题</th>
	          <th>联系日期</th>
	        </tr>
	      </thead>
	      <tbody>
      	<?php foreach ($data['record_week'] as $record) { ?>
		  		<tr>
	          <td><a href="<?php echo admin_url( 'admin.php?page=crm_edit_model&model=customer&pid='.$record['customer_id'] );?>" target="_blank"><?php echo $record['customer_name']; ?></a></td>
	          <td><a href="<?php echo admin_url( 'admin.php?page=crm_edit_model&model=member&pid='.$record['member_id'] );?>" target="_blank"><?php echo $record['member_name']; ?></a></td>
	          <td><a href="<?php echo admin_url( 'admin.php?page=crm_edit_model&model=record&pid='.$record['record_id'] );?>" target="_blank"><?php echo $record['topic']; ?></a></td>
	          <td><?php echo date( 'Y-m-d H:i:s',strtotime( $record['next_contact_time'] ) ); ?></td>
	        </tr>
		  	<?php } ?>
      	</tbody>
		  </table>
		</div>
  </div>

  <div class="col-md-4">
  	<div class="panel panel-default">
		  <!-- Default panel contents -->
		  <div class="panel-heading">
		  	<h4>
					一月内到期纪念日
				</h4>
		  </div>
		  <!-- Table -->
		</div>
  </div>
</div>