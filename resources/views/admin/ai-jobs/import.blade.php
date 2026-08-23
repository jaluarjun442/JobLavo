@extends('layouts.admin')

@section('title', 'AI Job Import | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            AI Job Import
        </h1>

        <p class="text-secondary mb-0">
            Paste AI generated job JSON and quickly add jobs without opening the Add Post page.
        </p>

    </div>


    <a
        href="{{ route('admin.posts.index') }}"
        class="btn btn-outline-secondary">

        ← Back to Posts

    </a>

</div>



{{-- =========================================================
     IMPORT JSON
========================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.ai-jobs.import.preview') }}">

            @csrf


            <div class="mb-3">

                <label
                    for="json_data"
                    class="form-label fw-semibold">

                    AI Generated JSON

                </label>


                <textarea
                    name="json_data"
                    id="json_data"
                    class="form-control font-monospace"
                    rows="18"
                    placeholder="Paste your AI generated JSON here..."
                    required>{{ old('json_data') }}</textarea>

            </div>


            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary">

                    Import Jobs

                </button>


                <button
                    type="reset"
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

    {{-- =====================================================
         QUEUE HEADER
    ====================================================== --}}

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">


            <div>

                <h2 class="h5 fw-bold mb-1">
                    Import Queue
                </h2>

                <div class="text-secondary small">

                    {{ count($jobs) }} job(s) waiting to be added

                </div>

            </div>



            {{-- CLEAR ALL --}}

            <form
                method="POST"
                action="{{ route('admin.ai-jobs.import.clear') }}"
                onsubmit="return confirm('Clear all imported jobs?');">

                @csrf

                <button
                    type="submit"
                    class="btn btn-sm btn-outline-danger">

                    Clear All

                </button>

            </form>

        </div>

    </div>



    {{-- =====================================================
         BULK CATEGORY SELECTION
    ====================================================== --}}

    <div class="card-body border-top border-bottom bg-light">

        <div class="row g-3 align-items-end">


            {{-- CATEGORY SELECT --}}

            <div class="col-lg-8">

                <label
                    for="bulk_categories"
                    class="form-label fw-semibold">

                    Select Categories

                </label>


                <select
                    id="bulk_categories"
                    name="category_ids[]"
                    class="form-select js-category-select"
                    multiple
                    data-placeholder="Select one or multiple categories">

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}">

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>


                <div class="form-text">

                    Select one or more categories. These categories
                    will be assigned to the selected jobs.

                </div>

            </div>



            {{-- SELECT ALL --}}

            <div class="col-lg-4">

                <div class="d-flex flex-wrap gap-2">

                    <button
                        type="button"
                        id="selectAllJobs"
                        class="btn btn-outline-primary">

                        Select All

                    </button>


                    <button
                        type="button"
                        id="clearSelectedJobs"
                        class="btn btn-outline-secondary">

                        Clear Selection

                    </button>


                    <button
                        type="button"
                        id="addSelectedJobs"
                        class="btn btn-primary">

                        Add Selected

                    </button>

                </div>

            </div>


        </div>

    </div>



    {{-- =====================================================
         JOB LIST
    ====================================================== --}}

    <div class="list-group list-group-flush">


        @foreach($jobs as $index => $job)


            <div class="list-group-item py-3">


                <div
                    class="d-flex flex-column flex-lg-row justify-content-between gap-3">


                    {{-- =================================================
                         JOB INFO
                    ================================================== --}}

                    <div class="d-flex gap-3 flex-grow-1">


                        {{-- CHECKBOX --}}

                        <div class="pt-1">

                            <input
                                type="checkbox"
                                class="form-check-input job-checkbox"
                                value="{{ $index }}"
                                aria-label="Select {{ $job['title'] ?? 'job' }}">

                        </div>



                        {{-- CONTENT --}}

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

                                {{ $job['title'] ?? 'Untitled Job' }}

                            </h3>



                            @if(!empty($job['category']))

                                <div class="small text-secondary">

                                    Original Category:
                                    {{ $job['category'] }}

                                </div>

                            @endif



                            @if(!empty($job['excerpt']))

                                <div class="small text-secondary mt-2">

                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($job['excerpt']),
                                        220
                                    ) }}

                                </div>

                            @endif


                        </div>

                    </div>



                    {{-- =================================================
                         ACTIONS
                    ================================================== --}}

                    <div class="d-flex align-items-center gap-2 flex-shrink-0">


                        {{-- PREVIEW --}}

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary preview-job"
                            data-bs-toggle="modal"
                            data-bs-target="#jobPreviewModal{{ $index }}">

                            Preview

                        </button>



                        {{-- ADD THIS POST --}}

                        <button
                            type="button"
                            class="btn btn-sm btn-primary add-single-job"
                            data-job-index="{{ $index }}">

                            Add This Post

                        </button>



                        {{-- REMOVE --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.ai-jobs.import.remove',
                                $index
                            ) }}"
                            onsubmit="return confirm('Remove this job from queue?');">

                            @csrf

                            <button
                                type="submit"
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



