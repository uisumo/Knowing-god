<div class="row">
<?php
$attempted_courses = attempted_courses_new();
// dd( $attempted_courses );
if ( ! empty( $recommended_courses ) ) {
    // If same course is in different subjects! Let us skip that course.
    foreach( $recommended_courses as $rc ) {
        array_push( $attempted_courses, $rc );
    }
}
if ( ! empty( $attempted_courses ) ) {
$recommended = DB::table('lmsseries AS ls')
        ->select(['ls.*', 'qc.slug AS catslug', 's.subject_title', 's.color_class'])
        ->join( 'subjects AS s', 's.id', '=', 'ls.subject_id' )
        ->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
        ->where( 'ls.parent_id', '=', '0' )
        ->where( 'ls.status', '=', 'active' )
        ->where( 'qc.category_status', '=', 'active' )
        ->where( 'ls.course_type', '=', 'regular' )
        ->whereNotIn( 'ls.id', $attempted_courses )
        ->orderBy('s.id', 'asc')
        ->orderBy( 'ls.display_order', 'asc' )
        ->orderBy( 'ls.updated_at', 'desc' )->limit(3)->get();
} else {
    $recommended = DB::table('lmsseries AS ls')
            ->select(['ls.*', 'qc.slug AS catslug', 's.subject_title', 's.color_class'])
            ->join( 'subjects AS s', 's.id', '=', 'ls.subject_id' )
            ->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
            ->orderBy('s.id', 'asc')
            ->where( 'ls.parent_id', '=', '0' )
            ->where( 'ls.status', '=', 'active' )
            ->where( 'qc.category_status', '=', 'active' )
            // ->where( 'ls.course_type', '=', 'regular' )
            ->orderBy('s.id', 'asc')
            ->orderBy( 'ls.display_order', 'asc' )
            ->orderBy( 'ls.updated_at', 'desc' );
			
    $recommended = $recommended->limit(3)->get();
}
// dd($recommended->toSql());
?>
    @if( ! empty( $recommended ) && $recommended->count() > 0 )
    <?php $recommended_courses = array(); ?>
    @foreach( $recommended as $recommended_course )
    <?php
    $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-green';
    $see_more_class = 'btn-green';
    if ( $recommended_course->color_class == 'text-blue' ) {
        $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-blue';
        $see_more_class = 'btn-blue';
    } elseif ( $recommended_course->color_class == 'text-yellow' ) {
        $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-yellow';
        $see_more_class = 'btn-yellow';
    }
    ?>
    @if ( $recommended )
        <?php
    array_push( $recommended_courses, $recommended_course->id );
    ?>
    <div class="col-lg-6">
        <div class="white-card cs-card mb-4">
            <div class="flow-hidden relative p-3">
                <div class="{{$ribbon_class}}">{{$recommended_course->subject_title}}</div>
                <div class="row category-cards-row ">
                    <div class="col-lg-6 category-cards-col cpr-0">
                        @if($recommended_course->image!='')
                        <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$recommended_course->image}}" alt="">
                        @else
                        <img class="img-fluid rounded" src="http://placehold.it/750x450" alt="">
                        @endif
                    </div>
                    <div class="col-lg-6 category-cards-col">
                        <?php
                        $target = URL_FRONTEND_LMSLESSON . $recommended_course->slug;
                        $modules = App\LmsSeries::where( 'parent_id', '=', $recommended_course->id );
                        if ( $modules->count() > 0 ) {
                            $target = URL_FRONTEND_LMSSERIES . $recommended_course->slug;
                        }
                        $target = URL_FRONTEND_LMSSERIES . $recommended_course->slug;
                        ?>
                        <h5 class="text-green course-card-tile">
                            @if ( $recommended_course->privacy == 'loginrequired' && ! Auth::check() )
                                <a href="#" ng-click="open_login_modal('{{base64_encode($target)}}')">{{$recommended_course->title}}</a>
                            @else
                                <a href="{{$target}}" title="{{$recommended_course->title}}">{{$recommended_course->title}}</a>
                            @endif
                        </h5>
                        @if( ! empty( $recommended_course->short_description ) )
                        <p class="course-card-text">{!!$recommended_course->short_description!!}</p>
                        @endif

                        <div class="mt-2">
                        @if ( $recommended_course->privacy == 'loginrequired' && ! Auth::check() )
                            <a href="#" class="btn btn-kg btn-course btn-round {{$see_more_class}}" ng-click="open_login_modal()">
							@if($recommended_course->is_paid == 1 && $recommended_course->cost > 0 )
								{{getCurrencyCode() . ' ' . $recommended_course->cost . ' '}}
							@endif
							{{getPhrase('Start Course')}}&nbsp;<i class="fa fa-sign-in" aria-hidden="true"></i></a>
                        @else
                        @if( ! Auth::check() )
                            <a href="{{$target}}" class="btn btn-kg btn-course btn-round {{$see_more_class}}">
							@if($recommended_course->is_paid == 1 && $recommended_course->cost > 0 )
								{{getCurrencyCode() . ' ' . $recommended_course->cost . ' '}}
							@endif
							{{getPhrase('Start Course')}}</a>
                        @else
                            @if ( $recommended_course->is_paid == 1 && $recommended_course->cost > 0 )
								<?php
								$target = URL_FRONTEND_LMSSERIES . $recommended_course->slug;
								?>
								<a href="{{$target}}" class="btn btn-kg btn-course-start btn-round {{$see_more_class}}">
								@if($recommended_course->is_paid == 1 && $recommended_course->cost > 0 )
									{{getCurrencyCode() . ' ' . $recommended_course->cost . ' '}}
								@endif
								{{getPhrase('Start Course')}}</a>
							@else
							<a href="#startcourseModal" class="btn btn-kg btn-course-start btn-round {{$see_more_class}}" data-toggle="modal" data-slug="{{$recommended_course->slug}}">{{getPhrase('Start Course')}}</a>
							@endif
                        @endif

                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    <?php
    $summary = categories_courses_completed_summary();
    if ( $summary['courses']['count'] > $summary['courses']['my_courses'] ) :
    ?>
    <div class="col-lg-6">
        <div class="add-course-card cs-card mb-4">
            @if(Auth::check())
            <a href="javascript:void(0);" class="text-center" ng-click="show_recommended()">
           <!-- <a href="{{URL_FRONTEND_RECOMMENDED_COURSES}}" class="text-center">
            <a href="#" class="text-center" ng-click="showAllCourses()">-->
            @else
            <a href="javascript:void(0);" class="text-center" ng-click="open_login_modal('{{base64_encode(URL_FRONTEND_LMSCATEGORIES)}}')">
            @endif
                <div class="join-btn cs-center">
                  <i class="icon icon-plus"></i>
                </div>
                <h6 class="mt-1">{{getPhrase('Add a Different Course')}}</h6>
            </a>
        </div>
    </div>
    <?php endif; ?>
    @else
    <div class="col-sm-12 text-center">
       <div class="oops-msg">  Ooops...! {{getPhrase('no_courses_available')}}</div>
    </div>
    @endif
</div>
@include('lms-forntview.startcourse-modal')
