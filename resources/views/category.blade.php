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
    ?: strtolower(
        $category->name .
        ', government jobs, govt jobs, recruitment, latest jobs, exam updates'
    )
)


@section(
    'canonical',
    url('/category/' . $category->slug)
)


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


                @if($category->parent)

                    <li class="breadcrumb-item">

                        <a
                            href="{{ route(
                                'category',
                                $category->parent->slug
                            ) }}"
                            class="text-decoration-none">

                            {{ $category->parent->name }}

                        </a>

                    </li>

                @endif


                <li
                    class="breadcrumb-item active"
                    aria-current="page">

                    {{ $category->name }}

                </li>


            </ol>

        </nav>



        <section
            class="bg-white border rounded-2 shadow-sm mb-4">

            <div class="p-3 p-md-4">

                <h1 class="h3 fw-bold text-dark mb-2">

                    {{ $category->name }}

                </h1>


                @if($category->description)

                    <p class="text-secondary mb-0">

                        {{ $category->description }}

                    </p>

                @else

                   

                @endif

            </div>

        </section>



        @if($category->children->count())

            <div class="subcategory-grid mb-4">

                @foreach($category->children as $subCategory)

                    @if($subCategory->status)

                        <a
                            href="{{ route(
                                'category',
                                $subCategory->slug
                            ) }}"
                            class="subcategory-tile">

                            {{ $subCategory->name }}

                        </a>

                    @endif

                @endforeach

            </div>

        @endif



        <div class="row g-4">



            <div class="col-lg-8">

                <section
                    class="bg-white border rounded-2 shadow-sm">


                    {{-- SECTION HEADER --}}

                    <div
                        class="d-flex
                               align-items-center
                               justify-content-between
                               px-3 py-3
                               text-white"
                        style="background:#06245f;">

                        <h2 class="h5 fw-bold mb-0">

                            Latest {{ $category->name }}

                        </h2>


                        <span
                            class="badge bg-white text-dark">

                            {{ $posts->total() }}

                            {{ $posts->total() == 1
                                ? 'Post'
                                : 'Posts' }}

                        </span>

                    </div>



                    {{-- POST LIST --}}

                    <div class="p-3">


                        @forelse($posts as $post)


                            <article
                                class="border-bottom py-3">


                                {{-- POST TITLE --}}

                                <h3 class="h5 mb-2">

                                    <a
                                        href="{{ route(
                                            'post',
                                            $post->slug
                                        ) }}"
                                        class="text-decoration-none fw-semibold"
                                        style="color:#064fc7;">

                                        {{ $post->title }}

                                    </a>

                                </h3>



                                {{-- DATE + CATEGORY --}}

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



                                {{-- EXCERPT --}}

                                @if(
                                    $post->short_description
                                    ?: $post->excerpt
                                )

                                    <p class="text-secondary mb-2">

                                        {{
                                            $post->short_description
                                            ?: $post->excerpt
                                        }}

                                    </p>

                                @endif



                                {{-- READ MORE --}}

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


                            {{-- NO POSTS --}}

                            <div class="text-center py-5">


                                <div class="mb-3">

                                    <span class="fs-1"
                                          aria-hidden="true">

                                        📄

                                    </span>

                                </div>


                                <h3
                                    class="h5 fw-bold text-dark">

                                    No Posts Available

                                </h3>


                                <p
                                    class="text-secondary mb-3">

                                    There are currently no
                                    published posts in this category.

                                </p>


                                <a
                                    href="{{ url('/') }}"
                                    class="btn btn-primary">

                                    Back to Home

                                </a>


                            </div>


                        @endforelse




                        @if($posts->hasPages())

                            <div class="pt-4">

                                {{ $posts->links() }}

                            </div>

                        @endif


                    </div>

                </section>

            </div>



            <div class="col-lg-4">

                @include(
                    'layouts.partials.sidebar'
                )

            </div>


        </div>



        @if($category->content)

            <section
                class="bg-white border rounded-2 shadow-sm mt-4">

                <div class="p-3 p-md-4">

                    <h2
                        class="h5 fw-bold text-dark mb-3">

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