@extends('layouts.full-width-no-menu')
@section('content')
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb ">
    @if( Auth::check() )
    <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
    @endif
    <li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('Categories')}}</a></li>
    @if ( ! empty( $category ) )
    <li class="breadcrumb-item"><a href="{{URL_FRONTEND_COURSE_LIST}}">{{$category->category}}</a></li>
    @endif
    <li class="breadcrumb-item"><strong class="text-green">{{$title}}</strong></li>
</ol>
<!-- Intro Content -->
<div class="row mt-2">
    <div class="col-sm-6 ">
        @if ( ! empty( $search ) )
        <h4>{{getPhrase('Search:')}} {{$search}}</h4>
        @else
        <h4><i class="fa fa-video-camera"></i> {{$title}}</h4>
        @endif
    </div>
    <div class="col-sm-6 rright">
        {!! Form::open(array('url' => URL_FRONTEND_RECOMMENDED_COURSES, 'method' => 'GET', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
        <label>
            <?php
            if ( empty( $search ) ) {
                $search = '';
            }
            ?>
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
    <?php
    $sl_no = 1;
    $content_image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
    $total = $contents->count();
    ?>
    @foreach($contents as $series)
        <div class="col-sm-6 col-md-4 mb-4">
            <div class="card modal-card-pins text-center">
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

    @if ( $contents->count() > 0 )
    <div class="row">
        <div class="col-sm-12">
            <div class="custom-pagination pull-right">
                {!! $contents->links() !!}
            </div>
        </div>
    </div>
    @endif
<?php $item_id = ''; ?>
    @include('lms-forntview.comments-modal')
    @include('lms-forntview.login-modal')
</div>
@stop
@section('footer_scripts')
    <?php /* ?>
    @include('common.validations')
    <?php */ ?>
    @include('lms-forntview.scripts.js-scripts')

    <script>
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });
    function getPosts(page) {
        var token = jQuery('#csrf').val();
        var data= {_method: 'post', '_token':token, action: 'fetch_recommended' };
        $.ajax({
            headers: {
                      'X-CSRF-TOKEN': token
            },
            url : '{{URL_FRONTEND_GET_DATA}}?page=' + page,
            token: token,
            data: data,
            type : 'post',
            dataType: 'json'
        }).done(function (data) {
            // $('.posts').html(data);
            $('#coursesList').html(data.html);
            location.hash = page;
        }).fail(function () {
            alert('Posts could not be loaded.');
        });
    }
    </script>
@stop
