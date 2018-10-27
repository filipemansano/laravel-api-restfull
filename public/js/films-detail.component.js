(function () {
    'use strict';

    angular
        .module('film')
        .component('filmDetail', {
            bindings: {
                film: '<'
            },
            controller: FilmDetailController,
            controllerAs: 'vm',
            templateUrl: 'components/film-detail.html'
        });

    function FilmDetailController($rootScope, AjaxService, toastr) {

        var vm = this;
        vm.user = $rootScope.user;
        vm.sending = false;
        vm.comment = "";

        vm.post = post;
        return vm;

        function post(){

            vm.sending = true;
            AjaxService.request("POST","comments/create",{
                "film_id": vm.film.id,
                "comment": vm.comment
            }).then(
                function d(comment){
                    vm.comment = "";
                    toastr.success("post successfully");
                    vm.film.comments.push(comment);
                }
            ).finally(function(){
                vm.sending = false
            });
        }
    }
})();