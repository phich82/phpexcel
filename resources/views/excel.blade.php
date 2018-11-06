@extends('layouts.app')

@section('content')
<div class="container">
    <button class="btn btn-primary download">Download here</button>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $(document).on('click', '.download', function (e) {
            e.preventDefault();
            window.location.href = "{{ route('excel') }}";
        });
    });
</script>
@endpush
