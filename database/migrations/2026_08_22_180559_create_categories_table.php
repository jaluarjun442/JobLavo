<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->longText('content')->nullable();

            // SEO
            $table->string('seo_title')->nullable();

            $table->string('meta_description', 500)->nullable();

            $table->string('meta_keywords', 500)->nullable();

            $table->boolean('status')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
