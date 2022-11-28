<ul class="list-inline-btns">
    <?php /* ?>
    <div class="mr-auto p-1"><h4>{{ $title }}</h4></div>
    <?php */ ?>

    <?php /* ?>
    <div class="p-1">
        <a href="{{URL_STUDENT_OTHER_LMS_GROUPS . '/requests'}}" class="btn button btn-info mb-4<?php if( ! empty( $active_class ) && $active_class == 'requests' ) echo ' active'; ?>" >{{ getPhrase('requests')}}</a>
    </div>
    <?php */ ?>
    <li>
        <a href="{{URL_STUDENT_OTHER_LMS_GROUPS . '/invitations'}}" class=" btn-three mb-2<?php if( ! empty( $active_class ) && $active_class == 'invitations' ) echo ' active'; ?>" >{{ getPhrase('invitations')}}</a>
    </li>
	@if(Auth::User()->current_user_role != 'subscriber')
    <li >
        <a href="{{URL_STUDENT_MY_GROUPS}}" class=" btn-three mb-2<?php if( ! empty( $active_class ) && $active_class == 'mygroups' ) echo ' active'; ?>" >{{ getPhrase('my_groups')}}</a>
    </li>
	@endif
    <li >
        <a href="{{URL_STUDENT_OTHER_LMS_GROUPS . '/joined'}}" class=" btn-three mb-2<?php if( ! empty( $active_class ) && $active_class == 'joined' ) echo ' active'; ?>" >{{ getPhrase('joined_groups')}}</a>
    </li>
    <li >
        <a href="{{URL_STUDENT_OTHER_LMS_GROUPS}}" class=" btn-three mb-2<?php if( ! empty( $active_class ) && $active_class == 'othergroups' ) echo ' active'; ?>" >{{ getPhrase('other_groups')}}</a>
    </li>
	@if(Auth::User()->current_user_role != 'subscriber')
    <li >
        <a href="{{URL_STUDENT_ADD_GROUP}}" class="btn-three mb-2<?php if( ! empty( $active_class ) && $active_class == 'create' ) echo ' active'; ?>" ><i class="fa fw fa-plus-circle"></i>&nbsp; {{ getPhrase('create')}}</a>
    </li>
	@endif
</ul>
