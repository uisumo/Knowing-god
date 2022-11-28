@extends('layouts.student.studentlayout')
@section('content')
		
			<div class="row">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{URL_USERS_EDIT.Auth::user()->slug}}">{{ getPhrase('profile') }}</a> / <a>{{Auth::user()->name}}</a> </li>
					</ol>
				</div>
			</div>
		  <div class="container-pad">
			<div class="row">
				<div class="col-sm-12">
					<div class="profile-card" style="background: url(<?php echo IMAGES; ?>pbg.png) center no-repeat;">
						<div class="corner-ribbon">{{ Auth::user()->current_user_level }}</div>
						<button class="btn btn-yellow btn-fund">Fund another <br>part of the Pathway!</button>
						<div class="media">
							<div class="profile-img">
								<img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt="{{Auth::user()->name}}" title="{{Auth::user()->name}}">
								<?php
								$subjects = App\Subject::get();
								?>
								@foreach( $subjects as $subject )
								<ul class="{{$subject->placement_on_dashboard}}">
									<li class="{{$subject->color_class}}"><i class="fa fa-map-marker"></i></li>
									<?php
									$completed_contents = completed_contents( $subject->id );
									?>
									@if ( ! empty( $completed_contents ) )
										@foreach( $completed_contents as $completed_content )
											<li><i class="fa fa-map-marker" title="{{$completed_content->title}}"></i></li>
										@endforeach
									@endif
								</ul>
								@endforeach
								
								<a href="{{URL_MESSAGES}}" class="profile-mgs-notify">{{Auth::user()->newThreadsCount()}}</a>
							</div>
							<div class="media-body ml-5">
								<p>{{Auth::user()->name}}</p>
								<?php
								$date_format = Corcel\Model\Option::get('date_format');
								?>
								<p>{{ getPhrase('joined') }} {{date($date_format, strtotime(Auth::user()->created_at))}}</p>
								<p>{{ getPhrase('completer_a_section') }}</p>
								<p>{{ getPhrase('join_a_group') }}</p>
								<p>{{ getPhrase('facilitate_a_group') }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div role="tablist" class="expand-card">
						<div class="card">
							<div class="card-header" role="tab" id="headingOne">
								<h4 class="mb-0">
									<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										<span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span> {{ getPhrase('profile_diamond') }}</a>
								</h4>
							</div>
							<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-4">
											<div class="join-btn join-btn-title">
											   <div class="join-btn-text">
											   {!! getPhrase('join_a_group') !!}</div>  <i class="icon icon-plus"></i>
											  </div>
										</div>
										<div class="col-sm-4">
											<div class="join-btn join-btn-title">
												<div class="join-btn-text">
													{!! getPhrase('pathway_start') !!}</div>  <i class="icon icon-plus"></i>
											  </div>
										</div>
										<div class="col-sm-4">
											@foreach( $subjects as $subject )
											<div id="tableContent" style="display:none">
												<div class="pathway-list">
													<h4>{!! getPhrase('pathway_start') !!}</h4>
													<ol class="ol">
														<li>{{ getPhrase('good_news') }} <span class="icon icon-tick pull-right text-green"></span></li>
														<li>{{ getPhrase('encountering_jesus') }} <span class="icon icon-tick pull-right text-green"></span></li>
														<li>{{ getPhrase('G.L.O.') }} <span class="icon icon-tick pull-right text-green"></span></li>
														<li>{{ getPhrase('3_easy_questions') }} <span class="icon icon-tick pull-right"></span></li>
														<li>{{ getPhrase('in_my_name') }} <span class="icon icon-tick pull-right"></span></li>
													</ol>
												</div>
											</div>
											@endforeach
											<?php /* ?>
											<div class="tableContent" style="display:none">
												<div class="pathway-list">
													<h4>{!! getPhrase('pathway_forward') !!}</h4>
													<ol class="ol">
														<li>{{ getPhrase('engage') }}<span class="icon icon-tick pull-right text-blue"></span></li>
														<li>{{ getPhrase('join_a_group') }} <span class="icon icon-tick pull-right text-blue"></span></li>
														<li>{{ getPhrase('D3D_Training') }} <span class="icon icon-tick pull-right text-yellow"></span></li>
														<li>{{ getPhrase('Rhythms_Training') }} <span class="icon icon-tick pull-right"></span></li>
														<li>{{ getPhrase('Facilitator_Training') }} <span class="icon icon-tick pull-right"></span></li>
													</ol>
												</div>
											</div>
											<?php */ ?>

											<div class="profile-diamond">
												<div class="diamond dimond-1" id="popupReturn">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 305.05 209.9"><defs><style>.cls-1{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#linear-gradient);}</style><linearGradient id="linear-gradient" x1="152.75" y1="208.9" x2="152.75" y2="1" gradientUnits="userSpaceOnUse"><stop offset="0.13" stop-color="#00a17e"/><stop offset="0.56" stop-color="#fff"/></linearGradient></defs><title>Asset 4</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-1" points="158 1 2 208.9 210 208.9 303.5 1 158 1"/></g></g></svg>
												</div>
												<div class="diamond dimond-2 popupReturn">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 294.12 209.9"><defs><style>.cls-2{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond2);}</style><linearGradient id="diamond2" x1="147.03" y1="208.9" x2="147.03" y2="1" gradientUnits="userSpaceOnUse"><stop offset="0.04" stop-color="#00a3c3"/><stop offset="0.04" stop-color="#09a6c5"/><stop offset="0.05" stop-color="#37b7d0"/><stop offset="0.06" stop-color="#62c6da"/><stop offset="0.07" stop-color="#88d4e3"/><stop offset="0.08" stop-color="#a8e0eb"/><stop offset="0.09" stop-color="#c4eaf1"/><stop offset="0.11" stop-color="#daf2f6"/><stop offset="0.13" stop-color="#ebf8fa"/><stop offset="0.15" stop-color="#f6fcfd"/><stop offset="0.18" stop-color="#fdfeff"/><stop offset="0.26" stop-color="#fff"/></linearGradient></defs><title>Asset 5</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-2" points="84.58 1 1.48 208.9 292.58 208.9 198.98 1 84.58 1"/></g></g></svg>
												</div>
												<div class="diamond dimond-3 popupReturn">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 305.2 211"><defs><style>.cls-3{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond3);}</style><linearGradient id="diamond3" x1="152.37" y1="210" x2="152.37" y2="1" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f8b600"/><stop offset="0.1" stop-color="#fff"/></linearGradient></defs><title>Asset 3</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-3" points="1.57 1 141.57 1 303.17 210 98.57 210 1.57 1"/></g></g></svg>
												</div>
												<div class="diamond dimond-4">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 347.75 454.85"><defs><style>.cls-4{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond4);}</style><linearGradient id="diamond4" x1="174.13" y1="1.01" x2="174.13" y2="449.91" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f8b600"/><stop offset="0.45" stop-color="#fff"/></linearGradient></defs><title>Asset 2</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-4" points="137.73 1.01 2.53 449.91 345.73 2.71 137.73 1.01"/></g></g></svg>
												</div>
												<div class="diamond dimond-5">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 304.14 474.58"><defs><style>.cls-5{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond5);}</style><linearGradient id="diamond5" x1="155.61" y1="3.3" x2="155.61" y2="471.34" gradientTransform="translate(-3.57 2.36) rotate(-0.87)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f8b600"/><stop offset="0.1" stop-color="#fff"/></linearGradient></defs><title>Asset 6</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-5" points="1.38 5.62 302.78 1.02 157.18 471.32 1.38 5.62"/></g></g></svg>
												</div>
												<div class="diamond dimond-6">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 347.72 454.94"><defs><style>.cls-6{stroke:#000;stroke-miterlimit:10;stroke-width:2px;fill:url(#diamond6);}</style><linearGradient id="diamond6" x1="173.56" y1="1.01" x2="173.56" y2="449.91" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f8b600"/><stop offset="0.1" stop-color="#fff"/></linearGradient></defs><title>Asset 7</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><polygon class="cls-6" points="2.02 2.71 206.51 1.01 345.12 449.91 2.02 2.71"/></g></g></svg>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h4 class="mb-0">{{getPhrase('my_courses')}}</h4>
							</div>
							<div class="card-body">
								<div class="media">
									<div class="join-btn">
										<a href="{{URL_STUDENT_MY_COURSES}}" title="{{getPhrase('my_courses')}}"><i class="icon icon-plus"></i></a>
									</div>
									<div class="media-body vertical-align">
										<p class="course-text">{{getPhrase('Learn about discipleship, Jesus and much more. Join a course today!')}}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header card-center-header" role="tab" id="headingTwo">								
								<h4 class="mb-0">
									<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										<span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span>{{getPhrase('Finish Your.....')}}</a>
								</h4>
								
							</div>
							<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
								<div class="card-body">
									<?php
									$attempted_courses = attempted_courses( 'records', array( 'exclude_completed' => TRUE, 'limit_records' => 1 ) );
									?>
									<div class="row">
										@if ( ! empty( $attempted_courses ) )
										
										<div class="col-sm-7">
											<h2 class="task-head text-right">{{$attempted_courses->title}}</h2>
										</div>
										<div class="col-sm-5">
											<div class="btn-outline blue-white-gridient">{{completed_percentage($attempted_courses->id)}}%{{getPhrase('Complete')}} </div>
										</div>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h4 class="mb-0">{{getPhrase('My Groups')}}</h4>
							</div>
							<div class="card-body">
								<div class="media">
									<a href="{{URL_STUDENT_MY_GROUPS}}">
									<div class="join-btn">
										<i class="icon icon-plus"></i>
									</div>
									</a>
									<div class="media-body vertical-align">
										<p class="course-text mt-3">Journey the Pathway with Others!</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			</div>
@stop

@section('footer_scripts')
	<script type="text/javascript" src="{{JS}}jquery-popup.js"></script>
	<script>
        $().ready(function(e) {

            var popupEvent = function() {}

            $('.popupReturn').hunterPopup({
                width: '240px',
                height: '100%',
                title: "Pathway",
                content: $('.tableContent'),

                event: popupEvent
            });
            $('#popupReturn').hunterPopup({
                width: '240px',
                height: '100%',
                title: "Pathway",
                content: $('#tableContent'),

                event: popupEvent
            });

        });

    </script>
@stop