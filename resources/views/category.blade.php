@extends('layouts.web')


@section('title',
$category->seo_title
?: $category->name . ' - Latest Government Jobs & Updates'
)


@section('meta_description',
$category->meta_description
?: 'Latest ' . $category->name . ', government job notifications, recruitment updates, admit cards, results and important exam information.'
)


@section('meta_keywords',
$category->meta_keywords
?: strtolower($category->name . ', government jobs, govt jobs, recruitment, latest jobs, exam updates')
)


@section('canonical', url('/category/' . $category->slug))


@section('og_title',
$category->seo_title
?: $category->name . ' - Latest Government Jobs & Updates'
)


@section('og_description',
$category->meta_description
?: 'Latest ' . $category->name . ' and important government job updates.'
)


@section('content')


<div class="bg-light py-4">


    <div class="container">


        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

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

                    {{ $category->name }}

                </li>


            </ol>

        </nav>



        {{-- =====================================================
             CATEGORY INTRO
        ====================================================== --}}

        <section class="bg-white border rounded-2 shadow-sm mb-4">


            <div class="p-3 p-md-4">


                <h1 class="h3 fw-bold text-dark mb-2">

                    {{ $category->name }}

                </h1>


                @if($category->description)

                <p class="text-secondary mb-0">

                    {{ $category->description }}

                </p>

                @else

                <p class="text-secondary mb-0">

                    Latest {{ $category->name }},
                    government job notifications, recruitment updates,
                    exam information, admit cards, answer keys and results.

                </p>

                @endif


            </div>


        </section>



        {{-- =====================================================
             MAIN CONTENT
        ====================================================== --}}

        <div class="row g-4">


            {{-- =================================================
                 POSTS
            ================================================== --}}

            <div class="col-lg-8">


                <section class="bg-white border rounded-2 shadow-sm">


                    {{-- SECTION HEADER --}}

                    <div class="d-flex
                                align-items-center
                                justify-content-between
                                px-3 py-3
                                text-white"
                        style="background:#06245f;">


                        <h2 class="h5 fw-bold mb-0">

                            Latest {{ $category->name }}

                        </h2>


                        <span class="badge bg-white text-dark">

                            {{ $posts->total() }}
                            {{ $posts->total() == 1 ? 'Post' : 'Posts' }}

                        </span>


                    </div>



                    {{-- POST LIST --}}

                    <div class="p-3">


                        @forelse($posts as $post)


                        <article class="border-bottom py-3">


                            {{-- POST TITLE --}}

                            <h3 class="h5 mb-2">


                                <a href="{{ url('/post/' . $post->slug) }}"
                                    class="text-decoration-none fw-semibold"
                                    style="color:#064fc7;">

                                    {{ $post->title }}

                                </a>


                            </h3>



                            {{-- DATE --}}

                            <div class="small text-secondary mb-2">


                                @if($post->published_at)

                                {{ $post->published_at->format('d M Y') }}

                                @else

                                {{ $post->created_at->format('d M Y') }}

                                @endif


                            </div>



                            {{-- EXCERPT --}}

                            @if($post->excerpt)


                            <p class="text-secondary mb-2">

                                {{ $post->excerpt }}

                            </p>


                            @endif



                            {{-- READ MORE --}}

                            <a href="{{ url('/post/' . $post->slug) }}"
                                class="small fw-semibold text-decoration-none"
                                style="color:#06245f;">

                                Read More →

                            </a>


                        </article>


                        @empty


                        {{-- NO POSTS --}}

                        <div class="text-center py-5">


                            <div class="mb-3">

                                <span class="fs-1">
                                    📄
                                </span>

                            </div>


                            <h3 class="h5 fw-bold text-dark">

                                No Posts Available

                            </h3>


                            <p class="text-secondary mb-3">

                                There are currently no published posts
                                in this category.

                            </p>


                            <a href="{{ url('/') }}"
                                class="btn btn-primary">

                                Back to Home

                            </a>


                        </div>


                        @endforelse



                        {{-- =================================================
                             PAGINATION
                        ================================================== --}}

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


                {{-- SEARCH BOX --}}

                <section class="bg-white border rounded-2 shadow-sm mb-4">


                    <div class="px-3 py-3 text-white"
                        style="background:#06245f;">


                        <h2 class="h6 fw-bold mb-0">

                            Search Jobs

                        </h2>


                    </div>



                    <div class="p-3">


                        <form action="{{ url('/search') }}"
                            method="GET">


                            <label for="category-search"
                                class="visually-hidden">

                                Search government jobs

                            </label>


                            <div class="input-group">


                                <input type="search"
                                    id="category-search"
                                    name="q"
                                    value="{{ request('q') }}"
                                    class="form-control"
                                    placeholder="Search jobs..."
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



                {{-- POPULAR CATEGORIES --}}

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


                        <a href="{{ url('/category/important-dates') }}"
                            class="list-group-item list-group-item-action py-3">

                            Important Dates

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



        {{-- =====================================================
             SEO CONTENT
        ====================================================== --}}

        @if($category->content)


        <section class="bg-white border rounded-2 shadow-sm mt-4">


            <div class="p-3 p-md-4">


                <h2 class="h5 fw-bold text-dark mb-3">

                    About {{ $category->name }}

                </h2>


                <div class="text-secondary">

                    {!! $category->content !!}

                </div>


        </div>


        </section>


        @endif


    </div>


</div>


@endsection