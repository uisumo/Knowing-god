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
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb mt-2">
							@if( Auth::check() )
							<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
							@endif
							<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}"><?php echo getPhrase( 'categories' ); ?> <?php if ( ! empty( $record ) ) echo '(' . $record->category . ')'; ?></a></li>
							<li class="breadcrumb-item"> <strong class="text-green">{{$title}}</strong> </li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1>{!! $title !!}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<th>{{ getPhrase('total_items')}}</th>
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
  
 @include('common.datatables', array('route'=>URL_LMS_GET_COURSES_LIST . $category, 'route_as_url' => TRUE))

@stop
