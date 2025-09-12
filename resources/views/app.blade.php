<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="Ustawi Wa Jamii - Community-driven organization dedicated to creating sustainable development and empowering youth through innovative programs and collective action.">
        <meta name="keywords" content="Ustawi Wa Jamii, community development, youth empowerment, sustainable development, Kenya, NGO">
        <meta name="author" content="Ustawi Wa Jamii">
        
        <!-- Favicon and Logo -->
        <link rel="icon" type="image/jpeg" href="/app-logo.jpg">
        <link rel="shortcut icon" type="image/jpeg" href="/app-logo.jpg">
        <link rel="apple-touch-icon" href="/app-logo.jpg">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="Ustawi Wa Jamii - Empowering Communities Worldwide">
        <meta property="og:description" content="Community-driven organization dedicated to creating sustainable development and empowering youth through innovative programs and collective action.">
        <meta property="og:image" content="{{ url('/app-logo.jpg') }}">
        <meta property="og:site_name" content="Ustawi Wa Jamii">
        
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/') }}">
        <meta property="twitter:title" content="Ustawi Wa Jamii - Empowering Communities Worldwide">
        <meta property="twitter:description" content="Community-driven organization dedicated to creating sustainable development and empowering youth through innovative programs and collective action.">
        <meta property="twitter:image" content="{{ url('/app-logo.jpg') }}">
        
        <!-- Structured Data for Google -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Ustawi Wa Jamii",
            "alternateName": "Ustawi",
            "url": "{{ url('/') }}",
            "logo": "{{ url('/app-logo.jpg') }}",
            "description": "Community-driven organization dedicated to creating sustainable development and empowering youth through innovative programs and collective action.",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Nairobi",
                "addressCountry": "KE"
            },
            "sameAs": [
                "https://facebook.com/ustawiwajamii",
                "https://twitter.com/ustawiwajamii",
                "https://instagram.com/ustawiwajamii"
            ]
        }
        </script>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased overflow-x-hidden">
        @inertia
    </body>
</html>
