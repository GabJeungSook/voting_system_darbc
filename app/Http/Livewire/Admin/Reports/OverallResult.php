<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Exports\OverallResultExport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportReport()
    {
        return Excel::download(new OverallResultExport($this->positions, $this->election, $this->selectedCounter), 'overAllResult.xlsx');
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
    //     $this->positions = Position::when(!empty($this->selectedCounter), function ($query) {
    //         $query->where('user_id', $this->selectedCounter);
    // })->with(['candidates'=>function($query){
    //     $query->withCount();
    // }])->get();
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
