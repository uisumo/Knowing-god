@extends($layout) 

@section('content')
Coming soon
<?php /* ?>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                    <li>{{ $title}}</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-secondary text-xs-center">
                    <div class="icon ml-2"> <span class="ion-card"></span> </div>
                    <div class="media-body">
                        <h4 class="card-title">{{ $payments->all}}</h4>
                        <p class="card-text">{{ getPhrase('Payments')}}</p>
                    </div> <a class="states-link" href="@if($payment_mode=='online')
							{{URL_ONLINE_PAYMENT_REPORT_DETAILS}}
							@else {{URL_OFFLINE_PAYMENT_REPORT_DETAILS}}
							@endif
							all">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-success text-xs-center">
                    <div class="icon ml-2"> <span class="ion-android-checkmark-circle"></span> </div>
                    <div class="media-body">
                        <h4 class="card-title">{{ $payments->success}}</h4>
                        <p class="card-text">{{ getPhrase('success')}}</p>
                    </div> <a class="states-link" href="@if($payment_mode=='online')
							{{URL_ONLINE_PAYMENT_REPORT_DETAILS}}
							@else {{URL_OFFLINE_PAYMENT_REPORT_DETAILS}}
							@endif
							success">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-warning text-xs-center">
                    <div class="icon ml-2"> <span class="ion-load-a"></span> </div>
                    <div class="media-body">
                        <h4 class="card-title">{{ $payments->pending}}</h4>
                        <p class="card-text">{{ getPhrase('pending')}}</p>
                    </div> <a class="states-link" href="@if($payment_mode=='online')
							{{URL_ONLINE_PAYMENT_REPORT_DETAILS}}
							@else {{URL_OFFLINE_PAYMENT_REPORT_DETAILS}}
							@endif
							pending">
								{{ getPhrase('view_all')}}  
							</a> </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-danger text-xs-center">
                    <div class="icon ml-2"> <span class="ion-trash-b"></span> </div>
                    <div class="media-body">
                        <h4 class="card-title">{{ $payments->cancelled}}</h4>
                        <p class="card-text">{{ getPhrase('cancelled')}}</p>
                    </div> <a class="states-link" href="@if($payment_mode=='online')
							{{URL_ONLINE_PAYMENT_REPORT_DETAILS}}
							@else {{URL_OFFLINE_PAYMENT_REPORT_DETAILS}}
							@endif
							cancelled">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary dsPanel">
                    <div class="panel-heading">{{getPhrase('payment_statistics')}}</div>
                    <div class="panel-body">
                        <canvas id="payments_chart" width="100" height="60"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary dsPanel">
                    <div class="panel-heading">{{getPhrase('payment_monthly_statistics')}}</div>
                    <div class="panel-body">
                        <canvas id="payments_monthly_chart" width="100" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php */ ?>
    <!-- /#page-wrapper -->@stop @section('footer_scripts') @include('common.chart', array('chart_data'=>$payments_chart_data,'ids' =>array('payments_chart'), 'scale'=>TRUE)) @include('common.chart', array('chart_data'=>$payments_monthly_data,'ids' =>array('payments_monthly_chart'), 'scale'=>true)) @stop