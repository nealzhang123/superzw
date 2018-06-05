<?php
$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field'    => 'name',
            'terms'    => '投资者确认页',
        ),
    ),
);
$query = new WP_Query( $args );
$agree_post = $query->posts[0];

$post_thumbnail = get_the_post_thumbnail( $agree_post );
?>
<style type="text/css">
body {
    background: url(<?php bloginfo('template_directory'); ?>/images/河海同意书背景图.jpg) center top no-repeat;
}
BODY {
    padding-bottom: 0;
    line-height: 180%;
    margin: 0 auto;
    padding-left: 0;
    width: 100%;
    padding-right: 0;
    font-family: "微软雅黑";
    color: #333;
    font-size: 12px;
    padding-top: 0;
}
body {
    display: block;
    margin: 8px;
}

.pageWidth {
    width: 1003px;
    background-repeat: no-repeat;
    background-position: center center;
}

#box_root {
    margin-left: auto;
    margin-right: auto;
}

#box_main {
    margin-left: auto;
    margin-right: auto;
}

#box_main_sub1{
	width: 100%;
}	

#box_main_sub2 {
    width: 100%;
    padding-top: 25px;
}

.columnSpace {
    padding-bottom: 0;
    padding-left: 0;
    padding-right: 0;
    padding-top: 0;
}

</style>
<script src="//cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
(function($){
	$(function(){
		$(document).on('click','#agree_accecpt',function(){
			$.cookie('hehai_agreed', 1, { expires: 1 });
			window.location.reload();
		});

		$(document).on('click','#agree_close',function(){
			window.open('','_self','');
			window.close();
		});
	}); 
})(jQuery);
</script>
</head>
<body id="mzsm"> 
<div class="pageWidth" id="box_root"> 
  <div id="box_main"> 
    <div id="box_main_sub1"> 
      <div xmlns="" class="columnSpace" name="说明页">  
        <div><?php echo $post_thumbnail; ?></div> 
      </div>
    </div>  
    <div id="box_main_sub2"> 
      <div xmlns="" class="columnSpace" name="说明页">  
        <div>
            <div style="padding-bottom: 25px; color: #172748; font-size: 16px; font-weight: bold;">
			<?php echo $agree_post->post_title; ?>
			</div>
			<div style="padding-bottom: 15px; overflow-x: hidden; overflow-y: auto; padding-left: 15px; padding-right: 15px; background: url(<?php bloginfo('template_directory'); ?>/images/mzsm_nrbg.png); height: 470px; color: #323232; font-size: 15px; padding-top: 15px;">
				<?php echo $agree_post->post_content; ?>
			</div>
			<div style="text-align: center; padding-top: 25px; padding-bottom:35px;">
				<button id="agree_accecpt">接受</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<button id="agree_close">放弃</button>
			</div>
		</div> 
      </div>
    </div> 
  </div> 
</div> 
</body>
</html>