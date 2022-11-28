@extends('layouts.student.studentlayout')
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@section('custom_div')
 <div ng-controller="singleLessonCtrl">
 @stop

@section('content')
<h2 class="mt-0">{{$title}}</h2>
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-2">
    @if( Auth::check() )
    <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
    @endif
    <li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}"><?php echo getPhrase( 'Categories' ); ?> <?php if ( ! empty( $record ) ) '('.$record->title.')'; ?></a></li>
    <li class="breadcrumb-item"> <strong class="text-green">{{$title}}</strong> </li>
</ol>

<div class="row">

    @if( $courses->count() > 0 )

        @foreach( $courses->get() as $course )
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
        <div class="col-lg-6">
            <div class="white-card cs-card mb-4">
                <div class="flow-hidden relative p-3">
                    <div class="{{$ribbon_class}}">{{$course->subject_title}}</div>
                    <div class="row category-cards-row">
                        <div class="col-md-6 category-cards-col cpr-0">
                            @if($course->image!='')
                            <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$course->image}}" alt="">
                            @else
                            <img class="img-fluid rounded" src="http://placehold.it/750x450" alt="">
                            @endif
                        </div>
                        <div class="col-md-6 category-cards-col">
                            <h5 class="text-green course-card-tile">{{$course->title}}</h5>
                            @if( ! empty( $course->short_description ) )
                            <p class="course-card-text">{!!$course->short_description!!}</p>
                            @endif
                            <div class="mt-2">
                            @if ( $course->privacy == 'loginrequired' && ! Auth::check() )
                                <a href="#" class="btn btn-kg btn-course btn-round {{$see_more_class}}" ng-click="open_login_modal('{{base64_encode(URL_FRONTEND_LMSSERIES . $course->slug)}}')">
								@if($course->is_paid == 1 && $course->cost > 0 )
									@if ( ! isItemPurchased( $course->id, 'lms' ) )
										{{getCurrencyCode() . ' ' . $course->cost . ' '}}
									@endif
								@endif
								{{getPhrase('see_more')}}&nbsp;<i class="fa fa-lock" aria-hidden="true"></i></a>
                            @else
                            <a href="{{URL_FRONTEND_LMSSERIES . $course->slug}}" class="btn btn-kg btn-course btn-round {{$see_more_class}}">
							@if($course->is_paid == 1 && $course->cost > 0 )
								@if ( ! isItemPurchased( $course->id, 'lms' ) )
									{{getCurrencyCode() . ' ' . $course->cost . ' '}}
								@endif
							@endif
							{{getPhrase('see_more')}}</a>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-lg-6">
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
            <div class="col-sm-12 text-center"><div class="oops-msg">Ooops...! {{getPhrase('No_courses_available')}}</div></div>
    @endif

</div>

<div class="row">
    <div class="col-sm-12">
<div class="mb-4">
<h2>{{getPhrase('Recommended for you')}}</h2>
</div>


@include( 'lms-forntview.recommended-courses-part' )
    </div>
</div>

@include('lms-forntview.login-modal')

@stop


@section('footer_scripts')
    @include('common.validations')
    @include('lms-forntview.scripts.js-scripts')

    <script src="{{JS}}select2.js"></script>

    <script>
      $('.select2').select2({
       placeholder: "Add User",
    });
    </script>
@stop

@section('custom_div_end')
 </div>
@stop
