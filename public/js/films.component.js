(function () {
    'use strict';

    angular
        .module('film')
        .component('films', {
            bindings: {
                films: '<'
            },
            controller: FilmController,
            controllerAs: 'vm',
            templateUrl: 'components/films.html'
        });

    function FilmController() {
        var vm = this;
    }
})();