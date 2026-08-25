<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where(
            'slug',
            'latest-government-jobs'
        )->first();

        if (!$category) {
            return;
        }


        Post::updateOrCreate(
            [
                'slug' => 'ssc-cgl-recruitment-2026',
            ],
            [

                'category_id' => $category->id,

                'title' => 'SSC CGL Recruitment 2026 – Apply Online',

                'slug' => 'ssc-cgl-recruitment-2026',


                /*
                |--------------------------------------------------------------------------
                | Short Content
                |--------------------------------------------------------------------------
                */

                'excerpt' =>
                'SSC CGL Recruitment 2026 latest notification, eligibility, important dates, vacancy details and online application information.',

                'short_description' =>
                'SSC CGL Recruitment 2026 notification details including eligibility, vacancies, application fee, important dates and online application process.',


                /*
                |--------------------------------------------------------------------------
                | Main Content
                |--------------------------------------------------------------------------
                */

                'content' => '

                    <p>
                        SSC CGL Recruitment 2026 notification has been
                        released for candidates interested in various
                        government posts. Eligible candidates can check
                        the complete recruitment details before applying
                        online.
                    </p>

                    <p>
                        Candidates are advised to carefully check the
                        eligibility criteria, important dates, application
                        fee and selection process before submitting the
                        application form.
                    </p>

                ',


                /*
                |--------------------------------------------------------------------------
                | Important Dates
                |--------------------------------------------------------------------------
                */

                'important_dates' => '

                    <ul>
                        <li><strong>Notification Date:</strong> 22 August 2026</li>
                        <li><strong>Online Application Start:</strong> 22 August 2026</li>
                        <li><strong>Last Date to Apply:</strong> As per official notification</li>
                        <li><strong>Exam Date:</strong> To be announced</li>
                    </ul>

                ',


                /*
                |--------------------------------------------------------------------------
                | Application Fee
                |--------------------------------------------------------------------------
                */

                'application_fee' => '

                    <ul>
                        <li>General / OBC / EWS: As per official notification</li>
                        <li>SC / ST / PwD: As per official notification</li>
                    </ul>

                    <p class="mb-0">
                        Candidates should check the official notification
                        for the latest application fee details.
                    </p>

                ',


                /*
                |--------------------------------------------------------------------------
                | Age Limit
                |--------------------------------------------------------------------------
                */

                'age_limit' => '

                    <ul>
                        <li><strong>Minimum Age:</strong> 18 Years</li>
                        <li><strong>Maximum Age:</strong> As per post</li>
                    </ul>

                    <p class="mb-0">
                        Age relaxation will be applicable as per
                        government rules.
                    </p>

                ',


                /*
                |--------------------------------------------------------------------------
                | Vacancy Details
                |--------------------------------------------------------------------------
                */

                'vacancy_details' => '

                    <p>
                        Vacancy details will vary according to the
                        post and department.
                    </p>

                    <table>
                        <thead>
                            <tr>
                                <th>Post</th>
                                <th>Total Vacancy</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Various Government Posts</td>
                                <td>To be announced</td>
                            </tr>
                        </tbody>
                    </table>

                ',


                /*
                |--------------------------------------------------------------------------
                | Eligibility
                |--------------------------------------------------------------------------
                */

                'eligibility' => '

                    <p>
                        Candidates should have the required educational
                        qualification for the respective post.
                    </p>

                    <ul>
                        <li>Educational qualification varies by post.</li>
                        <li>Candidates must satisfy the required age limit.</li>
                        <li>Other eligibility conditions will be as per the official notification.</li>
                    </ul>

                ',


                /*
                |--------------------------------------------------------------------------
                | Selection Process
                |--------------------------------------------------------------------------
                */

                'selection_process' => '

                    <ol>
                        <li>Computer Based Examination</li>
                        <li>Skill Test / Additional Test where applicable</li>
                        <li>Document Verification</li>
                        <li>Final Selection</li>
                    </ol>

                ',


                /*
                |--------------------------------------------------------------------------
                | Salary
                |--------------------------------------------------------------------------
                */

                'salary_details' => '

                    <p>
                        Salary and pay level will depend on the
                        selected post and department.
                    </p>

                    <p class="mb-0">
                        Candidates should refer to the official
                        recruitment notification for complete
                        salary and pay-level information.
                    </p>

                ',


                /*
                |--------------------------------------------------------------------------
                | How To Apply
                |--------------------------------------------------------------------------
                */

                'how_to_apply' => '

                    <ol>
                        <li>Visit the official recruitment website.</li>
                        <li>Open the SSC CGL Recruitment 2026 application link.</li>
                        <li>Complete the registration process.</li>
                        <li>Fill in the required application details.</li>
                        <li>Upload the required documents.</li>
                        <li>Pay the applicable application fee.</li>
                        <li>Submit the application form.</li>
                        <li>Save or print the submitted application for future reference.</li>
                    </ol>

                ',


                /*
                |--------------------------------------------------------------------------
                | Important Links
                |--------------------------------------------------------------------------
                */

                'important_links' => '

                    <div class="d-flex flex-column gap-2">

                        <a href="https://example.com"
                           target="_blank"
                           rel="nofollow noopener"
                           class="btn btn-primary">

                            Apply Online

                        </a>

                        <a href="https://example.com"
                           target="_blank"
                           rel="nofollow noopener"
                           class="btn btn-outline-primary">

                            Download Official Notification

                        </a>

                    </div>

                ',


                /*
                |--------------------------------------------------------------------------
                | Official Website
                |--------------------------------------------------------------------------
                */

                'official_website' => 'https://example.com',


                /*
                |--------------------------------------------------------------------------
                | SEO
                |--------------------------------------------------------------------------
                */

                'seo_title' =>
                'SSC CGL Recruitment 2026 – Apply Online',

                'meta_description' =>
                'SSC CGL Recruitment 2026 latest notification, eligibility, vacancy, important dates, application fee and online application details.',

                'meta_keywords' =>
                'SSC CGL Recruitment 2026, SSC CGL, SSC Jobs, Government Jobs, SSC CGL Apply Online',

                'canonical_url' => null,


                /*
                |--------------------------------------------------------------------------
                | Publishing
                |--------------------------------------------------------------------------
                */

                'status' => 'published',

                'published_at' => Carbon::now(),

                'is_featured' => true,

                'is_important' => true,

            ]
        );
    }
}
