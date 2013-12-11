'use strict';

var marthaApp = angular.module('marthaApp', [
    'marthaControllers',
    'marthaServices'
]);

var marthaServices = angular.module('marthaServices', ['ngResource']);

marthaServices.factory('Plugin', ['$resource',
    function($resource){
        return $resource('/api/plugins/:id', {}, {
            query: {method:'GET', params:{id:''}, isArray:true}
        });
    }]
);

marthaServices.factory('AvailablePlugin', ['$resource',
    function($resource){
        return $resource('/api/available-plugins/:id', {}, {
            query: {method:'GET', params:{id:''}, isArray:true}
        });
    }]
);

marthaServices.factory('User', ['$resource',
    function($resource){
        return $resource('/api/users/:id', {}, {
            query: {method:'GET', params:{id:''}, isArray:true}
        });
    }]
);
