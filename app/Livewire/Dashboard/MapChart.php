<?php

namespace App\Livewire\Dashboard;

use App\Models\Player\Player;
use App\Models\Player\Session;
use App\Models\ServerAnalytic;
use Carbon\Carbon;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class MapChart extends Component
{

    public $data;

    public function mount()
    {
        $this->data = Player::selectRaw('DISTINCT(country) as code, count(*) AS z')
            ->where('firstlogin', '>', Carbon::now()->subDays(60)->getTimestampMs())->groupBy('code')->get();
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
        return view('livewire.dashboard.map-chart');
    }
}
