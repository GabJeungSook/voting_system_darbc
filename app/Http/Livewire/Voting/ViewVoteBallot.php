<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use App\Models\Position;
use App\Models\Election;
use App\Models\Vote;

class ViewVoteBallot extends Component
{
    public $record;
    public $positions;
    public $election;
    public $votes;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->positions = Position::where('election_id', $this->election->id)->get();
        $this->votes = $this->record->votes()->get();
    }

    public function returnToVotingIndex()
    {
        return redirect()->route('admin.members');
    }

    public function render()
    {
        return view('livewire.voting.view-vote-ballot');
    }
}
