    <!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog">

    {!! Form::open(array('url' => URL_FORGOT_PASSWORD, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"passwordForm")) !!}

    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">{{getPhrase('forgot_password')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

      </div>

      <div class="modal-body">

        <div class="form-group">

                <span class="form-group-addon" id="basic-addon1"><i class="icon icon-email-resend"></i></span>



                {{ Form::email('email', $value = null , $attributes = array('class'=>'form-control',

            'ng-model'=>'email',

            'required'=> 'true',

            'placeholder' => getPhrase('email'),

            'ng-class'=>'{"has-error": passwordForm.email.$touched && passwordForm.email.$invalid}',

        )) }}

    <div class="validation-error" ng-messages="passwordForm.email.$error" >

        {!! getValidationMessage()!!}

        {!! getValidationMessage('email')!!}

    </div>



            </div>

      </div>

      <div class="modal-footer">

      <div class="pull-right">        

        <button type="submit" class="btn btn-primary" ng-disabled='!passwordForm.$valid'>{{getPhrase('submit')}}</button>

        </div>

      </div>

    </div>

    {!! Form::close() !!}

  </div>

</div>