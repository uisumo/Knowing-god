<!-- Modal -->
<div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e( 'Enter Your Request Here', 'knowing-god' ); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<fieldset class="form-group">
					<label for="full_name"><?php esc_html_e( 'Full Name', 'knowing-god' ); ?></label>
					<span class="text-red">*</span>
					<input class="form-control" placeholder="<?php esc_html_e( 'Jack', 'knowing-god' ); ?>" required="true" name="full_name" id="full_name" type="text">				
				</fieldset>
				<fieldset class="form-group">
					<label for="email"><?php esc_html_e( 'Email', 'knowing-god' ); ?></label>
					<span class="text-red">*</span>
					<input class="form-control" placeholder="email@gmail.com" id="email" required="true" name="email" type="text">				
				</fieldset>
				<textarea class="form-control" id="description" rows="5" placeholder="<?php esc_html_e( 'Enter Your Description Here', 'knowing-god' ); ?>" name="description" cols="50"></textarea>
				<input name="item_id" id="item_id" value="" type="hidden">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" id="btn-description" onclick="saveRequest()"><?php esc_html_e( 'Send Request', 'knowing-god' ); ?></button>
			</div>
			
		</div>
	</div>
</div>
<!-- /Modal -->
