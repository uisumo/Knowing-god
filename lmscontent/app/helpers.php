<?php

/**
 * Flash Helper
 * @param  string|null  $title
 * @param  string|null  $text
 * @return void
 */


function flash($title = null, $text = null, $type='info')
{
    $flash = app('App\Http\Flash');

    if (func_num_args() == 0) {
        return $flash;
    }
    return $flash->$type($title, $text);
}

/**
 * Language Helper
 * @param  string|null  $phrase
 * @return string
 */
function getPhrase($key = null)
{

    $phrase = app('App\Language');

    if (func_num_args() == 0) {
        return '';
    }

    return  $phrase::getPhrase($key);
}

/**
 * This method fetches the specified key in the type of setting
 * @param  [type] $key          [description]
 * @param  [type] $setting_type [description]
 * @return [type]               [description]
 */
function getSetting($key, $setting_type)
{
    return App\Settings::getSetting($key, $setting_type);
}

/**
 * Language Helper
 * @param  string|null  $phrase
 * @return string
 */
function isActive($active_class = '', $value = '')
{
    $value = isset($active_class) ? ($active_class == $value) ? 'active' : '' : '';
    if($value)
        return "class = ".$value;
    return $value;
}

/**
 * This method returns the path of the user image based on the type
 * It verifies wether the image is exists or not,
 * if not available it returns the default image based on type
 * @param  string $image [Image name present in DB]
 * @param  string $type  [Type of the image, the type may be thumb or profile,
 *                       by default it is thumb]
 * @return [string]      [returns the full qualified path of the image]
 */
function getProfilePath($image = '', $type = 'thumb')
{
    $obj = app('App\ImageSettings');
    $path = '';

    if($image=='') {
        if($type=='profile')
            return PREFIX.$obj->getDefaultProfilePicPath();
        return PREFIX.$obj->getDefaultprofilePicsThumbnailpath();
    }


    if($type == 'profile')
        $path = $obj->getProfilePicsPath();
    else
        $path = $obj->getProfilePicsThumbnailpath();
    $imageFile = $path.$image;

    if (File::exists($imageFile)) {
        return PREFIX.$imageFile;
    }

    if($type=='profile')
        return PREFIX.$obj->getDefaultProfilePicPath();
    return PREFIX.$obj->getDefaultprofilePicsThumbnailpath();

}

/**
 * This method returns the standard date format set by admin
 * @return [string] [description]
 */
function getDateFormat()
{
    $obj = app('App\GeneralSettings');
    return $obj->getDateFormat();
}


function getBloodGroups()
{
    return array(
            'A +ve'    => 'A +ve',
            'A -ve'    => 'A -ve',
            'B +ve'    => 'B +ve',
            'B -ve'    => 'B -ve',
            'O +ve'    => 'O +ve',
            'O -ve'    => 'O -ve',
            'AB +ve'   => 'AB +ve',
            'AB -ve'   => 'AB -ve',
        );
}

function getAge($date)
{


    // return Carbon::createFromDate(1984, 7, 17)->diff(Carbon::now())->format('%y years, %m months and %d days');
}

function getLibrarySettings()
{
    return json_decode((new App\LibrarySettings())->getSettings());
}

function getExamSettings()
{
    return json_decode((new App\ExamSettings())->getSettings());
}

/**
 * This method is used to generate the formatted number based
 * on requirement with the follwoing formatting options
 * @param  [type]  $sno    [description]
 * @param  integer $length [description]
 * @param  string  $token  [description]
 * @param  string  $type   [description]
 * @return [type]          [description]
 */
function makeNumber($sno, $length=2, $token = '0',$type='left')
{
    if($type=='right')
        return str_pad($sno, $length, $token, STR_PAD_RIGHT);

    return str_pad($sno, $length, $token, STR_PAD_LEFT);

}

/**
 * This method returns the settings for the selected key
 * @param  string $type [description]
 * @return [type]       [description]
 */
function getSettings($type='')
{
    if($type=='lms')
        return json_decode((new App\LmsSettings())->getSettings());

    if($type=='subscription')
        return json_decode((new App\SubscriptionSettings())->getSettings());

    if($type=='general')
        return json_decode((new App\GeneralSettings())->getSettings());

    if($type=='email'){

        $dta = json_decode((new App\EmailSettings())->getSettings());
        return $dta;
      }

   if($type=='attendance')
        return json_decode((new App\AttendanceSettings())->getSettings());

}

/**
 * This method returns the role of the currently logged in user
 * @return [type] [description]
 */
 function getRole($user_id = 0)
 {
     if ( Auth::check() ) {
		 if($user_id)
			return getUserRecord($user_id)->roles()->first()->name;
		 return Auth::user()->roles()->first()->name;
	 } else {
		 return FALSE;
	 }
 }
 
 /**
 * This method returns the role of the currently logged in user
 * @return [type] [description]
 */
 function getSpecialRole($user_id = 0)
 {
     if ( Auth::check() ) {
		 if($user_id)
			return getUserRecord($user_id)->current_user_role;
		 return Auth::user()->current_user_role;
	 } else {
		 return FALSE;
	 }
 }

/**
 * This is a common method to send emails based on the requirement
 * The template is the key for template which is available in db
 * The data part contains the key=>value pairs
 * That would be replaced in the extracted content from db
 * @param  [type] $template [description]
 * @param  [type] $data     [description]
 * @return [type]           [description]
 */
 function sendEmail($template, $data)
 {
    return (new App\EmailTemplate())->sendEmail($template, $data);
 }

/**
 * This method returns the formatted by appending the 0's
 * @param  [type] $number [description]
 * @return [type]         [description]
 */
 function formatPercentage($number)
 {
     return sprintf('%.2f',$number).' %';
 }


/**
 * This method returns the user based on the sent userId,
 * If no userId is passed returns the current logged in user
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
 function getUserRecord($user_id = 0)
 {
    if($user_id)
     return (new App\User())->where('id','=',$user_id)->first();
    return Auth::user();
 }

 /**
 * This method returns the user based on the sent userId,
 * If no userId is passed returns the current logged in user
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
 function getUserWithUserName( $username = '' )
 {

	if( $username ) {
		$user = ( new App\User() )->where('email','=',$username)->first();
		if ( $user ) {
			return $user;
		} else {
			$user = DB::table( WP_TABLE_PREFIX .'users')->where( 'user_email',  '=', $username )->first();

			$user_with_email = '';
			if ( $user ) {
				$user_with_email = ( new App\User() )->where('email','=',$user->user_email)->first();
			}

			if ( $user_with_email ) {
				// Auth::loginUsingId( $user_with_email->id, true );
				return $user_with_email;
			} else {
				if ( $user ) {
					$user = Corcel\Model\User::find( $user->ID );
					$user_record           = new \App\User();
					$user_record->name     = $user->display_name;
					$user_record->first_name = $user->meta->first_name;
					$user_record->last_name = $user->meta->last_name;
					$user_record->email    = $user->user_email;
					$user_record->username = $user->user_login;
					$user_record->password = $user->user_pass;

					$user_record->login_enabled = 1;
					$user_record->confirmed = 'yes';
					$user_record->status = 'activated';
					$user_record->wp_user_id = $user->ID;

					$user_record->role_id  = USER_ROLE_ID;
					$user_record->slug     = $user->user_login;
					$user_record->save();
					$user_record->roles()->attach( $user_record->role_id );

					// Auth::loginUsingId( $user_record->id, true );
					return $user_record;
				}
			}
		}
	} else {
		return FALSE;
	}
 }

/**
 * Returns the user record with the matching slug.
 * If slug is empty, it will return the currently logged in user
 * @param  string $slug [description]
 * @return [type]       [description]
 */
function getUserWithSlug($slug='')
{
    if($slug)
     return App\User::where('slug', $slug)->get()->first();
    return Auth::user();
}

/**
 * This method identifies if the url contains the specific string
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
 function urlHasString($str)
 {
    $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
     if (strpos($url, $str))
        return TRUE;
    return FALSE;

 }

 function checkRole($roles)
 {
     if(Entrust::hasRole($roles))
        return TRUE;
    return FALSE;
 }

 function getUserGrade($grade = 5)
 {
     switch ($grade) {
         case 1:
             return ['owner'];
             break;
        case 2:
             return ['owner', 'admin'];
             break;
        case 3:
             return ['owner', 'admin', 'staff'];
             break;
        case 4:
             return ['owner', 'admin', 'parent'];
             break;
        case 5:
             return ['student'];
             break;
        case 6:
             return ['admin'];
             break;
        case 7:
             return ['parent'];
             break;

     }
 }
 /**
  * Returns the appropriate layout based on the user logged in
  * @return [type] [description]
  */
 function getLayout( $type = 'default' )
 {
    $layout = 'layouts.student.studentlayout';
	if(checkRole(getUserGrade(5)) && $type == 'exams' ) {
		$layout = 'layouts.student.studentlayout';
	}
    if(checkRole(getUserGrade(2)))
        $layout             = 'layouts.admin.adminlayout';
    if(checkRole(['parent']))
        $layout             = 'layouts.parent.parentlayout';

    return $layout;
 }

 function validateUser($slug)
 {
    if($slug == Auth::user()->slug)
        return TRUE;
    return FALSE;
 }

 /**
  * Common method to send user restriction message for invalid attempt
  * @return [type] [description]
  */
 function prepareBlockUserMessage( $msg = '' )
 {
    if ( ! empty( $msg ) ) {
		flash('Ooops..!', $msg, 'error');
	} else {
		flash('Ooops..!', 'you_have_no_permission_to_access', 'error');
	}
     return '';
 }

 /**
  * Common method to send user restriction message for invalid attempt
  * @return [type] [description]
  */
 function pageNotFound()
 {
    flash('Ooops..!', 'page_not_found', 'error');
     return '';
 }


 function isEligible($slug)
 {
     if(!checkRole(getUserGrade(2)))
     {
        if(!validateUser($slug))
        {
            if(!checkRole(['parent']) || !isActualParent($slug))
            {
               prepareBlockUserMessage();
               return FALSE;
            }
        }
     }
     return TRUE;
 }

 /**
  * This method checks wether the student belongs to the currently loggedin parent or not
  * And returns the boolean value
  * @param  [type]  $slug [description]
  * @return boolean       [description]
  */
 function isActualParent($slug)
 {
     return (new App\User())
              ->isChildBelongsToThisParent(
                                    getUserWithSlug($slug)->id,
                                    Auth::user()->id
                                    );

 }

/**
 * This method returns the role name or role ID based on the type of parameter passed
 * It returns ID if role name is supplied
 * It returns Name if ID is passed
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
 function getRoleData($type)
 {

     if(is_numeric($type))
     {
        /**
         * Return the Role Name as the type is numeric
         */
        return App\Role::where('id','=',$type)->first()->name;

     }

     //Return Role Id as the type is role name
     return App\Role::where('name','=',$type)->first()->id;

 }

 /**
  * Checks the subscription details and returns the boolean value
  * @param  string  $type [this is the of package]
  * @return boolean       [description]
  */
 function isSubscribed($type = 'main',$user_slug='')
 {
    $user = getUserWithSlug();
    if($user_slug)
        $user = getUserWithSlug($user_slug);

    if($user->subscribed($type))
      return TRUE;
    return FALSE;
 }

/**
 * This method will send the random color to use in graph
 * The random color generation is based on the number parameter
 * As the border and bgcolor need to be same,
 * We are maintainig number parameter to send the same value for bgcolor and background color
 * @param  string  $type   [description]
 * @param  integer $number [description]
 * @return [type]          [description]
 */
 function getColor($type = 'background',$number = 777) {

    $hash = md5('color'.$number); // modify 'color' to get a different palette
    $color = array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))); //b
    if($type=='border')
    return 'rgba('.$color[0].','.$color[1].','.$color[2].',1)';
    return 'rgba('.$color[0].','.$color[1].','.$color[2].',0.2)';
}


function pushNotification($channels = ['owner','admin'], $event = 'newUser',  $options)
{

     $pusher = \Illuminate\Support\Facades\App::make('pusher');

         $pusher->trigger( $channels,
                      $event,
                      $options
                     );



}

/**
 * This method is used to return the default validation messages
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getValidationMessage($key='required')
{
    $message = '<p ng-message="required">'.getPhrase('this_field_is_required').'</p>';

    if($key === 'required')
        return $message;

        switch($key)
        {
          case 'minlength' : $message = '<p ng-message="minlength">'
                                        .getPhrase('the_text_is_too_short')
                                        .'</p>';
                                        break;
          case 'maxlength' : $message = '<p ng-message="maxlength">'
                                        .getPhrase('the_text_is_too_long')
                                        .'</p>';
                                        break;
          case 'pattern' : $message   = '<p ng-message="pattern">'
                                        .getPhrase('invalid_input')
                                        .'</p>';
                                        break;
            case 'image' : $message   = '<p ng-message="validImage">'
                                        .getPhrase('please_upload_valid_image_type')
                                        .'</p>';
                                        break;
          case 'email' : $message   = '<p ng-message="email">'
                                        .getPhrase('please_enter_valid_email')
                                        .'</p>';
                                        break;

          case 'number' : $message   = '<p ng-message="number">'
                                        .getPhrase('please_enter_valid_number')
                                        .'</p>';
                                        break;

          case 'confirmPassword' : $message   = '<p ng-message="compareTo">'
                                        .getPhrase('password_and_confirm_password_does_not_match')
                                        .'</p>';
                                        break;
           case 'password' : $message   = '<p ng-message="minlength">'
                                        .getPhrase('the_password_is_too_short')
                                        .'</p>';
                                        break;
           case 'phone' : $message   = '<p ng-message="minlength">'
                                        .getPhrase('please_enter_valid_phone_number')
                                        .'</p>';
                                        break;
        }
    return $message;
}

/**
 * Returns the predefined Regular Expressions for validation purpose
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getRegexPattern( $key='name' )
{
    $phone_regx = getSetting('phone_number_expression', 'site_settings');
    $pattern = array(
                    'name' => '/(^[A-Za-z0-9. ]+$)+/',
                    'email' => '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                    'phone'=>$phone_regx
                    );
    return $pattern[$key];
}

function getPhoneNumberLength()
{
  return getSetting('site_favicon', 'site_settings');
}


function getArrayFromJson($jsonData)
{
    $result = array();
    if($jsonData)
    {
        foreach(json_decode($jsonData) as $key=>$value)
            $result[$key] = $value;
    }
    return $result;
}


function prepareArrayFromString($string='', $delimeter = '|')
{

    return explode($delimeter, $string);
}

/**
 * Returns the random hash unique code
 * @return [type] [description]
 */
function getHashCode()
{
  return bin2hex(openssl_random_pseudo_bytes(20));
}

/**
 * Sends the default Currency set for the project
 * @return [type] [description]
 */
function getCurrencyCode()
{
  return getSetting('currency_code','site_settings') ;
}

/**
 * Returns the max records per page
 * @return [type] [description]
 */
function getRecordsPerPage()
{
  return RECORDS_PER_PAGE;
}

/**
 * Checks wether the user is eligible to use the current item
 * @param  [type]  $item_id   [description]
 * @param  [type]  $item_type [description]
 * @return boolean            [description]
 */
function isItemPurchased($item_id, $item_type, $user_id = '')
{
  return App\Payment::isItemPurchased($item_id, $item_type, $user_id);
}

/**
 * Checks wether the user is eligible to use the current item
 * @param  [type]  $item_id   [description]
 * @param  [type]  $item_type [description]
 * @return boolean            [description]
 */
function isLessonFree($course_id, $lesson_id)
{
  $record = App\LmsSeries::join('lmsseries_data AS lsd', 'lsd.lmsseries_id', '=', 'lmsseries.id')->where('lsd.lmsseries_id', '=', $course_id)->where('lmscontent_id', '=', $lesson_id)->first();
  
  if ( $record ) {
	  return ('yes' === $record->is_free);
  } else {
	  return FALSE;
  }  
}

function humanizeDate($target_date)
{
   $created = new \Carbon\Carbon($target_date);
   $now = \Carbon\Carbon::now();
   $difference = ($created->diff($now)->days < 1) ? getPhrase('today')
                                : $created->diffForHumans($now);
    return $difference;
}


function getTimeFromSeconds($seconds)
{
    return gmdate("H:i:s",$seconds);
}

