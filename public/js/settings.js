angular.module('settings', ['$strap.directives']);

var settingsController = function ($scope, $window, $location) {
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
};
