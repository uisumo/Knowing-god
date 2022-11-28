<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Knowing_God
 */

?>

<div id="post-<?php the_ID(); ?>" class="content-data">	
	
	<?php
	if ( 'hide' === knowing_god_page_banner() ) :
		if ( 'show' === get_post_meta( get_the_ID(), 'page_title', true ) ) {
			the_title( '<h2 class="card-title">', '</h2>' );
		}
	endif;
	?>
	<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knowing-god' ),
			'after'  => '</div>',
		) );
	?>


<?php if ( get_edit_post_link() ) : ?>
	<footer class="entry-footer">
		<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'knowing-god' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
<?php endif; ?>
</div><!-- #post-<?php the_ID(); ?> -->
