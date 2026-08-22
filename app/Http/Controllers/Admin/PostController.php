<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    /**
     * Posts Page
     */
    public function index()
    {
        return view('admin.posts.index');
    }


    /**
     * Server Side Data
     */
    public function data(Request $request)
    {
        $query = Post::with('category')
            ->select('posts.*');


        return DataTables::eloquent($query)

            ->addIndexColumn()


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            ->addColumn('image', function ($post) {

                if (!$post->featured_image) {

                    return '<span class="text-secondary">No Image</span>';
                }


                return '
                    <img
                        src="' . asset($post->featured_image) . '"
                        alt="' . e($post->title) . '"
                        width="60"
                        height="45"
                        style="object-fit:cover;border-radius:4px;"
                    >
                ';
            })


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            ->addColumn('status_badge', function ($post) {

                if ($post->status === 'published') {

                    return '
                        <span class="badge bg-success">
                            Published
                        </span>
                    ';
                }


                return '
                    <span class="badge bg-secondary">
                        Draft
                    </span>
                ';
            })


            /*
            |--------------------------------------------------------------------------
            | Published Date
            |--------------------------------------------------------------------------
            */

            ->addColumn('published_date', function ($post) {

                if (!$post->published_at) {

                    return '<span class="text-secondary">—</span>';
                }


                return $post->published_at->format('d M Y');
            })


            /*
            |--------------------------------------------------------------------------
            | Action
            |--------------------------------------------------------------------------
            */
            ->addColumn('action', function ($post) {

                $editUrl = route(
                    'admin.posts.edit',
                    $post->id
                );


                $viewUrl = url(
                    '/post/' . $post->slug
                );


                $deleteUrl = route(
                    'admin.posts.destroy',
                    $post->id
                );


                return '
        <div class="d-flex gap-1">

            <a
                href="' . $viewUrl . '"
                target="_blank"
                class="btn btn-sm btn-outline-secondary"
            >
                View
            </a>


            <a
                href="' . $editUrl . '"
                class="btn btn-sm btn-primary"
            >
                Edit
            </a>


            <form
                action="' . $deleteUrl . '"
                method="POST"
                class="d-inline"
                onsubmit="return confirm(\'Are you sure you want to delete this post?\');"
            >

                ' . csrf_field() . '

                ' . method_field('DELETE') . '

                <button
                    type="submit"
                    class="btn btn-sm btn-outline-danger"
                >
                    Delete
                </button>

            </form>

        </div>
    ';
            })


            ->rawColumns([
                'image',
                'status_badge',
                'published_date',
                'action',
            ])


            ->make(true);
    }
    public function create()
    {
        $categories = Category::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.posts.create',
            compact('categories')
        );
    }
    public function store(Request $request)
    {
        $validated = $request->validate([

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:posts,slug',
            ],

            'excerpt' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'important_dates' => [
                'nullable',
                'string',
            ],

            'application_fee' => [
                'nullable',
                'string',
            ],

            'age_limit' => [
                'nullable',
                'string',
            ],

            'vacancy_details' => [
                'nullable',
                'string',
            ],

            'eligibility' => [
                'nullable',
                'string',
            ],

            'selection_process' => [
                'nullable',
                'string',
            ],

            'salary_details' => [
                'nullable',
                'string',
            ],

            'how_to_apply' => [
                'nullable',
                'string',
            ],

            'important_links' => [
                'nullable',
                'string',
            ],

            'official_website' => [
                'nullable',
                'url',
                'max:255',
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

            'canonical_url' => [
                'nullable',
                'url',
                'max:255',
            ],

            'status' => [
                'required',
                'in:draft,published',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

        ]);


        /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    */

        $validated['slug'] = $validated['slug']
            ? \Illuminate\Support\Str::slug($validated['slug'])
            : \Illuminate\Support\Str::slug($validated['title']);


        /*
    |--------------------------------------------------------------------------
    | Publishing
    |--------------------------------------------------------------------------
    */

        if ($validated['status'] === 'published') {

            $validated['published_at'] =
                $validated['published_at']
                ?? now();
        } else {

            $validated['published_at'] = null;
        }


        /*
    |--------------------------------------------------------------------------
    | Featured / Important
    |--------------------------------------------------------------------------
    */

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $validated['is_important'] =
            $request->boolean('is_important');


        /*
    |--------------------------------------------------------------------------
    | Featured Image
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('featured_image')) {

            $image = $request->file('featured_image');

            $filename =
                time() . '_' .
                uniqid() . '.' .
                $image->getClientOriginalExtension();


            $image->move(
                public_path('uploads/posts'),
                $filename
            );


            $validated['featured_image'] =
                'uploads/posts/' . $filename;
        }


        /*
    |--------------------------------------------------------------------------
    | Create Post
    |--------------------------------------------------------------------------
    */

        Post::create($validated);


        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post created successfully.'
            );
    }
    public function edit(Post $post)
    {
        $categories = Category::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.posts.edit',
            compact(
                'post',
                'categories'
            )
        );
    }
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:posts,slug,' . $post->id,
            ],

            'excerpt' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'important_dates' => ['nullable', 'string'],
            'application_fee' => ['nullable', 'string'],
            'age_limit' => ['nullable', 'string'],
            'vacancy_details' => ['nullable', 'string'],
            'eligibility' => ['nullable', 'string'],
            'selection_process' => ['nullable', 'string'],
            'salary_details' => ['nullable', 'string'],
            'how_to_apply' => ['nullable', 'string'],
            'important_links' => ['nullable', 'string'],

            'official_website' => [
                'nullable',
                'url',
                'max:255',
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

            'canonical_url' => [
                'nullable',
                'url',
                'max:255',
            ],

            'status' => [
                'required',
                'in:draft,published',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

        ]);


        /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    */

        $validated['slug'] = $validated['slug']
            ? \Illuminate\Support\Str::slug($validated['slug'])
            : \Illuminate\Support\Str::slug($validated['title']);


        /*
    |--------------------------------------------------------------------------
    | Publishing
    |--------------------------------------------------------------------------
    */

        if ($validated['status'] === 'published') {

            $validated['published_at'] =
                $validated['published_at']
                ?? $post->published_at
                ?? now();
        } else {

            $validated['published_at'] = null;
        }


        /*
    |--------------------------------------------------------------------------
    | Featured / Important
    |--------------------------------------------------------------------------
    */

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $validated['is_important'] =
            $request->boolean('is_important');


        /*
    |--------------------------------------------------------------------------
    | Image
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('featured_image')) {

            $image = $request->file('featured_image');

            $filename =
                time() . '_' .
                uniqid() . '.' .
                $image->getClientOriginalExtension();


            $image->move(
                public_path('uploads/posts'),
                $filename
            );


            $validated['featured_image'] =
                'uploads/posts/' . $filename;
        }


        /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

        $post->update($validated);


        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post updated successfully.'
            );
    }
    public function destroy(Post $post)
    {
        /*
    |--------------------------------------------------------------------------
    | Delete Featured Image
    |--------------------------------------------------------------------------
    */

        if (
            $post->featured_image &&
            file_exists(public_path($post->featured_image))
        ) {
            unlink(public_path($post->featured_image));
        }


        /*
    |--------------------------------------------------------------------------
    | Delete Post
    |--------------------------------------------------------------------------
    */

        $post->delete();


        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post deleted successfully.'
            );
    }
}
