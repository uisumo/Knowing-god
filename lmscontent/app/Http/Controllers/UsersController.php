<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\User;
use App\GeneralSettings as Settings;
use Image;
use ImageSettings;
use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Support\Facades\Hash;
use Excel;
use Input;
use File;
use App\OneSignalApp;
use Exception;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

use \Auth;

use MikeMcLin\WpPassword\Facades\WpPassword;

class UsersController extends Controller
{

  public $excel_data = '';
    public function __construct()
    {
         $currentUser = \Auth::user();

         $this->middleware('auth');

    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function index($role = 'all')
     {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['layout']      = getLayout();
        $data['active_class'] = 'users';
        $data['heading']      = getPhrase('users');
        $data['title']        = getPhrase('users');
		$data['role'] = $role;
        return view('users.list-users', $data);
     }
	 
	 public function myFacilitators()
	 {
		if(!checkRole(getUserGrade(5)))
        {
          prepareBlockUserMessage();
          return back();
        }
		
		if ( 'coach' !== Auth::User()->current_user_role ) {
			prepareBlockUserMessage();
			return back();
		}

        $data['records']      = FALSE;
        $data['layout']      = getLayout();
        $data['active_class'] = 'users';
        $data['heading']      = getPhrase('my_facilitators');
        $data['title']        = getPhrase('my_facilitators');
        return view('users.my-facilitators', $data);
	 }
	 
	 public function getMyFacilitators()
	 {
		if(!checkRole(getUserGrade(5)))
        {
          prepareBlockUserMessage();
          return back();
        }		
		$records = User::join('roles', 'users.role_id', '=', 'roles.id')
			->select(['users.name', 'email', 'image', 'current_user_role','login_enabled','role_id',
			'slug', 'users.id', 'users.updated_at', 'users.status', 'username'])
			->where('current_user_role', '=', 'facilitator')
			->where('coach_id', '=', Auth::User()->id)
			->orderBy('users.updated_at', 'desc');
		return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dLabel">';
		// $link_data .= '<li><a href="'.URL_USERS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
		// $link_data .= '<li><a href="'.URL_GET_USER_SEND_EMAIL.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("send_email").'</a></li>';
		
		$link_data .= '<li><a href="'.URL_MESSAGES_CREATE.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("send_message").'</a></li>';
          
		$temp='';

		// $temp .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'coach_approve\');"><i class="fa fa-trash"></i>'. getPhrase("approve").'</a></li>';

		// $temp .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'coach_reject\');"><i class="fa fa-trash"></i>'. getPhrase("reject").'</a></li>';

		// $temp .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'coach_delete\');"><i class="fa fa-trash"></i>'. getPhrase("request_delete").'</a></li>';

		$temp .='</ul> </div>';
		$link_data .= $temp;
            return $link_data;
        })
        ->editColumn('name', function($records) {
          if(getRoleData($records->role_id)=='student')
            return '<a href="' . URL_USER_DETAILS_COACH . $records->slug . '">'.ucfirst($records->name).'</a>';

          return ucfirst($records->name);
        })
		->editColumn('current_user_role', function($records) {
			return ucfirst( $records->current_user_role );
		})
		->editColumn('email', function($records) {
          return $records->username . ' / ' . $records->email;
        })
         ->editColumn('image', function($records){
            return '<img src="'.getProfilePath($records->image).'"  style="border-radius:50%;width: 160px;height: 160px;"/>';
        })

