<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
			<div class="col-md-<?php echo esc_attr( $col_md ); ?>">
				<?php
				if ( have_posts() ) :

					if ( is_home() && ! is_front_page() ) : ?>
						<header>
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>

					<?php
					endif;

					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );
						// get_template_part( 'template-parts/content', 'search' );

					endwhile;

					// Previous/next page navigation.
					the_posts_pagination( array(
						'prev_text'          => esc_html__( '&laquo;', 'knowing-god' ),
						'next_text'          => esc_html__( '&raquo;', 'knowing-god' ),
						'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'knowing-god' ) . ' </span>',
						'mid_size' => 2,
					) );

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</div>
			<?php if ( 'right' === knowing_god_sidebar_position() ) : ?>
			<!-- Sidebar Widgets Column -->
			<div class="col-md-4">
				<?php get_sidebar(); ?>
			</div>
			<?php endif; ?>

		</div><!-- .row -->
	</div><!-- .container -->

<?php
get_footer();
