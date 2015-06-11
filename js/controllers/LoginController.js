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
										required: true,
										minlength:6
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


MetronicApp.controller('LoginController', function($rootScope, $scope, $http, $timeout, $location,$window ,AUTH_EVENTS,Session,AuthService) {
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
      
		 
    });
		
	 
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
