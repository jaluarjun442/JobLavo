<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Schema::table('categories', function (Blueprint $table) {

            $table->index(
                ['parent_id', 'status'],
                'categories_parent_status_index'
            );

            $table->index(
                ['status', 'display_header'],
                'categories_status_header_index'
            );

            $table->index(
                ['status', 'display_home_tiles'],
                'categories_status_home_tiles_index'
            );

            $table->index(
                ['status', 'display_home_large'],
                'categories_status_home_large_index'
            );

            $table->index(
                ['status', 'sort_order'],
                'categories_status_sort_order_index'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Posts
        |--------------------------------------------------------------------------
        */

        Schema::table('posts', function (Blueprint $table) {

            $table->index(
                ['status', 'http_status', 'published_at'],
                'posts_status_http_published_index'
            );

            $table->index(
                ['slug'],
                'posts_slug_index'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Category Post Pivot
        |--------------------------------------------------------------------------
        */

        Schema::table('category_post', function (Blueprint $table) {

            $table->index(
                ['category_id', 'post_id'],
                'category_post_category_post_index'
            );

            $table->index(
                ['post_id', 'category_id'],
                'category_post_post_category_index'
            );
        });
    }


    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {

            $table->dropIndex(
                'categories_parent_status_index'
            );

            $table->dropIndex(
                'categories_status_header_index'
            );

            $table->dropIndex(
                'categories_status_home_tiles_index'
            );

            $table->dropIndex(
                'categories_status_home_large_index'
            );

            $table->dropIndex(
                'categories_status_sort_order_index'
            );
        });


        Schema::table('posts', function (Blueprint $table) {

            $table->dropIndex(
                'posts_status_http_published_index'
            );

            $table->dropIndex(
                'posts_slug_index'
            );
        });


        Schema::table('category_post', function (Blueprint $table) {

            $table->dropIndex(
                'category_post_category_post_index'
            );

            $table->dropIndex(
                'category_post_post_category_index'
            );
        });
    }
};