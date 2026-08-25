<?php

namespace App\Console\Commands;

use App\Models\Source;
use App\Services\SourcePostFetcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchSourcePosts extends Command
{
    /*
    |--------------------------------------------------------------------------
    | Command Signature
    |--------------------------------------------------------------------------
    */

    protected $signature = 'sources_fetch';


    /*
    |--------------------------------------------------------------------------
    | Command Description
    |--------------------------------------------------------------------------
    */

    protected $description =
        'Fetch latest posts from all active sources';


    /*
    |--------------------------------------------------------------------------
    | Handle
    |--------------------------------------------------------------------------
    */

    public function handle(
        SourcePostFetcher $fetcher
    ): int {

        $sources = Source::query()
            ->where('status', true)
            ->get();


        $results = [];


        foreach ($sources as $source) {

            try {

                $count = $fetcher->fetch(
                    $source
                );


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
        | Single Log Entry
        |--------------------------------------------------------------------------
        */

        Log::info(
            "Source fetch command completed\n" .
            json_encode(
                [
                    'total_sources' =>
                        $sources->count(),

                    'sources' =>
                        $results,
                ],
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Console Output
        |--------------------------------------------------------------------------
        */

        $this->info(
            'Source fetch completed.'
        );


        $this->line(
            'Sources: ' .
            $sources->count()
        );


        foreach ($results as $result) {

            if ($result['success']) {

                $this->line(
                    $result['source'] .
                    ' → ' .
                    $result['new_posts'] .
                    ' new post(s)'
                );

            } else {

                $this->error(
                    $result['source'] .
                    ' → FAILED'
                );
            }
        }


        return self::SUCCESS;
    }
}