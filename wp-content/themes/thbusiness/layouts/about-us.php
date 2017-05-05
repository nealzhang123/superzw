<?php
/**
 * Template Name: 关于我们模板
 *
 * Displays the Test Template of the theme.
 *
 * @package thbusiness
 */
get_header(); ?>
<div class="col-xs-12 col-sm-12 col-md-8">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- .col-xs-12 col-sm-6 col-md-8 -->
<div class="col-xs-12 col-sm-6 col-md-4">
	<?php get_sidebar(); ?>
</div><!-- .col-xs-12 .col-sm-6 .col-md-4 -->
<?php get_footer(); ?>