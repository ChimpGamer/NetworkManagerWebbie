<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Laravel\Fortify\Actions\ConfirmPassword;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TwoFactorSettings extends Component
{
    public $enabled;
    public $qrCode;
    public $confirming = false;
    public $code = '';
    public $twoFactorSecret = '';

    public function mount(): void
    {
        $user = auth()->user();

        // If user started enabling 2FA but never confirmed it
        if ($user->two_factor_secret && is_null($user->two_factor_confirmed_at)) {
            // Disable it cleanly using Fortify's action
            app(DisableTwoFactorAuthentication::class)($user);
        }

        $this->refreshState();
    }

    public function refreshState(): void
    {
        $user = Auth::user();

        $this->enabled = ! is_null($user->two_factor_secret);
        $this->qrCode = $this->enabled ? $user->twoFactorQrCodeSvg() : null;
        $this->twoFactorSecret = $this->enabled && $this->confirming ? decrypt($user->two_factor_secret) : null;
    }

    public function enable(EnableTwoFactorAuthentication $enable): void
    {
        $enable(request()->user());

        $this->confirming = true;
        $this->refreshState();
    }

    public function confirm(ConfirmTwoFactorAuthentication $confirm): void
    {
        $this->validate([
            'code' => 'required',
        ]);

        $confirm(request()->user(), $this->code);

        $this->confirming = false;
        $this->refreshState();
        $this->closeModal('confirm2faModal');
    }

    public function disable(): void
    {
        Auth::user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->refreshState();
    }

    public function closeModal(?string $modalId = null): void
    {
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    public function render(): Factory|View
    {
        return view('livewire.profile.two-factor-settings');
    }
}
