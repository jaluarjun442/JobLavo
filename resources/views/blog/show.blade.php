@extends('layouts.web')

@section('title')
    {{ $blog->seo_title ?: $blog->title }}
@endsection

@section('meta_description')
    {{ $blog->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($blog->content), 160) }}
@endsection

@section('meta_keywords')
    {{ $blog->meta_keywords ?: $blog->title }}
@endsection

@section('meta_author')
    {{ $blog->published_by ?: 'JobLavo' }}
@endsection

@section('canonical')
    {{ $blog->canonical_url ?: route('blog.show', $blog->slug) }}
@endsection


{{-- =========================================================
     OPEN GRAPH
========================================================= --}}

@section('og_type')
    article
@endsection

@section('og_title')
    {{ $blog->seo_title ?: $blog->title }}
@endsection

@section('og_description')
    {{ $blog->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($blog->content), 160) }}
@endsection

@section('og_url')
    {{ $blog->canonical_url ?: route('blog.show', $blog->slug) }}
@endsection

@if($blog->desktop_image)

    @section('og_image')
        {{ asset($blog->desktop_image) }}
    @endsection

@endif


{{-- =========================================================
     TWITTER / X
========================================================= --}}

@section('twitter_title')
    {{ $blog->seo_title ?: $blog->title }}
@endsection

@section('twitter_description')
    {{ $blog->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($blog->content), 160) }}
@endsection

@if($blog->desktop_image)

    @section('twitter_image')
        {{ asset($blog->desktop_image) }}
    @endsection

@endif


{{-- =========================================================
     BLOG ARTICLE STRUCTURED DATA
========================================================= --}}

@push('structured_data')

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ $blog->canonical_url ?: route('blog.show', $blog->slug) }}"
    },
    "headline": @json($blog->title),
    "description": @json(
        $blog->meta_description
            ?: \Illuminate\Support\Str::limit(strip_tags($blog->content), 160)
    ),
    "url": "{{ $blog->canonical_url ?: route('blog.show', $blog->slug) }}",
    "datePublished": "{{ optional($blog->published_date)->toIso8601String() }}",
    "dateModified": "{{ optional($blog->updated_at)->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": @json($blog->published_by ?: 'JobLavo')
    },
    "publisher": {
        "@type": "Organization",
        "name": "JobLavo",
        "url": "{{ url('/') }}"
    }

    @if($blog->desktop_image)
    ,
    "image": [
        "{{ asset($blog->desktop_image) }}"
    ]
    @endif
}
</script>

@endpush

@section('content')

<section class="bg-white py-4">

    <div class="container">

        <div class="row">

            <div class="col-lg-8 mx-auto">


                {{-- =====================================================
                     BLOG HEADER
                ====================================================== --}}

                <article class="blog-post">


                    <h1 class="fw-bold mb-3">
                        {{ $blog->title }}
                    </h1>


                    <div class="small text-muted mb-4">

                        @if($blog->published_date)

                            {{ $blog->published_date->format('d M Y') }}

                        @endif


                        @if($blog->published_by)

                            <span class="mx-1">
                                ·
                            </span>

                            <a href="{{ route('manisha_jalu') }}" >{{ $blog->published_by }}</a>

                        @endif



                    </div>


                    {{-- =================================================
                         FEATURED IMAGE
                    ================================================== --}}

                    @if($blog->desktop_image)

                        <div class="mb-4">

                            <picture>

                                @if($blog->mobile_image)

                                    <source
                                        media="(max-width: 767px)"
                                        srcset="{{ asset($blog->mobile_image) }}"
                                        width="354"
                                        height="199"
                                    >

                                @endif


                                <img
                                    src="{{ asset($blog->desktop_image) }}"
                                    alt="{{ $blog->title }}"
                                    class="img-fluid rounded"
                                    width="1280"
                                    height="720"
                                    loading="eager"
                                    fetchpriority="high"
                                >

                            </picture>

                        </div>

                    @endif


                    {{-- =================================================
                         BLOG CONTENT
                    ================================================== --}}

                    <div class="blog-content">

                        {!! $blog->content !!}

                    </div>


                </article>


                {{-- =====================================================
                     RELATED BLOGS
                ====================================================== --}}

                @if($relatedBlogs->count())

                    <section class="mt-5">

                        <div class="job-section-card">


                            <div class="job-section-header header-navy">

                                <h2>
                                    You May Also Like
                                </h2>

                            </div>


                            <div class="p-3">

                                <ul class="job-list">

                                    @foreach($relatedBlogs as $relatedBlog)

                                        <li>

                                            <a
                                                href="{{ route(
                                                    'blog.show',
                                                    $relatedBlog->slug
                                                ) }}"
                                            >

                                                {{ $relatedBlog->title }}

                                            </a>

                                        </li>

                                    @endforeach

                                </ul>


                                <a
                                    href="{{ route('blog.index') }}"
                                    class="view-btn"
                                >

                                    View All Blogs →

                                </a>

                            </div>

                        </div>

                    </section>

                @endif

            </div>

        </div>

    </div>

</section>

@endsection