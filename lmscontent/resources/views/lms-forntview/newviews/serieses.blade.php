@extends('layouts.student.studentlayout')

@section('custom_div')
 <div ng-controller="singleLessonCtrl">
 @stop
 
@section('content')
<?php
$breadcrumb = array(
	'category' => $category,
);
if ( ! empty( $course ) ) {
	$breadcrumb['course'] = $course;
}
if ( ! empty( $title ) ) {
	$breadcrumb['title'] = $breadcrumb_title;
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

	@if( $serieses->count() > 0 )

		@foreach( $serieses as $series )
		<?php
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
		<div class="col-sm-6">
			<div class="white-card cs-card mb-4">
				<div class="flow-hidden relative p-3">
					<div class="{{$ribbon_class}}">{{$series->subject_title}}</div>
					<div class="row">
						<div class="col-sm-6 pr-0">
							@if($series->image!='')
							<img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$series->image}}" alt="">
							@else
							<img class="img-fluid rounded" src="http://placehold.it/750x450" alt="">
							@endif
						</div>
						<div class="col-sm-6">
							<h5 class="text-green">{{$series->title}}</h5>
							@if( ! empty( $series->short_description ) )
							<p class="course-card-text">{!!$series->short_description!!}</p>
							@endif
							<div class="mt-2"><a href="{{URL_LMS_SHOW_COUSES . $category->slug . '/' . $series->slug}}" class="btn btn-kg btn-course btn-round {{$see_more_class}}">{{getPhrase('see_more')}}</a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	@else
		Ooops...! {{getPhrase('No_serieses_available')}}
	@endif
	
</div>

<div class="row">
<div class="mb-4">
<h2>{{getPhrase('Recommended for you')}}</h2>
</div>

<div class="row">
	@if( ! empty( $subjects ) && $subjects->count() > 0 )
	<?php $recommended_courses = array(); ?>
	@foreach( $subjects as $subject )
	<?php
	$ribbon_class = 'corner-ribbon corner-ribbon-small left btn-green';
	$see_more_class = 'btn-green';
	if ( $subject->color_class == 'text-blue' ) {
		$ribbon_class = 'corner-ribbon corner-ribbon-small left btn-blue';
		$see_more_class = 'btn-blue';
	} elseif ( $subject->color_class == 'text-yellow' ) {
		$ribbon_class = 'corner-ribbon corner-ribbon-small left btn-yellow';
		$see_more_class = 'btn-yellow';
	}
	$attempted_courses = attempted_courses();

	if ( ! empty( $recommended_courses ) ) {
		// If same course is in different subjects! Let us skip that course.
		foreach( $recommended_courses as $rc ) {
			array_push( $attempted_courses, $rc );
		}
	}
	// Let us skip to show if there are any completed courses
	if ( ! empty( $attempted_courses ) ) {
		$recommended = DB::table('lmscontents AS lc')
		->select(['ls.*', 'qc.slug AS catslug'])
		->join( 'subjects AS s', 's.id', '=', 'lc.subject_id' )
		->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lc.id' )
		->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' )
		->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
		->where( 'lc.subject_id', '=', $subject->id )
		->whereNotIn( 'ld.lmsseries_id', $attempted_courses )
		->groupBy('ld.lmsseries_id')
		->orderBy( 'ls.display_order', 'asc' )
		->orderBy( 'ls.updated_at', 'desc' )
		->first();
		if ( ! $recommended ) {
			$recommended = DB::table('lmscontents AS lc')
			->select(['ls.*', 'qc.slug AS catslug'])
			->join( 'subjects AS s', 's.id', '=', 'lc.subject_id' )
			->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lc.id' )
			->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' )
			->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->where( 'lc.subject_id', '=', $subject->id )
			->groupBy('ld.lmsseries_id')
			->orderBy( 'ls.display_order', 'asc' )
			->orderBy( 'ls.updated_at', 'desc' )
			->first();
		}
	} else {
		$recommended = DB::table('lmscontents AS lc')
			->select(['ls.*', 'qc.slug AS catslug'])
			->join( 'subjects AS s', 's.id', '=', 'lc.subject_id' )
			->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lc.id' )
			->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' )
			->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->where( 'lc.subject_id', '=', $subject->id )
			->groupBy('ld.lmsseries_id')
			->orderBy( 'ls.display_order', 'asc' )
			->orderBy( 'ls.updated_at', 'desc' )
			->first();
	}
	?>
	@if ( $recommended )
		<?php
	array_push( $recommended_courses, $recommended->id );
	?>
	<div class="col-sm-6">
		<div class="white-card cs-card mb-4">
			<div class="flow-hidden relative p-3">
				<div class="{{$ribbon_class}}">{{$subject->subject_title}}</div>
				<div class="row">
					<div class="col-sm-6 pr-0">
						@if($recommended->image!='')
						<img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$recommended->image}}" alt="">
						@else
						<img class="img-fluid rounded" src="http://placehold.it/750x450" alt="">
						@endif
					</div>
					<div class="col-sm-6">
						<h5 class="text-green">{{$recommended->title}}</h5>
						@if( ! empty( $recommended->short_description ) )
						<p class="course-card-text">{!!$recommended->short_description!!}</p>
						@endif
						<div class="mt-2"><a href="{{URL_FRONTEND_LMSSERIES . $recommended->catslug}}" class="btn btn-kg btn-course btn-round {{$see_more_class}}">{{getPhrase('see_more')}}</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	@endforeach
	<div class="col-sm-6">
		<div class="add-course-card cs-card mb-4">
			@if(Auth::check())
			<!-- <a href="#" class="text-center" ng-click="show_recommended()"> -->
			<a href="{{URL_FRONTEND_RECOMMENDED_COURSES}}" class="text-center">
			@else
			<a href="#" class="text-center" ng-click="open_login_modal('{{base64_encode(URL_FRONTEND_RECOMMENDED_COURSES. '/'. $item->slug)}}')">
			@endif
				<div class="join-btn cs-center">
				  <i class="icon icon-plus"></i>
				</div>
				<h6 class="mt-1">{{getPhrase('Add a Course')}}</h6>
			</a>
		</div>
	</div>
	@else
		Ooops...! {{getPhrase('No_Categories_available')}}
		<a href="{{URL_USERS_SETTINGS.Auth::User()->slug}}" >{{getPhrase('click_here_to_change_your_preferences')}}</a>
	@endif
</div>

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