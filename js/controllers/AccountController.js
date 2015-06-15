'use strict';

var FormValidation = function () {
	var createAccountValidation = function() {
						var form1 = $('#signupForm');
						var error1 = $('.alert-danger', form1);
						var success1 = $('.alert-success', form1);	
                        form1.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								rules: {
									email: {
										required: true,
										email:true
									},
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
										
										$('div.bootbox').find('div#signform').css('display','none');
										$('div.bootbox').find('div#w9instruction').css('display','block');
										$('div.bootbox').find('div#w9instruction').html('We need your W9 information in order to make payments to you. Please fill out the W9 form electronically by clicking next below');
										$('div.bootbox').find('h4.modal-title').text('W9 Confirmation');
										$('div.modal-footer').css('display','block');
										$('div.modal-footer').find('.main-btn-new').css('display','none');
										$('div.modal-footer').find('.cancel-btn').css('display','inline-block');
										$('div.modal-footer').find('.main-btn').css('display','inline-block');
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

MetronicApp.directive('validPasswordC', function() {
  return {
    require: 'ngModel',
    scope: {
	  validPasswordC: '='
      
    },
    link: function(scope, element, attrs, ctrl) {
        scope.$watch(function() {
            var combined;
			
            if (scope.validPasswordC || ctrl.$viewValue) {
               combined = scope.noMatch + '_' + ctrl.$viewValue; 
            }                    
            return combined;
        }, function(value) {
            if (value) {
                ctrl.$parsers.unshift(function(viewValue) {
                    var origin = scope.validPasswordC;
                    if (origin !== viewValue) {
                        ctrl.$setValidity("noMatch", false);
                        return undefined;
                    } else {
                        ctrl.$setValidity("noMatch", true);
                        return viewValue;
                    }
                });
            }
        });
     }
    
  }
});


MetronicApp.controller('AccountController', function($rootScope, $scope, $http, $timeout, $location,$window ,AUTH_EVENTS,Session,AuthService,authenticationSvc) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components
        var authtoken = localStorage.getItem('access_token');
        
       // var renderAction = $route.current.action;
      // $scope.location = $location.url();
       $scope.vendortoken = $location.url().split('/')[2];
       localStorage.setItem('payauthtoken',$scope.vendortoken);
       //localStorage.clear();
		FormValidation.init();
       function callAtTimeout() {
			$('div#site_statistics_loading').css('display','none');
			$('p#vendoraccountloading').css('display','none');
			$('#signform').css('display','block');
		}
		
        function getunpaidpo(){
			
			bootbox.dialog({
				title: "Register yourself to Taste",
				message: $('#signupForm'),
				show: false,
				className:'registerform',
				closeButton:false,
				buttons: {
				  danger: {
					label: "Cancel",
					className: "cancel-btn",
					callback: function() {
						var email = $('input#email').val();
						var password =  $('input#password').val();
						$.ajax({
							url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
							type: 'post',
							data: {
								action: 'createuseraccount',
								email:email,
								password:password
							},
							headers: {
								"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
								"x-taste-access-token": localStorage.getItem('access_token')
							},
							dataType:'json',
							success: function (data) {
								
								if(data.status_code == 200){
									localStorage.setItem("userid", data.vendoruserid);
									localStorage.setItem("role", 'vendor');
									localStorage.setItem("name", 'xyz');
								//	window.location.href = '#/vendors'; 
									authenticationSvc.login(email,password,'vendors');	
									bootbox.hideAll();	
								} else {
									createauthtoken(createUserAccount);
								}
							}
						});
					
					}
				  },
				  success: {
					label: "Continue",
					className: "main-btn",
					callback: function() {
						var email = $('input#email').val();
						var password =  $('input#password').val();
						//alert('email'+email+'password'+password);
						$.ajax({
							url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
							type: 'post',
							data: {
								action: 'createuseraccount',
								email:email,
								password:password
							},
							headers: {
								"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
								"x-taste-access-token": localStorage.getItem('access_token')
							},
							dataType:'json',
							success: function (data) {
								
								if(data.status_code == 200){
									localStorage.setItem("userid", data.vendoruserid);
									localStorage.setItem("role", 'vendor');
									localStorage.setItem("name", 'xyz');
									bootbox.hideAll();
									authenticationSvc.login(email,password,'vendors/w9form');	
									//window.location.href = '#/vendors/w9form'; 
								} else {
									createauthtoken(createUserAccount);
								}
							}
						});
						
					}
				  },
				  signup: {
					label: "SignUp",
					className: "main-btn-new",
					callback: function() {
						$('input#signupbtn').click();
						return false;
					}
				  }
				} 
			})
			.on('shown.bs.modal', function() {
				$('#signupForm')
					.show();                             // Show the login form
					$('#signupForm').validate().resetForm(); // Reset form
			})
			.on('hide.bs.modal', function(e) {
				// Therefor, we need to backup the form
				$('#signupForm').hide().appendTo('body');
			})
			.modal('show');
					
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getaccountinfo',accesshash:$scope.vendortoken}).
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
					
					if(data.status_message == 'Invalid token'){
						
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
		
	//$scope.submitted = false;
	/*$scope.createUserAccount = function() {
		  bootbox.dialog({
					message: "We need your W9 information in order to make payments to you. Please fill out the W9 form electronically by clicking next below",
					title: "W9 Confirmation",
					buttons: {
					  danger: {
						label: "Cancel",
						className: "primary",
						callback: function() {
							var email = $('input#email').val();
							var password =  $('input#password').val();
							$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
							$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
							$http.post($scope.apppath+'/api/getunpaidpo',{action:'createuseraccount',email:email,password:password}).
							success(function(data, status, headers, config) {
								if(data.status_code == 200){
									Session.create(data.vendoruserid,'vendor','xyz');
									localStorage.setItem("userid", data.vendoruserid);
									localStorage.setItem("role", 'vendor');
									localStorage.setItem("name", 'xyz');
									//console.log(Session.userId+Session.userRole+Session.userName);
									$window.location.href = '#/vendors'; 
								} else {
									createauthtoken(createUserAccount);
								}		
							});
						}
					  },
					  success: {
						label: "Continue",
						className: "blue",
						callback: function() {
							var email = $('input#email').val();
							var password =  $('input#password').val();
							$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
							$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
							$http.post($scope.apppath+'/api/getunpaidpo',{action:'createuseraccount',email:email,password:password}).
							success(function(data, status, headers, config) {
								if(data.status_code == 200){
									Session.create(data.vendoruserid,'vendor','xyz');
									//console.log(Session.userId+Session.userRole+Session.userName);
									localStorage.setItem("userid", data.vendoruserid);
									localStorage.setItem("role", 'vendor');
									localStorage.setItem("name", 'xyz');
									$window.location.href = '#/vendors/w9form'; 
								} else {
									createauthtoken(createUserAccount);
								}		
							});
						}
					  }
					}
			 });
		
	}*/
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
