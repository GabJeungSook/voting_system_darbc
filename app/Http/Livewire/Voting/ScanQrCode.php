<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\RegisteredMember;
use Illuminate\Support\Facades\DB;
use App\Models\RegistrationDuration;
use Carbon\Carbon;

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
            DB::beginTransaction();
            $registrationDuration = RegistrationDuration::where('registered_member_id', $member->id)->first();
            if ($registrationDuration) {
                // If a record exists, update the time_start
                $registrationDuration->update(['time_start' => Carbon::now()->toTimeString()]);
            } else {
                // If a record doesn't exist, create a new one
                $registrationDuration = RegistrationDuration::create([
                    'registered_member_id' =>  $member->id,
                    'time_start' => Carbon::now()->toTimeString(),
                ]);
            }
            DB::commit();
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
