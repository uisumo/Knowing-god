<div class="media kg-profile-media row">
<div class="col-sm-5">
	<div class="profile-img">
		<img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt="{{Auth::user()->name}}" title="{{Auth::user()->name}}">
		<?php


		$pathway_start_contents = 0;
		$pathway_forward_contents = 0;
		$pathway_forever_contents = 0;

		$pathway_start_contents_completed = 0;
		$pathway_forward_contents_completed = 0;
		$pathway_forever_contents_completed = 0;

		$pathway_start_crowns = $pathway_forward_crowns = $pathway_forever_crowns = 0;
		if ( empty( $dashboard ) ) {
			$dashboard = 'subscriber_dashboard';
		}
		$settings = DB::table( 'dashboards' )->where( 'key', '=', $dashboard )->first();
/**
 * 1) Only the next pin should be outlined in green (if it’s the pathwaystart)
 * 2) Once any individual content piece [changed from original instructions in video] is completed then a pin should fill in to show completion for that 1 thing
 * 3) when a user completes the 5th piece of content the 5 filled in pins dissapear, a pathway with a pin on it [see icon in wireframe] will appear and an outlined green pin should appear in the 2nd 'slot' and then all other pins reappear in very light gray,
 * 4) Currently a 6th offset green pin appears but it shouldn't,
 * 5) the pins arent consistently in line and in the right position symmetrically,
 * 6) as the screen resizes smaller
 */
		?>

		@foreach( $subjects as $subject )
		<ul class="{{$subject->placement_on_dashboard}}">
			<?php
			// $completed_contents = completed_contents( $subject->id );
			$completed_contents = completed_pieces_new( $subject->id );
			// dd( $completed_contents );
			if ( $subject->id == 14 ) {
				// dd( $completed_contents );
			}
			if ( $subject->subject_title == PATHWAY_START_TITLE ) {
				$pathway_contents = pathway_contents( $subject->id );
				
				if( $pathway_contents ) {
					$pathway_start_contents = $pathway_contents->count();
				}
				$pathway_start_contents_completed = count( $completed_contents );
			}
			if ( $subject->subject_title == PATHWAY_FORWARD_TITLE ) {
				$pathway_contents = pathway_contents( $subject->id );
				if( $pathway_contents ) {
					$pathway_forward_contents = $pathway_contents->count();
				}
				$pathway_forward_contents_completed = count( $completed_contents );
			}
			if ( $subject->subject_title == PATHWAY_FOREVER_TITLE ) {
				$pathway_contents = pathway_contents( $subject->id );
				if( $pathway_contents ) {
					$pathway_forever_contents = $pathway_contents->count();
				}
				$pathway_forever_contents_completed = count( $completed_contents );
			}

			// $settings = DB::table( 'dashboards' )->where( 'key', '=', 'subscriber_dashboard' )->first();
			$stars = $pathways = $crowns = $star_symbol = $pathway_symbol = $crown_symbol = 0;
			$total_symbolized = 0;
			if ( ! empty( $settings ) && ! empty( $completed_contents )  ) {
				$crown_symbol = $settings->crown_symbol;
				if ( $crown_symbol > 0 ) { // To avoid 'Division by zero' error
					$crowns = intval ( count( $completed_contents ) / $crown_symbol );
				}
				if ( $crowns > 0 ) {
					if ( $subject->subject_title == PATHWAY_START_TITLE ) {
						$pathway_start_crowns = $crowns;
					}
					if ( $subject->subject_title == PATHWAY_FORWARD_TITLE ) {
						$pathway_forward_crowns = $crowns;
					}
					if ( $subject->subject_title == PATHWAY_FOREVER_TITLE ) {
						$pathway_forever_crowns = $crowns;
					}
				}

				$remaining_pins = count( $completed_contents ) - ( $crowns * $crown_symbol );
				$star_symbol = $settings->star_symbol;
				if ( $star_symbol > 0 && $remaining_pins > 0 ) { // To avoid 'Division by zero' error
					$stars = intval ( $remaining_pins / $star_symbol );
				}

				$remaining_pins = count( $completed_contents ) - ( $crowns * $crown_symbol ) - ( $stars * $star_symbol );

				$pathway_symbol = $settings->pathway_symbol;
				if ( $pathway_symbol > 0 && $remaining_pins > 0 ) { // To avoid 'Division by zero' error
					$pathways = intval ( $remaining_pins / $pathway_symbol );
				}

				$remaining_pins = count( $completed_contents ) - ( $crowns * $crown_symbol ) - ( $stars * $star_symbol ) - ( $pathways * $pathway_symbol );

				$total_symbolized = ( $crowns * $crown_symbol ) + ( $stars * $star_symbol ) + ( $pathways * $pathway_symbol );

			}
			// echo count( $completed_contents ) . '##' . $crowns;
			// echo '<br>C:' . $crown_symbol . '##S:' . $star_symbol . '##PP:' . $pathway_symbol;
			// echo $stars;
			/*
			if ( $stars > 5 ) {
				$stars = $stars - 3;
			}
			*/
			$pin = $counter = $counter2 = 0;
			if ( $total_symbolized == 0 ) {
			?>
			<li class="{{$subject->color_class}}"><i class="icon-map-pointer icon icon-pin"></i></li>
			<?php } ?>

			@if ( ! empty( $completed_contents ) )
				@if ( $stars > 0 )
					@for( $i = 0; $i < $stars; $i++ )
					<li class="{{$subject->color_class}}"><i class="fa fa-star" title="{{$star_symbol}} {{getPhrase('lessons')}}"></i></li>
					@endfor
				@endif

				@if ( $pathways > 0 )
					@for( $i = 0; $i < $pathways; $i++ )
					<li class="{{$subject->color_class}}"><i class="icon icon-globe-pointer" title="{{$pathway_symbol}} {{getPhrase('lessons')}}"></i></li>
					@endfor
				@endif

				@foreach( $completed_contents as $completed_content )
					<?php
					if ( $counter < $total_symbolized ) {
						$counter++;
						continue;
					}
					if ( $pathways == 0 ) {
					?>
					<li class="{{$subject->color_class}}"><i class="icon-map-pointer icon icon-pin" title="{{$completed_content->title}}"></i></li>
					<?php
					}
					++$pin;
					if ( $pin > 4 ) {
						break;
					}
					?>
				@endforeach
				<?php
				// 2nd slot pins
				$start_second_start = TRUE;
				if ( $pathways > 0 ) { ?>
				<li class="icon-group">
					<ul class="group-badge">
						<?php $pin = 0; ?>
						@if ( $start_second_start == TRUE )
						<li class="{{$subject->color_class}}"><span class="icon-map-pointer icon icon-pin"></span></li>
						<?php $start_second_start = FALSE;
						++$pin;
						?>
						@endif
						<?php $counter = 0;  ?>
						@foreach( $completed_contents as $completed_content )
						<?php
						if ( $counter < $total_symbolized ) {
							$counter++;
							continue;
						}
						?>
						<li class="{{$subject->color_class}}"><i class="icon-map-pointer icon icon-pin" title="{{$completed_content->title}}"></i></li>
						<?php
						++$pin;
						if ( $pin > 5 ) {
							break;
						}
						?>
						@endforeach
						@if ( $pin  < 5 )
							<?php $c = 0 ; ?>
							@for( $ep = $pin; $ep < 5; $ep++)
								<?php /* ?>
								@if ( $c == 0 )
								<li class="{{$subject->color_class}}"><span class="icon-map-pointer icon icon-pin"></span></li>
								<?php $c++; ?>
								@else
								<li><span class="icon-map-pointer icon icon-pin"></span></li>
								@endif
								<?php */ ?>
								<li><span class="icon-map-pointer icon icon-pin"></span></li>
							@endfor
						@endif
					</ul>
				</li>
				<?php
				}
				elseif ( $pin  < 4 ) { ?>
				@for( $ep = $pin; $ep < 4; $ep++)
					<li><i class="icon-map-pointer icon icon-pin"></i></li>
				@endfor
				<?php
				}
				?>
			@else
				@for( $ep = 0; $ep < 4; $ep++ )
					<li><i class="icon-map-pointer icon icon-pin"></i></li>
				@endfor
			@endif
		</ul>
		@endforeach

		<?php
		$crown_symbol = 0;
		if ( $settings ) {
			$crown_symbol = $settings->crown_symbol;
		}

		?>
		@if ( $pathway_start_crowns > 0 || $pathway_forward_crowns > 0 || $pathway_forever_crowns > 0 )
			<ul class="crown-list">
				@if ( $pathway_start_crowns > 0 )
					@for( $c = 0; $c < $pathway_start_crowns; $c++)
					<li class="text-green"><i class="icon icon-crown" title="{{$crown_symbol . ' ' . getPhrase('piece_of_content')}}"></i></li>
					@endfor
				@endif

				@if ( $pathway_forward_crowns > 0 )
					@for( $c = 0; $c < $pathway_forward_crowns; $c++)
					<li class="text-blue" title="{{$crown_symbol . ' ' . getPhrase('piece_of_content')}}"><i class="icon icon-crown"></i></li>
					@endfor
				@endif

				@if ( $pathway_forever_crowns > 0 )
					@for( $c = 0; $c < $pathway_forever_crowns; $c++)
					<li class="text-yellow" title="{{$crown_symbol . ' ' . getPhrase('piece_of_content')}}"><i class="icon icon-crown"></i></li>
					@endfor
				@endif
			</ul>
		@endif
		<?php /* ?>
		<a href="{{URL_MESSAGES}}" class="profile-mgs-notify">{{Auth::user()->newThreadsCount()}}</a>
		<?php */ ?>
		<a href="#" class="profile-mgs-notify" ng-click="showMessages()">{{Auth::user()->newThreadsCount()}}</a>
	</div>
	</div>
	@include('student.dashboard-parts.statistics', array('dashboard' => 'subscriber'))
</div>