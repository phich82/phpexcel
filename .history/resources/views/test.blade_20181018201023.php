@if (!empty($patterns))
    <ul style="border: 1px solid red;">
    @foreach ($patterns as $pattern)
        <li><a href="{{ $pattern['file'] }}">{!! $pattern['title'] !!}</a></li>
    @endforeach
    </ul>
@endif
