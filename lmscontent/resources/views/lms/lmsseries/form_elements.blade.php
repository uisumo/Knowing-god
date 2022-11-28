<div class="row">

 					 <fieldset class="form-group col-md-6">

						

						{{ Form::label('title', getphrase('title')) }}

						<span class="text-red">*</span>
						<?php
						$placeholder = getPhrase('course_title');
						if( $is_module == 'yes' ) {
							$placeholder = getPhrase('module_title');
						}
						?>

						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => $placeholder,

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
						<?php
						$placeholder = getPhrase('course_sub_title');
						if( $is_module == 'yes' ) {
							$placeholder = getPhrase('module_sub_title');
						}
						?>
						{{ Form::text('sub_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => $placeholder,
							'ng-model'=>'sub_title',
							'required'=> 'true', 
							'ng-class'=>'{"has-error": formLms.sub_title.$touched && formLms.sub_title.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							)) }}
						<div class="validation-error" ng-messages="formLms.sub_title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>

					
					@if ( $is_module == 'no' )
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
						{{ Form::label('privacy', getPhrase( 'privacy' ) ) }}
						<span class="text-red">*</span>
						<?php
						$privacy = array(
							'public' => getPhrase( 'Public' ),
							'loginrequired' => getPhrase( 'login_required' ),
							'infodisplay' => getPhrase( 'notice_display' ),
						);
						?>
						{{Form::select('privacy', $privacy, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'privacy',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.privacy.$touched && formLms.privacy.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.privacy.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					@endif
					
					<fieldset class="form-group col-md-6">
						{{ Form::label('display_order', getphrase('display_order')) }}
						
						{{ Form::number('display_order', $value = 0 , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('display_order'),
						'min' => 0,
						'ng-class'=>'{"has-error": formLms.display_order.$touched && formLms.display_order.$invalid}',
							)) }}
					</fieldset>
					@if( $is_module == 'no' )
					 <fieldset class="form-group col-md-6" >
						{{ Form::label('lms_category_id', getPhrase( 'category' ) ) }}
						<span class="text-red">*</span>
						{{Form::select('lms_category_id', $categories, null, ['placeholder' => getPhrase('select'),
						'class'=>'form-control', 
						'id' => 'lms_category_id',
						'ng-model'=>'lms_category_id',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.lms_category_id.$touched && formLms.lms_category_id.$invalid}',
							'onChange'=>'getSerieses()',
						]) }}
						<div class="validation-error" ng-messages="formLms.lms_category_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					
					@if ( lmsmode() == 'series' )
					<?php				
					if ( $record ) {
					$serieses = array_pluck(App\LmsSeriesMaster::where( 'lms_category_id', '=', $record->lms_category_id )->get(),'title', 'id');
					} else {
					$serieses = array();	
					}
					?>
					<fieldset class="form-group col-md-6" >
						{{ Form::label('lms_series_master_id', getPhrase( 'series' ) ) }}
						<span class="text-red">*</span>
						{{Form::select('lms_series_master_id', $serieses, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'lms_series_master_id',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.lms_series_master_id.$touched && formLms.lms_series_master_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.lms_series_master_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					@endif
					@endif
					
					@if( $is_module == 'yes' )
						
						<?php
						if ( $course ) {
							echo '<input type="hidden" name="parent_id" id="parent_id" value="' . $course->id . '">';
						} else {
						$courses = array_pluck( App\LmsSeries::where( 'parent_id', '=', '0' )->orderBy( 'title', 'asc' )->get(), 'title', 'id' );
						?>
						<fieldset class="form-group col-md-6" >
							{{ Form::label('parent_id', getPhrase( 'course' ) ) }}
							<span class="text-red">*</span>
							{{Form::select('parent_id', $courses, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
							'ng-model'=>'parent_id',
								'required'=> 'true',
								'ng-class'=>'{"has-error": formLms.parent_id.$touched && formLms.parent_id.$invalid}',
							]) }}
							<div class="validation-error" ng-messages="formLms.parent_id.$error" >
								{!! getValidationMessage()!!}
							</div>
						</fieldset>
						<?php } ?>
					@endif
					
					<!-- <input type="hidden" name="is_paid" value="0"> -->
					<input type="hidden" name="start_date" value="">
					<input type="hidden" name="end_date" value="">
					<input type="hidden" name="validity" value="-1">
					<!-- <input type="hidden" name="cost" value="0"> -->
				    </div>

				 
				
				<div class="row">

					<?php $payment_options = array('1'=>'Paid', '0'=>'Free'); ?>
					 <fieldset class="form-group col-md-6" >
						{{ Form::label('is_paid', getphrase('is_paid')) }}
						<span class="text-red">*</span>
						{{Form::select('is_paid', $payment_options, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'is_paid',
							'required'=> 'true',
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '20',
							'ng-class'=>'{"has-error": formLms.is_paid.$touched && formLms.is_paid.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.is_paid.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					<fieldset class="form-group col-md-3" ng-if="is_paid==1">
						{{ Form::label('cost', getphrase('cost')) }}
						<span class="text-red">*</span>
						{{ Form::number('cost', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '40',
							'min'=>'0',
						'ng-model'=>'cost', 
						'required'=> 'true', 
						'string-to-number'=>'true',
						'ng-class'=>'{"has-error": formLms.cost.$touched && formLms.cost.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formLms.cost.$error" >
							{!! getValidationMessage()!!}
							{!! getValidationMessage('number')!!}
						</div>
					</fieldset>
					
					<?php
					$special_roles = array_pluck( DB::table('specialroles')->get(),  'role_title', 'role_code' );
					$selected = array();
					if ( $record && ! empty( $record->free_for ) ) {
						$selected = explode(',', $record->free_for);
					}
					?>
					<fieldset class="form-group col-md-3" ng-if="is_paid==1">
						{{ Form::label('free_for', getphrase('free_for')) }}<br>
						@foreach( $special_roles as $special_role_key => $special_role_value)							
							{{Form::checkbox('free_for[]', $special_role_key, (in_array($special_role_key, $selected) ? true : false))}} {{$special_role_value}}<br>
						@endforeach
					</fieldset>
					
					<fieldset class="form-group col-md-6">
							{{ Form::label('total_items', getphrase('total_lessons')) }}
							<span class="text-red">*</span>
							{{ Form::text('total_items', $value = null , $attributes = array('class'=>'form-control','readonly'=>'true' ,'placeholder' => getPhrase('It will be updated by adding the lessons'))) }}
					</fieldset>



 					<fieldset class="form-group col-md-4" >
					   {{ Form::label('image', getphrase('image')) }}
					   <span class="text-red">*</span>
					   <input type="file" class="form-control" name="image" 
							  accept=".png,.jpg,.jpeg,.gif" id="image_input">
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
				<fieldset class="form-group col-md-4" >

				   {{ Form::label('image_icon', getphrase('image_icon')) }}

				         <input type="file" class="form-control" name="image_icon" 
				          accept=".png,.jpg,.jpeg" id="image_icon_input">

				          

				         <div class="validation-error" ng-messages="formCategories.image_icon.$error" >

	    					{!! getValidationMessage('image_icon')!!}

    				 

						</div>

				    </fieldset>



				     <fieldset class="form-group col-md-2" >

					@if($record)

				   		@if($record->image_icon)

				         <?php $examSettings = getExamSettings(); ?>

				         <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$record->image_icon }}" height="100" width="100" >



				         @endif

				     @endif

				    </fieldset>
					</div>

 
			

 					<div class="row">

					<fieldset class="form-group  col-md-6">
						{{ Form::label('short_description', getphrase('short_description')) }}
						<span class="text-red">*</span>
						{{ Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => getPhrase('short_description' ), 'ng-modal' => 'short_description', 'ng-maxlength' => '60' )) }}
						<div class="validation-error" ng-messages="formLms.short_description.$error" >
								{!! getValidationMessage('maxlength')!!}
							</div>
					</fieldset>

					<fieldset class="form-group  col-md-6">
						{{ Form::label('description', getphrase('description')) }}
						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => getPhrase('description'))) }}
					</fieldset>



					</div>
					
					<fieldset class="form-group col-md-6" >
						{{ Form::label('status', getPhrase( 'status' ) ) }}
						<span class="text-red">*</span>
						<?php
						$statuses = array(
							'active' => getPhrase( 'Active' ),
							'inactive' => getPhrase( 'In-active' ),
						);
						?>
						{{Form::select('status', $statuses, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'status',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.status.$touched && formLms.status.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.status.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					
					<input type="hidden" name="course_type" value="{{$course_type}}">

						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button">{{ $button_name }}</button>

						</div>

		 
