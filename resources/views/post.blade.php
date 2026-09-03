@extends('layouts.web')


@section(
    'title',
    $post->seo_title ?: $post->title
)


@section(
    'meta_description',
    $post->meta_description
        ?: ($post->excerpt ?: $post->title)
)


@section(
    'meta_keywords',
    $post->meta_keywords
        ?: strtolower(
            $post->title . ', government jobs, govt jobs, recruitment'
        )
)


@section(
    'canonical',
    $post->canonical_url
        ?: url('/post/' . $post->slug)
)


@section(
    'og_type',
    'article'
)


@section(
    'og_title',
    $post->seo_title ?: $post->title
)


@section(
    'og_description',
    $post->meta_description
        ?: ($post->excerpt ?: $post->title)
)


@section(
    'og_url',
    $post->canonical_url
        ?: url('/post/' . $post->slug)
)


@section(
    'twitter_title',
    $post->seo_title ?: $post->title
)


@section(
    'twitter_description',
    $post->meta_description
        ?: ($post->excerpt ?: $post->title)
)


@if($post->featured_image)

    @section(
        'og_image',
        asset($post->featured_image)
    )

    @section(
        'twitter_image',
        asset($post->featured_image)
    )

@endif


@push('structured_data')

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",

    "headline": @json($post->title),

    "description": @json(
        $post->meta_description
            ?: ($post->excerpt ?: $post->title)
    ),

    "datePublished": @json(
        $post->published_at
            ? $post->published_at->toIso8601String()
            : $post->created_at->toIso8601String()
    ),

    "dateModified": @json(
        $post->updated_at
            ? $post->updated_at->toIso8601String()
            : $post->created_at->toIso8601String()
    ),

    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": @json(
            $post->canonical_url
                ?: url('/post/' . $post->slug)
        )
    },

    "author": {
        "@type": "Organization",
        "name": @json(
            config(
                'app.name',
                'Government Jobs Portal'
            )
        )
    },

    "publisher": {
        "@type": "Organization",
        "name": @json(
            config(
                'app.name',
                'Government Jobs Portal'
            )
        )
    }

    @if($post->featured_image)
    ,
    "image": @json(
        asset($post->featured_image)
    )
    @endif
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "JobPosting",

    "title": @json($post->title),

    "description": @json(
        $post->short_description
            ?: ($post->excerpt ?: $post->title)
    ),

    "datePosted": @json(
        $post->published_at
            ? $post->published_at->toIso8601String()
            : $post->created_at->toIso8601String()
    ),

    "url": @json(
        $post->canonical_url
            ?: url('/post/' . $post->slug)
    ),

    "hiringOrganization": {
        "@type": "Organization",
        "name": "Government"
    },

    "jobLocation": {
        "@type": "Place",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "IN"
        }
    }

    @if($post->eligibility)
    ,
    "qualifications": @json(
        strip_tags($post->eligibility)
    )
    @endif

    @if($post->how_to_apply)
    ,
    "applicationInstructions": @json(
        strip_tags($post->how_to_apply)
    )
    @endif

    @if($post->official_website)
    ,
    "sameAs": @json(
        $post->official_website
    )
    @endif

    @if($post->featured_image)
    ,
    "image": @json(
        asset($post->featured_image)
    )
    @endif
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [

        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": @json(url('/'))
        }

        @if($post->categories && $post->categories->count())

            @foreach($post->categories as $category)

            ,
            {
                "@type": "ListItem",
                "position": {{ $loop->iteration + 1 }},
                "name": @json($category->name),
                "item": @json(
                    url('/category/' . $category->slug)
                )
            }

            @endforeach

            ,
            {
                "@type": "ListItem",
                "position": {{ $post->categories->count() + 2 }},
                "name": @json($post->title),
                "item": @json(
                    $post->canonical_url
                        ?: url('/post/' . $post->slug)
                )
            }

        @else

            ,
            {
                "@type": "ListItem",
                "position": 2,
                "name": @json($post->title),
                "item": @json(
                    $post->canonical_url
                        ?: url('/post/' . $post->slug)
                )
            }

        @endif

    ]
}
</script>

