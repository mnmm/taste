/***
Metronic AngularJS App Main Script
***/
var appPath = 'https://mnmdesignlabs.com/taste';
/* Metronic App */
var MetronicApp = angular.module("MetronicApp", [
    "ui.router", 
    "ui.bootstrap", 
    "oc.lazyLoad",  
    "ngSanitize"
]).service('Session', function () {
  this.create = function (userId, userRole,userName) {
    this.userId = userId;
    this.userRole = userRole;
    this.userName = userName
  };
  this.destroy = function () {
    this.userId = null;
    this.userRole = null;
    this.userName = null;
  };
})

.constant('AUTH_EVENTS', {
  loginSuccess: 'auth-login-success',
  loginFailed: 'auth-login-failed',
  logoutSuccess: 'auth-logout-success',
  sessionTimeout: 'auth-session-timeout',
  notAuthenticated: 'auth-not-authenticated',
  notAuthorized: 'auth-not-authorized'
})
.constant('USER_ROLES', {
  all: '*',
  admin: 'admin',
  vendor: 'vendor',
  guest: 'guest'
})



/* Configure ocLazyLoader(refer: https://github.com/ocombe/ocLazyLoad) */
MetronicApp.config(['$ocLazyLoadProvider','$locationProvider', function($ocLazyLoadProvider,$locationProvider) {
    $ocLazyLoadProvider.config({
        // global configs go here
       
    });
}]);

/********************************************
 BEGIN: BREAKING CHANGE in AngularJS v1.3.x:
*********************************************/
/**
`$controller` will no longer look for controllers on `window`.
The old behavior of looking on `window` for controllers was originally intended
for use in examples, demos, and toy apps. We found that allowing global controller
functions encouraged poor practices, so we resolved to disable this behavior by
default.

To migrate, register your controllers with modules rather than exposing them
as globals:

Before:

```javascript
function MyController() {
  // ...
}
```

After:

```javascript
angular.module('myApp', []).controller('MyController', [function() {
  // ...
}]);

Although it's not recommended, you can re-enable the old behavior like this:

```javascript
angular.module('myModule').config(['$controllerProvider', function($controllerProvider) {
  // this option might be handy for migrating old apps, but please don't use it
  // in new ones!
  $controllerProvider.allowGlobals();
}]);
**/

//AngularJS v1.3.x workaround for old style controller declarition in HTML
MetronicApp.config(['$controllerProvider', function($controllerProvider) {
  // this option might be handy for migrating old apps, but please don't use it
  // in new ones!
  $controllerProvider.allowGlobals();
}]);


/********************************************
 END: BREAKING CHANGE in AngularJS v1.3.x:
*********************************************/

/* Setup global settings */
MetronicApp.factory('settings', ['$rootScope', function($rootScope) {
    // supported languages
    var settings = {
        layout: {
            pageSidebarClosed: false, // sidebar menu state
            pageBodySolid: false, // solid body color state
            pageAutoScrollOnLoad: 1000 // auto scroll to top on page load
        },
        layoutImgPath: Metronic.getAssetsPath() + 'admin/layout/img/',
        layoutCssPath: Metronic.getAssetsPath() + 'admin/layout/css/'
    };

    $rootScope.settings = settings;

    return settings;
}]);

MetronicApp.factory('AuthService', function ($http, Session) {
  var authService = {};
 
  authService.isAuthenticated = function () {
    return !!localStorage.getItem("userid");
  };
  authService.isAuthorized = function (authorizedRoles) {
	
	var currentrole = '';
	currentrole = localStorage.getItem("role");
	
    var a = authorizedRoles.indexOf(currentrole);
 
    console.log(authService.isAuthenticated() && authorizedRoles.indexOf(currentrole) !== -1);
    return (authService.isAuthenticated() && authorizedRoles.indexOf(currentrole) !== -1);
  };
 
  return authService;
})

