<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiImportJob;
use App\Models\Category;
use App\Models\Post;
use App\Services\SitemapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Services\GoogleIndexingService;

class PostController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected SitemapService $sitemapService,
        protected GoogleIndexingService $googleIndexingService
    ) {}


    /*
    |--------------------------------------------------------------------------
    | Posts Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view(
            'admin.posts.index'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Server Side Data
    |--------------------------------------------------------------------------
    */

    public function data(Request $request)
    {
        $query = Post::query()
            ->with('categories')
            ->select('posts.*');


        /*
        |--------------------------------------------------------------------------
        | Indexing Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('index_status')
        ) {

            if (
                $request->index_status === 'indexed'
            ) {

                $query->where(
                    'posts.is_indexed',
                    true
                );
            } elseif (
                $request->index_status === 'not_indexed'
            ) {

                $query->where(
                    'posts.is_indexed',
                    false
                );
            }
        }

        $indexedCount = Post::query()
            ->where('is_indexed', true)
            ->count();

        $notIndexedCount = Post::query()
            ->where(function ($query) {
                $query->where('is_indexed', false)
                    ->orWhereNull('is_indexed');
            })
        ->count();
        return DataTables::eloquent($query)
            ->with([
                'indexed_count' => $indexedCount,
                'not_indexed_count' => $notIndexedCount,
            ])
            /*
            |--------------------------------------------------------------------------
            | Default Ordering
            |--------------------------------------------------------------------------
            */

            ->order(function ($query) {

                $query->orderBy(
                    'posts.id',
                    'desc'
                );
            })


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

                if (
                    $post->categories &&
                    $post->categories->count()
                ) {

                    $html = '';


                    foreach (
                        $post->categories as $category
                    ) {

                        $html .=
                            '<span class="badge bg-light text-dark border me-1 mb-1">'
                            . e($category->name) .
                            '</span>';
                    }


                    return $html;
                }


                return '<span class="text-muted">—</span>';
            })


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            ->addColumn('image', function ($post) {

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

                if (
                    $post->status === 'published'
                ) {

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

                if (
                    $post->published_at
                ) {

                    return $post
                        ->published_at
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

                if (
                    $post->is_featured
                ) {

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

                if (
                    $post->is_important
                ) {

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
            | Google Indexing
            |--------------------------------------------------------------------------
            */

            ->addColumn('indexing', function ($post) {

                if ($post->is_indexed) {

                    return '
                        <span class="badge bg-success">
                            Indexed
                        </span>
                    ';
                }

                return '
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary index-post-btn"
                        data-id="' . $post->id . '">

                        Index
                    </button>
                ';
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
            | Raw HTML
            |--------------------------------------------------------------------------
            */

            ->rawColumns([
                'image',
                'category',
                'status_badge',
                'published_date',
                'featured_badge',
                'important_badge',
                'indexing',
                'action',
            ])


            ->make(true);
    }


    /*
    |--------------------------------------------------------------------------
    | Create Post Page
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $aiJob = null;


        /*
        |--------------------------------------------------------------------------
        | Load AI Import Queue Job
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ai_queue')) {

            $aiJob = AiImportJob::query()

                ->where(
                    'id',
                    $request->integer('ai_queue')
                )

                ->where(
                    'status',
                    'pending'
                )

                ->first();


            if (!$aiJob) {

                return redirect()
                    ->route(
                        'admin.posts.create'
                    )
                    ->with(
                        'error',
                        'AI import job was not found or has already been processed.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::query()

            ->where(
                'status',
                true
            )

            ->with([
                'children' => function ($query) {

                    $query
                        ->where(
                            'status',
                            true
                        )
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }
            ])

            ->whereNull(
                'parent_id'
            )

            ->orderBy('sort_order')

            ->orderBy('name')

            ->get();


        return view(
            'admin.posts.create',
            compact(
                'categories',
                'aiJob'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Post
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated =
            $this->validatePost(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] =
            $validated['slug']
            ? Str::slug(
                $validated['slug']
            )
            : Str::slug(
                $validated['title']
            );


        /*
        |--------------------------------------------------------------------------
        | Canonical URL
        |--------------------------------------------------------------------------
        */

        if (
            empty($validated['canonical_url'])
        ) {

            $validated['canonical_url'] =
                url(
                    '/post/' .
                        $validated['slug']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Publishing
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] ===
            'published'
        ) {

            $validated['published_at'] =
                $validated['published_at']
                ?? now();
        } else {

            $validated['published_at'] =
                null;
        }


        /*
        |--------------------------------------------------------------------------
        | Google Indexing
        |--------------------------------------------------------------------------
        |
        | New posts wait for the public indexing cron.
        |
        */

        $validated['is_indexed'] =
            false;


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

        if (
            $request->hasFile(
                'featured_image'
            )
        ) {

            $image =
                $request->file(
                    'featured_image'
                );


            $filename =
                time() .
                '_' .
                uniqid() .
                '.' .
                $image
                ->getClientOriginalExtension();


            $image->move(
                public_path(
                    'uploads/posts'
                ),
                $filename
            );


            $validated['featured_image'] =
                'uploads/posts/' .
                $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categoryIds =
            $validated['category_ids'];


        unset(
            $validated['category_ids']
        );


        /*
        |--------------------------------------------------------------------------
        | Legacy category_id
        |--------------------------------------------------------------------------
        */

        $validated['category_id'] =
            $categoryIds[0] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Create Post
        |--------------------------------------------------------------------------
        */

        $post =
            Post::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Multiple Categories
        |--------------------------------------------------------------------------
        */

        $post->categories()->sync(
            $categoryIds
        );


        /*
        |--------------------------------------------------------------------------
        | AI Queue Cleanup
        |--------------------------------------------------------------------------
        */

        $this->removeAiQueueJob(
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Sitemap
        |--------------------------------------------------------------------------
        */

        $this->sitemapService->sync();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'ai_queue'
            )
        ) {

            return redirect()
                ->route(
                    'admin.ai-jobs.import'
                )
                ->with(
                    'success',
                    'Post added successfully.'
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


    /*
    |--------------------------------------------------------------------------
    | Direct AI Quick Add - Single
    |--------------------------------------------------------------------------
    */

    public function add(Request $request)
    {
        $validated =
            $request->validate([

                'job_id' => [
                    'required',
                    'integer',
                    'exists:ai_import_jobs,id',
                ],

                'category_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'category_ids.*' => [
                    'integer',
                    'exists:categories,id',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | Get Queue Job
        |--------------------------------------------------------------------------
        */

        $job =
            AiImportJob::query()

            ->where(
                'id',
                $validated['job_id']
            )

            ->where(
                'status',
                'pending'
            )

            ->first();


        if (!$job) {

            return redirect()
                ->route(
                    'admin.ai-jobs.import'
                )
                ->withErrors([
                    'job' =>
                    'Selected job was not found in the import queue.',
                ]);
        }


        $categoryIds =
            $this->normalizeCategoryIds(
                $validated['category_ids']
            );


        if (
            empty($categoryIds)
        ) {

            return redirect()
                ->route(
                    'admin.ai-jobs.import'
                )
                ->withErrors([
                    'category_ids' =>
                    'Please select at least one category.',
                ]);
        }


        DB::transaction(
            function () use (
                $job,
                $categoryIds
            ) {

                $content =
                    $job->content;


                $post =
                    $this->createPublishedPost(
                        $content,
                        $categoryIds
                    );


                $post->categories()->sync(
                    $categoryIds
                );


                /*
                |--------------------------------------------------------------------------
                | Remove Queue Row
                |--------------------------------------------------------------------------
                */

                $job->delete();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Sitemap
        |--------------------------------------------------------------------------
        */

        $this->sitemapService->sync();


        return redirect()
            ->route(
                'admin.ai-jobs.import'
            )
            ->with(
                'success',
                'Post added and published successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Direct AI Quick Add - Bulk
    |--------------------------------------------------------------------------
    */

    public function bulkAdd(Request $request)
    {
        $validated =
            $request->validate([

                'job_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'job_ids.*' => [
                    'integer',
                    'distinct',
                    'exists:ai_import_jobs,id',
                ],

                'category_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'category_ids.*' => [
                    'integer',
                    'exists:categories,id',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | Get Queue Jobs
        |--------------------------------------------------------------------------
        */

        $jobs =
            AiImportJob::query()

            ->where(
                'status',
                'pending'
            )

            ->whereIn(
                'id',
                $validated['job_ids']
            )

            ->get();


        if (
            $jobs->isEmpty()
        ) {

            return redirect()
                ->route(
                    'admin.ai-jobs.import'
                )
                ->withErrors([
                    'jobs' =>
                    'No jobs available in the import queue.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categoryIds =
            $this->normalizeCategoryIds(
                $validated['category_ids']
            );


        if (
            empty($categoryIds)
        ) {

            return redirect()
                ->route(
                    'admin.ai-jobs.import'
                )
                ->withErrors([
                    'category_ids' =>
                    'Please select at least one category.',
                ]);
        }


        $addedCount = 0;


        /*
        |--------------------------------------------------------------------------
        | Create Posts
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $jobs,
                $categoryIds,
                &$addedCount
            ) {

                foreach (
                    $jobs as $job
                ) {

                    $content =
                        $job->content;


                    if (
                        empty(trim(
                            (string) (
                                $content['title']
                                ?? ''
                            )
                        ))
                    ) {

                        continue;
                    }


                    $post =
                        $this->createPublishedPost(
                            $content,
                            $categoryIds
                        );


                    $post->categories()->sync(
                        $categoryIds
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Remove Queue Row
                    |--------------------------------------------------------------------------
                    */

                    $job->delete();


                    $addedCount++;
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Sitemap
        |--------------------------------------------------------------------------
        */

        $this->sitemapService->sync();


        return redirect()
            ->route(
                'admin.ai-jobs.import'
            )
            ->with(
                'success',
                $addedCount .
                    ' post(s) added and published successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Published Post For AI Import
    |--------------------------------------------------------------------------
    */

    private function createPublishedPost(
        array $job,
        array $categoryIds
    ): Post {

        $title =
            trim(
                (string) (
                    $job['title']
                    ?? ''
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug =
            Str::slug(
                $title
            );


        $originalSlug =
            $slug;


        $counter = 1;


        while (
            Post::where(
                'slug',
                $slug
            )->exists()
        ) {

            $slug =
                $originalSlug .
                '-' .
                $counter;


            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Published Post
        |--------------------------------------------------------------------------
        |
        | AI Quick Add always publishes immediately.
        | Published date is always now().
        |
        */

        return Post::create([

            /*
            |--------------------------------------------------------------------------
            | Legacy Category
            |--------------------------------------------------------------------------
            */

            'category_id' =>
            $categoryIds[0] ?? null,


            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'title' =>
            $title,

            'slug' =>
            $slug,

            'excerpt' =>
            $job['excerpt'] ?? '',

            'short_description' =>
            $job['short_description'] ?? '',

            'content' =>
            $job['content'] ?? '',

            'featured_image' =>
            null,
            'is_indexed' =>
            false,

            /*
            |--------------------------------------------------------------------------
            | Job Details
            |--------------------------------------------------------------------------
            */

            'important_dates' =>
            $job['important_dates'] ?? '',

            'application_fee' =>
            $job['application_fee'] ?? '',

            'age_limit' =>
            $job['age_limit'] ?? '',

            'vacancy_details' =>
            $job['vacancy_details'] ?? '',

            'eligibility' =>
            $job['eligibility'] ?? '',

            'selection_process' =>
            $job['selection_process'] ?? '',

            'salary_details' =>
            $job['salary_details'] ?? '',

            'how_to_apply' =>
            $job['how_to_apply'] ?? '',

            'important_links' =>
            $job['important_links'] ?? '',

            'official_website' =>
            $job['official_website'] ?? '',


            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            'seo_title' =>
            $job['seo_title']
                ?: $title,

            'meta_description' =>
            $job['meta_description'] ?? '',

            'meta_keywords' =>
            $job['meta_keywords'] ?? '',

            'canonical_url' =>
            url(
                '/post/' . $slug
            ),


            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

            'status' =>
            'published',

            'published_at' =>
            now(),

            /*
            |--------------------------------------------------------------------------
            | Google Indexing
            |--------------------------------------------------------------------------
            |
            | Public cron will submit this post later.
            |
            */

            'is_indexed' =>
            false,

            'is_featured' =>
            false,

            'is_important' =>
            false,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Remove AI Queue Job
    |--------------------------------------------------------------------------
    */

    private function removeAiQueueJob(
        Request $request
    ): void {

        if (
            !$request->filled(
                'ai_queue'
            )
        ) {
            return;
        }


        AiImportJob::query()

            ->where(
                'id',
                $request->integer(
                    'ai_queue'
                )
            )

            ->where(
                'status',
                'pending'
            )

            ->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Category IDs
    |--------------------------------------------------------------------------
    */

    private function normalizeCategoryIds(
        array $categoryIds
    ): array {

        return array_values(
            array_unique(
                array_map(
                    'intval',
                    $categoryIds
                )
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Post
    |--------------------------------------------------------------------------
    */

    public function edit(Post $post)
    {
        $categories =
            Category::query()

            ->where(
                'status',
                true
            )

            ->orderBy(
                'sort_order'
            )

            ->orderBy(
                'name'
            )

            ->get();


        $post->load(
            'categories'
        );


        return view(
            'admin.posts.edit',
            compact(
                'post',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Post
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Post $post
    ) {

        $validated =
            $this->validatePost(
                $request,
                $post
            );


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] =
            $validated['slug']
            ? Str::slug(
                $validated['slug']
            )
            : Str::slug(
                $validated['title']
            );


        /*
        |--------------------------------------------------------------------------
        | Canonical
        |--------------------------------------------------------------------------
        */

        if (
            empty($validated['canonical_url'])
        ) {

            $validated['canonical_url'] =
                url(
                    '/post/' .
                        $validated['slug']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Publishing
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] ===
            'published'
        ) {

            $validated['published_at'] =
                $validated['published_at']
                ?? $post->published_at
                ?? now();
        } else {

            $validated['published_at'] =
                null;
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

        if (
            $request->hasFile(
                'featured_image'
            )
        ) {

            $image =
                $request->file(
                    'featured_image'
                );


            $filename =
                time() .
                '_' .
                uniqid() .
                '.' .
                $image
                ->getClientOriginalExtension();


            $image->move(
                public_path(
                    'uploads/posts'
                ),
                $filename
            );


            $validated['featured_image'] =
                'uploads/posts/' .
                $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categoryIds =
            $validated['category_ids'];


        unset(
            $validated['category_ids']
        );


        /*
        |--------------------------------------------------------------------------
        | Legacy category_id
        |--------------------------------------------------------------------------
        */

        $validated['category_id'] =
            $categoryIds[0] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

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
        | Sitemap
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


    /*
    |--------------------------------------------------------------------------
    | Delete Post
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Post $post
    ) {

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
        | Sitemap
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

    /*
|--------------------------------------------------------------------------
| Google Indexing
|--------------------------------------------------------------------------
*/
    /*
|--------------------------------------------------------------------------
| Google Indexing
|--------------------------------------------------------------------------
*/

    private function submitGoogleIndexing(Post $post): void
    {
        if (!app()->environment('production')) {
            return;
        }

        if ($post->status !== 'published') {
            return;
        }

        $url = route(
            'post',
            $post->slug
        );

        $this->googleIndexingService->update(
            $url
        );

        /*
    |--------------------------------------------------------------------------
    | Mark As Indexed Request Submitted
    |--------------------------------------------------------------------------
    */

        $post->update([
            'is_indexed' => true
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Common Post Validation
    |--------------------------------------------------------------------------
    */

    private function validatePost(
        Request $request,
        ?Post $post = null
    ): array {

        $slugRule = [
            'nullable',
            'string',
            'max:255',
        ];


        if ($post) {

            $slugRule[] =
                'unique:posts,slug,' .
                $post->id;
        } else {

            $slugRule[] =
                'unique:posts,slug';
        }


        return $request->validate([

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

            'slug' => $slugRule,

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
    }
    public function googleIndexingCron()
    {
        $posts = Post::query()
            ->where('status', 'published')
            ->where('is_indexed', false)
            ->orderBy('id')
            ->limit(10)
            ->get();

        $success = 0;
        $failed = 0;

        foreach ($posts as $post) {

            $result = $this->submitGoogleIndexing($post);

            if ($result) {

                $post->update([
                    'is_indexed' => true,
                ]);

                $success++;
            } else {

                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'processed' => $posts->count(),
            'indexed' => $success,
            'failed' => $failed,
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Index Single Post
    |--------------------------------------------------------------------------
    */

    public function indexPost(Post $post)
    {
        if ($post->status !== 'published') {

            return response()->json([
                'success' => false,
                'message' => 'Only published posts can be indexed.'
            ], 422);
        }

        try {

            $this->submitGoogleIndexing($post);

            $post->update([
                'is_indexed' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post submitted to Google Indexing successfully.'
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Google Indexing failed.'
            ], 500);
        }
    }
    /*
|--------------------------------------------------------------------------
| Index All Pending Posts
|--------------------------------------------------------------------------
*/

    public function indexPendingPosts()
    {
        if (!app()->environment('production')) {
            return redirect()
                ->route('admin.posts.index')
                ->withErrors([
                    'indexing' =>
                    'Google indexing is available only in production.'
                ]);
        }

        $posts = Post::query()
            ->where('status', 'published')
            ->where(function ($query) {
                $query->where('is_indexed', false)
                    ->orWhereNull('is_indexed');
            })
            ->orderBy('id')
            ->limit(50)
            ->get();

        $indexedCount = 0;

        foreach ($posts as $post) {

            try {

                $this->submitGoogleIndexing($post);

                $indexedCount++;
            } catch (\Throwable $e) {

                report($e);

                continue;
            }
        }

        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                $indexedCount .
                    ' post(s) submitted for Google indexing.'
            );
    }
}
