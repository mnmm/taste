'use strict';

 

MetronicApp.controller('LoginController', function($rootScope, $scope, $http, $timeout, $location,$window ,AUTH_EVENTS,Session,AuthService) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.logout = function(){authenticationSvc.login($scope.email,$scope.password);}
	 
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
