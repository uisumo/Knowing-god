@if('subscriber' !== Auth::User()->current_user_role )
<div class="card r-card">
    <div class="card-header card-center-header" role="tab" id="headingTwo">
        <h4 class="mb-0">
            <a data-toggle="collapse" href="#collapseProfilechanges" aria-expanded="true" aria-controls="collapseProfilechanges">
                <span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
				@if( 'coach' === Auth::User()->current_user_role )
				{{getPhrase('my_facilitators')}}
				@else
				{{getPhrase('profile_changes')}}
				@endif
			</a>
        </h4>

    </div>
	<?php
	$request_details = DB::table('coach_requests AS cr')
		->join('users AS u', 'u.id', '=', 'cr.user_id')
		->where('u.id', '=', Auth::User()->id)->first();
	$coach = DB::table('users')->where('id', '=', Auth::User()->coach_id)->first();
	?>
    <div id="collapseProfilechanges" class="collapse show" role="tabpanel" aria-labelledby="headingTwo">
        <div class="card-body">            
            @if ( 'facilitator' === Auth::User()->current_user_role )
			<div class="row">
                
				<div class="col-sm-8">
                    <div class="profile-description">
						@if ( 'coach' === Auth::User()->current_user_role )
							Congratulations! Now You Are Coach. You Are A Leader Of Number Of Facilitators. Click <a href="{{URL_MY_FACILITATORS}}">here</a> To See Your Facilitators.
						@else
							Congratulations! Now you became facilitator. 
							@if(Auth::User()->coach_id > 0 )
								<?php
								if ( $coach ) {
									?>
									You have been assigned to a coach <a href="{{URL_USER_DETAILS_FACILITATOR . $coach->slug}}" title="{{$coach->name}}" alt="{{$coach->name}}">{{$coach->name}}</a>
									<?php
								} else {
									?>
									You have been assigned to a coach
									<?php
								}
								?>
								
							@else
								@if( $request_details )
									We have received your request for a coach. Will assign a coach soon. Please bear with us.
								@else
									If you want to coach a coach, sent us a request.
								@endif
							@endif
						@endif
					</div>
                </div>
				<div class="col-sm-4">
                    @if ( $coach )						
						<a href="{{URL_USER_DETAILS_FACILITATOR . $coach->slug}}" title="{{$coach->name}}" alt="{{$coach->name}}">
						<img src="{{ getProfilePath($coach->image, 'thumb') }}" style="border-radius:50%;width: 160px;height: 160px;" alt="{{$coach->name}}" title="{{$coach->name}}">
						<p>{{$coach->name}}</p>
						</a>
					@else
						<a href=""><h2 class="task-head ctext-center">{{getPhrase('assign_a_coach')}}</h2></a>
						@if( $request_details )
						<div class="btn btn-primary" ng-click="withdrawCoachRequest()">{{getPhrase('withdraw_request')}} </div>
						@else
						<div class="btn btn-primary" ng-click="showCoachform()">{{getPhrase('send_request')}} </div>
						@endif
					@endif
                </div>
				
            </div>
			@else
			<?php
			$my_facilitators = DB::table('users')
                            ->where('coach_id', '=', Auth::User()->id)
                            ->get();
			$count = 0;
			if ( $my_facilitators->count() > 0 ) :
			?>	
			<ul class="list-inline">
				@foreach( $my_facilitators as $my_facilitator )
				<li>
					<a href="{{URL_USER_DETAILS_COACH . $my_facilitator->slug}}">
						<div class="course-card">
							<div class="course-card-img">							
							<img src="{{ getProfilePath($my_facilitator->image,'profile')}}" alt="" class="img-responsive"></div>
							<div class="course-card-content">
								<p>{{$my_facilitator->name}}</p>
								@if ( ! empty( $my_facilitator->sub_title ) )
								<p>{{$my_facilitator->sub_title}}</p>
								@endif
							</div>
						</div>
					</a>
				</li>
				<?php $count++;
				if ( $count > 4 ) {
					break;
				}
				?>
				@endforeach
				@if ( $my_facilitators->count() > 4 )
					<li>
						<div class="media">
							<a href="{{URL_MY_FACILITATORS}}" title="{{getPhrase('my_facilitators')}}" class="course-plus-btn">
							<div class="join-btn join-btn-md">
								<i class="icon icon-plus"></i>
							</div>
							</a>							
						</div>
					</li>
				@endif
			</ul>
			<?php else: ?>
			@if ( 'coach' === Auth::User()->current_user_role )
				Congratulations! Now You Are Coach. You Are A Leader Of Number Of Facilitators. Click <a href="{{URL_MY_FACILITATORS}}">here</a> To See Your Facilitators.
			@endif
			<?php endif; ?>
			@endif
        </div>
    </div>
</div>
@endif