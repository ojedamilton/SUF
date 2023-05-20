<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="SUF">
        <meta name="keyword" content="SUF, Sistema Unificado de Facturación">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{asset('img/suf.png')}}">
        <!-- Add Css -->
        @yield('styles')
        <!-- Add more Css -->
        @yield('pre-scripts')
        <!-- Scripts con el defer se carga luego del hmtl -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Add more JS -->
        @yield('scripts')
    </head>
    <body id="bodycontaint" class="hold-transition sidebar-mini">
        <div id="app" class="wrapper">

            @yield('navbar')

            @yield('sidebar')

           {{--  <main class="">--}}

                @yield('content')
                
           {{--  </main> --}}
        
            @yield('footer')

        </div>
    </body>
</html>
<!-- Js -->
@stack('scripts')


