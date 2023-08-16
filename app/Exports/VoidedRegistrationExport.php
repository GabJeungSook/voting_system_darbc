<?php

namespace App\Exports;

use App\Models\VoidedMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class VoidedRegistrationExport implements FromView
{
    public $election;
    public $members;
    public $selectedCounter;

    public function __construct($members, $election, $selectedCounter)
    {
        $this->members = $members;
        $this->election = $election;
        $this->selectedCounter = $selectedCounter;
    }

      public function view(): View
    {
        return view('exports.voided-registrations', [
            'members' => $this->members,
            'election' => $this->election,
        ]);
    }
}
