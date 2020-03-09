@extends('main::public.layout')

@push('head')
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400&display=swap" rel="stylesheet"> 

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html, body {
        height: 100vh;
    }

    html {
        font-size: 16px;
        font-family: 'Open Sans', sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #6dd5fa;
        background: linear-gradient(to right, #2980b9, #6dd5fa, #ffffff);
    }

    .main {
        text-align: center;
    }

    .main .title h1 {
        font-weight: 300;
        font-size: 5rem;
        color: #444;
    }

    .main .nav a {
        text-decoration: none;
        outline: none;
        margin: 0 1rem;
        color: #444;
        transition: color .2s ease;
    }
    .main .nav a:hover {
        color: #fff;
    }
</style>
@endpush

@section('content')
    <main class="main">
        <div class="title">
            <h1>Devel</h1>
        </div>

        <nav class="nav">
            <a href="{{ route('dashboard.index') }}">Dashboard</a>
            <a href="http://voerro.com/en/projects/devel" target="_blank">Documentation</a>
            <a href="https://github.com/voerro/devel" target="_blank">GitHub</a>
        </nav>
    </main>
@endsection