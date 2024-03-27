<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use App\Models\Position;
use App\Models\Election;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use WireUi\Traits\Actions;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use DB;

class CastVote extends Component
{
    use Actions;

    public $record;
    public $currentStep = 1;
    public $steps;
    public $positions;
    public $election;
    public $votes;
    public $selectedImages = [];
    public $selectedCandidates = [];
    public $reviewVoteModal = false;
    public $reviewVoteStepsModal = false;

    public function mount()
    {
        $this->election = Election::where('is_active', true)->first();
        $this->positions = Position::where('election_id', $this->election->id)->get();
        $this->steps = $this->positions->count();
        $this->selectedImages = [];
        foreach ($this->positions as $step => $position) {
            $this->selectedImages[$step + 1] = [];
        }
    }

    public function getFileUrl($filename)
    {
        return Storage::url($filename);
    }

    // public function selectImage($imageId)
    // {
    //     $position = $this->positions[$this->currentStep - 1];
    //     $maximumWinners = $position->number_of_winners;

    //     if ($this->isMultipleSelectionStep()) {
    //         $selectedImagesCount = count($this->selectedImages[$this->currentStep]);

    //         if (in_array($imageId, $this->selectedImages[$this->currentStep])) {
    //             // Remove the image ID if it's already selected
    //             $this->selectedImages[$this->currentStep] = array_diff($this->selectedImages[$this->currentStep], [$imageId]);
    //         } else {
    //             // Add the image ID if it's not already selected and within the maximum limit
    //             if ($selectedImagesCount < $maximumWinners) {
    //                 $this->selectedImages[$this->currentStep][] = $imageId;
    //             }
    //         }
    //     } else {
    //         // Replace the selected image ID with the new one for single selection steps
    //         $this->selectedImages[$this->currentStep] = [$imageId];
    //     }
    // }

    public function selectImage($imageId)
    {
        $position = $this->positions[$this->currentStep - 1];
        $maximumWinners = $position->number_of_winners;

        $selectedImagesCount = count($this->selectedImages[$this->currentStep]);

        if (in_array($imageId, $this->selectedImages[$this->currentStep])) {
            // Remove the image ID if it's already selected
            $this->selectedImages[$this->currentStep] = array_diff($this->selectedImages[$this->currentStep], [$imageId]);
        } else {
            // Add the image ID if it's not already selected and within the maximum limit
            if ($selectedImagesCount < $maximumWinners) {
                $this->selectedImages[$this->currentStep][] = $imageId;
            }else{
                $this->dialog()->error(
                    $title = 'Dili pwede mag sobra sa '.$maximumWinners.' ang pilion.',
                );
            }
        }

        // if ($this->isMultipleSelectionStep()) {
        //     $selectedImagesCount = count($this->selectedImages[$this->currentStep]);

        //     if (in_array($imageId, $this->selectedImages[$this->currentStep])) {
        //         // Remove the image ID if it's already selected
        //         $this->selectedImages[$this->currentStep] = array_diff($this->selectedImages[$this->currentStep], [$imageId]);
        //     } else {
        //         // Add the image ID if it's not already selected and within the maximum limit
        //         if ($selectedImagesCount < $maximumWinners) {
        //             $this->selectedImages[$this->currentStep][] = $imageId;
        //         }else{
        //             $this->dialog()->error(
        //                 $title = 'Dili pwede mag sobra sa '.$maximumWinners.' ang pilion.',
        //             );
        //         }
        //     }
        // } else {
        //     // Replace the selected image ID with the new one for single selection steps
        //     $this->selectedImages[$this->currentStep] = [$imageId];
        // }
    }

    public function isMultipleSelectionStep()
    {
        $position = $this->positions[$this->currentStep - 1];
        $numberWinners = $position->number_of_winners;
        return $numberWinners > 1;
    }

    public function showBallot()
    {
        $selectedCandidates = [];
        foreach ($this->selectedImages as $step => $stepImages) {
            $position = $this->positions[$step - 1];
            foreach ($stepImages as $imageId) {
                $selectedCandidates[] = [
                    'candidate_id' => $imageId,
                    'position_id' => $position->id,
                ];
            }
        }
        $this->reviewVoteModal = true;
        $this->selectedCandidates =  $selectedCandidates;
        // dd($selectedCandidates);
    }

    public function showReview()
    {
        $selectedCandidates = [];
        foreach ($this->selectedImages as $step => $stepImages) {
            $position = $this->positions[$step - 1];
            foreach ($stepImages as $imageId) {
                $selectedCandidates[] = [
                    'candidate_id' => $imageId,
                    'position_id' => $position->id,
                ];
            }
        }
        $this->reviewVoteStepsModal = true;
        $this->selectedCandidates =  $selectedCandidates;
    }