{{-- =========================================================
     PREVIEW MODALS
========================================================= --}}

@foreach($jobs as $index => $job)


<div
    class="modal fade"
    id="jobPreviewModal{{ $index }}"
    tabindex="-1"
    aria-labelledby="jobPreviewLabel{{ $index }}"
    aria-hidden="true">


    <div class="modal-dialog modal-xl modal-dialog-scrollable">


        <div class="modal-content">


            {{-- MODAL HEADER --}}

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="jobPreviewLabel{{ $index }}">

                        {{ $job['title'] ?? 'Untitled Job' }}

                    </h5>

                    <div class="small text-secondary mt-1">

                        Job #{{ $index + 1 }}

                    </div>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">

                </button>

            </div>



            {{-- MODAL BODY --}}

            <div class="modal-body">


                {{-- BASIC INFORMATION --}}

                <div class="mb-4">

                    <h6 class="fw-bold border-bottom pb-2">
                        Basic Information
                    </h6>


                    <div class="row g-3">


                        @if(!empty($job['title']))

                            <div class="col-md-12">

                                <div class="small text-muted">
                                    Title
                                </div>

                                <div class="fw-semibold">
                                    {{ $job['title'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['category']))

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Original Category
                                </div>

                                <div>
                                    {{ $job['category'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['slug']))

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Slug
                                </div>

                                <div>
                                    {{ $job['slug'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['seo_title']))

                            <div class="col-md-12">

                                <div class="small text-muted">
                                    SEO Title
                                </div>

                                <div>
                                    {{ $job['seo_title'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['meta_description']))

                            <div class="col-md-12">

                                <div class="small text-muted">
                                    Meta Description
                                </div>

                                <div>
                                    {{ $job['meta_description'] }}
                                </div>

                            </div>

                        @endif


                    </div>

                </div>



                {{-- DESCRIPTION --}}

                @if(
                    !empty($job['excerpt']) ||
                    !empty($job['short_description'])
                )

                    <div class="mb-4">

                        <h6 class="fw-bold border-bottom pb-2">
                            Description
                        </h6>


                        @if(!empty($job['short_description']))

                            <div class="mb-3">

                                <div class="small text-muted mb-1">
                                    Short Description
                                </div>

                                <div>
                                    {{ $job['short_description'] }}
                                </div>

                            </div>

                        @endif


                        @if(!empty($job['excerpt']))

                            <div>

                                <div class="small text-muted mb-1">
                                    Excerpt
                                </div>

                                <div>
                                    {{ $job['excerpt'] }}
                                </div>

                            </div>

                        @endif

                    </div>

                @endif



                {{-- CONTENT --}}

                @if(!empty($job['content']))

                    <div class="mb-4">

                        <h6 class="fw-bold border-bottom pb-2">
                            Full Content
                        </h6>

                        <div
                            class="border rounded p-3 bg-light"
                            style="white-space:pre-wrap;">

                            {{ strip_tags($job['content']) }}

                        </div>

                    </div>

                @endif



                {{-- JOB DETAILS --}}

                <div class="mb-4">

                    <h6 class="fw-bold border-bottom pb-2">
                        Job Details
                    </h6>


                    <div class="row g-3">


                        @if(!empty($job['important_dates']))

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Important Dates
                                </div>

                                <div>
                                    {{ $job['important_dates'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['application_fee']))

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Application Fee
                                </div>

                                <div>
                                    {{ $job['application_fee'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['age_limit']))

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Age Limit
                                </div>

                                <div>
                                    {{ $job['age_limit'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['vacancy_details']))

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Vacancy Details
                                </div>

                                <div>
                                    {{ $job['vacancy_details'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['eligibility']))

                            <div class="col-md-12">

                                <div class="small text-muted">
                                    Eligibility
                                </div>

                                <div>
                                    {{ $job['eligibility'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['selection_process']))

                            <div class="col-md-12">

                                <div class="small text-muted">
                                    Selection Process
                                </div>

                                <div>
                                    {{ $job['selection_process'] }}
                                </div>

                            </div>

                        @endif



                        @if(!empty($job['salary_details']))

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Salary Details
                                </div>

                                <div>
                                    {{ $job['salary_details'] }}
                                </div>

                            </div>

                        @endif


                    </div>

                </div>



                {{-- HOW TO APPLY --}}

                @if(!empty($job['how_to_apply']))

                    <div class="mb-4">

                        <h6 class="fw-bold border-bottom pb-2">
                            How to Apply
                        </h6>

                        <div>
                            {{ $job['how_to_apply'] }}
                        </div>

                    </div>

                @endif



                {{-- IMPORTANT LINKS --}}

                @if(!empty($job['important_links']))

                    <div class="mb-4">

                        <h6 class="fw-bold border-bottom pb-2">
                            Important Links
                        </h6>

                        <div
                            class="border rounded p-3 bg-light"
                            style="white-space:pre-wrap;">

                            {{ strip_tags($job['important_links']) }}

                        </div>

                    </div>

                @endif



                @if(!empty($job['official_website']))

                    <div class="mb-3">

                        <div class="small text-muted">
                            Official Website
                        </div>

                        <div>
                            {{ $job['official_website'] }}
                        </div>

                    </div>

                @endif


            </div>



            {{-- MODAL FOOTER --}}

            <div class="modal-footer">


                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Close

                </button>


                <button
                    type="button"
                    class="btn btn-primary add-single-job"
                    data-job-index="{{ $index }}">

                    Add This Post

                </button>


            </div>


        </div>

    </div>

</div>


@endforeach



@else


{{-- =========================================================
     EMPTY QUEUE
========================================================= --}}

<div class="card border-0 shadow-sm">

    <div class="card-body text-center py-5">

        <div class="text-secondary">

            No jobs in import queue.

        </div>

    </div>

</div>

@endif



{{-- =========================================================
     ADD JOB FORM
========================================================= --}}

<form
    method="POST"
    action="{{ route('admin.ai-jobs.import.add') }}"
    id="quickAddForm"
    class="d-none">

    @csrf

    <input
        type="hidden"
        name="job_index"
        id="quickAddJobIndex">

    <div id="quickAddCategories"></div>

</form>



{{-- =========================================================
     SCRIPTS
========================================================= --}}

@push('scripts')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Select2
    |--------------------------------------------------------------------------
    */

    $('#bulk_categories').select2({

        width: '100%',

        placeholder:
            'Select one or multiple categories',

        allowClear: true

    });



    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    $('#selectAllJobs').on('click', function () {

        $('.job-checkbox').prop(
            'checked',
            true
        );

    });



    /*
    |--------------------------------------------------------------------------
    | Clear Selection
    |--------------------------------------------------------------------------
    */

    $('#clearSelectedJobs').on('click', function () {

        $('.job-checkbox').prop(
            'checked',
            false
        );

    });



    /*
    |--------------------------------------------------------------------------
    | Get Selected Categories
    |--------------------------------------------------------------------------
    */

    function getSelectedCategories() {

        return $('#bulk_categories')
            .val() || [];

    }



    /*
    |--------------------------------------------------------------------------
    | Check Categories
    |--------------------------------------------------------------------------
    */

    function validateCategories() {

        const categories =
            getSelectedCategories();


        if (!categories.length) {

            alert(
                'Please select at least one category.'
            );

            return false;
        }


        return true;
    }



    /*
    |--------------------------------------------------------------------------
    | Single Job Add
    |--------------------------------------------------------------------------
    */

    $('.add-single-job').on('click', function () {

        const jobIndex =
            $(this).data('job-index');


        if (!validateCategories()) {

            return;

        }


        const categories =
            getSelectedCategories();


        $('#quickAddJobIndex').val(
            jobIndex
        );


        $('#quickAddCategories').html('');



        categories.forEach(function (categoryId) {

            $('#quickAddCategories').append(

                $('<input>', {

                    type: 'hidden',

                    name: 'category_ids[]',

                    value: categoryId

                })

            );

        });


        $('#quickAddForm').submit();

    });



    /*
    |--------------------------------------------------------------------------
    | Add Selected Jobs
    |--------------------------------------------------------------------------
    */

    $('#addSelectedJobs').on('click', function () {


        if (!validateCategories()) {

            return;

        }


        const selectedJobs =
            $('.job-checkbox:checked')
                .map(function () {

                    return $(this).val();

                })
                .get();


        if (!selectedJobs.length) {

            alert(
                'Please select at least one job.'
            );

            return;

        }


        const categories =
            getSelectedCategories();


        $('#quickAddForm').attr(
            'action',
            "{{ route('admin.ai-jobs.import.bulk-add') }}"
        );


        $('#quickAddJobIndex').remove();


        $('#quickAddForm')
            .find('input[name="job_indices[]"]')
            .remove();


        selectedJobs.forEach(function (jobIndex) {

            $('#quickAddForm').append(

                $('<input>', {

                    type: 'hidden',

                    name: 'job_indices[]',

                    value: jobIndex

                })

            );

        });


        $('#quickAddCategories').html('');


        categories.forEach(function (categoryId) {

            $('#quickAddCategories').append(

                $('<input>', {

                    type: 'hidden',

                    name: 'category_ids[]',

                    value: categoryId

                })

            );

        });


        $('#quickAddForm').submit();

    });


});

</script>

@endpush


@endsection