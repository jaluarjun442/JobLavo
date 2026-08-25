<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->text('feed_url');

            $table->boolean('status')
                ->default(true);

            $table->unsignedInteger('latest_limit')
                ->default(10);

            $table->timestamps();

            $table->unique([
                'name',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
