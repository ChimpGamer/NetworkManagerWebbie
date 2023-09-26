<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Settings</h4>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    @foreach($settings as $i => $setting)
                        <div class="row" wire:key="setting-field-{{ $setting->variable }}">
                            <label for="setting-label-{{ $setting->variable }}" class="col-sm-3 form-label">{{ $setting->variable }}</label>
                            <div class="col-sm-9">
                                {{--@if($setting->isBooleanSetting())
                                    <div class="d-flex">
                                        <strong>Off</strong>
                                        <div class="form-check form-switch ms-2">
                                            <input id="setting-label-{{ $setting->variable }}" class="form-check-input" type="checkbox" role="switch"
                                                   wire:model.defer="settings.{{ $i }}.value" />
                                            <label class="form-check-label" style="font-weight: bold;"
                                                   for="setting-label-{{ $setting->variable }}"><strong>On</strong></label>
                                        </div>
                                    </div>
                                @else
                                    <input id="setting-label-{{ $setting->variable }}" class="form-control" type="text" wire:model.defer="settings.{{ $i }}.value">
                                @endif--}}
                                <input id="setting-label-{{ $setting->variable }}" class="form-control" type="text" wire:model.defer="settings.{{ $i }}.value">
                            </div>
                        </div>
                        <div class="border-bottom my-3 border-gray-200"></div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
</div>
