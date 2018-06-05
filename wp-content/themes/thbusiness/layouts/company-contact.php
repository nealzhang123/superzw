<?php
/**
 * Template Name: 联系我们模板
 *
 * Displays the Test Template of the theme.
 *
 * @package thbusiness
 */
get_header(); ?>
<div class="col-xs-12 col-sm-12 col-md-8">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php thbusiness_posted_on(); 
			if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'thbusiness' ), __( '1 Comment', 'thbusiness' ), __( '% Comments', 'thbusiness' ) ); ?></span>
			<?php endif; ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<?php thbusiness_featured_image(); ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'thbusiness' ),
				'after'  => '</div>',
				'link_before' => '<span>',
				'link_after' => '</span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer-insinglepost">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'thbusiness' ) );

			if ( thbusiness_categorized_blog() ) {
				echo '<span class="cat-links">' . $category_list . '</span>';
			} 

			echo get_the_tag_list('<span class="tags-links">',', ','</span>');
		?>

		<?php edit_post_link( __( 'Edit', 'thbusiness' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
		</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- .col-xs-12 col-sm-6 col-md-8 -->
<div class="col-xs-12 col-sm-6 col-md-4">
	<?php get_sidebar(); ?>
</div><!-- .col-xs-12 .col-sm-6 .col-md-4 -->
<?php get_footer(); ?>