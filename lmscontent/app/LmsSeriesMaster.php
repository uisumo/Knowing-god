<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class LmsSeriesMaster extends Model
{
   protected $table = 'lmsseries_master';

   
	public static function getRecordWithId($id)
    {
        return LmsSeriesMaster::where('id', '=', $id)->first();
    }
	
    public static function getRecordWithSlug($slug)
    {
        return LmsSeriesMaster::where('slug', '=', $slug)->first();
    }

    /**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public function getCourses( $parent_id = 0 )
    {
        return DB::table('lmsseries_data')
          ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
            ->where('lmsseries_id', '=', $this->id )
			// ->where( 'parent_id', '=', $parent_id )
			->get();
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
				->where('lmsseries_id', '=', $series_id )
				->where('lmscontents.id', '=', $content_id )
				->get();
		} else {
			return DB::table('lmsseries_data')
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->where('lmsseries_id', '=', $series_id )
				->get();
		}		
    }
	
	/**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public static function getPieces( $content_id )
    {
		return DB::table('lmscontents')
		->where('parent_id', '=', $content_id )
		->get();			
    }
}
