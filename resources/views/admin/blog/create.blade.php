@extends('layouts.admin')

@section('title', 'Add Blog | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Add Blog
        </h1>

        <p class="text-secondary mb-0">
            Create a new tips, guide or career blog post.
        </p>

    </div>


    <a
        href="{{ route('admin.blog.index') }}"
        class="btn btn-outline-secondary"
    >

        ← Back to Blogs

    </a>

</div>


@include('admin.blog._form', [

    'formAction' => route('admin.blog.store'),

    'formMethod' => 'POST',

    'submitText' => 'Create Blog',

    'blog' => null,

])

@endsection