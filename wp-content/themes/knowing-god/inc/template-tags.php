<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Knowing_God
 */

if ( ! function_exists( 'knowing_god_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function knowing_god_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="pathway_gray">' . $time_string . '</a>';
		echo '<i class="fa fa-clock-o pathway_gray"></i> ' . $posted_on; // WPCS: XSS OK.
	}
endif;

if ( ! function_exists( 'knowing_god_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current post author.
	 */
	function knowing_god_posted_by() {
		$byline = '<a class="pathway_gray" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';

		echo '<i class="fa fa-user-circle pathway_gray"></i> ' . $byline; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'knowing_god_categories' ) ) :
	/**
	 * Prints HTML with meta information for the categories.
	 */
	function knowing_god_categories() {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'knowing-god' ) );
		if ( $categories_list ) {
			printf( '<i class="fa fa-folder-open pathway_gray"></i> ' . $categories_list ); // WPCS: XSS OK.
		}
	}
endif;

if ( ! function_exists( 'knowing_god_tags' ) ) :
	/**
	 * Prints HTML with meta information for the tags.
	 */
	function knowing_god_tags() {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'knowing-god' ) );
		if ( $tags_list ) {
			printf( '<i class="fa fa-tags pathway_gray"></i>' . $tags_list ); // WPCS: XSS OK.
		}
	}
endif;

if ( ! function_exists( 'knowing_god_comments_link' ) ) :
	/**
	 * Prints HTML with meta information for the comments.
	 */
	function knowing_god_comments_link() {
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'knowing-god' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'knowing_god_editpost_link' ) ) :
	/**
	 * Prints HTML with meta information for the comments.
	 */
	function knowing_god_editpost_link() {
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
	}
endif;

if ( ! function_exists( 'knowing_god_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function knowing_god_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			knowing_god_categories();
			knowing_god_tags();
		}

		knowing_god_comments_link();

		knowing_god_editpost_link();
	}
endif;

if ( ! function_exists( 'knowing_god_categorized_blog' ) ) :
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function knowing_god_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'knowing_god_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'knowing_god_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so knowing_god_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so knowing_god_categorized_blog should return false.
			return false;
		}
	}
endif;

function knowing_god_series()
{
	if ( function_exists( 'get_the_series' ) ) {
		$serarray = get_the_series();
		//echo '<pre>';
		//print_r( $serarray );
		if ( ! empty( $serarray ) ) {
			$series = $serarray[0];
			$series_id = $series->term_id;
			$series_name = $series->name;
			$series_link = get_series_link( $series_id );
			$count = $series->count;
			$link = '<a href="' . $series_link . '" class="series-' . $series_id . '" title="'.$series_name.'">' . $series_name . '</a>&nbsp;<span class="badge badge-dark tags">' . $count . '</span>';
			echo '<i class="fa fa-files-o pathway_gray"></i> ' . $link; // WPCS: XSS OK.
		} else {
			echo '<i class="fa fa-files-o pathway_gray no-series"></i>' . esc_html__( 'No Series', 'knowing-god' ); // WPCS: XSS OK.
		}		
	}
}

function knowing_god_has_series() {
	if ( function_exists( 'get_the_series' ) ) {
		$serarray = get_the_series();
		if ( ! empty( $serarray ) ) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function knowing_god_audio() {
	$audio_file = get_post_meta( get_the_ID(), 'audio_file', true );
	if ( ! empty( $audio_file ) ) : ?>
	<div class="mt-3">	
		<audio class="" controls="" style="width:100%;">
			<source src="<?php echo esc_url( $audio_file ); ?>" type="audio/mp3">
			Your browser does not support the audio tag.
		</audio>
	</div>
	<?php
	endif;
}