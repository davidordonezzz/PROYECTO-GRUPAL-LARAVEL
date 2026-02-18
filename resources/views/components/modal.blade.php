@props(['name', 'show' => false, 'focusable' => false])
<div class="modal fade @if($show) show @endif" id="modal-{{ $name }}" tabindex="-1" @if($show) style="display:block" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
@if($show)
<div class="modal-backdrop fade show"></div>
@endif
