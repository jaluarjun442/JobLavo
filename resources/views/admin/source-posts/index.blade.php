@extends('layouts.admin')


@section('title', $source->name . ' Posts | Admin')


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

            {{ $source->name }}

            @if($status === 'unread')

            <span class="text-warning">
                Unread Posts
            </span>

            @else

            <span class="text-success">
                Read Posts
            </span>

            @endif

        </h1>


        <p class="text-secondary mb-0">

            {{ $posts->count() }} post(s)

        </p>

    </div>


    <div class="d-flex gap-2">

        <a
            href="{{ route(
                'admin.sources.posts',
                [
                    'source' => $source->id,
                    'status' => 'unread'
                ]
            ) }}"
            class="btn
                   {{ $status === 'unread'
                        ? 'btn-warning'
                        : 'btn-outline-warning' }}">

            Unread

        </a>


        <a
            href="{{ route(
                'admin.sources.posts',
                [
                    'source' => $source->id,
                    'status' => 'read'
                ]
            ) }}"
            class="btn
                   {{ $status === 'read'
                        ? 'btn-success'
                        : 'btn-outline-success' }}">

            Read

        </a>


        <a
            href="{{ route('admin.sources.index') }}"
            class="btn btn-outline-secondary">

            ← Sources

        </a>

    </div>

</div>



<div class="card border-0 shadow-sm">


    <div class="card-body">


        {{-- TOP CONTROLS --}}

        <div class="d-flex
                    flex-wrap
                    justify-content-between
                    align-items-center
                    gap-2
                    mb-3">


            <div>

                <button
                    type="button"
                    id="selectAllBtn"
                    class="btn btn-sm btn-outline-primary">

                    Select All

                </button>


                <button
                    type="button"
                    id="clearAllBtn"
                    class="btn btn-sm btn-outline-secondary">

                    Clear All

                </button>

            </div>


            <button
                type="button"
                id="copyTitlesBtn"
                class="btn btn-sm btn-primary">

                Copy Selected Titles

            </button>

        </div>



        {{-- POSTS --}}

        @forelse($posts as $post)


        <div
            class="border
                       rounded
                       p-3
                       mb-2
                       source-post-row">


            <div class="d-flex
                            align-items-start
                            gap-3">


                <div class="pt-1">

                    <input
                        type="checkbox"
                        class="form-check-input
                                   post-checkbox"
                        value="{{ $post->id }}"
                        data-title="{{ $post->title }}">

                </div>


                <div class="flex-grow-1">


                    <div class="fw-semibold">

                        {{ $post->title }}

                    </div>


                    <div
                        class="small
                                   text-secondary
                                   mt-1">

                        {{ $post->published_at
                                ? $post->published_at->format(
                                    'd M Y H:i'
                                )
                                : 'No date'
                            }}

                    </div>


                </div>


                <a
                    href="{{ $post->source_url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn
                               btn-sm
                               btn-outline-secondary">

                    View

                </a>


            </div>


        </div>


        @empty


        <div
            class="text-center
                       text-secondary
                       py-5">

            No
            {{ $status === 'unread'
                    ? 'unread'
                    : 'read'
                }}
            posts found.

        </div>


        @endforelse


    </div>


</div>


@endsection



@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function() {


            const checkboxes =
                document.querySelectorAll(
                    '.post-checkbox'
                );


            const selectAllBtn =
                document.getElementById(
                    'selectAllBtn'
                );


            const clearAllBtn =
                document.getElementById(
                    'clearAllBtn'
                );


            const copyTitlesBtn =
                document.getElementById(
                    'copyTitlesBtn'
                );


            /*
            |--------------------------------------------------------------------------
            | Select All
            |--------------------------------------------------------------------------
            */

            selectAllBtn.addEventListener(
                'click',
                function() {

                    checkboxes.forEach(
                        function(checkbox) {

                            checkbox.checked = true;

                        }
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Clear All
            |--------------------------------------------------------------------------
            */

            clearAllBtn.addEventListener(
                'click',
                function() {

                    checkboxes.forEach(
                        function(checkbox) {

                            checkbox.checked = false;

                        }
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Copy Selected Titles
            |--------------------------------------------------------------------------
            */

            copyTitlesBtn.addEventListener(
                'click',
                async function() {

                    const selected =
                        Array.from(
                            document.querySelectorAll(
                                '.post-checkbox:checked'
                            )
                        );


                    if (selected.length === 0) {

                        alert(
                            'Please select at least one post.'
                        );

                        return;

                    }


                    const titles =
                        selected.map(
                            function(checkbox) {

                                return checkbox
                                    .dataset
                                    .title;

                            }
                        );


                    const ids =
                        selected.map(
                            function(checkbox) {

                                return checkbox.value;

                            }
                        );


                    const text =
                        titles.join("\n");


                    try {

                        /*
                        |--------------------------------------------------------------------------
                        | Copy Titles
                        |--------------------------------------------------------------------------
                        */

                        await navigator
                            .clipboard
                            .writeText(text);


                        /*
                        |--------------------------------------------------------------------------
                        | Mark Selected Posts As Read
                        |--------------------------------------------------------------------------
                        */

                        const response =
                            await fetch(
                                "{{ route('admin.source-posts.mark-read') }}", {

                                    method: 'POST',

                                    headers: {

                                        'Content-Type': 'application/json',

                                        'X-CSRF-TOKEN': "{{ csrf_token() }}",

                                        'Accept': 'application/json'

                                    },

                                    body: JSON.stringify({
                                        ids: ids
                                    })

                                }
                            );


                        const result =
                            await response.json();


                        if (result.success) {

                            copyTitlesBtn.textContent =
                                'Copied & Marked Read';


                            setTimeout(
                                function() {

                                    window.location.reload();

                                },
                                800
                            );

                        }


                    } catch (error) {

                        alert(
                            'Could not copy titles.'
                        );

                    }

                }
            );


        }
    );
</script>

@endpush