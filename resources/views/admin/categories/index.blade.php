@extends('layouts.admin')

@section('title', 'Categories | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Categories
        </h1>

        <p class="text-secondary mb-0">
            Manage government job categories and sub categories.
        </p>

    </div>


    <a href="{{ route('admin.categories.create') }}"
        class="btn btn-primary">

        + Add Category

    </a>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table id="categoriesTable"
                class="table table-bordered table-hover align-middle w-100">

                <thead class="table-light">

                    <tr>

                        <th>No</th>

                        <th>Name</th>

                        <th>Parent</th>

                        <th>Slug</th>

                        <th>Posts</th>

                        <th>Status</th>

                        <th>Action</th>

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

<link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

@endpush


@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>


<script>
    $(document).ready(function() {

        $('#categoriesTable').DataTable({

            processing: true,

            serverSide: true,

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],

            ajax: {
                url: "{{ route('admin.categories.data') }}",
                type: "GET"
            },
            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'parent',
                    name: 'parent',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'slug',
                    name: 'slug'
                },

                {
                    data: 'posts_count',
                    name: 'posts_count',
                    searchable: false
                },

                {
                    data: 'status_badge',
                    name: 'status',
                    orderable: true,
                    searchable: false
                },

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