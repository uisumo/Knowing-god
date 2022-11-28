<div class="expand-card card-normal ">
    <div class="card">

        <div class="card-body row">
                    <fieldset class="form-group col-sm-6">
                        {{ Form::label('title', getphrase('title')) }}
                        <span class="text-red">*</span>
                        {{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Desciple',
                            'ng-model'=>'title',
                            'required'=> 'true',
                            'ng-pattern' => getRegexPattern("name"),
                            'ng-minlength' => '2',
                            'ng-maxlength' => '80',
                            'ng-class'=>'{"has-error": formUsers.title.$touched && formUsers.title.$invalid}',
                        )) }}
                        <div class="validation-error" ng-messages="formUsers.title.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('maxlength')!!}
                            {!! getValidationMessage('pattern')!!}
                        </div>
                    </fieldset>

                    <fieldset class="form-group col-sm-6">
                        {{ Form::label('sub_title', getphrase('sub_title')) }}
                        {{ Form::text('sub_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '4 Quiet Times in Ruth',
                            'ng-model'=>'sub_title',
                            'ng-minlength' => '2',
                            'ng-maxlength' => '80',
                            'ng-class'=>'{"has-error": formUsers.sub_title.$touched && formUsers.sub_title.$invalid}',
                        )) }}
                        <div class="validation-error" ng-messages="formUsers.sub_title.$error" >
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('maxlength')!!}
                        </div>
                    </fieldset>

                    <fieldset class="form-group col-sm-12 mt-2 mb-2">
                        {{ Form::label('description', getphrase('description')) }}
                        {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase( 'Group Description' ),
                            'ng-model'=>'description',
                            'ng-pattern' => getRegexPattern("name"),
                            'ng-minlength' => '2',
                            'ng-maxlength' => '250',
                            'ng-class'=>'{"has-error": formUsers.description.$touched && formUsers.description.$invalid}',
                        )) }}
                        <div class="validation-error" ng-messages="formUsers.last_name.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('pattern')!!}
                        </div>
                    </fieldset>

                    <fieldset class="form-group col-sm-6">
                        {{ Form::label('is_public', getphrase('is_public')) }}
                        <span class="text-red">*</span>
                        <?php
                        $selected = 'no';
                        if($record)
                            $selected = $record->is_public;
                        ?>
                        {{Form::select('is_public', array( 'no' => getPhrase('No'), 'yes' => getPhrase('Yes') ), $selected, ['placeholder' => getPhrase('select_option'),'class'=>'form-control',
                            'ng-model'=>'is_public',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formUsers.is_public.$touched && formUsers.is_public.$invalid}'
                         ])}}
                        <div class="validation-error" ng-messages="formUsers.is_public.$error" >
                            {!! getValidationMessage()!!}
                        </div>
                    </fieldset>


                    <fieldset class='form-group col-sm-6'>
                        {{ Form::label('image', getphrase('image')) }}
                        <div class="form-group ">
                            <div class=" ">

                    {!! Form::file('image', array('id'=>'image_input', 'accept'=>'.png,.jpg,.jpeg', 'class' => 'form-control' )) !!}

                            </div>

                            <?php if(isset($record) && $record) {

                                  if($record->image!='') {

                                ?>

                            <div class="col-md-6">
                                <img src="{{ getProfilePath($record->image) }}" />
                            </div>
                            <?php } } ?>

                        </div>

                    </fieldset>
<div class="col-sm-12 mt-2">

                        <div class="buttons text-center">
                            <button class="btn btn-lg btn-primary button"
                            >{{ $button_name }}</button>
    </div></div>
        </div>
    </div>
</div>
