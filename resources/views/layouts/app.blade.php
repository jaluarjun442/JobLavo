<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Home') }}</title>

    <!-- Scripts -->
    <script src='{{ asset("admin_asset/jquery.min.js") }}'></script>
    <script src='{{ asset("admin_asset/jquery.dataTables.min.js") }}'></script>
    <script src='{{ asset("admin_asset/jquery.validate.min.js") }}'></script>
    <script src='{{ asset("admin_asset/bootstrap.min.js") }}'></script>
    <script src='{{ asset("admin_asset/dataTables.bootstrap4.min.js") }}'></script>
    <script src='{{ asset("admin_asset/ckeditor/ckeditor.js") }}'></script>
    <script src='{{ asset("admin_asset/ckfinder/ckfinder.js") }}'></script>
    <script src='{{ asset("admin_asset/select2.min.js") }}'></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href='{{ asset("admin_asset/bootstrap.min.css") }}' rel="stylesheet" />
    <link href='{{ asset("admin_asset/jquery.dataTables.min.css") }}' rel="stylesheet" />
    <link href='{{ asset("admin_asset/dataTables.bootstrap4.min.css") }}' rel="stylesheet" />
    <link href='{{ asset("admin_asset/select2.min.css") }}' rel="stylesheet" />
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php

    ?>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <button
                    class="navbar-toggler"
                    type="button"
                    id="adminMenuToggle"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">

                    <span class="navbar-toggler-icon"></span>

                </button>
                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent">

                    @guest

                    @else

                    <ul class="navbar-nav mr-auto">

                        <a class="nav-link"
                            href="{{ route('admin_home') }}">
                            Home
                        </a>

                        <a class="nav-link"
                            href="{{ route('admin.store') }}">
                            Store
                        </a>

                        <a class="nav-link"
                            href="{{ route('admin.price') }}">
                            Price
                        </a>

                        <!-- <a class="nav-link"
                            href="{{ route('admin.category') }}">
                            Category
                        </a>

                        <a class="nav-link"
                            href="{{ route('admin.product') }}">
                            Product
                        </a> -->

                        <a class="nav-link" href="{{ route('admin.orders') }}">
                            Orders
                        </a>
                        <a class="nav-link" href="{{ route('admin.contact') }}">
                            Contacts
                        </a>
                        <a class="nav-link" href="{{ route('admin.print_documents') }}">
                            Print Documents
                        </a>
                        <a class="nav-link" href="{{ route('admin.blog') }}">
                            Blogs
                        </a>

                    </ul>

                    @endguest


                    <ul class="navbar-nav ml-auto">

                        @guest

                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
                        </li>

                        @if (Route::has('register'))

                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a>
                        </li>

                        @endif

                        @else

                        <li class="nav-item">

                            <a class="nav-link"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">

                                ( {{ Auth::user()->name }} )
                                {{ __('Logout') }}

                            </a>

                            <form
                                id="logout-form"
                                action="{{ route('logout') }}"
                                method="POST"
                                class="d-none">

                                @csrf

                            </form>

                        </li>

                        @endguest

                    </ul>

                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <style>
        /*
    =========================================
    ADMIN MOBILE MENU
    =========================================
    */

        @media (max-width: 767.98px) {

            #navbarSupportedContent {
                display: none !important;

                width: 100%;

                background: #fff;

                border-top: 1px solid #eee;

                padding: 10px 0;
            }


            #navbarSupportedContent.admin-menu-open {
                display: block !important;
            }


            #navbarSupportedContent .navbar-nav {
                display: block;

                width: 100%;
            }


            #navbarSupportedContent .nav-link {
                display: block;

                padding: 10px 15px;

                border-bottom: 1px solid #f1f1f1;

                color: #333;
            }


            #navbarSupportedContent .nav-link:hover {
                background: #f7f7f7;
            }


            #adminMenuToggle {
                cursor: pointer;
            }

        }


        /*
    =========================================
    DESKTOP
    =========================================
    */

        @media (min-width: 768px) {

            #navbarSupportedContent {
                display: flex !important;
            }

        }
    </style>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const toggle =
                    document.getElementById(
                        'adminMenuToggle'
                    );

                const menu =
                    document.getElementById(
                        'navbarSupportedContent'
                    );


                if (
                    !toggle ||
                    !menu
                ) {
                    return;
                }


                /*
                =====================================
                TOGGLE MENU
                =====================================
                */

                toggle.addEventListener(
                    'click',
                    function(event) {

                        event.preventDefault();

                        event.stopPropagation();


                        const isOpen =
                            menu.classList.contains(
                                'admin-menu-open'
                            );


                        if (isOpen) {

                            menu.classList.remove(
                                'admin-menu-open'
                            );

                            toggle.setAttribute(
                                'aria-expanded',
                                'false'
                            );

                        } else {

                            menu.classList.add(
                                'admin-menu-open'
                            );

                            toggle.setAttribute(
                                'aria-expanded',
                                'true'
                            );

                        }

                    }
                );


                /*
                =====================================
                DON'T AUTO CLOSE WHEN CLICKING
                INSIDE MENU
                =====================================
                */

                menu.addEventListener(
                    'click',
                    function(event) {

                        event.stopPropagation();

                    }
                );


                /*
                =====================================
                OUTSIDE CLICK = CLOSE
                =====================================
                */

                document.addEventListener(
                    'click',
                    function(event) {

                        if (
                            window.innerWidth >= 768
                        ) {
                            return;
                        }


                        if (
                            !menu.contains(event.target) &&
                            !toggle.contains(event.target)
                        ) {

                            menu.classList.remove(
                                'admin-menu-open'
                            );

                            toggle.setAttribute(
                                'aria-expanded',
                                'false'
                            );

                        }

                    }
                );


                /*
                =====================================
                RESIZE
                =====================================
                */

                window.addEventListener(
                    'resize',
                    function() {

                        if (
                            window.innerWidth >= 768
                        ) {

                            menu.classList.remove(
                                'admin-menu-open'
                            );

                            toggle.setAttribute(
                                'aria-expanded',
                                'false'
                            );

                        }

                    }
                );

            }
        );
    </script>
</body>


</html>