MetronicApp.factory("SideBarService", function() {

  return {
    changeSettingStatusCommon: function() {
		console.log('clicked');
		
    },
    first: function() {
      return users[0];
    }
  };
})

/*MetronicApp.factory('AuthResolver', function ($q, $rootScope, $state) {
  return {
    resolve: function () {
      var deferred = $q.defer();
     
      var unwatch = $rootScope.$watch('Session', function (Session) {
        if (angular.isDefined(Session)) {
          if (Session) {
            deferred.resolve(Session);
          } else {
            deferred.reject();
            $state.go('dashboard');
          }
          unwatch();
        }
      });
      return deferred.promise;
    }
  };
})*/

/* Setup App Main Controller */
MetronicApp.controller('AppController', ['$scope', '$rootScope', function($scope, $rootScope) {
    $scope.$on('$viewContentLoaded', function() {
        Metronic.initComponents(); // init core components
        //Layout.init(); //  Init entire layout(header, footer, sidebar, etc) on page load if the partials included in server side instead of loading with ng-include directive 
        $scope.apppath = 'https://mnmdesignlabs.com/taste';
    });
}]);

/***
Layout Partials.
By default the partials are loaded through AngularJS ng-include directive. In case they loaded in server side(e.g: PHP include function) then below partial 
initialization can be disabled and Layout.init() should be called on page load complete as explained above.
***/
/*
var onlyLoggedIn = function ($location,$q,$window) {
    var deferred = $q.defer();
    if (localStorage.getItem('vendor_access_token') != '') {
        deferred.resolve();
    } else {
        deferred.reject();
        $window.location.href = '#/vendors'; 
    }
    return deferred.promise;
};*/


/*MetronicApp.run(function ($rootScope) {

  $rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
	  
    if (!AuthService.isAuthorized(authorizedRoles)) {
			event.preventDefault();
      if (AuthService.isAuthenticated()) {
			// user is not allowed
			$rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
      } else {
			// user is not logged in
			$rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
      }
    }
  });

});*/
MetronicApp.run(function ($rootScope, AUTH_EVENTS, AuthService,Session) {
  $rootScope.$on('$stateChangeStart', function (event, next) {
	 
   var authorizedRoles = next.data.authorizedRoles;
   
   if(authorizedRoles != '' && typeof authorizedRoles != 'undefined'){
   //console.log('authorizedRoles'+authorizedRoles);
		if (!AuthService.isAuthorized(authorizedRoles)) {
		  if (AuthService.isAuthenticated()) {
		
				// user is not allowed
				$rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
		  } else {
			  
				// user is not logged in
				$rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
		  }
		}
	} else {
		localStorage.setItem("userid",1);
		localStorage.setItem("role","admin");
		localStorage.setItem("name","admin");
		//Session.create('1','all','guest');
	}
  });
})

/* Setup Layout Part - Header */
MetronicApp.controller('HeaderController', ['$scope', function($scope) {
    $scope.$on('$includeContentLoaded', function() {
        Layout.initHeader(); // init header
        $scope.apppath = 'https://mnmdesignlabs.com/taste';
    });
}]);

