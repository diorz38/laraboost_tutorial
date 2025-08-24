<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Manage extends Component
{
    public $tab = 'users';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.manage', [
            'tab' => $this->tab,
        ]);
    }
}
