<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
 
class UsersCompletedCourses extends Model  
{
    protected $table = 'users_completed_courses';
}