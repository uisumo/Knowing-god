<?php

namespace App\Http\Controllers\Auth;

use App\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use \Auth;
use DB;
use Socialite;
use Exception;

use MikeMcLin\WpPassword\Facades\WpPassword;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;
	use AuthenticatesUsers {
		logout as performLogout;
	}

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
	protected $redirectTo = '/';
	protected $dbuser = '';
	protected $provider = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }





   /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $type = 'student';
        if($data['is_student'])
            $type = 'parent';

        $role = getRoleData($type);

        $user           = new User();
        $user->name     = $data['name'];
        $user->username     = $data['username'];

        $user->email    = $data['email'];
        // $user->password = bcrypt($data['password']);
		$user->password = WpPassword::make( $data['password'] );
        $user->role_id  = $role;
        $user->slug     = $user->makeSlug($user->name);
		$user->confirmed  = 'no';

        $user->save();

        $user->roles()->attach($user->role_id);
        try{
            $this->sendPushNotification($user);
        sendEmail('registration', array('user_name'=>$user->name, 'username'=>$data['username'], 'to_email' => $user->email, 'password'=>$data['password']));

          }
         catch(Exception $ex)
        {

        }

        flash('success','record_added_successfully', 'success');

        $options = array(
                            'name' => $user->name,
                            'image' => getProfilePath($user->image),
                            'slug' => $user->slug,
                            'role' => getRoleData($user->role_id),
                        );
        pushNotification(['owner','admin'], 'newUser', $options);
         return $user;
    }



      public function sendPushNotification($user)
     {
        if(getSetting('push_notifications', 'module')) {
          if(getSetting('default', 'push_notifications')=='pusher') {
              $options = array(
                    'name' => $user->name,
                    'image' => getProfilePath($user->image),
                    'slug' => $user->slug,
                    'role' => getRoleData($user->role_id),
                );

            pushNotification(['owner','admin'], 'newUser', $options);
          }
          else {
            $this->sendOneSignalMessage('New Registration');
          }
        }
     }


      //this view the login page
     public function getLogin()
    {
		if ( Auth::check() ) {
			return redirect( URL_USERS_DASHBOARD );
		}
		return view('auth.login');
    }

	public function getLoginTest()
    {
		echo $hashed_password = WpPassword::make('plain-text-password');
		die();
		return view('auth.login');
    }


    /**
     * This is method is override from Authenticate Users class
     * This validates the user with username or email with the sent password
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postLogin(Request $request)
    {
		$username = $request->email;
		$password = $request->password;
		// Check user for activation
		$check_lms_user = DB::table( 'users' )->select( '*' )->where('username', '=', $username )->first();
		if ( ! $check_lms_user ) {
			 $check_lms_user = DB::table( 'users' )->select( '*' )->where('email', '=', $username )->first();
		}
		 
		if ( $check_lms_user ) {
			if( $check_lms_user->confirmed == 'no' )
		   {
				$message = getPhrase('Please check your email to activate your acount.');
				// flash('Ooops...!', $message, 'error');
				return redirect()->back()->withErrors( $message );
		   }
		   elseif($check_lms_user->status == 'suspended' )
		   {
				$message = getPhrase('We are sorry!. Your account has beed suspended. Please contact administrator');
				// flash('Ooops...!', $message, 'error');
				return redirect()->back()->withErrors( $message );
		   }		   
		}
		
		$wp_user = DB::table(WP_TABLE_PREFIX . 'users')->select( '*' )->where('user_login', '=', $username )->first();
		if ( ! $wp_user ) {
			$wp_user = DB::table(WP_TABLE_PREFIX . 'users')->select( '*' )->where('user_email', '=', $username )->first();
		}

		if ( ! $wp_user ) {
			$message = getPhrase("These credentials do not match our records.");
			// flash('Ooops...!', $message, 'error');
			return redirect()->back()->withErrors( $message );
		} else {
			$plain_password = $password;
			$wp_password = $wp_user->user_pass;

			if ( WpPassword::check($plain_password, $wp_password) ) {
				$check_lms_user = DB::table( 'users' )->select( '*' )->where('username', '=', $username )->first();
				 if ( ! $check_lms_user ) {
					 $check_lms_user = DB::table( 'users' )->select( '*' )->where('email', '=', $username )->first();
				 }

				 if ( ! $check_lms_user )
				 {
					// User not exists in LMS site. Lets inset the record
					$wp_user_id = $wp_user->ID;
					$wp_user_details = \Corcel\Model\User::find( $wp_user_id );
										
					$user    = new User();
					if ( $wp_user_details ) {
						$role_id = USER_ROLE_ID;
						$user_meta = $wp_user_details->meta;
						if ( $user_meta->wp_user_level > 0 ) {
							$role_id = OWNER_ROLE_ID;
						}
						
						$user->name     = $wp_user_details->display_name;
						$user->username     = $wp_user_details->user_login;
						$user->first_name     = $user_meta->first_name;
						$user->last_name    = $user_meta->last_name;
						$user->email     = $wp_user_details->user_email;
						$user->password = $wp_user_details->user_pass;
						$user->role_id  = $role_id;
						$user->slug     = $user->makeSlug($user->name);
						$user->confirmation_code = NULL;
						$user->confirmed  = '1';
						$user->status  = 'activated';
						$user->wp_user_id = $wp_user_details->ID;
						$user->save();
						$user->roles()->attach($user->role_id);
						$user_id = $user->id;
						Auth::loginUsingId( $user_id, true );

						$user_login = $wp_user_details->user_login;
						// setcookie('kg_user', base64_encode( $user_login ), time() + (86400 * 30), "/"); // 86400 = 1 day
						// Some systems not supporting Cookies so lets do this using DB
						DB::table('users')
							->where('id', '=', $user_id)
							->update( array(
								'is_lms_loggedin' => 'yes',
							) );
						$message = getPhrase( 'Password Success. Please wait we are redirecting...' );

						$redirect_to = base64_encode( URL_USERS_DASHBOARD_USER );
						if( in_array($user->role_id, array( OWNER_ROLE_ID, ADMIN_ROLE_ID, EXECUTIVE_ROLE_ID )) ) {
							$redirect_to = base64_encode( URL_USERS_DASHBOARD );
						}
						return redirect( URL_LOGIN_WP_USER . '?redirect=' . $redirect_to );
					} else {
						$user->name     = $wp_user->display_name;
						$user->username     = $wp_user->user_login;
						$user->first_name     = $wp_user->display_name;
						// $user->last_name    = $user_meta->last_name;
						$user->email     = $wp_user->user_email;
						$user->password = $wp_user->user_pass;
						$user->role_id  = USER_ROLE_ID;
						$user->slug     = $user->makeSlug($user->name);
						$user->confirmation_code = NULL;
						$user->confirmed  = '1';
						$user->status  = 'activated';
						$user->wp_user_id = $wp_user->ID;
						$user->save();
						$user->roles()->attach($user->role_id);

						$user_id = $user->id;
						Auth::loginUsingId( $user_id, true );

						$user_login = $wp_user->user_login;
						// setcookie('kg_user', base64_encode( $user_login ), time() + (86400 * 30), "/"); // 86400 = 1 day
						
						// Some systems not supporting Cookies so lets do this using DB
						DB::table('users')
							->where('id', '=', $user_id)
							->update( array(
								'is_lms_loggedin' => 'yes',
							) );

						$message = getPhrase( 'Password Success. Please wait we are redirecting...' );
						$redirect_to = base64_encode( URL_USERS_DASHBOARD_USER );
						if( in_array($user->role_id, array( OWNER_ROLE_ID, ADMIN_ROLE_ID, EXECUTIVE_ROLE_ID )) ) {
							$redirect_to = base64_encode( URL_USERS_DASHBOARD );
						}
						return redirect( URL_LOGIN_WP_USER . '?redirect=' . $redirect_to );
					}
				 } else {

					$user_id = $check_lms_user->id;
					Auth::loginUsingId( $user_id, true );

					$username = $check_lms_user->username;
					// setcookie('kg_user', base64_encode( $username ), time() + (86400 * 30), "/"); // 86400 = 1 day
					
					// Some systems not supporting Cookies so lets do this using DB
					DB::table('users')
						->where('id', '=', $user_id)
						->update( array(
							'is_lms_loggedin' => 'yes',
						) );

					$redirect_to = base64_encode( URL_USERS_DASHBOARD_USER );
					if( in_array($check_lms_user->role_id, array( OWNER_ROLE_ID, ADMIN_ROLE_ID, EXECUTIVE_ROLE_ID )) ) {
						$redirect_to = base64_encode( URL_USERS_DASHBOARD );
					}
					// Here the key is user name in LMS 'users' table
					return redirect( URL_LOGIN_WP_USER . '?redirect=' . $redirect_to . '&key=' . base64_encode( $username ) );
				 }
			} else {
				$message = getPhrase("These credentials do not match our records.");
				// flash('Ooops...!', $message, 'error');
				return redirect()->back()->withErrors( $message );
			}
		}
    }

	public function checkLogin( $user_name )
	{
		if ( Auth::check() ) {
			return redirect(URL_USERS_DASHBOARD);
		} else {

		}

	}





     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($logintype)
    {

        if(!getSetting($logintype.'_login', 'module'))
        {
            flash('Ooops..!', $logintype.'_login_is_disabled','error');
             return redirect(PREFIX);
        }
        $this->provider = $logintype;
        return Socialite::driver($this->provider)->redirect();

    }

     /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($logintype)
    {

        try{
        $user = Socialite::driver($logintype);


        if(!$user)
        {
            return redirect(PREFIX);
        }

        $user = $user->user();


         if($user)
         {

            if($this->checkIsUserAvailable($user)) {
                Auth::login($this->dbuser, true);
                flash('Success...!', 'log_in_success', 'success');
                return redirect(PREFIX);
            }
            flash('Ooops...!', 'faiiled_to_login', 'error');
            return redirect(PREFIX);
         }
     }
         catch (Exception $ex)
         {
            return redirect(PREFIX);
         }
    }

    public function checkIsUserAvailable($user)
    {

        $id         = $user->getId();
        $nickname   = $user->getNickname();
        $name       = $user->getName();
        $email      = $user->getEmail();
        $avatar     = $user->getAvatar();

        $this->dbuser = User::where('email', '=',$email)->first();

        if($this->dbuser) {
            //User already available return true
            return TRUE;
        }

        $newUser = array(
                            'name' => $name,
                            'email'=>$email,
                        );
        $newUser = (object)$newUser;

        $userObj = new User();
       $this->dbuser = $userObj->registerWithSocialLogin($newUser);
       $this->dbuser = User::where('slug','=',$this->dbuser->slug)->first();
       // $this->sendPushNotification($this->dbuser);
       return TRUE;

    }

    public function socialLoginCancelled(Request $request)
    {
         return redirect(PREFIX);
    }

	public function loginWpUser( $user_id, $redirect_url = '' ) {
		Auth::loginUsingId( base64_decode( $user_id ), true );
		
		// Let us check if user logged in Wp
		$check = DB::table('users')
		->where('id', '=', get_current_user_id())
		->where('is_wp_loggedin', '=', 'yes')->get();
		if ( $check->count() == 0 ) {
			flash('Ooops...!', 'are_you_kidding?', 'error');
            return redirect( URL_USERS_REGISTER );
		}
		
		DB::table('users')->where('id', '=', get_current_user_id())->update(
			array(
				'is_lms_loggedin' => 'yes',
			)
		);
		if ( ! empty( $redirect_url ) ) {
			return redirect( base64_decode( $redirect_url ) );
		} else {
			return redirect('dashboard');
		}
	}

	public function insertLmsUserToWp( $lms_user )
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
		// setcookie('user', base64_encode( $lms_user->username ), time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	
	public function ajaxLogin( Request $request )
	{
		$username = $request->email;
		$password = $request->password;
		
		// Check user for activation
		$check_lms_user = DB::table( 'users' )->select( '*' )->where('username', '=', $username )->first();
		if ( ! $check_lms_user ) {
			 $check_lms_user = DB::table( 'users' )->select( '*' )->where('email', '=', $username )->first();
		}
		 
		if ( $check_lms_user ) {
			if( $check_lms_user->confirmed == 'no' )
		   {
				$message = getPhrase('Please check your email to activate your acount.');
				return json_encode( array( 'status' => '0', 'message' => $message ) );
		   }
		   elseif($check_lms_user->status == 'Suspended' )
		   {
				$message = getPhrase('We are sorry!. Your account has beed suspended. Please contact administrator');
				return json_encode( array( 'status' => '0', 'message' => $message ) );
		   }		   
		}
		
		$login_status = FALSE;
		$wp_user = DB::table(WP_TABLE_PREFIX . 'users')->select( '*' )->where('user_login', '=', $username )->first();
		if ( ! $wp_user ) {
			$wp_user = DB::table(WP_TABLE_PREFIX . 'users')->select( '*' )->where('user_email', '=', $username )->first();
		}
		if ( ! $wp_user ) { // User not found in WP so user not yet registered. We are registering users with WP!
			$message = getPhrase( 'Sorry we dont have account with these details' );
			return json_encode( array( 'status' => '0', 'message' => $message ) );
		} else {
			$plain_password = $password;
			$wp_password = $wp_user->user_pass;
			if ( WpPassword::check($plain_password, $wp_password) ) {
				/**
				 * Let us sync user with LMS site
				 * case 1: Lets check if user exists in LMS site
				 * case 2: If user not exists in LMS site lets insert into it and login user with LMS user ID
				 * case 3: If user exists in LMS site. Lets login user with LMS user id
				 */
				 $check_lms_user = DB::table( 'users' )->select( '*' )->where('username', '=', $username )->first();
				 if ( ! $check_lms_user ) {
					 $check_lms_user = DB::table( 'users' )->select( '*' )->where('email', '=', $username )->first();
				 }
				 if ( ! $check_lms_user ) { // User not exists in LMS site. Lets inset the record
					$wp_user_id = $wp_user->ID;
					$wp_user_details = \Corcel\Model\User::find( $wp_user_id );

					$user    = new User();
					if ( $wp_user_details ) {
						$user_meta = $wp_user_details->meta;
						$role_id = USER_ROLE_ID;
						if ( $user_meta->wp_user_level > 0 ) {
							$role_id = OWNER_ROLE_ID;
						}
						$user->name     = $wp_user_details->display_name;
						$user->username     = $wp_user_details->user_login;
						$user->first_name     = $user_meta->first_name;
						$user->last_name    = $user_meta->last_name;
						$user->email     = $wp_user_details->user_email;
						$user->password = $wp_user_details->user_pass;
						$user->role_id  = $role_id;
						$user->slug     = $user->makeSlug($user->name);
						$user->confirmation_code = NULL;
						$user->confirmed  = '1';
						$user->status  = 'activated';
						$user->wp_user_id = $wp_user_details->ID;
						$user->save();
						$user->roles()->attach($user->role_id);
						$user_id = $user->id;
						Auth::loginUsingId( $user_id, true );

						$user_login = $wp_user_details->user_login;
						// setcookie('kg_user', base64_encode( $user_login ), time() + (86400 * 30), "/"); // 86400 = 1 day
						
						// Let us update DB
						DB::table('users')->where('id', '=', get_current_user_id())->update(
							array(
								'is_lms_loggedin' => 'yes',
							)
						);
						$message = getPhrase( 'Password Success. Please wait we are redirecting...' );
						return json_encode( array( 'status' => '1', 'message' => $message, 'redirect_to' => URL_LOGIN_WP_USER . '?redirect=' . $request->redirect_to . '&key=' . base64_encode( $user_login ) ) );
					} else {
						$user->name     = $wp_user->display_name;
						$user->username     = $wp_user->user_login;
						$user->first_name     = $wp_user->display_name;
						// $user->last_name    = $user_meta->last_name;
						$user->email     = $wp_user->user_email;
						$user->password = $wp_user->user_pass;
						$user->role_id  = USER_ROLE_ID;
						$user->slug     = $user->makeSlug($user->name);
						$user->confirmation_code = NULL;
						$user->confirmed  = '1';
						$user->status  = 'activated';
						$user->wp_user_id = $wp_user->ID;
						$user->save();
						$user->roles()->attach($user->role_id);
						$user_id = $user->id;
						Auth::loginUsingId( $user_id, true );

						$user_login = $wp_user->user_login;
						// setcookie('kg_user', base64_encode( $user_login ), time() + (86400 * 30), "/"); // 86400 = 1 day
						
						// Let us update DB
						DB::table('users')->where('id', '=', get_current_user_id())->update(
							array(
								'is_lms_loggedin' => 'yes',
							)
						);

						$message = getPhrase( 'Password Success. Please wait we are redirecting...' );
						return json_encode( array( 'status' => '1', 'message' => $message, 'redirect_to' => URL_LOGIN_WP_USER . '?redirect=' . $request->redirect_to . '&key=' . base64_encode( $user_login ) ) );
					}
				 } else { // User found in LMS site so we just

					$user_id = $check_lms_user->id;
					Auth::loginUsingId( $user_id, true );
					$wp_user_id = $check_lms_user->wp_user_id;
					if ( empty( $wp_user_id ) ) { // May be it missed to update 'wp_user_id' in LMS users table! Lets find it now!!
						$wp_user_id = $wp_user->ID;
						DB::table( 'users' )
							->where( 'id', '=', $user_id )
							->update(
							array( 'wp_user_id' => $wp_user_id )
						);
					}

					$username = $check_lms_user->username;
					// setcookie('kg_user', base64_encode( $username ), time() + (86400 * 30), "/"); // 86400 = 1 day
					
					// Let us update DB
					DB::table('users')->where('id', '=', get_current_user_id())->update(
						array(
							'is_lms_loggedin' => 'yes',
						)
					);

					$message = getPhrase( 'Password Success. Please wait we are redirecting...' );
					return json_encode( array( 'status' => '1', 'message' => $message, 'redirect_to' => URL_LOGIN_WP_USER . '?redirect=' . $request->redirect_to . '&key=' . base64_encode( $username ) ) );
				 }
			} else {
				$message = getPhrase( 'Password Failed' );
				return json_encode( array( 'status' => '0', 'message' => $message ) );
			}
		}
	}

	// MOBILE APP SERVICE FOR LOGIN

	public function login(Request $request)
	{


		 $login_status = FALSE;
        if (Auth::attempt(['username' => $request->email, 'password' => $request->password])) {
                // return redirect(PREFIX);
                $login_status = TRUE;
        }

        elseif (Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
            $login_status = TRUE;
        }

        if(!$login_status)
        {
        	 $message = getPhrase("Please Check Your Details");
            //flash('Ooops...!', $message, 'error');
			   return $message;

            //    return redirect()->back()
            // ->withInput($request->only($this->loginUsername(), 'remember'))
            // ->withErrors([
            //     $this->loginUsername() => $this->getFailedLoginMessage(),
            // ]);
        }
		return $login_status;
        /**
         * Check if the logged in user is parent or student
         * if parent check if admin enabled the parent module
         * if not enabled show the message to user and logout the user
         */

        if($login_status) {
            if(checkRole(getUserGrade(7)))  {
               if(!getSetting('parent', 'module')) {
                return redirect(URL_PARENT_LOGOUT);
               }
            }
        }

        /**
         * The logged in user is student/admin/owner
         */


	}

	public function confirm($confirmation_code)
	{
		$record = User::where('confirmation_code', $confirmation_code)->first();
		if($isValid = $this->isValidRecord($record))
		return redirect($isValid);

		$record->confirmed = 'yes';
		$record->confirmation_code = null;
		$record->status = 'activated';
		$record->save();
		$wp_user_id = is_wp_user_exists( $record->username );
		if ( $wp_user_id == 0 ) {
			insert_into_wp( $record );
		}
		
		$site_admin = default_site_admin();
		if ( ! empty( $site_admin ) ) {
			$thread = Thread::create( [
					'subject' => 'welcome to KG',
				]
			);
			// Message
			$message = 'Thanks for signing up for the KG website and joining our community! We\'re excited to help you follow Jesus with all your heart. Please take a minute to look around at your personal dashboard because it is your home at KnowingGod. Right from here you can add a friend, join a group, track your progress through the website, see unfinished content, and many more things. When you\'re ready go ahead and watch the about us video for an overview of the entire site. May God bless you richly as come to know Him better!';
			Message::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => $site_admin,
					'body'      => $message,
				]
			);
				
			// Sender
			Participant::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => $site_admin,
					'last_read' => new Carbon,
				]
			);
			$recipients = $record->id;
			// Recipients
			$thread->addParticipant($recipients);
		}
		
		flash('Success', getPhrase("You have successfully activated your account. Please login here."), 'success');
		return redirect(URL_USERS_LOGIN);
	}

	public function isValidRecord($record)
	{
	  if ($record === null) {
			flash('Ooops...!', getPhrase("code not valid"), 'error');
			return $this->getRedirectUrl();
		}
	}
	
	public function sendActivationMail( Request $request )
	{
		if ($request->isMethod('post')) {
			$details = User::where('email', '=', $request->email)->first();
			if( $details )
			{
				if( $details->status == 'suspended' )
				{
					$message = 'Admin has suspended your account. Please contact administrator.';
					flash('Ooops...!', $message, 'error');
					return redirect('send-activation-mail')->with('email', $request->email)->with( 'message', $message );
				} else {
					$details->confirmation_code = str_random(30);
					$details->save();
					
					$link = URL_USERS_CONFIRM . '/' . $details->confirmation_code;
					sendEmail('activation-mail', array('user_name'=>$details->email, 'username'=>$details->email, 'to_email' => $details->email, 'password'=>$details->password, 'confirmation_link' => $link));
					
					$message = 'We have send activation email to your email address, please check.';
					flash('Success', $message, 'success');
					return redirect('send-activation-mail')->with('email', $request->email)->with( 'message', $message );
				}
			} else {
				$message = 'We have not found your email address';
				flash('Ooops...!', $message, 'error');
				return redirect('send-activation-mail')->with('email', $request->email)->with( 'message', $message );
			}
		}
		return view('auth.send-activation-mail');
	}
	

	public function forgotpasswordEmail( Request $request )
	{
		$details = User::where('email', '=', $request->email)->first();

		if( $details )
		{
			if( $details->status == 'registered' )
			{
				flash('Ooops...!', 'Your account not yet activated.', 'error');
				return redirect('send-activation-mail')->with('email', $request->email);
			}
			elseif( $details->status == 'suspended' )
			{
				flash('Ooops...!', 'Admin has suspended your account. Please contact administrator', 'error');
			}
			else
			{
				$forgot_token = str_random(30);
				$details->forgot_token = $forgot_token;
				$random_password = str_random(10);
				//$details->password = bcrypt( str_random(30) );
				$details->save();
				$login_link = URL_USERS_LOGIN;
				$changepassword_link = URL_USERS_RESETPASSWORD . '/' . $forgot_token;
				$site_title = getSetting('site_title', 'site_settings');
				try{
					sendEmail('forgotpassword', array('user_name'=>$details->name, 'to_email' => $details->email, 'password' => $random_password, 'login_link' => $login_link, 'changepassword_link' =>  $changepassword_link, 'site_title' => $site_title));
					flash('Success...!', 'Reset Password Sent To Your Mail', 'success');
				}
				catch(Exception $ex)
				{
					flash('Ooops...!', 'There was an error : ' . $ex->getMessage(), 'error');
				}
			}
			return redirect( URL_USERS_LOGIN );
		}
		else
		{
			flash('Ooops...!', 'We have not found your email address', 'error');
			return redirect( URL_USERS_LOGIN );
		}
	}

	public function testmail()
	{

		$details = User::where('email', '=', 'adiyya@gmail.com')->first();

		$forgot_token = str_random(30);
		$details->forgot_token = $forgot_token;
		$random_password = str_random(10);
		// $details->password = bcrypt( str_random(30) );
		$details->save();
		$login_link = URL_USERS_LOGIN;
		$changepassword_link = URL_USERS_RESETPASSWORD . '/' . $forgot_token;
		$site_title = getSetting('site_title', 'site_settings');
		// print_r( $details );
		try{
			$ret = sendEmail('forgotpassword', array('user_name'=>$details->name, 'to_email' => $details->email, 'password' => $random_password, 'login_link' => $login_link, 'changepassword_link' =>  $changepassword_link, 'site_title' => $site_title));

			//flash('Success...!', 'Reset Password Sent To Your Mail', 'success');
			die('SENT');
		}
		catch(Exception $ex)
		{
			//flash('Ooops...!', 'There was an error : ' . $ex->getMessage(), 'error');
			echo $ex->getMessage();die();
		}
		// return redirect( URL_USERS_FORGOTPASSWORD );
	}

	public function resetpassword( $forgot_token )
	{
		$details = User::where('forgot_token', '=', $forgot_token)->first();
		if( $details )
		{
			$data['token'] = $forgot_token;
			$data['main_active'] 	= 'register';
			return view('auth.passwords.reset', $data);
		}
		else
		{
			flash('Ooops...!', 'link is not valid. please check your email for details', 'error');
			return redirect( URL_USERS_LOGIN );
		}
	}

	public function resetmypassword(Request $request)
	{
		$this->validate($request, [
        'email' => 'required|email',
		'password'  => 'required|min:8|confirmed',
		'password_confirmation'  => 'required|min:8|same:password',
        ]);
		$details = User::where('forgot_token', '=', $request->token)->where('email', '=', $request->email)->first();

		if( $details )
		{
			// $details->password = bcrypt($request->password);
			$details->password = WpPassword::make( $request->password );
			$details->forgot_token = null;
			$details->confirmed = 'yes';
			$details->confirmation_code = null;
			$details->status = 'activated';
			// $details->save();
			$wp_user_id = is_wp_user_exists( $details->username );
			
			if ( $wp_user_id > 0  ) {
				update_wp_password( $wp_user_id, WpPassword::make( $request->password ) );
			} else {
				insert_into_wp( $details );
			}
			flash('Congrulations...!', 'You have successfully reset your password. Please login here.', 'success');
			return redirect( URL_USERS_LOGIN );
		}
		else
		{
			flash('Ooops...!', 'link_is_not_valid. please_check_your_email_for_details', 'error');
			return redirect( URL_USERS_LOGIN );
		}
	}

	public function getLoginForm()
	{
		$data = array( 'from' => 'wp' );
		$html = view('lms-forntview.login-form')->with( $data )->render();
		return response()->json( array( 'html' => $html ) );
	}
	
	public function confirmChangeEmail( $code )
	{
		if ( empty( $code ) ) {
			flash('Ooops...!', 'Wrong operation.', 'error');
			$url = URL_USERS_LOGIN;
			if ( Auth::check() ) {
				$url = URL_USERS_DASHBOARD;
			}
			return redirect( $url );
		}
		$check = DB::table( 'email_change_requests' )->where( 'confirm_code', '=', $code )->first();
		
		if ( empty( $check ) ) {
			flash('Ooops...!', 'Wrong operation.', 'error');
			$url = URL_USERS_LOGIN;
			if ( Auth::check() ) {
				$url = URL_USERS_DASHBOARD;
			}
			return redirect( $url );
		}

		$lms_check = User::where( 'email', '=', $check->new_email )->first();
		$wp_check = DB::table( WP_TABLE_PREFIX . 'users' )->where( 'user_email', '=', $check->new_email )->first();
		if ( ! $lms_check && ! $wp_check  ) { // To make sure email is not exists in our database
			$user = User::where( 'id', '=', $check->user_id )->first();
			if ( $user ) {
				DB::table( 'users' )->where( 'username', '=', $user->username )->update( array( 'email' => $check->new_email ) );

				DB::table( WP_TABLE_PREFIX . 'users' )->where( 'user_login', '=', $user->username )->update( array( 'user_email' => $check->new_email ) );
				
				DB::table( 'email_change_requests' )->where( 'confirm_code', '=', $code )->delete();

				if ( Auth::check() ) {
					flash('Success...!', 'You have changed your email address successfully.', 'success');
					return redirect(URL_USERS_EDIT.Auth::user()->slug);
				} else {
					flash('Success...!', 'You have changed your email address successfully. Please login.', 'success');
					return redirect(URL_USERS_LOGIN);
				}
			} else {
				flash('Ooops...!', 'Some thing went wrong. Please login to do this opetation.', 'error');
				return redirect(URL_USERS_LOGIN);
			}
		} else {
			flash('Ooops...!', 'This email already exist in the system.', 'error');
			$url = URL_USERS_LOGIN;
			if ( Auth::check() ) {
				$url = URL_CHANGE_EMAIL;
			}
			return redirect( $url );
		}
	 }

}
