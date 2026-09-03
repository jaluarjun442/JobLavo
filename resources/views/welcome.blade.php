@extends('layouts.web')


@section('title')
    Latest Government Jobs, Admit Card, Results & Government Job Updates
@endsection


@section('meta_description')
    Get the latest government jobs, recruitment notifications, admit cards, exam results, answer keys and other government job updates.
@endsection


@section('canonical', url('/'))


@section('content')


{{-- =========================================================
     SMALL HOME CATEGORY TILES
========================================================= --}}

@if($homeTileCategories->count())

<section class="bg-white py-3">

    <div class="container">

        <div class="row g-3">

            @foreach($homeTileCategories as $category)

                <div class="col-6 col-md-4 col-lg-3 tile_category_h1">

                    <a
                        href="{{ route(
                            'category',
                            $category->slug
                        ) }}"
                        class="job-tile
                        @if($loop->iteration % 4 === 1)
                            tile-navy
                        @elseif($loop->iteration % 4 === 2)
                            tile-green
                        @elseif($loop->iteration % 4 === 3)
                            tile-purple
                        @else
                            tile-teal
                        @endif"
                    >

                        <h1>
                            {{ $category->name }}
                        </h1>

                    </a>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif



{{-- =========================================================
     LARGE CATEGORY SECTIONS
========================================================= --}}

@if($homeLargeCategories->count())

<section class="bg-white py-3">

    <div class="container">

        <div class="row g-3">

            @foreach($homeLargeCategories as $category)

                <div class="col-lg-4">


                    <section class="job-section-card">


                        {{-- SECTION HEADER --}}

                        <div
                            class="job-section-header
                            @if($loop->iteration % 4 === 1)
                                header-navy
                            @elseif($loop->iteration % 4 === 2)
                                header-green
                            @elseif($loop->iteration % 4 === 3)
                                header-purple
                            @else
                                header-teal
                            @endif"
                        >

                            <h2>

                                {{ $category->name }}

                            </h2>


                        </div>



                        {{-- JOB LIST --}}

                        <div class="p-3">


                            @if($category->posts->count())


                                <ul class="job-list">


                                    @foreach(
                                        $category->posts
                                        as $post
                                    )

                                        <li>

                                            <a
                                                href="{{ route(
                                                    'post',
                                                    $post->slug
                                                ) }}"
                                            >

                                                {{ $post->title }}

                                            </a>

                                        </li>

                                    @endforeach


                                </ul>


                            @else


                                <p
                                    class="text-muted mb-3">

                                    No posts available.

                                </p>


                            @endif



                            {{-- VIEW ALL --}}

                            <a
                                href="{{ route(
                                    'category',
                                    $category->slug
                                ) }}"
                                class="view-btn"
                            >

                                View All Jobs →

                            </a>


                        </div>


                    </section>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif



{{-- =========================================================
     ALL LATEST UPDATES
========================================================= --}}

<section class="bg-white py-3">

    <div class="container">


        <section class="job-section-card">


            {{-- HEADER --}}

            <div class="job-section-header header-navy">

                <h2>

                    Latest Job Updates

                </h2>


            </div>



            {{-- CONTENT --}}

            <div class="p-3">


                @if($latestPosts->count())


                    <ul class="job-list">


                        @foreach($latestPosts as $post)

                            <li>

                                <a
                                    href="{{ route(
                                        'post',
                                        $post->slug
                                    ) }}"
                                >

                                    {{ $post->title }}

                                </a>


                                {{-- MULTIPLE CATEGORIES --}}

                                @if(
                                    $post->categories &&
                                    $post->categories->count()
                                )

                                    <span
                                        class="small text-muted ms-1">

                                        —

                                    </span>

                                    @foreach(
                                        $post->categories
                                        as $postCategory
                                    )

                                        <a
                                            href="{{ route(
                                                'category',
                                                $postCategory->slug
                                            ) }}"
                                            class="small text-muted text-decoration-none ms-1"
                                        >

                                            {{ $postCategory->name }}

                                        </a>

                                        @if(!$loop->last)

                                            <span
                                                class="small text-muted">

                                                ,

                                            </span>

                                        @endif

                                    @endforeach

                                @endif


                            </li>

                        @endforeach


                    </ul>


                @else


                    <p class="text-muted mb-3">

                        No latest updates available.

                    </p>


                @endif



                {{-- VIEW ALL --}}

                <a
                    href="{{ route('latest.jobs') }}"
                    class="view-btn"
                >

                    View All Jobs →

                </a>


            </div>


        </section>

    </div>

