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
					?>
                    <div class="profile-card" style="background: url(<?php echo IMAGES; ?>leader-bg.png) center no-repeat;">
                        <div class="corner-ribbon">
                        @include('student.dashboard-parts.help-icon', array('help_text' => 'completion of all pathwaystart material from the MAIN NAVIGATION (Main menas?)' ))
						<img src="{{IMAGES}}leader-banner.svg" alt="{{getPhrase('Servant Leader')}}++" title="{{getPhrase('Servant Leader')}}++">
                        </div>
                        @include('student.dashboard-parts.help-pathway')
                        @include('student.dashboard-parts.pins-part', array('subjects' => $subjects, 'dashboard' => 'leader_dashboard'))
                    </div>

            <div class="row">
                <div class="col-sm-12">
                    <div role="tablist" class="expand-card">
                        <div class="card">
                            <div class="card-header" role="tab" id="headingOne">
                                <h4 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span> {{ getPhrase('profile_diamond') }}</a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                <div class="card-body">
                                    <div class="row">

                                        <?php
										$completed_contents = completed_contents( PATHWAY_START_ID );
										?>
										<div class="col-sm-5">
                                            <div class="profile-description">
                                                Congratulations! You have completed all Pathway<span class="text-green">Start</span> content and have earned {{count($completed_contents)}} <i class="icon-map-pointer icon icon-pin l-boarder text-green"></i> ! You can see these around your profile picture. Wondering what’s next? Move on to the Pathway
                                                <span class="text-blue">Forward</span> content.
                                            </div>
                                        </div>
                                        <div class="col-sm-2"></div>

                                        <div class="col-sm-5">@include('student.dashboard-parts.profile-diamond', array('subjects' => $subjects, 'dashboard' => 'learner_dashboard'))
                                        </div>
                                    </div>
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
                title: "Pathway",
                content: $('#tableContent-1'),
                event: popupEvent
            });

            $('#popupReturn-2').hunterPopup({
                width: '240px',
                height: '100%',
                title: "Pathway",
                content: $('#tableContent-2'),
                event: popupEvent
            });
            $('#popupReturn-3').hunterPopup({
                width: '240px',
                height: '100%',
                title: "Pathway",
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
