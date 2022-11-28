<!DOCTYPE html>
<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
    <meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')}}" type="image/x-icon" />
    <title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title> @yield('header_scripts')
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{NEW_CSS}}bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{CSS}}sweetalert.css" rel="stylesheet" type="text/css">
    <!-- Font Icons -->
    <link rel="stylesheet" href="{{NEW_CSS}}font-awesome.min.css">
    <link rel="stylesheet" href="{{NEW_CSS}}ionicons.min.css">
    <!-- Plugins -->
    <link rel="stylesheet" href="{{NEW_CSS}}metisMenu.min.css">
    <link rel="stylesheet" href="{{NEW_JS}}datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{NEW_JS}}datepicker/bootstrap-datepicker.min.css">
    <!-- Charts -->
    <!-- <link rel="stylesheet" href="{{NEW_JS}}charts/morris.css"> -->
    <!-- Themes -->
    <link rel="stylesheet" href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css
">
    <link href="{{CSS}}custom-fonts.css" rel="stylesheet" type="text/css">
    <link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="{{NEW_CSS}}admin/one.css" id="style_theme"> -->
    <!-- Custom Style-->
    <link rel="stylesheet" href="{{NEW_CSS}}admin/style.css">
    <link rel="stylesheet" href="{{NEW_CSS}}admin/admin-validation.css"> </head>

