<?php
include("lib/ptstripe.php");
class ApiController extends BaseController {

	
	public function getRealPOST($url) {
      $pairsurl = explode("?", $url);
     
      $vars = array();
      if(isset($pairsurl[1]) && strpos($pairsurl[1],'&') !== false){
		  $pairs = explode("&", $pairsurl[1]);
		 
		  foreach ($pairs as $pair) {
			  
			 $nv = explode("=", $pair);
			 $name = urldecode($nv[0]);
			 $value = urldecode($nv[1]);
			 $vars[$name] = $value;
			}
     
	  } /*else {
		 
		  $pairs = explode("=", $pairsurl[1]);
		  $vars[$pairs[0]] = $pairs[1];
	  }*/
	  
	   return $vars;
     
   }
   
   // function to display Add new Truck Form
	public function get_data() {
		
	$json_data = file_get_contents("php://input"); 
	//$json_data = json_encode(Input::all());

		if(empty($json_data))
		{
			
			$url=$_SERVER["REQUEST_URI"];
			
			if (strpos($url,'?') !== false) {
				$postArr = $this->getRealPOST($url);
				
				if(empty($postArr))
				{
					DB::table('api_request')->insert(
										array('request' => $json_data)
										);
					$result['status_code']=201;
					$result['status_message']='Problem in request data';
					$json_result = str_replace('null','""',json_encode($result));
					echo $json_result;
					exit;
				} else {
					$requestdata = array();
					if(count($postArr) > 0){
						//print_r($postArr);
						foreach($postArr as $key => $value){
							$requestdata['request'][$key] = $value;
						}
						$requestjsondata = json_encode($requestdata);
						//print_r($requestjsondata);
						DB::table('api_request')->insert(
										array('request' => $requestjsondata)
										);
						$data=json_decode($requestjsondata);
						//print_r($data);exit;
						return $data;
					}

				}

			} /*else {
				
				DB::table('api_request')->insert(
										array('request' => $json_data)
										);
				$result['status_code']=201;
				$result['status_message']='Problem in request data';
				$json_result = str_replace('null','""',json_encode($result));
				echo $json_result;
				exit;
			}*/
		}
		else
		{
			$data=Input::all();
			
			if(!empty($data)){
				
				$vars = array();
				foreach($data as $key => $value){
					$requestdata[$key] = $value;
				}
				$requestjsondata = json_encode($requestdata);
				DB::table('api_request')->insert(
										array('request' => $requestjsondata)
										);
				$data=json_decode($requestjsondata);
				
				return $data;
			
			} else {
				DB::table('api_request')->insert(
									array('request' => $json_data)
									);
				$data=json_decode($json_data);
		
				return $data;
			}
			//print_r($json_data);exit;
			//$json_data = file_get_contents("http://mnmdesignlabs.com/test.json");
			
		}
	}
	
		
	public function gettmdtoken()
	{
		$url = TMD_API_URL."create-access-token/";
		$post= json_encode(array(	'api_key'=>TMD_API_KEY,
						'api_secret'=>TMD_API_SECRET,
						'timestamp'=>time()
					));
		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS,$post);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1); 

		$response = curl_exec($handle);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		curl_close($handle);
		
		
		if($httpCode == 200)
		{
			$data = json_decode($response);
			
			if($data->status != 'error')
			{
				return $data->access_token;
			}
			else
			{
				return 2;
			}
		}
		else
		{
			return 2;
		} 
	}
	
	public function getsigningformhash($formname,$email,$reminder_code=0)
	{
		$access_token = $this->gettmdtoken();
		
		if($access_token!='' && $access_token!=2)
		{
			$url = TMD_API_URL."get-form-hash";
			$post= json_encode(array(	'form_name'=>$formname,
										'doc_version'=>'',
										'user_email'=>$email,
										'first_name'=>'',
										'lname_name'=>'',
										'email_status'=>1,
										'pdf_status'=>1,
										'url_status'=>1,
										'requester_name'=>'Gfood Lounge',
										'external_notes_show'=>1,
										'api_version'=>'v1',
										'reminder_code'=>$reminder_code,
									));

			$handle = curl_init($url);
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");  
			curl_setopt($handle, CURLOPT_HTTPHEADER, array(
														'x-tmd-request-timestamp : '.time(),
														'x-tmd-access-token : '.$access_token,
														'x-tmd-api-version  : '.'v1',
														));
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS,$post);
			curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1); 
	
			$response = curl_exec($handle);
			$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			
			curl_close($handle);
			if($httpCode == 200)
			{
				$data = json_decode($response);
			
				if($data->status != 'error')
				{
					return $data->hashcode;
				}
				else
				{
					return 2;
				}
			}
			else
			{
				return 2;
			} 
		}
		else
		{
			return 2;	
		}
	}
	
	
	public function checktokenauthentication(){
		
		$headers = getallheaders();

		foreach ($headers as $name => $value) {
			if($name=="x-taste-request-timestamp"){
				$timestamp=$value;
			}
			if($name=="x-taste-access-token"){
				$access_token=$value;
			}			
		}
		
		$validate_data = array(
			'timestamp' 	=> $timestamp,
			'access_token' 	=> $access_token
		);

		$rules = array(
			'timestamp' 	=> 'required|validatetimestamp',
			'access_token' 	=> 'required|authenticatetoken'
		);
		
		$messages = array(
				'timestamp.required' => 'Timestamp is required.',
				'timestamp.validatetimestamp' => 'You dont have permission to access this page. Time stamp error',
				'access_token.required' => 'Access Token is required',
				'access_token.authenticatetoken' => 'Access Token has been expired, Please generate a new one'
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
			$result['status_code']=200;
			$json_result = str_replace('null','""',json_encode($result));
			echo $json_result;
			exit;
		}
		
	}
	
	public function getusers(){
		
		$data1=$this->get_data();
		
		if (array_key_exists("access_token", $data1))
		{
   		 	$access_token = $data1->access_token;
		}
		else
		{
			 $access_token = '';
		}

		$validate_data = array(
			'access_token' 		=> $access_token,
		);
		
		$rules = array(
			'access_token' => 'required',
		);
		
		$messages = array(
			'access_token.required' => 'Access token is required.',
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
			//$users = User::getAllUsers();
			$users = DB::table('users')->get();
			if(count($users) > 0){
					$result['status_code']=200;
					$result['status_message']='OK';
					$result['users'] = $users;
			} else {
				$result['status_code']=200;
				$result['status_message']='OK';
				$result['users'] = 'No Records found';
			}
			$json_result = str_replace('null','""',json_encode($result));
			echo $json_result;
			exit;
		}
	}
	
	
	public function convert_date_format($date){
		//$explode_date = explode('/',$date);
		$new_date = date('Y-m-d', strtotime($date));
		return $new_date;
	}
	
	public function convert_date_format_pickup($date){
		$new_date = date('Y-m-d H:i:s', strtotime($date));
		return $new_date;
	}

	public function generateinvoicefrompo(){
		
		$headers = getallheaders();
		foreach ($headers as $name => $value) {
			if($name=="x-taste-request-timestamp"){
				$timestamp=$value;
			}
			if($name=="x-taste-access-token"){
				$access_token=$value;
			}			
		}
		$data1=$this->get_data();
		
		if (array_key_exists("po_no", $data1))
		{
   		 	$po_no = $data1->po_no;
		}
		else
		{
			 $po_no = '';
		}
		
		if (array_key_exists("po_date", $data1))
		{
   		 	$po_date = $data1->po_date;
		}
		else
		{
			 $po_date = '';
		}
		
		
		if (array_key_exists("vendor_name", $data1))
		{
   		 	$vendor_name = $data1->vendor_name;
		}
		else
		{
			 $vendor_name = '';
		}
		
		if (array_key_exists("vendor_id", $data1))
		{
   		 	$vendor_id = $data1->vendor_id;
		}
		else
		{
			 $vendor_id = '';
		}
		
		if (array_key_exists("shipping_address", $data1))
		{
			$shipping_address = array();
   		 	$shipping_address = $data1->shipping_address;
		}
		else
		{
			$shipping_address = array();
		}
		
		if(count($shipping_address) > 0){
			if (array_key_exists("city", $shipping_address))
			{
				$city = $shipping_address->city;
			} else {
				$city = '';
			}
			if (array_key_exists("state", $shipping_address))
			{
				$state = $shipping_address->state;
			} else {
				$state = '';
			}
			if (array_key_exists("country", $shipping_address))
			{
				$country = $shipping_address->country;
			} else {
				$country = '';
			}
			if (array_key_exists("zip", $shipping_address))
			{
				$zip = $shipping_address->zip;
			} else {
				$zip = '';
			}
		} else {
			$city='';
			$state ='';
			$country='';
			$zip = '';
		}
		/*if (array_key_exists("shipping_address", $data1))
		{
   		 	$shipping_address = $data1->shipping_address;
		}
		else
		{
			 $shipping_address = '';
		}*/
		
		/*if (array_key_exists("invoice_from", $data1))
		{
   		 	$invoice_from = $data1->invoice_from;
		}
		else
		{
			 $invoice_from = '';
		}
		
		if (array_key_exists("invoice_email", $data1))
		{
   		 	$invoice_email = $data1->invoice_email;
		}
		else
		{
			 $invoice_email = '';
		}
		
		if (array_key_exists("invoice_phone", $data1))
		{
   		 	$invoice_phone = $data1->invoice_phone;
		}
		else
		{
			 $invoice_phone = '';
		}
		
		if (array_key_exists("invoice_url", $data1))
		{
   		 	$invoice_url = $data1->invoice_url;
		}
		else
		{
			$invoice_url = '';
		}*/
		
		if (array_key_exists("totalamount", $data1))
		{
   		 	$totalamount = $data1->totalamount;
		}
		else
		{
			 $totalamount = '';
		}
	
		if (array_key_exists("pickup_time", $data1))
		{
   		 	$pickup_time = $data1->pickup_time;
		}
		else
		{
			 $pickup_time = '';
		}
		
		if (array_key_exists("status", $data1))
		{
   		 	$status = $data1->status;
		}
		else
		{
			 $status = '';
		}
		
		if (array_key_exists("approved_by", $data1))
		{
   		 	$approved_by = $data1->approved_by;
		}
		else
		{
			 $approved_by = '';
		}
		
		if (array_key_exists("paidstatus", $data1))
		{
   		 	$paid_status = $data1->paidstatus;
		}
		else
		{
			 $paid_status = '';
		}
		
		if (array_key_exists("priority", $data1))
		{
   		 	$priority = $data1->priority;
		}
		else
		{
			 $priority = '';
		}
		
		if(isset($data1->items)){
			$item_data = $data1->items;
		} else {
			$item_data ='';
		}

		$validate_data = array(
			'timestamp' 	=> $timestamp,
			'access_token' 	=> $access_token,
			'po_no' => $po_no,
			'po_date' => $po_date,
			'vendor_name' => $vendor_name,
			'shipping_address' => $shipping_address,
			/*'invoice_from' => $invoice_from,
			'invoice_email' => $invoice_email,
			'invoice_phone' => $invoice_phone,
			'invoice_url' => $invoice_url,*/
			'totalamount' => $totalamount,
			'pickup_time' => $pickup_time,
			'status' => $status,
			'paid_status' => $paid_status,
			'approved_by' => $approved_by,
			'items' => $item_data,
		);

		$rules = array(
			'timestamp' 	=> 'required|validatetimestamp',
			'access_token' 	=> 'required|authenticatetoken',
			'po_no' => 'required',
			'po_date' => 'required',
			'vendor_name' => 'required',
			'shipping_address' => 'required',
			/*'invoice_from' => 'required',
			'invoice_email' => 'required|email',
			'invoice_phone' => 'required',
			'invoice_url' => 'required',*/
			'totalamount' => 'required',
			'pickup_time' => 'required',
			'status' => 'required',
			'paid_status' => 'required',
			'approved_by' => 'required',
			'items' => 'required',
		);
		
		$messages = array(
				'timestamp.required' => 'Timestamp is required.',
				'timestamp.validatetimestamp' => 'You dont have permission to access this page. Time stamp error',
				'access_token.required' => 'Access Token is required',
				'access_token.authenticatetoken' => 'Access Token has been expired, Please generate a new one',
				'po_no.required' => 'Order id is required.',
				'po_date.required' => 'Order date is required.',
				'vendor_name.required' => 'Vendor name is required.',
				'shipping_address.required' => 'Shipping Address is required.',
				/*'invoice_from.required' => 'Taste Address is required.',
				'invoice_email.required' => 'E-mail address is required.',
				'invoice_email.email' 	 => 'Please enter a valid E-mail address.',
				'invoice_phone.required' => 'Phone is required.',
				'invoice_url.required' => 'URL is required.',*/
				'totalamount.required' => 'Total Amount is required.',
				'pickup_time.required' => 'Pickup Time is required.',
				'status.required' => 'Order status is required',
				'paid_status.required' => 'Order paid status is required',
				'approved_by' => 'Approved by information is  required',
				'items.required' => 'Item description are required.',
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
			
			$error = 0;
			if(count($item_data) > 0) {
				$po_date = $this->convert_date_format($po_date);
				$pickup_time = $this->convert_date_format_pickup($pickup_time);
				
				$insert_po_details = PoDetail::insert_po_details($po_no,$po_date,$vendor_id,$vendor_name,$city,$state,$country,$zip,$totalamount,$pickup_time,$status,$paid_status,$approved_by,$priority);
				if(isset($insert_po_details) && $insert_po_details != ''){
					foreach ($item_data as $item) {
						$item_id = $item->id;
						$quantity = $item->quantity;
						$price = $item->price;
						$name = $item->name;
						$insert_po_item_details = PoDetail::insert_po_item_details($po_no,$item_id,$name,$price,$quantity);	
					}
				} else {
					$error= 1;
					$message = 'Error saving PO details';
				}
			} else {
				$error= 1;
				$message = 'Atleast one item is needed in PO';
			}
			
			if($error == 1){
				$result['status_code']=201;
				$result['status_message']=$message;
				
			} else {
				$result['status_code']=200;
				$result['status_message']='PO details saved sucessfully';
			}
			$json_result = str_replace('null','""',json_encode($result));
			echo $json_result;
			exit;
 			//Process of generating Invoice commented
			 /*$postfields["firstname"] = 'Ankit';
			 $postfields["lastname"] = 'Arora';
			 $postfields["email"] = 'company@yopmail.com';
			 $postfields["companyname"] = 'Test Company';
			 $postfields["address1"] = $data1->shipping_address;
			 $postfields["city"] = 'Test';
			 $postfields["state"] = 'Test';
			 $postfields["postcode"] = '55555';
			 $postfields["country"] = "US";
			 $postfields["phonenumber"] = '(234)234 2342';
			 $postfields["password2"] = '123456';
			
			 $wh=new whmcs(WHMCS_API_URL,WHMCS_ADMIN,WHMCS_PASSWORD,WHMCS_API_KEY);
			
			 $jsondata=$wh->addclient($postfields);
			 
			 $client_data=json_decode($jsondata);
			
			 if($client_data->result=="success")
			 {
				$wh_id=$client_data->clientid ;
			 
			 }else{
			 
				return Redirect::to('register')->with('message', $client_data->message);
			 }
			 
			 $i=0;$total=0;
			 foreach ($item_data as $item) {
					if($item->price > 0){ 
						$total 	   = $total+($item->quantity*$item_info[0]->price);
						$val = "pid['$i']";
						$postfields["$val"]=$item->item_id;
						$i++;
					} else {
						$get_coupon_code = DB::table()->where('coupon_name','=',$item->name)->first();
						if(count($coupon_code) > 0){
							$total 	   = $total-($item->quantity*$item_info[0]->price);
							$postfields["promocode"] = $get_coupon_code->coupon_code;
						}
					}	
			}
			
			$postfields1["clientid"] 	= $wh_id;
			$postfields1["billingcycle"] = "onetime";
			$postfields1["paymentmethod"] = "stripe";
			
			$json_data=$wh->addorder($postfields1);
			$orderData=json_decode($json_data);
			if($orderData->result=="success"){
				$postfields2["invoiceid"] = $orderData->invoiceid ;*/
				/*$postfields2["transid"] = '';
				$postfields2["amount"] = $total;
				$postfields2["gateway"] = "stripe";
				$postfields2["date"] = date('Y-m-d');
				$jsondata=$wh->addinvoicepayment($postfields2);*/
				
			//}	
		}

	}
	
	public function searchorders($searchby='all',$orderby='po_date',$order='desc',$vendorname='',$paidstatus='',$servicedate='',$duedate=''){
		
		/*$headers = getallheaders();

		foreach ($headers as $name => $value) {
			if($name=="x-taste-request-timestamp"){
				$timestamp=$value;
			}
			if($name=="x-taste-access-token"){
				$access_token=$value;
			}			
		}*/
		/*$data1=$this->get_data();
		
		if (array_key_exists("searchby", $data1))
		{
   		 	$searchby = $data1->searchby;
		}
		else
		{
			 $searchby = 'all';
		
		}
		
		if (array_key_exists("orderby", $data1))
		{
   		 	$orderby = $data1->orderby;
		}
		else
		{
			 $orderby = 'po_date';
		
		}
		
		if (array_key_exists("order", $data1))
		{
   		 	$order = $data1->order;
		}
		else
		{
			 $order = 'desc';
		
		}
		
		if (array_key_exists("vendorname", $data1))
		{
   		 	$vendorname = $data1->vendorname;
		}
		else
		{
			 $vendorname = '';
		
		}
		
		if (array_key_exists("paidstatus", $data1))
		{
   		 	$paidstatus = $data1->paidstatus;
		}
		else
		{
			 $paidstatus = '';
		
		}
		
		if (array_key_exists("servicedate", $data1))
		{
   		 	$servicedate = $data1->servicedate;
		}
		else
		{
			 $servicedate = '';
		
		}
		
		if (array_key_exists("duedate", $data1))
		{
   		 	$duedate = $data1->duedate;
		}
		else
		{
			 $duedate = '';
		
		}
		
		$validate_data = array(
			'timestamp' 	=> $timestamp,
			'access_token' 	=> $access_token,
			//'vendorname' => $vendorname,
			//'action' 	=> $action,
		);

		$rules = array(
			'timestamp' 	=> 'required|validatetimestamp',
			'access_token' 	=> 'required|authenticatetoken',
			//'vendorname' =>  'required_if:searchby,vendor',
			//'action' => 'required',
		);
		
		$messages = array(
				'timestamp.required' => 'Timestamp is required.',
				'timestamp.validatetimestamp' => 'You dont have permission to access this page. Time stamp error',
				'access_token.required' => 'Access Token is required',
				'access_token.authenticatetoken' => 'Access Token has been expired, Please generate a new one',
			//	'vendorname.required_if' =>  'Vendor Name is required',
				//'action.required' => 'Action is required.',
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
		} else {*/

			$ordercount = 0;
			$get_po_listing = PoDetail::get_po_listings($searchby,$orderby,$order,$vendorname,$servicedate,$duedate,$paidstatus);
			if(count($get_po_listing) > 0){
				$recordsfound = count($get_po_listing);
				$result['status_code']=200;
				$rectext = ($recordsfound > 1)?'records':'record';
				$result['status_message']= $rectext.' found';
				$result['records_count']= $recordsfound;
				foreach($get_po_listing as $listingdetail){
					$result[$listingdetail->po_no]['po_no'] = $listingdetail->po_no;
					$result[$listingdetail->po_no]['po_date'] = $listingdetail->po_date;
					$result[$listingdetail->po_no]['vendor_name'] = $listingdetail->vendor_name;
					$result[$listingdetail->po_no]['city'] = $listingdetail->city;
					$result[$listingdetail->po_no]['state'] = $listingdetail->state;
					$result[$listingdetail->po_no]['country'] = $listingdetail->country;
					$result[$listingdetail->po_no]['total_amount'] = $listingdetail->total_amount;
					$result[$listingdetail->po_no]['pickup_time'] = $listingdetail->pickup_time;
					$result[$listingdetail->po_no]['status'] = $listingdetail->status;
					$result[$listingdetail->po_no]['approved_by'] = $listingdetail->approved_by;
					$result[$listingdetail->po_no]['paid_status'] = $listingdetail->paid_status;
					$result[$listingdetail->po_no]['due_date'] = $listingdetail->due_date;
					$get_po_detail = PoDetail::get_po_detail($listingdetail->po_no);
					if(count($get_po_detail) > 0){
						$result[$listingdetail->po_no]['items'] = $get_po_detail;
					}
				}
			} else {
				$result['status_code']=201;
				$result['status_message']='No records found';
			}
			
			$json_result = str_replace('null','""',json_encode($result));
			return $json_result;
			
		//}

	}
	
	public function sendpaymentlink(){
		
		$headers = getallheaders();

		foreach ($headers as $name => $value) {
			if($name=="x-taste-request-timestamp"){
				$timestamp=$value;
			}
			if($name=="x-taste-access-token"){
				$access_token=$value;
			}			
		}
		$data1=$this->get_data();
		
		if (array_key_exists("email", $data1))
		{
   		 	$email = $data1->email;
		}
		else
		{
			 $email = '';
		
		}

		$validate_data = array(
			'timestamp' 	=> $timestamp,
			'access_token' 	=> $access_token,
			'email'			=> $email,
		);

		$rules = array(
			'timestamp' 	=> 'required|validatetimestamp',
			'access_token' 	=> 'required|authenticatetoken',
			'email'			=> 'required|email',
		);
		
		$messages = array(
				'timestamp.required' => 'Timestamp is required.',
				'timestamp.validatetimestamp' => 'You dont have permission to access this page. Time stamp error',
				'access_token.required' => 'Access Token is required',
				'access_token.authenticatetoken' => 'Access Token has been expired, Please generate a new one',
				'email.required' => 'Email address is required',
				'email.email' => 'Please enter a valid Email Address',
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
			
			$sendauthorizationlink = Token::create_authorization_link($email);
			
			if($sendauthorizationlink != 0){
			
				$result['status_code']=200;
				$result['status_message']='';
			} else {
				$result['status_code']=201;
				$result['status_message']='No vendor found with above email';
			}
			
		}
		
	}
	
	public function getunpaidpo(){
		
		$headers = getallheaders();

		foreach ($headers as $name => $value) {
			if($name=="x-taste-request-timestamp"){
				$timestamp=$value;
			}
			if($name=="x-taste-access-token"){
				$access_token=$value;
			}			
		}
		
		$data1=$this->get_data();
	
		if (array_key_exists("timelimit", $data1))
		{
   		 	$type = $data1->timelimit;
		}
		else
		{
			 $type = '';
		
		}
		
		if (array_key_exists("action", $data1))
		{
   		 	$action = $data1->action;
		}
		else
		{
			 $action = '';
		
		}
		
		if (array_key_exists("fetchdata", $data1))
		{
   		 	$fetchdata = $data1->fetchdata;
		}
		else
		{
			 $fetchdata = 'all';
		
		}
		
		if (array_key_exists("activitytype", $data1))
		{
   		 	$activitytype = $data1->activitytype;
		}
		else
		{
			 $activitytype = 'today';
		
		}
		
		if($action == 'payments'){
			if (array_key_exists("searchby", $data1))
			{
				$searchby = $data1->searchby;
			}
			else
			{
				 $searchby = '';

			}

			if (array_key_exists("orderby", $data1))
			{
				$orderby = $data1->orderby;
			}
			else
			{
				 $orderby = 'po_date';

			}

			if (array_key_exists("order", $data1))
			{
				$order = $data1->order;
			}
			else
			{
				 $order = 'desc';

			}

			if (array_key_exists("vendorname", $data1))
			{
				$vendorname = $data1->vendorname;
			}
			else
			{
				 $vendorname = '';

			}

			if (array_key_exists("paidstatus", $data1))
			{
				$paidstatus = $data1->paidstatus;
			}
			else
			{
				 $paidstatus = 'paid,unpaid';

			}

			if (array_key_exists("servicedate", $data1))
			{
				$servicedate = $data1->servicedate;
			}
			else
			{
				 $servicedate = '';

			}

			/*if (array_key_exists("duedate", $data1))
			{
				$duedate = $data1->duedate;
			}
			else
			{
				 $duedate = '';

			}*/
			
			if (array_key_exists("due_date_from", $data1))
			{
				$due_date_from = $data1->due_date_from;
			}
			else
			{
				$due_date_from = '';

			}
			
			if (array_key_exists("due_date_to", $data1))
			{
				$due_date_to = $data1->due_date_to;
			}
			else
			{
				$due_date_to = '';

			}
			
			if (array_key_exists("start", $data1))
			{
				$start = $data1->start;
			}
			else
			{
				$start = '';

			}
			
			if (array_key_exists("length", $data1))
			{
				$length = $data1->length;
			}
			else
			{
				$length = '';

			}
			
			if (array_key_exists("draw", $data1))
			{
				$draw = $data1->draw;
			}
			else
			{
				$draw = '';

			}
			
			if (array_key_exists("order_id", $data1))
			{
				$order_id = $data1->order_id;
			}
			else
			{
				$order_id = '';

			}
			
			if (array_key_exists("orderamount", $data1))
			{
				$orderamount = $data1->orderamount;
			}
			else
			{
				$orderamount = '';

			}
		}
		
		if($action == 'getaccountinfo'){
			if (array_key_exists("accesshash", $data1))
			{
				$accesshash = $data1->accesshash;
			}
			else
			{
				 $accesshash = '';

			}
		}
		
		if($action == 'sendrequesterlink'){
			if (array_key_exists("vendorid", $data1))
			{
				$vendorid = $data1->vendorid;
			}
			else
			{
				 $vendorid = '';

			}
			
			if (array_key_exists("actiontype", $data1))
			{
				$actiontype = $data1->actiontype;
			}
			else
			{
				$actiontype = '';

			}
		}
		
		if($action == 'createuseraccount'){
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
		}
		
		if($action == 'getw9form'){
			if (array_key_exists("email", $data1))
			{
				$email = $data1->email;
			}
			else
			{
				 $email = '';

			}
			
			if (array_key_exists("formname", $data1))
			{
				$formname = $data1->formname;
			}
			else
			{
				 $formname = '';

			}
			
			if (array_key_exists("reminder_code", $data1))
			{
				$reminder_code = $data1->reminder_code;
			}
			else
			{
				 $reminder_code = '';

			}
		}
		
		if($action == 'savecontractinfo'){
			if (array_key_exists("email", $data1))
			{
				$email = $data1->email;
			}
			else
			{
				 $email = '';

			}
			
			if (array_key_exists("ssn_last4", $data1))
			{
				$ssn_last4 = $data1->ssn_last4;
			}
			else
			{
				 $ssn_last4 = '';

			}
			
			if (array_key_exists("ssn_enc", $data1))
			{
				$ssn_enc = $data1->ssn_enc;
			}
			else
			{
				 $ssn_enc = '';

			}
			
			if (array_key_exists("ein", $data1))
			{
				$ein = $data1->ein ;
			}
			else
			{
				$ein = '';

			}
			
			if (array_key_exists("payeename", $data1))
			{
				$payeename = $data1->payeename ;
			}
			else
			{
				$payeename = '';

			}
		}
		
		if($action == 'gettaxidforvendor' || $action == 'getpayeename' || $action == 'getbankaccountinfo'){
			if (array_key_exists("vendorid", $data1))
			{
				$vendorid = $data1->vendorid;
			}
			else
			{
				 $vendorid = '';

			}
		}
		
		if($action == 'savebankaccountinfo'){
			if (array_key_exists("vendorid", $data1))
			{
				$vendorid = $data1->vendorid;
			}
			else
			{
				 $vendorid = '';

			}
			if (array_key_exists("routing_number", $data1))
			{
				$routing_number = $data1->routing_number;
			}
			else
			{
				 $routing_number = '';

			}
			
			if (array_key_exists("account_number", $data1))
			{
				$account_number = $data1->account_number;
			}
			else
			{
				 $account_number = '';

			}
			
			if (array_key_exists("tax_id", $data1))
			{
				$tax_id = $data1->tax_id;
			}
			else
			{
				 $tax_id = '';

			}
			
			if (array_key_exists("paymenttype", $data1))
			{
				$paymenttype = $data1->paymenttype;
			}
			else
			{
				 $paymenttype = '';

			}
			
			if (array_key_exists("payeename", $data1))
			{
				$payeename = $data1->payeename;
			}
			else
			{
				 $payeename = '';

			}
			
			if (array_key_exists("mailingaddress", $data1))
			{
				$mailingaddress = $data1->mailingaddress;
			}
			else
			{
				$mailingaddress = '';

			}
			
			if (array_key_exists("authcode", $data1))
			{
				$authcode = $data1->authcode;
			}
			else
			{
				$authcode = '';

			}
			
			if (array_key_exists("bankid", $data1))
			{
				$bankid = $data1->bankid;
			}
			else
			{
				$bankid = 0;

			}
			
		}
		
		
		if($action == 'savesettings'){
			
			if (array_key_exists("test_secret_key", $data1))
			{
				$test_secret_key = $data1->test_secret_key;
			}
			else
			{
				 $test_secret_key = '';

			}
			
			if (array_key_exists("test_publishable_key", $data1))
			{
				$test_publishable_key = $data1->test_publishable_key;
			}
			else
			{
				 $test_publishable_key = '';

			}
			
			if (array_key_exists("live_secret_key", $data1))
			{
				$live_secret_key = $data1->live_secret_key;
			}
			else
			{
				 $live_secret_key = '';

			}
			
			if (array_key_exists("live_publishable_key", $data1))
			{
				$live_publishable_key = $data1->live_publishable_key;
			}
			else
			{
				 $live_publishable_key = '';

			}
			
			if (array_key_exists("type", $data1))
			{
				$type = $data1->type;
			}
			else
			{
				 $type = '';

			}
		}
		
		if($action == 'changesettingstatus'){
			
			if (array_key_exists("status", $data1))
			{
				$status = $data1->status;
			}
			else
			{
				 $status = '';

			}
		}
		if($action == 'makevendorpayment'){
			
			if (array_key_exists("poid", $data1))
			{
				$poid = $data1->poid;
			}
			else
			{
				$poid = '';

			}
			
			if (array_key_exists("amount", $data1))
			{
				$amount = $data1->amount;
			}
			else
			{
				$amount = '';

			}
			
		}

		
		$validate_data = array(
			'timestamp' 	=> $timestamp,
			'access_token' 	=> $access_token,
		);
		
		

		$rules = array(
			'timestamp' 	=> 'required|validatetimestamp',
			'access_token' 	=> 'required|authenticatetoken',
		);
		
		$messages = array(
				'timestamp.required' => 'Timestamp is required.',
				'timestamp.validatetimestamp' => 'You dont have permission to access this page. Time stamp error',
				'access_token.required' => 'Access Token is required',
				'access_token.authenticatetoken' => 'Access Token has been expired, Please generate a new one',
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
			
			if($action == 'dashboard'){
				if($fetchdata == 'all'){
					$countunpaidorders = PoDetail::get_unpaid_po();
					$nextweekpo = PoDetail::get_next_week_po();
					$type = 'today';
					$gettodaypo = PoDetail::get_po_detail_with_type($type);
					$nextweekdue = PoDetail::get_next_week_due_detail();
					$vendorpodetails = PoDetail::get_vendor_orders_detail($activitytype);
					$nextweekduecount = count($nextweekdue);
					
					if(isset($countunpaidorders->unpaidorders)){
						$result['status_code']=200;
						$result['status_message']='Records found';
						$result['unpaidorders']=$countunpaidorders->unpaidorders;
						$result['totalunpaidamount']=$countunpaidorders->unpaidamount;
						$result['unpaidnextweekorders']=$nextweekpo->unpaidnextweekorders;
						$result['unpaidnextweekamount']=$nextweekpo->unpaidnextweekamount;
						$result['getpodetails']=$gettodaypo;
						$result['nextweekdue']=$nextweekdue;
						$result['nextweekduecount']=$nextweekduecount;
						$result['vendorpodetails']=$vendorpodetails;
					} else {
						$result['status_code']=201;
						$result['status_message']='No Records found';
					}
				} else if($fetchdata == 'members') {
					
					$vendorpodetails = PoDetail::get_vendor_orders_detail($activitytype);
					
					if(count($vendorpodetails) > 0){
						$result['status_code']=200;
						$result['status_message']='Records found';
						$result['vendorpodetails']=$vendorpodetails;
					}
				} else {
					
				}
				
			} else if($action == 'payments') {
				$ordercount = 0;
				
				$get_po_listing = PoDetail::get_po_listings($searchby,$orderby,$order,$vendorname,$servicedate,$due_date_from,$due_date_to,$paidstatus,$order_id,$orderamount);
				
				
				if(count($get_po_listing) > 0){
					$recordsfound = count($get_po_listing);
					$i = 0;
					$totallength = $start+$length;
					
					if($start == 0){
						$startrec = 0;
					} else {
						$startrec = $start;
					}
					$status_list = array(
						"approved" => "success",
						"pending" => "danger"
				    );
				    
				    $paid_status_list = array(
						"paid" => "success",
						"unpaid" => "danger"
				    );
				 
				  foreach($get_po_listing as $listingdetail){
						//if($i >= $startrec && $i < $totallength){
							$shipping_address = $listingdetail->country .','.$listingdetail->state.','.$listingdetail->city.','.$listingdetail->zip;
							//$status = $status_list[$listingdetail->status];
							$paid_status = $paid_status_list[$listingdetail->paid_status];
							//$status_html = '<span class="label label-sm label-'.$status.'">'.$listingdetail->status.'</span>';
							$paid_status_html = '<span class="label label-sm label-'.$paid_status.'">'.$listingdetail->paid_status.'</span>';
							$podate = date("n/j, Y",strtotime($listingdetail->po_date));
							$duedate = date("n/j, g:ia",strtotime($listingdetail->due_date)); $duedate= substr($duedate, 0, -1);
							$payhtml = '';
							$payclass='';
							if($listingdetail->paid_status == 'paid')
							{
								$payhtml = 'javascript:void(0);';
							} else {
								$payhtml = 'javascript:void(0);';
							}
							$priority_status = $listingdetail->priority_status;
							$check_bank_details = PoDetail::check_bank_details_filled($listingdetail->vendor_email);
							
							$payment_done_hover = '';
							if($check_bank_details == 0){
								$request_status = 'Request Info';
								$request_id = 'requestinfo';
							} else {
								$request_status = 'Update Info';
								$request_id = 'updateinfo';
							}
							
							if($listingdetail->vendor_paid_status == 1){
								$pay_done_class = 'fade_pay';
								$payment_done_hover = 'accountingpopover';
							} else {
								
								if($check_bank_details == 1){
									$pay_done_class = 'makepayment';
								} else {
									$pay_done_class = 'fade_pay payment_done';
									
								}
							}
							//$requestinfohtml = PoDetail::get_request_info($listingdetail->vendor_id);
							$result['data'][]= array($listingdetail->po_no,$podate,$listingdetail->vendor_name,$shipping_address,'$'.$listingdetail->total_amount,$duedate,$paid_status_html,$priority_status,'<script type="text/ng-template" id="routingpopover.html">
							Your routing number is normally found on a check provided by your bank.
							</script><a '.$payment_done_hover.' href="'.$payhtml.'" class="btn btn-xs default btn-editable '.$pay_done_class.'" id="'.$listingdetail->po_no.'" data-payment-amount="'.$listingdetail->total_amount.'" data-placement="top">Pay</a><button class="btn btn-xs  default btn-editable requestinfo" id="'.$request_id.'" data-request-id="'.$listingdetail->vendor_id.'">'.$request_status.'</button>');
						//}
						$i++;
					}
					
					$result["draw"] = $draw;
					$result["recordsTotal"] = $recordsfound;
					$result["recordsFiltered"] = $recordsfound;
					
				
					//$result['data'] = $get_po_listing;
					
					/*$result['status_code']=200;
					$rectext = ($recordsfound > 1)?'records':'record';
					$result['status_message']= $rectext.' found';
					$result['pocount']= $recordsfound;
					$result['podetails'] = $get_po_listing;*/
					
					/*foreach($get_po_listing as $listingdetail){
						$result[$listingdetail->po_no]['po_no'] = $listingdetail->po_no;
						$result[$listingdetail->po_no]['po_date'] = $listingdetail->po_date;
						$result[$listingdetail->po_no]['vendor_name'] = $listingdetail->vendor_name;
						$result[$listingdetail->po_no]['city'] = $listingdetail->city;
						$result[$listingdetail->po_no]['state'] = $listingdetail->state;
						$result[$listingdetail->po_no]['country'] = $listingdetail->country;
						$result[$listingdetail->po_no]['total_amount'] = $listingdetail->total_amount;
						$result[$listingdetail->po_no]['pickup_time'] = $listingdetail->pickup_time;
						$result[$listingdetail->po_no]['status'] = $listingdetail->status;
						$result[$listingdetail->po_no]['approved_by'] = $listingdetail->approved_by;
						$result[$listingdetail->po_no]['paid_status'] = $listingdetail->paid_status;
						$result[$listingdetail->po_no]['due_date'] = $listingdetail->due_date;
						
						//po items code
						/*$get_po_detail = PoDetail::get_po_detail($listingdetail->po_no);
						if(count($get_po_detail) > 0){
							$result[$listingdetail->po_no]['items'] = $get_po_detail;
						}*/
					//}
					$json_result = str_replace('null','""',json_encode($result));
					echo $json_result;
					exit;
				} else {
					$result['data'][] =array();
					$result["draw"] = 0;
					$result["recordsTotal"] = 0;
					$result["recordsFiltered"] = 0;
					$result["zeroRecords"] = 0;
					
				}
			} else if($action == 'getvendorsdetails') {
				$allvendorsdetails = PoDetail::get_all_vendor_details();
				$result["allvendorsdetails"] = $allvendorsdetails;
			} else if($action == 'sendrequesterlink') {
				
				$sendauthorizationlink = Token::create_authorization_link($vendorid,$actiontype);
				if($sendauthorizationlink != 0){
					$result['status_code']=200;
					$result['status_message']='Request link send sucessfully!';
				} else {
					$result['status_code']=201;
					$result['status_message']='No vendor found with above email';
				}
			} else if($action == 'getaccountinfo') {
				//$check_user_exists = PoDetail::check_vendor_exists($accesshash);
				$vendoraccountinfo  = PoDetail::get_vendor_details($accesshash);

				if(isset($vendoraccountinfo['email']) || isset($vendoraccountinfo['tokenstatus']) || isset($vendoraccountinfo['aleadyexists'])){ 
					$result['status_code']=200;
					if(isset($vendoraccountinfo['email'])){
						$result['vendoremail'] = $vendoraccountinfo['email'];
					} else if(isset($vendoraccountinfo['tokenstatus'])){
						$result['status_code']=201;
						$result['status_message'] = 'Invalid token';
						$result['tokenstatus'] = $vendoraccountinfo['tokenstatus'];
						
					} else {
						$result['aleadyexists'] = $vendoraccountinfo['aleadyexists'];
						$result['w9signed'] =  $vendoraccountinfo['w9signed'];
						$result['userid'] = $vendoraccountinfo['userid'];
						$result['username'] = $vendoraccountinfo['username'];
					
					}
					
				} else {
					$result['status_code']=201;
					$result['status_message'] = 'Invalid token';
				}
			}else if($action == 'createuseraccount') {
				$vendoruserid  = PoDetail::create_vendor_account($email,$password);
				
				if(isset($vendoruserid) && $vendoruserid != ''){ 
					$result['status_code']=200;
					$result['vendoruserid'] = $vendoruserid;
				} else {
					$result['status_code']=201;
					$result['status_message'] = 'Invalid token';
				}
			} else if($action == 'getw9form') {
				$check_already_signed =  PoDetail::check_w9_signed($email);
				if($check_already_signed == 1){
					$result['w9signed'] = 1;
				} else {
					/*if($email != ''){
						$check_valid_hash = PoDetail::check_vendor_exists($email);
						
					}*/
					$hash = $this->getsigningformhash($formname,$email,$reminder_code);
					$result['w9response'] = $hash;
					$result['w9signed'] = 0;
				}
				$result['status_code']=200;
				
			} else if($action == 'savecontractinfo') {
				$save_tax_info = PoDetail::save_tax_info($email,$ssn_last4,$ssn_enc,$ein,$payeename);
				$result['status_code']=200;
				
				$result['updatedtaxid'] = $save_tax_info;
			} else if($action == 'gettaxidforvendor'){
				$get_tax_id = PoDetail::get_tax_info($vendorid);
				$check_bank_account_exists = PoDetail::check_bank_account_exists($vendorid);
				$result['paymenttype'] = $check_bank_account_exists;
				$result['status_code']=200;
				$result['taxinformation'] = $get_tax_id;
				
			} else if($action == 'savebankaccountinfo'){
				if($bankid != '' && $bankid == 0){
					if($paymenttype == 'manual'){
						$get_vendor_name = 	$payeename;
					} else {
						$get_vendor_name = PoDetail::get_vendor_account_info($vendorid);
					}
					
					if($get_vendor_name != ''){

						$obj=new ptStripe(STRIPE_KEY);

						$bank_data=$obj->createRecipient(array( 
							"name" => $get_vendor_name,
							"type" => 'individual',
							"bank_account" => array("country"=>'US',"routing_number"=>$routing_number,"account_number"=>$account_number),		
				
						));
						
						
						if($bank_data['status']==1)
						{
							$result['status_code']=201;
							$result['message'] = $bank_data['message'];
							$json_result = str_replace('null','""',json_encode($result));
							echo $json_result;exit;
							//return Redirect::to('addbankinfo')->with('message', $bank_data['message']);
						}
						$recipient=$bank_data['data']->id;
						$country = 'US';
						$save_bank_account = PoDetail::save_bank_account_info($vendorid,$get_vendor_name,$routing_number,$account_number,$recipient,$tax_id,$country,$mailingaddress,$authcode,$paymenttype);
						
						if($save_bank_account != '' && $save_bank_account != 0){
							$result['status_code']=200;
							$result['message'] = 'Bank Account Info saved sucessfully';
						} else{
							$result['status_code']=201;
							$result['message'] = 'Token is not valid for Adding bank details';
						}

						/*$recipient=$bank_data['data']->id;
						$balanced_account="SAVINGS";
						Balanced\Settings::$api_key = BALANCED_API_KEY;
						$marketplace = \Balanced\Marketplace::mine();
						$bank_account = $marketplace->bank_accounts->create(array(
								"account_number" => $data['account_number'],
								"account_type" => $balanced_account,
								"name" => $data['name'],
								"routing_number" => $data['routing_number']
						));

						$balanced_reponse=$bank_account->save();
						

						$bank = new Wallet;
						$bank->name = $data['name'];
								$bank->bank_name = $data['bank_name'];
								$bank->type = $data['type'];
								$bank->account_number = $data['account_number'];
						$bank->routing_number = $data['routing_number'];
						$bank->recipient  = $recipient ;
						$bank->balanced_bank_response = $balanced_reponse->href;
						$bank->tax_id = $data['tax_id'];
						$bank->country = $data['country'];
						$bank->vendorid =  Auth::user()->id;
						$bank->save();
						$bank= $bank->id;*/
						
					}
				} else{
					$obj=new ptStripe(STRIPE_KEY);
					$get_recipient_id = PoDetail::get_recipient_info($vendorid);
					$del_rec = $obj->deleteRecipient(array( "id" => $get_recipient_id));
					
					
					if($paymenttype == 'manual'){
						$get_vendor_name = 	$payeename;
					} else {
						$get_vendor_name = PoDetail::get_vendor_account_info($vendorid);
					}
					
					$obj=new ptStripe(STRIPE_KEY);
					$bank_data=$obj->createRecipient(array( 
						"name" => $get_vendor_name,
						"type" => 'individual',
						"bank_account" => array("country"=>'US',"routing_number"=>$routing_number,"account_number"=>$account_number),				
					));
					
					//print_r($bank_data);exit;
					
					if($bank_data['status']==1)
					{
						$result['status_code']=201;
						$result['message'] = $bank_data['message'];
						$json_result = str_replace('null','""',json_encode($result));
						echo $json_result;exit;
						
					}
					$recipient=$bank_data['data']->id;
					$country = 'US';
					$save_bank_account = PoDetail::update_bank_account_info($bankid,$vendorid,$get_vendor_name,$routing_number,$account_number,$recipient,$tax_id,$country,$mailingaddress,$authcode,$paymenttype);
					
					if($save_bank_account != '' && $save_bank_account != 0){
						$result['status_code']=200;
						$result['message'] = 'Bank Account Info saved sucessfully';
					} else{
						$result['status_code']=201;
						$result['message'] = 'Token is not valid for Adding bank details';
					}

				}

			} else if($action == 'getpayeename' || $action == 'getbankaccountinfo') {
				$get_payee_name = PoDetail::get_payee_info($vendorid);
				
				if(isset($get_payee_name->id) &&  $get_payee_name->id != ''){
					$result['status_code']=200;
					if($action == 'getpayeename'){
						$result['payeename'] = $get_payee_name->payeename;
						$result['taxinfo'] = $get_payee_name->ein;
					} else {
						$get_bank_info  = PoDetail::get_bank_info_for_vendor($vendorid);
						$result['bankaccountinfo'] = $get_bank_info;
						$result['payeename'] = $get_payee_name;
					}
				} else {
					$result['status_code']=201;
					$result['message'] = 'Bank Account Info saved sucessfully';
				}
			} else if($action == 'getsettinginfo'){
				$get_setting_info = PoDetail::get_setting_info();
				if(isset($get_setting_info->id) && $get_setting_info->id != ''){
					$result['status_code']=200;
					$result['settings'] = $get_setting_info;
				} else {
					$result['status_code']=201;
					$result['message'] = 'Not Exists';
				}
			} else if($action == 'savesettings'){
				$get_setting_info = PoDetail::save_setting_info($test_secret_key,$test_publishable_key,$live_secret_key,$live_publishable_key,$type);
				if($get_setting_info == 1 ){
					$result['status_code']=200;
				} else {
					$result['status_code']=201;
					$result['message'] = 'Not Exists';
				}
			} else if($action == 'changesettingstatus'){
				$change_setting_status = PoDetail::change_setting_status($status);
				if($change_setting_status == 1 ){
					$result['status_code']=200;
				} else {
					$result['status_code']=201;
					$result['message'] = 'Not Exists';
				}
			} else if($action == 'getsettingstatus'){
				$get_setting_status = PoDetail::get_setting_info();
				$result['status_code']=200;
				$result['currentstatus']=$get_setting_status->status;
			} else if($action == 'makevendorpayment'){
				$get_recipient = PoDetail::get_recipient($poid);
				
				if($get_recipient != '' && $get_recipient != '0'){
					$amount = $amount*100;
					$obj=new ptStripe(STRIPE_KEY);
		
					$bank_data=$obj->createTransfer(array(
					  "amount" => $amount,
					  "currency" => "usd",
					  "recipient" => $get_recipient,
					  "description" => "Transfer from Taste"
					));
					
					if($bank_data['status']==1)
					{
							$result['status_code']=201;
							$result['message'] = $bank_data['message'];
							$json_result = str_replace('null','""',json_encode($result));
							echo $json_result;exit;
					}
					
					$transfer_id = $bank_data['data']->id;
					
					$status = ($bank_data['status'] == 0)?1:0;
				
					$update_payment_status = PoDetail::update_payment_status($poid,$transfer_id,$status);
					
					if($update_payment_status == 1){
						$result['status_code']=200;
						$result['message']='payment done sucessfully';
					}
					
				} else {
					$result['status_code']=201;
					$result['message']='not valid recipeint';
				}	
			}
				
			$json_result = str_replace('null','""',json_encode($result));
			echo $json_result;
			exit;
		}
		
	}
	
	
	public function getpodataforgraphs(){
		
		$headers = getallheaders();

		foreach ($headers as $name => $value) {
			if($name=="x-taste-request-timestamp"){
				$timestamp=$value;
			}
			if($name=="x-taste-access-token"){
				$access_token=$value;
			}			
		}
		
		$data1=$this->get_data();
		
		if (array_key_exists("fetchordertype", $data1))
		{
   		 	$type = $data1->fetchordertype;
		}
		else
		{
			 $type = '';
		
		}
		
		$validate_data = array(
			'timestamp' 	=> $timestamp,
			'access_token' 	=> $access_token,
			'type' 	=> $type,
		);

		$rules = array(
			'timestamp' 	=> 'required|validatetimestamp',
			'access_token' 	=> 'required|authenticatetoken',
			'type' 	=> 'required',
		);
		
		$messages = array(
				'timestamp.required' => 'Timestamp is required.',
				'timestamp.validatetimestamp' => 'You dont have permission to access this page. Time stamp error',
				'access_token.required' => 'Access Token is required',
				'access_token.authenticatetoken' => 'Access Token has been expired, Please generate a new one',
				'type.required' => 'Order type is required',
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
			
			$getpodetails = PoDetail::get_po_detail_with_type($type);
			if(count($getpodetails)){
				$result['status_code']=200;
				$result['status_message']='Records found';	
				$result['getpodetails']=$getpodetails;
			} else {
				$result['status_code']=201;
				$result['status_message']='No Records found';
			}
			$json_result = str_replace('null','""',json_encode($result));
			echo $json_result;
			exit;
		}
		
	}
	
}
