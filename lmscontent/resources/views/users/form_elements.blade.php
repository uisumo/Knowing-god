
                    <fieldset class="form-group">
                        {{ Form::label('first_name', getphrase('first_name')) }}
                        <span class="text-red">*</span>
                        {{ Form::text('first_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Jack',
                            'ng-model'=>'first_name',
                            'required'=> 'true',
                            'ng-pattern' => getRegexPattern("name"),
                            'ng-minlength' => '2',
                            'ng-maxlength' => '20',
                            'ng-class'=>'{"has-error": formUsers.first_name.$touched && formUsers.first_name.$invalid}',
                        )) }}
                        <div class="validation-error" ng-messages="formUsers.first_name.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('maxlength')!!}
                            {!! getValidationMessage('pattern')!!}
                        </div>
                    </fieldset>

                    <fieldset class="form-group">
                        {{ Form::label('last_name', getphrase('last_name')) }}
                        {{ Form::text('last_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Jack',
                            'ng-model'=>'last_name',
                            'ng-pattern' => getRegexPattern("name"),
                            'ng-minlength' => '2',
                            'ng-maxlength' => '20',
                            'ng-class'=>'{"has-error": formUsers.last_name.$touched && formUsers.last_name.$invalid}',
                        )) }}
                        <div class="validation-error" ng-messages="formUsers.last_name.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('maxlength')!!}
                            {!! getValidationMessage('pattern')!!}
                        </div>
                    </fieldset>



                    <?php
                    $readonly = '';
                    $username_value = null;
                    if($record){
                        $readonly = 'readonly="true"';
                        // $username_value = $record->username;
                    }
                    ?>
                     <fieldset class="form-group">
                        {{ Form::label('username', getphrase('username')) }}
                        <span class="text-red">*</span>
                        {{ Form::text('username', $value = $username_value , $attributes = array('class'=>'form-control', 'placeholder' => 'Jack',
                            'ng-model'=>'username',
                            'required'=> 'true',
                             $readonly,
                            'ng-minlength' => '2',
                            'ng-maxlength' => '20',
                            'ng-class'=>'{"has-error": formUsers.username.$touched && formUsers.username.$invalid}',
                        )) }}
                        @if ( empty( $readonly ) )
                        <div class="validation-error" ng-messages="formUsers.username.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('minlength')!!}
                            {!! getValidationMessage('maxlength')!!}
                            {!! getValidationMessage('pattern')!!}
                        </div>
                        @endif
                    </fieldset>



                     <fieldset class="form-group">
                        <?php
                        $readonly = '';
                            if(!checkRole(getUserGrade(4)))
                            $readonly = 'readonly="true"';
                        if($record)
                        {
                            $readonly = 'readonly="true"';
                        }
                        ?>
                        {{ Form::label('email', getphrase('email')) }} <span class="text-red">*</span>
						@if(checkRole(getUserGrade(5)))
						<small><a href="{{URL_CHANGE_EMAIL}}">Change Email?</a></small>@endif

                        {{ Form::email('email', $value = null, $attributes = array('class'=>'form-control', 'placeholder' => 'jack@jarvis.com',
                            'ng-model'=>'email',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formUsers.email.$touched && formUsers.email.$invalid}',
                         $readonly)) }}
                         <div class="validation-error" ng-messages="formUsers.email.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('email')!!}
                        </div>
                    </fieldset>
					@if(checkRole(getUserGrade(2)))
						<fieldset class="form-group">
							{{ Form::label('coach_id', getphrase('coach')) }}
							
							<?php $disabled = (checkRole(getUserGrade(2))) ? '' :'disabled';
							$selected = 'subscriber';
							if($record)
								$selected = $record->coach_id;
							
							$coaches = array_pluck( DB::table('users')
							->where('current_user_role', '=', 'coach')->where('status', '=', 'activated')->get(), 'name', 'id');
							?>
							{{Form::select('coach_id', $coaches, $selected, ['placeholder' => getPhrase('select_coach'),'class'=>'form-control', $disabled,
								'ng-model'=>'coach_id',
								'required'=> 'true',
								'ng-class'=>'{"has-error": formUsers.coach_id.$touched && formUsers.coach_id.$invalid}'
							 ])}}
							  <div class="validation-error" ng-messages="formUsers.current_user_role.$error" >
								{!! getValidationMessage()!!}
							</div>
						</fieldset>
					@endif

					@if(checkRole(getUserGrade(2)))
                     <fieldset class="form-group">
                     {{ Form::label('password', getphrase('password')) }}

                        <span class="text-red">*</span>

                        {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',

                                'placeholder' => getPhrase("password"),

                                'ng-model'=>'password',

                                'required'=> 'true',

                                'ng-class'=>'{"has-error": formUsers.password.$touched && formUsers.password.$invalid}',

                                'ng-minlength' => 5

                            )) }}

                        <div class="validation-error" ng-messages="formUsers.password.$error" >

                            {!! getValidationMessage()!!}

                            {!! getValidationMessage('password')!!}

                        </div>


                    </fieldset>

                     <fieldset class="form-group">
                     {{ Form::label('confirm_password', getphrase('confirm_password')) }}

                        <span class="text-red">*</span>

                        {{ Form::password('password_confirmation', $attributes = array('class'=>'form-control instruction-call',

                                'placeholder' => getPhrase("confirm_password"),

                                'ng-model'=>'password_confirmation',

                                'required'=> 'true',

                                'ng-class'=>'{"has-error": formUsers.password_confirmation.$touched && formUsers.password.$invalid}',

                                'ng-minlength' => 5

                            )) }}

                        <div class="validation-error" ng-messages="formUsers.password_confirmation.$error" >

                            {!! getValidationMessage()!!}

                            {!! getValidationMessage('password')!!}

                        </div>


                    </fieldset>
					@endif					
					
					<fieldset class="form-group">
                        {{ Form::label('current_user_role', getphrase('special_role')) }}
                        <span class="text-red">*</span>
                        <?php $disabled = (checkRole(getUserGrade(2))) ? '' :'disabled';
                        $selected = 'subscriber';
                        if($record)
                            $selected = $record->current_user_role;
						
						$special_roles = array_pluck( DB::table('specialroles')->get(),  'role_title', 'role_code' );
                        ?>
                        {{Form::select('current_user_role', $special_roles, $selected, ['placeholder' => getPhrase('select_role'),'class'=>'form-control', $disabled,
                            'ng-model'=>'current_user_role',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formUsers.current_user_role.$touched && formUsers.current_user_role.$invalid}'
                         ])}}
                          <div class="validation-error" ng-messages="formUsers.current_user_role.$error" >
                            {!! getValidationMessage()!!}
                        </div>
                    </fieldset>
					
					<fieldset class="form-group">
                        {{ Form::label('current_user_level', getphrase('level')) }}
                        <span class="text-red">*</span>
                        <?php $disabled = (checkRole(getUserGrade(2))) ? '' :'disabled';
                        $selected = 'subscriber';
                        if($record)
                            $selected = $record->current_user_level;
						
						$user_levels = array(
							'subscriber' => getPhrase('subscriber'),
							'Servant Learner' => getPhrase('servant_learner'),
							'Servant' => getPhrase('servant'),
							'Servant Leader' => getPhrase('servant_leader'),
						);
                        ?>
                        {{Form::select('current_user_level', $user_levels, $selected, ['placeholder' => getPhrase('select_level'),'class'=>'form-control', $disabled,
                            'ng-model'=>'current_user_level',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formUsers.current_user_level.$touched && formUsers.current_user_level.$invalid}'
                         ])}}
                          <div class="validation-error" ng-messages="formUsers.current_user_level.$error" >
                            {!! getValidationMessage()!!}
                        </div>
                    </fieldset>




                    @if(checkRole(getUserGrade(2)))
                    <fieldset class="form-group">
                        {{ Form::label('role', getphrase('role')) }}
                        <span class="text-red">*</span>
                        <?php $disabled = (checkRole(getUserGrade(2))) ? '' :'disabled';
                        $selected = getRoleData('student');
                        if($record)
                            $selected = $record->role_id;
                        ?>
                        {{Form::select('role_id', $roles, $selected, ['placeholder' => getPhrase('select_role'),'class'=>'form-control', $disabled,
                            'ng-model'=>'role_id',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formUsers.role_id.$touched && formUsers.role_id.$invalid}'
                         ])}}
                          <div class="validation-error" ng-messages="formUsers.role_id.$error" >
                            {!! getValidationMessage()!!}
                        </div>
                    </fieldset>
                    @endif


                    <div class="row">
                    <fieldset class="form-group col-sm-6">
                        {{ Form::label('mobile_countrycode', getphrase('mobile_countrycode')) }}
                        <span class="text-red">*</span>
                        <?php $countryList = knowing_god_get_countries();
                        if($record)
                            $selected = $record->phone_code;
                        ?>
                        {{Form::select('mobile_countrycode', $countryList, $selected, ['placeholder' => getPhrase('select_country'),'class'=>'form-control',
                            'ng-model'=>'mobile_countrycode',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formUsers.mobile_countrycode.$touched && formUsers.mobile_countrycode.$invalid}'
                         ])}}
                        <div class="validation-error" ng-messages="formUsers.mobile_countrycode.$error" >
                            {!! getValidationMessage()!!}
                        </div>
                    </fieldset>

                    <fieldset class="form-group col-sm-6">
                        {{ Form::label('phone', getphrase('phone')) }}
                        <span class="text-red">*</span>
                        {{ Form::text('phone', $value = null , $attributes = array('class'=>'form-control', 'placeholder' =>
                        getPhrase('please_enter_10-15_digit_mobile_number'),
                            'ng-model'=>'phone',
                            'required'=> 'true',
                            'ng-pattern' => getRegexPattern("phone"),
                            'ng-class'=>'{"has-error": formUsers.phone.$touched && formUsers.phone.$invalid}',
                        )) }}
                        <div class="validation-error" ng-messages="formUsers.phone.$error" >
                            {!! getValidationMessage()!!}
                            {!! getValidationMessage('phone')!!}
                            {!! getValidationMessage('maxlength')!!}
                        </div>
                    </fieldset>
                    </div>

                    <div class="row">

                    @if(!checkRole(getUserGrade(5)))
                    <fieldset class="form-group col-sm-6">
                        <?php
                        $statuses = array(
                            'activated' => getPhrase( 'Active' ),
                            'registered' => getPhrase( 'Registered' ),
                            'suspended' => getPhrase( 'Suspended' ),
                        );
                        $selected = '';
                        if ( ! empty( $record ) ) {
                            $selected = $record->status;
                        }
                        ?>
                        {{ Form::label('status', getphrase('status')) }}
                        {{ Form::select('status', $value = $statuses, $selected, $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('select_user_status'),
                            )) }}
                    </fieldset>
                    @endif

                    <fieldset class="form-group col-sm-6">
                        <?php
                        $statuses = array(
                            'public' => getPhrase( 'Public' ),
                            'private' => getPhrase( 'Private' ),
                        );
                        $selected = '';
                        if ( ! empty( $record ) ) {
                            $selected = $record->privacy;
                        }
                        ?>
                        {{ Form::label('privacy', getphrase('privacy')) }}
                        {{ Form::select('privacy', $value = $statuses, $selected, $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('select_user_privacy'),
                            )) }}
                    </fieldset>



                    <fieldset class='col-sm-6'>

                        {{ Form::label('image', getphrase('image')) }}

                        <div class="form-group row">

                            <div class="col-md-6">



                    {!! Form::file('image', array('id'=>'image_input', 'accept'=>'.png,.jpg,.jpeg')) !!}
<small style="color:red"><i>{{getPhrase('File should be one of .png,.jpg,.jpeg')}}</i></small>
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

                      </div>



                        <div class="buttons text-center mt-4">

                            <button class="btn btn-lg btn-primary btn-min-width"

                            >{{ $button_name }}</button>

                        </div>
