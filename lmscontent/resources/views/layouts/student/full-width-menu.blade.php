<!DOCTYPE html>
<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
	<meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
	<meta name="csrf_token" content="{{ csrf_token() }}">
	<link rel="icon" href="{{IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')}}" type="image/x-icon" />
	<title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>
	<!-- Bootstrap Core CSS -->
 @yield('header_scripts')
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700" rel="stylesheet">
	<link href="{{CSS}}newcss/bootstrap.min.css" rel="stylesheet">
	<!--<link rel="stylesheet" href="{{CSS}}bootstrap-datepicker.min.css">-->
<?php /* ?>
	<!-- Morris Charts CSS -->
	<link href="{{CSS}}plugins/morris.css" rel="stylesheet">
	<!-- Custom CSS -->
	<!--<link href="{{CSS}}sb-admin.css" rel="stylesheet">-->
	<!-- Custom Fonts -->
	<link href="{{CSS}}custom-fonts.css" rel="stylesheet" type="text/css">

	<link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
	<?php */ ?>
	<?php if( isset( $additional_css ) && $additional_css == 'yes' ) { ?>
	<link href="{{CSS}}sb-admin.css" rel="stylesheet">	
	<link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
	<?php } ?>
	<link href="{{FONTAWSOME}}font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- New CSS -->
	<link href="{{CSS}}newcss/hunterPopup.css" rel="stylesheet" type="text/css">
	<link href="{{CSS}}newcss/fonts.css" rel="stylesheet" type="text/css">
	<link href="{{CSS}}newcss/style.css" rel="stylesheet" type="text/css">
	<link href="{{CSS}}newcss/jquery.fancybox.min.css" rel="stylesheet">
	
	<link href="{{CSS}}sweetalert.css" rel="stylesheet" type="text/css">
	

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>

        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->



</head>



<body ng-app="academia">

 @yield('custom_div')

 <?php 

 $class = '';

 if(!isset($right_bar))

 	$class = 'no-right-sidebar';

$block_class = '';

if(isset($block_navigation))

	$block_class = 'non-clickable';

 ?>


	<div id="wrapper" class="{{$class}}">

		<!-- New Navbar -->
		@include('layouts.student.navmenu')
		<!-- /New Navbar -->

		<!-- Navigation -->



		<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top navbar navbar-custom navbar-fixed-top {{$block_class}}" role="navigation" style="display: none">
			
		<?php 
		if(isset($block_navigation)) { ?>
			<div class="alert alert-danger alert-norefresh">
			  <strong>{{getPhrase('warning')}} !</strong> {{getPhrase('do_not_press_back/refresh_button')}}
			</div>

		<?php } ?>

			<!-- Brand and toggle get grouped for better mobile display -->

			<div class="navbar-header">

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>

				<a class="navbar-brand" href="{{PREFIX}}"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="{{getSetting('site_title','site_settings')}}"></a>

			</div>

			<!-- Top Menu Items -->
			<ul class="nav navbar-right top-nav">

				<li class="dropdown profile-menu">
@if(Auth::check())
					<div class="dropdown-toggle top-profile-menu" data-toggle="dropdown">

						

						<div class="username">

							<h2>{{Auth::user()->name}}</h2>

							 

						</div>

					

						

						<div class="profile-img"> <img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt=""> </div>
				<div class="mdi mdi-menu-down"></div>

					</div>

					<ul class="dropdown-menu">

						
						<?php /*?>
						<li>

							<a href="{{URL_BOOKMARKS.Auth::user()->slug}}">

								<sapn>{{ getPhrase('my_bookmarks') }}</sapn>

							</a>

						</li>
						<?php */ ?>
						<li>

							<a href="{{URL_USERS_EDIT.Auth::user()->slug}}">

								<sapn>{{ getPhrase('my_profile') }}</sapn>

							</a>

						</li>

						 <li>

							<a href="{{URL_USERS_CHANGE_PASSWORD.Auth::user()->slug}}">

								<sapn>{{ getPhrase('change_password') }}</sapn>

								</a>

						</li>
						<?php /*?>
						 <li>

							<a href="{{URL_USERS_SETTINGS.Auth::user()->slug}}">

								<sapn>{{ getPhrase('settings') }}</sapn>

								</a>

						</li>

						<li>

							<a href="{{URL_FEEDBACK_SEND}}">

								<sapn>{{ getPhrase('feedback') }}</sapn>

							</a>

						</li>
						<?php */ ?>
						 

						<li>

							<a href="{{URL_USERS_LOGOUT}}">

								<sapn>{{ getPhrase('logout') }}</sapn>

							</a>

						</li>

					</ul>

					@endif
			

				</li>

			</ul>

			<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->

			<!-- /.navbar-collapse -->

		</nav>
		 @if(env('DEMO_MODE')) 
		<div class="alert alert-info demo-alert">
		&nbsp;&nbsp;&nbsp;<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  			<strong>{{getPhrase('info')}}!</strong> CRUD {{getPhrase('operations_are_disabled_in_demo_version')}}
		</div>
		@endif

		@if(isset($right_bar))

			

		<aside class="right-sidebar" id="rightSidebar">

			<button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>

			<?php $right_bar_class_value = ''; 

			if(isset($right_bar_class))

				$right_bar_class_value = $right_bar_class;

			?>

			<div class="panel panel-right-sidebar {{$right_bar_class_value}}">

			<?php $data = '';

			if(isset($right_bar_data))

				$data = $right_bar_data;

			?>

				@include($right_bar_path, array('data' => $data))

			</div>

		</aside>

		

	@endif
