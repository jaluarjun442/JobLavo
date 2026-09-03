<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $table->unsignedSmallInteger('http_status')
                ->default(410)
                ->after('id');

        });


        // Existing posts → 410
        DB::table('posts')->update([
            'http_status' => 410,
        ]);
    }


    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $table->dropColumn('http_status');

        });
    }
};