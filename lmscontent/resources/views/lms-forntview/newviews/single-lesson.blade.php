@extends('layouts.student.studentlayout')

@section('custom_div')
 <div ng-controller="singleLessonCtrl">
 @stop

@section('content')

<h2 class="mt-4">{{$item->title}}
@if( ! empty( $item->sub_title ) )<small>| {{$item->sub_title}}</small>@endif
</h2>
<?php
$url = URL_STUDENT_LMS_SERIES_VIEW.$item->slug.'/'.$item->slug;
if ( $item->content_type == 'audio_url' ) {
    $url = $item->file_path;
}
?>
@include('lms-forntview.newviews.breadcrumb', array('category' => $category, 'series' => $series, 'course' => $course, 'title' => $title))

<?php
$pieces = App\LmsSeries::getPieces( $parent_item->id );
$total_pieces = $pieces->count();
$total_pieces++; // Parent Content added
if ( $total_pieces > 1 ) {
    $pieces_array = array();
    array_push( $pieces_array, $parent_item->slug ); // Parent Slug always at first place!!. If there is more than one piece the '$item' and '$parent_item' different
    foreach( $pieces as $piece ) {
        array_push( $pieces_array, $piece->slug );
    }

    $current_index = array_search( $item->slug, $pieces_array );
    $previous_index = $current_index - 1;
    $next_index = $current_index + 1;

    $previous_piece = $next_piece = '';
    if ( $current_index > 0 ) {
        if ( $previous_index == 0 ) {
            $previous_piece = URL_FRONTEND_LMSSINGLELESSON . $course->slug . '/' . $parent_item->slug;
        } else {
            $previous_piece = URL_FRONTEND_LMSSINGLELESSON . $course->slug . '/' . $item->slug . '/' . $pieces_array[ $previous_index ];
        }
    }

    if ( $next_index != $total_pieces ) {
        $next_piece = URL_FRONTEND_LMSSINGLELESSON . $course->slug . '/' . $item->slug . '/' . $pieces_array[ $next_index ];
    }
?>
<ol class="breadcrumb mt-3">
<?php if ( ! empty( $previous_piece ) ) { // Which means this is first piece in the lesson ?>
<a href="{{$previous_piece}}">
<i class="fa fa-arrow-left text-green">{{$current_index+1}} / {{$total_pieces}}</i>
</a>
<?php } ?>

<?php

if ( ! empty( $next_piece ) ) { // which means piece is last in the lesson ?>
<a href="{{$next_piece}}">
<i class="fa fa-arrow-right text-green align-right">{{$current_index+1}} / {{$total_pieces}}</i>
</a>
<?php } ?>
</ol>
<?php
}
// echo '<pre>';
// print_r( $item );
?>
<!-- Intro Content -->
@if ( ! empty( $item->file_path_video ) )
<div class="row mt-4">
    <div class="col-sm-12">
            <div class="video-container">
                <?php
                $icon_class = 'icon icon-tick-double';
                $is_completed = FALSE;
                if ( Auth::check() ) {
                    $getstatus = App\LmsTrack::getStatus( $item->id, 'video' );
                    if ( ! empty( $getstatus ) && $getstatus->status == 'completed' ) {
                        $icon_class = 'icon icon-tick-border'; // If it is completed
                        $is_completed = TRUE;
                    }
                }
                ?>
                @if( $is_completed )
                    <button class="fixed-top-left task-btn">
                @else
                    <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$item->id}}', 'video', '{{$course->id}}')">
                @endif
                <i class="{{$icon_class}}" id="video_icon"></i></button>

                <div class="fixed-top-right ">
                    <?php
                    $icon_class = 'lesson-pin icon icon-map-pointer';
                    if ( Auth::check() ) {
                        if ( is_completed( $item->id ) ) {
                            $icon_class = 'lesson-pin icon icon-pointer-border';
                        }
                    }

                    ?>
                    <i class="{{$icon_class}}" id="overall_status"></i>
                    @if( ! empty( $item->help_text ) )
                    <span class="lesson-pin-info"  data-container="body" data-toggle="popover" data-placement="right" data-content="{{$item->help_text}}">
                        ?
                    </span>
                    @endif
                </div>

                <?php
                $video_background_image = IMAGES . '900x400.png';
                if ( ! empty( $item->video_background_image ) && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $item->video_background_image ) ) {
                    $video_background_image = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $item->video_background_image;
                }
                ?>
                <img src="{{$video_background_image}}" alt="" class="img-fluid">
                <?php
                $url = $item->file_path_video;
                if ( $item->video_type == 'video' ) {
                    $url = IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $item->file_path_video;
                }
                ?>
                <a data-fancybox="" href="{{$url}}" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen">
                    <div class="video-play-icn"><i class="fa fa-play"></i></div>
                </a>
            </div>
    </div>
