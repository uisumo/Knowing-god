<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Knowing_God
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'card mb-4' ); ?>>
	<?php
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'knowing-god' ) );
	}
	?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'knowing-god-featured' ); ?>
	<?php endif; ?>
	<div class="card-body">
		<?php
		if ( is_singular() ) :
			if ( 'show' === knowing_god_page_banner() ) :
				the_title( '<h2 class="card-title">', '</h2>' );
			endif;
		else :
			the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
		<div class="card-text">
		<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'knowing-god' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knowing-god' ),
				'after'  => '</div>',
			) );
		?>
		</div>
	</div>
	<?php if ( 'post' === get_post_type() ) : ?>
	<div class="card-footer text-muted download-link">
		<?php
		$meta = get_post_meta( get_the_ID() );
		$style = '';
		if ( ! empty( $meta['audio_file'] ) ) {
			$style = 'style="border-bottom:1px solid black;"';
		}
		?>
		<div <?php echo $style; ?>>
		<ul class="card-icons">
			<li><?php knowing_god_posted_by(); ?></li>
			<li><?php knowing_god_posted_on(); ?></li>
			<li><?php knowing_god_categories(); ?></li>
			<li><?php knowing_god_series(); ?></li>
			<?php if ( is_single() ) : ?>
				<li><?php knowing_god_icon_row(); ?></li>
			<?php endif; ?>
			<?php // knowing_god_tags(); ?>
		</ul>
		</div>
		<?php knowing_god_audio(); ?>
	</div>
	<?php endif; ?>
	<?php // knowing_god_comments_link(); ?>
	<?php knowing_god_editpost_link(); 
		
	?>
	
</div><!-- #post-<?php the_ID(); ?> -->
