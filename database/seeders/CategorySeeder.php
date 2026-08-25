<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Latest Government Jobs',
                'slug' => 'latest-government-jobs',
            ],

            [
                'name' => 'Admit Card',
                'slug' => 'admit-card',
            ],

            [
                'name' => 'Answer Key',
                'slug' => 'answer-key',
            ],

            [
                'name' => 'Government Exam Results',
                'slug' => 'government-exam-results',
            ],

            [
                'name' => 'Syllabus',
                'slug' => 'syllabus',
            ],

            [
                'name' => 'Important Dates',
                'slug' => 'important-dates',
            ],

            [
                'name' => 'Railway Jobs',
                'slug' => 'railway-jobs',
            ],

            [
                'name' => 'Banking Jobs',
                'slug' => 'banking-jobs',
            ],

            [
                'name' => 'Teaching Jobs',
                'slug' => 'teaching-jobs',
            ],

            [
                'name' => 'Defence Jobs',
                'slug' => 'defence-jobs',
            ],

            [
                'name' => 'Police Jobs',
                'slug' => 'police-jobs',
            ],

            [
                'name' => 'Central Government Jobs',
                'slug' => 'central-government-jobs',
            ],

            [
                'name' => 'State Government Jobs',
                'slug' => 'state-government-jobs',
            ],

        ];


        foreach ($categories as $index => $category) {

            Category::updateOrCreate(
                [
                    'slug' => $category['slug'],
                ],
                [
                    'name' => $category['name'],
                    'status' => true,
                    'sort_order' => $index + 1,
                ]
            );

        }
    }
}