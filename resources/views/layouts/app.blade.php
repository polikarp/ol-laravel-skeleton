<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96">
    <title>{{ config('app.name', 'GEMS') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
    <link id="pagestyle" href="{{ asset('css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" />
    @livewireStyles
</head>
<body>
    <div id="app">
        @include('layouts.navbar')
        <main class="front_end">
            @yield('content')
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
    <script  type="module">
        // $( "#iconSidebar" ).on( "click", function() {
        //     if ($('#sidenav-main').is(":hidden")) {

        //         $('#sidenav-main').show('fast');
        //     } else {
        //         $('#sidenav-main').hide( 'fast' );
        //     }
        // } );
        // $( "#iconSidebarClose" ).on( "click", function() {
        //     $('#sidenav-main').hide( 'fast' );
        // } );
    </script>
</body>
</html>
