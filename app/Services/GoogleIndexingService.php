<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    /*
    |--------------------------------------------------------------------------
    | Submit URL Update
    |--------------------------------------------------------------------------
    */

    public function update(string $url): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Local / Non-Production Protection
        |--------------------------------------------------------------------------
        */

        if (!app()->environment('production')) {

            Log::info(
                'Google Indexing skipped - non-production environment.',
                [
                    'url' => $url,
                    'environment' => app()->environment(),
                ]
            );

            return false;
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | Credentials
            |--------------------------------------------------------------------------
            */

            $credentialsPath = storage_path(
                'app/' . env(
                    'GOOGLE_INDEXING_CREDENTIALS',
                    'google/joblavo-indexing.json'
                )
            );


            if (!file_exists($credentialsPath)) {

                Log::error(
                    'Google Indexing failed - credentials file not found.',
                    [
                        'url' => $url,
                        'credentials_path' => $credentialsPath,
                    ]
                );

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Google Client
            |--------------------------------------------------------------------------
            */

            $client = new Client();

            $client->setAuthConfig(
                $credentialsPath
            );

            $client->addScope(
                'https://www.googleapis.com/auth/indexing'
            );


            $httpClient = $client->authorize();


            /*
            |--------------------------------------------------------------------------
            | Google Indexing API
            |--------------------------------------------------------------------------
            */

            $response = $httpClient->post(
                'https://indexing.googleapis.com/v3/urlNotifications:publish',
                [
                    'json' => [
                        'url' => $url,
                        'type' => 'URL_UPDATED',
                    ],
                ]
            );


            $statusCode =
                $response->getStatusCode();


            $responseBody =
                (string) $response->getBody();


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            if (
                $statusCode >= 200 &&
                $statusCode < 300
            ) {

                Log::info(
                    'Google Indexing Request SUCCESS',
                    [
                        'url' => $url,
                        'type' => 'URL_UPDATED',
                        'http_status' => $statusCode,
                        'response' => $responseBody,
                    ]
                );

                return true;
            }


            /*
            |--------------------------------------------------------------------------
            | Unexpected Response
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Google Indexing Request FAILED',
                [
                    'url' => $url,
                    'type' => 'URL_UPDATED',
                    'http_status' => $statusCode,
                    'response' => $responseBody,
                ]
            );

            return false;
        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Exception
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Google Indexing Request EXCEPTION',
                [
                    'url' => $url,
                    'type' => 'URL_UPDATED',
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Submit URL Deleted
    |--------------------------------------------------------------------------
    */

    public function delete(string $url): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Local / Non-Production Protection
        |--------------------------------------------------------------------------
        */

        if (!app()->environment('production')) {

            Log::info(
                'Google Indexing delete skipped - non-production environment.',
                [
                    'url' => $url,
                    'environment' => app()->environment(),
                ]
            );

            return false;
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | Credentials
            |--------------------------------------------------------------------------
            */

            $credentialsPath = storage_path(
                'app/' . env(
                    'GOOGLE_INDEXING_CREDENTIALS',
                    'google/joblavo-indexing.json'
                )
            );


            if (!file_exists($credentialsPath)) {

                Log::error(
                    'Google Indexing delete failed - credentials file not found.',
                    [
                        'url' => $url,
                        'credentials_path' => $credentialsPath,
                    ]
                );

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Google Client
            |--------------------------------------------------------------------------
            */

            $client = new Client();

            $client->setAuthConfig(
                $credentialsPath
            );

            $client->addScope(
                'https://www.googleapis.com/auth/indexing'
            );


            $httpClient =
                $client->authorize();


            /*
            |--------------------------------------------------------------------------
            | Google Indexing API
            |--------------------------------------------------------------------------
            */

            $response = $httpClient->post(
                'https://indexing.googleapis.com/v3/urlNotifications:publish',
                [
                    'json' => [
                        'url' => $url,
                        'type' => 'URL_DELETED',
                    ],
                ]
            );


            $statusCode =
                $response->getStatusCode();


            $responseBody =
                (string) $response->getBody();


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            if (
                $statusCode >= 200 &&
                $statusCode < 300
            ) {

                Log::info(
                    'Google Indexing Delete SUCCESS',
                    [
                        'url' => $url,
                        'type' => 'URL_DELETED',
                        'http_status' => $statusCode,
                        'response' => $responseBody,
                    ]
                );

                return true;
            }


            /*
            |--------------------------------------------------------------------------
            | Failed
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Google Indexing Delete FAILED',
                [
                    'url' => $url,
                    'type' => 'URL_DELETED',
                    'http_status' => $statusCode,
                    'response' => $responseBody,
                ]
            );

            return false;
        } catch (\Throwable $e) {

            Log::error(
                'Google Indexing Delete EXCEPTION',
                [
                    'url' => $url,
                    'type' => 'URL_DELETED',
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return false;
        }
    }
}
