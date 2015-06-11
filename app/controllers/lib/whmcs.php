<?php
/*******************************************************************************
* PHP WHMCS API Integration Class
*******************************************************************************
* Author: Praveen Tiwari
* Email: praveen.tiwari@hashdone.com
* Website: http://www.hashdone.com
*
* File: whmcs.php
* Version: 1.0
* Copyright: (c) 2014 - Praveen
* You are free to use, distribute, and modify this software
* under the terms of the GNU General Public License. See the
* included license.txt file.
*
*******************************************************************************
*/

class whmcs {
    
  private  $url; // holds the exteral url of whmcs api
  private  $adminUser;  //admin user name
  private  $adminPass;  // admin pass
  private  $accessKey;  // accessKey to access api.(should be configure in whmcs configure.php)

   public function __construct($url,$adminUser,$adminPass,$accessKey)
    {
        $this->url= $url;
		$this->adminUser= $adminUser;
		$this->adminPass= md5($adminPass);
		$this->accessKey= $accessKey;

    }

	private function fgc($url,$request)
	{
		if($url=="")
		{
			return "";
		}
		if(is_array($request))
		{

		$request["username"] = $this->adminUser; 
		$request["password"] = $this->adminPass; 
		$request["accesskey"] = $this->accessKey;
		$request["responsetype"] = "json";




		$query_string = "";
		foreach ($request AS $k=>$v) $query_string .= "$k=".urlencode($v)."&";
		$requestString=$query_string;
		}else{
		$requestString=$request;
		}
		
		
		$timeout = 0; // set to zero for no timeout
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$file_contents = curl_exec($ch);
		if (curl_error($ch)) die("Connection Error: ".curl_errno($ch).' - '.curl_error($ch));
		curl_close($ch);
		
		
		// display file
		return $file_contents;

	}

	function login($email,$pass)
	{
		$request = array();
		$request["action"]    = "validatelogin";
		$request["email"]     = $email;
		$request["password2"] = $pass;
		$jsondata             = $this->fgc($this->url,$request);
		return $jsondata;
		die;

	}

	function getclientsdetails($request)
	{
		
		$request["action"] = "getclientsdetails";
		$jsondata = $this->fgc($this->url,$request);
		return $jsondata;
		die;
		
	}
	
	function sendemail($request)
	{
		
		$request["action"] = "sendemail";
		$jsondata = $this->fgc($this->url,$request);
		return $jsondata;
		die;
		
	}

	function getemails($clientid="")
	{
		$request = array();
		$request["action"] = "getemails";
		$request["clientid"] = $clientid;
		$jsondata = $this->fgc($this->url,$request);
		return $jsondata;
		die;
		
	}

	function addclient($request)
	{
		

		$request["action"] = "addclient";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function updateclient($request)
	{
		

		$request["action"] = "updateclient";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}
function deleteclient($request)
	{
		

		$request["action"] = "deleteclient";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function getclientpassword($userid)
	{
	
		$request["userid"] = $userid;
		$request["action"] = "getclientpassword";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}


	function updateclientproduct($request)
	{
		
		$request["action"] = "updateclientproduct";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function addcancelrequest($request)
	{
		
		$request["action"] = "addcancelrequest";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}


	function getclientsproducts($clientid)
	{
		$request["clientid"] = $clientid;
		$request["action"] = "getclientsproducts";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}


	function addcontact($request)
	{
		$request["action"] = "addcontact";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function updatecontact($request)
	{
		$request["action"] = "updatecontact";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function deletecontact($contactid)
	{
		$request["contactid"] = "contactid";
		$request["action"] = "deletecontact";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function getcontacts($email)
	{
		$request["email"] = $email;
		$request["action"] = "getcontacts";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}


	function getproducts($request)
	{
		
		$request["action"] = "getproducts";		
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}


	function getinvoice($request)
	{
		

		$request["action"] = "getinvoice";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function getinvoices($request)
	{
		

		$request["action"] = "getinvoices";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}
function gettransactions($userid)
	{
		
		$request["clientid"] = $userid;
		$request["action"] = "gettransactions";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

function addinvoicepayment($request)
	{
		

		$request["action"] = "addinvoicepayment";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	function addproduct($request)
	{

		$request["action"] = "addproduct";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}
	function addorder($request)
	{
		

		$request["action"] = "addorder";
		
		$jsondata = $this->fgc($this->url,$request);
		
		return $jsondata;
		die;

	}

	function getorders($request1)
	{
		
		$request1["action"] = "getorders";
		
		$jsondata = $this->fgc($this->url,$request1);
		///print_r($request);die;
		return $jsondata;
		die;

	}
	
	function getpromotions($request)
	{

		$request["action"] = "getpromotions";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function getorderstatuses($request)
	{
		
		$request["action"] = "getorderstatuses";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function pendingorder($orderid)
	{
		$request["orderid"] = "orderid";
		$request["action"] = "pendingorder";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function deleteorder($orderid)
	{
		$request["orderid"] = "orderid";
		$request["action"] = "deleteorder";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;

	}

	function cancelorder($request)
	{
		
		
		$request["action"] = "cancelorder";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	function acceptorder($request)
	{
		

		$request["action"] = "acceptorder";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	function addcredit($request)
	{
		
		$request["action"] = "addcredit";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}

	function getcredits($request)
	{
		
		$request["action"] = "getcredits";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}


	function addtransaction($request)
	{

		$request["action"] = "addtransaction";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}


	function createinvoice($request)
	{
		

		$request["action"] = "createinvoice";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}

	function updateinvoice($request)
	{
		

		$request["action"] = "updateinvoice";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}


	function updatetransaction($request)
	{
		

		$request["action"] = "updatetransaction";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}

	function capturepayment($invoiceid,$cvv)
	{
		
		$request["invoiceid"] = $invoiceid;
		$request["cvv"] = $cvv;
		$request["action"] = "capturepayment";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}

	function getpaymentmethods()
	{
		
		$request["responsetype"] = "json";
		$request["action"] = "getpaymentmethods";
	

		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}


   public function test()
	{
		echo $this->url;
		echo $this->adminUser;
		echo $this->adminPass;
		echo $this->accessKey;
	}


//////////////////////////////for ticket systems/////////////////////////////////4
	function openticket($request)
	{
		

		$request["action"] = "openticket";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
	
	function getsupportdepartments($request)
	{
		

		$request["action"] = "getsupportdepartments";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
	function getsupportstatuses($request)
	{
		

		$request["action"] = "getsupportstatuses";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
	
	function gettickets($request)
	{
		

		$request["action"] = "gettickets";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}

	function getticket($request)
	{
		

		$request["action"] = "getticket";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
		function getticketpredefinedcats($request)
	{
		

		$request["action"] = "getticketpredefinedcats";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
		function addticketreply($request)
	{
		

		$request["action"] = "addticketreply";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
		function addticketnote($request)
	{
		

		$request["action"] = "addticketnote";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
		function deleteticket($request)
	{
		

		$request["action"] = "deleteticket";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
	function updateticket($request)
	{
		

		$request["action"] = "updateticket";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	

	function addpromotion($request)
	{
		

		$request["action"] = "addpromotion";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
	function deletepromotion($request)
	{
		

		$request["action"] = "deletepromotion";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}
	
	function updatepromotion($request)
	{
		

		$request["action"] = "updatepromotion";
		$jsondata = $this->fgc($this->url,$request);
		///print_r($request);die;
		return $jsondata;
		die;
	
	}






}//end class

//$wh=new whmcs("http://localhost/whmcs/includes/api.php","admin","admin","abc1235");
///echo $wh->login("amit.luthra@hashdone.com","test123");

?>
