<div>
    @include('livewire.punishment_templates.punishment-template-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 justify-content-between text-center">
                <div class="col-md-auto me-auto">
                    <label>Show
                        <select class="form-select form-select-sm" style="display: inherit; width: auto" wire:model.live="per_page">
                            <option value=10>10</option>
                            <option value=25>25</option>
                            <option value=50>50</option>
                            <option value=100>100</option>
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-md-auto">
                    <h5 class="mb-0 text-center">
                        <strong>Punishment Templates</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="punishmentTemplateSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="punishmentTemplateSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="punishmentTemplatesTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Server</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($punishmentTemplates as $template)
                    <tr>
                        <td>{{ $template->id }}</td>
                        <td>{{ $template->name }}</td>
                        <td>{{ $template->type->name() }}</td>
                        <td>
                            @if($template->duration == -1)
                                Permanent
                            @else
                                {{ $template->duration / 1000 }}
                            @endif
                        </td>
                        <td>
                            @if($template->server == null)
                                Global
                            @else()
                                {{ $template->server }}
                            @endif
                        </td>
                        <td>{!! $template->reason !!}</td>
                        <th>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showPunishmentTemplateModal"
                                    wire:click="showPunishmentTemplate({{ $template->id }})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_pre_punishments')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#editTemplateModal"
                                        wire:click="editTemplate({{$template->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#deletePunishmentTemplateModal"
                                        wire:click="deletePunishmentTemplate({{$template->id}})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $punishmentTemplates->links() }}
        </div>
    </div>
    @can('edit_pre_punishments')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addTemplateModal"
                    wire:click="addTemplate">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Template
            </button>
        </div>
    @endcan
</div>
