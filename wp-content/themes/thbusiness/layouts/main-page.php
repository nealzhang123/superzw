<?php
/**
 * Template Name: 首页模板
 *
 * Displays the Test Template of the theme.
 *
 * @package thbusiness
 */
get_header();

$cate_id = get_cat_ID( '公司要闻' );
$news_posts = get_posts( array(
		'category' => $cate_id,
		'numberposts' => 7,
	)	
);

?>
<div class="col-xs-12 col-sm-12 col-md-12">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="homenews">
		      <div class="titles">
		        <h2 class="fl">
		        	<a href="<?php echo site_url('公司要闻'); ?>">要闻快报</a>
		        	<div class="fr more"> <a class="a-more iconbg" href="<?php echo site_url('公司要闻'); ?>" title="更多"><img src="<?php bloginfo('template_directory'); ?>/images/右箭头.gif" /></a>
		        	</div>
		        </h2>
		        
		      </div>
		      <div class="homemain-conts homenew s-conts" style="border:none;">
		       
		        <div class="row" style="margin-top: 1em;">
		          <div class="col-xs-3 col-sm-3 col-md-3">
		          	<?php echo get_the_post_thumbnail( $news_posts[0]->ID ); ?>
		          </div>
		          <div class="col-xs-9 col-sm-9 col-md-9">
		            <h3 style="margin: 0 0 0.5em 0;"><a href="<?php echo site_url('公司要闻') . '/?post_id=' . $news_posts[0]->ID; ?>" target="_blank"><?php echo $news_posts[0]->post_title; ?></a></h3>
		            <p> <?php echo trim( mb_substr( $news_posts[0]->post_content, 0, 200, 'utf-8') ); ?>...<a class="org" href="<?php echo site_url('公司要闻') . '/?post_id=' . $news_posts[0]->ID; ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/右双箭头.gif" title="详细"></a></p>
		          </div>
		        </div>
		      
		        <div class="row homenews-list" style="width: 80%;margin-top: 1em;">
	        	<?php 
	        	array_shift( $news_posts );

	        	foreach ( $news_posts as $post ) {
	        	?>
	        		<div class="col-xs-6 col-sm-6 col-md-6">
		          	<a href="<?php echo site_url('公司要闻') . '/?post_id=' . $post->ID; ?>" target="_blank"><?php echo trim( mb_substr( $post->post_title, 0, 25, 'utf-8') );?></a>
		          	</div>
	        	<?php } ?>
		        </div>

		      </div>
		    </div>
		</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- .col-xs-12 col-sm-12 col-md-12 -->
<?php get_footer(); ?>