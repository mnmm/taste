'use strict';

 var FormValidation = function () {
	 
	var handleLoginValidation = function() {
						var form1 = $('#loginForm');
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
										required: true
									}
								},
								messages: {
										email: {
											required: "Email is required",
											email: "Please provide a valid email address"
										},
										password: "Password is required",
								},
								invalidHandler: function (event, validator) { //display error alert on form submit              
									success1.hide();
									//error1.show();
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
										
										$('#submit_button_login').click();
									}
								});
	}
	
	var handleRegisterValidation = function() {
						var form2 = $('#registerForm');
						var error1 = $('.alert-danger', form2);
						var success1 = $('.alert-success', form2);	
                        form2.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								rules: {
									fullname: {
										required: true
									},
									email_address: {
										required: true,
										email: true
									},
									register_password: {
										required: true
									},
									rpassword: {
										equalTo: "#register_password"
									},
									address: {
										required: true
									},
									city: {
										required: true
									},
									state: {
										required: true
									},
									zip: {
										required: true
									},
									tnc: {
										required: true
									}
								},
								invalidHandler: function (event, validator) { //display error alert on form submit              
									success1.hide();
									//error1.show();
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

								submitHandler: function (form2) {
										$('button#createAccount').click();
									}
								});
	}
	

	return {
        //main function to initiate the module
        init: function () {
            handleLoginValidation();
            handleRegisterValidation();
        }

    };
}();


/*jQuery('#register-btn').click(function() {
	jQuery('.login-form').hide();
	jQuery('.register-form').show();
});

jQuery('#register-back-btn').click(function() {
	jQuery('.login-form').show();
	jQuery('.register-form').hide();
});*/
        
MetronicApp.controller('LoginController', function($rootScope, $scope, $http, $timeout, $location,$window ,authenticationSvc) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	FormValidation.init();
	var authtoken = localStorage.getItem('access_token');
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
	$scope.login = function(){authenticationSvc.login($scope.email,$scope.password,'vendors');}
	
	$scope.createAccount = function() {
		$('.login-form').hide();
		$('.register-form').show();
	}
	
	$scope.showLogin = function() {
		$('.login-form').show();
		$('.register-form').hide();
	}
	
	$scope.SignUp = function() {
		console.log('here');
	}
	
	function createauthtoken(){
			
		$http.post($scope.apppath+'/create_auth_token', {api_key:'1-Z9QSD6E6QJNDYTPBUD8XEX8',api_secret:'N-9OXFMLDXLXB7N2IXXOQR85XFV5V7QKGR_',timestamp:$scope.timestamp}). 
				success(function(data, status, headers, config) {
					if(data.status_code == 200 ){
						localStorage.setItem('access_token',data.access_token);
					}
		});			
	}
	
	function checktokenauthentication(authtoken){
		$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =authtoken;
		$http.post($scope.apppath+'/api/checktokenauthentication').
			success(function(data, status, headers, config) {
				if(data.status_code == 200){
					
				} else {
					createauthtoken();
				}
		});
	}

	if(authtoken != '' && typeof authtoken !== 'undefined' && authtoken !==null){
		checktokenauthentication(authtoken);
	} else {
		createauthtoken();
	}
	
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
    $rootScope.settings.layout.showAllOptions = true;
    
}); 


