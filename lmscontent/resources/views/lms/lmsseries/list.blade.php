@extends( $layout )
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<?php
if ( empty( $is_module ) ) {
	$is_module = 'no';
}
?>

<div id="page-wrapper">
			<div class="container-fluid">
				@if ( $is_module == 'yes' && ( ! empty( $course ) ) ) 
				<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
					<a class="nav-link" id="edit-tab" href="{{URL_LMS_SERIES_EDIT.$course->slug}}" aria-selected="true">Edit</a>
				  </li>
				 
				  <li class="nav-item">
					<a class="nav-link show active" id="modules-tab" href="{{URL_LMS_MODULES . '/' . $course->slug}}" aria-selected="false">Modules</a>
				  </li>
				 
				  <li class="nav-item">
					<a class="nav-link" id="lessons-tab" href="{{URL_COURSE_LESSONS . $course->slug}}"  aria-selected="false">Lessons</a>
				  </li>
				</ul>
				@endif
				
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{!! $title !!}</li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						
						<div class="pull-right messages-buttons">
							<?php
							
							$url = URL_LMS_SERIES_ADD;
							if ( $is_module == 'yes' ) {
								$url = URL_LMS_MODULES_ADD;							
								if ( ! empty( $course ) ) {
									$url .= '/' . $course->slug;
								}
							}

							if ( $parent_id === 'special' ) {
								$url = URL_LMS_SERIES_ADD . '/special';
							}
							?>
							<a href="{{$url}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div>
						<h1>{!! $title !!}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<?php /* ?>
									<th>{{ getPhrase('is_paid')}}</th>
									<th>{{ getPhrase('cost')}}</th>
									<th>{{ getPhrase('validity')}}</th>
									<?php */ ?>
									<th>{{ getPhrase('total_items')}}</th>
									<th>{{ getPhrase('action')}}</th>
								  
								</tr>
							</thead>
							 
						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection
 

@section('footer_scripts')
  
 @include('common.datatables', array('route'=>URL_LMS_SERIES_AJAXLIST . '/' . $parent_id, 'route_as_url' => TRUE))
 @include('common.deletescript', array('route'=>URL_LMS_SERIES_DELETE))

@stop
