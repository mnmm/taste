'use strict';

var FormValidation = function () {
	var createAccountValidation = function() {
						var form1 = $('#setPasswordForm');
						var error1 = $('.alert-danger', form1);
						var success1 = $('.alert-success', form1);	
                        form1.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								rules: {
									password:{
										required: true,
										minlength:6
									},
									confirm_password: {
										required:true,
										equalTo : "#password"
									}
								},
								invalidHandler: function (event, validator) { //display error alert on form submit              
									success1.hide();
									error1.show();
									Metronic.scrollTo(error1, -200);
								},

								highlight: function (element) { // hightlight error inputs
									$(element)
										.closest('.form-group').addClass('has-error'); // set error class to the control group
								},

								unhighlight: function (element) { // revert the change done by hightlight
									$(element)
										.closest('.form-group').removeClass('has-error'); // set error class to the control group
								},

								success: function (label) {
									label
										.closest('.form-group').removeClass('has-error'); // set success class to the control group
								},

								submitHandler: function (form1) {
										
										
									}
								});
	}
	

	return {
        //main function to initiate the module
        init: function () {
            createAccountValidation();
          
        }

    };
}();


MetronicApp.controller('SetPasswordController', function($rootScope, $scope, $http, $timeout, $location,$window ,AUTH_EVENTS,Session,AuthService,authenticationSvc) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components
        var authtoken = localStorage.getItem('access_token');
       
       $scope.vendorinvitetoken = $location.url().split('/')[2];
       localStorage.setItem('vendorinvitetoken',$scope.vendorinvitetoken);
       console.log(localStorage.getItem('vendorinvitetoken'));
		FormValidation.init();
       function callAtTimeout() {
			$('div#site_statistics_loading').css('display','none');
			$('p#vendoraccountloading').css('display','none');
			$('#signform').css('display','block');
		}
		
        function getunpaidpo(){
		
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'checkvalidinvitetoken',vendorinvitetoken:$scope.vendorinvitetoken }).
			success(function(data, status, headers, config) {
			
				if(data.status_code == 200){
					
					if(data.aleadyexists != '' && typeof data.aleadyexists != 'undefined'){
						Session.create(data.userid,'vendor',data.username);
						//localStorage.setItem('vendor_access_token',$scope.vendortoken);
						localStorage.setItem("userid", data.userid);
						localStorage.setItem("role", 'vendor');
						localStorage.setItem("name", data.username);
						localStorage.setItem("useremail", data.aleadyexists);
						bootbox.hideAll();	
						if(data.w9signed != '' && typeof data.aleadyexists != 'undefined' && data.w9signed  == 1){
							//$window.location.href = '#/vendors';
							$window.location.href = '#/vendors/w9form';
						} else {
							$window.location.href = '#/vendors/w9form';
						} 
					} else {
						 if(data.vendoremail != '' && typeof data.vendoremail != 'undefined'){
								$scope.vendorinfo = data.vendoremail;	
								localStorage.setItem("useremail", data.vendoremail);
								$('div#signform').find('input#email').val(data.vendoremail);
								var vm = this;
								$timeout(function () {
									 $timeout(callAtTimeout, 500);
								});
						 } 
					} 
				} else {
					
					if(data.status_message === 'Invalid token'){
						
						$scope.vendorinfo = '';
						$('input#email').prop('disabled',true);
						$('input#password_c').prop('disabled',true);
						$('input#password').prop('disabled',true);
						$('p#vendoraccountloading').css('display','none');
						$('p#tokenexpired').css('display','block');
						$('p#tokenexpired').css('color','red');
						$('div#site_statistics_loading').css('display','none');
						
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
		
    });
		
	
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
