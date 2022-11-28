<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \App;
use App\Subject;
use App\LmsSeries;
use App\QuizCategory;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
class LmsSeriesController extends Controller
{
	 protected  $examSettings;
	 
	 public function __construct() 
    {
    	$this->middleware( 'auth' );
    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'lms';
		$data['parent_id'] = 0;
		if ( ! empty( $slug ) ) {
			$details = LmsSeries::getRecordWithSlug( $slug );
			$data['parent_id'] = $details->id;
		}
        $data['title']              = getPhrase('courses');
		$data['layout'] = getLayout();
    	return view('lms.lmsseries.list', $data);
    }
	
	/**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function modulesIndex( $course = '' )
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
	  
		$data['course'] = FALSE;
		$data['parent_id'] = 'modules';
		$data['title']              = getPhrase('modules');
		if ( ! empty( $course ) ) {
		$record = LmsSeries::getRecordWithSlug($course); 

		if($isValid = $this->isValidRecord($record))
			return redirect($isValid); 
		$data['course'] = $record;
		$data['parent_id'] = $record->id;
		$data['title']   = getPhrase('modules : ') . '<i>' . $record->title . '</i>';
		}
		
        $data['active_class']       = 'lms';
        $data['is_module']  = 'yes';
		$data['layout'] = getLayout();
		
    	return view('lms.lmsseries.list', $data);
    }
	
	/**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function specialCourses()
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'lms';
		$data['parent_id'] = 'special';
		if ( ! empty( $slug ) ) {
			$details = LmsSeries::getRecordWithSlug( $slug );
			$data['parent_id'] = $details->id;
		}
        $data['title']              = getPhrase('special_courses');
		$data['layout'] = getLayout();
    	return view('lms.lmsseries.list', $data);
    }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable( $parent_id = 0 )
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();

		if ( $parent_id == 'modules' ) {
			$records = LmsSeries::select(['title', 'sub_title', 'image', 'is_paid', 'cost', 'validity',  'total_items','slug', 'id', 'updated_at', 'lms_category_id', 'subject_id', 'parent_id', 'lms_series_master_id', 'privacy', 'status' ])
	   ->where( 'parent_id', '!=', '0' )
	   ->where( 'course_type', '=', 'regular' )
       ->orderBy('updated_at', 'desc');
		} elseif ( $parent_id == 'special' ) {
			$records = LmsSeries::select(['title', 'sub_title', 'image', 'is_paid', 'cost', 'validity',  'total_items','slug', 'id', 'updated_at', 'lms_category_id', 'subject_id', 'parent_id', 'lms_series_master_id', 'privacy', 'status' ])
		->where( 'parent_id', '=', '0' )
		->where( 'course_type', '=', 'special' )
       ->orderBy('updated_at', 'desc');
		} else {
		   $records = LmsSeries::select(['title', 'sub_title', 'image', 'is_paid', 'cost', 'validity',  'total_items','slug', 'id', 'updated_at', 'lms_category_id', 'subject_id', 'parent_id', 'lms_series_master_id', 'privacy', 'status' ])
		   ->where( 'parent_id', '=', $parent_id )
		   ->where( 'course_type', '=', 'regular' )
		   ->orderBy('updated_at', 'desc');
		}

        return Datatables::of($records)
        ->addColumn('action', function ($records ) use( $parent_id ) {
         $temp = '';
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="'.URL_LMS_SERIES_UPDATE_SERIES.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("update_lessons").'</a></li>
                            ';
                            
							if ( $parent_id === '0' ) {
							$temp .= ' <li><a href="' . URL_LMS_MODULES_ADD . '/' . $records->slug . '"><i class="fa fa-pencil"></i>'. getPhrase("add_module").'</a></li>';
							}
							
							$temp .= '<li><a href="'.URL_LMS_SERIES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                           
                           if(checkRole(getUserGrade(1))) {
							$temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
							}
                    
                    $temp .='</ul></div>';


                    $link_data .=$temp;
            return $link_data;
            })
        ->editColumn('title', function($records) use( $parent_id )
        {
        	$str = '<a href="'.URL_LMS_SERIES_UPDATE_SERIES.$records->slug.'">'.$records->title.'</a>';
			
			if ( $records->sub_title != '' ) {
				$str .= '<br><small>' . $records->sub_title . '</small>';
			}
			$cat = '<br>' . getPhrase( 'category: ' ) . App\QuizCategory::where( 'id', $records->lms_category_id )->value('category');
			$sub = '<br>' . getPhrase( 'pathway: ' ) . App\Subject::where( 'id', $records->subject_id )->value('subject_title');			
			$course = '';
			if ( $parent_id == 'modules' ) {
			$course = '<br>' . getPhrase( 'course: ' ) . App\LmsSeries::where( 'id', $records->parent_id )->value('title');
			}
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
			   $modules = LmsSeries::where( 'parent_id', '=', $records->id )->count();
			   $str .= '<br>' . getPhrase( 'modules: ' ) . '<a href="' . URL_LMS_MODULES . '/' . $records->slug . '">' . $modules . '</a>';
		   }
		   return $str;
        })
        ->editColumn('validity', function($records)
        {
           return ($records->is_paid) ? $records->validity : '-';
        })
        ->editColumn('is_paid', function($records)
        {
            return ($records->is_paid) ? '<span class="label label-primary">'.getPhrase('paid') .'</span>' : '<span class="label label-success">'.getPhrase('free').'</span>';
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

    /**
     * This method loads the create view
     * @return void
     */
    public function create( $course_type = 'regular' )
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$data['record']         	= FALSE;
    	$data['active_class']       = 'lms';
    	$data['categories']       	= array_pluck(App\QuizCategory::all(),'category', 'id');
		$data['subjects'] = array_pluck( App\Subject::all(), 'subject_title', 'id' );
		
