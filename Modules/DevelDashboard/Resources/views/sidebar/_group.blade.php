<ul class="group">
    <li>
        <p class="group-name">
            <a href="{{ $item['url'] ?: '#' }}">{{ $name }}</a>
        </p>

        @if (isset($item['children']))
            @foreach ($item['children'] as $name => $item)
                @include('develdashboard::sidebar._group', [
                    'name' => $name,
                    'item' => $item,
                ])
            @endforeach
        @endif
    </li>
</ul>
