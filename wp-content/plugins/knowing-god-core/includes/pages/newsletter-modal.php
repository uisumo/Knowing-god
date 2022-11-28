<!-- Modal -->
<div class="modal fade" id="newsletterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e( 'KG News Letter', 'knowing-god' ); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="" method="post" name="newsletter" id="newsletter">

			<div class="modal-body">						
				
					<fieldset class="form-group">
						<input type="email" class="form-control" name="newsletteremail" id="newsletteremail" value="" placeholder="<?php esc_html_e( 'Email *', 'knowing-god' ); ?>" required>						
					</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php esc_html_e( 'Close', 'knowing-god' ); ?></button>
				<button type="button" class="btn btn-success" onclick="saveNewsletter()"><?php esc_html_e( 'Submit', 'knowing-god' ); ?></button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- /Modal -->