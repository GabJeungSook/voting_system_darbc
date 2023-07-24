<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Election;
use Illuminate\Http\Request;

class ApiMemberController extends Controller
{
    public function getMemberDetails($id){
        $election = Election::where('is_active', 1)->first();

        $member = Member::with('registered_member.election')
        ->find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }else{
            if($member->registered_member === null)
            {
                $data = [
                    'member_id' => $member->id,
                    'darbc_id' => $member->darbc_id,
                     'election_name' => $election->name,
                    // 'election_name' => $member->registered_member?->election->name,
                    'is_registered' => 0,
                    'is_active' => $member->is_active,
                    'has_voted' => 0,
                ];
            }else{
                $data = [
                    'member_id' => $member->id,
                    'darbc_id' => $member->darbc_id,
                    'election_name' => $member->registered_member->election->name,
                    'is_registered' => 1,
                    'is_active' => $member->is_active,
                    'has_voted' => $member->registered_member->has_voted,
                ];
            }


            return response()->json($data);
        }
    }
}
