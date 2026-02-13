<div>
    <x-modal id="enable2faModal" title="Enable Two-Factor Authentication">
        <p>Are you sure you want to enable two-factor authentication?</p>
        <x-slot name="footer">
            <button class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" wire:click="enable" data-mdb-dismiss="modal" data-mdb-modal-init data-mdb-target="#confirm2faModal">Enable</button>
        </x-slot>
    </x-modal>

    <x-modal id="confirm2faModal" title="Confirm Two-Factor Authentication">
        <p>Scan the QR code with your authenticator app, then enter the generated code. Alternatively, you may use the following setup code:</p>
        <p>{{ $twoFactorSecret }}</p>

        <div class="mb-3">
            {!! $qrCode !!}
        </div>

        <div class="mb-3">
            <label class="bold">2FA Code</label>
            <input type="text" class="form-control" placeholder="123456" wire:model="code">
            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <x-slot name="footer">
            <button class="btn btn-primary" wire:click="confirm">Confirm</button>
        </x-slot>
    </x-modal>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Two-Factor Authentication</h5>
        </div>

        <div class="card-body">

            @if (! $enabled)
                <p class="text-muted">Two-factor authentication adds an extra layer of security to your account.</p>

                <button class="btn btn-primary" data-mdb-modal-init data-mdb-target="#enable2faModal">
                    Enable Two-Factor Authentication
                </button>
            @else
                <div class="mb-3">
                    <strong>Status:</strong> <span class="text-success">Enabled</span>
                </div>

                <button class="btn btn-danger" wire:click="disable">
                    Disable Two-Factor Authentication
                </button>
            @endif
        </div>
    </div>
</div>
