'use strict';

var marthaApp = angular.module('marthaApp', [
    'marthaControllers',
    'marthaServices'
]);

var marthaServices = angular.module('marthaServices', ['ngResource']);

marthaServices.factory('User', ['$resource',
    function($resource){
        return $resource('/users/:id', {}, {
            query: {method:'GET', params:{id:''}, isArray:true}
        });
    }]
);
