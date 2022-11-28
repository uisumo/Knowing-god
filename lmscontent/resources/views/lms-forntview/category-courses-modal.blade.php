<!-- Modal -->
<div class="modal fade course-modal" id="coursesModal" tabindex="-1" role="dialog" aria-labelledby="coursesModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="coursesModalLabel">{{getPhrase('Courses')}}  </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>			
			
			<div class="modal-body">
				<div id="coursesList"></div>
			</div>
			<?php /* ?>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{getPhrase('Close')}}</button>				
			</div>
			<?php */ ?>
		</div>
	</div>
</div>
<!-- /Modal -->