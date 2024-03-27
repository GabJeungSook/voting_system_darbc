<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Support\Facades\Storage;

class LiveResult extends Component
{
    public $election;
    public $positions;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->positions = Position::where('election_id', $this->election->id)->get();
    }


    public function getFileUrl($filename)
    {
        return Storage::url($filename);
    }

    public function render()
    {
        return view('livewire.admin.live-result');
    }
}
