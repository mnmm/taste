<?php
	class Token extends Eloquent{
		
		public static function get_api_info($email='',$password='',$api_key,$api_secret){

			$password = Hash::make($password);
			$apiinfo = DB::table('users')
            ->leftjoin('developer_api_key', 'developer_api_key.user_id', '=', 'users.id')
			->select('users.*', 'developer_api_key.expire_auth_time')
			//->where('users.email', '=', $email)
			//->where('users.password', '=', $password)
          	->where('developer_api_key.api_key', '=', $api_key)
          	->where('developer_api_key.api_secret', '=', $api_secret)
			->where('developer_api_key.status', '=', 1)
			->where('users.status', '=', 1)
			->first();
			return $apiinfo;
		}
		
		public static function check_ip_allowed($user_id){

			$ip_allowed = DB::table('api_access_ip_url')
			->select('api_access_ip_url.*')
			->where('api_access_ip_url.user_id', '=', $user_id)
			->where('api_access_ip_url.status', '=', 1)
			->first();
			
			return $ip_allowed;
			
		}
		
		public static function insert_token($user_id,$access_token,$date){

			$insertTokenAr = array('user_id' => $user_id,'token' => $access_token, 'date'=>$date); 
			$id = DB::table('token_session')->insertGetId($insertTokenAr);
			return $id;
			
		}
		
		public static function create_authorization_link($vendorid,$actiontype,$vendoremail,$updateaccountemail){
			
			$time = time();
			$get_vendor_id = DB::table('taste_po')->select('vendor_id','vendor_name','vendor_email')->where('vendor_id','=',$vendorid)->first();
			
			if(isset($vendoremail) && $vendoremail != '' && isset($get_vendor_id->vendor_id) && $get_vendor_id->vendor_id != ''){
				if(isset($updateaccountemail) && $updateaccountemail != '' && $updateaccountemail == 1){
					$insertUpdateEmailAr = array('vendorid' => $get_vendor_id->vendor_id,'email' => $vendoremail); 
					$update_email_id = DB::table('updated_vendors_emails')->insertGetId($insertUpdateEmailAr);
					DB::table('taste_po')->where('vendor_id','=',$vendorid)->update(array('update_account_email_id' => $update_email_id));
				} 
				$email = $updateaccountemail;
			}  else {
				$email = $get_vendor_id->vendor_email;
			}
			echo $email;exit;
			
			if(isset($get_vendor_id->vendor_id) && $get_vendor_id->vendor_id != ''){
				
				$create_unique_token_code = $time.'@@@'.$get_vendor_id->vendor_id;
				$save_auth = base64_encode($create_unique_token_code);
				if($actiontype == 'requestinfo' ){
					$createaccount = 1;
				} else {
					$createaccount = 0;
				}
				$insertAuthAr = array('vendor_id' => $get_vendor_id->vendor_id,'auth_code' => $save_auth, 'status'=>1,'actiontype' => $createaccount); 
				$id = DB::table('payment_auth_code')->insertGetId($insertAuthAr);
				
				$auth_link = Request::root().'/#/vendor/'.$save_auth;
				$user = array(
					'email'=>$email,
					'name'=>$get_vendor_id->vendor_name
				);
				$data = array(
					'name' => $get_vendor_id->vendor_name,
					'auth_link' =>$auth_link,
				);
				$is_sent=Mail::send('emails.sendpaymentlink', $data, function($message) use ($user)
				{
					   $message->from('noreplay@gfoodtrucks.com', 'Taste');
					   $message->to($user['email'], $user['name'])->subject('Fill Your payment details here');
				});
				return $id;
			} else {
				return 0;
			}
			
		}

	}
?>
