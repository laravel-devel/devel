@if (isset($_metatags))
    @foreach ($_metatags as $_tag)
        {!! $_tag->toHtml() !!}
    @endforeach
@endif