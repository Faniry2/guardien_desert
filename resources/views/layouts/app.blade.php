<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('meta_description', __('messages.meta_description_page_accueil'))">
        
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
        <link rel="manifest" href="/site.webmanifest" />
        
        
       
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&display=swap" rel="stylesheet">

        <title>@yield('title', 'Renait-Sens — L\'Ombre du Tassili<') </title>

       

        <!-- Scripts -->
        @vite([ 'resources/js/app.js'])

       
        {{-- CSS spécifique page --}}
        @stack('styles')
    </head>
    <body >
        

        <!-- Page Content -->
        <main class="pt-14">
            @yield('content')
        </main>

        
        @stack('scripts')
    </body>
</html>