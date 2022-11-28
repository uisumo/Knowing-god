@extends('layouts.student.studentlayout')

@section('content')

    
                <h2 class="mt-4">{{$category->category}} 
				<?php if ( ! empty( $category->sub_title ) ) : ?>
				<small>| {{$category->sub_title}} </small>
				<?php endif; ?>
				</h2>

                <!-- Page Heading/Breadcrumbs -->
                <ol class="breadcrumb mt-3">
                    @if( Auth::check() )
					<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
					@endif
					<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('Categories')}}</a></li>
					<li class="breadcrumb-item"><strong class="text-green">{{$category->category}}</strong></li>
                </ol>

                <!-- Intro Content -->
                <div class="row mt-5">
                    <div class="col-lg-6 mb-4">
                        @if($category->image!='')
                        
                        <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_CATEGORIES.$category->image}}" alt="">

                        @else

                        <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_DEFAULT}}" alt="">

                        @endif
                    </div>
                    <div class="col-lg-6">
                        <h2 class="pathway_green">{{getPhrase('About')}} {{$category->category}}</h2>
                        <p class="mt-2">{!! $category->description !!} </p>

                    </div>
                </div>
                <!-- /.row -->

                <h2 class="mb-3"><i class="icon icon-books text-green"></i> {{getPhrase('Courses')}}</h2>
                <div class="row mt-1" ng-controller="singleLessonCtrl">
                   
				   @foreach($serieses as $series) 

                    <div class="col-sm-6 col-md-4 mb-4">
                        <div class="card h-100 text-center">
                            <?php
							/*
							$icon_class = 'icon icon-tick-double';
							// If it is completed class will be 'icon icon-tick-border'
							$is_my_course = mycours( $series->id );
							if ( $is_my_course->count() > 0 ) {
								$icon_class = 'icon icon-tick-border';
							}
							?>
							<button class="fixed-top-left task-btn" ng-click="make_my_course('{{$series->id}}')" title="{{getPhrase('Add this course to your courses list')}}">
							<i class="{{$icon_class}}" id="my_course_icon_{{$series->id}}"></i></button>
							<?php */ ?>
							@if($series->image!='')
                            <img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$series->image}}" alt="">
                            @else
                            <img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_DEFAULT}}" alt="">
                            @endif
                            <div class="card-body">
                                <h4 class="card-title">
                                    
									<a class="text-green" href="{{URL_FRONTEND_LMSLESSON . $series->slug}}">{{$series->title}}</a>
									<?php /* ?>
									<a class="text-green" data-toggle="modal" data-target="#lessonsModal" ng-click="fetch_lessons('{{$series->slug}}')">{{$series->title}}</a><?php */ ?>
                                </h4>
								<?php if ( ! empty( $series->sub_title ) ) : ?>
                                <h6 class="card-subtitle mb-2 text-green">{{$series->sub_title}}</h6>
								<?php endif; ?>
                                <p class="card-text">{!! $series->short_description !!}</p>
                            </div>
                            <?php
							$contents = App\LmsSeries::getAllContents( $series->id );							
							if ( $contents->count() > 0 ) {
								$total_contents = $contents->count();
								$completed = 0;
							?>
							<div class="card-footer">
                                <ul class="course-finished-path">
                                    <?php 
									foreach( $contents as $content ) : 
									$class = '';
									if ( is_completed( $content->id ) ) {
										$class = 'completed';
										$completed++;
									}
									?>
									<li class="{{$class}}"><i class="icon icon-pointer-white" title="{{$content->title}}"></i></li>
									<?php endforeach;
									if ( $total_contents == $completed ) {
										$check = App\UsersCompletedCourses::where(array( 'user_id' => Auth::User()->id, 'course_id' => $series->id ))->get();
										if ( $check->count() == 0 ) {
											$record = new App\UsersCompletedCourses();
											$record->user_id = Auth::User()->id;
											$record->course_id = $series->id;
											$record->save();
										}										
									}
									?>
                                </ul>
                            </div>
							<?php } ?>
                        </div>
                    </div>
                 
                 @endforeach
                  
                </div>
                <!-- /.row -->
	@include('lms-forntview.lessons-modal')			
@stop


@section('footer_scripts')
	@include('common.validations')
	@include('lms-forntview.scripts.js-scripts')
@stop