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
				   <a href="{{URL_STUDENT_ANALYSIS_SUBJECT . Auth::User()->slug}}" >Quiz Analysis by <span>Pathway</span></a>
				   <a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.Auth::user()->slug }}" >Quiz Analysis by <span>Exam</span></a>
				   <a href="{{URL_STUDENT_EXAM_ATTEMPTS.Auth::user()->slug }}" class="active">Quiz Analysis by <span>History</span></a>
			   </div>



                <!-- /.row -->

                <div class="panel panel-custom">

                    <div class="panel-heading">



                        <h4>{{ $title.' '.getPhrase('of').' '.$user->name .' '.getPhrase('in').' '.$exam_record->title.' '.getPhrase('exam') }}</h4>

                    </div>



                    <div class="panel-body packages">

                    <ul class="nav nav-tabs">

                            <li class="nav-item"><a data-toggle="tab" href="#academic_details" class="nav-link active">{{getPhrase('marks')}}</a></li>

                            <li class="nav-item"><a data-toggle="tab" href="#personal_details" class="nav-link">{{getPhrase('time')}}</a></li>



                    </ul>

                    <div class="tab-content tab-content-style">

                            <div id="academic_details" class="tab-pane active" role="tabpanel">

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

                             <?php



                             foreach($subjects_display as  $r) {

                                 $r = (object)$r;

                                 ?>

                                 <tr>

                                     <td>{{$r->subject_name}}</td>

                                     <td>{{$r->correct_answers}}</td>

                                     <td>{{$r->wrong_answers}}</td>

                                     <td>{{$r->not_answered}}</td>

                                     <td>{{$r->correct_answers+$r->wrong_answers+$r->not_answered}}</td>

                                 </tr>

                                 <?php } ?>

                        </table>

                        </div>



                        <div class="row">



                        <?php $ids=[];?>

                        @for($i=0; $i<count($chart_data); $i++)

                        <?php

                        $newid = 'myChart'.$i;

                        $ids[] = $newid; ?>



                        <div class="col-lg-6">

                            <canvas id="{{$newid}}" width="50%" height="30"></canvas>

                        </div>



                        @endfor

                        </div>

                         </div>

                          <div id="personal_details" class="tab-pane fade" role="tabpanel">

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

                            <?php



                            $sanalysis = json_decode($quizresult->subject_analysis);



                            foreach($sanalysis as  $r) {

                                 $r = (object)$r;



                                 ?>

                                 <tr>

                                     <td>{{App\Subject::getName($r->subject_id)}}</td>

                                     <td>{{getTimeFromSeconds($r->time_spent_correct_answers)}}</td>

                                     <td>{{getTimeFromSeconds($r->time_spent_wrong_answers)}}</td>

                                     <td>{{getTimeFromSeconds($r->time_to_spend)}}</td>

                                     <td> {{getTimeFromSeconds($r->time_spent)}} </td>

                                 </tr>

                            <?php } ?>

                        </table>

                        </div>

                            @if(isset($time_data))

                         <div class="row">

                     <h4> {{getPhrase('time_is_shown_in_seconds')}}</h4>

                        <?php



                         $timeids=[];?>

                        @for($i=0; $i<count($time_data); $i++)

                        <?php

                        $newid = 'myTimeChart'.$i;

                        $timeids[] = $newid; ?>



                        <div class="col-lg-4 ">

                            <canvas id="{{$newid}}" width="100" height="110"></canvas>

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



 @include('common.chart', array($chart_data,'ids' => $ids))

@if(isset($time_data))

    @include('common.chart', array('chart_data'=>$time_data,'ids' => $timeids))

@endif

@stop

