<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiJobImportController extends Controller
{
    public function create()
    {
        return view('admin.ai-jobs.import');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'json_data' => 'required|string',
        ]);

        $data = json_decode($request->json_data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()
                ->withInput()
                ->withErrors([
                    'json_data' => 'Invalid JSON format: ' . json_last_error_msg(),
                ]);
        }

        if (isset($data['jobs']) && is_array($data['jobs'])) {

            if (count($data['jobs']) === 0) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'json_data' => 'No jobs found in JSON.',
                    ]);
            }

            $data = $data['jobs'][0];
        }

        if (!is_array($data)) {
            return back()
                ->withInput()
                ->withErrors([
                    'json_data' => 'Invalid job data.',
                ]);
        }

        $allowedFields = [
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

        $job = [];

        foreach ($allowedFields as $field) {
            $job[$field] = $data[$field] ?? '';
        }

        session([
            'ai_import_job' => $job
        ]);

        return redirect()->route('admin.posts.create');
    }
}
