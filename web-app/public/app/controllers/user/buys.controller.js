'use strict';

angular.module('app')
.controller('UserBuysController', function(layout, auth) 
{
    var vm = Object.assign(this, 
    {

    });
    (function run()
    {
        layout.init({
            section : {title : 'Your buys'},
            breadcrumb : {title: 'your buys'},
            page : {className :'page-user-buys'},
            pagination : {
                active : true,
                sortBy : 'starts',
                sortOrder : 'asc',
                resourceUrl : '/user/' + auth.getUser().id + '/buy',
            }
        });
        layout.pagination.fetchItems();
    })();
});
