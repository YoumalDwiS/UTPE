<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonSyncronize extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $text;
    public $id;
    public function __construct($text = "", $id = "btn-sync")
    {
        $this->text = $text;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button-syncronize');
    }
}
