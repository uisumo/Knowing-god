<!-- Modal -->
<div class="modal fade" id="startcourseModal" tabindex="-1" role="dialog" aria-labelledby="startcourseModalLabel" aria-hidden="true" style="z-index:999999;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startcourseModalLabel">Start Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
			<div class="modal-body">
				{!! Form::open(array('url' => URL_START_COURSE, 'method' => 'POST', 'name'=>'frmstartCourse ', 'novalidate'=>'')) !!}
				
				<?php $tosentUsers = array();
				if ( Auth::check() ) {
				$start_course_users = DB::table('users')->where( 'status', '=', 'activated' )->where( 'privacy', '=', 'public' )->where( 'role_id', '=', STUDENT_ROLE_ID )->where( 'id', '!=', Auth::User()->id );
				
				?>
				<div class="form-group">
				 @if($start_course_users->count() > 0)
					
						<?php foreach($start_course_users->get() as $user) {
								$tosentUsers[ $user->id ] = $user->name; 
							}
						?>
					 {!! Form::label('Share with', 'Share with', ['class' => 'control-label']) !!}
					{{Form::select('recipients[]', $tosentUsers, null, ['class'=>'form-control select2', 'name'=>'recipients[]', 'multiple'=>'true'])}}
					@endif
				</div>
				<?php } ?>
				<div class="form-group">					
					<input style="display:inline-block;" type="checkbox" data-toggle="toggle" name="notify_author" id="notify_author" data-onstyle="success" data-offstyle="default" checked>
					{!! Form::label('notify_author', 'Notify Author', ['class' => 'control-label']) !!}
				</div>
				<input type="hidden" name="start_course_slug" id="start_course_slug" value="">
				<div class="alert alert-info">
				  <strong>Info!</strong> Please note you are starting this course. Keep tracking your course in 'My Courses' Page
				</div>
				<div class="text-center mt-3">
					{!! Form::submit('Start', ['class' => 'btn btn-primary']) !!}
				</div>
				
				{!! Form::close() !!}
			</div>
        </div>
    </div>
</div>
<!-- /Modal -->