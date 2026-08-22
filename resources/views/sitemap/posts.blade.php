@php
    echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach($posts as $post)

    <url>
        <loc>{{ url('/post/' . $post->slug) }}</loc>

        @if($post->updated_at)
            <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        @elseif($post->published_at)
            <lastmod>{{ $post->published_at->toAtomString() }}</lastmod>
        @endif

    </url>

@endforeach

</urlset>