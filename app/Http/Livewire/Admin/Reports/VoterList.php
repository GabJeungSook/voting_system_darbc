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
    public $voided_members;
    public $selectedCounter;
    public $counterName;
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
        $selectedCounter = $this->selectedCounter != null ? User::where('id', $this->selectedCounter)->first()->name : 'All';

        return Excel::download(new VoterListExport($this->members, $this->election, $this->selectedCounter), 'voterList_'.$selectedCounter.'.xlsx');
    }


    public function render()
    {
        $this->members = RegisteredMember::whereHas('votes')
        ->whereHas('vote.election', function ($query) {
            $query->where('id', $this->election->id);
        })
        ->when(!empty($this->selectedCounter), function ($query) {
            $query->whereHas('vote.user', function ($query) {
                $query->where('id', $this->selectedCounter);
            });
        })
        ->when(!empty($this->member_name), function ($query) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->member_name . '%')
                    ->orWhere('last_name', 'like', '%' . $this->member_name . '%');
            });
        })
        ->with(['registration_duration', 'vote.user'])
        ->get();

        $this->voided_members = VoidedMember::where('type', 'VOTING')->when(!empty($this->selectedCounter), function ($query) {
            $query->where('user_id', $this->selectedCounter);
        })->get();


        // $this->members = RegisteredMember::whereHas('votes')->whereHas('vote.election', function($query){
        //     $query->where('id', $this->election->id);
        // })->when(!empty($this->selectedCounter), function ($query) {
        //     $query->wherehas('vote.user', function($query){
        //         $query->where('id', $this->selectedCounter);
        //     });
        // })
        // ->with(['registration_duration','vote.user'])->get();


        return view('livewire.admin.reports.voter-list');
    }


}
