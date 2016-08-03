<div class="container-fluid">
	
  <div>
    <div class="pull-left">
      <button class="btn btn-primary btn-sm exec_export" data-href="<?php echo admin_url('admin.php?page=track_export_file'); ?>"><i class="glyphicon glyphicon-download"></i>&nbsp;导出</button>


    </div>
  	<div class="pull-right" id="hos_table_nav_top">
  	  <?php $this->get_table_nav_top_view( $data['list'] ); ?>
  	</div>
	</div>
  <div class="list_content">
		<div id="hos_table_content">
      <table class="wp-list-table widefat fixed striped ">
  <thead>
    <tr>
      <th scope="col" id="no1" class="manage-column column-no1 column-primary">队列编号&nbsp;&nbsp; 
       <input type="text" name="no1" size="8" value="" id="pregnant_b_no1" class="no1_health_search "> 
      </th>
      <th scope="col" id="ult_12wk" class="manage-column column-ult_12wk">头臀长B超_12周 &nbsp;&nbsp;
        <select name="ult_12wk " class="  health_search" id="pregnant_b_ult_12wk">
          <option selected="selected" value="">所有</option>
          <option value="0">0</option>
          <option value="1">1</option>
        </select>
      </th>
      <th scope="col" id="ult_16wk" class="manage-column column-ult_16wk">唐筛B超_18周&nbsp;&nbsp;
        <select name="ult_16wk " class="  health_search" id="pregnant_b_ult_16wk">
          <option selected="selected" value="">所有</option>
          <option value="0">0</option>
          <option value="1">1</option>
        </select>
      </th>
      <th scope="col" id="ult_24wk" class="manage-column column-ult_24wk">大排畸B超_24周&nbsp;&nbsp;
        <select name="ult_24wk " class=" pregnant_b_ult_24wk health_search" id="pregnant_b_ult_24wk">
          <option selected="selected" value="">所有</option>
          <option value="0">0</option>
          <option value="1">1</option>
        </select>
      </th>
      <th scope="col" id="ult_32wk" class="manage-column column-ult_32wk">小排畸B超_32周&nbsp;&nbsp; 
        <select name="ult_32wk " class="  health_search" id="pregnant_b_ult_32wk">
          <option selected="selected" value="">所有</option>
          <option value="0">0</option>
          <option value="1">1</option>
        </select>
      </th>
      <th scope="col" id="ult_37wk" class="manage-column column-ult_37wk">临产B超_37周&nbsp;&nbsp; 
        <select name="ult_37wk " class="  health_search" id="pregnant_b_ult_37wk">
          <option selected="selected" value="">所有</option>
          <option value="0">0</option>
          <option value="1">1</option>
        </select>
      </th> 
    </tr>
    </thead>
    <tbody id="the-list">
	   <?php $this->get_table_content_view($data['list']);?>
    </tbody>
       <tfoot>
        <tr>
          <th scope="col" class="manage-column column-no1 column-primary">队列编号</th>
          <th scope="col" class="manage-column column-ult_12wk">头臀长B超_12周 </th>
          <th scope="col" id="ult_16wk" class="manage-column column-ult_16wk">唐筛B超_18周</th>
          <th scope="col" class="manage-column column-ult_24wk">大排畸B超_24周</th>
          <th scope="col" class="manage-column column-ult_32wk">小排畸B超_32周</th>
          <th scope="col" class="manage-column column-ult_37wk">临产B超_37周</th>  </tr>
        </tfoot>
      </table>
		</div>
	</div>
	<div class="tablenav bottom tablenav-pages" id="hos_table_nav_bottom">
    <?php $this->get_table_nav_bottom_view( $data['list'] ); ?>
  </div>

  
</div>
