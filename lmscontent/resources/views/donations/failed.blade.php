    @extends( $layout )
@section('content')

<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb mt-2">
            @if( Auth::check() )
            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
            @endif
            <li class="breadcrumb-item"> <strong class="text-green">{{$title}}</strong> </li>
        </ol>
    </div>
</div>
          <div class="container-pad">

            <div class="row">
                <div class="col-sm-12">
                    <div role="tablist" class="expand-card">
                        <div class="card">
                            <div class="donations-card">
                                <div class="failed-icn"><i class="fa fa-frown-o" aria-hidden="true"></i></div>
                                <h1>{{getPhrase('Your donation failed/cancelled.')}}</h1>
                                <h2 class="d-failed-text">{!! getPhrase( sprintf( 'Click <a href="%s">Here</a> to try again', URL_STUDENT_DONATIONS_INDEX) ) !!}</h2>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
@stop

@section('footer_scripts')

@stop
