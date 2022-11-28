<?php
$title = get_the_excerpt( get_the_ID( $post_id ) );
?>
<ul class="socialshare">
<li><a href="https://twitter.com/intent/tweet?text=<?php  echo htmlspecialchars( urlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . '&url=' . urlencode( esc_url( get_permalink( $post_id ) ) ) . '&via=' . urlencode( get_bloginfo( 'name' ) ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-twitter"></i></a></li>

<li><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( esc_url( get_permalink( $post_id ) ) ); ?>&title=<?php echo urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-facebook"></i></a></li>

<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( get_permalink( $post_id ) ) . '&amp;media=' . ( ! empty( $image[0] ) ? $image[0] : '' ) . '&description=' . urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-pinterest"></i></a></li>


<li><a href="http://plus.google.com/share?url=<?php echo  esc_url( get_permalink( $post_id ) ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-google-plus"></i></a></li>

</ul>