@extends('layouts.student.studentlayout')
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@section('custom_div')
 <div ng-controller="singleLessonCtrl">
 @stop

@section('content')
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-2">
    @if( Auth::check() )
    <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
    @endif
    <li class="breadcrumb-item"> <strong class="text-green"><?php echo getPhrase( 'Pathway Courses' ); ?></strong> </li>
</ol>

<!-- Intro Content -->
<div class="mb-4">
@if(Auth::check())
<a href="{{URL_STUDENT_MY_COURSES}}" class="pull-right btn btn-round btn-gray">
@else
<a href="#" class="pull-right btn btn-round btn-gray" ng-click="open_login_modal('{{base64_encode(URL_STUDENT_MY_COURSES)}}')">
@endif
{{getPhrase('My Courses')}} <i class="fa fa-play" aria-hidden="true"></i>
</a>
<h2><i>{{getPhrase('Recommend for you')}}</i></h2>
</div>

@include( 'lms-forntview.recommended-courses-part' )
<!-- /.row -->

<h2 class="mt-4 mb-4"><i><?php echo getPhrase( 'course_categories' ); ?></i></h2>
<!-- Intro Content -->

<div class="row">
    @foreach($categories as $category)
    <div class="col-lg-6">
        <div class="white-card cs-card mb-4">
            <div class="flow-hidden relative p-3">
                <div class="row category-cards-row">
                    <div class="col-md-6  category-cards-col cpr-0">
                        <?php
                        $settings = getExamSettings();
                        $path = $settings->categoryImagepath;
                        $image = $path.$settings->defaultCategoryImage;
                        if($category->image!= ''){
                            $image = $path.$category->image;
                        }
                        ?>
                        <!--<a href="{{URL_FRONTEND_LMSSERIES.$category->slug}}" title="{{$category->category}}">-->
                        <a href="{{URL_FRONTEND_COURSE_LIST.$category->slug}}" title="{{$category->category}}">


                        <img class="img-fluid rounded" src="{{PREFIX . $image}}" alt="{{$category->category}}"></a>
                    </div>
                    <div class="col-md-6 category-cards-col ">
                        <h5 class="course-card-tile">{{$category->category}}</h5>
                        <p class="course-card-text">{!!$category->description!!} </p>
                        <div class="mt-2">
                        @if( lmsmode() == 'series' )
                            <button class="btn btn-kg btn-course btn-round btn-gray" ng-click="showSerieses('{{$category->slug}}')"><?php echo getPhrase( 'see_list' ); ?></button>
                        @else
                            <button class="btn btn-kg btn-course btn-round btn-gray" ng-click="showCourses('{{$category->slug}}')"><?php echo getPhrase( 'see_list' ); ?></button>
                        @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if(count($categories))
<div class="row">
    <div class="col-sm-12">
        <div class="custom-pagination pull-right">
            {!! $categories->links() !!}
        </div>
    </div>
</div>
@else
    <div class="row">
        <div class="col-sm-12">{{getPhrase('no_categories_available')}}</div>
    </div>
@endif
<!-- .row -->
@include('auth.forgot-password-modal')
@include('lms-forntview.login-modal')

@if ( lmsmode() == 'series' )
    @include('lms-forntview.show-serieses-modal')
@else
    @include('lms-forntview.category-courses-modal')
@endif
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
