<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Log File
    |--------------------------------------------------------------------------
    */

    private function logPath(): string
    {
        return storage_path('logs/laravel.log');
    }


    /*
    |--------------------------------------------------------------------------
    | View Logs
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $path = $this->logPath();

        $exists = File::exists($path);

        $size = 0;

        $lastModified = null;

        $logs = [];


        if ($exists) {

            $size = File::size($path);

            $lastModified = File::lastModified($path);


            /*
            |--------------------------------------------------------------------------
            | Read Latest 500 KB
            |--------------------------------------------------------------------------
            */

            $maxBytes = 500 * 1024;

            $handle = fopen($path, 'rb');

            $content = '';


            if ($handle) {

                if ($size > $maxBytes) {

                    fseek(
                        $handle,
                        -$maxBytes,
                        SEEK_END
                    );
                }


                $content = stream_get_contents(
                    $handle
                );


                fclose($handle);
            }


            /*
            |--------------------------------------------------------------------------
            | Parse Log Entries
            |--------------------------------------------------------------------------
            */

            if (
                trim($content) !== ''
            ) {

                $entries = preg_split(
                    '/(?=^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\])/m',
                    $content,
                    -1,
                    PREG_SPLIT_NO_EMPTY
                );


                foreach ($entries as $entry) {

                    $entry = trim($entry);


                    if ($entry === '') {
                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Header
                    |--------------------------------------------------------------------------
                    */

                    if (
                        preg_match(
                            '/^\[(.*?)\]\s+(\S+)\.(\w+):\s*(.*)$/s',
                            $entry,
                            $matches
                        )
                    ) {

                        $logs[] = [

                            'datetime' =>
                            $matches[1],

                            'environment' =>
                            $matches[2],

                            'level' =>
                            strtoupper(
                                $matches[3]
                            ),

                            'message' =>
                            trim(
                                $matches[4]
                            ),

                            'raw' =>
                            $entry,

                        ];
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Latest First
                |--------------------------------------------------------------------------
                */

                $logs = array_reverse(
                    $logs
                );


                /*
                |--------------------------------------------------------------------------
                | Limit Display
                |--------------------------------------------------------------------------
                */

                $logs = array_slice(
                    $logs,
                    0,
                    100
                );
            }
        }


        return view(
            'admin.logs.index',
            compact(
                'logs',
                'exists',
                'size',
                'lastModified'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Download Log
    |--------------------------------------------------------------------------
    */

    public function download()
    {
        $path = $this->logPath();


        if (!File::exists($path)) {

            abort(
                404,
                'Log file not found.'
            );
        }


        return response()->download(
            $path,
            'laravel.log',
            [
                'Content-Type' =>
                'text/plain; charset=UTF-8',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Clear Log
    |--------------------------------------------------------------------------
    */

    public function clear()
    {
        $path = $this->logPath();


        if (
            File::exists($path)
        ) {

            File::put(
                $path,
                ''
            );
        }


        return redirect()
            ->route(
                'admin.logs.index'
            )
            ->with(
                'success',
                'Laravel log cleared successfully.'
            );
    }
}
