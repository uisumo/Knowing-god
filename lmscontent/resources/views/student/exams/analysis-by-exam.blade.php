@extends($layout)

@section('header_scripts')

<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">

@stop

@section('content')





<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->

                <div class="row">

                    <div class="col-lg-12">

                        <ol class="breadcrumb mt-2">

                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>

                            <li class="breadcrumb-item"><a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.$user->slug}}">{{getPhrase('analysis')}}</i></a> </li>


                            <li class="breadcrumb-item active">{{ $title}}</li>

                        </ol>

                    </div>

                </div>
				@include( 'student.exams.analysis-navigation', array( 'active_menu' => 'byexam' ) )
                <!-- /.row -->

                <div class="panel panel-custom">

                    <div class="panel-heading">



                        <h4 class="mt-2 mb-4">{{ $title.' '.getPhrase('of').' '.$user->name }}</h4>

                    </div>

                    <div class="panel-body packages">

                        <div class="table-responsive">

                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

                            <thead>

                                <tr>



                                    <th>{{ getPhrase('title')}}</th>

                                    <th>{{ getPhrase('duration')}}</th>

                                    <th>{{ getPhrase('marks')}}</th>

                                    <th>{{ getPhrase('attempts')}}</th>







                                </tr>

                            </thead>



                        </table>

                        </div>


                                <div class="expand-card mt-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                            <div class="col-md-4 "></div>
                            <div class="col-md-4 ">
                                        <canvas id="myChart1" width="100" height="110"></canvas>
                                    </div><div class="col-md-4 "></div>
                            </div>
                            </div>



                        </div>
                        </div>



                    </div>

                </div>

            </div>

            <!-- /.container-fluid -->

        </div>

@endsection





@section('footer_scripts')



 @include('common.datatables', array('route'=>URL_STUDENT_EXAM_ANALYSIS_BYEXAM.$user->slug, 'route_as_url' => 'TRUE'))



@include('common.chart', array($chart_data,'ids' => array('myChart1' )))





@stop

