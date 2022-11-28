@extends( $layout )
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
							@if( checkRole( getUserGrade(5) ) )
								<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>

								<li class="breadcrumb-item"><a href="{{URL_STUDENT_MY_GROUPS}}">{{ getPhrase('groups')}}</a> </li>
								
								@if($group_details)
								<li class="breadcrumb-item"><a href="{{URL_STUDENT_DASHBOARD_GROUP . $group_slug}}">{{getPhrase('Group')}} ({{$group_details->title}})</a></li>
								@endif

								<li class="breadcrumb-item active">{{isset($title) ? $title : ''}}</li>
							@else
								<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
								<li>{{ $title }}</li>
							@endif
							
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<?php
						$button_title = getPhrase('add_courses');
						$url = URL_LMS_ADD_GROUP_COURSES . $group_slug;
						?>
						@if( $group_details && is_group_owner( $group_details->id ) )
						<div class="pull-right messages-buttons">				
							<a href="{{$url}}" class="btn  btn-primary button" >{{$button_title}}</a>
						</div>
						@endif
						
						<h1>{{ $title }}</h1>
					</div>
					@if ( $group_details )
					<?php group_information_messages( $group_details->id ); ?>
					@endif
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<th>{{ getPhrase('contents')}}</th>
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
	
@include('common.datatables', array('route'=>URL_LMS_GROUP_GET_COURSES . '/' . $group_slug, 'route_as_url' => 'yes' ))

@include('common.deletescript', array('route'=>URL_LMS_CONTENT_DELETE))
@if ( $group_details )
	@include('common.custom-message-alert')
@endif

@stop
