@extends('layouts.student.studentlayout')

@section('custom_div')
<div ng-controller="singleLessonCtrl">
@stop

@section('content')

<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL_USERS_EDIT.Auth::user()->slug}}">{{ getPhrase('dashboard') }}</a> / <a>{{Auth::user()->first_name}}</a> </li>
        </ol>
    </div>
</div>
          <div class="container-pad">
					<?php
					$subjects = App\Subject::get();
					
					if ( Auth::User()->id == 88 ) {
						// update_user_role();
					}
					?>
					<div class="profile-card" style="background: url(<?php echo IMAGES; ?>subscriber-bg.png) center no-repeat;">
                        <div class="corner-ribbon">
                        @include('student.dashboard-parts.help-icon', array('help_text' => 'Basic Profile' ))
						<img src="{{IMAGES}}subscriber-banner.svg" alt="{{getPhrase('Subscriber')}}" title="{{getPhrase('Subscriber')}}">
                        </div>
                        @include('student.dashboard-parts.help-pathway')
                        @include('student.dashboard-parts.pins-part', array('subjects' => $subjects, 'dashboard' => 'subscriber_dashboard'))
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div role="tablist" class="expand-card profile-expand-card">

                        @include('student.dashboard-parts.profile-changes')
						<div class="card">
                            <div class="card-header" role="tab" id="headingOne">
                                <h4 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span> {{ getPhrase('profile_diamond') }}</a>
                                </h4>
                            </div>
                            
                            <a href="#" ng-click="addFriend()">
                            <button class="add-frnd-btn ">
                            <span>+</span> {{getPhrase('add_a')}} <br>{{getPhrase('friend')}}
                            </button>
                            </a>

                            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                <div class="card-body">
                                    <ul class="ul-inline-items">



                                        <li class="ul-item">
                                            <?php
											$joined = groups_count( 'joined' );
											?>
											@if($joined->count() == 0)
											<a href="{{URL_STUDENT_OTHER_LMS_GROUPS}}" title="{!! strip_tags( getPhrase('join_a_group') ) !!}">
                                            <div class="join-btn join-btn-title">
                                               <div class="join-btn-text">
                                               Join a <span class="text-blue">Group</span></div>

                                               <i class="icon icon-plus"></i>
                                              </div>
                                            </a>
											@else
											<div class="profile-description">
                                                Congratulations! You have joined a group and you are permitted to complete the group material. Click <a href="{{URL_STUDENT_MY_GROUPS}}">here</a> to see you groups.
                                            </div>
											@endif

                                        </li>
                                        <?php
										$next_course = suggest_next_course();
                                        ?>
                                        @if ( ! empty( $next_course ) )
                                        <li class="ul-item">
                                            <a href="{{URL_FRONTEND_LMSSERIES . $next_course->slug}}" title="{{$next_course->subject_title}}">
                                            <div class="join-btn join-btn-title">
                                                <div class="join-btn-text">
                                                    {!! $next_course->title !!}
                                                    </div>

                                                    <i class="icon icon-plus"></i>
                                             </div>
                                            </a>
                                        </li>
                                        @endif

                                        <li class="ul-item col-sm-4">
                                        @include('student.dashboard-parts.profile-diamond', array('subjects' => $subjects, 'dashboard' => 'subscriber_dashboard'))
                                    </li>
                                    </ul>
                            </div>
                        </div>
                    </div>

                        @include('student.dashboard-parts.my-courses')
                        @include('student.dashboard-parts.finish-your')
                        @include('student.dashboard-parts.my-groups')
                        @include('student.dashboard-parts.completed-courses')
                    </div>
                </div>
            </div>

            </div>
@include('student.dashboard-modal')
@stop

@section('footer_scripts')
    @include('common.validations')
    @include('lms-forntview.scripts.js-scripts')
    <script type="text/javascript" src="{{JS}}jquery-popup.js"></script>
    <script>
        $().ready(function(e) {

            var popupEvent = function() {}

            $('#popupReturn-1').hunterPopup({
                width: '240px',
                height: '100%',
                title: "{!! getPhrase('PathwayStart') !!}",
                content: $('#tableContent-1'),
                event: popupEvent
            });

            $('#popupReturn-2').hunterPopup({
                width: '240px',
                height: '100%',
                title: "{!! getPhrase('PathwayForward') !!}",
                content: $('#tableContent-2'),
                event: popupEvent
            });
            $('#popupReturn-3').hunterPopup({
                width: '240px',
                height: '100%',
                title: "{!! getPhrase('PathwayForever') !!}",
                content: $('#tableContent-3'),
                event: popupEvent
            });


            // Diamond Lower Parts
            $('.dimond-4').hunterPopup({
                width: '240px',
                height: '100%',
                title: "{!! getPhrase('PathwayForever') !!}",
                content: $('#tableContent-3'),
                event: popupEvent
            });

            $('.dimond-5').hunterPopup({
                width: '240px',
                height: '100%',
                title: "{!! getPhrase('PathwayForward') !!}",
                content: $('#tableContent-2'),
                event: popupEvent
            });
            $('.dimond-6').hunterPopup({
                width: '240px',
                height: '100%',
                title: "{!! getPhrase('PathwayStart') !!}",
                content: $('#tableContent-1'),
                event: popupEvent
            });


        });

    </script>
@stop
@section('custom_div_end')
 </div>
@stop
