  <?php foreach($data->items as $item){
    ?>
     <tr>
      <td class="no1 column-no1 has-row-actions column-primary" data-colname="队列编号&nbsp;&nbsp;">
        <div id="no1_"><?php echo $item['no1']?></div>
        <button type="button" class="toggle-row">
          <span class="screen-reader-text">显示详情</span>
        </button>
      </td>

      <td class="ult_12wk column-ult_12wk" data-colname="头臀长B超_12周 &nbsp;&nbsp; 
          所有
          0
          1">
        <div id="ult_12wk_"><?php echo $item['ult_12wk']?></div>
      </td>
      <td class="ult_16wk column-ult_16wk" data-colname="唐筛B超_18周&nbsp;&nbsp; 
          所有
          0
          1">
        <div id="ult_16wk_"><?php echo $item['ult_16wk']?></div>
      </td>
      <td class="ult_24wk column-ult_24wk" data-colname="大排畸B超_24周&nbsp;&nbsp; 
          所有
          0
          1">
        <div id="ult_24wk_"><?php echo $item['ult_24wk']?></div>
      </td>
      <td class="ult_32wk column-ult_32wk" data-colname="小排畸B超_32周&nbsp;&nbsp; 
          所有
          0
          1">
        <div id="ult_32wk_"><?php echo $item['ult_32wk']?></div>
      </td>
      <td class="ult_37wk column-ult_37wk" data-colname="临产B超_37周&nbsp;&nbsp; 
          所有
          0
          1">
        <div id="ult_37wk_"><?php echo $item['ult_37wk']?></div>
      </td>
    </tr>


<?php  

  }?>
   


 