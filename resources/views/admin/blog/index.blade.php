@extends('layouts.admin')

@section('title', 'Blog Posts | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Blog Posts
        </h1>

        <p class="text-secondary mb-0">
            Manage tips, guides and career related blog posts.
        </p>

    </div>


    <a
        href="{{ route('admin.blog.create') }}"
        class="btn btn-primary"
    >

        + Add Blog

    </a>

</div>




@if($posts->count())

    <div class="card border-0 shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Image
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Published
                        </th>

                        <th>
                            Views
                        </th>

                        <th>
                            Published By
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($posts as $post)

                        <tr>

                            {{-- ID --}}

                            <td>

                                {{ $post->id }}

                            </td>


                            {{-- IMAGE --}}

                            <td>

                                @if($post->desktop_image)

                                    <img
                                        src="{{ asset($post->desktop_image) }}"
                                        alt="{{ $post->title }}"
                                        width="80"
                                        height="45"
                                        class="rounded object-fit-cover"
                                    >

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- TITLE --}}

                            <td>

                                <div class="fw-semibold">

                                    {{ $post->title }}

                                </div>

                                <small class="text-muted">

                                    /blog/{{ $post->slug }}

                                </small>

                            </td>


                            {{-- DATE --}}

                            <td>

                                @if($post->published_date)

                                    {{ $post->published_date->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- VIEWS --}}

                            <td>

                                {{ number_format($post->views_count) }}

                            </td>


                            {{-- AUTHOR --}}

                            <td>

                                {{ $post->published_by ?: '—' }}

                            </td>


                            {{-- ACTION --}}

                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-1">

                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route('blog.show', $post->slug) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-secondary"
                                    >

                                        View

                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('admin.blog.edit', $post->id) }}"
                                        class="btn btn-sm btn-primary"
                                    >

                                        Edit

                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route('admin.blog.destroy', $post->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this blog post?');"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        @if($posts->hasPages())

            <div class="p-3 border-top">

                {{ $posts->links() }}

            </div>

        @endif

    </div>

@else

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <h5 class="fw-semibold mb-2">
                No Blog Posts Yet
            </h5>

            <p class="text-muted mb-3">
                Start creating useful tips and guides for your visitors.
            </p>

            <a
                href="{{ route('admin.blog.create') }}"
                class="btn btn-primary"
            >

                + Add First Blog

            </a>

        </div>

    </div>

@endif

@endsection