@extends('layouts.admin')
@push('styles')

<link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet">

<style>

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default
    .select2-selection--multiple {

        min-height: 38px;

        border: 1px solid #dee2e6;

        border-radius: 6px;

        padding: 2px 5px;
    }

    .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice {

        background: #0d6efd;

        border: 0;

        color: #fff;

        padding: 3px 8px;

        margin-top: 4px;
    }

    .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice__remove {

        color: #fff;

        border-right: 0;

        margin-right: 5px;
    }

    .select2-container--default
    .select2-search--inline
    .select2-search__field {

        margin-top: 5px;
    }

</style>

@endpush
@section('title', 'AI Job Import | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            AI Job Import
        </h1>

        <p class="text-secondary mb-0">
            Import AI generated jobs and publish them quickly.
        </p>

    </div>


    <a
        href="{{ route('admin.posts.create') }}"
        class="btn btn-outline-secondary">

        ← Add Post

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

@if($jobs->count())


<div class="card border-0 shadow-sm">


    {{-- =====================================================
         QUEUE HEADER
    ====================================================== --}}

    <div class="card-header bg-white border-0 py-3">


        <div class="d-flex flex-column gap-3">


            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                <div>

                    <h2 class="h5 fw-bold mb-1">
                        Import Queue
                    </h2>

                    <div class="text-secondary small">

                        {{ $jobs->count() }} job(s) waiting to be added

                    </div>

                </div>


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



            {{-- =================================================
                 BULK ACTION BAR
            ================================================== --}}

            <div class="border rounded p-3 bg-light">


                <div class="row g-2 align-items-end">


                    {{-- SELECT ALL --}}

                    <div class="col-auto">

                        <div class="form-check">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="selectAllJobs">

                            <label
                                for="selectAllJobs"
                                class="form-check-label fw-semibold">

                                Select All

                            </label>

                        </div>

                    </div>



                    {{-- CATEGORY --}}

                    <div class="col-md">

                        <label
                            for="bulkCategoryIds"
                            class="form-label small fw-semibold mb-1">

                            Categories

                        </label>


                        <select
                            name="category_ids[]"
                            id="bulkCategoryIds"
                            class="form-select"
                            multiple
                            required>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}">

                                    {{ $category->name }}

                                </option>


                                @foreach($category->children as $child)

                                    <option
                                        value="{{ $child->id }}">

                                        — {{ $child->name }}

                                    </option>

                                @endforeach

                            @endforeach

                        </select>

                    </div>



                    {{-- ADD SELECTED --}}

                    <div class="col-auto">


                        <form
                            method="POST"
                            action="{{ route('admin.posts.bulk-add') }}"
                            id="bulkAddForm">

                            @csrf


                            <div id="selectedJobInputs"></div>


                            <div id="selectedCategoryInputs"></div>


                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="addSelectedBtn"
                                disabled>

                                Add Selected

                            </button>

                        </form>

                    </div>


                </div>


                <div
                    class="small text-secondary mt-2"
                    id="selectionInfo">

                    0 jobs selected

                </div>


            </div>


        </div>

    </div>



    {{-- =====================================================
         JOB LIST
    ====================================================== --}}

    <div class="list-group list-group-flush">


        @foreach($jobs as $index => $job)


            @php

                $content = $job->content;

                $title =
                    $content['title']
                    ?? 'Untitled Job';

                $category =
                    $content['category']
                    ?? '';

                $excerpt =
                    $content['excerpt']
                    ?? '';

            @endphp


            <div class="list-group-item py-3">


                <div class="d-flex align-items-start gap-3">


                    {{-- CHECKBOX --}}

                    <div class="pt-1">

                        <input
                            type="checkbox"
                            class="form-check-input job-checkbox"
                            value="{{ $job->id }}"
                            aria-label="Select {{ $title }}">

                    </div>



                    {{-- JOB INFO --}}

                    <div class="flex-grow-1">


                        <div class="d-flex align-items-center gap-2 mb-1">

                            <span class="badge bg-primary">

                                #{{ $index + 1 }}

                            </span>


                            <span class="badge bg-light text-dark border">

                                Pending

                            </span>

                        </div>


                        <h3 class="h6 fw-bold mb-1">

                            {{ $title }}

                        </h3>


                        @if($category)

                            <div class="small text-secondary">

                                @if(is_array($category))

                                    {{ json_encode($category) }}

                                @else

                                    {{ $category }}

                                @endif

                            </div>

                        @endif


                        @if($excerpt)

                            <div class="small text-secondary mt-2">

                                {{ \Illuminate\Support\Str::limit(
                                    is_array($excerpt)
                                        ? json_encode($excerpt)
                                        : $excerpt,
                                    180
                                ) }}

                            </div>

                        @endif


                    </div>



                    {{-- ACTIONS --}}

                    <div class="d-flex align-items-center gap-2">


                        {{-- PREVIEW --}}

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary preview-job-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#jobPreviewModal"
                            data-job='@json($content)'>

                            Preview

                        </button>


                        {{-- DIRECT ADD --}}

                        <form
                            method="POST"
                            action="{{ route('admin.posts.add') }}"
                            class="direct-add-form">

                            @csrf


                            <input
                                type="hidden"
                                name="job_id"
                                value="{{ $job->id }}">


                            <div class="direct-category-inputs"></div>


                            <button
                                type="submit"
                                class="btn btn-sm btn-primary direct-add-btn">

                                Add

                            </button>

                        </form>


                        {{-- REMOVE --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.ai-jobs.import.remove',
                                $job
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


@else


<div class="card border-0 shadow-sm">

    <div class="card-body text-center py-5">

        <div class="text-secondary">

            No jobs in import queue.

        </div>

    </div>

</div>

@endif



{{-- =========================================================
     PREVIEW MODAL
========================================================= --}}

<div
    class="modal fade"
    id="jobPreviewModal"
    tabindex="-1"
    aria-labelledby="jobPreviewModalLabel"
    aria-hidden="true">


    <div class="modal-dialog modal-xl modal-dialog-scrollable">


        <div class="modal-content">


            <div class="modal-header">

                <h5
                    class="modal-title fw-bold"
                    id="jobPreviewModalLabel">

                    Job Preview

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">

                </button>

            </div>


            <div
                class="modal-body"
                id="jobPreviewContent">

                {{-- JavaScript will insert content --}}

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Close

                </button>

            </div>


        </div>

    </div>

</div>



@endsection



@push('scripts')

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js">
</script>

<script
    src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js">
</script>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | Select2 Category Dropdown
        |--------------------------------------------------------------------------
        */

        $('#bulkCategoryIds').select2({
            placeholder: 'Select categories...',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });


        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const selectAll =
            document.getElementById(
                'selectAllJobs'
            );


        const checkboxes =
            document.querySelectorAll(
                '.job-checkbox'
            );


        const addSelectedBtn =
            document.getElementById(
                'addSelectedBtn'
            );


        const selectionInfo =
            document.getElementById(
                'selectionInfo'
            );


        const categorySelect =
            document.getElementById(
                'bulkCategoryIds'
            );


        const selectedJobInputs =
            document.getElementById(
                'selectedJobInputs'
            );


        const selectedCategoryInputs =
            document.getElementById(
                'selectedCategoryInputs'
            );


        const bulkForm =
            document.getElementById(
                'bulkAddForm'
            );


        /*
        |--------------------------------------------------------------------------
        | Update Bulk Selection
        |--------------------------------------------------------------------------
        */

        function updateSelection()
        {

            const selected =
                Array.from(
                    checkboxes
                ).filter(
                    checkbox =>
                        checkbox.checked
                );


            const count =
                selected.length;


            selectionInfo.textContent =
                count +
                ' job(s) selected';


            addSelectedBtn.disabled =
                count === 0;


            if (
                checkboxes.length
            ) {

                selectAll.checked =
                    count ===
                    checkboxes.length;

            }


            /*
            |--------------------------------------------------------------------------
            | Job IDs
            |--------------------------------------------------------------------------
            */

            selectedJobInputs.innerHTML =
                '';


            selected.forEach(
                function (checkbox) {

                    const input =
                        document.createElement(
                            'input'
                        );


                    input.type =
                        'hidden';

                    input.name =
                        'job_ids[]';

                    input.value =
                        checkbox.value;


                    selectedJobInputs.appendChild(
                        input
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            selectedCategoryInputs.innerHTML =
                '';


            Array.from(
                categorySelect.selectedOptions
            ).forEach(
                function (option) {

                    const input =
                        document.createElement(
                            'input'
                        );


                    input.type =
                        'hidden';

                    input.name =
                        'category_ids[]';

                    input.value =
                        option.value;


                    selectedCategoryInputs.appendChild(
                        input
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Select All
        |--------------------------------------------------------------------------
        */

        selectAll.addEventListener(
            'change',
            function () {

                checkboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            selectAll.checked;

                    }
                );


                updateSelection();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Individual Checkbox
        |--------------------------------------------------------------------------
        */

        checkboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    updateSelection
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Category Change
        |--------------------------------------------------------------------------
        */

        $('#bulkCategoryIds').on(
            'change',
            updateSelection
        );


        /*
        |--------------------------------------------------------------------------
        | Bulk Submit Validation
        |--------------------------------------------------------------------------
        */

        bulkForm.addEventListener(
            'submit',
            function (event) {

                const selected =
                    Array.from(
                        categorySelect.selectedOptions
                    );


                if (
                    selected.length === 0
                ) {

                    event.preventDefault();


                    alert(
                        'Please select at least one category.'
                    );


                    return;
                }


                const jobs =
                    Array.from(
                        checkboxes
                    ).filter(
                        checkbox =>
                            checkbox.checked
                    );


                if (
                    jobs.length === 0
                ) {

                    event.preventDefault();


                    alert(
                        'Please select at least one job.'
                    );

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Direct Add Forms
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.direct-add-form'
            )
            .forEach(
                function (form) {

                    form.addEventListener(
                        'submit',
                        function (event) {

                            const selected =
                                Array.from(
                                    categorySelect.selectedOptions
                                );


                            if (
                                selected.length === 0
                            ) {

                                event.preventDefault();


                                alert(
                                    'Please select at least one category.'
                                );


                                return;
                            }


                            const container =
                                form.querySelector(
                                    '.direct-category-inputs'
                                );


                            container.innerHTML =
                                '';


                            selected.forEach(
                                function (option) {

                                    const input =
                                        document.createElement(
                                            'input'
                                        );


                                    input.type =
                                        'hidden';

                                    input.name =
                                        'category_ids[]';

                                    input.value =
                                        option.value;


                                    container.appendChild(
                                        input
                                    );

                                }
                            );

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Preview
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.preview-job-btn'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const data =
                                JSON.parse(
                                    this.dataset.job
                                );


                            const container =
                                document.getElementById(
                                    'jobPreviewContent'
                                );


                            let html =
                                '';


                            Object.entries(
                                data
                            ).forEach(
                                function (
                                    [key, value]
                                ) {

                                    if (
                                        value ===
                                        null ||
                                        value ===
                                        ''
                                    ) {

                                        return;
                                    }


                                    let displayValue;


                                    if (
                                        typeof value ===
                                        'object'
                                    ) {

                                        displayValue =
                                            '<pre class="mb-0 p-3 bg-light border rounded">'
                                            +
                                            escapeHtml(
                                                JSON.stringify(
                                                    value,
                                                    null,
                                                    2
                                                )
                                            )
                                            +
                                            '</pre>';

                                    } else {

                                        displayValue =
                                            escapeHtml(
                                                String(
                                                    value
                                                )
                                            );

                                    }


                                    html +=
                                        '<div class="mb-4">' +

                                            '<div class="fw-bold text-capitalize mb-1">' +

                                                escapeHtml(
                                                    key.replace(
                                                        /_/g,
                                                        ' '
                                                    )
                                                ) +

                                            '</div>' +

                                            '<div class="text-secondary">' +

                                                displayValue +

                                            '</div>' +

                                        '</div>';
                                }
                            );


                            container.innerHTML =
                                html ||
                                '<div class="text-muted">No information available.</div>';

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(
            value
        ) {

            return value
                .replace(
                    /&/g,
                    '&amp;'
                )
                .replace(
                    /</g,
                    '&lt;'
                )
                .replace(
                    />/g,
                    '&gt;'
                )
                .replace(
                    /"/g,
                    '&quot;'
                )
                .replace(
                    /'/g,
                    '&#039;'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Initial State
        |--------------------------------------------------------------------------
        */

        updateSelection();

    }

);

</script>

@endpush