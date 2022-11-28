@extends('layouts.full-width-no-menu')
@section('content')
<h2 class="mt-4">{{$title}}
@if ( ! empty( $sub_title ) )
<small>| {{$sub_title}}</small>
@endif
</h2>
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-3">
	@if( Auth::check() )
	<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
	@endif
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('Categories')}}</a></li>
	
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSSERIES . $category->slug}}">{{getPhrase('Courses')}} ({{$category->category}} )</a></li>
	
	<li class="breadcrumb-item"><strong class="text-green">{{$title}}</strong></li>
</ol>
<!-- Intro Content -->
<div class="row mt-2">
	<div class="col-sm-12">
		<h4><i class="fa fa-video-camera"></i> {{$title}} Video Lessons</h4>
	</div>
</div>

<div class="row mt-4" ng-controller="singleLessonCtrl">
	<?php
	$sl_no = 1;
	$content_image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
	$total = $contents->count();
	?>
	@foreach($contents as $content)
	<?php
	$active_class = '';
	$url = '#';
	$type = 'File';
	$audio_link = '';
	
	$paid = ($item->is_paid && !isItemPurchased($item->id, 'lms')) ? TRUE : FALSE;
	if($content->file_path) {
		switch($content->content_type)
		{
			case 'file': $url = VALID_IS_PAID_TYPE.$item->slug.'/'.$content->slug;
						 $type = 'File';   
						break;
			case 'image': $url = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->slug;
							$type = 'Image'; 
		
			case 'url': $url = $content->file_path;
						$type = 'URL';   
						break;
			case 'video_url':
			case 'video':
			case 'iframe': 
							$url = URL_STUDENT_LMS_SERIES_VIEW.$item->slug.'/'.$content->slug;
							$type = 'Video';    
							break;
			case 'audio_url':
			case 'audio': 
							if ( $content->content_type == 'audio' ) {
								$url = URL_STUDENT_LMS_SERIES_VIEW.$item->slug.'/'.$content->slug;
								$audio_link = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $content->file_path;
							} else {
								$url = $item->file_path;
								$audio_link = $content->file_path;
							}
							
							$type = 'Audio';   
							break;
		}
	} 
	if( $content->image ) {
		$content_image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->image;	
	}
	
	$url = URL_FRONTEND_LMSSINGLELESSON . $item->slug . '/' . $content->slug;
?>

 <?php if($paid) $url = '#'; ?>
	<div class="col-lg-6 mb-4">
		<div class="card h-100 text-center video-list-card">
			<div class="card-content">
				<img class="card-img-top" src="<?php echo $content_image_path; ?>" alt="">
				<div class="media title-media">
					<h1>{{$sl_no}}</h1>
					<div class="media-body">
					@if($content->content_type=='url')
					<a target="_blank" href="{{$url}}" 
					@if($paid)
						onclick="showMessage('Please buy this package to continue');" 
					@endif
					>{{$content->title}}   

					</a> 
					@else
					<a href="{{$url}}" 
					@if($paid)
						onclick="showMessage('Please buy this package to continue');" 
					@endif
					>{{$content->title}}   

					</a>  
					@endif
					</div>
				</div>
				<?php
				$class = 'icon icon-map-pointer';
				// Need to check if its completed. If its completed 
				if ( is_completed( $content->id ) ) {
					$class = 'icon icon-pointer-border';
				}				
				?>
				<div class="video-list-pin fixed-top-right"><i class="<?php echo $class; ?>"></i></div>
			</div>
			
			<div class="card-footer download-link" style="font-size:1rem;">
				@if( $content->file_pdf != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_pdf ) )
				<a class="mr-2" href="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_pdf}}"><i class="fa fa-file-pdf-o"></i></a>
				@else
				<span class="mr-2"><i class="fa fa-file-pdf-o"></i></span>	
				@endif
				
				@if( $content->file_ppt != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_ppt ) )
				<a class="mr-2" href="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_ppt}}"><i class="fa fa-file-powerpoint-o"></i></a>
				@else
				<span class="mr-2"><i class="fa fa-file-powerpoint-o"></i></span>	
				@endif
				
				@if( $content->file_word != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_word ) )
				<a class="mr-2" href="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_word}}"><i class="fa fa-file-word-o"></i></a>
				@else
				<span class="mr-2"><i class="fa fa-file-word-o"></i></span>	
				@endif
				<?php
				$comments = lmscontent_comments( $content->id );
				?>
				@if( Auth::check() )
					<a class="mr-2" data-toggle="modal" data-target="#commentsModal" ng-click="getData({{$content->id}}, 'comments')">
				@else
					<a class="mr-2" data-toggle="modal" data-target="#loginModal" >
				@endif
				<i class="fa fa-comments-o"></i>
				@if( $comments->count() > 0 )
					{{$comments->count()}}
				@endif
				</a>
				<a class="mr-2" href="#"><i class="fa fa-share"></i></a>
				<a class="mr-2" href="#"><i class="fa fa-globe"></i></a>
				<a class="mr-2" href="#"><i class="fa fa-group"></i></a>
				<a class="mr-2" href="#"><i class="fa fa-graduation-cap"></i></a>
			</div>
			@if( $type == 'Audio' )
			<div class="card-footer-actions">
				<?php
				$total_pieces = App\LmsSeries::getPieces( $content->id )->count();
				$total_pieces++; // Parent Content added
				$completed = 0;
				?>
				<audio controls="">
					<source src="{{$audio_link}}" type="audio/mpeg">
					Your browser does not support the audio element.
				</audio><span class="vid-list-count text-green">{{$completed}}/{{$total_pieces}}</span>
			</div>
			@endif
		</div>
	</div>
	<?php $sl_no++; ?>
	@endforeach
<?php $item_id = ''; ?>
	@include('lms-forntview.comments-modal')
	@include('lms-forntview.login-modal')
</div>
@stop
@section('footer_scripts')
	@include('common.validations')
	@include('lms-forntview.scripts.js-scripts')
@stop