<div class="container" style="margin-top: 100px">
	<div class="row">
		@if ( ! isset( $left ) )
			@include('layouts.student.left-menu')
		@else
			<div class="col-md-3 col-lg-3 col-xl-3"></div>
		@endif
		<div class="col-md-9 col-lg-9 col-xl-9">
			@yield('content')
		</div>
	</div>
</div>

	</div>
	
	@include('layouts.footer-front')

	<!-- /#wrapper -->
	<?php /* ?>
	<!-- jQuery -->
	<script src="{{JS}}jquery-1.12.1.min.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="{{JS}}bootstrap.min.js"></script>
	<?php */ ?>
	
	<!--<script src="{{NEW_JS}}jquery.min.js"></script>-->
	<script src="{{JS}}jquery-1.12.1.min.js"></script>
    <script src="{{NEW_JS}}popper.min.js"></script>
    <script src="{{NEW_JS}}bootstrap.min.js"></script>
	<script src="{{NEW_JS}}jquery.fancybox.min.js"></script>



 

	<!--JS Control-->

	<script src="{{JS}}main.js"></script>

	<script src="{{JS}}sweetalert-dev.js"></script>
    
    <script>
            var csrfToken = $('[name="csrf_token"]').attr('content');

            setInterval(refreshToken, 600000); // 1 hour 

            function refreshToken(){
                $.get('refresh-csrf').done(function(data){
                    csrfToken = data; // the new token
                });
            }

            setInterval(refreshToken, 600000); // 1 hour 
			
			$( document ).ready(function(){
				$(function () {
					$('[data-toggle="popover"]').popover()
				});
			});
			
			function saveIssue() {
			var issue_url = jQuery('#issue_url').val();
			var issue_description = jQuery('#issue_description').val();
			// var token = jQuery('#csrf').val();
			 var token = jQuery('meta[name="csrf_token"]').attr('content');
			if( issue_url == '' ) {
				alertify.error('{{getPhrase("Please specify URL where you find issue")}}');
				return;
			}
			if( issue_description == '' ) {
				alertify.error('{{getPhrase("Please enter description")}}');
				return;
			}
			
			if ( issue_url !== '' && issue_description != ''  ) {
				var route = '{{URL_FRONTEND_SAVE_DATA}}';
				var data= {_method: 'post', '_token':token, issue_url: issue_url, issue_description:issue_description, action:'save_siteissue' };
				jQuery.ajax({
					headers: {
						  'X-CSRF-TOKEN': token
					},
					url : route,
					data: data,
					type : 'post',
					success : function( response ) {
						var result = jQuery.parseJSON( response );
						if ( result.status == 1 ) {
							jQuery('#siteissuesModal').modal('toggle');
							jQuery('#issue_url').val('');
							jQuery('#issue_description').val('');
							alertify.success(result.message);
						} else {
							alertify.error(result.message);
							return;
						}
					}
				});
			}
		}
			

        </script>

	

	@include('common.alertify')

	

	@yield('footer_scripts')

	@include('errors.formMessages')

	@yield('custom_div_end')
	{!!getSetting('google_analytics', 'seo_settings')!!}
</body>



</html>