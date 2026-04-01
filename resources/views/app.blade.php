<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ $faviconUrl ?? asset('images/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl ?? asset('images/favicon.png') }}">
        @if(!empty($primaryColor) || !empty($secondaryColor))
        <style>
            :root {
                @if(!empty($primaryColor)) --zuaaz-primary: {{ $primaryColor }}; @endif
                @if(!empty($secondaryColor)) --zuaaz-secondary: {{ $secondaryColor }}; @endif
            }
        </style>
        @endif

        <!-- Theme: light by default, respect saved preference to avoid flash -->
        <script>
            (function() {
                var theme = localStorage.getItem('theme');
                if (theme === 'dark') document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
            })();
        </script>

        <!-- Fonts: Open Sans (default), Fraunces (editorial home) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Organization + WebSite schema (SEO) -->
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => rtrim(config('app.url'), '/') . '/#organization',
                    'name' => config('zuaaz.name', 'SK Traders'),
                    'url' => config('app.url'),
                    'description' => 'Quality sofas and furniture store in Zambia. SK Traders – Lusaka showroom and nationwide delivery.',
                    'email' => config('zuaaz.contact.email', 'info@atlanticgardenfurniture.com'),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => config('zuaaz.address.office'),
                        'addressLocality' => 'Lusaka',
                        'addressRegion' => '',
                        'addressCountry' => 'ZM',
                    ],
                    'sameAs' => array_values(array_filter([
                        config('zuaaz.contact.instagram'),
                        config('zuaaz.contact.facebook'),
                        config('zuaaz.contact.linkedin'),
                        config('zuaaz.contact.twitter'),
                    ])),
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => rtrim(config('app.url'), '/') . '/#website',
                    'url' => config('app.url'),
                    'name' => config('zuaaz.name', 'SK Traders'),
                    'description' => 'Quality sofas and furniture in Zambia. Lusaka showroom, nationwide delivery.',
                    'publisher' => ['@id' => rtrim(config('app.url'), '/') . '/#organization'],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => ['@type' => 'EntryPoint', 'urlTemplate' => rtrim(config('app.url'), '/') . '/products?category={search_term_string}'],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES) !!}
        </script>

        @if(!empty($googleAnalyticsId ?? null))
        <!-- Google Analytics (GA4) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){ dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ $googleAnalyticsId }}');
        </script>
        @endif
        @if(!empty($customHeadScripts ?? null))
        {!! $customHeadScripts !!}
        @endif

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
