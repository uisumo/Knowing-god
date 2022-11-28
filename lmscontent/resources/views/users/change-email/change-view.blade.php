 
@extends($layout)

{{-- {{dd(checkRole(getUserGrade(2)))}} --}}
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb mt-2">							
							@if(checkRole(getUserGrade(2)))
							<li class="breadcrumb-item"><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li class="breadcrumb-item"><a href="{{URL_USERS}}">{{ getPhrase('users')}}</a> </li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
							@else
							<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
							<li class="breadcrumb-item"> <a href="{{URL_USERS_EDIT.Auth::user()->slug}}"><?php echo getPhrase( 'my_profile' ); ?></a> </li>
							<li class="breadcrumb-item active">{{getphrase('change_email')}}</li>
							@endif
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->
				
	<div class="panel panel-custom">
					<div class="panel-heading">
					 	<h2 class="mt-4 mb-4">{{ $title }}  </h2>
					</div>


					<div class="panel-body form-auth-style">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record, 
						array('url' => 'users/change-email', 'method'=>'post', 'novalidate'=>'', 'name'=>"changePassword")) }}
					@endif

					 @include('users.change-email.form_elements', array('button_name'=> $button_name, 'record' => $record)) 
					 
					{!! Form::close() !!}
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@endsection

@section('footer_scripts')
	@include('common.validations');
@stop