		$data['course_type']       = $course_type;
		if ( 'special' === $course_type ) {
			$data['title']              = getPhrase('add_special_course');
		} else {
			$data['title']              = getPhrase('add_course');
		}
    	return view('lms.lmsseries.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]       
     */
    public function edit($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LmsSeries::getRecordWithSlug($slug);

    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);
    	$data['record']       	  = $record;
    	$data['active_class']     = 'lms';
    	$data['settings']         = FALSE;
    	$data['categories']       	= array_pluck(App\QuizCategory::all(),'category', 'id');
		$data['subjects'] = array_pluck( App\Subject::all(), 'subject_title', 'id' );
    	$data['title']            = getPhrase('edit_course');
		$parent_id = $record->id;
		$data['modules'] = LmsSeries::select(['title', 'sub_title', 'image', 'is_paid', 'cost', 'validity',  'total_items','slug', 'id', 'updated_at', 'lms_category_id', 'subject_id', 'parent_id', 'lms_series_master_id', 'privacy', 'status' ])
		   ->where( 'parent_id', '=', $parent_id )
		   ->where( 'course_type', '=', 'regular' )
		   ->orderBy('updated_at', 'desc')->get();

		$data['lessons'] = DB::table('lmsseries_data')->select( ['lmscontents.title','lmscontents.image','lmscontents.content_type', 'subjects.subject_title','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at'] )
                ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
                ->where('parent_id', '=', 0 )
            ->where('lmsseries_id', '=', $record->id )
            ->get();
		if ( $record->parent_id > 0 ) {
			$data['title']            = getPhrase('edit_module');
		}
		
		$data['parent_id'] = $record->parent_id;
		$tab = \Request::Input( 'tab', 'edit' );
		$data['tab'] = $tab;
		// dd($data['lessons']);
		
    	return view('lms.lmsseries.add-edit', $data);
    }
	
	/**
     * This method loads the create view
     * @return void
     */
    public function createModule( $course = '' )
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
	  
	  $data['title']              = getPhrase('add_module');
	  
