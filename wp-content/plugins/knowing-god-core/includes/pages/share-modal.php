<!-- Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e( 'Share Your Content here', 'knowing-god' ); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<?php
				$title = get_the_excerpt( get_the_ID() );
				?>
				<ul class="socialshare">
				<li><a href="https://twitter.com/intent/tweet?text=<?php  echo htmlspecialchars( urlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . '&url=' . urlencode( esc_url( get_permalink() ) ) . '&via=' . urlencode( get_bloginfo( 'name' ) ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-twitter"></i></a></li>

				<li><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( esc_url( get_permalink() ) ); ?>&title=<?php echo urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-facebook"></i></a></li>

				<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( get_permalink() ) . '&amp;media=' . ( ! empty( $image[0] ) ? $image[0] : '' ) . '&description=' . urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-pinterest"></i></a></li>


				<li><a href="http://plus.google.com/share?url=<?php echo  esc_url( get_permalink() ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-google-plus"></i></a></li>

				</ul>
			</div>
			<div class="modal-footer">
				
			</div>
			
		</div>
	</div>
</div>
<!-- /Modal -->
