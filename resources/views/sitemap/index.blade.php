<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static Pages --}}

    @foreach($urls as $url)

        <url>

            <loc>{{ $url }}</loc>

        </url>

    @endforeach


    {{-- Categories --}}

    @foreach($categories as $category)

        <url>

            <loc>{{ url('/category/' . $category->slug) }}</loc>

        </url>

    @endforeach

</urlset>