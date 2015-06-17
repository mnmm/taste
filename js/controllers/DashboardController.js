'use strict';

MetronicApp.controller('DashboardController', function($rootScope, $scope, $http, $timeout,AUTH_EVENTS,Session,AuthService) {
	$scope.unpaid = [];
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
	//console.log($scope.timestamp);
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        Metronic.initAjax();
        $('.page-header').css('display','block');
        $('.page-sidebar-wrapper').css('display','block');
        $('.page-quick-sidebar-wrapper').css('display','block');
		var authtoken = localStorage.getItem('access_token');
		
		function getunpaidpo(){
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{timelimit:'all',action:'dashboard',fetchdata:'all'}).
			success(function(data, status, headers, config) {
				$('div.page-content').find('div#site_statistics_loading').each(function(){
					$(this).css('display','none');
				});
				Layout.initSidebar();
				if(data.status_code == 200){
					
					 if(data.unpaidorders != '')
						$scope.unpaidorders = data.unpaidorders;	
						
					 if(data.totalunpaidamount != '')
						$scope.totalunpaidamount = data.totalunpaidamount;
						
					if(data.totalunpaidamount != '')
						$scope.unpaidnextweekorders = data.unpaidnextweekorders;
						
					if(data.unpaidnextweekamount != '')
						$scope.unpaidnextweekamount = data.unpaidnextweekamount;
					else 
						$scope.unpaidnextweekamount = 0;
						
					if(data.getpodetails != ''){
						$scope.getpodetails = data.getpodetails;
						$scope.graphData =[];
						$scope.graphSaleStats =[];
						//console.log(data.getpodetails);
						angular.forEach($scope.getpodetails, function(x, k) {
       
							 if(x.time != undefined)
							  {
								$scope.graphData.push({
									  period: x.time,
									  paidorders: x.paidorders,
									  paidamount:x.paidamount
								});
							} else {
								if(x.totalamount != undefined)
								{
									$scope.graphSaleStats.push({
										  totalamount: x.totalamount,
										  totalorders: x.totalorders
									});
								}
							}

						});
						//console.log($scope.graphData);
						console.log($scope.graphSaleStats);
						Morris.Area({
							element: 'sales_statistics',
							padding: 0,
							behaveLikeLine: false,
							gridEnabled: false,
							gridLineColor: false,
							axes: false,
							fillOpacity: 1,
							data: $scope.graphData,
							lineColors: ['#399a8c', '#92e9dc'],
							xkey: 'period',
							ykeys: ['paidorders', 'paidamount'],
							labels: ['Paidorders', 'Paidamount'],
							pointSize: 0,
							lineWidth: 0,
							hideHover: 'auto',
							resize: true
						});
						
					}
					
					if(data.nextweekdue != ''){
						$scope.nextweekdue = data.nextweekdue;
						$scope.nextweekData =[];
						angular.forEach($scope.nextweekdue, function(x, k) {
							  if(x.po_no != undefined)
							  {
									$scope.nextweekData.push({
										  itemdetails: 'po no #'+x.po_no + ' with amount $'+ x.total_amount+' is due on '+x.due_date,
										  vendorname: x.vendor_name
									});
							  }
						});
						//console.log($scope.nextweekData);
					}
					
					if(data.nextweekduecount != ''){
						$scope.nextweekduecount = data.nextweekduecount;	
					}
					
					if(data.vendorpodetails != ''){
						$scope.vendorpodetails = data.vendorpodetails;
						console.log($scope.vendorpodetails);
					}
						
							
				} else {
				
					$http.post($scope.apppath+'/create_auth_token', {api_key:'1-Z9QSD6E6QJNDYTPBUD8XEX8',api_secret:'N-9OXFMLDXLXB7N2IXXOQR85XFV5V7QKGR_',timestamp:$scope.timestamp}). 
					success(function(data, status, headers, config) {
						if(data.status_code == 200 ){
							localStorage.setItem('access_token',data.access_token);
							getunpaidpo();
						}
						
					});
					/*console.log(createauthtoken(data.status_code));
					if(createauthtoken(data.status_code) == 200){
						localStorage.setItem('access_token',data.access_token);
						getunpaidpo();
					}*/
				}		
			});
		}
		
		
		 
  
		function createauthtoken(callback){
			
			$http.post($scope.apppath+'/create_auth_token', {api_key:'1-Z9QSD6E6QJNDYTPBUD8XEX8',api_secret:'N-9OXFMLDXLXB7N2IXXOQR85XFV5V7QKGR_',timestamp:$scope.timestamp}). 
					success(function(data, status, headers, config) {
						if(data.status_code == 200 ){
							localStorage.setItem('access_token',data.access_token);
							callback();
						}
			});			
		}
		
		function checktokenauthentication(authtoken,callback){
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =authtoken;
			$http.post($scope.apppath+'/api/checktokenauthentication').
				success(function(data, status, headers, config) {
					if(data.status_code == 200){
						callback();
					} else {
						createauthtoken(callback);
					}
			});
		}
		
		if(authtoken != '' && typeof authtoken !== 'undefined' && authtoken !==null){
			checktokenauthentication(authtoken, getunpaidpo);
		} else {
			createauthtoken(getunpaidpo);
		}
        
    });
    
     $scope.getpaidorders = function(type) {
		 
		$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
		$http.post($scope.apppath+'/api/getpodataforgraphs',{fetchordertype:type}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200){
					if(data.getpodetails != ''){
							$scope.getpodetails = data.getpodetails;
							$scope.graphData =[];
							$scope.graphSaleStats =[];
							//console.log(data.getpodetails);
							angular.forEach($scope.getpodetails, function(x, k) {
		   
								 if(x.time != undefined)
								  {
									$scope.graphData.push({
										  period: x.time,
										  paidorders: x.paidorders,
										  paidamount:x.paidamount
									});
								} else {
									if(x.totalamount != undefined)
									{	
										$scope.graphSaleStats.push({
											  totalamount: x.totalamount,
											  totalorders: x.totalorders
										});
									}
								}

							});
							//console.log($scope.graphData);
							$('#sales_statistics').empty();
							Morris.Area({
								element: 'sales_statistics',
								padding: 0,
								behaveLikeLine: false,
								gridEnabled: false,
								gridLineColor: false,
								axes: false,
								fillOpacity: 1,
								data: $scope.graphData,
								lineColors: ['#399a8c', '#92e9dc'],
								xkey: 'period',
								ykeys: ['paidorders', 'paidamount'],
								labels: ['Paidorders', 'Paidamount'],
								pointSize: 0,
								lineWidth: 0,
								hideHover: 'auto',
								resize: true
							});
							
							
						}	
				} else {
					createauthtoken(getpaidorders);
				}
		});
	 }
	 
	 
	 $scope.getvendororderdetails = function(type) {
		 
		$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
		$http.post($scope.apppath+'/api/getunpaidpo',{activitytype:type,action:'dashboard',fetchdata:'members'}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200){
					if(data.vendorpodetails != ''){
						$scope.vendorpodetails = data.vendorpodetails;
						console.log($scope.vendorpodetails);
					}	
				} else {
					createauthtoken(getpaidorders);
				}
		});
	 }
	
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = false;
    $rootScope.settings.layout.showAllOptions = false;
});
