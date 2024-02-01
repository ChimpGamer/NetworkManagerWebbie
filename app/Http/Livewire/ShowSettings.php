<?php

namespace App\Http\Livewire;

use App\Models\Value;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class ShowSettings extends Component
{
    use AuthorizesRequests;

    public string $search = '';

    public $settings;

    public $boolean = true;

    protected function rules()
    {
        return [
            'settings.*.variable' => 'required|string',
            'settings.*.value' => '',
        ];
    }

    public function mount()
    {
        $this->settings = Value::select('variable', 'value')
            ->where('variable', 'like', '%'.$this->search.'%')
            ->orderBy('variable')
            ->get();
    }

    public function save()
    {
        $this->authorize('edit_settings');
        $this->validate();

        $changedSettings = $this->settings->filter(function ($value) {
            return $value->isDirty('value');
        })->values();
        /*$changedSettings = $changedSettings->map(function ($value) {
            if ($value->value) {
                $value->value = 1;
            } else {
                $value->value = 0;
            }
            return $value;
        });*/
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
