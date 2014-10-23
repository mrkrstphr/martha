'use strict';

var marthaApp = angular.module('marthaApp', [
    'ngRoute',
    'marthaControllers'
]);

marthaApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'partials/dashboard.html',
                controller: 'DashboardController'
            }).
            when('/builds/:id', {
                templateUrl: 'partials/builds/view.html',
                controller: 'BuildDetailController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

var marthaControllers = angular.module('marthaControllers', []);

marthaControllers.controller('DashboardController', ['$scope',
    function($scope) {
        $scope.builds = [
            {id: 1001, method: 'GitHub Web Hook', status: 'success', created: '2014-10-04T08:36:44', project: {id: 10, name: 'uss-domain'}}
        ];
    }
]);

marthaControllers.controller('BuildDetailController', ['$scope', '$routeParams',
    function($scope, $routeParams) {
        $scope.build = {
            id: 1001,
            method: 'GitHub Web Hook',
            status: 'success',
            created: '2014-10-04T08:36:44',
            project: {
                id: 10,
                name: 'uss-domain'
            }
        };
    }
]);

marthaApp.directive('timeago', function() {
    return {
        restrict:'A',
        link: function(scope, element, attrs) {
            attrs.$observe('timeago', function() {
                element.html(
                    $('<time>')
                        .attr('datetime', attrs.timeago)
                        .text(moment(attrs.timeago).fromNow())
                ).attr('title', moment(attrs.timeago).format('llll'))
            });
        }
    };
});

marthaApp.directive('statusBadge', function () {
    return {
        restrict:'A',
        link: function(scope, element, attrs) {
            attrs.$observe('statusBadge', function() {
                element
                    .text(ucfirst(attrs.statusBadge))
                    .addClass('label label-' + attrs.statusBadge);
            });
        }
    };
});

var ucfirst = function(string) {
    return string.charAt(0).toUpperCase() + string.substr(1);
};
