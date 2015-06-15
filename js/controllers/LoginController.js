'use strict';

 

MetronicApp.controller('LoginController', function($rootScope, $scope, $http, $timeout, $location,$window ,authenticationSvc) {
	$scope.apppath= 'https://mnmdesignlabs.com/taste';
	$scope.login = function(){authenticationSvc.login($scope.email,$scope.password,'vendors');}
	 
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
