'use strict';

var FormValidation = function () {
	var InviteValidation = function() {
						var form1 = $('#addVendor');
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
									
									var vendoruserid  = localStorage.getItem('userid');
									var name = $('#addVendor').find('input#fullname').val();
									var email = $('#addVendor').find('input#email').val();
									var phone = $('#addVendor').find('input#phone').val();
									var address = $('#addVendor').find('input#address').val();
									var city = $('#addVendor').find('input#city').val();
									var state = $('#addVendor').find('input#state').val();
									var zip = $('#addVendor').find('input#zip').val();
									$.ajax({
											url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
											type: 'post',
											data: {
												action: 'addvendorinfo',
												fullname:name,
												email_address:email,
												phone:phone,
												address:address,
												city:city,
												state:state,
												zip:zip
											},
											headers: {
												"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
												"x-taste-access-token": localStorage.getItem('access_token')
											},
											dataType:'json',
											success: function (data) {
												
												if(data.status_code === 200 ){
													bootbox.hideAll();	
													$.ajax({
															url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
															type: 'post',
															data: {
																action: 'getallvendors'
															},
															headers: {
																"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
																"x-taste-access-token": localStorage.getItem('access_token')
															},
															dataType:'json',
															success: function (data) {
																if(data.data != ''){
																	
																	var newdata = data.data;
																	
																	var table = $('#sample_2');
																
																	/* Table tools samples: https://www.datatables.net/release-datatables/extras/TableTools/ */

																	var oTable = table.dataTable({
																		destroy: true,
																		// Internationalisation. For more info refer to http://datatables.net/manual/i18n
																		"language": {
																			"aria": {
																				"sortAscending": ": activate to sort column ascending",
																				"sortDescending": ": activate to sort column descending"
																			},
																			"emptyTable": "No data available in table",
																			"info": "Showing _START_ to _END_ of _TOTAL_ entries",
																			"infoEmpty": "No entries found",
																			"infoFiltered": "(filtered1 from _MAX_ total entries)",
																			"lengthMenu": "Show _MENU_ entries",
																			"search": "Search:",
																			"zeroRecords": "No matching records found"
																		},
																	
																		"order": [
																			[0, 'asc']
																		],
																		"lengthMenu": [
																			[5, 10, 20, -1],
																			[5, 10, 20, "All"] // change per page values here
																		],
																		"data":newdata,
																		// set the initial value
																		"pageLength": 10,
																		"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>" // horizobtal scrollable datatable
																		
																	});

																	var tableWrapper = $('#sample_2_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
																	tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
																	table.$('[data-toggle="popover"]').popover().mouseover(function(e) {e.preventDefault();});
																}
															}
														});
													
													
												} else {
												
													if(data.status_code === 201 ){
														
														if(data.message !== ''){
															$('p#accounterror').html(data.message).css('display','block');
														} 
 													} 
												}
											}
										});
									}
								});
	}
	
	
	var handleWysihtml5 = function () {
        if (!jQuery().wysihtml5) {
            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5({
                "stylesheets": ["https://mnmdesignlabs.com/taste/assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            });
        }
    }
	

	return {
        //main function to initiate the module
        init: function () {
            InviteValidation();
			handleWysihtml5();
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


MetronicApp.controller('InviteController', function($rootScope, $scope, $http, $timeout, $location,$window ,AUTH_EVENTS,Session,AuthService,authenticationSvc) {
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
			$('#signform').css('display','block');
		}
		
        function getunpaidpo(){
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getallvendors'}).
			success(function(data, status, headers, config) {
			
					if(data.data != ''){
							
						$scope.data = data.data;
						
						var table = $('#sample_2');

						/* Table tools samples: https://www.datatables.net/release-datatables/extras/TableTools/ */

						var oTable = table.dataTable({
							destroy: true,
							// Internationalisation. For more info refer to http://datatables.net/manual/i18n
							"language": {
								"aria": {
									"sortAscending": ": activate to sort column ascending",
									"sortDescending": ": activate to sort column descending"
								},
								"emptyTable": "No data available in table",
								"info": "Showing _START_ to _END_ of _TOTAL_ entries",
								"infoEmpty": "No entries found",
								"infoFiltered": "(filtered1 from _MAX_ total entries)",
								"lengthMenu": "Show _MENU_ entries",
								"search": "Search:",
								"zeroRecords": "No matching records found"
							},
						
							"order": [
								[0, 'asc']
							],
							"lengthMenu": [
								[5, 10, 20, -1],
								[5, 10, 20, "All"] // change per page values here
							],
							"data":$scope.data,
							// set the initial value
							"pageLength": 10,
							"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>" // horizobtal scrollable datatable
							
						});

						var tableWrapper = $('#sample_2_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
						tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
						table.$('[data-toggle="popover"]').popover().mouseover(function(e) {e.preventDefault();});
					}
					 
					
			});
			
			/*bootbox.dialog({
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
			});*/
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
    
    $scope.openAddVendorPopUp = function(){
			var vendoruserid  = localStorage.getItem('userid');
			$http.get($scope.apppath+"/api/checklogin").
			success(function(data1) {
					$scope.userroleInfo = data1;
					 vendoruserid = $scope.userroleInfo.id;
			
				$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
				$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
				$http.post($scope.apppath+'/api/getunpaidpo',{action:'getpayeename',vendorid:vendoruserid}).
				success(function(data, status, headers, config) {
					if(data.status_code == 200){
						
						bootbox.dialog({
							title:'Add Vendor',
							message: $('#addVendor'),
							show: false,
							animate:true,
							closeButton: false,
							className:'addvendordetail',
							buttons: {
							  danger: {
								label: "Cancel",
								className: "cancel-btn",
								callback: function() {
									
									bootbox.hideAll();	
								}
							  },
							  success: {
								label: "Save",
								className: "main-btn",
								callback: function() {
									$('#addVendor').find('input#savevendorinfo').click();
									return false;
								}
							  }
							} 
						})
						.on('shown.bs.modal', function() {

							$('#addVendor').show(); 
							$('#addVendor').validate().resetForm(); 	
								
						})
						.on('hide.bs.modal', function(e) {
						
							
						})
						.modal('show');

					} 		
				});
		});

	}
	
	$scope.openSendEmailPopUp = function(){
			var vendoruserid  = localStorage.getItem('userid');
			$http.get($scope.apppath+"/api/checklogin").
			success(function(data1) {
					$scope.userroleInfo = data1;
					 vendoruserid = $scope.userroleInfo.id;
			
				$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
				$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
				$http.post($scope.apppath+'/api/getunpaidpo',{action:'getpayeename',vendorid:vendoruserid}).
				success(function(data, status, headers, config) {
					if(data.status_code == 200){
						
						bootbox.dialog({
							title:'Invite Vendor',
							message: $('#inviteVendor'),
							show: false,
							animate:true,
							closeButton: false,
							className:'invitevendor',
							buttons: {
							  danger: {
								label: "Cancel",
								className: "cancel-btn",
								callback: function() {
									
									bootbox.hideAll();	
								}
							  },
							  success: {
								label: "Save",
								className: "main-btn",
								callback: function() {
									$('#inviteVendor').find('input#invitevendorbtn').click();
									return false;
								}
							  }
							} 
						})
						.on('shown.bs.modal', function() {

							$('#inviteVendor').show(); 
							$('#inviteVendor').validate().resetForm(); 	
								
						})
						.on('hide.bs.modal', function(e) {
						
							
						})
						.modal('show');

					} 		
				});
		});

	}
	
	
	
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
