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
<div class="container-fluid" style="margin-top: 100px">
	<div class="row">
		@if ( ! isset( $left ) )
			@include('layouts.student.left')
		@else
			<div class="col-md-12 col-lg-2 col-xl-3"></div>
		@endif
		<div class="col-md-12 col-lg-8 col-xl-6">
			@yield('content')
		</div>
		@if ( ! isset( $right ) )
			@include('layouts.student.right')
		@else
			<div class="col-md-12 col-lg-2 col-xl-3"></div>
		@endif
	</div>
</div>

	</div>

	<!-- /#wrapper -->
	<?php /* ?>
	<!-- jQuery -->
	<script src="{{JS}}jquery-1.12.1.min.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="{{JS}}bootstrap.min.js"></script>
	<?php */ ?>
	
	<script src="{{NEW_JS}}jquery.min.js"></script>
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
			

        </script>

	

	@include('common.alertify')

	

	@yield('footer_scripts')

		@include('errors.formMessages')

	@yield('custom_div_end')
	{!!getSetting('google_analytics', 'seo_settings')!!}
</body>

</html>