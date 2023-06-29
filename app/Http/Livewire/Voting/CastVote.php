<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use App\Models\Position;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use WireUi\Traits\Actions;
use DB;

class CastVote extends Component
{
    use Actions;

    public $record;
    public $currentStep = 1;
    public $steps;
    public $positions;
    public $election;
    public $selectedImages = [];
    public $selectedCandidates = [];
    public $reviewVoteModal = false;

    public function mount()
    {
        $this->positions = Position::get();
        $this->election = Election::where('is_active', true)->first();
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

        if ($this->isMultipleSelectionStep()) {
            $selectedImagesCount = count($this->selectedImages[$this->currentStep]);

            if (in_array($imageId, $this->selectedImages[$this->currentStep])) {
                // Remove the image ID if it's already selected
                $this->selectedImages[$this->currentStep] = array_diff($this->selectedImages[$this->currentStep], [$imageId]);
            } else {
                // Add the image ID if it's not already selected and within the maximum limit
                if ($selectedImagesCount < $maximumWinners) {
                    $this->selectedImages[$this->currentStep][] = $imageId;
                }
            }
        } else {
            // Replace the selected image ID with the new one for single selection steps
            $this->selectedImages[$this->currentStep] = [$imageId];
        }
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

    public function saveVote()
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
                ->title('Member Already Voted!')
                ->body('Member votes cannot be more than once.')
                ->error()
                ->send();

                // You can choose to redirect or return here, depending on your application flow
                $this->reviewVoteModal = false;
                return redirect()->route('voting.voting-module');
            }

            foreach ($this->selectedCandidates as $selectedCandidate) {
                $positionId = $selectedCandidate['position_id'];
                $candidateId = $selectedCandidate['candidate_id'];

                // Create a new record in the database for each selected candidate
                Vote::create([
                    'election_id' => $this->election->id,
                    'user_id' => auth()->user()->id,
                    'registered_member_id' => $registeredMemberId,
                    'position_id' => $positionId,
                    'candidate_id' => $candidateId,
                ]);
            }

            DB::commit();

            // Display a success message or perform any other necessary actions after saving the votes


            Notification::make()
            ->title('Member Successfully Voted!')
            ->success()
            ->send();



            // Redirect to the desired page after successful voting
            $this->reviewVoteModal = false;
            return redirect()->route('voting.voting-module');

        } catch (\Exception $e) {
            DB::rollback();

            // Handle the exception or display an error message
            Notification::make()
            ->title('Operation Failed!')
            ->body('Members vote cannot be saved.')
            ->danger()
            ->send();
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
