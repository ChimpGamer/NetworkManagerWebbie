<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;

class ShowChangePassword extends Component
{
    public ?string $oldPassword;

    public ?string $password;

    public ?string $password_confirmation;

    public function rules()
    {
        return [
            'oldPassword' => 'required|string',
            'password' => 'required|string|confirmed',
        ];
    }

    public function updatePassword()
    {
        $validatedData = $this->validate();

        if (! Hash::check($validatedData['oldPassword'], auth()->user()->getAuthPassword())) {
            return back()->with('error', 'Old Password Does not match!');
        }

        User::whereId(auth()->user()->id)->update(['password' => Hash::make($validatedData['password'])]);

        $this->resetInput();

        return back()->with('message', 'Successfully Changed Password');
    }

    private function resetInput()
    {
        $this->oldPassword = null;
        $this->password = null;
        $this->password_confirmation = null;
    }

    public function render(): View
    {
        return view('livewire.profile.show-change-password');
    }
}
