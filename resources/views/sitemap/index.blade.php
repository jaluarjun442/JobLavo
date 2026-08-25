@php
    echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- =====================================================
         MAIN / STATIC URLS
    ====================================================== --}}

    @foreach($urls as $url)

        <url><loc>{{ $url }}</loc></url>

    @endforeach



    {{-- =====================================================
         CATEGORY URLS
    ====================================================== --}}

    @foreach($categories as $category)

        <url><loc>{{ url('/category/' . $category->slug) }}</loc></url>

    @endforeach

</urlset>