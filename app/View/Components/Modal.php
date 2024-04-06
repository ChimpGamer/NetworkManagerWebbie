<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $title;
    public $footer;
    public $hasForm;

    public function __construct($id, $title, $hasForm = false)
    {
        $this->id = $id;
        $this->title = $title;
        $this->hasForm = $hasForm;
    }

    public function render()
    {
        return view('components.modal');
    }
}