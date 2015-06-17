'use strict';

MetronicApp.controller('VendorDashboardController', function($rootScope, $scope, $http, $timeout, $location,$window,AUTH_EVENTS,Session,AuthService) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components
        $('.page-header').css('display','block');
        $('.page-sidebar-wrapper').css('display','block');
        $('.theme-panel').css('display','block');
        $('.page-quick-sidebar-wrapper').css('display','block');
        $('.page-footer').css('display','block');
        $('.logo').css('display','none');
        var authtoken = localStorage.getItem('access_token');
       // var renderAction = $route.current.action;
      // $scope.location = $location.url();
 
		$scope.vendortoken = $location.url().split('/')[2];
		var payauthtoken = localStorage.getItem('payauthtoken');
		//console.log(Session.userId+Session.userRole+Session.userName);
		var userid = Session.userId;
		var userRole = Session.userRole;
		var userName = Session.userName;
		//console.log('userid'+userid+'userRole'+userRole+'userName'+userName);
		Session.create(userid,userRole,userName);
		Layout.initSidebar();
		//console.log(localStorage.getItem('vendor_access_token'));
		/*$scope.init = function () {
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getw9form',formname:'w9 form',email:'taste@yopmail.com',reminder_code:1}).
			success(function(data, status, headers, config) {
				
					if(data.w9response==2)
					{
						alert('Some error occured please try again !');
					}
					else
					{
						lightboxQuickUpload(data.w9response);
					}
				
			});
		}*/
		
        function getunpaidpo(){
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getaccountinfo',accesshash:payauthtoken}).
			success(function(data, status, headers, config) {
				Layout.initSidebar();
				if(data.status_code == 200){
					
					 if(data.vendorinfo != '')
						$scope.vendorinfo = data.vendorinfo;	
						
					 var vm = this;
				} else {
					if(data.status_message === 'Invalid token'){
						
					} else {
						createauthtoken(getunpaidpo);
					}
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
		//$scope.init();
    });
		
	$scope.submitted = false;
	$scope.createUserAccount = function() {
		
		if ($scope.signupForm.$valid) {
			var email = $('input#email').val();
			var password =  $('input#password').val();
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'createuseraccount',email:email,password:password}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200){
					 
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
