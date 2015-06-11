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
									//console.log($scope.apppath);
									var vendoruserid  = localStorage.getItem('userid');
									
									var routing_number = $('input#routing').val();
									var account_number = $('input#account_number').val();
									var country  ='US';
									var tax_id =  $('#tax_id').val();
									var paymenttype = $('input#paymenttype').val();
									var payeename = $('input#payeename').val();
									var mailingaddress = $('input#mailingaddress').val();
								    var authcode = localStorage.getItem('payauthtoken');
								    var id = $('input#bankaccountid').val();
								    
								    
										//console.log('vendoruserid'+vendoruserid+'routing_number'+routing_number+'account_number'+account_number+'tax_id'+tax_id+'paymentype'+paymentype+'payeename'+payeename+'mailingaddress'+mailingaddress);
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
          
        }

    };
}();

$('body').on('click', function (e) {
    $('.icon-info').each(function () {
		//alert(e.target);
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

MetronicApp.controller('VendorBankAccountController', function($rootScope, $scope, $http, $timeout, $location,$window,$modal) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components
        var authtoken = localStorage.getItem('access_token');
       
		$scope.vendortoken = $location.url().split('/')[2];
		//$scope.location = $location;
		var vendoruserid  = localStorage.getItem('userid');
        function getunpaidpo(){
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
						
					//	console.log('data.taxinfo'+data.taxinformation);
					localStorage.setItem('taxinfo',data.taxinformation);
					// $('#addBankAccount').find('input#tax_id').val(data.taxinformation)
					 /*if(data.taxinformation != '')
						$scope.taxinformation = data.taxinformation;	
					
					 if(data.paymenttype != '' &&  typeof data.paymenttype != 'undefined' && data.paymenttype != 0 ){
						
						$scope.paymenttype = data.paymenttype;
						if(data.paymenttype == 'E'){

							$('input#paymenttype').val('electronic');
							$('p#selectedpaymentmethod').text('Selected Payment Method : Electronic').css('display','block');
						} else {
							
							$('input#paymenttype').val('manual');
							$('p#selectedpaymentmethod').text('Selected Payment Method : Manual').css('display','block');
						}
						$('button#bankinfo').css('display','block');
						$('form#choosepayment').css('display','none');
					} */
						
						var vm = this;
				} else {
					
					if(data.status_message == 'Invalid token'){
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
		FormValidation.init();
		
			
    });
    
    

	
	
	$scope.hideBankPopup = function() {
		bootbox.hideAll();	
		
	}	
	$scope.submitted = false;
	
	$scope.updateBankAccountPopup = function() {
		
		var paymenttype= $('input#paymenttype').val();
		var vendoruserid  = localStorage.getItem('userid');
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
					/*var routingcss = 0;
					$('#addBankAccount').find('.popover').each(function(){
						if(routingcss == 0){
							$(this).css('display','block');
						}
						routingcss++;
					});*/
				}
				
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
						//title: "Your bank account must be a checking account.",
						//title: "Please add your checking account details",
						//message: $('#addBankAccount'),
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
						//$('#addBankAccount').hide().appendTo('body');
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
						buttons: {
						  danger: {
							label: "Cancel",
							className: "btn",
							callback: function() {
								bootbox.hideAll();	
							}
						  },
						  success: {
							label: "Add Account",
							className: "main-btn",
							callback: function() {
								$('input#savebankinfo').click();
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
	  $scope.saveAccountDetail = function () {
		console.log('comes here');
	  };

	  /*$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	  };*/
});


