<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	//$laravel = app();
	//return "Your Laravel version is ".$laravel::VERSION;
	return View::make('hello');
});

Route::post('/create_auth_token','TokenController@createAuthToken');

Validator::extend('authenticatetoken', function($field,$value,$parameters){
	$check_token_access = DB::table('token_session')->where('token',$value)->first();
	
	if(isset($check_token_access->id) && $check_token_access->id != ''){
		
		$token_date = strtotime($check_token_access->date).'<br>';
		$current_date = strtotime(date('Y-m-d H:i:s')).'<br>';//Current Time
		$expire_time = $token_date+(TOKEN_EXPIRE).'<br>';//Expire time 
		
		if($expire_time<$current_date){
			return false;
		} else {
			$update_token = DB::table('token_session')->where('token','=',$value)->update(array('date' => date('Y-m-d H:i:s')));
			return true;
		}
	} else {
		return false;
	}
});

Validator::extend('validatetimestamp', function($field,$value,$parameters){
	
	if((time() - $value > TIMESTAMP_IN_SEC)){
		return false;
	} else {
		return true;
	}
});

Route::group(array('prefix' => 'api'), function() {
	Route::post('/checktokenauthentication', array('uses' => 'ApiController@checktokenauthentication'));
	Route::post('/generateinvoice', array('uses' => 'ApiController@generateinvoicefrompo'));
	Route::post('/searchorders', array('uses' => 'ApiController@searchorders'));
	Route::post('/sendpaymentlink', array('uses' => 'ApiController@sendpaymentlink'));
	Route::post('/getunpaidpo', array('uses' => 'ApiController@getunpaidpo'));
	Route::post('/gettotalunpaidamount', array('uses' => 'ApiController@gettotalunpaidamount'));
	Route::post('/getpodataforgraphs', array('uses' => 'ApiController@getpodataforgraphs'));
	Route::post('/login', array('uses' => 'ApiController@login'));
	Route::post('/logout', array('uses' => 'ApiController@logout'));
});

Route::get('/','AdminDashboardController@viewdashboard');
/*Route::post('/oauth/access_token','OAuthController@postAccessToken');
Route::group(array('prefix' => 'api'), function() {
	Route::post('/users/me', array('uses' => 'ApiController@getusers'));
	Route::post('/generateinvoice', array('uses' => 'ApiController@generateinvoicefrompo'));
});*/


// issue an access token to a post from a login form
// protect priviledged endpoints
/*Route::group(array('prefix' => 'api', 'before' => 'oauth'), function() {

    Route::get('users/me', array('uses' => 'UserController@doMe'));
    Route::post('circles/new', array('uses' => 'CircleController@newCircle'));
   
});*/


