<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function home()
    {
        /*
    |--------------------------------------------------------------------------
    | Home Small Tiles
    |--------------------------------------------------------------------------
    */

        $homeTileCategories = Category::query()

            ->where('status', true)

            ->where('display_home_tiles', true)

            ->whereNull('parent_id')

            ->orderBy('sort_order')

            ->orderBy('name')

            ->get();


        /*
    |--------------------------------------------------------------------------
    | Home Large Categories
    |--------------------------------------------------------------------------
    */

        $homeLargeCategories = Category::query()

            ->where('status', true)

            ->where('display_home_large', true)

            ->whereNull('parent_id')

            ->orderBy('sort_order')

            ->orderBy('name')

            ->get();


        /*
    |--------------------------------------------------------------------------
    | Load Latest Posts For Each Category
    |--------------------------------------------------------------------------
    |
    | Use category_post pivot through whereHas('categories').
    | This supports posts assigned to multiple categories.
    |
    */

        $homeLargeCategories->each(function ($category) {

            /*
    |--------------------------------------------------------------------------
    | Parent + Child Category IDs
    |--------------------------------------------------------------------------
    */

            $categoryIds = collect([
                $category->id
            ]);

            $childCategoryIds = Category::query()
                ->where('parent_id', $category->id)
                ->where('status', true)
                ->pluck('id');

            $categoryIds = $categoryIds
                ->merge($childCategoryIds)
                ->unique()
                ->values()
                ->all();


            /*
    |--------------------------------------------------------------------------
    | Posts From Parent + Child Categories
    |--------------------------------------------------------------------------
    */

            $posts = Post::query()

                ->with('categories')

                ->whereHas(
                    'categories',
                    function ($query) use ($categoryIds) {

                        $query->whereIn(
                            'categories.id',
                            $categoryIds
                        );
                    }
                )

                ->where('status', 'published')

                ->whereNotNull('published_at')

                ->where(
                    'published_at',
                    '<=',
                    now()
                )

                ->latest('published_at')

                ->take(10)

                ->get();


            /*
    |--------------------------------------------------------------------------
    | Attach Posts To Home Category
    |--------------------------------------------------------------------------
    */

            $category->setRelation(
                'posts',
                $posts
            );
        });


        /*
    |--------------------------------------------------------------------------
    | Latest Updates
    |--------------------------------------------------------------------------
    */

        $latestPosts = Post::query()

            ->with('categories')

            ->where('status', 'published')

            ->whereNotNull('published_at')

            ->where(
                'published_at',
                '<=',
                now()
            )

            ->latest('published_at')

            ->take(20)

            ->get();


        /*
    |--------------------------------------------------------------------------
    | Home View
    |--------------------------------------------------------------------------
    */

        return view(
            'welcome',
            compact(
                'homeTileCategories',
                'homeLargeCategories',
                'latestPosts'
            )
        );
    }
    /**
     * Latest Jobs
     */
    public function latestJobs()
    {
        $posts = Post::query()

            ->with('categories')

            ->where('status', 'published')

            ->whereNotNull('published_at')

            ->where(
                'published_at',
                '<=',
                now()
            )

            ->latest('published_at')

            ->paginate(15)

            ->withQueryString();


        return view(
            'latest-jobs',
            compact('posts')
        );
    }


    /**
     * Category Page
     */
    public function category($slug)
    {
        $category = Category::query()

            ->with([
                'children' => function ($query) {

                    $query
                        ->where('status', true)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }
            ])

            ->where('slug', $slug)

            ->where('status', true)

            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Category IDs
        |--------------------------------------------------------------------------
        |
        | Parent category:
        | current category + all direct sub-categories
        |
        | Sub-category:
        | only itself
        |
        */

        $categoryIds = collect([
            $category->id
        ]);


        if ($category->children->count()) {

            $categoryIds = $categoryIds->merge(
                $category->children->pluck('id')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Posts
        |--------------------------------------------------------------------------
        |
        | A post can belong to multiple categories.
        |
        | Therefore we use whereHas('categories')
        | instead of category_id.
        |
        */

        $posts = Post::query()

            ->with('categories')

            ->whereHas(
                'categories',
                function ($query) use ($categoryIds) {

                    $query->whereIn(
                        'categories.id',
                        $categoryIds
                            ->unique()
                            ->values()
                            ->all()
                    );
                }
            )

            ->where('status', 'published')

            ->whereNotNull('published_at')

            ->where(
                'published_at',
                '<=',
                now()
            )

            ->latest('published_at')

            ->paginate(15)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        $seoTitle = $category->seo_title
            ?: $category->name . ' - Latest Government Jobs';


        $metaDescription = $category->meta_description
            ?: $category->description
            ?: 'Check the latest ' .
            $category->name .
            ' government jobs, recruitment notifications and updates.';


        return view(
            'category',
            compact(
                'category',
                'posts',
                'seoTitle',
                'metaDescription'
            )
        );
    }


    /**
     * Single Post
     */
    public function post($slug)
    {
        $post = Post::with('categories')

            ->where('slug', $slug)

            ->where('status', 'published')

            ->whereNotNull('published_at')

            ->where(
                'published_at',
                '<=',
                now()
            )

            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Related Posts
        |--------------------------------------------------------------------------
        |
        | A post can have multiple categories.
        |
        | Example:
        |
        | Current Post:
        | Gujarat + Latest Jobs + Police
        |
        | Related posts can match ANY of these categories.
        |
        */

        $categoryIds = $post->categories
            ->pluck('id')
            ->unique()
            ->values()
            ->all();


        $relatedPosts = Post::query()

            ->with('categories')

            ->where('id', '!=', $post->id)

            ->where('status', 'published')

            ->whereNotNull('published_at')

            ->where(
                'published_at',
                '<=',
                now()
            )

            ->when(
                !empty($categoryIds),
                function ($query) use ($categoryIds) {

                    $query->whereHas(
                        'categories',
                        function ($categoryQuery) use ($categoryIds) {

                            $categoryQuery->whereIn(
                                'categories.id',
                                $categoryIds
                            );
                        }
                    );
                }
            )

            ->latest('published_at')

            ->take(5)

            ->get();


        return view(
            'post',
            compact(
                'post',
                'relatedPosts'
            )
        );
    }


    /**
     * Search
     */
    public function search(Request $request)
    {
        $query = trim(
            $request->get('q', '')
        );


        $posts = Post::query()

            ->with('categories')

            ->where('status', 'published')

            ->whereNotNull('published_at')

            ->where(
                'published_at',
                '<=',
                now()
            )

            ->when(
                $query,
                function ($builder) use ($query) {

                    $builder->where(
                        function ($q) use ($query) {

                            $q->where(
                                'title',
                                'LIKE',
                                '%' . $query . '%'
                            )

                                ->orWhere(
                                    'excerpt',
                                    'LIKE',
                                    '%' . $query . '%'
                                )

                                ->orWhere(
                                    'short_description',
                                    'LIKE',
                                    '%' . $query . '%'
                                )

                                ->orWhere(
                                    'content',
                                    'LIKE',
                                    '%' . $query . '%'
                                );
                        }
                    );
                }
            )

            ->latest('published_at')

            ->paginate(15)

            ->withQueryString();


        return view(
            'search',
            compact(
                'posts',
                'query'
            )
        );
    }


    /**
     * Static Pages
     */
    public function staticPage($page)
    {
        $pages = [

            'about-us' => [
                'title' => 'About Us',
                'description' => 'Learn about JobLavo, a government jobs and recruitment updates website providing useful information about vacancies, admit cards, results, answer keys and career opportunities.',
                'keywords' => 'about JobLavo, government jobs, govt jobs, recruitment updates, latest government vacancies, admit card, results, answer key',
            ],

            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'description' => 'Read the JobLavo privacy policy to understand how information is collected, used, protected and handled when you use our website.',
                'keywords' => 'JobLavo privacy policy, privacy policy, data protection, website privacy, user information',
            ],

            'terms-and-conditions' => [
                'title' => 'Terms & Conditions',
                'description' => 'Read the terms and conditions governing the use of JobLavo and understand the rules, responsibilities and limitations applicable to website users.',
                'keywords' => 'JobLavo terms and conditions, terms of use, website terms, government jobs website',
            ],

            'disclaimer' => [
                'title' => 'Disclaimer',
                'description' => 'Read the JobLavo disclaimer about government job information, recruitment updates, official notifications, links and information accuracy.',
                'keywords' => 'JobLavo disclaimer, government job disclaimer, recruitment information disclaimer, official notification disclaimer',
            ],

        ];


        if (!isset($pages[$page])) {

            abort(404);
        }


        $data = $pages[$page];


        return view('pages.static', [

            'pageTitle' => $data['title'],

            'metaDescription' => $data['description'],

            'metaKeywords' => $data['keywords'],

            'pageUrl' => '/' . $page,

            'content' => $this->staticPageContent($page),

        ]);
    }


    /**
     * Static Page Content
     */
    private function staticPageContent($page)
    {
        $contents = [

            /*
            |--------------------------------------------------------------------------
            | ABOUT US
            |--------------------------------------------------------------------------
            */

            'about-us' => <<<'HTML'

<h1>About JobLavo</h1>

<p>
JobLavo is an online government jobs and recruitment information platform
created to make it easier for job seekers to discover the latest government
job opportunities and important recruitment updates in one place. The
website covers useful information related to government vacancies,
recruitment notifications, admit cards, examination results, answer keys,
selection updates and other employment-related announcements.
</p>

<p>
Finding a suitable government job can sometimes require checking multiple
department websites, recruitment boards and official notification pages.
JobLavo aims to make that process simpler by organizing important recruitment
information in a clear and easy-to-read format. Visitors can browse jobs by
category, search for particular recruitment opportunities and read important
details before visiting the relevant official website.
</p>

<h2>What You Can Find on JobLavo</h2>

<p>
JobLavo publishes information covering different types of government
recruitment opportunities. Depending on current recruitment announcements,
the website may include central government jobs, state government jobs,
banking recruitment, railway jobs, teaching vacancies, police recruitment,
defence opportunities, public sector jobs and other government employment
updates.
</p>

<ul>
    <li>Latest government job notifications</li>
    <li>Central and state government recruitment updates</li>
    <li>Admit card and examination updates</li>
    <li>Government exam results</li>
    <li>Answer keys and related examination information</li>
    <li>Vacancy and post-wise details</li>
    <li>Eligibility and educational qualification information</li>
    <li>Age limit and applicable relaxation details</li>
    <li>Application dates and important deadlines</li>
    <li>Selection process and examination information</li>
    <li>Official notification and application links</li>
</ul>

<h2>Our Approach to Government Job Information</h2>

<p>
We try to present recruitment information in a structured format so that
readers can quickly understand the basic details of a vacancy. Job pages may
include information such as the organization, post name, number of vacancies,
application dates, educational qualification, age limit, application fee,
salary or pay details, selection process and how to apply.
</p>

<p>
Where an official notification or recruitment website is available, visitors
should always refer to the official source before submitting an application.
The official notification remains the final authority for eligibility,
vacancy numbers, reservation rules, age relaxation, application fees,
important dates, documents and other recruitment conditions.
</p>

<h2>Why Official Sources Matter</h2>

<p>
Government recruitment information can change because of corrigenda, revised
vacancies, extended deadlines, examination schedules or other official
updates. For this reason, JobLavo encourages candidates to verify important
information from the concerned government department, recruitment board or
official application portal before taking any action.
</p>

<p>
JobLavo provides information for convenience and does not replace the
official recruitment notification. Candidates are responsible for reading
the complete notification and ensuring that they meet all applicable
requirements before applying.
</p>

<h2>Information for Job Seekers</h2>

<p>
Government jobs can have different eligibility requirements depending on the
organization and post. Candidates should carefully check educational
qualifications, required experience, age criteria, application deadlines,
documents and selection procedures for every recruitment opportunity.
Keeping track of admit cards, results and answer keys can also be important
during the recruitment process.
</p>

<p>
Our goal is to provide a useful starting point for candidates looking for
government employment information while encouraging them to make final
decisions using official recruitment sources.
</p>

<h2>Contact and Feedback</h2>

<p>
If you find an outdated link, incorrect information or another issue on a
JobLavo page, you can contact us through the website's contact page. We
welcome useful feedback that can help improve the quality and usefulness of
the information provided to visitors.
</p>

<p>
Thank you for visiting JobLavo and using our platform for government job and
recruitment updates.
</p>

HTML,


            /*
            |--------------------------------------------------------------------------
            | PRIVACY POLICY
            |--------------------------------------------------------------------------
            */

            'privacy-policy' => <<<'HTML'

<h1>Privacy Policy</h1>

<p>
Your privacy is important to us. This Privacy Policy explains how JobLavo
handles information when you visit and use our website. By using the website,
you acknowledge that you have read and understood this policy.
</p>

<h2>Information We Collect</h2>

<p>
JobLavo is primarily an informational website. We may collect limited
information that is automatically provided by your browser or device when
you access the website. This can include information such as browser type,
device type, operating system, approximate location information, referring
pages and general website usage information.
</p>

<p>
If you voluntarily contact us or submit information through a contact form,
we may receive the information that you choose to provide, such as your name,
email address and message. We use such information only for the purpose for
which it was submitted and for responding to your enquiry where appropriate.
</p>

<h2>How We Use Information</h2>

<p>
Information may be used to operate, maintain and improve JobLavo, understand
how visitors use different sections of the website, identify technical
problems, improve website performance and respond to enquiries.
</p>

<p>
We do not use information submitted through a contact form for purposes that
are unrelated to the original enquiry unless required or permitted by
applicable law.
</p>

<h2>Cookies</h2>

<p>
JobLavo may use cookies and similar technologies to improve website
functionality, understand visitor behaviour and remember certain preferences.
Cookies are small files stored on your device by a website.
</p>

<p>
Some third-party services used on websites, including analytics and
advertising services, may also use cookies or similar technologies according
to their own policies. You can manage or disable cookies through the
settings available in your web browser. Disabling certain cookies may affect
some website functionality.
</p>

<h2>Advertising</h2>

<p>
JobLavo may display advertisements provided by third-party advertising
networks. These services may use cookies or similar technologies to display
relevant advertisements and measure advertising performance.
</p>

<p>
Third-party advertising providers operate under their own privacy policies
and terms. Users should review the privacy information provided by the
relevant advertising provider for details about how advertising-related data
is handled.
</p>

<h2>Third-Party Links</h2>

<p>
JobLavo may provide links to government departments, recruitment boards,
official application portals and other external websites. These websites
operate independently and have their own privacy policies and terms.
</p>

<p>
We are not responsible for the privacy practices, content or security
practices of external websites. Before providing personal information on an
external website, users should review that website's privacy policy.
</p>

<h2>Data Security</h2>

<p>
We take reasonable measures to protect information handled through the
website. However, no internet transmission or electronic storage system can
be guaranteed to be completely secure. Users should avoid submitting
sensitive personal information through public or unsecured channels.
</p>

<h2>Children's Privacy</h2>

<p>
JobLavo is an informational website intended for people interested in
employment and recruitment information. We do not knowingly request
unnecessary personal information from children.
</p>

<h2>Changes to This Privacy Policy</h2>

<p>
This Privacy Policy may be updated from time to time to reflect changes in
website functionality, services, legal requirements or privacy practices.
Any updated version will be published on this page.
</p>

<h2>Contact</h2>

<p>
If you have questions regarding this Privacy Policy or the handling of
information on JobLavo, please contact us through the website's contact page.
</p>

HTML,


            /*
            |--------------------------------------------------------------------------
            | TERMS
            |--------------------------------------------------------------------------
            */

            'terms-and-conditions' => <<<'HTML'

<h1>Terms &amp; Conditions</h1>

<p>
Welcome to JobLavo. By accessing or using this website, you agree to comply
with the following terms and conditions. If you do not agree with these
terms, please discontinue use of the website.
</p>

<h2>Use of the Website</h2>

<p>
JobLavo provides recruitment and employment-related information for general
informational purposes. Users may browse job listings, recruitment updates,
admit card information, examination results, answer keys and related
resources for their personal use.
</p>

<p>
Users must not misuse the website, attempt to disrupt its operation, gain
unauthorized access to restricted areas or use the website for unlawful
activities.
</p>

<h2>Government Job Information</h2>

<p>
JobLavo attempts to present useful and current recruitment information based
on available sources. However, recruitment details can change after a page
is published. Government departments and recruitment authorities may issue
corrigenda, revised notifications, deadline extensions or other updates.
</p>

<p>
The official notification issued by the concerned organization is always the
final authority. Candidates must verify eligibility, age limit, vacancy,
reservation, application fee, important dates, qualifications, selection
process and other conditions from the official notification before applying.
</p>

<h2>Official Links</h2>

<p>
Where available, JobLavo may provide links to official recruitment websites,
notification PDFs and online application portals. Users should carefully
check the website address before entering personal information or submitting
an application.
</p>

<p>
JobLavo does not control external government websites and cannot guarantee
their availability, functionality or content at all times.
</p>

<h2>Accuracy of Information</h2>

<p>
We make reasonable efforts to keep recruitment information useful and
accurate. Nevertheless, JobLavo does not guarantee that every piece of
information will always be complete, current or error-free.
</p>

<p>
If an error or outdated detail is noticed, users may contact us so that the
information can be reviewed and updated where appropriate.
</p>

<h2>External Websites</h2>

<p>
The website may contain links to third-party or external websites. These
links are provided for convenience. Visiting an external website is at the
user's own discretion and is subject to the external website's terms and
privacy policy.
</p>

<h2>Intellectual Property</h2>

<p>
Unless otherwise stated, the original design, layout, branding and editorial
content created for JobLavo are protected by applicable intellectual property
laws. Content should not be copied, reproduced or redistributed in a manner
that violates applicable law or the rights of the website owner.
</p>

<h2>Limitation of Liability</h2>

<p>
JobLavo is an informational platform and does not guarantee employment,
selection, admission to any examination or success in any recruitment
process. Users are responsible for verifying information and making their
own decisions before applying for a job or examination.
</p>

<p>
JobLavo will not be responsible for losses resulting from reliance on
outdated information, changes made by recruitment authorities, technical
problems on external websites or unsuccessful applications.
</p>

<h2>Changes to These Terms</h2>

<p>
These terms may be updated when necessary. Continued use of the website
after changes are published indicates acceptance of the updated terms.
</p>

<h2>Contact</h2>

<p>
For questions or concerns about these Terms &amp; Conditions, please contact
us through the website's contact page.
</p>

HTML,


            /*
            |--------------------------------------------------------------------------
            | DISCLAIMER
            |--------------------------------------------------------------------------
            */

            'disclaimer' => <<<'HTML'

<h1>Disclaimer</h1>

<p>
The information published on JobLavo is provided for general informational
and educational purposes. We aim to make government job notifications,
recruitment updates, admit cards, results, answer keys and related information
easier to find and understand.
</p>

<h2>Information Accuracy</h2>

<p>
We make reasonable efforts to publish useful and accurate recruitment
information. However, recruitment notifications may be changed, corrected,
extended or withdrawn by the concerned government department or recruitment
authority at any time.
</p>

<p>
For this reason, candidates should always verify important information from
the official notification and official website before applying for a
government job or examination.
</p>

<h2>Official Notification is Final</h2>

<p>
Information displayed on JobLavo, including vacancy numbers, eligibility,
educational qualifications, age limits, application fees, salary details,
important dates, selection process and application instructions, should not
be considered a replacement for the official recruitment notification.
</p>

<p>
The concerned government organization or recruitment authority is the final
source of information regarding its recruitment process. In case of any
difference between information published on JobLavo and an official
notification, the official notification should always be followed.
</p>

<h2>External Links</h2>

<p>
JobLavo may provide links to official government websites, recruitment
portals, notification PDFs and online application pages. These links are
provided to help users access the original source of information.
</p>

<p>
We do not control external websites and cannot guarantee that an external
website will always be available, accurate, secure or free from technical
issues. Users should verify the domain and website information before
submitting personal details or making any payment.
</p>

<h2>No Guarantee of Selection</h2>

<p>
Publishing a recruitment notification on JobLavo does not mean that JobLavo
is associated with the recruiting organization or that a candidate will be
selected for the advertised position.
</p>

<p>
JobLavo does not guarantee employment, examination qualification, interview
selection or appointment. Candidates are responsible for checking the
eligibility conditions and completing the application process correctly.
</p>

<h2>Application Responsibility</h2>

<p>
Candidates should carefully read the complete official notification before
submitting an application. Applicants are responsible for entering correct
personal information, uploading the required documents, paying applicable
fees where required and submitting applications before the official
deadline.
</p>

<h2>Changes and Updates</h2>

<p>
Government recruitment information can change because of revised vacancies,
corrigenda, court orders, examination schedules, deadline extensions or other
official announcements. JobLavo may update published pages when such
information becomes available.
</p>

<h2>Contact Us</h2>

<p>
If you identify an incorrect, outdated or broken piece of information on
JobLavo, please contact us through the website's contact page. We appreciate
feedback that helps improve the usefulness and accuracy of the website.
</p>

HTML,

        ];


        return $contents[$page] ?? '';
    }


    /**
     * Contact Page
     */
    public function contact()
    {
        return view('contact');
    }


    /**
     * Contact Form Submit
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
            ],

            'subject' => [
                'required',
                'string',
                'max:200',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Email / Database Integration
        |--------------------------------------------------------------------------
        |
        | Later we will save the contact request in database
        | and/or send notification email.
        |
        */


        return redirect()
            ->route('contact')
            ->with(
                'success',
                'Thank you for contacting us. Your message has been received.'
            );
    }


    /**
     * 404 Page
     */
    public function noRoute()
    {
        return response()->view(
            'errors.404',
            [],
            404
        );
    }
}
