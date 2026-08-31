<?php

namespace App\Livewire\Analytics;

use App\Models\ServerAnalytic;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class OnlinePlayersChart extends Component
{
    #[Computed]
    public function data(): array
    {
        return ServerAnalytic::query()
            ->where('TIME', '>', now()->subDays(30)->getTimestampMs())
            ->orderBy('TIME')
            ->get(['TIME', 'ONLINE'])
            ->map(fn ($analytic) => [$analytic->TIME, $analytic->ONLINE])
            ->all();
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
        return view('livewire.analytics.online-players-chart');
    }
}
