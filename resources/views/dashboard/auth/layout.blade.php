<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/dashboard.css') }}">

    <title>Auth</title>
</head>
<body>
    <div id="vue" class="container">
        <main class="main">
            <section class="content center">
                @yield('content')
            </section>
        </main>
    </div>

    <script src="{{ mix('js/dashboard.js') }}"></script>
</body>
</html>