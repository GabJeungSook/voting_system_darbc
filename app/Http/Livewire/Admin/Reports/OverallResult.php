<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;

class OverallResult extends Component
{
    public $election;
    public $positions;
    public $votes;
    public $counter;
    public $selectedCounter;

    public function mount()
    {
        $this->positions = Position::with('candidates')->get();
        $this->election = Election::where('is_active', true)->first();
        $this->counter = User::where('role_id', 3)->get();
        $this->calculateVoteCounts();
    }

    public function calculateVoteCounts()
    {
        foreach ($this->positions as $position) {
            $position->candidates->each(function ($candidate) {
                $candidate->vote_count = $candidate->votes()->when(!empty($this->selectedCounter), function ($query) {
                        $query->where('user_id', $this->selectedCounter);
                })->count();
            });
        }
    }

    public function updatedSelectedCounter()
    {
        $this->calculateVoteCounts();
    }

    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.admin.reports.overall-result');
    }
}
