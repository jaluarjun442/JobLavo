@extends('layouts.admin')

@section('title', 'Add Post | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>
        <h1 class="h3 fw-bold mb-1">Add Post</h1>

        <p class="text-secondary mb-0">
            Create a new government job or recruitment post.
        </p>
    </div>

    <a href="{{ route('admin.posts.index') }}"
       class="btn btn-outline-secondary">

        ← Back to Posts

    </a>

</div>


@include('admin.posts._form', [

    'formAction' => route('admin.posts.store'),

    'formMethod' => 'POST',

    'submitText' => 'Create Post',

    'post' => new \App\Models\Post(),

])


@endsection