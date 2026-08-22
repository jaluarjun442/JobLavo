@extends('layouts.web')


@section('title', 'Contact Us | ' . config('app.name'))


@section(
'meta_description',
'Contact us for questions, corrections, feedback or other information related to government jobs and recruitment updates.'
)


@section(
'meta_keywords',
'contact us, government jobs, recruitment, job updates'
)


@section('canonical', url('/contact'))


@section('og_title', 'Contact Us')


@section(
'og_description',
'Contact us for questions, feedback and corrections related to government job updates.'
)


@section('content')

<div class="bg-light py-4">

    <div class="container">


        {{-- BREADCRUMB --}}

        <nav aria-label="breadcrumb" class="mb-3">

            <ol class="breadcrumb mb-0">

                <li class="breadcrumb-item">

                    <a href="{{ url('/') }}"
                        class="text-decoration-none">

                        Home

                    </a>

                </li>


                <li class="breadcrumb-item active"
                    aria-current="page">

                    Contact Us

                </li>

            </ol>

        </nav>



        <div class="row g-4">


            {{-- =================================================
                 CONTACT FORM
            ================================================== --}}

            <div class="col-lg-8">


                <section class="bg-white border rounded-2 shadow-sm">


                    <div class="p-3 p-md-4 border-bottom">


                        <h1 class="h3 fw-bold text-dark mb-2">

                            Contact Us

                        </h1>


                        <p class="text-secondary mb-0">

                            Have a question, feedback or correction?
                            Send us a message.

                        </p>


                    </div>



                    <div class="p-3 p-md-4">


                        {{-- SUCCESS MESSAGE --}}

                        @if(session('success'))

                        <div class="alert alert-success"
                            role="alert">

                            {{ session('success') }}

                        </div>

                        @endif



                        {{-- VALIDATION ERRORS --}}

                        @if($errors->any())

                        <div class="alert alert-danger"
                            role="alert">

                            <strong>
                                Please fix the following:
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



                        <form action="{{ url('/contact') }}"
                            method="POST">

                            @csrf



                            {{-- NAME --}}

                            <div class="mb-3">

                                <label for="contact-name"
                                    class="form-label fw-semibold">

                                    Name

                                </label>


                                <input type="text"
                                    id="contact-name"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="Enter your name"
                                    maxlength="100"
                                    required>

                            </div>



                            {{-- EMAIL --}}

                            <div class="mb-3">

                                <label for="contact-email"
                                    class="form-label fw-semibold">

                                    Email Address

                                </label>


                                <input type="email"
                                    id="contact-email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email"
                                    maxlength="150"
                                    required>

                            </div>



                            {{-- SUBJECT --}}

                            <div class="mb-3">

                                <label for="contact-subject"
                                    class="form-label fw-semibold">

                                    Subject

                                </label>


                                <input type="text"
                                    id="contact-subject"
                                    name="subject"
                                    class="form-control"
                                    value="{{ old('subject') }}"
                                    placeholder="Enter subject"
                                    maxlength="200"
                                    required>

                            </div>



                            {{-- MESSAGE --}}

                            <div class="mb-3">

                                <label for="contact-message"
                                    class="form-label fw-semibold">

                                    Message

                                </label>


                                <textarea id="contact-message"
                                    name="message"
                                    rows="6"
                                    class="form-control"
                                    placeholder="Write your message..."
                                    maxlength="5000"
                                    required>{{ old('message') }}</textarea>

                            </div>



                            {{-- SUBMIT --}}

                            <button type="submit"
                                class="btn px-4"
                                style="background:#06245f;color:#fff;">

                                Send Message

                            </button>


                        </form>


                    </div>


                </section>


            </div>



            {{-- =================================================
                 CONTACT INFORMATION
            ================================================== --}}

            <div class="col-lg-4">


                <section class="bg-white border rounded-2 shadow-sm">


                    <div class="px-3 py-3 text-white"
                        style="background:#06245f;">

                        <h2 class="h6 fw-bold mb-0">

                            Get in Touch

                        </h2>

                    </div>


                    <div class="p-3">


                        <div class="mb-4">


                            <h3 class="h6 fw-bold text-dark">

                                General Queries

                            </h3>


                            <p class="text-secondary mb-0">

                                For general questions, suggestions
                                or website-related queries, use the
                                contact form.

                            </p>


                        </div>



                        <div class="mb-4">


                            <h3 class="h6 fw-bold text-dark">

                                Corrections

                            </h3>


                            <p class="text-secondary mb-0">

                                If you find incorrect or outdated
                                information, please let us know with
                                the relevant post details.

                            </p>


                        </div>



                        <div>


                            <h3 class="h6 fw-bold text-dark">

                                Important

                            </h3>


                            <p class="text-secondary mb-0">

                                For official recruitment information,
                                candidates should always verify details
                                with the concerned government authority.

                            </p>


                        </div>


                    </div>


                </section>


            </div>


        </div>


    </div>

</div>

@endsection