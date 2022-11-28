<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{getPhrase('My Groups')}}</h4>
    </div>
    <?php /* ?>
    <a href="{{URL_INVITE_OTHER_FRIENDS}}">
    <button class="add-frnd-btn top-fixed">
    <span>+</span> {{getPhrase('add_a')}} <br>{{getPhrase('friend')}}
    </button>
    </a>
    <?php */ ?>
    <a href="#" ng-click="addFriend()">
    <button class="add-frnd-btn top-fixed">
    <span>+</span> {{getPhrase('add_a')}} <br>{{getPhrase('friend')}}
    </button>
    </a>

    <div class="card-body">
    <?php
	$cards = 5;
	$current_layout = DB::table( 'lmsmode' )->first();
	if ( ! $current_layout ) {
		$current_layout = 'bothsidebars';
	} else {
		$current_layout = $current_layout->layout;
	}
	if ( 'bothsidebars' === $current_layout ) {
		$cards = 2;
	} elseif ( in_array( $current_layout, array( 'leftsidebar', 'rightsidebar' )) ) {
		$cards = 3;
	}

    $my_groups = App\LMSGroups::where('user_id', '=', Auth::User()->id)->limit( $cards )->get();
    if ( $my_groups->count() == 0 ) {
        $my_groups = App\LMSGroups::select(['lmsgroups.id', 'lmsgroups.title','lmsgroups.sub_title', 'lmsgroups.slug', 'lmsgroups.image', 'lmsgroups.is_public', 'lmsgroups.total_items',  'lmsgroups.created_at', 'lmsgroups.user_id', 'lmsgroups.updated_at' ])
        ->join( 'lmsgroups_users AS lcu', 'lcu.group_id', '=', 'lmsgroups.id' )
        // ->where( 'is_public', '=', 'yes' )
        ->where( 'lcu.user_id', '=', Auth::User()->id )
        ->orderBy('updated_at', 'desc')->limit( $cards )->get();
    }

    ?>
        <ul class="list-inline">
            @if ( $my_groups->count() > 0 )
            @foreach( $my_groups as $group )
            <li>
                <a href="{{URL_STUDENT_DASHBOARD_GROUP . $group->slug}}">
				<div class="course-card">
                    <div class="course-card-img">
					<?php
					$image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
					if( $group->image ) {
						$image_path = IMAGE_PATH_UPLOAD_LMS_GROUPS.$group->image;
					}
					?>
                    <img src="{{$image_path}}" alt="" class="img-responsive"></div>
                    <div class="course-card-content">
                        <p>{{$group->title}}</p>
                        @if ( ! empty( $group->sub_title ) )
                        <p>{{$group->sub_title}}</p>
                        @endif
                    </div>
                </div>
				</a>
            </li>
            @endforeach
            @endif

            <li>
                <?php
				$url = URL_STUDENT_OTHER_LMS_GROUPS;
				if ( Auth::User()->current_user_role != 'subscriber' ) {
					$url = URL_STUDENT_ADD_GROUP;
				}
				?>
				<a href="{{$url}}" class="course-plus-btn">
                <div class="join-btn join-btn-md">
                    <i class="icon icon-plus"></i>
                </div>
                </a>
            </li>
        </ul>
    </div>
</div>