</div>
@endif

@if( ! empty( $item->description ) )
<div class="row">
    <div class="col-sm-12">
        <div class="text-container mt-4">
            <?php
            $icon_class = 'icon icon-tick-double';
            $is_completed = FALSE;
            if ( Auth::check() ) {
                $getstatus = App\LmsTrack::getStatus( $item->id, 'text' );
                if ( ! empty( $getstatus ) && $getstatus->status == 'completed' ) {
                    $icon_class = 'icon icon-tick-border'; // If it is completed
                    $is_completed = TRUE;
                }
            }
            ?>
            @if( $is_completed )
                <button class="fixed-top-left task-btn">
            @else
                <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$item->id}}', 'text', '{{$course->id}}')">
            @endif
            <i class="{{$icon_class}}" id="text_icon"></i></button>
            <h2>{!! $item->description !!}</h2>
        </div>
    </div>
</div>
@endif

@if( $item->quiz_id > 0 )
<div class="row mt-5">
    <div class="col-sm-12">
        <div class="quiz-container text-center">
            <div class="take-quiz-btn">
                <?php
                $is_completed = FALSE;
                $icon_class = 'icon icon-tick-double';
                if ( Auth::check() ) {
                    $getstatus = App\LmsTrack::getStatus( $item->id, 'quiz' );
                    if ( ! empty( $getstatus ) && $getstatus->status == 'completed' ) {
                        $icon_class = 'icon icon-tick-border'; // If it is completed
                        $is_completed = TRUE;
                    }
                }
                ?>
                @if( $is_completed )
                    <button class="fixed-top-left task-btn">
                @else
                    <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$item->id}}', 'quiz', '{{$course->id}}')">
                @endif
                <i class="{{$icon_class}}" id="quiz_icon"></i></button>
                @if ( Auth::check() )
                    <button class="btn btn-primary-outline btn-min-width btn-top-icn" data-toggle="modal" data-target="#quizModal" ng-click="start_exam({{$item->quiz_id}})">{{getPhrase('Lesson Quiz')}}</button>
                @else
                    <button class="btn btn-primary-outline btn-min-width btn-top-icn" ng-click="mark_as_complete('{{$item->id}}', 'quiz', '{{$course->id}}')">{{getPhrase('Lesson Quiz')}}</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="row justify-content-between mt-5 mb-8">
    <div class="col-sm-5">
        <button class="btn btn-primary-outline btn-min-width btn-block askforlogin" data-toggle="modal" data-target="#commentsModal" ng-click="getData({{$item->id}}, 'comments')">{{getPhrase('Add Comments')}} <i class="fa fa-comments-o"></i></button>
        <?php $item_id = $item->id; ?>
        @include('lms-forntview.comments-modal')
    </div>
    <div class="col-sm-5">
        @if ( Auth::check() )
        <button class="btn btn-primary-outline btn-min-width btn-block" data-toggle="modal" data-target="#notesModal" ng-click="getData({{$item->id}}, 'notes')">{{getPhrase('Take Notes')}}  <i class="fa fa-pencil-square"></i></button>
        @else
        <button class="btn btn-primary-outline btn-min-width btn-block" ng-click="mark_as_complete('{{$item->id}}', 'quiz', '{{$course->id}}')">{{getPhrase('Take Notes')}} <i class="fa fa-pencil-square"></i></button>
        @endif
        <!-- Modal -->
        <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{getPhrase('Enter your notes here')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! Form::open(array('url' => '', 'method' => 'POST', 'novalidate'=>'','name'=>'formComments')) !!}
                    <div class="modal-body">
                        {{ Form::textarea('notes', $value = null , $attributes = array('class'=>'form-control', 'ng-model' => 'notes', 'id' => 'notes', 'rows'=>'5', 'placeholder' => getPhrase('Enter your notes here'))) }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{getPhrase('Close')}}</button>
                        <button type="button" class="btn btn-primary" ng-click="saveNotes({{$item->id}}, {{$course->id}})">{{getPhrase('Save notes')}}</button>
                    </div>
                    {!! Form::close() !!}
                    <div id="notes_list"></div>
                </div>
            </div>
        </div>
        <!-- /Modal -->
    </div>
</div>
@include('lms-forntview.login-modal')
@include('auth.forgot-password-modal')
@if( $item->quiz_id > 0 )
    @include('lms-forntview.quiz-modal')
@endif
@stop
@section('footer_scripts')
    @include('common.validations')
     @include('lms-forntview.scripts.js-scripts')
@stop
@section('custom_div_end')
 </div>
@stop
