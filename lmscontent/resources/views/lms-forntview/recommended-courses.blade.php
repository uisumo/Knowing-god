@extends('layouts.student.studentlayout')
@section('content')
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb ">
    @if( Auth::check() )
    <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
    @endif
    <li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('Categories')}}</a></li>
    @if ( $category )
    <li class="breadcrumb-item"><a href="{{URL_FRONTEND_COURSE_LIST}}">{{$category->category}}</a></li>
    @endif
    <li class="breadcrumb-item"><strong class="text-green">{{$title}}</strong></li>
</ol>
<!-- Intro Content -->
<div class="row mt-2">
    <div class="col-sm-6">
        @if ( ! empty( $search ) )
        <h4>{{getPhrase('Search:')}} {{$search}}</h4>
        @else
        <h4><i class="fa fa-video-camera"></i> {{$title}}</h4>
        @endif
    </div>
    <div class="col-sm-6 rright">
        {!! Form::open(array('url' => URL_FRONTEND_RECOMMENDED_COURSES, 'method' => 'GET', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
        <label>
            <input type="search" class="search-field" placeholder="Search Courses" value="{{$search}}" name="s">
        </label>
        <input type="submit" class="search-submit" value="Search">
        @if ( ! empty( $search ) )
        &nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn-reset" href="{{URL_FRONTEND_RECOMMENDED_COURSES}}">{{getPhrase('clear search')}}</a>
        @endif
        {!! Form::close() !!}
    </div>
</div>

<div class="row mt-4" ng-controller="singleLessonCtrl">
    @if ( $contents->count() > 0)
    @foreach($contents as $series)
        <div class="col-sm-6 col-md-4 mb-4">
            <div class="card h-100 text-center">
                 <?php
                $icon_class = 'fa fa-heart-o';
                // If it is completed class will be 'icon icon-tick-border'
                $is_my_course = mycours( $series->id );
                if ( $is_my_course->count() > 0 ) {
                    $icon_class = 'fa fa-heart';
                }
                ?>
                <?php
                // dd( $series );
                $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-green';
                $see_more_class = 'btn-green';
                if ( $series->color_class == 'text-blue' ) {
                    $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-blue';
                    $see_more_class = 'btn-blue';
                } elseif ( $series->color_class == 'text-yellow' ) {
                    $ribbon_class = 'corner-ribbon corner-ribbon-small left btn-yellow';
                    $see_more_class = 'btn-yellow';
                }
                ?>
                <button class="fixed-top-left task-btn" ng-click="make_my_course('{{$series->id}}')" title="{{getPhrase('Add this course to your courses list')}}">
                <i class="{{$icon_class}}" id="my_course_icon_{{$series->id}}"></i></button>

                @if($series->image!='')
                <img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$series->image}}" alt="">
                @else
                <img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_DEFAULT}}" alt="">
                @endif
                <div class="card-body">
                    <h4 class="card-title">
                        <?php
                        $target = URL_FRONTEND_LMSLESSON . $series->slug;
                        $modules = App\LmsSeries::where( 'parent_id', '=', $series->id );
                        if ( $modules->count() > 0 ) {
                            $target = URL_FRONTEND_LMSSERIES . $series->slug;
                        }

                        $target = URL_FRONTEND_LMSSERIES . $series->slug;
                        ?>
                        @if ( $series->privacy == 'loginrequired' && ! Auth::check() )
                            <a href="#" class="text-green" ng-click="open_login_modal('{{base64_encode($target)}}')">{{getPhrase('Start Course')}}&nbsp;<i class="fa fa-sign-in" aria-hidden="true"></i></a>
                        @else
                            <a href="{{$target}}" class="text-green">{{$series->title}}</a>
                        @endif
                        <?php /* ?>
                        <a class="text-green" href="{{$target}}">{{$series->title}}</a>
                        <?php */ ?>
                        <?php
                        /* ?>
                        <a class="text-green" data-toggle="modal" data-target="#lessonsModal" ng-click="fetch_lessons('{{$series->slug}}')">{{$series->title}}</a><?php */ ?>
                    </h4>
                    <?php if ( ! empty( $series->sub_title ) ) : ?>
                    <h6 class="card-subtitle mb-2 text-green">{{$series->sub_title}}</h6>
                    <?php endif; ?>
                    <p class="card-text">{!! $series->short_description !!}</p>
                </div>
                <?php
                if ( $modules->count() > 0 ) { ?>
                <div class="card-footer">
                    <ul class="course-finished-path">
                        <?php
                        $total_modules = $modules->count();
                        $completed = 0;
                        foreach( $modules->get() as $module ) :
                        $class = '';

                        if ( is_module_completed( $module->id ) ) {
                            $class = 'completed';
                            $completed++;
                        }
                        ?>
                        <li class="{{$class}}"><i class="icon icon-pointer-white" title="{{$module->title}}"></i></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php
                } else {
                    $lessons = App\LmsSeries::getAllParentLessons( $series->id );

                    if ( $lessons->count() > 0 ) {
                        $total_contents = $lessons->count();
                        $completed = 0;
                    ?>
                    <div class="card-footer">
                        <ul class="course-finished-path">
                            <?php
                            foreach( $lessons as $lesson ) :
                            $class = '';
                            if ( is_completed( $lesson->id ) ) {
                                $class = 'completed';
                                $completed++;
                            }
                            ?>
                            <li class="{{$class}}"><i class="icon icon-pointer-white" title="{{$lesson->title}}"></i></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php }
                }
                ?>
            </div>
        </div>
    @endforeach
    @else
    <div class="col-sm-12">
    {{ getPhrase( 'There are no other courses available for now' )}}
</div>
    @endif
</div>
    @if(count($contents))
    <div class="row">
        <div class="col-sm-12">
        <div class="custom-pagination">
            {!! $contents->links() !!}
        </div>
        </div>
    </div>
    @endif
<?php $item_id = ''; ?>
    @include('lms-forntview.comments-modal')
    @include('lms-forntview.login-modal')
@stop
@section('footer_scripts')
    @include('common.validations')
    @include('lms-forntview.scripts.js-scripts')
@stop
