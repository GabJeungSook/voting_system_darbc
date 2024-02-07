<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class MemberImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $names = explode(',', $row['member_name']);
        $darbc_id = $row['darbc_id'];
        $lastName = trim($names[0]);
        $middleName = explode(' ', $names[1]);
        $middle = $middleName[0];
        $firstName = trim($names[1]);

        if ($row['restrictions'] === '' || $row['restrictions'] === null || empty($row['restrictions'])) {
            $restriction = '';
        } else {
            $restriction = $row['restrictions'];
        }
        $spa = $row['spa'];

        $member = new Member;
        $member->darbc_id = $darbc_id;
        $member->last_name = $lastName;
        $member->first_name = $firstName;
        $member->middle_name = $middle;
        $member->full_name = $row['member_name'];
        $member->spa = $spa;
        $member->restriction = $restriction;
        if (strpos($darbc_id, '.') !== false || ($restriction != null || $restriction != '')) {
            $member->is_active = 0;
        }else{
            $member->is_active = 1;
        }
        $member->save();
        return $member;
    }
}
