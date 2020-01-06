<div class="sidebar">
    <header class="header">
        <a href="{{ route('dashboard.index') }}">{{ config('app.name') }}</a>
    </header>

    <main class="main">
        @foreach ($sidebarMenu as $category => $groups)
            <p class="category">
                {{ $category }}
            </p>

            @include('dashboard.sidebar._category', ['groups' => $groups])
        @endforeach
    </main>
</div>