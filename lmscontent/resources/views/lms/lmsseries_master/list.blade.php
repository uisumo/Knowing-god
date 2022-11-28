@extends( $layout )
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')


<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					@if ( checkRole(getUserGrade(2)) )
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
					@else
						<ol class="breadcrumb mt-2">
						@if( Auth::check() )
						<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
						@endif
						<li class="breadcrumb-item"> <strong class="text-green"><?php echo getPhrase( 'serieses' ); ?></strong> </li>
					</ol>
					@endif
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					@if ( checkRole(getUserGrade(2)) )
						<div class="panel-heading">						
							<div class="pull-right messages-buttons">
								<a href="{{URL_LMS_SERIES_MASTER_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
							</div>
							
							<h1>{{ $title }}</h1>
						</div>
					@endif
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('image')}}</th>
									@if ( checkRole(getUserGrade(2)) )
									<th>{{ getPhrase('action')}}</th>
									@else
									<th>{{ getPhrase('courses')}}</th>
									@endif
								  
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
  <?php
  if ( empty( $category ) ) {
	  $category = FALSE;
  }
  ?>
 @if( $category )
	 @include('common.datatables', array('route'=>URL_LMS_SERIES_MASTER_AJAXLIST . '/' . $category, 'route_as_url' => TRUE))
@else
	@include('common.datatables', array('route'=>URL_LMS_SERIES_MASTER_AJAXLIST, 'route_as_url' => TRUE))
@endif

@include('common.deletescript', array('route'=>URL_LMS_SERIES_MASTER_DELETE))

@stop
