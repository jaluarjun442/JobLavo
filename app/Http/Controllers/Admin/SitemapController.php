<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sitemap;
use App\Services\SitemapService;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected SitemapService $sitemapService
    ) {
    }



    /*
    |--------------------------------------------------------------------------
    | Sitemap Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->sitemapService->sync();


        $sitemaps = Sitemap::orderByRaw("
            CASE
                WHEN type = 'main' THEN 0
                ELSE 1
            END
        ")
            ->orderBy('filename')
            ->get();


        return view(
            'admin.sitemaps.index',
            compact('sitemaps')
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Edit Sitemap
    |--------------------------------------------------------------------------
    */

    public function edit(Sitemap $sitemap)
    {
        return view(
            'admin.sitemaps.edit',
            compact('sitemap')
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Update Sitemap Status
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Sitemap $sitemap
    ) {
        $validated = $request->validate([

            'status' => [
                'required',
                'in:not_submitted,submitted,processing,indexed,error',
            ],

            'submitted_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Submitted Date
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'submitted'
        ) {

            $validated['submitted_at'] =
                $validated['submitted_at']
                ?? now();

        } elseif (
            $validated['status'] === 'not_submitted'
        ) {

            $validated['submitted_at'] = null;
        }


        $sitemap->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.sitemaps.index'
            )
            ->with(
                'success',
                'Sitemap status updated successfully.'
            );
    }



    /*
    |--------------------------------------------------------------------------
    | Refresh Sitemaps
    |--------------------------------------------------------------------------
    */

    public function refresh()
    {
        $this->sitemapService->sync();


        return redirect()
            ->route(
                'admin.sitemaps.index'
            )
            ->with(
                'success',
                'Sitemaps refreshed successfully.'
            );
    }
}