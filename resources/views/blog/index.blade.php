@extends('layouts.web')

@section('title')
    Latest Blog Posts, Tips & Career Guides | JobLavo
@endsection

@section('meta_description')
    Read useful career tips, government job guides, exam preparation tips and helpful information for job seekers on JobLavo.
@endsection

@section('canonical', route('blog.index'))

@section('content')

<section class="bg-white py-4">

    <div class="container">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}

        <div class="mb-4">

            <h1 class="h3 fw-bold mb-2">
                Latest Blogs & Career Guides
            </h1>

            <p class="text-secondary mb-0">
                Helpful tips, career guides and useful information for students and job seekers.
            </p>

        </div>


        {{-- =========================================================
             BLOG POSTS
        ========================================================== --}}

        @if($blogs->count())

            <div class="row g-4">

                @foreach($blogs as $blog)

                    <div class="col-md-6 col-lg-4">

                        <article class="card h-100 border-0 shadow-sm overflow-hidden">


                            {{-- IMAGE --}}

                            @if($blog->desktop_image)

                                <a
                                    href="{{ route('blog.show', $blog->slug) }}"
                                >

                                    <picture>

                                        @if($blog->mobile_image)

                                            <source
                                                media="(max-width: 767px)"
                                                srcset="{{ asset($blog->mobile_image) }}"
                                            >

                                        @endif

                                        <img
                                            src="{{ asset($blog->desktop_image) }}"
                                            alt="{{ $blog->title }}"
                                            class="img-fluid w-100"
                                            width="1280"
                                            height="720"
                                            loading="lazy"
                                        >

                                    </picture>

                                </a>

                            @endif


                            {{-- CONTENT --}}

                            <div class="card-body p-3 p-md-4">

                                <div class="small text-muted mb-2">

                                    @if($blog->published_date)

                                        {{ $blog->published_date->format('d M Y') }}

                                    @endif

                                    @if($blog->published_by)

                                        <span class="mx-1">
                                            ·
                                        </span>

                                        <a href="{{ route('manisha_jalu') }}" > {{ $blog->published_by }} </a>

                                    @endif

                                </div>


                                <h2 class="h5 fw-semibold mb-2">

                                    <a
                                        href="{{ route('blog.show', $blog->slug) }}"
                                        class="text-decoration-none text-dark"
                                    >

                                        {{ $blog->title }}

                                    </a>

                                </h2>


                                <p class="text-secondary mb-3">

                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($blog->content),
                                        150
                                    ) }}

                                </p>


                                <a
                                    href="{{ route('blog.show', $blog->slug) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >

                                    Read More →

                                </a>

                            </div>

                        </article>

                    </div>

                @endforeach

            </div>


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}

            @if($blogs->hasPages())

                <div class="mt-5 d-flex justify-content-center">

                    {{ $blogs->links() }}

                </div>

            @endif


        @else

            <div class="text-center py-5">

                <h2 class="h5 fw-semibold">
                    No Blog Posts Available
                </h2>

                <p class="text-muted mb-0">
                    New tips and guides will be published here soon.
                </p>

            </div>

        @endif

    </div>

</section>

@endsection