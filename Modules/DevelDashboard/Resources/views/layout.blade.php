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
        
        @include('develdashboard::_sidebar')

        <main class="main">
            @include('develdashboard::_header')
            
            <section class="content">
                @yield('content')
            </section>
        </main>
    </div>

    <script src="{{ mix('js/devel/dashboard.js') }}"></script>
</body>
</html>