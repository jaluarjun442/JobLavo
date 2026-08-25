<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitemapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sitemaps', function (Blueprint $table) {

            $table->id();

            $table->string('filename')->unique();

            $table->string('type')->default('posts');
            // main / posts

            $table->unsignedInteger('url_count')->default(0);

            $table->enum('status', [
                'not_submitted',
                'submitted',
                'processing',
                'indexed',
                'error',
            ])->default('not_submitted');

            $table->timestamp('submitted_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sitemaps');
    }
}
