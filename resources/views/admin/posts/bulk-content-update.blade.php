@extends('layouts.admin')

@section('title', 'Bulk Content Update')

@section('content')

<div class="container-fluid py-4">

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">
                    <h4 class="mb-1">Bulk Post Content Update</h4>
                    <small class="text-muted">
                        JSON ma Post ID → Content aapi ne multiple posts ek sathe update karo.
                    </small>
                </div>

                <div class="card-body">

                    {{-- SUCCESS MESSAGE --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ERROR MESSAGE --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- VALIDATION ERRORS --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="alert alert-warning">
                        <strong>Important:</strong>
                        Aa tool only <code>content</code> field update kare chhe.
                        Title, slug, SEO, images, category, status, indexing etc. change nahi thase.
                    </div>


                    <form action="{{ route('admin.bulk-content-update.save') }}"
                          method="POST">

                        @csrf

                        <div class="mb-3">

                            <label for="json_data" class="form-label fw-semibold">
                                JSON Data
                            </label>

                            <textarea
                                name="json_data"
                                id="json_data"
                                class="form-control font-monospace"
                                rows="25"
                                placeholder='{
    "1": "<p>Rewritten content for post 1...</p>",
    "2": "<p>Rewritten content for post 2...</p>",
    "3": "<p>Rewritten content for post 3...</p>"
}'>{{ old('json_data') }}</textarea>

                        </div>


                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary"
                                    onclick="return confirm('Are you sure you want to update these posts?');">
                                Update All Posts
                            </button>

                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="document.getElementById('json_data').value = '';">
                                Clear
                            </button>

                        </div>

                    </form>


                    <hr class="my-4">


                    <h5 class="mb-3">
                        JSON Example
                    </h5>

                    <pre class="bg-light border rounded p-3 mb-0"><code>{
    "1": "&lt;p&gt;This is rewritten content for post 1.&lt;/p&gt;",
    "2": "&lt;p&gt;This is rewritten content for post 2.&lt;/p&gt;",
    "3": "&lt;p&gt;This is rewritten content for post 3.&lt;/p&gt;"
}</code></pre>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection