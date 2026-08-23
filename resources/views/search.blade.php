@extends('layouts.web')


@section(
    'title',
    $query
        ? 'Search Results for "' . $query . '"'
        : 'Search Government Jobs'
)


@section(
    'meta_description',
    $query
        ? 'Search government jobs and recruitment updates for ' . $query . '. Find the latest job notifications and updates.'
        : 'Search the latest government jobs, recruitment notifications and government job updates.'
)


@section(
    'canonical',
    url('/search' . ($query ? '?q=' . urlencode($query) : ''))
)


@section('content')

<div class="bg-light py-4">

    <div class="container">


        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

        <nav
            aria-label="breadcrumb"
            class="mb-3">

            <ol class="breadcrumb mb-0">

                <li class="breadcrumb-item">

                    <a
                        href="{{ url('/') }}"
                        class="text-decoration-none">

                        Home

                    </a>

                </li>

                <li
                    class="breadcrumb-item active"
                    aria-current="page">

                    Search

                </li>

            </ol>

        </nav>



        <div class="row g-4">


            <div class="col-lg-8">


                <section
                    class="bg-white border rounded-2 shadow-sm">


                    {{-- =================================================
                         HEADER
                    ================================================== --}}

                    <div
                        class="px-3 py-3 text-white"
                        style="background:#06245f;">

                        <h1 class="h5 fw-bold mb-0">

                            @if($query)

                                Search Results for:
                                "{{ $query }}"

                            @else

                                Search Government Jobs

                            @endif

                        </h1>

                    </div>



                    {{-- =================================================
                         SEARCH FORM
                    ================================================== --}}

                    <div class="p-3 border-bottom">

                        <form
                            action="{{ route('search') }}"
                            method="GET">

                            <label
                                for="search-page-input"
                                class="visually-hidden">

                                Search government jobs

                            </label>


                            <div class="input-group">

                                <input
                                    type="search"
                                    id="search-page-input"
                                    name="q"
                                    value="{{ $query }}"
                                    class="form-control"
                                    placeholder="Search jobs..."
                                    autocomplete="off">


                                <button
                                    type="submit"
                                    class="btn"
                                    aria-label="Search jobs"
                                    style="
                                        background:#06245f;
                                        color:#fff;
                                    ">

                                    Search

                                </button>

                            </div>

                        </form>

                    </div>



                    {{-- =================================================
                         RESULTS
                    ================================================== --}}

                    <div class="p-3">


                        @if(!$query)

                            <div class="text-center py-5">

                                <h2 class="h5 fw-bold">

                                    Search Government Jobs

                                </h2>

                                <p class="text-muted mb-0">

                                    Enter a job title, department,
                                    exam or keyword to search.

                                </p>

                            </div>


                        @else


                            @forelse($posts as $post)


                                <article class="category-job-item border-bottom py-3">


                                    {{-- =================================================
                                         TITLE
                                    ================================================== --}}

                                    <h2 class="h5 mb-2">

                                        <a
                                            href="{{ route(
                                                'post',
                                                $post->slug
                                            ) }}"
                                            class="category-job-title">

                                            {{ $post->title }}

                                        </a>

                                    </h2>



                                    {{-- =================================================
                                         META
                                    ================================================== --}}

                                    <div
                                        class="small text-secondary mb-2">


                                        {{-- DATE --}}

                                        @if($post->published_at)

                                            {{ $post->published_at->format(
                                                'd M Y'
                                            ) }}

                                        @else

                                            {{ $post->created_at->format(
                                                'd M Y'
                                            ) }}

                                        @endif



                                        {{-- MULTIPLE CATEGORIES --}}

                                        @if(
                                            $post->categories &&
                                            $post->categories->count()
                                        )

                                            <span class="ms-2">

                                                •

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
                                                    class="text-decoration-none ms-1"
                                                    style="color:#064fc7;">

                                                    {{ $postCategory->name }}

                                                </a>


                                                @if(!$loop->last)

                                                    <span>

                                                        ,

                                                    </span>

                                                @endif

                                            @endforeach

                                        @endif


                                    </div>



                                    {{-- =================================================
                                         DESCRIPTION
                                    ================================================== --}}

                                    @if(
                                        $post->short_description
                                        ?: $post->excerpt
                                    )

                                        <p
                                            class="text-secondary mb-2">

                                            {{
                                                $post->short_description
                                                ?: $post->excerpt
                                            }}

                                        </p>

                                    @endif



                                    {{-- =================================================
                                         READ MORE
                                    ================================================== --}}

                                    <a
                                        href="{{ route(
                                            'post',
                                            $post->slug
                                        ) }}"
                                        class="small fw-semibold text-decoration-none"
                                        style="color:#06245f;">

                                        Read More →

                                    </a>


                                </article>


                            @empty


                                {{-- =================================================
                                     NO RESULTS
                                ================================================== --}}

                                <div
                                    class="text-center py-5">


                                    <h2 class="h5 fw-bold">

                                        No Results Found

                                    </h2>


                                    <p class="text-muted mb-3">

                                        No published jobs were found
                                        for "{{ $query }}".

                                    </p>


                                    <a
                                        href="{{ url('/') }}"
                                        class="btn btn-primary">

                                        Back to Home

                                    </a>


                                </div>


                            @endforelse





                        {{-- PAGINATION --}}
                        @if($posts->hasPages())

                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">

                                {{-- PREVIOUS --}}

                                <div>

                                    @if($posts->onFirstPage())

                                        <span class="btn btn-outline-secondary disabled">
                                            ← Previous
                                        </span>

                                    @else

                                        <a
                                            href="{{ $posts->previousPageUrl() }}"
                                            class="btn btn-outline-primary">

                                            ← Previous

                                        </a>

                                    @endif

                                </div>



                                {{-- NEXT --}}

                                <div>

                                    @if($posts->hasMorePages())

                                        <a
                                            href="{{ $posts->nextPageUrl() }}"
                                            class="btn btn-primary">

                                            Next →

                                        </a>

                                    @else

                                        <span class="btn btn-outline-secondary disabled">
                                            Next →
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @endif


                        @endif


                    </div>

                </section>

            </div>




            {{-- =====================================================
                 SIDEBAR
            ====================================================== --}}

            <div class="col-lg-4">

                @include(
                    'layouts.partials.sidebar'
                )

            </div>


        </div>

    </div>

</div>

@endsection