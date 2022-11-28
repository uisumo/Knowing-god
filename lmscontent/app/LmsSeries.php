<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class LmsSeries extends Model
{
   protected $table = 'lmsseries';

   
	public static function getRecordWithId($id)
    {
        return LmsSeries::where('id', '=', $id)->first();
    }
	
    public static function getRecordWithSlug($slug)
    {
        return LmsSeries::where('slug', '=', $slug)->first();
    }

    /**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public function getContents( $parent_id = 0, $is_paginate = FALSE )
    {
        if ( $is_paginate ) {
			return DB::table('lmsseries_data')
          ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
            ->where('lmsseries_id', '=', $this->id )
			->where('lmscontents.lesson_status', '=', 'active' )
			// ->where( 'parent_id', '=', $parent_id )
			->paginate(LESSONS_ON_COURSE_PAGE);
		} else {
		return DB::table('lmsseries_data')
          ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
            ->where('lmsseries_id', '=', $this->id )
			->where('lmscontents.lesson_status', '=', 'active' )
			// ->where( 'parent_id', '=', $parent_id )
			->get();
		}
    }
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getAllContents( $series_id, $content_id = '' )
    {
        if ( ! empty( $content_id ) ) {
			return DB::table('lmsseries_data')
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->where('lmscontents.lesson_status', '=', 'active' )
				->where('lmsseries_id', '=', $series_id )
				->where('lmscontents.id', '=', $content_id )
				->get();
		} else {
			return DB::table('lmsseries_data')
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->where('lmscontents.lesson_status', '=', 'active' )
				->where('lmsseries_id', '=', $series_id )
				->get();
		}		
    }
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getAllParentLessons( $series_id, $parent_id = 0 )
    {
        return DB::table('lmsseries_data')
          ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
		  ->where('lmscontents.lesson_status', '=', 'active' )
            ->where('lmsseries_id', '=', $series_id )
			->where( 'parent_id', '=', $parent_id )
			->get();
    }
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getAllModuleParentLessons( $course_id, $module_id = 0 )
    {
        return DB::table('lmsseries_data')->select( 'lmscontents.*', 'lmsseries_data.*', 'lmscontents.created_at AS post_date' )
          ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
		  ->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data.lmsseries_id')
            ->where('lmsseries_id', '=', $module_id )
			->where('lmsseries.parent_id', '=', $course_id )
			->where( 'lmscontents.parent_id', '=', '0' )
			->where('lmscontents.lesson_status', '=', 'active' )
			->get();
    }
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getPieces( $content_id, $course_id = 0, $module_id = 0 )
    {
		return DB::table('lmscontents')->select( 'lmscontents.*', DB::raw( $module_id . ' as module_id', 'lmscontents.created_at AS post_date'), DB::raw( $course_id . ' as course_id') )
		->where('lmscontents.lesson_status', '=', 'active' )
		->where('parent_id', '=', $content_id )
		->get();			
    }
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getModules( $course_id )
    {
		return LmsSeries::where('parent_id', '=', $course_id)->where('status', '=', 'active')->get();			
    }
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getAllModuleLessons( $module_id, $content_id = '' )
    {
        if ( ! empty( $content_id ) ) {
			return DB::table('lmsseries_data')
				->select( 'lmscontents.*', 'module.id AS module_id', 'module.title AS module_title', 'module.sub_title AS module_sub_title',  'course.id AS course_id', 'course.title AS course_title', 'lmscontents.created_at AS post_date' )
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->join( 'lmsseries as module', 'module.id', '=', 'lmsseries_data.lmsseries_id' )
				->join( 'lmsseries as course', 'course.id', '=', 'module.parent_id' )
				->where('lmscontents.lesson_status', '=', 'active' )
				->where('lmsseries_id', '=', $module_id )
				->where('lmscontents.id', '=', $content_id )
				->get();
		} else {
			return DB::table('lmsseries_data')
				->select( 'lmscontents.*', 'module.id AS module_id', 'module.title AS module_title', 'module.sub_title AS module_sub_title',  'course.id AS course_id', 'course.title AS course_title', 'lmscontents.created_at AS post_date' )
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->join( 'lmsseries as module', 'module.id', '=', 'lmsseries_data.lmsseries_id' )
				->join( 'lmsseries as course', 'course.id', '=', 'module.parent_id' )
				->where('lmscontents.lesson_status', '=', 'active' )
				->where('lmsseries_data.lmsseries_id', '=', $module_id )
				->get();
		}		
    }
	
	public static function getAllLessons()
	{
		return DB::table('lmsseries_data')
				->select('lmscontents.*', 'lmscontents.created_at AS post_date')
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data.lmsseries_id')
				->join('quizcategories', 'quizcategories.id', '=', 'lmsseries.lms_category_id')
				->where('lmsseries.status', '=', 'active' )
				->where('quizcategories.category_status', '=', 'active' )
				->where('lmscontents.lesson_status', '=', 'active' )
				->get();
	}
	
	public static function getAllCourseLessons( $course_id )
	{
		return DB::table('lmsseries_data')
				->select('lmscontents.*', 'lmsseries_data.lmsseries_id', 'lmsseries.id AS course_id', 'lmsseries.parent_id AS module_id', 'lmscontents.created_at AS post_date')
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data.lmsseries_id')
				->join('quizcategories', 'quizcategories.id', '=', 'lmsseries.lms_category_id')
				/*
				->join('subjects', function( $join ) {
					$join->on( 'subjects.id', '=', 'quizcategories.subject_id')->on( 'subjects.id', '=', 'lmscontents.subject_id' );
				})
				*/
				
				->where('lmsseries.status', '=', 'active' )
				->where('quizcategories.category_status', '=', 'active' )
				->where('lmscontents.lesson_status', '=', 'active' )
				->where('lmsseries.id', '=', $course_id)
				->get();
	}
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getAllModuleAllLessons( $course_id, $module_id = 0 )
    {
        return DB::table('lmsseries_data')
			->select( 'lmscontents.*', 'lmsseries_data.lmsseries_id', 'lmsseries.id AS module_id', 'lmsseries.parent_id AS course_id', 'lmscontents.created_at AS post_date' )
			->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
			->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data.lmsseries_id')
			->join('quizcategories', 'quizcategories.id', '=', 'lmsseries.lms_category_id')
			->where('lmsseries_id', '=', $module_id )
			->where('lmsseries.parent_id', '=', $course_id )
			->where('lmsseries.status', '=', 'active' )
			->where('quizcategories.category_status', '=', 'active' )
			->where('lmscontents.lesson_status', '=', 'active' )
			->get();
    }
}
