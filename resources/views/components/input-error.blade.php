@props(['messages'])
@if ($messages)
    @foreach ((array) $messages as $message)
        <div class="text-danger small">{{ $message }}</div>
    @endforeach
@endif
