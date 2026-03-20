<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('meta_description', __('messages.meta_description_page_accueil'))">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Philosopher:ital@0;1&display=swap" rel="stylesheet">

        <title>@yield('title', 'Renait-Sens — L\'Ombre du Tassili<') </title>

       

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

       
        {{-- CSS spécifique page --}}
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-white text-gray-800">
        

        <!-- Page Content -->
        <main class="pt-14">
            @yield('content')
        </main>

        
        @stack('scripts')
    </body>
</html>
