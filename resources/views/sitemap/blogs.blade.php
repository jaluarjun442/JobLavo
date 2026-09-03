@php
    echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($blogs as $blog)

        <url>

            <loc>
                {{ route('blog.show', $blog->slug) }}
            </loc>

            @if($blog->updated_at)

                <lastmod>
                    {{ $blog->updated_at->toAtomString() }}
                </lastmod>

            @elseif($blog->published_date)

                <lastmod>
                    {{ $blog->published_date->toAtomString() }}
                </lastmod>

            @endif

            <changefreq>
                weekly
            </changefreq>

            <priority>
                0.7
            </priority>

        </url>

    @endforeach

</urlset>