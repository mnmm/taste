'use strict';
var FormValidation = function () {
	var handleValidation1 = function() {
						var form1 = $('#addBankAccount');
						var error1 = $('.alert-danger', form1);
						var success1 = $('.alert-success', form1);	
                        form1.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								messages: {
								   
								},
								rules: {
									/*payeename: {
										required: true,
									},*/
									mailingaddress: {
										required: true,
										email:true
									},
									routing: {
										checkroutingnumber:true,
										required: true,
										digits:true
									},
									account_number: {
										required:true,
										digits:true,
										maxlength:17
									},
									confirm_account_number: {
										equalTo : "#account_number",
										digits: true,
										maxlength:17
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

								submitHandler: function (form) {

									var vendoruserid  = localStorage.getItem('userid');
									
									var routing_number = $('input#routing').val();
									var account_number = $('input#account_number').val();
									var country  ='US';
									var tax_id =  $('#tax_id').val();
									var paymenttype = $('input#paymenttype').val();
									var payeename = $('input#payeename').val();
									var mailingaddress = $('input#mailingaddress').val();
								    var authcode = localStorage.getItem('payauthtoken');
								    var id = $('#addBankAccount').find('input#bankaccountid').val();

										$.ajax({
											url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
											type: 'post',
											data: {
												action: 'savebankaccountinfo',
												vendorid:vendoruserid,
												routing_number:routing_number,
												account_number:account_number,
												country:country,
												tax_id:tax_id,
												paymenttype:paymenttype,
												payeename:payeename,
												mailingaddress:mailingaddress,
												authcode:authcode,
												bankid:id
											},
											headers: {
												"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
												"x-taste-access-token": localStorage.getItem('access_token')
											},
											dataType:'json',
											success: function (data) {
												
												if(data.status_code == 200 ){
													bootbox.hideAll();	
													$('div.paymentmethods').find('div#manageBankAccounts').find('button#addbankacnt').text('Update Bank Account');
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
	
	var handleManualValidation = function() {
						var form2 = $('form#manualBankAccount');
						var error1 = $('.alert-danger', form2);
						var success1 = $('.alert-success', form2);	
                        form2.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								messages: {
								   
								},
								rules: {
									payeename:{
										required:true
									},
									mailingaddress: {
										required: true
									},
									zipcode: {
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

								submitHandler: function (form2) {
									
									var vendoruserid  = localStorage.getItem('userid');
									
									var payeename = $('#manualBankAccount').find('input#payeename').val();
									var mailingaddress =  $('#manualBankAccount').find('input#mailingaddress').val();
									var zipcode =  $('#manualBankAccount').find('input#zipcode').val();
									var id =$('#manualBankAccount').find('input#bankaccountid').val();
									
								     var authcode = localStorage.getItem('payauthtoken');
								     
										$.ajax({
											url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
											type: 'post',
											data: {
												action: 'savebankaccountinfomanual',
												vendorid:vendoruserid,
												payeename:payeename,
												mailingaddress:mailingaddress,
												zipcode:zipcode,
												bankid:id,
												authcode:authcode
											},
											headers: {
												"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
												"x-taste-access-token": localStorage.getItem('access_token')
											},
											dataType:'json',
											success: function (data) {
												
												if(data.status_code == 200 ){
													bootbox.hideAll();
													$('div.paymentmethods').find('div#manageBankAccounts').find('button#addpayeebtn').css('display','none');	
													$('div.paymentmethods').find('div#manageBankAccounts').find('button#updatepayeebtn').css('display','inline-block');
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
	
	
	
	var ManualBankAccountValidation = function() {
						var form3 = $('form#manualAccount');
						var error1 = $('.alert-danger', form3);
						var success1 = $('.alert-success', form3);	
                        form3.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								messages: {
								   
								},
								rules: {
									check:{
										required:true
									},
									checkdate: {
										required: true
									},
									carrier: {
										required:true
									},
									airwaybill: {
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

								submitHandler: function (form3) {
									
									var vendoruserid  = localStorage.getItem('userid');
									
									var check = $('#manualAccount').find('input#check').val();
									var checkdate =  $('#manualAccount').find('input#checkdate').val();
									var carrier =  $('#manualAccount').find('select#carrier').val();
									var airwaybill =$('#manualAccount').find('input#airwaybill').val();
									var mailingaddress =  $('#manualAccount').find('input#mailingaddress').val();
									/*var locationname =$('#manualAccount').find('input#locationname').val();
									var crossstreet =$('#manualAccount').find('input#crossstreet').val();
									var streetaddress1 =$('#manualAccount').find('input#streetaddress1').val();
									var streetaddress2 =$('#manualAccount').find('input#streetaddress2').val();
									var neighborhood =$('#manualAccount').find('input#neighborhood').val();
									var city =$('#manualAccount').find('input#city').val();
									var state =$('#manualAccount').find('input#state').val();
									var zip =$('#manualAccount').find('input#zip').val();*/
									
									var id =$('#manualAccount').find('input#bankaccountid').val();
									
								     var authcode = localStorage.getItem('payauthtoken');
								     
										$.ajax({
											url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
											type: 'post',
											data: {
												action: 'savebankaccountinfomanual',
												vendorid:vendoruserid,
												check:check,
												checkdate:checkdate,
												carrier:carrier,
												airwaybill:airwaybill,
												mailingaddress:mailingaddress,
												bankid:id,
												authcode:authcode
											},
											headers: {
												"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
												"x-taste-access-token": localStorage.getItem('access_token')
											},
											dataType:'json',
											success: function (data) {
												
												if(data.status_code == 200 ){
													bootbox.hideAll();
													$('div.paymentmethods').find('div#manageBankAccounts').find('button#addpayeebtn').css('display','none');	
													$('div.paymentmethods').find('div#manageBankAccounts').find('button#updatepayeebtn').css('display','inline-block');
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
            handleValidation1();
            handleManualValidation();
            ManualBankAccountValidation();
        }

    };
}();

var ComponentsPickers = function () {

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
              //  rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: false,
                
            }).on('changeDate', function (ev) {
				$('#manualAccount').find('input#datepickerhide').val(1);
				 $(this).datepicker('hide');
				 ev.preventDefault();
            }).on('hide', function (ev) {
				$('#manualAccount').find('input#datepickerhide').val(1);
				 $(this).datepicker('hide');
				 ev.preventDefault();
            });
            
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    }
     return {
        //main function to initiate the module
        init: function () {
            handleDatePickers();
        }
    };

}();
            
/*$('body').on('click', function (e) {
    $('.icon-info').each(function () {
		//alert(e.target);
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
			
            $(this).popover('hide');
        }
    });
});*/

MetronicApp.controller('VendorBankAccountController', function($rootScope, $scope, $http, $timeout, $location,$window,$modal) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components
        var authtoken = localStorage.getItem('access_token');
       
		$scope.vendortoken = $location.url().split('/')[2];
		//$scope.location = $location;
		var vendoruserid  = localStorage.getItem('userid');
		
		function makeSettings(optionsfortransfer) {
			//console.log('optionsfortransfer'+optionsfortransfer);
			var transfersplit = optionsfortransfer.split('##@##');
			
			$('div.paymentmethods').find('div#manageBankAccounts').find('span#enabledtransfertext').html(transfersplit[0]);
			$('div.paymentmethods').find('div#manageBankAccounts').find('input#transfertypeselected').val(transfersplit[1]);
			$('div.paymentmethods').find('div#manageBankAccounts').find('button#transferbtn').html('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>'+transfersplit[2]);	

			$('div.paymentmethods').find('div.modal-body').find('div#manageBankAccounts').find('button#'+transfersplit[3]).css('display','block');
			
			$('div.paymentmethods').find('div.modal-body').find('div#manageBankAccounts').find('button#'+transfersplit[4]).css('display','inline-block');
			
			
		}


        function getunpaidpo(){
			$http.get($scope.apppath+"/api/checklogin").
				success(function(data1) {
						$scope.userroleInfo = data1;
						vendoruserid = $scope.userroleInfo.id;
						localStorage.setItem('userid',vendoruserid);
						$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
						$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
						
						$http.post($scope.apppath+'/api/getunpaidpo',{action:'gettaxidforvendor',vendorid:vendoruserid}).
						success(function(data, status, headers, config) {
							if(data.status_code == 200){
									$scope.apppath= 'https://mnmdesignlabs.com/taste';
									
									
									var modalInstance = $modal.open({
									  animation: true,
									  templateUrl: 'myModalContent.html',
									  controller: 'ModalInstanceCtrl',
									  windowClass:'paymentmethods',
									  resolve: {
										
									  }
									});
									
									var transfertext = '';
									var transferbtntext = '';
									if(data.transferoption != '' && typeof data.transferoption != 'undefined'){
										if(data.transferoption  == 1){
											transfertext ='Manual check transfer enabled';
											transferbtntext ='Switch to enable ACH transfer';
										} else {
											transfertext ='ACH transfers enabled';
											transferbtntext ='Switch to enable manual payment';
										}
									}
									
									var displaybuttonclass = 'addbankacnt';
									 if(data.paymenttype != '' &&  typeof data.paymenttype != 'undefined' && data.paymenttype != 0 ){
										 if(data.paymenttype == 'electronic'){
											 displaybuttonclass = 'updatebankacnt'
										 } else {
											displaybuttonclass = 'addbankacnt';
										 }
									 } else {
										displaybuttonclass = 'addbankacnt';
									 }
									 
									var displaypayeebuttonclass = 'addpayeebtn';
									if(data.manualexists != '' &&  typeof data.manualexists != 'undefined' && data.manualexists != 0 ){
										displaypayeebuttonclass = 'updatepayeebtn'
									 } else {
										displaypayeebuttonclass = 'addpayeebtn';
									 }
									
									var optionsfortransfer = transfertext+'##@##'+data.transferoption+'##@##'+transferbtntext+'##@##'+displaybuttonclass+'##@##'+displaypayeebuttonclass;
									/*$timeout(function () {
										$timeout(makeSettings, 500,optionsfortransfer);
									});*/
									$timeout(function() {makeSettings(optionsfortransfer)}, 500); 
									$scope.transferoption = data.transferoption;
									
									
									localStorage.setItem('taxinfo',data.taxinformation);
								
									var vm = this;
							} else {
								console.log('data.status_message'+data.status_message);
								if(data.status_message == 'Invalid token'){
									
								} else {
									createauthtoken(getunpaidpo);
								}
							}		
						});
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
		FormValidation.init();
		ComponentsPickers.init();
			
    });
    
    

	
	
	$scope.hideBankPopup = function() {
		bootbox.hideAll();	
		
	}	
	$scope.submitted = false;
	
	$scope.updateBankAccountPopup = function() {
		
		var paymenttype= $('input#paymenttype').val();
		var vendoruserid  = localStorage.getItem('userid');
		$http.get($scope.apppath+"/api/checklogin").
				success(function(data1) {
					$scope.userroleInfo = data1;
					 vendoruserid = $scope.userroleInfo.id;
		$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
		$http.post($scope.apppath+'/api/getunpaidpo',{action:'getbankaccountinfo',vendorid:vendoruserid}).
			success(function(data, status, headers, config) {
				
				if(data.paymenttype != '' &&  data.paymenttype == 'manual'){
					//console.log(data.bankaccountinfo);
					//console.log(data.bankaccountinfo.id);
					$('#addBankAccount').find('input#bankaccountid').val(data.bankaccountinfo.id);
					$('#addBankAccount').find('input#routing').val(data.bankaccountinfo.routing_number);
					$('#addBankAccount').find('input#account_number').val(data.bankaccountinfo.account_number);
					$('#addBankAccount').find('input#confirm_account_number').val(data.bankaccountinfo.account_number);
					$('#addBankAccount').find('input#tax_id').val(data.bankaccountinfo.tax_id);
					$('#addBankAccount').find('input#mailingaddress').val(data.bankaccountinfo.mailing_address);
					bootbox.dialog({
						//title: "Your bank account must be a checking account.",
						title: "Please add your checking account details",
						message: $('#addBankAccount'),
						show: false 
					})
					.on('shown.bs.modal', function() {
						$('#addBankAccount')
							.show()                             // Show the login form
							.validate().resetForm(); // Reset form
					})
					.on('hide.bs.modal', function(e) {
						// Therefor, we need to backup the form
						$('#addBankAccount').hide().appendTo('body');
					})
					.modal('show');
					$('#addBankAccount').find('#accountingpopover').click();
					$('#addBankAccount').find('#routingpopover').click();
					var routingcss = 0;
					$('#addBankAccount').find('.popover').each(function(){
						if(routingcss != 0){
							$(this).css('display','block');
						} else {
							$(this).css('left','231.167px');
							$(this).css('top','-91.5px');
						}
						routingcss++;
					});
					$('#addBankAccount').find('input#paymenttype').val('manual');
					$('#addBankAccount').find('input#payeename').val(data.payeename);
					$('#addBankAccount').find('#payeeinfo').show();
					$('#addBankAccount').find('#mailinginfo').show();
					$('#addBankAccount').find('input#mailingaddress').prop('disabled',false);
					$('#addBankAccount').find('button#accountbtn').text('Update account');
					
					
				} else {
					
					//console.log(data.bankaccountinfo.id);
					$('#addBankAccount').find('input#bankaccountid').val(data.bankaccountinfo.id);
					$('#addBankAccount').find('input#routing').val(data.bankaccountinfo.routing_number);
					$('#addBankAccount').find('input#account_number').val(data.bankaccountinfo.account_number);
					$('#addBankAccount').find('input#confirm_account_number').val(data.bankaccountinfo.account_number);
					$('#addBankAccount').find('input#tax_id').val(data.bankaccountinfo.tax_id);
					bootbox.dialog({
					//title: "Your bank account must be a checking account.",
					title: "Please add your checking account details",
					message: $('#addBankAccount'),
					show: false 
					})
					.on('shown.bs.modal', function() {
						$('#addBankAccount')
							.show()                             // Show the login form
							.validate().resetForm(); // Reset form
					})
					.on('hide.bs.modal', function(e) {
						// Bootbox will remove the modal (including the body which contains the login form)
						// after hiding the modal
						// Therefor, we need to backup the form
						$('#addBankAccount').hide().appendTo('body');
					})
					.modal('show');
					
					
					/*$('#addBankAccount').find('.popover').each(function(){
						$(this).css('display','block');
					});*/
					$('#addBankAccount').find('input#routing').focus();
					
					$('#addBankAccount').find('input#paymenttype').val('electronic');
					$('#addBankAccount').find('#payeeinfo').hide();
					$('#addBankAccount').find('#mailinginfo').hide();
					$('#addBankAccount').find('input#mailingaddress').prop('disabled',true);
					$('#addBankAccount').find('button#accountbtn').text('Update account');
					$('#addBankAccount').find('#accountingpopover').click();
					$('#addBankAccount').find('#routingpopover').click();
					var routingcss = 0;
					$('#addBankAccount').find('.popover').each(function(){
						if(routingcss != 0){
							$(this).css('display','block');
						} else {
							$(this).css('left','231.167px');
							$(this).css('top','-91.5px');
						}
						routingcss++;
					});
				}
				
			});	
			});	
		
	}
	
	
    // Can use parseInt(x, 10) on $scope.checkboxSelection or index.toString() if you want to remove the single quotes you see in isCheckboxSelected('1').
    $scope.paymentMethodChosen = function (value) {
		
		
		if(value == 'electronic'){
			var modalInstance = $modal.open({
			  animation: true,
			  templateUrl: 'myModalContent.html',
			  controller: 'ModalInstanceCtrl',
			  windowClass:'paymentmethods',
			  resolve: {
				
			  }
			});
			
		} else {
			
			var vendoruserid  = localStorage.getItem('userid');
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getpayeename',vendorid:vendoruserid}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200){
					
					 bootbox.dialog({
						message: $('#manageBankAccounts'),
						show: false,
						animate:true  
					})
					.on('shown.bs.modal', function() {
						/*$('#addBankAccount')
							.show()                             // Show the login form
							.validate().resetForm(); // Reset form*/
							
						$('#manageBankAccounts').show(); 
					})
					.on('hide.bs.modal', function(e) {
						// Therefor, we need to backup the form
						$('#manageBankAccounts').hide().appendTo('body'); 
					})
					.modal('show');
					$('#addBankAccount').find('#accountingpopover').click();
					$('#addBankAccount').find('#routingpopover').click();
					var routingcss = 0;
					$('#addBankAccount').find('.popover').each(function(){
						if(routingcss != 0){
							$(this).css('display','block');
						} else {
							$(this).css('left','231.167px');
							$(this).css('top','-91.5px');
						}
						routingcss++;
					});
					$('#addBankAccount').find('input#paymenttype').val('manual');
					$('#addBankAccount').find('input#payeename').val(data.payeename);
					$('#addBankAccount').find('input#tax_id').val(data.taxinfo);
					$('#addBankAccount').find('#payeeinfo').show();
					$('#addBankAccount').find('#mailinginfo').show();
					$('#addBankAccount').find('input#mailingaddress').prop('disabled',false);
					
					
					
				} else {
				
					createauthtoken(paymentMethodChosen);
				
				}		
			});
			
			
		}
	}
	
	
	
	
	/*$scope.makeAccountingFocus = function() {
		$('#account_number').prop('type', 'text');

	}
	
	$scope.makeAccountingBlur = function() {
		$('#account_number').prop('type', 'password');
	}
	
	$scope.makeAccountingConfirmFocus = function() {
		$('#confirm_account_number').prop('type', 'text');
	}
	
	$scope.makeAccountingConfirmBlur = function() {
		$('#confirm_account_number').prop('type', 'password');
	}*/
	
	
    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = false;
})
.directive('routingpopover', function ($compile,$templateCache) {

var getTemplate = function (contentType) {
    var template = '';
    switch (contentType) {
        case 'user':
            template = $templateCache.get("routingpopover.html");
            break;
    }
    return template;
}
return {
    restrict: "A",
    link: function (scope, element, attrs) {
        var popOverContent;
      
        popOverContent = getTemplate("user");                  
        
        var options = {
            content: popOverContent,
            placement: "right",
            html: true,
            date: scope.date
        };
        $(element).popover(options);
    }
};
})
.directive('accountingpopover', function ($compile,$templateCache) {

var getTemplate = function (contentType) {
    var template = '';
    switch (contentType) {
        case 'user':
            template = $templateCache.get("accountingpopover.html");
            break;
    }
    return template;
}
return {
    restrict: "A",
    link: function (scope, element, attrs) {
        var popOverContent;
      
        popOverContent = getTemplate("user");                  
        
        var options = {
            content: popOverContent,
            placement: "right",
            html: true,
            date: scope.date
        };
        $(element).popover(options);
    }
};
});

 
MetronicApp.controller('ModalInstanceCtrl', function ($rootScope, $scope, $http, $timeout, $location,$window,$modal) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
	$scope.openBankAccountPopUp = function () {
			
			var vendoruserid  = localStorage.getItem('userid');
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getpayeename',vendorid:vendoruserid}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200){
					
					bootbox.dialog({
						title:'Please add your checking account details',
						message: $('#addBankAccount'),
						show: false,
						animate:true,
						closeButton: false,
						className:'addaccountdetail',
						buttons: {
						  danger: {
							label: "Cancel",
							className: "cancel-btn",
							callback: function() {
								bootbox.hideAll();	
							}
						  },
						  success: {
							label: "Add Account",
							className: "main-btn",
							callback: function() {
								$('input#savebankinfo').click();
								return false;
							}
						  }
						} 
					})
					.on('shown.bs.modal', function() {
						
						$('#addBankAccount').show();     	
							
					})
					.on('hide.bs.modal', function(e) {
						// Bootbox will remove the modal (including the body which contains the login form)
						// after hiding the modal
						// Therefor, we need to backup the form
						//$('#addBankAccount').hide().appendTo('body');
						//$('#addBankAccount').hide().appendTo('body');
						$('#addBankAccount').hide().appendTo('.fade-in-up');    
					})
					.modal('show');
					
					//$('#addBankAccount').find('#accountingpopover').click();
					$('#addBankAccount').find('#routingpopover').click();
					var routingcss = 0;
					$('#addBankAccount').find('.popover').each(function(){
						if(routingcss != 0){
							$(this).css('display','block');
						} else {
							
							$(this).css('left','231.167px');
							$(this).css('top','-91.5px');
						}
						routingcss++;
					});
					$('#addBankAccount').find('input#paymenttype').val('electronic');
					$('#addBankAccount').find('#payeeinfo').hide();
					$('#addBankAccount').find('#mailinginfo').hide();
					$('#addBankAccount').find('input#mailingaddress').prop('disabled',true);
					$('#addBankAccount').find('input#tax_id').val(data.taxinfo);
				
					/*var modalInstance = $modal.open({
					  animation: true,
					  templateUrl: 'addBankAccount.html',
					  backdrop:false,
					  windowClass:'addaccountdetail',
					  resolve: {
						
					  }
					});
					$('#addBankAccount').find('input#tax_id').val(localStorage.getItem('taxinfo'));
					
					//$('form#addBankAccount').resetForm();   */     // Show the login form
					
					
				} 		
			});

	}
	
	
	$scope.openUpdateBankAccountPopUp = function () {
			
			var vendoruserid  = localStorage.getItem('userid');
			$http.get($scope.apppath+"/api/checklogin").
				success(function(data1) {
					$scope.userroleInfo = data1;
					 vendoruserid = $scope.userroleInfo.id;
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getbankaccountinfo',vendorid:vendoruserid}).
			success(function(data, status, headers, config) {

					
					bootbox.dialog({
						title:'Please update your checking account details',
						message: $('#addBankAccount'),
						show: false,
						animate:true,
						closeButton: false,
						className:'addaccountdetail',
						buttons: {
						  danger: {
							label: "Cancel",
							className: "cancel-btn",
							callback: function() {
								bootbox.hideAll();	
							}
						  },
						  success: {
							label: "Update Account",
							className: "main-btn",
							callback: function() {
								$('input#savebankinfo').click();
								return false;
							}
						  }
						} 
					})
					.on('shown.bs.modal', function() {
						
						$('#addBankAccount').show();     	
							
					})
					.on('hide.bs.modal', function(e) {
						
						// Therefor, we need to backup the form
						//$('#addBankAccount').hide().appendTo('body');
						//$('#addBankAccount').hide().appendTo('body');  
						$('#addBankAccount').hide().appendTo('.fade-in-up');  
					})
					.modal('show');
					$('#addBankAccount').find('button.cancel-btn').removeClass('btn');
					$('#addBankAccount').find('button.main-btn').removeClass('btn');
					$('#addBankAccount').find('input#bankaccountid').val(data.bankaccountinfo.id);
					$('#addBankAccount').find('input#routing').val(data.bankaccountinfo.routing_number);
					$('#addBankAccount').find('input#account_number').val(data.bankaccountinfo.account_number);
					$('#addBankAccount').find('input#confirm_account_number').val(data.bankaccountinfo.account_number);
					$('#addBankAccount').find('input#tax_id').val(data.bankaccountinfo.tax_id);
					
					$('#addBankAccount').find('input#routing').focus();
					
					$('#addBankAccount').find('input#paymenttype').val('electronic');
					$('#addBankAccount').find('#payeeinfo').hide();
					$('#addBankAccount').find('#mailinginfo').hide();
					$('#addBankAccount').find('input#mailingaddress').prop('disabled',true);
					$('#addBankAccount').find('#accountingpopover').click();
					$('#addBankAccount').find('#routingpopover').click();
					var routingcss = 0;
					$('#addBankAccount').find('.popover').each(function(){
						if(routingcss != 0){
							$(this).css('display','block');
						} else {
							$(this).css('left','231.167px');
							$(this).css('top','-91.5px');
						}
						routingcss++;
					});
					
				
			});	
		});	

	}
	
	
	
	
	
	//function to add payee details
	$scope.addPayeeDetails = function () {
			
			
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
						title:'Please add your checking account details',
						//message: $('#manualBankAccount'),
						message: $('#manualAccount'),
						show: false,
						animate:true,
						closeButton: false,
						className:'addaccountdetail',
						buttons: {
						  danger: {
							label: "Cancel",
							className: "cancel-btn",
							callback: function() {
								$('#manualAccount').find('input#datepickerhide').val(0);
								bootbox.hideAll();	
							}
						  },
						  success: {
							label: "Save",
							className: "main-btn",
							callback: function() {
								//console.log('comes here');
								
								//$('#manualBankAccount').find('input#savemanualbankinfo').click();
								$('#manualAccount').find('input#savemanualbankinfo').click();
								return false;
							}
						  }
						} 
					})
					.on('shown.bs.modal', function() {
						
						//$('#manualBankAccount').show(); 
						//$('#manualBankAccount').validate().resetForm();    
						$('#manualAccount').show(); 
						$('#manualAccount').validate().resetForm(); 	
							
					})
					.on('hide.bs.modal', function(e) {
					
						var hiddendatepicker = $('#manualAccount').find('input#datepickerhide').val();
						
						if (hiddendatepicker == 1) {
							// datepicker is open. you need the second condition because it starts off as visible but empty
						} else {
							console.log('hiddendatepicker'+hiddendatepicker);
							$('#manualAccount').hide().appendTo('.fade-in-up');  
						}
						
					})
					.modal('show');
					
					//$('#manualBankAccount').find('input#paymenttype').val('manual');
					//$('#manualBankAccount').find('input#tax_id').val(data.taxinfo);
					
					$('#manualAccount').find('input#paymenttype').val('manual');
					$('#manualAccount').find('input#tax_id').val(data.taxinfo);
					
				} 		
			});
		});

	}
	
	
	//function to update payee details
	$scope.updatePayeeDetails = function () {
			
			
			var vendoruserid  = localStorage.getItem('userid');
			$http.get($scope.apppath+"/api/checklogin").
				success(function(data1) {
					$scope.userroleInfo = data1;
					 vendoruserid = $scope.userroleInfo.id;
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getpayeeinfo',vendorid:vendoruserid}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200){
					
					bootbox.dialog({
						title:'Please update your checking account details',
						message: $('#manualAccount'),
						show: false,
						animate:true,
						closeButton: false,
						className:'addaccountdetail',
						buttons: {
						  danger: {
							label: "Cancel",
							className: "cancel-btn",
							callback: function() {
								$('#manualAccount').find('input#datepickerhide').val(0);
								bootbox.hideAll();	
							}
						  },
						  success: {
							label: "Update",
							className: "main-btn",
							callback: function() {
								
								$('#manualAccount').find('input#savemanualbankinfo').click();
								return false;
							}
						  }
						} 
					})
					.on('shown.bs.modal', function() {
						
						$('#manualAccount').show(); 
						$('#manualAccount').validate().resetForm();    	
							
					})
					.on('hide.bs.modal', function(e) {
						
						var hiddendatepicker = $('#manualAccount').find('input#datepickerhide').val();
						
						if (hiddendatepicker == 1) {
							// datepicker is open. you need the second condition because it starts off as visible but empty
						} else {
							console.log('hiddendatepicker'+hiddendatepicker);
							$('#manualAccount').hide().appendTo('.fade-in-up');  
						}
						
					})
					.modal('show');
					
					
					$('#manualAccount').find('button.cancel-btn').removeClass('btn');
					$('#manualAccount').find('button.main-btn').removeClass('btn');
					$('#manualAccount').find('input#check').val(data.bankaccountinfo.check);
					var checkdate = data.bankaccountinfo.checkdate;
					var extractdate =  checkdate.split('-');
					var newdate = extractdate[1]+'-'+extractdate[2]+'-'+extractdate[0];
					$('#manualAccount').find('input#checkdate').val(newdate);
					
					$('#manualAccount').find('select#carrier').val(data.bankaccountinfo.carrier);
					$('#manualAccount').find('input#airwaybill').val(data.bankaccountinfo.airwaybill);
					$('#manualAccount').find('input#mailing_address').val(data.bankaccountinfo.mailing_address);
					$('#manualAccount').find('input#bankaccountid').val(data.bankaccountinfo.id);

				} 		
			});
		});

	}
	
	//function to redirect to dashboard
	$scope.redirecToDashboard = function () {
		bootbox.hideAll();	
		modalInstance.$promise.then(modalInstance.hide);
		$window.location.href = '#/vendors';
		
	}
	
	
	//function to change between transfer methods
	$scope.toggleTransferMethods = function () {
		
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
						var transfertypeselected = $('div.paymentmethods').find('div#manageBankAccounts').find('input#transfertypeselected').val();
						
						if(transfertypeselected == 0){
							var newtransferstatus = 1;
							var btnlabel = 'Turn off ACH transfers';
						} else {
							var newtransferstatus = 0;
							var btnlabel = 'Turn off manual transfers';
						}
						
						bootbox.dialog({
							message: $('#transfers'),
							show: false,
							animate:true,
							closeButton: false,
							className:'transferoptions',
							buttons: {
							  danger: {
								label: "Cancel",
								className: "cancel-btn",
								callback: function() {
									bootbox.hideAll();	
								}
							  },
							  success: {
								label: btnlabel,
								className: "off-red-btn",
								callback: function() {
									//$('input#changetransfermethod').click();
									$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
									$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
									var vendoruserid  = localStorage.getItem('userid');
									$http.post($scope.apppath+'/api/getunpaidpo',{action:'updatetransfermethod',vendorid:vendoruserid,transferstatus:newtransferstatus}).
									success(function(data, status, headers, config) {
										if(data.status_code == 200){
											if(data.updated == 1 && typeof data.updated != 'undefined'){
												var transfertext = '';
												var transferbtntext = '';
												
													if(newtransferstatus  == 1){
														transfertext ='Manual check transfer enabled';
														transferbtntext ='Switch to enable ACH transfer';
													} else {
														transfertext ='ACH transfers enabled';
														transferbtntext ='Switch to enable manual payment';
													}

													$('div.paymentmethods').find('div#manageBankAccounts').find('span#enabledtransfertext').html(transfertext);
													$('div.paymentmethods').find('div#manageBankAccounts').find('input#transfertypeselected').val(newtransferstatus);
													$('div.paymentmethods').find('div#manageBankAccounts').find('button#transferbtn').html('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>'+transferbtntext);	
													bootbox.hideAll();	
												
											}
										} 		
									});
									return false;
								}
							  }
							} 
						})
						.on('shown.bs.modal', function() {
							
							$('#transfers').show();     	
								
						})
						.on('hide.bs.modal', function(e) {
							//$('#addBankAccount').hide().appendTo('body');
							$('#transfers').hide().appendTo('body');  
						})
						.modal('show');
						
						if(transfertypeselected == 1){
							$('div.transferoptions').find('div#transfertextmanual').css('display','block');
							$('div.transferoptions').find('div#transfertextautomatic').css('display','none');
						} else {
							$('div.transferoptions').find('div#transfertextautomatic').css('display','block');
							$('div.transferoptions').find('div#transfertextmanual').css('display','none');
						}
				} 		
			});
		});

	}
	  

	  /*$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	  };*/
});


