<?php

namespace App\Livewire\Dashboard;

use App\Models\Player\Player;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class MapChart extends Component
{

    public $data;

    public function mount(): void
    {
        $this->data = Player::selectRaw('DISTINCT(country) as code, count(*) AS z')
            ->where('firstlogin', '>', Carbon::now()->subDays(60)->getTimestampMs())->groupBy('code')->get();
    }

    public function placeholder(): string
    {
        return <<<'HTML'
            <div>
                <div class="w-100 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        HTML;
    }

    public function render(): View
    {
        return view('livewire.dashboard.map-chart');
    }
}
