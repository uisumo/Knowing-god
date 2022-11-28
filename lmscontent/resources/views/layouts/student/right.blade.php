<?php
$cols = 3;
$current_layout = DB::table( 'lmsmode' )->first();
if ( ! $current_layout ) {
	$current_layout = 'bothsidebars';
} else {
	$current_layout = $current_layout->layout;
}
if ( 'bothsidebars' === $current_layout ) {
	$cols = 2;
}
?>
<div class="col-md-{{$cols}}">

			<div class="sidebar-boxes" >
              <!-- Search Widget -->
              <div class="card mb-4">
                <h5 class="card-header">{{ getPhrase('search') }}</h5>
				<div class="card-body">
				   @include( 'layouts.student.search-form' )
				 </div>
              </div><!-- end search area-->
			  
			  <?php knowing_god_recent_posts(); ?>
			  <?php knowing_god_recent_posts( 'recent_articles' ); ?>
              <?php knowing_god_recent_courses(); ?>

          </div>
          </div>