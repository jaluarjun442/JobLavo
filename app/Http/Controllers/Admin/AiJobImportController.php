<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiImportJob;
use App\Models\Category;
use Illuminate\Http\Request;

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
        $jobs = AiImportJob::query()
            ->where('status', 'pending')
            ->latest('id')
            ->get();


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

            $jobs = [$data];
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

        $importedCount = 0;


        foreach ($jobs as $jobData) {

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
                empty(trim(
                    (string) $job['title']
                ))
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Store Job In Database Queue
            |--------------------------------------------------------------------------
            */

            AiImportJob::create([
                'content' => $job,
                'status' => 'pending',
            ]);


            $importedCount++;
        }


        if (
            $importedCount === 0
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_data' =>
                    'No valid jobs found in JSON.',
                ]);
        }


        return redirect()
            ->route(
                'admin.ai-jobs.import'
            )
            ->with(
                'success',
                $importedCount .
                    ' job(s) imported successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Remove One Job
    |--------------------------------------------------------------------------
    */

    public function remove(
        AiImportJob $job
    ) {

        $job->delete();


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
        AiImportJob::query()

            ->where(
                'status',
                'pending'
            )

            ->delete();


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
