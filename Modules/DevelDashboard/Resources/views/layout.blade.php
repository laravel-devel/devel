<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    @include('develcore::seo._metatags')

    <link rel="stylesheet" href="{{ mix('css/devel/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/devel/line-awesome.min.css') }}">
</head>
<body>
    <div id="vue" class="container">
        <v-alert></v-alert>

        <v-notification :flash="[
            @if (session('info'))
                { type: 'info', 'message': {{ json_encode(session('info')) }} },
            @elseif (session('success'))
                { type: 'success', 'message': {{ json_encode(session('success')) }} },
            @elseif (session('error'))
                { type: 'error', 'message': {{ json_encode(session('error')) }} },
            @endif
        ]"></v-notification>

        @include('develdashboard::_sidebar')

        <main class="main">
            @include('develdashboard::_header')
            
            <section class="content">
                @yield('content')
            </section>
        </main>
    </div>

    <script>
        window.$user = {!! auth()->user() !!};
    </script>
    <script src="{{ mix('js/devel/dashboard.js') }}"></script>
</body>
</html>