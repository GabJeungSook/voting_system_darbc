<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use App\Models\RegisteredMember;
use WireUi\Traits\Actions;

class ScanQrCode extends Component
{
    public $scannedCode;
    use Actions;

    public function verifyCode()
    {
    $member = RegisteredMember::where('qr_code', $this->scannedCode)->first();
    if ($member) {
        if($member->votes()->exists())
        {
            $this->reset('scannedCode');
            $this->dialog()->error(
                $title = 'Code Already Used! ',
                $description = strtoupper($member->first_name.' '.$member->middle_name.' '.$member->last_name).' already voted.'
            );
        }else{
            $this->reset('scannedCode');
            return redirect()->route('voting.cast-vote', $member);
        }

    } else {
        $this->reset('scannedCode');
        $this->dialog()->error(
            $title = 'Code Not Found! ',
            $description = 'Member is not registered.'
        );
    }
    }

    public function render()
    {
        return view('livewire.voting.scan-qr-code');
    }
}