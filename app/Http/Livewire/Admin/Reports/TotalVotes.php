<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Exports\TotalVotesExport;
use Livewire\Component;
use App\Models\Election;
use App\Models\RegisteredMember;
use App\Models\VoidedMember;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Exports\VoterListExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Vote;


class TotalVotes extends Component
{
    public $election;
    public $members;
    public $voting_tellers;

    public function mount()
    {
        $this->voting_tellers = User::where('role_id', 3)->get();
        $this->election = Election::where('is_active', true)->first();
    }

    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function exportReport()
    {

        // $selectedCounter = $this->selectedCounter != null ? User::where('id', $this->selectedCounter)->first()->name : 'All';

        return Excel::download(new TotalVotesExport($this->election, $this->voting_tellers), 'total_votes.xlsx');
    }
    public function render()
    {
        return view('livewire.admin.reports.total-votes');
    }
}
