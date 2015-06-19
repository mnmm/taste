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
								invalidHandler: function (event) { //display error alert on form submit              
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

								submitHandler: function () {
										
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
									entries: {
										required: true,
										number:true
									},
									emailcontact:{
										email: true
									},
									tnc: {
										required: true
									}
								},
								invalidHandler: function (event) { //display error alert on form submit              
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

								submitHandler: function () {
										$('button#createAccount').click();
									}
								});
	}
	
	 var handleClockfaceTimePickers = function () {

			if (!jQuery().clockface) {
				return;
			}

			$('.clockface_1').clockface();
			$('.clockface_2').clockface();
			
			

	}
	

	return {
        //main function to initiate the module
        init: function () {
            handleLoginValidation();
            handleRegisterValidation();
            handleClockfaceTimePickers();
        }

    };
}();

/*var ComponentsPickers = function () {
	 var handleClockfaceTimePickers = function () {

			if (!jQuery().clockface) {
				return;
			}

			$('.clockface_1').clockface();
			$('.clockface_2').clockface();
			
			

		}
		
    return {
        //main function to initiate the module
        init: function () {
          handleClockfaceTimePickers();
        }
    };

}();*/

  
MetronicApp.controller('LoginController', function($rootScope, $scope, $http, $timeout, $location,$window ,authenticationSvc) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	FormValidation.init();
	var authtoken = localStorage.getItem('access_token');
	$scope.timestamp = Math.floor((new Date().getTime()/1000));
	$scope.login = function(){authenticationSvc.login($scope.email,$scope.password,'vendors');}
	function callAtTimeout() {
		$('.daysopen').SumoSelect({selectAll:true,csvDispCount:3,selectAlltext:'Select All'});
		$('.locationtype').SumoSelect({selectAll:false,csvDispCount:3});
		
	}
	$timeout(function () {
		 $timeout(callAtTimeout, 1000);
	});
	$scope.createAccount = function() {
		$('.login-form').hide();
		$('.register-form').show();
		$('.login').find('.content').css('width','800px');
	}
	
	$scope.showLogin = function() {
		$('.login-form').show();
		$('.register-form').hide();
		$('.login').find('.content').css('width','400px');
	}
	
	
	$scope.SignUp = function() {
		
		var locationArr = [];
		var locationArrval = {0:'restaurant',1:'caterer',2:'foodtruck',3:'bakery',4:'other'};
		var daysArrval = {0:'monday',1:'tuesday',2:'webnesday',3:'thursday',4:'friday',5:'saturday',6:'sunday'}; 
		$('div#locationdata').find('option:selected').each(function () {
			if($(this).val() !== 4){
				var locval = $(this).val();
				//console.log('locvalAr'+locationArrval[0]);
				locationArr.push(locationArrval[locval]);
			} else {
				var locationotherval = $('input#locationtypeother').val();
				//console.log('locationotherval'+locationotherval);
				if(locationotherval !== '' && typeof locationotherval !== 'undefined'){
					locationArr.push(locationotherval);
				}
			}
		});
		var locationtype = '';
		if(locationArr.length > 0){
			locationtype = locationArr.join();
		}
		
		//console.log('locationtype'+locationtype);
		var daysArr = [];
		$('div#daysopendata').find('option:selected').each(function () {
				var dayval = $(this).val();
				console.log('locvalAr'+daysArrval[dayval]);
				daysArr.push(daysArrval[dayval]);
		});
		var opendays = '';
		if(daysArr.length > 0){
			opendays = daysArr.join();
		}
		
		$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =authtoken;
		$http.post($scope.apppath+'/api/getunpaidpo',{fullname:$scope.fullname,email_address:$scope.email_address,password:$scope.register_password,address:$scope.address,city:$scope.city,state:$scope.state,zip:$scope.zip,phone:$scope.phone,location:$scope.location,address2:$scope.address2,crossstreet:$scope.crossstreet,neighborhood:$scope.neighborhood,entries:$scope.entries,daysopen:opendays,businessopeninghours:$('input#businessopeninghour').val(),businessclosinghours:$('input#businessclosinghour').val(),locationdescription:$scope.locationdescription,locationtype:locationtype,locationnotes:$scope.locationnotes,mealnotes:$scope.mealnotes,emailcontact:$scope.emailcontact,contactphone:$scope.contactphone,action:'createvendoraccount'}).
			success(function(data) {
				if(data.status_code === 200){
					var subject = 'registered as new vendor';
						var message = $scope.fullname+' has registred as new vendor with email '+$scope.email_address;
						$http.post($scope.apppath+'/api/getunpaidpo',{subject:subject,message:message,userid:1,action:'saveadminnotes'}).
						success(function(data) {
							if(data.status_code === 200){
								$('form#registerForm')[0].reset();
								var sucess2 = $('#registerformsucess');
								$('#registerForm').find('#registerformsucess').show();
								Metronic.scrollTo(sucess2, -200);
								$('#registerformalert').hide();
							}
						});
				} else {
					if(data.status_code === 201){
						if(data.accountcreated ===  0){
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
	
	function createauthtoken(){
			
		$http.post($scope.apppath+'/create_auth_token', {api_key:'1-Z9QSD6E6QJNDYTPBUD8XEX8',api_secret:'N-9OXFMLDXLXB7N2IXXOQR85XFV5V7QKGR_',timestamp:$scope.timestamp}). 
				success(function(data) {
					if(data.status_code === 200 ){
						localStorage.setItem('access_token',data.access_token);
					}
		});			
	}
	
	function checktokenauthentication(authtoken){
		$http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =authtoken;
		$http.post($scope.apppath+'/api/checktokenauthentication').
			success(function(data) {
				if(data.status_code === 200){
					
				} else {
					createauthtoken();
				}
		});
	}
	
	$scope.blisterPackTemplates=[{resnameid:'restaurant',name:"Restaurant"},{resnameid:'caterer',name:"Caterer"},{resnameid:'foodtruck',name:"Food Truck"},{resnameid:'bakery',name:"Bakery"},{resnameid:'other',name:"Other"}]
	
	$scope.blisterPackTemplatess=[{daynameid:'monday',dayname:"Monday"},{daynameid:'tuesday',dayname:"Tuesday"},{daynameid:'wednesday',dayname:"Wednesday"},{daynameid:'thursday',dayname:"Thursday"},{daynameid:'friday',dayname:"Friday"},{daynameid:'saturday',dayname:"Saturday"},{daynameid:'sunday',dayname:"Sunday"}]
	
	$scope.changedValue=function(item){
		
		var otherexists = 0;
		
		$.each( item, function( key, value ) {
			
			if(value === 'other'){
				otherexists = 1;
			}
		});
		if(otherexists === 1){
			$('#locationtypeother').css('display','inline-block');
		} else {
			$('#locationtypeother').css('display','none');
		}
    }  
    
    $scope.changedDay=function(item){
		
		/*var otherexists = 0;
		$.each( item, function( key, value ) {
			if(value.id == 'other'){
				otherexists = 1;
			}
		});
		if(otherexists == 1){
			$('#locationtypeother').css('display','inline-block');
		} else {
			$('#locationtypeother').css('display','none');
		}*/
    }   

	if(authtoken !== '' && typeof authtoken !== 'undefined' && authtoken !== null){
		console.log('authtoken'+authtoken);
		checktokenauthentication(authtoken);
	} else {
		createauthtoken();
	}
	
	//ComponentsPickers.init();
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
    $rootScope.settings.layout.showAllOptions = true;
    
}); 


