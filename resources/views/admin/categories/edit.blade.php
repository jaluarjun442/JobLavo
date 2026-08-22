@extends('layouts.admin')

@section('title', 'Edit Category | Admin')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>

        <h1 class="h3 fw-bold mb-1">
            Edit Category
        </h1>

        <p class="text-secondary mb-0">
            Update {{ $category->name }}.
        </p>

    </div>


    <a href="{{ route('admin.categories.index') }}"
        class="btn btn-outline-secondary">

        ← Back

    </a>

</div>


<form action="{{ route('admin.categories.update', $category->id) }}"
    method="POST">

    @csrf

    @method('PUT')


    <div class="row g-4">


        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-bold py-3">
                    Category Information
                </div>


                <div class="card-body p-3 p-md-4">


                    <div class="mb-3">

                        <label for="name"
                            class="form-label fw-semibold">

                            Category Name

                        </label>


                        <input type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $category->name) }}"
                            maxlength="255"
                            required>

                    </div>


                    <div class="mb-3">

                        <label for="parent_id"
                            class="form-label fw-semibold">

                            Parent Category

                        </label>


                        <select id="parent_id"
                            name="parent_id"
                            class="form-select">

                            <option value="">
                                Main Category
                            </option>


                            @foreach($parentCategories as $parent)

                            <option value="{{ $parent->id }}"
                                {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>

                                {{ $parent->name }}

                            </option>

                            @endforeach

                        </select>


                        <div class="form-text">

                            Leave as Main Category if this is a top-level category.

                        </div>

                    </div>


                    <div class="mb-3">

                        <label for="slug"
                            class="form-label fw-semibold">

                            Slug

                        </label>


                        <input type="text"
                            id="slug"
                            name="slug"
                            class="form-control"
                            value="{{ old('slug', $category->slug) }}"
                            maxlength="255">

                    </div>


                    <div class="mb-3">

                        <label for="description"
                            class="form-label fw-semibold">

                            Description

                        </label>


                        <textarea id="description"
                            name="description"
                            rows="4"
                            class="form-control">{{ old('description', $category->description) }}</textarea>

                    </div>


                    <div>

                        <label for="content"
                            class="form-label fw-semibold">

                            Category Content

                        </label>


                        <textarea id="content"
                            name="content"
                            rows="8"
                            class="form-control">{{ old('content', $category->content) }}</textarea>

                    </div>


                </div>

            </div>

        </div>



        <div class="col-lg-4">


            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white fw-bold py-3">
                    Settings
                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <label for="sort_order"
                            class="form-label fw-semibold">

                            Sort Order

                        </label>


                        <input type="number"
                            id="sort_order"
                            name="sort_order"
                            min="0"
                            class="form-control"
                            value="{{ old('sort_order', $category->sort_order) }}">

                    </div>


                    <div class="form-check form-switch">

                        <input type="hidden"
                            name="status"
                            value="0">


                        <input type="checkbox"
                            class="form-check-input"
                            id="status"
                            name="status"
                            value="1"
                            {{ old('status', $category->status) ? 'checked' : '' }}>


                        <label for="status"
                            class="form-check-label">

                            Active Category

                        </label>

                    </div>

                </div>

            </div>



            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-bold py-3">
                    SEO
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
                            maxlength="255"
                            class="form-control"
                            value="{{ old('seo_title', $category->seo_title) }}">

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
                            class="form-control">{{ old('meta_description', $category->meta_description) }}</textarea>

                    </div>


                    <div>

                        <label for="meta_keywords"
                            class="form-label fw-semibold">

                            Meta Keywords

                        </label>


                        <textarea id="meta_keywords"
                            name="meta_keywords"
                            rows="3"
                            maxlength="500"
                            class="form-control">{{ old('meta_keywords', $category->meta_keywords) }}</textarea>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="mt-4 mb-4">

        <button type="submit"
            class="btn btn-primary px-4">

            Update Category

        </button>


        <a href="{{ route('admin.categories.index') }}"
            class="btn btn-outline-secondary ms-2">

            Cancel

        </a>

    </div>

</form>

@endsection