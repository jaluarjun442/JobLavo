@extends('layouts.web')

@section('title')
    Cookie Policy | JobLavo
@endsection

@section('meta_description')
    Learn how JobLavo uses cookies and similar technologies to improve website functionality, understand usage and provide a better browsing experience.
@endsection

@section('canonical', url('/cookie-policy'))

@section('content')

<section class="bg-white py-4 py-md-5">

    <div class="container">

        <div class="job-section-card">

            <div class="job-section-header header-navy">

                <h1>
                    Cookie Policy
                </h1>

            </div>


            <div class="p-4">


                <p>
                    This Cookie Policy explains how JobLavo uses cookies and similar
                    technologies when you visit and use our website. It explains what
                    cookies are, why they are used and how you can manage your cookie
                    preferences.
                </p>


                <h2 class="h4 fw-bold mt-4 mb-3">
                    What Are Cookies?
                </h2>

                <p>
                    Cookies are small text files that may be stored on your device
                    when you visit a website. They help websites remember information,
                    understand how visitors use the site and provide certain features.
                </p>


                <h2 class="h4 fw-bold mt-4 mb-3">
                    How We Use Cookies
                </h2>

                <p>
                    JobLavo may use cookies and similar technologies for purposes such
                    as improving website functionality, remembering preferences,
                    understanding website traffic and measuring the performance of
                    our content and services.
                </p>


                <h2 class="h4 fw-bold mt-4 mb-3">
                    Types of Cookies We May Use
                </h2>

                <h3 class="h5 fw-bold mt-3 mb-2">
                    Essential Cookies
                </h3>

                <p>
                    These cookies may be necessary for certain website functions,
                    security and basic functionality. Without them, some parts of
                    the website may not work as intended.
                </p>


                <h3 class="h5 fw-bold mt-3 mb-2">
                    Analytics Cookies
                </h3>

                <p>
                    Analytics technologies may help us understand how visitors use
                    JobLavo, such as which pages are visited and how users navigate
                    the website. This information can help us improve the website
                    and its content.
                </p>


                <h3 class="h5 fw-bold mt-3 mb-2">
                    Advertising Cookies
                </h3>

                <p>
                    JobLavo may display advertisements provided by third-party
                    advertising services. These services may use cookies or similar
                    technologies to help deliver and measure advertisements and
                    understand interactions with advertising content.
                </p>


                <h2 class="h4 fw-bold mt-4 mb-3">
                    Third-Party Services
                </h2>

                <p>
                    Some services used on JobLavo may be provided by third parties.
                    These third parties may use cookies or similar technologies in
                    accordance with their own privacy policies and terms.
                </p>


                <h2 class="h4 fw-bold mt-4 mb-3">
                    Managing Cookies
                </h2>

                <p>
                    Most web browsers allow you to control or delete cookies through
                    their settings. You can choose to block cookies, delete existing
                    cookies or receive a notification before cookies are stored.
                    Disabling certain cookies may affect the functionality of some
                    parts of the website.
                </p>


                <h2 class="h4 fw-bold mt-4 mb-3">
                    Changes to This Cookie Policy
                </h2>

                <p>
                    We may update this Cookie Policy from time to time to reflect
                    changes in our website, services or applicable requirements.
                    Any updated version will be published on this page.
                </p>


                <h2 class="h4 fw-bold mt-4 mb-3">
                    Contact Us
                </h2>

                <p>
                    If you have questions about this Cookie Policy or the use of
                    cookies on JobLavo, you can contact us through our
                    <a href="{{ route('contact') }}">
                        Contact
                    </a>
                    page.
                </p>


                <p class="text-muted small mt-4 mb-0">
                    Last updated: {{ now()->format('d M Y') }}
                </p>


            </div>

        </div>

    </div>

</section>

@endsection