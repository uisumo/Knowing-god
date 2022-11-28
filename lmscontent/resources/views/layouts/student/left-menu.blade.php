<div class="col-md-3 col-lg-3 col-xl-2">
	<div class="sidebar-boxes">
	<aside class="left-sidebar">

			<div class="">

				<ul class="nav navbar-nav side-nav">

					<li {{ isActive($active_class, 'dashboard') }}>
						<a href="{{PREFIX}}">
							<i class="icon-home"></i> {{ getPhrase('summary') }}
						</a>
					</li>

					<?php /* ?>
					<li {{ isActive($active_class, 'exams') }} >
					<a data-toggle="collapse" data-target="#exams"><i class=" icon-exams" ></i> 
					{{ getPhrase('exams') }} </a>
					<ul id="exams" class="collapse sidemenu-dropdown">
						<li><a href="{{URL_STUDENT_EXAM_CATEGORIES}}"> <i class="fa fa-random"></i>{{ getPhrase('categories') }}</a></li>
						<li><a href="{{URL_STUDENT_EXAM_SERIES_LIST}}"> <i class="fa fa-list-ol"></i>{{ getPhrase('exam_series') }}</a></li>
					</ul>
					<?php */ ?>



					</li>
					<li {{ isActive($active_class, 'analysis') }} >
					<a data-toggle="collapse" data-target="#analysis"> 
					<i class="fa fa-bar-chart" aria-hidden="true"></i>
					{{ getPhrase('analysis') }} </a>
					<ul id="analysis" class="collapse sidemenu-dropdown">	<li><a href="{{URL_STUDENT_ANALYSIS_SUBJECT.Auth::user()->slug }}"> <i class="fa fa-key"></i>{{ getPhrase('by_subjcet') }}</a></li>

						<li><a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.Auth::user()->slug }}"> <i class="fa fa-suitcase"></i>{{ getPhrase('by_exam') }}</a></li>

						<li><a href="{{URL_STUDENT_EXAM_ATTEMPTS.Auth::user()->slug }}"> <i class="fa fa-history"></i>{{ getPhrase('history') }} </a></li>

					</ul>


					</li>
					<li {{ isActive($active_class, 'lms') }} >
					<a data-toggle="collapse" data-target="#lms"><i class="icon-school-hub" ></i> 
					LMS </a> 			

					<ul id="lms" class="collapse sidemenu-dropdown">
							<li><a href="{{ URL_STUDENT_LMS_CATEGORIES }}"> <i class="fa fa-random"></i>{{ getPhrase('categories') }}</a></li>		 

							<li><a href="{{ URL_STUDENT_LMS_SERIES }}"> <i class="fa fa-list-ol"></i>{{ getPhrase('series') }}</a></li>

					</ul>
					</li>



					@if(getSetting('messaging', 'module'))
					<li {{ isActive($active_class, 'messages') }} > 
                            <a  href="{{URL_MESSAGES}}"><span><i class="fa fa-comments-o fa-2x" aria-hidden="true"><h5 class="badge badge-success">{{$count = Auth::user()->newThreadsCount()}}</h5></i></span>
					{{ getPhrase('messages')}} </a>
					</li>
					@endif

				 

					<li {{ isActive($active_class, 'subscriptions') }} >
					<a  href="{{URL_PAYMENTS_LIST.Auth::user()->slug}}"><i class="icon-history" ></i> 
					{{ getPhrase('subscriptions') }} </a>
					</li>

					<li {{ isActive($active_class, 'notifications') }} > 
						<a href="{{URL_NOTIFICATIONS}}" ><i class="fa fa-bell-o" aria-hidden="true"></i>
					{{ getPhrase('notifications') }} </a>
					</li> 

				</ul>

			</div>

		</aside>
</div>
</div>