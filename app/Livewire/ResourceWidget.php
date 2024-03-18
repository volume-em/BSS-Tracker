<?php

namespace App\Livewire;

use Livewire\Component;

class ResourceWidget extends Component
{
    public $model;
    public bool $shouldLoad = false;

    public function render()
    {
        $entries = [];

        if ($this->shouldLoad) {
            $entries = $this->model::all();
        }

        return view('livewire.resource-widget', ['entries' => $entries]);
    }

    public function load($model = null)
    {
        if (! is_null($model)) {
            $this->model = $model;
        }

        $this->shouldLoad = true;
    }
}
