'use strict';

angular.module('app')
.controller('UserFavouritesController', function(layout, auth) 
{
    var vm = Object.assign(this, 
    {

    });
    (function run()
    {
        layout.init({
            section : {title : 'Your favourites'},
            breadcrumb : {title: 'your favourites'},
            page : {className :'page-user-favourite'},
            pagination : {
                active : true,
                sortBy : 'starts',
                sortOrder : 'asc',
                resourceUrl : '/user/' + auth.getUser().id + '/favourite',
            }
        });
        layout.pagination.fetchItems();
    })();
});
