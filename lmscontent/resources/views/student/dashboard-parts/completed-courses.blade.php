<?php
$cards = 5;
$current_layout = DB::table( 'lmsmode' )->first();
if ( ! $current_layout ) {
    $current_layout = 'bothsidebars';
} else {
    $current_layout = $current_layout->layout;
}
if ( 'bothsidebars' === $current_layout ) {
    $cards = 2;
} elseif ( in_array( $current_layout, array( 'leftsidebar', 'rightsidebar' )) ) {
    $cards = 3;
}
$completed_courses_profile = completed_courses( '', '', 'records' );
$completed_serieses = completed_serieses( '', '', 'records' );

if ( ! empty( $completed_serieses ) ) {
	foreach( $completed_serieses as $series ) {
		$check = DB::table( TBL_WP_TERMMETA )->where('term_id', '=', $series->term_id )->where('meta_key', '=', 'series_image_loc')->first();
		if ( $check ) {
			$series->series_image = $check->meta_value;
		}
	}
}
// dd( $completed_courses_profile );
if ( ! empty( $completed_serieses ) && ! empty( $completed_courses_profile ) ) {
	// $completed_courses_profile = $completed_courses_profile->merge( $completed_serieses );
}
// die('gggggggggggg');
if ( empty( $completed_courses_profile ) ) {
	$completed_courses_profile = $completed_serieses;
}

if ( ! empty( $completed_courses_profile ) ) : ?>
<div class="card r-card">
    <div class="card-header">
        <h4 class="mb-0">{{getPhrase('completed_courses')}}</h4>
    </div>
    <div class="card-body">
        <ul class="list-inline">
        @if ( ! empty( $completed_courses_profile ) && $completed_courses_profile->count() > 0 )
            @foreach( $completed_courses_profile as $courses_profile )
                <li>
                    <?php
					$image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
					if( ! empty( $courses_profile->image ) ) {
						$image_path = IMAGE_PATH_UPLOAD_LMS_SERIES.$courses_profile->image;
					}					
					$url = URL_FRONTEND_LMSSERIES . $courses_profile->slug;
					if( ! empty( $courses_profile->series_image ) ) {
						$image_path = $courses_profile->series_image;
						$url = HOST . 'series/' . $courses_profile->slug;
					}
					?>
					<a href="{{$url}}" title="{{$courses_profile->title}}">
					<div class="course-card">
                        <div class="course-card-img">
                        
                        <img src="{{$image_path}}" alt="" class="img-responsive"></div>
                        <div class="course-card-content">
                            <p>{{$courses_profile->title}}</p>
                            @if ( ! empty( $courses_profile->sub_title ) )
                            <p>{{$courses_profile->sub_title}}</p>
                            @endif
                        </div>
                    </div>
					</a>
                </li>
            @endforeach
        @endif
        </ul>
    </div>
</div>
<?php endif; ?>