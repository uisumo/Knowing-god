@extends('layouts.student.studentlayout')
@section('content')
<h2 class="mt-0">{{$title}}
@if ( ! empty( $sub_title ) )
<small>| {{$sub_title}}</small>
@endif
</h2>

<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-3">
	@if( Auth::check() )
	<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
	@endif
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('categories')}}</a></li>
	
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_COURSE_LIST . $category->slug}}">{{$category->category}}</a></li>
	
	@if ( $parent_course )
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSSERIES . $parent_course->slug}}">{{$parent_course->title}}</a></li>
	@endif
	
	<li class="breadcrumb-item"><strong class="text-green">{{$title}}</strong></li>
</ol>
<!-- Intro Content -->
<div class="row mt-2">
	<div class="col-sm-12">
		<h4><i class="fa fa-video-camera"></i> {{$title}} Video Lessons</h4>
	</div>
</div>
@if ( ! empty( $parent_course ) && $parent_course->privacy == 'infodisplay' && ! Auth :: check() )
	<div class="alert alert-info">
	  <strong>Info!</strong> If you could log in, you can track your progress. Click <a href="#" onclick="open_login_modal('<?php echo base64_encode( url()->current() ); ?>')">here</a> to login
	</div>
@endif
<?php
$is_paid = FALSE;
if( $parent_course->is_paid == 1 && $parent_course->cost > 0 ) {
	if ( ! isItemPurchased( $parent_course->id, 'lms' ) ) {
		$is_paid = TRUE;
	}
}
?>
@include('lms-forntview.other-views.lessons', array('contents' => $contents, 'is_paid' => $is_paid))
@stop
@section('footer_scripts')
	@include('common.validations')
	@include('lms-forntview.scripts.js-scripts')
@stop