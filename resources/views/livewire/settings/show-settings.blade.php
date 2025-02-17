<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">@lang('settings.title')</h4>
            </div>
            <div class="card-body">
                <form wire:submit="save">
                    @foreach($settings as $i => $setting)
                        <div class="row" wire:key="setting-field-{{ $setting->variable }}">
                            <label for="setting-label-{{ $setting->variable }}"
                                   class="col-sm-3 form-label">{{ $setting->variable }}</label>
                            <div class="col-sm-9">
                                @can('edit_settings')
                                    @if($setting->isBooleanSetting())
                                        <div class="d-flex">
                                            <strong>Off</strong>
                                            <div class="form-check form-switch ms-2">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="setting-label-{{ $setting->variable }}"
                                                       wire:model.live="settings.{{ $i }}.value"/>
                                                <label class="form-check-label" style="font-weight: bold;"
                                                       for="setting-label-{{ $setting->variable }}"><strong>On</strong></label>
                                            </div>
                                        </div>
                                    @else
                                        <input id="setting-label-{{ $setting->variable }}" class="form-control"
                                               type="text" wire:model="settings.{{ $i }}.value">
                                    @endif
                                @else
                                    <span>{{ $setting->value }}</span>
                                @endcan
                            </div>
                        </div>
                        <div class="border-bottom my-3 border-gray-200"></div>
                    @endforeach

                    @can('edit_settings')
                        <button type="submit" class="btn btn-primary">@lang('settings.buttons.save')</button>
                    @endcan
                    <div wire:dirty.class="is-dirty"></div>
                </form>
            </div>
        </div>
    </div>

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
</div>

@script
<script>
    window.onbeforeunload = function (e) {
        let dirtyElements = document.getElementsByClassName('is-dirty');
        if (dirtyElements.length > 0) {
            return true;
        }
    };
</script>
@endscript
