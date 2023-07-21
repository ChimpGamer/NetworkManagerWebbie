<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header h5">
                    Settings
                    <label for="settingsSearch" class="float-end mx-2">
                        <input id="settingsSearch" type="search" wire:model="search" class="form-control"
                               placeholder="Search..." />
                    </label>
                </div>
                <div class="card-body border-0 shadow table-responsive">
                    <table class="table table-hover text-nowrap">
                        <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <th>{{$setting->variable}}</th>
                                <td>{{$setting->value}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
