@extends('layouts.lightbox-layout')
@section('content')
<div id="page-wrapper">

	<div class="row">
		<div class="col-sm-12">
			<ol class="breadcrumb mt-2">
				<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
				<li class="breadcrumb-item active">{{$title}}</li>
			</ol>
		</div>
	</div>
	<div class="row ">
		<div class="col-sm-12">
			<div class="">
				@if(Auth::check())
				<div class="row library-items">
					{!! Form::open(array('url' => URL_CONTENT_COMMENT_SAVE . $content_slug, 'method' => 'POST', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
					<div class="col-md-12 col-md-offset-3">
						{!! Form::label('Enter Your Comments', 'Enter Your Comments', ['class' => 'control-label']) !!}
						{{ Form::textarea('modal_commnets', $value = null , $attributes = array('class'=>'form-control', 'ng-model' => 'comments', 'id' => 'modal_commnets', 'rows'=>'5', 'placeholder' => getPhrase('Enter commnets here'))) }}
						<input type="hidden" name="modal_item_id" id="modal_item_id" value="{{$item_id}}">
						<input type="hidden" name="page_name" id="page_name" value="{{$page_name}}">
						
						<input type="hidden" name="course_id" id="course_id" value="{{$course_id}}">
						<input type="hidden" name="module_id" id="module_id" value="{{$module_id}}">					
						
						<!-- Submit Form Input -->
						<div class="text-center mt-3">
						{!! Form::submit('Submit', ['class' => 'btn btn-secondary btn-compose btn-lg btn-min-width']) !!}
						</div>
					</div>
				{!! Form::close() !!}
				</div>
				@endif
				<hr>
				<div class="modal-table-cust mt-2 height-overflow"> 
					<table class="table table-striped table-bordered datatable2" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th></th>						  
							</tr>
						</thead>				 
					</table>
				</div>
			</div>
		</div>
	</div>

</div>
@stop
@section('footer_scripts')
@include('common.datatables2', array('route'=>URL_CONTENT_COMMENTS_GETLIST . $content_slug, 'route_as_url' => TRUE))
@stop
