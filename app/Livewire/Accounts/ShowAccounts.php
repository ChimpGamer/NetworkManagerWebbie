<?php

namespace App\Livewire\Accounts;

use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowAccounts extends Component
{
    use AuthorizesRequests;

    public int $user_id = -1;

    public string $username = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $user_group = '';

    public string $last_login = '0';

    public bool $is_active = false;

    protected function rules(): array
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
        'is_active' => 'required|boolean',
    ];

    #[Computed]
    public function allUserGroups(): Collection
    {
        return Group::all();
    }

    public function addAccount(): void
    {
        $this->authorize('manage_groups_and_accounts');
        $this->resetInput();
    }

    public function createAccount(): void
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
        $this->closeModal('addAccountModal');
    }

    #[On('info')]
    public function showAccount($rowId): void
    {
        $this->resetInput();
        $user = User::find($rowId);
        if ($user == null) {
            session()->flash('error', 'Account $'.$rowId.' not found');

            return;
        }

        $this->user_id = $user->id;
        $this->username = $user->username;
        $this->user_group = $user->usergroup;
        $this->last_login = $user->last_login;
        $this->is_active = $user->is_active;
    }

    #[On('edit')]
    public function editAccount($rowId): void
    {
        $this->resetInput();
        $user = User::find($rowId);
        if ($user == null) {
            session()->flash('error', 'Account $'.$rowId.' not found');

            return;
        }

        $this->user_id = $user->id;
        $this->username = $user->username;
        $this->user_group = $user->usergroup;
        $this->is_active = $user->is_active;
    }

    public function updateAccount(): void
    {
        $this->authorize('manage_groups_and_accounts');
        $validatedData = $this->validate($this->editRules);
        $username = $validatedData['username'];
        $userGroup = $validatedData['user_group'];

        User::where('id', $this->user_id)->update([
            'username' => $username,
            'usergroup' => $userGroup,
            'is_active' => $validatedData['is_active'],
        ]);

        $this->refreshTable();
        session()->flash('message', 'Account Updated Successfully');
        $this->closeModal('editAccountModal');
    }

    #[On('delete')]
    public function deleteAccount($rowId): void
    {
        $user = User::find($rowId);
        if ($user == null) {
            session()->flash('error', 'Account $'.$rowId.' not found');

            return;
        }
        $this->user_id = $user->id;
        $this->username = $user->username;
    }

    public function delete(): void
    {
        $this->authorize('manage_groups_and_accounts');
        User::find($this->user_id)->delete();

        $this->resetInput();
        $this->refreshTable();
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput(): void
    {
        $this->reset(
            'user_id',
            'username',
            'password',
            'password_confirmation',
            'user_group'
        );
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-accounts-table');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.accounts.show-accounts');
    }
}
