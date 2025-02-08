<div wire:ignore.self class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            @if($hasForm)
                <form {{ $attributes }}>
                    @endif
                        <div class="modal-body">
                        {{ $slot }}
                        </div>
                        <div class="modal-footer">
                        {{ $footer }}
                        </div>
                    @if($hasForm)
                </form>
            @endif
        </div>
    </div>
</div>