if( ! function_exists( 'knowing_god_lms_excerpt' ) ) {
	/**
	 * Function to get the excerpt to display
	 *
	 * @since 1.0
	 * @param int $post_id - post_id.
	 * @param int $count - characters to get.
	 * @return string
	 */
	function knowing_god_lms_excerpt( $post_id, $count = 200 ) {
	  $excerpt = Corcel\Model\Post::find( $post_id )->content;
	  $excerpt = strip_tags( $excerpt );
	  $length = strlen( $excerpt );
	  $excerpt = substr( $excerpt, 0, $count );
	  $excerpt = substr( $excerpt, 0, strripos( $excerpt, " " ) );
	  if ( $length > $count ) {
		$excerpt = $excerpt  . '...';
	  }
	  return $excerpt;
	}
}

if( ! function_exists( 'knowing_god_recent_posts' ) ) {
	function knowing_god_recent_posts( $title = 'recent_posts', $no_of_posts = 3 ) {

		if ( 'recent_articles' === $title ) {
			$posts = Corcel\Model\Post::type('post')->published()->hasMeta('custom_post_type', 'article')->newest()->take( $no_of_posts )->get();
			// $posts = Corcel\Model\Post::type('post')->hasMeta('custom_post_type', 'article')->take( $no_of_posts )->get();
		} else {
			$posts = Corcel\Model\Post::type('post')->published()->hasMeta('custom_post_type', 'post')->newest()->take( $no_of_posts )->get();
			// $posts = Corcel\Model\Post::type('post')->published()->newest()->take( $no_of_posts )->get();
		}
		if ( $posts->count() > 0 ) { ?>
			<!-- Posts Widget -->
			<div class="card no_border mb-4">
				<h4 class="card-header widget-title"><?php echo getPhrase( $title ); ?></h4>
				<ul class="list-unstyled">
					<?php foreach( $posts as $post ) : ?>
					<li class="media mt-1 mb-0">
						<?php
						$icon_image = $post->meta->icon_image;
						if ( empty( $icon_image ) ) {
						$icon_image = $post->thumbnail;
						}
						?>
						<img class="d-flex mr-3 align-self-center" width="40" height="40" src="<?php echo $icon_image; ?>" alt="<?php echo $post->title; ?>">
						<div class="media-body">
							<h6 class="mt-1 mb-1"><a href="<?php echo $post->url; ?>"><?php echo $post->title; ?></a></h6>
							<p class="mb-0">
							<?php
							echo ( $post->excerpt ) ? $post->excerpt : knowing_god_lms_excerpt( $post->ID, 50 );
							$author = (Object) $post->author;
							?><span><small> <em><?php echo getPhrase(' | by '); ?></em><?php echo $author->user_nicename; ?></small></span></p>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div><!--end Popular Posts-->
			<?php
		}
	}
}

if( ! function_exists( 'knowing_god_recent_courses' ) ) {
	function knowing_god_recent_courses() {
		$series = App\LmsSeries::select(['lmsseries.*', 'quizcategories.category', 'users.name AS author_name'])
		->join('quizcategories', 'quizcategories.id', '=', 'lmsseries.lms_category_id')
		->join('users', 'users.id', '=', 'lmsseries.created_by')
		->orderBy( 'lmsseries.created_at', 'desc' )
		->where( 'lmsseries.parent_id', '=', 0 )
		->limit(4);
		if ( $series->count() > 0 ) :

		?>
		<div class="mb-4">
		  <h4 style="font-weight:400;" class="card-header text-center"><?php echo getPhrase( 'Recent' ); ?><span class=""> <?php echo getPhrase( 'Courses' ); ?></span></h4>
			  <ul class="list-unstyled">
				<?php
				foreach($series->get() as $c) :
				$image_icon = IMAGES . 'pforward-courses.png';
				if ( ! empty( $c->image_icon ) ) {
					$image_icon = IMAGE_PATH_UPLOAD_LMS_SERIES . $c->image_icon;
				}

				$str = '';

				$modules = App\LmsSeries::where( 'parent_id', '=', $c->id )->count();

				$lessons = $c->total_items;
				if ( $lessons > 0 ) {
					$str .= getPhrase( 'lessons:' ) . $lessons;
				}
				if ( $modules > 0 ) {
					if ( $lessons > 0 ) {
						$str .= ' | ';
					}
					$str .= getPhrase( 'modules:' ) . $modules;
				}

				?>
				<li class="media mt-1 mb-0">
					  <img class="d-flex mr-3 align-self-center" height="40" width="40" src="<?php echo $image_icon; ?>" alt="Generic placeholder image">
					  <div class="media-body">
						  <h6 class="mt-1 mb-1"><a style="font-weight:400; color:black;" href="<?php echo URL_FRONTEND_LMSLESSON . $c->slug; ?>"><?php echo $c->title; ?></a></h6>
						  <p class="mb-0" style="font-size:.9rem; color:#555; line-height:.9rem;"><?php echo strip_tags( $c->short_description ); ?><span> <small>| <em><?php echo getPhrase( 'by' ); ?></em> <?php echo $c->author_name; ?></small> | <?php echo $str; ?></span>
						  <?php /* ?>
						  | <?php echo $c->category; ?>
						  <?php */ ?>
						  </p>
					  </div>
				  </li>
				  <?php endforeach; ?>
			  </ul>
		  </div><!--end courses section -->
		<?php
		endif;
	}
}


