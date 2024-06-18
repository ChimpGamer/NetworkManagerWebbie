<?php

namespace App\Livewire\Analytics;

use App\Models\Player\Player;
use App\Models\Player\Session;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class AverageStats extends Component
{
    #[Computed]
    public function mnp()
    {
        return Player::where('firstlogin', '>=', Carbon::now()->subDays(30)->getTimestampMs())->count();
    }

    #[Computed]
    public function wnp()
    {
        return Player::where('firstlogin', '>=', Carbon::now()->subWeeks()->getTimestampMs())->count();
    }

    #[Computed]
    public function dnp()
    {
        return Player::where('firstlogin', '>=', Carbon::now()->subDays()->getTimestampMs())->count();
    }

    #[Computed]
    public function mrp()
    {
        return Session::where('start', '>=', Carbon::now()->subDays(30)->getTimestampMs())->distinct()->count('uuid');
    }

    #[Computed]
    public function wrp()
    {
        return Session::where('start', '>=', Carbon::now()->subWeeks()->getTimestampMs())->distinct()->count('uuid');
    }

    #[Computed]
    public function drp()
    {
        return Session::where('start', '>=', Carbon::now()->subDays()->getTimestampMs())->distinct()->count('uuid');
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
        return view('livewire.analytics.average-stats');
    }
}
