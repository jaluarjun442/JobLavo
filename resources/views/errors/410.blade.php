@extends('layouts.web')

@section('title', 'Page No Longer Available')

@section('meta_description', 'This page is no longer available.')

@section('content')

<section class="bg-white py-5">

    <div class="container">

        <div class="text-center py-5">

            <h1 class="fw-bold mb-3">
                Page No Longer Available
            </h1>

            <p class="text-muted mb-4">
                This government job update is no longer available.
                Please check our latest job updates for current opportunities.
            </p>

            <a
                href="{{ url('/') }}"
                class="btn btn-primary"
            >
                Go to Home
            </a>

        </div>

    </div>

</section>

@endsection