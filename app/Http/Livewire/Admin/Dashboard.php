<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard');
    }

    public function redirectToLiveResult()
    {
        return redirect()->route('admin.live-result');
    }
}
