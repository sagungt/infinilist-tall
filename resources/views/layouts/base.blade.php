<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @hasSection('title')

            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <!-- Favicon -->
		<link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

        @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/ck.css'])
        @livewireStyles
        @livewireScripts

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>
        <livewire:partials.navbar />
        @yield('body')
        <livewire:partials.footer />
    </body>
    <script src="https://unpkg.com/flowbite@1.5.5/dist/flowbite.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
    @stack('scripts')
    <script>
        // document.addEventListener('DOMContentLoaded', () => {
        //     const navbarMenu = document.querySelector('#navbar-menu');
        //     const navbarButton = document.querySelector('#navbar-button');
        //     navbarButton.addEventListener('click', () => {
        //         navbarMenu.classList.toggle('hidden');
        //         navbarButton.querySelector('.hamburger').classList.toggle('active')
        //     });
        // })
    </script>
</html>
