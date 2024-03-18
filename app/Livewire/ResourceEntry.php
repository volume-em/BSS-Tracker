<?php

namespace App\Livewire;

use Livewire\Component;

class ResourceEntry extends Component
{
    public $model;
    public $entries;

    public function render()
    {
        return view('livewire.resource-entry');
    }

    public function load_investigator($id)
    {
        return view('livewire.resource-entry');
    }

    public function load_project($id)
    {
        dd($id);
    }

    public function load_biosample($id)
    {
        dd($id);
    }

    public function load_sample($id)
    {
        dd($id);
    }

    public function load_specimen($id)
    {
        dd($id);
    }
}