</section>
{{-- =========================================================
     LATEST BLOGS
========================================================= --}}

@if($latestBlogs->count())

<section class="py-4">

    <div class="container">

        {{-- =====================================================
             SECTION HEADER
        ====================================================== --}}

        <div class="mb-3">

            <h2 class="h4 fw-bold mb-1">
                Latest Blogs
            </h2>

            <p class="text-muted mb-0">
                Practical tips, helpful guides and useful career information for job seekers.
            </p>

        </div>


        {{-- =====================================================
             BLOG CARDS
        ====================================================== --}}

        <div class="row g-3">

            @foreach($latestBlogs as $blog)

                <div class="col-12 col-md-6 col-lg-4">

                    <article class="card h-100 border-0 shadow-sm overflow-hidden">

                        {{-- =================================================
                             BLOG IMAGE
                        ================================================== --}}

                        @if($blog->desktop_image)

                            <a
                                href="{{ route('blog.show', $blog->slug) }}"
                            >

                                <img
                                    src="{{ asset($blog->desktop_image) }}"
                                    alt="{{ $blog->title }}"
                                    class="card-img-top"
                                    width="400"
                                    height="225"
                                    loading="lazy"
                                >

                            </a>

                        @endif


                        {{-- =================================================
                             BLOG CONTENT
                        ================================================== --}}

                        <div class="card-body d-flex flex-column">

                            <h3 class="h6 fw-bold mb-2">

                                <a
                                    href="{{ route('blog.show', $blog->slug) }}"
                                    class="text-decoration-none text-dark-blog"
                                >

                                    {{ $blog->title }}

                                </a>

                            </h3>


                            {{-- =================================================
                                 PUBLISHED DATE
                            ================================================== --}}

                            @if($blog->published_date)

                                <div class="small text-muted mt-auto">

                                    {{ $blog->published_date->format('d M Y') }}

                                </div>

                            @endif

                        </div>

                    </article>

                </div>

            @endforeach

        </div>


        {{-- =====================================================
             VIEW ALL BLOGS
        ====================================================== --}}

        <div class="text-center mt-4">

            <a
                href="{{ route('blog.index') }}"
                class="btn btn-outline-primary"
            >
                View All Blogs →
            </a>

        </div>

    </div>

</section>

@endif
{{-- =========================================================
     HOME INFORMATIONAL CONTENT
========================================================= --}}

