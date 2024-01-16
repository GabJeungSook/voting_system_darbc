<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;
use App\Models\RegisteredMember;
use App\Models\VoidedMember;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Exports\VoterListExport;
use Maatwebsite\Excel\Facades\Excel;

class VoterList extends Component
{

    public $election;
    public $counter;
    public $members;
    public $selectedCounter;
    public $member_name;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->counter = User::where('role_id', 3)->get();
    }

    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function exportReport()
    {
        $selectedCounter = $this->selectedCounter != null ? User::where('id', $this->selectedCounter)->first()->name : 'All';

        return Excel::download(new VoterListExport($this->members, $this->election, $this->selectedCounter), 'voterList_'.$selectedCounter.'.xlsx');
    }


    public function render()
    {

        $this->members = RegisteredMember::whereHas('votes')->whereHas('vote.election', function($query){
            $query->where('id', $this->election->id);
        })->when(!empty($this->selectedCounter), function ($query) {
            $query->wherehas('vote.user', function($query){
                $query->where('id', $this->selectedCounter);
            });
        })
        ->with(['registration_duration','vote.user'])->get();


        return view('livewire.admin.reports.voter-list');
    }


}
