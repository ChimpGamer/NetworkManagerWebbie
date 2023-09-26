<div>
    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="row gy-4">
        <div class="col-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="mb-0 text-center">
                        <strong>Change Password</strong>
                    </h5>
                </div>
                <form wire:submit.prevent="updatePassword">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="oldPassword" class="bold">Old Password</label>
                            <input id="oldPassword" type="password" wire:model="oldPassword" class="form-control">
                            @error('oldPassword') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="bold">New Password</label>
                            <input id="newPassword" type="password" wire:model="password" class="form-control">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="newPasswordConfirmation" class="bold">Confirm New Password</label>
                            <input id="newPasswordConfirmation" type="password" wire:model="password_confirmation" class="form-control">
                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
