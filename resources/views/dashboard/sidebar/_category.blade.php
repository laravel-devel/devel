@foreach ($groups as $group => $items)
    <ul class="group">
        <li>
            <p class="group-name">
                <a href="#">{{ $group }}</a>
            </p>

            <ul class="items">
                @foreach ($items as $url => $item)
                    <li>
                        <a href="{{ $url }}">{{ $item }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
@endforeach