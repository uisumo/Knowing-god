<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Knowing_God
 */

get_header(); ?>
    <div class="container kg-layout">
        <?php
		if ( 'show' === knowing_god_page_banner() ) {
			include get_template_directory() . '/inc/top-banner.php';
		}
		?>
            <div class="row">
                <?php if ( 'left' === knowing_god_sidebar_position() ) : ?>
                    <!-- Sidebar Widgets Column -->
                    <div class="col-md-4">
                        <?php get_sidebar(); ?>
                    </div>
                    <?php
			endif;
			$col_md = 8;
			if ( 'none' === knowing_god_sidebar_position() ) {				
				$col_md = 12;
			}
			?>
                        <!-- Blog Entries Column -->
                        <div class="col-md-<?php echo esc_attr( $col_md ); ?> kg-404">
                            <?php if ( 'hide' === knowing_god_page_banner() ) : ?>
                                <header class="page-header">
                                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'knowing-god' ); ?></h1> </header>
                                <!-- .page-header -->
                                <?php endif; ?>
                                    <div class="page-content">
                                        <h2>
                                            <?php esc_html_e( 'OOPS! WE ARE SORRY , PAGE NOT FOUND', 'knowing-god' ); ?>
                                        </h2>
                                    </div>
                                    <!-- .page-content -->
                        </div>
                        <!-- .error-404 -->
                        <?php if ( 'right' === knowing_god_sidebar_position() ) : ?>
                            <!-- Sidebar Widgets Column -->
                            <div class="col-md-4">
                                <?php get_sidebar(); ?>
                            </div>
                            <?php endif; ?>
            </div>
            <!-- .row -->
    </div>
    <!-- .container -->
    <?php
get_footer();