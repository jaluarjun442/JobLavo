<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Home Page
     */
    public function home()
    {
        return view('welcome');
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();


        /*
    |--------------------------------------------------------------------------
    | Sub Categories
    |--------------------------------------------------------------------------
    */

        $subCategories = Category::where('parent_id', $category->id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();


        /*
    |--------------------------------------------------------------------------
    | Category + Sub Category IDs
    |--------------------------------------------------------------------------
    */

        $categoryIds = collect([$category->id])
            ->merge(
                $subCategories->pluck('id')
            )
            ->unique()
            ->values();


        /*
    |--------------------------------------------------------------------------
    | Posts
    |--------------------------------------------------------------------------
    */

        $posts = Post::whereIn('category_id', $categoryIds)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(15);


        return view(
            'category',
            compact(
                'category',
                'subCategories',
                'posts'
            )
        );
    }
    /**
     * Single Post
     */
    public function post($slug)
    {
        $post = Post::with('category')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();


        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
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
        $query = trim($request->get('q', ''));

        $posts = collect();

        if ($query !== '') {

            $posts = Post::with('category')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where(function ($q) use ($query) {

                    $q->where('title', 'like', '%' . $query . '%')
                        ->orWhere('excerpt', 'like', '%' . $query . '%')
                        ->orWhere('content', 'like', '%' . $query . '%');
                })
                ->latest('published_at')
                ->paginate(15)
                ->withQueryString();
        }

        return view('search', compact('query', 'posts'));
    }


    /**
     * Static Pages
     */
    public function staticPage($page)
    {
        $pages = [

            'about-us' => [
                'title' => 'About Us',
                'description' => 'Learn more about our government jobs and recruitment updates website.',
                'keywords' => 'about us, government jobs, govt jobs, recruitment',
            ],

            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'description' => 'Read our privacy policy and learn how information is handled on this website.',
                'keywords' => 'privacy policy, government jobs website',
            ],

            'terms-and-conditions' => [
                'title' => 'Terms & Conditions',
                'description' => 'Read the terms and conditions for using our government jobs and updates website.',
                'keywords' => 'terms and conditions, government jobs website',
            ],

            'disclaimer' => [
                'title' => 'Disclaimer',
                'description' => 'Read the disclaimer for information published on our government jobs website.',
                'keywords' => 'disclaimer, government jobs, recruitment',
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
        return match ($page) {

            'about-us' => '

                <h2>About Our Website</h2>

                <p>
                    Our website provides useful information about
                    government jobs, recruitment notifications,
                    admit cards, answer keys, examination results
                    and other government employment updates.
                </p>

                <p>
                    We aim to make important recruitment information
                    easier to find and understand for job seekers.
                </p>

                <p>
                    Users should always verify important information
                    such as vacancies, eligibility, application dates
                    and official notifications from the concerned
                    government department or recruitment authority.
                </p>

            ',


            'privacy-policy' => '

                <h2>Privacy Policy</h2>

                <p>
                    We respect your privacy and are committed to
                    protecting the information of visitors using
                    this website.
                </p>

                <h3>Information We Collect</h3>

                <p>
                    We may collect basic information such as browser
                    information, device information and website usage
                    data for security, analytics and improving the
                    website experience.
                </p>

                <h3>Cookies</h3>

                <p>
                    This website may use cookies and similar technologies
                    to improve functionality, understand website usage
                    and provide relevant services.
                </p>

                <h3>Third Party Services</h3>

                <p>
                    Third-party services such as analytics or advertising
                    providers may collect information according to
                    their own privacy policies.
                </p>

                <h3>Contact</h3>

                <p>
                    If you have any questions regarding this privacy
                    policy, please contact us through our contact page.
                </p>

            ',


            'terms-and-conditions' => '

                <h2>Terms & Conditions</h2>

                <p>
                    By accessing and using this website, you agree to
                    comply with these terms and conditions.
                </p>

                <h3>Use of Information</h3>

                <p>
                    Information published on this website is provided
                    for general informational purposes. Users should
                    independently verify important information with
                    the relevant official authority.
                </p>

                <h3>External Links</h3>

                <p>
                    Our website may contain links to external websites.
                    We are not responsible for the content, availability
                    or policies of external websites.
                </p>

                <h3>Content Accuracy</h3>

                <p>
                    We make reasonable efforts to provide useful and
                    updated information, but we do not guarantee that
                    every piece of information will always be complete,
                    accurate or current.
                </p>

            ',


            'disclaimer' => '

                <h2>Disclaimer</h2>

                <p>
                    The information provided on this website is for
                    general informational purposes only.
                </p>

                <p>
                    We are not a government department, government
                    recruitment authority or official government
                    organization unless explicitly stated otherwise.
                </p>

                <p>
                    Recruitment notifications, vacancies, examination
                    dates, eligibility requirements, application fees
                    and other details may change. Users should always
                    verify such information through the official
                    website or notification of the concerned authority.
                </p>

                <p>
                    We are not responsible for any loss or inconvenience
                    resulting from reliance on information published
                    on this website.
                </p>

            ',


            default => '',
        };
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
