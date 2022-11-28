<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>

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
