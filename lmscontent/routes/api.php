<?php

use Illuminate\Http\Request;
use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('get-users', function()
{
    $users = User::myuser();
	 return response(array(
                'error' => false,
                'users' =>$users->toArray(),
               ),200); 
});
Route::get('articles', 'UsersController@loadUsers');
Route::get('lmsCategories', 'LmsCategoryController@getLmsCategories');
Route::post('login', 'Auth\LoginController@login');
Route::group(array('prefix' => 'api'), function() {
    Route::resource('restful-apis','UsersController@loadUsers');
});