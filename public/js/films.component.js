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

    function FilmController($state) {

        var vm = this;
        vm.changeFilm = changeFilm;
        return vm;

        function changeFilm(direction){

            let pag = vm.films.current;
            if(direction == 'previous'){
                --pag;
            }else{
                ++pag;
            }

            $state.go("filmsPag", {"pag": pag});
        }
    }
})();