<?php
$cols = 3;
$current_layout = DB::table( 'lmsmode' )->first();
if ( ! $current_layout ) {
	$current_layout = 'bothsidebars';
} else {
	$current_layout = $current_layout->layout;
}
if ( 'bothsidebars' === $current_layout ) {
	$cols = 2;
}
$cols = 4;
$sidebar_widgets = '';
$widgets = Corcel\Model\Option::get( 'sidebars_widgets' );
if ( ! empty( $widgets['sidebar-1'] ) ) {
	$sidebar_widgets = $widgets['sidebar-1'];
}
$is_search = FALSE;
$is_recent_series = FALSE;
$is_recent_posts = FALSE;
$is_recent_articles = FALSE;
$is_recent_courses = FALSE;
$is_categories = FALSE;
$is_tag_cloud = FALSE;
if ( ! empty( $sidebar_widgets ) ) {
	foreach( $sidebar_widgets as $sidebar_widget ) {
		if (strpos($sidebar_widget, 'search') !== false) {
			$is_search = TRUE;
		}
		if (strpos($sidebar_widget, 'latestseries') !== false) {
			$is_recent_series = TRUE;
		}
		if (strpos($sidebar_widget, 'categories') !== false) {
			$is_categories = TRUE;
		}
		if (strpos($sidebar_widget, 'tag_cloud') !== false) {
			$is_tag_cloud = TRUE;
		}
	}
}
?>
<div class="col-md-{{$cols}}">
	<div class="sidebar-boxes" >
		<!-- Search Widget -->
		<div class="card mb-4 widget widget_search">
			<h4 class="card-header widget-title">{{ getPhrase('search') }}</h4>
			<div class="card-body">
				@include( 'layouts.student.search-form' )
			</div>
		</div>
		<!-- end search area-->
		<?php if ( $is_recent_series ) { series_widget(); } ?>
		
		<?php if ( $is_categories ) { categories_widget(); } ?>
		
		<?php if ( $is_tag_cloud ) { tags_cloud_widget(); } ?>		

		<?php if ( $is_recent_posts ) { knowing_god_recent_posts(); } ?>
		<?php if ( $is_recent_articles ) { knowing_god_recent_posts( 'recent_articles' ); } ?>
		<?php if ( $is_recent_courses ) { knowing_god_recent_courses(); } ?>

	</div>
</div>