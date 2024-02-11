<?php

namespace App\Http\Livewire\Registration;

use App\Models\Member;
use App\Models\RegisteredMember;
use Livewire\Component;

class Report extends Component
{
    public $report_get;
    public $members;
    public $reg_members;

    public function render()
    {
        $query = Member::where(function ($query) {
            $query->whereNotNull('restriction')
                  ->orWhere('restriction', '!=', '');
        })->where(function ($query) {
            $query->where('restriction', '!=', '')
                  ->orWhere(function ($query) {
                      $query->where('restriction', '!=', null)
                            ->where('restriction', '!=', '')
                            ->where('restriction', '!=', ' ');
                  });
        });

        $this->members = $query->get();
        $this->reg_members = RegisteredMember::where('user_id', auth()->user()->id)->get();

        return view('livewire.registration.report', [
            'restricted_members' => $this->report_get != 1 ? [] : ($this->members->isEmpty() ? [] : $this->members),
            'registered_members' => $this->report_get != 2 ? [] : $this->reg_members,
        ]);
    }
}
