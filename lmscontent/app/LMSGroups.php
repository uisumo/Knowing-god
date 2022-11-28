<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class LMSGroups extends Model
{
	protected $table = 'lmsgroups';  

	public static function getRecordWithSlug( $slug )
	{
		return LMSGroups::where( 'slug', '=', $slug )->first();
	}

    /**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public function getContents( $group_id )
    {
        return DB::table('lmsgroups_contents')
          ->join('lmscontents', 'lmscontents.id', '=', 'lmsgroups_contents.content_id')
            ->where('group_id', '=', $group_id )
			->get();
    }
}
