<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $table->text('short_description')
                ->nullable()
                ->after('excerpt');

            $table->text('important_dates')
                ->nullable();

            $table->text('application_fee')
                ->nullable();

            $table->text('age_limit')
                ->nullable();

            $table->text('vacancy_details')
                ->nullable();

            $table->text('eligibility')
                ->nullable();

            $table->text('selection_process')
                ->nullable();

            $table->text('salary_details')
                ->nullable();

            $table->text('how_to_apply')
                ->nullable();

            $table->text('important_links')
                ->nullable();

            $table->string('official_website')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $table->dropColumn([
                'short_description',
                'important_dates',
                'application_fee',
                'age_limit',
                'vacancy_details',
                'eligibility',
                'selection_process',
                'salary_details',
                'how_to_apply',
                'important_links',
                'official_website',
            ]);
        });
    }
};
