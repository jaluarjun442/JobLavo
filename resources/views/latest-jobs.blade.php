@extends('layouts.web')


@section(
    'title',
    'Latest Government Jobs & Recruitment Updates'
)


@section(
    'meta_description',
    'Check the latest government jobs, recruitment notifications and job updates. Browse recent government job posts with complete details.'
)


@section('canonical', url('/latest-jobs'))


@section('content')


<div class="bg-light py-4">

    <div class="container">


        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

        <nav
            aria-label="breadcrumb"
            class="mb-3">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">

                    <a href="{{ url('/') }}">

                        Home

                    </a>

                </li>


                <li
                    class="breadcrumb-item active"
                    aria-current="page">

                    Latest Jobs

                </li>

            </ol>

        </nav>



        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div
            class="bg-white border rounded-2 shadow-sm p-4 mb-4">

            <h1 class="h2 fw-bold mb-2">

                Latest Government Jobs

            </h1>


            <p class="text-secondary mb-0">

                Latest government job notifications,
                recruitment updates and new job opportunities.

            </p>

        </div>



        {{-- =====================================================
             JOB LIST
        ====================================================== --}}

        <div
            class="bg-white border rounded-2 shadow-sm">


            <div
                class="px-3 py-3 text-white header-navy">

                <h2 class="h5 fw-bold mb-0">

                    Latest Job Updates

                </h2>

            </div>


            <div class="p-3">


                @forelse($posts as $post)


                    <article
                        class="latest-job-item">


                        <h2 class="h5 mb-2">

                            <a
                                href="{{ route(
                                    'post',
                                    $post->slug
                                ) }}"
                                class="text-decoration-none">

                                {{ $post->title }}

                            </a>

                        </h2>



                        <div
                            class="small text-secondary mb-2">


                            @if($post->published_at)

                                {{ $post->published_at->format(
                                    'd M Y'
                                ) }}

                            @else

                                {{ $post->created_at->format(
                                    'd M Y'
                                ) }}

                            @endif



                            @if($post->category)

                                <span class="ms-2">

                                    •
                                    {{ $post->category->name }}

                                </span>

                            @endif


                        </div>



                        @if(
                            $post->short_description
                            ?: $post->excerpt
                        )

                            <p class="text-secondary mb-0">

                                {{
                                    $post->short_description
                                    ?: $post->excerpt
                                }}

                            </p>

                        @endif


                    </article>


                @empty


                    <div class="text-center py-5">

                        <h3 class="h5">

                            No Jobs Available

                        </h3>


                        <p class="text-muted mb-0">

                            There are currently no
                            published job updates.

                        </p>

                    </div>


                @endforelse



                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                @if($posts->hasPages())

                    <div class="mt-4">

                        {{ $posts->links() }}

                    </div>

                @endif


            </div>

        </div>


    </div>

</div>


@endsection