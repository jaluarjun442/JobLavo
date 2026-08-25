@extends('layouts.admin')

@section('title', 'Logs | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Laravel Logs
        </h1>

        <p class="text-secondary mb-0">
            View and manage application logs.
        </p>

    </div>


    <div class="d-flex gap-2">

        @if($exists)

        <a
            href="{{ route('admin.logs.download') }}"
            class="btn btn-outline-primary">

            ↓ Download

        </a>


        <form
            action="{{ route('admin.logs.clear') }}"
            method="POST"
            class="d-inline"
            onsubmit="return confirm('Are you sure you want to clear the Laravel log?');">

            @csrf

            <button
                type="submit"
                class="btn btn-outline-danger">

                Clear Log

            </button>

        </form>

        @endif

    </div>

</div>



{{-- =========================================================
     LOG INFO
========================================================= --}}

<div class="row g-3 mb-4">


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="small text-secondary mb-1">
                    Log File
                </div>

                <div class="fw-semibold">
                    storage/logs/laravel.log
                </div>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="small text-secondary mb-1">
                    File Size
                </div>

                <div class="fw-semibold">

                    @if($exists)

                    {{ number_format($size / 1024, 2) }} KB

                    @else

                    0 KB

                    @endif

                </div>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="small text-secondary mb-1">
                    Last Modified
                </div>

                <div class="fw-semibold">

                    @if($lastModified)

                    {{ date('d M Y H:i:s', $lastModified) }}

                    @else

                    —

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     LOG LIST
========================================================= --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">


            <div>

                <h2 class="h5 fw-bold mb-1">
                    Log Entries
                </h2>

                <div class="small text-secondary">
                    Latest 100 entries
                </div>

            </div>


            @if(count($logs))

            <div style="min-width:280px;">

                <input
                    type="search"
                    id="logSearch"
                    class="form-control"
                    placeholder="Search logs..."
                    aria-label="Search logs">

            </div>

            @endif

        </div>

    </div>



    <div class="card-body p-0">


        @if(!$exists)


        <div class="text-center py-5">

            <h3 class="h5">
                No Log File
            </h3>

            <p class="text-muted mb-0">
                Laravel has not created a log file yet.
            </p>

        </div>


        @elseif(!count($logs))


        <div class="text-center py-5">

            <h3 class="h5">
                Log Is Empty
            </h3>

            <p class="text-muted mb-0">
                No errors or log entries are available.
            </p>

        </div>


        @else


        <div id="logList">

            @foreach($logs as $index => $log)

            @php

            $level = strtoupper(
            $log['level']
            );

            $badgeClass = match($level) {

            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY'
            => 'bg-danger',

            'WARNING'
            => 'bg-warning text-dark',

            'NOTICE'
            => 'bg-info text-dark',

            'DEBUG'
            => 'bg-secondary',

            default
            => 'bg-primary',

            };


            $icon = match($level) {

            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY'
            => '❌',

            'WARNING'
            => '⚠️',

            'NOTICE'
            => '🔔',

            'DEBUG'
            => '🔧',

            default
            => 'ℹ️',

            };

            @endphp


            <div
                class="log-entry border-bottom p-3"
                data-log-search="{{ strtolower(
                            $log['level'] . ' ' .
                            $log['datetime'] . ' ' .
                            $log['message']
                        ) }}">


                {{-- HEADER --}}

                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-2">


                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="badge {{ $badgeClass }}">

                            {{ $icon }}
                            {{ $level }}

                        </span>


                        <span class="small text-secondary">

                            {{ $log['environment'] }}

                        </span>

                    </div>


                    <div class="small text-secondary">

                        {{ $log['datetime'] }}

                    </div>

                </div>

{{-- MESSAGE --}}

@php

    $logMessage = $log['message'];

    $prettyJson = null;

    $logTitle = $logMessage;

    /*
    |--------------------------------------------------------------------------
    | Find JSON Object
    |--------------------------------------------------------------------------
    */

    $jsonStart = strpos($logMessage, '{');

    if ($jsonStart !== false) {

        $jsonText = trim(
            substr(
                $logMessage,
                $jsonStart
            )
        );


        $decodedJson = json_decode(
            $jsonText,
            true
        );


        if (
            json_last_error() === JSON_ERROR_NONE
            && is_array($decodedJson)
        ) {

            $prettyJson = json_encode(
                $decodedJson,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE
            );


            $logTitle = trim(
                substr(
                    $logMessage,
                    0,
                    $jsonStart
                )
            );

        }

    }

@endphp


<div class="log-message">

    @if($prettyJson)

        <div class="fw-semibold mb-2">
            {{ $logTitle }}
        </div>


        <pre
            class="mb-0 p-3"
            style="
                background:#f8f9fa;
                border:1px solid #dee2e6;
                border-radius:6px;
                font-size:13px;
                line-height:1.6;
                white-space:pre-wrap;
                word-break:break-word;
                overflow:auto;
                max-height:400px;
            ">{{ $prettyJson }}</pre>

    @else

        <div
            style="
                white-space:pre-wrap;
                word-break:break-word;
            ">

            {{ \Illuminate\Support\Str::limit(
                $logMessage,
                1000
            ) }}

        </div>

    @endif

</div>

                {{-- RAW DETAILS --}}

                <details class="mt-2">

                    <summary
                        class="small fw-semibold text-primary"
                        style="cursor:pointer;">

                        View Full Log

                    </summary>


                    <pre
                        class="mt-3 mb-0 p-3"
                        style="
                                    background:#111827;
                                    color:#e5e7eb;
                                    border-radius:6px;
                                    max-height:500px;
                                    overflow:auto;
                                    font-size:12px;
                                    line-height:1.6;
                                    white-space:pre-wrap;
                                    word-break:break-word;
                                ">{{ $log['raw'] }}</pre>

                </details>


            </div>

            @endforeach

        </div>


        {{-- NO SEARCH RESULT --}}

        <div
            id="noLogResults"
            class="text-center py-5 d-none">

            <h3 class="h6">
                No matching logs found.
            </h3>

            <p class="small text-muted mb-0">
                Try another search term.
            </p>

        </div>


        @endif


    </div>

</div>



@endsection



@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function() {

            const search =
                document.getElementById(
                    'logSearch'
                );


            const entries =
                document.querySelectorAll(
                    '.log-entry'
                );


            const noResults =
                document.getElementById(
                    'noLogResults'
                );


            if (!search) {
                return;
            }


            search.addEventListener(
                'input',
                function() {

                    const value =
                        this.value
                        .toLowerCase()
                        .trim();


                    let visible = 0;


                    entries.forEach(
                        function(entry) {

                            const text =
                                entry.dataset
                                .logSearch;


                            if (
                                !value ||
                                text.includes(value)
                            ) {

                                entry.style.display =
                                    '';

                                visible++;

                            } else {

                                entry.style.display =
                                    'none';
                            }

                        }
                    );


                    if (noResults) {

                        noResults.classList.toggle(
                            'd-none',
                            visible > 0
                        );
                    }

                }
            );

        }
    );
</script>

@endpush