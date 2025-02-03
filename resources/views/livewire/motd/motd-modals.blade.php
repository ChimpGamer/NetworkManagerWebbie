<!-- Add Motd Modal -->
<div wire:ignore.self class="modal fade" id="addMotdModal" tabindex="-1" aria-labelledby="addMotdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMotdModalLabel">@lang('motd.modal.add.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createMotd'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.add.text-label')</label>
                        <textarea wire:model.live="text" class="form-control"></textarea>
                        @error('text') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.add.hover-description-label')</label>
                        <textarea wire:model.live="description" class="form-control"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.add.custom-version-label')</label>
                        <input type="text" wire:model.live="customversion" class="form-control">
                        @error('customversion') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.add.favicon-label')</label>
                        <input type="text" wire:model.live="faviconUrl" class="form-control">
                        @error('faviconUrl') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.add.expires-label')</label>
                        <input type="datetime-local" wire:model="expires" class="form-control">
                        @error('expires') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.add.maintenance-mode-label')</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="maintenanceModeSwitch"
                                       wire:model.live="maintenance_mode" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="enabledSwitch"><strong>On</strong></label>
                            </div>
                            @error('maintenance_mode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.add.enabled-label')</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="enabledSwitch"
                                       wire:model.live="enabled" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="enabledSwitch"><strong>On</strong></label>
                            </div>
                            @error('enabled') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-dark"
                       @if(!empty($text)) href="https://webui.advntr.dev/?mode=server_list&input={{urlencode($text)}}"
                       target="_blank" rel="noopener noreferrer" @endif>
                        @lang('motd.modal.add.preview-button')
                    </a>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">@lang('motd.modal.close')</button>
                    <button type="submit" class="btn btn-primary">@lang('motd.modal.add.submit-button')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Motd Modal -->
<div wire:ignore.self class="modal fade" id="editMotdModal" tabindex="-1" aria-labelledby="editMotdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMotdModalLabel">@lang('motd.modal.edit.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateMotd'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.edit.text-label')</label>
                        <textarea wire:model.live="text" class="form-control"></textarea>
                        @error('text') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.edit.hover-description-label')</label>
                        <textarea wire:model.live="description" class="form-control"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.edit.custom-version-label')</label>
                        <input type="text" wire:model.live="customversion" class="form-control">
                        @error('customversion') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.edit.favicon-label')</label>
                        <input type="text" wire:model.live="faviconUrl" class="form-control">
                        @error('faviconUrl') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.edit.expires-label')</label>
                        <input type="datetime-local" wire:model="expires" class="form-control">
                        @error('expires') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.edit.maintenance-mode-label')</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="maintenanceModeSwitch"
                                       wire:model.live="maintenance_mode" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="maintenanceModeSwitch"><strong>On</strong></label>
                            </div>
                            @error('maintenance_mode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('motd.modal.edit.enabled-label')</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="enabledSwitch"
                                       wire:model.live="enabled" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="enabledSwitch"><strong>On</strong></label>
                            </div>
                            @error('enabled') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn"
                       @if(!empty($text)) href="https://webui.advntr.dev/?mode=server_list&input={{urlencode($text)}}"
                       target="_blank" rel="noopener noreferrer" @endif>
                        @lang('motd.modal.edit.preview-button')
                    </a>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">@lang('motd.modal.close')</button>
                    <button type="submit" class="btn btn-primary">@lang('motd.modal.edit.submit-button')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Motd Modal -->
<div wire:ignore.self class="modal fade" id="deleteMotdModal" tabindex="-1" aria-labelledby="deleteMotdModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMotdModalLabel">@lang('motd.modal.delete.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>@lang('motd.modal.delete.text', ['motdId' => $motdId])</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">@lang('motd.modal.close')</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">@lang('motd.modal.delete.submit-button')</button>
            </div>
        </div>
    </div>
</div>
