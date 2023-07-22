<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use League\Csv\Reader;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use WireUi\Traits\Actions;

class Upload extends Component
{
    use WithFileUploads;
    use Actions;
    public $members;
    public $without_profile;
    public $with_spa;
    public $with_garnishment;
    public $with_court_order;
    public $non_member_w_court_order;
    public $bad_standing;
    public $no_replacement;
    public $w_mediation_proceeding;

    public function uploadMember()
    {
        $csvContents = Storage::get($this->members->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $firstName = $csvRecord[3];
            if (substr($firstName, -1) === '.') {
                $firstName = substr($firstName, 0, -2);
            }

            $middleName = $csvRecord[2];
            if (strlen($middleName) > 2) {
                $middleName = null;
            }

            $restriction = trim($csvRecord[5]);
            if (empty($restriction)) {
                $restriction = null;
            }

            Member::create([
                'darbc_id' => $csvRecord[0],
                'last_name' => $csvRecord[1],
                'middle_name' => $middleName,
                'first_name' => $firstName,
                'restriction' => $csvRecord[4],
                'full_name' => $restriction,
            ]);
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members was successfully uploaded'
        );
    }

    public function uploadMemberWithoutProfile()
    {
        $csvContents = Storage::get($this->without_profile->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $member->restriction = "Member Without Profile";
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members without profiles successfully uploaded'
        );
    }

    public function uploadMemberWithSPA()
    {
        $csvContents = Storage::get($this->with_spa->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $member->restriction = "Member With SPA";
            $member->spa = $csvRecord[1];
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members with SPA successfully uploaded'
        );
    }


    public function uploadMemberWithGarnishment()
    {
        $csvContents = Storage::get($this->with_garnishment->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $member->restriction = "Member With Garnishment";
            $member->is_active = 0;
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members with garnishment successfully uploaded'
        );
    }

    public function uploadMemberWithCourtOrder()
    {
        $csvContents = Storage::get($this->with_court_order->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $member->restriction = "Member With Court Order";
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members with court order successfully uploaded'
        );
    }

    public function uploadNonMemberWithCourtOrder()
    {
        $csvContents = Storage::get($this->non_member_w_court_order->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $member->restriction = "Non-Member With Court Order";
            $member->is_active = 0;
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Non-Members with court order successfully uploaded'
        );
    }

    public function uploadMemberWithBadStanding()
    {
        $csvContents = Storage::get($this->bad_standing->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $second_column = trim($csvRecord[2]);
            $third_column = trim($csvRecord[3]);
            if(empty($second_column))
            {
                $member->restriction = $csvRecord[1];
            }elseif(!empty($second_column) && empty($third_column))
            {
                $member->restriction = $csvRecord[1].' ('.$csvRecord[2].')';
            }elseif(!empty($second_column) && !empty($third_column))
            {
                $member->restriction = $csvRecord[1].' ('.$csvRecord[2].') - '.strtoupper($csvRecord[3]);
            }
            $member->is_active = 0;
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members with bad standing successfully uploaded'
        );
    }

    public function uploadMemberHOLDNoReplacement()
    {
        $csvContents = Storage::get($this->no_replacement->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $member->restriction = $csvRecord[1];
            $member->is_active = 0;
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members with HOLD (No Replacement) successfully uploaded'
        );
    }

    public function uploadMemberHOLDMediationProceeding()
    {
        $csvContents = Storage::get($this->w_mediation_proceeding->getClientOriginalName());
        $csvReader = Reader::createFromString($csvContents);
        $csvRecords = $csvReader->getRecords();

        foreach ($csvRecords as $csvRecord) {
            $member = Member::where('darbc_id', $csvRecord[0])->first();
            $member->restriction = $csvRecord[1];
            $member->is_active = 0;
            $member->save();
        }
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Members with HOLD (Mediation Proceeding) successfully uploaded'
        );
    }

    public function render()
    {
        return view('livewire.admin.upload');
    }
}
