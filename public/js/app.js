(function () {
    'use strict';

    angular
        .module('film', ['ui.router', 'ngStorage', 'toastr'])
        .run(run)
        .service('AjaxService', AjaxService)
        .factory('Interceptor', Interceptor)
        .config(Configure);

    run.$inject = ['$rootScope', '$http', '$localStorage'];
    AjaxService.$inject = ['$http', '$q'];
    Interceptor.$inject = ['$rootScope', '$q', 'toastr', '$localStorage'];
    Configure.$inject = ['$stateProvider', '$urlServiceProvider', '$httpProvider', 'toastrConfig'];

    function run($rootScope, $http, $localStorage) {

        $rootScope.user = {
            name: null,
            logged: false
        };

        // keep user logged in after page refresh
        if ($localStorage.user) {

            $rootScope.user = {
                name: $localStorage.user.name,
                logged: true
            };

            $http.defaults.headers.common.Authorization = 'Bearer ' + $localStorage.user.token;
        }
    }

    function Configure($stateProvider, $urlServiceProvider, $httpProvider, toastrConfig) {

        $httpProvider.interceptors.push('Interceptor');

        angular.extend(toastrConfig, {
            allowHtml: true,
            closeButton: true,
            closeHtml: '<button>&times;</button>',
            iconClasses: {
                error: 'toast-error',
                info: 'toast-info',
                success: 'toast-success',
                warning: 'toast-warning'
            },
            messageClass: 'toast-message',
            progressBar: true,
            tapToDismiss: false,
            timeOut: 5000,
            titleClass: 'toast-title',
            toastClass: 'toast'
        });

        $urlServiceProvider.rules.otherwise({ state: 'films' });

        $stateProvider.state('films', {
            url: '/films',
            component: 'films',
            resolve: {
                films: function (AjaxService) {
                    return AjaxService.request("GET", "films/1", {});
                }
            },
        });

        $stateProvider.state('filmsPag', {
            url: '/films/pag/:pag',
            component: 'films',
            resolve: {
                films: function ($transition$, AjaxService) {
                    return AjaxService.request("GET", "films/" + $transition$.params().pag, {});
                }
            },
        });

        $stateProvider.state('filmDetail', {
            url: '/films/:slugName',
            //parent: 'films',
            component: 'filmDetail',
            resolve: {
                film: function ($transition$, AjaxService) {
                    return AjaxService.request("GET", "films/" + $transition$.params().slugName, {});
                }
            }
        });

    }

    /* @ngInject */
    function AjaxService($http, $q) {

        this.request = request;

        ////////////////

        function request(method, url, data) {

            var promessa = $q.defer();

            document.getElementById('loading').style.display = 'inline-block';

            $http({
                method: method,
                url: ENDPOINT + "/api/" + url,
                data: data,
                cache: false,
                headers: {
                    "Content-Type": "application/json",
                }
            }).then(function successCallback(response) {
                promessa.resolve(response.data);
                document.getElementById('loading').style.display = 'none';
            }, function errorCallback(response) {
                promessa.reject(response);
                document.getElementById('loading').style.display = 'none';
            });

            return promessa.promise;
        }
    }

    /* @ngInject */
    function Interceptor($rootScope, $q, toastr, $localStorage) {

        return {
            responseError: function (error) {

                var msgError = "Some error occurred, please see the log.";
                var titleError = "Internal Error";

                if (angular.isDefined(error.data)) {

                    if (angular.isDefined(error.data.msg)) {

                        titleError = "";
                        msgError = error.data.msg;

                    } else if (angular.isDefined(error.data.validation_error)) {

                        msgError = "";
                        titleError = "Validation Error";

                        angular.forEach(error.data.validation_error, function (value, key) {
                            msgError += value;
                        });
                    }
                }

                toastr.error(msgError, titleError);

                if (error.code == 401) {

                    $rootScope.user = {
                        name: null,
                        logged: false
                    };

                    delete $localStorage.user;
                    $http.defaults.headers.common.Authorization = '';
                }

                return $q.reject(error);
            }
        };
    }

})();