@endpush



@php

    /*
    |--------------------------------------------------------------------------
    | Popular Categories
    |--------------------------------------------------------------------------
    | Only active main categories
    */

    $popularCategories = \App\Models\Category::query()
        ->where('status', true)
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->take(8)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Related Posts
    |--------------------------------------------------------------------------
    | Related posts are already prepared by FrontController.
    |
    | They are matched using ANY category assigned to current post.
    |--------------------------------------------------------------------------
    */

@endphp



@section('content')

<div class="bg-light py-4">

    <div class="container">


        <nav
            aria-label="breadcrumb"
            class="mb-3">

            <ol class="breadcrumb mb-0">


                {{-- HOME --}}

                <li class="breadcrumb-item">

                    <a
                        href="{{ url('/') }}"
                        class="text-decoration-none">

                        Home

                    </a>

                </li>



                {{-- CATEGORIES --}}

                @if($post->categories && $post->categories->count())

                    @foreach($post->categories as $category)

                        <li class="breadcrumb-item">

                            <a
                                href="{{ route(
                                    'category',
                                    $category->slug
                                ) }}"
                                class="text-decoration-none">

                                {{ $category->name }}

                            </a>

                        </li>

                    @endforeach

                @endif



                {{-- POST --}}

                <li
                    class="breadcrumb-item active"
                    aria-current="page">

                    {{ $post->title }}

                </li>


            </ol>

        </nav>




        <div class="row g-4">



            <div class="col-lg-8">


                <article
                    class="bg-white border shadow-sm">


                    <div class="p-3 p-md-4 border-bottom">


                        {{-- CATEGORY BADGES --}}

                        @if($post->categories && $post->categories->count())

                            <div class="mb-3 d-flex flex-wrap gap-2">

                                @foreach($post->categories as $category)

                                    <a
                                        href="{{ route(
                                            'category',
                                            $category->slug
                                        ) }}"
                                        class="badge text-decoration-none"
                                        style="background:#06245f;">

                                        {{ $category->name }}

                                    </a>

                                @endforeach

                            </div>

                        @endif



                        {{-- POST TITLE --}}

                        <h1 class="h2 fw-bold text-dark mb-3">

                            {{ $post->title }}

                        </h1>



                        {{-- PUBLISHED DATE --}}

                        <div class="small text-secondary">
                            Published By : <a href="{{ route('manisha_jalu') }}" >  Manisha Jalu </a> || 
                            Published At :

                            <strong>

                                {{ $post->published_at
                                    ? $post->published_at->format('d M Y')
                                    : $post->created_at->format('d M Y') }}

                            </strong>

                        </div>


                    </div>





                @if($post->featured_image)

                @if($post->featured_image)

                    <div class="p-3 p-md-4 pb-0">

                        @php
                            $isMobile = preg_match(
                                '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i',
                                request()->userAgent()
                            );
                        @endphp


                        @if($isMobile && $post->mobile_image)

                            <img
                                src="{{ asset($post->mobile_image) }}"
                                alt="{{ $post->title }}"
                                class="img-fluid rounded"
                                width="354"
                                height="199"
                                loading="eager"
                                fetchpriority="high">

                        @else

                            <img
                                src="{{ asset($post->featured_image) }}"
                                alt="{{ $post->title }}"
                                class="img-fluid rounded"
                                width="1280"
                                height="720"
                                loading="eager"
                                fetchpriority="high">

                        @endif

                    </div>

                @endif

                @endif


                    @if($post->short_description)

                        <div class="p-3 p-md-4 pb-0">

                            <div class="job-post-intro">

                                {{ $post->short_description }}

                            </div>

                        </div>

                    @elseif($post->excerpt)

                        <div class="p-3 p-md-4 pb-0">

                            <div class="job-post-intro">

                                {{ $post->excerpt }}

                            </div>

                        </div>

                    @endif



                    @if($post->content)

                        <div class="p-3 p-md-4">

                            <div class="job-post-content">

                                {!! $post->content !!}

                            </div>

                        </div>

                    @endif



                  

                </article>

                {{-- =========================================================
                    AUTHOR BOX
                ========================================================== --}}

                <section class="bg-white py-3 mt-4">

                    <div class="container">
                                            <div
                                                class="px-3 py-3 text-white"
                                                style="background:#06245f;">

                                                <h2 class="h5 fw-bold mb-0">

                                                    Author

                                                </h2>

                                            </div>
                        <div
                            class="p-3 p-md-4"
                            style="
                                background:#fff;
                                box-shadow:0 8px 25px rgba(0,0,0,0.05);
                            "
                        >

                            <div class="d-flex align-items-start gap-3">

                                {{-- Author Initial --}}
                                <div
                                    class="d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="
                                        width:58px;
                                        height:58px;
                                        border-radius:50%;
                                        background:#f1f3f5;
                                        color:#0d2b52;
                                        font-size:24px;
                                        font-weight:700;
                                    "
                                >
                                    M
                                </div>


                                {{-- Author Content --}}
                                <div>

                                    <h3
                                        class="mb-2"
                                        style="
                                            font-size:20px;
                                            font-weight:700;
                                            color:#102a43;
                                        "
                                    >
                                    <a href="{{ route('manisha_jalu') }}" >   
                                        Manisha Jalu
                                    </a>
                                    </h3>


                                    <p
                                        class="mb-0"
                                        style="
                                            font-size:15px;
                                            line-height:1.7;
                                            color:#36506b;
                                        "
                                    >
