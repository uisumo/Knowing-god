<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {!! getPhrase('my_account') !!}
  </a>
  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">
  <li class="menu-item">
    <a class="dropdown-item" href="{{URL_USERS_DASHBOARD_USER}}">{{ getPhrase('dashboard') }}</a>
	</li>
	<li class="menu-item">
	<?php
	$role = getRole();
	if($role=='admin' || $role=='owner'){ ?>
	<a class="dropdown-item" href="{{URL_USERS_DASHBOARD}}">{{ getPhrase('admin_dashboard') }}</a>
	<?php } ?>
	</li>
<li class="menu-item">
    <a class="dropdown-item" href="{{URL_USERS_EDIT.Auth::user()->slug}}">
        {{ getPhrase('my_profile') }}
    </a>
	</li>
<li class="menu-item">
    <a class="dropdown-item" href="{{URL_STUDENT_MY_COURSES}}">{{ getPhrase('my_courses') }}</a>
	</li>
	<li class="menu-item">
    <a class="dropdown-item" href="{{URL_STUDENT_MY_GROUPS}}">{{ getPhrase('my_groups') }}</a>
	</li>
	<li class="menu-item">
	<a class="dropdown-item" href="{{URL_PAYMENTS_LIST . Auth::user()->slug }}">{{ getPhrase('my_donations') }}</a>
	</li>

    <?php /* ?>
	<a class="dropdown-item" href="{{URL_USERS_SETTINGS . Auth::User()->slug}}">{{ getPhrase('settings') }}</a>
	<?php */ ?>
	<li class="menu-item">
    <a class="dropdown-item" href="{{URL_FRONTEND_LMSCATEGORIES}}">{{ getPhrase('categories') }}</a>
	</li>
	<li class="menu-item">
    <a class="dropdown-item" href="{{URL_MESSAGES}}">{{ getPhrase('messages') }}</a>
	</li>
	
	<li class="menu-item">
    <a class="dropdown-item" href="{{URL_STUDENT_ANALYSIS_SUBJECT.Auth::user()->slug}}">{{ getPhrase('Quiz Analysis') }}</a>
	</li>

	
	<li class="menu-item">
    <a class="dropdown-item" href="{{URL_FEEDBACK_SEND}}">
        <span>{{ getPhrase('feedback') }}</span>
    </a>
	</li>
	
	<li class="menu-item">
    <a class="dropdown-item" href="{{URL_PROFILE_PRIVACY_SETTINGS}}">
        <span>{{ getPhrase('privacy_settings') }}</span>
    </a>
	</li>
	
	<li class="menu-item">
    <a class="dropdown-item" href="{{URL_USERS_CHANGE_PASSWORD . Auth::user()->slug}}">
        <span>{{ getPhrase('change_passowrd') }}</span>
    </a>
	</li>


    <?php /* ?>
    <a class="dropdown-item" href="<?php echo HOST; ?>lmscontent/logout">{{ getPhrase('logout') }}</a>
    <?php */ ?>
  </ul>
</li>
<li class="nav-item">
        <a href="<?php echo HOST; ?>wp-login.php?action=logout&_wpnonce=71592f9ad5&redirect_to={{URL_LMS_LOGOUT}}" style="color:#aaa;" class="nav-link">
            {{ getPhrase('logout') }}
        </a>
</li>
