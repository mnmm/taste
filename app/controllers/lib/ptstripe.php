<?php
/*******************************************************************************
* PHP STRIPE API Integration Class
*******************************************************************************
* Author: Praveen Tiwari
* Email: praveen.tiwari@hashdone.com
* Website: http://www.hashdone.com
*
* File: stripe.php
* Version: 1.0
* Copyright: (c) 2014 - Praveen
* You are free to use, distribute, and modify this software
* under the terms of the GNU General Public License. See the
* included license.txt file.
*
*******************************************************************************
*/

include("Stripe.php");

class ptStripe {
    
  private  $key; // holds the exteral url of whmcs api
  public function __construct($key)
    {
        $this->key= $key;
		Stripe::setApiKey($this->key);
    }
	
	
	//*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#tokens
	//$request=array( "card" => array( "number" => "4242424242424242", "exp_month" => 4, "exp_year" => 2015, "cvc" => "314" ) );
	///******************************?////////////////////////
	
	 public function createToken($request)
	{
		
		
		try { 
		// Use Stripe's bindings... 
		$token=Stripe_Token::create($request);
		
		$ar=array("status"=>0,"data"=>$token);
		 return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		 
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  }  catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		  		 $body = $e->getJsonBody();
		         $err = $body['error'];
		         $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   		$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
			
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			 }		
	}
   //end createToken function 
   
   
   ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#create_bank_account_token
  ///******************************?////////////////////////
 public function createBankAccountToken( $request )
	{
		try { 
		$cbat=Stripe_Token::create($request);
		$ar=array("status"=>0,"data"=>$cbat);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end createBankAccountToken function





///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_token
  ///******************************?////////////////////////
 public function retrieveToken( $request )
	{
		try { 
		$rt=Stripe_Token::retrieve($request);
		$ar=array("status"=>0,"data"=>$rt);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end retrieveToken function


   
   
   
   
	
	
	//*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#create_charge
	///******************************?////////////////////////
	
	 public function createCharge($request)
	{
		
		
		try { 
		// Use Stripe's bindings... 
		$charge=Stripe_Charge::create($request);
		
		$ar=array("status"=>0,"data"=>$charge);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		   
		  }  catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		  		 $body = $e->getJsonBody();
		         $err = $body['error'];
		         $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   		$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
			
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			 }		
	}
   //end createCharge function 
	
	
 //*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#retrieve_charge
	//$request=array("customer_Id"=>"");
	///******************************?////////////////////////
	
	 public function retrieveCharge($request)
	{
		
		
		try { 
		// Use Stripe's bindings... 
		$charge=Stripe_Charge::retrieve($request['id']);
		
		$ar=array("status"=>0,"data"=>$charge);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  }  catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		  		 $body = $e->getJsonBody();
		         $err = $body['error'];
		         $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   		$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
			
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			 }		
	}
   //end retrieveCharge function	



  ///*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#update_charge
	////$request=array("customer_Id"=>"","description" =>"Charge for test@example.com");
	///******************************?////////////////////////
	
	 public function updateCharge($request)
	{
		
		
		try { 
		// Use Stripe's bindings... 
		$ch = Stripe_Charge::retrieve($request['customer_Id']);
        $ch->description = $request['description'];
        $ch->save();
		$ar=array("status"=>0,"data"=> $ch);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  }  catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		  		 $body = $e->getJsonBody();
		         $err = $body['error'];
		         $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   		$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
			
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			 }		
	}
   //end updateCharge function	
		
	
	
  ///*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#refund_charge
	//$request=array("customer_Id"=>"","param"=>array("amount"=>300));
  ///******************************?////////////////////////
	
	 public function refundCharge($request)
	{
		
		
		try { 
		// Use Stripe's bindings... 
		$ch = Stripe_Charge::retrieve($request['id']);
	///	$ch->amount=$request['amount'];
        ////$ch->refund();
		$ch1=$ch->refund("amount=" . $request['amount']);
		$ar=array("status"=>0,"data"=> $ch);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  }  catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		  		 $body = $e->getJsonBody();
		         $err = $body['error'];
		         $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   		$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
			
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			 }		
	}
   //end refundCharge function	
			
	
	
		
  ///*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#charge_capture
    //$request=array("customer_Id"=>"","param"=>array("amount"=>300));
  ///******************************?////////////////////////
	
	 public function chargeCapture($request)
	{
		
		
		try { 
		// Use Stripe's bindings... 
		$ch = Stripe_Charge::retrieve($request['customer_Id']);
        $ch->capture($request['param']);
		$ar=array("status"=>0,"data"=> $ch);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  }  catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		  		 $body = $e->getJsonBody();
		         $err = $body['error'];
		         $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   		$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
			
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			 }		
	}
   //end chargeCapture function	
			




 ///*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#list_charges
	//$request=array("count" => 3);
  ///******************************?////////////////////////
	
	 public function listCharges($request)
	{
		
		
		try { 
		// Use Stripe's bindings... 
		$ch=Stripe_Charge::all($request);
		$ar=array("status"=>0,"data"=> $ch);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  }  catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		  		 $body = $e->getJsonBody();
		         $err = $body['error'];
		         $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   		$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
			
			   $body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			 }		
	}
   //end listCharges function	
				
	
	
	
	
	//*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#create_customer
	//$request=array( "card" => array( "number" => "4242424242424242", "exp_month" => 4, "exp_year" => 2015, "cvc" => "314" ),"account_balance" =>"100", "description" => "test" ) // obtained with Stripe.js 
	///******************************?////////////////////////
 public function createCustomer($request)
	{
		try { 
		// Use Stripe's bindings... 
		$customer=Stripe_Customer::create($request);
		
		$ar=array("status"=>0,"data"=>$customer);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end createCustomer function



    //*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#retrieve_customer
	//$request=array("customer_id" => "cus_3mdKMx9VBKmqm1");
	///******************************?////////////////////////

public function retrieveCustomer($request)
	{
		try { 
		// Use Stripe's bindings... 
		$retrieve=Stripe_Customer::retrieve($request['customer_Id']);
		
		$ar=array("status"=>0,"data"=>$retrieve);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			
			 }	
	}
// end retrieveCustomer function



	//*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#update_customer
	//$request=array("customer_id" =>"",  "card" => array( "number" => "4242424242424242", "exp_month" => 4, "exp_year" => 2015, "cvc" => "314" ),"account_balance" =>"100", "description" => "test")  // obtained with Stripe.js 
	///******************************?////////////////////////
 public function updateCustomer($request )
	{
		try { 
		 $cu = Stripe_Customer::retrieve($request['customer_Id']); 
		/* if($request["description"]!=""){
		 $cu->description = $request["description"];
		 }
		 if($request["card"]!=""){
		 $cu->card = $request["card"];
		 }
		 if($request["account_balance"]!=""){
		 $cu->account_balance = $request["account_balance"];
		 }*/
		 $cu->description = $request["description"];
		 $cu->card = $request["card"];
		 $cu->account_balance = $request["account_balance"];
		 $cu->save();
		$ar=array("status"=>0,"data"=>$cu);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end updateCustomer function




	//*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#delete_customer
	//$request=array("customer_id" => "cus_3mdKMx9VBKmqm1") ;
	///******************************?////////////////////////
 public function deleteCustomer($request )
	{
		try { 
		 $cu = Stripe_Customer::retrieve($request['customer_Id']); 
		 $cu->delete();
		$ar=array("status"=>0,"data"=>$cu);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end deleteCustomer function



    //*****************************/////////////////////////////
	//https://stripe.com/docs/api#list_customers
	///******************************?////////////////////////
 public function allCustomer($request)
	{
		try { 
		$allcustomer=Stripe_Customer::all($request);
		$ar=array("status"=>0,"data"=>$allcustomer);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end allCustomer function





    //*****************************/////////////////////////////
	//https://stripe.com/docs/api/php#create_card
	//$request=array("customerId" => "cus_103meW2eZvKYlo2CqHE8boec" "param" => array("card" => array( "number" => "4242424242424242", "exp_month" => 4, "exp_year" => 2015, "cvc" => "314"));
	///******************************?////////////////////////
 public function createNewCard($request )
	{
		try { 
		$NewCard = Stripe_Customer::retrieve($request['customerId']); 
		$data=$NewCard->cards->create($request['param']);
		$ar=array("status"=>0,"data"=>$data);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		     return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end createNewcard function




  //*****************************/////////////////////////////
  	////$request=array("customer_Id" => "cus_3mdKMx9VBKmqm1","param"=>array("id"=>"","customer"=>"")) ;
	//$cu = Stripe_Customer::retrieve($request); 
	//https://stripe.com/docs/api#retrieve_card
	///******************************?////////////////////////
 public function retriveExistCard($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request['customer_Id']);
		 $card = $cu->cards->retrieve($request['param']);
		$ar=array("status"=>0,"data"=>$card);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
	            return $ar;		
			 }	
	}
//end retriveExistCard function




   //*****************************/////////////////////////////
	 //https://stripe.com/docs/api#update_card
	 //$request=array("customer_Id" => "cus_3mdKMx9VBKmqm1","param"=>array("id"=>"","customer"=>""),"name"=>"") ;
  ///******************************?////////////////////////
 public function updateCard($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request['customer_Id']); 
		$card = $cu->cards->retrieve($request["param"]);
		$card->name = $request['name']; 
		$card->save();
		$ar=array("status"=>0,"data"=>$card);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end updateCard function




  ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api#delete_card
  ///******************************?////////////////////////
 public function deleteCard($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request['customer_Id']); 
		$card = $cu->cards->retrieve($request['id'])->delete();
		$ar=array("status"=>0,"data"=>$card);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  	return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end deleteCard function



 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_cards
	 ////$request=array("customer_Id" => "cus_103meW2eZvKYlo2CqHE8boec" "param" => array("count'=>3"));
  ///******************************?////////////////////////
 public function allCard($request )
	{
		try { 

		$card = Stripe_Customer::retrieve($request['customer_Id'])->cards->all($request["param"]);
		
		$ar=array("status"=>0,"data"=>$card);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  	 return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end allCard function








  ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#create_subscription
  ///******************************?////////////////////////
 public function createSubscription($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request); 
		$cu->subscriptions->create($request);
		$ar=array("status"=>0,"data"=>$cu);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end createSubscription function





 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_subscription
  ///******************************?////////////////////////
 public function retrieveSubscription($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request['customer_Id']); 
		$subscription = $cu->subscriptions->retrieve($request['param']);
		$ar=array("status"=>0,"data"=>$subscription);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  	return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end retrieveSubscription function


///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#update_subscription
  ///******************************?////////////////////////
 public function updateSubscription($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request); 
		$subscription = $cu->subscriptions->retrieve($request);
		 $subscription->plan = $request;
		  $subscription->save();
		$ar=array("status"=>0,"data"=>$subscription);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end updateSubscription function






 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#cancel_subscription
  ///******************************?////////////////////////
 public function cancelSubscription($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request); 
		$cu->subscriptions->retrieve($request)->cancel();
		$ar=array("status"=>0,"data"=>$cu);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently) 
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			
			 }	
	}
//end cancelSubscription function




 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_subscriptions
	 //$request=array("customer_Id" => "cus_3hPFOK0e1hvJoh", "param" => array("count"=>"1"));
  ///******************************?////////////////////////
 public function listActiveSubscription($request )
	{
		try { 
		$cu=Stripe_Customer::retrieve($request['customer_Id'])->subscriptions->all($request['param']);
		$ar=array("status"=>0,"data"=>$cu);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   	  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;

		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end listActiveSubscription function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#create_plan
  ///******************************?////////////////////////
 public function createPlan($request )
	{
		try { 
		$plan=Stripe_Plan::create($request );
		$ar=array("status"=>0,"data"=>$plan);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end createPlan function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_plan
  ///******************************?////////////////////////
 public function retrievePlan($request )
	{
		try { 
		$retrievePlan=Stripe_Plan::retrieve($request);
		$ar=array("status"=>0,"data"=>$retrievePlan);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end retrievePlan function

///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#update_plan
  ///******************************?////////////////////////
 public function updatePlan($request )
	{
		try { 
		$p = Stripe_Plan::retrieve("M"); 
		$p->name = "New plan name";
		 $p->save();
		$ar=array("status"=>0,"data"=>$p);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			     return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
			 }	
	}
//end updatePlan function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#delete_plan
  ///******************************?////////////////////////
 public function deletePlan($request )
	{
		try { 
		$plan = Stripe_Plan::retrieve($request);
	    $plan->delete();
		$ar=array("status"=>0,"data"=>$plan);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  	  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end deletePlan function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_plans
  ///******************************?////////////////////////
 public function listAllPlan($request )
	{
		try { 
		$plan = Stripe_Plan::all($request);
		$ar=array("status"=>0,"data"=>$plan);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			
			 }	
	}
//end listAllPlan function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#create_coupon
  ///******************************?////////////////////////
 public function createCoupon($request )
	{
		try { 
		$coupon=Stripe_Coupon::create($request );
		$ar=array("status"=>0,"data"=>$coupon);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end createCoupon function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_coupon
  ///******************************?////////////////////////
 public function retrieveCoupon($request )
	{
		try { 
		$coupon=Stripe_Coupon::retrieve($request[id]);
		$ar=array("status"=>0,"data"=>$coupon);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end retrieveCoupon function




 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#delete_coupon
  ///******************************?////////////////////////
 public function deleteCoupon($request )
	{
		try { 
		$coupon= Stripe_Coupon::retrieve("25OFF");
		$coupon->delete();
		$ar=array("status"=>0,"data"=>$coupon);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end deleteCoupon function


///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_coupons
  ///******************************?////////////////////////
 public function listAllCoupon($request )
	{
		try { 
		$coupon = Stripe_Coupon::all($request);
		$ar=array("status"=>0,"data"=>$coupon);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end listAllCoupon function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#delete_discount
  ///******************************?////////////////////////
 public function deleteCustomerDiscount($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve($request);
	    $cu->deleteDiscount();
		$ar=array("status"=>0,"data"=>$cu);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end deleteCustomerDiscount function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#delete_subscription_discount
  ///******************************?////////////////////////
 public function deleteSubscriptionDiscount($request )
	{
		try { 
		$cu = Stripe_Customer::retrieve("cus_3mgve3vA4Cfeof"); 
		$cu->subscriptions->retrieve("sub_3mymzFgS4lFhLO")->deleteDiscount();
		$ar=array("status"=>0,"data"=>$cu);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			 }	
	}
//end deleteSubscriptionDiscount function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_invoice
  ///******************************?////////////////////////
 public function retrieveExistInvoice($request )
	{
		try { 
		$Invoice= Stripe_Invoice::retrieve($request);
		
		$ar=array("status"=>0,"data"=>$Invoice);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end retrieveExistInvoice functio





///*****************************/////////////////////////////
	 //https://stripe.com/docs/api#invoice_lines
  ///******************************?////////////////////////
 public function retrieveInvoiceLine($request )
	{
		try { 
		$Invoice= Stripe_Invoice::retrieve($request)->lines->all($request);
		
		$ar=array("status"=>0,"data"=>$Invoice);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
			 return $ar;
			 }	
	}
//end retrieveInvoiceLine function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api#create_invoice
  ///******************************?////////////////////////
 public function createInvoice($request )
	{
		try { 
		$Invoice= Stripe_Invoice::create($request);
		
		$ar=array("status"=>0,"data"=>$Invoice);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end createInvoice function





///*****************************/////////////////////////////
	 //https://stripe.com/docs/api#pay_invoice
  ///******************************?////////////////////////
 public function payInvoice($request )
	{
		try { 
		$invoice = Stripe_Invoice::retrieve($request);
		 $invoice->pay();
		$ar=array("status"=>0,"data"=>$Invoice);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  	return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end payInvoice function


  ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api#update_invoice
  ///******************************?////////////////////////
 public function updateExistInvoice($request )
	{
		try { 
		$invoice = Stripe_Invoice::retrieve($request);
        $invoice->closed = true;
        $invoice->save();
		$ar=array("status"=>0,"data"=>$Invoice);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end updateExistInvoice function



 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_customer_invoices
  ///******************************?////////////////////////
 public function listCustomerInvoices($request )
	{
		try { 
		$invoice = Stripe_Invoice::all($request);
		$ar=array("status"=>0,"data"=>$invoice);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end listCustomerInvoices function





 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_customer_invoice
  ///******************************?////////////////////////
 public function retrieveCustomerInvoice($request )
	{
		try { 
		$invoice = Stripe_Invoice::upcoming($request);
		$ar=array("status"=>0,"data"=>$Invoice);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end retrieveCustomerInvoice function






 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#create_invoiceitem
  ///******************************?////////////////////////
 public function createInvoiceItem($request )
	{
		try { 
		$invoiceItem = Stripe_InvoiceItem::create($request);

		$ar=array("status"=>0,"data"=>$invoiceItem);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  	return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end createInvoiceItem function


 ///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_invoiceitem
  ///******************************?////////////////////////
 public function retrieveInvoiceItem($request )
	{
		try { 
		$invoiceItem = Stripe_InvoiceItem::retrieve($request);

		$ar=array("status"=>0,"data"=>$invoiceItem);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end retrieveInvoiceItem function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#update_invoiceitem
  ///******************************?////////////////////////
 public function updateInvoiceItem($request )
	{
		try { 
		$ii = Stripe_InvoiceItem::retrieve($request);
        $ii->amount = $request;
        $ii->description = $request;
        $ii->save();
		$ar=array("status"=>0,"data"=>$ii);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end updateInvoiceItem function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#delete_invoiceitem
  ///******************************?////////////////////////
 public function deleteInvoiceItem($request )
	{
		try { 
		$ii = Stripe_InvoiceItem::retrieve($request);
        $ii->delete();
		$ar=array("status"=>0,"data"=>$ii);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end deleteInvoiceItem function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_invoiceitems
  ///******************************?////////////////////////
 public function listInvoiceItem( )
	{
		try { 
		$ii = Stripe_InvoiceItem::all();
		$ar=array("status"=>0,"data"=>$ii);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end listInvoiceItem function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#update_dispute
  ///******************************?////////////////////////
 public function updateDispute( $request )
	{
		try { 
		$ch = Stripe_Charge::retrieve($request);
        $ch->updateDispute($request);
		$ar=array("status"=>0,"data"=>$ch);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end updateDispute function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#close_dispute
  ///******************************?////////////////////////
 public function closeDispute( $request )
	{
		try { 
		$ch = Stripe_Charge::retrieve($request);
        $ch->closeDispute();
		$ar=array("status"=>0,"data"=>$ch);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			
			 }	
	}
//end closeDispute function


///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#create_transfer
  ///******************************?////////////////////////
 public function createTransfer( $request )
	{
		try { 
		$tr=Stripe_Transfer::create($request);
		$ar=array("status"=>0,"data"=>$tr);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end createTransfer function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_transfer
  ///******************************?////////////////////////
 public function retrieveTransfer( $request )
	{
		try { 
		$tr=Stripe_Transfer::retrieve($request);
		$ar=array("status"=>0,"data"=>$tr);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end retrieveTransfer function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#update_transfer
  ///******************************?////////////////////////
 public function updateTransfer( $request )
	{
		try { 
		$tr = Stripe_Transfer::retrieve($request);
        $tr->description = $request;
        $tr->save();
		$ar=array("status"=>0,"data"=>$tr);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			     return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
			 }	
	}
//end updateTransfer function


///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_transfers
  ///******************************?////////////////////////
 public function listTransfer( $request )
	{
		try { 
		$tr = Stripe_Transfer::all($request );
		$ar=array("status"=>0,"data"=>$tr);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			     return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			 }	
	}
//end listTransfer function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#create_recipient
  ///******************************?////////////////////////
 public function createRecipient( $request )
	{
		try { 
		$recipient = Stripe_Recipient::create($request);
		$ar=array("status"=>0,"data"=>$recipient);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end createRecipient function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_recipient
  ///******************************?////////////////////////
 public function retrieveRecipient( $request )
	{
		try { 
		$recipient = Stripe_Recipient::retrieve($request);
		$ar=array("status"=>0,"data"=>$recipient);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			
			 }	
	}
//end retrieveRecipient function


///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#update_recipient
  ///******************************?////////////////////////
 public function updateRecipient( $request )
	{
		try { 
		$rp = Stripe_Recipient::retrieve($request['id']);
		if($request['name']!="")
        $rp->name =$request['name'] ;
		if($request['tax_id']!="")
        $rp->tax_id =$request['tax_id'] ;
		if($request['bank_detail']!="")
		{
        $rp->country =$request['bank_detail']['country'] ;
		$rp->routing_number =$request['bank_detail']['routing_number'] ;
		$rp->account_number =$request['bank_detail']['account_number'] ;
		
		}
		
       $rps= $rp->save();
		$ar=array("status"=>0,"data"=>$rps);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		 
		 
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			
			 }	
	}
//end updateRecipient function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#delete_recipient
  ///******************************?////////////////////////
 public function deleteRecipient( $request )
	{
		try { 
		$rp = Stripe_Recipient::retrieve($request['id']);
        $rp->delete();
		$ar=array("status"=>0,"data"=>$rp);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end deleteRecipient function


///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_recipients
  ///******************************?////////////////////////
 public function listRecipient( )
	{
		try { 
		$rp = Stripe_Recipient::all();
   
		$ar=array("status"=>0,"data"=>$rp);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		     $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
			return $ar;
			 }	
	}
//end listRecipient function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_application_fee
  ///******************************?////////////////////////
 public function retrieveApplicationFee( $request )
	{
		try { 
		$af = Stripe_ApplicationFee::retrieve( $request);
   
		$ar=array("status"=>0,"data"=>$af );
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end retrieveApplicationFee function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#refund_application_fee
  ///******************************?////////////////////////
 public function refundApplicationFee( $request )
	{
		try { 
		$fee = Stripe_ApplicationFee::retrieve($request);
        $fee->refund();
		$ar=array("status"=>0,"data"=>$fee);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end refundApplicationFee function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_application_fees
  ///******************************?////////////////////////
 public function listApplicationFee( $request )
	{
		try { 
		$fee=Stripe_ApplicationFee::all($request);
		$ar=array("status"=>0,"data"=>$fee);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end listApplicationFee function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_account
  ///******************************?////////////////////////
 public function retrieveAccount( $request )
	{
		try { 
		$ra=Stripe_Account::retrieve($request);
		$ar=array("status"=>0,"data"=>$ra);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  	return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			     return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				  return $ar;
			 }	
	}
//end retrieveAccount function


///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_balance
  ///******************************?////////////////////////
 public function retrieveBalance( $request )
	{
		try { 
		$rb=Stripe_Balance::retrieve( $request );
		$ar=array("status"=>0,"data"=>$rb);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		    return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end retrieveBalance function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_balance_transaction
  ///******************************?////////////////////////
 public function retrieveBalanceTransaction( $request )
	{
		try { 
		$rbt=Stripe_BalanceTransaction::retrieve( $request );
		$ar=array("status"=>0,"data"=>$rbt);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		   $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end retrieveBalanceTransaction function




///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#balance_history
  ///******************************?////////////////////////
 public function balanceHistory( $request )
	{
		try { 
		$bh=Stripe_BalanceTransaction::all( $request);
		$ar=array("status"=>0,"data"=>$bh);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			    return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				 return $ar;
			 }	
	}
//end balanceHistory function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#retrieve_event
  ///******************************?////////////////////////
 public function retrieveEvent( $request )
	{
		try { 
		$re=Stripe_Event::retrieve($request);
		$ar=array("status"=>0,"data"=>$re);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		   return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end retrieveEvent function



///*****************************/////////////////////////////
	 //https://stripe.com/docs/api/php#list_events
  ///******************************?////////////////////////
 public function listEvents( $request )
	{
		try { 
		$le=Stripe_Event::all($request);
		$ar=array("status"=>0,"data"=>$le);
		return $ar;
		} catch(Stripe_CardError $e) {
		  // Since it's a decline, Stripe_CardError will be caught 
		  $body = $e->getJsonBody();
		  $err = $body['error'];
		  /* print('Status is:' . $e->getHttpStatus() . "\n"); 
		   print('Type is:' . $err['type'] . "\n");
		   print('Code is:' . $err['code'] . "\n");
		   print('Param is:' . $err['param'] . "\n");
		   print('Message is:' . $err['message'] . "\n");*/
		   $ar=array("status"=>1,"message"=>$err['message']);
		  return $ar;
		  } catch (Stripe_InvalidRequestError $e) {
		  // Invalid parameters were supplied to Stripe's API 
		      $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		  } catch (Stripe_AuthenticationError $e) {
		   // Authentication with Stripe's API failed // (maybe you changed API keys recently)
		    $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
		   } catch (Stripe_ApiConnectionError $e) {
		    // Network communication with Stripe failed
			  $body = $e->getJsonBody();
		      $err = $body['error'];
		      $ar=array("status"=>1,"message"=>$err['message']);
			  return $ar;
			} catch (Stripe_Error $e) {
			// Display a very generic error to the user, and maybe send // yourself an email
				$body = $e->getJsonBody();
		       $err = $body['error'];
		       $ar=array("status"=>1,"message"=>$err['message']);
			   return $ar;
			} catch (Exception $e) { 
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
		        $err = $body['error'];
		        $ar=array("status"=>1,"message"=>$err['message']);
				return $ar;
			 }	
	}
//end listEvents function










}//end class


//$wh=new ptStripe('sk_test_kVr0w2la2ZsXFGpeDv2XhTdT'); // class object

//$request=array("customerId" => "cus_3hPFOK0e1hvJoh", "param" => array("card" => array( "number" => "4242424242424242", "exp_month" => 4, "exp_year" => 2015, "cvc" => "314")));

//$t=$wh->deleteCustomer($request);
///print_r($t);die;
///echo $wh->login("amit.luthra@hashdone.com","test123");

?>
