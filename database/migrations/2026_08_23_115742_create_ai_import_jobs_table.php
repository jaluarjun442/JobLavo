<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiImportJobsTable extends Migration
{
    public function up()
    {
        Schema::create('ai_import_jobs', function (Blueprint $table) {

            $table->id();

            $table->json('content');

            $table->string('status')
                ->default('pending');

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('ai_import_jobs');
    }
}
