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
				
				@if ( ! empty( $group_slug ) )
				<?php group_information_messages( $group_details->id ); ?>
				@endif
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<?php
						$button_title = getPhrase('create');
						$url = '';						
						if( checkRole( getUserGrade(5) ) ) {
							$button_title = getPhrase('add_lessons');
							if ( ! empty( $group_slug ) ) {
								$details = App\LMSGroups::getRecordWithSlug( $group_slug );
								if ( $details->user_id == Auth::User()->id ) {
									$url = URL_STUDENT_ADD_GROUP_CONTENTS . $group_slug;
								}
							} else {
								$url = '';
							}							
						} else {
							$button_title = getPhrase('create');
							$url = URL_LMS_CONTENT_ADD;			
						}
						?>
						@if( ! empty( $url ) )
						<div class="pull-right messages-buttons">				
							<a href="{{$url}}" class="btn  btn-primary button" >{{$button_title}}</a>
						</div>
						@endif
						
						<h1>{{ $title }}</h1>
					</div>
					
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<th>{{ getPhrase('type')}}</th>
									<th>{{ getPhrase('pathway')}}</th>
									@if(checkRole(getUserGrade(2)))
									<th>{{ getPhrase('action')}}</th>
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
	@if( ! empty( $group_slug ) )
		@include('common.datatables', array('route'=>URL_STUDENT_LMS_CONTENT_GETLIST . $group_slug, 'route_as_url' => 'yes' ))
		@include('common.custom-message-alert')
	@else
		@include('common.datatables', array('route'=>'lmscontent.dataTable'))
	@endif 
 @include('common.deletescript', array('route'=>URL_LMS_CONTENT_DELETE))

@stop
