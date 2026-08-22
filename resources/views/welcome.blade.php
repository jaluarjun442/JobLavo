@extends('layouts.web')


@section('title', 'Government Jobs, Admit Card, Answer Key & Results')


@section('meta_description',
'Latest government jobs, recruitment notifications, admit cards, answer keys, exam results and important government job updates.'
)


@section('meta_keywords',
'government jobs, govt jobs, latest government jobs, sarkari jobs, government vacancy, recruitment, admit card, answer key, results'
)


@section('canonical', url('/'))


@section('og_title',
'Latest Government Jobs, Admit Card, Answer Key & Results'
)


@section('og_description',
'Find the latest government job vacancies, recruitment notifications, admit cards, answer keys and exam results.'
)



@section('content')


<div class="job-home">


    {{-- =====================================================
         TOP FEATURE TILES
    ====================================================== --}}

    <section class="bg-white py-3">

        <div class="container">

            <div class="row g-3">


                {{-- DSSSB --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/dsssb-various-post-apply-online') }}"
                        class="job-tile tile-navy">

                        <h2>
                            DSSSB Various Post
                            <br>
                            Apply Online
                        </h2>

                    </a>

                </div>


                {{-- UP POLICE --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/up-police-constable-recruitment') }}"
                        class="job-tile tile-navy">

                        <h2>
                            UP POLICE
                            <br>
                            CONSTABLE
                        </h2>

                    </a>

                </div>


                {{-- LEKHPAL --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/upsssc-lekhpal-recruitment') }}"
                        class="job-tile tile-green">

                        <h2>
                            UPSSSC Lekhpal
                            <br>
                            Post Apply Online
                        </h2>

                    </a>

                </div>


                {{-- RRB --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/rrb-group-d-recruitment') }}"
                        class="job-tile tile-purple">

                        <h2>
                            RRB Group D 2026
                            <br>
                            Apply Online
                        </h2>

                    </a>

                </div>


                {{-- CUET --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/nta-cuet-pg-online-form') }}"
                        class="job-tile tile-teal">

                        <h2>
                            NTA CUET PG
                            <br>
                            Online Form
                        </h2>

                    </a>

                </div>


                {{-- SSC CPO --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/ssc-cpo-answer-key') }}"
                        class="job-tile tile-gold">

                        <h2>
                            SSC CPO SI
                            <br>
                            Answer Key
                        </h2>

                    </a>

                </div>


                {{-- AGE CALCULATOR --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/age-calculator-for-exam') }}"
                        class="job-tile tile-red">

                        <h2>
                            AGE CALCULATOR
                            <br>
                            FOR ALL EXAM
                        </h2>

                    </a>

                </div>


                {{-- RRB ADMIT CARD --}}

                <div class="col-6 col-md-4 col-lg-3">

                    <a href="{{ url('/post/rrb-group-d-admit-card') }}"
                        class="job-tile tile-purple">

                        <h2>
                            RRB Group D
                            <br>
                            Admit Card 2025
                        </h2>

                    </a>

                </div>


            </div>



            {{-- QUICK LINKS --}}

            <div class="row g-3 mt-1">


                <div class="col-md-4">

                    <a href="{{ url('/category/tools') }}"
                        class="quick-btn quick-tools">

                        ▣ &nbsp; PHOTO RESIZER TOOLS

                    </a>

                </div>


                <div class="col-md-4">

                    <a href="#"
                        class="quick-btn quick-telegram">

                        ◉ &nbsp; Join Telegram Channel

                    </a>

                </div>


                <div class="col-md-4">

                    <a href="#"
                        class="quick-btn quick-whatsapp">

                        ◉ &nbsp; Join Whatsapp Channel

                    </a>

                </div>


            </div>

        </div>

    </section>



    {{-- =====================================================
         CATEGORY BOXES
    ====================================================== --}}

    <section class="bg-white py-3">

        <div class="container">

            <div class="row g-3">


                {{-- =================================================
                     LATEST JOBS
                ================================================== --}}

                <div class="col-lg-4">

                    <div class="job-section-card section-navy">


                        <div class="job-section-header header-navy">

                            <h2>
                                Latest Jobs
                            </h2>

                            <span class="job-badge">
                                Jobs
                            </span>

                        </div>


                        <div class="p-3">

                            <ul class="job-list">


                                <li>
                                    <a href="{{ url('/post/ibps-so-xvi-online-form') }}">
                                        IBPS SO XVI Online Form 2026 Apply For
                                        745 Posts
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/up-anganwadi-worker-recruitment') }}">
                                        UP Anganwadi Worker Recruitment 2026
                                        District Wise Vacancies
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/rrb-alp-recruitment') }}">
                                        RRB ALP Recruitment 2026 Apply Online
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/ssc-chsl-recruitment') }}">
                                        SSC CHSL Recruitment Online Application
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/banking-recruitment-2026') }}">
                                        Banking Recruitment 2026 Latest Vacancy
                                    </a>
                                </li>


                            </ul>


                            <div class="mt-2">

                                <a href="{{ url('/category/latest-government-jobs') }}"
                                    class="view-btn view-navy">

                                    View All Jobs →

                                </a>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     ADMIT CARD
                ================================================== --}}

                <div class="col-lg-4">

                    <div class="job-section-card section-green">


                        <div class="job-section-header header-green">

                            <h2>
                                Admit Card
                            </h2>

                            <span class="job-badge">
                                Exam
                            </span>

                        </div>


                        <div class="p-3">

                            <ul class="job-list">


                                <li>
                                    <a href="{{ url('/post/up-police-si-exam-city-details') }}">
                                        UP Police SI Exam City Details 2026
                                        Check City Slip
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/cbse-group-a-b-c-admit-card') }}">
                                        CBSE Group A, B, C Tier-II Exam Date 2026
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/rrb-ntpc-graduate-level-cbt-1') }}">
                                        RRB NTPC Graduate Level CBT-1 Exam City
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/rrb-group-d-admit-card') }}">
                                        RRB Group D Admit Card 2026 Download
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/ssc-cgl-admit-card') }}">
                                        SSC CGL Admit Card Latest Update
                                    </a>
                                </li>


                            </ul>


                            <div class="mt-2">

                                <a href="{{ url('/category/admit-card') }}"
                                    class="view-btn view-green">

                                    View All Admit Cards →

                                </a>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     ANSWER KEY
                ================================================== --}}

                <div class="col-lg-4">

                    <div class="job-section-card section-red">


                        <div class="job-section-header header-red">

                            <h2>
                                Answer Key
                            </h2>

                            <span class="job-badge">
                                Answer Key
                            </span>

                        </div>


                        <div class="p-3">

                            <ul class="job-list">


                                <li>
                                    <a href="{{ url('/post/rrb-group-d-answer-key') }}">
                                        RRB Group D Answer Key 2026
                                        Download PDF
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/rssb-ayush-officer-answer-key') }}">
                                        RSSB Ayush Officer Answer Key 2026
                                        Released
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/ssc-cpo-answer-key') }}">
                                        SSC CPO SI Answer Key 2026 Download
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/nta-answer-key') }}">
                                        NTA Exam Answer Key Latest Update
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/railway-answer-key') }}">
                                        Railway Exam Answer Key Download
                                    </a>
                                </li>


                            </ul>


                            <div class="mt-2">

                                <a href="{{ url('/category/answer-key') }}"
                                    class="view-btn view-red">

                                    View All Answer Keys →

                                </a>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     RESULTS
                ================================================== --}}

                <div class="col-lg-4">

                    <div class="job-section-card section-purple">


                        <div class="job-section-header header-purple">

                            <h2>
                                Latest Results
                            </h2>

                            <span class="job-badge">
                                Results
                            </span>

                        </div>


                        <div class="p-3">

                            <ul class="job-list">


                                <li>
                                    <a href="{{ url('/post/ssc-exam-result') }}">
                                        SSC Exam Result 2026 Latest Update
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/railway-result') }}">
                                        Railway Recruitment Result 2026
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/rrb-group-d-result') }}">
                                        RRB Group D Result Latest Information
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/ssc-chsl-result') }}">
                                        SSC CHSL Result Check Details
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/banking-exam-result') }}">
                                        Banking Exam Result 2026 Updates
                                    </a>
                                </li>


                            </ul>


                            <div class="mt-2">

                                <a href="{{ url('/category/government-exam-results') }}"
                                    class="view-btn view-purple">

                                    View All Results →

                                </a>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     SYLLABUS
                ================================================== --}}

                <div class="col-lg-4">

                    <div class="job-section-card section-gold">


                        <div class="job-section-header header-gold">

                            <h2>
                                Syllabus
                            </h2>

                            <span class="job-badge">
                                Syllabus
                            </span>

                        </div>


                        <div class="p-3">

                            <ul class="job-list">


                                <li>
                                    <a href="{{ url('/post/ssc-cgl-syllabus') }}">
                                        SSC CGL Syllabus & Exam Pattern
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/railway-syllabus') }}">
                                        Railway Exam Syllabus 2026
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/up-police-syllabus') }}">
                                        UP Police Exam Syllabus
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/banking-exam-syllabus') }}">
                                        Banking Exam Syllabus
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/teaching-exam-syllabus') }}">
                                        Teaching Exam Syllabus
                                    </a>
                                </li>


                            </ul>


                            <div class="mt-2">

                                <a href="{{ url('/category/syllabus') }}"
                                    class="view-btn view-gold">

                                    View All Syllabus →

                                </a>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     IMPORTANT DATES
                ================================================== --}}

                <div class="col-lg-4">

                    <div class="job-section-card section-teal">


                        <div class="job-section-header header-teal">

                            <h2>
                                Important Dates
                            </h2>

                            <span class="job-badge">
                                Dates
                            </span>

                        </div>


                        <div class="p-3">

                            <ul class="job-list">


                                <li>
                                    <a href="{{ url('/post/ssc-important-dates') }}">
                                        SSC Recruitment Important Dates
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/railway-important-dates') }}">
                                        Railway Recruitment Important Dates
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/banking-important-dates') }}">
                                        Banking Exam Important Dates
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/teaching-exam-dates') }}">
                                        Teaching Recruitment Dates
                                    </a>
                                </li>


                                <li>
                                    <a href="{{ url('/post/defence-exam-dates') }}">
                                        Defence Recruitment Dates
                                    </a>
                                </li>


                            </ul>


                            <div class="mt-2">

                                <a href="{{ url('/category/important-dates') }}"
                                    class="view-btn view-teal">

                                    View All Dates →

                                </a>

                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </section>



    {{-- =====================================================
         BOTTOM CTA
    ====================================================== --}}

    <section class="bg-white pb-3">

        <div class="container">

            <div class="bottom-job-banner">

                <div class="row align-items-center g-3">


                    <div class="col-lg-8">

                        <div class="d-flex align-items-start gap-3">

                            <div class="fs-3">
                                ▣
                            </div>

                            <div>

                                <h2>
                                    Looking for the latest Government Jobs?
                                </h2>

                                <p>
                                    Check the latest vacancies, recruitment
                                    notifications, admit cards, answer keys and results.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-lg-4 text-lg-end">

                        <a href="{{ url('/category/latest-government-jobs') }}"
                            class="bottom-job-btn">

                            View All Government Jobs →

                        </a>

                    </div>


                </div>

            </div>

        </div>

    </section>


</div>

@endsection