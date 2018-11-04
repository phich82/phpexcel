@if (!empty($patterns))
    <ul>
    @foreach ($patterns as $pattern)
        <li><a href="{{ $pattern['file'] }}">{!! $pattern['title'] !!}</a></li>
    @endforeach
    </ul>
@endif
