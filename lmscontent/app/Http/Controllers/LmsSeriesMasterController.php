<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \App;
use App\Subject;
use App\LmsSeriesMaster;
use App\QuizCategory;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
class LmsSeriesMasterController extends Controller
{
	 public function __construct() 
    {
    	// $this->middleware( 'auth' );
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
        $data['title']              = getPhrase('serieses');
		$data['layout']              = getLayout();
		
    	return view('lms.lmsseries_master.list', $data);
    }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable( $category = '' )
    {
		/*
      if(!checkRole(getUserGrade(2)) && !checkRole(getUserGrade(5)) )
      {
        prepareBlockUserMessage();
        return back();
      }
	  */

        $records = array();

		if ( ! empty( $category ) ) {
			$category_details = QuizCategory::getRecordWithSlug( $category );
			if ( $category_details ) {
				$records = LmsSeriesMaster::select(['title', 'sub_title', 'image', 'slug', 'id', 'updated_at', 'lms_category_id', 'subject_id' ])
				->where( 'lms_category_id', '=', $category_details->id )
				->orderBy('updated_at', 'desc');
			} else { // Trying hack
			$records = LmsSeriesMaster::select(['title', 'sub_title', 'image', 'slug', 'id', 'updated_at', 'lms_category_id', 'subject_id' ])
			->where( 'lms_category_id', '=', 0 )
			->orderBy('updated_at', 'desc');
			}
		} else {
		$records = LmsSeriesMaster::select(['title', 'sub_title', 'image', 'slug', 'id', 'updated_at', 'lms_category_id', 'subject_id' ])
		->orderBy('updated_at', 'desc');
		}

        $rows = Datatables::of($records);
        if( checkRole(getUserGrade(2)) ) {
			$rows = $rows->addColumn('action', function ($records) {         
				$link_data = '<div class="dropdown more">
				<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mdi mdi-dots-vertical"></i>
				</a>
				<ul class="dropdown-menu" aria-labelledby="dLabel">                           
				<li><a href="'.URL_LMS_SERIES_MASTER_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';

				$temp = '';
				if(checkRole(getUserGrade(1))) {
					$temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
				}
				$temp .='</ul></div>';
				$link_data .=$temp;
				return $link_data;
			});
		}
			else {
			$rows = $rows->addColumn('courses', function ($records) use( $category ) {				
				$count = App\LmsSeries::where( 'lms_series_master_id', '=', $records->id  )->count();
				if ( ! empty( $category ) ) {
					$temp = '<a href="'.URL_LMS_SHOW_COUSES. $category . '/'.$records->slug.'">'. $count.'</a>';
				} else {
					$temp = '<a href="'.URL_LMS_SHOW_COUSES.'">'. $count.'</a>';
				}
				return $temp;
			});
			}
        $rows = $rows->editColumn('title', function($records) use( $category )
        {
        	if( checkRole(getUserGrade(2)) ) {
				$str = '<a href="'.URL_LMS_SERIES_MASTER_UPDATE_SERIES.$records->slug.'">'.$records->title.'</a>';
			} else {
				if ( ! empty( $category ) ) {
					$str = '<a href="' . URL_LMS_SHOW_COUSES . $category . '/' . $records->slug . '">' . $records->title . '</a>';
				} else {
					$str = '<a href="'.URL_LMS_SHOW_COUSES.$records->slug.'">'.$records->title.'</a>';
				}
			}
			if ( $records->sub_title != '' ) {
				$str .= '<br><small>' . $records->sub_title . '</small>';
			}
			$cat = '<br>' . getPhrase( 'category: ' ) . App\QuizCategory::where( 'id', $records->lms_category_id )->value('category');
			$sub = '<br>' . getPhrase( 'pathway: ' ) . App\Subject::where( 'id', $records->subject_id )->value('subject_title');
			return $str . $cat . $sub;
        })
        ->editColumn('image', function($records)
        {
          $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
          if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_LMS_SERIES.$records->image;

            return '<img src="'.$image_path.'" height="60" width="60"  />';
        })        
        ->removeColumn( 'id' )
		->removeColumn( 'sub_title' )
        ->removeColumn( 'slug' )
        ->removeColumn( 'updated_at' )
		->removeColumn( 'lms_category_id' )
		->removeColumn( 'subject_id' )		
        ->make();
		return $rows;
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
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

      	$data['title']              = getPhrase('add_series');
    	return view('lms.lmsseries_master.add-edit', $data);
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

    	$record = LmsSeriesMaster::getRecordWithSlug($slug);

    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);
    	$data['record']       	  = $record;
    	$data['active_class']     = 'lms';
    	$data['settings']         = FALSE;
    	$data['categories']       	= array_pluck(App\QuizCategory::all(),'category', 'id');
		$data['subjects'] = array_pluck( App\Subject::all(), 'subject_title', 'id' );
    	$data['title']            = getPhrase('edit_series');
    	return view('lms.lmsseries_master.add-edit', $data);
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

