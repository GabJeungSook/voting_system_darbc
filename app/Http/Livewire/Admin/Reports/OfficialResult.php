<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Election;

class OfficialResult extends Component
{
    public $elections;
    public $report_get;

    public function mount()
    {
        $this->elections = Election::get();
    }
    public function render()
    {
        return view('livewire.admin.reports.official-result');
    }
}
