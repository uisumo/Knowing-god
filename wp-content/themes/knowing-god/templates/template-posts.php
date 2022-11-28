<?php
/**
 * Template Name: Posts
 *
 * @package Knowing God
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
				$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
				$original_query = $wp_query;
				$wp_query = null;
				$args = array(
					'post_type' => 'post',
					'paged' => intval( $paged ),
					'meta_query' => array(
						array(
							'key'     => 'custom_post_type',
							'value'   => 'post',
							'compare' => '=',
						),
					),
				);
				$wp_query = new WP_Query( $args );
				
				if ( $wp_query->have_posts() ) :
					
					/* Start the Loop */
					while ( $wp_query->have_posts() ) : $wp_query->the_post();

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
