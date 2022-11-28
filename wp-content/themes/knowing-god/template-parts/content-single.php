<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Knowing_God
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'card-single mb-4' ); ?>>
	<?php
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'knowing-god' ) );
	}
	?>
	
	<?php		
	$course_id = 0;
	if ( knowing_god_get_series_id() ) {
		$course_id = knowing_god_get_series_id();
	}
	$icon_class = 'icon icon-tick-double';
	$onclick = 'mark_as_complete(\''.get_the_ID().'\', \'text\', \''.$course_id.'\', 0)';
	if ( knowing_god_is_post_completed( get_the_ID(), $course_id, '', 'text' ) ) {
		$icon_class = 'icon icon-tick-border';
		$onclick = 'mark_as_complete(\''.get_the_ID().'\', \'text-uncomplete\', \''.$course_id.'\', 0)';
	}
	if ( has_post_thumbnail() ) : ?>
		<?php
		if( '' !== get_the_content() ) {
			if ( ! is_user_logged_in() ) {
				?>
			<button class="fixed-top-left task-btn task-no-hover" data-toggle="modal" data-target="#loginModalForm"><i class="<?php echo esc_attr( $icon_class ); ?>" id="text_icon"></i></button>
			<?php
			} else {
		?>
			<button class="fixed-top-left task-btn task-no-hover" onclick="<?php echo $onclick; ?>"><i class="<?php echo esc_attr( $icon_class ); ?>" id="text_icon"></i></button>
			<?php } ?>
			
			<?php
			$icon_class_pointer = 'lesson-pin icon icon-map-pointer';
			if ( is_user_logged_in() ) {
				if ( knowing_god_is_post_completed( get_the_ID(), $course_id, 0 ) ) {
					$icon_class_pointer = 'lesson-pin icon icon-pointer-border';
				}
			}
			?>
			<div class="fixed-top-right ">
				<i class="<?php echo esc_attr( $icon_class_pointer ); ?>" id="overall_status"></i>
			</div>
			
			
			<?php
		}
		?>
		<?php the_post_thumbnail( 'knowing-god-featured' ); ?>
	<?php endif; ?>
	
	<div class="card-single-body kg-body-content">
		<?php
		if ( is_singular() ) :
			if ( is_page() ) {
				if ( 'hide' === knowing_god_page_banner() ) :
					if ( 'show' === get_post_meta( get_the_ID(), 'page_title', true ) ) {
						the_title( '<h2 class="card-title">', '</h2>' );
					}
				endif;
			} else {
				the_title( '<h2 class="card-title mt-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
				<?php
				if( ! has_post_thumbnail() && '' !== get_the_content() ) {
					if ( ! is_user_logged_in() ) { ?>
					<button class="fixed-top-left task-btn title-button-to-complete" data-toggle="modal" data-target="#loginModalForm"><i class="<?php echo esc_attr( $icon_class ); ?>" id="text_icon"></i></button>
					<?php						
					} else {
					?>
					<button class="fixed-top-left task-btn title-button-to-complete" onclick="<?php echo $onclick; ?>"><i class="<?php echo esc_attr( $icon_class ); ?>" id="text_icon"></i></button>
				<?php
					}
				}
				?>
				<?php				
			}
		else :
			the_title( '<h2 class="card-title mt-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			
		endif;
		?>
		
		<?php if ( 'post' === get_post_type() && is_singular() ) : ?>
	
	<div class="card-single-footer blog-footer text-muted">
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
		<?php // knowing_god_audio(); ?>
	</div>
	<?php endif; ?>
	
		<p class="card-text">
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
		</p>
	</div>
	<?php if ( 'post' === get_post_type() && ! is_singular() ) : ?>
	
	<div class="card-footer text-muted">
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
	<?php knowing_god_editpost_link(); ?>
	
</div><!-- #post-<?php the_ID(); ?> -->
