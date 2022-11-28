<!-- Modal -->
<div class="modal fade modal-full full-modal" id="lessonsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="quizModalLabel">{{getPhrase('Lessons')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>			
			
			<div class="modal-body">
				<div id="lessonsContent"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{getPhrase('Close')}}</button>				
			</div>
		</div>
	</div>
</div>
<!-- /Modal -->