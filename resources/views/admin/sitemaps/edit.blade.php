@extends('layouts.admin')


@section('title', 'Edit Sitemap | Admin')


@section('content')


<div class="d-flex
            flex-column
            flex-md-row
            justify-content-between
            align-items-md-center
            gap-3
            mb-4">


    {{-- =====================================================
         TITLE
    ====================================================== --}}

    <div>

        <h1 class="h3 fw-bold mb-1">

            Edit Sitemap

        </h1>


        <p class="text-secondary mb-0">

            Update sitemap submission status and notes.

        </p>

    </div>



    {{-- =====================================================
         BACK
    ====================================================== --}}

    <a
        href="{{ route('admin.sitemaps.index') }}"
        class="btn btn-outline-secondary">

        ← Back to Sitemap

    </a>

</div>



<div class="card border-0 shadow-sm">


    <div class="card-body">


        <form
            action="{{ route(
                'admin.sitemaps.update',
                $sitemap
            ) }}"
            method="POST">

            @csrf

            @method('PUT')



            {{-- =================================================
                 SITEMAP
            ================================================== --}}

            <div class="mb-4">

                <label
                    class="form-label fw-semibold">

                    Sitemap

                </label>


                <input
                    type="text"
                    class="form-control"
                    value="{{ $sitemap->filename }}"
                    readonly>

            </div>



            {{-- =================================================
                 TYPE
            ================================================== --}}

            <div class="mb-4">

                <label
                    class="form-label fw-semibold">

                    Type

                </label>


                <input
                    type="text"
                    class="form-control"
                    value="{{ ucfirst(
                        $sitemap->type
                    ) }}"
                    readonly>

            </div>



            {{-- =================================================
                 URL COUNT
            ================================================== --}}

            <div class="mb-4">

                <label
                    class="form-label fw-semibold">

                    URL Count

                </label>


                <input
                    type="text"
                    class="form-control"
                    value="{{ number_format(
                        $sitemap->url_count
                    ) }}"
                    readonly>

            </div>



            {{-- =================================================
                 SITEMAP URL
            ================================================== --}}

            <div class="mb-4">

                <label
                    class="form-label fw-semibold">

                    Sitemap URL

                </label>


                <div class="input-group">

                    <input
                        type="text"
                        class="form-control"
                        value="{{ url(
                            '/' .
                            $sitemap->filename
                        ) }}"
                        readonly>


                    <a
                        href="{{ url(
                            '/' .
                            $sitemap->filename
                        ) }}"
                        target="_blank"
                        rel="noopener"
                        class="btn btn-outline-primary">

                        Open

                    </a>

                </div>

            </div>



            {{-- =================================================
                 STATUS
            ================================================== --}}

            <div class="mb-4">

                <label
                    for="status"
                    class="form-label fw-semibold">

                    Status

                </label>


                <select
                    name="status"
                    id="status"
                    class="form-select @error('status') is-invalid @enderror">


                    <option
                        value="not_submitted"
                        @selected(
                            old(
                                'status',
                                $sitemap->status
                            ) === 'not_submitted'
                        )>

                        Not Submitted

                    </option>


                    <option
                        value="submitted"
                        @selected(
                            old(
                                'status',
                                $sitemap->status
                            ) === 'submitted'
                        )>

                        Submitted to Google

                    </option>


                    <option
                        value="processing"
                        @selected(
                            old(
                                'status',
                                $sitemap->status
                            ) === 'processing'
                        )>

                        Processing

                    </option>


                    <option
                        value="indexed"
                        @selected(
                            old(
                                'status',
                                $sitemap->status
                            ) === 'indexed'
                        )>

                        Indexed

                    </option>


                    <option
                        value="error"
                        @selected(
                            old(
                                'status',
                                $sitemap->status
                            ) === 'error'
                        )>

                        Error

                    </option>

                </select>


                @error('status')

                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>

                @enderror

            </div>



            {{-- =================================================
                 SUBMITTED AT
            ================================================== --}}

            <div class="mb-4">

                <label
                    for="submitted_at"
                    class="form-label fw-semibold">

                    Submitted At

                </label>


                <input
                    type="datetime-local"
                    name="submitted_at"
                    id="submitted_at"
                    class="form-control @error('submitted_at') is-invalid @enderror"
                    value="{{ old(
                        'submitted_at',
                        $sitemap->submitted_at
                            ? $sitemap->submitted_at->format(
                                'Y-m-d\TH:i'
                            )
                            : ''
                    ) }}">


                @error('submitted_at')

                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>

                @enderror

            </div>



            {{-- =================================================
                 NOTES
            ================================================== --}}

            <div class="mb-4">

                <label
                    for="notes"
                    class="form-label fw-semibold">

                    Notes

                </label>


                <textarea
                    name="notes"
                    id="notes"
                    rows="5"
                    maxlength="2000"
                    class="form-control @error('notes') is-invalid @enderror"
                    placeholder="Add any notes about this sitemap...">{{ old(
                        'notes',
                        $sitemap->notes
                    ) }}</textarea>


                <div class="form-text">

                    Optional notes about Google submission,
                    indexing, errors or other sitemap activity.

                </div>


                @error('notes')

                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>

                @enderror

            </div>



            {{-- =================================================
                 BUTTONS
            ================================================== --}}

            <div class="d-flex flex-wrap gap-2">


                <button
                    type="submit"
                    class="btn btn-primary">

                    Save Changes

                </button>


                <a
                    href="{{ route(
                        'admin.sitemaps.index'
                    ) }}"
                    class="btn btn-outline-secondary">

                    Cancel

                </a>


            </div>

        </form>

    </div>

</div>


@endsection