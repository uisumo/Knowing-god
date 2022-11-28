@extends('layouts.student.studentlayout')
@section('content')
<h2 class="mt-4">{{$title}}</h2>
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-3">
	@if( Auth::check() )
	<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
	@endif
	
	<li class="breadcrumb-item"><strong class="text-green">{{$title}}</strong></li>
</ol>
<!-- Intro Content -->
<div class="row mt-2">
	<div class="col-sm-12">
		<h4>{{$title}}</h4>
		<small>{{getPhrase('Click the button to add the course to your list')}}</small>
	</div>
</div>

<div class="row mt-4" ng-controller="singleLessonCtrl">
	
	@foreach($subject_courses as $series)
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
						<a class="text-green" href="{{$target}}">{{$series->title}}</a>
						<?php /* ?>
						<a class="text-green" data-toggle="modal" data-target="#lessonsModal" ng-click="fetch_lessons('{{$series->slug}}')">{{$series->title}}</a><?php */ ?>
					</h4>
					<?php if ( ! empty( $series->sub_title ) ) : ?>
					<h6 class="card-subtitle mb-2 text-green">{{$series->sub_title}}</h6>
					<?php endif; ?>
					<p class="card-text">{!! $series->short_description !!}</p>
				</div>
				<?php
				$contents = App\LmsSeries::getAllContents( $series->id );							
				if ( $contents->count() > 0 ) {
					$total_contents = $contents->count();
					$completed = 0;
				?>
				<div class="card-footer">
					<ul class="course-finished-path">
						<?php 
						foreach( $contents as $content ) : 
						$class = '';
						$total_pieces = App\LmsSeries::getPieces( $content->id );
						$total_pieces_count = $total_pieces->count();
						$total_pieces_count++; // Parent Content added
						if ( $total_pieces_count > 1 ) {
							if ( completed_contents( 0, $content->id ) ) { // Let us see parent piece completed or not
								$completed++;
							}
							foreach( $total_pieces as $piece ) {						
								if ( completed_contents( 0, $piece->id ) ) {
									$completed++;
								}
							}
						} elseif ( is_completed( $content->id ) ) {
							$class = 'completed';
							$completed++;
						}
						?>
						<li class="{{$class}}"><i class="icon icon-pointer-white" title="{{$content->title}}"></i></li>
						<?php endforeach;
						if ( $total_contents == $completed ) {
							$check = App\UsersCompletedCourses::where(array( 'user_id' => Auth::User()->id, 'course_id' => $series->id ))->get();
							if ( $check->count() == 0 ) {
								$record = new App\UsersCompletedCourses();
								$record->user_id = Auth::User()->id;
								$record->course_id = $series->id;
								$record->save();
							}										
						}
						?>
					</ul>
				</div>
				<?php } ?>
			</div>
		</div>
	@endforeach
<?php $item_id = ''; ?>
	@include('lms-forntview.comments-modal')
	@include('lms-forntview.login-modal')
</div>
@stop
@section('footer_scripts')
	@include('common.validations')
	@include('lms-forntview.scripts.js-scripts')
@stop