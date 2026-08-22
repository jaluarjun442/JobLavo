<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sitemap;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $sitemapCount = Sitemap::count();

        $sitemapUrls = Sitemap::sum('url_count');

        $sitemapIndexed = Sitemap::where(
            'status',
            'indexed'
        )->count();

        $sitemapPending = Sitemap::whereIn(
            'status',
            [
                'not_submitted',
                'submitted',
                'processing',
            ]
        )->count();
        return view(
            'admin.dashboard',
            compact(
                'sitemapCount',
                'sitemapUrls',
                'sitemapIndexed',
                'sitemapPending'
            )
        );
    }


    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'You have been logged out successfully.'
            );
    }
}
