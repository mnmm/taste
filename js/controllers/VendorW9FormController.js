'use strict';

MetronicApp.controller('VendorW9FormController', function($rootScope, $scope, $http, $timeout, $location,$window) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components
        var authtoken = localStorage.getItem('access_token');
        // var renderAction = $route.current.action;
       // $scope.location = $location.url();
		//$scope.vendortoken = $location.url().split('/')[2];
		var payauthtoken  = localStorage.getItem('payauthtoken');
		var useremail = localStorage.getItem('useremail');
		//console.log(localStorage.getItem('vendor_access_token'));
		localStorage.setItem('w9signed',0);
		$scope.init = function () {
		$http.get($scope.apppath+"/api/checklogin").
				success(function(data1) {
					$scope.userroleInfo = data1;
					 useremail = $scope.userroleInfo.email;
					
						$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
						$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
						
						$http.post($scope.apppath+'/api/getunpaidpo',{action:'getw9form',formname:'w9 form',email:useremail,reminder_code:1}).
						success(function(data) {
							if(data.w9signed == 0){
								if(data.w9response==2)
								{
									alert('Some error occured please try again !');
								}
								else
								{
									lightboxQuickUploadTaste(data.w9response);
								}
							} else {
								localStorage.setItem('w9signed',1);
								$window.location.href = '#/vendors/addbankinfo';
							}
						});
				
				});
		}
		
        function getunpaidpo(){
			var w9signed = localStorage.getItem('w9signed');
			if(w9signed == 0){
				$http.get($scope.apppath+"/api/checklogin").
				success(function(data1) {
					$scope.userroleInfo = data1;
					 useremail = $scope.userroleInfo.email;
					 
				$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
				$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
				$http.post($scope.apppath+'/api/getunpaidpo',{action:'getaccountinfo',accesshash:payauthtoken,email:useremail}).
				success(function(data) {
					Layout.initSidebar();
					if(data.status_code === 200){
						
						 if(data.vendorinfo !== '')
							$scope.vendorinfo = data.vendorinfo;	
						
							var vm = this;
					} else {
						if(data.status_message === 'Invalid token'){
							$window.location.href = '#/vendors/addbankinfo';
						} else {
							createauthtoken(getunpaidpo);
						}
					}		
				});
				});
				
			}
		}
		
		$window.addEventListener('message', receiveMessage, true);
		 /*window.addEventListener("message", receiveMessage, true);*/
		 function receiveMessage(event){
		 
			var dt=event.data;
			if (dt.indexOf("#$%") >= 0){
				
			} else {
				//alert(dt);
				var all_dt=dt.split("#@#");
				var vendoruserid  = localStorage.getItem('userid');
				$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
				$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
				$http.post($scope.apppath+'/api/getunpaidpo',{action:'getrequesterinfo',vendorid:vendoruserid,email:all_dt[0]}).
				success(function(data) {
					if(data.status_code === 200){
						$http.post($scope.apppath+'/api/getunpaidpo',{action:'savecontractinfo',email:all_dt[0],ssn_last4:all_dt[1],ssn_enc:all_dt[2],ein:all_dt[3],payeename:data.requestername,address:data.requesterinfo}).
						success(function(data) {
							if(data.updatedtaxid === 1){
								$window.location.href = '#/vendors/addbankinfo';
							 }
						});
					}
				});
				
				

			}
		}
		
		
		function createauthtoken(callback){
			
			$http.post($scope.apppath+'/create_auth_token', {api_key:'1-Z9QSD6E6QJNDYTPBUD8XEX8',api_secret:'N-9OXFMLDXLXB7N2IXXOQR85XFV5V7QKGR_',timestamp:$scope.timestamp}). 
					success(function(data) {
						if(data.status_code === 200 ){
							localStorage.setItem('access_token',data.access_token);
							callback();
						}
			});			
		}
		
		function checktokenauthentication(authtoken,callback){
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =authtoken;
			$http.post($scope.apppath+'/api/checktokenauthentication').
				success(function(data) {
					if(data.status_code === 200){
						callback();
					} else {
						createauthtoken(callback);
					}
			});
		}
		
		$scope.init();
		
		if(authtoken != '' && typeof authtoken !== 'undefined' && authtoken !==null){
			checktokenauthentication(authtoken, getunpaidpo);
		} else {
			createauthtoken(getunpaidpo);
		}
		
    });
		
	$scope.submitted = false;
	$scope.createUserAccount = function() {
		
		if ($scope.signupForm.$valid) {
			var email = $('input#email').val();
			var password =  $('input#password').val();
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'createuseraccount',email:email,password:password}).
			success(function(data) {
				if(data.status_code === 200){
					 
				} else {
					createauthtoken(createUserAccount);
				}		
			});
		} else {
			
			$scope.signupForm.submitted = true;
		}
	  }
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = false;
}); 
