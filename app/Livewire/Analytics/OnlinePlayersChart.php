<?php

namespace App\Livewire\Analytics;

use App\Models\ServerAnalytic;
use Carbon\Carbon;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class OnlinePlayersChart extends Component
{
    public $data;

    public function mount()
    {
        $this->data = ServerAnalytic::select('TIME', 'ONLINE')->where('TIME', '>', Carbon::now()->subDays(30)->getTimestampMs())
            ->orderBy('TIME')
            ->get()->map(function (ServerAnalytic $serverAnalytic) {
                return array_values($serverAnalytic->attributesToArray()); // Remove keys (no idea if there is a better way)
            })/*->toJson()*/;
        /*dd($this->data);*/
    }

    public function placeholder()
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

    public function render()
    {
        return view('livewire.analytics.online-players-chart');
    }
}
