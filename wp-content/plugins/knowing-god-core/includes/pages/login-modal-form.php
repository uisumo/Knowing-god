<!-- Modal -->
<div class="modal fade" id="loginModalForm" tabindex="-1" role="dialog" aria-labelledby="loginModalFormLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="loginModalFormLabel"><?php esc_html_e( 'Login Here', 'knowing-god' ); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>		
			
			<form name="formLogin" id="formLogin" method="post" action="">
			<div class="modal-body">
			   <div class="modal-login-layout">
				<div class="form-group">
					<input type="text" name="email" id="email" placeholder="<?php echo esc_attr__( 'User name OR Email', 'knowing-god' ); ?>" required>
				</div>

				<div class="form-group">
					<input type="password" name="password" id="password" placeholder="<?php echo esc_attr__( 'Password', 'knowing-god' ); ?>" required>
				</div>
				<input type="hidden" name="redirecturl" id="redirecturl" value="<?php echo knowing_god_get_current_url(); ?>">
				<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
				<div class="text-center">				
				<button type="button" class="btn button btn-info" onclick="ajaxLogin()"><?php esc_html_e( 'Login', 'knowing-god' ); ?></button>				
				</div>
			</div>

			</div>
			<div class="modal-footer">
				<div class="  st-login-tags col-sm-12">
					<a href="javascript:void(0);" onclick="openforgot()"><?php esc_html_e( 'Forgot Password?', 'knowing-god' ); ?></a>
					<?php
					if ( get_option( 'users_can_register' ) ) : ?>
					&nbsp; | &nbsp;  <a href="<?php echo esc_url( knowing_god_urls('registration') ); ?>">
					<?php esc_html_e( 'Don\'t have an account? Register', 'knowing-god' ); ?>
					<?php endif; ?>
					</a>
				</div>
				<input type="hidden" name="redirect_url" id="redirect_url" value="">

			</div>
			</form>
			
		</div>
	</div>
</div>
<!-- /Modal -->
