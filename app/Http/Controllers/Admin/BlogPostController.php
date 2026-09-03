<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Blog Posts List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $posts = BlogPost::query()
            ->latest('published_date')
            ->paginate(20);

        return view(
            'admin.blog.index',
            compact('posts')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.blog.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated =
            $this->validateBlogPost(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] =
            $validated['slug']
            ? Str::slug(
                $validated['slug']
            )
            : Str::slug(
                $validated['title']
            );


        /*
        |--------------------------------------------------------------------------
        | Published Date
        |--------------------------------------------------------------------------
        */

        $validated['published_date'] =
            $validated['published_date']
            ?? now();


        /*
        |--------------------------------------------------------------------------
        | Published By
        |--------------------------------------------------------------------------
        */

        $validated['published_by'] =
            $validated['published_by']
            ?? 'JobLavo';


        /*
        |--------------------------------------------------------------------------
        | Desktop Image
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'desktop_image'
            )
        ) {

            $image =
                $request->file(
                    'desktop_image'
                );


            $filename =
                $image->getClientOriginalName();


            $image->move(
                base_path(
                    'uploads/blogs'
                ),
                $filename
            );


            $validated['desktop_image'] =
                'uploads/blogs/' .
                $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Mobile Image
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'mobile_image'
            )
        ) {

            $image =
                $request->file(
                    'mobile_image'
                );


            $filename =
                $image->getClientOriginalName();


            $image->move(
                base_path(
                    'uploads/blogs'
                ),
                $filename
            );


            $validated['mobile_image'] =
                'uploads/blogs/' .
                $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Blog Post
        |--------------------------------------------------------------------------
        */

        BlogPost::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.blog.index'
            )
            ->with(
                'success',
                'Blog post created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        BlogPost $blog
    ) {

        return view(
            'admin.blog.edit',
            compact('blog')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        BlogPost $blog
    ) {

        $validated =
            $this->validateBlogPost(
                $request,
                $blog
            );


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] =
            $validated['slug']
            ? Str::slug(
                $validated['slug']
            )
            : Str::slug(
                $validated['title']
            );


        /*
        |--------------------------------------------------------------------------
        | Desktop Image
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'desktop_image'
            )
        ) {

            $image =
                $request->file(
                    'desktop_image'
                );


            $filename =
                $image->getClientOriginalName();


            $image->move(
                base_path(
                    'uploads/blogs'
                ),
                $filename
            );


            $validated['desktop_image'] =
                'uploads/blogs/' .
                $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Mobile Image
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'mobile_image'
            )
        ) {

            $image =
                $request->file(
                    'mobile_image'
                );


            $filename =
                $image->getClientOriginalName();


            $image->move(
                base_path(
                    'uploads/blogs'
                ),
                $filename
            );


            $validated['mobile_image'] =
                'uploads/blogs/' .
                $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Blog Post
        |--------------------------------------------------------------------------
        */

        $blog->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.blog.index'
            )
            ->with(
                'success',
                'Blog post updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        BlogPost $blog
    ) {

        $blog->delete();


        return redirect()
            ->route(
                'admin.blog.index'
            )
            ->with(
                'success',
                'Blog post deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function validateBlogPost(
        Request $request,
        ?BlogPost $blog = null
    ): array {

        $slugRule = [

            'nullable',

            'string',

            'max:255',

        ];


        if ($blog) {

            $slugRule[] =
                'unique:blog_posts,slug,' .
                $blog->id;

        } else {

            $slugRule[] =
                'unique:blog_posts,slug';

        }


        return $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => $slugRule,


            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            'desktop_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'mobile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],


            /*
            |--------------------------------------------------------------------------
            | Content
            |--------------------------------------------------------------------------
            */

            'content' => [
                'required',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            'seo_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'meta_keywords' => [
                'nullable',
                'string',
                'max:500',
            ],

            'canonical_url' => [
                'nullable',
                'url',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

            'published_date' => [
                'nullable',
                'date',
            ],

            'published_by' => [
                'nullable',
                'string',
                'max:255',
            ],

        ]);
    }
}