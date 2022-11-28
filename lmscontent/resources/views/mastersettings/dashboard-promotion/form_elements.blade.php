 				
				    <fieldset class="form-group col-md-6">
						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Maths',
							'ng-model'=>'title', 
							'ng-pattern' => getRegexPattern('name'),
							'required'=> 'true', 
							'ng-class'=>'{"has-error": formSubjects.title.$touched && formSubjects.title.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '40',
						)) }}
						<div class="validation-error" ng-messages="formSubjects.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>

					
					<fieldset class="form-group col-md-6">						
						{{ Form::label('priority', getphrase('priority')) }}
						<span class="text-red">*</span>
						{{ Form::number('priority', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'M1',
							'ng-model'=>'priority',
							'required'=> 'true', 
							'ng-class'=>'{"has-error": formSubjects.priority.$touched && formSubjects.priority.$invalid}',
							'ng-min' => '0',
						)) }}
						<div class="validation-error" ng-messages="formSubjects.subject_code.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>
					
					<div class="row">
					<fieldset class="form-group col-md-6">
							<?php $criterias = array('' => getPhrase( 'No Criteria' ), '<' => getPhrase('Less Than'), '<=' => getPhrase('Less Than OR Equal To'), '>' => getPhrase('Greater Than'), '>=' => getPhrase('Greater Than OR Equal To'), 'between' => getPhrase('Between'), '=' => getPhrase('Equal To'));?>
							{{ Form::label('courses_criteria', getphrase('courses_criteria')) }}
							<span class="text-red">*</span>
							{{Form::select('courses_criteria', $criterias, null, ['class'=>'form-control', 'ng-model' => 'courses_criteria'])}}
					</fieldset>	
					<fieldset class="form-group col-md-6" ng-if="courses_criteria!=''">
							{{ Form::label('courses_from', getphrase('courses_from')) }}
							<span class="text-red">*</span>
							{{ Form::number('courses_from', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('courses_from'),
                             "id"=>"courses_from",
							'ng-model'=>'courses_from',
							'min'=>'0',
							'string-to-number'=>'true',
							'ng-class'=>'{"has-error": formBadges.courses_from.$touched && formBadges.courses_from.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.courses_from.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					</div>
					<fieldset class="form-group col-md-6" ng-if="courses_criteria=='between'">
							{{ Form::label('courses_to', getphrase('courses_to')) }}
							<span class="text-red">*</span>
							{{ Form::number('courses_to', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('courses_to'),
                             "id"=>"courses_to",
							'ng-model'=>'courses_to',							
							'string-to-number'=>'true',
							'min'=>'0',
							'ng-class'=>'{"has-error": formBadges.courses_to.$touched && formBadges.courses_to.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.courses_to.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					
					<!-- Modules -->
					<div class="row">
					<fieldset class="form-group col-md-6">
							<?php $criterias = array('' => getPhrase( 'No Criteria' ),'<' => getPhrase('Less Than'), '<=' => getPhrase('Less Than OR Equal To'), '>' => getPhrase('Greater Than'), '>=' => getPhrase('Greater Than OR Equal To'), 'between' => getPhrase('Between'), '=' => getPhrase('Equal To'));?>
							{{ Form::label('modules_criteria', getphrase('modules_criteria')) }}
							<span class="text-red">*</span>
							{{Form::select('modules_criteria', $criterias, null, ['class'=>'form-control', 'ng-model' => 'modules_criteria'])}}
					</fieldset>	
					<fieldset class="form-group col-md-6" ng-if="modules_criteria!=''">
							{{ Form::label('modules_from', getphrase('modules_from')) }}
							<span class="text-red">*</span>
							{{ Form::number('modules_from', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('modules_from'),
                             "id"=>"modules_from",
							'ng-model'=>'modules_from',
							'min'=>'0',
							'required'=> 'true', 
							'string-to-number'=>'true',
							'ng-class'=>'{"has-error": formBadges.modules_from.$touched && formBadges.modules_from.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.modules_from.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					</div>
					<fieldset class="form-group col-md-6" ng-if="modules_criteria=='between'">
							{{ Form::label('modules_to', getphrase('modules_to')) }}
							<span class="text-red">*</span>
							{{ Form::number('modules_to', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('modules_to'),
                             "id"=>"modules_to",
							'ng-model'=>'modules_to',							
							'string-to-number'=>'true',
							'min'=>'0',
							'ng-class'=>'{"has-error": formBadges.modules_to.$touched && formBadges.modules_to.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.modules_to.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					
					<!-- Lessons -->
					<div class="row">
					<fieldset class="form-group col-md-6">
							<?php $criterias = array('' => getPhrase( 'No Criteria' ),'<' => getPhrase('Less Than'), '<=' => getPhrase('Less Than OR Equal To'), '>' => getPhrase('Greater Than'), '>=' => getPhrase('Greater Than OR Equal To'), 'between' => getPhrase('Between'), '=' => getPhrase('Equal To'));?>
							{{ Form::label('lessons_criteria', getphrase('lessons_criteria')) }}
							<span class="text-red">*</span>
							{{Form::select('lessons_criteria', $criterias, null, ['class'=>'form-control', 'ng-model' => 'lessons_criteria'])}}
					</fieldset>	
					<fieldset class="form-group col-md-6" ng-if="lessons_criteria!=''">
							{{ Form::label('lessons_from', getphrase('lessons_from')) }}
							<span class="text-red">*</span>
							{{ Form::number('lessons_from', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('lessons_from'),
                             "id"=>"lessons_from",
							'ng-model'=>'lessons_from',
							'min'=>'0',
							'string-to-number'=>'true',
							'ng-class'=>'{"has-error": formBadges.lessons_from.$touched && formBadges.lessons_from.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.lessons_from.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					</div>
					<fieldset class="form-group col-md-6" ng-if="lessons_criteria=='between'">
							{{ Form::label('lessons_to', getphrase('lessons_to')) }}
							<span class="text-red">*</span>
							{{ Form::number('lessons_to', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('lessons_to'),
                             "id"=>"lessons_to",
							'ng-model'=>'lessons_to',							
							'string-to-number'=>'true',
							'min'=>'0',
							'ng-class'=>'{"has-error": formBadges.lessons_to.$touched && formBadges.lessons_to.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.lessons_to.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					
					<fieldset class="form-group col-md-6">
							{{ Form::label('star_symbol', getphrase('star_symbol_lessons_completed')) }}
							<span class="text-red">*</span>
							{{ Form::number('star_symbol', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('star_symbol'),
                             "id"=>"star_symbol",						
							'string-to-number'=>'true',
							'min'=>'0',
							'ng-class'=>'{"has-error": formBadges.star_symbol.$touched && formBadges.star_symbol.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.star_symbol.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					
					<fieldset class="form-group col-md-6">
							{{ Form::label('pathway_symbol', getphrase('pathway_pin_lessons_completed')) }}
							<span class="text-red">*</span>
							{{ Form::number('pathway_symbol', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('pathway_symbol'),
                             "id"=>"pathway_symbol",						
							'string-to-number'=>'true',
							'min'=>'0',
							'ng-class'=>'{"has-error": formBadges.pathway_symbol.$touched && formBadges.pathway_symbol.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.pathway_symbol.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
					
					<fieldset class="form-group col-md-6">
							{{ Form::label('crown_symbol', getphrase('crown_symbol_lessons_completed')) }}
							<span class="text-red">*</span>
							{{ Form::number('crown_symbol', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('crown_symbol'),
                             "id"=>"crown_symbol",						
							'string-to-number'=>'true',
							'min'=>'0',
							'ng-class'=>'{"has-error": formBadges.crown_symbol.$touched && formBadges.crown_symbol.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formBadges.crown_symbol.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>                  
					</fieldset>
				
				<div class="buttons text-center">
					<button class="btn btn-lg btn-success button" 
					>{{ $button_name }}</button>
				</div>
		 