<section class="bg-white py-5">

    <div class="container">

        <div class="job-section-card">

            <div class="job-section-header header-navy">

                <h2>
                    Sarkari Jobs & Government Job Updates in India
                </h2>

            </div>


            <div class="p-4">


                <p>
                    Government jobs in India continue to be an important career
                    choice for candidates looking for stable employment and
                    long-term career opportunities. Every year, central and
                    state government departments, public sector organizations,
                    universities, courts, railways, banks, defence departments
                    and other government institutions release recruitment
                    notifications for different qualifications and job roles.
                    Candidates can find opportunities ranging from 10th pass
                    and 12th pass jobs to graduate, postgraduate, technical,
                    engineering and professional vacancies.
                </p>


                <p>
                    Finding a suitable Sarkari Naukri requires regular attention
                    because recruitment notifications are published throughout
                    the year. Each government recruitment can have different
                    eligibility requirements, application dates, examination
                    schedules, selection procedures and official application
                    links. Keeping track of these details can help candidates
                    avoid missing an important opportunity or an application
                    deadline.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    How Indians Secure Their Future with Sarkari Jobs
                </h3>


                <p>
                    For many Indian candidates, a government job is considered
                    an attractive career option because of its structured
                    employment system, regular salary, opportunities for
                    professional growth and various benefits provided according
                    to the rules of the concerned organization. Government
                    recruitment is available in different sectors and at
                    different educational levels, which allows candidates to
                    search for opportunities according to their qualification,
                    skills and interests.
                </p>


                <p>
                    Candidates preparing for government examinations often
                    follow recruitment notifications, admit cards, examination
                    schedules, answer keys and results as part of their
                    preparation journey. Staying updated with these stages is
                    important because an application does not end with
                    submitting the form. Candidates may also need to download
                    an admit card, appear for an examination, check an answer
                    key, participate in further selection stages and finally
                    check the result or merit list.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    Benefits of Government Jobs in India
                </h3>


                <p>
                    Government employment is commonly preferred for its
                    structured career path and the benefits available under
                    the applicable service rules. Depending on the department
                    and position, employees may receive a regular salary,
                    allowances, leave benefits, promotion opportunities and
                    retirement-related benefits. The exact benefits vary
                    according to the organization, post, service rules and
                    applicable government regulations.
                </p>


                <p>
                    Another advantage is the wide range of career options.
                    Government vacancies are not limited to one particular
                    qualification or profession. Recruitment may be announced
                    for administrative positions, clerical work, teaching,
                    healthcare, engineering, technical roles, banking,
                    railways, police, defence, research and many other areas.
                    This gives candidates the opportunity to look for
                    positions that match their educational background.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    Find Latest Sarkari Naukri and Government Jobs
                </h3>


                <p>
                    Our platform brings together government job updates and
                    recruitment information in one place so candidates can
                    regularly check newly published opportunities. Job seekers
                    can browse recruitment updates, explore different
                    categories and review important information before visiting
                    the official website of the recruiting organization.
                </p>


                <p>
                    Government recruitment information may include details
                    such as the name of the organization, number of vacancies,
                    educational qualification, age requirements, application
                    dates, selection process, examination details and official
                    application links. Candidates should always read the
                    original recruitment notification carefully before applying
                    because eligibility conditions and other requirements can
                    differ from one recruitment to another.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    Government Jobs for Different Qualifications
                </h3>


                <p>
                    Government recruitment opportunities are available for
                    candidates with different educational qualifications.
                    Depending on the recruitment, vacancies may be available
                    for 10th pass, 12th pass, ITI, diploma, undergraduate,
                    graduate, postgraduate and professional candidates.
                    Technical and specialized recruitment may also require
                    specific educational qualifications, experience or
                    professional registration.
                </p>


                <p>
                    Instead of applying for every vacancy, candidates should
                    focus on recruitment notifications for which they meet the
                    prescribed eligibility requirements. Checking the
                    qualification, age limit, experience requirements and
                    application deadline before starting the application can
                    help candidates save time and avoid submitting an
                    application for an unsuitable post.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    Central and State Government Job Updates
                </h3>


                <p>
                    Government vacancies can be announced by central
                    government departments as well as state government
                    organizations. Candidates may therefore find recruitment
                    opportunities from different states and regions along with
                    central government organizations. Depending on the post,
                    the selection process may include written examinations,
                    skill tests, interviews, physical tests, document
                    verification or other stages.
                </p>


                <p>
                    Candidates looking for state government jobs can regularly
                    check updates relevant to their state, while those
                    interested in central government recruitment can follow
                    notifications issued by central departments and
                    organizations. Keeping these updates organized makes it
                    easier to identify new vacancies and important recruitment
                    dates.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    How to Apply for a Government Job?
                </h3>


                <p>
                    The application process depends on the recruiting
                    organization. In many cases, candidates need to visit the
                    official recruitment website, read the notification,
                    confirm their eligibility, complete the online application
                    form, upload the required documents and pay the applicable
                    fee if required. Some organizations may use other
                    application methods such as email or offline submission
                    depending on the recruitment.
                </p>


                <p>
                    Before submitting an application, candidates should verify
                    the official notification and make sure that all details
                    entered in the application form are correct. Important
                    dates should also be checked carefully because applications
                    submitted after the prescribed deadline may not be
                    accepted.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    Admit Card, Answer Key and Result Updates
                </h3>


                <p>
                    Recruitment information does not stop after the application
                    deadline. Candidates may need to follow several subsequent
                    updates including examination dates, admit cards, answer
                    keys, interview schedules, document verification notices
                    and final results. Checking these updates on time is
                    important for candidates who are actively participating in
                    a recruitment process.
                </p>


                <p>
                    An admit card generally contains important examination
                    information such as the examination date, venue and
                    candidate details. After an examination, the recruiting
                    organization may publish an answer key or other related
                    information before announcing the result. Candidates
                    should rely on the official notification and website for
                    the final and authoritative information.
                </p>


                <h3 class="h5 fw-bold mt-4 mb-3">
                    Tips for Government Job Preparation
                </h3>


                <p>
                    Candidates preparing for Sarkari Naukri examinations can
                    begin by understanding the syllabus and selection process
                    of the target recruitment. Maintaining a regular study
                    routine, practicing previous examination questions,
                    improving general awareness and reviewing important
                    subjects can help candidates organize their preparation.
                    The preparation strategy should be adjusted according to
                    the examination pattern and requirements of the particular
                    recruitment.
                </p>


                <p>
                    It is also useful to keep track of application deadlines
                    and examination-related announcements while preparing.
                    Candidates should save important notifications and check
                    official updates regularly so that they do not miss a
                    required step in the recruitment process.
                </p>



            </div>

        </div>

    </div>

</section>

@endsection