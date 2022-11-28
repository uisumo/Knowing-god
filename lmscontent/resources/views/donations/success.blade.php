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
                                <div class="thanks-icn"><i class="fa fa-thumbs-up" aria-hidden="true"></i></div>
                                <h1>{{getPhrase('Thanks for your donation.')}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
@stop

@section('footer_scripts')

@stop
