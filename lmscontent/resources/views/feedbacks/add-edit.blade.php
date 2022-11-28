@extends($layout)

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-3">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
                            <li class="breadcrumb-item active">{{getPhrase('feedback_form')}}</li>
                        </ol>
                    </div>
                </div>
                    @include('errors.errors')
                <!-- /.row -->
                <div class="expand-card card-normal mt-3">
                <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0 mt-3">{{ $title }}</h4>
                                </div>
                                <div class="card-body">
                    <?php $button_name = getPhrase('send'); ?>

                    {!! Form::open(array('url' => URL_FEEDBACK_SEND, 'method' => 'POST', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
                    <div class="row">
                      <fieldset class="form-group col-md-12">

                        {{ Form::label('title', getphrase('title')) }}
                        <span class="text-red">*</span>
                        {{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('title'),
                            'ng-model'=>'title',
                            'ng-pattern'=>getRegexPattern('name'),
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formQuiz.title.$touched && formQuiz.title.$invalid}',
                            'ng-minlength' => '4',
                            'ng-maxlength' => '45',
                            )) }}
                        <div class="validation-error" ng-messages="formQuiz.title.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('pattern')!!}
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('maxlength')!!}
                        </div>
                    </fieldset>
                    </div>

                    <div class="row">
                    <fieldset class="form-group col-md-12">

                        {{ Form::label('subject', getphrase('subject')) }}
                        <span class="text-red">*</span>
                        {{ Form::text('subject', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('subject'),
                            'ng-model'=>'subject',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formQuiz.subject.$touched && formQuiz.subject.$invalid}',
                            'ng-minlength' => '2',
                            'ng-maxlength' => '40',
                            )) }}
                        <div class="validation-error" ng-messages="formQuiz.subject.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('pattern')!!}
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('maxlength')!!}
                        </div>
                    </fieldset>
                 </div>

                    <div class="row">
                     <fieldset class="form-group col-md-12">
                     {{ Form::label('description', getphrase('description')) }}
                        <span class="text-red">*</span>
                             <textarea name="description" ng-model="description"
                             required="true" class='form-control' rows="5"></textarea>
                        <div class="validation-error ckeditor" ng-messages="formQuiz.description.$error"  >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('number')!!}
                        </div>
                    </fieldset>
                    </div>
                        <div class="buttons text-center mt-4">
                            <button class="btn btn-lg btn-primary btn-min-width"
                            ng-disabled='!formQuiz.$valid'>{{ $button_name }}</button>
                        </div>
                    {!! Form::close() !!}
                    </div>

                </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
@stop

@section('footer_scripts')
 @include('common.validations')
@stop
