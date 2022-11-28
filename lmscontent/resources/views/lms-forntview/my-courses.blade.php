@extends('layouts.student.studentlayout')

@section('custom_div')
<div ng-controller="singleLessonCtrl">
@stop

@section('content')
<h2 class="mb-3"><i class="icon icon-books text-green"></i> {{getPhrase('My Courses')}}</h2>
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-2">
    @if( Auth::check() )
    <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
    @endif
    <li class="breadcrumb-item"> <strong class="text-green"><?php echo getPhrase( 'My Courses' ); ?></strong> </li>
</ol>

	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  
	  <li class="nav-item">
		<a class="nav-link @if( empty( $slug ) ) show active @endif " id="edit-tab" href="{{URL_STUDENT_MY_COURSES}}" aria-selected="true">{{getPhrase('all')}}</a>
	  </li>
	  
	  
	  <li class="nav-item">
		<a class="nav-link @if( 'running' === $slug ) show active @endif" id="modules-tab" href="{{URL_STUDENT_MY_COURSES . '/running'}}" aria-selected="false">{{getPhrase('running')}}</a>
	  </li>
	  
	  <li class="nav-item">
		<a class="nav-link @if( 'completed' === $slug ) show active @endif" id="lessons-tab" href="{{URL_STUDENT_MY_COURSES . '/completed'}}"  aria-selected="false">{{getPhrase('completed')}}</a>
	  </li>
	</ul>
	
	
    <div class="row mt-1 ">
   <?php
   $c = 1;
   ?>
   @foreach($my_courses as $series)
    <div class="col-lg-6">
        <div class="white-card cs-card mt-3 mb-3">
            <?php if ( $c == 1 ) : ?>
            <?php /* ?>
            <a href="{{URL_INVITE_OTHER_FRIENDS}}">
                <button class="add-frnd-btn bottom-fixed20">
                <span>+</span> {{getPhrase('add_a')}} <br>{{getPhrase('friend')}}
                </button>
            </a>
            <?php */ ?>
            <a href="javascript:void(0);" ng-click="addFriend()">
            <button class="add-frnd-btn ">
            <span>+</span> {{getPhrase('add_a')}} <br>{{getPhrase('friend')}}
            </button>
            </a>
            <?php endif;
            $c++;
            $btn_class = 'btn-green';
            if ( $series->color_class == 'text-blue' ) {
                $btn_class = 'btn-blue';
            }
            if ( $series->color_class == 'text-yellow' ) {
                $btn_class = 'btn-yellow';
            }
            ?>
            <div class="flow-hidden relative p-3">
                <div class="corner-ribbon corner-ribbon-small left {{$btn_class}}">{{$series->subject_title}}</div>
                <div class="row category-cards-row">
                    <div class="col-md-6 cpr-0 category-cards-col">
                        @if($series->image!='')
                        <img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$series->image}}" alt="">
                        @else
                        <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_DEFAULT}}" alt="">
                        @endif
                    </div>
                    <div class="col-md-6 category-cards-col">
                        <h5 class="text-green course-card-tile">{{$series->title}}</h5>
                        <p class="course-card-text">{!! $series->short_description !!}</p>

                        <?php
                        $total_modules    = $completed_modules = $total_lessons = $completed_lessons = 0;

                        $course_lessons = DB::table('lmsseries_data')
                                ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
                                ->where('lmsseries_id', '=', $series->id )
                                ->get();
                        if ( $course_lessons->count() > 0 ) {
                            // $completed_lessons += $course_lessons->count();
                            foreach( $course_lessons as $lesson ) {
                                if ( is_lesson_completed( $lesson->id, $series->id  ) ) {
                                    $completed_lessons++;
                                }
                            }
                        }
                        // This contains all lessons includes module lessons and direct lessons in a course
                        $lessons_statistics = lessons_statistics( $series->id );

                        $total_lessons = $lessons_statistics[ 'total_lessons' ];

                        $modules = App\LmsSeries::where( 'parent_id', '=', $series->id )->get();
                        $total_modules = $modules->count();
                        if ( $total_modules > 0 ) {
                            $completed_modules = 0;
                            foreach( $modules as $module ) :
                                $lessons = DB::table('lmsseries_data')
                                ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
                                ->where('lmsseries_id', '=', $module->id )
                                ->get();
                                $module_lessons = $lessons->count();
                                $module_lessons_completed = 0;
                                if ( $module_lessons > 0 ) {
                                    foreach( $lessons as $lesson ) :
                                        if ( is_lesson_completed( $lesson->id, $series->id, $module->id ) ) {
                                            $module_lessons_completed++;
                                        }
                                    endforeach;
                                }
                                $completed_lessons = $completed_lessons + $module_lessons_completed;
                                if ( $module_lessons == $module_lessons_completed ) {
                                    $completed_modules++;
                                }
                            endforeach;
                        }
                        ?>
                        <p class="course-card-text mt-2">
                        <span class="text-danger">{{$completed_modules}}/{{$total_modules}}</span> {{getPhrase('Modules Completed')}}
                        <br><span class="text-danger">{{$completed_lessons}}/{{$total_lessons}}</span> {{getPhrase('Lessons Completed')}}
                        <br>{{getPhrase('Start :')}}{{humanizeDate( $series->course_start )}}
						<?php
						$course_finished_date = course_finished_date( $series->id );
						if ( ! empty( $course_finished_date ) ) {
						?>
						<br>{{getPhrase('End :')}}{{$course_finished_date}}
                        <?php
						}
                        $last_visited = last_visited($series->id);
                        if ( empty( $last_visited ) ) {
                            $last_visited = getPhrase( 'Never' );
                        }
                        ?>
                        <br>{{getPhrase('Last Visited :')}}{{$last_visited}}
                        <?php
						$course_groups = course_groups( $series->id );
						if ( $course_groups->count() > 0 ) {
							$counter = 0;
						?>
						<br>{{getPhrase('Group:')}}
						@foreach( $course_groups as $course_group )
							<?php
							if ( $counter > 0 ) {
								echo ', ';
							}
							$counter++;
							?>
							<a href="{{URL_STUDENT_DASHBOARD_GROUP . $course_group->slug}}" title="{{$course_group->title}}">{{$course_group->title}}</a>
						@endforeach
						<?php } ?>
						
						</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                          <div class="text-center mt-3">
                          <?php
                          $button_title = getPhrase('Resume Course');
                        // if ( is_course_completed( $series->id ) ) {
						//echo $total_modules . '##' . 	$completed_modules . '@@@@' .  $total_lessons . '##' .  $completed_lessons;
						// dd($series);
						if ( ( $total_modules == $completed_modules ) &&  ( $total_lessons == $completed_lessons )  ) {
                             $button_title = getPhrase('Course Completed');
                             if ( $series->course_status == 'running' ) {
								mark_as_completed_course( $series->id );
							 }
                          }
                          $url = URL_FRONTEND_LMSLESSON . $series->slug;
                          if ( $total_modules > 0 ) {
                              $url = URL_FRONTEND_LMSSERIES . $series->slug;
                          }
                          $url = URL_FRONTEND_LMSSERIES . $series->slug;
                          ?>
                          <a href="{{$url}}" class="btn btn-kg btn-course btn-round {{$btn_class}}">{{$button_title}}</a>
                          <a href="{{URL_STUDENT_MY_COURSES}}/{{$series->slug}}" title="{{getPhrase('remove')}}" onclick="return confirm('<?php echo getPhrase('are you sure?'); ?>');" class="remove-link">{{getPhrase('remove')}}</a>
                          </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
 @endforeach

 @if ( $my_courses->count() > 0 )
    <?php
    $recommended_courses = recommended_courses()->count();
    if ( $my_courses->count() < $recommended_courses ) {
    ?>
    <div class="col-lg-6">
        <div class="add-course-card cs-card mb-4">
            @if(Auth::check())
            <!-- <a href="#" class="text-center" ng-click="show_recommended()"> -->
            <a href="{{URL_FRONTEND_RECOMMENDED_COURSES}}" class="text-center">
            @else
            <a href="#" class="text-center" ng-click="open_login_modal('{{base64_encode(URL_FRONTEND_RECOMMENDED_COURSES . '/' . $series->slug)}}')">
            @endif
                <div class="join-btn cs-center">
                  <i class="icon icon-plus"></i>
                </div>
                <h6 class="mt-1">{{getPhrase('Add a Course')}}</h6>
            </a>
        </div>
    </div>
    <?php } ?>
    @endif

@if ( $my_courses->count() == 0 )
     <div class="col-sm-12"><?php echo getPhrase( sprintf( "<span class='no-courses-text'>Ooops!! You dont add any courses yet. Click to  </span><a href='%s' class='captilize'>Add Here </a> ", URL_FRONTEND_RECOMMENDED_COURSES ) ); ?></div>

    @endif
@if( $my_courses->count() > 0 )
        <div class="custom-pagination">
        <div class="col-sm-12">{!! $my_courses->links() !!}</div>
        </div>
@endif

</div>
@include('student.dashboard-modal')
@stop
@section('footer_scripts')
@include('common.validations')
    @include('lms-forntview.scripts.js-scripts')
@stop

@section('custom_div_end')
 </div>
@stop
