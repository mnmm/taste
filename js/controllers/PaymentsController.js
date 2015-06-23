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
													$('a#'+po_no).parent('td').prev('td').find('span.label').removeClass('label-danger');
													$('a#'+po_no).parent('td').prev('td').find('span.label').addClass('label-success');
													$('a#'+po_no).parent('td').prev('td').find('span.label').text('paid');
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
												/*if(data.status_code == 200 ){
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
												}*/
											}
										});
									
									}
								});
	}
	
	
	/*var PriorityValidation = function() {
						var form4 = $('form#changepriority');
						var error1 = $('.alert-danger', form3);
						var success1 = $('.alert-success', form4);	
                        form4.validate({
								errorElement: 'span', //default input error message container
								errorClass: 'help-block help-block-error', // default input error message class
								focusInvalid: false, // do not focus the last invalid input
								ignore: "",  // validate all fields including form hidden input
								messages: {
								   
								},
								rules: {
									priority:{
										required:true
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

								submitHandler: function (form4) {
									alert('comes here');
									var priority = $('#changepriority').find('input#priority').val();
									var po_no = $('#changepriority').find('input#po_no').val();
									
										$.ajax({
											url: 'https://mnmdesignlabs.com/taste/api/getunpaidpo',
											type: 'post',
											data: {
												action: 'updatepriority',
												priority:priority,
												poid:po_no
											},
											headers: {
												"x-taste-request-timestamp": Math.floor((new Date().getTime()/1000)), 
												"x-taste-access-token": localStorage.getItem('access_token')
											},
											dataType:'json',
											success: function (data) {
												
												if(data.status_code == 200){
													$('table#sample_2').find('tr').find('td').each(function(){
														
													});
													
													bootbox.hideAll();	
												} else {
													if(data.status_code == 201){
														if(data.message != ''){
															$('div.bootbox').find('div.bootbox-body').append('<p style="color:red;">'+data.message+'</p>');
														} else {
															
														}
													} 
												}											
											}
										});
									
									}
								});
	}*/

	return {
        //main function to initiate the module
        init: function () {
          
            ManualBankAccountValidation();
           // PriorityValidation();
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

MetronicApp.controller('PaymentsController', function($rootScope, $scope, $http, $timeout) {
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
		
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'payments'}).
			success(function(data, status, headers, config) {
				if(data.data != ''){
					$scope.data = data.data;
					
					var table = $('#sample_2');

					/* Table tools samples: https://www.datatables.net/release-datatables/extras/TableTools/ */

					/* Set tabletools buttons and button container */

					/*$.extend(true, $.fn.DataTable.TableTools.classes, {
						"container": "btn-group tabletools-btn-group pull-right",
						"buttons": {
							"normal": "btn btn-sm default",
							"disabled": "btn btn-sm default disabled"
						}
					});*/
		
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
						"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
						"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
							//console.log('in this function'+aData[0]);
							if (aData[0].indexOf("low") >= 0){
								//$('tr', nRow).addClass('yellow');
								nRow.className = 'priority-yellow';
							} else if(aData[0].indexOf("medium") >= 0){
								//$('tr', nRow).addClass('blue');
								nRow.className = 'priority-orange';
							} else if(aData[0].indexOf("high") >= 0){
								//$('tr', nRow).addClass('red');
								nRow.className = 'priority-red';
							} else {
								//$('tr', nRow).addClass('orange');
								nRow.className = 'priority-green';
							}
							 // Bold the grade for all 'A' grade browsers
							/* if ( aData[4] == "A" )
							 {
							   $('td:eq(4)', nRow).html( 'A' );
							 }*/
						 }
						
						//"data":$scope.podata,
						// Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
						// setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
						// So when dropdowns used the scrollable div should be removed. 
						//"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

					});

					var tableWrapper = $('#sample_2_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
					tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
					table.$('[data-toggle="popover"]').popover().mouseover(function(e) {e.preventDefault();});
				}
						/*if(data.allvendordetails != ''){
								$scope.allvendorsdetails = data.allvendorsdetails;
								$timeout(function () {
									 $timeout(callAtTimeout, 500);
								});
						}*/
			});
			
			/*$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
			$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
			$http.post($scope.apppath+'/api/getunpaidpo',{action:'getvendorsdetails'}).
			success(function(data, status, headers, config) {
				if(data.allvendordetails != ''){
						$scope.allvendorsdetails = data.allvendorsdetails;
						$timeout(function () {
							 $timeout(callAtTimeout, 500);
						});
				}
			});*/

			/*grid.init({
            src: $("#datatable_orders"),
            onSuccess: function (grid) {
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                "lengthMenu": [
                    [5, 10, 15, 20, 25, -1],
                    [5, 10, 15, 20, 25, "All"] // change per page values here
                ],
                "pageLength": 5, // default record count per page
                 "language": { // language settings
                        // metronic spesific
                        "metronicGroupActions": "_TOTAL_ records selected:  ",
                       // "metronicAjaxRequestGeneralError": "Could not complete request. Please check your internet connection",

                        // data tables spesific
                        "lengthMenu": "<span class='seperator'>|</span>View _MENU_ records",
                        "info": "<span class='seperator'>|</span>Found total _TOTAL_ records",
                        "infoEmpty": "No records found to show",
                        "emptyTable": "No data available in table",
                        "zeroRecords": "No matching records found"
                    },
              "ajax": {
                    "url": "https://mnmdesignlabs.com/taste/api/getunpaidpo", // ajax source
                    "type": "POST",
                    "data": function(data) { // add request parameters before submit
							
							
							if(data.order[0]['column'] != '' && typeof data.order[0]['column'] !== 'undefined' && data.order[0]['dir'] != '' && typeof data.order[0]['dir'] !== 'undefined'){
								if(data.order[0]['column'] == 0){
									data.orderby = 'pono';
								} else if(data.order[0]['column'] == 1){
									data.orderby = 'po_date';
								} else if(data.order[0]['column'] == 2){
									data.orderby = 'vendorname';
								} else if(data.order[0]['column'] == 5){
									data.orderby = 'duedate';
								} else {
									if(data.order[0]['column'] == 6){
										data.orderby = 'paidstatus';
									}
								}
								var orderin = data.order[0]['dir'];
								data.order = null;
								data.order = orderin;
								
							} else {
								data.orderby = 'paidstatus';
								data.order = null;
								data.order = 'desc';
							}
							
							
							data.action = 'payments';
							data.fetchdata = 'all';
							data.columns = null;
							data.search = null;
							
							data.columns = '1';
							data.search = 'unpaid';
						
							var vendorname = $("input[name='order_customer_name']").val();
							var due_date_from = $("input[name='order_purchase_price_from']").val();
							var due_date_to = $("input[name='order_purchase_price_to']").val();
							var servicedate = $("input[name='order_date_from']").val();
							var paidstatus = $("select[name='order_status']").val();
							var po_no = $("input[name='order_id']").val();
							
							var searchby = '';
							var multipleparameterssearch = 0;
							
							var vendorArr = [];
							$('div#vendordata').find('option:selected').each(function () {
								vendorArr.push($(this).val());
							});
							
							if(vendorArr.length > 0){
								//data.vendorname = vendorname;
								data.vendorname = vendorArr.join()
								searchby = 'vendorname';
								multipleparameterssearch += 1;
							}
							
							if(po_no != '' && typeof po_no !== 'undefined'){
								data.order_id = po_no;
								searchby = 'po_no';
								multipleparameterssearch += 1;
							}

							if(due_date_from != '' && typeof due_date_from !== 'undefined'){
								
								data.due_date_from = due_date_from;
								searchby = 'duedate';
								multipleparameterssearch += 1;
								if(due_date_to != '' && typeof due_date_to !== 'undefined'){
									data.due_date_to = due_date_to;
									multipleparameterssearch += 1;
								} 
							} 
							
							if(servicedate != '' && typeof servicedate !== 'undefined'){
								data.servicedate = servicedate;
								searchby = 'servicedate';
								multipleparameterssearch += 1;
							}
							
							
							var paidstatusArr = [];
							$('div#statusdata').find('option:selected').each(function () {
								paidstatusArr.push($(this).val());
							});
							//console.log(paidstatusArr);
							if(paidstatusArr.length > 0){
								//data.vendorname = vendorname;
								data.paidstatus = paidstatusArr.join()
								searchby = 'paidstatus';
								multipleparameterssearch += 1;
							}
							
							var orderamountArr = [];
							$('div#orderdata').find('option:selected').each(function () {
								orderamountArr.push($(this).val());
							});
							
							if(orderamountArr.length > 0){
								data.orderamount = orderamountArr.join()
								searchby = 'orderamount';
								multipleparameterssearch += 1;
							}

							
							if(multipleparameterssearch > 1 ){
								data.searchby = 'all';
							} else {
								if(searchby != ''){
									data.searchby = searchby;
								}
							}

							JSON.stringify(data);
                      },
                  
                    "dataType":"json",
                    "headers": { 'x-taste-request-timestamp':Math.floor((new Date().getTime()/1000)),'x-taste-access-token':localStorage.getItem('access_token')},
                  
                },
            }
        });

        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            } else if (action.val() == "") {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });*/
        
        $('#button#vendorid').on('click', '.table-group-action-submit', function (e) {
			console.log('her');
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
			//$http.defaults.headers.common['Content-type'] ='application/json';
			$http.post($scope.apppath+'/api/getunpaidpo',{poid:poid,action:'makevendorpayment',amount:paymentamount}).
				success(function(data, status, headers, config) {
					//console.log(data);
					//console.log(data.status_code);
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
		ComponentsPickers.init();
		  
    });
    
    $(document).on("click", ".sorting_1", function() {
		/*console.log('comes here');
		var currentstatus = $(this).parent().prop('className');
		console.log(currentstatus);
		if(currentstatus == 'priority-yellow'){
			var priority = 'low';
		} else if(currentstatus == 'priority-orange'){
			var priority = 'medium';
		} else if(currentstatus == 'priority-red') {
			var priority = 'high';
		} else {
			var priority = 'hold';
		}*/
		var priority = $(this).find("input[name='prioritystatus']").val();
		var orderno = $(this).find("input[name='orderno']").val();
		bootbox.dialog({
			title:'Change Priority',
			message: $('#changepriority'),
			show: false,
			animate:true,
			closeButton: false,
			className:'changepriority',
			buttons: {
			  danger: {
				label: "Cancel",
				className: "cancel-btn",
				callback: function() {
					bootbox.hideAll();	
				}
			  },
			  success: {
				label: "Update",
				className: "main-btn",
				callback: function() {
					//console.log('priority status');
					//$('#changepriority').find('#changeprioritystatus').click();
					//return false;
					var priority = $('#changepriority').find('input#priority_status').val();
					var po_no = $('#changepriority').find('input#po_no').val();
					$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
					$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
					$http.post($scope.apppath+'/api/getunpaidpo',{action:'updatepriority',priority:priority,poid:po_no}).
					success(function(data, status, headers, config) {
						if(data.status_code == 200){
							if(data.updated == 1 && typeof data.updated != 'undefined'){
								$('table#sample_2').find('tr').find('td').each(function(){
									if($(this).find("input[name='orderno']").val() === po_no){
										$(this).find("input[name='prioritystatus']").val(priority);
										if(priority == 'low'){
											var prioritycolor = 'priority-yellow';
										} else if(priority == 'low') {
											var prioritycolor = 'priority-orange';
										} else if() {
											var prioritycolor = 'priority-red';
										} else {
											var prioritycolor = 'priority-green';
										}
										$(this).parent('tr').addClass(prioritycolor);
									}
								});
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
			$('#changepriority').show(); 
			//$('#changepriority').validate().resetForm(); 	
				
		})
		.on('hide.bs.modal', function(e) {
			$('#changepriority').hide().appendTo('body');
		})
		.modal('show');
		//$('#changepriority').find('label#'+priority).css('display','none');
		$('#changepriority').find('label').find('span').each(function(){
			if($(this).hasClass('checked')){
				$(this).removeClass('checked');
			}
		});
		$('#changepriority').find('label#'+priority).find('span').addClass('checked');
		$('#changepriority').find('label#'+priority).find('input#'+priority).prop('checked',true);
		$('#changepriority').find('input#po_no').val(orderno);
		$('#changepriority').find('input#priority_status').val(priority);
		//$('#changepriority').find('input#'+priority).css('display','none');
	});
    
    $(document).on("click", ".makepayment", function() {
		var paymentamount = $(this).attr('data-payment-amount');
		var poid =  $(this).attr('id');
	
		//console.log('paymentamount'+paymentamount+'vendorid'+vendorid);
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
											if(data.payeeaddress !== '' && typeof data.payeeaddress !== 'undefined'){
												$('#manualAccount').find('input#mailingaddress').val(data.payeeaddress);
											}
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
	
    
    $(document).on("click", ".requestinfo", function() {
		var vendorid = $(this).attr('data-request-id');
		var actiontype =  $(this).attr('id');
	
		//console.log($(this).attr('data-request-id'));
		if(vendorid != ''){
				if(actiontype == 'updateinfo'){
							bootbox.dialog({
							message: "Do you want to send update info link to vendor ?",
							title: "Update bank info confirmation",
							size: 'small',
							className:'requestlinkconfirmation',
							buttons: {
							  danger: {
								label: "Cancel",
								className: "cancel-btn",
								callback: function() {
									bootbox.hideAll();	
								}
							  },
							  success: {
								label: "Continue",
								className: "main-btn",
								callback: function() {
									$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
									$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
									$http.post($scope.apppath+'/api/getunpaidpo',{vendorid:vendorid,action:'sendrequesterlink',actiontype:'updateinfo'}).
										success(function(data, status, headers, config) {
											if(data.status_code == 200){
												bootbox.hideAll();	
											} else {
												createauthtoken(sendrequestlink);
											}
									});
								}
							  }
							}
					 });
					 
				} else {
					bootbox.dialog({
							message: "Do you want to send request info link to vendor ?",
							title: "Request bank info confirmation",
							size: 'small',
							className:'requestlinkconfirmation',
							buttons: {
							  danger: {
								label: "Cancel",
								className: "cancel-btn",
								callback: function() {
									bootbox.hideAll();	
								}
							  },
							  success: {
								label: "Continue",
								className: "main-btn",
								callback: function() {
									$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
									$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
									$http.post($scope.apppath+'/api/getunpaidpo',{vendorid:vendorid,action:'sendrequesterlink',actiontype:'requestinfo'}).
										success(function(data, status, headers, config) {
											if(data.status_code == 200){
												bootbox.hideAll();	
											} else {
												createauthtoken(sendrequestlink);
											}
									});
								}
							  }
							}
					 });
	
				}
				
			}
	});
    
  
 /* $scope.sendrequestlink = function(vendorid) {
		 alert('here');
			if(vendorid != ''){
				$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
				$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
				$http.post($scope.apppath+'/api/getunpaidpo',{vendorid:vendorid,action:'sendrequesterlink'}).
					success(function(data, status, headers, config) {
						if(data.status_code == 200){
							if(data.vendorpodetails != ''){
								$scope.vendorpodetails = data.vendorpodetails;
							}	
						} else {
							createauthtoken(sendrequestlink);
						}
				});
			}
	}*/
	
	$scope.priorityChosen = function(value) {
		$('div.changepriority').find('input#priority_status').val(value);
	}

   
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
