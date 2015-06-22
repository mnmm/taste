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
	
	public static function get_all_vendors(){
		$sortby=",invite_status asc,name";
		$order="asc";
		$result = DB::select( DB::raw("SELECT users.*, user_invite.status as invite_status, user_invite.created_date as sent_date from users LEFT JOIN user_invite on users.email = user_invite.email where users.usertype = 2 order by users.status asc $sortby $order"));   
		return $result;
	}
	
	//function to get user details
	public static function get_user_complete_details($userid) 
	{
   		 $result = DB::table('users')->where('id', $userid)->get();
		 return $result;
    }
	
	public static function add_vendor_account($createAccountArr){
			
			$check_vendor_account = DB::table('users')->where('email',$createAccountArr['email_address'])->first();
			if(isset($check_vendor_account->id) && $check_vendor_account->id != ''){
				$reg_id = 0;
			} else {
				$token=time();
				$register = new Register;
				$register->name = $createAccountArr['fullname'];
				$register->email = $createAccountArr['email_address'];
				$register->password = Hash::make('123456');
				$register->address = $createAccountArr['address'];
				$register->city = $createAccountArr['city'];
				$register->state = $createAccountArr['state'];
				$register->zip = $createAccountArr['zip'];
				$register->phone = $createAccountArr['phone'];
				$register->usertype = 2;
				$register->status =  1;
				$register->save();
				$reg_id = $register->id;
				
				DB::table('user_invite')->insert(array( 'email' => $createAccountArr['email_address'],'status' => 2,'created_date'=>date('Y-m-d H:i:s'))); 
				$user_id  = Auth::user()->id; 
				$user_details = User::get_user_complete_details($user_id);
				if(count($user_details) > 0){
					$username = $user_details[0]->name;
				}
				
				$sub = 'created a new vendor';
				$url_sent = Request::root().'/invite';
				$message = 'with email <a href="javascript:void(0);">'.$createAccountArr['email_address'].'</a>';
				Logs::sendadminnotes($sub,$message,$user_id,2);
			}
			
			return $reg_id;
			
	  }
	  
	  // Function to send invite Email to vendor 
	public function send_invite_email($email,$message)
    {
		$user_detail  = DB::table('user_invite')->where('email',$email)->first();
		
		$user = array(
			'email'=>$email,
			'name'=>'Vendor'
		);
		
		$loginurl = Request::root().'/InvitedUser/'.base64_encode($user_detail->id);
		
		$user_email_details  = DB::table('users')->where('email',$email)->first();
		$user_type = $user_email_details->usertype;
		
		if(isset($data['message']))
		{
			$msg = $data['message'];
			if (preg_match("/invitelink/", $msg)){
				
				$string = $msg;
				$newurl = $loginurl;
				$pattern = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";
				$msg1 = preg_replace($pattern,$newurl,$string);
				
			
			}
			else
			{
				$msg1 = $msg;
			}
		}
		
		
		// the data that will be passed into the mail view blade template
		
		
		$data = array(
			'detail'=>'Your Invation details are as below',
			'loginlink' => $loginurl,
			'email' => $email,
			'custommessage'=> $msg1
		);

		
		Mail::send('emails.invite', $data, function($message) use ($user)
		{
			$message->from('noreply@gfoodtrucks.com', 'Taste');
			$message->to($user['email'], $user['name'])->cc(INFO_EMAIL)->subject('GFood Invite Email');
		});
		
		$sub = 'sent invitation ';
		
		if($user_type==1)
		{
			$url_sent = Request::root().'/admin/managevendors';
		}
		else 
		{
			$url_sent = Request::root().'/admin/manageusers';
		}	
		
		$message = 'to <a href="'.$url_sent.'">'.$user_email_details->email.'</a>';
		Logs::sendadminnotes($sub,$message,Auth::user()->id,3);
		
		DB::table('user_invite')->where('email',$email)->update(array('status'=>3,'created_date'=>date('Y-m-d H:i:s')));
		echo 1;exit;
	}
	  
}
