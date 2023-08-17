<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Language {{$language->name}}</h4>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    @foreach($messages as $i => $message)
                        <div class="row" wire:key="message-field-{{ $message->key }}">
                            <label for="message-label-{{ $message->key }}" class="col-sm-3 form-label">{{ $message->key }}</label>
                            <div class="col-sm-9">
                                <input id="message-label-{{ $message->key }}" class="form-control" type="text" wire:model.defer="languageMessages.{{ $i }}.message">
                            </div>
                        </div>
                        <div class="border-bottom my-3 border-gray-200"></div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