Manisha Jalu is an editor at JobLavo who covers government recruitment notifications, examination updates, admit cards, results and other career-related announcements. Her work focuses on organizing official recruitment information into clear, easy-to-understand guides for job seekers.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                {{-- =====================================================
                     RELATED POSTS
                ====================================================== --}}

                @if($relatedPosts->count())

                    <section class="mt-4">


                        <div
                            class="bg-white border shadow-sm">


                            {{-- HEADER --}}

                            <div
                                class="px-3 py-3 text-white"
                                style="background:#06245f;">

                                <h2 class="h5 fw-bold mb-0">

                                    Related Jobs

                                </h2>

                            </div>



                            {{-- POSTS --}}

                            <div class="p-3">

                                <div class="row g-3">


                                    @foreach(
                                        $relatedPosts
                                        as $related
                                    )

                                        <div
                                            class="col-md-6 col-lg-6">


                                            <article
                                                class="border rounded-2
                                                       h-100 p-3">


                                                {{-- RELATED CATEGORIES --}}

                                                @if(
                                                    $related->categories &&
                                                    $related->categories->count()
                                                )

                                                    <div class="mb-2 d-flex flex-wrap gap-1">

                                                        @foreach(
                                                            $related->categories->take(2)
                                                            as $relatedCategory
                                                        )

                                                            <a
                                                                href="{{ route(
                                                                    'category',
                                                                    $relatedCategory->slug
                                                                ) }}"
                                                                class="badge text-decoration-none"
                                                                style="background:#06245f;">

                                                                {{ $relatedCategory->name }}

                                                            </a>

                                                        @endforeach

                                                    </div>

                                                @endif


                                                <h3
                                                    class="h6 fw-bold mb-2">


                                                    <a
                                                        href="{{ route(
                                                            'post',
                                                            $related->slug
                                                        ) }}"
                                                        class="text-decoration-none"
                                                        style="color:#064fc7;">

                                                        {{ $related->title }}

                                                    </a>


                                                </h3>


                                                <!-- <div
                                                    class="small text-secondary">


                                                    {{ $related->published_at
                                                        ? $related->published_at->format('d M Y')
                                                        : $related->created_at->format('d M Y') }}


                                                </div> -->


                                            </article>


                                        </div>

                                    @endforeach


                                </div>

                            </div>


                        </div>


                    </section>

                @endif


            </div>



            <div class="col-lg-4">

                @include(
                    'layouts.partials.sidebar'
                )

            </div>

        </div>



    </div>

</div>

@endsection