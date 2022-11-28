<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
 
class LmsTrack extends Model  
{
    protected $table = 'lmscontents_track';

    public static function getRecordWithSlug($slug)
    {
        return LmsTrack::where('slug', '=', $slug)->first();
    }
	
	/**
	 * Returns the history of exam attempts based on the current logged in user
	 * @return [type] [description]
	 */
    public static function getStatus( $content_id, $type, $course_id = '', $module_id = '', $content_type = ''  )
    {
    	$track = LmsTrack::where('user_id', '=', Auth::user()->id)
			->where('content_id', $content_id)
			->where( 'type', $type );
		/**
		 * We no need to track each piece of conten in course level, which means If user has completed 'piece 1' content from a course/group if has completed from all places.
		 */
		if ( ! empty( $course_id ) ) {
			// $track = $track->where( 'course_id', '=', $course_id );
		}
		if ( ! empty( $module_id ) ) {
			// $track = $track->where( 'module_id', '=', $module_id );
		}
		if ( ! empty( $content_type ) ) {
			// $track = $track->where( 'content_type', '=', $content_type );
		}		
		$track = $track->first();
		return $track;
    }

    /**
     * Returns the current quiz user record
     * @return [type] [description]
     */
    public function getUser()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
}


