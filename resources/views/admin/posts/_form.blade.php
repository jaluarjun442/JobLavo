<form action="{{ $formAction }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    @if($formMethod === 'PUT')
    @method('PUT')
    @endif
    @if(request()->has('ai_queue'))

    <input
        type="hidden"
        name="ai_queue"
        value="{{ request('ai_queue') }}">

    @endif

    {{-- ERROR MESSAGE --}}

    @if($errors->any())

    <div class="alert alert-danger mb-4">

        <div class="fw-bold mb-2">
            Please fix the following errors:
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    <div class="row g-4">


        {{-- =====================================================
             LEFT COLUMN
        ====================================================== --}}

        <div class="col-lg-8">


            {{-- BASIC INFORMATION --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white fw-bold py-3">

                    Basic Information

                </div>


                <div class="card-body p-3 p-md-4">


                    {{-- CATEGORY --}}

                    <div class="mb-3">

                        <label for="category_id"
                            class="form-label fw-semibold">

                            Category <span class="text-danger">*</span>

                        </label>


                      <div class="mb-3">

                            <label
                                for="category_ids"
                                class="form-label fw-semibold"
                            >
                                Categories
                            </label>

                            @php
                                $selectedCategoryIds = old(
                                    'category_ids',
                                    $post->categories->pluck('id')->toArray()
                                );
                            @endphp

                            <select
                                name="category_ids[]"
                                id="category_ids"
                                class="form-select"
                                multiple
                            >

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        {{ in_array($category->id, $selectedCategoryIds) ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Select one or multiple categories.
                            </div>

                        </div>

                    </div>


                    {{-- TITLE --}}

                    <div class="mb-3">

                        <label for="title"
                            class="form-label fw-semibold">

                            Post Title <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                            id="title"
                            name="title"
                            class="form-control"
                            value="{{ old('title', isset($post->title) ? $post->title : '') }}"
                            maxlength="255"
                            placeholder="Example: SSC CGL Recruitment 2026"
                            required>

                    </div>


                    {{-- SLUG --}}

                    <div class="mb-3">

                        <label for="slug"
                            class="form-label fw-semibold">

                            Slug

                        </label>


                        <input type="text"
                            id="slug"
                            name="slug"
                            class="form-control"
                            value="{{ old('slug', isset($post->slug) ? $post->slug : '') }}"
                            maxlength="255"
                            placeholder="ssc-cgl-recruitment-2026">


                        <div class="form-text">
                            Leave blank to generate slug automatically from title.
                        </div>

                    </div>


                    {{-- EXCERPT --}}

                    <div class="mb-3">

                        <label for="excerpt"
                            class="form-label fw-semibold">

                            Excerpt

                        </label>


                        <textarea id="excerpt"
                            name="excerpt"
                            rows="3"
                            maxlength="1000"
                            class="form-control"
                            placeholder="Short summary shown on listings...">{{ old('excerpt', isset($post->excerpt) ? $post->excerpt : '') }}</textarea>

                    </div>


                    {{-- SHORT DESCRIPTION --}}

                    <div class="mb-3">

                        <label for="short_description"
                            class="form-label fw-semibold">

                            Short Description

                        </label>


                        <textarea id="short_description"
                            name="short_description"
                            rows="4"
                            class="form-control"
                            placeholder="Brief description of this government job...">{{ old('short_description', isset($post->short_description) ? $post->short_description : '') }}</textarea>

                    </div>


                    {{-- CONTENT --}}

                    <div>

                        <label for="content"
                            class="form-label fw-semibold">

                            Main Content

                        </label>


                        <textarea id="content"
                            name="content"
                            rows="16"
                            class="form-control"
                            placeholder="Write complete job information here...">{{ old('content', isset($post->content) ? $post->content : '') }}</textarea>

                    </div>

                </div>

            </div>



            {{-- =====================================================
                 JOB DETAILS
            ====================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white fw-bold py-3">

                    Job Details

                </div>


                <div class="card-body p-3 p-md-4">


                    <div class="mb-3">

                        <label for="important_dates"
                            class="form-label fw-semibold">

                            Important Dates

                        </label>


                        <textarea id="important_dates"
                            name="important_dates"
                            rows="6"
                            class="form-control"
                            placeholder="Application Start:&#10;Last Date:&#10;Exam Date:&#10;Admit Card:&#10;Result:">{{ old('important_dates', isset($post->important_dates) ? $post->important_dates : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="application_fee"
                            class="form-label fw-semibold">

                            Application Fee

                        </label>


                        <textarea id="application_fee"
                            name="application_fee"
                            rows="4"
                            class="form-control">{{ old('application_fee', isset($post->application_fee) ? $post->application_fee : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="age_limit"
                            class="form-label fw-semibold">

                            Age Limit

                        </label>


                        <textarea id="age_limit"
                            name="age_limit"
                            rows="4"
                            class="form-control">{{ old('age_limit', isset($post->age_limit) ? $post->age_limit : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="vacancy_details"
                            class="form-label fw-semibold">

                            Vacancy Details

                        </label>


                        <textarea id="vacancy_details"
                            name="vacancy_details"
                            rows="6"
                            class="form-control">{{ old('vacancy_details', isset($post->vacancy_details) ? $post->vacancy_details : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="eligibility"
                            class="form-label fw-semibold">

                            Eligibility

                        </label>


                        <textarea id="eligibility"
                            name="eligibility"
                            rows="6"
                            class="form-control">{{ old('eligibility', isset($post->eligibility) ? $post->eligibility : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="selection_process"
                            class="form-label fw-semibold">

                            Selection Process

                        </label>


                        <textarea id="selection_process"
                            name="selection_process"
                            rows="5"
                            class="form-control">{{ old('selection_process', isset($post->selection_process) ? $post->selection_process : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="salary_details"
                            class="form-label fw-semibold">

                            Salary Details

                        </label>


                        <textarea id="salary_details"
                            name="salary_details"
                            rows="5"
                            class="form-control">{{ old('salary_details', isset($post->salary_details) ? $post->salary_details : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="how_to_apply"
                            class="form-label fw-semibold">

                            How To Apply

                        </label>


                        <textarea id="how_to_apply"
                            name="how_to_apply"
                            rows="7"
                            class="form-control">{{ old('how_to_apply', isset($post->how_to_apply) ? $post->how_to_apply : '') }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label for="important_links"
                            class="form-label fw-semibold">

                            Important Links

                        </label>


                        <textarea id="important_links"
                            name="important_links"
                            rows="7"
                            class="form-control"
                            placeholder="Apply Online: https://...&#10;Official Notification: https://...">{{ old('important_links', isset($post->important_links) ? $post->important_links : '') }}</textarea>

                    </div>


                    <div>

                        <label for="official_website"
                            class="form-label fw-semibold">

                            Official Website

                        </label>


                        <input type="text"
                            id="official_website"
                            name="official_website"
                            class="form-control"
                            value="{{ old('official_website', isset($post->official_website) ? $post->official_website : '') }}"
                            placeholder="https://example.gov.in">

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             RIGHT COLUMN
        ====================================================== --}}

        <div class="col-lg-4">


            {{-- PUBLISHING --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white fw-bold py-3">

                    Publishing

                </div>


                <div class="card-body">


                    {{-- STATUS --}}

                    <div class="mb-3">

                        <label for="status"
                            class="form-label fw-semibold">

                            Status

                        </label>


                        <select id="status"
                            name="status"
                            class="form-select">


                            <option value="draft"
                                {{ old('status', isset($post->status) ? $post->status : 'draft') == 'draft' ? 'selected' : '' }}>

                                Draft

                            </option>


                            <option value="published"
                                {{ old('status', isset($post->status) ? $post->status : '') == 'published' ? 'selected' : '' }}>

                                Published

                            </option>


                        </select>

                    </div>


                    {{-- PUBLISHED DATE --}}

                    <div class="mb-3">

                        <label for="published_at"
                            class="form-label fw-semibold">

                            Published Date

                        </label>


                        <input type="datetime-local"
                            id="published_at"
                            name="published_at"
                            class="form-control"
                            value="{{ old('published_at', isset($post->published_at) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">

                    </div>


                    {{-- FEATURED --}}

                    <div class="form-check form-switch mb-3">

                        <input type="hidden"
                            name="is_featured"
                            value="0">


                        <input type="checkbox"
                            class="form-check-input"
                            id="is_featured"
                            name="is_featured"
                            value="1"
                            {{ old('is_featured', isset($post->is_featured) ? $post->is_featured : false) ? 'checked' : '' }}>


                        <label for="is_featured"
                            class="form-check-label">

                            Featured Post

                        </label>

                    </div>


                    {{-- IMPORTANT --}}

                    <div class="form-check form-switch">

                        <input type="hidden"
                            name="is_important"
                            value="0">


                        <input type="checkbox"
                            class="form-check-input"
                            id="is_important"
                            name="is_important"
                            value="1"
                            {{ old('is_important', isset($post->is_important) ? $post->is_important : false) ? 'checked' : '' }}>


                        <label for="is_important"
                            class="form-check-label">

                            Important Post

                        </label>

                    </div>

                </div>

            </div>



            {{-- =====================================================
                 FEATURED IMAGE
            ====================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white fw-bold py-3">

                    Featured Image

                </div>


                <div class="card-body">


                    @if(isset($post->featured_image) && $post->featured_image)

                    <div class="mb-3">

                        <img src="{{ asset($post->featured_image) }}"
                            alt="{{ $post->title }}"
                            class="img-fluid rounded border"
                            style="max-height: 200px; width: 100%; object-fit: cover;">

                    </div>

                    @endif


                    <input type="file"
                        id="featured_image"
                        name="featured_image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp">


                    <div class="form-text">

                        JPG, JPEG, PNG or WebP. Maximum 5 MB.

                    </div>

                </div>

            </div>



            {{-- =====================================================
                 SEO
            ====================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white fw-bold py-3">

                    SEO Settings

                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <label for="seo_title"
                            class="form-label fw-semibold">

                            SEO Title

                        </label>


                        <input type="text"
                            id="seo_title"
                            name="seo_title"
                            class="form-control"
                            maxlength="255"
                            value="{{ old('seo_title', isset($post->seo_title) ? $post->seo_title : '') }}">

                    </div>


                    <div class="mb-3">

                        <label for="meta_description"
                            class="form-label fw-semibold">

                            Meta Description

                        </label>


                        <textarea id="meta_description"
                            name="meta_description"
                            rows="5"
                            maxlength="500"
                            class="form-control">{{ old('meta_description', isset($post->meta_description) ? $post->meta_description : '') }}</textarea>


                        <div class="form-text">

                            Keep it clear and useful for search results.

                        </div>

                    </div>


                    <div class="mb-3">

                        <label for="meta_keywords"
                            class="form-label fw-semibold">

                            Meta Keywords

                        </label>


                        <textarea id="meta_keywords"
                            name="meta_keywords"
                            rows="3"
                            maxlength="500"
                            class="form-control">{{ old('meta_keywords', isset($post->meta_keywords) ? $post->meta_keywords : '') }}</textarea>

                    </div>


                    <div>

                        <label for="canonical_url"
                            class="form-label fw-semibold">

                            Canonical URL

                        </label>


                        <input type="url"
                            id="canonical_url"
                            name="canonical_url"
                            class="form-control"
                            maxlength="255"
                            value="{{ old('canonical_url', isset($post->canonical_url) ? $post->canonical_url : '') }}"
                            placeholder="https://example.com/post/example">

                    </div>

                </div>

            </div>


        </div>

    </div>



    {{-- =====================================================
         SAVE BUTTON
    ====================================================== --}}

    <div class="d-flex flex-wrap gap-2 mt-4 mb-4">

        <button type="submit"
            class="btn btn-primary px-4">

            {{ $submitText }}

        </button>


        <a href="{{ route('admin.posts.index') }}"
            class="btn btn-outline-secondary">

            Cancel

        </a>

    </div>


</form>
@push('scripts')

<script>
$(document).ready(function () {

    $('#category_ids').select2({
        placeholder: 'Select categories',
        width: '100%',
        allowClear: true
    });

});
</script>

@endpush