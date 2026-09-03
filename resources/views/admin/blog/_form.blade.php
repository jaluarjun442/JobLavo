<form
    action="{{ $formAction }}"
    method="POST"
    enctype="multipart/form-data"
>

    @csrf

    @if($formMethod !== 'POST')

        @method($formMethod)

    @endif


    {{-- =========================================================
         BASIC INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Blog Information
            </h5>

        </div>


        <div class="card-body">


            {{-- TITLE --}}

            <div class="mb-3">

                <label
                    for="title"
                    class="form-label fw-semibold"
                >
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $blog?->title) }}"
                    required
                >

                @error('title')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- SLUG --}}

            <div class="mb-3">

                <label
                    for="slug"
                    class="form-label fw-semibold"
                >
                    Slug
                </label>

                <input
                    type="text"
                    id="slug"
                    name="slug"
                    class="form-control"
                    value="{{ old('slug', $blog?->slug) }}"
                    placeholder="Leave blank to generate automatically"
                >

                @error('slug')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- CONTENT --}}

            <div class="mb-3">

                <label
                    for="content"
                    class="form-label fw-semibold"
                >
                    Content
                </label>

                <textarea
                    id="content"
                    name="content"
                    rows="18"
                    class="form-control"
                    required
                >{{ old('content', $blog?->content) }}</textarea>

                @error('content')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

    </div>


    {{-- =========================================================
         IMAGES
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Blog Images
            </h5>

        </div>


        <div class="card-body">


            {{-- DESKTOP IMAGE --}}

            <div class="mb-4">

                <label
                    for="desktop_image"
                    class="form-label fw-semibold"
                >
                    Desktop Image
                </label>

                <input
                    type="file"
                    id="desktop_image"
                    name="desktop_image"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp"
                >


                @if($blog?->desktop_image)

                    <div class="mt-3">

                        <img
                            src="{{ asset($blog->desktop_image) }}"
                            alt="{{ $blog->title }}"
                            width="320"
                            height="180"
                            class="img-fluid rounded"
                        >

                    </div>

                @endif


                @error('desktop_image')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- MOBILE IMAGE --}}

            <div>

                <label
                    for="mobile_image"
                    class="form-label fw-semibold"
                >
                    Mobile Image
                </label>

                <input
                    type="file"
                    id="mobile_image"
                    name="mobile_image"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp"
                >


                @if($blog?->mobile_image)

                    <div class="mt-3">

                        <img
                            src="{{ asset($blog->mobile_image) }}"
                            alt="{{ $blog->title }}"
                            width="320"
                            height="180"
                            class="img-fluid rounded"
                        >

                    </div>

                @endif


                @error('mobile_image')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

    </div>


    {{-- =========================================================
         SEO
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                SEO Settings
            </h5>

        </div>


        <div class="card-body">


            {{-- SEO TITLE --}}

            <div class="mb-3">

                <label
                    for="seo_title"
                    class="form-label fw-semibold"
                >
                    SEO Title
                </label>

                <input
                    type="text"
                    id="seo_title"
                    name="seo_title"
                    class="form-control"
                    value="{{ old('seo_title', $blog?->seo_title) }}"
                >

                @error('seo_title')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- META DESCRIPTION --}}

            <div class="mb-3">

                <label
                    for="meta_description"
                    class="form-label fw-semibold"
                >
                    Meta Description
                </label>

                <textarea
                    id="meta_description"
                    name="meta_description"
                    rows="4"
                    class="form-control"
                >{{ old('meta_description', $blog?->meta_description) }}</textarea>

                @error('meta_description')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- META KEYWORDS --}}

            <div class="mb-3">

                <label
                    for="meta_keywords"
                    class="form-label fw-semibold"
                >
                    Meta Keywords
                </label>

                <textarea
                    id="meta_keywords"
                    name="meta_keywords"
                    rows="3"
                    class="form-control"
                >{{ old('meta_keywords', $blog?->meta_keywords) }}</textarea>

                @error('meta_keywords')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- CANONICAL --}}

            <div>

                <label
                    for="canonical_url"
                    class="form-label fw-semibold"
                >
                    Canonical URL
                </label>

                <input
                    type="url"
                    id="canonical_url"
                    name="canonical_url"
                    class="form-control"
                    value="{{ old('canonical_url', $blog?->canonical_url) }}"
                    placeholder="Leave blank to generate automatically"
                >

                @error('canonical_url')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

    </div>


    {{-- =========================================================
         PUBLISHING
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Publishing
            </h5>

        </div>


        <div class="card-body">


            <div class="row g-3">


                {{-- PUBLISHED DATE --}}

                <div class="col-md-6">

                    <label
                        for="published_date"
                        class="form-label fw-semibold"
                    >
                        Published Date
                    </label>

                    <input
                        type="datetime-local"
                        id="published_date"
                        name="published_date"
                        class="form-control"
                        value="{{ old(
                            'published_date',
                            $blog?->published_date
                                ? $blog->published_date->format('Y-m-d\TH:i')
                                : now()->format('Y-m-d\TH:i')
                        ) }}"
                    >

                    @error('published_date')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- PUBLISHED BY --}}

                <div class="col-md-6">

                    <label
                        for="published_by"
                        class="form-label fw-semibold"
                    >
                        Published By
                    </label>

                    <input
                        type="text"
                        id="published_by"
                        name="published_by"
                        class="form-control"
                        value="{{ old(
                            'published_by',
                            $blog?->published_by ?? 'Manisha Jalu'
                        ) }}"
                    >

                    @error('published_by')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SUBMIT
    ========================================================== --}}

    <div class="d-flex justify-content-end gap-2">

        <a
            href="{{ route('admin.blog.index') }}"
            class="btn btn-outline-secondary"
        >
            Cancel
        </a>


        <button
            type="submit"
            class="btn btn-primary"
        >

            {{ $submitText }}

        </button>

    </div>

</form>