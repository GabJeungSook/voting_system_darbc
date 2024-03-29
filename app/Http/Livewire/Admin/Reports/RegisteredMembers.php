<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;
use App\Models\RegisteredMember;
use App\Models\User;
use App\Exports\RegisteredMembersExport;
use Maatwebsite\Excel\Facades\Excel;

class RegisteredMembers extends Component
{
    public $election;
    public $counter;
    public $members;
    public $selectedCounter;
    public $counterName;
    public $selectedDate;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->counter = User::where('role_id', 2)->get();
    }

    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function updatedSelectedCounter()
    {
        if($this->selectedCounter == null){
            $this->counterName = null;
            return;
        }else{

            $this->counterName = User::find($this->selectedCounter)->name;
        }
    }


    public function exportReport()
    {
        return Excel::download(new RegisteredMembersExport($this->members, $this->election, $this->selectedCounter), 'registeredMembers.xlsx');
    }

    public function render()
    {
        $this->members = RegisteredMember::where('election_id', $this->election->id)
        ->when(!empty($this->selectedCounter), function ($query) {
            $query->where('user_id', $this->selectedCounter);
        }) ->when(!empty($this->selectedDate), function ($query) {
            $query->whereDate('created_at', $this->selectedDate);
        })->with(['member'])->get();

        return view('livewire.admin.reports.registered-members');
    }
}
