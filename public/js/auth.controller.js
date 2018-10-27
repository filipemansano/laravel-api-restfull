(function () {
    'use strict';

    angular
        .module('film')
        .controller('Auth', Auth);

    Auth.$inject = ['$rootScope','$localStorage','$http','AjaxService', 'toastr'];

    /* @ngInject */
    function Auth($rootScope, $localStorage, $http, AjaxService, toastr) {

        var vm = this;

        vm.signin_info = {
            email: "",
            password: "",
        };

        vm.signup_info = {
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
        };

        vm.sending = false;
        vm.signin = signin;
        vm.signup = signup;
        
        return vm;

        ////////////////

        function signin() {

            var toast = toastr.info('Checking credentials <i class="fa fa-sync fa-spin"></i>', {
                progressBar: false,
                closeButton: false,
                timeOut: 0,
            });

            vm.sending = true;
            AjaxService.request("POST", "auth/login", vm.signin_info).then(

                function (response) {

                    if (angular.isUndefined(response.token)) {
                        return toastr.error("Token not received");
                    }

                    $rootScope.user = {
                        name: response.name,
                        logged: true
                    };

                    $localStorage.user = {
                        name: response.name,
                        token: response.token
                    };

                    $http.defaults.headers.common.Authorization = 'Bearer ' + response.token;
                    
                    toastr.success("Welcome "+response.name);
                }

            ).finally(function () {
                vm.sending = false;
                toastr.clear(toast);
            });
        }

        function signup() {

            var toast = toastr.info('Checking information please wait <i class="fa fa-sync fa-spin"></i>', {
                progressBar: false,
                closeButton: false,
                timeOut: 0,
            });

            vm.sending = true;
            AjaxService.request("POST", "auth/register", vm.signup_info).then(

                function (response) {

                    toastr.success('Successful registration!');

                    vm.signin_info.email = vm.signup_info.email;
                    vm.signin_info.password = vm.signup_info.password;

                    signin();
                },

            ).finally(function () {
                vm.sending = false;
                toastr.clear(toast);
            });
        }
    }
})();