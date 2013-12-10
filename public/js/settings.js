'use strict';

var controllers = angular.module('marthaControllers', ['$strap.directives']);

controllers.controller('settingsController', ['$scope', 'User', 'Plugin',
    function($scope, User, Plugin) {
        $scope.users = User.query();
        $scope.installed_plugins = Plugin.query();

        $scope.selectedPill = 1;
    }]
);
