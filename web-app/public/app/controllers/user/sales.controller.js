'use strict';

angular.module('app')
.controller('UserSalesController', function(layout, auth) 
{
    var vm = Object.assign(this, 
    {

    });
    (function run()
    {
        layout.init({
            section : {title : 'Your sells'},
            breadcrumb : {title: 'your sells'},
            page : {className :'page-user-sells'},
            pagination : {
                active : true,
                sortBy : 'starts',
                sortOrder : 'asc',
                resourceUrl : '/user/' + auth.getUser().id + '/sale',
            }
        });
        layout.pagination.fetchItems();
    })();
});