    public function printBallot($member)
    {
        $reg_member = $member;
        $votes = $reg_member->votes()->get();
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $member_name = strtoupper($reg_member->first_name.' '.$reg_member->middle_name.' '.$reg_member->last_name);
        $connector = new NetworkPrintConnector($printerIp, $printerPort);
        $printer = new Printer($connector);
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer -> setEmphasis(true);
            $printer -> text("DARBC ELECTION 2023\n");
            $printer -> text("OFFICIAL BALLOT\n");
            $printer -> setEmphasis(false);
            $printer -> feed(1);
            $printer -> text(\Carbon\Carbon::parse(now())->format('F d, Y')."\n");
            $printer -> text(\Carbon\Carbon::parse($reg_member->registration_duration->time_start)->format('h:i:s A')." - ".\Carbon\Carbon::parse($reg_member->registration_duration->time_end)->format('h:i:s A'));
            $printer -> feed(3);
            foreach($this->positions as $position)
            {
                $printer -> setEmphasis(true);
                $printer -> text(strtoupper($position->name)."\n");
                $printer -> setEmphasis(false);
                $printer -> feed(1);

                  // Retrieve the votes for the current position and member
                // $votes = $member->votes()->where('position_id', $position->id)->get();

                 // Print the candidates for the position
                    foreach ($votes as $vote) {
                        if ($vote->position_id == $position->id)
                        {
                            $printer->text(strtoupper($vote->candidate?->first_name.' '.$vote->candidate?->middle_name.' '.$vote->candidate?->last_name) . "\n");
                        }
                    }
                    $printer->feed(2);
            }

            $printer -> feed(4);
            $printer -> setEmphasis(true);
            $printer -> text($member_name."\n");
            $printer -> text(strtoupper(auth()->user()->name));
            $printer -> setEmphasis(false);
            $printer -> feed(2);
            $printer -> cut();
            $printer -> close();
        } finally {
            $printer -> close();
        }
    }

    public function printCoupon($member)
    {
        $reg_member = $member;
        $votes = $reg_member->votes()->get();
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $member_name = strtoupper($reg_member->first_name.' '.$reg_member->middle_name.' '.$reg_member->last_name);
        $connector = new NetworkPrintConnector($printerIp, $printerPort);
        $printer = new Printer($connector);
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer -> setEmphasis(true);
            $printer -> text("DARBC ELECTION 2023\n");
            $printer -> text("VOTERS COUPON\n");
            $printer -> setEmphasis(false);
            $printer -> feed(1);
            $printer -> text(\Carbon\Carbon::parse(now())->format('F d, Y')."\n");
            $printer -> text(\Carbon\Carbon::parse($reg_member->registration_duration->time_start)->format('h:i:s A')." - ".\Carbon\Carbon::parse($reg_member->registration_duration->time_end)->format('h:i:s A'));
            $printer -> feed(3);

            $printer -> setEmphasis(true);
            $printer -> text($member_name);
            $printer -> feed(1);
            $printer -> text("VOTED AT ". strtoupper(auth()->user()->name));
            $printer -> setEmphasis(false);
            $printer -> feed(2);
            $printer -> cut();
            $printer -> close();
        } finally {
            $printer -> close();
        }
    }

    public function checkPrinterConnection()
    {
        try{
            $printerIp = auth()->user()->printer->ip_address;
            $printerPort = 9100;
            $connector = new NetworkPrintConnector($printerIp);
            $printer = new Printer($connector);
                try {
                    $printer -> text("DARBC ELECTION 2024\n");
                    $printer -> feed(1);
                    $printer -> cut();
                    $printer -> close();

                    return true;
                } catch(\Exception $e)
                {
                return false;
                }finally {
                    $printer -> close();
                }
        }catch(\Exception $e)
        {
            return false;
        }
    }


    public function saveVote()
    {
        if($this->checkPrinterConnection())
        {
            DB::beginTransaction();

            try {
                $registeredMemberId = $this->record->id;

                // Check if the registered_member_id already exists for the current election
                $existingVote = Vote::where('election_id', $this->election->id)
                                    ->where('registered_member_id', $registeredMemberId)
                                    ->exists();

                if ($existingVote) {
                    // Display an error message or handle the case where the member has already voted
                    Notification::make()
                    ->title('Member '.$this->record->first_name.' '.$this->record->middle_name.' '.$this->record->last_name. ' Already Voted!')
                    ->danger()
                    ->send();

                    // You can choose to redirect or return here, depending on your application flow
                    $this->reviewVoteModal = false;
                    return redirect()->route('voting.scan-qr');
                }
                if($this->selectedCandidates == null)
                {
                    Vote::create([
                        'election_id' => $this->election->id,
                        'user_id' => auth()->user()->id,
                        'registered_member_id' => $registeredMemberId,
                    ]);
                }else{
                    foreach ($this->selectedCandidates as $selectedCandidate) {
                        $positionId = $selectedCandidate['position_id'];
                        $candidateId = $selectedCandidate['candidate_id'];

                        // Create a new record in the database for each selected candidate
                        Vote::create([
                            'election_id' => $this->election->id,
                            'user_id' => auth()->user()->id,
                            'registered_member_id' => $registeredMemberId,
                            'position_id' => $positionId,
                            'candidate_id' => $candidateId === null ? null : $candidateId,
                        ]);
                    }
                }


                $this->record->has_voted = 1;
                $this->record->registration_duration->update(['time_end' => Carbon::now()->toTimeString()]);
                $this->record->save();

                DB::commit();
                $this->printBallot($this->record);
                //0.5 second interval before printCoupon
                sleep(0.5);
                $this->printCoupon($this->record);
                Notification::make()
                ->title('Member Successfully Voted!')
                ->success()
                ->send();


                $this->reviewVoteModal = false;
                //  route('voting.view-ballot', $record);
                return redirect()->route('voting.scan-qr');

            } catch (\Exception $e) {
                DB::rollback();

                // Handle the exception or display an error message
                Notification::make()
                ->title('Operation Failed!')
                ->body('Members vote cannot be saved.'. $e)
                ->danger()
                ->send();
            }
        }else{
            $this->dialog()->error(
                $title = 'Printer Disconnected',
                $description = 'Printer is not connected. Please check the printer connection.'
            );
        }

    }

    public function nextStep()
    {
        $this->currentStep = min($this->currentStep + 1, $this->steps);
    }

    public function previousStep()
    {
        $this->currentStep = max($this->currentStep - 1, 1);
    }

    public function render()
    {
        return view('livewire.voting.cast-vote');
    }
}
