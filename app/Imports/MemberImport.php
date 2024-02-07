<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

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
        $restriction = $row['restrictions'];
        $spa = $row['spa'];


        if(Member::count() === 0)
        {
            dd('empty');
            $member = new Member;
            $member->darbc_id = $darbc_id;
            $member->last_name = $lastName;
            $member->first_name = $firstName;
            $member->middle_name = $middle;
            $member->spa = $spa;
            $member->restriction = $restriction;
            if (strpos($darbc_id, '.') !== false || ($restriction != null || $restriction != '')) {
                $member->is_active = 0;
            }else{
                $member->is_active = 1;
            }
            $member->save();
        }else{
            $member = Member::where('darbc_id', $darbc_id)->first();

            if($member != null)
            {
                if($member->last_name != $lastName || $member->first_name != $firstName || $member->middle_name != $middle || $member->restriction != $restriction || $member->spa != $spa)
                {
                    $member->last_name = $lastName;
                    $member->first_name = $firstName;
                    $member->middle_name = $middle;
                    $member->spa = $spa;
                    $member->restriction = $restriction;
                    if (strpos($member->darbc_id, '.') !== false || ($member->restriction != null || $member->restriction != '')) {
                        $member->is_active = 0;
                    }else{
                        $member->is_active = 1;
                    }
                    $member->save();
                }
                else
                {
                    return null;
                }
            }
        }
    }
}
