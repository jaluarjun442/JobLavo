<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class CategoryController extends Controller
{
    /**
     * Category List
     */
    public function index()
    {
        $categories = Category::orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }
    public function data(Request $request)
    {
        $query = Category::select('categories.*')
            ->with('parent')
            ->withCount('posts');


        return DataTables::eloquent($query)

            ->addIndexColumn()


            ->addColumn('parent', function ($category) {

                if ($category->parent) {

                    return '<span class="badge bg-light text-dark border">'
                        . e($category->parent->name) .
                        '</span>';
                }

                return '<span class="text-secondary">
                        Main Category
                    </span>';
            })


            ->addColumn('status_badge', function ($category) {

                if ($category->status) {

                    return '<span class="badge bg-success">
                            Active
                        </span>';
                }

                return '<span class="badge bg-secondary">
                        Inactive
                    </span>';
            })


            ->addColumn('action', function ($category) {

                $editUrl = route(
                    'admin.categories.edit',
                    $category->id
                );

                $deleteUrl = route(
                    'admin.categories.destroy',
                    $category->id
                );


                return '
                <div class="d-flex gap-1">

                    <a href="' . $editUrl . '"
                       class="btn btn-sm btn-primary">

                        Edit

                    </a>


                    <form action="' . $deleteUrl . '"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm(\'Are you sure you want to delete this category?\');">

                        ' . csrf_field() . '

                        ' . method_field('DELETE') . '

                        <button type="submit"
                                class="btn btn-sm btn-outline-danger">

                            Delete

                        </button>

                    </form>

                </div>
            ';
            })


            ->rawColumns([
                'parent',
                'status_badge',
                'action',
            ])


            ->make(true);
    }

    /**
     * Create Category
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.categories.create',
            compact('parentCategories')
        );
    }
    /**
     * Store Category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'parent_id' => [
                'nullable',
                'exists:categories,id',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:categories,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'seo_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'meta_keywords' => [
                'nullable',
                'string',
                'max:500',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);

        $validated['parent_id'] = $request->input('parent_id') ?: null;
        $validated['slug'] = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);


        $validated['status'] = $request->boolean('status');


        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;


        Category::create($validated);


        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }


    /**
     * Edit Category
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('status', true)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.categories.edit',
            compact(
                'category',
                'parentCategories'
            )
        );
    }
    /**
     * Update Category
     */
    public function update(
        Request $request,
        Category $category
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'parent_id' => [
                'nullable',
                'exists:categories,id',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:categories,slug,' . $category->id,
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'seo_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'meta_keywords' => [
                'nullable',
                'string',
                'max:500',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);

        $validated['parent_id'] = $request->input('parent_id') ?: null;
        $validated['slug'] = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);


        $validated['status'] = $request->boolean('status');


        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;


        $category->update($validated);


        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }


    /**
     * Delete Category
     */
    public function destroy(Category $category)
    {
        $category->delete();


        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }
}
