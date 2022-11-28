<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use \App;
use Password;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;

use Corcel\Laravel\Auth\ResetsPasswords as CorcelResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
	// use ResetsPasswords;
    
	use ResetsPasswords, CorcelResetsPasswords {
        CorcelResetsPasswords::resetPassword insteadof ResetsPasswords;
    }
	
	

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	
	public function forgotpasswordEmail( Request $request )
	{
		$details = User::where('email', '=', $request->email)->first();
		if( $details )
		{
			if( $details->status == 'Suspended' )
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
			return redirect( URL_USERS_FORGOTPASSWORD );
		}
	}
 
    
}
