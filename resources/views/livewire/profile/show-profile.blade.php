<div>
    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="row gy-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="mb-0 text-center">
                        <strong>@lang('profile.change-password.title')</strong>
                    </h5>
                </div>
                <form wire:submit="updatePassword">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="oldPassword" class="bold">@lang('profile.change-password.old-password')</label>
                            <input id="oldPassword" type="password" wire:model="oldPassword" class="form-control">
                            @error('oldPassword') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="bold">@lang('profile.change-password.new-password')</label>
                            <input id="newPassword" type="password" wire:model="password" class="form-control">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="newPasswordConfirmation"
                                   class="bold">@lang('profile.change-password.confirm-new-password')</label>
                            <input id="newPasswordConfirmation" type="password" wire:model="password_confirmation"
                                   class="form-control">
                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit"
                                class="btn btn-primary">@lang('profile.change-password.buttons.save')</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            @livewire('profile.two-factor-settings')
        </div>
    </div>
</div>
