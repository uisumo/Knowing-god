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

                            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                <div class="card-body">
                                    @include('errors.errors')
                                    <div class="row">
                                        {!! Form::open(array('url' => URL_STUDENT_DONATIONS_PROCESS, 'method' => 'POST', 'novalidate'=>'','name'=>'formDonation', 'files'=>'true')) !!}
                                        <?php
                                        $currency_code = getSetting('currency_code', 'site_settings');
                                        $denominations = array(
                                            '10' => $currency_code . '10',
                                            '50' => $currency_code . '50',
                                            '100' => $currency_code . '100',
                                            'other' => $currency_code . '20',
                                        );
                                        ?>
                                        <div class="col-md-12">
                                        {{ Form::label('donation_amount', getphrase('choose_amount')) }}
                                        <span class="text-red">*</span>
                                        </div>

                                        @foreach( $denominations as $key => $val )
                                        <div class="col-md-12  donation-input">
                                        {{ Form::radio('donation_amount', $key, true, array('id'=>'donation_' . $key )) }}
                                            <label for="donation_{{$key}}"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>
                                            @if( $key == 'other' )
                                                {{ Form::number('donation_amount_enter', $value = null , $attributes = array('class'=>'form-control', 'id' => 'donation_amount_enter',
                                                'min' => 1,
                                                    'placeholder' => getPhrase("enter_donation_amount"),    'ng-model'=>'donation_amount_enter',        'required'=> 'true',
                                                    'ng-class'=>'{"has-error": registrationForm.donation_amount_enter.$touched && registrationForm.donation_amount_enter.$invalid}',
                                                    'ng-minlength' => '1',
                                                    'steps' => '0.01'

                                                )) }}
                                                <div class="validation-error" ng-messages="formDonation.donation_amount_enter.$error" >
                                                    {!! getValidationMessage()!!}
                                                </div>
                                            @else
                                                {{$val}}
                                            @endif
                                        </label>
                                        </div>
                                        @endforeach

                                        <div class="col-md-12 mt-3">
                                        {{ Form::label('gateway', getphrase('choose_gateway')) }}
                                        <span class="text-red">*</span>
                                        </div>
                                        @if( getSetting('paypal', 'module') )
                                        <div class="col-md-12">
                                        {{ Form::radio('gateway', 'paypal', true, array('id'=>'paypal' )) }}
                                            <label for="paypal"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>{{getPhrase('paypal')}}
                                        </label>
                                        </div>
                                        @endif

                                        <?php /* ?>
                                        <div class="col-md-12">
                                        {{ Form::radio('gateway', 'stripe', false, array('id'=>'stripe' )) }}
                                            <label for="stripe"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>{{getPhrase('stripe')}}
                                        </label>
                                        </div>
                                        <?php */ ?>

                                        @if ( ! Auth::check() )
                                            <div class="col-md-12">
                                            <div class="row">
                                            <div class="col">
                                            <fieldset class="form-group">
                                            {{ Form::text('first_name', $value = null, $attributes = array('class'=>'form-control', 'id' => 'first_name',
                                                'placeholder' => getPhrase("enter_first_name"),    'ng-model'=>'first_name',                'required'=> 'true',
                                                'maxlength' => 50,
                                                'ng-class'=>'{"has-error": registrationForm.first_name.$touched && registrationForm.first_name.$invalid}',
                                            )) }}
                                            <div class="validation-error" ng-messages="formDonation.first_name.$error" >
                                                {!! getValidationMessage()!!}
                                            </div>
                                            </fieldset>
                                            </div>

                                            <div class="col">
                                            <fieldset class="form-group">
                                            {{ Form::text('last_name', $value = null, $attributes = array('class'=>'form-control', 'id' => 'last_name',
                                                'placeholder' => getPhrase("enter_last_name"),    'ng-model'=>'last_name',                    'required'=> 'true',
                                                'maxlength' => 50,
                                                'ng-class'=>'{"has-error": registrationForm.last_name.$touched && registrationForm.last_name.$invalid}',
                                            )) }}
                                            <div class="validation-error" ng-messages="formDonation.last_name.$error" >
                                                {!! getValidationMessage()!!}
                                            </div>
                                            </fieldset>
                                            </div>
                                            </div>

                                            <fieldset class="form-group">
                                            {{ Form::email('email_address', $value = null, $attributes = array('class'=>'form-control', 'id' => 'email_address',
                                                'placeholder' => getPhrase("enter_email_address"),    'ng-model'=>'email_address',                    'required'=> 'true',
                                                'ng-class'=>'{"has-error": registrationForm.email_address.$touched && registrationForm.email_address.$invalid}',
                                            )) }}
                                            <div class="validation-error" ng-messages="formDonation.email_address.$error" >
                                                {!! getValidationMessage()!!}
                                            </div>
                                            </fieldset>

                                            </div>
                                        @endif

                                        <div class=" buttons mt-4 ml-2">
                                            <button type="submit"  class="btn button btn-primary btn-lg"
                                            ng-disabled='!formDonation.$valid'>{{getPhrase('donate_now')}}</button>
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
