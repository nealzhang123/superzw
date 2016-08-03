<div class="container-fluid"> 
  <div id="category" class="row">
    <ul style="margin-bottom:5px;" class="nav nav-pills">
      <li style="padding-left:0px;padding-right:5px;" class="nav-header">
        <i class="fa fa-list-ul fa-3x"></i> 
      </li>
      <?php foreach ($data['categories'] as $key => $category) { ?>
        <li class="category_item<?php echo (0==$key)? ' active':''; ?>"><a><?php echo $category['cate_name'] ?></a></li>  
      <?php } ?>
      <li>
        <a style="padding:2px;"><i class="fa fa-plus-square fa-2x"></i></a>
      </li> 
      <li>
        <a style="padding:2px;"><i class="fa fa-pencil-square fa-2x"></i></a>
      </li>
      <li> 
        <a style="padding:2px;"><i class="fa fa-times-circle fa-2x"></i></a>
      </li>
    </ul>
  </div>
  <div>
    <form method="POST" ENCTYPE="multipart/form-data">
      <div class="col-md-2">
        <p>
        1.选择分组，添加该分组所有客户至接收人手机.<br>
        2.如果没有你需要的分组，可自行创建新分组.<br>
        3.手机号码为空的客户，分组导入自动忽略.<br>
        4.同一手机号间隔发送时间不得少于20秒.<br>
        5.一次发送最好不多于30个客户.<br>
        6.每行一个客户.<br>
        </p>
      </div>

      <div class="col-md-2">
        <p align="center">
          <b>&nbsp;接受人手机<br></b>
          <font color="#808080">每行一个号码</font>
        </p>
        <p align="center">
          <textarea class="form-control" name="sms_account" rows="8" id="sms_account"></textarea>
        </p>
      </div>

      <div class="col-md-3">
        <p align="center">
          <b>短信内容<br></b>
          
        </p>

        <p align="left">
          <b>选择模板:</b>
          <a title="编辑模板" class="btn btn-small btn-primary edit_send_view" value="">编辑</a>
          <a title="新增模版" class="btn btn-small btn-primary edit_send_view" value="" >新增</a><br />
          <b>短信内容:</b><br />
          <textarea rows="15" class="form-control" name="sms_content" id="sms_content"></textarea>
        </p>

        <div>选择开始发送时间: &nbsp;
          <span div class='input-group date'>
            <input data-format="yyyy-MM-dd hh:mm" type="text" name="sms_public_time" class='timepicker'>
          </span>
          <div style="letter-spacing:3px;">
            &gt;&gt;<font color="red">发送时间为空</font>则即时发送。
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <p>
          <font color="#FF0000">注意：</font>（您使用本系统发送短信，就表明您同意并接受以下内容）<br><br>
          1.不得发送包含以下内容、文字的短信息内容：非法的、骚扰性的、中伤他人的、辱骂性的、恐吓性的、伤害性的、庸俗的、淫秽的信息；教唆他人构成犯罪行为的信息；危害国家安全的信息；及任何不符合国家法律、国际惯例、地方法律规定的信息。<br><br>
          2.不能违反运营商规定，不得发送竞争对手产品的广告，不能按手机号段形式进行广告业务的宣传等，不能发送与本行业无关和移动运营商限制和禁止发送的短信内容，特别是广告类信息，群发短信等，对违反此声明产生的一切后果由发送者及其单位承担。<br><br>
          3.最好不要在晚22:00至早7:00时段发送短信，以免引起客户反感。<br><br>
          4.<font color="red">短信内容不能多于48个字（其中空格，数字，字母，汉字均为一个字）</font><br><br>
        </p>
        <br /><br /><br />
        <div id="showzishu">你还能输入:<font color="red"><b>65</b></font>个字...</div>
        <button type="submit" id="send_save" class="btn btn-primary">群发</button>
      </div>
    </form>
  </div>
</div>