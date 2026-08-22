@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="h3 mb-1">
                Add Category
            </h1>

            <p class="text-muted mb-0">
                Create a new job category or sub category.
            </p>

        </div>


        <a
            href="{{ route('admin.categories.index') }}"
            class="btn btn-outline-secondary">

            ← Back

        </a>

    </div>



    {{-- Validation Errors --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    <form
        action="{{ route('admin.categories.store') }}"
        method="POST">

        @csrf



        {{-- =====================================================
             BASIC INFORMATION
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Category Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- NAME --}}

                    <div class="col-md-8">

                        <label class="form-label">

                            Category Name
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control"
                            required>

                    </div>



                    {{-- PARENT CATEGORY --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Parent Category

                        </label>

                        <select
                            name="parent_id"
                            class="form-select">

                            <option value="">
                                Main Category
                            </option>


                            @foreach($parentCategories as $parent)

                                <option
                                    value="{{ $parent->id }}"
                                    {{ old('parent_id') == $parent->id ? 'selected' : '' }}>

                                    {{ $parent->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">

                            Leave as Main Category if this is
                            a parent category.

                        </div>

                    </div>



                    {{-- SLUG --}}

                    <div class="col-md-8">

                        <label class="form-label">

                            Slug

                        </label>

                        <input
                            type="text"
                            name="slug"
                            value="{{ old('slug') }}"
                            class="form-control">

                        <div class="form-text">

                            Leave blank to generate automatically.

                        </div>

                    </div>



                    {{-- STATUS --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Status

                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option
                                value="1"
                                {{ old('status', 1) == 1 ? 'selected' : '' }}>

                                Active

                            </option>

                            <option
                                value="0"
                                {{ old('status') === '0' ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>



                    {{-- DESCRIPTION --}}

                    <div class="col-12">

                        <label class="form-label">

                            Description

                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control">{{ old('description') }}</textarea>

                    </div>



                    {{-- CONTENT --}}

                    <div class="col-12">

                        <label class="form-label">

                            Content

                        </label>

                        <textarea
                            name="content"
                            rows="10"
                            class="form-control">{{ old('content') }}</textarea>

                        <div class="form-text">

                            HTML content is supported.

                        </div>

                    </div>



                </div>

            </div>

        </div>



        {{-- =====================================================
             HOME DISPLAY SETTINGS
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Home Page Display
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- HOME TILES --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="form-check form-switch">

                                <input
                                    type="checkbox"
                                    name="display_home_tiles"
                                    value="1"
                                    class="form-check-input"
                                    id="display_home_tiles"
                                    {{ old('display_home_tiles') ? 'checked' : '' }}>

                                <label
                                    class="form-check-label fw-semibold"
                                    for="display_home_tiles">

                                    Display in Home Tiles

                                </label>

                            </div>


                            <div class="text-muted small mt-2">

                                Enable this to show this category
                                in the small category tiles section
                                on the homepage.

                            </div>

                        </div>

                    </div>



                    {{-- HOME LARGE GRID --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="form-check form-switch">

                                <input
                                    type="checkbox"
                                    name="display_home_large"
                                    value="1"
                                    class="form-check-input"
                                    id="display_home_large"
                                    {{ old('display_home_large') ? 'checked' : '' }}>

                                <label
                                    class="form-check-label fw-semibold"
                                    for="display_home_large">

                                    Display in Home Large Grid

                                </label>

                            </div>


                            <div class="text-muted small mt-2">

                                Enable this to show this category
                                as a large job section on the homepage.

                            </div>

                        </div>

                    </div>



                </div>

            </div>

        </div>



        {{-- =====================================================
             SEO
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    SEO Settings
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- SEO TITLE --}}

                    <div class="col-12">

                        <label class="form-label">

                            SEO Title

                        </label>

                        <input
                            type="text"
                            name="seo_title"
                            value="{{ old('seo_title') }}"
                            class="form-control">

                    </div>



                    {{-- META DESCRIPTION --}}

                    <div class="col-12">

                        <label class="form-label">

                            Meta Description

                        </label>

                        <textarea
                            name="meta_description"
                            rows="4"
                            maxlength="500"
                            class="form-control">{{ old('meta_description') }}</textarea>

                    </div>



                    {{-- META KEYWORDS --}}

                    <div class="col-12">

                        <label class="form-label">

                            Meta Keywords

                        </label>

                        <textarea
                            name="meta_keywords"
                            rows="3"
                            maxlength="500"
                            class="form-control">{{ old('meta_keywords') }}</textarea>

                    </div>



                    {{-- SORT ORDER --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Sort Order

                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order', 0) }}"
                            min="0"
                            class="form-control">

                        <div class="form-text">

                            Lower number appears first.

                        </div>

                    </div>


                </div>

            </div>

        </div>



        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('admin.categories.index') }}"
                class="btn btn-outline-secondary">

                Cancel

            </a>


            <button
                type="submit"
                class="btn btn-primary">

                Save Category

            </button>

        </div>


    </form>

</div>

@endsection