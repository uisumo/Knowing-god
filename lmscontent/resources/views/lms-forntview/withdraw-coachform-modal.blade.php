@extends('layouts.lightbox-layout')
@section('content')
<div id="page-wrapper">

	<div class="row">
		<div class="col-sm-12">
			<ol class="breadcrumb mt-2">
				<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
				<li class="breadcrumb-item active">{{getPhrase('withdraw_request_for_a_coach')}}</li>
			</ol>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-sm-12">
			<div class="add-frnd-modal">
				<div class="row library-items">
					{!! Form::open(array('url' => URL_WITHDRAW_COACH_REQUEST, 'method' => 'POST', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
					<div class="col-md-12 col-md-offset-3">
						<label class="control-label">
						Instructions
						</label>
						<!-- Submit Form Input -->
						<div class="text-center mt-3">
						{!! Form::submit('Yes', ['class' => 'btn btn-secondary btn-compose btn-lg btn-min-width']) !!}
						<a class="btn btn-secondary btn-compose btn-lg btn-min-width" data-dismiss="modal">{{getPhrase('no')}}</a>
						</div>
					</div>
				{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

</div>
@stop
@section('footer_scripts')

@stop
