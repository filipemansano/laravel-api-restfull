(function () {
    'use strict';

    angular
        .module('film')
        .component('filmsAdd', {
            bindings: {
                genres: '<'
            },
            controller: FilmAddController,
            controllerAs: 'vm',
            templateUrl: 'components/film-add.html'
        });

    function FilmAddController($state, AjaxService, toastr) {

        var vm = this;
        vm.createFilm = createFilm;
        vm.photo = "";
        vm.release_date = new Date();

        vm.film = {
            name: "",
            description: "",
            release_date: null,
            rating: 1,
            ticket_price: "0",
            country: "",
            photo: "",
            genres: []
        };

        return vm;

        function createFilm() {

            var f = document.getElementById('photo-film').files[0],
                r = new FileReader();

            r.onloadend = function (e) {

                let year = vm.release_date.getFullYear();
                let month = parseInt(vm.release_date.getMonth())+1;
                let day = vm.release_date.getDate();

                if(month < 9){
                    month = "0"+month;
                }

                if(day < 9){
                    day = "0"+day;
                }

                let base64 = e.target.result.split(",");
                vm.film.photo = base64[1];
                vm.film.release_date = year + "-" + month + "-" + day

                AjaxService.request("POST","films/create",vm.film).then(
                    function(success){
                        toastr.success("Film created!");
                        $state.go("filmDetail", {"slugName": success.slug_name});
                    }
                )
            }

            r.readAsDataURL(f);
        }
    }
})();