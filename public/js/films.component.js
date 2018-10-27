(function () {
    'use strict';

    angular
        .module('film')
        .component('films', {
            bindings: {
                films: '='
            },
            controller: FilmController,
            controllerAs: 'vm',
            templateUrl: 'components/films.html'
        });

    function FilmController(AjaxService) {

        var vm = this;
        vm.loading = false;
        vm.changeFilm = changeFilm;
        return vm;

        function changeFilm(direction){

            let pag = vm.films.current;
            if(direction == 'previous'){
                --pag;
            }else{
                ++pag;
            }

            vm.loading = true;
            AjaxService.request("GET", "films/" + pag , {}).then(
                function(film){
                    vm.films = film;
                }
            ).finally(function(){
                vm.loading = false;
            });
        }
    }
})();