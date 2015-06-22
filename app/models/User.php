<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
	
	
	public function getAllUsers(){
		$get_users = DB::table('users')->get();
		return $get_users;
	}
	
	public function get_all_vendors(){
		$sortby=",invite_status asc,first_name";
		$order="asc";
		$result = DB::select( DB::raw("SELECT users.*, user_invite.status as invite_status, user_invite.created_date as sent_date from users LEFT JOIN user_invite on users.email = user_invite.email where users.usertype = 2 order by users.status asc $sortby $order"));   
	}
}
