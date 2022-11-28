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


<div class="row mt-4">
    <div class="col-sm-12">
        <div class="expand-card card-normal">
            
            <div class="card">
            <div class="card-body">
                                     <div class="row library-items">

{!! Form::open(array('url' => URL_GET_USER_SEND_EMAIL . '/' . $record->slug, 'method' => 'POST', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
<div class="col-md-12">
    <div class="form-group">
	{{getPhrase('name:') . $record->name}}<br>
	{{getPhrase('email:') . $record->email}}
    </div> 
	<!-- Message Form Input -->
    <div class="form-group">
        {!! Form::label('message', 'Message', ['class' => 'control-label']) !!}
        {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
    </div>   
    
    <!-- Submit Form Input -->
    <div class="text-center mt-3">
	<input type="hidden" name="recipients" value="{{$record->email}}">
        {!! Form::submit('Submit', ['class' => 'btn btn-lg btn-success button']) !!}
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