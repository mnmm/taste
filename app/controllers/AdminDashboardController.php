<?php

class AdminDashboardController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| DashboardController
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	
	|
	*/
	
	public function viewdashboard() {
		echo  Request::path();exit;
		return View::make('index');
	}

}
