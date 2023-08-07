<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;

class ShowChangePassword extends Component
{
    public ?string $oldPassword;
    public ?string $newPassword;

    public function rules()
    {
        return [
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string',
        ];
    }

    public function updatePassword()
    {
        $validatedData = $this->validate();

        if (!Hash::check($validatedData['oldPassword'], auth()->user()->getAuthPassword())) {
            return back()->with('error', 'Old Password Does not match!');
        }

        User::whereId(auth()->user()->id)->update(['password' => Hash::make($validatedData['newPassword'])]);

        $this->resetInput();
        return back()->with('message', 'Successfully Changed Password');
    }

    private function resetInput()
    {
        $this->oldPassword = null;
        $this->newPassword = null;
    }

    public function render(): View
    {
        return view('livewire.profile.show-change-password');
    }
}
