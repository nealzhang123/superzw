				<footer>
					<div id="footer">
					    <a class="cd-top visible-lg"></a>
						<div class="container">
							<div class="row">
								<div class="col-md-6 col-md-offset-3 text-center">
									<p class="kratos-social-icons">
									<?php echo (!kratos_option('social_weibo')) ? '' : '<a target="_blank" rel="nofollow" href="' . kratos_option('social_weibo') . '"><i class="fa fa-weibo"></i></a>'; ?>
									<?php echo (!kratos_option('social_tweibo')) ? '' : '<a target="_blank" rel="nofollow" href="' . kratos_option('social_tweibo') . '"><i class="fa fa-tencent-weibo"></i></a>'; ?>
									<?php echo (!kratos_option('social_twitter')) ? '' : '<a target="_blank" rel="nofollow" href="' . kratos_option('social_twitter') . '"><i class="fa fa-twitter"></i></a>'; ?>
									<?php echo (!kratos_option('social_facebook')) ? '' : '<a target="_blank" rel="nofollow" href="' . kratos_option('social_facebook') . '"><i class="fa fa-facebook-official"></i></a>'; ?>
									<?php echo (!kratos_option('social_linkedin')) ? '' : '<a target="_blank" rel="nofollow" href="' . kratos_option('social_linkedin') . '"><i class="fa fa-linkedin-square"></i></a>'; ?>
									<?php echo (!kratos_option('social_github')) ? '' : '<a target="_blank" rel="nofollow" href="' . kratos_option('social_github') . '"><i class="fa fa-github"></i></a>'; ?>
									</p>
									<p>欢迎追访 <a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a>， 我的学习博客. <br />一起努力进步吧！</p>
								</div>
							</div>
						</div>
					</div>
				</footer>
			</div>
		</div>
		<?php wp_footer();?>
	</body>
</html>