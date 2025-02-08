<?php

namespace App\Livewire\Analytics;

use App\Helpers\CountryUtils;
use App\Models\Player\Player;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class PlayerRegionsChart extends Component
{
    #[Computed]
    public function mapData()
    {
        return Player::selectRaw('DISTINCT(country) as code, count(*) AS z')->groupBy('country')->get();
    }

    #[Computed]
    public function countriesData()
    {
        return Player::selectRaw('DISTINCT(country) as country, count(*) AS count')->groupBy('country')->orderBy('count', 'DESC')->limit(15)->get();
    }

    #[Computed]
    public function countryNames()
    {
        return $this->countriesData()->map(function ($item) {
            return CountryUtils::countryCodeToCountry($item['country']);
        });
    }

    #[Computed]
    public function countryPlayers()
    {
        return $this->countriesData()->map(function ($item) {
            return $item['count'];
        });
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
        return view('livewire.analytics.player-regions-chart');
    }
}
