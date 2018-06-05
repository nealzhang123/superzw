<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package thbusiness
 */

$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field'    => 'name',
            'terms'    => '联系我们',
        ),
    ),
);
$query = new WP_Query( $args );
$contact_post = $query->posts[0];

$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field'    => 'name',
            'terms'    => '公司地址',
        ),
    ),
);

$query = new WP_Query( $args );
$address_post = $query->posts[0];

?>
</div><!-- .row -->
</div><!-- .container -->
	</div><!-- #content -->
	
<div class="container-fluid">
<div class="row">
	<span class="scrollup-icon"><a href="#" class="scrollup"></a></span>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
		<div class="row">
		<?php if ( is_active_sidebar( 'footer-left' ) || is_active_sidebar( 'footer-mid' ) || is_active_sidebar( 'footer-right' ) ) : ?>
			<div class="footer-widget-area">
				<div class="col-md-4">
					<div class="left-footer">
						<?php get_sidebar( 'footer-left' ); ?>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="mid-footer">
						<?php get_sidebar( 'footer-mid' ); ?>					
					</div>
				</div>

				<div class="col-md-4">
					<div class="right-footer">
						<?php get_sidebar( 'footer-right' ); ?>					
					</div>
				</div>						
			</div><!-- .footer-widget-area -->
		<?php endif; ?>
	</div><!-- .row -->
</div><!-- .container -->		
	<div class="footer-site-info">
		<div class="container">
		<div class="row footer-middle">
            <div class="col-md-6 col-xs-6 col-lg-6 connect_us">
            	<div class="f-mi-img call-img fl">
                </div>
                <span class="foot_title"><strong>联系我们</strong></span>
                <p><?php echo $contact_post->post_content; ?></p>
            </div>
            <div class="col-md-6 col-xs-6 col-lg-6 connect_us">
            	<span class="foot_title"><strong>公司地址</strong></span>
                <p><?php echo $address_post->post_content; ?></p>
            </div>
        </div>

		<div class="row">
			<div class="footer-details-container">
				<div class="copyright-container">

					<div class="col-xs-12 col-md-6 col-sm-6">
						<div>
						<?php 
							$footer_copyright_text = get_theme_mod( 'footer_copyright_text', '' );
							if( ! empty( $footer_copyright_text ) ) {
								echo wp_kses_post( $footer_copyright_text ); 
							} else {
								$site_link = '<a href="' . esc_url( home_url( '/' ) ) .'" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home">' . esc_attr( get_bloginfo( 'name' ) ) . '</a>';
								printf( __( 'Copyright &copy; %1$s %2$s.', 'thbusiness' ), date( 'Y' ), $site_link );
							} ?>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6 fr">
						技术支持：河海集团信息管理部
						<!-- <div class="credit-container">
							<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'thbusiness' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'thbusiness' ), 'WordPress' ); ?></a><span class="sep"> | </span><a href="<?php echo esc_url( __( 'http://themezhut.com/themes/thbusiness', 'thbusiness' ) ); ?>" target="_blank" rel="designer"><?php echo esc_html__( 'Theme: THBusiness By ThemezHut', 'THBusiness' ); ?></a>
						</div> -->
					</div>
					
				</div><!-- .footer-details-container -->
			</div><!-- .row -->
			</div><!-- .container -->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- .row -->
</div><!-- .container-fluid -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>