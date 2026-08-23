<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateExistingPostCategoriesToPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('posts')
            ->whereNotNull('category_id')
            ->orderBy('id')
            ->eachById(function ($post) {

                DB::table('category_post')
                    ->insertOrIgnore([
                        'post_id' => $post->id,
                        'category_id' => $post->category_id,
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pivot', function (Blueprint $table) {
            //
        });
    }
}
