var controllers = angular.module('settings', ['$strap.directives']);

controllers.controller('settingsController', ['$scope',
    function($scope) {
        $scope.users = [
            {
                id: 1,
                fullName: 'Kristopher Wilson',
                email: 'kristopherwilson@gmail.com'
            },
            {
                id: 2,
                fullName: 'Marshall Moo',
                email: 'marshall.moo@gmail.com'
            }
        ];

        $scope.installed_plugins = [
            {
                title: 'A Sample Plugin',
                description: 'This plugin provides the ability to kick some ass.',
                version: '2.4.16',
                author: 'Kristopher Wilson',
                author_url: 'https://github.com/mrkrstphr',
                is_enabled: true,
                is_update_available: false
            },
            {
                title: 'Another Kind of Plugin',
                description: 'This plugin kind sucks; nobody really thought this through...',
                version: '3.8',
                author: 'Billy Bob',
                author_url: 'aol.com',
                is_enabled: false,
                is_update_available: true
            }
        ];

        $scope.selectedPill = 1;
    }]
);
