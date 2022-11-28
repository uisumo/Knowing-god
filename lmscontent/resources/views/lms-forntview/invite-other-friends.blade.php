@extends($layout)

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
                            <li class="breadcrumb-item active"> {{ $title }} </li>
                        </ol>
                    </div>
                </div>


<div class="row mt-4">
    <div class="col-sm-12">
        <div class="expand-card card-normal">

            <div class="card">
            <div class="card-body">
                                     <div class="row library-items">

{!! Form::open(array('url' => URL_INVITE_OTHER_FRIENDS, 'method' => 'POST', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
<div class="col-md-12 col-md-offset-3">

     {!! Form::label('Enter Email Addresses', 'Enter Email Address', ['class' => 'control-label']) !!}

    {!! Form::textarea('recipients', null, ['class' => 'form-control', 'rows' => 3]) !!}
    <small color="red">{{getPhrase('separate with comma(,) for multiple email addresses')}}</small>


    <?php /* ?>
    <!-- Subject Form Input -->
    <div class="form-group">
        {!! Form::label('subject', 'Subject', ['class' => 'control-label']) !!}
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    </div>
    <?php */ ?>

    <!-- Message Form Input -->
    <div class="form-group mt-2">
        {!! Form::label('message', 'Message', ['class' => 'control-label']) !!}
        {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
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

@stop
