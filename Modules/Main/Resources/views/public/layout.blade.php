<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    @include('core::seo._metatags')

    @stack('head')
</head>
<body>
    <main id="vue" class="main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>