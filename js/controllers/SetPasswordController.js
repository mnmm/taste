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
									confirmpassword: {
										required:true,
										equalTo : "#password"
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

								submitHandler: function (form1) {
										
										$('button#updatepassword').click();
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
					
					if(data.userdata != '' && typeof data.userdata != 'undefined'){
						$('#setPasswordForm').find('input#invitedemail').val(data.userdata.email);
						$('#setPasswordForm').find('input#inviteid').val(data.userdata.invitationcode);
					} 
				} else {
					
					if(data.status_message === 'Invitation links is invalid/expired'){
						$('input#password').prop('disabled',true);
						$('input#confirmpassword').prop('disabled',true);
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
		
		$scope.updatePassword = function() {
		var authtoken = localStorage.getItem('access_token');
		$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =authtoken;
		var invitedemail = $('input#invitedemail').val();
		var inviteid = $('input#inviteid').val();
		$http.post($scope.apppath+'/api/getunpaidpo',{password:$scope.password,email_address:invitedemail,inviteid:inviteid,action:'updatevendorpassword'}).
			success(function(data) {
				if(data.status_code === 200){
					var subject = 'registered as new vendor';
						var message = $scope.fullname+' has registred as new vendor with email '+$scope.email_address;
						$http.post($scope.apppath+'/api/getunpaidpo',{subject:subject,message:message,userid:1,action:'saveadminnotes'}).
						success(function(data) {
							if(data.status_code === 200){
								$('form#setPasswordForm')[0].reset();
								var sucess2 = $('#registerformsucess');
								$('#setPasswordForm').find('#registerformsucess').show();
								Metronic.scrollTo(sucess2, -200);
								$('#registerformalert').hide();
							}
						});
				} else {
					if(data.status_code === 201){
						if(data.updatedpassword ===  0){
							var error3 = $('#registerformalert');
							error3.show();
							Metronic.scrollTo(error3, -200);
							$('#registerformalert').show(data.message);
							$('#registerformsucess').hide();
						} 
					}
				}
		});
	}
	
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
    $rootScope.settings.layout.showAllOptions = true;
}); 