/* Setup Layout Part - Sidebar */
MetronicApp.controller('SidebarController', ['$scope','$http', function($scope, $http) {
    $scope.$on('$includeContentLoaded', function() {
        Layout.initSidebar(); // init sidebar
        $scope.apppath = 'https://mnmdesignlabs.com/taste';
       
        $http.defaults.headers.common['x-taste-request-timestamp'] = Math.floor((new Date().getTime()/1000));
		$http.defaults.headers.common['x-taste-access-token'] =localStorage.getItem('access_token');
		$http.post($scope.apppath+'/api/getunpaidpo',{action:'getsettingstatus'}).
		success(function(data, status, headers, config) {
			
			if(data.status_code == 200){
				if(data.currentstatus != '' && typeof data.currentstatus != 'undefined'){
					if(data.currentstatus == 0){
						$("#checkwrapper .on").removeClass('active');
						$("#checkwrapper .off").addClass('active');
						$("#checkwrapper").attr('openstatus','0');
						$('span#test').addClass('bright-text');
					} else {
						$("#checkwrapper .on").addClass('active');
						$("#checkwrapper .off").removeClass('active');
						$("#checkwrapper").attr('openstatus','1');
						$('span#live').addClass('bright-text');
					}
				}  
			} else {

			}		
		});

    });
    
   
			
    $scope.changeSettingStatus = function(){
		var status=	$("#checkwrapper").attr('openstatus');
		if(status == 0){
			
			$("#checkwrapper .on").addClass('active');
			$("#checkwrapper .off").removeClass('active');
			$("#checkwrapper").attr('openstatus','1');
			$('span#live').addClass('bright-text');
			
		} else {
			
			$("#checkwrapper .on").removeClass('active');
			$("#checkwrapper .off").addClass('active');
			$("#checkwrapper").attr('openstatus','0');
			$('span#test').addClass('bright-text');
			
		}
		
		$http.post($scope.apppath+'/api/getunpaidpo',{action:'changesettingstatus',status:status}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200 ){
					
				} else {
					if(status == 0){
						$("#checkwrapper .on").removeClass('active');
						$("#checkwrapper .off").addClass('active');
						$("#checkwrapper").attr('openstatus','0');
						$('span#live').removeClass('bright-text');
						$('span#test').addClass('bright-text');
					} else {
						$("#checkwrapper .on").addClass('active');
						$("#checkwrapper .off").removeClass('active');
						$("#checkwrapper").attr('openstatus','1');
						$('span#test').removeClass('bright-text');
						$('span#live').addClass('bright-text');
					}
				}
		});
	}
}]);

/* Setup Layout Part - Quick Sidebar */
MetronicApp.controller('QuickSidebarController', ['$scope', function($scope) {    
    $scope.$on('$includeContentLoaded', function() {
        setTimeout(function(){
            QuickSidebar.init(); // init quick sidebar  
            $scope.apppath = 'https://mnmdesignlabs.com/taste';      
        }, 2000)
    });
}]);

/* Setup Layout Part - Theme Panel */
MetronicApp.controller('ThemePanelController', ['$scope', function($scope) {    
    $scope.$on('$includeContentLoaded', function() {
		
        Demo.init(); // init theme panel
        $scope.apppath = 'https://mnmdesignlabs.com/taste';
    });
}]);

/* Setup Layout Part - Footer */
MetronicApp.controller('FooterController', ['$scope', function($scope) {
    $scope.$on('$includeContentLoaded', function() {
		
        Layout.initFooter(); // init footer
        $scope.apppath = 'https://mnmdesignlabs.com/taste';
    });
}]);

/*$scope.changeSettingStatus = function() {
		var status=	$("#checkwrapper").attr('openstatus');
		if(status == 0){
			
			$("#checkwrapper .on").addClass('active');
			$("#checkwrapper .off").removeClass('active');
			$("#checkwrapper").attr('openstatus','1');
			
		} else {
			
			$("#checkwrapper .on").removeClass('active');
			$("#checkwrapper .off").addClass('active');
			$("#checkwrapper").attr('openstatus','0');
			
		}
		
		$http.post($scope.apppath+'/api/getunpaidpo',{action:'changesettingstatus',status:status}).
			success(function(data, status, headers, config) {
				if(data.status_code == 200 ){
					
				} else {
					if(status == 0){
						$("#checkwrapper .on").removeClass('active');
						$("#checkwrapper .off").addClass('active');
						$("#checkwrapper").attr('openstatus','0');
					} else {
						$("#checkwrapper .on").addClass('active');
						$("#checkwrapper .off").removeClass('active');
						$("#checkwrapper").attr('openstatus','1');
					}
				}
		});
}*/

