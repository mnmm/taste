'use strict';

 var FormValidation = function () {
	var createAccountValidation = function() {
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
										
										$('#submit_button_login').click();
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

MetronicApp.controller('LoginController', function($rootScope, $scope, $http, $timeout, $location,$window ,authenticationSvc) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	FormValidation.init();
	$scope.login = function(){authenticationSvc.login($scope.email,$scope.password,'vendors');}
	
	
	 
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
