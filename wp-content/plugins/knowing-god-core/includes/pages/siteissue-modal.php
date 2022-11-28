<!-- Modal -->
<div class="modal fade" id="siteissuesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e( 'Enter Issue Description', 'knowing-god' ); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="" method="post" name="fromsiteissue" id="fromsiteissue">

			<div class="modal-body">						
				<fieldset class="form-group">
				<?php
				$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				?>
				<input type="text" class="form-control" name="issue_url" id="issue_url" value="<?php echo esc_attr( $actual_link ); ?>" placeholder="<?php esc_html_e( 'Enter URL where you find issue', 'knowing-god' ); ?>" required>
				</fieldset>
				
				<?php if( ! is_user_logged_in() ) : ?>
					<fieldset class="form-group">
						<input type="text" class="form-control" name="full_name" id="issue_full_name" value="" placeholder="<?php esc_html_e( 'Full Name *', 'knowing-god' ); ?>" required>
					</fieldset>
					<fieldset class="form-group">
						<input type="email" class="form-control" name="email" id="issue_email" value="" placeholder="<?php esc_html_e( 'Email *', 'knowing-god' ); ?>" required>						
					</fieldset>
				<?php endif; ?>
				<input type="hidden" id="issue_current_user_id" name="current_user_id" value="<?php echo get_current_user_id(); ?>">
				
				<fieldset class="form-group">
				<textarea type="text" class="form-control" name="issue_description" id="issue_description" value="<?php echo esc_attr( $actual_link ); ?>" placeholder="<?php esc_html_e( 'Enter your description here', 'knowing-god' ); ?>" rows="5"></textarea>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php esc_html_e( 'Close', 'knowing-god' ); ?></button>
				<button type="button" class="btn btn-success" onclick="saveIssue()"><?php esc_html_e( 'Submit Issue', 'knowing-god' ); ?></button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- /Modal -->