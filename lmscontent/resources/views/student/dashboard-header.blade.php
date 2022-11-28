<div class="row">
	<div class="col-sm-12">
		<div class="profile-card" style="background: url(<?php echo IMAGES; ?>pbg.png) center no-repeat;">
			<div class="corner-ribbon">{{ Auth::user()->current_user_level }}</div>
			@if( ! empty( $button_title ) )
			<button class="btn btn-yellow btn-fund">{{$button_title}}</button>
			@endif
			<div class="media">
				<div class="profile-img">
					<img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt="{{Auth::user()->name}}" title="{{Auth::user()->name}}">
					<a href="#" class="profile-mgs-notify" ng-click="showMessages()">{{Auth::user()->newThreadsCount()}}</a>
				</div>
				<div class="media-body ml-5">
					<p>{{ $user->name}}</p>
					<p>{{$user->email}}</p>
				</div>
			</div>
		</div>
	</div>
</div>