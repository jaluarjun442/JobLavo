@extends('layouts.admin')


@section('title', 'Sitemap | Admin')


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

            Sitemap

        </h1>


        <p class="text-secondary mb-0">

            Manage website and post sitemaps.

        </p>

    </div>



    {{-- =====================================================
         ACTIONS
    ====================================================== --}}

    <div class="d-flex flex-wrap gap-2">


        {{-- REFRESH --}}

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



        {{-- MAIN SITEMAP --}}

        <a
            href="{{ url('/sitemap.xml') }}"
            target="_blank"
            rel="noopener"
            class="btn btn-outline-primary">

            View Main Sitemap

        </a>

    </div>

</div>



{{-- =========================================================
     SITEMAP TABLE
========================================================= --}}

<div class="card border-0 shadow-sm">


    <div class="card-body p-0">


        <div class="table-responsive">


            <table
                class="table
                       table-bordered
                       table-hover
                       mb-0
                       align-middle">


                <thead class="table-light">

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Sitemap
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            URLs
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Submitted
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>



                <tbody>


                    @forelse(
                        $sitemaps
                        as $index => $sitemap
                    )


                        <tr>


                            {{-- =================================================
                                 NUMBER
                            ================================================== --}}

                            <td>

                                {{ $index + 1 }}

                            </td>



                            {{-- =================================================
                                 SITEMAP
                            ================================================== --}}

                            <td>

                                <strong>

                                    {{ $sitemap->filename }}

                                </strong>


                                <div
                                    class="small text-muted mt-1">

                                    {{ url(
                                        '/' . $sitemap->filename
                                    ) }}

                                </div>

                            </td>



                            {{-- =================================================
                                 TYPE
                            ================================================== --}}

                            <td>


                                @if(
                                    $sitemap->type === 'main'
                                )

                                    <span
                                        class="badge bg-primary">

                                        Main

                                    </span>

                                @else

                                    <span
                                        class="badge bg-secondary">

                                        Posts

                                    </span>

                                @endif


                            </td>



                            {{-- =================================================
                                 URL COUNT
                            ================================================== --}}

                            <td>

                                <strong>

                                    {{ number_format(
                                        $sitemap->url_count
                                    ) }}

                                </strong>

                            </td>



                            {{-- =================================================
                                 STATUS
                            ================================================== --}}

                            <td>


                                @switch(
                                    $sitemap->status
                                )


                                    @case('not_submitted')

                                        <span
                                            class="badge bg-secondary">

                                            Not Submitted

                                        </span>

                                    @break



                                    @case('submitted')

                                        <span
                                            class="badge bg-primary">

                                            Submitted to Google

                                        </span>

                                    @break



                                    @case('processing')

                                        <span
                                            class="badge bg-warning text-dark">

                                            Processing

                                        </span>

                                    @break



                                    @case('indexed')

                                        <span
                                            class="badge bg-success">

                                            Indexed

                                        </span>

                                    @break



                                    @case('error')

                                        <span
                                            class="badge bg-danger">

                                            Error

                                        </span>

                                    @break



                                    @default

                                        <span
                                            class="badge bg-secondary">

                                            Not Submitted

                                        </span>

                                @endswitch


                            </td>



                            {{-- =================================================
                                 SUBMITTED
                            ================================================== --}}

                            <td>

                                @if(
                                    $sitemap->submitted_at
                                )

                                    {{ $sitemap->submitted_at->format(
                                        'd M Y H:i'
                                    ) }}

                                @else

                                    <span
                                        class="text-muted">

                                        —

                                    </span>

                                @endif

                            </td>



                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td>


                                <div
                                    class="d-flex
                                           flex-wrap
                                           gap-2">


                                    {{-- VIEW --}}

                                    <a
                                        href="{{ url(
                                            '/' .
                                            $sitemap->filename
                                        ) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="btn
                                               btn-sm
                                               btn-outline-primary">

                                        View

                                    </a>



                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'admin.sitemaps.edit',
                                            $sitemap
                                        ) }}"
                                        class="btn
                                               btn-sm
                                               btn-primary">

                                        Edit

                                    </a>


                                </div>


                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                <div
                                    class="fw-semibold
                                           text-dark
                                           mb-1">

                                    No sitemap found.

                                </div>


                                <div
                                    class="small text-muted">

                                    Click "Refresh Sitemaps"
                                    to generate sitemap records.

                                </div>

                            </td>

                        </tr>


                    @endforelse


                </tbody>


            </table>

        </div>

    </div>

</div>


@endsection