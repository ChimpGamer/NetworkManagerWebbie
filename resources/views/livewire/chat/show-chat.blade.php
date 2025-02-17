<div>
    <div class="card">
        <div class="card-header">
            <div class="row mt-2 align-items-center text-center">
                <div class="col-md-auto me-auto">
                    <label>Type:
                        <select class="form-select form-select-sm" style="display: inherit; width: auto" wire:model.change="type">
                            <option value="1">Chat</option>
                            @can('view_chat_pm')
                                <option value="2">PM</option>
                            @endcan
                            @can('view_chat_party')
                                <option value="3">Party</option>
                            @endcan
                            @can('view_chat_staff_chat')
                                <option value="4">Staff Chat</option>
                            @endcan
                            @can('view_chat_admin_chat')
                                <option value="5">Admin Chat</option>
                            @endcan
                            @can('view_chat_friends')
                                <option value="6">Friends</option>
                            @endcan
                        </select>
                    </label>
                </div>
                <div class="col-md-auto">
                    <h5 class="mb-0">
                        <strong>Chat</strong>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            @if ($this->type == 2 || $this->type === 6)
                <livewire:chat-with-receiver-table type="{{ $this->type }}" />
            @else
                <livewire:chat-table type="{{ $this->type }}" />
            @endif
        </div>
    </div>
</div>
