<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row mt-2 align-items-center text-center">
                    <div class="col-md-auto me-auto">
                        <h4 class="mb-0">Language {{$language->name}}</h4>
                    </div>

                    <div class="col-md-auto" x-init>
                        <div class="col-md-auto ms-auto" wire:ignore x-data="{
                            handleKeydown(e) {
                                // 'cmd+k'
                                if (e.keyCode == 75 && (e.metaKey == true || e.ctrlKey == true)) {
                                    document.getElementById('languageMessageSearch').focus();
                                    e.preventDefault()
                                }

                                // 'esc'
                                if (e.keyCode == 27) {
                                    document.getElementById('languageMessageSearch').blur();
                                    e.preventDefault()
                                }
                            }
                        }">
                            <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                                <input type="search" id="languageMessageSearch" class="form-control form-control-sm" @keydown.window="handleKeydown" wire:model.live="search"/>
                                <label class="form-label" for="languageMessageSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form wire:submit="save">
                    @foreach($messages as $i => $message)
                        <div class="row" wire:key="message-field-{{ $message->key }}">
                            <label for="message-label-{{ $message->key }}" class="col-sm-3 form-label">{{ $message->key }}</label>
                            <div class="col-sm-9">
                                <input id="message-label-{{ $message->key }}" class="form-control" type="text" wire:model="languageMessages.{{ $i }}.message">
                            </div>
                        </div>
                        <div class="border-bottom my-3 border-gray-200"></div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
</div>
