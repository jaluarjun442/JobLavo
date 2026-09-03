@extends('layouts.admin')

@section('title', 'Edit Blog | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Edit Blog
        </h1>

        <p class="text-secondary mb-0">
            Update this blog post.
        </p>

    </div>


    <div class="d-flex gap-2">

        <a
            href="{{ route('blog.show', $blog->slug) }}"
            target="_blank"
            class="btn btn-outline-secondary"
        >
            View Blog
        </a>

        <a
            href="{{ route('admin.blog.index') }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Blogs
        </a>

    </div>

</div>


@include('admin.blog._form', [

    'formAction' => route(
        'admin.blog.update',
        $blog->id
    ),

    'formMethod' => 'PUT',

    'submitText' => 'Update Blog',

    'blog' => $blog,

])

@endsection