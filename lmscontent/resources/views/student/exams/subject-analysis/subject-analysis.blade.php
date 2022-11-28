 @extends($layout)

@section('header_scripts')



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
				
				<div class="exam-analysis-btns">
				   <a href="{{URL_STUDENT_ANALYSIS_SUBJECT . Auth::User()->slug}}" class="active">Quiz Analysis by <span>Pathway</span></a>
				   <a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.Auth::user()->slug }}" class="">Quiz Analysis by <span>Exam</span></a>
				   <a href="{{URL_STUDENT_EXAM_ATTEMPTS.Auth::user()->slug }}" class="">Quiz Analysis by <span>History</span></a>
			   </div>


				<!-- /.row -->

				<div class="panel panel-custom">
					<div class="panel-heading">

						<h4 class="mt-2 mb-3">{{ $title.' '.getPhrase('of').' '.$user->name }}</h4>

					</div>

					<div class="panel-body packages mt-1">
					<?php /* ?>
					<ul class="nav nav-tabs add-student-tabs">

							<li class="active"><a data-toggle="tab" href="#academic_details">{{getPhrase('marks')}}</a></li>

							<li><a data-toggle="tab" href="#personal_details">{{getPhrase('time')}}</a></li>



					</ul>
					<?php */ ?>
					<ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
					  <li class="nav-item">
						<a class="nav-link active" id="academic_details-tab" data-toggle="tab" href="#academic_details" role="tab" aria-controls="academic_details" aria-selected="true">{{getPhrase('marks')}}</a>
					  </li>
					  <li class="nav-item">
						<a class="nav-link" id="personal_details-tab" data-toggle="tab" href="#personal_details" role="tab" aria-controls="personal_details" aria-selected="false">{{getPhrase('time')}}</a>
					  </li>
					</ul>

					<div class="tab-content " id="myTabContent">

							<div id="academic_details" class="tab-pane fade show active">



						<div class="table-responsive">

						<table class="table table-striped table-bordered  " cellspacing="0" width="100%">

							<thead>

								<tr>



									<th>{{ getPhrase('title')}}</th>

									<th>{{ getPhrase('correct')}}</th>

									<th>{{ getPhrase('wrong')}}</th>

									<th>{{ getPhrase('not_answered')}}</th>

									<th>{{ getPhrase('total')}}</th>





								</tr>

							</thead>

							<?php foreach($subjects_display as  $r) {

							 	$r = (object)$r;

							 	?>

							 	<tr>

							 		<td>{{$r->subject_name}}</td>

							 		<td>{{$r->correct_answers}}</td>

							 		<td>{{$r->wrong_answers}}</td>

							 		<td>{{$r->not_answered}}</td>

							 		<td> {{$r->correct_answers+$r->wrong_answers+$r->not_answered}} </td>

							 	</tr>

							<?php } ?>

						</table>

						</div>

						 @if(isset($subjects_display))

 						<div class="row">



						<?php $ids=[];?>

						@for($i=0; $i<count($subjects_display); $i++)

						<?php

						$newid = 'myChart'.$i;

						$ids[] = $newid; ?>



						<div class="col-lg-4 ">

							<div class="expand-card card-normal mt-3">
							<div class="card">
								<div class="card-body">
										<canvas id="{{$newid}}" width="100" height="110"></canvas>
								</div>
							</div>
							</div>

						</div>



						@endfor

						</div>

						@endif

						</div>



						<div id="personal_details" class="tab-pane fade">



								<div class="table-responsive">

						<table class="table table-striped table-bordered  " cellspacing="0" width="100%">

							<thead>

								<tr>



									<th>{{ getPhrase('title')}}</th>

									<th>{{ getPhrase('spent_on_correct')}}</th>

									<th>{{ getPhrase('spent_on_wrong')}}</th>

									<th>{{ getPhrase('total_time')}}</th>

									<th>{{ getPhrase('spent_time')}}</th>





								</tr>

							</thead>

							<?php foreach($subjects_display as  $r) {

							 	$r = (object)$r;

							 	?>

							 	<tr>

							 		<td>{{$r->subject_name}}</td>

							 		<td>{{getTimeFromSeconds($r->time_spent_on_correct_answers)}}</td>

							 		<td>{{getTimeFromSeconds($r->time_spent_on_wrong_answers)}}</td>

							 		<td>{{getTimeFromSeconds($r->time_to_spend)}}</td>

							 		<td> {{getTimeFromSeconds($r->time_spent)}} </td>

							 	</tr>

							<?php } ?>

						</table>

						</div>

						@if(isset($time_data))

 						<div class="row">

					 <div class="col-sm-12">
					 	<p class="text-right"> <small>{{getPhrase('time_is_shown_in_seconds')}}</small></p>
					 </div>

						<?php



						 $timeids=[];?>

						@for($i=0; $i<count($time_data); $i++)

						<?php

						$newid = 'myTimeChart'.$i;

						$timeids[] = $newid; ?>


					</div>
					<div class="row">
						<div class="col-lg-7 ">
							<div class="expand-card card-normal mt-3">
							<div class="card">
								<div class="card-body">
										<canvas id="{{$newid}}" width="100" height="110"></canvas>

								</div>
							</div>
							</div>


						</div>



						@endfor

						</div>

						@endif

						</div>





						</div>

					</div>

				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

@endsection





@section('footer_scripts')

 @if(isset($chart_data))

	@include('common.chart', array('chart_data'=>$chart_data,'ids' => $ids))

@endif

@if(isset($time_data))

	@include('common.chart', array('chart_data'=>$time_data,'ids' => $timeids))

@endif

@stop

