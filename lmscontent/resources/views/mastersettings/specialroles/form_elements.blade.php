 				
				    <fieldset class="form-group">
						{{ Form::label('role_title', getphrase('role_title')) }}
						<span class="text-red">*</span>
						{{ Form::text('role_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Maths',
							'ng-model'=>'role_title', 
							'ng-pattern' => getRegexPattern('name'),
							'required'=> 'true', 
							'ng-class'=>'{"has-error": formSubjects.role_title.$touched && formSubjects.role_title.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '40',
						)) }}
						<div class="validation-error" ng-messages="formSubjects.role_title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>
					
					<fieldset class="form-group">
						{{ Form::label('description', getphrase('description')) }}
						<span class="text-red">*</span>
						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Maths',
							'ng-model'=>'description',
							'ng-class'=>'{"has-error": formSubjects.description.$touched && formSubjects.description.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '500',
						)) }}
						<div class="validation-error" ng-messages="formSubjects.description.$error" >
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>
					
					</fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button" 
							ng-disabled='!formSubjects.$valid'>{{ $button_name }}</button>
						</div>
		 