/* Setup Rounting For All Pages */
MetronicApp.config(['$stateProvider', '$urlRouterProvider','USER_ROLES', function($stateProvider, $urlRouterProvider,USER_ROLES) {
    // Redirect any unmatched url
    $urlRouterProvider.otherwise("/dashboard.html");  
	
    $stateProvider

        // Dashboard
        .state('dashboard', {
            url: "/dashboard.html",
            templateUrl: "views/dashboard.html",            
            data: {pageTitle: 'Admin Dashboard Template',appPath:'https://mnmdesignlabs.com/taste', authorizedRoles: ['admin']},
            controller: "DashboardController",
            resolve: {
				
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files
                        files: [
                            appPath+'/assets/global/plugins/morris/morris.css',
                            appPath+'/assets/admin/pages/css/tasks.css',
                            
                            appPath+'/assets/global/plugins/morris/morris.min.js',
                            appPath+'/assets/global/plugins/morris/raphael-min.js',
                            appPath+'/assets/global/plugins/jquery.sparkline.min.js',

                            appPath+'/assets/admin/pages/scripts/index3.js',
                            appPath+'/assets/admin/pages/scripts/tasks.js',

                           appPath+'/'+'js/controllers/DashboardController.js'
                        ] 
                    });
                }]
            }
        })
        
         // Admin Settings
        .state("settings", {
            url: "/settings",
            templateUrl: "views/settings.html",
            data: {pageTitle: 'Vendor Settings',appPath:'https://mnmdesignlabs.com/taste', authorizedRoles: ['vendor','admin','all']},
            controller: "SettingsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [

                              appPath+'/assets/global/plugins/select2/select2.css',                             
                              appPath+'/assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
                              appPath+'/assets/global/plugins/jquery-validation/js/additional-methods.js',    
                              appPath+'/assets/global/plugins/select2/select2.min.js',
                              appPath+'/assets/global/plugins/bootbox/bootbox.min.js',
                              appPath+'/assets/admin/pages/scripts/ui-alert-dialog-api.js',
                              appPath+'/js/controllers/SettingsController.js'
                              
                              
                        ]                    
                    });
                }]
            }
        })

        
         // Todo
        /*.state('payments', {
            url: "/payments",
            templateUrl: "views/payments.html",
            data: {pageTitle: 'Payments',appPath:'http://localhost/laravel'},
            controller: "PaymentsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({ 
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            appPath+'/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css',
                            appPath+'/assets/global/plugins/select2/select2.css',
                            appPath+'/assets/admin/pages/css/todo.css',
                            
                            appPath+'/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                            appPath+'/assets/global/plugins/select2/select2.min.js',

                            appPath+'/assets/admin/pages/scripts/payments.js',

                            appPath+'/js/controllers/PaymentsController.js'  
                        ]                    
                    });
                }]
            }
        })*/

        // AngularJS plugins
        .state('fileupload', {
            url: "/file_upload.html",
            templateUrl: "views/file_upload.html",
            data: {pageTitle: 'AngularJS File Upload'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load([{
                        name: 'angularFileUpload',
                        files: [
                            '../../../assets/global/plugins/angularjs/plugins/angular-file-upload/angular-file-upload.min.js',
                        ] 
                    }, {
                        name: 'MetronicApp',
                        files: [
                            'js/controllers/GeneralPageController.js'
                        ]
                    }]);
                }]
            }
        })

        // UI Select
        .state('uiselect', {
            url: "/ui_select.html",
            templateUrl: "views/ui_select.html",
            data: {pageTitle: 'AngularJS Ui Select'},
            controller: "UISelectController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load([{
                        name: 'ui.select',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/angularjs/plugins/ui-select/select.min.css',
                            '../../../assets/global/plugins/angularjs/plugins/ui-select/select.min.js'
                        ] 
                    }, {
                        name: 'MetronicApp',
                        files: [
                            'js/controllers/UISelectController.js'
                        ] 
                    }]);
                }]
            }
        })

        // UI Bootstrap
        .state('uibootstrap', {
            url: "/ui_bootstrap.html",
            templateUrl: "views/ui_bootstrap.html",
            data: {pageTitle: 'AngularJS UI Bootstrap'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load([{
                        name: 'MetronicApp',
                        files: [
                            'js/controllers/GeneralPageController.js'
                        ] 
                    }]);
                }] 
            }
        })

        // Tree View
        .state('tree', {
            url: "/tree",
            templateUrl: "views/tree.html",
            data: {pageTitle: 'jQuery Tree View'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load([{
                        name: 'MetronicApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/jstree/dist/themes/default/style.min.css',

                            '../../../assets/global/plugins/jstree/dist/jstree.min.js',
                            '../../../assets/admin/pages/scripts/ui-tree.js',
                            'js/controllers/GeneralPageController.js'
                        ] 
                    }]);
                }] 
            }
        })     

        // Form Tools
        .state('formtools', {
            url: "/form-tools",
            templateUrl: "views/form_tools.html",
            data: {pageTitle: 'Form Tools'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load([{
                        name: 'MetronicApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
                            '../../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
                            '../../../assets/global/plugins/jquery-tags-input/jquery.tagsinput.css',
                            '../../../assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css',
                            '../../../assets/global/plugins/typeahead/typeahead.css',

                            '../../../assets/global/plugins/fuelux/js/spinner.min.js',
                            '../../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
                            '../../../assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
                            '../../../assets/global/plugins/jquery.input-ip-address-control-1.0.min.js',
                            '../../../assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js',
                            '../../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
                            '../../../assets/global/plugins/jquery-tags-input/jquery.tagsinput.min.js',
                            '../../../assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
                            '../../../assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js',
                            '../../../assets/global/plugins/typeahead/handlebars.min.js',
                            '../../../assets/global/plugins/typeahead/typeahead.bundle.min.js',
                            '../../../assets/admin/pages/scripts/components-form-tools.js',

                            'js/controllers/GeneralPageController.js'
                        ] 
                    }]);
                }] 
            }
        })        

        // Date & Time Pickers
        .state('pickers', {
            url: "/pickers",
            templateUrl: "views/pickers.html",
            data: {pageTitle: 'Date & Time Pickers'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load([{
                        name: 'MetronicApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/clockface/css/clockface.css',
                            '../../../assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                            '../../../assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
                            '../../../assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css',
                            '../../../assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css',
                            '../../../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',

                            '../../../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                            '../../../assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
                            '../../../assets/global/plugins/clockface/js/clockface.js',
                            '../../../assets/global/plugins/bootstrap-daterangepicker/moment.min.js',
                            '../../../assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js',
                            '../../../assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js',
                            '../../../assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',

                            '../../../assets/admin/pages/scripts/components-pickers.js',

                            'js/controllers/GeneralPageController.js'
                        ] 
                    }]);
                }] 
            }
        })

        // Custom Dropdowns
        .state('dropdowns', {
            url: "/dropdowns",
            templateUrl: "views/dropdowns.html",
            data: {pageTitle: 'Custom Dropdowns'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load([{
                        name: 'MetronicApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/bootstrap-select/bootstrap-select.min.css',
                            '../../../assets/global/plugins/select2/select2.css',
                            '../../../assets/global/plugins/jquery-multi-select/css/multi-select.css',

                            '../../../assets/global/plugins/bootstrap-select/bootstrap-select.min.js',
                            '../../../assets/global/plugins/select2/select2.min.js',
                            '../../../assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js',

                            '../../../assets/admin/pages/scripts/components-dropdowns.js',

                            'js/controllers/GeneralPageController.js'
                        ] 
                    }]);
                }] 
            }
        }) 

        // Advanced Datatables
        .state('datatablesAdvanced', {
            url: "/datatables/advanced.html",
            templateUrl: "views/datatables/advanced.html",
            data: {pageTitle: 'Advanced Datatables'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/select2/select2.css',                             
                            '../../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css', 
                            '../../../assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css',
                            '../../../assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css',

                            '../../../assets/global/plugins/select2/select2.min.js',
                            '../../../assets/global/plugins/datatables/all.min.js',
                            'js/scripts/table-advanced.js',

                            'js/controllers/GeneralPageController.js'
                        ]
                    });
                }]
            }
        })

        // Ajax Datetables
        .state('datatablesAjax', {
            url: "/datatables/ajax.html",
            templateUrl: "views/datatables/ajax.html",
            data: {pageTitle: 'Ajax Datatables'},
            controller: "GeneralPageController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/select2/select2.css',                             
                            '../../../assets/global/plugins/bootstrap-datepicker/css/datepicker.css',
                            '../../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',

                            '../../../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                            '../../../assets/global/plugins/select2/select2.min.js',
                            '../../../assets/global/plugins/datatables/all.min.js',

                            '../../../assets/global/scripts/datatable.js',
                            'js/scripts/table-ajax.js',

                            'js/controllers/GeneralPageController.js'
                        ]
                    });
                }]
            }
        })
	
        // User Profile
        .state("payments", {
            url: "/payments",
            templateUrl: "views/payments.html",
            data: {pageTitle: 'Payments',appPath:'https://mnmdesignlabs.com/taste', authorizedRoles: ['admin']},
            controller: "PaymentsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [

                             /*appPath+'/assets/global/plugins/select2/select2.css',
                             appPath+'/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
                             appPath+'/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             appPath+'/assets/global/plugins/sumoselect/sumoselect.css',
                            
                             appPath+'/assets/global/plugins/select2/select2.min.js',
                             appPath+'/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js',
                            
                             appPath+'/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                             appPath+'/assets/global/plugins/sumoselect/jquery.sumoselect.js',
                             appPath+'/assets/admin/layout/scripts/demo.js',
							 
							 appPath+'/assets/admin/pages/scripts/ecommerce-orders.js',
							 
							
                             appPath+'/assets/admin/pages/scripts/payments.js',
								
                             appPath+'/js/controllers/PaymentsController.js'*/
                              appPath+'/assets/global/plugins/select2/select2.css',                             
                             appPath+'/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css', 
                             appPath+'/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css',
                             appPath+'/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css',

                             appPath+'/assets/global/plugins/select2/select2.min.js',
                             appPath+'/assets/global/plugins/datatables/all.min.js',
                             appPath+'/assets/global/plugins/bootbox/bootbox.min.js',
                             appPath+'/assets/admin/pages/scripts/ui-alert-dialog-api.js',
                            // appPath+'/js/scripts/table-advanced.js',
							 appPath+'/assets/admin/pages/scripts/payments.js',
                             appPath+'/js/controllers/PaymentsController.js'
                              
                              
                        ]                    
                    });
                }]
            }
        })
        
        //vendor dashboard url
        .state("vendordashboard", {
            url: "/vendors",
            templateUrl: "views/vendor/dashboard.html",
            data: {pageTitle: 'Vendor Account',appPath:'https://mnmdesignlabs.com/taste', authorizedRoles: ['vendor']},
            controller: "VendorDashboardController",
            resolve: {
				/*auth: function resolveAuthentication(AuthResolver) { 
						return AuthResolver.resolve();
				},*/
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                              appPath+'/assets/global/plugins/select2/select2.css',                             
                              appPath+'/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
                              appPath+'/assets/global/plugins/select2/select2.min.js',
                              appPath+'/js/controllers/VendorDashboardController.js'
                        ]                    
                    });
                }]
            }
        })
        
        //vendor dashboard url
        .state("vendorw9form", {
            url: "/vendors/w9form",
            templateUrl: "views/vendor/w9form.html",
            data: {pageTitle: 'Vendor w9Form',appPath:'https://mnmdesignlabs.com/taste', authorizedRoles: ['vendor']},
            controller: "VendorW9FormController",
            resolve: {
				
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                              appPath+'/assets/global/plugins/select2/select2.css',                             
                              appPath+'/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
                              appPath+'/assets/global/plugins/select2/select2.min.js',
                              '//mnmdesignlabs.com/trackmydocs_dev/js/component_js/component_js.js',
                              appPath+'/js/controllers/VendorW9FormController.js'
                        ]                    
                    });
                }]
            }
        })
        
        .state("addbankinfo", {
            url: "/vendors/addbankinfo",
            templateUrl: "views/vendor/addbankinfo.html",
            data: {pageTitle: 'Vendor Bank Account Info',appPath:'https://mnmdesignlabs.com/taste', authorizedRoles: ['vendor']},
            controller: "VendorBankAccountController",
            resolve: {
				
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                              appPath+'/assets/global/plugins/select2/select2.css',  
                              appPath+'/assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
                              appPath+'/assets/global/plugins/jquery-validation/js/additional-methods.js',                           
                              appPath+'/assets/global/plugins/select2/select2.min.js',
                              appPath+'/assets/global/plugins/bootbox/bootbox.min.js',
                              appPath+'/assets/admin/pages/scripts/ui-alert-dialog-api.js',
                              appPath+'/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js',
                             // appPath+'/assets/admin/pages/scripts/form-validation.js',
                              appPath+'/js/controllers/VendorBankAccountController.js'
                        ]                    
                    });
                }]
            }
        })
        
         // User Profile Dashboard
        .state("createaccount", {
            url: "/vendor/:param",
            templateUrl: "views/vendor/createaccount.html",
            data: {pageTitle: 'User Account',appPath:'https://mnmdesignlabs.com/taste', authorizedRoles: ['vendor','admin','all']},
            controller: "AccountController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [

                              appPath+'/assets/global/plugins/select2/select2.css',                             
                              appPath+'/assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
                              appPath+'/assets/global/plugins/jquery-validation/js/additional-methods.js',    
                              appPath+'/assets/global/plugins/select2/select2.min.js',
                              appPath+'/assets/global/plugins/bootbox/bootbox.min.js',
                              appPath+'/assets/admin/pages/scripts/ui-alert-dialog-api.js',
                              appPath+'/js/controllers/AccountController.js'
                              
                              
                        ]                    
                    });
                }]
            }
        })
        
       
        // User Profile Dashboard
        .state("profile.dashboard", {
            url: "/dashboard",
            templateUrl: "views/profile/dashboard.html",
            data: {pageTitle: 'User Profile'}
        })

        // User Profile Account
        .state("profile.account", {
            url: "/account",
            templateUrl: "views/profile/account.html",
            data: {pageTitle: 'User Account'}
        })

        // User Profile Help
        .state("profile.help", {
            url: "/help",
            templateUrl: "views/profile/help.html",
            data: {pageTitle: 'User Help'}      
        })

        // Todo
        .state('todo', {
            url: "/todo",
            templateUrl: "views/todo.html",
            data: {pageTitle: 'Todo'},
            controller: "TodoController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({ 
                        name: 'MetronicApp',  
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            '../../../assets/global/plugins/bootstrap-datepicker/css/datepicker3.css',
                            '../../../assets/global/plugins/select2/select2.css',
                            '../../../assets/admin/pages/css/todo.css',
                            
                            '../../../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                            '../../../assets/global/plugins/select2/select2.min.js',

                            '../../../assets/admin/pages/scripts/todo.js',

                            'js/controllers/TodoController.js'  
                        ]                    
                    });
                }]
            }
        })

}]);

/* Init global settings and run the app */
MetronicApp.run(["$rootScope", "settings", "$state", function($rootScope, settings, $state) {
    $rootScope.$state = $state; // state to be accessed from view
}]);
