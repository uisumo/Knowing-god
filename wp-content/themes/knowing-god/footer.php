<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Knowing_God
 */
 if ( function_exists( 'knowing_god_social_share' ) ) {
	knowing_god_social_share();
 }
 if ( function_exists( 'knowing_god_globe_icon' ) ) {
	knowing_god_globe_icon();
 }
 if ( function_exists( 'knowing_god_quiz_icon' ) ) {
	knowing_god_quiz_icon();
 }
 if ( function_exists( 'knowing_god_login_modal' ) ) {
	knowing_god_login_modal();
 }
 if ( function_exists( 'knowing_god_siteissue_modal' ) ) {
	knowing_god_siteissue_modal();
 }
 if ( function_exists( 'knowing_god_login_modal_form' ) ) {
	knowing_god_login_modal_form();
 }
 if ( function_exists( 'knowing_god_generic_modal' ) ) {
	knowing_god_generic_modal();
 } 
 if ( function_exists( 'knowing_god_newsletter_modal' ) ) {
	knowing_god_newsletter_modal();
 }
?>
	<?php if ( is_active_sidebar( 'footer-widgets' ) ) { ?>
	<div class="footer mb-3" style="background-color:black; border-bottom: 1px solid grey;">
		<div class="container">
			<div class="row mt-2">
				<?php dynamic_sidebar( 'footer-widgets' ); ?>				
			</div>
		</div>
	</div><!--.row - Site Links-->
	<?php } ?>
	<footer class="footer-bottom py-1 bg-dark">
        <div class="container">
            <div class="row">
                <?php if ( has_nav_menu( 'footer-menu' ) ) { ?>
				<div class="col-md-4 mt-1 ml-auto">
				<?php
					$args = array( 
						'theme_location' => 'footer-menu',
						'depth' => 4,
						'menu_class' => 'm-0 text-center',
						'container' => false,						
					);
					wp_nav_menu( $args );
				?>
				</div>				
				<?php
				}
				?>
                <?php if ( 'show' === get_theme_mod( 'footer-credits-show-hide', 'show' ) ) : ?>
				<div class="col-md-4">
                    <p class="mt-1 mb-0" style="color:#888; text-align:center;">
					<?php 
					echo ( get_theme_mod( 'footer-credits') ) ? get_theme_mod( 'footer-credits') : esc_attr( sprintf( esc_html__( '%s &copy; All Rights Reserved', 'knowing-god' ), date( 'Y' ) ) );
					?></p>
                </div>
				<?php endif; ?>
				
                <?php if ( 'show' === get_theme_mod( 'footer-socialicons-show-hide', 'hide' ) ) : ?>
					<?php
					$social = knowing_god_get_social_networks();
					$share_network = false;
					foreach( $social as $key => $val ) {
						if ( '' !== get_theme_mod( $key, '' ) ) {
							$share_network = true;
						}
					}
					if ( true === $share_network ) :
					?>
					<div class="col-md-4 mt-1">
						<p class="text-center">
							<?php
							foreach( $social as $key => $val ) {
								if ( false !== filter_var( get_theme_mod( $key, '' ), FILTER_VALIDATE_URL ) ) {
								?>
									<a href="<?php echo esc_url( get_theme_mod( $key ) );?>" target="_blank" class="mr-2"><i class="<?php echo esc_attr( $val['icon'] ); ?>"></i></a>
								<?php
								}
							} ?>
						</p>
					</div>
					<?php endif; ?>
				<?php endif; ?>
            </div><!--terms of use etc and social links-->
        </div>
    </footer>
</div><!-- #app -->

<?php wp_footer(); ?>

</body>
</html>
