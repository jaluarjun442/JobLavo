<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            $table->string('title');

            $table->string('slug')->unique();


            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            $table->string('desktop_image')->nullable();

            $table->string('mobile_image')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Content
            |--------------------------------------------------------------------------
            */

            $table->longText('content')->nullable();


            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            $table->string('seo_title')->nullable();

            $table->string('meta_description', 500)->nullable();

            $table->string('meta_keywords', 500)->nullable();

            $table->string('canonical_url')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Publishing / Views
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('views_count')
                ->default(0);

            $table->dateTime('published_date')
                ->nullable();

            $table->string('published_by')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });
    }


    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
};