'use strict';

angular.module('app', [
    'ui.bootstrap', 
    'ui.bootstrap.tpls',
    'ngRoute',
    'ngAnimate', 
    'ngCookies', 
    'ngResource', 
    'duScroll', 
    "flash"
//  'shopAnimations',
//  'shopControllers',
//  'shopFilters',
//  'shopServices'
]);

angular.module('app').constant('API_PATH', '/v1.0');

angular.module('app').config(function($routeProvider) {

    $routeProvider.

    //auth
    when('/auth/login', {
        templateUrl: 'tpl/pages/auth-login.tpl.html',
        controller: 'AuthLoginController',
        controllerAs: 'page',
        options : { }        
    }).
    when('/auth/signup', {
        templateUrl: 'tpl/pages/auth-signup.tpl.html',
        controller: 'AuthSignupController',
        controllerAs: 'page',
        options : { }  
    }).

    //auth user
    // when('/user', {
    //     templateUrl: 'tpl/pages/user-profile.tpl.html',
    //     controller: 'UserProfileController',
    //     controllerAs: 'page',
    // }).  
    when('/user/settings', {
        templateUrl: 'tpl/pages/user-settings.tpl.html',
        controller: 'UserSettingsController',
        controllerAs: 'page',
        middleware : {  },
        options : { }      
    }).
    when('/user/favourites', {
        templateUrl: 'tpl/pages/product-results.tpl.html',
        controller: 'UserFavouritesController',
        controllerAs: 'page',
        middleware : { auth: true },
        options : {  }        
    }).    
    when('/user/buys', {
        templateUrl: 'tpl/pages/user-buys.tpl.html',
        controller: 'UserBuysController',
        controllerAs: 'page',
        middleware : { auth: true },
        options : {  }        
    }).      
    when('/user/wares', {
        templateUrl: 'tpl/pages/product-results.tpl.html',
        controller: 'UserWaresController',
        controllerAs: 'page',
        middleware : { auth: true },
        options : { }        
    }).
    when('/user/sells', {
        templateUrl: 'tpl/pages/user-sellings.tpl.html',
        controller: 'UserSalesController',
        controllerAs: 'page',
        middleware : { auth: true },
        options : { }        
    }).


    //other users
    // when('/user/:uid', {
    //     templateUrl: 'tpl/pages/user-profile.tpl.html',
    //     controller: 'UserProfileController',
    //     controllerAs: 'page',
    // }).    
    when('/user/:uid/wares', {
        templateUrl: 'tpl/pages/product-results.tpl.html',
        controller: 'UserWaresController',
        controllerAs: 'page',      
        options : {}        
    }).
    when('/user/:uid/buys', {
        templateUrl: 'tpl/pages/product-results.tpl.html',
        controller: 'UserBuysController',
        controllerAs: 'page',    
        options : { }        
    }).
    when('/user/:uid/sellings', {
        templateUrl: 'tpl/pages/product-results.tpl.html',
        controller: 'UserSellingsController',
        controllerAs: 'page',
        middleware : { auth: true },
        options : { }        
    }).    

    //product
    when('/', {
        templateUrl: 'tpl/pages/product-results.tpl.html',
        controller: 'ProductResultsController',
        controllerAs: 'page', 
        options : { }              
    }).    
    when('/product/search', {
        templateUrl: 'tpl/pages/product-results.tpl.html',
        controller: 'ProductResultsController',
        controllerAs: 'page',
        options : {  }
    }).
    when('/product/:pid', {
        templateUrl: 'tpl/pages/product-show.tpl.html',
        controller: 'ProductShowController',
        controllerAs: 'page',
        options : {  }   
    }).
    when('/product/:pid/edit', {
        templateUrl: 'tpl/pages/product-edit.tpl.html',
        controller: 'ProductEditController',
        controllerAs: 'page',
        middleware : { auth: true },
        options : {  }       
    }).

    otherwise({
        redirectTo: '/'
    });

});

angular.module('app').run(function($rootScope, $document, $location, auth, layout)
{
    $rootScope.layout = layout;
    $rootScope.auth = auth;
    $rootScope.isInvalid = isInvalid;

    $rootScope.$on('$routeChangeStart', function(event, next, current)
    {
        //check if user is logged using middleware auth
        if(getMiddleware('auth', next) && !auth.getUser()){
            event.preventDefault();
            $location.path('/auth/login');
        }

        //scroll animate to top on route change
        var someElement = angular.element(document.body);
        $document.scrollToElementAnimated(someElement);

        layout.setDefaults();
    });

    function getMiddleware(name, route)
    {
        if(typeof route.$$route.middleware === "undefined"
        || typeof route.$$route.middleware[name] === "undefined"){
            return null;
        }

        return route.$$route.middleware[name];
    }

    function isInvalid(element, apiErrors)
    {
        return (element.$$parentForm.$submitted || element.$touched) 
        && (element.$invalid || apiErrors[element.$name] );
    }
});

