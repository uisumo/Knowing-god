@extends($layout)
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
                            @if( Auth::check() )
							<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
							@endif
                            <li class="breadcrumb-item active"> {{ $title }} </li>
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

{!! Form::open(array('url' => URL_SEND_TRANSLATION_REQUEST, 'method' => 'POST', 'name'=>'formNotifications', 'novalidate'=>'')) !!}
<div class="col-md-12 col-md-offset-3">
    <?php
	$lable = getPhrase( 'select_lesson' );
	if ( $type == 'post' ) {
		$lable = getPhrase( 'select_post' );
	}
	?>
        
	{!! Form::label('lesson', $lable , ['class' => 'control-label']) !!}
	<span class="text-red">*</span>
	{{Form::select('lesson', $lessons, $selected, ['class'=>'form-control select2', 'name'=>'lesson'])}}

 
    
    <!-- Subject Form Input -->
    @if( ! Auth::check() )
	<div class="form-group">
        {!! Form::label('full_name', getPhrase( 'full_name' ), ['class' => 'control-label']) !!}
		<span class="text-red">*</span>
        {!! Form::text('full_name', null, ['class' => 'form-control', 'placeholder' => getPhrase('full_name'), 'maxlength' => 60]) !!}
    </div>
	<div class="form-group">
        {!! Form::label('email', getPhrase( 'email' ), ['class' => 'control-label']) !!}
		<span class="text-red">*</span>
        {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => getPhrase('email')]) !!}
    </div>
	@endif

    <!-- Message Form Input -->
    <div class="form-group">
        <input type="hidden" name="conten_type" value="<?php echo $type; ?>">
		{!! Form::label('message', getPhrase( 'message' ), ['class' => 'control-label']) !!}
		<span class="text-red">*</span>
        {!! Form::textarea('message', null, ['class' => 'form-control', 'placeholder' => getPhrase('message')]) !!}
    </div>

   
    
    <!-- Submit Form Input -->
    <div class="text-center mt-3">
        {!! Form::submit('Submit', ['class' => 'btn btn-secondary btn-compose btn-lg btn-min-width']) !!}
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
    
    <script src="{{JS}}select2.js"></script>
    
    <script>
      $('.select2').select2({
       placeholder: "Add User",
    });
    </script>
@stop