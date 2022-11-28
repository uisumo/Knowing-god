@extends('layouts.lightbox-layout')
@section('content')
<div id="page-wrapper">

  <div class="row">
    <div class="col-sm-12">
         <ol class="breadcrumb mt-2">
             <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
             <li class="breadcrumb-item active"><a href="{{URL_MESSAGES}}">{{getPhrase('messages')}}</a> </li>
         </ol>
     </div>
  </div>

  <div class="row mt-2">
	   <div class="col-sm-12">
	   	<div class="heading-right">
	   		<div class="pull-right messages-buttons">
				   <a class="btn btn-primary button" href="{{URL_MESSAGES}}"> {{getPhrase('inbox')}} </a>
				   <a class="btn btn-primary button" href="{{URL_MESSAGES}}/unread"> {{getPhrase('unread').'('.$count = Auth::user()->newThreadsCount().')'}} </a>
				   <a class="btn btn-secondary btn-compose" href="{{URL_MESSAGES_CREATE}}"> 
				   {{getPhrase('compose')}}</a>
			   </div>
	   		<h1>{{getPhrase('inbox')}}</h1>
	   	</div>
	   </div>
	</div>
	
	<div class="row mt-2">
	   <div class="col-sm-12">
		<ul class="list-unstyled inbox-media"><?php $currentUserId = Auth::user()->id;?>
		@if(count($threads)>0)
			@foreach($threads as $thread)
			<?php 
			
			$class = $thread->isUnread( $currentUserId ) ? 'unread' : ''; 
			$sender = getUserRecord( $thread->latestMessage->user_id );
			
			$image_path ='';
			if(isset($sender->image)) {
				$image_path = getProfilePath($sender->image);	
			} else {
				$image_path = IMAGE_PATH_PROFILE_THUMBNAIL_DEFAULT;
			}								
			?>
			<li class="media {!!$class!!}">
			  <img class="mr-3 inbox-img" src="{{$image_path}}" alt="img">
			  <div class="media-body">
				<h5 class="mt-0 mb-1"><a href="{{URL_MESSAGES_SHOW.$thread->id}}" class="message-suject">{{ucfirst($thread->subject)}}</a> <span class="posted-time right">{{$thread->latestMessage->updated_at->diffForHumans()}}</span></h5>
				<p>{!! $thread->latestMessage->body !!}</p>
			  </div>
			</li>
			@endforeach
		@else
		{{getPhrase( 'sorry_no_message_for_you' )}}
		@endif						
		
	  </ul>
	</div>
  </div>


 
</div>
@stop
@section('footer_scripts')
<?php /* ?>
	@include('common.validations')
	@include('lms-forntview.scripts.js-scripts')
	<?php */ ?>
@stop