        ->removeColumn('login_enabled')
        ->removeColumn('role_id')
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')
		->removeColumn( 'status' )
		->removeColumn('username')
        ->make();
	 }
	 
	 public function coachRequests()
	 {
		if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['layout']      = getLayout();
        $data['active_class'] = 'users';
        $data['heading']      = getPhrase('coach_requests');
        $data['title']        = getPhrase('coach_requests');
        return view('users.coach-requests', $data);
	 }
	 
	public function getCoachRequests()
	{
		$records = User::join('roles', 'users.role_id', '=', 'roles.id')
			->join('coach_requests AS cr', 'cr.user_id', '=', 'users.id')
			->select(['users.name', 'email', 'image', 'current_user_role','login_enabled','role_id',
			'slug', 'users.id', 'users.updated_at', 'users.status', 'username'])
			// ->where('current_user_role', '=', 'subscriber')
			->orderBy('users.updated_at', 'desc');
		return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dLabel">';
						
        //  $link_data .= '<li><a href="'.URL_USERS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
		// $link_data .= '<li><a href="'.URL_GET_USER_SEND_EMAIL.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("send_email").'</a></li>';
          if( in_array( $records->status, array( 'registered', 'suspended' ) ) )
          {
           // $link_data .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug . '\', \'activated\');"><i class="fa fa-trash"></i>' . getPhrase("activate").'</a></li>';
          } else {
			 // $link_data .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'suspended\');"><i class="fa fa-trash"></i>'. getPhrase("suspend").'</a></li>';
		  }
                         $temp='';

                        //Show delete option to only the owner user
                        if(checkRole(getUserGrade(1)) && $records->id!=\Auth::user()->id)   {
                       //$temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("user_delete").'</a></li>';
                         }
						 
						$temp .= '<li><a href="'.URL_USERS_EDIT.$records->slug.'/assigncoach"><i class="fa fa-trash"></i>'. getPhrase("approve").'</a></li>';
						
						$temp .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'coach_reject\');"><i class="fa fa-trash"></i>'. getPhrase("reject").'</a></li>';
						
						$temp .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'coach_delete\');"><i class="fa fa-trash"></i>'. getPhrase("request_delete").'</a></li>';

                        $temp .='</ul> </div>';
                        $link_data .= $temp;
            return $link_data;
            })
         ->editColumn('name', function($records) {
          if(getRoleData($records->role_id)=='student')
            return '<a href="'.URL_USER_DETAILS.$records->slug.'">'.ucfirst($records->name).'</a>';

          return ucfirst($records->name);
        })
		->editColumn('current_user_role', function($records) {
			return ucfirst( $records->current_user_role );
		})
		->editColumn('email', function($records) {
          return $records->username . ' / ' . $records->email;
        })
         ->editColumn('image', function($records){
            return '<img src="'.getProfilePath($records->image).'"  style="border-radius:50%;width: 160px;height: 160px;"/>';
        })

        ->removeColumn('login_enabled')
        ->removeColumn('role_id')
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')
		->removeColumn( 'status' )
		->removeColumn('username')
        ->make();
	}


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getDatatable($slug = '')
    {
        $records = array();

        if($slug=='' || $slug=='all')
        {
             $records = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->select(['users.name', 'email', 'image', 'roles.display_name','login_enabled','role_id',
              'slug', 'users.id', 'users.updated_at', 'users.status', 'username', 'current_user_role'])
            ->orderBy('users.updated_at', 'desc');
        }
        else {
            if ( in_array( $slug, array( 'facilitator', 'coach' ) ) ) {
				$records = User::join('roles', 'users.role_id', '=', 'roles.id')
				->select(['users.name',  'image', 'email', 'roles.display_name','login_enabled','role_id','slug', 'users.updated_at', 'username', 'current_user_role'])
				->where('current_user_role', '=', $slug)
				 ->orderBy('users.updated_at', 'desc');
			} else {
				if ( 'subscriber' === $slug ) {
					$slug = 'student';
				}
				$role = App\Role::getRoleId($slug);
				if ( 'student' === $slug ) {
					$records = User::join('roles', 'users.role_id', '=', 'roles.id')
				->select(['users.name',  'image', 'email', 'roles.display_name','login_enabled','role_id','slug', 'users.updated_at', 'username', 'current_user_role'])
				->where( 'roles.id', '=', $role->id )
				->where( 'current_user_role', '=', 'subscriber' )
				->orderBy('users.updated_at', 'desc');
				} else {
					$records = User::join('roles', 'users.role_id', '=', 'roles.id')
				->select(['users.name',  'image', 'email', 'roles.display_name','login_enabled','role_id','slug', 'users.updated_at', 'username', 'current_user_role'])
				->where( 'roles.id', '=', $role->id )
				->orderBy('users.updated_at', 'desc');
				}
				
			}
        }
		

        return Datatables::of($records)
        ->addColumn('action', function ($records) {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="'.URL_USERS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
		$link_data .= '<li><a href="'.URL_GET_USER_SEND_EMAIL.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("send_email").'</a></li>';
          if( in_array( $records->status, array( 'registered', 'suspended' ) ) )
          {
            $link_data .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug . '\', \'activated\');"><i class="fa fa-trash"></i>' . getPhrase("activate").'</a></li>';
          } else {
			  $link_data .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'suspended\');"><i class="fa fa-trash"></i>'. getPhrase("suspend").'</a></li>';
		  }
			 $temp='';

			//Show delete option to only the owner user
			if(checkRole(getUserGrade(1)) && $records->id!=\Auth::user()->id)   {
				$temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
			}			
			if ( 'coach' === $records->current_user_role ) {
				$temp .= '<li><a href="'.URL_ASSIGN_FACILITATORS.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("assign_facilitators").'</a></li>';
			}
			$temp .='</ul> </div>';
			$link_data .= $temp;
            return $link_data;
            })
         ->editColumn('name', function($records) {
          if(getRoleData($records->role_id)=='student')
            return '<a href="'.URL_USER_DETAILS.$records->slug.'">'.ucfirst($records->name).'</a>';

          return ucfirst($records->name);
        })
		->editColumn('email', function($records) {
          return $records->username . ' / ' . $records->email;
        })
         ->editColumn('image', function($records){
            return '<img src="'.getProfilePath($records->image).'"  style="border-radius:50%;width: 160px;height: 160px;"/>';
        })
		->editColumn('display_name', function($records) {
			if ( USER_ROLE_ID === $records->role_id ) {
				return $records->current_user_role;
			} else {
				return $records->display_name;
			}			
		})
        ->removeColumn('login_enabled')
        ->removeColumn('role_id')
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')
		->removeColumn( 'status' )
		->removeColumn('username')
		->removeColumn('current_user_role')
        ->make();
    }
	
	public function assignFacilitators( $slug )
	{
		$coach = User::where('slug', '=', $slug)->first();
		if ( empty ( $coach ) ) {
			prepareBlockUserMessage( 'user_not_found' );
			return back();
		}
		
		if ( 'coach' !== $coach->current_user_role ) {
			prepareBlockUserMessage( 'selected_user_not_a_coach' );
			return back();
		}
		
		$data['settings']           = FALSE;
		
		$facilitators = DB::table('users')
                            ->where('coach_id', '=', $coach->id)
                            ->get();
		$previous_records = array();
		foreach($facilitators as $r){
			array_push($previous_records, $r);
		}
		$settings['contents'] = $previous_records;		
		$data['settings']  = json_encode($settings);
		
		$data['coach'] = $coach;
		$data['title']        = getPhrase('assign_facilitators_to') . ' : ' . $coach->name;
		$data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'users.right-bar-update-facilitators';
		
		$data['layout']      = getLayout();
		return view('users.assign-facilitators',$data);
	}
	
	public function getAssignFacilitators( $coach_slug = '' )
	{
		$records = User::join('roles', 'users.role_id', '=', 'roles.id')
			->join('coach_requests AS cr', 'cr.user_id', '=', 'users.id')
			->select(['users.name', 'email', 'image', 'current_user_role','login_enabled','role_id',
			'slug', 'users.id', 'users.updated_at', 'users.status', 'username'])
			->where('current_user_role', '=', 'facilitator')
			->orderBy('users.updated_at', 'desc');
		$coach = User::where('slug', '=', $coach_slug)->first();
		$existing_facilitators = array_pluck( DB::table('users')
				->where('coach_id', '=', $coach->id)
				->get(), 'id');
		$records = $records->whereNotIn('users.id', $existing_facilitators);
		return Datatables::of($records)
        ->addColumn('action', function ($records) use( $coach_slug ) {
			$coach = User::where('slug', '=', $coach_slug)->first();
			if ( $coach ) {
				$link_data = '<a href="javascript:void(0);" onclick="addToBag(\''.$coach->id.'\', \''.$records->id.'\');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> '. getPhrase("add").'</a>';
			} else {
				$link_data = '-';
			}			
            return $link_data;
        })
        ->editColumn('name', function($records) {
          if(getRoleData($records->role_id)=='student')
            return '<a href="'.URL_USER_DETAILS.$records->slug.'">'.ucfirst($records->name).'</a>';

          return ucfirst($records->name);
        })
		->editColumn('current_user_role', function($records) {
			return ucfirst( $records->current_user_role );
		})
		->editColumn('email', function($records) {
          return $records->username . ' / ' . $records->email;
        })
         ->editColumn('image', function($records){
            return '<img src="'.getProfilePath($records->image).'"  style="border-radius:50%;width: 160px;height: 160px;"/>';
        })

        ->removeColumn('login_enabled')
        ->removeColumn('role_id')
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')
		->removeColumn( 'status' )
		->removeColumn('username')
        ->make();
	}
	
	public function addToBag( Request $request )
	{		
		$coach_id = $request->coach_id;
		$facilitator_id = $request->facilitator_id;
        $coach = User::where('id','=', $coach_id)->first();
		$facilitator = User::where('id','=', $facilitator_id)->first();
		$msg = getPhrase('Something went wrong.');
		$status = 'fail';
		if ( 'coach' === $coach->current_user_role && 'facilitator' === $facilitator->current_user_role ) {	
			DB::table('users')
				->where('id', '=', $facilitator_id)
				->update(array('coach_id' => $coach_id));
			$msg = getPhrase('added');
			$status = 'success';
		} else {
			$msg = getPhrase('User not eligible to add this list');
		}
		$tr = '<tr><td>' . $facilitator->name . '</td><td>' . $facilitator->email . '</td><td><a ng-click="removeItem(' . $coach_id . ', ' . $facilitator_id . ')" class="btn-outline btn-close text-red"><i class="fa fa-close"></i></a></td></tr>';
        return json_encode(array('facilitator_id' => $facilitator_id, 'message' => $msg, 'status' => $status, 'facilitator_tr' => $tr, 'facilitator' => $facilitator ));
	}
	
	public function removeFromBag( Request $request )
	{		
		$coach_id = $request->coach_id;
		$facilitator_id = $request->facilitator_id;
		$msg = getPhrase('Facilitator Removed');
		$status = 'success';
		DB::table('users')
				->where('id', '=', $facilitator_id)
				->update(array('coach_id' => 0));
        return json_encode(array('facilitator_id' => $facilitator_id, 'message' => $msg, 'status' => $status ));
	}



     /**
      * Show the form for creating a new resource.
      *
      * @return Response
      */
     public function create()
     {
        if(!checkRole(getUserGrade(4)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['record']       = FALSE;
        $data['active_class'] = 'users';

        // $data['roles']        = $this->getUserRoles();
        $roles                = \App\Role::select('display_name', 'id','name')->where('status', '=', 'active')->get();
        $final_roles = [];
        foreach($roles as $role)
        {

           if(!checkRole(getUserGrade(1))) {

            if(!(strtolower($role->name) == 'admin' || strtolower($role->name) =='owner'))
              $final_roles[$role->id] = $role->display_name;
          }
          else
           $final_roles[$role->id] = $role->display_name;
        }
        $data['roles']        = $final_roles;
        $data['title']        = getPhrase('add_user');
        if(checkRole(['parent']))
          $data['active_class'] = 'children';
        $data['layout']       = getLayout();

        return view('users.add-edit-user', $data);
     }

     /**
      * This method returns the roles based on the user type logged in
      * @param  [type] $roles [description]
      * @return [type]        [description]
      */
     public function getUserRoles()
     {
        $roles                = \App\Role::where('status', '=', 'active')->pluck('display_name', 'id');

        return array_where($roles, function ($key, $value) {
          if(!checkRole(getUserGrade(1))) {
            if(!($value == 'Admin' || $value =='Owner'))
              return $value;
          }
          else
            return $value;
        });
     }

     /**
      * Store a newly created resource in storage.
      *
      * @return Response
      */
     public function store(Request $request )
     {

        $columns = array(
        // 'name'  => 'bail|required|max:20|',
		'first_name' => 'bail|required|max:50',
        'username' => 'required|unique:users,username|unique:' . WP_TABLE_PREFIX . 'users,user_login',
        'email' => 'required|unique:users,email|unique:' . WP_TABLE_PREFIX . 'users,user_email',
        'image' => 'bail|mimes:png,jpg,jpeg|max:2048',
        'password'=> 'bail|required|min:8',
        'password_confirmation'=>'bail|required|min:8|same:password',
		'status' => 'bail|required',
        );

        if(checkRole(getUserGrade(2)))
          $columns['role_id']  = 'bail|required';



        $this->validate($request,$columns);

        $role_id = getRoleData('student');

        if($request->role_id)
          $role_id = $request->role_id;

        $user           = new User();
        $name           = $request->first_name;
		$user->first_name  = $request->first_name;
		if ( ! empty( $request->last_name ) ) {
			$name .= ' ' . $request->last_name;
			$user->last_name  = $request->last_name;
		}
        $user->name     = $name;
        $user->email    = $request->email;
        $password       = $request->password;
        // $user->password = bcrypt($password);
		$user->password = WpPassword::make( $password );


        if(checkRole(['parent']))
          $user->parent_id = getUserWithSlug()->id;

        $user->role_id        = $role_id;
		if(checkRole(getUserGrade(2))) {
			if( $request->has('current_user_role') ) {
				$user->current_user_role  = $request->current_user_role;
			}
		}
        $user->login_enabled  = 1;
        $slug = $user::makeSlug($name);
        $user->username = $request->username;
        $user->slug           = $slug;
        $user->phone        = $request->phone;
        // $user->address      = $request->address;
		$user->status      = $request->address;
		if ( $request->status == 'activated' ) {
			$user->confirmed = 'yes';
			$user->confirmation_code = NULL;
			$user->status = 'activated';
		} elseif( $request->status == 'registered' ) {
			$user->confirmed = 'no';
			$user->status = 'registered';
		} else {
			$user->confirmed = 'no';
			$user->status = 'registered';
		}
		$user->privacy = $request->privacy;
        $user->save();

		// Let us sync data with WP users table
		$data['main'] = array(
			'user_login' => $request->username,
			'user_pass' => $user->password,
			'user_nicename' => $name,
			'user_email' => $user->email,
			'user_registered' => date('Y-m-d H:i:s'),
			'user_status' => 0,
			'display_name' => $name,
		);
		if ( $request->status == 'activated' ) {
			$data['main']['user_status'] = 1;
		}
		$data['meta'] = array(
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'mobile' => $request->phone,
			'mobile_countrycode' => $request->mobile_countrycode,
			'privacy' => $request->privacy,

			'rich_editing' => 'true',
			'comment_shortcuts' => 'false',
			'admin_color' => 'fresh',
			'use_ssl' => '0',
			'pw_user_status' => 'approved',
		);
		if ( $role_id == OWNER_ROLE_ID || $role_id == ADMIN_ROLE_ID ) {
			$data['meta']['show_admin_bar_front'] = 'false';
			$data['meta']['wp_capabilities'] = 'a:1:{s:13:"administrator";b:1;}';
			$data['meta']['show_admin_bar_front'] = '10';
		} else {
			$data['meta']['show_admin_bar_front'] = 'false';
			$data['meta']['wp_capabilities'] = 'a:1:{s:10:"subscriber";b:1;}';
			$data['meta']['show_admin_bar_front'] = '0';
		}
		save_update_wp( 'users', 'create', $data, array( 'lms_user_id' => $user->id ) );


        if(!env('DEMO_MODE')) {
           $user->roles()->attach($user->role_id);
          $this->processUpload($request, $user);
        }
        $message = getPhrase('record_added_successfully_with_password ').' '.$password;
        $exception = 0;
       try{
        if(!env('DEMO_MODE')) {
       sendEmail('registration', array('user_name'=>$user->name, 'username'=>$user->username, 'to_email' => $user->email, 'password'=>$password));
        }

       //$this->sendPushNotification($user);
     }
     catch(Exception $ex)
     {
        $message = getPhrase('record_added_successfully_with_password ').' '.$password;
        $message .= getPhrase('\ncannot_send_email_to_user, please_check_your_server_settings');
        $exception = 1;
     }

      $flash = app('App\Http\Flash');
      $flash->create('Success...!', $message, 'success', 'flash_overlay',FALSE);


       if(checkRole(['parent']))
        return redirect('dashboard');

       return redirect(URL_USERS);
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

     /**
      * This method sends the message to admin via One Signal
      * @param  string $message [description]
      * @return [type]          [description]
      */
     public function sendOneSignalMessage($new_message='')
     {
        $gcpm = new OneSignalApp();

      $message = array(
             "en" => $new_message,
             "title" => 'New Registration',
             "icon" => "myicon",
             "sound" => "default"
            );
          $data = array(
            "body" => $new_message,
             "title" => "New Registration",
          );

          $gcpm->setDevices(env('ONE_SIGNAL_USER_ID'));
          $response = $gcpm->sendToAll($message,$data);
     }




     protected function processUpload(Request $request, User $user)
     {

       if(env('DEMO_MODE')) {
        return 'demo';
       }

         if ($request->hasFile('image')) {

          $imageObject = new ImageSettings();

          $destinationPath      = base_path( $imageObject->getProfilePicsPath() );
          $destinationPathThumb = base_path( $imageObject->getProfilePicsThumbnailpath() );

          $fileName = $user->id.'.'.$request->image->guessClientExtension();
          ;
          $request->file('image')->move($destinationPath, $fileName);
          $user->image = $fileName;

          Image::make(file_get_contents( $destinationPath.$fileName))->fit($imageObject->getProfilePicSize())->save($destinationPath.$fileName );

          Image::make(file_get_contents( $destinationPath.$fileName ))->fit($imageObject->getProfileThumbnailSize())->save($destinationPathThumb.$fileName);


          $user->save();

		  return $fileName;
        }
     }

    public function isValidRecord($record)
    {
      if ($record === null) {
        flash('Ooops...!', getPhrase("page_not_found"), 'error');
        return $this->getRedirectUrl();
    }

    return FALSE;
    }

    public function getReturnUrl()
    {
      return URL_USERS;
    }

     /**
      * Display the specified resource.
      *
      *@param  unique string  $slug
      * @return Response
      */
     public function show($slug)
     {
        //
     }



     /**
      * Show the form for editing the specified resource.
      *
      * @param  unique string  $slug
      * @return Response
      */
     public function edit($slug, $operation = '')
     {
        $record = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();


        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */

     $UserOwnAccount = FALSE;
     if(\Auth::user()->id == $record->id)
      $UserOwnAccount = TRUE;

      if(!$UserOwnAccount)  {
        $current_user_role = getRoleData($record->role_id);

        if((($current_user_role=='admin' || $current_user_role == 'owner') ))
        {
          if(!checkRole(getUserGrade(1))) {
            prepareBlockUserMessage();
            return back();
          }
        }
      }

        $data['record']             = $record;
        // dd('hrere');
        // $data['roles']              = $this->getUserRoles();

         $roles                = \App\Role::select('display_name', 'id','name')->get();
        $final_roles = [];
        foreach($roles as $role)
        {

           if(!checkRole(getUserGrade(1))) {

            if(!(strtolower($role->name) == 'admin' || strtolower($role->name) =='owner'))
              $final_roles[$role->id] = $role->display_name;
          }
          else
           $final_roles[$role->id] = $role->display_name;
        }
        $data['roles']        = $final_roles;


        if($UserOwnAccount && checkRole(['admin']))
          $data['roles'][getRoleData('admin')] = 'Admin';

        $data['active_class']       = 'users';
        $data['title']              = getPhrase('edit_user');
        $data['layout']             = getLayout();
		$data['operation'] = $operation;
        // dd($data);
        return view('users.add-edit-user', $data);
     }

	 public function loadUsers(Request $request)
	 {
		 $users = User::get();
        return response(array(
                'error' => false,
                'products' =>$users->toArray(),
               ),200);
	 }


     /**
      * Update the specified resource in storage.
      *
      * @param  int  $id
      * @return Response
      */
     public function update(Request $request, $slug, $operation = '')
     {
        $record     = User::where('slug', $slug)->get()->first();
		if ( empty( $record ) ) {
			return back();
		}
        $validation = [
        'first_name'      => 'bail|required|max:20|',
        'email'     => 'bail|required|unique:users,email,'.$record->id,
        'image'     => 'bail|mimes:png,jpg,jpeg|max:2048',
        ];

        if(!isEligible($slug))
          return back();

        if(checkRole(getUserGrade(2))) {
          $validation['role_id'] = 'bail|required|integer';
		  $validation['status'] = 'bail|required';
		  if ( $request->password != '' ) {
			  $validation['password'] = 'bail|required|min:8';
			  $validation['password_confirmation'] = 'bail|required|min:8|same:password';
		  }
		}


        $this->validate($request, $validation);

        $name = $request->first_name;
		if ( ! empty( $request->last_name ) ) {
			$name .= ' ' . $request->last_name;
		}
        $previous_role_id = $record->role_id;
         if($name != $record->name)
            $record->slug = $record::makeSlug($name);

        $record->name = $name;

		$record->first_name = $request->first_name;
		$record->last_name = $request->last_name;

        $record->email = $request->email;

        if(checkRole(getUserGrade(2))) {
			$record->role_id  = $request->role_id;
			if( $request->has('current_user_role') ) {
				if ( 'subscriber' === $request->current_user_role ) {
					// If this user demoting to subscriber now. Means user may have assigned as coach to other users!! May be!!!. Let us remove him from all users profiles.
					$check = DB::table('users')->where('coach_id', '=', $record->id);
					if ( $check->count() > 0 ) {
						DB::table('users')
						->where('coach_id', '=', $record->id)
						->update( array(
							'coach_id' => 0,
						) );
					}
				}
				$record->current_user_role  = $request->current_user_role;
			}
			if( $request->has('current_user_level') ) {
				$record->current_user_level  = $request->current_user_level;
			}
			if( $request->has('coach_id') ) {
				$record->coach_id  = $request->coach_id;
				DB::table('coach_requests')->where('user_id', '=', $record->id)->delete();
				
			}
			$record->status = $request->status;
			if ( $request->status == 'activated' ) {
				$record->confirmed = 'yes';
				$record->confirmation_code = NULL;
			} else {
				$record->confirmed = 'no';
			}
		}
		$record->phone = $request->phone;
		$record->phone_code = $request->mobile_countrycode;
		$record->address = $request->address;
		$record->privacy = $request->privacy;
		
		if ( $request->password != '' ) {
			// Change Password
			$password    = $request->password;
			// $record->password = bcrypt($password);
			$record->password = WpPassword::make( $password );
		}

		$record->save();

		// Let us sync data with WP users table
		$data['main'] = array(
			'user_nicename' => $name,
			'display_name' => $name,
		);
		if ( $request->status == 'activated' ) {
			$data['main']['user_status'] = 1;
		}
		if ( $request->password != '' ) {
			$data['main']['user_pass'] = WpPassword::make( $request->password );
		}
		$data['meta'] = array(
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'mobile' => $request->phone,
			'mobile_countrycode' => $request->mobile_countrycode,
			'privacy' => $request->privacy,

			'rich_editing' => 'true',
			'comment_shortcuts' => 'false',
			'admin_color' => 'fresh',
			'use_ssl' => '0',
			'pw_user_status' => 'approved',
		);
		$role_id = $record->role_id;
		if ( $role_id == OWNER_ROLE_ID || $role_id == ADMIN_ROLE_ID ) {
			$data['meta']['show_admin_bar_front'] = 'false';
			$data['meta']['wp_capabilities'] = 'a:1:{s:13:"administrator";b:1;}';
			$data['meta']['show_admin_bar_front'] = '10';
		} else {
			$data['meta']['show_admin_bar_front'] = 'false';
			$data['meta']['wp_capabilities'] = 'a:1:{s:10:"subscriber";b:1;}';
			$data['meta']['show_admin_bar_front'] = '0';
		}

		save_update_wp( 'users', 'update', $data, array( 'key' => 'ID', 'value' => $record->wp_user_id ) );


        if(checkRole(getUserGrade(2)))
        {
          /**
           * Delete the Roles associated with that user
           * Add the new set of roles
           */

         if(!env('DEMO_MODE')) {
          DB::table('role_user')
          ->where('user_id', '=', $record->id)
          ->where('role_id', '=', $previous_role_id)
          ->delete();

         $record->roles()->attach($request->role_id);
       }
        }
        if(!env('DEMO_MODE')) {
          $this->processUpload($request, $record);
        }
        flash('success','record_updated_successfully', 'success');
        // return redirect('users/edit/'.$record->slug);
        if(checkRole(getUserGrade(2))) {
			if ( 'assigncoach' === $operation ) {				
				return redirect(URL_USERS_COACH_REQUESTS);
			} else {
				return redirect(URL_USERS);
			}
			
		}        
		return redirect(URL_USERS_EDIT.$record->slug);
      }



     /**
      * Remove the specified resource from storage.
      *
      * @param  unique string  $slug
      * @return Response
      */
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
         if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = User::where('slug', $slug)->first();

        /**
         * Check if any exams exists with this category,
         * If exists we cannot delete the record
         */
           if(!env('DEMO_MODE')) {
				$imageObject = new ImageSettings();

				$destinationPath      = $imageObject->getProfilePicsPath();
				$destinationPathThumb = $imageObject->getProfilePicsThumbnailpath();

				$this->deleteFile($record->image, $destinationPath);
				$this->deleteFile($record->image, $destinationPathThumb);
				$record->delete();

				$username = $record->username;
				// Let us delete WP User details
				$wp_user = \Corcel\Model\User::where( 'user_login', '=',$username  )->first();
				if ( $wp_user ) {
					$wp_user_id = $wp_user->ID;
					\Corcel\Model\Post::where( 'post_author', '=', $wp_user_id )->delete();
					$wp_user->delete();
				}
          }
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
            return json_encode($response);

    }

    public function deleteFile($record, $path, $is_array = FALSE)
    {
       if(env('DEMO_MODE')) {
        return ;
       }

        $files = array();
        $files[] = $path.$record;
        File::delete($files);
    }



    public function listUsers($role_name)
    {
      $role = App\Role::getRoleId($role_name);

      $users = User::where('role_id', '=', $role->id)->get();

      $users_list =  array();

      foreach ($users as $key => $value) {
        $r = array('id'=>$value->id, 'text' => $value->name, 'image' => $value->image);
            array_push($users_list, $r);
      }
      return json_encode($users_list);
    }

    public function details($slug)
    {
        $record     = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();

        $data['record']      = $record;



         $user = $record;
            //Overall performance Report
            $resultObject = new App\QuizResult();
            $records = $resultObject->getOverallSubjectsReport($user);
            $color_correct          = getColor('background', rand(0,999));
            $color_wrong            = getColor('background', rand(0,999));
            $color_not_attempted    = getColor('background', rand(0,999));
            $correct_answers        = 0;
            $wrong_answers          = 0;
            $not_answered           = 0;

            foreach($records as $record) {
                $record = (object)$record;
                $correct_answers    += $record->correct_answers;
                $wrong_answers      += $record->wrong_answers;
                $not_answered       += $record->not_answered;

           }

            $labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];
            $dataset = [$correct_answers, $wrong_answers, $not_answered];
            $dataset_label[] = 'lbl';
            $bgcolor  = [$color_correct,$color_wrong,$color_not_attempted];
            $border_color = [$color_correct,$color_wrong,$color_not_attempted];
            $chart_data['type'] = 'pie';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getphrase('overall_performance');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

            //Best scores in each quizzes
            $records = $resultObject->getOverallQuizPerformance($user);
            $labels = [];
            $dataset = [];
            $bgcolor = [];
            $bordercolor = [];

            foreach($records as $record) {
                $color_number = rand(0,999);
                $record = (object)$record;
                $labels[] = $record->title;
                $dataset[] = $record->percentage;
                $bgcolor[] = getColor('background',$color_number);
                $bordercolor[] = getColor('border', $color_number);
           }

            $labels = $labels;
            $dataset = $dataset;
            $dataset_label = getPhrase('performance');
            $bgcolor  = $bgcolor;
            $border_color = $bordercolor;
            $chart_data['type'] = 'bar';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getPhrase('best_performance_in_all_quizzes');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

        $data['ids'] = array('myChart0', 'myChart1');
        $data['title']        = getPhrase('user_details');
        $data['layout']        = getLayout();
         $data['active_class'] = 'users';
        if(checkRole(['parent']))
          $data['active_class'] = 'children';
        // $data['right_bar']          = TRUE;

      $data['right_bar_path']     = 'student.exams.right-bar-performance-chart';
      $data['right_bar_data']     = array('chart_data' => $data['chart_data']);

        return view('users.user-details', $data);

    }
	
	public function detailsCoach( $slug )
    {
        $record  = User::select(['users.*', 'coach.name AS coach_name'])
		->join( 'users AS coach', 'coach.id', '=', 'users.coach_id')
		->where('users.slug', $slug)
		->where('coach.id', Auth::User()->id)
		->get()
		->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */
        $data['record']      = $record;



         $user = $record;
            //Overall performance Report
            $resultObject = new App\QuizResult();
            $records = $resultObject->getOverallSubjectsReport($user);
            $color_correct          = getColor('background', rand(0,999));
            $color_wrong            = getColor('background', rand(0,999));
            $color_not_attempted    = getColor('background', rand(0,999));
            $correct_answers        = 0;
            $wrong_answers          = 0;
            $not_answered           = 0;

            foreach($records as $record) {
                $record = (object)$record;
                $correct_answers    += $record->correct_answers;
                $wrong_answers      += $record->wrong_answers;
                $not_answered       += $record->not_answered;

           }

            $labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];
            $dataset = [$correct_answers, $wrong_answers, $not_answered];
            $dataset_label[] = 'lbl';
            $bgcolor  = [$color_correct,$color_wrong,$color_not_attempted];
            $border_color = [$color_correct,$color_wrong,$color_not_attempted];
            $chart_data['type'] = 'pie';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getphrase('overall_performance');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

            //Best scores in each quizzes
            $records = $resultObject->getOverallQuizPerformance($user);
            $labels = [];
            $dataset = [];
            $bgcolor = [];
            $bordercolor = [];

            foreach($records as $record) {
                $color_number = rand(0,999);
                $record = (object)$record;
                $labels[] = $record->title;
                $dataset[] = $record->percentage;
                $bgcolor[] = getColor('background',$color_number);
                $bordercolor[] = getColor('border', $color_number);
           }

            $labels = $labels;
            $dataset = $dataset;
            $dataset_label = getPhrase('performance');
            $bgcolor  = $bgcolor;
            $border_color = $bordercolor;
            $chart_data['type'] = 'bar';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getPhrase('best_performance_in_all_quizzes');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

        $data['ids'] = array('myChart0', 'myChart1');
        $data['title']        = getPhrase('user_details');
        $data['layout']        = getLayout();
         $data['active_class'] = 'users';
        if(checkRole(['parent']))
          $data['active_class'] = 'children';
        // $data['right_bar']          = TRUE;

      $data['right_bar_path']     = 'student.exams.right-bar-performance-chart';
      $data['right_bar_data']     = array('chart_data' => $data['chart_data']);
      return view('users.user-details-coach', $data);
    }
	
	public function detailsFacilitator( $slug )
    {
        $record  = User::select(['coach.*', 'coach.name AS coach_name'])
		->join( 'users AS coach', 'coach.id', '=', 'users.coach_id')
		->where('coach.slug', $slug)
		->where('users.id', Auth::User()->id)
		->get()
		->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */
        $data['record']      = $record;



         $user = $record;
            //Overall performance Report
            $resultObject = new App\QuizResult();
            $records = $resultObject->getOverallSubjectsReport($user);
            $color_correct          = getColor('background', rand(0,999));
            $color_wrong            = getColor('background', rand(0,999));
            $color_not_attempted    = getColor('background', rand(0,999));
            $correct_answers        = 0;
            $wrong_answers          = 0;
            $not_answered           = 0;

            foreach($records as $record) {
                $record = (object)$record;
                $correct_answers    += $record->correct_answers;
                $wrong_answers      += $record->wrong_answers;
                $not_answered       += $record->not_answered;

           }

            $labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];
            $dataset = [$correct_answers, $wrong_answers, $not_answered];
            $dataset_label[] = 'lbl';
            $bgcolor  = [$color_correct,$color_wrong,$color_not_attempted];
            $border_color = [$color_correct,$color_wrong,$color_not_attempted];
            $chart_data['type'] = 'pie';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getphrase('overall_performance');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

            //Best scores in each quizzes
            $records = $resultObject->getOverallQuizPerformance($user);
            $labels = [];
            $dataset = [];
            $bgcolor = [];
            $bordercolor = [];

            foreach($records as $record) {
                $color_number = rand(0,999);
                $record = (object)$record;
                $labels[] = $record->title;
                $dataset[] = $record->percentage;
                $bgcolor[] = getColor('background',$color_number);
                $bordercolor[] = getColor('border', $color_number);
           }

            $labels = $labels;
            $dataset = $dataset;
            $dataset_label = getPhrase('performance');
            $bgcolor  = $bgcolor;
            $border_color = $bordercolor;
            $chart_data['type'] = 'bar';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getPhrase('best_performance_in_all_quizzes');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

        $data['ids'] = array('myChart0', 'myChart1');
        $data['title']        = getPhrase('coach_details');
        $data['layout']        = getLayout();
         $data['active_class'] = 'users';
        if(checkRole(['parent']))
          $data['active_class'] = 'children';
        // $data['right_bar']          = TRUE;

      $data['right_bar_path']     = 'student.exams.right-bar-performance-chart';
      $data['right_bar_data']     = array('chart_data' => $data['chart_data']);
      return view('users.user-details-facilitator', $data);
    }

    /**
     * This method will show the page for change password for user
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function changePassword($slug)
    {

       $record = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();

        $data['record']             = $record;
        $data['active_class']       = 'profile';
        $data['title']              = getPhrase('change_password');
        $data['layout']             = getLayout();
        return view('users.change-password.change-view', $data);
    }

    /**
     * This method updates the password submitted by the user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
     public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|min:8',
            'password'=> 'required|min:8',
			'password_confirmation'=>'required|min:8|same:password',
        ]);

        $user = \Auth::user();
		if ( $request->old_password == $request->password ) {
			flash('Oops..!','old_password and new password should not be same', 'error');
            return redirect()->back();
		}

        // if (Hash::check($credentials['old_password'], $user->password)){
		if ( WpPassword::check($request->old_password, $user->password) ) {
            // $user->password = bcrypt($credentials['password']);
			$user->password = WpPassword::make( $request->password );
            $user->save();
			
			$wp_user = DB::table( WP_TABLE_PREFIX . 'users' )->where( 'user_login', '=', $user->username )->first();
			
			if ( $wp_user ) {
				$update = array(
					'user_pass' => WpPassword::make( $request->password ),
				);
				DB::table( WP_TABLE_PREFIX . 'users' )->where( 'user_login', '=', $user->username )->update( $update );
			}
			
            flash('success','password_updated_successfully', 'success');
            return redirect(URL_USERS_CHANGE_PASSWORD.$user->slug);

        }else {
			flash('Oops..!','old_password is wrong', 'error');
            return redirect()->back();
       }
  }

  /**
    * Display a Import Users page
    *
    * @return Response
    */
     public function importUsers($role = 'student')
     {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['active_class'] = 'users';
        $data['heading']      = getPhrase('users');
        $data['title']        = getPhrase('import_users');
        $data['layout']        = getLayout();
        return view('users.import.import', $data);
     }

     public function readExcel(Request $request)
     {

        $columns = array(
        'excel'  => 'bail|required',
        );

        $this->validate($request,$columns);

       if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }
       $success_list = [];
       $failed_list = [];

       try{
        if(Input::hasFile('excel')){
          $path = Input::file('excel')->getRealPath();
          $data = Excel::load($path, function($reader) {
          })->get();

          $user_record = array();
          $users =array();
          $isHavingDuplicate = 0;
          if(!empty($data) && $data->count()){

            foreach ($data as $key => $value) {

              foreach($value as $record)
              {
                unset($user_record);

                $user_record['first_name'] = $record->first_name;
				$user_record['last_name'] = $record->last_name;

				$user_record['username'] = $record->username;
                $user_record['name'] = $record->name;
                $user_record['email'] = $record->email;

                $user_record['password'] = $record->password;
                $user_record['phone'] = $record->phone;
				if ( ! empty($record->current_user_level) && in_array($record->current_user_level, array('subscriber', 'Servant Learner', 'Servant','Servant Leader') ) ) {
					$user_record['current_user_level'] = $record->current_user_level;
				} else {
					$user_record['current_user_level'] = 'subscriber';
				}
				if ( ! empty($record->current_user_level) && in_array($record->current_user_level, array('subscriber', 'facilitator', 'coach' ) ) ) {
					$user_record['current_user_role'] = $record->current_user_role;
				} else {
					$user_record['current_user_role'] = 'subscriber';
				}
				

				/**
				 * Admin importing users means they are trusted users and not need to confirm the account.
				 */
				$user_record['confirmed'] = 'yes';
				$user_record['status'] = 'activated';

                $user_record['address'] = $record->address;
                $user_record['role_id'] = STUDENT_ROLE_ID;

                $user_record = (object)$user_record;
                $failed_length = count($failed_list);
                if($this->isRecordExists($record->username, 'username'))
                {

                  $isHavingDuplicate = 1;
                  $temp = array();
                 $temp['record'] = $user_record;
                 $temp['type'] ='Record already exists with this name';
                 $failed_list[$failed_length] = (object)$temp;
                  continue;
                }

                if($this->isRecordExists($record->email, 'email'))
                {
                  $isHavingDuplicate = 1;
                  $temp = array();
                 $temp['record'] = $user_record;
                 $temp['type'] ='Record already exists with this email';
                 $failed_list[$failed_length] = (object)$temp;
                  continue;
                }

                $users[] = $user_record;

              }

            }
              if($this->addUser($users))
                  $success_list = $users;
          }
        }



       $this->excel_data['failed'] = $failed_list;
       $this->excel_data['success'] = $success_list;

       flash('success','record_added_successfully', 'success');
       $this->downloadExcel();

     }
     catch( \Illuminate\Database\QueryException $e)
     {
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_sheet_uploaded', 'error');
       }
     }

        // URL_USERS_IMPORT_REPORT
       $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
       $data['records']      = FALSE;
       $data['layout']      = getLayout();
       $data['active_class'] = 'users';
       $data['heading']      = getPhrase('users');
       $data['title']        = getPhrase('report');

       return view('users.import.import-result', $data);

     }

