<?php

namespace App\Http\Controllers\Auth;

use \Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

use MikeMcLin\WpPassword\Facades\WpPassword;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
             'username' => 'required|unique:users,username|unique:' . WP_TABLE_PREFIX . 'users,user_login',
            'first_name' => 'required|max:255',
            'email' => 'required|unique:users,email|unique:' . WP_TABLE_PREFIX . 'users,user_email',
            'password' => 'required|min:8|confirmed',
        ]);
    }
	
	public function getRegister( $role = 'user' )
	{
		if( Auth::check() ) {
			if ( checkRole( getUserGrade( 2 ) ) ) {
				return redirect( URL_USERS_DASHBOARD );
			} else {
				return redirect( URL_USERS_DASHBOARD_USER );
			}
		}
		if( ! in_array( $role, array('user', 'vendor') ) ) {
			flash('Ooops...!', getPhrase("some thing wrong"), 'error');
			return redirect( URL_USERS_REGISTER );
		}
		$data['main_active'] 	= 'register';
		$data['role'] = $role;
		return view('auth.register', $data);
	}
	
	/**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function postRegister(Request $request)
    {
		
		$columns = array(
        // 'name'  => 'bail|required|max:20|',
		'first_name' => 'required|max:50',
        'username' => 'required|unique:users,username|unique:' . WP_TABLE_PREFIX . 'users,user_login',
        'email' => 'required|unique:users,email|unique:' . WP_TABLE_PREFIX . 'users,user_email',
        'password'=> 'required|min:8',
        'password_confirmation'=>'required|min:8|same:password',
        );
		$this->validate($request,$columns);
		
		
		$data = array(
			'first_name' => $request->first_name,
			'username' => $request->username,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => $request->password,
			'role' => $request->role,			
		);
		$name = $data['first_name'];
		if ( ! empty( $data['last_name'] ) ) {
			$name .= ' ' . $data['last_name'];
		}
		
		$user    = new User();
		$user->name     = $name;
		$user->username     = $data['username'];
        $user->first_name     = $data['first_name'];
        if ( ! empty( $data['last_name'] ) ) {
			$user->last_name    = $data['last_name'];
		}		
		$user->email     = $data['email'];
        // $user->password = bcrypt($data['password']);
		$user->password = WpPassword::make( $data['password'] );
        $user->role_id  = USER_ROLE_ID;		
        $user->slug     = $user->makeSlug($user->name);
		$user->confirmation_code = str_random(30);
		$link = URL_USERS_CONFIRM . '/' . $user->confirmation_code;
		$user->save();
		$user->roles()->attach($user->role_id);
		
		// Let us insert user into Wp
		$wp_user_id = is_wp_user_exists( $user->username );
		if ( $wp_user_id == 0 ) {
			insert_into_wp( $user );
		}
		
		try{
			sendEmail('registration', array('user_name'=>$user->username, 'username'=>$user->name, 'to_email' => $user->email, 'password'=>$data['password'], 'confirmation_link' => $link));
        }
        catch(Exception $ex)
        {
            
        }
		$message = 'You Have Registered Successfully. Please Check Your Email <b>'.$user->email.'</b> To Activate Your Account';
		// flash('success', $message, 'success');
		return redirect( URL_USERS_LOGIN )->with('success', $message);
    }




    public function studentOnlineRegistration()
    {
        return view('auth.student-online-registration');
    }
}
