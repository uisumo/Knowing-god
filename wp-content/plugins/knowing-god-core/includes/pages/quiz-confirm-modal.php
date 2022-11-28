<!-- Modal -->
<div class="modal fade" id="quizModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e( 'Take Quiz', 'knowing-god' ); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
			<?php 
			esc_html_e( 'Do you want to take quiz?', 'knowing-god' );
			$quiz_id = get_post_meta( get_the_ID(), 'quiz_id', true );
			?>
			</div>
			<div class="modal-footer">
				<span id="quizConfirmLink">
				<?php if ( $quiz_id > 0 ) :
				global $wpdb;
				$quiz_row = $wpdb->get_row( sprintf( "SELECT * FROM quizzes WHERE id = %d", $quiz_id  ) );
					if ( $quiz_row ) :
					?>
					<a class="btn btn-secondary" href="<?php echo esc_url( knowing_god_urls( 'take-quiz' ) ) . $quiz_row->slug; ?>"><?php esc_html_e( 'Yes', 'knowing-god' ); ?></a>
					<?php 
					endif;
				endif; ?>
				</span>
				
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php esc_html_e( 'No', 'knowing-god' ); ?></button>
			</div>
			
		</div>
	</div>
</div>
<!-- /Modal -->