function is_completed( $id, $content_type = '', $type = 'content', $user_id = '', $course_id = '', $module_id = '' ) {
	if ( Auth::check() ) {
		if ( empty( $user_id ) ) {
			$user_id = Auth::User()->id;
		}
		$details = App\LmsContent::where( 'id', $id )->first();
		$track_history = '';
		if ( ! empty( $content_type ) ) {
			$track_history = App\LmsTrack::where( 'user_id', $user_id )
						->where( 'content_id', $id )
						->where( 'type', $content_type );
		} else {
			$track_history = App\LmsTrack::where( 'user_id', $user_id )
						->where( 'content_id', $id );
		}
		if ( ! empty( $course_id ) ) {
			$track_history = $track_history->where( 'course_id', '=', $course_id );
		}
		if ( ! empty( $module_id ) ) {
			$track_history = $track_history->where( 'module_id', '=', $module_id );
		}
		$track_history = $track_history->get();

		$video_completed = $text_completed = $quiz_completed = FALSE;

		if ( ! empty( $details ) ) {
			if ( ! empty( $track_history ) ) {
				foreach( $track_history as $history ) {
					// Video.
					if ( ! empty( $details->file_path_video ) ) {
						if ( $history->status == 'completed' && $history->type == 'video' ) {
							$video_completed = TRUE;
						}
					} else {
						$video_completed = TRUE;
					}

					// Description.
					if ( ! empty( $details->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}

					// Quiz.
					if ( ! empty( $details->quiz_id ) ) {
						if ( $history->status == 'completed' && $history->type == 'quiz' ) {
							$quiz_completed = TRUE;
						}
					} else {
						$quiz_completed = TRUE;
					}

				}
			}
			if ( ! empty( $content_type ) ) {
				if ( $content_type == 'video' ) {
					return $video_completed;
				}
				if ( $content_type == 'text' ) {
					return $text_completed;
				}
				if ( $content_type == 'quiz' ) {
					return $quiz_completed;
				}
			} else {
				if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE ) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}
	} else {
		return FALSE;
	}
}

/**
 * This function will check the if the lesson completed in the particular course/module
 * @param int $lesson_id - Lessons ID
 * @param int $course_id - Course ID
 * @param int $module_id - Module ID
 * @param string $content_type
 * @param string $type
 * @param int $user_id
 */
function is_lesson_piece_completed( $lesson_id, $course_id, $module_id = '', $content_type = '', $type = 'content', $user_id = '' ) {
	if ( Auth::check() ) {
		if ( empty( $user_id ) ) {
			$user_id = Auth::User()->id;
		}
		$details = App\LmsContent::where( 'id', $lesson_id )->first();
		if ( ! empty( $content_type ) ) {
			$track_history = App\LmsTrack::join( 'lmscontents', 'lmscontents.id', 'lmscontents_track.content_id' )->where( 'user_id', $user_id )
						->where( 'lmscontents_track.content_id', $lesson_id )
						->where( 'lmscontents_track.type', $content_type );
			if ( ! empty( $course_id ) ) {
				$track_history = $track_history->where( 'lmscontents_track.course_id', '=', $course_id );
			}
			if ( ! empty( $module_id ) ) {
				$track_history = $track_history->where( 'lmscontents_track.module_id', '=', $module_id );
			}
			$track_history = $track_history->get();
		} else {
			$track_history = App\LmsTrack::join( 'lmscontents', 'lmscontents.id', 'lmscontents_track.content_id' )->where( 'user_id', $user_id )
						->where( 'lmscontents_track.content_id', $lesson_id );
			if ( ! empty( $course_id ) ) {
				$track_history = $track_history->where( 'lmscontents_track.course_id', '=', $course_id );
			}
			if ( ! empty( $module_id ) ) {
				$track_history = $track_history->where( 'lmscontents_track.module_id', '=', $module_id );
			}
			$track_history = $track_history->get();
		}

		$video_completed = $text_completed = $quiz_completed = FALSE;
		// dd( $lesson_id );
		if ( ! empty( $details ) ) {
			if ( ! empty( $track_history ) ) {
				foreach( $track_history as $history ) {
					// Video.
					if ( ! empty( $details->file_path_video ) ) {
						if ( $history->status == 'completed' && $history->type == 'video' ) {
							$video_completed = TRUE;
						}
					} else {
						$video_completed = TRUE;
					}

					// Description.
					if ( ! empty( $details->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}

					// Quiz.
					if ( ! empty( $details->quiz_id ) ) {
						if ( $history->status == 'completed' && $history->type == 'quiz' ) {
							$quiz_completed = TRUE;
						}
					} else {
						$quiz_completed = TRUE;
					}

				}
			}
			if ( ! empty( $content_type ) ) {
				if ( $content_type == 'video' ) {
					return $video_completed;
				}
				if ( $content_type == 'text' ) {
					return $text_completed;
				}
				if ( $content_type == 'quiz' ) {
					return $quiz_completed;
				}
			} else {
				/*
				if ( empty( $content_type ) ) {
					dd( $track_history );
					var_dump( $video_completed );
					var_dump( $text_completed );
					var_dump( $quiz_completed );die();
				}
				*/
				if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE ) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}
	} else {
		return FALSE;
	}
}

/**
 * This function will check the if the lesson completed in the particular course/module
 * @param int $lesson_id - Lessons ID
 * @param int $course_id - Course ID
 * @param int $module_id - Module ID
 * @param string $content_type
 * @param string $type
 * @param int $user_id
 */

 function is_lesson_completed( $lesson_id, $course_id, $module_id = '', $content_type = '', $type = 'content', $user_id = '' ) {
	if ( Auth::check() ) {
		if ( empty( $user_id ) ) {
			$user_id = Auth::User()->id;
		}
		$details = App\LmsContent::where( 'id', $lesson_id )->first();
		// Let us check if the content has pieces
		$pieces_completed = TRUE;
		$pieces = App\LmsContent::where( 'parent_id', $lesson_id )->get();

		if ( ! empty( $pieces ) ) {
			foreach( $pieces as $piece ) {
				
				if ( ! is_lesson_completed( $piece->id, $course_id, $module_id, $content_type, $type, $user_id ) ) {
					$pieces_completed = FALSE;
				}
			}
		}
		
		if ( ! empty( $content_type ) ) {
			$track_history = App\LmsTrack::join( 'lmscontents', 'lmscontents.id', 'lmscontents_track.content_id' )->where( 'user_id', $user_id )
						->where( 'lmscontents_track.content_id', $lesson_id )
						->where( 'lmscontents_track.type', $content_type );
			if ( ! empty( $course_id ) ) {
				if ( $type == 'group' ) {
					// $track_history = $track_history->where( 'lmscontents_track.group_id', '=', $course_id );
				} else {
					// $track_history = $track_history->where( 'lmscontents_track.course_id', '=', $course_id );
				}
			}
			if ( ! empty( $module_id ) ) {
				// $track_history = $track_history->where( 'lmscontents_track.module_id', '=', $module_id );
			}
			$track_history = $track_history->get();
		} else {
			$track_history = App\LmsTrack::join( 'lmscontents', 'lmscontents.id', 'lmscontents_track.content_id' )->where( 'user_id', $user_id )
						->where( 'lmscontents_track.content_id', $lesson_id );
			if ( ! empty( $course_id ) ) {
				if ( $type == 'group' ) {
					// $track_history = $track_history->where( 'lmscontents_track.group_id', '=', $course_id );
				} else {
					// $track_history = $track_history->where( 'lmscontents_track.course_id', '=', $course_id );
				}
			}
			if ( ! empty( $module_id ) ) {
				// $track_history = $track_history->where( 'lmscontents_track.module_id', '=', $module_id );
			}
			$track_history = $track_history->get();
			if ( $type == 'group' ) {
				// echo $user_id;
				// echo $course_id;
				// echo $lesson_id;
				// dd($track_history->toSql());
			}
		}
		if ( Auth::User()->id == 14 ) {
			// dd( $track_history );
		}
		$video_completed = $text_completed = $quiz_completed = FALSE;

		if ( ! empty( $details ) ) {
			if ( ! empty( $track_history ) ) {
				foreach( $track_history as $history ) {
					// Video.
					if ( ! empty( $details->file_path_video ) ) {
						if ( $history->status == 'completed' && $history->type == 'video' ) {
							$video_completed = TRUE;
						}
					} else {
						$video_completed = TRUE;
					}

					// Description.
					if ( ! empty( $details->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}

					// Quiz.
					if ( ! empty( $details->quiz_id ) ) {
						if ( $history->status == 'completed' && $history->type == 'quiz' ) {
							$quiz_completed = TRUE;
						}
					} else {
						$quiz_completed = TRUE;
					}

				}
			}
			if ( ! empty( $content_type ) ) {
				if ( $content_type == 'video' ) {
					return $video_completed;
				}
				if ( $content_type == 'text' ) {
					return $text_completed;
				}
				if ( $content_type == 'quiz' ) {
					return $quiz_completed;
				}
			} else {
				if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE && $pieces_completed == TRUE ) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}
	} else {
		return FALSE;
	}
}

/*
function is_lesson_completed( $lesson_id, $course_id, $module_id = '', $content_type = '', $type = 'content', $user_id = '' ) {
	if ( Auth::check() ) {
		if ( empty( $user_id ) ) {
			$user_id = Auth::User()->id;
		}
		$details = App\LmsContent::where( 'id', $lesson_id )->first();
		// Let us check if the content has pieces
		$pieces_completed = TRUE;
		$pieces = App\LmsContent::where( 'parent_id', $lesson_id )->get();

		if ( ! empty( $pieces ) ) {
			foreach( $pieces as $piece ) {
				
				if ( ! is_lesson_completed( $piece->id, $course_id, $module_id, $content_type, $type, $user_id ) ) {
					$pieces_completed = FALSE;
				}
			}
		}
		if ( ! empty( $content_type ) ) {
			$track_history = App\LmsTrack::join( 'lmscontents', 'lmscontents.id', 'lmscontents_track.content_id' )->where( 'user_id', $user_id )
						->where( 'lmscontents_track.content_id', $lesson_id )
						->where( 'lmscontents_track.type', $content_type );
			if ( ! empty( $course_id ) ) {
				$track_history = $track_history->where( 'lmscontents_track.course_id', '=', $course_id );
			}
			if ( ! empty( $module_id ) ) {
				$track_history = $track_history->where( 'lmscontents_track.module_id', '=', $module_id );
			}
			$track_history = $track_history->get();
		} else {
			$track_history = App\LmsTrack::join( 'lmscontents', 'lmscontents.id', 'lmscontents_track.content_id' )->where( 'user_id', $user_id )
						->where( 'lmscontents_track.content_id', $lesson_id );
			if ( ! empty( $course_id ) ) {
				// $track_history = $track_history->where( 'lmscontents_track.course_id', '=', $course_id );
			}
			if ( ! empty( $module_id ) ) {
				// $track_history = $track_history->where( 'lmscontents_track.module_id', '=', $module_id );
			}
			$track_history = $track_history->get();
		}
		
		$video_completed = $text_completed = $quiz_completed = FALSE;

		if ( ! empty( $details ) ) {
			if ( ! empty( $track_history ) ) {
				foreach( $track_history as $history ) {
					// Video.
					if ( ! empty( $details->file_path_video ) ) {
						if ( $history->status == 'completed' && $history->type == 'video' ) {
							$video_completed = TRUE;
						}
					} else {
						$video_completed = TRUE;
					}

					// Description.
					if ( ! empty( $details->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}

					// Quiz.
					if ( ! empty( $details->quiz_id ) ) {
						if ( $history->status == 'completed' && $history->type == 'quiz' ) {
							$quiz_completed = TRUE;
						}
					} else {
						$quiz_completed = TRUE;
					}

				}
			}
			if ( ! empty( $content_type ) ) {
				if ( $content_type == 'video' ) {
					return $video_completed;
				}
				if ( $content_type == 'text' ) {
					return $text_completed;
				}
				if ( $content_type == 'quiz' ) {
					return $quiz_completed;
				}
			} else {
				if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE && $pieces_completed == TRUE ) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}
	} else {
		return FALSE;
	}
}
*/
function last_visited( $course_id )
{
	$track_history = App\LmsTrack::select(['lmscontents_track.created_at'])->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
	->where('lmscontents_track.course_id', '=', $course_id)
	->where('lmscontents_track.user_id', '=', Auth::User()->id)
	->orderBy( 'lmscontents_track.created_at', 'desc' );
	if ( $track_history->count() > 0 ) {
		return $track_history->first()->created_at->diffForHumans();
	} else {
		return '';
	}
}

function date_cast( $date )
{
	return \Carbon\Carbon::parse($date);
}

function course_finished_date( $course_id )
{
	$track_history = App\MyCourses::select(['course_completed'])
	->where('course_id', '=', $course_id)
	->where('user_id', '=', get_current_user_id())
	->where('course_status', '=', 'completed')
	->orderBy( 'created_at', 'desc' );
	if ( $track_history->count() > 0 ) {
		$course_completed = strtotime( $track_history->first()->course_completed );
		return date_cast( $track_history->first()->course_completed )->diffForHumans();
	} else {
		return '';
	}
}

function course_start_date( $course_id )
{
	$track_history = App\MyCourses::where('course_id', '=', $course_id)
	->where('user_id', '=', Auth::User()->id)
	->orderBy( 'created_at', 'asc' );
	if ( $track_history->count() > 0 ) {
		return $track_history->first()->created_at->diffForHumans();
	} else {
		return '';
	}
}

function pathway_contents( $subject_id = 0, $course_id = 0 )
{
	if ( Auth::check() ) {
		if ( $subject_id > 0 ) {
			// $contents = App\LmsContent::where( 'subject_id', '=', $subject_id )->get();
			$contents = collect();
			$options = array( 'order_by' => 'ls.subject_id' );
			if ( $course_id > 0 ) {
				$options['course_id'] = $course_id;
			}
			$courses = subject_courses_new( $subject_id, $options );
			if ( $courses->count() > 0 ) {
				foreach( $courses as $course ) {
					$lessons_pieces_new = lessons_pieces_new( $course->id );
					$contents = $contents->merge( $lessons_pieces_new['lessons'] );
				}
			}
			
			/**
			 * Let us take unique contents only.
			 */
			$unique_ids = array();
			$contents_old = $contents;
			$contents = collect();
			foreach( $contents_old as $content ) {
				if ( ! in_array( $content->id, $unique_ids ) ) {
					$contents = $contents->push( $content );
					$unique_ids[] = $content->id;
				}
			}
			// dd( $contents );
			
			// $contents = $contents->groupBy('id');
			// dd( $contents );
			// Let us collect all 'Pathway' posts
			$pathway_contents_posts = pathway_contents_posts( $subject_id );
			if ( ! empty( $pathway_contents_posts ) ) {
				$contents = $contents->merge( $pathway_contents_posts );
			}
			$contents = $contents->sortBy('post_date');

			return $contents;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

function pathway_contents_posts( $subject_id = 0 )
{
	if ( Auth::check() ) {
		if ( $subject_id > 0 ) {
			$subject = get_subject_by_id( $subject_id );
			$contents = Corcel\Model\Post::published()->where('post_type', '=', 'post')->hasMeta('pathway', $subject)->get(); // setting key and value
			
			$unique_ids = array();
			$contents_old = $contents;
			$contents = collect();
			foreach( $contents_old as $content ) {
				if ( ! in_array( $content->ID, $unique_ids ) ) {
					$contents = $contents->push( $content );
					$unique_ids[] = $content->id;
				}
			}
			return $contents;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

/*
function completed_pieces_new( $subject_id = 0, $lesson_id = 0, $course_id = 0 )
{
	$track_history = array();
	if ( Auth::check() ) {
		if ( $subject_id > 0 ) {
		$track_history = App\LmsTrack::
			select( ['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id'] )
			->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
			->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
			->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->join('users_my_courses AS umc', function( $join ) {
				$join->on('umc.course_id', '=', 'ls.id')
				->on('umc.user_id', '=', 'lmscontents_track.user_id');
			})
			->where( 'lmscontents_track.user_id', Auth::User()->id )
			->where( 'lmscontents.subject_id', $subject_id )
			->where( 'ls.status', '=', 'active' )
			->where( 'qc.category_status', '=', 'active' )
			->where( 'lmscontents_track.content_type', '=', 'course' )
			->groupBy( 'lmscontents_track.content_id');
		if ( $course_id > 0 ) {
			$track_history = $track_history->where('umc.course_id', '=', $course_id);
		}
		$track_history = $track_history->get();
		} else {
			if ( $lesson_id > 0 ) {
				$track_history = App\LmsTrack::select(['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id'])
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
				->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
				->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
				->join('users_my_courses AS umc', function( $join ) {
					$join->on('umc.course_id', '=', 'ls.id')
					->on('umc.user_id', '=', 'lmscontents_track.user_id');
				})
				->where( 'lmscontents_track.user_id', Auth::User()->id )
				->where( 'lmscontents_track.content_id', $lesson_id )
				->where( 'ls.status', '=', 'active' )
				->where( 'qc.category_status', '=', 'active' )
				->where( 'lmscontents_track.content_type', '=', 'course' )
				->groupBy( 'lmscontents_track.content_id');
				if ( $course_id > 0 ) {
					$track_history = $track_history->where('umc.course_id', '=', $course_id);
				}
				$track_history = $track_history->get();
			} else {
				$track_history = App\LmsTrack::select(['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id'])
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
				->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
				->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
				->join('users_my_courses AS umc', function( $join ) {
					$join->on('umc.course_id', '=', 'ls.id')
					->on('umc.user_id', '=', 'lmscontents_track.user_id');
				})
				->where( 'lmscontents_track.user_id', Auth::User()->id )
				->where( 'ls.status', '=', 'active' )
				->where( 'qc.category_status', '=', 'active' )
				->where( 'lmscontents_track.content_type', '=', 'course' )
				->groupBy( 'lmscontents_track.content_id');
				if ( $course_id > 0 ) {
					$track_history = $track_history->where('umc.course_id', '=', $course_id);
				}
				$track_history = $track_history->get();
			}
		}
	}
	$content_history = array();

	$video_completed = $text_completed = $quiz_completed = FALSE;
	if ( ! empty( $track_history ) ) {
		foreach( $track_history as $history ) {
			if ( is_lesson_piece_completed_new( $history->content_id, $history->module_id, $history->course_id ) ) {
				$content_history[ 'course_' . $history->content_id ] = $history;
			}
		}
	}

	// Let us check for posts if any completed!
	$completed_pieces_new_posts = completed_pieces_new_posts( $subject_id, $lesson_id);
	if ( ! empty( $completed_pieces_new_posts ) ) {
		foreach( $completed_pieces_new_posts as $key => $completed_pieces_new_post ) {
			$content_history[ $key ] = $completed_pieces_new_post;
		}
	}
	return $content_history;
}
*/

function completed_pieces_new( $subject_id = 0, $lesson_id = 0, $course_id = 0 )
{
	$track_history = array();
	if ( Auth::check() ) {
		if ( $subject_id > 0 ) {
		$track_history = App\LmsTrack::
			select( ['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id', 'lmscontents_track.created_at'] )
			->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
			/* ->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
			 ->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->join('users_my_courses AS umc', function( $join ) {
				$join->on('umc.course_id', '=', 'ls.id')
				->on('umc.user_id', '=', 'lmscontents_track.user_id');
			})*/
			->where( 'lmscontents_track.user_id', Auth::User()->id )
			->where( 'lmscontents.subject_id', $subject_id )
			// ->where( 'ls.status', '=', 'active' )
			// ->where( 'qc.category_status', '=', 'active' )
			// ->where( 'lmscontents_track.content_type', '=', 'course' )
			->where( function( $where ) {
				$where->orWhere( 'lmscontents_track.content_type', '=', 'course' )
				->orWhere( 'lmscontents_track.content_type', '=', 'group' );
			})
			->orderBy('lmscontents_track.created_at', 'desc')
			->groupBy( 'lmscontents_track.content_id');
		if ( $course_id > 0 ) {
			// $track_history = $track_history->where('umc.course_id', '=', $course_id);
		}
		
		$track_history = $track_history->get();
		//dd( $track_history->toSql());
		} else {
			if ( $lesson_id > 0 ) {
				$track_history = App\LmsTrack::select(['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id', 'lmscontents_track.created_at'])
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
				/* ->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
				->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
				->join('users_my_courses AS umc', function( $join ) {
					$join->on('umc.course_id', '=', 'ls.id')
					->on('umc.user_id', '=', 'lmscontents_track.user_id');
				})*/
				->where( 'lmscontents_track.user_id', Auth::User()->id )
				->where( 'lmscontents_track.content_id', $lesson_id )
				// ->where( 'ls.status', '=', 'active' )
				// ->where( 'qc.category_status', '=', 'active' )
				->where( function( $where ) {
					$where->orWhere( 'lmscontents_track.content_type', '=', 'course' )
					->orWhere( 'lmscontents_track.content_type', '=', 'group' );
				})
				->groupBy( 'lmscontents_track.content_id');
				if ( $course_id > 0 ) {
					$track_history = $track_history->where('umc.course_id', '=', $course_id);
				}
				$track_history = $track_history->get();
			} else {
				$track_history = App\LmsTrack::select(['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id', 'lmscontents_track.created_at'])
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
				/* ->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
				->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
				->join('users_my_courses AS umc', function( $join ) {
					$join->on('umc.course_id', '=', 'ls.id')
					->on('umc.user_id', '=', 'lmscontents_track.user_id');
				})*/
				->where( 'lmscontents_track.user_id', Auth::User()->id )
				// ->where( 'ls.status', '=', 'active' )
				// ->where( 'qc.category_status', '=', 'active' )
				->where( function( $where ) {
					$where->orWhere( 'lmscontents_track.content_type', '=', 'course' )
					->orWhere( 'lmscontents_track.content_type', '=', 'group' );
				})
				->groupBy( 'lmscontents_track.content_id');
				if ( $course_id > 0 ) {
					$track_history = $track_history->where('umc.course_id', '=', $course_id);
				}
				$track_history = $track_history->get();
			}
		}
	}
	$content_history = array();
	$not_content_history = array();
	
		// dd( $track_history );
		
	$video_completed = $text_completed = $quiz_completed = FALSE;
	if ( ! empty( $track_history ) ) {
		foreach( $track_history as $history ) {
			// echo $history->content_id . '<br>';
			if ( is_lesson_piece_completed_new( $history->content_id, $history->module_id, $history->course_id ) ) {
				$content_history[ 'course_' . $history->content_id ] = $history;
			} else {
				$not_content_history[ 'course_' . $history->content_id ] = $history;
			}
		}
	}
	// dd( $not_content_history );
	// Let us check for posts if any completed!
	$completed_pieces_new_posts = completed_pieces_new_posts( $subject_id, $lesson_id);
	if ( ! empty( $completed_pieces_new_posts ) ) {
		foreach( $completed_pieces_new_posts as $key => $completed_pieces_new_post ) {
			$content_history[ $key ] = $completed_pieces_new_post;
		}
	}
	
	usort( $content_history, "sort_track_history" );
	
	return $content_history;
}

function sort_track_history( $a, $b ) {
	return ( strtotime( $a->created_at ) > strtotime( $b->created_at ) ) ? TRUE : FALSE;
}
function completed_pieces_new_posts( $subject_id = 0, $lesson_id = 0 )
{
	$track_history = array();
	$subject = '';
	if ( $subject_id == PATHWAYSTART_ID ) {
		$subject = 'pathwaystart';
	}
	if ( $subject_id == PATHWAYFORWARD_ID ) {
		$subject = 'pathwayforward';
	}
	if ( $subject_id == PATHWAYFOREVER_ID ) {
		$subject = 'pathwayforever';
	}
	if ( Auth::check() ) {
		if ( $subject_id > 0 ) {
		$track_history = App\LmsTrack::
			select( [TBL_WP_POSTS . '.post_title AS title', TBL_WP_POSTS . '.post_content AS description', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id', 'lmscontents_track.created_at'] )
			->join(TBL_WP_POSTS, TBL_WP_POSTS . '.id', '=', 'lmscontents_track.content_id')
			->join(TBL_WP_POSTMETA, TBL_WP_POSTMETA.'.post_id', '=', TBL_WP_POSTS . '.ID')
			->where( 'lmscontents_track.user_id', Auth::User()->id )
			->where( TBL_WP_POSTMETA . '.meta_key', 'pathway' )
			->where( TBL_WP_POSTMETA . '.meta_value', $subject )
			->where( 'lmscontents_track.content_type', '=', 'post' )
			->orderBy('lmscontents_track.created_at', 'desc')
			->groupBy( 'lmscontents_track.content_id');

		$track_history = $track_history->get();
		} else {
			if ( $lesson_id > 0 ) {

				$track_history = App\LmsTrack::
					select( [TBL_WP_POSTS . '.post_title AS title', TBL_WP_POSTS . '.post_content AS description', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id', 'lmscontents_track.created_at'] )
					->join(TBL_WP_POSTS, TBL_WP_POSTS . '.id', '=', 'lmscontents_track.content_id')
					->join(TBL_WP_POSTMETA, TBL_WP_POSTMETA.'.post_id', '=', TBL_WP_POSTS . '.ID')
					->where( 'lmscontents_track.user_id', Auth::User()->id )
					->where( 'lmscontents_track.content_id', $lesson_id )
					->where( 'lmscontents_track.content_type', '=', 'post' )
					->groupBy( 'lmscontents_track.content_id')
					->get();
			} else {
				$track_history = App\LmsTrack::
					select( [TBL_WP_POSTS . '.post_title AS title', TBL_WP_POSTS . '.post_content AS description', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id', 'lmscontents_track.created_at'] )
					->join(TBL_WP_POSTS, TBL_WP_POSTS . '.id', '=', 'lmscontents_track.content_id')
					->join(TBL_WP_POSTMETA, TBL_WP_POSTMETA.'.post_id', '=', TBL_WP_POSTS . '.ID')
					->where( 'lmscontents_track.user_id', Auth::User()->id )
					->where( 'lmscontents_track.content_type', '=', 'post' )
					->groupBy( 'lmscontents_track.content_id')
					->get();
			}
		}
	}

	$content_history = array();
	if ( ! empty( $track_history ) ) {
		foreach( $track_history as $history ) {
			if ( is_lesson_piece_completed_new_post( $history->content_id, $history->module_id, $history->course_id ) ) {
				$content_history[ 'post_' . $history->content_id ] = $history;
			}
		}
	}
	return $content_history;
}

function is_lesson_piece_completed_new_post( $content_id, $module_id = '', $course_id = '' )
{
	if ( Auth::check() ) {
		$track_history = App\LmsTrack::
			select( [TBL_WP_POSTS . '.post_title AS title', TBL_WP_POSTS . '.post_content AS description', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id', 'lmscontents_track.course_id', 'lmscontents_track.module_id'] )
			->join(TBL_WP_POSTS, TBL_WP_POSTS . '.id', '=', 'lmscontents_track.content_id')
			->where( 'lmscontents_track.user_id', Auth::User()->id )
			->where( 'lmscontents_track.content_type', '=', 'post' )
			->where( TBL_WP_POSTS . '.ID', '=', $content_id )
			// ->where( 'lmscontents_track.module_id', '=', $module_id )
			// ->where( 'lmscontents_track.course_id', '=', $course_id )
			;

		$track_history = $track_history->get();
		if ( $content_id == 1304 ) {
		// dd( $track_history );
		}
		if ( $track_history->count() > 0 ) {
			$video_completed = TRUE; // Because there is no Video for posts
			$text_completed = $quiz_completed = FALSE;
			foreach( $track_history as $history ) {

				if ( empty( $history->description ) ) {
					$text_completed = TRUE;
				}

				// Description.
				if ( $history->type == 'text' ) {
					if ( ! empty( $history->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}
				}

				// Quiz.
				$post_quiz = DB::table( TBL_WP_POSTS )->select(['meta_value'])->join(TBL_WP_POSTMETA, TBL_WP_POSTMETA . '.post_id', '=', TBL_WP_POSTS . '.ID')
				->where(TBL_WP_POSTMETA . '.meta_key', '=', 'quiz_id')
				->where(TBL_WP_POSTS . '.ID', '=', $content_id)
				->first();

				if ( empty( $post_quiz ) ) {
					$quiz_completed = TRUE;
				} else {
					$quiz_id = $post_quiz->meta_value;
					if( empty( $quiz_id ) ) {
						$quiz_completed = TRUE;
					} else {
						if ( $history->status == 'completed' && $history->type == 'quiz' ) {
							$quiz_completed = TRUE;
						}
					}
				}
			}
			/*
			if ( $content_id == 1304 ) {
				var_dump( $video_completed );
				var_dump( $text_completed );
				var_dump( $quiz_completed );
				die();
			}
			*/
			if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE ) {
				return TRUE;
			}
		}
	} else {
		return FALSE;
	}
}
/*
function is_lesson_piece_completed_new( $content_id, $module_id, $course_id )
{
	if ( Auth::check() ) {
		$track_history = App\LmsTrack::
			select( ['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id'] )
			->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
			->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
			->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->join('users_my_courses AS umc', function( $join ) {
				$join->on('umc.course_id', '=', 'ls.id')
				->on('umc.user_id', '=', 'lmscontents_track.user_id');
			})
			->where( 'lmscontents_track.user_id', Auth::User()->id )
			->where( 'ls.status', '=', 'active' )
			->where( 'qc.category_status', '=', 'active' )
			->where( 'lmscontents_track.content_id', '=', $content_id )
			->where( 'lmscontents_track.module_id', '=', $module_id )
			->where( 'lmscontents_track.course_id', '=', $course_id )
			->get();
		if ( $track_history->count() > 0 ) {
			$video_completed = $text_completed = $quiz_completed = FALSE;
			foreach( $track_history as $history ) {
				if ( empty( $history->file_path_video ) ) {
					$video_completed = TRUE;
				}
				if ( empty( $history->description ) ) {
					$text_completed = TRUE;
				}
				if ( empty( $history->quiz_id ) ) {
					$quiz_completed = TRUE;
				}
				// Video.
				if ( $history->type == 'video' ) {
					if ( ! empty( $history->file_path_video ) ) {
						if ( $history->status == 'completed' && $history->type == 'video' ) {
							$video_completed = TRUE;
						}
					} else {
						$video_completed = TRUE;
					}
				}

				// Description.
				if ( $history->type == 'text' ) {
					if ( ! empty( $history->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}
				}

				// Quiz.
				if ( ! empty( $history->quiz_id ) ) {
					if ( $history->status == 'completed' && $history->type == 'quiz' ) {
						$quiz_completed = TRUE;
					}
				} else {
					$quiz_completed = TRUE;
				}
			}

			if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE ) {
				return TRUE;
			}
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}
*/
function is_lesson_piece_completed_new( $content_id, $module_id = '', $course_id = '' )
{
	if ( Auth::check() ) {
		$track_history = App\LmsTrack::
			select( ['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id'] )
			->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
			/*->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
			->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->join('users_my_courses AS umc', function( $join ) {
				$join->on('umc.course_id', '=', 'ls.id')
				->on('umc.user_id', '=', 'lmscontents_track.user_id');
			})
			*/
			->where( 'lmscontents_track.user_id', Auth::User()->id )
			// ->where( 'ls.status', '=', 'active' )
			// ->where( 'qc.category_status', '=', 'active' )
			->where( 'lmscontents_track.content_id', '=', $content_id )
			//->where( 'lmscontents_track.module_id', '=', $module_id )
			//->where( 'lmscontents_track.course_id', '=', $course_id )
			->get();
		
		if ( $track_history->count() > 0 ) {
			$video_completed = $text_completed = $quiz_completed = FALSE;
			foreach( $track_history as $history ) {
				if ( empty( $history->file_path_video ) ) {
					$video_completed = TRUE;
				}
				if ( empty( $history->description ) ) {
					$text_completed = TRUE;
				}
				if ( empty( $history->quiz_id ) ) {
					$quiz_completed = TRUE;
				}
				// Video.
				if ( $history->type == 'video' ) {
					if ( ! empty( $history->file_path_video ) ) {
						if ( $history->status == 'completed' && $history->type == 'video' ) {
							$video_completed = TRUE;
						}
					} else {
						$video_completed = TRUE;
					}
				}

				// Description.
				if ( $history->type == 'text' ) {
					if ( ! empty( $history->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}
				}

				// Quiz.
				if ( ! empty( $history->quiz_id ) ) {
					if ( $history->status == 'completed' && $history->type == 'quiz' ) {
						$quiz_completed = TRUE;
					}
				} else {
					$quiz_completed = TRUE;
				}
			}

			if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE ) {
				return TRUE;
			}
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

function completed_contents( $subject_id = 0, $lesson_id = 0 )
{
	$track_history = array();
	if ( Auth::check() ) {
		if ( $subject_id > 0 ) {
		$track_history = App\LmsTrack::
			select( ['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id'] )
			->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
			->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
			->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			// ->join('users_my_courses AS umc', 'umc.course_id', '=', 'ls.id' )
			->join('users_my_courses AS umc', function( $join ) {
				$join->on('umc.course_id', '=', 'ls.id')
				->on('umc.user_id', '=', 'lmscontents_track.user_id');
			})
			->where( 'lmscontents_track.user_id', Auth::User()->id )
			->where( 'lmscontents.subject_id', $subject_id )
			->where( 'ls.status', '=', 'active' )
			->where( 'qc.category_status', '=', 'active' )
			//->where( 'lmscontents_track.content_id', '=', '105' );
			->get();
		} else {
			if ( $lesson_id > 0 ) {
				$track_history = App\LmsTrack::select(['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id'])
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
				->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
				->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
				->join('users_my_courses AS umc', 'umc.course_id', '=', 'ls.id' )
				->where( 'lmscontents_track.user_id', Auth::User()->id )
				->where( 'lmscontents_track.content_id', $lesson_id )
				->where( 'ls.status', '=', 'active' )
				->where( 'qc.category_status', '=', 'active' )
				->get();
			} else {
				$track_history = App\LmsTrack::select(['lmscontents.title', 'lmscontents.file_path_video', 'lmscontents.description', 'lmscontents.quiz_id', 'lmscontents_track.user_id', 'lmscontents_track.status', 'lmscontents_track.type', 'lmscontents_track.content_id', 'lmscontents_track.history_id'])
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
				->join('lmsseries AS ls', 'ls.id', '=', 'lmscontents_track.course_id' )
				->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
				->join('users_my_courses AS umc', 'umc.course_id', '=', 'ls.id' )
				->where( 'lmscontents_track.user_id', Auth::User()->id )
				->where( 'ls.status', '=', 'active' )
				->where( 'qc.category_status', '=', 'active' );
				// dd( $track_history->toSql() );
				$track_history = $track_history->get();
			}
		}
	}
	$content_history = array();
	$video_completed = $text_completed = $quiz_completed = FALSE;
	// dd( $track_history->toSql() );

	if ( ! empty( $track_history ) ) {
		foreach( $track_history as $history ) {

			// $video_completed = $text_completed = $quiz_completed = FALSE;

			if ( empty( $history->file_path_video ) ) {
				$video_completed = TRUE;
			}
			if ( empty( $history->description ) ) {
				$text_completed = TRUE;
			}
			if ( empty( $history->quiz_id ) ) {
				$quiz_completed = TRUE;
			}
			// Video.
			if ( $history->type == 'video' ) {
				if ( ! empty( $history->file_path_video ) ) {
					if ( $history->status == 'completed' && $history->type == 'video' ) {
						$video_completed = TRUE;
					}
				} else {
					$video_completed = TRUE;
				}
			}

			// Description.
			if ( $history->type == 'text' ) {
				if ( ! empty( $history->description ) ) {
					if ( $history->status == 'completed' && $history->type == 'text' ) {
						$text_completed = TRUE;
					}
				} else {
					$text_completed = TRUE;
				}
			}

			// Quiz.
			if ( ! empty( $history->quiz_id ) ) {
				if ( $history->status == 'completed' && $history->type == 'quiz' ) {
					$quiz_completed = TRUE;
				}
			} else {
				$quiz_completed = TRUE;
			}

			if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE ) {
				$content_history[ $history->content_id ] = $history;
			}
		}
	}
	return $content_history;
}

function lmscontent_comments( $content_id )
{
	$track_history = App\LmsComments::where( 'content_id', '=', $content_id )
	->where( 'type', '=', 'comments' )
		->get();
	return $track_history;
}

function save_update_wp( $table, $operation, $data = array(), $condition = array() )
{
	if ( empty( $data ) ) {
		return;
	}

	if ( $table == 'users' ) {
		if ( $operation == 'update' ) {
			DB::table( WP_TABLE_PREFIX .'users')->where( $condition['key'], $condition['value'] )->update( $data['main'] );

			if ( ! empty( $data['meta'] ) ) {
				$meta_table = WP_TABLE_PREFIX . 'usermeta';
				$meta_type = 'user';
				foreach( $data['meta'] as $key => $value ) {
					if ( ! empty( $condition['value'] ) ) :
						$column = $meta_type . '_id';
						$check = DB::table( $meta_table )->where( 'meta_key', '=', $key )->where( $column, '=', $condition['value'] )->first();
						if ( $check ) {
							DB::table( $meta_table )->where( 'meta_key', '=', $key )->where( $column, '=', $condition['value'] )->update( array( $column => $condition['value'], 'meta_value' => $value ) );
						} else {
							DB::table( $meta_table )->insert( array( $column => $condition['value'], 'meta_value' => $value ) );
						}
					endif;
				}
			}
		} elseif ( $operation == 'create' ) {
			$email_check = DB::table( WP_TABLE_PREFIX .'users')->where( 'user_email', '=', $data['main']['user_email'] );
			if ( $email_check->count() == 0 ) {
				$username_check = DB::table( WP_TABLE_PREFIX .'users')->where( 'user_login', '=', $data['main']['user_login'] );
				if ( $username_check->count() > 0 ) {
					$data['main']['user_login'] = $data['main']['user_login'] . $username_check->count();
				}
				$wp_user_id = DB::table( WP_TABLE_PREFIX .'users')->insertGetId( $data['main'] );
				if ( ! empty( $data['meta'] ) ) {
					$usermeta = $data['meta'];
					foreach( $usermeta as $meta_key => $meta_value ) {
						$metarow = array(
							'user_id' => $wp_user_id,
							'meta_key' => $meta_key,
							'meta_value' => $meta_value,
						);
						DB::table( WP_TABLE_PREFIX . 'usermeta' )->insert( $metarow );
					}
				}
				// Let us update 'wp_user_id' in LMS table so that we can use it later
				DB::table( 'users' )
					->where( 'id', '=', $condition['lms_user_id'] )
					->update(
					array( 'wp_user_id' => $wp_user_id )
				);
			}
		}

	}
}

function knowing_god_get_countries() {
	$countries = DB::table('wp_kg_countries')->get();
	$countries_array = array();
	if ( $countries->count() > 0 ) {
		foreach( $countries as $country ) {
			$countries_array[ $country->phonecode . '_' . $country->id_countries ] = $country->name . ' ( ' . $country->phonecode . ' )';
		}
	}
	return $countries_array;
}

function attempted_courses( $output_type = 'array', $options = array() ) {
	$track_history = App\LmsTrack::
		select( 'ls.*' )
		->join('lmscontents', 'lmscontents.id', '=', 'lmscontents_track.content_id')
		->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lmscontents.id' )
		->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' );
	if ( Auth::check() ) {
		$track_history = $track_history->where( 'lmscontents_track.user_id', Auth::User()->id );
	}
	if ( ! empty( $options['exclude_completed'] ) ) {
		$completed_courses = completed_courses();
		if ( ! empty( $completed_courses ) ) {
			$track_history = $track_history->whereNotIn( 'ls.id', $completed_courses );
		}
	}
	if ( ! empty( $options['limit_records'] ) ) {
		$track_history = $track_history->limit( $options['limit_records'] );
	}
	$track_history = $track_history->groupBy('ld.lmsseries_id')
		->inRandomOrder();
	if ( ! empty( $options['limit_records'] ) && $options['limit_records'] == 1 ) {
		$track_history = $track_history->first();
	} else {
	$track_history = $track_history->get();
	}
	if ( $output_type == 'records' ) {
		return $track_history;
	} else {
		$attempted_courses = array();
		if ( ! empty( $track_history ) ) {
			foreach ( $track_history as $history ) {
				array_push($attempted_courses, $history->id );
			}
		}
		return $attempted_courses;
	}
}

function attempted_courses_new( $output_type = 'array', $options = array() ) {
	if ( Auth::check() ) {
		$track_history = App\MyCourses::select(['ls.*', 'subjects.subject_title', 'subjects.color_class', 'users_my_courses.created_at AS course_start', 'users_my_courses.course_status', 'users_my_courses.course_completed'])
			->join( 'lmsseries AS ls', 'ls.id', '=', 'users_my_courses.course_id' )
			->join( 'subjects', 'subjects.id', '=', 'ls.subject_id' )
			->where('ls.parent_id', '=', 0)
			->join('quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id')
			->where( 'ls.status', '=', 'active' )
			->where('qc.category_status','=','active' )
			->where('users_my_courses.content_type', '=', 'course');
		// $track_history = $track_history->where('ls.id', '=', '75');


		if ( Auth::check() ) {
			$track_history = $track_history->where( 'user_id', Auth::User()->id );
		}
		/*
		if ( ! empty( $options['exclude_completed'] ) ) {
			$completed_courses = completed_courses();
			if ( ! empty( $completed_courses ) ) {
				$track_history = $track_history->whereNotIn( 'ls.id', $completed_courses );
			}
		}
		*/
		if ( ! empty( $options['course_status'] ) ) {			
			$track_history = $track_history->where( 'course_status', $options['course_status'] );		
		}
		if ( ! empty( $options['exclude_completed'] ) ) {			
			$track_history = $track_history->where( 'course_status', 'running' );		
		}
		if ( ! empty( $options['limit_records'] ) ) {
			$track_history = $track_history->limit( $options['limit_records'] );
		}
		$track_history = $track_history->groupBy('ls.id');
		if ( ! empty( $options['order_by'] ) ) {
			$track_history = $track_history->orderBy( $options['order_by']['column'], $options['order_by']['order'] );
		} else {
			$track_history = $track_history->inRandomOrder();
		}
		if ( ! empty( $options['limit_records'] ) && $options['limit_records'] == 1 ) {
			$track_history = $track_history->first();
		} else {
			if ( ! empty( $options['paginate'] ) ) {
				$track_history = $track_history->paginate( $options['paginate'] );
			} else {
				$track_history = $track_history->get();
			}
		}
		if ( $output_type == 'records' ) {
			return $track_history;
		} else {
			$attempted_courses = array();
			if ( ! empty( $track_history ) ) {
				foreach ( $track_history as $history ) {
					array_push($attempted_courses, $history->id );
				}
			}
			return $attempted_courses;
		}
	} else {
		return array();
	}
}

function my_courses_profile_series()
{
	$track_history = App\MyCourses::select(['ls.name AS title', 'ls.slug', DB::raw('term_group AS series_image'), 'ls.term_id'])
		->join( TBL_WP_TERMS . ' AS ls', 'ls.term_id', '=', 'users_my_courses.course_id' )
		->join( TBL_WP_TERM_TAXONOMY . ' AS tt', 'tt.term_id', '=', 'ls.term_id' )
		->where('tt.taxonomy', '=', 'series');
	if ( Auth::check() ) {
		$track_history = $track_history->where( 'user_id', Auth::User()->id );
	}
	$track_history = $track_history->get();

	if ( $track_history->count() > 0 ) {
		foreach( $track_history as $history ) {
			$check = DB::table( TBL_WP_TERMMETA )->where('term_id', '=', $history->term_id )->where('meta_key', '=', 'series_image_loc')->first();

			if ( $check ) {
				$history->series_image = $check->meta_value;
			}
		}
	}
	return $track_history;
}

function mycours( $course_id = ''  ) {
	$mycourse = FALSE;
	if ( ! empty( $course_id ) ) {
		if ( Auth::check() ) {
			$mycourse = App\MyCourses::where( 'course_id', '=', $course_id )
	->where( 'user_id', '=', Auth::User()->id )->get();
		} else {
			$mycourse = App\MyCourses::get();
		}

	} else {
		if ( Auth::check() ) {
			$mycourse = App\MyCourses::where( 'user_id', '=', Auth::User()->id )->get();
		}
	}
	return $mycourse;
}

function completed_courses( $course_id = '', $subject_id = '', $output_type = 'array' ) {
	if ( ! empty( $subject_id ) ) {
		$records = App\MyCourses::select('ls.*', 'users_my_courses.course_id')->join( 'lmsseries AS ls', 'ls.id', '=', 'users_my_courses.course_id' )
		->join ( 'subjects AS s', 's.id', '=', 'ls.subject_id')
		->join ( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id')
		->where( 'ls.status', '=', 'active' )
		->where( 'qc.category_status', '=', 'active' )
		->where( 'ls.parent_id', '=', '0' )
		->where( 'user_id', '=', Auth::User()->id )
		->where( 'users_my_courses.course_status', '=', 'completed' )
		->where( 'ls.subject_id', '=', $subject_id );
	} else {
		$records = App\MyCourses::select('ls.*', 'users_my_courses.course_id')
		->join( 'lmsseries AS ls', 'ls.id', '=', 'users_my_courses.course_id' )
		->join ( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id')
		->where( 'ls.status', '=', 'active' )
		->where( 'qc.category_status', '=', 'active' )
		->where( 'users_my_courses.course_status', '=', 'completed' )
		->where( 'ls.parent_id', '=', '0' );
		if ( Auth::check() ) {
		$records = $records->where( 'user_id', '=', Auth::User()->id );
		}

	}
	if ( ! empty( $course_id ) ) {
		$records = $records->where( 'course_id', '=', $course_id );
	}
	$records = $records->orderBy( 'users_my_courses.course_completed', 'asc' );

	// $records = $records->get();

	if ( $records->count() > 0 ) {

		if ( $output_type == 'array' ) {
			$completed_courses = array();
			foreach ( $records->get() as $record ) {
				array_push( $completed_courses, $record->course_id );
			}
			return array_unique( $completed_courses );
		} else {
			return $records->get();
		}

	} else {
		return array();
	}
}

function completed_serieses( $course_id = '', $subject_id = '', $output_type = 'array' )
{
	if ( ! empty( $subject_id ) ) {
		$subject = '';
		if ( $subject_id == PATHWAYSTART_ID ) {
			$subject = 'pathwaystart';
		}
		if ( $subject_id == PATHWAYFORWARD_ID ) {
			$subject = 'pathwayforward';
		}
		if ( $subject_id == PATHWAYFOREVER_ID ) {
			$subject = 'pathwayforever';
		}
		// $records = Corcel\Taxonomy::where('taxonomy', 'series')->hasMeta('pathway', $subject);
		$records = DB::table(TBL_WP_TERM_TAXONOMY)
		->join(TBL_LMS_USERS_MY_COURSES, TBL_LMS_USERS_MY_COURSES . '.course_id', '=', TBL_WP_TERM_TAXONOMY . '.term_id')
		->join(TBL_WP_TERMMETA, TBL_WP_TERMMETA . '.term_id', '=', TBL_WP_TERM_TAXONOMY . '.term_id')
		->where('taxonomy', 'series')
		->where('meta_key', 'series_pathway')
		
		->where('course_status', 'completed')
		->where('content_type', 'postsseries')
		
		->where('meta_value', $subject);
	} else {
		$records = DB::table(TBL_WP_TERM_TAXONOMY)
		->select(
			TBL_WP_TERMS . '.name AS title',
			TBL_WP_TERMS . '.slug',
			TBL_LMS_USERS_MY_COURSES . '.user_id',
			TBL_LMS_USERS_MY_COURSES . '.course_id',
			TBL_WP_TERMS . '.term_group AS series_image',
			TBL_WP_TERMS . '.term_id'
			)
		->join(TBL_WP_TERMS, TBL_WP_TERMS . '.term_id', '=', TBL_WP_TERM_TAXONOMY . '.term_id')
		->join(TBL_LMS_USERS_MY_COURSES, TBL_LMS_USERS_MY_COURSES . '.course_id', '=', TBL_WP_TERM_TAXONOMY . '.term_id')
		
		->where('course_status', 'completed')
		->where('content_type', 'postsseries')
		
		->where('taxonomy', 'series');
		if ( Auth::check() ) {
			$records = $records->where( 'user_id', '=', Auth::User()->id );
		}
	}
	if ( ! empty( $course_id ) ) {
		$records = $records->where( 'course_id', '=', $course_id );
	}
	$records = $records->orderBy( TBL_LMS_USERS_MY_COURSES . '.course_completed', 'asc' );

	// $records = $records->get();

	if ( $records->count() > 0 ) {
		if ( $output_type == 'array' ) {
			$completed_courses = array();
			foreach ( $records->get() as $record ) {
				array_push( $completed_courses, $record->course_id );
			}
			return array_unique( $completed_courses );
		} else {
			return $records->get();
		}
	} else {
		return array();
	}
}

function is_course_completed( $course_id )
{
	$completed_courses = completed_courses( $course_id );
	if ( in_array ( $course_id, $completed_courses ) ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function completed_percentage( $course_id ) {

	// $course_id = 9;
	$total_modules = $completed_modules = $total_lessons = $completed_lessons = $completed = 0;

	$lessons_statistics = lessons_pieces_new( $course_id );
    $total_lessons = $lessons_statistics[ 'total_lessons' ]; // This includes module lessons and direct lessons

	// Let us see how many direct pieces in the course
	$lessons = $lessons_statistics['lessons'];
	$comp = array();
	if ( $lessons->count() > 0 ) {
		foreach( $lessons as $lesson ) {
			if ( is_lesson_piece_completed( $lesson->id, $course_id, $lesson->module_id  ) ) {
				$completed_lessons++;
				$comp[] = $lesson->title;
			}
		}
	}

	if ( $completed_lessons > 0 ) {
		$completed = ($completed_lessons / $total_lessons ) * 100;
	}
	return $completed;
}

function subject_courses( $subject_id, $options = array() ) {
	$records = DB::table('lmscontents AS lc')
						->select(['ls.*'])
						->join( 'subjects AS s', 's.id', '=', 'lc.subject_id' )
						->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lc.id' )
						->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' )
						->where( 'ls.parent_id', '=', 0 )
						->where( 'lc.subject_id', '=', $subject_id )
						->groupBy('ld.lmsseries_id');
	if ( ! empty( $options ) && ! empty( $options['order_by'] ) ) {
		// $records = $records->inRandomOrder();
	} else {
		$records = $records->inRandomOrder();
	}
	$records = $records->get();

	return $records;
}

function subject_courses_new( $subject_id, $options = array() ) {
	$select = [ 'ls.*' ];
	if ( ! empty( $options ) && ! empty( $options['select'] ) ) {
		$select = [ $options['select'] ];
	}
	$records = DB::table('lmsseries AS ls')
				->select( $select )
				->join( 'subjects AS s', 's.id', '=', 'ls.subject_id' )
				->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
				->where( 'ls.parent_id', '=', 0 )
				->where( 'ls.status', '=', 'active' )
				->where( 'qc.category_status', '=', 'active' )
				->where( 'ls.subject_id', '=', $subject_id );
	if ( ! empty( $options ) && ! empty( $options['course_id'] ) && $options['course_id'] > 0 ) {
		$records = $records->where('ls.id', '=', $options['course_id']);
	}
	if ( ! empty( $options ) && ! empty( $options['order_by'] ) && $options['order_by'] != 'no' ) {
		$records = $records->orderBy( $options['order_by'], 'asc' );
	} else {
		$records = $records->inRandomOrder();
	}
	$records = $records->get();

	return $records;
}

function group_details( $case = '', $options = array() )
{
	$user_id = 0;
	if ( Auth::check() ) {
		$user_id = Auth::User()->id;
	}
	if ( ! empty( $options['user_id'] ) ) {
		$user_id = $options['user_id'];
	}
	// Auth::User()->id;
	switch( $case )
	{
		case 'mygroupscount':
			// $user_id = Auth::User()->id;
			$count = App\LMSGroups::where('user_id', '=', $user_id )->get()->count();
			return $count;
			break;
		case 'contentscount':
			if ( ! empty( $options['group_id'] ) ) {
				if ( ! empty( $user_id ) ) {
					$result = App\LMSGroups::select(['lmsgroups.id AS group_id', 'lmsgroups.title AS group_title', 'lmsgroups.slug AS group_slug', 'lmscontents.*'])
					->join( 'lmsgroups_contents AS lgc', 'lgc.group_id', '=', 'lmsgroups.id' )
					->join('lmscontents', 'lmscontents.id', '=', 'lgc.content_id')
					->where( 'lmsgroups.id', '=', $options['group_id'] )
					->where( 'lgc.content_type', '=', 'lesson' )
					->where( 'lmscontents.lesson_status', '=', 'active' );
					if ( ! empty( $options['pathway'] ) ) {
						$result = $result->where( 'lmscontents.subject_id', '=', $options['pathway'] );
					}
					$result = $result->get();
					return $result;
				}
			} else {

			}
			break;
		case 'coursescount':
			if ( ! empty( $options['group_id'] ) ) {
				if ( ! empty( $user_id ) ) {
					$result = App\LMSGroups::select(['lmsgroups.id AS group_id', 'lmsgroups.title AS group_title', 'lmsgroups.slug AS group_slug', 'lmsseries.*'])
					->join( 'lmsgroups_contents AS lgc', 'lgc.group_id', '=', 'lmsgroups.id' )
					->join('lmsseries', 'lmsseries.id', '=', 'lgc.content_id')
					->where( 'lmsgroups.id', '=', $options['group_id'] )
					->where( 'lgc.content_type', '=', 'course' )
					->where( 'lmsseries.status', '=', 'active' );
					if ( ! empty( $options['pathway'] ) ) {
						$result = $result->where( 'lmsseries.subject_id', '=', $options['pathway'] );
					}
					$result = $result->get();
					return $result;
				}
			} else {

			}
			break;
		case 'postscount':
			if ( ! empty( $options['group_id'] ) ) {
				if ( ! empty( $user_id ) ) {
					$result = App\LMSGroups::select(['lmsgroups.id AS group_id', 'lmsgroups.title AS group_title', 'lmsgroups.slug AS group_slug', TBL_WP_POSTS . '.*'])
					->join( 'lmsgroups_contents AS lgc', 'lgc.group_id', '=', 'lmsgroups.id' )
					->join(TBL_WP_POSTS, TBL_WP_POSTS.'.ID', '=', 'lgc.content_id')
					->where( 'lmsgroups.id', '=', $options['group_id'] )
					->where( 'lgc.content_type', '=', 'post' )
					->where( TBL_WP_POSTS . '.post_status', '=', 'publish' );
					if ( ! empty( $options['pathway'] ) ) {
						$pathway = $options['pathway'];
						if ( PATHWAYSTART_ID == $options['pathway'] ) {
							$pathway = 'pathwaystart';
						}
						if ( PATHWAYFORWARD_ID == $options['pathway'] ) {
							$pathway = 'pathwayforward';
						}
						if ( PATHWAYFOREVER_ID == $options['pathway'] ) {
							$pathway = 'pathwayforever';
						}						
						$result = $result->join(TBL_WP_POSTMETA, TBL_WP_POSTMETA.'.post_id', '=', TBL_WP_POSTS . '.ID');
						$result = $result->where( TBL_WP_POSTMETA . '.meta_key', 'pathway' );
						$result = $result->where( TBL_WP_POSTMETA . '.meta_value', $pathway );
					}
					$result = $result->get();
					// dd( $result->toSql() );
					return $result;
				}
			} else {

			}
			break;
		case 'invitedcount':
			if ( ! empty( $options['group_id'] ) ) {
				if ( ! empty( $user_id ) ) {
					return App\LMSGroups::select('lmsgroups.*', 'lgu.user_id AS participant_id')->join( 'lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id' )
					->where( 'lmsgroups.id', '=', $options['group_id'] )
					->where( 'lgu.status', '=', 'invited' )
					->where( 'lmsgroups.user_id', '=', $user_id );
				}
			}
			break;
		case 'requested':
			if ( ! empty( $options['group_id'] ) ) {
				if ( ! empty( $user_id ) ) {
					return App\LMSGroups::select('lmsgroups.*', 'lgu.user_id AS participant_id')->join( 'lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id' )
					->where( 'lmsgroups.id', '=', $options['group_id'] )
					->where( 'lgu.status', '=', 'requested' )
					->where( 'lmsgroups.user_id', '=', $user_id );
				}
			}
			break;
		case 'accepted':
			if ( ! empty( $options['group_id'] ) ) {
				if ( ! empty( $user_id ) ) {
					return App\LMSGroups::select('lmsgroups.*', 'lgu.user_id AS participant_id')->join( 'lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id' )
					->where( 'lmsgroups.id', '=', $options['group_id'] )
					->where( 'lgu.status', '=', 'accepted' )
					// ->where( 'lmsgroups.user_id', '=', $user_id )
					;
				}
			}
			break;
		case 'groupcomments':
			if ( ! empty( $options['group_id'] ) ) {
				return App\LmsComments::select( ['lmscontents_comments.comments_notes', 'users.image', 'users.name', 'lmscontents_comments.created_at'] )
				->join( 'users', 'users.id', '=', 'lmscontents_comments.user_id' )
				->join( 'lmsgroups', 'lmsgroups.id', '=', 'lmscontents_comments.group_id' )
				->where( 'lmsgroups.id', '=', $options['group_id'] )
				->where('type', '=', 'groupcomments')
				->orderBy( 'lmscontents_comments.created_at', 'desc' )
						;
			}
			break;
	}
}

function groups_count( $type = 'joined' ) {
	if ( $type == 'facilitated' ) {
		$row = App\LMSGroups::join( 'lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id' )
		->where( 'lmsgroups.user_id', '=', Auth::User()->id )
		->where('lgu.status', '=', 'accepted')
		->groupBy('lgu.group_id')->get()
		;		
	} elseif( $type == 'donations' ) {
		$row = DB::table( 'donations' )->where( 'user_id', '=', Auth::User()->id )->where( 'payment_status', '=', 'success' )->sum( 'cost' );
	} elseif( $type == 'joined' ) {
		$row = App\LMSGroups::join( 'lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id' )
		->where( 'lgu.user_id', '=', Auth::User()->id )
		->where( 'lmsgroups.user_id', '!=', Auth::User()->id )
		->where( 'lgu.status', '=', 'accepted' )
		;
	} else {
		$row = App\LMSGroups::join( 'lmsgroups_users AS lgu', 'lgu.user_id', '=', 'lmsgroups.user_id' )
		->where( 'lgu.user_id', '=', Auth::User()->id )
		->where( 'lmsgroups.user_id', '!=', Auth::User()->id );
	}
	return $row;
}

function completed_pieces( $lesson_id ) {

	$row = App\LmsContent::join( 'lmscontents_track AS lct', 'lct.content_id', '=', 'lmscontents.id' )
	->where( 'lct.user_id', '=', Auth::User()->id )
	->where( 'lmscontents.id', '=', $lesson_id );

	return $row;
}

function is_module_completed( $course_id )
{
	$lessons = App\LmsSeries::getAllModuleLessons( $course_id );
	// echo '<pre>'; print_r ( $lessons );
	if ( $lessons->count() > 0 ) {
		$total_contents = $lessons->count();
		$completed = 0;
		foreach( $lessons as $lesson ) {
			if ( is_lesson_completed( $lesson->id, $lesson->course_id, $lesson->module_id  ) ) {
				$completed++;
			}
		}
		if ( $total_contents == $completed ) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return TRUE;
	}
}

function categories_courses_completed_summary()
{
	$details = array();
	$details['categories'] = App\QuizCategory::all();
	// Courses
	$courses = App\LmsSeries::where( 'parent_id', '=', '0' )->where( 'status', '=', 'active' )->get();
	$courses_completed = completed_courses();

	/*
	if ( $courses->count() > 0 ) {
		foreach( $courses as $course ) {
			$modules = App\LmsSeries::where( 'parent_id', '=', $course->id )->where( 'status', '=', 'active' )->get();
			foreach( $modules as $module ) :
				if ( is_module_completed( $module->id ) ) {
					$courses_completed++;
				}
			endforeach;
		}
	}
	*/

	// My Courses.
	$details['courses'] = array(
		'list'       => $courses,
		'count'      => $courses->count(),
		'completed'  => count( $courses_completed ),
		'my_courses' => 0,
	);
	if ( mycours() ) {
		$details['courses']['my_courses'] = mycours()->count();
	}

	// Modules.
	$modules = App\LmsSeries::where( 'parent_id', '>', '0' )->where( 'status', '=', 'active' )->get();
	$modules_completed = 0;
	if ( $modules->count() > 0 ) {
		foreach( $modules as $module ) {
			if ( is_module_completed( $module->id ) ) {
				$modules_completed++;
			}
		}
	}
	$details['modules'] = array(
		'list'      => $modules,
		'count'     => $modules->count(),
		'completed' => $modules_completed,
	);

	// Lessons.
	$lessons = App\LmsSeries::getAllLessons();
	$lessons_completed = 0;
	$completed = array();
	if ( $lessons->count() > 0 ) {
		foreach( $lessons as $lesson ) {
			if ( is_completed( $lesson->id ) ) {
				if ( ! in_array( $lesson->id, $completed ) ) {
					$completed[] = $lesson->id;
					$lessons_completed++;
				}
			}
		}
	}
	$details['lessons'] = array(
		'list'      => $lessons,
		'count'     => $lessons->count(),
		'completed' => $lessons_completed,
	);
	return $details;
}

function lmsmode() {
	$lmsmode = DB::table('lmsmode')->first();
	if ( $lmsmode ) {
		$lmsmode = $lmsmode->lmsmode;
	} else {
		$lmsmode = 'default';
	}
	return $lmsmode;
}

function knowing_god_get_wp_user_id() {
	if ( Auth::check() ) {
		$user_id = Auth::User()->id;
		$wp_user_id = Auth::User()->wp_user_id;
		if ( ! empty( $wp_user_id ) ) {
			$user_id = $wp_user_id;
		} else {
			// If any case it dont find LMS user id in WP DB! Let us take it from LMS DB directly
			$user = DB::table( TBL_WP_USERS )->where('user_login', '=', Auth::User()->username )->first();
			if ( $user ) {
				$user_id = $user->ID;
			}

		}
	} else {
		$user_id = 0;
	}
	return $user_id;
}

function mark_as_completed_course( $course_id ) {
	if ( Auth::check() ) {
		/*
		$check = App\UsersCompletedCourses::where(array( 'user_id' => Auth::User()->id, 'course_id' => $course_id, 'content_type' => 'course' ))->get();
		if ( $check->count() == 0 ) {
			$record = new App\UsersCompletedCourses();
			$record->user_id = Auth::User()->id;
			$record->course_id = $course_id;
			$record->wp_user_id = knowing_god_get_wp_user_id();
			$record->save();
		}
		*/
		
		App\MyCourses::where('course_id', '=', $course_id)
		->where('user_id', '=', get_current_user_id())
		->update(
			array(
				'course_status' => 'completed',
				'course_completed' => date('Y-m-d H:i:s'),
			)
		);
	}
}

function recommended_courses() {
	if ( Auth::check() ) {
		$mycourses = array_pluck( App\MyCourses::where( 'user_id', '=', Auth::User()->id )->get(), 'course_id', 'course_id' );
		$mycourses_array = array();
		if ( ! empty( $mycourses ) ) {
			foreach( $mycourses as $key => $val ) {
				array_push($mycourses_array, $val );
			}
		}

		$serieses  = App\LmsSeries::select('lmsseries.*')->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
		->where('status','=','active')
		->where('qc.category_status','=','active')
			->whereNotIn( 'lmsseries.id', $mycourses_array )
			->where( 'parent_id', '=', 0 )
			->orderBy( 'title', 'asc');
	} else {
	$serieses   = App\LmsSeries::select('lmsseries.*')->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
	->where('status','=','active')
	->where('qc.category_status','=','active')
	->where( 'parent_id', '=', 0 )
	->orderBy( 'title', 'asc');
	}
	return $serieses;
}

function lessons_statistics( $course_id, $options = array() )
{
	$lessons_count = 0;
	$lessons = App\LmsSeries::getAllParentLessons( $course_id );
	$lessons_count = $lessons_count + $lessons->count();

	$modules = App\LmsSeries::getModules( $course_id );
	if ( $modules->count() > 0 ) {
		foreach( $modules as $module ) {
			$module_lessons = App\LmsSeries::getAllModuleParentLessons( $course_id, $module->id );
			// echo $module_lessons->count() .'@@';
			$lessons_count = $lessons_count + $module_lessons->count();
		}
	}
	return array( 'total_lessons' => $lessons_count );
}

function lessons_pieces_new( $course_id, $options = array() )
{
	$lessons_count = 0;
	$lessons = App\LmsSeries::getAllCourseLessons( $course_id );
	if ( $lessons->count() > 0 ) {
		$pieces = collect();
		foreach( $lessons as $lesson ) {
			$pieces_collection = App\LmsSeries::getPieces( $lesson->id );
			$pieces = $pieces->merge( $pieces_collection );
		}
		$lessons = $lessons->merge( $pieces );
	}
	// dd( $lessons );
	$lessons_count = $lessons_count + $lessons->count();

	$modules = App\LmsSeries::getModules( $course_id );

	if ( $modules->count() > 0 ) {
		foreach( $modules as $module ) {
			$module_lessons = App\LmsSeries::getAllModuleAllLessons( $course_id, $module->id );
			$lessons = $lessons->merge( $module_lessons );
			$pieces = 0;
			foreach( $module_lessons as $module_lesson ) {
				$pieces_collection = App\LmsSeries::getPieces( $module_lesson->id );
				$pieces_count = $pieces_collection->count();
				$lessons = $lessons->merge( $pieces_collection );
				$pieces += $pieces_count;
			}
			$lessons_count = $lessons_count + $module_lessons->count() + $pieces;
		}
	}

	return array( 'total_lessons' => $lessons_count, 'lessons' => $lessons );
}

function get_subject_by_id( $subject_id ) {
	$subject = '';
	if ( $subject_id == PATHWAYSTART_ID ) {
		$subject = 'pathwaystart';
	}
	if ( $subject_id == PATHWAYFORWARD_ID ) {
		$subject = 'pathwayforward';
	}
	if ( $subject_id == PATHWAYFOREVER_ID ) {
		$subject = 'pathwayforever';
	}
	return $subject;
}

function lessons_pieces_new_posts( $course_id, $options = array() )
{
	$lessons_count = 0;
	$subject = '';
	// if ( )
	$lessons = App\LmsSeries::getAllCourseLessons( $course_id );
	if ( $lessons->count() > 0 ) {
		$pieces = collect();
		foreach( $lessons as $lesson ) {
			$pieces_collection = App\LmsSeries::getPieces( $lesson->id );
			$pieces = $pieces->merge( $pieces_collection );
		}
		$lessons = $lessons->merge( $pieces );
	}
	// dd( $lessons );
	$lessons_count = $lessons_count + $lessons->count();

	$modules = App\LmsSeries::getModules( $course_id );

	if ( $modules->count() > 0 ) {
		foreach( $modules as $module ) {
			$module_lessons = App\LmsSeries::getAllModuleAllLessons( $course_id, $module->id );
			$lessons = $lessons->merge( $module_lessons );
			$pieces = 0;
			foreach( $module_lessons as $module_lesson ) {
				$pieces_collection = App\LmsSeries::getPieces( $module_lesson->id );
				$pieces_count = $pieces_collection->count();
				$lessons = $lessons->merge( $pieces_collection );
				$pieces += $pieces_count;
			}
			$lessons_count = $lessons_count + $module_lessons->count() + $pieces;
		}
	}

	return array( 'total_lessons' => $lessons_count, 'lessons' => $lessons );
}

function lessons_pieces( $course_id, $options = array() )
{
	$lessons_count = 0;
	$lessons = App\LmsSeries::getAllCourseLessons( $course_id );
	// dd( $lessons );
	$lessons_count = $lessons_count + $lessons->count();

	$modules = App\LmsSeries::getModules( $course_id );

	if ( $modules->count() > 0 ) {
		foreach( $modules as $module ) {
			$module_lessons = App\LmsSeries::getAllModuleAllLessons( $course_id, $module->id );
			$lessons = $lessons->merge( $module_lessons );
			$pieces = 0;
			foreach( $module_lessons as $module_lesson ) {
				$pieces_collection = App\LmsSeries::getPieces( $module_lesson->id );
				$pieces_count = $pieces_collection->count();
				$lessons = $lessons->merge( $pieces_collection );
				$pieces += $pieces_count;
			}
			$lessons_count = $lessons_count + $module_lessons->count() + $pieces;
		}
	}

	return array( 'total_lessons' => $lessons_count, 'lessons' => $lessons );
}

if( ! function_exists( 'knowing_god_get_excerpt' ) ) {
    /**
     * Function to get the excerpt to display
     *
     * @since 1.0
     * @param int $excerpt - excerpt / content.
     * @param int $count - number of words.
     * @return string
     */
    function knowing_god_get_excerpt( $excerpt, $count = 55 ) {
		$str = '';
		$words = str_word_count( $excerpt );
		if ( $words > $count ) {
			$words = str_word_count( $excerpt, 1 );
			$counter = 1;
			foreach( $words as $word ) {
				if ( $counter > $count ) {
					break;
				}
				$str .= $word . ' ';
				$counter++;
			}
			$excerpt = $str;
		}
		return $excerpt;
    }
}

function knowing_god_esc_attr( $text )
{
	return strip_tags( $text );
}

function is_wp_user_exists( $username )
{
	$wp_user_details = \Corcel\Model\User::where( 'user_login', '=', $username )->first();
	if ( $wp_user_details ) {
		return $wp_user_details->ID;
	} else {
		return 0;
	}
}

function insert_into_wp( $lms_user )
{
	$user_array = array(
		'user_login' => $lms_user->username,
		'user_pass' => $lms_user->password,
		'user_nicename' => $lms_user->name,
		'user_email' => $lms_user->email,
		'user_registered' => date('Y-m-d H:i:s'),
		'user_status' => 0,
		'display_name' => $lms_user->name
	);
	$wp_user_id = DB::table( WP_TABLE_PREFIX . 'users' )->insertGetId( $user_array );

	// Meta data
	$usermeta = array(
		'nickname' => $user_array['user_nicename'],
		'first_name' => $lms_user->first_name,
		'last_name' => $lms_user->last_name,
		'rich_editing' => 'true',
		'comment_shortcuts' => 'false',
		'admin_color' => 'fresh',
		'use_ssl' => '0',
		'pw_user_status' => 'approved',
		'lms_user_id' => $lms_user->id,
	);
	if ( $lms_user->role_id == OWNER_ROLE_ID || $lms_user->role_id == ADMIN_ROLE_ID ) {
		$usermeta['show_admin_bar_front'] = 'false';
		$usermeta['wp_capabilities'] = array( 'administrator' );
		$usermeta['show_admin_bar_front'] = '10';
	} else {
		$usermeta['show_admin_bar_front'] = 'false';
		$usermeta['wp_capabilities'] = array( 'subscriber' );
		$usermeta['show_admin_bar_front'] = '0';
	}

	foreach( $usermeta as $meta_key => $meta_value ) {
		if ( in_array( $meta_key, array( 'wp_capabilities' ) ) ) { // Dirty Fix!!!!
			$metarow = array(
				'user_id' => $wp_user_id,
				'meta_key' => $meta_key,
			);
			if ( $lms_user->role_id == OWNER_ROLE_ID || $lms_user->role_id == ADMIN_ROLE_ID ) {
				$metarow['meta_value'] = 'a:1:{s:13:"administrator";b:1;}';
			} else {
				$metarow['meta_value'] = 'a:1:{s:10:"subscriber";b:1;}';
			}
		} else {
			$metarow = array(
				'user_id' => $wp_user_id,
				'meta_key' => $meta_key,
				'meta_value' => $meta_value,
			);
		}
		DB::table( WP_TABLE_PREFIX . 'usermeta' )->insert( $metarow );
	}

	// Let us update 'wp_user_id' in LMS table so that we can use it later
	DB::table( 'users' )
		->where( 'id', '=', $lms_user->id )
		->update(
		array( 'wp_user_id' => $wp_user_id )
	);
	return $wp_user_id;
}

function update_wp_password( $wp_user_id, $hashed_password )
{
	DB::table( WP_TABLE_PREFIX . 'users' )
		->where( 'ID', '=', $wp_user_id )
		->update(
		array( 'user_pass' => $hashed_password )
	);
}

function compare_values( $a, $b )
{
	return ( strtotime( $a->post_date ) < strtotime( $b->post_date ) ) ? TRUE : FALSE;
}

function categories_widget()
{
	$categories = Corcel\Model\Taxonomy::where('taxonomy', 'category')->where('count', '>', 0)->get();
	if ( $categories->count() > 0 ) {
		?>
	<div id="latestseries-2" class="card mb-4 widget widget_latestseries"><h4 class="card-header widget-title"><?php echo getPhrase( 'Popular Categories' ); ?></h4>			<ul>
	<?php foreach( $categories as $category ) :	?>
	<li><a href="<?php echo HOST; ?>category/<?php echo $category->slug; ?>" class="series-<?php echo $category->term_id; ?>" title="<?php echo knowing_god_esc_attr( $category->name ); ?>"><?php echo $category->name; ?></a> <span class="badge badge-dark badge-pill"><?php echo $category->count; ?></span></li>
	<?php endforeach; ?>
	</ul>
	</div>
		<?php
	}
}

function series_widget()
{
	$categories = Corcel\Model\Taxonomy::where('taxonomy', 'series')->where('count', '>', 0)->get();
	if ( $categories->count() > 0 ) {
		?>
	<div id="latestseries-2" class="card mb-4 widget widget_latestseries"><h4 class="card-header widget-title"><?php echo getPhrase( 'Popular Series' ); ?></h4>			<ul>
	<?php foreach( $categories as $category ) : ?>
	<li><a href="<?php echo HOST; ?>series/<?php echo $category->slug; ?>" class="series-<?php echo $category->term_id; ?>" title="<?php echo knowing_god_esc_attr( $category->name ); ?>"><?php echo $category->name; ?></a> <span class="badge badge-dark badge-pill"><?php echo $category->count; ?></span></li>
	<?php endforeach; ?>
	</ul>
	</div>
		<?php
	}
}

function tags_cloud_widget()
{
	$categories = Corcel\Model\Taxonomy::where('taxonomy', 'post_tag')->where('count', '>', 0)->get();
	if ( $categories->count() > 0 ) {
		?>
	<div id="latestseries-2" class="card mb-4 widget widget_latestseries"><h4 class="card-header widget-title"><?php echo getPhrase( 'Popular Series' ); ?></h4>			<ul>
	<?php foreach( $categories as $category ) : ?>
	<li><a href="<?php echo HOST; ?>series/<?php echo $category->slug; ?>" class="series-<?php echo $category->term_id; ?>" title="<?php echo knowing_god_esc_attr( $category->name ); ?>"><?php echo $category->name; ?></a> <span class="badge badge-dark badge-pill"><?php echo $category->count; ?></span></li>
	<?php endforeach; ?>
	</ul>
	</div>
		<?php
	}
}

function default_site_admin()
{
	return getSetting('site_admin','site_settings');
}

function course_lessons_summary( $action, $options = array() )
{
	switch( $action ) {
		case 'lessons_pieces':
			if ( ! empty( $options['course_id'] ) ) {
				$course_id = $options['course_id'];
				$course_details = App\LmsSeries::where( 'id', '=', $course_id )->first();
				$lessons = App\LmsSeries::getAllCourseLessons( $course_id );
				$lessons_pieces = $lessons;

				if ( $lessons->count() > 0 ) {
					foreach( $lessons as $lesson ) {
						// Let us find if any lesson have pieces.
						$pieces_collection = App\LmsSeries::getPieces( $lesson->id, $course_id, $lesson->module_id );
						$lessons_pieces = $lessons_pieces->merge( $pieces_collection );
					}
				}
				$modules = App\LmsSeries::getModules( $course_id );
				if ( $modules->count() > 0 ) {
					foreach( $modules as $module ) {
						// Let us find this module lessons.
						$module_lessons = App\LmsSeries::getAllModuleAllLessons( $course_id, $module->id );
						$lessons = $lessons->merge( $module_lessons );
						$lessons_pieces = $lessons_pieces->merge( $module_lessons );
						$pieces = 0;

						$module_lessons_count = $module_lessons->count();
						$module_lessons_completed = 0;
						foreach( $module_lessons as $module_lesson ) {
							// Let us find if this lesson has any pieces.
							$pieces_collection = App\LmsSeries::getPieces( $module_lesson->id, $course_id, $module->id );
							$module_lessons_count += $pieces_collection->count();
							$lessons_pieces = $lessons_pieces->merge( $pieces_collection );

							if ( Auth::check() ) {
								if ( is_lesson_piece_completed( $module_lesson->id, $module_lesson->course_id, $module_lesson->module_id ) ) {
									$module_lessons_completed++;
								}

								foreach( $pieces_collection as $piece ) {
									if ( is_lesson_piece_completed( $piece->id, $piece->course_id, $piece->module_id ) ) {
										$module_lessons_completed++;
									}
								}
							}

						}
					}
				}
				$lessons_pieces_completed_count = $completed_modules = $lessons_completed_count = 0;
				$lessons_pieces_completed = $lessons_completed = array();

				if ( Auth::check() ) {
					if ( $lessons_pieces->count() > 0 ) {
						foreach( $lessons_pieces as $piece ) {
							if ( is_lesson_piece_completed( $piece->id, $piece->course_id, $piece->module_id ) ) {
								$lessons_pieces_completed_count++;
								$lessons_pieces_completed[] = $piece;
							}
						}
					}

					if ( $lessons->count() > 0 ) {
						foreach( $lessons as $lesson ) {
							if ( is_lesson_completed( $lesson->id, $lesson->course_id, $lesson->module_id ) ) {
								$lessons_completed_count++;
								$lessons_completed[] = $lesson;
							}
						}
					}
				}
				return array(
					'course' => $course_details,
					'modules' => $modules,
					'modules_count' => $modules->count(),

					'lessons_pieces' => $lessons_pieces,
					'lessons_pieces_count' => $lessons_pieces->count(),
					'lessons_pieces_completed_count' => $lessons_pieces_completed_count,
					'lessons_pieces_completed' => $lessons_pieces_completed,

					'lessons' => $lessons,
					'lessons_count' => $lessons->count(),
					'lessons_completed_count' => $lessons_completed_count,
					'lessons_completed' => $lessons_completed,
					'completed_modules' => $completed_modules,
				);
			}
			break;
	}
}

function is_course_completed_new( $course_id )
{
	// Course Modules
	$modules = App\LmsSeries::select(['lmsseries.*'])->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
		->where('lmsseries.status', '=', 'active')
		->where('qc.category_status', '=', 'active')
		->where('lmsseries.parent_id', '=', $course_id)
		->get();
	// Course Lessons
	$lessons = DB::table('lmsseries_data')
			->select(['lmscontents.*'])
			->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
			->where('lmsseries_id', '=', $course_id )
			->get();
	$modules_completed = $lessons_completed = 0;
	if ( $modules->count() > 0 ) {
		foreach( $modules as $module ) {
			if ( is_module_completed( $module->id ) ) {
				$modules_completed++;
			}
		}
	}

	if ( $lessons->count() > 0 ) {
		foreach( $lessons as $lesson ) {
			if ( is_lesson_completed( $lesson->id, $course_id  ) ) {
				$lessons_completed++;
			}
		}
	}
	if ( $modules_completed == $modules->count() && $lessons_completed == $lessons->count() ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function get_special_courses( $pathway = '' ) {
	if ( empty( $pathway ) ) {
	return App\LmsSeries::select(['lmsseries.*', 'qc.category', 'qc.slug AS cat_slug'])
		->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
		->join('subjects AS s', 's.id', '=', 'lmsseries.subject_id')
		->where('lmsseries.status','=','active' )
		->where('qc.category_status','=','active' )
		->where('lmsseries.course_type', '=', 'special')
		->orderBy('lmsseries.display_order', 'asc')
		->get();
	} else {
	return App\LmsSeries::select(['lmsseries.*', 'qc.category', 'qc.slug AS cat_slug'])
		->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
		->join('subjects AS s', 's.id', '=', 'lmsseries.subject_id')
		->where('lmsseries.status','=','active' )
		->where('qc.category_status','=','active' )
		->where('lmsseries.course_type', '=', 'special')
		->where('s.subject_title', '=', $pathway )
		->orderBy('lmsseries.display_order', 'asc')
		->get();
	}
}

function get_regular_courses() {
	return App\LmsSeries::select(['lmsseries.*', 'qc.category', 'qc.slug AS cat_slug'])
		->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
		->join('subjects AS s', 's.id', '=', 'lmsseries.subject_id')
		->where('lmsseries.status','=','active' )
		->where('qc.category_status','=','active' )
		->where('lmsseries.course_type', '=', 'regular')
		->orderBy('s.id', 'asc')
		->orderBy('lmsseries.display_order', 'asc')
		->get();
}

function suggest_next_course()
{

	$user_id = 0;
	if ( Auth::check() ) {
		$user_id = Auth::User()->id;
	}

	$course_new = '';
	$special_courses = get_special_courses();
	$completed_courses = '';
	// dd( $special_courses );
	if ( $special_courses->count() > 0 ) {
		foreach( $special_courses as $course ) {
			if ( ! is_course_completed_new( $course->id ) ) {
				$course_new = $course;
				return $course_new;
			} else {
				$completed_courses[] = $course;
			}
		}
	}
	// dd( $completed_courses );

	if ( empty( $course_new ) ) {
		$regular_courses = get_regular_courses();
		if ( $regular_courses->count() > 0 ) {
			foreach( $regular_courses as $course ) {
				if ( ! is_course_completed_new( $course->id ) ) {
					$course_new = $course;
					return $course_new;
				}
			}
		}
	}
	return $course_new;

	/*
	$user_courses = App\MyCourses::select(['users_my_courses.user_id', 'c.*'])
		->join('lmsseries AS c', 'c.id', '=', 'users_my_courses.course_id')
		->join('quizcategories AS qc', 'qc.id', '=', 'c.lms_category_id')
		->where('users_my_courses.user_id', '=', $user_id)
		->where('c.status', '=', 'active')
		->where('qc.category_status', '=', 'active')
		->get()
		;

	$course_new = '';
	if ( $user_courses->count() > 0 ) {
		foreach( $user_courses as $course ) {

			if ( ! is_course_completed_new( $course->id ) ) {
				$course_new = $course;
				return $course_new;
			}
		}

		if ( empty( $course_new ) ) {
			$special_courses = get_special_courses();
			if ( $special_courses->count() > 0 ) {
				foreach( $special_courses as $course ) {
					if ( ! is_course_completed_new( $course ) ) {
						$course_new = $course;
						return $course_new;
					}
				}
			}

			if ( empty( $course_new ) ) {
				$regular_courses = get_regular_courses();
				if ( $regular_courses->count() > 0 ) {
					foreach( $regular_courses as $course ) {
						if ( ! is_course_completed_new( $course ) ) {
							$course_new = $course;
							return $course_new;
						}
					}
				}
			}
		}
	} else {
		if ( empty( $course_new ) ) {
			$special_courses = get_special_courses();
			if ( $special_courses->count() > 0 ) {
				foreach( $special_courses as $course ) {
					if ( ! is_course_completed_new( $course ) ) {
						$course_new = $course;
						return $course_new;
					}
				}
			}

			if ( empty( $course_new ) ) {
				$regular_courses = get_regular_courses();
				if ( $regular_courses->count() > 0 ) {
					foreach( $regular_courses as $course ) {
						if ( ! is_course_completed_new( $course ) ) {
							$course_new = $course;
							return $course_new;
						}
					}
				}
			}
		}
	}
	return $course_new;
	*/
}

function course_groups( $course_id, $user_id = '' )
{
	if ( empty( $user_id ) && Auth::check() ) {
		$user_id = Auth::User()->id;
	}
	return DB::table('lmsgroups AS lg')
		->select(['lg.*'])
		->join('lmsgroups_contents AS lgc', 'lgc.group_id', '=', 'lg.id')
		->join('lmsseries AS ls', 'ls.id', '=', 'lgc.content_id')
		->where('lgc.content_type', '=', 'course')
		->where('lg.group_status', '=', 'active')
		->where('ls.status', '=', 'active')
		->where('lgc.content_id', '=', $course_id)
		->get();
}

function post_groups( $post_id, $user_id = '' )
{
	if ( empty( $user_id ) && Auth::check() ) {
		$user_id = Auth::User()->id;
	}
	return DB::table('lmsgroups AS lg')
		->select(['lg.*'])
		->join('lmsgroups_contents AS lgc', 'lgc.group_id', '=', 'lg.id')
		->join(TBL_WP_POSTS, TBL_WP_POSTS . '.ID', '=', 'lgc.content_id')
		->where('lgc.content_type', '=', 'post')
		->where('lg.group_status', '=', 'active')
		->where(TBL_WP_POSTS . '.post_status', '=', 'publish')
		->where('lgc.content_id', '=', $post_id)
		->get();
}

// completed at least 2 pieces of each part of the 7 c's
function at_least_two_completed( $course_id )
{
	$modules = App\LmsSeries::getModules( $course_id );
	$at_least_two_completed = TRUE;
	foreach( $modules as $module ) {
		$pieces = collect();
		$module_lessons = App\LmsSeries::getAllModuleAllLessons( $course_id, $module->id );
		$pieces = $pieces->merge( $module_lessons );
		foreach( $module_lessons as $module_lesson ) {
			$pieces_collection = App\LmsSeries::getPieces( $module_lesson->id );
			$pieces = $pieces->merge( $pieces_collection );
		}
		
		if ( $pieces->count() > 0 ) {
			$pieces_completed = 0;
			foreach( $pieces as $piece ) {
				if ( is_lesson_piece_completed_new( $piece->id ) ) {
					$pieces_completed++;					
				}
			}
			if ( $pieces_completed < 2 ) {
				$at_least_two_completed = FALSE;
			}
		}
	}
	
	if ( $modules->count() > 0 ) {
		return $at_least_two_completed;
	} else {
		return FALSE;
	}
}

function update_user_role()
{
	/**
	 * 1. Subscriber: Basic Role
	 * 2. Facilitator(is a leader of a number of subscribers):
	 * 		- They have completed all of PathwayStart
	 *		- They have completed "A Way Forward" (under PathwayForward)
	 *		- They have completed "The 7C's - beginner"
	 *		- They have completed Serve Others
	 * 		The user who has completed all these items
				- will receive an email indicating that  they are now a facilitator and
				- can start a group using the PathwayStart material.
				- An administrator will be notified as well
	 * 3. Coach ( is a leader of a number of facilitators )
			- Criteria for a coach is that they have led 3 groups successfully through all pathway-start and forward material.
	*/
	$completed_pathway_start = $completed_awayforward = $completed_7cs = $completed_servingothers = FALSE;
	if ( Auth::check() ) {
		// They have completed all of PathwayStart
		$pathway_contents = pathway_contents( PATHWAY_START_ID ); // It includes all pieces from LMS and posts from CMS.
		$completed_contents = completed_pieces_new( PATHWAY_START_ID ); // It includes completed pieces from LMS and posts from CMS
		// dd($pathway_contents);
		
		//die();
		/*
		foreach( $completed_contents as $content ) {
			echo ( $content->content_id ) ? $content->content_id : $content->content_id;
			echo '<br>';
		}
		*/
		
		//dd( array_pluck( $completed_contents, 'id', 'title' ) );
		if ( get_current_user_id() == 124) {
		/*
		echo $pathway_contents->count() . '##' . count( $completed_contents );
		$pathway_contents_array = array_pluck ($pathway_contents, 'title', 'id' );
		$completed_contents_array =  array_pluck ($completed_contents, 'title', 'content_id' );
		 dd( $pathway_contents->groupBy('id') );
		 */
		}

		$completed_pathway_start =  ( $pathway_contents->count() <= count( $completed_contents ) );

		// They have completed "A Way Forward" (under PathwayForward)
		$awayforward_contents = pathway_contents( PATHWAYFORWARD_ID, PATHWAYFORWARD_AWAYFORWARD_ID );
		$awayforward_completed_contents = completed_pieces_new( PATHWAYFORWARD_ID, 0,   PATHWAYFORWARD_AWAYFORWARD_ID );
		$completed_awayforward =  ( $awayforward_contents->count() <= count( $awayforward_completed_contents ) );
		//echo $awayforward_contents->count() . '##' . count( $awayforward_completed_contents );
		//dd($completed_awayforward);

		// They have completed "The 7C's - beginner"
		$sevencs_contents = pathway_contents( PATHWAYFORWARD_ID, PATHWAYFORWARD_7CS_BEGINNER_ID );
		$sevencs_completed_contents = completed_pieces_new( PATHWAYFORWARD_ID, 0,   PATHWAYFORWARD_7CS_BEGINNER_ID );
		$completed_7cs = ( $sevencs_contents->count() <= count( $sevencs_completed_contents ) );
		// echo $sevencs_contents->count() . '##' . count( $sevencs_completed_contents );

		// They have completed Serve Others
		$servingothers_contents = pathway_contents( PATHWAYFORWARD_ID, PATHWAYFORWARD_SERVEINGOTHERS_ID );
		$servingothers_completed_contents = completed_pieces_new( PATHWAYFORWARD_ID, 0,   PATHWAYFORWARD_SERVEINGOTHERS_ID );
		$completed_servingothers = ( $servingothers_contents->count() <= count( $servingothers_completed_contents ) );
		// echo $servingothers_contents->count() . '##' . count( $servingothers_completed_contents );
		$user = Auth::User();
		$current_user_role = $user->current_user_role;
		$default_site_admin = default_site_admin();
		$admin_email = '';
		if ( ! empty( $default_site_admin ) ) {
			$admin = App\User::where('id', '=', $default_site_admin)->first();
			if ( $admin ) {
				$admin_email = $admin->email;
			}
		}
		// var_dump( $completed_pathway_start );die();
		// $completed_pathway_start = $completed_awayforward = $completed_7cs = $completed_servingothers = TRUE;
		if ( get_current_user_id() == 124) {
		/*
		var_dump( $completed_pathway_start );
		var_dump( $completed_awayforward );
		var_dump( $completed_7cs );
		var_dump( $completed_servingothers );
		var_dump( $current_user_role );
		die();
		*/
		}
		if ( TRUE === $completed_pathway_start
			&& TRUE === $completed_awayforward
			&& TRUE === $completed_7cs
			&& TRUE === $completed_servingothers
			&& 'subscriber' === $current_user_role
		) {
			DB::table('users')->where('id', '=', $user->id)->update( array( 'current_user_role' => 'facilitator' ) );

			// will receive an email indicating that  they are now a facilitator
			sendEmail('facilitator-email', array('user_name'=>$user->username, 'username'=>$user->name, 'to_email' => $user->email));

			// An administrator will be notified as well
			if ( ! empty( $admin_email ) ) {
				sendEmail('facilitator-email-admin-notice', array('user_name'=>$user->username, 'username'=>$user->name, 'to_email' => $admin_email));
			}
		}

		/**
		 * Need to chack for Coach.
		 *
		 * Criteria for a coach is that they have led 3 groups successfully through all pathway-start and forward material.
		 */
		 // User 'admin' OR 'owner' promoted as coach automatically
		 if ( 'coach' != $current_user_role && in_array( getRole(), array( 'admin', 'owner' ) ) ) {
			 // DB::table('users')->where('id', '=', $user->id)->update( array( 'current_user_role' => 'coach' ) );
		 }
		
		// successfully led a group through the pathwaystart material
		$user_groups = DB::table('lmsgroups')->where('user_id', '=', get_current_user_id())->where('group_status', '=', 'active')->get();
		$users_completed_pathwaystart = 0;
		$successfully_led_groups = array();
		if ( $user_groups->count() > 0 ) {
			foreach( $user_groups as $user_group ) {
				$group_members = DB::table('lmsgroups_users')->where('group_id', '=', $user_group->id )->where('status', '=', 'accepted')->get();
				if ( $group_members->count() > 0 ) {
					foreach( $group_members as $group_member ) {
						if ( is_user_completed_group( $user_group->id, 
							array(
								'user_id' => $group_member->user_id,
								'pathway' => PATHWAYSTART_ID,
								)  ) ) {
							$users_completed_pathwaystart++;
						}
					}
					// Successfully led a group means: At least 2 users have to complete pathway start material.
					if ( ! in_array( $user_group->id, $successfully_led_groups ) && ( $users_completed_pathwaystart >= 2 ) ) {
						$successfully_led_groups[] = $user_group->id;
					}
				}
			}
		}
		
		// successfully led a group through the pathwayforward material
		$users_completed_pathwayforward = 0;
		if ( $user_groups->count() > 0 ) {
			foreach( $user_groups as $user_group ) {
				$group_members = DB::table('lmsgroups_users')->where('group_id', '=', $user_group->id )->where('status', '=', 'accepted')->get();
				if ( $group_members->count() > 0 ) {
					foreach( $group_members as $group_member ) {
						if ( is_user_completed_group( $user_group->id, 
							array( 
								'user_id' => $group_member->user_id,
								'pathway' => PATHWAYFORWARD_ID,
								)  ) ) {
							$users_completed_pathwayforward++;
						}
					}
				}
			}
		}
		// Criteria for a coach is that they have led 3 groups successfully through all pathway-start and forward material.		
		if ( 'facilitator' === $current_user_role 
			// && $users_completed_pathwaystart >= 3 
			&& count( $successfully_led_groups ) >= 3
			// && $users_completed_pathwayforward >= 3 
		) {
			DB::table('users')->where('id', '=', $user->id)->update( array( 'current_user_role' => 'coach' ) );
		}
		 

		/**
		 * User levels Updation
		 *
		 * 1. Subscriber
				- Basic
			2. Servant Learner
				- completion of all pathwaystart material from the MAIN NAVIGATION
				(This description from Greg Reply on asana: after having become a facilitator and after having been assigned a coach, will then become a ServantLearner - http://prntscr.com/i3147x) - Let us know which one we need to follow. )
			3. Servant
				- successfully led a group through the pathwaystart material ( How do you define successfully led a group? (Sorry to ask this, we discussed in the call but not getting) )
				- Completed the pathwayForward material from the MAIN NAVIGATION
				- acquired a coach from the coash list - (What is criteria to acquire a coach? (Sorry to ask this, we discussed in the call but not getting) )
			4. Servant Leader
				- successfully led a group through the pathwaystart material ( How do you define successfully led a group?)
				- Completed the pathwayForward material from the MAIN NAVIGATION
				- acquired a coach from the coash list - (What is criteria to acquire a coach? (Sorry to ask this, we discussed in the call but not getting) )
				- the person has become a coach (Define a criteria to become a coach - (Sorry to ask this, we discussed in the call but not getting))
				- facilitated 10 groups
				- completed at least 2 pieces of each part of the 7 c's
					  (What is 7 c's?
					  Where to display them on site?
					  )
		 */
		$pathwaystart_material = get_special_courses( PATHWAY_START_TITLE );
		$pathwaystart_completed_courses = 0;
		if ( $pathwaystart_material->count() > 0 ) {
			foreach( $pathwaystart_material as $course ) {
				if ( is_course_completed_new( $course->id ) ) {
					$pathwaystart_completed_courses++;
				}
			}
		}
		if ( get_current_user_id() == 123 ) {
			
		}
		
		// Checking for "Servant Learner"
		/**
		 * - completion of all pathwaystart material from the MAIN NAVIGATION
		 * - having become a facilitator
		 * - having been assigned a coach
		 */
		if (
			'subscriber' === get_current_user_level()
			&& $pathwaystart_material->count() <= $pathwaystart_completed_courses
			&& 'facilitator' === get_current_user_special_role()
			&& get_current_user_coach_id() > 0
			) {
			DB::table('users')->where('id', '=', $user->id)->update( array( 'current_user_level' => 'Servant Learner' ) );
		}
		
		// Checking for "Servant"
		// successfully led a group through the pathwaystart material
		$user_groups = DB::table('lmsgroups')->where('user_id', '=', get_current_user_id())->where('group_status', '=', 'active')->get();
		$users_completed_pathwaystart = 0;
		if ( $user_groups->count() > 0 ) {
			foreach( $user_groups as $user_group ) {
				$group_members = DB::table('lmsgroups_users')->where('group_id', '=', $user_group->id )->where('status', '=', 'accepted')->get();
				if ( $group_members->count() > 0 ) {
					foreach( $group_members as $group_member ) {
						if ( is_user_completed_group( $user_group->id, 
							array( 
								'user_id' => $group_member->user_id,
								'pathway' => PATHWAYSTART_ID,
								)  ) ) {
							$users_completed_pathwaystart++;
						}
					}
				}
			}
		}
		
		// Completed the pathwayForward material from the MAIN NAVIGATION
		$pathwayforward_material = get_special_courses( PATHWAY_FORWARD_TITLE );
		$pathwayforward_completed_courses = 0;
		if ( $pathwayforward_material->count() > 0 ) {
			foreach( $pathwayforward_material as $course ) {
				if ( is_course_completed_new( $course->id ) ) {
					$pathwayforward_completed_courses++;
				}
			}
		}
		
		/**
		 * - successfully led a group through the pathwaystart material (Successfully leading a group means that at least 2 people finished the material assigned to the group.)
		 * - Completed the pathwayForward material from the MAIN NAVIGATION
		 * - acquired a coach from the coash list
		 */
		if (
			'Servant Learner' === get_current_user_level()
			&& $users_completed_pathwaystart >= 2
			&& $pathwayforward_material->count() == $pathwayforward_completed_courses
			&& get_current_user_coach_id() > 0
			) {
			DB::table('users')->where('id', '=', $user->id)->update( array( 'current_user_level' => 'Servant' ) );
		}
		
		
		// Completed the pathwayForward material from the MAIN NAVIGATION
		$pathwayforward_material = get_special_courses( PATHWAY_FORWARD_TITLE );
		$pathwayforward_completed_courses = 0;
		if ( $pathwayforward_material->count() > 0 ) {
			foreach( $pathwayforward_material as $course ) {
				if ( is_course_completed_new( $course->id ) ) {
					$pathwayforward_completed_courses++;
				}
			}
		}
		if ( 'Servant Learner' === get_current_user_level() 
			&& $users_completed_pathwaystart >= 1
			&& $pathwayforward_material->count() <= $pathwayforward_completed_courses
			&& get_current_user_coach_id() > 0
		) {
			DB::table('users')->where('id', '=', $user->id)->update( array( 'current_user_level' => 'Servant' ) );
		}
		
		// Checking for "Servant Leader"
		/**
		 * Servant Leader
			- successfully led a group through the pathwaystart material ( How do you define successfully led a group?)
			- Completed the pathwayForward material from the MAIN NAVIGATION
			- acquired a coach from the coash list - (What is criteria to acquire a coach? (Sorry to ask this, we discussed in the call but not getting) )
			- the person has become a coach (Define a criteria to become a coach - (Sorry to ask this, we discussed in the call but not getting))
			(Criteria for a coach is that they have led 3 groups successfully through all pathway-start and forward material. This means that, obviously, they have met the facilitator criteria.)
			- facilitated 10 groups
			- completed at least 2 pieces of each part of the 7 c's
				  (What is 7 c's?
				  Where to display them on site?
				  )
		 */
		
		$facilitated_groups = groups_count( 'facilitated' )->count(); // Need some clarity from client.
		
		if ( 'Servant' === get_current_user_level() 
			// && $pathwayforward_material->count() == $pathwayforward_completed_courses // Completed the pathwayForward material from the MAIN NAVIGATION
			// && get_current_user_coach_id() > 0 // acquired a coach from the coash list
			&& 'coach' === get_current_user_special_role() // the person has become a coach
			&& TRUE === at_least_two_completed( PATHWAYFORWARD_7CS_ID ) // completed at least 2 pieces of each part of the 7 c's
			&& $facilitated_groups >= 10
		) {
			DB::table('users')->where('id', '=', $user->id)->update( array( 'current_user_level' => 'Servant Leader' ) );
		}	 
	}
}

function is_user_completed_group( $group_id, $options = array() )
{
	$user_id = 0;
	if ( empty( $options['user_id'] ) ) {
		$user_id = Auth::User()->id;
	}
	
	$get_options = array( 
		'group_id' => $group_id, 
		'user_id' => $user_id,
	);
	if ( ! empty( $options['pathway'] ) ) {
		$get_options['pathway'] = $options['pathway'];
	}
	$lessons = group_details( 'contentscount', $get_options );
	$courses = group_details( 'coursescount', $get_options );
	$posts = group_details( 'postscount', $get_options );
		
	$pieces = collect();
	$pieces = $pieces->merge( $lessons );
	
	if ( $lessons->count() > 0 ) {
		foreach( $lessons as $lesson ) {
			$pieces_collection = App\LmsSeries::getPieces( $lesson->id );
			$pieces = $pieces->merge( $pieces_collection );
		}
	}
	
	if ( $courses->count() > 0 ) {
		foreach( $courses as $course ) {
			// Direct Course Lessons
			$courselessons = App\LmsSeries::getAllCourseLessons( $course->id );
			$pieces = $pieces->merge( $courselessons );

			if ( $courselessons->count() > 0 ) {
				foreach( $courselessons as $lesson ) {
					$pieces_collection = App\LmsSeries::getPieces( $lesson->id );
					$pieces = $pieces->merge( $pieces_collection );
				}
			}
			
			// Modules in courese
			$modules = App\LmsSeries::getModules( $course->id );
			foreach( $modules as $module ) {
				$module_lessons = App\LmsSeries::getAllModuleAllLessons( $course->id, $module->id );
				$pieces = $pieces->merge( $module_lessons );
				foreach( $module_lessons as $module_lesson ) {
					$pieces_collection = App\LmsSeries::getPieces( $module_lesson->id );
					$pieces = $pieces->merge( $pieces_collection );
				}
			}
		}
	}
	
	$pieces_total = $pieces->count() + $posts->count();
	$pieces_completed = 0;
	foreach( $pieces as $piece ) {
		if ( is_lesson_piece_completed_new( $piece->id, $group_id ) ) {
			$pieces_completed++;
		}
	}
	
	foreach( $posts as $post ) {
		if ( is_lesson_piece_completed_new_post( $post->ID, $group_id ) ) {
			$pieces_completed++;
		}
	}
	
	return ( $pieces_total === $pieces_completed );
}

function is_coach()
{
	if ( Auth::check() ) {
		return ('coach' === Auth::User()->current_user_role);
	} else {
		return FALSE;
	}
}

function is_group_owner( $group_id )
{
	if ( Auth::check() ) {
		$check = App\LMSGroups::where('user_id', '=', get_current_user_id())->where('id', '=', $group_id);
		return ( $check->count() > 0 );
	} else {
		return FALSE;
	}
}

function is_group_owner_slug( $group_slug )
{
	if ( Auth::check() ) {
		$check = App\LMSGroups::where('user_id', '=', get_current_user_id())->where('slug', '=', $group_slug);
		return ( $check->count() > 0 );
	} else {
		return FALSE;
	}
}

function is_group_members( $group_id )
{
	if ( Auth::check() ) {
		if ( is_group_owner( $group_id ) ) {
			return TRUE;
		}
		$check = App\LMSGroups::join('lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id')
		->where('lgu.user_id', '=', get_current_user_id())
		->where('lmsgroups.id', '=', $group_id)
		->where('lgu.status', '=', 'accepted');
		return ( $check->count() > 0 );
	} else {
		return FALSE;
	}
}

function is_group_members_slug( $group_slug )
{
	if ( Auth::check() ) {
		if ( is_group_owner_slug( $group_slug ) ) {
			return TRUE;
		}
		$check = App\LMSGroups::join('lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id')
		->where('lgu.user_id', '=', get_current_user_id())
		->where('lmsgroups.slug', '=', $group_slug)
		->where('lgu.status', '=', 'accepted');
		return ( $check->count() > 0 );
	} else {
		return FALSE;
	}
}

function group_information_messages( $group_id )
{
	if ( ! is_group_members( $group_id ) ) :	
			$group_member_status = group_member_status( $group_id );
			
			$group_details = App\LMSGroups::select(['lmsgroups.*', 'u.name AS owner_name'])->join('users AS u', 'u.id', '=', 'lmsgroups.user_id')
		->where('u.status', '=', 'activated')
		->where('lmsgroups.group_status', '=', 'active')
		->where('lmsgroups.id', '=', $group_id)
		->first();
		$group_slug = $group_details->slug;
			
			$message = sprintf('You are not a member of this group. Click <a href="%s">here</a> to send the request.', URL_MANAGE_GROUP_REQUESTS_DIRECT . $group_slug . '/' . get_current_user_id() . '/requested');
			if ( $group_member_status ) {
				if ( 'invited' === $group_member_status->group_member_status ) {
					$message = sprintf('%s has invited to join this group. Click <a href="%s">here</a> to join the group', $group_details->owner_name, URL_MANAGE_GROUP_REQUESTS_DIRECT . $group_slug . '/' . get_current_user_id() . '/requested');
				}
				if ( 'requested' === $group_member_status->group_member_status ) {
					$message = sprintf('You have requested <b>%s</b> to join this group. Please wait to till he accept your request. Click <a href="%s">here</a> to withdraw your request.', $group_details->owner_name, URL_MANAGE_GROUP_REQUESTS_DIRECT . $group_slug . '/' . get_current_user_id() . '/withdraw');
				}
				if ( 'rejected' === $group_member_status->group_member_status ) {
					$message = sprintf('Your request to join the group rejected. Click <a href="%s">here</a> to remove your request.', URL_MANAGE_GROUP_REQUESTS_DIRECT . $group_slug . '/' . get_current_user_id() . '/withdraw');
				}
			}
		?>
		<div class="alert alert-info">
		  <strong>Info!</strong> <?php echo $message; ?>
		</div>
	<?php endif;
}

function group_member_status( $group_id )
{
	if ( Auth::check() ) {
		$check = App\LMSGroups::select(['lmsgroups.*', 'lgu.status AS group_member_status'])->join('lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id')
		->where('lgu.user_id', '=', get_current_user_id())
		->where('lmsgroups.id', '=', $group_id)->first()
		;
		return $check;
	} else {
		return FALSE;
	}
}

function get_current_user_id()
{
	if ( Auth::check() ) {
		return Auth::User()->id;
	} else {
		return 0;
	}
}

function get_current_user_special_role()
{
	if ( Auth::check() ) {
		return Auth::User()->current_user_role;
	} else {
		return 'subscriber';
	}
}

function get_current_user_level()
{
	if ( Auth::check() ) {
		return Auth::User()->current_user_level;
	} else {
		return 'subscriber';
	}
}

function get_current_user_coach_id()
{
	if ( Auth::check() ) {
		return Auth::User()->coach_id;
	} else {
		return 0;
	}
}

function is_coach_for( $user_id )
{
	if ( Auth::check() ) {
		$check = App\User::where('coach_id', '=', get_current_user_id())->where('id', '=', $user_id)->get();
		return ( $check->count() > 0 );
	} else {
		return FALSE;
	}
}

function is_facilitator()
{
	if ( Auth::check() ) {
		return ('facilitator' === Auth::User()->current_user_role);
	} else {
		return FALSE;
	}
}

function sendMessage( $from, $to, $subject, $message )
{
	$thread = Cmgmyr\Messenger\Models\Thread::create([ 'subject' => $subject,]);
	// Message
	Cmgmyr\Messenger\Models\Message::create(
		[
			'thread_id' => $thread->id,
			'user_id'   => $from,
			'body'      => $message,
		]
	);

	// Sender
	Cmgmyr\Messenger\Models\Participant::create(
		[
			'thread_id' => $thread->id,
			'user_id'   => $from,
			'last_read' => new Carbon\Carbon,
		]
	);

	// Recipients
	$recipients = is_array( $to ) ? $to : array( $to );
	$thread->addParticipant($recipients);
}
