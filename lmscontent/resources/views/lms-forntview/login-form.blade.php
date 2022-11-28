{!! Form::open(array('url' => '', 'method' => 'POST', 'novalidate'=>'','name'=>'formLogin')) !!}
<div class="modal-body">
   <div class="modal-login-layout">
    <div class="form-group">
        <?php if ( $from == 'wp' ) { ?>
        <input type="hidden" name="csrf" id="csrf" value="{{csrf_token()}}">
        <?php } ?>
        {{ Form::text('email', $value = null , $attributes = array('class'=>'form-control',
            'ng-model'=>'email',
            'required'=> 'true',
            'id'=> 'email',
            'placeholder' => getPhrase('username').'/'.getPhrase('email'),
            'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',
        )) }}
    </div>

    <div class="form-group">
        {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',
        'placeholder' => getPhrase("password"),
        'ng-model'=>'registration.password',
        'required'=> 'true',
        'id'=> 'password',
        'ng-class'=>'{"has-error": loginForm.password.$touched && loginForm.password.$invalid}',
        'ng-minlength' => 5
        )) }}
    </div>
    <!--    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{getPhrase('Close')}}</button>-->
    <div class="text-center">
    <?php if ( $from == 'wp' ) { ?>
    <button type="button" class="btn button btn-primary"  onclick="ajaxLogin()">{{getPhrase('Login')}}</button>
    <?php } else { ?>
    <button type="button" class="btn button btn-primary"  ng-click="ajaxLogin()">{{getPhrase('Login')}}</button>
    <?php } ?>
    </div>
</div>

</div>
<div class="modal-footer">
    <div class="  st-login-tags col-sm-12">
        <a href="javascript:void(0);" onclick="openforgot()">{{getPhrase('Forgot Password?')}}</a>
        <?php
        if ( Corcel\Model\Option::get( 'users_can_register' ) ) : ?>
        &nbsp; | &nbsp;  <a href="{{URL_USERS_REGISTER}}">
        <?php echo getPhrase( 'Don\'t have an account? Register' ); ?>
        <?php endif; ?>
        </a>
    </div>
    <input type="hidden" name="redirect_url" id="redirect_url" value="">

</div>
{!! Form::close() !!}
<?php
if ( $from == 'wp' ) { ?>
@include('common.alertify')
@include('common.validations')
@include('lms-forntview.scripts.js-scripts')
<?php
}
?>
