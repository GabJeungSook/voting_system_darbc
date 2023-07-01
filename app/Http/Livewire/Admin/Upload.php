<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use League\Csv\Reader;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;

class Upload extends Component
{
    use WithFileUploads;
    public $members;

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
    }

    public function render()
    {
        return view('livewire.admin.upload');
    }
}
