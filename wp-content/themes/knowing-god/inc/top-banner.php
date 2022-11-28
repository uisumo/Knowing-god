<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Knowing_God
 */
?>
<!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3">
<?php
if ( is_page_template() ) {
	the_title();
} elseif ( is_page() || is_single() || is_singular() ) {
	if ( is_page() ) {
		the_title();
	}
} elseif ( is_tag() || is_category() || is_author() || is_archive() ) {
	the_archive_title();
	the_archive_description( '<div class="archive-description">', '</div>' );
} elseif ( is_search() ) {
	printf( esc_html__( 'Search Results for: %s', 'knowing-god' ), '<span>' . get_search_query() . '</span>' );
} else {
	if ( is_404() ) {
		esc_html_e( 'Page not found', 'knowing-god' );
	} else {
		esc_html_e( 'PathwayBlog', 'knowing-god' );
	}
}
?>
</h1>

<ol class="breadcrumb">
<li class="breadcrumb-item">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_html_e( 'Home', 'knowing-god' ); ?>"><?php esc_html_e( 'Home', 'knowing-god' ); ?></a>
</li>
<li class="breadcrumb-item active">
<?php
if ( is_page_template() ) {
	the_title();
} elseif ( is_page() || is_single() || is_singular() ) {
	the_title();
} elseif ( is_tag() || is_category() || is_author() || is_archive() ) {
	the_archive_title();
	the_archive_description( '<div class="archive-description">', '</div>' );
} elseif ( is_search() ) {
	printf( esc_html__( 'Search Results for: %s', 'knowing-god' ), '<span>' . get_search_query() . '</span>' );
} else {
	if ( is_404() ) {
		esc_html_e( 'Page not found', 'knowing-god' );
	} else {
		esc_html_e( 'All Posts', 'knowing-god' );
	}
}
?>
</li>
</ol>