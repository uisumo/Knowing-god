@extends($layout)

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
							@if(checkRole(getUserGrade(2)))
								<li class="breadcrumb-item"><a href="{{URL_USERS}}">{{ getPhrase('users')}}</a> </li>
							@else
								<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
							@endif
							<li class="breadcrumb-item"> <strong class="text-green">{{$title}}</strong> </li>
						</ol>
                    </div>
                </div>
				@include('errors.errors')


<div class="row mt-4">
    <div class="col-sm-12">
        <div class="expand-card card-normal">
            
            <div class="card">
            <div class="card-body">
                                     <div class="row library-items">

{!! Form::open(array('url' => URL_TRANSLATION_REQUEST_VIEW . $record->slug, 'method' => 'POST', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
<div class="col-md-12">
    <div class="form-group">
	{{getPhrase('name:')}}
	<?php
	if ( $record->user_id > 0 ) {
		$user = App\User::where( 'id', '=', $record->user_id )->first();
		if ( $user ) {
			echo '<a href="'.URL_USER_DETAILS.$user->slug.'">'.$record->full_name.'</a>';
		} else {
			echo $record->full_name;
		}
	} else {
		echo $record->full_name;
	}
	?>
	<br>
	{{getPhrase('email:') . $record->email}}<br>
	{{getPhrase('lesson:')}}
	<?php
	if ( $record->content_id > 0 ) {
		$lesson = App\LmsContent::where('id', '=', $record->content_id )->first();
		if ( $lesson ) {
			echo '<a href="' . URL_LMS_CONTENT_EDIT . $lesson->slug . '">' . $lesson->title . '</a>';
		} else {
			echo '<a href="'.$record->url.'" target="_blank">'. getPhrase('view') . '</a>';
		}
	} else {
		echo '<a href="'.$record->url.'" target="_blank">'. getPhrase('view') . '</a>';
	}
	?><br>
	{{getPhrase('date:' . $record->created_at)}}
    </div> 
	<!-- Message Form Input -->
    <div class="form-group">
        {!! Form::label('message', 'Message', ['class' => 'control-label']) !!}
        {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
    </div>   
    
	<input type="hidden" name="recipients" value="{{$record->email}}">
    <!-- Submit Form Input -->
	@if( $record->user_id > 0 )
    <div class="text-center mt-3">
        {!! Form::submit('Send Message', ['class' => 'btn btn-lg btn-success button', 'name' => 'sendmessage']) !!}
    </div>
	@endif
	
	<div class="text-center mt-3">
        {!! Form::submit('Send Email', ['class' => 'btn btn-lg btn-success button', 'name' => 'sendemail']) !!}
    </div>
</div>
{!! Form::close() !!}
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

@stop