    	$record = LmsSeriesMaster::getRecordWithSlug($slug);
		 $rules = [
			'title'      => 'bail|required|max:30',
			'lms_category_id' => 'bail|required',
			'subject_id' => 'bail|required',
			];
         /**
        * Check if the title of the record is changed, 
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name, TRUE);
      
       //Validate the overall request
       $this->validate($request, $rules);
    	$record->title 				= $name;
		$record->sub_title 				= $request->sub_title;
       	
        
        $record->lms_category_id			= $request->lms_category_id;
		$record->subject_id			= $request->subject_id;
		
        $record->short_description	= $request->short_description;
        $record->description		= $request->description;
        
        $record->record_updated_by 	= Auth::user()->id;
        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesMasterImagepath;
	        $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
            $record->save();
        }
        flash('success','record_updated_successfully', 'success');
    	return redirect(URL_LMS_SERIES_MASTER);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

	    $rules = [
         'title'          	   => 'bail|required|max:30' ,
         'lms_category_id' => 'bail|required',
			'subject_id' => 'bail|required',
          ];
          // dd($request);
        $this->validate($request, $rules);
        $record = new LmsSeriesMaster();
      	$name  						=  $request->title;
		$record->title 				= $name;
		$record->sub_title 			= $request->sub_title;
       	$record->slug 				= $record->makeSlug($name, TRUE);
       
        $record->lms_category_id	= $request->lms_category_id;
		$record->subject_id	= $request->subject_id;
		
        $record->short_description	= $request->short_description;
        $record->description		= $request->description;
        
        $record->record_updated_by 	= Auth::user()->id;
		$record->created_by 	= Auth::user()->id;
        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {
            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesMasterImagepath;
	        $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }
        flash('success','record_added_successfully', 'success');
    	return redirect(URL_LMS_SERIES_MASTER);
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
     public function processUpload(Request $request, $record, $file_name)
     {
      if(env('DEMO_MODE')) {
        return 'demo';
      }
         if ($request->hasFile($file_name)) {
          $examSettings = getSettings('lms');
            
            $imageObject = new ImageSettings();

          $destinationPath            = public_path( $examSettings->seriesMasterImagepath );
          $destinationPathThumb       = public_path( $examSettings->seriesMasterThumbImagepath );
          
          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
          
          $request->file($file_name)->move($destinationPath, $fileName);
         
         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit( $examSettings->imageSizeWidth, $examSettings->imageSizeHeight )->save( $destinationPath . $fileName );
        

           Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
        return $fileName;

        }
     }
 
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
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = LmsSeriesMaster::where('slug', $slug)->first();
        if(!$record)
        {
          $response['status'] = 0;
          $response['message'] = getPhrase('invalid_record');  
           return json_encode($response);
        }

        try{
        if(!env('DEMO_MODE')) {
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
       return json_encode($response);

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
    	return URL_LMS_SERIES_MASTER;
    }


    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSeries(Request $request)
    {

    	$category_id 	= $request->category_id;
    	$items 			= App\LmsContent::where('subject_id','=',$category_id)
                     
    				        ->get();
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
		$record = LmsSeriesMaster::getRecordWithSlug($slug); 
    	$data['record']         	= $record;
    	$data['active_class']       = 'lms';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'lms.lmsseries_master.right-bar-update-lmslist';
		$data['categories']       	= array_pluck(App\Subject::all(),'subject_title', 'id');        
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
                $temp['code'] 		 = $series_details->code;
                $temp['title'] 		 = $series_details->title;
                
                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
        }
        
        
    	$data['exam_categories']       	= array_pluck(App\QuizCategory::all(), 
    									'category', 'id');

    	// $data['categories']       	= array_pluck(QuizCategory::all(), 'category', 'id');
    	$data['title']              = getPhrase('update_course_for').' '.$record->title;
    	return view('lms.lmsseries_master.update-list', $data);

    }

    public function storeSeries(Request $request, $slug)
    {	
    	
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $lms_series = LmsSeriesMaster::getRecordWithSlug($slug); 

        $lmsseries_id  = $lms_series->id;
        $contents  	= json_decode($request->saved_series);
       
        $contents_to_update = array();
        foreach ($contents as $record) {
            $temp = array();
            $temp['lmscontent_id'] = $record->id;
            $temp['lmsseries_id'] = $lmsseries_id;
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
        return redirect(URL_LMS_SERIES_MASTER);
    }

    /**
     * This method lists all the available exam series for students
     * 
     * @return [type] [description]
     */
    public function listSeries()
    {
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('serieses');
        $data['series']         = LmsSeriesMaster::paginate((new App\GeneralSettings())->getPageLength());
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

        $record = LmsSeriesMaster::getRecordWithSlug($slug); 
        
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
	
	public function getSerieses( $category )
	{
		if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }


    	$list = LmsSeriesMaster::where( 'lms_category_id', '=', $category )->get();
    		 
    	$serieses =  array();
    	array_push($serieses, array('id'=>'', 'text' => getPhrase( 'Please select' ) ) );
		if ( $list->count() > 0 ) {
			foreach ( $list as $series ) {
				$r = array('id'=>$series->id, 'text' => $series->title);
				array_push($serieses, $r);
			}
		}    	
    	return json_encode($serieses);
	}

    
}
