
<?php
$counter = 1;
?>
@foreach( $subjects as $subject )
<div id="tableContent-{{$counter++}}" style="display:none">
	<div class="pathway-list">
		<h4>{{$subject->subject_title}}</h4>
		<?php
		$courses = subject_courses_new( $subject->id, array( 'order_by' => 'no' ) );
		if ( $subject->id == 17 ) {
			// print_r( $courses );
		}
		
		$completed_courses = completed_courses( '',$subject->id );
		
		$percent = 0;
		if ( $courses->count() > 0 && count( $completed_courses ) > 0 ) {
			$percent = ( count( $completed_courses ) * 100 ) / $courses->count();
		}
		// echo $percent;
		?>
		@if ( $courses->count() > 0 )
		<ol class="ol">
			@foreach($courses as $course)
			<?php
			$icon_class = 'icon icon-tick pull-right';

			// If it is completed we need to chagne this to 'icon icon-tick pull-right text-green'
			if ( in_array( $course->id, $completed_courses )) {
				$icon_class = 'icon icon-tick pull-right text-green';
			}

			?>
			<li>{{$course->title}} <span class="{{$icon_class}}"></span></li>
			@endforeach
		</ol>
		@endif
	</div>
</div>
@endforeach

<div class="profile-diamond">
	<?php
	$counter = 1;
	?>
	@foreach( $subjects as $subject )
	<?php
	$courses = subject_courses_new( $subject->id, array( 'order_by' => 'no' ) );

	$completed_courses = completed_courses( '',$subject->id );
	$percent = 0;
	$stop_color = 'fff';
	if ( $courses->count() > 0 && count( $completed_courses ) > 0 ) {
		$percent = ( count( $completed_courses ) * 100 ) / $courses->count();
	}
	if ( $percent == 100 ) {
		$stop_color = '00a17e';
		if ( $subject->subject_title == 'PathwayForward' ) {
			$stop_color = '00a3c3';
		}
		if ( $subject->subject_title == 'PathwayForever' ) {
			$stop_color = 'f8b600';
		}
	}
	?>
	<div class="diamond dimond-{{$counter}}" id="popupReturn-{{$counter++}}">
		@if( $subject->subject_title == 'PathwayStart' )
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 305.05 209.9"><defs><style>.cls-1{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#linear-gradient);}</style><linearGradient id="linear-gradient" x1="152.75" y1="208.9" x2="152.75" y2="1" gradientUnits="userSpaceOnUse">
			<stop offset="0.04" stop-color="#00a17e"/>
			<stop offset="{{$percent}}%" stop-color="#{{$stop_color}}"/></linearGradient></defs><title>Asset 4</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-1" points="158 1 2 208.9 210 208.9 303.5 1 158 1"/></g></g></svg>
		@elseif ( $subject->subject_title == 'PathwayForward' )
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 294.12 209.9"><defs><style>.cls-2{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond2);}</style><linearGradient id="diamond2" x1="147.03" y1="208.9" x2="147.03" y2="1" gradientUnits="userSpaceOnUse">
		<stop offset="0.04" stop-color="#00a3c3"/>
		<stop offset="{{$percent}}%" stop-color="#{{$stop_color}}"/></linearGradient></defs><title>Asset 5</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-2" points="84.58 1 1.48 208.9 292.58 208.9 198.98 1 84.58 1"/></g></g></svg>
		@elseif ( $subject->subject_title == 'PathwayForever' )
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 305.2 211"><defs><style>.cls-3{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond3);}</style><linearGradient id="diamond3" x1="152.37" y1="210" x2="152.37" y2="1" gradientUnits="userSpaceOnUse">
			<stop offset="0.04" stop-color="#f8b600"/>
			<stop offset="{{$percent}}%" stop-color="#{{$stop_color}}"/></linearGradient></defs><title>Asset 3</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-3" points="1.57 1 141.57 1 303.17 210 98.57 210 1.57 1"/></g></g></svg>
		@endif
	</div>
	@endforeach

	<?php
	$counter = 1;
	?>
	@foreach( $subjects as $subject )
	<?php
	$courses = subject_courses_new( $subject->id, array( 'order_by' => 'no' ) );

	$completed_courses = completed_courses( '',$subject->id );
	$percent = 0;
	$stop_color = 'fff';
	if ( $courses->count() > 0 && count( $completed_courses ) > 0 ) {
		$percent = ( count( $completed_courses ) * 100 ) / $courses->count();
	}
	if ( $percent == 100 ) {
		$stop_color = '00a17e';
		if ( $subject->subject_title == 'PathwayForward' ) {
			$stop_color = '00a3c3';
		}
		if ( $subject->subject_title == 'PathwayForever' ) {
			$stop_color = 'f8b600';
		}
	}
	?>
	@if ( $subject->subject_title == 'PathwayForever' )
	<div class="diamond dimond-4">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 347.75 454.85"><defs><style>.cls-4{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond4);}</style><linearGradient id="diamond4" x1="174.13" y1="1.01" x2="174.13" y2="449.91" gradientUnits="userSpaceOnUse">
		<stop offset="0" stop-color="#f8b600"/>
		<stop offset="{{$percent}}%" stop-color="#{{$stop_color}}"/></linearGradient></defs><title>Asset 2</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-4" points="137.73 1.01 2.53 449.91 345.73 2.71 137.73 1.01"/></g></g></svg>
	</div>
	@endif
	@if ( $subject->subject_title == 'PathwayForward' )
	<div class="diamond dimond-5">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 304.14 474.58"><defs><style>.cls-5{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond5);}</style><linearGradient id="diamond5" x1="155.61" y1="3.3" x2="155.61" y2="471.34" gradientTransform="translate(-3.57 2.36) rotate(-0.87)" gradientUnits="userSpaceOnUse">
		<stop offset="0" stop-color="#00a3c3"/>
		<stop offset="{{$percent}}%" stop-color="#{{$stop_color}}"/></linearGradient></defs><title>Asset 6</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-5" points="1.38 5.62 302.78 1.02 157.18 471.32 1.38 5.62"/></g></g></svg>
	</div>
	@endif

	@if( $subject->subject_title == 'PathwayStart' )
	<div class="diamond dimond-6">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 347.72 454.94"><defs><style>.cls-6{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond6);}</style><linearGradient id="diamond6" x1="173.56" y1="1.01" x2="173.56" y2="449.91" gradientUnits="userSpaceOnUse">
		<stop offset="0" stop-color="#00a17e"/>
		<stop offset="{{$percent}}%" stop-color="#{{$stop_color}}"/></linearGradient></defs><title>Asset 7</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-6" points="2.02 2.71 206.51 1.01 345.12 449.91 2.02 2.71"/></g></g></svg>
	</div>
	@endif
	@endforeach
</div>