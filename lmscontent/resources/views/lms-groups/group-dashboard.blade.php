@extends( $layout )
@section('content')

<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb mt-2">
            @if( Auth::check() )
            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
            <li class="breadcrumb-item"> <a href="{{URL_STUDENT_MY_GROUPS}}"><?php echo getPhrase( 'my_groups' ); ?></a> </li>
            @endif
            <li class="breadcrumb-item"> <a href="{{URL_STUDENT_MY_GROUPS}}"><?php echo getPhrase( 'Groups' ); ?></a> </li>
            <li class="breadcrumb-item"> <strong class="text-green">{{$details->title}}</strong> </li>
        </ol>
    </div>
</div>
          <div class="  ">
			<?php group_information_messages( $details->id ); ?>
            <div class="row">
                <div class="col-sm-12">
                    <div role="tablist" class="expand-card ">
                        <div class="card r-card">

                            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-4 mt-3 mb-3">
                                            <div class="g-act">
                                                <a href="{{URL_LMS_SHOW_GROUP_POSTS . $details->slug}}" title="{{getPhrase('posts')}}">
                                                <div class="join-btn-text"><i class="fa fa-book g-act-icn"></i>
                                               {!! getPhrase('posts') !!}</div>
                                               <?php
                                               $group_details = group_details( 'postscount', array('group_id' => $details->id ) );
                                               ?>
                                               <h4>{{$group_details->count()}}</h4>
                                               </a>
                                              </div>
                                        </div>
										<div class="col-sm-4 mt-3 mb-3">
                                            <div class="g-act">
                                                <a href="{{URL_LMS_SHOW_GROUP_COURSES . $details->slug}}" title="{{getPhrase('courses')}}">
                                                <div class="join-btn-text"><i class="fa fa-book g-act-icn"></i>
                                               {!! getPhrase('courses') !!}</div>
                                               <?php
                                               $group_details = group_details( 'coursescount', array('group_id' => $details->id ) );
                                               ?>
                                               <h4>{{$group_details->count()}}</h4>
                                               </a>
                                              </div>
                                        </div>
										<div class="col-sm-4 mt-3 mb-3">
                                            <div class="g-act">
                                                <a href="{{URL_LMS_CONTENT . '/' . $details->slug}}" title="{{getPhrase('lessons')}}">
                                                <div class="join-btn-text"><i class="fa fa-book g-act-icn"></i>
                                               {!! getPhrase('lessons') !!}</div>
                                               <?php
                                               $group_details = group_details( 'contentscount', array('group_id' => $details->id ) );
                                               ?>

                                               <h4>{{$group_details->count()}}</h4>
                                               </a>
                                              </div>
                                        </div>
                                        <div class="col-sm-4 mt-3 mb-3">
                                            <div class="g-act">
                                                <a href="{{URL_STUDENT_UPDATE_GROUP_INVITATIONS . $details->slug}}" title="{{getPhrase('members')}}">
                                                <div class="join-btn-text"><i class="fa fa-users g-act-icn"></i>
                                                    {!! getPhrase('members') !!}</div>
                                                    <?php
                                                   $acceptedcount = group_details( 'accepted', array('group_id' => $details->id ) );
                                                   ?>

                                                    <h4>{{$acceptedcount->count()}}</h4>
                                                    </a>
                                              </div>
                                        </div>
                                        @if ( is_group_owner( $details->id ) )
										<div class="col-sm-4 mt-3 mb-3">
                                            <div class="g-act">
                                                <a href="{{URL_STUDENT_UPDATE_GROUP_INVITATIONS . $details->slug . '/invited'}}" title="{!! getPhrase('invitations') !!}">

                                                <div class="join-btn-text"><i class="fa fa-plus-circle g-act-icn"></i>
                                                    {!! getPhrase('invitations') !!}</div>
                                                    <?php
                                                   $invitedcount = group_details( 'invitedcount', array('group_id' => $details->id ) );
                                                   ?>

                                                    <h4>{{$invitedcount->count()}}</h4>
                                                    </a>
                                              </div>
                                        </div>
										

                                        <div class="col-sm-4 mt-3 mb-3">
                                            <div class="g-act">
                                                <a href="{{URL_STUDENT_UPDATE_GROUP_INVITATIONS . $details->slug . '/requested'}}" title="{{getPhrase('members')}}">

                                                <div class="join-btn-text"><i class="fa fa-user-plus g-act-icn"></i>
                                                    {!! getPhrase('requests') !!}</div>
                                                    <?php
                                                   $requested = group_details( 'requested', array('group_id' => $details->id ) );
                                                   ?>
                                                    <h4>{{$requested->count()}}</h4>
                                                    </a>
                                              </div>
                                        </div>
										@endif
										
										<div class="col-sm-4 mt-3 mb-3">
                                            <div class="g-act">
                                                <a href="javascript:void(0);" onclick="get_group_comment({{$details->id}});">
                                                <div class="join-btn-text"><i class="fa fa-comments-o g-act-icn"></i>
                                                    {!! getPhrase('comments') !!}</div>
                                                    <?php
                                                   $groupcomments = group_details( 'groupcomments', array('group_id' => $details->id ) );
                                                   ?>
                                                    <h4>{{$groupcomments->count()}}</h4>
                                                </a>
                                              </div>
                                        </div>
										
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
@stop

@section('footer_scripts')
	@include('student.dashboard-modal')
	@include( 'lms-groups.scripts.js-scripts' )
@stop