	  if ( ! empty( $course ) ) {
		$record = LmsSeries::getRecordWithSlug($course); 

		if($isValid = $this->isValidRecord($record))
			return redirect($isValid); 
		$data['course'] = $record;
		$data['parent_id'] = $record->id;
		$data['title']   = getPhrase('add_module_for : ') . '<i>' . $record->title . '</i>';
		}
		
    	$data['record']         	= FALSE;
		$data['serieses'] = array();
    	$data['active_class']       = 'lms';
    	$data['categories']       	= array_pluck(App\QuizCategory::all(),'category', 'id');
		$data['subjects'] = array_pluck( App\Subject::all(), 'subject_title', 'id' );
		
		$data['is_module']  = 'yes';

      	
    	return view('lms.lmsseries.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]       
     */
    public function editModule($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LmsSeries::getRecordWithSlug($slug);

    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);
    	$data['record']       	  = $record;
		$data['serieses'] = array_pluck(App\LmsSeriesMaster::where( 'lms_category_id', '=', $record->lms_category_id )->get(),'title', 'id');
		
		$data['is_module']  = 'yes';
		
    	$data['active_class']     = 'lms';
    	$data['settings']         = FALSE;
    	$data['categories']       	= array_pluck(App\QuizCategory::all(),'category', 'id');
		$data['subjects'] = array_pluck( App\Subject::all(), 'subject_title', 'id' );
    	$data['title']            = getPhrase('edit_module');
    	return view('lms.lmsseries.add-edit', $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LmsSeries::getRecordWithSlug($slug);
		
		$upload_max_filesize = ini_get('upload_max_filesize') . 'B';
		$upload_max_filesize_kilobytes = preg_replace('/\D/', '', $upload_max_filesize) * 1024;
		
		if ( $record->parent_id > 0 ) {
			$rules = [
			'title'      => 'required|max:60',
			// 'image' => 'required',
			'short_description' => 'required|max:200' ,
			];
			if ($request->hasFile('image')) {
			  $rules['image'] = 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
		  }
		} else {
			$rules = [
				'title'      => 'required|max:60',
				'lms_category_id' => 'required',
				'subject_id' => 'required',
				// 'image' => 'required',
				'privacy' => 'required',
				'short_description' => 'required|max:200' ,
				];
			if ($request->hasFile('image')) {
			  $rules['image'] = 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
		  }
		}
        if ($request->has('parent_id')) {
			$rules['parent_id'] = 'required';
		}
		if ($request->has('lms_series_master_id')) {
			$rules['lms_series_master_id'] = 'required';
		}
		/**
        * Check if the title of the record is changed, 
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name, TRUE);
      
       //Validate the overall request
	   $customMessages = [
			'lms_category_id.required' => 'Category field is required',
			'subject_id.required' => 'Pathway field is required',
		];
       $this->validate($request, $rules, $customMessages );
    	$record->title 				= $name;
		$record->sub_title 				= $request->sub_title;
       	
        $record->is_paid			= $request->is_paid;
        
		if ( $record->parent_id > 0 ) {
			$parent = LmsSeries::where( 'id', '=', $request->parent_id )->first();
			if ( $parent ) {
				$record->lms_category_id	= $parent->lms_category_id;		
				$record->subject_id			= $parent->subject_id;
				$record->lms_series_master_id = $parent->lms_series_master_id;
			}
		} else {
		$record->lms_category_id	= $request->lms_category_id;		
		$record->subject_id			= $request->subject_id;
		}
		
		if ($request->has('parent_id')) {
			$record->parent_id	= $request->parent_id;
		}
		
		if ($request->has('privacy')) {
			$record->privacy	= $request->privacy;
		}
		
		if ($request->has('lms_series_master_id')) {
			$record->lms_series_master_id	= $request->lms_series_master_id;
		}
		$record->display_order		= $request->display_order;
        $record->validity			= -1;
        $record->cost				= 0;
        if($request->is_paid) {
        	$record->validity		= $request->validity;
        	$record->cost			= $request->cost;
			if ($request->has('free_for')) {
				$record->free_for = implode(',', $request->free_for);
			} else {
				$record->free_for = NULL;
			}
    	}
        $record->total_items		= $request->total_items;       

        $record->short_description	= $request->short_description;
        $record->description		= $request->description;
        $record->start_date   = $request->start_date;
        $record->end_date   = $request->end_date;
        $record->record_updated_by 	= Auth::user()->id;
		
		$record->status		= $request->status;
		
        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }
		
		$file_name = 'image_icon';
        if ($request->hasFile($file_name))
        {
            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image_icon, $path);
            $record->image_icon      = $this->processUpload($request, $record,$file_name, '128x128');
            $record->save();
        }
		
		
		
        flash('success','record_updated_successfully', 'success');
    	if ($request->has('parent_id')) {
			$url = URL_LMS_MODULES;
			$course = LmsSeries::where( 'id', '=', $record->parent_id )->first();
			if ( $course ) {
				$url .= '/' . $course->slug;
			}
			return redirect( $url );
		} else {			
			$url = URL_LMS_SERIES;
			
			if ( $record->parent_id > 0 ) {
				$url = URL_LMS_MODULES;
			}
			
			if ( 'special' === $record->course_type ) {
				return redirect( URL_SPECIAL_COURSES );
			} else {
				return redirect( $url );
			}
		}
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request, $slug = '' )
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
		$upload_max_filesize = ini_get('upload_max_filesize') . 'B';
		$upload_max_filesize_kilobytes = preg_replace('/\D/', '', $upload_max_filesize) * 1024;
		
		if ($request->has('parent_id')) {
			$rules = [
			'title'          	   => 'required|max:30' ,
			'parent_id' => 'required',
			'image' => 'required',
			'short_description' => 'required|max:200' ,
          ];
		  if ($request->hasFile('image')) {
			  $rules['image'] = 'required|mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
		  }
		} else {
			$rules = [
				'title'          	   => 'required|max:30',
				'lms_category_id' => 'required',
				'subject_id' => 'required',
				'image' => 'required',
				'privacy' => 'required',
				'short_description' => 'required|max:200' ,
			  ];
			  if ($request->hasFile('image')) {
				  $rules['image'] = 'required|mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
			  }
		}
		if ($request->has('lms_series_master_id')) {
			$rules['lms_series_master_id'] = 'required';
		}
        
        $customMessages = [
			'lms_category_id.required' => 'Category field is required',
			'subject_id.required' => 'Pathway field is required',
		];
		$this->validate($request, $rules, $customMessages );
	   
        $record = new LmsSeries();
      	$name  						=  $request->title;
		$record->title 				= $name;
		$record->sub_title 			= $request->sub_title;
       	$record->slug 				= $record->makeSlug($name, TRUE);
        $record->is_paid			= $request->is_paid;
        $record->validity			= -1;
        if ($request->has('parent_id')) {
			$parent = LmsSeries::where( 'id', '=', $request->parent_id )->first();
			if ( $parent ) {
				$record->lms_category_id	= $parent->lms_category_id;		
				$record->subject_id			= $parent->subject_id;
			}
		} else {
			$record->lms_category_id	= $request->lms_category_id;		
			$record->subject_id	= $request->subject_id;
		}
		if ($request->has('parent_id')) {
			$record->parent_id	= $request->parent_id;
		}
		if ($request->has('privacy')) {
			$record->privacy	= $request->privacy;
		}
		if ($request->has('lms_series_master_id')) {
			$record->lms_series_master_id	= $request->lms_series_master_id;
		}
		$record->display_order		= $request->display_order;
        $record->cost				= 0;
        if($request->is_paid) {
        	$record->validity		= $request->validity;
        	$record->cost			= $request->cost;
			if ($request->has('free_for')) {
				$record->free_for = implode(',', $request->free_for);
			} else {
				$record->free_for = NULL;
			}
    	}
        $record->total_items		= $request->total_items;
        $record->short_description	= $request->short_description;
        $record->description		= $request->description;
        $record->start_date   = $request->start_date;
        $record->end_date   = $request->end_date;
        $record->record_updated_by 	= Auth::user()->id;
		$record->created_by 	= Auth::user()->id;
		
		$record->status		 = $request->status;
		$record->course_type = $request->course_type;
		
        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {
            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }
		
		$file_name = 'image_icon';
        if ($request->hasFile($file_name))
        {
            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image_icon, $path);
            $record->image_icon      = $this->processUpload($request, $record,$file_name, '128x128' );
            $record->save();
        }
        flash('success','record_added_successfully', 'success');
		$course_type = $request->course_type;
    	if ($request->has('parent_id')) {
			$url = URL_LMS_MODULES;
			$course = LmsSeries::where( 'id', '=', $record->parent_id )->first();
			if ( $course ) {
				$url .= '/' . $course->slug;
			}
			return redirect( $url );
		} else {
			if ( 'special' === $course_type ) {
				return redirect( URL_SPECIAL_COURSES );
			} else {
				return redirect( URL_LMS_SERIES );
			}
		}
    }

    public function deleteFile($record, $path, $is_array = FALSE)
    {
      if(env('DEMO_MODE')) {
        return;
      }
        $files = array();
        $files[] = $path.$record;
        File::delete($files);
    }

    /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record, $file_name, $size = '' )
     {
      if(env('DEMO_MODE')) {
        return 'demo';
      }
         if ($request->hasFile($file_name)) {
          $examSettings = getSettings('lms');
            
            $imageObject = new ImageSettings();

          $destinationPath            = public_path( $examSettings->seriesImagepath );
          $destinationPathThumb       = public_path( $examSettings->seriesThumbImagepath );
          
          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
          
          $request->file($file_name)->move($destinationPath, $fileName);
         
         //Save Normal Image with resize
		 if ( ! empty( $size ) ) {
			 $parts = explode( 'x', $size );
			 Image::make($destinationPath.$fileName)->fit( $parts[0], $parts[1] )->save( $destinationPath . $fileName );
        

			Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
		 } else {
			 Image::make($destinationPath.$fileName)->fit( $examSettings->imageSizeWidth, $examSettings->imageSizeHeight )->save( $destinationPath . $fileName );
        

			Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
		 }
          
        return $fileName;

        }
     }
 
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean 
     */
    public function delete($slug, $type = 'ajax')
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = LmsSeries::where('slug', $slug)->first();
        if(!$record)
        {
          $response['status'] = 0;
          $response['message'] = getPhrase('invalid_record');  
          if ( $type == 'editcourse' ) {
			  flash('Oops',$response['message'], 'error');
			  return json_encode($response);
		  } else {
			return json_encode($response);
		  }
        }

        try{
        if(!env('DEMO_MODE')) {
          // Let us delete modules
		  LmsSeries::where('parent_id', '=', $record->id)->delete();
		  $record->delete();
        }
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
       if ( $type == 'editcourse' ) {
		  if ( $response['status'] == 1 ) {
			  flash('success',$response['message'], 'success');
		  } else {
			flash('Oops',$response['message'], 'error');
		  }
		  return json_encode($response);
	  } else {
		return json_encode($response);
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
    	return URL_LMS_SERIES;
    }


    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSeries(Request $request)
    {

    	$category_id 	= $request->category_id;
		$items = array();
		
		if ( 'course' === $request->type ) {
			$items = App\LmsSeries::select('lmsseries.*', 'qc.category', 'lmsseries.lms_category_id')
			->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
			->where('lmsseries.subject_id','=',$category_id)
			->where('parent_id', '=', '0')
			->where('qc.category_status','=','active')
			->where('lmsseries.status','=','active');
			if( checkRole(['student']) ) {
				$items = $items->where('is_paid', '=', '0');
			}
			$items = $items->get();
		} elseif ( 'posts' === $request->type ) {
			$pathway = 'pathwaystart';
			if ( PATHWAYFORWARD_ID == $category_id ) {
				$pathway = 'pathwayforward';
			}
			if ( PATHWAYFOREVER_ID == $category_id ) {
				$pathway = 'pathwayforever';
			}
			
			$items = \Corcel\Model\Post::select(['post_title AS title', 'ID AS id', 'post_name AS slug'])->hasMeta('pathway', $pathway)
			->where('post_status', '=', 'publish')
			->where('post_type', '=', 'post')
			->get();
			foreach( $items as $item ) {
				$item->category = $pathway;
			}
		} else {
			$items = App\LmsContent::where('subject_id','=',$category_id)->where('parent_id', '=', '0')->get();
		}
    	return json_encode(array('items'=>$items));
    }
    
    /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateSeries($slug)
    {

       if(!checkRole(getUserGrade(2)))
       {
            prepareBlockUserMessage();
            return back();
        }

    	/**
    	 * Get the Quiz Id with the slug
    	 * Get the available questions from questionbank_quizzes table
    	 * Load view with this data
    	 */
		$record = LmsSeries::getRecordWithSlug($slug); 
    	$data['record']         	= $record;
    	$data['active_class']       = 'lms';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'lms.lmsseries.right-bar-update-lmslist';
		if ( $record ) {
			$data['categories']       	= array_pluck(App\Subject::where('id', '=', $record->subject_id)->get(),'subject_title', 'id');
		} else {
			$data['categories']       	= array_pluck(App\Subject::all(),'subject_title', 'id');
		}     
        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_items > 0)
        {
            $series = DB::table('lmsseries_data')
                            ->where('lmsseries_id', '=', $record->id)
                            ->get();
            
            foreach($series as $r)
            {
                $temp = array();
                $temp['id'] 	= $r->lmscontent_id;
                $series_details = App\LmsContent::where('id', '=', $r->lmscontent_id)->first();
              // dd($series_details);
                $temp['content_type'] = $series_details->content_type;
                $temp['code'] 		  = $series_details->code;
                $temp['title'] 		  = $series_details->title;
				$temp['is_free'] 	  = $r->is_free;
                
                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
        }
        
        
    	$data['exam_categories']       	= array_pluck(App\QuizCategory::all(), 
    									'category', 'id');

    	// $data['categories']       	= array_pluck(QuizCategory::all(), 'category', 'id');
    	$data['title']              = getPhrase('update_lessons_for').' '.$record->title;
    	return view('lms.lmsseries.update-list', $data);

    }

    public function storeSeries(Request $request, $slug)
    {	
    	
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $lms_series = LmsSeries::getRecordWithSlug($slug); 

        $lmsseries_id  = $lms_series->id;
        $contents  	= json_decode($request->saved_series);
       
        $contents_to_update = array();
		$index = 0;
		$is_free = $request->is_free;
        foreach ($contents as $record) {
            $temp = array();
            $temp['lmscontent_id'] = $record->id;
            $temp['lmsseries_id'] = $lmsseries_id;
			$temp['is_free'] = $is_free[ $index++ ];
            array_push($contents_to_update, $temp);            
        }
		
        $lms_series->total_items = count($contents);
        if(!env('DEMO_MODE')) {
        //Clear all previous questions
        DB::table('lmsseries_data')->where('lmsseries_id', '=', $lmsseries_id)->delete();
        //Insert New Questions
        DB::table('lmsseries_data')->insert($contents_to_update);
          $lms_series->save();
        }
        flash('success','record_updated_successfully', 'success');
		$redirect_to = URL_LMS_SERIES;
		if ( $lms_series->parent_id > 0 ) {
			/*
			$module = LmsSeries::where( 'id', '=', $lms_series->parent_id )->first();
			$redirect_to = URL_LMS_MODULES . '/' . $module->slug;
			*/
			$redirect_to = URL_LMS_MODULES;
		}
		if ( 'special' === $lms_series->course_type ) {
			$redirect_to = URL_SPECIAL_COURSES;
		}
        return redirect( $redirect_to );
    }

    /**
     * This method lists all the available exam series for students
     * 
     * @return [type] [description]
     */
    public function listSeries()
    {
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('courses');
        $data['series']         = LmsSeries::paginate((new App\GeneralSettings())->getPageLength());
        $data['layout']              = getLayout();
       return view('student.exams.exam-series-list', $data);
    }

    /**
     * This method displays all the details of selected exam series
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function viewItem($slug)
    {

        $record = LmsSeries::getRecordWithSlug($slug); 
        
        if($isValid = $this->isValidRecord($record))
          return redirect($isValid);  

        $data['active_class']       = 'exams';
        $data['pay_by']             = '';
        $data['title']              = $record->title;
        $data['item']               = $record;
         $data['right_bar']          = TRUE;
          $data['right_bar_path']     = 'student.exams.exam-series-item-view-right-bar';
        $data['right_bar_data']     = array(
                                            'item' => $record,
                                            );
        $data['layout']              = getLayout();
       return view('student.exams.exam-series-view-item', $data);
    }
	
	/**
     * This method displays all the details of selected exam series
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function CourseLessons( $slug )
    {

        $record = LmsSeries::getRecordWithSlug($slug); 
        
        if($isValid = $this->isValidRecord($record))
          return redirect($isValid);  

        $data['active_class']       = 'exams';
        $data['pay_by']             = '';
        $data['title']              = $record->title;
        $data['record']               = $record;
        $data['layout']              = getLayout();
		$data['type'] = 'courselessons';
		
       return view('lms.lmsseries.course-lessons', $data);
    }
	
	public function setSettings()
    {
        $this->examSettings = getSettings('lms');
    }
	
	public function getCourseLessonsDatatable( $slug )
	{
		if( ! checkRole(getUserGrade(2)) && ! checkRole(getUserGrade(5) ) )
      {
        prepareBlockUserMessage();
        return back();
      }
	  
	  $fields = array( 'lmscontents.title','lmscontents.image','lmscontents.content_type', 'subjects.subject_title','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at'	
		);
		
		$records = App\LmsContent::join( 'lmsseries_data AS lsd', 'lsd.lmscontent_id', '=', 'lmscontents.id' )
		->join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
		
		->join( 'lmsseries AS ls', 'ls.id', '=', 'lsd.lmsseries_id' )
		->select( $fields )
		->where( 'ls.slug', '=', $slug )
        ->orderBy('updated_at','desc');
		
		$this->setSettings();
		$return =  Datatables::of($records);
		if( checkRole( getUserGrade(2) ) ) {
		$return = $return->addColumn('action', function ($record) {
				$extra = '<div class="dropdown more">
							<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="mdi mdi-dots-vertical"></i>
							</a>
							<ul class="dropdown-menu" aria-labelledby="dLabel">
								<li><a href="'.URL_LMS_CONTENT_EDIT.$record->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
								$temp = "";
								 if(checkRole(getUserGrade(1))){
						$temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$record->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
							}
							$extra .= $temp.'</ul></div>';
						return $extra;
				});
		}
		if( checkRole( getUserGrade(5) ) ) {
			$return = $return->editColumn('title', function($record){			
				//if ( ! empty( $record->group_slug ) ) {
					return '<a href="' . URL_FRONTEND_GROUP_LMSSINGLELESSON . $record->group_slug . '/' . $record->slug . '">'.$record->title.'</a>';
				//} else {
				//	return '-';
				//}			
			});
		}
		$return = $return->removeColumn('id')
		->removeColumn('updated_at')
		->removeColumn('slug')
		->editColumn('image', function($record){
			$image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
			if($record->image)
			$image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->image;
			return '<img src="'.$image_path.'" height="100" width="100" />';
		})
		->make();
			return $return;
		
	
	}

    
}
