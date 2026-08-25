@extends('layouts.web')


@section('title', 'Page Not Found | ' . config('app.name'))


@section(
'meta_description',
'The requested page could not be found. Browse the latest government jobs and recruitment updates.'
)


@section('meta_robots', 'noindex, follow')


@section('canonical', url()->current())


@section('content')

<div class="bg-light py-5">

    <div class="container">


        <div class="row justify-content-center">

            <div class="col-lg-7">


                <section class="bg-white border rounded-2 shadow-sm text-center">


                    <div class="p-4 p-md-5">


                        {{-- ERROR NUMBER --}}

                        <div class="display-1 fw-bold"
                            style="color:#06245f;">

                            404

                        </div>


                        {{-- TITLE --}}

                        <h1 class="h3 fw-bold text-dark mb-3">

                            Page Not Found

                        </h1>


                        {{-- DESCRIPTION --}}

                        <p class="text-secondary mb-4">

                            The page you are looking for may have been
                            moved, removed or the URL may be incorrect.

                        </p>


                        {{-- HOME BUTTON --}}

                        <a href="{{ url('/') }}"
                            class="btn px-4 py-2"
                            style="background:#06245f;color:#fff;">

                            Go to Homepage

                        </a>


                    </div>


                </section>



                {{-- QUICK LINKS --}}

                <div class="row g-3 mt-3">


                    <div class="col-md-4">

                        <a href="{{ url('/category/latest-government-jobs') }}"
                            class="d-block bg-white border rounded-2 shadow-sm
                                  text-center text-decoration-none
                                  text-dark fw-semibold p-3">

                            Latest Jobs

                        </a>

                    </div>


                    <div class="col-md-4">

                        <a href="{{ url('/category/admit-card') }}"
                            class="d-block bg-white border rounded-2 shadow-sm
                                  text-center text-decoration-none
                                  text-dark fw-semibold p-3">

                            Admit Cards

                        </a>

                    </div>


                    <div class="col-md-4">

                        <a href="{{ url('/category/government-exam-results') }}"
                            class="d-block bg-white border rounded-2 shadow-sm
                                  text-center text-decoration-none
                                  text-dark fw-semibold p-3">

                            Results

                        </a>

                    </div>


                </div>


            </div>

        </div>


    </div>

</div>

@endsection