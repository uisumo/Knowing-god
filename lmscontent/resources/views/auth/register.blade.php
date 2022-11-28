@extends('layouts.full-width-layout')

@section('content')

<div class="login-content">
<div class="row justify-content-md-center my-5">
<div class="col-md-8 mx-auto">


    <h2 class="text-center">{{getPhrase('Welcome to Knowing')}}<span class="pathway_green">God</span>{{getPhrase('.Org')}}</h2>
	<div class="card">
	<div class="card-header">{{getPhrase('Please Register')}}</div>
	<div class="card-body">
<!--        <div class="logo text-center"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="" height="59" width="211"></div> -->
<!--@include('layouts.site-navigation')-->

        @include('errors.errors')



        {!! Form::open(array('url' => URL_USERS_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"registrationForm")) !!}


         <div class="form-group row">
			<label class="col-lg-4 col-form-label text-lg-right">First Name</label>
             <div class="col-lg-6">
                {{ Form::text('first_name', $value = null , $attributes = array('class'=>'form-control',
                    'placeholder' => getPhrase("first_name"),
                    'ng-model'=>'first_name',
                    'ng-pattern' => getRegexPattern('name'),
                    'required'=> 'true',
                    'ng-class'=>'{"has-error": registrationForm.first_name.$touched && registrationForm.first_name.$invalid}',
                    'ng-minlength' => '4',
                )) }}
                <div class="validation-error" ng-messages="registrationForm.first_name.$error" >
                    {!! getValidationMessage()!!}
                    {!! getValidationMessage('minlength')!!}
                    {!! getValidationMessage('pattern')!!}
                </div>
				</div>
        </div>

        <div class="form-group row">
			<label class="col-lg-4 col-form-label text-lg-right">Last Name</label>
            <div class="col-lg-6">
                {{ Form::text('last_name', $value = null , $attributes = array('class'=>'form-control',
                    'placeholder' => getPhrase("last_name"),
                    'ng-model'=>'last_name',
                    'ng-pattern' => getRegexPattern('name'),
                    'ng-class'=>'{"has-error": registrationForm.last_name.$touched && registrationForm.last_name.$invalid}',
                )) }}
                <div class="validation-error" ng-messages="registrationForm.last_name.$error" >
                    {!! getValidationMessage('minlength')!!}
                    {!! getValidationMessage('pattern')!!}
                </div>
            </div>
		</div>


		<div class="form-group row">
			<label class="col-lg-4 col-form-label text-lg-right">User Name</label>
            <div class="col-lg-6">
        {{ Form::text('username', $value = null , $attributes = array('class'=>'form-control',

            'placeholder' => getPhrase("username"),

            'ng-model'=>'username',



            'required'=> 'true',

            'ng-class'=>'{"has-error": registrationForm.username.$touched && registrationForm.username.$invalid}',

            'ng-minlength' => '4',

        )) }}

    <div class="validation-error" ng-messages="registrationForm.username.$error" >

        {!! getValidationMessage()!!}

        {!! getValidationMessage('minlength')!!}

        {!! getValidationMessage('pattern')!!}

    </div>
	</div>
</div>



	<div class="form-group row">
		<label class="col-lg-4 col-form-label text-lg-right">E-Mail Address</label>
        <div class="col-lg-6">

            {{ Form::email('email', $value = null , $attributes = array('class'=>'form-control',

            'placeholder' => getPhrase("email"),

            'ng-model'=>'email',

            'required'=> 'true',

            'ng-class'=>'{"has-error": registrationForm.email.$touched && registrationForm.email.$invalid}',

        )) }}

				<div class="validation-error" ng-messages="registrationForm.email.$error" >

					{!! getValidationMessage()!!}

					{!! getValidationMessage('email')!!}

				</div>
		</div>
    </div>



            <div class="form-group row">


		<label class="col-lg-4 col-form-label text-lg-right">Password</label>
        <div class="col-lg-6">

        {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',

            'placeholder' => getPhrase("password"),

            'ng-model'=>'registration.password',

            'required'=> 'true',

            'ng-class'=>'{"has-error": registrationForm.password.$touched && registrationForm.password.$invalid}',

            'ng-minlength' => 5

        )) }}

    <div class="validation-error" ng-messages="registrationForm.password.$error" >

        {!! getValidationMessage()!!}

        {!! getValidationMessage('password')!!}

    </div>
</div>


            </div>



            <div class="form-group row">
		<label class="col-lg-4 col-form-label text-lg-right">Confirm Password</label>
        <div class="col-lg-6">

                    {{ Form::password('password_confirmation', $attributes = array('class'=>'form-control instruction-call',

            'placeholder' => getPhrase("password_confirmation"),

            'ng-model'=>'registration.password_confirmation',

            'required'=> 'true',

            'ng-class'=>'{"has-error": registrationForm.password_confirmation.$touched && registrationForm.password_confirmation.$invalid}',

            'ng-minlength' => 5,

            'compare-to' =>"registration.password"

        )) }}

    <div class="validation-error" ng-messages="registrationForm.password_confirmation.$error" >

        {!! getValidationMessage()!!}

        {!! getValidationMessage('minlength')!!}

        {!! getValidationMessage('confirmPassword')!!}

    </div>
</div>
            </div>





    <?php $parent_module = getSetting('parent', 'module');
    $parent_module = FALSE;
    ?>

            @if(!$parent_module)

        <input type="hidden" name="is_student" value="0">

            @else

        <div class="row">





                            <div class="col-md-6">

                            {{ Form::radio('is_student', 0, true, array('id'=>'free')) }}



                                <label for="free"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('i_am_a_student')}}</label>

                            </div>

                            <div class="col-md-6">

                            {{ Form::radio('is_student', 1, false, array('id'=>'paid' )) }}

                                <label for="paid">

                                <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('i_am_a_parent')}} </label>

                            </div>



            </div>

        @endif
     <div class="form-group row">
        <div class="col-lg-6 offset-lg-4">
            <div class=" buttons">

                <button type="submit"  class="btn button btn-success">{{getPhrase('register')}}</button>

            

        {!! Form::close() !!}



    <!-- <span class="mt-3"><small style="color:#838383">Have an account?</small> <a href="{{URL_USERS_LOGIN}}">{{getPhrase('Login»')}} </a></span>*/-->
	</div>
    </div>
	</div>
	</div>
	</div> 
    </div>
</div>
@stop



@section('footer_scripts')
@include('errors.formMessages')
    @include('common.validations')

@stop
