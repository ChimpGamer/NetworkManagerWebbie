<?php

namespace App\Livewire;

use App\Models\HelpOP;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowHelpOP extends Component
{
    use AuthorizesRequests;

    public int $helpOPId;

    #[On('delete')]
    public function deleteHelpOP($rowId): void
    {
        $helpOP = HelpOP::find($rowId);
        if ($helpOP == null) {
            session()->flash('error', 'HelpOP #'.$rowId.' not found');

            return;
        }
        $this->helpOPId = $helpOP->id;
    }

    public function delete(): void
    {
        $this->authorize('edit_helpop');
        HelpOP::find($this->helpOPId)->delete();
        $this->resetInput();
        $this->refreshTable();
    }

    public function resetInput(): void
    {
        $this->helpOPId = -1;
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-helpop-table');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.helpop.show-helpop');
    }
}
