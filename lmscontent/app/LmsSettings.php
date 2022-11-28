<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsSettings extends Model
{
     protected  $settings = array(
     'categoryImagepath'        => "uploads/lms/categories/",
     'contentImagepath'     	=> "uploads/lms/content/",
     'seriesImagepath'          => "uploads/lms/series/",
     'seriesThumbImagepath'     => "uploads/lms/series/thumb/",
	 'groupsImagepath'        => "uploads/lms/groups/",
     'defaultCategoryImage'     => "default.png",
     'imageSize'                => 300,
	 'imageSizeWidth'             => 750,
	 'imageSizeHeight'             => 450,
     'examMaxFileSize'          => 10000,
	'content_types'            => array(
						'audio' => 'Audio File',
						'audio_url' => 'Audio URL',
						),
	'video_types' => array(
						'video' => 'Video File',
						'video_url' => 'Video URL',
						),
	'lessons_per_page'                => 4,
	
	'seriesMasterImagepath'          => "uploads/lms/series-master/",
	'seriesMasterThumbImagepath'     => "uploads/lms/series-master/thumb/",
     );

      

 

    /**
     * This method returns the settings related to Library System
     * @param  boolean $key [For specific setting ]
     * @return [json]       [description]
     */
    public function getSettings($key = FALSE)
    {
    	if($key && array_key_exists($key,$settings))
    		return json_encode($this->settings[$key]);
    	return json_encode($this->settings);
    }
}
