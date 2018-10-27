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

    function FilmController(AjaxService,$state) {

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
                    $state.go("filmDetail", { slugName: film.film.slug_name});
                }
            ).finally(function(){
                vm.loading = false;
            });
        }
    }
})();