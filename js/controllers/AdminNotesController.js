'use strict';
var FormValidation = function () {
	
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
									var check = $('#manualAccount').find('input#check').val();
									var checkdate =  $('#manualAccount').find('input#checkdate').val();
									var carrier =  $('#manualAccount').find('select#carrier').val();
									var airwaybill =$('#manualAccount').find('input#airwaybill').val();
									var mailingaddress =  $('#manualAccount').find('input#mailingaddress').val();
									var po_no =$('#manualAccount').find('input#po_no').val();
									
										$.ajax({
											url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
											type: 'post',
											data: {
												action: 'payviamanualmode',
												poid:po_no,
												check:check,
												checkdate:checkdate,
												carrier:carrier,
												airwaybill:airwaybill,
												mailingaddress:mailingaddress
											},
											headers: {
												"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
												"x-taste-access-token": localStorage.getItem('access_token')
											},
											dataType:'json',
											success: function (data) {
												
												if(data.status_code == 200){
													$('a#'+po_no).removeClass('makepayment');
													$('a#'+po_no).addClass('fade_pay');
													bootbox.hideAll();	
												} else {
													if(data.status_code == 201){
														if(data.message != ''){
															$('div.bootbox').find('div.bootbox-body').append('<p style="color:red;">'+data.message+'</p>');
															
														} else {
															//createauthtoken(sendrequestlink);
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
          
            ManualBankAccountValidation();
        }

    };
}();

MetronicApp.controller('AdminNotesController', function($rootScope, $scope, $http, $timeout) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
	console.log($scope.timestamp);
    $scope.$on('$viewContentLoaded', function() {   
        Metronic.initAjax(); // initialize core components   
        var authtoken = localStorage.getItem('access_token');   
        $('.page-header').css('display','block');
        $('.page-sidebar-wrapper').css('display','block');
        $('.theme-panel').css('display','block');
        $('.page-quick-sidebar-wrapper').css('display','block');
        $('.page-footer').css('display','block'); 
        function callAtTimeout() {
			//$('.customername').SumoSelect({selectAll:true,csvDispCount:3,selectAlltext:'All' });
			$('div.page-content').find('div#site_statistics_loading').each(function(){
				$(this).css('display','none');
			});
		}

        function getunpaidpo(){
			//var grid = new Datatable();
			var data = { "action": "payments" ,"fetchdata": "all" };
			
			
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
		
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'adminnotes'}).
			success(function(data, status, headers, config) {
				if(data.data != ''){
					$scope.data = data.data;
					
					var table = $('#sample_2');

					var oTable = table.dataTable({

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
		
		
		function makevendorpayment(poid,paymentamount){
			$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			
			$http.post($scope.apppath+'/api/getunpaidpo',{poid:poid,action:'makevendorpayment',amount:paymentamount}).
				success(function(data, status, headers, config) {
					
					if(data.status_code == 200){
						bootbox.hideAll();	
					} else {
						if(data.status_code == 201){
							if(data.message != ''){
								$('div.bootbox').find('div.bootbox-body').append(data.message).css('color','red');
								
							} else {
								//createauthtoken(sendrequestlink);
							}
							
						} 
						
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
    
    
    $(document).on("click", ".makepayment", function() {
		var paymentamount = $(this).attr('data-payment-amount');
		var poid =  $(this).attr('id');
	
		if(poid != ''){
				if(paymentamount != 0){
						$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
						$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
						$http.post($scope.apppath+'/api/getunpaidpo',{poid:poid,action:'checktransferoption'}).
							success(function(data, status, headers, config) {
								if(data.status_code == 200){
									if(data.transferoption == 1){
										bootbox.dialog({
											title:'Please add your check details here',
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
												label: "Pay",
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
										
											$('#manualAccount').find('#accountingpopover').click();
											$('#manualAccount').find('#routingpopover').click();
											var routingcss = 0;
											$('#manualAccount').find('.popover').each(function(){
												if(routingcss != 0){
													$(this).css('display','block');
												} else {
													
													$(this).css('left','231.167px');
													$(this).css('top','-91.5px');
												}
												routingcss++;
											});
											$('#manualAccount').find('input#paymenttype').val('manual');
											$('#manualAccount').find('input#po_no').val(poid);
										
									
									} else {
										bootbox.dialog({
											message: "Do you want to make payment to vendor?",
											title: "Payment Confirmation",
											size: 'small',
											className:'paymentlinkconfirmation',
											buttons: {
											  danger: {
												label: "Cancel",
												className: "cancel-btn",
												callback: function() {
													bootbox.hideAll();	
												}
											  },
											  success: {
												label: "Pay",
												className: "main-btn",
												callback: function() {
													
													$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
													$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
													//$http.defaults.headers.common['Content-type'] ='application/json';
													$http.post($scope.apppath+'/api/getunpaidpo',{poid:poid,action:'makevendorpayment',amount:paymentamount}).
														success(function(data, status, headers, config) {
															//console.log(data);
															//console.log(data.status_code);
															if(data.status_code == 200){
																$('a#'+poid).removeClass('makepayment');
																$('a#'+poid).addClass('fade_pay');
																bootbox.hideAll();	
															} else {
																if(data.status_code == 201){
																	if(data.message != ''){
																		$('div.bootbox').find('div.bootbox-body').append('<p style="color:red;">'+data.message+'</p>');
																		
																	} else {
																		//createauthtoken(sendrequestlink);
																	}
																	
																} 
																
															}
													});
													return false;
													
												}
											  }
											}
									 });
									}
									
								} else {
									if(data.status_code == 201){
										
									} 
									
								}
						});
							
					 
				} 
				
			}
	});

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = false;
}).directive('paymentpopover', function ($compile,$templateCache) {

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
