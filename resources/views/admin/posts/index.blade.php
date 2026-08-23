@extends('layouts.admin')


@section('title', 'Posts | Admin')


@section('content')


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


    <div class="d-flex gap-2">

        <a
            href="{{ route('admin.ai-jobs.import') }}"
            class="btn btn-dark">

            🤖 AI Import

        </a>


        <a
            href="{{ route('admin.posts.create') }}"
            class="btn btn-primary">

            + Add Post

        </a>

    </div>

</div>



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


        $('#postsTable').DataTable({


            processing: true,


            serverSide: true,


            responsive: false,



            ajax: {

                url: "{{ route('admin.posts.data') }}",

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
                | Multiple Categories
                |--------------------------------------------------------------------------
                */

                {
                    data: 'category',

                    name: 'category',

                    orderable: false,

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

                    orderable: true,

                    searchable: true
                },



                /*
                |--------------------------------------------------------------------------
                | Published
                |--------------------------------------------------------------------------
                */

                {
                    data: 'published_date',

                    name: 'published_at'
                },



                /*
                |--------------------------------------------------------------------------
                | Featured
                |--------------------------------------------------------------------------
                */

                {
                    data: 'featured_badge',

                    name: 'is_featured',

                    orderable: true,

                    searchable: false
                },



                /*
                |--------------------------------------------------------------------------
                | Important
                |--------------------------------------------------------------------------
                */

                {
                    data: 'important_badge',

                    name: 'is_important',

                    orderable: true,

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