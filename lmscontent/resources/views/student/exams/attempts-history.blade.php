@extends($layout)

@section('header_scripts')

<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
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
				
				@include( 'student.exams.analysis-navigation', array( 'active_menu' => 'history' ) )

								

				<!-- /.row -->

				<div class="panel panel-custom">

					<div class="panel-heading">

						 

						<h4 class="mt-3 mb-4">{{ $title.' '.getPhrase('of').' '.$user->name }}</h4>

					</div>

					<div class="panel-body packages">

						<div class="table-responsive"> 

						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>

								<tr>

								 

									<th>{{ getPhrase('title')}}</th>							 

									<th>{{ getPhrase('marks')}}</th>								 	 

									<th>{{ getPhrase('result')}}</th>

									 

									<th>{{ getPhrase('action')}}</th>

								  

								</tr>

							</thead>

							 

						</table>

						</div>

							<div class="expand-card mt-4">
								<div class="card">
                                    <div class="card-body">
                                    	<div class="row">
							<div class="col-md-2 "></div>
							<div class="col-md-8 ">
                                       <canvas id="myChart1" width="100" height="110"></canvas>
                                    </div><div class="col-md-2 "></div>
                            </div>
							</div>

							

						</div>
						</div>


					</div>

				</div>

			</div>

			<!-- /.container-fluid -->

		</div>
@include('student.dashboard-modal')
@endsection

 



@section('footer_scripts')

 @if(!$exam_record)

 @include('common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS.$user->slug, 'route_as_url' => 'TRUE'))

 @else

 @include('common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS.$user->slug.'/'.$exam_record->slug, 'route_as_url' => 'TRUE'))

 @endif

 @include('common.chart', array($chart_data,'ids' => array('myChart1')))

@stop

