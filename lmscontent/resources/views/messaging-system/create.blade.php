@extends($layout)
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
                            <li class="breadcrumb-item"><a href="{{URL_MESSAGES}}">{{getPhrase('messages')}}</a> </li>

                            <li class="breadcrumb-item active"> {{ $title }} </li>
                        </ol>
                    </div>
                </div>



                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="heading-right">
                    <div class="pull-right messages-buttons">
                            <a class="btn btn-primary button" href="{{URL_MESSAGES}}"> {{getPhrase('inbox')}} </a>
							<a class="btn btn-primary button" href="{{URL_MESSAGES}}/unread"> {{getPhrase('unread').' ('.$count = Auth::user()->newThreadsCount().')'}} </a>
                            <a class="btn btn-primary button" href="{{URL_MESSAGES_CREATE}}">
                            {{getPhrase('compose')}}</a>


                        </div>
                        <h1>{{$title}}</h1>
                    </div>
                    </div>
                </div>

<div class="row mt-4">
    <div class="col-sm-12">
        <div class="expand-card card-normal">

            <div class="card">
            <div class="card-body">
                                     <div class="message-max-width">

{!! Form::open(['route' => 'messages.store']) !!}
<div class="">
<?php $tosentUsers = array(); ?>
 @if($users->count() > 0)

        <?php foreach($users as $user) {
                $tosentUsers[$user->id] = $user->name;
            }
        ?>
     {!! Form::label('Select User', 'Select User', ['class' => 'control-label']) !!}
    {{Form::select('recipients[]', $tosentUsers, null, ['class'=>'form-control select2', 'name'=>'recipients[]', 'multiple'=>'true'])}}
    @endif


    <!-- Subject Form Input -->
    <div class="form-group">
        {!! Form::label('subject', 'Subject', ['class' => 'control-label']) !!}
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Message Form Input -->
    <div class="form-group">
        {!! Form::label('message', 'Message', ['class' => 'control-label']) !!}
        {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
    </div>



    <!-- Submit Form Input -->
    <div class="text-center mt-3">
        {!! Form::submit('Submit', ['class' => 'btn btn-primary button btn-lg btn-min-width']) !!}
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
