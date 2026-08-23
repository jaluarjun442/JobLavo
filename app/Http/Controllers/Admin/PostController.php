<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SitemapService;

class PostController extends Controller
{
    public function __construct(
        protected SitemapService $sitemapService
    ) {}
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
        $query = Post::query()
            ->with('categories')
            ->select('posts.*');


        return DataTables::eloquent($query)


            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            ->addIndexColumn()



            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            ->addColumn('category', function ($post) {

                if ($post->categories && $post->categories->count()) {

                    $html = '';

                    foreach ($post->categories as $category) {

                        $html .= '<span class="badge bg-light text-dark border me-1 mb-1">'
                            . e($category->name) .
                            '</span>';
                    }

                    return $html;
                }


                return '<span class="text-muted">
                        —
                    </span>';
            })



            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            ->addColumn('image', function ($post) {

                /*
                |--------------------------------------------------------------------------
                | Image is currently optional
                |--------------------------------------------------------------------------
                */

                if (
                    empty($post->featured_image)
                ) {

                    return '<span class="text-muted">
                            No Image
                        </span>';
                }


                $imageUrl = asset(
                    'storage/' . $post->featured_image
                );


                return '
                <img
                    src="' . e($imageUrl) . '"
                    alt="' . e($post->title) . '"
                    width="60"
                    height="45"
                    style="
                        object-fit:cover;
                        border-radius:4px;
                    "
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

                    return '<span class="badge bg-success">
                            Published
                        </span>';
                }


                return '<span class="badge bg-secondary">
                        Draft
                    </span>';
            })



            /*
            |--------------------------------------------------------------------------
            | Published Date
            |--------------------------------------------------------------------------
            */

            ->addColumn('published_date', function ($post) {

                if ($post->published_at) {

                    return $post->published_at
                        ->format('d M Y H:i');
                }


                return '<span class="text-muted">
                        —
                    </span>';
            })



            /*
            |--------------------------------------------------------------------------
            | Featured
            |--------------------------------------------------------------------------
            */

            ->addColumn('featured_badge', function ($post) {

                if ($post->is_featured) {

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
            | Important
            |--------------------------------------------------------------------------
            */

            ->addColumn('important_badge', function ($post) {

                if ($post->is_important) {

                    return '<span class="badge bg-danger">
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

            ->addColumn('action', function ($post) {

                $editUrl = route(
                    'admin.posts.edit',
                    $post->id
                );


                $deleteUrl = route(
                    'admin.posts.destroy',
                    $post->id
                );


                return '
                <div class="d-flex gap-1">

                    <a
                        href="' . $editUrl . '"
                        class="btn btn-sm btn-primary">

                        Edit

                    </a>


                    <form
                        action="' . $deleteUrl . '"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm(\'Are you sure you want to delete this post?\');">

                        ' . csrf_field() . '

                        ' . method_field('DELETE') . '

                        <button
                            type="submit"
                            class="btn btn-sm btn-outline-danger">

                            Delete

                        </button>

                    </form>

                </div>
            ';
            })



            /*
            |--------------------------------------------------------------------------
            | Raw HTML Columns
            |--------------------------------------------------------------------------
            */

            ->rawColumns([
                'image',
                'category',
                'status_badge',
                'published_date',
                'featured_badge',
                'important_badge',
                'action',
            ])


            ->make(true);
    }


    /**
     * Create Post Page
     */
    public function create()
    {
        $categories = Category::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $post = new Post();


        /*
        |--------------------------------------------------------------------------
        | AI Import Queue
        |--------------------------------------------------------------------------
        */

        $aiJob = null;

        if (request()->has('ai_queue')) {

            $index = (int) request('ai_queue');

            $jobs = session(
                'ai_import_jobs',
                []
            );


            if (isset($jobs[$index])) {

                $aiJob = $jobs[$index];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Old Single AI Import Support
        |--------------------------------------------------------------------------
        */

        if (!$aiJob) {

            $aiJob = session(
                'ai_import_job'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AI Imported Job
        |--------------------------------------------------------------------------
        */

        if (is_array($aiJob)) {

            foreach ($aiJob as $field => $value) {

                /*
                |--------------------------------------------------------------------------
                | Category is handled separately
                |--------------------------------------------------------------------------
                */

                if ($field === 'category') {
                    continue;
                }


                if (in_array($field, [

                    'title',
                    'excerpt',
                    'short_description',
                    'content',
                    'important_dates',
                    'application_fee',
                    'age_limit',
                    'vacancy_details',
                    'eligibility',
                    'selection_process',
                    'salary_details',
                    'how_to_apply',
                    'important_links',
                    'official_website',
                    'seo_title',
                    'meta_description',
                    'meta_keywords',

                ])) {

                    $post->{$field} = $value;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Generate Slug
            |--------------------------------------------------------------------------
            */

            if (!empty($post->title)) {

                $post->slug =
                    \Illuminate\Support\Str::slug(
                        $post->title
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Default Publishing Settings
            |--------------------------------------------------------------------------
            */

            $post->status = 'published';

            $post->published_at = now();


            /*
            |--------------------------------------------------------------------------
            | Match AI Category
            |--------------------------------------------------------------------------
            |
            | AI currently provides one category name.
            | We convert it into category_ids so the
            | multiple-category form can preselect it.
            |
            */

            $post->category_ids = [];


            if (!empty($aiJob['category'])) {

                $category = Category::where(
                    'status',
                    true
                )
                    ->whereRaw(
                        'LOWER(name) = ?',
                        [
                            strtolower(
                                trim(
                                    $aiJob['category']
                                )
                            )
                        ]
                    )
                    ->first();


                if ($category) {

                    $post->category_ids = [
                        $category->id
                    ];
                }
            }
        }


        return view(
            'admin.posts.create',
            compact(
                'categories',
                'post'
            )
        );
    }


    /**
     * Store Post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            'category_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'category_ids.*' => [
                'integer',
                'exists:categories,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Job Details
            |--------------------------------------------------------------------------
            */

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
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

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
            ? \Illuminate\Support\Str::slug(
                $validated['slug']
            )
            : \Illuminate\Support\Str::slug(
                $validated['title']
            );


        /*
        |--------------------------------------------------------------------------
        | Canonical URL
        |--------------------------------------------------------------------------
        */

        $validated['canonical_url'] =
            $validated['canonical_url']
            ?? url(
                '/post/' . $validated['slug']
            );


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
            $request->boolean(
                'is_featured'
            );

        $validated['is_important'] =
            $request->boolean(
                'is_important'
            );


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            $image = $request->file(
                'featured_image'
            );


            $filename =
                time() . '_' .
                uniqid() . '.' .
                $image->getClientOriginalExtension();


            $image->move(
                public_path(
                    'uploads/posts'
                ),
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

        $categoryIds =
            $validated['category_ids'];


        unset(
            $validated['category_ids']
        );


        $post = Post::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Sync Categories
        |--------------------------------------------------------------------------
        */

        $post->categories()->sync(
            $categoryIds
        );


        /*
        |--------------------------------------------------------------------------
        | AI Import Queue
        |--------------------------------------------------------------------------
        */

        if ($request->has('ai_queue')) {

            $index = (int) $request->input(
                'ai_queue'
            );


            $jobs = session(
                'ai_import_jobs',
                []
            );


            if (isset($jobs[$index])) {

                unset(
                    $jobs[$index]
                );


                session([
                    'ai_import_jobs' =>
                    array_values($jobs),
                ]);
            }


            session()->forget(
                'ai_import_job'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sitemap Sync
        |--------------------------------------------------------------------------
        */

        $this->sitemapService->sync();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        if ($request->has('ai_queue')) {

            return redirect()
                ->route(
                    'admin.ai-jobs.import'
                )
                ->with(
                    'success',
                    'Post added successfully. Next AI job is ready.'
                );
        }


        return redirect()
            ->route(
                'admin.posts.index'
            )
            ->with(
                'success',
                'Post created successfully.'
            );
    }


    /**
     * Edit Post
     */
    public function edit(Post $post)
    {
        $categories = Category::where(
            'status',
            true
        )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Load Categories
        |--------------------------------------------------------------------------
        */

        $post->load('categories');


        return view(
            'admin.posts.edit',
            compact(
                'post',
                'categories'
            )
        );
    }


    /**
     * Update Post
     */
    public function update(
        Request $request,
        Post $post
    ) {

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            'category_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'category_ids.*' => [
                'integer',
                'exists:categories,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Job Details
            |--------------------------------------------------------------------------
            */

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
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

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
            ? \Illuminate\Support\Str::slug(
                $validated['slug']
            )
            : \Illuminate\Support\Str::slug(
                $validated['title']
            );


        /*
        |--------------------------------------------------------------------------
        | Canonical URL
        |--------------------------------------------------------------------------
        */

        if (empty($validated['canonical_url'])) {

            $validated['canonical_url'] =
                url(
                    '/post/' . $validated['slug']
                );
        }


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
            $request->boolean(
                'is_featured'
            );

        $validated['is_important'] =
            $request->boolean(
                'is_important'
            );


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            $image = $request->file(
                'featured_image'
            );


            $filename =
                time() . '_' .
                uniqid() . '.' .
                $image->getClientOriginalExtension();


            $image->move(
                public_path(
                    'uploads/posts'
                ),
                $filename
            );


            $validated['featured_image'] =
                'uploads/posts/' . $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Post
        |--------------------------------------------------------------------------
        */

        $categoryIds =
            $validated['category_ids'];


        unset(
            $validated['category_ids']
        );


        $post->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Sync Categories
        |--------------------------------------------------------------------------
        */

        $post->categories()->sync(
            $categoryIds
        );


        /*
        |--------------------------------------------------------------------------
        | Sitemap Sync
        |--------------------------------------------------------------------------
        */

        $this->sitemapService->sync();


        return redirect()
            ->route(
                'admin.posts.index'
            )
            ->with(
                'success',
                'Post updated successfully.'
            );
    }


    /**
     * Delete Post
     */
    public function destroy(Post $post)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Featured Image
        |--------------------------------------------------------------------------
        */

        if (
            $post->featured_image &&
            file_exists(
                public_path(
                    $post->featured_image
                )
            )
        ) {

            unlink(
                public_path(
                    $post->featured_image
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Category Relations
        |--------------------------------------------------------------------------
        */

        $post->categories()->detach();


        /*
        |--------------------------------------------------------------------------
        | Delete Post
        |--------------------------------------------------------------------------
        */

        $post->delete();


        /*
        |--------------------------------------------------------------------------
        | Sitemap Sync
        |--------------------------------------------------------------------------
        */

        $this->sitemapService->sync();


        return redirect()
            ->route(
                'admin.posts.index'
            )
            ->with(
                'success',
                'Post deleted successfully.'
            );
    }


}
