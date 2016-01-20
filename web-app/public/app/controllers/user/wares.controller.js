'use strict';

angular.module('app')
.controller('UserWaresController', function(layout, auth) 
{
    var vm = Object.assign(this, 
    {

    });
    (function run()
    {
        layout.init({
            section : {title : 'Your wares'},
            breadcrumb : {title: 'your wares'},
            page : {className :'page-user-wares'},
            pagination : {
                active : true,
                sortBy : 'starts',
                sortOrder : 'asc',
                resourceUrl : '/user/' + auth.getUser().id + '/ware',
            }
        });
        layout.pagination.fetchItems();
    })();
});
