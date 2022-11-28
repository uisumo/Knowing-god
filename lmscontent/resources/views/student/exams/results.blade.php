@extends( $layout )

@section('custom_div')
 <div ng-controller="singleLessonCtrl">
 @stop

@section('header_scripts')

@stop

@section('content')

<div id="page-wrapper">

			<div class="container-fluid">
					<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb mt-2">

							<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>

							<li class="breadcrumb-item"> <a href="{{URL_FRONTEND_LMSCATEGORIES}}"> {{ getPhrase('Categories') }} </a></li>

							<li class="breadcrumb-item active"> {{$title}} {{getPhrase('Results')}}</li>

						</ol>

					</div>

				</div>
				
				<div class="row">
                    <div class="col-sm-12">
                        <div role="tablist" class="expand-card">
                            <div class="card">
                                <div class="card-header" role="tab" id="headingOne">
                                    <h4 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span> {{getPhrase('Score Card')}}</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="card-body">
                                       <ul class="library-statistic">
											<li class="total-books">
												{{getPhrase('score') }} <span>{{$record->marks_obtained}} / {{$record->total_marks}}</span>
											</li>
											<li class="total-journals">
												{{getPhrase('percentage')}} <span><?php echo sprintf('%0.2f', $record->percentage); ?></span>
											</li>
											<li class="digital-items">
											<?php $grade_system = getSettings('general')->gradeSystem; ?>
												{{ getPhrase('result')}} <span>{{  ucfirst($record->exam_status) }}</span>
											</li>
										</ul>
										<div class="mt-4 text-center">
											<a onClick="setLocalItem('{{URL_RESULTS_VIEW_ANSWERS.$quiz->slug.'/'.$record->slug}}')" href="javascript:void(0);" class="btn t btn-primary">{{ getPhrase('view_key') }}</a>
										</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" role="tab" id="headingTwo">
                                    <h4 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            <span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span> Quiz Activity</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="card-body">
                                        
                                        <!-- Charts content -->
                                        <div class="row" >

					<div class="col-md-6">

					 
						 @if(isset($marks_data))

	 						<div class="row">

						

							<?php $ids=[];?>

							@for($i=0; $i<count($marks_data); $i++)

							<?php 

							$newid = 'myMarksChart'.$i;

							$mark_ids[] = $newid; ?>

							

							 

								<canvas id="{{$newid}}" width="100" height="60"></canvas>

							 



							@endfor

							</div>

						@endif



					</div>

					<div class="col-md-6">

						

					@if(isset($time_data))

	 						<div class="row">

						

							<?php $ids=[];?>

							@for($i=0; $i<count($time_data); $i++)

							<?php 

							$newid = 'myTimeChart'.$i;

							$time_ids[] = $newid; ?>

								<canvas id="{{$newid}}" width="100" height="60"></canvas>

							@endfor

							</div>

						@endif



					</div>

					</div>
                                        <!-- ends Charts content -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


			<!-- </div> -->

			<!-- /.container-fluid -->

		</div>

		<!-- /#page-wrapper -->

	</div>

	<!-- /#wrapper -->

	 

@stop



@section('footer_scripts')
@include('common.validations')
	@include('lms-forntview.scripts.js-scripts')

   <script src="{{JS}}chart-vue.js"></script>



@if(isset($marks_data))

	@include('common.chart', array('chart_data'=>$marks_data,'ids' => $mark_ids));

@endif

@if(isset($time_data))

	@include('common.chart', array('chart_data'=>$time_data,'ids' => $time_ids));

@endif

<script>
function setLocalItem(url) {
	localStorage.setItem('redirect_url',url);
	window.location = url;
}
</script>

@stop

@section('custom_div_end')
 </div>
@stop