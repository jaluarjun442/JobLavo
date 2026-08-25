<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_posts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('source_id')
                ->constrained('sources')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('source_post_id');

            $table->string('title');

            $table->text('source_url');

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Source Posts
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'source_id',
                'source_post_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_posts');
    }
};
