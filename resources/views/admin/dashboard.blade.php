@extends('layouts.admin')


@section('title', 'Dashboard | Admin')


@section('content')


<div class="mb-4">

    <h1 class="h3 fw-bold mb-1">
        Dashboard
    </h1>

    <p class="text-secondary mb-0">
        Welcome to your admin panel.
    </p>

</div>



<div class="row g-3">


    {{-- CATEGORIES --}}

    <div class="col-md-6 col-xl-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-secondary small mb-2">
                    Categories
                </div>

                <div class="h3 fw-bold mb-3">
                    {{ \App\Models\Category::count() }}
                </div>

                <a href="{{ route('admin.categories.index') }}"
                    class="text-decoration-none fw-semibold">

                    Manage Categories →

                </a>

            </div>

        </div>

    </div>



    {{-- POSTS --}}

    <div class="col-md-6 col-xl-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-secondary small mb-2">
                    Total Posts
                </div>

                <div class="h3 fw-bold mb-3">
                    {{ \App\Models\Post::count() }}
                </div>

                <span class="text-secondary">
                    All posts
                </span>

            </div>

        </div>

    </div>



    {{-- PUBLISHED --}}

    <div class="col-md-6 col-xl-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-secondary small mb-2">
                    Published Posts
                </div>

                <div class="h3 fw-bold mb-3">
                    {{ \App\Models\Post::where('status', 'published')->count() }}
                </div>

                <span class="text-success fw-semibold">
                    Published
                </span>

            </div>

        </div>

    </div>



    {{-- DRAFTS --}}

    <div class="col-md-6 col-xl-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-secondary small mb-2">
                    Draft Posts
                </div>

                <div class="h3 fw-bold mb-3">
                    {{ \App\Models\Post::where('status', 'draft')->count() }}
                </div>

                <span class="text-warning fw-semibold">
                    Drafts
                </span>

            </div>

        </div>

    </div>


</div>



{{-- QUICK ACTIONS --}}


    <div class="col-md-6 col-xl-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-secondary small mb-1">
                    Sitemaps
                </div>

                <div class="h3 fw-bold mb-1">
                    {{ $sitemapCount }}
                </div>

                <div class="small text-secondary">
                    {{ $sitemapUrls }} URLs
                </div>

                <div class="mt-3 d-flex gap-2">

                    <span class="badge bg-success">
                        {{ $sitemapIndexed }} Indexed
                    </span>

                    <span class="badge bg-warning text-dark">
                        {{ $sitemapPending }} Pending
                    </span>

                </div>

                <a href="{{ route('admin.sitemaps.index') }}"
                    class="btn btn-sm btn-outline-primary mt-3">

                    Manage Sitemap

                </a>

            </div>

        </div>

    </div>
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body p-4">


        <h2 class="h5 fw-bold mb-3">
            Quick Actions
        </h2>


        <div class="d-flex flex-wrap gap-2">


            <a href="{{ route('admin.categories.create') }}"
                class="btn btn-primary">

                + Add Category

            </a>


            <a href="{{ route('admin.categories.index') }}"
                class="btn btn-outline-primary">

                Manage Categories

            </a>


            <a href="{{ url('/') }}"
                target="_blank"
                class="btn btn-outline-secondary">

                View Website

            </a>


        </div>


    </div>


</div>


@endsection