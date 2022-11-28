 					

 				

					<div class="row">

 					 <fieldset class="form-group col-md-6">

						

						{{ Form::label('title', getphrase('title')) }}

						<span class="text-red">*</span>

						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('series_title'),

							'ng-model'=>'title',

							'required'=> 'true', 

							'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',

							'ng-minlength' => '2',

							'ng-maxlength' => '40',

							)) }}

						<div class="validation-error" ng-messages="formLms.title.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('pattern')!!}

	    					{!! getValidationMessage('minlength')!!}

	    					{!! getValidationMessage('maxlength')!!}

						</div>

					</fieldset>
					
					<fieldset class="form-group col-md-6">
						{{ Form::label('sub_title', getphrase('sub_title')) }}
						<span class="text-red">*</span>
						{{ Form::text('sub_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('series_title'),
							'ng-model'=>'sub_title',
							'required'=> 'true', 
							'ng-class'=>'{"has-error": formLms.sub_title.$touched && formLms.sub_title.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '40',
							)) }}
						<div class="validation-error" ng-messages="formLms.sub_title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>

					
					<fieldset class="form-group col-md-6" >
						{{ Form::label('subject_id', getPhrase( 'pathway' ) ) }}
						<span class="text-red">*</span>
						{{Form::select('subject_id', $subjects, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'subject_id',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.subject_id.$touched && formLms.subject_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.subject_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					
					 <fieldset class="form-group col-md-6" >
						{{ Form::label('lms_category_id', 'Category') }}
						<span class="text-red">*</span>
						{{Form::select('lms_category_id', $categories, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'lms_category_id',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.lms_category_id.$touched && formLms.lms_category_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.lms_category_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					
					
				    </div>

				 

				<div class="row">

				 
 					<fieldset class="form-group col-md-4" >

				   {{ Form::label('image', getphrase('image')) }}

				         <input type="file" class="form-control" name="image" 
				          accept=".png,.jpg,.jpeg" id="image_input">

				          

				         <div class="validation-error" ng-messages="formCategories.image.$error" >

	    					{!! getValidationMessage('image')!!}

    				 

						</div>

				    </fieldset>



				     <fieldset class="form-group col-md-2" >

					@if($record)

				   		@if($record->image)

				         <?php $examSettings = getExamSettings(); ?>

				         <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$record->image }}" height="100" width="100" >



				         @endif

				     @endif

				    </fieldset>

			    </div>

 
			

 					<div class="row">

					<fieldset class="form-group  col-md-6">

						

						{{ Form::label('short_description', getphrase('short_description')) }}

						

						{{ Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('short_description'))) }}

					</fieldset>

					<fieldset class="form-group  col-md-6">

						

						{{ Form::label('description', getphrase('description')) }}

						

						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('description'))) }}

					</fieldset>



					</div>

						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!formLms.$valid'>{{ $button_name }}</button>

						</div>

		 