public function getFailedData()
{
  return $this->excel_data;
}

public function downloadExcel()
{
    Excel::create('users_report', function($excel) {
      $excel->sheet('Failed', function($sheet) {
      $sheet->row(1, array('Reason','Name', 'Username','Email','Password','Phone','Address', 'User Level', 'User Role'));
      $data = $this->getFailedData();
      $cnt = 2;
      // dd($data['failed']);
      foreach ($data['failed'] as $data_item) {
        $item = $data_item->record;
        $sheet->appendRow($cnt++, array($data_item->type, $item->name, $item->username, $item->email, $item->password, $item->phone, $item->address, $item->current_user_level, $item->current_user_role));
      }
    });

    $excel->sheet('Success', function($sheet) {
      $sheet->row(1, array('Name', 'Username','Email','Password','Phone','Address', 'User Level', 'User Role'));
      $data = $this->getFailedData();
      $cnt = 2;
      foreach ($data['success'] as $data_item) {
        $item = $data_item;
        $sheet->appendRow($cnt++, array($item->name, $item->username, $item->email, $item->password, $item->phone, $item->address, $item->current_user_level, $item->current_user_role));
      }

    });

    })->download('xlsx');

    return TRUE;
}
     /**
      * This method verifies if the record exists with the email or user name
      * If Exists it returns true else it returns false
      * @param  [type]  $value [description]
      * @param  string  $type  [description]
      * @return boolean        [description]
      */
     public function isRecordExists($record_value, $type='email')
     {
        return User::where($type,'=',$record_value)->get()->count();
     }

     public function addUser($users)
     {
      foreach($users as $request) {
        $user           = new User();
        $name           = $request->name;
        $user->name     = $name;
        $user->email    = $request->email;
        $user->username    = $request->username;
        // $user->password = bcrypt($request->password);
		$user->password = WpPassword::make( $request->password );

        $user->role_id        = $request->role_id;
        $user->login_enabled  = 1;
        $user->slug           = $user::makeSlug($name);
        $user->phone        = $request->phone;
        $user->address      = $request->address;
		
		$user->current_user_level      = $request->current_user_level;
		$user->current_user_role      = $request->current_user_role;

        $user->save();

        $user->roles()->attach($user->role_id);
      }
       return true;
     }

  /**
   * This method shows the user preferences based on provided user slug and settings available in table.
   * @param  [type] $slug [description]
   * @return [type]       [description]
   */
  public function settings($slug)
  {
       $record = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();


        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */

     $UserOwnAccount = FALSE;
     if(\Auth::user()->id == $record->id)
      $UserOwnAccount = TRUE;

      if(!$UserOwnAccount)  {
        $current_user_role = getRoleData($record->role_id);

        if((($current_user_role=='admin' || $current_user_role == 'owner') ))
        {
          if(!checkRole(getUserGrade(1))) {
            prepareBlockUserMessage();
            return back();
          }
        }
      }

       $data['record']       = $record;
       $data['quiz_categories']   = App\QuizCategory::get();
	   $data['subjects']   = App\Subject::get();

       // $data['lms_category'] = App\LmsCategory::get();
	   $data['lms_category'] = App\QuizCategory::get();

       // dd($data);
       $data['layout']       = getLayout();
       $data['active_class'] = 'users';
       $data['heading']      = getPhrase('account_settings');
       $data['title']        = getPhrase('account_settings');
      // flash('success','record_added_successfully', 'success');
       return view('users.account-settings', $data);
}

  /**
   * This method updates the user preferences based on the provided categories
   * All these settings will be stored under Users table settings field as json format
   * @param  Request $request [description]
   * @param  [type]  $slug    [description]
   * @return [type]           [description]
   */
  public function updateSettings(Request $request, $slug)
  {
        $record = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();


        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */

     $UserOwnAccount = FALSE;
     if(\Auth::user()->id == $record->id)
      $UserOwnAccount = TRUE;

      if(!$UserOwnAccount)  {
        $current_user_role = getRoleData($record->role_id);

        if((($current_user_role=='admin' || $current_user_role == 'owner') ))
        {
          if(!checkRole(getUserGrade(1))) {
            prepareBlockUserMessage();
            return back();
          }
        }
      }

    $options = [];
    if($record->settings)
    {
      $options =(array) json_decode($record->settings)->user_preferences;

    }

    $options['quiz_categories'] = [];
    $options['lms_categories']  = [];
	$options['subjects']  = [];
    if($request->has('quiz_categories')) {
    foreach($request->quiz_categories as $key => $value)
      $options['quiz_categories'][] = $key;
    }
    if($request->has('lms_categories')) {
      foreach($request->lms_categories as $key => $value)
        $options['lms_categories'][] = $key;
    }
	if($request->has('subjects')) {
      foreach($request->subjects as $key => $value)
        $options['subjects'][] = $key;
    }

    $record->settings = json_encode(array('user_preferences'=>$options));

    $record->save();

    flash('success','record_updated_successfully', 'success');
     return back();
  }


  public function viewParentDetails($slug)
  {
     if(!checkRole(getUserGrade(4)))
        {
          prepareBlockUserMessage();
          return back();
        }

       $record = User::where('slug', '=', $slug)->first();

       if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

       $data['layout']       = getLayout();
       $data['active_class'] = 'users';
       $data['record']       = $record;

       $data['heading']      = getPhrase('parent_details');
       $data['title']        = getPhrase('parent_details');
       return view('users.parent-details', $data);
  }

  public function updateParentDetails(Request $request, $slug)
  {

     if(!checkRole(getUserGrade(4)))
        {
          prepareBlockUserMessage();
          return back();
        }


    $user                   = User::where('slug', '=', $slug)->first();
        $role_id = getRoleData('parent');
        $message = '';
        $hasError = 0;

        DB::beginTransaction();
        if($request->account == 0)
        {
            //User is not having an account, create it and send email
            //Update the newly created user ID to the current user parent record
            $parent_user = new User();
            $parent_user->name = $request->parent_name;
            $parent_user->username = $request->parent_user_name;
            $parent_user->role_id = $role_id;
            $parent_user->slug = $parent_user::makeSlug($request->parent_user_name);
            $parent_user->email = $request->parent_email;
            // $parent_user->password = bcrypt('password');
			$parent_user->password = WpPassword::make( 'password' );

        try{
            $parent_user->save();
            $parent_user->roles()->attach($role_id);
            $user->parent_id = $parent_user->id;
            $user->save();

            sendEmail('registration', array('user_name'=>$user->name, 'username'=>$user->username, 'to_email' => $user->email, 'password'=>$parent_user->password));

            DB::commit();
            $message = 'record_updated_successfully';
        }
        catch(Exception $ex){
            DB::rollBack();
            $hasError = 1;
            $message = $ex->getMessage();
        }
    }
        if($request->account == 1)
        {
            try{
             $user->parent_id =  $request->parent_user_id;
             $user->save();
             DB::commit();
            }
            catch(Exception $ex)
            {
                $hasError = 1;
                DB::rollBack();
                $message = $ex->getMessage();
            }
        }
        if(!$hasError)
            flash('success',$message, 'success');
        else
            flash('Ooops',$message, 'error');
        return back();
  }


  public function getParentsOnSearch(Request $request)
  {
        $term = $request->search_text;
        $role_id = getRoleData('parent');
        $records = App\User::
            where('name','LIKE', '%'.$term.'%')
            ->orWhere('username', 'LIKE', '%'.$term.'%')
            ->orWhere('phone', 'LIKE', '%'.$term.'%')
            ->groupBy('id')
            ->havingRaw('role_id='.$role_id)
            ->select(['id','role_id','name', 'username', 'email', 'phone'])
            ->get();
            return json_encode($records);
  }
  
  public function sendMessage( $from, $to, $subject, $message )
  {
	$thread = Thread::create([ 'subject' => $subject,]);
	  
	// Message
	Message::create(
		[
			'thread_id' => $thread->id,
			'user_id'   => $from,
			'body'      => $message,
		]
	);
	
	// Sender
	Participant::create(
		[
			'thread_id' => $thread->id,
			'user_id'   => $from,
			'last_read' => new Carbon,
		]
	);
	
	// Recipients
	$recipients = array( $to );
	$thread->addParticipant($recipients);
  }

  public function changeStatus( Request $request )
  {
	  if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

	  $user = App\User::where( 'slug', '=', $request->slug )->first();
	  if ( $user )
	  {
		  $status = $request->status;
		  if ( 'coach_approve' === $status ) {
			  $user->current_user_role = 'coach';
			  $this->sendMessage( Auth::User()->id, $user->id, getPhrase('Congratulations! Now you are COACH'), getPhrase( sprintf( 'Congratulations! Now you are COACH. You are a leader of number of facilitators. Click <a href="%s">here</a> to see your facilitators.', URL_MY_FACILITATORS ) ) );
			  $details = DB::table('coach_requests')->where('user_id', '=', $user->id)->first();
			  if ( $details ) {
				  $record = array(
					'status' => 'accepted',
				  );
				  DB::table('coach_requests')->where('user_id', '=', $user->id)->update( $record );
			  }
		  } elseif ( 'coach_reject' === $status ) {
			  
			  $this->sendMessage( Auth::User()->id, $user->id, getPhrase('Sorry Your request rejected'), getPhrase( 'Sorry Your request rejected. Complete all criteria to become coach and you can request again.' ) );
			  
			  $details = DB::table('coach_requests')->where('user_id', '=', $user->id)->first();
			  if ( $details ) {
				  $record = array(
					'status' => 'rejected',
				  );
				  DB::table('coach_requests')->where('user_id', '=', $user->id)->update( $record );
			  }
		  } elseif ( 'coach_delete' === $status ) {
			  $details = DB::table('coach_requests')->where('user_id', '=', $user->id)->first();
			  if ( $details ) {
				  $record = array(
					'status' => 'deleted',
				  );
				  DB::table('coach_requests')->where('user_id', '=', $user->id)->update( $record );
			  }
		  } elseif ( $status == 'activated' ) {
			$user->status = 'activated';
			$user->forgot_token = null;
			$user->confirmed = 'yes';
			$user->confirmation_code = null;
		  } else {
			 $user->status = 'suspended';
		  }
		  $user->save();

		  $response['status'] = 1;
            $response['message'] = getPhrase('record_updated_successfully');
            return json_encode($response);
	  } else {
		  $response['status'] = 0;
            $response['message'] = getPhrase('wrong_operation');
            return json_encode($response);
	  }
  }

  /**
      * Show the form for editing the specified resource.
      *
      * @param  unique string  $slug
      * @return Response
      */
     public function getSendEmail( $slug )
     {
        $record = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();


        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */

     $UserOwnAccount = FALSE;
     if(\Auth::user()->id == $record->id)
      $UserOwnAccount = TRUE;

      if(!$UserOwnAccount)  {
        $current_user_role = getRoleData($record->role_id);

        if((($current_user_role=='admin' || $current_user_role == 'owner') ))
        {
          if(!checkRole(getUserGrade(1))) {
            prepareBlockUserMessage();
            return back();
          }
        }
      }

        $data['record']             = $record;
        // dd('hrere');
        // $data['roles']              = $this->getUserRoles();

         $roles                = \App\Role::select('display_name', 'id','name')->get();
        $final_roles = [];
        foreach($roles as $role)
        {

           if(!checkRole(getUserGrade(1))) {

            if(!(strtolower($role->name) == 'admin' || strtolower($role->name) =='owner'))
              $final_roles[$role->id] = $role->display_name;
          }
          else
           $final_roles[$role->id] = $role->display_name;
        }
        $data['roles']        = $final_roles;


        if($UserOwnAccount && checkRole(['admin']))
          $data['roles'][getRoleData('admin')] = 'Admin';

        $data['active_class']       = 'users';
        $data['title']              = getPhrase('send_email');
        $data['layout']             = getLayout();
        // dd($data);
        return view('users.send-email', $data);
     }

	 public function sendEmail( Request $request )
	{
		$rules = [
         'recipients'          	   => 'bail|required',
		 'message'          	   => 'bail|required',
         ];
        $this->validate($request, $rules);
		$emails = $request->recipients;
		if ( ! empty( $emails ) ) {
			$emails = explode(',', $emails);
			if ( ! empty( $emails ) ) {
				foreach( $emails as $email )
				{
					if (!filter_var(trim( $email ), FILTER_VALIDATE_EMAIL) === false) {
						// Let us send and notification email to user set in profile email addresses
						sendEmail('user_email', array( 'to_email' => $email, 'message' => $request->message, ));
					}
				}
				flash('Success...!', 'Email sent successfully', 'success');
				return redirect(URL_USERS);
			} else {
				flash('Ooops...!', 'No Emails found to send invitation', 'error');
			return redirect(URL_USERS);
			}
		} else {
			flash('Ooops...!', 'No Emails found to send invitation', 'error');
			return redirect(URL_USERS);
		}
	}

	/**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function translationRequests($type = 'translation')
     {
        if( ! checkRole(getUserGrade(2) ) )
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['layout']      = getLayout();
        $data['active_class'] = 'translation';
        $data['heading']      = getPhrase('translation_requests');
        $data['title']        = getPhrase('translation_requests');
        return view('users.translation-requests.translation-requests', $data);
     }

	 /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getTranslationRequests()
    {
        $records = array();


		$records = DB::table('translation_siteissues')->where('type', '=', 'translation')->orderBy('created_at', 'desc');


        return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="'.URL_TRANSLATION_REQUEST_VIEW.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("view").'</a></li>';

							$temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

                        $temp .='</ul> </div>';
                        $link_data .= $temp;
            return $link_data;
            })
         ->editColumn('full_name', function($records) {
            if ( $records->user_id > 0 ) {
				$user = User::where( 'id', '=', $records->user_id )->first();
				if ( $user ) {
					return '<a href="'.URL_USER_DETAILS.$user->slug.'">'.$records->full_name.'</a>';
				} else {
					return $records->full_name;
				}
			} else {
				return $records->full_name;
			}
        })
		->editColumn('url', function($records){
            if ( $records->content_id > 0 ) {
				if ( $records->conten_type == 'post' ) {
					$lesson = \Corcel\Model\Post::find( $records->content_id );
				} else {
					$lesson = App\LmsContent::where('id', '=', $records->content_id )->first();
				}
				if ( $lesson ) {
					if ( $records->conten_type == 'post' ) {
						return '<a href="' . HOST . 'wp-admin/post.php?post=' . $lesson->ID . '&action=edit" target="_blank">' . $lesson->post_title . '</a>';
					} else {
						return '<a href="' . URL_LMS_CONTENT_EDIT . $lesson->slug . '">' . $lesson->title . '</a>';
					}
				} else {
					return '<a href="'.$records->url.'" target="_blank">'. getPhrase('view') . '</a>';
				}
			} else {
				return '<a href="'.$records->url.'" target="_blank">'. getPhrase('view') . '</a>';
			}
        })
        ->editColumn('description', function($records){
            return $records->description;
        })
		->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('content_id')
        ->removeColumn('conten_type')
        ->removeColumn('user_id')
        ->removeColumn('user_agent')
		->removeColumn( 'ip_address' )
		// ->removeColumn( 'created_at' )
		->removeColumn( 'wp_user_id' )
		->removeColumn( 'updated_at' )
        ->removeColumn( 'type' )
        ->make();
    }

	public function deleteTranslationRequests( $slug )
	{
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}
		/**
		* Check if any exams exists with this category,
		* If exists we cannot delete the record
		*/
		if(!env('DEMO_MODE')) {
			DB::table('translation_siteissues')->where('slug', $slug)->delete();
		}
		$response['status'] = 1;
		$response['message'] = getPhrase('record_deleted_successfully');
		return json_encode($response);
	}

	public function viewTranslationRequest( $slug )
	{
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}

		$record = DB::table('translation_siteissues')->where('slug', $slug)->first();
		if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

		$data['record']             = $record;
		$data['active_class']       = 'translation';
        $data['title']              = getPhrase('view_translation_request');
        $data['layout']             = getLayout();
        return view('users.translation-requests.view-translation-request', $data);
	}

	public function processTranslationRequest( Request $request, $slug )
	{
		$record = DB::table('translation_siteissues')->where('slug', $slug)->first();
		if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

		$rules = [
         'recipients'          	   => 'bail|required',
		 'message'          	   => 'bail|required',
         ];
        $this->validate($request, $rules);
		if ( ! empty( $request->sendemail ) ) {
			$emails = $request->recipients;
			if ( ! empty( $emails ) ) {
				$emails = explode(',', $emails);
				if ( ! empty( $emails ) ) {
					foreach( $emails as $email )
					{
						if (!filter_var(trim( $email ), FILTER_VALIDATE_EMAIL) === false) {
							// Let us send and notification email to user set in profile email addresses
							sendEmail('user_email', array( 'to_email' => $email, 'message' => $request->message, ));
						}
					}
					flash('Success...!', 'Email sent successfully', 'success');
					return redirect(URL_TRANSLATION_REQUESTS);
				} else {
					flash('Ooops...!', 'No Emails found to send invitation', 'error');
					return redirect(URL_TRANSLATION_REQUESTS);
				}
			}
		} elseif ( $record->user_id > 0 ) {
			$subject = getPhrase( 'translation_request:' . $record->url );
			if ( $record->content_id > 0 ) {
				$lesson = App\LmsContent::where('id', '=', $record->content_id )->first();
				if ( $lesson ) {
					$subject = getPhrase( 'translation_request:' . $lesson->title );
				}
			}
			$thread = Thread::create(
				[
					'subject' => $subject,
				]
			);

			// Message
			Message::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id,
					'body'      => $request->message,
				]
			);

			// Sender
			Participant::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id,
					'last_read' => new Carbon,
				]
			);
			// Recipients
			$recipients = array( $record->user_id );
			$thread->addParticipant($recipients);
			flash('Success...!', 'Message sent successfully', 'success');
			return redirect(URL_TRANSLATION_REQUESTS);
		} else {
			flash('Ooops...!', 'No User found to send message', 'error');
			return redirect(URL_TRANSLATION_REQUESTS);
		}
	}

	// Site Issues Start
	/**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function siteIssues($type = 'siteissue')
     {
        if( ! checkRole(getUserGrade(2) ) )
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['layout']      = getLayout();
        $data['active_class'] = 'siteissues';
        $data['heading']      = getPhrase('site_issues');
        $data['title']        = getPhrase('site_issues');
        return view('users.site-issues.siteissues', $data);
     }

	 /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getSiteIssues()
    {
        $records = array();


		$records = DB::table('translation_siteissues')->where('type', '=', 'siteissue')->orderBy('created_at', 'desc');


        return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="'.URL_SITE_ISSUES_VIEW.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("view").'</a></li>';

							$temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

                        $temp .='</ul> </div>';
                        $link_data .= $temp;
            return $link_data;
            })
		->editColumn('url', function($records){
            if ( $records->content_id > 0 ) {
				$lesson = App\LmsContent::where('id', '=', $records->content_id )->first();
				if ( $lesson ) {
					return '<a href="' . URL_LMS_CONTENT_EDIT . $lesson->slug . '">' . $lesson->title . '</a>';
				} else {
					return $records->url;
				}
			} else {
				return $records->url;
			}
        })
        ->editColumn('description', function($records){
            return $records->description;
        })
		->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('content_id')
        ->removeColumn('conten_type')
        ->removeColumn('user_id')
        ->removeColumn('user_agent')
		->removeColumn( 'ip_address' )
		->removeColumn( 'full_name' )
		->removeColumn( 'email' )
		->removeColumn( 'updated_at' )
        ->removeColumn( 'type' )
		->removeColumn( 'wp_user_id' )
        ->make();
    }

	public function deleteSiteIssues( $slug )
	{
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}
		/**
		* Check if any exams exists with this category,
		* If exists we cannot delete the record
		*/
		if(!env('DEMO_MODE')) {
			DB::table('translation_siteissues')->where('slug', $slug)->delete();
		}
		$response['status'] = 1;
		$response['message'] = getPhrase('record_deleted_successfully');
		return json_encode($response);
	}

	public function viewSiteIssues( $slug )
	{
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}

		$record = DB::table('translation_siteissues')->where('slug', $slug)->first();
		if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

		$data['record']             = $record;
		$data['active_class']       = 'siteissues';
        $data['title']              = getPhrase('view_site_issue');
        $data['layout']             = getLayout();
        return view('users.site-issues.view-siteissue', $data);
	}

	public function processSiteIssues( Request $request, $slug )
	{
		$record = DB::table('translation_siteissues')->where('slug', $slug)->first();
		if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

		$rules = [
         'recipients'          	   => 'required',
		 'message'          	   => 'required',
         ];
        $this->validate($request, $rules);
		if ( ! empty( $request->sendemail ) ) {
			$emails = $request->recipients;
			if ( ! empty( $emails ) ) {
				$emails = explode(',', $emails);
				if ( ! empty( $emails ) ) {
					foreach( $emails as $email )
					{
						if (!filter_var(trim( $email ), FILTER_VALIDATE_EMAIL) === false) {
							// Let us send and notification email to user set in profile email addresses
							sendEmail('user_email', array( 'to_email' => $email, 'message' => $request->message, ));
						}
					}
					flash('Success...!', 'Email sent successfully', 'success');
					return redirect(URL_SITE_ISSUES);
				} else {
					flash('Ooops...!', 'No Emails found to send invitation', 'error');
					return redirect(URL_SITE_ISSUES);
				}
			}
		} elseif ( $record->user_id > 0 ) {
			$subject = getPhrase( 'site_issue:' ) . $record->url;
			$thread = Thread::create(
				[
					'subject' => $subject,
				]
			);

			// Message
			Message::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id,
					'body'      => $request->message,
				]
			);

			// Sender
			Participant::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id,
					'last_read' => new Carbon,
				]
			);
			// Recipients
			$recipients = array( $record->user_id );
			$thread->addParticipant($recipients);
			flash('Success...!', 'Message sent successfully', 'success');
			return redirect(URL_SITE_ISSUES);
		} else {
			flash('Ooops...!', 'No User found to send message', 'error');
			return redirect(URL_SITE_ISSUES);
		}
	}

	/**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function newsLetterSubscriptions($type = 'newsletter')
     {
        if( ! checkRole(getUserGrade(2) ) )
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['layout']      = getLayout();
        $data['active_class'] = 'newsletter';
		$data['type'] = 'newsletter';
        $data['heading']      = getPhrase('news_letter_subscriptions');
        $data['title']        = getPhrase('news_letter_subscriptions');
        return view('users.site-issues.newsletter-subscriptions', $data);
     }

	 /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getNewsLetterSubscriptions()
    {
        $records = array();


		$records = DB::table('translation_siteissues')->where('type', '=', 'newsletter')->orderBy('created_at', 'desc');


        return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dLabel">';

							$temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

                        $temp .='</ul> </div>';
                        $link_data .= $temp;
            return $link_data;
            })
		->editColumn('email', function($records){
			return $records->email;
        })
		->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('content_id')
        ->removeColumn('conten_type')
        ->removeColumn('user_id')
        ->removeColumn('user_agent')
		->removeColumn( 'ip_address' )
		->removeColumn( 'full_name' )
		->removeColumn( 'updated_at' )
        ->removeColumn( 'type' )
		->removeColumn( 'description' )
		->removeColumn( 'url' )
        ->make();
    }

	public function changeEmail( Request $request )
     {
        if( ! checkRole(getUserGrade(5) ) )
        {
          prepareBlockUserMessage();
          return back();
        }

		if ($request->isMethod('post')) {
			if ( 'resend' === $request->button || 'remove' === $request->button ) {
				$columns = array(
					'new_email' => 'required|email',
				);
			} else {
				$columns = array(
					'new_email' => 'bail|required|email|unique:users,email',
				);
			}
			
			$this->validate($request,$columns);
			$lms_check = User::where( 'email', '=', $request->new_email )->first();
			$wp_check = DB::table( WP_TABLE_PREFIX . 'users' )->where( 'user_email', '=', $request->new_email )->first();
			
			if ( ! $lms_check && ! $wp_check  ) {
				$check = DB::table( 'email_change_requests' )->where( 'user_id', '=', Auth::User()->id )->first();
				if ( ! $check ) {
					$data = array(
						'user_id' => Auth::User()->id,
						'new_email' => $request->new_email,
						'confirm_code' => str_random(30),
					);
					DB::table( 'email_change_requests' )->insert( $data );
					$user = User::where( 'id', '=', Auth::User()->id )->first();
					$link = URL_CONFIRM_CHANGE_EMAIL . $data['confirm_code'];
					sendEmail('change_email', array('user_name'=>$user->email, 'username'=>$user->name, 'to_email' => $request->new_email, 'confirmation_link' => $link));

					flash('Success...!', 'We have sent you confirmation link to new email address. Please check to confirm the email address.', 'success');
					return redirect(URL_CHANGE_EMAIL);
				} else {

					if ( 'resend' === $request->button ) {
						$user = User::where( 'id', '=', Auth::User()->id )->first();
						$link = URL_CONFIRM_CHANGE_EMAIL . '/' . str_random(30);
						sendEmail('change_email', array('user_name'=>$user->email, 'username'=>$user->name, 'to_email' => $request->new_email, 'confirmation_link' => $link));

						DB::table( 'email_change_requests' )->where( 'user_id', '=', Auth::User()->id )->update( array( 'email_sent' => $check->email_sent + 1 ) );

						flash('Success...!', 'We have sent you confirmation link to new email address. Please check to confirm the email address.', 'success');
						return redirect(URL_CHANGE_EMAIL);
					} elseif ( 'remove' === $request->button ) {
						DB::table( 'email_change_requests' )->where( 'user_id', '=', Auth::User()->id )->delete();
						flash('Ooops...!', 'We have removed your request to change email address', 'error');
						return redirect(URL_CHANGE_EMAIL);
					} else {
						flash('Ooops...!', 'We have already received change request from you. Please check your new email address to confirm.', 'error');
						return redirect(URL_CHANGE_EMAIL);
					}
				}
			} else {
				flash('Ooops...!', 'This email already exist in the system.', 'error');
				return redirect(URL_CHANGE_EMAIL);
			}
		}

        $data['record']      = User::where('id', Auth::User()->id)->first();
        $data['layout']      = getLayout();
        $data['active_class'] = 'newsletter';
        $data['heading']      = getPhrase('change_email');
        $data['title']        = getPhrase('change_email');
        return view('users.change-email.change-view', $data);
     }
	 
	/**
	* This method shows the user preferences based on provided user slug and settings available in table.
	* @param  [type] $slug [description]
	* @return [type]       [description]
	*/
	public function profilePrivacySettings()
	{
		$record = \Auth::user();

		if($isValid = $this->isValidRecord($record))
		 return redirect($isValid);

		$data['record']       = $record;
		
		$data['settings']['group'] = array(
			'grouprequests' => getPhrase('group_requested'), // Some one has request you to join your group
			'groupinvitation' => getPhrase('group_invited'), // Some one has invite you to join their group
			'grouprequests_accepted' => getPhrase('group_request_accepted'),
			'groupinvitation_accepted' => getPhrase('group_invitation_accepted'),
			'groupleft' => getPhrase('group_left'),
		);
		
		// dd($data);
		$data['layout']       = getLayout();
		$data['active_class'] = 'users';
		$data['heading']      = getPhrase('privacy_settings');
		$data['title']        = getPhrase('privacy_settings');
		// flash('success','record_added_successfully', 'success');
		return view('users.privacy-settings', $data);
	}
	
/**
   * This method updates the user preferences based on the provided categories
   * All these settings will be stored under Users table settings field as json format
   * @param  Request $request [description]
   * @param  [type]  $slug    [description]
   * @return [type]           [description]
   */
  public function updatePrivacySettings(Request $request )
  {
		$record = \Auth::user();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

		$options = [];
		if($record->settings)
		{
		  $options =(array) json_decode($record->settings)->user_preferences;

		}

    $options['group'] = [];
    if($request->has('group')) {
    foreach($request->group as $key => $value)
      $options['group'][] = $key;
    }

    $record->settings = json_encode(array('user_preferences'=>$options));

    $record->save();

    flash('success','record_updated_successfully', 'success');
     return back();
  }
}
