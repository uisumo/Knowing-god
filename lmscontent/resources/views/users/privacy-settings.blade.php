
@extends($layout)

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
                            @if(checkRole(getUserGrade(2)))
                            <li class="breadcrumb-item"><a href="{{URL_USERS}}">{{ getPhrase('users')}}</a> </li>
                            <li class="breadcrumb-item"><strong class="text-green">{{isset($title) ? $title : ''}}</strong></li>
                            @else
                            <li class="breadcrumb-item"><strong class="text-green">{{$title}}</strong></li>
                            @endif
                        </ol>
                    </div>
                </div>
                    @include('errors.errors')
                <!-- /.row -->

                <?php
                $user_options = null;
                if( $record->settings ) {
                    $user_options = json_decode($record->settings)->user_preferences;
				}				
                ?>
                <div class="expand-card card-normal mt-3">
    <div class="card" >
        <div class="card-header">
                    @if(checkRole(getUserGrade(2)))
                        <div class="pull-right messages-buttons">

                            <a href="{{URL_USERS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

                        </div>
                        @endif

                            <h4 class="mb-0 mt-3">{{ $title }}</h4>
                        </div>


                    <div class="card-body">

                     <?php $button_name = getPhrase('update'); ?>
                        {{ Form::model($record,
                        array('url' => URL_PROFILE_PRIVACY_SETTINGS,
                        'method'=>'post','novalidate'=>'','name'=>'formUsers ', 'files'=>'true' )) }}

                    <h5 class="head-bold">{{getPhrase('groups')}}</h5>

                    <div class="p-3">
                    <div class="row">
                    @foreach($settings['group'] as $key => $val )
					<?php
					$checked = '';
					if($user_options) {
						if ( ! empty ( $user_options->group ) )
						{
							if( in_array( $key,$user_options->group ) ) {
								$checked='checked';
							}
						
						}
					}
					?>
                    <div class="col-md-6">
                        <label class="checkbox-inline" >
                            <input type="checkbox" data-toggle="toggle" name="group[{{$key}}]" data-onstyle="success" data-offstyle="default" {{$checked}}> {{$val}}
                        </label>
                    </div>
                    @endforeach

                 </div>
                </div>
				
				<div class="buttons text-center mt-2">
                            <button class="btn btn-lg btn-primary button"
                            >{{ getPhrase('update') }}</button>
                        </div>

                    {!! Form::close() !!}
                    </div>
                </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
@endsection

@section('footer_scripts')
 @include('common.validations');
 <script src="{{JS}}bootstrap-toggle.min.js"></script>
@stop
