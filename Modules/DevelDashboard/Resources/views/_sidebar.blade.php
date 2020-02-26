<div class="sidebar">
    <header class="header">
        <a href="{{ route('dashboard.index') }}">{{ setting('site-name') }}</a>
    </header>

    <main class="main">
        @foreach ($sidebarMenu as $category => $items)
            <p class="category">
                {{ $category }}
            </p>

            @foreach ($items as $name => $item)
                @include('develdashboard::sidebar._group', [
                    'name' => $name,
                    'item' => $item,
                ])
            @endforeach
        @endforeach
    </main>
</div>