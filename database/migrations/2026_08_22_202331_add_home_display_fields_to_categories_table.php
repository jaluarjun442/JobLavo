<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {

            $table->boolean('display_home_tiles')
                ->default(false)
                ->after('status');

            $table->boolean('display_home_large')
                ->default(false)
                ->after('display_home_tiles');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {

            $table->dropColumn([
                'display_home_tiles',
                'display_home_large',
            ]);
        });
    }
};
