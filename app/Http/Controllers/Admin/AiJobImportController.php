<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AiJobImportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Allowed AI Job Fields
    |--------------------------------------------------------------------------
    */

    private function allowedFields(): array
    {
        return [
            'title',
            'category',
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
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | AI Import Page
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $jobs = session(
            'ai_import_jobs',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Categories For Select2
        |--------------------------------------------------------------------------
        */

        $categories = Category::query()

            ->where('status', true)

            ->orderBy('sort_order')

            ->orderBy('name')

            ->get([
                'id',
                'parent_id',
                'name',
            ]);


        return view(
            'admin.ai-jobs.import',
            compact(
                'jobs',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Import JSON
    |--------------------------------------------------------------------------
    */

    public function preview(Request $request)
    {
        $request->validate([
            'json_data' => 'required|string',
        ]);


        $data = json_decode(
            $request->json_data,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | JSON Validation
        |--------------------------------------------------------------------------
        */

        if (
            json_last_error() !== JSON_ERROR_NONE
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_data' =>
                        'Invalid JSON format: ' .
                        json_last_error_msg(),
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Get Jobs Array
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['jobs']) &&
            is_array($data['jobs'])
        ) {

            $jobs = $data['jobs'];

        } else {

            /*
            |--------------------------------------------------------------------------
            | Single Job JSON
            |--------------------------------------------------------------------------
            */

            $jobs = [
                $data
            ];
        }


        if (
            count($jobs) === 0
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_data' =>
                        'No jobs found in JSON.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prepare Jobs
        |--------------------------------------------------------------------------
        */

        $allowedFields =
            $this->allowedFields();


        $preparedJobs = [];


        foreach (
            $jobs as $jobData
        ) {

            if (
                !is_array($jobData)
            ) {
                continue;
            }


            $job = [];


            foreach (
                $allowedFields as $field
            ) {

                $job[$field] =
                    $jobData[$field] ?? '';
            }


            /*
            |--------------------------------------------------------------------------
            | Skip Empty Job
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    trim(
                        $job['title']
                    )
                )
            ) {
                continue;
            }


            $preparedJobs[] =
                $job;
        }


        if (
            count($preparedJobs) === 0
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_data' =>
                        'No valid jobs found in JSON.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Queue
        |--------------------------------------------------------------------------
        */

        $existingJobs =
            session(
                'ai_import_jobs',
                []
            );


        /*
        |--------------------------------------------------------------------------
        | Add New Jobs To Queue
        |--------------------------------------------------------------------------
        */

        $existingJobs =
            array_merge(
                $existingJobs,
                $preparedJobs
            );


        /*
        |--------------------------------------------------------------------------
        | Store Queue
        |--------------------------------------------------------------------------
        */

        session([
            'ai_import_jobs' =>
                array_values(
                    $existingJobs
                ),
        ]);


        return redirect()
            ->route(
                'admin.ai-jobs.import'
            )
            ->with(
                'success',
                count($preparedJobs) .
                ' job(s) imported successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Add One Job Directly
    |--------------------------------------------------------------------------
    */

    public function add(Request $request)
    {
        $validated =
            $request->validate([

                'job_index' => [
                    'required',
                    'integer',
                    'min:0',
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


        $jobs =
            session(
                'ai_import_jobs',
                []
            );


        $index =
            (int) $validated['job_index'];


        /*
        |--------------------------------------------------------------------------
        | Check Queue Job
        |--------------------------------------------------------------------------
        */

        if (
            !isset($jobs[$index])
        ) {

            return redirect()
                ->route(
                    'admin.ai-jobs.import'
                )
                ->withErrors([
                    'job' =>
                        'Selected job was not found in the import queue.',
                ]);
        }


        $job =
            $jobs[$index];


        /*
        |--------------------------------------------------------------------------
        | Save Post
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $job,
                $validated
            ) {

                $post =
                    $this->createPost(
                        $job
                    );


                /*
                |--------------------------------------------------------------------------
                | Save Multiple Categories
                |--------------------------------------------------------------------------
                */

                $post->categories()->sync(
                    $validated['category_ids']
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Remove From Queue
        |--------------------------------------------------------------------------
        */

        unset(
            $jobs[$index]
        );


        session([
            'ai_import_jobs' =>
                array_values($jobs),
        ]);


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
    | Add Multiple Selected Jobs
    |--------------------------------------------------------------------------
    */

    public function bulkAdd(Request $request)
    {
        $validated =
            $request->validate([

                'job_indices' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'job_indices.*' => [
                    'integer',
                    'min:0',
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


        $jobs =
            session(
                'ai_import_jobs',
                []
            );


        $indices =
            collect(
                $validated['job_indices']
            )
                ->map(
                    fn ($index) =>
                        (int) $index
                )
                ->unique()
                ->sort()
                ->values();


        /*
        |--------------------------------------------------------------------------
        | Validate All Selected Jobs First
        |--------------------------------------------------------------------------
        */

        foreach (
            $indices as $index
        ) {

            if (
                !isset($jobs[$index])
            ) {

                return redirect()
                    ->route(
                        'admin.ai-jobs.import'
                    )
                    ->withErrors([
                        'job' =>
                            'One or more selected jobs are no longer available in the queue.',
                    ]);
            }
        }


        $addedCount = 0;


        /*
        |--------------------------------------------------------------------------
        | Create All Posts
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $indices,
                $jobs,
                $validated,
                &$addedCount
            ) {

                foreach (
                    $indices as $index
                ) {

                    $job =
                        $jobs[$index];


                    $post =
                        $this->createPost(
                            $job
                        );


                    $post->categories()->sync(
                        $validated['category_ids']
                    );


                    $addedCount++;
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Remove Added Jobs
        |--------------------------------------------------------------------------
        |
        | Remove from highest index to lowest index so the original
        | queue indexes do not shift before all selected jobs are removed.
        |
        */

        foreach (
            $indices
                ->sortDesc()
                as $index
        ) {

            unset(
                $jobs[$index]
            );
        }


        session([
            'ai_import_jobs' =>
                array_values($jobs),
        ]);


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
    | Create Post
    |--------------------------------------------------------------------------
    */

    private function createPost(
        array $job
    ): Post {

        $title =
            trim(
                $job['title'] ?? ''
            );


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
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
        | Create Post
        |--------------------------------------------------------------------------
        |
        | Direct publishing:
        |
        | status       = published
        | published_at = now()
        |
        */

        return Post::create([

            /*
            |--------------------------------------------------------------------------
            | Legacy Category
            |--------------------------------------------------------------------------
            |
            | category_id is nullable now.
            | We intentionally leave it NULL because categories are now
            | stored through the category_post pivot table.
            |
            */

            'category_id' =>
                null,


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
                null,


            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

            'status' =>
                'published',

            'published_at' =>
                now(),

            'is_featured' =>
                false,

            'is_important' =>
                false,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Remove One Job From Queue
    |--------------------------------------------------------------------------
    */

    public function remove($index)
    {
        $jobs =
            session(
                'ai_import_jobs',
                []
            );


        if (
            isset($jobs[$index])
        ) {

            unset(
                $jobs[$index]
            );


            session([
                'ai_import_jobs' =>
                    array_values($jobs),
            ]);
        }


        return redirect()
            ->route(
                'admin.ai-jobs.import'
            )
            ->with(
                'success',
                'Job removed from import queue.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Clear Entire Queue
    |--------------------------------------------------------------------------
    */

    public function clear()
    {
        session()->forget(
            'ai_import_jobs'
        );


        return redirect()
            ->route(
                'admin.ai-jobs.import'
            )
            ->with(
                'success',
                'Import queue cleared.'
            );
    }
}