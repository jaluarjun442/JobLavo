<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index()
    {
        $publishedPosts = Post::where(
            'status',
            'published'
        )->count();

        $sitemapCount = max(
            1,
            (int) ceil($publishedPosts / 100)
        );

        return response()
            ->view(
                'robots',
                compact('sitemapCount')
            )
            ->header(
                'Content-Type',
                'text/plain; charset=UTF-8'
            );
    }
}
