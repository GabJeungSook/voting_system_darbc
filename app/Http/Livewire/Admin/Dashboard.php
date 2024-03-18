<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\RegisteredMember;
use App\Models\Election;
use App\Models\User;
use App\Models\VoidedMember;
use App\Models\Vote;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class Dashboard extends Component
{
    public $tellers;
    public $voting_tellers;
    public $election;
    public $total_registered_members;
    public $total_voters;
    public $total_overall_votes;
    public $total_voided_votes;
    // protected $listeners = ['backupCompleted'];

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->tellers = User::where('role_id', 2)->get();
        $this->voting_tellers = User::where('role_id', 3)->get();
        $this->total_registered_members = RegisteredMember::where('election_id', $this->election?->id)->where('is_voided', false)->count();
        $this->total_voters = Vote::where('election_id', $this->election?->id)->distinct('registered_member_id')->count();
        $this->total_voided_votes = VoidedMember::where('type', 'VOTING')->count();
        $this->total_overall_votes = $this->total_voters + $this->total_voided_votes;
        // $this->total_voters = Vote::where('election_id', $this->election?->id)->count();
    }

    public function testPrinter()
    {
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $connector = new NetworkPrintConnector($printerIp);
        $printer = new Printer($connector);
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("DARBC 2024 ELECTION\n");
            $printer->text(auth()->user()->name);
            $printer->feed(4);
            $printer->text("Printer is good to go!");
            $printer->feed(4);
            $printer->cut();
            $printer->close();
        } finally {
            $printer -> close();
        }
    }

    public function redirectToLiveResult()
    {
        return redirect()->route('admin.live-result');
    }

    // public function backupDatabase()
    // {
    //     try{
    //         Artisan::call('snapshot:create');
    //     }catch(\Exception $e){
    //         $this->emit('backupCompleted');
    //     }

    // }

    public function backupCompleted()
        {
            $backupPath = database_path('snapshots');

            // Get all files in the backup directory
            $backupFiles = File::files($backupPath);

            // Sort the files by modification time in descending order
            $backupFiles = collect($backupFiles)->sortByDesc(function ($file) {
                return $file->getMTime();
            })->values()->all();

            // Check if there are any backup files
            if (!empty($backupFiles)) {
                // Get the path of the latest backup file
                $latestBackupPath = $backupFiles[0]->getRealPath();

                // Download the latest backup file
                return response()->download($latestBackupPath);
            } else {
                // No backup files found
                return response()->json(['error' => 'No backup files available'], 404);
            }
        }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
