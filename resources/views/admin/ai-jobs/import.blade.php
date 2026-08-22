@extends('layouts.admin')

@section('title', 'AI Job Import | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            AI Job Import
        </h1>

        <p class="text-secondary mb-0">
            Paste AI generated job JSON to fill the Add Post form.
        </p>

    </div>

    <a href="{{ route('admin.posts.create') }}"
        class="btn btn-outline-secondary">

        ← Add Post

    </a>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body">

        @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif


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
                    rows="25"
                    placeholder='Paste your AI generated JSON here...'
                    required>{{ old('json_data') }}</textarea>

            </div>


            <div class="d-flex gap-2">

                <button type="submit"
                    class="btn btn-primary">

                    Import & Fill Post

                </button>

                <button type="reset"
                    class="btn btn-outline-secondary">

                    Clear

                </button>

            </div>

        </form>

    </div>

</div>

@endsection