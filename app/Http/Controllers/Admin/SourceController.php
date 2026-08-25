<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Sources Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $sources = Source::latest()->get();

        return view(
            'admin.sources.index',
            compact('sources')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Source
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:sources,name',
            ],

            'feed_url' => [
                'required',
                'url',
                'max:2000',
            ],

            'latest_limit' => [
                'required',
                'integer',
                'min:1',
                'max:50',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

        ]);


        $validated['status'] =
            $request->boolean('status');


        Source::create(
            $validated
        );


        return redirect()
            ->route('admin.sources.index')
            ->with(
                'success',
                'Source added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Source
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Source $source
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:sources,name,' . $source->id,
            ],

            'feed_url' => [
                'required',
                'url',
                'max:2000',
            ],

            'latest_limit' => [
                'required',
                'integer',
                'min:1',
                'max:50',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

        ]);


        $validated['status'] =
            $request->boolean('status');


        $source->update(
            $validated
        );


        return redirect()
            ->route('admin.sources.index')
            ->with(
                'success',
                'Source updated successfully.'
            );
    }
    /*
|--------------------------------------------------------------------------
| Fetch Source Now
|--------------------------------------------------------------------------
*/

    public function fetchNow(
        Source $source
    ) {
        try {

            $count = app(
                \App\Services\SourcePostFetcher::class
            )->fetch($source);


            return redirect()
                ->route('admin.sources.index')
                ->with(
                    'success',
                    $count .
                        ' new post(s) fetched from ' .
                        $source->name .
                        '.'
                );
        } catch (\Throwable $e) {

            report($e);


            return redirect()
                ->route('admin.sources.index')
                ->withErrors([
                    'source' =>
                    'Failed to fetch posts from ' .
                        $source->name .
                        '.'
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Source
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Source $source
    ) {

        $source->delete();


        return redirect()
            ->route('admin.sources.index')
            ->with(
                'success',
                'Source deleted successfully.'
            );
    }
    /*
|--------------------------------------------------------------------------
| Fetch All Active Sources
|--------------------------------------------------------------------------
*/

    public function fetchAll()
    {
        dd('exit');
        exit();
        $sources = Source::query()
            ->where('status', true)
            ->get();

        $results = [];

        foreach ($sources as $source) {

            try {

                $count = app(
                    \App\Services\SourcePostFetcher::class
                )->fetch($source);

                $results[] = [
                    'source_id' => $source->id,
                    'source' => $source->name,
                    'new_posts' => $count,
                    'success' => true,
                ];
            } catch (\Throwable $e) {

                report($e);

                $results[] = [
                    'source_id' => $source->id,
                    'source' => $source->name,
                    'new_posts' => 0,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }


        /*
    |--------------------------------------------------------------------------
    | Single Log Entry For Entire Cron Run
    |--------------------------------------------------------------------------
    */

        \Log::info(
            'Source fetch cron completed',
            [
                'total_sources' => $sources->count(),
                'sources' => $results,
            ]
        );


        return response()->json([
            'success' => true,
            'sources' => $results,
        ]);
    }
}
