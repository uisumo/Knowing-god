@extends($layout)
@section('custom_div')
<div ng-controller="users_controller">
@stop

@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop

@section('content')

<div id="page-wrapper" >
			<div class="container-fluid">
				<!-- Page Heading -->
				@if(checkRole(getUserGrade(2)))
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
				@else
					<div class="row">
						<div class="col-sm-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{URL_USERS_EDIT.Auth::user()->slug}}">{{ getPhrase('dashboard') }}</a></li>
								<li class="breadcrumb-item">{{ $title }}</li>
							</ol>
						</div>
					</div>
				@endif
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages" ng-init="initAngData({{$settings}});">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
								 	<th>{{ getPhrase('name')}}</th>
									<th>{{ getPhrase('user_name')}} / {{ getPhrase('email')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<th>{{ getPhrase('role')}}</th>
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

@section('custom_div_end')
 </div>
@stop
 
@section('footer_scripts')
@include('common.alertify')
@include('users.scripts.js-scripts')
@include('common.datatables', array('route'=>URL_ASSIGN_FACILITATORS_LIST_GETLIST . '/' . $coach->slug, 'route_as_url' => true))
@stop
