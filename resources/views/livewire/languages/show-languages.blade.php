<div>
    @include('livewire.languages.language-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session()->has('warning-message'))
        <h5 class="alert alert-warning">{{ session('warning-message')  }}</h5>
    @endif

    <h5 class="alert alert-info"><i class="fa-solid fa-circle-info"></i> Note: These languages and language message are
        all in-game.</h5>

    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Languages</strong>
            </h5>

            <div class="float-end d-inline" wire:ignore>
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="languageSearch" class="form-control" wire:model.live="search"/>
                    <label class="form-label" for="languageSearch"
                           style="font-family: Roboto, 'FontAwesome'">Search...</label>
                </div>
            </div>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="languagesTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($languages as $language)
                    <tr>
                        <td>{{ $language->id }}</td>
                        <td>{{ $language->name }}</td>
                        <th>
                            @can('edit_languages')
                                <a type="button" style="background: transparent; border: none;" href="/languages/{{$language->id}}">
                                    <i class="material-icons text-warning">edit</i>
                                </a>
                                <button type="button" style="background: transparent; border: none;"
                                        @if(!$this->isProtectedLanguage($language))
                                            data-mdb-ripple-init data-mdb-modal-init
                                            data-mdb-target="#deleteLanguageModal"
                                        @endif
                                        wire:click="deleteLanguage({{ $language->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $languages->links() }}
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addLanguageModal"
                wire:click="addLanguage">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Language
        </button>
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addLanguageMessageModal"
                wire:click="addLanguage">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Language Message
        </button>
    </div>
</div>
