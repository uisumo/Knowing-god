@extends('layouts.student.studentlayout')

@section('custom_div')
 <div ng-controller="singleLessonCtrl">
 @stop

@section('content')

<h2 class="mt-0">{{$display_item->title}}
@if( ! empty( $display_item->sub_title ) )<small>| {{$display_item->sub_title}}</small>@endif
</h2>
<?php
$url = URL_STUDENT_LMS_SERIES_VIEW.$item->slug.'/'.$item->slug;
if ( $item->content_type == 'audio_url' ) {
    $url = $item->file_path;
}

$lesson_id = $display_item->id;
$course_id = 0;
$module_id = 0;
$group_id = 0;
$content_type = 'course';
// dd($series_details);
if( empty( $group_details ) ) {
	if ( $parent_course ) {
		$course_id = $parent_course->id;
		$module_id = $series_details->id;
	} else {
		$course_id = $series_details->id;
	}
} else {
	$content_type = 'group';
	$group_id = $group_details->id;
}
// echo $course_id;

?>
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-3">
    @if( Auth::check() )
    <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
    @endif
	
	@if( empty( $group_details ) )
    <li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('categories')}}</a></li>
    <li class="breadcrumb-item"><a href="{{URL_FRONTEND_COURSE_LIST . $category->slug}}">{{$category->category}}</a></li>
	@else
	<li class="breadcrumb-item"><a href="{{URL_STUDENT_MY_GROUPS}}">{{getPhrase('Groups')}}</a></li>
	<li class="breadcrumb-item"><a href="{{URL_STUDENT_DASHBOARD_GROUP . $group_details->slug}}">{{getPhrase('Group')}} ({{$group_details->title}})</a></li>
	@endif
	@if( empty( $group_details ) )
		@if ( $parent_course )
			<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSSERIES . $parent_course->slug}}">{{$parent_course->title}}</a></li>

			<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSLESSON . $series_details->slug}}">{{$series_details->title}}</a></li>
		@else
			<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSSERIES . $series_details->slug}}">{{$series_details->title}}</a></li>
		@endif
	@else
		<?php
	// dd( $series_details );
	?>
	@endif
    <li class="breadcrumb-item"><strong class="text-green">{{$display_item->title}}</strong></li>
</ol>
<?php

$pieces = App\LmsSeries::getPieces( $item->id );
$total_pieces = $pieces->count();
$total_pieces++; // Parent Content added
if ( $total_pieces > 1 ) {
    $pieces_array = array();
    array_push( $pieces_array, $item->slug ); // Parent Slug always at first place!!. If there is more than one piece the '$item' and '$parent_item' different
    foreach( $pieces as $piece ) {
        array_push( $pieces_array, $piece->slug );
    }
    if ( $current_piece ) {
        $current_index = array_search( $current_piece->slug, $pieces_array );
    } else {
        $current_index = array_search( $item->slug, $pieces_array );
    }

    $previous_index = $current_index - 1;
    $next_index = $current_index + 1;

    $current_display = $current_index + 1;
    $previous_display = $current_display - 1;
    $next_display = $current_display + 1;

    $previous_piece = $next_piece = '';
    if ( $current_index > 0 ) {
        if ( $previous_index == 0 ) {
            $previous_piece = URL_FRONTEND_LMSSINGLELESSON . $series_details->slug . '/' . $item->slug;
        } else {
            $previous_piece = URL_FRONTEND_LMSSINGLELESSON . $series_details->slug . '/' . $item->slug . '/' . $pieces_array[ $previous_index ];
        }
    }

    if ( $next_index != $total_pieces ) {
        $next_piece = URL_FRONTEND_LMSSINGLELESSON . $series_details->slug . '/' . $item->slug . '/' . $pieces_array[ $next_index ];
    }
?>
<ol class="breadcrumb mt-3">
<div class="d-flex justify-content-between">
   <div>
<?php if ( ! empty( $previous_piece ) ) { // Which means this is first piece in the lesson ?>
<a href="{{$previous_piece}}">
<i class="fa fa-arrow-left text-green">{{$previous_display}} / {{$total_pieces}}</i>
</a>
<?php } ?>

   </div>
   <div>
<span class="current_display">{{$current_display}}</span>

   </div>
   <div>
<?php

if ( ! empty( $next_piece ) ) { // which means piece is last in the lesson ?>
<a href="{{$next_piece}}">
<i class="fa fa-arrow-right text-green align-right">{{$next_display}} / {{$total_pieces}}</i>
</a>
<?php } ?>

   </div>
</div>
</ol>
<?php
}
?>
@if( empty( $group_details ) )
@if ( $parent_course )
    @if( $parent_course->privacy == 'infodisplay' && ! Auth :: check() )
    <div class="alert alert-info">
      <strong>Info!</strong> If you could log in, you can track your progress. Click <a href="#" onclick="open_login_modal('<?php echo base64_encode( url()->current() ); ?>')">here</a> to login
    </div>
    @elseif( $series_details->privacy == 'infodisplay' && ! Auth :: check() )
    <div class="alert alert-info">
      <strong>Info!</strong> If you could log in, you can track your progress. Click <a href="#" onclick="open_login_modal('<?php echo base64_encode( url()->current() ); ?>')">here</a> to login
    </div>
    @endif
