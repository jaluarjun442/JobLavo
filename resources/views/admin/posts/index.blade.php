@extends('layouts.admin')


@section('title', 'Posts | Admin')


@section('content')


<div>

    <div class="d-flex
                flex-column
                flex-md-row
                justify-content-between
                align-items-md-center
                gap-3
                mb-4">


        <div>

            <h1 class="h3 fw-bold mb-1">
                Posts
            </h1>

            <p class="text-secondary mb-0">
                Manage government job posts and updates.
            </p>

        </div>


        <div class="d-flex
                    flex-wrap
                    gap-2
                    align-items-center">


            {{-- =========================================================
                 INDEXING FILTER
            ========================================================== --}}

            <div class="d-flex
                        align-items-center
                        gap-2">

                <label
                    for="indexStatus"
                    class="mb-0
                           fw-semibold
                           text-nowrap">

                    Indexing:

                </label>


                <select
                    id="indexStatus"
                    class="form-select form-select-sm"
                    style="width: 150px;">

                    <option value="">
                        All
                    </option>

                    <option value="indexed">
                        Indexed
                    </option>

                    <option value="not_indexed">
                        Not Indexed
                    </option>

                </select>

            </div>


            {{-- =========================================================
                 HTTP STATUS FILTER
            ========================================================== --}}

            <div class="d-flex
                        align-items-center
                        gap-2">

                <label
                    for="httpStatus"
                    class="mb-0
                           fw-semibold
                           text-nowrap">

                    HTTP:

                </label>


                <select
                    id="httpStatus"
                    class="form-select form-select-sm"
                    style="width: 150px;">

                    <option value="">
                        All
                    </option>

                    <option value="200">
                        200 - Active
                    </option>

                    <option value="410">
                        410 - Gone
                    </option>

                </select>

            </div>


            {{-- =========================================================
                 INDEXED COUNT
            ========================================================== --}}

            <span
                class="badge
                       bg-success-subtle
                       text-success
                       border
                       px-3
                       py-2">

                Indexed:

                <span id="indexedCount">
                    0
                </span>

            </span>


            {{-- =========================================================
                 NOT INDEXED COUNT
            ========================================================== --}}

            <span
                class="badge
                       bg-warning-subtle
                       text-warning-emphasis
                       border
                       px-3
                       py-2">

                Not Indexed:

                <span id="notIndexedCount">
                    0
                </span>

            </span>


            {{-- =========================================================
                 INDEX PENDING
            ========================================================== --}}

            <form
                action="{{ route('admin.posts.index-pending') }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Index all pending published posts?');">

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-primary">

                    <i class="bi bi-google me-1"></i>

                    Index Pending

                </button>

            </form>


            {{-- =========================================================
                 AI IMPORT
            ========================================================== --}}

            <a
                href="{{ route('admin.ai-jobs.import') }}"
                class="btn btn-dark">

                🤖 AI Import

            </a>


            {{-- =========================================================
                 ADD POST
            ========================================================== --}}

            <a
                href="{{ route('admin.posts.create') }}"
                class="btn btn-primary">

                + Add Post

            </a>


        </div>

    </div>



    {{-- =========================================================
         POSTS TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">


        <div class="card-body">


            <div class="table-responsive">


                <table
                    id="postsTable"
                    class="table table-bordered
                           table-hover
                           align-middle
                           w-100">


                    <thead class="table-light">

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Image
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Categories
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Published
                            </th>

                            <th>
                                Featured
                            </th>

                            <th>
                                Important
                            </th>

                            <th>
                                Indexing
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>
                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>


@endsection



@push('styles')

<link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

@endpush



@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js">
</script>


<script
    src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js">
</script>


<script
    src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js">
</script>



<script>

    $(document).ready(function () {


        /*
        |--------------------------------------------------------------------------
        | DataTable
        |--------------------------------------------------------------------------
        */

        const postsTable =
            $('#postsTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: false,


                ajax: {

                    url:
                        "{{ route('admin.posts.data') }}",

                    type: "GET",


                    data: function (d) {

                        /*
                        |--------------------------------------------------------------------------
                        | Indexing Filter
                        |--------------------------------------------------------------------------
                        */

                        d.index_status =
                            $('#indexStatus').val();


                        /*
                        |--------------------------------------------------------------------------
                        | HTTP Status Filter
                        |--------------------------------------------------------------------------
                        */

                        d.http_status =
                            $('#httpStatus').val();

                    },


                    dataSrc: function (json) {


                        /*
                        |--------------------------------------------------------------------------
                        | Update Index Counts
                        |--------------------------------------------------------------------------
                        */

                        if (
                            typeof json.indexed_count !==
                            'undefined'
                        ) {

                            $('#indexedCount')
                                .text(
                                    json.indexed_count
                                );

                        }


                        if (
                            typeof json.not_indexed_count !==
                            'undefined'
                        ) {

                            $('#notIndexedCount')
                                .text(
                                    json.not_indexed_count
                                );

                        }


                        return json.data;

                    }

                },


                pageLength: 10,


                lengthMenu: [

                    [10, 25, 50, 100],

                    [10, 25, 50, 100]

                ],


                order: [

                    [0, 'desc']

                ],


                columns: [


                    /*
                    |--------------------------------------------------------------------------
                    | No
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'DT_RowIndex',

                        name:
                            'DT_RowIndex',

                        orderable:
                            false,

                        searchable:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Image
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'image',

                        name:
                            'image',

                        orderable:
                            false,

                        searchable:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Title
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'title',

                        name:
                            'title'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Multiple Categories
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'category',

                        name:
                            'category',

                        orderable:
                            false,

                        searchable:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'status_badge',

                        name:
                            'status',

                        orderable:
                            true,

                        searchable:
                            true

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Published
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'published_date',

                        name:
                            'published_at'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Featured
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'featured_badge',

                        name:
                            'is_featured',

                        orderable:
                            true,

                        searchable:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Important
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'important_badge',

                        name:
                            'is_important',

                        orderable:
                            true,

                        searchable:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Indexing
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'indexing',

                        name:
                            'indexing',

                        orderable:
                            false,

                        searchable:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Action
                    |--------------------------------------------------------------------------
                    */

                    {

                        data:
                            'action',

                        name:
                            'action',

                        orderable:
                            false,

                        searchable:
                            false

                    }

                ]

            });



        /*
        |--------------------------------------------------------------------------
        | Indexing Filter
        |--------------------------------------------------------------------------
        */

        $('#indexStatus').on(
            'change',
            function () {

                postsTable
                    .ajax
                    .reload();

            }
        );



        /*
        |--------------------------------------------------------------------------
        | HTTP Status Filter
        |--------------------------------------------------------------------------
        */

        $('#httpStatus').on(
            'change',
            function () {

                postsTable
                    .ajax
                    .reload();

            }
        );


    });

</script>

@endpush