<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Category Listing
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.categories.index');
    }



    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */
    public function data(Request $request)
    {
        $query = Category::query()
            ->select('categories.*')
            ->with('parent')
            ->withCount('posts');


        return DataTables::eloquent($query)

            ->addIndexColumn()


            /*
        |--------------------------------------------------------------------------
        | Parent
        |--------------------------------------------------------------------------
        */

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


            /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

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


            /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

            ->addColumn('header', function ($category) {

                if ($category->display_header) {

                    return '<span class="badge bg-primary">
                            Yes
                        </span>';
                }

                return '<span class="badge bg-light text-dark border">
                        No
                    </span>';
            })


            /*
        |--------------------------------------------------------------------------
        | Home Tiles
        |--------------------------------------------------------------------------
        */

            ->addColumn('home_tiles', function ($category) {

                if ($category->display_home_tiles) {

                    return '<span class="badge bg-primary">
                            Yes
                        </span>';
                }

                return '<span class="badge bg-light text-dark border">
                        No
                    </span>';
            })


            /*
        |--------------------------------------------------------------------------
        | Home Large
        |--------------------------------------------------------------------------
        */

            ->addColumn('home_large', function ($category) {

                if ($category->display_home_large) {

                    return '<span class="badge bg-success">
                            Yes
                        </span>';
                }

                return '<span class="badge bg-light text-dark border">
                        No
                    </span>';
            })


            /*
        |--------------------------------------------------------------------------
        | Action
        |--------------------------------------------------------------------------
        */

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


            /*
        |--------------------------------------------------------------------------
        | Manual Parent Search
        |--------------------------------------------------------------------------
        */

            ->filterColumn('parent', function ($query, $keyword) {

                $query->whereHas('parent', function ($parentQuery) use ($keyword) {

                    $parentQuery->where(
                        'name',
                        'LIKE',
                        '%' . $keyword . '%'
                    );
                });
            })


            /*
        |--------------------------------------------------------------------------
        | Raw HTML Columns
        |--------------------------------------------------------------------------
        */

            ->rawColumns([
                'parent',
                'status_badge',
                'header',
                'home_tiles',
                'home_large',
                'action',
            ])


            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $parentCategories = Category::query()

            ->where('status', true)

            ->whereNull('parent_id')

            ->orderBy('sort_order')

            ->orderBy('name')

            ->get();


        return view(
            'admin.categories.create',
            compact('parentCategories')
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
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

            'display_header' => [
                'nullable',
                'boolean',
            ],

            'display_home_tiles' => [
                'nullable',
                'boolean',
            ],

            'display_home_large' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {

            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['status'] = $request->boolean(
            'status'
        );


        $validated['display_header'] = $request->boolean(
            'display_header'
        );


        $validated['display_home_tiles'] = $request->boolean(
            'display_home_tiles'
        );


        $validated['display_home_large'] = $request->boolean(
            'display_home_large'
        );


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Category::create($validated);


        return redirect()

            ->route('admin.categories.index')

            ->with(
                'success',
                'Category created successfully.'
            );
    }



    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $category = Category::findOrFail($id);


        $parentCategories = Category::query()

            ->where('status', true)

            ->whereNull('parent_id')

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



    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $category = Category::findOrFail($id);


        $validated = $request->validate([

            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
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

            'display_header' => [
                'nullable',
                'boolean',
            ],

            'display_home_tiles' => [
                'nullable',
                'boolean',
            ],

            'display_home_large' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Slug If Empty
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {

            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $category->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['status'] = $request->boolean(
            'status'
        );


        $validated['display_header'] = $request->boolean(
            'display_header'
        );


        $validated['display_home_tiles'] = $request->boolean(
            'display_home_tiles'
        );


        $validated['display_home_large'] = $request->boolean(
            'display_home_large'
        );


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $category->update($validated);


        return redirect()

            ->route('admin.categories.index')

            ->with(
                'success',
                'Category updated successfully.'
            );
    }



    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $category = Category::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Prevent Parent Delete If It Has Children
        |--------------------------------------------------------------------------
        */

        if ($category->children()->exists()) {

            return redirect()

                ->route('admin.categories.index')

                ->with(
                    'error',
                    'This category has sub-categories. Delete or move them first.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $category->delete();


        return redirect()

            ->route('admin.categories.index')

            ->with(
                'success',
                'Category deleted successfully.'
            );
    }



    /*
    |--------------------------------------------------------------------------
    | Generate Unique Slug
    |--------------------------------------------------------------------------
    */

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {

        $slug = Str::slug($name);

        $originalSlug = $slug;

        $counter = 1;


        while (

            Category::query()

            ->where(
                'slug',
                $slug
            )

            ->when(
                $ignoreId,
                function ($query) use ($ignoreId) {

                    $query->where(
                        'id',
                        '!=',
                        $ignoreId
                    );
                }
            )

            ->exists()

        ) {

            $slug =
                $originalSlug .
                '-' .
                $counter;

            $counter++;
        }


        return $slug;
    }
}
