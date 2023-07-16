<?php

namespace App\Http\Livewire\Registration;

use App\Models\Member;
use Livewire\Component;

class Report extends Component
{
    public $report_get;
    public $members;

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

        return view('livewire.registration.report', [
            'restricted_members' => $this->report_get != 1 ? [] : ($this->members->isEmpty() ? [] : $this->members),
        ]);
    }
}
