<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;
use App\Models\VoidedMember;
use App\Models\User;
use App\Exports\VoidedVoteExport;
use Maatwebsite\Excel\Facades\Excel;

class VoidedVotes extends Component
{
    public $election;
    public $counter;
    public $members;
    public $selectedCounter;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->counter = User::where('role_id', 2)->get();
    }

    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function exportReport()
    {
        return Excel::download(new VoidedVoteExport($this->members, $this->election, $this->selectedCounter), 'voidedVotes.xlsx');
    }

    public function render()
    {
        $this->members = VoidedMember::where('type', 'VOTING')->when(!empty($this->selectedCounter), function ($query) {
            $query->where('user_id', $this->selectedCounter);
        })->get();

        return view('livewire.admin.reports.voided-votes');
    }
}
