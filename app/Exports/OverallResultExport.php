<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OverallResultExport implements FromView
{
    public $positions;
    public $election;
    public $selectedCounter;

    public function __construct($positions, $election, $selectedCounter)
    {
        $this->positions = $positions;
        $this->election = $election;
        $this->selectedCounter = $selectedCounter;

        foreach ($this->positions as $position) {
            $position->candidates->each(function ($candidate) {
                $candidate->vote_count = $candidate->votes()->when(!empty($this->selectedCounter), function ($query) {
                        $query->where('user_id', $this->selectedCounter);
                })->count();
            });
        }
    }

      public function view(): View
    {
        return view('exports.overall-result', [
            'positions' => $this->positions,
            'election' => $this->election,
        ]);
    }
}
