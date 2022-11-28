<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Knowing_God
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'card mb-4' ); ?>>
	<?php /* if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'knowing-god-featured' ); ?>
	<?php endif; */ ?>

	<div class="card-body">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	<div class="card-text"><?php the_excerpt(); ?></div>
	</div>
	<?php if ( 'post' === get_post_type() ) : ?>
	<div class="card-footer text-muted download-link">
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
	<?php knowing_god_editpost_link(); ?>
</div><!-- #post-<?php the_ID(); ?> -->
