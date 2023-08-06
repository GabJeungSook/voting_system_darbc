<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;
use App\Models\RegisteredMember;
use App\Models\VoidedMember;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class VoterList extends Component
{

    public $election;
    public $counter;
    public $members;
    public $selectedCounter;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->counter = User::where('role_id', 3)->get();
    }
    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }
    public function render()
    {

        $this->members = RegisteredMember::whereHas('votes')->whereHas('vote.election', function($query){
            $query->where('id', $this->election->id);
        })->when(!empty($this->selectedCounter), function ($query) {
            $query->wherehas('vote.user', function($query){
                $query->where('id', $this->selectedCounter);
            });
        })->with(['registration_duration','vote.user'])->get();


        return view('livewire.admin.reports.voter-list');
    }


}
