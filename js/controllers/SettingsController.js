'use strict';

var FormValidation = function () {
	var SettingsValidation = function() {
						var form1 = $('#settingsForm');
						var error1 = $('.alert-danger', form1);
						var success1 = $('.alert-success', form1);	
                        form1.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								rules: {
									test_secret_key: {
										required: true,
									},
									live_secret_key:{
										required: true
									},
									live_publishable_key: {
										required:true
									},
									test_publishable_key: {
										required:true
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
									
										var test_secret_key = $('input#test_secret_key').val();
										var test_publishable_key = $('input#test_publishable_key').val();
										var live_secret_key = $('input#live_secret_key').val();
										var live_publishable_key = $('input#live_publishable_key').val();
										var actiontype = $('input#actiontype').val();

										$.ajax({
											url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
											type: 'post',
											data: {
												action: 'savesettings',
												test_secret_key:test_secret_key,
												test_publishable_key:test_publishable_key,
												live_secret_key:live_secret_key,
												live_publishable_key:live_publishable_key,
												type: actiontype
											},
											headers: {
												"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
												"x-taste-access-token": localStorage.getItem('access_token')
											},
											dataType:'json',
											success: function (data) {
												
												if(data.status_code == 200 ){
													bootbox.hideAll();	
												} else {
												    if(data.status_code == 201 ){
														
														if(data.message != ''){
															$('p#accounterror').html(data.message).css('display','block');
														} else {
															
														}
 													} 
												}
											}
										});
									}
								});
	}
	

	return {
        //main function to initiate the module
        init: function () {
            SettingsValidation();
          
        }

    };
}();

MetronicApp.controller('SettingsController', function($rootScope, $scope, $http, $timeout, $location,$window ,AUTH_EVENTS,Session,AuthService,SideBarService) {
	
	//console.log( SideBarService.all());
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components
        var authtoken = localStorage.getItem('access_token');

       $scope.vendortoken = $location.url().split('/')[2];
       localStorage.setItem('payauthtoken',$scope.vendortoken);
       //localStorage.clear();
		FormValidation.init();
       function callAtTimeout() {
			$('div#site_statistics_loading').css('display','none');
			$('p#vendoraccountloading').css('display','none');
			$('#settingsform').css('display','block');
		}
		
        function getunpaidpo(){
			
			bootbox.dialog({
				title: "Please enter API keys for stripe live and test mode",
				message: $('#settingsForm'),
				show: false,
				closeButton:false,
				buttons: {
				  danger: {
					label: "Cancel",
					className: "cancel-btn",
					callback: function() {
						$('#settingsForm').hide();
						$('#settingsForm').hide();
						bootbox.hideAll();	
					}
				  },
				  success: {
					label: "Save Settings",
					className: "main-btn",
					callback: function() {
						$('input#settingsubmit').click();
						return false;
					}
				  }
				}
			})
			.on('shown.bs.modal', function() {
				$('#settingsForm')
					.show();                             // Show the login form
					$('#settingsForm').validate().resetForm(); // Reset form
			})
			.on('hide.bs.modal', function(e) {
				// Therefor, we need to backup the form
				$('#settingsForm').hide().appendTo('body');
			})
			.modal('show');
			
			var vendoruserid  = localStorage.getItem('userid');		
			//console.log('vendorid'+vendoruserid);
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getsettinginfo'}).
			success(function(data, status, headers, config) {
				Layout.initSidebar();
				if(data.status_code == 200){
					
					if(data.settings != '' && typeof data.settings != 'undefined'){
						$('#settingsForm').find('input#test_secret_key').val(data.settings.stripe_test_secret_key);	
						$('#settingsForm').find('input#test_publishable_key').val(data.settings.stripe_test_publishable_key);
						$('#settingsForm').find('input#live_secret_key').val(data.settings.stripe_live_secret_key);
						$('#settingsForm').find('input#live_publishable_key').val(data.settings.stripe_live_publishable_key);
						$('#settingsForm').find('input#live_publishable_key').val(data.settings.stripe_live_publishable_key);
						$('#settingsForm').find('input#actiontype').val(1);
						$('#settingsForm').find('button#btnsetting').text('Update');
						$timeout(function () {
							$timeout(callAtTimeout, 500);
						});
					}  
				} else {
					
					if(data.message == 'Not Exists'){
						$timeout(function () {
							$timeout(callAtTimeout, 500);
						});
						
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
    
    $scope.loadSettingsPage = function() {
		bootbox.dialog({
				title: "Please enter API keys for stripe live and test mode",
				message: $('#settingsForm'),
				show: false,
				closeButton:false,
				buttons: {
				  danger: {
					label: "Cancel",
					className: "cancel-btn",
					callback: function() {
						$('div.bootbox').find('div.modal-body').find('div#settingsForm').hide();
						$('div.bootbox').find('div.modal-body').find('form#settingsForm').hide();
						bootbox.hideAll();	
					}
				  },
				  success: {
					label: "Save Settings",
					className: "main-btn",
					callback: function() {
						$('input#settingsubmit').click();
						return false;
					}
				  }
				}
			})
			.on('shown.bs.modal', function() {
				$('#settingsForm')
					.show();                             // Show the login form
					$('#settingsForm').validate().resetForm(); // Reset form
			})
			.on('hide.bs.modal', function(e) {
				// Therefor, we need to backup the form
				$('#settingsForm').hide().appendTo('body');
			})
			.modal('show');
			
			var vendoruserid  = localStorage.getItem('userid');		
			//console.log('vendorid'+vendoruserid);
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getsettinginfo'}).
			success(function(data, status, headers, config) {
				Layout.initSidebar();
				if(data.status_code == 200){
					
					if(data.settings != '' && typeof data.settings != 'undefined'){
						$('#settingsForm').find('input#test_secret_key').val(data.settings.stripe_test_secret_key);	
						$('#settingsForm').find('input#test_publishable_key').val(data.settings.stripe_test_publishable_key);
						$('#settingsForm').find('input#live_secret_key').val(data.settings.stripe_live_secret_key);
						$('#settingsForm').find('input#live_publishable_key').val(data.settings.stripe_live_publishable_key);
						$('#settingsForm').find('input#live_publishable_key').val(data.settings.stripe_live_publishable_key);
						$('#settingsForm').find('input#actiontype').val(1);
						$('#settingsForm').find('button#btnsetting').text('Update');
						$('div.bootbox').find('div.modal-body').find('div#settingsForm').show();
						$('div.bootbox').find('div.modal-body').find('form#settingsForm').show();
						$timeout(function () {
							$timeout(callAtTimeout, 500);
						});
					}  
				} else {
					
					if(data.message == 'Not Exists'){
						$timeout(function () {
							$timeout(callAtTimeout, 500);
						});
						
					} else {
						createauthtoken(getunpaidpo);
					}
				}		
			});
	}
    
    
  
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = false;
}); 
