<?php
/**
 * The template for displaying archive pages
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
			<div class="col-md-<?php echo esc_attr( $col_md ); ?>">
				<?php
				if ( have_posts() ) : ?>

					<?php if ( 'hide' === knowing_god_page_banner() ) : ?>
					<header class="page-header">
						<?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="archive-description">', '</div>' );
						?>
					</header><!-- .page-header -->
					<?php endif; ?>

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					endwhile;

					the_posts_navigation( array(
            'prev_text'          => __( 'Previous' ),
            'next_text'          => __( 'Next' ),
            'screen_reader_text' => __( 'Posts navigation' ),
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
