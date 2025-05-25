<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <title>{{ config('app.name', 'NigerDev') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            // Forcer HTTPS sur les assets et formulaires
            if (window.location.protocol === 'https:') {
                // Forcer HTTPS sur les assets
                const links = document.getElementsByTagName('link');
                const scripts = document.getElementsByTagName('script');
                
                for (let i = 0; i < links.length; i++) {
                    if (links[i].href.startsWith('http:')) {
                        links[i].href = links[i].href.replace('http:', 'https:');
                    }
                }
                
                for (let i = 0; i < scripts.length; i++) {
                    if (scripts[i].src.startsWith('http:')) {
                        scripts[i].src = scripts[i].src.replace('http:', 'https:');
                    }
                }

                // Forcer HTTPS sur les formulaires
                document.addEventListener('DOMContentLoaded', function() {
                    const forms = document.getElementsByTagName('form');
                    for (let i = 0; i < forms.length; i++) {
                        if (forms[i].action.startsWith('http:')) {
                            forms[i].action = forms[i].action.replace('http:', 'https:');
                        }
                    }
                });
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
