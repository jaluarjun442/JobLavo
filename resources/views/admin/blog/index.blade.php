@extends('layouts.admin')

@section('title', 'Blogs | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>
        <h1 class="h3 fw-bold mb-1">
            Blogs
        </h1>

        <p class="text-secondary mb-0">
            Manage tips, guides and other blog posts.
        </p>
    </div>

    <a
        href="{{ route('admin.blog.create') }}"
        class="btn btn-primary"
    >
        + Add Blog
    </a>

</div>


{{-- =========================================================
     BLOG TABLE
========================================================= --}}

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="blogsTable"
                class="table table-bordered table-hover align-middle w-100"
            >

                <thead>

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
                            Published Date
                        </th>

                        <th>
                            Published By
                        </th>

                        <th>
                            Views
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>
                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@push('head')

{{-- DataTables CSS --}}

<link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"
>

@endpush


@push('scripts')

{{-- =========================================================
     DATATABLE JS
========================================================= --}}

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>


<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Blog DataTable
    |--------------------------------------------------------------------------
    */

    $('#blogsTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: false,

        ajax: {

            url: "{{ route('admin.blog.data') }}",

            type: "GET"

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

                data: 'DT_RowIndex',

                name: 'DT_RowIndex',

                orderable: false,

                searchable: false

            },


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            {

                data: 'image',

                name: 'image',

                orderable: false,

                searchable: false

            },


            /*
            |--------------------------------------------------------------------------
            | Title
            |--------------------------------------------------------------------------
            */

            {

                data: 'title',

                name: 'title'

            },


            /*
            |--------------------------------------------------------------------------
            | Published Date
            |--------------------------------------------------------------------------
            */

            {

                data: 'published_date',

                name: 'published_date'

            },


            /*
            |--------------------------------------------------------------------------
            | Published By
            |--------------------------------------------------------------------------
            */

            {

                data: 'published_by',

                name: 'published_by'

            },


            /*
            |--------------------------------------------------------------------------
            | Views
            |--------------------------------------------------------------------------
            */

            {

                data: 'views_count',

                name: 'views_count'

            },


            /*
            |--------------------------------------------------------------------------
            | Actions
            |--------------------------------------------------------------------------
            */

            {

                data: 'action',

                name: 'action',

                orderable: false,

                searchable: false

            }

        ]

    });


});

</script>

@endpush