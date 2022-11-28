
@extends('layouts.full-width-layout')

@section('content')
<div class="login-content">
<div class="row justify-content-md-center my-5">
<div class="col-md-8 mx-auto">
<div class="card">

<!--        <div class="logo text-center"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="" height="59" width="211" ></div>-->

<!--          @include('layouts.site-navigation')-->
<div class="card-header">{{getPhrase('send_activation_mail')}}</div>

<?php
$message = '';
if (session('message')) {
	$message = session('message');
}
?>
<div class="card-body">
        {!! Form::open(array('url' => URL_SEND_ACTIVATION_MAIL, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"loginForm")) !!}



        @include('errors.errors')



            <div class="form-group row">
<label for="email" class="col-lg-4 col-form-label text-lg-right">E-Mail Address</label>
<?php
$email = '';
if (session('email')) {
	$email = session('status');
}
?>
 <div class="col-lg-6">

                {{ Form::text('email', $value = $email , $attributes = array('class'=>'form-control',

            'ng-model'=>'email',

            'required'=> 'true',
            'id'=> 'email',


            'placeholder' => getPhrase('email'),

            'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',

        )) }}

    <div class="validation-error" ng-messages="loginForm.email.$error" >

        {!! getValidationMessage()!!}

        {!! getValidationMessage('email')!!}

    </div>

</div>

            </div>

     <div class="form-group row">
        <div class="col-lg-8 offset-lg-4">
            <div class=" buttons forgot-links">

             <button type="submit" class="btn button btn-success">Send</button>
					{!! Form::close() !!}
						
			</div>

		</div>
	</div>
	<div class="form-group row">
		<div class="col-lg-12">
			<a href="javascript:void(0);" class="btn-link"  data-toggle="modal" data-target="#myModal" ><i class="icon icon-question"></i> Forgot password?</a>
			<a href="{{URL_USERS_LOGIN}}" class="btn-link"> {{ getPhrase('Have account?') }}</a>
			<a href="{{URL_USERS_REGISTER}}" class="btn-link"> {{ getPhrase('Need to Register?') }}</a>
		</div>
	</div>
</div>
    </div>
	</div>
	</div>


@include('auth.forgot-password-modal')
@stop



@section('footer_scripts')

    @include('common.validations')

@stop
