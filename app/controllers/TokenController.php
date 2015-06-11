<?php

class TokenController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Token Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	
	|
	*/
	
	public function generateRandomPassword($length = 10) {
		$alphabets = range('A','Z');
		$numbers = range('0','9');
		$additional_characters = array('_','-','X');
		$final_array = array_merge($alphabets,$numbers,$additional_characters);
			 
		$password = '';
	  
		while($length--) {
		  $key = array_rand($final_array);
		  $password .= $final_array[$key];
		}
		return $password;
	}

	public function createAuthToken()
	{
		$data1 = App::make('ApiController')->get_data();
		
		if (array_key_exists("api_key", $data1))
		{
   		 	$api_key = $data1->api_key;
		}
		else
		{
			 $api_key = '';
		}
		
		if (array_key_exists("api_secret", $data1))
		{
   		 	$api_secret = $data1->api_secret;
		}
		else
		{
			 $api_secret = '';
		}
		
		if (array_key_exists("email", $data1))
		{
   		 	$email = $data1->email;
		}
		else
		{
			 $email = '';
		}
		
		if (array_key_exists("password", $data1))
		{
   		 	$password = $data1->password;
		}
		else
		{
			 $password = '';
		}
		
		if (array_key_exists("timestamp", $data1))
		{
   		 	$timestamp = $data1->timestamp;
		}
		else
		{
			 $timestamp = '';
		}

		$validate_data = array(
			'api_key' 		=> $api_key,
			'api_secret' 	=> $api_secret,
			//'email' 		=> $email,
			//'password' 		=> $password,
			'timestamp' 	=> $timestamp,
		);
		
		$rules = array(
			'api_key' 		=> 'required',
			'api_secret' 	=> 'required',
			//'email' 		=> 'required',
			//'password' 		=> 'required',
			'timestamp' 	=> 'required|validatetimestamp',
		);
		
		$messages = array(
			'api_key.required' => 'Api Key is required.',
			'api_secret.required' => 'Api secret is required.',
			//'email.required' => 'Email is required.',
			//'password.required' => 'Password is required.',
			'timestamp.required' => 'Timestamp is required.',
			'timestamp.validatetimestamp' => 'You dont have permission to access this page. Time stamp error'
		);
		
		
		$validator = Validator::make($validate_data,$rules,$messages);
		 
		if($validator->fails()){

			$result['status_code']=201;
			$err ='';
			$i=0;
			foreach ($validator->messages()->getMessages() as $field_name => $messages){
				$i++;
				$err .= $i.'). '.$messages[0]."     "; // messages are retrieved (publicly)
			}
			$result['status_message']=trim($err,",");
			$json_result = str_replace('null','""',json_encode($result));
			echo $json_result;
			exit;
			
		} else {
			$authenticate = 0;
			$user_ip_check = 0;
			$result = array();
			$auth_status=Token::get_api_info($email,$password,$api_key,$api_secret);
			//echo "<pre>";
			
			if(count($auth_status) > 0){
				if(isset($auth_status->expire_auth_time) && ($auth_status->expire_auth_time != '0000-00-00 00:00:00')){
					$currentdate=strtotime(date("Y-m-d h:i:s",time()));
					$expirydate=strtotime($auth_status->expire_auth_time);
					if($currentdate > $expirydate){
						$authenticate = 0;
						$message = 'Auth Key has expired !!';
					} else {
						$user_id=$auth_status->id;
						$api_ip_allowed=Token::check_ip_allowed($user_id);
						$user_ip_check = 1;
					}
				} else {
					$user_id=$auth_status->id;
					$api_ip_allowed=Token::check_ip_allowed($user_id);
					$user_ip_check = 1;
				}
			} else {
				$authenticate = 0;
				$message = 'Authentication failed. NO user found';
			}
			
			if($user_ip_check == 1){
				if(isset($api_ip_allowed->id) && $api_ip_allowed->id != ''){
					$ip_address = $api_ip_allowed->ip_address;
					$allowed_ip_address = '122.173.134.80';
					if($ip_address != ''){
						if (strpos($ip_address,',') !== false) {
							$split_ip_address = explode(',',$ip_address);
							if (in_array($_SERVER['SERVER_ADDR'], $ip_array)) {
								$authenticate = 1;
							} else {
								//invalid
								$authenticate = 0;
								$message = 'IP address not allowed to access API';
							}
						//} else if($ip_address == $_SERVER['SERVER_ADDR']){
						} else if($ip_address == $allowed_ip_address){
							$authenticate = 1;
						} else {
							$authenticate = 0;
							$message = 'IP address not allowed to access API';
						}
					} else {
						$authenticate = 1;
					}
				} else {
					$authenticate = 0;
					$message = 'IP address not allowed to access API';
				}
			}
			
			if($authenticate == 1){
				$date=date('Y-m-d H:i:s');
				$current_date = strtotime($date);//Current Time
				$expire_time = $current_date+(TOKEN_EXPIRE);//Expire time 
				$access_token=$this->generateRandomPassword('40');
				$insert_token = Token::insert_token($user_id,$access_token,$date);
				
				if(isset($insert_token) && $insert_token != ''){
					$result['status_code']=200;
					$result['status_message']='OK';
					$result['access_token']=$access_token;
					$result['expire_time']=$expire_time;
				} else {
					$result['status_code']=201;
					$result['status_message']='Error creating new token';
					
				}
			} else {
				$result['status_code']=201;
				$result['status_message']=$message;
			}
			$json_result = str_replace('null','""',json_encode($result));
			echo $json_result;
			exit;
		}
	}

}
