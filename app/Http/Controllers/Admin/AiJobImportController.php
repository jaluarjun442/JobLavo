<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $jobs = session('ai_import_jobs', []);

        return view(
            'admin.ai-jobs.import',
            compact('jobs')
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

        if (json_last_error() !== JSON_ERROR_NONE) {

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
            | Single Job JSON
            */

            $jobs = [$data];
        }


        if (count($jobs) === 0) {

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


        foreach ($jobs as $jobData) {

            if (!is_array($jobData)) {
                continue;
            }


            $job = [];


            foreach (
                $allowedFields
                as $field
            ) {

                $job[$field] =
                    $jobData[$field] ?? '';
            }


            /*
            |--------------------------------------------------------------------------
            | Skip Completely Empty Job
            |--------------------------------------------------------------------------
            */

            if (
                empty(trim(
                    $job['title']
                ))
            ) {
                continue;
            }


            $preparedJobs[] = $job;
        }


        if (count($preparedJobs) === 0) {

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
            session('ai_import_jobs', []);


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
        | Store In Session
        |--------------------------------------------------------------------------
        */

        session([
            'ai_import_jobs' =>
                array_values($existingJobs),
        ]);


        return redirect()
            ->route('admin.ai-jobs.import')
            ->with(
                'success',
                count($preparedJobs) .
                ' job(s) imported successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Remove One Job From Queue
    |--------------------------------------------------------------------------
    */

    public function remove($index)
    {
        $jobs =
            session('ai_import_jobs', []);


        if (
            isset($jobs[$index])
        ) {

            unset($jobs[$index]);

            $jobs =
                array_values($jobs);


            session([
                'ai_import_jobs' => $jobs,
            ]);
        }


        return redirect()
            ->route('admin.ai-jobs.import')
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
            ->route('admin.ai-jobs.import')
            ->with(
                'success',
                'Import queue cleared.'
            );
    }
}