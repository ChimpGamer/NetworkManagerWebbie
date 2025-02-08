<?php

namespace App\Livewire;

use App\Models\Value;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class ShowSettings extends Component
{
    use AuthorizesRequests;

    public string $search = '';

    public $settings;

    protected function rules(): array
    {
        return [
            'settings.*.variable' => 'required|string',
            'settings.*.value' => '',
        ];
    }

    public function mount(): void
    {
        $settings = Value::select('variable', 'value')
            ->where('variable', 'like', '%'.$this->search.'%')
            ->orderBy('variable')
            ->get();

        $this->settings = $settings->map(function ($value) {
            if ($value->isBooleanSetting()) {
                if ($value->value == '1') {
                    $value->value = true;
                } elseif ($value->value == '0') {
                    $value->value = false;
                }
            }

            return $value;
        });
    }

    public function save(): void
    {
        $this->authorize('edit_settings');
        $this->validate();

        $changedSettings = $this->settings->map(function ($value) {
            if ($value->isBooleanSetting()) {
                if ($value->value) {
                    $value->value = '1';
                } else {
                    $value->value = '0';
                }
            }

            return $value;
        })->filter(function ($value) {
            return $value->isDirty('value');
        })->values();
        if ($changedSettings->isEmpty()) {
            return;
        }
        foreach ($changedSettings as $setting) {
            $setting->save();
        }
        $message = 'Successfully updated the following setting(s): '.$changedSettings->implode('variable', ', ');
        session()->flash('message', $message);
    }

    public function render(): View
    {
        return view('livewire.settings.show-settings')->with('settings', $this->settings);
    }
}
