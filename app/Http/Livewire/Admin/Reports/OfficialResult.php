<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;
use App\Models\Position;
use App\Exports\OfficialResultExport;
use Maatwebsite\Excel\Facades\Excel;

class OfficialResult extends Component
{
    public $election;
    public $positions;
    public $votes;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->positions = Position::with('candidates')->where('election_id', $this->election->id)->get();
        $this->calculateVoteCounts();
    }

    public function calculateVoteCounts()
    {
        foreach ($this->positions as $position) {
            $position->candidates->each(function ($candidate) {
                $candidate->vote_count = $candidate->votes()->count();
            });
        }
    }

    public function exportReport()
    {
        return Excel::download(new OfficialResultExport($this->positions, $this->election), 'officialResult.xlsx');
    }

    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.admin.reports.official-result',[
            'elections' => Election::get(),
        ]);
    }
}