@elseif( $series_details->privacy == 'infodisplay' && ! Auth :: check() )
    <div class="alert alert-info">
      <strong>Info!</strong> If you could log in, you can track your progress. Click <a href="#" onclick="open_login_modal('<?php echo base64_encode( url()->current() ); ?>')">here</a> to login
    </div>
@endif
@endif
<!-- Intro Content -->

<div class="row mt-4">
    <div class="col-sm-12">
            <div class="video-container">
                <?php
                $icon_class = 'icon icon-tick-double';
                $is_completed = FALSE;
                if ( Auth::check() ) {
                    $getstatus = App\LmsTrack::getStatus( $display_item->id, 'video', $course_id, $module_id, $content_type );
                    if ( ! empty( $getstatus ) && $getstatus->status == 'completed' ) {
                        $icon_class = 'icon icon-tick-border'; // If it is completed
                        $is_completed = TRUE;
                    }
                }
				if ( ! empty( $display_item->file_path_video ) ) {
                ?>
                @if( $is_completed )
                    <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$lesson_id}}', 'video-uncomplete', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">
                @else
                    <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$lesson_id}}', 'video', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">
                @endif
                <i class="{{$icon_class}}" id="video_icon"></i></button>
				<?php } ?>

                <div class="fixed-top-right ">
                    <?php
                    $icon_class = 'lesson-pin icon icon-map-pointer';
                    if ( Auth::check() ) {
                        if ( is_lesson_piece_completed( $display_item->id, $course_id, $module_id, '', $content_type ) ) {
                            $icon_class = 'lesson-pin icon icon-pointer-border';
                        }
                    }

                    ?>
                    <i class="{{$icon_class}}" id="overall_status"></i>
                    @if( ! empty( $display_item->help_text ) )
                    <span class="lesson-pin-info"  data-container="body" data-toggle="popover" data-placement="right" data-content="{{$display_item->help_text}}">
                        ?
                    </span>
                    @endif
                </div>

                <?php
                $video_background_image = IMAGES . '900x400.png';
                if ( ! empty( $display_item->video_background_image ) && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $display_item->video_background_image ) ) {
                    $video_background_image = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $display_item->video_background_image;
                }
                ?>
                <img src="{{$video_background_image}}" alt="" class="img-fluid">
                <?php
                $video_url = $display_item->lms_file_video;
                if ( $display_item->video_type == 'video' ) {
                    $video_url = IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $display_item->file_path_video;
                }
                ?>
				@if ( ! empty( $video_url ) )                
                <a data-fancybox="" href="{{$video_url}}" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen">
                    <div class="video-play-icn"><i class="fa fa-play"></i></div>
                </a>
                @endif
            </div>
    </div>
</div>


