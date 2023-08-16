<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OfficialResultExport implements FromView
{
    public $positions;
    public $election;

    public function __construct($positions, $election)
    {
        $this->positions = $positions;

        $this->election = $election;

        foreach ($this->positions as $position) {
            $position->candidates->each(function ($candidate) {
                $candidate->vote_count = $candidate->votes()->count();
            });
        }
    }

    public function view(): View
    {
        return view('exports.official-result', [
            'positions' => $this->positions,
            'election' => $this->election,
        ]);
    }
}
