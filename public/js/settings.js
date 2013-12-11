'use strict';

var controllers = angular.module('marthaControllers', ['$strap.directives']);

controllers.controller('settingsController', ['$scope', 'User', 'Plugin', 'AvailablePlugin',
    function($scope, User, Plugin, AvailablePlugin) {
        $scope.users = User.query();
        $scope.installed_plugins = Plugin.query();
        $scope.available_plugins = AvailablePlugin.query();

        $scope.selectedPill = 1;
    }]
);
