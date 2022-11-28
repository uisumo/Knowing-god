<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Request;
use \App;
use App\Http\Requests;
use App\LmsContent;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Exception;
class LmsContentController extends Controller
{
    
    public function __construct()
    {
    	$this->middleware('auth');
    }

    protected  $examSettings;

    public function setSettings()
    {
        $this->examSettings = getSettings('lms');
    }

    public function getSettings()
    {
        return $this->examSettings;
    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index( $group_slug = '' )
    {
       if(!checkRole(getUserGrade(2)) && !checkRole(getUserGrade(5)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $data['active_class']       = 'lms';
        $data['title']              = getPhrase('lessons');
        if ( checkRole(getUserGrade(5)) ) {
			$data['layout']              = getLayout( 'exams' );
		} else {
			$data['layout']              = getLayout();
		}
		$data['group_slug'] = $group_slug;
		$data['group_details'] = FALSE;
		if ( ! empty( $group_slug ) ) {
			$data['group_details'] = App\LMSGroups::getRecordWithSlug( $group_slug );
		}
    	return view('lms.lmscontents.list', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable( $group_slug = '' )
    {
	  if( ! checkRole(getUserGrade(2)) && ! checkRole(getUserGrade(5) ) )
      {
        prepareBlockUserMessage();
        return back();
      }

    $records = LmsContent::join('subjects', 'lmscontents.subject_id', '=', 'subjects.id');
	$fields = array( 'lmscontents.title','lmscontents.image','lmscontents.content_type', 'subjects.subject_title','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at'	
	);
	if ( ! empty( $group_slug ) ) {
		$records = $records->join( 'lmsgroups_contents AS lgc', 'lgc.content_id', '=', 'lmscontents.id' )
		->join( 'lmsgroups AS lg', 'lg.id', '=', 'lgc.group_id' )
		->where( 'lg.slug', '=', $group_slug )
		->where( 'lmscontents.lesson_status', '=', 'active' )
		->where( 'lgc.content_type', '=', 'lesson' )
		;
		array_push( $fields, 'lg.title AS group_title' );
		array_push( $fields, 'lg.slug AS group_slug' );
	}
	$records = $records->select( $fields )
            ->orderBy('updated_at','desc');
	// echo $records->toSql();die();
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
		$return = $return->editColumn('title', function($record) use( $group_slug ) {			
			if ( ! empty( $group_slug ) && ! is_group_members_slug( $group_slug ) ) {
				return '<a href="#" onclick="showMessage(\'Please join this group to continue\');">'.$record->title.'</a>';
			} else {
				return '<a href="' . URL_FRONTEND_GROUP_LMSSINGLELESSON . $record->group_slug . '/' . $record->slug . '">' . $record->title.'</a>';
			}	
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
	
	/**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatableSlug( $group_slug )
    {
      if(!checkRole(getUserGrade(2)) && !checkRole(getUserGrade(5)))
      {
        prepareBlockUserMessage();
        return back();
      }

    $records = LmsContent::join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
    		->select(['lmscontents.title','lmscontents.image','lmscontents.content_type', 'subjects.subject_title','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at' ])
            ->orderBy('updated_at','desc')
            ;
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
			return '<a href="' . URL_FRONTEND_GROUP_LMSSINGLELESSON . $record->slug . '">'.$record->title.'</a>';
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
    	$data['subjects']       	= array_pluck(App\Subject::all(), 'subject_title', 'id');
		$contents = array_pluck( LmsContent::all(), 'title', 'id' );
		$data['quizzes']       	= array_pluck(App\Quiz::all(), 'title', 'id');
		$contents = array_pluck( LmsContent::all(), 'title', 'id' );
		// array_prepend( $contents, 'Please select', '0' );
		$data['contents']           = $contents;		
        $data['title']              = getPhrase('add_lesson');
    	$data['layout']              = getLayout();

    	return view('lms.lmscontents.add-edit', $data);
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
    	$record = LmsContent::getRecordWithSlug($slug);
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);
		
		$data['record']         	= $record;
    	$data['title']       		= getPhrase('edit').' '.$record->title;
    	$data['active_class']       = 'lms';
    	$data['subjects']           = array_pluck(App\Subject::all(), 'subject_title', 'id');
		$data['quizzes']       	= array_pluck(App\Quiz::all(), 'title', 'id');
		$data['contents']           = array_pluck( LmsContent::all(), 'title', 'id' );
    	$data['settings']           = json_encode($record);
        $data['layout']              = getLayout();
    	return view('lms.lmscontents.add-edit', $data);
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

    	$record = LmsContent::getRecordWithSlug($slug);
		
		$upload_max_filesize = ini_get('upload_max_filesize') . 'B';
		$upload_max_filesize_kilobytes = preg_replace('/\D/', '', $upload_max_filesize) * 1024;
		
		$rules = [
         'subject_id'                   => 'required|integer' ,
         'title'                        => 'required|max:60' ,
         //'content_type'                 => 'required',
         'code'                         => 'required|unique:lmscontents,code,'.$record->id,
		 // 'image' => 'required|mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes,
        ];
        $file_path = $record->file_path;
		
        switch ($request->content_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    if($request->file_path)
                        $file_path = $request->file_path;
                break;
            case 'file' :
                   if($request->file_path)
                    $file_path = $request->lms_file;
                break;
            case 'video' :
                    if($request->file_path)
                    $file_path = $request->lms_file;
                break;
            case 'audio' :
                    if($request->file_path)
                    $file_path = $request->lms_file;
                break;
        }
		
		// Video File
		$file_path_video = '';
        switch ($request->video_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    if ( empty( $record->file_path_video ) ) {
						$rules['file_path_video'] = 'required';
					}
                    $file_path_video = $request->file_path_video;
                break;
            case 'file' :
                     if ( empty( $record->lms_file_video ) ) {
						$rules['lms_file_video'] = 'required';
					 }
					 if ($request->hasFile('lms_file_video'))
					{
						$rules['lms_file_video'] = 'required|mimetypes:video/webm,video/x-f4v,video/x-fli,video/x-flv,video/x-m4v,video/x-matroska,video/x-mng,video/x-ms-asf,video/x-ms-vob,video/x-ms-wm,video/x-ms-wmv,video/x-ms-wmx,video/x-ms-wvx,video/x-msvideo,video/x-sgi-movie,video/x-smv,x-conference/x-cooltalk|max:' . $upload_max_filesize_kilobytes;
					}
                    $file_path_video = $request->lms_file_video;
                break;
            case 'video' :
                      if ( empty( $record->lms_file_video ) ) {
						$rules['lms_file_video'] = 'required';
					  }
                    $file_path_video = $request->lms_file_video;
                break;
            case 'audio' :
                    if ( empty( $record->lms_file_video ) ) {
						$rules['lms_file_video'] = 'required';
					}
                    $file_path_video = $request->lms_file_video;
                break;
            case 'iframe' : 
                    $rules['file_path_video'] = 'required';
                    $file_path_video = $request->file_path_video;
        }
		
		
		if ($request->hasFile('image'))
        {
			$rules['image'] = 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
		}
		// Audio File
		if ($request->hasFile('lms_file'))
        {
			// $rules['lms_file'] = 'mimetypes:audio/mpeg|max:' . $upload_max_filesize_kilobytes;
			$rules['lms_file'] = 'mimetypes:audio/mpeg,audio/3gpp,audio/3gpp2,audio/amr,audio/mp4,audio/ogg,audio/s3m,audio/silk,audio/vnd.dece.audio,audio/vnd.digital-winds,audio/vnd.rip,audio/x-wav|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('video_background_image'))
        {
			$rules['video_background_image'] = 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('file_word'))
        {
			$rules['file_word'] = 'mimes:doc,docx|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('file_ppt'))
        {
			$rules['file_ppt'] = 'mimes:ppt,pptx|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('file_pdf'))
        {
			$rules['file_pdf'] = 'mimes:pdf|max:' . $upload_max_filesize_kilobytes;
		}

		$customMessages = [
			'content_type.required' => 'Please select Audio Type',
			'subject_id.required' => 'Please select pathway',
			
			'image.mimes' => 'Please upload valid image. Eg: jpeg,jpg,png,gif',
			'image.max' => 'Please upload a image should be less than ' . $upload_max_filesize,
			
			'lms_file.required' => 'Please upload audio file. Eg: mp3, 3gp, wav',
			'lms_file.mimes' => 'Please upload valid audio file. Eg: mp3, 3gp, wav',
			'lms_file.max' => 'Please upload a audio file should be less than ' . $upload_max_filesize,
			
			'lms_file_video.required' => 'Please upload video file. Eg: avi,flv,wmv,mov,mp4,3gp',
			'lms_file_video.mimes' => 'Please upload valid video file. Eg: avi,flv,wmv,mov,mp4,3gp files only',
			'lms_file_video.max' => 'Please upload a video file should be less than ' . $upload_max_filesize,
			
			'video_background_image.required' => 'Please upload Video Background Image. Eg: jpeg,jpg,png,gif',
			'video_background_image.mimes' => 'Please upload valid Video Background Image. Eg: jpeg,jpg,png,gif',
			'video_background_image.max' => 'Please upload a Video Background Image should be less than ' . $upload_max_filesize,
			
			'file_word.required' => 'Please upload Word file. Eg: doc, docx',
			'file_word.mimes' => 'Please upload valid Word file. Eg: doc, docx',
			'file_word.max' => 'Please upload a Word file should be less than ' . $upload_max_filesize,
			
			'file_ppt.required' => 'Please upload PPT file. Eg: ppt, pptx',
			'file_ppt.mimes' => 'Please upload valid PPT file. Eg: ppt, pptx',
			'file_ppt.max' => 'Please upload a PPT file should be less than ' . $upload_max_filesize,
			
			'file_pdf.required' => 'Please upload PDF file. Eg: pdf',
			'file_pdf.mimes' => 'Please upload valid PDF file. Eg: pdf',
			'file_pdf.max' => 'Please upload a PDF file should be less than ' . $upload_max_filesize,
		];
        
        $this->validate( $request, $rules, $customMessages );
         DB::beginTransaction();
       try{
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name, TRUE);
      
    	$name  						=  $request->title;
		$record->title 				= $name;
		if ( $request->has('reference') ) {
			$record->reference	= $request->reference;
		}
		if ( $request->has('content_sub_title') ) {
			$record->content_sub_title	= $request->content_sub_title;
		}
		if ( $request->has('lesson_status') ) {
			$record->lesson_status	= $request->lesson_status;
		}
        if ( ! empty( $request->parent_id ) ) {
			$record->parent_id = $request->parent_id;
		} else {
			$record->parent_id = 0;
		}
       
        $record->subject_id         = $request->subject_id;
        $record->code               = $request->code;
        $record->content_type       = $request->content_type;
        
        $record->file_path          = $file_path;
		
		// Video File
		$record->video_type 		= $request->video_type;
		$record->file_path_video 	= $file_path_video;
		$record->help_text 		= $request->help_text;
		if ( ! empty( $request->quiz_id ) ) {
			$record->quiz_id 		= $request->quiz_id;
		}
		
        $record->description        = $request->description;
        $record->record_updated_by  = Auth::user()->id;

        $record->save();
         $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
            $this->setSettings();
            $examSettings = $this->getSettings();
            $path = $examSettings->contentImagepath;
            $this->deleteFile($record->image, $path);

			$record->image      = $this->processUpload($request, $record,$file_name);

			$record->save();
        }

         $file_name = 'lms_file';
        if ($request->hasFile($file_name))
        {

            $this->setSettings();
            $examSettings = $this->getSettings();
            $path = $examSettings->contentImagepath;
            $this->deleteFile($record->file_path, $path);

              $record->file_path      = $this->processUpload($request, $record,$file_name, FALSE);
              
              $record->save();
        }
		
		// Video File
		$file_name = 'lms_file_video';
        if ($request->hasFile($file_name))
        {			
			$rules = array( $file_name => 'mimetypes:video/webm,video/x-f4v,video/x-fli,video/x-flv,video/x-m4v,video/x-matroska,video/x-mng,video/x-ms-asf,video/x-ms-vob,video/x-ms-wm,video/x-ms-wmv,video/x-ms-wmx,video/x-ms-wvx,video/x-msvideo,video/x-sgi-movie,video/x-smv,x-conference/x-cooltalk|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->lms_file_video, $path);
			$record->lms_file_video      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// Video Background Image
		$file_name = 'video_background_image';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->video_background_image, $path);
			$record->video_background_image = $this->processUpload($request, $record, $file_name, TRUE, '900x400');
			$record->save();
		}
		
		// Word File
		$file_name = 'file_word';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:doc,docx|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->file_word, $path);
			$record->file_word      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// PPT File
		$file_name = 'file_ppt';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:ppt,pptx|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->file_ppt, $path);
			$record->file_ppt      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// PDF File
		$file_name = 'file_pdf';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:pdf|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->file_pdf, $path);
			$record->file_pdf      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// Let us add this lesson to a course / module
		if ( $request->module_id > 0 ) {
			$contents_to_update = array(
				'lmsseries_id' => $request->module_id,
				'lmscontent_id' => $record->id,
			);
			$check = DB::table('lmsseries_data')->where( $contents_to_update );
			if ( $check->count() == 0 ) {
				DB::table('lmsseries_data')->insert( $contents_to_update );
			}
		} elseif ( $request->course_id > 0 ) {
			$contents_to_update = array(
				'lmsseries_id' => $request->course_id,
				'lmscontent_id' => $record->id,
			);
			$check = DB::table('lmsseries_data')->where( $contents_to_update );
			if ( $check->count() == 0 ) {
				DB::table('lmsseries_data')->insert( $contents_to_update );
			}
		}
        DB::commit();
        flash('success','record_updated_successfully', 'success');

    }  catch(Exception $e)
     {
        DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {			
          flash('oops...!',$e->getMessage(), 'error');
       }
       else {
          flash('oops...!','improper_data_file_submitted', 'error');
       }
     }
    	return redirect(URL_LMS_CONTENT);
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
    	 
		 $upload_max_filesize = ini_get('upload_max_filesize') . 'B';
		$upload_max_filesize_kilobytes = preg_replace('/\D/', '', $upload_max_filesize) * 1024;
		
	    $rules = [
         'subject_id'          	        => 'required|integer' ,
         'title'          	   			=> 'required|max:60' ,
         // 'content_type'                 => 'required',
         'code'         => 'required|unique:lmscontents', 
		'image' => 'required|mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes,		 
        ];
        $file_path = '';
        switch ($request->content_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    $rules['file_path'] = 'required';
                    $file_path = $request->file_path;
                break;
            case 'file' :
                     $rules['lms_file'] = 'required';
                    $file_path = $request->lms_file;
                break;
            case 'video' :
                      $rules['lms_file'] = 'required';
                    $file_path = $request->lms_file;
                break;
            case 'audio' :
                    $rules['lms_file'] = 'required';
                    $file_path = $request->lms_file;
                break;
            case 'iframe' : 
                    $rules['file_path'] = 'required';
                    $file_path = $request->file_path;
        }
		// dd($request->file('lms_file')->getMimeType());
		
		// Video File
		$file_path_video = '';
        switch ($request->video_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    $rules['file_path_video'] = 'required';
                    $file_path_video = $request->file_path_video;
                break;
            case 'file' :
                     $rules['lms_file_video'] = 'required';
					 if ($request->hasFile('lms_file_video'))
					{
						$rules['lms_file_video'] = 'required|mimetypes:video/webm,video/x-f4v,video/x-fli,video/x-flv,video/x-m4v,video/x-matroska,video/x-mng,video/x-ms-asf,video/x-ms-vob,video/x-ms-wm,video/x-ms-wmv,video/x-ms-wmx,video/x-ms-wvx,video/x-msvideo,video/x-sgi-movie,video/x-smv,x-conference/x-cooltalk|max:' . $upload_max_filesize_kilobytes;
					}
                    $file_path_video = $request->lms_file_video;
                break;
            case 'video' :
                      $rules['lms_file_video'] = 'required';
                    $file_path_video = $request->lms_file_video;
                break;
            case 'audio' :
                    $rules['lms_file_video'] = 'required';
                    $file_path_video = $request->lms_file_video;
                break;
            case 'iframe' : 
                    $rules['file_path_video'] = 'required';
                    $file_path_video = $request->file_path_video;
        }
		if ($request->hasFile('image'))
        {
			$rules['image'] = 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
		}
		// Audio File
		if ($request->hasFile('lms_file'))
        {
			// $rules['lms_file'] = 'mimes:mp3,3gp,wav|max:' . $upload_max_filesize_kilobytes;
			$rules['lms_file'] = 'mimetypes:audio/mpeg,audio/3gpp,audio/3gpp2,audio/amr,audio/mp4,audio/ogg,audio/s3m,audio/silk,audio/vnd.dece.audio,audio/vnd.digital-winds,audio/vnd.rip,audio/x-wav|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('video_background_image'))
        {
			$rules['video_background_image'] = 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('file_word'))
        {
			$rules['file_word'] = 'mimes:doc,docx|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('file_ppt'))
        {
			$rules['file_ppt'] = 'mimes:ppt,pptx|max:' . $upload_max_filesize_kilobytes;
		}
		
		if ($request->hasFile('file_pdf'))
        {
			$rules['file_pdf'] = 'mimes:pdf|max:' . $upload_max_filesize_kilobytes;
		}
		$customMessages = [
			'content_type.required' => 'Please select Audio Type',
			'subject_id.required' => 'Please select pathway',
			
			'image.mimes' => 'Please upload valid image. Eg: jpeg,jpg,png,gif',
			'image.max' => 'Please upload a image should be less than ' . $upload_max_filesize,
			
			'lms_file.required' => 'Please upload audio file. Eg: mp3, 3gp, wav',
			'lms_file.mimes' => 'Please upload valid audio file. Eg: mp3, 3gp, wav',
			'lms_file.max' => 'Please upload a audio file should be less than ' . $upload_max_filesize,
			
			'lms_file_video.required' => 'Please upload video file. Eg: avi,flv,wmv,mov,mp4,3gp',
			'lms_file_video.mimes' => 'Please upload valid video file. Eg: avi,flv,wmv,mov,mp4,3gp files only',
			'lms_file_video.max' => 'Please upload a video file should be less than ' . $upload_max_filesize,
			
			'video_background_image.required' => 'Please upload Video Background Image. Eg: jpeg,jpg,png,gif',
			'video_background_image.mimes' => 'Please upload valid Video Background Image. Eg: jpeg,jpg,png,gif',
			'video_background_image.max' => 'Please upload a Video Background Image should be less than ' . $upload_max_filesize,
			
			'file_word.required' => 'Please upload Word file. Eg: doc, docx',
			'file_word.mimes' => 'Please upload valid Word file. Eg: doc, docx',
			'file_word.max' => 'Please upload a Word file should be less than ' . $upload_max_filesize,
			
			'file_ppt.required' => 'Please upload PPT file. Eg: ppt, pptx',
			'file_ppt.mimes' => 'Please upload valid PPT file. Eg: ppt, pptx',
			'file_ppt.max' => 'Please upload a PPT file should be less than ' . $upload_max_filesize,
			
			'file_pdf.required' => 'Please upload PDF file. Eg: pdf',
			'file_pdf.mimes' => 'Please upload valid PDF file. Eg: pdf',
			'file_pdf.max' => 'Please upload a PDF file should be less than ' . $upload_max_filesize,
		];
        $this->validate( $request, $rules, $customMessages );

     DB::beginTransaction();
       try{
        $record = new LmsContent();
      	$name  						=  $request->title;
		$record->title 				= $name;
		if ( $request->has('reference') ) {
			$record->reference	= $request->reference;
		}
		if ( $request->has('content_sub_title') ) {
			$record->content_sub_title	= $request->content_sub_title;
		}
       	$record->slug 				= $record->makeSlug($name, TRUE);
        $record->subject_id         = $request->subject_id;
        $record->code               = $request->code;
       	$record->content_type 		= $request->content_type;
		if ( ! empty( $request->parent_id ) ) {
			$record->parent_id = $request->parent_id;
		} else {
			$record->parent_id = 0;
		}       	
       	$record->file_path 		   = $file_path;
		
		// Video File
		$record->video_type 	= $request->video_type;
		$record->file_path_video = $file_path_video;
		$record->help_text 		= $request->help_text;
		if ( ! empty( $request->quiz_id ) ) {
			$record->quiz_id 		= $request->quiz_id;
		}
		
        $record->description		= $request->description;
        $record->record_updated_by 	= Auth::user()->id;
		if ( $request->has('lesson_status') ) {
			$record->lesson_status	= $request->lesson_status;
		}
        
        $record->save();
		
 		 $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
		    $this->setSettings();
            $examSettings = $this->getSettings();
	        $path = $examSettings->contentImagepath;
	        $this->deleteFile($record->image, $path);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

        $file_name = 'lms_file';
        if ($request->hasFile($file_name))
        {
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->file_path, $path);
			$record->file_path      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// Video FIle
		$file_name = 'lms_file_video';
        if ($request->hasFile($file_name))
        {			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->lms_file_video, $path);
			$record->lms_file_video      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// Video Background Image
		$file_name = 'video_background_image';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->video_background_image, $path);
			$record->video_background_image = $this->processUpload($request, $record, $file_name, TRUE, '900x400');
			$record->save();
		}
		
		// Word File
		$file_name = 'file_word';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:doc,docx|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->file_word, $path);
			$record->file_word      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// PPT File
		$file_name = 'file_ppt';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:ppt,pptx|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->file_ppt, $path);
			$record->file_ppt      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}
		
		// PDF File
		$file_name = 'file_pdf';
        if ($request->hasFile($file_name))
        {
			$rules = array( $file_name => 'mimes:pdf|max:' . $upload_max_filesize_kilobytes );
            $this->validate($request, $rules);
			
			$this->setSettings();
			$examSettings = $this->getSettings();
			$path = $examSettings->contentImagepath;
			$this->deleteFile($record->file_pdf, $path);
			$record->file_pdf      = $this->processUpload($request, $record, $file_name, FALSE);
			$record->save();
		}

         DB::commit();
        flash('success','record_added_successfully', 'success');

    }
     catch( Exception $e)
     {
        DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_data_file_submitted', 'error');
       }
     }
        
    	return redirect(URL_LMS_CONTENT);
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
        $record = LmsContent::where('slug', $slug)->first();
        $this->setSettings();
        try{
            if(!env('DEMO_MODE')) {
                $examSettings = $this->getSettings();
                $path = $examSettings->contentImagepath;
                $this->deleteFile($record->image, $path);
                if($record->file_path!='')
                    $this->deleteFile($record->file_path, $path);
                $record->delete();
            }
            
            $response['status'] = 1;
            $response['message'] = getPhrase('category_deleted_successfully');
        }
        catch (\Illuminate\Database\QueryException $e) {
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
    	return URL_LMS_CONTENT;
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

     /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record, $file_name, $is_image = TRUE, $image_size = '' )
     {

        if(env('DEMO_MODE')) {
            return 'demo';
        }

         
         if ($request->hasFile($file_name)) {
          $settings = $this->getSettings();
          $destinationPath      = public_path( $settings->contentImagepath );
          $path = $_FILES[$file_name]['name'];
          $ext = pathinfo($path, PATHINFO_EXTENSION);

          $fileName = $record->id.'-'.$file_name.'.'.$ext; 
          
          $request->file($file_name)->move($destinationPath, $fileName);
         if($is_image){
			
			if ( empty( $image_size ) ) {				
				//Save Normal Image with 300x300
				Image::make($destinationPath.$fileName)->fit( $settings->imageSize )->save($destinationPath.$fileName);
				
				// 900x400
				$fileName_new = '900_400_' . $fileName;
				Image::make($destinationPath.$fileName)->fit( 900, 400 )->save($destinationPath.$fileName_new);
			} else {
				$image_size =  explode( 'x', $image_size );
				Image::make($destinationPath.$fileName)->fit( $image_size[0], $image_size[1] )->save($destinationPath.$fileName);
			}
         }
         return $fileName;
        }
        
     }
	 
	 public function getModules( Request $request )
	 {
		 $course_id = $request->course_id;
		 if ( $course_id > 0 ) {
			 $modules = array_pluck( App\LmsSeries::where( 'parent_id', '=', $course_id )->get(), 'title', 'id' );
			 if ( empty( $modules ) ) {
				 $modules = array(
					'0' => getPhrase( 'please_select' ),
				 );
			 } else {
				$modules = array_prepend( $modules, getPhrase( 'please_select' ), '0' );
			 }
		 } else {
			 $modules = array(
					'0' => getPhrase( 'please_select' ),
				 );
		 }
		 $modules_str = '';
		 foreach( $modules as $key => $val ) {
			 $modules_str .= '<option value="'.$key.'">'.$val.'</option>';
		 }
		 return json_encode( array( 'html' => $modules_str ) );
	 }
}
