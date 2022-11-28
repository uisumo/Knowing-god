<?php
if ( empty( $item_id ) ) {
	$item_id = 0;
}
?>
<!-- Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
				@if(Auth::check())
					{{getPhrase('Add Comments')}}
				@else
					{{getPhrase('Comments')}}	
				@endif
			</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@if(Auth::check())
			{!! Form::open(array('url' => '', 'method' => 'POST', 'novalidate'=>'','name'=>'formComments')) !!}
			<div class="modal-body">						
				{{ Form::textarea('commnets', $value = null , $attributes = array('class'=>'form-control', 'ng-model' => 'comments', 'id' => 'comments', 'rows'=>'5', 'placeholder' => getPhrase('Enter commnets here'))) }}
				<input type="hidden" name="item_id" id="item_id" value="{{$item_id}}">
			</div>
			<div class="modal-footer">				
				<button type="button" class="btn btn-primary" id="btn-comments" ng-click="saveComments({{$item_id}}, 'comments')">{{getPhrase('submit')}}</button>
			</div>
			{!! Form::close() !!}
			@endif
			<div id="comments_list"></div>
			
		</div>
	</div>
</div>
<!-- /Modal -->