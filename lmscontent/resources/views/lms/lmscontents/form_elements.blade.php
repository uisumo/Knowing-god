 <?php 
 $settings = getSettings('lms');
 $upload_max_filesize = ini_get('upload_max_filesize') . 'B';
 ?>
 			
				<div class="row">
 					 <fieldset class="form-group col-md-6">

						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'God Speaks',
						'ng-model'=>'title',
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',

						)) }}
						<div class="validation-error" ng-messages="formLms.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>
					
					<fieldset class="form-group col-md-6">
						{{ Form::label('content_sub_title', getphrase('sub_title')) }}
						
						{{ Form::text('content_sub_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Love of Jesus',
						'ng-model'=>'content_sub_title',
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'ng-class'=>'{"has-error": formLms.content_sub_title.$touched && formLms.content_sub_title.$invalid}',

						)) }}
						<div class="validation-error" ng-messages="formLms.content_sub_title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>
					
					<fieldset class="form-group col-md-6">
						{{ Form::label('reference', getphrase('reference')) }}
						
						{{ Form::text('reference', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Mark 2:1-12',
						'ng-model'=>'reference',
							'required'=> 'true',
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'ng-class'=>'{"has-error": formLms.reference.$touched && formLms.reference.$invalid}',

						)) }}
						<div class="validation-error" ng-messages="formLms.reference.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>
					
					<fieldset class="form-group col-md-6">
						{{ Form::label('subject_id', getphrase('pathway')) }}
						<span class="text-red">*</span>
						{{Form::select('subject_id', $subjects, null, [ 'class'=>'form-control'])}}
					</fieldset>
					<input type="hidden" name="code" value="<?php echo str_random(40); ?>">
					
					<?php
					$statuses = array(
						'active' => getPhrase('active'),
						'inactive' => getPhrase('in_active'),
						);
					?>
					<fieldset class="form-group col-md-6" >
						{{ Form::label('lesson_status', getphrase('status')) }}
						<span class="text-red">*</span>
						{{Form::select('lesson_status', $statuses, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'lesson_status',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.lesson_status.$touched && formLms.lesson_status.$invalid}',

						]) }}
						<div class="validation-error" ng-messages="formLms.lesson_status.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					
					<fieldset class="form-group col-md-6" >
						{{ Form::label('parent_id', getphrase('Piece of')) }}
						
						{{Form::select('parent_id', $contents, null, ['placeholder' => getPhrase('select'),'class'=>'form-control select2', 
						'ng-model'=>'parent_id',
							'ng-class'=>'{"has-error": formLms.parent_id.$touched && formLms.parent_id.$invalid}',

						]) }}
						<div class="validation-error" ng-messages="formLms.parent_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					
				</div>
				<input type="hidden" name="course_id" value="0">
				<input type="hidden" name="module_id" value="0">
				<?php
				/*
				?>
				<div class="row">
					<?php
					$courses = array_pluck( App\LmsSeries::where( 'parent_id', '=', 0 )->get(), 'title', 'id' );
					$courses = array_prepend( $courses, getPhrase( 'please_select' ), '0' );					
					?>
					<fieldset class="form-group col-md-6">
						{{ Form::label('course_id', getphrase('course')) }}
						{{Form::select('course_id', $courses, null, [ 'class'=>'form-control', 'ng-model' => 'course_id', 'ng-change' => 'getModules()'])}}
						<?php
						if ( $record ) {
						$check = DB::table('lmsseries_data')
							->join( 'lmsseries', 'lmsseries.id', '=', 'lmsseries_data.lmsseries_id' )
							->where( 'lmsseries.parent_id', '=', 0 )
							->where( 'lmsseries_data.lmscontent_id', '=', $record->id )->get();
						if ( $check->count() > 0 ) {
							echo getPhrase( 'courses:' );
							echo '<ul>';
							foreach( $check as $course ) {
								echo '<li>'.$course->title.'</li>';
							}
							echo '</ul>';
						}
						}
						?>
					</fieldset>
					<fieldset class="form-group col-md-6">
						<?php
						$modules = array(
							'0' => getPhrase( 'please_select' ),
						);
						?>
						{{ Form::label('module_id', getphrase('module')) }}						
						{{Form::select('module_id', $modules, null, [ 'class'=>'form-control', 'id' => 'module_id'])}}
						
						<?php
						if ( $record ) {
						$check = DB::table('lmsseries_data')
							->join( 'lmsseries', 'lmsseries.id', '=', 'lmsseries_data.lmsseries_id' )
							->where( 'lmsseries.parent_id', '>', 0 )
							->where( 'lmsseries_data.lmscontent_id', '=', $record->id )->get();
						if ( $check->count() > 0 ) {
							echo getPhrase( 'modules:' );
							echo '<ul>';
							foreach( $check as $course ) {
								echo '<li>'.$course->title.'</li>';
							}
							echo '</ul>';
						}
						}
						?>
					</fieldset>
				</div>
				<?php */ ?>

 			<div class="row">
 					

					 <fieldset class="form-group  col-md-6"   >
				   {{ Form::label('image', getphrase('image')) }}
				   <span class="text-red">*</span>
				         <input type="file" class="form-control" name="image"
				          accept=".png,.jpg,.jpeg,.gif" id="image_input">
						  <small style="color:red"><i>{{getPhrase('File should be less than ' . $upload_max_filesize)}}</i></small>

				    </fieldset>

					 <fieldset class="form-group col-md-6"   >
					@if($record)
				   		@if($record->image)
				         <img src="{{ IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->image }}" height="100" width="100">
				         @endif
				     @endif
				    </fieldset>
					
					
					
			</div>

 
					<div  class="row">
				 	 <fieldset class="form-group col-md-6" >
						{{ Form::label('content_type', getphrase('audio_type')) }}
						
						{{Form::select('content_type', $settings->content_types, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'content_type',
							'required'=> 'true', 
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '20',
							'ng-class'=>'{"has-error": formLms.content_type.$touched && formLms.content_type.$invalid}',

						]) }}
						<div class="validation-error" ng-messages="formLms.content_type.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					 <fieldset ng-if="content_type=='url' || content_type=='iframe' || content_type=='video_url'|| content_type=='audio_url'" class="form-group col-md-6">
							{{ Form::label('file_path', getphrase('resource_link')) }}
							<span class="text-red">*</span>
							{{ Form::text('file_path', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Resource URL',
								'ng-model'=>'file_path',
								'required'=> 'true', 
								'ng-class'=>'{"has-error": formLms.file_path.$touched && formLms.file_path.$invalid}',

						)) }}
						<div class="validation-error" ng-messages="formLms.file_path.$error" >
	    					{!! getValidationMessage()!!}
						</div>
						</fieldset>



					<fieldset ng-if="content_type=='file' || content_type=='video' || content_type=='audio'" class="form-group col-md-6">
							{{ Form::label('lms_file', getphrase('audio_file')) }}
							<span class="text-red">*</span>
							 <input type="file" 
							 class="form-control" 
							 name="lms_file"  >
							 <small style="color:red"><i>{{getPhrase('File should be less than ' . $upload_max_filesize)}}</i></small>
							 @if($record)					
								@if($record->file_path!='')											{{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->file_path, getPhrase('download'))}} 
								@endif
							@endif						 
					</fieldset>

					</div>
					
					<div  class="row">
				 	 <fieldset class="form-group col-md-6" >
						{{ Form::label('video_type', getphrase('video_type')) }}
						<span class="text-red">*</span>
						{{Form::select('video_type', $settings->video_types, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
						'ng-model'=>'video_type',
							'required'=> 'true', 
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '20',
							'ng-class'=>'{"has-error": formLms.video_type.$touched && formLms.video_type.$invalid}',

						]) }}
						<div class="validation-error" ng-messages="formLms.video_type.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					 <fieldset ng-if="video_type=='url' || video_type=='iframe' || video_type=='video_url'|| video_type=='audio_url'" class="form-group col-md-6">
							{{ Form::label('file_path_video', getphrase('resource_link')) }}
							<span class="text-red">*</span>
							{{ Form::text('file_path_video', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Resource URL',
								'ng-model'=>'file_path_video',
								'required'=> 'true', 
								'ng-class'=>'{"has-error": formLms.file_path_video.$touched && formLms.file_path_video.$invalid}',

						)) }}
						<div class="validation-error" ng-messages="formLms.file_path_video.$error" >
	    					{!! getValidationMessage()!!}
						</div>
						</fieldset>



					<fieldset ng-if="video_type=='file' || video_type=='video' || video_type=='audio'" class="form-group col-md-6">
							{{ Form::label('lms_file_video', getphrase('video_file')) }}
							<span class="text-red">*</span>
							 <input type="file" 
							 class="form-control" 
							 name="lms_file_video"  >
							 <small style="color:red"><i>{{getPhrase('File should be less than ' . $upload_max_filesize)}}</i></small>
							 @if($record)					
								@if($record->lms_file_video!='')											{{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->lms_file_video, getPhrase('download'))}} 
								@endif
							@endif	
					</fieldset>
					
					<fieldset ng-if="video_type=='video' || video_type=='video_url'" class="form-group col-md-6">
							{{ Form::label('lms_file_video', getphrase('video_background_image')) }}
							<span class="text-red">*</span>
							 <input type="file" 
							 class="form-control" 
							 name="video_background_image"  >
							 @if($record)					
								@if($record->video_background_image!='')											{{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->video_background_image, getPhrase('download'))}} 
								@endif
							@endif	
					</fieldset>
					
					<fieldset ng-if="video_type=='video' || video_type=='video_url'" class="form-group col-md-6">
							{{ Form::label('help_text', getphrase('help_text')) }}

							{{ Form::textarea('help_text', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => getphrase( 'help_text' ) ) ) }}
					</fieldset>
					
					<fieldset ng-if="video_type=='video' || video_type=='video_url'" class="form-group col-md-6">
							{{ Form::label('quiz_id', getphrase('Quiz')) }}

							{{Form::select('quiz_id', $quizzes, null, ['placeholder' => getPhrase('select'),'class'=>'form-control', 
							'ng-model'=>'quiz_id',
								'ng-class'=>'{"has-error": formLms.quiz_id.$touched && formLms.quiz_id.$invalid}',
							]) }}
					</fieldset>
					
					</div>
					
					<div  class="row">
					<fieldset class="form-group col-md-6">
							{{ Form::label('file_word', getphrase('word')) }}
							 &nbsp;<i class="fa fa-file-word-o"></i>
							 <input type="file" class="form-control" name="file_word"  >
							 <small style="color:red"><i>{{getPhrase('File should be less than ' . $upload_max_filesize)}}</i></small>
							 @if($record)					
								@if($record->file_word!='')											{{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->file_word, getPhrase('download'))}} 
								@endif
							@endif	
					</fieldset>		 
					
					
					<fieldset class="form-group col-md-6">
							{{ Form::label('file_ppt', 'PPT') }}&nbsp;<i class="fa fa-file-powerpoint-o"></i>
							 <input type="file" class="form-control" name="file_ppt"  >
							 <small style="color:red"><i>{{getPhrase('File should be less than ' . $upload_max_filesize)}}</i></small>
							 @if($record)					
								@if($record->file_ppt!='')											{{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->file_ppt, getPhrase('download'))}} 
								@endif
							@endif	
					</fieldset>					 
										
					<fieldset class="form-group col-md-6">
							{{ Form::label('file_pdf', 'PDF') }}&nbsp;<i class="fa fa-file-pdf-o"></i>
							 <input type="file" class="form-control" name="file_pdf"  >
							 <small style="color:red"><i>{{getPhrase('File should be less than ' . $upload_max_filesize)}}</i></small>
							 @if($record)					
								@if($record->file_pdf!='')											{{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->file_pdf, getPhrase('download'))}} 
								@endif
							@endif	
					</fieldset>				 
					
					</div>

					<fieldset class="form-group">

						{{ Form::label('description', getphrase('lesson_content')) }}

						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => 'Fine description')) }}
					</fieldset>

 


						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button">{{ $button_name }}</button>
						</div>
