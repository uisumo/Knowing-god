@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
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
 
@section('footer_scripts')
<?php /* ?>
 @include('common.datatables', array('route'=>'users.dataTable'))
 <?php */ ?>
 @include('common.datatables', array('route'=>'users.coachesDataTable'))
 @include('common.deletescript', array('route'=>URL_USERS_DELETE, 'update_route' => URL_USER_CHANGE_STATUS))
@stop
