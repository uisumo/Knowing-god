<?php
$check = DB::table( 'email_change_requests' )->where( 'user_id', '=', Auth::User()->id )->first();
if ( $check ) { ?>
<div class="alert alert-success" role="alert">
  <strong>Great!</strong> We have already received your request to chagne email. We have sent you a email to <b>{{$check->new_email}}</b>. Please check your inbox to confirm the email address.
</div>

@if ( $check->email_sent < 5 )

<button type="submit" class="btn btn-lg btn-success button" name="button" value="resend">
<input type="hidden" name="new_email" id="new_email" value="{{$check->new_email}}">
Resend Confirm Link
</button>
<small>{{5 - $check->email_sent . ' attempts remain(s)'}}</small>
&nbsp;|&nbsp;
@endif

<button type="submit" class="btn btn-lg btn-danger" type="submit" name="button" value="remove">
<input type="hidden" name="new_email" id="new_email" value="{{$check->new_email}}">
Remove Request</button>


<?php	
} else {
?>
<fieldset class="form-group">
	{{ Form::label('new_email', getphrase('new_email')) }}
	<span class="text-red">*</span>
	{{ Form::email('new_email', 
		$value = null, 
		$attributes = array('class'=>'form-control', 
		'placeholder' => getphrase('new_email'),
		'ng-model'=>'new_email',
		'required'=> 'true',
		'ng-class'=>'{"has-error": changePassword.new_email.$touched && changePassword.new_email.$invalid}',
		'ng-minlength' => 5
	)) }}
	<div class="validation-error" ng-messages="changePassword.new_email.$error" >
	{!! getValidationMessage()!!}
	{!! getValidationMessage('email')!!}
	</div>
</fieldset>
<div class="buttons text-center">
	<button class="btn btn-lg btn-success button" type="submit" name="button" value="add">{{ $button_name }}</button>
</div>
<?php } ?>