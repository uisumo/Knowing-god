<?php

namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Datatables;
use DB;
use Input;
use Excel;
class SpecialroleController extends Controller
{
    public $excel_data = '';   
    public function __construct()
    {
    	$this->middleware('auth');
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
        $data['active_class']       = 'specialroles';
        $data['title']              = getPhrase('specialroles_list');
    	return view('mastersettings.specialroles.list', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

        

         $records = DB::table('specialroles')->select([
         	'id','role_title', 'description', 'slug'])
         ->orderBy('updated_at','desc');
        
        return Datatables::of($records)
        ->addColumn('action', function ($records) {
         

            $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="'.URL_SPECIALROLE_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                            $temp = '';
                            if(checkRole(getUserGrade(1))) {
                               // $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                            }

                            $temp .='</ul> </div>';
                            $link_data .= $temp;
                    return $link_data;
            })

        ->removeColumn('slug')
        ->removeColumn('updated_at')
        ->make();
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
    	$data['active_class']       = 'specialroles';
    	$data['title']              = getPhrase('add_subject');
    	return view('mastersettings.specialroles.add-edit', $data);
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
    	$record = DB::table('specialroles')->where('slug', $slug)->first();
      $data['record']       		= $record;
    	$data['active_class']       = 'specialroles';
      $data['title']              = getPhrase('edit_subject');
    	return view('mastersettings.specialroles.add-edit', $data);
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
        $record    = DB::table('specialroles')->where('slug', $slug)->get()->first();
        
          $this->validate($request, [
       	 'role_title' => 'bail|required|max:40|unique:specialroles,role_title,' . $record->id,
        
         ]);
        DB::beginTransaction();
        try{

        $name 					        = $request->role_title;
       
       /**
        * Check if the title of the record is changed, 
        * if changed update the slug value based on the new title
        */
        $newrecord = array();
		if($name != $record->role_title)
        $newrecord['slug'] = strtolower( $name ) . ' - ' . str_random(20);
        $newrecord['role_title']	= $name;
        $newrecord['description'] = $request->description;
        DB::table('specialroles')->where('slug', $slug)->update( $newrecord );

    	flash('success','record_updated_successfully', 'success');

       DB::commit();
         
      }
     catch(Exception $e)
     {

      DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_data_in_the_question', 'error');
       }
     }

    	return redirect(URL_SPECIALROLE);
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

       $this->validate($request, [
         'role_title'          => 'bail|required|max:40',         
            ]);

         DB::beginTransaction();
      try {
    	$record = array() ;
        $name 					= $request->role_title;
        $record['role_title'] 	= $name;
        $record['slug']			= strtolower( $name ) . ' - ' . str_random(20);
        $record['description']	= $request->description;
		DB::table('specialroles')->insert( $record );
       DB::commit();
         flash('success','record_added_successfully', 'success');
      }
     catch(Exception $e)
     {

      DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_data_in_the_question', 'error');
       }
     }

      
    	return redirect(URL_SPECIALROLE);
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
        $record = DB::table('specialroles')->where('slug', $slug)->first();
        /**
         * Check if any topic exists in this subject
         * If topics are available, dont delete this subject
         */
       try {
          if(!env('DEMO_MODE')) {
           DB::table('specialroles')->where('slug', $slug)->delete();
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
}
