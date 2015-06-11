'use strict';

 

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
		 
    });
		
	 
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
