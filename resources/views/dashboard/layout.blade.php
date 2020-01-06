<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/dashboard.css') }}">

    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        @include('dashboard._sidebar')

        <main class="main">
            @include('dashboard._header')
            
            <section class="content">
                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>