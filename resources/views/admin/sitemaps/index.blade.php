@extends('layouts.admin')

@section('title', 'Sitemap | Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Sitemap
        </h1>

        <p class="text-secondary mb-0">
            Manage website and post sitemaps.
        </p>

    </div>
    <form
        action="{{ route('admin.sitemaps.refresh') }}"
        method="POST"
        class="d-inline">

        @csrf

        <button
            type="submit"
            class="btn btn-primary">

            ↻ Refresh Sitemaps

        </button>

    </form>
    <a href="{{ url('/sitemap.xml') }}"
        target="_blank"
        class="btn btn-outline-primary">

        View Main Sitemap

    </a>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Sitemap</th>

                        <th>Type</th>

                        <th>URLs</th>

                        <th>Status</th>

                        <th>Submitted</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($sitemaps as $index => $sitemap)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>

                            <strong>
                                {{ $sitemap->filename }}
                            </strong>
                            <div class="small text-muted mt-1">

                                {{ url('/' . $sitemap->filename) }}

                            </div>
                        </td>

                        <td>

                            @if($sitemap->type === 'main')

                            <span class="badge bg-primary">
                                Main
                            </span>

                            @else

                            <span class="badge bg-secondary">
                                Posts
                            </span>

                            @endif

                        </td>

                        <td>
                            {{ $sitemap->url_count }}
                        </td>

                        <td>

                            @switch($sitemap->status)

                            @case('not_submitted')
                            <span class="badge bg-secondary">
                                Not Submitted
                            </span>
                            @break

                            @case('submitted')
                            <span class="badge bg-primary">
                                Submitted to Google
                            </span>
                            @break

                            @case('processing')
                            <span class="badge bg-warning text-dark">
                                Processing
                            </span>
                            @break

                            @case('indexed')
                            <span class="badge bg-success">
                                Indexed
                            </span>
                            @break

                            @case('error')
                            <span class="badge bg-danger">
                                Error
                            </span>
                            @break

                            @endswitch

                        </td>

                        <td>

                            {{ $sitemap->submitted_at
                                    ? $sitemap->submitted_at->format('d M Y H:i')
                                    : '—'
                                }}

                        </td>

                        <td>

                            <div class="d-flex gap-2">

                                @if($sitemap->filename === 'sitemap.xml')

                                <a href="{{ url('/sitemap.xml') }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                                @else

                                <a href="{{ url('/' . $sitemap->filename) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                                @endif


                                <a href="{{ route(
                                        'admin.sitemaps.edit',
                                        $sitemap
                                    ) }}"
                                    class="btn btn-sm btn-primary">

                                    Edit

                                </a>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-4">

                            No sitemap found.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection