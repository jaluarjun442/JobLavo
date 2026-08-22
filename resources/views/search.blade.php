@extends('layouts.web')


@section('title')

@if($query)
Search Results for "{{ $query }}"
@else
Search Government Jobs
@endif

@endsection


@section('meta_description')

@if($query)
Search results for {{ $query }} - Find government jobs,
recruitment notifications, admit cards, results and exam updates.
@else
Search latest government jobs, recruitment notifications,
admit cards, answer keys and exam results.
@endif

@endsection


@section('meta_keywords',
'government jobs search, govt jobs, latest government jobs, recruitment, exam jobs'
)


@section('canonical', url('/search') . ($query ? '?q=' . urlencode($query) : ''))


@section('og_title')

@if($query)
Search Results for "{{ $query }}"
@else
Search Government Jobs
@endif

@endsection


@section('og_description',
'Search latest government jobs, recruitment notifications, admit cards, results and exam updates.'
)


@section('content')


<div class="bg-light py-4">

    <div class="container">


        {{-- =====================================================
             SEARCH HEADER
        ====================================================== --}}

        <section class="bg-white border rounded-2 shadow-sm mb-4">

            <div class="p-3 p-md-4">


                <h1 class="h3 fw-bold text-dark mb-3">

                    @if($query)

                    Search Results for:
                    <span style="color:#06245f;">
                        "{{ $query }}"
                    </span>

                    @else

                    Search Government Jobs

                    @endif

                </h1>


                {{-- SEARCH FORM --}}

                <form action="{{ url('/search') }}"
                    method="GET">


                    <label for="search-page-input"
                        class="visually-hidden">

                        Search government jobs

                    </label>


                    <div class="input-group input-group-lg">


                        <input type="search"
                            id="search-page-input"
                            name="q"
                            value="{{ $query }}"
                            class="form-control"
                            placeholder="Search government jobs..."
                            autocomplete="off">


                        <button type="submit"
                            class="btn"
                            style="background:#06245f;color:#fff;">

                            Search

                        </button>


                    </div>


                </form>


            </div>

        </section>



        {{-- =====================================================
             RESULTS
        ====================================================== --}}

        @if($query)


        <div class="row g-4">


            {{-- RESULTS LIST --}}

            <div class="col-lg-8">


                <section class="bg-white border rounded-2 shadow-sm">


                    <div class="d-flex
                                    align-items-center
                                    justify-content-between
                                    px-3 py-3 text-white"
                        style="background:#06245f;">


                        <h2 class="h5 fw-bold mb-0">

                            Search Results

                        </h2>


                        <span class="badge bg-white text-dark">

                            {{ $posts->total() }}
                            {{ $posts->total() == 1 ? 'Result' : 'Results' }}

                        </span>


                    </div>



                    <div class="p-3">


                        @forelse($posts as $post)


                        <article class="border-bottom py-3">


                            {{-- CATEGORY --}}

                            @if($post->category)

                            <div class="mb-2">

                                <a href="{{ url('/category/' . $post->category->slug) }}"
                                    class="badge text-decoration-none"
                                    style="background:#06245f;">

                                    {{ $post->category->name }}

                                </a>

                            </div>

                            @endif



                            {{-- TITLE --}}

                            <h3 class="h5 mb-2">


                                <a href="{{ url('/post/' . $post->slug) }}"
                                    class="text-decoration-none fw-semibold"
                                    style="color:#064fc7;">

                                    {{ $post->title }}

                                </a>


                            </h3>



                            {{-- DATE --}}

                            <div class="small text-secondary mb-2">

                                {{ $post->published_at
                                            ? $post->published_at->format('d M Y')
                                            : $post->created_at->format('d M Y') }}

                            </div>



                            {{-- EXCERPT --}}

                            @if($post->excerpt)

                            <p class="text-secondary mb-2">

                                {{ $post->excerpt }}

                            </p>

                            @endif



                            <a href="{{ url('/post/' . $post->slug) }}"
                                class="small fw-semibold text-decoration-none"
                                style="color:#06245f;">

                                Read More →

                            </a>


                        </article>


                        @empty


                        <div class="text-center py-5">


                            <div class="fs-1 mb-3">
                                🔎
                            </div>


                            <h3 class="h5 fw-bold">

                                No Results Found

                            </h3>


                            <p class="text-secondary mb-0">

                                We couldn't find any posts matching
                                "{{ $query }}".

                            </p>


                        </div>


                        @endforelse



                        {{-- PAGINATION --}}

                        @if($posts->hasPages())

                        <div class="pt-4">

                            {{ $posts->links() }}

                        </div>

                        @endif


                    </div>


                </section>


            </div>



            {{-- =================================================
                     SIDEBAR
                ================================================== --}}

            <div class="col-lg-4">


                <section class="bg-white border rounded-2 shadow-sm">


                    <div class="px-3 py-3 text-white"
                        style="background:#06245f;">

                        <h2 class="h6 fw-bold mb-0">

                            Popular Categories

                        </h2>

                    </div>


                    <div class="list-group list-group-flush">


                        <a href="{{ url('/category/latest-government-jobs') }}"
                            class="list-group-item list-group-item-action py-3">

                            Latest Government Jobs

                        </a>


                        <a href="{{ url('/category/admit-card') }}"
                            class="list-group-item list-group-item-action py-3">

                            Admit Card

                        </a>


                        <a href="{{ url('/category/answer-key') }}"
                            class="list-group-item list-group-item-action py-3">

                            Answer Key

                        </a>


                        <a href="{{ url('/category/government-exam-results') }}"
                            class="list-group-item list-group-item-action py-3">

                            Government Exam Results

                        </a>


                        <a href="{{ url('/category/syllabus') }}"
                            class="list-group-item list-group-item-action py-3">

                            Syllabus

                        </a>


                        <a href="{{ url('/category/railway-jobs') }}"
                            class="list-group-item list-group-item-action py-3">

                            Railway Jobs

                        </a>


                        <a href="{{ url('/category/banking-jobs') }}"
                            class="list-group-item list-group-item-action py-3">

                            Banking Jobs

                        </a>


                    </div>


                </section>


            </div>


        </div>


        @else


        {{-- =====================================================
                 EMPTY SEARCH STATE
            ====================================================== --}}

        <section class="bg-white border rounded-2 shadow-sm">

            <div class="text-center py-5 px-3">


                <div class="fs-1 mb-3">
                    🔎
                </div>


                <h2 class="h4 fw-bold text-dark">

                    Search for Government Jobs

                </h2>


                <p class="text-secondary mb-0">

                    Enter a job title, department, exam or keyword
                    to find relevant updates.

                </p>


            </div>

        </section>


        @endif


    </div>

</div>


@endsection