<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Knowing_God
 */

?>
    <!doctype html>
    <html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <?php wp_head(); ?>
        <script>
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        var lms_url = '<?php echo get_site_url(); ?>';
        <?php if ( function_exists( 'knowing_god_urls' ) ) : ?>
        var lms_url = '<?php echo knowing_god_urls('lms-url'); ?>';
        <?php endif;?>
        </script>
    </head>

    <body <?php body_class(); ?>>
        <div id="app" class="site">
            <a class="skip-link screen-reader-text" href="#content">
                <?php esc_html_e( 'Skip to content', 'knowing-god' ); ?>
            </a>
            <nav id="site-navigation" class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <?php knowing_god_get_header_logo(); ?>
                    </div>

                     <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php
                if ( has_nav_menu( 'primary-menu' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary-menu',
                        'menu_id'        => 'primary-menu',
                        'menu_class' => 'navbar-nav mr-auto kg-nav-menu',
                        'container' => false,
                    ) );
                }
            ?>
                       <ul class="navbar-nav kg-nav-menu" id="login-menu">
                            <?php
                    $is_logged_in = is_user_logged_in();
                    
                if ( $is_logged_in ) {
                    $url = admin_url();
                    $redirect = wp_login_url();
                    if ( function_exists( 'knowing_god_urls' ) ) {
                        if ( knowing_god_is_user( 'subscriber' ) ) {
                            $url = knowing_god_urls( 'my-account' );
                        }
                        $redirect = knowing_god_urls( 'lms-logout' );
                    }
                ?>
                <?php /* ?>
                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-myaccount">
                    <a href="<?php echo esc_url( $url ); ?>">
                        <?php esc_html_e( 'My Account', 'knowing-god' ); ?>
                    </a>
                </li>
                <?php */ 
				
				?>
                
					<?php if ( function_exists( 'knowing_god_urls' ) ) { ?>
                      <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php esc_html_e( 'My Account', 'knowing-god' ); ?></a>
                          <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog"   aria-labelledby="navbarDropdownMenuLink">
								<li class="menu-item">
									<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'my-account-user' ) ); ?>"><?php esc_html_e( 'Dashboard', 'knowing-god' ); ?></a>
								</li>
						
								<?php if ( knowing_god_get_user_role() == 'administrator' ) { ?>
								<li class="menu-item">
								<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'my-account' ) ); ?>"><?php esc_html_e( 'Admin Dashboard', 'knowing-god' ); ?></a>
								</li>
								<?php } ?>
						
								<li class="menu-item">
								<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'my-profile' ) ); ?>"><?php esc_html_e( 'My Profile', 'knowing-god' ); ?></a>
								</li>

								<li class="menu-item">
								<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'my-courses' ) ); ?>"><?php esc_html_e( 'My Courses', 'knowing-god' ); ?></a>
								</li>

								<li class="menu-item">
								<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'my-groups' ) ); ?>"><?php esc_html_e( 'My Groups', 'knowing-god' ); ?></a>
								</li>                        

								<li class="menu-item">
								<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'user-categories' ) ); ?>"><?php esc_html_e( 'Categories', 'knowing-god' ); ?></a>
								</li>

								<li class="menu-item">
								<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'user-messages' ) ); ?>"><?php esc_html_e( 'Messages', 'knowing-god' ); ?></a>
								</li>
								
								<li class="menu-item">
								<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'by-pathway' ) ); ?>"><?php esc_html_e( 'Quiz Analysis', 'knowing-god' ); ?></a>
								</li>
								
								<?php /* ?>						
								<!-- Exam Analysis -->
								<li class="menu-item dropdown-submenu">
									<a class="dropdown-item" href="#">Quiz Analysis</a>
										<ul class="dropdown-menu">
											<li  class="menu-item">
												<a href="<?php echo esc_url( knowing_god_urls( 'by-pathway' ) ); ?>" class="dropdown-item"><?php esc_html_e( 'By Pathway', 'knowing-god' ); ?></a>
											</li>
											<li  class="menu-item">
												<a href="<?php echo esc_url( knowing_god_urls( 'by-exam' ) ); ?>" class="dropdown-item"><?php esc_html_e( 'By Exam', 'knowing-god' ); ?></a>
											</li>    
											<li  class="menu-item">
												<a href="<?php echo esc_url( knowing_god_urls( 'history' ) ); ?>" class="dropdown-item"><?php esc_html_e( 'History', 'knowing-god' ); ?></a>
											</li>
										</ul>
								</li>
								<?php */ ?>
						  
								<li class="menu-item">
									<a class="dropdown-item" href="<?php echo esc_url( knowing_god_urls( 'feedback' ) ); ?>"><?php esc_html_e( 'Feedback', 'knowing-god' ); ?></a>
								</li>
							</ul> 
                    </li>
					<?php } ?>

                <li class="nav-item menu-item-myaccount">
                    <a href="<?php echo wp_logout_url(); ?>&redirect_to=<?php echo $redirect; ?>" class="nav-link">
                        <?php esc_html_e( 'Logout', 'knowing-god' ); ?>
                    </a>
                </li>
                <?php } else {
                    $login = wp_login_url();
                    if ( function_exists( 'knowing_god_urls' ) ) {
                        $login = knowing_god_urls( 'login' );
                    }
                    ?>
                    <li id="menu-item-login" class="nav-item menu-item menu-item-type-post_type menu-item-object-page">
                        <a href="<?php echo esc_url( $login ); ?>" class="nav-link">
                        <?php esc_html_e( 'Login', 'knowing-god' ); ?>
                        </a>
                    </li>
                    <?php if ( get_option('users_can_register') ) :
                        $registration = wp_registration_url();
                        if ( function_exists( 'knowing_god_urls' ) ) {
                        $registration = knowing_god_urls( 'registration' );
                        }
                        ?>
                        <li id="menu-item-registration" class="nav-item menu-item menu-item-type-post_type menu-item-object-page">
                        <a href="<?php echo esc_url( $registration ); ?>" class="nav-link">
                        <?php esc_html_e( 'Register', 'knowing-god' ); ?>
                        </a>
                        </li>
                    <?php endif; ?>
                <?php } ?>
                        </ul>
                       
                    </div>
                </div>
            </nav>

            <!-- #site-navigation -->
