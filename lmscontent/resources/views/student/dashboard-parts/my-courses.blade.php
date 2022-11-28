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
/*
$my_courses_profile = App\MyCourses::select(['lmsseries.*', 'subjects.subject_title', 'subjects.color_class'])
        ->join( 'lmsseries', 'lmsseries.id', '=', 'users_my_courses.course_id' )
        ->join( 'subjects', 'subjects.id', '=', 'lmsseries.subject_id' )
        ->where('user_id', '=', Auth::User()->id )
        ->where('lmsseries.status', '=', 'active' )
        ->where('lmsseries.parent_id', '=', '0' )
        ->limit( $cards )
        ->get();
        */
$my_courses_profile = attempted_courses_new( 'records',
    array(
        'limit_records' => $cards,
        'order_by' => array(
            'column' => 'users_my_courses.created_at',
            'order' => 'asc'
        ),
        'exclude_completed' => TRUE,
    )
);

$my_courses_profile_series = my_courses_profile_series();
// dd( $my_courses_profile_series );
$my_courses_profile = $my_courses_profile->merge( $my_courses_profile_series );

?>
<div class="card r-card">
    <div class="card-header">
        <h4 class="mb-0">{{getPhrase('my_courses')}}</h4>
    </div>
    <div class="card-body">
        <ul class="list-inline">
        @if ( $my_courses_profile->count() > 0 )

            @foreach( $my_courses_profile as $courses_profile )
                <li>
				<?php
                  $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
                  if($courses_profile->image) {
                    $image_path = IMAGE_PATH_UPLOAD_LMS_SERIES.$courses_profile->image;
                  }
				  if ( isset( $courses_profile->series_image ) && ! empty( $courses_profile->series_image ) ) {
					  $image_path = $courses_profile->series_image;
				  }
				  $url = URL_FRONTEND_LMSSERIES . $courses_profile->slug;
				  if ( isset( $courses_profile->series_image ) ) {
					  $url = HOST . 'series/' . $courses_profile->slug;
				  }                  
                ?>
                <a href="{{$url}}">
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
		
		<!-- Let us display Serieses -->

        <li>
            <div class="media">
                <a href="{{URL_STUDENT_MY_COURSES}}" title="{{getPhrase('my_courses')}}" class="course-plus-btn">
                @if ( $my_courses_profile->count() == 0 )
                <div class="join-btn">
                    <i class="icon icon-plus"></i>
                </div>
                @else
                <div class="join-btn join-btn-md">
                    <i class="icon icon-plus"></i>
                </div>
                @endif
                </a>
                @if ( $my_courses_profile->count() == 0 )
                <div class="media-body vertical-align">
                    <p class="course-text">{{getPhrase('Learn about discipleship, Jesus and much more. Join a course today!')}}</p>
                </div>
                @endif
            </div>
        </li>
    </ul>
    </div>
</div>
