<?php

namespace App\Services;

use App\Models\Source;
use App\Models\SourcePost;
use Illuminate\Support\Facades\Http;

class SourcePostFetcher
{
    /*
    |--------------------------------------------------------------------------
    | Fetch New Posts
    |--------------------------------------------------------------------------
    */

    public function fetch(Source $source): int
    {
        /*
        |--------------------------------------------------------------------------
        | Last Imported Published Date
        |--------------------------------------------------------------------------
        */
        /*
|--------------------------------------------------------------------------
| Check Existing Source Posts
|--------------------------------------------------------------------------
*/

        $hasExistingPosts = SourcePost::query()
            ->where('source_id', $source->id)
            ->exists();


        /*
|--------------------------------------------------------------------------
| First Fetch
|--------------------------------------------------------------------------
*/

        if (!$hasExistingPosts) {

            return $this->createInitialBaseline($source);
        }


        /*
|--------------------------------------------------------------------------
| Existing Source
|--------------------------------------------------------------------------
*/

        $lastPublishedAt = SourcePost::query()
            ->where('source_id', $source->id)
            ->max('published_at');


        $page = 1;

        $newPosts = 0;


        while (true) {

            /*
            |--------------------------------------------------------------------------
            | WordPress API
            |--------------------------------------------------------------------------
            */

            $response = Http::timeout(30)
                ->get($source->feed_url, [

                    'page'     => $page,

                    'per_page' => 100,

                    'orderby'  => 'date',

                    'order'    => 'desc',

                ]);


            /*
            |--------------------------------------------------------------------------
            | API Error
            |--------------------------------------------------------------------------
            */

            if (!$response->successful()) {

                /*
                |--------------------------------------------------------------------------
                | WordPress 400 usually means no more pages
                |--------------------------------------------------------------------------
                */

                if ($response->status() === 400) {
                    break;
                }


                throw new \RuntimeException(
                    'Source API request failed. HTTP status: '
                        . $response->status()
                );
            }


            $posts = $response->json();


            if (
                !is_array($posts) ||
                empty($posts)
            ) {
                break;
            }


            $reachedOldPosts = false;


            foreach ($posts as $post) {


                /*
                |--------------------------------------------------------------------------
                | Source Post ID
                |--------------------------------------------------------------------------
                */

                if (!isset($post['id'])) {
                    continue;
                }


                $sourcePostId =
                    (int) $post['id'];


                /*
                |--------------------------------------------------------------------------
                | Published Date
                |
                | Prefer WordPress date_gmt.
                | Do NOT use modified date.
                |--------------------------------------------------------------------------
                */

                $publishedAt =
                    $post['date_gmt']
                    ?? $post['date']
                    ?? null;


                if (!$publishedAt) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Existing Historical Boundary
                |--------------------------------------------------------------------------
                */

                if (
                    $lastPublishedAt &&
                    strtotime($publishedAt) <
                    strtotime($lastPublishedAt)
                ) {

                    $reachedOldPosts = true;

                    break;
                }


                /*
                |--------------------------------------------------------------------------
                | Duplicate Check
                |
                | ID is only used for duplicate protection.
                | ID is NOT used as the stopping condition.
                |--------------------------------------------------------------------------
                */

                $exists = SourcePost::query()
                    ->where(
                        'source_id',
                        $source->id
                    )
                    ->where(
                        'source_post_id',
                        $sourcePostId
                    )
                    ->exists();


                if ($exists) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Title
                |--------------------------------------------------------------------------
                */

                $title =
                    data_get(
                        $post,
                        'title.rendered'
                    );


                /*
                |--------------------------------------------------------------------------
                | Source URL
                |--------------------------------------------------------------------------
                */

                $sourceUrl =
                    data_get(
                        $post,
                        'link'
                    );


                /*
                |--------------------------------------------------------------------------
                | Save
                |--------------------------------------------------------------------------
                */

                SourcePost::create([

                    'source_id' =>
                    $source->id,

                    'source_post_id' =>
                    $sourcePostId,

                    'title' =>
                    $title ?: 'Untitled',

                    'source_url' =>
                    $sourceUrl ?: '',

                    'published_at' =>
                    $publishedAt,

                ]);


                $newPosts++;
            }


            /*
            |--------------------------------------------------------------------------
            | Reached Previously Imported Date
            |--------------------------------------------------------------------------
            */

            if ($reachedOldPosts) {
                break;
            }


            /*
            |--------------------------------------------------------------------------
            | Next Page
            |--------------------------------------------------------------------------
            */

            $page++;
        }


        return $newPosts;
    }
    /*
|--------------------------------------------------------------------------
| Create Initial Baseline
|--------------------------------------------------------------------------
*/

    private function createInitialBaseline(Source $source): int
    {
        $response = Http::timeout(30)
            ->get($source->feed_url, [

                'page'     => 1,

                'per_page' => $source->latest_limit,

                'orderby'  => 'date',

                'order'    => 'desc',

            ]);


        if (!$response->successful()) {

            throw new \RuntimeException(
                'Source API request failed. HTTP status: '
                    . $response->status()
            );
        }


        $posts = $response->json();


        if (
            !is_array($posts) ||
            empty($posts)
        ) {
            return 0;
        }


        $newPosts = 0;


        foreach ($posts as $post) {

            if (!isset($post['id'])) {
                continue;
            }


            $sourcePostId =
                (int) $post['id'];


            $publishedAt =
                $post['date_gmt']
                ?? $post['date']
                ?? null;


            if (!$publishedAt) {
                continue;
            }


            $title =
                data_get(
                    $post,
                    'title.rendered'
                );


            $sourceUrl =
                data_get(
                    $post,
                    'link'
                );


            SourcePost::firstOrCreate(

                [
                    'source_id' =>
                    $source->id,

                    'source_post_id' =>
                    $sourcePostId,
                ],

                [
                    'title' =>
                    $title ?: 'Untitled',

                    'source_url' =>
                    $sourceUrl ?: '',

                    'published_at' =>
                    $publishedAt,
                ]

            );


            $newPosts++;
        }


        return $newPosts;
    }
}