<body ng-app="academia" class="fix-header"> @yield('custom_div')
    <!-- Top bar -->
    <div class="ag-topbar">
        <nav class="navbar navbar-expand-lg navbar-fixed-top navbar-dark bg-dark">
            <div class="mini-logo">
                <a class="navbar-brand" href="{{PREFIX}}"> <img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="{{getSetting('site_title','site_settings')}}" width="180" height="50"></a>

            </div> <span class="ion ion-navicon sidebar-toggle-btn"></span>
            <?php $newUsers = (new App\User())->getLatestUsers(); ?>
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="open-close"><i class="ion ion-navicon-round"></i></a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown d-none-992">
                        <a class="nav-link round dropdown-toggle" href="#" id="navbarDropdownMenuStar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ion ion-ios-email-outline"></i><span class="badge nav-badge badge-success">{{$newUsers->count()}}</span> </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuStar">
                            <ul class="nav-drop-messages"> @foreach($newUsers as $user)
                                <li>
                                    <div class="media small-media"> <img class="d-flex mr-2 icn-size" src="{{ getProfilePath($user->image)}}" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <h5><a href="{{URL_USER_DETAILS.$user->slug}}">{{ucfirst($user->name)}}</a></h5> {{ getPhrase('was_joined')}} <small class="d-flex float-md-right">{{$user->created_at->diffForHumans()}}</small> </div>
                                    </div>
                                </li> @endforeach </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown"> @if(Auth::check()) <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" class="user-image" alt=""><span class="user-name d-none-992">{{Auth::user()->name}}</span>
                    </a> @endif
                        <div class="dropdown-menu dropdown-menu-right nav-drop-profile" aria-labelledby="navbarDropdownProfile">
                            <a class="dropdown-item" href="{{URL_USERS_EDIT}}{{Auth::user()->slug}}"> <i class="ion ion-ios-person"></i>{{ getPhrase('my_profile') }} </a>
                            <a class="dropdown-item" href="{{URL_USERS_CHANGE_PASSWORD}}{{Auth::user()->slug}}"> <i class="fa fw fa-cog"></i>&nbsp;{{ getPhrase('change_password') }} </a>
                            <a class="dropdown-item" href="{{URL_USERS_LOGOUT}}"> <i class="fa fw fa-sign-out"></i>&nbsp;{{ getPhrase('logout') }} </a>
                        </div>
                    </li>
                </ul>
        </nav>
    </div>
    <!-- /Top bar -->
    <!-- Left bar -->
    <div class="sidebar sidebar-nav ">
        <!-- Logo -->
		<?php
		if ( empty( $active_class ) ) {
			$active_class = '';
		}
		?>
        <div class="ag-logo">
            <a class="navbar-brand" href="{{PREFIX}}"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="{{getSetting('site_title','site_settings')}}" width="180" height="50"></a>
        </div>
        <ul class="metismenu" id="ag-menu">
            <li {{ isActive($active_class, 'dashboard') }}>
                <a href="{{PREFIX}}"> <i class="ion ion-speedometer"></i> <span class="hide-menu">{{ getPhrase('dashboard') }} </span></a>
            </li>
            <?php /* ?>
            <li {{ isActive($active_class, 'users') }}> <a href="{{URL_USERS}}" aria-expanded="false"><i class="ion ion-person"></i><span class="hide-menu">{{ getPhrase('users') }}</span></a> </li>
			<?php */ ?>

			<li {{ isActive($active_class, 'users') }}> <a class="has-arrow" href="#" aria-expanded="false"><i class="ion ion-person" ></i><span class="hide-menu">{{ getPhrase('users') }}</span></a>
                <ul class="nav nav-second-level collapse" aria-expanded="false">

					<li>
                        <a href="{{ URL_USERS }}"> <i class="ion ion-ios-list-outline"></i>{{ getPhrase('users') }}</a>
                    </li>

                    <li>
                        <a href="{{ URL_USERS_ROLE }}subscriber"> <i class="ion ion-ios-list-outline"></i>{{ getPhrase('subscribers') }}</a>
                    </li>
					<li>
                        <a href="{{ URL_USERS_ROLE }}facilitator"> <i class="fa fa-bars"></i>&nbsp;&nbsp;{{ getPhrase('facilitators') }}</a>
                    </li>
					<li>
                        <a href="{{ URL_USERS_ROLE }}coach"> <i class="ion ion-android-folder"></i>{{ getPhrase('coaches') }}</a>
                    </li>
					<li>
                        <a href="{{ URL_USERS_ROLE }}admin"> <i class="ion ion-android-folder"></i>{{ getPhrase('admins') }}</a>
                    </li>
					<li>
                        <a href="{{ URL_USERS_ROLE }}owner"> <i class="ion ion-android-folder"></i>{{ getPhrase('owners') }}</a>
                    </li>
					
					<li>
                        <a href="{{ URL_USERS_COACH_REQUESTS }}"> <i class="ion ion-android-folder"></i>{{ getPhrase('coach_requests') }}</a>
                    </li>
                </ul>
            </li>
			<?php /* ?>
			<li {{ isActive($active_class, 'subjects') }}> <a href="{{URL_MASTERSETTINGS_SUBJECTS}}" aria-expanded="false"><i class="ion ion-ios-albums"></i><span class="hide-menu">{{ getPhrase('pathway') }}</span></a> </li>
			<?php */ ?>

			<li {{ isActive($active_class, 'categories') }}> <a href="{{URL_QUIZ_CATEGORIES}}" aria-expanded="false"><i class="ion ion-levels"></i><span class="hide-menu">{{ getPhrase('categories') }}</span></a> </li>

			<li {{ isActive($active_class, 'lms') }}> <a class="has-arrow" href="#" aria-expanded="false"><i class="ion ion-network" ></i><span class="hide-menu">LMS</span></a>
                <ul class="nav nav-second-level collapse" aria-expanded="false">
                    @if ( lmsmode() == 'series' )
					<li>
                        <a href="{{ URL_LMS_SERIES_MASTER }}"> <i class="ion ion-ios-list-outline"></i>{{ getPhrase('series') }}</a>
                    </li>
					@endif
                    <li>
                        <a href="{{ URL_LMS_SERIES }}"> <i class="ion ion-ios-list-outline"></i>{{ getPhrase('courses') }}</a>
                    </li>
					<li>
                        <a href="{{ URL_LMS_MODULES }}"> <i class="fa fa-bars"></i>&nbsp;&nbsp;{{ getPhrase('modules') }}</a>
                    </li>
					<li>
                        <a href="{{ URL_LMS_CONTENT }}"> <i class="ion ion-android-folder"></i>{{ getPhrase('lessons') }}</a>
                    </li>

					<li>
                        <a href="{{ URL_SPECIAL_COURSES }}"> <i class="ion ion-ios-list-outline"></i>{{ getPhrase('special_courses') }}</a>
                    </li>


                </ul>
            </li>

            <li {{ isActive($active_class, 'exams') }}> <a class="has-arrow" href="#" aria-expanded="false"><i class="ion ion-ios-book" ></i><span class="hide-menu">{{ getPhrase('exams') }}</span></a>
                <ul class="nav nav-second-level collapse" aria-expanded="false">
                    <li><a href="{{URL_QUIZ_QUESTIONBANK}}"><i class="ion ion-help"></i> {{ getPhrase('question_bank') }}</a></li>
                    <li><a href="{{URL_QUIZZES}}"><i class="ion ion-ios-stopwatch"></i> {{ getPhrase('quiz')}}</a></li>

					<!--
					<li><a href="{{URL_EXAM_SERIES}}"><i class="ion ion-android-list"></i> {{ getPhrase('exam_series')}}</a></li>
					-->
                    <li><a href="{{URL_INSTRUCTIONS}}"><i class="ion ion-ios-information"></i> {{ getPhrase('instructions')}}</a></li>
                </ul>
            </li>


            <li {{ isActive($active_class, 'reports') }}> <a class="has-arrow" href="#" aria-expanded="false"><i class="ion ion-document-text" ></i><span class="hide-menu">
                {{ getPhrase('donations') }}</span> </a>
                <ul class="nav nav-second-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{URL_STUDENT_DONATIONS_SUMMARY}}"> <i class="ion ion-link"></i>{{ getPhrase('summary') }}</a>
                    </li>
					<li>
                        <a href="{{URL_ONLINE_DONATIONS_REPORT_DETAILS}}all"> <i class="ion ion-link"></i>{{ getPhrase('list') }}</a>
                    </li>
                    <?php /* ?>
					<li>
                        <a href="{{URL_OFFLINE_PAYMENT_REPORTS}}"> <i class="ion ion-social-usd"></i>{{ getPhrase('offline_payments') }}</a>
                    </li>
					<?php */ ?>
                    <li>
                        <a href="{{URL_PAYMENT_REPORT_EXPORT}}"> <i class="ion ion-ios-upload"></i>{{ getPhrase('export') }}</a>
                    </li>
                </ul>
            </li>
            <?php /* ?>
			<li {{ isActive($active_class, 'notifications') }}> <a href="{{URL_ADMIN_NOTIFICATIONS}}" aria-expanded="false"><i class="ion ion-android-notifications" aria-hidden="true"></i><span class="hide-menu">
                {{ getPhrase('notifications') }} </span></a> </li>

			<li {{ isActive($active_class, 'sms') }}> <a href="{{URL_SEND_SMS}}" aria-expanded="false"><i class="ion ion-email" ></i><span class="hide-menu">
                SMS </span></a> </li>
			<?php */ ?>
            <li {{ isActive($active_class, 'messages') }}> <a href="{{URL_MESSAGES}}" aria-expanded="false"><span><i class="ion ion-chatbubbles" aria-hidden="true"></i></span>
                <span class="hide-menu">{{ getPhrase('messages')}} <h5 class="badge badge-success">{{$count = Auth::user()->newThreadsCount()}}</h5></span></a> </li>
            <li {{ isActive($active_class, 'feedback') }}> <a href="{{URL_FEEDBACKS}}" aria-expanded="false"><i class="ion ion-ios-paper" ></i>
                <span class="hide-menu">{{ getPhrase('feedback') }} </span></a> </li>

			<li {{ isActive($active_class, 'lmsgroups') }}> <a href="{{URL_ADMIN_ALL_LMSGROUPS}}" aria-expanded="false"><i class="ion ion-ios-paper" ></i>
                <span class="hide-menu">LMS {{ getPhrase('groups') }} </span></a> </li>

			<li {{ isActive($active_class, 'master_settings') }}> <a class="has-arrow" href="#" aria-expanded="false"><i class="ion ion-gear-b" ></i>
                <span class="hide-menu">{{ getPhrase('master_settings') }}</span> </a>
                <ul class="nav nav-second-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{URL_MASTERSETTINGS_EMAIL_TEMPLATES}}"> <i class="ion ion-archive"></i> {{ getPhrase('email_templates') }}</a>
                    </li>
					@if(checkRole(getUserGrade(1)))
                    <li>
                        <a href="{{URL_MASTERSETTINGS_SETTINGS}}"> <i class="ion ion-ios-cog"></i> {{ getPhrase('settings') }}</a>
                    </li>
					@endif
					<li>
                        <a href="{{URL_ADMIN_DASHBOARDS}}"> <i class="ion ion-archive"></i> {{ getPhrase('profile_dashboard') }}</a>
                    </li>
					<li>
                        <a href="{{URL_SHOW_LAYOUT}}"> <i class="ion ion-archive"></i> {{ getPhrase('site_layout') }}</a>
                    </li>
					<li {{ isActive($active_class, 'roles') }}> <a href="{{URL_SPECIALROLE}}" aria-expanded="false"><i class="ion ion-ios-albums"></i><span class="hide-menu">{{ getPhrase('roles') }}</span></a> </li>
					<?php /* ?>
					<li>
                        <a href="{{URL_SHOW_LMSMODE}}"> <i class="ion ion-archive"></i> LMS {{ getPhrase('mode') }}</a>
                    </li>
					<?php */ ?>
					</ul>
            </li>
			<li {{ isActive($active_class, 'translation') }}>
                <a href="{{URL_TRANSLATION_REQUESTS}}"> <i class="fa fa-globe" aria-hidden="true"></i> <span class="hide-menu">{{ getPhrase('translation_requests') }} </span></a>
            </li>
			<li {{ isActive($active_class, 'siteissues') }}>
                <a href="{{URL_SITE_ISSUES}}"> <i class="fa fa-minus-circle" aria-hidden="true"></i> <span class="hide-menu">{{ getPhrase('site_issues') }} </span></a>
            </li>
			<li {{ isActive($active_class, 'newsletter') }}>
                <a href="{{URL_NEWSLETTER_SUBSCRIPTIONS}}"> <i class="fa fa-envelope-open-o" aria-hidden="true"></i> <span class="hide-menu">{{ getPhrase('News Letter Subscriptions') }} </span></a>
            </li>

			<li {{ isActive($active_class, 'languages') }}>
                <a href="{{URL_LANGUAGES_LIST}}"> <i class="ion ion-radio-waves" aria-hidden="true"></i> <span class="hide-menu">{{ getPhrase('languages') }} </span></a>
            </li>

			<li {{ isActive($active_class, 'notifications') }}>
				<a href="{{HOST . 'wp-admin'}}" aria-expanded="false"><i class="fa fa-wordpress" aria-hidden="true"></i><span class="hide-menu">
                {{ getPhrase('WordPress Admin') }} </span></a>
			</li>
        </ul>
    </div>
    <!-- /Left bar -->
	<?php
	/*
	 $class = '';
	 if(!isset($right_bar))
		$class = 'no-right-sidebar';
	 ?>
	@if(isset($right_bar))
		<aside class="right-sidebar" id="rightSidebar">
			<button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>
			<div class="panel panel-right-sidebar">
				<?php $data = '';
			if(isset($right_bar_data))
				$data = $right_bar_data;
			?>
				@include($right_bar_path, array('data' => $data))
			</div>
		</aside>
	@endif
	<?php */ ?>
    <!-- Container wrapper -->
	<?php
	 $class = '';
	 if(isset($right_bar))
		$class = 'right-sidbar-added';
	 ?>
    <div class="ag-container-wrapper">
        <div class="wrapper-pad clearfix <?php echo $class; ?>">
            <div class="ag-example main-container">
            @yield('content')
             </div>
             @if(isset($right_bar))
			 <div class="right-expand-bar">
                <div class="expand-bar-toggle"><i class="ion-navicon"></i></div>
                 <div class="right-sidbar-content">
                     <div class="row">
                     <div class="col-sm-12">
                         <!-- Todo List Card -->
                        <div class="card relative">

                            <!-- <div class="card-header bordered"> Your Title Here </div> -->
                            <div class="card-body">
                                <?php $data = '';
								if(isset($right_bar_data))
									$data = $right_bar_data;
								?>
								@include($right_bar_path, array('data' => $data))
                            </div>
                        </div>
                        <!-- Todo List Card -->
                     </div>
                 </div>
                 </div>
             </div>
			 @endif
        </div>

    </div>
    <!-- /Container wrapper -->
    <div class="csfooter footer">&copy; {{date('Y')}} Copyrights KnowingGod.Org. All Rights Reserved</div>
    <!-- Script files-->
    <script src="{{NEW_JS}}jquery.min.js"></script>
    <script>
        var csrfToken = $('[name="csrf_token"]').attr('content');
        setInterval(refreshToken, 600000); // 1 hour
        function refreshToken() {
            $.get('refresh-csrf').done(function (data) {
                csrfToken = data; // the new token
            });
        }
        setInterval(refreshToken, 600000); // 1 hour
    </script>
    <script src="{{NEW_JS}}popper.min.js"></script>
    <script src="{{NEW_JS}}bootstrap.min.js"></script>
    <!-- Plugins -->
    <script src="{{NEW_JS}}metisMenu.min.js"></script>
    <?php /* ?>
        <script src="{{NEW_JS}}jRate.min.js"></script>
        <script src="{{NEW_JS}}datepicker/bootstrap-datepicker.min.js"></script>
        <!-- Datatables -->
        <script src="{{NEW_JS}}datatables/jquery.dataTables.min.js"></script>
        <script src="{{NEW_JS}}datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Charts -->
        <script src="{{NEW_JS}}charts/raphael-min.js"></script>
        <script src="{{NEW_JS}}charts/morris.js"></script>
        <script src="{{NEW_JS}}charts/jquery-charts.js"></script>
        <?php */ ?>
            <!-- Custom Script -->
            <script src="{{NEW_JS}}main.js"></script>
            <script src="{{JS}}sweetalert-dev.js"></script>
             @yield('footer_scripts')

             @include('errors.formMessages')

             @yield('custom_div_end')

              {!!getSetting('google_analytics', 'seo_settings')!!}

            <div class="ajax-loader" style="display:none;" id="ajax_loader"><img src="{{AJAXLOADER}}"> {{getPhrase('please_wait')}}...</div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        var docHeight = $(window).height();
        var footerHeight = $('.csfooter').height();
        var footerTop = $('.csfooter').position().top + footerHeight;
        if (footerTop < docHeight) {
            $('.csfooter').css('margin-top', 10 + (docHeight - footerTop) + 'px');
        }
    });
</script>

</html>