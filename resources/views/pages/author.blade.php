@extends('layouts.web')


@section('title', $pageTitle . ' | Government Jobs & Updates')


@section('meta_description',
$metaDescription
?? ($pageTitle . ' - Read important information, policies and details about our government jobs website.')
)


@section('meta_keywords',
$metaKeywords
?? strtolower($pageTitle . ', government jobs, govt jobs, recruitment')
)




@section('og_title', $pageTitle)


@section(
'og_description',
$metaDescription
?? ($pageTitle . ' - Government Jobs & Updates')
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

                    {{ $pageTitle }}

                </li>

            </ol>

        </nav>



        {{-- PAGE CONTENT --}}

        <article class="bg-white border rounded-2 shadow-sm">


            {{-- HEADER --}}

            <!-- <div class="p-3 p-md-4 border-bottom">


                <h1 class="h2 fw-bold text-dark mb-0">

                    {{ $pageTitle }}

                </h1>


            </div> -->



            {{-- CONTENT --}}

            <div class="p-3 p-md-4">


                <div class="author-profile">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                            style="width:72px;height:72px;font-size:28px;">
                            M
                        </div>

                        <div>
                            <h1 class="h2 fw-bold text-dark mb-1">
                                Manisha Jalu
                            </h1>

                            <p class="text-muted mb-0">
                                Author &amp; Editor at JobLavo
                            </p>
                        </div>
                    </div>


                    <p>
                        Manisha Jalu is an author and editor at JobLavo who covers government
                        jobs, recruitment notifications, examination updates, admit cards,
                        results and other career-related information.
                    </p>

                    <p>
                        Her work focuses on organizing important recruitment information into
                        a clear and easy-to-understand format so that job seekers can quickly
                        find details such as vacancies, eligibility requirements, important
                        dates, application fees, selection processes and application links.
                    </p>


                    <h2 class="h4 fw-bold text-dark mt-4 mb-3">
                        What Manisha Covers
                    </h2>

                    <p>
                        Manisha regularly works on content related to different types of
                        government recruitment and examination updates. Her coverage may
                        include Central Government jobs, State Government jobs, banking
                        recruitment, railway jobs, teaching positions, defence recruitment,
                        public sector opportunities and other competitive examination updates.
                    </p>

                    <ul>
                        <li>Government job recruitment notifications</li>
                        <li>Latest vacancy and application updates</li>
                        <li>Admit cards and examination information</li>
                        <li>Government exam results and answer keys</li>
                        <li>Eligibility, age limit and application details</li>
                        <li>Selection process and salary information</li>
                        <li>Important links and official application websites</li>
                    </ul>


                    <h2 class="h4 fw-bold text-dark mt-4 mb-3">
                        Our Approach to Job Updates
                    </h2>

                    <p>
                        JobLavo aims to make recruitment information easier to understand by
                        presenting important details in an organized format. When preparing
                        an article, Manisha focuses on the information available in recruitment
                        notifications and official sources and presents the relevant details
                        for candidates in a simpler format.
                    </p>

                    <p>
                        Readers are encouraged to check the official recruitment notification
                        and the concerned organization's official website before submitting
                        an application. Official notifications remain the final source for
                        eligibility conditions, dates, vacancies and other recruitment rules.
                    </p>


                    <h2 class="h4 fw-bold text-dark mt-4 mb-3">
                        Information for Job Seekers
                    </h2>

                    <p>
                        Recruitment details can sometimes change after an article is published.
                        Candidates should therefore verify the latest information, including
                        application deadlines, eligibility requirements, examination schedules
                        and other important instructions, before taking any action.
                    </p>

                    <p>
                        JobLavo provides recruitment information for informational purposes and
                        helps readers locate relevant official sources. Candidates should use
                        the official website or notification provided in each recruitment
                        article for the final application and verification.
                    </p>


                    <h2 class="h4 fw-bold text-dark mt-4 mb-3">
                        About JobLavo
                    </h2>

                    <p>
                        JobLavo is a government jobs and career information website covering
                        recruitment notifications, admit cards, results, answer keys and other
                        examination-related updates. The website is designed to help job
                        seekers find important recruitment information in one place.
                    </p>

                </div>


            </div>


        </article>


    </div>

</div>

@endsection