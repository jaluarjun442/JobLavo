@extends('layouts.admin')

@section('content')

<div class="container-fluid">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="h3 mb-1">
                Categories
            </h1>

            <p class="text-muted mb-0">
                Manage parent categories and sub-categories.
            </p>

        </div>


        <a
            href="{{ route('admin.categories.create') }}"
            class="btn btn-primary">

            + Add Category

        </a>

    </div>



    {{-- =====================================================
         CATEGORY TABLE
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="categoriesTable"
                    class="table table-bordered table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Parent
                            </th>

                            <th>
                                Posts
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Header
                            </th>

                            <th>
                                Home Tiles
                            </th>

                            <th>
                                Home Large
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script
    src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js">
</script>

<script
    src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js">
</script>


<script>

$(document).ready(function() {

    $('#categoriesTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        ajax: "{{ route('admin.categories.data') }}",

        order: [
            [1, 'asc']
        ],

        columns: [

            /*
            |--------------------------------------------------------------------------
            | #
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
            | Category
            |--------------------------------------------------------------------------
            */

            {
                data: 'name',

                name: 'name'
            },


            /*
            |--------------------------------------------------------------------------
            | Parent
            |--------------------------------------------------------------------------
            */

            {
                data: 'parent',

                name: 'parent',

                orderable: false
            },


            /*
            |--------------------------------------------------------------------------
            | Posts
            |--------------------------------------------------------------------------
            */

            {
                data: 'posts_count',

                name: 'posts_count',

                searchable: false
            },


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            {
                data: 'status_badge',

                name: 'status',

                orderable: false,

                searchable: false
            },


            /*
            |--------------------------------------------------------------------------
            | Header
            |--------------------------------------------------------------------------
            */

            {
                data: 'header',

                name: 'display_header',

                orderable: false,

                searchable: false
            },


            /*
            |--------------------------------------------------------------------------
            | Home Tiles
            |--------------------------------------------------------------------------
            */

            {
                data: 'home_tiles',

                name: 'display_home_tiles',

                orderable: false,

                searchable: false
            },


            /*
            |--------------------------------------------------------------------------
            | Home Large
            |--------------------------------------------------------------------------
            */

            {
                data: 'home_large',

                name: 'display_home_large',

                orderable: false,

                searchable: false
            },


            /*
            |--------------------------------------------------------------------------
            | Action
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