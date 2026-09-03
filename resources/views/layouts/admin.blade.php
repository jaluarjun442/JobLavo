<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Admin Panel')
    </title>

    <meta name="robots"
        content="noindex, nofollow">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet" />
</head>


<body class="bg-light">


    {{-- =====================================================
     NAVBAR
====================================================== --}}

    <nav class="navbar navbar-expand-lg navbar-dark"
        style="background:#06245f;">

        <div class="container">


            <a class="navbar-brand fw-bold"
                href="{{ url('/admin') }}">

                Admin Panel

            </a>


            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminNavigation"
                aria-controls="adminNavigation"
                aria-expanded="false"
                aria-label="Open admin navigation">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse"
                id="adminNavigation">


                <ul class="navbar-nav ms-auto">


                    <li class="nav-item">

                        <a href="{{ url('/admin/categories') }}"
                            class="nav-link">

                            Categories

                        </a>

                    </li>
                    <li class="nav-item">

                        <a href="{{ route('admin.posts.index') }}"
                            class="nav-link">

                            Posts

                        </a>

                    </li>
                    <li class="nav-item">

                        <a href="{{ route('admin.blog.index') }}"
                            class="nav-link">

                            Blogs

                        </a>

                    </li>
                    <li class="nav-item">

                        <a href="{{ route('admin.sources.index') }}"
                            class="nav-link">

                            Sources

                        </a>

                    </li>
                    <li class="nav-item">

                        <a href="{{ route('admin.sitemaps.index') }}"
                            class="nav-link">

                            Sitemaps

                        </a>

                    </li>
                    <li class="nav-item">

                        <a
                            href="{{ route('admin.logs.index') }}"
                            class="nav-link">

                            Logs

                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="{{ url('/') }}"
                            target="_blank"
                            class="nav-link">

                            View Website

                        </a>

                    </li>


                    <li class="nav-item">

                        <form action="{{ route('admin.logout') }}"
                            method="POST"
                            class="d-inline">

                            @csrf

                            <button type="submit"
                                class="nav-link btn btn-link border-0">

                                Logout

                            </button>

                        </form>

                    </li>

                </ul>


            </div>

        </div>

    </nav>



    {{-- =====================================================
     CONTENT
====================================================== --}}

    <main>

        <div class="container py-4">


            {{-- SUCCESS --}}

            @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show"
                role="alert">

                {{ session('success') }}


                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">

                </button>

            </div>

            @endif



            {{-- ERRORS --}}

            @if($errors->any())

            <div class="alert alert-danger"
                role="alert">

                <strong>
                    Please fix the following errors:
                </strong>


                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                    @endforeach

                </ul>

            </div>

            @endif


            @yield('content')


        </div>

    </main>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')

</body>

</html>