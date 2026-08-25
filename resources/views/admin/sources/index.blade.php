@extends('layouts.admin')


@section('title', 'Sources | Admin')


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
            Sources
        </h1>

        <p class="text-secondary mb-0">
            Manage external platforms used for latest post imports.
        </p>

    </div>


    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#addSourceModal">

        + Add Source

    </button>
    <a target="_blank"
        href="{{ route('cron.source-fetch') }}"
        class="btn btn-dark">
        Fetch All
    </a>

</div>




@if($errors->any())

<div class="alert alert-danger">

    <ul class="mb-0">

        @foreach($errors->all() as $error)

        <li>
            {{ $error }}
        </li>

        @endforeach

    </ul>

</div>

@endif



<div class="card border-0 shadow-sm">


    <div class="card-body">


        <div class="table-responsive">


            <table
                class="table table-bordered
                       table-hover
                       align-middle
                       mb-0">


                <thead class="table-light">

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            Source
                        </th>

                        <th>
                            Feed URL
                        </th>

                        <th>
                            Latest Limit
                        </th>
                        <th>Unread</th>
                        <th>Read</th>
                        <th>
                            Status
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse($sources as $source)


                    <tr>


                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            <strong>
                                {{ $source->name }}
                            </strong>

                        </td>


                        <td>

                            <div
                                class="text-truncate"
                                style="max-width: 400px;">

                                <a
                                    href="{{ $source->feed_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer">

                                    {{ $source->feed_url }}

                                </a>

                            </div>

                        </td>


                        <td>

                            <span class="badge bg-light text-dark border">

                                {{ $source->latest_limit }}

                            </span>

                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $source->unread_posts_count }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-success">
                                {{ $source->read_posts_count }}
                            </span>
                        </td>
                        <td>

                            @if($source->status)

                            <span class="badge bg-success">
                                Active
                            </span>

                            @else

                            <span class="badge bg-secondary">
                                Inactive
                            </span>

                            @endif

                        </td>


                        <td>
                            <a
                                href="{{ route(
        'admin.sources.posts',
        [
            'source' => $source->id,
            'status' => 'unread'
        ]
    ) }}"
                                class="btn btn-sm btn-outline-warning">

                                Unread Posts

                            </a>


                            <a
                                href="{{ route(
        'admin.sources.posts',
        [
            'source' => $source->id,
            'status' => 'read'
        ]
    ) }}"
                                class="btn btn-sm btn-outline-success">

                                Read Posts

                            </a>
                            <form
                                action="{{ route(
                                'admin.sources.fetch',
                                $source->id
                            ) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-success">

                                    Fetch Now

                                </button>

                            </form>
                            <div class="d-flex gap-1">


                                {{-- EDIT --}}

                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSourceModal{{ $source->id }}">

                                    Edit

                                </button>


                                {{-- DELETE --}}

                                <form
                                    action="{{ route(
                                            'admin.sources.destroy',
                                            $source->id
                                        ) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this source?');">

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger">

                                        Delete

                                    </button>

                                </form>


                            </div>

                        </td>


                    </tr>


                    {{-- EDIT MODAL --}}

                    <div
                        class="modal fade"
                        id="editSourceModal{{ $source->id }}"
                        tabindex="-1"
                        aria-hidden="true">


                        <div class="modal-dialog modal-dialog-centered">


                            <div class="modal-content">


                                <form
                                    action="{{ route(
                                            'admin.sources.update',
                                            $source->id
                                        ) }}"
                                    method="POST">

                                    @csrf

                                    @method('PUT')


                                    <div class="modal-header">

                                        <h5 class="modal-title">
                                            Edit Source
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>

                                    </div>


                                    <div class="modal-body">


                                        <div class="mb-3">

                                            <label
                                                class="form-label fw-semibold">

                                                Source Name

                                            </label>

                                            <input
                                                type="text"
                                                name="name"
                                                class="form-control"
                                                value="{{ $source->name }}"
                                                required>

                                        </div>


                                        <div class="mb-3">

                                            <label
                                                class="form-label fw-semibold">

                                                Feed URL

                                            </label>

                                            <input
                                                type="url"
                                                name="feed_url"
                                                class="form-control"
                                                value="{{ $source->feed_url }}"
                                                required>

                                        </div>


                                        <div class="mb-3">

                                            <label
                                                class="form-label fw-semibold">

                                                Latest Posts Limit

                                            </label>

                                            <input
                                                type="number"
                                                name="latest_limit"
                                                class="form-control"
                                                min="1"
                                                max="50"
                                                value="{{ $source->latest_limit }}"
                                                required>

                                        </div>


                                        <div class="form-check">

                                            <input
                                                type="checkbox"
                                                name="status"
                                                value="1"
                                                class="form-check-input"
                                                id="editStatus{{ $source->id }}"
                                                <?php if ($source->status) echo 'checked'; ?>>

                                            <label
                                                for="editStatus{{ $source->id }}"
                                                class="form-check-label">

                                                Active Source

                                            </label>

                                        </div>


                                    </div>


                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">

                                            Cancel

                                        </button>


                                        <button
                                            type="submit"
                                            class="btn btn-primary">

                                            Update Source

                                        </button>

                                    </div>


                                </form>


                            </div>

                        </div>

                    </div>


                    @empty


                    <tr>

                        <td
                            colspan="6"
                            class="text-center
                                       text-secondary
                                       py-4">

                            No sources added yet.

                        </td>

                    </tr>


                    @endforelse


                </tbody>


            </table>


        </div>


    </div>


</div>



{{-- ADD SOURCE MODAL --}}

<div
    class="modal fade"
    id="addSourceModal"
    tabindex="-1"
    aria-hidden="true">


    <div class="modal-dialog modal-dialog-centered">


        <div class="modal-content">


            <form
                action="{{ route('admin.sources.store') }}"
                method="POST">

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Source
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Source Name

                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="MaruGujarat"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Feed URL

                        </label>

                        <input
                            type="url"
                            name="feed_url"
                            class="form-control"
                            placeholder="https://example.com/wp-json/wp/v2/posts"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Latest Posts Limit

                        </label>

                        <input
                            type="number"
                            name="latest_limit"
                            class="form-control"
                            min="1"
                            max="50"
                            value="10"
                            required>

                        <div class="form-text">

                            Only the latest posts will be checked.

                        </div>

                    </div>


                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="status"
                            value="1"
                            class="form-check-input"
                            id="sourceStatus"
                            checked>

                        <label
                            for="sourceStatus"
                            class="form-check-label">

                            Active Source

                        </label>

                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary">

                        Add Source

                    </button>

                </div>


            </form>


        </div>


    </div>


</div>


@endsection