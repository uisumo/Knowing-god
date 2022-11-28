@extends('layouts.student.studentlayout')

@section('custom_div')
 <div ng-controller="singleLessonCtrl">
 @stop

@section('content')
<?php
$breadcrumb = array(
    'category' => $category,
);
if ( ! empty( $series ) ) {
    $breadcrumb['series'] = $series;
}
if ( ! empty( $course ) ) {
    $breadcrumb['course'] = $course;
}
if ( ! empty( $title ) ) {
    $breadcrumb['title'] = $title;
}
?>
@include('lms-forntview.newviews.breadcrumb', $breadcrumb )
<?php /* ?>
<!-- Intro Content -->
<div class="mb-4">
<h2>{{$title}}</h2>
</div>
<?php */ ?>

<div class="row">

    @if( $courses->count() > 0 )

        @foreach( $courses as $course )
        <?php
        $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-green';
        $see_more_class = 'btn-green';
        if ( $course->color_class == 'text-blue' ) {
            $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-blue';
            $see_more_class = 'btn-blue';
        } elseif ( $course->color_class == 'text-yellow' ) {
            $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-yellow';
            $see_more_class = 'btn-yellow';
        }
        ?>
        <div class="col-sm-6">
            <div class="white-card cs-card mb-4">
                <div class="flow-hidden relative p-3">
                    <div class="{{$ribbon_class}}">{{$course->subject_title}}</div>
                    <div class="row">
                        <div class="col-sm-6 cpr-0">
                            @if($course->image!='')
                            <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$course->image}}" alt="">
                            @else
                            <img class="img-fluid rounded" src="http://placehold.it/750x450" alt="">
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <h5 class="text-green course-card-tile">{{$course->title}}</h5>
                            @if( ! empty( $course->short_description ) )
                            <p class="course-card-text">{!!$course->short_description!!}</p>
                            @endif
                            <?php
                            $url = URL_FRONTEND_LMSLESSON . $course->slug;
                            $modules = App\LmsSeries::where( 'parent_id', '=', $course->id )->count();
                            if ( $modules > 0 ) {
                                $url = URL_FRONTEND_LMSSERIES . $course->slug;
                            }
                            $url = URL_FRONTEND_LMSSERIES . $course->slug;
                            ?>
                            <div class="mt-2"><a href="{{$url}}" class="btn btn-kg btn-course btn-round {{$see_more_class}}">{{getPhrase('see_more')}}</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-sm-6">
            <div class="add-course-card cs-card mb-4">
                @if(Auth::check())
                <!-- <a href="#" class="text-center" ng-click="show_recommended()"> -->
                <a href="{{URL_FRONTEND_RECOMMENDED_COURSES}}" class="text-center">
                @else
                <a href="#" class="text-center" ng-click="open_login_modal('{{base64_encode(URL_FRONTEND_RECOMMENDED_COURSES . '/' . $item->slug)}}')">
                @endif
                    <div class="join-btn cs-center">
                      <i class="icon icon-plus"></i>
                    </div>
                    <h6 class="mt-1">{{getPhrase('Add a Course')}}</h6>
                </a>
            </div>
        </div>
    @else
        Ooops...! {{getPhrase('No_courses_available')}}
    @endif

</div>

<div class="row">
<div class="mb-4">
<h2>{{getPhrase('Recommended for you')}}</h2>
</div>

@include( 'lms-forntview.recommended-courses-part' )
</div>

@include('lms-forntview.login-modal')

@stop


@section('footer_scripts')
    @include('common.validations')
    @include('lms-forntview.scripts.js-scripts')
@stop

@section('custom_div_end')
 </div>
@stop
