<?php

namespace App\Livewire\Accounts;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowAccounts extends Component
{
    use AuthorizesRequests;

    public int $user_id = -1;

    public string $username = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $user_group = '';

    protected function rules()
    {
        return [
            'username' => 'required|string|min:4',
            'password' => 'required|string|confirmed|min:8',
            'user_group' => 'required|string|exists:App\Models\Group,name',
        ];
    }

    protected array $editRules = [
        'username' => 'required|string|min:4',
        'user_group' => 'required|string|exists:App\Models\Group,name',
    ];

    #[Computed]
    public function allUserGroups(): Collection
    {
        return Group::all();
    }

    public function addAccount()
    {
        $this->authorize('manage_groups_and_accounts');
        $this->resetInput();
    }

    public function createAccount()
    {
        $this->authorize('manage_groups_and_accounts');
        $validatedData = $this->validate();
        $username = $validatedData['username'];
        $password = $validatedData['password'];
        $userGroup = $validatedData['user_group'];

        // No need to manually hash password
        User::create([
            'username' => $username,
            'password' => $password,
            'usergroup' => $userGroup,
            'notifications' => [],
        ]);

        session()->flash('message', 'Successfully Added Account');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function editAccount(User $user)
    {
        $this->authorize('manage_groups_and_accounts');
        $this->resetInput();

        $this->user_id = $user->id;
        $this->username = $user->username;
        $this->user_group = $user->usergroup;
    }

    public function updateAccount()
    {
        $this->authorize('manage_groups_and_accounts');
        $validatedData = $this->validate($this->editRules);
        $username = $validatedData['username'];
        $userGroup = $validatedData['user_group'];

        User::where('id', $this->user_id)->update([
            'username' => $username,
            'usergroup' => $userGroup,
        ]);

        session()->flash('message', 'Account Updated Successfully');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deleteAccount(User $user)
    {
        $this->authorize('manage_groups_and_accounts');
        $this->user_id = $user->id;
        $this->username = $user->username;
    }

    public function delete()
    {
        $this->authorize('manage_groups_and_accounts');
        User::find($this->user_id)->delete();

        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->reset(
            'user_id',
            'username',
            'password',
            'password_confirmation',
            'user_group'
        );
    }

    public function render()
    {
        $users = User::all();

        return view('livewire.accounts.show-accounts', ['accounts' => $users]);
    }
}
