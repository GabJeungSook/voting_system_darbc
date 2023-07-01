<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\RegisteredMember;
use App\Models\Election;
use App\Models\User;
use App\Models\Vote;

class Dashboard extends Component
{
    public $tellers;
    public $voting_tellers;
    public $election;
    public $total_registered_members;
    public $total_voters;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->tellers = User::where('role_id', 2)->get();
        $this->voting_tellers = User::where('role_id', 3)->get();
        $this->total_registered_members = RegisteredMember::where('election_id', $this->election?->id)->count();
        $this->total_voters = Vote::where('election_id', $this->election->id)->count();
    }

    public function redirectToLiveResult()
    {
        return redirect()->route('admin.live-result');
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
