 					 
 					 <fieldset class="form-group">						
						{{ Form::label('category', getphrase('category_name')) }}
						<span class="text-red">*</span>
						{{ Form::text('category', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_category_name'),
							'ng-model'=>'category', 
							'ng-pattern' => getRegexPattern('name'),
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'required'=> 'true', 
							'ng-class'=>'{"has-error": formCategories.category.$touched && formCategories.category.$invalid}',
							 
							)) }}
							<div class="validation-error" ng-messages="formCategories.category.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>
					
					<fieldset class="form-group">						
						{{ Form::label('category_order', getphrase('category_order')) }}
						
						{{ Form::text('category_order', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_category_name'),
							'ng-model'=>'category_order',
							'ng-class'=>'{"has-error": formCategories.category_order.$touched && formCategories.category_order.$invalid}',
							 
							)) }}
							<div class="validation-error" ng-messages="formCategories.category.$error" >
	    					{!! getValidationMessage()!!}
							</div>
					</fieldset>
					
					<?php /* ?>
					<fieldset class="form-group">						
						{{ Form::label('subject_id', getphrase('subject')) }}
						<span class="text-red">*</span>
						{{Form::select('subject_id', $subjects, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'subject_id',
						'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.subject_id.$touched && formLms.subject_id.$invalid}',
						]) }}
							<div class="validation-error" ng-messages="formCategories.subject_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					<?php */ ?>
					
					<fieldset class="form-group" >
						{{ Form::label('category_status', getPhrase( 'status' ) ) }}
						<span class="text-red">*</span>
						<?php
						$statuses = array(
							'active' => getPhrase( 'Active' ),
							'inactive' => getPhrase( 'In-active' ),
						);
						?>
						{{Form::select('category_status', $statuses, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'category_status',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.category_status.$touched && formLms.category_status.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.category_status.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
 			 
 					  <fieldset class="form-group" >
				   {{ Form::label('category', getphrase('image')) }}
				         <input type="file" class="form-control" name="catimage" 
				         accept=".png,.jpg,.jpeg" id="image_input">
				          
				          
				    </fieldset>

				     <fieldset class="form-group" >
					@if($record)	
				   		@if($record->image)
				         <?php $examSettings = getExamSettings(); ?>
				         <img src="{{ PREFIX.$examSettings->categoryImagepath.$record->image }}" height="100" width="100" >

				         @endif
				     @endif
					

				    </fieldset>

				  
					<fieldset class="form-group">
						
						{{ Form::label('description', getphrase('description')) }}
						
						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => 'Description')) }}
					</fieldset>
						
					</fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formCategories.category.$valid'>{{ $button_name }}</button>
						</div>
		 