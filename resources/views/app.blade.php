<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{{ asset('favicon.ico') }}}">
    <title>Laravel Test</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="{{{ asset('css/angular-toastr.css') }}}" rel="stylesheet">
    <link href="{{{ asset('css/film.css') }}}" rel="stylesheet">
    
</head>

<body ng-app="film">
    <header>
        <div class="collapse bg-dark" id="navbarHeader" ng-if="!user.logged">
            <div class="container">
                <div class="row" ng-controller="Auth as a">
                    <div class="col-md-6 py-4">
                        <h4 class="text-white">Sign in</h4>
                        <input ng-disabled="a.sending" ng-model="a.signin_info.email" type="email" class="form-control mb-2" placeholder="E-mail"/>
                        <input ng-disabled="a.sending" ng-model="a.signin_info.password" type="password" class="form-control mb-2" placeholder="Password"/>
                        <button ng-disabled="a.sending" ng-click="a.signin()" type="button" class="btn btn-primary w-100">
                            <i class="fa fa-lock"></i> Login
                        </button>
                    </div>
                    <div class="col-md-6 py-4">
                        <h4 class="text-white">Sign up</h4>
                        <div class="row">
                            <div class="col-6">
                                <input ng-model="a.signup_info.name" ng-disabled="a.sending" type="nome" class="form-control mb-2" placeholder="Name"/>
                            </div>
                            <div class="col-6">
                                <input ng-model="a.signup_info.email" ng-disabled="a.sending" type="email" class="form-control mb-2" placeholder="E-mail"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <input ng-model="a.signup_info.password" ng-disabled="a.sending" type="password" class="form-control mb-2" placeholder="Password"/>
                            </div>
                            <div class="col-6">
                                <input ng-model="a.signup_info.password_confirmation" ng-disabled="a.sending" type="password" class="form-control mb-2" placeholder="Confirm Password"/>
                            </div>
                        </div>
                        <button ng-disabled="a.sending" ng-click="a.signup()" type="button" class="btn btn-primary w-100">
                            <i class="fa fa-lock"></i> Register
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark box-shadow">
            <div class="container d-flex justify-content-between">
                <a href="/#!/films" class="navbar-brand d-flex align-items-center">
                    <strong>Film Album</strong> <i id="loading" class="fa fa-sync fa-spin"></i>
                </a>
                
                <button ng-if="!user.logged" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span> Login / Register
                </button>

                <span class="text-white" ng-show="user.logged">Bem vindo <span ng-bind="user.name"></span></span>
            </div>
        </div>
    </header>
    <main role="main">
        <ui-view>
            <div class="text-center">
                Loading... 
            </div>
        </ui-view>
    </main>
    <footer class="text-muted">
        <div class="container">
            
            <p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
            <p>New to Bootstrap?
                <a href="../../">Visit the homepage</a> or read our
                <a href="../../getting-started/">getting started guide</a>.
            </p>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- Custom styles for this template -->
    
    <!-- App -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.5/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/1.0.20/angular-ui-router.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ngStorage/0.3.10/ngStorage.min.js"></script>

    <script type="text/javascript">
        var ENDPOINT = "<?=$ENDPOINT?>";
    </script>

    <script type="text/javascript" src="{{{ asset('js/angular-toastr.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('js/app.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('js/films.component.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('js/films-detail.component.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('js/auth.controller.js') }}}"></script>
    
</body>

</html>