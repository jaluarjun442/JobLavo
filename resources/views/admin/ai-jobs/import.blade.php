@extends('layouts.admin')

@section('title', 'AI Job Import | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            AI Job Import
        </h1>

        <p class="text-secondary mb-0">
            Paste AI generated job JSON and import multiple jobs at once.
        </p>

    </div>


    <a href="{{ route('admin.posts.create') }}"
        class="btn btn-outline-secondary">

        ← Add Post

    </a>

</div>








{{-- =========================================================
     IMPORT JSON
========================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <form method="POST"
            action="{{ route('admin.ai-jobs.import.preview') }}">

            @csrf


            <div class="mb-3">

                <label class="form-label fw-semibold">

                    AI Generated JSON

                </label>


                <textarea
                    name="json_data"
                    class="form-control font-monospace"
                    rows="18"
                    placeholder='Paste your AI generated JSON here...'
                    required>{{ old('json_data') }}</textarea>

            </div>


            <div class="d-flex gap-2">

                <button type="submit"
                    class="btn btn-primary">

                    Import Jobs

                </button>


                <button type="reset"
                    class="btn btn-outline-secondary">

                    Clear

                </button>

            </div>

        </form>

    </div>

</div>



{{-- =========================================================
     IMPORT QUEUE
========================================================= --}}

@if(count($jobs))

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

            <div>

                <h2 class="h5 fw-bold mb-1">
                    Import Queue
                </h2>

                <div class="text-secondary small">

                    {{ count($jobs) }} job(s) waiting to be added

                </div>

            </div>


            <form method="POST"
                action="{{ route('admin.ai-jobs.import.clear') }}"
                onsubmit="return confirm('Clear all imported jobs?');">

                @csrf

                <button type="submit"
                    class="btn btn-sm btn-outline-danger">

                    Clear All

                </button>

            </form>

        </div>

    </div>


    <div class="list-group list-group-flush">


        @foreach($jobs as $index => $job)

        <div class="list-group-item py-3">


            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">


                {{-- JOB INFO --}}

                <div class="flex-grow-1">

                    <div class="d-flex align-items-center gap-2 mb-1">

                        <span class="badge bg-primary">

                            #{{ $index + 1 }}

                        </span>


                        <span class="badge bg-light text-dark border">

                            Ready

                        </span>

                    </div>


                    <h3 class="h6 fw-bold mb-1">

                        {{ $job['title'] ?: 'Untitled Job' }}

                    </h3>


                    @if(!empty($job['category']))

                    <div class="small text-secondary">

                        {{ $job['category'] }}

                    </div>

                    @endif


                    @if(!empty($job['excerpt']))

                    <div class="small text-secondary mt-2">

                        {{ \Illuminate\Support\Str::limit(
                            strip_tags($job['excerpt']),
                            180
                        ) }}

                    </div>

                    @endif

                </div>



                {{-- ACTIONS --}}

                <div class="d-flex align-items-center gap-2">

                    <a href="{{ route('admin.posts.create') }}?ai_queue={{ $index }}"
                        class="btn btn-sm btn-primary">

                        Add This Post

                    </a>


                    <form method="POST"
                        action="{{ route(
                            'admin.ai-jobs.import.remove',
                            $index
                        ) }}"
                        onsubmit="return confirm('Remove this job from queue?');">

                        @csrf

                        <button type="submit"
                            class="btn btn-sm btn-outline-danger">

                            Remove

                        </button>

                    </form>

                </div>


            </div>

        </div>

        @endforeach


    </div>

</div>

@else


<div class="card border-0 shadow-sm">

    <div class="card-body text-center py-5">

        <div class="text-secondary">

            No jobs in import queue.

        </div>

    </div>

</div>

@endif

@endsection