User-agent: *
Allow: /

Sitemap: {{ url('/sitemap.xml') }}
@for($i = 1; $i <= $sitemapCount; $i++)
Sitemap: {{ url('/sitemap-' . $i . '.xml') }}
@endfor