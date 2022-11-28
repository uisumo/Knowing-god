<div class="expand-card card-normal mt-100">
	<div class="card" >
					<div class="card-heading p-4">
						<h4>{{getPhrase('billing_details')}}</h4>
					</div>
				<?php $user = Auth::user();?>
					<div class="card-body">
							<div class="row">
								<div class="col-xs-4">{{getPhrase('name')}} : </div>
								<div class="col-xs-8"><strong>{{$user->name}}</strong></div>
							</div>
							<hr>

							<div class="row">
								<div class="col-xs-4">{{getPhrase('email')}} : </div>
								<div class="col-xs-8"><a href="mailto:{{$user->email}}"><strong>{{$user->email}}</strong></a></div>
							</div>
							@if ( $user->phone )
							<hr>
							<div class="row">
								<div class="col-xs-4">{{getPhrase('phone')}} : </div>
								<div class="col-xs-8"><strong>{{$user->phone}}</strong></div>
							</div>
							@endif


					</div>
				@if($user->address)
					<div class="card-heading p-4">
						<h2>{{getPhrase('billing_address')}}</h2>
					</div>
					

					<div class="card-body">
						<div class="row">								 
								<div class="col-xs-12"><strong>{{$user->address}}</strong></div>
							</div>
					</div>
					@endif
	</div>
</div>