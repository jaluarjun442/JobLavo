<!doctype html>
<html lang="en">

<head>
    <script>(function(s){s.dataset.zone='11685935',s.src='https://n6wxm.com/vignette.min.js'})([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')))</script>
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">
    @if(app()->environment('production'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-64LK51PQVX"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-64LK51PQVX');
        </script>
    @endif
    {{-- =========================================================
         BASIC SEO
    ========================================================== --}}

    <title>
        @yield(
        'title',
        'Government Jobs & Recruitment Updates'
        )
    </title>


    <meta name="description"
        content="@yield(
              'meta_description',
              'Latest government jobs, recruitment notifications, admit cards, answer keys, exam results and important career updates.'
          )">


    <meta name="keywords"
        content="@yield(
              'meta_keywords',
              'government jobs, govt jobs, latest government jobs, recruitment, admit card, answer key, results'
          )">

    <meta name="robots" content="max-image-preview:large">


    <meta name="author"
        content="@yield(
              'meta_author',
              config('app.name', 'Government Jobs Portal')
          )">


    <meta name="theme-color"
        content="#08245c">


    {{-- =========================================================
         CANONICAL
    ========================================================== --}}

    <link rel="canonical"
        href="@yield(
              'canonical',
              url()->current()
          )">


    {{-- =========================================================
         OPEN GRAPH
    ========================================================== --}}

    <meta property="og:type"
        content="@yield('og_type', 'website')">


    <meta property="og:title"
        content="@yield(
              'og_title',
              config('app.name', 'Government Jobs Portal')
          )">


    <meta property="og:description"
        content="@yield(
              'og_description',
              'Latest government jobs, recruitment notifications, admit cards, answer keys and exam results.'
          )">


    <meta property="og:url"
        content="@yield(
              'og_url',
              url()->current()
          )">


    <meta property="og:site_name"
        content="{{ config('app.name', 'Government Jobs Portal') }}">


    @hasSection('og_image')

    <meta property="og:image"
        content="@yield('og_image')">

    @endif


    {{-- =========================================================
         TWITTER / X
    ========================================================== --}}

    <meta name="twitter:card"
        content="summary_large_image">


    <meta name="twitter:title"
        content="@yield(
              'twitter_title',
              config('app.name', 'Government Jobs Portal')
          )">


    <meta name="twitter:description"
        content="@yield(
              'twitter_description',
              'Latest government jobs and recruitment updates.'
          )">


    @hasSection('twitter_image')

    <meta name="twitter:image"
        content="@yield('twitter_image')">

    @endif


    {{-- =========================================================
         STRUCTURED DATA
    ========================================================== --}}

    @stack('structured_data')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [

            {
                "@type": "WebSite",
                "@id": "{{ url('/') }}/#website",
                "url": "{{ url('/') }}",
                "name": "JobLavo",
                "inLanguage": "en-IN"
            },

            {
                "@type": "Organization",
                "@id": "{{ url('/') }}/#organization",
                "name": "JobLavo",
                "url": "{{ url('/') }}",
                "description": "Government jobs, recruitment notifications, admit cards, answer keys, exam results and important career updates.",
                "areaServed": "IN"
            }

        ]
    }
    </script>

    {{-- =========================================================
         BOOTSTRAP 5
    ========================================================== --}}

   <link rel="stylesheet" href="{{ asset('web_assets/css/bootstrap.min.css') }}">
    @stack('head')

</head>


