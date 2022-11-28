<!-- Modal -->
<div class="modal fade" id="translationIssueModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					{{getPhrase('Enter your request here')}}
			</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			{!! Form::open(array('url' => '', 'method' => 'POST', 'novalidate'=>'','name'=>'formComments')) !!}
			<div class="modal-body">						
				@if( ! Auth::check() )
					<fieldset class="form-group">
						{{ Form::label('full_name', getphrase('full_name')) }}
						<span class="text-red">*</span>
						{{ Form::text('full_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Jack',
							'ng-model'=>'full_name',
							'required'=> 'true', 
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'ng-class'=>'{"has-error": formUsers.full_name.$touched && formUsers.full_name.$invalid}',
						)) }}
						<div class="validation-error" ng-messages="formUsers.full_name.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>
					<fieldset class="form-group">
						{{ Form::label('email', getphrase('email')) }}
						<span class="text-red">*</span>
						{{ Form::text('email', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'email@gmail.com',
							'ng-model'=>'email',
							'id' => 'email',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formUsers.email.$touched && formUsers.email.$invalid}',
						)) }}
						<div class="validation-error" ng-messages="formUsers.email.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
				@endif
				{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'ng-model' => 'description', 'id' => 'description', 'rows'=>'5', 'placeholder' => getPhrase('Enter your Description here'))) }}
				<input type="hidden" name="item_id" id="item_id" value="{{$item_id}}">
			</div>
			<div class="modal-footer">				
				<button type="button" class="btn btn-primary" id="btn-description" ng-click="saveRequest({{$item_id}})">{{getPhrase('submit_request')}}</button>
			</div>
			{!! Form::close() !!}			
		</div>
	</div>
</div>
<!-- /Modal -->