<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;

class OfficialResult extends Component
{
    public $election;
    public $reportget;
    public $report_show = false;

    public function generateReport()
    {
        $this->election = Election::find($this->reportget);
        $this->report_show = true;
    }

    public function render()
    {
        return view('livewire.admin.reports.official-result',[
            'elections' => Election::get(),
        ]);
    }
}
