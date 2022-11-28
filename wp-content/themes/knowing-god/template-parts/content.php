<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Knowing_God
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'card-list mb-4' ); ?>>
	<?php
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'knowing-god' ) );
	}
	?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'knowing-god-featured' ); ?>
	<?php endif; ?>
	
	<div class="card-body kg-body-content">
		<?php
		if ( is_singular() ) :
			if ( is_page() ) {
				if ( 'hide' === knowing_god_page_banner() ) :
					if ( 'show' === get_post_meta( get_the_ID(), 'page_title', true ) ) {
						the_title( '<h2 class="card-title">', '</h2>' );
					}
				endif;
			} else {
				the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
		else :
			the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			
		endif;
		?>
		
		<?php if ( 'post' === get_post_type() && is_singular() ) : ?>
		<hr>
	<div class="card-single-footer text-muted">
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
	</div><hr>
	<?php endif; ?>
	
		<p class="card-text">
		<?php echo strip_tags( get_the_excerpt() , '<a><div>'); ?></p>
		
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knowing-god' ),
				'after'  => '</div>',
			) );
		?>
		
	</div>
	<?php if ( 'post' === get_post_type() && ! is_singular() ) : ?>
	
	<div class="blog-footer text-muted">
		<?php
		$meta = get_post_meta( get_the_ID() );
		$style = '';
		if ( ! empty( $meta['audio_file'] ) ) {
			$style = ''; 
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
	<?php knowing_god_editpost_link(); ?>
	
</div><!-- #post-<?php the_ID(); ?> -->
