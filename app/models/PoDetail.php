<?php
	class PoDetail extends Eloquent{

		public static function insert_po_details($po_no,$po_date,$vendor_id,$vendor_name,$city,$state,$country,$zip,$totalamount,$pickup_time,$status,$paid_status,$approved_by,$priority){
			
			$convert_date = strtotime($pickup_time);
			$make_due_date = strtotime("+3 day", $convert_date);
			$due_date = date('Y-m-d H:i:s', $make_due_date);
			
			$insertPoDetailAr = array('po_no' => $po_no,'po_date' => $po_date,'vendor_id'=>$vendor_id,'vendor_name'=>$vendor_name,'city'=>$city,'state'=>$state,'country'=>$country,'zip'=>$zip,'total_amount'=>$totalamount,'pickup_time'=>$pickup_time,'due_date'=>$due_date,'status'=>$status,'paid_status'=>$paid_status,'priority_status' => $priority,'approved_by'=>$approved_by,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')); 
			$id = DB::table('taste_po')->insertGetId($insertPoDetailAr);
			return $id;
			
		}

		public static function insert_po_item_details($po_no,$item_id,$name,$price,$quantity){

			$insertPoDescriptionAr = array('po_no' => $po_no,'product_id' => $item_id,'product_title'=>$name,'price'=>$price,'quantity'=>$quantity); 
			$id = DB::table('taste_po_description')->insertGetId($insertPoDescriptionAr);
			return $id;
			
		}
		
		
		public static function get_po_listings($searchby='',$orderby='',$order= '',$vendorname='',$servicedate='',$due_date_from='',$due_date_to='',$paidstatus = '',$orderid='',$orderamount = ''){
		
			$search_string = '';
			$orderby_string = '';
			if($searchby == 'all' || $searchby == 'vendorname'){
				if($vendorname != ''){
					if (strpos($vendorname,',') !== false) {
						$extractvendorname = explode(',',$vendorname);
						$search_string .= 'taste_po.vendor_id IN ("' . implode('","', $extractvendorname) . '")';
					} else {
						$search_string .= 'taste_po.vendor_id ="'.$vendorname.'"';
					}
					//$search_string .= 'taste_po.vendor_name LIKE "%'.$vendorname.'%"';
				} 
			}
			
			if($searchby == 'all' || $searchby == 'po_no'){
				if($orderid != ''){
					if($search_string != ''){
						$search_string .= ' AND taste_po.po_no ="'.$orderid.'"';
					} else {
						$search_string .= 'taste_po.po_no ="'.$orderid.'"';
					}
						
				}  
			}
			
			if($searchby == 'all' || $searchby == 'servicedate'){
				if($servicedate != ''){
					if($search_string != ''){
						$search_string .= ' AND taste_po.po_date = "'.$servicedate.'"';
					} else {
						$search_string .= 'taste_po.po_date = "'.$servicedate.'"';
					}
				}
			}
			
			if($searchby == 'all' || $searchby == 'paidstatus'){
				if (strpos($paidstatus,',') !== false) {
						$extractpaidstatus = explode(',',$paidstatus);
						if($search_string != ''){
							$search_string .= ' AND  taste_po.paid_status IN ("' . implode('","', $extractpaidstatus) . '")';
						} else {
							$search_string .= 'taste_po.paid_status IN ("' . implode('","', $extractpaidstatus) . '")';
						}
				} else {
					if($search_string != ''){
						$search_string .= ' AND taste_po.paid_status = "'.$paidstatus.'"';
					} else {
						$search_string .= 'taste_po.paid_status = "'.$paidstatus.'"';
					}
				}
			}
			
			
			if($searchby == 'all' || $searchby == 'duedate'){
				/*if($due_date_from != '' && $due_date_to != ''){
					$extract_due_date_from = explode(' ',$due_date_from);
					$extract_due_date_to = explode(' ',$due_date_to);
					if(count($extract_due_date_from) == 1 && count($extract_due_date_to) == 1){
						if($search_string != ''){
							$search_string .= ' AND taste_po.due_date >= "'.$due_date_from.' 00:00:00" AND taste_po.due_date <= "'.$due_date_to.' 23:59:59"';
						} else {
							$search_string .= 'taste_po.due_date >= "'.$due_date_from.' 00:00:00" AND taste_po.due_date <= "'.$due_date_to.' 23:59:59"';
						}
					} else {
						if($search_string != ''){
							$search_string .= ' AND taste_po.due_date = "'.$due_date_from.'" AND taste_po.due_date <="'.$due_date_to.'"';
						} else {
							$search_string .= 'taste_po.due_date = "'.$duedate.'" AND taste_po.due_date <="'.$due_date_to.'"';
						}
					}
				} else*/ if($due_date_from != ''){
					/*$extract_due_date_from = explode(' ',$due_date_from);
					if(count($extract_due_date_from) == 1){
						if($search_string != ''){
							$search_string .= ' AND taste_po.due_date >= "'.$due_date_from.' 00:00:00" AND taste_po.due_date <= "'.$due_date_to.' 23:59:59"';
						} else {
							$search_string .= ' taste_po.due_date >= "'.$due_date_from.' 00:00:00" AND taste_po.due_date <= "'.$due_date_to.' 23:59:59"';
						}
					} else {*/
						if($search_string != ''){
							$search_string .= ' AND taste_po.due_date >= "'.$due_date_from.' 00:00:00" AND taste_po.due_date <= "'.$due_date_from.' 23:59:59"';
						} else {
							$search_string .= 'taste_po.due_date >= "'.$due_date_from.' 00:00:00" AND taste_po.due_date <= "'.$due_date_from.' 23:59:59"';
						}	
					//}
				} /*else {
					$extract_due_date_to = explode(' ',$due_date_to);
					if(count($extract_due_date_to) == 1){
						if($search_string != ''){
							$search_string .= ' AND taste_po.due_date <= "'.$due_date_to.' 23:59:59"';
						} else {
							$search_string .= 'taste_po.due_date >= "'.$due_date_to.' 23:59:59"';
						}
					} else {
						if($search_string != ''){
							$search_string .= ' AND taste_po.due_date <= "'.$due_date_to.'"';
						} else {
							$search_string .= 'taste_po.due_date <= "'.$due_date_to.'"';
						}	
					}
				}*/
			}
			
			if($searchby == 'all' || $searchby == 'orderamount'){
				if (strpos($orderamount,',') !== false) {
						$extractamount = explode(',',$orderamount);
						$countamountrange = 0;
						foreach($extractamount as $amountinfo){
							$getorderamount = explode('-',$amountinfo);
							
							if($search_string != ''){
								
								if($getorderamount[1] != 'all'){
									if($countamountrange >= 1){
										$search_string .= ' OR ';
									} else {
										$search_string .= ' AND ';
									}
									$search_string .='  taste_po.total_amount BETWEEN '.$getorderamount[0].'.00 AND '.$getorderamount[1].'.00';
								} else {
									
									if($countamountrange >= 1){
										$search_string .= ' OR ';
									} else {
										$search_string .= ' AND ';
									}
									$search_string .= ' taste_po.total_amount >= '.$getorderamount[0].'.00';
								}
							} else {
								if($getorderamount[1] != 'all'){
									$search_string .= 'taste_po.total_amount BETWEEN '.$getorderamount[0].'.00 AND '.$getorderamount[1].'.00';
								} else {
									$search_string .= 'taste_po.total_amount >= '.$getorderamount[0].'.00';
								}
							}
							$countamountrange++;
						}
					
				} else {
					$getorderamount = explode('-',$orderamount);
					if($search_string != ''){
						if($getorderamount[1] != 'all'){
							$search_string .= ' AND  taste_po.total_amount BETWEEN '.$getorderamount[0].'.00 AND '.$getorderamount[1].'.00';
						} else{
							$search_string .= ' AND  taste_po.total_amount >= '.$getorderamount[0].'.00';
						}
					} else {
						if($getorderamount[1] != 'all'){
							$search_string .= 'taste_po.total_amount BETWEEN '.$getorderamount[0].'.00 AND '.$getorderamount[1].'.00';
						} else {
							$search_string .= ' AND  taste_po.total_amount >= '.$getorderamount[0].'.00';
						}
					}
				}
			}
			
			
			$orderbyfield = '';
			if($orderby != ''){
				if($orderby == 'vendorname'){
					$orderbyfield = 'vendor_name';
				}
				
				else if($orderby == 'status'){
					$orderbyfield = 'status';
				}
				
				else if($orderby == 'paidstatus'){
					$orderbyfield = 'paid_status';
				}
				
				else if($orderby == 'pono'){
					$orderbyfield = 'po_no';
				}
				
				else if($orderby == 'pickuptime'){
					$orderbyfield = 'pickup_time';
				}
				
				else if($orderby == 'duedate'){
					$orderbyfield = 'due_date';
				} 
				
				else {
					$orderbyfield = 'po_date';
				}
				
			} else {
				$orderbyfield = 'po_date';
			}
			$orderstring = 'ORDER BY '.$orderbyfield.' '.$order;
			if($search_string != ''){
				//echo "SELECT taste_po .* FROM taste_po WHERE $search_string $orderstring";
				$po_listing =  DB::select(DB::raw("SELECT taste_po .* FROM taste_po WHERE $search_string $orderstring"));
			} else { 
				//echo "SELECT taste_po .* FROM taste_po $orderstring";
				$po_listing =  DB::select(DB::raw("SELECT taste_po .* FROM taste_po $orderstring"));
			}
			
			return $po_listing;
		}
		
		public static function get_po_detail($po_no){

			$po_details = DB::table('taste_po_description')->select('taste_po_description.product_id','taste_po_description.product_title','taste_po_description.price','taste_po_description.quantity')->where('taste_po_description.po_no','=',$po_no)->get();
			return $po_details;
		}
		
		
		public static function get_unpaid_po(){

			$po_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as unpaidorders,SUM(total_amount) as unpaidamount'))->where('paid_status','=','unpaid')->first();
			
			return $po_details;
		}
		
		/*public static function get_unpaid_amount_total(){

			$po_details = DB::table('taste_po')->select(DB::raw('SUM(total_amount) as unpaidamount'))->where('paid_status','=','unpaid')->first();
			return $po_details;
		}*/
		
		public static function get_next_week_po(){
			
			$getsunday  = (new DateTime('next Sunday'))->format("Y-m-d");
			$po_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as unpaidnextweekorders,SUM(total_amount) as unpaidnextweekamount'))->where('paid_status','=','unpaid')->where('due_date','>',$getsunday)->first();
			
			return $po_details;
		}
		
		public static function get_po_detail_with_type($type){
			
			$paidorderArr = array();
			$totalamount = 0;
			$totalorders = 0;
			if($type == 'today'){
				$current_time = $lounge_close_time = date('H:i');
				$timeo = strtotime($current_time);
				$check_new_lounge_time       = date("m-d-Y H:i", strtotime(START_TIME, $timeo));
				$check_new_lounge_split_date = explode('-', $check_new_lounge_time);
				$check_new_lounge_date       = $check_new_lounge_split_date[1];
				$today                       = date('d');

				if ($check_new_lounge_date != $today) {        
					$lounge_new_open_time = '00:00';
				} else {
					$lounge_new_open_time = date("H:i", strtotime(START_TIME, $timeo));
				}
				
				$extract_starting_hour = explode(':',$lounge_new_open_time);
				$starting_hour = $extract_starting_hour[0];
				$closing_hour = date('H');
				//echo 'lounge_new_open_time'.$lounge_new_open_time;
				//echo '$lounge_close_time'.$lounge_close_time;exit;
				$tStart = strtotime($lounge_new_open_time);
				$tEnd = strtotime($lounge_close_time);
				$tCrnt =  strtotime($current_time);
				$tNow = $tStart;
				while($tNow <= $tEnd){
					$start_time =  date("Y-m-d H:i",$tNow);
					$start_hour = date('H:i',$tNow);
					$tNow = strtotime('+60 minutes',$tNow);
					$end_time = date("Y-m-d H:i",$tNow);
					$po_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as paidorders,SUM(total_amount) as paidamount'))->where('paid_status','=','paid')->where('due_date','>=',$start_time)->where('due_date','<=',$end_time)->first();	
					
					if(count($po_details) > 0){
						
						if($po_details->paidamount != '')
							$paidamount = $po_details->paidamount;
						else 
							$paidamount = 0;
						
						$totalamount += $paidamount;
						$totalorders += 1;
						$paidorderArr[] = array('time'=>$start_hour,'paidorders' => $po_details->paidorders ,'paidamount' => $paidamount);
					} else {
						$paidorderArr[] = array('time'=>$start_hour,'paidorders' => 0 ,'paidamount' => 0);
					}
				}
			} else if($type == 'week'){
				//$start_date = date('Y-m-d', strtotime('-7 days'));
				$start_date = date('Y-m-d', strtotime('last week'));
				$end_date = date("Y-m-d", strtotime('sunday last week'));
			//	$today = date('Y-m-d');
				while (strtotime($start_date) <= strtotime($end_date)) {
					$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
					$make_start_date = $start_date.' '.'00:00:00';
					$make_end_date = $start_date.' '.'23:59:59';
					$po_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as paidorders,SUM(total_amount) as paidamount'))->where('paid_status','=','paid')->where('due_date','>=',$make_start_date)->where('due_date','<=',$make_end_date)->first();	
					if(count($po_details) > 0){
						
						if($po_details->paidamount != '')
							$paidamount = $po_details->paidamount;
						else 
							$paidamount = 0;
							
						$totalamount += $paidamount;
						$totalorders += 1;
						$paidorderArr[] = array('time'=>$start_date,'paidorders' => $po_details->paidorders ,'paidamount' => $paidamount);
					} else {
						$paidorderArr[] = array('time'=>$start_date,'paidorders' => 0 ,'paidamount' => 0);
					}
				}
				
		    } else {
				$start_date = date('Y-m-d', strtotime('first day of last month'));
				$end_date = date('Y-m-d', strtotime('last day of last month'));
				while (strtotime($start_date) <= strtotime($end_date)) {
					$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
					$make_start_date = $start_date.' '.'00:00:00';
					$make_end_date = $start_date.' '.'23:59:59';
					$po_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as paidorders,SUM(total_amount) as paidamount'))->where('paid_status','=','paid')->where('due_date','>=',$make_start_date)->where('due_date','<=',$make_end_date)->first();	
					if(count($po_details) > 0){
						
						if($po_details->paidamount != '')
							$paidamount = $po_details->paidamount;
						else 
							$paidamount = 0;
						
						$totalamount += $paidamount;
						$totalorders += 1;
						$paidorderArr[] = array('time'=>$start_date,'paidorders' => $po_details->paidorders ,'paidamount' => $paidamount);
					} else {
						$paidorderArr[] = array('time'=>$start_date,'paidorders' => 0 ,'paidamount' => 0);
					}
				}
			}
			$paidorderArr[] = array('totalamount' => $totalamount, 'totalorders' => $totalorders);
			return $paidorderArr;
		}
		
		
		public static function get_next_week_due_detail(){
			//$next_week_date = date('Y-m-d', strtotime('next week'));
			$start_date = date('Y-m-d');
			$next_week_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$unpaid_details = DB::table('taste_po')->select('taste_po.po_no','taste_po.vendor_name','taste_po.total_amount','taste_po.due_date')->where('due_date','>=',$next_week_date)->get();
			return $unpaid_details;
		}
		
		public static function get_vendor_orders_detail($type){
			$vendororderArr = array();
			$vendorpaidorderArr = array();
			$vendorunpaidorderArr = array();
			$totalamount = 0;
			$totalorders = 0;
			$totalunpaidorders = 0;
			$totalpaidorders = 0;
			if($type == 'today'){
				$start_time =  date("Y-m-d 00:00:00");
				$po_paid_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as paidorders,SUM(total_amount) as paidamount,vendor_name,vendor_id'))->where('paid_status','=','paid')->where('due_date','>=',$start_time)->groupBy('vendor_id')->get();
				
				$po_unpaid_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as unpaidorders,SUM(total_amount) as unpaidamount,vendor_name,vendor_id'))->where('paid_status','=','unpaid')->where('due_date','>=',$start_time)->groupBy('vendor_id')->get();

				
			} else if($type == 'week') {
				
				$start_date = date('Y-m-d', strtotime('last week'));
				$end_date = date("Y-m-d", strtotime('sunday last week'));
				$make_start_date = $start_date.' '.'00:00:00';
				$make_end_date = $end_date.' '.'23:59:59';
				$po_paid_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as paidorders,SUM(total_amount) as paidamount,vendor_name,vendor_id'))->where('paid_status','=','paid')->where('due_date','>=',$make_start_date)->where('due_date','<=',$make_end_date)->groupBy('vendor_id')->get();
				
				$po_unpaid_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as unpaidorders,SUM(total_amount) as unpaidamount,vendor_name,vendor_id'))->where('paid_status','=','unpaid')->where('due_date','>=',$make_start_date)->where('due_date','<=',$make_end_date)->groupBy('vendor_id')->get();
				
				
			} else {
				$start_date = date('Y-m-d', strtotime('first day of last month'));
				$end_date = date('Y-m-d', strtotime('last day of last month'));
				
				$make_start_date = $start_date.' '.'00:00:00';
				$make_end_date = $end_date.' '.'23:59:59';
				
				$po_paid_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as paidorders,SUM(total_amount) as paidamount,vendor_name,vendor_id'))->where('paid_status','=','paid')->where('due_date','>=',$make_start_date)->where('due_date','<=',$make_end_date)->groupBy('vendor_id')->get();
				
				$po_unpaid_details = DB::table('taste_po')->select(DB::raw('COUNT(*) as unpaidorders,SUM(total_amount) as unpaidamount,vendor_name,vendor_id'))->where('paid_status','=','unpaid')->where('due_date','>=',$make_start_date)->where('due_date','<=',$make_end_date)->groupBy('vendor_id')->get();
			}
			
			$paidvendorArr = array();
			if(count($po_paid_details) > 0){
					foreach($po_paid_details as $info){
						$paidvendorArr[] = $info->vendor_id;
						$vendorpaidorderArr[$info->vendor_id]['vendor_id'] =  $info->vendor_id;
						$vendorpaidorderArr[$info->vendor_id]['paidorders'] =  $info->paidorders;
						$vendorpaidorderArr[$info->vendor_id]['paidamount'] =  $info->paidamount;
						$vendorpaidorderArr[$info->vendor_id]['vendorname'] =  $info->vendor_name;
					}
				}
				
			$unpaidvendorArr = array();
			if(count($po_unpaid_details) > 0){
				foreach($po_unpaid_details as $info){
					$unpaidvendorArr[] = $info->vendor_id;
					$vendorunpaidorderArr[$info->vendor_id]['vendor_id'] =  $info->vendor_id;
					$vendorunpaidorderArr[$info->vendor_id]['unpaidorders'] =  $info->unpaidorders;
					$vendorunpaidorderArr[$info->vendor_id]['unpaidamount'] =  $info->unpaidamount;
					$vendorunpaidorderArr[$info->vendor_id]['vendorname'] =  $info->vendor_name;
				}
			}
			/*echo "<pre>";
			print_r($vendorpaidorderArr);
			print_r($vendorunpaidorderArr);*/
			$common_array = array();
			if(count($vendorpaidorderArr) > 0){
				if(count($vendorunpaidorderArr) > 0){
					
					foreach($vendorpaidorderArr as $orderinfo){
							
						if(isset($vendorunpaidorderArr[$orderinfo['vendor_id']])){
							$common_array[] = $orderinfo['vendor_id'];
							$vendororderArr[$orderinfo['vendor_id']]['vendor_id'] = $orderinfo['vendor_id'];
							$vendororderArr[$orderinfo['vendor_id']]['paidamount'] = $orderinfo['paidamount'];
							$vendororderArr[$orderinfo['vendor_id']]['paidorders'] = $orderinfo['paidorders'];
							$vendororderArr[$orderinfo['vendor_id']]['vendorname'] = $orderinfo['vendorname'];
							$vendororderArr[$orderinfo['vendor_id']]['unpaidorders'] = $vendorunpaidorderArr[$orderinfo['vendor_id']]['unpaidorders'];
							$vendororderArr[$orderinfo['vendor_id']]['orders'] = $vendorunpaidorderArr[$orderinfo['vendor_id']]['unpaidorders'] + $orderinfo['paidorders'];
						} else {
							
							$vendororderArr[$orderinfo['vendor_id']]['vendor_id'] = $orderinfo['vendor_id'];
							$vendororderArr[$orderinfo['vendor_id']]['paidamount'] = $orderinfo['paidamount'];
							$vendororderArr[$orderinfo['vendor_id']]['paidorders'] = $orderinfo['paidorders'];
							$vendororderArr[$orderinfo['vendor_id']]['vendorname'] = $orderinfo['vendorname'];
							$vendororderArr[$orderinfo['vendor_id']]['unpaidorders'] = 0;
							$vendororderArr[$orderinfo['vendor_id']]['orders'] = $orderinfo['paidorders'];
						}
					}
				} else {
					foreach($vendorpaidorderArr as $orderinfo){

						$vendororderArr[$orderinfo['vendor_id']]['vendor_id'] = $orderinfo['vendor_id'];
						$vendororderArr[$orderinfo['vendor_id']]['paidamount'] = $orderinfo['paidamount'];
						$vendororderArr[$orderinfo['vendor_id']]['paidorders'] = $orderinfo['paidorders'];
						$vendororderArr[$orderinfo['vendor_id']]['vendorname'] = $orderinfo['vendorname'];
						$vendororderArr[$orderinfo['vendor_id']]['unpaidorders'] = 0;
						$vendororderArr[$orderinfo['vendor_id']]['orders'] = $orderinfo['paidorders'];
					
					}
				}
				/*echo "<pre>";
				print_r($paidvendorArr);
				print_r($unpaidvendorArr);
				print_r($common_array);*/
				if(count($unpaidvendorArr) > 0){
					if(count($common_array) > 0){
						foreach($vendorunpaidorderArr as $oinfo){
							if(in_array($oinfo['vendor_id'], $common_array)){
							} else {
								$vendororderArr[$oinfo['vendor_id']]['vendor_id'] = $oinfo['vendor_id'];
								$vendororderArr[$oinfo['vendor_id']]['paidamount'] = 0;
								$vendororderArr[$oinfo['vendor_id']]['paidorders'] = 0;
								$vendororderArr[$oinfo['vendor_id']]['vendorname'] = $oinfo['vendorname'];
								$vendororderArr[$oinfo['vendor_id']]['unpaidorders'] = $oinfo['unpaidorders'];
								$vendororderArr[$oinfo['vendor_id']]['orders'] = $oinfo['unpaidorders'];
							}
						}
					} else {
						foreach($vendorunpaidorderArr as $oinfo){
							
								$vendororderArr[$oinfo['vendor_id']]['vendor_id'] = $oinfo['vendor_id'];
								$vendororderArr[$oinfo['vendor_id']]['paidamount'] = 0;
								$vendororderArr[$oinfo['vendor_id']]['paidorders'] = 0;
								$vendororderArr[$oinfo['vendor_id']]['vendorname'] = $oinfo['vendorname'];
								$vendororderArr[$oinfo['vendor_id']]['unpaidorders'] = $oinfo['unpaidorders'];
								$vendororderArr[$oinfo['vendor_id']]['orders'] = $oinfo['unpaidorders'];
							
						}
					}
				}
			} else {
				
				if(count($vendorunpaidorderArr) > 0){
					foreach($vendorunpaidorderArr as $orderinfo){
						$vendororderArr[$orderinfo['vendor_id']]['vendor_id'] = $orderinfo['vendor_id'];
						$vendororderArr[$orderinfo['vendor_id']]['paidamount'] = 0;
						$vendororderArr[$orderinfo['vendor_id']]['paidorders'] = 0;
						$vendororderArr[$orderinfo['vendor_id']]['vendorname'] = $orderinfo['vendorname'];
						$vendororderArr[$orderinfo['vendor_id']]['unpaidorders'] = $orderinfo['unpaidorders'];
						$vendororderArr[$orderinfo['vendor_id']]['orders'] = $orderinfo['unpaidorders'];
					}
				}
			}
			return $vendororderArr;
		}
		
		public static function get_all_vendor_details(){

			$vendors_details = DB::table('taste_po')->select(DB::raw('DISTINCT(taste_po.vendor_id),taste_po.vendor_name'))->get();
			
			return $vendors_details;
		}
		
		public static function get_request_info($vendorid){

			$request_details = DB::table('request_info')->select('request_info.*')->where('request_info.vendor_id','=',$vendorid)->get();
			
			return $request_details;
		}
		
		public static function get_vendor_details($accesshash,$email= ''){
			$vendorArr = array();
			if($accesshash != ''){
				$request_details = DB::table('payment_auth_code')->select('payment_auth_code.*')->where('auth_code','=',$accesshash)->where('status','=',1)->get();
				
				//print_r($request_details);exit;
				if(count($request_details) > 0){
					
					$decrypt_hash_id = base64_decode($accesshash);
					$get_vendor_id = explode('@@@',$decrypt_hash_id);
				
					if($get_vendor_id[1] != '' && $get_vendor_id[1] != 0){
						
						$vendordetails = DB::table('taste_po')->select('taste_po.id','taste_po.vendor_email')->where('vendor_id','=',$get_vendor_id[1])->get();
						
						if($vendordetails[0]->id != '' && $vendordetails[0]->vendor_email != ''){
							
								$get_user_id = DB::table('users')->where('email',$vendordetails[0]->vendor_email)->get();
								if(count($get_user_id) > 0){
									$id = $get_user_id[0]->id;
									$currentuser = User::find($id);
									$usergroup = $currentuser->usertype;
									$status = $currentuser->status;
									if($currentuser->tax_id != ''){
										$w9_signed = $currentuser->tax_id;
										$vendorArr['w9signed'] = 1;
									} else {
										$vendorArr['w9signed'] = 0;
									}
									if($usergroup == 2) {
										if($status == 1) {	
											
											$vendorArr['aleadyexists'] = $vendordetails[0]->vendor_email;
											$vendorArr['userid'] = $currentuser->id;
											$vendorArr['username'] = $currentuser->name;
										} 
									}
								} else {
									$vendorArr['email'] = $vendordetails[0]->vendor_email;
								}
						} else {
							$vendorArr['tokenstatus'] = 0;
						}
					} else {
						$vendorArr['tokenstatus'] = 0;
					}
				} else{
					$vendorArr['tokenstatus'] = 0;
				}
			
			} else {
				$get_user_id = DB::table('users')->where('email',$email)->get();
				if(count($get_user_id) > 0){
					$id = $get_user_id[0]->id;
					$currentuser = User::find($id);
					$usergroup = $currentuser->usertype;
					$status = $currentuser->status;
					if($currentuser->tax_id != ''){
						$w9_signed = $currentuser->tax_id;
						$vendorArr['w9signed'] = 1;
					} else {
						$vendorArr['w9signed'] = 0;
					}
					if($usergroup == 2) {
						if($status == 1) {	
							
							$vendorArr['aleadyexists'] = $vendordetails[0]->vendor_email;
							$vendorArr['userid'] = $currentuser->id;
							$vendorArr['username'] = $currentuser->name;
						} 
					}
				} else {
					$vendorArr['email'] = $vendordetails[0]->vendor_email;
				}
			}
			
			return $vendorArr;
		}
		
		public static function check_vendor_exists($accesshash){

			$request_details = DB::table('payment_auth_code')->select('payment_auth_code.*')->where('auth_code','=',$accesshash)->where('status','=',1)->get();
			$vendorArr = array();
			if(count($request_details) > 0){
				$decrypt_hash_id = base64_decode($accesshash);
				$get_vendor_id = explode('@@@',$decrypt_hash_id);
				if($get_vendor_id[1] != '' && $get_vendor_id[1] != 0){
					$vendordetails = DB::table('taste_po')->select('taste_po.id','taste_po.vendor_email')->where('vendor_id','=',$get_vendor_id[1])->first();
					
					if($vendordetails->id != '' && $vendordetails->vendor_email != ''){
						
						$vendorArr['email'] = $vendordetails->vendor_email;
					} else {
						$vendorArr['tokenstatus'] = 0;
					}
				} else {
					$vendorArr['tokenstatus'] = 0;
				}
			} else{
				$vendorArr['tokenstatus'] = 0;
			}
			
			return $vendorArr;
		}
		
		public static function create_vendor_account($email,$password){
			
			$check_vendor_account = DB::table('users')->where('email',$email)->first();
			if(isset($check_vendor_account->id) && $check_vendor_account->id != ''){
				$reg_id = $check_vendor_account->id;
			} else {
				$token=time();
				$register = new Register;
				$get_vendor_name_info = DB::table('taste_po')->where('vendor_email',$email)->first();
				$register->name = $get_vendor_name_info->vendor_name;
				$register->email = $email;
				$register->password = Hash::make($password);
				$register->usertype = 2;
				$register->status =  1;
				$register->save();
				$reg_id = $register->id;
			}
			return $reg_id;
			
		}
		
		public static function save_tax_info($email,$ssn_last4,$ssn_enc,$ein,$payeename){
			
			$check_vendor_account = DB::table('users')->where('email',$email)->first();
			if(isset($check_vendor_account->id) && $check_vendor_account->id != ''){
				$reg_id = $check_vendor_account->id;
				DB::table('w9form')->where('vendorid', '=', $reg_id)->delete();
				
				$id = DB::table('w9form')->insertGetId(
				array('vendorid' => $reg_id, 'email' => $email, 'ssn_last4' => $ssn_last4, 'ssn_enc' => $ssn_enc,'ein' => $ein,'payeename' => $payeename, 'created' => date('Y-m-d')));
				
				DB::table('users')->where('id','=',$reg_id)->update(array('tax_id' => $ein));
				return 1;
			} else {
				return 0;
			}

		}
		
		public static function check_w9_signed($email){
			
			$check_vendor_account = DB::table('users')->where('email',$email)->first();
			if(isset($check_vendor_account->tax_id) && $check_vendor_account->tax_id != ''){
				return 1;
			} else {
				return 0;
			}

		}
		
		public static function get_tax_info($vendorid){
			
			$check_vendor_account = DB::table('users')->where('id',$vendorid)->first();
			if(isset($check_vendor_account->id) && $check_vendor_account->id != ''){
				$tax_id = $check_vendor_account->tax_id;
				return $tax_id;
			} else {
				return 0;
			}

		}
		
		public static function get_vendor_account_info($vendorid){
			
			$get_vendor_info =  DB::table('users')->where('id','=',$vendorid)->first();
			$vendor_name = $get_vendor_info->name;
			
			return $vendor_name;
			
			
		}
		
		public static function save_bank_account_info($vendorid,$get_vendor_name,$routing_number,$account_number,$recipient,$tax_id,$country,$mailingaddress,$authcode,$paymenttype){
			
			$insertBankDetailAr = array('vendorid' => $vendorid,'name' => $get_vendor_name,'bank_name'=>'','type'=>'individual','account_number'=>$account_number,'tax_id'=>$tax_id,'country'=>$country,'routing_number'=>$routing_number,'recipient'=>$recipient,'mailing_address' => $mailingaddress,'paymenttype'=>$paymenttype); 
			
			$id = DB::table('bank_detail')->insertGetId($insertBankDetailAr);
			
			if($id != '' && $authcode != ''){
				$expire_auth_code = DB::table('payment_auth_code')->where('auth_code','=',$authcode)->first();
				if(isset($expire_auth_code->id ) && $expire_auth_code->id != ''){
					DB::table('payment_auth_code')->where('auth_code','=',$authcode)->update(array('status' => 0));
					return $id;
				} else {
					return 0;
				} 
			} else {
				return 0;
			}
		}
		
		
		public static function get_payee_info($vendorid){
			
			$get_vendor_info =  DB::table('w9form')->where('vendorid','=',$vendorid)->first();
			
			return $get_vendor_info;
			
			
		}
		
		public static function check_bank_details_filled($vendoremail){
			
			$get_vendor_info =  DB::table('users')->where('email','=',$vendoremail)->first();
			if(isset($get_vendor_info->id) && $get_vendor_info->id != ''){
				$userid = $get_vendor_info->id;
				$bankdetail = DB::table('bank_detail')->where('vendorid','=',$userid)->first();
				if(isset($bankdetail->id) && $bankdetail->id != ''){
					return 1;
				} else{
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		public static function check_bank_account_exists($vendorid){
			
			$get_vendor_info =  DB::table('users')->where('id','=',$vendorid)->first();
			if(isset($get_vendor_info->id) && $get_vendor_info->id != ''){
				$userid = $get_vendor_info->id;
				$bankdetail = DB::table('bank_detail')->where('vendorid','=',$userid)->first();
				if(isset($bankdetail->id) && $bankdetail->id != ''){
					return $bankdetail->paymenttype;
				} else{
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		public static function get_bank_account_detail_automatic($vendorid){
			
			$get_vendor_info =  DB::table('users')->where('id','=',$vendorid)->first();
			if(isset($get_vendor_info->id) && $get_vendor_info->id != ''){
				$userid = $get_vendor_info->id;
				$bankdetail = DB::table('bank_detail')->where('vendorid','=',$userid)->first();
				if(isset($bankdetail->id) && $bankdetail->id != ''){
					return $bankdetail;
				} else{
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		public static function get_bank_account_detail_manual($vendorid){
			$get_vendor_info =  DB::table('users')->where('id','=',$vendorid)->first();
			if(isset($get_vendor_info->id) && $get_vendor_info->id != ''){
				$userid = $get_vendor_info->id;
				$bankdetail = DB::table('bank_details_info_manual')->where('vendorid','=',$userid)->first();
				if(isset($bankdetail->id) && $bankdetail->id != ''){
					return $bankdetail;
				} else{
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		public static function get_bank_info_for_vendor($vendorid){
			
			$get_bank_info =  DB::table('bank_detail')->where('vendorid','=',$vendorid)->first();
			
			return $get_bank_info;
			
			
		}
		
		public static function get_recipient_info($vendorid){
			
			$get_bank_info =  DB::table('bank_detail')->where('vendorid','=',$vendorid)->first();
			
			return $get_bank_info->recipient;
			
			
		}
		
		public static function update_bank_account_info($bankid,$vendorid,$get_vendor_name,$routing_number,$account_number,$recipient,$tax_id,$country,$mailingaddress,$authcode,$paymenttype){
			
			$insertBankDetailAr = array('vendorid' => $vendorid,'name' => $get_vendor_name,'bank_name'=>'','type'=>'individual','account_number'=>$account_number,'tax_id'=>$tax_id,'country'=>$country,'routing_number'=>$routing_number,'recipient'=>$recipient,'mailing_address' => $mailingaddress,'paymenttype'=>$paymenttype); 
			
			DB::table('bank_detail')->where('id','=',$bankid)->update($insertBankDetailAr);
			
			if($authcode != ''){
				$expire_auth_code = DB::table('payment_auth_code')->where('auth_code','=',$authcode)->first();
				if(isset($expire_auth_code->id ) && $expire_auth_code->id != ''){
					DB::table('payment_auth_code')->where('auth_code','=',$authcode)->update(array('status' => 0));
					return $bankid;
				} else {
					return 0;
				} 
			} else {
				return 0;
			}
		}
		
		
		//function to get admin level settings	
		public static function get_setting_info(){
			
			$settings =  DB::table('taste_settings')->first();
			
			return $settings;
			
			
		}
			
		
	   //function to save and update the admin level settings	
	   public static function save_setting_info($test_secret_key,$test_publishable_key,$live_secret_key,$live_publishable_key,$type){
			
				if($type == 0){
					
					$insertSettingsAr = array('stripe_test_secret_key' => $test_secret_key,'stripe_test_publishable_key' => $test_publishable_key,'stripe_live_secret_key'=>$live_secret_key,'stripe_live_publishable_key'=>$live_publishable_key); 

					$id = DB::table('taste_settings')->insertGetId($insertSettingsAr);
					
					if($id != ''){
						return 1;
					} else {
						return 0;
					}
				} else {
					
					$updateSettingsAr = array('stripe_test_secret_key' => $test_secret_key,'stripe_test_publishable_key' => $test_publishable_key,'stripe_live_secret_key'=>$live_secret_key,'stripe_live_publishable_key'=>$live_publishable_key); 
					
					DB::table('taste_settings')->where('id','=',1)->update($updateSettingsAr);
					
					return 1;
				}
			
		}
		
		//function to change setting status
		 public static function change_setting_status($status){
			 $new_status = ($status == 0)? 1:0;
			 $check_setting_exists = DB::table('taste_settings')->where('id','=',1)->first();
			 if(isset($check_setting_exists->id) && $check_setting_exists->id != ''){
				DB::table('taste_settings')->where('id','=',1)->update(array('status' => $new_status));
				return 1;
			} else {
				return 0;
			}
		 }
		 
		 
		 //function to get recipient
		 public static function get_recipient($poid){
			
			$get_vendor_info = DB::table('taste_po')->where('po_no','=',$poid)->first();
			if(isset($get_vendor_info->id) && $get_vendor_info->id != ''){
				$get_user_id = DB::table('users')->where('email',$get_vendor_info->vendor_email)->get();
				if(count($get_user_id) > 0){
					$id = $get_user_id[0]->id;
					
					$get_bank_detail = DB::table('bank_detail')->select('recipient')->where('vendorid','=',$id)->first();
					
					if(isset($get_bank_detail->recipient) && $get_bank_detail->recipient != ''){
						return $get_bank_detail->recipient;
					} else {
						return 0;
					}
				}
			} else {
				return 0;
			}
		 }
		
	   //function to update payment status
	   public static function update_payment_status($poid,$transfer_id,$status){
			
			 $upadte_payment_status_po = DB::table('taste_po')->where('po_no','=',$poid)->update(array('vendor_paid_status'=> $status,'stripe_payment_id' => $transfer_id,'payment_type' => 1 ));
			 
			 return 1;
	   }
	   
	   
	   //function to update payment status
	   public static function get_user_detail_for_transfer($userid){

			 $user_details = DB::table('users')->where('id','=',$userid)->first();
			 
			 return $user_details->name;
	   }
	   
	   //function to update payment status
		public static function check_transfer_option($vendorid){
				
				$get_vendor_info =  DB::table('users')->where('id','=',$vendorid)->first();
				return $get_vendor_info->transfer_option;
		}

		//function to update payment status
		public static function update_vendor_transfer_choice($vendorid,$transferstatus){
				
				$update_transfer =  DB::table('users')->where('id','=',$vendorid)->update(array('transfer_option' => $transferstatus ));
				return 1;
		}
		
		//function to save manual bank account info
		/*public static function save_manual_bank_info($vendorid,$payeename,$mailingaddress,$zipcode,$authcode){
			
			$insertBankDetailAr = array('vendorid' => $vendorid,'payee_name' => $payeename,'mailing_address' => $mailingaddress,'zipcode'=>$zipcode); 
			
			$id = DB::table('manual_bank_details')->insertGetId($insertBankDetailAr);
			
			if($id != '' && $authcode != ''){
				$expire_auth_code = DB::table('payment_auth_code')->where('auth_code','=',$authcode)->first();
				if(isset($expire_auth_code->id ) && $expire_auth_code->id != ''){
					DB::table('payment_auth_code')->where('auth_code','=',$authcode)->update(array('status' => 0));
					return $id;
				} else {
					return 0;
				} 
			} else {
				return 0;
			}
		}*/
		
		//function to save manual bank account info
		public static function save_manual_bank_info($vendorid,$check,$checkdate,$carrier,$airwaybill,$mailingaddress,$authcode){
			if($authcode != ''){
				$convertdate = explode('-',$checkdate);
				$new_check_date = $convertdate[2].'-'.$convertdate[0].'-'.$convertdate[1];
				$expire_auth_code = DB::table('payment_auth_code')->where('auth_code','=',$authcode)->first();
				if(isset($expire_auth_code->id ) && $expire_auth_code->id != '' && $expire_auth_code->status == 1){
					$insertBankDetailAr = array('vendorid' => $vendorid,'check' => $check,'checkdate'=>$new_check_date,'carrier'=>$carrier,'airwaybill'=>$airwaybill,'mailing_address' => $mailingaddress); 
					$id = DB::table('manual_bank_details')->insertGetId($insertBankDetailAr);
					DB::table('payment_auth_code')->where('auth_code','=',$authcode)->update(array('status' => 0));
					return $id;
				} else {
					return 0;
				} 
			} else {
				return 0;
			}
		}
		
		
		//function to save manual bank account info
		public static function save_manual_bank_info_new($vendorid,$bankname,$nameonaccount,$routing_number,$account_number,$mailingaddress,$authcode){
			if($authcode != ''){
				$expire_auth_code = DB::table('payment_auth_code')->where('auth_code','=',$authcode)->first();
				if(isset($expire_auth_code->id ) && $expire_auth_code->id != '' && $expire_auth_code->status == 1){
					$insertBankDetailAr = array('vendorid' => $vendorid,'bankname' => $bankname,'nameonaccount'=>$nameonaccount,'routing_number'=>$routing_number,'account_number'=>$account_number,'mailing_address' => $mailingaddress); 
					$id = DB::table('bank_details_info_manual')->insertGetId($insertBankDetailAr);
					DB::table('payment_auth_code')->where('auth_code','=',$authcode)->update(array('status' => 0));
					return $id;
				} else {
					return 0;
				} 
			} else {
				return 0;
			}
		}
		
		//function to update manual bank account info
		public static function update_manual_bank_info_new($bankid,$vendorid,$bankname,$nameonaccount,$routing_number,$account_number,$mailingaddress,$authcode){

			if($authcode != ''){
				$expire_auth_code = DB::table('payment_auth_code')->where('auth_code','=',$authcode)->first();
				if(isset($expire_auth_code->id ) && $expire_auth_code->id != '' && $expire_auth_code->status == 1){
					
					$insertBankDetailAr = array('bankname' => $bankname,'nameonaccount' => $nameonaccount,'routing_number'=>$routing_number,'account_number'=>$account_number,'mailing_address'=>$mailingaddress); 
					DB::table('bank_details_info_manual')->where('id','=',$bankid)->update($insertBankDetailAr);
					DB::table('payment_auth_code')->where('auth_code','=',$authcode)->update(array('status' => 0));
					return 1;
				} else {
					return 0;
				} 
			} else {
				return 0;
			}
		}
		
		
		//function to update manual bank account info
		public static function update_manual_bank_info($bankid,$vendorid,$check,$checkdate,$carrier,$airwaybill,$mailingaddress,$authcode){

			if($authcode != ''){
				$expire_auth_code = DB::table('payment_auth_code')->where('auth_code','=',$authcode)->first();
				if(isset($expire_auth_code->id ) && $expire_auth_code->id != '' && $expire_auth_code->status == 1){
					$convertdate = explode('-',$checkdate);
					$new_check_date = $convertdate[2].'-'.$convertdate[0].'-'.$convertdate[1];
					$insertBankDetailAr = array('check' => $check,'checkdate' => $new_check_date,'carrier'=>$carrier,'airwaybill'=>$airwaybill,'mailing_address'=>$mailingaddress); 
					DB::table('manual_bank_details')->where('id','=',$bankid)->update($insertBankDetailAr);
					DB::table('payment_auth_code')->where('auth_code','=',$authcode)->update(array('status' => 0));
					return 1;
				} else {
					return 0;
				} 
			} else {
				return 0;
			}
		}
		
		public static function check_manual_bank_account_exists($vendorid){
			
			$get_vendor_info =  DB::table('users')->where('id','=',$vendorid)->first();
			if(isset($get_vendor_info->id) && $get_vendor_info->id != ''){
				$userid = $get_vendor_info->id;
				$bankdetail = DB::table('bank_details_info_manual')->where('vendorid','=',$userid)->first();
				if(isset($bankdetail->id) && $bankdetail->id != ''){
					return $bankdetail->id;
				} else{
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		
		public static function get_manual_payee_info($vendorid){
			
			$get_vendor_info =  DB::table('bank_details_info_manual')->where('vendorid','=',$vendorid)->first();
			
			return $get_vendor_info;
		}
		
		public static function check_transfer_option_vendor($poid){
			
			$get_vendor_info =  DB::table('taste_po')->where('po_no','=',$poid)->first();
		
			if(isset($get_vendor_info->id) && $get_vendor_info->id != ''){
				$user_data = DB::table('users')->where('email','=',$get_vendor_info->vendor_email)->first();
				return $user_data->transfer_option;
			} else {
				return 0;
			}
			
		}
		
		//function to update manual bank account info
		public static function pay_via_check($poid,$check,$checkdate,$carrier,$airwaybill,$mailingaddress){
			
			$get_vendor_info =  DB::table('taste_po')->where('po_no','=',$poid)->first();
			
			$convertdate = explode('-',$checkdate);
			$new_check_date = $convertdate[2].'-'.$convertdate[0].'-'.$convertdate[1];	
			
			$insertBankDetailAr = array('po_no'=> $poid, 'check' => $check,'checkdate' => $new_check_date,'carrier'=>$carrier,'airwaybill'=>$airwaybill,'mailing_address'=>$mailingaddress,'payment_date' => date('Y-m-d H:i:s')); 
			
			$id =  DB::table('bank_details_manual')->insertGetId($insertBankDetailAr);
			
			DB::table('taste_po')->where('po_no','=',$poid)->update(array('vendor_paid_status'=> 1,'payment_date' => date('Y-m-d H:i:s'), 'payment_type' => 2 ,'manual_payment_id'	=> $id));
			return 1;

	  }
	  
	  //function to create vendor self account
		public static function create_vendor_account_self($fullname,$email_address,$password,$address,$city,$state,$zip,$phone,$location,$locationtype,$address2,$crossstreet,$neighborhood,$entries,$daysopen,$businessopeninghours,$businessclosinghours,$locationdescription,$locationnotes,$mealnotes,$contactphone,$emailcontact){
			
			$check_vendor_account = DB::table('users')->where('email',$email_address)->first();
			if(isset($check_vendor_account->id) && $check_vendor_account->id != ''){
				$reg_id = 0;
			} else {
				$token=time();
				$register = new Register;
				$register->name = $fullname;
				$register->email = $email_address;
				$register->password = Hash::make($password);
				$register->location = $location;
				$register->address = $address;
				$register->address2 = $address2;
				$register->crossstreet = $crossstreet;
				$register->neighborhood = $neighborhood;
				$register->entries = $entries;
				$register->daysopen = $daysopen;
				if($businessopeninghours != ''){
					$businessopeninghours = $businessopeninghours.':00';
				}
				if($businessclosinghours != ''){
					$businessclosinghours = $businessclosinghours.':00';
				}
				$register->businessopeninghours = $businessopeninghours;
				$register->businessclosinghours = $businessclosinghours;
				$register->locationnotes = $locationnotes;
				$register->mealnotes = $mealnotes;
				$register->locationtype = $locationtype;
				$register->locationdescription = $locationdescription;
				$register->contactphone = $contactphone;
				$register->emailcontact = $emailcontact;
				$register->city = $city;
				$register->state = $state;
				$register->zip = $zip;
				$register->phone = $phone;
				$register->usertype = 2;
				$register->status =  1;
				$register->save();
				$reg_id = $register->id;
			}
			return $reg_id;
	  }
	
}
?>
