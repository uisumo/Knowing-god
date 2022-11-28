<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\LMSGroups;
use Auth;
use App\LmsSettings;
use File;
use Image;
use ImageSettings;
use App\LmsContent;
use DB;
use Yajra\Datatables\Datatables;
use App\User;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

class LMSGroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * To list the User Groups
     * @return List of available user groups
     */
    public function index()
    {
        $groups                  = Group::all();
          $data['groups']          = $groups;
          $data['active_class']   = 'users';
          $data['title']          = getPhrase('user_groups');
        return view('admin.groups.groups-list', $data);
    }

    public function myGroups( $is_joined = 'no' )
    {
		$data['layout']   = getLayout();
		$data['title']          = getPhrase('my_groups');
		$data['type'] = 'mygroups';
		$data['active_class'] = 'mygroups';
		$data['is_joined'] = $is_joined;
		return view('lms-groups.list', $data);
    }

    public function otherGroups( $is_joined = 'no' )
    {
        $data['layout']   = getLayout();
        if ( 'joined' === $is_joined ) {
            $data['title']          = getPhrase('joined_groups');
            $data['active_class'] = 'joined';
        } elseif ( 'invitations' === $is_joined ) {
            $data['title']          = getPhrase('available_groups');
            $data['active_class'] = 'invitations';
        } elseif ( 'requests' === $is_joined ) {
            $data['title']          = getPhrase('requests');
            $data['active_class'] = 'requests';
        } else {
            $other_user = User::where('slug', '=', $is_joined)->first();
			if ( $other_user ) {
				$data['title']          = $other_user->name . getPhrase(' : groups');
				$data['active_class'] = 'othergroups';

			} else {
				$data['title']          = getPhrase('available_groups');
				$data['active_class'] = 'othergroups';
			}
        }
        $data['type'] = 'othergroups';

        $data['is_joined'] = $is_joined;
        return view('lms-groups.list', $data);
    }

    public function myGroupsgetList( $type = 'mygroups', $is_joined = 'no' )
     {
        $records = array();

        if ( $type == 'mygroups' ) {
            $records = LMSGroups::select(['id', 'title', 'slug', 'image', 'is_public', 'total_items',  'created_at', 'user_id', 'updated_at', 'group_status' ])
            ->where( 'user_id', '=', Auth::User()->id )
            ->orderBy('updated_at', 'desc');
        } else {
            if ( $is_joined == 'joined' ) {
                $records = LMSGroups::select(['lmsgroups.id', 'lmsgroups.title', 'lmsgroups.slug', 'lmsgroups.image', 'lmsgroups.is_public', 'lmsgroups.total_items',  'lmsgroups.created_at', 'lmsgroups.user_id', 'lmsgroups.updated_at', 'group_status' ])
                ->join( 'lmsgroups_users AS lcu', 'lcu.group_id', '=', 'lmsgroups.id' )
                // ->where( 'is_public', '=', 'yes' )
                ->where( 'lcu.user_id', '=', Auth::User()->id )
                ->orderBy('updated_at', 'desc');
            } elseif ( $is_joined == 'invitations' ) {
                $records = LMSGroups::select(['lmsgroups.id', 'lmsgroups.title', 'lmsgroups.slug', 'lmsgroups.image', 'lmsgroups.is_public', 'lmsgroups.total_items',  'lmsgroups.created_at', 'lmsgroups.user_id', 'lmsgroups.updated_at', 'group_status' ])
                ->join( 'lmsgroups_users AS lcu', 'lcu.group_id', '=', 'lmsgroups.id' )
                ->where( 'lcu.user_id', '=', Auth::User()->id )
                ->orderBy('updated_at', 'desc');
            } else {
                if ( Auth::check() ) {
                    if( checkRole( getUserGrade(2) ) ) {
                        if ( ! in_array( $is_joined, array( 'no', 'mygroups', 'joined', 'invitations' ) ) ) {
                            $records = LMSGroups::select(['lmsgroups.id', 'lmsgroups.title', 'lmsgroups.slug', 'lmsgroups.image', 'lmsgroups.is_public', 'lmsgroups.total_items',  'lmsgroups.created_at', 'lmsgroups.user_id', 'lmsgroups.updated_at', 'lmsgroups.group_status' ])
                            ->join( 'users', 'users.id', '=', 'lmsgroups.user_id' )
                            ->where( 'users.slug', '=', $is_joined )
                            ->orderBy('lmsgroups.updated_at', 'desc');
                        } else {
                        $records = LMSGroups::select(['id', 'title', 'slug', 'image', 'is_public', 'total_items',  'created_at', 'user_id', 'updated_at', 'group_status' ])
                        ->orderBy('updated_at', 'desc');
                        }
                    } else {
						if ( ! in_array( $is_joined, array( 'no', 'mygroups', 'joined', 'invitations' ) ) ) {
							$records = LMSGroups::select(['lmsgroups.id', 'lmsgroups.title', 'lmsgroups.slug', 'lmsgroups.image', 'lmsgroups.is_public', 'lmsgroups.total_items',  'lmsgroups.created_at', 'lmsgroups.user_id', 'lmsgroups.updated_at', 'lmsgroups.group_status' ])
									->join( 'users', 'users.id', '=', 'lmsgroups.user_id' )
									->where( 'users.slug', '=', $is_joined )
									->orderBy('lmsgroups.updated_at', 'desc');
						} else {
							$records = LMSGroups::select(['id', 'title', 'slug', 'image', 'is_public', 'total_items',  'created_at', 'user_id', 'updated_at', 'group_status' ])
							->where( 'is_public', '=', 'yes' )
							->where( 'user_id', '!=', Auth::User()->id )
							->orderBy('updated_at', 'desc');
						}
                    }
                } else {
                $records = LMSGroups::select(['id', 'title', 'slug', 'image', 'is_public', 'total_items',  'created_at', 'user_id', 'updated_at', 'group_status' ])
                ->where( 'is_public', '=', 'yes' )
                ->orderBy('updated_at', 'desc');
                }
            }
        }

        $records = Datatables::of($records)
        ->addColumn('action', function ($records) {

          if ( Auth::User()->id == $records->user_id || checkRole( getUserGrade(2) ) ) :
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';
                        $temp = '';
                        if ( Auth::User()->id == $records->user_id ) {
                        $temp .= '<li><a href="' . URL_STUDENT_ADD_GROUP_CONTENTS . $records->slug . '"><i class="fa fa-pencil"></i>'.getPhrase("update_lessons").'</a></li>
                           <li><a href="' . URL_STUDENT_UPDATE_GROUP . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
                           ';
                        }

                        if ( checkRole( getUserGrade(2) ) ) {
                            if ( $records->group_status == 'active' ) {
                            $temp .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'inactive\');"><i class="fa fa-trash"></i>'. getPhrase("suspend").'</a></li>';
                            } else {
                                $temp .= '<li><a href="javascript:void(0);" onclick="updateUserRecord(\''.$records->slug.'\', \'active\');"><i class="fa fa-trash"></i>'. getPhrase("activate").'</a></li>';
                            }
                        }

                        $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

                        $temp .='</ul></div>';


                    $link_data .=$temp;
            return $link_data;
            else :
            $lable = getPhrase('Join');
            $action = 'invite';
            $invitation = array(
                'user_id' => Auth::User()->id,
                'group_id' => $records->id,
             );
            $check = DB::table('lmsgroups_users')->where( $invitation );
            $note = '';
            $title = getPhrase( 'Click here to ' ) . $lable;
            if ( $check->count() > 0 ) {
                $lable = getPhrase('Withdraw Request');
                $action = 'with_draw';
                $title = getPhrase( 'Click here to ' ) . $lable;
                $check = $check->first();
                if ( $check->status == 'suspended' ) {
                    $lable = getPhrase('Suspended');
                    $title = getPhrase( 'You are suspended from the group' );
                    $action = 'remove';
                    $note = '<font color="red">' . getPhrase( 'You are suspended from the group. Click the button to leave the group.' ) . '</font>';
                }
                if ( $check->status == 'invited' ) {
                    $lable = getPhrase('Accept');
                    $title = getPhrase( 'Click here to ' ) . $lable;
                    $action = 'accept';
                    $note = '<button class="btn button btn-info mb-4" type="button" onclick="add_remove_group(' . $records->id . ',' . Auth::User()->id . ', \'reject\', \'group\')" id="user_button_' . $records->id . '_reject" title="'.getLayout('Click here to reject the request').'"><i class="fa fa-user-o"></i>' . getPhrase( 'Reject' ) . '</button>';
                }
                if ( $check->status == 'rejected' ) {
                    $lable = getPhrase('Rejected');
                    $title = getPhrase( 'Request Rejected, click here to delete' );
                    $action = 'delete';
                    //$note = '<button class="btn button btn-info mb-4" type="button" onclick="add_remove_group(' . $records->id . ',' . Auth::User()->id . ', \'delete\', \'group\')" id="user_button_' . $records->id . '_delete" title="'.getLayout('Click here to delete the request').'"><i class="fa fa-user-o"></i>' . getPhrase( 'Delete' ) . '</button>';
                }
                if ( $check->status == 'accepted' ) {
                    $lable = getPhrase('Leave Group');
                    $title = getPhrase( 'Click here to ' ) . $lable;
                    $action = 'leave';
                    $note = '<br><font color="red"><small>' . getPhrase( 'You will loss all data related to this group.' ) . '</small></font>';
                }
            }


            $output = '<button class="btn button btn-info mb-4" type="button" onclick="add_remove_group(' . $records->id . ',' . Auth::User()->id . ', \''.$action.'\', \'group\')" id="user_button_' . $records->id . '_'.$action.'" title="' . $title . '"><i class="fa fa-user-o"></i>' . $lable . '</button>';
            if ( ! empty( $note ) ) {
                $output .= $note;
            }
			if ( is_coach_for( $records->user_id ) ) {
				$output .= '&nbsp;|&nbsp;<button class="btn button btn-info mb-4" type="button" onclick="get_group_comment(' . $records->id . ')"  title="' . getPhrase('comment') . '"><i class="fa fa-comments-o"></i>' . getPhrase('comment') . '</button>';
			}
            return $output;
            endif;
            })
        ->editColumn('title', function($records)
        {
            $str = '<a href="' . URL_STUDENT_DASHBOARD_GROUP . $records->slug . '">' . $records->title . '</a>';
            if( checkRole( getUserGrade(2) ) ) {
                $user_details = App\User::where( 'id', '=', $records->user_id )->first();
                if ( $user_details ) {
                    $str .= '<br><b>'.getPhrase('User:').'</b> <a href="' . URL_USER_DETAILS . $user_details->slug . '">' . $user_details->name . '</a>';
                }
                $str .= '<br><b>'.getPhrase('is_public:').'</b>' . ucfirst( $records->is_public );
                $str .= '<br><b>'.getPhrase('status:').'</b>' . ucfirst( $records->group_status );
            }
            return $str;
        })
		->editColumn('total_items', function($records)
		{
			$lessons = DB::table('lmsgroups_contents')->where('group_id', '=', $records->id)->where('content_type', '=', 'lesson');
			$courses = DB::table('lmsgroups_contents')->where('group_id', '=', $records->id)->where('content_type', '=', 'course');
			$str = '';
			if ( $lessons->count() > 0 ) {
				$str .= getPhrase('lessons:') . $lessons->count();
			}
			if ( $courses->count() > 0 ) {
				if ( $str != '' ) {
					$str .= '<br>';
				}
				$str .= getPhrase('courses:') . $courses->count();
			}
			if ( '' == $str ) {
				$str = getPhrase('no_lessons');
			}
			return $str;
		})
        ->editColumn('image', function($records)
        {
          $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
          if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_LMS_GROUPS.$records->image;

            return '<img src="'.$image_path.'" height="60" width="60"  />';
        });
        if ( $type == 'mygroups' ) {
            $records = $records->editColumn('is_public', function($records)
            {
                return ($records->is_public == 'yes' ) ? '<span class="label label-primary">'.getPhrase('yes') .'</span>' : '<span class="label label-success">'.getPhrase('no').'</span>';
            });
        }

        $records = $records->editColumn('created_at', function( $records )
        {
            return date( 'd M, Y', strtotime( $records->created_at ) );
        })
        ->removeColumn( 'id' )
        ->removeColumn( 'slug' )
        ->removeColumn( 'updated_at' )
        ->removeColumn( 'user_id' )
        ->removeColumn( 'group_status' );
        if ( $type != 'mygroups' ) {
            $records = $records->removeColumn( 'is_public' );
        }
        $records = $records->make();
        return $records;
     }

    public function create()
    {
		if ( Auth::User()->current_user_role == 'subscriber' ) {
			prepareBlockUserMessage( 'you dont have permission to create group' );
            return back();
		}
		$groups                  = LMSGroups::all();
		$data['groups']          = $groups;
		$data['active_class']   = 'create';
		$data['layout']   = getLayout();
		$data['record']       = FALSE;
		$data['title']          = getPhrase('create_group');
		return view('lms-groups.add-group', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
        if ( Auth::User()->current_user_role == 'subscriber' ) {
			prepareBlockUserMessage( 'you dont have permission to create group' );
            return back();
		}
		
		$record = LmsGroups::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['record']               = $record;
        $data['active_class']       = 'lms';
        $data['layout']   = getLayout();
        $data['title']              = getPhrase('edit_group');
        return view('lms-groups.add-group', $data);
    }

    /**
      * Store a newly created resource in storage.
      *
      * @return Response
      */
     public function store(Request $request )
     {
        if ( Auth::User()->current_user_role == 'subscriber' ) {
			prepareBlockUserMessage( 'you dont have permission to create group' );
            return back();
		}
		$rules = [
			'title' => 'bail|required|max:60|unique:lmsgroups,title',
            ];
		$this->validate($request, $rules);
		$record = new LmsGroups();
		$title               =  $request->title;
		$record->user_id     = Auth::User()->id;
		$record->title          = $title;
		$record->sub_title      = $request->sub_title;
		$record->slug          = $record->makeSlug( $title,TRUE );
		$record->description = $request->description;
		$record->is_public     = $request->is_public;
		$record->save();
		$file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);

            $settings = json_decode((new LmsSettings())->getSettings());
            $path      = $settings->groupsImagepath;
            $this->deleteFile($record->image, $path);

            $record->image      = $this->processUpload($request, $record,$file_name);
            $record->save();
        }

        flash('success','record_added_successfully', 'success');
        $redirect = URL_STUDENT_MY_GROUPS;
        if( checkRole( getUserGrade(2) ) ) {
            $redirect = URL_ADMIN_ALL_LMSGROUPS;
        }
        return redirect($redirect);
     }

     /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {

        $record = LmsGroups::getRecordWithSlug($slug);


        $rules = [
         'title'                 => 'bail|required|max:60|unique:lmsgroups,title,' . $record->id,
          ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name,TRUE);

       //Validate the overall request
        $this->validate($request, $rules);
        $record->title             = $name;
        $record->sub_title             = $request->sub_title;
        $record->description    = $request->description;
        $record->is_public        = $request->is_public;
        $record->save();
          $file_name = 'image';
         if ($request->hasFile($file_name))
        {

             $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
              $this->validate($request, $rules);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

        flash('success','record_updated_successfully', 'success');
        $redirect = URL_STUDENT_MY_GROUPS;
        if( checkRole( getUserGrade(2) ) ) {
            $redirect = URL_ADMIN_ALL_LMSGROUPS;
        }

        return redirect($redirect);
    }

     /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record, $file_name)
     {
        if(env('DEMO_MODE')) {
            return 'demo';
        }

         if ($request->hasFile($file_name)) {
          $settings = json_decode((new LmsSettings())->getSettings());
          $destinationPath      = public_path( $settings->groupsImagepath );
          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($settings->imageSize)->save($destinationPath.$fileName);
         return $fileName;
        }
     }

    public function deleteFile($record, $path, $is_array = FALSE)
    {
        if(env('DEMO_MODE')) {
            return '';
        }
        $files = array();
        $files[] = $path.$record;
        File::delete($files);
    }

    public function groupDashboard( $slug )
    {
        // Courses
        // Members
        // Messages (Within Group)
        $groups                  =
          $data['details']          = LMSGroups::getRecordWithSlug( $slug );
          $data['active_class']   = 'lmsgroups';
        $data['layout']   = getLayout();
        $data['record']       = FALSE;
          $data['title']          = getPhrase('user_groups');
        return view('lms-groups.group-dashboard', $data);
    }

    public function addGroupCourses( $slug = '' )
    {
        $groups                  = LMSGroups::all();
          $data['groups']          = $groups;
          $data['active_class']   = 'lmsgroups';
        $data['layout']   = getLayout();
        $data['record']       = FALSE;
          $data['title']          = getPhrase('user_groups');
        return view('lms-groups.add-group', $data);
    }

    public function inviteGroupMembers( $slug = '' )
    {
        $groups                  = LMSGroups::all();
          $data['groups']          = $groups;
          $data['active_class']   = 'lmsgroups';
        $data['layout']   = getLayout();
        $data['record']       = FALSE;
          $data['title']          = getPhrase('user_groups');
        return view('lms-groups.add-group', $data);
    }

    public function groupSingleLesson( $group_slug, $slug, $piece_slug = '' )
     {
        /*
		$data = array();
        $record = Lmscontent::getRecordWithSlug( $slug );
        $data['item']      = $record;
        $data['parent_item']      = $record;
        if( ! empty( $piece_slug ) ) {
            $data['item']      = Lmscontent::getRecordWithSlug( $piece_slug );
        }
        $group_details = LMSGroups::getRecordWithSlug($group_slug);
        $data['group_details'] = $group_details;
        $data['layout']      = getLayout();
        $data['left'] = 'no';
        $data['right'] = 'no';
        return view( 'lms-groups.group-single-lesson', $data );
		*/
		
		$data = array();
		$record = Lmscontent::getRecordWithSlug( $slug );
		$data['item']      = $record;
		$data['display_item']      = $record;
		$data['current_piece'] = FALSE;
		if( ! empty( $piece_slug ) ) {
			$data['current_piece']  = Lmscontent::getRecordWithSlug( $piece_slug );
			$data['display_item']      = $data['current_piece'];
		}

		$group_details = LMSGroups::getRecordWithSlug($group_slug);
		
        $data['group_details'] = $group_details;
		$data['parent_course']      = FALSE;
		
		$series_details = App\LMSGroups::select(['lmsseries.*'])->join( 'lmsgroups_contents AS lgc', 'lgc.group_id', '=', 'lmsgroups.id' )
					->join('lmsseries', 'lmsseries.id', '=', 'lgc.content_id')
					->join('lmsseries_data AS lsd', 'lsd.lmsseries_id', '=', 'lmsseries.id')
					->join('lmscontents AS lc', 'lc.id', '=', 'lsd.lmscontent_id')
					->where( 'lmsgroups.slug', '=', $group_slug )
					->where( 'lc.slug', '=', $slug )
					->where( 'lgc.content_type', '=', 'lesson' )
					->where( 'lmsseries.status', '=', 'active' )
					->first();
		// dd( $series_details );
		if ( ! empty( $series_details->parent_id ) ) {
			$data['parent_course'] = App\LmsSeries::where('id', '=',$series_details->parent_id )->first();
		}
		$data['series_details'] = $series_details;
		
		$data['category'] = App\QuizCategory::getRecordWithId( $group_details->lms_category_id );
		$data['layout']      = getLayout();
		$data['left'] = 'no';
		$data['right'] = 'no';
		return view( 'lms-forntview.single-lesson', $data );
     }
	 
	public function showCourse( $slug )
    {
     	$course_details   = LmsSeries::select(['lmsseries.*', 'qc.category', 'qc.slug AS cat_slug'])
		->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
		->where('lmsseries.slug','=',$slug )
		->where('lmsseries.status','=','active' )
		->where('qc.category_status','=','active' )
		->first();

		if( ! $course_details )
		{
			prepareBlockUserMessage();
			return back();
		}

		if ( $course_details->privacy == 'loginrequired' && ! Auth::check() ) {
			prepareBlockUserMessage();
			return back();
		}

		if ( Auth::check() ) {
			$add_mycourse = TRUE;
			if ( $course_details->is_paid == 1 && $course_details->cost > 0 ) {
				if ( ! isItemPurchased( $course_details->id, 'lms' ) ) {
					$add_mycourse = FALSE;
				}
			}
			/**
			 * If the course is paid course, It should buy to add it to my courses
			 */
			if ( $add_mycourse ) {
				$check = App\MyCourses::where('user_id', '=', Auth::User()->id)->where( 'course_id', '=', $course_details->id );

				if ( $check->count() == 0 ) {
					$record = new App\MyCourses();
					$record->user_id = Auth::User()->id;
					$record->course_id = $course_details->id;
					$record->save();
				}
			}
		}

     	$data['course_details']    = $course_details;
		$data['title']    = $course_details->title;
		$data['layout']      = getLayout();
     	return view('lms-forntview.other-views.show-course',$data);
    }

     public function addGroupContents( $group_slug )
     {
        /**
         * Get the Quiz Id with the slug
         * Get the available questions from questionbank_quizzes table
         * Load view with this data
         */
        $record = LMSGroups::getRecordWithSlug( $group_slug );
        $data['record']             = $record;
        $data['active_class']       = 'lmsgroups';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'lms-groups.right-bar-update-lmslist';
        
		if ( 'facilitator' === get_current_user_special_role() ) {
			/**
			 * Facilitator: can start a group using the PathwayStart material.
			 */
			$data['categories']      = array_pluck(App\Subject::where('id', '=', PATHWAY_START_ID)->get(),'subject_title', 'id');
		} else {
			$data['categories']      = array_pluck(App\Subject::all(),'subject_title', 'id');
		}
		
        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_items > 0)
        {
			/*
		   $series = DB::table('lmsseries_data')
                            ->where('lmsseries_id', '=', $record->id)
                            ->get();
							*/
			$series = DB::table('lmsgroups_contents AS lgc')
			->select(['lmscontents.*', 'lgc.group_id', 'lgc.content_id'])
			->join('lmscontents', 'lmscontents.id', '=', 'lgc.content_id')
			//->join('quizcategories AS qc', 'qc.id', '=', 'lmscontents.lms_category_id')
			->where('group_id', '=', $record->id)
			->where('lgc.content_type', '=', 'lesson')
			// ->where('lmscontents.status', '=', 'active')
			//->where('qc.category_status', '=', 'active')
			->get()
			;
            foreach($series as $r)
            {
                $temp = array();
                $temp['id']     = $r->content_id;                
              // dd($series_details);
                $temp['content_type'] = $r->content_type;
                $temp['code']          = $r->code;
                $temp['title']          = $r->title;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
        }

        $data['exam_categories']           = array_pluck(App\QuizCategory::all(),
                                        'category', 'id');
        $data['title']              = getPhrase('update_lessons_for').' '.$record->title;

        $data['layout'] = getLayout('exams');

        return view( 'lms-groups.update-list', $data );
     }

     public function updateGroupContents( Request $request, $slug )
     {
         $lms_group = LMSGroups::getRecordWithSlug($slug);

        $lmsgroup_id  = $lms_group->id;
        $contents      = json_decode($request->saved_series);

        $contents_to_update = array();
        foreach ($contents as $record) {
            $temp = array();
            $temp['content_id'] = $record->id;
            $temp['group_id'] = $lmsgroup_id;
			$temp['content_type'] = 'lesson';
            array_push($contents_to_update, $temp);

        }
        $lms_group->total_items = $lms_group->total_posts + $lms_group->total_courses + count($contents);
		$lms_group->total_lessons = count($contents);
        //Clear all previous questions
        DB::table('lmsgroups_contents')
		->where('group_id', '=', $lmsgroup_id)
		->where('content_type', '=', 'lesson')
		->delete();
        //Insert New Questions
        DB::table('lmsgroups_contents')->insert($contents_to_update);
        $lms_group->save();
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_STUDENT_DASHBOARD_GROUP . $lms_group->slug );
     }

     public function groupInvitations( $group_slug, $invitation_status = 'accepted' )
     {
        $record = LMSGroups::getRecordWithSlug( $group_slug );

        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['group']          = $record;
        $data['invitation_status']    = $invitation_status;
        $data['active_class']   = 'users';
        $data['layout']   = getLayout();
		if ( 'accepted' === $invitation_status ) {
			$data['title']          = getPhrase('members');
		} else {
			$data['title']          = getPhrase('invitations');
		}

        $data['is_joined'] = 'no';
        return view('lms-groups.group-invitations', $data);
     }

     public function groupInvitationsAdd( $group_slug )
     {
         $record = LMSGroups::getRecordWithSlug( $group_slug );

        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['group']          = $record;
        $data['active_class']   = 'users';
        $data['layout']   = getLayout();
        $data['title']          = getPhrase('invite users');
        return view('lms-groups.group-invitations-add', $data);
     }

     public function groupInvitationsAddGetUsers( $group_slug )
     {
         $records = array();
         $group = LmsGroups::getRecordWithSlug( $group_slug );
         $records = User::select(['id', 'name', 'first_name', 'last_name', 'image', 'slug', 'created_at'])
         ->where( 'status', '=', 'activated' )
         ->where( 'privacy', '=', 'public' ) // We need this in order to respect users privacy
         ->where( 'role_id', '=', STUDENT_ROLE_ID )
         ->where( 'id', '!=', Auth::User()->id )
         ->orderBy('name');
         return Datatables::of( $records )
         ->addColumn('action', function ( $records ) use( $group ) {
             $lable = getPhrase('Add');
             $action = 'invite';
             $invitation = array(
                'user_id' => $records->id,
                'group_id' => $group->id,
             );
             $check = DB::table('lmsgroups_users')->where( $invitation );
             $str = '';
             if ( $check->count() > 0 ) {
                $lable = getPhrase('Remove');
                $action = 'remove';

                $check = $check->first();
                if ( $check->status == 'suspended' ) {
                    $lable = getPhrase('Suspended');
                    $action = 'remove';
                }
                if ( $check->status == 'rejected' ) {
                    $lable = getPhrase('Rejected');
                    $action = 'remove';
                }
                if ( $check->status == 'requested' ) {
                    $lable = getPhrase('Requested');
                    $action = 'remove';

                    $str = '&nbsp;<button class="btn button btn-info mb-4" type="button" onclick="add_remove_group(' . $records->id . ',' . Auth::User()->id . ', \'reject\', \'user\')" id="user_button_' . $records->id . '_reject" title="'.getLayout('Click here to reject the request').'"><i class="fa fa-user-o"></i>' . getPhrase( 'Reject' ) . '</button>';
                }
             }
             return '<button class="btn button btn-info" type="button" onclick="add_remove_group(' . $group->id . ',' . $records->id . ', \''.$action.'\', \'user\')" id="user_button_' . $records->id . '_'.$action.'"><i class="fa fa-user-o"></i>' . $lable . '</button>' . $str;
         })
        ->editColumn('name', function($records)
        {
            $str = '<img src="'.getProfilePath($records->image).'"  style="border-radius:50%;width: 70px;height: 70px;"/>';
            $str .= '<br>' . $records->name;
            return $str;
        })
        ->editColumn('created_at', function($records){
            return date('d M, Y', strtotime( $records->created_at ) );
        })
         ->removeColumn( 'id' )
         ->removeColumn( 'slug' )
         ->removeColumn( 'first_name' )
         ->removeColumn( 'last_name' )
         ->removeColumn( 'image' )
         ->make();
     }

     public function groupInvitationsGetList( $group_slug, $group_status = 'accepted' )
     {
         $records = array();

         $group = LmsGroups::getRecordWithSlug( $group_slug );

         $records = LMSGroups::select(['u.name', 'u.slug', 'u.image', 'lgu.created_at', 'lgu.updated_at', 'lmsgroups.id', 'lgu.status', 'lgu.user_id' ])
         ->join( 'lmsgroups_users AS lgu', 'lgu.group_id', '=', 'lmsgroups.id' )
         ->join( 'users AS u', 'u.id', '=', 'lgu.user_id' )
         ->where( 'lmsgroups.slug', '=', $group_slug )
         ->where( 'u.status', '=', 'activated' )
         ->where( 'lgu.status', '=', $group_status )
         ->where( 'privacy', '=', 'public' )
         ->orderBy('lgu.updated_at', 'desc');
         $records = Datatables::of($records);
         $str = '';
         if ( $group->user_id == Auth::User()->id ) {
             $records = $records->addColumn('action', function ( $records ) {
                $str = '';
                $lable = getPhrase('Remove'); // Invited
                $action = 'remove';
                if ( $records->status == 'suspended' ) {
                    $lable = getPhrase('Suspended');
                    $action = 'remove';
                }
                if ( $records->status == 'rejected' ) {
                    $lable = getPhrase('Rejected');
                    $action = 'remove';
                }
                if ( $records->status == 'requested' ) {
                    $lable = getPhrase('accept_it?');
                    $action = 'accept';

                    $str = '&nbsp;<button class="btn button btn-info mb-4" type="button" onclick="add_remove_group(' . $records->id . ',' . $records->user_id . ', \'reject\', \'user\')" id="user_button_' . $records->id . '_reject" title="'.getLayout('Click here to reject the request').'"><i class="fa fa-user-o"></i>' . getPhrase( 'Reject' ) . '</button>';
                }
                 return '<button class="btn button btn-info mb-4" type="button" onclick="add_remove_group(' . $records->id . ',' . $records->user_id . ', \''.$action.'\', \'user\')" id="user_button_' . $records->user_id . '_'.$action.'"><i class="fa fa-user-o"></i>' . $lable . '</button>' . $str;
             });
         }
        $records = $records->editColumn( 'name', function( $records ) use($group_slug) {
            $str = '<img src="'.getProfilePath($records->image).'"  style="border-radius:50%;width: 160px;height: 160px;"/>';
            if ( checkRole( getUserGrade(2) ) ) {
            $str .= '<br>
            <a href="' . URL_STUDENT_GROUP_USER_STATUS . $group_slug . '/'.$records->slug.'" title="'.getPhrase( 'View Status' ).'">' . $records->name . '</a>';
            } else {
                $str .= '<br>' . $records->name;
            }
            return $str;
        })
        ->editColumn( 'created_at', function( $records ){
            return date( 'd M, Y', strtotime( $records->created_at ) );
        } )
        ->removeColumn( 'slug' )
        ->removeColumn( 'id' )
        ->removeColumn( 'updated_at' )
        ->removeColumn( 'image' )
        ->removeColumn( 'status' )
        ->removeColumn( 'user_id' )
        ->make();
        return $records;
     }

     /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = LmsGroups::where('slug', $slug)->first();
        try{
            $id = $record->id;
            DB::table('lmsgroups_contents')->where('group_id', '=', $id)->delete();
            DB::table('lmsgroups_users')->where('group_id', '=', $id)->delete();

            $record->delete();
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
        }
         catch ( \Illuminate\Database\QueryException $e) {
                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->errorInfo;
           else
            $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
       }
        return json_encode($response);
    }

     public function groupInvitationsDelete( $group_slug, $invitation_slug )
     {

     }


     public function isValidRecord($record)
    {
        if ($record === null) {

            flash('Ooops...!', getPhrase("page_not_found"), 'error');
               return $this->getRedirectUrl();
        }

        return FALSE;
    }

	public function sendNotification( $details, $action, $user_id, $receiver = '' )
	{
		$subject = $message = $recipient_id = '';

		if ( 'groupinvitation' === $action ) {
			// Let us send notifications
			$subject = 'You are invited to join a group : ';
			$thread = Thread::create( [ 'subject' => getPhrase( $subject . $details->title ),] );
			$recipient_id = $receiver;

			$remove_link = '<br>click here to remove <a href="' .URL_MANAGE_GROUP_REQUESTS .  $details->slug . '/' . $receiver . '/remove/' . $thread->id . '" title="' . $details->title . '">'. $details->title . '</a>';
			$message = 'You are requested to join group : ' .$details->title . ' <br>click here to join <a href="' .URL_MANAGE_GROUP_REQUESTS .  $details->slug . '/' . $receiver . '/accept/' . $thread->id . '" title="' . $details->title . '">'. $details->title . '</a>' . $remove_link;
		} elseif ( 'grouprequests' === $action ) {
			$user_details = User::where('id', '=', $user_id)->first();
			if ( $user_details ) {
				$subject = $user_details->name . ' has requested you to join your group : ';
				$thread = Thread::create( [ 'subject' => getPhrase( $subject . $details->title ),] );

				$remove_link = '<br>click here to remove <a href="' .URL_MANAGE_GROUP_REQUESTS .  $details->slug . '/' . $user_id . '/remove/' . $thread->id . '" title="' . $details->title . '">'. $details->title . '</a>';
				$message = $user_details->name . ' has requested you to join your group : ' . $details->title . ' <br>click here to accept <a href="' .URL_MANAGE_GROUP_REQUESTS .  $details->slug . '/' . $user_id . '/accept/' . $thread->id . '" title="' . $details->title . '">'. $details->title . '</a>' . $remove_link;
			}
			$recipient_id = $receiver;
		} elseif( 'grouprequests_accepted' === $action ) {
			$user_details = User::where('id', '=', $user_id)->first();
			if ( $user_details ) {
				$subject = $user_details->name . ' has accepted your request to join group : ';
				$thread = Thread::create( [ 'subject' => getPhrase( $subject . $details->title ),] );
				$message = $user_details->name . ' has accepted your request to join group : ' . $details->title . ' <br>click here to see details <a href="' .URL_STUDENT_DASHBOARD_GROUP . $details->slug . '" title="' . $details->title . '">'. $details->title . '</a>';
			}
			$recipient_id = $receiver;
		} elseif( 'groupinvitation_accepted' === $action ) {
			$user_details = User::where('id', '=', $user_id)->first();

			if ( $user_details ) {
				$subject = $user_details->name . ' has accepted your invitation to join group : ';
				$thread = Thread::create( [ 'subject' => getPhrase( $subject . $details->title ),] );
				$message = $user_details->name . ' has accepted your invitation to join group : ' . $details->title . ' <br>click here to see details <a href="' .URL_STUDENT_DASHBOARD_GROUP . $details->slug . '" title="' . $details->title . '">'. $details->title . '</a>';
			}
			if ( ! empty( $receiver ) ) {
				$recipient_id = $receiver;
			} else {
				$recipient_id = $user_id;
			}
		}

		$user_options = null;
		$record = User::where('id', '=', $recipient_id)->first();
		if( ! empty( $record ) && $record->settings ) {
			$user_options = json_decode( $record->settings )->user_preferences;
		}

		$can_send = TRUE;
		if($user_options) {
			if ( ! empty ( $user_options->group ) )
			{
				if( ! in_array( $action,$user_options->group ) ) {
					$can_send = FALSE;
				}

			}
		}

		if ( ! empty( $subject ) && ! empty( $message ) && ! empty( $recipient_id ) && $can_send ) {
			Message::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id,
					'body'      => $message,
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

			$recipients[] = $recipient_id;
			$thread->addParticipant($recipients);
		}
	}

    public function groupInvitationsAddRemoveUser( Request $request )
    {
        $response = array(
            'status' => 'success',
            'message' => getPhrase( 'You have sent invitation successfully' ),
            'user_id' => $request->user_id,
            'button_text' => getPhrase( 'Invitation Sent' ),
            'action' => $request->action,
            'group_id' => $request->group_id,
            'operation_type' => $request->operation_type,
        );
        $invitation = array(
                'group_id' => $request->group_id,
                'user_id' => $request->user_id,
             );
        $group = LmsGroups::where( 'id', '=', $request->group_id )->first();
        if ( $request->action == 'invite' ) {
             $check = DB::table('lmsgroups_users')->where( $invitation )->get();
             if ( $check->count() == 0 )
             {
                 $invitation['slug'] = rand();
                 if ( Auth::User()->id == $group->user_id ) {
                    $invitation['status'] = 'invited';
					// Let us send notification
					$this->sendNotification( $group, 'groupinvitation', $group->user_id, $request->user_id );
                 } else {
                    $invitation['status'] = 'requested';
					$this->sendNotification( $group, 'grouprequests', $request->user_id, $group->user_id );
                    $response['message'] = getPhrase('Group request sent');
                 }
                 DB::table('lmsgroups_users')->insert( $invitation );
             } else {
                $response['status'] = 'already_exists';
                $response['message'] = getPhrase('User already in the group');
                $response['button_text'] = 'Already In Group';
             }
        }
        if ( in_array( $request->action, array( 'remove', 'leave', 'with_draw', 'delete' ) ) ) {
            if ( $request->operation_type == 'group' ) {
                if ( $request->action == 'leave' ){
                    $response['message'] = getPhrase('You permanently left the group');
                    $response['button_text'] = 'Left';
                } else if( $request->action == 'with_draw' ) {
                    $response['message'] = getPhrase('You have withdrawed your request');
                    $response['button_text'] = 'Withdrawed';
                } else if( $request->action == 'delete' ) {
                    $response['message'] = getPhrase('You have deleted your request');
                    $response['button_text'] = 'Deleted';
                } else {
                    $response['message'] = getPhrase('User removed from group');
                    $response['button_text'] = 'Removed';
                }
            } else {
                $response['message'] = getPhrase('User removed from group');
                $response['button_text'] = 'Removed';
            }

            DB::table('lmsgroups_users')->where( $invitation )->delete();
        }
        if ( $request->action == 'accept' ) {
            DB::table('lmsgroups_users')->where( $invitation )->update( array('status' => 'accepted',  'updated_at' => date('Y-m-d H:i:s')) );
            if ( Auth::User()->id == $group->user_id ) {
                $response['message'] = getPhrase('You have accepted user');
            } else {
                $response['message'] = getPhrase('Now you are member of <i>' . $group->title . '</i> group');
            }
            $response['button_text'] = 'Accepted';
        }
        if ( $request->action == 'reject' ) {
            /*
            if ( $invitation['user_id'] != Auth::User()->id ) { // Which means you are rejecting other user request, but owner of group is you only!!
                $invitation['user_id'] = Auth::User()->id;
            }
            */

            DB::table('lmsgroups_users')->where( $invitation )->update( array('status' => 'rejected',  'updated_at' => date('Y-m-d H:i:s')) );
            if ( Auth::User()->id == $group->user_id ) {
                // $response['message'] = getPhrase('You have accepted user');
                $response['message'] = getPhrase('You have rejected group (' . $group->title . ') request');
            } else {
                $response['message'] = getPhrase('You have rejected group (' . $group->title . ') request');
            }
            $response['button_text'] = 'Rejected';
        }
        return json_encode( $response );
    }

    public function groupUserStatus( $group_slug, $user_slug )
    {
        if ( empty( $group_slug ) || empty( $user_slug ) ) {
            flash('Ooops...!', getPhrase("wrong_operation"), 'error');
               return $this->getRedirectUrl();
        }

        $group = LMSGroups::getRecordWithSlug( $group_slug );

        if($isValid = $this->isValidRecord($group))
            return redirect($isValid);

        $user = App\User::getRecordWithSlug( $user_slug );
        if($isValid = $this->isValidRecord($user))
            return redirect($isValid);

        $data['group']    = $group;
        $data['item']    = $group;
        $group_obj = new LMSGroups();
        $data['contents']   = $group_obj->getContents( $group->id );
        $data['user']    = $user;
        $data['layout']   = getLayout();
        $data['title']          = $user->name . ' ' . getPhrase( 'status' );
        return view('lms-groups.group-user-status', $data);

    }

    public function changeStatus( Request $request )
    {
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

          $group = LmsGroups::where( 'slug', '=', $request->slug )->first();
          if ( $group )
          {
            $group->group_status = $request->status;
            $group->save();

            $response['status'] = 1;
            $response['message'] = getPhrase('record_updated_successfully');
            return json_encode($response);
          } else {
            $response['status'] = 0;
            $response['message'] = getPhrase('wrong_operation');
            return json_encode($response);
          }
    }

	public function manage_groups_requests( $group_slug, $user_id, $action, $thread_id = '' )
	{
		if ( ! empty( $thread_id ) ) {
			 try {
            $thread = Thread::findOrFail($thread_id);
			} catch (ModelNotFoundException $e) {

			}
			if ( $thread ) {
				$thread->markAsRead( $user_id );
			}
		}

		$details = LmsGroups::select(['lmsgroups_users.*', 'lmsgroups.slug', 'lmsgroups.user_id AS group_owner'])->join('lmsgroups_users', 'lmsgroups_users.group_id', 'lmsgroups.id')
					->join('users', 'users.id', '=', 'lmsgroups_users.user_id')
					->where( 'lmsgroups.slug', '=', $group_slug )
					->where('users.id', '=', $user_id)->first();
		if ( ! empty( $thread_id ) ) {
			 try {
            $thread = Thread::findOrFail($thread_id);
			} catch (ModelNotFoundException $e) {

			}
			if ( $thread && $details ) {
				$thread->markAsRead( $details->group_owner );
			}
		}

		$group = LmsGroups::where( 'slug', '=', $group_slug )->first();
		if ( ! $group ) {
			flash('Ooops...!', getPhrase("wrong_operation"), 'error');
            return redirect( URL_STUDENT_MY_GROUPS );
		}
		if ( empty( $group_slug ) || empty( $user_id ) || empty( $action ) ) {
			flash('Ooops...!', getPhrase("wrong_operation"), 'error');
            return redirect( URL_STUDENT_MY_GROUPS );
		}

		// dd( $details );
		if ( ! $details ) {
			flash('Ooops...!', getPhrase("group_details_not_found"), 'error');
            return redirect( URL_STUDENT_MY_GROUPS );
		}

		$invitation = array(
                'user_id' => $details->user_id,
				'group_id' => $details->group_id,
             );

		if ( 'accept' === $action ) {
			DB::table('lmsgroups_users')->where( $invitation )->update( array('status' => 'accepted',  'updated_at' => date('Y-m-d H:i:s')) );
			flash('Great!', getPhrase("thanks_for_accepting_request"), 'success');
            if ( Auth::User()->id == $details->group_owner ) {
				$this->sendNotification( $group, 'grouprequests_accepted', $details->group_owner, $details->user_id );
				return redirect( URL_USERS_DASHBOARD_USER );
			} else {
				$this->sendNotification( $group, 'groupinvitation_accepted', $details->user_id, $details->group_owner );
				return redirect( URL_STUDENT_DASHBOARD_GROUP . $details->slug );
			}
		} elseif ( 'remove' === $action ) {
			DB::table('lmsgroups_users')->where( $invitation )->delete();
			return redirect( URL_USERS_DASHBOARD_USER );
		}
	}
	
	public function manage_groups_requests_direct( Request $request, $group_slug, $user_id, $action )
	{
		if ( $user_id != get_current_user_id() ) {
			flash('Ooops...!', getPhrase("are you kidding?"), 'error');
			$referer = \URL::previous();
			if ( empty( $referer ) ) {
				$referer = URL_STUDENT_OTHER_LMS_GROUPS;
			}
            return redirect( URL_STUDENT_OTHER_LMS_GROUPS );
		}
		
		$group = LmsGroups::where( 'slug', '=', $group_slug )->first();
		if ( ! $group ) {
			flash('Ooops...!', getPhrase("wrong_operation"), 'error');
            return redirect( URL_STUDENT_OTHER_LMS_GROUPS );
		}
		if ( empty( $group_slug ) || empty( $user_id ) || empty( $action ) ) {
			flash('Ooops...!', getPhrase("wrong_operation"), 'error');
            return redirect( URL_STUDENT_OTHER_LMS_GROUPS );
		}

		if ( 'requested' === $action ) {
			$invitation = array(
                'user_id' => $user_id,
				'group_id' => $group->id,
				'status' => 'requested',
				'joined_status' => 'joined',
             );
			 $check = DB::table('lmsgroups_users')->where( array(
				'user_id' => $user_id,
				'group_id' => $group->id
			 ) )->get();
			 $message = getPhrase('your_request_has_been_sent to group owner. Please wait till he accept your request');
			 if ( $check->count() == 0 ) {
				 DB::table('lmsgroups_users')->insert( $invitation );
			 } else {
				 $message = getPhrase('You have already requested to join this group. Please wait till group owner will accept the request.');
			 }
			flash('Success!', $message, 'success');
			$referer = \URL::previous();
			if ( empty( $referer ) ) {
				$referer = URL_STUDENT_OTHER_LMS_GROUPS;
			}
            return redirect( $referer );
		}
		
		if ( 'withdraw' === $action ) {
			DB::table('lmsgroups_users')->where( array(
				'user_id' => $user_id,
				'group_id' => $group->id
			 ) )->delete();
			$message = 'Your request removed to join the group.';
			flash('Success!', $message, 'success');
			$referer = \URL::previous();
			if ( empty( $referer ) ) {
				$referer = URL_STUDENT_OTHER_LMS_GROUPS;
			}
			return redirect( $referer );
		}
		
		$details = LmsGroups::select(['lmsgroups_users.*', 'lmsgroups.slug', 'lmsgroups.user_id AS group_owner'])->join('lmsgroups_users', 'lmsgroups_users.group_id', 'lmsgroups.id')
					->join('users', 'users.id', '=', 'lmsgroups_users.user_id')
					->where( 'lmsgroups.slug', '=', $group_slug )
					->where('users.id', '=', $user_id)->first();
					
		if ( ! $details ) {
			flash('Ooops...!', getPhrase("group_details_not_found"), 'error');
            return redirect( URL_STUDENT_MY_GROUPS );
		}
		

		$invitation = array(
                'user_id' => $details->user_id,
				'group_id' => $details->group_id,
             );

		if ( 'accept' === $action ) {
			DB::table('lmsgroups_users')->where( $invitation )->update( array('status' => 'accepted',  'updated_at' => date('Y-m-d H:i:s')) );
			flash('Great!', getPhrase("thanks_for_accepting_request"), 'success');
            if ( Auth::User()->id == $details->group_owner ) {
				$this->sendNotification( $group, 'grouprequests_accepted', $details->group_owner, $details->user_id );
				return redirect( URL_USERS_DASHBOARD_USER );
			} else {
				$this->sendNotification( $group, 'groupinvitation_accepted', $details->user_id, $details->group_owner );
				return redirect( URL_STUDENT_DASHBOARD_GROUP . $details->slug );
			}
		} elseif ( 'remove' === $action ) {
			DB::table('lmsgroups_users')->where( $invitation )->delete();
			return redirect( URL_USERS_DASHBOARD_USER );
		}
	}
	
	public function showGroupCourses( $group_slug, $course_slug = '' )
	{
		if( ! Auth::check() )
		{
			prepareBlockUserMessage();
			return back();
		}
		
		$group_details = App\LMSGroups::getRecordWithSlug( $group_slug );
		
		if ( empty( $group_details ) ) {
			prepareBlockUserMessage( 'group_not_found' );
			return back();
		}
		
		if ( ! empty( $course_slug ) ) {
			$course_details = App\LmsSeries::getRecordWithSlug( $course_slug );
			if ( empty( $course_details ) ) {
				prepareBlockUserMessage( 'course_not_found' );
				return back();
			}
			$data['course_details'] = $course_details;
		}
		
		$data['active_class']       = 'lms';
        $data['title']              = getPhrase('courses');
        if ( checkRole(getUserGrade(5)) ) {
			$data['layout']              = getLayout( 'exams' );
		} else {
			$data['layout']              = getLayout();
		}
		$data['group_slug'] = $group_slug;
		$data['group_details'] = $group_details;
		
		if ( empty( $course_slug ) ) {
			return view('lms-groups.courses', $data);
		} else {
			return view('lms-forntview.other-views.show-course', $data);
		}
	}
	
	public function addGroupCourses2( $group_slug )
	{
		if( ! Auth::check() )
       {
            prepareBlockUserMessage();
            return back();
        }

    	/**
    	 * Get the Quiz Id with the slug
    	 * Get the available questions from questionbank_quizzes table
    	 * Load view with this data
    	 */
		$record = LmsGroups::getRecordWithSlug($group_slug);
    	$data['record']         	= $record;
    	$data['active_class']       = 'lms';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'lms-groups.right-bar-update-courses';
		// $data['pathways']       	= array_pluck(App\Subject::all(),'subject_title', 'id');
		if ( 'facilitator' === get_current_user_special_role() ) {
			/**
			 * Facilitator: can start a group using the PathwayStart material.
			 */
			$data['pathways']      = array_pluck(App\Subject::where('id', '=', PATHWAY_START_ID)->get(),'subject_title', 'id');
		} else {
			$data['pathways']      = array_pluck(App\Subject::all(),'subject_title', 'id');
		}
		$data['categories']       	= array_pluck(App\QuizCategory::where('category_status', '=', 'active')->get(),'category', 'id');
        $data['settings']           = FALSE;
        $previous_records = array();
		// dd( $record );
        if($record->total_courses > 0)
        {
            $series = DB::table('lmsgroups_contents AS lgc')
			->select(['lmsseries.*', 'lgc.group_id', 'lgc.content_id', 'qc.category'])
			->join('lmsseries', 'lmsseries.id', '=', 'lgc.content_id')
			->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
			->where('group_id', '=', $record->id)
			->where('lgc.content_type', '=', 'course')
			->where('lmsseries.status', '=', 'active')
			->where('qc.category_status', '=', 'active')
			->get()
			;
			

            foreach($series as $r)
            {
                $temp = array();
                $temp['id'] 	= $r->content_id;
              // dd($series_details);
                $temp['content_type'] = $r->category;
                $temp['code'] 		 = $r->total_items;
                $temp['title'] 		 = $r->title;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
        }


    	$data['exam_categories']       	= array_pluck(App\QuizCategory::all(),
    									'category', 'id');

    	// $data['categories']       	= array_pluck(QuizCategory::all(), 'category', 'id');
    	$data['title']              = getPhrase('update_courses_for').' '.$record->title;
    	return view('lms-groups.update-courses', $data);
	}
	
	public function storeGroupCourses(Request $request, $slug)
    {	
    	
        if( ! Auth::check() )
        {
            prepareBlockUserMessage();
            return back();
        }

        $lms_group = LmsGroups::getRecordWithSlug($slug); 

        $group_id  = $lms_group->id;
        $contents  	= json_decode($request->saved_series);
       
        $contents_to_update = array();
        foreach ($contents as $record) {
            $temp = array();
            $temp['content_id'] = $record->id;
            $temp['group_id'] = $group_id;
			$temp['content_type'] = 'course';
            array_push($contents_to_update, $temp);
            
        }
		$lms_group->total_courses = count($contents_to_update);
        $lms_group->total_items = $lms_group->total_posts + $lms_group->total_lessons + count($contents_to_update);
		        
		//Clear all previous questions
		DB::table('lmsgroups_contents')
			->where('group_id', '=', $group_id)
			->where('content_type', '=', 'course')
			->delete();
		//Insert New Questions
		DB::table('lmsgroups_contents')->insert($contents_to_update);
		$lms_group->save();
        
        flash('success','courses_updated_successfully', 'success');
		$redirect_to = URL_STUDENT_DASHBOARD_GROUP . $lms_group->slug;
        return redirect( $redirect_to );
    }
	
	/**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable( $group_slug = '' )
    {
      if( ! Auth::check() )
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();
		$parent_id = 0;
	   $records = App\LmsSeries::select(['lmsseries.title', 'lmsseries.sub_title', 'lmsseries.image', 'lmsseries.is_paid', 'lmsseries.cost', 'lmsseries.validity',  'lmsseries.total_items','lmsseries.slug', 'lmsseries.id', 'lmsseries.updated_at', 'lmsseries.lms_category_id', 'lmsseries.subject_id', 'lmsseries.parent_id', 'lmsseries.lms_series_master_id', 'lmsseries.privacy', 'lmsseries.status' ])
		// ->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')	   
		->join('lmsgroups_contents AS lgc', 'lgc.content_id', '=', 'lmsseries.id')
		->join('lmsgroups AS lg', 'lg.id', '=', 'lgc.group_id')
		->where( 'parent_id', '=', $parent_id )
		->where( 'lgc.content_type', '=', 'course' )
		->where('lg.slug', '=', $group_slug)
		->where( 'lmsseries.status', '=', 'active' )
		// ->where( 'qc.category_status', '=', 'active' )
		->orderBy('lmsseries.updated_at', 'desc');

        return Datatables::of($records)
        
        ->editColumn('title', function($records) use( $group_slug )
        {
        	// $str = '<a href="'.URL_FRONTEND_LMSSERIES.$records->slug.'">'.$records->title.'</a>';
			
			$str = '<a href="'.URL_LMS_SHOW_GROUP_COURSES . $group_slug . '/' . $records->slug.'">'.$records->title.'</a>';
			
			if ( ! is_group_members_slug( $group_slug ) ) {
				$str = '<a href="#" onclick="showMessage(\'Please join this group to continue\');">'.$records->title.'</a>';
			}
			
			if ( $records->sub_title != '' ) {
				$str .= '<br><small>' . $records->sub_title . '</small>';
			}
			$cat = '<br>' . getPhrase( 'category: ' ) . App\QuizCategory::where( 'id', $records->lms_category_id )->value('category');
			$sub = '<br>' . getPhrase( 'pathway: ' ) . App\Subject::where( 'id', $records->subject_id )->value('subject_title');			
			$course = '';
			
			if ( lmsmode() == 'series' ) {
			$series = '<br>' . getPhrase( 'series: ' ) . App\LmsSeriesMaster::where( 'id', $records->lms_series_master_id )->value('title');
			} else {
				$series = '';
			}
			$course .= '<br>' . getPhrase( 'status: ' ) . ucfirst( $records->status );
			return $str . $sub . $cat . $series . $course;
        })
        ->editColumn('image', function($records)
        {
          $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
          if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_LMS_SERIES.$records->image;

            return '<img src="'.$image_path.'" height="60" width="60"  />';
        })
        ->editColumn('total_items', function( $records ) use( $parent_id )
        {
           $str = getPhrase( 'lessons: ' ) . $records->total_items;
		   
		   if ( $parent_id == '0' ) {
			   $modules = App\LmsSeries::where( 'parent_id', '=', $records->id )->count();
			   $str .= '<br>' . getPhrase( 'modules: ' ) . $modules;
		   }
		   return $str;
        })
                
        ->removeColumn( 'id' )
		->removeColumn( 'sub_title' )
        ->removeColumn( 'slug' )
        ->removeColumn( 'updated_at' )
		->removeColumn( 'lms_category_id' )
		->removeColumn( 'subject_id' )
		->removeColumn( 'cost' )
        ->removeColumn( 'validity' )
		->removeColumn( 'is_paid' )
		->removeColumn( 'lms_series_master_id' )
		->removeColumn( 'parent_id' )
		->removeColumn( 'privacy' )
		->removeColumn( 'status' )
        ->make();
    }
	
	public function getComments( $group_slug )
	{
		$records = App\LmsComments::select( ['lmscontents_comments.comments_notes', 'users.image', 'users.name', 'lmscontents_comments.created_at'] )
				->join( 'users', 'users.id', '=', 'lmscontents_comments.user_id' )
				->join( 'lmsgroups', 'lmsgroups.id', '=', 'lmscontents_comments.group_id' )
				->where( 'lmsgroups.slug', '=', $group_slug )
				->where('type', '=', 'groupcomments')
				->where('users.status', '=', 'activated')
				->orderBy( 'lmscontents_comments.created_at', 'desc' )
						;
		return Datatables::of($records)
        
        ->editColumn('name', function($records)
        {
			$image = getProfilePath($records->image);
			$str = '<li class="media"><img class="d-flex mr-3 icn-size" src="'.$image.'"   title="'.$records->name.'"/>&nbsp;' . $records->comments_notes . '&nbsp;<small>'.$records->created_at->diffForHumans().'</small></li>';
			return $str;
        })                
        ->removeColumn( 'comments_notes' )
		->removeColumn( 'image' )
		->removeColumn('created_at')
        ->make();
	}
	
	public function saveComments( Request $request, $slug )
	{
		$record = array(
			'content_id' => 0,
			'user_id' => get_current_user_id(),
			'comments_notes' => $request->modal_commnets,
			'type' => 'groupcomments',
			'group_id' => $request->modal_item_id,
		);
		$to = array_pluck( group_details( 'accepted', array('group_id' => $record['group_id'] ) )->get(), 'participant_id' );
				
		DB::table('lmscontents_comments')->insert( $record );
		
		if ( $request->has('message_to_members') ) {
			// sendMessage( $from, $to, $subject, $message )
			$subject = getPhrase('Message from coach');
			$message = $request->modal_commnets;
			$to = array_pluck( group_details( 'accepted', array('group_id' => $record['group_id'] ) )->get(), 'participant_id' );
			sendMessage( get_current_user_id(), $to, $subject, $message );
		}

		flash('Success!', 'Commented submitted successfully.', 'success');
		return redirect( URL_ADMIN_ALL_LMSGROUPS . '/' . $slug );
	}
	
	// Posts in Groups
	public function showGroupPosts( $group_slug, $post_slug = '' )
	{
		if( ! Auth::check() )
		{
			prepareBlockUserMessage();
			return back();
		}
		
		$group_details = App\LMSGroups::select(['lmsgroups.*', 'u.name AS owner_name'])->join('users AS u', 'u.id', '=', 'lmsgroups.user_id')
		->where('u.status', '=', 'activated')
		->where('lmsgroups.group_status', '=', 'active')
		->where('lmsgroups.slug', '=', $group_slug)
		->first();
		//getRecordWithSlug( $group_slug );
		
		if ( empty( $group_details ) ) {
			prepareBlockUserMessage( 'group_not_found' );
			return back();
		}
		
		if ( ! empty( $post_slug ) ) {
			$post_details = Post::published()->where('post_name', '=', $post_slug)->first();
			if ( empty( $post_details ) ) {
				prepareBlockUserMessage( 'post_not_found' );
				return back();
			}
			$data['post_details'] = $post_details;
		}
		
		
		$data['active_class']       = 'lms';
        $data['title']              = getPhrase('posts');
        if ( checkRole(getUserGrade(5)) ) {
			$data['layout']              = getLayout( 'exams' );
		} else {
			$data['layout']              = getLayout();
		}
		$data['group_slug'] = $group_slug;
		$data['group_details'] = $group_details;
		
		if ( empty( $course_slug ) ) {
			return view('lms-groups.posts', $data);
		} else {
			return view('lms-forntview.other-views.show-course', $data);
		}
	}
	
	/**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatablePosts( $group_slug = '' )
    {
      if( ! Auth::check() )
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();
		$parent_id = 0;
		$records = DB::table(TBL_WP_POSTS)->select(['post_title', TBL_WP_POSTS . '.ID AS id', 'post_name AS slug'])
		->join('lmsgroups_contents AS lgc', 'lgc.content_id', '=', TBL_WP_POSTS . '.ID')
		->join('lmsgroups AS lg', 'lg.id', '=', 'lgc.group_id')
		->where('post_status', '=', 'publish')
		->where('lgc.content_type', '=', 'post')
		->where('post_type', '=', 'post')
		->where('lg.slug', '=', $group_slug)
		;
		// dd( $records->toSql() );
		
        return Datatables::of($records)
        
		->addColumn('image', function($records)
        {
			$image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
			$themnail = \Corcel\Model\Post::find($records->id)->meta->_thumbnail_id;
			//print_r($themnail);
			if($themnail) {
			   $image_path = \Corcel\Model\Post::find($themnail)->guid;
			}
			return '<img src="'.$image_path.'" height="60" width="60"  />';
        })
		/*->addColumn('action', function($records) use( $group_slug )
        {
			$group_details = LmsGroups::where('slug', '=', $group_slug)->first();
			return '<a onclick="addToBag(' . $records->ID . ', ' . $group_details->id . ');" class="btn btn-primary" id="post_'.$records->ID.'">' . getPhrase('add') . '</a>';
		})*/
        ->editColumn('post_title', function($records) use( $group_slug )
        {	
			$target = HOST . $records->slug;
			$str = '<a href="' . $target .'" target="_blank">'.$records->post_title.'</a>';
			if ( ! is_group_members_slug( $group_slug ) ) {
				$str = '<a href="#" onclick="showMessage(\'Please join this group to continue\');">'.$records->post_title.'</a>';
			}
			
			$meta = \Corcel\Model\Post::find($records->id)->meta;
			$sub = '';
			if ( ! empty( $meta->pathway ) ) {
			$sub = '<br>' . getPhrase( 'pathway: ' ) . ucfirst( $meta->pathway );
			}
			return $str . $sub;
        })        
		->removeColumn('id')
		//->removeColumn('image')
		->removeColumn('slug')
        ->make();
    }
	
	public function addGroupPosts( $group_slug )
	{
		if( ! Auth::check() )
       {
            prepareBlockUserMessage();
            return back();
        }

    	/**
    	 * Get the Quiz Id with the slug
    	 * Get the available questions from questionbank_quizzes table
    	 * Load view with this data
    	 */
		$record = LmsGroups::getRecordWithSlug($group_slug);
    	$data['record']         	= $record;
    	$data['active_class']       = 'lms';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'lms-groups.right-bar-update-posts';
		// $data['pathways']       	= array_pluck(App\Subject::all(),'subject_title', 'id');
		if ( 'facilitator' === get_current_user_special_role() ) {
			/**
			 * Facilitator: can start a group using the PathwayStart material.
			 */
			$data['pathways']      = array_pluck(App\Subject::where('id', '=', PATHWAY_START_ID)->get(),'subject_title', 'id');
		} else {
			$data['pathways']      = array_pluck(App\Subject::all(),'subject_title', 'id');
		}
		$cat = \Corcel\Model\Taxonomy::where('taxonomy', 'category')->with('posts')->get();
		$categories = array();
		if ( $cat->count() > 0 ) {
			foreach( $cat as $category ) {
				$categories[ $category->term_id ] = $category->name;
			}
		}
		
		$data['categories']       	= $categories;
        $data['settings']           = FALSE;
        $previous_records = array();
		// dd( $record );
        if($record->total_courses > 0)
        {
            $series = DB::table('lmsgroups_contents AS lgc')
			->select([TBL_WP_POSTS . '.*', 'lgc.group_id', 'lgc.content_id', 'lmsgroups.total_posts AS total_items'])
			->join(TBL_WP_POSTS, TBL_WP_POSTS . '.ID', '=', 'lgc.content_id')	
			->join('lmsgroups', 'lmsgroups.id', '=', 'lgc.group_id')
			->where('lgc.group_id', '=', $record->id)
			->where('lgc.content_type', '=', 'post')
			->where(TBL_WP_POSTS . '.post_status', '=', 'publish')			
			->get()
			;
			

            foreach($series as $r)
            {
                $temp = array();
                $temp['id'] 	= $r->content_id;
				$meta = \Corcel\Model\Post::find($r->ID)->meta;
				$pathway = '-';
				if ( ! empty( $meta->pathway ) ) {
					$pathway = $meta->pathway;
				}
                $temp['content_type'] = $pathway;
                $temp['code'] 		 = $r->total_items;
                $temp['title'] 		 = $r->post_title;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
		// dd($settings);
        }


    	$data['exam_categories']       	= array_pluck(App\QuizCategory::all(),
    									'category', 'id');

    	// $data['categories']       	= array_pluck(QuizCategory::all(), 'category', 'id');
    	$data['title']              = getPhrase('update_posts_for').' '.$record->title;
    	return view('lms-groups.update-posts', $data);
	}
	
	public function storeGroupPosts(Request $request, $slug)
    {	
    	
        if( ! Auth::check() )
        {
            prepareBlockUserMessage();
            return back();
        }

        $lms_group = LmsGroups::getRecordWithSlug($slug); 

        $group_id  = $lms_group->id;
        $contents  	= json_decode($request->saved_series);
       
        $contents_to_update = array();
        foreach ($contents as $record) {
            $temp = array();
            $temp['content_id'] = $record->id;
            $temp['group_id'] = $group_id;
			$temp['content_type'] = 'post';
            array_push($contents_to_update, $temp);
            
        }
		$lms_group->total_posts = count($contents_to_update);
        $lms_group->total_items = $lms_group->total_lessons + $lms_group->total_courses + count($contents_to_update);
		        
		//Clear all previous questions
		DB::table('lmsgroups_contents')
			->where('group_id', '=', $group_id)
			->where('content_type', '=', 'post')
			->delete();
		//Insert New Questions
		DB::table('lmsgroups_contents')->insert($contents_to_update);
		$lms_group->save();
        
        flash('success','posts_updated_successfully', 'success');
		$redirect_to = URL_STUDENT_DASHBOARD_GROUP . $lms_group->slug;
        return redirect( $redirect_to );
    }
	
}
