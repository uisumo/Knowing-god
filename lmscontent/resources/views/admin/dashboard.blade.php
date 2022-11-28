@extends('layouts.admin.adminlayout') @section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i> {{ $title}}</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-success">
                    <div class="icon ml-2"> <span class="ion-person-stalker"></span> </div>
                    <div class="media-body">
                        <p>{{ getPhrase('users')}}</p>
                        <h4>{{ App\User::get()->count()}}</h4> </div> <a class="states-link" href="{{URL_USERS}}">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-danger">
                    <div class="icon ml-2"> <span class="ion-ios-browsers-outline"></span> </div>
                    <div class="media-body">
                        <p>{{ getPhrase('categories')}}</p>
                        <h4>{{ App\QuizCategory::get()->count()}}</h4> </div> <a class="states-link" href="{{URL_QUIZ_CATEGORIES}}">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-secondary">
                    <div class="icon ml-2"> <span class="ion-ios-pie-outline"></span> </div>
                    <div class="media-body">
                        <p>{{ getPhrase('quizzes')}}</p>
                        <h4>{{ App\Quiz::get()->count()}}</h4> </div> <a class="states-link" href="{{URL_QUIZZES}}">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
            <?php /* ?>
			<div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-warning">
                    <div class="icon ml-2"> <span class="ion-network"></span> </div>
                    <div class="media-body">
                        <p>{{ getPhrase('subjects')}}</p>
                        <h4>{{ App\Subject::get()->count()}}</h4> </div> <a class="states-link" href="{{URL_SUBJECTS}}">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
            
			<div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-primary">
                    <div class="icon ml-2"> <span class="ion-android-list"></span> </div>
                    <div class="media-body">
                        <p>{{ getPhrase('topics')}}</p>
                        <h4>{{ App\Topic::get()->count()}}</h4> </div> <a class="states-link" href="{{URL_TOPICS}}">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
			<?php */ ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="media stats-media bg-pink">
                    <div class="icon ml-2"> <span class="ion-help"></span> </div>
                    <div class="media-body">
                        <p>{{ getPhrase('questions')}}</p>
                        <h4>{{ App\QuestionBank::get()->count()}}</h4> </div> <a class="states-link" href="{{URL_QUIZ_QUESTIONBANK}}">
								{{ getPhrase('view_all')}}
							</a> </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <div class="row">
            
			<div class="col-md-6">
                <div class="panel panel-primary dsPanel">
                    <div class="panel-heading"><i class="fa fa-bar-chart-o"></i> {{getPhrase('donations_statistics')}}</div>
                    <div class="panel-body">
                        <canvas id="payments_chart" width="100" height="60"></canvas>
                    </div>
                </div>
            </div>
			
            <div class="col-md-6">
                <div class="panel panel-primary dsPanel">
                    <div class="panel-heading"><i class="fa  fa-line-chart"></i> {{getPhrase('donations_monthly_statistics')}}</div>
                    <div class="panel-body">
                        <canvas id="payments_monthly_chart" width="100" height="60"></canvas>
                    </div>
                </div>
            </div>
			
            <div class="col-md-6">
                <div class="panel panel-primary dsPanel">
                    <div class="panel-heading"><i class="fa fa-pie-chart"></i> {{getPhrase('quizzes_usage')}}</div>
                    <div class="panel-body">
                        <canvas id="demanding_quizzes" width="100" height="60"></canvas>
                    </div>
                </div>
            </div>
            <?php /* ?>
			<div class="col-md-6">
                <div class="panel panel-primary dsPanel">
                    <div class="panel-heading"><i class="fa fa-pie-chart"></i> {{getPhrase('paid_quizzes_usage')}}</div>
                    <div class="panel-body">
                        <canvas id="demanding_paid_quizzes" width="100" height="60"></canvas>
                    </div>
                </div>
            </div>
			<?php */ ?>
        </div>
    </div>
    <!-- /#page-wrapper -->@stop 
	@section('footer_scripts') 
	<?php /* ?>
	@include('common.chart', array($chart_data,'ids' =>$ids)) <?php */ ?>
	@include('common.chart', array('chart_data'=>$payments_chart_data,'ids' =>array('payments_chart'), 'scale'=>TRUE)) 
	
	@include('common.chart', array('chart_data'=>$payments_monthly_data,'ids' =>array('payments_monthly_chart'), 'scale'=>true)) 
	
	@include('common.chart', array('chart_data'=>$demanding_quizzes,'ids' =>array('demanding_quizzes'))) 
	<?php /* ?>
	@include('common.chart', array('chart_data'=>$demanding_paid_quizzes,'ids' =>array('demanding_paid_quizzes'))) 
	<?php */ ?>
	@stop