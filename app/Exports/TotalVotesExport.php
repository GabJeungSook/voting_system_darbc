<?php

namespace App\Exports;

use App\Models\Vote;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class TotalVotesExport implements FromView
{
    public $election;
    public $voting_tellers;

    public function __construct($election, $voting_tellers)
    {
        $this->voting_tellers = $voting_tellers;
        $this->election = $election;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.total-votes', [
            'election' => $this->election,
            'voting_tellers' => $this->voting_tellers,
        ]);
    }
}
