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

                <div class="col-6 col-md-4 col-lg-3">

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

                        <h2>
                            {{ $category->name }}
                        </h2>

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


                                @if($post->category)

                                    <span
                                        class="small text-muted ms-1">

                                        —
                                        {{ $post->category->name }}

                                    </span>

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



@endsection