<body>


    {{-- =========================================================
         TOP BAR
    ========================================================== --}}

    <div class="site-topbar">

        <div class="container">

            <div class="d-flex
                        justify-content-between
                        align-items-center
                        py-2">

                <div>

                    Welcome to
                    {{ config('app.name', 'Government Jobs Portal') }}

                </div>


                <div class="d-none d-md-flex
                            align-items-center
                            gap-3
                            topbar-muted">

                    <span>
                        Latest Job Updates
                    </span>

                    <span>
                        |
                    </span>

                    <span>
                        Recruitment Information
                    </span>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         MAIN HEADER
    ========================================================== --}}

    <header class="site-header">

        <nav class="navbar navbar-expand-lg site-navbar">

            <div class="container mx-width-100">


                {{-- LOGO --}}

                <a href="{{ url('/') }}"
                    class="navbar-brand
                          d-flex
                          align-items-center
                          gap-3">


                    <div class="site-logo">

                        JL

                    </div>


                    <div class="site-brand">

                        <div class="site-brand-name">

                            {{ config(
                                'app.name',
                                'Government Jobs Portal'
                            ) }}

                        </div>



                    </div>


                </a>



                {{-- MOBILE MENU BUTTON --}}

                <button
                    class="navbar-toggler site-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavigation"
                    aria-controls="mainNavigation"
                    aria-expanded="false"
                    aria-label="Open navigation menu">

                    <span class="navbar-toggler-icon"></span>

                </button>



                {{-- NAVIGATION --}}

                <div
                    class="collapse navbar-collapse site-nav"
                    id="mainNavigation">


                    <ul class="navbar-nav
                               ms-auto
                               align-items-lg-center">


                        {{-- HOME --}}

                        <li class="nav-item nav-item-des">

                            <a href="{{ url('/') }}"
                                class="nav-link">

                                Home

                            </a>

                        </li>
                        {{-- =========================================================
     PARENT CATEGORIES ONLY
========================================================= --}}

                        @foreach($headerCategories as $category)

                        <li class="nav-item nav-item-des">

                            <a
                                href="{{ route('category', $category->slug) }}"
                                class="nav-link">

                                {{ $category->name }}

                            </a>

                        </li>

                        @endforeach

                        {{-- ABOUT --}}

                        <li class="nav-item nav-item-des">

                            <a href="{{ url('/about-us') }}"
                                class="nav-link">

                                About Us

                            </a>

                        </li>



                        {{-- CONTACT --}}

                        <li class="nav-item nav-item-des">

                            <a href="{{ url('/contact') }}"
                                class="nav-link">

                                Contact

                            </a>

                        </li>



                        {{-- SEARCH --}}

                        <li class="nav-item nav-item-des
                                   ms-lg-2
                                   mt-2
                                   mt-lg-0">

                            <a href="{{ url('/search') }}"
                                class="site-search-btn">

                                <span class="search-icon">
                                    ⌕
                                </span>

                                Search

                            </a>

                        </li>


                    </ul>

                </div>

            </div>

        </nav>

    </header>



    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <main class="site-main">

        @yield('content')

    </main>



    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <footer class="site-footer">

        <div class="container py-5">

            <div class="row g-4">


                {{-- ABOUT --}}

                <div class="col-lg-8">

                    <h2 class="footer-title">

                        {{ config(
                            'app.name',
                            'Government Jobs Portal'
                        ) }}

                    </h2>


                    <p class="footer-text">

                    We are a Non-Government Entity provide jobs information gathered from various trusted sources. We also provide official source pdf link or website URL at the end of each job. All the content provided here is only for the summarized information purpose to the job aspirants. JobLavo team does not call any person for job offers or job assistance. JobLavo.com never charge any candidate or person for any job. Please beware of fake calls or emails. For any assistance, please contact us on email at info@joblavo.com While all efforts have been made to make the Information available on this App/website as Authentic as possible. We are not responsible for any loss to anybody or anything caused by any Shortcoming, Defect or Inaccuracy of the Information on this Website. Please check Official Government Website twice before applying for any job.

                    </p>

                </div>



                {{-- QUICK LINKS --}}

                <div class="col-6 col-lg-2">

                    <h2 class="footer-title">

                        Quick Links

                    </h2>


                    <ul class="list-unstyled">


                        <li class="mb-2">

                            <a
                                href="{{ url('/') }}"
                                class="footer-link">

                                Home

                            </a>

                        </li>


                        <li class="mb-2">

                            <a
                                href="{{ url('/about-us') }}"
                                class="footer-link">

                                About Us

                            </a>

                        </li>


                        <li class="mb-2">

                            <a
                                href="{{ url('/contact') }}"
                                class="footer-link">

                                Contact Us

                            </a>

                        </li>


                    </ul>

                </div>



                {{-- LEGAL --}}

                <div class="col-6 col-lg-2">

                    <h2 class="footer-title">

                        Legal

                    </h2>


                    <ul class="list-unstyled">


                        <li class="mb-2">

                            <a
                                href="{{ url('/privacy-policy') }}"
                                class="footer-link">

                                Privacy Policy

                            </a>

                        </li>


                        <li class="mb-2">

                            <a
                                href="{{ url('/terms-and-conditions') }}"
                                class="footer-link">

                                Terms & Conditions

                            </a>

                        </li>


                        <li class="mb-2">

                            <a
                                href="{{ url('/disclaimer') }}"
                                class="footer-link">

                                Disclaimer

                            </a>

                        </li>


                    </ul>

                </div>


            </div>

        </div>



        {{-- COPYRIGHT --}}

        <div class="footer-bottom">

            <div class="container py-3">

                <div class="d-flex
                            flex-column
                            flex-md-row
                            justify-content-between
                            align-items-center
                            gap-2">


                    <small class="footer-muted">

                        © {{ date('Y') }}

                        {{ config(
                            'app.name',
                            'Government Jobs Portal'
                        ) }}.

                        All rights reserved.

                    </small>


                    <small class="footer-muted">

                        Government Jobs & Recruitment Alert.

                    </small>


                </div>

            </div>

        </div>

    </footer>



    {{-- =========================================================
         BOOTSTRAP JS
    ========================================================== --}}

    <script src="{{ asset('web_assets/js/bootstrap.bundle.min.js') }}" defer></script>

    @stack('scripts')

</body>

</html>