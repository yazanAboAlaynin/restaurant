<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <title>Laravel</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        body {
            font: 400 15px/1.8 Lato, sans-serif;
            color: #777;
        }
        h3, h4 {
            margin: 10px 0 30px 0;
            letter-spacing: 10px;
            font-size: 20px;
            color: #111;
        }
        .container {
            padding: 90px 120px;
        }

        .carousel-inner img {
            -webkit-filter: grayscale(10%);
            filter: grayscale(20%);
            width: 100%;
            margin: auto;
        }
        .carousel-caption h3 {
            color: #fff !important;
        }
        @media (max-width: 600px) {
            .carousel-caption {
                display: none;
            }
        }
        .bg-1 {
            background: #cd8916;
            color: #bdbdbd;
        }
        .bg-1 h3 {color: #fff;}
        .bg-1 p {font-style: italic;}

        .navbar {
            font-family: Montserrat, sans-serif;
            margin-bottom: 0;
            background-color: #cd8916;
            border: 0;
            font-size: 11px !important;
            letter-spacing: 4px;
            opacity: 0.9;
        }
        .navbar li a, .navbar .navbar-brand {
            color: #d5d5d5 !important;
        }
        .navbar-nav li a:hover {
            color: #fff !important;
        }
        .navbar-nav li.active a {
            color: #fff !important;
            background-color: #2c0600 !important;
        }
        .navbar-default .navbar-toggle {
            border-color: transparent;
        }




        footer {
            background-color: #cd8916;
            color: #f5f5f5;
            padding: 32px;
        }
        footer a {
            color: #f5f5f5;
        }
        footer a:hover {
            color: #777;
            text-decoration: none;
        }
    </style>
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">
<div id="app">
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">Restaurant</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#myPage">HOME</a></li>
                <li><a href="#user">User</a></li>
                <li><a href="#vendor">Vendor</a></li>
                <li><a class="" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form></a></li>

            </ul>
        </div>
    </div>
</nav>
        <main class="py-4">
            @yield('content')
        </main>
    <footer class="text-center">
        <br><br>

        <a class="up-arrow" href="#myPage" data-toggle="tooltip" title="TO TOP">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </a><br><br>
        <p>Move to the top</p>
    </footer>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        })
    </script>
    </div>

</body>
</html>
