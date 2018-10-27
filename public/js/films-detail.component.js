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

    function FilmDetailController(AjaxService) {

        var vm = this;
        return vm;
    }
})();