<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Campus Event</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        html, body {
            font-family: 'Nunito', sans-serif;
            min-height: 100%;
            margin: 0;
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #0d6efd;
        }

        #app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-page {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
    </style>
</head>
<body>
    <div id="app">

        @if (!request()->routeIs('login', 'register'))
            @include('layouts.navbar')
        @endif
        
        <main class="{{ request()->routeIs('login', 'register') ? 'auth-page' : '' }}">
            @yield('content')
        </main>
    </div>
    
    @if (!request()->routeIs('login', 'register'))
        @include('layouts.footer')
    @endif

    <script src="https://code.jquery.com/jquery-4.0.0.js" integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U=" crossorigin="anonymous"></script>
</body>
</html>