@if( ! empty( $display_item->description ) )
<div class="row">
    <div class="col-sm-12">
        <div class="text-container mt-4">
            <?php
            $icon_class = 'icon icon-tick-double';
            $is_completed = FALSE;
            if ( Auth::check() ) {
                $getstatus = App\LmsTrack::getStatus( $display_item->id, 'text', $course_id, $module_id, $content_type );
                if ( ! empty( $getstatus ) && $getstatus->status == 'completed' ) {
                    $icon_class = 'icon icon-tick-border'; // If it is completed
                    $is_completed = TRUE;
                }
            }
            ?>
            @if( $is_completed )
                <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$lesson_id}}', 'text-uncomplete', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">
            @else
                <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$lesson_id}}', 'text', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">
            @endif
            <i class="{{$icon_class}}" id="text_icon"></i></button>
            <h2>{!! $display_item->description !!}</h2>
        </div>
    </div>
</div>
@endif

@if( $display_item->quiz_id > 0 )
<div class="row mt-5">
    <div class="col-sm-12">
        <div class="quiz-container text-center">
            <div class="take-quiz-btn">
                <?php
                $is_completed = FALSE;
                $icon_class = 'icon icon-tick-double';
                if ( Auth::check() ) {
                    $getstatus = App\LmsTrack::getStatus( $display_item->id, 'quiz', $course_id, $module_id, $content_type );
                    if ( ! empty( $getstatus ) && $getstatus->status == 'completed' ) {
                        $icon_class = 'icon icon-tick-border'; // If it is completed
                        $is_completed = TRUE;
                    }
                }
                ?>
                @if( $is_completed )
                    <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$lesson_id}}', 'quiz-uncomplete', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">
                @else
                    <button class="fixed-top-left task-btn" ng-click="mark_as_complete('{{$lesson_id}}', 'quiz', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">
                @endif
                <i class="{{$icon_class}}" id="quiz_icon"></i></button>
                @if ( Auth::check() )
                    <button class="btn btn-primary-outline btn-min-width btn-top-icn" data-toggle="modal" data-target="#quizModal" ng-click="start_exam({{$display_item->quiz_id}})">{{getPhrase('Lesson Quiz')}}</button>
                @else
                    <button class="btn btn-primary-outline btn-min-width btn-top-icn" ng-click="mark_as_complete('{{$lesson_id}}', 'quiz', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">{{getPhrase('Lesson Quiz')}}</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="row justify-content-between mt-5 mb-8">
    <div class="col-md-5">
        <button class="btn btn-primary-outline btn-min-width btn-block askforlogin" data-toggle="modal" data-target="#commentsModal" ng-click="getData({{$display_item->id}}, 'comments')">{{getPhrase('Add Comments')}} <i class="fa fa-comments-o"></i></button>
        <?php $item_id = $display_item->id; ?>
        @include('lms-forntview.comments-modal')
    </div>
    <div class="col-md-5">
        @if ( Auth::check() )
        <button class="btn btn-primary-outline btn-min-width btn-block" data-toggle="modal" data-target="#notesModal" ng-click="getData({{$display_item->id}}, 'notes')">{{getPhrase('Take Notes')}}  <i class="fa fa-pencil-square"></i></button>
        @else
        <button class="btn btn-primary-outline btn-min-width btn-block" ng-click="mark_as_complete('{{$lesson_id}}', 'quiz', '{{$course_id}}', '{{$module_id}}', '{{$content_type}}', '{{$group_id}}')">{{getPhrase('Take Notes')}} <i class="fa fa-pencil-square"></i></button>
        @endif
        <!-- Modal -->
        <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{getPhrase('Take Notes')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! Form::open(array('url' => '', 'method' => 'POST', 'novalidate'=>'','name'=>'formComments')) !!}
                    <div class="modal-body">
                        {{ Form::textarea('notes', $value = null , $attributes = array('class'=>'form-control', 'ng-model' => 'notes', 'id' => 'notes', 'rows'=>'5', 'placeholder' => getPhrase('Enter notes here'))) }}
                    </div>
                    <div class="modal-footer">
                        @if( empty( $group_details ) )
							<button type="button" class="btn btn-primary" ng-click="saveNotes({{$display_item->id}}, {{$series_details->id}}, 'comments', {{$series_details->id}})">{{getPhrase('Save')}}</button>
						@else
							<button type="button" class="btn btn-primary" ng-click="saveNotes({{$display_item->id}}, {{$group_details->id}}, 'groupcomments', {{$group_details->id}})">{{getPhrase('Save')}}</button>
						@endif
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
@if( $display_item->quiz_id > 0 )
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
