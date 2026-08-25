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
        return $this->sendRequest(
            $url,
            'URL_UPDATED',
            'update'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submit URL Deleted
    |--------------------------------------------------------------------------
    */

    public function delete(string $url): bool
    {
        return $this->sendRequest(
            $url,
            'URL_DELETED',
            'delete'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Common Google Indexing Request
    |--------------------------------------------------------------------------
    */

    private function sendRequest(
        string $url,
        string $type,
        string $action
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Default Log Data
        |--------------------------------------------------------------------------
        */

        $logData = [

            'action' => $action,

            'type' => $type,

            'url' => $url,

            'success' => false,

        ];


        /*
        |--------------------------------------------------------------------------
        | Local / Non-Production Protection
        |--------------------------------------------------------------------------
        */

        if (!app()->environment('production')) {

            $logData['skipped'] = true;

            $logData['reason'] =
                'non-production environment';

            $logData['environment'] =
                app()->environment();


            Log::info(
                "Google Indexing Request\n" .
                json_encode(
                    $logData,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_SLASHES |
                    JSON_UNESCAPED_UNICODE
                )
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

                $logData['error'] =
                    'Credentials file not found.';

                $logData['credentials_path'] =
                    $credentialsPath;


                Log::error(
                    "Google Indexing Request\n" .
                    json_encode(
                        $logData,
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_SLASHES |
                        JSON_UNESCAPED_UNICODE
                    )
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
                        'type' => $type,
                    ],
                ]
            );


            $statusCode =
                $response->getStatusCode();


            $responseBody =
                (string) $response->getBody();


            /*
            |--------------------------------------------------------------------------
            | Response Data
            |--------------------------------------------------------------------------
            */

            $logData['http_status'] =
                $statusCode;

            $logData['response'] =
                $responseBody;


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            if (
                $statusCode >= 200 &&
                $statusCode < 300
            ) {

                $logData['success'] = true;


                Log::info(
                    "Google Indexing Request\n" .
                    json_encode(
                        $logData,
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_SLASHES |
                        JSON_UNESCAPED_UNICODE
                    )
                );


                return true;
            }


            /*
            |--------------------------------------------------------------------------
            | Failed Response
            |--------------------------------------------------------------------------
            */

            Log::error(
                "Google Indexing Request\n" .
                json_encode(
                    $logData,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_SLASHES |
                    JSON_UNESCAPED_UNICODE
                )
            );


            return false;


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Exception
            |--------------------------------------------------------------------------
            */

            $logData['error'] =
                $e->getMessage();

            $logData['file'] =
                $e->getFile();

            $logData['line'] =
                $e->getLine();


            Log::error(
                "Google Indexing Request\n" .
                json_encode(
                    $logData,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_SLASHES |
                    JSON_UNESCAPED_UNICODE
                )
            );


            return false;
        }
    }
}