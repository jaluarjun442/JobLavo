@extends('layouts.admin')

@section('title', 'Edit Post | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Edit Post
        </h1>

        <p class="text-secondary mb-0">
            Update government job or recruitment information.
        </p>

    </div>


    <div class="d-flex gap-2">

        <a href="{{ url('/post/' . $post->slug) }}"
           target="_blank"
           class="btn btn-outline-secondary">

            View Post

        </a>


        <a href="{{ route('admin.posts.index') }}"
           class="btn btn-outline-secondary">

            ← Back

        </a>

    </div>

</div>


@include('admin.posts._form', [

    'formAction' => route('admin.posts.update', $post->id),

    'formMethod' => 'PUT',

    'submitText' => 'Update Post',

    'post' => $post,

])


@endsection